<?php
require_once 'config.php';

function getBooks() {
    global $conn;
    $sql = "SELECT * FROM books";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getBookById($id) {
    global $conn;
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function addBook($title, $author, $year, $description, $image) {
    global $conn;
    $sql = "INSERT INTO books (title, author, year,  description, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssiss", $title, $author, $year, $description, $image);
    return mysqli_stmt_execute($stmt);
}

function searchBooks($keyword) {
    global $conn;
    $keyword = "%{$keyword}%";
    $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}


function addBorrower($nameBorrowers, $email, $date, $address, $book_id) {
    global $conn;
    $status = 'Dipinjam';

    // Query untuk menyimpan data ke tabel borrowers
    $borrowerSql = "INSERT INTO borrowers (nameBorrowers, email, date, address) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($borrowerSql);
    $stmt->bind_param("ssss", $nameBorrowers, $email, $date, $address);

    if ($stmt->execute()) {
        // Query untuk memperbarui status buku berdasarkan ID buku
        $bookSql = "UPDATE books SET status=?, nameBorrowers=?, email=?, date=?, address=? WHERE id=?";
        $stmt2 = $conn->prepare($bookSql);
        $stmt2->bind_param("sssssi", $status, $nameBorrowers, $email, $date, $address, $book_id);
        

        if ($stmt2->execute()) {
            return true;
        } else {
            echo "Error: " . $bookSql . "<br>" . $conn->error;
            return false;
        }
    } else {
        echo "Error: " . $borrowerSql . "<br>" . $conn->error;
        return false;
    }
}

function getBorrowerById($id) {
    global $conn;
    $sql = "SELECT * FROM borrowers WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $borrower = mysqli_fetch_assoc($result);
    return $borrower ? $borrower : null;
}

function backBooks() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

        if ($book_id > 0) {
            // Update status buku dan hapus informasi peminjam
            $status = 'Pinjam';
            $empty = '';

            // Query untuk memperbarui status dan mengosongkan kolom peminjam
            $sql = "UPDATE books SET status=?, nameBorrowers=?, email=?, date=?, address=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $status, $empty, $empty, $empty, $empty, $book_id);

            if ($stmt->execute()) {
                // Redirect kembali ke halaman admin atau halaman lain yang diinginkan
                header("Location: admin.php");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }

            $stmt->close();
        } else {
            echo "Invalid book ID.";
        }
    }
}

function editBook() {
    global $conn; // Ensure $conn is available in this function

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;
        $author = isset($_POST['author']) ? $_POST['author'] : '';
        $year = isset($_POST['year']) ? intval($_POST['year']) : 0; // Ensure year is an integer
        $description = isset($_POST['description']) ? $_POST['description'] : '';

        if ($book_id > 0 && !empty($author) && $year > 0 && !empty($description)) {
            // Query untuk memperbarui data buku
            $sql = "UPDATE books SET author=?, year=?, description=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }

            // Binding parameters
            if (!$stmt->bind_param("sisi", $author, $year, $description, $book_id)) {
                die('Bind param failed: ' . htmlspecialchars($stmt->error));
            }

            // Executing the statement
            if (!$stmt->execute()) {
                die('Execute failed: ' . htmlspecialchars($stmt->error));
            }

            // Redirect to admin page after successful update
            header("Location: admin.php");
            exit();
        } else {
            echo "Please fill in all fields correctly.";
        }
    }
}

?>

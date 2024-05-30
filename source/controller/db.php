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


function addBorrower($nameBorrowers, $email, $date, $return_date, $address, $cardIdentity, $book_id) {
    global $conn;
    $status = 'Dipinjam';

    // Query untuk memeriksa status pengguna di tabel borrowers
    $checkStatusSql = "SELECT statusUser FROM borrowers WHERE nameBorrowers = ?";
    $stmtCheck = $conn->prepare($checkStatusSql);
    $stmtCheck->bind_param("s", $nameBorrowers);
    $stmtCheck->execute();
    $stmtCheck->bind_result($statusUser);
    $stmtCheck->fetch();
    $stmtCheck->close();

    // Jika pengguna dibanned, tolak peminjaman
    if ($statusUser === 'Banned') {
        echo "<script>alert('User ini dilarang meminjam buku.'); window.location.href = 'index.php';</script>";
        return false;
    } else {

    // Jika pengguna tidak dibanned, lanjutkan proses peminjaman
    $borrowerSql = "INSERT INTO borrowers (nameBorrowers, email, date, return_date, address, cardIdentity) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($borrowerSql);
    $stmt->bind_param("ssssss", $nameBorrowers, $email, $date, $return_date, $address, $cardIdentity);

    if ($stmt->execute()) {
        // Query untuk memperbarui status buku berdasarkan ID buku
        $bookSql = "UPDATE books SET status=?, nameBorrowers=?, email=?, date=?, return_date=?, address=?, cardIdentity=? WHERE id=?";
        $stmt2 = $conn->prepare($bookSql);
        $stmt2->bind_param("sssssssi", $status, $nameBorrowers, $email, $date, $return_date, $address, $cardIdentity, $book_id);

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

            // Query untuk memeriksa dan mendapatkan nama peminjam berdasarkan ID buku
            $sqlGetBorrower = "SELECT nameBorrowers FROM books WHERE id = ?";
            $stmtGetBorrower = $conn->prepare($sqlGetBorrower);
            $stmtGetBorrower->bind_param("i", $book_id);
            $stmtGetBorrower->execute();
            $stmtGetBorrower->store_result(); // Simpan hasil query untuk pengecekan
            $stmtGetBorrower->bind_result($nameBorrowers);
            $stmtGetBorrower->fetch();
            $stmtGetBorrower->close();

            // Jika nama peminjam ditemukan, perbarui status pengguna
            if ($stmtGetBorrower > 0) {
                $sql2 = "UPDATE borrowers SET statusUser=? WHERE nameBorrowers=?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("ss", $empty, $nameBorrowers);

                if ($stmt2->execute()) {
                    $stmt2->close();
                } else {
                    echo "Error updating borrower record: " . $conn->error;
                    return false; // Menghentikan eksekusi jika terjadi kesalahan
                }
            } else {
                echo "Borrower name not found.";
                return false; // Menghentikan eksekusi jika nama peminjam tidak ditemukan
            }

            // Query untuk memperbarui status buku dan informasi peminjam
            $sql = "UPDATE books SET status=?, nameBorrowers=?, email=?, date=?, address=?, cardIdentity=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $status, $empty, $empty, $empty, $empty, $empty, $book_id);

            if ($stmt->execute()) {
                $stmt->close();

                // Redirect kembali ke halaman admin atau halaman lain yang diinginkan
                header("Location: admin.php");
                exit();
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Invalid book ID.";
        }
    }
}


function late() {
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $book_id = isset($_POST['book_id']) ? intval($_POST['book_id']) : 0;

        if ($book_id > 0) {
            // Update status buku dan hapus informasi peminjam
            $statusUser = 'Banned';

            // Query untuk mengambil nama peminjam berdasarkan book_id
            $sqlGetBorrower = "SELECT nameBorrowers FROM books WHERE id = ?";
            $stmtGetBorrower = $conn->prepare($sqlGetBorrower);
            $stmtGetBorrower->bind_param("i", $book_id);
            $stmtGetBorrower->execute();
            $stmtGetBorrower->bind_result($nameBorrowers);
            $stmtGetBorrower->fetch();
            $stmtGetBorrower->close();

            if ($nameBorrowers) {
                    // Query untuk memperbarui status pengguna jika nama peminjam cocok
                    $sql2 = "UPDATE borrowers SET statusUser=? WHERE nameBorrowers=?";
                    $stmt2 = $conn->prepare($sql2);
                    $stmt2->bind_param("ss", $statusUser, $nameBorrowers);

                    if ($stmt2->execute()) {
                        $stmt2->close();

                        // Redirect kembali ke halaman admin atau halaman lain yang diinginkan
                        header("Location: admin.php");
                        exit();
                    } else {
                        echo "Error updating borrower record: " . $conn->error;
                    }

            } else {
                echo "Borrower name not found.";
            }
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

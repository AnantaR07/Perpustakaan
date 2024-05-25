<?php
require_once '../../source/controller/db.php';

// Dapatkan ID buku dari parameter URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id > 0) {
    // Ambil data buku berdasarkan ID
    $book = getBookById($book_id);
} else {
    echo "Invalid book ID.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $address = $_POST['address'];
    $book_id = intval($_POST['book_id']);

    // Memanggil fungsi addBorrower
    if (addBorrower($name, $email, $date, $address, $book_id)) {
        echo "Peminjam berhasil ditambahkan dan status buku diperbarui.";
        header("Location: index.php");
        exit();
    } else {
        echo "Terjadi kesalahan saat menambahkan peminjam atau memperbarui status buku.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($book['title']); ?> - Detail Buku</title>
    <link rel="stylesheet" href="../../source/css/styles.css">
</head>
<body>
    <?php include __DIR__ . '/../../view/component/navbar.php'; ?>

    <div class="containerFlex">
        <div class="con-left">
            <h1>Identitas Peminjam</h1>
            <form action="borrowerForm.php?id=<?php echo $book_id; ?>" method="POST">
                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                <div class="form-group">
                    <label for="name">Nama:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="date">Tanggal Peminjaman:</label>
                    <input type="date" id="date" name="date" required>
                </div>

                <div class="form-group">
                    <label for="address">Alamat:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" value="Submit">
                </div>
            </form>
        </div>
        <div class="con-right">
            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="Book Image">
            <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        </div>
    </div>
    
</div>

<?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

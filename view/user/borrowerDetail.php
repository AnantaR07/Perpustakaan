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
            <form action="index.php" method="POST">
                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                <div class="form-group">
                    <label for="name">Nama:</label>
                    <p><?php echo htmlspecialchars($book['nameBorrowers']); ?></p>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <p><?php echo htmlspecialchars($book['email']); ?></p>
                </div>

                <div class="form-group">
                    <label for="date">Tanggal Peminjaman:</label>
                    <p><?php echo htmlspecialchars($book['date']); ?></p>
                </div>

                <div class="form-group">
                    <label for="address">Alamat:</label>
                    <p><?php echo htmlspecialchars($book['address']); ?></p>
                </div>

                <div class="form-group">
                    <input type="submit" value="Kembali">
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

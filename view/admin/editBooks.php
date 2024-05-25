<?php
require_once '../../source/controller/db.php'; // Include the db.php file

// Dapatkan ID buku dari parameter URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id > 0) {
    // Ambil data buku berdasarkan ID
    $book = getBookById($book_id);
} else {
    echo "Invalid book ID.";
    exit();
}

// Memanggil fungsi editBook jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_book'])) {
    editBook();
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
<?php include __DIR__ . '/../../view/component/navbarAdmin.php'; ?>

    <div class="container">
        <div class="con-center">
        <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="Book Image">
        </div>
        <h1>Edit Buku</h1>
            <form action="" method="POST"> <!-- Form submitted to the same page -->
                <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                <div class="info">
                    <div class="label">Penulis</div>
                    <div class="value">: <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>"></div>
                </div>
                <div class="info">
                    <div class="label">Tahun</div>
                    <div class="value">: <input type="number" name="year" value="<?php echo htmlspecialchars($book['year']); ?>"></div>
                </div>
                <div class="description">
                    <p><strong>Deskripsi :</strong></p>
                    <textarea name="description"><?php echo htmlspecialchars($book['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <button class="button-back" type="submit" name="update_book">Update Book</button>
                </div>
            </form>
    </div>

    <?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

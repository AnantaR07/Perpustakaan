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

    <div class="container">
        <div class="con-center">
        <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="Book Image">
        </div>
        <div class="info">
            <div class="label">Author</div>
            <div class="value">: <?php echo htmlspecialchars($book['author']); ?></div>
        </div>
        <div class="info">
            <div class="label">Year</div>
            <div class="value">: <?php echo htmlspecialchars($book['year']); ?></div>
        </div>
    <div class="description">
        <p><strong>Description :</strong></p>
        <p><?php echo htmlspecialchars($book['description']); ?></p>
    </div>
    </div>

    <?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

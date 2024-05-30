<?php
require_once '../../source/controller/db.php';
$books = getBooks();


// Ambil keyword dari parameter URL
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

$books = [];
if (!empty($keyword)) {
    // Panggil fungsi searchBooks untuk mencari buku berdasarkan keyword
    $books = searchBooks($keyword);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library</title>
    <link rel="stylesheet" href="../../source/css/styless.css">
</head>
<body>

<?php include __DIR__ . '/../../view/component/navbarAdmin.php'; ?>

<div class="container">
        <h1>Hasil Pencarian untuk "<?php echo htmlspecialchars($keyword); ?>"</h1>
        <form action="bookSearchAdmin.php" method="get">
            <input type="text" name="keyword" placeholder="Pencarian berdasarkan judul buku/penulis">
            <input type="submit" value="Cari">
        </form>


        <?php if (empty($books)): ?>
            <p>Tidak ada buku yang ditemukan.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td><a href="bookDetailAdmin.php?id=<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['title']); ?></a></td>
                            <td><?php echo htmlspecialchars($book['author']); ?></td>
                            <td><?php echo htmlspecialchars($book['description']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

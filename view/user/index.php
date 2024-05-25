<?php
require_once '../../source/controller/db.php';
$books = getBooks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library</title>
    <link rel="stylesheet" href="../../source/css/styles.css">
</head>
<body>

<?php include __DIR__ . '/../../view/component/navbar.php'; ?>

    <div class="container">
        <h1>Library Books</h1>
        <form action="booksearch.php" method="get">
            <input type="text" name="keyword" placeholder="Pencarian berdasarkan judul buku/penulis">
            <input type="submit" value="Cari">
        </form>
        <table>
            <thead>
                <tr>
                    <th>Buku</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($books as $book): ?>
                    <tr>
                        <td><img src="<?php echo htmlspecialchars($book['image']); ?>" width="150" height="150"></td>
                        <td><a href="bookDetail.php?id=<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['title']); ?></a></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['year']); ?></td>

                        <?php
                            // Tentukan teks tombol dan URL tujuan berdasarkan status buku
                            if ($book['status'] == 'Dipinjam') {
                                    $buttonText = 'Detail';
                                    $buttonUrl = 'borrowerDetail.php?id=' . $book['id'];
                            } else {
                                    $buttonText = 'Belum Dipinjam';
                                    $buttonUrl = 'borrowerForm.php?id=' . $book['id'];
                            }
                        ?>
                        <td><button class="button" onclick="window.location.href='<?php echo $buttonUrl; ?>'"><?php echo $buttonText; ?></button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

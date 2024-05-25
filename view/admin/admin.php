<?php
session_start();
// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: loginPage.php");
    exit();
}

// Cek apakah pengguna memiliki peran admin
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
    // Jika bukan admin, alihkan ke halaman lain atau tampilkan pesan kesalahan
    header("Location: admin.php");
    exit();
}

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

<?php include __DIR__ . '/../../view/component/navbarAdmin.php'; ?>

<div class="container">
    <h1>Library Books</h1>
    <form action="bookSearchAdmin.php" method="get">
        <input type="text" name="keyword" placeholder="Pencarian berdasarkan judul buku/penulis">
        <input type="submit" value="Cari">
    </form>
    <button class="button-add" onclick="window.location.href='<?php echo 'addBook.php'; ?>'">Tambah Buku</button>
    <table>
        <thead>
            <tr>
                <th>Buku</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($books as $book): ?>
                <tr>
                    <td><img src="<?php echo htmlspecialchars($book['image']); ?>" width="150" height="150"></td>
                    <td><a href="bookDetailAdmin.php?id=<?php echo $book['id']; ?>"><?php echo htmlspecialchars($book['title']); ?></a></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo htmlspecialchars($book['year']); ?></td>

                    <?php
                        // Tentukan teks tombol dan URL tujuan berdasarkan status buku
                        if ($book['status'] == 'Dipinjam') {
                                $buttonText = 'Detail';
                                $buttonUrl = 'borrowerAdminDetail.php?id=' . $book['id'];
                        } else {
                                $buttonText = 'Belum Dipinjam';
                                $buttonUrl = 'borrowerAdminDetail.php?id=' . $book['id'];
                        }
                    ?>
                    <td><button class="button" onclick="window.location.href='<?php echo $buttonUrl; ?>'"><?php echo $buttonText; ?></button></td>
                    <td><button class="button-add" onclick="window.location.href='editBooks.php?id=<?php echo $book['id']; ?>'">Edit Buku</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

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

// if ($user_id > 0) {
//     // Ambil data buku berdasarkan ID
//     $user = getBookById($book_id);
// } else {
//     echo "Invalid user ID.";
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'returned':
                backBooks();
                header("Location: admin.php");
                exit();
            case 'late':
                late();
                header("Location: borrowerAdminDetail.php");
                exit();
            default:
                echo "Aksi tidak dikenali.";
                break;
        }
    } else {
        echo "Aksi tidak dipilih.";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($book['title']); ?> - Detail Buku</title>
    <link rel="stylesheet" href="../../source/css/styless.css">
</head>
<body>
<?php include __DIR__ . '/../../view/component/navbarAdmin.php'; ?>

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
                    <label for="date">Tanggal Pengembalian:</label>
                    <p><?php echo htmlspecialchars($book['return_date']); ?></p>
                </div>

                <div class="form-group">
                    <label for="address">Alamat:</label>
                    <p><?php echo htmlspecialchars($book['address']); ?></p>
                </div>

                <div class="form-group">
                    <label for="cardIdentity">Kartu Identitas:</label>
                    <img src="<?php echo htmlspecialchars($book['cardIdentity']); ?>" alt="Kartu Identitas" style="width: 300px; height: auto;">
                </div>
            </form>

            <form action="" method="POST" id="dropdownForm">
            <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($book_id); ?>">
            <div class="form-group">
                <label for="actionDropdown">Pilih Aksi:</label>
                <select name="action" id="actionDropdown">
                    <option value="returned">Buku Sudah Dikembalikan!</option>
                    <option value="late">User Terlambat Mengembalikan Buku!</option>
                </select>
            </div>
            <button class="button-add" type="submit">Submit</button>
            </form>

            <br>
            <button class="btn-back" onclick="window.location.href='<?php echo 'admin.php'; ?>'">Kembali</button>
        </div>
        <div class="con-right">
            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="Book Image">
            <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        </div>
    </div>

<?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

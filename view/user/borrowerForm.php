<?php
require_once '../../source/controller/db.php';

// Dapatkan ID buku dari parameter URL
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($book_id > 0) {
    // Ambil data buku berdasarkan ID
    $book = getBookById($book_id);
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $return_date = date('Y-m-d', strtotime($date . ' + 10 days'));
    $address = $_POST['address'];
    $book_id = intval($_POST['book_id']);

    // Mengambil data file yang diunggah
    if (isset($_FILES['identity_card']) && $_FILES['identity_card']['error'] == UPLOAD_ERR_OK) {
        $identity_card = $_FILES['identity_card']['name'];
        $target_dir = "../../source/img/identity/";
        $target_file = $target_dir . basename($identity_card);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Memeriksa apakah file adalah gambar sebenarnya atau bukan
        $check = getimagesize($_FILES['identity_card']['tmp_name']);
        if ($check !== false) {
            // Cek ukuran file (maks 5MB)
            if ($_FILES['identity_card']['size'] > 5000000) {
                echo "<script>alert('Maaf, file Anda terlalu besar.');</script>";
            }
            // Izinkan format file tertentu
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                echo "<script>alert('Maaf, hanya JPG, JPEG, PNG & GIF yang diperbolehkan.');</script>";
            }

            // Jika semua cek terpenuhi, pindahkan file ke direktori target
            if (move_uploaded_file($_FILES['identity_card']['tmp_name'], $target_file)) {
                // Memanggil fungsi addBorrower
                if (addBorrower($name, $email, $date, $return_date, $address, $target_file, $book_id)) {
                    echo "Peminjam berhasil ditambahkan dan status buku diperbarui.";
                    header("Location: index.php");
                    exit();
                } else {
                    echo "<script>alert('Terjadi kesalahan saat menambahkan peminjam atau memperbarui status buku.');</script>";
                }
            } else {
                echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file Anda.');</script>";
            }
        } else {
            echo "<script>alert('File yang diunggah bukan gambar.');</script>";
        }
    } else {
        echo "<script>alert('Tidak ada file yang diunggah atau terjadi kesalahan saat mengunggah.'); </script>";
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
    <?php include __DIR__ . '/../../view/component/navbar.php'; ?>

    <div class="containerFlex">
        <div class="con-left">
            <h1>Masukkan Identitas</h1>
            <form action="borrowerForm.php?id=<?php echo $book_id; ?>" method="POST" enctype="multipart/form-data">
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
                    <input type="date" id="date" name="date" required onchange="calculateReturnDate()">
                </div>

                <div class="form-group">
                    <label for="return_date">Tanggal Kembali:</label>
                    <input type="text" id="return_date" name="return_date" readonly>
                </div>

                <div class="form-group">
                    <label for="address">Alamat:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>

                <div class="form-group">
                    <label for="identity_card" class="custom-file-upload">Kartu Identitas (KTP/KTM):</label>
                    <input type="file" id="identity_card" name="identity_card" required onchange="previewImage(event)">
                    <img id="preview" src="#" alt="Pratinjau Gambar" style="display: none;">
                </div>

                <div class="form-group">
                    <input type="submit" value="Submit">
                </div>
                <button class="btn-back" onclick="window.location.href='<?php echo '../user/index.php'; ?>'">Kembali</button>
            </form>
            <script>
            function calculateReturnDate() {
            const dateInput = document.getElementById('date').value;
            const returnDateInput = document.getElementById('return_date');
            if (dateInput) {
                const borrowDate = new Date(dateInput);
                borrowDate.setDate(borrowDate.getDate() + 10);
                const day = String(borrowDate.getDate()).padStart(2, '0');
                const month = String(borrowDate.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
                const year = String(borrowDate.getFullYear()).slice(2); // Get last two digits of the year
                const returnDate = `${day}/${month}/${year}`;
                returnDateInput.value = returnDate;
            } else {
                returnDateInput.value = '';
            }
        }
            function previewImage(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('preview');
                    output.src = reader.result;
                    output.style.display = 'block';
                }
                reader.readAsDataURL(event.target.files[0]);
            }
            </script>
        </div>
        <div class="con-right">
            <img src="<?php echo htmlspecialchars($book['image']); ?>" alt="Book Image">
            <h2><?php echo htmlspecialchars($book['title']); ?></h2>
        </div>
    </div>

    <?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

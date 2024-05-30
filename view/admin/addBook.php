<?php
require_once '../../source/controller/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $description = $_POST['description'];

    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../source/img/books/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            // Check file size (limit to 2MB)
            if ($_FILES["image"]["size"] <= 2000000) {
                // Allow certain file formats
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                        // File is uploaded successfully
                        if (addBook($title, $author, $year, $description, $target_file)) {
                            header("Location: admin.php");
                            exit();
                        } else {
                            echo "Error adding book.";
                        }
                    } else {
                        echo "Sorry, there was an error uploading your file.";
                    }
                } else {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                }
            } else {
                echo "Sorry, your file is too large.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        echo "No file was uploaded.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="../../source/css/styless.css">
</head>
<body>

<?php include __DIR__ . '/../../view/component/navbarAdmin.php'; ?>

    <div class="container">
        <h1>Tambah Buku Baru</h1>
        <form action="addBook.php" method="post" enctype="multipart/form-data">
            <label>Judul:</label>
            <input type="text" name="title" required><br>
            <label>Penulis:</label>
            <input type="text" name="author" required><br>
            <label>Tahun:</label>
            <input type="number" name="year" required><br>
            <label>Deskripsi:</label>
            <input type="text" name="description" required><br>
            <label>Gambar:</label>
            <div class="form-group">
                <label for="image" class="custom-file-upload">Unggah Gambar Buku </label>
                <input type="file" id="image" name="image" onchange="previewImage(event)">
                <img id="preview" src="#" alt="Pratinjau Gambar" style="display: none;">
            <input type="submit" value="Tambah Buku">
            </div>
        </form>
    </div>

    <script>
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

    <?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

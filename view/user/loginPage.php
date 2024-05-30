<?php
require_once '../../source/controller/db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']); // Enkripsi password dengan MD5

    // Query untuk mengambil pengguna berdasarkan username
    $sql = "SELECT id, username, password FROM adminperpus WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // Verifikasi password (MD5)
        if ($password === $hashed_password) {
            // Simpan informasi pengguna dalam sesi
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: ../admin/admin.php"); // Alihkan ke halaman dashboard
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username tidak ditemukan.";
    }

    $stmt->close();
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../../source/css/styless.css">
</head>
<body>

<?php include __DIR__ . '/../../view/component/navbar.php'; ?>

<div class="login-container">
        <h2>Login Admin</h2>
        <form action="loginPage.php" method="post">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                    <input type="submit" value="Submit">
            </div>
        </form>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../../view/component/footer.php'; ?>
</body>
</html>

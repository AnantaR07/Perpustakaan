<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library</title>
</head>
<style>
.navbar {
  background-color: #333;
  overflow: hidden;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 20px;
}

.navbar h2 {
  color: white;
  font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS", sans-serif;
  margin: 0;
}

.navbar .right {
  display: flex;
  align-items: center;
}

.navbar a {
  color: #f2f2f2;
  padding: 10px 20px;
  text-decoration: none;
  font-size: 17px;
}

.navbar a:hover {
  color: #4caf50;
}

.navbar button {
  padding: 10px 20px;
  font-size: 17px;
  cursor: pointer;
  background-color: #4caf50;
  color: white;
  border: none;
  border-radius: 5px;
  margin-left: 10px;
}

.navbar button:hover {
  background-color: #45a049;
}
</style>
<body>
<div class="navbar">
    <h2>PERPUS-ON</h2>
    <div class="right">
    <?php if (isset($_SESSION['user_id'])): ?>
      <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="../admin/admin.php">Home Admin</a>
      <?php endif; ?>
        <button onclick="window.location.href='<?php echo '../user/index.php'; ?>'">Logout</button>
      <?php else: ?>
        <a href="../user/index.php">Home</a>
        <button onclick="window.location.href='<?php echo '../user/loginPage.php'; ?>'">Login</button>
    <?php endif; ?>

    </div>
</div>
</body>
</html>

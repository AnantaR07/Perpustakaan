<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library</title>
</head>
<style>

.navbar {
  background-color: #20343b;
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
  color: #018bd4;
}

.navbar button {
  padding: 10px 20px;
  font-size: 17px;
  cursor: pointer;
  background-color: #018bd4;
  color: white;
  border: none;
  border-radius: 5px;
  margin-left: 10px;
}

.navbar button:hover {
  background-color: #fff;
  color: #018bd4;
}

.navbar img{
  height: auto;
  width: 60px;
}

.left{
  display: flex;
  justify-content: flex-start;
  color: #018bd4;
}

</style>
<body>
<div class="navbar">
    <div class="left">
      <img src="../../source/img/property/logo.jpg">
      <h3>PERPUS-ON</h3>
    </div>
    <div class="right">
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="../admin/admin.php">Home Admin</a>
        <button onclick="window.location.href='<?php echo '../user/index.php'; ?>'">Logout</button>
    <?php endif; ?>
    </div>
</div>
</body>
</html>

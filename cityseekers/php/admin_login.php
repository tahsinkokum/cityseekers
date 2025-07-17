<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Basit sabit kullanıcı - geliştirmek istersen DB'den çekebiliriz
    $admin_user = 'admin';
    $admin_pass = 'admin123';

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['isim'] = 'admin';
        header('Location: admin_panel.php');
        exit;
    } else {
        $hata = "Hatalı kullanıcı adı veya şifre.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Admin Giriş</title>
  <style>
    body {
      background: #f5f5f5;
      font-family: Arial;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      width: 300px;
    }
    input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      font-size: 16px;
    }
    button {
      width: 100%;
      padding: 10px;
      background: #28a745;
      color: white;
      border: none;
      font-size: 16px;
      cursor: pointer;
    }
    .error {
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>
<div class="login-box">
  <h2>Admin Girişi</h2>
  <?php if (!empty($hata)) echo "<p class='error'>$hata</p>"; ?>
  <form method="POST">
    <input type="text" name="username" placeholder="Kullanıcı Adı" required>
    <input type="password" name="password" placeholder="Şifre" required>
    <button type="submit">Giriş Yap</button>
  </form>
</div>
</body>
</html>

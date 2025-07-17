<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>City Seekers - Giriş Yap</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      overflow: hidden;
    }

    .video-background {
      position: fixed;
      top: 0;
      left: 0;
      min-width: 100%;
      min-height: 100%;
      z-index: -1;
      object-fit: cover;
    }

    .login-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      position: relative;
      z-index: 1;
    }

    .logo-img {
      height: 150px;
      margin-bottom: 20px;
    }

    .login-container {
      width: 400px;
      background: rgba(255, 255, 255, 0.8);
      padding: 30px;
      border-radius: 10px;
      text-align: center;
    }

    .login-container input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    .login-container button {
      width: 100%;
      padding: 12px;
      background-color: #28a745;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .login-container button:hover {
      background-color: #218838;
    }

    .links {
      margin-top: 15px;
      display: flex;
      justify-content: space-between;
    }

    .links a {
      color: #007BFF;
      text-decoration: none;
      font-weight: bold;
      background-color: rgba(255, 255, 255, 0.9);
      padding: 8px 15px;
      border-radius: 5px;
      transition: background-color 0.3s;
    }

    .links a:hover {
      background-color: #e6e6e6;
    }

    /* Admin simgesi */
    #admin-icon {
      position: fixed;
      bottom: 20px;
      left: 20px;
      font-size: 28px;
      cursor: pointer;
      background: rgba(255,255,255,0.8);
      padding: 10px;
      border-radius: 50%;
      box-shadow: 0 0 10px black;
      z-index: 999;
    }

    #admin-panel {
      position: fixed;
      bottom: 80px;
      left: 20px;
      background: rgba(255,255,255,0.95);
      padding: 20px;
      border-radius: 10px;
      display: none;
      z-index: 999;
      width: 240px;
      box-shadow: 0 0 10px black;
    }

    #admin-panel input {
      width: 100%;
      padding: 8px;
      margin-bottom: 10px;
    }

    #admin-panel button {
      width: 100%;
      background-color: #28a745;
      color: white;
      padding: 8px;
      border: none;
      border-radius: 5px;
    }
  </style>
</head>
<body>

  <video autoplay muted loop class="video-background">
    <source src="videos/amasra.mp4" type="video/mp4">
    Tarayıcınız video etiketini desteklemiyor.
  </video>

  <div class="login-wrapper">
    <img src="Logo_transparent.png" alt="CitySeekers Logo" class="logo-img">

    <div class="login-container">
      <form action="php/login.php" method="POST">
        <input type="email" name="mail" placeholder="Mail" required>
        <input type="password" name="sifre" placeholder="Şifre" required>
        <button type="submit">Giriş Yap</button>
      </form>

      <div class="links">
        <a href="kayit.html">Kayıt Ol</a>
        <a href="sifremi-unuttum.html">Şifremi Unuttum</a>
      </div>
    </div>
  </div>

  <!-- Admin Giriş Simgesi -->
  <div id="admin-icon" onclick="toggleAdminPanel()">⚙️</div>

  <!-- Gizli Admin Paneli -->
  <div id="admin-panel">
    <form action="php/login.php" method="POST">
      <input type="text" name="mail" placeholder="Admin Kullanıcı Adı" required>
      <input type="password" name="sifre" placeholder="Şifre" required>
      <button type="submit">Admin Giriş</button>
    </form>
  </div>

  <script>
    function toggleAdminPanel() {
      const panel = document.getElementById("admin-panel");
      panel.style.display = (panel.style.display === "block") ? "none" : "block";
    }
  </script>

</body>
</html>

<?php
include "config.php";
include_once "functions.php";

algista_testkasutajad($connect);

$error = "";

if (isset($_POST['login'])) {
    if (logi_sisse($connect, $_POST['username'], $_POST['password'])) {
        if ($_SESSION['role'] === 'Admin') {
            header("Location: home.php");
        } else {
            header("Location: catalog.php");
        }
        exit;
    } else {
        $error = "Vale kasutajanimi või parool!";
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnusHub - Logi sisse</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <main style="max-width: 400px;">
        <h1>Logi sisse</h1>
        
        <?php if ($error): ?>
            <p style="color: #e53e3e; margin-bottom: 1rem;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Kasutajanimi</label>
            <input type="text" name="username" required>
            
            <label>Parool</label>
            <input type="password" name="password" required>
            
            <button type="submit" name="login">Logi sisse</button>
        </form>
    </main>
</body>
</html>

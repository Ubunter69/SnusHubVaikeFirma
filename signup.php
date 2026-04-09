<?php
include "config.php";
include_once "functions.php";

$message = "";

if (isset($_POST['signup'])) {
    $result = registreeri_kasutaja($connect, $_POST['username'], $_POST['password']);
    if ($result === true) {
        header("Location: login.php?msg=Konto loodud! Logi sisse.");
        exit;
    } else {
        $message = $result;
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnusHub - Registreerumine</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <main style="max-width: 400px;">
        <h1>Registreeru</h1>
        
        <?php if ($message): ?>
            <p style="color: #e53e3e; margin-bottom: 1rem;"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Kasutajanimi</label>
            <input type="text" name="username" required>
            
            <label>Parool</label>
            <input type="password" name="password" required>
            
            <button type="submit" name="signup">Loo konto</button>
        </form>
    </main>
</body>
</html>

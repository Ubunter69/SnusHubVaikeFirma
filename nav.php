<?php
include_once "functions.php";
?>
<nav class="top-nav">
    <div class="nav-brand">
        <a href="home.php" class="brand-link">SnusHub</a>
    </div>
    <div class="nav-links">
        <?php if (isset($_SESSION['username'])): ?>
            <span style="color: white; margin-right: 1rem;">Tere, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            
            <?php if ($_SESSION['role'] === 'Admin'): ?>
                <a href="home.php" class="nav-link">Avaleht</a>
                <a href="galerii.php" class="nav-link">Galerii</a>
                <a href="catalog.php" class="nav-link">Tooted</a>
                <a href="admin.php" class="nav-link">Admin</a>
            <?php else: // Õpilane ?>
                <a href="home.php" class="nav-link">Avaleht</a>
                <a href="catalog.php" class="nav-link">Tooted</a>
            <?php endif; ?>
            
            <a href="logout.php" class="nav-link" style="color: #feb2b2;">Logi välja</a>
        <?php else: ?>
            <a href="home.php" class="nav-link">Avaleht</a>
            <a href="catalog.php" class="nav-link">Tooted</a>
            <a href="login.php" class="nav-link">Logi sisse</a>
            <a href="signup.php" class="nav-link">Registreeru</a>
        <?php endif; ?>
    </div>
</nav>

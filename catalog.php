<?php
include_once "functions.php";
include "config.php";

$msg = "";
if (isset($_POST['buy_product']) && isset($_SESSION['username'])) {
    $msg = osta_toode($connect, $_POST['product_id']);
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnusHub - Tooted</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <main>
        <h1>Meie tooted</h1>
        <p class="description">Valik kvaliteetseid tooteid meie poes.</p>

        <?php if ($msg): ?>
            <p style="background: #ebf8ff; color: #2b6cb0; padding: 1rem; border-radius: 5px; margin-bottom: 2rem;"><?= $msg ?></p>
        <?php endif; ?>

        <div class="grid">
            <?php
            $result = mysqli_query($connect, "SELECT * FROM products");

            if (mysqli_num_rows($result) == 0): ?>
                <p>Tooteid ei leitud.</p>
            <?php else: 
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="item">
                        <img src="images/<?= htmlspecialchars($row['image'] ?? 'no-image.png') ?>" 
                             alt="<?= htmlspecialchars($row['name']) ?>" 
                             style="width: 100%; height: 200px; object-fit: cover;"
                             onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=Pilt+puudub'">
                        <small style="color: #4a5568; text-transform: uppercase; font-weight: bold;">
                            <?= htmlspecialchars($row['category'] ?? 'Üldine') ?>
                        </small>
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <p class="price"><?= number_format($row['price'], 2) ?> €</p>
                        <p style="font-size: 0.8rem; color: <?= ($row['stock'] > 0 ? '#38a169' : '#e53e3e') ?>;">
                            <?= ($row['stock'] > 0 ? 'Laos: ' . (int)$row['stock'] : 'Läbi müüdud') ?>
                        </p>

                        <?php if (isset($_SESSION['username'])): ?>
                            <?php if ($row['stock'] > 0): ?>
                                <form method="POST" style="margin-top: 1rem;">
                                    <input type="hidden" name="product_id" value="<?= $row['id'] ?>">
                                    <button type="submit" name="buy_product" class="btn">Osta</button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <p style="font-size: 0.8rem; color: #718096; margin-top: 1rem;">Ostmiseks logi sisse</p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; 
            endif; ?>
        </div>
    </main>

    <footer style="text-align: center; margin-top: 5rem; padding-bottom: 2rem; color: #718096;">
        <p>&copy; 2026 SnusHub. Kõik õigused kaitstud.</p>
    </footer>
</body>
</html>

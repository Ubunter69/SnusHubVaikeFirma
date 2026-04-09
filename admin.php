<?php
include_once "functions.php";
include "config.php";

// Kontrollib, kas kasutajal on Admin õigused
kontrolli_ligipaasu('Admin');

// Kustutab toote ID järgi
if (isset($_GET['delete_product'])) {
    kustuta_toode($connect, $_GET['delete_product']);
    header("Location: admin.php");
    exit;
}

// Lisab uue toote või uuendab olemasolevat
if (isset($_POST['save_product'])) {
    salvesta_toode($connect, $_POST['id'] ?? 0, $_POST['name'], $_POST['description'], $_POST['price'], $_POST['category'], $_POST['stock'], $_POST['image']);
    header("Location: admin.php");
    exit;
}

// Lisab uue pildi galeriisse
if (isset($_POST['add_gallery'])) {
    lisa_galerii_pilt($connect, $_POST['description'], $_FILES['image']);
    header("Location: admin.php");
    exit;
}

// Kustutab galerii pildi
if (isset($_GET['delete_gallery'])) {
    kustuta_galerii_pilt($connect, $_GET['delete_gallery']);
    header("Location: admin.php");
    exit;
}

// Võtab ühe toote andmed muutmiseks
$edit_product = null;
if (isset($_GET['edit_product'])) {
    $id = (int)$_GET['edit_product'];
    $res = mysqli_query($connect, "SELECT * FROM products WHERE id = $id");
    $edit_product = mysqli_fetch_assoc($res);
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnusHub - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <main>
        <h1>Admin paneel</h1>

        <section>
            <h2>Toodete haldamine</h2>

            <!-- Vorm toodete lisamiseks või muutmiseks -->
            <form method="POST">
                <?php if ($edit_product): ?>
                    <!-- Kui toode on valitud, täidetakse vorm olemasolevate andmetega -->
                    <input type="hidden" name="id" value="<?= $edit_product['id'] ?>">
                    <input type="text" name="name" value="<?= htmlspecialchars($edit_product['name']) ?>" required>
                    <input type="text" name="category" value="<?= htmlspecialchars($edit_product['category'] ?? '') ?>">
                    <input type="number" name="stock" value="<?= $edit_product['stock'] ?? 0 ?>">
                    <input type="text" name="image" value="<?= htmlspecialchars($edit_product['image'] ?? '') ?>">
                    <textarea name="description"><?= htmlspecialchars($edit_product['description']) ?></textarea>
                    <input type="number" name="price" step="0.01" value="<?= $edit_product['price'] ?>" required>
                    <button type="submit" name="save_product">Salvesta muudatused</button>
                <?php else: ?>
                    <!-- Uue toote lisamise vorm -->
                    <input type="text" name="name" placeholder="Uus toode" required>
                    <input type="text" name="category" placeholder="Kategooria">
                    <input type="number" name="stock" value="0">
                    <input type="text" name="image">
                    <textarea name="description"></textarea>
                    <input type="number" name="price" step="0.01" required>
                    <button type="submit" name="save_product">Lisa toode</button>
                <?php endif; ?>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Pilt</th>
                        <th>Nimi</th>
                        <th>Kategooria</th>
                        <th>Laos</th>
                        <th>Hind</th>
                        <th>Tegevused</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Võtab kõik tooted andmebaasist
                    $result = mysqli_query($connect, "SELECT * FROM products");

                    // Läbib kõik tooted ükshaaval
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <!-- Kuvab pildi -->
                            <td>
                                <img src="images/<?= htmlspecialchars($row['image'] ?? 'no-image.png') ?>">
                            </td>

                            <!-- Kuvab andmed turvaliselt -->
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['category'] ?? '-') ?></td>

                            <!-- Näitab laoseisu -->
                            <td><?= (int)($row['stock'] ?? 0) ?></td>

                            <!-- Vormindab hinna -->
                            <td><?= number_format($row['price'], 2) ?> €</td>

                            <!-- Muutmine ja kustutamine -->
                            <td>
                                <a href="admin.php?edit_product=<?= $row['id'] ?>">Muuda</a> | 
                                <a href="admin.php?delete_product=<?= $row['id'] ?>" onclick="return confirm('Kas oled kindel?')">Kustuta</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Galerii haldamine</h2>

            <!-- Pildi üleslaadimise vorm -->
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="image" required>
                <textarea name="description"></textarea>
                <button type="submit" name="add_gallery">Lisa pilt</button>
            </form>

            <div class="grid">
                <?php
                // Võtab galerii pildid (uusimad eespool)
                $result = mysqli_query($connect, "SELECT * FROM gallery ORDER BY id DESC");

                // Läbib kõik pildid
                while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="item">
                        <img src="images/<?= htmlspecialchars($row['image_name']) ?>">
                        <p><?= htmlspecialchars($row['description']) ?></p>

                        <!-- Kustutab pildi -->
                        <a href="admin.php?delete_gallery=<?= $row['id'] ?>" onclick="return confirm('Kustuta pilt?')">
                            Kustuta pilt
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>
</body>
</html>

<?php
include_once "functions.php";
include "config.php";
kontrolli_ligipaasu('Admin');

if (isset($_GET['delete_product'])) {
    kustuta_toode($connect, $_GET['delete_product']);
    header("Location: admin.php");
    exit;
}


if (isset($_POST['save_product'])) {
    salvesta_toode($connect, $_POST['id'] ?? 0, $_POST['name'], $_POST['description'], $_POST['price'], $_POST['category'], $_POST['stock'], $_POST['image']);
    header("Location: admin.php");
    exit;
}

if (isset($_POST['add_gallery'])) {
    lisa_galerii_pilt($connect, $_POST['description'], $_FILES['image']);
    header("Location: admin.php");
    exit;
}


if (isset($_GET['delete_gallery'])) {
    kustuta_galerii_pilt($connect, $_GET['delete_gallery']);
    header("Location: admin.php");
    exit;
}


$edit_product = null;
if (isset($_GET['edit_product'])) {
    $id = (int)$_GET['edit_product'];
    $res = mysqli_query($connect, "SELECT * FROM products WHERE id = $id");
    $edit_product = mysqli_fetch_assoc($res);// Võtab päringu tulemuse esimese rea ja teisendab selle assotsiatiivseks massiiviks
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
        <p class="description">Toodete ja galerii haldamine.</p>

        <section>
            <h2>Toodete haldamine</h2>
            <form method="POST">
                <?php if ($edit_product): ?>
                    <input type="hidden" name="id" value="<?= $edit_product['id'] ?>">
                    <input type="text" name="name" value="<?= htmlspecialchars($edit_product['name']) ?>" placeholder="Nimetus" required>
                    <input type="text" name="category" value="<?= htmlspecialchars($edit_product['category'] ?? '') ?>" placeholder="Kategooria">
                    <input type="number" name="stock" value="<?= $edit_product['stock'] ?? 0 ?>" placeholder="Laoseis">
                    <input type="text" name="image" value="<?= htmlspecialchars($edit_product['image'] ?? '') ?>" placeholder="Pildi nimi (nt: siberiawhite.jpg)">
                    <textarea name="description" placeholder="Kirjeldus"><?= htmlspecialchars($edit_product['description']) ?></textarea>
                    <input type="number" name="price" step="0.01" value="<?= $edit_product['price'] ?>" placeholder="Hind" required>
                    <button type="submit" name="save_product">Salvesta muudatused</button>
                    <a href="admin.php" style="margin-left: 1rem; color: #718096; text-decoration: none;">Tühista</a>
                <?php else: ?>
                    <input type="text" name="name" placeholder="Uus toode" required>
                    <input type="text" name="category" placeholder="Kategooria">
                    <input type="number" name="stock" placeholder="Laoseis" value="0">
                    <input type="text" name="image" placeholder="Pildi nimi (nt: siberiawhite.jpg)">
                    <textarea name="description" placeholder="Kirjeldus"></textarea>
                    <input type="number" name="price" step="0.01" placeholder="Hind" required>
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
                    $result = mysqli_query($connect, "SELECT * FROM products");
                    while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><img src="images/<?= htmlspecialchars($row['image'] ?? 'no-image.png') ?>" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=Pilt+puudub'"></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>// Kuvab toote nime turvaliselt (väldib XSS rünnakuid)
                            <td><?= htmlspecialchars($row['category'] ?? '-') ?></td>// Kuvab kategooria, kui puudub siis näitab "-"
                            <td><?= (int)($row['stock'] ?? 0) ?></td>
                            <td><?= number_format($row['price'], 2) ?> €</td>
                            <td>
                                <a href="admin.php?edit_product=<?= $row['id'] ?>" style="color: #3182ce;">Muuda</a> | 
                                <a href="admin.php?delete_product=<?= $row['id'] ?>" style="color: #e53e3e;" onclick="return confirm('Kas oled kindel?')">Kustuta</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section style="margin-top: 4rem;">
            <h2>Galerii haldamine</h2>
            <form method="POST" enctype="multipart/form-data">
                <label>Vali pilt üleslaadimiseks</label>
                <input type="file" name="image" accept="image/*" required>
                <textarea name="description" placeholder="Pildi kirjeldus"></textarea>
                <button type="submit" name="add_gallery">Lisa pilt galeriisse</button>
            </form>

            <div class="grid">
                <?php
                $result = mysqli_query($connect, "SELECT * FROM gallery ORDER BY id DESC");
                while ($row = mysqli_fetch_assoc($result)): ?>// Läbib kõik tulemused ükshaaval
                    <div class="item">
                        <img src="images/<?= htmlspecialchars($row['image_name']) ?>" alt="" style="max-height: 150px; object-fit: cover;">
                        <p><?= htmlspecialchars($row['description']) ?></p>
                        <a href="admin.php?delete_gallery=<?= $row['id'] ?>" class="btn-delete" style="color: #e53e3e;" onclick="return confirm('Kustuta pilt?')">Kustuta pilt</a>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
    </main>

    <footer style="text-align: center; margin-top: 5rem; padding-bottom: 2rem; color: #718096;">
        <p>&copy; 2026 SnusHub. Kõik õigused kaitstud.</p>
    </footer>
</body>
</html>

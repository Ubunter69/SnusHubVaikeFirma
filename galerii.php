<?php
include_once "functions.php";
include "config.php";
kontrolli_ligipaasu('Admin');
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnusHub - Galerii</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include "nav.php"; ?>

    <main>
        <h1>Galerii</h1>
        <p class="description">Valik pilte meie toodetest ja tegevustest.</p>

        <div class="grid">
            <?php
            $result = mysqli_query($connect, "SELECT * FROM gallery ORDER BY upload_date DESC");

<?php
// Kontrollib, kas andmebaasis on pilte
if (mysqli_num_rows($result) == 0): ?>
    <p>Pilte ei leitud.</p>
<?php else: 
    // Läbib kõik galerii pildid ükshaaval
    while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="item">
            <!-- Kuvab pildi kaustast images vastavalt failinimele -->
            <img src="images/<?= htmlspecialchars($row['image_name']) ?>" 
                 alt="<?= htmlspecialchars($row['description']) ?>"
                 style="width: 100%; height: 250px; object-fit: cover;"
                 onerror="this.onerror=null; this.src='https://placehold.co/400x300?text=Pilt+puudub'">

            <!-- Kuvab pildi kirjelduse -->
            <p><?= htmlspecialchars($row['description']) ?></p>

            <!-- Vormindab kuupäeva kujule päev.kuu.aasta -->
            <small style="color: #a0aec0;">
                <?= date('d.m.Y', strtotime($row['upload_date'])) ?>
            </small>
        </div>
    <?php endwhile; 
endif; ?>

    <footer style="text-align: center; margin-top: 5rem; padding-bottom: 2rem; color: #718096;">
        <p>&copy; 2026 SnusHub. Kõik õigused kaitstud.</p>
    </footer>
</body>
</html>

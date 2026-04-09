<?php
// Käivitab sessiooni ainult siis, kui see pole veel aktiivne
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function kontrolli_ligipaasu($roll = '') {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit;
    }

    // Kui nõutud roll on määratud ja kasutaja roll ei vasta sellele
    if ($roll !== '' && $_SESSION['role'] !== $roll) {
        if ($_SESSION['role'] === 'Õpilane') {
            header("Location: catalog.php");
        } else {
            header("Location: home.php");
        }
        exit;
    }
}

function osta_toode($connect, $product_id) {
    $product_id = (int)$product_id;

    $sql = "UPDATE products SET stock = stock - 1 WHERE id = $product_id AND stock > 0";
    mysqli_query($connect, $sql);
    
    if (mysqli_affected_rows($connect) > 0) {
        return "Toode ostetud edukalt!";
    } else {
        return "Vabandust, toode on läbi müüdud!";
    }
}

function kustuta_toode($connect, $id) {
    $id = (int)$id;

    return mysqli_query($connect, "DELETE FROM products WHERE id = $id");
}


function kustuta_galerii_pilt($connect, $id) {
    $id = (int)$id;

    $res = mysqli_query($connect, "SELECT image_name FROM gallery WHERE id = $id");
    // Võtab päringu tulemuse ja salvestab selle massiivina muutujasse $img
    $img = mysqli_fetch_assoc($res);
    
    // Kui fail eksisteerib serveris, kustutatakse see ka kaustast images
    if ($img && file_exists("images/" . $img['image_name'])) {
        unlink("images/" . $img['image_name']);
    }
    
    // Pärast faili kustutamist eemaldab kirje andmebaasist
    return mysqli_query($connect, "DELETE FROM gallery WHERE id = $id");
}

/**
 * Loeb andmebaasist kasutajad ja loob testkasutajad, kui tabel on tühi.
 */
function algista_testkasutajad($connect) {
    // Kontrollib, kas users tabelis on vähemalt üks kasutaja olemas
    $check_users = mysqli_query($connect, "SELECT id FROM users LIMIT 1");

    // Kui tabel on tühi, lisatakse vaikimisi testkasutajad
    if (mysqli_num_rows($check_users) == 0) {
        // Paroolid salvestatakse turvaliselt hashitud kujul
        $admin_pass = password_hash('12345', PASSWORD_DEFAULT);
        $student_pass = password_hash('54321', PASSWORD_DEFAULT);

        mysqli_query($connect, "INSERT INTO users (username, password, role) VALUES ('admin', '$admin_pass', 'Admin'), ('opilane', '$student_pass', 'Õpilane')");
    }
}


function logi_sisse($connect, $username, $password) {
    // Kaitseb kasutajanime SQL-süsti eest
    $username = mysqli_real_escape_string($connect, $username);

    $result = mysqli_query($connect, "SELECT * FROM users WHERE username = '$username'");
    
    // Võtab andmebaasist ühe rea ja kontrollib, kas kasutaja leiti
    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            return true;
        }
    }


    return false;
}


function registreeri_kasutaja($connect, $username, $password) {
    $username = mysqli_real_escape_string($connect, $username);

    // Hashib parooli enne salvestamist
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Vaikimisi roll on Õpilane
    $role = 'Õpilane';

    $check = mysqli_query($connect, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check) > 0) {
        return "See kasutajanimi on juba võetud!";
    }
    
    
    if (mysqli_query($connect, "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')")) {
        return true;
    }

    return "Viga registreerimisel!";
}


function salvesta_toode($connect, $id, $name, $desc, $price, $cat, $stock, $img) {
    $id = (int)$id;
    $name = mysqli_real_escape_string($connect, $name);
    $desc = mysqli_real_escape_string($connect, $desc);
    $price = (float)$price;
    $cat = mysqli_real_escape_string($connect, $cat);
    $stock = (int)$stock;
    $img = mysqli_real_escape_string($connect, $img);

    if ($id > 0) {
        return mysqli_query($connect, "UPDATE products SET name='$name', description='$desc', price='$price', category='$cat', stock=$stock, image='$img' WHERE id=$id");
    } else {
        return mysqli_query($connect, "INSERT INTO products (name, description, price, category, stock, image) VALUES ('$name', '$desc', '$price', '$cat', $stock, '$img')");
    }
}


function lisa_galerii_pilt($connect, $description, $file) {
    // Puhastab kirjelduse enne andmebaasi salvestamist
    $description = mysqli_real_escape_string($connect, $description);
    
    // Kontrollib, kas fail on olemas ja üleslaadimisel ei tekkinud viga
    if (isset($file) && $file['error'] == 0) {
        $target_dir = "images/";

        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $file_name = basename($file["name"]);
        $target_file = $target_dir . $file_name;
        
        // Liigutab faili ajutisest kaustast lõppkausta
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // Salvestab pildi andmed andmebaasi
            return mysqli_query($connect, "INSERT INTO gallery (image_name, description) VALUES ('$file_name', '$description')");
        }
    }

    return false;
}
?>

<?php
session_start();

if (!isset($_SESSION["login"]["status"]) || $_SESSION["login"]["status"] !== "admin") {
    header("location: login.php");
    exit();
}

if (isset($_POST['submit'])) {
    require("db/db_connect.php");

    if (
        isset($_POST['title']) &&
        isset($_POST['title_url']) &&
        isset($_POST['content']) &&
        isset($_POST['author']) &&
        isset($_GET['id'])
    ) {
        $id = mysqli_real_escape_string($db, $_GET['id']);
        $title = mysqli_real_escape_string($db, $_POST['title']);
        $title_url = mysqli_real_escape_string($db, strtolower(str_replace(" ", "-", $_POST['title_url'])));
        $thumbnail = $_FILES['thumbnail'];
        $content = mysqli_real_escape_string($db, $_POST['content']);
        $author = mysqli_real_escape_string($db, $_POST['author']);
        $visible = $_POST['option'];

        $sql = "SELECT * FROM articles WHERE (title = '{$title}' OR title_url = '{$title_url}') AND id != $id";
        $query = mysqli_query($db, $sql);

        if (mysqli_num_rows($query) == 0) {
            $photo_include = false;

            if (isset($thumbnail) && $thumbnail['error'] === 0) {
                $allowed_mime_types = ['image/jpeg', 'image/png'];
                $max_file_size = 1 * 1024 * 1024; // 1 MB

                if (in_array($thumbnail['type'], $allowed_mime_types) && $thumbnail['size'] <= $max_file_size) {
                    $target_directory = 'media/img/';
                    $target_file = $target_directory . $title_url . ".png";
                    $target_file_to_db = "./media/img/" . $title_url . ".png";

                    if (move_uploaded_file($thumbnail['tmp_name'], $target_file)) {
                        $photo_include = true;
                    } else {
                        $error = "Wystąpił błąd podczas zapisywania pliku.";
                    }
                } else {
                    $error = "Plik nie spełnia wymagań dotyczących typu lub rozmiaru.";
                }
            }

            if ($photo_include) {
                $edited = "UPDATE `articles` SET `public`='$visible',`title`='$title',`title_url`='$title_url',`thumbnail`='$target_file_to_db',`content`='$content',`author`='$author' WHERE `id`=$id";
            } else {
                $edited = "UPDATE `articles` SET `public`='$visible',`title`='$title',`title_url`='$title_url',`content`='$content',`author`='$author' WHERE `id`=$id";
            }

            if (mysqli_query($db, $edited)) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Wystąpił błąd podczas aktualizacji artykułu w bazie danych.";
            }
        } else {
            $error = "Artykuł o podanym tytule lub adresie URL już istnieje.";
        }
    } else {
        $error = "Niektóre pola są puste.";
    }

    require("db/db_close.php");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Deepler</title>
    <link rel="stylesheet" href="css/output.css">
</head>
<body>
    <?php
    include("elements/nav.html");
    ?>
    <?php
    require("./db/db_connect.php");
    ?>
    <?php
    if (isset($_GET["id"])) {
        $id = mysqli_real_escape_string($db, $_GET["id"]);
        $sql = "SELECT `id`, `public`, `title`, `title_url`, `thumbnail`, `content`, `author` FROM articles WHERE id=$id";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "
                <section class='upload'>
                <div class='container'>
                    <form
                    action='edit-article.php?id=$row[id]'
                    method='POST'
                    enctype='multipart/form-data'
                >
                    <label for='title'>Tytuł: </label>
                    <input type='text' name='title' value='" . htmlspecialchars($row['title']) . "' required />
                    <label for='title_url'>Podaj tytuł do wyświetlania w adresie URL: </label>
                    <input type='text' name='title_url' value='" . htmlspecialchars($row['title_url']) . "' required />
                    <label for='thumbnail'>Zaznacz obraz, który ma być miniaturą (400x600): </label>
                    <input type='file' name='thumbnail' />
                    <img src='" . htmlspecialchars($row['thumbnail']) . "'>
                    <label for='content'>Treść artykułu: </label>
                    <textarea name='content' rows='20' cols='50' required>" . htmlspecialchars($row['content']) . "</textarea>
                    <label for='author'>Autor: </label>
                    <input type='text' name='author' value='" . htmlspecialchars($row['author']) . "' required />
                    <select name='option'>
                        <option value='1'>Widoczne</option>
                        <option value='0' " . ($row['public'] == 0 ? "selected" : "") . ">Niewidoczne</option>
                    </select>
                    <input type='submit' value='Zaktualizuj artykuł' name='submit'/>
                    </form>
                </div>
            </section>
            ";
            }
        } else { $error = "Błąd: Nie znaleziono podanego artykułu na podstawie ID."; }
    } else { $error = "Błąd w adresie URL: Brak określonego ?id."; }

    if (!empty($error)) {
        echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
    }

    require("./db/db_close.php");
    ?>
    <?php
    include("elements/footer.html");
    ?>
</body>
</html>

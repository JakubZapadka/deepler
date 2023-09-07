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
        isset($_POST['author'])
    ) {
        $title = mysqli_real_escape_string($db, $_POST['title']);
        $title_url = mysqli_real_escape_string($db, strtolower(str_replace(" ", "-", $_POST['title_url'])));
        $thumbnail = $_FILES['thumbnail'];
        $content = mysqli_real_escape_string($db, $_POST['content']);
        $author = mysqli_real_escape_string($db, $_POST['author']);

        $sql = "SELECT * FROM articles WHERE title = '{$title}' OR title_url = '{$title_url}'";
        $query = mysqli_query($db, $sql);

        if (mysqli_num_rows($query) == 0) {
            if (isset($thumbnail) && $thumbnail['error'] === 0) {
                $allowed_mime_types = ['image/png'];
                $max_file_size = 1 * 1024 * 1024; // 1 MB

                if (in_array($thumbnail['type'], $allowed_mime_types) && $thumbnail['size'] <= $max_file_size) {
                    $target_directory = 'media/img/';
                    $target_file = $target_directory . $title_url . ".png";
                    $target_file_to_db = "./media/img/" . $title_url . ".png";

                    if (move_uploaded_file($thumbnail['tmp_name'], $target_file)) {
                        // Wstaw nowy artykuł do bazy danych
                        $insert = "INSERT INTO `articles`(`public`, `title`, `title_url`, `thumbnail`, `content`, `author`, `release_date`) VALUES (1,'{$title}','{$title_url}','{$target_file_to_db}','{$content}','{$author}',NOW())";

                        if (mysqli_query($db, $insert)) {
                            header("Location: index.php");
                            exit();
                        } else {
                            $error_message = "Wystąpił błąd podczas dodawania artykułu do bazy danych.";
                        }
                    } else {
                        $error_message = "Wystąpił błąd podczas zapisywania pliku.";
                    }
                } else {
                    $error_message = "Plik nie spełnia wymagań dotyczących typu lub rozmiaru.";
                }
            } else {
                $error_message = "Błąd związany z miniaturką.";
            }
        } else {
            $error_message = "Artykuł o podanym tytule lub adresie URL już istnieje.";
        }
    } else {
        $error_message = "Niektóre pola są puste.";
    }

    require("db/db_close.php");
}
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <title>Deepler</title>
        <?php
        include("elements/head.html");
        ?>
    </head>
    <body>
        <?php
            include("elements/nav.html");
        ?>
        <section class="upload">
            <div class="container">
                <form
                action="php/write-article.php"
                method="POST"
                enctype="multipart/form-data"
            >
                <label for="title">Tytuł: </label>
                <input type="text" name="title" required />
                <label for="title_url">Podaj tytuł do wyświetlania w adresie URL: </label>
                <input type="text" name="title_url" required />
                <label for="thumbnail">Zaznacz obraz, który ma być miniaturą (400x600): </label>
                <input type="file" name="thumbnail" required />
                <label for="content">Treść artykułu: </label>
                <textarea name="content" rows="20" cols="50" required></textarea>
                <label for="author">Autor: </label>
                <input type="text" name="author" required />
                <input type="submit" value="Umieść artykuł" name="submit"/>
            </form>
            <?php  
            if(isset($error_message)){
                echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8');
                $error_message = "";
            }
            ?>
        </div>
    </section>
    <?php
    include("elements/footer.html");
    ?>
</body>
</html>

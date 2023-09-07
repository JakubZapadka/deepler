<?php
session_start();
if(!isset($_SESSION["login"]["status"]) || $_SESSION["login"]["status"] != "admin"){
    header("location: login.php");
    exit();
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
    <section class="articles-container">
        <div class="container">
            <h2>panel admina</h2>
            <a href='notVisible-articles.php' class="admin_button">Niewidoczne artykuły</a>
            <a href='write-article.php' class="admin_button">Dodaj artykuł</a>
            <a href='php/logout.php' class="admin_button">Logout</a>
            <a href="logs.txt" class="admin_button">logi</a>
        </div>
    </section>
    <?php
        include("elements/footer.html");
    ?>
</body>
</html>
<?php
session_start();
if(!isset($_SESSION["login"]["status"]) || $_SESSION["login"]["status"] != "admin"){
    header("location: login.php");
    exit();
}
    function latest_articles($limit){
        global $db;
        $sql = "SELECT * FROM `articles` WHERE public = 0 ORDER BY release_date desc LIMIT {$limit}";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $now = new DateTime(); // bieżąca data i czas
                $date = new DateTime($row['release_date']); // data z bazy danych
                $diff = $now->diff($date); // różnica między datami
                if((int)($diff->format('%d'))==1){
                    $time = $diff->format('%d dzień temu');
                }elseif((int)($diff->format('%d'))>1){
                    $time = $diff->format('%d dni temu');
                }elseif((int)($diff->format('%h'))==1){
                    $time = $diff->format('%h godzinę temu');
                }elseif((int)($diff->format('%h'))>1){
                    $time = $diff->format('%h godzin temu');
                }elseif((int)($diff->format('%s'))==1){
                    $time = $diff->format('%i minutę temu');
                }else{
                    $time = $diff->format('%i minut temu');
                }

                echo "
                <article class='latest_articles'>
                    <a href='edit-article.php?id={$row["id"]}'><img src='{$row["thumbnail"]}' alt=''></a>
                    <a href='edit-article.php?id={$row["id"]}'><h3>{$row['title']}</h3></a>
                    <span class='realease_date'>{$time}</span>
                </article>";
            }
        }
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
            <h2>Niewidoczne artykuły</h2>
            <?php
            require("./db/db_connect.php");
            ?>
            <?php
                latest_articles(10);
            ?>
            <?php
                require("./db/db_close.php");
            ?>
        </div>
    </section>
    <?php
        include("elements/footer.html");
    ?>
</body>
</html>
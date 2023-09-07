<?php
    function latest_articles($limit){
        global $db;
        $sql = "SELECT * FROM `articles` WHERE public = 1 ORDER BY release_date desc LIMIT {$limit}";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $now = new DateTime(); // bieżąca data i czas
                $date = new DateTime($row['release_date']); // data z bazy danych
                $diff = $now->diff($date); // różnica między datami
                if((int)($diff->format('%y'))==1){
                    $time = $diff->format('%y rok temu');
                }elseif((int)($diff->format('%y'))>1){
                    $time = $diff->format('%y lat temu');
                }elseif((int)($diff->format('%m'))==1){
                    $time = $diff->format('%m miesiąc temu');
                }elseif((int)($diff->format('%m'))>1){
                    $time = $diff->format('%m miesięcy temu');
                }elseif((int)($diff->format('%d'))==1){
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
                    <a href='article.php?t={$row["title_url"]}'><img src='{$row["thumbnail"]}' alt=''></a>
                    <a href='article.php?t={$row["title_url"]}'><h3>{$row['title']}</h3></a>
                    <span class='realease_date'>{$time}</span>
                </article>";
            }
        }
    }
    function show_details(){
        global $db;
        $title_url = $_GET['t'];
        $sql = "SELECT * FROM `articles` WHERE public = 1 and title_url = '{$title_url}' LIMIT 1";
        $result = mysqli_query($db, $sql);
        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                session_start();
                if(isset($_SESSION["login"]["status"])){
                    if($_SESSION["login"]["status"] == "admin"){
                        echo "
                        <a href='edit-article.php?id={$row["id"]}' style='padding: 10px'>edytuj artykuł</a>
                        ";
                    }
                }
                if($row['public'] == TRUE){
                    $content = nl2br($row['content']);
                    echo "
                    <h1 class='title'>{$row['title']}</h1>
                    <p class='release-date'>{$row['release_date']}</p>
                    <img src='{$row["thumbnail"]}' alt=''>
                    <p class='content'>{$content}</p>
                    <p class='author'>Autor: <a href='author.php?author=$row[author]'>{$row['author']}</a></p>
                    ";
                }
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
    <section class="details">
        <div class="container">
            <article class="article-details">
                <?php
                    require("./db/db_connect.php");
                ?>
                <?php
                    show_details();
                ?>
            </article>
            <aside class="aside">
                <?php
                    latest_articles(6);
                ?>
                <?php
                    require("./db/db_close.php");
                ?>
            </aside>
        </div>
    </section>
    <?php
        include("elements/footer.html");
    ?>
</body>
</html>
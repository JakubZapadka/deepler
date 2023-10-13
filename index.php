<?php
function latest_articles($rows_per_page){
    global $db, $nr_of_pages, $nr_of_current_page;
    // PAGINATION
    if(isset($_GET['page'])){
        $nr_of_current_page = $_GET['page'];
        $start_value = ($nr_of_current_page-1)*$rows_per_page;
        $sql = "SELECT * FROM `articles` WHERE public = 1 ORDER BY release_date desc LIMIT {$start_value}, {$rows_per_page}";
    }else{
        $nr_of_current_page = 1;
        $sql = "SELECT * FROM `articles` WHERE public = 1 ORDER BY release_date desc LIMIT 0, {$rows_per_page}";
    }
    $nr_of_rows = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) AS count FROM articles WHERE public = 1"))["count"];
    $nr_of_pages = ceil($nr_of_rows / $rows_per_page);

    function generateLink($class, $href, $text, $active = true) {
        if($active){
            echo "<li class='$class'><a href='?page=$href'>$text</a></li>";
        }
        else{
            echo "<li class='$class'><span>$text</span></li>";
        }
    }

    //SHOWING ARTICLES
    $result = mysqli_query($db, $sql);
    if (mysqli_num_rows($result) > 0) {

        while($row = mysqli_fetch_assoc($result)) {
            $now = new DateTime();
            $date = new DateTime($row['release_date']); 
            $diff = $now->diff($date); 
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
                <a href='article.php?t={$row["title_url"]}'>
                    <div class='blur_load' style='background-image: url(".substr($row["thumbnail"], 0, -4)."-small.png')'>
                        <img src='{$row["thumbnail"]}' alt='' loading='lazy'>
                    </div>
                </a>
                <a href='article.php?t={$row["title_url"]}'><h3>{$row['title']}</h3></a>
                <span class='realease_date'>{$time}</span>
            </article>";
        }
    }else{
        echo "Brak wyników";
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
            <?php
            session_start();
            if(isset($_SESSION["login"]["status"]) && $_SESSION["login"]["status"] = "admin"){
                echo "<span class='admin_panel'><a class='admin_button' href='admin-panel.php'>panel admina</a></span>";
            }
            ?>
            <!--<h2>Nowości</h2>-->
            <?php
            require("./db/db_connect.php");
            ?>
            <?php
                latest_articles(6);
            ?>
            <?php
                require("./db/db_close.php");
            ?>
        </div>
    </section>
    <section class="pagination">
        <?php
            echo "<span>{$nr_of_current_page} z {$nr_of_pages} stron </span>";
        ?>
        <ul>
            <?php
            if( $nr_of_current_page > 1){
                generateLink("previous_page", $nr_of_current_page-1, "<");
            }
            if($nr_of_current_page > 3){
                generateLink("first_page", "1", "1");
            }
            if($nr_of_current_page > 2){
                generateLink("", $nr_of_current_page-2, $nr_of_current_page-2);
            }
            if($nr_of_current_page > 1){
                generateLink("", $nr_of_current_page-1, $nr_of_current_page-1);
            }
            generateLink("active", "", $nr_of_current_page, false);
            if($nr_of_current_page < $nr_of_pages){
                generateLink("", $nr_of_current_page+1, $nr_of_current_page+1);
            }
            if($nr_of_current_page+1 < $nr_of_pages){
                generateLink("", $nr_of_current_page+2, $nr_of_current_page+2);
            }
            if($nr_of_current_page+2 < $nr_of_pages){
                generateLink("last_page", "$nr_of_pages", "$nr_of_pages");
            }
            if( $nr_of_current_page < $nr_of_pages){
                generateLink("next_page", $nr_of_current_page+1, ">");
            }
            ?>
        </ul>
    </section>
    <?php
        include("elements/footer.html");
    ?>
</body>
</html>
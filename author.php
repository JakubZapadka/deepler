<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Deepler - autor</title>
    <?php
        include("elements/head.html");
    ?>
</head>
<body>
    <?php
        include("elements/nav.html");
    ?>
    <section class="author">
        <div class="container">
        <?php
            require("db/db_connect.php");
            $author = strtolower($_GET['author']);
            $sql = "SELECT * from authors where name = '$author'";
            $result = mysqli_query($db, $sql);
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    echo "
                    <section class='author__avatar'><img src='media/avatar/$row[id].jpg' onerror='this.src=\"media/avatar/default.jpg\"'>
                    <h1>$row[name]</h1>
                    </section>
                    <section class='author__description'>
                    <h2>Opis</h2>
                    <p>$row[description]</p>
                    </section>
                    ";
                }
            }else{
                echo "nie znaleziono takiego autora";
            }
            require("db/db_close.php");
        ?>
        </div>
    </section>
    <?php
        include("elements/footer.html");
    ?>
</body>
</html>
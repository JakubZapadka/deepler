<?php
session_start();

if(isset($_SESSION["login"]["status"]) && $_SESSION["login"]["status"] == "admin"){
    header("location: index.php");
    exit();
}

require("db/db_connect.php");

if (isset($_POST['submit'])) {
    $login = $_POST['login'];
    $pass = $_POST['password'];

    if (!empty($login) && !empty($pass)) {
        $sql = "SELECT `id`, `nickname`, `password`, `status` FROM `users` WHERE nickname = ? AND password = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $login, $pass);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Ustaw dane użytkownika w sesji
            $_SESSION["login"] = [
                "id" => $row['id'],
                "login" => $row['nickname'],
                "status" => $row['status']
            ];

            header("Location: index.php");
            exit();
        } else {
            $error = "Nie znaleziono użytkownika z takim loginem i hasłem.";
        }
    }
}

require("db/db_close.php");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Deepler - logowanie</title>
    <?php
        include("elements/head.html");
    ?>
</head>
<body>
    <?php
    include("elements/nav.html");
    ?>

    <div class="login_section">
        <form action="login.php" method="POST">
            <h1>Logowanie</h1>
            <input type="text" name="login" placeholder="login" required>
            <input type="password" name="password" placeholder="haslo" required>
            <input type="submit" name="submit" value="Zaloguj">
        </form>
    </div>

    <section class="error_handler">
        <?php
        if (!empty($error)) {
            echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8');
        }
        $error = null;
        ?>
    </section>

    <?php
    include("elements/footer.html");
    ?>
</body>
</html>

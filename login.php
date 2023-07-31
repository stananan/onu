<?php
//Frontend Login Page
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);


if (isset($_POST["username"]) && isset($_POST["password"])) {
    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

        $username = $_POST['username'];
        $userpassword = $_POST['password'];

        $sth = $dbh->prepare("SELECT * FROM `users` WHERE :username = `username`;");
        $sth->bindValue(':username', $username);
        if ($sth->execute()) {
            $loginUser = $sth->fetch();

            if (!isset($loginUser['password'])) {
                $_SESSION["message"] = "Incorrent Credentials";
            } else {

                if (password_verify($userpassword, $loginUser['password'])) {

                    $userTable = $dbh->prepare("UPDATE `users` SET `last_login_time` = NOW() WHERE :username = `username`;");
                    $userTable->bindValue(":username", $username);
                    $userTable->execute();
                    $_SESSION["user"] = $loginUser['id'];

                    $userisadmin = $dbh->prepare("SELECT `is_admin` FROM users WHERE :username = username;");
                    $userisadmin->bindValue(":username", $username);
                    $userisadmin->execute();
                    $_SESSION["admin"] = $userisadmin->fetch()['is_admin'];

                    header("Location: index.php");
                } else {
                    $_SESSION["message"] = "Incorrent Password";
                }
            }
        } else {
            $_SESSION["message"] = "Error, please try again";
        }
    } catch (PDOException $e) {
        $errormessage = $e->getMessage();
        $errorcode = $e->getCode();
        $_SESSION["message"] = $errormessage;
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="images/reddit-logo.ico">

</head>

<body>




    <?php

    if (isset($_SESSION["message"])) {
        echo '<p> ' . $_SESSION["message"] . '</p>';
        unset($_SESSION["message"]);
    }

    ?>
    <!-- login form -->
    <form action="login.php" method="post">
        <h3>Username:</h3>
        <?php

        echo "<input type = 'text' name = 'username' required>";
        echo "<h3>Password:</h3>";
        echo "<input type ='password' name = 'password' required>";
        ?>

        <button type="submit">Login</button>


    </form>



</body>

</html>
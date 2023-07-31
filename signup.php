<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);


if (isset($_POST["username"]) && isset($_POST["password"])) {
    try {
        if (strlen($_POST["username"]) < 3 || strlen($_POST["password"]) < 3) {

            $_SESSION["message"] = "Invalid Username or password. Too short, username and password must be longer than 3 characters";
        } else if (!ctype_alpha($_POST["username"])) {

            $_SESSION["message"] = "Invalid Username. No numbers or special characters";
        } else {
            $isadmin = 0;

            if (isset($_POST["admincode"])) {
                if (password_verify($_POST["admincode"], adminCode)) {
                    $isadmin = 1;
                }
            }

            $username = $_POST['username'];
            $userpassword = password_hash($_POST["password"], PASSWORD_DEFAULT);

            $sth = $dbh->prepare("INSERT INTO users (`username`, `password`, `is_admin`, `creation_time`, `last_login_time`)
            VALUES (:username, :userpassword, :isadmin, NOW(), NOW());");

            $sth->bindValue(':username', $username);
            $sth->bindValue(":userpassword", $userpassword);
            $sth->bindValue(":isadmin", $isadmin);

            if ($sth->execute()) {
                echo "User successfully created";

                $userTable = $dbh->prepare("SELECT `id`, `is_admin` FROM users WHERE :username = username;");
                $userTable->bindValue(":username", $username);
                $userTable->execute();
                $userDetails = $userTable->fetch();
                $_SESSION["user"] = $userDetails['id'];
                $_SESSION["admin"] = $userDetails['is_admin'];

                header("Location: index.php");
            } else {
                echo "Error, please try again";
            }
        }
    } catch (PDOException $e) {
        $errormessage = $e->getMessage();
        $errorcode = $e->getCode();
        if (str_contains($errormessage, "1062 Duplicate entry") && $errorcode == 23000) {
            $_SESSION["message"] = "Username already exists, please create another one.";
        } else if (str_contains($errormessage, "1406 Data too long for column 'username'") && $errorcode == 22001) {
            $_SESSION["message"] = "Username is too long";
        } else {

            $_SESSION["message"] = "Error creating user";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body>
    <?php

    if (isset($_SESSION["message"])) {
        echo '<p> ' . $_SESSION["message"] . '</p>';
        unset($_SESSION["message"]);
    }

    ?>
    <!-- register form -->
    <form action="signup.php" method="post">
        <h3>Username:</h3>
        <?php
        echo "<input type = 'text' name = 'username' required>";
        echo "<h3>Password:</h3>";
        echo "<input type ='password' name = 'password' required>";
        echo "<h3>Admin Code:</h3>";
        echo "<input type='password' name = 'admincode'>";
        ?>
        <br>
        <button type="submit">Create</button>

    </form>
</body>

</html>
<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (!isset($_GET['id']) || !isset($_SESSION['user'])) {
    $_SESSION['message'] = "Please log in or sign up";
    header("Location: index.php");
    die();
}

try {
    $roomCheckTable = $dbh->prepare("SELECT * FROM `rooms` WHERE `id` = :id;");
    $roomCheckTable->bindValue(":id", $_GET['id']);
    $roomCheckTable->execute();
    $room = $roomCheckTable->fetch();
    if (empty($room)) {
        $_SESSION['message'] = "Room does not exist";
        header("Location: index.php");
        die();
    }
} catch (PDOException $e) {
    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();

    $_SESSION['message'] = $e;
    header("Location: index.php");
    die();
}

$_SESSION['room'] = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/message.js"></script>
    <link rel="stylesheet" href="stylesheets/room.css">
    <title>Room</title>
</head>

<body>
    <?php
    echo "<h1>Room <span id='room'>" . $_SESSION['room'] . "</span></h1>";


    if ($_SESSION['user'] == $room['creator_id']) {


        echo "<a href='backend/deleteroom.php?id=" . $room['id'] . "'>Delete Room</a>";
    }
    ?>

    <div class="chat">
        <h2 id="loading">Loading . . .</h2>
        <h2 id="empty">This chat is empty, Start the conversation!</h2>
    </div>

    <?php
    // try {
    //     $userTable = $dbh->prepare("SELECT username FROM users WHERE id = :id");
    //     $userTable->bindValue(":id", $_SESSION['user']);
    //     $userTable->execute();

    //     echo "<p id='user'>" . $userTable['username'] . "</p>";
    // } catch (PDOException $e) {
    //     echo $e;
    // }
    ?>


    <input type="text" name="message-val" id="message">
    <button id="send">Send</button>
    <button id="tts">TTS</button>
</body>

</html>
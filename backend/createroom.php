<?php
require "../realconfig.php";
session_start();


if (!isset($_SESSION['user'])) {
    $_SESSION['message'] = "Please log in or sign up";
    header("Location: index.php");
    die();
}


try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    $roomCheckTable = $dbh->prepare("SELECT * FROM `rooms` WHERE `creator_id` = :id;");
    $roomCheckTable->bindValue(":id", $_SESSION['user']);
    $roomCheckTable->execute();
    if (!empty($roomCheckTable->fetch())) {
        $_SESSION['message'] = "You already have a room created! Users can only host one room";
        header("Location: ../index.php");
        die();
    }

    $roomInsertTable = $dbh->prepare("INSERT INTO `rooms` (`creator_id`, `creation_time`) VALUES (:id, NOW());");
    $roomInsertTable->bindValue(":id", $_SESSION['user']);
    if ($roomInsertTable->execute()) {
        echo "room succesful";
    };

    $room_id = $dbh->lastInsertId();
    header("Location: ../room.php?id=" . $room_id . "");
} catch (PDOException $e) {
    $errormessage = $e->getMessage();
    $errorcode = $e->getCode();
    $_SESSION['message'] = $e;
    header("Location: ../index.php");
    die();
}

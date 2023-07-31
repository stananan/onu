<?php
require "../realconfig.php";
session_start();


if (isset($_GET['id'])) {
    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
        $creatorTable = $dbh->prepare("SELECT * FROM `rooms` WHERE `id` = :id");
        $creatorTable->bindValue(":id", $_GET['id']);
        $creatorTable->execute();
        $creator = $creatorTable->fetch();

        if ((isset($creator['creator_id']) && $creator['creator_id'] == $_SESSION['user']) || (isset($_SESSION['admin']) && $_SESSION['admin'] == 1)) {
            $messageTable = $dbh->prepare("DELETE FROM `messages` WHERE room_id = :room");
            $messageTable->bindValue(":room", $_GET['id']);
            $messageTable->execute();

            $roomTable = $dbh->prepare("DELETE FROM `rooms` WHERE `id` = :id");
            $roomTable->bindValue(":id", $_GET['id']);
            $roomTable->execute();
            $_SESSION['message'] = "Room Deleted";
            header("Location: ../index.php");
        }
    } catch (PDOException $e) {
        echo $e;
    }
} else {
    header("Location: ../index.php");
}

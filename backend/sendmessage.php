<?php

require "../realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (isset($_POST['user']) && isset($_POST['message']) && isset($_POST['room'])) {
    $user = $_POST['user'];
    $message = $_POST['message'];
    $room = $_POST['room'];

    try {
        $messageInsert = $dbh->prepare("INSERT INTO `messages` (`room_id`, `user_id`, `content`, `creation_time`) VALUES (:room, :user, :content, NOW());");
        $messageInsert->bindValue(":room", intval($room));
        $messageInsert->bindValue(":user", intval($user));
        $messageInsert->bindValue(":content", $message);
        $messageInsert->execute();
    } catch (PDOException $e) {
        echo $e;
    }
}

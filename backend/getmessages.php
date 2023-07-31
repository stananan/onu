<?php

require "../realconfig.php";
session_start();

if (!isset($_GET['room'])) {
    header("Location: ../index.php");
    die();
}


try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
    $roomCheckTable = $dbh->prepare("SELECT * FROM `rooms` WHERE `id` = :id;");
    $roomCheckTable->bindValue(":id", $_GET['room']);
    $roomCheckTable->execute();
    $room = $roomCheckTable->fetch();

    if (empty($room)) {
        $_SESSION['message'] = "Room was deleted by creator or admin";
        die();
    }

    $limit = 0;

    if (isset($_GET['messageLength'])) {
        $limit = $_GET['messageLength'];
    }



    $desiredLimit = 0; // Default value, in case something goes wrong.

    $countQuery = $dbh->prepare("SELECT COUNT(messages.id) AS total FROM `messages` WHERE room_id = :room");
    $countQuery->bindValue(":room", $_GET['room']);
    $countQuery->execute();
    $totalMessages = $countQuery->fetchColumn();

    $desiredLimit = $totalMessages - (int)$limit;

    // Handle the exception appropriately.


    // Prepare the SQL statement with the LIMIT clause using the calculated limit:
    $messageTable = $dbh->prepare("SELECT messages.user_id, messages.content, users.username, messages.creation_time 
    FROM `messages`
    JOIN `users` ON users.id = messages.user_id
    WHERE messages.room_id = :room
    ORDER BY messages.creation_time DESC
    LIMIT :desiredLimit");

    $messageTable->bindValue(':desiredLimit', (int)$desiredLimit, PDO::PARAM_INT);
    $messageTable->bindValue(':room', $_GET['room']);
    $messageTable->execute();
    $messages = $messageTable->fetchAll();

    function escapeArrayValues(array &$array)
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                escapeArrayValues($value); // Recursively call for nested arrays
            } else {
                $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }
        }
    }


    escapeArrayValues($messages);

    header('Content-Type: application/json');
    echo json_encode($messages);
} catch (PDOException $e) {
    echo $e;
}

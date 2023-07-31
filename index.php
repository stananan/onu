<?php
require "realconfig.php";
session_start();
$dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);

if (isset($_SESSION['room'])) {
    unset($_SESSION['room']);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Onu</title>
    <style>
        td,
        th {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <h1>Onu</h1>
    <?php
    if (isset($_SESSION['user'])) {
        try {
            $userTable = $dbh->prepare("SELECT `username` FROM `users` WHERE `id` = :id;");
            $userTable->bindValue(":id", $_SESSION['user']);
            $userTable->execute();
            $user = $userTable->fetch()['username'];
            echo "<h2>User: " . $user . "</h2>";
        } catch (PDOException $e) {
            echo $e;
        }

        if (isset($_SESSION['message'])) {
            echo "<h1>" . $_SESSION['message'] . "</h1>";
            unset($_SESSION['message']);
        }
    ?>

        <a href="logout.php">Logout</a>
        <br>
        <form action="backend/createroom.php" method="post">
            <h2>Create a new room</h2>
            <button type='submit'>Create</button>
        </form>
        <form action="room.php" method="get">
            <h2>Join room with id</h2>
            <input type="number" name="id" required>
            <button type='submit'>Join Room</button>
        </form>

        <table>
            <tr>
                <th>Rooms</th>
            </tr>


            <?php
            try {
                $roomTable = $dbh->prepare("SELECT * FROM `rooms`;");
                $roomTable->execute();
                $rooms = $roomTable->fetchAll();
                foreach ($rooms as $room) {
                    echo "<tr>";
                    echo "<td><a href='room.php?id=" . $room['id'] . "'>" . $room['id'] . "</a></td>";
                    echo "</tr>";
                }
            } catch (PDOException $e) {
                echo $e;
            }
            ?>
        </table>

    <?php
    } else {
    ?>
        <a href="login.php">Login</a>
        <a href="signup.php">Signup</a>

    <?php
    }

    ?>


</body>

</html>
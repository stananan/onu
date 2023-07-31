<html>

<head>
    <title>Drop Blewit Database</title>
</head>

<body>
    <?php
    require "../realconfig.php";

    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
        $dbh->exec('DROP TABLE IF EXISTS users, rooms, messages;');
        echo "<p>Successfully dropped databases</p>";
        session_start();

        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    } catch (PDOException $e) {
        echo "<p>Error: {$e->getMessage()}</p>";
    }
    ?>
</body>

</html>
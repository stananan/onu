<html>

<head>
    <title>Installing database</title>
</head>

<body>
    <?php
    require_once "../realconfig.php";
    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PASSWORD);
        //create comic table
        $query = file_get_contents('database.sql');
        $dbh->exec($query);
        echo "<p>Successfully installed databases</p>";
    } catch (PDOException $e) {
        echo "<p>Error: {$e->getMessage()}</p>";
    }

    ?>
</body>

</html>
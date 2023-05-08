<?php
    $name = $_POST["name"];
    $type = $_POST["type"];
    $location = $_POST["location"];
    $state = $_POST["state"];
    $homepage = $_POST["homepage"];

    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        $sql = "INSERT INTO universities (name, type, location, state, homepage) VALUES (?, ?, ?, ?, ?)";
        $statement = $link->prepare($sql);
        $statement->bind_param("sssss", $name, $type, $location, $state, $homepage);
        $statement->execute();
		$statement->close();

        echo "<script>history.back();</script>";
    }
?>
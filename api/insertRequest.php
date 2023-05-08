<?php
    $program = $_GET["prog"];
    $grade = $_GET["grad"];

    $hashcode = uniqid("MPR");

    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        $sql = "INSERT INTO requests (program, grade, hashCode) VALUES (?, ?, ?)";
        $statement = $link->prepare($sql);
        $statement->bind_param("sss", $program, $grade, $hashcode);
        $statement->execute();
		$statement->close();
    }
    echo $hashcode;
?>
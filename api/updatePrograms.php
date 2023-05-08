<?php
    $hflag = $_GET['subject'];
    $id = $_GET["id"];
    $process = $_GET["process"];
    $email = $_GET["email"];

    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        $sql = "UPDATE programs SET process = ?, email = ? WHERE id = ?";
        $statement = $link->prepare($sql);
        $statement->bind_param("ssi", $process, $email, $id);
        $statement->execute();
		$statement->close();
    }
?>
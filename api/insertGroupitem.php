<?php
    $hflag = $_GET["subject"];
    $reqid = $_GET["req"];
    $module = $_GET["module"];

    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        if ($hflag == "Geschichte") {
            $sql = "INSERT INTO groupitems_hist (req, name) VALUES (?, ?)";
        }
        else {
            $sql = "INSERT INTO groupitems (req, name) VALUES (?, ?)";
        }
        $statement = $link->prepare($sql);
        $statement->bind_param("ss", $reqid, $module);
        $statement->execute();
		$statement->close();
    }
?>
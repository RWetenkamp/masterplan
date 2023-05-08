<?php
    $hflag = $_GET['subject'];
    $uni = $_GET["uni"];
    $program = $_GET["program"];
    $type = $_GET["type"];
    $req_name = $_GET["req_name"];
    $value = $_GET["value"];
    $unit = $_GET['unit'];
    $mandatory = $_GET["mandatory"];
    $orgroup = $_GET["orgroup"];

    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        if ($hflag == "Geschichte") {
            $sql = "INSERT INTO requirements_hist (uni, program, type, name, value, unit, mandatory, orgroup) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        }
        else {
            $sql = "INSERT INTO requirements (uni, program, type, name, value, unit, mandatory, orgroup) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        }
        $statement = $link->prepare($sql);
        $statement->bind_param("ssssssss", $uni, $program, $type, $req_name, $value, $unit, $mandatory, $orgroup);
        $statement->execute();
		$statement->close();
    }
?>
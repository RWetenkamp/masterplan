<?php
    $hflag = $_GET['subject'];
    $uni = $_GET["uni"];
    $name = $_GET["name"];
    $category = $_GET["category"];
    $degree = $_GET["degree"];
    $language = $_GET["language"];
    $ects = $_GET['ects'];
    $duration = $_GET["duration"];
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
        if ($hflag == "Geschichte") {
            $sql = "INSERT INTO programs_hist (uni, name, category, degree, language, ects, duration, process, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }
        else {
            $sql = "INSERT INTO programs (uni, name, category, degree, language, ects, duration, process, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }
        $statement = $link->prepare($sql);
        $statement->bind_param("sssssssss", $uni, $name, $category, $degree, $language, $ects, $duration, $process, $email);
        $statement->execute();
		$statement->close();
    }
?>
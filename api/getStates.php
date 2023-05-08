<?php
    header('Content-Type: application/json');
    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        $sql = "SELECT DISTINCT state FROM institutions ORDER BY state ASC";
        $statement = $link->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();
        $text = "[";
        while ($program = (array)$result->fetch_object()) {
            $text = $text.'"'.$program['state'].'",';
        }
        $text = $text."]";
        $text = str_replace(",]", "]", $text);
        echo $text;
		$statement->close();
    }
?>
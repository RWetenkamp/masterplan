<?php
    header('Content-Type: application/json');
    $query = $_GET["q"];
    $query = "%".$query."%";
    $query = str_replace(" ", "%", $query);
    $query = str_replace("-", "%", $query);
    $query = str_replace("FH", "Fachhochschule", $query);
    $query = str_replace("Europa", "Europa-", $query);
    $query = str_replace("DH", "Duale Hochschule", $query);
    $query = str_replace("HS", "Hochschule", $query);
    $query = str_replace("TU", "Technische Universität", $query);
    $query = str_replace("Uni ", "Universität ", $query);
    

    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        $sql = "SELECT * FROM institutions WHERE name LIKE ? OR short_name LIKE ?";
        $statement = $link->prepare($sql);
        $statement->bind_param("ss", $query, $query);
        $statement->execute();
        $result = $statement->get_result();
        echo "[";
        while ($program = (array)$result->fetch_object()) {
            echo '"'.$program['name'].'",';
        }
        echo "]";
		$statement->close();
    }
?>
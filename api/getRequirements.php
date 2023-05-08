<?php
    $hflag = $_GET['subject'];
    $id = $_GET['id'];
    header('Content-Type: application/json');
    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        if ($hflag == "Geschichte") {
            $sql = "SELECT * FROM requirements_hist WHERE program = ?";
        }
        else {
            $sql = "SELECT * FROM requirements WHERE program = ?";
        }
        $statement = $link->prepare($sql);
        $statement->bind_param("s", $id);
        $statement->execute();
        $result = $statement->get_result();
        $matchingItems = [];
        while ($program = (array)$result->fetch_object()) {
            $groupitems = findGroupItems($program["id"], $link, $hflag);
            array_push($matchingItems, array("id" => $program["id"], "type" => $program["type"], "name" => $program["name"], "specifications" => $groupitems, "value" => $program["value"], "unit" => $program["unit"], "ongroup" => $program["ongroup"]));
        }
        echo json_encode($matchingItems);
		$statement->close();
    }

    function findGroupItems($reqid, $link, $hflag) {
        if ($hflag == "Geschichte") {
            $sql2 = "SELECT * FROM groupitems_hist WHERE req = ?";
        }
        else {
            $sql2 = "SELECT * FROM groupitems WHERE req = ?";
        }
        $statement2 = $link->prepare($sql2);
        $statement2->bind_param("s", $reqid);
        $statement2->execute();
        $result2 = $statement2->get_result();

        $resultlist = [];
        while ($gritem = (array)$result2->fetch_object()) {
            array_push($resultlist, $gritem["name"]);
        }
        return $resultlist;
    }

?>
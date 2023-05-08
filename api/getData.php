<?php
    $hflag = $_GET['subject'];
    header('Content-Type: application/json');
    $states = $_GET['states'];
    $degrees = $_GET['degrees'];
    $topics = $_GET['topics'];
    $query = $_GET['query'];
    $query = "%".$query."%";

    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $states_arr = explode(";", $states);
    $degrees_arr = explode(";", $degrees);
    $topics_arr = explode(";", $topics);

    $states_arr_e = [];
    if ($states <> "") {
        foreach ($states_arr as $state) {
            array_push($states_arr_e, trim($state));
        }
    }
    else {
        $states_arr_e = json_decode(file_get_contents("https://masterplan.rwetenkamp.de/api/getStates.php"));
    }
    

    $degrees_arr_e = [];
    if ($degrees <> "") {
        foreach ($degrees_arr as $degree) {
            array_push($degrees_arr_e, trim($degree));
        }
    }
    else {
        $degrees_arr_e = json_decode(file_get_contents("https://masterplan.rwetenkamp.de/api/getDegrees.php?subject=".$hflag));
    }
    

    $topics_arr_e = [];
    if ($topics <> "") {
        foreach ($topics_arr as $topic) {
            array_push($topics_arr_e, trim($topic));
        }
    }
    else {
        $topics_arr_e = json_decode(file_get_contents("https://masterplan.rwetenkamp.de/api/getCategories.php?subject=".$hflag));
    }
    

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        if ($query == "") {
            $sql = "";
            if ($hflag == "Geschichte") {
                $sql = "SELECT programs_hist.*, state, universities.name as uniname FROM programs_hist JOIN universities ON universities.id = programs_hist.uni ORDER BY id ASC";
            }
            else {
                $sql = "SELECT programs.*, state, universities.name as uniname FROM programs JOIN universities ON universities.id = programs.uni ORDER BY id ASC";
            }
            $statement = $link->prepare($sql);
            // $statement->bind_param("sss", $states_sql, $degrees_sql, $topics_sql);
            $text = [];
            $statement->execute();   
            $result = $statement->get_result();
            while ($program = (array)$result->fetch_object()) {
                array_push($text, array('id' => $program['id'], 'state' => $program['state'], 'uni' => $program['uniname'], 'name' => $program['name'], 'category' => $program['category'], 'degree' => $program['degree'], 'language' => $program["language"], "ects" => $program["ects"], "duration" => $program["duration"], "process" => $program["process"], "email" => $program["email"]));
            }
            $statement->close();
        }
        else {
            $sql = "";
            if ($hflag == "Geschichte") {
                $sql = "SELECT programs_hist.*, state, universities.name as uniname FROM programs_hist JOIN universities ON universities.id = programs_hist.uni WHERE universities.name LIKE ? OR programs_hist.name LIKE ? OR category LIKE ? ORDER BY id ASC";
            }
            else {
                $sql = "SELECT programs.*, state, universities.name as uniname FROM programs JOIN universities ON universities.id = programs.uni WHERE universities.name LIKE ? OR programs.name LIKE ? OR category LIKE ? ORDER BY id ASC";
            }
            
            $statement = $link->prepare($sql);
            $statement->bind_param("sss", $query, $query, $query);
            $text = [];
            $statement->execute();   
            $result = $statement->get_result();
            while ($program = (array)$result->fetch_object()) {
                array_push($text, array('id' => $program['id'], 'state' => $program['state'], 'uni' => $program['uniname'], 'name' => $program['name'], 'category' => $program['category'], 'degree' => $program['degree'], 'language' => $program["language"], "ects" => $program["ects"], "duration" => $program["duration"], "process" => $program["process"], "email" => $program["email"]));
            }
            $statement->close();
        }
        $matchingItems = [];

        foreach ($text as $item) {
            if (in_array($item['state'], $states_arr_e) && in_array($item['degree'], $degrees_arr_e) && in_array($item['category'], $topics_arr_e)) {
                array_push($matchingItems, $item);
            }
        }
        // DEBUG: echo json_encode([$states_arr_e, $degrees_arr_e, $topics_arr_e]);
        echo json_encode($matchingItems);
    }
?>
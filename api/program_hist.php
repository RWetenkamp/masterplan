<?php
    $uniID = $_GET['uniid'];

    $names = array();
    $ids = array();

    $host_name = 'db5006458411.hosting-data.io';
    $database = 'dbs5373455';
    $user_name = 'dbu1092438';
    $password = 'kiJXm84TM7dPsM9';

    $link = new mysqli($host_name, $user_name, $password, $database);

    if ($link->connect_error) {
        die('<p>Verbindung zum MySQL Server fehlgeschlagen: '. $link->connect_error .'</p>');
    } else {
        if ($uniID <> "") {
            $sql = "SELECT * FROM universities WHERE id = ?"; 
            $statement = $link->prepare($sql);
            $statement->bind_param("i", $uniID);   
        }
        else {
            $sql = "SELECT * FROM `universities` WHERE NOT EXISTS (SELECT * FROM `programs` WHERE `universities`.id = `programs`.uni) LIMIT 1";
            $statement = $link->prepare($sql);
        }
        $statement->execute();
        $result = $statement->get_result();
        $uni = (array)$result->fetch_object();
        $statement->close();
    }
    function ampel($x) {
        if ($x == 0) {
            return '<span style="padding: 1%; background-color: red; color: white;">Erhebung unvollständig</span>';
        }
        else if ($x == 1) {
            return '<span style="padding: 1%; background-color: darkorange; color: white;">Vorprüfung ausstehend</span>';
        }
        else if ($x == 2) {
            return '<span style="padding: 1%; background-color: gold; color: white;">Bestätigung ausstehend</span>';
        }
        else if ($x == 3) {
            return '<span style="padding: 1%; background-color: lime; color: black;">Korrektur ausstehend</span>';
        }
        else if ($x == 4) {
            return '<span style="padding: 1%; background-color: green; color: white;">Erhebung abgeschlossen</span>';
        }
        else {
            return '<span style="padding: 1%; background-color: gray; color: white;">Kein Status vorliegend</span>';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Add programs
        </title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Lexend+Deca&display=swap" rel="stylesheet">
        <meta charset="UTF-8">
        <style>
            * {
                font-family: 'Lexend Deca';
            }
            html, body {
                padding: 0.5%;
            }
            input, select {
                padding: 1vw;
            }
            td, th {
                padding: 0.5vw;
            }
            td, th {
                background-color: #eeeded;
            }
            button {
                padding: 1vw;
                background: lightgreen;
                border: 5px solid lightgreen;
            }
            button:hover {
                text-decoration: underline;
                padding: 1vw;
                background: none;
                cursor: pointer;
                border: 5px solid lightgreen;
            }
            th, td {
                text-align: center;
            }
            .popup {
                background-color: rgb(240,240,240);
                z-index: 7;
                padding: 2vw;
                box-shadow: 1%;

                width: 30vw;
                max-height: 70vh;
                position: absolute;
                top: 18vh;
                left: 32vw;
                padding: 1vw;
                border-radius: 5px;
                color: black;
                transition: display 1s;
            }
            .popup_headline {
                text-align: left;
                width: 100%;
                font-weight:600;
                font-size: 2vw;
                float: left;
            }
            #popup_content {
                display: inline-block;
                overflow-y: scroll;
                max-height: 60vh;
                width: 100%;
            }
            .hidden {
                display: none;
                transition: display 1s;
            }
            #close {
                position: absolute;
                color: red;
                top: 1vh; 
                left: 94%;
                font-size: 2vw;
            }
            #close:hover {
                cursor: pointer;
                color: white;
            }
        </style>
        <script>
            function submit() {
                let uni, name, category, degree, language, ects, duration;
                uni = document.getElementsByName("uni")[0].value;
                name = document.getElementsByName("name")[0].value;
                degree = document.getElementsByName("degree")[0].value;
                category = document.getElementsByName("category")[0].value;
                language = document.getElementsByName("language")[0].value;
                ects = document.getElementsByName("ects")[0].value;
                duration = document.getElementsByName("duration")[0].value;
                process = document.getElementsByName("process")[0].value;
                email = document.getElementsByName("email")[0].value;

                var request = new XMLHttpRequest();
                request.open("GET","insertProgram.php?subject=Geschichte&uni="+uni+"&name="+name+"&category="+category+"&degree="+degree+"&language="+language+"&ects="+ects+"&duration="+duration+"&process="+process+"&email="+email);
                request.addEventListener('load', function(event) {
                if (request.status >= 200 && request.status < 300) {
                    console.log(request.responseText);
                } else {
                    console.warn(request.statusText, request.responseText);
                }
                });
                request.send();
            }
            function addToGroup() {
                let reqid, modules;
                reqid = document.getElementById("popup_id").value;
                modules = document.getElementsByName("modules")[0].value;
                let sentModules = [];
                var request = new XMLHttpRequest();
                request.open("GET","insertGroupitem.php?subject=Geschichte&req="+reqid+"&module="+modules);
                request.addEventListener('load', function(event) {
                if (request.status >= 200 && request.status < 300) {
                    console.log(request.responseText);
                    sentModules.push(modules);
                    sentModules.forEach(modul =>{
                        document.getElementById("sentModules").innerHTML += modul+"<br>";
                    })
                    document.getElementsByName("modules")[0].value = '';
                } else {
                    console.warn(request.statusText, request.responseText);
                }
                });
                request.send();
            }

            function addRequ() {
                let uni, program, type, req_name, value, unit, mandatory, orgroup;
                uni = document.getElementsByName("uni")[0].value;
                program = document.getElementsByName("program")[0].value;
                type = document.getElementsByName("type")[0].value;
                req_name = document.getElementsByName("req_name")[0].value;
                value = document.getElementsByName("value")[0].value;
                unit = document.getElementsByName("unit")[0].value;
                mandatory = document.getElementsByName("mandatory")[0].value;
                orgroup = document.getElementsByName("orgroup")[0].value;

                var request = new XMLHttpRequest();
                request.open("GET","insertRequirement.php?subject=Geschichte&uni="+uni+"&program="+program+"&type="+type+"&req_name="+req_name+"&value="+value+"&unit="+unit+"&mandatory="+mandatory+"&orgroup="+orgroup);
                request.addEventListener('load', function(event) {
                if (request.status >= 200 && request.status < 300) {
                    console.log(request.responseText);
                } else {
                    console.warn(request.statusText, request.responseText);
                }
                });
                request.send();
            }
            function openPopup(id) {
                document.getElementById("popup").classList.remove("hidden");
                document.getElementById("popup_id").value = id;
            }
            function closePopup() {
                document.getElementById("popup").classList.add("hidden");
            }
        </script>
    </head>
    <body>
        <h1>
            <?php echo $uni['id']." | ".$uni['name'];?>
        </h1>
        
        <span>
            <?php echo $uni['location'];?>, <?php echo $uni['state'];?>
        </span>
        <br><br><bR><br>
        <?php echo ampel($uni['status']); ?>
        <br><br><bR><br>
        <button onclick="window.open('<?php echo $uni['homepage'];?>', '_blank');">> Homepage aufrufen</button>
        <br><br>
        <h2>
            Studiengänge
        </h2>
        <table>
            <tr>
                <th>
                    Nr.
                </th>
                <th>
                    Bezeichnung
                </th>
                <th>
                    Kategorie
                </th>
                <th>
                    Abschluss
                </th>
                <th>
                    Sprache
                </th>
                <th>
                    ECTS
                </th>
                <th>
                    Regelstudienzeit
                </th>
                <th>
                    Zulassungsverfahren
                </th>
                <th>
                    E-Mail-Kontakt
                </th>
                <th>
                    Aktionen
                </th>
            </tr>
            <?php
                $sql2 = "SELECT * FROM `programs_hist` WHERE uni = ?";
                $statement2 = $link->prepare($sql2);
                $statement2->bind_param("i", $uniID); 
                $statement2->execute();
                $result2 = $statement2->get_result();
                while ($program = (array)$result2->fetch_object()) {
                    array_push($names, $program['name']);
                    array_push($ids, $program['id']);
                    echo "<tr><td>".$program['id'].'</td><td style="max-width: 10vw;">'.$program['name']."</td><td>".$program['category']."</td><td>".$program['degree']."</td><td>".$program['language']."</td><td>".$program['ects']."</td><td>".$program['duration']."</td><td>";
                    if ($program['process'] != "") {
                        echo $program['process'];
                    }
                    else {
                        echo '<select name="process_'.$program['id'].'">
                        <option>
                            Kriteriengeleitet
                        </option>
                        <option>
                            Bewerbung
                        </option>
                        <option>
                            Auswahltest
                        </option>
                        <option>
                            NC-Beschränkung
                        </option>
                        <option>
                            Mischform
                        </option>
                        <option>
                            Sonstiges
                        </option>
                    </select>';
                    }
                    echo "</td><td>";
                    if ($program['email'] != "") {
                        echo $program['email'];
                    }
                    else {
                        echo '<input type="text" name="email_'.$program['id'].'">';
                    }
                    echo '</td><td><button onclick="addAdditional('.$program['id'].');">Ergänzen</button></td></tr>';
                }
                $statement2->close(); 
            ?>
            <tr>
                <td>
                    ➜ 
                    <input type="hidden" name="uni" value="<?php echo $uni['id']?>">
                </td>
                <td>
                    <input type="text" name="name" placeholder="Bezeichnung">
                </td>
                <td>
                    <input type="text" name="category" list="categories" />
                    <datalist id="categories">
                        <option default>
                            Geschichte
                        </option>
                        <option>
                            Internationale Geschichte
                        </option>
                        <option>
                            Neuere Geschichte
                        </option>
                        <option>
                            Alte Geschichte
                        </option>
                        <option>
                            Spezialisierungen
                        </option>
                        <option>
                            Interdisziplinär
                        </option>
                        <option>
                            Sonstiges
                        </option>
                    </datalist>
                </td>
                <td>
                    <select name="degree">
                        <option default>
                            Master of Science (M. Sc.)
                        </option>
                        <option>
                            Master of Engineering (M. Eng.)
                        </option>
                        <option>
                            Master of Arts (M. A.)
                        </option>
                        <option>
                            Master of Education (M. Ed.)
                        </option>
                        <option>
                            Sonstiges
                        </option>
                    </select>
                </td>
                <td>
                    <select name="language">
                        <option default>
                            Deutsch
                        </option>
                        <option>
                            Englisch
                        </option>
                        <option>
                            Beides
                        </option>
                        <option>
                            Wählbar
                        </option>
                        <option>
                            Sonstiges
                        </option>
                    </select>
                </td>
                <td>
                    <select name="ects">
                        <option default>
                            120
                        </option>
                        <option>
                            90
                        </option>
                        <option>
                            Sonstiges
                        </option>
                    </select>
                </td>
                <td>
                    <select name="duration">
                        <option default>
                            4 Semester
                        </option>
                        <option>
                            3 Semester
                        </option>
                        <option>
                            5 Semester
                        </option>
                        <option>
                            5 Trimester
                        </option>
                    </select>
                </td>
                <td>
                    <select name="process">
                        <option>
                            Kriteriengeleitet
                        </option>
                        <option>
                            Bewerbung
                        </option>
                        <option>
                            Auswahltest
                        </option>
                        <option>
                            NC-Beschränkung
                        </option>
                        <option>
                            Mischform
                        </option>
                        <option>
                            Sonstiges
                        </option>
                    </select>
                </td>
                <td>
                    <input type="text" name="email" placeholder="E-Mail-Adresse">
                </td>
                <td>
                    <button onclick="submit();">> Eintragen</button>
                </td>
            </tr>
        </table>
        <br><br>
        <h2>
            Zulassungsvoraussetzungen
        </h2>
        <table>
            <tr>
                <th>
                    Nr.
                </th>
                <th>
                    Studiengang
                </th>
                <th>
                    Typ
                </th>
                <th>
                    Bezeichnung
                </th>
                <th>
                    Wert
                </th>
                <th>
                    Einheit
                </th>
                <th>
                    Zwingend
                </th>
                <th>
                    ODER-Gruppe
                </th>
                <th>
                    Aktionen
                </th>
            </tr>
            <?php
                $sql3 = "SELECT * FROM `requirements_hist` WHERE uni = ? ORDER BY program";
                $statement3 = $link->prepare($sql3);
                $statement3->bind_param("i", $uniID); 
                $statement3->execute();
                $result3 = $statement3->get_result();
                while ($requirement = (array)$result3->fetch_object()) {
                    echo "<tr><td>".$requirement['id']."</td><td>".$requirement['program']."</td><td>".$requirement['type']."</td><td>".$requirement['name']."</td><td>".$requirement['value']."</td><td>".$requirement['unit']."</td><td>".$requirement['mandatory']."</td><td>".$requirement['orgroup'].'</td><td><button onclick="openPopup('.$requirement['id'].');"> Spezifizieren</button></td></tr>';
                }
                $statement3->close(); 
            ?>
            <tr>
                <td>
                    ➜ 
                    <input type="hidden" name="uni" value="<?php echo $uni['id']?>">
                </td>
                <td>
                    <select name="program">
                    <?php
                        $looper = 0;
                        while ($looper < count($names)) {
                            echo "<option value=".$ids[$looper].">".$names[$looper]." (ID ".$ids[$looper].")</option>";
                            $looper = $looper + 1;
                        }
                    ?>
                    </select>
                </td>
                <td>
                    <select name="type">
                        <option>
                            Note
                        </option>
                        <option>
                            Modulkenntnis
                        </option>
                        <option>
                            Sprache
                        </option>
                        <option>
                            Forschungsbeitrag
                        </option>
                        <option>
                            Sonstiges
                        </option>
                        <option>
                            Nachholmöglichkeit
                        </option>
                        <option>
                            Bewerbung
                        </option>
                    </select>
                </td>
                <td>
                    <input type="text" name="req_name">
                </td>
                <td>
                    <input type="text" name="value">
                </td>
                <td>
                    <select name="unit">
                        <option>
                            Note (1.0 - 5.0)
                        </option>
                        <option>
                            ECTS-Punkte
                        </option>
                        <option>
                            CEFR-Level (A1 - C2)
                        </option>
                        <option>
                            Abgaben
                        </option>
                        <option>
                            Sonstiges
                        </option>
                    </select>
                </td>
                <td>
                    <select name="mandatory">
                        <option value="true">
                            Ja
                        </option>
                        <option value="false">
                            Nein
                        </option>
                    </select>
                </td>
                <td>
                    <input type="text" name="orgroup">
                </td>
                <td>
                    <button onclick="addRequ();" <?php if (count($names) == 0) {echo " disabled";}?>>> Hinzufügen</button>
                </td>
            </tr>
        </table>
        <br><br>
        <div id="popup" class="popup hidden">
            <h3>Fächergruppenspezifikation</h3><br>
            <div id="close" onclick="closePopup();">X</div>
            <div id="popup_content">
                <input type="hidden" id="popup_id">
                <div id="sentModules"></div><br>
                <input name="modules" placeholder="Modul">
                <br><br>
                <button onclick="addToGroup();">> Hinzufügen</button>
            </div>
        </div>
        <button onclick="window.location = '/api/program_hist.php?uniid='+<?php echo $uni['id'];?>;">> Aktualisieren</button> &nbsp; &nbsp;
        <button onclick="window.location = '/api/program_hist.php';">> Fortfahren</button>
        <br><br><br><br>
        <details>
            <summary>
                Ausfüllhinweise
            </summary>
            <ul>
                <li>Fließkommazahlen bitte mit Punkt "." als Separator angeben.</li>
                <li>ODER-Gruppe: Hier können zusammenhängende Voraussetzungen mit einer für diesen Studiengang eindeutigen Gruppenkennung versehen werden. <bR>
                Es ist unerheblich, wie diese Kennung aussieht.<br>
                <i>Beispiel:</i> TheoMat für "30 ECTS in Theoretischer Informatik oder 30 ECTS in Mathematik" - zwei Bedingungen mit der gleichen ODER-Gruppe.</li>
                <li>Nach Möglichkeit bitte nicht auf "Sonstiges" ausweichen.</li>
            </ul>
            <h3>Vielen Dank!</h3>
        </details>
    </body>
</html>
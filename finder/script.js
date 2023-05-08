var texts = {
    "uni": {
        "uni": "Dieser Weg ist meistens problemlos möglich!<br>Lediglich für spezielle Universitäten oder Exzellenzstudiengänge gibt es Ausnahmen."
    },
    "haw": {
        "haw": "Dieser Weg ist häufig problemlos möglich!<br>Achten Sie bitte auf die ECTS-Punktzahl Ihres Bachelor-Abschlusses.",
        "uni": "Dieser Weg ist ein bisschen holpriger!<br>Für den Wechsel von der HAW an die Universität gibt es für viele Studiengänge spezielle Voraussetzungen."
    }
};

function cngTo() {
    let select_from = document.getElementById("select_from").value;
    let select_to = document.getElementById("select_to").value;

    try {
        document.getElementById("selection").innerHTML = texts[select_from][select_to];
        document.getElementById("startline").innerHTML = "Finden Sie hier Ihren optimalen Weg in die Zukunft.";
        loadPrivacy();
    }
    catch {
        document.getElementById("selection").innerHTML = "Sorry! Hier sind wir noch nicht soweit!";
    }
}

function loadPrivacy() {
    document.getElementById("privacy").classList.remove("hidden");
}

function proceed(event) {
    switch (event) {
        case "privacy-accepted": 
            document.getElementById("privacy").classList.add("hidden");
            document.getElementById("presurvey").classList.remove("hidden");
            break;
        case "presurvey-completed":
            var institution = document.getElementById("institution").value;
            var program = document.getElementById("programInput").value;
            var grade = document.getElementById("grade").value;

            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", "../api/getProgramId.php?inst="+institution+"&prog="+program, false ); // false for synchronous request
            xmlHttp.send( null );
            var programId = xmlHttp.responseText;
            console.log("Program-ID: "+programId);

            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", "../api/insertRequest.php?&prog="+programId+"&grad="+grade, false ); // false for synchronous request
            xmlHttp.send( null );
            var response = xmlHttp.responseText;
            
            document.getElementById("presurvey").classList.add("hidden");
            document.getElementById("programDetails").classList.remove("hidden");

            document.getElementById("institutionB").innerHTML = institution;
            document.getElementById("programB").innerHTML = program;
            document.getElementById("codeB").innerHTML = response;
            break;
    }
} 

function loadKnown(event) {
    switch (event) {
        case "universities":
            var query = document.getElementById("institution").value;
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", "../api/getInstitutions.php?q="+query, false ); // false for synchronous request
            xmlHttp.send( null );
            var response = xmlHttp.responseText;
            response = response.replace(",]", "]");
            var resultArray = JSON.parse(response);
            document.getElementById("knownInstitutions").innerHTML = "";
            resultArray.forEach(institutions => {
                document.getElementById("knownInstitutions").innerHTML += '<a class="knownUni" onclick="selectUni(`'+institutions+'`);">'+institutions+'</a>';
            });
            break;
        case "programs":
            var query = document.getElementById("institution").value;
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", "../api/getPrograms.php?q="+query, false ); // false for synchronous request
            xmlHttp.send( null );
            var response = xmlHttp.responseText;
            response = response.replace(",]", "]");
            var resultArray = JSON.parse(response);
            document.getElementById("knownPrograms").innerHTML = "";
            resultArray.forEach(program => {
                document.getElementById("knownPrograms").innerHTML += '<a class="knownUni" onclick="selectProgram(`'+program+'`);">'+program+'</a>';
            });
            break;
        case "modules":
            var query = document.getElementById("module").value;
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", "../api/getModules.php?q="+query, false);
            xmlHttp.send( null );
            var response = xmlHttp.responseText;
            response = response.replace(",]", "]");
            var resultArray = JSON.parse(response);
            document.getElementById("knownModules").innerHTML = "";
            resultArray.forEach(institutions => {
                document.getElementById("knownModules").innerHTML += '<a class="knownUni" onclick="selectModule(`'+institutions+'`);">'+institutions+'</a>';
            });
            break;

    }
}

function selectUni(uni) {
    document.getElementById("knownInstitutions").innerHTML = "";
    document.getElementById("institution").value = uni;
    document.getElementById("institution").setAttribute("disabled", "true");
    document.getElementById("unselectBtn").classList.remove("hidden");
    document.getElementById("programInput").removeAttribute("disabled");
}

function selectProgram(uni) {
    document.getElementById("knownPrograms").innerHTML = "";
    document.getElementById("programInput").value = uni;
    document.getElementById("programInput").setAttribute("disabled", "true");
    document.getElementById("unselectBtn2").classList.remove("hidden");
}

function unselect(event) {
    switch (event) {
        case 'institution':
            document.getElementById("institution").removeAttribute("disabled");
            document.getElementById("institution").value = "";
            document.getElementById("knownPrograms").innerHTML = "";
            document.getElementById("unselectBtn").classList.add("hidden");
            document.getElementById("programInput").setAttribute("disabled", "true");
            break;
        case 'program':
            document.getElementById("programInput").removeAttribute("disabled");
            document.getElementById("programInput").value = "";
            document.getElementById("knownPrograms").innerHTML = "";
            document.getElementById("unselectBtn2").classList.add("hidden");
            break;
    }
}
    
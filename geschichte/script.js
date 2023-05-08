function loadKnown(event) {
    switch (event) {
        case "states":
            document.getElementById("knownTopics").innerHTML = '';
            document.getElementById('knownStates').innerHTML = '';
            document.getElementById('knownDegrees').innerHTML = '';
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", "../api/getStates.php", false ); // false for synchronous request
            xmlHttp.send( null );
            var response = xmlHttp.responseText;
            var resultArray = JSON.parse(response);
            document.getElementById("knownStates").innerHTML = "";
            resultArray.forEach(state => {
                document.getElementById("knownStates").innerHTML += '<a class="knownUni" id="'+state+'" onclick="selectState(`'+state+'`);">'+state+'</a>';
            });
            break;
        case "degrees":
            document.getElementById("knownTopics").innerHTML = '';
            document.getElementById('knownStates').innerHTML = '';
            document.getElementById('knownDegrees').innerHTML = '';
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open( "GET", "../api/getDegrees.php?subject=Geschichte", false ); // false for synchronous request
            xmlHttp.send( null );
            var response = xmlHttp.responseText;
            var resultArray = JSON.parse(response);
            document.getElementById("knownDegrees").innerHTML = "";
            resultArray.forEach(degree => {
                document.getElementById("knownDegrees").innerHTML += '<a class="knownUni" id="'+degree+'" onclick="selectDegree(`'+degree+'`);">'+degree+'</a>';
            });
            break;
        case "topics":
            document.getElementById("knownTopics").innerHTML = '';
            document.getElementById('knownStates').innerHTML = '';
            document.getElementById('knownDegrees').innerHTML = '';
            var xmlHttp = new XMLHttpRequest();
            xmlHttp.open("GET", "../api/getCategories.php?subject=Geschichte", false);
            xmlHttp.send( null );
            var response = xmlHttp.responseText;
            var resultArray = JSON.parse(response);
            document.getElementById("knownTopics").innerHTML = "";
            resultArray.forEach(topic => {
                document.getElementById("knownTopics").innerHTML += '<a class="knownUni" id="'+topic+'" onclick="selectTopic(`'+topic+'`);">'+topic+'</a>';
            });
            break;

    }
}

function selectState(state) {
    document.getElementById(state).setAttribute("class", "hidden");
    document.getElementById("states").value += state+"; ";
}

function selectDegree(state) {
    document.getElementById(state).setAttribute("class", "hidden");
    document.getElementById("degrees").value += state+"; ";
}

function selectTopic(state) {
    document.getElementById(state).setAttribute("class", "hidden");
    document.getElementById("topics").value += state+"; ";
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

function getFlag(language) {
    if (language == "Englisch") {
        return "🇬🇧";
    }
    else if (language == "Deutsch") {
        return "🇩🇪";
    }
    else if (language == "Wählbar" || language == "Beides") {
        return "🇬🇧 / 🇩🇪";
    }
    else {
        return "";
    }
}

function getRequirements(id) {
    if (document.getElementById("requ-"+id).classList.contains("hidden")) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open("GET", "../api/getRequirements.php?subject=Geschichte&id="+id, false);
        xmlHttp.send( null );
        var response = xmlHttp.responseText;
        var resultArray = JSON.parse(response);
        document.getElementById("requ-"+id).classList.remove("hidden");
        document.getElementById("requ-"+id).innerHTML = `<h3>Zulassungsvoraussetzungen</h3>`;
        resultArray.forEach(topic => {
            var specifications = "&nbsp;";
            if (topic.specifications.length > 0) {
                specifications +='<span style="font-weight: normal;"><ul style="margin-top:1.5vh;">';
                topic.specifications.forEach(specification => {
                    specifications += "<li>"+specification+"</li>";
                });
                specifications += "</ul></span>";
            }
            document.getElementById("requ-"+id).innerHTML += `
                <div class="requirement">
                    <span class="requ-field" id="requ-type">`+topic.type+`

                    </span>
                    <span class="requ-field" id="requ-name">`+topic.name+specifications+`
                    
                    </span>
                    <span class="requ-field">`+topic.value+`

                    </span>
                    <span class="requ-field" id="requ-type">`+topic.unit+`

                    </span>
                </div>
            `;
        });
    }
    else {
        document.getElementById("requ-"+id).classList.add("hidden");
    }
}

function search() {
    var states = document.getElementById("states").value;
    var degrees = document.getElementById("degrees").value;
    var topics = document.getElementById("topics").value;
    var query = document.getElementById("query").value;

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.open("GET", "../api/getData.php?subject=Geschichte&states="+states+"&degrees="+degrees+"&topics="+topics+"&query="+query, false);
    xmlHttp.send( null );
    var response = xmlHttp.responseText;
    var resultArray = JSON.parse(response);
    document.getElementById("results").classList.remove("hidden");
    document.getElementById("results").innerHTML = `<h2>Ergebnisse</h2>`;
    resultArray.forEach(topic => {

        document.getElementById("results").innerHTML += 
        `<div class="result-collapsed" onclick="getRequirements(`+topic.id+`);">
                <span id="id">
                    # `+topic.id+`
                </span>
                <span id="title">`+
                    topic.name+`
                </span>
                <span id="language">`+
                getFlag(topic.language)+`
                </span>
                <br>
                <span id="id">
                </span>
                <span id="institution">`+topic.degree+` / `+topic.ects+` ECTS
                </span>
                <br><br>
                <span id="id">
                </span>
                <span id="institution">`+topic.uni+`
                </span>
                <div class="requirements hidden" id="requ-`+topic.id+`">
                    <!-- Add requirements here -->
                </div>
            </div>`;
    });
}

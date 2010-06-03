// Request erstellen
function createXMLHttpRequest() {

    var ab = false;
    // IE 7, Mozilla, Opera, Safari
    if (typeof(XMLHttpRequest) != 'undefined') {
        ab = new XMLHttpRequest();
    }
    if (!ab) {
        // IE 6 und älter
        try {
            ab  = new ActiveXObject("Msxml2.XMLHTTP");
        } catch(e) {
            try {
                ab  = new ActiveXObject("Microsoft.XMLHTTP");
            } catch(e) {
                ab  = false;
            }
        }
    }

    return ab;
}


// Antwort verarbeiten
function handleResponse() {

    if(req.readyState == 4){
        var response = req.responseText;
        var update = new Array();
        document.getElementById('overview').innerHTML = response;
        document.getElementById('overview_load').innerHTML = "";
    }
}

// Daten laden und ausgeben
function dooverview(wait){

    if (wait == "1"){
        document.getElementById('overview_load').innerHTML = "<div style=\"text-align: center; margin: 5px auto auto 5px; width: 200px; position: absolute;\"><table style=\"margin: auto auto;\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"tborder\"><tr class=\"trow1\"><td><span class=\"smalltext\"><img src=\"images/spinner.gif\" alt=\"Loading\" width=\"12\" height=\"12\" /> Loading...</span></td></tr><table></div>";
    }
    req.open('GET', 'xmlhttp.php?action=overview');
    req.onreadystatechange = handleResponse;
    req.send(null);
}
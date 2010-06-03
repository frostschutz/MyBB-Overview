/**
 * This file is part of Overview plugin for MyBB.
 * Copyright (C) 2005-2009 Michael Schlechtinger <kontakt@mybboard.de>
 * Copyright (C) 2010 Andreas Klauer <Andreas.Klauer@metamorpher.de>
 * License: GPLv3
 */

// Handle AJAX response
function overview_response()
{
    if(overview.readyState == 4)
    {
        document.getElementById('overview').innerHTML = overview.responseText;
        document.getElementById('overview_load').innerHTML = "";
    }
}

// Load and print data
function overview_request(wait)
{
    if(wait == "1")
    {
        document.getElementById('overview_load').innerHTML = "<div style=\"text-align: center; margin: 5px auto auto 5px; width: 200px; position: absolute;\"><table style=\"margin: auto auto;\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"tborder\"><tr class=\"trow1\"><td><span class=\"smalltext\"><img src=\"images/spinner.gif\" alt=\"Loading\" width=\"12\" height=\"12\" /> Loading...</span></td></tr><table></div>";
    }

    overview = new XMLHttpRequest();
    overview.open('GET', 'xmlhttp.php?action=overview');
    overview.onreadystatechange = overview_response;
    overview.send(null);
}

/* --- End of file. --- */

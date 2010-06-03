<?php
define("IN_MYBB", "1");
###################################
# Plugin Overview 3.0.3           #
# (c) 2005-2006 by MyBBoard.de    #
# Website: http://www.mybboard.de #
# All rights reserved             #
###################################

// Nicht cachen
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

// Nicht in Wer ist online-Liste
define("KILL_GLOBALS", 1);
define("NO_ONLINE", 1);

require("./global.php");

### Parser initiieren ###
require_once MYBB_ROOT."/inc/class_parser.php";
$parser = new postParser;

// Nur für Mitglieder?
if($mybb->settings['overview_no_guests'] == "yes") {
    if($mybb->user['uid'] == "0") {
        die("Not allowed!");
    }
}

/*if(empty($_SERVER['HTTP_REFERER'])) {
    die("Not allowed!");
}*/

$language = $mybb->settings['bblanguage'];

// Sprachdatei laden
$lang->load("overview");

// Anzahl der Spalten ermitteln
$num_columns = overview_num_columns();

//Nicht sichtbare Foren ausschließen
$unviewwhere = "";
$unviewable = get_unviewable_forums();
if($unviewable) {
    $unviewwhere = "AND fid NOT IN (".$unviewable.")";
}
        
// Variablen definieren
$overview_content = "";
$trow_message = "";
$overview = "";
        
// Sortierung auslesen und Daten ausgeben
$orderquery = $db->query("SELECT name from ".TABLE_PREFIX."settings WHERE name IN ('overview_do_newestusers','overview_do_topposters','overview_do_newestthreads','overview_do_mostreplies','overview_do_favouritethreads','overview_do_newestposts') ORDER BY value ASC");
while ($order = $db->fetch_array($orderquery)) {
    $overview_content .= call_user_func($order['name']);
}

// Nachricht zeigen?
if($mybb->settings['overview_trow_message_onoff'] == "yes") {
    $overview_message = do_htmlentities($mybb->settings['overview_trow_message']);
    eval("\$trow_message = \"".$templates->get("index_overview_message")."\";");
}

// Template laden
eval("\$overview = \"".$templates->get("index_overview")."\";");

// Übersicht ausgeben
echo $overview;
?>
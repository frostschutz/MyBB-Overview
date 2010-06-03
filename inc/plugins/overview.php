<?php
###################################
# Plugin Overview 3.2             #
# (c) 2005-2009 by MyBBoard.de    #
# Website: http://www.mybboard.de #
# License: GPLv3 / license.txt    #
###################################

if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

### Benötigte Daten für das Plugin-System ###
$plugins->add_hook("index_start", "overview");
$plugins->add_hook("xmlhttp", "overview_ajax");
$plugins->add_hook("index_end", "overview_end");

### Informationen zum Plugin ###
function overview_info()
{
    return array(
        "name"          => "Overview",
        "title"         => "Overview",
        "description"   => "Displays a box on the index page that shows different infomations about your board.",
        "website"       => "http://www.mybboard.de",
        "author"        => "MyBBoard.de",
        "authorsite"    => "http://www.mybboard.de",
        "version"       => "3.2.2",
        "guid"          => "79710704156952a4cf8793808c6ab3ea",
        "compatibility" => "14*"
    );
}

### Installation ###
function overview_install()
{
    global $db;

    // Templates für dieses Plugin einfügen
    $templatearray = array(
        "title" => "index_overview",
        "template" => "<table width=\"100%\" border=\"0\" cellspacing=\"{\$theme[\'borderwidth\']}\" cellpadding=\"0\" class=\"tborder\">
        <thead>
        <tr><td colspan=\"{\$num_columns}\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"{\$theme[\'tablespace\']}\" width=\"100%\"><tr class=\"thead\"><td>{\$collapseinsert1}<strong>{\$lang->overview_overview}</strong></td></tr></table></td>
        </tr>
        </thead>
        <tbody{\$collapseinsert2}>
        {\$trow_message}
        <tr>
        {\$overview_content}
        </tr>
        </tbody>
        </table>
        <br />",
        "sid" => -1
    );
    $db->insert_query("templates", $templatearray);

    $templatearray = array(
        "title" => "index_overview_2_columns",
        "template" => "<td valign=\"top\" class=\"{\$trow}\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"{\$theme[\'tablespace\']}\">
        <tr class=\"tcat\">
        <td colspan=\"2\" valign=\"top\"><strong>{\$table_heading}</strong></td>
        </tr>
        <tr class=\"{\$trow}\">
        <td valign=\"top\"><strong>{\$column1_heading}</strong></td>
        <td align=\"right\" valign=\"top\"><strong>{\$column2_heading}</strong></td>
        </tr>
        {\$table_content}
        </table></td>",
        "sid" => -1
    );
    $db->insert_query("templates", $templatearray);

    $templatearray = array(
        "title" => "index_overview_2_columns_row",
        "template" => "<tr class=\"{\$trow}\">
        <td valign=\"top\"><div class=\"smalltext\">{\$val1}</div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\">{\$val2}</div></td>
        </tr>",
        "sid" => -1
    );
    $db->insert_query("templates", $templatearray);

    $templatearray = array(
        "title" => "index_overview_3_columns",
        "template" => "<td valign=\"top\" class=\"{\$trow}\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"{\$theme[\'tablespace\']}\">
        <tr class=\"tcat\">
        <td colspan=\"3\" valign=\"top\"><strong>{\$table_heading}</strong></td>
        </tr>
        <tr class=\"{\$trow}\">
        <td valign=\"top\"><strong>{\$column1_heading}</strong></td>
        <td valign=\"top\"><strong>{\$column2_heading}</strong></td>
        <td align=\"right\" valign=\"top\"><strong>{\$column3_heading}</strong></td>
        </tr>
        {\$table_content}
        </table></td>",
        "sid" => -1
    );
    $db->insert_query("templates", $templatearray);

    $templatearray = array(
        "title" => "index_overview_3_columns_row",
        "template" => "<tr class=\"{\$trow}\">
        <td valign=\"top\"><div class=\"smalltext\">{\$val1}</div></td>
        <td valign=\"top\"><div class=\"smalltext\">{\$val2}</div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\">{\$val3}</div></td>
        </tr>",
        "sid" => -1
    );
    $db->insert_query("templates", $templatearray);

    $templatearray = array(
        "title" => "index_overview_message",
        "template" => "<tr class=\"trow1\">
        <td colspan=\"{\$num_columns}\">
        <table  border=\"0\" cellspacing=\"0\" cellpadding=\"{\$theme[\'tablespace\']}\" width=\"100%\">
        <tr>
        <td class=\"smalltext\">
        {\$overview_message}
        </td>
        </tr>
        </table>
        </td>
        </tr>",
        "sid" => -1
    );
    $db->insert_query("templates", $templatearray);

    // Einstellungsgruppe hinzufügen
    $overview_group = array(
        "name" => "Overview",
        "title" => "Overview",
        "description" => "Settings for the \"Overview\"-Plugin.",
        "disporder" => 1,
        "isdefault" => 0
    );
    $db->insert_query("settinggroups", $overview_group);
    $gid = $db->insert_id();

    // Einstellungen hinzufügen
    $overview_1 = array(
        "name" => "overview_max",
        "title" => "Number of Items",
        "description" => "Enter the number of items (Users/Threads/Posts) to be shown.",
        "optionscode" => "text",
        "value" => 5,
        "disporder" => 1,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_1);

    $overview_2 = array(
        "name" => "overview_newest_members",
        "title" => "Show newest members?",
        "description" => "Choose if you want the newest members to be shown.",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 2,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_2);

    $overview_3 = array(
        "name" => "overview_do_newestusers",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => "1",
        "disporder" => "3",
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_3);

    $overview_4 = array(
        "name" => "overview_top_posters",
        "title" => "Show Top Posters?",
        "description" => "Choose if you want the top posters to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 4,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_4);

    $overview_5 = array(
        "name" => "overview_do_topposters",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 2,
        "disporder" => 5,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_5);

    $overview_6 = array(
        "name" => "overview_newest_threads",
        "title" => "Show newest threads?",
        "description" => "Choose if you want the newest threads to be shown.",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 6,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_6);

    $overview_7 = array(
        "name" => "overview_do_newestthreads",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 3,
        "disporder" => 7,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_7);

    $overview_8 = array(
        "name" => "overview_most_replies",
        "title" => "Show threads with most replies?",
        "description" => "Choose if you want the threads with the most replies to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 8,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_8);

    $overview_9 = array(
        "name" => "overview_do_mostreplies",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 4,
        "disporder" => 9,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_9);

    $overview_10 = array(
        "name" => "overview_favourite_threads",
        "title" => "Show favourite Threads?",
        "description" => "Choose if you want the favourite threads to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 10,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_10);

    $overview_11 = array(
        "name" => "overview_do_favouritethreads",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 5,
        "disporder" => 11,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_11);

    $overview_12 = array(
        "name" => "overview_newest_posts",
        "title" => "Show newest posts?",
        "description" => "Choose if you want the newest posts to be shown.",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 12,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_12);

    $overview_13 = array(
        "name" => "overview_do_newestposts",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 6,
        "disporder" => 13,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_13);

    $overview_14 = array(
        "name" => "overview_bestrep_members",
        "title" => "Show best reputated members?",
        "description" => "Choose if you want the best reputated members to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 14,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_14);

    $overview_15 = array(
        "name" => "overview_do_bestrepmembers",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 7,
        "disporder" => 15,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_15);

    $overview_16 = array(
        "name" => "overview_newest_polls",
        "title" => "Show best newest polls?",
        "description" => "Choose if you want the newest polls to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 16,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_16);

    $overview_17 = array(
        "name" => "overview_do_newestpolls",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 8,
        "disporder" => 17,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_17);

    $overview_18 = array(
        "name" => "overview_next_events",
        "title" => "Show best next events?",
        "description" => "Choose if you want the next events to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 18,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_18);

    $overview_19 = array(
        "name" => "overview_do_nextevents",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 9,
        "disporder" => 19,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_19);

    $overview_20 = array(
        "name" => "overview_show_re",
        "title" => "Do you want to show the \"RE:\" from the subjects of replies?",
        "description" => "Choose if you want the \"RE:\" to be shown in front of the subjects of replies.",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 20,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_20);

    $overview_21 = array(
        "name" => "overview_subjects_lenght",
        "title" => "Number of Characters",
        "description" => "How many characters of subjects should be shown (0 = show all)?",
        "optionscode" => "text",
        "value" => 0,
        "disporder" => 21,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_21);

    $overview_22 = array(
        "name" => "overview_usernamestyle",
        "title" => "Format usernames?",
        "description" => "Do you want to format the usernames in the style of their usergroups?",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 22,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_22);

    $overview_23 = array(
        "name" => "overview_showicon",
        "title" => "Show post icons?",
        "description" => "Do you want to display post icons in front of subjects?",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 23,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_23);

    $overview_24 = array(
        "name" => "overview_trow_message_onoff",
        "title" => "Show message?",
        "description" => "Choose if you want to show a message.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 24,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_24);

    $overview_25 = array(
        "name" => "overview_trow_message",
        "title" => "Message",
        "description" => "Enter the message. You can use MyCode.",
        "optionscode" => "textarea",
        "value" => "Enter your message here!",
        "disporder" => 25,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_25);

    $overview_26 = array(
        "name" => "overview_ajax_onoff",
        "title" => "Ajax",
        "description" => "Do you want to enable the Ajax functionality so that the overview box reloads itself?",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => 26,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_26);

    $overview_27 = array(
        "name" => "overview_ajax_time",
        "title" => "Period",
        "description" => "Enter the period of time after that the overview box should reload itself (Seconds)?",
        "optionscode" => "text",
        "value" => 60,
        "disporder" => 27,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_27);

    $overview_28 = array(
        "name" => "overview_ajax_loading",
        "title" => "Loading",
        "description" => "Do you want to show a \"Loading\"-Window?",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => 28,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_28);

    $overview_29 = array(
        "name" => "overview_usergroups",
        "title" => "Disable overview for usergroups",
        "description" => "Enter the IDs of the usergroups that should not see the overview table (0 = none). Seperate several IDs with commas.",
        "optionscode" => "text",
        "value" => 0,
        "disporder" => 29,
        "gid" => intval($gid)
    );
    $db->insert_query("settings", $overview_29);

    // settings.php erneuern
    rebuild_settings();
}

### Deinstallation ###
function overview_uninstall()
{
    global $db;

    // Templates von dieses Plugin entfernen
    $templatearray = array(
        "index_overview",
        "index_overview_2_columns",
        "index_overview_2_columns_row",
        "index_overview_3_columns",
        "index_overview_3_columns_row",
        "index_overview_message",
    );
    $deltemplates = implode("','", $templatearray);

    $db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title in ('{$deltemplates}');");

    // Einstellungsgruppe löschen
    $query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='Overview'");
    $g = $db->fetch_array($query);
    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE gid='{$g['gid']}'");

    // Einstellungen löschen
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE gid='{$g['gid']}'");

    // settings.php erneuern
    rebuild_settings();
}

### Installationsstatus ###
function overview_is_installed()
{
    global $db;

    $query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='Overview'");

    if($db->num_rows($query) != 0)
    {
        return true;
    }

    return false;
}

### Aktivierung ###
function overview_activate()
{
    // Variablen für dieses Plugin einfügen
    require MYBB_ROOT."inc/adminfunctions_templates.php";
    find_replace_templatesets("index", '#{\$header}(\r?)\n#', "{\$header}\n{\$overview}\n");
    find_replace_templatesets("index", '#{\$footer}(\r?)\n#', "{\$footer}\n{\$overview_body}\n");
    find_replace_templatesets("index", '#<body>(\r?)\n#', "<body{\$overview_body_onload}>\n");
    find_replace_templatesets("index", '#{\$headerinclude}(\r?)\n#', "{\$headerinclude}\n{\$overview_headerinclude}\n");
}

### Deaktivierung ###
function overview_deactivate()
{
    // Variablen von dieses Plugin entfernen
    require MYBB_ROOT."/inc/adminfunctions_templates.php";
    find_replace_templatesets("index", '#{\$overview}(\r?)\n#', "", 0);
    find_replace_templatesets("index", '#{\$overview_body}(\r?)\n#', "", 0);
    find_replace_templatesets("index", '#<body{\$overview_body_onload}>(\r?)\n#', "<body>\n", 0);
    find_replace_templatesets("index", '#{\$overview_headerinclude}(\r?)\n#', "", 0);
}

### Funktionen ###

// Hauptfunktionen
function overview()
{
    global $db, $mybb, $cache, $templates, $theme, $lang, $overview, $collapsed;

    if($mybb->settings['overview_usergroups'] != 0)
    {
        $overviewgroups = explode(",", $mybb->settings['overview_usergroups']);
    }

    if($mybb->settings['overview_usergroups'] == 0 || !in_array($mybb->user['usergroup'], $overviewgroups))
    {
        $language = $mybb->settings['bblanguage'];

        // Sprachdatei laden
        $lang->load("overview");

        // Anzahl der Spalten ermitteln
        $num_columns = overview_num_columns();

        //Nicht sichtbare Foren ausschließen
        $overview_unviewwhere = "";
        $overview_unviewable = get_unviewable_forums();
        if($overview_unviewable) {
            $overview_unviewwhere = "AND fid NOT IN ({$overview_unviewable})";
        }

        // Variablen definieren
        $overview_content = "";
        $trow_message = "";
        $overview = "";

        // Sortierung auslesen und Daten ausgeben
        $orderquery = $db->query("
            SELECT name from ".TABLE_PREFIX."settings
            WHERE name IN ('overview_do_newestusers','overview_do_topposters','overview_do_newestthreads','overview_do_mostreplies','overview_do_favouritethreads','overview_do_newestposts','overview_do_bestrepmembers','overview_do_newestpolls','overview_do_nextevents')
            ORDER BY value ASC
        ;");

        $collapseinsert1 = $collapseinsert2 = "";
        if($mybb->settings['overview_ajax_onoff'] != 1)
        {
            $expdisplay = "";
            if(isset($collapsed['overview_c']) && $collapsed['overview_c'] == "display: show;")
            {
                    $expcolimage = "collapse_collapsed.gif";
                    $expdisplay = "display: none;";
                    $expaltext = "[+]";
            }
            else
            {
                    $expcolimage = "collapse.gif";
                    $expaltext = "[-]";
            }

            $collapseinsert1 = "<div class=\"expcolimage\"><img src=\"{$theme['imgdir']}/{$expcolimage}\" id=\"overview_img\" class=\"expander\" alt=\"{$expaltext}\" title=\"{$expaltext}\" /></div>";
            $collapseinsert2 = " style=\"{$expdisplay}\" id=\"overview_e\"";
        }

        while ($order = $db->fetch_array($orderquery))
        {
            $overview_content .= call_user_func($order['name'], $overview_unviewwhere);
        }

        // Nachricht zeigen?
        if($mybb->settings['overview_trow_message_onoff'] == "1")
        {
            require_once  MYBB_ROOT."inc/class_parser.php";
            $messageparser = new postParser;
            $parseoptions = array(
                "allow_html" => 0,
                "allow_mycode" => 1,
                "allow_smilies" => 1,
                "allow_imgcode" => 1
            );
            $overview_message = $messageparser->parse_message(htmlspecialchars_uni($mybb->settings['overview_trow_message']), $parseoptions);
            eval("\$trow_message = \"".$templates->get("index_overview_message")."\";");
        }

        // Template laden
        eval("\$overview = \"".$templates->get("index_overview")."\";");

        if($mybb->settings['overview_ajax_onoff'] == 1)
        {
            return $overview;
        }
    }
}

function overview_ajax()
{
    global $mybb;

    if($mybb->input['action'] == "overview" && $mybb->settings['overview_ajax_onoff'] == 1)
    {
        echo overview();
    }
}

function overview_end()
{
    global $mybb, $intervall, $overview_headerinclude, $overview_body_onload, $overview_body_onload2, $overview, $overview_body;

    $overview_headerinclude = $overview_body_onload = $overview_body_onload2 = $overview_body = "";

    if($mybb->settings['overview_usergroups'] != 0)
    {
        $overviewgroups = explode(",", $mybb->settings['overview_usergroups']);
    }

    if($mybb->settings['overview_ajax_onoff'] == 1 && ($mybb->settings['overview_usergroups'] == 0 || !in_array($mybb->user['usergroup'], $overviewgroups)))
    {
        if($mybb->settings['overview_ajax_loading'] == 1)
        {
            $loaddisplay = 1;
        }
        else
        {
            $loaddisplay = 0;
        }
        $intervall = $mybb->settings['overview_ajax_time'] * 1000;
        $overview_headerinclude = "<script type=\"text/javascript\" src=\"jscripts/overview.js\"></script>\n<script language=\"JavaScript\" type=\"text/javascript\">\nvar req = createXMLHttpRequest();\n</script>";
        $overview_body_onload = " onload=\"dooverview(".$loaddisplay.");\"";
        $overview_body_onload2 = "; dooverview(".$loaddisplay.")";
        $overview = "<span id=\"overview_load\"></span>\n<div id=\"overview\"></div>";
        $overview_body = "<script type=\"text/javascript\">\nsetInterval('dooverview(".$loaddisplay.")', ".$intervall.");\n</script>";
    }
}

// Neueste Mitglieder
function overview_do_newestusers() {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_newest_members'] == 1)
    {

        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        $table_heading = $lang->overview_newest_members;
        $column1_heading = $lang->overview_username;
        $column2_heading = $lang->overview_posts;

        // Daten für neueste Benutzer aus Datenbank auslesen
        $query = $db->query("
            SELECT username, postnum, uid, usergroup, displaygroup
            FROM ".TABLE_PREFIX."users
            ORDER BY uid DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Daten ausgeben
        while ($users = $db->fetch_array($query))
        {
            $val1 = overview_parseuser($users['uid'], $users['username'], $users['usergroup'], $users['displaygroup']);
            $val2 = "<a href=\"search.php?action=finduser&amp;uid={$users['uid']}\">{$users['postnum']}</a>";
            eval("\$table_content .= \"".$templates->get("index_overview_2_columns_row")."\";");
        }
        eval("\$output = \"".$templates->get("index_overview_2_columns")."\";");
    }

    return $output;
}

// Top Poster
function overview_do_topposters() {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_top_posters'] == 1)
    {

        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        $table_heading = $lang->overview_top_posters;
        $column1_heading = $lang->overview_username;
        $column2_heading = $lang->overview_posts;

        // Daten für Top Poster aus Datenbank auslesen
        $query = $db->query("
            SELECT username, postnum, uid, usergroup, displaygroup
            FROM ".TABLE_PREFIX."users
            ORDER BY postnum DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Daten ausgeben
        while ($users = $db->fetch_array($query))
        {
            $val1 = overview_parseuser($users['uid'], $users['username'], $users['usergroup'], $users['displaygroup']);
            $val2 = "<a href=\"search.php?action=finduser&amp;uid={$users['uid']}\">{$users['postnum']}</a>";
            eval("\$table_content .= \"".$templates->get("index_overview_2_columns_row")."\";");
        }
        eval("\$output = \"".$templates->get("index_overview_2_columns")."\";");
    }

    return $output;
}

// Neueste Themen
function overview_do_newestthreads($overview_unviewwhere) {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_newest_threads'] == 1)
    {

        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        $table_heading = $lang->overview_newest_threads;
        $column1_heading = $lang->overview_topic;
        $column2_heading = $lang->overview_author;
        $column3_heading = $lang->overview_replies;

        // Daten für neueste Themen aus Datenbank auslesen
        $query = $db->query("
            SELECT subject, username, uid, tid, replies, icon
            FROM ".TABLE_PREFIX."threads
            WHERE visible = '1' {$overview_unviewwhere} AND closed NOT LIKE 'moved|%'
            ORDER BY dateline DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Daten ausgeben
        while ($threads = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($threads['subject'], $threads['icon'], $threads['tid']);
            $val2 = overview_parseuser($threads['uid'], $threads['username']);
            $val3 = "<a href=\"javascript:MyBB.whoPosted({$threads['tid']});\">{$threads['replies']}</a>";
            eval("\$table_content .= \"".$templates->get("index_overview_3_columns_row")."\";");
        }
        eval("\$output = \"".$templates->get("index_overview_3_columns")."\";");
    }

    return $output;
}

// Themen mit meisten Antworten
function overview_do_mostreplies($overview_unviewwhere) {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_most_replies'] == 1)
    {

        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        $table_heading = $lang->overview_most_replies;
        $column1_heading = $lang->overview_topic;
        $column2_heading = $lang->overview_replies;

        // Daten für Themen mit meisten Antworten aus Datenbank auslesen
        $query = $db->query("
            SELECT subject, tid, replies, icon
            FROM ".TABLE_PREFIX."threads
            WHERE visible = '1' {$overview_unviewwhere} AND closed NOT LIKE 'moved|%'
            ORDER BY replies DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Daten ausgeben
        while($threads = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($threads['subject'], $threads['icon'], $threads['tid']);
            $val2 = "<a href=\"javascript:MyBB.whoPosted({$threads['tid']});\">{$threads['replies']}</a>";
            eval("\$table_content .= \"".$templates->get("index_overview_2_columns_row")."\";");
        }
        eval("\$output = \"".$templates->get("index_overview_2_columns")."\";");
    }

    return $output;
}

// Beliebteste Themen
function overview_do_favouritethreads($overview_unviewwhere) {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_favourite_threads'] == 1)
    {

        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        $table_heading = $lang->overview_favourite_threads;
        $column1_heading = $lang->overview_topic;
        $column2_heading = $lang->overview_views;

        // Daten für beliebteste Themen aus Datenbank auslesen
        $query = $db->query("
            SELECT subject, tid, views, icon
            FROM ".TABLE_PREFIX."threads
            WHERE visible = '1' {$overview_unviewwhere} AND closed NOT LIKE 'moved|%'
            ORDER BY views DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Daten ausgeben
        while ($threads = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($threads['subject'], $threads['icon'], $threads['tid']);
            $val2 = $threads['views'];
            eval("\$table_content .= \"".$templates->get("index_overview_2_columns_row")."\";");
        }
        eval("\$output = \"".$templates->get("index_overview_2_columns")."\";");
    }

    return $output;
}

// Neueste Beiträge
function overview_do_newestposts($overview_unviewwhere) {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_newest_posts'] == 1)
    {

        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        $table_heading = $lang->overview_newest_posts;
        $column1_heading = $lang->overview_subject;
        $column2_heading = $lang->overview_author;

        // Daten für neueste Beiträge aus Datenbank auslesen
        $query = $db->query("
            SELECT subject, username, uid, pid, tid, icon
            FROM ".TABLE_PREFIX."posts
            WHERE visible='1' {$overview_unviewwhere}
            ORDER BY dateline DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Daten ausgeben
        while($posts = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($posts['subject'], $posts['icon'], $posts['tid'], $posts['pid'], 0, 1);
            $val2 = overview_parseuser($posts['uid'], $posts['username']);
            eval("\$table_content .= \"".$templates->get("index_overview_2_columns_row")."\";");
        }
        eval("\$output = \"".$templates->get("index_overview_2_columns")."\";");
    }

    return $output;
}

// Nächste Termine
function overview_do_nextevents() {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_next_events'] == 1)
    {

        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        $table_heading = $lang->overview_next_events;
        $column1_heading = $lang->overview_event;
        $column2_heading = $lang->overview_author;

        if($mybb->usergroup['canviewcalendar'] == 1)
        {
            // Berechtigungen zusammensetzen
            $query = $db->query("
                SELECT cid
                FROM ".TABLE_PREFIX."calendarpermissions
                WHERE gid = '".intval($mybb->user['usergroup'])."' AND canviewcalendar = '0'
            ;");

            $cids = $sep = "";
            if($db->num_rows($query) != 0)
            {
                while($groups = $db->fetch_array($query))
                {
                    $cids .= $sep.$groups['cid'];
                    $sep = ",";
                }
                $cids = "AND e.cid NOT IN ({$cids})";
            }

            // Daten für nächste Events aus Datenbank auslesen
            $query = $db->query("
                SELECT e.eid, e.name, e.starttime, e.uid, u.username, u.usergroup, u.displaygroup
                FROM ".TABLE_PREFIX."events e
                LEFT JOIN ".TABLE_PREFIX."users u ON (e.uid=u.uid)
                WHERE e.visible = '1' AND (e.private = '0' OR e.uid = '".intval($mybb->user['uid'])."') AND e.starttime > '".TIME_NOW."' {$cids}
                ORDER BY starttime ASC
                LIMIT 0,{$mybb->settings['overview_max']}
            ;");

            // Daten ausgeben
            while($events = $db->fetch_array($query))
            {
                $events['name'] = my_date($mybb->settings['dateformat'], $events['starttime']).": ".$events['name'];
                $val1 = overview_parsesubject($events['name'], 0, 0, $events['eid'], 0);
                $val2 = overview_parseuser($events['uid'], $events['username'], $events['usergroup'], $events['displaygroup']);
                eval("\$table_content .= \"".$templates->get("index_overview_2_columns_row")."\";");
            }
        }
        eval("\$output = \"".$templates->get("index_overview_2_columns")."\";");
    }

    return $output;
}

// Neueste Umfragen
function overview_do_newestpolls($overview_unviewwhere) {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_newest_polls'] == 1)
    {

        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        $table_heading = $lang->overview_newest_polls;
        $column1_heading = $lang->overview_question;
        $column2_heading = $lang->overview_author;

        // Daten für neueste Umfragen aus Datenbank auslesen
        $query = $db->query("
            SELECT p.question, p.tid, t.uid, t.username, t.icon
            FROM ".TABLE_PREFIX."polls p
            LEFT JOIN ".TABLE_PREFIX."threads t ON (p.tid=t.tid)
            WHERE t.visible='1' {$overview_unviewwhere} AND t.closed NOT LIKE 'moved|%'
            ORDER BY p.pid DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Daten ausgeben
        while($polls = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($polls['question'], $polls['icon'], $polls['tid']);
            $val2 = overview_parseuser($polls['uid'], $polls['username']);
            eval("\$table_content .= \"".$templates->get("index_overview_2_columns_row")."\";");
        }
        eval("\$output = \"".$templates->get("index_overview_2_columns")."\";");
    }

    return $output;
}

// Bestbewertete user
function overview_do_bestrepmembers() {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_bestrep_members'] == 1)
    {

        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        $table_heading = $lang->overview_bestrep_members;
        $column1_heading = $lang->overview_username;
        $column2_heading = $lang->overview_reputation;

        // Daten für neueste Benutzer aus Datenbank auslesen
        $query = $db->query("
            SELECT username, reputation, uid, usergroup, displaygroup
            FROM ".TABLE_PREFIX."users
            ORDER BY reputation DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Daten ausgeben
        while ($users = $db->fetch_array($query))
        {
            $val1 = overview_parseuser($users['uid'], $users['username'], $users['usergroup'], $users['displaygroup']);
            $val2 = get_reputation($users['reputation'], $users['uid']);
            eval("\$table_content .= \"".$templates->get("index_overview_2_columns_row")."\";");
        }
        eval("\$output = \"".$templates->get("index_overview_2_columns")."\";");
    }

    return $output;
}

// Betreff verarbeiten
function overview_parsesubject($subject, $icon=0, $tid=0, $pid=0, $eid=0, $removere=0)
{
    global $mybb, $parser, $cache;

    if($mybb->settings['overview_show_re'] == 0 && $removere == 1)
    {
        $subject = str_replace("RE: ", "", $subject);
    }

    if(!$parser)
    {
        require_once  MYBB_ROOT."inc/class_parser.php";
        $parser = new postParser;
    }

    $subjectfull = $subject = $parser->parse_badwords($subject);

    if($mybb->settings['overview_subjects_lenght'] != 0)
    {
        if(my_strlen($subject) > $mybb->settings['overview_subjects_lenght'])
        {
            $subject = my_substr($subject, 0, $mybb->settings['overview_subjects_lenght'])."...";
        }
    }

    $subjectfull = htmlspecialchars_uni($subjectfull);
    $subject = htmlspecialchars_uni($subject);

    if($pid)
    {
        $link = get_post_link($pid, $tid)."#pid".$pid;
    }
    else if($eid)
    {
        $link = get_event_link($eid);
    }
    else
    {
        $link = get_thread_link($tid);
    }

    $icon_cache = $cache->read("posticons");

    if($mybb->settings['overview_showicon'] != 0 && $icon > 0 && $icon_cache[$icon])
    {
        $icon = $icon_cache[$icon];
        $icon = "<img src=\"{$icon['path']}\" alt=\"{$icon['name']}\" style=\"vertical-align: middle;\" />&nbsp;";
    }
    else
    {
        $icon = "";
    }

    return "<a href=\"{$link}\" title=\"{$subjectfull}\">{$subject}</a>";
}

// User verarbeiten
function overview_parseuser($uid, $username, $usergroup=0, $displaygroup=0)
{
    global $mybb, $db, $lang;

    $username = htmlspecialchars_uni($username);

    if(!$uid)
    {
        $usergroup = 1;
        if($username == "Guest")
        {
            $username = $lang->guest;
        }
    }

    if($mybb->settings['overview_usernamestyle'] == 1)
    {
        if(!$usergroup)
        {
            $query = $db->simple_select("users", "username, usergroup, displaygroup", "uid = '{$uid}'");
            $user = $db->fetch_array($query);
            $username = htmlspecialchars_uni($user['username']);
            $usergroup = $user['usergroup'];
            $displaygroup = $user['displaygroup'];
        }
        $username = format_name($username, $usergroup, $displaygroup);
    }

    if($uid)
    {
        $link = get_profile_link($uid);
        return "<a href=\"{$link}\">{$username}</a>";
    }
    else
    {
        return $username;
    }
}

// Tablerow Farbe ändern
function overview_trowcolor($trow)
{
    if(!isset($trow))
    {
        $trow = "trow1";
    }
    else
    {
        if ($trow == "trow1")
        {
            $trow = "trow2";
        }
        else if($trow == "trow2")
        {
            $trow = "trow1";
        }
    }

    return $trow;
}

// Spaltenanzahl ermitteln
function overview_num_columns()
{
    global $mybb;

    $i = 0;
    $settings = array(
        'overview_newest_members',
        'overview_top_posters',
        'overview_newest_threads',
        'overview_most_replies',
        'overview_favourite_threads',
        'overview_newest_posts',
        'overview_bestrep_members',
        'overview_newest_polls',
        'overview_next_events'
    );

    foreach($settings as $setting)
    {
        if($mybb->settings[$setting] == 1)
        {
            $i++;
        }
    }

    return $i;
}

// Einstellungen erneuern
if(!function_exists("rebuild_settings"))
{

    function rebuild_settings()
    {
        global $db;

        $query = $db->query("SELECT * FROM ".TABLE_PREFIX."settings ORDER BY title ASC");
        while($setting = $db->fetch_array($query))
        {
            $setting['value'] = addslashes($setting['value']);
            $settings .= "\$settings['".$setting['name']."'] = \"".$setting['value']."\";\n";
        }
        $settings = "<?php\n/*********************************\ \n  DO NOT EDIT THIS FILE, PLEASE USE\n  THE SETTINGS EDITOR\n\*********************************/\n\n$settings\n?>";
        $file = fopen(MYBB_ROOT."inc/settings.php", "w");
        fwrite($file, $settings);
        fclose($file);
    }
}
?>
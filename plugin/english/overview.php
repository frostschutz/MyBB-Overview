<?php
###################################
# Plugin Overview 3.0.4           #
# (c) 2005-2006 by MyBBoard.de    #
# Website: http://www.mybboard.de #
# All rights reserved             #
###################################
if(!defined("IN_MYBB")) {
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

### Benötigte Daten für das Plugin-System ###
$plugins->add_hook("index_start", "overview");
$plugins->add_hook("index_end", "overview_end");

### Informationen zum Plugin ###
function overview_info()
{
	return array(
		"name"        => "Overview",
		"title"        => "Overview",
		"description" => "Displays a box on the index page that shows different infomations about your board.",
		"website"     => "http://www.mybboard.de",
		"author"      => "MyBBoard.de",
		"authorsite"  => "http://www.mybboard.de",
		"version"     => "3.0.4",
		);
}

### Aktivierung ###
function overview_activate()
{
    global $db;

	// Variablen für dieses Plugin einfügen
	require MYBB_ROOT."inc/adminfunctions_templates.php";
	find_replace_templatesets("index", '#{\$header}(\r?)\n#', "{\$header}\n{\$overview}\n");
	find_replace_templatesets("index", '#{\$footer}(\r?)\n#', "{\$footer}\n{\$overview_body}\n");
	find_replace_templatesets("index", '#<body>(\r?)\n#', "<body{\$overview_body_onload}>\n");
	find_replace_templatesets("index", '#{\$headerinclude}(\r?)\n#', "{\$headerinclude}\n{\$overview_headerinclude}\n");
	
	// Templates für dieses Plugin einfügen
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview",
		"template" => "<table width=\"100%\" border=\"0\" cellspacing=\"\$theme[borderwidth]\" cellpadding=\"0\" class=\"tborder\">
		<thead>
		<tr><td colspan=\"\$num_columns\"><table  border=\"0\" cellspacing=\"0\" cellpadding=\"\$theme[tablespace]\" width=\"100%\"><tr class=\"thead\"><td><strong>\$lang->overview_overview</strong></td></tr></table></td>
        </tr>
        </thead>
        <tbody>
        \$trow_message
        <tr>
	    \$overview_content
	    </tr>
        </tbody>
        </table>
        <!-- You\'re not allowed to remove this link! Take a look at the readme for further information. -->
        <div style=\"text-align: right; font-size: 10px;\">&Uuml;bersicht by <a href=\"http://www.mybboard.de\" target=\"_blank\">MyBBoard.de</a></div>
        <br />",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
				
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_newmembers",
		"template" => "<td valign=\"top\" class=\"\$trow\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"\$theme[tablespace]\">
        <tr class=\"tcat\">
        <td colspan=\"2\" valign=\"top\"><strong>\$lang->overview_newest_members</strong></td>
        </tr>
        <tr class=\"\$trow\">
        <td valign=\"top\"><strong>\$lang->overview_username</strong></td>
        <td align=\"right\" valign=\"top\"><strong>\$lang->overview_posts</strong></td>
        </tr>
        \$newmembers_row
        </table></td>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_newmembers_row",
		"template" => "<tr class=\"\$trow\">
        <td valign=\"top\"><div class=\"smalltext\"><a href=\"member.php?action=profile&amp;uid=\$uid\">\$username</a></div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\"><a href=\"search.php?action=finduser&amp;uid=\$uid\">\$postnum</a></div></td>
        </tr>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_topposters",
		"template" => "<td valign=\"top\" class=\"\$trow\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"\$theme[tablespace]\">
        <tr class=\"tcat\">
        <td colspan=\"2\" valign=\"top\"><strong>\$lang->overview_top_posters</strong></td>
        </tr>
        <tr class=\"\$trow\">
        <td valign=\"top\"><strong>\$lang->overview_username</strong></td>
        <td align=\"right\" valign=\"top\"><strong>\$lang->overview_posts</strong></td>
        </tr>
        \$topposters_row
        </table></td>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_topposters_row",
		"template" => "<tr class=\"\$trow\">
        <td valign=\"top\"><div class=\"smalltext\"><a href=\"member.php?action=profile&amp;uid=\$uid\">\$username</a></div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\"><a href=\"search.php?action=finduser&amp;uid=\$uid\">\$postnum</a></div></td>
        </tr>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_newthreads",
		"template" => "<td valign=\"top\" class=\"\$trow\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"\$theme[tablespace]\">
        <tr class=\"tcat\">
        <td colspan=\"3\" valign=\"top\"><strong>\$lang->overview_newest_threads</strong></td>
        </tr>
        <tr class=\"\$trow\">
        <td valign=\"top\"><strong>\$lang->overview_topic</strong></td>
        <td valign=\"top\"><strong>\$lang->overview_author</strong></td>
        <td align=\"right\" valign=\"top\"><strong>\$lang->overview_replies</strong></td>
        </tr>
        \$newthreads_row
        </table></td>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_newthreads_row",
		"template" => "<tr class=\"\$trow\">
        <td valign=\"top\"><div class=\"smalltext\"><a href=\"showthread.php?tid=\$tid\" title=\"\$subject_long\">\$subject</a></div></td>
        <td valign=\"top\"><div class=\"smalltext\"><a href=\"member.php?action=profile&amp;uid=\$uid\">\$username</a></div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\"><a href=\"javascript:MyBB.whoPosted(\$tid);\">\$replies</a></div></td>
        </tr>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
	
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_mostreplies",
		"template" => "<td valign=\"top\" class=\"\$trow\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"\$theme[tablespace]\">
        <tr class=\"tcat\">
        <td colspan=\"2\" valign=\"top\"><strong>\$lang->overview_most_replies</strong></td>
        </tr>
        <tr class=\"\$trow\">
        <td valign=\"top\"><strong>\$lang->overview_topic</strong></td>
        <td align=\"right\" valign=\"top\"><strong>\$lang->overview_replies</strong></td>
        </tr>
        \$mostreplies_row
        </table></td>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_mostreplies_row",
		"template" => "<tr class=\"\$trow\">
        <td valign=\"top\"><div class=\"smalltext\"><a href=\"showthread.php?tid=\$tid\" title=\"\$subject_long\">\$subject</a></div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\"><a href=\"javascript:MyBB.whoPosted(\$tid);\">\$replies</a></div></td>
        </tr>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_favouritethreads",
		"template" => "<td valign=\"top\" class=\"\$trow\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"\$theme[tablespace]\">
        <tr class=\"tcat\">
        <td colspan=\"2\" valign=\"top\"><strong>\$lang->overview_favourite_threads</strong></td>
        </tr>
        <tr class=\"\$trow\">
        <td valign=\"top\"><strong>\$lang->overview_topic</strong></td>
        <td align=\"right\" valign=\"top\"><strong>\$lang->overview_views</strong></td>
        </tr>
        \$favouritethreads_row
        </table></td>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_favouritethreads_row",
		"template" => "<tr class=\"\$trow\">
        <td valign=\"top\"><div class=\"smalltext\"><a href=\"showthread.php?tid=\$tid\" title=\"\$subject_long\">\$subject</a></div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\">\$views</div></td>
        </tr>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_newposts",
		"template" => "<td valign=\"top\" class=\"\$trow\"><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"\$theme[tablespace]\">
        <tr class=\"tcat\">
        <td colspan=\"2\" valign=\"top\"><strong>\$lang->overview_newest_posts</strong></td>
        </tr>
        <tr class=\"\$trow\">
        <td valign=\"top\"><strong>\$lang->overview_subject</strong></td>
        <td align=\"right\" valign=\"top\"><strong>\$lang->overview_author</strong></td>
        </tr>
        \$newposts_row
        </table></td>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_column_newposts_row",
		"template" => "<tr class=\"\$trow\">
        <td valign=\"top\"><div class=\"smalltext\"><a href=\"showthread.php?tid=\$tid\&amp;pid=\$pid#pid\$pid\" title=\"\$subject_long\">\$subject</a></div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\"><a href=\"member.php?action=profile&amp;uid=\$uid\">\$username</a></div></td>
        </tr>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	$templatearray = array(
		"tid" => "NULL",
		"title" => "index_overview_message",
		"template" => "<tr class=\"trow1\">
		<td colspan=\"\$num_columns\">
		<table  border=\"0\" cellspacing=\"0\" cellpadding=\"\$theme[tablespace]\" width=\"100%\">
		<tr>
        <td class=\"smalltext\">
        \$overview_message
        </td>
        </tr>
        </table>
        </td>
        </tr>",
		"sid" => "-1",
		);
	$db->insert_query(TABLE_PREFIX."templates", $templatearray);
		
	// Einstellungsgruppe hinzufügen
	$overview_group = array(
		"gid" => "NULL",
		"name" => "Overview",
		"title" => "Overview",
		"description" => "Settings for the \"Overview\"-Plugin.",
		"disporder" => "1",
		"isdefault" => "no",
		);
	$db->insert_query(TABLE_PREFIX."settinggroups", $overview_group);
	$gid = $db->insert_id();
	
	// Einstellungen hinzufügen
	$overview_1 = array(
		"sid" => "NULL",
		"name" => "overview_max",
		"title" => "Number of Items",
		"description" => "Enter the number of items (Users/Threads/Posts) to be shown.",
		"optionscode" => "text",
		"value" => "5",
		"disporder" => "1",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_1);
	
	$overview_2 = array(
		"sid" => "NULL",
		"name" => "overview_newest_members",
		"title" => "Show newest members?",
		"description" => "Choose if you want the newest members to be shown.",
		"optionscode" => "yesno",
		"value" => "yes",
		"disporder" => "2",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_2);
	
	$overview_3 = array(
		"sid" => "NULL",
		"name" => "overview_do_newestusers",
		"title" => "Sorting",
		"description" => "Here you can change the order.",
		"optionscode" => "text",
		"value" => "1",
		"disporder" => "3",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_3);
	
	$overview_4 = array(
		"sid" => "NULL",
		"name" => "overview_top_posters",
		"title" => "Show Top Posters?",
		"description" => "Choose if you want the top posters to be shown.",
		"optionscode" => "yesno",
		"value" => "no",
		"disporder" => "4",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_4);
	
	$overview_5 = array(
		"sid" => "NULL",
		"name" => "overview_do_topposters",
		"title" => "Sorting",
		"description" => "Here you can change the order.",
		"optionscode" => "text",
		"value" => "2",
		"disporder" => "5",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_5);
	
	$overview_6 = array(
		"sid" => "NULL",
		"name" => "overview_newest_threads",
		"title" => "Show newest threads?",
		"description" => "Choose if you want the newest threads to be shown.",
		"optionscode" => "yesno",
		"value" => "yes",
		"disporder" => "6",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_6);
	
	$overview_7 = array(
		"sid" => "NULL",
		"name" => "overview_do_newestthreads",
		"title" => "Sorting",
		"description" => "Here you can change the order.",
		"optionscode" => "text",
		"value" => "3",
		"disporder" => "7",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_7);
	
	$overview_8 = array(
		"sid" => "NULL",
		"name" => "overview_most_replies",
		"title" => "Show threads with most replies?",
		"description" => "Choose if you want the threads with the most replies to be shown.",
		"optionscode" => "yesno",
		"value" => "no",
		"disporder" => "8",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_8);
	
	$overview_9 = array(
		"sid" => "NULL",
		"name" => "overview_do_mostreplies",
		"title" => "Sorting",
		"description" => "Here you can change the order.",
		"optionscode" => "text",
		"value" => "4",
		"disporder" => "9",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_9);
	
	$overview_10 = array(
		"sid" => "NULL",
		"name" => "overview_favourite_threads",
		"title" => "Show favourite Threads?",
		"description" => "Choose if you want the favourite threads to be shown.",
		"optionscode" => "yesno",
		"value" => "no",
		"disporder" => "10",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_10);
	
	$overview_11 = array(
		"sid" => "NULL",
		"name" => "overview_do_favouritethreads",
		"title" => "Sorting",
		"description" => "Here you can change the order.",
		"optionscode" => "text",
		"value" => "5",
		"disporder" => "11",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_11);
	
	$overview_12 = array(
		"sid" => "NULL",
		"name" => "overview_newest_posts",
		"title" => "Show newest posts?",
		"description" => "Choose if you want the newest posts to be shown.",
		"optionscode" => "yesno",
		"value" => "yes",
		"disporder" => "12",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_12);
	
	$overview_13 = array(
		"sid" => "NULL",
		"name" => "overview_do_newestposts",
		"title" => "Sorting",
		"description" => "Here you can change the order.",
		"optionscode" => "text",
		"value" => "6",
		"disporder" => "13",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_13);
	
	$overview_14 = array(
		"sid" => "NULL",
		"name" => "overview_show_re",
		"title" => "Do you want to remove the \"RE:\" from the subjects of replies?",
		"description" => "Choose if you want the \"RE:\" to be shown in front of the subjects of replies.",
		"optionscode" => "yesno",
		"value" => "yes",
		"disporder" => "14",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_14);
	
	$overview_15 = array(
		"sid" => "NULL",
		"name" => "overview_subjects_lenght",
		"title" => "Number of Characters",
		"description" => "How many characters of subjects should be shown (0 = show all)?",
		"optionscode" => "text",
		"value" => "0",
		"disporder" => "15",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_15);
	
	$overview_16 = array(
		"sid" => "NULL",
		"name" => "overview_usernamestyle",
		"title" => "Format usernames?",
		"description" => "Do you want to format the usernames in the style of their usergroups?<br /><small>Note: Can make use of many queries.</small>",
		"optionscode" => "yesno",
		"value" => "no",
		"disporder" => "16",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_16);
	
	$overview_17 = array(
		"sid" => "NULL",
		"name" => "overview_trow_message_onoff",
		"title" => "Show message?",
		"description" => "Choose if you want to show a message.",
		"optionscode" => "yesno",
		"value" => "no",
		"disporder" => "17",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_17);
	
	$overview_18 = array(
		"sid" => "NULL",
		"name" => "overview_trow_message",
		"title" => "Message",
		"description" => "Enter the message.",
		"optionscode" => "textarea",
		"value" => "Enter your message here!",
		"disporder" => "18",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_18);
	
	$overview_19 = array(
		"sid" => "NULL",
		"name" => "overview_ajax_onoff",
		"title" => "Ajax",
		"description" => "Do you want to enable the Ajax functionality so that the overview box reloads itself?",
		"optionscode" => "yesno",
		"value" => "no",
		"disporder" => "19",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_19);
	
	$overview_20 = array(
		"sid" => "NULL",
		"name" => "overview_ajax_time",
		"title" => "Period",
		"description" => "Enter the period of time after that the overview box should reload itself (Seconds)?",
		"optionscode" => "text",
		"value" => "60",
		"disporder" => "20",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_20);
	
	$overview_21 = array(
		"sid" => "NULL",
		"name" => "overview_ajax_loading",
		"title" => "Loading",
		"description" => "Do you want to show a \"Loading\"-Window?",
		"optionscode" => "yesno",
		"value" => "yes",
		"disporder" => "21",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_21);
	
	$overview_22 = array(
		"sid" => "NULL",
		"name" => "overview_no_guests",
		"title" => "For members only?",
		"description" => "Do you want to deactivate the overview for guests?",
		"optionscode" => "yesno",
		"value" => "no",
		"disporder" => "22",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_22);
	
	$overview_23 = array(
		"sid" => "NULL",
		"name" => "overview_do_htmlentities",
		"title" => "htmlentities() or htmlspecialchars()?",
		"description" => "Do you want to use htmlentities() or htmlspecialchars()?<br /><small>If you have problems with the display of special characters use the other method</small>",
		"optionscode" => "radio
        1=htmlentites
        2=htmlspecialchars",
		"value" => "1",
		"disporder" => "23",
		"gid" => intval($gid),
		);
	$db->insert_query(TABLE_PREFIX."settings", $overview_23);
	
	// settings.php erneuern
	rebuild_settings();
}

### Deaktivierung ###
function overview_deactivate()
{
    global $db;

	// Variablen von dieses Plugin entfernen
	require MYBB_ROOT."/inc/adminfunctions_templates.php";
	find_replace_templatesets("index", '#{\$overview}(\r?)\n#', "", 0);
	find_replace_templatesets("index", '#{\$overview_body}(\r?)\n#', "", 0);
	find_replace_templatesets("index", '#<body{\$overview_body_onload}>(\r?)\n#', "<body>\n", 0);
	find_replace_templatesets("index", '#{\$overview_headerinclude}(\r?)\n#', "", 0);
	
	// Templates von dieses Plugin entfernen
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_newmembers'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_newmembers_row'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_topposters'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_topposters_row'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_newthreads'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_newthreads_row'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_mostreplies'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_mostreplies_row'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_favouritethreads'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_favouritethreads_row'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_newposts'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_column_newposts_row'");
	$db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title='index_overview_message'");
	
	// Einstellungsgruppen löschen
	$query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='Overview'");
	$g = $db->fetch_array($query);
	$db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE gid='".$g['gid']."'");

	// Einstellungen löschen
	$db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE gid='".$g['gid']."'");

	// Rebuilt settings.php
	rebuild_settings();
}

### Funktionen ###
function overview()
{
    global $db, $mybb, $cache, $templates, $theme, $lang, $overview;
    
    if($mybb->settings['overview_ajax_onoff'] != "yes") {

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
    }
}

function overview_end() {
    global $mybb, $intervall, $overview_headerinclude, $overview_body_onload, $overview_body_onload2, $overview, $overview_body;
    if($mybb->settings['overview_no_guests'] == "yes") {
        if($mybb->user['uid'] == "0") {
            $overview_headerinclude = "";
            $overview_body_onload = "";
            $overview_body = "";
            $overview = "";
        } else {
            if($mybb->settings['overview_ajax_onoff'] == "yes") {
                if($mybb->settings['overview_ajax_loading'] == "yes") {
                    $loaddisplay = "1";
                } else {
                    $loaddisplay = "0";
                }
                $intervall = $mybb->settings['overview_ajax_time'] * 1000;
                $overview_headerinclude = "<script type=\"text/javascript\" src=\"jscripts/overview.js\"></script>\n<script language=\"JavaScript\" type=\"text/javascript\">\nvar req = createXMLHttpRequest();\n</script>";
                $overview_body_onload = " onload=\"dooverview(".$loaddisplay.");\"";
                $overview_body_onload2 = "; dooverview(".$loaddisplay.")";
                $overview = "<span id=\"overview_load\"></span>\n<div id=\"overview\"></div>";
                $overview_body = "<script type=\"text/javascript\">\nsetInterval('dooverview(".$loaddisplay.")', ".$intervall.");\n</script>";
            } else {
                $overview_headerinclude = "";
                $overview_body_onload = "";
                $overview_body = "";
            }
        }
    } else {
        if($mybb->settings['overview_ajax_onoff'] == "yes") {
            if($mybb->settings['overview_ajax_loading'] == "yes") {
                $loaddisplay = "1";
            } else {
                $loaddisplay = "0";
            }
            $intervall = $mybb->settings['overview_ajax_time'] * 1000;
            $overview_headerinclude = "<script type=\"text/javascript\" src=\"jscripts/overview.js\"></script>\n<script language=\"JavaScript\" type=\"text/javascript\">\nvar req = createXMLHttpRequest();\n</script>";
            $overview_body_onload = " onload=\"dooverview(".$loaddisplay.");\"";
            $overview = "<span id=\"overview_load\"></span>\n<div id=\"overview\"></div>";
            $overview_body = "<script type=\"text/javascript\">\nsetInterval('dooverview(".$loaddisplay.")', ".$intervall.");\n</script>";
        } else {
            $overview_headerinclude = "";
            $overview_body_onload = "";
            $overview_body = "";
        }
    }
} 

### Eigene Funktionen ###

// Neueste Mitglieder zeigen?
function overview_do_newestusers() {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_newest_members'] == "yes") {
        
        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        // Daten für neueste Benutzer aus Datenbank auslesen
        $query1 = $db->query("SELECT username,postnum,uid,usergroup,displaygroup FROM ".TABLE_PREFIX."users ORDER BY uid DESC LIMIT 0,".$mybb->settings['overview_max']."");

        // Daten ausgeben
        while ($newest_members = $db->fetch_array($query1)) {
            $uid = $newest_members['uid'];
            $username = overview_usernamestyle(do_htmlentities($newest_members['username']), $newest_members['usergroup'], $newest_members['displaygroup']);
	        $postnum = $newest_members['postnum'];
            eval("\$newmembers_row .= \"".$templates->get("index_overview_column_newmembers_row")."\";");
        }
        eval("\$column_newmembers = \"".$templates->get("index_overview_column_newmembers")."\";");
    }
    return $column_newmembers;
}

// Top Poster zeigen?
function overview_do_topposters() {

    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_top_posters'] == "yes") {
	
	    // Hintergrund festlegen
        $trow = overview_trowcolor($trow);
		
        // Daten für Top Poster aus Datenbank auslesen
        $query2 = $db->query ("SELECT username,postnum,uid,usergroup,displaygroup FROM ".TABLE_PREFIX."users ORDER BY postnum DESC LIMIT 0,".$mybb->settings['overview_max']."");

        // Daten ausgeben
        while ($topposters = $db->fetch_array($query2)) {
            $uid = $topposters['uid'];
            $username = overview_usernamestyle(do_htmlentities($topposters['username']), $topposters['usergroup'], $topposters['displaygroup']);
	        $postnum = $topposters['postnum'];
            eval("\$topposters_row .= \"".$templates->get("index_overview_column_topposters_row")."\";");
        }
        eval("\$column_topposters = \"".$templates->get("index_overview_column_topposters")."\";");
    }
    return $column_topposters;
}

// Neueste Themen zeigen?
function overview_do_newestthreads() {

    global $mybb, $db, $templates, $theme, $lang, $trow, $unviewwhere, $parser;

    if($mybb->settings['overview_newest_threads'] == "yes") {
	
	    // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        // Daten für neueste Themen aus Datenbank auslesen
        $query3 = $db->query ("SELECT subject,username,uid,tid,replies FROM ".TABLE_PREFIX."threads WHERE visible='1' ".get_unviewable()." ORDER BY dateline DESC LIMIT 0,".$mybb->settings['overview_max']."");

        // Daten ausgeben
        while ($newest_threads = $db->fetch_array($query3)) {
	        $subject_long = do_htmlentities($parser->parse_badwords($newest_threads['subject']));

	        $tid = $newest_threads['tid'];
	        $uid = $newest_threads['uid'];
	        $subject = do_htmlentities(overview_limitsubject($parser->parse_badwords($newest_threads['subject']), $mybb->settings['overview_subjects_lenght']));
            $username = overview_usernamestyle_db($uid, do_htmlentities($newest_threads['username']));
	        $replies = $newest_threads['replies'];
            eval("\$newthreads_row .= \"".$templates->get("index_overview_column_newthreads_row")."\";");
        }
        eval("\$column_newthreads = \"".$templates->get("index_overview_column_newthreads")."\";");
    }
    return $column_newthreads;
}

// Themen mit meisten Antworten zeigen?
function overview_do_mostreplies() {

    global $mybb, $db, $templates, $theme, $lang, $trow, $unviewwhere, $parser;
    
    if($mybb->settings['overview_most_replies'] == "yes") {
	
	    // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        // Daten für Themen mit meisten Antworten aus Datenbank auslesen
        $query4 = $db->query ("SELECT subject,tid,replies FROM ".TABLE_PREFIX."threads WHERE visible='1' ".get_unviewable()." ORDER BY replies DESC LIMIT 0,".$mybb->settings['overview_max']."");

        // Daten ausgeben
        while ($most_replies = $db->fetch_array($query4)) {
	        $subject_long = do_htmlentities($parser->parse_badwords($most_replies['subject']));

	        $tid = $most_replies['tid'];
	        $subject = do_htmlentities(overview_limitsubject($parser->parse_badwords($most_replies['subject']), $mybb->settings['overview_subjects_lenght']));
	        $replies = $most_replies['replies'];
            eval("\$mostreplies_row .= \"".$templates->get("index_overview_column_mostreplies_row")."\";");
        }
        eval("\$column_mostreplies = \"".$templates->get("index_overview_column_mostreplies")."\";");
    }
    return $column_mostreplies;
}

// Beliebteste Themen zeigen?
function overview_do_favouritethreads() {

    global $mybb, $db, $templates, $theme, $lang, $trow, $unviewwhere, $parser;
    
    if($mybb->settings['overview_favourite_threads'] == "yes") {
	
        // Hintergrund festlegen
        $trow = overview_trowcolor($trow);
		
        // Daten für beliebteste Themen aus Datenbank auslesen
        $query5 = $db->query("SELECT subject,tid,views FROM ".TABLE_PREFIX."threads WHERE visible='1' ".get_unviewable()." ORDER BY views DESC LIMIT 0,".$mybb->settings['overview_max']."");

        // Daten ausgeben
        while ($favourite_threads = $db->fetch_array($query5)) {
	        $subject_long = do_htmlentities($parser->parse_badwords($favourite_threads['subject']));

	        $tid = $favourite_threads['tid'];
	        $subject = do_htmlentities(overview_limitsubject($parser->parse_badwords($favourite_threads['subject']), $mybb->settings['overview_subjects_lenght']));
	        $views = $favourite_threads['views'];
            eval("\$favouritethreads_row .= \"".$templates->get("index_overview_column_favouritethreads_row")."\";");
        }
        eval("\$column_favouritethreads = \"".$templates->get("index_overview_column_favouritethreads")."\";");
    }
    return $column_favouritethreads;
}

// Neueste Beiträge zeigen?
function overview_do_newestposts() {

    global $mybb, $db, $templates, $theme, $lang, $trow, $unviewwhere, $parser;
    
    if($mybb->settings['overview_newest_posts'] == "yes") {
  
	    // Hintergrund festlegen
        $trow = overview_trowcolor($trow);

        // Daten für neueste Themen aus Datenbank auslesen
        $query6 = $db->query ("SELECT subject,username,uid,pid,tid FROM ".TABLE_PREFIX."posts WHERE visible='1' ".get_unviewable()." ORDER BY dateline DESC LIMIT 0,".$mybb->settings['overview_max']."");

        // Daten ausgeben
        while ($newest_posts = $db->fetch_array($query6)) {
            if($mybb->settings['overview_show_re'] == "no") {
                $newest_posts['subject'] = str_replace("RE: ", "", $newest_posts['subject']);
            }
	        $subject_long = do_htmlentities($parser->parse_badwords($newest_posts['subject']));

            $pid = $newest_posts['pid'];
	        $tid = $newest_posts['tid'];
	        $uid = $newest_posts['uid'];
	        $subject = do_htmlentities(overview_limitsubject($parser->parse_badwords($newest_posts['subject']), $mybb->settings['overview_subjects_lenght']));
	        $username = overview_usernamestyle_db($uid, do_htmlentities($newest_posts['username']));
            eval("\$newposts_row .= \"".$templates->get("index_overview_column_newposts_row")."\";");
        }
        eval("\$column_newposts = \"".$templates->get("index_overview_column_newposts")."\";");
    }
    return $column_newposts;
}

// Entitäten verarbeiten
function do_htmlentities($var) {
    global $mybb;
    if($mybb->settings['overview_do_htmlentities'] == "1") {
        $var = htmlentities($var);
    } else {
        $var = htmlspecialchars($var);
    }
    return $var;
}

// Betreff kürzen
function overview_limitsubject($subject, $length)
{
    if ($length != 0)
	{
        if (strlen($subject) > $length) 
        {
	    $subject = substr($subject,0,$length) . "...";
	    }
	}
	return $subject;
}

// Benutzernamenstil
function overview_usernamestyle($username, $usergroup, $displaygroup)
{
    global $mybb;

    if($mybb->settings['overview_usernamestyle'] == "yes")
    {
    $username = format_name($username, $usergroup, $displaygroup);
    }
    return $username;
}

// Benutzernamenstil mit DB-Abfrage
function overview_usernamestyle_db($uid, $username)
{
    global $mybb, $db;

    if($mybb->settings['overview_usernamestyle'] == "yes")
    {
    $query = $db->query("SELECT usergroup,displaygroup FROM ".TABLE_PREFIX."users WHERE uid = '".$uid."'");
	$query_array = $db->fetch_array($query);
    $username = format_name($username, $query_array['usergroup'], $query_array['displaygroup']);
    }
    return $username;
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
	    elseif ($trow == "trow2") 
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
    if($mybb->settings['overview_newest_members'] == "yes") {
    $i++;
    }
    if($mybb->settings['overview_top_posters'] == "yes") {
    $i++;
    }
    if($mybb->settings['overview_newest_threads'] == "yes") {
    $i++;
    }
    if($mybb->settings['overview_most_replies'] == "yes") {
    $i++;
    }
    if($mybb->settings['overview_favourite_threads'] == "yes") {
    $i++;
    }
    if($mybb->settings['overview_newest_posts'] == "yes") {
    $i++;
    }
	return $i;
}

// Unsichtbare Foren ermitteln
function get_unviewable() {
    $unviewwhere = "";
    $unviewable = get_unviewable_forums();
	if($unviewable) {
        $unviewwhere = "AND fid NOT IN (".$unviewable.")";
	}
	return $unviewwhere;
}

// Einstellungen erneuern
if(!function_exists("rebuild_settings")) {

	function rebuild_settings() {
		global $db;
		$query = $db->query("SELECT * FROM ".TABLE_PREFIX."settings ORDER BY title ASC");
		while($setting = $db->fetch_array($query)) {
			$setting['value'] = addslashes($setting['value']);
			$settings .= "\$settings['".$setting['name']."'] = \"".$setting['value']."\";\n";
		}
		$settings = "<?php\n/*********************************\ \n  DO NOT EDIT THIS FILE, PLEASE USE\n  THE SETTINGS EDITOR\n\*********************************/\n\n$settings\n?>";
		$file = fopen(MYBB_ROOT."/inc/settings.php", "w");
		fwrite($file, $settings);
		fclose($file);
	}
}
?>
<?php
/**
 * This file is part of Overview plugin for MyBB.
 * Copyright (C) 2005-2009 Michael Schlechtinger <kontakt@mybboard.de>
 * Copyright (C) 2010 Andreas Klauer <Andreas.Klauer@metamorpher.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

if(!defined("IN_MYBB"))
{
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

/* --- Hooks: --- */

// AJAX hook.
$plugins->add_hook("xmlhttp", "overview_ajax");

// Index hooks (add only if not disabled for index)
global $settings;

if(!$settings['overview_noindex'])
{
    $plugins->add_hook("index_start", "overview");
    $plugins->add_hook("index_end", "overview_end");
}

// Dirty cache hooks (add only if cache enabled)
if(intval($settings['overview_cache']) > 0)
{
    $plugins->add_hook("datahandler_post_insert_post", "overview_deletecache");
    $plugins->add_hook("datahandler_post_insert_thread", "overview_deletecache");
    $plugins->add_hook("datahandler_event_insert", "overview_deletecache");
    $plugins->add_hook("datahandler_user_insert", "overview_deletecache");
    $plugins->add_hook("admin_config_settings_change", "overview_deletecache");
}

// Custom hooks that are safe to call on custom pages.
$plugins->add_hook("overview_start", "overview");
$plugins->add_hook("overview_end", "overview_end");

/* --- Plugin-API: --- */

function overview_info()
{
    return array(
        "name"          => "Overview",
        "title"         => "Overview",
        "description"   => "Displays a box on the index page that shows different infomations about your board.<br />"
                           ."<i>Maintained by <a href=\"mailto:Andreas.Klauer@metamorpher.de\">Andreas Klauer</a></i>",
        "website"       => "http://www.mybboard.de",
        "author"        => "Michael Schlechtinger",
        "authorsite"    => "http://www.mybboard.de",
        "version"       => "3.9.0",
        "guid"          => "79710704156952a4cf8793808c6ab3ea",
        "compatibility" => "16*"
        );
}

function overview_install()
{
    // Clean up to avoid double overview effect.
    overview_uninstall();

    global $db;

    // Insert templates
    $templatearray = array(
        "title" => "overview",
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
        "title" => "overview_2_columns",
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
        "title" => "overview_2_columns_row",
        "template" => "<tr class=\"{\$trow}\">
        <td valign=\"top\"><div class=\"smalltext\">{\$val1}</div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\">{\$val2}</div></td>
        </tr>",
        "sid" => -1
        );
    $db->insert_query("templates", $templatearray);

    $templatearray = array(
        "title" => "overview_3_columns",
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
        "title" => "overview_3_columns_row",
        "template" => "<tr class=\"{\$trow}\">
        <td valign=\"top\"><div class=\"smalltext\">{\$val1}</div></td>
        <td valign=\"top\"><div class=\"smalltext\">{\$val2}</div></td>
        <td align=\"right\" valign=\"top\"><div class=\"smalltext\">{\$val3}</div></td>
        </tr>",
        "sid" => -1
        );
    $db->insert_query("templates", $templatearray);

    $templatearray = array(
        "title" => "overview_message",
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

    $query = $db->query("SELECT MAX(disporder) as disporder
                         FROM ".TABLE_PREFIX."settinggroups");
    $row = $db->fetch_array($query);
    $disporder = $row['disporder'] + 1;

    // Insert setting groups
    $overview_group = array(
        "name" => "Overview",
        "title" => "Overview",
        "description" => "Settings for the \"Overview\"-Plugin.",
        "disporder" => $disporder,
        "isdefault" => 0
        );
    $db->insert_query("settinggroups", $overview_group);
    $gid = intval($db->insert_id());

    $disp = 1;

    // Insert settings
    $setting = array(
        "name" => "overview_max",
        "title" => "Number of Items",
        "description" => "Enter the number of items (Users/Threads/Posts) to be shown.",
        "optionscode" => "text",
        "value" => 5,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_newest_members",
        "title" => "Show newest members?",
        "description" => "Choose if you want the newest members to be shown.",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_do_newestusers",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => "1",
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_top_posters",
        "title" => "Show Top Posters?",
        "description" => "Choose if you want the top posters to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => intval($gid)
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_do_topposters",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 2,
        "disporder" => $disp++,
        "gid" => intval($gid)
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_newest_threads",
        "title" => "Show newest threads?",
        "description" => "Choose if you want the newest threads to be shown.",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => $disp++,
        "gid" => intval($gid)
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_do_newestthreads",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 3,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_most_replies",
        "title" => "Show threads with most replies?",
        "description" => "Choose if you want the threads with the most replies to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_do_mostreplies",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 4,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_favourite_threads",
        "title" => "Show favourite Threads?",
        "description" => "Choose if you want the favourite threads to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_do_favouritethreads",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 5,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_newest_posts",
        "title" => "Show newest posts?",
        "description" => "Choose if you want the newest posts to be shown.",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_do_newestposts",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 6,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_bestrep_members",
        "title" => "Show best reputated members?",
        "description" => "Choose if you want the best reputated members to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_do_bestrepmembers",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 7,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_newest_polls",
        "title" => "Show best newest polls?",
        "description" => "Choose if you want the newest polls to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_do_newestpolls",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 8,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_next_events",
        "title" => "Show best next events?",
        "description" => "Choose if you want the next events to be shown.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_do_nextevents",
        "title" => "Sorting",
        "description" => "Here you can change the order.",
        "optionscode" => "text",
        "value" => 9,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_show_re",
        "title" => "Do you want to show the \"RE:\" from the subjects of replies?",
        "description" => "Choose if you want the \"RE:\" to be shown in front of the subjects of replies.",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_subjects_length",
        "title" => "Number of Characters",
        "description" => "How many characters of subjects should be shown (0 = show all)?",
        "optionscode" => "text",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_usernamestyle",
        "title" => "Format usernames?",
        "description" => "Do you want to format the usernames in the style of their usergroups?",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_showicon",
        "title" => "Show post icons?",
        "description" => "Do you want to display post icons in front of subjects?",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_showprefix",
        "title" => "Show thread prefix?",
        "description" => "Do you want to display thread prefix in front of subjects?",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_trow_message_onoff",
        "title" => "Show message?",
        "description" => "Choose if you want to show a message.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_trow_message",
        "title" => "Message",
        "description" => "Enter the message. You can use MyCode.",
        "optionscode" => "textarea",
        "value" => "Enter your message here!",
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_ajax_onoff",
        "title" => "Ajax",
        "description" => "Do you want to enable the Ajax functionality so that the overview box reloads itself?",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_ajax_time",
        "title" => "Period",
        "description" => "Enter the period of time after that the overview box should reload itself (Seconds)?",
        "optionscode" => "text",
        "value" => 60,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_ajax_loading",
        "title" => "Loading",
        "description" => "Do you want to show a \"Loading\"-Window?",
        "optionscode" => "yesno",
        "value" => 1,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_usergroups",
        "title" => "Disable overview for usergroups",
        "description" => "Enter the IDs of the usergroups that should not see the overview table (0 = none). Seperate several IDs with commas.",
        "optionscode" => "text",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_noindex",
        "title" => "Hide overview from index page",
        "description" => "If you don\\'t want the overview to display on the index page, say yes. This is only useful if you make a custom overview page.",
        "optionscode" => "yesno",
        "value" => 0,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    $setting = array(
        "name" => "overview_cache",
        "title" => "Cache overview",
        "description" => "Building the Overview requires some database queries. The Overview result can be cached to reduce server load. Specify for how long the cache should be used (in seconds). Setting to 0 disables the cache.",
        "optionscode" => "text",
        "value" => 300,
        "disporder" => $disp++,
        "gid" => $gid,
        );
    $db->insert_query("settings", $setting);

    // rebuild settings.php
    rebuild_settings();
}

function overview_uninstall()
{
    global $db;

    // Remove templates
    $templatearray = array(
        "overview",
        "overview_2_columns",
        "overview_2_columns_row",
        "overview_3_columns",
        "overview_3_columns_row",
        "overview_message",
        );

    $deltemplates = implode("','", $templatearray);

    // Kill old templates too, if present.
    $deltemplates .= "','index_" . implode("','index_", $templatearray);

    $db->query("DELETE FROM ".TABLE_PREFIX."templates WHERE title IN ('{$deltemplates}');");

    // Remove setting groups
    $query = $db->query("SELECT gid FROM ".TABLE_PREFIX."settinggroups WHERE name='Overview'");
    $g = $db->fetch_array($query);
    $db->query("DELETE FROM ".TABLE_PREFIX."settinggroups WHERE gid='{$g['gid']}'");

    // Remove settings
    $db->query("DELETE FROM ".TABLE_PREFIX."settings WHERE gid='{$g['gid']}'");

    // rebuild settings.php
    rebuild_settings();
}

function overview_is_installed()
{
    global $templates;

    if($templates->get("overview", 0, 0))
    {
        return true;
    }

    return false;
}

function overview_activate()
{
    // Clean up to avoid double overview effect.
    overview_deactivate();

    // Insert variables into templates
    require_once MYBB_ROOT."inc/adminfunctions_templates.php";
    find_replace_templatesets("index", '#{\$header}(\r?)\n#', "{\$header}\n{\$overview}\n");
    find_replace_templatesets("index", '#{\$footer}(\r?)\n#', "{\$footer}\n{\$overview_body}\n");
    find_replace_templatesets("index", '#<body>(\r?)\n#', "<body{\$overview_body_onload}>\n");
    find_replace_templatesets("index", '#{\$headerinclude}(\r?)\n#', "{\$headerinclude}\n{\$overview_headerinclude}\n");
}

function overview_deactivate()
{
    // Remove variables from templates
    require_once MYBB_ROOT."/inc/adminfunctions_templates.php";
    find_replace_templatesets("index", '#{\$overview}(\r?)\n#', "", 0);
    find_replace_templatesets("index", '#{\$overview_body}(\r?)\n#', "", 0);
    find_replace_templatesets("index", '#<body{\$overview_body_onload}>(\r?)\n#', "<body>\n", 0);
    find_replace_templatesets("index", '#{\$overview_headerinclude}(\r?)\n#', "", 0);

    overview_deletecache();
}

function overview_deletecache()
{
    global $cache, $db;

    // Remove cache
    if(is_object($cache->handler))
    {
        $query = $db->simple_select("datacache", "title", "title LIKE 'overview%'");
        while($row = $db->fetch_array($query))
        {
            $cache->handler->delete($row['title']);
        }
    }

    $db->delete_query("datacache", "title LIKE 'overview%'");
}

/* --- Functions: --- */

function overview()
{
    global $db, $mybb, $cache, $templates, $theme, $lang, $overview, $collapsed;

    if($mybb->settings['overview_usergroups'] != 0)
    {
        $overviewgroups = explode(",", $mybb->settings['overview_usergroups']);
    }

    if($mybb->settings['overview_usergroups'] == 0 || !in_array($mybb->user['usergroup'], $overviewgroups))
    {
        // Fetch from cache, if present.
        $delta = intval($mybb->settings['overview_cache']);
        $extra = "{$mybb->user['usergroup']}{$mybb->user['additionalgroups']}";

        if($delta > 0)
        {
            $overcache = $cache->read("overview{$extra}");

            if($overcache && $overcache['time'] >= (TIME_NOW-$delta))
            {
                $overview = $overcache['data'];
                return $overview;
            }
        }

        // No luck with the cache, build the overview:

        $language = $mybb->settings['bblanguage'];

        // Load language files
        $lang->load("overview");

        // Determine number of columns
        $num_columns = overview_num_columns();

        // Exclude unviewable forums
        $overview_unviewwhere = "";
        $overview_unviewable = get_unviewable_forums();
        if($overview_unviewable)
        {
            $overview_unviewwhere = "AND fid NOT IN ({$overview_unviewable})";
        }

        // Define variables
        $overview_content = "";
        $trow_message = "";
        $overview = "";

        // Determine sort order
        $orderquery = $db->query("
            SELECT name from ".TABLE_PREFIX."settings
            WHERE name IN ('overview_do_newestusers','overview_do_topposters','overview_do_newestthreads','overview_do_mostreplies','overview_do_favouritethreads','overview_do_newestposts','overview_do_bestrepmembers','overview_do_newestpolls','overview_do_nextevents')
            ORDER BY value ASC
        ;");

        $collapseinsert1 = $collapseinsert2 = "";

        // Output data
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

        // Show message?
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
            eval("\$trow_message = \"".$templates->get("overview_message")."\";");
        }

        // Load template
        eval("\$overview = \"".$templates->get("overview")."\";");

        // Populate cache
        if($delta > 0)
        {
            $cache->update("overview{$extra}",
                           array('time' => TIME_NOW, 'data' => $overview));
        }

        return $overview;
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
        $overview_headerinclude = "<script type=\"text/javascript\" src=\"jscripts/overview.js\"></script>\n";
        $overview_body_onload = " onload=\"overview_request(".$loaddisplay.");\"";
        $overview_body_onload2 = "; overview_request(".$loaddisplay.")";
        $overview = "<span id=\"overview_load\"></span>\n<div id=\"overview\"></div>";
        $overview_body = "<script type=\"text/javascript\">\nsetInterval('overview_request(".$loaddisplay.")', ".$intervall.");\n</script>";
    }
}

// Newest users
function overview_do_newestusers()
{
    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_newest_members'] == 1)
    {
        $trow = alt_trow();

        $table_heading = $lang->overview_newest_members;
        $column1_heading = $lang->overview_username;
        $column2_heading = $lang->overview_posts;

        // Fetch data for newest user from database
        $query = $db->query("
            SELECT username, postnum, uid, usergroup, displaygroup
            FROM ".TABLE_PREFIX."users
            ORDER BY uid DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Print data
        while ($users = $db->fetch_array($query))
        {
            $val1 = overview_parseuser($users['uid'], $users['username'], $users['usergroup'], $users['displaygroup']);
            $val2 = "<a href=\"search.php?action=finduser&amp;uid={$users['uid']}\">{$users['postnum']}</a>";
            eval("\$table_content .= \"".$templates->get("overview_2_columns_row")."\";");
        }

        eval("\$output = \"".$templates->get("overview_2_columns")."\";");
    }

    return $output;
}

// Top posters
function overview_do_topposters()
{
    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_top_posters'] == 1)
    {
        $trow = alt_trow();

        $table_heading = $lang->overview_top_posters;
        $column1_heading = $lang->overview_username;
        $column2_heading = $lang->overview_posts;

        // Fetch data for top posters from database
        $query = $db->query("
            SELECT username, postnum, uid, usergroup, displaygroup
            FROM ".TABLE_PREFIX."users
            ORDER BY postnum DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Print data
        while ($users = $db->fetch_array($query))
        {
            $val1 = overview_parseuser($users['uid'], $users['username'], $users['usergroup'], $users['displaygroup']);
            $val2 = "<a href=\"search.php?action=finduser&amp;uid={$users['uid']}\">{$users['postnum']}</a>";
            eval("\$table_content .= \"".$templates->get("overview_2_columns_row")."\";");
        }

        eval("\$output = \"".$templates->get("overview_2_columns")."\";");
    }

    return $output;
}

// Newest threads
function overview_do_newestthreads($overview_unviewwhere)
{
    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_newest_threads'] == 1)
    {
        // Hintergrund festlegen
        $trow = alt_trow();

        $table_heading = $lang->overview_newest_threads;
        $column1_heading = $lang->overview_topic;
        $column2_heading = $lang->overview_author;
        $column3_heading = $lang->overview_replies;

        // Fetch data
        $query = $db->query("
            SELECT subject, username, uid, tid, replies, icon, prefix
            FROM ".TABLE_PREFIX."threads
            WHERE visible = '1' {$overview_unviewwhere} AND closed NOT LIKE 'moved|%'
            ORDER BY dateline DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Print data
        while ($threads = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($threads['subject'], $threads['icon'], $threads['prefix'], $threads['tid']);
            $val2 = overview_parseuser($threads['uid'], $threads['username']);
            $val3 = "<a href=\"javascript:MyBB.whoPosted({$threads['tid']});\">{$threads['replies']}</a>";
            eval("\$table_content .= \"".$templates->get("overview_3_columns_row")."\";");
        }

        eval("\$output = \"".$templates->get("overview_3_columns")."\";");
    }

    return $output;
}

// Most replies
function overview_do_mostreplies($overview_unviewwhere)
{
    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_most_replies'] == 1)
    {
        $trow = alt_trow();

        $table_heading = $lang->overview_most_replies;
        $column1_heading = $lang->overview_topic;
        $column2_heading = $lang->overview_replies;

        // Fetch data
        $query = $db->query("
            SELECT subject, tid, replies, icon, prefix
            FROM ".TABLE_PREFIX."threads
            WHERE visible = '1' {$overview_unviewwhere} AND closed NOT LIKE 'moved|%'
            ORDER BY replies DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Print data
        while($threads = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($threads['subject'], $threads['icon'], $threads['prefix'], $threads['tid']);
            $val2 = "<a href=\"javascript:MyBB.whoPosted({$threads['tid']});\">{$threads['replies']}</a>";
            eval("\$table_content .= \"".$templates->get("overview_2_columns_row")."\";");
        }

        eval("\$output = \"".$templates->get("overview_2_columns")."\";");
    }

    return $output;
}

// Favourite threads
function overview_do_favouritethreads($overview_unviewwhere)
{
    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_favourite_threads'] == 1)
    {
        $trow = alt_trow();

        $table_heading = $lang->overview_favourite_threads;
        $column1_heading = $lang->overview_topic;
        $column2_heading = $lang->overview_views;

        // Fetch data
        $query = $db->query("
            SELECT subject, tid, views, icon, prefix
            FROM ".TABLE_PREFIX."threads
            WHERE visible = '1' {$overview_unviewwhere} AND closed NOT LIKE 'moved|%'
            ORDER BY views DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Print data
        while ($threads = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($threads['subject'], $threads['icon'], $threads['prefix'], $threads['tid']);
            $val2 = $threads['views'];
            eval("\$table_content .= \"".$templates->get("overview_2_columns_row")."\";");
        }

        eval("\$output = \"".$templates->get("overview_2_columns")."\";");
    }

    return $output;
}

// Newest posts
function overview_do_newestposts($overview_unviewwhere)
{
    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_newest_posts'] == 1)
    {
        $trow = alt_trow();

        $table_heading = $lang->overview_newest_posts;
        $column1_heading = $lang->overview_subject;
        $column2_heading = $lang->overview_author;

        // Fetch data
        $query = $db->query("
            SELECT subject, username, uid, pid, tid, icon
            FROM ".TABLE_PREFIX."posts
            WHERE visible='1' {$overview_unviewwhere}
            ORDER BY dateline DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Print data
        while($posts = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($posts['subject'], $posts['icon'], 0, $posts['tid'], $posts['pid'], 0, 1);
            $val2 = overview_parseuser($posts['uid'], $posts['username']);
            eval("\$table_content .= \"".$templates->get("overview_2_columns_row")."\";");
        }

        eval("\$output = \"".$templates->get("overview_2_columns")."\";");
    }

    return $output;
}

// Next events
function overview_do_nextevents()
{
    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_next_events'] == 1)
    {
        $trow = alt_trow();

        $table_heading = $lang->overview_next_events;
        $column1_heading = $lang->overview_event;
        $column2_heading = $lang->overview_author;

        if($mybb->usergroup['canviewcalendar'] == 1)
        {
            // Permissions
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

            // TODO: Instead of substracting 24 hours, align to the users timezone boundary.
            $today = TIME_NOW - 60*60*24;

            // Decide whether we can include private events or not.
            if(intval($mybb->settings['overview_cache']) > 0)
            {
                $private = "e.private='0'";
            }

            else
            {
                $private = "(e.private='0' OR e.uid='".intval($mybb->user['uid'])."')";
            }

            // Fetch data
            $query = $db->query("
                SELECT e.eid, e.name, e.starttime, e.uid, u.username, u.usergroup, u.displaygroup
                FROM ".TABLE_PREFIX."events e
                LEFT JOIN ".TABLE_PREFIX."users u ON (e.uid=u.uid)
                WHERE e.visible = '1' AND {$private} AND (e.starttime > '{$today}' OR e.endtime > '{$today}') {$cids}
                ORDER BY starttime ASC
                LIMIT 0,{$mybb->settings['overview_max']}
            ;");

            // Print data
            while($events = $db->fetch_array($query))
            {
                $events['name'] = my_date($mybb->settings['dateformat'], $events['starttime']).": ".$events['name'];
                $val1 = overview_parsesubject($events['name'], 0, 0, 0, 0, $events['eid'], 0);
                $val2 = overview_parseuser($events['uid'], $events['username'], $events['usergroup'], $events['displaygroup']);
                eval("\$table_content .= \"".$templates->get("overview_2_columns_row")."\";");
            }
        }

        eval("\$output = \"".$templates->get("overview_2_columns")."\";");
    }

    return $output;
}

// Newest polls
function overview_do_newestpolls($overview_unviewwhere)
{
    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_newest_polls'] == 1)
    {
        $trow = alt_trow();

        $table_heading = $lang->overview_newest_polls;
        $column1_heading = $lang->overview_question;
        $column2_heading = $lang->overview_author;

        // Fetch data
        $query = $db->query("
            SELECT p.question, p.tid, t.uid, t.username
            FROM ".TABLE_PREFIX."polls p
            LEFT JOIN ".TABLE_PREFIX."threads t ON (p.tid=t.tid)
            WHERE t.visible='1' {$overview_unviewwhere} AND t.closed NOT LIKE 'moved|%'
            ORDER BY p.pid DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Print data
        while($polls = $db->fetch_array($query))
        {
            $val1 = overview_parsesubject($polls['question'], 0, 0, $polls['tid']);
            $val2 = overview_parseuser($polls['uid'], $polls['username']);
            eval("\$table_content .= \"".$templates->get("overview_2_columns_row")."\";");
        }

        eval("\$output = \"".$templates->get("overview_2_columns")."\";");
    }

    return $output;
}

// Members with the best reputation
function overview_do_bestrepmembers()
{
    global $mybb, $db, $templates, $theme, $lang, $trow;

    if($mybb->settings['overview_bestrep_members'] == 1)
    {
        $trow = alt_trow();

        $table_heading = $lang->overview_bestrep_members;
        $column1_heading = $lang->overview_username;
        $column2_heading = $lang->overview_reputation;

        // Fetch data
        $query = $db->query("
            SELECT username, reputation, uid, usergroup, displaygroup
            FROM ".TABLE_PREFIX."users
            ORDER BY reputation DESC
            LIMIT 0,{$mybb->settings['overview_max']}
        ;");

        // Print data
        while ($users = $db->fetch_array($query))
        {
            $val1 = overview_parseuser($users['uid'], $users['username'], $users['usergroup'], $users['displaygroup']);
            $val2 = get_reputation($users['reputation'], $users['uid']);
            eval("\$table_content .= \"".$templates->get("overview_2_columns_row")."\";");
        }

        eval("\$output = \"".$templates->get("overview_2_columns")."\";");
    }

    return $output;
}

function overview_parsesubject($subject, $icon=0, $prefix=0, $tid=0, $pid=0, $eid=0, $removere=0)
{
    global $mybb, $parser, $cache, $db;

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

    if($mybb->settings['overview_subjects_length'] != 0)
    {
        if(my_strlen($subject) > $mybb->settings['overview_subjects_length'])
        {
            $subject = my_substr($subject, 0, $mybb->settings['overview_subjects_length'])."...";
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

    // Icon
    if($mybb->settings['overview_showicon'] != 0 && $icon > 0)
    {
        $icon_cache = $cache->read("posticons");
        $icon = $icon_cache[$icon];
    }

    if(is_array($icon))
    {
        $icon = "<img src=\"{$icon['path']}\" alt=\"{$icon['name']}\" style=\"vertical-align: middle;\" />&nbsp;";
    }

    else
    {
        $icon = "";
    }

    // Prefix
    if($mybb->settings['overview_showprefix'] && $prefix > 0)
    {
        // MyBB does not have a prefix cache - boo hoo.
        global $overview_prefixcache;

        if(!isset($overview_prefixcache[$prefix]))
        {
            $query = $db->simple_select('threadprefixes', 'displaystyle', "pid='$prefix'");
            $row = $db->fetch_array($query);

            if($row)
            {
                $overview_prefixcache[$prefix] = $row['displaystyle'].'&nbsp';
            }

            else
            {
                $overview_prefixcache[$prefix] = '';
            }
        }

        $prefix = $overview_prefixcache[$prefix];
    }

    else
    {
        $prefix = '';
    }

    return "{$icon}{$prefix}<a href=\"{$link}\" title=\"{$subjectfull}\">{$subject}</a>";
}

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

// Determine number of columns
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

/* --- End of file. --- */
?>

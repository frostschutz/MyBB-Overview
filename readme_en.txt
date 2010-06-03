### INFORMATION ###

Plugin: Overview
Version 3.0.4
Autor: MyBBoard.de
Website: http://www.mybboard.de

### NOTE ###
If you like this modification it would be nice to show your support. You can make a small donation via PayPal to paypal@mybboard.de. Thank you!

### INSTALLATION ###

1. Copy the plugin file into the plugin directory "inc/plugins".

2. Copy the language file into the folder "inc/languages/*language*/".

3. Copy the whole content of the folder "files" into the board's folder on your server.

3. After that you can activate the plugin via Admin-CP.

### UPDATE NOTE ###
If you installed a older version than 3.0.1 before you can remove the following code from your index.php:
--------------
// Overview
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
// Overview
--------------

### NOTE ###

You agree that the author of this plugin will not be held responsible for anything which may happen as a result of downloading, installing or using any of the files that you are downloading, including any instructions included in the download.
You are not allowed to redistribute this plugin or a modified version without permission.
You are not allowed to delete the visible Link to MyBBoard.de. You can place the link somewhere else on the index page.

### CHANGELOG ###

3.0.4
- Fix: Problems with special characters in message

3.0.3
- "Who posted" popup didn't work

3.0.2
- New: Polish language file
- Fix: Copied threads were shown in the newest threads column

3.0.1
- Integrated Bad Word Filter
- Ability to choose between htmlentities and htmlspecialchars
- No need of modifying files anymore
- Collapse image removed

3.0
- Copatibility to MyBB 1.2
- Bug with unviewable forums fixed

2.5
- New: The overview table reloads itself automatically (Ajax)
- New: You can choose between normal and Ajax version
- New: You can change the sorting of the columns
- New: You can deactivate the overview for guests
- New: You can use MyCode in the message

2.01
- New: Included laguagefiles for italian, turkish
- New: Possibility to format the usernames in the style of their usergroups

2.0
- New: Column "Threads with most replies"
- New: You can display a message
- New: The "RE:" in front of the subjects of replies can be removed
- New: A click on the number of posts of a thread shows the authors
- New: A click on the number of posts of a user shows the posts
- New: Included laguagefiles for arabic (simplified), dutch, english, german, hebrew, norwegian
- Fix: Collapse/expand bug
- Fix: Invalid HTML output
- Code audit

1.6
- Multilanguagesupport
- Fix: On some servers there was the problem that the number of table columns was wrong

1.5
- New Feature: Expand and Collapse
- Title-Tag added

1.4
- Fix: Drafts are shown

1.3
- New column: "Top Poster"
- New column: "Favourite Threads"

1.2
- Optimized code
- New Column: Newest Posts
- Each column can be activated or deactivated
- Number of items can be more than 10
- New templates
- Possibility to enter the maximal number of characters to show (subjects)

1.1
- Fix: Threads that are in forums that are not visible for all are shown

1.0
- First release
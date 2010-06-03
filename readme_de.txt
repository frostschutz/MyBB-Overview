### INFORMATION ###

Plugin: Übersicht
Version 3.0.4
Autor: MyBBoard.de
Website: http://www.mybboard.de

### HINWEIS ###
Wenn Ihnen diese Erweiterung gefällt, würden wir uns über eine kleine Unterstützung freuen. Weitere Informationen dazu finden Sie hier: http://www.mybboard.de/content/view/195/173/
Auch wenn das Skript kostenlos angeboten wird, ist die Arbeit daran nicht unerheblich. Zudem unterstützen Sie die deutsche Seite zum MyBB, die Ihnen u.a. auch die Übersetzung bereitstellt.

### INSTALLATION ###

1. Kopieren Sie die Plugin-Datei aus dem Ordner der gewünschten Sprachversion in den Ordner "inc/plugins/".

2. Kopieren Sie die Sprachdatei der gewünschten Sprache in den Ordner "inc/languages/*Sprache*/".

3. Kopieren Sie den gesamten Inhalt des Ordners "files" in den Ordner, in den Sie das Forum installiert haben.

3. Sie können das Plugin dann über den Plugin-Manager im Admin-CP aktivieren.

### UPDATE HINWEIS ###
Hatten Sie eine Version vor 3.0.1 installiert, können Sie den folgenden Code aus der Datei index.php des Forums löschen.
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

### UPDATE ###

Deaktivieren Sie das alte Plugin und folgen Sie der oben genannten Installationsanleitung.


### HINWEIS ###

Der Autor und die Übersetzer haften nicht für Schäden jeglicher Art, sollten Ihnen diese durch die Verwendung des Plugins entstehen.
Sie dürfen dieses Plugin nicht ohne Erlaubnis weitergeben (Download, o.ä.), dies betrifft auch modifizierte Versionen.
Es ist untersagt, den sichtbaren Link zu MyBBoard.de zu löschen. Sie können den Link woanders auf der Startseite einfügen.

### CHANGELOG ###

3.0.4
- Fix: Problem mit Sonderzeichen in einer Nachricht

3.0.3
- Fix: Popup mit Benutzern, die zu einem Thema geschrieben haben, hat sich nicht geöffnet

3.0.2
- Neu: Polnische Sprachdatei
- Fix: Kopierte Themen werden bei neuesten Themen gezeigt

3.0.1
- Badword-Filter integriert
- Möglichkeit zwischen htmlentities und htmlspecialchars zu wählen
- Keine Änderungen in Dateien mehr notwendig
- Zuklapp-Icon entfernt

3.0
- Kompatibilität zu MyBB 1.2
- Fehler mit nicht sichtbaren Foren behoben

2.5
- Neu: Durch Nutzung von Ajax lädt sich die Übersicht in einstellbaren Intervallen selbst neu
- Neu: Man kann zwischen normaler und Ajax-Version wählen
- Neu: Die Anzeigereihenfolge der einzelnen Boxen kann beliebig geändert werden
- Neu: Die Übersicht ist für Gäste deaktivierbar
- Neu: In der Nachricht kann MyCode verwendet werden

2.01
- Neu: Sprachdateien italienisch, türkisch enthalten
- Neu: Möglichkeit die Benutzernamen im Benutzergruppenstil anzeigen zu lassen

2.0
- Neu: Spalte "Themen mit meisten Antworten"
- Neu: Nachricht kann angezeigt werden
- Neu: Die Anzeige von "RE:" vor dem Betreff einer Antwort kann entfernt werden
- Neu: Ein Klick auf die Anzahl der Beiträge zu einem Thema zeigt die Verfasser an
- Neu: Ein Klick auf die Anzahl der Beiträge eines Benutzers zeigt alle Beiträge an
- Neu: Sprachdateien arabisch, englisch, deutsch, hebräisch, niederländisch, norwegisch enthalten
- Fix: Einstellung ein-/ausgeklappt wurde nicht gespeichert
- Fix: Umlaute wurden nicht umgewandelt (nicht valider HTML-Code)
- Code generalüberholt

1.6
- Unterstützung mehrerer Sprachen durch Sprachteien
- Fix: Anzahl der Tabellenspalten wurde auf einigen Servern nicht ermittelt

1.5
- Neues Feature: Aus- und Einklappen
- Title-Tag hinzugefügt

1.4
- Fix: Entwürfe werden gezeigt

1.3
- Neue Spalte: "Top Poster"
- Neue Spalte: "Beliebteste Themen"

1.2
- Codeoptimierung
- Neue Spalte: Neueste Beiträge
- Einzelne Spalten lassen sich im Admin-CP aktivieren und deaktivieren
- Anzahl der zu zeigenden Items nicht mehr auf höchstens 10 beschränkt
- Aufteilung in mehrere Templates zur besseren Anpassung
- Möglichkeit, die Anzahl der anzuzeigenden Zeichen der Betreffe/Themen zu begrenzen

1.1
- Fix: Themen aus für nur bestimmte Benutzergruppen sichtbare Foren werden für alle ausgegeben

1.0
- Erstveröffentlichung
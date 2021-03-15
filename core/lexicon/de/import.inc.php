<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Geben Sie eine kommaseparierte Liste von Dateiendungen für den Import an:<br /><small><em>Lassen Sie das Feld leer, wenn alle Dateien entsprechend den in Ihrer Site verfügbaren Inhaltstypen importiert werden sollen. Unbekannte Typen werden als einfacher Text behandelt.</em></small>';
$_lang['import_base_path'] = 'Geben Sie den Pfad zu dem Verzeichnis an, in dem die zu installierenden Dateien liegen:<br /><small><em>Lassen Sie dieses Feld leer, wenn Sie den Pfad für statische Dateien verwenden möchten, der für den Kontext, in den importiert wird, eingestellt ist.</em></small>';
$_lang['import_duplicate_alias_found'] = 'Ressource [[+id]] verwendet bereits den Alias [[+alias]]. Bitte geben Sie einen eindeutigen Alias an.';
$_lang['import_element'] = 'HTML-Element, dessen Inhalt jeweils importiert werden soll:';
$_lang['import_element_help'] = 'Stellen Sie JSON-Code mit Zuordnungen der Art "Feld":"Wert" zur Verfügung. Wenn der Wert mit einem Dollarzeichen ($) beginnt, handelt es sich um einen jQuery-artigen Selektor. "Feld" kann ein Ressourcen-Feld oder der Name einer TV sein.';
$_lang['import_enter_root_element'] = 'Geben Sie das Wurzelelement für den Import an:';
$_lang['import_files_found'] = '<strong>%s Dokumente zum Importieren gefunden …</strong><p/>';
$_lang['import_parent_document'] = 'Eltern-Dokument:';
$_lang['import_parent_document_message'] = 'Verwenden Sie den unten stehenden Ressourcen-Baum, um die Eltern-Ressource auszuwählen, in die Ihre Dateien importiert werden sollen.';
$_lang['import_resource_class'] = 'Wählen Sie eine modResource-Klasse für den Import:<br /><small><em>Verwenden Sie modStaticResource, um auf statische Dateien zu verlinken, oder modDocument, um den Inhalt in die Datenbank zu kopieren.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Fehlgeschlagen!</span>';
$_lang['import_site_html'] = 'Website aus HTML-Dateien importieren';
$_lang['import_site_importing_document'] = 'Importiere Datei <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Maximale Importdauer:';
$_lang['import_site_maxtime_message'] = 'Hier können Sie die Dauer (in Sekunden) angeben, die das System für einen Import maximal benötigen darf (die PHP-Einstellungen werden dabei außer Kraft gesetzt). Bitte beachten Sie, dass von der Angabe von 0 Sekunden oder einer extrem hohen Zeitspanne dringend abgeraten wird, da dies zu Komplikationen mit Ihrem Webserver führen kann.';
$_lang['import_site_message'] = '<p>Mit dieser Funktion können Sie den Inhalt mehrerer HTML-Dateien in die Datenbank importieren. <em>Bitte beachten Sie, dass Sie Ihre Dateien und/oder Verzeichnisse zuvor in das Verzeichnis core/import/ kopieren müssen.</em></p><p>Bitte füllen Sie das nachfolgende Formular aus, wählen Sie optional aus dem Ressourcen-Baum eine Eltern-Ressource für die importierten Dateien aus und klicken Sie auf "HTML importieren", um den Import zu starten. Die importierten Dateien werden am angegebenen Ort gespeichert, wobei, wenn möglich, der Dateiname als Alias des Dokuments verwendet wird und der HTML-Seitentitel als Titel des Dokuments.</p>';
$_lang['import_site_resource'] = 'Ressourcen aus statischen Dateien importieren';
$_lang['import_site_resource_message'] = '<p>Mit dieser Funktion können Sie Ressourcen aus (ggf. mehreren) statischen Dateien in die Datenbank importieren. <em>Bitte beachten Sie, dass Sie Ihre Dateien und/oder Ordner zuvor in den Ordner core/import/ kopieren müssen.</em></p><p>Bitte füllen Sie das nachfolgende Formular aus, wählen Sie optional aus dem Ressourcen-Baum eine Eltern-Ressource für die importierten Dateien aus und klicken Sie auf "Ressourcen importieren", um den Import zu starten. Die importierten Dateien werden am angegebenen Ort gespeichert, wobei, wenn möglich, der Dateiname als Alias des Dokuments verwendet wird und, wenn es sich um HTML-Dateien handelt, der HTML-Seitentitel als Titel des Dokuments.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Ausgelassen!</span>';
$_lang['import_site_start'] = 'Import starten';
$_lang['import_site_success'] = '<span style="color:#009900">Erfolgreich!</span>';
$_lang['import_site_time'] = 'Import beendet. Es wurden %s Sekunden für den Import benötigt.';
$_lang['import_use_doc_tree'] = 'Verwenden Sie den unten stehenden Ressourcen-Baum, um die Eltern-Ressource auszuwählen, in die Ihre Dateien importiert werden sollen.';
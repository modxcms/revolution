<?php
/**
 * Export German lexicon topic
 *
 * @package modx
 * @subpackage lexicon
 *
 * @language de
 * @namespace core
 * @topic export
 *
 * MODX Revolution translated to German by Jan-Christoph Ihrens (enigmatic_user, enigma@lunamail.de)
 */
$_lang['export_site_cacheable'] = 'Inklusive nicht gecachter Dateien:';
$_lang['export_site_exporting_document'] = 'Exportiere Datei <strong>%s</strong> von <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, ID %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Fehlgeschlagen!</span>';
$_lang['export_site_html'] = 'Website im HTML-Dateien exportieren';
$_lang['export_site_maxtime'] = 'Maximale Exportdauer:';
$_lang['export_site_maxtime_message'] = 'Hier können Sie die Dauer (in Sekunden) angeben, die das System für einen Export der Site maximal benötigen darf (die PHP-Einstellungen werden dabei außer Kraft gesetzt). Geben Sie 0 für unbegrenzte Zeit ein. Bitte beachten Sie, dass von der Angabe von 0 Sekunden oder einer extrem hohen Zeitspanne dringend abgeraten wird, da dies zu Komplikationen mit Ihrem Webserver führen kann.';
$_lang['export_site_message'] = '<p>Mit dieser Funktion können Sie die gesamte Site in HTML-Dateien exportieren. Beachten Sie bitte, dass Sie viel von der Funktionalität von MODX einbüßen, wenn Sie dies tun:</p><ul><li>Seitenzugriffe auf die exportierten Dateien werden nicht aufgezeichnet.</li><li>Interaktive Snippets funktionieren NICHT in exportierten Dateien.</li><li>Nur reguläre Dokumente werden exportiert, Weblinks nicht.</li><li>Der Export kann fehlschlagen, wenn Ihre Dokumente Snippets enthalten, die eine Header-Weiterleitung vornehmen.</li><li>Abhängig davon, wie Sie Ihre Dokumente, Stylesheets und Bilder miteinander verknüpft haben, könnte das Design Ihrer Site zerstört werden. Um dieses Problem zu lösen, können Sie die exportierten Dateien in das gleiche Verzeichnis speichern bzw. kopieren, in dem die Datei index.php liegt.</li></ul><p>Bitte füllen Sie das Formular aus und Klicken Sie auf "Exportieren", um den Export zu starten. Die generierten Dateien werden an dem von Ihnen angegebenen Ort gespeichert, wobei, soweit möglich, der Alias des Dokuments als Dateiname verwendet wird. Es ist ratsam, die MODX-Einstellung "Suchmaschinenfreundliche Aliasse" für den Export auf "Ja" zu setzen. Abhängig von der Größe Ihrer Site kann der Export eine Weile dauern.</p><p><em>Alle bereits vorhandenen Dateien werden bei Namensgleichheit überschrieben!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>%s Dokumente zum Exportieren gefunden...</strong></p>';
$_lang['export_site_prefix'] = 'Datei-Präfix:';
$_lang['export_site_start'] = 'Export starten';
$_lang['export_site_success'] = '<span style="color:#009900">Erfolgreich!</span>';
$_lang['export_site_suffix'] = 'Datei-Suffix:';
$_lang['export_site_target_unwritable'] = 'Das Zielverzeichnis ist nicht beschreibbar. Bitte stellen Sie sicher, dass das Verzeichnis beschreibbar ist, und versuchen Sie es erneut.';
$_lang['export_site_time'] = 'Export beendet. Es wurden %s Sekunden für den Export benötigt.';

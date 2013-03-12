<?php
/**
 * Config Check German lexicon topic
 *
 * @package modx
 * @subpackage lexicon
 *
 * @language de
 * @namespace core
 * @topic configcheck
 *
 * MODX Revolution translated to German by Jan-Christoph Ihrens (enigmatic_user, enigma@lunamail.de)
 */
$_lang['configcheck_admin'] = 'Bitte kontaktieren Sie einen Systemadministrator und setzen Sie ihn über diese Nachricht in Kenntnis!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'Kontext-Einstellung allow_tags_in_post außerhalb von "mgr" aktiviert';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Die Kontext-Einstellung allow_tags_in_post ist in Ihrer Installation außerhalb des mgr-Kontexts aktiviert. MODX empfiehlt, diese Einstellung zu deaktivieren, wenn Sie nicht ausdrücklich Benutzern erlauben müssen, MODX-Tags, numerische HTML-Entities (z.B. &amp;#174; für ®) oder HTML-Script-Tags mittels POST an eine Seite Ihrer Site zu übertragen. Dies sollte grundsätzlich deaktiviert sein, außer im mgr-Kontext.';  // an ein Formular in Ihrer Site???
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'Systemeinstellung allow_tags_in_post aktiviert';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Die Systemeinstellung allow_tags_in_post in Ihrer Installation aktiviert. MODX empfiehlt, diese Einstellung zu deaktivieren, wenn Sie nicht ausdrücklich Benutzern erlauben müssen, MODX-Tags, numerische HTML-Entities (z.B. &amp;#174; für ®) oder HTML-Script-Tags mittels POST an eine Seite Ihrer Site zu übertragen. Es ist besser, dies mittels Kontext-Einstellungen für bestimmte Kontexte zu aktivieren.';  // an ein Formular in Ihrer Site???
$_lang['configcheck_cache'] = 'Cache-Verzeichnis nicht beschreibbar';
$_lang['configcheck_cache_msg'] = 'MODX kann nicht in das Cache-Verzeichnis schreiben. MODX wird trotzdem wie erwartet funktionieren, aber es findet kein Caching statt. Um dies zu beheben, sorgen Sie dafür, dass das Verzeichnis /_cache/ beschreibbar ist.';
$_lang['configcheck_configinc'] = 'Config-Datei ist noch beschreibbar!';
$_lang['configcheck_configinc_msg'] = 'Ihre Site ist anfällig für Hacker-Angriffe, die einigen Schaden auf Ihrer Site anrichten könnten. Bitte sorgen Sie dafür, dass Ihre Config-Datei schreibgeschützt ist! Wenn Sie nicht der Site-Admin sind, kontaktieren Sie bitte einen Systemadministrator und setzen Sie ihn über diese Nachricht in Kenntnis! Der Pfad zur Config-Datei lautet [[+path]]';
$_lang['configcheck_default_msg'] = 'Eine unspezifizierte Warnung wurde übermittelt. Was merkwürdig ist.';
$_lang['configcheck_errorpage_unavailable'] = 'Die Fehlerseite Ihrer Site ist nicht verfügbar.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Dies bedeutet, dass Ihre Fehlerseite für normale Websurfer nicht zugänglich ist oder nicht existiert. Dies kann zu einer Endlosschleife und zu vielen Fehlermeldungen in Ihrem Fehler-Log führen. Stellen Sie sicher, dass der Seite keine Benutzergruppen zugeordnet sind.';
$_lang['configcheck_errorpage_unpublished'] = 'Die Fehlerseite Ihrer Site wurde nicht veröffentlicht oder existiert nicht.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Dies bedeutet, dass Ihre Fehlerseite der Öffentlichkeit nicht zugänglich ist. Veröffentlichen Sie die Seite oder stellen Sie sicher, dass sie unter "System &gt; Systemeinstellungen" einem existierenden Dokument in Ihrem Ressourcen-Baum zugeordnet ist.';
$_lang['configcheck_images'] = 'Bilderverzeichnis nicht beschreibbar';
$_lang['configcheck_images_msg'] = 'Das Bilderverzeichnis ist nicht beschreibbar oder existiert nicht. Das bedeutet, dass die Bildmanager-Funktionen im Editor nicht funktionieren!';
$_lang['configcheck_installer'] = 'Installationsskript noch vorhanden';
$_lang['configcheck_installer_msg'] = 'Das Verzeichnis setup/ enthält das Installationsskript für MODX. Stellen Sie sich nur einmal vor, was passieren könnte, wenn ein böser Mensch dieses Verzeichnis findet und die Installationsroutine startet! Er würde zwar vermutlich nicht allzu weit kommen, weil er Zugangsdaten für die Datenbank eingeben müsste, aber es ist dennoch am besten, dieses Verzeichnis von Ihrem Server zu löschen. Es befindet sich hier: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Inkorrekte Anzahl von Einträgen in der Sprachdatei';
$_lang['configcheck_lang_difference_msg'] = 'Die momentan ausgewählte Sprache hat eine andere Anzahl von Einträgen als die Standardsprache. Das muss nicht notwendigerweise ein Problem sein, kann aber bedeuten, dass die Sprachdatei aktualisiert werden muss.';
$_lang['configcheck_notok'] = 'Ein oder mehrere Konfigurationsdetails haben die Prüfung nicht bestanden: ';
$_lang['configcheck_ok'] = 'Prüfung bestanden - keine Warnungen.';
$_lang['configcheck_register_globals'] = 'register_globals ist in Ihrer php.ini-Konfigurationsdatei auf ON gesetzt';
$_lang['configcheck_register_globals_msg'] = 'Diese Konfiguration macht Ihre Seite wesentlich anfälliger für Cross-Site-Scripting-Attacken (XSS-Attacken). Sie sollten mit Ihrem Webhoster darüber sprechen, was Sie tun können, um diese Einstellung zu deaktivieren.';
$_lang['configcheck_title'] = 'Konfigurations-Prüfung';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Die zu Ihrer Site gehörende Seite für unautorisierte Zugriffe wurde nicht veröffentlicht oder existiert nicht.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Dies bedeutet, dass Ihre Seite für unautorisierte Zugriffe für normale Websurfer nicht zugänglich ist oder nicht existiert. Dies kann zu einer Endlosschleife und zu vielen Fehlermeldungen in Ihrem Fehler-Log führen. Stellen Sie sicher, dass der Seite keine Benutzergruppen zugeordnet sind.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Die in den Site-Konfigurationseinstellungen definierte Seite für unautorisierte Zugriffe wurde nicht veröffentlicht.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Dies bedeutet, dass Ihre Seite für unautorisierte Zugriffe der Öffentlichkeit nicht zugänglich ist. Veröffentlichen Sie die Seite oder stellen Sie sicher, dass sie unter "System &gt; Systemeinstellungen" einem existierenden Dokument in Ihrem Ressourcen-Baum zugeordnet ist.';
$_lang['configcheck_warning'] = 'Konfigurations-Warnung:';
$_lang['configcheck_what'] = 'Was bedeutet das?';

<?php
/**
 * Setting German lexicon topic
 *
 * @package modx
 * @subpackage lexicon
 *
 * @language de
 * @namespace core
 * @topic setting
 *
 * MODX Revolution translated to German by Jan-Christoph Ihrens (enigmatic_user, enigma@lunamail.de)
 */
$_lang['area'] = 'Bereich';
$_lang['area_authentication'] = 'Authentifizierung und Sicherheit';
$_lang['area_caching'] = 'Caching';
$_lang['area_core'] = 'Core-Code';
$_lang['area_editor'] = 'Rich-Text-Editor';
$_lang['area_file'] = 'Dateisystem';
$_lang['area_filter'] = 'Nach Bereich filtern...';
$_lang['area_furls'] = 'Suchmaschinenfreundliche URLs';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Lexikon und Sprache';
$_lang['area_mail'] = 'E-Mail-Einstellungen';
$_lang['area_manager'] = 'Backend-Manager';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session und Cookies';
$_lang['area_lexicon_string'] = 'Lexikon-Eintrag für den Bereich';
$_lang['area_lexicon_string_msg'] = 'Geben Sie hier den Schlüssel für den Lexikon-Eintrag für den Bereich ein. Wenn es keinen Lexikon-Eintrag gibt, wird einfach der Bereichs-Schlüssel angezeigt.<br />Core-Bereiche: authentication, caching, file, furls, gateway, language, manager, session, site, system';
$_lang['area_site'] = 'Site';
$_lang['area_system'] = 'System und Server';
$_lang['areas'] = 'Bereiche';
$_lang['charset'] = 'Zeichensatz';
$_lang['country'] = 'Land';
$_lang['description_desc'] = 'Eine kurze Beschreibung der Einstellung. Dies kann auch ein Schlüssel eines Lexikon-Eintrags sein.';
$_lang['key_desc'] = 'Der Schlüssel der Einstellung. Er ist in Ihren Inhalten über den [[++key]]-Platzhalter verfügbar.';
$_lang['name_desc'] = 'Ein Name für die Einstellung. Dies kann auch ein Schlüssel eines Lexikon-Eintrags sein.';
$_lang['namespace'] = 'Namensraum';
$_lang['namespace_desc'] = 'Der Namensraum, mit dem diese Einstellung verbunden ist. Das Standard-Lexikon-Thema wird für diesen Namensraum geladen, wenn die Einstellungen eingelesen werden.';
$_lang['namespace_filter'] = 'Nach Namensraum filtern...';
$_lang['search_by_key'] = 'Nach Schlüssel suchen...';
$_lang['setting_create'] = 'Neue Einstellung anlegen';
$_lang['setting_err'] = 'Bitte überprüfen Sie Ihre Daten für die folgenden Felder: ';
$_lang['setting_err_ae'] = 'Eine Einstellung mit diesem Schlüssel existiert bereits. Bitte geben Sie einen anderen Namen für den Schlüssel an.';
$_lang['setting_err_nf'] = 'Einstellung nicht gefunden.';
$_lang['setting_err_ns'] = 'Einstellung nicht angegeben';
$_lang['setting_err_remove'] = 'Ein Fehler trat auf beim Versuch, die Einstellung zu löschen.';
$_lang['setting_err_save'] = 'Ein Fehler trat auf beim Versuch, die Einstellung zu speichern.';
$_lang['setting_err_startint'] = 'Schlüsselnamen von Einstellungen dürfen nicht mit einer Ziffer beginnen.';
$_lang['setting_err_invalid_document'] = 'Es gibt kein Dokument mit der ID %d. Bitte geben Sie ein existierendes Dokument an.';
$_lang['setting_remove'] = 'Einstellung löschen';
$_lang['setting_remove_confirm'] = 'Sind Sie sicher, dass Sie diese Einstellung löschen möchten? Das könnte Ihre MODX-Installation unbrauchbar machen.';
$_lang['setting_update'] = 'Einstellung bearbeiten';
$_lang['settings_after_install'] = 'Da dies eine neue MODX-Installation ist, müssen Sie diese Einstellungen kontrollieren und ggf. einige Ihren Wünschen entsprechend ändern. Nachdem Sie die Einstellungen kontrolliert und ggf. angepasst haben, klicken Sie auf "Speichern", um die Daten in der Datenbank zu aktualisieren.<br /><br />';
$_lang['settings_desc'] = 'Hier können Sie sowohl generelle Konfigurationseinstellungen für die MODX-Manager-Benutzeroberfläche vornehmen als auch festlegen, wie sich Ihre MODX-Website verhält. Doppelklicken Sie über der Einstellung, die Sie ändern möchten, auf die Werte-Spalte, um den Wert dynamisch direkt in der Tabelle zu bearbeiten, oder führen Sie einen Rechtsklick auf einer Einstellung aus, um weitere Optionen angeboten zu bekommen. Sie können, wo vorhanden, auch auf das "+"-Icon klicken, um eine Erläuterung zu der jeweiligen Einstellung zu bekommen.';
$_lang['settings_furls'] = 'Suchmaschinenfreundliche URLs';
$_lang['settings_misc'] = 'Verschiedenes';
$_lang['settings_site'] = 'Site';
$_lang['settings_ui'] = 'Interface &amp; Features';
$_lang['settings_users'] = 'Benutzer';
$_lang['system_settings'] = 'Systemeinstellungen';
$_lang['usergroup'] = 'Benutzergruppe';

// user settings
$_lang['setting_access_category_enabled'] = 'Kategorien-Zugriff prüfen';
$_lang['setting_access_category_enabled_desc'] = 'Verwenden Sie diese Einstellung, um Kategorien-ACL-Checks zu aktivieren oder zu deaktivieren (pro Kontext). <strong>HINWEIS: Wenn diese Option auf "Nein" gesetzt wurde, werden ALLE Kategorien-Zugriffsberechtigungen ignoriert!</strong>';

$_lang['setting_access_context_enabled'] = 'Kontext-Zugriff prüfen';
$_lang['setting_access_context_enabled_desc'] = 'Verwenden Sie diese Einstellung, um Kontext-ACL-Checks zu aktivieren oder zu deaktivieren. <strong>HINWEIS: Wenn diese Option auf "Nein" gesetzt wurde, werden ALLE Kontext-Zugriffsberechtigungen ignoriert. Deaktivieren Sie diese Einstellung NICHT systemweit oder für den mgr-Kontext, da Sie sonst den Zugriff auf die Manager-Oberfläche deaktivieren!</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Ressourcen-Gruppen-Zugriff prüfen';
$_lang['setting_access_resource_group_enabled_desc'] = 'Verwenden Sie diese Einstellung, um Ressourcen-Gruppen-ACL-Checks zu aktivieren oder zu deaktivieren (pro Kontext). <strong>HINWEIS: Wenn diese Option auf "Nein" gesetzt wurde, werden ALLE Ressourcen-Gruppen-Zugriffsberechtigungen ignoriert!</strong>';

$_lang['setting_allow_mgr_access'] = 'Zugriff auf den MODX-Manager';
$_lang['setting_allow_mgr_access_desc'] = 'Verwenden Sie diese Option, um den Zugriff auf die MODX-Manager-Oberfläche zu erlauben oder zu verbieten. <strong>HINWEIS: Wenn diese Einstellung auf "nein" gesetzt ist, werden Benutzer auf die "Startseite für in den Manager eingeloggte Benutzer" oder die "Startseite der Website" weitergeleitet.';

$_lang['setting_failed_login'] = 'Fehlgeschlagene Login-Versuche';
$_lang['setting_failed_login_desc'] = 'Hier können Sie die Anzahl fehlgeschlagener Login-Versuche angeben, die erlaubt sind, bevor der Benutzer geblockt wird.';

$_lang['setting_login_allowed_days'] = 'Wochentagsbeschränkung';
$_lang['setting_login_allowed_days_desc'] = 'Wählen Sie die Wochentage aus, an denen der Benutzer Zugriff haben soll.';

$_lang['setting_login_allowed_ip'] = 'Zugelassene IP-Adresse';
$_lang['setting_login_allowed_ip_desc'] = 'Geben Sie die IP-Adressen an, von denen aus sich dieser Benutzer einloggen darf. <strong>HINWEIS: Trennen Sie mehrere IP-Adressen mit einem Komma (,).</strong>';

$_lang['setting_login_homepage'] = 'Startseite für eingeloggte Benutzer';
$_lang['setting_login_homepage_desc'] = 'Geben Sie die ID des Dokuments ein, zu dem Sie den Butzer weiterleiten möchten, nachdem er sich eingeloggt hat. <strong>ACHTUNG: Stellen Sie sicher, dass die ID, die Sie eingeben, zu einem existierenden Dokument gehört, dass dieses veröffentlicht wurde und dass der Benutzer Zugriff darauf hat!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Zugriffs-Richtlinien-Schema-Version';
$_lang['setting_access_policies_version_desc'] = 'Die Version des Zugriffs-Richtlinien-Systems. BITTE NICHT ÄNDERN!';

$_lang['setting_allow_forward_across_contexts'] = 'Weiterleitungen in andere Kontexte erlauben';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, können Symlinks und modX::sendForward()-API-Aufrufe Requests zu Ressourcen in anderen Kontexten weiterleiten.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Passwort-vergessen-Funktion auf Manager-Login-Seite zulassen';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Wenn Sie diese Einstellung auf "Nein" setzen, wird die Möglichkeit, sich ein neues Passwort zuschicken zu lassen, wenn man das bisherige vergessen hat, auf der Login-Seite des Managers deaktiviert.';

$_lang['setting_allow_tags_in_post'] = 'HTML-Tags in POST-Requests erlauben';
$_lang['setting_allow_tags_in_post_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt ist, können POST-Requests HTML-Formular-Tags enthalten.';

$_lang['setting_archive_with'] = 'Erzwinge PCLZip-Archive';
$_lang['setting_archive_with_desc'] = 'Wählen Sie "Ja", um PCLZip anstatt ZipArchive als ZIP-Extension zu nutzen. Wählen Sie diese Einstellung, falls Sie "extractTo"-Fehler erhalten oder Probleme beim Entpacken in der Package-Verwaltung haben.';

$_lang['setting_auto_menuindex'] = 'Automatische Menü-Indizierung';
$_lang['setting_auto_menuindex_desc'] = 'Wählen Sie "Ja", um die automatische Menü-Indizierung einzuschalten. Ist diese aktiv, erhält das als erstes erstellte Dokument in einem Container/Ordner als Menü-Index den Wert 0, und dieser Wert wird dann für jedes nachfolgende Dokument, das Sie erstellen, erhöht.';

$_lang['setting_auto_check_pkg_updates'] = 'Automatische Suche nach Package-Updates';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, sucht MODX in der Package-Verwaltung automatisch nach Updates für Packages. Dies kann die Anzeige der Tabelle verlangsamen.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Cache-Ablaufzeit für die automatische Package-Updates-Überprüfung';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Die Anzahl der Minuten, für die die Package-Verwaltung die Ergebnisse der Package-Updates-Überprüfung cacht.';

$_lang['setting_allow_multiple_emails'] = 'E-Mail-Adressen-Duplikate für Benutzer erlauben';
$_lang['setting_allow_multiple_emails_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, dürfen mehrere Benutzer die selbe E-Mail-Adresse verwenden.';

$_lang['setting_automatic_alias'] = 'Alias automatisch generieren';
$_lang['setting_automatic_alias_desc'] = 'Wählen Sie "Ja", wenn das System beim Speichern automatisch einen auf dem Seitentitel der Ressource basierenden Alias generieren soll.';

$_lang['setting_base_help_url'] = 'Basis-URL der Hilfe';
$_lang['setting_base_help_url_desc'] = 'Die Basis-URL für die Hilfe-Links oben rechts auf den Seiten im Manager.';

$_lang['setting_blocked_minutes'] = 'Anzahl Minuten für Sperrung';
$_lang['setting_blocked_minutes_desc'] = 'Hier können Sie die Anzahl der Minuten eingeben, für die ein Benutzer geblockt wird, wenn er die maximal erlaubte Anzahl an fehlgeschlagenen Login-Versuchen erreicht hat. Bitte geben Sie hier nur ganze Zahlen ein (keine Kommata, Leerzeichen etc.)';

$_lang['setting_cache_action_map'] = 'Aktionen-Cache aktivieren';
$_lang['setting_cache_action_map_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, werden Aktionen (bzw. Controller-Maps) gecacht, um die Ladezeiten von Manager-Seiten zu reduzieren.';

$_lang['setting_cache_context_settings'] = 'Kontext-Einstellungen-Cache aktivieren';
$_lang['setting_cache_context_settings_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, werden Kontext-Einstellungen gecacht, um die Ladezeiten zu verringern.';

$_lang['setting_cache_db'] = 'Datenbank-Cache aktivieren';
$_lang['setting_cache_db_desc'] = 'Wenn diese Option aktiviert ist, werden Objekte und Ergebnisse von SQL-Abfragen gecacht, um die Datenbank-Last signifikant zu reduzieren.';

$_lang['setting_cache_db_expires'] = 'Ablaufzeit für Datenbank-Cache';
$_lang['setting_cache_db_expires_desc'] = 'Standardzeit für das Ablaufen des Datenbank-Caches. Wird diese Einstellung auf"0" gesetzt, läuft der Cache niemals ab, wenn nicht ein Datensatz aktualisiert (geändert) wird.';

$_lang['setting_cache_db_session'] = 'Datenbank-Session-Cache aktivieren';
$_lang['setting_cache_db_session_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird und cache_db aktiviert ist, werden Datenbank-Sessions im DB-Result-Set-Cache gecacht.';

$_lang['setting_cache_db_session_lifetime'] = 'Ablaufzeit für DB-Session-Cache';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Dieser Wert (in Sekunden) setzt den Zeitraum fest, innerhalb dessen Cache-Dateien für Session-Einträge im DB-Result-Set-Cache gültig sind.';

$_lang['setting_cache_default'] = 'Voreinstellung für Cache';
$_lang['setting_cache_default_desc'] = 'Wählen Sie "Ja", um für alle neuen Ressourcen standardmäßig den Cache zu aktivieren.';
$_lang['setting_cache_default_err'] = 'Bitte geben Sie an, ob Dokumente standardmäßig gecacht werden sollen oder nicht.';

$_lang['setting_cache_disabled'] = 'Globale Cache-Optionen deaktivieren';
$_lang['setting_cache_disabled_desc'] = 'Wählen Sie "Ja", um alle MODX-Caching-Features zu deaktivieren.';
$_lang['setting_cache_disabled_err'] = 'Bitte geben Sie an, ob der Cache aktiviert werden soll oder nicht.';

$_lang['setting_cache_expires'] = 'Ablaufzeit für den Standard-Cache';
$_lang['setting_cache_expires_desc'] = 'Dieser Wert (in Sekunden) legt fest, wie lange Cache-Dateien des Standard-Caches gültig sind. Der Wert "0" bedeutet, dass der Cache niemals abläuft.';

$_lang['setting_cache_format'] = 'Zu verwendendes Cache-Format';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serialisiert. Bitte wählen Sie eines dieser Formate.';

$_lang['setting_cache_handler'] = 'Caching-Handler-Klasse';
$_lang['setting_cache_handler_desc'] = 'Der Klassenname des Type-Handlers, der für das Caching genutzt werden soll.';

$_lang['setting_cache_lang_js'] = 'Lexikon-JavaScript-Zeichenketten cachen';
$_lang['setting_cache_lang_js_desc'] = 'Wenn diese Option auf "Ja" gesetzt ist, werden Server-Header verwendet, um die ins JavaScript geladenen Lexikon-Zeichenketten für die Manager-Oberfläche zu cachen.';

$_lang['setting_cache_lexicon_topics'] = 'Lexikon-Themen cachen';
$_lang['setting_cache_lexicon_topics_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, werden alle Lexikon-Themen gecacht, wodurch die Ladezeiten für die Internationalisierungs-Funktionalität drastisch reduziert werden. Es wird dringend empfohlen, diese Einstellung auf "Ja" zu belassen.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Nicht zum Core-Namensraum gehörende Lexikon-Themen cachen';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Wenn diese Einstellung deaktiviert ist, werden nicht zum Core-Namensraum gehörende Lexikon-Themen nicht gecacht. Es ist nützlich, dies zu deaktivieren, wenn Sie Ihre eigenen Extras entwickeln.';

$_lang['setting_cache_resource'] = 'Partiellen Ressourcen-Cache aktivieren';
$_lang['setting_cache_resource_desc'] = 'Partielles Ressourcen-Caching kann für jede Ressource einzeln konfiguriert werden, wenn dieses Feature aktiviert ist. Das Deaktivieren dieses Features deaktiviert es global.';

$_lang['setting_cache_resource_expires'] = 'Ablaufzeit für den partiellen Ressourcen-Cache';
$_lang['setting_cache_resource_expires_desc'] = 'Ablaufzeit für den partiellen Ressourcen-Cache. Der Wert "0" bedeutet, dass der Cache niemals abläuft.';

$_lang['setting_cache_scripts'] = 'Skript-Cache aktivieren';
$_lang['setting_cache_scripts_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, cacht MODX alle Skripte (Snippets und Plugins) in Dateien, um die Ladezeiten zu verringern. Es wird empfohlen, diese Einstellung auf "Ja" zu belassen.';

$_lang['setting_cache_system_settings'] = 'Systemeinstellungen-Cache aktivieren';
$_lang['setting_cache_system_settings_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, werden die Systemeinstellungen gecacht, um die Ladezeiten zu verringern. Es wird empfohlen, diese Einstellung auf "Ja" zu belassen.';

$_lang['setting_clear_cache_refresh_trees'] = 'Aktualisiere Bäume, wenn Site-Cache geleert wird';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Wenn diese Einstellung aktiviert ist, werden die Ressourcen-, Element- und Dateibäume aktualisiert, wenn der Site-Cache geleert wird.';

$_lang['setting_compress_css'] = 'Komprimiertes CSS verwenden';
$_lang['setting_compress_css_desc'] = 'Wenn diese Option aktiviert ist, verwendet MODX eine komprimierte Version seiner CSS-Stylesheets in der Manager-Oberfläche. Dadurch werden die Lade- und Ausführungszeiten im Manager deutlich reduziert. Deaktivieren Sie diese Einstellung nur, wenn Sie Core-Elemente modifizieren. Achtung: Funktioniert nicht in via Git heruntergeladenen Installationen - in diesen bitte auf "Nein" lassen!';

$_lang['setting_compress_js'] = 'Komprimierte JavaScript-Bibliotheken verwenden';
$_lang['setting_compress_js_desc'] = 'Wenn dies aktiviert ist, benutzt MODX eine komprimierte Version seiner JavaScript-Bibliotheken. Dies reduziert Last und Ausführungszeit. Deaktivieren Sie diese Einstellung nur, wenn Sie Core-Elemente modifizieren. Achtung: Funktioniert nicht in via Git heruntergeladenen Installationen - in diesen bitte auf "Nein" lassen!';

$_lang['setting_compress_js_groups'] = 'Gruppieren nutzen, wenn JavaScript komprimiert wird';
$_lang['setting_compress_js_groups_desc'] = 'Die Core-JavaScripts des MODX-Managers gruppieren durch Benutzung der groupsConfig-Funktion von minify. Setzen Sie diese Einstellung auf "Ja", wenn Sie Suhosin einsetzen oder sich andere einschränkende Faktoren auswirken.';

$_lang['setting_compress_js_max_files'] = 'Maximale Anzahl komprimierter JavaScript-Dateien';
$_lang['setting_compress_js_max_files_desc'] = 'Die maximale Anzahl an JavaScript-Dateien, die MODX gleichzeitig zu komprimieren versucht, wenn compress_js eingeschaltet ist. Setzen Sie diese Einstellung auf einen niedrigeren Wert, wenn Sie im Manager Probleme mit Google Minify haben.';

$_lang['setting_concat_js'] = 'Verknüpfte Javascript-Bibliotheken verwenden';
$_lang['setting_concat_js_desc'] = 'Wenn diese Option aktiviert ist, verwendet MODX eine verknüpfte Version seiner meistverwendeten JavaScript-Bibliotheken in der Manager-Oberfläche; diese werden dann als eine einzige Datei ausgeliefert. Dadurch werden die Lade- und Ausführungszeiten im Manager drastisch reduziert. Deaktivieren Sie diese Einstellung nur, wenn Sie Core-Elemente modifizieren. Achtung: Funktioniert nicht in via Git heruntergeladenen Installationen - in diesen bitte auf "Nein" lassen!';

$_lang['setting_container_suffix'] = 'Container-Suffix';
$_lang['setting_container_suffix_desc'] = 'Das Suffix, das Ressourcen, die als Container definiert wurden, hinzugefügt wird, wenn suchmaschinenfreundliche URLs verwendet werden.';

$_lang['setting_context_tree_sort'] = 'Sortierung der Kontexte im Ressourcen-Baum aktivieren';
$_lang['setting_context_tree_sort_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, werden Kontexte im sich auf der linken Seite befindenden Ressourcen-Baum alphanumerisch sortiert.';
$_lang['setting_context_tree_sortby'] = 'Sortierfeld von Kontexten im Ressourcen-Baum';
$_lang['setting_context_tree_sortby_desc'] = 'Das Feld, nach dem Kontexte im Ressourcen-Baum sortiert werden, wenn die Sortierung aktiviert ist.';
$_lang['setting_context_tree_sortdir'] = 'Sortierrichtung von Kontexten im Ressourcen-Baum';
$_lang['setting_context_tree_sortdir_desc'] = 'Gibt an, ob Kontexte im Ressourcen-Baum auf- oder absteigend sortiert werden, wenn die Sortierung aktiviert ist.';

$_lang['setting_cultureKey'] = 'Sprache';
$_lang['setting_cultureKey_desc'] = 'Wählen Sie die Sprache für alle Nicht-Manager-Kontexte, einschließlich des Kontexts "web".';

$_lang['setting_date_timezone'] = 'Standard-Zeitzone';
$_lang['setting_date_timezone_desc'] = 'Gibt die Standard-Zeitzonen-Einstellung für PHP-Datumsfunktionen an, wenn die Einstellung nicht leer gelassen wird. Wird hier nichts eingegeben und die PHP-Konfigurationseinstellung date.timezone (kann mittels php.ini, date_default_timezone_set(), ini_set(), .htaccess etc. eingestellt werden) ist in Ihrer PHP-Umgebung nicht gesetzt, wird UTC vorausgesetzt.';

$_lang['setting_debug'] = 'Debugging-Einstellungen';
$_lang['setting_debug_desc'] = 'Einstellmöglichkeit zum Ein- und Ausschalten des Debugging in MODX und/oder zum Setzen des PHP-error_reporting-Levels. "" = verwende aktuellen error_reporting-Wert, "0" = false (keine Meldungen anzeigen, error_reporting = 0), "1" = true (alle Meldungen anzeigen, error_reporting = -1) oder ein beliebiger gültiger Wert für error_reporting (als Integer-Zahl).';

$_lang['setting_default_content_type'] = 'Standard-Inhaltstyp';
$_lang['setting_default_content_type_desc'] = 'Wählen Sie den Standard-Inhaltstyp, den Sie für neue Ressourcen verwenden möchten. Sie können weiterhin einen anderen Inhaltstyp im Ressourcen-Editor auswählen; mit dieser Einstellung treffen Sie nur eine Vorauswahl für einen der Inhaltstypen.';

$_lang['setting_default_duplicate_publish_option'] = 'Standardmäßige Veröffentlichungs-Option für Ressourcen-Duplikate';
$_lang['setting_default_duplicate_publish_option_desc'] = 'Die standardmäßig gewählte Option, wenn eine Ressource dupliziert wird. Dies kann entweder "unpublish" sein, um alle Duplikate als unveröffentlicht zu markieren, "publish", um alle Duplikate zu veröffentlichen, oder "preserve", um den Veröffentlichungs-Status basierend auf der duplizierten Ressource beizubehalten.';

$_lang['setting_default_media_source'] = 'Standard-Medienquelle';
$_lang['setting_default_media_source_desc'] = 'Die Medienquelle, die standardmäßig verwendet werden soll.';  // geladen

$_lang['setting_default_template'] = 'Standard-Template';
$_lang['setting_default_template_desc'] = 'Wählen Sie das Standard-Template, das Sie für neue Ressourcen verwenden möchten. Sie können weiterhin ein anderes Template im Ressourcen-Editor auswählen; diese Einstellung sorgt nur dafür, dass eines Ihrer Templates für Sie vorausgewählt wird.';

$_lang['setting_default_per_page'] = 'Standardanzahl der Einträge pro Seite';
$_lang['setting_default_per_page_desc'] = 'Standardanzahl der Einträge pro Seite in den Tabellen im gesamten Manager.';

$_lang['setting_editor_css_path'] = 'Pfad zur CSS-Datei';
$_lang['setting_editor_css_path_desc'] = 'Geben Sie den Pfad zu Ihrer CSS-Datei ein, die Sie im Editor benutzen möchten. Der beste Weg, den Pfad anzugeben, ist, den Pfad vom Server-Root aus einzugeben, z.B.: /assets/site/style.css. Wenn Sie kein Stylesheet in den Editor laden möchten, lassen Sie dieses Feld leer.';

$_lang['setting_editor_css_selectors'] = 'CSS-Selektoren für den Editor';
$_lang['setting_editor_css_selectors_desc'] = 'Eine kommaseparierte Liste von CSS-Selektoren für einen Rich-Text-Editor.';

$_lang['setting_emailsender'] = 'E-Mail-Adresse';
$_lang['setting_emailsender_desc'] = 'Hier können Sie die E-Mail-Adresse angeben, die verwendet wird, wenn Benutzern ihre Benutzernamen und Passwörter zugeschickt werden.';
$_lang['setting_emailsender_err'] = 'Bitte geben Sie die Administrations-E-Mail-Adresse an.';

$_lang['setting_emailsubject'] = 'E-Mail-Betreff';
$_lang['setting_emailsubject_desc'] = 'Die Betreffzeile für die E-Mail, die standardmäßig nach Erstellung eines Accounts versendet wird.';
$_lang['setting_emailsubject_err'] = 'Bitte geben Sie die Betreffzeile für die E-Mail für neu erstellte Accounts an.';

$_lang['setting_enable_dragdrop'] = 'Drag & Drop in Ressourcen-/Element-Bäumen aktivieren';
$_lang['setting_enable_dragdrop_desc'] = 'Wenn diese Einstellung auf "Nein" gesetzt wird, ist Drag & Drop in Ressourcen- und Element-Bäumen nicht möglich.';

$_lang['setting_error_page'] = 'Fehlerseite';
$_lang['setting_error_page_desc'] = 'Geben Sie die ID des Dokuments ein, das Benutzern angezeigt werden soll, wenn sie ein Dokument aufrufen, das nicht existiert. <strong>ACHTUNG: Stellen Sie sicher, dass die ID, die Sie eingeben, zu einem existierenden Dokument gehört und dass dieses veröffentlicht wurde!</strong>';
$_lang['setting_error_page_err'] = 'Bitte geben Sie eine Ressourcen-ID für die Fehlerseite an.';

$_lang['setting_extension_packages'] = 'Erweiterungs-Packages';
$_lang['setting_extension_packages_desc'] = 'Ein JSON-Array von Packages, die bei der MODX-Initialisierung geladen werden sollen. Im Format [{"packagename":{pfad":"pfad/zum/package"},{"weiterespackage":{"pfad":"pfad/zum/anderenpackage"}}]';

$_lang['setting_failed_login_attempts'] = 'Fehlgeschlagene Login-Versuche';
$_lang['setting_failed_login_attempts_desc'] = 'Geben Sie an, wie viele fehlgeschlagene Login-Versuche erlaubt sein sollen, bevor der Benutzer geblockt wird.';

$_lang['setting_fe_editor_lang'] = 'Frontend-Editor-Sprache';
$_lang['setting_fe_editor_lang_desc'] = 'Wählen Sie eine Sprache aus, die im Editor benutzt werden soll, wenn er als Frontent-Editor (also innerhalb der eigentlichen Website) verwendet wird.';

$_lang['setting_feed_modx_news'] = 'URL des MODX-Newsfeeds';
$_lang['setting_feed_modx_news_desc'] = 'Geben Sie die URL des RSS-Feeds für das MODX-News-Fenster im Manager an.';

$_lang['setting_feed_modx_news_enabled'] = 'MODX-Newsfeed aktiviert';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Wenn diese Einstellung auf "Nein" gesetzt wird, wird der Newsfeed auf der Startseite des Managers nicht angezeigt.';

$_lang['setting_feed_modx_security'] = 'URL des MODX-Sicherheitshinweise-Feeds';
$_lang['setting_feed_modx_security_desc'] = 'Geben Sie die URL des RSS-Feeds für das MODX-Sicherheitshinweise-Fenster im Manager an.';

$_lang['setting_feed_modx_security_enabled'] = 'MODX-Sicherheitshinweise-Feed aktiviert';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Wenn diese Einstellung auf "Nein" gesetzt wird, wird der Sicherheitshinweise-Feed auf der Startseite des Managers nicht angezeigt.';

$_lang['setting_filemanager_path'] = 'Dateimanager-Pfad (Verwendung nicht empfohlen)';
$_lang['setting_filemanager_path_desc'] = 'Achtung: Diese Einstellung wird in späteren MODX-Versionen nicht mehr zur Verfügung stehen - bitte nutzen Sie stattdessen Medienquellen. IIS setzt die Einstellung document_root, die vom Dateimanager verwendet wird, um festzulegen, was angezeigt wird, häufig nicht korrekt. Wenn Sie Probleme mit der Benutzung des Dateimanagers haben, stellen Sie sicher, dass dieser Pfad auf den Root Ihrer MODX-Installation zeigt. Der Pfad muss mit einem Slash enden.';

$_lang['setting_filemanager_path_relative'] = 'Ist der Dateimanager-Pfad relativ? (Verwendung nicht empfohlen)';
$_lang['setting_filemanager_path_relative_desc'] = 'Achtung: Diese Einstellung wird in späteren MODX-Versionen nicht mehr zur Verfügung stehen - bitte nutzen Sie stattdessen Medienquellen. Wenn Ihre "filemanager_path"-Einstellung relativ zum MODX-"base_path" ist, setzen Sie diese Einstellung bitte auf "Ja". Wenn Ihr "filemanager_path" außerhalb des Document-Roots liegt, setzen Sie sie auf "Nein".';

$_lang['setting_filemanager_url'] = 'Dateimanager-URL (Verwendung nicht empfohlen)';
$_lang['setting_filemanager_url_desc'] = 'Achtung: Diese Einstellung wird in späteren MODX-Versionen nicht mehr zur Verfügung stehen - bitte nutzen Sie stattdessen Medienquellen. Optional. Verwenden Sie diese Option, wenn Sie eine bestimmte URL angeben möchten, von der aus Sie auf die Dateien im MODX-Dateimanager zuzugreifen (hilfreich, wenn Sie den Dateimanager-Pfad auf einen Pfad außerhalb des MODX-Webroots gesetzt haben). Stellen Sie sicher, dass dies die über das Web erreichbare URL der Dateimanager-Pfad-Einstellung ist. Der Pfad muss mit einem Slash enden. Falls diese Einstellung leer gelassen wird, versucht MODX, sie selbst zu erkennen.';

$_lang['setting_filemanager_url_relative'] = 'Ist die Dateimanager-URL relativ? (Verwendung nicht empfohlen)';
$_lang['setting_filemanager_url_relative_desc'] = 'Achtung: Diese Einstellung wird in späteren MODX-Versionen nicht mehr zur Verfügung stehen - bitte nutzen Sie stattdessen Medienquellen. Wenn Ihre "filemanager_url"-Einstellung relativ zur MODX-"base_url" ist, setzen Sie diese Einstellung bitte auf "Ja". Wenn Ihre "filemanager_url" außerhalb des Webroots liegt, setzen Sie sie auf "Nein".';

$_lang['setting_forgot_login_email'] = 'Login-vergessen-Mail';
$_lang['setting_forgot_login_email_desc'] = 'Das Template für die Mail, die User erhalten, die ihren MODX-Benutzernamen und/oder ihr Passwort vergessen haben.';

$_lang['setting_form_customization_use_all_groups'] = 'Alle Benutzergruppen-Zugehörigkeiten für die Formular-Anpassung nutzen';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, werden für die Formular-Anpassung *alle* Sets für *alle* Benutzergruppen, denen ein Benutzer angehört, genutzt, wenn Formular-Anpassungs-Sets angewendet werden. Anderenfalls wird nur das Set verwendet, das der primären Gruppe des Benutzers zugeordnet ist. Hinweis: Wenn Sie diese Einstellung auf "Ja" setzen, kann es wegen Konflikten zwischen Formular-Anpassungs-Sets zu Problemen kommen.';

$_lang['setting_forward_merge_excludes'] = 'Felder, deren Werte bei Verwendung von Symlinks nicht überschrieben werden sollen';
$_lang['setting_forward_merge_excludes_desc'] = 'Bei Verwendung eines Symlinks werden die Werte in den Feldern der Ziel-Ressource überschrieben von den Werten des Symlinks, die nicht leer sind; verwenden Sie diese kommaseparierte Liste von Ausnahmen, um die angegebenen Felder davor zu bewahren, von den Werten des Symlinks überschrieben zu werden.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Suchmaschinenfreundliche Aliasse in Kleinbuchstaben';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Legt fest, ob nur Kleinbuchstaben in einem Ressourcen-Alias erlaubt sein sollen';

$_lang['setting_friendly_alias_max_length'] = 'Maximale Länge suchmaschinenfreundlicher Aliasse';
$_lang['setting_friendly_alias_max_length_desc'] = 'Ist dieser Wert größer als null, gibt er die maximale Anzahl an Zeichen an, die in einem Ressourcen-Alias erlaubt sind. Ist er null, so ist die Alias-Länge nicht begrenzt.';

$_lang['setting_friendly_alias_restrict_chars'] = 'Suchmaschinenfreundliche Aliasse: Methode zur Einschränkung der erlaubten Zeichen';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'Die Methode, die zur Einschränkung der in einem Ressourcen-Alias erlaubten Zeichen verwendet wird. "pattern" erlaubt die in einem separat anzugebenden regulären Ausdruck festgelegten Zeichen, "legal" erlaubt alle in einer URL zulässigen Zeichen, "alpha" erlaubt nur die Buchstaben des Alphabets und "alphanumeric" erlaubt nur Buchstaben und Ziffern.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Suchmaschinenfreundliche Aliasse: RegEx zur Einschränkung der erlaubten Zeichen';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Ein gültiger regulärer Ausdruck zur Einschränkung der in einem Ressourcen-Alias erlaubten Zeichen.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Suchmaschinenfreundliche Aliasse: Element-Tags entfernen';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Gibt an, ob Element-Tags aus Ressourcen-Aliassen entfernt werden sollen.';

$_lang['setting_friendly_alias_translit'] = 'Transliteration suchmaschinenfreundlicher Aliasse';
$_lang['setting_friendly_alias_translit_desc'] = 'Die Transliterations-Methode, die auf einen für eine Ressource angegebenen Alias angewendet werden soll. Standardmäßig ist diese Enstellung leer oder enthält den Wert "none"; dann findet keine Transliteration statt. Andere mögliche Werte sind "iconv" (falls verfügbar) oder der Name einer Transliterations-Tabelle, die von einer benutzerdefinierten Transliterations-Service-Klasse zur Verfügung gestellt wird.';

$_lang['setting_friendly_alias_translit_class'] = 'Suchmaschinenfreundliche Aliasse: Transliterations-Service-Klasse';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Eine optionale Service-Klasse, die benannte Transliterations-Dienste für die Generierung/Filterung suchmaschinenfreundlicher Aliasse zur Verfügung stellt.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Suchmaschinenfreundliche Aliasse: Pfad zur Transliterations-Service-Klasse';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'Der Pfad zum Model-Package, aus dem die Transliterations-Service-Klasse für suchmaschinenfreundliche Aliasse geladen wird.';

$_lang['setting_friendly_alias_trim_chars'] = 'Suchmaschinenfreundliche Aliasse: abzuschneidende Zeichen';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Zeichen, die am Ende eines übergebenen Ressourcen-Alias abgeschnitten werden sollen.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Suchmaschinenfreundliche Aliasse: bevorzugtes Wort-Trennzeichen';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Das bevorzugte Wort-Trennzeichen für suchmaschinenfreundliche Aliasse.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Suchmaschinenfreundliche Aliasse: mögliche Wort-Trennzeichen';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Zeichen, die Wort-Trennzeichen repräsentieren, wenn suchmaschinenfreundliche Aliasse verarbeitet werden. Diese Zeichen werden konvertiert und konsolidiert zu dem bevorzugten Wort-Trennzeichen für suchmaschinenfreundliche Aliasse.';

$_lang['setting_friendly_urls'] = 'Suchmaschinenfreundliche URLs verwenden';
$_lang['setting_friendly_urls_desc'] = 'Dies erlaubt Ihnen, suchmaschinenfreundliche URLs mit MODX zu verwenden. Bitte beachten Sie, dass dies nur für MODX-Installationen gilt, die auf einem Apache-Webserver laufen, und dass Sie eine .htaccess-Datei schreiben müssen, damit dies funktioniert. Mehr Informationen finden Sie in der .htaccess-Datei, die in der MODX-Distribution enthalten ist.';
$_lang['setting_friendly_urls_err'] = 'Bitte geben Sie an, ob Sie suchmaschinenfreundliche URLs verwenden möchten oder nicht.';

$_lang['setting_friendly_urls_strict'] = 'Strikte suchmaschinenfreundliche URLs verwenden';
$_lang['setting_friendly_urls_strict_desc'] = 'Wenn suchmaschinenfreundliche URLs aktiviert sind, bewirkt diese Option, dass nicht-kanonische Requests, die zu einer Ressource passen, mit dem Statuscode 301 zur kanonischen URL für diese Ressource weitergeleitet werden. WARNUNG: Aktivieren Sie diese Option nicht, wenn Sie eigene Weiterleitungsregeln verwenden, deren Weiterleitungsziel nicht zumindest mit dem Anfang der kanonischen URL übereinstimmt. Beispiel: Eine kanonische URL foo/ und eigene Weiterleitungen zu foo/bar.html würden funktionieren, aber Versuche, bei einer kanonischen URL bar/foo.html zu foo/ weiterzuleiten, würden eine Weiterleitung zu foo/ statt zu bar/foo.html erzwingen, wenn diese Option aktiviert ist.';

$_lang['setting_global_duplicate_uri_check'] = 'In allen Kontexten nach doppelten URIs suchen';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Wählen Sie "Ja", wenn bei der Überprüfung auf doppelte URIs alle Kontexte berücksichtigt werden sollen. Anderenfalls wird nur der Kontext, in dem die Ressource gespeichert wird, überprüft.';

$_lang['setting_hidemenu_default'] = 'Standardeinstellung für Option "nicht in Menüs anzeigen"';
$_lang['setting_hidemenu_default_desc'] = 'Wählen Sie "Ja", wenn alle neuen Ressourcen standardmäßig nicht in Menüs angezeigt werden sollen.';

$_lang['setting_inline_help'] = 'Inline-Erläuterungstexte für Felder anzeigen';
$_lang['setting_inline_help_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, werden die Erläuterungstexte der Eingabefelder direkt unter den jeweiligen Feldern angezeigt. Wird "Nein" gewählt, so erhalten alle Felder stattdessen Tooltipp-basierte Erläuterungstexte.';

$_lang['setting_link_tag_scheme'] = 'URL-Generierungs-Schema';
$_lang['setting_link_tag_scheme_desc'] = 'URL-Generierungs-Schema für das Tag [[~id]]. Mögliche Optionen: siehe <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\modX::makeUrl()">hier</a>.';

$_lang['setting_locale'] = 'Locale';
$_lang['setting_locale_desc'] = 'Setzen Sie die Locale-Einstellung für das System. Lassen Sie das Feld leer, wenn die Standardeinstellung verwendet werden soll. Konsultieren Sie <a href="http://de.php.net/setlocale" target="_blank">die PHP-Dokumentation</a>, wenn Sie weitere Informationen benötigen.';

$_lang['setting_lock_ttl'] = 'Dauer der Sperre';
$_lang['setting_lock_ttl_desc'] = 'Die Anzahl der Sekunden, für die die Sperre einer Ressource bestehen bleibt, wenn der Benutzer inaktiv ist.';

$_lang['setting_log_level'] = 'Logging-Level';
$_lang['setting_log_level_desc'] = 'Der Standard-Logging-Level; je niedriger der Level, desto weniger Einträge werden geloggt. Verfügbare Optionen: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO) und 4 (DEBUG).';

$_lang['setting_log_target'] = 'Logging-Ziel';
$_lang['setting_log_target_desc'] = 'Das Standard-Logging-Ziel; gibt an, wohin Log-Einträge geschrieben werden. Verfügbare Optionen: "FILE", "HTML" und "ECHO". Standard ist "FILE", wenn nichts anderes angegeben wurde.';

$_lang['setting_mail_charset'] = 'E-Mail-Zeichensatz';
$_lang['setting_mail_charset_desc'] = 'Legt den Standard-Zeichensatz (charset) für E-Mails fest, z.B. "iso-8859-1" oder "utf-8"';

$_lang['setting_mail_encoding'] = 'E-Mail-Codierung';
$_lang['setting_mail_encoding_desc'] = 'Legt die Codierung für die Nachricht fest. Optionen hierfür sind "8bit", "7bit", "binary", "base64" und "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'SMTP verwenden';
$_lang['setting_mail_use_smtp_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, wird MODX versuchen, SMTP in Mail-Funktionen zu verwenden.';

$_lang['setting_mail_smtp_auth'] = 'SMTP-Authentifizierung';
$_lang['setting_mail_smtp_auth_desc'] = 'Legt fest, ob eine SMTP-Authentifizierung stattfindet. Verwendet die Einstellungen mail_smtp_user und mail_smtp_pass.';

$_lang['setting_mail_smtp_helo'] = 'SMTP-HELO-Nachricht';
$_lang['setting_mail_smtp_helo_desc'] = 'Legt die SMTP-HELO-Nachricht fest (wird hier nichts eingetragen, so wird standardmäßig der Hostname des SMTP-Servers verwendet).';

$_lang['setting_mail_smtp_hosts'] = 'SMTP-Server';
$_lang['setting_mail_smtp_hosts_desc'] = 'Legt die SMTP-Server fest. Werden mehrere Server eingetragen, so müssen diese durch Semikola getrennt werden. Sie können auch für jeden Server einen abweichenden Port angeben, indem Sie folgendes Format verwenden: [servername:port] (z.B. "smtp1.example.com:25;smtp2.example.com"). Die Server werden in der angegebenen Reihenfolge ausprobiert.';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP-Keep-Alive';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Verhindert, dass die SMTP-Verbindung nach dem Senden jeder Mail beendet wird. Nicht empfohlen.';

$_lang['setting_mail_smtp_pass'] = 'SMTP-Passwort';
$_lang['setting_mail_smtp_pass_desc'] = 'Das Passwort zur Authentifizierung beim SMTP-Server.';

$_lang['setting_mail_smtp_port'] = 'SMTP-Port';
$_lang['setting_mail_smtp_port_desc'] = 'Legt den Standard-SMTP-Port fest.';

$_lang['setting_mail_smtp_prefix'] = 'SMTP-Verbindungs-Präfix';
$_lang['setting_mail_smtp_prefix_desc'] = 'Legt den Verbindungs-Präfix fest. Möglich sind "", "ssl" oder "tls"';

$_lang['setting_mail_smtp_single_to'] = 'SMTP: Mails einzeln versenden';
$_lang['setting_mail_smtp_single_to_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, werden Mails an jeden Empfänger einzeln versendet, anderenfalls wird eine einzige Mail versendet, bei der alle Empfänger im entsprechenden Adressfeld stehen.';

$_lang['setting_mail_smtp_timeout'] = 'SMTP-Timeout';
$_lang['setting_mail_smtp_timeout_desc'] = 'Legt den SMTP-Server-Timeout in Sekunden fest. Dies funktioniert nicht auf Win32-Servern.';

$_lang['setting_mail_smtp_user'] = 'SMTP-Benutzername';
$_lang['setting_mail_smtp_user_desc'] = 'Der Benutzername zur Authentifizierung beim SMTP-Server.';

$_lang['setting_manager_direction'] = 'Textrichtung im MODX-Manager';
$_lang['setting_manager_direction_desc'] = 'Geben Sie an, ob der Text im MODX-Manager von links nach rechts (Eingabe: "ltr") oder von rechts nach links (Eingabe: "rtl") ausgegeben werden soll.';

$_lang['setting_manager_date_format'] = 'Manager-Datumsformat';
$_lang['setting_manager_date_format_desc'] = 'Das Format für Datumsangaben im Manager. Diese Einstellung ist im gleichen Format vorzunehmen, wie es die PHP-Funktion <a href="http://de.php.net/manual/en/function.date.php" target="_blank">date()</a> erwartet.';

$_lang['setting_manager_favicon_url'] = 'Manager-Favicon-URL';
$_lang['setting_manager_favicon_url_desc'] = 'Wenn hier etwas eingegeben wird, wird diese URL als favicon für den MODX-Manager geladen. Es muss eine zum Verzeichnis manager/ relative URL oder eine absolute URL eingegeben werden.';

$_lang['setting_manager_html5_cache'] = 'Verwende lokalen HTML5-Cache im Manager';
$_lang['setting_manager_html5_cache_desc'] = 'Experimentell. Aktiviere lokales HTML5-Caching für den Manager. Die Benutzung wird nur empfohlen, wenn der Manager mit Hilfe von modernen Browsern bedient wird.';

$_lang['setting_manager_js_cache_file_locking'] = 'Datei-Sperrung für den Manager-JS/CSS-Cache aktivieren';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Cache-Datei-Sperrung. Setzen Sie diese Einstellung auf "Nein", wenn das Dateisystem NFS ist.';
$_lang['setting_manager_js_cache_max_age'] = 'Cache-Alter der Manager-JS/CSS-Komprimierung';
$_lang['setting_manager_js_cache_max_age_desc'] = 'Maximales Alter des Browser-Caches für die Manager-CSS/JS-Komprimierung in Sekunden. Nach diesem Zeitraum sendet der Browser einen weiteren "Conditional GET Request". Mit einem längeren Zeitraum erreichen Sie geringeren Traffic.';
$_lang['setting_manager_js_document_root'] = 'Document Root für JS-/CSS-Komprimierung im Manager';
$_lang['setting_manager_js_document_root_desc'] = 'Wenn Ihr Server die Server-Variable DOCUMENT_ROOT nicht (oder nicht korrekt) zur Verfügung stellt, setzen Sie sie hier explizit, um die CCS-/JavaScript-Kompression des Managers zu ermöglichen. Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun.';
$_lang['setting_manager_js_zlib_output_compression'] = 'zlib-Output-Komprimierung für Manager-JS/CSS aktivieren';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Gibt an, ob zlib-Output-Komprimierung für komprimiertes CSS/JS im Manager aktiviert wird oder nicht. Aktivieren Sie diese Einstellung nicht, wenn Sie nicht sicher sind, dass die PHP-Konfigurationsvariable zlib.output_compression auf den Wert 1 gesetzt werden kann. MODX empfiehlt, diese Option ausgeschaltet zu lassen.';

$_lang['setting_manager_lang_attribute'] = 'HTML- und XML-Sprach-Attribute im Manager';
$_lang['setting_manager_lang_attribute_desc'] = 'Geben Sie den Code für die Sprache ein, der am besten zu der von Ihnen gewählten Sprache für den MODX-Manager passt. Dies stellt sicher, dass Ihr Browser den Inhalt im am besten für Sie geeigneten Format ausgeben kann.';

$_lang['setting_manager_language'] = 'Manager-Sprache';
$_lang['setting_manager_language_desc'] = 'Wählen Sie die Sprache für den MODX-Content-Manager.';

$_lang['setting_manager_login_url_alternate'] = 'Alternative Manager-Login-URL';
$_lang['setting_manager_login_url_alternate_desc'] = 'Eine alternative URL, zu der ein nicht authentifizierter Benutzer geschickt wird, wenn es nötig ist, dass er sich in den Manager einloggt. Das Login-Formular dort muss den Benutzer in den Kontext "mgr" einloggen, damit dies funktioniert.';

$_lang['setting_manager_login_start'] = 'Startseite für in den Manager eingeloggte Benutzer';
$_lang['setting_manager_login_start_desc'] = 'Geben Sie die ID des Dokuments ein, zu dem Sie den Benutzer weiterleiten möchten, nachdem er sich in den MODX-Manager eingeloggt hat. <strong>ACHTUNG: Stellen Sie sicher, dass die ID, die Sie eingeben, zu einem existierenden Dokument gehört, dass dieses veröffentlicht wurde und dass der Benutzer Zugriff darauf hat!</strong>';

$_lang['setting_manager_theme'] = 'Manager-Theme';
$_lang['setting_manager_theme_desc'] = 'Wählen Sie das Theme für den MODX-Manager.';

$_lang['setting_manager_time_format'] = 'Manager-Zeitformat';
$_lang['setting_manager_time_format_desc'] = 'Das Format für Uhrzeitangaben im Manager. Diese Einstellung ist im gleichen Format vorzunehmen, wie es die PHP-Funktion <a href="http://de.php.net/manual/en/function.date.php" target="_blank">date()</a> erwartet.';

$_lang['setting_manager_use_tabs'] = 'Reiter im Layout des MODX-Managers verwenden';
$_lang['setting_manager_use_tabs_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, so werden Reiter für die Darstellung der Inhalte verwendet. Anderenfalls wird eine Portal-Darstellung verwendet.';

$_lang['setting_manager_week_start'] = 'Wochenanfang';
$_lang['setting_manager_week_start_desc'] = 'Legen Sie den Wochentag fest, mit dem die Woche beginnt. Geben Sie "0" ein (oder lassen Sie das Feld leer) wenn die Woche am Sonntag beginnt, "1", wenn sie am Montag beginnt, und so weiter...';

$_lang['setting_modRequest.class'] = 'Request-Handler-Klasse';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_default_sort'] = 'Datei-Browser-Standard-Sortierung';
$_lang['setting_modx_browser_default_sort_desc'] = 'Das standardmäßige Sortierkriterium bei Benutzung des Popup-Datei-Browsers im Manager. Mögliche Werte sind: name, size, lastmod (Abkürzung für "last modified").';

$_lang['setting_modx_charset'] = 'Zeichencodierung';
$_lang['setting_modx_charset_desc'] = 'Bitte wählen Sie die Zeichencodierung, die Sie verwenden möchten. Bitte beachten Sie, dass MODX zwar mit einigen dieser Codierungen getestet wurde, aber nicht mit allen. Für die meisten Sprachen ist die Standardeinstellung "UTF-8" vorzuziehen.';

$_lang['setting_new_file_permissions'] = 'Dateirechte für neue Dateien';
$_lang['setting_new_file_permissions_desc'] = 'Nach dem Hochladen einer neuen Datei im Dateimanager versucht dieser, die Dateirechte in die zu ändern, die in dieser Einstellung gespeichert sind. Dies könnte in einigen Konfigurationen evtl. nicht funktionieren, z.B. bei Verwendung des IIS-Webservers. In diesem Fall müssen Sie die Rechte selbst ändern.';

$_lang['setting_new_folder_permissions'] = 'Verzeichnisrechte für neue Verzeichnisse';
$_lang['setting_new_folder_permissions_desc'] = 'Nach dem Anlegen eines neuen Ordners im Dateimanager versucht dieser, die Verzeichnisrechte in die zu ändern, die in dieser Einstellung gespeichert sind. Dies könnte in einigen Konfigurationen evtl. nicht funktionieren, z.B. bei Verwendung des IIS-Webservers. In diesem Fall müssen Sie die Rechte selbst ändern.';

$_lang['setting_password_generated_length'] = 'Länge der automatisch generierten Passwörter';
$_lang['setting_password_generated_length_desc'] = 'Die Länge der automatisch für Benutzer generierten Passwörter';

$_lang['setting_password_min_length'] = 'Passwort-Mindestlänge';
$_lang['setting_password_min_length_desc'] = 'Die Mindestlänge für ein Benutzer-Passwort.';

$_lang['setting_principal_targets'] = 'Zu ladende ACL-Targets';
$_lang['setting_principal_targets_desc'] = 'Passen Sie die ACL-Targets an, die für MODX-Benutzer geladen werden sollen (ACL = Access Control List, deutsch: Zugriffssteuerungsliste).';

$_lang['setting_proxy_auth_type'] = 'Proxy-Authentifizierungs-Typ';
$_lang['setting_proxy_auth_type_desc'] = 'Unterstützt entweder BASIC oder NTLM.';

$_lang['setting_proxy_host'] = 'Proxy-Host';
$_lang['setting_proxy_host_desc'] = 'Wenn Ihr Server einen Proxy verwendet, geben Sie hier den Hostnamen ein, um MODX-Features zu aktivieren, die den Proxy evtl. verwenden müssen, wie z. B. die Package-Verwaltung.';

$_lang['setting_proxy_password'] = 'Proxy-Passwort';
$_lang['setting_proxy_password_desc'] = 'Das Passwort, das benötigt wird, um sich beim Proxy-Server zu authentifizieren.';

$_lang['setting_proxy_port'] = 'Proxy-Port';
$_lang['setting_proxy_port_desc'] = 'Der Port für Ihren Proxy-Server.';

$_lang['setting_proxy_username'] = 'Proxy-Benutzername';
$_lang['setting_proxy_username_desc'] = 'Der Benutzername, der benötigt wird, um sich beim Proxy-Server zu authentifizieren.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb: Erlaube src über dem Document Root';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Gibt an, ob der src-Pfad außerhalb des Document-Root liegen darf. Dies ist nützlich für Multi-Kontext-Einsatz mit mehreren Virtual Hosts.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb: maximale Cache-Lebensdauer';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Lösche gecachte Thumbnails, auf die in den letzten X Tagen nicht zugegriffen wurde.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb: maximale Cache-Größe';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Lösche die Thumbnails, deren Zugriffe am längsten zurückliegen, wenn der Cache auf mehr als X MB anwächst.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb: maximale Anzahl an Cache-Dateien';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Lösche die Thumbnails, deren Zugriffe am längsten zurückliegen, wenn der Cache mehr als X Dateien umfasst.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb: Cache für Quelldateien';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Gibt an, ob Quelldateien gecacht werden sollen, wenn sie geladen werden, oder nicht. Es wird die Einstellung "Nein" empfohlen.';

$_lang['setting_phpthumb_document_root'] = 'PHPThumb-Document-Root';
$_lang['setting_phpthumb_document_root_desc'] = 'Tragen Sie hier etwas ein, wenn Sie Probleme mit der Server-Variablen DOCUMENT_ROOT haben oder wenn Fehler bei der Verwendung von OutputThumbnail oder !is_resource auftreten. Geben Sie den absoluten Document-Root-Pfad ein, den Sie verwenden möchten. Wenn dieses Feld leer ist, verwendet MODX die DOCUMENT_ROOT-Server-Variable.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb: Fehler-Hintergrundfarbe';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Ein Hexadezimalwert, ohne das #, der die Hintergrundfarbe für phpThumb-Fehlermeldungen angibt.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb: Fehler-Schriftgröße';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Ein em-Wert, der die Schriftgröße angibt, die für Text in phpThumb-Fehlermeldungen verwendet wird.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb: Fehler-Schriftfarbe';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Ein Hexadezimalwert, ohne das #, der die Schriftfarbe für Text in phpThumb-Fehlermeldungen angibt.';

$_lang['setting_phpthumb_far'] = 'phpThumb: Seitenverhältnis erzwingen';
$_lang['setting_phpthumb_far_desc'] = 'Die Standard-Force-Aspect-Ratio-Einstellung für phpThumb, wenn es in MODX verwendet wird. Der Standardwert ist "C", womit eine zentrierte Ausrichtung erreicht wird.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb: ImageMagick-Pfad';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Optional. Geben Sie hier einen alternativen Pfad zu ImageMagick an, um Thumbnails mit phpThumb zu generieren, falls nicht bereits in den PHP-Standardeinstellungen geschehen ist.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb: Hotlinking deaktiviert';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Andere Server im src-Parameter sind erlaubt, wenn Sie Hotlinking in phpThumb nicht deaktivieren.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb: Hotlinking: Bild löschen';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Gibt an, ob ein Bild, das von einem anderen Server generiert wurde, gelöscht werden soll, wenn dies nicht erlaubt wurde.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb: Hotlinking-nicht-erlaubt-Meldung';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Eine Meldung, die anstatt des Thumbnails angezeigt wird, wenn ein Hotlinking-Versuch zurückgewiesen wird.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb: Für Hotlinking zugelassene Domains';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Eine kommaseparierte Liste von Hostnamen, die in src-URLs erlaubt sind.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb: Offsite-Linking deaktiviert';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Deaktiviert die Möglichkeit für andere, phpThumb zu nutzen, um Bilder in ihren eigenen Sites darzustellen.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb: Offsite-Linking: Bild löschen';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Gibt an, ob ein Bild, das von einem anderen Server verlinkt wurde, gelöscht werden soll, wenn dies nicht erlaubt wurde.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb: Offsite-Linking erfordert Referrer';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Wenn diese Einstellung aktiviert ist, werden alle Offsite-Linking-Versuche ohne gültigen Referrer-Header zurückgewiesen.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb: Offsite-Linking-nicht-erlaubt-Meldung';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Eine Meldung, die anstatt des Thumbnails angezeigt wird, wenn ein Offsite-Linking-Versuch zurückgewiesen wird.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb: Für Offsite-Linking zugelassene Domains';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Eine kommaseparierte Liste von Hostnamen, die als Referrer für Offsite-Linking erlaubt sind.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb: Offsite-Linking-Wasserzeichen-Quelle';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Optional. Ein gültiger Dateisystem-Pfad zu einer Datei, die als Quelle für Wasserzeichen verwendet werden soll, wenn Ihre Bilder offsite (auf einem anderen Server) durch phpThumb gerendert werden.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb: Zoom-Crop';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Die Standard-Zoom-Crop-Einstellung für phpThumb, wenn es in MODX verwendet wird. Der Standardwert ist "0", wodurch Zoom-Cropping verhindert wird.';

$_lang['setting_publish_default'] = 'Ressourcen standardmäßig veröffentlichen';
$_lang['setting_publish_default_desc'] = 'Wählen Sie "Ja", wenn alle neuen Ressourcen standardmäßig veröffentlicht werden sollen.';
$_lang['setting_publish_default_err'] = 'Bitte geben Sie an, ob neue Dokumente standardmäßig veröffentlicht werden sollen.';

$_lang['setting_rb_base_dir'] = 'Ressourcen-Pfad';
$_lang['setting_rb_base_dir_desc'] = 'Geben Sie den Serverpfad zum Ressourcen-Verzeichnis ein. Diese Einstellung wird normalerweise automatisch generiert. Wenn Sie einen IIS-Server verwenden, ist MODX möglicherweise nicht in der Lage, den Pfad selbst zu ermitteln, was zu einer Fehlermeldung im Ressourcen-Browser führt. In diesem Fall können Sie hier den Pfad zum Ressourcen-Verzeichnis eingeben (so, wie er im Windows-Explorer angezeigt wird). <strong>HINWEIS:</strong> Das Ressourcen-Verzeichnis muss die Unterverzeichnisse images/, files/, flash/ und media/ enthalten, damit der Ressourcen-Browser korrekt funktioniert.';
$_lang['setting_rb_base_dir_err'] = 'Bitte geben Sie das Basisverzeichnis für den Ressourcen-Browser an.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Dieses Ressourcen-Verzeichnis existiert entweder nicht, oder es kann nicht darauf zugegriffen werden. Bitte geben Sie ein gültiges Verzeichnis an oder passen Sie die Verzeichnisrechte dieses Verzeichnisses an.';

$_lang['setting_rb_base_url'] = 'Ressourcen-URL';
$_lang['setting_rb_base_url_desc'] = 'Geben Sie die URL des Ressourcen-Verzeichnisses ein. Diese Einstellung wird normalerweise automatisch generiert. Wenn Sie einen IIS-Server verwenden, ist MODX möglicherweise nicht in der Lage, den Pfad selbst zu ermitteln, was zu einer Fehlermeldung im Ressourcen-Browser führt. In diesem Fall können Sie hier die URL des Ressourcen-Verzeichnisses eingeben (so, wie Sie Sie im Internet Explorer eingeben würden).';
$_lang['setting_rb_base_url_err'] = 'Bitte geben Sie die Basis-URL für den Ressourcen-Browser an.';

$_lang['setting_request_controller'] = 'Dateiname des Request-Controllers';
$_lang['setting_request_controller_desc'] = 'Der Dateiname des Haupt-Request-Controllers, von dem aus MODX geladen wird. Die meisten Benutzer können hier "index.php" eingestellt lassen.';

$_lang['setting_request_method_strict'] = 'Strikte Request-Methode';
$_lang['setting_request_method_strict_desc'] = 'Wenn diese Option aktiviert ist, werden Requests über den Request-ID-Parameter ignoriert, wenn suchmaschinenfreundliche URLs verwendet werden, und Requests über den Request-Alias-Parameter werden ignoriert, wenn suchmaschinenfreundliche URLs nicht aktiviert sind.';

$_lang['setting_request_param_alias'] = 'Request-Alias-Parameter';
$_lang['setting_request_param_alias_desc'] = 'Der Name des GET-Parameters für Ressourcen-Aliasse, wenn eine Weiterleitung mittels suchmaschinenfreundlicher URLs stattfindet.';

$_lang['setting_request_param_id'] = 'Request-ID-Parameter';
$_lang['setting_request_param_id_desc'] = 'Der Name des GET-Parameters für Ressourcen-IDs, wenn keine suchmaschinenfreundlichen URLs verwendet werden.';

$_lang['setting_resolve_hostnames'] = 'Hostnamen auflösen';
$_lang['setting_resolve_hostnames_desc'] = 'Möchten Sie, dass MODX versucht, die Hostnamen Ihrer Besucher aufzulösen, wenn diese Ihre Website besuchen? Das Auflösen von Hostnamen kann zusätzliche Server-Last erzeugen; Ihre Besucher werden dies im Normalfall jedoch nicht bemerken.';

$_lang['setting_resource_tree_node_name'] = 'Feld zur Benennung der Knoten im Ressourcen-Baum';
$_lang['setting_resource_tree_node_name_desc'] = 'Geben Sie das Ressourcen-Feld an, das zur Darstellung der Knoten im Ressourcen-Baum verwendet werden soll. Standardmäßig wird das Feld pagetitle verwendet, es kann aber jedes Ressourcen-Feld verwendet werden, z.B. menutitle, alias, longtitle etc.';

$_lang['setting_resource_tree_node_tooltip'] = 'Ressourcen-Baum-Tooltip-Feld';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Geben Sie das Ressourcen-Feld an, das bei der Darstellung der Tooltips für die Einträge im Ressourcen-Baum verwendet wird. Jedes Ressourcen-Feld kann verwendet werden, z. B. menutitle, alias, longtitle etc. Wird dieses Feld leer gelassen, so wird der longtitle mit einer Beschreibung darunter angezeigt.';

$_lang['setting_richtext_default'] = 'Rich-Text-Editor standardmäßig verwenden';
$_lang['setting_richtext_default_desc'] = 'Wählen Sie "Ja", wenn alle neuen Ressourcen standardmäßig den Rich-Text-Editor verwenden sollen.';

$_lang['setting_search_default'] = 'Ressourcen standardmäßig durchsuchbar';
$_lang['setting_search_default_desc'] = 'Wählen Sie "Ja", wenn alle neuen Ressourcen standardmäßig durchsuchbar sein sollen.';
$_lang['setting_search_default_err'] = 'Bitte geben Sie an, ob neue Dokumente standardmäßig durchsuchbar sein sollen oder nicht.';

$_lang['setting_server_offset_time'] = 'Server-Zeit-Offset';
$_lang['setting_server_offset_time_desc'] = 'Geben Sie die Zeitdifferenz zwischen Ihrem Standort und dem des Servers in Stunden an.';

$_lang['setting_server_protocol'] = 'Servertyp';
$_lang['setting_server_protocol_desc'] = 'Wenn Ihre Website über eine HTTPS-Verbindung aufgerufen werden soll, geben Sie hier bitte "https" ein, sonst "http".';
$_lang['setting_server_protocol_err'] = 'Bitte geben Sie an, ob Ihre Website komplett SSL-gesichert ist (also alle Seiten über HTTPS aufgerufen werden) oder nicht.';
$_lang['setting_server_protocol_http'] = 'HTTP';
$_lang['setting_server_protocol_https'] = 'HTTPS';

$_lang['setting_session_cookie_domain'] = 'Session-Cookie-Domain';
$_lang['setting_session_cookie_domain_desc'] = 'Verwenden Sie diese Einstellung, um die Session-Cookie-Domain anzupassen. Lassen Sie das Feld leer, wenn die aktuelle Domain verwendet werden soll.';

$_lang['setting_session_cookie_lifetime'] = 'Session-Cookie-Lebensdauer';
$_lang['setting_session_cookie_lifetime_desc'] = 'Verwenden Sie diese Einstellung, um die Session-Cookie-Lebensdauer anzupassen (in Sekunden). Diese gibt an, wie lange ein Session-Cookie gültig ist, wenn die Login-Option "An mich erinnern" gewählt wurde. Standardeinstellung ist "604800" (= 7 Tage).';

$_lang['setting_session_cookie_path'] = 'Session-Cookie-Pfad';
$_lang['setting_session_cookie_path_desc'] = 'Verwenden Sie diese Einstellung, um den Cookie-Pfad anzupassen. Damit kann genau festgelegt werden, wo innerhalb einer Site ein Cookie gültig ist und wo nicht. Lassen Sie das Feld leer, wenn die MODX_BASE_URL verwendet werden soll.';

$_lang['setting_session_cookie_secure'] = 'Sichere Session-Cookies';
$_lang['setting_session_cookie_secure_desc'] = 'Setzen Sie diese Einstellung auf "Ja", um sichere Session-Cookies zu verwenden. Diese werden ausschließlich SSL-geschützt übertragen.';

$_lang['setting_session_gc_maxlifetime'] = 'Maximale Lebensdauer des Session-Garbage-Collectors';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Erlaubt Anpassung der PHP-Konfigurationseinstellung session.gc_maxlifetime bei Benutzung von "modSessionHandler".';

$_lang['setting_session_handler_class'] = 'Name der Session-Handler-Klasse';
$_lang['setting_session_handler_class_desc'] = 'Für datenbankgestützte Sessions verwenden Sie bitte "modSessionHandler". Lassen Sie dieses Feld leer, um die Standard-PHP-Sessionverwaltung zu verwenden.';

$_lang['setting_session_name'] = 'Session-Name';
$_lang['setting_session_name_desc'] = 'Verwenden Sie diese Einstellung, um den Session-Namen für die Sessions in MODX anzupassen. Lassen Sie das Feld leer, wenn der standardmäßige PHP-Session-Name verwendet werden soll.';

$_lang['setting_settings_version'] = 'MODX-Version';
$_lang['setting_settings_version_desc'] = 'Die aktuell verwendete Version von MODX Revolution.';

$_lang['setting_settings_distro'] = 'MODX-Distribution';
$_lang['setting_settings_distro_desc'] = 'Die momentan installierte MODX-Distribution.';

$_lang['setting_set_header'] = 'HTTP-Header setzen';
$_lang['setting_set_header_desc'] = 'Wenn diese Einstellung aktiviert ist, versucht MODX, die HTTP-Header für Ressourcen zu setzen.';

$_lang['setting_show_tv_categories_header'] = 'Zeigt Reiter-Überschrift "Kategorien" für TV an';
$_lang['setting_show_tv_categories_header_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wurde, zeigt MODX die Überschrift "Kategorien" über dem ersten Kategorien-Reiter an, wenn Template-Variablen in einer Ressource bearbeitet werden.';

$_lang['setting_signupemail_message'] = 'E-Mail nach Account-Erstellung';
$_lang['setting_signupemail_message_desc'] = 'Hier können Sie die Nachricht eingeben, die an einen Benutzer gesendet wird, wenn Sie einen Account für ihn erstellen und MODX ihm eine E-Mail senden lassen, die seinen Benutzernamen und sein Passwort enthält.<br /><strong>Hinweis:</strong> Die folgenden Platzhalter werden vom System ersetzt, wenn eine Nachricht versendet wird:<br /><br />[[+sname]] - Name Ihrer Website,<br />[[+saddr]] - E-Mail-Adresse ihrer Website (bzw. des Webmasters),<br />[[+surl]] - URL Ihrer Website,<br />[[+uid]] - Benutzername oder ID des Benutzers,<br />[[+pwd]] - Passwort des Benutzers,<br />[[+ufn]] - Vollständiger Name des Benutzers.<br /><br /><strong>Achten Sie darauf, dass zumindest [[+uid]] und [[+pwd]] in der E-Mail enthalten sind, da sonst der Benutzername und das Passwort nicht mit der Mail versendet werden und Ihre Benutzer folglich ihre Zugangsdaten nicht kennen!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hallo [[+uid]],\n\nanbei erhalten Sie Ihre Zugangsdaten für das Backend von [[+sname]]:\n\nBenutzername: [[+uid]]\nPasswort: [[+pwd]]\n\nSobald Sie sich in das Backend ([[+surl]]) eingeloggt haben, können Sie Ihr Passwort ändern.\n\nMit freundlichen Grüßen,\nIhr Website-Administrator';

$_lang['setting_site_name'] = 'Name Ihrer Website';
$_lang['setting_site_name_desc'] = 'Geben Sie den Namen Ihrer Website hier ein.';
$_lang['setting_site_name_err'] = 'Bitte geben Sie einen Namen für Ihre Website ein.';

$_lang['setting_site_start'] = 'Startseite der Website';
$_lang['setting_site_start_desc'] = 'Geben Sie die ID der Ressource ein, die Sie als Startseite der Website verwenden möchten. <strong>ACHTUNG: Stellen Sie sicher, dass die ID, die Sie eingeben, zu einer existierenden Ressource gehört und dass diese veröffentlicht wurde!</strong>';
$_lang['setting_site_start_err'] = 'Bitte geben Sie eine Ressourcen-ID für die Startseite der Site an.';

$_lang['setting_site_status'] = 'Website-Status';
$_lang['setting_site_status_desc'] = 'Wählen Sie "Ja", um Ihre Website im Internet zu veröffentlichen. Wenn Sie "Nein" auswählen, sehen Besucher Ihrer Site die "Website-Offline-Mitteilung"; die Website selbst kann dann nicht abgerufen werden.';
$_lang['setting_site_status_err'] = 'Bitte wählen Sie, ob die Website online ("Ja") oder offline ("Nein") ist.';

$_lang['setting_site_unavailable_message'] = 'Website-Offline-Mitteilung';
$_lang['setting_site_unavailable_message_desc'] = 'Mitteilung, die angezeigt wird, wenn die Website offline geschaltet wurde oder ein Fehler auftritt. <strong>Hinweis: Diese Mitteilung wird nur angezeigt, wenn die Option "Website-Offline-Seite" nicht verwendet (also leer gelassen) wird.</strong>';

$_lang['setting_site_unavailable_page'] = 'Website-Offline-Seite';
$_lang['setting_site_unavailable_page_desc'] = 'Geben Sie die ID der Ressource ein, die angezeigt werden soll, wenn die Website offline geschaltet wurde. <strong>ACHTUNG: Stellen Sie sicher, dass die ID, die Sie eingeben, zu einer existierenden Ressource gehört und dass diese veröffentlicht wurde!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Bitte geben Sie die Ressourcen-ID für die Website-Offline-Seite an.';

$_lang['setting_strip_image_paths'] = 'Relative Dateibrowser-Pfade?';
$_lang['setting_strip_image_paths_desc'] = 'Wenn Sie diese Einstellung auf "Nein" setzen, wird MODX Dateibrowser-Ressourcen-Quellen (Bilder, Dateien, Flash-Animationen etc.) als absolute URLs speichern. Relative URLs dagegen sind hilfreich, wenn Sie Ihre MODX-Installation verschieben möchten, z. B. von einer Testsite zu einer produktiven Website. Falls Ihnen nicht klar ist, was das bedeutet, belassen Sie es am besten bei der Einstellung "Ja".';

$_lang['setting_symlink_merge_fields'] = 'Ressourcen-Felder in Symlinks kombinieren';
$_lang['setting_symlink_merge_fields_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt ist, werden nichtleere Felder automatisch mit denen der Ziel-Ressource kombiniert, wenn mit Hilfe von Symlinks weitergeleitet wird.';

$_lang['setting_topmenu_show_descriptions'] = 'Beschreibungen im Hauptmenü anzeigen';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Wenn diese Einstellung auf "Nein" gesetzt wird, werden die Beschreibungen in den Menüpunkten des MODX-Manager-Hauptmenüs nicht angezeigt.';

$_lang['setting_tree_default_sort'] = 'Feld, nach dem der Ressourcen-Baum standardmäßig sortiert wird';
$_lang['setting_tree_default_sort_desc'] = 'Das Feld, nach dem der Ressourcen-Baum standardmäßig beim Öffnen des Managers sortiert wird.';

$_lang['setting_tree_root_id'] = 'Ressourcen-Baum-Basis-ID';
$_lang['setting_tree_root_id_desc'] = 'Geben Sie hier eine gültige ID einer Ressource ein, um den Ressourcen-Baum links bei dieser Ressource als Basis beginnen zu lassen. Benutzer können dann nur Ressourcen sehen, die Kinder der angegebenen Ressource sind.';

$_lang['setting_tvs_below_content'] = 'Template-Variablen unter den Inhalt verschieben';
$_lang['setting_tvs_below_content_desc'] = 'Setzen Sie diese Einstellung auf "Ja", um Template-Variablen beim Bearbeiten von Ressourcen unter das Eingabefeld für den Inhalt zu verschieben.';

$_lang['setting_ui_debug_mode'] = 'Benutzerschnittstellen-Debug-Modus';
$_lang['setting_ui_debug_mode_desc'] = 'Setzen Sie diese Einstellung auf "Ja", um Debug-Meldungen auszugeben, wenn Sie die Benutzerschnittstelle für das Standard-Manager-Theme verwenden. Sie müssen einen Browser verwenden, der console.log unterstützt.';

$_lang['setting_udperms_allowroot'] = 'Benutzer-Ressourcen im Site-Root zulassen';
$_lang['setting_udperms_allowroot_desc'] = 'Möchten Sie Ihren Benutzern erlauben, neue Ressourcen im Wurzelverzeichnis der Website zu erstellen?';

$_lang['setting_unauthorized_page'] = 'Seite für unautorisierte Zugriffe';
$_lang['setting_unauthorized_page_desc'] = 'Geben Sie die ID der Ressource ein, die angezeigt werden soll, wenn eine geschützte Ressource aufgerufen wurde oder eine, für die dem Benutzer die Berechtigung fehlt. <strong>ACHTUNG: Stellen Sie sicher, dass die ID, die Sie eingeben, zu einer existierenden Ressource gehört, dass diese veröffentlicht wurde und öffentlich zugänglich ist!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Geben Sie eine Ressourcen-ID für die Seite für unautorisierte Zugriffe ein.';

$_lang['setting_upload_files'] = 'Hochladbare Dateitypen';
$_lang['setting_upload_files_desc'] = 'Hier können Sie eine Liste von Dateitypen eingeben, die über den Ressourcen-Manager in das Verzeichnis assets/files/ hochgeladen werden können. Bitte geben Sie die Dateiendungen der Dateitypen ein, durch Kommata getrennt.';

$_lang['setting_upload_flash'] = 'Hochladbare Flash-Dateitypen';
$_lang['setting_upload_flash_desc'] = 'Hier können Sie eine Liste von Dateitypen eingeben, die über den Ressourcen-Manager in das Verzeichnis assets/flash/ hochgeladen werden können. Bitte geben Sie die Dateiendungen der Flash-Dateitypen ein, durch Kommata getrennt.';

$_lang['setting_upload_images'] = 'Hochladbare Bild-Dateitypen';
$_lang['setting_upload_images_desc'] = 'Hier können Sie eine Liste von Dateitypen eingeben, die über den Ressourcen-Manager in das Verzeichnis assets/images/ hochgeladen werden können. Bitte geben Sie die Dateiendungen der Bildtypen ein, durch Kommata getrennt.';

$_lang['setting_upload_maxsize'] = 'Maximale Upload-Größe';
$_lang['setting_upload_maxsize_desc'] = 'Geben Sie die maximale Größe für Dateien an, die über den Dateimanager hochgeladen werden können. Die Upload-Dateigröße muss in Bytes angegeben werden. <strong>Hinweis: Der Upload großer Dateien kann eine sehr lange Zeit benötigen!</strong>';

$_lang['setting_upload_media'] = 'Hochladbare Medien-Dateitypen';
$_lang['setting_upload_media_desc'] = 'Hier können Sie eine Liste von Dateitypen eingeben, die über den Ressourcen-Manager in das Verzeichnis assets/media/ hochgeladen werden können. Bitte geben Sie die Dateiendungen der Medientypen ein, durch Kommata getrennt.';

$_lang['setting_use_alias_path'] = 'Suchmaschinenfreundliche Alias-Pfade';
$_lang['setting_use_alias_path_desc'] = 'Wenn Sie diese Einstellung auf "Ja" setzen, wird der komplette Pfad zur Ressource angezeigt, wenn diese einen Alias hat. Wenn z.B. eine Ressource mit dem Alias "kind" in einer Container-Ressource mit dem Alias "eltern" abgelegt ist, wird der komplette Alias-Pfad zu dieser Ressource als "/eltern/kind.html" angezeigt.<br /><strong>HINWEIS: Wenn Sie diese Option auf "Ja" setzen (also Alias-Pfade aktivieren), verwenden Referenz-Elemente (wie Bilder, CSS- und JavaScript-Dateien etc.) absolute Pfade, also z.B. "/assets/images" im Gegensatz zu "assets/images". Dadurch wird verhindert, dass der Browser (oder der Webserver) die relativen Pfade an die Alias-Pfade anhängt.</strong>';

$_lang['setting_use_browser'] = 'Ressourcen-Browser aktivieren';
$_lang['setting_use_browser_desc'] = 'Wählen Sie "Ja", um den Ressourcen-Browser zu aktivieren. Dies erlaubt Ihren Benutzern, Ressourcen wie Bilder, Flash- und Medien-Dateien auf den Server hochzuladen und dort durchzusehen.';
$_lang['setting_use_browser_err'] = 'Bitte geben Sie an, ob Sie den Ressourcen-Browser verwenden möchten oder nicht.';

$_lang['setting_use_editor'] = 'Rich-Text-Editor aktivieren';
$_lang['setting_use_editor_desc'] = 'Möchten Sie den Rich-Text-Editor aktivieren? Wenn Sie lieber HTML-Code schreiben, können Sie den Editor mittels dieser Einstellung deaktivieren. Bitte beachten Sie, dass diese Einstellung sich auf alle Dokumente und alle Benutzer auswirkt!';
$_lang['setting_use_editor_err'] = 'Bitte geben Sie an, ob Sie einen Rich-Text-Editor verwenden möchten oder nicht.';

$_lang['setting_use_multibyte'] = 'Multibyte-Extension nutzen';
$_lang['setting_use_multibyte_desc'] = 'Setzen Sie diese Einstellung auf "Ja", wenn Sie die mbstring-Extension für Multibyte-Zeichen (Zeichen, die in der verwendeten Zeichencodierung durch mehr als ein Byte repräsentiert werden) in Ihrer MODX-Installation nutzen möchten. Setzen Sie diese Einstellung nur auf "Ja", wenn die mbstring-PHP-Extension installiert ist.';

$_lang['setting_use_weblink_target'] = 'WebLink-Ziel verwenden';
$_lang['setting_use_weblink_target_desc'] = 'Setzen Sie diese Einstellung auf "Ja", wenn Sie möchten, dass mittels MODX-Link-Tags ([[~RessourcenID]]) oder der Methode makeUrl() generierte Weblink-URLs aus der in der Weblink-Ressource eingegebenen URL bestehen. Anderenfalls bestehen diese aus der internen MODX-URL. Ein Beispiel: Es existieren ein Dokument mit der Ressourcen-ID 5 und dem Alias "mein-dokument" und ein Weblink mit der Ressourcen-ID 12, in dessen URL-Feld mit der Bezeichnung "Weblink" nur die Ressourcen-ID des Dokuments (5) engetragen wurde; suchmaschinenfreundliche URLs bzw. Aliasse sind aktiviert. In einem HTML-Link wird nun ein MODX-Link-Tag mit der Ressourcen-ID des Weblinks verwendet: &lt;a href="[[~12]]"&gt;Link auf den Weblink&lt;/a&gt;. Steht diese Einstellung auf "Ja", so enthält die generierte URL nur genau das, was in das URL-Feld des Weblinks eingegeben wurde, nämlich die Ressourcen-ID des Dokuments, also "5". Steht diese Einstellung auf "Nein", so enthält die generierte URL den Alias des verlinkten Dokuments plus die ggf. zugeordnete Endung, im Normalfall also "mein-dokument.html".';

$_lang['setting_webpwdreminder_message'] = 'E-Mail nach Passwort-Anforderung';
$_lang['setting_webpwdreminder_message_desc'] = 'Hier können Sie die Nachricht eingeben, die an einen Benutzer gesendet wird, wenn er eine neues Passwort anfordert. Der MODX-Manager sendet eine E-Mail an den Benutzer, die dessen neues Passwort und Aktivierungs-Informationen enthält.<br /><strong>Hinweis:</strong> Die folgenden Platzhalter werden vom System ersetzt, wenn eine Nachricht versendet wird:<br /><br />[[+sname]] - Name Ihrer Website,<br />[[+saddr]] - E-Mail-Adresse ihrer Website (bzw. des Webmasters),<br />[[+surl]] - URL Ihrer Website,<br />[[+uid]] - Benutzername oder ID des Benutzers,<br />[[+pwd]] - Passwort des Benutzers,<br />[[+ufn]] - Vollständiger Name des Benutzers.<br /><br /><strong>Achten Sie darauf, dass zumindest [[+uid]] und [[+pwd]] in der E-Mail enthalten sind, da sonst der Benutzername und das Passwort nicht mit der Mail versendet werden und Ihre Benutzer folglich ihre Zugangsdaten nicht kennen!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Hallo [[+uid]],\n\num Ihr neues Passwort zu aktivieren, klicken Sie bitte auf den folgenden Link:\n\n[[+surl]]\n\nNach erfolgreicher Aktivierung können Sie folgendes Passwort verwenden, um sich einzuloggen:\n\nPasswort:[[+pwd]]\n\nFalls Sie diese E-Mail nicht angefordert haben sollten, ignorieren Sie sie bitte einfach.\n\nMit freundlichen Grüßen,\nIhr Website-Administrator';

$_lang['setting_websignupemail_message'] = 'E-Mail nach Website-Account-Erstellung';
$_lang['setting_websignupemail_message_desc'] = 'Hier können Sie die Nachricht eingeben, die an einen Benutzer gesendet wird, wenn Sie einen Website-Account für ihn erstellen und MODX ihm eine E-Mail senden lassen, die seinen Benutzernamen und sein Passwort enthält.<br /><strong>Hinweis:</strong> Die folgenden Platzhalter werden vom System ersetzt, wenn eine Nachricht versendet wird:<br /><br />[[+sname]] - Name Ihrer Website,<br />[[+saddr]] - E-Mail-Adresse ihrer Website (bzw. des Webmasters),<br />[[+surl]] - URL Ihrer Website,<br />[[+uid]] - Benutzername oder ID des Benutzers,<br />[[+pwd]] - Passwort des Benutzers,<br />[[+ufn]] - Vollständiger Name des Benutzers.<br /><br /><strong>Achten Sie darauf, dass zumindest [[+uid]] und [[+pwd]] in der E-Mail enthalten sind, da sonst der Benutzername und das Passwort nicht mit der Mail versendet werden und Ihre Benutzer folglich ihre Zugangsdaten nicht kennen!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hallo [[+uid]],\n\nanbei erhalten Sie Ihre Zugangsdaten für [[+sname]]:\n\nBenutzername: [[+uid]]\nPasswort: [[+pwd]]\n\nSobald Sie sich in [[+sname]] unter [[+surl]] eingeloggt haben, können Sie Ihr Passwort ändern.\n\nMit freundlichen Grüßen,\nIhr Website-Administrator';

$_lang['setting_welcome_screen'] = 'Willkommens-Bildschirm anzeigen';
$_lang['setting_welcome_screen_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt ist, wird der Willkommens-Bildschirm beim nächsten erfolgreichen Laden der Manager-Startseite einmalig angezeigt, danach nicht mehr.';

$_lang['setting_welcome_screen_url'] = 'URL für den Willkommens-Bildschirm';
$_lang['setting_welcome_screen_url_desc'] = 'Die URL für den Willkommens-Bildschirm, der beim ersten Laden der Manager-Startseite von MODX Revolution angezeigt wird.';

$_lang['setting_which_editor'] = 'Zu verwendender Editor';
$_lang['setting_which_editor_desc'] = 'Hier können Sie auswählen, welchen Rich-Text-Editor Sie verwenden möchten. Sie können zusätzliche Rich-Text-Editoren über die Package-Verwaltung herunterladen und installieren.';

$_lang['setting_which_element_editor'] = 'Für Elemente zu verwendender Editor';
$_lang['setting_which_element_editor_desc'] = 'Hier können Sie auswählen, welchen Rich-Text-Editor Sie verwenden möchten, wenn Sie Elemente (Templates, Chunks, Snippets etc.) bearbeiten. Sie können zusätzliche Rich-Text-Editoren über die Package-Verwaltung herunterladen und dann installieren';

$_lang['setting_xhtml_urls'] = 'XHTML-URLs';
$_lang['setting_xhtml_urls_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, werden alle URLs, die von MODX generiert werden, XHTML-valide erzeugt, einschließlich Codierung des Ampersand-Zeichens ("kaufmännisches Und").';

$_lang['setting_default_context'] = 'Standard-Kontext';
$_lang['setting_default_context_desc'] = 'Wählen Sie den Standard-Kontext, den Sie für neue Ressourcen verwenden möchten.';

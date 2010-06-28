<?php
/**
 * @package modx
 * @subpackage lexicon

 * @language de
 * @namespace core
 * @topic setting
 */
$_lang['area'] = 'Bereich';
$_lang['area_authentication'] = 'Authentifizierung und Sicherheit';
$_lang['area_caching'] = 'Caching';
$_lang['area_editor'] = 'Rich-Text-Editor';
$_lang['area_file'] = 'Dateisystem';
$_lang['area_filter'] = 'Nach Bereich filtern...';
$_lang['area_furls'] = 'Suchmaschinenfreundliche URLs';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Lexikon und Sprache';
$_lang['area_mail'] = 'Mail';
$_lang['area_manager'] = 'Backend-Manager';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session und Cookie';
$_lang['area_lexicon_string'] = 'Lexikon-Eintrag für den Bereich';
$_lang['area_lexicon_string_msg'] = 'Geben Sie hier den Schlüssel für den Lexikon-Eintrag für den Bereich ein. Wenn es keinen Lexikon-Eintrag gibt, wird einfach der Bereichs-Schlüssel angezeigt.<br />Core-Bereiche:<ul><li>authentication</li><li>caching</li><li>file</li><li>furls</li><li>language</li><li>manager</li><li>session</li><li>site</li><li>system</li></ul>';
$_lang['area_site'] = 'Site';
$_lang['area_system'] = 'System und Server';
$_lang['areas'] = 'Bereiche';
$_lang['namespace'] = 'Namensraum';
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
$_lang['setting_remove_confirm'] = 'Sind Sie sicher, dass Sie diese Einstellung löschen möchten? Das könnte Ihre MODx-Installation unbrauchbar machen.';
$_lang['setting_update'] = 'Einstellung bearbeiten';
$_lang['settings_after_install'] = 'Da dies eine neue MODx-Installation ist, müssen Sie diese Einstellungen kontrollieren und ggf. einige Ihren Wünschen entsprechend ändern. Nachdem Sie die Einstellungen kontrolliert und ggf. angepasst haben, klicken Sie auf "Speichern", um die Daten in der Datenbank zu aktualisieren.<br /><br />';
$_lang['settings_desc'] = 'Hier können Sie sowohl generelle Konfigurationseinstellungen für die MODx-Manager-Benutzeroberfläche vornehmen als auch festlegen, wie sich Ihre MODx-Website verhält. Doppelklicken Sie über der Einstellung, die Sie ändern möchten, auf die Werte-Spalte, um den Wert dynamisch direkt in der Tabelle zu bearbeiten, oder führen Sie einen Rechtsklick auf einer Einstellung aus, um weitere Optionen angeboten zu bekommen. Sie können, wo vorhanden, auch auf das "+"-Icon klicken, um eine Erläuterung zu der jeweiligen Einstellung zu bekommen.';
$_lang['settings_furls'] = 'Suchmaschinenfreundliche URLs';
$_lang['settings_misc'] = 'Verschiedenes';
$_lang['settings_site'] = 'Site';
$_lang['settings_ui'] = 'Interface &amp; Features';
$_lang['settings_users'] = 'Benutzer';
$_lang['system_settings'] = 'Systemeinstellungen';

// user settings
$_lang['setting_allow_mgr_access'] = 'Zugriff auf den MODx-Manager';
$_lang['setting_allow_mgr_access_desc'] = 'Verwenden Sie diese Option, um den Zugriff auf die MODx-Manager-Oberfläche zu erlauben oder zu verbieten. <strong>HINWEIS: Wenn diese Einstellung auf "nein" gesetzt ist, werden Benutzer auf die "Startseite für in den Manager eingeloggte Benutzer" oder die "Startseite der Website" weitergeleitet.';

$_lang['setting_failed_login'] = 'Fehlgeschlagene Login-Versuche';
$_lang['setting_failed_login_desc'] = 'Hier können Sie die Anzahl fehlgeschlagener Login-Versuche angeben, die erlaubt sind, bevor der Benutzer geblockt wird.';

$_lang['setting_login_allowed_days'] = 'Wochentagsbeschränkung';
$_lang['setting_login_allowed_days_desc'] = 'Wählen Sie die Wochentage aus, an denen der Benutzer Zugriff haben soll.';

$_lang['setting_login_allowed_ip'] = 'Zugelassene IP-Adresse';
$_lang['setting_login_allowed_ip_desc'] = 'Geben Sie die IP-Adressen an, von denen aus sich dieser Benutzer einloggen darf. <strong>HINWEIS: Trennen Sie mehrere IP-Adressen mit einem Komma (,).</strong>';

$_lang['setting_login_homepage'] = 'Startseite für eingeloggte Benutzer';
$_lang['setting_login_homepage_desc'] = 'Geben Sie die ID des Dokuments ein, zu dem Sie den Butzer weiterleiten möchten, nachdem er sich eingeloggt hat. <strong>ACHTUNG: Stellen Sie sicher, dass die ID, die Sie eingeben, zu einem existierenden Dokument gehört, dass dieses veröffentlicht wurde und dass der Benutzer Zugriff darauf hat!</strong>';

// system settings
$_lang['setting_allow_duplicate_alias'] = 'Alias-Duplikate zulassen';
$_lang['setting_allow_duplicate_alias_desc'] = 'Wenn diese Einstellung auf "ja" gesetzt wird, können doppelte Aliasse gespeichert werden. <strong>ACHTUNG: Bei Verwendung dieser Einstellung sollte auch die Option "Suchmaschinenfreundliche Aliasse benutzen" auf "ja" gesetzt werden, um Probleme bei der Referenzierung von Ressourcen zu vermeiden.</strong>';

$_lang['setting_allow_tags_in_post'] = 'HTML-Tags in POST-Requests erlauben';
$_lang['setting_allow_tags_in_post_desc'] = 'Wenn diese Einstellung auf "ja" gesetzt ist, können POST-Requests HTML-Formular-Tags enthalten.';

$_lang['setting_auto_menuindex'] = 'Automatische Menü-Indizierung';
$_lang['setting_auto_menuindex_desc'] = 'Wählen Sie "ja", um die automatische Menü-Indizierung einzuschalten. Ist diese aktiv, erhält das als erstes erstellte Dokument in einem Container/Ordner als Menü-Index den Wert 0, und dieser Wert wird dann für jedes nachfolgende Dokument, das Sie erstellen, erhöht.';

$_lang['setting_auto_check_pkg_updates'] = 'Automatische Suche nach Package-Updates';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, sucht MODx in der Package-Verwaltung automatisch nach Updates für Packages. Dies kann die Anzeige der Tabelle verlangsamen.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Cache-Ablaufzeit für die automatische Package-Updates-Überprüfung';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Die Anzahl der Minuten, für die die Package-Verwaltung die Ergebnisse der Package-Updates-Überprüfung cacht.';
$_lang['setting_allow_multiple_emails'] = 'E-Mail-Adressen-Duplikate für Benutzer erlauben';
$_lang['setting_allow_multiple_emails_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, dürfen mehrere Benutzer die selbe E-Mail-Adresse verwenden.';

$_lang['setting_automatic_alias'] = 'Alias automatisch generieren';
$_lang['setting_automatic_alias_desc'] = 'Wählen Sie "ja", wenn das System beim Speichern automatisch einen auf dem Seitentitel der Ressource basierenden Alias generieren soll.';

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

$_lang['setting_cache_default'] = 'Voreinstellung für Cache';
$_lang['setting_cache_default_desc'] = 'Wählen Sie "ja", um für alle neuen Ressourcen standardmäßig den Cache zu aktivieren.';
$_lang['setting_cache_default_err'] = 'Bitte geben Sie an, ob Dokumente standardmäßig gecacht werden sollen oder nicht.';

$_lang['setting_cache_disabled'] = 'Globale Cache-Optionen deaktivieren';
$_lang['setting_cache_disabled_desc'] = 'Wählen Sie "ja", um alle MODx-Caching-Features zu deaktivieren.';
$_lang['setting_cache_disabled_err'] = 'Bitte geben Sie an, ob der Cache aktiviert werden soll oder nicht.';

$_lang['setting_cache_json'] = 'JSON-Daten cachen';
$_lang['setting_cache_json_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, so wird das Cachen im JSON-Format vorgenommen.';

$_lang['setting_cache_expires'] = 'Ablaufzeit für den Standard-Cache';
$_lang['setting_cache_expires_desc'] = 'Dieser Wert (in Sekunden) legt fest, wie lange Cache-Dateien des Standard-Caches gültig sind.';

$_lang['setting_cache_json_expires'] = 'Ablaufzeit für JSON-Cache';
$_lang['setting_cache_json_expires_desc'] = 'Ablaufzeit für im JSON-Format gecachte Daten. Der Wert "0" bedeutet, dass der Cache niemals abläuft.';

$_lang['setting_cache_handler'] = 'Caching-Handler-Klasse';
$_lang['setting_cache_handler_desc'] = 'Der Klassenname des Type-Handlers, der für das Caching genutzt werden soll.';

$_lang['setting_cache_lang_js'] = 'Lexikon-JavaScript-Zeichenketten cachen';
$_lang['setting_cache_lang_js_desc'] = 'Wenn diese Option auf "ja" gesetzt ist, werden Server-Header verwendet, um die ins JavaScript geladenen Lexikon-Zeichenketten für die Manager-Oberfläche zu cachen.';

$_lang['setting_cache_lexicon_topics'] = 'Lexikon-Themen cachen';
$_lang['setting_cache_lexicon_topics_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, werden alle Lexikon-Themen gecacht, wodurch die Ladezeiten für die Internationalisierungs-Funktionalität drastisch reduziert werden. Es wird dringend empfohlen, diese Einstellung auf "Ja" zu belassen.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Nicht zum Core-Namespace gehörende Lexikon-Themen cachen';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Wenn diese Einstellung deaktiviert ist, werden nicht zum Core-Namespace gehörende Lexikon-Themen nicht gecacht. Es ist nützlich, dies zu deaktivieren, wenn Sie Ihre eigenen Extras entwickeln.';

$_lang['setting_cache_resource'] = 'Partiellen Ressourcen-Cache aktivieren';
$_lang['setting_cache_resource_desc'] = 'Partielles Ressourcen-Caching kann für jede Ressource einzeln konfiguriert werden, wenn dieses Feature aktiviert ist. Das Deaktivieren dieses Features deaktiviert es global.';

$_lang['setting_cache_resource_expires'] = 'Ablaufzeit für den partiellen Ressourcen-Cache';
$_lang['setting_cache_resource_expires_desc'] = 'Ablaufzeit für den partiellen Ressourcen-Cache. Der Wert "0" bedeutet, dass der Cache niemals abläuft.';

$_lang['setting_cache_scripts'] = 'Skript-Cache aktivieren';
$_lang['setting_cache_scripts_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, cacht MODx alle Skripte (Snippets und Plugins) in Dateien, um die Ladezeiten zu verringern. Es wird empfohlen, diese Einstellung auf "Ja" zu belassen.';

$_lang['setting_cache_system_settings'] = 'Systemeinstellungen-Cache aktivieren';
$_lang['setting_cache_system_settings_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, werden die Systemeinstellungen gecacht, um die Ladezeiten zu verringern. Es wird empfohlen, diese Einstellung auf "Ja" zu belassen.';

$_lang['setting_compress_css'] = 'Komprimiertes CSS verwenden';
$_lang['setting_compress_css_desc'] = 'Wenn diese Option aktiviert ist, verwendet MODx eine komprimierte Version seiner CSS-Stylesheets in der Manager-Oberfläche. Dadurch werden die Lade- und Ausführungszeiten im Manager deutlich reduziert. Deaktivieren Sie diese Einstellung nur, wenn Sie Core-Elemente modifizieren.';

$_lang['setting_compress_js'] = 'Komprimierte JavaScript-Bibliotheken verwenden';
$_lang['setting_compress_js_desc'] = 'Wenn dies aktiviert ist, benutzt MODx eine komprimierte Version seiner JavaScript-Bibliotheken. Dies reduziert Last und Ausführungszeit. Deaktivieren Sie diese Einstellung nur, wenn Sie Core-Elemente modifizieren.';

$_lang['setting_concat_js'] = 'Verknüpfte Javascript-Bibliotheken verwenden';
$_lang['setting_concat_js_desc'] = 'Wenn diese Option aktiviert ist, verwendet MODx eine verknüpfte Version seiner meistverwendeten JavaScript-Bibliotheken in der Manager-Oberfläche; diese werden dann als eine einzige Datei ausgeliefert. Dadurch werden die Lade- und Ausführungszeiten im Manager drastisch reduziert. Deaktivieren Sie diese Einstellung nur, wenn Sie Core-Elemente modifizieren.';

$_lang['setting_container_suffix'] = 'Container-Suffix';
$_lang['setting_container_suffix_desc'] = 'Das Suffix, das Ressourcen, die als Container definiert wurden, hinzugefügt wird, wenn suchmaschinenfreundliche URLs verwendet werden.';

$_lang['setting_cultureKey'] = 'Sprache';
$_lang['setting_cultureKey_desc'] = 'Wählen Sie die Sprache für alle Nicht-Manager-Kontexte, einschließlich des Kontexts "web".';

$_lang['setting_custom_resource_classes'] = 'Eigene Ressourcen-Klassen';
$_lang['setting_custom_resource_classes_desc'] = 'Eine kommaseparierte Liste von eigenen Ressourcen-Klassen. Geben Sie diese in der Form kleingeschriebener_lexikon_schluessel:klassenName an (Beispiel: wiki_resource:WikiResource). Alle eigenen Ressourcen-Klassen müssen modResource erweitern. Um die Controller-Position für jede Klasse anzugeben, fügen Sie eine Einstellung mit [kleingeschriebenerKlassenName]_delegate_path mit dem Verzeichnispfad der PHP-Dateien create.php/update.php an. Beispiel: wikiresource_delegate_path für eine Klasse namens WikiResource, die modResource erweitert.';

$_lang['setting_default_template'] = 'Standard-Template';
$_lang['setting_default_template_desc'] = 'Wählen Sie das Standard-Template, das Sie für neue Ressourcen verwenden möchten. Sie können weiterhin ein anderes Template im Ressourcen-Editor auswählen; diese Einstellung sorgt nur dafür, dass eines Ihrer Templates für Sie vorausgewählt wird.';

$_lang['setting_editor_css_path'] = 'Pfad zur CSS-Datei';
$_lang['setting_editor_css_path_desc'] = 'Geben Sie den Pfad zu Ihrer CSS-Datei ein, die Sie im Editor benutzen möchten. Der beste Weg, den Pfad anzugeben, ist, den Pfad vom Server-Root aus einzugeben, z.B.: /assets/site/style.css. Wenn Sie kein Stylesheet in den Editor laden möchten, lassen Sie dieses Feld leer.';

$_lang['setting_editor_css_selectors'] = 'CSS-Selektoren für den Editor';
$_lang['setting_editor_css_selectors_desc'] = '';

$_lang['setting_emailsender'] = 'E-Mail-Adresse';
$_lang['setting_emailsender_desc'] = 'Hier können Sie die E-Mail-Adresse angeben, die verwendet wird, wenn Benutzern ihre Benutzernamen und Passwörter zugeschickt werden.';
$_lang['setting_emailsender_err'] = 'Bitte geben Sie die Administrations-E-Mail-Adresse an.';

$_lang['setting_emailsubject'] = 'E-Mail-Betreff';
$_lang['setting_emailsubject_desc'] = 'Die Betreffzeile für die E-Mail, die standardmäßig nach Erstellung eines Accounts versendet wird.';
$_lang['setting_emailsubject_err'] = 'Bitte geben Sie die Betreffzeile für die E-Mail für neu erstellte Accounts an.';

$_lang['setting_error_page'] = 'Fehlerseite';
$_lang['setting_error_page_desc'] = 'Geben Sie die ID des Dokuments ein, das Benutzern angezeigt werden soll, wenn sie ein Dokument aufrufen, das nicht existiert. <strong>ACHTUNG: Stellen Sie sicher, dass die ID, die Sie eingeben, zu einem existierenden Dokument gehört und dass dieses veröffentlicht wurde!</strong>';
$_lang['setting_error_page_err'] = 'Bitte geben Sie eine Ressourcen-ID für die Fehlerseite an.';

$_lang['setting_failed_login_attempts'] = 'Fehlgeschlagene Login-Versuche';
$_lang['setting_failed_login_attempts_desc'] = 'Geben Sie an, wie viele fehlgeschlagene Login-Versuche erlaubt sein sollen, bevor der Benutzer geblockt wird.';

$_lang['setting_fe_editor_lang'] = 'Frontend-Editor-Sprache';
$_lang['setting_fe_editor_lang_desc'] = 'Wählen Sie eine Sprache aus, die im Editor benutzt werden soll, wenn er als Frontent-Editor (also innerhalb der eigentlichen Website) verwendet wird.';

$_lang['setting_feed_modx_news'] = 'URL des MODx-Newsfeeds';
$_lang['setting_feed_modx_news_desc'] = 'Geben Sie die URL des RSS-Feeds für das MODx-News-Fenster im Manager an.';

$_lang['setting_feed_modx_news_enabled'] = 'MODx-Newsfeed aktiviert';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Wenn diese Einstellung auf "Nein" gesetzt wird, wird der Newsfeed auf der Startseite des Managers nicht angezeigt.';

$_lang['setting_feed_modx_security'] = 'URL des MODx-Sicherheitshinweise-Feeds';
$_lang['setting_feed_modx_security_desc'] = 'Geben Sie die URL des RSS-Feeds für das MODx-Sicherheitshinweise-Fenster im Manager an.';

$_lang['setting_feed_modx_security_enabled'] = 'MODx-Sicherheitshinweise-Feed aktiviert';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Wenn diese Einstellung auf "Nein" gesetzt wird, wird der Sicherheitshinweise-Feed auf der Startseite des Managers nicht angezeigt.';

$_lang['setting_filemanager_path'] = 'Dateimanager-Pfad';
$_lang['setting_filemanager_path_desc'] = 'IIS setzt die Einstellung document_root, die vom Dateimanager verwendet wird, um festzulegen, was angezeigt wird, häufig nicht korrekt. Wenn Sie Probleme mit der Benutzung des Dateimanagers haben, stellen Sie sicher, dass dieser Pfad auf den Root Ihrer MODx-Installation zeigt.';
$_lang['setting_filemanager_path_err'] = 'Bitte geben Sie für den Dateimanager den absoluten Pfad zum Document-Root an.';
$_lang['setting_filemanager_path_err_invalid'] = 'Dieses Dateimanager-Verzeichnis existiert entweder nicht, oder es kann nicht darauf zugegriffen werden. Bitte geben Sie ein gültiges Verzeichnis an oder passen Sie die Rechte dieses Verzeichnisses an.';

$_lang['setting_friendly_alias_urls'] = 'Suchmaschinenfreundliche Aliasse benutzen';
$_lang['setting_friendly_alias_urls_desc'] = 'Wenn Sie suchmaschinenfreundliche URLs verwenden und die Ressource einen Alias hat, hat der Alias immer Vorrang vor der suchmaschinenfreundlichen URL. Wird diese Option auf "Ja" gesetzt, wird auch das Inhaltstyp-Suffix der Ressource auf den Alias angewendet. Wenn z.B. Ihre Ressource mit der ID 1 den Alias "einfuehrung" hat, und Sie haben als Inhaltstyp-Suffix ".html" eingestellt, wird das Setzen dieser Option auf "Ja" dazu führen, dass der Link "einfuehrung.html" generiert wird. Wenn es keinen Alias gibt, generiert MODx den Link "1.html".';

$_lang['setting_friendly_urls'] = 'Suchmaschinenfreundliche URLs benutzen';
$_lang['setting_friendly_urls_desc'] = 'Dies erlaubt Ihnen, suchmaschinenfreundliche URLs mit MODx zu verwenden. Bitte beachten Sie, dass dies nur für MODx-Installationen gilt, die auf einem Apache-Webserver laufen, und dass Sie eine .htaccess-Datei schreiben müssen, damit dies funktioniert. Mehr Informationen finden Sie in der .htaccess-Datei, die in der MODx-Distribution enthalten ist.';
$_lang['setting_friendly_urls_err'] = 'Bitte geben Sie an, ob Sie suchmaschinenfreundliche URLs verwenden möchten oder nicht.';

$_lang['setting_mail_charset'] = 'E-Mail-Zeichensatz';
$_lang['setting_mail_charset_desc'] = 'Legt den Standard-Zeichensatz (charset) für E-Mails fest, z.B. "iso-8859-1" oder "utf-8"';

$_lang['setting_mail_encoding'] = 'E-Mail-Codierung';
$_lang['setting_mail_encoding_desc'] = 'Legt die Codierung für die Nachricht fest. Optionen hierfür sind "8bit", "7bit", "binary", "base64" und "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'SMTP verwenden';
$_lang['setting_mail_use_smtp_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, wird MODx versuchen, SMTP in Mail-Funktionen zu verwenden.';

$_lang['setting_mail_smtp_auth'] = 'SMTP-Authentifizierung';
$_lang['setting_mail_smtp_auth_desc'] = 'Legt fest, ob eine SMTP-Authentifizierung stattfindet. Verwendet die Einstellungen mail_smtp_user und mail_smtp_password.';

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

$_lang['setting_manager_direction'] = 'Textrichtung im MODx-Manager';
$_lang['setting_manager_direction_desc'] = 'Geben Sie an, ob der Text im MODx-Manager von links nach rechts (Eingabe: "ltr") oder von rechts nach links (Eingabe: "rtl") ausgegeben werden soll.';

$_lang['setting_manager_date_format'] = 'Manager-Datumsformat';
$_lang['setting_manager_date_format_desc'] = 'Das Format für Datumsangaben im Manager. Diese Einstellung ist im gleichen Format vorzunehmen, wie es die PHP-Funktion <a href="http://de.php.net/manual/en/function.date.php" target="_blank">date()</a> erwartet.';

$_lang['setting_manager_time_format'] = 'Manager-Zeitformat';
$_lang['setting_manager_time_format_desc'] = 'Das Format für Uhrzeitangaben im Manager. Diese Einstellung ist im gleichen Format vorzunehmen, wie es die PHP-Funktion <a href="http://de.php.net/manual/en/function.date.php" target="_blank">date()</a> erwartet.';

$_lang['setting_manager_lang_attribute'] = 'HTML- und XML-Sprach-Attribute im Manager';
$_lang['setting_manager_lang_attribute_desc'] = 'Geben Sie den Code für die Sprache ein, der am besten zu der von Ihnen gewählten Sprache für den MODx-Manager passt. Dies stellt sicher, dass Ihr Browser den Inhalt im am besten für Sie geeigneten Format ausgeben kann.';

$_lang['setting_manager_language'] = 'Manager-Sprache';
$_lang['setting_manager_language_desc'] = 'Wählen Sie die Sprache für den MODx-Content-Manager.';

$_lang['setting_manager_login_start'] = 'Startseite für in den Manager eingeloggte Benutzer';
$_lang['setting_manager_login_start_desc'] = 'Geben Sie die ID des Dokuments ein, zu dem Sie den Benutzer weiterleiten möchten, nachdem er sich in den MODx-Manager eingeloggt hat. <strong>ACHTUNG: Stellen Sie sicher, dass die ID, die Sie eingeben, zu einem existierenden Dokument gehört, dass dieses veröffentlicht wurde und dass der Benutzer Zugriff darauf hat!</strong>';

$_lang['setting_manager_theme'] = 'Manager-Theme';
$_lang['setting_manager_theme_desc'] = 'Wählen Sie das Theme für den MODx-Manager.';

$_lang['setting_manager_use_tabs'] = 'Reiter im Layout des MODx-Managers verwenden';
$_lang['setting_manager_use_tabs_desc'] = 'Wird diese Einstellung auf "Ja" gesetzt, so werden Reiter für die Darstellung der Inhalte verwendet. Anderenfalls wird eine Portal-Darstellung verwendet.';

$_lang['setting_modRequest.class'] = 'Request-Handler-Klasse';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_charset'] = 'Zeichencodierung';
$_lang['setting_modx_charset_desc'] = 'Bitte wählen Sie die Zeichencodierung, die Sie im Manager verwenden möchten. Bitte beachten Sie, dass MODx zwar mit einigen dieser Codierungen getestet wurde, aber nicht mit allen. Für die meisten Sprachen ist die Standardeinstellung "UTF-8" vorzuziehen.';

$_lang['setting_new_file_permissions'] = 'Dateirechte für neue Dateien';
$_lang['setting_new_file_permissions_desc'] = 'Nach dem Hochladen einer neuen Datei im Dateimanager versucht dieser, die Dateirechte in die zu ändern, die in dieser Einstellung gespeichert sind. Dies könnte in einigen Konfigurationen evtl. nicht funktionieren, z.B. bei Verwendung des IIS-Webservers. In diesem Fall müssen Sie die Rechte selbst ändern.';

$_lang['setting_new_folder_permissions'] = 'Verzeichnisrechte für neue Verzeichnisse';
$_lang['setting_new_folder_permissions_desc'] = 'Nach dem Anlegen eines neuen Ordners im Dateimanager versucht dieser, die Verzeichnisrechte in die zu ändern, die in dieser Einstellung gespeichert sind. Dies könnte in einigen Konfigurationen evtl. nicht funktionieren, z.B. bei Verwendung des IIS-Webservers. In diesem Fall müssen Sie die Rechte selbst ändern.';

$_lang['setting_password_generated_length'] = 'Länge der automatisch generierten Passwörter';
$_lang['setting_password_generated_length_desc'] = 'Die Länge der automatisch für Benutzer generierten Passwörter';

$_lang['setting_password_min_length'] = 'Passwort-Mindestlänge';
$_lang['setting_password_min_length_desc'] = 'Die Mindestlänge für ein Benutzer-Passwort.';

$_lang['setting_proxy_auth_type'] = 'Proxy-Authentifizierungs-Typ';
$_lang['setting_proxy_auth_type_desc'] = 'Unterstützt entweder BASIC oder NTLM.';

$_lang['setting_proxy_host'] = 'Proxy-Host';
$_lang['setting_proxy_host_desc'] = 'Wenn Ihr Server einen Proxy verwendet, geben Sie hier den Hostnamen ein, um MODx-Features zu aktivieren, die den Proxy evtl. verwenden müssen, wie z. B. die Package-Verwaltung.';

$_lang['setting_proxy_password'] = 'Proxy-Passwort';
$_lang['setting_proxy_password_desc'] = 'Das Passwort, das benötigt wird, um sich beim Proxy-Server zu authentifizieren.';

$_lang['setting_proxy_port'] = 'Proxy-Port';
$_lang['setting_proxy_port_desc'] = 'Der Port für Ihren Proxy-Server.';

$_lang['setting_proxy_username'] = 'Proxy-Benutzername';
$_lang['setting_proxy_username_desc'] = 'Der Benutzername, der benötigt wird, um sich beim Proxy-Server zu authentifizieren.';

$_lang['setting_publish_default'] = 'Ressourcen standardmäßig veröffentlichen';
$_lang['setting_publish_default_desc'] = 'Wählen Sie "Ja", wenn alle neuen Ressourcen standardmäßig veröffentlicht werden sollen.';
$_lang['setting_publish_default_err'] = 'Bitte geben Sie an, ob neue Dokumente standardmäßig veröffentlicht werden sollen.';

$_lang['setting_rb_base_dir'] = 'Ressourcen-Pfad';
$_lang['setting_rb_base_dir_desc'] = 'Geben Sie den Serverpfad zum Ressourcen-Verzeichnis ein. Diese Einstellung wird normalerweise automatisch generiert. Wenn Sie einen IIS-Server verwenden, ist MODx möglicherweise nicht in der Lage, den Pfad selbst zu ermitteln, was zu einer Fehlermeldung im Ressourcen-Browser führt. In diesem Fall können Sie hier den Pfad zum Ressourcen-Verzeichnis eingeben (so, wie er im Windows-Explorer angezeigt wird). <strong>HINWEIS:</strong> Das Ressourcen-Verzeichnis muss die Unterverzeichnisse images/, files/, flash/ und media/ enthalten, damit der Ressourcen-Browser korrekt funktioniert.';
$_lang['setting_rb_base_dir_err'] = 'Bitte geben Sie das Basisverzeichnis für den Ressourcen-Browser an.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Dieses Ressourcen-Verzeichnis existiert entweder nicht, oder es kann nicht darauf zugegriffen werden. Bitte geben Sie ein gültiges Verzeichnis an oder passen Sie die Verzeichnisrechte dieses Verzeichnisses an.';

$_lang['setting_rb_base_url'] = 'Ressourcen-URL';
$_lang['setting_rb_base_url_desc'] = 'Geben Sie die URL des Ressourcen-Verzeichnisses ein. Diese Einstellung wird normalerweise automatisch generiert. Wenn Sie einen IIS-Server verwenden, ist MODx möglicherweise nicht in der Lage, den Pfad selbst zu ermitteln, was zu einer Fehlermeldung im Ressourcen-Browser führt. In diesem Fall können Sie hier die URL des Ressourcen-Verzeichnisses eingeben (so, wie Sie Sie im Internet Explorer eingeben würden).';
$_lang['setting_rb_base_url_err'] = 'Bitte geben Sie die Basis-URL für den Ressourcen-Browser an.';

$_lang['setting_request_controller'] = 'Dateiname des Request-Controllers';
$_lang['setting_request_controller_desc'] = 'Der Dateiname des Haupt-Request-Controllers, von dem aus MODx geladen wird. Die meisten Benutzer können hier "index.php" eingestellt lassen.';

$_lang['setting_request_param_alias'] = 'Request-Alias-Parameter';
$_lang['setting_request_param_alias_desc'] = 'Der Name des GET-Parameters für Ressourcen-Aliasse, wenn eine Weiterleitung mittels suchmaschinenfreundlicher URLs stattfindet.';

$_lang['setting_request_param_id'] = 'Request-ID-Parameter';
$_lang['setting_request_param_id_desc'] = 'Der Name des GET-Parameters für Ressourcen-IDs, wenn keine suchmaschinenfreundlichen URLs verwendet werden.';

$_lang['setting_resolve_hostnames'] = 'Hostnamen auflösen';
$_lang['setting_resolve_hostnames_desc'] = 'Möchten Sie, dass MODx versucht, die Hostnamen Ihrer Besucher aufzulösen, wenn diese Ihre Website besuchen? Das Auflösen von Hostnamen kann zusätzliche Server-Last erzeugen; Ihre Besucher werden dies im Normalfall jedoch nicht bemerken.';

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
$_lang['setting_session_cookie_domain_desc'] = 'Verwenden Sie diese Einstellung, um die Session-Cookie-Somain anzupassen.';

$_lang['setting_session_cookie_lifetime'] = 'Session-Cookie-Lebensdauer';
$_lang['setting_session_cookie_lifetime_desc'] = 'Verwenden Sie diese Einstellung, um die Session-Cookie-Lebensdauer anzupassen (in Sekunden). Diese gibt an, wie lange ein Session-Cookie gültig ist, wenn die Login-Option "An mich erinnern" gewählt wurde. Standardeinstellung ist "604800" (= 7 Tage).';

$_lang['setting_session_cookie_path'] = 'Session-Cookie-Pfad';
$_lang['setting_session_cookie_path_desc'] = 'Verwenden Sie diese Einstellung, um den Cookie-Pfad anzupassen. Damit kann genau festgelegt werden, wo innerhalb einer Site ein Cookie gültig ist und wo nicht.';

$_lang['setting_session_cookie_secure'] = 'Sichere Session-Cookies';
$_lang['setting_session_cookie_secure_desc'] = 'Setzen Sie diese Einstellung auf "Ja", um sichere Session-Cookies zu verwenden. Diese werden ausschließlich SSL-geschützt übertragen.';

$_lang['setting_session_handler_class'] = 'Name der Session-Handler-Klasse';
$_lang['setting_session_handler_class_desc'] = 'Für datenbankgestützte Sessions verwenden Sie bitte "modSessionHandler". Lassen Sie dieses Feld leer, um die Standard-PHP-Sessionverwaltung zu verwenden.';

$_lang['setting_session_name'] = 'Session-Name';
$_lang['setting_session_name_desc'] = 'Verwenden Sie diese Einstellung, um den Session-Namen für die Sessions in MODx anzupassen.';

$_lang['setting_settings_version'] = 'MODx-Version';
$_lang['setting_settings_version_desc'] = 'Die aktuell verwendete Version von MODx Revolution.';

$_lang['setting_set_header'] = 'HTTP-Header setzen';
$_lang['setting_set_header_desc'] = 'Wenn diese Einstellung aktiviert ist, versucht MODx, die HTTP-Header für Ressourcen zu setzen.';

$_lang['setting_signupemail_message'] = 'E-Mail nach Account-Erstellung';
$_lang['setting_signupemail_message_desc'] = 'Hier können Sie die Nachricht eingeben, die an einen Benutzer gesendet wird, wenn Sie einen Account für ihn erstellen und MODx ihm eine E-Mail senden lassen, die seinen Benutzernamen und sein Passwort enthält.<br /><strong>Hinweis:</strong> Die folgenden Platzhalter werden vom System ersetzt, wenn eine Nachricht versendet wird:<br /><br />[[+sname]] - Name Ihrer Website,<br />[[+saddr]] - E-Mail-Adresse ihrer Website (bzw. des Webmasters),<br />[[+surl]] - URL Ihrer Website,<br />[[+uid]] - Benutzername oder ID des Benutzers,<br />[[+pwd]] - Passwort des Benutzers,<br />[[+ufn]] - Vollständiger Name des Benutzers.<br /><br /><strong>Achten Sie darauf, dass zumindest [[+uid]] und [[+pwd]] in der E-Mail enthalten sind, da sonst der Benutzername und das Passwort nicht mit der Mail versendet werden und Ihre Benutzer folglich ihre Zugangsdaten nicht kennen!</strong>';
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
$_lang['setting_strip_image_paths_desc'] = 'Wenn Sie diese Einstellung auf "Nein" setzen, wird MODx Dateibrowser-Ressourcen-Quellen (Bilder, Dateien, Flash-Animationen etc.) als absolute URLs speichern. Relative URLs dagegen sind hilfreich, wenn Sie Ihre MODx-Installation verschieben möchten, z. B. von einer Testsite zu einer produktiven Website. Falls Ihnen nicht klar ist, was das bedeutet, belassen Sie es am besten bei der Einstellung "Ja".';

$_lang['setting_tree_root_id'] = 'Ressourcen-Baum-Basis-ID';
$_lang['setting_tree_root_id_desc'] = 'Geben Sie hier eine gültige ID einer Ressource ein, um den Ressourcen-Baum links bei dieser Ressource als Basis beginnen zu lassen. Benutzer können dann nur Ressourcen sehen, die Kinder der angegebenen Ressource sind.';

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

$_lang['setting_webpwdreminder_message'] = 'E-Mail nach Passwort-Anforderung';
$_lang['setting_webpwdreminder_message_desc'] = 'Hier können Sie die Nachricht eingeben, die an einen Benutzer gesendet wird, wenn er eine neues Passwort anfordert. Der MODx-Manager sendet eine E-Mail an den Benutzer, die dessen neues Passwort und Aktivierungs-Informationen enthält.<br /><strong>Hinweis:</strong> Die folgenden Platzhalter werden vom System ersetzt, wenn eine Nachricht versendet wird:<br /><br />[[+sname]] - Name Ihrer Website,<br />[[+saddr]] - E-Mail-Adresse ihrer Website (bzw. des Webmasters),<br />[[+surl]] - URL Ihrer Website,<br />[[+uid]] - Benutzername oder ID des Benutzers,<br />[[+pwd]] - Passwort des Benutzers,<br />[[+ufn]] - Vollständiger Name des Benutzers.<br /><br /><strong>Achten Sie darauf, dass zumindest [[+uid]] und [[+pwd]] in der E-Mail enthalten sind, da sonst der Benutzername und das Passwort nicht mit der Mail versendet werden und Ihre Benutzer folglich ihre Zugangsdaten nicht kennen!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Hallo [[+uid]],\n\num Ihr neues Passwort zu aktivieren, klicken Sie bitte auf den folgenden Link:\n\n[[+surl]]\n\nNach erfolgreicher Aktivierung können Sie folgendes Passwort verwenden, um sich einzuloggen:\n\nPasswort:[[+pwd]]\n\nFalls Sie diese E-Mail nicht angefordert haben sollten, ignorieren Sie sie bitte einfach.\n\nMit freundlichen Grüßen,\nIhr Website-Administrator';

$_lang['setting_websignupemail_message'] = 'E-Mail nach Website-Account-Erstellung';
$_lang['setting_websignupemail_message_desc'] = 'Hier können Sie die Nachricht eingeben, die an einen Benutzer gesendet wird, wenn Sie einen Website-Account für ihn erstellen und MODx ihm eine E-Mail senden lassen, die seinen Benutzernamen und sein Passwort enthält.<br /><strong>Hinweis:</strong> Die folgenden Platzhalter werden vom System ersetzt, wenn eine Nachricht versendet wird:<br /><br />[[+sname]] - Name Ihrer Website,<br />[[+saddr]] - E-Mail-Adresse ihrer Website (bzw. des Webmasters),<br />[[+surl]] - URL Ihrer Website,<br />[[+uid]] - Benutzername oder ID des Benutzers,<br />[[+pwd]] - Passwort des Benutzers,<br />[[+ufn]] - Vollständiger Name des Benutzers.<br /><br /><strong>Achten Sie darauf, dass zumindest [[+uid]] und [[+pwd]] in der E-Mail enthalten sind, da sonst der Benutzername und das Passwort nicht mit der Mail versendet werden und Ihre Benutzer folglich ihre Zugangsdaten nicht kennen!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hallo [[+uid]],\n\nanbei erhalten Sie Ihre Zugangsdaten für [[+sname]]:\n\nBenutzername: [[+uid]]\nPasswort: [[+pwd]]\n\nSobald Sie sich in [[+sname]] unter [[+surl]] eingeloggt haben, können Sie Ihr Passwort ändern.\n\nMit freundlichen Grüßen,\nIhr Website-Administrator';

$_lang['setting_welcome_screen'] = 'Willkommens-Bildschirm anzeigen';
$_lang['setting_welcome_screen_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt ist, wird der Willkommens-Bildschirm beim nächsten erfolgreichen Laden der Manager-Startseite einmalig angezeigt, danach nicht mehr.';

$_lang['setting_which_editor'] = 'Zu verwendender Editor';
$_lang['setting_which_editor_desc'] = 'Hier können Sie festlegen, welchen Rich-Text-Editor Sie verwenden möchten. Sie können zusätzliche Rich-Text-Editoren von der MODx-Website (Extras > RichtextEditors) herunterladen und dann installieren.';

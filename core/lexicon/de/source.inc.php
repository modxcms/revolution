<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Zugriffsrechte';
$_lang['base_path'] = 'Basispfad';
$_lang['base_path_relative'] = 'Basispfad relativ?';
$_lang['base_url'] = 'Basis-URL';
$_lang['base_url_relative'] = 'Basis-URL relativ?';
$_lang['minimum_role'] = 'Mindestens benötigte Rolle';
$_lang['path_options'] = 'Pfad-Optionen';
$_lang['policy'] = 'Zugriffs-Richtlinie';
$_lang['source'] = 'Medienquelle';
$_lang['source_access_add'] = 'Benutzergruppe hinzufügen';
$_lang['source_access_remove'] = 'Zugriff löschen';
$_lang['source_access_remove_confirm'] = 'Sind Sie sicher, dass Sie den Zugriff dieser Benutzergruppe auf diese Quelle entfernen möchten?';
$_lang['source_access_update'] = 'Zugriff bearbeiten';
$_lang['source_description_desc'] = 'Eine kurze Beschreibung der Medienquelle.';
$_lang['source_err_ae_name'] = 'Eine Medienquelle mit diesem Namen existiert bereits! Bitte geben Sie einen neuen Namen an.';
$_lang['source_err_nf'] = 'Medienquelle nicht gefunden!';
$_lang['source_err_init'] = 'Konnte die Medienquelle "[[+source]]" nicht initialisieren!';
$_lang['source_err_nfs'] = 'Es kann keine Medienquelle mit der ID [[+id]] gefunden werden.';
$_lang['source_err_ns'] = 'Bitte geben Sie die Medienquelle an.';
$_lang['source_err_ns_name'] = 'Bitte geben Sie einen Namen für die Medienquelle an.';
$_lang['source_name_desc'] = 'Der Name der Medienquelle.';
$_lang['source_properties.intro_msg'] = 'Verwalten Sie nachstehend die Eigenschaften dieser Medienquelle.';
$_lang['source_remove_confirm'] = 'Sind Sie sicher, dass Sie diese Medienquelle löschen möchten? Dadurch könnten alle TVs, die Sie dieser Quelle zugeordnet haben, unbrauchbar werden.';
$_lang['source_remove_multiple_confirm'] = 'Sind Sie sicher, dass Sie diese Medienquellen löschen möchten? Dadurch könnten alle Template-Variablen, die Sie diesen Quellen zugeordnet haben, unbrauchbar werden.';
$_lang['source_type'] = 'Quellen-Typ';
$_lang['source_type_desc'] = 'Der Typ – oder Treiber – der Medienquelle. Die Quelle verwendet diesen Treiber, um eine Verbindung herzustellen, wenn sie ihre Daten abruft. Beispiele: "Dateisystem" holt Dateien vom Dateisystem; "S3" holt Dateien von einem S3-Bucket.';
$_lang['source_type.file'] = 'Dateisystem';
$_lang['source_type.file_desc'] = 'Eine Dateisystem-basierte Quelle, die Zugriff auf Dateien Ihres Servers ermöglicht.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Ermöglicht Zugriff auf einen Amazon-S3-Bucket.';
$_lang['source_type.ftp'] = 'File Transfer Protocol';
$_lang['source_type.ftp_desc'] = 'Navigiert einen remote FTP-Server.';
$_lang['source_types'] = 'Quellen-Typen';
$_lang['source_types.intro_msg'] = 'Dies ist eine Liste aller installierten Medienquellen-Typen in dieser MODX-Instanz.';
$_lang['source.access.intro_msg'] = 'Hier können Sie den Zugriff auf eine Medienquelle auf bestimmte Benutzergruppen einschränken und Zugriffs-Richtlinien für diese Benutzergruppen zuordnen. Eine Medienquelle, der keine Benutzergruppen zugeordnet wurden, ist für alle Manager-Benutzer verfügbar.';
$_lang['sources'] = 'Medienquellen';
$_lang['sources.intro_msg'] = 'Verwalten Sie hier alle Ihre Medienquellen.';
$_lang['user_group'] = 'Benutzergruppe';

/* file source type */
$_lang['allowedFileTypes'] = 'Erlaubte Dateitypen';
$_lang['prop_file.allowedFileTypes_desc'] = 'Wenn hier etwas eingegeben wird, wird die Anzeige von Dateien auf diejenigen mit den angegebenen Dateiendungen beschränkt. Bitte geben Sie eine kommaseparierte Liste von Dateiendungen ein, ohne den Punkt vor der Endung.';
$_lang['basePath'] = 'Basispfad';
$_lang['prop_file.basePath_desc'] = 'Der Dateipfad, auf den die Medienquelle verweist, zum Beispiel: assets/images/<br>Der Pfad kann vom Parameter "basePathRelative" abhängen';
$_lang['basePathRelative'] = 'Basispfad relativ zum MODX-Installationspfad';
$_lang['prop_file.basePathRelative_desc'] = 'Wenn die Basispfad-Einstellung oben nicht relativ zum MODX-Installationspfad angegeben wurde, setzen Sie diese Einstellung auf "Nein".';
$_lang['baseUrl'] = 'Basis-URL';
$_lang['prop_file.baseUrl_desc'] = 'Die URL, von der aus auf diese Medienquelle zugegriffen werden kann, zum Beispiel: assets/images/<br>Die Url kann vom Parameter "baseUrlRelative" abhängen';
$_lang['baseUrlPrependCheckSlash'] = 'Basis-URL nur voranstellen, wenn URL nicht mit / beginnt';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Wenn diese Einstellung auf "Ja" gesetzt wird, wird MODX der URL nur dann die Basis-URL (baseUrl) voranstellen, wenn kein Slash (/) am Anfang der URL gefunden wird, wenn die TV verarbeitet wird. Dies ist nützlich, um einen TV-Wert außerhalb der Basis-URL einstellen zu können.';
$_lang['baseUrlRelative'] = 'Basis-URL relativ zur MODX-Installations-URL';
$_lang['prop_file.baseUrlRelative_desc'] = 'Wenn die Basis-URL-Einstellung oben nicht relativ zum MODX-Installations-URL angegeben wurde, setzen Sie diese Einstellung auf "Nein".';
$_lang['imageExtensions'] = 'Bild-Dateiendungen';
$_lang['prop_file.imageExtensions_desc'] = 'Eine kommaseparierte Liste von Dateiendungen, deren zugehörige Dateien als Bilder verwendet werden sollen. MODX wird versuchen, Thumbnails für Dateien mit diesen Endungen zu generieren.';
$_lang['skipFiles'] = 'Versteckte Dateien und Ordner';
$_lang['prop_file.skipFiles_desc'] = 'Eine kommaseparierte Liste. MODX versteckt Dateien und Ordner, die auf einen Eintrag dieser Liste passen.';
$_lang['thumbnailQuality'] = 'Thumbnail-Qualität';
$_lang['prop_file.thumbnailQuality_desc'] = 'Die Qualität der generierten Thumbnails, auf einer Skala von 0 bis 100.';
$_lang['thumbnailType'] = 'Thumbnail-Typ';
$_lang['prop_file.thumbnailType_desc'] = 'Der Bildtyp der generierten Thumbnails.';
$_lang['prop_file.visibility_desc'] = 'Standard-Sichtbarkeit für neue Dateien und Ordner.';
$_lang['no_move_folder'] = 'Der Medienquellen-Treiber unterstützt derzeit kein Verschieben von Ordnern.';

/* s3 source type */
$_lang['bucket'] = 'Bucket';
$_lang['prop_s3.bucket_desc'] = 'Der S3-Bucket, aus dem Ihre Daten geladen werden.';
$_lang['prop_s3.key_desc'] = 'Der Amazon-Benutzername (Access Key ID) für den Zugriff auf den Bucket.';
$_lang['prop_s3.imageExtensions_desc'] = 'Eine kommaseparierte Liste von Dateiendungen, deren zugehörige Dateien als Bilder verwendet werden sollen. MODX wird versuchen, Thumbnails für Dateien mit diesen Endungen zu generieren.';
$_lang['prop_s3.secret_key_desc'] = 'Das Amazon-Passwort (Secret Access Key) für den Zugriff auf den Bucket.';
$_lang['prop_s3.skipFiles_desc'] = 'Eine kommaseparierte Liste. MODX versteckt Dateien und Ordner, die auf einen Eintrag dieser Liste passen.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'Die Qualität der generierten Thumbnails, auf einer Skala von 0 bis 100.';
$_lang['prop_s3.thumbnailType_desc'] = 'Der Bildtyp der generierten Thumbnails.';
$_lang['prop_s3.url_desc'] = 'Die URL der Amazon-S3-Instanz.';
$_lang['prop_s3.endpoint_desc'] = 'Alternative S3-kompatible Endpunkt-URL, z.B. "https://s3.<region>.example.com". Lesen Sie in der Dokumentation Ihres S3-kompatiblen Anbieters nach, wo sich der Endpunkt befindet. Lassen Sie den Eintrag für Amazon S3 leer.';
$_lang['prop_s3.region_desc'] = 'Region des Amazon-S3-Buckets. Beispiel: us-west-1';
$_lang['prop_s3.prefix_desc'] = 'Optionales Pfad-/Ordner-Präfix';
$_lang['s3_no_move_folder'] = 'Der S3-Treiber unterstützt das Verschieben von Ordnern zu diesem Zeitpunkt nicht.';

/* ftp source type */
$_lang['prop_ftp.host_desc'] = 'Server-Hostname oder IP-Adresse';
$_lang['prop_ftp.username_desc'] = 'Benutzername für die Authentifizierung. Kann "anonymous" sein.';
$_lang['prop_ftp.password_desc'] = 'Passwort des Benutzers. Für anonyme Benutzer leer lassen.';
$_lang['prop_ftp.url_desc'] = 'Wenn dieser FTP eine öffentliche URL hat, können Sie hier seine öffentliche http-Adresse eingeben. Dies ermöglicht auch eine Bildvorschau im Media-Browser.';
$_lang['prop_ftp.port_desc'] = 'Port des Servers, Standard ist 21.';
$_lang['prop_ftp.root_desc'] = 'Der Root-Ordner. Er wird nach der Verbindungsherstellung geöffnet';
$_lang['prop_ftp.passive_desc'] = 'Aktivieren oder deaktivieren Sie den passiven FTP-Modus';
$_lang['prop_ftp.ssl_desc'] = 'SSL-Verbindung aktivieren oder deaktivieren';
$_lang['prop_ftp.timeout_desc'] = 'Timeout für die Verbindung in Sekunden.';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

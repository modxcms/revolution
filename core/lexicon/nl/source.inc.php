<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Toegangsrechten';
$_lang['base_path'] = 'Standaard Pad';
$_lang['base_path_relative'] = 'Standaard Pad Relatief?';
$_lang['base_url'] = 'Standaard URL';
$_lang['base_url_relative'] = 'Standaard URL Relatief?';
$_lang['minimum_role'] = 'Minimale Rol';
$_lang['path_options'] = 'Pad Opties';
$_lang['policy'] = 'Toegangsbeleid';
$_lang['source'] = 'Mediabron';
$_lang['source_access_add'] = 'Gebruikersgroep toevoegen';
$_lang['source_access_remove'] = 'Toegang Verwijderen';
$_lang['source_access_remove_confirm'] = 'Weet je zeker dat je de toegang tot deze Media Source voor deze Gebruikersgroep wilt verwijderen?';
$_lang['source_access_update'] = 'Verander Toegang';
$_lang['source_create'] = 'Nieuwe Mediabron';
$_lang['source_description_desc'] = 'Een korte beschrijving van de Media Source.';
$_lang['source_duplicate'] = 'Dupliceer Media Source';
$_lang['source_err_ae_name'] = 'Er bestaat al een Media Source met die naam! Probeer het opnieuw met een andere naam.';
$_lang['source_err_nf'] = 'Media Source niet gevonden!';
$_lang['source_err_init'] = 'Could not initialize "[[+source]]" Media Source!';
$_lang['source_err_nfs'] = 'Geen Media Source gevonden met id: [[+id]].';
$_lang['source_err_ns'] = 'Kies alsjeblieft een Media Source.';
$_lang['source_err_ns_name'] = 'Specificeer een naam voor de Media Source.';
$_lang['source_name_desc'] = 'De naam van de Media Source.';
$_lang['source_properties.intro_msg'] = 'Beheer de properties van deze Media Source hieronder.';
$_lang['source_remove'] = 'Verwijder Media Source';
$_lang['source_remove_confirm'] = 'Weet je zeker dat je deze Media Source wilt verwijderen? Dit kan TVs welke deze Source gebruiken in de war brengen.';
$_lang['source_remove_multiple'] = 'Verwijder Meerdere Media Sources';
$_lang['source_remove_multiple_confirm'] = 'Weet je zeker dat je deze Media Sources wilt verwijderen? Dit kan TVs welke deze Sources gebruiken in de war brengen.';
$_lang['source_update'] = 'Bewerk Media Source';
$_lang['source_type'] = 'Brontype';
$_lang['source_type_desc'] = 'Het Source type, ook de Driver genoemd, van de Media Source. De Source zal deze Driver gebruiken om met de service te verbinden voor data weergave. "Bestandsserver" zal bestanden van de server laden, terwijl de "Amazon S3" Driver verbind met een Amazon S3 Bucket.';
$_lang['source_type.file'] = 'Bestandssysteem';
$_lang['source_type.file_desc'] = 'Een bestandsserver gebaseerde Source welke de bestanden op je server weergeeft.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Geeft een Amazon S3 bucket weer.';
$_lang['source_type.ftp'] = 'File Transfer Protocol';
$_lang['source_type.ftp_desc'] = 'Navigates an FTP remote server.';
$_lang['source_types'] = 'Brontypes';
$_lang['source_types.intro_msg'] = 'Dit is een lijst van de geïnstalleerde Media bron types.';
$_lang['source.access.intro_msg'] = 'Hier kan je aangeven welke Gebruikersgroepen een Media Source kan gebruiken aan de hand van een toegangsbeleid. Een Media Source zonder geässocieerde Gebruikersgroepen is beschikbaar voor alle Manager gebruikers.';
$_lang['sources'] = 'Mediabronnen';
$_lang['sources.intro_msg'] = 'Beheer je media bronnen.';
$_lang['user_group'] = 'Gebruikersgroep';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'Wanneer ingevuld zullen de bestanden gefilterd worden zodat alleen deze extensies worden weergegeven. Vul in als komma gescheiden lijst, zonder de punt.';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'Het bestandspad waar de Source moet beginnen.';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Als het bovenstaande pad niet relatief maar absoluut is, moet je dit op "Nee" zetten. ';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'De URL waarvan deze bron benaderd kan worden in de browser.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Als dit waar is zal MODX alleen de baseUrl voor het gekozen pad zetten als er geen forward slash (/) is gevonden aan het begin van de URL. Kan gebruikt worden om een TV waarde te zetten buiten de baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'Als de bovenstaande URL niet relatief maar absoluut is, moet je dit op "Nee" zetten.';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'Een komma gescheiden lijst van bestandsextensies om als afbeelding te gebruiken. MODX zal proberen van bestanden met deze extensies thumbnails te maken.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'Een komma gescheiden lijst van bestanden en mappen welke MODX moet overslaan en verbergen.';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'De kwaliteit van de getoonde thumbnails, tusssen 1 en 100 waarbij 100 de hoogste kwaliteit is.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'Afbeeldingstype om de thumbnails in te maken.';
$_lang['prop_file.visibility_desc'] = 'Default visibility for new files and folders.';
$_lang['no_move_folder'] = 'The Media Source driver does not support moving of folders at this time.';

/* s3 source type */
$_lang['bucket'] = 'Bucket';
$_lang['prop_s3.bucket_desc'] = 'De S3 Bucket om data van te laden.';
$_lang['prop_s3.key_desc'] = 'De Amazon Key om te gebruiken in authenticatie.';
$_lang['prop_s3.imageExtensions_desc'] = 'Een komma gescheiden lijst van bestandsextensies om als afbeelding te gebruiken. MODX zal proberen van bestanden met deze extensies thumbnails te maken.';
$_lang['prop_s3.secret_key_desc'] = 'De Amazon geheime key voor bucket authenticatie.';
$_lang['prop_s3.skipFiles_desc'] = 'Een komma gescheiden lijst van bestanden en mappen welke MODX moet overslaan en verbergen.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'De kwaliteit van de getoonde thumbnails, tusssen 1 en 100 waarbij 100 de hoogste kwaliteit is.';
$_lang['prop_s3.thumbnailType_desc'] = 'Afbeeldingstype om de thumbnails in te maken.';
$_lang['prop_s3.url_desc'] = 'De URL van de Amazon S3 instantie.';
$_lang['prop_s3.region_desc'] = 'Regio van de bucket. Voorbeeld: us-west-1';
$_lang['prop_s3.prefix_desc'] = 'Optional path/folder prefix';
$_lang['s3_no_move_folder'] = 'De S3 driver ondersteund op dit moment het verplaatsen van mappen niet.';

/* ftp source type */
$_lang['prop_ftp.host_desc'] = 'Server hostname or IP address';
$_lang['prop_ftp.username_desc'] = 'Username for authentication. Can be "anonymous".';
$_lang['prop_ftp.password_desc'] = 'Password of user. Leave empty for anonymous user.';
$_lang['prop_ftp.url_desc'] = 'If this FTP is has a public URL, you can enter its public http-address here. This will also enable image previews in the media browser.';
$_lang['prop_ftp.port_desc'] = 'Port of the server, default is 21.';
$_lang['prop_ftp.root_desc'] = 'The root folder, it will be opened after connection';
$_lang['prop_ftp.passive_desc'] = 'Enable or disable passive ftp mode';
$_lang['prop_ftp.ssl_desc'] = 'Enable or disable ssl connection';
$_lang['prop_ftp.timeout_desc'] = 'Timeout for connection in seconds.';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

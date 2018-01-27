<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Åtkomstinställningar';
$_lang['base_path'] = 'Bassökväg';
$_lang['base_path_relative'] = 'Relativ bassökväg?';
$_lang['base_url'] = 'Bas-URL';
$_lang['base_url_relative'] = 'Relativ bas-URL?';
$_lang['minimum_role'] = 'Minimiroll';
$_lang['path_options'] = 'Sökvägsalternativ';
$_lang['policy'] = 'Policy';
$_lang['source'] = 'Mediakälla';
$_lang['source_access_add'] = 'Lägg till användargrupp';
$_lang['source_access_remove'] = 'Ta bort tillgång';
$_lang['source_access_remove_confirm'] = 'Är du säker på att du vill ta bort tillgång till denna källa för denna användargrupp?';
$_lang['source_access_update'] = 'Uppdatera tillgång';
$_lang['source_create'] = 'Skapa ny mediakälla';
$_lang['source_description_desc'] = 'En kort beskrivning av mediakällan.';
$_lang['source_duplicate'] = 'Duplicera mediakälla';
$_lang['source_err_ae_name'] = 'Det finns redan en mediakälla med det namnet! Ange ett annat namn.';
$_lang['source_err_nf'] = 'Mediakällan kunde inte hittas!';
$_lang['source_err_nfs'] = 'Kan inte hitta mediakällan med id: [[+id]].';
$_lang['source_err_ns'] = 'Ange mediakällan.';
$_lang['source_err_ns_name'] = 'Ange ett namn för mediakällan.';
$_lang['source_name_desc'] = 'Mediakällans namn';
$_lang['source_properties.intro_msg'] = 'Hantera egenskaperna för denna källa nedan.';
$_lang['source_remove'] = 'Ta bort mediakälla';
$_lang['source_remove_confirm'] = 'Är du säker på att du vill ta bort denna mediakälla? Detta kan ha sönder mallvariabler som du har tilldelat till denna källa.';
$_lang['source_remove_multiple'] = 'Ta bort flera mediakällor';
$_lang['source_remove_multiple_confirm'] = 'Är du säker på att du vill ta bort dessa mediakällor? Detta kan ha sönder mallvariabler som du har tilldelat till dessa källor.';
$_lang['source_update'] = 'Uppdatera mediakälla';
$_lang['source_type'] = 'Källtyp';
$_lang['source_type_desc'] = 'Mediakällans typ eller drivrutin. Källan kommer att använda denna drivrutin för att ansluta när den hämtar sin data. Till exempel: Filsystem hämtar filer från filsystemet. S3 hämtar filer från en S3-hink.';
$_lang['source_type.file'] = 'Filsystem';
$_lang['source_type.file_desc'] = 'En filsystembaserad källa som navigerar bland din servers filer.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Navigerar en Amazon S3-hink.';
$_lang['source_types'] = 'Källtyper';
$_lang['source_types.intro_msg'] = 'Det här är en lista med alla de installerade typer av mediakällor som du har i denna MODX-instans.';
$_lang['source.access.intro_msg'] = 'Här kan du begränsa en mediakälla till specifika användargrupper och ange policyer för dessa användargrupper. En mediakälla som inte är ihopkopplad med några användargrupper är tillgänglig för alla användare av hanteraren.';
$_lang['sources'] = 'Mediakällor';
$_lang['sources.intro_msg'] = 'Hantera alla dina mediakällor här.';
$_lang['user_group'] = 'Användargrupp';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'Om denna aktiveras så kommer den att begränsa de filer som visas till endast de filsuffix som anges här. Skriv som en kommaseparerad lista utan punkter.';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'Den filsökväg som källan ska pekas mot.';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Om bassökvägen ovan inte är relativ till installationssökvägen för MODX, så sätter du den här till Nej.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'Den URL som denna källa kan kommas åt från.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Om denna sätts till "Ja" kommer MODX bara att lägga till baseURL i början av sökvägen om inget snedstreck (/) finns i början av URL:en när en mallvariabel ska visas. Det här är användbart om du vill ange ett värde på en mallvariabel som ligger utanför baseURL.';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'Om inställningen för rot-URL ovan inte är relativ till MODX installations-URL sätter du denna till "Nej".';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'En kommaseparerad lista med de filsuffix som ska användas som bilder. MODX kommer att försöka göra tumnaglar av filer med dessa suffix.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'En kommaseparerad lista. MODX kommer att hoppa över och gömma filer och mappar som matchar någon av dessa.';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'Kvalitén på de renderade tumnaglarna på en skala från 0-100.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'Den bildtyp som tumnaglarna ska renderas som.';

/* s3 source type */
$_lang['bucket'] = 'Hink';
$_lang['prop_s3.bucket_desc'] = 'Den S3-hink som din data ska laddas från.';
$_lang['prop_s3.key_desc'] = 'Amazons nyckel som ska användas för autentisering till hinken.';
$_lang['prop_s3.imageExtensions_desc'] = 'En kommaseparerad lista med de filsuffix som ska användas som bilder. MODX kommer att försöka göra tumnaglar av filer med dessa suffix.';
$_lang['prop_s3.secret_key_desc'] = 'Amazons hemliga nyckel som ska användas för autentisering till hinken.';
$_lang['prop_s3.skipFiles_desc'] = 'En kommaseparerad lista. MODX kommer att hoppa över och gömma filer och mappar som matchar någon av dessa.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'Kvalitén på de renderade tumnaglarna på en skala från 0-100.';
$_lang['prop_s3.thumbnailType_desc'] = 'Den bildtyp som tumnaglarna ska renderas som.';
$_lang['prop_s3.url_desc'] = 'URL:en för Amazon S3-instansen.';
$_lang['s3_no_move_folder'] = 'S3-drivrutinen stödjer än så länge inte flyttning av mappar.';
$_lang['prop_s3.region_desc'] = 'Hinkens region, exempel: us-west-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

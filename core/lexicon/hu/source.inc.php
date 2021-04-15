<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Hozzáférési engedélyek';
$_lang['base_path'] = 'Alap elérési út';
$_lang['base_path_relative'] = 'Alap elérési út viszonylagos?';
$_lang['base_url'] = 'Alap webcím';
$_lang['base_url_relative'] = 'Alap webcím viszonylagos?';
$_lang['minimum_role'] = 'Legkisebb szükséges szerep';
$_lang['path_options'] = 'Elérési út beállításai';
$_lang['policy'] = 'Házirend';
$_lang['source'] = 'Médiaforrás';
$_lang['source_access_add'] = 'Felhasználócsoport hozzáadása';
$_lang['source_access_remove'] = 'Hozzáférés eltávolítása';
$_lang['source_access_remove_confirm'] = 'Biztosan eltávolítja ennek a felhasználói csoportnak a hozzáférését ehhez a forráshoz?';
$_lang['source_access_update'] = 'Hozzáférés frissítése';
$_lang['source_create'] = 'Új médiaforrás létrehozása';
$_lang['source_description_desc'] = 'A médiaforrás rövid leírása.';
$_lang['source_duplicate'] = 'A médiaforrás kettőzése';
$_lang['source_err_ae_name'] = 'Már létezik ezzel a névvel médiaforrás! Kérjük, adjon meg másik nevet.';
$_lang['source_err_nf'] = 'A médiaforrás nem található!';
$_lang['source_err_nfs'] = 'Nem található médiaforrás [[+id]] azonosítóval.';
$_lang['source_err_ns'] = 'Kérjük, adja meg a médiaforrást.';
$_lang['source_err_ns_name'] = 'Kérjük, adja meg a médiaforrás nevét.';
$_lang['source_name_desc'] = 'A médiaforrás neve.';
$_lang['source_properties.intro_msg'] = 'Alább kezelheti a forrás tulajdonságait.';
$_lang['source_remove'] = 'A médiaforrás törlése';
$_lang['source_remove_confirm'] = 'Biztosan eltávolítja ezt a médiaforrást? Használhatatlan lehet bármelyik sablonváltozó, amit ehhez az erőforráshoz rendelt.';
$_lang['source_remove_multiple'] = 'Több médiaforrás törlése.';
$_lang['source_remove_multiple_confirm'] = 'Biztosan törli ezeket a médiaforrásokat? Használhatatlan lehet bármelyik sablonváltozó, amit ezekhez az erőforrásokhoz rendelt.';
$_lang['source_update'] = 'Médiaforrás módosítása';
$_lang['source_type'] = 'Forrásfajta';
$_lang['source_type_desc'] = 'A médiaforrás fajtája vagy meghajtója. A forrás ezt a meghajtót használja a kapcsolódáshoz, amikor összegyűjti az adatokat. Például: A MODX fájlrendszere a fájlrendszerből olvas be állományokat. Az S3 egy S3 tárolóból veszi az állományokat.';
$_lang['source_type.file'] = 'Fájlrendszer';
$_lang['source_type.file_desc'] = 'Állományrendszer-alapú forrás a kiszolgáló állományainak kezelésére.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Amazon S3 tároló kezelése.';
$_lang['source_types'] = 'Forrásfajták';
$_lang['source_types.intro_msg'] = 'Ez az összes, erre a MODX példányra telepített médiaforrás felsorolása.';
$_lang['source.access.intro_msg'] = 'Here you can restrict a Media Source to specific User Groups and apply policies for those User Groups. A Media Source with no User Groups attached to it is available to all manager users.';
$_lang['sources'] = 'Médiaforrások';
$_lang['sources.intro_msg'] = 'Kezelje itt az összes médiaforrást.';
$_lang['user_group'] = 'Felhasználócsoport';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'If set, will restrict the files shown to only the specified extensions. Please specify in a comma-separated list, without the dots preceding the extensions.';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'Az állomány elérési útvonala, amire a forrás mutat.';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'If the Base Path setting above is not relative to the MODX install path, set this to No.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'The URL that this source can be accessed from.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'If true, MODX only will prepend the baseUrl if no forward slash (/) is found at the beginning of the URL when rendering the TV. Useful for setting a TV value outside the baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'If the Base URL setting above is not relative to the MODX install URL, set this to No.';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'A comma-separated list of file extensions to use as images. MODX will attempt to make thumbnails of files with these extensions.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'A comma-separated list. MODX will skip over and hide files and folders that match any of these.';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'The quality of the rendered thumbnails, in a scale from 0-100.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'The image type to render thumbnails as.';

/* s3 source type */
$_lang['bucket'] = 'Vödör';
$_lang['prop_s3.bucket_desc'] = 'The S3 Bucket to load your data from.';
$_lang['prop_s3.key_desc'] = 'The Amazon key for authentication to the bucket.';
$_lang['prop_s3.imageExtensions_desc'] = 'A comma-separated list of file extensions to use as images. MODX will attempt to make thumbnails of files with these extensions.';
$_lang['prop_s3.secret_key_desc'] = 'The Amazon secret key for authentication to the bucket.';
$_lang['prop_s3.skipFiles_desc'] = 'A comma-separated list. MODX will skip over and hide files and folders that match any of these.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'The quality of the rendered thumbnails, in a scale from 0-100.';
$_lang['prop_s3.thumbnailType_desc'] = 'The image type to render thumbnails as.';
$_lang['prop_s3.url_desc'] = 'The URL of the Amazon S3 instance.';
$_lang['s3_no_move_folder'] = 'The S3 driver does not support moving of folders at this time.';
$_lang['prop_s3.region_desc'] = 'A vödör körzete. Például: us-west-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

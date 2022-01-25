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
$_lang['source_access_remove'] = 'Hozzáférés törlése';
$_lang['source_access_remove_confirm'] = 'Biztosan törli ennek a felhasználói csoportnak a hozzáférését ehhez a forráshoz?';
$_lang['source_access_update'] = 'Hozzáférés szerkesztése';
$_lang['source_description_desc'] = 'A médiaforrás rövid leírása.';
$_lang['source_err_ae_name'] = 'Már létezik ezzel a névvel médiaforrás! Kérjük, adjon meg másik nevet.';
$_lang['source_err_nf'] = 'A médiaforrás nem található!';
$_lang['source_err_init'] = 'Nem sikerült a "[[+source]]" médiaforrást előkészíteni!';
$_lang['source_err_nfs'] = 'Nem található médiaforrás [[+id]] azonosítóval.';
$_lang['source_err_ns'] = 'Kérjük, adja meg a médiaforrást.';
$_lang['source_err_ns_name'] = 'Kérjük, adja meg a médiaforrás nevét.';
$_lang['source_name_desc'] = 'A médiaforrás neve.';
$_lang['source_properties.intro_msg'] = 'Alább kezelheti a forrás tulajdonságait.';
$_lang['source_remove_confirm'] = 'Biztosan törli ezt a médiaforrást? Használhatatlan lehet bármelyik sablonváltozó, amit ehhez az erőforráshoz rendelt.';
$_lang['source_remove_multiple_confirm'] = 'Biztosan törli ezeket a médiaforrásokat? Használhatatlan lehet bármelyik sablonváltozó, amit ezekhez az erőforrásokhoz rendelt.';
$_lang['source_type'] = 'Forrásfajta';
$_lang['source_type_desc'] = 'A médiaforrás fajtája vagy meghajtója. A forrás ezt a meghajtót használja a kapcsolódáshoz, amikor összegyűjti az adatokat. Például: A MODX fájlrendszere a fájlrendszerből olvas be állományokat. Az S3 egy S3 tárolóból veszi az állományokat.';
$_lang['source_type.file'] = 'Fájlrendszer';
$_lang['source_type.file_desc'] = 'Állományrendszer-alapú forrás a kiszolgáló állományainak kezelésére.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Amazon S3 tároló kezelése.';
$_lang['source_type.ftp'] = 'Állományátviteli szabályrendszer (FTP)';
$_lang['source_type.ftp_desc'] = 'Távoli FTP kiszolgáló kezelése.';
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
$_lang['prop_file.basePath_desc'] = 'A forrásra mutató elérési út, pl. assets/images/<br>Az útvonalra hatással lehet a "basePathRelative" értéke';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'If the Base Path setting above is not relative to the MODX install path, set this to No.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'A forrás elérésére való URL, pl. assets/images/<br>Az útvonalra hatással lehet a "baseUrlRelative" értéke';
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
$_lang['prop_file.visibility_desc'] = 'Az új állományok és mappák alapértelmezett láthatósága.';
$_lang['no_move_folder'] = 'A médiaforrás illesztője nem támogatja a mappák mozgatását most.';

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
$_lang['prop_s3.endpoint_desc'] = 'S3-hoz illeszkedő végponti URL, pl. "https://s3.<region>.example.com". Tekintse át az S3-hoz illeszkedő szolgáltató leírását a végpont elhelyezkedéséről. Hagyja üresen az Amazon S3 esetén';
$_lang['prop_s3.region_desc'] = 'A vödör körzete. Például: us-west-1';
$_lang['prop_s3.prefix_desc'] = 'Elhagyható elérési út/mappa előtag';
$_lang['s3_no_move_folder'] = 'The S3 driver does not support moving of folders at this time.';

/* ftp source type */
$_lang['prop_ftp.host_desc'] = 'Kiszolgáló neve vagy IP címe';
$_lang['prop_ftp.username_desc'] = 'Felhasználónév hitelesítéshez. Lehet "névtelen".';
$_lang['prop_ftp.password_desc'] = 'A felhasználó jelszava. Névtelen felhasználóhoz hagyja üresen.';
$_lang['prop_ftp.url_desc'] = 'Ha ennek az FTP kiszolgálónak van nyilvános webcíme, itt beírhatja. Ezzel a médiaböngészőben láthatók lesznek a képek előnézetei.';
$_lang['prop_ftp.port_desc'] = 'A kiszolgáló portja, alapértelmezetten 21.';
$_lang['prop_ftp.root_desc'] = 'Alapkönyvtár, kapcsolódás után lesz megnyitva';
$_lang['prop_ftp.passive_desc'] = 'Passzív FTP mód engedélyezése vagy tiltása';
$_lang['prop_ftp.ssl_desc'] = 'SSL kapcsolódás engedélyezése vagy tiltása';
$_lang['prop_ftp.timeout_desc'] = 'Kapcsolódás időtúllépése másodpercben.';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

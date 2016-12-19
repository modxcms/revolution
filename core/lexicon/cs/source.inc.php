<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Přístupová práva';
$_lang['base_path'] = 'Výchozí cesta';
$_lang['base_path_relative'] = 'Relativní výchozí cesta?';
$_lang['base_url'] = 'Výchozí URL';
$_lang['base_url_relative'] = 'Relativní výchozí URL?';
$_lang['minimum_role'] = 'Minimální role';
$_lang['path_options'] = 'Možnosti cesty';
$_lang['policy'] = 'Přístupové právo';
$_lang['source'] = 'Zdroj médií';
$_lang['source_access_add'] = 'Přidat uživatelskou skupinu';
$_lang['source_access_remove'] = 'Odebrat přístup';
$_lang['source_access_remove_confirm'] = 'Opravdu chcete odebrat přístup k tomuto zdroji této uživatelské skupině?';
$_lang['source_access_update'] = 'Upravit přístup';
$_lang['source_create'] = 'Vytvořit nový zdroj médií';
$_lang['source_description_desc'] = 'Popis zdroje médií.';
$_lang['source_duplicate'] = 'Zkopírovat zdroj médií';
$_lang['source_err_ae_name'] = 'Zdroj médií s tímto názvem již existuje! Zadejte jiný název.';
$_lang['source_err_nf'] = 'Zdroj médií nenalezen!';
$_lang['source_err_nfs'] = 'Nebyl nalezen žádný zdroj médií s id: [[+id]].';
$_lang['source_err_ns'] = 'Nevybrali jste zdroj médií.';
$_lang['source_err_ns_name'] = 'Zadejte název zdroje médií.';
$_lang['source_name_desc'] = 'Název zdroje médií.';
$_lang['source_properties.intro_msg'] = 'Správa vlastností tohoto zdroje.';
$_lang['source_remove'] = 'Odstranit zdroj médií';
$_lang['source_remove_confirm'] = 'Opravdu chcete odstranit tento zdroj médií? Toto může způsobit problémy s TVs, které jsou přiřazeny k tomuto zdroji.';
$_lang['source_remove_multiple'] = 'Odstranit více zdrojů médií';
$_lang['source_remove_multiple_confirm'] = 'Opravdu chcete odstranit tyto zdroje médií? Toto může způsobit problémy s TVs, které jsou přiřazeny k těmto zdrojům.';
$_lang['source_update'] = 'Upravit zdroj médií';
$_lang['source_type'] = 'Typ zdroje';
$_lang['source_type_desc'] = 'Typ nebo ovladač zdroje médií. Zdroj bude používat tento ovladač pro přístup k datům. Například: "Souborový systém" pracuje se soubory ze souborového systému. "Amazon S3" zajistí přístup k souborům ve službě Amazon S3.';
$_lang['source_type.file'] = 'Souborový systém';
$_lang['source_type.file_desc'] = 'Souborový systém na Vašem serveru.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Soubory v rámci služby Amazon S3.';
$_lang['source_types'] = 'Typy zdrojů';
$_lang['source_types.intro_msg'] = 'Seznam všech instalovaných typů zdrojů médií dostupných v této instanci MODX.';
$_lang['source.access.intro_msg'] = 'Na tomto místě můžete omezit zdroj médií určitým uživatelským skupinám a aplikovat na ně přístupové právo. Zdroj médií bez omezení pro uživatelskou skupinu je dostupný všem uživatelům správce obsahu.';
$_lang['sources'] = 'Zdroje médií';
$_lang['sources.intro_msg'] = 'Správa všech zdrojů médií.';
$_lang['user_group'] = 'Uživatelská skupina';

/* file source type */
$_lang['allowedFileTypes'] = 'povolené přípony souborů';
$_lang['prop_file.allowedFileTypes_desc'] = 'Je-li nastaveno, omezí zobrazené soubor pouze na určité přípony. Zadejte je jako čárkou oddělený seznam, bez tečky.';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'Cesta k souborům v rámci zdroje.';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Pokud není výchozí cesta uvedená výše k instalaci MODX relativní, nastavte Ano.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'URL adresa, ze které může být tento zdroj dostupný.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Je-li nastaveno, pak při vykreslování TV v případě, že URL nezačíná (/) doplní MODX před URL baseUrl. Užitečné při potřebě nastavit hodnotu TV mimo baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'Pokud není výchozí URL uvedená výše relativní k instalaci MODX, nastavte Ano.';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'Čárkou oddělený seznam přípon souborů, které mají být zobrazeny jako obrázky. MODX se pokusí u souborů s těmito příponami vytvořit náhledy.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'Čárkou oddělený seznam. MODX vynechá a skryje všechny soubory a složky odpovídající tomuto zápisu.';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'Kvalita vytvořených náhledů v měřítku 0 - 100.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'Typ obrázku, ve kterém budou vytvářeny náhledy.';

/* s3 source type */
$_lang['bucket'] = 'Bucket';
$_lang['prop_s3.bucket_desc'] = 'S3 Bucket, ze kterého se mají načítat data.';
$_lang['prop_s3.key_desc'] = 'Amazon key pro ověření přístupu k bucketu.';
$_lang['prop_s3.imageExtensions_desc'] = 'Čárkou oddělený seznam přípon souborů, které mají být zobrazeny jako obrázky. MODX se pokusí u souborů s těmito příponami vytvořit náhledy.';
$_lang['prop_s3.secret_key_desc'] = 'Amazon secret key pro ověření přístupu k bucketu.';
$_lang['prop_s3.skipFiles_desc'] = 'Čárkou oddělený seznam. MODX vynechá a skryje všechny soubory a složky odpovídající tomuto zápisu.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'Kvalita vytvořených náhledů v měřítku 0 - 100.';
$_lang['prop_s3.thumbnailType_desc'] = 'Typ obrázku, ve kterém budou vytvářeny náhledy.';
$_lang['prop_s3.url_desc'] = 'URL instance Amazon S3.';
$_lang['s3_no_move_folder'] = 'Ovladač Amazon S3 v tuto chvíli nepodporuje přesun složek.';
$_lang['prop_s3.region_desc'] = 'Region S3 cloudu. Například: us-west-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

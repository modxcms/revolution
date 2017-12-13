<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Правы доступу';
$_lang['base_path'] = 'Базавы шлях';
$_lang['base_path_relative'] = 'Базавы шлях адносны?';
$_lang['base_url'] = 'Базавы URL';
$_lang['base_url_relative'] = 'Базавы URL адносны?';
$_lang['minimum_role'] = 'Мінімальная роля';
$_lang['path_options'] = 'Параметры шляху';
$_lang['policy'] = 'Палітыка';
$_lang['source'] = 'Медыя-крыніца ';
$_lang['source_access_add'] = 'Дадаць групу карыстальнікаў';
$_lang['source_access_remove'] = 'Выдаліць доступ';
$_lang['source_access_remove_confirm'] = 'Вы сапраўды жадаеце выдаліць доступ да гэтай медыя-крынiцы з гэтай групы карыстальнікаў?';
$_lang['source_access_update'] = 'Абнавіць доступ';
$_lang['source_create'] = 'Стварыць новую медыя-крыніцу';
$_lang['source_description_desc'] = 'Кароткае апісанне медыя-крыніцы.';
$_lang['source_duplicate'] = 'Дубляваць медыя-крыніцу';
$_lang['source_err_ae_name'] = 'Медыя-крыніца з такой назвай ужо існуе! Калі ласка, пазначце іншае імя.';
$_lang['source_err_nf'] = 'Медыя-крыніца не знойдзена!';
$_lang['source_err_nfs'] = 'Не знойдзена ніводнай медыя-крыніцы з id: [[+id]].';
$_lang['source_err_ns'] = 'Калі ласка, пазначце медыя-крыніцу.';
$_lang['source_err_ns_name'] = 'Калі ласка, пазначце назву медыя-крыніцы.';
$_lang['source_name_desc'] = 'Назва медыя-крыніцы.';
$_lang['source_properties.intro_msg'] = 'Ніжэй знаходзіцца кіраване ўласцівасцямі гэтай крыніцы.';
$_lang['source_remove'] = 'Выдаліць медыя-крыніцу';
$_lang['source_remove_confirm'] = 'Вы сапраўды жадаеце выдаліць гэтую медыя-крыніцу? Гэта можа зламаць некаторыя зменныя шаблону, якія вы прыстасавалі да гэтай крыніцы.';
$_lang['source_remove_multiple'] = 'Выдаліць адразу некалькі медыя-крыніц';
$_lang['source_remove_multiple_confirm'] = 'Вы сапраўды жадаеце выдаліць гэтыя медыя-крыніцы? Гэта можа зламаць некаторыя зменныя шаблону, якія вы прыстасавалі да гэтых крыніц.';
$_lang['source_update'] = 'Абнавіць медыя-крыніцу';
$_lang['source_type'] = 'Тып крыніцы';
$_lang['source_type_desc'] = 'The type, or driver, of the Media Source. The Source will use this driver to connect to when gathering its data. For example: File System will grab files from the file system. S3 will get files from an S3 bucket.';
$_lang['source_type.file'] = 'Файлавая сістэма';
$_lang['source_type.file_desc'] = 'Крыніца, заснаваная на файлавай сістэме, якая паказвае файлы вашага сервера.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Паказвае файлы вядра Amazon S3.';
$_lang['source_types'] = 'Тыпы крыніц';
$_lang['source_types.intro_msg'] = 'Гэта спіс усіх усталяваных тыпаў медыя-крыніц для гэтага асобніка MODX.';
$_lang['source.access.intro_msg'] = 'Here you can restrict a Media Source to specific User Groups and apply policies for those User Groups. A Media Source with no User Groups attached to it is available to all manager users.';
$_lang['sources'] = 'Медыя-крыніцы';
$_lang['sources.intro_msg'] = 'Тут можна кіраваць усімі вашымі медыя-крыніцамі.';
$_lang['user_group'] = 'Група карыстальнiкаў';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes (дазволеныя тыпы файлаў)';
$_lang['prop_file.allowedFileTypes_desc'] = 'If set, will restrict the files shown to only the specified extensions. Please specify in a comma-separated list, without the dots preceding the extensions.';
$_lang['basePath'] = 'basePath (базавы шлях)';
$_lang['prop_file.basePath_desc'] = 'The file path to point the Source to.';
$_lang['basePathRelative'] = 'basePathRelative (адносны базавы шлях)';
$_lang['prop_file.basePathRelative_desc'] = 'If the Base Path setting above is not relative to the MODX install path, set this to No.';
$_lang['baseUrl'] = 'baseUrl (базавая спасылка)';
$_lang['prop_file.baseUrl_desc'] = 'The URL that this source can be accessed from.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash (правяраць слэш у пачатку базавай спасылкі)';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'If true, MODX only will prepend the baseUrl if no forward slash (/) is found at the beginning of the URL when rendering the TV. Useful for setting a TV value outside the baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative (адносная базавая спасылка)';
$_lang['prop_file.baseUrlRelative_desc'] = 'If the Base URL setting above is not relative to the MODX install URL, set this to No.';
$_lang['imageExtensions'] = 'imageExtensions (пашырэнні малюнкаў)';
$_lang['prop_file.imageExtensions_desc'] = 'Падзелены коскамі спіс пашырэнняў файлаў для выкарыстання ў якасці малюнкаў. MODX будзе спрабаваць зрабіць эскізы файлаў з гэтымі пашырэннямі.';
$_lang['skipFiles'] = 'skipFiles (ігнараваць файлы)';
$_lang['prop_file.skipFiles_desc'] = 'A comma-separated list. MODX will skip over and hide files and folders that match any of these.';
$_lang['thumbnailQuality'] = 'thumbnailQuality (якасць мініяцюр)';
$_lang['prop_file.thumbnailQuality_desc'] = 'Якасць паказваемых эскізаў, па шкале ад 0 да 100.';
$_lang['thumbnailType'] = 'thumbnailType (фармат мініяцюр)';
$_lang['prop_file.thumbnailType_desc'] = 'Тып файла малюнка ў якім рабіць эскіз.';

/* s3 source type */
$_lang['bucket'] = 'Вядро';
$_lang['prop_s3.bucket_desc'] = 'The S3 Bucket to load your data from.';
$_lang['prop_s3.key_desc'] = 'The Amazon key for authentication to the bucket.';
$_lang['prop_s3.imageExtensions_desc'] = 'Падзелены коскамі спіс пашырэнняў файлаў для выкарыстання ў якасці малюнкаў. MODX будзе спрабаваць зрабіць эскізы файлаў з гэтымі пашырэннямі.';
$_lang['prop_s3.secret_key_desc'] = 'The Amazon secret key for authentication to the bucket.';
$_lang['prop_s3.skipFiles_desc'] = 'A comma-separated list. MODX will skip over and hide files and folders that match any of these.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'Якасць паказваемых эскізаў, па шкале ад 0 да 100.';
$_lang['prop_s3.thumbnailType_desc'] = 'Тып файла малюнка ў якім рабіць эскіз.';
$_lang['prop_s3.url_desc'] = 'URL асобніка Amazon S3.';
$_lang['s3_no_move_folder'] = 'S3 драйвер пакуль што не падтрымлівае перасоўванне каталогаў.';
$_lang['prop_s3.region_desc'] = 'Рэгіён вядра. Прыклад: us-west-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

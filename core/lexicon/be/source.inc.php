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
$_lang['source'] = 'Крыніца файлаў';
$_lang['source_access_add'] = 'Дадаць групу карыстальнікаў';
$_lang['source_access_remove'] = 'Delete Access';
$_lang['source_access_remove_confirm'] = 'Are you sure you want to delete Access to this Source for this User Group?';
$_lang['source_access_update'] = 'Edit Access';
$_lang['source_description_desc'] = 'Кароткае апісанне крыніцы файлаў.';
$_lang['source_err_ae_name'] = 'Крыніца файлаў з такой назвай ужо існуе! Калі ласка, пазначце іншае імя.';
$_lang['source_err_nf'] = 'Крыніца файлаў не знойдзена!';
$_lang['source_err_init'] = 'Не атрымалася ініцыялізаваць крыніцу файлаў "[[+source]]"!';
$_lang['source_err_nfs'] = 'Не знойдзена ніводнай крыніцы файлаў з id: [[+id]].';
$_lang['source_err_ns'] = 'Калі ласка, пазначце крыніцу файлаў.';
$_lang['source_err_ns_name'] = 'Калі ласка, пазначце назву крыніцы файлаў.';
$_lang['source_name_desc'] = 'Назва крыніцы файлаў.';
$_lang['source_properties.intro_msg'] = 'Ніжэй знаходзіцца кіраване ўласцівасцямі гэтай крыніцы.';
$_lang['source_remove_confirm'] = 'Are you sure you want to delete this Media Source? This might break any TVs you have assigned to this source.';
$_lang['source_remove_multiple_confirm'] = 'Вы сапраўды жадаеце выдаліць гэтыя крыніцы файлаў? Гэта можа зламаць некаторыя зменныя шаблону, якія вы прыстасавалі да гэтых крыніц.';
$_lang['source_type'] = 'Тып крыніцы';
$_lang['source_type_desc'] = 'Тып, або драйвер крыніцы файлаў. Крыніца будзе выкарыстоўваць гэты драйвер для падлучэння падчас збору сваіх дадзеных. Напрыклад: файлавая сістэма будзе захопліваць файлы з файлавай сістэмы. S3 атрымае файлы з вядра S3.';
$_lang['source_type.file'] = 'Файлавая сістэма';
$_lang['source_type.file_desc'] = 'Крыніца, заснаваная на файлавай сістэме, якая паказвае файлы вашага сервера.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Паказвае файлы вядра Amazon S3.';
$_lang['source_type.ftp'] = 'Пратакол перасылкі файлаў (FTP)';
$_lang['source_type.ftp_desc'] = 'Прагляд знешняга FTP сервера.';
$_lang['source_types'] = 'Тыпы крыніц';
$_lang['source_types.intro_msg'] = 'Гэта спіс усіх усталяваных тыпаў крыніц файлаў для гэтага асобніка MODX.';
$_lang['source.access.intro_msg'] = 'Тут вы можаце абмежаваць крыніцы файлаў для канкрэтных груп карыстальнікаў і прымяніць палітыкі доступу для гэтых груп карыстальнікаў. Крыніца файлаў без падлучаных груп карыстальнікаў даступна для ўсіх карыстальнікаў сістэмы кіравання.';
$_lang['sources'] = 'Крыніцы файлаў';
$_lang['sources.intro_msg'] = 'Тут можна кіраваць усімі вашымі крыніцамі файлаў.';
$_lang['user_group'] = 'Група карыстальнiкаў';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes (дазволеныя тыпы файлаў)';
$_lang['prop_file.allowedFileTypes_desc'] = 'Калi абрана, будзе абмяжоўваць файлы, паказваючы толькі адзначаныя пашырэнні. Калі ласка, пазначце пашырэнні ў спісе праз коску, без кропак.';
$_lang['basePath'] = 'basePath (базавы шлях)';
$_lang['prop_file.basePath_desc'] = 'The file path to point the Source to, for example: assets/images/<br>The path may depend on the "basePathRelative" parameter';
$_lang['basePathRelative'] = 'basePathRelative (адносны базавы шлях)';
$_lang['prop_file.basePathRelative_desc'] = 'Калі базавы шлях, адзначаны вышэй, не адносіцца да шляху ўсталяванага MODX, усталюйце значэнне як Не.';
$_lang['baseUrl'] = 'baseUrl (базавая спасылка)';
$_lang['prop_file.baseUrl_desc'] = 'The URL that this source can be accessed from, for example: assets/images/<br>The path may depend on the "baseUrlRelative" parameter';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash (правяраць слэш у пачатку базавай спасылкі)';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Калі так, MODX будзе падстаўляць baseUrl да спасылак пры апрацоўцы зменнай шаблону, калі ў пачатку URL няма прамога слэшу (/). Карысна для ўсталёўкі значэння зменнай шаблону па-за baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative (адносная базавая спасылка)';
$_lang['prop_file.baseUrlRelative_desc'] = 'Калі налада базавага URL не з\'яўляецца адноснай да URL усталяванага MODX, усталюйце значэнне Не.';
$_lang['imageExtensions'] = 'imageExtensions (пашырэнні малюнкаў)';
$_lang['prop_file.imageExtensions_desc'] = 'Падзелены коскамі спіс пашырэнняў файлаў для выкарыстання ў якасці малюнкаў. MODX будзе спрабаваць зрабіць эскізы файлаў з гэтымі пашырэннямі.';
$_lang['skipFiles'] = 'skipFiles (ігнараваць файлы)';
$_lang['prop_file.skipFiles_desc'] = 'Падзелены коскамі спіс. MODX прапусціць і схавае файлы і каталогі, якія адпавядаюць любым са спіса.';
$_lang['thumbnailQuality'] = 'thumbnailQuality (якасць мініяцюр)';
$_lang['prop_file.thumbnailQuality_desc'] = 'Якасць паказваемых эскізаў, па шкале ад 0 да 100.';
$_lang['thumbnailType'] = 'thumbnailType (фармат мініяцюр)';
$_lang['prop_file.thumbnailType_desc'] = 'Тып файла малюнка ў якім рабіць эскіз.';
$_lang['prop_file.visibility_desc'] = 'Бачнасць па змаўчанні для новых файлаў і каталогаў.';
$_lang['no_move_folder'] = 'Драйвер крыніц файлаў пакуль што не падтрымлівае перасоўванне каталогаў.';

/* s3 source type */
$_lang['bucket'] = 'Вядро';
$_lang['prop_s3.bucket_desc'] = 'Вядро S3, з якога патрэбна загружаць дадзеныя.';
$_lang['prop_s3.key_desc'] = 'Ключ Amazon для доступу да вядра (S3).';
$_lang['prop_s3.imageExtensions_desc'] = 'Падзелены коскамі спіс пашырэнняў файлаў для выкарыстання ў якасці малюнкаў. MODX будзе спрабаваць зрабіць эскізы файлаў з гэтымі пашырэннямі.';
$_lang['prop_s3.secret_key_desc'] = 'Сакрэтны ключ Amazon для доступу да вядра (S3).';
$_lang['prop_s3.skipFiles_desc'] = 'Падзелены коскамі спіс. MODX прапусціць і схавае файлы і каталогі, якія адпавядаюць любым са спіса.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'Якасць паказваемых эскізаў, па шкале ад 0 да 100.';
$_lang['prop_s3.thumbnailType_desc'] = 'Тып файла малюнка ў якім рабіць эскіз.';
$_lang['prop_s3.url_desc'] = 'URL асобніка Amazon S3.';
$_lang['prop_s3.endpoint_desc'] = 'Alternative S3-compatible endpoint URL, e.g., "https://s3.<region>.example.com". Review your S3-compatible provider’s documentation for the endpoint location. Leave empty for Amazon S3';
$_lang['prop_s3.region_desc'] = 'Рэгіён вядра. Прыклад: us-west-1';
$_lang['prop_s3.prefix_desc'] = 'Неабавязковы прэфікс для шляхоў або каталогаў';
$_lang['s3_no_move_folder'] = 'S3 драйвер пакуль што не падтрымлівае перасоўванне каталогаў.';

/* ftp source type */
$_lang['prop_ftp.host_desc'] = 'Імя хаста сервера або IP-адрас';
$_lang['prop_ftp.username_desc'] = 'Імя карыстальніка для аўтарызацыі. Можа быць "anonymous".';
$_lang['prop_ftp.password_desc'] = 'Пароль карыстальніка. Пакіньце пустым для ананімнага карыстальніка.';
$_lang['prop_ftp.url_desc'] = 'Калі гэты FTP мае публічны URL, вы можаце ўвесці тут яго публічны HTTP-адрас. Гэта таксама дасць магчымасць папярэдняга прагляду малюнкаў у дыспетчары файлаў.';
$_lang['prop_ftp.port_desc'] = 'Порт сервера, па змаўчанні 21.';
$_lang['prop_ftp.root_desc'] = 'Каранёвы каталог, ён будзе адкрыты пасля падлучэння';
$_lang['prop_ftp.passive_desc'] = 'Дазволіць або забараніць пасіўны рэжым FTP';
$_lang['prop_ftp.ssl_desc'] = 'Дазволіць або забараніць SSL злучэнне';
$_lang['prop_ftp.timeout_desc'] = 'Тайм-аўт для злучэння ў секундах.';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

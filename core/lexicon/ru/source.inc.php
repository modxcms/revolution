<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Права доступа';
$_lang['base_path'] = 'Базовый путь';
$_lang['base_path_relative'] = 'Базовый путь относительный?';
$_lang['base_url'] = 'Базовый URL';
$_lang['base_url_relative'] = 'Базовый URL относительный?';
$_lang['minimum_role'] = 'Минимальная роль';
$_lang['path_options'] = 'Параметры базового пути';
$_lang['policy'] = 'Политика';
$_lang['source'] = 'Источник файлов';
$_lang['source_access_add'] = 'Добавить группу пользователей';
$_lang['source_access_remove'] = 'Удалить доступ';
$_lang['source_access_remove_confirm'] = 'Вы уверены, что хотите удалить доступ к источнику для этой группы пользователей?';
$_lang['source_access_update'] = 'Редактировать доступ';
$_lang['source_create'] = 'Создать новый источник файлов';
$_lang['source_description_desc'] = 'Краткое описание источника файлов.';
$_lang['source_duplicate'] = 'Копировать';
$_lang['source_err_ae_name'] = 'Источник файлов с таким именем уже существуе! Укажите другое имя.';
$_lang['source_err_nf'] = 'Источник файлов не найден!';
$_lang['source_err_nfs'] = 'Не найден источник файлов с идентификатором: [[+id]].';
$_lang['source_err_ns'] = 'Укажите источник файлов.';
$_lang['source_err_ns_name'] = 'Укажите имя источника файлов.';
$_lang['source_name_desc'] = 'Название источника файлов.';
$_lang['source_properties.intro_msg'] = 'Параметры источника файлов представлены ниже.';
$_lang['source_remove'] = 'Удалить';
$_lang['source_remove_confirm'] = 'Вы уверены, что хотите удалить этот источник файлов? Это может нарушить работу TV которые были связаны с этим источником.';
$_lang['source_remove_multiple'] = 'Удалить';
$_lang['source_remove_multiple_confirm'] = 'Вы уверены, что хотите удалить этот источники файлов? Это может нарушить работу TV которые были связаны с этими источниками.';
$_lang['source_update'] = 'Редактировать';
$_lang['source_type'] = 'Тип источника файлов';
$_lang['source_type_desc'] = 'Тип или драйвер источника файлов. Источник будет использовать этот драйвер при обработке данных. Например: «Файловая система» будет работать с файлами из файловой системы. «S3» будет работать с файлами из S3 контейнера.';
$_lang['source_type.file'] = 'Файловая система';
$_lang['source_type.file_desc'] = 'Источник использует файловую систему сервера.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Использует хранилище Amazon S3.';
$_lang['source_types'] = 'Типы источников';
$_lang['source_types.intro_msg'] = 'Это список всех установленных типов источников файлов.';
$_lang['source.access.intro_msg'] = 'Здесь вы можете дать доступ к источнику файлов определённым группам пользователей и назначить политики доступа этим группам пользователей. Источник файлов не имеющий связанной с ним группы пользователей будет доступен всем пользователям бэкэнда.';
$_lang['sources'] = 'Источники файлов';
$_lang['sources.intro_msg'] = 'Здесь можно управлять всеми источниками файлов.';
$_lang['user_group'] = 'Группы пользователей';
$_lang['prop_file.allowedFileTypes_desc'] = 'If set, will restrict the files shown to only the specified extensions. Please specify in a comma-separated list, without the .';
$_lang['prop_file.basePath_desc'] = 'The file path to point the Source to.';
$_lang['prop_file.basePathRelative_desc'] = 'If the Base Path setting above is not relative to the MODX install path, set this to Yes.';
$_lang['prop_file.baseUrl_desc'] = 'The URL that this source can be accessed from.';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'If true, MODX only will prepend the baseUrl if no forward slash (/) is found at the beginning of the URL when rendering the TV. Useful for setting a TV value outside the baseUrl.';
$_lang['prop_file.baseUrlRelative_desc'] = 'If the Base URL setting above is not relative to the MODX install URL, set this to Yes.';
$_lang['prop_file.imageExtensions_desc'] = 'A comma-separated list of file extensions to use as images. MODX will attempt to make thumbnails of files with these extensions.';
$_lang['prop_file.skipFiles_desc'] = 'A comma-separated list. MODX will skip over and hide files and folders that match any of these.';
$_lang['prop_file.thumbnailQuality_desc'] = 'The quality of the rendered thumbnails, in a scale from 0-100.';
$_lang['prop_file.thumbnailType_desc'] = 'The image type to render thumbnails as.';
$_lang['bucket'] = 'Bucket';
$_lang['prop_s3.bucket_desc'] = 'The S3 Bucket to load your data from.';
$_lang['prop_s3.key_desc'] = 'The Amazon key for authentication to the bucket.';
$_lang['prop_s3.imageExtensions_desc'] = 'A comma-separated list of file extensions to use as images. MODX will attempt to make thumbnails of files with these extensions.';
$_lang['prop_s3.secret_key_desc'] = 'The Amazon secret key for authentication to the bucket.';
$_lang['prop_s3.skipFiles_desc'] = 'A comma-separated list. MODX will skip over and hide files and folders that match any of these.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'The quality of the rendered thumbnails, in a scale from 0-100.';
$_lang['prop_s3.thumbnailType_desc'] = 'The image type to render thumbnails as.';
$_lang['prop_s3.url_desc'] = 'The URL of the Amazon S3 instance.';
$_lang['s3_no_move_folder'] = 'The S3 driver does not support moving of folders at this time.';
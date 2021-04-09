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
$_lang['source_duplicate'] = 'Копировать источник файлов';
$_lang['source_err_ae_name'] = 'Источник файлов с таким названием уже существует! Пожалуйста, укажите другое название.';
$_lang['source_err_nf'] = 'Источник файлов не найден!';
$_lang['source_err_nfs'] = 'Не найден источник файлов с ID: [[+id]].';
$_lang['source_err_ns'] = 'Укажите источник файлов.';
$_lang['source_err_ns_name'] = 'Пожалуйста, укажите название источника файлов.';
$_lang['source_name_desc'] = 'Название источника файлов.';
$_lang['source_properties.intro_msg'] = 'Параметры источника файлов представлены ниже.';
$_lang['source_remove'] = 'Удалить источник файлов';
$_lang['source_remove_confirm'] = 'Вы уверены, что хотите удалить этот источник файлов? Это может нарушить работу TV, которые были связаны с этим источником.';
$_lang['source_remove_multiple'] = 'Удалить несколько источников файлов';
$_lang['source_remove_multiple_confirm'] = 'Вы уверены, что хотите удалить этот источник файлов? Это может нарушить работу TV, которые были связаны с этими источниками.';
$_lang['source_update'] = 'Редактировать источник файлов';
$_lang['source_type'] = 'Тип источника файлов';
$_lang['source_type_desc'] = 'Тип или драйвер источника файлов. Источник будет использовать этот драйвер при обработке данных. Например: «Файловая система» будет работать с файлами из файловой системы. «S3» будет работать с файлами из S3 корзины.';
$_lang['source_type.file'] = 'Файловая система';
$_lang['source_type.file_desc'] = 'Источник использует файловую систему сервера.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Использует хранилище Amazon S3.';
$_lang['source_types'] = 'Типы источников';
$_lang['source_types.intro_msg'] = 'Это список всех установленных типов источников файлов.';
$_lang['source.access.intro_msg'] = 'Здесь вы можете дать доступ к источнику файлов определённым группам пользователей и назначить политики доступа для этих групп пользователей. Источник файлов, не имеющий связанной с ним группы пользователей, будет доступен всем пользователям системы управления.';
$_lang['sources'] = 'Источники файлов';
$_lang['sources.intro_msg'] = 'Здесь можно управлять всеми источниками файлов.';
$_lang['user_group'] = 'Группа пользователей';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes (разрешённые типы файлов)';
$_lang['prop_file.allowedFileTypes_desc'] = 'Если указано, будут отображены файлы только с перечисленными расширениями. Укажите список, через запятую, без знака «.»';
$_lang['basePath'] = 'basePath (базовый путь)';
$_lang['prop_file.basePath_desc'] = 'Путь к файлам источника.';
$_lang['basePathRelative'] = 'basePathRelative (относительный базовый путь)';
$_lang['prop_file.basePathRelative_desc'] = 'Выберите «Нет», если параметр «basePath» указан не относительно пути установки MODX.';
$_lang['baseUrl'] = 'baseUrl (базовая ссылка)';
$_lang['prop_file.baseUrl_desc'] = 'URL, по которому будет доступен этот источник файлов.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash (проверять слеш в начале базовой ссылки)';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Если да, MODX только поставит перед именем baseUrl, если в начале URL не будет найден прямой слеш (/) в момент генерации TV. Полезно для установки значения TV вне baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative (относительная базовая ссылка)';
$_lang['prop_file.baseUrlRelative_desc'] = 'Выберите «Нет», если параметр «baseUrl» указан не относительно URL установки MODX.';
$_lang['imageExtensions'] = 'imageExtensions (расширения изображений)';
$_lang['prop_file.imageExtensions_desc'] = 'Список расширений файлов, через запятую, являющихся изображениями. MODX попытается создать превью для файлов с этими расширениями.';
$_lang['skipFiles'] = 'skipFiles (игнорировать файлы)';
$_lang['prop_file.skipFiles_desc'] = 'Список, через запятую. MODX будет пропускать и скрывать файлы и папки, совпадающие с любой из масок.';
$_lang['thumbnailQuality'] = 'thumbnailQuality (качество миниатюр)';
$_lang['prop_file.thumbnailQuality_desc'] = 'Качество генерируемых превью, по шкале от 0 до 100.';
$_lang['thumbnailType'] = 'thumbnailType (формат миниатюр)';
$_lang['prop_file.thumbnailType_desc'] = 'Тип изображения, используемый для генерации превью.';

/* s3 source type */
$_lang['bucket'] = 'Корзина';
$_lang['prop_s3.bucket_desc'] = 'S3 корзина, из которой загружать ваши данные.';
$_lang['prop_s3.key_desc'] = 'Ключ доступа Amazon для авторизации в корзине.';
$_lang['prop_s3.imageExtensions_desc'] = 'Список расширений файлов, через запятую, являющихся изображениями. MODX попытается создать превью для файлов с этими расширениями.';
$_lang['prop_s3.secret_key_desc'] = 'Секретный ключ Amazon для авторизации в корзине.';
$_lang['prop_s3.skipFiles_desc'] = 'Список, через запятую. MODX будет пропускать и скрывать файлы и папки, совпадающие с любой из масок.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'Качество генерируемых превью, по шкале от 0 до 100.';
$_lang['prop_s3.thumbnailType_desc'] = 'Тип изображения, используемый для генерации превью.';
$_lang['prop_s3.url_desc'] = 'URL хранилища Amazon S3.';
$_lang['s3_no_move_folder'] = 'На текущий момент драйвер S3 не поддерживает перемещение папок.';
$_lang['prop_s3.region_desc'] = 'Регион хранилища, например: us-west-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

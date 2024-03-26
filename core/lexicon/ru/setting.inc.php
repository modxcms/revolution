<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Раздел';
$_lang['area_authentication'] = 'Авторизация и безопасность';
$_lang['area_caching'] = 'Кэширование';
$_lang['area_core'] = 'Ядро';
$_lang['area_editor'] = 'Визуальный редактор';
$_lang['area_file'] = 'Файловая система';
$_lang['area_filter'] = 'Фильтр по разделу...';
$_lang['area_furls'] = 'Дружественные URL';
$_lang['area_gateway'] = 'Шлюз';
$_lang['area_language'] = 'Словарь и язык';
$_lang['area_mail'] = 'Почта';
$_lang['area_manager'] = 'Система управления';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Прокси';
$_lang['area_session'] = 'Сессии и куки';
$_lang['area_static_elements'] = 'Статические элементы';
$_lang['area_static_resources'] = 'Статические ресурсы';
$_lang['area_lexicon_string'] = 'Запись словаря для раздела';
$_lang['area_lexicon_string_msg'] = 'Введите ключ записи словаря для раздела. Если запись с таким ключом не будет найдена, отобразится сам ключ. <br />Разделы пространства «core»: authentication, caching, file, furls, gateway, language, manager, session, site, system';
$_lang['area_site'] = 'Сайт';
$_lang['area_system'] = 'Система и сервер';
$_lang['areas'] = 'Разделы';
$_lang['charset'] = 'Кодировка';
$_lang['country'] = 'Страна';
$_lang['description_desc'] = 'Краткое описание настройки. Можно указать запись словаря, основанную на ключе, формата «setting_» + ключ + «_desc».';
$_lang['key_desc'] = 'Ключ настройки. Будет доступен в контенте через плейсхолдер [[++key]]';
$_lang['name_desc'] = 'Название настройки. Можно указать запись словаря, основанную на ключе, формата «setting_» + ключ.';
$_lang['namespace'] = 'Пространство имён';
$_lang['namespace_desc'] = 'Пространство имён, с которым эта настройка связана. Тема словаря «default» для этого пространства имён будет использована при просмотре.';
$_lang['namespace_filter'] = 'Отбор по пространству имён...';
$_lang['setting_err'] = 'Пожалуйста, проверьте данные для следующих полей: ';
$_lang['setting_err_ae'] = 'Настройка с таким ключом уже есть. Пожалуйста, укажите другой ключ.';
$_lang['setting_err_nf'] = 'Настройка не найдена.';
$_lang['setting_err_ns'] = 'Настройка не указана';
$_lang['setting_err_not_editable'] = 'Эта настройка не может быть отредактирована в таблице. Пожалуйста, используйте контекстное меню для редактирования значения!';
$_lang['setting_err_remove'] = 'Произошла ошибка при попытке удалить настройку.';
$_lang['setting_err_save'] = 'Произошла ошибка при попытке сохранить настройку.';
$_lang['setting_err_startint'] = 'Название настройки не может начинаться с цифры.';
$_lang['setting_err_invalid_document'] = 'Документ с ID %d не существует. Пожалуйста, укажите существующий документ.';
$_lang['setting_remove_confirm'] = 'Вы уверены, что хотите удалить эту настройку? Это может нарушить работу MODX.';
$_lang['settings_after_install'] = 'В случае новой установки вам необходимо проконтролировать введенные настройки, и, при необходимости, изменить их. После того, как вы проверите настройки, нажмите «Сохранить» для обновления настроек базы данных.<br /><br />';
$_lang['settings_desc'] = 'Здесь вы можете изменить основные опции и настройки системы управления MODX, а также сайта. <b>Каждая настройка будет доступна через плейсхолдер [[++key]].</b><br />Дважды нажмите по параметру в колонке «Значение» для редактирования, или нажмите правой кнопкой мыши для других действий. Чтобы увидеть описание настройки, нажмите на «+».';
$_lang['settings_furls'] = 'Дружественные URL';
$_lang['settings_misc'] = 'Различные настройки';
$_lang['settings_site'] = 'Сайт';
$_lang['settings_ui'] = 'Интерфейс и особенности';
$_lang['settings_users'] = 'Пользователь';
$_lang['system_settings'] = 'Системные настройки';
$_lang['usergroup'] = 'Группа пользователей';

// user settings
$_lang['setting_access_category_enabled'] = 'Проверять доступ к категориям';
$_lang['setting_access_category_enabled_desc'] = 'Включает или отключает проверку прав доступа к категориям. <strong>ВАЖНО: Если эта настройка установлена в «Нет», то все политики доступа к категориям будут игнорироваться!</strong>';

$_lang['setting_access_context_enabled'] = 'Проверять доступ к контекстам';
$_lang['setting_access_context_enabled_desc'] = 'Включает или отключает проверку прав доступа к контекстам. <strong>ВАЖНО: Если эта настройка установлена в «Нет», то все политики доступа к контекстам будут игнорироваться! Не отключайте проверку прав доступа к контекстам для всей системы или для контекста "mgr", иначе вы отключите доступ к интерфейсу системы управления.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Проверять доступ к группам ресурсов';
$_lang['setting_access_resource_group_enabled_desc'] = 'Включает или отключает проверку прав доступа к группам ресурсов. <strong>ВАЖНО: Если эта настройка установлена в «Нет», то все политики доступа к группам ресурсов будут игнорироваться!</strong>';

$_lang['setting_allow_mgr_access'] = 'Доступ к системе управления';
$_lang['setting_allow_mgr_access_desc'] = 'Активируйте данную опцию для доступа к системе управления.<br /><strong>ВАЖНО: Если эта настройка установлена в «Нет», то пользователи будут перенаправлены на страницу авторизации или на главную страницу.</strong>';

$_lang['setting_failed_login'] = 'Количество неудачных попыток входа';
$_lang['setting_failed_login_desc'] = 'Укажите число неудачных попыток входа в систему управления, при превышении которого пользователь будет блокирован.';

$_lang['setting_login_allowed_days'] = 'Разрешённые дни';
$_lang['setting_login_allowed_days_desc'] = 'Укажите дни, в которые пользователю разрешено входить в систему управления.';

$_lang['setting_login_allowed_ip'] = 'Разрешённые IP адреса';
$_lang['setting_login_allowed_ip_desc'] = 'Укажите IP-адреса, через запятую, с которых пользователю разрешено входить в систему управления.';

$_lang['setting_login_homepage'] = 'Главная страница авторизованного пользователя';
$_lang['setting_login_homepage_desc'] = 'Укажите ID ресурса, на который будет перенаправлен пользователь после авторизации. <strong>ВАЖНО: удостоверьтесь, что ID принадлежит существующему и опубликованному ресурсу, и что у пользователя есть права для его просмотра!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Версия системы управления политиками доступа';
$_lang['setting_access_policies_version_desc'] = 'Версия системы управления политиками доступа. НЕ МЕНЯЙТЕ!';

$_lang['setting_allow_forward_across_contexts'] = 'Разрешить перенаправление через контексты';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Если разрешено, символические ссылки и метод API modX::sendForward() смогут перенаправлять запросы на ресурсы из других контекстов.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Отображать «Забыли своё имя пользователя?» на странице входа в систему управления.';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Если выбрано «Нет», «Забыли своё имя пользователя?» на странице входа в систему управления не будет отображаться.';

$_lang['setting_allow_tags_in_post'] = 'Разрешить передачу HTML тегов в POST запросах';
$_lang['setting_allow_tags_in_post_desc'] = 'Если выбрано «Нет», все POST-параметры в пределах системы управления будут очищены от любых тегов. MODX рекомендует оставить эту настройку включённой («Да»).';

$_lang['setting_allow_tv_eval'] = 'Разрешить исполнение кода в TV';
$_lang['setting_allow_tv_eval_desc'] = 'Выберите этот параметр, чтобы разрешить или запретить исполнение кода в TV. Если выбрано «Нет», код или значение обработается как обычный текст.';

$_lang['setting_anonymous_sessions'] = 'Анонимные сессии';
$_lang['setting_anonymous_sessions_desc'] = 'Если параметр отключен, доступ к PHP-сессии будут иметь только пользователи, прошедшие проверку. Это позволит уменьшить накладные расходы для анонимных пользователей, если им не нужен доступ к уникальной сессии. Эта настройка не будет работать, если параметр <i>session_enabled</i> имеет значение <b>false</b> (выключен), т. к. сессии и так не будут создаваться.';

$_lang['setting_archive_with'] = 'Использовать PCLZip';
$_lang['setting_archive_with_desc'] = 'Если выбрано «Да», работы с zip-архивами будет использоваться PCLZip вместо ZipArchive. Выберите «Да», если у вас возникают проблемы с распаковкой пакетов в «Менеджере пакетов».';

$_lang['setting_auto_menuindex'] = 'Нумерация меню по умолчанию';
$_lang['setting_auto_menuindex_desc'] = 'Выберите «Да» для автоматической нумерации меню по умолчанию.';

$_lang['setting_auto_check_pkg_updates'] = 'Автоматически проверять наличие обновлений пакетов';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Если выбрано «Да», MODX будет автоматически проверять наличие обновлений для пакетов в «Менеджере пакетов». Это может замедлить загрузку таблицы, отображающей пакеты.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Время жизни кэша результатов автоматической проверки наличия обновлений пакетов';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Продолжительность времени (в минутах), на которое «Менеджер пакетов» будет кэшировать результаты проверки наличия обновлений пакетов.';

$_lang['setting_allow_multiple_emails'] = 'Разрешить пользователям использовать один адрес электронной почты';
$_lang['setting_allow_multiple_emails_desc'] = 'Если выбрано «Да», разные пользователи могут использовать один и тот же адрес электронной почты.';

$_lang['setting_automatic_alias'] = 'Автоматически генерировать псевдоним';
$_lang['setting_automatic_alias_desc'] = 'Выберите «Да» для автоматической генерации псевдонимов на базе заголовка ресурса при его сохранении.';

$_lang['setting_automatic_template_assignment'] = 'Автоматическое присвоение шаблона';
$_lang['setting_automatic_template_assignment_desc'] = 'Выберите, как шаблон будет присваиваться при создании нового ресурса. Доступно: "system" (шаблон по умолчанию из системных настроек), "parent" (шаблон родительского ресурса), или "sibling" (наиболее используемый шаблон соседних ресурсов).';

$_lang['setting_base_help_url'] = 'URL помощи';
$_lang['setting_base_help_url_desc'] = 'URL для ссылки помощи в верхнем правом углу страниц системы управления.';

$_lang['setting_blocked_minutes'] = 'Длительность блокировки';
$_lang['setting_blocked_minutes_desc'] = 'Время (в минутах), на которое пользователь будет заблокирован в случае превышения количества попыток входа в систему управления. Вводите только цифры (без запятых, пробелов и т.п.).';

$_lang['setting_cache_alias_map'] = 'Разрешить кэширование карты псевдонимов контекста';
$_lang['setting_cache_alias_map_desc'] = 'Если выбрано «Да», все URI ресурсов кэшируются в контекст. Включайте для небольших сайтов и отключайте для больших сайтов, чтобы увеличить производительность.';

$_lang['setting_use_context_resource_table'] = 'Использовать таблицу "context_resource"';
$_lang['setting_use_context_resource_table_desc'] = 'Если выбрано «Да», при обновлении контекста используется таблица "context_resource". Это позволяет программно иметь один ресурс в нескольких контекстах одновременно. Если вы не используете эти ресурсы в множестве контекстов через API, можно задать это значение равным "false". На крупных сайтах вы можете получить потенциальный прирост производительности в системе управления.';

$_lang['setting_cache_context_settings'] = 'Включить кэширование настроек контекстов';
$_lang['setting_cache_context_settings_desc'] = 'Если выбрано «Да», настройки контекстов будут кэшироваться для ускорения загрузки страниц.';

$_lang['setting_cache_db'] = 'Включить кэширование базы данных';
$_lang['setting_cache_db_desc'] = 'Если выбрано «Да», объекты и наборы результатов выборки по SQL-запросам кэшируются, значительно снижая нагрузку на базу.';

$_lang['setting_cache_db_expires'] = 'Время жизни кэша базы данных';
$_lang['setting_cache_db_expires_desc'] = 'Значение (в секундах) устанавливает время жизни кэша для результатов запроса к базе данных.';

$_lang['setting_cache_db_session'] = 'Включить кэширование сессий, обрабатываемых базой данных';
$_lang['setting_cache_db_session_desc'] = 'Если выбрано «Да», и настройка «cache_db» включена, сессии, хранящиеся в базе данных, будут также кэшироваться.';

$_lang['setting_cache_db_session_lifetime'] = 'Время жизни кэша сессий базы данных';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Значение (в секундах) устанавливает время жизни кэша сессий базы данных.';

$_lang['setting_cache_default'] = 'Кэшируемый по умолчанию';
$_lang['setting_cache_default_desc'] = 'Выберите «Да» для того, чтобы сделать все новые ресурсы кэшируемыми по умолчанию.';
$_lang['setting_cache_default_err'] = 'Кэшировать ресурсы по умолчанию?';

$_lang['setting_cache_expires'] = 'Время жизни кэша';
$_lang['setting_cache_expires_desc'] = 'Значение (в секундах) устанавливает время жизни кэша.';

$_lang['setting_cache_resource_clear_partial'] = 'Частичная очистка кэша для указанных контекстов';
$_lang['setting_cache_resource_clear_partial_desc'] = 'Когда включено, MODX обновит кэш ресурсов только для указанных контекстов.';

$_lang['setting_cache_format'] = 'Используемый формат кэша';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = сериализация. Выберите один из форматов.';

$_lang['setting_cache_handler'] = 'Класс-обработчик системы кэширования';
$_lang['setting_cache_handler_desc'] = 'Название класса-обработчика, используемого для кэширования.';

$_lang['setting_cache_lang_js'] = 'Кэшировать JavaScript-файлы с записями словаря';
$_lang['setting_cache_lang_js_desc'] = 'Если выбрано «Да», будут добавлены кэширующие заголовки к JavaScript-файлам с записями словарей для системы управления.';

$_lang['setting_cache_lexicon_topics'] = 'Кэшировать темы словарей';
$_lang['setting_cache_lexicon_topics_desc'] = 'Если выбрано «Да», все темы словарей будут кэшироваться для увеличения скорости загрузки страниц системы управления. MODX рекомендует оставить этот параметр включённым («Да»).';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Кэшировать темы словарей, не входящие в ядро';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Если выбрано «Нет», темы словарей, не входящие в ядро, не будут кэшироваться. Это может быть удобно при разработке компонентов.';

$_lang['setting_cache_resource'] = 'Включить частичное кэширование ресурсов';
$_lang['setting_cache_resource_desc'] = 'Если выбрано «Да», частичное кэширование определяется самими ресурсами. Отключение опции отключит его на всём сайте.';

$_lang['setting_cache_resource_expires'] = 'Время жизни частичного кэша ресурсов';
$_lang['setting_cache_resource_expires_desc'] = 'Значение (в секундах) устанавливает время жизни частичного кэша ресурсов.';

$_lang['setting_cache_scripts'] = 'Включить кэширование скриптов';
$_lang['setting_cache_scripts_desc'] = 'Если выбрано «Да», MODX будет кэшировать все скрипты (сниппеты и плагины) в файлы для увеличения скорости загрузки. MODX рекомендует оставить эту настройку включённой («Да»).';

$_lang['setting_cache_system_settings'] = 'Включить кэширование настроек системы';
$_lang['setting_cache_system_settings_desc'] = 'Если выбрано «Да», настройки системы будут кэшироваться для ускорения загрузки страниц. MODX рекомендует оставить эту настройку включённой («Да»).';

$_lang['setting_clear_cache_refresh_trees'] = 'Обновлять древовидные меню при очистке кэша сайта';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Если выбрано «Да», после обновления кэша сайта будут обновляться древовидные меню.';

$_lang['setting_compress_css'] = 'Использовать сжатый CSS';
$_lang['setting_compress_css_desc'] = 'Если выбрано «Да», MODX будет использовать сжатые версии файлов CSS-стилей в системе управления.';

$_lang['setting_compress_js'] = 'Использовать сжатые JavaScript-библиотеки';
$_lang['setting_compress_js_desc'] = 'Если выбрано «Да», MODX будет предоставлять сжатую версию файла со скриптами системы управления.';

$_lang['setting_compress_js_groups'] = 'Использовать группировку при сжатии JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Группировать JavaScript-файлы системы управления MODX, используя конфигурацию "groupsConfig" для Google-компрессора. Выберите «Да», если вы используете расширение "suhosin" или другие ограничения.';

$_lang['setting_concat_js'] = 'Использовать объединённые JavaScript-библиотеки';
$_lang['setting_concat_js_desc'] = 'Если выбрано «Да», MODX будет использовать объединенные версии JavaScript-библиотек в системе управления. Это существенно увеличивает скорость загрузки страниц системы управления. Отключайте, только если вы редактируете элементы ядра.';

$_lang['setting_confirm_navigation'] = 'Подтверждать переход с несохраненными изменениями';
$_lang['setting_confirm_navigation_desc'] = 'Если выбрано «Да», пользователю нужно будет подтвердить свое намерение перейти, если есть несохраненные изменения.';

$_lang['setting_container_suffix'] = 'Суффикс контейнера';
$_lang['setting_container_suffix_desc'] = 'Суффикс, который будет добавляться к псевдониму ресурса-контейнера (при включенных дружественных URL).';

$_lang['setting_context_tree_sort'] = 'Включить сортировку контекстов в дереве ресурсов';
$_lang['setting_context_tree_sort_desc'] = 'Если установлено значение Да, контексты будет сортироваться в дереве ресурсов слева. Вы можете настроить поле для сортировки контекстов в системной настройке \'context_tree_sortby\'.';
$_lang['setting_context_tree_sortby'] = 'Поле для сортировки контекстов в дереве ресурсов';
$_lang['setting_context_tree_sortby_desc'] = 'Поле, по которому будут сортироваться контексты в дереве ресурсов, если сортировка включена.';
$_lang['setting_context_tree_sortdir'] = 'Направление сортировки контекстов в дереве ресурсов';
$_lang['setting_context_tree_sortdir_desc'] = 'Направление сортировки контекстов в дереве ресурсов, если сортировка включена.';

$_lang['setting_cultureKey'] = 'Язык';
$_lang['setting_cultureKey_desc'] = 'Выберите язык для всех контекстов, за исключением контекста системы управления.';

$_lang['setting_date_timezone'] = 'Временная зона по умолчанию';
$_lang['setting_date_timezone_desc'] = 'Если указано, определяет временную зону по умолчанию для PHP-функций, работающими с датами. Список поддерживаемых значений смотрите <a href="http://php.net/timezones" target="_blank">здесь</a>. Если не указано, и PHP ini-параметр "date.timezone" не задан в вашем окружении, будет использоваться Всемирное координированное время.';

$_lang['setting_debug'] = 'Режим отладки';
$_lang['setting_debug_desc'] = 'Включает/выключает режим отладки в MODX и/или устанавливает уровень отображения ошибок для PHP. «» - использовать текущий «error_reporting», «0» - отключить (error_reporting = 0), «1» - включить (error_reporting = -1), или любое другое значение «error_reporting» (как число).';

$_lang['setting_default_content_type'] = 'Тип содержимого по умолчанию';
$_lang['setting_default_content_type_desc'] = 'Выберите тип содержимого, используемый по умолчанию при создании нового ресурса. Вы можете выбрать другой тип содержимого при редактировании ресурса.';

$_lang['setting_default_duplicate_publish_option'] = 'Настройки публикации при копировании ресурсов';
$_lang['setting_default_duplicate_publish_option_desc'] = 'Выберите настройки публикации при копировании ресурса. Может быть «unpublish» - все копии будут сняты с публикации, «publish» - все копии будут опубликованы, или «preserve» - у копии будет сохранено состояние публикации копируемого ресурса.';

$_lang['setting_default_media_source'] = 'Источник файлов по умолчанию';
$_lang['setting_default_media_source_desc'] = 'Источник файлов, загружаемый по умолчанию.';

$_lang['setting_default_media_source_type'] = 'Тип источника файлов по умолчанию';
$_lang['setting_default_media_source_type_desc'] = 'Тип источника файлов, используемый по умолчанию при создании нового источника файлов.';

$_lang['setting_photo_profile_source'] = 'User Profile Photo Source';
$_lang['setting_photo_profile_source_desc'] = 'Specifies the Media Source to use for storing and retrieving profile photos/avatars. If not specified, the default Media Source will be used.';

$_lang['setting_default_template'] = 'Шаблон по умолчанию';
$_lang['setting_default_template_desc'] = 'Выберите шаблон, используемый по умолчанию при создании нового ресурса. Вы сможете изменить шаблон при редактировании ресурса.';

$_lang['setting_default_per_page'] = 'По умолчанию на странице';
$_lang['setting_default_per_page_desc'] = 'Количество строк, отображаемое по умолчанию в таблицах системы управления.';

$_lang['setting_emailsender'] = 'Электронная почта отправителя';
$_lang['setting_emailsender_desc'] = 'Укажите адрес электронной почты, от имени которого будет производиться отправка писем.';
$_lang['setting_emailsender_err'] = 'Пожалуйста, укажите электронную почта отправителя.';

$_lang['setting_enable_dragdrop'] = 'Включить перетаскивание в древовидных меню ';
$_lang['setting_enable_dragdrop_desc'] = 'Если указано «Нет», перетаскивание будет недоступно для деревьев ресурсов и элементов.';

$_lang['setting_enable_template_picker_in_tree'] = 'Включить выбор шаблонов в деревьях ресурсов';
$_lang['setting_enable_template_picker_in_tree_desc'] = 'Включите это, чтобы использовать окно выбора шаблонов при создании нового ресурса в дереве.';

$_lang['setting_error_page'] = 'Страница ошибки 404 «Документ не найден»';
$_lang['setting_error_page_desc'] = 'Введите ID ресурса, который вы хотите использовать как страницу ошибки 404 «Документ не найден». <strong>ВАЖНО: убедитесь, что этот ID принадлежит существующему ресурсу, и что этот ресурс опубликован!</strong>';
$_lang['setting_error_page_err'] = 'Пожалуйста, укажите ID ресурса для ошибки 404 «Документ не найден».';

$_lang['setting_ext_debug'] = 'Режим отладки ExtJS';
$_lang['setting_ext_debug_desc'] = 'Включать или не включать загрузку ext-all-debug.js для отладки вашего кода на ExtJS.';

$_lang['setting_extension_packages'] = 'Пакеты расширений';
$_lang['setting_extension_packages_desc'] = 'JSON-массив с пакетами расширений, который необходимо загрузить при создании экземпляра класса MODX. В формате: [{"packagename":{path":"path/to/package"},{"anotherpkg":{"path":"path/to/otherpackage"}}]';

$_lang['setting_enable_gravatar'] = 'Использовать Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'Если этот параметр включен, в качестве иконки профиля будет использоваться фото из Gravatar (если пользователь не загружал свои фото в профиль).';

$_lang['setting_failed_login_attempts'] = 'Количество неудачных попыток входа';
$_lang['setting_failed_login_attempts_desc'] = 'Число неудачных попыток входа в систему управления, при превышении которого пользователь пользователь будет заблокирован.';

$_lang['setting_feed_modx_news'] = 'URL RSS-канала «Новости MODX»';
$_lang['setting_feed_modx_news_desc'] = 'Укажите URL RSS-канала для виджета «Новости MODX».';

$_lang['setting_feed_modx_news_enabled'] = 'Отображение RSS-канала «Новости MODX»';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Если выбрано «Нет», MODX будет скрывать ленту новостей на начальном экране системы управления.';

$_lang['setting_feed_modx_security'] = 'URL канала «Уведомления безопасности MODX»';
$_lang['setting_feed_modx_security_desc'] = 'Укажите URL RSS-канала для виджета «Уведомления безопасности MODX».';

$_lang['setting_feed_modx_security_enabled'] = 'Отображение RSS-канала «Уведомления безопасности MODX»';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Если выбрано «Нет», MODX будет скрывать ленту «Уведомления безопасности MODX» на начальном экране системы управления.';

$_lang['setting_form_customization_use_all_groups'] = 'Учитывать членство во всех группах пользователей для настройки форм';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Если выбрано «Да», для настройки форм будут использоваться все наборы правил для всех групп, в которые входит пользователь. В противном случае, будут использоваться наборы правил только для первичной группы. Важно: при включении этой настройки возможны ошибки из-за конфликтов наборов правил настройки форм.';

$_lang['setting_forward_merge_excludes'] = 'sendForward исключённые поля';
$_lang['setting_forward_merge_excludes_desc'] = 'При использовании символической ссылки её непустые поля переопределяют значения соответствующих полей целевого ресурса; используйте этот разделённый запятыми список полей для того, чтобы отключить переопределение полей ресурса полями символической ссылки.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Только строчные символы в псевдонимах';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Разрешить использовать только строчные символы в псевдонимах ресурсов.';

$_lang['setting_friendly_alias_max_length'] = 'Максимальная длина псевдонима';
$_lang['setting_friendly_alias_max_length_desc'] = 'Если указано больше нуля, заданное значение будет использоваться как максимальное число символов, допускаемое в псевдониме ресурсов. Ноль означает отсутствие ограничения.';

$_lang['setting_friendly_alias_realtime'] = 'Создавать ЧПУ-псевдоним (так называемые «дружественные URL») «на лету»';
$_lang['setting_friendly_alias_realtime_desc'] = 'Определяет, должен ли псевдоним ресурса создаваться «на лету» при вводе заголовка или это должно случаться когда ресурс сохранен (настройка «automatic_alias» должна быть включена, чтобы это работало).';

$_lang['setting_friendly_alias_restrict_chars'] = 'Метод фильтрации символов в псевдонимах';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'Метод фильтрации символов в псевдонимах ресурса. «pattern» - для фильтрации будет использоваться регулярное выражение, «legal» - псевдоним может состоять из любых допустимых в URL символов, «alpha» - псевдоним может состоять только из букв, и «alphanumeric» - псевдоним может состоять только из букв и цифр.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Шаблон для фильтрации символов в псевдонимах';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Регулярное выражение для ограничения символов, используемых в псевдонимах ресурсов.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Вырезать теги элементов из псевдонима';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Определяет, следует ли вырезать теги элементов из псевдонимов ресурсов.';

$_lang['setting_friendly_alias_translit'] = 'Транслитерация псевдонимов';
$_lang['setting_friendly_alias_translit_desc'] = 'Метод транслитерации используемый для псевдонимов ресурсов. Пусто или «none» - не использовать транслитерацию. Другие возможные значения: «iconv» (если доступно PHP-расширение «iconv») или название таблицы транслитерации, которая используется пользовательским классом транслитерации. Для включения транслитерации с русского языка надо установить пакет «translit», и в настойке «Транслитерация псевдонимов» написать «russian».';

$_lang['setting_friendly_alias_translit_class'] = 'Класс, управляющий транслитерацией псевдонимов';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Класс, производящий транслитерацию при генерации и фильтрации псевдонима ресурса.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Путь к классу, осуществляющему транслитерацию псевдонимов';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'Местоположение модели пакета, отвечающего за транслитерацию псевдонимов.';

$_lang['setting_friendly_alias_trim_chars'] = 'Символы, вырезаемые из псевдонима';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Символы, которые надо вырезать из окончания псевдонима.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Разделитель слов в псевдонимах';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Символ, который будет заменять пробелы между словами.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Разделители слов в псевдонимах';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Символы, представляющие собой разделители слов при обработке псевдонимов. Эти символы будут преобразованы в предпочитаемый символ-разделитель, указанный в настройке «friendly_alias_word_delimiter».';

$_lang['setting_friendly_urls'] = 'Использовать дружественные URL';
$_lang['setting_friendly_urls_desc'] = 'Эта настройка позволяет использовать в MODX дружественные URL. Обратите внимание, что это работает только на серверах Apache, и вам надо модифицировать файл .htaccess, чтобы этот механизм заработал. Для дополнительной информации смотрите пример файла .htaccess, поставляемого с MODX.';
$_lang['setting_friendly_urls_err'] = 'Пожалуйста, укажите, хотите ли вы использовать дружественные URL.';

$_lang['setting_friendly_urls_strict'] = 'Строгий режим дружественных URL';
$_lang['setting_friendly_urls_strict_desc'] = 'Если выбрано «Да», неканонические запросы, соответствующие ресурсу, будут перенаправлены с кодом 301 на канонический URI для этого ресурса. ПРЕДУПРЕЖДЕНИЕ. Не включайте, если вы используете пользовательские правила перезаписи, которые не совпадают, по крайней мере, с началом канонического URI. Например, канонический URI "foo/" с пользовательскими перезаписями для "foo/bar.html" будет работать, но попытки переписать "bar/foo.html" как "foo/" приведут к перенаправлению к "foo/", если эта опция включена.';

$_lang['setting_global_duplicate_uri_check'] = 'Проверять на дублирование URI во всех контекстах';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Выберите «Да», для того чтобы проверять на дублирование URI во всех контекстах. Если выбрано «Нет», будет проверяться только контекст, в котором ресурс сохраняется.';

$_lang['setting_hidemenu_default'] = 'Скрыть из меню по умолчанию';
$_lang['setting_hidemenu_default_desc'] = 'Выберите «Да», для того чтобы параметр «Скрыть из меню» был выбран по умолчанию, при создании новых ресурсов.';

$_lang['setting_inline_help'] = 'Показывать текст подсказки рядом с полем';
$_lang['setting_inline_help_desc'] = 'Если выбрано «Да», рядом с полем будет выводиться текст подсказки. Если выбрано «Нет», подсказка будет «всплывающей».';

$_lang['setting_link_tag_scheme'] = 'Схема URL';
$_lang['setting_link_tag_scheme_desc'] = 'Схема генерации URL для тега [[~id]]. Доступные опции смотрите <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\modX::makeUrl()">здесь</a>.';

$_lang['setting_locale'] = 'Локаль';
$_lang['setting_locale_desc'] = 'Устанавливает локаль для системы. Оставьте пустым чтобы использовать локаль по умолчанию. <a href="http://php.net/setlocale" target="_blank">Документация по настройке локалей в PHP</a> .';

$_lang['setting_lock_ttl'] = 'Время жизни блокировки';
$_lang['setting_lock_ttl_desc'] = 'Количество секунд, на которое будет оставаться блокировка ресурса, если пользователь неактивен.';

$_lang['setting_log_level'] = 'Уровень записи сообщений в журнал ошибок';
$_lang['setting_log_level_desc'] = 'Уровень записи сообщений в журнал ошибок. Чем меньше уровень, тем меньше сообщений будет записано. Возможные значения: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO), и 4 (DEBUG).';

$_lang['setting_log_target'] = 'Метод вывода журнала ошибок';
$_lang['setting_log_target_desc'] = 'Метод вывода сообщений журнала. Возможные значения: «FILE», «HTML», или «ECHO». По умолчанию используется «FILE».';

$_lang['setting_log_deprecated'] = 'Устаревшие функции в журнале ошибок';
$_lang['setting_log_deprecated_desc'] = 'Включить уведомления об использовании устаревших функций в журнале ошибок.';

$_lang['setting_mail_charset'] = 'Кодировка';
$_lang['setting_mail_charset_desc'] = 'Кодировка (по умолчанию) для электронных писем, такая как «iso-8859-1» или «utf-8»';

$_lang['setting_mail_encoding'] = 'Формат кодирования';
$_lang['setting_mail_encoding_desc'] = 'Установите формат кодирования для электронных писем. Это может быть «8bit», «7bit», «binary», «base64», и «quoted-printable».';

$_lang['setting_mail_use_smtp'] = 'Использовать SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Выберите «Да», для использования SMTP при отправки электронной почты.';

$_lang['setting_mail_smtp_auth'] = 'SMTP аутентификация';
$_lang['setting_mail_smtp_auth_desc'] = 'Выберите «Да», для SMTP аутентификации. Будут использоваться настройки «mail_smtp_user» и «mail_smtp_pass».';

$_lang['setting_mail_smtp_helo'] = 'SMTP Helo сообщение';
$_lang['setting_mail_smtp_helo_desc'] = 'Определяет сообщение SMTP HELO (по умолчанию имя хоста).';

$_lang['setting_mail_smtp_hosts'] = 'SMTP хосты';
$_lang['setting_mail_smtp_hosts_desc'] = 'Список хостов, разделенных запятыми. Для каждого хоста можно указать свой порт в следующем формате: [hostname:port] (например: "smtp1.example.com:25;smtp2.example.com"). MODX будет пытаться использовать хосты по порядку.';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP удержание соединения';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Предотвращать закрытие SMTP соединения после каждой отправки сообщения. Не рекомендуется.';

$_lang['setting_mail_smtp_pass'] = 'SMTP пароль';
$_lang['setting_mail_smtp_pass_desc'] = 'Пароль, используемый при SMTP авторизации.';

$_lang['setting_mail_smtp_port'] = 'SMTP номер порта';
$_lang['setting_mail_smtp_port_desc'] = 'Укажите порт SMTP сервера.';

$_lang['setting_mail_smtp_secure'] = 'SMTPS';
$_lang['setting_mail_smtp_secure_desc'] = 'Задает тип шифрования "SMTPS". Доступно: "", "ssl" или "tls".';

$_lang['setting_mail_smtp_autotls'] = 'Авто TLS для SMTP';
$_lang['setting_mail_smtp_autotls_desc'] = 'Включать ли TLS-шифрование автоматически, даже если "SMTPS" не установлено на "TLS". При условии, что сервер поддерживает "SMTPS".';

$_lang['setting_mail_smtp_single_to'] = 'SMTP посылать по одному';
$_lang['setting_mail_smtp_single_to_desc'] = 'Предоставляет возможность отправлять сообщения адресатам из поля «to» по одному, вместо разовой отправки на все адреса.';

$_lang['setting_mail_smtp_timeout'] = 'SMTP время ожидания';
$_lang['setting_mail_smtp_timeout_desc'] = 'Определяет время ожидания (timeout) SMTP сервера. Не работает на win32 серверах.';

$_lang['setting_mail_smtp_user'] = 'SMTP пользователь';
$_lang['setting_mail_smtp_user_desc'] = 'Пользователь, используемый при SMTP авторизации.';

$_lang['setting_main_nav_parent'] = 'Основное меню';
$_lang['setting_main_nav_parent_desc'] = 'Контейнер, содержащий все записи основного меню.';

$_lang['setting_manager_direction'] = 'Направление текста в системе управления';
$_lang['setting_manager_direction_desc'] = 'Выберите направление в котором будет генерироваться текст в системе управления (слева-направо или справа-налево).';

$_lang['setting_manager_date_format'] = 'Формат даты в системе управления';
$_lang['setting_manager_date_format_desc'] = 'Строка в формате PHP date(), определяющая формат даты в системе управления.';

$_lang['setting_manager_favicon_url'] = 'URL фавиконки системы управления';
$_lang['setting_manager_favicon_url_desc'] = 'Если задано, будет использоваться в качестве URL фавиконки системы управления MODX. Необходимо указать либо URL относительно директории /manager/, либо абсолютный URL.';

$_lang['setting_manager_login_url_alternate'] = 'Альтернативный URL страницы входа в систему управления';
$_lang['setting_manager_login_url_alternate_desc'] = 'Альтернативный URL, на который будет направлен неавторизованный пользователь при необходимости авторизации в системе управления. Форма входа должна авторизовать пользователя в контексте "mgr".';

$_lang['setting_manager_tooltip_enable'] = 'Включить подсказки в системе управления';
$_lang['setting_manager_tooltip_delay'] = 'Время задержки для подсказок в системе управления';

$_lang['setting_login_background_image'] = 'Фоновое изображение для страницы входа';
$_lang['setting_login_background_image_desc'] = 'Фоновое изображение для страницы входа в панель управления. Оно автоматически растянется, чтобы заполнить весь экран.';

$_lang['setting_login_logo'] = 'Логотип для страницы входа';
$_lang['setting_login_logo_desc'] = 'Логотип, который будет отображаться в левом верхнем углу страницы входа в панель управления. Если поле не заполнено, то будет отображаться логотип MODX.';

$_lang['setting_login_help_button'] = 'Показать кнопку Справки';
$_lang['setting_login_help_button_desc'] = 'Если включено, кнопка помощи отобразится на странице входа в панель управления. Можно настроить информацию, показанную со следующими записями лексикона в core/login: login_help_button_text, login_help_title, and login_help_text.';

$_lang['setting_manager_login_start'] = 'Страница входа в систему управления';
$_lang['setting_manager_login_start_desc'] = 'Введите ID ресурса, на который будет перенаправлен пользователь после входа в систему управления. <strong>ВАЖНО: убедитесь, что введённый вами ID принадлежит существующему ресурсу, что он опубликован и доступен для пользователя!</strong>';

$_lang['setting_manager_theme'] = 'Шаблон системы управления';
$_lang['setting_manager_theme_desc'] = 'Выберите шаблон для системы управления.';

$_lang['setting_manager_logo'] = 'Логотип панели управления';
$_lang['setting_manager_logo_desc'] = 'Логотип, показываемый в шапке панели управления.';

$_lang['setting_manager_time_format'] = 'Формат времени в системе управления';
$_lang['setting_manager_time_format_desc'] = 'Строка в формате PHP date(), определяющая формат отображения времени в системе управления.';

$_lang['setting_manager_use_tabs'] = 'Использовать вкладки в шаблоне системы управления';
$_lang['setting_manager_use_tabs_desc'] = 'Если выбрано «Да», то в системе управления будут использоваться вкладки. Иначе будут использоваться отдельные панели.';

$_lang['setting_manager_week_start'] = 'Первый день недели';
$_lang['setting_manager_week_start_desc'] = 'Укажите день, с которого начинается неделя. Используйте 0 (или оставьте поле пустым) для воскресенья, 1 для понедельника и т.д.';

$_lang['setting_mgr_tree_icon_context'] = 'Иконка контекста';
$_lang['setting_mgr_tree_icon_context_desc'] = 'CSS-класс, используемый для отображения иконки контекста в дереве. Вы можете использовать эту настройку для установки уникальной иконки каждому контексту.';

$_lang['setting_mgr_source_icon'] = 'Иконка источника файлов';
$_lang['setting_mgr_source_icon_desc'] = 'CSS-класс иконки, показывающей медиа-файлы в дереве. По умолчанию используется класс <b>icon-folder-open-o</b>, изображающий открытую папку';

$_lang['setting_modRequest.class'] = 'Класс-обработчик запросов';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Скрыть файлы в диспетчере файлов';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'Если выбрано «Да», файлы, располагающиеся в папке, не будут отображаться в дереве диспетчера файлов.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Отключить быстрый предпросмотр изображений';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'Если выбрано «Да», при наведении курсора на файл изображения всплывающее окно предпросмотра отображаться не будет.';

$_lang['setting_modx_browser_default_sort'] = 'Сортировка по умолчанию в диспетчере файлов';
$_lang['setting_modx_browser_default_sort_desc'] = 'Метод сортировки по умолчанию для диспетчера файлов. Возможные значения: «name» (название), «size» (размер), «lastmod» (изменён).';

$_lang['setting_modx_browser_default_viewmode'] = 'Режим просмотра по умолчанию в диспетчере файлов';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'Режим просмотра по умолчанию при использовании контекстного меню файлового менеджера в системе управления. Доступные значения: «grid» (таблица), «list» (список).';

$_lang['setting_modx_charset'] = 'Кодировка символов';
$_lang['setting_modx_charset_desc'] = 'Пожалуйста, укажите какую кодировку вы хотите использовать для системы управления. Обратите внимание, что MODX был протестирован со многими кодировками, но не со всеми. Для большинства языков предпочтительной является кодировка UTF-8.';

$_lang['setting_new_file_permissions'] = 'Права на новый файл';
$_lang['setting_new_file_permissions_desc'] = 'При загрузке нового файла через диспетчер файлов, будет произведена попытка установить права доступа к этому файлу в соответствии с этой настройкой. Может не работать на некоторых серверах, например IIS. В этом случае Вам следует вручную установить права.';

$_lang['setting_new_folder_permissions'] = 'Права на новую папку';
$_lang['setting_new_folder_permissions_desc'] = 'При создании новой папки через диспетчер файлов, будет произведена попытка установить права доступа к этой папке в соответствии с этой настройкой. Может не работать на некоторых серверах, например IIS. В этом случае вам следует вручную установить права.';

$_lang['setting_parser_recurse_uncacheable'] = 'Отложенный некэшируемый парсинг';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'Если отключено, некэшируемые элементы могут показывать кэшированное содержимое внутри кэшируемых элементов. Отключайте ТОЛЬКО если у вас есть проблемы со сложным вложенным парсингом, который перестал работать как ожидалось.';

$_lang['setting_password_generated_length'] = 'Длина сгенерированного пароля';
$_lang['setting_password_generated_length_desc'] = 'Длина сгенерированного пароля для пользователя.';

$_lang['setting_password_min_length'] = 'Минимальная длина пароля';
$_lang['setting_password_min_length_desc'] = 'Минимальная длина пароля для пользователя.';

$_lang['setting_preserve_menuindex'] = 'Сохранять индекс меню при дублировании ресурсов';
$_lang['setting_preserve_menuindex_desc'] = 'При дублировании ресурсов порядок расположения меню также будет сохранен.';

$_lang['setting_principal_targets'] = 'Целевые классы для загрузки списков контроля доступа';
$_lang['setting_principal_targets_desc'] = 'Настройте целевые классы, для которых необходимо загрузить списки контроля доступа пользователей.';

$_lang['setting_proxy_auth_type'] = 'Прокси тип авторизации';
$_lang['setting_proxy_auth_type_desc'] = 'Можно указать «BASIC» либо «NTLM».';

$_lang['setting_proxy_host'] = 'Прокси-хост';
$_lang['setting_proxy_host_desc'] = 'Если ваш сервер использует прокси, укажите имя прокси-хоста для того, чтобы сделать доступными некоторые функции MODX, такие как «Менеджер пакетов».';

$_lang['setting_proxy_password'] = 'Прокси-пароль';
$_lang['setting_proxy_password_desc'] = 'Пароль для авторизации на прокси-сервере.';

$_lang['setting_proxy_port'] = 'Прокси-порт';
$_lang['setting_proxy_port_desc'] = 'Порт прокси-сервера.';

$_lang['setting_proxy_username'] = 'Пользователь прокси-сервера';
$_lang['setting_proxy_username_desc'] = 'Имя пользователя для авторизации на прокси-сервере.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Разрешить источники выше корневой директории';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Разрешает или запрещает использование файлов, расположенных вне корневой директории, в качестве источников. Может быть использовано для систем с множеством контекстов, расположенных на разных виртуальных хостах.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb Максимальное время жизни кэша';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Удалять кэш изображений, которые не запрашивались больше указанного числа дней.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb Максимальный размер кэша';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Если размер кэша превысит указанное значение (в мегабайтах), то будет удалён кэш картинок, которые запрашивались наиболее давно.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Максимальное количество кэшированных файлов';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Если кэш превысит указанное число файлов, то будет удалён кэш картинок, которые запрашивались наиболее давно.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb Кэшировать файлы-источники';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Кэшировать или нет файлы источников при загрузке. Рекомендуем выключить.';

$_lang['setting_phpthumb_document_root'] = 'phpThumb Корневая директория';
$_lang['setting_phpthumb_document_root_desc'] = 'Установите эту настройку, если имеются проблемы, связанные с переменной «DOCUMENT_ROOT» или возникают ошибки с «OutputThumbnail» или «!is_resource». Установите необходимый абсолютный путь к корневой директории сервера. При пустом значении MODX будит использовать переменную «DOCUMENT_ROOT» сервера.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb Цвет фона сообщения об ошибке';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Шестнадцатиричное число, без символа #, определяет фон сообщения об ошибке.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb Размер шрифта сообщения об ошибке';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Размер шрифта, заданный в em.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb Цвет текста ошибки';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Шестнадцатиричное число, без символа #, определяет цвет текста сообщения об ошибке.';

$_lang['setting_phpthumb_far'] = 'phpThumb Принудительное соотношение сторон';
$_lang['setting_phpthumb_far_desc'] = 'Значение по умолчанию для параметра far когда он используется в MODX. По умолчанию значение C, которое заставляет сохранить пропорции относительно центра.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb Путь к ImageMagick';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Необязательно. Устанавливает альтернативный путь к ImageMagick здесь для генерации эскизов с phpThumb, если не задано в PHP по умолчанию.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Отключить хотлинкинг';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Удаленные серверы разрешены в параметре src пока отключен hotlinking в phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Удалять изображения при включенном Hotlinking';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Сообщает, когда нельзя удалить изображение, сгенерированное на удаленном сервере.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Сообщение о запрете Hotlinking';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Сообщение, которое отображается вместо картинок, когда hotlinking запрещен.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Допустимые домены для Hotlinking';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Разделенный запятыми список имен хостов, которые допустимы в ссылках в src.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Запрещать внешние ссылки';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Запрещает другим использовать phpThumb для генерации изображений на их собственных сайтах.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Удалять изображения по внешним ссылкам';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Сообщает, когда нельзя удалить изображение, связанное с удаленным сервером.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Требовать указание referrer для внешних подключений';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Если выбрано «Да», любые внешние запросы без разрешенного заголовка referrer будут отклонены.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Сообщение о недоступности внешних ссылок';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Сообщение, которое отображается вместо картинок, когда внешнее подключение отклонено.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Допустимые домены для внешних ссылок';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Разделенный запятыми список имен хостов, которым разрешено внешнее подключение.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Адрес водяного знака для внешних запросов';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Необязательно. Допустимый путь к файлу, который будет использоваться в качестве водяного знака, когда ваши изображения отображаются вне сайта для phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Кадрирование';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Значение zc по умолчанию для использования в MODX. По умолчанию 0, что предотвращает обрезку с увеличением (zoom cropping).';

$_lang['setting_publish_default'] = ' Публиковать по умолчанию';
$_lang['setting_publish_default_desc'] = 'Выберите «Да» если хотите, чтобы все новые ресурсы сразу становились опубликованными.';
$_lang['setting_publish_default_err'] = 'Пожалуйста, укажите хотите ли вы чтобы новые ресурсы по умолчанию публиковались.';

$_lang['setting_quick_search_in_content'] = 'Разрешить поиск по содержимому';
$_lang['setting_quick_search_in_content_desc'] = 'Если выбрано «Да», то содержимое элемента (ресурса, шаблона, чанка и т.д.) также будет доступно для быстрого поиска.';

$_lang['setting_quick_search_result_max'] = 'Количество элементов в результатах поиска';
$_lang['setting_quick_search_result_max_desc'] = 'Максимальное количество элементов для каждого типа (ресурс, шаблон, чанк и т.д.) в результатах быстрого поиска.';

$_lang['setting_request_controller'] = 'Название файла контроллера запроса';
$_lang['setting_request_controller_desc'] = 'Название файла основного контроллера запроса, из которого MODX загружается. Большинство пользователей может оставить значение «index.php».';

$_lang['setting_request_method_strict'] = 'Строгий метод запроса';
$_lang['setting_request_method_strict_desc'] = 'Если выбрано «Да», запросы через параметр ID будут игнорироваться при включённых дружественных URL. Если дружественные URL отключены, то запросы с использованием псевдонима будут игнорироваться.';

$_lang['setting_request_param_alias'] = 'Название параметра запроса для псевдонима';
$_lang['setting_request_param_alias_desc'] = 'Название GET-параметра, передающего псевдоним ресурса, при использовании дружественных URL.';

$_lang['setting_request_param_id'] = 'Название параметра запроса для ID';
$_lang['setting_request_param_id_desc'] = 'Название GET-параметра, передающего ID ресурса, когда дружественные URL отключены.';

$_lang['setting_resource_tree_node_name'] = 'Поле для названия узла в дереве ресурсов';
$_lang['setting_resource_tree_node_name_desc'] = 'Укажите поле ресурса, которое будет использоваться в качестве названия узла в дереве ресурсов. По умолчанию поле «pagetitle», любое поле ресурса может быть использовано: «menutitle», «alias», «longtitle», и т.п.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Запасное поле для узла в дереве ресурсов';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Укажите поле ресурса для использования в качестве запасного названия узла в дереве ресурсов. Это значение будет использоваться, если ресурс имеет пустое значение для заданного поля ресурса в дереве.';

$_lang['setting_resource_tree_node_tooltip'] = 'Поле подсказки для ресурса в дереве ресурсов';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Укажите поле ресурса для использования в качестве всплывающей подсказки в дереве ресурсов. Любое поле ресурса может быть использовано: «menutitle», «alias», «longtitle», и т.п. Если не указано, будет использовано «longtitle» с «description» под ним.';

$_lang['setting_richtext_default'] = 'Использовать визуальный редактор';
$_lang['setting_richtext_default_desc'] = 'Выберите «Да», чтобы все новые ресурсы использовали визуальный текстовый редактор по умолчанию.';

$_lang['setting_search_default'] = '«Доступен для поиска» по умолчанию';
$_lang['setting_search_default_desc'] = 'Выберите «Да» для того, чтобы сделать все новые ресурсы доступными для поиска по умолчанию.';
$_lang['setting_search_default_err'] = 'Пожалуйста, укажите, хотите ли вы чтобы ресурсы были доступны для поиска по умолчанию.';

$_lang['setting_server_offset_time'] = 'Разница во времени';
$_lang['setting_server_offset_time_desc'] = 'Укажите разницу в часах между вашим локальным временем и временем сервера.';

$_lang['setting_session_cookie_domain'] = 'Домен для сессионных куки';
$_lang['setting_session_cookie_domain_desc'] = 'Используйте эту настройку для указания доменного имени для сессионных куки. При пустом значении, в качестве доменного имени будет использоваться текущий домен.';

$_lang['setting_session_cookie_samesite'] = 'Session Cookie Samesite';
$_lang['setting_session_cookie_samesite_desc'] = 'Выберите Lax или Strict.';

$_lang['setting_session_cookie_lifetime'] = 'Длительность хранения куки сессий';
$_lang['setting_session_cookie_lifetime_desc'] = 'Используйте эту настройку для выбора длительности хранения сессионных куки в секундах. Эта настройка используется для определения длительности хранения клиентских сессионных куки при выборе опции «запомнить меня» во время аутентификации.';

$_lang['setting_session_cookie_path'] = 'Путь для сессионных куки';
$_lang['setting_session_cookie_path_desc'] = 'Используйте эту настройку для задания пути для сессионных куки. Оставьте значение пустым для использования «MODX_BASE_URL» в качестве пути.';

$_lang['setting_session_cookie_secure'] = 'Шифрование сессионных куки';
$_lang['setting_session_cookie_secure_desc'] = 'Включите эту настройку для использования шифрования сессионных куки.';

$_lang['setting_session_cookie_httponly'] = 'Сессионные куки в режиме HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Включите эту настройку для установки флага HttpOnly для сессионых кук.';

$_lang['setting_session_gc_maxlifetime'] = 'Максимальное время жизни сессии';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Allows customization of the session.gc_maxlifetime PHP ini setting when using \'MODX\\Revolution\\modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Название класса-обработчика сессий';
$_lang['setting_session_handler_class_desc'] = 'For database managed sessions, use \'MODX\\Revolution\\modSessionHandler\'.  Leave this blank to use standard PHP session management.';

$_lang['setting_session_name'] = 'Имя сессии';
$_lang['setting_session_name_desc'] = 'Используйте эту настройку для указания сессионного имени, используемого в сессиях MODX. Оставьте значение пустым для использования имени PHP-сессии по умолчанию.';

$_lang['setting_settings_version'] = 'Версия настроек';
$_lang['setting_settings_version_desc'] = 'Установленная версия MODX.';

$_lang['setting_settings_distro'] = 'Дистрибутив';
$_lang['setting_settings_distro_desc'] = 'Версия установленного дистрибутива MODX.';

$_lang['setting_set_header'] = 'Посылать HTTP заголовки';
$_lang['setting_set_header_desc'] = 'Если выбрано «Да», MODX будет пытаться установить HTTP-заголовки для ресурсов.';

$_lang['setting_send_poweredby_header'] = 'Отправлять заголовок X-Powered-By';
$_lang['setting_send_poweredby_header_desc'] = 'Если выбрано «Да», MODX будет отправлять заголовок «X-Powered-By», чтобы обозначить этот сайт как созданный на MODX. Это помогает отследить глобальное использование MODX с помощью сторонних трекеров, проверяющих ваш сайт. Поскольку это облегчает определение системы, на которой создан ваш сайт, это может несколько увеличить риски с точки зрения безопасности в том случае, если в MODX будет найдена уязвимость.';

$_lang['setting_show_tv_categories_header'] = 'Показывать заголовок «Категории» над вкладками с категориями при выводе TV';
$_lang['setting_show_tv_categories_header_desc'] = 'Если указано «Да», над вкладками категорий TV будет отображен заголовок «Категории».';

$_lang['setting_signupemail_message'] = 'Письмо регистрации';
$_lang['setting_signupemail_message_desc'] = 'Здесь вы можете установить сообщение, отправляемое вашим пользователям после регистрации учётной записи, с данными об их имени учётной записи и пароле. <br /><strong>ВАЖНО:<strong> Следующие плейсхолдеры заменяются MODX перед отправкой письма: <br /><br />[[+sname]] - название вашего сайта, <br />[[+saddr]] - адрес электронной почты вашего сайта, <br />[[+surl]] - url вашего сайта, <br />[[+uid]] - имя учётной записи пользователя (логин) или ID, <br />[[+pwd]] - пароль пользователя, <br />[[+ufn]] - полное имя пользователя. <br /><br /><strong>Обязательно укажите тэги [[+uid]] и [[+pwd]] в письме, иначе ваши пользователи не смогут узнать свои имя учётной записи и пароль!</strong>';
$_lang['setting_signupemail_message_default'] = 'Здравствуйте, [[+uid]] \n\nВаши данные регистрации на сайте [[+sname]]:\n\nИмя пользователя: [[+uid]]\nПароль: [[+pwd]]\n\nКак только вы авторизуетесь на сайте ([[+surl]]), вы сможете поменять свой пароль.\n\nС уважением,\nАдминистрация сайта';

$_lang['setting_site_name'] = 'Название сайта';
$_lang['setting_site_name_desc'] = 'Введите название вашего сайта.';
$_lang['setting_site_name_err']  = 'Пожалуйста, введите название сайта.';

$_lang['setting_site_start'] = 'Главная страница сайта';
$_lang['setting_site_start_desc'] = 'Введите ID ресурса, который вы хотите использовать в качестве «Главной страницы сайта». <strong>ВАЖНО: убедитесь, что этот ID принадлежит существующему ресурсу и что этот ресурс опубликован!</strong>';
$_lang['setting_site_start_err'] = 'Пожалуйста, укажите ID ресурса, который будет «Главной страницей сайта».';

$_lang['setting_site_status'] = 'Сайт опубликован';
$_lang['setting_site_status_desc'] = 'Выберите «Да» для публикации вашего сайта в сети. Если вы выберите «Нет», ваши посетители увидят «Сообщение о недоступности сайта», и не смогут просматривать содержимое сайта.';
$_lang['setting_site_status_err'] = 'Пожалуйста, выберите «Да», если сайт работает или «Нет», если сайт не работает.';

$_lang['setting_site_unavailable_message'] = 'Сообщение о недоступности сайта';
$_lang['setting_site_unavailable_message_desc'] = 'Сообщение, которое будет показано в случае, если сайт недоступен или возникла ошибка. <strong>ВАЖНО: Это сообщение выводится только в случае, если не указана страница «Сайт недоступен».</strong>';

$_lang['setting_site_unavailable_page'] = 'Страница ошибки 503 «Сайт недоступен»';
$_lang['setting_site_unavailable_page_desc'] = 'Введите ID ресурса, который вы хотите использовать в качестве страницы ошибки 503 «Сайт недоступен». <strong>ВАЖНО: убедитесь, что этот ID принадлежит существующему ресурсу и этот ресурс опубликован!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Пожалуйста, укажите ID ресурса для страницы ошибки 503 «Сайт недоступен».';

$_lang['setting_static_elements_automate_templates'] = 'Автоматизировать статические элементы для шаблонов?';
$_lang['setting_static_elements_automate_templates_desc'] = 'Это автоматизирует обработку статических файлов, таких как создание и удаление статических файлов для шаблонов.';

$_lang['setting_static_elements_automate_tvs'] = 'Автоматизировать статические элементы для TV?';
$_lang['setting_static_elements_automate_tvs_desc'] = 'Это автоматизирует обработку статических файлов, таких как создание и удаление статических файлов для TV.';

$_lang['setting_static_elements_automate_chunks'] = 'Автоматизировать статические элементы для чанков?';
$_lang['setting_static_elements_automate_chunks_desc'] = 'Это автоматизирует обработку статических файлов, таких как создание и удаление статических файлов для чанков.';

$_lang['setting_static_elements_automate_snippets'] = 'Автоматизировать статические элементы для сниппетов?';
$_lang['setting_static_elements_automate_snippets_desc'] = 'Это автоматизирует обработку статических файлов, таких как создание и удаление статических файлов для сниппетов.';

$_lang['setting_static_elements_automate_plugins'] = 'Автоматизировать статические элементы для плагинов?';
$_lang['setting_static_elements_automate_plugins_desc'] = 'Это автоматизирует обработку статических файлов, таких как создание и удаление статических файлов для плагинов.';

$_lang['setting_static_elements_default_mediasource'] = 'Источник файлов для статических элементов по умолчанию';
$_lang['setting_static_elements_default_mediasource_desc'] = 'Укажите источник файлов по умолчанию, где будут храниться статические элементы.';

$_lang['setting_static_elements_default_category'] = 'Категория для статических элементов по умолчанию';
$_lang['setting_static_elements_default_category_desc'] = 'Укажите категорию по умолчанию для новых статических элементов.';

$_lang['setting_static_elements_basepath'] = 'Путь к файлам статических элементов';
$_lang['setting_static_elements_basepath_desc'] = 'Путь к файлам, где хранятся статические элементы.';

$_lang['setting_resource_static_allow_absolute'] = 'Разрешить абсолютный путь к статическим ресурсам';
$_lang['setting_resource_static_allow_absolute_desc'] = 'Эта настройка позволяет пользователям вводить абсолютный путь к любому читаемому файлу на сервере в качестве содержимого статического ресурса. Важно: включение этой настройки может рассматриваться как риск безопасности! Настоятельно рекомендуется держать эту настройку выключенной, если вы не доверяете каждому пользователю системы управления.';

$_lang['setting_resource_static_path'] = 'Путь к статическим ресурсам';
$_lang['setting_resource_static_path_desc'] = 'При отключении "resource_static_allow_absolute" статические ресурсы могут находиться только в пределах пути, указанного здесь. Важно: настройка может позволить пользователям читать файлы, которые они не должны читать! Настоятельно рекомендуется ограничить пользователей определенным каталогом, таким как {core_path}static/ или {assets_path}.';

$_lang['setting_symlink_merge_fields'] = 'Объединять поля ресурса с полями символической ссылки';
$_lang['setting_symlink_merge_fields_desc'] = 'Если установлено значение «Да», то непустые поля символической ссылки заменят поля целевого ресурса при переадресации с использованием символической ссылки.';

$_lang['setting_syncsite_default'] = 'Очищать кэш по умолчанию';
$_lang['setting_syncsite_default_desc'] = 'Если выбрано «Да», при сохранении ресурса кэш будет очищаться по умолчанию.';
$_lang['setting_syncsite_default_err'] = 'Пожалуйста, укажите, хотите ли вы или нет, чтобы кэш очищался по умолчанию при сохранении ресурса.';

$_lang['setting_topmenu_show_descriptions'] = 'Показывать описания пунктов в главном меню';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Если выбрано «Нет», MODX будет скрывать дополнительное описание для пунктов главного меню.';

$_lang['setting_tree_default_sort'] = 'Поле сортировки дерева ресурсов';
$_lang['setting_tree_default_sort_desc'] = 'Поле, по которому сортируется дерево ресурсов при загрузке.';

$_lang['setting_tree_root_id'] = 'ID корня дерева ресурсов';
$_lang['setting_tree_root_id_desc'] = 'Укажите ID ресурса, который будет корнем дерева ресурсов. Пользователь будет иметь возможность видеть только дочерние ресурсы этого ресурса.';

$_lang['setting_tvs_below_content'] = 'Разместить TV ниже контента';
$_lang['setting_tvs_below_content_desc'] = 'Если выбрано «Да», TV будут размещены ниже поля «Содержимое» при редактировании ресурса.';

$_lang['setting_ui_debug_mode'] = 'Режим отладки для интерфейса системы управления';
$_lang['setting_ui_debug_mode_desc'] = 'Если выбрано «Да», в консоль браузера будут выводиться отладочные сообщения интерфейса системы управления. Вы должны использовать браузер, поддерживающий console.log.';

$_lang['setting_unauthorized_page'] = 'Страница ошибки 401 «Доступ запрещен»';
$_lang['setting_unauthorized_page_desc'] = 'Введите ID ресурса, который вы хотите выводить пользователям при запросе защищённых или требующих авторизации ресурсов. <strong>ВАЖНО: убедитесь, что введённый ID принадлежит существующему ресурсу и этот ресурс опубликован и публично доступен!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Пожалуйста укажите ID ресурса, который будет являться страницей ошибки 401 «Доступ запрещен».';

$_lang['setting_upload_files'] = 'Разрешённые к загрузке файлы';
$_lang['setting_upload_files_desc'] = 'Здесь вы можете указать список типов файлов, которые можно загружать, используя диспетчер файлов. Пожалуйста, введите расширения файлов, разделяя их запятыми.';

$_lang['setting_upload_file_exists'] = 'Проверять файлы на существование при загрузке';
$_lang['setting_upload_file_exists_desc'] = 'Если включено, при загрузке файла с таким же именем будет показано сообщение с ошибкой. Если отключено, то существующий файл будет перезаписан новым.';

$_lang['setting_upload_images'] = 'Разрешенные к загрузке изображения';
$_lang['setting_upload_images_desc'] = 'Здесь вы можете ввести список типов файлов, которые можно загружать в каталог "assets/images/", используя диспетчер файлов. Пожалуйста, введите расширения файлов-изображений, разделяя их запятыми.';

$_lang['setting_upload_maxsize'] = 'Максимальный размер загрузки';
$_lang['setting_upload_maxsize_desc'] = 'Введите максимальный размер файла, возможный для загрузки через диспетчер файлов. Размер файла должен быть введен в байтах.';

$_lang['setting_upload_media'] = 'Разрешенные к загрузке медиа-файлы';
$_lang['setting_upload_media_desc'] = 'Здесь вы можете ввести список типов файлов, которые можно загружать в каталог "assets/media/", используя диспетчер файлов. Пожалуйста, введите расширения медиа-файлов, разделяя их запятыми.';

$_lang['setting_upload_translit'] = 'Транслитерировать имена загружаемых файлов?';
$_lang['setting_upload_translit_desc'] = 'Если включено, то название загружаемого файла будет транслироваться в соответствии с глобальными правилами транслитерации.';

$_lang['setting_use_alias_path'] = 'Использовать вложенные URL';
$_lang['setting_use_alias_path_desc'] = 'Установка значения «Да» для этой опции произведет вывод полного пути к ресурсу, если у ресурса есть псевдоним. Например, если ресурс с псевдонимом "child" расположен внутри ресурса-контейнера с псевдонимом "parent", то полный путь к ресурсу будет выведен так: "/parent/child.html".<br /><strong>ВАЖНО: Устанавливая значение «Да» для этой опции, используйте полный путь для указания пути к таким файлам, как изображения, CSS, JavaScript, и т.д.: например, "/assets/images", а не "assets/images". Или же используйте тег &lt;base /&gt; для явного указания базового URL.</strong>';

$_lang['setting_use_editor'] = 'Использовать текстовый редактор';
$_lang['setting_use_editor_desc'] = 'Хотите ли вы использовать текстовый редактор? Если вам удобнее использовать HTML, можете отключить текстовый редактор с помощью этой опции. Имейте в виду, что эта опция применяется ко всем документам и пользователям!';
$_lang['setting_use_editor_err'] = 'Пожалуйста, укажите, хотите вы или нет использовать RTE редактор.';

$_lang['setting_use_frozen_parent_uris'] = 'Использовать «замороженные» URI родителя';
$_lang['setting_use_frozen_parent_uris_desc'] = 'Если выбрано «Да», URI для дочерних ресурсов будет генерироваться с учётом «замороженного» URI родителя, игнорируя псевдонимы ресурсов выше по дереву.';

$_lang['setting_use_multibyte'] = 'Использовать библиотеку "mbstring"';
$_lang['setting_use_multibyte_desc'] = 'Включите, если вы хотите использовать библиотеку «mbstring» для работы с многобайтовыми кодировками. Включайте только, если библиотека «mbstring» установлена на вашем сервере.';

$_lang['setting_use_weblink_target'] = 'Использовать целевую веб-ссылку';
$_lang['setting_use_weblink_target_desc'] = 'Если выбрано «Да», MODX теги ссылок и makeUrl() API вызов будут генерировать конечные ссылки, указанные как целевые URL для ресурсов типа «ссылка». В противном случае, будет сгенерирована внутренняя ссылка, перенаправляющая на целевой URL.';

$_lang['setting_user_nav_parent'] = 'Меню пользователя';
$_lang['setting_user_nav_parent_desc'] = 'Контейнер, содержащий все записи меню пользователя.';

$_lang['setting_welcome_screen'] = 'Показывать окно приветствия';
$_lang['setting_welcome_screen_desc'] = 'Если выбрано «Да», всплывающее окно приветствия будет однократно отображено при следующей загрузке начального экрана.';

$_lang['setting_welcome_screen_url'] = 'URL окна приветствия';
$_lang['setting_welcome_screen_url_desc'] = 'URL всплывающего окна приветствия, отображаемого при первой загрузке MODX Revolution.';

$_lang['setting_welcome_action'] = 'Действие начального экрана';
$_lang['setting_welcome_action_desc'] = 'Контроллер по умолчанию при входе в систему управления в случае, когда контроллер не задан в URL.';

$_lang['setting_welcome_namespace'] = 'Пространство имен начального экрана';
$_lang['setting_welcome_namespace_desc'] = 'Пространство имен, которому принадлежит действие начального экрана.';

$_lang['setting_which_editor'] = 'Редактор';
$_lang['setting_which_editor_desc'] = 'Здесь вы можете выбрать, какой редактор использовать. Вы можете скачать и установить дополнительные редакторы в разделе «Менеджер пакетов».';

$_lang['setting_which_element_editor'] = 'Редактор кода';
$_lang['setting_which_element_editor_desc'] = 'Здесь вы можете выбрать какой редактор использовать при редактировании кода. Вы можете скачать и установить дополнительные редакторы в разделе «Менеджер пакетов».';

$_lang['setting_xhtml_urls'] = 'XHTML-совместимые URL';
$_lang['setting_xhtml_urls_desc'] = 'Если выбрано «Да», все ссылки, генерируемые MODX, будут XHTML-совместимыми (символ &amp; будет заменен на соответсвующую сущность &amp;amp;).';

$_lang['setting_default_context'] = 'Контекст по умолчанию';
$_lang['setting_default_context_desc'] = 'Выберите контекст, используемый по умолчанию при создании нового ресурса.';

$_lang['setting_auto_isfolder'] = 'Автоматически помечать как «контейнер»';
$_lang['setting_auto_isfolder_desc'] = 'Если настройка включена, ресурс будет помечен как «контейнер» автоматически.';

$_lang['setting_default_username'] = 'Имя пользователя по умолчанию';
$_lang['setting_default_username_desc'] = 'Имя пользователя по умолчанию для не авторизованных пользователей.';

$_lang['setting_manager_use_fullname'] = 'Отображать «Полное имя» в «шапке» системы управления';
$_lang['setting_manager_use_fullname_desc'] = 'Если настройка включена, в шапке системы управления будет отображаться содержимое поля «Полное имя» вместо «Имя пользователя»';

$_lang['setting_log_snippet_not_found'] = 'Записывать ошибки в журнал, если сниппет не найден';
$_lang['setting_log_snippet_not_found_desc'] = 'Если выбрано «Да», то при вызове сниппетов, которые не были найдены, появится запись в «Журнале ошибок».';

$_lang['setting_error_log_filename'] = 'Название файла журнала ошибок';
$_lang['setting_error_log_filename_desc'] = 'Задайте название файла журнала ошибок MODX (включая расширение файла).';

$_lang['setting_error_log_filepath'] = 'Путь к журналу ошибок';
$_lang['setting_error_log_filepath_desc'] = 'Дополнительно укажите абсолютный путь к пользовательскому файлу ошибок. Вы можете использовать плейсхолдер {cache_path}.';

$_lang['setting_passwordless_activated'] = 'Включить вход без пароля';
$_lang['setting_passwordless_activated_desc'] = 'Если включено, пользователи будут входить с помощью одноразовой ссылки, по адресу электронной почты, без ввода имени пользователя и пароля.';

$_lang['setting_passwordless_expiration'] = 'Срок действия входа без пароля';
$_lang['setting_passwordless_expiration_desc'] = 'Продолжительность времени в секундах, на которое действительна одноразовая ссылка для входа.';

$_lang['setting_static_elements_html_extension'] = 'Static elements html extension';
$_lang['setting_static_elements_html_extension_desc'] = 'Расширение для файлов, используемых статическими элементами с HTML-содержимым.';

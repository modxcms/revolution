<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Розділ';
$_lang['area_authentication'] = 'Автентифікація і безпека';
$_lang['area_caching'] = 'Кешування';
$_lang['area_core'] = 'Код ядра';
$_lang['area_editor'] = 'Текстовий редактор';
$_lang['area_file'] = 'Файлова система';
$_lang['area_filter'] = 'Фільтр за розділом...';
$_lang['area_furls'] = 'Дружні URL';
$_lang['area_gateway'] = 'Шлюз';
$_lang['area_language'] = 'Словник і мова';
$_lang['area_mail'] = 'Пошта';
$_lang['area_manager'] = 'Панель управління';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Проксі';
$_lang['area_session'] = 'Сеанси та куки';
$_lang['area_static_elements'] = 'Статичні елементи';
$_lang['area_static_resources'] = 'Статичні ресурси';
$_lang['area_lexicon_string'] = 'Запис словника для розділу';
$_lang['area_lexicon_string_msg'] = 'Введіть ключ запису словника розділу. Якщо запис з таким ключем не знайдено, з\'явиться сам ключ. <br />Розділи простору "core": authentication, caching, file, furls, gateway, language, manager, session, site, system';
$_lang['area_site'] = 'Сайт';
$_lang['area_system'] = 'Система і сервер';
$_lang['areas'] = 'Розділи';
$_lang['charset'] = 'Кодування';
$_lang['country'] = 'Країна';
$_lang['description_desc'] = 'Короткий опис налаштування. Можна вказати запис словника на основі ключа формату «setting_» + ключ + «_desc».';
$_lang['key_desc'] = 'Ключ налаштування. Буде доступний у контенті через плейсхолдер [[++key]]';
$_lang['name_desc'] = 'Назва налаштування. Можна вказати запис словника, який базується на ключі, формату «setting_» + ключ.';
$_lang['namespace'] = 'Простір імен';
$_lang['namespace_desc'] = 'Простір імен, з яким це налаштування пов\'язане. Тема словника "default" для цього простору імен буде використана під час перегляду.';
$_lang['namespace_filter'] = 'Фільтр за простором імен...';
$_lang['setting_err'] = 'Перевірте свої дані на наступні поля: ';
$_lang['setting_err_ae'] = 'Налаштування з таким ключем є. Будь ласка, вкажіть інший ключ.';
$_lang['setting_err_nf'] = 'Налаштування не знайдено.';
$_lang['setting_err_ns'] = 'Налаштування не вказано';
$_lang['setting_err_not_editable'] = 'This setting can\'t be edited in the grid. Please use the gear/context menu to edit the value!';
$_lang['setting_err_remove'] = 'An error occurred while trying to delete the setting.';
$_lang['setting_err_save'] = 'Помилка при спробі зберегти налаштування.';
$_lang['setting_err_startint'] = 'Назва настройки не може починатися з цифри.';
$_lang['setting_err_invalid_document'] = 'Документ із ID %d не існує. Будь ласка, вкажіть існуючий документ.';
$_lang['setting_remove_confirm'] = 'Ви впевнені, що хочете видалити це налаштування? Це може порушити встановлення MODX.';
$_lang['settings_after_install'] = 'У разі нової установки вам необхідно проконтролювати введені налаштування та, при необхідності, змінити їх. Після того, як ви перевірите налаштування, натисніть «Зберегти» для оновлення налаштувань бази даних.<br /><br />';
$_lang['settings_desc'] = 'Тут ви можете змінити основні опції та налаштування системи керування MODX, а також сайту. <b>Кожна настройка буде доступна через плейсхолдер [[++key]].</b><br />Двічі натисніть за параметром у колонці «Значення» для редагування, або натисніть правою кнопкою миші для інших дій. Щоб побачити опис налаштування, натисніть «+».';
$_lang['settings_furls'] = 'Дружні URL';
$_lang['settings_misc'] = 'Різне';
$_lang['settings_site'] = 'Сайт';
$_lang['settings_ui'] = 'Інтерфейс &amp; Можливості';
$_lang['settings_users'] = 'Користувач';
$_lang['system_settings'] = 'Системні налаштування';
$_lang['usergroup'] = 'Група користувачів';

// user settings
$_lang['setting_access_category_enabled'] = 'Перевіряти доступ до категорій';
$_lang['setting_access_category_enabled_desc'] = 'Використовуйте для увімкнення або вимкнення перевірки доступу до категорій. <strong>ВАЖЛИВО: Якщо цей параметр встановлено у "Ні", то всі політики доступу до категорій ігноруватимуться!</strong>';

$_lang['setting_access_context_enabled'] = 'Перевіряти доступ до контекстів';
$_lang['setting_access_context_enabled_desc'] = 'Використовуйте для увімкнення або вимкнення перевірки доступу до контекстів. <strong>ВАЖЛИВО: Якщо цей параметр встановлено у "Ні", то всі політики доступу до категорій ігноруватимуться. НЕ ВИМИКАЙТЕ перевірку прав доступу до контекстів для всієї системи або для контексту "mgr", оскільки таким чином Ви вимкнете доступ до інтерфейсу системи управління сайтом.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Перевіряти доступ до груп ресурсів';
$_lang['setting_access_resource_group_enabled_desc'] = 'Використовуйте для увімкнення або вимкнення перевірки доступу до груп ресурсів. <strong>ВАЖЛИВО: Якщо цей параметр встановлено у "Ні", то всі політики доступу до груп ресурсів ігноруватимуться!</strong>';

$_lang['setting_allow_mgr_access'] = 'Доступ до системи керування';
$_lang['setting_allow_mgr_access_desc'] = 'Активуйте цю опцію для доступу до системи керування.<br /><strong>ВАЖЛИВО: Якщо це налаштування встановлено в «Ні», то користувачі будуть перенаправлені на сторінку авторизації або на головну сторінку.</strong>';

$_lang['setting_failed_login'] = 'Ліміт спроб авторизації';
$_lang['setting_failed_login_desc'] = 'Тут Ви можете вказати кількість невдалих спроб входу до системи, при перевищенні якої користувача буде заблоковано.';

$_lang['setting_login_allowed_days'] = 'Дозволені дні';
$_lang['setting_login_allowed_days_desc'] = 'Виберіть дні, коли цей користувач може увійти.';

$_lang['setting_login_allowed_ip'] = 'Дозволені IP адреси';
$_lang['setting_login_allowed_ip_desc'] = 'Вкажіть IP-адреси через кому, з яких користувачеві дозволено входити в систему керування.';

$_lang['setting_login_homepage'] = 'Головна сторінка авторизованого користувача';
$_lang['setting_login_homepage_desc'] = 'Вкажіть ID ресурсу, на який користувач буде перенаправлений після авторизації. <strong>ВАЖЛИВО: переконайтеся, що ID належить існуючому та опублікованому ресурсу, і що користувач має право на його перегляд!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Версія системи управління політиками доступу';
$_lang['setting_access_policies_version_desc'] = 'Версія системи керування політиками доступу. НЕ МЕНЯЙТЕ!';

$_lang['setting_allow_forward_across_contexts'] = 'Дозволити перенаправлення через контексти';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Якщо дозволено, символічні посилання та метод API modX::sendForward() зможуть перенаправляти запити на ресурси з інших контекстів.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Дозволити "Забув пароль" на сторінці входу до системи';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Встановлення значення "Ні" вимкне функцію "Забув пароль" на сторінці авторизації Менеджера.';

$_lang['setting_allow_tags_in_post'] = 'Дозволити передачу HTML тегів у POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Якщо вибрано «Ні», всі параметри POST в межах системи керування будуть очищені від будь-яких тегів. MODX рекомендує залишити це налаштування увімкненим («Так»).';

$_lang['setting_allow_tv_eval'] = 'Дозволити виконання коду у TV';
$_lang['setting_allow_tv_eval_desc'] = 'Виберіть цей параметр, щоб дозволити або заборонити виконання коду TV. Якщо вибрано «Ні», код або значення буде оброблено як звичайний текст.';

$_lang['setting_anonymous_sessions'] = 'Анонімні сесії';
$_lang['setting_anonymous_sessions_desc'] = 'Якщо вимкнено, то лише досвідчені користувачі матимуть доступ до сеансу PHP. Це може знизити накладні витрати для анонімних користувачів і завантаження, яке вони накладають на сайт MODX, якщо їм не потрібен доступ до унікальної сесії. Якщо session_enabled є false, цей параметр не має ефекту, як сеанси ніколи не будуть доступні.';

$_lang['setting_archive_with'] = 'Використати PCLZip';
$_lang['setting_archive_with_desc'] = 'Якщо вибрано "Так", роботи з zip-архівами буде використовуватися PCLZip замість ZipArchive. Виберіть "Так", якщо у вас виникають проблеми з розпакуванням пакетів у "Менеджері пакетів".';

$_lang['setting_auto_menuindex'] = 'Нумерація меню за замовчуванням';
$_lang['setting_auto_menuindex_desc'] = 'Виберіть «Так», щоб автоматично нумерувати меню за промовчанням.';

$_lang['setting_auto_check_pkg_updates'] = 'Автоматично перевіряти наявність оновлень пакетів';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Якщо вибрано "Так", MODX автоматично перевірятиме наявність оновлень для пакетів у Менеджері пакетів. Це може сповільнити завантаження таблиці, яка відображає пакети.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Час життя кешу результатів автоматичної перевірки наявності оновлень пакетів';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Тривалість часу (у хвилинах), на який «Менеджер пакетів» кешуватиме результати перевірки наявності оновлень пакетів.';

$_lang['setting_allow_multiple_emails'] = 'Дозволити користувачам мати однакові e-mail';
$_lang['setting_allow_multiple_emails_desc'] = 'Якщо увімкнено, різні користувачі можуть використовувати одну й ту ж адресу електронної пошти.';

$_lang['setting_automatic_alias'] = 'Автоматично генерувати псевдонім';
$_lang['setting_automatic_alias_desc'] = 'Виберіть «Так» для автоматичної генерації псевдонімів на базі заголовка ресурсу під час його збереження.';

$_lang['setting_automatic_template_assignment'] = 'Автоматичне присвоєння шаблону';
$_lang['setting_automatic_template_assignment_desc'] = 'Виберіть, як шаблони будуть призначатися нових ресурсів при створенні. Можливі варіанти: system (шаблон за замовчуванням з системних налаштувань), parent (успадковує батьківський шаблон) або sibling (успадковує найбільш використовуваний шаблон sibling)';

$_lang['setting_base_help_url'] = 'URL допомоги';
$_lang['setting_base_help_url_desc'] = 'URL допомоги у верхньому правому куті сторінок системи керування.';

$_lang['setting_blocked_minutes'] = 'Тривалість блокування';
$_lang['setting_blocked_minutes_desc'] = 'Тут Ви можете вказати час (у хвилинах), на який буде заблоковано користувача в разі перевищення кількості невдалих спроб входу до системи. Будь ласка, вводьте лише цифри (без ком, пробілів тощо)';

$_lang['setting_cache_alias_map'] = 'Увімкнути кешування карти псевдонімів контексту';
$_lang['setting_cache_alias_map_desc'] = 'Якщо увімкнено, усі URI ресурсів кешуватимуться до контексту. Вмикайте для невеличких сайтів і вимикайте для великих сайтів для збільшення продуктивності.';

$_lang['setting_use_context_resource_table'] = 'Use the context resource table';
$_lang['setting_use_context_resource_table_desc'] = 'When enabled, context refreshes use the context_resource table. This enables you to programmatically have one resource in multiple contexts. If you do not use those multiple resource contexts via the API, you can set this to false. On large sites you will get a potential performance boost in the manager then.';

$_lang['setting_cache_context_settings'] = 'Включити кешування налаштувань контекстів';
$_lang['setting_cache_context_settings_desc'] = 'Якщо вибрано "Так", параметри контекстів кешуватимуться для прискорення завантаження сторінок.';

$_lang['setting_cache_db'] = 'Увімкнути кешування бази даних';
$_lang['setting_cache_db_desc'] = 'Якщо вибрано "Так", об\'єкти та набори результатів вибірки за SQL-запитами кешуються, значно знижуючи навантаження на базу.';

$_lang['setting_cache_db_expires'] = 'Час життя кешу бази даних';
$_lang['setting_cache_db_expires_desc'] = 'Значення (у секундах) встановлює час життя кешу для результатів запиту до бази даних.';

$_lang['setting_cache_db_session'] = 'Включити кешування сесій, що обробляються базою даних';
$_lang['setting_cache_db_session_desc'] = 'Якщо вибрано «Так», і налаштування «cache_db» увімкнено, сесії, що зберігаються в базі даних, також кешуватимуться.';

$_lang['setting_cache_db_session_lifetime'] = 'Час життя кешу сесій бази даних';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Значення (у секундах) встановлює час життя кешу сесій бази даних.';

$_lang['setting_cache_default'] = 'Кешований за замовчуванням';
$_lang['setting_cache_default_desc'] = 'Виберіть «Так», щоб зробити все нові ресурси кешованими за замовчуванням.';
$_lang['setting_cache_default_err'] = 'Кешувати ресурси за промовчанням?';

$_lang['setting_cache_expires'] = 'Час життя кешу';
$_lang['setting_cache_expires_desc'] = 'Значення (у секундах) встановлює час кешу.';

$_lang['setting_cache_resource_clear_partial'] = 'Часткове очищення кешу для зазначених контекстів';
$_lang['setting_cache_resource_clear_partial_desc'] = 'Коли увімкнено, MODX оновить кеш ресурсів лише для зазначених контекстів.';

$_lang['setting_cache_format'] = 'Використовуваний формат кешу';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = серіалізація. Виберіть один із форматів.';

$_lang['setting_cache_handler'] = 'Клас-обробник системи кешування';
$_lang['setting_cache_handler_desc'] = 'Назва класу-обробника, що використовується для кешування.';

$_lang['setting_cache_lang_js'] = 'Кешувати JavaScript-файли із записами словника';
$_lang['setting_cache_lang_js_desc'] = 'Якщо вибрано «Так», будуть додані заголовки, що кеширують, до JavaScript-файлів із записами словників для системи управління.';

$_lang['setting_cache_lexicon_topics'] = 'Кешувати теми словників';
$_lang['setting_cache_lexicon_topics_desc'] = 'Якщо вибрано "Так", всі теми словників будуть кешуватися для збільшення швидкості завантаження сторінок системи керування. MODX рекомендує залишити цей параметр увімкненим ("Так").';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Кешувати теми словників, які не входять до ядра';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Якщо вибрано "Ні", теми словників, які не входять до ядра, не будуть кешуватися. Це може бути зручним при розробці компонентів.';

$_lang['setting_cache_resource'] = 'Включити часткове кешування ресурсів';
$_lang['setting_cache_resource_desc'] = 'Якщо вибрано "Так", часткове кешування визначається самими ресурсами. Вимкнення опції відключить його на всьому сайті.';

$_lang['setting_cache_resource_expires'] = 'Час життя часткового кешу ресурсів';
$_lang['setting_cache_resource_expires_desc'] = 'Значення (у секундах) встановлює час часткового кешу ресурсів.';

$_lang['setting_cache_scripts'] = 'Увімкнути кешування скриптів';
$_lang['setting_cache_scripts_desc'] = 'Якщо вибрано «Так», MODX кешуватиме всі скрипти (сніппети та плагіни) у файли для збільшення швидкості завантаження. MODX рекомендує залишити це налаштування увімкненим («Так»).';

$_lang['setting_cache_system_settings'] = 'Увімкнути кешування налаштувань системи';
$_lang['setting_cache_system_settings_desc'] = 'Якщо вибрано "Так", налаштування системи будуть кешуватися для прискорення завантаження сторінок. MODX рекомендує залишити це налаштування увімкненим ("Так").';

$_lang['setting_clear_cache_refresh_trees'] = 'Оновлювати деревоподібні меню під час очищення кешу сайту';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Якщо вибрано "Так", після оновлення кешу сайту оновлюватимуться деревоподібні меню.';

$_lang['setting_compress_css'] = 'Використовувати стислий CSS';
$_lang['setting_compress_css_desc'] = 'Якщо вибрано "Так", MODX буде використовувати стислі версії файлів CSS-стилів у системі керування.';

$_lang['setting_compress_js'] = 'Використовувати стиснуті JavaScript бібліотеки';
$_lang['setting_compress_js_desc'] = 'Якщо вибрано "Так", MODX надаватиме стислу версію файлу зі скриптами системи керування.';

$_lang['setting_compress_js_groups'] = 'Використовувати угруповання під час стиснення JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Групувати JavaScript-файли системи керування MODX, використовуючи конфігурацію "groupsConfig" для компресора Google. Виберіть «Так», якщо ви використовуєте розширення suhosin або інші обмеження.';

$_lang['setting_concat_js'] = 'Використовувати об\'єднані JavaScript-бібліотеки';
$_lang['setting_concat_js_desc'] = 'Якщо це ввімкнено, MODX використовуватиме об\'єднану версію своїх загальних JavaScript бібліотек в інтерфейсі менеджера. Це значно зменшує час завантаження та виконання в менеджері. Вимкніть лише у випадку, якщо ви змінюєте основні елементи.';

$_lang['setting_confirm_navigation'] = 'Підтвердити навігацію з незбереженими змінами';
$_lang['setting_confirm_navigation_desc'] = 'Коли це увімкнено, користувачу буде запропоновано підтвердити свій намір, якщо є незбережені зміни.';

$_lang['setting_container_suffix'] = 'Суфікс контейнера';
$_lang['setting_container_suffix_desc'] = 'Суфікс, який додається до набору ресурсів як контейнерів під час використання FURL.';

$_lang['setting_context_tree_sort'] = 'Увімкнути сортування контекстів в дереві ресурсів';
$_lang['setting_context_tree_sort_desc'] = 'Якщо встановлено "Так", контексти будуть алфавітно відсортовані у лівому дереві ресурсів.';
$_lang['setting_context_tree_sortby'] = 'Поле сортування контекстів у дереві ресурсів';
$_lang['setting_context_tree_sortby_desc'] = 'Поле для сортування контекстів у дереві ресурсів, якщо сортування активовано.';
$_lang['setting_context_tree_sortdir'] = 'Напрямок сортування контекстів у дереві ресурсів';
$_lang['setting_context_tree_sortdir_desc'] = 'Напрямок сортування контекстів у дереві ресурсів, якщо сортування ввімкнено.';

$_lang['setting_cultureKey'] = 'Мова';
$_lang['setting_cultureKey_desc'] = 'Виберіть мову для контекстів, включаючи web, за винятком контексту менеджера.';

$_lang['setting_date_timezone'] = 'Часова зона за замовчуванням';
$_lang['setting_date_timezone_desc'] = 'Контролює налаштування часового поясу за замовчуванням для функцій дати PHP, якщо воно не пусте. Якщо значення пусте й параметр ini PHP date.timezone не встановлено у вашому середовищі, буде прийнято UTC.';

$_lang['setting_manager_datetime_empty_value'] = 'Datetime Empty Value';
$_lang['setting_manager_datetime_empty_value_desc'] = 'The text (if any) that will show in grids and forms when a datetime field’s value has not been set. (Default: “–” [a single en dash])';

$_lang['setting_manager_datetime_separator'] = 'Datetime Separator';
$_lang['setting_manager_datetime_separator_desc'] = 'When the date and time are shown as a combined element, these characters will be used to visually separate them. (Default: “, ” [comma and space])';

$_lang['setting_debug'] = 'Налагодження';
$_lang['setting_debug_desc'] = 'Вмикає/вимикає режим налагодження MODX та/або встановлює рівень відображення помилок для PHP. "" - використовувати поточний "error_reporting", "0" - відключити (error_reporting = 0), "1" - включити (error_reporting = -1), або будь-яке інше значення "error_reporting" (як число).';

$_lang['setting_default_content_type'] = 'Тип вмісту за замовчуванням';
$_lang['setting_default_content_type_desc'] = 'Виберіть тип вмісту, який використовується за умовчанням під час створення нового ресурсу. Ви можете вибрати інший тип під час редагування ресурсу.';

$_lang['setting_default_duplicate_publish_option'] = 'Параметри публікації за замовчуванням під час копіювання ресурсів';
$_lang['setting_default_duplicate_publish_option_desc'] = 'Виберіть параметри публікації за замовчуванням під час копіювання ресурсу. Може бути "unpublish" - всі копії будуть зняті з публікації, "publish" - всі копії будуть опубліковані, або "preserve" - ​​у копії буде збережено стан публікації ресурсу, що копіюється.';

$_lang['setting_default_media_source'] = 'Джерело файлів за замовчуванням';
$_lang['setting_default_media_source_desc'] = 'Джерело файлів, що завантажується за замовчуванням.';

$_lang['setting_default_media_source_type'] = 'Тип джерела медіа за замовчуванням';
$_lang['setting_default_media_source_type_desc'] = 'Тип джерела файлів, який використовується за умовчанням під час створення нового джерела файлів.';

$_lang['setting_photo_profile_source'] = 'User Profile Photo Source';
$_lang['setting_photo_profile_source_desc'] = 'Specifies the Media Source to use for storing and retrieving profile photos/avatars. If not specified, the default Media Source will be used.';

$_lang['setting_default_template'] = 'Шаблон за замовчуванням';
$_lang['setting_default_template_desc'] = 'Виберіть шаблон, який використовується за умовчанням під час створення нового ресурсу. Ви можете змінити шаблон під час редагування ресурсу.';

$_lang['setting_default_per_page'] = 'За замовчуванням на сторінці';
$_lang['setting_default_per_page_desc'] = 'Типова кількість результатів, що показуються в сітках у всьому менеджері.';

$_lang['setting_emailsender'] = 'Електронна пошта відправника';
$_lang['setting_emailsender_desc'] = 'Тут ви можете вказати адресу електронної пошти, яка використовується при надсиланні користувачам їх імені та паролів.';
$_lang['setting_emailsender_err'] = 'Будь ласка, вкажіть електронну пошту відправника.';

$_lang['setting_enable_dragdrop'] = 'Увімкнути перетягування у деревоподібних меню';
$_lang['setting_enable_dragdrop_desc'] = 'Якщо вказано «Ні», перетягування буде недоступним для дерев ресурсів та елементів.';

$_lang['setting_enable_template_picker_in_tree'] = 'Enable the Template Picker in Resource Trees';
$_lang['setting_enable_template_picker_in_tree_desc'] = 'Enable this to use the template picker modal window when creating a new resource in the tree.';

$_lang['setting_error_page'] = 'ID cторінки помилки';
$_lang['setting_error_page_desc'] = 'Введіть ID ресурсу, який потрібно використовувати як сторінку помилки 404 «Документ не знайдено». <strong>ВАЖЛИВО: переконайтеся, що цей ID належить існуючому ресурсу, і що цей ресурс опубліковано!</strong>';
$_lang['setting_error_page_err'] = 'Будь ласка, вкажіть ID ресурсу для помилки 404 "Документ не знайдено".';

$_lang['setting_ext_debug'] = 'Режим налагодження ExtJS';
$_lang['setting_ext_debug_desc'] = 'Вмикати або не вмикати завантаження ext-all-debug.js для налагодження коду на ExtJS.';

$_lang['setting_extension_packages'] = 'Пакети розширень';
$_lang['setting_extension_packages_desc'] = 'JSON-масив з пакетами розширень, який необхідно завантажити під час створення екземпляра класу MODX. У форматі: [{"packagename":{path":"path/to/package"}},{"anotherpkg":{"path":"path/to/otherpackage"}}]';

$_lang['setting_enable_gravatar'] = 'Увімкнути Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'Якщо цей параметр увімкнено, як іконка профілю буде використовуватися фото з Gravatar (якщо користувач не завантажував свої фото у профіль).';

$_lang['setting_failed_login_attempts'] = 'Ліміт спроб авторизації';
$_lang['setting_failed_login_attempts_desc'] = 'Кількість невдалих спроб входу до системи, при перевищенні якої користувача буде заблоковано.';

$_lang['setting_feed_modx_news'] = 'Стрічка новин MODX';
$_lang['setting_feed_modx_news_desc'] = 'Вкажіть URL RSS-каналу для віджету «Новини MODX».';

$_lang['setting_feed_modx_news_enabled'] = 'Увімкнення стрічки новин MODX';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Якщо вибрано "Ні", MODX буде приховувати стрічку новин на початковому екрані системи керування.';

$_lang['setting_feed_modx_security'] = 'URL каналу «Сповіщення безпеки MODX»';
$_lang['setting_feed_modx_security_desc'] = 'Вкажіть URL-адресу RSS-каналу для віджету «Повідомлення безпеки MODX».';

$_lang['setting_feed_modx_security_enabled'] = 'Увімкнення стрічки новин безпеки MODX';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Якщо вибрано "Ні", MODX буде приховувати стрічку "Сповіщення безпеки MODX" на початковому екрані системи керування.';

$_lang['setting_form_customization_use_all_groups'] = 'Враховувати членство у всіх групах користувачів при налаштуванні форм';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Якщо вибрано "Так", для налаштування форм будуть використовуватися *всі* набори правил для *всіх* груп, до яких входить користувач. В іншому випадку, будуть використовуватися набори правил лише для первинної групи. Важливо: при включенні цієї настройки можливі помилки через конфлікти наборів правил налаштування форм.';

$_lang['setting_forward_merge_excludes'] = 'Виключені поля для методу sendForward';
$_lang['setting_forward_merge_excludes_desc'] = 'З використанням символічного посилання її непусті поля перевизначають значення відповідних полів цільового ресурсу; використовуйте цей розділений комами список полів для того, щоб вимкнути перевизначення полів ресурсу полями символічного посилання.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Тільки малі символи в псевдонімах';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Дозволити використовувати лише малі символи в псевдонімах ресурсів.';

$_lang['setting_friendly_alias_max_length'] = 'Максимальна довжина псевдоніму';
$_lang['setting_friendly_alias_max_length_desc'] = 'Якщо вказано більше нуля, задане значення використовуватиметься як максимальна кількість символів, що допускається у псевдонімі ресурсів. Нуль означає відсутність обмеження.';

$_lang['setting_friendly_alias_realtime'] = 'Створювати ЧПУ-псевдонім (так звані «дружні URL») «на льоту»';
$_lang['setting_friendly_alias_realtime_desc'] = 'Визначає, чи псевдонім ресурсу створюватися «на льоту» під час введення заголовка чи це має бути коли ресурс збережено (налаштування «automatic_alias» має бути включена, щоб це працювало).';

$_lang['setting_friendly_alias_restrict_chars'] = 'Метод фільтрації символів у псевдонімах';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'Метод фільтрації символів у псевдонімах ресурсу. "pattern" - для фільтрації буде використовуватися регулярне вираз, "legal" - псевдонім може складатися з будь-яких допустимих в URL символів, "alpha" - псевдонім може складатися тільки з літер, і "alphanumeric" - псевдонім може складатися тільки з літер та цифр.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Шаблон для фільтрації символів у псевдонімах';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Регулярний вираз обмеження символів, що використовуються в псевдонімах ресурсів.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Вирізати теги елементів із псевдоніму';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Визначає, чи слід вирізати теги елементів із псевдонімів ресурсів.';

$_lang['setting_friendly_alias_translit'] = 'Транслітерація псевдонімів';
$_lang['setting_friendly_alias_translit_desc'] = 'Метод транслітерації, що використовується для псевдонімів ресурсів. Пусто чи «none» - не використовувати транслітерацію. Інші можливі значення: «iconv» (якщо доступне PHP-розширення «iconv») або назва таблиці транслітерації, яка використовується класом класу транслітерації. ';

$_lang['setting_friendly_alias_translit_class'] = 'Клас, керуючий транслітерацією псевдонімів';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Клас, що робить транслітерацію при генерації та фільтрації псевдоніма ресурсу.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Шлях до класу, який здійснює транслітерацію псевдонімів';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'Розташування моделі пакета, що відповідає за транслітерацію псевдонімів.';

$_lang['setting_friendly_alias_trim_chars'] = 'Символи, що вирізуються із псевдоніму';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Символи, які треба вирізати із закінчення псевдоніма.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Розділювач слів у псевдонімах';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Символ, який буде замінювати пробіли між словами.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Розділювачі слів у псевдонімах';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Символи, що є роздільниками слів при обробці псевдонімів. Ці символи будуть перетворені на бажаний символ-розділювач, вказаний у налаштуванні «friendly_alias_word_delimiter».';

$_lang['setting_friendly_urls'] = 'Дружні URL';
$_lang['setting_friendly_urls_desc'] = 'Ця настройка дозволяє використовувати в MODX дружні URL-адреси. Зверніть увагу, що це працює тільки на серверах Apache, і вам потрібно модифікувати файл .htaccess, щоб цей механізм запрацював. Для додаткової інформації дивіться приклад файлу .htaccess, що постачається з MODX.';
$_lang['setting_friendly_urls_err'] = 'Будь ласка, вкажіть, чи ви хочете використовувати дружні URL.';

$_lang['setting_friendly_urls_strict'] = 'Використовуйте суворі дружні URL-адреси';
$_lang['setting_friendly_urls_strict_desc'] = 'Якщо вибрано "Так", неканонічні запити, що відповідають ресурсу, будуть перенаправлені з кодом 301 на канонічний URI для цього ресурсу. ПОПЕРЕДЖЕННЯ. Не включайте, якщо ви використовуєте правила перезапису, які не збігаються, принаймні, з початком канонічного URI. Наприклад, канонічний URI "foo/" з перезаписами користувача для "foo/bar.html" буде працювати, але спроби переписати "bar/foo.html" як "foo/" призведуть до перенаправлення до "foo/", якщо ця опція включена .';

$_lang['setting_global_duplicate_uri_check'] = 'Перевіряти на дублювання URI у всіх контекстах';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Виберіть «Так», щоб перевіряти на дублювання URI у всіх контекстах. Якщо вибрано «Ні», перевірятиметься лише контекст, у якому ресурс зберігається.';

$_lang['setting_hidemenu_default'] = 'Приховати з меню за замовчуванням';
$_lang['setting_hidemenu_default_desc'] = 'Виберіть «Так», щоб вибрати параметр «Приховати з меню» за замовчуванням при створенні нових ресурсів.';

$_lang['setting_inline_help'] = 'Показувати текст підказки поряд із полем';
$_lang['setting_inline_help_desc'] = 'Якщо вибрано "Так", поряд з полем буде виводитися текст підказки. Якщо вибрано "Ні", підказка буде "випливаючою".';

$_lang['setting_link_tag_scheme'] = 'Схема URL';
$_lang['setting_link_tag_scheme_desc'] = 'URL generation scheme for tag [[~id]]. Available options <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\modX::makeUrl()" target="_blank">here</a>.';

$_lang['setting_locale'] = 'Локаль';
$_lang['setting_locale_desc'] = 'Встановлює локаль для системи. Залиште порожнім, щоб використовувати локаль за замовчуванням. Зверніться до <a href="http://php.net/setlocale" target="_blank">документації з налаштування локалей у PHP</a> за більш детальною інформацією.';

$_lang['setting_lock_ttl'] = 'Час життя блокування';
$_lang['setting_lock_ttl_desc'] = 'Кількість секунд, на яку залишатиметься блокування ресурсу, якщо користувач неактивний.';

$_lang['setting_log_level'] = 'Рівень журналювання';
$_lang['setting_log_level_desc'] = 'Рівень запису повідомлень до журналу помилок. Чим менший рівень, тим менше повідомлень буде записано. Можливі значення: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO) та 4 (DEBUG).';

$_lang['setting_log_target'] = 'Метод виведення журналу помилок';
$_lang['setting_log_target_desc'] = 'Метод виведення повідомлень журналу. Можливі значення: "FILE", "HTML", або "ECHO". За промовчанням використовується «FILE».';

$_lang['setting_log_deprecated'] = 'Застарілі функції у журналі помилок';
$_lang['setting_log_deprecated_desc'] = 'Увімкнути сповіщення про використання застарілих функцій у журналі помилок.';

$_lang['setting_mail_charset'] = 'Кодування';
$_lang['setting_mail_charset_desc'] = 'Кодування (за замовчуванням) для електронних листів, таких як «iso-8859-1» або «utf-8»';

$_lang['setting_mail_encoding'] = 'Формат кодування електронних листів';
$_lang['setting_mail_encoding_desc'] = 'Встановіть формат кодування. Це може бути "8bit", "7bit", "binary", "base64", і "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Використовувати SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Якщо увімкнено, MODX намагатиметься використовувати SMTP під час надсилання електронної пошти.';

$_lang['setting_mail_smtp_auth'] = 'SMTP аутентифікація';
$_lang['setting_mail_smtp_auth_desc'] = 'Встановлює SMTP автентифікацію. Використовуватимуться налаштування «mail_smtp_user» та «mail_smtp_pass».';

$_lang['setting_mail_smtp_helo'] = 'SMTP Helo повідомлення';
$_lang['setting_mail_smtp_helo_desc'] = 'Визначте повідомлення SMTP HELO (за промовчанням ім\'я хоста).';

$_lang['setting_mail_smtp_hosts'] = 'SMTP хости';
$_lang['setting_mail_smtp_hosts_desc'] = 'Встановлює SMTP хости. Всі хости повинні бути розділені крапкою з комою. Ви також можете вказати інший порт для кожного хоста, використовуючи цей формат: [ім\'я хоста:порт] (наприклад, "smtp1.example.com:25;smtp2.example.com"). Хости будуть випробувані в порядку.';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP утримання з\'єднання';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Запобігає закриттю з\'єднання SMTP після кожного надсилання повідомлення. Не рекомендується.';

$_lang['setting_mail_smtp_pass'] = 'SMTP пароль';
$_lang['setting_mail_smtp_pass_desc'] = 'Пароль, який використовується під час SMTP авторизації.';

$_lang['setting_mail_smtp_port'] = 'SMTP номер порту';
$_lang['setting_mail_smtp_port_desc'] = 'Вкажіть порт SMTP сервера за замовчуванням.';

$_lang['setting_mail_smtp_secure'] = 'SMTP Secure';
$_lang['setting_mail_smtp_secure_desc'] = 'Sets SMTP secure encryption type. Options are "", "ssl" or "tls"';

$_lang['setting_mail_smtp_autotls'] = 'SMTP Auto TLS';
$_lang['setting_mail_smtp_autotls_desc'] = 'Whether to enable TLS encryption automatically if a server supports it, even if "SMTP Secure" is not set to "tls"';

$_lang['setting_mail_smtp_single_to'] = 'SMTP посилати по одному';
$_lang['setting_mail_smtp_single_to_desc'] = 'Надайте можливість надсилати повідомлення адресатам з поля «ВІД» по одному замість разового відправлення на всі адреси.';

$_lang['setting_mail_smtp_timeout'] = 'SMTP час очікування';
$_lang['setting_mail_smtp_timeout_desc'] = 'Визначте час очікування (таймаут) сервера SMTP. Не працює на win32 серверах.';

$_lang['setting_mail_smtp_user'] = 'SMTP користувач';
$_lang['setting_mail_smtp_user_desc'] = 'Користувач, який використовується під час SMTP авторизації.';

$_lang['setting_mail_dkim_selector'] = 'Селектор DKIM';
$_lang['setting_mail_dkim_selector_desc'] = 'Доменний селектор DKIM, де зберігаються публічний ключ.';

$_lang['setting_mail_dkim_identity'] = 'Ідентифікація DKIM';
$_lang['setting_mail_dkim_identity_desc'] = 'Ідентичність DKIM підписана - як зазвичай ваша From адреса відправника ';

$_lang['setting_mail_dkim_domain'] = 'Домен DKIM';
$_lang['setting_mail_dkim_domain_desc'] = 'Доменне ім\'я DKIM.';

$_lang['setting_mail_dkim_privatekeyfile'] = 'Файл приватного ключа DKIM';
$_lang['setting_mail_dkim_privatekeyfile_desc'] = 'Шлях до файлу закритого ключа DKIM. Ви можете використовувати рядок приватного ключа DKIM замість цього.';

$_lang['setting_mail_dkim_privatekeystring'] = 'Рядок приватного ключа DKIM';
$_lang['setting_mail_dkim_privatekeystring_desc'] = 'Займає перевагу над файлом приватного ключа DKIM.';

$_lang['setting_mail_dkim_passphrase'] = 'Парольна фраза DKIM';
$_lang['setting_mail_dkim_passphrase_desc'] = 'Використовується лише в тому випадку, якщо ваш ключ зашифрований.';

$_lang['setting_main_nav_parent'] = 'Батьківський елемент для основного меню';
$_lang['setting_main_nav_parent_desc'] = 'Контейнер містить всі записи основного меню.';

$_lang['setting_manager_direction'] = 'Напрямок тексту у Менеджері';
$_lang['setting_manager_direction_desc'] = 'Виберіть напрямок тексту у панелі керування (зліва-направо або зправа-наліво).';

$_lang['setting_manager_date_format'] = 'Формат дати у системі управління';
$_lang['setting_manager_date_format_desc'] = 'Рядок у форматі PHP date(), що визначає формат дати в системі керування.';

$_lang['setting_manager_favicon_url'] = 'URL піктограми Менеджера';
$_lang['setting_manager_favicon_url_desc'] = 'Якщо встановлено, буде використовуватися як URL фавіконки системи керування MODX. Необхідно вказати або URL щодо директорії /manager/ або абсолютний URL.';

$_lang['setting_manager_login_url_alternate'] = 'Альтернативна URL-адреса сторінки входу в систему управління';
$_lang['setting_manager_login_url_alternate_desc'] = 'Альтернативна URL-адреса, на яку буде спрямований неавторизований користувач за необхідності авторизації в системі управління. Форма входу має авторизувати користувача у контексті "mgr".';

$_lang['setting_manager_tooltip_enable'] = 'Увімкнути підказки Менеджера';
$_lang['setting_manager_tooltip_delay'] = 'Час затримки підказок Менеджера';

$_lang['setting_login_background_image'] = 'Фонове зображення входу в систему';
$_lang['setting_login_background_image_desc'] = 'Фонове зображення для використання в формі входу в систему. Воно автоматично розтягнеться, щоб заповнити екран.';

$_lang['setting_login_logo'] = 'Лого для входу';
$_lang['setting_login_logo_desc'] = 'Логотип для показу в лівому верхньому кутку адміністративній частині (адмінкє) логін. Якщо залишити порожнім, на ньому з\'явиться логотип MODX.';

$_lang['setting_login_help_button'] = 'Показати кнопку довідки';
$_lang['setting_login_help_button_desc'] = 'Коли включено, ви знайдете кнопку допомоги на екрані входу в систему. Можна налаштувати інформацію, що відображається наступними записами лексики в core/login: login_help_button_text, login_help_title, and login_help_text.';

$_lang['setting_manager_login_start'] = 'Сторінка входу в систему управління';
$_lang['setting_manager_login_start_desc'] = 'Введіть ID ресурсу, на який буде перенаправлено користувач після входу в систему управління. <strong>ВАЖЛИВО: переконайтеся, що введений вами ID належить існуючому ресурсу, що він опублікований та доступний для користувача!</strong>';

$_lang['setting_manager_theme'] = 'Тема Менеджера';
$_lang['setting_manager_theme_desc'] = 'Виберіть тему для системи управління.';

$_lang['setting_manager_logo'] = 'Лого адмінки';
$_lang['setting_manager_logo_desc'] = 'Логотип для відображення в заголовку Content адміністративній частині (адмінкє).';

$_lang['setting_manager_time_format'] = 'Формат часу у системі управління';
$_lang['setting_manager_time_format_desc'] = 'Рядок у форматі PHP date(), що визначає формат відображення часу в системі керування.';

$_lang['setting_manager_use_tabs'] = 'Використовувати вкладки у шаблоні системи управління';
$_lang['setting_manager_use_tabs_desc'] = 'Якщо вибрано "Так", то в системі керування будуть використовуватися вкладки. Інакше використовуватимуться окремі панелі.';

$_lang['setting_manager_week_start'] = 'Початок тижня';
$_lang['setting_manager_week_start_desc'] = 'Вкажіть день, з якого починається тиждень. Використовуйте 0 (або залиште поле порожнім) для неділі, 1 для понеділка тощо.';

$_lang['setting_mgr_tree_icon_context'] = 'Значок контекстного дерева';
$_lang['setting_mgr_tree_icon_context_desc'] = 'CSS-клас, який використовується для відображення контексту іконки в дереві. Ви можете використовувати це налаштування для встановлення унікальної іконки кожному контексту.';

$_lang['setting_mgr_source_icon'] = 'Значок джерела файлів';
$_lang['setting_mgr_source_icon_desc'] = 'CSS-клас іконки показ медіа-файли в дереві. За замовчуванням використовується клас <b>icon-folder-open-o</b>, який зображує відкриту папку';

$_lang['setting_modRequest.class'] = 'Клас-обробник запитів';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Приховати файли у диспетчері файлів';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'Якщо вибрано "Так", файли, які знаходяться в папці, не відображатимуться в дереві диспетчера файлів.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Вимкнути швидкий перегляд зображень';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'Якщо вибрано «Так», при наведенні курсору на файл зображення спливаюче вікно попереднього перегляду не відображатиметься.';

$_lang['setting_modx_browser_default_sort'] = 'Сортування за замовчуванням у диспетчері файлів';
$_lang['setting_modx_browser_default_sort_desc'] = 'Метод сортування за промовчанням для диспетчера файлів. Можливі значення: "name" (назва), "size" (розмір), "lastmod" (змінений).';

$_lang['setting_modx_browser_default_viewmode'] = 'Режим перегляду за промовчанням у диспетчері файлів';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'Режим перегляду за умовчанням під час використання контекстного меню файлового менеджера в системі керування. Доступні значення: "grid" (таблиця), "list" (список).';

$_lang['setting_modx_charset'] = 'Кодування символів';
$_lang['setting_modx_charset_desc'] = 'Будь ласка, виберіть кодування, яке Ви хочете використовувати. Зверніть увагу на те, що MODX був протестований з деякими зі вказаних кодувань, але не з усіма. Для більшості мов рекомендується використовувати кодування за замовчуванням UTF-8.';

$_lang['setting_new_file_permissions'] = 'Права на новий файл';
$_lang['setting_new_file_permissions_desc'] = 'При завантаженні нового файлу через диспетчер файлів буде спроба встановити права доступу до цього файлу відповідно до цього налаштування. Може не працювати на деяких серверах, наприклад, IIS. У цьому випадку Вам слід встановити вручну права.';

$_lang['setting_new_folder_permissions'] = 'Права на нову папку';
$_lang['setting_new_folder_permissions_desc'] = 'При створенні нової папки через диспетчер файлів буде зроблено спробу встановити права доступу до цієї папки відповідно до цієї настройки. Може не працювати на деяких серверах, наприклад, IIS. У цьому випадку вам слід встановити вручну права.';

$_lang['setting_package_installer_at_top'] = 'Закріпити інсталятор пакетів зверху';
$_lang['setting_package_installer_at_top_desc'] = 'Якщо вибрано, Інсталятор буде прикріплений до верхньої частини меню "Пакетів". В іншому випадку він буде розміщений відповідно до позиції в меню.';

$_lang['setting_parser_recurse_uncacheable'] = 'Відкладений парсинг, що не кешується.';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'Якщо вимкнено, елементи, що не кешуються, можуть показувати кешований вміст усередині кешованих елементів. Відключайте ТІЛЬКИ якщо у вас є проблеми зі складним вкладеним парсингом, який перестав працювати, як очікувалося.';

$_lang['setting_password_generated_length'] = 'Довжина згенерованого пароля';
$_lang['setting_password_generated_length_desc'] = 'Довжина згенерованого пароля для користувача.';

$_lang['setting_password_min_length'] = 'Мінімальна довжина пароля';
$_lang['setting_password_min_length_desc'] = 'Мінімальна довжина пароля для користувача.';

$_lang['setting_preserve_menuindex'] = 'Зберігати індекс меню під час дублювання ресурсів';
$_lang['setting_preserve_menuindex_desc'] = 'При дублюванні ресурсів порядок розташування меню також буде збережено.';

$_lang['setting_principal_targets'] = 'Цільові класи для завантаження списків контролю доступу';
$_lang['setting_principal_targets_desc'] = 'Налаштуйте цільові класи, для яких потрібно завантажити списки контролю доступу користувачів.';

$_lang['setting_proxy_auth_type'] = 'Тип проксі автентифікації';
$_lang['setting_proxy_auth_type_desc'] = 'Підтримує або BASIC або NTLM.';

$_lang['setting_proxy_host'] = 'Проксі сервер';
$_lang['setting_proxy_host_desc'] = 'Якщо сервер використовує проксі, вкажіть ім\'я проксі-хоста для того, щоб зробити доступними деякі функції MODX, такі як «Менеджер пакетів».';

$_lang['setting_proxy_password'] = 'Пароль проксі-сервера';
$_lang['setting_proxy_password_desc'] = 'Пароль для авторизації на проксі-сервері.';

$_lang['setting_proxy_port'] = 'Порт проксі';
$_lang['setting_proxy_port_desc'] = 'Порт проксі-сервера.';

$_lang['setting_proxy_username'] = 'Ім\'я користувача проксі-сервера';
$_lang['setting_proxy_username_desc'] = 'Ім\'я користувача для авторизації на проксі-сервері.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Дозволити джерела вище кореневої директорії';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Дозволяє або забороняє використання файлів, розташованих поза кореневою директорією, як джерела. Може бути використаний для систем з безліччю контекстів, розташованих на різних віртуальних хостах.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb Максимальний час життя кешу';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Видаляти кеш зображень, які не вимагали більше вказаної кількості днів.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb Максимальний розмір кешу';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Якщо розмір кешу перевищить вказане значення (у мегабайтах), то буде видалено кеш картинок, які вимагалися найбільш давно.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Максимальна кількість кешованих файлів';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Якщо кеш перевищить вказану кількість файлів, буде видалено кеш картинок, які запитувалися найдавніше.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb Кешувати файли-джерела';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Кешувати чи ні файли джерел під час завантаження. Рекомендуємо вимкнути.';

$_lang['setting_phpthumb_document_root'] = 'phpThumb Коренева директорія';
$_lang['setting_phpthumb_document_root_desc'] = 'Встановіть це налаштування, якщо є проблеми, пов\'язані зі змінною «DOCUMENT_ROOT» або виникають помилки з «OutputThumbnail» або «!is_resource». Встановіть необхідний абсолютний шлях до кореневої директорії сервера. При порожньому значенні MODX буде використовувати змінну "DOCUMENT_ROOT" сервера.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb Колір тла повідомлення про помилку';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Шістнадцяткове число без символу # визначає фон повідомлення про помилку.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb Розмір шрифту повідомлення про помилку';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Розмір шрифту, заданий em.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb Колір тексту помилки';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Шістнадцяткове число без символу # визначає колір тексту повідомлення про помилку.';

$_lang['setting_phpthumb_far'] = 'phpThumb Примусове співвідношення сторін';
$_lang['setting_phpthumb_far_desc'] = 'Значення за промовчанням для параметра "far" коли він використовується в MODX. За промовчанням значення "C", яке змушує зберегти пропорції щодо центру.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb Шлях до ImageMagick';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Необов\'язково. Встановлює альтернативний шлях до ImageMagick для генерації ескізів з phpThumb, якщо не задано в PHP за замовчуванням.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Вимкнути хотлінкінг';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Видалені сервери дозволені в параметрі "src" поки відключений hotlinking в phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Видаляти зображення при увімкненому Hotlinking';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Повідомляє, коли не можна видалити зображення, згенероване на віддаленому сервері.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Повідомлення про заборону Hotlinking';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Повідомлення, яке відображається замість картинок, коли hotlinking заборонено.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Допустимі домени для Hotlinking';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Розділений комами список імен хостів, які припустимі посилання в src.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Забороняти зовнішні посилання';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Забороняє іншим використовувати phpThumb для генерації зображень на своїх сайтах.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Видаляти зображення за зовнішніми посиланнями';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Повідомляє, коли не можна видалити зображення, пов\'язане з віддаленим сервером.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Вимагати вказівку referrer для зовнішніх підключень';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Якщо вибрано "Так", будь-які зовнішні запити без дозволеного заголовка "referrer" будуть відхилені.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Повідомлення про недоступність зовнішніх посилань';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Повідомлення, яке відображається замість картинок, коли відхилено зовнішнє підключення.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Допустимі домени для зовнішніх посилань';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Розділений комами список імен хостів, яким дозволено зовнішнє підключення.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Адреса водяного знаку для зовнішніх запитів';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Необов\'язково. Допустимий шлях до файлу, який буде використовуватися як водяний знак, коли ваші зображення відображаються поза сайтом для phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Кадрування';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Значення "zc" за промовчанням для використання в MODX. За умовчанням 0, що запобігає обрізанню зі збільшенням (zoom cropping).';

$_lang['setting_publish_default'] = 'Публікувати за замовчуванням';
$_lang['setting_publish_default_desc'] = 'Виберіть «Так», якщо хочете, щоб усі нові ресурси відразу ставали опублікованими.';
$_lang['setting_publish_default_err'] = 'Будь ласка, вкажіть, чи ви хочете, щоб нові ресурси за замовчуванням публікувалися.';

$_lang['setting_quick_search_in_content'] = 'Allow search in content';
$_lang['setting_quick_search_in_content_desc'] = 'If \'Yes\', then the content of the element (resource, template, chunk, etc.) will also be available for quick search.';

$_lang['setting_quick_search_result_max'] = 'Number of items in search result';
$_lang['setting_quick_search_result_max_desc'] = 'Maximum number of elements for each type (resource, template, chunk, etc.) in the quick search result.';

$_lang['setting_request_controller'] = 'Назва файлу контролера запиту';
$_lang['setting_request_controller_desc'] = 'Назва файлу основного контролера запиту, з якого MODX завантажується. Більшість користувачів може залишити значення "index.php".';

$_lang['setting_request_method_strict'] = 'Жорсткий метод запиту';
$_lang['setting_request_method_strict_desc'] = 'Якщо вибрано «Так», запити через параметр ID будуть ігноруватися при увімкнених дружніх URL-адресах. Якщо дружні URL-адреси відключені, запити з використанням псевдоніма будуть ігноруватися.';

$_lang['setting_request_param_alias'] = 'Назва параметра запиту для псевдоніма';
$_lang['setting_request_param_alias_desc'] = 'Назва GET-параметра, що передає псевдонім ресурсу при використанні дружніх URL.';

$_lang['setting_request_param_id'] = 'Параметр запиту ID';
$_lang['setting_request_param_id_desc'] = 'Назва GET-параметра, що передає ID ресурсу, коли дружні URL вимкнуто.';

$_lang['setting_resource_tree_node_name'] = 'Поле для назви вузла у дереві ресурсів';
$_lang['setting_resource_tree_node_name_desc'] = 'Вкажіть поле ресурсу, яке використовуватиметься як назва вузла в дереві ресурсів. За замовчуванням поле "pagetitle", будь-яке поле ресурсу може бути використане: "menutitle", "alias", "longtitle", і т.п.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Запасне поле для вузла у дереві ресурсів';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Вкажіть поле ресурсу для використання як запасну назву вузла в дереві ресурсів. Це значення використовуватиметься, якщо ресурс має порожнє значення для заданого поля ресурсу в дереві.';

$_lang['setting_resource_tree_node_tooltip'] = 'Полі підказки для ресурсу в дереві ресурсів';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Вкажіть поле ресурсу для використання як підказка в дереві ресурсів. Будь-яке поле ресурсу може бути використане: "menutitle", "alias", "longtitle", і т.п. Якщо не вказано, буде використано "longtitle" з "description" під ним.';

$_lang['setting_richtext_default'] = 'Використовувати візуальний редактор';
$_lang['setting_richtext_default_desc'] = 'Виберіть «Так», щоб використовувати нові текстові візуальні текстові редактори за промовчанням.';

$_lang['setting_search_default'] = '«Доступний для пошуку» за замовчуванням';
$_lang['setting_search_default_desc'] = 'Виберіть «Так», щоб зробити нові ресурси доступними для пошуку за промовчанням.';
$_lang['setting_search_default_err'] = 'Будь ласка, вкажіть, чи ви хочете, щоб ресурси були доступні для пошуку за замовчуванням.';

$_lang['setting_server_offset_time'] = 'Різниця у часі';
$_lang['setting_server_offset_time_desc'] = 'Вкажіть різницю в годиннику між вашим локальним часом і часом сервера.';

$_lang['setting_session_cookie_domain'] = 'Домен для сесійних куки';
$_lang['setting_session_cookie_domain_desc'] = 'Використовуйте це налаштування для вказівки доменного імені для сесійних куки. При порожньому значенні як доменне ім\'я буде використовуватися поточний домен.';

$_lang['setting_session_cookie_samesite'] = 'Атрибут Samesite для сесійних cookie';
$_lang['setting_session_cookie_samesite_desc'] = 'Виберіть Lax або Strict.';

$_lang['setting_session_cookie_lifetime'] = 'Тривалість зберігання кукі сесій';
$_lang['setting_session_cookie_lifetime_desc'] = 'Використовуйте це налаштування для вибору тривалості зберігання сесійних куки в секундах. Ця установка використовується для визначення тривалості зберігання клієнтських сесійних куки при виборі опції «запам\'ятати мене» під час автентифікації.';

$_lang['setting_session_cookie_path'] = 'Шлях для сесійних куки';
$_lang['setting_session_cookie_path_desc'] = 'Використовуйте це налаштування для завдання шляху для сесійних куки. Залишіть значення порожнім для використання «MODX_BASE_URL» як шлях.';

$_lang['setting_session_cookie_secure'] = 'Шифрування сесійних куки';
$_lang['setting_session_cookie_secure_desc'] = 'Увімкніть це налаштування для використання шифрування сесійних куки.';

$_lang['setting_session_cookie_httponly'] = 'Сесійні куки в режимі HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Увімкніть це налаштування для встановлення прапора HttpOnly для сесійних кук.';

$_lang['setting_session_gc_maxlifetime'] = 'Максимальний час життя сесії';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Allows customization of the session.gc_maxlifetime PHP ini setting when using \'MODX\\Revolution\\modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Назва класу-обробника сесій';
$_lang['setting_session_handler_class_desc'] = 'For database managed sessions, use \'MODX\\Revolution\\modSessionHandler\'.  Leave this blank to use standard PHP session management.';

$_lang['setting_session_name'] = 'Назва сесii';
$_lang['setting_session_name_desc'] = 'Використовуйте це налаштування для вказівки сесійного імені, що використовується в сесіях MODX. Залишіть значення порожнім для використання імені PHP-сесії за промовчанням.';

$_lang['setting_settings_version'] = 'Версія налаштувань';
$_lang['setting_settings_version_desc'] = 'Поточна встановлена версія MODX.';

$_lang['setting_settings_distro'] = 'Розповсюдження налаштувань';
$_lang['setting_settings_distro_desc'] = 'Поточна встановлена версія MODX.';

$_lang['setting_set_header'] = 'Встановити HTTP-заголовки';
$_lang['setting_set_header_desc'] = 'Якщо відмічено, MODX намагатиметься встановити HTTP заголовки для ресурсів.';

$_lang['setting_send_poweredby_header'] = 'Надсилати заголовок "X-Powered-By"';
$_lang['setting_send_poweredby_header_desc'] = 'Якщо вибрано "Так", MODX надсилатиме заголовок "X-Powered-By", щоб позначити цей сайт як створений на MODX. Це допомагає відстежити глобальне використання MODX за допомогою сторонніх трекерів, які перевіряють ваш сайт. Оскільки це полегшує визначення системи, на якій створено ваш сайт, це може дещо збільшити ризики з точки зору безпеки в тому випадку, якщо MODX буде знайдена вразливість.';

$_lang['setting_show_tv_categories_header'] = 'Показувати заголовок «Категорії» над вкладками з категоріями під час виведення TV';
$_lang['setting_show_tv_categories_header_desc'] = 'Якщо вказано "Так", над вкладками категорій TV буде відображено заголовок "Категорії".';

$_lang['setting_signupemail_message'] = 'Лист реєстрації';
$_lang['setting_signupemail_message_desc'] = 'Тут ви можете встановити повідомлення, що надсилається вашим користувачам після реєстрації облікового запису, з даними про їхнє ім\'я облікового запису та паролі. <br /><strong>ВАЖЛИВО:<strong> Наступні плейсхолдери замінюються MODX перед відправкою листа: <[ /sname]] - назва вашого сайту, <[ /saddr]] - адреса електронної пошти вашого сайту, <br />[[+surl]] - url вашого сайту, <br />[[+uid]] - ім\'я облікового запису користувача (логін) або ID, <[ /pwd ]] - пароль користувача, <[ /ufn]] - повне ім\'я користувача. <br /><br /><strong>Обов\'язково вкажіть теги [[+uid]] та [[+pwd]] у листі, інакше ваші користувачі не зможуть дізнатися своє ім\'я облікового запису та пароль!</strong>';
$_lang['setting_signupemail_message_default'] = 'Здрастуйте, [[+uid]] \n\nВаші дані реєстрації на сайті [[+sname]]:\n\nІм\'я користувача: [[+uid]]\nПароль: [[+pwd]]\n\nЯк тільки ви авторизуєтесь на сайті ([[+surl]]), ви зможете змінити свій пароль.\n\nЗ повагою,\nАдміністрація сайту';

$_lang['setting_site_name'] = 'Назва сайту';
$_lang['setting_site_name_desc'] = 'Введіть назву вашого сайту.';
$_lang['setting_site_name_err']  = 'Будь-ласка, введіть назву сайту.';

$_lang['setting_site_start'] = 'Головна сторінка сайту';
$_lang['setting_site_start_desc'] = 'Введіть ID ресурсу, який ви хочете використовувати як «Головну сторінку сайту». <strong>ВАЖЛИВО: переконайтеся, що цей ID належить існуючому ресурсу і що цей ресурс опубліковано!</strong>';
$_lang['setting_site_start_err'] = 'Будь ласка, вкажіть ID ресурсу, який буде "Головною сторінкою сайту".';

$_lang['setting_site_status'] = 'Статус сайту';
$_lang['setting_site_status_desc'] = 'Виберіть "Так" для публікації вашого сайту в мережі. Якщо ви оберете "Ні", ваші відвідувачі побачать "Повідомлення про недоступність сайту", і не зможуть переглядати вміст сайту.';
$_lang['setting_site_status_err'] = 'Будь ласка, виберіть "Так", якщо сайт працює або Ні, якщо сайт не працює.';

$_lang['setting_site_unavailable_message'] = 'Повідомлення про недоступність сайту';
$_lang['setting_site_unavailable_message_desc'] = 'Повідомлення, яке буде показано у випадку, якщо сайт недоступний або виникла помилка. <strong>ВАЖЛИВО: Це повідомлення відображається лише у випадку, якщо не вказано сторінку «Сайт недоступний».</strong>';

$_lang['setting_site_unavailable_page'] = 'Сторінка помилки 503 «Сайт недоступний»';
$_lang['setting_site_unavailable_page_desc'] = 'Введіть ID ресурсу, який ви хочете використовувати як сторінку помилки 503 «Сайт недоступний». <strong>ВАЖЛИВО: переконайтеся, що цей ID належить існуючому ресурсу та опубліковано!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Будь ласка, вкажіть ID ресурсу для сторінки помилки 503 "Сайт недоступний".';

$_lang['setting_static_elements_automate_templates'] = 'Автоматизувати статичні елементи для шаблонів?';
$_lang['setting_static_elements_automate_templates_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for templates.';

$_lang['setting_static_elements_automate_tvs'] = 'Automate static elements for TVs?';
$_lang['setting_static_elements_automate_tvs_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for TVs.';

$_lang['setting_static_elements_automate_chunks'] = 'Автоматизувати статичні елементи для чанків?';
$_lang['setting_static_elements_automate_chunks_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for chunks.';

$_lang['setting_static_elements_automate_snippets'] = 'Автоматизувати статичні елементи для сніпетів?';
$_lang['setting_static_elements_automate_snippets_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for snippets.';

$_lang['setting_static_elements_automate_plugins'] = 'Автоматизувати статичні елементи для плагінів?';
$_lang['setting_static_elements_automate_plugins_desc'] = 'This will automate the handling of static files, such as creating and deleting static files for plugins.';

$_lang['setting_static_elements_default_mediasource'] = 'Джерело файлів для статичних елементів за промовчанням';
$_lang['setting_static_elements_default_mediasource_desc'] = 'Вкажіть джерело файлів за промовчанням, де зберігатимуться статичні елементи.';

$_lang['setting_static_elements_default_category'] = 'Категорія для статичних елементів за замовчуванням';
$_lang['setting_static_elements_default_category_desc'] = 'Вкажіть стандартну категорію для нових статичних елементів.';

$_lang['setting_static_elements_basepath'] = 'Шлях до файлів статичних елементів';
$_lang['setting_static_elements_basepath_desc'] = 'Дорога до файлів, де зберігаються статичні елементи.';

$_lang['setting_resource_static_allow_absolute'] = 'Дозволити абсолютний шлях до статичних ресурсів';
$_lang['setting_resource_static_allow_absolute_desc'] = 'Це налаштування дозволяє користувачам вводити абсолютний шлях до будь-якого файлу, що читається на сервері, як вміст статичного ресурсу. Важливо: увімкнення цієї настройки може розглядатися як ризик безпеки! Настійно рекомендується тримати це налаштування вимкненим, якщо ви не довіряєте кожному користувачеві системи керування.';

$_lang['setting_resource_static_path'] = 'Шлях до статичних ресурсів';
$_lang['setting_resource_static_path_desc'] = 'При відключенні "resource_static_allow_absolute" статичні ресурси можуть знаходитись лише в межах шляху, вказаного тут. Важливо: налаштування може дозволити користувачам читати файли, які вони не повинні читати! Рекомендовано обмежити користувачів певним каталогом, таким як {core_path}static/ або {assets_path}.';

$_lang['setting_symlink_merge_fields'] = 'Поєднувати поля ресурсу з полями символічного посилання';
$_lang['setting_symlink_merge_fields_desc'] = 'Якщо встановлено так, непусті поля символічного посилання замінять поля цільового ресурсу при переадресації з використанням символічного посилання.';

$_lang['setting_syncsite_default'] = 'Очистити кеш за замовчуванням';
$_lang['setting_syncsite_default_desc'] = 'Если выбрано «Да», при сохранении ресурса кэш будет очищаться по умолчанию.';
$_lang['setting_syncsite_default_err'] = 'Будь ласка, вкажіть, чи ви хочете чи ні, щоб кеш очищався за умовчанням при збереженні ресурсу.';

$_lang['setting_topmenu_show_descriptions'] = 'Show Descriptions in Main Menu';
$_lang['setting_topmenu_show_descriptions_desc'] = 'If set to \'No\', MODX will hide the descriptions from main menu items in the manager.';

$_lang['setting_tree_default_sort'] = 'Поле сортування дерева ресурсів за промовчанням';
$_lang['setting_tree_default_sort_desc'] = 'Поле, яким сортується дерево ресурсів під час завантаження.';

$_lang['setting_tree_root_id'] = 'Кореневий ID дерева';
$_lang['setting_tree_root_id_desc'] = 'Вкажіть ID ресурсу, який буде коренем дерева ресурсів. Користувач матиме змогу бачити лише дочірні ресурси цього ресурсу.';

$_lang['setting_tvs_below_content'] = 'Розмістити TV нижче контенту';
$_lang['setting_tvs_below_content_desc'] = 'Set this to Yes to move TVs below the Content when editing Resources.';

$_lang['setting_ui_debug_mode'] = 'Режим налагодження інтерфейсу';
$_lang['setting_ui_debug_mode_desc'] = 'Якщо вибрано «Так», у консоль браузера будуть виводитись налагоджувальні повідомлення інтерфейсу системи управління. Ви повинні використовувати браузер, який підтримує console.log.';

$_lang['setting_unauthorized_page'] = 'Сторінка помилки 401 «Доступ заборонено»';
$_lang['setting_unauthorized_page_desc'] = 'Введіть ID ресурсу, який ви хочете виводити користувачам при запиті захищених ресурсів, що вимагають авторизації. <strong>ВАЖЛИВО: переконайтеся, що введений ID належить існуючому ресурсу, і цей ресурс опублікований та публічно доступний!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Будь ласка, вкажіть ID ресурсу, який буде сторінкою помилки 401 «Доступ заборонено».';

$_lang['setting_upload_files'] = 'Завантажувані типи файлів';
$_lang['setting_upload_files_desc'] = 'Тут можна вказати список типів файлів, які можна завантажувати за допомогою диспетчера файлів. Будь ласка, введіть розширення файлів, розділяючи їх комами.';

$_lang['setting_upload_file_exists'] = 'Перевіряти файли на існування під час завантаження';
$_lang['setting_upload_file_exists_desc'] = 'Якщо увімкнено, під час завантаження файлу з таким же ім\'ям буде показано повідомлення з помилкою. Якщо вимкнено, існуючий файл буде перезаписано новим.';

$_lang['setting_upload_maxsize'] = 'Максимальний розмір завантаження';
$_lang['setting_upload_maxsize_desc'] = 'Введіть максимальний розмір файлу для завантаження через диспетчер файлів. Розмір файлу має бути введений у байтах.';

$_lang['setting_upload_translit'] = 'Транслітерйтезувати імена завантажених файлів?';
$_lang['setting_upload_translit_desc'] = 'Якщо увімкнено цю опцію, назва завантаженого файлу буде транслітерація відповідно до глобальних правил перекладу.';

$_lang['setting_upload_translit_restrict_chars_pattern'] = 'File Name Character Restriction Pattern';
$_lang['setting_upload_translit_restrict_chars_pattern_desc'] = 'A valid RegEx pattern for restricting characters used in an uploaded file’s name.';

$_lang['setting_use_alias_path'] = 'Використовувати вкладені URL';
$_lang['setting_use_alias_path_desc'] = 'Встановлення значення «Так» для цієї опції зробить висновок повного шляху до ресурсу, якщо ресурс має псевдонім. Наприклад, якщо ресурс із псевдонімом "child" розташований всередині ресурсу-контейнера із псевдонімом "parent", то повний шлях до ресурсу буде виведений так: "/parent/child.html".<br /><strong>ВАЖЛИВО: Встановлюючи значення « Так» для цієї опції, використовуйте повний шлях для вказівки шляху до таких файлів, як зображення, CSS, JavaScript тощо: наприклад, "/assets/images", а не "assets/images". Або ж використовуйте тег &lt;base /&gt; для явної вказівки базової URL-адреси.</strong>';

$_lang['setting_use_editor'] = 'Використовувати текстовий редактор';
$_lang['setting_use_editor_desc'] = 'Чи хотіли б ви використовувати текстовий редактор? Якщо вам зручніше використовувати HTML, ви можете вимкнути текстовий редактор за допомогою цієї опції. Майте на увазі, що ця опція застосовується до всіх документів та користувачів!';
$_lang['setting_use_editor_err'] = 'Будь ласка, вкажіть, чи хочете ви чи ні використовувати RTE редактор.';

$_lang['setting_use_frozen_parent_uris'] = 'Використовувати «заморожені» URI батька';
$_lang['setting_use_frozen_parent_uris_desc'] = 'Якщо вибрано Так, URI для дочірніх ресурсів буде генеруватися з урахуванням «замороженого» URI батька, ігноруючи псевдоніми ресурсів вище по дереву.';

$_lang['setting_use_multibyte'] = 'Використовувати розширення Multibyte';
$_lang['setting_use_multibyte_desc'] = 'Встановіть значення "Так", якщо Ви хочете використовувати розширення mbstring для роботи з мультибайтними кодуваннями. Вказуйте "Так" лише в тому випадку, якщо розширення PHP mbstring встановлено на Вашому сервері.';

$_lang['setting_use_weblink_target'] = 'Використовувати цільове веб-посилання';
$_lang['setting_use_weblink_target_desc'] = 'Якщо вибрано "Так", MODX теги посилань і makeUrl() API виклик будуть генерувати кінцеві посилання, вказані як цільові URL-адреси для ресурсів типу "посилання". В іншому випадку, буде згенеровано внутрішнє посилання, що перенаправляє на цільову URL-адресу.';

$_lang['setting_user_nav_parent'] = 'Батьківський елемент для меню користувача';
$_lang['setting_user_nav_parent_desc'] = 'Контейнер містить всі записи меню користувача.';

$_lang['setting_welcome_screen'] = 'Показати заставку';
$_lang['setting_welcome_screen_desc'] = 'Якщо вибрано «Так», спливаюче вікно привітання буде відображатися одноразово під час наступного завантаження початкового екрана.';

$_lang['setting_welcome_screen_url'] = 'URL вікна привітання';
$_lang['setting_welcome_screen_url_desc'] = 'URL спливаючого вікна привітання, що відображається під час першого завантаження MODX Revolution.';

$_lang['setting_welcome_action'] = 'Дія початкового екрану';
$_lang['setting_welcome_action_desc'] = 'Стандартний контролер при вході в систему керування у випадку, коли контролер не заданий в URL.';

$_lang['setting_welcome_namespace'] = 'Простір імен початкового екрану';
$_lang['setting_welcome_namespace_desc'] = 'Простір імен, якому належить дія початкового екрана.';

$_lang['setting_which_editor'] = 'Редактор для використання';
$_lang['setting_which_editor_desc'] = 'Тут ви можете вибрати, який редактор використовувати. Ви можете завантажити та встановити додаткові редактори у розділі "Менеджер пакетів".';

$_lang['setting_which_element_editor'] = 'Редактор коду';
$_lang['setting_which_element_editor_desc'] = 'Тут ви можете вибрати який редактор використовувати під час редагування коду. Ви можете завантажити та встановити додаткові редактори у розділі "Менеджер пакетів".';

$_lang['setting_xhtml_urls'] = 'XHTML-сумісні URL';
$_lang['setting_xhtml_urls_desc'] = 'Якщо вибрано «Так», всі посилання, що генеруються MODX, будуть XHTML-сумісними (символ &amp; буде замінено на відповідну сутність &amp;amp;).';

$_lang['setting_default_context'] = 'Контекст за замовчуванням';
$_lang['setting_default_context_desc'] = 'Виберіть контекст, який використовується за умовчанням під час створення нового ресурсу.';

$_lang['setting_auto_isfolder'] = 'Встановити контейнер автоматично';
$_lang['setting_auto_isfolder_desc'] = 'Якщо налаштування увімкнено, ресурс буде позначений як «контейнер» автоматично.';

$_lang['setting_default_username'] = 'Ім\'я користувача за замовчуванням';
$_lang['setting_default_username_desc'] = 'Ім\'я користувача за умовчанням для неавтентифікованих.';

$_lang['setting_manager_use_fullname'] = 'Відображати «Повне ім\'я» у «шапці» системи керування';
$_lang['setting_manager_use_fullname_desc'] = 'Якщо налаштування увімкнено, у шапці системи керування відображатиметься вміст поля «Повне ім\'я» замість «Ім\'я користувача»';

$_lang['setting_log_snippet_not_found'] = 'Записувати помилки в журнал, якщо сніпет не знайдено';
$_lang['setting_log_snippet_not_found_desc'] = 'Якщо вибрано «Так», під час виклику сніппетів, які не були знайдені, з\'явиться запис у «Журналі помилок».';

$_lang['setting_error_log_filename'] = 'Ім\'я файлу журналу помилок';
$_lang['setting_error_log_filename_desc'] = 'Введіть назву файлу журналу помилок MODX (включаючи розширення файлу).';

$_lang['setting_error_log_filepath'] = 'Шлях до журналу помилок';
$_lang['setting_error_log_filepath_desc'] = 'Optionally set a absolute path the a custom error log location. You might use placeholders like {cache_path}.';

$_lang['setting_passwordless_activated'] = 'Активувати вхід без пароля';
$_lang['setting_passwordless_activated_desc'] = 'When enabled, users will enter their email address to receive a one-time login link, rather than entering a username and password.';

$_lang['setting_passwordless_expiration'] = 'Passwordless login expiration';
$_lang['setting_passwordless_expiration_desc'] = 'How long a one-time login link is valid in seconds.';

$_lang['setting_static_elements_html_extension'] = 'Розширення файлів для статичних елементів';
$_lang['setting_static_elements_html_extension_desc'] = 'Розширення для файлів, що використовуються статичними елементами з вмістом HTML.';

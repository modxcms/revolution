<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Перевірка існування і можливості запису до <span class="mono">[[+file]]</span>: ';
$_lang['test_config_file_nw'] = 'Для нової інсталяції на системах Linux/Unix, будь ласка, створіть порожній файл з іменем <span class="mono">[[+key]].inc.php</span> у каталозі MODX core <span class="mono">config/</span> з правами доступу, які дозволяють PHP змінювати його.';
$_lang['test_db_check'] = 'Створення підключення до бази даних: ';
$_lang['test_db_check_conn'] = 'Перевірте параметри підключення і повторіть спробу.';
$_lang['test_db_failed'] = 'Не вдалося встановити з\'єднання з базою даних!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Перевірка PHP-розширення zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Your PHP installation does not have the "zlib" extension installed. This extension is necessary for MODX to run. Please enable it to continue.';
$_lang['test_directory_exists'] = 'Перевірка існування каталогу <span class="mono">[[+dir]]</span>: ';
$_lang['test_directory_writable'] = 'Перевірка можливості запису до каталога <span class="mono">[[+dir]]</span> : ';
$_lang['test_memory_limit'] = 'Перевірка виділеної пам\'яті (повинно бути не менше 24М): ';
$_lang['test_memory_limit_fail'] = 'MODX помітив, що значення memory_limit становить [[+memory]], що є нижчим за рекомендоване значення 24M. MODX спробував встановити memory_limit у 24M, але це йому не вдалося. Будь ласка, встановіть значення memory_limit у Вашому файлі php.ini у 24M або вище перед тим, як продовжити. Якщо це не вирішило проблему (наприклад, Ви бачите білий екран при встановленні), встановіть значення у 32M, 64M або вище.';
$_lang['test_memory_limit_success'] = 'ОК! memory_limit становить [[+memory]]';
$_lang['test_mysql_version_5051'] = 'У MODX можуть виникати проблеми при роботі з Вашою версією MySQL ([[+version]]) через помилки, пов\'язані з драйверами PDO у цій версії. Будь ласка, оновіть MySQL для усунення цих проблем. Навіть якщо Ви не будете використовувати MODX, радимо Вам оновити цю версію MySQL для підвищення стабільності та безпеки Вашого сайту.';
$_lang['test_mysql_version_client_nf'] = 'Не вдається визначити версію клієнта MySQL!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX не вдалося визначити версію Вашого MySQL-клієнта за допомогою функції mysql_get_client_info(). Будь ласка, перед тим, як продовжити, переконайтеся власноруч, що версія Вашого MySQL-клієнта не нижче 4.1.20.';
$_lang['test_mysql_version_client_old'] = 'У MODX можуть виникнути проблеми через те, що Ви використовуєте дуже стару версію клієнта MySQL ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX може бути встановлений з цією версією клієнта MySQL, але ми не гарантуємо, що усі функціональні можливості будуть доступні і працюватимуть відповідним чином при використанні старих версій бібліотек клієнта MySQL.';
$_lang['test_mysql_version_client_start'] = 'Перевірка версії MySQL-клієнта:';
$_lang['test_mysql_version_fail'] = 'Ви використовуєте MySQL [[+version]], а MODX Revolution потребує MySQL 4.1.20 або більш пізньої версії. Будь ласка, оновіть MySQL хоча б до версії 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Не вдалося визначити версію сервера MySQL!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX не вдалося визначити версію Вашого MySQL-сервера за допомогою функції mysql_get_server_info(). Будь ласка, перед тим, як продовжити, переконайтеся власноруч, що версія Вашого MySQL-сервера не нижче 4.1.20.';
$_lang['test_mysql_version_server_start'] = 'Перевірка версії MySQL-сервера:';
$_lang['test_mysql_version_success'] = 'OK! Працює: [[+version]]';
$_lang['test_nocompress'] = 'Перевірка, чи слід вимкнути стиснення CSS/JS: ';
$_lang['test_nocompress_disabled'] = 'ОК! Вимкнено.';
$_lang['test_nocompress_skip'] = 'Не вибрано, пропускаємо тест.';
$_lang['test_php_version_fail'] = 'Ви використовуєте PHP [[+version]], а для роботи MODX Revolution необхідний PHP версії 5.1.1 або вище. Будь ласка, оновіть PHP принаймні до 5.1.1. MODX рекомендує оновити хоча б до версії 5.3.2+.';
$_lang['test_php_version_start'] = 'Перевірка версії PHP:';
$_lang['test_php_version_success'] = 'OK! Працює: [[+version]]';
$_lang['test_safe_mode_start'] = 'Перевірка, чи вимкнена директива safe_mode:';
$_lang['test_safe_mode_fail'] = 'MODX з\'ясував, що директива safe_mode увімкнена. Для того, щоб продовжити, Ви маєте вимкнути safe_mode у Вашій конфігурації PHP.';
$_lang['test_sessions_start'] = 'Перевірка правильного налаштування сесій:';
$_lang['test_simplexml'] = 'Перевірка SimpleXML:';
$_lang['test_simplexml_nf'] = 'Не вдалося знайти SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX не зміг знайти SimpleXML у Вашому середовищі PHP. Керування пакунками та інші можливості не працюватимуть без нього. Ви можете продовжити встановлення, але рекомендується увімкнути SimpleXML для використання його можливостей.';
$_lang['test_suhosin'] = 'Перевірка suhosin:';
$_lang['test_suhosin_max_length'] = 'Максимальне значення Suhosin GET занадто низьке!';
$_lang['test_suhosin_max_length_err'] = 'Ви використовуєте доповнення PHP Suhosin, і Ваше значення suhosin.get.max_value_length занадто низьке для того, щоб MODX міг правильно стискати JS-файли у менеджері. MODX рекомендує підняти це значення до 4096, а поки що MODX автоматично встановить стиснення JS (параметр compress_js)  у 0 для запобігання виникнення помилок.';
$_lang['test_table_prefix'] = 'Перевірка префіксу таблиць `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Table prefix is already in use in this database!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'У базі даних немає такого префіксу!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Перевірка виділеної пам\'яті для zip-розширень (повинно бути не менше 24М): ';
$_lang['test_zip_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';
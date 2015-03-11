<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Проверяване дали <span class="mono">[[+file]]</span> съществува и е достъпен за запис: ';
$_lang['test_config_file_nw'] = 'За нови Linux/Unix инсталации, моля създайте празен файл с име <span class="mono">[[+key]].inc.php</span> във вашата MODX главна<span class="mono">config/</span> директория с права настроени за запис от PHP.';
$_lang['test_db_check'] = 'CrСъздаване на връзка с базата данни: ';
$_lang['test_db_check_conn'] = 'Проверете параметрите на връзката и опитайте отново.';
$_lang['test_db_failed'] = 'Връзката с базата данни пропадна!';
$_lang['test_db_setup_create'] = 'Инсталацията ще се опита да създаде база данни.';
$_lang['test_dependencies'] = 'Проверка на PHP за zlib зависимост: ';
$_lang['test_dependencies_fail_zlib'] = 'PHP инсталацията ви не разполага с инсталирано "zlib" разширение. Това разширение е необходимо за да тръгне MODX. Моля разрешете го, за да продължите.';
$_lang['test_directory_exists'] = 'Проверка дали директорията <span class="mono">[[+dir]]</span> съществува: ';
$_lang['test_directory_writable'] = 'Проверка дали директорията <span class="mono">[[+dir]]</span> е записваема: ';
$_lang['test_memory_limit'] = 'Проверка дали лимита на паметта е настроен поне на 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX намери вашите memory_limit настройки да бъдат на [[+memory]], под препоръчителните от 24M. MODX се опита да зададе memory_limit на 24M, но без успех. Моля задайте memory_limit настройките във вашия php.ini файл на поне 24M или повече, преди да продължите. Ако продължавате да имате затруднения (като например бял екран при инсталиране), задайте 32M, 64M или по-високо.';
$_lang['test_memory_limit_success'] = 'OK! Зададено на [[+memory]]';
$_lang['test_mysql_version_5051'] = 'Ще възникнат проблеми при MODX с вашата MySQL версия ([[+version]]), заради многото грешки, свързани с PDO драиверите на тази версия. Моля актуализирайте MySQL за да бъдат избегнати тези проблеми. Дори и да не изберете използването на MODX, е препоръчително да актуализирате до тази версия с цел сигурност и стабилност на вашия собствен сайт.';
$_lang['test_mysql_version_client_nf'] = 'Не може да открие версията на MySQL клиента!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX не може да открие версията на вашия MySQL клиент чрез mysql_get_client_info(). Моля, преди да продължите, проверете ръчно, че версията на вашия MySQL клиент е поне 4.1.20.';
$_lang['test_mysql_version_client_old'] = 'Може да възникнат проблеми с MODX тъй като използвате много стара версия на MySQL клиент ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX ще позволи инсталацията, използвайки тази MySQL версия, но не можем да гарантираме за пълната функционалност и работа при използването на стари MySQL библиотеки.';
$_lang['test_mysql_version_client_start'] = 'Проверяване на MySQL версията:';
$_lang['test_mysql_version_fail'] = 'Използвате MySQL [[+version]], а MODX Revolution изисква MySQL 4.1.20 или по-нов. Моля, актуализирайте MySQL поне до версия 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Не може да открия версията на MySQL сървъра!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX не може да открие версията на вашия MySQL сървър чрез mysql_get_server_info(). Моля, преди да продължите, проверете ръчно дали версията на вашия MySQL сървър е поне 4.1.20 .';
$_lang['test_mysql_version_server_start'] = 'Проверка версията на MySQL сървъра:';
$_lang['test_mysql_version_success'] = 'OK! Използва: [[+version]]';
$_lang['test_nocompress'] = 'Проверяване дали трябва да се изключи CSS/JS компресия: ';
$_lang['test_nocompress_disabled'] = 'OK! Забранен.';
$_lang['test_nocompress_skip'] = 'Не е избрано, пропускане на теста.';
$_lang['test_php_version_fail'] = 'Използвате PHP [[+version]], а MODX Revolution изисква PHP 5.1.1 или по-нов. Моля, наравете ъпгрйд на PHP поне до версия 5.1.1. MODX препоръчва ъпгрей най-малко до версия 5.3.2+.';
$_lang['test_php_version_516'] = 'Ще възникнат проблеми при MODX с вашата PHP версия ([[+version]]), заради многото грешки, свързани с PDO драиверите на тази версия. Моля актуализирайте PHP до версия 5.3.0 или по-нова, за да бъдат избегнати тези проблеми. MODX препоръчва ъпгрейд поне до версия 5.3.2+. Дори и да не изберете използването на MODX, е препоръчително да актуализирате до тази версия с цел сигурност и стабилност на вашия собствен сайт.';
$_lang['test_php_version_520'] = 'Ще възникнат проблеми при MODX с вашата PHP версия ([[+version]]), заради многото грешки, свързани с PDO драиверите на тази версия. Моля актуализирайте PHP до версия 5.3.0 или по-нова, за да бъдат избегнати тези проблеми. MODX препоръчва ъпгрейд поне до версия 5.3.2+. Дори и да не изберете използването на MODX, е препоръчително да актуализирате до тази версия с цел сигурност и стабилност на вашия собствен сайт.';
$_lang['test_php_version_start'] = 'Проверка на PHP версия:';
$_lang['test_php_version_success'] = 'OK! Използва: [[+version]]';
$_lang['test_safe_mode_start'] = 'Проверка, дали safe_mode е изключен:';
$_lang['test_safe_mode_fail'] = 'MODX откри, че safe_mode е включен. За да продължите трябва да изключите safe_mode във вашата PHP конфигурация configuration.';
$_lang['test_sessions_start'] = 'Проверка дали сесиите са правилно конфигурирани:';
$_lang['test_simplexml'] = 'Проверка за SimpleXML:';
$_lang['test_simplexml_nf'] = 'Не може да намери SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX не може да открие SimpleXML във вашата PHP среда. Управлението на пакетите и други функционалности няма да работят, ако то не е инсталирано. Може да продължите с инсталацията, но MODX препоръчва разрешаването на SimpleXML с цел разширени функции и функционалност.';
$_lang['test_suhosin'] = 'Проверка за suhosin проблеми:';
$_lang['test_suhosin_max_length'] = 'Suhosin GET max value е твърде ниско!';
$_lang['test_suhosin_max_length_err'] = 'В момента използвате PHP suhosin разширение, а вашето suhosin.get.max_value_length е настроено твърде ниско, за да може MODX правилно да компресира JS файлове в мениджъра. MODX препоръчва да покачите стойността на 4096; дотогава MODX автоматично ще задава вашата JS компресия (compress_js setting) на 0 за да предотврати грешки.';
$_lang['test_table_prefix'] = 'Проверка на табличния префикс `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Табличния префикс е готов за употреба в тази база данни!';
$_lang['test_table_prefix_inuse_desc'] = 'Инсталацията не може да се инсталира в съответната база данни, тъй като тя вече съдържа таблици със зададения от вас префикс. Моля, изберете нов table_prefix и пробвайте отново.';
$_lang['test_table_prefix_nf'] = 'В тази база данни не съществува табличен префикс!';
$_lang['test_table_prefix_nf_desc'] = 'Инсталацията не може да се инсталира в съответната база данни, тъй като не съдържа таблици със зададения за актуализиране префикс. Моля, изберете нов table_prefix и пробвайте отново.';
$_lang['test_zip_memory_limit'] = 'Проверка дали лимита на паметта е настроен на поне 24M за zip разширения: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX намери, че настройката на memory_limit е под препоръчителната от 24M. Опита на MODX да настрои memory_limit на 24M беше неуспешен. Моля задайте memory_limit настройката във вашия php.ini файл на 24M или повече преди да продължите, така че zip разширенията да функционират правилно.';
<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Проверка существования и возможности записи в файл <span class="mono">[[+file]]</span>: ';
$_lang['test_config_file_nw'] = 'Для новой установки на Linux/Unix системах, создайте пустой файл с названием <span class="mono">[[+key]].inc.php</span> в каталоге <span class="mono">config/</span> с правами доступа, позволяющими веб-серверу его изменять.';
$_lang['test_db_check'] = 'Создание подключения к базе данных: ';
$_lang['test_db_check_conn'] = 'Проверьте параметры соединения и повторите попытку.';
$_lang['test_db_failed'] = 'Связь с базой данных не установлена!';
$_lang['test_db_setup_create'] = 'Программа установки пытается создать базу данных.';
$_lang['test_dependencies'] = 'Проверка PHP расширения zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Ваша конфигурация PHP  не имеет установленного расширения zlib. Это расширение необходимо для запуска MODX. Пожалуйста, установите его, чтобы продолжить.';
$_lang['test_directory_exists'] = 'Проверка существования каталога <span class="mono">[[+dir]]</span>: ';
$_lang['test_directory_writable'] = 'Проверка возможности записи в каталог <span class="mono">[[+dir]]</span>: ';
$_lang['test_memory_limit'] = 'Проверка  выделенной памяти (должно быть не менее 24 МБ): ';
$_lang['test_memory_limit_fail'] = 'Параметр "memory_limit" (ограничения выделяемой памяти) равен [[+memory]], что меньше рекомендуемого 24 МБ. MODX не смог самостоятельно его повысить. Для продолжения установите в файле php.ini этот параметр равным 24 МБ или больше. Если это не решило проблему (пустой белый экран во время установки), повышайте memory_limit до 32 МБ, 64 МБ или выше.';
$_lang['test_memory_limit_success'] = 'OK! Параметр "memory_limit" (ограничения выделяемой памяти) равен [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX будет испытывать проблемы с вашей версией MySQL ([[+version]]), которые обусловлены многочисленными ошибками, связанных с работой PDO драйвера в этой версии. Обновите MySQL для устранения этих проблем. Даже если вы не будете использовать MODX, мы рекомендуем обновить эту версию MySQL для повышения стабильности и безопасности.';
$_lang['test_mysql_version_client_nf'] = 'Не удаётся определить версию клиента MySQL!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX не смог определить версию MySQL-клиента, используя функцию "mysql_get_client_info()". Проверьте версию самостоятельно, она должна быть не ниже 4.1.20.';
$_lang['test_mysql_version_client_old'] = 'Могут возникнуть проблемы в работе MODX, потому что вы используете очень старую версию MySQL клиента ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX позволяет установку с этой версией MySQL клиента, но мы не можем гарантировать, что все функциональные возможности будут доступны и будут работать должным образом при использовании старых версий MySQL клиента.';
$_lang['test_mysql_version_client_start'] = 'Проверка версии MySQL-клиента: ';
$_lang['test_mysql_version_fail'] = 'Вы используете MySQL [[+version]], для MODX требуется MySQL 4.1.20 или более поздняя версия. Обновите MySQL хотя бы до 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Не удалось определить версию сервера MySQL!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX не смог определить версию MySQL- сервера, используя "mysql_get_server_info()". Убедитесь, что версия вашего сервера не меньше 4.1.20, перед тем как продолжить.';
$_lang['test_mysql_version_server_start'] = 'Проверка версии MySQL-сервера: ';
$_lang['test_mysql_version_success'] = 'OK! Работает: [[+version]]';
$_lang['test_nocompress'] = 'Проверка выключено ли сжатие CSS/JS: ';
$_lang['test_nocompress_disabled'] = 'OK! Отключено.';
$_lang['test_nocompress_skip'] = 'Не выбрано, пропускаем тест.';
$_lang['test_php_version_fail'] = 'Используется PHP [[+version]], а для работы MODX Revolution необходим PHP [[+required]] или выше. Обновите PHP до версии не ниже [[+required]]. MODX рекомендует обновление до [[+recommended]].';
$_lang['test_php_version_start'] = 'Проверка версии PHP: ';
$_lang['test_php_version_success'] = 'OK! Работает: [[+version]]';
$_lang['test_safe_mode_start'] = 'Проверка того, что директива "safe_mode" выключена (off): ';
$_lang['test_safe_mode_fail'] = 'MODX обнаружил, что директива safe_mode  включена. Для продолжения установки вы должны отключить safe_mode в вашей конфигурации PHP.';
$_lang['test_sessions_start'] = 'Проверка настроек сессий: ';
$_lang['test_simplexml'] = 'Проверка SimpleXML: ';
$_lang['test_simplexml_nf'] = 'Не удалось найти SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX не смог найти SimpleXML в вашем окружении PHP. «Менеджер пакетов» и другие возможности не будут работать. Вы можете продолжить установку, но рекомендуется включить SimpleXML для использования всех возможностей.';
$_lang['test_suhosin'] = 'Проверка расширения "suhosin": ';
$_lang['test_suhosin_max_length'] = 'Suhosin GET max значение слишком низкое!';
$_lang['test_suhosin_max_length_err'] = 'Вы используете расширение "suhosin" PHP, и ваши настройки suhosin.get.max_value_length установлены в слишком низкое значение для MODX для правильного сжатия JS-файлов в системе управления. MODX рекомендует повысить это значение до 4096; на данный момент MODX автоматически установит для вашего сжатия JS (параметр "compress_js") 0, чтобы предотвратить ошибки.';
$_lang['test_table_prefix'] = 'Проверка префикса таблиц `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Такой префикс таблиц уже используется в указанной базе данных!';
$_lang['test_table_prefix_inuse_desc'] = 'Продолжение установки невозможно — таблицы с указанным префиксом уже существуют. Измените префикс таблиц и попробуйте снова.';
$_lang['test_table_prefix_nf'] = 'Нет такого префикса таблиц в базе данных!';
$_lang['test_table_prefix_nf_desc'] = 'Продолжение установки невозможно, так как в выбранной базе данных нет таблиц с префиксом, указанным вами для обновления. Измените table_prefix и начните установку снова.';
$_lang['test_zip_memory_limit'] = 'Проверка доступной памяти (для zip-расширений должно быть выделено не менее 24 МБ): ';
$_lang['test_zip_memory_limit_fail'] = 'Для выполнения скриптов выделено меньше 24 МБ памяти (параметр "memory_limit"). MODX не смог самостоятельно увеличить объём памяти до 24 МБ. Для продолжения установите в файле php.ini параметр "memory_limit "равным 24 МБ или больше, тогда zip-расширения смогут работать корректно.';
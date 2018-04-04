<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Checking if <span class="mono">[[+file]]</span> exists and is writable: ';
$_lang['test_config_file_nw'] = 'Для новых Linux/Unix ўсталёвак, калі ласка, стварыце ў каталозе <span class="mono"> config</span> ядра MODX пусты файл з імем <span class="mono">[[+key]].inc.php</span> з правамі, дазваляючымі запіс з PHP.';
$_lang['test_db_check'] = 'Creating connection to the database: ';
$_lang['test_db_check_conn'] = 'Check the connection details and try again.';
$_lang['test_db_failed'] = 'Database connection failed!';
$_lang['test_db_setup_create'] = 'Setup will attempt to create the database.';
$_lang['test_dependencies'] = 'Checking PHP for zlib dependency: ';
$_lang['test_dependencies_fail_zlib'] = 'Your PHP installation does not have the "zlib" extension installed. This extension is necessary for MODX to run. Please enable it to continue.';
$_lang['test_directory_exists'] = 'Checking if <span class="mono">[[+dir]]</span> directory exists: ';
$_lang['test_directory_writable'] = 'Checking if <span class="mono">[[+dir]]</span> directory is writable: ';
$_lang['test_memory_limit'] = 'Checking if memory limit is set to at least 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX знайшоў, што ваша налада memory_limit мае значэнне [[+memory]], што ніжэй рэкамендуемага значэння 24М. MODX паспрабаваў ўсталяваць memory_limit як 24М, але беспаспяхова. Калі ласка, усталюйце наладу memory_limit ў файле php.ini, па меншай меры, 24M або вышэй, перш чым працягнуць. Калі ў вас ўсё яшчэ ўзнікаюць праблемы (напрыклад, пусты белы экран падчас усталёўкі), усталюйце 32M, 64M або вышэй.';
$_lang['test_memory_limit_success'] = 'Добра! Абмежаванне па памяці ўстаноўлена ў [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX будзе мець праблемы на вашай версіі MySQL ([[+version]]) з-за шматлікіх памылак, звязаных з драйверамі PDO на гэтай версіі. Калі ласка, абнавіце MySQL, каб выправіць гэтыя праблемы. Нават, калі вы вырашыце не выкарыстоўваць MODX, рэкамендуецца выканаць абнаўленне да гэтай версіі для забеспячэння бяспекі і стабільнасці вашага ўласнага сайта.';
$_lang['test_mysql_version_client_nf'] = 'Не атрымалася выявіць версію MySQL кліента!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX не змог выявіць версію кліента MySQL з дапамогай mysql_get_client_info(). Пераканайцеся самастойна, што версія вашага кліента MySQL, па меншай меры, 4.1.20, паперш чым працягнуць.';
$_lang['test_mysql_version_client_old'] = 'MODX можа мець праблемы, таму што вы выкарыстоўваеце вельмі старую версію кліента MySQL ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX дазволіць ўстаноўку, выкарыстоўваючы гэту версію кліента MySQL, але мы не можам гарантаваць, што ўсе функцыянальныя магчымасці будуць даступны або працаваць належным чынам пры выкарыстанні больш старых версій кліенцкіх бібліятэк MySQL.';
$_lang['test_mysql_version_client_start'] = 'Праверка версіі кліента MySQL:';
$_lang['test_mysql_version_fail'] = 'Вы працуеце на MySQL [[+version]], але MODx Revolution патрабуе MySQL 4.1.20 або больш позняй версіі. Калі ласка, абнавіце MySQL, па меншай меры да 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Не атрымалася выявіць версію MySQL сервера!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX не змог выявіць версію сервера MySQL з дапамогай mysql_get_server_info(). Калі ласка, самастойна пераканайцеся, што ваша версія сервера MySQL з\'яўляецца, па меншай меры 4.1.20, перш чым працягнуць.';
$_lang['test_mysql_version_server_start'] = 'Праверка версіі сервера MySQL:';
$_lang['test_mysql_version_success'] = 'Добра! Працуе: [[+version]]';
$_lang['test_nocompress'] = 'Праверка, ці павінны мы адключыць сціск CSS/JS: ';
$_lang['test_nocompress_disabled'] = 'Добра! Адключана.';
$_lang['test_nocompress_skip'] = 'Не абрана, прапускаем тэст.';
$_lang['test_php_version_fail'] = 'Выкарыстоўваецца PHP [[+version]] і MODX Revolution патрабуе PHP [[+required]] ці вышэй. Калі ласка абнавіце PHP па меншай меры да [[+required]]. MODX рэкамендуе абнаўленне да бягучай стабільнай версіі [[+recommended]] па меркаваннях бяспекі і для падтрымкі ў будучыні.';
$_lang['test_php_version_start'] = 'Праверка версіі PHP:';
$_lang['test_php_version_success'] = 'Добра! Працуе: [[+version]]';
$_lang['test_safe_mode_start'] = 'Праверка, каб пераканацца, што safe_mode выключаны:';
$_lang['test_safe_mode_fail'] = 'MODX знайшоў уключаны safe_mode. Вы павінны адключыць safe_mode ў канфігурацыі PHP, каб працягнуць.';
$_lang['test_sessions_start'] = 'Checking if sessions are properly configured:';
$_lang['test_simplexml'] = 'Праверка SimpleXML:';
$_lang['test_simplexml_nf'] = 'Не атрымалася знайсці SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX не змог знайсці SimpleXML у вашым РНР асяроддзі. Кіраванне пакетамі і іншыя функцыі не будуць працаваць без яго. Вы можаце працягнуць усталёўку, але MODX рэкамендуе ўключыць SimpleXML для дадатковых функцый і функцыянальных магчымасцяў.';
$_lang['test_suhosin'] = 'Праверка на памылкі suhosin:';
$_lang['test_suhosin_max_length'] = 'Значэнне suhosin GET max занадта малое!';
$_lang['test_suhosin_max_length_err'] = 'Вы карыстаецеся пашырэннем Suhosin для PHP і вашае значэнне suhosin.get.max_value_length усталявана занадта нізка для MODX, каб правільна сціскаць JS файлы. MODX рэкамендуе павысіць гэта значэнне да 4096; пакуль што MODX будзе аўтаматычна ўсталёўваць сцісканне JS (налада compress_js) у 0, каб пазбегнуць памылак.';
$_lang['test_table_prefix'] = 'Праверка прэфікса табліцы `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Прэфікс табліцы ўжо выкарыстоўваюцца ў гэтай базе дадзеных!';
$_lang['test_table_prefix_inuse_desc'] = 'Setup couldn\'t install into the selected database, as it already contains tables with the prefix you specified. Please choose a new table_prefix, and run Setup again.';
$_lang['test_table_prefix_nf'] = 'Table prefix does not exist in this database!';
$_lang['test_table_prefix_nf_desc'] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table_prefix, and run Setup again.';
$_lang['test_zip_memory_limit'] = 'Checking if memory limit is set to at least 24M for zip extensions: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX found your memory_limit setting to be below the recommended setting of 24M. MODX attempted to set the memory_limit to 24M, but was unsuccessful. Please set the memory_limit setting in your php.ini file to 24M or higher before proceeding, so that the zip extensions can work properly.';
<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX патрабуе mysql пашырэнне для PHP і па ўсёй бачнасці, не было загружана.';
$_lang['mysql_err_pdo'] = 'MODX патрабуе драйвер pdo_mysql у той час, калі выкарыстоўваецца роднае PDO, але здаецца, што драйвер не загружаны.';
$_lang['mysql_version_5051'] = 'MODX будзе мець праблемы на вашай версіі MySQL ([[+version]]) з-за шматлікіх памылак, звязаных з драйверамі PDO на гэтай версіі. Калі ласка, абнавіце MySQL, каб выправіць гэтыя праблемы. Нават, калі вы вырашыце не выкарыстоўваць MODX, рэкамендуецца выканаць абнаўленне да гэтай версіі для забеспячэння бяспекі і стабільнасці вашага ўласнага сайта.';
$_lang['mysql_version_client_nf'] = 'MODX не змог выявіць версію кліента MySQL з дапамогай mysql_get_client_info(). Пераканайцеся самастойна, што версія вашага кліента MySQL, па меншай меры, 4.1.20, паперш чым працягнуць.';
$_lang['mysql_version_client_start'] = 'Праверка версіі кліента MySQL:';
$_lang['mysql_version_client_old'] = 'MODX можа мець праблемы, таму што вы выкарыстоўваеце вельмі старую версію кліента MySQL ([[+version]]). MODX дазволіць ўстаноўку з гэтай версіяй кліента MySQL, але мы не можам гарантаваць, што усе функцыянальныя магчымасці будуць даступныя або працаваць належным чынам пры выкарыстанні больш старых версій кліенцкіх бібліятэк MySQL.';
$_lang['mysql_version_fail'] = 'Вы працуеце на MySQL [[+version]], але MODx Revolution патрабуе MySQL 4.1.20 або больш позняй версіі. Калі ласка, абнавіце MySQL, па меншай меры да 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX не змог выявіць версію сервера MySQL з дапамогай mysql_get_server_info(). Калі ласка, самастойна пераканайцеся, што ваша версія сервера MySQL з\'яўляецца, па меншай меры 4.1.20, перш чым працягнуць.';
$_lang['mysql_version_server_start'] = 'Праверка версіі сервера MySQL:';
$_lang['mysql_version_success'] = 'Добра! Працуе: [[+version]]';

$_lang['sqlsrv_version_success'] = 'Добра!';
$_lang['sqlsrv_version_client_success'] = 'Добра!';
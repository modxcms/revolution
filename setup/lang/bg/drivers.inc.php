<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX изисква mysql разширението за PHP и не се появява за зареждане.';
$_lang['mysql_err_pdo'] = 'MODX изисква pdo_mysql при използването на нативно PDO, което не изглежда да е заредено.';
$_lang['mysql_version_5051'] = 'Ще възникнат проблеми при MODX с вашата MySQL версия ([[+version]]), заради мното грешки, свързани с PDO драиверите на тази версия. Моля актуализирайте MySQL за да бъдат поправени тези проблеми. Дори и да не изберете използването на MODX, е препоръчително да актуализирате до тази версия с цел сигурност и стабилност на вашия собствен сайт.';
$_lang['mysql_version_client_nf'] = 'MODX не може да открие версията на вашия MySQL клиент чрез mysql_get_client_info(). Моля, преди да продължите, проверете ръчно, че версията на вашия MySQL клиент е поне 4.1.20.';
$_lang['mysql_version_client_start'] = 'Проверяване на MySQL версията:';
$_lang['mysql_version_client_old'] = 'Може да възникнат проблеми при MODX, понеже използвате много стара MySQL версия ([[+version]]). MODX ще позволи инсталацията, използвайки тази MySQL версия, но не можем да гарантираме, че пълната функционалност ще бъде налична или ще работи безотказно, когато използвате стари версии на  MySQL библиотеки.';
$_lang['mysql_version_fail'] = 'Използвате MySQL [[+version]], а MODX Revolution изисква MySQL 4.1.20 или по-нов. Моля, актуализирайте MySQL поне до версия 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX не може да открие версията на вашия MySQL сървър чрез mysql_get_server_info(). Моля, преди да продължите, проверете ръчно дали версията на вашия MySQL сървър е поне 4.1.20 .';
$_lang['mysql_version_server_start'] = 'Проверка версията на MySQL сървъра:';
$_lang['mysql_version_success'] = 'OK! Използва: [[+version]]';

$_lang['sqlsrv_version_success'] = 'ОК!';
$_lang['sqlsrv_version_client_success'] = 'ОК!';
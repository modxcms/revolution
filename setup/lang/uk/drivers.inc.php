<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX потребує розширення mysql для PHP, і, схоже, воно не було завантажено.';
$_lang['mysql_err_pdo'] = 'MODX requires the pdo_mysql driver when native PDO is being used and it does not appear to be loaded.';
$_lang['mysql_version_5051'] = 'MODX матиме проблеми з Вашою версією MySQL ([[+version]]), що зумовлено  численними помилками, пов\'язаними з роботою драйвера PDO у цій версії. Будь ласка, оновіть MySQL для вирішення цих проблем. Навіть якщо Ви не будете використовувати MODX, ми рекомендуємо Вам оновити до цієї версії для підвищення стабільності і безпеки Вашого сайту.';
$_lang['mysql_version_client_nf'] = 'MODX не вдалося визначити версію Вашого MySQL-клієнта за допомогою функції mysql_get_client_info(). Будь ласка, перед тим, як продовжити, переконайтеся власноруч, що версія Вашого MySQL-клієнта не нижче 4.1.20.';
$_lang['mysql_version_client_start'] = 'Перевірка версії MySQL-клієнта:';
$_lang['mysql_version_client_old'] = 'У MODX можуть виникати проблеми через те, що Ви використовуєте дуже стару версію MySQL-клієнта ([[+version]]). MODX може продовжити встановлення з цією версію MySQL-клієнта, проте ми не можемо гарантувати, що вся функціональність буде доступна і працюватиме належним чином при використанні старих версій бібліотек MySQL-клієнта.';
$_lang['mysql_version_fail'] = 'Ви використовуєте MySQL [[+version]], а MODX Revolution потребує MySQL 4.1.20 або більш пізньої версії. Будь ласка, оновіть MySQL хоча б до версії 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX не вдалося визначити версію Вашого MySQL-сервера за допомогою функції mysql_get_server_info(). Будь ласка, перед тим, як продовжити, переконайтеся власноруч, що версія Вашого MySQL-сервера не нижче 4.1.20.';
$_lang['mysql_version_server_start'] = 'Перевірка версії MySQL-сервера:';
$_lang['mysql_version_success'] = 'OK! Працює: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';
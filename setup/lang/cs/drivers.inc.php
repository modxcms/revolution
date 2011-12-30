<?php
/**
 * Czech Drivers Lexicon Topic for Revolution setup
 *
 * @language cs
 * @package setup
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2011-12-29
 */
// $_lang['mysql_err_ext'] = 'MODX requires the mysql extension for PHP and it does not appear to be loaded.';
$_lang['mysql_err_ext'] = 'MODX vyžaduje MySQL extenzi pro PHP a ta se zdá být nenačtena.';

// $_lang['mysql_err_pdo'] = 'MODX requires the pdo_mysql driver when native PDO is being used and it does not appear to be loaded.';
$_lang['mysql_err_pdo'] = 'MODX vyžaduje ovladač pdo_mysql pokud má být používáno nativní PDO a ten se zdá být nenačten.';

// $_lang['mysql_version_5051'] = 'MODX will have issues on your MySQL version ([[+version]]), because of the many bugs related to the PDO drivers on this version. Please upgrade MySQL to patch these problems. Even if you choose not to use MODX, it is recommended you upgrade to this version for the security and stability of your own website.';
$_lang['mysql_version_5051'] = 'MODX bude mít problémy s Vaší verzí MySQL ([[+version]]), z důvodu mnoha chyb spojených s PDO ovladač v této verzi. Aktualizujte MySQL pro odstranění těchto chyb. I v případě, že nebudete používat MODX důrazně doporučujeme aktualizaci MySQL z důvodů zabezpečení a stability Vašich vlastních stránek.';

// $_lang['mysql_version_client_nf'] = 'MODX could not detect your MySQL client version via mysql_get_client_info(). Please manually make sure that your MySQL client version is at least 4.1.20 before proceeding.';
$_lang['mysql_version_client_nf'] = 'MODX se nepodařilo zjistit verzi Vašeho MySQL klienta pomocí mysql_get_client_info(). Před tím než budete pokračovat se prosím ujistěte, že verze Vašeho MySQL klienta je 4.1.20 nebo novější.';

// $_lang['mysql_version_client_start'] = 'Checking MySQL client version:';
$_lang['mysql_version_client_start'] = 'Kontroluji verzi MySQL klienta:';

// $_lang['mysql_version_client_old'] = 'MODX may have issues because you are using a very old MySQL client version ([[+version]]). MODX will allow installation using this MySQL client version, but we cannot guarantee all functionality will be available or work properly when using older versions of the MySQL client libraries.';
$_lang['mysql_version_client_old'] = 'MODX nemusí správně fungovat, protože používáte starou verzi MySQL klienta verze ([[+version]]). MODX Vám umožní instalaci pomocí této staré verze MySQL klienta, ale nemůže zaručit, že budou dostupné všechny funkce a že budou pracovat správně.';

// $_lang['mysql_version_fail'] = 'You are running on MySQL [[+version]], and MODX Revolution requires MySQL 4.1.20 or later. Please upgrade MySQL to at least 4.1.20.';
$_lang['mysql_version_fail'] = 'Váš MySQL server běží na verzi [[+version]], MODX Revolution vyžaduje MySQL ve verzi 4.1.20 nebo novější. Aktualizujte alespoň na verzi 4.1.20.';

// $_lang['mysql_version_server_nf'] = 'MODX could not detect your MySQL server version via mysql_get_server_info(). Please manually make sure that your MySQL server version is at least 4.1.20 before proceeding.';
$_lang['mysql_version_server_nf'] = 'MODX se nepodařilo zjistit verzi vašeho MySQL serveru pomocí mysql_get_server_info(). Před tím než budete pokračovat se prosím ujistěte, že verze vašeho MySQL serveru je 4.1.20 nebo novější.';

// $_lang['mysql_version_server_start'] = 'Checking MySQL server version:';
$_lang['mysql_version_server_start'] = 'Kontroluji verzi MySQL serveru:';

// $_lang['mysql_version_success'] = 'OK! Running: [[+version]]';
$_lang['mysql_version_success'] = 'OK! Běží na: [[+version]]';

// $_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_success'] = 'OK!';

// $_lang['sqlsrv_version_client_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';

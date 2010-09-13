<?php
/**
 * Czech Drivers Lexicon Topic for Revolution setup
 *
 * @language cs
 * @package setup
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2010-09-11
 */
$_lang['mysql_err_ext'] = 'MODx vyžaduje MySQL extenzi pro PHP a ta se zdá být nenačtena.';
$_lang['mysql_err_pdo'] = 'MODx vyžaduje ovladač pdo_mysql pokud má být používáno nativní PDO a ten se zdá být nenačten.';
$_lang['mysql_version_5051'] = 'MODx bude mít problémy s Vaší verzí MySQL ([[+version]]), z důvodu mnoha chyb spojených s PDO ovladač v této verzi. Aktualizujte MySQL pro odstranění těchto chyb. I v případě, že nebudete používat MODx důrazně doporučujeme aktualizaci MySQL z důvodů zabezpečení a stability Vašich vlastních stránek.';
$_lang['mysql_version_client_nf'] = 'MODx se nepodařilo zjistit verzi Vašeho MySQL klienta pomocí mysql_get_client_info(). Před tím než budete pokračovat se prosím ujistěte, že verze Vašeho MySQL klienta je 4.1.20 nebo novější.';
$_lang['mysql_version_client_start'] = 'Kontroluji verzi MySQL klienta:';
$_lang['mysql_version_client_old'] = 'MODx nemusí správně fungovat, protože používáte starou verzi MySQL klienta verze ([[+version]]). MODx Vám umožní instalaci pomocí této staré verze MySQL klienta, ale nemůže zaručit, že budou dostupné všechny funkce a že budou pracovat správně.';
$_lang['mysql_version_fail'] = 'Váš MySQL server běží na verzi [[+version]], MODx Revolution vyžaduje MySQL ve verzi 4.1.20 nebo novější. Aktualizujte alespoň na verzi 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODx se nepodařilo zjistit verzi vašeho MySQL serveru pomocí mysql_get_server_info(). Před tím než budete pokračovat se prosím ujistěte, že verze vašeho MySQL serveru je 4.1.20 nebo novější.';
$_lang['mysql_version_server_start'] = 'Kontroluji verzi MySQL serveru:';
$_lang['mysql_version_success'] = 'OK! Běží na: [[+version]]';


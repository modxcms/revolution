<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX vyžaduje MySQL extenzi pro PHP a ta se zdá být nenačtena.';
$_lang['mysql_err_pdo'] = 'MODX vyžaduje ovladač pdo_mysql pokud má být používáno nativní PDO a ten se zdá být nenačten.';
$_lang['mysql_version_5051'] = 'MODX bude mít problémy s Vaší verzí MySQL ([[+version]]), z důvodu mnoha chyb spojených s PDO ovladač v této verzi. Aktualizujte MySQL pro odstranění těchto chyb. I v případě, že nebudete používat MODX důrazně doporučujeme aktualizaci MySQL z důvodů zabezpečení a stability Vašich vlastních stránek.';
$_lang['mysql_version_client_nf'] = 'MODX se nepodařilo zjistit verzi Vašeho MySQL klienta pomocí mysql_get_client_info(). Před tím než budete pokračovat se prosím ujistěte, že verze Vašeho MySQL klienta je 4.1.20 nebo novější.';
$_lang['mysql_version_client_start'] = 'Kontroluji verzi MySQL klienta:';
$_lang['mysql_version_client_old'] = 'MODX nemusí správně fungovat, protože používáte starou verzi MySQL klienta verze ([[+version]]). MODX Vám umožní instalaci pomocí této staré verze MySQL klienta, ale nemůže zaručit, že budou dostupné všechny funkce a že budou pracovat správně.';
$_lang['mysql_version_fail'] = 'Váš MySQL server běží na verzi [[+version]], MODX Revolution vyžaduje MySQL ve verzi 4.1.20 nebo novější. Aktualizujte alespoň na verzi 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX se nepodařilo zjistit verzi vašeho MySQL serveru pomocí mysql_get_server_info(). Před tím než budete pokračovat se prosím ujistěte, že verze vašeho MySQL serveru je 4.1.20 nebo novější.';
$_lang['mysql_version_server_start'] = 'Kontroluji verzi MySQL serveru:';
$_lang['mysql_version_success'] = 'OK! Běží na: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';
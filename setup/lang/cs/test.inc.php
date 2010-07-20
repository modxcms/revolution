<?php
/**
 * Test-related Czech Lexicon Topic for Revolution setup.
 *
 * @language cs
 * @package setup
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2010-07-18
 */
$_lang['test_config_file'] = 'Kontroluji <span class="mono">%s</span> zda existuje a lze do něj zapisovat: ';
$_lang['test_config_file_nw'] = 'V nových instalacích Linuxu/Unixu vytvořte prázdný soubor s názvem <span class="mono">%s.inc.php</span> v MODx jádře <span class="mono">config/</span> složky s atributy nastavenými pro zápis PHP.';
$_lang['test_db_check'] = 'Vytvoření připojení k databázi: ';
$_lang['test_db_check_conn'] = 'Zkontrolujte údaje pro připojení a zkuste to znovu.';
$_lang['test_db_failed'] = 'Nepovedlo se spojit s databází!';
$_lang['test_db_setup_create'] = 'Instalátor se pokusí vytvořit databázi.';
$_lang['test_dependencies'] = 'Kontroluji PHP pro přítomnost extenze zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Vaše PHP instalace neobsahuje instalovanou extenzi "zlib". Tato extenze je nutná pro běh MODxu. Před pokračováním musí být nainstalována.';
$_lang['test_directory_exists'] = 'Kontroluji zda existuje složka <span class="mono">%s</span>: ';
$_lang['test_directory_writable'] = 'Kontroluji zda lze do složky <span class="mono">%s</span> zapisovat: ';
$_lang['test_memory_limit'] = 'Kontroluji zda je "memory_limit" nastaven alespoň na 24MB: ';
$_lang['test_memory_limit_fail'] = 'MODx zjistil, že nastavení "memory_limit" je nižší než doporučených 24MB. MODx se pokusil zvýšit "memory_limit" na 24MB, ale to se nepovedlo. Nastavte "memory_limit" v souboru "php.ini" alespoň na 24MB.';
$_lang['test_memory_limit_success'] = 'OK! Nastaveno na %sB';
$_lang['test_mysql_version_5051'] = 'MODx bude mít problémy s Vaší verzí MySQL (%s), z důvodu mnoha chyb spojených s PDO ovladač v této verzi. Aktualizujte MySQL pro odstranění těchto chyb. I v případě, že nebudete používat MODx důrazně doporučujeme aktualizaci MySQL z důvodů zabezpečení a stability Vašich vlastních stránek.';
$_lang['test_mysql_version_client_nf'] = 'Nepodařilo se zjistit verzi MySQL klienta!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODx nemohl zjistit verzi Vašeho MySQL klienta pomocí mysql_get_client_info(). Ujistěte se prosím, že verze Vašeho MySQL klienta je alespoň 4.1.20 nebo vyšší než budete pokračovat.';
$_lang['test_mysql_version_client_old'] = 'MODx nemusí správně fungovat, protože používáte starou verzi MySQL klienta verze (%s)';
$_lang['test_mysql_version_client_old_msg'] = 'MODx Vám umožní instalaci pomocí této staré verze MySQL klienta, ale nemůže zaručit, že budou dostupné všechny funkce a že budou pracovat správně.';
$_lang['test_mysql_version_client_start'] = 'Kontroluji verzi MySQL klienta:';
$_lang['test_mysql_version_fail'] = 'Váš server běží na MySQL verze %s, MODx Revolution vyžaduje MySQL alespoň ve verzi 4.1.20 nebo vyšší. Aktualizujte alespoň na verzi 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Nepodařilo se zjistit verzi MySQL serveru!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODx nemohl zjistit verzi vašeho MySQL serveru pomocí mysql_get_server_info(). Ujistěte se prosím, že verze vašeho MySQL serveru je alespoň 4.1.20 nebo vyšší než budete pokračovat.';
$_lang['test_mysql_version_server_start'] = 'Kontroluji verzi MySQL serveru:';
$_lang['test_mysql_version_success'] = 'OK! Beží na: %s';
$_lang['test_php_version_fail'] = 'Váš server beží na PHP verze %s, MODx Revolution vyžaduje alespoň verzi PHP 4.3.0 a novější';
$_lang['test_php_version_516'] = 'MODx bude mít problémy s Vaší verzí (%s), z důvodů mnoha chyb spojených s PDO ovladači v této verzi. Aktualizujte PHP alespoň na verzi 5.3.0 nebo vyšší, které již mají odstraněné tyto chyby. MODx doporučuje aktualizaci na verzi 5.3.2+. I v případě, že nebudete používat MODx důrazně doporučujeme aktualizaci PHP z důvodů zabezpečení a stability vašich vlastních stránek.';
$_lang['test_php_version_520'] = 'MODx bude mít problémy s Vaší verzí (%s), z důvodů mnoha chyb spojených s PDO ovladači v této verzi. Aktualizujte PHP alespoň na verzi 5.3.0 nebo vyšší, které již mají odstraněné tyto chyby. MODx doporučuje aktualizaci na verzi 5.3.2+. I v případě, že nebudete používat MODx důrazně doporučujeme aktualizaci PHP z důvodů zabezpečení a stability vašich vlastních stránek.';
$_lang['test_php_version_start'] = 'Kontroluji verzi PHP:';
$_lang['test_php_version_success'] = 'OK! Běží na: %s';
$_lang['test_sessions_start'] = 'Kontroluji správnost nastavení session:';
$_lang['test_simplexml'] = 'Kontroluji SimpleXML:';
$_lang['test_simplexml_nf'] = 'Nepodařilo se nalézt SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODx nemohl nalézt SimpleXML ve vašem PHP prostředí. Správce balíčků a ostatní funkcionality bez toho nebudou fungovat. Můžete pokračovat v instalaci, ale MODx doporučuje aktivovat SimpleXML pro další fukcionality.';
$_lang['test_table_prefix'] = 'Kontroluji prefix tabulky `%s`: ';
$_lang['test_table_prefix_inuse'] = 'Tento prefix tabulky je již v dané databázi použit!';
$_lang['test_table_prefix_inuse_desc'] = 'Instalátor nemohl provést instalaci do zvolené databáze, neboť již obsahuje tabulky se zvoleným prefixem. Spusťte instalátor znovu a zadejte jiný prefix pro tabulky.';
$_lang['test_table_prefix_nf'] = 'Prefix tabulek neexistuje v této databázi!';
$_lang['test_table_prefix_nf_desc'] = 'Instalátor nemohl provést instalaci do zvolené databáze, neboť neobsahuje existující tabulky s uvedeným prefixem, které jste chtěl aktualizovat. Spusťte instalátor znovu a zadejte jiný prefix pro tabulky.';
$_lang['test_zip_memory_limit'] = 'Kontroluji zda je "memory_limit" nastaven alespoň na 24MB pro zip extenzi: ';
$_lang['test_zip_memory_limit_fail'] = 'MODx zjistil, že Vaše nastavení "memory_limit" je nižší než doporučených 24MB. MODx se pokusil zvýšit "memory_limit" na 24MB, ale to se nepovedlo. Nastavte "memory_limit" v souboru "php.ini" alespoň na 24MB nebo více, tak aby zip extenze mohla pracovat správně.';
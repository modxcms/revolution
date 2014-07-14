<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Kontroluji <span class="mono">[[+file]]</span> zda existuje a lze do něj zapisovat: ';
$_lang['test_config_file_nw'] = 'V instalacích Linux/Unix vytvořte prázdný soubor s názvem <span class="mono">[[+key]].inc.php</span> v umístění <span class="mono">./core/config/</span> s atributy nastavenými pro zápis PHP.';
$_lang['test_db_check'] = 'Vytvoření připojení k databázi: ';
$_lang['test_db_check_conn'] = 'Zkontrolujte údaje pro připojení a zkuste to znovu.';
$_lang['test_db_failed'] = 'Nepovedlo se spojit s databází!';
$_lang['test_db_setup_create'] = 'Instalátor se pokusí vytvořit databázi.';
$_lang['test_dependencies'] = 'Kontroluji PHP pro přítomnost extenze zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Vaše PHP instalace neobsahuje instalovanou extenzi "zlib". Tato extenze je nutná pro běh MODxu. Před pokračováním musí být nainstalována.';
$_lang['test_directory_exists'] = 'Kontroluji zda existuje adresář <span class="mono">[[+dir]]</span>: ';
$_lang['test_directory_writable'] = 'Kontroluji zda lze do adresáře <span class="mono">[[+dir]]</span> zapisovat: ';
$_lang['test_memory_limit'] = 'Kontroluji zda je "memory_limit" nastaven alespoň na 24M (24 MB): ';
$_lang['test_memory_limit_fail'] = 'MODX zjistil, že nastavení "memory_limit" je [[+memory]] a to je nižší než doporučených 24M. MODX se pokusil zvýšit "memory_limit" na 24M, ale to se nepovedlo. Nastavte "memory_limit" v souboru "php.ini" alespoň na 24M. Pokud máte stále problémy (např. bílá obrazovka při instalaci) zkuste nastavit 32M, 64M nebo více.';
$_lang['test_memory_limit_success'] = 'OK! Nastaveno na [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX bude mít problémy s Vaší verzí MySQL ([[+version]]), z důvodu mnoha chyb spojených s PDO ovladač v této verzi. Aktualizujte MySQL pro odstranění těchto chyb. I v případě, že nebudete používat MODX důrazně doporučujeme aktualizaci MySQL z důvodů zabezpečení a stability Vašich vlastních stránek.';
$_lang['test_mysql_version_client_nf'] = 'Nepodařilo se zjistit verzi MySQL klienta!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX nemohl zjistit verzi Vašeho MySQL klienta pomocí mysql_get_client_info(). Před tím než budete pokračovat se prosím ujistěte, že verze Vašeho MySQL klienta je 4.1.20 nebo novější.';
$_lang['test_mysql_version_client_old'] = 'MODX nemusí správně fungovat, protože používáte starou verzi MySQL klienta verze ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX Vám umožní instalaci pomocí této staré verze MySQL klienta, ale nemůže zaručit, že budou dostupné všechny funkce a že budou pracovat správně.';
$_lang['test_mysql_version_client_start'] = 'Kontroluji verzi MySQL klienta:';
$_lang['test_mysql_version_fail'] = 'Váš MySQL server běží na verzi [[+version]], MODX Revolution vyžaduje MySQL ve verzi 4.1.20 nebo novější. Aktualizujte alespoň na verzi 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Nepodařilo se zjistit verzi MySQL serveru!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX nemohl zjistit verzi vašeho MySQL serveru pomocí mysql_get_server_info(). Před tím než budete pokračovat se prosím ujistěte, že verze vašeho MySQL serveru je 4.1.20 nebo novější.';
$_lang['test_mysql_version_server_start'] = 'Kontroluji verzi MySQL serveru:';
$_lang['test_mysql_version_success'] = 'OK! Beží na: [[+version]]';
$_lang['test_nocompress'] = 'Kontrola, zda má být zakázána komprese CSS/JS: ';
$_lang['test_nocompress_disabled'] = 'Ok! Zakázáno.';
$_lang['test_nocompress_skip'] = 'Nezaškrtnuto, vynechávám.';
$_lang['test_php_version_fail'] = 'Váš server beží na PHP verze [[+version]], MODX Revolution vyžaduje verzi PHP 5.1.1 nebo novější. Prosím aktualizujte PHP aslespoň na verzi 5.1.1. MODX doporučuje aktualizaci na verzi 5.3.2 nebo novější.';
$_lang['test_php_version_516'] = 'MODX bude mít problémy s Vaší verzí ([[+version]]), z důvodů mnoha chyb spojených s PDO ovladači v této verzi. Aktualizujte PHP alespoň na verzi 5.3.0 nebo vyšší, které již mají odstraněné tyto chyby. MODX doporučuje aktualizaci na verzi 5.3.2+. I v případě, že nebudete používat MODX důrazně doporučujeme aktualizaci PHP z důvodů zabezpečení a stability vašich vlastních stránek.';
$_lang['test_php_version_520'] = 'MODX bude mít problémy s Vaší verzí ([[+version]]), z důvodů mnoha chyb spojených s PDO ovladači v této verzi. Aktualizujte PHP alespoň na verzi 5.3.0 nebo vyšší, které již mají odstraněné tyto chyby. MODX doporučuje aktualizaci na verzi 5.3.2+. I v případě, že nebudete používat MODX důrazně doporučujeme aktualizaci PHP z důvodů zabezpečení a stability vašich vlastních stránek.';
$_lang['test_php_version_start'] = 'Kontroluji verzi PHP:';
$_lang['test_php_version_success'] = 'OK! Běží na: [[+version]]';
$_lang['test_safe_mode_start'] = 'Kontrola zda je vypnutý safe_mode:';
$_lang['test_safe_mode_fail'] = 'MODX zjistil, že je safe_mode aktivní. Před pokračováním v instalaci je nutné vypnout safe_mode v konfiguraci PHP.';
$_lang['test_sessions_start'] = 'Kontroluji správnost nastavení session:';
$_lang['test_simplexml'] = 'Kontroluji SimpleXML:';
$_lang['test_simplexml_nf'] = 'Nepodařilo se nalézt SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX nemohl nalézt SimpleXML ve vašem PHP prostředí. Správce balíčků a ostatní funkcionality bez toho nebudou fungovat. Můžete pokračovat v instalaci, ale MODX doporučuje aktivovat SimpleXML pro další fukcionality.';
$_lang['test_suhosin'] = 'Kontrola problémů se suhosin:';
$_lang['test_suhosin_max_length'] = 'Hodnota suhosin GET max value je příliš nízká!';
$_lang['test_suhosin_max_length_err'] = 'Používáte PHP extenzi suhosin a její nastavení suhosin.get.max_value_length je nastaveno na příliš nízkou hodnotu při níž MODX nemůže pracovat správně s kompresí JS pro správce obsahu. MODX doporučuje zvýšit tuto hodnotu na 4096, dokud nebudu toto nastaveno MODX automaticky vypne kompresi JS (nastavení compress_js), aby předešel případným problémům.';
$_lang['test_table_prefix'] = 'Kontroluji prefix tabulky `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Tento prefix tabulky je již v dané databázi použit!';
$_lang['test_table_prefix_inuse_desc'] = 'Instalátor nemohl provést instalaci do zvolené databáze, neboť již obsahuje tabulky se zvoleným prefixem. Spusťte instalátor znovu a zadejte jiný prefix pro tabulky.';
$_lang['test_table_prefix_nf'] = 'Prefix tabulek neexistuje v této databázi!';
$_lang['test_table_prefix_nf_desc'] = 'Instalátor nemohl provést instalaci do zvolené databáze, neboť neobsahuje existující tabulky s uvedeným prefixem, které jste chtěl aktualizovat. Spusťte instalátor znovu a zadejte jiný prefix pro tabulky.';
$_lang['test_zip_memory_limit'] = 'Kontroluji zda je "memory_limit" nastaven alespoň na 24M (24 MB) pro zip extenzi: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX zjistil, že Vaše nastavení "memory_limit" je nižší než doporučených 24M. MODX se pokusil zvýšit "memory_limit" na 24M, ale to se nepovedlo. Nastavte "memory_limit" v souboru "php.ini" alespoň na 24M nebo více, tak aby zip extenze mohla pracovat správně.';
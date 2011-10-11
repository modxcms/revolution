<?php
/**
 * Czech language files for Revolution 2.1.0 setup
 *
 * @language cs
 * @package setup
 *
 * @author modxcms.cz
 * @updated 2011-03-30
 */
$_lang['additional_css'] = '';
$_lang['addons'] = 'Doplňky';
$_lang['advanced_options'] = 'Další možnosti';
$_lang['all'] = 'Vše';
$_lang['app_description'] = 'CMS a PHP aplikační Framework';
$_lang['app_motto'] = 'MODX Create and Do More with Less';
$_lang['back'] = 'Zpět';
$_lang['base_template'] = 'BaseTemplate';
$_lang['cache_manager_err'] = 'MODX Cache Manager se nepovedlo načíst.';
$_lang['choose_language'] = 'Zvolte jazyk';
$_lang['cleanup_errors_title'] = 'Důležitá poznámka:';
$_lang['cli_install_failed'] = 'Installation Failed! Errors: [[+errors]]';
$_lang['cli_no_config_file'] = 'MODX could not find a configuration file (such as config.xml) for your CLI install. To run MODX Setup from the command line, you must provide a config xml file. See the official documentation for more information.';
$_lang['cli_tests_failed'] = 'Pre-Install Tests Failed! Errors: [[+errors]]';
$_lang['close'] = 'zavřít';
$_lang['config_file_err_w'] = 'Chyba při zápisu konfiguračního souboru.';
$_lang['config_file_perms_notset'] = 'Atributy konfiguračního souboru nebyly aktualizovány. Měli by jste změnit atributy konfiguračního souboru, zabráníte tím tak neopravněné manipulaci se souborem.';
$_lang['config_file_perms_set'] = 'Atributy konfiguračního souboru úspěšně aktualizovány.';
$_lang['config_file_written'] = 'Konfigurační soubor byl úspěšně zapsán.';
$_lang['config_key'] = 'MODX konfigurační klíč';
$_lang['config_key_change'] = 'Pokud chcete změnit MODX konfigurační klíč, <a id="cck-href" href="javascript:void(0);">klikněte zde</a>.';
$_lang['config_key_override'] = 'Pokud chcete spustit instalační program s jiným konfiguračním klíčem než současným, uvedeným v "setup/includes/config.core.php", zadejte jej níže.';
$_lang['config_not_writable_err'] = 'Pokusil jste se změnit nastavení v souboru "setup/includes/config.core.php", ten je ale nasteven atributem pouze pro čtení. Změňte atributy souboru nebo upravte soubor manuálně před tím než budete pokračovat.';
$_lang['connection_character_set'] = 'Znaková sada připojení:';
$_lang['connection_collation'] = 'Porovnání:';
$_lang['connection_connection_and_login_information'] = 'Údaje pro připojení a přihlášení k databázi';
$_lang['connection_connection_note'] = 'Zadejte název databáze vytvořené pro MODX. Pokud tato databáze ještě neexistuje, instalátor se jí pokusí vytvořit. V závislosti na nastavení databáze nebo uživatelských právech se vytvoření nemusí povést.';
$_lang['connection_database_host'] = 'Databázový server:';
$_lang['connection_database_info'] = 'Now please enter the login data for your database.';
$_lang['connection_database_login'] = 'Uživatelské jméno pro databázi:';
$_lang['connection_database_name'] = 'Název databáze:';
$_lang['connection_database_pass'] = 'Heslo pro databázi:';
$_lang['connection_database_type'] = 'Typ databáze:';
$_lang['connection_default_admin_email'] = 'E-mail:';
$_lang['connection_default_admin_login'] = 'Uživatelské jméno:';
$_lang['connection_default_admin_note'] = 'Nyní je třeba, aby jste zadali údaje pro výchozí administrátorský účet. Vyplněte uživatelské jméno a heslo, které si zapamatujte. Tyto údaje budete potřebovat po dokončení instalace pro přístup do správce obsahu.';
$_lang['connection_default_admin_password'] = 'Heslo:';
$_lang['connection_default_admin_password_confirm'] = 'Ověření hesla:';
$_lang['connection_default_admin_user'] = 'Výchozí uživatel správce obsahu';
$_lang['connection_table_prefix'] = 'Prefix tabulek:';
$_lang['connection_test_connection'] = 'Ověřit připojení';
$_lang['connection_title'] = 'Nastavení připojení';
$_lang['context_connector_options'] = '<strong>Nastavení kontextu pro konektory</strong> (služby pro připojení pomocí AJAX)';
$_lang['context_connector_path'] = 'Cesta k souborům pro kontext konektorů';
$_lang['context_connector_url'] = 'URL pro kontext konektorů';
$_lang['context_installation'] = 'Možnosti kontextů';
$_lang['context_manager_options'] = '<strong>Nastavení kontextu pro správce obsahu</strong> (backend webového portálu)';
$_lang['context_manager_path'] = 'Cesta k souborům pro kontext správce obsahu';
$_lang['context_manager_url'] = 'URL pro kontext správce obsahu';
$_lang['context_override'] = 'Pokud necháte hodnoty nezměněny (nezaškrtnuté zaškrtávací políčko), budou použity automaticky zjištěné hodnoty. Zadáním vlastní hodnoty (zaškrtnutím políčka), dáváte systému najevo, že si přejete, aby byla použita zadaná cesta bez ohledu na nastavení v konfiguračním souboru.';
$_lang['context_web_options'] = '<strong>Nastavení kontextu pro web</strong> (frontend webového portálu)';
$_lang['context_web_path'] = 'Cesta k souborům pro kontext webu';
$_lang['context_web_url'] = 'URL pro kontext webu';
$_lang['continue'] = 'Pokračovat';
$_lang['dau_err_save'] = 'Nastala chyba při ukládání výchozího účtu adminitrátora.';
$_lang['dau_saved'] = 'Vytvořen výchozí účet pro administrátora.';
$_lang['db_check_db'] = 'Ověření databáze: ';
$_lang['db_connecting'] = 'Připojení k MySQL serveru:';
$_lang['db_connected'] = 'Připojení k databázi úspěšně navázáno!';
$_lang['db_created'] = 'Databáze byla úspěšně vytvořena.';
$_lang['db_err_connect'] = 'Nepodařilo se připojit k databázi.';
$_lang['db_err_connect_upgrade'] = 'Nepodařilo se připojit k existující databázi pro aktualizaci. Zkontrolujte údaje pro připojení a zkuste to znovu.';
$_lang['db_err_connect_server'] = 'Nepodařilo se připojit k databázovému serveru. Zkontrolujte údaje pro připojení a zkuste to znovu.';
$_lang['db_err_create'] = 'Nastala chyba při vytváření databáze.';
$_lang['db_err_create_database'] = 'MODX nemohl vytvořit vaši databázi. Vytvořte databázi ručně a zkuste to znovu.';
$_lang['db_err_show_charsets'] = 'MODX nemohl získat možné znakové sady pro Váš MySQL server.';
$_lang['db_err_show_collations'] = 'MODX nemohl získat možná porovnání pro Vaš MySQL server.';
$_lang['db_success'] = 'V pořádku!';
$_lang['db_test_coll_msg'] = 'Ověřit a případně vytvořit databázi.';
$_lang['db_test_conn_msg'] = 'Ověřit připojení k databázovému serveru a zobrazit porovnání.';
$_lang['default_admin_user'] = 'Výchozí účet administrátora';
$_lang['delete_setup_dir'] = 'Zde zaškrtněte pro ODSTRANĚNÍ instalačního adresáře "/setup".';
$_lang['dir'] = 'ltr';
$_lang['email_err_ns'] = 'E-mailová adresa není platná';
$_lang['err_occ'] = 'Vyskytly se chyby!';
$_lang['err_update_table'] = 'Chyba při aktualizaci tabulky pro třídu [[+class]]';
$_lang['errors_occurred'] = 'Došlo k chybám při instalaci jádra. Přečtěte si níže uvedené výsledky instalace, opravte problémy a postupujte podle pokynů.';
$_lang['failed'] = 'Chyba!';
$_lang['fatal_error'] = 'KRYTICKÁ CHYBA: MODX instalační program nemůže pokračovat.';
$_lang['home'] = 'Home';
$_lang['img_banner'] = 'assets/images/img_banner.gif';
$_lang['img_box'] = 'assets/images/img_box.png';
$_lang['img_splash'] = 'assets/images/img_splash.gif';
$_lang['install'] = 'Instalovat';
$_lang['install_packages'] = 'Instalovat balíčky';
$_lang['install_packages_desc'] = 'Můžete si vybrat individuální balíčky, které chcete nainstalovat. Jakmile nainstalujete volitelné balíčky klikněte na "Dokončit" pro dokončení instalace.';
$_lang['install_packages_options'] = 'Možnosti instalace balíčků';
$_lang['install_success'] = 'Instalace jádra proběhla úspěšně. Pro dokončení klikněte na "Další".';
$_lang['install_summary'] = 'Shrnutí instalace';
$_lang['install_update'] = 'Instalace/aktualizace';
$_lang['installation_finished'] = 'Installation finished in [[+time]]';
$_lang['license'] = '<p class="title">Před pokračováním v instalaci musíte souhlasit se zněním licence.</p>
	<p>Usage of this software is subject to the GPL license. To help you understand
	what the GPL licence is and how it affects your ability to use the software, we
	have provided the following summary:</p>
	<h4>The GNU General Public License is a Free Software license.</h4>
	<p>Like any Free Software license, it grants to you the four following freedoms:</p>
	<ul>
        <li>The freedom to run the program for any purpose. </li>
        <li>The freedom to study how the program works and adapt it to your needs. </li>
        <li>The freedom to redistribute copies so you can help your neighbor. </li>
        <li>The freedom to improve the program and release your improvements to the
        public, so that the whole community benefits. </li>
	</ul>
	<p>You may exercise the freedoms specified here provided that you comply with
	the express conditions of this license. The principal conditions are:</p>
	<ul>
        <li>You must conspicuously and appropriately publish on each copy distributed an
        appropriate copyright notice and disclaimer of warranty and keep intact all the
        notices that refer to this License and to the absence of any warranty; and give
        any other recipients of the Program a copy of the GNU General Public License
        along with the Program. Any translation of the GNU General Public License must
        be accompanied by the GNU General Public License.</li>

        <li>If you modify your copy or copies of the program or any portion of it, or
        develop a program based upon it, you may distribute the resulting work provided
        you do so under the GNU General Public License. Any translation of the GNU
        General Public License must be accompanied by the GNU General Public License. </li>

        <li>If you copy or distribute the program, you must accompany it with the
        complete corresponding machine-readable source code or with a written offer,
        valid for at least three years, to furnish the complete corresponding
        machine-readable source code.</li>

        <li>Any of these conditions can be waived if you get permission from the
        copyright holder.</li>

        <li>Your fair use and other rights are in no way affected by the above.</li>
    </ul>
	<p>The above is a summary of the GNU General Public License. By proceeding, you
	are agreeing to the GNU General Public Licence, not the above. The above is
	simply a summary of the GNU General Public Licence, and its accuracy is not
	guaranteed. It is strongly recommended you read the <a href="http://www.gnu.org/copyleft/gpl.html" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">GNU General Public
	License</a> in full before proceeding, which can also be found in the license
	file distributed with this package.</p>
';
$_lang['license_agree'] = 'Souhlasím s podmínkami stanovenými v této licenci.';
$_lang['license_agreement'] = 'Licenční smlouva';
$_lang['license_agreement_error'] = 'Musíte souhlasit s licenčními podmínkami před pokračováním v instalaci.';
$_lang['login'] = 'Přihlásit se';
$_lang['modx_class_err_nf'] = 'Nepodařilo se načíst soubor třídy MODX.';
$_lang['modx_configuration_file'] = 'MODX konfigurační soubor';
$_lang['modx_err_instantiate'] = 'Nepodařilo se inicializovat třídu MODX.';
$_lang['modx_err_instantiate_mgr'] = 'Nepodařilo se inicializovat kontext pro správce obsahu.';
$_lang['modx_footer1'] = '&copy; 2005-2011 the <a href="http://modx.com/" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;"  style="color: green; text-decoration:underline">MODX</a> Content Management Framework (CMF) projekt. Všechna práva vyhrazena. MODX je licencován pod GNU GPL.';
$_lang['modx_footer2'] = 'MODX je free software. Doporučujeme Vám být kreativní a používat MODX jak jen uznáte za vhodné. Pouze se ujistěte, že pokud uděláte nějaké změny a budete chtít upravený MODX distribuovat dál, musí být zdrojové kódy volně přístupné!';
$_lang['modx_install'] = 'Instalace MODX';
$_lang['modx_install_complete'] = 'Instalace MODX dokončena';
$_lang['modx_object_err'] = 'Objekt MODX se nepodařilo načíst.';
$_lang['next'] = 'Další';
$_lang['none'] = 'Žádný';
$_lang['ok'] = 'OK!';
$_lang['options_core_inplace'] = 'Soubory jsou připravené<br /><small>(Doporučeno pro instalaci na sdíleném serveru)</small>';
$_lang['options_core_inplace_note'] = 'Tuto volbu zaškrtněte pokud jste exportovali MODX z GIT repozitáře nebo jste jej před instalací rozbalili z archívu.';
$_lang['options_core_unpacked'] = 'Balíček jádra byl manuálně rozbalen<br /><small>(Doporučeno pro instalaci na sdíleném serveru)</small>';
$_lang['options_core_unpacked_note'] = 'Ověřte, že je rozbalen obsah souboru "core/packages/core.transport.zip". Tato volba zkrátí čas instalace na systémech s nízko nastavenými hodnotami PHP "time_limit" a Apache script execution time "KeepAliveTimeout", které nemáte možnost přenastavit.';
$_lang['options_install_new_copy'] = 'Instalace nové kopie ';
$_lang['options_install_new_note'] = 'Pozor, tato volba přepíše všechna současná data ve zvolené databázi.';
$_lang['options_important_upgrade'] = 'Důležitá poznámka pro aktualizaci';
$_lang['options_important_upgrade_note'] = 'Ujistěte se, že jsou uživatelé správce obsahu <strong>odhlášeni před aktualizací</strong>, předejdete tím problémům (např: znemožnění přístupu k dokumentům). Pokud máte problémy po aktualizaci, odhlašte všechny session, vyprázdněte cache prohlížeče a následně se znovu přihlašte.';
$_lang['options_new_file_permissions'] = 'Atributy nového souboru';
$_lang['options_new_file_permissions_note'] = 'Zde určete jaké atributy budou mít nové soubory vytvořené skrze MODX, např. 0664 nebo 0666.';
$_lang['options_new_folder_permissions'] = 'Atributy nového adresáře';
$_lang['options_new_folder_permissions_note'] = 'Zde určete jaké atributy budou mít nové adresáře vytvořené skrze MODX, např. 0775 nebo 0777.';
$_lang['options_new_installation'] = 'Nová instalace';
$_lang['options_title'] = 'Možnosti instalace';
$_lang['options_upgrade_advanced'] = 'Pokročilá aktualizace<br /><small>(úprava databázové konfigurace)</small>';
$_lang['options_upgrade_advanced_note'] = 'Pouze pro administrátory nebo při přechodu na server s jiným kódováním znaků. <strong>V této volbě je třeba znát název databáze, uživatelské jméno, heslo a detaily připojení/porovnávání.</strong>';
$_lang['options_upgrade_existing'] = 'Aktualizace existující instalace';
$_lang['options_upgrade_existing_note'] = 'Aktualizace současné verze souborů a databáze.';
$_lang['package_execute_err_retrieve'] = 'Instalace se  nezdařila, protože MODX nemohl rozbalit balíček "[[+path]]packages/core.transport.zip". Ujistěte se, že soubor "[[+path]]packages/core.transport.zip" existuje a je možné do něj zapisovat a také že je možno zapisovat do adresáře "[[+path]]packages/".';
$_lang['package_err_install'] = 'Nepodařilo se nainstalovat balíček [[+package]].';
$_lang['package_err_nf'] = 'Nepodařilo se získat balíček [[+package]] pro instalaci.';
$_lang['package_installed'] = 'Úspěšně nainstalován balíček [[+package]].';
$_lang['password_err_invchars'] = 'Heslo obsahuje nepovolené znaky jako např. /, \\, &apos;, &quot;, (, )  nebo {}.';
$_lang['password_err_nomatch'] = 'Hesla nesouhlasí';
$_lang['password_err_ns'] = 'Heslo nebylo vyplněno';
$_lang['password_err_short'] = 'Heslo musí být alespoň 6 znaků dlouhé.';
$_lang['please_select_login'] = 'Klikněte na tlačítko "Přihlásit se" pro přístup do správce obsahu.';
$_lang['preinstall_failure'] = 'Nastaly problémy. Zkontrolujte výsledek před-instalačního testu níže, opravte chyby dle pokynů a klikněte na "Zkusit znovu".';
$_lang['preinstall_success'] = 'Před-instalační kontroly proběhly úspěšně. Pro pokračování klikněte na "Instalovat".';
$_lang['refresh'] = 'Obnovit';
$_lang['request_handler_err_nf'] = 'Nepodařilo se na načíst požadovaný handler na [[+path]]. Ujistěte se, že byly na server nahráty všechny potřebné soubory.';
$_lang['restarted_msg'] = 'MODX restartoval proces instalace jako bezpečnostní opatření, protože byl instalátor nečinný po dobu delší než 15 minut.';
$_lang['retry'] = 'Znovu';
$_lang['security_notice'] = 'Bezpečnostní oznámení';
$_lang['select'] = 'Vybrat';
$_lang['settings_handler_err_nf'] = 'MODX nemohl nalézt třídu modInstallSettings v umístění: [[+path]]. Ujistěte se, že jste na server nahráli všechny soubory.';
$_lang['setup_err_remove'] = 'Nastala chyba při pokusu odebrat instalační adresář.';
$_lang['setup_err_assets'] = 'Adresář "./assets" nebyl vytvořen v umístění: [[+path]] <br />Vytvořte tento adresář ručně a nastavte jej pro zápis (pomocí atributů), aby jste mohli použít Správce balíčků nebo komponenty třetích stran.';
$_lang['setup_err_assets_comp'] = 'Adresář "./assets/components" nebyl vytvořen v umístění: [[+path]] <br />Vytvořte tento adresář ručně a nastavte jej pro zápis (pomocí atributů), aby jste mohli použít Správce balíčků nebo komponenty třetích stran.';
$_lang['setup_err_core_comp'] = 'Adresář "./core/components" nebyl vytvořen v umístění: [[+path]] <br />Vytvořte tento adresář ručně a nastavte jej pro zápis (pomocí atributů), aby jste mohli použít Správce balíčků nebo komponenty třetích stran.';
$_lang['skip_to_bottom'] = 'přejít na konec stránky';
$_lang['success'] = 'V pořádku';
$_lang['table_created'] = 'Úspěšně vytvořena tabulka pro třídu [[+class]]';
$_lang['table_err_create'] = 'Chyba při pokusu vytvořit tabulku pro třídu [[+class]]';
$_lang['table_updated'] = 'Úspěšně aktualizována tabulka pro třídu [[+class]]';
$_lang['test_class_nf'] = 'Nepodařilo se najít třídu instalačních testů v umístění: [[+path]] <br />Ujistěte se prosím, že jste nahráli všechny potřebné soubory.';
$_lang['test_version_class_nf'] = 'Nepodařilo se najít třídu instačních testů Versioner v umístění: [[+path]] <br />Ujistěte se prosím, že jste nahráli všechny potřebné soubory.';
$_lang['thank_installing'] = 'Děkujeme, že jste si vybrali ';
$_lang['transport_class_err_load'] = 'Chyba při nahrávání transportní třídy.';
$_lang['toggle'] = 'Zobrazit/skrýt';
$_lang['toggle_success'] = 'Zobrazit/skrýt zprávy';
$_lang['toggle_warnings'] = 'Zobrazit/skrýt varování';
$_lang['username_err_invchars'] = 'Uživatelské jméno nesmí obsahovat nepovolené znaky, jako např: /, \\, &apos;, &quot; nebo {}.';
$_lang['username_err_ns'] = 'Neplatné uživatelské jméno';
$_lang['version'] = 'verze';
$_lang['warning'] = 'Varování';
$_lang['welcome'] = 'Vítejte v instalačním programu systému MODX.';
$_lang['welcome_message'] = '<p>Tento program Vás provede zbytkem instalace.</p>
	<p>Pro pokračování klikněte na tlačítko "Další":</p>
';
$_lang['workspace_err_nf'] = 'Nepodařilo se nalézt aktivní workspace.';
$_lang['workspace_err_path'] = 'Chyba nastavení cesty k aktivnímu workspace.';
$_lang['workspace_path_updated'] = 'Cesta k aktivnímu workspace byla aktualizována.';
$_lang['versioner_err_nf'] = 'Nepodařilo se najít Versioner v umístění: [[+path]] <br />Ujistěte se prosím, že jste nahráli všechny potřebné soubory.';
$_lang['xpdo_err_ins'] = 'Nepodařilo se inicializovat xPDO.';
$_lang['xpdo_err_nf'] = 'MODXu se nepodařilo nalézt třídu xPDO v umístění [[+path]]. Ujistěte se, že jste ji nahráli správně.';
$_lang['preload_err_cache'] = 'Ujistěte se, že složka "[[+path]]cache" existuje a PHP do ní může zapisovat.';
$_lang['preload_err_core_path'] = 'Ujistěte se, že jste definovali platnou cestu do MODX_CORE_PATH v konfiguračním souboru "setup/includes/config.core.php" ; musí být definována cesta k funkčnímu MODX jádru.';
$_lang['preload_err_mysql'] = 'MODX vyžaduje MySQL extenzi pokud je používáno PHP bez nativní podpory PDO a ta se zdá nebyla načtena.';
$_lang['preload_err_pdo'] = 'MODX vyžaduje PDO extenzi pokud má být používáno nativní PDO a ta se zdá nebyla načtena.';
$_lang['preload_err_pdo_mysql'] = 'MODX vyžaduje ovladač pdo_mysql pokud má být používáno nativní PDO a ten se zdá být nenačten.';
$_lang['test_config_file'] = 'Kontroluji zda <span class="mono">[[+file]]</span> existuje a lze do něj zapisovat: ';
$_lang['test_config_file_nw'] = 'V instalacích na Linux/Unix vytvořte prázdný soubor s názvem <span class="mono">[[+file]].inc.php</span> v umístění <span class="mono">./core/config/</span> s atributy nastavenými pro zápis PHP.';
$_lang['test_db_check'] = 'Vytvoření připojení k databázi: ';
$_lang['test_db_check_conn'] = 'Zkontrolujte údaje pro připojení a zkuste to znovu.';
$_lang['test_db_failed'] = 'Nepovedlo se spojit s databází!';
$_lang['test_db_setup_create'] = 'Instalátor se pokusí vytvořit databázi.';
$_lang['test_dependencies'] = 'Kontroluji PHP na přítomnost extenze zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Vaše PHP instalace neobsahuje instalovanou extenzi "zlib". Tato extenze je nutná pro běh MODXu. Před pokračováním musí být nainstalována.';
$_lang['test_directory_exists'] = 'Kontroluji zda existuje adresář <span class="mono">[[+dir]]</span>: ';
$_lang['test_directory_writable'] = 'Kontroluji zda lze do adresáře <span class="mono">[[+dir]]</span> zapisovat: ';
$_lang['test_memory_limit'] = 'Kontroluji zda je "memory_limit" nastaven alespoň na 24M (24 MB): ';
$_lang['test_memory_limit_fail'] = 'MODX zjistil, že nastavení "memory_limit" je nižší než doporučených 24M. MODX se pokusil zvýšit "memory_limit" na 24M, ale to se nepovedlo. Nastavte "memory_limit" v souboru "php.ini" alespoň na 24M.';
$_lang['test_php_version_fail'] = 'Váš server beží na PHP verze [[+version]], MODX Revolution vyžaduje verzi PHP 4.3.0 nebo novější';
$_lang['test_php_version_sn'] = 'Dokuď bude běžet MODX na verzi PHP ([[+version]]), nedoporučujeme Vám MODX v této verzi používat. Vaše verze PHP je zranitelná mnoha bezpečnostními dírami. Aktualizujte PHP na verzi 4.3.11 nebo novější, které obsahují záplaty těchto děr. Dopuručujeme Vám aktualizovat na tuto verzi pro zvýšení bezpečnosti Vašich vlastních webových stránek.';
$_lang['test_php_version_start'] = 'Kontroluji verzi PHP:';
$_lang['test_sessions_start'] = 'Kontroluji správnost nastavení session:';
$_lang['test_table_prefix'] = 'Kontroluji prefix tabulky `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Tento prefix tabulky je již v dané databázi použit!';
$_lang['test_table_prefix_inuse_desc'] = 'Instálator nemohl nainstalovat MODX do zvolené databáze, neboť ta již obsahuje tabulky se zvoleným prefixem. Zadejte nový prefix tabulek ("table_prefix") a spusťte instalátor znovu.';
$_lang['test_table_prefix_nf'] = 'Zvolený prefix tabulek v této databázi neexistuje!';
$_lang['test_table_prefix_nf_desc'] = 'Instalátor nemohl nainstalovat MODX do zvolené databáze, neboť ta neobsahuje existující tabulky s prefixem tabulek, které jste uvedl a mají být aktualizovány. Zadejte nový prefix tabulek ("table_prefix") a spusťte instalátor znovu.';
$_lang['test_zip_memory_limit'] = 'Kontroluji zda je "memory_limit" nastaven alespoň na 24M (24 MB) pro zip extenzi: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX zjistil, že Vaše nastavení "memory_limit" je nižší než doporučených 24M. MODX se pokusil zvýšit "memory_limit" na 24M, ale to se nepovedlo. Nastavte "memory_limit" v souboru "php.ini" alespoň na 24M nebo více, tak aby zip extenze mohla pracovat správně.';
<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Oblast';
$_lang['area_authentication'] = 'Autentizaci a zabezpečení';
$_lang['area_caching'] = 'Cachování';
$_lang['area_core'] = 'Jádro MODX';
$_lang['area_editor'] = 'WYSIWYG editor';
$_lang['area_file'] = 'Souborový systém';
$_lang['area_filter'] = 'Filtrovat dle oblasti...';
$_lang['area_furls'] = 'Přátelská URL';
$_lang['area_gateway'] = 'Brána';
$_lang['area_language'] = 'Jazyk a slovník';
$_lang['area_mail'] = 'Pošta';
$_lang['area_manager'] = 'Správce obsahu';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session a Cookie';
$_lang['area_static_elements'] = 'Static Elements';
$_lang['area_static_resources'] = 'Static Resources';
$_lang['area_lexicon_string'] = 'Oblast záznamu slovníku';
$_lang['area_lexicon_string_msg'] = 'Zadejte klíč záznamu slovníku pro tuto oblast. Pokud ve slovníku záznam není, zobrazí se pouze klíč oblasti.<br />Oblasti jádra: authentication, caching, file, furls, gateway, language, manager, session, site, system';
$_lang['area_site'] = 'Portál';
$_lang['area_system'] = 'Systém a server';
$_lang['areas'] = 'Oblasti';
$_lang['charset'] = 'Znaková sada';
$_lang['country'] = 'Země';
$_lang['description_desc'] = 'Popis položky konfigurace. Můžete zadat také klíč slovníku.';
$_lang['key_desc'] = 'Klíč položky nastavení. Položka nastavení bude dostupná v obsahu jako placeholder [[++key]].';
$_lang['name_desc'] = 'Název položky nastavení. Můžete zadat také klíč slovníku.';
$_lang['namespace'] = 'Jmenný prostor';
$_lang['namespace_desc'] = 'Jmenný prostor, ke kterému tato položka nastavení patří. Výchozí téma slovníku bude načteno pro tento jmenný prostor při dotazu na nastavení.';
$_lang['namespace_filter'] = 'Filtrovat dle jmenného prostoru...';
$_lang['search_by_key'] = 'Hledat dle klíče...';
$_lang['setting_create'] = 'Vytvořit novou položku konfigurace';
$_lang['setting_err'] = 'Zkontrolujte údaje v těchto políčkách: ';
$_lang['setting_err_ae'] = 'Položka konfigurace s tímto klíčem již existuje. Zadejte jiný název klíče.';
$_lang['setting_err_nf'] = 'Položka konfigurace nenalezena.';
$_lang['setting_err_ns'] = 'Nespecifikována položka konfigurace';
$_lang['setting_err_remove'] = 'Nastala chyba při ostraňování položky konfigurace.';
$_lang['setting_err_save'] = 'Nastala chyba při ukládání položky konfigurace.';
$_lang['setting_err_startint'] = 'Položka konfigurace nesmí začínat číslem.';
$_lang['setting_err_invalid_document'] = 'Dokument s ID %d neexistuje. Zadejte existující dokument.';
$_lang['setting_remove'] = 'Odstranit položku';
$_lang['setting_remove_confirm'] = 'Opravdu chcete odstranit tuto položku konfigurace? Mohlo by dojít k narušení správné funkčnosti správce obsahu.';
$_lang['setting_update'] = 'Upravit položku';
$_lang['settings_after_install'] = 'Protože se jedná o novou instalaci, je třeba aby jste zkontroloval tyto položky konfigurace a změnil všechny, které je třeba. Poté co vše zkontrolujete klikněte na "Uložit" a tím dojde k aktualizaci databáze.<br /><br />';
$_lang['settings_desc'] = 'Here you can set general preferences and configuration settings for the MODX manager interface, as well as how your MODX site runs. <b>Each setting will be available via the [[++key]] placeholder.</b><br />Double-click on the value column for the setting you\'d like to edit to dynamically edit via the grid, or right-click on a setting for more options. You can also click the "+" sign for a description of the setting.';
$_lang['settings_furls'] = 'Přátelská URL';
$_lang['settings_misc'] = 'Smíšené';
$_lang['settings_site'] = 'Portál';
$_lang['settings_ui'] = 'Rozhranní &amp; Funkce';
$_lang['settings_users'] = 'Uživatel';
$_lang['system_settings'] = 'Konfigurace systému';
$_lang['usergroup'] = 'Uživatelská skupina';

// user settings
$_lang['setting_access_category_enabled'] = 'Řízení přístupů ke kategoriím';
$_lang['setting_access_category_enabled_desc'] = 'Použijte pro povolení / zamezení kontroly přístupů ke kategoriím v rámci daného kontextu. <strong>POZNÁMKA: Je-li tato možnost nastavena na Ne, pak jsou ignorována práva pro přístup ke kategoriím!</strong>';

$_lang['setting_access_context_enabled'] = 'Řízení přístupů ke kontextům';
$_lang['setting_access_context_enabled_desc'] = 'Použijte pro povolení / zamezení kontroly přístupů ke kontextům. <strong>POZNÁMKA: Je-li tato možnost nastavena na Ne, pak jsou ignorována práva pro přístup ke kontextům! NEVYPÍNEJTE TUTO VOLBU v rámci celého systému nebo pro kontext "mgr", došlo by tím k zamezní přístupu do správce obsahu.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Řízení přístupů ke skupinám dokumentů';
$_lang['setting_access_resource_group_enabled_desc'] = 'Použijte pro povolení / zamezení kontroly přístupů ke skupinám dokumentů v rámci daného kontextu. <strong>POZNÁMKA: Je-li tato možnost nastavena na Ne, pak jsou ignorována práva pro přístup ke skupinám dokumentů!!</strong>';

$_lang['setting_allow_mgr_access'] = 'Přístup do správce obsahu';
$_lang['setting_allow_mgr_access_desc'] = 'Toto vyberte pokud si přejete povolit přístup do správce obsahu. <strong>Poznámka: Pokud je tato možnost nastavena na hodnotu "Ne" bude uživatel přesměrován na stránku přihlášení do správce obsahu nebo na úvodní stránku portálu.</strong>';

$_lang['setting_failed_login'] = 'Počet neúspěšných přihlášení';
$_lang['setting_failed_login_desc'] = 'Počet neúspěšných přihlášení, kterých může uživatel dosáhnout předtím než bude jeho účet zablokován.';

$_lang['setting_login_allowed_days'] = 'Povolené dny';
$_lang['setting_login_allowed_days_desc'] = 'Vyberte dny, ve kterých se tento uživatel může přihlašet do správce obsahu.';

$_lang['setting_login_allowed_ip'] = 'Povolené IP adresy';
$_lang['setting_login_allowed_ip_desc'] = 'Zadejte IP adresy, ze kterých bude uživateli umožněno přihlásit se. <strong>Poznámka: Více IP adres je možno oddělit čárkou.</strong>';

$_lang['setting_login_homepage'] = 'Úvodní stránka po přihlášení';
$_lang['setting_login_homepage_desc'] = 'Zadejte ID dokumentu, do kterého chcete uživatele přesměrovat po té co se přihlásí do správce obsahu. <strong>Poznámka: ujistěte se, že dokument s tímto ID existuje, že je publikován a přístupný tomuto uživateli!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Verze schématu přístupové politiky';
$_lang['setting_access_policies_version_desc'] = 'Verze systému přístupové politiky. NEMĚŇTE.';

$_lang['setting_allow_forward_across_contexts'] = 'Povolit přesměrování mezi kontexty';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Tímto lze povolit, aby symbolický odkaz nebo API metoda modX::sendForward() mohla přesměrovat na dokument z jiného kontextu.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Zobrazit možnost "Zapomněli jste?" pro reset hesla na přihlašovací obrazovce správce obsahu';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Nastavení na "Ne", znemožníte možnost nechat si zaslat zapomenuté hesla na přihlašovací obrazovce správce obsahu.';

$_lang['setting_allow_tags_in_post'] = 'Povolit tagy v POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Je-li nastaveno "Ne", z obsahu POST proměnných v rámci správce obsahu budou odstraněny všechny HTML tagy, číselné entity a MODX tagy. Doporučujeme nechat tuto hodnotu na "Ne" pro jiné kontexty než "mgr", kde je ve výchozím stavu povolen.';

$_lang['setting_allow_tv_eval'] = 'Enable eval in TV bindings';
$_lang['setting_allow_tv_eval_desc'] = 'Select this option to enable or disable eval in TV bindings. If this option is set to no, the code/value will just be handled as regular text.';

$_lang['setting_anonymous_sessions'] = 'Anynomní připojení';
$_lang['setting_anonymous_sessions_desc'] = 'Pokud není povoleno, pouze přihlášení uživatelé budou mít přístup do PHP session. To může snížit zátěž kterou způsobují anonymní uživatele MODX webu pokud nepotřebují přístup k unikátní session. Pokud je session_enabled vypnuté (false), toto nastavení nemá vliv a sessions nebudou dostupné.';

$_lang['setting_archive_with'] = 'Používat PCLZip archivaci';
$_lang['setting_archive_with_desc'] = 'Pokud Ano, PCLZip bude používán namísto ZipArchive pro soubory zip. Tuto volbu povolte pokud se Vám zobrazují chyby extractTo nebo máte problémy s rozbalováním ve Správě balíčků.';

$_lang['setting_auto_menuindex'] = 'Automatický menu index';
$_lang['setting_auto_menuindex_desc'] = 'Zvolte "Ano" pro zapnutí automatického indexování položek v menu. (Slouží např. pro řazení položek ve stromu dokumentů.)';

$_lang['setting_auto_check_pkg_updates'] = 'Automatická kontrola aktualizací balíčků';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Je-li nastaveno "Ano", MODX bude automaticky kontrolovat aktualizace balíčků. Toto nastavení může zpomalit načítání.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Cache pro další automatickou kontrolu aktualizací balíčku';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Počet minut, po které bude správce balíčků udržovat výsledky aktualizací balíčku v cache.';

$_lang['setting_allow_multiple_emails'] = 'Povolit vícenásobné použití e-mailu pro uživatele';
$_lang['setting_allow_multiple_emails_desc'] = 'Je-li nastaveno "Ano", uživatelé mohou sdílet stejnou e-mailovou adresu.';

$_lang['setting_automatic_alias'] = 'Automaticky generovat aliasy';
$_lang['setting_automatic_alias_desc'] = 'Zvolte "Ano", pokud má MODX automaticky generovat aliasy z titulků dokumentů při ukládání.';

$_lang['setting_automatic_template_assignment'] = 'Automatické přiřazení šablony';
$_lang['setting_automatic_template_assignment_desc'] = 'Vyberte si, jak jsou šablony přiřazeny k nově vytvořeným dokumentům. Možnosti zahrnují: systém (výchozí šablonu z nastavení systému), rodič (dědí šablonu z nadřazeného dokumentu) nebo sourozenci (dědí nejčastěji používané šablony na stejné úrovni)';

$_lang['setting_base_help_url'] = 'Základní URL nápovědy';
$_lang['setting_base_help_url_desc'] = 'Základní URL pro odkazy Nápovědy v pravém horním rohu správce obsahu.';

$_lang['setting_blocked_minutes'] = 'Doba blokování uživatele';
$_lang['setting_blocked_minutes_desc'] = 'Počet minut, po které bude uživatel blokován, pokud překročí maximální počet pokusů pro přihlášení. Zadávejte pouze čísla (žádné čárky, mezery atd.)';

$_lang['setting_cache_action_map'] = 'Povolit cache mapy akcí';
$_lang['setting_cache_action_map_desc'] = 'Je-li nastaveno "Ano", akce (nebo kontrolní mapy) budou ukládány do cache a tím se zkrátí doba načítání správce obsahu.';

$_lang['setting_cache_alias_map'] = 'Povolit cache mapy aliasů v rámci kontextu';
$_lang['setting_cache_alias_map_desc'] = 'Je-li nastaveno "Ano", URI všech dokumentů jsou ukládány do cache kontextu. Pro lepší výkon toto povolte na menších a zakažte na rozsáhlejších portálech.';

$_lang['setting_use_context_resource_table'] = 'Use the context resource table for context cache refreshes';
$_lang['setting_use_context_resource_table_desc'] = 'When enabled, context cache refreshes use the context_resource table. This enables you to programmatically have one resource in multiple contexts. If you do not use those multiple resource contexts via the API, you can set this to false. On large sites you will get a potential performance boost in the manager then.';

$_lang['setting_cache_context_settings'] = 'Povolit cache nastavení kontextů';
$_lang['setting_cache_context_settings_desc'] = 'Je-li nastaveno "Ano", kontextová nastavení budou ukládána do cache a tím se zkrátí doba načítání.';

$_lang['setting_cache_db'] = 'Povolit cache databáze';
$_lang['setting_cache_db_desc'] = 'Je-li nastaveno "Ano", objekty a přímé SQL dotazy budou ukládány do cache a tím se sníží zátěž databáze.';

$_lang['setting_cache_db_expires'] = 'Expirace databázové cache';
$_lang['setting_cache_db_expires_desc'] = 'Doba (v sekundách), po kterou bude zachovávána databázová cache.';

$_lang['setting_cache_db_session'] = 'Povolit Database Session Cache';
$_lang['setting_cache_db_session_desc'] = 'Je-li povoleno a cache_db je zaptun, databázové sessions budou ukládány v cache pro DB result-set cache.';

$_lang['setting_cache_db_session_lifetime'] = 'Čas expirace pro DB Session Cache';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Hodnota (v sekundách) určující množství poslední session pro záznamy session v DB result-set cache.';

$_lang['setting_cache_default'] = 'Používat cache dokumentů';
$_lang['setting_cache_default_desc'] = 'Zvolte "Ano" pokud chcete, aby byly všechny dokumenty ve výchozím stavu ukládány do cache.';
$_lang['setting_cache_default_err'] = 'Zvolte jestli chcete nebo nechcete cachovat dokumenty.';

$_lang['setting_cache_expires'] = 'Obecná expirace cache';
$_lang['setting_cache_expires_desc'] = 'Doba (v sekundách), po kterou bude zachovávána cache.';

$_lang['setting_cache_resource_clear_partial'] = 'Clear Partial Resource Cache for provided contexts';
$_lang['setting_cache_resource_clear_partial_desc'] = 'Pokud je povoleno, aktualizace MODX vyprázdní pouze mezipaměť pro zadaný kontext.';

$_lang['setting_cache_format'] = 'Formát dat pro uchování cache';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serializace. Vyberte jeden z těchto formátů';

$_lang['setting_cache_handler'] = 'Název třídy správce cache';
$_lang['setting_cache_handler_desc'] = 'Název třídy, která se bude používat při ukládání do cache.';

$_lang['setting_cache_lang_js'] = 'Ukládat JS řetězce slovníků do cache';
$_lang['setting_cache_lang_js_desc'] = 'Je-li nastaveno "Ano", tak bude cache používat serverové hlavičky pro slovníkové JS řetězce, které se nacházejí ve správci obsahu.';

$_lang['setting_cache_lexicon_topics'] = 'Ukládat témata slovníku do cache';
$_lang['setting_cache_lexicon_topics_desc'] = 'Je-li toto nastavení aktivní, všechny témata slovníku budou ukládána do cache. Toto nastavení značně zrychlí načítání stránek správce obsahu při použití internacionalizace. Doporučujeme ponechat hodnotu na "Ano".';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Ukládat ostatní témata slovníku do cache';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Je-li toto nastavení aktivní, témata slovníku, které nejsou součástí jádra budou také ukládána do cache. Toto nastavení je vhodné deaktivovat při vytváření svých vlastních Extras.';

$_lang['setting_cache_resource'] = 'Povolit ukládání částí dokumentů do cache';
$_lang['setting_cache_resource_desc'] = 'Částečné ukládání dokumentu do cache lze nastavit pokud je tato volba aktivní.';

$_lang['setting_cache_resource_expires'] = 'Doba expirace ukládání částí dokumentů do cache';
$_lang['setting_cache_resource_expires_desc'] = 'Tato hodnota v sekundách určuje dobu, po kterou budou soubory zachovány v cache.';

$_lang['setting_cache_scripts'] = 'Povolit cache pro skripty';
$_lang['setting_cache_scripts_desc'] = 'Je-li aktivní, MODX bude do cache ukládat všechny skripty (snippety a pluginy) do souboru pro snížení času potřebného pro načítání stránek. Doporučujeme ponechat tuto hodnotu nastavenou na "Ano".';

$_lang['setting_cache_system_settings'] = 'Povolit cache pro konfiguraci systému';
$_lang['setting_cache_system_settings_desc'] = 'Je-li aktivováno, konfigurace systému bude ukládána do cache, tím dojde ke snížení času potřebného pro načítání stránek. Doporučujeme ponechat tuto hodnotu nastavenou na "Ano".';

$_lang['setting_clear_cache_refresh_trees'] = 'Obnovit stromy při vyprázdnění cache';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Je-li tato volba aktivní, budou strom dokumentů, elementů a souborů znovunačteny při vyprázdnění cache.';

$_lang['setting_compress_css'] = 'Používat komprimované CSS';
$_lang['setting_compress_css_desc'] = 'Je-li toto nastavení aktivní, MODX bude používat komprimované verze CSS ve správci obsahu. Toto nastavení značně urychluje běh správce obsahu. Deaktivujte pouze v případě, kdy upravujete elementy jádra.';

$_lang['setting_compress_js'] = 'Používat komprimované javaskriptové knihovny';
$_lang['setting_compress_js_desc'] = 'Je-li toto nastavení aktivní, MODX bude používat komprimované verze javaskriptových knihoven ve správci obsahu. Toto nastavení značně urychluje běh správce obsahu. Deaktivujte pouze v případě, kdy upravujete elementy jádra.';

$_lang['setting_compress_js_groups'] = 'Použít seskupování při kompresi javaskriptů';
$_lang['setting_compress_js_groups_desc'] = 'Seskupit javasckripty jádra MODX správce obsahu pomocí minifikačního groupsConfig. Nastavte na Ano používáteli suhosin nebo jiný limitující faktor.';

$_lang['setting_compress_js_max_files'] = 'Maximální práh komprese javaskriptových souborů';
$_lang['setting_compress_js_max_files_desc'] = 'Maximální počet javaskriptových souborů, které se pokusí MODX zkomprimovat najednou pokud je aktivní compress_js. Nastavte na nižší číslo pokud máte problémy s Google Minifikací ve správci obsahu.';

$_lang['setting_concat_js'] = 'Používat minimalizované javaskriptové knihovny';
$_lang['setting_concat_js_desc'] = 'Je-li toto nastavení aktivní, MODX bude používat minimalizované verze javaskriptových knihoven ve správci obsahu. Toto nastavení značně urychluje běh správce obsahu. Deaktivujte pouze v případě, kdy upravujete elementy jádra.';

$_lang['setting_confirm_navigation'] = 'Potvrdit změnu stránky při neuložených změnách';
$_lang['setting_confirm_navigation_desc'] = 'Když je tato možnost aktivní, bude uživatel vyzván k potvrzení v případě, že chce opustit stránku obsahující neuložené změny.';

$_lang['setting_container_suffix'] = 'Přípona složek dokumentů';
$_lang['setting_container_suffix_desc'] = 'Přípona, která bude přidána složce dokumentů pokud se používají přátelská URL.';

$_lang['setting_context_tree_sort'] = 'Povolit řazení kontextů ve stromu dokumentů';
$_lang['setting_context_tree_sort_desc'] = 'Je-li nastaveno na Ano, kontexty se budou automaticky abecedně řadit v levém stromovém menu dokumentů.';
$_lang['setting_context_tree_sortby'] = 'Pole pro řazení kontextů ve stromu dokumentů';
$_lang['setting_context_tree_sortby_desc'] = 'Políčko podle, kterého se mají řadit kontexty ve stromu dokumentů, je-li řazení aktivní.';
$_lang['setting_context_tree_sortdir'] = 'Směr řazení kontextů ve stromu dokumentů';
$_lang['setting_context_tree_sortdir_desc'] = 'Směr řazení kontextů ve stromu dokumentů, je-li řazení aktivní.';

$_lang['setting_cultureKey'] = 'Jazyk';
$_lang['setting_cultureKey_desc'] = 'Vyberte jazyk, pro všechny kontexty (kromě správce obsahu "mgr") včetně kontextu "web".';

$_lang['setting_date_timezone'] = 'Výchozí časové pásmo';
$_lang['setting_date_timezone_desc'] = 'Určuje výchozí nastavení časového pásma pro PHP funkce pro práci s datumem, pokud je uvedena hodnota. Není-li uvedena hodnota a nastavení PHP date.timezone ini  nastavení není nastaveno pro Vaše prostředí, bude použito pásmo UTC.';

$_lang['setting_debug'] = 'Debug';
$_lang['setting_debug_desc'] = 'Řízení režimu ladění v rámci MODX zapnuto/vypnuto a/nebo nastavení PHP úrovně error_reporting. \'\' = současné nastavení systému, \'0\' = vypnuto (error_reporting = 0), \'1\' = zapnuto (error_reporting = -1), nebo lze použít jakoukoli jinou platnou hodnotu pro error_reporting.';

$_lang['setting_default_content_type'] = 'Výchozí typ obsahu';
$_lang['setting_default_content_type_desc'] = 'Vyberte výchozí typ obsahu, který bude nastavován pro nové dokumenty. Stále budete mít možnost vybrat jiný typ obsahu v editoru dokumentu, toto nastavení zajistí pouze předvýběr jedné možnosti.';

$_lang['setting_default_duplicate_publish_option'] = 'Výchozí nastavení publikace při kopírování';
$_lang['setting_default_duplicate_publish_option_desc'] = 'Výchozí nastavení možnosti publikování při kopírování dokumentu. Může být buď "unpublish" pro ukončení publikování všech kopií, "publish" pro publikování všech kopií nebo "preserve" pro zachování nastavení pro jednotlivé kopírované dokumenty.';

$_lang['setting_default_media_source'] = 'Výchozí zdroj médií';
$_lang['setting_default_media_source_desc'] = 'Výchozí zdroj médií, který se má načíst.';

$_lang['setting_default_media_source_type'] = 'Default Media Source Type';
$_lang['setting_default_media_source_type_desc'] = 'The default selected Media Source Type when creating a new Media Source.';

$_lang['setting_default_template'] = 'Výchozí šablona';
$_lang['setting_default_template_desc'] = 'Vyberte výchozí šablonu, která bude použita pro nové dokumenty. Stále budete mít možnost při úpravě dokumentu vybrat ostatní šablony, toto nastavení je pouze před-výběrem jedné z šablon.';

$_lang['setting_default_per_page'] = 'Počet výsledků na stránce';
$_lang['setting_default_per_page_desc'] = 'Výchozí počet zobrazených výsledků na stránce v rámci celého správce obsahu.';

$_lang['setting_editor_css_path'] = 'Cesta k CSS souboru';
$_lang['setting_editor_css_path_desc'] = 'Zadejte cestu k CSS souboru, který chcete použít v rámci WYSIWYG editoru. Nejlepší je zadat cestu od kořene portálu, například: /assets/site/style.css. Pokud nechcete používat ve WYSIWYG editoru CSS styly ponechte toto políčko prázdné.';

$_lang['setting_editor_css_selectors'] = 'CSS selektory pro editor';
$_lang['setting_editor_css_selectors_desc'] = 'Čárkou oddělený seznam CSS selektorů pro WYSIWYG editor.';

$_lang['setting_emailsender'] = 'Adresa odesílatele registračního e-mailu';
$_lang['setting_emailsender_desc'] = 'Zadejte e-mailovou adresu, která se zobrazí jako odesílatel v e-mailu při odeslání uživatelských údajů po registraci.';
$_lang['setting_emailsender_err'] = 'Zadejte e-mail.';

$_lang['setting_emailsubject'] = 'Předmět registračního e-mailu';
$_lang['setting_emailsubject_desc'] = 'Předmět e-mailu, který je poslán uživateli po jeho registraci.';
$_lang['setting_emailsubject_err'] = 'Zadejte text předmětu e-mailu.';

$_lang['setting_enable_dragdrop'] = 'Povolit přetahování ve stromu dokumentů a elementů';
$_lang['setting_enable_dragdrop_desc'] = 'Je-li nastavení neaktivní, není možno upravovat dokumenty/elementy přetažením v rámci stromu.';

$_lang['setting_error_page'] = 'Chybová stránka';
$_lang['setting_error_page_desc'] = 'Zadejte ID dokumentu, na který chcete přesměrovat uživatele, kteří se pokusili přistoupit na stránku, která neexistuje. <strong>Poznámka: ujistěte se, že ID patří existujícímu dokumentu a že je publikován!</strong>';
$_lang['setting_error_page_err'] = 'Zadejte ID dokumentu, který bude sloužit jako chybová stránka.';

$_lang['setting_ext_debug'] = 'ExtJS debug';
$_lang['setting_ext_debug_desc'] = 'Pokud chcete načíst ext-all-debug.js pro jednodušší ladění ExtJS kódu.';

$_lang['setting_extension_packages'] = 'Rozšíření balíčky';
$_lang['setting_extension_packages_desc'] = 'Čárkou oddělený seznam balíčků, které se mají nahrát při vytvoření nové instance MODX. Zadávejte ve formátu: nazev_balicku:cesta_k_modelu';

$_lang['setting_enable_gravatar'] = 'Povolit Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'Je-li povoleno, jako profilový obrázek se použije obrázek ze služby Gravatar (pokud uživatel nenahrál vlastní fotografii v rámci MODX).';

$_lang['setting_failed_login_attempts'] = 'Počet neúspěšných přihlášení';
$_lang['setting_failed_login_attempts_desc'] = 'Počet neúspěšných pokusů o přihlášení předtím než bude uživatel zablokován.';

$_lang['setting_fe_editor_lang'] = 'Jazyk frontend editoru';
$_lang['setting_fe_editor_lang_desc'] = 'Vyberte jazyk použitý v editoru na frontendu, pokud je použit.';

$_lang['setting_feed_modx_news'] = 'MODX RSS URL novinek';
$_lang['setting_feed_modx_news_desc'] = 'Zadejte URL pro RSS feed nesoucí novinky.';

$_lang['setting_feed_modx_news_enabled'] = 'MODX RSS novinky';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Je-li nastaveno na "Ne", MODX nebude zobrazovat novinky na úvodní stránce správce obsahu.';

$_lang['setting_feed_modx_security'] = 'MODX RSS URL bezpečnostních oznámení';
$_lang['setting_feed_modx_security_desc'] = 'Zadejte URL pro RSS feed nesoucí bezpečnostní oznámení.';

$_lang['setting_feed_modx_security_enabled'] = 'MODX RSS bezpečnostní oznámení';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Je-li nastaveno na "Ne", MODX nebude zobrazovat bezpečnostní oznámení na úvodní stránce správce obsahu.';

$_lang['setting_filemanager_path'] = 'Cesta pro správce souborů (Deprecated)';
$_lang['setting_filemanager_path_desc'] = 'Deprecated - Používejte Zdroje médií. IIS často nemá správně nastavenou proměnnou "document_root", která je používána správcem souborů, s čím může pracovat. Máte-li problémy s používáním správce souborů, ujistěte se, že tato cesta je nastavena do kořene MODX instalace.';

$_lang['setting_filemanager_path_relative'] = 'Relativní cesta pro správce souborů? (Deprecated)';
$_lang['setting_filemanager_path_relative_desc'] = 'Deprecated - Používejte Zdroje médií. Je-li cesta nastavená ve filemanager_path relativní vůči MODX base_path, nastavte tuto volbu na Ano, pokud je cesta ve filemanager_path mimo docroot nastavte Ne.';

$_lang['setting_filemanager_url'] = 'URL pro správce souborů (Deprecated)';
$_lang['setting_filemanager_url_desc'] = 'Deprecated - Používejte Zdroje médií. Volitelné. Tuto volbu použijte pokud chcete nastavit explicitní URL pro přístup k souborům v rámci správce souborů (užitečné v případě, že jste změnili filemanager_path na cestu mimo MODX webroot). Ujistěte se, že je tato URL přístupná z webu. Pokud tuto volbu ponecháte prázdnou, MODX se pokusí automaticky tuto URL doplnit.';

$_lang['setting_filemanager_url_relative'] = 'Relativní URL pro správce souborů? (Deprecated)';
$_lang['setting_filemanager_url_relative_desc'] = 'Deprecated - Používejte Zdroje médií. Je-li URL nastavená v filemanager_url relativní vůči MODX base_url, nastavte tuto volbu na Ano. Je-li URL nastavená ve filemanager_url mimo webroot nastavte Ne.';

$_lang['setting_forgot_login_email'] = 'E-mail zapomenutého přihlášení';
$_lang['setting_forgot_login_email_desc'] = 'Šablona e-mailu, který je odeslán pokud uživatel zapomněl své přihlašovací údaje.';

$_lang['setting_form_customization_use_all_groups'] = 'Pro přizpůsobení formulářů využit pravidla všech členských uživatelských skupiny';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Je-li nastaveno Ano, při aplikaci pravidel z přizpůsobení formulářů budou použita pravidla všech uživatelských skupin, do kterých je uživatel přiřazen. V opačném případě jsou použita pouze pravidla přiřazená k uživatelově primární skupině. Poznámka: volba Ano může zapříčinit chyby s konfliktními pravidly v jednotlivých uživatelských skupinách.';

$_lang['setting_forward_merge_excludes'] = 'Potlačená políčka při symbolickém odkazování';
$_lang['setting_forward_merge_excludes_desc'] = 'Při symbolickém odkazování dochází ke sloučení hodnot políček ze symbolického a cílového dokumentu. Zapsaním názvu políček oddělených čárkou dojde k potlačení přepisu políček z hodnot symbolického dokumentu.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Aliasy malými písmeny';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Určuje zda se mají používat pouze malá písmena v aliasech dokumentů.';

$_lang['setting_friendly_alias_max_length'] = 'Maximální délka aliasu';
$_lang['setting_friendly_alias_max_length_desc'] = 'Je-li hodnota větší než 0, maximální délku aliasu dokumentu bude omezena na tuto hodnotu. Nula pro neomezenou délku.';

$_lang['setting_friendly_alias_realtime'] = 'Generovat FURL alias v reálném čase';
$_lang['setting_friendly_alias_realtime_desc'] = 'Určuje, zda má být alias dokumentu vytvářen za běhu při zadávání Názvu dokumentu nebo se tak stane až po uložení dokumentu ("automatic_alias" musí být povolen, aby toto fungovalo).';

$_lang['setting_friendly_alias_restrict_chars'] = 'Metoda odstranění nechtěných znaků z aliasu';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'Metoda omezující použití znaků v aliasech dokumentů. Možnosti: "pattern" povoluje RegEx filtr, "legal" povoluje všechny platné URL znaky, "alpha" povoluje pouze pismena abecedy a "alphanumeric" povoluje pouze znaky a čísla.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Vzor nechtěných znaků při metodě "pattern"';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Plátný RegEx vzor pro odstranění nechtěných znaků z aliasu dokumentu.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Odebrat html tagy z aliasu';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Určuje zda by měli být tagy odstraněny z aliasu dokumentu.';

$_lang['setting_friendly_alias_translit'] = 'Metoda přepisování aliasů';
$_lang['setting_friendly_alias_translit_desc'] = 'Metoda přepisování aliasů pro dokumnety. Nevyplněná nebo "none" je výchozí možnost, kdy nedochází k přepisu. Další možné hodnoty jsou "iconv" (pokud je dostupný) nebo tabulka poskytující vlastní přepisovací třídu služby.';

$_lang['setting_friendly_alias_translit_class'] = 'Třída zajišťující přepisování aliasů';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Volitelná služba poskytující tabulku pro službu přepisů pro generování/filtrování aliasů.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Cesta ke třídě zajišťující přepis aliasů';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'Cesta k balíčku s modelem odkud se má načítat služba třídy pro přepis FURL Alias.';

$_lang['setting_friendly_alias_trim_chars'] = 'Odstranění znaků z konce aliasů';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Znaky, které mají být odstraněny z konce URL u aliasu dokumentu.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Oddělovač slov aliasu';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Preferovaný oddělovač slov v přátelských URL.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Povolené oddělovače slov';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Znaky, které budou použity jako oddělovače slov při vytváření přátelských URL.';

$_lang['setting_friendly_urls'] = 'Používat přátelské URL';
$_lang['setting_friendly_urls_desc'] = 'Nastavení zda má MODX používat přátelské URL (lepší pro zpracování vyhledávači). Toto nastavení funguje pouze pro MODX instalace běžící serveru Apache, pro správnou funkčnost je také nutné nastavit soubor .htaccess. Pro více informací náhledněte do souboru .htaccess přiloženého v MODX distribuci.';
$_lang['setting_friendly_urls_err'] = 'Zvolte zda chcete používat přátelská URL či nikoli.';

$_lang['setting_friendly_urls_strict'] = 'Používat striktní přátelské URL';
$_lang['setting_friendly_urls_strict_desc'] = 'Pokud používáte přátelské URL, pak tato volba vynutí při ne-kánonickém dotazu, který odpovídá některému dokumentu přesměrování 301 na jeho kánonickou URI. POZOR: Nepoužívejte pokud používáte vlastní přepisovací pravidla, která nezachycují kánonické URI od začátku. Například: kánonické URI foo/ s vlastní přepisem na foo/bar.html bude fungovat, ale pokus o přepsání bar/foo.html na foo/ vynutí přesměrování na foo/ pokud je tato volba aktivní.';

$_lang['setting_global_duplicate_uri_check'] = 'Kontrola duplicitních URI napříč kontexty';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Vyberte "Ano" pokud chcete kontrolovat duplikáty napříč všemi kontexty. V ostatních případech je kontrolován pouze kontext v němž je daný dokument ukládán.';

$_lang['setting_hidemenu_default'] = 'Nezobrazovat v menu jako výchozí nastavení';
$_lang['setting_hidemenu_default_desc'] = 'Nastavte "Ano" pokud chcete, aby všechny nově vytvořené dokumenty měli přednastaveno nezobrazování se v menu.';

$_lang['setting_inline_help'] = 'Zobrazovat řádkové nápovědy u políček';
$_lang['setting_inline_help_desc'] = 'Je-li nastaveno na "Ano" pak budou přímo pod políčky zobrazeny jejich nápovědné texty. Pokud je nastaveno na "Ne", budou nápovědné texty zobrazeny v tooltipech.';

$_lang['setting_link_tag_scheme'] = 'Schéma generování URL';
$_lang['setting_link_tag_scheme_desc'] = 'Schéma generování URL pro tag [[~id]]. Možné volby viz: <a href="http://api.modxcms.com/modx/modX.html#makeUrl">http://api.modxcms.com/modx/modX.html#makeUrl</a>';

$_lang['setting_locale'] = 'Locale';
$_lang['setting_locale_desc'] = 'Nastavte locale pro vaše národní použití. Ponechte prázdné pro použití výchozí hodnoty. Více informací v <a href="http://php.net/setlocale" target="_blank">PHP dokumentaci</a>.';

$_lang['setting_lock_ttl'] = 'Čas odstranění zámků';
$_lang['setting_lock_ttl_desc'] = 'Počet sekund, po kterou je dokument uzamčen a uživatel v něm již nic neupravil, po jehož uplynutí bude daný zámek zrušen.';

$_lang['setting_log_level'] = 'Úroveň logování';
$_lang['setting_log_level_desc'] = 'Výchozí úroveň logování; čím nižší úroveň tím méně zpráv bude logováno. Možné hodnoty: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO), and 4 (DEBUG).';

$_lang['setting_log_target'] = 'Výstup logování';
$_lang['setting_log_target_desc'] = 'Výchozí výstup, kam mají být logy zapisovány. Možné hodnoty: \'FILE\', \'HTML\', nebo \'ECHO\'. Výchozí hodnota je \'FILE\'';

$_lang['setting_log_deprecated'] = 'Log Deprecated Functions';
$_lang['setting_log_deprecated_desc'] = 'Enable to receive notices in your error log when deprecated functions are used.';

$_lang['setting_mail_charset'] = 'Znaková sada e-mailu';
$_lang['setting_mail_charset_desc'] = 'Znaková sada e-mailu, např. "iso-8859-1" nebo "UTF-8". Doporučujeme "UTF-8".';

$_lang['setting_mail_encoding'] = 'Kódování e-mail';
$_lang['setting_mail_encoding_desc'] = 'Nastavení kódování e-ailových zpráv. Možnosti jsou "8bit", "7bit", "binary", "base64" a "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Použít SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Je-li nastaveno na Ano, MODX použije pro odesílání e-mailů SMTP server.';

$_lang['setting_mail_smtp_auth'] = 'SMTP autentizace';
$_lang['setting_mail_smtp_auth_desc'] = 'Sada SMTP autentizací. Využívá nastavení mail_smtp_user a mail_smtp_password.';

$_lang['setting_mail_smtp_helo'] = 'SMTP Helo zpráva';
$_lang['setting_mail_smtp_helo_desc'] = 'Nastavení SMTP HELO zprávy (Výchozí hostname).';

$_lang['setting_mail_smtp_hosts'] = 'SMTP servery';
$_lang['setting_mail_smtp_hosts_desc'] = 'Nastavení SMTP serverů. Jednotlivé servery musí být odděleny středníkem. Můžete také definovat různý port pro každý server použitím tohoto formátu: [server:port] (např. "smtp1.example.com:25;smtp2.example.com"). Servery budou použity postupně v případě, že předchozí neodpovídá.';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP udržovat spojení';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Zabraňuje SMTP serveru ukončit spojení po odeslání každého e-mailu. Nedoporučuje se.';

$_lang['setting_mail_smtp_pass'] = 'SMTP heslo';
$_lang['setting_mail_smtp_pass_desc'] = 'Heslo pro autentizaci k SMTP serveru.';

$_lang['setting_mail_smtp_port'] = 'SMTP port';
$_lang['setting_mail_smtp_port_desc'] = 'Nastavení výchozího SMTP portu.';

$_lang['setting_mail_smtp_prefix'] = 'SMTP šifrování';
$_lang['setting_mail_smtp_prefix_desc'] = 'Nastaví šifrování SMTP připojení. Možnosti jsou "", "ssl" nebo "tls".';

$_lang['setting_mail_smtp_autotls'] = 'SMTP automatické TLS';
$_lang['setting_mail_smtp_autotls_desc'] = 'Automaticky povolit TLS šifrování, pokud jej server podporuje, i když "SMTP Encryption" není nastaveno na "tls"';

$_lang['setting_mail_smtp_single_to'] = 'SMTP jednotlivě';
$_lang['setting_mail_smtp_single_to_desc'] = 'Možnost odesílání e-mailových zpráv jednotlivě.';

$_lang['setting_mail_smtp_timeout'] = 'SMTP timeout';
$_lang['setting_mail_smtp_timeout_desc'] = 'Nastavení délky timeoutu SMTP serveru v sekundách. Tato funkčnost nepracuje ve Windows systémech.';

$_lang['setting_mail_smtp_user'] = 'SMTP uživatelské jméno';
$_lang['setting_mail_smtp_user_desc'] = 'Uživatelské jméno pro autentizaci k SMTP.';

$_lang['setting_main_nav_parent'] = 'Složka hlavního menu';
$_lang['setting_main_nav_parent_desc'] = 'Složka pro načítání všech položek hlavního menu.';

$_lang['setting_manager_direction'] = 'Směr zobrazení textu ve správci obsahu';
$_lang['setting_manager_direction_desc'] = 'Zvolte směr textu, kterým bude zobrazen obsah správce obsahu, zleva do prava nebo zprava do leva.';

$_lang['setting_manager_date_format'] = 'Formát data ve správci obsahu';
$_lang['setting_manager_date_format_desc'] = 'Formátovací řetězec v PHP date() formátu, jak má být datum reprezentován ve správci obsahu.';

$_lang['setting_manager_favicon_url'] = 'URL favikony pro správce obsahu';
$_lang['setting_manager_favicon_url_desc'] = 'Je-li tato volba nastavena, bude její hodnota použita pro načtení favikony pro správce obsahu. Cesta musí být zadána absolutně nebo relativně vůči adresáři /manager.';

$_lang['setting_manager_js_cache_file_locking'] = 'Povolit uzamykání JS/CSS cache souborů správce obsahu';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Uzamykání souborů cache. Nastavte na "Ne" používáte-li souborový systém NFS.';
$_lang['setting_manager_js_cache_max_age'] = 'Staří komprimované cache JS/CSS pro správce obsahu';
$_lang['setting_manager_js_cache_max_age_desc'] = 'Maximální stáří (v sekundách) cache prohlížeče pro CSS/JS správce obsahu. Po uplynutí této doby bude prohlížeči poslán další podmíněný GET. Pro nižší trafic nastavte delší dobu.';
$_lang['setting_manager_js_document_root'] = 'Document Root pro komprimované JS/CSS ve správci obsahu';
$_lang['setting_manager_js_document_root_desc'] = 'Pokud Váš server nezpracovává proměnnou serveru DOCUMENT_ROOT a chcete používat možnost komprese JS/CSS ve správci obsahu nastavte jí zde manuálně. Pokud si nejste jist o co jde, pak toto nastavení neměňte.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Povolit zlib výstupní kompresy JS/CSS pro správce obsahu';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Zda-li použít či nikoli zlib výstupní kompresi komprimovaných CSS/JS pro správce obsahu. Nechte vypnuté pokud si nejste jistí, že může být nastavena PHP konfigurační proměnná zlib.output_compression na 1. MODX toto doporučuje neměnit.';

$_lang['setting_manager_lang_attribute'] = 'HTML a XML jazykové atributy správce obsahu';
$_lang['setting_manager_lang_attribute_desc'] = 'Zadejte jazykový kód, který nejlépe vystihuje zvolený jazyk správce obsahu, toto nastavení zajistí, že Vám prohlížeč zobrazí správně data.';

$_lang['setting_manager_language'] = 'Jazyk správce obsahu';
$_lang['setting_manager_language_desc'] = 'Zvolte jazyk pro MODX správce obsahu.';

$_lang['setting_manager_login_url_alternate'] = 'Alternativní URL pro Správce obsahu';
$_lang['setting_manager_login_url_alternate_desc'] = 'Alternativní URL, na kterou je přesměrován nepřihlášený uživatel, pokud se chce přihlásit do Správce obsahu.';

$_lang['setting_manager_login_start'] = 'Úvodní stránka po přihlášení do správce obsahu';
$_lang['setting_manager_login_start_desc'] = 'Zadejte ID dokumentu, na která chcete přesměrovat uživatele po přihlášení do správce obsahu. <strong>Poznámka: ujistěte se, že ID patří existujícímu dokumentu, je publikován a je přístupný tomuto uživateli!</strong>';

$_lang['setting_manager_theme'] = 'Vzhled správce obsahu';
$_lang['setting_manager_theme_desc'] = 'Vyberte vzhled pro správce obsahu.';

$_lang['setting_manager_time_format'] = 'Formát času ve správci obsahu';
$_lang['setting_manager_time_format_desc'] = 'Formátovací řetězec v PHP date() formátu, jak má být čas reprezentován ve správci obsahu.';

$_lang['setting_manager_use_tabs'] = 'Používat záložky ve správci obsahu';
$_lang['setting_manager_use_tabs_desc'] = 'Je-li aktivní, správce obsahu použije pro vykreslení obsahu záložky, jinak budou panely vykresleny pod sebou.';

$_lang['setting_manager_week_start'] = 'Začátek týdne';
$_lang['setting_manager_week_start_desc'] = 'Určuje den, kterým začíná týden. 0 nebo prázdné pole je neděle, 1 pondělí, atd. ';

$_lang['setting_mgr_tree_icon_context'] = 'Ikona kontextu';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Definujte CSS třídu, která se použije k zobrazení ikony kontextu ve stromu dokumentů. Toto nastavení můžete použít v rámci každého kontextu, tedy každý kontext může mít jinou ikonu.';

$_lang['setting_mgr_source_icon'] = 'Ikona zdroje médií';
$_lang['setting_mgr_source_icon_desc'] = 'CSS třída, která se použije k zobrazení ikony Zdroje médií ve stromu souborů. Výchozí nastavení je "icon-folder-open-o"';

$_lang['setting_modRequest.class'] = 'Třída obsluhy dotazu';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Skrýt soubory ve stromu Prohlížeče médií';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'Je-li Ano, soubory uvnitř složky nejsou zobrazeny ve stromu v rámci Prohlížeče médií.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Skrýt náhledy v rámci stromu v Prohlížeči médií';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'Je-li toto povoleno, nebudou se ve stromu v Prohlížeči médií zobrazovat náhledy souborů po najetí kurzorem myši. Výchozí nastavení je Ano.';

$_lang['setting_modx_browser_default_sort'] = 'Výchozí řazení v Průzkumníku zdrojů';
$_lang['setting_modx_browser_default_sort_desc'] = 'Výchozí nastavení řazení v Průzkumníku zdrojů (vkládání obrázků atp.). Možné hodnoty jsou: name, size, lastmod (poslední změna).';

$_lang['setting_modx_browser_default_viewmode'] = 'Výchozí pohled v Průzkmníku zdrojů';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'Výchozí pohled při používání modálního okna Průzkmníku zdrojů. Povolené hodnoty: grid, list.';

$_lang['setting_modx_charset'] = 'Kódování znaků';
$_lang['setting_modx_charset_desc'] = 'Nastavte jaké kódování znaků chcete používat v rámci správce obsahu. Pamatujte, že MODX byl testován s mnoha kódováními, ale ne se všemi. Pro většinu jazyků je preferováno výchozí nastavení "UTF-8".';

$_lang['setting_new_file_permissions'] = 'Atributy nového souboru';
$_lang['setting_new_file_permissions_desc'] = 'Souborům nahraným pomocí správce souborů budou nastaveny tyto atributy. Toto nastavení nemusí fungovat na některých serverech, např. na IIS, v těchto případech budete muset nastavit atributy manuálně.';

$_lang['setting_new_folder_permissions'] = 'Atributy nové složky';
$_lang['setting_new_folder_permissions_desc'] = 'Složkám vytvořeným ve správci souborů budou nastaveny tyto atributy. Toto nastavení nemusí fungovat na některých serverech, např. na IIS, v těchto případech budete muset nastavit atributy manuálně.';

$_lang['setting_parser_recurse_uncacheable'] = 'Zpožděné necachované zpracování';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'Je-li zakázáno, výstup necahovatelných elementů může být cachován uvnitř cachovatelných elementů. Zakažte pouze, pokud máte problémy se zpracováním vnořených komplexní elementů, které nefungují podle očekávání.';

$_lang['setting_password_generated_length'] = 'Délka automaticky generovaného hesla';
$_lang['setting_password_generated_length_desc'] = 'Délka automaticky generovaného hesla pro uživatele.';

$_lang['setting_password_min_length'] = 'Minimální délka hesla';
$_lang['setting_password_min_length_desc'] = 'Minimální délka hesla uživatele.';

$_lang['setting_preserve_menuindex'] = 'Zachovat Menu Index při duplikování dokumentu';
$_lang['setting_preserve_menuindex_desc'] = 'Při duplikování dokumentu bude pozice v menu (menu index) zachována.';

$_lang['setting_principal_targets'] = 'ACL cíle pro načtení';
$_lang['setting_principal_targets_desc'] = 'Vlastní ACL cíle pro MODX uživatele.';

$_lang['setting_proxy_auth_type'] = 'Proxy typ autentizace';
$_lang['setting_proxy_auth_type_desc'] = 'Podporuje buď BASIC nebo NTLM.';

$_lang['setting_proxy_host'] = 'Proxy server';
$_lang['setting_proxy_host_desc'] = 'Pokud Váš server používá proxy nastavte jej na tomto místě. Tím povolíte MODXu používat proxy, např. pro přístup ke správci balíčků.';

$_lang['setting_proxy_password'] = 'Proxy heslo';
$_lang['setting_proxy_password_desc'] = 'Heslo požadované pro autentizaci k proxy serveru.';

$_lang['setting_proxy_port'] = 'Proxy port';
$_lang['setting_proxy_port_desc'] = 'Port proxy serveru.';

$_lang['setting_proxy_username'] = 'Proxy uživatelské jméno';
$_lang['setting_proxy_username_desc'] = 'Uživatelské jméno pro autentizaci k proxy serveru.';

$_lang['setting_photo_profile_source'] = 'Zdroj médií pro profilové fotografie uživatelů';
$_lang['setting_photo_profile_source_desc'] = 'Zdroj médií sloužící k ukládání fotografií uživatelských profilů. Výchozí nastavení je výchozí zdroj médií.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'Povolit soubory mimo root';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Indikuje zda může být cesta src mimo root. Tato volba je užitečná při multi-kontextovém vývoji s více virtuálními hosty.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb Maximální stáří cache';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Smaže náhledy uložené v cache, které nebyly načteny více než X dní.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb Maximální velikost cache';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Smaže nejméně často načítané náhledy, když velikost cache přesáhne X MB.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Maximální počet souborů v cache';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Smaže nejméně často načítané náhledy pokud má cache více než X souborů.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb Ukládat zdrojové soubory do cache';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Určuje zda se mají zdrojové soubory načítat do cache nebo ne. Doporujeme nastavit "Ne".';

$_lang['setting_phpthumb_document_root'] = 'PHPThumb Document Root';
$_lang['setting_phpthumb_document_root_desc'] = 'Tuto volbu nastavte pokud máte problém se serverovou proměnnou DOCUMENT_ROOT, nebo dostáváte chyby od OutputThumbnail nebo !is_resource. Chcete-li tuto volbu využít pak nastavte absolutní cestu v rámci serveru. Je-li hodnota tohoto nastavení prázdná tak MODX použije proměnné serveru DOCUMENT_ROOT.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb Chyba: barva pozadí';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Hexadecimální hodnota barvy bez #, definující barvu pozadí při výpisu chyby phpThumb.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb Chyba: velikost fontu';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Hodnota definující velikost fontu v "em" (bez textu "em") při výpisu text chyby v phpThumb.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb Chyba: barva textu';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Hexadecimální hodnota barvy bez #, definující barvu textu při výpisu chyby phpThumb.';

$_lang['setting_phpthumb_far'] = 'phpThumb zachovat poměr stran';
$_lang['setting_phpthumb_far_desc'] = 'Výchozí hodnota "C" pro zachování poměru stran směrem ke středu.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb Cesta k ImageMagick';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Volitelné. Nastavení cesty k ImageMagick pro alternativní generování náhledů pomocí phpThumb, pokud není ve výchozím nastavení PHP.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Hotlinking: aktivní';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Vzdálené servery jsou povoleny v atributu src jestliže, není tato volba nastavena na "Ne".';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Hotlinking: odstranit obrázky';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Volba zda mají být odstraněny obrázky generované z cizích serverů, pokud není povolen hotlinking.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Hotlinking: zpráva nepovoleného přístupu';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Zpráva, která se zobrazí místo náhledu při zakázaném hotlinkingu.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Hotlinking: platné domény';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Čárkou oddělený seznam domén, které mohou používat hotlinking.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Offsite Linking: vypnut';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Znemožnění používat phpThumb pro vykreslení obrázků na cizích portálech.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Offsite Linking: odstranit obrázky';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Volba zda mají být odstraněny obrázky linkované z cizích nepovolených portálů.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Offsite Linking: vyžadovat Referrer';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Je-li aktivní, nebude povolen offsite linking pokud nebude platná hlavička Referrer.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Offsite Linking: zpráva nepovoleného přístupu';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Zpráva, která se zobrazí místo náhledu při zakázaném offsite linkingu.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Offsite Linking: platné domény';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Čárkou oddělený seznam domén, které mohou používat offsite linking.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Offsite Linking: zdroj vodoznaku';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Volitelné. Cesta k obrázku, který má být použit jako vodoznak při vykreslování obrázku při offsite linking.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Zoom-Crop (ořez při zvětšení)';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Výchozí nastavení Zoom-Crop pro phpThumb pokud je použit v MODX. Výchozí hodnota je 0, tím se zabrání oříznutí při zvětšení.';

$_lang['setting_publish_default'] = 'Ve výchozím stavu publikováno';
$_lang['setting_publish_default_desc'] = 'Zvolte "Ano", pokud chcete, aby všechny nově vytvořené dokumenty byly ve výchozím stavu publikované.';
$_lang['setting_publish_default_err'] = 'Zvolte zda chcete, aby byly dokumenty publikovány nebo ne.';

$_lang['setting_rb_base_dir'] = 'Cesta k souborům';
$_lang['setting_rb_base_dir_desc'] = 'Zadejte fyzickou cestu k adresáři se zdroji. Toto nastavení je obvykle generováno automaticky. Používáte-li IIS, MODX není schopen zjistit automaticky tuto cestu, což zapříčiňuje zobrazení chyb ve správci souborů. V tomto případě můžete zadat cestu do adresáře s obrázky (stejně jako ji vkládáte do Vašeho prohlížeče). <strong>Poznámka:</strong> Adresář se zdroji musí obsahovat složky "images, files, flash a media" jinak nebude správce souborů pracovat správně.';
$_lang['setting_rb_base_dir_err'] = 'Zadejte cestu ke kořenu pro správce souborů.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Tento adresář buď neexistuje nebo není přístupný. Zadejte platný adresář nebo nastavte atributy pro přístup PHP.';

$_lang['setting_rb_base_url'] = 'URL k souborům';
$_lang['setting_rb_base_url_desc'] = 'Zadejte virtuální cestu k adresáři souborů. Toto nastavení je obvykle generováno automaticky. Používáte-li IIS, MODX není schopen zjistit automaticky tuto URL, což zapříčiňuje zobrazení chyb ve správci souborů. V tomto případě můžete zadat URL do adresáře s obrázky (stejně jako ji vkládáte do Vašeho prohlížeče).';
$_lang['setting_rb_base_url_err'] = 'Nastavte URL pro správce souborů.';

$_lang['setting_request_controller'] = 'Název souboru kontroleru požadavků';
$_lang['setting_request_controller_desc'] = 'Název souboru hlavního kontroleru požadavků odkud se načítá MODX. Většina uživatelů by toto měla ponechat na index.php.';

$_lang['setting_request_method_strict'] = 'Striktní metoda dotazu';
$_lang['setting_request_method_strict_desc'] = 'Je-li aktivní, dotazy skrze ID parametr budou ignorovány pokud jsou zaplé FURLs a dotazy skrze Alias parametr budou ignorovány pokud jsou vyplé FURLs.';

$_lang['setting_request_param_alias'] = 'Název parametru požadavku';
$_lang['setting_request_param_alias_desc'] = 'Název GET parametru identifikujícího alias dokumentu při přesměrování pomocí přátelských URL.';

$_lang['setting_request_param_id'] = 'Název ID parametru požadavku';
$_lang['setting_request_param_id_desc'] = 'Název GET parametru identifikujícího ID dokumnetu pokud nejsou použity přátelské URL.';

$_lang['setting_resolve_hostnames'] = 'Získavat hostname návštěvníků';
$_lang['setting_resolve_hostnames_desc'] = 'Chcete, aby se MODX pokoušel získávat hostname návštěvníků portálu? Získávání hostname může způsobit zatížení serveru navíc, ale návštěvníky to neovlivní.';

$_lang['setting_resource_tree_node_name'] = 'Zdroj názvu dokumentu ve stromu dokumentů';
$_lang['setting_resource_tree_node_name_desc'] = 'Zadejte název políčka, kterého obsah se má zobrazovat jako název dokumentu ve stromu dokumentů. Výchozí hodnotou je "pagetitle", ale může zde být použito jakékoliv políčko jako např. "menutitle", "alias", "longtitle" atd.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Sekundární pole pro název ve stromu dokumentů';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Zadejte pole dokumentu, které se má použít pro názvy dokumentu ve stromu dokumentů. Toto bude použito v případě, že primární pole nemá definovánu žádnou hodnotu.';

$_lang['setting_resource_tree_node_tooltip'] = 'Políčko nápovědy pro strom dokumentů';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Vyberte políčko, které má být použito pro zobrazení nápovědy. Lze použít jakékoliv políčko dokumentu jako např. "menutitle", "alias", "longtitle", atd. Je-li tato volba nevyplněna bude použito políčko "longtitle" včetně podpopisu z políčka "description".';

$_lang['setting_richtext_default'] = 'WYSIWYG editor';
$_lang['setting_richtext_default_desc'] = 'Zvolte "Ano" pokud chcete, aby všechny nově vytvořené dokumenty používaly ve výchozím stavu WYSIWYG editor.';

$_lang['setting_search_default'] = 'Vyhledatelné dokumenty';
$_lang['setting_search_default_desc'] = 'Zvolte "Ano" pokud chcete, aby byly všechny nově přidané dokumnety ve výchozím stavu vyhledatelné.';
$_lang['setting_search_default_err'] = 'Zvolte zda mají být dokumenty ve výchozím stavu vyhledatelné či nikoli.';

$_lang['setting_server_offset_time'] = 'Rozdíl času serveru';
$_lang['setting_server_offset_time_desc'] = 'Nastavte počet hodin, který je rozdílem mezi Vaším místem a místem, kde je umístěn server.';

$_lang['setting_server_protocol'] = 'Typ serveru';
$_lang['setting_server_protocol_desc'] = 'Pokud Váš portál používá spojení https, vyberte jej zde.';
$_lang['setting_server_protocol_err'] = 'Vyberte zda je Váš portál zabezpečený pomocí https nebo ne.';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Doména session cookie';
$_lang['setting_session_cookie_domain_desc'] = 'Toto nastavení použijte pro přizpůsobení domény pro session cookie.';

$_lang['setting_session_cookie_lifetime'] = 'Životnost session cookie';
$_lang['setting_session_cookie_lifetime_desc'] = 'Tímto nastavením můžete přizpůsobit životnost session cookie v sekundách. Toto nastavení je použito pro nastavení životnosti sesson cookie přihlášeného uživatele pokud při přihlášení zvolí "Zapamatovat si mě".';

$_lang['setting_session_cookie_path'] = 'Cesta pro session cookie';
$_lang['setting_session_cookie_path_desc'] = 'Toto nastavení použijte pro přizpůsobení cesty ke cookie pro identifikaci portálu v závislosti na specifické session cookie.';

$_lang['setting_session_cookie_secure'] = 'Zabezpečení session cookie';
$_lang['setting_session_cookie_secure_desc'] = 'Aktivací této možnosti dojde k zabezpečení session cookie.';

$_lang['setting_session_cookie_httponly'] = 'Session Cookie HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Povolte toto nastavení pro nastavení příznaku HttpOnly v session cookies.';

$_lang['setting_session_cookie_samesite'] = 'Session Cookie Samesite';
$_lang['setting_session_cookie_samesite_desc'] = 'Zvolte Lax nebo Strict.';

$_lang['setting_session_gc_maxlifetime'] = 'Maximální životnost Session Garbage Collectoru';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Umožnuje přizpůsobení nastavení PHP ini session.gc_maxlifetime používá-li se "modSessionHandler".';

$_lang['setting_session_handler_class'] = 'Název třídy správce session';
$_lang['setting_session_handler_class_desc'] = 'Pro databází spravované session, použijte "modSessionHandler". Toto ponechte prázdné, pro použití standardní PHP správy session.';

$_lang['setting_session_name'] = 'Název session';
$_lang['setting_session_name_desc'] = 'Toto nastavení použijte pro přizpůsobení názvu session v MODX.';

$_lang['setting_settings_version'] = 'Verze MODX';
$_lang['setting_settings_version_desc'] = 'Verze instalovaného MODX.';

$_lang['setting_settings_distro'] = 'Distribuce';
$_lang['setting_settings_distro_desc'] = 'Současně instalovaná distribuce MODX.';

$_lang['setting_set_header'] = 'Nastavovat HTTP hlavičky';
$_lang['setting_set_header_desc'] = 'Pokud je aktivní, MODX se pokusí nastavit HTTP hlavičky pro dokumnety.';

$_lang['setting_send_poweredby_header'] = 'Odesílaná hlavička X-Powered-By';
$_lang['setting_send_poweredby_header_desc'] = 'Pokud je povoleno, MODX bude odesílat hlavičku "X-Powered-By" pro identifikaci webu postaveného na MODX. To usnadní zjišťování míry rozšíření MODX nástroji třetích stran. Protože to usnadňuje identifikaci systému vašeho webu, může to mírně zvýšit bezpečnostní riziko pokud je nalezena nějaká bezpečnosntí chyba v MODX.';

$_lang['setting_show_tv_categories_header'] = 'Zobrazovat záložky "Kategorií" u TVs';
$_lang['setting_show_tv_categories_header_desc'] = 'Je-li nasteveno na "Ano", MODX bude zobrazovat kategorie v záložce TVs při úpravách dokumentů.';

$_lang['setting_signupemail_message'] = 'Registrační e-mail';
$_lang['setting_signupemail_message_desc'] = 'Šablona zprávy, která bude poslána uživateli pokud mu vytvoříte účet a necháte MODX zaslat mu e-mail obsahujicí jeho uživatelské jméno a heslo. <br /><strong>Poznámka:</strong> Následující placeholdery jsou před odesláním nahrazeny správcem obsahu: <br /><br />[[+sname]] - Název portálu, <br />[[+saddr]] - E-mailová adresa portálu, <br />[[+surl]] - URL adresa portálu, <br />[[+uid]] - Jméno nebo ID uživatele, <br />[[+pwd]] - Heslo uživatele, <br />[[+ufn]] - Celé jméno uživatele. <br /><br /><strong>Ponechte placeholdery [[+uid]] a [[+pwd]] v e-mailu nebo nebude uživatelské jméno a heslo obsaženo v e-mailu a uživatel nebude znát své uživatelské jméno a heslo!</strong>';
$_lang['setting_signupemail_message_default'] = 'Dobrý den [[+uid]] \n\nZde jsou Vaše přihlašovací údaje pro [[+sname]] Správce obsahu:\n\nUživatelské jméno: [[+uid]]\nHeslo: [[+pwd]]\n\nJakmile se přihlásíte do správce obsahu ([[+surl]]) můžete si změnit heslo.\n\S pozdravem,\nadministrátor portálu.';

$_lang['setting_site_name'] = 'Název portálu';
$_lang['setting_site_name_desc'] = 'Zadejte název Vašeho portálu.';
$_lang['setting_site_name_err']  = 'Zadejte název Vašeho portálu.';

$_lang['setting_site_start'] = 'Úvodní stránka portálu';
$_lang['setting_site_start_desc'] = 'Zadejte ID zdroje, kterou chcete použít jako úvodní stránku. <strong>Poznámka: ujistěte se, že zadané ID patří existujícímu zdroji a je publikován!</strong>';
$_lang['setting_site_start_err'] = 'Zadejte ID zdroje, který má být úvodní stránkou portálu.';

$_lang['setting_site_status'] = 'Stav portálu';
$_lang['setting_site_status_desc'] = 'Zvolte "Ano" pro zveřejnění celého portálu na web (stav online). Vyberete-li "Ne" (stav offline), návštěvníci portálu uvidí "Zpráva nedostupnosti portálu" a nebudou si moci portál procházet.';
$_lang['setting_site_status_err'] = 'Zadejte zda má být portál online (Ano) nebo offline (Ne).';

$_lang['setting_site_unavailable_message'] = 'Zpráva nedostupnosti portálu';
$_lang['setting_site_unavailable_message_desc'] = 'Zpráva, která se zobrazí pokud je portál offline nebo pokud nastala chyba. <strong>Poznámka: tato zpráva bude zobrazena pouze v případě, pokud není nastavena možnost "Stránka nedostupnousti portálu".</strong>';

$_lang['setting_site_unavailable_page'] = 'Stránka nedostupnousti portálu';
$_lang['setting_site_unavailable_page_desc'] = 'Zadejte ID dokumentu, kterou chcete použít jako tzv. offline stránku. <strong>Poznámka: ujistěte se, že zadané ID patří existujícímu zdroji a je publikován!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Zadejte ID dokumentu, která bude použita jako stránka nedostupnosti portálu.';

$_lang['setting_static_elements_automate_templates'] = 'Automate static elements for templates?';
$_lang['setting_static_elements_automate_templates_desc'] = 'This will automate the handling of static files, such as creating and removing static files for templates.';

$_lang['setting_static_elements_automate_tvs'] = 'Automate static elements for template variables?';
$_lang['setting_static_elements_automate_tvs_desc'] = 'This will automate the handling of static files, such as creating and removing static files for template variables.';

$_lang['setting_static_elements_automate_chunks'] = 'Automate static elements for chunks?';
$_lang['setting_static_elements_automate_chunks_desc'] = 'This will automate the handling of static files, such as creating and removing static files for chunks.';

$_lang['setting_static_elements_automate_snippets'] = 'Automate static elements for snippets?';
$_lang['setting_static_elements_automate_snippets_desc'] = 'This will automate the handling of static files, such as creating and removing static files for snippets.';

$_lang['setting_static_elements_automate_plugins'] = 'Automate static elements for plugins?';
$_lang['setting_static_elements_automate_plugins_desc'] = 'This will automate the handling of static files, such as creating and removing static files for plugins.';

$_lang['setting_static_elements_default_mediasource'] = 'Static elements default mediasource';
$_lang['setting_static_elements_default_mediasource_desc'] = 'Specify a default mediasource where you want to store the static elements in.';

$_lang['setting_static_elements_default_category'] = 'Výchozí kategorie pro statické prvky';
$_lang['setting_static_elements_default_category_desc'] = 'Zvolte výchozí kategorii pro vytváření nových statických prvků.';

$_lang['setting_static_elements_basepath'] = 'Static elements basepath';
$_lang['setting_static_elements_basepath_desc'] = 'Basepath of where to store the static elements files.';

$_lang['setting_resource_static_allow_absolute'] = 'Allow absolute static resource path';
$_lang['setting_resource_static_allow_absolute_desc'] = 'This setting enables users to enter a fully qualified absolute path to any readable file on the server as the content of a static resource. Important: enabling this setting may be considered a significant security risk! It\'s strongly recommended to keep this setting disabled, unless you fully trust every single manager user.';

$_lang['setting_resource_static_path'] = 'Static resource base path';
$_lang['setting_resource_static_path_desc'] = 'When resource_static_allow_absolute is disabled, static resources are restricted to be within the absolute path provided here.  Important: setting this too wide may allow users to read files they shouldn\'t! It is strongly recommended to limit users to a specific directory such as {core_path}static/ or {assets_path} with this setting.';

$_lang['setting_strip_image_paths'] = 'Přepisovat URL souborů';
$_lang['setting_strip_image_paths_desc'] = 'Pokud je nastaveno na "Ne", MODX bude zapisovat cesty k souborům (obrázky, soubory, flash, atd.) jako absolutní URL. Relativní URL jsou užitečné pokud byste chtěli přesunout celou instalaci MODX, např. z vývojového serveru na produkční. Pokud netušíte co s tímto nastavením, ponechte jej nastavené na "Ano".';

$_lang['setting_symlink_merge_fields'] = 'Sloučit políčka dokumentů v symbolických odkazech';
$_lang['setting_symlink_merge_fields_desc'] = 'JeIf nastaveno "Ano", dojde k automatickému sloučení neprázdných políček při přesměrování pomocí symbolických odkazů.';

$_lang['setting_syncsite_default'] = 'Výchozí stav smazání cache po uložení';
$_lang['setting_syncsite_default_desc'] = 'Vyberte "Ano" pokud chcete ve výchozím stavu smazat cache dokumentu po jeho uložení.';
$_lang['setting_syncsite_default_err'] = 'Prosím zvolte, zda chcete ve výchozím nastavení smazat cache po uložení dokumentu.';

$_lang['setting_topmenu_show_descriptions'] = 'Zobrazovat popisky v horním menu';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Je-li nastaveno na "Ne", MODX skryje popisky u položek horního menu v rámci správce obsahu.';

$_lang['setting_tree_default_sort'] = 'Výchozí řazení dokumentů ve stromu dokumentů';
$_lang['setting_tree_default_sort_desc'] = 'Políčko, které se má použít pro výchozí řazení dokumentů v rámci stromu dokumentů.';

$_lang['setting_tree_root_id'] = 'ID kořenu stromu';
$_lang['setting_tree_root_id_desc'] = 'Nastavte ID zdroje, pod kterým začne levý strom dokumentů. Uživatel bude mít možnost vidět pouze potomky tohoto zdroje.';

$_lang['setting_tvs_below_content'] = 'Přesunout TVs pod obsah dokumentu';
$_lang['setting_tvs_below_content_desc'] = 'Nastavte toto na "Ano", pokud chcete přessunout Template Variables ze záložky TVs dolu pod obsah dokumentu.';

$_lang['setting_ui_debug_mode'] = 'UI Debug Mode';
$_lang['setting_ui_debug_mode_desc'] = 'Nastavte na "Ano" pro výpis ladících zpráv používáte-li UI pro výchozí téma správce obsahu. Musíte používat prohlížeč podporující console.log.';

$_lang['setting_udperms_allowroot'] = 'Povolit kořenovou složku';
$_lang['setting_udperms_allowroot_desc'] = 'Chcete uživatelům povolit vytváření nových dokumentů v kořenové složce portálu? ';

$_lang['setting_unauthorized_page'] = 'Stránka neautorizovaného přístupu';
$_lang['setting_unauthorized_page_desc'] = 'Zadejte ID zdroje, na který chcete přesměrovat uživatele pokud se pokusili přistoupit ke stránce, pro kterou nemají oprávnění. <strong>Poznámka: ujistěte se, že zadané ID patří existujícího zdroji, který je publikován a je přístupný veřejnosti!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Zadejte ID zdroje pro stránku neautorizovaného přístupu.';

$_lang['setting_upload_check_exists'] = 'Check if uploaded file exists';
$_lang['setting_upload_check_exists_desc'] = 'When enabled an error will be shown when uploading a file that already exists with the same name. When disabled, the existing file will be quietly replaced with the new file.';

$_lang['setting_upload_files'] = 'Povolené typy souborů';
$_lang['setting_upload_files_desc'] = 'Zde můžete zadat seznam souborů, které mohou být nahrávány do "assets/files/" pomocí správce souborů. Zadejte přípony souborů pro typy souborů oddělené čárkami.';

$_lang['setting_upload_flash'] = 'Povolené typy souborů flash';
$_lang['setting_upload_flash_desc'] = 'Zde můžete zadat seznam souborů, které mohou být nahrávány do "assets/flash/" pomocí správce souborů. Zadejte přípony souborů pro typy flashů oddělené čárkami.';

$_lang['setting_upload_images'] = 'Povolené typy obrázků';
$_lang['setting_upload_images_desc'] = 'Zde můžete zadat seznam souborů, které mohou být nahrávány do "assets/images/" pomocí správce souborů. Zadejte přípony souborů pro typy obrázků oddělené čárkami.';

$_lang['setting_upload_maxsize'] = 'Maximální velikost nahrávaného souboru';
$_lang['setting_upload_maxsize_desc'] = 'Zadejte maximální velikost souboru, kterou je možno nahrát pomocí správce souborů. Velikost musí být zadána v bajtech. <strong>Poznámka: Nahrávání velkých souborů může trvat dlouho!</strong>';

$_lang['setting_upload_media'] = 'Povolené typy médií';
$_lang['setting_upload_media_desc'] = 'Zde můžete zadat seznam souborů, které mohou být nahrávány do "assets/media/" pomocí správce souborů. Zadejte přípony souborů pro typy médií oddělené čárkami.';

$_lang['setting_use_alias_path'] = 'Použít cesty pomocí přátelských aliasů';
$_lang['setting_use_alias_path_desc'] = 'Nastavením možnosti na "Ano" zobrazí celou cestu k dokumentu pokud má dokument alias. Například, pokud je dokument s aliasem "potomek" umístěn uvnitř složky s aliasem "rodic", pak bude celá adresa zobrazena jako "/rodic/potomek.html".<br /><strong>Poznámka: Je-li toto nastaveno na Ano (zapnutím cest pomocí aliasů), referencované položky (jako obrázky, css, javaskripty, atd.) používají absolutní cesty: např., "/assets/images" na rozdíl od "assets/images". Tímto zamezíte prohlížeči (nebo serveru) vkládání relativních cest do aliasů.</strong>';

$_lang['setting_use_browser'] = 'Povolit správce souborů';
$_lang['setting_use_browser_desc'] = 'Nastavte "Ano" pro aktivaci správce souborů. Toto nastavení povolí uživatelům procházet a nahrávat soubory jako např. obrázky, flash nebo soubory médií na server.';
$_lang['setting_use_browser_err'] = 'Uveďte zda chcete nebo nechcete používat správce souborů.';

$_lang['setting_use_editor'] = 'Povolit WYSIWYG editor';
$_lang['setting_use_editor_desc'] = 'Chcete aktivovat WYSIWYG editor? Pokud je Vám pohodlnější psát přímo HTML, pak ponechte toto nastavení neaktivní. Poznámka: toto nastavení je globálním, tzn. že bude aplikováno na všechny dokumenty a uživatele!';
$_lang['setting_use_editor_err'] = 'Uveďte zda chcete použít WYSIWYG editor nebo ne';

$_lang['setting_use_frozen_parent_uris'] = 'Používat statické URI rodičů';
$_lang['setting_use_frozen_parent_uris_desc'] = 'Pokud je povoleno, URI pro potomky dokumentů bude generovaná oproti statické URI jednoho z jeho rodičů, ignoruje aliasy dokumentů v rámci stromu.';

$_lang['setting_use_multibyte'] = 'Použít Multibyte extenzi pro PHP';
$_lang['setting_use_multibyte_desc'] = 'Nastavte na "Ano", pokud chcete používat extenzi mbstring pro multibyte znaky ve Vaší instalaci MODXu. Nastavte pouze pokud máte extenzi instalovanou v PHP. Silně doporučujeme nastavit "Ano" pro použití s češtinou.';

$_lang['setting_use_weblink_target'] = 'Použít cíl jako webový odkaz';
$_lang['setting_use_weblink_target_desc'] = 'Nastavte na "Ano" pokud chcete, aby MODX tagy odkazů a makeUrl() generovali odkazy jako cílové URL pro webové odkazy. Nastavením "Ne" budou generovány interní MODX URL.';

$_lang['setting_user_nav_parent'] = 'Složka uživatelského menu';
$_lang['setting_user_nav_parent_desc'] = 'Složka pro načítání všech položek uživatelského menu.';

$_lang['setting_webpwdreminder_message'] = 'E-mail pro vyžádání nového hesla';
$_lang['setting_webpwdreminder_message_desc'] = 'Šablona zprávy, která se odešle pokud zažádá webový uživatel o zaslání nového hesla e-mailem. Správce obsahu mu odešle e-mail obsahující nové heslo a aktivační informace. <br /><strong>Poznámka:</strong> Následující placeholdery jsou nahrazeny správcem obsahu než je správa odeslána: <br /><br />[[+sname]] - Název portálu, <br />[[+saddr]] - E-mailová adresa portálu, <br />[[+surl]] - URL adresa portálu, <br />[[+uid]] - Jméno nebo ID uživatele, <br />[[+pwd]] - Heslo uživatele, <br />[[+ufn]] - Celé jméno uživatele. <br /><br /><strong>Ponechte placeholdery [[+uid]] a [[+pwd]] v e-mailu nebo nebude uživatelské jméno a heslo obsaženo v e-mailu a uživatel nebude znát své uživatelské jméno a heslo!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Dobrý den [[+uid]]\n\nPro aktivaci nového heslo klikněte na odkaz:\n\n[[+surl]]\n\nPokud vše proběhlo úspěšně můžete použít následující heslo pro přihlášení:\n\nHeslo:[[+pwd]]\n\nPokud jste o změnu hesla nežádali tak tento e-mail ignorujte.\n\nS pozdravem,\nadministrátor portálu.';

$_lang['setting_websignupemail_message'] = 'E-mail po registraci z webu';
$_lang['setting_websignupemail_message_desc'] = 'Šablona zprávy odesílané webovým uživatelům, pokud jim vytvoříte účet webového uživatele a necháte správce obsahu, aby jim odeslal e-mail obsahující jejich uživatelské jméno a heslo. <br /><strong>Poznámka:</strong> Následující placeholdery jsou nahrazeny správcem obsahu než je správa odeslána: <br /><br />[[+sname]] - Název portálu, <br />[[+saddr]] - E-mailová adresa portálu, <br />[[+surl]] - URL adresa portálu, <br />[[+uid]] - Jméno nebo ID uživatele, <br />[[+pwd]] - Heslo uživatele, <br />[[+ufn]] - Celé jméno uživatele. <br /><br /><strong>Ponechte placeholdery [[+uid]] a [[+pwd]] v e-mailu nebo nebude uživatelské jméno a heslo obsaženo v e-mailu a uživatel nebude znát své uživatelské jméno a heslo!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Dobrý den [[+uid]] \n\nZde jsou Vaše přihlašovací údaje pro portál [[+sname]]:\n\nUživatelské jméno: [[+uid]]\nHeslo: [[+pwd]]\n\nJakmile se přihlásíte na [[+sname]] ([[+surl]]) můžete si změnit své heslo.\n\nS pozdravem,\nadministrátor portálu.';

$_lang['setting_welcome_screen'] = 'Zobrazit uvítací obrazovku';
$_lang['setting_welcome_screen_desc'] = 'Je-li nastaveno na "Ano", uvítací obrazovka se zobrazí při dalším načtení úvodní stránky a pak se již nezobrazí.';

$_lang['setting_welcome_screen_url'] = 'URL uvítací obrazovky';
$_lang['setting_welcome_screen_url_desc'] = 'URL uvítací obrazovky, která se zobrazí po prvním přihlášení do správce obsahu.';

$_lang['setting_welcome_action'] = 'Akce úvodní stránky';
$_lang['setting_welcome_action_desc'] = 'Výchozí kontroler, který se má načíst při vstupu do manageru, pokud není explicitně určen v URL.';

$_lang['setting_welcome_namespace'] = 'Jmenný prostor úvodní stránky';
$_lang['setting_welcome_namespace_desc'] = 'Jmenný prostor, do kterého patří akce pro úvodní stránku.';

$_lang['setting_which_editor'] = 'Výchozí editor';
$_lang['setting_which_editor_desc'] = 'Zde můžete nastavit, který editor chcete používat. Další editory je možno stáhnout a nainstalovat ze stránky MODX download.';

$_lang['setting_which_element_editor'] = 'Výchozí editor pro elementy';
$_lang['setting_which_element_editor_desc'] = 'Zde můžete nastavit, který WYSIWYG editor chcete používat pro editování elementů. Další WYSIWYG editory je možno stáhnout a nainstalovat pomocí správce balíčků.';

$_lang['setting_xhtml_urls'] = 'XHTML URLs';
$_lang['setting_xhtml_urls_desc'] = 'Pokud je nastaveno na Ano, všechny odkazy, které generuje MODX budou v souladu s xHTML včetně zakódování ampersandů.';

$_lang['setting_default_context'] = 'Výchozí kontext';
$_lang['setting_default_context_desc'] = 'Zvolte jaký kontext má být předvybraný při vytváření nového dokumentu.';

$_lang['setting_auto_isfolder'] = 'Automaticky označit dokument jako složkou';
$_lang['setting_auto_isfolder_desc'] = 'Pokud Ano, tak bude automaticky zaškrtnut parametr Složka.';

$_lang['setting_default_username'] = 'Výchozí uživatelské jméno';
$_lang['setting_default_username_desc'] = 'Výchozí uživatelské jméno pro nepřihlášeného uživatele.';

$_lang['setting_manager_use_fullname'] = 'V záhlaví manageru zobrazovat celé jméno uživatele ';
$_lang['setting_manager_use_fullname_desc'] = 'Pokud je nastavena na hodnotu Ano, obsah pole "Celé jméno" z uživatelova profilu bude zobrazeno namísto "Uživatelského jména" v záhlaví manageru';

$_lang['setting_log_snippet_not_found'] = 'Log snippets not found';
$_lang['setting_log_snippet_not_found_desc'] = 'Při zapnutí budou volané snippety, které neexistují, zaznamenány do protokolu chyb.';

$_lang['setting_error_log_filename'] = 'Název souboru protokolu chyb';
$_lang['setting_error_log_filename_desc'] = 'Přizpůsobte si název souboru protokolu chyb MODX (včetně přípony souboru).';

$_lang['setting_error_log_filepath'] = 'Cesta k protokolu chyb';
$_lang['setting_error_log_filepath_desc'] = 'Volitelně nastavit absolutní cestu umístění protokolu chyb. Můžete použít placehodery jako např. {cache_path}.';

<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Område';
$_lang['area_authentication'] = 'Autentisering och säkerhet';
$_lang['area_caching'] = 'Cachning';
$_lang['area_core'] = 'Kärnkod';
$_lang['area_editor'] = 'Richtext-editor';
$_lang['area_file'] = 'Filsystem';
$_lang['area_filter'] = 'Filtrera efter område...';
$_lang['area_furls'] = 'Vänliga URL:er';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Lexikon och språk';
$_lang['area_mail'] = 'E-post';
$_lang['area_manager'] = 'Hanteraren';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session och cookie';
$_lang['area_lexicon_string'] = 'Områdets lexikonpost';
$_lang['area_lexicon_string_msg'] = 'Ange lexikonpostens nyckel för området här. Om det inte finns någon lexikonpost så kommer bara områdesnyckeln att visas.<br />Kärnområden: authentication, caching, file, furls, gateway, language, manager, session, site, system';
$_lang['area_site'] = 'Webbplats';
$_lang['area_system'] = 'System och server';
$_lang['areas'] = 'Områden';
$_lang['charset'] = 'Teckenuppsättning';
$_lang['country'] = 'Land';
$_lang['description_desc'] = 'En kort beskrivning av inställningen. Det här kan vara en lexikonpost baserad på nyckeln enligt formatet "setting_" + nyckel + "_desc".';
$_lang['key_desc'] = 'Inställningens nyckel. Den kommer att bli tillgänglig i ditt innehåll via platshållaren [[++nyckel]].';
$_lang['name_desc'] = 'Inställningens namn. Det här kan vara en lexikonpost baserad på nyckeln enligt formatet "setting_" + nyckel.';
$_lang['namespace'] = 'Namnrymd';
$_lang['namespace_desc'] = 'Den namnrymd som denna inställning är associerad med. Standardlexikonämnet kommer att laddas för denna namnrymd när inställningar hämtas.';
$_lang['namespace_filter'] = 'Filtrera efter namnrymd...';
$_lang['search_by_key'] = 'Sök på nyckel...';
$_lang['setting_create'] = 'Skapa ny inställning';
$_lang['setting_err'] = 'Kontrollera dina uppgifter i följande fält: ';
$_lang['setting_err_ae'] = 'Det finns redan en inställning med den nyckeln. Ange ett annat nyckelnamn.';
$_lang['setting_err_nf'] = 'Inställningen kunde inte hittas.';
$_lang['setting_err_ns'] = 'Inställningen är inte specificerad';
$_lang['setting_err_remove'] = 'Ett fel inträffade när inställningen skulle tas bort.';
$_lang['setting_err_save'] = 'Ett fel inträffade när inställningen skulle sparas.';
$_lang['setting_err_startint'] = 'Inställningar får inte börja med en siffra.';
$_lang['setting_err_invalid_document'] = 'Det finns inget dokument som har ID %d. Ange ett existerande dokument.';
$_lang['setting_remove'] = 'Ta bort inställning';
$_lang['setting_remove_confirm'] = 'Är du säker på att du vill ta bort den här inställningen? Det kan innebära att din MODX-installation slutar fungera.';
$_lang['setting_update'] = 'Uppdatera inställning';
$_lang['settings_after_install'] = 'Eftersom detta är en ny installation, måste du gå igenom dessa inställningar och ändra det du vill. När du är klar med kontrollen av alla inställningar, klicka på \'Spara\' för att uppdatera inställningsdatabasen.<br /><br />';
$_lang['settings_desc'] = 'Här gör du allmänna inställningar och konfigurationer för användargränssnittet i MODX hanterare, samt för hur din MODX-webbplats fungerar. Dubbelklicka i värdekolumnen för den inställning som du vill redigera för att göra ändringarna dynamiskt i rutnätet eller högerklicka på en inställning för att se fler val. Du kan också klicka på plustecknet för att få en beskrivning av inställningen';
$_lang['settings_furls'] = 'Vänliga URL:er';
$_lang['settings_misc'] = 'Övrigt';
$_lang['settings_site'] = 'Webbplats';
$_lang['settings_ui'] = 'Gränssnitt &amp; funktioner';
$_lang['settings_users'] = 'Användare';
$_lang['system_settings'] = 'Systeminställningar';
$_lang['usergroup'] = 'Användargrupp';

// user settings
$_lang['setting_access_category_enabled'] = 'Kontrollera åtkomst till kategorier';
$_lang['setting_access_category_enabled_desc'] = 'Använd den här inställningen för att aktivera eller inaktivera ACL-kontroller för kategorier (per kontext). <strong>Notera: Om inställningen sätts till "Nej" kommer ALLA åtkomsträttigheter för kategorier att ignoreras!</strong>';

$_lang['setting_access_context_enabled'] = 'Kontrollera åtkomst till kontexter';
$_lang['setting_access_context_enabled_desc'] = 'Använd den här inställningen för att aktivera eller inaktivera ACL-kontroller för kontexter. <strong>Notera: Om inställningen sätts till "Nej" kommer ALLA åtkomsträttigheter för kontexter att ignoreras. Inaktivera INTE den här för hela systemet eller för hanterarens kontext eftersom det betyder att du inaktiverar tillgången till hanteraren.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Kontrollera åtkomst till resursgrupper';
$_lang['setting_access_resource_group_enabled_desc'] = 'Använd den här inställningen för att aktivera eller inaktivera ACL-kontroller för resursgrupper (per kontext). <strong>Notera: Om den här inställningen sätts till "Nej" kommer ALLA åtkomsträttigheter för resursgrupper att ignoreras.</strong>';

$_lang['setting_allow_mgr_access'] = 'Tillgång till hanterarens gränssnitt';
$_lang['setting_allow_mgr_access_desc'] = 'Använd den här inställningen för att aktivera eller inaktivera tillgång till hanterarens gränssnitt. <strong>Notera: Om inställningen sätts till "Nej" kommer användaren att omdirigeras till hanterarens inloggningssida eller till webbplatsens startsida.</strong>';

$_lang['setting_failed_login'] = 'Misslyckade inloggningsförsök';
$_lang['setting_failed_login_desc'] = 'Här kan du ange hur många misslyckade inloggningsförsök som är tillåtet innan användaren blockeras.';

$_lang['setting_login_allowed_days'] = 'Tillåtna dagar';
$_lang['setting_login_allowed_days_desc'] = 'Välj vilka dagar denna användare får logga in.';

$_lang['setting_login_allowed_ip'] = 'Tillåtna IP-adresser';
$_lang['setting_login_allowed_ip_desc'] = 'Ange de IP-adresser som denna användare får logga in från. <strong>NOTERA: Separera flera IP-adresser med kommatecken (,).</strong>';

$_lang['setting_login_homepage'] = 'Startsida efter inloggning';
$_lang['setting_login_homepage_desc'] = 'Ange ID på det dokument som du vill skicka användaren till efter att hon eller han har loggat in. <strong>NOTERA: kontrollera att det ID du anger tillhör ett befintligt dokument, att det är publicerat och är tillgängligt för användaren!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Version på åtkomstpolicyschema';
$_lang['setting_access_policies_version_desc'] = 'Versionen på åtkomstpolicysystemet. ÄNDRA INTE.';

$_lang['setting_allow_forward_across_contexts'] = 'Tillåt vidarebefordran mellan kontexter';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Om denna sätts till "Ja" kan symlänkar och modX::sendForward() API-anrop vidarebefordra länkningar till resurser i andra kontexter.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Tillåt "Glömt lösenord?" på hanterarens inloggningssida';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Om denna sätts till "Nej" inaktiveras funktionaliteten för glömt lösenord på hanterarens inloggningssida.';

$_lang['setting_allow_tags_in_post'] = 'Tillåt taggar i POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Om denna sätts till "Nej" kommer alla POST-händelser i hanteraren att rensas från HTML-taggar. MODX rekommenderar att denna lämnas satt till "Nej" för andra kontexter än mgr, där den är satt till "Ja" som standard.';

$_lang['setting_allow_tv_eval'] = 'Inaktivera eval i TV-koppling';
$_lang['setting_allow_tv_eval_desc'] = 'Välj detta alternativ för att aktivera eller inaktivera eval i TV-kopplingar. Om det här alternativet sätts till Nej, kommer koden/värdet bara hanteras som vanlig text.';

$_lang['setting_anonymous_sessions'] = 'Anonyma sessioner';
$_lang['setting_anonymous_sessions_desc'] = 'Om denna inaktiveras så kommer bara autentiserade användare att ha tillgång till en PHP-session. Det här kan reducera overheaden för anonyma användare och deras belastning på webbplatsen om de inte behöver tillgång till en unik session. Om session_enabled är inaktiverad har denna inställning ingen effekt eftersom sessioner inte är tillgängliga.';

$_lang['setting_archive_with'] = 'Tvinga användning av PCLZip-arkiv';
$_lang['setting_archive_with_desc'] = 'Om denna sätts till "Ja" kommer PCLZip att användas för zip-filer istället för ZipArchive. Aktivera den här om du får extractTo-fel eller har problem med uppackning i pakethanteraren.';

$_lang['setting_auto_menuindex'] = 'Standardvärde för menyindexering';
$_lang['setting_auto_menuindex_desc'] = 'Välj "Ja" för att aktivera automatisk ökning av menyindex som standard.';

$_lang['setting_auto_check_pkg_updates'] = 'Automatisk sökning efter paketuppdateringar';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Om denna sätts till "Ja" kommer MODX att automatiskt söka efter uppdateringar för paket i pakethanteraren. Det här kan sakta ner laddningen av sidan.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Utgångstid för cachning av resultaten vid automatisk sökning efter paketuppdateringar';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Det antal minuter som pakethanteringen ska cacha resultaten vid sökande efter paketuppdateringar.';

$_lang['setting_allow_multiple_emails'] = 'Tillåt e-postdubbletter för användare';
$_lang['setting_allow_multiple_emails_desc'] = 'Om denna aktiveras så kan användare dela samma e-postadress.';

$_lang['setting_automatic_alias'] = 'Generera alias automatiskt';
$_lang['setting_automatic_alias_desc'] = 'Välj "Ja" för att låta systemet automatiskt skapa ett alias baserat på resursens titel när det sparas.';

$_lang['setting_base_help_url'] = 'Standard-URL för hjälp';
$_lang['setting_base_help_url_desc'] = 'Den standard-URL som ska användas för att bygga hjälplänkarna i det övre högra hörnet av sidor i hanteraren.';

$_lang['setting_blocked_minutes'] = 'Blockeringstid';
$_lang['setting_blocked_minutes_desc'] = 'Här kan du ange hur många minuter en användare blir blockerad efter att ha gjort för många misslyckade inloggningsförsök. Ange värdet som ett tal (inga kommatecken, mellanslag etc).';

$_lang['setting_cache_action_map'] = 'Aktivera cachning av händelsekartor';
$_lang['setting_cache_action_map_desc'] = 'När denna är aktiverad kommer händelser (eller controller-kartor) att cachas för att minska laddningstiderna i hanteraren.';

$_lang['setting_cache_alias_map'] = 'Aktivera cachning av kontexts aliaskarta';
$_lang['setting_cache_alias_map_desc'] = 'När denna är aktiverad cachas alla resurs-URI:er till kontexten. Aktivera på mindre webbplatser och inaktivera på större webbplatser för bättre prestande.';

$_lang['setting_use_context_resource_table'] = 'Använd resurstabellen för kontexter';
$_lang['setting_use_context_resource_table_desc'] = 'När denna är aktiverad kommer uppdateringar av kontexter att använda tabellen context_resource. Det här gör att du programmässigt kan ha en resurs i flera kontexter. Om du inte använder dessa resurskontexter via API:n så kan du sätta den här till false. På stora webbplatser kan du potentiellt få en prestandaökning i hanteraren då.';

$_lang['setting_cache_context_settings'] = 'Aktivera cachning av kontextinställningar';
$_lang['setting_cache_context_settings_desc'] = 'När denna är aktiverad kommer kontextinställningar att cachas för att minska laddningstider.';

$_lang['setting_cache_db'] = 'Aktivera databascache';
$_lang['setting_cache_db_desc'] = 'När denna är aktiverad, cachas objekt och obearbetade resultat från SQL-frågor, för att markant minska belastningen på databasen.';

$_lang['setting_cache_db_expires'] = 'Utgångstid för databas-cache';
$_lang['setting_cache_db_expires_desc'] = 'Detta värde (i sekunder) anger den tid som cachefiler varar för cachning av databasresultat.';

$_lang['setting_cache_db_session'] = 'Aktivera sessionscache för databasen';
$_lang['setting_cache_db_session_desc'] = 'När denna är aktiverad tillsammans med cache_db kommer databassessioner att cachas i databasens cache för resultatuppsättningar.';

$_lang['setting_cache_db_session_lifetime'] = 'Förfallotid för databasens sessionscache';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Det här värdet (i sekunder) anger den tid som cachefiler varar för sessionsposter i databasens cache för resultatuppsättningar.';

$_lang['setting_cache_default'] = 'Cachebara som standard';
$_lang['setting_cache_default_desc'] = 'Välj "Ja" för att göra alla nya resurser cachebara som standard.';
$_lang['setting_cache_default_err'] = 'Ange om du vill att dokument ska cachas som standard eller inte.';

$_lang['setting_cache_disabled'] = 'Avaktivera globala cachealternativ';
$_lang['setting_cache_disabled_desc'] = 'Välj "Ja" för att avaktivera alla MODX cachefunktioner. MODX rekommenderar inte att cachning avaktiveras.';
$_lang['setting_cache_disabled_err'] = 'Ange om du vill att cachen ska vara aktiverad eller inte.';

$_lang['setting_cache_expires'] = 'Utgångstid för standardcache';
$_lang['setting_cache_expires_desc'] = 'Detta värde (i sekunder) anger den tid som cache-filer varar för standardcachning.';

$_lang['setting_cache_format'] = 'Cacheformat';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serialisera. Ett av formaten';

$_lang['setting_cache_handler'] = 'Klass för cache-hantering';
$_lang['setting_cache_handler_desc'] = 'Klassnamnet på den typhanterare som ska användas för cachning.';

$_lang['setting_cache_lang_js'] = 'Cacha lexikonsträngar för javascript';
$_lang['setting_cache_lang_js_desc'] = 'Om denna sätts till "Ja" kommer server-headers att användas för att cacha lexikonsträngarna som laddas till javascript i hanterarens gränssnitt.';

$_lang['setting_cache_lexicon_topics'] = 'Cacha lexikonämnen';
$_lang['setting_cache_lexicon_topics_desc'] = 'När denna är aktiverad cachas alla lexikonämnen för att reducera laddningstider för internationaliseringsfunktionalitet. MODX rekommenderar starkt att lämna denna satt till "Ja".';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Cacha lexikonämnen utanför kärnan';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Om denna inaktiveras kommer lexikonämnen som inte hör till kärnan inte att cachas. Det här är användbart att inaktivera när du utvecklar dina egna Extras.';

$_lang['setting_cache_resource'] = 'Aktivera partiell dokumentcache';
$_lang['setting_cache_resource_desc'] = 'När denna är aktiverad kan man konfigurera partiell dokumentcache per dokument. Om denna inställning avaktiveras blir den avaktiverad globalt.';

$_lang['setting_cache_resource_expires'] = 'Utgångstid för partiell resurscache';
$_lang['setting_cache_resource_expires_desc'] = 'Detta värde (i sekunder) anger den tid som cachefiler varar för partiell resurscachning.';

$_lang['setting_cache_scripts'] = 'Aktivera script-cache';
$_lang['setting_cache_scripts_desc'] = 'När denna är aktiverad kommer MODX att cacha alla script (snippets och plugins) till fil för att reducera laddningstider. MODX rekommenderar att denna lämnas satt till "Ja".';

$_lang['setting_cache_system_settings'] = 'Aktivera cachning av systeminställningar';
$_lang['setting_cache_system_settings_desc'] = 'När denna är aktiverad kommer systeminställningar att cachas för att minska laddningstider. MODX rekommenderar att denna lämnas aktiverad.';

$_lang['setting_clear_cache_refresh_trees'] = 'Uppdatera träd efter rensning av webbplatsens cache';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'När den här är aktiverad kommer träden att uppdateras när webbplatsens cache rensas.';

$_lang['setting_compress_css'] = 'Använd komprimerad CSS';
$_lang['setting_compress_css_desc'] = 'När denna är aktiverad kommer MODX att använda en komprimerad version av sina CSS-stilmallar i hanterarens gränssnitt.';

$_lang['setting_compress_js'] = 'Använd komprimerade javascript-bibliotek';
$_lang['setting_compress_js_desc'] = 'När denna är aktiverad kommer MODX att använda en komprimerad version av skriptfilerna.';

$_lang['setting_compress_js_groups'] = 'Använd gruppering när javascript komprimeras';
$_lang['setting_compress_js_groups_desc'] = 'Gruppera javascript för MODX hanterares kärna genom att använda groupsConfig i Minify. Sätt till "Ja" om du använder Suhosin eller andra begränsande faktorer.';

$_lang['setting_compress_js_max_files'] = 'Maximalt antal javascript-filer vid komprimering';
$_lang['setting_compress_js_max_files_desc'] = 'Det maximala antalet javascript-filer som MODX kommer att försöka komprimera på en gång när compress_js är aktiverad. Sätt till ett lägre antal om du får problem med Google Minify i hanteraren.';

$_lang['setting_concat_js'] = 'Använd sammanfogade javascript-bibliotek';
$_lang['setting_concat_js_desc'] = 'När denna är aktiverad kommer MODX att använda en sammanfogad version av sina javascript-bibliotek i hanterarens gränssnitt. Detta minskar laddnings- och exekveringstiden i hanteraren ordentligt. Avaktivera bara om du modifierar element i kärnan.';

$_lang['setting_confirm_navigation'] = 'Bekräfta navigering vid osparade ändringar';
$_lang['setting_confirm_navigation_desc'] = 'När denna är aktiverad uppmanas användaren att bekräfta sin avsikt att lämna sidan om det finns osparade ändringar.';

$_lang['setting_container_suffix'] = 'Behållarsuffix';
$_lang['setting_container_suffix_desc'] = 'Det suffix som ska läggas till resurser som är angivna som behållare när vänliga URL:er används.';

$_lang['setting_context_tree_sort'] = 'Aktivera sortering av kontexter i resursträdet';
$_lang['setting_context_tree_sort_desc'] = 'Om denna sätts till "Ja" kommer kontexter att sorteras alfanumeriskt i resursträdet till vänster i hanteraren.';
$_lang['setting_context_tree_sortby'] = 'Sorteringsfält för kontexter i resursträdet';
$_lang['setting_context_tree_sortby_desc'] = 'Det fält som kontexter i resursträdet sorteras efter om sortering är aktiverad.';
$_lang['setting_context_tree_sortdir'] = 'Sorteringsriktning för kontexter i resursträdet';
$_lang['setting_context_tree_sortdir_desc'] = 'Den riktning som kontexter i resursträdet kommer att sorteras i om sortering är aktiverad.';

$_lang['setting_cultureKey'] = 'Språk';
$_lang['setting_cultureKey_desc'] = 'Välj språk för alla kontexter utanför hanteraren, inklusive web.';

$_lang['setting_date_timezone'] = 'Standardtidszon';
$_lang['setting_date_timezone_desc'] = 'Om en tidszon anges används den som standardtidszon för PHP:s datumfunktioner. Om inställningen lämnas tom och om PHP:s ini-inställning date.timezone inte är satt i din miljö så kommer UTC att användas.';

$_lang['setting_debug'] = 'Debuggning';
$_lang['setting_debug_desc'] = 'Aktiverar/inaktiverar debuggning i MODX och/eller sätter nivån på PHP:s error_reporting. "" = använd aktuell error_reporting, "0" = false (error_reporting = 0), "1" = true (error_reporting = -1) eller annat giltigtvärde för error_reporting (som ett heltal).';

$_lang['setting_default_content_type'] = 'Standardtyp för innehåll';
$_lang['setting_default_content_type_desc'] = 'Välj den innehållstyp som ska användas som standard för nya resurser. Du kan alltid välja en annan innehållstyp i resursredigeraren. Denna inställning förväljer bara en innehållstyp åt dig.';

$_lang['setting_default_duplicate_publish_option'] = 'Standardval för publiceringsstatus på duplicerade resurser';
$_lang['setting_default_duplicate_publish_option_desc'] = 'Den valda standardinställningen när resurser dupliceras. Kan vara antingen "avpublicera" för att avpublicera alla dubbletter, "publicera" för att publicera alla dubbletter eller "behåll" för att behåll publiceringsstatusen baserat på den dubblerade resursen.';

$_lang['setting_default_media_source'] = 'Standardmediakälla';
$_lang['setting_default_media_source_desc'] = 'Den mediakälla som ska laddas som standard.';

$_lang['setting_default_template'] = 'Standardmall';
$_lang['setting_default_template_desc'] = 'Välj den standarmall du vill använda för nya resurser. Du kan fortfarande välja en annan mall när du redigerar resursen. Denna inställning är bara förvalet.';

$_lang['setting_default_per_page'] = 'Antal per sida';
$_lang['setting_default_per_page_desc'] = 'Det antal resultat som visas i rutnät som standard.';

$_lang['setting_editor_css_path'] = 'Sökväg till CSS-fil';
$_lang['setting_editor_css_path_desc'] = 'Skriv in sökvägen till den CSS-fil du vill använda i en richtext-editor. Det bästa sättet att ange sökvägen är att göra det från serverns rot, tex /assets/site/style.css. Lämna fältet tomt om du inte vill ladda en stilmall i en richtext-editor.';

$_lang['setting_editor_css_selectors'] = 'CSS-selektorer för editor';
$_lang['setting_editor_css_selectors_desc'] = 'En kommaseparerad lista med CSS-selektorer för en richtext-editor.';

$_lang['setting_emailsender'] = 'Avsändaradress i registreringsmeddelande';
$_lang['setting_emailsender_desc'] = 'Här kan du ange e-postadressen som används för att skicka användarnamn och lösenord till en användare.';
$_lang['setting_emailsender_err'] = 'Ange e-postadressen för administrationen.';

$_lang['setting_emailsubject'] = 'Ämne i registreringsmeddelande';
$_lang['setting_emailsubject_desc'] = 'Här kan du ange ämnet för e-posten som skickas när en användare är registrerad.';
$_lang['setting_emailsubject_err'] = 'Ange ett ärende för e-posten som skickas vi registrering.';

$_lang['setting_enable_dragdrop'] = 'Aktivera dra-och-släpp i resurs/elementträden';
$_lang['setting_enable_dragdrop_desc'] = 'Om denna sätts till "Nej" hindras dra-och-släpp i resurs- och elementträden.';

$_lang['setting_error_page'] = 'Felsida';
$_lang['setting_error_page_desc'] = 'Skriv in ID till den sida du vill skicka användare till om de försöker komma åt ett dokument som inte finns (404 Page Not Found).<br /><strong>OBS: Se till att detta ID tillhör ett existerande dokument, och att det har blivit publicerat!</strong>';
$_lang['setting_error_page_err'] = 'Ange ett dokument-ID för felsidan.';

$_lang['setting_ext_debug'] = 'ExtJS debug';
$_lang['setting_ext_debug_desc'] = 'Anger om ext-all-debug.js ska laddas eller inte för att hjälpa dig felsöka din ExtJS-kod.';

$_lang['setting_extension_packages'] = 'Tilläggspaket';
$_lang['setting_extension_packages_desc'] = 'En JSON-lista med paket som ska laddas när MODX instansieras. Ska var i formatet [{"packagename":{"path":"path/to/package"}},{"anotherpackagename":{"path":"path/to/otherpackage"}}]';

$_lang['setting_enable_gravatar'] = 'Aktivera Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'Om den här aktiveras kommer Gravatar att användas som profilbild (om användaren inte har en uppladdad profilbild).';

$_lang['setting_failed_login_attempts'] = 'Misslyckade inloggningsförsök';
$_lang['setting_failed_login_attempts_desc'] = 'Antalet misslyckade inloggningsförsök en användare kan göra innan den blir "blockerad".';

$_lang['setting_fe_editor_lang'] = 'Editorns språk';
$_lang['setting_fe_editor_lang_desc'] = 'Här kan du ange språk för editorn som används.';

$_lang['setting_feed_modx_news'] = 'URL för MODX nyhetsflöde';
$_lang['setting_feed_modx_news_desc'] = 'Ange URL:en till RSS-flödet för MODX nyhetspanel i hanteraren.';

$_lang['setting_feed_modx_news_enabled'] = 'MODX nyhetsflöde aktiverat';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Om denna sätts till "Nej" kommer MODX att dölja nyhetsflödet på hanterarens välkomstsida.';

$_lang['setting_feed_modx_security'] = 'URL för MODX flöde för säkerhetsnotiser';
$_lang['setting_feed_modx_security_desc'] = 'Ange URL:en till RSS-flödet för MODX säkerhetsnotispanel i hanteraren.';

$_lang['setting_feed_modx_security_enabled'] = 'MODX flöde för säkerhetsnotiser aktiverat';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Om denna sätts till "Nej"  kommer MODX att dölja flödet för säkerhetsnotiser på hanterarens välkomstsida.';

$_lang['setting_filemanager_path'] = 'Sökväg till filhanteraren (Föråldrad)';
$_lang['setting_filemanager_path_desc'] = 'Föråldrad - använd mediakällor istället. IIS fyller oftast inte i inställningarna för document_root ordentligt, vilket används av filhanteraren för att bestämma vad du får se. Om du har problem med filhanteraren, se till så att denna katalog pekar till roten på din installation av MODX.';

$_lang['setting_filemanager_path_relative'] = 'Är filhanterarens sökväg relativ? (Föråldrad)';
$_lang['setting_filemanager_path_relative_desc'] = 'Föråldrad - använd mediakällor istället. Om din inställning för filemanager_path (Sökväg till filhanteraren) är relativ i förhållande till MODX base_path väljer du "Ja" här. Om din filemanager_path ligger utanför dokumentroten väljer du "Nej".';

$_lang['setting_filemanager_url'] = 'Filhanterarens URL (Föråldrad)';
$_lang['setting_filemanager_url_desc'] = 'Föråldrad - använd mediakällor istället. Valfri. Använd den här inställningen om du vill ange en uttrycklig URL för att komma åt filerna i MODX filhanterare (användbart om du har ändrat manager_path till en sökväg utanför MODX webbrot). Kontrollera att detta är den webbåtkomliga URL:en av det angivna värdet för filemanager_path. Om fältet lämnas tomt kommer MODX att försöka beräkna URL:en automatiskt.';

$_lang['setting_filemanager_url_relative'] = 'Är filhanterarens URL relativ? (Föråldrad)';
$_lang['setting_filemanager_url_relative_desc'] = 'Föråldrad - använd mediakällor istället. Om din inställning för filemanager_url (Filhanterarens URL) är relativ i förhållande till MODX base_url väljer du "Ja" här. Om din filemanager_url ligger utanför den huvudsakliga webbroten väljer du "Nej".';

$_lang['setting_forgot_login_email'] = 'E-post vid bortglömda inloggningsuppgifter';
$_lang['setting_forgot_login_email_desc'] = 'Mallen för det e-postmeddelande som skickas när en användare har glömt sitt användarnamn och/eller sitt lösenord till MODX.';

$_lang['setting_form_customization_use_all_groups'] = 'Använd alla medlemskap i användargrupper för formuläranpassning';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Om denna sätts till "Ja" kommer formuläranpassningen att använda *alla* set för *alla* användargrupper som en användare är medlem i när formuläranpassningsset tillämpas. I annat fall kommer bara det set som hör till användarens primära grupp att användas. Notera: Om denna sätts till "Ja" kan det orsaka buggar på grund av motstridiga formuläranpassningsset.';

$_lang['setting_forward_merge_excludes'] = 'Undantagsfält för sammanslagning vid vidarebefordran';
$_lang['setting_forward_merge_excludes_desc'] = 'En symlänks värden i ifyllda fält "skriver över" motsvarande värden i målresursen. Genom att använda denna kommaavgränsade lista med undantag, så förhindras de angivna fälten från att "skrivas över" av symlänken.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Gemena FURL-alias';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Anger om enbart gemena tecken tillåts i resursalias.';

$_lang['setting_friendly_alias_max_length'] = 'Maximal längd på FURL-alias';
$_lang['setting_friendly_alias_max_length_desc'] = 'Om större än noll, det maximala antalet tecken som tillåts i ett resursalias. Noll är det samma som obegränsat.';

$_lang['setting_friendly_alias_realtime'] = 'FURL-alias i realtid';
$_lang['setting_friendly_alias_realtime_desc'] = 'Anger om ett resursalias ska skapas i farten när man skriver sidtiteln eller om det ska skapas när resursen sparas (automatic_alias måste vara aktiverat för att det här ska hända).';

$_lang['setting_friendly_alias_restrict_chars'] = 'Metod för teckenbegränsning i FURL-alias';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'Den metod som ska användas för att begränsa antalet tecken i ett resursalias. "pattern" tillåter att ett RegEx anges, "legal" tillåter bara giltiga tecken för URL:er, "alpha" tillåter bara bokstäver från alfabetet och "alphanumeric" tillåter bara bokstäver och siffror.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Mönster för begränsning av tecken i FURL-alias';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Ett giltigt RegEx som ska användas för att begränsa vilka tecken som får användas i ett resursalias.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Rensa elementtaggar från FURL-alias';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Anger om elementtaggar ska rensas bort från ett resursalias.';

$_lang['setting_friendly_alias_translit'] = 'Translitterationsmetod för FURL-alias';
$_lang['setting_friendly_alias_translit_desc'] = 'Den translitterationsmetod som ska användas på ett alias för en resurs. Standardinställningen är tomt eller "ingen" vilket hoppar över translitteration. Andra möjliga värden är "iconv" (om tillgänglig) eller en namngiven translitterationstabell tillhandahållen av en anpassad serviceklass för translitteration.';

$_lang['setting_friendly_alias_translit_class'] = 'Translitterationsklass för FURL-alias';
$_lang['setting_friendly_alias_translit_class_desc'] = 'En valfri serviceklass som tillhandahåller namngivna translitterationstjänster för generering/filtrering av FURL-alias.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Sökväg till transliterationsklass för FURL-alias';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'Den plats för modellpaket som transliterationsklassen för FURL-alias kommer att laddas från.';

$_lang['setting_friendly_alias_trim_chars'] = 'Rensningstecken i FURL-alias';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Tecken som ska rensas bort från slutet på ett givet resursalias.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Föredragen ordavgränsare för FURL-alias';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Den föredragna avgränsaren mellan ord i vänliga URL:er.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Ordavgränsare för FURL-alias';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Tecken som representerar avgränsare mellan ord när delar i resursalias processas. Dessa tecken kommer att konverteras och konsolideras till den föredragna avgränsaren mellan ord i resursalias.';

$_lang['setting_friendly_urls'] = 'Använd vänliga URL:er';
$_lang['setting_friendly_urls_desc'] = 'Detta låter dig använda adresser som är vänliga mot sökmotorer. Notera att detta endast fungerar när MODX körs på Apache, och du måste skriva en .htaccess-fil för att det ska fungera. Se .htaccess-filen som följde med i distributionen för mer information.';
$_lang['setting_friendly_urls_err'] = 'Ange om du vill använda vänliga URL:er eller inte.';

$_lang['setting_friendly_urls_strict'] = 'Använd strikta vänliga URL:er';
$_lang['setting_friendly_urls_strict_desc'] = 'När vänliga URL:er är aktiverade kommer detta alternativ att tvinga ickenormaliserade anrop som matchar en resurs att 301-hänvisas till den normaliserade URI:n för den resursen. VARNING: Aktivera inte den här om du använder anpassade omskrivningsregler som inte matchar åtminstone början på den normaliserade URI:n. Till exempel, en normaliserad URI som foo/ med anpassade omskrivningar för foo/bar.html skulle fungera, men försök att omskriva bar/foo.html som foo/ skulle tvinga en omdirigering till foo/ med detta alternativ aktiverat.';

$_lang['setting_global_duplicate_uri_check'] = 'Kontrollera URI-dubbletter i alla kontexter';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Om du väljer "Ja" kommer kontroller av URI-dubbletter att inkludera alla kontexter. Väljer du "Nej" görs kontrollen bara i den kontext som resursen sparas i.';

$_lang['setting_hidemenu_default'] = 'Dölj i menyer som standard';
$_lang['setting_hidemenu_default_desc'] = 'Välj "Ja" för att dölja alla nya resurser i menyer som standard.';

$_lang['setting_inline_help'] = 'Visa hjälptexter för fält inline';
$_lang['setting_inline_help_desc'] = 'Om denna sätts till "Ja" kommer hjälptexten för fält att visas direkt nedanför fältet. Om den sätts till "Nej" kommer alla fält att visa hjälptexten som verktygstips.';

$_lang['setting_link_tag_scheme'] = 'Schema för att skapa URL';
$_lang['setting_link_tag_scheme_desc'] = 'Schema för URL-skapande för taggen [[~id]]. Tillgängliga alternativ <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\\modX::makeUrl()">här</a>.';

$_lang['setting_locale'] = 'Systemspråk';
$_lang['setting_locale_desc'] = 'Anger språket (locale) för systemet. Lämna fältet tomt för att använda standardinställningen. Se <a href="http://php.net/setlocale" target="_blank">PHP-dokumentationen</a> för mer information.';

$_lang['setting_lock_ttl'] = 'Livstid för lås';
$_lang['setting_lock_ttl_desc'] = 'Det antal sekunder som ett lås på en resurs kommer att vara kvar om användaren är inaktiv.';

$_lang['setting_log_level'] = 'Loggningsnivå';
$_lang['setting_log_level_desc'] = 'Standardnivån för loggning. Ju lägre nivå desto färre meddelanden loggas. Tillgängliga val: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO) och 4 (DEBUG).';

$_lang['setting_log_target'] = 'Loggningsmål';
$_lang['setting_log_target_desc'] = 'Standardmålet till vilket loggningsmeddelanden skrivs. Tillgängliga val: "FILE", "HTML" eller "ECHO". Standardvalet är "FILE" om inget annat anges.';

$_lang['setting_mail_charset'] = 'Teckenkodning för e-post';
$_lang['setting_mail_charset_desc'] = 'Standardteckenkodningen för e-post, tex "iso-8859-1" eller "utf-8"';

$_lang['setting_mail_encoding'] = 'E-postkodning';
$_lang['setting_mail_encoding_desc'] = 'Anger kodningen för e-postmeddelanden. Möjliga värden är "8bit", "7bit", "binary", "base64" och "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Använd SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Om denna sätts till "Ja" kommer MODX att försöka använda SMTP i mail-funktioner.';

$_lang['setting_mail_smtp_auth'] = 'SMTP-autentisering';
$_lang['setting_mail_smtp_auth_desc'] = 'Anger SMTP-autentisering. Använder inställningarna för mail_smtp_user och mail_smtp_pass.';

$_lang['setting_mail_smtp_helo'] = 'SMTP Helo-meddelande';
$_lang['setting_mail_smtp_helo_desc'] = 'Anger meddelandet för SMTP HELO (värdnamnet används som standard).';

$_lang['setting_mail_smtp_hosts'] = 'SMTP-värdar';
$_lang['setting_mail_smtp_hosts_desc'] = 'Anger SMTP-värdar. Alla värdar måste separeras med semikolon. Du kan ange en separat port för varje värd genom att använda följande format: [värdnamn:port] (tex "smtp1.example.com:25;smtp2.example.com"). Värdarna kommer att provas i den ordning de angivits.';

$_lang['setting_mail_smtp_keepalive'] = 'Håll SMTP vid liv';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Förhindrar att SMTP-anslutningen stängs efter varje e-postsändning. Rekommenderas inte.';

$_lang['setting_mail_smtp_pass'] = 'SMTP-lösenord';
$_lang['setting_mail_smtp_pass_desc'] = 'Lösenordet som ska användas för att autentisera mot SMTP.';

$_lang['setting_mail_smtp_port'] = 'SMTP-port';
$_lang['setting_mail_smtp_port_desc'] = 'Anger SMTP-serverns standardport.';

$_lang['setting_mail_smtp_prefix'] = 'Anslutningsprefix för SMTP';
$_lang['setting_mail_smtp_prefix_desc'] = 'Anger anslutningsprefixet. Möjliga värden är "", "ssl" eller "tls".';

$_lang['setting_mail_smtp_single_to'] = 'SMTP individuella meddelanden';
$_lang['setting_mail_smtp_single_to_desc'] = 'Ger möjligheten att låta till-fältet processas som individuella meddelanden istället för att sända till hela TO-adressen.';

$_lang['setting_mail_smtp_timeout'] = 'SMTP-timeout';
$_lang['setting_mail_smtp_timeout_desc'] = 'Anger SMTP-serverns timeout i sekunder. Denna funktion fungerar inte på win32-servrar.';

$_lang['setting_mail_smtp_user'] = 'SMTP-användare';
$_lang['setting_mail_smtp_user_desc'] = 'Användaren som ska autentiseras mot SMTP.';

$_lang['setting_main_nav_parent'] = 'Huvudmenyns förälder';
$_lang['setting_main_nav_parent_desc'] = 'Den behållare som används för att hämta alla uppgifter till huvudmenyn.';

$_lang['setting_manager_direction'] = 'Textriktning i hanteraren';
$_lang['setting_manager_direction_desc'] = 'Välj textriktning i hanteraren: antingen vänster-till-höger eller höger-till-vänster.';

$_lang['setting_manager_date_format'] = 'Hanterarens datumformat';
$_lang['setting_manager_date_format_desc'] = 'Formateringssträngen, i PHP:s date()-format, för datum som visas i hanteraren.';

$_lang['setting_manager_favicon_url'] = 'URL för hanterarens favicon';
$_lang['setting_manager_favicon_url_desc'] = 'Om du anger en URL här kommer den att laddas som hanteraren favicon. Måste vara en relativ URL i förhållande till katalogen /manager eller en absolut URL.';

$_lang['setting_manager_js_cache_file_locking'] = 'Aktivera fillåsning för hanterarens JS/CSS-cache';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Fillåsning för cachen. Sätt till "Nej" om filsystemet är NFS.';
$_lang['setting_manager_js_cache_max_age'] = 'Livslängd för hanterarens cachning av komprimerad JS/CSS';
$_lang['setting_manager_js_cache_max_age_desc'] = 'Maximal livslängd i webbläsarens cache av komprimerad JS/CSS i hanteraren i sekunder. När tiden gått ut kommer webbläsaren att skicka en ny villkorad GET. Använd längre tidsperiod för lägre trafik.';
$_lang['setting_manager_js_document_root'] = 'Dokumentrot för komprimering av JS/CSS i hanteraren';
$_lang['setting_manager_js_document_root_desc'] = 'Om din server inte kan hantera servervariabeln DOCUMENT_ROOT anger du den uttryckligen här för att göra det möjligt att komprimera hanterarens JS/CSS. Ändra inte den här om du inte vet vad du håller på med.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Aktivera zlib utdatakompression för hanterarens JS/CSS';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Anger om zlib utdatakompression för komprimerad JS/CSS i hanteraren är aktiverad eller inte. Aktivera inte om du inte är säker på att PHP:s konfigurationsvariabel zlib.output_compression kan sättas till 1. MODX rekommenderar att den lämnas inaktiverad.';

$_lang['setting_manager_lang_attribute'] = 'Hanterarens språkattribut för HTML och XML';
$_lang['setting_manager_lang_attribute_desc'] = 'Ange den språkkod som bäst överensstämmer med din språkinställning för hanteraren. Det här säkerställer att webbläsare kan presentera innehållet i det bästa formatet för dig.';

$_lang['setting_manager_language'] = 'Hanterarens språk';
$_lang['setting_manager_language_desc'] = 'Välj vilket språket du vill använda i MODX publiceringshanterare.';

$_lang['setting_manager_login_url_alternate'] = 'Alternativ URL till hanterarens inloggning';
$_lang['setting_manager_login_url_alternate_desc'] = 'En alternativ URL som oautentiserade användare skickas till när de behöver logga in i hanteraren. Inloggningsformuläret där måste logga in användaren till mgr-kontexten för att det ska fungera.';

$_lang['setting_manager_login_start'] = 'Startsida efter inloggning i hanteraren';
$_lang['setting_manager_login_start_desc'] = 'Ange ID för det dokument du vill att användaren ska komma till när den loggat in i hanteraren.<br /><strong>Notera: Kontrollera att det ID du valt hör till ett existerande dokument, att det är publicerat och att användaren har behörighet för det!</strong>';

$_lang['setting_manager_theme'] = 'Tema för hanteraren';
$_lang['setting_manager_theme_desc'] = 'Välj tema för innehållshanteraren.';

$_lang['setting_manager_time_format'] = 'Hanterarens tidsformat';
$_lang['setting_manager_time_format_desc'] = 'Formateringssträngen, i PHP:s date()-format, för tidsinställningarna som finns i hanteraren.';

$_lang['setting_manager_use_tabs'] = 'Använd flikar i hanterarens layout';
$_lang['setting_manager_use_tabs_desc'] = 'Om denna aktiveras kommer hanteraren att använda flikar för att visa de olika panelerna. I annat fall kommer portaler att användas.';

$_lang['setting_manager_week_start'] = 'Veckostart';
$_lang['setting_manager_week_start_desc'] = 'Ange den dag som inleder en vecka. Använd 0 (eller lämna tom) för söndag eller 1 för måndag och så vidare...';

$_lang['setting_mgr_tree_icon_context'] = 'Ikon för kontextträd';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Ange en CSS-klass här som ska användas för att visa kontextikonen i trädet. Du kan använda den här inställningen för varje kontext för att anpassa ikonen per kontext.';

$_lang['setting_mgr_source_icon'] = 'Ikon för mediakälla';
$_lang['setting_mgr_source_icon_desc'] = 'Ange en CSS-klass som ska användas för att visa mediakällans ikoner i filträdet. Standard är "icon-folder-open-o".';

$_lang['setting_modRequest.class'] = 'Anropshanterarens klass';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Dölj filer i mediaträdet';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'Om denna sätts till "Ja" kommer filer som ligger i mappar inte att visas i medialäsarens träd. Standard är "Nej".';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Dölj bildbubblor i mediaträdet';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'Om denna sätts till "Ja" kommer inte bilder att förhandsvisas i bildbubblor när man håller muspekaren över en fil i mediaträdet. Standard är "Ja".';

$_lang['setting_modx_browser_default_sort'] = 'Standardsortering i filutforskare';
$_lang['setting_modx_browser_default_sort_desc'] = 'Den sorteringsmetod som ska användas som standard när popup-filutforskaren används i hanteraren. Tillgängliga värden är: name (namn), size (storlek), lastmod (senast modifierad).';

$_lang['setting_modx_browser_default_viewmode'] = 'Standardvisning i filhanteraren';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'Anger hur filer ska visas som standard i hanterarens popup-filhanterare. Tillgängliga val: rutnät, lista.';

$_lang['setting_modx_charset'] = 'Teckenkodning';
$_lang['setting_modx_charset_desc'] = 'Välj den teckenkodning du vill använda. Notera att MODX har testats med ett antal av dessa kodningar, men inte alla. För de flesta språk är standardalternativet UTF-8 att föredra.</b>';

$_lang['setting_new_file_permissions'] = 'Behörigheter för nya filer';
$_lang['setting_new_file_permissions_desc'] = 'När en ny fil laddas upp med Filhanteraren, kommer Filhanteraren att försöka ändra filbehörigheterna till dom som anges i denna inställning. Det här kanske inte fungerar på alla system, tex IIS, i vilket fall du blir tvungen att ändra behörigheterna manuellt.';

$_lang['setting_new_folder_permissions'] = 'Behörigheter för nya kataloger';
$_lang['setting_new_folder_permissions_desc'] = 'När en ny katalog skapas i Filhanteraren, kommer Filhanteraren att försöka ändra katalogbehörigheterna till dom som anges i denna inställning. Det här kanske inte fungerar på alla system, tex IIS, i vilket fall du blir tvungen att ändra behörigheterna manuellt.';

$_lang['setting_parser_recurse_uncacheable'] = 'Fördröj o-cachebar tolkning';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'Om den här inaktiveras kan o-cachebara element bli cachade inuti cachebara element. Inaktivera BARA om du har problem med komplex kapslad tolkning som slutat fungera som förväntat.';

$_lang['setting_password_generated_length'] = 'Längd på automatgenererat lösenord';
$_lang['setting_password_generated_length_desc'] = 'Längden på ett automatgenererat lösenord för en användare.';

$_lang['setting_password_min_length'] = 'Minimal längd för lösenord';
$_lang['setting_password_min_length_desc'] = 'Den minimala längden på en användares lösenord.';

$_lang['setting_preserve_menuindex'] = 'Bevara menyindex när resurser dupliceras';
$_lang['setting_preserve_menuindex_desc'] = 'När resurser dupliceras kommer menyindexet/ordningen att bevaras.';

$_lang['setting_principal_targets'] = 'ACL-mål att ladda';
$_lang['setting_principal_targets_desc'] = 'Anpassa ACL-målen som ska laddas för MODX-användare.';

$_lang['setting_proxy_auth_type'] = 'Autentiseringstyp för proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Stödjer antingen BASIC eller NTLM.';

$_lang['setting_proxy_host'] = 'Värd för proxy';
$_lang['setting_proxy_host_desc'] = 'Om din server använder en proxy anger du värdnamnet här för att möjliggöra MODX-funktioner som kan behöva använda proxyn, tex pakethanteringen.';

$_lang['setting_proxy_password'] = 'Lösenord för proxy';
$_lang['setting_proxy_password_desc'] = 'Lösenordet som krävs för att autentisera mot din proxyserver.';

$_lang['setting_proxy_port'] = 'Port för proxy';
$_lang['setting_proxy_port_desc'] = 'Porten för din proxyserver.';

$_lang['setting_proxy_username'] = 'Användarnamn för proxy';
$_lang['setting_proxy_username_desc'] = 'Användarnamnet som ska användas för att autentisera mot proxyservern.';

$_lang['setting_photo_profile_source'] = 'Mediakälla för användarfoto';
$_lang['setting_photo_profile_source_desc'] = 'Den mediakälla där användarnas profilbilder sparas. Om inget annat anges används standardmediakällan.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'Tillåt sökväg ovanför dokumentrot för phpThumb';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Anger om det är tillåtet med sökvägar utanför dokumentroten. Det här är användbart vid multikontext-installationer med flera virtuella hostar.';

$_lang['setting_phpthumb_cache_maxage'] = 'Maximal cachetid för phpThumb';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Ta bort cachade tumnaglar som inte har använts på mer än X dagar.';

$_lang['setting_phpthumb_cache_maxsize'] = 'Maximal cachestorlek för phpThumb';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Ta bort de tumnaglar som inte använts på längst tid när cachens storlek överstiger X megabyte.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'Maximalt antal cachefiler för phpThumb';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Ta bort de tumnaglar som inte använts på längst tid när cachen överstiger X antal filer.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'Cacha källfiler för phpThumb';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Anger om källfiler ska cachas när de laddas eller inte. Rekommenderas vara inaktiverad.';

$_lang['setting_phpthumb_document_root'] = 'Dokumentrot för PHPThumb';
$_lang['setting_phpthumb_document_root_desc'] = 'Ange den här om du har problem med servervariabeln DOCUMENT_ROOT eller om du får fel med OutputThumbnail eller !is_resource. Ange den absoluta sökvägen till dokumentroten som du vill använda. Om fältet lämnas tomt kommer MODX att använda servervariabeln DOCUMENT_ROOT.';

$_lang['setting_phpthumb_error_bgcolor'] = 'Bakgrundsfärg för fel i phpThumb';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Ett hexadecimalt värde, utan #-tecknet, som anger vilken bakgrundsfärg som ska användas vid felmeddelanden från phpThumb.';

$_lang['setting_phpthumb_error_fontsize'] = 'Teckenstorlek för fel i phpThumb';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Ett em-värde som anger storleken på den text som används för felmeddelanden i phpThumb.';

$_lang['setting_phpthumb_error_textcolor'] = 'Teckenfärg för fel i phpThumb';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Ett hexadecimalt värde, utan #-tecknet, som anger vilken teckenfärg som ska användas vid felmeddelanden från phpThumb.';

$_lang['setting_phpthumb_far'] = 'Tvinga bildformat för phpThumb';
$_lang['setting_phpthumb_far_desc'] = 'Standardinställningen för tvingat bildformat (far) i phpThumb när det används i MODX. Är satt till C som standard för att tvinga fram ett centrerat bildformat.';

$_lang['setting_phpthumb_imagemagick_path'] = 'Sökväg till ImageMagick för phpThumb';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Valfri. Ange en alternativ sökväg till ImageMagick för att generera tumnaglar med phpThumb, om det inte är PHP:s standard.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'Inaktiverad hotlinking för phpThumb';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Fjärrservrar är tillåtna i src-värdet om du inte inaktiverar hotlinking för phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'Borttagning av bild vid hotlinking för phpThumb';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Anger om en bild som genererats på en fjärrserver ska tas bort om det inte är tillåtet.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'Meddelande vid otillåten hotlinking för phpThumb';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Ett meddelande som visas istället för tumnageln när ett hotlink-försök hindrats.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'Giltiga domäner vid hotlinking för phpThumb';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'En kommaavgränsad lista med domännamn som är tillåtna i src-URL:er.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'Inaktiverad länkning från andra webbplatser för phpThumb';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Inaktiverar möjligheten för andra att använda phpThumb för att visa bilder på deras egna webbplatser.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'Bildradering vid länkning från andra webbplatser för phpThumb';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Anger om en bild som länkas från en fjärrserver ska tas bort när det inte är tillåtet.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'Kräv referrer vid länkning från andra webbplatser för phpThumb';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Om den här aktiveras kommer alla försök att länka från andra webbplatser att stoppas om det inte finns en giltig referrer header.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'Meddelande vid otillåten länkning från andra webbplatser för phpThumb';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Ett meddelande som visas istället för tumnageln när ett försök att länka från en annan webbplats stoppats.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'Giltiga domäner vid länkning från andra webbplatser för phpThumb';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'En kommaavgränsad lista med domännamn som är tillåtna referrers vid länkning från andra webbplatser.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'Fil för vattenmärkning av utifrån länkade bilder för phpThumb';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Valfri. En giltig sökväg till en fil som ska användas för vattenmärkning av dina bilder när de visas på andra webbplatser av phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'Zoom-beskärning för phpThumb';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Standardinställningen för zoom-beskärning (zc) i phpThumb när det används i MODX. Är satt till 0 som standard för att förhindra zoom-beskärning.';

$_lang['setting_publish_default'] = 'Publicerade som standard';
$_lang['setting_publish_default_desc'] = 'Välj "Ja" för att göra alla nya resurser publicerade som standard.';
$_lang['setting_publish_default_err'] = 'Ange om du vill att dokument ska publiceras som standard eller inte.';

$_lang['setting_rb_base_dir'] = 'Sökväg till resurs';
$_lang['setting_rb_base_dir_desc'] = 'Ange den fysiska sökvägen till resursens katalog. Den här inställningen görs vanligen automatiskt, men om du använder IIS är det möjligt att MODX inte kan räkna ut sökvägen själv, vilket orsakar ett felmeddelande i resursläsaren. I så fall kan du skriva in sökvägen till bildkatalogen här (sökvägen som den visas i Utforskaren).<br /><strong>OBS: Resurskatalogen måste innehålla underkatalogerna images, files, flash och media för att resursläsaren ska fungera korrekt.</strong>';
$_lang['setting_rb_base_dir_err'] = 'Ange resursläsarens baskatalog.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Denna resurskatalog finns inte eller kan inte kommas åt. Ange en giltig katalog eller ändra rättigheterna för denna katalog.';

$_lang['setting_rb_base_url'] = 'Resursens adress';
$_lang['setting_rb_base_url_desc'] = 'Ange den virtuella sökvägen till resurskatalogen. Den här inställningen görs vanligen automatiskt, men om du använder IIS är det möjligt att MODX inte kan räkna ut adressen på egen hand, vilket orsakar ett felmeddelande i resursläsaren. I så fall kan du skriva in adressen till bildkatalogen här (adressen som du skulle skriva den i Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Ange resursläsarens bas-URL.';

$_lang['setting_request_controller'] = 'Anropscontrollerns filnamn';
$_lang['setting_request_controller_desc'] = 'Filnamnet på den huvudsakliga anropscontrollern från vilken MODX laddas. De flesta användare kan låta denna vara index.php.';

$_lang['setting_request_method_strict'] = 'Strikt anropsmetod';
$_lang['setting_request_method_strict_desc'] = 'När denna är aktiverad kommer anrop via ID-parametern att ignoreras när vänliga URL:er är aktiverade och anrop via aliasparametern kommer att ignoreras när vänliga URL:er inte är aktiverade.';

$_lang['setting_request_param_alias'] = 'Alias-parameter för anrop';
$_lang['setting_request_param_alias_desc'] = 'Namnet på GET-parametern som identifierar resursalias när omdirigering görs med vänliga URL:er.';

$_lang['setting_request_param_id'] = 'ID-parameter för anrop';
$_lang['setting_request_param_id_desc'] = 'Namnet på GET-parametern som identifierar resurs-ID:n när vänliga URL:er inte används.';

$_lang['setting_resolve_hostnames'] = 'Gör namnuppslag';
$_lang['setting_resolve_hostnames_desc'] = 'Vill du att MODX ska försöka göra namnuppslag på dina besökares värddatornamn när de besöker din webbplats? Namnuppslag kan skapa extra belastning för servern, men dina besökare kommer inte att märka av det på något sätt.';

$_lang['setting_resource_tree_node_name'] = 'Resursträdets nodfält';
$_lang['setting_resource_tree_node_name_desc'] = 'Ange det resursfält som ska användas när noder i resursträdet ska visas. pagetitle används som standard, men vilket resursfält som helst kan användas, tex menutitle, alias, longtitle etc.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Reservfält för resursträdsnoder';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Ange det resursfält som ska användas som reserv vid rendering av noder i resursträdet. Detta kommer att användas om resursen har ett tomt värde för det konfigurerade nodfältet.';

$_lang['setting_resource_tree_node_tooltip'] = 'Fält att använda för resursträdets textbubblor';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Ange det resursfält som ska användas när noder visas i resursträdet. Vilket resursfält som helst kan användas, tex menytitel, resursens alias, lång titel etc. Om fältet lämnas tomt kommer den långa titeln att användas med en beskrivning under.';

$_lang['setting_richtext_default'] = 'Richtext som standard';
$_lang['setting_richtext_default_desc'] = 'Välj "Ja" för att ange att alla nya resurser ska använda richtext-editorn som standard.';

$_lang['setting_search_default'] = 'Sökbara som standard';
$_lang['setting_search_default_desc'] = 'Välj "Ja" för att göra alla nya resurser sökbara som standard.';
$_lang['setting_search_default_err'] = 'Ange om du vill att dokument ska vara sökbara som standard eller inte.';

$_lang['setting_server_offset_time'] = 'Serverns tidsskillnad';
$_lang['setting_server_offset_time_desc'] = 'Välj det antal timmar som skiljer mellan dig och servern.';

$_lang['setting_server_protocol'] = 'Servertyp';
$_lang['setting_server_protocol_desc'] = 'Specificera här om din sida använder en http- eller en https-anslutning.';
$_lang['setting_server_protocol_err'] = 'Ange om din webbplats är säker eller inte (http/https).';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Sessionscookiens domän';
$_lang['setting_session_cookie_domain_desc'] = 'Använd den här inställningen för att anpassa domänen för sessionscookien. Lämna tom för att använda den nuvarande domänen.';

$_lang['setting_session_cookie_lifetime'] = 'Sessionscookiens livslängd';
$_lang['setting_session_cookie_lifetime_desc'] = 'Använd denna inställning för att anpassa sessionscookiens livslängd i sekunder. Den bestämmer livslängden på en användares sessionscookie när den valt \'Kom ihåg mig\' vid inloggningen.';

$_lang['setting_session_cookie_path'] = 'Sessionscookiens sökväg';
$_lang['setting_session_cookie_path_desc'] = 'Använd den här inställningen för att anpassa cookiesökvägen så webbplatsspecifika sessionscookies kan identifieras. Lämna tom för att använda MODX_BASE_URL.';

$_lang['setting_session_cookie_secure'] = 'Säkra sessionscookies';
$_lang['setting_session_cookie_secure_desc'] = 'Aktivera denna inställning för att använda säkra sessionscookies. Det här kräver att din webbplats är åtkomlig via https annars blir webbplatsen och/eller hanteraren oåtkomliga.';

$_lang['setting_session_cookie_httponly'] = 'HttpOnly för sessions-cookie';
$_lang['setting_session_cookie_httponly_desc'] = 'Använd den här inställningen för att ange flaggan HttpOnly för sessions-cookies.';

$_lang['setting_session_gc_maxlifetime'] = 'Maximal livslängd för sessionens sophämtning';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Tillåter anpassning av PHP:s ini-inställning session.gc_maxlifetime när "modSessionHandler" används.';

$_lang['setting_session_handler_class'] = 'Sessionshanterarklassens namn';
$_lang['setting_session_handler_class_desc'] = 'Använd \'modSessionHandler\' för databashanterade sessioner. Lämna fältet tomt för att använda PHP:s vanliga sessionshantering.';

$_lang['setting_session_name'] = 'Sessionsnamn';
$_lang['setting_session_name_desc'] = 'Använd denna inställning för att anpassa det sessionsnamn som används för sessioner i MODX. Lämna tom för att använda PHP:s standardnamn.';

$_lang['setting_settings_version'] = 'Versionsinställning';
$_lang['setting_settings_version_desc'] = 'Den aktuella installerade versionen av MODX.';

$_lang['setting_settings_distro'] = 'Distributionsinställning';
$_lang['setting_settings_distro_desc'] = 'Den aktuella installerade distributionen av MODX.';

$_lang['setting_set_header'] = 'Sätt HTTP-headers';
$_lang['setting_set_header_desc'] = 'När denna är aktiverad kommer MODX att försöka sätta HTTP-headers för resurser.';

$_lang['setting_send_poweredby_header'] = 'Skicka headern X-Powered-By';
$_lang['setting_send_poweredby_header_desc'] = 'När den här är aktiverad kommer MODX att skicka headern "X-Powered-By" för att identifiera att denna webbplats är byggd med MODX. Detta hjälper till att spåra användningen av MODX genom tredjeparts spårningsprogram som besöker din webbplats. Eftersom det här gör det lättare att identifiera vad webbplatsen är byggd med så kan det innebära en något ökad säkerhetsrisk om ett säkerhetsproblem skulle upptäckas i MODX.';

$_lang['setting_show_tv_categories_header'] = 'Visa flikrubriken "Kategorier" med mallvariabler';
$_lang['setting_show_tv_categories_header_desc'] = 'Om denna sätts till "Ja" kommer MODX att visa rubriken "Kategorier" ovanför den första kategorifliken när mallvariabler redigeras i en resurs.';

$_lang['setting_signupemail_message'] = 'Registreringsmeddelande';
$_lang['setting_signupemail_message_desc'] = 'Här kan du ange det meddelande som skickas till användare när du skapar ett konto för dem och låta MODX skicka e-post till dom med deras användarnamn och lösenord.<br /><strong>Notera:</strong> Följande begrepp ersätts av innehållshanteraren när meddelandet sänds:<br /><br />[[+sname]] - Namnet på din webbplats<br />[[+saddr]] - Webbplatsens e-postadress<br />[[+surl]] - Webbplatsens adress<br />[[+uid]] - Användarens login eller ID<br />[[+pwd]] - Användarens lösenord<br />[[+ufn]] - Användarens namn<br /><br /><strong>Låt [[+uid]] och [[+pwd]] stå kvar i meddelandet, annars innehåller mailet inte användarnamn och lösenord, vilket gör att dina användare inte kan logga in!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hej [[+uid]] \n\nHär kommer dina inloggningsuppgifter för [[+sname]] ([[+surl]]) innehållshanterare:\n\nAnvändarnamn: [[+uid]]\nLösenord: [[+pwd]]\n\nDu kan ändra ditt lösenord när du loggat in i innehållshanteraren.\n\nVänliga hälsningar\nWebmastern';

$_lang['setting_site_name'] = 'Webbplatsens namn';
$_lang['setting_site_name_desc'] = 'Skriv in namnet på din webbplats här.';
$_lang['setting_site_name_err']  = 'Ange ett namn på webbplatsen.';

$_lang['setting_site_start'] = 'Startsida';
$_lang['setting_site_start_desc'] = 'Skriv in ID till resursen du vill ha som startsida här.<br /><strong>OBS: Se till att detta ID tillhör en existerande resurs, och att den har blivit publicerad!</strong>';
$_lang['setting_site_start_err'] = 'Ange ett dokument-ID för webbplatsens startsida.';

$_lang['setting_site_status'] = 'Webbplatsens status';
$_lang['setting_site_status_desc'] = 'Välj "Ja" för att publicera din webbplats. Väljer du "Nej", kommer dina besökare att se meddelandet för "Webbplatsen inte tillgänglig" och kommer inte att kunna besöka sidan.';
$_lang['setting_site_status_err'] = 'Ange om webbplatsen är online (Ja) eller offline (Nej).';

$_lang['setting_site_unavailable_message'] = 'Meddelande för "Webbplatsen inte tillgänglig"';
$_lang['setting_site_unavailable_message_desc'] = 'Meddelandet som visas när webbplatsen är offline, eller när något fel har inträffat.<br /><strong>OBS: Detta meddelande visas bara om ingen "Webbplatsen inte tillgänglig"-sida bestämts.</strong>';

$_lang['setting_site_unavailable_page'] = 'Sida för "Webbplatsen inte tillgänglig"';
$_lang['setting_site_unavailable_page_desc'] = 'Ange ID för den resurs du vill använda som en offline-sida här. <br /><strong>OBS: Kontrollera att detta ID hör till en existerande resurs och att den blivit publicerad!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Ange ett dokument-ID för sidan som visas när webbplatsen inte är tillgänglig.';

$_lang['setting_strip_image_paths'] = 'Skriv om sökvägar till resurser?';
$_lang['setting_strip_image_paths_desc'] = 'Om denna inställning sätts till "Nej", så kommer MODX att skriva sökvägarna till resurser i filhanteraren (bilder, filer, flash etc.) som absoluta URL:er. Relativa URL:er är användbara om du ska flytta din MODX-installation, tex från en testserver till en produktionsserver. Om du inte har någon aning om vad det här betyder, så är det bäst att lämna inställningen satt till "Ja".';

$_lang['setting_symlink_merge_fields'] = 'Slå ihop resursfält i symlänkar';
$_lang['setting_symlink_merge_fields_desc'] = 'Om du anger "Ja" här kommer ifyllda fält automatiskt att slås ihop med målresursen när vidarebefordring sker via symlänkar.';

$_lang['setting_syncsite_default'] = 'Töm cachen som standard';
$_lang['setting_syncsite_default_desc'] = 'Sätt till "Ja" för att tömma cachen efter att du sparat en resurs som standard.';
$_lang['setting_syncsite_default_err'] = 'Ange om du vill tömma cachen efter att ha sparat en resurs som standard eller inte.';

$_lang['setting_topmenu_show_descriptions'] = 'Visa beskrivningar i toppmenyn';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Om denna sätts till "Nej" kommer MODX att dölja beskrivningarna för menyposter i hanterarens toppmeny.';

$_lang['setting_tree_default_sort'] = 'Standardfält för sortering av resursträdet';
$_lang['setting_tree_default_sort_desc'] = 'Det resursfält som används som standard för sortering av resursträdet när hanteraren laddas.';

$_lang['setting_tree_root_id'] = 'Trädets rot-ID';
$_lang['setting_tree_root_id_desc'] = 'Sätt denna till ett giltigt resurs-ID för att starta resursträdet till vänster under den noden som rot. Användaren kommer bara att kunna se resurser som är barn till den angivna resursen.';

$_lang['setting_tvs_below_content'] = 'Flytta mallvariabler nedanför innehåll';
$_lang['setting_tvs_below_content_desc'] = 'Sätt denna till "Ja" för att flytta mallvariabler nedanför innehållet när resurser redigeras.';

$_lang['setting_ui_debug_mode'] = 'UI-debuggningsläge';
$_lang['setting_ui_debug_mode_desc'] = 'Sätt denna till "Ja" för att skriva ut debuggningsmeddelanden när UI:n för hanterarens standardtema används. Du måste använda en webbläsare som stödjer console.log.';

$_lang['setting_udperms_allowroot'] = 'Tillåt rot';
$_lang['setting_udperms_allowroot_desc'] = 'Vill du tillåta dina användare att skapa nya resurser i roten på webbplatsen? ';

$_lang['setting_unauthorized_page'] = 'Otillåten-sida';
$_lang['setting_unauthorized_page_desc'] = 'Ange ID till den resurs som du vill skicka användare till om de har frågat efter en säker eller otillåten resurs.<br /><strong>OBS: Se till att det ID du anger tillhör en existerande resurs, att den har blivit publicerad och kan kommas åt av alla!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Ange ett resurs-ID för otillåten-sidan.';

$_lang['setting_upload_files'] = 'Uppladdningsbara filtyper';
$_lang['setting_upload_files_desc'] = 'Här kan du skriva en lista med de typer av filer som kan laddas upp till "assets/files/" med filhanteraren. Skriv i suffixen för filtyperna, separerade med kommatecken.';

$_lang['setting_upload_flash'] = 'Uppladdningsbara flashtyper';
$_lang['setting_upload_flash_desc'] = 'Här kan du skriva en lista med de typer av flashfiler som kan laddas upp till "assets/flash/" med filhanteraren. Skriv i suffixen för flashtyperna, separerade med kommatecken.';

$_lang['setting_upload_images'] = 'Uppladdningsbara bildtyper';
$_lang['setting_upload_images_desc'] = 'Här kan du skriva en lista med de typer av bildfiler som kan laddas upp till "assets/images/" med filhanteraren. Skriv filändelserna för bildfilerna, separerade med kommatecken.';

$_lang['setting_upload_maxsize'] = 'Största storlek för uppladdningar';
$_lang['setting_upload_maxsize_desc'] = 'Skriv den största filstorleken som kan laddas upp via filhanteraren. Storleken måste anges i bytes.<br /><strong>OBS: Stora filer kan ta väldigt lång tid att ladda upp!</strong>';

$_lang['setting_upload_media'] = 'Uppladdningsbara mediatyper';
$_lang['setting_upload_media_desc'] = 'Här kan du skriva en lista med de typer av mediafiler som kan laddas upp till "assets/media/" med filhanteraren. Skriv filändelserna för mediatyperna, separerade med kommatecken.';

$_lang['setting_use_alias_path'] = 'Använd vänliga aliassökvägar';
$_lang['setting_use_alias_path_desc'] = 'Sätts detta val till "Ja", kommer hela sökvägen till resursen att visas om resursen har ett alias. Till exempel, om en resurs med aliaset "barn" befinner sig i en behållare med aliaset "foralder", kommer hela sökvägen att visas som "/foralder/barn.html".<br /><strong>Notera: När detta sätts till "Ja" (slår på aliassökvägar), måste du referera objekt (som bilder, css, javascript etc) med en absolut sökväg. Exempel: "/assets/images" istället för "assets/images". Genom att göra så förhindrar du att webbläsaren (eller webbservern) lägger till den relativa sökvägen till aliassökvägen.</strong>';

$_lang['setting_use_browser'] = 'Använd resursutforskare';
$_lang['setting_use_browser_desc'] = 'Välj "Ja" för att använda resursutforskaren. Detta låter dina användare läsa och ladda upp resurser såsom bilder, flash- och mediafiler till servern.';
$_lang['setting_use_browser_err'] = 'Ange om du vill använda resursutforskaren eller inte.';

$_lang['setting_use_editor'] = 'Aktivera richtext-editor';
$_lang['setting_use_editor_desc'] = 'Vill du aktivera en richtext-editor? Om du trivs bättre med att skriva HTML, kan du stänga av editorn genom att ändra denna inställning.<br /><strong>OBS: Denna inställning gäller för samtliga dokument och alla användare!</strong>';
$_lang['setting_use_editor_err'] = 'Ange om du vill använda en RTE-editor eller inte.';

$_lang['setting_use_frozen_parent_uris'] = 'Använd frysta föräldra-URI:er';
$_lang['setting_use_frozen_parent_uris_desc'] = 'När den här är aktiverad blir URI:er för barnresurser realtiva till den frusna URI:n för deras föräldrar. Alias högre upp i trädet ignoreras.';

$_lang['setting_use_multibyte'] = 'Använd multibyte-tillägget';
$_lang['setting_use_multibyte_desc'] = 'Sätt till "Ja" om du vill använda mbstring-tillägget för multibyte-tecken i din MODX-installation. Sätt den till "Ja" endast om du har PHP-tillägget mbstring installerat.';

$_lang['setting_use_weblink_target'] = 'Använd webblänkmål';
$_lang['setting_use_weblink_target_desc'] = 'Om du aktiverar den här inställningen kommer länkar för webblänkresurser att renderas som målets URL istället för den interna MODX-URL:en. Det här gäller oavsett om du använder länktaggar eller API-metoden modX::makeUrl().';

$_lang['setting_user_nav_parent'] = 'Användarmenyns förälder';
$_lang['setting_user_nav_parent_desc'] = 'Den behållare som används för att hämta alla uppgifter till användarmenyn.';

$_lang['setting_webpwdreminder_message'] = 'E-post för webbpåminnelse';
$_lang['setting_webpwdreminder_message_desc'] = 'Skriv ett meddelande som skickas till dina webbanvändare när de begärt ett nytt lösenord via e-post. Innehållshanteraren kommer att skicka ett e-postmeddelande med deras nya lösenord och aktiveringsinformation.<br /><strong>Notera:</strong> Följande platshållare ersätts av innehållshanteraren när ett meddelande skickas:<br /><br />[[+sname]] - Namnet på din webbplats<br />[[+saddr]] - E-postadressen till din webbplats<br />[[+surl]] - Adressen till din webbplats<br />[[+uid]] - Användarens inloggningsnamn eller ID<br />[[+pwd]] - Användarens lösenord<br />[[+ufn]] - Användarens namn<br /><br /><b>Lämna [[+uid]] och [[+pwd]] i meddelandet, annars får inte mottagaren av e-posten reda på sitt nya användarnamn och lösenord!</b>';
$_lang['setting_webpwdreminder_message_default'] = 'Hej [[+uid]]\n\nKlicka på följande länk för att aktivera ditt nya lösenord:\n\n[[+surl]]\n\nOm allt går bra använder du följande lösenord för att logga in:\n\nLösenord:[[+pwd]]\n\nOm du inte har bett om det här brevet så kan du strunta i det.\n\nVänliga hälsningar\nWebmastern';

$_lang['setting_websignupemail_message'] = 'E-post för webbregistreringar';
$_lang['setting_websignupemail_message_desc'] = 'Här kan du ange det meddelande som skickas till dina webbanvändare när du skapar ett webbkonto för dem, och låter innehållshanteraren skicka ett e-postmeddelande med användarnamn och lösenord.<br /><strong>Notera:</strong> Följande platshållare ersätts av innehållshanteraren när meddelandet skickas:<br /><br />[[+sname]] - Namnet på din webbplats<br />[[+saddr]] - E-postadressen till din webbplats<br />[[+surl]] - Adressen till din webbplats<br />[[+uid]] - Användarens inloggningsnamn eller ID<br />[[+pwd]] - Användarens lösenord<br />[[+ufn]] - Användarens namn<br /><br /><strong>Lämna [[+uid]] och [[+pwd]] i meddelandet, annars får inte mottagaren av e-posten reda på sitt användarnamn och lösenord!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hej [[+uid]] \n\nHär kommer dina inloggningsuppgifter för [[+sname]] ([[+surl]]):\n\nAnvändarnamn: [[+uid]]\nLösenord: [[+pwd]]\n\nDu kan ändra ditt lösenord när du loggat in i [[+sname]].\n\nVänliga hälsningar\nWebmastern';

$_lang['setting_welcome_screen'] = 'Visa välkomstmeddelande';
$_lang['setting_welcome_screen_desc'] = 'Om denna sätts till "Ja" kommer ett välkomstmeddelande att visas vid nästa laddning av välkomstsidan och sedan inte visas mer efter det.';

$_lang['setting_welcome_screen_url'] = 'URL för välkomstmeddelande';
$_lang['setting_welcome_screen_url_desc'] = 'URL:en för det välkomstmeddelande som visas när MODX Revolution laddas för första gången.';

$_lang['setting_welcome_action'] = 'Välkomståtgärd';
$_lang['setting_welcome_action_desc'] = 'Den standard-controller som ska laddas när man öppnar hanteraren och det inte finns någon controller angiven i URL:en.';

$_lang['setting_welcome_namespace'] = 'Välkomstnamnrymd';
$_lang['setting_welcome_namespace_desc'] = 'Den namnrymd som välkomståtgärden tillhör.';

$_lang['setting_which_editor'] = 'Editor att använda';
$_lang['setting_which_editor_desc'] = 'Här kan du välja vilken richtext-editor du vill använda. Du kan ladda ner och installera fler richtext-editorer i MODX pakethanterare.';

$_lang['setting_which_element_editor'] = 'Editor att använda för element';
$_lang['setting_which_element_editor_desc'] = 'Här kan du välja vilken richtext-editor du vill använda när du redigerar element. Du kan ladda ner och installera fler richtext-editorer i pakethanteraren.';

$_lang['setting_xhtml_urls'] = 'XHTML-URL:er';
$_lang['setting_xhtml_urls_desc'] = 'Om denna sätts till "Ja" kommer alla URL:er som genereras av MODX att vara XHTML-kompatibla inklusive kodning av et-tecken.';

$_lang['setting_default_context'] = 'Standardkontext';
$_lang['setting_default_context_desc'] = 'Ange den kontext som du vill använda för nya resurser.';

$_lang['setting_auto_isfolder'] = 'Sätt som behållare automatiskt';
$_lang['setting_auto_isfolder_desc'] = 'Om denna sätts till "Ja" kommer behållarinställningen att ändras automatiskt.';

$_lang['setting_default_username'] = 'Standardanvändarnamn';
$_lang['setting_default_username_desc'] = 'Användarnamn för en oautentiserad användare.';

$_lang['setting_manager_use_fullname'] = 'Visa fullständigt namn i hanterarens sidhuvud ';
$_lang['setting_manager_use_fullname_desc'] = 'Om denna sätts till "Ja" kommer användarens fullständiga namn att visas i hanteraren istället för användarnamnet.';

$_lang['log_snippet_not_found'] = 'Logga snippets som inte hittas';
$_lang['log_snippet_not_found_desc'] = 'Om satt till Ja kommer snippets som anropas, men inte hittas att loggas till felloggen.';

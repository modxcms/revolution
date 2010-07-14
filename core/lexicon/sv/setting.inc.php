<?php
/**
 * Setting Swedish lexicon topic
 *
 * @language sv
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Område';
$_lang['area_authentication'] = 'Autentisering och säkerhet';
$_lang['area_caching'] = 'Cachning';
$_lang['area_editor'] = 'Richtext-editor';
$_lang['area_file'] = 'Filsystem';
$_lang['area_filter'] = 'Filtrera efter område...';
$_lang['area_furls'] = 'Vänliga URLer';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Lexikon och språk';
$_lang['area_mail'] = 'E-post';
$_lang['area_manager'] = 'Hanteraren';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session och cookie';
$_lang['area_lexicon_string'] = 'Områdets lexikonpost';
$_lang['area_lexicon_string_msg'] = 'Ange lexikonpostens nyckel för området här. Om det inte finns någon lexikonpost så kommer bara områdesnyckeln att visas.<br />Kärnområden:<ul><li>authentication</li><li>caching</li><li>file</li><li>furls</li><li>gateway</li><li>language</li><li>manager</li><li>session</li><li>site</li><li>system</li></ul>';
$_lang['area_site'] = 'Webbplats';
$_lang['area_system'] = 'System och server';
$_lang['areas'] = 'Områden';
$_lang['namespace'] = 'Namnrymd';
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
$_lang['setting_remove_confirm'] = 'Är du säker på att du vill ta bort den här inställningen? Det kan innebära att din MODx-installation slutar fungera.';
$_lang['setting_update'] = 'Uppdatera inställning';
$_lang['settings_after_install'] = 'Eftersom detta är en ny installation, måste du gå igenom dessa inställningar och ändra det du vill. När du är klar med kontrollen av alla inställningar, klicka på \'Spara\' för att uppdatera inställningsdatabasen.<br /><br />';
$_lang['settings_desc'] = 'Här gör du allmänna inställningar och konfigurationer för användargränssnittet i MODx hanterare, samt för hur din MODx-webbplats fungerar. Dubbelklicka i värdekolumnen för den inställning som du vill redigera för att göra ändringarna dynamiskt i rutnätet eller högerklicka på en inställning för att se fler val. Du kan också klicka på plustecknet för att få en beskrivning av inställningen';
$_lang['settings_furls'] = 'Vänliga adresser';
$_lang['settings_misc'] = 'Övrigt';
$_lang['settings_site'] = 'Webbplatsen';
$_lang['settings_ui'] = 'Gränssnitt &amp; funktioner';
$_lang['settings_users'] = 'Användare';
$_lang['system_settings'] = 'Systeminställningar';

// user settings
$_lang['setting_allow_mgr_access'] = 'Tillgång till hanterarens gränssnitt';
$_lang['setting_allow_mgr_access_desc'] = 'Använd den här inställningen för att aktivera eller inaktivera tillgång till hanterarens gränssnitt. <strong>Notera: Om inställningen sätts till Nej kommer användaren att omdirigeras till hanterarens inloggningssida eller till webbplatsens startsida.</strong>';

$_lang['setting_failed_login'] = 'Misslyckade inloggningsförsök';
$_lang['setting_failed_login_desc'] = 'Här kan du ange hur många misslyckade inloggningsförsök som är tillåtet innan användaren blockeras.';

$_lang['setting_login_allowed_days'] = 'Tillåtna dagar';
$_lang['setting_login_allowed_days_desc'] = 'Välj vilka dagar denna användare får logga in.';

$_lang['setting_login_allowed_ip'] = 'Tillåtna IP-adresser';
$_lang['setting_login_allowed_ip_desc'] = 'Ange de IP-adresser som denna användare får logga in från. <strong>NOTERA: Separera flera IP-adresser med kommatecken (,).</strong>';

$_lang['setting_login_homepage'] = 'Startsida efter inloggning';
$_lang['setting_login_homepage_desc'] = 'Ange ID på det dokument som du vill skicka användaren till efter att hon eller han har loggat in. <strong>NOTERA: kontrollera att det ID du anger tillhör ett befintligt dokument, att det är publicerat och är tillgängligt för användaren!</strong>';

// specific settings
$_lang['setting_allow_duplicate_alias'] = 'Tillåt aliasdubletter';
$_lang['setting_allow_duplicate_alias_desc'] = 'Om satt till "Ja" kommer aliasdubletter att kunna sparas.<br /><strong>Notera: Den här inställningen bör användas med "Vänliga aliassökvägar" satt till "Ja" för att undvika problem med refereringen av dokument.</strong>';

$_lang['setting_allow_tags_in_post'] = 'Tillåt HTML-taggar i POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Om denna sätts till "Nej" kommer alla POST-händelser i hanteraren att rensas från taggar. MODx rekommenderar att denna lämnas att till "Ja".';

$_lang['setting_auto_menuindex'] = 'Standardvärde för menyindexering';
$_lang['setting_auto_menuindex_desc'] = 'Välj "Ja" för att aktivera automatisk ökning av menyindex som standard.';

$_lang['setting_auto_check_pkg_updates'] = 'Automatisk sökning efter paketuppdateringar';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Om denna sätts till "Ja" kommer MODx att automatiskt söka efter uppdateringar för paket i pakethanteraren. Det här kan sakta ner laddningen av sidan (the grid).';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Utgångstid för cachning av resultaten vid automatisk sökning efter paketuppdateringar';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Det antal minuter som pakethanteringen ska cacha resultaten vid sökande efter paketuppdateringar.';

$_lang['setting_allow_multiple_emails'] = 'Tillåt e-postdubletter för användare';
$_lang['setting_allow_multiple_emails_desc'] = 'Om denna aktiveras så kan användare dela samma e-postadress.';

$_lang['setting_automatic_alias'] = 'Generera alias automatiskt';
$_lang['setting_automatic_alias_desc'] = 'Välj "Ja" för att låta systemet automatiskt skapa ett alias baserat på resursens titel när det sparas.';


$_lang['setting_blocked_minutes'] = 'Blockeringstid:';
$_lang['setting_blocked_minutes_desc'] = 'Här kan du ange hur många minuter en användare blir blockerad efter att ha gjort för många misslyckade inloggningsförsök. Ange värdet som ett tal (inga kommatecken, mellanslag etc).';

$_lang['setting_cache_action_map'] = 'Aktivera cachning av händelsekartor';
$_lang['setting_cache_action_map_desc'] = 'När denna är aktiverad kommer händelser (eller kontrollantkartor) att cachas för att minska laddningstiderna i hanteraren.';

$_lang['setting_cache_context_settings'] = 'Aktivera cachning av kontextinställningar';
$_lang['setting_cache_context_settings_desc'] = 'När denna är aktiverad kommer kontextinställningar att cachas för att minska laddningstider.';

$_lang['setting_cache_db'] = 'Aktivera databascache';
$_lang['setting_cache_db_desc'] = 'När denna är aktiverad, cachas objekt och obearbetade resultat från SQL-frågor, för att markant minska belastningen på databasen.';

$_lang['setting_cache_db_expires'] = 'Utgångstid för databas-cache';
$_lang['setting_cache_db_expires_desc'] = 'Detta värde (i sekunder) anger den tid som cachefiler varar för cachning av databasresultat.';

$_lang['setting_cache_default'] = 'Cachebara som standard';
$_lang['setting_cache_default_desc'] = 'Välj "Ja" för att göra alla nya resurser cachebara som standard.';
$_lang['setting_cache_default_err'] = 'Ange om du vill att dokument ska cachas som standard eller inte.';

$_lang['setting_cache_disabled'] = 'Avaktivera globala cachealternativ';
$_lang['setting_cache_disabled_desc'] = 'Välj "Ja" för att avaktivera alla MODx cachefunktioner. MODx rekommenderar inte att cachning avaktiveras.';
$_lang['setting_cache_disabled_err'] = 'Ange om du vill att cachen ska vara aktiverad eller inte.';

$_lang['setting_cache_json'] = 'Cacha JSON-data';
$_lang['setting_cache_json_desc'] = 'Cacha all JSON-data som skickas till eller från hanterarens gränssnitt.';

$_lang['setting_cache_expires'] = 'Utgångstid för standardcache';
$_lang['setting_cache_expires_desc'] = 'Detta värde (i sekunder) anger den tid som cache-filer varar för standardcachning.';

$_lang['setting_cache_json_expires'] = 'Utgångstid för JSON-cache';
$_lang['setting_cache_json_expires_desc'] = 'Detta värde (i sekunder) anger den tid som cache-filer varar för JSON-cachning.';

$_lang['setting_cache_handler'] = 'Klass för cache-hantering';
$_lang['setting_cache_handler_desc'] = 'Klassnamnet på den typhanterare som ska användas för cachning.';

$_lang['setting_cache_lang_js'] = 'Cacha lexikonsträngar för javascript';
$_lang['setting_cache_lang_js_desc'] = 'Om denna sätts till "Ja" kommer server-headers att användas för att cacha lexikonsträngarna som laddas till javascript i hanterarens gränssnitt.';

$_lang['setting_cache_lexicon_topics'] = 'Cacha lexikonämnen';
$_lang['setting_cache_lexicon_topics_desc'] = 'När denna är aktiverad cachas alla lexikonämnen för att reducera laddningstider för internationaliseringsfunktionalitet. MODx rekommenderar starkt att lämna denna satt till Ja".';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Cacha lexikonämnen utanför kärnan';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Om denna inaktiveras kommer lexikonämnen som inte hör till kärnan inte att cachas. Det här är användbart att inaktivera när du utvecklar dina egna Extras.';

$_lang['setting_cache_resource'] = 'Aktivera partiell dokumentcache';
$_lang['setting_cache_resource_desc'] = 'När denna är aktiverad kan man konfigurera partiell dokumentcache per dokument. Om denna inställninga avaktiveras blir den avaktiverad globalt.';

$_lang['setting_cache_resource_expires'] = 'Utgångstid för partiell resurscache';
$_lang['setting_cache_resource_expires_desc'] = 'Detta värde (i sekunder) anger den tid som cachefiler varar för partiell resurscachning.';

$_lang['setting_cache_scripts'] = 'Aktivera script-cache';
$_lang['setting_cache_scripts_desc'] = 'När denna är aktiverad kommer MODx att cacha alla script (snippets och plugins) till fil för att reducera laddningstider. MODx rekommenderar att denna lämnas satt till "Ja".';

$_lang['setting_cache_system_settings'] = 'Aktivera cachning av systeminställningar';
$_lang['setting_cache_system_settings_desc'] = 'När denna är aktiverad kommer systeminställningar att cachas för att minska laddningstider. MODx rekommenderar att denna lämnas aktiverad.';

$_lang['setting_compress_css'] = 'Använd komprimerad CSS';
$_lang['setting_compress_css_desc'] = 'När denna är aktiverad kommer MODx att använda en komprimerad version av sina css-stilmallar i hanterarens gränssnitt. Detta minskar laddnings- och exekveringstiden i hanteraren ordentligt. Avaktivera bara om du modifierar element i kärnan.';

$_lang['setting_compress_js'] = 'Använd komprimerade javascript-bibliotek';
$_lang['setting_compress_js_desc'] = 'När denna är aktiverad kommer MODx att använda en komprimerad version av sina javascript-bibliotek i hanterarens gränssnitt. Detta minskar laddnings- och exekveringstiden i hanteraren ordentligt. Avaktivera bara om du modifierar element i kärnan.';

$_lang['setting_concat_js'] = 'Använd sammanfogade javascript-bibliotek';
$_lang['setting_concat_js_desc'] = 'När denna är aktiverad kommer MODx att använda en sammanfogad version av sina javascript-bibliotek i hanterarens gränssnitt. Detta minskar laddnings- och exekveringstiden i hanteraren ordentligt. Avaktivera bara om du modifierar element i kärnan.';

$_lang['setting_container_suffix'] = 'Behållarsuffix';
$_lang['setting_container_suffix_desc'] = 'Det suffix som ska läggas till resurser som är angivna som behållare när vänliga URL:er används.';

$_lang['setting_cultureKey'] = 'Språk';
$_lang['setting_cultureKey_desc'] = 'Välj språk för alla kontexter utanför hanteraren, inklusive webben.';

$_lang['setting_custom_resource_classes'] = 'Anpassade resursklasser';
$_lang['setting_custom_resource_classes_desc'] = 'En kommaseparerad lista med anpassade resursklasser. Ange med lexikonnyckel_med_gemener:klassNamn (tex wiki_resource:WikiResource). Alla anpassade resursklasser måste utvidga modResource. För att ange kontrollantens position för varje klass lägger du till en inställning för [klassensNamnMedGemener]_delegate_path tillsammans med sökvägen till php filerna för skapande/uppdatering. Till exempel: wikiresource_delegate_path för klassen WikiResource som utvidgar modResource.';

$_lang['setting_default_template'] = 'Standardmall';
$_lang['setting_default_template_desc'] = 'Välj den standarmall du vill använda för nya resurser. Du kan fortfarande välja en annan mall när du redigerar resursen. Denna inställning är bara förvalet.';

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

$_lang['setting_error_page'] = 'Felsida';
$_lang['setting_error_page_desc'] = 'Skriv in ID till den sida du vill skicka användare till om de försöker komma åt ett dokument som inte finns.<br /><strong>OBS: Se till att detta ID tillhör ett existerande dokument, och att det har blivit publicerat!</strong>';
$_lang['setting_error_page_err'] = 'Ange ett dokument-ID för felsidan.';

$_lang['setting_failed_login_attempts'] = 'Misslyckade inloggningsförsök';
$_lang['setting_failed_login_attempts_desc'] = 'Antalet misslyckade inloggningsförsök en användare kan göra innan den blir "blockerad".';

$_lang['setting_fe_editor_lang'] = 'Editorns språk';
$_lang['setting_fe_editor_lang_desc'] = 'Här kan du ange språk för editorn som används.';

$_lang['setting_feed_modx_news'] = 'URL för MODx nyhetsflöde';
$_lang['setting_feed_modx_news_desc'] = 'Ange URLn till RSS-flödet för MODx nyhetspanel i hanteraren.';

$_lang['setting_feed_modx_news_enabled'] = 'MODx nyhetsflöde aktiverat';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Om denna sätts till "Nej" kommer MODx att dölja nyhetsflödet på hanterarens välkomstsida.';

$_lang['setting_feed_modx_security'] = 'URL för MODx flöde för säkerhetsnotiser';
$_lang['setting_feed_modx_security_desc'] = 'Ange URLn till RSS-flödet för MODx säkerhetsnotiserpanel i hanteraren.';

$_lang['setting_feed_modx_security_enabled'] = 'MODx flöde för säkerhetsnotiser aktiverat';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Om denna sätts till "Nej"  kommer MODx att dölja flödet för säkerhetsnotiser på hanterarens välkomstsida.';

$_lang['setting_filemanager_path'] = 'Sökväg till filhanteraren';
$_lang['setting_filemanager_path_desc'] = 'IIS fyller oftast inte i inställningarna för document_root ordentligt, vilket används av filhanteraren för att bestämma vad du får se. Om du har problem med filhanteraren, se till så att denna katalog pekar till roten på din installation av MODx.';
$_lang['setting_filemanager_path_err'] = 'Ange den absoluta sökvägen till dokumentroten för filhanteraren.';
$_lang['setting_filemanager_path_err_invalid'] = 'Denna katalog för filhanteraren finns inte eller kan inte kommas åt. Ange en giltig katalog eller justera katalogens åtkomsträttigheter.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Gemena FURL-alias';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Anger om enbart gemena tecken tillåts i resursalias.';

$_lang['setting_friendly_alias_max_length'] = 'Maximal längd på FURL-alias';
$_lang['setting_friendly_alias_max_length_desc'] = 'Om större än noll, det maximala antalet tecken som tillåts i ett resursalias. Noll är det samma som obegränsat.';

$_lang['setting_friendly_alias_restrict_chars'] = 'Metod för teckenbegränsning i FURL-alias';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'Den metod som ska användas för att begränsa antalet tecken i ett resursalias. "pattern" tillåter att ett RegEx anges, "legal" tillåter bara giltiga tecken för URL:er, "alpha" tillåter bara bokstäver fråm alfabetet och "alphanumeric" tillåter bara bokstäver och siffror.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Mänster för begränsning av tecken i FURL-alias';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Ett giltigt RegEx som ska användas för att begränsa vilka tecken som får användas i ett resursalias.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Rensa elementtaggar från FURL-alias';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Anger om elementtaggar ska rensas bort från ett resursalias.';

$_lang['setting_friendly_alias_translit'] = 'Translitterationsmetod för FURL-alias';
$_lang['setting_friendly_alias_translit_desc'] = 'Den translitterationsmetod som ska användas på ett alias för en resurs. Standardinställningen är tomt eller "ingen" vilket hoppar över translitteration. Andra möjliga värden är "iconv" (om tillgänglig) eller en namngiven translitterationstabell tillhandahållen av en anpassad serviceklass för translitteration.';

$_lang['setting_friendly_alias_translit_class'] = 'Translitterationsklass för FURL-alias';
$_lang['setting_friendly_alias_translit_class_desc'] = 'En valfri serviceklass som tillhandahåller namngivna translitterationstjänster för generering/filtrering av FURL-alias.';

$_lang['setting_friendly_alias_trim_chars'] = 'Rensningstecken i FURL-alias';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Tecken som ska rensas bort från slutet på ett givet resursalias.';

$_lang['setting_friendly_alias_urls'] = 'Använd vänliga alias';
$_lang['setting_friendly_alias_urls_desc'] = 'Om du använder vänliga adresser och resursen har ett alias, kommer aliaset alltid att prioriteras i den vänliga adressen. Genom att sätta detta alternativ till "Ja", kommer resursens innehållstypssuffix att läggas till aliaset.<br />Exempel: om din resurs med ID 1 har aliaset "introduktion", prefixet är satt till "", innehållstypssuffixet till ".html" och du sätter denna inställning till "Ja", så kommer "introduktion.html" att visas. Om det inte finns något alias, kommer MODx att generera länken "1.html".';

$_lang['setting_friendly_alias_word_delimiter'] = 'Föredragen ordavgränsare för FURL-alias';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Den föredragna avgränsaren mellan ord i vänliga URL:er.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Ordavgränsare för FURL-alias';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Tecken som representerar avgränsare mellan ord när delar i resursalias processas. Dessa tecken kommer att konverteras och konsolideras till den föredragna avgränsaren mellan ord i resursalias.';

$_lang['setting_friendly_urls'] = 'Använd vänliga adresser';
$_lang['setting_friendly_urls_desc'] = 'Detta låter dig använda adresser som är vänliga mot sökmotorer. Notera att detta endast fungerar när MODx körs på Apache, och du måste skriva en .htaccess-fil för att det ska fungera. Se .htaccess-filen som följde med i distributionen för mer information.';
$_lang['setting_friendly_urls_err'] = 'Ange om du vill använda vänliga adresser eller inte.';

$_lang['setting_mail_charset'] = 'Teckenkodning för e-post';
$_lang['setting_mail_charset_desc'] = '(Standard-)Teckenkodningen för e-post, tex "iso-8859-1" eller "UTF-8"';

$_lang['setting_mail_encoding'] = 'E-postkodning';
$_lang['setting_mail_encoding_desc'] = 'Anger kodningen för e-postmeddelanden. Möjliga värden är "8bit", "7bit", "binary", "base64" och "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Använd SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Om denna sätts till "Ja" kommer MODx att försöka använda SMTP i mail-funktioner.';

$_lang['setting_mail_smtp_auth'] = 'SMTP-autentisering';
$_lang['setting_mail_smtp_auth_desc'] = 'Anger SMTP-autentisering. Använder inställningarna för mail_smtp_user och mail_smtp_password.';

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

$_lang['setting_manager_direction'] = 'Textriktning i hanteraren';
$_lang['setting_manager_direction_desc'] = 'Välj textriktning i hanteraren: antingen vänster-till-höger eller höger-till-vänster.';

$_lang['setting_manager_date_format'] = 'Hanterarens datumformat';
$_lang['setting_manager_date_format_desc'] = 'Formateringssträngen, i PHP date()-format, för datum som visas i hanteraren.';

$_lang['setting_manager_lang_attribute'] = 'Hanterarens språkattribut<br />för HTML och XML';
$_lang['setting_manager_lang_attribute_desc'] = 'Ange den språkkod som bäst överensstämmer med din språkinställning för hanteraren. Det här säkerställer att webbläsare kan presentera innehållet i det bästa formatet för dig.';

$_lang['setting_manager_language'] = 'Hanterarens språk';
$_lang['setting_manager_language_desc'] = 'Välj vilket språket du vill använda i MODx publiceringshanterare.';

$_lang['setting_manager_login_start'] = 'Startsida efter inloggning i hanteraren';
$_lang['setting_manager_login_start_desc'] = 'Ange ID för det dokument du vill att användaren ska komma till när den loggat in i hanteraren.<br /><strong>Notera: Kontrollera att det ID du valt hör till ett existerande dokument, att det är publicerat och att användaren har behörighet för det!</strong>';

$_lang['setting_manager_theme'] = 'Tema för hanteraren';
$_lang['setting_manager_theme_desc'] = 'Välj tema för innehållshanteraren.';

$_lang['setting_manager_time_format'] = 'Hanterarens tidsformat';
$_lang['setting_manager_time_format_desc'] = 'Formateringssträngen, i PHPs date()-format, för tidsinställningarna som finns i hanteraren.';

$_lang['setting_manager_use_tabs'] = 'Använd flikar i hanterarens layout';
$_lang['setting_manager_use_tabs_desc'] = 'Om denna aktiveras kommer hanteraren att använda flikar för att visa de olika panelerna. I annat fall kommer portaler att användas.';

$_lang['setting_modRequest.class'] = 'Anropshanterarens klass';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_charset'] = 'Teckenkodning';
$_lang['setting_modx_charset_desc'] = 'Välj den teckenkodning du vill använda. Notera att MODx har testats med ett antal av dessa kodningar, men inte alla. För de flesta språk är standardalternativet UTF-8 att föredra.</b>';

$_lang['setting_new_file_permissions'] = 'Behörigheter för nya filer';
$_lang['setting_new_file_permissions_desc'] = 'När en ny fil laddas upp med Filhanteraren, kommer Filhanteraren att försöka ändra filbehörigheterna till dom som anges i denna inställning. Det här kanske inte fungerar på alla system, t&nbsp;ex IIS, i vilket fall du blir tvungen att ändra behörigheterna manuellt.';

$_lang['setting_new_folder_permissions'] = 'Behörigheter för nya kataloger';
$_lang['setting_new_folder_permissions_desc'] = 'När en ny katalog skapas i Filhanteraren, kommer Filhanteraren att försöka ändra katalogbehörigheterna till dom som anges i denna inställning. Det här kanske inte fungerar på alla system, t&nbsp;ex IIS, i vilket fall du blir tvungen att ändra behörigheterna manuellt.';

$_lang['setting_password_generated_length'] = 'Längd på automatgenererat lösenord';
$_lang['setting_password_generated_length_desc'] = 'Längden på ett automatgenererat lösenord för en användare.';

$_lang['setting_proxy_auth_type'] = 'Autentiseringstyp för proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Stödjer antingen BASIC eller NTLM.';

$_lang['setting_proxy_host'] = 'Värd för proxy';
$_lang['setting_proxy_host_desc'] = 'Om din server använder en proxy anger du värdnamnet här för att möjliggöra MODx-funktioner som kan behöva använda proxyn, tex pakethanteringen.';

$_lang['setting_proxy_password'] = 'Lösenord för proxy';
$_lang['setting_proxy_password_desc'] = 'Lösenordet som krävs för att autentisera mot din proxyserver.';

$_lang['setting_proxy_port'] = 'Port för proxy';
$_lang['setting_proxy_port_desc'] = 'Porten för din proxyserver.';

$_lang['setting_proxy_username'] = 'Användarnamn för proxy';
$_lang['setting_proxy_username_desc'] = 'Användarnamnet som ska användas för att autentisera mot proxyservern.';

$_lang['setting_password_min_length'] = 'Minimal lösenordslängd';
$_lang['setting_password_min_length_desc'] = 'Den minimala längden på ett lösenord för en användare.';

$_lang['setting_phpthumb_cache_maxage'] = 'Maximal cachetid för phpThumb';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Ta bort cachade tumnaglar som inte har använts på mer än X dagar.';

$_lang['setting_phpthumb_cache_maxsize'] = 'Maximal cachestorlek för phpThumb';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Ta bort de tumnaglar som inte använts på längst tid när cachens storlek övergår X megabyte.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'Maximalt antal cachefiler för phpThumb';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Ta bort de tumnaglar som inte använts på längst tid när cachen övergår X antal filer.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'Cacha källfiler för phpThumb';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Anger om källfiler ska cachas när de laddas eller inte. Rekommenderas vara inaktiverad.';

$_lang['setting_phpthumb_zoomcrop'] = 'Zoom-beskärning för phpThumb';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Standardinställningen för zoom-beskärning (zc) i phpThumb när det används i MODx. Är satt till 0 som standard för att förhindra zoom-beskärning.';

$_lang['setting_phpthumb_far'] = 'Tvinga bildformat för phpThumb';
$_lang['setting_phpthumb_far_desc'] = 'Standardinställningen för tvingat bildformat (far) i phpThumb när det annvänds i MODx. Är satt till C som standard för att tvinga fram ett centrerat bildformat.';

$_lang['setting_publish_default'] = 'Publicerade som standard';
$_lang['setting_publish_default_desc'] = 'Välj "Ja" för att göra alla nya resurser publicerade som standard.';
$_lang['setting_publish_default_err'] = 'Ange om du vill att dokument ska publiceras som standard eller inte.';

$_lang['setting_rb_base_dir'] = 'Sökväg till resurs';
$_lang['setting_rb_base_dir_desc'] = 'Ange den fysiska sökvägen till resursens katalog. Den här inställningen görs vanligen automatiskt, men om du använder IIS är det möjligt att MODx inte kan räkna ut sökvägen själv, vilket orsakar ett felmeddelande i resursläsaren. I så fall kan du skriva in sökvägen till bildkatalogen här (sökvägen som den visas i Utforskaren).<br /><strong>OBS: Resurskatalogen måste innehålla underkatalogerna images, files, flash och media för att resursläsaren ska fungera korrekt.</strong>';
$_lang['setting_rb_base_dir_err'] = 'Ange resursläsarens baskatalog.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Denna resurskatalog finns inte eller kan inte kommas åt. Ange en giltig katalog eller ändra rättigheterna för denna katalog.';

$_lang['setting_rb_base_url'] = 'Resursens adress';
$_lang['setting_rb_base_url_desc'] = 'Ange den virtuella sökvägen till resurskatalogen. Den här inställningen görs vanligen automatiskt, men om du använder IIS är det möjligt att MODx inte kan räkna ut adressen på egen hand, vilket orsakar ett felmeddelande i resursläsaren. I så fall kan du skriva in adressen till bildkatalogen här (adressen som du skulle skriva den i Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Ange resursläsarens bas-URL.';

$_lang['setting_request_controller'] = 'Anropskontrollantens filnamn';
$_lang['setting_request_controller_desc'] = 'Filnamnet på den huvudsakliga anropskontrollanten från vilken MODx laddas. De flesta användare kan låta denna vara index.php.';

$_lang['setting_request_param_alias'] = 'Alias-parameter för anrop';
$_lang['setting_request_param_alias_desc'] = 'Namnet på GET-parametern som identifierar resursalias när omdirigering görs med vänliga URL:er.';

$_lang['setting_request_param_id'] = 'ID-parameter för anrop';
$_lang['setting_request_param_id_desc'] = 'Namnet på GET-parametern som identifierar resurs-ID:n när vänliga URL:er inte används.';

$_lang['setting_resolve_hostnames'] = 'Gör namnuppslag';
$_lang['setting_resolve_hostnames_desc'] = 'Vill du att MODx ska försöka göra namnuppslag på dina besökares värddatornamn när de besöker din webbplats? Namnuppslag kan skapa extra belastning för servern, men dina besökare kommer inte att märka av det på något sätt.';

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
$_lang['setting_session_cookie_domain_desc'] = 'Använd den här inställningen för att anpassa domänen för sessionscookien.';

$_lang['setting_session_cookie_lifetime'] = 'Sessionscookiens livslängd';
$_lang['setting_session_cookie_lifetime_desc'] = 'Använd denna inställning för att anpassa sessionscookiens livslängd i sekunder. Den bestämmer livslängden på en användares sessionscookie när den valt \'Kom ihåg mig\' vid inloggningen.';

$_lang['setting_session_cookie_path'] = 'Sessionscookiens sökväg';
$_lang['setting_session_cookie_path_desc'] = 'Använd den här inställningen för att anpassa cookiesökvägen så webbplatsspecifika sessionscookies kan identifieras.';

$_lang['setting_session_cookie_secure'] = 'Säkra sessionscookies';
$_lang['setting_session_cookie_secure_desc'] = 'Aktivera denna inställning för att använda säkra sessionscookies.';

$_lang['setting_session_handler_class'] = 'Sessionshanterarens klassnamn';
$_lang['setting_session_handler_class_desc'] = 'Använd \'modSessionHandler\' för databashanterade sessioner. Lämna fältet tomt för att använda PHPs vanliga sessionshantering.';

$_lang['setting_session_name'] = 'Sessionsnamn';
$_lang['setting_session_name_desc'] = 'Använd denna inställning för att anpassa det sessionsnamn som används för sessioner i MODx.';

$_lang['setting_settings_version'] = 'Inställningar för version';
$_lang['setting_settings_version_desc'] = 'Den aktuella installerade versionen av MODx.';

$_lang['setting_set_header'] = 'Sätt HTTP-headers';
$_lang['setting_set_header_desc'] = 'När denna är aktiverad kommer MODx att försöka sätta HTTP-headers för resurser.';

$_lang['setting_signupemail_message'] = 'Registreringsmeddelande';
$_lang['setting_signupemail_message_desc'] = 'Här kan du ange det meddelande som skickas till användare när du skapar ett konto för dem och låta MODx skicka e-post till dom med deras användarnamn och lösenord.<br /><strong>Notera:</strong> Följande begrepp ersätts av innehållshanteraren när meddelandet sänds:<br /><br />[+sname+] - Namnet på din webbplats<br />[+saddr+] - Webbplatsens e-postadress<br />[+surl+] - Webbplatsens adress<br />[+uid+] - Användarens login eller ID<br />[+pwd+] - Användarens lösenord<br />[+ufn+] - Användarens namn<br /><br /><strong>Låt [+uid+] och [+pwd+] stå kvar i meddelandet, annars innehåller mailet inte användarnamn och lösenord, vilket gör att dina användare inte kan logga in!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hej [+uid+] \n\nHär kommer dina inloggningsuppgifter för [+sname+] ([+surl+]) innehållshanterare:\n\nAnvändarnamn: [+uid+]\nLösenord: [+pwd+]\n\nDu kan ändra ditt lösenord när du loggat in i innehålshanteraren.\n\nVänliga hälsningar\nWebmastern';

$_lang['setting_site_name'] = 'Webbplatsens namn';
$_lang['setting_site_name_desc'] = 'Skriv in namnet på din webbplats här.';
$_lang['setting_site_name_err']  = 'Ange ett namn på webbplatsen.';

$_lang['setting_site_start'] = 'Startsida';
$_lang['setting_site_start_desc'] = 'Skriv in ID till resursen du vill ha som startsida här.<br /><strong>OBS: Se till att detta ID tillhör en existerande resurs, och att den har blivit publicerad!</strong>';
$_lang['setting_site_start_err'] = 'Ange ett dokument-ID för webbplatsens startsida.';

$_lang['setting_site_status'] = 'Webbplatsens status';
$_lang['setting_site_status_desc'] = 'Välj "Ja" för att publicera din webbplats. Väljer du "Nej", kommer dina besökare att se meddelandet för "Webbplatsen inte tillgänglig" och kommer inte att kunna besöka sidan.';
$_lang['setting_site_status_err'] = 'Ange om webbplatsen är online (Ja) eller offline (Nej).';

$_lang['setting_site_unavailable_message'] = 'Meddelande för<br />"Webbplatsen inte tillgänglig"';
$_lang['setting_site_unavailable_message_desc'] = 'Meddelandet som visas när webbplatsen är offline, eller när något fel har inträffat.<br /><strong>OBS: Detta meddelande visas bara om ingen "Webbplatsen inte tillgänglig"-sida bestämts.</strong>';

$_lang['setting_site_unavailable_page'] = 'Sida för<br />"Webbplatsen inte tillgänglig"';
$_lang['setting_site_unavailable_page_desc'] = 'Ange ID för den resurs du vill använda som en offline-sida här. <br /><strong>OBS: Kontrollera att detta ID hör till en existerande resurs och att den blivit publicerad!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Ange ett dokument-ID för sidan som visas när webbplatsen inte är tillgänglig.';

$_lang['setting_strip_image_paths'] = 'Skriv om sökvägar till resurser?';
$_lang['setting_strip_image_paths_desc'] = 'Om denna inställning sätts till "Nej", så kommer MODx att skriva sökvägarna till resurser i filhanteraren (bilder, filer, flash etc.) som absoluta URLer. Relativa URLer är användbara om du ska flytta din MODX-installation, tex från en testserver till en produktionsserver. Om du inte har någon aning om vad det här betyder, så är det bäst att lämna inställningen satt till "Ja".';

$_lang['setting_tree_root_id'] = 'Trädets rot-ID';
$_lang['setting_tree_root_id_desc'] = 'Sätt denna till ett giltigt resurs-ID för att starta resursträdet till vänster under den noden som rot. Användaren kommer bara att kunna se resurser som är barn till den angivna resursen.';

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
$_lang['setting_upload_images_desc'] = 'Här kan du skriva en lista med de typer av bildfiler som kan laddas upp till "assets/images/" med filhanteraren. Skriv i suffixen för bildfilerna, separerade med kommatecken.';

$_lang['setting_upload_maxsize'] = 'Största storlek för uppladdningar';
$_lang['setting_upload_maxsize_desc'] = 'Skriv den största filstorleken som kan laddas upp via filhanteraren. Storleken måste anges i bytes.<br /><strong>OBS: Stora filer kan ta väldigt lång tid att ladda upp!</strong>';

$_lang['setting_upload_media'] = 'Uppladdningsbara mediatyper';
$_lang['setting_upload_media_desc'] = 'Här kan du skriva en lista med de typer av mediafiler som kan laddas upp till "assets/media/" med filhanteraren. Skriv i suffixen för mediatyperna, separerade med kommatecken.';

$_lang['setting_use_alias_path'] = 'Använd vänliga aliassökvägar';
$_lang['setting_use_alias_path_desc'] = 'Sätts detta val till "Ja", kommer hela sökvägen till resursen att visas om resursen har ett alias. Till exempel, om en resurs med aliaset "barn" befinner sig i en behållare med aliaset "foralder", kommer hela sökvägen att visas som "/foralder/barn.html".<br /><strong>Notera: När detta sätts till "Ja" (slår på aliassökvägar), måste du referera objekt (som bilder, css, javascript etc) med en absolut sökväg. Exempel: "/assets/images" istället för "assets/images". Genom att göra så förhindrar du att webbläsaren (eller webbservern) lägger till den relativa sökvägen till aliassökvägen.</strong>';

$_lang['setting_use_browser'] = 'Använd resursläsare';
$_lang['setting_use_browser_desc'] = 'Välj "Ja" för att använda resursläsaren. Detta låter dina användare läsa och ladda upp resurser såsom bilder, flash- och mediafiler till servern.';
$_lang['setting_use_browser_err'] = 'Ange om du vill använda resursläsaren eller inte.';

$_lang['setting_use_editor'] = 'Aktivera richtext-editor';
$_lang['setting_use_editor_desc'] = 'Vill du aktivera en richtext-editor? Om du trivs bättre med att skriva HTML, kan du stänga av editorn genom att ändra denna inställning.<br /><strong>OBS: Denna inställning gäller för samtliga dokument och alla användare!</strong>';
$_lang['setting_use_editor_err'] = 'Ange om du vill använda en RTE-editor eller inte.';

$_lang['setting_use_multibyte'] = 'Använd multibyte-tillägget';
$_lang['setting_use_multibyte_desc'] = 'Sätt till "Ja" om du vill använda mbstring-tillägget för multibyte-tecken i din MODx-installation. Sätt den till "Ja" endast om du har PHP-tillägget mbstring installerat.';

$_lang['setting_webpwdreminder_message'] = 'E-post för webbpåminnelse';
$_lang['setting_webpwdreminder_message_desc'] = 'Skriv ett meddelande som skickas till dina webbanvändare när de begärt ett nytt lösenord via e-post. Innehållshanteraren kommer att skicka ett e-postmeddelande med deras nya lösenord och aktiveringsinformation.<br /><strong>Notera:</strong> Följande platshållare ersätts av innehållshanteraren när ett meddelande skickas:<br /><br />[+sname+] - Namnet på din webbplats<br />[+saddr+] - E-postadressen till din webbplats<br />[+surl+] - Adressen till din webbplats<br />[+uid+] - Användarens inloggningsnamn eller ID<br />[+pwd+] - Användarens lösenord<br />[+ufn+] - Användarens namn<br /><br /><b>Lämna [+uid+] och [+pwd+] i meddelandet, annars får inte mottagaren av e-posten reda på sitt nya användarnamn och lösenord!</b>';
$_lang['setting_webpwdreminder_message_default'] = 'Hej [+uid+]\n\nKlicka på följande länk för att aktivera ditt nya lösenord:\n\n[+surl+]\n\nOm allt går bra använder du följande lösenord för att logga in:\n\nLösenord:[+pwd+]\n\nOm du inte har bett om det här brevet så kan du strunta i det.\n\nVänliga hälsningar\nWebmastern';

$_lang['setting_websignupemail_message'] = 'E-post för webbregistreringar';
$_lang['setting_websignupemail_message_desc'] = 'Här kan du ange det meddelande som skickas till dina webbanvändare när du skapar ett webbkonto för dem, och låter innehållshanteraren skicka ett e-postmeddelande med användarnamn och lösenord.<br /><strong>Notera:</strong> Följande platshållare ersätts av innehållshanteraren när meddelandet skickas:<br /><br />[+sname+] - Namnet på din webbplats<br />[+saddr+] - E-postadressen till din webbplats<br />[+surl+] - Adressen till din webbplats<br />[+uid+] - Användarens inloggningsnamn eller ID<br />[+pwd+] - Användarens lösenord<br />[+ufn+] - Användarens namn<br /><br /><strong>Lämna [+uid+] och [+pwd+] i meddelandet, annars får inte mottagaren av e-posten reda på sitt användarnamn och lösenord!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hej [+uid+] \n\nHär kommer dina inloggningsuppgifter för [+sname+] ([+surl+]):\n\nAnvändarnamn: [+uid+]\nLösenord: [+pwd+]\n\nDu kan ändra ditt lösenord när du loggat in i [+sname+].\n\nVänliga hälsningar\nWebmastern';

$_lang['setting_welcome_screen'] = 'Vissa välkomstmeddelande';
$_lang['setting_welcome_screen_desc'] = 'Om denna sätts till "Ja" kommer ett välkomstmeddelande att visas vid nästa laddning av välkomstsidan och sedan inte visas mer efter det.';

$_lang['setting_which_editor'] = 'Editor att använda';
$_lang['setting_which_editor_desc'] = 'Här kan du välja vilken richtext-editor du vill använda. Du kan ladda ner och installera fler richtext-editorer i MODx pakethanterare.';

$_lang['setting_which_element_editor'] = 'Editor att använda för element';
$_lang['setting_which_element_editor_desc'] = 'Här kan du välja vilken richtext-editor du vill använda när du redigerar element. Du kan ladda ner och installera fler richtext-editorer i pakethanteraren.';

<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Area';
$_lang['area_authentication'] = 'Autentimine ja Turvalisus';
$_lang['area_caching'] = 'Puhveradmine';
$_lang['area_core'] = 'Core Code';
$_lang['area_editor'] = 'Võimalustega Editor';
$_lang['area_file'] = 'Failisüsteem';
$_lang['area_filter'] = 'Filtreeri piirkonna järgi...';
$_lang['area_furls'] = 'Sõbralik URL';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Lexicon ja Keel';
$_lang['area_mail'] = 'Mail';
$_lang['area_manager'] = 'Back-end Manager';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session ja Cookie';
$_lang['area_static_elements'] = 'Static Elements';
$_lang['area_static_resources'] = 'Static Resources';
$_lang['area_lexicon_string'] = 'Piirkonna Lexicon-i Kirje';
$_lang['area_lexicon_string_msg'] = 'Enter the key of the lexicon entry for the area here. If there is no lexicon entry, it will just display the area key.<br />Core Areas:<ul><li>authentication</li><li>caching</li><li>file</li><li>furls</li><li>gateway</li><li>language</li><li>manager</li><li>session</li><li>site</li><li>system</li></ul>';
$_lang['area_site'] = 'Sait';
$_lang['area_system'] = 'Süsteem ja Server';
$_lang['areas'] = 'Areas';
$_lang['charset'] = 'Charset';
$_lang['country'] = 'Country';
$_lang['description_desc'] = 'A short description of the Setting. This can be a Lexicon Entry based on the key, following the format "setting_" + key + "_desc".';
$_lang['key_desc'] = 'The key for the Setting. It will be available in your content via the [[++key]] placeholder.';
$_lang['name_desc'] = 'A Name for the Setting. This can be a Lexicon Entry based on the key, following the format "setting_" + key.';
$_lang['namespace'] = 'Namespace';
$_lang['namespace_desc'] = 'The Namespace that this Setting is associated with. The default Lexicon Topic will be loaded for this Namespace when grabbing Settings.';
$_lang['namespace_filter'] = 'Filtreeri nimeruumi järgi...';
$_lang['search_by_key'] = 'Otsi võtme järgi...';
$_lang['setting_create'] = 'Loo Uus Seade';
$_lang['setting_err'] = 'Palun kontrollige järgnevate väljade andmeid: ';
$_lang['setting_err_ae'] = 'Sellise võtmeg seade juba eksisteerib. Palun kasutage teistsugust nime.';
$_lang['setting_err_nf'] = 'Seadet ei leitud.';
$_lang['setting_err_ns'] = 'Seadet ei olenud määratud';
$_lang['setting_err_remove'] = 'Tekkis viga seade eemaldamisel.';
$_lang['setting_err_save'] = 'Tekkis viga seade salvestamisel.';
$_lang['setting_err_startint'] = 'Seaded ei või alata numbriga.';
$_lang['setting_err_invalid_document'] = 'Dokumenti ID-ga %d ei ole olemas. Palun määrake olemasolev dokument.';
$_lang['setting_remove'] = 'Eemalda Seade';
$_lang['setting_remove_confirm'] = 'Olete kindel, et soovite eemaldada selle seade? See võib teie MODX installatsiooni katki teha.';
$_lang['setting_update'] = 'Muuda Seadet';
$_lang['settings_after_install'] = 'Kuna MODX on hetkel värske install, peate kontrollima neid seaded ja muutma neid seadeid mida soovite. P
Pärast seadete kontrollimist, vajutage \'Salvesta\', uuendamaks seadete andmebaasi<br /><br />';
$_lang['settings_desc'] = 'Here you can set general preferences and configuration settings for the MODX manager interface, as well as how your MODX site runs. <b>Each setting will be available via the [[++key]] placeholder.</b><br />Double-click on the value column for the setting you\'d like to edit to dynamically edit via the grid, or right-click on a setting for more options. You can also click the "+" sign for a description of the setting.';
$_lang['settings_furls'] = 'Sõbralikud URL-id';
$_lang['settings_misc'] = 'Mitmesugust';
$_lang['settings_site'] = 'Sait';
$_lang['settings_ui'] = 'Liidese &amp; Võimalused';
$_lang['settings_users'] = 'Kasutaja';
$_lang['system_settings'] = 'Süsteemi Seaded';
$_lang['usergroup'] = 'Kasutaja Grupp';

// user settings
$_lang['setting_access_category_enabled'] = 'Check Category Access';
$_lang['setting_access_category_enabled_desc'] = 'Use this to enable or disable Category ACL checks (per Context). <strong>NOTE: If this option is set to no, then ALL Category Access Permissions will be ignored!</strong>';

$_lang['setting_access_context_enabled'] = 'Check Context Access';
$_lang['setting_access_context_enabled_desc'] = 'Use this to enable or disable Context ACL checks. <strong>NOTE: If this option is set to no, then ALL Context Access Permissions will be ignored. DO NOT disable this system-wide or for the mgr Context or you will disable access to the manager interface.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Check Resource Group Access';
$_lang['setting_access_resource_group_enabled_desc'] = 'Use this to enable or disable Resource Group ACL checks (per Context). <strong>NOTE: If this option is set to no, then ALL Resource Group Access Permissions will be ignored!</strong>';

$_lang['setting_allow_mgr_access'] = 'Manageri Liidese Juurdepääs';
$_lang['setting_allow_mgr_access_desc'] = 'Valige see valik, et lubada või keelata juudepääsu mangeri liidesele. <strong>Märkus: Kui see valik on no asendis, siis suunatakse kasutaja Manageri Login Startup või Site Start lehele.</strong>';

$_lang['setting_failed_login'] = 'Ebaõnnestunud Sisselogimise Katseid';
$_lang['setting_failed_login_desc'] = 'Siit sate sisestada numbri kui palju kordi võib ebaõnnestunud login katseid proovida kuniks ta blokeeritakse.';

$_lang['setting_login_allowed_days'] = 'Lubatud Päevad';
$_lang['setting_login_allowed_days_desc'] = 'Valige päevad, millal see kasutaja võib sisse logida.';

$_lang['setting_login_allowed_ip'] = 'Lubatud IP Aadress';
$_lang['setting_login_allowed_ip_desc'] = 'Sisestage IP aadress, millelt see kasutaja sisenenda võib. <strong>Märkus: Mitme IP aadressi kasutamisel, eralda need komaga (,)</strong>';

$_lang['setting_login_homepage'] = 'Sisselogimise Koduleht';
$_lang['setting_login_homepage_desc'] = 'Sisestage dokumendi ID, kuhu soovite kasutaja suunata peale seda kui ta on sisseloginud. <strong>Märkus: olge kindel, et ID mille siestate kuulub olemsolevale dokumendile ja on avalikustatud ning juurdepääsetav selle kasutaja poolt!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Juurdepääsu Skeemi Versioon';
$_lang['setting_access_policies_version_desc'] = 'Juurdepääsu süsteemi versioon. ÄRA SEDA MUUDA.';

$_lang['setting_allow_forward_across_contexts'] = 'Allow Forwarding Across Contexts';
$_lang['setting_allow_forward_across_contexts_desc'] = 'When true, Symlinks and modX::sendForward() API calls can forward requests to Resources in other Contexts.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Allow Forgot Password in Manager Login Screen';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Setting this to "No" will disable the forgot password ability on the manager login screen.';

$_lang['setting_allow_tags_in_post'] = 'Luba HTML Tagid POST-is';
$_lang['setting_allow_tags_in_post_desc'] = 'Kui false, siis kõikidest POST tegevustest manageri eemaldatakse HTML-tagid. MODX Soovitab selle valiku jätta true asendisse.';

$_lang['setting_allow_tv_eval'] = 'Enable eval in TV bindings';
$_lang['setting_allow_tv_eval_desc'] = 'Select this option to enable or disable eval in TV bindings. If this option is set to no, the code/value will just be handled as regular text.';

$_lang['setting_anonymous_sessions'] = 'Anonymous Sessions';
$_lang['setting_anonymous_sessions_desc'] = 'If disabled, only authenticated users will have access to a PHP session. This can reduce overhead for anonymous users and the load they impose on a MODX site if they do not need access to a unique session. If session_enabled is false, this setting has no effect as sessions would never be available.';

$_lang['setting_archive_with'] = 'Kasuta PCLZip Arhiive';
$_lang['setting_archive_with_desc'] = 'Kui true asendis, siis kasutatakse PCLZip-i ZipArchive asemel zip laiendusena. Luba see valik, kui tekib lahtipakkimise probleeme Pakkide Halduses.';

$_lang['setting_auto_menuindex'] = 'Menüü vaikimisi indekseerimine';
$_lang['setting_auto_menuindex_desc'] = 'Vali \'Jah\' asend, et lülitada sisse automaatne manüü indeksi loomine.';

$_lang['setting_auto_check_pkg_updates'] = 'Pakkide Uuenduste Automaatne Kontroll';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Kui \'Jah\' asendis, siis MODX automaatselt kontrollib Pakkide uuendusi Pakkide Halduse all. See võib põhjustada tabeli aeglast laadimist.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Puhvri Aegumise Aeg Pakkide uuenduste kontrollimise vahel';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Arv minuteid kui kaukas Pakkide Haldus puhverdab pakkide uunenduste tulemusi.';

$_lang['setting_allow_multiple_emails'] = 'Luba Dublikaat Emailid Kasutajatele';
$_lang['setting_allow_multiple_emails_desc'] = 'Kui lubatud, Kasutajad võivad jagada sama emaili aaadressi.';

$_lang['setting_automatic_alias'] = 'Automaatselt genereeri alias';
$_lang['setting_automatic_alias_desc'] = 'Kui \'Jah\', siis süsteem salvestamisel automaatselt genereerib aliase ressurssi lehe tiitel väljast.';

$_lang['setting_automatic_template_assignment'] = 'Automatic Template Assignment';
$_lang['setting_automatic_template_assignment_desc'] = 'Choose how templates are assigned to new Resources on creation. Options include: system (default template from system settings), parent (inherits the parent template), or sibling (inherits the most used sibling template)';

$_lang['setting_base_help_url'] = 'Base Help URL';
$_lang['setting_base_help_url_desc'] = 'The base URL by which to build the Help links in the top right of pages in the manager.';

$_lang['setting_blocked_minutes'] = 'Blokeeritud Minutid';
$_lang['setting_blocked_minutes_desc'] = 'Siit saate sisestada mitu minutit kasutaja on blokeeritud, peale läbikukkunud sisse logimise katsed on ületatud. Palun sisesta väärtus ainult numbrina (ilma komadeta, tühikuteda jne.)';

$_lang['setting_cache_action_map'] = 'Luba Tegevuste Map-i Puhver';
$_lang['setting_cache_action_map_desc'] = 'Kui lubatud, tegevused (või kontrollerite mapid) puhverdatakse, et vähendada manageri lehetede laadimise aega.';

$_lang['setting_cache_alias_map'] = 'Enable Context Alias Map Cache';
$_lang['setting_cache_alias_map_desc'] = 'When enabled, all Resource URIs are cached into the Context. Enable on smaller sites and disable on larger sites for better performance.';

$_lang['setting_use_context_resource_table'] = 'Use the context resource table for context cache refreshes';
$_lang['setting_use_context_resource_table_desc'] = 'When enabled, context cache refreshes use the context_resource table. This enables you to programmatically have one resource in multiple contexts. If you do not use those multiple resource contexts via the API, you can set this to false. On large sites you will get a potential performance boost in the manager then.';

$_lang['setting_cache_context_settings'] = 'Enable Context Setting Cache';
$_lang['setting_cache_context_settings_desc'] = 'Kui lubatud, context settings will be cached to reduce load times.';

$_lang['setting_cache_db'] = 'Luba Andmebaasi Puhver';
$_lang['setting_cache_db_desc'] = 'Kui lubatud, siis objektid ja SQL-päringute andmed puhverdatakse, et märgatavalt vähendada andmebaasi koormust.';

$_lang['setting_cache_db_expires'] = 'Aegumise Aeg DB puhvrile';
$_lang['setting_cache_db_expires_desc'] = 'See väärtus (sekundites) määrab aja koguse, kui kaua puhver failid püsivad DB andmete puhverdamiselT.';

$_lang['setting_cache_db_session'] = 'Enable Database Session Cache';
$_lang['setting_cache_db_session_desc'] = 'When enabled, and cache_db is enabled, database sessions will be cached in the DB result-set cache.';

$_lang['setting_cache_db_session_lifetime'] = 'Expiration Time for DB Session Cache';
$_lang['setting_cache_db_session_lifetime_desc'] = 'This value (in seconds) sets the amount of time cache files last for session entries in the DB result-set cache.';

$_lang['setting_cache_default'] = 'Puhverdatav vaikimisi';
$_lang['setting_cache_default_desc'] = 'Valige \'Jah\', et muuta kõik Ressurssid puhverdavaks vaikimisi.';
$_lang['setting_cache_default_err'] = 'Palun määrake kas või mitte soovite, et dokumendid oleks puhverdatud vaikimisi.';

$_lang['setting_cache_expires'] = 'Aegumise Aeg Tava Puhvrile';
$_lang['setting_cache_expires_desc'] = 'See väärtus (sekundites) määrab aja koguse, kaua puhver failid kestavad vaikimisi.';

$_lang['setting_cache_resource_clear_partial'] = 'Clear Partial Resource Cache for provided contexts';
$_lang['setting_cache_resource_clear_partial_desc'] = 'When enabled, MODX refresh will only clear resource cache for the provided contexts.';

$_lang['setting_cache_format'] = 'Caching Format to Use';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serialize. One of the formats';

$_lang['setting_cache_handler'] = 'Puhveradmise Handler Class';
$_lang['setting_cache_handler_desc'] = 'Classi nimi, mida kasutatase puhveramisel.';

$_lang['setting_cache_lang_js'] = 'Puhvrerda Lexiconi JS Stringid';
$_lang['setting_cache_lang_js_desc'] = 'Kui true, siis see kasutab serveri päeised, et puhverdada lexiconi stringe, mis laetakse manageri liidessesse JavaScripti poolt.';

$_lang['setting_cache_lexicon_topics'] = 'Puhverda Lexiconi Teemad';
$_lang['setting_cache_lexicon_topics_desc'] = 'Kui lubatud, sii kõik Lexiconi Teemad puhverdatakse, et vähendada tõsiselt laadimise aegu Internationalizationi funktsionaalsuses. MODX soovitab selle seade määrata \'Jah\' asendisse.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Puhverda Mitte-Core kuuluvad Lexiconi Teemad';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Kui keelatud, siis Lexiconi teemasid ei puhverdata, neid mis Core alla ei kuulu. See on kasulik keelta siis, kui arendad omaenda lisasid.';

$_lang['setting_cache_resource'] = 'Luba Osaline Ressurssi Puhver';
$_lang['setting_cache_resource_desc'] = 'Osaline ressurssi puhver on konfigureeritav ressurssi poolt, kui see võimalus on lubatudP. Selle võimaluse keelamine keelab selle võimaluse globaalselt.';

$_lang['setting_cache_resource_expires'] = 'Aegumise Aeg Osalisele Ressurssi Puhvrile';
$_lang['setting_cache_resource_expires_desc'] = 'See väärtus (sekundites) määrab aja, kui kaua osalise Ressurssi puhver failid kestavad puhverdamisel.';

$_lang['setting_cache_scripts'] = 'Luba Skriptide Puhver';
$_lang['setting_cache_scripts_desc'] = 'Kui lubatud, MODX puhverdab kõiki Skripte (Snippeteid ja Pluginaid) failidesse, vähendades koormust serverile. MODX soovitab jätta selle seade asendisse\'Jah\'.';

$_lang['setting_cache_system_settings'] = 'Luba Süsteemi Seadete Puhverdamine';
$_lang['setting_cache_system_settings_desc'] = 'Kui lubatud, süsteemi seaded puhverdatakse, et vähendada laadmise aega. MODX soovitab selle seade jätmist lubavasse asendisse.';

$_lang['setting_clear_cache_refresh_trees'] = 'Värskenda Puud kui Saidi Pühves Puhastatakse';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Kui lubatud, uuendab puud pärast saidi puhvri puhastamist.';

$_lang['setting_compress_css'] = 'Kasuta Kokkusurutud CSS-i';
$_lang['setting_compress_css_desc'] = 'Kui see võimalus on lubatud, siis MODX kasutab kokkusurutud versiooni oma css stiilides, mida kasutatakse manageri liideses. See vähendab märgatavalt laadmise ja käivitamise aegu manageris. Keela ainult siis, kui muudad core elemente.';

$_lang['setting_compress_js'] = 'Kasuta Kokkusurutud Javascript Teeke';
$_lang['setting_compress_js_desc'] = 'Kui lubatud, MODX kasutab kokkusurutud versioone oma JavaScript teekidest, mida kasutatakse manageri liideses. See vähendab märgatavalt laadimise ja käivitamise aega manageris. Keela ainult juhul, kui muudad core elemente.';

$_lang['setting_compress_js_groups'] = 'Use Grouping When Compressing JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Group the core MODX manager JavaScript using minify\'s groupsConfig. Set to Yes if using suhosin or other limiting factors.';

$_lang['setting_compress_js_max_files'] = 'Maximum JavaScript Files Compression Threshold';
$_lang['setting_compress_js_max_files_desc'] = 'The maximum number of JavaScript files MODX will attempt to compress at once when compress_js is on. Set to a lower number if you are experiencing issues with Google Minify in the manager.';

$_lang['setting_concat_js'] = 'Kasuta Ühendatud Javascript Teeke';
$_lang['setting_concat_js_desc'] = 'Kui lubatud, siis MODX kasutab ühendatud (kõik failid on liidetud üheks) versiooni oma JavaScript teekidest manageri liideses. See vähendab märgatavalt laadimise ja käivitamise aega manageris. Keela ainult juhul, kui muudad core elemente.';

$_lang['setting_confirm_navigation'] = 'Confirm Navigation with unsaved changes';
$_lang['setting_confirm_navigation_desc'] = 'When this is enabled, the user will be prompted to confirm their intention if there are unsaved changes.';

$_lang['setting_container_suffix'] = 'Konteineri Järelliide (Suffix)';
$_lang['setting_container_suffix_desc'] = 'Suffix, mis lisatakse Ressursside lõppu, mis on konteinerid, kasutatakse FURL-ide kasutamisel.';

$_lang['setting_context_tree_sort'] = 'Enable Sorting of Contexts in Resource Tree';
$_lang['setting_context_tree_sort_desc'] = 'If set to Yes, Contexts will be alphanumerically sorted in the left-hand Resources tree.';
$_lang['setting_context_tree_sortby'] = 'Sort Field of Contexts in Resource Tree';
$_lang['setting_context_tree_sortby_desc'] = 'The field to sort Contexts by in the Resources tree, if sorting is enabled.';
$_lang['setting_context_tree_sortdir'] = 'Sort Direction of Contexts in Resource Tree';
$_lang['setting_context_tree_sortdir_desc'] = 'The direction to sort Contexts in the Resources tree, if sorting is enabled.';

$_lang['setting_cultureKey'] = 'Keel';
$_lang['setting_cultureKey_desc'] = 'Valige keel kõikidele miite-manageriga seutod Contextidele, kaasaarvatud \'web\'.';

$_lang['setting_date_timezone'] = 'Default Time Zone';
$_lang['setting_date_timezone_desc'] = 'Controls the default timezone setting for PHP date functions, if not empty. If empty and the PHP date.timezone ini setting is not set in your environment, UTC will be assumed.';

$_lang['setting_debug'] = 'Debug';
$_lang['setting_debug_desc'] = 'Controls turning debugging on/off in MODX and/or sets the PHP error_reporting level. \'\' = use current error_reporting, \'0\' = false (error_reporting = 0), \'1\' = true (error_reporting = -1), or any valid error_reporting value (as an integer).';

$_lang['setting_default_content_type'] = 'Default Content Type';
$_lang['setting_default_content_type_desc'] = 'Select the default Content Type you wish to use for new Resources. You can still select a different Content Type in the Resource editor; this setting just pre-selects one of your Content Types for you.';

$_lang['setting_default_duplicate_publish_option'] = 'Default Duplicate Resource Publishing Option';
$_lang['setting_default_duplicate_publish_option_desc'] = 'The default selected option when duplicating a Resource. Can be either "unpublish" to unpublish all duplicates, "publish" to publish all duplicates, or "preserve" to preserve the publish state based on the duplicated Resource.';

$_lang['setting_default_media_source'] = 'Default Media Source';
$_lang['setting_default_media_source_desc'] = 'The default Media Source to load.';

$_lang['setting_default_media_source_type'] = 'Default Media Source Type';
$_lang['setting_default_media_source_type_desc'] = 'The default selected Media Source Type when creating a new Media Source.';

$_lang['setting_default_template'] = 'Vaikimisi Template';
$_lang['setting_default_template_desc'] = 'Valige vaikimisi Template, mida soovite kasutadauutel Ressurssidel. Saate ikka valida teisi templatesid ressurssi editoris, see valik lihtsalt eel-valib ühe template teie eest.';

$_lang['setting_default_per_page'] = 'Vaikimisi Lehe kohta';
$_lang['setting_default_per_page_desc'] = 'Vaikimisi number tulemusi, mida näidta tabelis kogu manageris.';

$_lang['setting_editor_css_path'] = 'Sihtkoht CSS failile';
$_lang['setting_editor_css_path_desc'] = 'Sisestage sihtkoht CSS failile, mida soovite kasutada võimalustega tekstiredakoris (richtext editor). Parim viis on sihtkoht sisestada nõnda, et server root kataloogist, nagu näiteks: /assets/site/style.css. Kui ei soovi laadida stiili richtext editori, jätke see väli tühjaks.';

$_lang['setting_editor_css_selectors'] = 'CSS Selectorid Editori jaoks';
$_lang['setting_editor_css_selectors_desc'] = 'Koma eraldatud list CSS selectoritega richtext editori jaoks.';

$_lang['setting_emailsender'] = 'Registreerumise E-maili From Aadress';
$_lang['setting_emailsender_desc'] = 'Siit saate määrata, e-maili aadressi, mida kasutatakse kautajanime ja parooli e-postide saatmisel kasutajatele.';
$_lang['setting_emailsender_err'] = 'Palun määrake administratiooni emaili aadress.';

$_lang['setting_emailsubject'] = 'Registreerimise E-maili Subject';
$_lang['setting_emailsubject_desc'] = 'Kasutaja registreerumise e-maili vaikimisi subject.';
$_lang['setting_emailsubject_err'] = 'Palun määrake subject registreerumise emailile.';

$_lang['setting_enable_dragdrop'] = 'Luba Drag/Drop Ressurssi/Elemendi Puus';
$_lang['setting_enable_dragdrop_desc'] = 'Kui off, siis välditakse Ressurssi ja Elemendi Puus draggimist ja droppimist.';

$_lang['setting_error_page'] = 'Vealeht';
$_lang['setting_error_page_desc'] = 'Sisestage dokumendi ID, mida soovite saata kasutajatele, kui nad pärivad dokumenti, mis ei eksisteeri. <strong>Märkus: olge kindel, et see ID kuulub olemasolevale dokumendile ja et dokument oleks avalikustatud!</strong>';
$_lang['setting_error_page_err'] = 'palun määrake dokumendi ID mis on vealeheks.';

$_lang['setting_ext_debug'] = 'ExtJS debug';
$_lang['setting_ext_debug_desc'] = 'Whether or not to load ext-all-debug.js to help debug your ExtJS code.';

$_lang['setting_extension_packages'] = 'Laiendus Paketid';
$_lang['setting_extension_packages_desc'] = 'Koma eraldatud nimekiri pakettidest, mida laadida MODX A comma separated list of packages to load on MODX käivitamisel. Formaadis packagename:pathtomodel';

$_lang['setting_enable_gravatar'] = 'Enable Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'If enabled, Gravatar will be used as a profile image (if user do not have profile photo uploaded).';

$_lang['setting_failed_login_attempts'] = 'Ebaõnnestunud Sisselogimise Katseid';
$_lang['setting_failed_login_attempts_desc'] = 'Arv läbikukkunud sisselogimise katseid, mida kasutaja saab sootatada, enne kui ta \'blokeeritakse\'.';

$_lang['setting_fe_editor_lang'] = 'Front-end Editori Keel';
$_lang['setting_fe_editor_lang_desc'] = 'Valige keel editorile, kui seda kasutatakse front-endis.';

$_lang['setting_feed_modx_news'] = 'MODX Uudiste Feed';
$_lang['setting_feed_modx_news_desc'] = 'Määrake URL RSS feed-ile, et kuvada MODX uundiseid manageris.';

$_lang['setting_feed_modx_news_enabled'] = 'MODX Uudiste Feed Lubatud';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Kui \'Ei\', siis MODX peaidab uudised welcome sektsioonist manageris.';

$_lang['setting_feed_modx_security'] = 'MODX Turva Teadete Feed URL';
$_lang['setting_feed_modx_security_desc'] = 'Määrake URL RSS feed-ile MODX Turva Teadete saamiseks manageris.';

$_lang['setting_feed_modx_security_enabled'] = 'MODX Turva Feed Lubatud';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Kui \'Ei\', siis MODX peidab Turva feedi welcome sektsioonist manageris.';

$_lang['setting_filemanager_path'] = 'Failide Manageri Path';
$_lang['setting_filemanager_path_desc'] = 'IIS tihtipeale ei täida document_root välja õigesti, mida ksutatakse faili manageri poolt. Kui teil on probleeme faili managerigia, kontrollige, et see oath oleks õige root kataloogi peale, kui asub teie MODX installatsioon..';

$_lang['setting_filemanager_path_relative'] = 'Kas Is Faili Manager Path on Relatiivne?';
$_lang['setting_filemanager_path_relative_desc'] = 'Kui teie filemanager_path seade on relatiivne MODX base_path suhtes, siis palun määrake see sede Jah asendisse. Kui teie filemanager_path on väljaspool docroot-i, määrakse see Ei peale.';

$_lang['setting_filemanager_url'] = 'Failide Manageri Url';
$_lang['setting_filemanager_url_desc'] = 'Valikuline. Määra juhul, kui soovite määrata kindla URL-i, et juurdepääseda failidele Failide Manageri kaudu (kasulik, kui olete filemanager_path määranud kataloogi peale, mis asub väljaspool MODX webroot-i). Olge kindlad, et see on veebist ligipääsetav URL vastavalt filemanager_path seade väärtusele. Kui jätate tühjaks, siis MODX üritab automaatselt selle välja arvutada.';

$_lang['setting_filemanager_url_relative'] = 'On Faili Manager URL Relatiivne?';
$_lang['setting_filemanager_url_relative_desc'] = 'Kui teie filemanager_url seade on relatiivne MODX base_url suhtes, siis palun määrake see seade jah asendisse. Kui teie filemanager_url on väljaspool põhi webroot-i, määrakse see Ei asendisse.';

$_lang['setting_forgot_login_email'] = 'Ununenud kasutajatunnuste Email';
$_lang['setting_forgot_login_email_desc'] = 'Template emailile, mis saadetakse kasutajale, kui nad on unustanud oma MODX kasutajanime ja/või parooli';

$_lang['setting_form_customization_use_all_groups'] = 'Use All User Group Memberships for Form Customization';
$_lang['setting_form_customization_use_all_groups_desc'] = 'If set to true, FC will use *all* Sets for *all* User Groups a member is in when applying Form Customization Sets. Otherwise, it will only use the Set belonging to the User\'s Primary Group. Note: setting this to Yes might cause bugs with conflicting FC Sets.';

$_lang['setting_forward_merge_excludes'] = 'sendForward Exclude Fields on Merge';
$_lang['setting_forward_merge_excludes_desc'] = 'A SymLink merges non-empty field values over the values in the target Resource; using this comma-delimited list of excludes prevents specified fields from being overridden by the SymLink.';

$_lang['setting_friendly_alias_lowercase_only'] = 'FURL Lowercase Aliases';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Määrab, kas ainult lubada lowercase tähed Ressurssi aliases.';

$_lang['setting_friendly_alias_max_length'] = 'FURL Alias Maksimaalne Pikkus';
$_lang['setting_friendly_alias_max_length_desc'] = 'Kui suurem kui null, maksimaalne arv tähti, mis on lubatud Ressurssi aliases. Null tähenab piiramatut.';

$_lang['setting_friendly_alias_realtime'] = 'FURL Alias Real-Time';
$_lang['setting_friendly_alias_realtime_desc'] = 'Determines whether a resource alias should be created on the fly when typing the pagetitle or if this should happen when the resource is saved (automatic_alias needs to be enabled for this to have an effect).';

$_lang['setting_friendly_alias_restrict_chars'] = 'FURL Aliase Tähtede Piiramise Meetod';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'Meetod, mida kasutatakse tähtede piiramiseks Ressurssi aliases. "pattern" lubab RegEx patternit kasutada, "legal" lubab kõik kõiki lubatud URL tähti, "alpha" lubab ainult tähti, mis on tähestikus ja "alphanumeric" lubab ainult tähti ja numbreid.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'FURL Aliase Tähtede Piiramise Muster';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Korrektne RegEx pattern piiramaks tähti, mida võib kasutada Ressurssi aliases.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'FURL Alias Eemalda Elemendi Tagid';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Määrab kas Elementide tagid tuleb eemaldada Ressurssi aliasest.';

$_lang['setting_friendly_alias_translit'] = 'FURL Aliase Transliteratsioon';
$_lang['setting_friendly_alias_translit_desc'] = 'Transliteratsiooni meetod mida kaustada Ressurssi aliases. Tühi või "none" on vaikimisiväärtus, m is jätab transliteratsiooni vahele. Teised väärtuste võimalused on "iconv" (kui php seda võimadab) või nimetatud transliteratsiooni tabel, mis on kohandatud transliteratsiooni teenuse class.';

$_lang['setting_friendly_alias_translit_class'] = 'FURL Aliase Transliteratsiooni Teenuse Class';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Valikuline teenuse class, mis pakub nimetatud transliteratsiooni teenuseid FURL Aliase genereermisel/filtreerimisel.';

$_lang['setting_friendly_alias_translit_class_path'] = 'FURL Alias Transliteration Service Class Path';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'Paki asukoht, kust FURL Alias Transliteration Service Class laetakse.';

$_lang['setting_friendly_alias_trim_chars'] = 'FURL Aliase Trim Tähed';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Tähed, mis tuleb Ressurssi aliase lõpust eemaldada.';

$_lang['setting_friendly_alias_word_delimiter'] = 'FURL Aliase Sõnade Eraldaja';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Eelistatud eraldaja, mida kasutada friendly URL aliaste slug-ides.';

$_lang['setting_friendly_alias_word_delimiters'] = 'FURL Aliase Sõnade Eraldajad';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Tähed mis esindavad sõnade eraldajaid kui töödeltakse friendly URL aliase sluge. Need tähed konverditakse ja konsolideeritakse eelistatud FURL aliase sõna eraldajaga.';

$_lang['setting_friendly_urls'] = 'Kasuta Friendly URLe';
$_lang['setting_friendly_urls_desc'] = 'See lubab kasutada otsingumootori jaoks sõbralikke URLe MODX-iga. Palun pnagetähele, et see töötab ainult MODX installatsioonidel, mis töötavad Apache veebiserveril ja te peate kirjutama .htaccess-i faili, et see võimalus töötaks. Uurige .htaccess faili mis on kaasapandud MODX-iga, et saada rohkem infot.';
$_lang['setting_friendly_urls_err'] = 'Palun määrake kas või mitte soovite kasutada sõbralikke URLe.';

$_lang['setting_friendly_urls_strict'] = 'Use Strict Friendly URLs';
$_lang['setting_friendly_urls_strict_desc'] = 'When friendly URLs are enabled, this option forces non-canonical requests that match a Resource to 301 redirect to the canonical URI for that Resource. WARNING: Do not enable if you use custom rewrite rules which do not match at least the beginning of the canonical URI. For example, a canonical URI of foo/ with custom rewrites for foo/bar.html would work, but attempts to rewrite bar/foo.html as foo/ would force a redirect to foo/ with this option enabled.';

$_lang['setting_global_duplicate_uri_check'] = 'Check for Duplicate URIs Across All Contexts';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Select \'Yes\' to make duplicate URI checks include all Contexts in the search. Otherwise, only the Context the Resource is being saved in is checked.';

$_lang['setting_hidemenu_default'] = 'Peida Vaikimisi Menüüdest';
$_lang['setting_hidemenu_default_desc'] = 'Valige \'Jah\', et teha kõik uued ressurssid menüüst peidetuks vaikimisi.';

$_lang['setting_inline_help'] = 'Show Inline Help Text for Fields';
$_lang['setting_inline_help_desc'] = 'If \'Yes\', then fields will display their help text directly below the field. If \'No\', all fields will have tooltip-based help.';

$_lang['setting_link_tag_scheme'] = 'URL Generation Scheme';
$_lang['setting_link_tag_scheme_desc'] = 'URL generation scheme for tag [[~id]]. Available options <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\modX::makeUrl()" target="_blank">here</a>.';

$_lang['setting_locale'] = 'Locale';
$_lang['setting_locale_desc'] = 'Set the locale for the system. Leave blank to use the default. See <a href="http://php.net/setlocale" target="_blank">the PHP documentation</a> for more information.';

$_lang['setting_lock_ttl'] = 'Lock Time-to-Live';
$_lang['setting_lock_ttl_desc'] = 'The number of seconds a lock on a Resource will remain for if the user is inactive.';

$_lang['setting_log_level'] = 'Logging Level';
$_lang['setting_log_level_desc'] = 'The default logging level; the lower the level, the fewer messages that are logged. Available options: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO), and 4 (DEBUG).';

$_lang['setting_log_target'] = 'Logging Target';
$_lang['setting_log_target_desc'] = 'The default logging target where log messages are written. Available options: \'FILE\', \'HTML\', or \'ECHO\'. Default is \'FILE\' if not specified.';

$_lang['setting_log_deprecated'] = 'Log Deprecated Functions';
$_lang['setting_log_deprecated_desc'] = 'Enable to receive notices in your error log when deprecated functions are used.';

$_lang['setting_mail_charset'] = 'Mail Charset';
$_lang['setting_mail_charset_desc'] = 'Vaikimisi tähestik, mida kasutada e-mailides, nt: \'iso-8859-1\' või \'UTF-8\'';

$_lang['setting_mail_encoding'] = 'Mail Kodeering';
$_lang['setting_mail_encoding_desc'] = 'Määrab sõnumi kodeeringu (encoding). Valikud on selle jaoks "8bit", "7bit", "binary", "base64" ja "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Kasuta SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Kui true, MODX üritab kasutada SMTP-d maili funktsioonides.';

$_lang['setting_mail_smtp_auth'] = 'SMTP Autentimine';
$_lang['setting_mail_smtp_auth_desc'] = 'Määrab SMTP autentimise. Kasutab mail_smtp_user ja mail_smtp_pass seadet.';

$_lang['setting_mail_smtp_helo'] = 'SMTP Helo Message';
$_lang['setting_mail_smtp_helo_desc'] = 'Määrab SMTP HELO teate (Vaikimisi on hostname).';

$_lang['setting_mail_smtp_hosts'] = 'SMTP Hostid';
$_lang['setting_mail_smtp_hosts_desc'] = 'Määrab SMTP hostid. Kõik hostid tuleb eraldada semikooloniga. Saate määrata ka erinevaid porte iga hosti kohta, kasutades seda formaati: [hostname:port] (nt. "smtp1.example.com:25;smtp2.example.com"). Hoste proovitakse järjekorras.';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP Keep-Alive';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Hoiab ära SMTP ühenduse sulgumise oeale maili saatmist. Ei ole soovitatud.';

$_lang['setting_mail_smtp_pass'] = 'SMTP Parool';
$_lang['setting_mail_smtp_pass_desc'] = 'Parool, millega SMTP-s audentida.';

$_lang['setting_mail_smtp_port'] = 'SMTP Port';
$_lang['setting_mail_smtp_port_desc'] = 'Vaikimisi SMTP serveri port.';

$_lang['setting_mail_smtp_prefix'] = 'SMTP Encryption';
$_lang['setting_mail_smtp_prefix_desc'] = 'Sets the encryption of the SMTP connection. Options are "", "ssl" or "tls"';

$_lang['setting_mail_smtp_autotls'] = 'SMTP Auto TLS';
$_lang['setting_mail_smtp_autotls_desc'] = 'Whether to enable TLS encryption automatically if a server supports it, even if "SMTP Encryption" is not set to "tls"';

$_lang['setting_mail_smtp_single_to'] = 'SMTP Single To';
$_lang['setting_mail_smtp_single_to_desc'] = 'Võimaldab To välja eraldi protsessimist individiuaalselemailil, selle asemel, et saata kogu TO aadressitele';

$_lang['setting_mail_smtp_timeout'] = 'SMTP Timeout';
$_lang['setting_mail_smtp_timeout_desc'] = 'Määrab SMTP serveri timeout-i sekundites. See funktsioon ei tööta win32 serveritel.';

$_lang['setting_mail_smtp_user'] = 'SMTP Kasutaja';
$_lang['setting_mail_smtp_user_desc'] = 'Kasutaja, millega audentida SMTP-s.';

$_lang['setting_main_nav_parent'] = 'Main menu parent';
$_lang['setting_main_nav_parent_desc'] = 'The container used to pull all records for the main menu.';

$_lang['setting_manager_direction'] = 'Manageri Teksi Suund';
$_lang['setting_manager_direction_desc'] = 'Valige suund, kuidas teksti rendrerdatakse Manageris: left to right või right to left.';

$_lang['setting_manager_date_format'] = 'Manager Kuupäeva Formaat';
$_lang['setting_manager_date_format_desc'] = 'PHP date() funktsiooni formaat, kuidas kuupäevasid esitatakse manageris.';

$_lang['setting_manager_favicon_url'] = 'Manager Favicon URL';
$_lang['setting_manager_favicon_url_desc'] = 'If set, will load this URL as a favicon for the MODX manager. Must be a relative URL to the manager/ directory, or an absolute URL.';

$_lang['setting_manager_js_cache_file_locking'] = 'Enable File Locking for Manager JS/CSS Cache';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Cache file locking. Set to No if filesystem is NFS.';
$_lang['setting_manager_js_cache_max_age'] = 'Manager JS/CSS Compression Cache Age';
$_lang['setting_manager_js_cache_max_age_desc'] = 'Maximum age of browser cache of manager CSS/JS compression in seconds. After this period, the browser will send another conditional GET. Use a longer period for lower traffic.';
$_lang['setting_manager_js_document_root'] = 'Manager JS/CSS Compression Document Root';
$_lang['setting_manager_js_document_root_desc'] = 'If your server does not handle the DOCUMENT_ROOT server variable, set it explicitly here to enable the manager CSS/JS compression. Do not change this unless you know what you are doing.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Enable zlib Output Compression for Manager JS/CSS';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Whether or not to enable zlib output compression for compressed CSS/JS in the manager. Do not turn this on unless you are sure the PHP config variable zlib.output_compression can be set to 1. MODX recommends leaving it off.';

$_lang['setting_manager_lang_attribute'] = 'Manageri HTML ja XML Keele Atribuut';
$_lang['setting_manager_lang_attribute_desc'] = 'Sisestage keele kood, mis sobib paremini valitud manageri keelega, see kindlustab, et brauser saab esitada sisu parimas teile sobilikus formaadis.';

$_lang['setting_manager_language'] = 'Manageri Keel';
$_lang['setting_manager_language_desc'] = 'Valige keel MODX Sihuhaldussüsteemi Managerile.';

$_lang['setting_manager_login_url_alternate'] = 'Alternate Manager Login URL';
$_lang['setting_manager_login_url_alternate_desc'] = 'An alternate URL to send an unauthenticated user to when they need to login to the manager. The login form there must login the user to the "mgr" context to work.';

$_lang['setting_manager_login_start'] = 'Manageri Login Startup';
$_lang['setting_manager_login_start_desc'] = 'Sisestage dokumendi ID, mida soovite saata kasutajale pärast kui nad on sisse loginud manageri. <strong>Märge: olge kindel, et ID kuulub olemasolevale dokumendile ja et see on avalikustatud ning juudepääsetav kasutaja poolt!</strong>';

$_lang['setting_manager_theme'] = 'Manager Theme';
$_lang['setting_manager_theme_desc'] = 'Valige Theme Managerile.';

$_lang['setting_manager_time_format'] = 'Manager Aja Formaat';
$_lang['setting_manager_time_format_desc'] = 'PHP date() funktsiooni formaat, kuidas kellaaja seaded esitatakse manageris.';

$_lang['setting_manager_use_tabs'] = 'Kasuta Tabe Manageri Layoutis';
$_lang['setting_manager_use_tabs_desc'] = 'Kui true, siis manager kasutab tab-e content panes-ide renderdamisel. Teisit, kasutab see portals-eid.';

$_lang['setting_manager_week_start'] = 'Week start';
$_lang['setting_manager_week_start_desc'] = 'Define the day starting the week. Use 0 (or leave empty) for sunday, 1 for monday and so on...';

$_lang['setting_mgr_tree_icon_context'] = 'Context tree icon';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Define a CSS class here to be used to display the context icon in the tree. You can use this setting on each context to customize the icon per context.';

$_lang['setting_mgr_source_icon'] = 'Media Source icon';
$_lang['setting_mgr_source_icon_desc'] = 'Indicate a CSS class to be used to display the Media Sources icons in the files tree. Defaults to "icon-folder-open-o"';

$_lang['setting_modRequest.class'] = 'Request Handler Class';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Media Browser Tree Hide Files';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'If true the files inside folders are not displayed in the Media Browser source tree.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Media Browser Tree Hide Tooltips';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'If true, no image preview tooltips are shown when hovering over a file in the Media Browser tree. Defaults to true.';

$_lang['setting_modx_browser_default_sort'] = 'Media Browser Default Sort';
$_lang['setting_modx_browser_default_sort_desc'] = 'The default sort method when using the Media Browser in the manager. Available values are: name, size, lastmod (last modified).';

$_lang['setting_modx_browser_default_viewmode'] = 'Media Browser Default View Mode';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'The default view mode when using the Media Browser in the manager. Available values are: grid, list.';

$_lang['setting_modx_charset'] = 'Character encoding';
$_lang['setting_modx_charset_desc'] = 'Palun valige millist character encoding soovite kasutada. Teadmiseks, et MODX-i on teistud mitmete encodingutega, kuid mitte kõigiga. Enamus keelte jaoks vaikeväärtus UTF-8 on eelistatud.';

$_lang['setting_new_file_permissions'] = 'Uue Faili Õigused';
$_lang['setting_new_file_permissions_desc'] = 'Kui laete üles uue faili File Manageri kaudu, siis File Manager üritab muuta faili õigused selle seade järgi. See ei pruugi töödata osadel serveritel, nagu näiteks IIS - sel juhul peate käsitsi muutma faili õiguseid.';

$_lang['setting_new_folder_permissions'] = 'Uue Kausta Õigused';
$_lang['setting_new_folder_permissions_desc'] = 'Luues uut kausta File Manageris, siis File Manager üritab muuta kausta õigused selle seade järgi. See ei pruugi töödata osadel serveritel, nagu näiteks IIS - sel juhul peate käsitsi muutma faili õiguseid.';

$_lang['setting_parser_recurse_uncacheable'] = 'Delay Uncacheable Parsing';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'If disabled, uncacheable elements may have their output cached inside cacheable element content. Disable this ONLY if you are having problems with complex nested parsing which stopped working as expected.';

$_lang['setting_password_generated_length'] = 'Automaatselt--Genereeritud Parooli Pikkus';
$_lang['setting_password_generated_length_desc'] = 'Automaatselt genereeritud Kasutaja parooli pikkus.';

$_lang['setting_password_min_length'] = 'Minimaalne Parooli Pikkus';
$_lang['setting_password_min_length_desc'] = 'Minimaalne kasutaja parooli pikkus.';

$_lang['setting_preserve_menuindex'] = 'Preserve Menu Index When Duplicating Resources';
$_lang['setting_preserve_menuindex_desc'] = 'When duplicating Resources, the menu index order will also be preserved.';

$_lang['setting_principal_targets'] = 'ACL Targets to Load';
$_lang['setting_principal_targets_desc'] = 'Customize the ACL targets to load for MODX Users.';

$_lang['setting_proxy_auth_type'] = 'Proxy Autentimise Tüüp';
$_lang['setting_proxy_auth_type_desc'] = 'Toetab kas BASIC või NTLM tüüpi.';

$_lang['setting_proxy_host'] = 'Proxy Host';
$_lang['setting_proxy_host_desc'] = 'Kui teie server kasutab proxy-it, pange proxy hostinimi siia, et lubada see MODX funktsioonidel mis võivad vajada proxy-it, nagu näiteks Pakkide Haldus.';

$_lang['setting_proxy_password'] = 'Proxy Parool';
$_lang['setting_proxy_password_desc'] = 'Nõutud parool millega autentiseerida proxy serveris.';

$_lang['setting_proxy_port'] = 'Proxy Port';
$_lang['setting_proxy_port_desc'] = 'Proxy serveri port.';

$_lang['setting_proxy_username'] = 'Proxy Kasutajanimi';
$_lang['setting_proxy_username_desc'] = 'Kasutajanimi millega autentiseerida proxy serveris.';

$_lang['setting_photo_profile_source'] = 'User photo Media Source';
$_lang['setting_photo_profile_source_desc'] = 'The Media Source used to store users profiles photos. Defaults to default Media Source.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Luba src Peale Document Root-i';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Näitab, kas src path on lubatud väljaspool document rooti. See on kasulik, mitme-contextigia lehtedel, mis asuvad omaette virtuaal hostides.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb Max Puhvri Aeg';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Kustutab puhverdatud pisipildid, mida pole vaadatud X päeva jooksul.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb Max Puhvri Suurus';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Kustutab pisipildid mida pole ammu kuvatud, kui puihver kasvab suuremaks kui X megabaiti.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Max Puhvris Faile';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Kustutab pisipildid mida pole ammu kuvatud, kui puhvris on rohkem kui X faili.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb Puhverda Source Faile';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Kas puhverdada või mitte source faile, kui need laetakse. Soovitatud on off.';

$_lang['setting_phpthumb_document_root'] = 'PHPThumb Document Root';
$_lang['setting_phpthumb_document_root_desc'] = 'Set this if you are experiencing issues with the server variable DOCUMENT_ROOT, or getting errors with OutputThumbnail or !is_resource. Set it to the absolute document root path you would like to use. If this is empty, MODX will use the DOCUMENT_ROOT server variable.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb Vea Tasuta Värv';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Hex väärtus, ilma algava # märgita, näitab veateate tausta värvi phpThumb-il.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb Vea Fondi Suurus';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Väärtus em-ides näitab fondi suurust, mis ilmub phpThumb-i vea teate väljundis.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb Vea Fondi Värv';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'hex väärtus, ilma # märgita, Näitab fondi värvi, mis ilmub phpThumb-i vea teate väljundis';

$_lang['setting_phpthumb_far'] = 'phpThumb Force Aspect Ratio';
$_lang['setting_phpthumb_far_desc'] = 'Vaikimsi far seade phpThumb-ile kui kasutuses MODX-iga. Vaikimisi C, et sundida aspect ratio pildi keskme suunas.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb ImageMagick Path';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Valikuline. Määrake alternatiivne ImageMagick sihtkoht siit, millega genereerida pisipilte phpThumb-iga, kui see ei asu PHP default asukohas.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Hotlinking Disabled';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Remote servers are allowed in the src parameter unless you disable hotlinking in phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Hotlinking Erase Image';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Indicates if an image generated from a remote server should be erased when not allowed.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Hotlinking Not Allowed Message';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'A message that is rendered instead of the thumbnail when a hotlinking attempt is rejected.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Hotlinking Valid Domains';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'A comma-delimited list of hostnames that are valid in src URLs.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Offsite Linking Disabled';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Disables the ability for others to use phpThumb to render images on their own sites.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Offsite Linking Erase Image';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Indicates if an image linked from a remote server should be erased when not allowed.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Offsite Linking Require Referrer';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'If enabled, any offsite linking attempts will be rejected without a valid referrer header.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Offsite Linking Not Allowed Message';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'A message that is rendered instead of the thumbnail when an offsite linking attempt is rejected.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Offsite Linking Valid Domains';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'A comma-delimited list of hostnames that are valid referrers for offsite linking.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Offsite Linking Watermark Source';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Optional. A valid file system path to a file to use as a watermark source when your images are rendered offsite by phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Zoom-Crop';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Vaikimisi zc seade phpThumb-ile kui kasutuses MODX-iga. Vaikimisi 0, et ära hoida zoom-i ja cropp-imist.';

$_lang['setting_publish_default'] = 'Avalikustatud Vaikimisi';
$_lang['setting_publish_default_desc'] = 'Valige \'Jah\', et muuta kõik ressurssid vaikimisi avalikustatuks.';
$_lang['setting_publish_default_err'] = 'Palun määrake, kas soovite või mitte, et dokumendid oleksid avalikustatud vaikimisi.';

$_lang['setting_rb_base_dir'] = 'Ressurssi path';
$_lang['setting_rb_base_dir_desc'] = 'Siestage füüsiline asukoht ressurssi kaustani. See seade tavaliselt genereeritakse automaatselt. Kuid, kui kasutate IIS-i, siis ei pruugi MODX olla võimeline kaustasid välja nuputama, põhustades Resource Browser näitama erroreid. Sellisel juhul saate sisestada kasuta piltideni siit (sisestage kaust nii nagu näete seda Windows Explorer-is). <strong>MÄRKUS:</strong> Ressurssi kataloog peab sisaldama alamkaustasid: images, files, flash ja media selleks, et resssurssi brauseri saaks töödata korralikult.';
$_lang['setting_rb_base_dir_err'] = 'Palun määrake ressurssi brauseri baas kataloog.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Seda ressurssi kataloogi ei eksisteeri või sellele puudub juurdepääs. Palun määrake kehtiv kataloog või muudke kataloogi õiguseid.';

$_lang['setting_rb_base_url'] = 'Ressurssi URL';
$_lang['setting_rb_base_url_desc'] = 'Sisestage virtuaalne sihtkoht ressurssi kataloogini. See seade tavaliselt genereeritakse automaatselt. Kuid, kui kasutate IIS-i, siis ei pruugi MODX olla võimeline kaustasid välja nuputama, põhustades Resource Browser näitama erroreid. Sellisel juhul saate sisestada URL-i piltide kataloogini siit (URL nagu isestaksite seda Internet Explorer-is).';
$_lang['setting_rb_base_url_err'] = 'Palun määrake ressurssi brauseri baas URL.';

$_lang['setting_request_controller'] = 'Päringu Kontrolleri Failinimi';
$_lang['setting_request_controller_desc'] = 'Põhi päringu kontrolleri failinimi, millest MODX laetakse. Enamus kasutajaid jätavad selle index.php peale.';

$_lang['setting_request_method_strict'] = 'Strict Request Method';
$_lang['setting_request_method_strict_desc'] = 'If enabled, requests via the Request ID Parameter will be ignored with FURLs enabled, and those via Request Alias Parameter will be ignored without FURLs enabled.';

$_lang['setting_request_param_alias'] = 'Päringu Aliase Parameeter';
$_lang['setting_request_param_alias_desc'] = 'GET parameeteri nimi, millega idenfitseerida Ressurssi alaseid, kui toimub suunamine FURL-idega.';

$_lang['setting_request_param_id'] = 'Päringu ID Parameeter';
$_lang['setting_request_param_id_desc'] = 'GET parameeteri nimi, millega idenfitseerida Ressurssi ID-sid, kui FURL-id ei ole kasutusel.';

$_lang['setting_resolve_hostnames'] = 'Lahenda hostinimed';
$_lang['setting_resolve_hostnames_desc'] = 'kas soovite, et MODX üritaks lahendada külastajte hostinimesid, kui nad külastavad teie lehte? Hostinimede lahendamine võib põhjustada lisa koormust teie serverile, kuigi külastajatele jääb see märkamatuks.';

$_lang['setting_resource_tree_node_name'] = 'Ressurssi Puu Üksuse Väli';
$_lang['setting_resource_tree_node_name_desc'] = 'Määra Ressurssi väli, mida kasutada üksutse kuvamisel Ressurssi Puus. Vaikimisi Kasutatakse pagetitle välja, kui suvalist Ressurssi välja on võimalk kasutada, nagu näiteks menutitle, alias, longtitle, jne.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Resource Tree Node Fallback Field';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Specify the Resource field to use as fallback when rendering the nodes in the Resource Tree. This will be used if the resource has an empty value for the configured Resource Tree Node Field.';

$_lang['setting_resource_tree_node_tooltip'] = 'Resource Tree Tooltip Field';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Specify the Resource field to use when rendering the nodes in the Resource Tree. Any Resource field can be used, such as menutitle, alias, longtitle, etc. If blank, will be the longtitle with a description underneath.';

$_lang['setting_richtext_default'] = 'Richtext Editor Vaikimisi';
$_lang['setting_richtext_default_desc'] = 'Valige \'Jah\', et kõik Ressurssid kasutaksid Richtext Editor vaikimisi.';

$_lang['setting_search_default'] = 'Searchable Vaikimisi';
$_lang['setting_search_default_desc'] = 'Valige \'Jah\', et muuta kõik uued ressurssid otsitavaks vaikimisi.';
$_lang['setting_search_default_err'] = 'Palun määrake kas soovite, et dokumendid oleksid otsitavad vaikismisi.';

$_lang['setting_server_offset_time'] = 'Server offset time';
$_lang['setting_server_offset_time_desc'] = 'Valige tundide erinevus, mis on teie ja serveri asukoha vahel.';

$_lang['setting_server_protocol'] = 'Serveri Tüüp';
$_lang['setting_server_protocol_desc'] = 'Kui teie leht kasutab https ühendust, siis määrake see siin.';
$_lang['setting_server_protocol_err'] = 'Palun määrake kas teil on turva leht võit mitte.';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Session Küpsise Domeen';
$_lang['setting_session_cookie_domain_desc'] = 'Kasutage seda seadet, et muuta sessiooni küpsise domeeni.';

$_lang['setting_session_cookie_lifetime'] = 'Session Küpsise Eluiga';
$_lang['setting_session_cookie_lifetime_desc'] = 'Kasutage seda seadet, et määrata sessiooni küpsise eluiga sekundites. Seda kasutatakse "jäta mind meelde" sisselogimise võimalusel, selle küpsise eluiga.';

$_lang['setting_session_cookie_path'] = 'Session Küpsise Sihtkoht';
$_lang['setting_session_cookie_path_desc'] = 'Kasutage seda seadet, et määrata küpsise sihtkohta (path), mida kasutatakse lehe spetssifiliste sessiooni kõpsiste denfitseerimiseks.';

$_lang['setting_session_cookie_secure'] = 'Session Küpisese Turvalisus';
$_lang['setting_session_cookie_secure_desc'] = 'Lubage see seade, kui soovite turvalisi sessioon küpsiseid kasutada.';

$_lang['setting_session_cookie_httponly'] = 'Session Cookie HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Use this setting to set the HttpOnly flag on session cookies.';

$_lang['setting_session_cookie_samesite'] = 'Session Cookie Samesite';
$_lang['setting_session_cookie_samesite_desc'] = 'Choose Lax or Strict.';

$_lang['setting_session_gc_maxlifetime'] = 'Session Garbage Collector Max Lifetime';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Allows customization of the session.gc_maxlifetime PHP ini setting when using \'modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Session Handler Classname';
$_lang['setting_session_handler_class_desc'] = 'Sessioonid, mis salvestatakse andmebaasi, kasutage \'modSessionHandler\'. Jätek väli tühjaks, kui soovite standartset PHP sessioonide haldust.';

$_lang['setting_session_name'] = 'Sessiooni Nimi';
$_lang['setting_session_name_desc'] = 'Kasutage seda seadet, et määrata sessiooni nimi, mida kasutatakse MODX sessioonides.';

$_lang['setting_settings_version'] = 'Seadete Versioon';
$_lang['setting_settings_version_desc'] = 'Hetkel installitud MODX versioon.';

$_lang['setting_settings_distro'] = 'Settings Distribution';
$_lang['setting_settings_distro_desc'] = 'Hetkel installeeritud MODX distribution.';

$_lang['setting_set_header'] = 'Määra HTTP Headerid';
$_lang['setting_set_header_desc'] = 'Kui lubatud, MODX üritab määrata HTTP headereid Ressurssi jaoks.';

$_lang['setting_send_poweredby_header'] = 'Send X-Powered-By Header';
$_lang['setting_send_poweredby_header_desc'] = 'When enabled, MODX will send the "X-Powered-By" header to identify this site as built on MODX. This helps tracking global MODX usage through third party trackers inspecting your site. Because this makes it easier to identify what your site is built with, it might pose a slightly increased security risk if a vulnerability is found in MODX.';

$_lang['setting_show_tv_categories_header'] = 'Show "Categories" Tabs Header with TVs';
$_lang['setting_show_tv_categories_header_desc'] = 'If "Yes", MODX will show the "Categories" header above the first category tab when editing TVs in a Resource.';

$_lang['setting_signupemail_message'] = 'Registreerumise e-mail';
$_lang['setting_signupemail_message_desc'] = 'Siit saate määrata sõnumi, mis saadetekase kasutajatele, kui loote neidle konto, ning lubate MODX-il saata neile e-maili, mis sisaldab nende kasutajanime ja parooli. <br /><strong>MÄRKUS:</strong> Järgnevad placeholders asendatakse Sisu Halduse poolt sõnumi saatmisel: <br /><br />[[+sname]] - Veebilehe nimi, <br />[[+saddr]] - Veebilehe e-maili aadress, <br />[[+surl]] - Veebilehe aadress, <br />[[+uid]] - Kasutaja sisselogimise nimi või id, <br />[[+pwd]] - Kasutaja parool, <br />[[+ufn]] - Kasutaja täis nimi. <br /><br /><strong>Jätke [[+uid]] ja [[+pwd]] e-maili või muidu kasutajanime ja parooli ei saadeta ja kasutajad ei tea oma kasutajatunnust ja prooli!</strong>';
$_lang['setting_signupemail_message_default'] = 'Tere [[+uid]] \n\nSiin on teie sisselogimise detailid [[+sname]] Sisuhaldus Süsteemi:\n\nKasutajanimi: [[+uid]]\nParool: [[+pwd]]\n\nKui olete sisse loginud Sisuhaldusesse ([[+surl]]), on teil võimalk muuta oma parooli.\n\nParimat,\nLehe Administraator';

$_lang['setting_site_name'] = 'Lahe Nimi';
$_lang['setting_site_name_desc'] = 'Sisestage oma lehe nimi siia.';
$_lang['setting_site_name_err']  = 'Palun sisestage lehe nimi.';

$_lang['setting_site_start'] = 'Lehekülje Algus';
$_lang['setting_site_start_desc'] = 'Sisestage Ressurssi ID, mida soovite kasutada avalehena. <strong>Märkus: olge kindel, et see ID kuulub olemasolevale Ressurssile, ning et see oleks avalikustatud!</strong>';
$_lang['setting_site_start_err'] = 'Palun määrake Ressurssi ID, mis on avaleheks.';

$_lang['setting_site_status'] = 'Lehe Staatus';
$_lang['setting_site_status_desc'] = 'Valige \'Jah\', et avalikustada leht veebi. Kui valite \'Ei\', sisi lehe külastajad näeevad  \'Leht pole saadaval sõnumit\' ja pole võimelised lehe surfama.';
$_lang['setting_site_status_err'] = 'Palun möärake, kas soovite või mitte panna lehe online (Yes) või offline (No).';

$_lang['setting_site_unavailable_message'] = 'Leht pole saadaval sõnum';
$_lang['setting_site_unavailable_message_desc'] = 'Sõnum, mida näidata, kui leht on offline või kui on tekkinud viga. <strong>Märkus: Seda teadet kuvatakse ainult siis, kui \'Leht pole saadaval leht\' valikut ei ole valitud.</strong>';

$_lang['setting_site_unavailable_page'] = 'Leht pole saadaval leht';
$_lang['setting_site_unavailable_page_desc'] = 'Sisestage Ressurssi ID, mida soovita kasutada offline lehena. <strong>MÄRKUS: olge kindel, et ID kuulub olemasolevale Ressurssile ja et see oleks avalikustatud!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Palun määrake dokumendi ID \'leht pole saadaval\' lehele.';

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

$_lang['setting_static_elements_default_category'] = 'Static elements default category';
$_lang['setting_static_elements_default_category_desc'] = 'Specify a default category for creating new static elements.';

$_lang['setting_static_elements_basepath'] = 'Static elements basepath';
$_lang['setting_static_elements_basepath_desc'] = 'Basepath of where to store the static elements files.';

$_lang['setting_resource_static_allow_absolute'] = 'Allow absolute static resource path';
$_lang['setting_resource_static_allow_absolute_desc'] = 'This setting enables users to enter a fully qualified absolute path to any readable file on the server as the content of a static resource. Important: enabling this setting may be considered a significant security risk! It\'s strongly recommended to keep this setting disabled, unless you fully trust every single manager user.';

$_lang['setting_resource_static_path'] = 'Static resource base path';
$_lang['setting_resource_static_path_desc'] = 'When resource_static_allow_absolute is disabled, static resources are restricted to be within the absolute path provided here.  Important: setting this too wide may allow users to read files they shouldn\'t! It is strongly recommended to limit users to a specific directory such as {core_path}static/ or {assets_path} with this setting.';

$_lang['setting_strip_image_paths'] = 'Kirjuta ümber brauseri path-id?';
$_lang['setting_strip_image_paths_desc'] = 'Kui valitud \'Ei\' asend, siis MODX kirjutab faili brauseri resurssi src-id (images, files, flash, jne.) absoluutsete URL-idena. Relatiivsed URL-id on kasulikud, kui soovite kolida MODX installiga, nt: staging serverist production servierisse. Kui teil pole aimugi, mis see tähendab, siis parim on jätta see valik \'Jah\' peale.';

$_lang['setting_symlink_merge_fields'] = 'Merge Resource Fields in Symlinks';
$_lang['setting_symlink_merge_fields_desc'] = 'If set to Yes, will automatically merge non-empty fields with target resource when forwarding using Symlinks.';

$_lang['setting_syncsite_default'] = 'Empty Cache default';
$_lang['setting_syncsite_default_desc'] = 'Select \'Yes\' to empty the cache after you save a resource by default.';
$_lang['setting_syncsite_default_err'] = 'Please state whether or not you want to empty the cache after saving a resource by default.';

$_lang['setting_topmenu_show_descriptions'] = 'Näita kirjeldusi Põhi Menüüs';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Kui \'Ei\', siis MODX peidab kirjeldused põhi menüü linkidelt manageris.';

$_lang['setting_tree_default_sort'] = 'Ressurssi Puu Vaikimisi Sorteeritav Väli';
$_lang['setting_tree_default_sort_desc'] = 'Vaikimisi väli mille järgi soreeritakse Ressurssi puu, kui manager laetakse.';

$_lang['setting_tree_root_id'] = 'Tree Root ID';
$_lang['setting_tree_root_id_desc'] = 'Määrake kehtiv Ressurssi ID, millest vasak Ressurssi puu alguse saab, üksus mis on root. Kasutaja on võimaline nägema ainult Ressursse, mis on selle määratud Ressurssi alamad (children).';

$_lang['setting_tvs_below_content'] = 'Move TVs Below Content';
$_lang['setting_tvs_below_content_desc'] = 'Set this to Yes to move Template Variables below the Content when editing Resources.';

$_lang['setting_ui_debug_mode'] = 'UI Debug Mode';
$_lang['setting_ui_debug_mode_desc'] = 'Set this to Yes to output debug messages when using the UI for the default manager theme. You must use a browser that supports console.log.';

$_lang['setting_udperms_allowroot'] = 'Luba root';
$_lang['setting_udperms_allowroot_desc'] = 'Kas soovite lubada kasutajatel luua uusi Ressursse lehe root-i? ';

$_lang['setting_unauthorized_page'] = 'Authoriseerimata lehte';
$_lang['setting_unauthorized_page_desc'] = 'Sisestage Ressurssi ID, mida soovite saata kasutajatele, kui nad pärivad turvatud või autoriseerimata Ressurssi. <strong>MÄRKUS: olge kindel, et IS kuulub olemasolevale Ressurssile ja et see oleks avalikustatud ja on avalikult juurdepääsetav!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Palun määrake Ressurssi ID authoriseerimata lehe jaoks.';

$_lang['setting_upload_check_exists'] = 'Check if uploaded file exists';
$_lang['setting_upload_check_exists_desc'] = 'When enabled an error will be shown when uploading a file that already exists with the same name. When disabled, the existing file will be quietly replaced with the new file.';

$_lang['setting_upload_files'] = 'Uploadable File Types';
$_lang['setting_upload_files_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/files/\' using the Resource Manager. Please enter the extensions for the filetypes, seperated by commas.';

$_lang['setting_upload_flash'] = 'Üleslaetavad Flashi Tüübid';
$_lang['setting_upload_flash_desc'] = 'Siit saate sisestada nimekirja failidest, mida saab üleslaadida \'assets/flash/\' kasuta, kasutades Ressurssi Manageri. Palun sisestage faililaiendid flashi tüüpidele, eraldatud komadega.';

$_lang['setting_upload_images'] = 'Üleslaetavad Piltide Tüübid';
$_lang['setting_upload_images_desc'] = 'Siit saate sisestada nimekirja failidest, mida saab üleslaadida \'assets/images/\' kasuta, kasutades Ressurssi Manageri. Palun sisestage faililaiendid piltide tüüpidele, eraldatud komadega.';

$_lang['setting_upload_maxsize'] = 'Maksimaalne üleslatav suurus';
$_lang['setting_upload_maxsize_desc'] = 'Sisestage maksimaalne faili suurus, mida saab üleslaadida faili manageri kaudu. Üleslaetav fail suurus tuleb sisestada baitides. <strong>MÄRKUS: Suurte failide üleslaadimisele võib kuluda palju aega!</strong>';

$_lang['setting_upload_media'] = 'Üleslaetavad Meedia Tüübid';
$_lang['setting_upload_media_desc'] = 'Siit saate sisestada nimekirja failidest, mida saab üleslaadida \'assets/media/\' kasuta, kasutades Ressurssi Manageri. Palun sisestage faililaiendid meedia tüüpidele, eraldatud komadega.';

$_lang['setting_use_alias_path'] = 'Kasuta Sõbralikke Aliase Pathe';
$_lang['setting_use_alias_path_desc'] = 'Valides \'jah\' kuvatakse terve path Ressurssini, kuiRessurssil on alias. Näiteks. kui Ressurss aliasega  \'child\' asub konteiner-Ressurssi sees, millel on alias \'parent\', siis täis alias paht Ressurssile kuvatakse kui \'/parent/child.html\'.<br /><strong>MÄRKUS: Valides \'Jah\' (lubades alias path-id), viitavad üksused (nagu näiteks pildid, css, javascriptid, jne) peavad kasutama absoluutseid pathe: näiteks \'/assets/images\' vastupidiselt \'assets/images\'. Tehes nii, hoiate ära, et brauser (või veebiserver) ei lisaks relatiivseid pathe aliase path-ile.</strong>';

$_lang['setting_use_browser'] = 'Luba Ressurssi Brauser';
$_lang['setting_use_browser_desc'] = 'Valige yes, et lubada ressurssi brauser. See lubab teie kasutajatel sirvida ja üleslaadida serverisse ressursse nagu pildid, flash ja meedia.';
$_lang['setting_use_browser_err'] = 'Palun valige, kas soovite või mitte kasutada ressurssi brauserit.';

$_lang['setting_use_editor'] = 'Luba Rich Text Editor';
$_lang['setting_use_editor_desc'] = 'Kas soovite lubada rich text editor? Kui olete harjunud kirjutama HTML-i, siis võite keelata editori "off" seadega. See seade kehtib kõikidele dokumentidele ja kõikidele kasutajatele!';
$_lang['setting_use_editor_err'] = 'Palun määrake, kas soovite või mitte kasutada RTE editori.';

$_lang['setting_use_frozen_parent_uris'] = 'Use Frozen Parent URIs';
$_lang['setting_use_frozen_parent_uris_desc'] = 'When enabled, the URI for children resources will be relative to the frozen URI of one of its parents, ignoring the aliases of resources high in the tree.';

$_lang['setting_use_multibyte'] = 'Kasuta Multibyte Laiendust';
$_lang['setting_use_multibyte_desc'] = 'Määrake "true", kui soovite kasutada mbstring laiendust multibyte tähtede jaoks teie MODX-is. Ainult siis määrake "true" kui teie serveril on mbstring PHP laiendus installeeritud.';

$_lang['setting_use_weblink_target'] = 'Use WebLink Target';
$_lang['setting_use_weblink_target_desc'] = 'Set to true if you want to have MODX link tags and makeUrl() generate links as the target URL for WebLinks. Otherwise, the internal MODX URL will be generated by link tags and the makeUrl() method.';

$_lang['setting_user_nav_parent'] = 'User menu parent';
$_lang['setting_user_nav_parent_desc'] = 'The container used to pull all records for the user menu.';

$_lang['setting_webpwdreminder_message'] = 'Veebi Parooli Meeletuletuse e-mail';
$_lang['setting_webpwdreminder_message_desc'] = 'Sisetage sõnum, mis saadetakse kasutajatele, kui nad tellivad uu parooli e-posti kaudu. Sisu Haldus saadab e-maili, mis sisaldab nende uut parooli ja aktiveerimise informatsiooni. <br /><strong>Märkus:</strong> Järgnevad placeholder-id asendatakse Content Manageri poolt, kui sõnum saadetakse: <br /><br />[[+sname]] - Veebilehe nimi, <br />[[+saddr]] - Veebilehe e-maili aadress, <br />[[+surl]] - Veebilehe aadress, <br />[[+uid]] - Kasutaja sisselogimise tunnus või id, <br />[[+pwd]] - Kasutaja parool, <br />[[+ufn]] - Kasutaja täisnimi. <br /><br /><strong>Jätke [[+uid]] aja [[+pwd]] e-maili või mudiu kasutajanime ja prooli ei saadeta ja kasutajad ei tea omaenda kasutajanime või parooli!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Tere [[+uid]]\n\nUue parooli aktiveerimiseks, klikkige sellel lingil:\n\n[[+surl]]\n\nKui edukas, siis saate sisselogimiseks kasutada järgnevad parooli:\n\nParool:[[+pwd]]\n\nKui teie ei tellinud seda e-maili, siis palun ignoreerige seda.\n\nParimat,\nLahe Administraator';

$_lang['setting_websignupemail_message'] = 'Veebi Registreerumise e-mail';
$_lang['setting_websignupemail_message_desc'] = 'Siit saate määrata sõnumi, mis saadetakse teie kasutajatele, kui nad loovad veebi konto ja lasete  Content Manager saata neile e-maili, mis sisaldab nende kasutajatunnust ja parooli. <br /><strong>Märkus:</strong> Järgnevad placeholder-id asendatakse Content Manageri poolt, kui sõnum saadetakse: <br /><br />[[+sname]] - Veebilehe nimi, <br />[[+saddr]] - Veebilehe e-maili aadress, <br />[[+surl]] - Veebilehe aadress, <br />[[+uid]] - Kasutaja sisselogimise tunnus või id, <br />[[+pwd]] - Kasutaja parool, <br />[[+ufn]] - Kasutaja täisnimi. <br /><br /><strong>Jätke [[+uid]] aja [[+pwd]] e-maili või mudiu kasutajanime ja prooli ei saadeta ja kasutajad ei tea omaenda kasutajanime või parooli!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Tere [[+uid]] \n\nSiin on teie sisselogimise detailid [[+sname]] jaoks:\n\nKasutajatunnus: [[+uid]]\nParool: [[+pwd]]\n\nKui olete siseloginud [[+sname]] ([[+surl]]), on teil võimalus parooli muuta.\n\nParimat,\nLehe Administraator';

$_lang['setting_welcome_screen'] = 'Näita Tervitus Ekraani';
$_lang['setting_welcome_screen_desc'] = 'Kui on true, tervitus ekraan ilmub järgmise eduka laadimise avaleheküljele ja siis ei näidata pärast seda.';

$_lang['setting_welcome_screen_url'] = 'Tervitus Ekraani URL';
$_lang['setting_welcome_screen_url_desc'] = 'URL tervitus ekraani jaoks, mida laadida, kui esimest korda laetakse MODX Revolution.';

$_lang['setting_welcome_action'] = 'Welcome Action';
$_lang['setting_welcome_action_desc'] = 'The default controller to load when accessing the manager when no controller is specified in the URL.';

$_lang['setting_welcome_namespace'] = 'Welcome Namespace';
$_lang['setting_welcome_namespace_desc'] = 'The namespace the Welcome Action belongs to.';

$_lang['setting_which_editor'] = 'Editor, mida ksutada';
$_lang['setting_which_editor_desc'] = 'Siit saate valida, millist Rich Text Editori soovite kasutada. Saate allalaadida ja installida täiendavaid Rich Text Editore Pakkide Halduses.';

$_lang['setting_which_element_editor'] = 'Editor, mida kasutada Elementidel';
$_lang['setting_which_element_editor_desc'] = 'Siit saate valida Rich Text Editori, mida soovite kasutada, kui muudate Elemente. Saate allalaadida ja installida täiendavaid Rich Text Editore Pakkide Halduses.';

$_lang['setting_xhtml_urls'] = 'XHTML URL-id';
$_lang['setting_xhtml_urls_desc'] = 'Kui true, siis kõik genereeritud URL-id MODX-i poolt on XHTML standardi järgi, kaasaarvatud ampersand tähemärk.';

$_lang['setting_default_context'] = 'Default Context';
$_lang['setting_default_context_desc'] = 'Select the default Context you wish to use for new Resources.';

$_lang['setting_auto_isfolder'] = 'Set container automatically';
$_lang['setting_auto_isfolder_desc'] = 'If set to yes, container property will be changed automatically.';

$_lang['setting_default_username'] = 'Default username';
$_lang['setting_default_username_desc'] = 'Default username for an unauthenticated user.';

$_lang['setting_manager_use_fullname'] = 'Show fullname in manager header ';
$_lang['setting_manager_use_fullname_desc'] = 'If set to yes, the content of the "fullname" field will be shown in manager instead of "loginname"';

$_lang['setting_log_snippet_not_found'] = 'Log snippets not found';
$_lang['setting_log_snippet_not_found_desc'] = 'If set to yes, snippets that are called but not found will be logged to the error log.';

$_lang['setting_error_log_filename'] = 'Error log filename';
$_lang['setting_error_log_filename_desc'] = 'Customize the filename of the MODX error log file (includes file extension).';

$_lang['setting_error_log_filepath'] = 'Error log path';
$_lang['setting_error_log_filepath_desc'] = 'Optionally set a absolute path the a custom error log location. You might use placehodlers like {cache_path}.';

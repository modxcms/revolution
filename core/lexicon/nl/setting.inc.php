<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Ruimte';
$_lang['area_authentication'] = 'Authenticatie en Beveiliging';
$_lang['area_caching'] = 'Caching';
$_lang['area_core'] = 'Core';
$_lang['area_editor'] = 'Rich-Tekst Editor';
$_lang['area_file'] = 'Bestandssysteem';
$_lang['area_filter'] = 'Filter op gebied...';
$_lang['area_furls'] = 'Vriendelijke URLs';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Lexicon en taal';
$_lang['area_mail'] = 'E-mail';
$_lang['area_manager'] = 'Back-end Manager';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Sessie en Cookie';
$_lang['area_lexicon_string'] = 'Gebied Lexicon Entry';
$_lang['area_lexicon_string_msg'] = 'Vul hier een key in van een lexicon entry gebied. Indien er geen lexicon entry is, dan wordt de key van het gebied getoond.<br />Core gebieden:<ul><li>authentication</li><li>caching</li><li>file</li><li>furls</li><li>gateway</li><li>language</li><li>manager</li><li>session</li><li>site</li><li>system</li></ul>';
$_lang['area_site'] = 'Site';
$_lang['area_system'] = 'Systeem en Server';
$_lang['areas'] = 'Ruimten';
$_lang['charset'] = 'Karakterset';
$_lang['country'] = 'Land';
$_lang['description_desc'] = 'Een korte beschrijving van de instelling. Kan een Lexicon key zijn.';
$_lang['key_desc'] = 'De key voor de instelling. De instelling zal beschikbaar zijn via de [[++key]] tags.';
$_lang['name_desc'] = 'Een Naam voor de instelling, kan een Lexicon key zijn.';
$_lang['namespace'] = 'Namespace';
$_lang['namespace_desc'] = 'De Namespace waar deze Instelling bij hoort. Het "default" Lexicon Topic voor de Namespace zal beschikbaar zijn wanneer Instellingen worden geladen.';
$_lang['namespace_filter'] = 'Filter op namespace...';
$_lang['search_by_key'] = 'Zoek op key...';
$_lang['setting_create'] = 'Maak nieuwe instelling';
$_lang['setting_err'] = 'Controleer jouw gegevens voor de volgende velden: ';
$_lang['setting_err_ae'] = 'Instelling met deze key bestaat reeds. Definieer een andere keynaam.';
$_lang['setting_err_nf'] = 'Instelling niet gevonden.';
$_lang['setting_err_ns'] = 'Instelling niet gedefinieerd';
$_lang['setting_err_remove'] = 'Er is een fout opgetreden tijdens het proberen te verwijderen van de instelling.';
$_lang['setting_err_save'] = 'Er is een fout opgetreden tijdens het proberen op te slaan van de instelling.';
$_lang['setting_err_startint'] = 'Instelling mag niet starten met een cijfer.';
$_lang['setting_err_invalid_document'] = 'Er is geen document met ID %d. Definieer een geldig document.';
$_lang['setting_remove'] = 'Instelling verwijderen';
$_lang['setting_remove_confirm'] = 'Weet je zeker dat je deze insteling wilt verwijderen? Dit kan jouw MODX installatie kapot maken.';
$_lang['setting_update'] = 'Instelling updaten';
$_lang['settings_after_install'] = 'Aangezien dit een nieuwe installatie is, ben je verplicht deze instellingen te controleren en daar waar je wenst aan te passen. Nadat je deze instellingen hebt gecontroleerd, klik op \'opslaan\' om de instellingen in de database weg te schrijven.<br /><br />';
$_lang['settings_desc'] = 'Hier kun je algemene instellingen en configuratie instellingen voor MODX manager interface instellen, en tevens voor jouw MODX website. Klik dubbel op de kolom met de waarde om de instelling welke je wilt aanpassen te wijzigen of klik met de rechtermuisknop op een instelling voor meer opties. Je kunt ook op het "+" teken klikken voor een omschrijving van de instelling.';
$_lang['settings_furls'] = 'Vriendelijke URLs';
$_lang['settings_misc'] = 'Diversen';
$_lang['settings_site'] = 'Site';
$_lang['settings_ui'] = 'Interface & Features';
$_lang['settings_users'] = 'Gebruiker';
$_lang['system_settings'] = 'Systeem Instellingen';
$_lang['usergroup'] = 'Gebruikersgroep';

// user settings
$_lang['setting_access_category_enabled'] = 'Controleer Categorie Toegang';
$_lang['setting_access_category_enabled_desc'] = 'Gebruik dit om aan te geven of Categorie ACLs (per context) gecontroleerd moeten worden. <strong>LET OP: als deze optie uit staat worden ALLE categorie toegangsrechten genegeerd!</strong>';

$_lang['setting_access_context_enabled'] = 'Controleer Context Toegang';
$_lang['setting_access_context_enabled_desc'] = 'Gebruik dit om aan te geven of Context ACLs gecontroleerd moeten worden. <strong>LET OP: als deze optie uit staat worden ALLE context toegangsrechten genegeerd! Zet dit NIET uit via een systeem-brede instelling of context instelling voor de mgr context, omdat je dan geen toegang meer hebt tot de manager interface.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Controleer Resource Groep Toegang';
$_lang['setting_access_resource_group_enabled_desc'] = 'Gebruik dit om aan te geven of Resource Groep ACLs (per context) gecontroleerd moeten worden. <strong>LET OP: als deze optie uit staat worden ALLE resource groep toegangsrechten genegeerd!</strong>';

$_lang['setting_allow_mgr_access'] = 'Manager Interface Toegang';
$_lang['setting_allow_mgr_access_desc'] = 'Selecteer deze optie om toegang tot de manager interface aan of uit te zetten. <strong>Let op: Als deze op nee is ingesteld dan zal de gebruiker naar de Manager Login of Site startpagina gestuurd worden</strong>';

$_lang['setting_failed_login'] = 'Mislukte inlog pogingen';
$_lang['setting_failed_login_desc'] = 'Hier kun je het aantal keren foutief inloggen definieren waarna een gebruiker geblokkeerd wordt.';

$_lang['setting_login_allowed_days'] = 'Dagen toegestaan';
$_lang['setting_login_allowed_days_desc'] = 'Selecteer het aantal dagen zolang de gebruiker mag inloggen.';

$_lang['setting_login_allowed_ip'] = 'Toegestaan IP adres';
$_lang['setting_login_allowed_ip_desc'] = 'Vul het IP adres in van waar de gebruiker mag inloggen. <strong>Let op: verdeel meerdere IP adressen middels een komma (,)</strong>';

$_lang['setting_login_homepage'] = 'Inlogpagina';
$_lang['setting_login_homepage_desc'] = 'Vul het ID van het document in waar ed gebruiker naartoe gestuurd wordt nadat hij/zij ingelogd is. <strong>Let op: controleer dat het ID dat je invult behoort bij een geldig document en bereikbaar is voor deze gebruiker!</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Toegansbeleid schema versie';
$_lang['setting_access_policies_version_desc'] = 'De versie van het toegangsbeleid systeem. NIET VERANDEREN.';

$_lang['setting_allow_forward_across_contexts'] = 'Sta forwarding over contexts toe';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Indien waar, Symlinks en modX::sendForward() API calls kunnen requests naar documenten in andere contexts forwarden.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Wachtwoord vergeten toestaan in de Manager Login';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Deze instelling op "Nee" zetten zal het Wachtwoord vergeten van de manager login verwijderen.';

$_lang['setting_allow_tags_in_post'] = 'HTML tags in POST toestaan';
$_lang['setting_allow_tags_in_post_desc'] = 'Indien onwaar, alle POST acties in de manager strippen elke html tag eruit. MODX beveelt aan om deze op waar te laten staan.';

$_lang['setting_anonymous_sessions'] = 'Anonieme sessies';
$_lang['setting_anonymous_sessions_desc'] = 'Bij uitschakelen van deze optie hebben alleen geauthenticeerde gebruikers toegang tot een PHP sessie. Dit vermindert de impact van een anonieme gebruiker op een MODX site als ze geen toegang nodig hebben tot een unieke sessie. Als session_enabled uitgeschakeld is heeft dit geen effect aangezien sessies dan niet beschikbaar zijn.';

$_lang['setting_archive_with'] = 'Forceer PCLZip Archieven';
$_lang['setting_archive_with_desc'] = 'Indien waar, dan wordt de PCLZip gebruikt in plaats van ZipArchive als zip extentie. Zet dit aan als je uitpak-errors krijgt of problemen hebt met uitpakken in de Pakket Manager.';

$_lang['setting_auto_menuindex'] = 'Standaard Menu indexing';
$_lang['setting_auto_menuindex_desc'] = 'Selecteer \'Ja\' om standaard automatische menu index doortelling in te schakelen.';

$_lang['setting_auto_check_pkg_updates'] = 'Automatisch controleren op pakket updates';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Indien \'Ja\', MODX zal automatisch controleren op updates voor pakketjes in de Pakket Manager. Hierdoor zal het laden van het overzicht langer duren.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Cache verlooptijd voor automatisch pakket update controle';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Het aantal minuten dat de Pakket Manager de update resultaten in de cache houdt.';

$_lang['setting_allow_multiple_emails'] = 'Toestaan dubbele e-mailadressen voor gebruikers';
$_lang['setting_allow_multiple_emails_desc'] = 'Indien ingeschakeld, gebruikers mogen hetzelfde e-mailadres hebben.';

$_lang['setting_automatic_alias'] = 'Automatisch aliassen genereren';
$_lang['setting_automatic_alias_desc'] = 'Selecteer \'Ja\' om het systeem automatisch een alias te laten genereren aan de hand van de document titel bij het opslaan.';

$_lang['setting_base_help_url'] = 'Basis help URL';
$_lang['setting_base_help_url_desc'] = 'De basis URL waarmee de Help links, rechtsboven van de manager pagina\'s gebouwd worden.';

$_lang['setting_blocked_minutes'] = 'Geblokkeerde minuten';
$_lang['setting_blocked_minutes_desc'] = 'Hier kun je het aantal minuten definieren dat een gebruiker geblokkeerd is na het bereiken van het maximale aantal foutieve inlogpogingen. Vul alleen cijfers in (geen komma\'s, spaties etc.)';

$_lang['setting_cache_action_map'] = 'Actie Map Cache inschakelen';
$_lang['setting_cache_action_map_desc'] = 'Indien ingeschakeld, acties (of controller maps) worden gecached om de laadtijd van de manager te beforderen.';

$_lang['setting_cache_alias_map'] = 'Context Alias Map Cache inschakelen';
$_lang['setting_cache_alias_map_desc'] = 'Indien ingeschakeld zullen alle Document URIs per context gecached worden. Bij grote (10.000+ documenten) websites kan er mogelijk verbeterde performance worden behaald door dit uit te zetten.';

$_lang['setting_cache_context_settings'] = 'Context Instellingen Cache';
$_lang['setting_cache_context_settings_desc'] = 'Indien ingeschakeld, context instellingen worden gecached om de laadtijd te bevorderen.';

$_lang['setting_cache_db'] = 'Database Cache inschakelen';
$_lang['setting_cache_db_desc'] = 'Indien ingeschakeld, objects en ruwe resultaatsets van SQL queries worden gecached om de snelheid significant te verbeteren.';

$_lang['setting_cache_db_expires'] = 'Verlooptijd voor Database Cache';
$_lang['setting_cache_db_expires_desc'] = 'Deze waarde (in seconden) stelt de verlooptijd voor de cache bestanden van de database in.';

$_lang['setting_cache_db_session'] = 'Database Sessie Cache inschakelen';
$_lang['setting_cache_db_session_desc'] = 'Indien ingeschakeld, en cache_db is ingeschakeld, database sessies worden gecached in de DB result-set cache.';

$_lang['setting_cache_db_session_lifetime'] = 'Verlooptijd voor DB Sessie Cache';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Deze waarde (in seconden) stelt de verlooptijd voor de cache bestanden van de database in.';

$_lang['setting_cache_default'] = 'Cacheable standaard';
$_lang['setting_cache_default_desc'] = 'Stel in op \'Ja\' om alle nieuwe documenten standaard cacheable te maken.';
$_lang['setting_cache_default_err'] = 'Vermeld of je standaard wilt dat documenten worden gecached.';

$_lang['setting_cache_disabled'] = 'Uitschakelen globale Cache opties';
$_lang['setting_cache_disabled_desc'] = 'Selecteer \'Ja\' om alle MODX caching functionaliteit uit te schakelen. MODX raad het niet aan dit uit te zetten.';
$_lang['setting_cache_disabled_err'] = 'Vermeld of je wilt dat cache ingeschakeld moet zijn.';

$_lang['setting_cache_expires'] = 'Verlooptijd van standaard Cache';
$_lang['setting_cache_expires_desc'] = 'Deze waarde (in seconden) stelt de verlooptijd voor de laatste cache bestanden van de standaard in.';

$_lang['setting_cache_format'] = 'Te gebruiken caching formaat';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serialize. Een van de formaten.';

$_lang['setting_cache_handler'] = 'Caching Handler Class';
$_lang['setting_cache_handler_desc'] = 'De class naam van het type handler te gebruiken voor caching.';

$_lang['setting_cache_lang_js'] = 'Cache Lexicon JS Strings';
$_lang['setting_cache_lang_js_desc'] = 'Indien op waar ingesteld, zal dit server headers gebruiken om de lexicon strings geladen in Javascript voor de manager interface te cachen.';

$_lang['setting_cache_lexicon_topics'] = 'Cache Lexicon Topics';
$_lang['setting_cache_lexicon_topics_desc'] = 'Indien ingeschakeld, alle Lexicon topics zullen gecached worden om de laadtijd van de internationalisatie functionaliteit sterk te verbeteren. MODX beveelt ten strengste aan dit op \'Ja\' te laten staan.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Cache niet-core Lexicon Topics';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Indien uitgeschakeld, niet-core Lexicon topics worden niet gecached. Dit kan handig zijn als je je eigen extra\'s ontwikkeld.';

$_lang['setting_cache_resource'] = 'Inschakelen gedeeltelijke document Cache';
$_lang['setting_cache_resource_desc'] = 'Gedeeltelijke document caching is configureerbaar per document als deze functionaliteit ingeschakeld is. Uitschakelen zal het globaal uitzetten.';

$_lang['setting_cache_resource_expires'] = 'Verlooptijd voor gedeeltelijke document Cache';
$_lang['setting_cache_resource_expires_desc'] = 'Deze waarde (in seconden) stelt de verlooptijd voor de laatste cache bestanden van de gedeeltelijke documenten in.';

$_lang['setting_cache_scripts'] = 'Inschakelen Script Cache';
$_lang['setting_cache_scripts_desc'] = 'Indien ingeschakeld, MODX zal alle scripts cachen (snippets en plugins) naar een bestand om de laadtijd te verbeteren. MODX beveelt aan deze op \'Ja\' ingesteld te laten.';

$_lang['setting_cache_system_settings'] = 'Inschakelen Systeeminstellingen Cache';
$_lang['setting_cache_system_settings_desc'] = 'Indien ingeschakeld, systeeminstellingen worden gecached om laadtijd te verbeteren. MODX beveelt aan om dit aan te laten staan.';

$_lang['setting_clear_cache_refresh_trees'] = 'Vernieuw boomstructuren bij legen site cache';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Indien ingeschakeld, dan worden de boomstructuren vernieuwd na het legen van de site cache.';

$_lang['setting_compress_css'] = 'Gebruik Gecomprimeerde CSS';
$_lang['setting_compress_css_desc'] = 'Indien ingeschakeld, MODX zal een gecomprimeerde versie van zijn css stylesheets gebruiken in de manager interface. Dit reduceert de laadtijd enorm in de manager. Schakel deze alleen uit als je aanpassingen verricht aan core elementen.';

$_lang['setting_compress_js'] = 'Gebruik Gecomprimeerde Javascript Libraries';
$_lang['setting_compress_js_desc'] = 'Indien ingeschakeld, MODX zal een gecomprimeerde versie van zijn Javascript libraries gebruiken in de manager interface. Dit reduceert de laadtijd enorm in de manager. Schakel deze alleen uit als je aanpassingen verricht aan core elementen.';

$_lang['setting_compress_js_groups'] = 'Gebruik groepen bij compressie van JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Groepeer de core MODX JavaScript middels groupsConfig. Stel in op Ja indien je suhosin of andere limiet factoren gebruikt.';

$_lang['setting_compress_js_max_files'] = 'Maximale grens voor compressie van JavaScript bestanden';
$_lang['setting_compress_js_max_files_desc'] = 'Het maximum aantal JavaScript bestanden dat MODX in 1x probeert te compressen als compress_js aan is. Stel lager in als je problemen ondervindt met Google Minify in de Manager.';

$_lang['setting_concat_js'] = 'Gebruik Samengevoegde Javascript Libraries';
$_lang['setting_concat_js_desc'] = 'Indien ingeschakeld, MODX zal een samengevoegde versie gebruiken van zijn Javascript libraries in de manager interface. Dit reduceert de laadtijd enorm in de manager. Schakel deze alleen uit als je aanpassingen verricht aan core elementen.';

$_lang['setting_confirm_navigation'] = 'Bevestig navigatie bij niet opgeslagen wijzigingen';
$_lang['setting_confirm_navigation_desc'] = 'Wanneer dit ingeschakeld is zal de gebruiker gevraagd worden om bevestiging wanneer de pagina verlaten wordt bij niet-opgeslagen wijzigingen.';

$_lang['setting_container_suffix'] = 'Container Achtervoegsel';
$_lang['setting_container_suffix_desc'] = 'Het achtervoegsel voor documenten indien het containers zijn en als er gebruikt gemaakt wordt van FURLs.';

$_lang['setting_context_tree_sort'] = 'Sorteer Contexts in de Resource Tree';
$_lang['setting_context_tree_sort_desc'] = 'Indien ingeschakeld zullen Contexts alfabetisch gesorteerd worden in de Resource tree.';
$_lang['setting_context_tree_sortby'] = 'Sorteerveld voor Contexts in Resource Tree';
$_lang['setting_context_tree_sortby_desc'] = 'Het veld om Contexts op te sorteren in de Resource Tree indien sorteren is ingeschakeld.';
$_lang['setting_context_tree_sortdir'] = 'Sorteerrichting van Contexts in de Resource Tree';
$_lang['setting_context_tree_sortdir_desc'] = 'De sorteerrichting voor contexts in the Resource Tree, indien sorteren is ingeschakeld.';

$_lang['setting_cultureKey'] = 'Taal';
$_lang['setting_cultureKey_desc'] = 'Selecteer de taal voor alle niet-manager Contexts, inclusief web.';

$_lang['setting_date_timezone'] = 'Standaard Tijdzone';
$_lang['setting_date_timezone_desc'] = 'Handelt de standaard tijdzone voor PHP date functies, indien niet leeg. Indien leeg en de PHP date.timezone ini insteling is niet gezet in jouw omgeving, UTC wordt aangehouden.';

$_lang['setting_debug'] = 'Debug';
$_lang['setting_debug_desc'] = 'Zet debugging aan en/of uit in MODX en/of bepaald de PHP error_reporting level. \'\' = gebruik huidige error_reporting, \'0\' = false (error_reporting = 0), \'1\' = true (error_reporting = -1), of elke geldige error_reporting waarde (als een nummer).';

$_lang['setting_default_content_type'] = 'Standaard Content Type';
$_lang['setting_default_content_type_desc'] = 'Selecteer de standaard Content Type voor nieuwe Resources. Je kunt nog altijd andere Content Types selecteren bij het wijzigen van een Resource. Het is enkel een voor-selectie.';

$_lang['setting_default_duplicate_publish_option'] = 'Standaard optie voor Resource duplicatie';
$_lang['setting_default_duplicate_publish_option_desc'] = 'De standaard geselecteerde optie voor dupliceren van een Resource. Kan zijn "unpublish", voor niet-gepubliceerde duplicaten, "publish" voor gepubliceerde duplicaten, of "preserve" voor de staat van de Resource die je dupliceert.';

$_lang['setting_default_media_source'] = 'Standaard Media bron';
$_lang['setting_default_media_source_desc'] = 'De standaard te laden Media bron.';

$_lang['setting_default_template'] = 'Standaard Template';
$_lang['setting_default_template_desc'] = 'Selecteer de standaard template welke je wilt gebruiken voor nieuwe documenten. Uiteraard kun je altijd nog een andere template kiezen in de document-editor, deze instelling selecteert er alvast een voor jou.';

$_lang['setting_default_per_page'] = 'Standaard Per Pagina';
$_lang['setting_default_per_page_desc'] = 'Het standaard aantal rijen per pagina dat gebruikt wordt in overzichten in de manager.';

$_lang['setting_editor_css_path'] = 'Pad naar CSS bestand';
$_lang['setting_editor_css_path_desc'] = 'Vul een pad in naar jouw CSS bestand dat je wilt gebruiken in een richtekst editor. De beste manier om het pad in te voeren is vanaf de root van jouw installatie, bijvoorbeeld: /assets/site/style.css. Indien je geen CSS wilt laden in de richtekst editor, laat dit veld dan leeg.';

$_lang['setting_editor_css_selectors'] = 'CSS Selectors voor de editor';
$_lang['setting_editor_css_selectors_desc'] = 'Een komma-gescheiden lijst van CSS selectors voor de richtext editor.';

$_lang['setting_emailsender'] = 'Registratie e-mail from adres';
$_lang['setting_emailsender_desc'] = 'Vul hier het e-mailadres in dat gebruikt wordt voor het versturen van de gebruikers zijn gebruikersnaam en wachtwoord.';
$_lang['setting_emailsender_err'] = 'Vermeld het e-mailadres voor het e-mail bericht aan gebruikers.';

$_lang['setting_emailsubject'] = 'Registratie e-mail onderwerp';
$_lang['setting_emailsubject_desc'] = 'Het onderwerp voor de standaard inschrijf e-mail wanneer een gebruiker zich aanmeldt.';
$_lang['setting_emailsubject_err'] = 'Vermeld een onderwerp voor de inschrijf e-mail.';

$_lang['setting_enable_dragdrop'] = 'Schakel drag/drop in voor document/element boomstructuren';
$_lang['setting_enable_dragdrop_desc'] = 'Als uit, voorkom dan slepen en neerzetten in de document en element boomstructuur.';

$_lang['setting_error_page'] = 'Foutpagina';
$_lang['setting_error_page_desc'] = 'Vul het ID in van het document jij wilt dat gebruikers naar doorgestuurd worden als een document verzoek niet gevonden kan worden. <strong>Let op: controleer dat dit ID behoort tot een geldig document en dat deze gepubliceerd is!</strong>';
$_lang['setting_error_page_err'] = 'Specificeer een document ID voor de foutpagina.';

$_lang['setting_ext_debug'] = 'ExtJS Debug';
$_lang['setting_ext_debug_desc'] = 'Het wel of niet laden van ext-all-debug.js om je te helpen jouw ExtJS code te debuggen.';

$_lang['setting_extension_packages'] = 'Pakket Extensies';
$_lang['setting_extension_packages_desc'] = 'Een komma-gescheiden list van te laden pakketjes in de MODX installatie. In het formaat pakketnaam:padnaarmodel';

$_lang['setting_enable_gravatar'] = 'Gravatar activeren';
$_lang['setting_enable_gravatar_desc'] = 'Indien je dit activeert, dan wordt je Gravatar gebruikt als profiel afbeelding (tenzij gebruikers een foto hebben geupload in MODX).';

$_lang['setting_failed_login_attempts'] = 'Mislukte inlog pogingen';
$_lang['setting_failed_login_attempts_desc'] = 'Het aantal foutieve inlogpogingen waarna een gebruiker \'geblokkeerd\' wordt.';

$_lang['setting_fe_editor_lang'] = 'Front-end editor taal';
$_lang['setting_fe_editor_lang_desc'] = 'Kies een taal voor de editor wanneer je een front-end editor gebruikt.';

$_lang['setting_feed_modx_news'] = 'MODX Nieuws Feed URL';
$_lang['setting_feed_modx_news_desc'] = 'Stel de URL in voor de RSS feed voor het MODX Nieuws paneel in de manager.';

$_lang['setting_feed_modx_news_enabled'] = 'MODX Nieuws Feed Ingeschakeld';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Indien \'Nee\', MODX zal het Nieuws blok verbergen op het welkomstscherm van de manager.';

$_lang['setting_feed_modx_security'] = 'MODX Beveiliginsberichten Feed URL';
$_lang['setting_feed_modx_security_desc'] = 'Stel de URL in voor de RSS feed voor hey MODX beveiligingsberichten paneel in de manager.';

$_lang['setting_feed_modx_security_enabled'] = 'MODX Beveiligings Feed Ingeschakeld';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Indien \'Nee\', MODX zal het Beveiligings blok verbergen op het welkomstscherm van de manager.';

$_lang['setting_filemanager_path'] = 'Bestandsmanager pad';
$_lang['setting_filemanager_path_desc'] = 'IIS heeft moeite met het goed instellen van de document_root instelling, welke door de bestandsmanager gebruikt wordt zodat bepaald wordt wat jij te zien krijgt. Indien je problemen ondervindt in de bestandsmanager, controleer of dit pad naar de root van jouw MODX installatie gaat.';

$_lang['setting_filemanager_path_relative'] = 'Is het Bestandsmanager pad relatief?';
$_lang['setting_filemanager_path_relative_desc'] = 'Indien filemanager_path instelling relatief is aan de MODX base_path, zet deze instelling dan op Ja. Indien jouw filemanager_path buiten de docroot valt, zet dit dan op Nee.';

$_lang['setting_filemanager_url'] = 'Bestandsmanager URL';
$_lang['setting_filemanager_url_desc'] = 'Optioneel. Stel deze in als je een expliciete URL wilt gebruiken voor de bestanden in de MODX bestandsmanager (handig als je het filemanager_path naar een pad buiten de MODX webroot hebt veranderd). Controleer wel of dit een web-bereikbare URL van de filemanager_path is. Als je deze leeg laat zal MODX proberen dit pad automatisch te berekenen.';

$_lang['setting_filemanager_url_relative'] = 'Is de bestandsmanager URL relatief?';
$_lang['setting_filemanager_url_relative_desc'] = 'Indien filemanager_url instelling relatief is aan de MODX base_url, zet deze instelling dan op Ja. Indien jouw filemanager_url buiten de webroot valt, zet dit dan op Nee.';

$_lang['setting_forgot_login_email'] = 'Inlog vergeten e-mail';
$_lang['setting_forgot_login_email_desc'] = 'Het template voor de e-mail welke gestuurd wordt zodra een gebruiker zijn MODX gebruikersnaam/wachtwoord vergeten is';

$_lang['setting_form_customization_use_all_groups'] = 'Gebruik alle gebruikersgroep lidmaatschappen voor Form Customization';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Indien Ja is ingesteld, zal FC *alle* sets op *alle* gebruikersgroepen met leden gebruiken. Anders, worden alleen de Sets gebruikt die behoren bij de primaire gebruikersgroep. Instellen op Ja kan voor problemen zorgen met conflicterende FC Sets.';

$_lang['setting_forward_merge_excludes'] = 'sendForward sluit velden bij samenvoegen uit';
$_lang['setting_forward_merge_excludes_desc'] = 'Een Symlink voegt niet-lege veld waarden met het doel document samen; deze komma gescheiden lijst van velden worden van samenvoegen uitgesloten door de Symlink.';

$_lang['setting_friendly_alias_lowercase_only'] = 'FURL Kleine letter aliassen';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Bepaal of er alleen kleine letters in een document alias gebruikt mag worden.';

$_lang['setting_friendly_alias_max_length'] = 'FURL Maximale alias lengte';
$_lang['setting_friendly_alias_max_length_desc'] = 'Indien groter dan nul, het maximale aantal tekens dat gebruikt voor een document alias. Nul is gelijk aan ongelimiteerd.';

$_lang['setting_friendly_alias_realtime'] = 'Real-time alias genereren';
$_lang['setting_friendly_alias_realtime_desc'] = 'Geeft aan of de alias van een document automatisch en in real time aangemaakt moet worden op basis van de pagina title. De automatic_alias instelling moet ook ingeschakeld zijn. Als automatic_alias ingeschakeld is, maar friendly_alias_realtime staat uit, dan zal de alias aangemaakt worden tijdens het opslaan van een document.';

$_lang['setting_friendly_alias_restrict_chars'] = 'FURL Beperking alias karakter methode';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'De methode wordt gebruikt om het aantal karakters te beperken in een document alias. "pattern" staat een RegEx patroon toe, "legal" staat elk geldig URL karakter toe, "alpha" staat alleen letters uit het alfabet toe en "alphanumeric" staat alleen letters en cijfers toe.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'FURL beperkingspatroon alias karakter';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Een geldig RegEx patroon voor het beperken van karakters in een document alias.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'FURL alias strip element tags';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Bepaal of element tags gestript moeten worden van een document alias.';

$_lang['setting_friendly_alias_translit'] = 'FURL alias transliteratie';
$_lang['setting_friendly_alias_translit_desc'] = 'De methode voor transliteratie die wordt gebruikt op een alias van een document. Leeg of "none" is de standaard waarde welke transliteratie overslaat. Andere mogelijke waarden zijn "iconv" (indien beschikbaar) of een naam van een transliteratie tabel, geleverd door een aangepaste transliteratie service class.';

$_lang['setting_friendly_alias_translit_class'] = 'FURL alias transliteration service class';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Een optionele service class welke een aangepaste transliteratie service bied voor de FURL Alias generator/filtering.';

$_lang['setting_friendly_alias_translit_class_path'] = 'FURL Alias Transliteration Service Class pad';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'De pakket model locatie waar de FURL Alias Transliteration Service Class uit geladen wordt.';

$_lang['setting_friendly_alias_trim_chars'] = 'FURL trim alias karakters';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Karakters welke aan het eind van de een document alias weggehaald worden.';

$_lang['setting_friendly_alias_word_delimiter'] = 'FURL scheidingsteken alias';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Het voorkeurs scheidingsteken voor de woorden in de alias voor vriendelijke URLs.';

$_lang['setting_friendly_alias_word_delimiters'] = 'FURL scheidingsteken alias';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'De karakters welke scheidingstekens voor vriendelijke URL aliassen gebruikt worden. Deze tekens worden omgezet en geconsolideerd om de gewenste FURL alias scheidingsteken te maken.';

$_lang['setting_friendly_urls'] = 'Gebruik vriendelijke URLs';
$_lang['setting_friendly_urls_desc'] = 'Dit staat je toe om zoekmachine vriendelijke URLs te gebruiken met MODX. Let op: dit zal alleen werken in MODX installaties draaiend op Apache en je moet een .htaccess bestand gemaakt hebben om dit te laten werken. Bekijk de .htaccess bestand welke is meegeleverd voor meer info.';
$_lang['setting_friendly_urls_err'] = 'Vul in of je wel of niet gebruik wilt maken van vriendelijke URLs.';

$_lang['setting_friendly_urls_strict'] = 'Gebruik stricte Vriendelijke URLs';
$_lang['setting_friendly_urls_strict_desc'] = 'Als vriendelijke URLs ingeschakeld zijn, forceert deze optie non-canonical requests welke met een Resource overeenkomen naar een 301 redirect naar de canonical URI voor betreffende Resource. PAS OP: Zet deze niet aan indien je custom rewrite regels gebruikt welke niet overeenkomen, op z\'n minst aan het begin van een canonical URI. Ter voorbeeld; een canonical URI als foo/ met custom rewrites voor foo/bar.html zal werken, maar zal proberen bar/foo.html te rewriten als foo/ zal een redirect forceren naar foo/ met deze optie ingeschakeld.';

$_lang['setting_global_duplicate_uri_check'] = 'Controleer op gedupliceerde URIs in alle contexts';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Selecteer \'Ja\' voor controles op gedupliceerde URIs voor alle contexts tijdens het zoeken. Anders, wordt alleen de context waar het document zich in bevindt gecontroleerd.';

$_lang['setting_hidemenu_default'] = 'Standaard verbergen in menu';
$_lang['setting_hidemenu_default_desc'] = 'Selecteer \'Ja\' om alle nieuwe documenten standaard te verbergen in het menu.';

$_lang['setting_inline_help'] = 'Toon inline Help tekst voor velden';
$_lang['setting_inline_help_desc'] = 'Indien \'Ja\', dan zullen velden hun help tekst direct onder het veld getoond worden. Indien \'Nee\', alle velden krijgen tooltip gebaseerde help.';

$_lang['setting_link_tag_scheme'] = 'URL Generator Schema';
$_lang['setting_link_tag_scheme_desc'] = 'URL generator schema voor tag . Beschikbare opties: <a href="http://api.modxcms.com/modx/modX.html#makeUrl">http://api.modxcms.com/modx/modX.html#makeUrl</a>';

$_lang['setting_locale'] = 'Locale';
$_lang['setting_locale_desc'] = 'Stel de locale in voor het systeem. Laat leeg om de standaard te gebruiken. Zie <a href="http://php.net/setlocale" target="_blank">de PHP documentatie</a> voor meer info.';

$_lang['setting_lock_ttl'] = 'Vergrendel Time-to-Live';
$_lang['setting_lock_ttl_desc'] = 'Het aantal seconden dat de Resource vergrendeld blijft zodra de gebruiker inactief is.';

$_lang['setting_log_level'] = 'Logging niveau';
$_lang['setting_log_level_desc'] = 'De standaard logging niveau. Hoe lager het niveau, hoe minder meldingen er gelogd worden. Mogelijkheden: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO) en 4 (DEBUG).';

$_lang['setting_log_target'] = 'Logging doel';
$_lang['setting_log_target_desc'] = 'De standaard loggin doel waar log meldingen geschreven worden. Mogelijkheden: \'FILE\', \'HTML\' of \'ECHO\'. Standaard is \'FILE\' indien niet opgegeven.';

$_lang['setting_mail_charset'] = 'Mail Karakterset';
$_lang['setting_mail_charset_desc'] = 'Het (standaard) karakterset voor e-mails, bijv. \'iso-8859-1\' of \'UTF-8\'';

$_lang['setting_mail_encoding'] = 'E-mail Encoding';
$_lang['setting_mail_encoding_desc'] = 'Stelt de encoding in van het bericht. Mogelijkheden voor dit zijn "8bit", "7bit", "binary", "base64" en "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Gebruik SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Indien waar, MODX zal probren SMTP te gebruiken in mail functionaliteiten.';

$_lang['setting_mail_smtp_auth'] = 'SMTP Authenticatie';
$_lang['setting_mail_smtp_auth_desc'] = 'Stelt SMTP authenticatie in. Maakt gebruik van de mail_smtp_user en mail_smtp_pass instellingen.';

$_lang['setting_mail_smtp_helo'] = 'SMTP Helo Bericht';
$_lang['setting_mail_smtp_helo_desc'] = 'Stelt de SMTP HELO in van het bericht (Standaard de hostnaam).';

$_lang['setting_mail_smtp_hosts'] = 'SMTP Hosts';
$_lang['setting_mail_smtp_hosts_desc'] = 'Stelt de SMTP host in. Alle hosts moeten worden gescheiden door een puntcomma. Je kunt ook een andere poort gebruiken voor elke host, in dit formaat: [hostnaam:poort] (bijv. "smtp1.example.com:25;smtp2.example.com"). Hosts worden op volgorde gebruikt.';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP Keep-Alive';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Voorkomt dat de SMTP-verbinding wordt gesloten na elke gestuurde e-mail. Niet aanbevolen.';

$_lang['setting_mail_smtp_pass'] = 'SMTP wachtwoord';
$_lang['setting_mail_smtp_pass_desc'] = 'Het wachtwoord voor de authenticatie voor de SMTP.';

$_lang['setting_mail_smtp_port'] = 'SMTP poort';
$_lang['setting_mail_smtp_port_desc'] = 'Stel de standaard SMTP poort in.';

$_lang['setting_mail_smtp_prefix'] = 'SMTP verbinding voorvoegsel';
$_lang['setting_mail_smtp_prefix_desc'] = 'Stel het voorvoegsel van de verbinding in. Mogelijk zijn "", "ssl" of "tls"';

$_lang['setting_mail_smtp_single_to'] = 'SMTP Single To';
$_lang['setting_mail_smtp_single_to_desc'] = 'Biedt de mogelijkheid om e-mail berichten afzonderlijk te sturen in plaats van het versturen naar alle \'naar\' adressen.';

$_lang['setting_mail_smtp_timeout'] = 'SMTP Timeout';
$_lang['setting_mail_smtp_timeout_desc'] = 'Stel de SMTP server timeout in, in seconden. Deze functionaliteit zal niet werken op win32 servers.';

$_lang['setting_mail_smtp_user'] = 'SMTP Gebruiker';
$_lang['setting_mail_smtp_user_desc'] = 'De gebruikersnaam voor de authenticatie voor de SMTP.';

$_lang['setting_main_nav_parent'] = 'Bovenliggend hoofdmenu';
$_lang['setting_main_nav_parent_desc'] = 'De container gebruikt om de records op te halen voor het belangrijkste menu.';

$_lang['setting_manager_direction'] = 'Manager tekstrichting';
$_lang['setting_manager_direction_desc'] = 'Kies de richting waarin de tekst getoond moet worden in de manager, van links naar rechts of van rechts naar links.';

$_lang['setting_manager_date_format'] = 'Manager Datumformaat';
$_lang['setting_manager_date_format_desc'] = 'Het formaat, in PHP date() formaat, voor de datums vertegenwoordigd in de manager.';

$_lang['setting_manager_favicon_url'] = 'Manager Favicon URL';
$_lang['setting_manager_favicon_url_desc'] = 'Indien gezet, dan zal deze URL als favicon voor de manager geladen worden. Moet een relatieve URL zijn ten opzichte van de manager/ map of een absolute URL.';

$_lang['setting_manager_js_cache_file_locking'] = 'Schakel Bestandsvergrendeling voor Manager JS/CSS Cache in';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Cache bestandsvergrendeling. Stel in op Nee als bestandssysteem NFS is.';
$_lang['setting_manager_js_cache_max_age'] = 'Manager JS/CSS Compressie Cache Leeftijd';
$_lang['setting_manager_js_cache_max_age_desc'] = 'De maximale leeftijd van browser cache van de manager CSS/JS compressie in seconden. Na deze periode, zal de browser een andere conditionele GET sturen. Gebruik lagere periode bij minder verkeerd.';
$_lang['setting_manager_js_document_root'] = 'Manager JS/CSS Compressie Document Root';
$_lang['setting_manager_js_document_root_desc'] = 'Als jouw server de DOCUMENT_ROOT niet beschikbaar heeft, stel deze hier expliciet in voor de manager CSS/JS compressie. Verander dit niet behalve als je weet wat je doet.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Schakel zlib Output Compression in voor Manager JS/CSS';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Het wel of niet inschakelen van zlib output compressie voor gecompresseerde CSS/JS in de manager. Schakel niet in behalve als je zeker weet dat de PHP config variabele zlib.output_compression 1 kan zijn. MODX beveelt aan deze uit te laten.';

$_lang['setting_manager_lang_attribute'] = 'Manager HTML en XML taal attribuut';
$_lang['setting_manager_lang_attribute_desc'] = 'Vul een taalcode in dat het beste past bij de gekozen manager taal, dit zal ervoor zorgen dat de browser de content in een zo goed mogelijk formaat kan tonen.';

$_lang['setting_manager_language'] = 'Manager taal';
$_lang['setting_manager_language_desc'] = 'Selecteer de taal voor de MODX content manager.';

$_lang['setting_manager_login_url_alternate'] = 'Alternatieve Manager Login URL';
$_lang['setting_manager_login_url_alternate_desc'] = 'Een alternatieve URL om niet geauthoriseerde gebruikers heen te sturen als ze moeten inloggen. Het login formulier moet de gebruiker inloggen voor de "mgr" context, om dit te laten werken.';

$_lang['setting_manager_login_start'] = 'Manager inlog start';
$_lang['setting_manager_login_start_desc'] = 'Vul het ID in van het document waar de gebruiker naartoe gestuurd moet worden zodra hij/zij inlogt in de manager. <strong>Let op: controleer of het ID behoort tot een bestaand document en dat het gepubliceerd is en bereikbaar voor deze gebruiker!</strong>';

$_lang['setting_manager_theme'] = 'Manager Thema';
$_lang['setting_manager_theme_desc'] = 'Selecteer een thema voor de content manager.';

$_lang['setting_manager_time_format'] = 'Manager Tijdsformaat';
$_lang['setting_manager_time_format_desc'] = 'Het formaat, in PHP date(), voor de tijd instellingen getoond in de manager.';

$_lang['setting_manager_use_tabs'] = 'Gebruik tabs in de manager layout';
$_lang['setting_manager_use_tabs_desc'] = 'Indien waar, de manager zal tabs gebruiken voor het renderen van de content panelen. Anders gebruikt het portalen.';

$_lang['setting_manager_week_start'] = 'Start van de Week';
$_lang['setting_manager_week_start_desc'] = 'Definieer de startdag van de week. Gebruik 0 (of laat leeg) voor Zondag, 1 voor maandag enzovoorts...';

$_lang['setting_mgr_tree_icon_context'] = 'Context boom icoon';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Definieer hier een CSS class welke gebruikt wordt om een Context icoon in de Context boom te tonen. Je kan deze instelling op elke Context toepassen om zo voor elke Context een ander icoon te gebruiken.';

$_lang['setting_mgr_source_icon'] = 'Media bron icoon';
$_lang['setting_mgr_source_icon_desc'] = 'Geef middels deze CSS class aan welk icoon gebruikt wordt voor open folder iconen in de Media bronnen boom. Standaard "icon-folder-open-o"';

$_lang['setting_modRequest.class'] = 'Request Handler Class';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Media verkenner boom verborgen bestanden';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'Indien ingeschakeld zullen we geen bestanden in folders getoond worden in de Media verkenner. Standaard uitgeschakeld.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Media verkenner boom verberg tooltips';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'Indien ingeschakeld zullen er geen afbeelding ter voorvertoning weergegven worden wanneer er over een bestand in de Media verkenner bewogen wordt. Standaard ingeschakeld.';

$_lang['setting_modx_browser_default_sort'] = 'Bestand Browser Standaard Sortering';
$_lang['setting_modx_browser_default_sort_desc'] = 'De standaard sortering voor pop bestand browser in de manager. Mogelijkheden zijn: name, size, lastmod (last modified).';

$_lang['setting_modx_browser_default_viewmode'] = 'Bestandsbrowser Standaard Weergave Modus';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'De standaard weergavemodus bij het gebruik van de bestandsbrowser in de manager. Beschikbare waardes zijn: grid, list.';

$_lang['setting_modx_charset'] = 'Karakter encoding';
$_lang['setting_modx_charset_desc'] = 'Selecteer welek karakter encoding je wilt gebruiken. Onthoud dat MODX is getest met een aantal van deze encodings, maar niet met alle. Voor de meeste talen is de standaard instelling UTF-8 de voorkeur.';

$_lang['setting_new_file_permissions'] = 'Nieuwe bestandsrechten';
$_lang['setting_new_file_permissions_desc'] = 'Wanneer een nieuw bestand geupload wordt in de bestandsmanager, zal de bestandsmanager proberen de rechten van het bestand te veranderen naar deze instelling. Dit werkt niet op alle omgevingen, zoals IIS. In dat geval moet je zelf handmatig de rechten aanpassen.';

$_lang['setting_new_folder_permissions'] = 'Nieuwe maprechten';
$_lang['setting_new_folder_permissions_desc'] = 'Wanneer een nieuwe map toegeoegd wordt in de bestandsmanager, zal de bestandsmanager proberen de rechten van de map te veranderen naar deze instelling. Dit werkt niet op alle omgevingen, zoals IIS. In dat geval moet je zelf handmatig de rechten aanpassen.';

$_lang['setting_parser_recurse_uncacheable'] = 'Vertraagd Uncacheable Tags Parsen';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'Mocht je problemen ondervinden met complexe geneste tags, dan kan deze instelling uitgeschakeld worden. In dat geval kan het resultaat van een uncacheable element gecached worden binnen de context van een cacheable element. ';

$_lang['setting_password_generated_length'] = 'Lengte auto-gegenereerde wachtwoorden';
$_lang['setting_password_generated_length_desc'] = 'De lengte van de auto-gegenereerde wachtwoorden voor een gebruiker.';

$_lang['setting_password_min_length'] = 'Minimale wachtwoord lengte';
$_lang['setting_password_min_length_desc'] = 'De minimale lengte van een wachtwoord voor een gebruiker.';

$_lang['setting_preserve_menuindex'] = 'Bewaar de menu-index bij het dupliceren van resources';
$_lang['setting_preserve_menuindex_desc'] = 'Bij het dupliceren van resources zal de menu-index bewaard blijven.';

$_lang['setting_principal_targets'] = 'ACL doelen te laden';
$_lang['setting_principal_targets_desc'] = 'Pas de ACL doelen voor MODX users aan.';

$_lang['setting_proxy_auth_type'] = 'Proxy Authenticatie Type';
$_lang['setting_proxy_auth_type_desc'] = 'Ondersteund BASIC of NTLM.';

$_lang['setting_proxy_host'] = 'Proxy Host';
$_lang['setting_proxy_host_desc'] = 'Als jouw server gebruik maakt van een proxy, stel de hostnaam hier in om bepaalde functionaliteiten van MODX te activeren welke een proxy gebruiken, zoals de Pakket Manager.';

$_lang['setting_proxy_password'] = 'Proxy wachtwoord';
$_lang['setting_proxy_password_desc'] = 'Het wachtwoord nodig voor authenticatie van de proxy server.';

$_lang['setting_proxy_port'] = 'Proxy poort';
$_lang['setting_proxy_port_desc'] = 'De poort voor jouw proxy server.';

$_lang['setting_proxy_username'] = 'Proxy gebruikersnaam';
$_lang['setting_proxy_username_desc'] = 'De gebruikersnaam nodig voor authenticatie van de proxy server.';

$_lang['setting_photo_profile_source'] = 'Gebruiker foto media bron';
$_lang['setting_photo_profile_source_desc'] = 'De mediabron waarin profielfoto\'s van gebruikers worden opgeslagen. Indien niet ingesteld zal de standaard media bron worden gebruikt.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Sta src buiten document root toe';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Geeft aan of het src pad buiten de document root is toegestaan. Dit is handig voor multi-context implementaties met meerdere virtuele hosts.';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb maximale cache leeftijd';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Verwijder gecachte thumbnails welke niet opgeroepen zijn meer dan X dagen.';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb maximale cache grootte';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Verwijder minst onlangs benaderde thumbnails wanneer de cache groter is dan X megabytes.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb maximale cache bestanden';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Verwijder minst onlangs benaderde thumbnails wanneer de cache meer dan X bestanden heeft.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb cache bronbestanden';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Wel of niet cachen van bronbestanden zodra ze geladen zijn. Uit is aanbevolen.';

$_lang['setting_phpthumb_document_root'] = 'PHPThumb Document Root';
$_lang['setting_phpthumb_document_root_desc'] = 'Stel dit in als je problemen ondervindt met de server variable DOCUMENT_ROOT, of wanneer je fouten krijgt met OutputThumbnail of !is_resource. Zet hier het absolute document root in welke jij wilt gebruiken. Indien deze leeg is, zal MODX de DOCUMENT_ROOT server variable gebruiken.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb fout achtergrond kleur';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Een Hex waarde, zonder #, voor de achtergrond kleur van de phpThumb fout uitvoer.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb fout font size';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Een em waarde voor de font grootte, zichtbaar in de phpThumb fout uitvoer.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb fout font color';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Een Hex waarde, zonder #, voor de tekst kleur van de phpThumb fout uitvoer.';

$_lang['setting_phpthumb_far'] = 'phpThumb behoud verhoudingen forceren';
$_lang['setting_phpthumb_far_desc'] = 'De standaard instelling voor zover phpThumb bij gebruik in MODX. Standaard C tot verhouding in de richting van het midden.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb ImageMagick pad';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Optioneel. Stel een alternatief ImageMagick pad in voor het genereren van de thumbnails met phpThumb, als het niet in de PHP standaard opgenomen is.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Hotlinking Uitgeschakeld';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Externe servers zijn toegestaan in de src parameter tenzij je hotlinking in phpThumb uitschakeld.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Hotlinking afbeelding wissen';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Geeft aan of een afbeelding van een externe server gewist zou moeten worden wanneer niet toegestaan.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Hotlinking niet toegestaan bericht';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Een bericht in plaats van een thumbnail dat zichtbaar wordt wanneer een hotlinking poging is afgewezen.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Hotlinking geldige domeinen';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Een komma-gescheiden lijst van host namen welke geldig zijn voor src URLs.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Offsite Linking uitgeschakeld';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Schakeld de mogelijkheid voor anderen om phpThumb te gebruiken voor hun eigen sites uit.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Offsite Linking afbeelding wissen';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Geeft aan of een afbeelding gelinkt van een externe server gewist zou moeten worden wanneer niet toegestaan.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Offsite Linking Referrer verplicht';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Indien ingeschakeld, elke offsite linking poging zonder geldige referrer header wordt afgewezen.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Offsite Linking niet toegestaan bericht';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Een bericht in plaats van een thumbnail dat zichtbaar wordt wanneer een offsite linking poging is afgewezen.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Offsite Linking geldige domeinen';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Een komma-gescheiden lijst van host namen welke geldige referrers zijn voor offsite linking.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Offsite Linking watermerk bron';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Optioneel. Een geldig bestandssysteem pad naar een bestand welke gebruikt wordt voor het watermerk, wanneer jouw afbeeldingen offsite gerenderd worden door phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'PHPThumb Zoom Crop';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'De standaard zoom-crop instelling voor phpThumb bij gebruik in MODX. Standaard op 0 om zoom-crop te voorkomen.';

$_lang['setting_publish_default'] = 'Standaard gepubliceerd';
$_lang['setting_publish_default_desc'] = 'Selecteer \'Ja\' om alle nieuwe documenten standaard gepubliceerd te maken.';
$_lang['setting_publish_default_err'] = 'Vul in om wel of niet documenten standaard gepubliceerd te maken.';

$_lang['setting_rb_base_dir'] = 'Document pad';
$_lang['setting_rb_base_dir_desc'] = 'Vul een fysiek pad in naar een document map. Deze instelling wordt normaal gesproken automatisch gegenereerd. Echter als je IIS gebruikt zal MODX niet in staat zijn het pad goed te genereren, dit veroorzaakt problemen met de document browser. In dat geval, kun je een pad naar de afbeeldingen map hier instellen (zoals je die ziet in Windows Explorer). <strong>Let op:</strong> De documentmap moet een submap images, files, flash en media bevatten om de browser goed te laten werken.';
$_lang['setting_rb_base_dir_err'] = 'Vul een document browser basis map in.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Dit document bestaat niet of kan niet bereikt worden. Vul een geldige map in of pas de rechten van die map aan.';

$_lang['setting_rb_base_url'] = 'Document URL';
$_lang['setting_rb_base_url_desc'] = 'Vul een virtueel pad in naar de documentmap. Deze instelling wordt normaal gesproken automatisch gegenereerd. Echter als je IIS gebruikt zal MODX niet in staat zijn het pad goed te genereren, dit veroorzaakt problemen met de document browser. In dat geval, kun je een URL naar de afbeeldingen map hier instellen (zoals je die ziet in Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Vul een document browser basis URL in.';

$_lang['setting_request_controller'] = 'Request controller bestandsnaam';
$_lang['setting_request_controller_desc'] = 'De bestandsnaam van de hoofd request controller vanwaar MODX geladen is. De meeste gebruikers kunnen dit laten staan op index.php.';

$_lang['setting_request_method_strict'] = 'Stricte Aanvraag Methode';
$_lang['setting_request_method_strict_desc'] = 'Indien ingeschakeld, aanvragen via de Aanvraag ID Parameter worden genegeeerd indien FURLs ingeschakeld zijn en de Aanvraag Alias Parameter wordt genegeeerd indien FURLs uitgeschakeld zijn.';

$_lang['setting_request_param_alias'] = 'Request alias parameter';
$_lang['setting_request_param_alias_desc'] = 'De naam van de GET parameter om document aliassen te identificeren bij doorsturen met FURLs.';

$_lang['setting_request_param_id'] = 'Request ID Parameter';
$_lang['setting_request_param_id_desc'] = 'De naam van de GET parameter om document aliassen te identificeren zonder gebruik van FURLs.';

$_lang['setting_resolve_hostnames'] = 'Achterhalen hostnamen';
$_lang['setting_resolve_hostnames_desc'] = 'Wil je dat MODX probeert de hostnamen van jouw bezoekers te achterhalen wanneer deze jouw site bezoeken? Hostnamen achterhalen kan wellicht extra laadtijd veroorzaken, hoewel jouw bezoekers op geen enkele wijze bericht hiervan krijgen.';

$_lang['setting_resource_tree_node_name'] = 'Document structuur node veld';
$_lang['setting_resource_tree_node_name_desc'] = 'Specificeer het te gebruiken document veld bij het renderen van de nodes in de document structuur. Standaard pagetitle, maar elk veld kan gebruikt worden, zoals menutitle, alias, longtitle, etc.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Resource Tree item fallback veld';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Geef het resource veld op welke gebruikt moet worden als fallback voor het tonen van items in de Resource tree. Deze wordt gebruikt als de resource een lege waarde heeft voor het geconfigureerd resource tree item veld.';

$_lang['setting_resource_tree_node_tooltip'] = 'Document structuur Tooltip veld';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Specificeer het te gebruiken document veld bij het renderen van de nodes in de document structuur. Elk document veld kan gebruikt worden, zoals menutitle, alias, longtitle etc. Indien leeg, dan wordt longtitle met de description eronder gebruikt.';

$_lang['setting_richtext_default'] = 'Richtext standaard';
$_lang['setting_richtext_default_desc'] = 'Selecteer \'Ja\' om alle nieuwe documenten gebruik te laten maken van de richtekst editor.';

$_lang['setting_search_default'] = 'Standaard doorzoekbaar';
$_lang['setting_search_default_desc'] = 'Selecteer \'Ja\' om alle nieuwe documenten standaard doorzoekbaar in te stellen.';
$_lang['setting_search_default_err'] = 'Geef aan of documenten standaard doorzoekbaar moeten zijn of niet.';

$_lang['setting_server_offset_time'] = 'Server offset tijd';
$_lang['setting_server_offset_time_desc'] = 'Selecteer het aantal uren in tijdsverschil in waar jij bent en waar de server is.';

$_lang['setting_server_protocol'] = 'Server Type';
$_lang['setting_server_protocol_desc'] = 'Als jouw site op een http verbinding draait, specificeer het hier.';
$_lang['setting_server_protocol_err'] = 'Geef aan of jouw site wel of niet een beveiligde site is';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Sessie cookie domein';
$_lang['setting_session_cookie_domain_desc'] = 'Gebruik deze instelling om de sessie cookie domein aan te passen.';

$_lang['setting_session_cookie_lifetime'] = 'Sessie cookie verlooptijd';
$_lang['setting_session_cookie_lifetime_desc'] = 'Gebruik deze instelling om de sessie cookie verlooptijd in seconden aan te passen. Dit wordt gebruikt om de verlooptijd van de sessie cookie wanneer er gekozen wordt voor \'onthoud mij\' optie bij het inloggen.';

$_lang['setting_session_cookie_path'] = 'Sessie cookie pad';
$_lang['setting_session_cookie_path_desc'] = 'Gebruik deze instelling om het cookie pad aan te passen voor het identificeren van specifieke site sessie cookies';

$_lang['setting_session_cookie_secure'] = 'Sessie cookie beveiligd';
$_lang['setting_session_cookie_secure_desc'] = 'Activeer deze instelling om beveiligde cookies te gebruiken voor SSL verbindingen.';

$_lang['setting_session_cookie_httponly'] = 'Sessie Cookie HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Gebruik deze instelling om de HttpOnly flag op sessie cookies in te stellen.';

$_lang['setting_session_gc_maxlifetime'] = 'Sessie Garbage Collector Max Lifetime';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Staat aanpassingen toe van de session.gc_maxlifetime PHP ini instelling wanneer \'modSessionHandler\' wordt gebruikt.';

$_lang['setting_session_handler_class'] = 'Sessie handler classnaam';
$_lang['setting_session_handler_class_desc'] = 'Voor database gestuurde sessies, gebruik \'modSessionHandler\'. Laat deze leeg voor de standaard PHP sessie management.';

$_lang['setting_session_name'] = 'Sessienaam';
$_lang['setting_session_name_desc'] = 'Gebruik deze instelling om de sessienaam voor gebruik in sessies in MODX aan te passen.';

$_lang['setting_settings_version'] = 'Instellingen Versie';
$_lang['setting_settings_version_desc'] = 'De huidig ge�nstalleerde versie van MODX.';

$_lang['setting_settings_distro'] = 'Distributie instellingen';
$_lang['setting_settings_distro_desc'] = 'De huidige geinstalleerde distributie van MODX.';

$_lang['setting_set_header'] = 'Stel HTTP Headers in';
$_lang['setting_set_header_desc'] = 'Indien geactiveerd, MODX zal proberen de HTTP headers in te stellen voor documenten.';

$_lang['setting_send_poweredby_header'] = 'Verstuur X-Powered-By Header';
$_lang['setting_send_poweredby_header_desc'] = 'Wanneer deze instelling is ingeschakeld zal MODX een "X-Powered-By" header meesturen om de site te identificeren als gebouwd met MODX. Dit helpt om het gebruik van MODX bij te houden door middel van derde partijen die sites analyseren. Omdat dit het makkelijker maakt om te identificeren waarmee de site is gebouwd, levert dit een mogelijk iets verhoogd beveiligingsrisico op mocht er een ernstige kwetsbaarheid gevonden worden in MODX.';

$_lang['setting_show_tv_categories_header'] = 'Toon "Categorieën" Tabs Header met TVs';
$_lang['setting_show_tv_categories_header_desc'] = 'Indien "Ja", MODX toont de "Categorieën" header boven de eerste categorie tab bij het bewerken van Resource TVs.';

$_lang['setting_signupemail_message'] = 'Inschrijf e-mail';
$_lang['setting_signupemail_message_desc'] = 'Hier kun je het e-mailbericht opstellen welke gestuurd wordt naar gebruikers wanneer je een account voor ze aanmaakt en MODX een e-mail laat sturen met de te gebruiken gebruikersnaam en wachtwoord.<br /><strong>Let op:</strong> de volgende placeholders worden vervangen door de Content Manager wanneer het bericht verstuurd wordt:<br /><br />[[+sname]] - Naam van jouw website<br />[[+saddr]] - Het standaard e-mailadres<br />[[+surl]] - Website URL<br />[[+uid]] - Gebruikers loginnaam of id<br />[[+pwd]] - Gebruikerswachtwoord<br />[[+ufn]] - Gebruikers volledige naam<br /><br />Laat de [[+uid]] en [[+pwd]] in het e-mailbericht, want anders wordt er geen gebruikersnaam en wachtwoord gestuurd en weet de gebruiker niet hoe hij/zij moet inloggen!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hallo [[+uid]]\n\nHier zijn jouw logingegevens voor [[+sname]] Content Manager:\n\nGebruikersnaam: [[+uid]]\nWachtwoord: [[+pwd]]\n\nAls je inlogt in de Content Manager ([[+surl]]), kun je je wachtwoord wijzigen.\n\nMet vriendelijke groet,\nSite beheerder';

$_lang['setting_site_name'] = 'Site naam';
$_lang['setting_site_name_desc'] = 'Vul de naam van jouw site in!';
$_lang['setting_site_name_err']  = 'Vul een site naam in';

$_lang['setting_site_start'] = 'Site Start';
$_lang['setting_site_start_desc'] = 'Vul het ID van het document in dat jij als startpagina wilt hebben. <strong>Let op:</strong> controleer of dit ID behoort tot een bestaand document en dat dit document gepubliceerd is!</strong>';
$_lang['setting_site_start_err'] = 'Vul een ID in welke de startpagina zal zijn.';

$_lang['setting_site_status'] = 'Sitestatus';
$_lang['setting_site_status_desc'] = 'Selecteer \'Ja\' om de site beschikbaar te maken voor het web. Indien je \'Nee\' selecteert, jouw bezoekers zien dan een \'Site niet beschikbaar bericht\' en zijn niet in staat door de site te navigeren.';
$_lang['setting_site_status_err'] = 'Selecteer of jouw website wel (Ja) of niet (Nee) online is.';

$_lang['setting_site_unavailable_message'] = 'Site niet beschikbaar bericht';
$_lang['setting_site_unavailable_message_desc'] = 'Bericht te tonen wanneer de site offline is of wanneer er een fout optreed. <strong>Let op: Dit bericht wordt alleen getoont als de Site niet beschikbaar pagina optie niet ingesteld is.</strong>';

$_lang['setting_site_unavailable_page'] = 'Site niet beschikbaar pagina';
$_lang['setting_site_unavailable_page_desc'] = 'Vul een ID van een document dat je wilt gebruiker als offline pagina. <strong>Let op: controleer dat dit ID een geldig document ID is en dat het document gepubliceerd is!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Vul een document ID in voor de niet beschikbaar pagina.';

$_lang['setting_strip_image_paths'] = 'Herschrijf browser paden?';
$_lang['setting_strip_image_paths_desc'] = 'Als dit op \'Nee\' gezet is, MODX zal bestandsmanager geschreven src\'s (afbeeldingen, bestanden, flash, etc.) als absolute URLs. Relatieve URLs zijn nuttig indien je wenst de MODX installatie te verplaatsen, bijv. van staging naar een productie omgeving. Indien je niet weet wat dit inhoudt laat dit dan staan op \'Ja\'.';

$_lang['setting_symlink_merge_fields'] = 'Voeg document velden samen in Symlinks';
$_lang['setting_symlink_merge_fields_desc'] = 'Indien op Ja ingesteld, worden alle niet-lege velden autmatisch samengevoegd met het doel document wanneer Symlinks gebruikt worden.';

$_lang['setting_syncsite_default'] = 'Automatisch Cache Legen ';
$_lang['setting_syncsite_default_desc'] = 'Schakel deze instelling in om de cache standaard te legen wanneer een document wordt opgeslagen.';
$_lang['setting_syncsite_default_err'] = 'Geef aan of je wel of niet wilt dat de cache standaard wordt geleegd bij het opslaan van een document.';

$_lang['setting_topmenu_show_descriptions'] = 'Toon omschrijvingen in het topmenu';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Als op \'No\' ingesteld is, MODX zal de omschrijvingen verbergen in het menu bovenin de manager.';

$_lang['setting_tree_default_sort'] = 'Document structuur standaard sorteerveld';
$_lang['setting_tree_default_sort_desc'] = 'Het standaard sorteerveld voor de document structuur wanneer de manager geladen wordt.';

$_lang['setting_tree_root_id'] = 'Structuur Root ID';
$_lang['setting_tree_root_id_desc'] = 'Stel dit in naar een geldig ID van een document om boomstructuur aan de linkerkant te starten als root. De gebruiker zal alleen de documenten zien dat subs zijn van dit document.';

$_lang['setting_tvs_below_content'] = 'Verplaats TVs onder Content';
$_lang['setting_tvs_below_content_desc'] = 'Stel in op Ja om Template Variabelen onder de Content te verplaatsen bij het bewerken van een Resourcen.';

$_lang['setting_ui_debug_mode'] = 'UI Debug Modus';
$_lang['setting_ui_debug_mode_desc'] = 'Stel in op Ja om debug meldingen te tonen wanneer u de standaard manager theme gebruikt. Je moet een browser gebruiken die console.log ondersteunt.';

$_lang['setting_udperms_allowroot'] = 'Root toestaan';
$_lang['setting_udperms_allowroot_desc'] = 'Wil je gebruikers toestaan om nieuwe documenten te maken in de root van jouw site?';

$_lang['setting_unauthorized_page'] = 'Onbevoegde pagina';
$_lang['setting_unauthorized_page_desc'] = 'Vul het ID in van het document waar je gebruikers naar toe wilt sturen als ze een beveiligde of onbevoegde pagina opvragen. <strong>Let op: controleer of dit document ID bestaat en gepubliceerd is en toegankelijk is voor deze gebruiker!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Vul een document ID in voor de onbevoegde pagina.';

$_lang['setting_upload_files'] = 'Uploadbare bestandstypen';
$_lang['setting_upload_files_desc'] = 'Hier kun je een lijst van bestandstypen invullen welke geupload kunnen worden in \'assets/files/\' bij gebruik van de document manager. Vul de extenties van de bestandstypen in gescheiden door een komma.';

$_lang['setting_upload_flash'] = 'Uploadbare Flashtypen';
$_lang['setting_upload_flash_desc'] = 'Hier kun je een lijst van bestandstypen invullen welke geupload kunnen worden in \'assets/flash/\' bij gebruik van de document manager. Vul de extenties van de bestandstypen in gescheiden door een komma.';

$_lang['setting_upload_images'] = 'Uploadbare afbeeldingstypen';
$_lang['setting_upload_images_desc'] = 'Hier kun je een lijst van bestandstypen invullen welke geupload kunnen worden in \'assets/images/\' bij gebruik van de document manager. Vul de extenties van de bestandstypen in gescheiden door een komma.';

$_lang['setting_upload_maxsize'] = 'Maximale upload grootte';
$_lang['setting_upload_maxsize_desc'] = 'Vul een maximale bestandsgrootte in dat geupload kan worden via de bestandsmanager. Upload grootte moet ingevuld worden in bytes. <strong>Let op: grote bestanden kunnen er lang over doen voordat ze geupload zijn!</strong>';

$_lang['setting_upload_media'] = 'Uploadbare mediatypen';
$_lang['setting_upload_media_desc'] = 'Hier kun je een lijst van bestandstypen invullen welke geupload kunnen worden in \'assets/media/\' bij gebruik van de document manager. Vul de extenties van de bestandstypen in gescheiden door een komma.';

$_lang['setting_use_alias_path'] = 'Gebruik vriendelijke aliaspaden';
$_lang['setting_use_alias_path_desc'] = 'Stel deze optie in op \'Ja\' zal een volledig pad tonen naar het document als het een alias heeft. Ter voorbeeld, als een document met een alias genaamd \'child\' in een container document zit met een alias genaamd \'parent\', dan is het volledige pad naar het document dat getoond word zoiets als \'parent/child.html\'<br /><strong>Let op: wanneer je deze instelling instelt op \'Ja\' (aanzetten aliaspaden), refereer items (zoals afbeeldingen, css, javascripts, etc.) middels gebruik van absolute paden: bijv., \'/assets/images\' in tegenstelling tot \'assets/images\'. Als je dit doet dan voorkom je dat de browser (of webserver) dat ze het pad toevoegen aan het alias pad.</strong>';

$_lang['setting_use_browser'] = 'Activeren document manager';
$_lang['setting_use_browser_desc'] = 'Selecteer \'Ja\' om de document manager te gebruiken. Dit zal jouw gebruikers toestaan om te bladeren en te uploaden van documenten zoals afbeeldingen, flash en media bestanden op de server.';
$_lang['setting_use_browser_err'] = 'Vul in of je wel of niet gebruik wilt maken van de document manager.';

$_lang['setting_use_editor'] = 'Activeer rich tekst editor';
$_lang['setting_use_editor_desc'] = 'Wil je gebruik van de rich tekst editor inschakelen? Als je je pretiger voelt met schrijven van HTML, dan kun je de editor uitzetten middels deze instelling. Let op dat deze instelling wordt toegepast op alle documenten en gebruikers!';
$_lang['setting_use_editor_err'] = 'Geef aan of je de RTE editor wel of niet wilt gebruiken.';

$_lang['setting_use_frozen_parent_uris'] = 'Gebruik Frozen Parent URIs';
$_lang['setting_use_frozen_parent_uris_desc'] = 'Indien ingeschakeld zal de URI voor onderliggende documenten relatief zijn van de bevroren URI van de parent, waarbij aliases hoger in de resource tree worden genegeerd. ';

$_lang['setting_use_multibyte'] = 'Gebruik multibyte extensie';
$_lang['setting_use_multibyte_desc'] = 'Stel in op waar als je gebruik wilt maken van mbstring extentie voor multibyte karakters in jouw MODX installatie. Alleen op waar instellen als de mbstring PHP extentie ge�nstalleerd is.';

$_lang['setting_use_weblink_target'] = 'Gebruik WebLink doel';
$_lang['setting_use_weblink_target_desc'] = 'Stel in als Ja als je wilt dat MODX link tags en makeUrl() links genereren moet voor Weblinks. Anders wordt de interne MODX URL gegenereerd door link tags en de makeUrl() methode.';

$_lang['setting_user_nav_parent'] = 'Gebruikersmenu parent';
$_lang['setting_user_nav_parent_desc'] = 'De menu container welke gebruikt wordt om het gebruikersmenu te tonen. ';

$_lang['setting_webpwdreminder_message'] = 'Web herinneringsbericht';
$_lang['setting_webpwdreminder_message_desc'] = 'Hier kun je het e-mailbericht opstellen welke gestuurd wordt naar gebruikers wanneer ze een nieuw wachtwoord aanvragen. De Content Managr zal een e-mail sturen met het nieuwe wachtwoord en activatie informatie.<br /><strong>Let op:</strong> de volgende placeholders worden vervangen door de Content Manager wanneer het bericht verstuurd wordt:<br /><br />[[+sname]] - Naam van jouw website<br />[[+saddr]] - Het standaard e-mailadres<br />[[+surl]] - Website URL<br />[[+uid]] - Gebruikers loginnaam of id<br />[[+pwd]] - Gebruikerswachtwoord<br />[[+ufn]] - Gebruikers volledige naam<br /><br />Laat de [[+uid]] en [[+pwd]] in het e-mailbericht, want anders wordt er geen gebruikersnaam en wachtwoord gestuurd en weet de gebruiker niet hoe hij/zij moet inloggen!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Hallo [[+uid]]\n\nOm je nieuwe wachtwoord te activeren, klik op de volgende link:\n\n[[+surl]]\n\nAls dit succesvol is kun je het volgende wachtwoord gebruiken om in te loggen:\n\nWachtwoord: [[+pwd]]\n\nAls je niet om een nieuw wachtwoord gevraagd hebt dan kun je dit bericht negeren.\n\nMet vriendelijke groet,\nSite beheerder';

$_lang['setting_websignupemail_message'] = 'Web Inschrijf e-mail';
$_lang['setting_websignupemail_message_desc'] = 'Hier kun je het e-mailbericht opstellen welke gestuurd wordt naar gebruikers wanneer je een account voor ze aanmaakt en MODX een e-mail laat sturen met de te gebruiken gebruikersnaam en wachtwoord.<br /><strong>Let op:</strong> de volgende placeholders worden vervangen door de Content Manager wanneer het bericht verstuurd wordt:<br /><br />[[+sname]] - Naam van jouw website<br />[[+saddr]] - Het standaard e-mailadres<br />[[+surl]] - Website URL<br />[[+uid]] - Gebruikers loginnaam of id<br />[[+pwd]] - Gebruikerswachtwoord<br />[[+ufn]] - Gebruikers volledige naam<br /><br />Laat de [[+uid]] en [[+pwd]] in het e-mailbericht, want anders wordt er geen gebruikersnaam en wachtwoord gestuurd en weet de gebruiker niet hoe hij/zij moet inloggen!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hallo [[+uid]] \n\nHier jouw login gegevens voor [[+sname]]:\n\nGebruikersnaam: [[+uid]]\nWachtwoord: [[+pwd]]\n\nEenmaal ingelogd op [[+sname]] ([[+surl]]), kun je je wachtwoord wijzigen.\n\nMet vriendelijke groet,\nSite Administrator';

$_lang['setting_welcome_screen'] = 'Toon welkomstscherm';
$_lang['setting_welcome_screen_desc'] = 'Indien op waar gezet, dan wordt het welkomstscherm wordt getoond bij het laden van de welkomstpagina en daarna niet meer.';

$_lang['setting_welcome_screen_url'] = 'Welkomstscherm URL';
$_lang['setting_welcome_screen_url_desc'] = 'De URL voor het welkomstscherm dat geladen wordt bij de eerste keer laden van MODX Revolution.';

$_lang['setting_welcome_action'] = 'Welkom Actie';
$_lang['setting_welcome_action_desc'] = 'De standaard controller om te laden wanneer de manager bezocht wordt zonder dat een actie in de URL is gespecificeerd.';

$_lang['setting_welcome_namespace'] = 'Welkom Namespace';
$_lang['setting_welcome_namespace_desc'] = 'De namespace voor de Welkom Actie.';

$_lang['setting_which_editor'] = 'Te gebruiken editor';
$_lang['setting_which_editor_desc'] = 'Hier kun je selecteren welke rich tekst editor je wenst te gebruiken. Je kunt alternatieve rich tekst editors downloaden en installeren vai de Pakket Manager.';

$_lang['setting_which_element_editor'] = 'Te gebruiken editor voor elementen';
$_lang['setting_which_element_editor_desc'] = 'Hier kun je selecteren welke rich tekst editor je wenst te gebruiken om elemente te bewerken. Je kunt alternatieve rich tekst editors downloaden en installeren vai de Pakket Manager.';

$_lang['setting_xhtml_urls'] = 'XHTML URLs';
$_lang['setting_xhtml_urls_desc'] = 'Indien op waar gezet, alle URLs gegenereerd door MODX zijn XHTML-compliant, inclusief de encoding van het ampersand karakter.';

$_lang['setting_default_context'] = 'Standaard Context';
$_lang['setting_default_context_desc'] = 'Selecteer de standaard Context die je wilt gebruiken bij nieuwe Resources.';

$_lang['setting_auto_isfolder'] = 'Automatisch container instellen';
$_lang['setting_auto_isfolder_desc'] = 'Indien ingeschakeld zal de container eigenschap van een resource automatisch bijgewerkt worden bij het maken van wijzigingen in de document boomstructuur. ';

$_lang['setting_default_username'] = 'Standaard gebruikersnaam';
$_lang['setting_default_username_desc'] = 'De standaard gebruikersnaam voor een gebruiker die niet is ingelogd. ';

$_lang['setting_manager_use_fullname'] = 'Toon volledige naam in manager';
$_lang['setting_manager_use_fullname_desc'] = 'Indien ingesteld op ja zal de volledige naam van de gebruiker getoond worden in de manager, in plaats van de username';

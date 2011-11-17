<?php
/**
 * Config Check Czech lexicon topic
 *
 * @language cs
 * @package modx
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2011-10-23
 */
// $_lang['configcheck_admin'] = 'Please contact a systems administrator and warn them about this message!';
$_lang['configcheck_admin'] = 'Kontaktujte prosím administrátora systému a sdělte mu varování z této zprávy!';

// $_lang['configcheck_cache'] = 'cache directory not writable';
$_lang['configcheck_cache'] = 'do složky "cache" nelze zapisovat';

// $_lang['configcheck_cache_msg'] = 'MODX cannot write to the cache directory. MODX will still function as expected, but no caching will take place. To solve this, make the /_cache/ directory writable.';
$_lang['configcheck_cache_msg'] = 'MODX nemůže zapisovat do složky "cache". MODX bude fungovat, ale nebude se provádět ukládání do cache. Pro vyřešení tohoto problému nastavte atributy složky "core/cache" pro zápis.';

// $_lang['configcheck_configinc'] = 'Config file still writable!';
$_lang['configcheck_configinc'] = 'Do konfiguračního souboru je stále možno zapisovat!';

// $_lang['configcheck_configinc_msg'] = 'Your site is vulnerable to hackers who could do a lot of damage to the site. Please make your config file read only! If you are not the site admin, please contact a systems administrator and warn them about this message! It is located at [[+path]]';
$_lang['configcheck_configinc_msg'] = 'Váš portál je zranitelný hackery a mohlo by dojít k jeho poškození. Nastavte atributy konfiguračního souboru "[[+path]]" pouze pro čtení!';

// $_lang['configcheck_default_msg'] = 'An unspecified warning was found. Which is strange.';
$_lang['configcheck_default_msg'] = 'Bylo zjištěno nespecifikované varování. To je teda krapet divné.';

// $_lang['configcheck_errorpage_unavailable'] = 'Your site's Error page is not available.';
$_lang['configcheck_errorpage_unavailable'] = 'Chybová stránka Vašeho portálu není dostupná.';

// $_lang['configcheck_errorpage_unavailable_msg'] = 'This means that your Error page is not accessible to normal web surfers or does not exist. This can lead to a recursive looping condition and many errors in your site logs. Make sure there are no webuser groups assigned to the page.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'To znamená, že chybová stránka není dostupná pro návštěvníky webu nebo neexistuje. To může vést k rekurzivní smyčce a mnoha chybám v chybových zprávách. Ujistěte se, že tu není žádná skupina webových uživatelů přiřazená k této stránce.';

// $_lang['configcheck_errorpage_unpublished'] = 'Your site's Error page is not published or does not exist.';
$_lang['configcheck_errorpage_unpublished'] = 'Chybová stránka portálu není publikovaná nebo neexistuje.';

// $_lang['configcheck_errorpage_unpublished_msg'] = 'This means that your Error page is inaccessible to the general public. Publish the page or make sure it is assigned to an existing document in your site tree in the System &gt; System Settings menu.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'To znamená, že chybová stránka není dostupná pro návštěvníky webu. Publikujte tuto stránku a ujistěte se, že je chybová stránka definována v menu "Systém &gt; Konfigurace systému".';

// $_lang['configcheck_images'] = 'Images directory not writable';
$_lang['configcheck_images'] = 'Do složky pro obrázky nelze zapisovat';

// $_lang['configcheck_images_msg'] = 'The images directory isn't writable, or doesn't exist. This means the Image Manager functions in the editor will not work!';
$_lang['configcheck_images_msg'] = 'Složka pro obrázky je pouze pro čtení nebo neexistuje. To znamená, že Správce obrázků nebude pracovat správně!';

// $_lang['configcheck_installer'] = 'Installer still present';
$_lang['configcheck_installer'] = 'Instalátor stále existuje!';

// $_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! He'd probably not get too far, because he'll need to enter some user information for the database, but it's still best to remove this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_installer_msg'] = 'Hlavní složka stále obsahuje složku "setup" obsahující instalátor systému MODX. Mohlo by dojít k narušení bezpečnosti systému pokud by neoprávněná osoba spustila instalátor! It is located at: [[+path]]';

// $_lang['configcheck_lang_difference'] = 'Incorrect number of entries in language file';
$_lang['configcheck_lang_difference'] = 'Nesprávný počet záznamů v jazykovém souboru';

// $_lang['configcheck_lang_difference_msg'] = 'The currently selected language has a different number of entries than the default language. While not necessarily a problem, this may mean the language file needs to be updated.';
$_lang['configcheck_lang_difference_msg'] = 'Aktuálně vybraný jazyk má rozdílný počet záznamů než výchozí jazyk angličtina. Mělo by dojít k aktualizaci jazykového souboru pro tuto verzi MODX.';

// $_lang['configcheck_notok'] = 'One or more configuration details didn't check out OK: ';
$_lang['configcheck_notok'] = 'Jeden nebo více konfiguračních údajů není správně nastaveno: ';

// $_lang['configcheck_ok'] = 'Check passed OK - no warnings to report.';
$_lang['configcheck_ok'] = 'Kontrola OK - žádné varování do reportu.';

// $_lang['configcheck_register_globals'] = 'register_globals is set to ON in your php.ini configuration file';
$_lang['configcheck_register_globals'] = 'register_globals v php.ini je nastaveno na ON';

// $_lang['configcheck_register_globals_msg'] = 'This configuration makes your site much more susceptible to Cross Site Scripting (XSS) attacks. You should speak to your host about what you can do to disable this setting.';
$_lang['configcheck_register_globals_msg'] = 'Díky tomtuto nastavení je Váš portál mnohem více náchylný k hackerským útokům typu Cross Site Scripting (XSS). Měli by jste pohovořit se svým poskytovatelem hostingu a zjistit co je možné udělat k deaktivaci tohoto nastavení.';

// $_lang['configcheck_title'] = 'Configuration check';
$_lang['configcheck_title'] = 'Kontrola nastavení';

// $_lang['configcheck_unauthorizedpage_unavailable'] = 'Your site's Unauthorized page is not published or does not exist.';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Stránko o neautorizovaném přístupu není publikovaná nebo neexistuje.';

// $_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'This means that your Unauthorized page is not accessible to normal web surfers or does not exist. This can lead to a recursive looping condition and many errors in your site logs. Make sure there are no webuser groups assigned to the page.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'To znamená, že stránka o neautorizovaném přístupu není dostupná pro návštěvníky webu nebo neexistuje. To může vést k rekurzivní smyčce a mnoha chybám v chybových zprávách. Ujistěte se, že tu není žádná skupina webových uživatelů přiřazená k této stránce.';

// $_lang['configcheck_unauthorizedpage_unpublished'] = 'The Unauthorized page defined in the site configuration settings is not published.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Stránka o neautorizovaném přístupu definovaná v nastavení portálu není publikovaná.';

// $_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'This means that your Unauthorized page is inaccessible to the general public. Publish the page or make sure it is assigned to an existing document in your site tree in the System &gt; System Settings menu.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'To znamená, že stránka o neautorizovaném přístupu není dostupná pro návštěvníky webu. Publikujte stránku a ujistěte se, že je tato stránka definována v menu "Systém &gt; Konfigurace systému".';

// $_lang['configcheck_warning'] = 'Configuration warning:';
$_lang['configcheck_warning'] = 'Varování:';

// $_lang['configcheck_what'] = 'What does this mean?';
$_lang['configcheck_what'] = 'Co to znamená?';

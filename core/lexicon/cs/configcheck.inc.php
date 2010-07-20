<?php
/**
 * Config Check Czech lexicon topic
 *
 * @language cs
 * @package modx
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2010-07-17
 */
$_lang['configcheck_admin'] = 'Kontaktujte prosím administrátora systému a sdělte mu varování z této zprávy!';
$_lang['configcheck_cache'] = 'do složky "cache" nelze zapisovat';
$_lang['configcheck_cache_msg'] = 'MODx nemůže zapisovat do složky "cache". MODx bude fungovat, ale nebude se provádět ukládání do cache. Pro vyřešení tohoto problému nastavte atributy složky "core/cache" pro zápis.';
$_lang['configcheck_configinc'] = 'Do konfiguračního souboru je stále možno zapisovat!';
$_lang['configcheck_configinc_msg'] = 'Váš portál je zranitelný hackery a mohlo by dojít k jeho poškození. Nastavte atributy konfiguračního souboru "core/config/config.inc.php" pouze pro čtení!';
$_lang['configcheck_default_msg'] = 'Bylo zjištěno nespecifikované varování. To je teda krapet divné.';
$_lang['configcheck_errorpage_unavailable'] = 'Chybová stránka Vašeho portálu není dostupná.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'To znamená, že chybová stránka není dostupná pro návštěvníky webu nebo neexistuje. To může vést k rekurzivní smyčce a mnoha chybám v chybových zprávách. Ujistěte se, že tu není žádná skupina webových uživatelů přiřazená k této stránce.';
$_lang['configcheck_errorpage_unpublished'] = 'Chybová stránka portálu není publikovaná nebo neexistuje.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'To znamená, že chybová stránka není dostupná pro návštěvníky webu. Publikujte tuto stránku a ujistěte se, že je chybová stránka definována v menu "Systém &gt; Konfigurace systému".';
$_lang['configcheck_images'] = 'Do složky pro obrázky nelze zapisovat';
$_lang['configcheck_images_msg'] = 'Složka pro obrázky je pouze pro čtení nebo neexistuje. To znamená, že Správce obrázků nebude pracovat správně!';
$_lang['configcheck_installer'] = 'Instalátor stále existuje';
$_lang['configcheck_installer_msg'] = 'Hlavní složka stále obsahuje složku "setup" obsahující instalátor systému MODx. Mohlo by dojít k narušení bezpečnosti systému pokud by neoprávněná osoba spustila instalátor!';
$_lang['configcheck_lang_difference'] = 'Nesprávný počet záznamů v jazykovém souboru';
$_lang['configcheck_lang_difference_msg'] = 'Aktuálně vybraný jazyk má rozdílný počet záznamů než výchozí jazyk angličtina. Mělo by dojít k aktualizaci jazykového souboru pro tuto verzi MODx.';
$_lang['configcheck_notok'] = 'Jeden nebo více konfiguračních údajů není správně nastaveno: ';
$_lang['configcheck_ok'] = 'Kontrola OK - žádné varování do reportu.';
$_lang['configcheck_register_globals'] = 'register_globals v php.ini je nastaveno na ON';
$_lang['configcheck_register_globals_msg'] = 'Díky tomtuto nastavení je Váš portál mnohem více náchylný k hackerským útokům typu Cross Site Scripting (XSS). Měli by jste pohovořit se svým poskytovatelem hostingu a zjistit co je možné udělat k deaktivaci tohoto nastavení.';
$_lang['configcheck_title'] = 'Kontrola nastavení';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Stránko o neautorizovaném přístupu není publikovaná nebo neexistuje.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'To znamená, že stránka o neautorizovaném přístupu není dostupná pro návštěvníky webu nebo neexistuje. To může vést k rekurzivní smyčce a mnoha chybám v chybových zprávách. Ujistěte se, že tu není žádná skupina webových uživatelů přiřazená k této stránce.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Stránka o neautorizovaném přístupu definovaná v nastavení portálu není publikovaná.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'To znamená, že stránka o neautorizovaném přístupu není dostupná pro návštěvníky webu. Publikujte stránku a ujistěte se, že je tato stránka definována v menu "Systém &gt; Konfigurace systému".';
$_lang['configcheck_warning'] = 'Varování:';
$_lang['configcheck_what'] = 'Co to znamená?';
<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Kontaktujte prosím administrátora systému a sdělte mu varování z této zprávy!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post povoleno v nastavení kontextu mimo `mgr`';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Nastavení kontextu allow_tags_in_post je povoleno v instalaci MODX mimo kontext mgr. MODX doporučuje toto nastavení zakázat pokud nepotřebujete, aby uživatelé MODX mohli posílat MODX značky, číselné entity nebo HTML skript tagy pomocí POST metody do formuláře. Toto by mělo být obecně zakázáno kromě kontextu mgr.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post povoleno v Konfiguraci systému';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Nastavení allow_tags_in_post v Konfiguraci systému System Setting je povoleno v instalaci MODX. MODX doporučuje toto nastavení zakázat pokud nepotřebujete, aby uživatelé MODX mohli posílat MODX značky, číselné entity nebo HTML skript tagy pomocí POST metody do formuláře. Je lepší tuto hodnotu aktivovat v rámci nastavení daných kontextů.';
$_lang['configcheck_cache'] = 'Do složky "cache" nelze zapisovat';
$_lang['configcheck_cache_msg'] = 'MODX nemůže zapisovat do složky "cache". MODX bude fungovat, ale nebude se provádět ukládání do cache. Pro vyřešení tohoto problému nastavte atributy složky "core/cache" pro zápis.';
$_lang['configcheck_configinc'] = 'Do konfiguračního souboru je stále možno zapisovat!';
$_lang['configcheck_configinc_msg'] = 'Váš portál je zranitelný hackery a mohlo by dojít k jeho poškození. Nastavte atributy konfiguračního souboru "[[+path]]" pouze pro čtení!';
$_lang['configcheck_default_msg'] = 'Bylo zjištěno nespecifikované varování. To je teda krapet divné.';
$_lang['configcheck_errorpage_unavailable'] = 'Chybová stránka Vašeho portálu není dostupná.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'To znamená, že chybová stránka není dostupná pro návštěvníky webu nebo neexistuje. Může to vést k rekurzivní smyčce a mnoha chybám v chybových zprávách. Ujistěte se, že tu není žádná skupina webových uživatelů přiřazená k této stránce.';
$_lang['configcheck_errorpage_unpublished'] = 'Chybová stránka portálu není publikována nebo neexistuje.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Znamená, že chybová stránka není dostupná pro návštěvníky webu. Publikujte tuto stránku a ujistěte se, že je chybová stránka definována v menu "Systém &gt; Konfigurace systému".';
$_lang['configcheck_htaccess'] = 'Složka "core" je přístupná z webu';
$_lang['configcheck_htaccess_msg'] = 'MODX zjistil, že složka "core" je (částečně) přístupná veřejnosti. <strong>To není doporučeno a jedná se o bezpečnostní riziko.</strong> Pokud vaše instalace MODX běží na webserveru Apache měli by jste alespoň nastavit soubor .htaccess uvnitř složky "core" <em>[[+ fileLocation]]</em>. To lze snadno provést přejmenování existujícího příkladového souboru ht.access na soubor .htaccess. <p>Pro další informace jak zlepšit zabezpečení MODX si přečtěte článek <a href="https://rtfm.modx.com/revolution/2.x/administering-your-site/security/hardening-modx-revolution">Hardening MODX Revolution [en]</a>.</p> Pokud máte vše správně nastaveno, pak by například otevření odkazu <a href="[[+checkUrl]]" target="_blank"> Changelog</a> mělo zobrazit hlášení 403 (Přístup odepřen) nebo ještě lépe 404 (Stránka nenalezena). Pokud se zobrazí obsah changelog souboru v prohlížeči, pak je stále něco nesprávně nastaveno a je třeba upravit konfiguraci, nejlépe zavolejte odborníka.';
$_lang['configcheck_images'] = 'Do složky pro obrázky nelze zapisovat';
$_lang['configcheck_images_msg'] = 'Složka pro obrázky je pouze pro čtení nebo neexistuje. To znamená, že správce obrázků nebude pracovat správně!';
$_lang['configcheck_installer'] = 'Instalátor stále existuje!';
$_lang['configcheck_installer_msg'] = 'Hlavní složka stále obsahuje složku "setup" obsahující instalátor systému MODX. Mohlo by dojít k narušení bezpečnosti systému pokud by neoprávněná osoba spustila instalátor, který je umístěn zde: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Nesprávný počet záznamů v jazykovém souboru';
$_lang['configcheck_lang_difference_msg'] = 'Aktuálně vybraný jazyk má rozdílný počet záznamů než výchozí jazyk angličtina. Mělo by dojít k aktualizaci jazykového souboru pro tuto verzi MODX.';
$_lang['configcheck_notok'] = 'Jeden nebo více konfiguračních údajů není správně nastaveno: ';
$_lang['configcheck_ok'] = 'Kontrola OK - žádné varování do reportu.';
$_lang['configcheck_phpversion'] = 'Verze PHP je zastaralá';
$_lang['configcheck_phpversion_msg'] = 'Vaše verze PHP [[+ phpversion]] již není vyvíjena vývojáři PHP, což znamená, že nejsou k dispozici žádné aktualizace zabezpečení. Je rovněž pravděpodobné, že MODX nebo některý z balíčků již dnes nebo v blízké budoucnosti nebude podporovat tuto verzi. Prosím aktualizujte své prostředí na serveru alespoň na verzi PHP [[+ phprequired]] co nejdříve.';
$_lang['configcheck_register_globals'] = 'register_globals v php.ini je nastaveno na ON';
$_lang['configcheck_register_globals_msg'] = 'Díky tomtuto nastavení je Váš portál mnohem více náchylný k hackerským útokům typu Cross Site Scripting (XSS). Měli by jste pohovořit se svým poskytovatelem hostingu a zjistit co je možné udělat k deaktivaci tohoto nastavení.';
$_lang['configcheck_title'] = 'Kontrola nastavení';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Stránko o neautorizovaném přístupu není publikována nebo neexistuje.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'To znamená, že stránka o neautorizovaném přístupu není dostupná pro návštěvníky webu nebo neexistuje. To může vést k rekurzivní smyčce a mnoha chybám v chybových zprávách. Ujistěte se, že tu není žádná skupina webových uživatelů přiřazená k této stránce.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Stránka o neautorizovaném přístupu definovaná v nastavení portálu není publikována.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'To znamená, že stránka o neautorizovaném přístupu není dostupná pro návštěvníky webu. Publikujte stránku a ujistěte se, že je tato stránka definována v menu "Systém &gt; Konfigurace systému".';
$_lang['configcheck_warning'] = 'Varování:';
$_lang['configcheck_what'] = 'Co to znamená?';

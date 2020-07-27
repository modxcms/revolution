<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Palun kontakteeruge süsteemi administraatoriga ja teavitage teda sellest teatest!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post Context Setting Enabled outside `mgr`';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'The allow_tags_in_post Context Setting is enabled in your installation outside the mgr Context. MODX recommends this setting be disabled unless you need to explicitly allow users to submit MODX tags, numeric entities, or HTML script tags via the POST method to a form in your site. This should generally be disabled except in the mgr Context.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post System Setting Enabled';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'The allow_tags_in_post System Setting is enabled in your installation. MODX recommends this setting be disabled unless you need to explicitly allow users to submit MODX tags, numeric entities, or HTML script tags via the POST method to a form in your site. It is better to enable this via Context Settings for specific Contexts.';
$_lang['configcheck_cache'] = 'cache kaust ei ole kirjutatav';
$_lang['configcheck_cache_msg'] = 'MODX ei suuda kirjutada cache kausta. MODX töötab nii nagu peab aga puhverdamist ei toimu. Et viga parandada, palun lisa /_cache/ kaustale vastavad õigused.';
$_lang['configcheck_configinc'] = 'Config fail on ikka kirjutatav!';
$_lang['configcheck_configinc_msg'] = 'Teie leht on haavatav häkkerite poolt, kes võivad palju kahju teha sellele lehele. Palun muuda config fail ainult loetavaks (read-only)! Kui Te ei ole lehe administraator, siis palun teavitage süsteemi administraatorit ja edastage see sõnum! Fail asub core/config/config.inc.php';
$_lang['configcheck_default_msg'] = 'Täpsustamata hoiatus leiti. Mis on imelk.';
$_lang['configcheck_errorpage_unavailable'] = 'Teie lehe vealeht (Error page) ei ole saadaval.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'See tähendab, et vealeht ei ole juurdepääsetav või eksisteeri, et kuvada veebikülastajatele. See võib viia lõppemata ringile ja tekitab palju vigasid lehe logidesse. Kontrolli, et veebikasutaja gruppe ei ole määratud sellele lehele.';
$_lang['configcheck_errorpage_unpublished'] = 'Teie lehe vealeht (Error page) ei ole avalikustatud (published) või ei eksisteeri.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'See tähendab, et vealehele ei pääse veebikülastaja ligi. Palun Avalikusta see leht või kontrolli, et Süsteem &gt; Süsteemi Seaded menüüs oleks see määratud olemasolevale dokumendile.';
$_lang['configcheck_htaccess'] = 'Core folder is accessible by web';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/current/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'Piltide (Images) kaust ei ole kirjutatav';
$_lang['configcheck_images_msg'] = 'Piltide kaust ei ole kirjutatav või ei ekisteeri. See tähendab, et Image Manager funktsioonid ei tööta!';
$_lang['configcheck_installer'] = 'Installer ikka eksiteerib';
$_lang['configcheck_installer_msg'] = 'Kataloog setup/ sisaldab MODX installerit. Kujuta ette, mis võib juhtuda, kui võõras leiab selle kausta ja jooksutab installerit! Ta arvatavasti ei jõuaks kaugele, kuna ta peab sisestama informatsiooni andmebaasi kasutaja kohta aga ikkagi parim oleks eemaldada see kaust serverist.';
$_lang['configcheck_lang_difference'] = 'Ebakorrketne arv sissekanded keele failis';
$_lang['configcheck_lang_difference_msg'] = 'Hetkel valitud keelel on erinev arv sissekanded kui põhikeele failis. See ei pruugi olla probleem, kuid see võib tähendada, et keele fail vajab uuendust.';
$_lang['configcheck_notok'] = 'Mõned konfiguratiooni detailid vajavad tähelepanu: ';
$_lang['configcheck_ok'] = 'Kontroll läbitud OK - hoiatused puuduvad.';
$_lang['configcheck_phpversion'] = 'PHP version is outdated';
$_lang['configcheck_phpversion_msg'] = 'Your PHP version [[+phpversion]] is no longer maintained by the PHP developers, which means no security updates are available. It is also likely that MODX or an extra package now or in the near future will no longer support this version. Please update your environment at least to PHP [[+phprequired]] as soon as possible to secure your site.';
$_lang['configcheck_register_globals'] = 'register_globals väärtus on ON teie php.ini konfiguratiooni failis';
$_lang['configcheck_register_globals_msg'] = 'Selline konfiguratioon muudab teie lehe vastuvõtlikumaks XSS rünnakutele. Sa peaksid küsima nõu oma veebimajutajalt kuidas see seadistus keelata.';
$_lang['configcheck_title'] = 'Konfiguratiooni kontroll';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Teie lehe Unauthorized leht ei ole avalikustatud või ei ekisteeri.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'See tähendab, et teie Unauthorized leht ei ole juurdepääsetav veebikülastajale. See võib viia lõppemata ringile ja tekitab palju vigasid lehe logidesse. Kontrolli, et veebikasutaja gruppe ei ole määratud sellele lehele.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Unauthorized leht, mis on määratud lehe konfiguratioonis, ei ole avalikustatud.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'See tähendab, et Unauthorized leht ei ole juurdepääsetav veebikülastajatele. Palun Avalikusta see leht või kontrolli, et Süsteem &gt; Süsteemi Seaded menüüs oleks see määratud olemasolevale dokumendile.';
$_lang['configcheck_warning'] = 'Konfiguratiooni hoiatus:';
$_lang['configcheck_what'] = 'Mis see tähendab?';

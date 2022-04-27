<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Ota yhteyttä järjestelmän ylläpitäjään ja varoita heitä tästä viestistä!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post Konteksti-asetus käytössä `mgr`:n ulkopuolella';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'Allow_tags_in_post Konteksti -asetus on käytössä mgr Kontekstin ulkopuolella. MODX suosittelee, että tämä asetus poistetaan käytöstä, ellei sinun tarvitse nimenomaisesti sallia käyttäjien lähettää MODX tunnisteita, numeerisia kohteita tai HTML-koodeja POST-menetelmän kautta sivustosi lomakkeelle. Tämä asetus kannattaa yleensä poistaa käytöstä paitsi mgr Kontekstin yhteydessä.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post järjestelmäasetus käytössä';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'Asennuksen Allow_tags_in_post Konteksti-asetus on käytössä. MODX suosittelee, että tämä asetus poistetaan käytöstä, ellei sinun tarvitse nimenomaisesti sallia käyttäjien lähettää MODX tunnisteita, numeerisia kohteita tai HTML-koodeja POST-menetelmän kautta sivustosi lomakkeelle. Tämä asetus kannattaa ottaa käyttöön Konteksti -asetuksista kullekin Kontekstille.';
$_lang['configcheck_cache'] = 'Cache-hakemisto ei ole kirjoitettavissa';
$_lang['configcheck_cache_msg'] = 'MODX cannot write to the cache directory. MODX will still function as expected, but no caching will take place. To solve this, make the /cache/ directory writable.';
$_lang['configcheck_configinc'] = 'Config-tiedosto yhä kirjoitettavissa!';
$_lang['configcheck_configinc_msg'] = 'Sivustosi on alttiina hakkereille, jotka voivat tehdä paljon vahinkoa sivustolle. Tee Config-tiedostosta vain luettava! Jos et ole sivuston ylläpitäjä, ota yhteyttä järjestelmän ylläpitäjään ja varoita tästä viestistä! Se sijaitsee täällä [[+path]]';
$_lang['configcheck_default_msg'] = 'Määrittelemätön varoitus on löytynyt. Mikä on outoa.';
$_lang['configcheck_errorpage_unavailable'] = 'Sivuston virhesivu ei ole käytettävissä.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Tämä tarkoittaa, että Virhe -sivu ei ole käytössä normaalin web-käytön yhteydessä tai sitä ei ole olemassa. Tämä voi johtaa rekursiiviseen silmukoiden ehtoon ja voi aiheuttaa paljon virheitä sivuston loki-tiedostoon. Varmista, että sivuun ei ole määritetty web-käyttäjäryhmiä.';
$_lang['configcheck_errorpage_unpublished'] = 'Sivuston Virhe-sivua ei ole julkaistu tai sitä ei ole.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Tämä tarkoittaa, että Virhe-sivusi ei ole julkisten käyttäjien saatavilla. Julkaise sivu tai varmista  Järjestelmä &gt; Järjestelmäasetukset -valikosta, että se on määritetty olemassa olevaan dokumenttiin sivuston hakemistopuussa.';
$_lang['configcheck_htaccess'] = 'Core folder is accessible by web';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/3.x/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'Kuvat -hakemisto ei ole kirjoitettavissa';
$_lang['configcheck_images_msg'] = 'Kuvat -hakemisto ei ole kirjoitettavissa tai sitä ei ole olemassa. Tämä tarkoittaa, että Manager -toiminnot editorissa eivät toimi!';
$_lang['configcheck_installer'] = 'Asennus -hakemisto edelleen olemassa';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to delete this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Virheellinen määrä merkintöjä kielitiedostossa';
$_lang['configcheck_lang_difference_msg'] = 'Valittuna olevassa kielessä on eri määrä merkintöjä kuin oletuskielessä. Vaikka tämä ei välttämättä aiheuta ongelmia, voi olla että kielitiedosto pitää päivittää.';
$_lang['configcheck_notok'] = 'Yksi tai useampi yksittäinen asetus ei läpäissyt tarkistusta: ';
$_lang['configcheck_phpversion'] = 'PHP version is outdated';
$_lang['configcheck_phpversion_msg'] = 'Your PHP version [[+phpversion]] is no longer maintained by the PHP developers, which means no security updates are available. It is also likely that MODX or an extra package now or in the near future will no longer support this version. Please update your environment at least to PHP [[+phprequired]] as soon as possible to secure your site.';
$_lang['configcheck_register_globals'] = 'register_globals on asetettu päälle php.ini asetustiedostossa';
$_lang['configcheck_register_globals_msg'] = 'Tämä kokoonpano tekee sivustosi alttiimmaksi Cross Site Scripting (XSS) hyökkäyksille. Tiedustele sivuston ylläpitäjältä, miten tämä asetus poistetaan käytöstä.';
$_lang['configcheck_title'] = 'Kokoonpanon tarkistus';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Sivuston Luvaton -sivua ei ole julkaistu tai sitä ei ole olemassa.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Tämä tarkoittaa, että Luvaton -sivu ei ole käytössä normaalin web-käytön yhteydessä tai sitä ei ole olemassa. Tämä voi johtaa rekursiiviseen silmukoiden ehtoon ja voi aiheuttaa paljon virheitä sivuston loki-tiedostoon. Varmista, että sivuun ei ole määritetty web-käyttäjäryhmiä.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Sivuston kokoonpanoasetuksissa määriteltyä Luvaton -sivua ei ole julkaistu.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Tämä tarkoittaa, että Luvaton-sivusi ei ole julkisten käyttäjien saatavilla. Julkaise sivu tai varmista  Järjestelmä &gt; Järjestelmäasetukset -valikosta, että se on määritetty olemassa olevaan dokumenttiin sivuston hakemistopuussa.';
$_lang['configcheck_warning'] = 'Kokoonpanovaroitus:';
$_lang['configcheck_what'] = 'Mitä tämä tarkoittaa?';

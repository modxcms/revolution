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
$_lang['configcheck_cache_msg'] = 'MODX ei voi kirjoittaa cache-hakemistoon. MODX toimii yhä odotetusti, mutta välimuistiin lataaminen ei ole käytössä. Voit ratkaista tämän tekemällä /_cache/ -hakemistosta kirjoitettavan.';
$_lang['configcheck_configinc'] = 'Config-tiedosto yhä kirjoitettavissa!';
$_lang['configcheck_configinc_msg'] = 'Sivustosi on alttiina hakkereille, jotka voivat tehdä paljon vahinkoa sivustolle. Tee Config-tiedostosta vain luettava! Jos et ole sivuston ylläpitäjä, ota yhteyttä järjestelmän ylläpitäjään ja varoita tästä viestistä! Se sijaitsee täällä [[+ polku]]';
$_lang['configcheck_default_msg'] = 'Määrittelemätön varoitus on löytynyt. Mikä on outoa.';
$_lang['configcheck_errorpage_unavailable'] = 'Sivuston virhesivu ei ole käytettävissä.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Tämä tarkoittaa, että Virhe -sivu ei ole käytössä normaalin web-käytön yhteydessä tai sitä ei ole olemassa. Tämä voi johtaa rekursiiviseen silmukoiden ehtoon ja voi aiheuttaa paljon virheitä sivuston loki-tiedostoon. Varmista, että sivuun ei ole määritetty web-käyttäjäryhmiä.';
$_lang['configcheck_errorpage_unpublished'] = 'Sivuston Virhe-sivua ei ole julkaistu tai sitä ei ole.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Tämä tarkoittaa, että Virhe-sivusi ei ole julkisten käyttäjien saatavilla. Julkaise sivu tai varmista  Järjestelmä &gt; Järjestelmäasetukset -valikosta, että se on määritetty olemassa olevaan dokumenttiin sivuston hakemistopuussa.';
$_lang['configcheck_images'] = 'Kuvat -hakemisto ei ole kirjoitettavissa';
$_lang['configcheck_images_msg'] = 'Kuvat -hakemisto ei ole kirjoitettavissa tai sitä ei ole olemassa. Tämä tarkoittaa, että Manager -toiminnot editorissa eivät toimi!';
$_lang['configcheck_installer'] = 'Asennus -hakemisto edelleen olemassa';
$_lang['configcheck_installer_msg'] = 'Järjestelmän asennustiedostot ovat yhä palvelimen kansiossa:  [[+path]]. On suositeltavaa poistaa kansio: [[+path]].';
$_lang['configcheck_lang_difference'] = 'Virheellinen määrä merkintöjä kielitiedostossa';
$_lang['configcheck_lang_difference_msg'] = 'Valittuna olevassa kielessä on eri määrä merkintöjä kuin oletuskielessä. Vaikka tämä ei välttämättä aiheuta ongelmia, voi olla että kielitiedosto pitää päivittää.';
$_lang['configcheck_notok'] = 'Yksi tai useampi yksittäinen asetus ei läpäissyt tarkistusta: ';
$_lang['configcheck_ok'] = 'Tarkistus sujui ongelmitta - ei varoituksia.';
$_lang['configcheck_register_globals'] = 'register_globals on asetettu päälle php.ini asetustiedostossa';
$_lang['configcheck_register_globals_msg'] = 'Tämä kokoonpano tekee sivustosi alttiimmaksi Cross Site Scripting (XSS) hyökkäyksille. Tiedustele sivuston ylläpitäjältä, miten tämä asetus poistetaan käytöstä.';
$_lang['configcheck_title'] = 'Kokoonpanon tarkistus';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Sivuston Luvaton -sivua ei ole julkaistu tai sitä ei ole olemassa.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Tämä tarkoittaa, että Luvaton -sivu ei ole käytössä normaalin web-käytön yhteydessä tai sitä ei ole olemassa. Tämä voi johtaa rekursiiviseen silmukoiden ehtoon ja voi aiheuttaa paljon virheitä sivuston loki-tiedostoon. Varmista, että sivuun ei ole määritetty web-käyttäjäryhmiä.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Sivuston kokoonpanoasetuksissa määriteltyä Luvaton -sivua ei ole julkaistu.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Tämä tarkoittaa, että Luvaton-sivusi ei ole julkisten käyttäjien saatavilla. Julkaise sivu tai varmista  Järjestelmä &gt; Järjestelmäasetukset -valikosta, että se on määritetty olemassa olevaan dokumenttiin sivuston hakemistopuussa.';
$_lang['configcheck_warning'] = 'Kokoonpanovaroitus:';
$_lang['configcheck_what'] = 'Mitä tämä tarkoittaa?';
<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Neem contact op met de systeembeheerder en waarschuw hem over deze melding!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post Context Instelling is ingeschakeld buiten de `mgr` context';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'De allow_tags_in_post Context Setting is in deze installatie ingeschakeld buiten de "mgr" context. Tenzij de gebruikers van de website specifiek MODX tags, genummerde entities of HTML script tags via POST naar een of meerdere formulieren op de site moeten kunnen sturen is het sterk aan te raden deze instelling te deactiveren. Over het algemeen moet alleen de "mgr" context deze instelling gebruiken.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post Systeem Instelling Ingeschakeld';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'De allow_tags_in_post System Setting is in deze installatie ingeschakeld. Tenzij de gebruikers van de website specifiek MODX tags, genummerde entities of HTML script tags via POST naar een of meerdere formulieren op de site moeten kunnen sturen is het sterk aan te raden deze instelling te deactiveren. Indien deze instelling nodig is, kan dit het beste op context niveau worden ingeschakeld met Context Settings.';
$_lang['configcheck_cache'] = 'cache map is niet schrijfbaar';
$_lang['configcheck_cache_msg'] = 'MODX cannot write to the cache directory. MODX will still function as expected, but no caching will take place. To solve this, make the /cache/ directory writable.';
$_lang['configcheck_configinc'] = 'Configuratiebestand nog steeds schrijfbaar!';
$_lang['configcheck_configinc_msg'] = 'Je website is kwetsbaar voor hackers die veel schade kunnen aanrichten. Zorg ervoor dat je configuratiebestand op alleen-lezen is ingesteld! Als je niet de beheerder bent, neem dan contact op met een systeembeheerder en waarschuw hem over deze melding! Het bestand kan gevonden worden in [[+path]]';
$_lang['configcheck_default_msg'] = 'Een onverwachte fout is opgetreden. Dit is toch wel een beetje apart.';
$_lang['configcheck_errorpage_unavailable'] = 'De foutpagina van je website is niet beschikbaar.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Dit betekent dat jouw foutpagina niet bereikbaar is voor bezoekers of deze bestaat niet. Dit kan leiden tot een oneindige loop en veel fouten in logbestanden. Zorg ervoor dat de foutpagina publiekelijk toegankelijk is.';
$_lang['configcheck_errorpage_unpublished'] = 'De foutpagina van je website is niet gepubliceerd of bestaat niet.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Dit betekent dat jouw foutpagina niet bereikbaar is voor bezoekers. Publiceer de pagina of controleer of het is gekoppeld aan een bestaand document in jouw site structuur in het Systeem > Systeeminstellingen menu.';
$_lang['configcheck_htaccess'] = 'Core map is toegankelijk op het web';
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
$_lang['configcheck_images'] = 'Afbeeldingen map is niet schrijfbaar';
$_lang['configcheck_images_msg'] = 'De afbeeldingen map is niet schrijfbaar of bestaat niet. Dit betekent dat Afbeeldingenbeheer functionaliteiten in de editor niet werken!';
$_lang['configcheck_installer'] = 'Installatie is nog aanwezig';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to delete this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Er is een onjuist aantal entries gevonden in het taalbestand';
$_lang['configcheck_lang_difference_msg'] = 'Het huidige geselecteerd taalbestand heeft een verschillend aantal entries dan de standaard taal. Waar dit niet direct een probleem is, kan dit betekenen dat dit bestand geupdate moet worden.';
$_lang['configcheck_notok'] = 'E&eacute;n of meerdere configuratiedetails zijn niet succesvol: ';
$_lang['configcheck_phpversion'] = 'Verouderde PHP versie';
$_lang['configcheck_phpversion_msg'] = 'De gebruikte PHP versie [[+phpversion]] is niet meer ondersteund door de PHP ontwikkelaars, wat betekent dat er geen beveiligingsupdates voor beschikbaar zijn. Het is mogelijk dat MODX of een uitbreiding voor MODX nu of in de toekomst deze versie niet meer zal ondersteunen. Zorg er voor dat uw server omgeving zo spoedig mogelijk geüpdatet wordt naar ten minste PHP [[+phprequired]] om uw site veilig te houden.';
$_lang['configcheck_register_globals'] = 'register_globals staat AAN in jouw php.ini bestand';
$_lang['configcheck_register_globals_msg'] = 'Deze configuratie maakt jouw website veel vatbaarder voor Cross Site Scripting (XSS) aanvallen. Je zult contact op moeten nemen met jouw provider om te bespreken wat je kunt doen om deze instelling uit te schakelen.';
$_lang['configcheck_title'] = 'Configuratiecontrole';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Jouw site\'s Geen-toegang pagina is niet gepubliceerd of bestaat niet.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Dit betekent dat jouw geen-toegang pagina niet bereikbaar is voor normale bezoekers of niet bestaat. Dit kan leiden tot een recursieve lus en er zullen veel errors door verschijnen op jouw site. Controleer dat geen normale gebruikersgroepen aan deze pagina gekoppeld zijn.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'De in de site configuratie instellingen gedefinieerde geen-toegangs pagina is niet gepubliceerd.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Dit betekent dat jouw geen-toegang pagina is onbereikbaar voor bezoekers. Publiceer de pagina of controleer of het is gekoppeld aan een bestaand document in jouw site structuur in het Systeem > Systeeminstellingen menu.';
$_lang['configcheck_warning'] = 'Configuratiewaarschuwing:';
$_lang['configcheck_what'] = 'Wat betekent dit?';

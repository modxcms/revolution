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
$_lang['configcheck_cache_msg'] = 'MODX kan niet naar de cache map schrijven. MODX zal nog steeds functioneren zoals verwacht, maar er zal geen caching plaatsvinden. Maak de /cache/ map schrijfbaar om dit op te lossen.';
$_lang['configcheck_configinc'] = 'Configuratiebestand nog steeds schrijfbaar!';
$_lang['configcheck_configinc_msg'] = 'Je website is kwetsbaar voor hackers die veel schade kunnen aanrichten. Zorg ervoor dat je configuratiebestand op alleen-lezen is ingesteld! Als je niet de beheerder bent, neem dan contact op met een systeembeheerder en waarschuw hem over deze melding! Het bestand kan gevonden worden in [[+path]]';
$_lang['configcheck_default_msg'] = 'Een onverwachte fout is opgetreden. Dit is toch wel een beetje apart.';
$_lang['configcheck_errorpage_unavailable'] = 'De foutpagina van je website is niet beschikbaar.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Dit betekent dat jouw foutpagina niet bereikbaar is voor bezoekers of deze bestaat niet. Dit kan leiden tot een oneindige loop en veel fouten in logbestanden. Zorg ervoor dat de foutpagina publiekelijk toegankelijk is.';
$_lang['configcheck_errorpage_unpublished'] = 'De foutpagina van je website is niet gepubliceerd of bestaat niet.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Dit betekent dat jouw foutpagina niet bereikbaar is voor bezoekers. Publiceer de pagina of controleer of het is gekoppeld aan een bestaand document in jouw site structuur in het Systeem > Systeeminstellingen menu.';
$_lang['configcheck_htaccess'] = 'Core map is toegankelijk op het web';
$_lang['configcheck_htaccess_msg'] = 'MODX heeft gedetecteerd dat jouw core folder (gedeeltelijk) toegankelijk is voor het publiek.
<strong>Dit is niet aanbevolen en een beveiligingsrisico.</strong>
Als jouw MODX installatie wordt uitgevoerd op een Apache webserver dan moet je op zijn minst het .htaccess bestand in de core map <em>[[+fileLocation]]</em> instellen.
Dit kan eenvoudig gedaan worden door het bestaande ht.access voorbeeldbestand te hernoemen naar .htaccess.
<p>Er zijn andere methoden en webservers die je kunt gebruiken lees de <a href="https://docs.modx.com/3.x/en/getting-started/maintenance/securing-modx">Versterk MODX Gids</a>
voor meer informatie over het beveiligen van jouw site.</p>
Als je alles correct hebt ingesteld, zou het bladeren naar bijv. het <a href="[[+checkUrl]]" target="_blank">Changelog</a> bestand je een 403 (machtiging geweigerd) of beter een 404 (niet gevonden) verwijzing moeten geven. Als je de changelog
daarin in de browser kunt zien, er is nog iets mis en moet je de instelling opnieuw configureren of moet je een expert hier naar laten kijken om dit op te lossen.';
$_lang['configcheck_images'] = 'Afbeeldingen map is niet schrijfbaar';
$_lang['configcheck_images_msg'] = 'De afbeeldingen map is niet schrijfbaar of bestaat niet. Dit betekent dat Afbeeldingenbeheer functionaliteiten in de editor niet werken!';
$_lang['configcheck_installer'] = 'Installatie is nog aanwezig';
$_lang['configcheck_installer_msg'] = 'De setup/ map bevat het installatieprogramma voor MODX. Stelt u zich eens voor wat er kan gebeuren als een kwaadaardige persoon deze map vindt en de installatie uitvoert! Ze zullen waarschijnlijk niet te ver gaan, omdat ze wat gebruikersinformatie voor de database moeten invoeren, maar het is nog steeds het beste om deze map van uw server te verwijderen. Het bevindt zich op: [[+path]]';
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

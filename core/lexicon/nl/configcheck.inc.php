<?php
/**
 * Config Check Dutch lexicon topic
 *
 * @language nl
 * @package modx
 * @subpackage lexicon
 *
 * @author Bert Oost at OostDesign.nl <bert@oostdesign.nl>
 */
$_lang['configcheck_admin'] = 'Neem contact op met de beheerder en waarschuw hem over deze melding!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'allow_tags_in_post Context Setting is ingeschakeld buiten de `mgr` context';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'De allow_tags_in_post Context Setting is in deze installatie ingeschakeld buiten de "mgr" context. Tenzij de gebruikers van de website specifiek MODX tags, genummerde entities of HTML script tags via POST naar een of meerdere formulieren op de site moeten kunnen sturen is het sterk aan te raden deze instelling te deactiveren. Over het algemeen moet alleen de "mgr" context deze instelling gebruiken.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'allow_tags_in_post System Setting Ingeschakeld';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'De allow_tags_in_post System Setting is in deze installatie ingeschakeld. Tenzij de gebruikers van de website specifiek MODX tags, genummerde entities of HTML script tags via POST naar een of meerdere formulieren op de site moeten kunnen sturen is het sterk aan te raden deze instelling te deactiveren. Indien deze instelling nodig is, kan dit het beste op context niveau worden ingeschakeld met Context Settings.';
$_lang['configcheck_cache'] = 'cache map is niet schrijfbaar';
$_lang['configcheck_cache_msg'] = 'MODX kan niet schrijven naar de cache map. MODX zal nog steeds functioneren als verwacht maar caching zal niet werken. Om dit op te lossen maak de /cache/ map schrijfbaar.';
$_lang['configcheck_configinc'] = 'Configuratiebestand nog steeds schrijfbaar!';
$_lang['configcheck_configinc_msg'] = 'De website is kwetsbaar voor hackers welke veel schade aan jouw website kunnen aanrichten. Stel jouw configuratiebestand in op alleen-lezen! Indien je niet de beheerder bent, neem dan contact met de beheerder op en waarschuw hem over deze melding. Het bestand kan worden gevonden in [[+path]]';
$_lang['configcheck_default_msg'] = 'Een onverwachte fout is opgetreden. Dit is toch wel een beetje apart.';
$_lang['configcheck_errorpage_unavailable'] = 'De website\'s foutpagina is niet beschikbaar.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Dit betekent dat jouw foutpagina niet bereikbaar is voor bezoekers of deze bestaat niet. Dit kan leiden tot een oneindige loop en veel fouten in logbestanden. Zorg ervoor dat de foutpagina publiekelijk toegankelijk is.';
$_lang['configcheck_errorpage_unpublished'] = 'De site\'s foutpagina is niet gepubliceerd of bestaat niet.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Dit betekent dat jouw foutpagina niet bereikbaar is voor bezoekers. Publiceer de pagina of controleer of het is gekoppeld aan een bestaand document in jouw site structuur in het Systeem > Systeeminstellingen menu.';
$_lang['configcheck_images'] = 'Afbeeldingen map is niet schrijfbaar';
$_lang['configcheck_images_msg'] = 'De afbeeldingen map is niet schrijfbaar of bestaat niet. Dit betekent dat Afbeeldingenbeheer functionaliteiten in de editor niet werken!';
$_lang['configcheck_installer'] = 'Installatie is nog aanwezig';
$_lang['configcheck_installer_msg'] = 'De setup/ map bevat de installatie voor MODX. Stel je eens voor wat er kan gebeuren als een kwaadaardig persoon deze map vind en de installatie opnieuw doorloopt! Hij zal waarschijnlijk niet ver komen, omdat hij gebruikersinformatie voor de database in moet vullen, maar het is nog altijd beter om deze map te verwijderen van de server.';
$_lang['configcheck_lang_difference'] = 'Er is een onjuist aantal entries gevonden in het taalbestand';
$_lang['configcheck_lang_difference_msg'] = 'Het huidige geselecteerd taalbestand heeft een verschillend aantal entries dan de standaard taal. Waar dit niet direct een probleem is, kan dit betekenen dat dit bestand geupdate moet worden.';
$_lang['configcheck_notok'] = 'E&eacute;n of meerdere configuratiedetails zijn niet succesvol: ';
$_lang['configcheck_ok'] = 'Controle geeft OKE - Geen fouten te melden.';
$_lang['configcheck_register_globals'] = 'register_globals staat AAN in jouw php.ini bestand';
$_lang['configcheck_register_globals_msg'] = 'Deze configuratie maakt jouw website veel vatbaarder voor Cross Site Scripting (XSS) aanvallen. Je zult contact op moeten nemen met jouw provider om te bespreken wat je kunt doen om deze instelling uit te schakelen.';
$_lang['configcheck_title'] = 'Configuratiecontrole';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Jouw site\'s Geen-toegang pagina is niet gepubliceerd of bestaat niet.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Dit betekent dat jouw geen-toegang pagina niet bereikbaar is voor normale bezoekers of niet bestaat. Dit kan leiden tot een recursieve lus en er zullen veel errors door verschijnen op jouw site. Controleer dat geen normale gebruikersgroepen aan deze pagina gekoppeld zijn.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'De in de site configuratie instellingen gedefinieerde geen-toegangs pagina is niet gepubliceerd.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Dit betekent dat jouw geen-toegang pagina is onbereikbaar voor bezoekers. Publiceer de pagina of controleer of het is gekoppeld aan een bestaand document in jouw site structuur in het Systeem > Systeeminstellingen menu.';
$_lang['configcheck_warning'] = 'Configuratiewaarschuwing:';
$_lang['configcheck_what'] = 'Wat betekent dit?';

<?php
/**
 * Config Check Swedish lexicon topic
 *
 * @language sv
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Kontakta en systemadministratör och varna om detta meddelande!';
$_lang['configcheck_cache'] = 'cache-katalogen inte skrivbar';
$_lang['configcheck_cache_msg'] = 'MODX kan inte skriva till cache-katalogen. MODX kommer fortfarande att fungera som väntat, men inga dokument kommer att cachas. För att rätta till det här, gör katalogen /_cache/ skrivbar.';
$_lang['configcheck_configinc'] = 'Konfigurationsfilen är fortfarande skrivbar!';
$_lang['configcheck_configinc_msg'] = 'Din webbplats är sårbar för hackers som kan göra mycket skada. Se till att din konfigurationsfil blir enbart läsbar. Om du inte är webbplatsens administratör bör du kontakta denne och berätta om det här meddelandet. Konfigurationsfilen finns här: [[+path]]';
$_lang['configcheck_default_msg'] = 'En ospecificerad varning hittades, vilket är konstigt.';
$_lang['configcheck_errorpage_unavailable'] = 'Felsidan för din webbplats är inte tillgänglig.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Detta betyder att din felsida inte är tillgänglig för vanliga användare eller att den inte existerar. Det här kan leda till att ett tillstånd med upprepande loopar skapas och ger upphov till att många fel rapporteras i webbplatsens loggar. Kontrollera att inga webbanvändargrupper är anslutna till sidan.';
$_lang['configcheck_errorpage_unpublished'] = 'Felsidan för din webbplats är inte publicerad eller existerar inte.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Detta betyder att din felsida inte är tillgänglig för allmänheten. Publicera sidan eller kontrollera under System &gt; Systeminställningar att den refererade sidan är ett existerande dokument i webbplatsens dokumentträd.';
$_lang['configcheck_images'] = 'Bildkatalogen är inte skrivbar';
$_lang['configcheck_images_msg'] = 'Bildkatalogen är inte skrivbar eller finns inte. Detta betyder att bildhanteringsfunktionerna i editorn inte kommer att fungera!';
$_lang['configcheck_installer'] = 'Installationsprogrammet är fortfarande kvar';
$_lang['configcheck_installer_msg'] = 'Katalogen setup/ innehåller installationsprogrammet för MODX. Tänk vad som kan hända om en elak människa hittar katalogen och kör installationen! Hen kommer förhoppningsvis inte så långt eftersom databasen kräver inloggningsuppgifter, men det är ändå bäst att ta bort katalogen från servern. Du hittar den här: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Fel antal fraser i språkfilen';
$_lang['configcheck_lang_difference_msg'] = 'Språket som för närvarande är valt har ett annat antal fraser än standardspråket. Detta behöver inte vara ett problem, men kan betyda att språkfilen behöver uppdateras.';
$_lang['configcheck_notok'] = 'En eller flera konfigurationsdetaljer är inte korrekta: ';
$_lang['configcheck_ok'] = 'Kontrollen utförd OK - inga varningar att rapportera.';
$_lang['configcheck_register_globals'] = 'register_globals är satt till ON i din php.ini konfigurationsfil';
$_lang['configcheck_register_globals_msg'] = 'Denna konfiguration gör din webbplats betydligt mer sårbar för så kallade serveröverskridande scriptattacker (Cross Site Scripting eller XSS). Du bör ta kontakt med din webbhost och ta reda på vad du kan göra för att stänga av den här inställningen.';
$_lang['configcheck_title'] = 'Konfigurationskontroll';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'Din webbplats otillåten-sida är inte publicerad eller existerar inte.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Detta betyder att din otillåten-sida inte är tillgänglig eller att den inte existerar. Det här kan leda till att ett tillstånd med upprepande loopar skapas och ger upphov till att många fel rapporteras i webbplatsens loggar. Kontrollera att inga webbanvändargrupper är anslutna till sidan.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'Den otillåten-sida som angetts i inställningarna är inte publicerad.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Detta betyder att din otillåten-sida är oåtkomlig för allmänheten. Publicera sidan eller kontrollera under System &gt; Systeminställningar att den refererade sidan är ett existerande dokument i webbplatsens dokumentträd.';
$_lang['configcheck_warning'] = 'Konfigurationsvarning:';
$_lang['configcheck_what'] = 'Vad betyder det här?';

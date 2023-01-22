<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Åtkomst';
$_lang['filter_by_category'] = 'Filtrera efter kategori...';
$_lang['rank'] = 'Rang';
$_lang['template'] = 'Mall';
$_lang['template_assignedtv_tab'] = 'Tilldelade mallvariabler';
$_lang['template_category_desc'] = 'Använd för att gruppera mallar i elementträdet.';
$_lang['template_code'] = 'Mall-kod (html)';
$_lang['template_delete_confirm'] = 'Är du säker på att du vill ta bort denna mall?';
$_lang['template_description_desc'] = 'Användningsinformation för denna mall som visas i sökresultat och som ett verktygstips i elementträdet.';
$_lang['template_duplicate_confirm'] = 'Är du säker på att du vill duplicera denna mall?';
$_lang['template_edit_tab'] = 'Redigera mall';
$_lang['template_empty'] = '(tom)';
$_lang['template_err_default_template'] = 'Denna mall är angiven som standardmall. Ange en ny standardmall i MODX inställningar innan du tar bort denna mall.<br />';
$_lang['template_err_delete'] = 'Ett fel inträffade när mallen skulle tas bort.';
$_lang['template_err_duplicate'] = 'Ett fel inträffade när mallen skulle dupliceras.';
$_lang['template_err_ae'] = 'Det finns redan en mall med namnet "[[+name]]".';
$_lang['template_err_in_use'] = 'Denna mall används. Ange en ny mall för de dokument som använder mallen. Dokument som använder mallen:<br />';
$_lang['template_err_invalid_name'] = 'Mallnamnet är ogiltigt.';
$_lang['template_err_locked'] = 'Mallen är låst för redigering.';
$_lang['template_err_nf'] = 'Mallen kunde inte hittas!';
$_lang['template_err_ns'] = 'Ingen mall angiven.';
$_lang['template_err_ns_name'] = 'Ange ett namn på mallen.';
$_lang['template_err_remove'] = 'Ett fel inträffade när mallen skulle tas bort.';
$_lang['template_err_save'] = 'Ett fel inträffade när mallen skulle sparas.';
$_lang['template_icon'] = 'Klass för ikon i hanteraren';
$_lang['template_icon_desc'] = 'En CSS-klass för att tilldela en ikon (visas i dokumentträden) till alla resurser som använder denna mall. Klasser för Font Awesome Free 5 som “fa-home” kan användas.';
$_lang['template_lock'] = 'Lås mall för redigering';
$_lang['template_lock_desc'] = 'Endast användare med “edit_locked”-behörighet kan redigera denna mall.';
$_lang['template_locked_message'] = 'Denna mall är låst.';
$_lang['template_management_msg'] = 'Här kan du skapa en ny mall eller välja en redan befintlig för redigering.';
$_lang['template_name_desc'] = 'Mallens namn.';
$_lang['template_new'] = 'Skapa mall';
$_lang['template_no_tv'] = 'Inga mallvariabler har tilldelats den här mallen än.';
$_lang['template_preview'] = 'Förhandsgranskningsbild';
$_lang['template_preview_desc'] = 'Används för att förhandsgranska layouten för denna mall när du skapar en ny resurs. (Minsta storlek: 335 x 236)';
$_lang['template_preview_source'] = 'Förhandsgranskningsbildens mediakälla';
$_lang['template_preview_source_desc'] = 'Anger bassökvägen för denna mallens förhandsgranskningsbild till den som anges i den valda mediakällan. Välj “Ingen” om du anger en absolut eller annan anpassad sökväg till filen.';
$_lang['template_properties'] = 'Standardegenskaper';
$_lang['template_reset_all'] = 'Återställ alla sidor så de använder standardmallen';
$_lang['template_reset_specific'] = 'Återställ endast "%s" sidor';
$_lang['template_tab_general_desc'] = 'Här kan du ange grundläggande attribut för denna <em>mall</em> samt dess innehåll. Innehållet måste vara HTML, antingen placerat i fältet <em>Mallkod</em> nedan eller i en statisk extern fil, och kan inkludera MODX-taggar. Observera att ändrade eller nya mallar inte kommer att synas på din webbplats cachade sidor förrän cachen töms. Du kan dock använda förhandsgranskningsfunktionen på en sida för att se mallen i aktion.';
$_lang['template_tv_edit'] = 'Redigera mallvariablernas sorteringsordning';
$_lang['template_tv_msg'] = 'Mallvariablerna som tilldelats den här mallen visas nedan.';
$_lang['templates'] = 'Mallar';
$_lang['tvt_err_nf'] = 'Mallvariabeln har inte tillgång till den angivna mallen.';
$_lang['tvt_err_remove'] = 'Ett fel inträffade när mallvariabeln skulle tas bort från mallen.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];

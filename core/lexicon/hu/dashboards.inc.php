<?php
/**
 * English language strings for Dashboards
 *
 * @package modx
 * @subpackage lexicon
 * @language en
 */
$_lang['dashboard'] = 'Vezérlőpult';
$_lang['dashboard_add'] = 'Vezérlőpult hozzáadása';
$_lang['dashboard_create'] = 'Vezérlőpult létrehozása';
$_lang['dashboard_desc_name'] = 'Vezérlőpult elnevezése.';
$_lang['dashboard_desc_description'] = 'Vezérlőpult rövid leírása.';
$_lang['dashboard_desc_hide_trees'] = 'Checking this will hide the left-hand trees when this Dashboard is rendered on the welcome page.';
$_lang['dashboard_hide_trees'] = 'Bal oldali fák elrejtése';
$_lang['dashboard_duplicate'] = 'Vezérlőpult kettőzése';
$_lang['dashboard_remove'] = 'Vezérlőpult törlése';
$_lang['dashboard_remove_confirm'] = 'Biztosan eltávolítja ezt a vezérlőelemet?';
$_lang['dashboard_remove_multiple'] = 'Vezérlőpultok eltávolítása';
$_lang['dashboard_remove_multiple_confirm'] = 'Biztosan eltávolítja a kijelölt vezérlőelemeket?';
$_lang['dashboard_update'] = 'Vezérlőpult frissítése';
$_lang['dashboard_err_ae_name'] = 'Már létezik "[[+name]]" nevű vezérlőpult! Próbálkozzon másik névvel.';
$_lang['dashboard_err_duplicate'] = 'Hiba történt a vezérlőpult kettőzésekor.';
$_lang['dashboard_err_nf'] = 'Vezérlőpult nem található.';
$_lang['dashboard_err_ns'] = 'Vezérlőpult nincs megadva.';
$_lang['dashboard_err_ns_name'] = 'Kérjük, adja meg a vezérlőelem nevét.';
$_lang['dashboard_err_remove'] = 'Hiba történt a vezérlőpult eltávolítása közben.';
$_lang['dashboard_err_remove_default'] = 'Nem távolíthatja el az alapértelmezett vezérlőpultot!';
$_lang['dashboard_err_save'] = 'Hiba történt a vezérlőpult mentése közben.';
$_lang['dashboard_usergroup_add'] = 'Vezérlőpult hozzáadása felhasználói csoporthoz';
$_lang['dashboard_usergroup_remove'] = 'Vezérlőpult eltávolítása felhasználói csoportból';
$_lang['dashboard_usergroup_remove_confirm'] = 'Are you sure you want to revert this User Group to using the default Dashboard?';
$_lang['dashboard_usergroups.intro_msg'] = 'Here is a list of all the User Groups using this Dashboard.';
$_lang['dashboard_widget_err_placed'] = 'This widget is already placed in this Dashboard!';
$_lang['dashboard_widgets.intro_msg'] = 'Here you can add, manage, and remove Widgets from this Dashboard. You can also drag and drop the rows in the grid to rearrange them.';
$_lang['dashboards'] = 'Vezérlőpultok';
$_lang['dashboards.intro_msg'] = 'Itt kezelheti az ennek a MODX kezelőnek elérhető összes vezérlőpultot.';
$_lang['rank'] = 'Rangsor';
$_lang['user_group_filter'] = 'Felhasználói csoport szerint';
$_lang['widget'] = 'Vezérlőelem';
$_lang['widget_content'] = 'Vezérlőelem tartalma';
$_lang['widget_create'] = 'Új vezérlőelem létrehozása';
$_lang['widget_err_ae_name'] = 'Már létezik "[[+name]]" nevű vezérlőelem! Próbálkozzon másik névvel.';
$_lang['widget_err_nf'] = 'Vezérlőelem nem található!';
$_lang['widget_err_ns'] = 'Vezérlőelem nincs megadva!';
$_lang['widget_err_ns_name'] = 'Kérjük, adja meg a vezérlőelem nevét.';
$_lang['widget_err_remove'] = 'Hiba történt a vezérlőelem eltávolítása közben.';
$_lang['widget_err_save'] = 'Hiba történt a vezérlőelem mentése közben.';
$_lang['widget_file'] = 'Állomány';
$_lang['widget_dashboards.intro_msg'] = 'Below is a list of all the Dashboards that this Widget has been placed on.';
$_lang['widget_dashboard_remove'] = 'Vezérlőelem eltávolítása az irányítópultról';
$_lang['widget_description_desc'] = 'A description, or Lexicon Entry key, of the Widget and what it does.';
$_lang['widget_html'] = 'HTML';
$_lang['widget_lexicon_desc'] = 'The Lexicon Topic to load with this Widget. Useful for providing translations for the name and description, as well as any text in the widget.';
$_lang['widget_name_desc'] = 'The name, or Lexicon Entry key, of the Widget.';
$_lang['widget_new'] = 'Új vezérlőelem';
$_lang['widget_remove'] = 'Vezérlőelem törlése';
$_lang['widget_remove_confirm'] = 'Biztosan eltávolítja ezt a vezérlőpulti vezérlőelemet? Ez a művelet nem vonható vissza, és eltávolítja a vezérlőelemet minden vezérlőpultból.';
$_lang['widget_remove_multiple'] = 'Több vezérlőelem törlése';
$_lang['widget_remove_multiple_confirm'] = 'Biztosan eltávolítja ezeket a vezérlőpulti vezérlőelemeket? Ez a művelet nem vonható vissza, és eltávolítja a vezérlőelemeket minden hozzárendelt vezérlőpultból.';
$_lang['widget_namespace'] = 'Névtér';
$_lang['widget_namespace_desc'] = 'The Namespace that this widget will be loaded into. Useful for custom paths.';
$_lang['widget_php'] = 'Beágyazott PHP vezérlőelem';
$_lang['widget_place'] = 'Vezérlőelem elhelyezése';
$_lang['widget_size'] = 'Méret';
$_lang['widget_size_desc'] = 'The size of the widget. Can either be a half-screen wide ("Half"), the width of the screen ("Full"), or a full screen width and two rows ("Double").';
$_lang['widget_size_double'] = 'Kétszeres';
$_lang['widget_size_full'] = 'Teljes';
$_lang['widget_size_half'] = 'Fél';
$_lang['widget_snippet'] = 'Kódrészlet';
$_lang['widget_type'] = 'Vezérlőelem típusa';
$_lang['widget_type_desc'] = 'The type of widget this is. "Snippet" widgets are MODX Snippets that are run and return their output. "HTML" widgets are just straight HTML. "File" widgets are loaded directly from files, which can either return their output or the name of the modDashboardWidgetClass-extended class to load. "Inline PHP" Widgets are widgets that are straight PHP in the widget content, similar to a Snippet.';
$_lang['widget_unplace'] = 'Vezérlőelem eltávolítása az irányítópultról';
$_lang['widget_update'] = 'Vezérlőelem frissítése';
$_lang['widgets'] = 'Vezérlőelemek';
$_lang['widgets.intro_msg'] = 'Below is a list of all the installed Dashboard Widgets you have.';

$_lang['w_configcheck'] = 'Konfiguráció ellenőrzése';
$_lang['w_configcheck_desc'] = 'Displays a configuration check that ensures your MODX install is secure.';
$_lang['w_newsfeed'] = 'MODX hírfolyam';
$_lang['w_newsfeed_desc'] = 'Megjeleníti a MODX hírfolyamot';
$_lang['w_recentlyeditedresources'] = 'Nemrégiben módosított erőforrások';
$_lang['w_recentlyeditedresources_desc'] = 'Shows a list of the most recently edited resources by the user.';
$_lang['w_securityfeed'] = 'MODX biztonsági hírfolyam';
$_lang['w_securityfeed_desc'] = 'Megjeleníti a MODX biztonsági hírfolyamot';
$_lang['w_whosonline'] = 'Ki online';
$_lang['w_whosonline_desc'] = 'Online felhasználók felsorolása.';
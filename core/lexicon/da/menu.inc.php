<?php
/**
 * Menu English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['action'] = 'Handling';
$_lang['action_desc'] = 'Controller-stien der skal bruges for dette menupunkt. Stien til controller\'en bliver bygget ved at præfikse værdien med namespace-stien, "controllers" og manager-tema. (Fx: user/update i core namespace\'et går til [core_namespace_path]controllers/ [mgr_theme]/user/update.class.php)';
$_lang['description_desc'] = 'Tekst eller leksikonnøgle, der skal bruges til visning af beskrivelsen for denne side i menuen.';
$_lang['handler'] = 'Handler';
$_lang['handler_desc'] = '(Valgfri) Hvis ufyldt, vil feltet handling ikke blive brugt, men i stedet vil dette JavaScript blive udført, når du klikker på menupunktet.';
$_lang['icon'] = 'Ikon';
$_lang['icon_desc'] = 'Et valgfrit ikon eller HTML.';
$_lang['lexicon_key'] = 'Leksikonnøgle';
$_lang['lexicon_key_desc'] = 'Tekst eller leksikonnøgle, der skal bruges til visning af titlen for denne side i menuen.';
$_lang['menu_confirm_remove'] = 'Are you sure you want to delete the menu: "[[+menu]]"?<br />NOTE: Any nested menus will also be deleted!';
$_lang['menu_err_ae'] = 'Der findes allerede en menu med dette navn. Skriv venligst en anden tekst.';
$_lang['menu_err_nf'] = 'Menuen blev ikke fundet!';
$_lang['menu_err_ns'] = 'Ingen menu angivet!';
$_lang['menu_err_remove'] = 'An error occurred while trying to delete the menu.';
$_lang['menu_err_save'] = 'Der opstod en fejl under forsøget på at gemme menuen.';
$_lang['menu_parent'] = 'Overordnet menu';
$_lang['menu_parent_err_ns'] = 'Overordnet menu ikke angivet!';
$_lang['menu_parent_err_nf'] = 'Den overordnede menu blev ikke fundet!';
$_lang['menu_top'] = 'Main Menu';
$_lang['menus'] = 'Menuer';
$_lang['namespace'] = 'Namespace';
$_lang['namespace_desc'] = 'Det namespace, som dette menupunkt er baseret på. Dette vil bestemme stien for den controller, der bliver brugt.';
$_lang['parameters'] = 'Parametre';
$_lang['parameters_desc'] = 'De request-parametre der skal føjes til URL-adressen, når du klikker på denne menu. (Fx: &expire=1)';
$_lang['permissions'] = 'Rettighed';
$_lang['permissions_desc'] = 'En rettighedsnøgle der kræves for at indlæse dette menupunkt.';
$_lang['topmenu'] = 'Main Menu';
$_lang['topmenu_desc'] = 'This allows you to associate actions with menu items in the main menu bar of the MODX manager. Simply place menus where you would like them in their respective positions.';

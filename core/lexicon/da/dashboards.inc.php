<?php
/**
 * English language strings for Dashboards
 *
 * @package modx
 * @subpackage lexicon
 * @language en
 */
$_lang['dashboard'] = 'Betjeningspanel';
$_lang['dashboard_desc_name'] = 'Navnet på betjeningspanelet.';
$_lang['dashboard_desc_description'] = 'En kort beskrivelse af betjeningspanelet.';
$_lang['dashboard_desc_hide_trees'] = 'Kryds her vil skjule navigationstræer til venstre når dette betjeningspanel bliver vist på velkomstsiden.';
$_lang['dashboard_hide_trees'] = 'Skjul venstre-navigationstræer';
$_lang['dashboard_desc_customizable'] = 'Allow users to customize this dashboard for their accounts: create, delete and change position or size of widgets.';
$_lang['dashboard_customizable'] = 'Customizable';
$_lang['dashboard_remove_confirm'] = 'Are you sure you want to delete this Dashboard?';
$_lang['dashboard_remove_multiple_confirm'] = 'Are you sure you want to delete the selected Dashboards?';
$_lang['dashboard_err_ae_name'] = 'Et betjeningspanel med navnet "[[+name]]" findes allerede! Prøv venligst et andet navn.';
$_lang['dashboard_err_duplicate'] = 'Der opstod en fejl under forsøget på at kopiere betjeningspanelet.';
$_lang['dashboard_err_nf'] = 'Betjeningspanelet blev ikke fundet.';
$_lang['dashboard_err_ns'] = 'Betjeningspanelet ikke angivet.';
$_lang['dashboard_err_ns_name'] = 'Angiv venligst et navn til widget\'en.';
$_lang['dashboard_err_remove'] = 'An error occurred while trying to delete the Dashboard.';
$_lang['dashboard_err_remove_default'] = 'You cannot delete the default Dashboard!';
$_lang['dashboard_err_save'] = 'Der skete en fejl under forsøget på at gemme betjeningspanelet.';
$_lang['dashboard_usergroup_add'] = 'Tildel betjeningspanel til brugergruppen';
$_lang['dashboard_usergroup_remove'] = 'Delete Dashboard from User Group';
$_lang['dashboard_usergroup_remove_confirm'] = 'Er du sikker på du vil gendanne denne brugergruppe til at bruge standard betjeningspanelet?';
$_lang['dashboard_usergroups.intro_msg'] = 'Her er en liste over de brugergrupper som benytter dette betjeningspanel.';
$_lang['dashboard_widget_err_placed'] = 'Denne widget er allerede placeret i dette betjeningspanel!';
$_lang['dashboard_widgets.intro_msg'] = 'Manage widgets in this dashboard. You can also drag and drop rows in the grid to rearrange them.<br><br>Please note: if a dashboard is "customizable", this settings will be applied only for the first load for every user. From here they will be able to create, delete and change the position or size of their widgets. User access to widgets can be limited by applying permissions.';
$_lang['dashboards'] = 'Betjeningspaneler';
$_lang['dashboards.intro_msg'] = 'Her kan du administrere alle tilgængelige betjeningspaneler i denne MODX manager.';
$_lang['rank'] = 'Rang';
$_lang['user_group_filter'] = 'Efter brugergruppe';
$_lang['widget'] = 'Widget';
$_lang['widget_content'] = 'Widget indhold';
$_lang['widget_err_ae_name'] = 'Der findes allerede en widget med navnet [[+name]]. Prøv venligst et andet navn.';
$_lang['widget_err_nf'] = 'Widget blev ikke fundet!';
$_lang['widget_err_ns'] = 'Widget er ikke angivet!';
$_lang['widget_err_ns_name'] = 'Angiv venligst et navn til widget\'en.';
$_lang['widget_err_remove'] = 'An error occurred while trying to delete the Widget.';
$_lang['widget_err_save'] = 'Der opstod en fejl under forsøget på at gemme widget\'en.';
$_lang['widget_file'] = 'Fil';
$_lang['widget_dashboards.intro_msg'] = 'Nedenfor er en liste over alle de betjeningspaneler som denne widget er indsat i.';
$_lang['widget_dashboard_remove'] = 'Delete Widget From Dashboard';
$_lang['widget_description_desc'] = 'En beskrivelse, eller leksikonnøgle, af hvad denne widget er og hvad den gør.';
$_lang['widget_html'] = 'HTML';
$_lang['widget_lexicon_desc'] = 'Leksikonemnet der skal indlæses med denne widget. Det er nyttigt til at levere oversættelser af navnet og beskrivelsen, såvel som tekst brugt i widget\'en.';
$_lang['widget_permission_desc'] = 'This permission will be required to add this widget to a user dashboard.';
$_lang['widget_permission'] = 'Rettighed';
$_lang['widget_name_desc'] = 'Navnet, eller leksikonnøglen, på widget.';
$_lang['widget_add'] = 'Add Widget';
$_lang['widget_add_desc'] = 'Please select a Widget to add to your Dashboard.';
$_lang['widget_add_success'] = 'The widget was successfully added to your Dashboard. It will be loaded after closing this window.';
$_lang['widget_remove_confirm'] = 'Are you sure you want to delete this Dashboard Widget? This is permanent, and will delete the Widget from all Dashboards.';
$_lang['widget_remove_multiple_confirm'] = 'Are you sure you want to delete these Dashboard Widgets? This is permanent, and will delete the Widgets from all their assigned Dashboards.';
$_lang['widget_namespace'] = 'Namespace';
$_lang['widget_namespace_desc'] = 'Det namespace som denne widget vil anvende. Nyttigt til brugerdefinerede stier.';
$_lang['widget_php'] = 'Inline PHP-widget';
$_lang['widget_place'] = 'Placer widget';
$_lang['widget_size'] = 'Størrelse';
$_lang['widget_size_desc'] = 'The size of the widget. Can either be a from "quarter" to "double".';
$_lang['widget_size_double'] = 'Double Size';
$_lang['widget_size_full'] = 'Full Size';
$_lang['widget_size_three_quarters'] = 'Three Quarters';
$_lang['widget_size_two_third'] = 'Two Third';
$_lang['widget_size_half'] = 'Halv';
$_lang['widget_size_one_third'] = 'One Third';
$_lang['widget_size_quarter'] = 'Quarter';
$_lang['widget_snippet'] = 'Snippet';
$_lang['widget_type'] = 'Widgettype';
$_lang['widget_type_desc'] = 'Den type af widget det er. "Snippet" widgets er MODX Snippets der køres og returnerer deres output. "HTML" widgets er almindelig HTML. "Fil" widgets indlæses direkte fra filer, som enten kan returnere deres output eller navnet på den klasse, som implementerer modDashboardWidgetClass, der skal indlæses. "Inline PHP" Widgets er widgets med ren PHP indhold, svarende til en snippet.';
$_lang['widget_unplace'] = 'Delete Widget from Dashboard';
$_lang['widgets'] = 'Widgets';
$_lang['widgets.intro_msg'] = 'Nedenfor er en liste over alle installerede betjeningspanels widgets du har.';

$_lang['action_new_resource'] = 'New page';
$_lang['action_new_resource_desc'] = 'Create a new page for your website.';
$_lang['action_view_website'] = 'View website';
$_lang['action_view_website_desc'] = 'Open your website in a new window.';
$_lang['action_advanced_search'] = 'Advanced search';
$_lang['action_advanced_search_desc'] = 'Advanced search through your website.';
$_lang['action_manage_users'] = 'Manage users';
$_lang['action_manage_users_desc'] = 'Manage all your website and manager users.';

$_lang['w_buttons'] = 'Buttons';
$_lang['w_buttons_desc'] = 'Displays a set of buttons from array specified in properties.';
$_lang['w_updates'] = 'Updates';
$_lang['w_updates_desc'] = 'Checks for available updates for core and extras.';
$_lang['w_configcheck'] = 'Konfigurationskontrol';
$_lang['w_configcheck_desc'] = 'Viser en konfigurationskontrol der sikrer din MODX-installation er sikker.';
$_lang['w_newsfeed'] = 'MODX nyhedsfeed';
$_lang['w_newsfeed_desc'] = 'Viser MODX nyhedsfeed';
$_lang['w_recentlyeditedresources'] = 'Senest redigerede ressourcer';
$_lang['w_recentlyeditedresources_desc'] = 'Viser en liste over de senest redigerede ressourcer af brugeren.';
$_lang['w_securityfeed'] = 'MODX sikkerhedsfeed';
$_lang['w_securityfeed_desc'] = 'Viser MODX sikkerhedsfeed';
$_lang['w_whosonline'] = 'Hvem er online';
$_lang['w_whosonline_desc'] = 'Viser en liste over online brugere.';
$_lang['w_view_all'] = 'View all';
$_lang['w_no_data'] = 'Ingen data at vise';
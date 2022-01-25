<?php
/**
 * English language strings for Dashboards
 *
 * @package modx
 * @subpackage lexicon
 * @language en
 */
$_lang['dashboard'] = 'Nástěnka';
$_lang['dashboard_desc_name'] = 'Název nástěnky.';
$_lang['dashboard_desc_description'] = 'Krátký popis nástěnky.';
$_lang['dashboard_desc_hide_trees'] = 'Zaškrnutím tohoto pole dojde ke schování levého stromového menu pokud je tato nástěnka zobrazena na úvodní stránce správce obsahu.';
$_lang['dashboard_hide_trees'] = 'Skrýt levé stromové menu';
$_lang['dashboard_desc_customizable'] = 'Allow users to customize this dashboard for their accounts: create, delete and change position or size of widgets.';
$_lang['dashboard_customizable'] = 'Přizpůsobitelný';
$_lang['dashboard_remove_confirm'] = 'Are you sure you want to delete this Dashboard?';
$_lang['dashboard_remove_multiple_confirm'] = 'Are you sure you want to delete the selected Dashboards?';
$_lang['dashboard_err_ae_name'] = 'Nástěnka s názvem "[[+name]]" již existuje! Zadejte jiný název.';
$_lang['dashboard_err_duplicate'] = 'Nastala chyba při kopírování nástěnky.';
$_lang['dashboard_err_nf'] = 'Nástěnka nenalezena.';
$_lang['dashboard_err_ns'] = 'Nástěnka neurčena.';
$_lang['dashboard_err_ns_name'] = 'Zadejte název pro tento widget.';
$_lang['dashboard_err_remove'] = 'An error occurred while trying to delete the Dashboard.';
$_lang['dashboard_err_remove_default'] = 'You cannot delete the default Dashboard!';
$_lang['dashboard_err_save'] = 'Nastala chyba při ukládání nástěnky.';
$_lang['dashboard_usergroup_add'] = 'Přiřadit nástěnku uživatelské skupině';
$_lang['dashboard_usergroup_remove'] = 'Delete Dashboard from User Group';
$_lang['dashboard_usergroup_remove_confirm'] = 'Opravdu chcete této skupině nastavit výchozí nástěnku?';
$_lang['dashboard_usergroups.intro_msg'] = 'Seznam všech uživatelských skupin používajících tuto nástěnku.';
$_lang['dashboard_widget_err_placed'] = 'Widget je již na této nástěnce umístěn!';
$_lang['dashboard_widgets.intro_msg'] = 'Manage widgets in this dashboard. You can also drag and drop rows in the grid to rearrange them.<br><br>Please note: if a dashboard is "customizable", this settings will be applied only for the first load for every user. From here they will be able to create, delete and change the position or size of their widgets. User access to widgets can be limited by applying permissions.';
$_lang['dashboards'] = 'Nástěnky';
$_lang['dashboards.intro_msg'] = 'Správa všech dostupných nástěnek pro MODX správce obsahu.';
$_lang['rank'] = 'Pořadí';
$_lang['user_group_filter'] = 'Dle uživatelské skupiny';
$_lang['widget'] = 'Widget';
$_lang['widget_content'] = 'Obsah widgetu';
$_lang['widget_err_ae_name'] = 'Widget s názvem "[[+name]]" již existuje! Zadejte jiný název.';
$_lang['widget_err_nf'] = 'Widget nenalezen!';
$_lang['widget_err_ns'] = 'Widget neurčen!';
$_lang['widget_err_ns_name'] = 'Zadejte název pro tento widget.';
$_lang['widget_err_remove'] = 'An error occurred while trying to delete the Widget.';
$_lang['widget_err_save'] = 'Nastala chyba při ukládání widgetu.';
$_lang['widget_file'] = 'Soubor';
$_lang['widget_dashboards.intro_msg'] = 'Seznam všech nástěnek, na kterých je tento widget umístěn.';
$_lang['widget_dashboard_remove'] = 'Delete Widget From Dashboard';
$_lang['widget_description_desc'] = 'Popis nebo klíč slovníku pro tento widget, který vyjadřuje co widget dělá.';
$_lang['widget_html'] = 'HTML';
$_lang['widget_lexicon_desc'] = 'Téma slovníku, které se má načíst pro tento widget. Užitečné pro překlady názvu, popisu a všech ostatních textů ve widgetu.';
$_lang['widget_permission_desc'] = 'This permission will be required to add this widget to a user dashboard.';
$_lang['widget_permission'] = 'Oprávnění';
$_lang['widget_name_desc'] = 'Název nebo klíč slovníku pro tento widget.';
$_lang['widget_add'] = 'Add Widget';
$_lang['widget_add_desc'] = 'Please select a Widget to add to your Dashboard.';
$_lang['widget_add_success'] = 'The widget was successfully added to your Dashboard. It will be loaded after closing this window.';
$_lang['widget_remove_confirm'] = 'Are you sure you want to delete this Dashboard Widget? This is permanent, and will delete the Widget from all Dashboards.';
$_lang['widget_remove_multiple_confirm'] = 'Are you sure you want to delete these Dashboard Widgets? This is permanent, and will delete the Widgets from all their assigned Dashboards.';
$_lang['widget_namespace'] = 'Jmenný prostor';
$_lang['widget_namespace_desc'] = 'Jmenný prostor, ve kterém bude tento widget načítán. Vhodné pro vlastní cesty.';
$_lang['widget_php'] = 'Vložený PHP widget';
$_lang['widget_place'] = 'Umístit widget';
$_lang['widget_size'] = 'Velikost';
$_lang['widget_size_desc'] = 'The size of the widget. Can either be a from "quarter" to "double".';
$_lang['widget_size_double'] = 'Dvojitá velikost';
$_lang['widget_size_full'] = 'Plná velikost';
$_lang['widget_size_three_quarters'] = 'Tři čtvrtiny';
$_lang['widget_size_two_third'] = 'Dvě třetiny';
$_lang['widget_size_half'] = 'Poloviční';
$_lang['widget_size_one_third'] = 'Jedna třetina';
$_lang['widget_size_quarter'] = 'Čtvrtina';
$_lang['widget_snippet'] = 'Snippet';
$_lang['widget_type'] = 'Typ widgetu';
$_lang['widget_type_desc'] = 'Typ tohoto widgetu. "Snippet" je MODX Snippet, který se spustí a vrátí nějaký výstup. "HTML" widgety vracejí pouze HTML. Widget "Soubor" je načítán přímo ze souboru, kde buď vrací přímo výstup nebo název třídy modDashboardWidgetClass-extended class pro další načtení. "Vložený PHP widget" je widget, který je psaný přímo PHP kódem podobně jako snippet.';
$_lang['widget_unplace'] = 'Delete Widget from Dashboard';
$_lang['widgets'] = 'Widgety';
$_lang['widgets.intro_msg'] = 'Seznam všech dostupných widgetů.';

$_lang['action_new_resource'] = 'Nová stránka';
$_lang['action_new_resource_desc'] = 'Create a new page for your website.';
$_lang['action_view_website'] = 'Zobrazit web';
$_lang['action_view_website_desc'] = 'Otevřete svůj web v novém okně.';
$_lang['action_advanced_search'] = 'Rozšířené hledání';
$_lang['action_advanced_search_desc'] = 'Advanced search through your website.';
$_lang['action_manage_users'] = 'Správa uživatelů';
$_lang['action_manage_users_desc'] = 'Manage all your website and manager users.';

$_lang['w_buttons'] = 'Buttons';
$_lang['w_buttons_desc'] = 'Displays a set of buttons from array specified in properties.';
$_lang['w_updates'] = 'Aktualizace';
$_lang['w_updates_desc'] = 'Checks for available updates for core and extras.';
$_lang['w_configcheck'] = 'Kontrola konfigurace';
$_lang['w_configcheck_desc'] = 'Zobrazení kontroly konfigurace pro ujištění se, že je Vaše instalace MODX bezpečná.';
$_lang['w_newsfeed'] = 'MODX Novinky';
$_lang['w_newsfeed_desc'] = 'Zobrazení MODX novinek';
$_lang['w_recentlyeditedresources'] = 'Naposledy upravované dokumenty';
$_lang['w_recentlyeditedresources_desc'] = 'Zobrazení seznamu naposledy upravovaných dokumentů a uživatelů.';
$_lang['w_securityfeed'] = 'MODX Bezpečnostní novinky';
$_lang['w_securityfeed_desc'] = 'Zobrazení novinek okolo bezpečnosti MODX';
$_lang['w_whosonline'] = 'Kdo je právě přihlášen';
$_lang['w_whosonline_desc'] = 'Seznam přihlášených uživatelů.';
$_lang['w_view_all'] = 'Zobrazit vše';
$_lang['w_no_data'] = 'Žádná data k zobrazení';
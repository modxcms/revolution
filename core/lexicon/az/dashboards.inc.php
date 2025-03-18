<?php
/**
 * English language strings for Dashboards
 *
 * @package modx
 * @subpackage lexicon
 * @language en
 */
$_lang['dashboard'] = 'Dashboard';
$_lang['dashboard_desc_name'] = 'The name of the Dashboard.';
$_lang['dashboard_desc_description'] = 'A short description of the Dashboard.';
$_lang['dashboard_desc_hide_trees'] = 'Checking this will hide the left-hand trees when this Dashboard is rendered on the welcome page.';
$_lang['dashboard_hide_trees'] = 'Hide Left-Hand Trees';
$_lang['dashboard_desc_customizable'] = 'Allow users to customize this dashboard for their accounts: create, delete and change position or size of widgets.';
$_lang['dashboard_customizable'] = 'Customizable';
$_lang['dashboard_remove_confirm'] = 'Are you sure you want to delete this Dashboard?';
$_lang['dashboard_remove_multiple_confirm'] = 'Are you sure you want to delete the selected Dashboards?';
$_lang['dashboard_err_ae_name'] = 'A dashboard with the name "[[+name]]" already exists! Please try another name.';
$_lang['dashboard_err_duplicate'] = 'An error occurred while trying to duplicate the dashboard.';
$_lang['dashboard_err_nf'] = 'Dashboard not found.';
$_lang['dashboard_err_ns'] = 'Dashboard not specified.';
$_lang['dashboard_err_ns_name'] = 'Please specify a name for the widget.';
$_lang['dashboard_err_remove'] = 'An error occurred while trying to delete the Dashboard.';
$_lang['dashboard_err_remove_default'] = 'You cannot delete the default Dashboard!';
$_lang['dashboard_err_save'] = 'An error occurred while trying to save the Dashboard.';
$_lang['dashboard_usergroup_add'] = 'Assign Dashboard to User Group';
$_lang['dashboard_usergroup_remove'] = 'Delete Dashboard from User Group';
$_lang['dashboard_usergroup_remove_confirm'] = 'Are you sure you want to revert this User Group to using the default Dashboard?';
$_lang['dashboard_usergroups.intro_msg'] = 'Here is a list of all the User Groups using this Dashboard.';
$_lang['dashboard_widget_err_placed'] = 'This widget is already placed in this Dashboard!';
$_lang['dashboard_widgets.intro_msg'] = 'Manage widgets in this dashboard. You can also drag and drop rows in the grid to rearrange them.<br><br>Please note: if a dashboard is "customizable", this settings will be applied only for the first load for every user. From here they will be able to create, delete and change the position or size of their widgets. User access to widgets can be limited by applying permissions.';
$_lang['dashboards'] = 'Dashboards';
$_lang['dashboards.intro_msg'] = 'Here you can manage all the available Dashboards for this MODX manager.';
$_lang['rank'] = 'Rank';
$_lang['user_group_filter'] = 'By User Group';
$_lang['widget'] = 'Widget';
$_lang['widget_content'] = 'Widget Content';
$_lang['widget_err_ae_name'] = 'A widget with the name "[[+name]]" already exists! Please try another name.';
$_lang['widget_err_nf'] = 'Widget not found!';
$_lang['widget_err_ns'] = 'Widget not specified!';
$_lang['widget_err_ns_name'] = 'Please specify a name for the widget.';
$_lang['widget_err_remove'] = 'An error occurred while trying to delete the Widget.';
$_lang['widget_err_save'] = 'An error occurred while trying to save the Widget.';
$_lang['widget_file'] = 'File';
$_lang['widget_dashboards.intro_msg'] = 'Below is a list of all the Dashboards that this Widget has been placed on.';
$_lang['widget_dashboard_remove'] = 'Delete Widget From Dashboard';
$_lang['widget_description_desc'] = 'A description, or Lexicon Entry key, of the Widget and what it does.';
$_lang['widget_html'] = 'HTML';
$_lang['widget_lexicon_desc'] = 'The Lexicon Topic to load with this Widget. Useful for providing translations for the name and description, as well as any text in the widget.';
$_lang['widget_permission_desc'] = 'This permission will be required to add this widget to a user dashboard.';
$_lang['widget_permission'] = 'Permission';
$_lang['widget_name_desc'] = 'The name, or Lexicon Entry key, of the Widget.';
$_lang['widget_add'] = 'Add Widget';
$_lang['widget_add_desc'] = 'Please select a Widget to add to your Dashboard.';
$_lang['widget_add_success'] = 'The widget was successfully added to your Dashboard. It will be loaded after closing this window.';
$_lang['widget_remove_confirm'] = 'Are you sure you want to delete this Dashboard Widget? This is permanent, and will delete the Widget from all Dashboards.';
$_lang['widget_remove_multiple_confirm'] = 'Are you sure you want to delete these Dashboard Widgets? This is permanent, and will delete the Widgets from all their assigned Dashboards.';
$_lang['widget_namespace'] = 'Namespace';
$_lang['widget_namespace_desc'] = 'The Namespace that this widget will be loaded into. Useful for custom paths.';
$_lang['widget_php'] = 'Inline PHP Widget';
$_lang['widget_place'] = 'Place Widget';
$_lang['widget_size'] = 'Size';
$_lang['widget_size_desc'] = 'The size of the widget. Can either be a from "quarter" to "double".';
$_lang['widget_size_double'] = 'Double Size';
$_lang['widget_size_full'] = 'Full Size';
$_lang['widget_size_three_quarters'] = 'Three Quarters';
$_lang['widget_size_two_third'] = 'Two Third';
$_lang['widget_size_half'] = 'Half';
$_lang['widget_size_one_third'] = 'One Third';
$_lang['widget_size_quarter'] = 'Quarter';
$_lang['widget_snippet'] = 'Snippet';
$_lang['widget_type'] = 'Widget Type';
$_lang['widget_type_desc'] = 'The type of widget this is. "Snippet" widgets are MODX Snippets that are run and return their output. "HTML" widgets are just straight HTML. "File" widgets are loaded directly from files, which can either return their output or the name of the modDashboardWidgetClass-extended class to load. "Inline PHP" Widgets are widgets that are straight PHP in the widget content, similar to a Snippet.';
$_lang['widget_unplace'] = 'Delete Widget from Dashboard';
$_lang['widgets'] = 'Widgets';
$_lang['widgets.intro_msg'] = 'Below is a list of all the installed Dashboard Widgets you have.';

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
$_lang['w_configcheck'] = 'Configuration Check';
$_lang['w_configcheck_desc'] = 'Displays a configuration check that ensures your MODX install is secure.';
$_lang['w_newsfeed'] = 'MODX News Feed';
$_lang['w_newsfeed_desc'] = 'Displays the MODX News Feed';
$_lang['w_recentlyeditedresources'] = 'Recently Edited Resources';
$_lang['w_recentlyeditedresources_desc'] = 'Shows a list of the most recently edited resources by the user.';
$_lang['w_securityfeed'] = 'MODX Security Feed';
$_lang['w_securityfeed_desc'] = 'Displays the MODX Security Feed';
$_lang['w_whosonline'] = 'Who\'s Online';
$_lang['w_whosonline_desc'] = 'Shows a list of online users.';
$_lang['w_view_all'] = 'View all';
$_lang['w_no_data'] = 'No data to display';
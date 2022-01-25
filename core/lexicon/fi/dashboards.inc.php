<?php
/**
 * English language strings for Dashboards
 *
 * @package modx
 * @subpackage lexicon
 * @language en
 */
$_lang['dashboard'] = 'Kojelauta';
$_lang['dashboard_desc_name'] = 'Kojelaudan nimi.';
$_lang['dashboard_desc_description'] = 'Kojelaudan lyhyt kuvaus.';
$_lang['dashboard_desc_hide_trees'] = 'Tämä valinta piilottaa vasemman hakemistopuun kun kojelauta muodostetaan aloitussivulle.';
$_lang['dashboard_hide_trees'] = 'Piilota hakemistopuu vasemmalla';
$_lang['dashboard_desc_customizable'] = 'Allow users to customize this dashboard for their accounts: create, delete and change position or size of widgets.';
$_lang['dashboard_customizable'] = 'Customizable';
$_lang['dashboard_remove_confirm'] = 'Are you sure you want to delete this Dashboard?';
$_lang['dashboard_remove_multiple_confirm'] = 'Are you sure you want to delete the selected Dashboards?';
$_lang['dashboard_err_ae_name'] = 'Kojelaudan nimi "[[+name]]" on jo olemassa! Kokeile toista nimeä.';
$_lang['dashboard_err_duplicate'] = 'Virhe kopioitaessa kojelautaa.';
$_lang['dashboard_err_nf'] = 'Kojelautaa ei löydy.';
$_lang['dashboard_err_ns'] = 'Kojelautaa ei ole määritetty.';
$_lang['dashboard_err_ns_name'] = 'Määritä pienoisohjelman nimi .';
$_lang['dashboard_err_remove'] = 'An error occurred while trying to delete the Dashboard.';
$_lang['dashboard_err_remove_default'] = 'You cannot delete the default Dashboard!';
$_lang['dashboard_err_save'] = 'Virhe tallennettaessa kojelautaa.';
$_lang['dashboard_usergroup_add'] = 'Määrittää kojelauta käyttäjäryhmään';
$_lang['dashboard_usergroup_remove'] = 'Delete Dashboard from User Group';
$_lang['dashboard_usergroup_remove_confirm'] = 'Oletko varma, että haluat palauttaa tämän käyttäjäryhmän kojelaudan oletuskojelaudaksi?';
$_lang['dashboard_usergroups.intro_msg'] = 'Lista tämän kojelaudan käyttäjäryhmistä.';
$_lang['dashboard_widget_err_placed'] = 'Tämä pienoisohjelma on jo sijoitettu tähän kojelautaan!';
$_lang['dashboard_widgets.intro_msg'] = 'Manage widgets in this dashboard. You can also drag and drop rows in the grid to rearrange them.<br><br>Please note: if a dashboard is "customizable", this settings will be applied only for the first load for every user. From here they will be able to create, delete and change the position or size of their widgets. User access to widgets can be limited by applying permissions.';
$_lang['dashboards'] = 'Kojelaudat';
$_lang['dashboards.intro_msg'] = 'Täällä voit hallita kaikkia saatavilla olevia kojelautoja MODX hallinassa.';
$_lang['rank'] = 'Luokitus';
$_lang['user_group_filter'] = 'Käyttäjäryhmä';
$_lang['widget'] = 'Pienoisohjelma';
$_lang['widget_content'] = 'Pienoisohjelman sisältö';
$_lang['widget_err_ae_name'] = 'Nimi "[[+name]]" on jo olemassa! Kokeile toista nimeä.';
$_lang['widget_err_nf'] = 'Pienoisohjelmaa ei löydy!';
$_lang['widget_err_ns'] = 'Pienoisohjelmaa ei ole määritetty!';
$_lang['widget_err_ns_name'] = 'Määritä pienoisohjelman nimi .';
$_lang['widget_err_remove'] = 'An error occurred while trying to delete the Widget.';
$_lang['widget_err_save'] = 'Virhe tallennettaessa pienoisohjelmaa.';
$_lang['widget_file'] = 'Tiedosto';
$_lang['widget_dashboards.intro_msg'] = 'Alla on lista kaikista kojelaudoista joihin tämä pienoisohjelma on sijoitettu.';
$_lang['widget_dashboard_remove'] = 'Delete Widget From Dashboard';
$_lang['widget_description_desc'] = 'Pienoisohjelman kuvaus tai Lexicon avain pienoisohjelmasta ja sen toiminnoista.';
$_lang['widget_html'] = 'HTML';
$_lang['widget_lexicon_desc'] = 'The Lexicon Topic to load with this Widget. Useful for providing translations for the name and description, as well as any text in the widget.';
$_lang['widget_permission_desc'] = 'This permission will be required to add this widget to a user dashboard.';
$_lang['widget_permission'] = 'Käyttöoikeus';
$_lang['widget_name_desc'] = 'The name, or Lexicon Entry key, of the Widget.';
$_lang['widget_add'] = 'Add Widget';
$_lang['widget_add_desc'] = 'Please select a Widget to add to your Dashboard.';
$_lang['widget_add_success'] = 'The widget was successfully added to your Dashboard. It will be loaded after closing this window.';
$_lang['widget_remove_confirm'] = 'Are you sure you want to delete this Dashboard Widget? This is permanent, and will delete the Widget from all Dashboards.';
$_lang['widget_remove_multiple_confirm'] = 'Are you sure you want to delete these Dashboard Widgets? This is permanent, and will delete the Widgets from all their assigned Dashboards.';
$_lang['widget_namespace'] = 'Namespace';
$_lang['widget_namespace_desc'] = 'The Namespace that this widget will be loaded into. Useful for custom paths.';
$_lang['widget_php'] = 'Upotettu PHP pienoisohjelma';
$_lang['widget_place'] = 'Sijoita pienoisohjelma';
$_lang['widget_size'] = 'Koko';
$_lang['widget_size_desc'] = 'The size of the widget. Can either be a from "quarter" to "double".';
$_lang['widget_size_double'] = 'Double Size';
$_lang['widget_size_full'] = 'Full Size';
$_lang['widget_size_three_quarters'] = 'Three Quarters';
$_lang['widget_size_two_third'] = 'Two Third';
$_lang['widget_size_half'] = 'Puoli';
$_lang['widget_size_one_third'] = 'One Third';
$_lang['widget_size_quarter'] = 'Quarter';
$_lang['widget_snippet'] = 'PHP-pala';
$_lang['widget_type'] = 'Pienoisohjelman tyyppi';
$_lang['widget_type_desc'] = 'The type of widget this is. "Snippet" widgets are MODX Snippets that are run and return their output. "HTML" widgets are just straight HTML. "File" widgets are loaded directly from files, which can either return their output or the name of the modDashboardWidgetClass-extended class to load. "Inline PHP" Widgets are widgets that are straight PHP in the widget content, similar to a Snippet.';
$_lang['widget_unplace'] = 'Delete Widget from Dashboard';
$_lang['widgets'] = 'Pienoisohjelmat';
$_lang['widgets.intro_msg'] = 'Alla on lista kaikista asennetuista pienoisohjelmista.';

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
$_lang['w_configcheck'] = 'Kokoonpanon tarkistus';
$_lang['w_configcheck_desc'] = 'Näyttää kokoonpano tarkistuksen, joka takaa MODX asennus on turvallista.';
$_lang['w_newsfeed'] = 'MODX uutissyöte';
$_lang['w_newsfeed_desc'] = 'Näyttää MODX uutissyötteen';
$_lang['w_recentlyeditedresources'] = 'Äskettäin muokatut resurssit';
$_lang['w_recentlyeditedresources_desc'] = 'Näyttää luettelon käyttäjän äskettäin muokatuista resursseista.';
$_lang['w_securityfeed'] = 'MODX Tietoturva syöte';
$_lang['w_securityfeed_desc'] = 'Näyttää MODX turvatiedotteet';
$_lang['w_whosonline'] = 'Kirjautuneena';
$_lang['w_whosonline_desc'] = 'Näyttää luettelon kirjautuneista käyttäjistä.';
$_lang['w_view_all'] = 'View all';
$_lang['w_no_data'] = 'Ei tietoja näytettäväksi';
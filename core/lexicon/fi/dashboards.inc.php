<?php
/**
 * English language strings for Dashboards
 *
 * @package modx
 * @subpackage lexicon
 * @language en
 */
$_lang['dashboard'] = 'Kojelauta';
$_lang['dashboard_add'] = 'Lisää kojelauta';
$_lang['dashboard_create'] = 'Luo kojelauta';
$_lang['dashboard_desc_name'] = 'Kojelaudan nimi.';
$_lang['dashboard_desc_description'] = 'Kojelaudan lyhyt kuvaus.';
$_lang['dashboard_desc_hide_trees'] = 'Tämä valinta piilottaa vasemman hakemistopuun kun kojelauta muodostetaan aloitussivulle.';
$_lang['dashboard_hide_trees'] = 'Piilota hakemistopuu vasemmalla';
$_lang['dashboard_duplicate'] = 'Kopioi kojelauta';
$_lang['dashboard_remove'] = 'Poista kojelauta';
$_lang['dashboard_remove_confirm'] = 'Oletko varma, että haluat poistaa tämän kojelaudan?';
$_lang['dashboard_remove_multiple'] = 'Poista kojelaudat';
$_lang['dashboard_remove_multiple_confirm'] = 'Haluatko varmasti poistaa valitut kojelaudat?';
$_lang['dashboard_update'] = 'Päivitä kojelauta';
$_lang['dashboard_err_ae_name'] = 'Kojelaudan nimi "[[+name]]" on jo olemassa! Kokeile toista nimeä.';
$_lang['dashboard_err_duplicate'] = 'Virhe kopioitaessa kojelautaa.';
$_lang['dashboard_err_nf'] = 'Kojelautaa ei löydy.';
$_lang['dashboard_err_ns'] = 'Kojelautaa ei ole määritetty.';
$_lang['dashboard_err_ns_name'] = 'Määritä pienoisohjelman nimi .';
$_lang['dashboard_err_remove'] = 'Virhe yritettäessä poistaa kojelauta.';
$_lang['dashboard_err_remove_default'] = 'Et voi poistaa oletusarvoista kojelautaa!';
$_lang['dashboard_err_save'] = 'Virhe tallennettaessa kojelautaa.';
$_lang['dashboard_usergroup_add'] = 'Määrittää kojelauta käyttäjäryhmään';
$_lang['dashboard_usergroup_remove'] = 'Kojelaudan poistaminen käyttäjäryhmästä';
$_lang['dashboard_usergroup_remove_confirm'] = 'Oletko varma, että haluat palauttaa tämän käyttäjäryhmän kojelaudan oletuskojelaudaksi?';
$_lang['dashboard_usergroups.intro_msg'] = 'Lista tämän kojelaudan käyttäjäryhmistä.';
$_lang['dashboard_widget_err_placed'] = 'Tämä pienoisohjelma on jo sijoitettu tähän kojelautaan!';
$_lang['dashboard_widgets.intro_msg'] = 'Tässä voit lisätä, hallita ja poistaa pienoisohjelmia tässä kojelaudassa. Voit myös muokata rivien järjestystä ruudukossa vetämällä.';
$_lang['dashboards'] = 'Kojelaudat';
$_lang['dashboards.intro_msg'] = 'Täällä voit hallita kaikkia saatavilla olevia kojelautoja MODX hallinassa.';
$_lang['rank'] = 'Luokitus';
$_lang['user_group_filter'] = 'Käyttäjäryhmä';
$_lang['widget'] = 'Pienoisohjelma';
$_lang['widget_content'] = 'Pienoisohjelman sisältö';
$_lang['widget_create'] = 'Luo uusi pienoisohjelma';
$_lang['widget_err_ae_name'] = 'Nimi "[[+name]]" on jo olemassa! Kokeile toista nimeä.';
$_lang['widget_err_nf'] = 'Pienoisohjelmaa ei löydy!';
$_lang['widget_err_ns'] = 'Pienoisohjelmaa ei ole määritetty!';
$_lang['widget_err_ns_name'] = 'Määritä pienoisohjelman nimi .';
$_lang['widget_err_remove'] = 'Virhe poistaessa pienoisohjelmaa.';
$_lang['widget_err_save'] = 'Virhe tallennettaessa pienoisohjelmaa.';
$_lang['widget_file'] = 'Tiedosto';
$_lang['widget_dashboards.intro_msg'] = 'Alla on lista kaikista kojelaudoista joihin tämä pienoisohjelma on sijoitettu.';
$_lang['widget_dashboard_remove'] = 'Pienoisohjelman poistaminen kojelaudasta';
$_lang['widget_description_desc'] = 'Pienoisohjelman kuvaus tai Lexicon avain pienoisohjelmasta ja sen toiminnoista.';
$_lang['widget_html'] = 'HTML';
$_lang['widget_lexicon_desc'] = 'The Lexicon Topic to load with this Widget. Useful for providing translations for the name and description, as well as any text in the widget.';
$_lang['widget_name_desc'] = 'The name, or Lexicon Entry key, of the Widget.';
$_lang['widget_new'] = 'Uusi pienoisohjelma';
$_lang['widget_remove'] = 'Poista pienoisohjelma';
$_lang['widget_remove_confirm'] = 'Oletko varma, että haluat poistaa tämän pienoisohjelman? Tämä on pysyvää ja ohjelma poistetaan kaikista kojelaudoista.';
$_lang['widget_remove_multiple'] = 'Poista useita pienoisohjelmia';
$_lang['widget_remove_multiple_confirm'] = 'Haluatko varmasti poistaa nämä kojelaudan pienoisohjelmat? Tämä on pysyvää ja poistaa pienoisohjelman kaikista määritetyistä kojelaudoista.';
$_lang['widget_namespace'] = 'Namespace';
$_lang['widget_namespace_desc'] = 'The Namespace that this widget will be loaded into. Useful for custom paths.';
$_lang['widget_php'] = 'Upotettu PHP pienoisohjelma';
$_lang['widget_place'] = 'Sijoita pienoisohjelma';
$_lang['widget_size'] = 'Koko';
$_lang['widget_size_desc'] = 'The size of the widget. Can either be a half-screen wide ("Half"), the width of the screen ("Full"), or a full screen width and two rows ("Double").';
$_lang['widget_size_double'] = 'Tuplaus';
$_lang['widget_size_full'] = 'Täysi';
$_lang['widget_size_half'] = 'Puoli';
$_lang['widget_snippet'] = 'PHP-pala';
$_lang['widget_type'] = 'Pienoisohjelman tyyppi';
$_lang['widget_type_desc'] = 'The type of widget this is. "Snippet" widgets are MODX Snippets that are run and return their output. "HTML" widgets are just straight HTML. "File" widgets are loaded directly from files, which can either return their output or the name of the modDashboardWidgetClass-extended class to load. "Inline PHP" Widgets are widgets that are straight PHP in the widget content, similar to a Snippet.';
$_lang['widget_unplace'] = 'Poista pienoisohjelma kojelaudasta';
$_lang['widget_update'] = 'Päivitä pienoisohjelma';
$_lang['widgets'] = 'Pienoisohjelmat';
$_lang['widgets.intro_msg'] = 'Alla on lista kaikista asennetuista pienoisohjelmista.';

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
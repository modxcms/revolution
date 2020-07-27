<?php
/**
 * System Events English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['events'] = 'Événements';
$_lang['system_event'] = 'Événement système';
$_lang['system_events'] = 'Événements système';
$_lang['system_events.desc'] = 'System Events are the events in MODX that Plugins are registered to. They are "fired" throughout the MODX code, allowing Plugins to interact with MODX code and add custom functionality without hacking core code. You can create your own events for your custom project here too. You cannot delete core events, only your own.';
$_lang['system_events.search_by_name'] = 'Rechercher par nom d\'évènement';
$_lang['system_events.create'] = 'Create Event';
$_lang['system_events.name_desc'] = 'Nom de l\'événement, que vous pourrez utiliser via un appel à &dollar;modx->invokeEvent(name, properties)';
$_lang['system_events.groupname'] = 'Groupe';
$_lang['system_events.groupname_desc'] = 'The name of the group where the event belongs to. Select an existing one or type a new group name.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'La liste des plugins attachés à l’événement. Sélectionnez les plugins qui doivent être attachés à l’événement.';

$_lang['system_events.service'] = 'Service';
$_lang['system_events.service_1'] = 'Événements du service "Parser"';
$_lang['system_events.service_2'] = 'Événements d\'accès au gestionnaire';
$_lang['system_events.service_3'] = 'Événements du service d\'accès Web';
$_lang['system_events.service_4'] = 'Événements du service de gestion du cache';
$_lang['system_events.service_5'] = 'Événements du service de gestion des Templates';
$_lang['system_events.service_6'] = 'Événements défini par l\'utilisateur';

$_lang['system_events.remove'] = 'Delete Event';
$_lang['system_events.remove_confirm'] = 'Are you sure you want to delete the <b>[[+name]]</b> event? This is irreversible!';

$_lang['system_events_err_ns'] = 'Nom de l\'événement système non précisé.';
$_lang['system_events_err_ae'] = 'Ce nom d\'événement système existe déjà.';
$_lang['system_events_err_startint'] = 'Il n\'a pas permis de commencer le nom par un chiffre.';
$_lang['system_events_err_remove_not_allowed'] = 'You\'re not allowed to delete this System Event.';

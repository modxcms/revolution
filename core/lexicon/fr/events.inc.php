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
$_lang['system_events.desc'] = 'Les événements système sont les événements MODX auxquels les Plugins sont associés. Ils sont invoqués partout dans le code de MODX, afin de permettre aux Plugins d\'interagir avec le code MODX et ainsi d\'ajouter des fonctionnalités personnalisées sans devoir modifier le code principal. Vous pouvez ici aussi créer vos propres événements pour votre projet. Vous ne pouvez pas supprimer les événements de base, seulement les vôtres.';
$_lang['system_events.search_by_name'] = 'Rechercher par nom d\'évènement';
$_lang['system_events.name_desc'] = 'Nom de l\'événement, que vous pourrez utiliser via un appel à &dollar;modx->invokeEvent(name, properties)';
$_lang['system_events.groupname'] = 'Groupe';
$_lang['system_events.groupname_desc'] = 'Nom du groupe auquel l\'événement appartient. Sélectionnez un groupe existant ou saisissez le nom d\'un nouveau groupe.';
$_lang['system_events.plugins'] = 'Plugins';
$_lang['system_events.plugins_desc'] = 'La liste des plugins attachés à l’événement. Sélectionnez les plugins qui doivent être attachés à l’événement.';

$_lang['system_events.service'] = 'Service';
$_lang['system_events.service_1'] = 'Événements du service "Parser"';
$_lang['system_events.service_2'] = 'Événements d\'accès au gestionnaire';
$_lang['system_events.service_3'] = 'Événements du service d\'accès Web';
$_lang['system_events.service_4'] = 'Événements du service de gestion du cache';
$_lang['system_events.service_5'] = 'Événements du service de gestion des Templates';
$_lang['system_events.service_6'] = 'Événements défini par l\'utilisateur';

$_lang['system_events.remove_confirm'] = 'Êtes-vous sûr de que vouloir supprimer l\'événement <b>[[+name]]</b> ? Ceci est irréversible !';

$_lang['system_events_err_ns'] = 'Nom de l\'événement système non précisé.';
$_lang['system_events_err_ae'] = 'Ce nom d\'événement système existe déjà.';
$_lang['system_events_err_startint'] = 'Il n\'a pas permis de commencer le nom par un chiffre.';
$_lang['system_events_err_remove_not_allowed'] = 'Vous n\'êtes pas autorisé à supprimer cet événement système.';

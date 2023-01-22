<?php
/**
 * Plugin English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['event'] = 'Événement';
$_lang['events'] = 'Événements';
$_lang['plugin'] = 'Plugin';
$_lang['plugin_add'] = 'Ajouter un Plugin';
$_lang['plugin_category_desc'] = 'Use to group Plugins within the Elements tree.';
$_lang['plugin_code'] = 'Plugin Code (PHP)';
$_lang['plugin_config'] = 'Configuration du plugin';
$_lang['plugin_description_desc'] = 'Usage information for this Plugin shown in search results and as a tooltip in the Elements tree.';
$_lang['plugin_delete_confirm'] = 'Voulez-vous vraiment supprimer ce plugin?';
$_lang['plugin_disabled'] = 'Deactivate Plugin';
$_lang['plugin_disabled_msg'] = 'When deactivated, this Plugin will not respond to events.';
$_lang['plugin_duplicate_confirm'] = 'Voulez-vous vraiment dupliquer ce plugin?';
$_lang['plugin_err_create'] = 'Une erreur s\'est produite lors de la création du plugin.';
$_lang['plugin_err_ae'] = 'Un plugin ayant pour nom "[[+name]]" existe déjà.';
$_lang['plugin_err_invalid_name'] = 'Nom de plugin invalide.';
$_lang['plugin_err_duplicate'] = 'Une erreur est survenue lors de la duplication du plugin.';
$_lang['plugin_err_nf'] = 'Plugin introuvable!';
$_lang['plugin_err_ns'] = 'Plugin non spécifié.';
$_lang['plugin_err_ns_name'] = 'Veuillez entrer un nom pour ce plugin.';
$_lang['plugin_err_remove'] = 'Une erreur est survenue lors de la suppression du plugin.';
$_lang['plugin_err_save'] = 'Une erreur s\'est produite lors de l\'enregistrement du plugin.';
$_lang['plugin_event_err_duplicate'] = 'Une erreur s\'est produite lors de la duplication des événements du plugin';
$_lang['plugin_event_err_nf'] = 'Événement du plugin introuvable.';
$_lang['plugin_event_err_ns'] = 'Événement du plugin non spécifié.';
$_lang['plugin_event_err_remove'] = 'Une erreur s\'est produite lors de la suppression des événements du plugin';
$_lang['plugin_event_err_save'] = 'Une erreur s\'est produite lors de l\'enregistrement de l\'évènement de ce plugin.';
$_lang['plugin_event_msg'] = 'Sélectionnez les événements auxquels le plugin doit être associé.';
$_lang['plugin_event_plugin_remove_confirm'] = 'Voulez-vous vraiment supprimer ce plugin de cet événement ?';
$_lang['plugin_lock'] = 'Plugin verrouillé pour modification';
$_lang['plugin_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Plugin.';
$_lang['plugin_locked_message'] = 'Ce plugin est verrouillé.';
$_lang['plugin_management_msg'] = 'Choisissez ici le plugin que vous souhaitez modifier.';
$_lang['plugin_name_desc'] = 'Nom du plugin.';
$_lang['plugin_new'] = 'Créer un module (plugin)';
$_lang['plugin_priority'] = 'Modifier l\'ordre d\'exécution des plugins par événement';
$_lang['plugin_properties'] = 'Propriétés du plugin';
$_lang['plugin_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Plugin</em> as well as its content. The content must be PHP, either placed in the <em>Plugin Code</em> field below or in a static external file. The PHP code entered runs in response to one or more MODX System Events that you specify.';
$_lang['plugins'] = 'Plugins';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['plugin_desc_category'] = $_lang['plugin_category_desc'];
$_lang['plugin_desc_description'] = $_lang['plugin_description_desc'];
$_lang['plugin_desc_name'] = $_lang['plugin_name_desc'];
$_lang['plugin_lock_msg'] = $_lang['plugin_lock_desc'];

// --tabs
$_lang['plugin_msg'] = $_lang['plugin_tab_general_desc'];

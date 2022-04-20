<?php
/**
 * Template English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

$_lang['access'] = 'Accès';
$_lang['filter_by_category'] = 'Filtrer par catégorie…';
$_lang['rank'] = 'Ordre';
$_lang['template'] = 'Modèle';
$_lang['template_assignedtv_tab'] = 'TV assignés';
$_lang['template_category_desc'] = 'Use to group Templates within the Elements tree.';
$_lang['template_code'] = 'Template Code (HTML)';
$_lang['template_delete_confirm'] = 'Voulez-vous vraiment supprimer ce modèle?';
$_lang['template_description_desc'] = 'Usage information for this Template shown in search results and as a tooltip in the Elements tree.';
$_lang['template_duplicate_confirm'] = 'Voulez-vous vraiment dupliquer ce modèle?';
$_lang['template_edit_tab'] = 'Modifier le modèle';
$_lang['template_empty'] = '(vide)';
$_lang['template_err_default_template'] = 'Ce modèle est défini comme modèle par défaut. Veuillez choisir un autre modèle par défaut dans la configuration de MODX avant de supprimer celui-ci.<br />';
$_lang['template_err_delete'] = 'Une erreur est survenue lors de la suppression du modèle.';
$_lang['template_err_duplicate'] = 'Une erreur est survenue lors de la duplication du modèle.';
$_lang['template_err_ae'] = 'Un modèle ayant pour nom "[[+name]]" existe déjà.';
$_lang['template_err_in_use'] = 'Ce modèle est utilisé. Veuillez sélectionner un autre modèle pour les documents utilisant ce modèle. Les documents qui utilisent ce modèle sont:<br />';
$_lang['template_err_invalid_name'] = 'Le nom du modèle n\'est pas valide';
$_lang['template_err_locked'] = 'Le modèle est protégé en écriture.';
$_lang['template_err_nf'] = 'Modèle introuvable!';
$_lang['template_err_ns'] = 'Modèle non spécifié.';
$_lang['template_err_ns_name'] = 'Veuillez préciser un nom pour le modèle.';
$_lang['template_err_remove'] = 'Une erreur est survenue lors de la suppression du modèle.';
$_lang['template_err_save'] = 'Une erreur est survenue lors de la sauvegarde du modèle.';
$_lang['template_icon'] = 'Manager Icon Class';
$_lang['template_icon_desc'] = 'A CSS class to assign an icon (shown in the document trees) for all resources using this template. Font Awesome Free 5 classes such as “fa-home” may be used.';
$_lang['template_lock'] = 'Protéger le modèle contre les modifications';
$_lang['template_lock_desc'] = 'Only users with “edit_locked” permissions can edit this Template.';
$_lang['template_locked_message'] = 'Ce modèle est verrouillé.';
$_lang['template_management_msg'] = 'Choisissez ici le modèle que vous souhaitez modifier.';
$_lang['template_name_desc'] = 'Nom du modèle.';
$_lang['template_new'] = 'Créer un modèle';
$_lang['template_no_tv'] = 'Aucune variable de modèle n\'a encore été assignée à ce modèle.';
$_lang['template_preview'] = 'Preview Image';
$_lang['template_preview_desc'] = 'Used to preview the layout of this Template when creating a new Resource. (Minimum size: 335 x 236)';
$_lang['template_preview_source'] = 'Preview Image Media Source';
$_lang['template_preview_source_desc'] = 'Sets the basePath for this Template’s Preview Image to the one specified in the chosen Media Source. Choose “None” when specifying an absolute or other custom path to the file.';
$_lang['template_properties'] = 'Propriétés par défaut';
$_lang['template_reset_all'] = 'Réinitialiser toutes les pages qui utilisent le modèle par défaut';
$_lang['template_reset_specific'] = 'Réinitialiser uniquement \'%s\' pages';
$_lang['template_tab_general_desc'] = 'Here you can enter the basic attributes for this <em>Template</em> as well as its content. The content must be HTML, either placed in the <em>Template Code</em> field below or in a static external file, and may include MODX tags. Note that changed or new templates won’t be visible in your site’s cached pages until the cache is emptied; however, you can use the preview function on a page to see the template in action.';
$_lang['template_title'] = 'Créer/modifier un modèle';
$_lang['template_tv_edit'] = 'Modifier l\'ordre de tri des TVs';
$_lang['template_tv_msg'] = 'Les <abbr title="Template Variables">TV</abbr> assignées à ce modèle sont listés ci-dessous.';
$_lang['template_untitled'] = 'Modèle sans nom';
$_lang['templates'] = 'Modèles';
$_lang['tvt_err_nf'] = 'La Variable de modèle n\'a pas accès au modèle spécifié.';
$_lang['tvt_err_remove'] = 'Une erreur s\'est produite en essayant de supprimer la TV du modèle.';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['template_desc_category'] = $_lang['template_category_desc'];
$_lang['template_desc_description'] = $_lang['template_description_desc'];
$_lang['template_desc_name'] = $_lang['template_name_desc'];
$_lang['template_lock_msg'] = $_lang['template_lock_desc'];

// --tabs
$_lang['template_msg'] = $_lang['template_tab_general_desc'];

<?php
/**
 * TV English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['example_tag_tvname'] = 'NomDeLaTV';
$_lang['has_access'] = 'A un accès ?';
$_lang['filter_by_category'] = 'Filtrer par catégorie…';
$_lang['rank'] = 'Ordre';
$_lang['rendering_options'] = 'Options de rendu';
$_lang['tv'] = 'TV';
$_lang['tvs'] = 'Variables de modèle';
$_lang['tv_binding_msg'] = 'Ce champs est prévu pour des sources de données reliées avec la commande @';
$_lang['tv_caption'] = 'Légende';
$_lang['tv_caption_desc'] = 'L\'étiquette affichée pour cette TV dans les pages d\'édition des ressources (peut être remplacée par un modèle ou d\'autres critères à l\'aide de la fonction <a href="?a=security/forms" target="_blank">Personnalisation des formulaires</a>).';
$_lang['tv_category_desc'] = 'Permet de regrouper les TVs dans les pages d\'édition des ressources et dans l\'arborescence des éléments.';
$_lang['tv_change_template_msg'] = 'Si vous changez ce modèle la page rechargera la Variable de Modèle, entraînant la perte de tous les changements non sauvegardés.<br /><br /> Êtes-vous sûr de vouloir changer ce modèle ?';
$_lang['tv_delete_confirm'] = 'Êtes-vous sûr de vouloir supprimer cette variable de modèle (TV) ?';
$_lang['tv_description'] = 'Description';
$_lang['tv_description_desc'] = 'Informations sur l\'utilisation de cette TV affichées à côté de sa légende dans les pages d\'édition des ressources et sous forme d\'info-bulle dans l\'arborescence des éléments.';
$_lang['tv_err_delete'] = 'Une erreur est survenue lors de la suppression de la TV.';
$_lang['tv_err_duplicate'] = 'Une erreur est survenue lors de la duplication de la TV.';
$_lang['tv_err_duplicate_templates'] = 'Une erreur est apparue lors de la duplication des variables de modèle.';
$_lang['tv_err_duplicate_documents'] = 'Une erreur est apparue lors de la duplication des documents de la TV.';
$_lang['tv_err_duplicate_documentgroups'] = 'Une erreur est apparue lors de la duplication des groupes du document de la TV.';
$_lang['tv_err_ae'] = 'Une variable de modèle ayant pour nom "[[+name]]" existe déjà.';
$_lang['tv_err_invalid_name'] = 'Le nom de la TV est invalide.';
$_lang['tv_err_invalid_id_attr'] = 'Les identifiants HTML ne doivent pas commencer par un chiffre ni contenir d\'espace blanc.';
$_lang['tv_err_locked'] = 'TV verrouillée !';
$_lang['tv_err_nf'] = 'TV non trouvée.';
$_lang['tv_err_nfs'] = 'TV non trouvée pour la clé : [[+id]]';
$_lang['tv_err_ns'] = 'TV non spécifiée.';
$_lang['tv_err_reserved_name'] = 'Une variable de modèle ne peut avoir le même nom qu\'un champ de Ressource.';
$_lang['tv_err_save_access_permissions'] = 'Une erreur est survenue en essayant de sauvegarder les permissions d\'accès à la variable de modèle.';
$_lang['tv_err_save'] = 'Une erreur s\'est produite pendant la sauvegarde de la TV.';
$_lang['tv_inuse'] = 'Les documents suivants utilisent actuellement cette TV. Pour poursuivre l\'opération de suppression, cliquez sur le bouton Supprimer sinon cliquez sur le bouton Annuler.';
$_lang['tv_inuse_template'] = 'Les modèles suivants utilisent actuellement cette TV : [[+templates]]. <br /><br />; Veuillez désactiver la TV des modèles avant de la supprimer.';
$_lang['tv_lock'] = 'Restreindre l\'édition';
$_lang['tv_lock_desc'] = 'Seuls les utilisateurs disposant des droits "edit_locked" peuvent modifier cette TV.';
$_lang['tv_management_msg'] = 'Gérez des TV personnalisées supplémentaires pour vos documents.';
$_lang['tv_name'] = 'Nom de la TV';
$_lang['tv_name_desc'] = 'Placez le contenu généré par cette TV dans une ressource, un modèle ou un élément en utilisant la balise MODX suivante: <span class="copy-this">[[*<span class="example-replace-name">'.$_lang['example_tag_tvname'].'</span>]]</span>';
$_lang['tv_new'] = 'Créer une TV';
$_lang['tv_novars'] = 'Aucune TV trouvée';
$_lang['tv_properties'] = 'Propriétés par défaut';
$_lang['tv_rank'] = 'Ordre de tri';
$_lang['tv_rank_desc'] = 'Permet de contrôler le positionnement de cette TV dans les pages d\'édition des ressources (peut être remplacé par un modèle ou d\'autres critères à l\'aide de la fonction <a href="?a=security/forms" target="_blank">Personnalisation des formulaires</a>).';
$_lang['tv_reset_params'] = 'Paramètres de remise à zéro';
$_lang['tv_tab_access_desc'] = 'Sélectionnez les Groupes de ressources auxquels appartient cette TV. Seuls les utilisateurs ayant accès aux groupes sélectionnés pourront modifier cette TV. Si aucun groupe n\'est sélectionné, tous les utilisateurs ayant accès au Manager pourront modifier la TV.';
$_lang['tv_tab_general_desc'] = 'Ici, vous pouvez créer/modifier une <dfn>Variable de modèle</dfn> (TV). Les TV doivent être affectées aux modèles afin de pouvoir y accéder à partir des snippets et des documents.';
$_lang['tv_tab_input_options'] = 'Options d\'entrée';
$_lang['tv_tab_input_options_desc'] = '<p>Vous pouvez modifier ici les options d\'entrée pour la TV, en fonction du type de rendu d\'entrée que vous avez sélectionné.</p>';
$_lang['tv_tab_output_options'] = 'Options d\'affichage';
$_lang['tv_tab_output_options_desc'] = '<p>Ici, vous pouvez modifier les options de sortie pour la TV, spécifiques au type de rendu de sortie que vous sélectionnez.</p>';
$_lang['tv_tab_sources_desc'] = 'Définissez ici quels Media Sources sont utilisés pour cette TV, et dans quel contexte. Double-cliquez sur le nom de la source pour le modifier.';
$_lang['tv_tab_tmpl_access'] = 'Accès au Modèle';
$_lang['tv_tab_tmpl_access_desc'] = 'Sélectionnez les modèles autorisés à accéder à la TV.';
$_lang['tv_tag_copied'] = 'Tag de la TV copiée !';
$_lang['tv_widget'] = 'Widget';
$_lang['tv_widget_prop'] = 'Propriété du Widget';
$_lang['tvd_err_remove'] = 'Une erreur s\'est produite en essayant de supprimer la TV du document.';
$_lang['tvdg_err_remove'] = 'Une erreur s\'est produite en essayant de supprimer la TV du groupe de document.';
$_lang['tvdg_err_save'] = 'Une erreur s\'est produite en essayant d\'ajouter la TV au groupe de document.';
$_lang['tvt_err_nf'] = 'La Variable de modèle n\'a pas accès au modèle spécifié.';
$_lang['tvt_err_remove'] = 'Une erreur s\'est produite en essayant de supprimer la TV du modèle.';
$_lang['tvt_err_save'] = 'Une erreur s\'est produite en essayant d\'ajouter la TV au modèle de document.';

// Temporarily match old keys to new ones to ensure compatibility
// -- fields
$_lang['tv_desc_caption'] = $_lang['tv_caption_desc'];
$_lang['tv_desc_category'] = $_lang['tv_category_desc'];
$_lang['tv_desc_description'] = $_lang['tv_description_desc'];
$_lang['tv_desc_name'] = $_lang['tv_name_desc'];
$_lang['tv_lock_msg'] = $_lang['tv_lock_desc'];
$_lang['tv_rank_msg'] = $_lang['tv_rank_desc'];

// -- tabs
$_lang['tv_access_msg'] = $_lang['tv_tab_access_desc'];
$_lang['tv_input_options'] = $_lang['tv_tab_input_options'];
$_lang['tv_input_options_msg'] = $_lang['tv_tab_input_options_desc'];
$_lang['tv_msg'] = $_lang['tv_tab_general_desc'];
$_lang['tv_output_options'] = $_lang['tv_tab_output_options'];
$_lang['tv_output_options_msg'] = $_lang['tv_tab_output_options_desc'];
$_lang['tv_sources.intro_msg'] = $_lang['tv_tab_sources_desc'];
$_lang['tv_tmpl_access'] = $_lang['tv_tab_tmpl_access'];
$_lang['tv_tmpl_access_msg'] = $_lang['tv_tab_tmpl_access_desc'];

/*
    Refer to default.inc.php for the keys below.
    (Placement in this default file necessary to allow
    quick create/edit panels access to them when opened
    outside the context of their respective element types)

    tv_caption_desc
    tv_category_desc
    tv_description_desc

*/

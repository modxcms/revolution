<?php
/**
 * Snippet English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['example_tag_snippet_name'] = 'NomDuSnippet';
$_lang['snippet'] = 'Snippet';
$_lang['snippets_available'] = 'Snippets disponibles, à ajouter dans votre page';
$_lang['snippet_category_desc'] = 'Utiliser pour regrouper les snippets dans l\'arborescence des éléments.';
$_lang['snippet_code'] = 'Code du snippet (PHP)';
$_lang['snippet_delete_confirm'] = 'Voulez-vous vraiment supprimer ce snippet?';
$_lang['snippet_description_desc'] = 'Informations pour l\'utilisation de ce Snippet affichées dans les résultats de recherche et comme infobulle dans l\'arborescence des Eléments.';
$_lang['snippet_duplicate_confirm'] = 'Voulez-vous vraiment dupliquer ce snippet?';
$_lang['snippet_duplicate_error'] = 'Une erreur s\'est produite pendant la copie du snippet.';
$_lang['snippet_err_create'] = 'Une erreur s\'est produite lors de la création du snippet.';
$_lang['snippet_err_delete'] = 'Une erreur s\'est produite lors de la suppression du snippet .';
$_lang['snippet_err_duplicate'] = 'Une erreur s\'est produite lors de la duplication du snippet.';
$_lang['snippet_err_ae'] = 'Un snippet ayant pour nom "[[+name]]" existe déjà.';
$_lang['snippet_err_invalid_name'] = 'Nom de snippet invalide.';
$_lang['snippet_err_locked'] = 'Ce snippet est verrouillé contre les modifications.';
$_lang['snippet_err_nf'] = 'Snippet introuvable!';
$_lang['snippet_err_ns'] = 'Snippet non spécifié.';
$_lang['snippet_err_ns_name'] = 'Veuillez spécifier un nom pour le snippet.';
$_lang['snippet_err_remove'] = 'Une erreur s\'est produite lors de la suppression du snippet .';
$_lang['snippet_err_save'] = 'Une erreur s\'est produite lors de l\'enregistrement du snippet.';
$_lang['snippet_execonsave'] = 'Exécuter le snippet après son enregistrement.';
$_lang['snippet_lock'] = 'Verrouiller le snippet contre les modifications';
$_lang['snippet_lock_desc'] = 'Seuls les utilisateurs ayant les permissions « edit_locked» peuvent modifier ce Snippet.';
$_lang['snippet_management_msg'] = 'Choisissez ici le snippet que vous souhaitez modifier.';
$_lang['snippet_name_desc'] = 'Placez le contenu généré par ce Snippet dans une Ressource, un Modèle ou un Chunk en utilisant le tag MODX suivant : [[+tag]]';
$_lang['snippet_new'] = 'Créer un snippet de code';
$_lang['snippet_properties'] = 'Propriétés par défaut';
$_lang['snippet_tab_general_desc'] = 'Saisir ici les attributs de base pour ce <em>Snippet</em> ainsi que son contenu. Le contenu doit être du code PHP, soit placé dans le champ <em>Code du snippet</em> ci-dessous soit dans un fichier externe statique. Pour recevoir des sorties de votre Snippet au point où il est appelé (au sein d\'un modèle ou d\'un Chunk), une valeur doit être retournée depuis le code.';
$_lang['snippet_tag_copied'] = 'Tag du snippet copié !';
$_lang['snippets'] = 'Snippets';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['snippet_desc_category'] = $_lang['snippet_category_desc'];
$_lang['snippet_desc_description'] = $_lang['snippet_description_desc'];
$_lang['snippet_desc_name'] = $_lang['snippet_name_desc'];
$_lang['snippet_lock_msg'] = $_lang['snippet_lock_desc'];

// --tabs
$_lang['snippet_msg'] = $_lang['snippet_tab_general_desc'];

<?php
/**
 * Chunk English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */

// Entry out of alpha order because it must come before the entry it's used in below
$_lang['example_tag_chunk_name'] = 'NomDuChunk';

$_lang['chunk'] = 'Chunk';
$_lang['chunk_category_desc'] = 'Utiliser pour regrouper les Chunks dans l\'arborescence des Éléments.';
$_lang['chunk_code'] = 'Code source du chunk (HTML)';
$_lang['chunk_description_desc'] = 'Informations d\'utilisation pour ce Chunk affiché dans les résultats de recherche et comme une infobulle dans l\'arborescence des Éléments.';
$_lang['chunk_delete_confirm'] = 'Voulez-vous vraiment supprimer ce chunk?';
$_lang['chunk_duplicate_confirm'] = 'Voulez-vous vraiment dupliquer ce chunk?';
$_lang['chunk_err_create'] = 'Une erreur est survenue lors de la création du chunk.';
$_lang['chunk_err_duplicate'] = 'Erreur dans la duplication du chunk';
$_lang['chunk_err_ae'] = 'Un chunk ayant pour nom "[[+name]]" existe déjà.';
$_lang['chunk_err_invalid_name'] = 'Nom de chunk invalide.';
$_lang['chunk_err_locked'] = 'Le chunk est verrouillé.';
$_lang['chunk_err_remove'] = 'Une erreur est survenue lors de la suppression du chunk.';
$_lang['chunk_err_save'] = 'Une erreur s\'est produite pendant l\'enregistrement du chunk.';
$_lang['chunk_err_nf'] = 'Chunk introuvable!';
$_lang['chunk_err_nfs'] = 'Chunk introuvable avec l\'id: [[+id]]';
$_lang['chunk_err_ns'] = 'Chunk non spécifié.';
$_lang['chunk_err_ns_name'] = 'Veuillez préciser un nom.';
$_lang['chunk_lock'] = 'Verrouiller le chunk pour l\'édition';
$_lang['chunk_lock_desc'] = 'Seuls les utilisateurs ayant les permissions « edit_locked» peuvent modifier ce Chunk.';
$_lang['chunk_name_desc'] = 'Placez le contenu généré par ce Chunk dans une Ressource, un Modèle ou un autre Chunk en utilisant le tag MODX suivant : [[+tag]]';
$_lang['chunk_new'] = 'Créer un Chunk';
$_lang['chunk_properties'] = 'Propriétés par défaut';
$_lang['chunk_tab_general_desc'] = 'Saisir les attributs de base de ce <em>Chunk</em> ainsi que son contenu. Le contenu doit être HTML, soit placé dans le champ <em>Chunk Code</em> ci-dessous ou dans un fichier externe statique, et peut inclure des balises MODX. Notez cependant que le code PHP ne s\'exécutera pas dans cet élément.';
$_lang['chunk_tag_copied'] = 'Balise de chunk copiée !';
$_lang['chunks'] = 'Chunks';

// Temporarily match old keys to new ones to ensure compatibility
// --fields
$_lang['chunk_desc_category'] = $_lang['chunk_category_desc'];
$_lang['chunk_desc_description'] = $_lang['chunk_description_desc'];
$_lang['chunk_desc_name'] = $_lang['chunk_name_desc'];
$_lang['chunk_lock_msg'] = $_lang['chunk_lock_desc'];

// --tabs
$_lang['chunk_msg'] = $_lang['chunk_tab_general_desc'];

<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Spécifiez les extensions (séparées par des virgules) des fichiers à importer.<br /><small><em>Laissez vide pour importer tous les fichiers selon les types de contenus disponibles dans votre site. Les types inconnus seront marqués comme texte brut.</em></small>';
$_lang['import_base_path'] = 'Entrez le chemin du dossier de base contenant les fichiers à importer.<br /><small><em>Laissez vide pour utiliser le chemin spécifié dans les paramètres du contexte</em></small>.';
$_lang['import_duplicate_alias_found'] = 'La ressource [[+id]] utilise déjà l\'alias [[+alias]]. Veuillez entrer un alias unique.';
$_lang['import_element'] = 'Entrez la balise HTML de base à importer:';
$_lang['import_element_help'] = 'Fournit un ensemble JSON avec des associations « champ »:« valeur ». Si la valeur commence par $ c\'est un sélecteur de type jQuery. Le champ peut être une ressource ou le nom d\'une TV.';
$_lang['import_enter_root_element'] = 'Entrez l\'élément racine à importer:';
$_lang['import_files_found'] = '<strong>%s document(s) trouvé(s) pour l\'importation… </strong></p>';
$_lang['import_parent_document'] = 'Document parent:';
$_lang['import_parent_document_message'] = 'Utilisez l\'arborescence présentée ci-dessous pour sélectionner l\'emplacement où importer vos fichiers.';
$_lang['import_resource_class'] = 'Choisissez une catégorie de la classe modResource pour l\'importation : <br /><small><em>Utilisez modStaticResource pour lier des fichiers statiques, ou modDocument pour copier le contenu de la base de données.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Echec!</span>';
$_lang['import_site_html'] = 'Importer un site depuis du HTML';
$_lang['import_site_importing_document'] = 'Importation de <strong>%s</strong> fichier(s) ';
$_lang['import_site_maxtime'] = 'Temps d\'importation maximal :';
$_lang['import_site_maxtime_message'] = 'Vous pouvez spécifier ici le nombre de secondes dont MODX dispose pour l\'importation d\'un site (passe outre les paramètres PHP). Entrez 0 pour un temps illimité. Veuillez noter que mettre 0 ou un nombre élevé peut occasioner des dysfonctionnements sur votre serveur et n\'est donc pas recommandé.';
$_lang['import_site_message'] = '<p>Cet outil vous permet d\'importer le contenu d\'un ensemble de fichiers HTML dans la base de données. <em>Veuillez noter que vous devez copier vos fichiers et/ou dossiers dans le dossier d\'import /core/import.</em> </p><p>Veuillez remplir le formulaire d\'options ci-dessous. Sélectionnez éventuellement une ressource parente dans l\'arborescence pour les fichiers à importer, et appuyez sur "Importer de l\'HTML" pour démarrer le processus d\'importation. Les fichiers importés seront enregistrés à l\'emplacement choisi en utilisant, si possible, le nom des fichiers en tant qu\'alias de document et le titre de la page comme titre de document.</p>';
$_lang['import_site_resource'] = 'Importer des ressources à partir de fichiers statiques';
$_lang['import_site_resource_message'] = '<p>Cet outil vous permet d\'importer des ressources dans la base de données à partir d\'un ensemble de fichiers statiques. <em>Veuillez noter que vous devez copier vos fichiers et/ou dossiers dans le dossier d\'importation core/import.</em></p><p>Veuillez remplir le formulaire d\'options ci-dessous. Sélectionnez éventuellement une ressource parente dans l\'arborescence pour les fichiers à importer, et appuyez sur "Importer des Ressources" pour démarrer le processus d\'importation. Les fichiers importés seront enregistrés à l\'emplacement choisi en utilisant, si possible, le nom des fichiers en tant qu\'alias du document et, si c\'est un fichier HTML, le titre de la page en tant que titre du document.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Ignoré!</span>';
$_lang['import_site_start'] = 'Démarrer l\'importation';
$_lang['import_site_success'] = '<span style="color:#009900">Opération effectuée!</span>';
$_lang['import_site_time'] = 'Import terminé. L\'importation a pris %s secondes.';
$_lang['import_use_doc_tree'] = 'Utilisez l\'arborescence présentée ci-dessous pour sélectionner l\'emplacement où importer vos fichiers.';
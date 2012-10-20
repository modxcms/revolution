<?php
/**
 * Setting French lexicon topic
 *
 * @language fr
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Zone';
$_lang['area_authentication'] = 'Identification et sécurité';
$_lang['area_caching'] = 'Cache';
$_lang['area_core'] = 'Code du core';
$_lang['area_editor'] = 'Éditeur riche de texte';
$_lang['area_file'] = 'Système de fichier';
$_lang['area_filter'] = 'Filtrer par zone…';
$_lang['area_furls'] = 'URLs simples';
$_lang['area_gateway'] = 'Gateway-porte d\'accès';
$_lang['area_language'] = 'Lexique et langue';
$_lang['area_mail'] = 'Email';
$_lang['area_manager'] = 'Back-end (interface) du Manager';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session et Cookie';
$_lang['area_lexicon_string'] = 'Entrées du lexique de zone';
$_lang['area_lexicon_string_msg'] = 'Entrez ici la clé de l\'entrée du lexique pour la zone. S\'il n\'y a pas d\'entrée de lexique, cela affichera la clé de la zone.<br />Zones du coeur:<ul><li>identification</li><li>cache</li><li>fichier</li><li>urls simples</li><li>gateway</li><li>langue</li><li>manager</li><li>session</li><li>site</li><li>système</li></ul>';
$_lang['area_site'] = 'Site';
$_lang['area_system'] = 'Système et Serveur';
$_lang['areas'] = 'Zones';
$_lang['charset'] = 'Jeu de caractères (charset)';
$_lang['country'] = 'Pays';
$_lang['description_desc'] = 'Courte description du paramètre. Peut être une clé d\'entrée de lexique.';
$_lang['key_desc'] = 'La clé du paramètre. Si votre clé se nomme « key », son contenu sera disponible en utilisant le tag [[++key]].';
$_lang['name_desc'] = 'Nom du paramètre. Peut être une clé d\'entrée de lexique.';
$_lang['namespace'] = 'Espace de nom';
$_lang['namespace_desc'] = 'L\'espace de nom auquel est associé ce paramètre. Le sujet « default » du lexique sera chargé lors du chargement des paramètres de cet espace de nom.';
$_lang['namespace_filter'] = 'Filtrer par espace de nom…';
$_lang['search_by_key'] = 'Chercher par clé…';
$_lang['setting_create'] = 'Créer une nouvelle option';
$_lang['setting_err'] = 'Veuillez vérifier vos données pour les champs suivants: ';
$_lang['setting_err_ae'] = 'Une option avec cette clé existe déjà. Veuillez indiquer un autre nom de clé.';
$_lang['setting_err_nf'] = 'Option non trouvée.';
$_lang['setting_err_ns'] = 'Option non spécifiée';
$_lang['setting_err_remove'] = 'Une erreur est survenue lors de la suppression de l\'option.';
$_lang['setting_err_save'] = 'Une erreur est survenue lors de la sauvegarde de l\'option.';
$_lang['setting_err_startint'] = 'Les options ne peuvent débuter par un entier.';
$_lang['setting_err_invalid_document'] = 'Il n\'y a pas de document ayant pour ID %d. Veuillez indiquer un document existant.';
$_lang['setting_remove'] = 'Supprimer l\'option';
$_lang['setting_remove_confirm'] = 'Êtes-vous sûr de vouloir supprimer cette option ? Ceci peut endommager votre installation de MODX.';
$_lang['setting_update'] = 'Mettre à jour l\'option';
$_lang['settings_after_install'] = 'Comme c\'est une nouvelle installation, vous devez contrôler les options de configuration et changer celles que vous souhaitez. Après avoir contrôlé les options, cliquez sur \'Sauvegarder\' pour mettre à jour la base de données des options.<br /><br />';
$_lang['settings_desc'] = 'Ici vous pouvez indiquer les préférences et configurations pour l\'interface de gestion de MODX et également le fonctionnement de votre site MODX. Double-cliquez sur la colonne de valeur de l\'option que vous souhaitez éditer pour éditer dynamiquement via la grille, ou faites un clic droit sur une option pour plus d\'options. Vous pouvez également cliquer le symbole "+" pour une description de l\'option de configuration.';
$_lang['settings_furls'] = 'URLs simples';
$_lang['settings_misc'] = 'Divers';
$_lang['settings_site'] = 'Site';
$_lang['settings_ui'] = 'Interface &amp; Fonctions';
$_lang['settings_users'] = 'Utilisateur';
$_lang['system_settings'] = 'Configuration du Système';
$_lang['usergroup'] = 'Groupe d\'utilisateur';

// user settings
$_lang['setting_access_category_enabled'] = 'Vérifier les permissions de catégorie';
$_lang['setting_access_category_enabled_desc'] = 'Utilisez ceci pour activer ou désactiver la vérification des droits d\'accès aux catégories (par contexte). <strong>NOTE: Si cette option est désactivée, alors toutes les permissions d\'accès aux catégories seront ignorées!</strong>';

$_lang['setting_access_context_enabled'] = 'Vérifier les permissions de contexte';
$_lang['setting_access_context_enabled_desc'] = 'Utilisez ceci pour activer ou désactiver la vérification des droits d\'accès aux contextes. <strong>NOTE: Si cette option est désactivée, alors toutes les permissions d\'accès aux contextes seront ignorées. NE DÉSACTIVEZ PAS cette option pour le système entier ou pour le contexte mgr car vous interdiriez l\'accès au gestionnaire.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Vérifier les permissions des groupes de ressource';
$_lang['setting_access_resource_group_enabled_desc'] = 'Utilisez ceci pour activer ou désactiver la vérification des droits d\'accès aux groupes de ressource (par contexte). <strong>NOTE: Si cette option est désactivée, alors toutes les permissions d\'accès aux groupes de ressource seront ignorées!</strong>';

$_lang['setting_allow_mgr_access'] = 'Accès à l\'interface du manager';
$_lang['setting_allow_mgr_access_desc'] = 'Utilisez cette option pour activer ou désactiver l\'accès à l\'interface du manager. <strong>NOTE: Si cette option est définie à non, alors l\'utilisateur sera redirigé vers l\'écran d\'identification ou la page d\'accueil du site web.</strong>';

$_lang['setting_failed_login'] = 'Tentatives échouées d\'identification';
$_lang['setting_failed_login_desc'] = 'Ici vous pouvez entrer le nombre de tentatives échouées d\'identification autorisées avant qu\'un utilisateur ne soit bloqué.';

$_lang['setting_login_allowed_days'] = 'Jours autorisés';
$_lang['setting_login_allowed_days_desc'] = 'Sélectionnez les jours auxquels cet utilisateur est autorisé à ce connecter.';

$_lang['setting_login_allowed_ip'] = 'Adresse IP autorisée';
$_lang['setting_login_allowed_ip_desc'] = 'Entrez l\'adresse IP depuis laquelle cet utilisateur peut se connecter. <strong>NOTE: Séparez les différentes adresses IP par une virugule (,)</strong>';

$_lang['setting_login_homepage'] = 'Page d\'accueil de connexion';
$_lang['setting_login_homepage_desc'] = 'Entrez l\'ID du document vers lequel vous souhaitez rediriger un utilisateur une fois qu\'il/elle s\'est identifié(e). <strong>NOTE: assurez-vous de l\'ID appartient à un document existant, publié et accessible par cet utilisateur !</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Version du schéma de règle d\'accès';
$_lang['setting_access_policies_version_desc'] = 'La version du système de règle d\'accès. NE PAS CHANGER.';

$_lang['setting_allow_forward_across_contexts'] = 'Autoriser la redirection dans d\'autres contextes';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Les Symlinks et les appels à l\'API modX::sendForward() peuvent rediriger les requètes vers des ressources dans d\'autres contextes.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Formulaire d\'oubli de mot de passe sur l\'écran de connexion';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Non désactive la fonction de récupération de mot de passe sur la page de connexion du manager.';

$_lang['setting_allow_tags_in_post'] = 'Autoriser les tags HTML en POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Désactivé, toutes les actions POST à l\'intérieur du manager seront débarrassées de tout tags. Modx recommande de laisser cette option activée.';

$_lang['setting_archive_with'] = 'Forcer l\'archivage PCLZip';
$_lang['setting_archive_with_desc'] = 'Utiliser PCLZip à la place de ZipArchive en tant qu\'extenssion de zip. Activer cette option si vous obtenez des erreurs d\'extraction ou avez des problèmes de décompression dans le gestionnaire de paquets.';

$_lang['setting_auto_menuindex'] = 'Index de menu par défaut';
$_lang['setting_auto_menuindex_desc'] = 'Sélectionnez \'Oui\' pour activer l\'incrémentation automatique de l\'index de menu par défaut.';

$_lang['setting_auto_check_pkg_updates'] = 'Vérification automatique des mises à jour de package';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Si \'Oui\' est sélectionné, MODX vérifiera automatiquement la présence de mise à jour des packages dans le gestionnaire de packages. Cela ralentie le chargement de la grille.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Expiration du cache pour les vérifications automatiques de mise à jour de package';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Valeur en minutes que le gestionnaire de package gardera en cache les résultats de recherche de mise à jour.';

$_lang['setting_allow_multiple_emails'] = 'Autoriser les doublons d\'emails des utilisateurs';
$_lang['setting_allow_multiple_emails_desc'] = 'Activé, cela permet aux utilisateurs de partager la même adresse email.';

$_lang['setting_automatic_alias'] = 'Création automatique d\'alias';
$_lang['setting_automatic_alias_desc'] = 'Sélectionnez \'Oui\' pour que le système génère automatiquement un alias basé sur le titre de la page lors de son enregistrement.';

$_lang['setting_base_help_url'] = 'URL de base de l\'aide';
$_lang['setting_base_help_url_desc'] = 'L\'URL de base à partir de laquelle la construction de liens d\'aide se fait (en haut à droite des pages dans le manager).';

$_lang['setting_blocked_minutes'] = 'Durée de blocage';
$_lang['setting_blocked_minutes_desc'] = 'Entrez ici le nombre de minutes qu\'un utilisateur sera bloqué s\'il atteint le nombre maximal de tentative de connexions. Veuillez n\'entrer qu\'un nombre (pas de virgule, espace, etc.)';

$_lang['setting_cache_action_map'] = 'Activer la mise en cache de "plan d\'action"';
$_lang['setting_cache_action_map_desc'] = 'Activé, les actions (ou plans de contrôleurs) seront mis en cache pour réduire le temps de chargement des pages du manager.';

$_lang['setting_cache_context_settings'] = 'Activer la mise en cache de la configuration des contextes';
$_lang['setting_cache_context_settings_desc'] = 'Activé, la configuration des contextes sera mis en cache pour réduire les temps de chargement.';

$_lang['setting_cache_db'] = 'Activer la mise en cache de la base de données';
$_lang['setting_cache_db_desc'] = 'Activé, les objets et résultats bruts des requêtes SQL sont mis en cache pour réduire significativement la charge de la base de données.';

$_lang['setting_cache_db_expires'] = 'Délais d\'expiration du cache de la base de données';
$_lang['setting_cache_db_expires_desc'] = 'Cette valeur (en secondes) défini le délais de mise en cache des fichiers de résultat de la base de données.';

$_lang['setting_cache_db_session'] = 'Activer le cache des sessions dans la base de données';
$_lang['setting_cache_db_session_desc'] = 'Lorsqu\'actif ainsi que cache_db, la table des sessions sera mise en cache (in the DB result-set cache).';

$_lang['setting_cache_db_session_lifetime'] = 'Délais d\'expiration du cache des sessions';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Cette valeur (en secondes) défini le délais de mise en cache des sessions (in the DB result-set cache).';

$_lang['setting_cache_default'] = 'En cache par défaut';
$_lang['setting_cache_default_desc'] = 'Sélectionnez \'Oui\' pour mettre en cache les nouvelles ressources par défaut.';
$_lang['setting_cache_default_err'] = 'Veuillez indiquer si vous souhaitez ou non que les documents soient mis en cache par défaut..';

$_lang['setting_cache_disabled'] = 'Désactiver les options de cache global';
$_lang['setting_cache_disabled_desc'] = 'Sélectionnez \'Oui\' pour désactiver toutes les fonctions de cache de MODX. MODX recommande de ne pas désactiver le cache.';
$_lang['setting_cache_disabled_err'] = 'Veuillez indiquer si vous souhaitez ou non que le cache soit activé.';

$_lang['setting_cache_expires'] = 'Délais d\'expiration par défaut du cache';
$_lang['setting_cache_expires_desc'] = 'Cette valeur (en secondes) défini la durée de mise en cache par défaut des fichiers.';

$_lang['setting_cache_format'] = 'Format de cache à utiliser';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = sérialiser. Un des formats';

$_lang['setting_cache_handler'] = 'Classe de prise en charge du cache';
$_lang['setting_cache_handler_desc'] = 'Nom de la classe à utiliser pour la prise en charge du cache.';

$_lang['setting_cache_lang_js'] = 'Mettre en cache les chaînes JS du lexique';
$_lang['setting_cache_lang_js_desc'] = 'Activé, cela utilisera les entêtes serveur pour mettre en cache les chaînes du lexique chargées dans Javascript pour l\'interface du manager.';

$_lang['setting_cache_lexicon_topics'] = 'Mettre en cache des sujets de lexique';
$_lang['setting_cache_lexicon_topics_desc'] = 'Activé, tous les sujets du lexique seront mis en cache afin de réduire les temps de chargement des fonctionnalités d\'internationnalisation. MODX recommande vivement de laisser cette option sur \'Oui\'.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Mettre en cache le lexique des sujets autres au ceux du noyau';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Désactivé, le lexique des sujets autres que ceux du noyau ne seront pas mis en cache. C\'est utile lors du développement de vos Extras.';

$_lang['setting_cache_resource'] = 'Activer la mise en cache partiel de ressource';
$_lang['setting_cache_resource_desc'] = 'La mise en cache partiel de ressource est configurable par ressource quand cette fonction est activée. Désactiver cette fonction la désactivement globalement.';

$_lang['setting_cache_resource_expires'] = 'Temps d\'expiration pour le cache partiel des ressources';
$_lang['setting_cache_resource_expires_desc'] = 'Cette valeur (en secondes) défini la durée de mise en cache partiel des ressources.';

$_lang['setting_cache_scripts'] = 'Activer le système de cache de Script';
$_lang['setting_cache_scripts_desc'] = 'Activé, MODX mettra en cache tous les Scripts (Snippets et Plugins) "to file" pour réduire les temps de chargement. MODX recommande de laisser \'Oui\' activé.';

$_lang['setting_cache_system_settings'] = 'Activer le système de cache de la configuration';
$_lang['setting_cache_system_settings_desc'] = 'Activé, les options de configurations seront mis en cache pour réduire les temps de chargement. MODX recommande de laisser activé.';

$_lang['setting_clear_cache_refresh_trees'] = 'Rafraîchir l\'arborescence lors de la purge du cache';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Rafraîchie l\'arborescence après une purge du cache.';

$_lang['setting_compress_css'] = 'Utitliser des CSS compressés';
$_lang['setting_compress_css_desc'] = 'Lorsque cei est activé, MODX utilise une version compressée de ses feuilles de style dans l\'interface du manager. Ceci réduit grandement les temps de chargement et d\'éxécution au sein du manager. Désactivez cette option seulement si vous modifiez des éléments du noyau.';

$_lang['setting_compress_js'] = 'Utilisation de librairies Javascript compressées';
$_lang['setting_compress_js_desc'] = 'Quand ceci est activé, MODX utilisera une version compressée de ses librairies Javascript dans l\'interface du manager. Désactivez seulement si vous modifiez des élements du coeur/noyau/base.';

$_lang['setting_compress_js_groups'] = 'Grouper les compressions JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Grouper la compression des fichiers JavaScript du manager de MODX. Activez cette option si vous utilisez Suhosin ou tout autre facteur imposant des limites.';

$_lang['setting_compress_js_max_files'] = 'Seuil maximal de compression des fichiers JavaScript';
$_lang['setting_compress_js_max_files_desc'] = 'Nombre maximal de fichiers JavaScript que MODX essaiera de compresser en une seule passe, lorsque compress_js est activé. Utilisez un petit nombre si vous rencontrez des problèmes avec Google Minify dans le manager.';

$_lang['setting_concat_js'] = 'Utiliser les librairies Javascript concaténées';
$_lang['setting_concat_js_desc'] = 'Quand ceci est activé, MODX utilisera une version concaténée de ses librairies Javascript dans l\'interface du manager. Ceci réduit grandement le chargement et le temps d\'éxécution au sein du manager. Désactivez seuelement si vous modifiez des éléments du coeur/noyau/base.';

$_lang['setting_container_suffix'] = 'Suffixe de conteneur';
$_lang['setting_container_suffix_desc'] = 'Le suffixe à ajouter aux ressources conteneurs avec l\'utilisation des URLs simples.';

$_lang['setting_context_tree_sort'] = 'Activer le classement des contextes dans l\'arborescence';
$_lang['setting_context_tree_sort_desc'] = 'Activé, les contextes seront affichés dans l\'arborescence par ordre alphabétique.';
$_lang['setting_context_tree_sortby'] = 'Champ de classement des contextes';
$_lang['setting_context_tree_sortby_desc'] = 'Le champ à utiliser pour classer les contextes dans l\'arborescence (si activé).';
$_lang['setting_context_tree_sortdir'] = 'Direction de classement des contextes';
$_lang['setting_context_tree_sortdir_desc'] = 'L\'ordre de classement des contextes (si activé).';

$_lang['setting_cultureKey'] = 'Langue';
$_lang['setting_cultureKey_desc'] = 'Sélectionnez la langue pour tous les contextes "non-manager", web inclus.';

$_lang['setting_date_timezone'] = 'Fuseau horaire par défaut';
$_lang['setting_date_timezone_desc'] = 'Contrôle le fuseau horaire par défaut de PHP (timezone). Si ce paramètre et vide ainsi que le paramètre date.timezone de PHP, UTC sera utilisé.';

$_lang['setting_debug'] = 'Debug';
$_lang['setting_debug_desc'] = 'Activez ou désactiver le debugging dans MODX et/ou définissez le niveau de error_reporting de PHP. \'\' = utiliser la valeur actuelle de error_reporting, \'0\' = false (error_reporting = 0), \'1\' = true (error_reporting = -1), ou toute autre valeur de error_reporting valide (un entier).';

$_lang['setting_default_content_type'] = 'Type de contenu par défaut';
$_lang['setting_default_content_type_desc'] = 'Indiquez le type de contenu par défaut à utiliser pour les nouvelles ressources. Vous pourrez toujours sélectionner un type de contenu différent lorsque vous éditerez une ressource; ce paramètre pré-selectionne seulement le type de contenu.';

$_lang['setting_default_duplicate_publish_option'] = 'Option de publication lors de la duplication de ressource';
$_lang['setting_default_duplicate_publish_option_desc'] = 'L\'option sélectionnée par défaut lors de la duplication d\'une ressource. Peut être « unpublish » pour dépublier toutes les ressources dupliquées, « publish » pour publier toutes les ressources dupliquées ou « preserve » pour conserver le status de la ressource originale (sur la ressource dupliquée).';

$_lang['setting_default_media_source'] = 'Media Source par défaut';
$_lang['setting_default_media_source_desc'] = 'Media Source à charger par défaut.';

$_lang['setting_default_template'] = 'Modèle par défaut';
$_lang['setting_default_template_desc'] = 'Sélectionnez le modèle par défaut que vous souhaitez utiliser pour les nouvelles ressources. Vous pouvez toujours sélectionner un modèle différent dans l\'éditeur de ressource, cette option pré-sélectionne seuelement un de vos modèles pour vous.';

$_lang['setting_default_per_page'] = 'Defaut par page';
$_lang['setting_default_per_page_desc'] = 'Nombre de résultats à afficher dans les grilles du manager.';

$_lang['setting_editor_css_path'] = 'Chemin vers le fichier CSS';
$_lang['setting_editor_css_path_desc'] = 'Indiquez le chemin vers votre fichier CSS que vous souhaitez utiliser à l\'intérieur de votre éditeur de texte riche. Le meilleur moyen est d\'entrer le chemin depuis la racine de votre serveur, par exemple : /assets/site/style.css. Si vous ne souhaitez pas charger une feuille de style dans votre éditeur de texte riche, laissez ce champs vide.';

$_lang['setting_editor_css_selectors'] = 'Sélecteurs CSS pour l\'éditeur';
$_lang['setting_editor_css_selectors_desc'] = 'Une liste des sélecteurs CSS, séparas par une virguel, pour l\'éditeur de texte riche.';

$_lang['setting_emailsender'] = 'Adresse email d\'inscription';
$_lang['setting_emailsender_desc'] = 'Ici vous pouvez indiquer l\'adresse email utilisée lors de l\'envoi des noms d\'utilisateurs et mots de passe des utilisateurs.';
$_lang['setting_emailsender_err'] = 'Veuillez indiquer l\'adresse email d\'administration.';

$_lang['setting_emailsubject'] = 'Sujet de l\'email d\'inscription';
$_lang['setting_emailsubject_desc'] = 'Ligne de sujet par défaut de l\'email quand un utilisateur s\'est enregistré.';
$_lang['setting_emailsubject_err'] = 'Veuillez indiquer un sujet pour l\'email d\'inscription.';

$_lang['setting_enable_dragdrop'] = 'Activer le Drag/Drop dans l\'arborescence de ressources/éléments';
$_lang['setting_enable_dragdrop_desc'] = 'Active ou désactive le glisser-déposer dans l\'arboresence de ressources et d\'éléments.';

$_lang['setting_error_page'] = 'Page d\'erreur';
$_lang['setting_error_page_desc'] = 'Entrez l\'ID du document que vous souhaitez afficher aux utilisateurs qui demandent un document qui n\'existe pas. <strong>NOTE: assurez-vous que cet ID appartienne à un document publié existant!</strong>';
$_lang['setting_error_page_err'] = 'Veuillez indiquer un ID de document pour la page d\'erreur.';

$_lang['setting_extension_packages'] = 'Extension Packages';
$_lang['setting_extension_packages_desc'] = 'Une array JSON de paquets à charger lors de l\'instanciation de MODX, au format [{"packagename":{path":"path/to/package"},{"anotherpkg":{"path":"path/to/otherpackage"}}]';

$_lang['setting_failed_login_attempts'] = 'Tentatives échouées de connexion';
$_lang['setting_failed_login_attempts_desc'] = 'Nombre de tentatives échouées de connexion qu\'un utilisateur est autorisé à commetre avant d\'être \'bloqué\'.';

$_lang['setting_fe_editor_lang'] = 'Langue de l\'éditeur de Front-end';
$_lang['setting_fe_editor_lang_desc'] = 'Choisissez une langue à utiliser pour éditer dans le front-end.';

$_lang['setting_feed_modx_news'] = 'URL du flux d\'actualités de MODX';
$_lang['setting_feed_modx_news_desc'] = 'Défini l\'URL du flux d\'actualités du panneau de MODX dans le manager.';

$_lang['setting_feed_modx_news_enabled'] = 'Actualités de MODX activées';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Si \'Non\' est sélectionné, MODX cachera le flux d\'actualités de la page d\'accueil du manager.';

$_lang['setting_feed_modx_security'] = 'URL du flux des bulletins de sécurité de MODX';
$_lang['setting_feed_modx_security_desc'] = 'Défini l\'URL du panneau de flux des bulletins de sécurité de MODX dans le manager.';

$_lang['setting_feed_modx_security_enabled'] = 'Activer le flux RSS des informations de Sécurité MODX';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Si \'Non\' est sélectionné, MODX mettra en cache le flux de sécurité de la page d\'accueil du manager.';

$_lang['setting_filemanager_path'] = 'Chemin du gestionnaire de fichier';
$_lang['setting_filemanager_path_desc'] = 'Souvent, IIS ne détermine pas correctement l\'option document_root, qui est utilisé par le gestionnaire de fichier pour déterminer ce que vous pouver explorer. Si vous avez des problèmes en utilisant le gestionnaire de fichier, assurez-vous que ce chemin pointe vers la racine de votre installation MODX.';

$_lang['setting_filemanager_path_relative'] = 'Le chemin vers le gestionnaire de fichier est-il relatif?';
$_lang['setting_filemanager_path_relative_desc'] = 'Si votre paramètre filemanager_path est relatif à base_path, veuillez sélectionner Oui. Si votre paramètre filemanager_path est en dehors du docroot, veuillez sélectionner non.';

$_lang['setting_filemanager_url'] = 'Adresse du gestionnaire de fichier';
$_lang['setting_filemanager_url_desc'] = 'Optionel. Définissez ce paramètre pour utiliser une URL spécifique afin d\'accèder aux fichiers depuis le gestionnaire de fichier de MODX (utile si vous avez changé filemanager_path pour un répertoire en dehors de la racine web de MODX). Assurez-vous que ce paramètre soit l\'URL du paramètre filemanager_path. Laissez vide pour que MODX essaie de déterminer ce paramètre.';

$_lang['setting_filemanager_url_relative'] = 'URL du gestionnaire de ficher relative?';
$_lang['setting_filemanager_url_relative_desc'] = 'Si votre paramètre filemanager_url est relatif à base_url, veuillez alors sélectionner oui. Si votre paramètre filemanager_url est en dehors de la racine web, sélectionnez non.';

$_lang['setting_forgot_login_email'] = 'Forgot Login Email';
$_lang['setting_forgot_login_email_desc'] = 'Le modèle de l\'email qui est envoyé quand un utilisateur a oublié ses identifiants et/ou mot de passe de MODX.';

$_lang['setting_form_customization_use_all_groups'] = 'Utiliser la personnalisation des formulaires pour tous les groupes';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Lorsque cette option est activée, la personnalisation des formulaires sera appliquée même lorsque le groupe ne sera pas le groupe principal de l\'utilisateur. Sinon, elle ne s\'appliquera que lorsque l\'utilisateur aura ce groupe d\'utilisateur en tant que groupe principal. Note : activer cette option peut entrainer des conflits entre les sets.';

$_lang['setting_forward_merge_excludes'] = 'Faire suivre exclue les champs "fusionnés"';
$_lang['setting_forward_merge_excludes_desc'] = 'Un lien symbolique qui fusionne les valeurs des champs non vides vers les valeurs de la ressource cible; utiliser une liste "d\'exclusions", séparées par des virgules, évite que les champs indiqués soient écrasés par le lien symbolique.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Alias en minuscules';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Défini si l\'alias des ressources doit être seulement en minuscules ou non.';

$_lang['setting_friendly_alias_max_length'] = 'Longeur maximale d\'alias';
$_lang['setting_friendly_alias_max_length_desc'] = 'Nombre maximum de caractères autorisés dans les alias de ressource. Zero égal illimité.';

$_lang['setting_friendly_alias_restrict_chars'] = 'Méthode de restriction des caractères d\'alias';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'La méthode utilisée pour restreindre les caractères utilisés dans les alias de ressource. « Pattern » autorise l\'utilisation d\'un modèle RegEx, « legal » autorise tout caractère valide, « alpha » autorise uniquement les lettres de l\'alphabet, et « alphanumeric » autorise uniquement les lettres et nombres.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Modèle de restriction de caractère d\'alias';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Un modèle valide RegEx pour restreindre les caractères utilisés dans les alias de ressource.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Enlever les tags des alias';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Défini si les tags doivent être enlevés des alias de ressource.';

$_lang['setting_friendly_alias_translit'] = 'FURL Alias Transliteration';
$_lang['setting_friendly_alias_translit_desc'] = 'La méthode de translitération à utiliser sur l\'alias des ressources. Vide ou "none" est le réglage par défaut qui n\'utilise pas la translitération. D\'autre valeurs possibles sont "iconv" (si disponible) ou le nom d\'un tableau de translitération fourni par une classe de translitération tierce.';

$_lang['setting_friendly_alias_translit_class'] = 'Classe de service de translitération';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Une classe optionnelle de service qui fourni des services de translitération pour le filtrage/génération des alias.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Chemin de la classe de service de translitération d\'alias';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'The model package location where the FURL Alias Transliteration Service Class will be loaded from.';

$_lang['setting_friendly_alias_trim_chars'] = 'Caractères de bord';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Les caractères à supprimer de la fin des alias de ressource fournis.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Séparateur de mot';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Le séparateur de mot préféré pour les alias d\'URL simples.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Séparateurs de mot';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Les séparateurs de mot préférés pour les alias d\'URL simples. Ces caractères seront convertis et unifiés pour les alias d\'URL simples.';

$_lang['setting_friendly_urls'] = 'Utiliser les URLs simples';
$_lang['setting_friendly_urls_desc'] = 'Ceci vous autorise à utiliser les URLs simple (pour les moteurs de recherche). Veuillez noter que cette option ne fonctionne que pour les installations MODX tournant avec Apache et que vous aurez besoin d\'écrire un fichier .htaccess pour que cela fonctionne. Regardez le fichier .htaccess inclu dans la distribution pour plus d\'informations.';
$_lang['setting_friendly_urls_err'] = 'Veuillez indiquer si vous souhaitez utiliser les URLs simples';

$_lang['setting_friendly_urls_strict'] = 'Utiliser les URLs simplifiées strictes';
$_lang['setting_friendly_urls_strict_desc'] = 'Lorsque les URLs simplifiées sont activées, cette option force les requêtes non canonical correspondant à une ressource à utiliser une redirection 301 vers l\'URI canonical de cette ressource. ATTENTION : n\'utilisez pas cette cette option si vous utilisez des règles de rewrite (réécriture) qui ne correspondent pas au début de l\'URI canonical. Par exemple, pour une URI canonical foo/ avec une régle de rewrite personnalisée en foo/bar.html fonctionnera, mais les tentatives de réécriture de bar/foo.html en foo/ forcera la redirection vers foo/ avec cette option activée.';

$_lang['setting_global_duplicate_uri_check'] = 'Vérifier les URIs identiques dans tous les contextes';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Sélectionnez \'Oui\' pour vérifier l\'existance de doublons d\'URI dans tous les contextes. Sinon, seul le contexte dans lequel la ressource est enregistrée est vérifié.';

$_lang['setting_hidemenu_default'] = 'Ne pas afficher dans les menus par défaut';
$_lang['setting_hidemenu_default_desc'] = 'Choisissez \'oui\' pour que toutes les nouvelles ressources soient cachées des menus par défaut.';

$_lang['setting_inline_help'] = 'Afficher l\'aide sous les champs';
$_lang['setting_inline_help_desc'] = '« Oui » affiche le texte d\'aide directement sous le champs. « Non » affiche le texte d\'aide dans un tooltip.';

$_lang['setting_link_tag_scheme'] = 'Schéma de génération d\'URL';
$_lang['setting_link_tag_scheme_desc'] = 'Schéma de génération d\'URL pour le tag [[~id]]. Options disponibles <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\modX::makeUrl()">dans la documentation de l\'API</a>';

$_lang['setting_locale'] = 'Locale';
$_lang['setting_locale_desc'] = 'Définie la locale du système. Laissez vide pour utiliser celle par défaut. Consultez <a href="http://php.net/setlocale" target="_blank">la documentation PHP</a> pour plus d\'information.';

$_lang['setting_lock_ttl'] = 'Durée de vie du lock';
$_lang['setting_lock_ttl_desc'] = 'Nombre de secondes qu\'une ressource restera vérouillée lorsque l\'utilisateur l\'éditant est inactif.';

$_lang['setting_log_level'] = 'Niveau de log';
$_lang['setting_log_level_desc'] = 'Le niveau par défaut de log; plus le niveau est bas, moins les informations seront log. Options disponibles : 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO), and 4 (DEBUG).';

$_lang['setting_log_target'] = 'Cible du log';
$_lang['setting_log_target_desc'] = 'La cible par défaut où les messages de log seront écrit. Options disponibles : \'FILE\', \'HTML\', ou \'ECHO\'. Si aucune valeur n\'est spécifiée \'FILE\' est utilisé.';

$_lang['setting_mail_charset'] = 'Charset Mail';
$_lang['setting_mail_charset_desc'] = 'Le charset (par défaut défaut) pour les emails, par ex. \'iso-8859-1\' ou \'UTF-8\'';

$_lang['setting_mail_encoding'] = 'Encodage Mail';
$_lang['setting_mail_encoding_desc'] = 'Défini l\'encodage du message. Les choix sont "8bit", "7bit", "binary", "base64", et "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Utiliser un SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Si activé, MODX essaiera d\'utiliser le SMTP pour les fonctions mail.';

$_lang['setting_mail_smtp_auth'] = 'Identification SMTP';
$_lang['setting_mail_smtp_auth_desc'] = 'Défini l\'identification SMTP. Utilise les options mail_smtp_user et mail_smtp_password.';

$_lang['setting_mail_smtp_helo'] = 'Message Helo du SMTP';
$_lang['setting_mail_smtp_helo_desc'] = 'Défini les message HELO du SMTP (nom d\'hôte par défaut).';

$_lang['setting_mail_smtp_hosts'] = 'Hôtes SMTP';
$_lang['setting_mail_smtp_hosts_desc'] = 'Défini les hôtes SMTP. Tous les hôtes doivent être séparés par des points-virgules. Vous pouvez également indiquer un port différent  pour chaque hôte en utilisant ce format: [hôte:port] (exemple "smtp1.exemple.com:25;smtp2.exemple.com"). Les hôtes seront testés dans l\'ordre.';

$_lang['setting_mail_smtp_keepalive'] = 'Keep-Alive SMTP';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Évite que la connexion SMTP soit fermée après chaque envoie d\'email. Non recommandé.';

$_lang['setting_mail_smtp_pass'] = 'Mot de passe SMTP';
$_lang['setting_mail_smtp_pass_desc'] = 'Le mot de passe d\'identifaction au serveur SMTP.';

$_lang['setting_mail_smtp_port'] = 'Port SMTP';
$_lang['setting_mail_smtp_port_desc'] = 'Défini le port du serveur SMTP par défaut.';

$_lang['setting_mail_smtp_prefix'] = 'Préfixe de connexion SMTP';
$_lang['setting_mail_smtp_prefix_desc'] = 'Défini le préfixe de connexion. Les choix sont "", "ssl" ou "tls"';

$_lang['setting_mail_smtp_single_to'] = 'Simple destinataire SMTP';
$_lang['setting_mail_smtp_single_to_desc'] = 'Donne la possiblité d\'effectuer un champ TO individuel, au lieu d\'envoyer à toutes les adresses TO.';

$_lang['setting_mail_smtp_timeout'] = 'Timeout SMTP';
$_lang['setting_mail_smtp_timeout_desc'] = 'Défini le délais de timeout en second du serveur SMTP. Cette fonction ne fonctionne pas sur win32.';

$_lang['setting_mail_smtp_user'] = 'Utilisateur SMTP';
$_lang['setting_mail_smtp_user_desc'] = 'L\'utilisateur pour s\'identifier auprès du SMTP.';

$_lang['setting_manager_direction'] = 'Orientation du texte du manager';
$_lang['setting_manager_direction_desc'] = 'Choisissez l\'orientation dans laquelle la texte sera affiché dans le manager, de gauche à droite ou de droite à gauche.';

$_lang['setting_manager_date_format'] = 'Format de date du manager';
$_lang['setting_manager_date_format_desc'] = 'Le format de chaine de caratères, au format PHP date(), pour les dates représentées dans le manager.';

$_lang['setting_manager_favicon_url'] = 'URL de favicon du manager';
$_lang['setting_manager_favicon_url_desc'] = 'Utilise le favicon indiqué pour le manager de MODX. Doit être relatif au répertoire manager/ ou une URL absolue.';

$_lang['setting_manager_html5_cache'] = 'Utiliser le cache local HTML5 dans le manager';
$_lang['setting_manager_html5_cache_desc'] = 'Expérimental. Active le cache local HTML5 pour le manager. Recommendé uniquement avec l\'utilisation de navigateurs récents (supportant le cache HTML5).';

$_lang['setting_manager_js_cache_file_locking'] = 'Activer le verrouillage des fichiers JS/CSS du manager';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Mettre en cache le verrouillage des fichiers. Sélectionnez non si votre système de fichier est NFS.';
$_lang['setting_manager_js_cache_max_age'] = 'Durée du cache des JS/CSS compressés';
$_lang['setting_manager_js_cache_max_age_desc'] = 'Durée maximale du cache navigateur pour les fichiers CSS/JS du manager, en secondes. Au delà de ctte période, le navigateur enverra un GET "conditionnel". Utilisez une valeur élevée pour moins de traffic.';
$_lang['setting_manager_js_document_root'] = 'Compression JS/CSS à la racine';
$_lang['setting_manager_js_document_root_desc'] = 'Si votre serveur ne prend pas en compte la variable serveur DOCUMENT_ROOT, définissez-la ici pour activer la compression CSS/JS du manager. Ne configurez cette option que si vous savez ce que vous faites.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Activer la compression zlib pour les JS/CSS du manager';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Autorise ou non la compression zlib sur les fichiers CSS/JS du manager. N\'activez pas cette fonction à moins d\'être sûr que votre configuration PHP autorise le changement de la variable zlib.output_compression à 1. MODX recommande de laisser cette fonction désactivée..';

$_lang['setting_manager_lang_attribute'] = 'Attributs HTML et XML du manager';
$_lang['setting_manager_lang_attribute_desc'] = 'Entrez le code de langue qui correspond au mieux à la langue choisie pour votre manager, ceci assurera que le navigateur puisse afficher le contenu de la meilleur façon possible pour vous.';

$_lang['setting_manager_language'] = 'Langue du manager';
$_lang['setting_manager_language_desc'] = 'Sélectionnez la langue pour le gestionnaire de contenu de MODX.';

$_lang['setting_manager_login_url_alternate'] = 'URL alternative de connexion au manager';
$_lang['setting_manager_login_url_alternate_desc'] = 'Une URL alternative vers laquelle envoyer les utilisateurs non identifiés au manager. Le formulaire de connexion doit identifier l\'utilisateur au contexte « mgr » pour fonctionner.';

$_lang['setting_manager_login_start'] = 'Démarrage après identification au manager';
$_lang['setting_manager_login_start_desc'] = 'Entrez l\'ID du document vers lequel vous souhaitez envoyer un utilisateur après sa connexion au manager. <strong>NOTE: assurez-vous que l\'ID que vous avez entré appartient à un document existant, publié et accessible par cet utilisateur!</strong>';

$_lang['setting_manager_theme'] = 'Thème du manager';
$_lang['setting_manager_theme_desc'] = 'Sélectionnez le thème du gestionnaire de contenu.';

$_lang['setting_manager_time_format'] = 'Format de date du manager';
$_lang['setting_manager_time_format_desc'] = 'Le format de chaine de caratères, au format PHP date(), pour les options représentées dans le manager.';

$_lang['setting_manager_use_tabs'] = 'Utiliser les onglets dans l\'agencement du manager';
$_lang['setting_manager_use_tabs_desc'] = 'Si oui, le manager utilisera les onglets pour afficher les panneaux de contenu. Sinon il utilisera les "portails".';

$_lang['setting_manager_week_start'] = 'Début de semaine';
$_lang['setting_manager_week_start_desc'] = 'Indiquez le jour débutant la semaine. Utilisez 0 (ou laissez vide) pour dimanche, 1 pour lundi et ainsi de suite…';

$_lang['setting_modRequest.class'] = 'Classe de prise en charge de requête';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_default_sort'] = 'Listing des fichiers';
$_lang['setting_modx_browser_default_sort_desc'] = 'L\'ordre d\'affichage des fichiers par défaut lors de l\'utilisation de la popup du navigateur de fichier dans le manager. Les valeurs acceptées sont : name, size, lastmod (date de modification).';

$_lang['setting_modx_charset'] = 'Encodage de caractère';
$_lang['setting_modx_charset_desc'] = 'Veuillez indiquer quel encodage de caractère vous souhaitez utiliser. Veuillez noter que MODX a été testé certains encodages mais pas tous. Pour la plupart des langues, l\'option par défaut UTF-8 est préférable.';

$_lang['setting_new_file_permissions'] = 'Permissions des nouveaux fichiers';
$_lang['setting_new_file_permissions_desc'] = 'Lorsque vous uploadez un nouveau fichier dans le gestionnaire de fichier, celui-ci essaiera de changer les permissions du fichier à celles définies dans cette option. Il se peut que cela ne fonctionne pas sur certaines configurations, telles qu\'avec IIS, dans quel cas vous devrez changer manuellement les permissions.';

$_lang['setting_new_folder_permissions'] = 'Permissions des nouveaux répertoires';
$_lang['setting_new_folder_permissions_desc'] = 'Lorsque vous créez un nouveau répertoire dans le gestionnaire de fichier, ceci-ci essaiera de changer les permissions du répertoire à celles entrées dans cette option. Il se peut que cela ne fonctionne pas sur certaines configurations, telles qu\'avec IIS, dans quel cas vous devrez changer manuellement les permissions.';

$_lang['setting_password_generated_length'] = 'Longueur des mots de passe générés automatiquement';
$_lang['setting_password_generated_length_desc'] = 'La longueur des mots de passe utilisateur générés automatiquement.';

$_lang['setting_password_min_length'] = 'Longueur minimale du mot de passe';
$_lang['setting_password_min_length_desc'] = 'Longueur minimale du mot de passe des utilisateurs.';

$_lang['setting_principal_targets'] = 'ACL à charger';
$_lang['setting_principal_targets_desc'] = 'Personnalise les ACL à charger pour les utilisateur MODX.';

$_lang['setting_proxy_auth_type'] = 'Type d\'identification du proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Supporte soit BASIC ou NTLM.';

$_lang['setting_proxy_host'] = 'Hôte du proxy';
$_lang['setting_proxy_host_desc'] = 'Si votre serveur utilise un proxy, indiquez ici son nom de domaine pour activer les fonctions de MODX qui peuvent utiliser le serveur proxy, comme le gestionnaire de package.';

$_lang['setting_proxy_password'] = 'Mot de passe du proxy';
$_lang['setting_proxy_password_desc'] = 'Le mot de passe nécessaire pour vous identifier sur votre serveur proxy.';

$_lang['setting_proxy_port'] = 'Port du Proxy';
$_lang['setting_proxy_port_desc'] = 'Port de votre serveur proxy.';

$_lang['setting_proxy_username'] = 'Nom d\'utilisateur du Proxy';
$_lang['setting_proxy_username_desc'] = 'Le nom d\'utilisateur pour vous identifier sur votre serveur proxy.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb autorise des sources en dehors de la racine web';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Indique si le chemin source peut être en dehors de la racine web. Ce paramètre est utile pour déployer des contextes multiples avec plusieurs virtuals hosts.';

$_lang['setting_phpthumb_cache_maxage'] = 'Durée maximale pour le cache de phpThumb';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Supprime les vignettes mis en cache qui n\'ont pas été accédées depuis plus de X jours.';

$_lang['setting_phpthumb_cache_maxsize'] = 'Taille maximale du cache de phpThumb';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Supprime les vignettes les plus anciennes quand la taille du cache devient plus important que X Mb.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'Taille maximale du cache de fichiers phpThumb';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Supprime les vignettes les plus anciennes quand le cache contient plus de X fichiers.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'Mettre en cache les fichiers sources';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Choisissez de mettre en cache ou pas les fichiers sources quand ils sont chargés. Désactiver cette option est recommandé.';

$_lang['setting_phpthumb_document_root'] = 'Répertoire racine pour PHPThumb';
$_lang['setting_phpthumb_document_root_desc'] = 'Utilisez ceci si vous rencontrez des problèmes avec la variable serveur DOCUMENT_ROOT, ou si vous obtenez des erreurs avec OutputThumbnail ou !is_resource. Indiquez le chemin absolu du répertoire racine que vous souhaitez utiliser. Si ce champs reste vide, MODX utilisera la variable de serveur DOCUMENT_ROOT.';

$_lang['setting_phpthumb_error_bgcolor'] = 'Couleur de fond d\'erreur phpThumb';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Valeure hexadecimale, sans le #, indiquant une couleur de fond pour les erreurs de rendu.';

$_lang['setting_phpthumb_error_fontsize'] = 'Taille de police d\'erreur phpThumb';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Valeure en em indiquant la taille de police à utiliser pour le texte apparaissant dans les erreurs de rendu de phpThumb.';

$_lang['setting_phpthumb_error_textcolor'] = 'Couleur de police d\'erreur phpThumb';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Valeure hexadecimale, sans le #, indiquant une couleur de police pour le texte apparaissant dans les erreurs de rendu.';

$_lang['setting_phpthumb_far'] = 'Forcer l\'aspect-ratio de phpThumb';
$_lang['setting_phpthumb_far_desc'] = 'Les options par défaut d\'éloignement lorsque phpThumb est utilisé avec MODX. « C » par défaut pour forcer l\'aspect-ratio vers le centre.';

$_lang['setting_phpthumb_imagemagick_path'] = 'Chemin ImageMagick pour phpThumb';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Optionnel. Définissez le chemin d\'un alternatif à ImageMagick pour générer des miniatures avec phpThumb, si ce n\'est déjà défini dans PHP.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Hotlinking désactivé';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Les serveurs distants sont autorisés dans le paramètre src, à moins que vous ne désactiviez le hotlinking dans phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Hotlinking Erase Image';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Indique si une image générée depuis un serveur distant doit être effacée lorsque le site n\'est pas autorisé.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Hotlinking message non autorisé';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Un message qui est affiche à la place de la miniature quand une tentative d\'hotlinking est refusée.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Hotlinking domaines valides';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Une liste de noms de domaines (séparés par des virgules) autorisés dans les URLs src';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb lien offsite désactivés';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Désactive la possiblité des tierces à utiliser phpThumb pour afficher des images sur leurs propres sites.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb lien offsite, effacement d\'image';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Indique si une image liée depuis un site distant doit être effacée lorsque le site n\'est pas autorisé.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb lien offsite avec Referrer';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Tous les essais de liens "offsite" sans une entête correcte de referrer seront rejetés.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb lien offsite, message non autorisé';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Message à afficher à la place de la miniature lorsqu\'une tentative de lien offsite est rejetée.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb lien offsite, domaines valides';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Une liste de noms de domaines (séparés par des virgules) autorisés à utiliser les liens offsite.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb lien offsite, source de filigrane';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Optionnel. Un chemin (système) valide vers un fichier à utiliser en tant que filigrane quand vos images sont affichées offsite par phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb zoom-recadrage';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Les options zc par défaut lorsque phpThumb est utilisé dans MODX. « 0 » par défaut pour éviter le zoom et le recadrage.';

$_lang['setting_publish_default'] = 'Publié par défaut';
$_lang['setting_publish_default_desc'] = 'Sélectionnez \'Oui\' pour définir les nouvelles ressources comme publiées par défaut.';
$_lang['setting_publish_default_err'] = 'Veuillez indiquer si vous désirez ou non que vos documents soient publiés apr défaut.';

$_lang['setting_rb_base_dir'] = 'Chemin des ressources';
$_lang['setting_rb_base_dir_desc'] = 'Entrez le chemin physique du navigateur de ressource. Cette option est généralement générée automatique. Cependant, si vous utilisez IIS, MODX peut ne pas trouver le chemin d\'accès par lui même, entrainant un message d\'erreur dans la navigateur de ressource. Dans ce cas, veuillez entrer le chemin d\'accès du répertoire d\'images (comme vous le verriez dans l\'explorateur Windows). <strong>NOTE:</strong> Le répertoire de ressource doit contenir les sous-répertoires images, files, flash et media afin que le navigateur de ressource fonctionne correctement.';
$_lang['setting_rb_base_dir_err'] = 'Veuillez indiquer le répertoire de base du navigateur de ressource.';
$_lang['setting_rb_base_dir_err_invalid'] = 'soit ce répertoire de ressource n\'existe pas, soit il ne peut être accédé. Veuillez indiquer un répertoire valide ou ajuster les droits d\'accès de ce répertoire.';

$_lang['setting_rb_base_url'] = 'URL des ressources';
$_lang['setting_rb_base_url_desc'] = 'Entrez le chemin virtuel d\'accès au répertoire des ressources. Cette option est en générale définie automatiquement. Cependant, si vous utilisez IIS, MODX peut ne pas trouver l\'URL par lui même, entrainant un message d\'erreur dans le navigateur de ressource. Dans ce cas, vous pouvez entrez l\'URL du répertoire d\'images (l\'URL telle que vous la rentreriez dans Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Veuillez indiquer l\'URL de base du navigateur de ressource.';

$_lang['setting_request_controller'] = 'Nom de fichier du contrôleur de requête';
$_lang['setting_request_controller_desc'] = 'Le nom de fichier du contrôleur principale de requête par lequel MODX est chargé. La plupart des utilisateurs peuvent laisser index.php.';

$_lang['setting_request_method_strict'] = 'Méthode de requête sctricte';
$_lang['setting_request_method_strict_desc'] = 'Lorsque cette option est activée, les requêtes faites avec le paramètre d\'ID seront ignorées si les URLs simples sont activées, et inversement, les requêtes faites avec le paramètre d\'alias seront ignorées si les URLs simples sont désactivées.';

$_lang['setting_request_param_alias'] = 'Paramètre de requête d\'alias';
$_lang['setting_request_param_alias_desc'] = 'Nom du paramètre GET pour identifier les alias des ressources quand les FURLs sont utilisées.';

$_lang['setting_request_param_id'] = 'Paramètre de requête ID';
$_lang['setting_request_param_id_desc'] = 'Nom du paramètre GET pour identifier les IDs des ressources quand les FURLs ne sont pas utilisées.';

$_lang['setting_resolve_hostnames'] = 'Résolution des noms de domaines';
$_lang['setting_resolve_hostnames_desc'] = 'Souhaitez-vous que MODX essai de résoudre les noms de domaines de vos visiteurs quand ils visitent votre site? Résoudre les noms de domaines peut créer une charge supplémentaire du serveur, mais vos visiteurs ne le ressentiront pas.';

$_lang['setting_resource_tree_node_name'] = 'Resource Tree Node Field';
$_lang['setting_resource_tree_node_name_desc'] = 'Indiquez le champ de ressource à utiliser lors de l\'affichage des nodes dans l\'arborescence. Pagetitle par défaut, mais n\'importe quel champ de ressource peut être utilisé, tels que menutitle, alias, longtitle, etc.';

$_lang['setting_resource_tree_node_tooltip'] = 'Champs des infos-bulles de ressource';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Indiquez le champs de ressource à utiliser lors de l\'affichage des nodes de l\'arborescence de ressources. Tous les champs de ressource peuvent être utilisés, tels que menutitle, alias, longtitle, etc. Si ce champs reste vide, le champs longtitle sera utilisé ainsi que description en dessous.';

$_lang['setting_richtext_default'] = 'Richtext Default';
$_lang['setting_richtext_default_desc'] = 'Sélectionnez \'Oui\' pour définir les nouvelles ressources comme utilisant un éditeur riche de texte par défaut.';

$_lang['setting_search_default'] = 'Recherchable par Défaut';
$_lang['setting_search_default_desc'] = 'Sélectionnez \'Oui\' pour définir les nouvelles ressources comme recherchables par défaut.';
$_lang['setting_search_default_err'] = 'Veuillez indiquer si vous désirez ou non que vos documents soient recherchables par défaut.';

$_lang['setting_server_offset_time'] = 'Décalage horaire du serveur';
$_lang['setting_server_offset_time_desc'] = 'Indiquez le nombre d\'heures de décalage entre vous et votre serveur.';

$_lang['setting_server_protocol'] = 'Type de Serveur';
$_lang['setting_server_protocol_desc'] = 'Si votre site utilise une connexion sécurisée (https), veuillez l\'indiquer ici.';
$_lang['setting_server_protocol_err'] = 'Veuillez indiquer si votre site utilise ou non une connexion sécurisée.';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Domaine de session de coockie';
$_lang['setting_session_cookie_domain_desc'] = 'Utilisez cette option pour personnaliser le domaine de session de coockie.';

$_lang['setting_session_cookie_lifetime'] = 'Durée de vie de Session de Cookie';
$_lang['setting_session_cookie_lifetime_desc'] = 'Utilisez cette option pour personnaliser la durée de vie de session de coockie (en secondes). Ceci est utilisé la durée de la session côté client quand l\'option \'se rappeler de moi\' est utilisée lors du login.';

$_lang['setting_session_cookie_path'] = 'Chemin de Session de Cookie';
$_lang['setting_session_cookie_path_desc'] = 'Utilisez cette option pour personnaliser le chemin de coockie pour identifier les sessions de coockies spécifiques au site.';

$_lang['setting_session_cookie_secure'] = 'Sessions sécurisées de Cookie';
$_lang['setting_session_cookie_secure_desc'] = 'Activez cette option pour utiliser les sessions sécurisées de coockies.';

$_lang['setting_session_gc_maxlifetime'] = 'Session Garbage Collector Max Lifetime';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Autorise la personnalisation du paramètre session.gc_maxlifetime (PHP ini) lors de l\'utilisation de \'modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Nom de classe de prise en charge des Sessions';
$_lang['setting_session_handler_class_desc'] = 'Pour les sessions de base de données gérées, utilisez \'modSessionHandler\'.  Laissez vide pour utiliser la gestion des sessions standard avec PHP.';

$_lang['setting_session_name'] = 'Nom de Session';
$_lang['setting_session_name_desc'] = 'Utilisez ces options pour personnaliser les noms de sessions utilisées dans MODX.';

$_lang['setting_settings_version'] = 'Options de Version';
$_lang['setting_settings_version_desc'] = 'Version installée de MODX.';

$_lang['setting_settings_distro'] = 'Paramètres de distribution';
$_lang['setting_settings_distro_desc'] = 'Distribution de MODX actuellement installée.';

$_lang['setting_set_header'] = 'Activer les entêtes HTTP';
$_lang['setting_set_header_desc'] = 'Activé, MODX essai de définir les entêtes HTTP pour les ressources.';

$_lang['setting_show_tv_categories_header'] = 'Afficher « Catégories » dans l\'entête de l\'onglet  des TVs';
$_lang['setting_show_tv_categories_header_desc'] = 'Activé, MODX affiche l\'entête « Catégories » au dessus du premier onglet de TV, lors de l\'édition d\'une ressource.';

$_lang['setting_signupemail_message'] = 'E-mail d\'inscription';
$_lang['setting_signupemail_message_desc'] = 'Ici vous pouvez définir le message envoyé à vos utilisateurs lorsque vous leur créer un compte et laissez MODX leur envoyer un email contenant leur nom d\'utilisateur et leur mot de passe. <br /><strong>Note:</strong> Les placeholders suivants sont remplacés par le gestionnaire de contenu lors de l\'envoi du message: <br /><br />[[+sname]] - Nom de votre site web, <br />[[+saddr]] - Adresse email de votre site internet, <br />[[+surl]] - URL de votre site, <br />[[+uid]] - Identifiant ou id d\'utilisateur, <br />[[+pwd]] - Mot de passe de l\'utilisateur, <br />[[+ufn]] - Nom complet de l\'utilisateur. <br /><br /><strong>Laissez [[+uid]] et [[+pwd]] dans l\'email ou le nom d\'utilisateur et le mot de passe ne seront pas envoyés par email et vos utilisateurs ne pourront se connecter!</strong>';
$_lang['setting_signupemail_message_default'] = 'Bonjour [[+uid]] \n\nVoici vos informations de connexion au gestionnaire de contenu pour [[+sname]] :\n\nNom d\'utilisateur: [[+uid]]\nMot de passe: [[+pwd]]\n\nUne fois connecté au gestionnaire de contenu ([[+surl]]), vous pouvez changer votre mot de passe.\n\nCordialement,\nl\'administrateur du site';

$_lang['setting_site_name'] = 'Nom du site';
$_lang['setting_site_name_desc'] = 'Entrez ici le nom de votre site.';
$_lang['setting_site_name_err']  = 'Veuillez entrer un nom de site.';

$_lang['setting_site_start'] = 'Accueil du site';
$_lang['setting_site_start_desc'] = 'Entrez ici l\'ID de la ressource que vous souhaitez utiliser comme page d\'accueil. <strong>NOTE: assurez-vous que l\'ID appartient à une ressource existante et publiée!</strong>';
$_lang['setting_site_start_err'] = 'Veuillez spécifier un l\'ID de la ressource de départ.';

$_lang['setting_site_status'] = 'Statut du site';
$_lang['setting_site_status_desc'] = 'Sélectionnez \'Oui\' pour publier votre site sur le web. Si vous sélectionnez \'Non\', vos visiteurs verront le \'Message de site indisponible\', et ne pourront pas naviger sur le site.';
$_lang['setting_site_status_err'] = 'Veuillez indiquer si le site est publié (Oui) ou indisponible (Non).';

$_lang['setting_site_unavailable_message'] = 'Message de site indisponible';
$_lang['setting_site_unavailable_message_desc'] = 'Message à afficher quand le site est hors ligne ou qu\'une erreur intervient. <strong>Note: Ce message s\'affichera uniquement si l\'option de Page site indisponible n\'est pas spécifiée.</strong>';

$_lang['setting_site_unavailable_page'] = 'Page site indisponible';
$_lang['setting_site_unavailable_page_desc'] = 'Entrez l\'ID de la ressource que vous souhaitez utiliser comme page de site indisponible ici. <strong>NOTE: assurez-vous d\'entrer un ID appartenant à une ressource existante et publiée!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Veuillez indiquer l\'ID du document pour la page site indisponible.';

$_lang['setting_strip_image_paths'] = 'Réécrire les chemins du navigateur?';
$_lang['setting_strip_image_paths_desc'] = 'Sélectionnez \'Non\' pour que MODX écrive les src (images, fichiers, flash, etc.) des fichiers ressources en URL absolues. Les URL relatives sont utiles si vous souhaitez déplacer votre installation MODX, par exemple, depuis un site en temporaire vers un site en production. Si vous ne savez pas ce que cela signifie, il est préférable de laisser \'oui\'.';

$_lang['setting_symlink_merge_fields'] = 'Fusionner les champs de ressource des Symlinks';
$_lang['setting_symlink_merge_fields_desc'] = 'Activé, cela fusionnera automatiquement les champs non vides avec ceux de la ressource cible, lors de redirections utilisant les Symlinks.';

$_lang['setting_topmenu_show_descriptions'] = 'Afficher les description dans la navigation principale';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Sélectionnez non pour que MODX cache les descriptions dans la navigation principale du manager.';

$_lang['setting_tree_default_sort'] = 'Champ de classement de l\'arborescence';
$_lang['setting_tree_default_sort_desc'] = 'Le champ par défaut utilisé pour classer les ressources dans l\'arborescence lors du chargement du manager.';

$_lang['setting_tree_root_id'] = 'ID racine de l\'arborescence';
$_lang['setting_tree_root_id_desc'] = 'Indiquez un ID valide de ressource pour démarrer l\'arborescence de ressource (à gauche) en dessous de cette ressource. Les utilisateurs veront uniquement les ressources qui sont enfants de la ressource spécifiée.';

$_lang['setting_tvs_below_content'] = 'TVs en dessous du contenu';
$_lang['setting_tvs_below_content_desc'] = 'Activez cette option pour afficher les variables de modèle en dessous du contenu, lors de l\'édition de ressource.';

$_lang['setting_ui_debug_mode'] = 'Mode debug de l\'UI';
$_lang['setting_ui_debug_mode_desc'] = 'Activez cette option pour afficher les messages de debug lors de l\'utilisation du thème de manager par défaut. Vous devez utiliser un navigateur qui supporte console.log.';

$_lang['setting_udperms_allowroot'] = 'Accès racine';
$_lang['setting_udperms_allowroot_desc'] = 'Voulez-vous autoriser vos utilisateurs à créer de nouvelles ressources à la racine du site?';

$_lang['setting_unauthorized_page'] = 'Page non autorisée';
$_lang['setting_unauthorized_page_desc'] = 'Entrez l\'ID de la ressource vers laquelle vous souhaitez rediriger les utilisateurs qui ont demandé à accéder à une ressource sécurisée ou non autorisée. <strong>NOTE: assurez-vous que l\'ID que vous avez indiqué est celle d\'une ressource existante, publiée et accéssible publiquement!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Veuillez spécifier un ID de ressource pour la page « non autorisé ».';

$_lang['setting_upload_files'] = 'Types de fichiers uploadables';
$_lang['setting_upload_files_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être uploadés dans \'assets/files/\' en utilisant le gestionnaire de ressource. Veuillez entrer les extensions pour chaque type de fichier, séparés par des virgules.';

$_lang['setting_upload_flash'] = 'Types de fichiers Flash uploadables';
$_lang['setting_upload_flash_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être uploadés dans \'assets/flash/\' en utilisant le gestionnaire de ressource. Veuillez entrer les extensions pour chaque type de fichier flash, séparés par des virgules.';

$_lang['setting_upload_images'] = 'Types d\'images uploadables';
$_lang['setting_upload_images_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être uploadés dans \'assets/images/\' en utilisant le gestionnaire de ressource. Veuillez entrer les extensions pour chaque type d\'images, séparés par des virgules.';

$_lang['setting_upload_maxsize'] = 'Taille maximale des uploads';
$_lang['setting_upload_maxsize_desc'] = 'Entrez la taille maximale des fichiers qui peuvent être uploadés via le gestionnaire de fichier. La taille doit être indiquée en octects (bytes). <strong>NOTE: Les fichiers volumineux peuvent demander beaoucp de temps pour être uploadés!</strong>';

$_lang['setting_upload_media'] = 'Types de média uploadables';
$_lang['setting_upload_media_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être uploadés dans \'assets/media/\' en utilisant le gestionnaire de ressource. Veuillez entrer les extensions pour chaque type de média, séparés par des virgules.';

$_lang['setting_use_alias_path'] = 'Utiliser les alias simples';
$_lang['setting_use_alias_path_desc'] = 'Sélectionner \'oui\' pour cette option affichera le chemin complet de la ressource si la ressource a un alias. Par exemple, si une ressource ayant pour alias \'enfant\' est située dans une ressource conteneur ayant pour alias \'parent\', alors l\'alias du chemin complet sera affiché \'/parent/enfant.html\'.<br /><strong>NOTE: Mettre \'oui\' dans cette option (activer les alias simples) implique l\'utilisation de chemin absolu pour les objets (tels qu\'images, css, javascripts, etc), par exemple : \'/assets/images\' au lieu de \'assets/images\'. En faisant de tel, vous éviterez au navigateur (ou serveur web) d\'ajouter le chemin relatif à l\'alias.</strong>';

$_lang['setting_use_browser'] = 'Activer le navigateur de ressouce';
$_lang['setting_use_browser_desc'] = 'Sélectionnez oui pour activer le navigateur de ressource. Ceci autorisera vos utilisateurs à naviger et uploader des ressources, telles que des images, du flash et d\'autres fichiers média sur le serveur.';
$_lang['setting_use_browser_err'] = 'Veuillez indiquer si vous désirez ou non utiliser le navigateur de ressource.';

$_lang['setting_use_editor'] = 'Activer l\'éditeur de texte riche';
$_lang['setting_use_editor_desc'] = 'Voulez-vous activer l\'éditeur de texte riche? Si vous êtes plus à l\'aise en rédigeant de l\'HTML alors vous pouvez désactiver l\'éditeur avec cette option. Notez que cette option s\'applique à tous les documents et utilisateurs!';
$_lang['setting_use_editor_err'] = 'Veuillez indiquer si vous désirez ou non utiliser un RTE.';

$_lang['setting_use_multibyte'] = 'Utiliser l\'extenssion Multibyte';
$_lang['setting_use_multibyte_desc'] = 'Mettre à oui si vous désirez utilisez l\'extenssion mbstring pour les caractères multibyte dans votre installation de MODX. À n\'activer que si l\'extenssion mbstring est installée.';

$_lang['setting_use_weblink_target'] = 'Utiliser le lien de destination';
$_lang['setting_use_weblink_target_desc'] = 'Activez cette option si vous désirez que les liens MODX et makeUrl() utilisent la destination du lien pour générer le lien. Par défaut, MODX utilisera le système interne d\'URL et la méthode makeUrl().';

$_lang['setting_webpwdreminder_message'] = 'Email de rappel web';
$_lang['setting_webpwdreminder_message_desc'] = 'Entrez un message qui sera envoyé aux utilisateurs web lorsqu\'ils demanderont un nouveau mot de passe par email. Le gestionnaire de contenu envera un email contenant leur nouveau mot de passe et les informations d\'activation. <br /><strong>Note:</strong> Les placeholders sont remplacés par le gestionnaire de contenu lors de l\'envoi du message : <br /><br />[[+sname]] - Nom de votre site web, <br />[[+saddr]] - Addresse email du site web, <br />[[+surl]] - URL du site web, <br />[[+uid]] - Identifiant ou ID de l\'utilisateur, <br />[[+pwd]] - Mot de passe de l\'utilisateur, <br />[[+ufn]] - Nom complet de l\'utilisateur. <br /><br /><strong>Laissez [[+uid]] et [[+pwd]] dans l\'e-mail ou l\'itendifiant et le mot de passe ne seront pas envoyés et vos utilisateurs ne pourront se connecter!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Bonjour [[+uid]]\n\nPour activer votre nouveau mot de passe veuillez vous rendre à l\'adresse :\n\n[[+surl]]\n\nEn cas de réussite vous pourrez utiliser le mot de passe suivant pour vous connecter :\n\nPassword:[[+pwd]]\n\nSi vous n\'avez pas demandé cet email, veuillez alors l\'ignorer.\n\nCordialement,\nL\'adminstrateur du site';

$_lang['setting_websignupemail_message'] = 'E-mail d\'inscription web';
$_lang['setting_websignupemail_message_desc'] = 'Ici vous pouvez définir le message envoyé à vos utilisateurs web quand vous leur créez un compte web et laissez le gestionnaire de contenu leur envoyer un e-mail contenant leur identifiant et leur mot de passe. <br /><strong>Note:</strong> Les placeholders sont remplacés par le gestionnaire de contenu quand le message est envoyé: <br /><br />[[+sname]] - Nom de votre site web, <br />[[+saddr]] - Adresse e-mail de votre site internet, <br />[[+surl]] - URL du site internet, <br />[[+uid]] - Identifiant ou nom ou ID de l\'utilisateur, <br />[[+pwd]] - Mot de passe de l\'utilisateur, <br />[[+ufn]] - Nom complet de l\'utilisateur. <br /><br /><strong>Laissez [[+uid]] et [[+pwd]] dans l\'e-mail, sinon l\'identifiant et le mot de passe ne seront pas envoyés et vos utilisateurs ne pourront se connecter!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Bonjour [[+uid]] \n\nVoici vos informations de connexion pour l\'utilisateur [[+sname]]:\n\nIdentifiant: [[+uid]]\nMot de passe: [[+pwd]]\n\nLors de votre connexion avec l\'utilisateur [[+sname]] ([[+surl]]), vous pourrez changer votre mot de passe.\n\nCordialement,\nL\'Administrateur';

$_lang['setting_welcome_screen'] = 'Afficher l\'écran de bienvenue';
$_lang['setting_welcome_screen_desc'] = 'Coché, la page de bienvenue sera affichée au prochain chargement de la page et ne s\'affichera plus par la suite.';

$_lang['setting_welcome_screen_url'] = 'URL d\'écran de bienvenue';
$_lang['setting_welcome_screen_url_desc'] = 'URL pour l\'écran de bienvenue qui s\'affiche lors du premier chargement de MODX Revolution.';

$_lang['setting_which_editor'] = 'Éditeur à utiliser';
$_lang['setting_which_editor_desc'] = 'Ici vous pouvez choisir quel RTE vous souhaitez utiliser. Vous pouvez télécharger et installer d\'autres RTE depuis la page de téléchargement de MODX.';

$_lang['setting_which_element_editor'] = 'Éditeur à utiliser pour les éléments';
$_lang['setting_which_element_editor_desc'] = 'Vous pouvez indiquer ici quel éditeur de texte riche vous souhaitez utiliser pour éditer les éléments. Vous pouvez télécharger et installer des éditeurs additionnels depuis le gestionnaire de package.';

$_lang['setting_xhtml_urls'] = 'XHTML URLs';
$_lang['setting_xhtml_urls_desc'] = 'Toutes les URLs générées par MODX seront XHTML-compliant, inclu l\'encoding du caractère ampersand.';

$_lang['setting_default_context'] = 'Contexte par défaut';
$_lang['setting_default_context_desc'] = 'Sélectionnez le contexte par défaut lors de la création de nouvelles ressources.';

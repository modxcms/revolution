<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Groupe';
$_lang['area_authentication'] = 'Authentification et sécurité';
$_lang['area_caching'] = 'Cache';
$_lang['area_core'] = 'Code du core';
$_lang['area_editor'] = 'Éditeur de texte riche';
$_lang['area_file'] = 'Système de fichiers';
$_lang['area_filter'] = 'Filtrer par zone…';
$_lang['area_furls'] = 'URLs simples';
$_lang['area_gateway'] = 'Passerelle';
$_lang['area_language'] = 'Lexique et langue';
$_lang['area_mail'] = 'E-mail';
$_lang['area_manager'] = 'Gestionnaire';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session et Cookie';
$_lang['area_static_elements'] = 'Contenus statiques';
$_lang['area_static_resources'] = 'Static Resources';
$_lang['area_lexicon_string'] = 'Entrées du lexique de zone';
$_lang['area_lexicon_string_msg'] = 'Entrez ici la clé de l\'entrée du lexique pour la zone. S\'il n\'y a pas d\'entrée de lexique, cela affichera la clé de la zone.<br />Zones du coeur : Authentification, cache, fichier, urls simples, passerelle, langue, gestionnaire, session, site, système';
$_lang['area_site'] = 'Site';
$_lang['area_system'] = 'Système et Serveur';
$_lang['areas'] = 'Groupes';
$_lang['charset'] = 'Jeu de caractères';
$_lang['country'] = 'Pays';
$_lang['description_desc'] = 'Courte description du paramètre. Peut-être une entrée de lexique basée sur une clé au format : "setting_" + clé+ "_desc" (ex. setting_login_homepage_desc).';
$_lang['key_desc'] = 'La clé du paramètre. Ell sera disponible dans votre contenu en utilisant le Placeholder [[++key]].';
$_lang['name_desc'] = 'Nom du paramètre. Peut-être une entrée de lexique basée sur la clé suivant le format "setting_" + clé (ex. setting_login_homepage).';
$_lang['namespace'] = 'Espace de noms';
$_lang['namespace_desc'] = 'L\'espace de nom auquel est associé ce paramètre. Le sujet "default" du lexique sera chargé lors du chargement des paramètres de cet espace de nom.';
$_lang['namespace_filter'] = 'Filtrer par espace de noms…';
$_lang['search_by_key'] = 'Chercher par clé…';
$_lang['setting_create'] = 'Créer un nouveau paramètre';
$_lang['setting_err'] = 'Veuillez vérifier vos données pour les champs suivants : ';
$_lang['setting_err_ae'] = 'Une option avec cette clé existe déjà. Veuillez indiquer un autre nom de clé.';
$_lang['setting_err_nf'] = 'Option non trouvée.';
$_lang['setting_err_ns'] = 'Option non spécifiée';
$_lang['setting_err_remove'] = 'Une erreur est survenue lors de la suppression de l\'option.';
$_lang['setting_err_save'] = 'Une erreur est survenue lors de la sauvegarde de l\'option.';
$_lang['setting_err_startint'] = 'Les options ne peuvent débuter par un entier.';
$_lang['setting_err_invalid_document'] = 'Il n\'y a pas de Ressource ayant pour ID %d. Veuillez indiquer une Ressource existante.';
$_lang['setting_remove'] = 'Supprimer l\'option';
$_lang['setting_remove_confirm'] = 'Êtes-vous sûr de vouloir supprimer cette option ? Ceci peut endommager votre installation de MODX.';
$_lang['setting_update'] = 'Mettre à jour l\'option';
$_lang['settings_after_install'] = 'Comme c\'est une nouvelle installation, vous devez contrôler les options de configuration et changer celles que vous souhaitez. Après avoir contrôlé les options, cliquez sur "Sauvegarder" pour mettre à jour la base de données des options.<br /><br />';
$_lang['settings_desc'] = 'Ici, vous pouvez définir les préférences générales et les paramètres de configuration de l\'interface gestionnaire de MODX, ainsi que le fonctionnement de votre site MODX. <b>Chaque paramètre sera disponible via le placeholder [[++key]].</b><br />Double-cliquez sur la colonne de valeur pour le paramétrage que vous souhaitez éditer dynamiquement via la grille, ou faites un clic droit sur un paramètre pour plus d\'options. Vous pouvez également cliquer sur le signe "+" pour une description du paramètre.';
$_lang['settings_furls'] = 'URLs simples';
$_lang['settings_misc'] = 'Divers';
$_lang['settings_site'] = 'Site';
$_lang['settings_ui'] = 'Interface &amp; Fonctionnalités';
$_lang['settings_users'] = 'Utilisateur';
$_lang['system_settings'] = 'Paramètres du système';
$_lang['usergroup'] = 'Groupe d\'utilisateurs';

// user settings
$_lang['setting_access_category_enabled'] = 'Vérifier les permissions de catégorie';
$_lang['setting_access_category_enabled_desc'] = 'Utilisez ceci pour activer ou désactiver la vérification des droits d\'accès aux catégories (par contexte). <strong>NOTE : Si cette option est désactivée, alors toutes les permissions d\'accès aux catégories seront ignorées !</strong>';

$_lang['setting_access_context_enabled'] = 'Vérifier les permissions de contexte';
$_lang['setting_access_context_enabled_desc'] = 'Utilisez ceci pour activer ou désactiver la vérification des droits d\'accès aux contextes. <strong>NOTE : Si cette option est désactivée, alors toutes les permissions d\'accès aux contextes seront ignorées. NE DÉSACTIVEZ PAS cette option pour le système entier ou pour le contexte "mgr" car vous interdiriez l\'accès au gestionnaire.</strong>';

$_lang['setting_access_resource_group_enabled'] = 'Vérifier les permissions des groupes de ressource';
$_lang['setting_access_resource_group_enabled_desc'] = 'Utilisez ceci pour activer ou désactiver la vérification des droits d\'accès aux groupes de ressource (par contexte). <strong>NOTE : Si cette option est désactivée, alors toutes les permissions d\'accès aux groupes de ressource seront ignorées !</strong>';

$_lang['setting_allow_mgr_access'] = 'Accès à l\'interface du manager';
$_lang['setting_allow_mgr_access_desc'] = 'Utilisez cette option pour activer ou désactiver l\'accès à l\'interface du manager. <strong>NOTE : Si cette option est définie à non, alors l\'utilisateur sera redirigé vers l\'écran d\'identification du manager ou la page d\'accueil du site web.</strong>';

$_lang['setting_failed_login'] = 'Tentatives d\'identification échouées';
$_lang['setting_failed_login_desc'] = 'Ici vous pouvez entrer le nombre d\'erreurs d\'identification autorisées avant qu\'un utilisateur ne soit bloqué.';

$_lang['setting_login_allowed_days'] = 'Jours autorisés';
$_lang['setting_login_allowed_days_desc'] = 'Sélectionnez les jours  pour lesquels l\'accès de cet utilisateur est autorisé.';

$_lang['setting_login_allowed_ip'] = 'Adresse IP autorisée';
$_lang['setting_login_allowed_ip_desc'] = 'Entrez les adresses IP depuis lesquelles cet utilisateur peut se connecter. <strong>NOTE: Séparez les différentes adresses IP par une virgule (,)</strong>';

$_lang['setting_login_homepage'] = 'Page d\'accueil de connexion';
$_lang['setting_login_homepage_desc'] = 'Entrez l\'ID du document vers lequel vous souhaitez rediriger un utilisateur une fois qu\'il/elle s\'est identifié(e). <strong>NOTE : assurez-vous que l\'ID correspond à un document existant, publié et accessible par cet utilisateur !</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'Version du schéma de règle d\'accès';
$_lang['setting_access_policies_version_desc'] = 'La version du système de règle d\'accès. <strong>NE PAS CHANGER</strong>.';

$_lang['setting_allow_forward_across_contexts'] = 'Autoriser la redirection dans d\'autres contextes';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Quand vrai, les lien symboliques et les appels à l\'API modX::sendForward() peuvent rediriger les requêtes vers des ressources dans d\'autres contextes.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Formulaire de récupération de mot de passe sur l\'écran de connexion au manager';
$_lang['setting_allow_manager_login_forgot_password_desc'] = '"Non" désactive la fonction de récupération de mot de passe depuis la page de connexion au manager.';

$_lang['setting_allow_tags_in_post'] = 'Autoriser les tags dans POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Désactivé, toutes les variables de POST seront vidées des tags HTML, des entitées numériques ainsi que des tags MODX. MODX recommande de désactiver cette option pour les contextes autres que "mgr", qui a cette option activée par défaut.';

$_lang['setting_allow_tv_eval'] = 'Activer l’évaluation de liaison TV';
$_lang['setting_allow_tv_eval_desc'] = 'Sélectionnez cette option pour activer ou désactiver l\'évaluation dans les liaisons TV. Si cette option est définie à non, le code/valeur sera simplement traité comme du texte normal.';

$_lang['setting_anonymous_sessions'] = 'Sessions anonymes';
$_lang['setting_anonymous_sessions_desc'] = 'Si désactivé, seuls les utilisateurs authentifiés auront accès à une session PHP. Cela peut réduire la surcharge créée par les utilisateurs anonymes, s\'ils n\'ont pas besoin d\'accéder à une session unique. Si session_enabled a la valeur false, ce paramètre n\'a aucun effet car les sessions ne seront jamais disponibles.';

$_lang['setting_archive_with'] = 'Forcer l\'archivage PCLZIP';
$_lang['setting_archive_with_desc'] = 'Utiliser PCLZIP en lieu et place de ZIPArchive pour gérer les fichiers ZIP. Activer cette option si vous obtenez des erreurs d\'extraction ou avez des problèmes de décompression dans le gestionnaire d\'extensions.';

$_lang['setting_auto_menuindex'] = 'Indexation de menu par défaut';
$_lang['setting_auto_menuindex_desc'] = 'Sélectionnez "Oui" pour activer l\'incrémentation automatique de l\'index de menu par défaut.';

$_lang['setting_auto_check_pkg_updates'] = 'Vérification automatique des mises à jour des extensions';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Si "Oui" est sélectionné, MODX vérifiera automatiquement la présence de mise à jour des extensions dans le gestionnaire. Cela peux ralentir le chargement de la grille.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Expiration du cache pour les vérifications automatiques de mise à jour des extensions';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'La durée en minutes pendant laquelle le gestionnaire d\'extensions gardera en cache les résultats de recherche de mise à jour.';

$_lang['setting_allow_multiple_emails'] = 'Autoriser les doublons d\'e-mails des utilisateurs';
$_lang['setting_allow_multiple_emails_desc'] = 'Activé, permet aux utilisateurs de partager la même adresse e-mail.';

$_lang['setting_automatic_alias'] = 'Création automatique des alias';
$_lang['setting_automatic_alias_desc'] = 'Sélectionnez "Oui" pour que le système génère automatiquement un alias basé sur le titre de la page lors de son enregistrement.';

$_lang['setting_automatic_template_assignment'] = 'Affectation automatique de modèle';
$_lang['setting_automatic_template_assignment_desc'] = 'Choisissez le mode d’affectation des modèles pour les nouvelles Ressources lors de leur création. Les options possibles sont : système (modèle par défaut défini dans le panneau de configuration), parent (paramètres hérité du modèle parent), ou un frère (hérite des paramètres les plus utilisés par les Ressources de même parent)';

$_lang['setting_base_help_url'] = 'URL de base de l\'aide';
$_lang['setting_base_help_url_desc'] = 'L\'URL de base à partir de laquelle la construction de liens d\'aide se fait (en haut à droite des pages dans le manager).';

$_lang['setting_blocked_minutes'] = 'Durée de blocage';
$_lang['setting_blocked_minutes_desc'] = 'Entrez ici le nombre de minutes de blocage d\'un utilisateur s\'il atteint le nombre maximal de tentatives de connexions. Veuillez n\'entrer qu\'un nombre (pas de virgule, espace, etc.)';

$_lang['setting_cache_action_map'] = 'Activer la mise en cache des actions';
$_lang['setting_cache_action_map_desc'] = 'Activé, les actions seront mises en cache pour réduire le temps de chargement des pages du manager.';

$_lang['setting_cache_alias_map'] = 'Activer la mise en cache des Alias';
$_lang['setting_cache_alias_map_desc'] = 'Activé, les URI des ressources seront mises en cache dans le Contexte. Pour des raisons de performance il est conseillé de n\'activer cette option que pour de "petits" sites.';

$_lang['setting_use_context_resource_table'] = 'Utiliser la table de ressources du contexte pour actualiser le cache contextuel';
$_lang['setting_use_context_resource_table_desc'] = 'Lorsque cette option est activée, le cache de contexte utilise la table context_resource. Cela vous permet d\'avoir accès par programme à une ressource dans plusieurs contextes. Si vous n\'utilisez pas ces contextes de ressources multiples via l\'API, vous pouvez le définir à false. Sur les grands sites vous obtiendrez alors un gain de performance potentiel dans le gestionnaire.';

$_lang['setting_cache_context_settings'] = 'Activer la mise en cache de la configuration des contextes';
$_lang['setting_cache_context_settings_desc'] = 'Activé, la configuration des contextes sera mise en cache pour réduire les temps de chargement.';

$_lang['setting_cache_db'] = 'Activer la mise en cache de la base de données';
$_lang['setting_cache_db_desc'] = 'Activé, les objets et résultats bruts des requêtes SQL sont mis en cache pour réduire significativement la charge de la base de données.';

$_lang['setting_cache_db_expires'] = 'Délais d\'expiration du cache de la base de données';
$_lang['setting_cache_db_expires_desc'] = 'Cette valeur (en secondes) définie le délai de mise en cache des fichiers de résultat de la base de données.';

$_lang['setting_cache_db_session'] = 'Activer le cache des sessions dans la base de données';
$_lang['setting_cache_db_session_desc'] = 'Lorsqu\'il est actif ainsi que cache_db, la table des sessions sera mise en cache en base de données.';

$_lang['setting_cache_db_session_lifetime'] = 'Délais d\'expiration du cache des sessions';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Cette valeur (en secondes) définie la durée de vie des sessions en base de données.';

$_lang['setting_cache_default'] = 'Mise en cache par défaut';
$_lang['setting_cache_default_desc'] = 'Sélectionnez "Oui" pour mettre en cache les nouvelles Ressources par défaut.';
$_lang['setting_cache_default_err'] = 'Veuillez indiquer si vous souhaitez ou non que les documents soient mis en cache par défaut..';

$_lang['setting_cache_expires'] = 'Délais d\'expiration par défaut du cache';
$_lang['setting_cache_expires_desc'] = 'Cette valeur (en secondes) définie la durée de mise en cache par défaut des fichiers.';

$_lang['setting_cache_resource_clear_partial'] = 'Vider le cache partiel de ressources pour les contextes fournis';
$_lang['setting_cache_resource_clear_partial_desc'] = 'Lorsqu’activé, l\'actualisation MODX effacera uniquement le cache de la ressource pour les contextes fournis.';

$_lang['setting_cache_format'] = 'Format de cache à utiliser';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = "sérialiser"';

$_lang['setting_cache_handler'] = 'Classe de prise en charge du cache';
$_lang['setting_cache_handler_desc'] = 'Nom de la classe à utiliser pour la prise en charge du cache.';

$_lang['setting_cache_lang_js'] = 'Mettre en cache les chaînes JS du lexique';
$_lang['setting_cache_lang_js_desc'] = 'Activé, cela utilisera les entêtes serveur pour mettre en cache les chaînes du lexique chargées en JavaScript pour l\'interface du manager.';

$_lang['setting_cache_lexicon_topics'] = 'Mettre en cache des sujets de lexique';
$_lang['setting_cache_lexicon_topics_desc'] = 'Activé, tous les sujets du lexique seront mis en cache afin de réduire les temps de chargement des fonctionnalités d\'internationalisation. MODX recommande vivement de laisser cette option sur \'Oui\'.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Mettre en cache le lexique des sujets autres que ceux du "Core"';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Désactivé, le lexique des sujets autres que ceux du "Core" ne seront pas mis en cache. C\'est utile lors du développement de vos Extensions.';

$_lang['setting_cache_resource'] = 'Activer la mise en cache partielle de ressource';
$_lang['setting_cache_resource_desc'] = 'La mise en cache partielle de ressource est configurable par ressource quand cette fonction est activée. Désactiver cette fonction la désactive globalement.';

$_lang['setting_cache_resource_expires'] = 'Temps d\'expiration pour le cache partiel de ressource';
$_lang['setting_cache_resource_expires_desc'] = 'Cette valeur (en secondes) définie la durée de mise en cache partiel des ressources.';

$_lang['setting_cache_scripts'] = 'Activer le système de cache de Script';
$_lang['setting_cache_scripts_desc'] = 'Activé, MODX mettra en cache tous les "scripts" (Snippets et Plugins) pour réduire les temps de chargement. MODX recommande de laisser sur  "Oui".';

$_lang['setting_cache_system_settings'] = 'Activer le système de cache de la configuration';
$_lang['setting_cache_system_settings_desc'] = 'Activé, les options de configurations seront mises en cache pour réduire les temps de chargement. MODX recommande de laisser cette option activée.';

$_lang['setting_clear_cache_refresh_trees'] = 'Rafraîchir l\'arborescence lors de la purge du cache';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Rafraîchie l\'arborescence après une purge du cache.';

$_lang['setting_compress_css'] = 'Utitliser des CSS compressés';
$_lang['setting_compress_css_desc'] = 'Lorsque cette option est activée, MODX utilisera une version compressée de ses feuilles de style CSS dans l’interface manager.';

$_lang['setting_compress_js'] = 'Utilisation de librairies JavaScript compressées';
$_lang['setting_compress_js_desc'] = 'Quand ceci est activé, MODX utilisera une version compressée de ses librairies JavaScript dans l\'interface du manager. Désactivez seulement si vous modifiez des éléments du "core" MODX.';

$_lang['setting_compress_js_groups'] = 'Grouper les compressions JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Grouper la compression des fichiers JavaScript du manager de MODX. Activez cette option si vous utilisez Suhosin ou tout autre environnement imposant des limites.';

$_lang['setting_compress_js_max_files'] = 'Seuil maximal de compression des fichiers JavaScript';
$_lang['setting_compress_js_max_files_desc'] = 'Nombre maximal de fichiers JavaScript que MODX essaiera de compresser en une seule passe, lorsque compress_js est activé. Utilisez un petit nombre si vous rencontrez des problèmes avec Google Minify dans le manager.';

$_lang['setting_concat_js'] = 'Utiliser les librairies JavaScript concaténées';
$_lang['setting_concat_js_desc'] = 'Quand ceci est activé, MODX utilisera une version concaténée de ses librairies JavaScript dans l\'interface du manager. Ceci réduit grandement le chargement et le temps d\'exécution au sein du manager. Désactivez seulement si vous modifiez des éléments du "core" MODX.';

$_lang['setting_confirm_navigation'] = 'Confirmation au changement de page';
$_lang['setting_confirm_navigation_desc'] = 'Quand activée, une confirmation sera demandée à l\'utilisateur au changement de page si des modifications ne sont pas sauvegardées.';

$_lang['setting_container_suffix'] = 'Suffixe des conteneurs';
$_lang['setting_container_suffix_desc'] = 'Le suffixe à ajouter aux ressources conteneurs avec l\'utilisation des URLs simples.';

$_lang['setting_context_tree_sort'] = 'Activer le classement des contextes dans l\'arborescence';
$_lang['setting_context_tree_sort_desc'] = 'Activé, les contextes seront affichés dans l\'arborescence par ordre alphabétique.';
$_lang['setting_context_tree_sortby'] = 'Champ de classement des contextes';
$_lang['setting_context_tree_sortby_desc'] = 'Le champ à utiliser pour classer les contextes dans l\'arborescence (si activé).';
$_lang['setting_context_tree_sortdir'] = 'Sens de tri des contextes dans l\'arborescence';
$_lang['setting_context_tree_sortdir_desc'] = 'Le sens de tri des contextes (si activé).';

$_lang['setting_cultureKey'] = 'Langue';
$_lang['setting_cultureKey_desc'] = 'Sélectionnez la langue - par défaut - des contextes "non-manager", web inclus.';

$_lang['setting_date_timezone'] = 'Fuseau horaire par défaut';
$_lang['setting_date_timezone_desc'] = 'Contrôle le fuseau horaire par défaut de PHP (timezone). Si ce paramètre et vide ainsi que le paramètre date.timezone de PHP, UTC sera utilisé.';

$_lang['setting_debug'] = 'Mod debug';
$_lang['setting_debug_desc'] = 'Activez ou désactiver le debugage dans MODX et/ou définissez le niveau de error_reporting de PHP. \'\' = Utiliser la valeur actuelle de error_reporting, \'0\' = false (error_reporting = 0), \'1\' = true (error_reporting = -1), ou toute autre valeur de error_reporting valide (un entier).';

$_lang['setting_default_content_type'] = 'Type de contenu par défaut';
$_lang['setting_default_content_type_desc'] = 'Indiquez le type de contenu par défaut à utiliser pour les nouvelles ressources. Vous pourrez toujours sélectionner un type de contenu différent lorsque vous éditerez une ressource; ce paramètre présélectionne seulement le type de contenu.';

$_lang['setting_default_duplicate_publish_option'] = 'Option de publication lors de la duplication de ressource';
$_lang['setting_default_duplicate_publish_option_desc'] = 'L\'option sélectionnée par défaut lors de la duplication d\'une ressource. Peut être "dépublié" pour dépublier toutes les ressources dupliquées, "publié" pour publier toutes les ressources dupliquées ou bien "préserver" pour conserver le statut de la ressource originale (sur la ressource dupliquée).';

$_lang['setting_default_media_source'] = 'Media Source par défaut';
$_lang['setting_default_media_source_desc'] = 'Media Source à charger par défaut.';

$_lang['setting_default_media_source_type'] = 'Source des médias par défaut';
$_lang['setting_default_media_source_type_desc'] = 'Le type de Media Source par défaut sélectionné lors de la création d\'un nouveau Media Source.';

$_lang['setting_default_template'] = 'Modèle par défaut';
$_lang['setting_default_template_desc'] = 'Sélectionnez le modèle par défaut que vous souhaitez utiliser pour les nouvelles ressources. Vous pouvez toujours sélectionner un modèle différent dans l\'éditeur de ressource, cette option présélectionne seulement un de vos modèles pour vous.';

$_lang['setting_default_per_page'] = 'Eléments par page';
$_lang['setting_default_per_page_desc'] = 'Nombre de résultats par page à afficher dans les grilles du manager.';

$_lang['setting_editor_css_path'] = 'Chemin vers le fichier CSS';
$_lang['setting_editor_css_path_desc'] = 'Indiquez le chemin vers le fichier CSS que vous souhaitez utiliser à l\'intérieur de votre éditeur de texte riche. Le meilleur moyen est d\'entrer le chemin depuis la racine de votre serveur, par exemple : /assets/site/style.css. Si vous ne souhaitez pas charger une feuille de style dans votre éditeur de texte riche, laissez ce champ vide.';

$_lang['setting_editor_css_selectors'] = 'Sélecteurs CSS pour l\'éditeur';
$_lang['setting_editor_css_selectors_desc'] = 'Une liste des sélecteurs CSS, séparés par une virgule, pour l\'éditeur de texte riche.';

$_lang['setting_emailsender'] = 'Adresse e-mail d\'inscription';
$_lang['setting_emailsender_desc'] = 'Ici vous pouvez indiquer l\'adresse email utilisée lors de l\'envoi des noms et mots de passe des utilisateurs.';
$_lang['setting_emailsender_err'] = 'Veuillez indiquer l\'adresse e-mail d\'administration.';

$_lang['setting_emailsubject'] = 'Sujet de l\'e-mail d\'inscription';
$_lang['setting_emailsubject_desc'] = 'Ligne de sujet par défaut de l\'e-mail quand un utilisateur s\'est enregistré.';
$_lang['setting_emailsubject_err'] = 'Veuillez indiquer un sujet pour l\'e-mail d\'inscription.';

$_lang['setting_enable_dragdrop'] = 'Activer le Glisser-Déposer dans l\'arborescence de ressources/éléments';
$_lang['setting_enable_dragdrop_desc'] = 'Inactif, empêchera l\'utilisation du glisser-déposer dans l\'arborescence de ressources et d\'éléments.';

$_lang['setting_error_page'] = 'Page d\'erreur';
$_lang['setting_error_page_desc'] = 'Entrez l\'ID du document que vous souhaitez afficher aux utilisateurs qui demandent un document qui n\'existe pas. <strong>NOTE : assurez-vous que cet ID appartienne à un document publié existant!</strong>';
$_lang['setting_error_page_err'] = 'Veuillez indiquer un ID de document pour la page d\'erreur.';

$_lang['setting_ext_debug'] = 'Mode debug pour ExtJS';
$_lang['setting_ext_debug_desc'] = 'Activez cette option pour charger le debugger d\'ExtJS (cf. ext-all-debug.js).';

$_lang['setting_extension_packages'] = 'Extensions';
$_lang['setting_extension_packages_desc'] = 'Une array JSON d\'extensions à charger lors de l\'instanciation de MODX, au format [{"packagename":{path":"path/to/package"},{"anotherpkg":{"path":"path/to/otherpackage"}}]';

$_lang['setting_enable_gravatar'] = 'Activer Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'Activez cette option afin d\'utiliser Gravatar pour afficher l\'image de profile de l\'utilisateur (si l\'utilisateur n\'a pas ajouté de photo).';

$_lang['setting_failed_login_attempts'] = 'Tentatives d\'identification échouées';
$_lang['setting_failed_login_attempts_desc'] = 'Nombre d\'échecs de connexion utilisateur autorisés avant d\'être \'bloqué\'.';

$_lang['setting_fe_editor_lang'] = 'Langue de l\'éditeur de Front-end';
$_lang['setting_fe_editor_lang_desc'] = 'Choisissez une langue à utiliser pour dans l\'éditeur de front-end.';

$_lang['setting_feed_modx_news'] = 'URL du flux d\'actualités de MODX';
$_lang['setting_feed_modx_news_desc'] = 'Défini l\'URL du flux d\'actualités du panneau de MODX dans le manager.';

$_lang['setting_feed_modx_news_enabled'] = 'Actualités de MODX activées';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Si "Non" est sélectionné, MODX cachera le flux d\'actualités de la page d\'accueil du manager.';

$_lang['setting_feed_modx_security'] = 'URL du flux des bulletins de sécurité de MODX';
$_lang['setting_feed_modx_security_desc'] = 'Défini l\'URL du panneau de flux des bulletins de sécurité de MODX dans le manager.';

$_lang['setting_feed_modx_security_enabled'] = 'Activer le flux RSS des informations de Sécurité MODX';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Si "Non" est sélectionné, MODX mettra en cache le flux de sécurité de la page d\'accueil du manager.';

$_lang['setting_filemanager_path'] = 'Chemin du gestionnaire de fichiers';
$_lang['setting_filemanager_path_desc'] = 'Souvent, IIS ne détermine pas correctement l\'option document_root, qui est utilisé par le gestionnaire de fichiers pour déterminer ce que vous pouvez explorer. Si vous avez des problèmes en utilisant le gestionnaire de fichiers, assurez-vous que ce chemin pointe vers la racine de votre installation MODX.';

$_lang['setting_filemanager_path_relative'] = 'Le chemin vers le gestionnaire de fichiers est-il relatif ? (Obsolète)';
$_lang['setting_filemanager_path_relative_desc'] = '<strong>Obsolète</strong> - Si votre paramètre filemanager_path est relatif à base_path, veuillez sélectionner Oui. Si votre paramètre filemanager_path est en dehors du docroot, veuillez sélectionner non.';

$_lang['setting_filemanager_url'] = 'Adresse du gestionnaire de fichiers (Obsolète)';
$_lang['setting_filemanager_url_desc'] = '<strong>Obsolète</strong> - Définissez ce paramètre pour utiliser une URL spécifique afin d\'accéder aux fichiers depuis le gestionnaire de fichier de MODX (utile si vous avez changé filemanager_path pour un répertoire en dehors de la racine web de MODX). Assurez-vous que ce paramètre soit l\'URL du paramètre filemanager_path. Laissez vide pour que MODX essaie de déterminer ce paramètre.';

$_lang['setting_filemanager_url_relative'] = 'URL du gestionnaire de ficher relative ?';
$_lang['setting_filemanager_url_relative_desc'] = 'Si votre paramètre filemanager_url est relatif à base_url, veuillez alors sélectionner "Oui". Si votre paramètre filemanager_url est en dehors de la racine web, sélectionnez "Non".';

$_lang['setting_forgot_login_email'] = 'E-mail à la perte de mot de passe';
$_lang['setting_forgot_login_email_desc'] = 'Modèle de l\'e-mail qui est envoyé quand un utilisateur a oublié son identifiant et/ou son mot de passe de MODX.';

$_lang['setting_form_customization_use_all_groups'] = 'Utiliser la personnalisation des formulaires pour tous les groupes';
$_lang['setting_form_customization_use_all_groups_desc'] = 'Lorsque cette option est activée, la personnalisation des formulaires sera appliquée même lorsque le groupe ne sera pas le groupe principal de l\'utilisateur. Sinon, elle ne s\'appliquera que lorsque l\'utilisateur aura ce groupe d\'utilisateur en tant que groupe principal. Note : activer cette option peut entrainer des conflits entre les sets.';

$_lang['setting_forward_merge_excludes'] = 'Faire suivre exclue les champs "fusionnés"';
$_lang['setting_forward_merge_excludes_desc'] = 'Un lien symbolique qui fusionne les valeurs des champs non vides vers les valeurs de la ressource cible; utiliser une liste "d\'exclusions", séparées par des virgules, évite que les champs indiqués soient écrasés par le lien symbolique.';

$_lang['setting_friendly_alias_lowercase_only'] = 'Alias en minuscules';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Défini si l\'alias des ressources doit être seulement en minuscules ou non.';

$_lang['setting_friendly_alias_max_length'] = 'Longeur maximale d\'alias';
$_lang['setting_friendly_alias_max_length_desc'] = 'Nombre maximum de caractères autorisés dans les alias de ressource. Zéro égal illimité.';

$_lang['setting_friendly_alias_realtime'] = 'Génaration des alias en temps réel';
$_lang['setting_friendly_alias_realtime_desc'] = 'Détermine si un alias de ressource doit être créé à la volée lorsque vous saisissez le titre de la page ou bien si cela se produit lorsque la ressource est enregistrée. Dans ce cas le paramètre automatic_alias doit être activé.';

$_lang['setting_friendly_alias_restrict_chars'] = 'Méthode de filtrage des caractères d\'alias';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'La méthode utilisée pour filtrer les caractères utilisés dans les alias de ressource. "Pattern" autorise l\'utilisation d\'un modèle RegEx, "legal" autorise tout caractère valide, "alpha" autorise uniquement les lettres de l\'alphabet, et "alphanumeric" autorise uniquement les lettres et nombres.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'Modèle de filtrage de caractère d\'alias';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'Un modèle valide RegEx pour filtrer les caractères utilisés dans les alias de ressource.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'Enlever les tags non désirés des alias';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Défini si les tags  (HTML, MODX)  non désirés doivent être enlevés des alias de ressource.';

$_lang['setting_friendly_alias_translit'] = 'Méthode de translitération des alias';
$_lang['setting_friendly_alias_translit_desc'] = 'La méthode de translitération à utiliser pour l\'alias des ressources. Vide ou "none" est le réglage par défaut qui n\'utilise pas la translitération. D\'autre valeurs possibles sont "iconv" (si disponible) ou le nom d\'un tableau de translitération fourni par une classe de translitération tierce.';

$_lang['setting_friendly_alias_translit_class'] = 'Classe de service de translitération';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Une classe optionnelle de service qui fourni des services de translitération pour le filtrage/génération des alias.';

$_lang['setting_friendly_alias_translit_class_path'] = 'Chemin vers la classe de service de translitération d\'alias';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'Chemin d\'accès à la classe du service qui sera chargée de la translitération des alias.';

$_lang['setting_friendly_alias_trim_chars'] = 'Caractères "de bord"';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Liste des caractères à supprimer de la fin des alias de ressource.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Séparateur de mots';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Le séparateur de mots préféré pour les alias d\'URL simples.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Séparateurs de mots';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Les séparateurs de mot préférés pour les alias d\'URL simples. Ces caractères seront convertis et unifiés pour les alias d\'URL simples.';

$_lang['setting_friendly_urls'] = 'Utiliser les URLs simples';
$_lang['setting_friendly_urls_desc'] = 'Ceci vous autorise à utiliser les URLs simple (pour les moteurs de recherche). Veuillez noter que cette option ne fonctionne que pour les installations MODX sous Apache et que vous devrez écrire un fichier .htaccess pour que cela fonctionne. Regardez le fichier .htaccess inclus dans la distribution pour plus d\'informations.';
$_lang['setting_friendly_urls_err'] = 'Veuillez indiquer si vous souhaitez utiliser les URLs simples';

$_lang['setting_friendly_urls_strict'] = 'Utiliser les URLs simplifiées strictes';
$_lang['setting_friendly_urls_strict_desc'] = 'Lorsque les URLs simplifiées sont activées, cette option force les requêtes non canonical correspondant à une ressource à utiliser une redirection 301 vers l\'URI canonical de cette ressource. ATTENTION : n\'utilisez pas cette cette option si vous utilisez des règles de rewrite (réécriture) qui ne correspondent pas au début de l\'URI canonical. Par exemple, pour une URI canonical foo/ avec une régle de rewrite personnalisée en foo/bar.html fonctionnera, mais les tentatives de réécriture de bar/foo.html en foo/ forcera la redirection vers foo/ avec cette option activée.';

$_lang['setting_global_duplicate_uri_check'] = 'Vérifier les URIs identiques dans tous les Contextes';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Sélectionnez "Oui" pour vérifier l\'existence de doublons d\'URI dans tous les Contextes. Sinon, seul le contexte dans lequel la Ressource est enregistrée est vérifié.';

$_lang['setting_hidemenu_default'] = 'Ne pas afficher dans les menus par défaut';
$_lang['setting_hidemenu_default_desc'] = 'Choisissez "Oui" pour que toutes les nouvelles ressources soient cachées des menus par défaut.';

$_lang['setting_inline_help'] = 'Afficher l\'aide sous les champs';
$_lang['setting_inline_help_desc'] = '"Oui" affiche le texte d\'aide directement sous le champ. "Non" affiche le texte d\'aide dans un "tooltip".';

$_lang['setting_link_tag_scheme'] = 'Schéma de génération d\'URL';
$_lang['setting_link_tag_scheme_desc'] = 'Schéma de génération des URLs pour le tag [[~id]]. Les options disponibles sont décrites dans la documentation de la méthode <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\modX::makeUrl()">makeURL()</a>.';

$_lang['setting_locale'] = 'Localisation';
$_lang['setting_locale_desc'] = 'Définie la localisation du système. Laissez vide pour utiliser celle par défaut. Consultez <a href="http://php.net/setlocale" target="_blank">la documentation PHP de setlocale()</a> pour plus d\'information.';

$_lang['setting_lock_ttl'] = 'Durée de vie du verrouillage';
$_lang['setting_lock_ttl_desc'] = 'Nombre de secondes durant laquelle une ressource restera verrouillée lorsque l\'utilisateur l\'éditant est inactif.';

$_lang['setting_log_level'] = 'Niveau de trace';
$_lang['setting_log_level_desc'] = 'Le niveau par défaut de trace; plus le niveau est bas, moins les informations seront tracées. Options disponibles : 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO) et 4 (DEBUG).';

$_lang['setting_log_target'] = 'Cible des traces';
$_lang['setting_log_target_desc'] = 'La cible par défaut vers laquelle les messages de trace seront écrits. Options disponibles : \'FILE\', \'HTML\' ou \'ECHO\'. Si aucune valeur n\'est spécifiée \'FILE\' est utilisée.';

$_lang['setting_log_deprecated'] = 'Journaliser les fonctions obsolètes';
$_lang['setting_log_deprecated_desc'] = 'Activez cette option pour recevoir des notifications dans votre journal des erreurs lorsque des fonctions obsolètes sont utilisées.';

$_lang['setting_mail_charset'] = 'Jeu de caractères des e-mails';
$_lang['setting_mail_charset_desc'] = 'Jeu de caractères par défaut pour les e-mails, par ex. "iso-8859-1" ou "UTF-8"';

$_lang['setting_mail_encoding'] = 'Encodage des e-mails';
$_lang['setting_mail_encoding_desc'] = 'Défini l\'encodage des messages. Les choix possibles sont "8bit", "7bit", "binary", "base64" et "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Utiliser un SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Si activé, MODX essaiera d\'utiliser le protocole SMTP pour l\'envoi des e-mails.';

$_lang['setting_mail_smtp_auth'] = 'Authentification SMTP';
$_lang['setting_mail_smtp_auth_desc'] = 'Défini l\'authentification SMTP. Utilise les options mail_smtp_user et mail_smtp_password.';

$_lang['setting_mail_smtp_helo'] = 'Message HELO de SMTP';
$_lang['setting_mail_smtp_helo_desc'] = 'Défini le messages HELO du protocole SMTP (par défaut, nom du serveur).';

$_lang['setting_mail_smtp_hosts'] = 'Serveurs SMTP';
$_lang['setting_mail_smtp_hosts_desc'] = 'Défini les serveurs SMTP. Tous les hôtes doivent être séparés par des points-virgules. Vous pouvez également indiquer un port différent  pour chaque serveur en utilisant ce format : [serveur:port] (exemple "smtp1.exemple.com:25;smtp2.exemple.com"). Les serveurs seront essayés dans l\'ordre.';

$_lang['setting_mail_smtp_keepalive'] = 'Keep-Alive SMTP';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Évite que la connexion SMTP soit fermée après chaque envoi d\'e-mail. Cette option n\'est pas recommandée.';

$_lang['setting_mail_smtp_pass'] = 'Mot de passe SMTP';
$_lang['setting_mail_smtp_pass_desc'] = 'Le mot de passe d\'authentification au serveur SMTP.';

$_lang['setting_mail_smtp_port'] = 'Port SMTP';
$_lang['setting_mail_smtp_port_desc'] = 'Défini le port par défaut du serveur SMTP.';

$_lang['setting_mail_smtp_prefix'] = 'SMTP Encryption';
$_lang['setting_mail_smtp_prefix_desc'] = 'Sets the encryption of the SMTP connection. Options are "", "ssl" or "tls"';

$_lang['setting_mail_smtp_autotls'] = 'SMTP Auto TLS';
$_lang['setting_mail_smtp_autotls_desc'] = 'Whether to enable TLS encryption automatically if a server supports it, even if "SMTP Encryption" is not set to "tls"';

$_lang['setting_mail_smtp_single_to'] = 'Simple destinataire SMTP';
$_lang['setting_mail_smtp_single_to_desc'] = 'Donne la possibilité d\'effectuer un champ destinataire individuel, au lieu d\'envoyer à tous les destinataires.';

$_lang['setting_mail_smtp_timeout'] = 'Timeout SMTP';
$_lang['setting_mail_smtp_timeout_desc'] = 'Défini le délai en secondes du timeout de connexion au serveur SMTP. Cette fonction ne fonctionne pas sur les serveurs win32.';

$_lang['setting_mail_smtp_user'] = 'Utilisateur SMTP';
$_lang['setting_mail_smtp_user_desc'] = 'L\'utilisateur d\'authentification au serveur SMTP.';

$_lang['setting_main_nav_parent'] = 'Conteneur du menu principal';
$_lang['setting_main_nav_parent_desc'] = 'Le conteneur utilisé pour générer le contenu du menu principal.';

$_lang['setting_manager_direction'] = 'Orientation du texte du manager';
$_lang['setting_manager_direction_desc'] = 'Choisissez l\'orientation d\'affichage du texte dans le manager, de gauche à droite (ltr) ou de droite à gauche (rtl).';

$_lang['setting_manager_date_format'] = 'Format de date du manager';
$_lang['setting_manager_date_format_desc'] = 'La chaine de caractères, au format PHP <a href="www.php.net/manual/fr/function.date.php" targe="_blank">date()</a>, pour le formatage des dates dans le manager.';

$_lang['setting_manager_favicon_url'] = 'URL du favicon du manager';
$_lang['setting_manager_favicon_url_desc'] = 'Utilise le favicon indiqué pour le manager de MODX. Doit être relatif au répertoire manager/ ou bien une URL absolue.';

$_lang['setting_manager_js_cache_file_locking'] = 'Activer le verrouillage des fichiers JS/CSS du manager';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Mettre en cache le verrouillage des fichiers. Sélectionnez non si votre système de fichier est NFS.';
$_lang['setting_manager_js_cache_max_age'] = 'Durée du cache des JS/CSS compressés';
$_lang['setting_manager_js_cache_max_age_desc'] = 'Durée maximale du cache navigateur pour les fichiers CSS/JS du manager, en secondes. Au-delà de ctte période, le navigateur enverra un GET "conditionnel". Utilisez une valeur élevée pour moins de trafic.';
$_lang['setting_manager_js_document_root'] = 'Compression JS/CSS à la racine';
$_lang['setting_manager_js_document_root_desc'] = 'Si votre serveur ne prend pas en compte la variable serveur DOCUMENT_ROOT, définissez-la ici pour activer la compression CSS/JS du manager. Ne configurez cette option que si vous savez ce que vous faites.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Activer la compression zlib pour les JS/CSS du manager';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Autorise ou non la compression zlib sur les fichiers CSS/JS du manager. N\'activez pas cette fonction à moins d\'être sûr que votre configuration PHP autorise le changement de la variable zlib.output_compression à 1. MODX recommande de laisser cette fonction désactivée..';

$_lang['setting_manager_lang_attribute'] = 'Attributs HTML et XML du manager';
$_lang['setting_manager_lang_attribute_desc'] = 'Entrez le code de langue qui correspond au mieux à la langue choisie pour votre manager, ceci assurera que le navigateur puisse afficher le contenu de la meilleure façon possible pour vous.';

$_lang['setting_manager_language'] = 'Langue du manager';
$_lang['setting_manager_language_desc'] = 'Sélectionnez la langue pour le gestionnaire de contenu de MODX.';

$_lang['setting_manager_login_url_alternate'] = 'URL alternative de connexion au manager';
$_lang['setting_manager_login_url_alternate_desc'] = 'Une URL alternative vers laquelle envoyer les utilisateurs non authentifiés auprès du manager. Le formulaire de connexion doit authentifier l\'utilisateur au contexte "mgr" pour fonctionner.';

$_lang['setting_manager_login_start'] = 'Démarrage après identification au manager';
$_lang['setting_manager_login_start_desc'] = 'Entrez l\'ID du document vers lequel vous souhaitez envoyer un utilisateur après sa connexion au manager. <strong>NOTE : assurez-vous que l\'ID que vous avez entré appartient à un document existant, publié et accessible par cet utilisateur !</strong>';

$_lang['setting_manager_theme'] = 'Thème du manager';
$_lang['setting_manager_theme_desc'] = 'Sélectionnez le thème du gestionnaire de contenu.';

$_lang['setting_manager_time_format'] = 'Format de date du manager';
$_lang['setting_manager_time_format_desc'] = 'Le format de chaine de caractères, au format PHP date(), pour les options représentées dans le manager.';

$_lang['setting_manager_use_tabs'] = 'Utiliser les onglets dans l\'agencement du manager';
$_lang['setting_manager_use_tabs_desc'] = 'Si "Oui", le manager utilisera les onglets pour afficher les panneaux de contenu. Sinon il utilisera les "panneaux".';

$_lang['setting_manager_week_start'] = 'Début de semaine';
$_lang['setting_manager_week_start_desc'] = 'Indiquez le jour débutant la semaine. Utilisez 0 (ou laissez vide) pour dimanche, 1 pour lundi et ainsi de suite…';

$_lang['setting_mgr_tree_icon_context'] = 'Icône d\'arborescence de contexte';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Définir une classe CSS ici à utiliser pour afficher l\'icône de contexte dans l\'arborescence. Vous pouvez utiliser ce paramètre sur chaque contexte pour personnaliser l\'icône par contexte.';

$_lang['setting_mgr_source_icon'] = 'Icône de Media Source';
$_lang['setting_mgr_source_icon_desc'] = 'Indiquez une classe CSS à utiliser pour les icônes de Média Sources dans l\'arborescence de fichiers. Par défaut, la classe « icon-folder-open-o » est utilisée';

$_lang['setting_modRequest.class'] = 'Classe de prise en charge de requête';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Masquer les fichiers dans l\'arborescence du "Média Browser"';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'Si vraie, les fichiers à l’intérieur des dossiers ne sont pas affichés dans l’arborescence source Media Browser.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Cacher les info-bulles de l\'arborescence du "Média Browser"';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'Activez cette option pour qu\'aucune info-bulles de la visualisation image n\'apparaissent lors du survol d\'un fichier dans l\'arborescence du "Média Browser". La valeur par défaut est "oui".';

$_lang['setting_modx_browser_default_sort'] = 'Listing par défaut des fichiers';
$_lang['setting_modx_browser_default_sort_desc'] = 'L\'ordre d\'affichage des fichiers par défaut lors de l\'utilisation de la popup du navigateur de fichier dans le manager. Les valeurs acceptées sont : name, size, lastmod (date de modification).';

$_lang['setting_modx_browser_default_viewmode'] = 'Mode d\'affichage par défaut du navigateur de fichiers';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'Le mode d\'affichage par défaut lorsque vous utilisez la popup du navigateur de fichiers dans le manager. Les valeurs disponibles sont : "grid" ou "list".';

$_lang['setting_modx_charset'] = 'Encodage des caractères';
$_lang['setting_modx_charset_desc'] = 'Veuillez indiquer quel encodage de caractère vous souhaitez utiliser. Veuillez noter que MODX a été testé avec certains encodages, mais pas tous. Pour la plupart des langues, l\'option par défaut UTF-8 est préférable.';

$_lang['setting_new_file_permissions'] = 'Permissions des nouveaux fichiers';
$_lang['setting_new_file_permissions_desc'] = 'Lorsque vous uploadez un nouveau fichier dans le gestionnaire de fichiers, celui-ci essaiera de changer les permissions du fichier pour celles définies dans cette option. Il se peut que cela ne fonctionne pas sur certaines configurations, telles qu\'avec IIS, dans quel cas vous devrez changer manuellement les permissions.';

$_lang['setting_new_folder_permissions'] = 'Permissions des nouveaux répertoires';
$_lang['setting_new_folder_permissions_desc'] = 'Lorsque vous créez un nouveau répertoire dans le gestionnaire de fichiers, ceci-ci essaiera de changer les permissions du répertoire pour celles entrées dans cette option. Il se peut que cela ne fonctionne pas sur certaines configurations, telles qu\'avec IIS, dans quel cas vous devrez changer manuellement les permissions.';

$_lang['setting_parser_recurse_uncacheable'] = 'Retarder l\'analyse des éléments non cacheables';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'Si désactivé, les éléments non cacheables peuvent avoir leur résultat mis en cache à l\'intérieur du contenu d\'éléments cacheables. Désactivez ceci SEULEMENT si vous avez des problèmes avec éléments imbriqués de façon complexe, qui a cessé de fonctionner comme prévu.';

$_lang['setting_password_generated_length'] = 'Longueur des mots de passe générés automatiquement';
$_lang['setting_password_generated_length_desc'] = 'La longueur des mots de passe utilisateur générés automatiquement.';

$_lang['setting_password_min_length'] = 'Longueur minimale du mot de passe';
$_lang['setting_password_min_length_desc'] = 'La longueur minimale du mot de passe des utilisateurs.';

$_lang['setting_preserve_menuindex'] = 'Préserver l\'Index de menu lors de la duplication des Ressources';
$_lang['setting_preserve_menuindex_desc'] = 'Lors de la duplication des Ressources, l\'index de menu sera également préservé.';

$_lang['setting_principal_targets'] = 'ACL à charger';
$_lang['setting_principal_targets_desc'] = 'Personnalise les ACL à charger pour les utilisateurs MODX.';

$_lang['setting_proxy_auth_type'] = 'Type d\'authentification du proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Supporte BASIC ou NTLM.';

$_lang['setting_proxy_host'] = 'Serveur Proxy';
$_lang['setting_proxy_host_desc'] = 'Si votre serveur utilise un Proxy, indiquez ici son nom de domaine pour activer les fonctions de MODX qui peuvent utiliser le serveur Proxy, comme le gestionnaire de package.';

$_lang['setting_proxy_password'] = 'Mot de passe du Proxy';
$_lang['setting_proxy_password_desc'] = 'Le mot de passe nécessaire pour vous authentifier sur le serveur Proxy.';

$_lang['setting_proxy_port'] = 'Port du Proxy';
$_lang['setting_proxy_port_desc'] = 'Le port du serveur Proxy.';

$_lang['setting_proxy_username'] = 'Nom d\'utilisateur du Proxy';
$_lang['setting_proxy_username_desc'] = 'Le nom d\'utilisateur pour vous authentifier sur le serveur Proxy.';

$_lang['setting_photo_profile_source'] = 'Média Source des photos d\'utilisateurs';
$_lang['setting_photo_profile_source_desc'] = 'Le Media Source utilisé pour stocker les photos de profils utilisateurs. Par défaut, le Media Source par défaut.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb autorise des sources en dehors de la racine web';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Indique si le chemin source peut être en dehors de la racine web. Ce paramètre est utile pour déployer des Contextes multiples avec plusieurs serveurs virtuels.';

$_lang['setting_phpthumb_cache_maxage'] = 'Durée maximale de cache de phpThumb';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Supprime les vignettes mises en cache qui n\'ont pas été accédées depuis plus de X jours.';

$_lang['setting_phpthumb_cache_maxsize'] = 'Taille maximale du cache de phpThumb';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Supprime les vignettes les plus anciennes quand la taille du cache devient plus importante que X Mb.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'Taille maximale du cache de fichiers phpThumb';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Supprime les vignettes les plus anciennes quand le cache contient plus de X fichiers.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'Mettre en cache les fichiers sources';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Choisissez de mettre en cache ou pas les fichiers sources quand ils sont chargés. Désactiver cette option est recommandé.';

$_lang['setting_phpthumb_document_root'] = 'Répertoire racine pour PHPThumb';
$_lang['setting_phpthumb_document_root_desc'] = 'Utilisez ceci si vous rencontrez des problèmes avec la variable serveur DOCUMENT_ROOT, ou si vous obtenez des erreurs avec OutputThumbnail() ou !is_resource(). Indiquez le chemin absolu du répertoire racine que vous souhaitez utiliser. Si ce champ reste vide, MODX utilisera la variable de serveur DOCUMENT_ROOT.';

$_lang['setting_phpthumb_error_bgcolor'] = 'Couleur de fond des erreurs phpThumb';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'Valeur hexadecimale, sans le #, indiquant la couleur de fond pour les erreurs de rendu de phpThumb.';

$_lang['setting_phpthumb_error_fontsize'] = 'Taille de la police d\'erreur phpThumb';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'Valeur en em indiquant la taille de police à utiliser pour le texte apparaissant dans les erreurs de rendu de phpThumb.';

$_lang['setting_phpthumb_error_textcolor'] = 'Couleur de la police d\'erreur phpThumb';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'Valeur hexadecimale, sans le #, indiquant la couleur de la police pour les textes apparaissant dans les erreurs de rendu de phpThumb.';

$_lang['setting_phpthumb_far'] = 'Forcer l\'aspect-ratio de phpThumb';
$_lang['setting_phpthumb_far_desc'] = 'Les options par défaut d\'éloignement lorsque phpThumb est utilisé avec MODX. "C" par défaut pour forcer l\'aspect-ratio vers le centre. "T" pour le haut, "B" pour le bas...';

$_lang['setting_phpthumb_imagemagick_path'] = 'Chemin vers ImageMagick pour phpThumb';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Optionnel. Permet de définir un chemin alternatif à ImageMagick pour générer les miniatures avec phpThumb, si celui-ci n\'est pas déjà défini au niveau de PHP.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Hotlinking désactivé';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Les serveurs distants sont autorisés dans le paramètre "src", à moins que vous ne désactiviez le hotlinking dans phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb lien rapide vers Effacement de l\'image';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Indique si une image générée depuis un serveur distant doit être effacée lorsque le site n\'est pas autorisé.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'Message si phpThumb Hotlinking non autorisé';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'Un message qui sera affiché à la place de la miniature quand une tentative d\'hotlinking est refusée.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'Domaines valides pour phpThumb Hotlinking';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'Une liste de noms de domaines (séparés par des virgules) autorisés dans les URLs "src".';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb lien offsite désactivés';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Désactive la possibilité des tierces à utiliser phpThumb pour afficher des images sur leurs propres sites.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb lien offsite, effacement d\'image';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Indique si une image liée depuis un site distant doit être effacée lorsque le site n\'est pas autorisé.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb lien offsite avec "Referrer"';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'Tous les essais de liens "offsite" sans une entête correcte de referrer seront rejetés.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb lien offsite, message non autorisé';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'Message à afficher à la place de la miniature lorsqu\'une tentative de lien offsite est rejetée.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb lien offsite, domaines valides';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'Une liste de noms de domaines (séparés par des virgules) autorisés à utiliser les liens offsite.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb lien offsite, source de filigrane';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Optionnel. Un chemin (système) valide vers un fichier à utiliser en tant que filigrane quand vos images sont affichées offsite par phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb zoom-recadrage';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Les options zc par défaut lorsque phpThumb est utilisé dans MODX. "0" par défaut pour éviter le zoom et le recadrage.';

$_lang['setting_publish_default'] = 'Publié par défaut';
$_lang['setting_publish_default_desc'] = 'Sélectionnez \'Oui\' pour définir les nouvelles ressources comme publiées par défaut.';
$_lang['setting_publish_default_err'] = 'Veuillez indiquer si vous désirez ou non que vos ressources soient publiées par défaut.';

$_lang['setting_rb_base_dir'] = 'Chemin des fichiers ressources';
$_lang['setting_rb_base_dir_desc'] = 'Entrez le chemin physique du répertoire des fichiers ressources. Cette option est généralement générée automatique. Cependant, si vous utilisez IIS, MODX peut ne pas trouver le chemin d\'accès par lui même, entrainant un message d\'erreur. Dans ce cas, veuillez entrer le chemin d\'accès du répertoire d\'images (comme vous le verriez dans l\'explorateur Windows). <strong>NOTE :</strong> Le répertoire des fichiers ressources doit contenir les sous-répertoires images, files, flash et média... afin que le navigateur fonctionne correctement.';
$_lang['setting_rb_base_dir_err'] = 'Veuillez indiquer le répertoire de base du navigateur des fichiers ressources.';
$_lang['setting_rb_base_dir_err_invalid'] = 'Soit ce répertoire des fichiers ressources n\'existe pas, soit il ne peut être accédé. Veuillez indiquer un répertoire valide ou ajuster les droits d\'accès de ce répertoire.';

$_lang['setting_rb_base_url'] = 'URL des fichiers ressources';
$_lang['setting_rb_base_url_desc'] = 'Entrez le chemin virtuel d\'accès au répertoire des ressources. Cette option est en général définie automatiquement. Cependant, si vous utilisez IIS, MODX peut ne pas trouver l\'URL par lui même, entrainant un message d\'erreur dans le navigateur de ressource. Dans ce cas, vous pouvez entrez l\'URL du répertoire d\'images (l\'URL telle que vous la rentreriez dans Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Veuillez indiquer l\'URL de base du navigateur de ressource.';

$_lang['setting_request_controller'] = 'Nom de fichier du contrôleur de requête';
$_lang['setting_request_controller_desc'] = 'Le nom de fichier du contrôleur principal de requête par lequel MODX est chargé. La plupart des utilisateurs peuvent laisser index.php.';

$_lang['setting_request_method_strict'] = 'Méthode de requête sctricte';
$_lang['setting_request_method_strict_desc'] = 'Lorsque cette option est activée, les requêtes faites avec le paramètre d\'ID seront ignorées si les URLs simples sont activées, et inversement, les requêtes faites avec le paramètre d\'alias seront ignorées si les URLs simples sont désactivées.';

$_lang['setting_request_param_alias'] = 'Paramètre de requête pour les alias';
$_lang['setting_request_param_alias_desc'] = 'Nom du paramètre GET pour identifier les alias des ressources quand les URL simples sont utilisées.';

$_lang['setting_request_param_id'] = 'Paramètre de requête pour les ID';
$_lang['setting_request_param_id_desc'] = 'Nom du paramètre GET pour identifier les IDs des ressources quand les URL simples ne sont pas utilisées.';

$_lang['setting_resolve_hostnames'] = 'Résolution des noms de domaines';
$_lang['setting_resolve_hostnames_desc'] = 'Souhaitez-vous que MODX essai de résoudre les noms de domaines de vos visiteurs quand ils visitent votre site ? Résoudre les noms de domaines peut créer une charge supplémentaire du serveur, mais vos visiteurs ne le ressentiront pas.';

$_lang['setting_resource_tree_node_name'] = 'Valeur des noeuds de l\'arbre des ressources';
$_lang['setting_resource_tree_node_name_desc'] = 'Indiquez le champ de ressource à utiliser lors de l\'affichage des noeuds dans l\'arborescence. "pagetitle" par défaut, mais n\'importe quel champ de ressource peut être utilisé, tels que menutitle, alias, longtitle, etc.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Valeur de secours pour les Noeuds de l\'arbre des ressources';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Nom du champ à utiliser par défaut pour les noeuds de l\'arbre des ressources, si la valeur configurée est vide.';

$_lang['setting_resource_tree_node_tooltip'] = 'Champs des infos-bulles de ressource';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Indiquez le champ de ressource à utiliser lors de l\'affichage des nodes de l\'arborescence de ressources. Tous les champs de ressource peuvent être utilisés, tels que menutitle, alias, longtitle, etc. Si ce champ reste vide, le champ longtitle sera utilisé ainsi que description en dessous.';

$_lang['setting_richtext_default'] = 'Editeur WYSIWYG';
$_lang['setting_richtext_default_desc'] = 'Sélectionnez "Oui" pour définir les nouvelles ressources comme utilisant un éditeur de texte riche (WYSIWYG ) par défaut.';

$_lang['setting_search_default'] = 'Recherchable par défaut';
$_lang['setting_search_default_desc'] = 'Sélectionnez "Oui" pour définir les nouvelles ressources comme recherchables par défaut.';
$_lang['setting_search_default_err'] = 'Veuillez indiquer si vous désirez ou non que vos documents soient recherchables par défaut.';

$_lang['setting_server_offset_time'] = 'Décalage horaire du serveur';
$_lang['setting_server_offset_time_desc'] = 'Indiquez le nombre d\'heures de décalage entre vous et votre serveur.';

$_lang['setting_server_protocol'] = 'Type de serveur';
$_lang['setting_server_protocol_desc'] = 'Si votre site utilise une connexion sécurisée (https), veuillez l\'indiquer ici.';
$_lang['setting_server_protocol_err'] = 'Veuillez indiquer si votre site utilise ou non une connexion sécurisée.';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Cookie de session de domaine';
$_lang['setting_session_cookie_domain_desc'] = 'Utilisez cette option pour personnaliser le domaine de session de cookie.';

$_lang['setting_session_cookie_lifetime'] = 'Durée de vie du Cookie de Session';
$_lang['setting_session_cookie_lifetime_desc'] = 'Utilisez cette option pour personnaliser la durée de vie de session de coockie (en secondes). Ceci est utilisé la durée de la session côté client quand l\'option \'se rappeler de moi\' est utilisée lors du login.';

$_lang['setting_session_cookie_path'] = 'Chemin du Cookie de Session';
$_lang['setting_session_cookie_path_desc'] = 'Utilisez cette option pour personnaliser le chemin de coockie pour identifier les cookies de sessions spécifiques au site. Laissez vide pour utiliser  MODX_BASE_URL.';

$_lang['setting_session_cookie_secure'] = 'Cookie de sessions sécurisées';
$_lang['setting_session_cookie_secure_desc'] = 'Activez cette option pour utiliser les cookies de sessions sécurisées. Ceci implique que votre site est accessible via https, sinon votre site et gestionnaire deviendront inaccessibles.';

$_lang['setting_session_cookie_httponly'] = 'Cookie de session HTTPOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Activez ce paramètre pour utiliser le flag HTTPOnly dans le cookie de session.';

$_lang['setting_session_cookie_samesite'] = 'Session Cookie Samesite';
$_lang['setting_session_cookie_samesite_desc'] = 'Choose Lax or Strict.';

$_lang['setting_session_gc_maxlifetime'] = 'Durée de vie maximale des sessions';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Autorise la personnalisation du paramètre session.gc_maxlifetime (PHP ini) lors de l\'utilisation de \'modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Nom de classe de prise en charge des sessions';
$_lang['setting_session_handler_class_desc'] = 'Pour les sessions de base de données gérées, utilisez \'modSessionHandler\'.  Laissez vide pour utiliser la gestion des sessions standard avec PHP.';

$_lang['setting_session_name'] = 'Nom de la session';
$_lang['setting_session_name_desc'] = 'Utilisez ces options pour personnaliser les noms de sessions utilisées dans MODX. Laissez vide pour utiliser le nom par défaut du PHP.';

$_lang['setting_settings_version'] = 'Options de Version';
$_lang['setting_settings_version_desc'] = 'Version installée de MODX.';

$_lang['setting_settings_distro'] = 'Paramètres de distribution';
$_lang['setting_settings_distro_desc'] = 'Distribution de MODX actuellement installée.';

$_lang['setting_set_header'] = 'Activer les entêtes HTTP';
$_lang['setting_set_header_desc'] = 'Activé, MODX essaie de définir les entêtes HTTP pour les ressources.';

$_lang['setting_send_poweredby_header'] = 'Envoyer l\'en-tête X-Powered-By';
$_lang['setting_send_poweredby_header_desc'] = 'Quand activé, MODX enverra l\'en-tête "X-Powered-By" pour identifier ce site comme construit sur MODX. Cela permet les statistiques d\'utilisation de MODX par l\'intermédiaire de trackers tiers qui inspectent votre site. Comme il sera plus facile d\'identifier avec quoi votre site est construit, cela pourrait poser un risque légèrement accru de sécurité si une vulnérabilité est trouvée dans MODX.';

$_lang['setting_show_tv_categories_header'] = 'Afficher « Catégories » dans l\'entête de l\'onglet  des TVs';
$_lang['setting_show_tv_categories_header_desc'] = 'Activé, MODX affiche l\'entête « Catégories » au-dessus du premier onglet de TV, lors de l\'édition d\'une ressource.';

$_lang['setting_signupemail_message'] = 'E-mail d\'inscription';
$_lang['setting_signupemail_message_desc'] = 'Ici vous pouvez définir le message envoyé à vos utilisateurs lorsque vous leur créer un compte et laissez MODX leur envoyer un email contenant leur nom d\'utilisateur et leur mot de passe. <br /><strong>Note:</strong> Les placeholders suivants sont remplacés par le gestionnaire de contenu lors de l\'envoi du message: <br /><br />[[+sname]] - Nom de votre site web, <br />[[+saddr]] - Adresse email de votre site internet, <br />[[+surl]] - URL de votre site, <br />[[+uid]] - Identifiant ou id d\'utilisateur, <br />[[+pwd]] - Mot de passe de l\'utilisateur, <br />[[+ufn]] - Nom complet de l\'utilisateur. <br /><br /><strong>Laissez [[+uid]] et [[+pwd]] dans l\'email ou le nom d\'utilisateur et le mot de passe ne seront pas envoyés par email et vos utilisateurs ne pourront se connecter!</strong>';
$_lang['setting_signupemail_message_default'] = 'Bonjour [[+uid]] \n\nVoici vos informations de connexion au gestionnaire de contenu pour [[+sname]] :\n\nNom d\'utilisateur: [[+uid]]\nMot de passe: [[+pwd]]\n\nUne fois connecté au gestionnaire de contenu ([[+surl]]), vous pouvez changer votre mot de passe.\n\nCordialement,\nl\'administrateur du site';

$_lang['setting_site_name'] = 'Nom du site';
$_lang['setting_site_name_desc'] = 'Entrez ici le nom de votre site.';
$_lang['setting_site_name_err']  = 'Veuillez entrer un nom de site.';

$_lang['setting_site_start'] = 'Accueil du site';
$_lang['setting_site_start_desc'] = 'Entrez ici l\'ID de la ressource que vous souhaitez utiliser comme page d\'accueil. <strong>Note : assurez-vous que l\'ID appartient à une ressource existante et publiée !</strong>';
$_lang['setting_site_start_err'] = 'Veuillez spécifier un l\'ID de la ressource de départ.';

$_lang['setting_site_status'] = 'Statut du site';
$_lang['setting_site_status_desc'] = 'Sélectionnez "Oui" pour publier votre site sur le web. Si vous sélectionnez "Non", vos visiteurs verront le "Message de site indisponible", et ne pourront pas naviguer sur le site.';
$_lang['setting_site_status_err'] = 'Veuillez indiquer si le site est publié (Oui) ou indisponible (Non).';

$_lang['setting_site_unavailable_message'] = 'Message en cas de site indisponible';
$_lang['setting_site_unavailable_message_desc'] = 'Message à afficher quand le site est hors ligne ou qu\'une erreur intervient. <strong>Note : Ce message s\'affichera uniquement si l\'option de "Page en cas de site indisponible" n\'est pas spécifiée.</strong>';

$_lang['setting_site_unavailable_page'] = 'Page en cas de site indisponible';
$_lang['setting_site_unavailable_page_desc'] = 'Entrez l\'ID de la ressource que vous souhaitez utiliser comme page de site indisponible ici. <strong>Note : assurez-vous d\'entrer un ID appartenant à une ressource existante et publiée !</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Veuillez indiquer l\'ID du document pour la page site indisponible.';

$_lang['setting_static_elements_automate_templates'] = 'Automatiser les éléments statiques pour les gabarits ?';
$_lang['setting_static_elements_automate_templates_desc'] = 'Cela automatisera la gestion des fichiers statiques, comme la création et la suppression de fichiers statiques pour les modèles.';

$_lang['setting_static_elements_automate_tvs'] = 'Automatiser les éléments statiques pour les variables de modèle ?';
$_lang['setting_static_elements_automate_tvs_desc'] = 'Ceci automatisera la gestion des fichiers statiques, comme la création et la suppression de fichiers statiques pour les variables de modèle.';

$_lang['setting_static_elements_automate_chunks'] = 'Automatiser les éléments statiques pour les chuncks ?';
$_lang['setting_static_elements_automate_chunks_desc'] = 'Ceci automatisera la gestion des fichiers statiques, tels que la création et la suppression de fichiers statiques pour les chunks.';

$_lang['setting_static_elements_automate_snippets'] = 'Automatiser les éléments statiques pour les snippets ?';
$_lang['setting_static_elements_automate_snippets_desc'] = 'Cela automatisera la gestion des fichiers statiques, comme la création et la suppression de fichiers statiques pour les snippets.';

$_lang['setting_static_elements_automate_plugins'] = 'Automatiser les éléments statiques pour les plugins ?';
$_lang['setting_static_elements_automate_plugins_desc'] = 'Ceci automatisera la gestion des fichiers statiques, comme la création et la suppression de fichiers statiques pour les plugins.';

$_lang['setting_static_elements_default_mediasource'] = 'Médiasource par défaut des éléments statiques';
$_lang['setting_static_elements_default_mediasource_desc'] = 'Spécifiez une médiasource par défaut dans laquelle vous voulez stocker les éléments statiques.';

$_lang['setting_static_elements_default_category'] = 'Catégorie par défaut des éléments statiques';
$_lang['setting_static_elements_default_category_desc'] = 'Spécifier une catégorie par défaut pour la création de nouveaux éléments statiques.';

$_lang['setting_static_elements_basepath'] = 'Chemin de base des éléments statiques';
$_lang['setting_static_elements_basepath_desc'] = 'Chemin de base où stocker les fichiers d\'éléments statiques.';

$_lang['setting_resource_static_allow_absolute'] = 'Allow absolute static resource path';
$_lang['setting_resource_static_allow_absolute_desc'] = 'This setting enables users to enter a fully qualified absolute path to any readable file on the server as the content of a static resource. Important: enabling this setting may be considered a significant security risk! It\'s strongly recommended to keep this setting disabled, unless you fully trust every single manager user.';

$_lang['setting_resource_static_path'] = 'Static resource base path';
$_lang['setting_resource_static_path_desc'] = 'When resource_static_allow_absolute is disabled, static resources are restricted to be within the absolute path provided here.  Important: setting this too wide may allow users to read files they shouldn\'t! It is strongly recommended to limit users to a specific directory such as {core_path}static/ or {assets_path} with this setting.';

$_lang['setting_strip_image_paths'] = 'Réécrire les chemins du navigateur ?';
$_lang['setting_strip_image_paths_desc'] = 'Sélectionnez "Non" pour que MODX écrive les "src" (images, fichiers, flash, etc.) des fichiers ressources en URL absolues. Les URL relatives sont utiles si vous souhaitez déplacer votre installation MODX, par exemple, depuis un site en temporaire vers un site en production. Si vous ne savez pas ce que cela signifie, il est préférable de laisser "Oui".';

$_lang['setting_symlink_merge_fields'] = 'Fusionner les champs de ressource des liens symboliques';
$_lang['setting_symlink_merge_fields_desc'] = 'Activé, cela fusionnera automatiquement les champs non vides avec ceux de la ressource cible, lors de redirections utilisant les liens symboliques.';

$_lang['setting_syncsite_default'] = 'Vider le cache par défaut';
$_lang['setting_syncsite_default_desc'] = 'Sélectionnez « Oui » pour vider le cache après avoir enregistré une ressource.';
$_lang['setting_syncsite_default_err'] = 'Prière d\'indiquer si vous voulez ou non par défaut vider le cache après avoir sauvé une ressource.';

$_lang['setting_topmenu_show_descriptions'] = 'Afficher les descriptions dans la navigation principale';
$_lang['setting_topmenu_show_descriptions_desc'] = 'Sélectionnez non pour que MODX cache les descriptions dans la navigation principale du manager.';

$_lang['setting_tree_default_sort'] = 'Champ de classement de l\'arborescence';
$_lang['setting_tree_default_sort_desc'] = 'Le champ par défaut utilisé pour classer les ressources dans l\'arborescence lors du chargement du manager.';

$_lang['setting_tree_root_id'] = 'ID racine de l\'arborescence';
$_lang['setting_tree_root_id_desc'] = 'Indiquez un ID valide de ressource pour démarrer l\'arborescence de ressource (à gauche) en dessous de cette ressource. Les utilisateurs verront uniquement les ressources qui sont enfants de la ressource spécifiée.';

$_lang['setting_tvs_below_content'] = 'TVs en dessous du contenu';
$_lang['setting_tvs_below_content_desc'] = 'Activez cette option pour afficher les variables de modèle en dessous du contenu, lors de l\'édition de ressource.';

$_lang['setting_ui_debug_mode'] = 'Mode debug de l\'UI';
$_lang['setting_ui_debug_mode_desc'] = 'Activez cette option pour afficher les messages de debug lors de l\'utilisation du thème de manager par défaut. Vous devez utiliser un navigateur qui supporte la fonction console.log().';

$_lang['setting_udperms_allowroot'] = 'Accès racine';
$_lang['setting_udperms_allowroot_desc'] = 'Voulez-vous autoriser vos utilisateurs à créer de nouvelles ressources à la racine du site ?';

$_lang['setting_unauthorized_page'] = 'Page pour accès non autorisée';
$_lang['setting_unauthorized_page_desc'] = 'Entrez l\'ID de la ressource vers laquelle vous souhaitez rediriger les utilisateurs qui ont demandé à accéder à une ressource sécurisée ou non autorisée. <strong>Note : assurez-vous que l\'ID que vous avez indiqué est celle d\'une ressource existante, publiée et accessible publiquement !</strong>';
$_lang['setting_unauthorized_page_err'] = 'Veuillez spécifier un ID de ressource de la page pour accès non autorisé.';

$_lang['setting_upload_check_exists'] = 'Vérifier si le fichier téléchargé existe';
$_lang['setting_upload_check_exists_desc'] = 'Lorsque cette option est activée, une erreur sera affichée lors du téléchargement d\'un fichier qui existe déjà avec le même nom. Lorsque cette option est désactivée, le fichier existant sera discrètement remplacé par le nouveau fichier.';

$_lang['setting_upload_files'] = 'Types de fichiers autorisés';
$_lang['setting_upload_files_desc'] = 'Ici vous pouvez indiquer une liste des types de fichiers qui peuvent être chargés dans \'assets/files/\' en utilisant le gestionnaire de fichiers. Veuillez entrer les extensions pour chaque type de fichier, séparées par des virgules.';

$_lang['setting_upload_flash'] = 'Types de fichiers Flash autorisés';
$_lang['setting_upload_flash_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être chargés dans \'assets/flash/\' en utilisant le gestionnaire de fichiers. Veuillez entrer les extensions pour chaque type de fichier flash, séparées par des virgules.';

$_lang['setting_upload_images'] = 'Types d\'images autorisés';
$_lang['setting_upload_images_desc'] = 'Ici vous pouvez indiquer une liste des types de fichiers qui peuvent être chargés dans \'assets/images/\' en utilisant le gestionnaire de fichiers. Veuillez entrer les extensions pour chaque type d\'images, séparées par des virgules.';

$_lang['setting_upload_maxsize'] = 'Taille maximale des chargements';
$_lang['setting_upload_maxsize_desc'] = 'Entrez la taille maximale des fichiers qui peuvent être chargés via le gestionnaire de fichiers. La taille doit être indiquée en octects (bytes). <strong>Notes : Les fichiers volumineux peuvent demander beaucoup de temps pour être chargés !</strong>';

$_lang['setting_upload_media'] = 'Types de média autorisés';
$_lang['setting_upload_media_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être chargés dans \'assets/média/\' en utilisant le gestionnaire de ressource. Veuillez entrer les extensions pour chaque type de média, séparées par des virgules.';

$_lang['setting_use_alias_path'] = 'Utiliser les alias simples';
$_lang['setting_use_alias_path_desc'] = 'Sélectionner "Oui" pour cette option affichera le chemin complet de la ressource si la ressource a un alias. Par exemple, si une ressource ayant pour alias "enfant" est située dans une ressource conteneur ayant pour alias "parent", alors l\'alias du chemin complet sera affiché "/parent/enfant.html".<br /><strong>NOTE : Mettre "oui" dans cette option (activer les alias simples) implique l\'utilisation de chemin absolu pour les objets (tels qu\'images, css, javascripts, etc.), par exemple : "\'/assets/images" au lieu de "assets/images". En faisant ainsi, vous éviterez au navigateur (ou serveur web) d\'ajouter le chemin relatif à l\'alias.</strong>';

$_lang['setting_use_browser'] = 'Activer le navigateur de fichiers';
$_lang['setting_use_browser_desc'] = 'Sélectionnez "Oui" pour activer le navigateur de fichiers. Ceci autorisera vos utilisateurs à naviguer et charger des ressources, telles que des images, du flash et d\'autres fichiers média sur le serveur.';
$_lang['setting_use_browser_err'] = 'Veuillez indiquer si vous désirez ou non utiliser le navigateur de fichiers.';

$_lang['setting_use_editor'] = 'Activer l\'éditeur de texte riche';
$_lang['setting_use_editor_desc'] = 'Voulez-vous activer l\'éditeur de texte riche ? Si vous êtes plus à l\'aise en écrivant du HTML alors vous pouvez désactiver l\'éditeur avec cette option. Notez que cette option s\'applique à toutes les ressources et tous les utilisateurs !';
$_lang['setting_use_editor_err'] = 'Veuillez indiquer si vous désirez ou non utiliser un éditeur de texte riche.';

$_lang['setting_use_frozen_parent_uris'] = 'Utiliser les URI fixes des parents';
$_lang['setting_use_frozen_parent_uris_desc'] = 'Lorsqu\'activé, l\'URI des ressources enfants sera relative à l\'URI fixe de l\'un de ses parents, ignorant ainsi les alias des ressources plus hautes dans l\'arborescence.';

$_lang['setting_use_multibyte'] = 'Utiliser l\'extension "Multibyte"';
$_lang['setting_use_multibyte_desc'] = 'Mettre à "Oui" si vous désirez utilisez l\'extension "mbstring" pour les caractères multibyte dans votre installation de MODX. À n\'activer que si l\'extension "mbstring" est installée.';

$_lang['setting_use_weblink_target'] = 'Utiliser le lien de destination';
$_lang['setting_use_weblink_target_desc'] = 'Activez cette option si vous désirez que les liens MODX et makeUrl() utilisent la destination du lien pour générer le lien. Par défaut, MODX utilisera le système interne d\'URL et la méthode makeUrl().';

$_lang['setting_user_nav_parent'] = 'Conteneur du menu utilisateur';
$_lang['setting_user_nav_parent_desc'] = 'Le conteneur utilisé pour générer le contenu du menu utilisateur.';

$_lang['setting_webpwdreminder_message'] = 'E-mail de rappel web';
$_lang['setting_webpwdreminder_message_desc'] = 'Entrez un message qui sera envoyé aux utilisateurs web lorsqu\'ils demanderont un nouveau mot de passe par e-mail. Le gestionnaire de contenu enverra un e-mail contenant leur nouveau mot de passe et les informations d\'activation. <br /><strong>Note :</strong> Les placeholders sont remplacés par le gestionnaire de contenu lors de l\'envoi du message : <br /><br />[[+sname]] - Nom de votre site web, <br />[[+saddr]] - Addresse email du site web, <br />[[+surl]] - URL du site web, <br />[[+uid]] - Identifiant ou ID de l\'utilisateur, <br />[[+pwd]] - Mot de passe de l\'utilisateur, <br />[[+ufn]] - Nom complet de l\'utilisateur. <br /><br /><strong>Laissez [[+uid]] et [[+pwd]] dans l\'e-mail ou l\'identifiant et le mot de passe ne seront pas envoyés et vos utilisateurs ne pourront se connecter !</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Bonjour [[+uid]]\n\nPour activer votre nouveau mot de passe veuillez vous rendre à l\'adresse :\n\n[[+surl]]\n\nEn cas de réussite vous pourrez utiliser le mot de passe suivant pour vous connecter :\n\nPassword :[[+pwd]]\n\nSi vous n\'avez pas demandé cet e-mail, veuillez alors l\'ignorer.\n\nCordialement,\nL\'administrateur du site';

$_lang['setting_websignupemail_message'] = 'E-mail d\'inscription web';
$_lang['setting_websignupemail_message_desc'] = 'Ici vous pouvez définir le message envoyé à vos utilisateurs web quand vous leur créez un compte web et laissez le gestionnaire de contenu leur envoyer un e-mail contenant leur identifiant et leur mot de passe. <br /><strong>Note :</strong> Les placeholders sont remplacés par le gestionnaire de contenu quand le message est envoyé : <br /><br />[[+sname]] - Nom de votre site web, <br />[[+saddr]] - Adresse e-mail de votre site internet, <br />[[+surl]] - URL du site internet, <br />[[+uid]] - Identifiant ou nom ou ID de l\'utilisateur, <br />[[+pwd]] - Mot de passe de l\'utilisateur, <br />[[+ufn]] - Nom complet de l\'utilisateur. <br /><br /><strong>Laissez [[+uid]] et [[+pwd]] dans l\'e-mail, sinon l\'identifiant et le mot de passe ne seront pas envoyés et vos utilisateurs ne pourront se connecter !</strong>';
$_lang['setting_websignupemail_message_default'] = 'Bonjour [[+uid]] \n\nVoici vos informations de connexion pour l\'utilisateur [[+sname]] :\n\nIdentifiant : [[+uid]]\nMot de passe : [[+pwd]]\n\nLors de votre connexion avec l\'utilisateur [[+sname]] ([[+surl]]), vous pourrez changer votre mot de passe.\n\nCordialement,\nL\'Administrateur';

$_lang['setting_welcome_screen'] = 'Afficher l\'écran de bienvenue';
$_lang['setting_welcome_screen_desc'] = 'Coché, l\'écran de bienvenue sera affiché au prochain chargement de la page et ne s\'affichera plus par la suite.';

$_lang['setting_welcome_screen_url'] = 'URL d\'écran de bienvenue';
$_lang['setting_welcome_screen_url_desc'] = 'L\'URL de l\'écran de bienvenue qui s\'affiche lors du premier chargement de MODX Revolution.';

$_lang['setting_welcome_action'] = 'Action de bienvenue';
$_lang['setting_welcome_action_desc'] = 'Accueil par défaut du manager, c\'est à dire le contrôleur à charger quand celui-ci n\'est pas spécifié au niveau de l\'URL.';

$_lang['setting_welcome_namespace'] = 'Espace de noms de bienvenue';
$_lang['setting_welcome_namespace_desc'] = 'Espace de noms associé à l\'action de bienvenue.';

$_lang['setting_which_editor'] = 'Éditeur à utiliser';
$_lang['setting_which_editor_desc'] = 'Ici vous pouvez choisir quel éditeur de texte riche vous souhaitez utiliser. Vous pouvez télécharger et installer d\'autres Editeurs de Texte Riche depuis la page de téléchargement de MODX.';

$_lang['setting_which_element_editor'] = 'Éditeur à utiliser pour les éléments';
$_lang['setting_which_element_editor_desc'] = 'Vous pouvez indiquer ici quel éditeur de texte riche vous souhaitez utiliser pour modifier les éléments. Vous pouvez télécharger et installer des éditeurs additionnels depuis le gestionnaire de package.';

$_lang['setting_xhtml_urls'] = 'URLs XHTML';
$_lang['setting_xhtml_urls_desc'] = 'Si coché, toutes les URLs générées par MODX seront conforme XHTML, y compris l\'encodage du caractère esperluette.';

$_lang['setting_default_context'] = 'Contexte par défaut';
$_lang['setting_default_context_desc'] = 'Sélectionnez le contexte par défaut lors de la création de nouvelles ressources.';

$_lang['setting_auto_isfolder'] = 'Définir automatiquement  comme conteneur';
$_lang['setting_auto_isfolder_desc'] = 'Si la valeur est oui, la propriété du container sera automatiquement modifiée.';

$_lang['setting_default_username'] = 'Nom d\'utilisateur par défaut';
$_lang['setting_default_username_desc'] = 'Nom par défaut d\'un utilisateur non authentifié.';

$_lang['setting_manager_use_fullname'] = 'Afficher le nom complet de l\'utilisateur dans l\'entête du manager ';
$_lang['setting_manager_use_fullname_desc'] = 'Si cette option est activée, le champ "Nom complet" sera utilisé en lieu et place du champ "Nom de connexion" pour l\'affichage dans l\'entête du manager';

$_lang['setting_log_snippet_not_found'] = 'Journalisation des snippets introuvables';
$_lang['setting_log_snippet_not_found_desc'] = 'Si la valeur est Oui, les "snippets" qui sont appelés, mais pas trouvés, seront enregistrés dans le journal des erreurs.';

$_lang['setting_error_log_filename'] = 'Nom du fichier de journal des erreurs';
$_lang['setting_error_log_filename_desc'] = 'Personnaliser le nom de fichier du fichier du journal des erreurs MODX (y compris l’extension de fichier).';

$_lang['setting_error_log_filepath'] = 'Chemin d’accès du journal des erreurs';
$_lang['setting_error_log_filepath_desc'] = 'Éventuellement défini un chemin d’accès absolu pour l’emplacement du journal un message d’erreur personnalisé. Vous pouvez utiliser des placeholders comme {cache_path}.';

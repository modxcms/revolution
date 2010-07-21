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
$_lang['area_editor'] = 'Éditeur riche de texte';
$_lang['area_file'] = 'Système de fichier';
$_lang['area_filter'] = 'Filtrer par zone…';
$_lang['area_furls'] = 'URLs simples';
$_lang['area_gateway'] = 'Gateway-porte d\'accès';
$_lang['area_language'] = 'Lexique et langue';
$_lang['area_mail'] = 'Email';
$_lang['area_manager'] = 'Back-end (interface) du Manager';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Session et Cookie';
$_lang['area_lexicon_string'] = 'Entrées du lexique de zone';
$_lang['area_lexicon_string_msg'] = 'Entrez ici la clé de l\'entrée du lexique pour la zone. S\'il n\'y a pas d\'entrée de lexique, cela affichera la clé de la zone.<br />Zones du coeur:<ul><li>identification</li><li>cache</li><li>fichier</li><li>urls simples</li><li>gateway</li><li>langue</li><li>manager</li><li>session</li><li>site</li><li>système</li></ul>';
$_lang['area_site'] = 'Site';
$_lang['area_system'] = 'Systéme et Serveur';
$_lang['areas'] = 'Zones';
$_lang['namespace'] = 'Espace de nom';
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
$_lang['setting_remove_confirm'] = 'Êtes-vous sûr de vouloir supprimer cette option ? Ceci peut endommager votre installation de MODx.';
$_lang['setting_update'] = 'Mettre à jour l\'option';
$_lang['settings_after_install'] = 'Comme c\'est une nouvelle installation, vous devez contrôler les options de configuration et changer celles que vous souhaitez. Après avoir contrôlé les options, cliquez sur \'Sauvegarder\' pour mettre à jour la base de données des options.<br /><br />';
$_lang['settings_desc'] = 'Ici vous pouvez indiquer les préférences et configurations pour l\'interface de gestion de MODx et également le fonctionnement de votre site MODx. Double-cliquez sur la colonne de valeur de l\'option que vous souhaitez éditer pour éditer dynamiquement via la grille, ou faites un clic droit sur une option pour plus d\'options. Vous pouvez également cliquer le symbole "+" pour une description de l\'option de configuration.';
$_lang['settings_furls'] = 'URLs simples';
$_lang['settings_misc'] = 'Divers';
$_lang['settings_site'] = 'Site';
$_lang['settings_ui'] = 'Interface &amp; Fonctions';
$_lang['settings_users'] = 'Utilisateur';
$_lang['system_settings'] = 'Configuration du Systéme';

// user settings
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
$_lang['setting_allow_duplicate_alias'] = 'Autoriser les doublons d\'alias';
$_lang['setting_allow_duplicate_alias_desc'] = 'Si \'oui\' est sélectionné, cela permet de saugarder des alias en doublons. <strong>NOTE: Cette option doit être utilisée avec l\'option \'URLs simple de répertoire\' définie sur \'Oui\' pour éviter des problèmes de référence aux ressources.</strong>';

$_lang['setting_allow_tags_in_post'] = 'Autoriser les tags HTML en POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Désactivé, toutes les actions POST à l\'intérieur du manager seront débarrassées de tout tags. Modx recommande de laisser cette option activée.';

$_lang['setting_auto_menuindex'] = 'Index de menu par défaut';
$_lang['setting_auto_menuindex_desc'] = 'Sélectionnez \'Oui\' pour activer l\'incrémentation automatique de l\'index de menu par défaut.';

$_lang['setting_auto_check_pkg_updates'] = 'Vérification automatique des mises à jour de package';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Si \'Oui\' est sélectionné, MODx vérifiera automatiquement la présence de mise à jour des packages dans le gestionnaire de packages. Cela ralentie le chargement de la grille.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Expiration du cache pour les vérifications automatiques de mise à jour de package';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'Valeur en minutes que le gestionnaire de package gardera en cache les résultats de recherche de mise à jour.';

$_lang['setting_allow_multiple_emails'] = 'Autoriser les doublons d\'emails des utilisateurs';
$_lang['setting_allow_multiple_emails_desc'] = 'Activé, cela permet aux utilisateurs de partager la même adresse email.';

$_lang['setting_automatic_alias'] = 'Création automatique d\'alias';
$_lang['setting_automatic_alias_desc'] = 'Sélectionnez \'Oui\' pour que le système génère automatiquement un alias basé sur le titre de la page lors de son enregistrement.';

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

$_lang['setting_cache_default'] = 'En cache par défaut';
$_lang['setting_cache_default_desc'] = 'Sélectionnez \'Oui\' pour mettre en cache les nouvelles ressources par défaut.';
$_lang['setting_cache_default_err'] = 'Veuillez indiquer si vous souhaitez ou non que les documents soient mis en cache par défaut..';

$_lang['setting_cache_disabled'] = 'Désactiver les options de cache global';
$_lang['setting_cache_disabled_desc'] = 'Sélectionnez \'Oui\' pour désactiver toutes les fonctions de cache de MODx. MODx recommande de ne pas désactiver le cache.';
$_lang['setting_cache_disabled_err'] = 'Veuillez indiquer si vous souhaitez ou non que le cache soit activé.';

$_lang['setting_cache_json'] = 'Mie en cache des données JSON';
$_lang['setting_cache_json_desc'] = 'Met en cache les données JSON envers depuis et vers l\'interface du manager.';

$_lang['setting_cache_expires'] = 'Délais d\'expiration par défaut du cache';
$_lang['setting_cache_expires_desc'] = 'Cette valeur (en secondes) défini la durée de mise en cache par défaut des fichiers.';

$_lang['setting_cache_json_expires'] = 'Délais d\'expiration du cache JSON';
$_lang['setting_cache_json_expires_desc'] = 'Cette valeur (en secondes) défini la durée de mise en cache des fichiers pour le cache JSON.';

$_lang['setting_cache_handler'] = 'Classe de prise en charge du cache';
$_lang['setting_cache_handler_desc'] = 'Nom de la classe à utiliser pour la prise en charge du cache.';

$_lang['setting_cache_lang_js'] = 'Mettre en cache les chaînes JS du lexique';
$_lang['setting_cache_lang_js_desc'] = 'Activé, cela utilisera les entêtes serveur pour mettre en cache les chaînes du lexique chargées dans Javascript pour l\'interface du manager.';

$_lang['setting_cache_lexicon_topics'] = 'Mettre en cache des sujets de lexique';
$_lang['setting_cache_lexicon_topics_desc'] = 'Activé, tous les sujets du lexique seront mis en cache afin de réduire les temps de chargement des fonctionnalités d\'internationnalisation. MODx recommande vivement de laisser cette option sur \'Oui\'.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Mettre en cache le lexique des sujets autres au ceux du noyau';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Désactivé, le lexique des sujets autres que ceux du noyau ne seront pas mis en cache. C\'est utile lors du développement de vos Extras.';

$_lang['setting_cache_resource'] = 'Activer la mise en cache partiel de ressource';
$_lang['setting_cache_resource_desc'] = 'La mise en cache partiel de ressource est configurable par ressource quand cette fonction est activée. Désactiver cette fonction la désactivement globalement.';

$_lang['setting_cache_resource_expires'] = 'Temps d\'expiration pour le cache partiel des ressources';
$_lang['setting_cache_resource_expires_desc'] = 'Cette valeur (en secondes) défini la durée de mise en cache partiel des ressources.';

$_lang['setting_cache_scripts'] = 'Activer le système de cache de Script';
$_lang['setting_cache_scripts_desc'] = 'Activé, MODx mettra en cache tous les Scripts (Snippets et Plugins) "to file" pour réduire les temps de chargement. MODx recommande de laisser \'Oui\' activé.';

$_lang['setting_cache_system_settings'] = 'Activer le système de cache de la configuration';
$_lang['setting_cache_system_settings_desc'] = 'Activé, les options de configurations seront mis en cache pour réduire les temps de chargement. MODx recommande de laisser activé.';

$_lang['setting_compress_css'] = 'Utitliser des CSS compressés';
$_lang['setting_compress_css_desc'] = 'Lorsque cei est activé, MODx utilise une version compressée de ses feuilles de style dans l\'interface du manager. Ceci réduit grandement les temps de chargement et d\'éxécution au sein du manager. Désactivez cette option seulement si vous modifiez des éléments du noyau.';

$_lang['setting_compress_js'] = 'Utilisation de librairies Javascript compressées';
$_lang['setting_compress_js_desc'] = 'Quand ceci est activé, MODx utilisera une version compressée de ses librairies Javascript dans l\'interface du manager. Désactivez seulement si vous modifiez des élements du coeur/noyau/base.';

$_lang['setting_concat_js'] = 'Utiliser les librairies Javascript concaténées';
$_lang['setting_concat_js_desc'] = 'Quand ceci est activé, MODx utilisera une version concaténée de ses librairies Javascript dans l\'interface du manager. Ceci réduit grandement le chargement et le temps d\'éxécution au sein du manager. Désactivez seuelement si vous modifiez des éléments du coeur/noyau/base.';

$_lang['setting_container_suffix'] = 'Suffixe de conteneur';
$_lang['setting_container_suffix_desc'] = 'Le suffixe à ajouter aux ressources conteneurs avec l\'utilisation des URLs simples.';

$_lang['setting_cultureKey'] = 'Langue';
$_lang['setting_cultureKey_desc'] = 'Sélectionnez la langue pour tous les contextes "non-manager", web inclus.';

$_lang['setting_custom_resource_classes'] = 'Classes personnalisées de ressource';
$_lang['setting_custom_resource_classes_desc'] = 'Liste de classes personnalisées, séparées par des virgules, de ressource. Indiquez avec lowercase_lexicon_key:className (Ex: wiki_resource:WikiResource). Toutes les classes personnalisées de ressource doivent étendre modResource. Pour indiquer la localisation du contrôleur de chaque classe, ajoutez une option avec [nameOfClassLowercase]_delegate_path avec le chemin d\'accès au répertoire des fichiers php créer/mettre à jour. Ex: wikiresource_delegate_path pour une classe WikiResource qui étend modResource.';
 	 	
$_lang['setting_default_template'] = 'Modèle par défaut';
$_lang['setting_default_template_desc'] = 'Sélectionnez le modèle par défaut que vous souhaitez utiliser pour les nouvelles ressources. Vous pouvez toujours sélectionner un modèle différent dans l\'éditeur de ressource, cette option pré-sélectionne seuelement un de vos modèles pour vous.';

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

$_lang['setting_error_page'] = 'Page d\'erreur';
$_lang['setting_error_page_desc'] = 'Entrez l\'ID du document que vous souhaitez afficher aux utilisateurs qui demandent un document qui n\'existe pas. <strong>NOTE: assurez-vous que cet ID appartienne à un document publié existant!</strong>';
$_lang['setting_error_page_err'] = 'Veuillez indiquer un ID de document pour la page d\'erreur.';

$_lang['setting_failed_login_attempts'] = 'Tentatives échouées de connexion';
$_lang['setting_failed_login_attempts_desc'] = 'Nombre de tentatives échouées de connexion qu\'un utilisateur est autorisé à commetre avant d\'être \'bloqué\'.';

$_lang['setting_fe_editor_lang'] = 'Langue de l\'éditeur de Front-end';
$_lang['setting_fe_editor_lang_desc'] = 'Choisissez une langue à utiliser pour éditer dans le front-end.';

$_lang['setting_feed_modx_news'] = 'URL du flux d\'actualités de MODx';
$_lang['setting_feed_modx_news_desc'] = 'Défini l\'URL du flux d\'actualités du panneau de MODx dans le manager.';

$_lang['setting_feed_modx_news_enabled'] = 'Actualités de MODx activées';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Si \'Non\' est sélectionné, MODx cachera le flux d\'actualités de la page d\'accueil du manager.';

$_lang['setting_feed_modx_security'] = 'URL du flux des bulletins de sécurité de MODx';
$_lang['setting_feed_modx_security_desc'] = 'Défini l\'URL du panneau de flux des bulletins de sécurité de MODx dans le manager.';

$_lang['setting_feed_modx_security_enabled'] = 'Activer le flux RSS des informations de Sécurité MODx';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Si \'Non\' est sélectionné, MODx cachera le flux de sécurité de la page d\'accueil du manager.';

$_lang['setting_filemanager_path'] = 'Chemin du gestionnaire de fichier';
$_lang['setting_filemanager_path_desc'] = 'Souvent, IIS ne "peuple/remplie" pas l\'option document_root correctement, qui est utilisé par le gestionnaire de fichier pour déterminer ce que vous pouver explorer. Si vous avez des problèmes en utilisant le gestionnaire de fichier, assurez-vous que ce chemin pointe vers la racine de votre installation MODX.';
$_lang['setting_filemanager_path_err'] = 'Veuillez indiquer le chemin racine absolu pour le gestionnaire de fichier.';
$_lang['setting_filemanager_path_err_invalid'] = 'Ce répertoire de gestionnaire de fichier n\'existe pas ou n\'est pas accessible. Veuillez indiquer un répertoire valide ou ajuster les permissions de celui-ci.';

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

$_lang['setting_friendly_alias_translit'] = 'Translitération d\'alias';
$_lang['setting_friendly_alias_translit_desc'] = 'La méthode de translitération à utiliser sur un alias de ressource spécifié. Vide ou « none » est la valeur par défaut qui évite la translitération. Les autres valeurs possibles sont « iconv » (si disponible) ou un tableau de translitération nommé fourni par une classe de service de translitération personnalisée.';

$_lang['setting_friendly_alias_translit_class'] = 'Classe de service de translitération';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Une classe optionnelle de service qui fourni des services de translitération pour le filtrage/génération des alias.';

$_lang['setting_friendly_alias_trim_chars'] = 'Caractères de bord';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Les caractères à supprimer de la fin des alias de ressource fournis.';

$_lang['setting_friendly_alias_urls'] = 'Utiliser les URLs simples';
$_lang['setting_friendly_alias_urls_desc'] = 'Si vous utilisez les URLs simples et que la ressource a un alias, l\'alias sera prioritaire face aux URLs simples. En mettant cette option à \'Oui\', le suffix de type de contenu de la ressource sera également appliqué à l\'alias. Par exemple, si la ressource ayant pour ID 1 a un alias `introduction`, et que vous avez défini le suffixe de type de contenu à `.html`, mettre cette option à `oui` génèrera `introduction.html`. S\'il n\'y a pas d\'alias, MODx génèrera `1.html` en lien.';

$_lang['setting_friendly_alias_word_delimiter'] = 'Séparateur de mot';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'Le séparateur de mot préféré pour les alias d\'URL simples.';

$_lang['setting_friendly_alias_word_delimiters'] = 'Séparateurs de mot';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Les séparateurs de mot préférés pour les alias d\'URL simples. Ces caractères seront convertis et unifiés pour les alias d\'URL simples.';

$_lang['setting_friendly_urls'] = 'Utiliser les URLs simples';
$_lang['setting_friendly_urls_desc'] = 'Ceci vous autorise à utiliser les URLs simple (pour les moteurs de recherche). Veuillez noter que cette option ne fonctionne que pour les installations MODx tournant avec Apache et que vous aurez besoin d\'écrire un fichier .htaccess pour que cela fonctionne. Regardez le fichier .htaccess inclu dans la distribution pour plus d\'informations.';
$_lang['setting_friendly_urls_err'] = 'Please state whether or not you want to use friendly URLs.';

$_lang['setting_mail_charset'] = 'Charset Mail';
$_lang['setting_mail_charset_desc'] = 'Le charset (par défaut défaut) pour les emails, par ex. \'iso-8859-1\' ou \'UTF-8\'';

$_lang['setting_mail_encoding'] = 'Encodage Mail';
$_lang['setting_mail_encoding_desc'] = 'Défini l\'encodage du message. Les choix sont "8bit", "7bit", "binary", "base64", et "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Utiliser un SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Si activé, MODx essaiera d\'utiliser le SMTP pour les fonctions mail.';

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

$_lang['setting_manager_lang_attribute'] = 'Attributs HTML et XML du manager';
$_lang['setting_manager_lang_attribute_desc'] = 'Entrez le code de langue qui correspond au mieux à la langue choisie pour votre manager, ceci assurera que le navigateur puisse afficher le contenu de la meilleur façon possible pour vous.';

$_lang['setting_manager_language'] = 'Langue du manager';
$_lang['setting_manager_language_desc'] = 'Sélectionnez la langue pour le gestionnaire de contenu de MODx.';

$_lang['setting_manager_login_start'] = 'Démarrage après identification au manager';
$_lang['setting_manager_login_start_desc'] = 'Entrez l\'ID du document vers lequel vous souhaitez envoyer un utilisateur après sa connexion au manager. <strong>NOTE: assurez-vous que l\'ID que vous avez entré appartient à un document existant, publié et accessible par cet utilisateur!</strong>';

$_lang['setting_manager_theme'] = 'Thème du manager';
$_lang['setting_manager_theme_desc'] = 'Sélectionnez le thème du gestionnaire de contenu.';

$_lang['setting_manager_time_format'] = 'Format de date du manager';
$_lang['setting_manager_time_format_desc'] = 'Le format de chaine de caratères, au format PHP date(), pour les options représentées dans le manager.';

$_lang['setting_manager_use_tabs'] = 'Utiliser les onglets dans l\'agencement du manager';
$_lang['setting_manager_use_tabs_desc'] = 'Si oui, le manager utilisera les onglets pour afficher les panneaux de contenu. Sinon il utilisera les "portails".';

$_lang['setting_modRequest.class'] = 'Classe de prise en charge de requête';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_charset'] = 'Encodage de caractère';
$_lang['setting_modx_charset_desc'] = 'Veuillez indiquer quel encodage de caractère vous souhaitez utiliser. Veuillez noter que MODx a été testé certains encodages mais pas tous. Pour la plupart des langues, l\'option par défaut UTF-8 est préférable.';

$_lang['setting_new_file_permissions'] = 'Permissions des nouveaux fichiers';
$_lang['setting_new_file_permissions_desc'] = 'Lorsque vous uploadez un nouveau fichier dans le gestionnaire de fichier, celui-ci essaiera de changer les permissions du fichier à celles définies dans cette option. Il se peut que cela ne fonctionne pas sur certaines configurations, telles qu\'avec IIS, dans quel cas vous devrez changer manuellement les permissions.';

$_lang['setting_new_folder_permissions'] = 'Permissions des nouveaux répertoires';
$_lang['setting_new_folder_permissions_desc'] = 'Lorsque vous créez un nouveau répertoire dans le gestionnaire de fichier, ceci-ci essaiera de changer les permissions du répertoire à celles entrées dans cette option. Il se peut que cela ne fonctionne pas sur certaines configurations, telles qu\'avec IIS, dans quel cas vous devrez changer manuellement les permissions.';

$_lang['setting_password_generated_length'] = 'Longueur des mots de passe générés automatiquement';
$_lang['setting_password_generated_length_desc'] = 'La longueur des mots de passe utilisateur générés automatiquement.';

$_lang['setting_phpthumb_cache_maxage'] = 'Durée maximale pour le cache de phpThumb';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Supprime les vignettes mis en cache qui n\'ont pas été accédées depuis plus de X jours.';

$_lang['setting_phpthumb_cache_maxsize'] = 'Taille maximale du cache de phpThumb';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Supprime les vignettes les plus anciennes quand la taille du cache devient plus important que X Mb.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'Taille maximale du cache de fichiers phpThumb';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Supprime les vignettes les plus anciennes quand le cache contient plus de X fichiers.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'Mettre en cache les fichiers sources';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Choisissez de mettre en cache ou pas les fichiers sources quand ils sont chargés. Désactiver cette option est recommandé.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb zoom-recadrage';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'Les options zc par défaut lorsque phpThumb est utilisé dans MODx. « 0 » par défaut pour éviter le zoom et le recadrage.';

$_lang['setting_phpthumb_far'] = 'Forcer l\'aspect-ratio de phpThumb';
$_lang['setting_phpthumb_far_desc'] = 'Les options par défaut d\'éloignement lorsque phpThumb est utilisé avec MODx. « C » par défaut pour forcer l\'aspect-ratio vers le centre.';

$_lang['setting_proxy_auth_type'] = 'Type d\'identification du proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Supporte soit BASIC ou NTLM.';

$_lang['setting_proxy_host'] = 'Hôte du proxy';
$_lang['setting_proxy_host_desc'] = 'Si votre serveur utilise un proxy, indiquez ici son nom de domaine pour activer les fonctions de MODx qui peuvent utiliser le serveur proxy, comme le gestionnaire de package.';

$_lang['setting_proxy_password'] = 'Mot de passe du proxy';
$_lang['setting_proxy_password_desc'] = 'Le mot de passe nécessaire pour vous identifier sur votre serveur proxy.';

$_lang['setting_proxy_port'] = 'Port du Proxy';
$_lang['setting_proxy_port_desc'] = 'Port de votre serveur proxy.';

$_lang['setting_proxy_username'] = 'Nom d\'utilisateur du Proxy';
$_lang['setting_proxy_username_desc'] = 'Le nom d\'utilisateur pour vous identifier sur votre serveur proxy.';

$_lang['setting_password_min_length'] = 'Longueur minimale du mot de passe';
$_lang['setting_password_min_length_desc'] = 'Longueur minimale du mot de passe des utilisateurs.';

$_lang['setting_publish_default'] = 'Publié par défaut';
$_lang['setting_publish_default_desc'] = 'Sélectionnez \'Oui\' pour définir les nouvelles ressources comme publiées par défaut.';
$_lang['setting_publish_default_err'] = 'Veuillez indiquer si vous désirez ou non que vos documents soient publiés apr défaut.';

$_lang['setting_rb_base_dir'] = 'Chemin des ressources';
$_lang['setting_rb_base_dir_desc'] = 'Entrez le chemin physique du navigateur de ressource. Cette option est généralement générée automatique. Cependant, si vous utilisez IIS, MODx peut ne pas trouver le chemin d\'accès par lui même, entrainant un message d\'erreur dans la navigateur de ressource. Dans ce cas, veuillez entrer le chemin d\'accès du répertoire d\'images (comme vous le verriez dans l\'explorateur Windows). <strong>NOTE:</strong> Le répertoire de ressource doit contenir les sous-répertoires images, files, flash et media afin que le navigateur de ressource fonctionne correctement.';
$_lang['setting_rb_base_dir_err'] = 'Veuillez indiquer le répertoire de base du navigateur de ressource.';
$_lang['setting_rb_base_dir_err_invalid'] = 'soit ce répertoire de ressource n\'existe pas, soit il ne peut être accédé. Veuillez indiquer un répertoire valide ou ajuster les droits d\'accès de ce répertoire.';

$_lang['setting_rb_base_url'] = 'URL des ressources';
$_lang['setting_rb_base_url_desc'] = 'Entrez le chemin virtuel d\'accès au répertoire des ressources. Cette option est en générale définie automatiquement. Cependant, si vous utilisez IIS, MODx peut ne pas trouver l\'URL par lui même, entrainant un message d\'erreur dans le navigateur de ressource. Dans ce cas, vous pouvez entrez l\'URL du répertoire d\'images (l\'URL telle que vous la rentreriez dans Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Veuillez indiquer l\'URL de base du navigateur de ressource.';

$_lang['setting_request_controller'] = 'Nom de fichier du contrôleur de requête';
$_lang['setting_request_controller_desc'] = 'Le nom de fichier du contrôleur principale de requête par lequel MODx est chargé. La plupart des utilisateurs peuvent laisser index.php.';

$_lang['setting_request_param_alias'] = 'Paramètre de requête d\'alias';
$_lang['setting_request_param_alias_desc'] = 'Nom du paramètre GET pour identifier les alias des ressources quand les FURLs sont utilisées.';

$_lang['setting_request_param_id'] = 'Paramètre de requête ID';
$_lang['setting_request_param_id_desc'] = 'Nom du paramètre GET pour identifier les IDs des ressources quand les FURLs ne sont pas utilisées.';

$_lang['setting_resolve_hostnames'] = 'Résolution des noms de domaines';
$_lang['setting_resolve_hostnames_desc'] = 'Souhaitez-vous que MODx essai de résoudre les noms de domaines de vos visiteurs quand ils visitent votre site? Résoudre les noms de domaines peut créer une charge supplémentaire du serveur, mais vos visiteurs ne le ressentiront pas.';

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

$_lang['setting_session_handler_class'] = 'Nom de classe de prise en charge des Sessions';
$_lang['setting_session_handler_class_desc'] = 'Pour les sessions de base de données gérées, utilisez \'modSessionHandler\'.  Laissez vide pour utiliser la gestion des sessions standard avec PHP.';

$_lang['setting_session_name'] = 'Nom de Session';
$_lang['setting_session_name_desc'] = 'Utilisez ces options pour personnaliser les noms de sessions utilisées dans MODx.';

$_lang['setting_settings_version'] = 'Options de Version';
$_lang['setting_settings_version_desc'] = 'Version installée de MODx.';

$_lang['setting_set_header'] = 'Activer les entêtes HTTP';
$_lang['setting_set_header_desc'] = 'Activé, MODx essai de définir les entêtes HTTP pour les ressources.';

$_lang['setting_signupemail_message'] = 'E-mail d\'inscription';
$_lang['setting_signupemail_message_desc'] = 'Ici vous pouvez définir le message envoyé à vos utilisateurs lorsque vous leur créer un compte et laissez MODx leur envoyer un email contenant leur nom d\'utilisateur et leur mot de passe. <br /><strong>Note:</strong> Les placeholders suivants sont remplacés par le gestionnaire de contenu lors de l\'envoi du message: <br /><br />[[+sname]] - Nom de votre site web, <br />[[+saddr]] - Adresse email de votre site internet, <br />[[+surl]] - URL de votre site, <br />[[+uid]] - Identifiant ou id d\'utilisateur, <br />[[+pwd]] - Mot de passe de l\'utilisateur, <br />[[+ufn]] - Nom complet de l\'utilisateur. <br /><br /><strong>Laissez [[+uid]] et [[+pwd]] dans l\'email ou le nom d\'utilisateur et le mot de passe ne seront pas envoyés par email et vos utilisateurs ne pourront se connecter!</strong>';
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
$_lang['setting_strip_image_paths_desc'] = 'Sélectionnez \'Non\' pour que MODx écrive les src (images, fichiers, flash, etc.) des fichiers ressources en URL absolues. Les URL relatives sont utiles si vous souhaitez déplacer votre installation MODx, par exemple, depuis un site en temporaire vers un site en production. Si vous ne savez pas ce que cela signifie, il est préférable de laisser \'oui\'.';

$_lang['setting_tree_root_id'] = 'ID racine de l\'arborescence';
$_lang['setting_tree_root_id_desc'] = 'Indiquez un ID valide de ressource pour démarrer l\'arborescence de ressource (à gauche) en dessous de cette ressource. Les utilisateurs veront uniquement les ressources qui sont enfants de la ressource spécifiée.';

$_lang['setting_udperms_allowroot'] = 'Accès racine';
$_lang['setting_udperms_allowroot_desc'] = 'Voulez-vous autoriser vos utilisateurs à créer de nouvelles ressources à la racine du site?';

$_lang['setting_unauthorized_page'] = 'Page non autorisée';
$_lang['setting_unauthorized_page_desc'] = 'Entrez l\'ID de la ressource vers laquelle vous souhaitez rediriger les utilisateurs qui ont demandé à accéder à une ressource sécurisée ou non autorisée. <strong>NOTE: assurez-vous que l\'ID que vous avez indiqué est celle d\'une ressource existante, publiée et accéssible publiquement!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Veuillez spécifier un ID de ressource pour la page « non autorisé ».';

$_lang['setting_upload_files'] = 'Types de fichiers uploadables';
$_lang['setting_upload_files_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être uploadés dans \'assets/files/\' en utilisant le gestionnaire de ressource. Veuillez entrer les extenssions pour chaque type de fichier, séparés par des virgules.';

$_lang['setting_upload_flash'] = 'Types de fichiers Flash uploadables';
$_lang['setting_upload_flash_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être uploadés dans \'assets/flash/\' en utilisant le gestionnaire de ressource. Veuillez entrer les extenssions pour chaque type de fichier flash, séparés par des virgules.';

$_lang['setting_upload_images'] = 'Types d\'images uploadables';
$_lang['setting_upload_images_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être uploadés dans \'assets/images/\' en utilisant le gestionnaire de ressource. Veuillez entrer les extenssions pour chaque type d\'images, séparés par des virgules.';

$_lang['setting_upload_maxsize'] = 'Taille maximale des uploads';
$_lang['setting_upload_maxsize_desc'] = 'Entrez la taille maximale des fichiers qui peuvent être uploadés via le gestionnaire de fichier. La taille doit être indiquée en octects (bytes). <strong>NOTE: Les fichiers volumineux peuvent demander beaoucp de temps pour être uploadés!</strong>';

$_lang['setting_upload_media'] = 'Types de média uploadables';
$_lang['setting_upload_media_desc'] = 'Ici vous pouvez indiquer une liste de fichiers qui peuvent être uploadés dans \'assets/media/\' en utilisant le gestionnaire de ressource. Veuillez entrer les extenssions pour chaque type de média, séparés par des virgules.';

$_lang['setting_use_alias_path'] = 'Utiliser les alias simples';
$_lang['setting_use_alias_path_desc'] = 'Sélectionner \'oui\' pour cette option affichera le chemin complet de la ressource si la ressource a un alias. Par exemple, si une ressource ayant pour alias \'enfant\' est située dans une ressource conteneur ayant pour alias \'parent\', alors l\'alias du chemin complet sera affiché \'/parent/enfant.html\'.<br /><strong>NOTE: Mettre \'oui\' dans cette option (activer les alias simples) implique l\'utilisation de chemin absolu pour les objets (tels qu\'images, css, javascripts, etc), par exemple : \'/assets/images\' au lieu de \'assets/images\'. En faisant de tel, vous éviterez au navigateur (ou serveur web) d\'ajouter le chemin relatif à l\'alias.</strong>';

$_lang['setting_use_browser'] = 'Activer le navigateur de ressouce';
$_lang['setting_use_browser_desc'] = 'Sélectionnez oui pour activer le navigateur de ressource. Ceci autorisera vos utilisateurs à naviger et uploader des ressources, telles que des images, du flash et d\'autres fichiers média sur le serveur.';
$_lang['setting_use_browser_err'] = 'Veuillez indiquer si vous désirez ou non utiliser le navigateur de ressource.';

$_lang['setting_use_editor'] = 'Activer l\'éditeur de texte riche';
$_lang['setting_use_editor_desc'] = 'Voulez-vous activer l\'éditeur de texte riche? Si vous êtes plus à l\'aise en rédigeant de l\'HTML alors vous pouvez désactiver l\'éditeur avec cette option. Notez que cette option s\'applique à tous les documents et utilisateurs!';
$_lang['setting_use_editor_err'] = 'Veuillez indiquer si vous désirez ou non utiliser un RTE.';

$_lang['setting_use_multibyte'] = 'Utiliser l\'extenssion Multibyte';
$_lang['setting_use_multibyte_desc'] = 'Mettre à oui si vous désirez utilisez l\'extenssion mbstring pour les caractères multibyte dans votre installation de MODx. À n\'activer que si l\'extenssion mbstring est installée.';

$_lang['setting_webpwdreminder_message'] = 'Email de rappel web';
$_lang['setting_webpwdreminder_message_desc'] = 'Entrez un message qui sera envoyé aux utilisateurs web lorsqu\'ils demanderont un nouveau mot de passe par email. Le gestionnaire de contenu envera un email contenant leur nouveau mot de passe et les informations d\'activation. <br /><strong>Note:</strong> Les placeholders sont remplacés par le gestionnaire de contenu lors de l\'envoi du message : <br /><br />[[+sname]] - Nom de votre site web, <br />[[+saddr]] - Addresse email du site web, <br />[[+surl]] - URL du site web, <br />[[+uid]] - Identifiant ou ID de l\'utilisateur, <br />[[+pwd]] - Mot de passe de l\'utilisateur, <br />[[+ufn]] - Nom complet de l\'utilisateur. <br /><br /><strong>Laissez [[+uid]] et [[+pwd]] dans l\'e-mail ou l\'itendifiant et le mot de passe ne seront pas envoyés et vos utilisateurs ne pourront se connecter!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Bonjour [[+uid]]\n\nPour activer votre nouveau mot de passe veuillez vous rendre à l\'adresse :\n\n[[+surl]]\n\nEn cas de réussite vous pourrez utiliser le mot de passe suivant pour vous connecter :\n\nPassword:[[+pwd]]\n\nSi vous n\'avez pas demandé cet email, veuillez alors l\'ignorer.\n\nCordialement,\nL\'adminstrateur du site';

$_lang['setting_websignupemail_message'] = 'E-mail d\'inscription web';
$_lang['setting_websignupemail_message_desc'] = 'Ici vous pouvez définir le message envoyé à vos utilisateurs web quand vous leur créez un compte web et laissez le gestionnaire de contenu leur envoyer un e-mail contenant leur identifiant et leur mot de passe. <br /><strong>Note:</strong> Les placeholders sont remplacés par le gestionnaire de contenu quand le message est envoyé: <br /><br />[[+sname]] - Nom de votre site web, <br />[[+saddr]] - Adresse e-mail de votre site internet, <br />[[+surl]] - URL du site internet, <br />[[+uid]] - Identifiant ou nom ou ID de l\'utilisateur, <br />[[+pwd]] - Mot de passe de l\'utilisateur, <br />[[+ufn]] - Nom complet de l\'utilisateur. <br /><br /><strong>Laissez [[+uid]] et [[+pwd]] dans l\'e-mail, sinon l\'identifiant et le mot de passe ne seront pas envoyés et vos utilisateurs ne pourront se connecter!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Bonjour [[+uid]] \n\nVoici vos informations de connexion pour l\'utilisateur [[+sname]]:\n\nIdentifiant: [[+uid]]\nMot de passe: [[+pwd]]\n\nLors de votre connexion avec l\'utilisateur [[+sname]] ([[+surl]]), vous pourrez changer votre mot de passe.\n\nCordialement,\nL\'Administrateur';

$_lang['setting_welcome_screen'] = 'Afficher l\'écran de bienvenue';
$_lang['setting_welcome_screen_desc'] = 'Coché, la page de bienvenue sera affichée au prochain chargement de la page et ne s\'affichera plus par la suite.';
$_lang['setting_which_editor'] = 'Éditeur à utiliser';
$_lang['setting_which_editor_desc'] = 'Ici vous pouvez choisir quel RTE vous souhaitez utiliser. Vous pouvez télécharger et installer d\'autres RTE depuis la page de téléchargement de MODX.';

$_lang['setting_which_element_editor'] = 'Éditeur à utiliser pour les éléments';
$_lang['setting_which_element_editor_desc'] = 'Vous pouvez indiquer ici quel éditeur de texte riche vous souhaitez utiliser pour éditer les éléments. Vous pouvez télécharger et installer des éditeurs additionnels depuis le gestionnaire de package.';
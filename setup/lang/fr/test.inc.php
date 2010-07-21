<?php
/**
 * Test-related French Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Vérification si <span class="mono">%s</span> existe et est accessible en écriture: ';
$_lang['test_config_file_nw'] = 'Pour une nouvelle installation sous Linux/Unix, veuillez créer un fichier vierge nommé <span class="mono">%s.inc.php</span> dans votre répertoire de base MODx <span class="mono">config/</span> avec les permissions pour être accessible en écriture par PHP.';
$_lang['test_db_check'] = 'Création de la connexion à la base de données: ';
$_lang['test_db_check_conn'] = 'Veuillez vérifier vos informations de connexion puis réessayer.';
$_lang['test_db_failed'] = 'Échec de connexion à la base de données!';
$_lang['test_db_setup_create'] = 'MODx va tenter de créer la base de données.';
$_lang['test_dependencies'] = 'Vérification de PHP pour la dépendance zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Votre installation de PHP ne contient pas l\'extension "zlib". Celle-ci est nécessaire au bon fonctionnement de MODx. Veuillez activer cette extension pour continuer.';
$_lang['test_directory_exists'] = 'Vérification de l\'existence du répertoire <span class="mono">%s</span>: ';
$_lang['test_directory_writable'] = 'Vérification si le répertoire <span class="mono">%s</span> est accessible en écriture: ';
$_lang['test_memory_limit'] = 'Vérification si la limite de mémoire est fixée au minimum à 24M: ';
$_lang['test_memory_limit_fail'] = 'MODx a détecté une configuration de memory_limit inférieure aux 24M recommandés. MODx a tenté sans succès de paramétrer memory_limit à 24M. Veuillez paramétrer la valeur de memory_limit au minimum à 24M dans votre fichier php.ini. Si vous rencontrez à nouveau des problèmes (tels qu\'un écran blanc à l\'installation), veuillez augmenter la valeur à 32M, 64M ou plus.';
$_lang['test_memory_limit_success'] = 'OK! Défini à %s';
$_lang['test_mysql_version_5051'] = 'MODx aura des problèmes avec votre version de MySQL (%s), ceci en raison de multiples bugs liés aux pilotes PDO sur cette version. Veuillez mettre à jour MySQL pour corriger ces problèmes. Même si vous choisissez de ne pas utiliser MODx, il est recommandé de mettre à jour MySQL afin d\'assurer la stabilité et la sécurité de votre propre site web.';
$_lang['test_mysql_version_client_nf'] = 'Impossible de détecter la version du client MySQL!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODx n\'a pas réussi à détecter votre version du client MySQL par mysql_get_client_info(). Veuillez vérifier manuellement que votre version du client MySQL est supérieure ou égale à 4.1.20 avant de continuer.';
$_lang['test_mysql_version_client_old'] = 'MODx peut avoir des problèmes à cause de votre très ancienne version du client MySQL (%s)';
$_lang['test_mysql_version_client_old_msg'] = 'MODx autorisera l\'installation en utilisant ce client MySQL, mais nous ne garantissons pas que toutes les fonctionnalités seront disponibles ou fonctionneront correctement en utilisant des anciennes librairies de client MySQL.';
$_lang['test_mysql_version_client_start'] = 'Vérification de la version du client MySQL:';
$_lang['test_mysql_version_fail'] = 'Vous utilisez MySQL %s, et MODx Revolution requiert MySQL 4.1.20 ou supérieur. Veuillez mettre à jour MySQL en version 4.1.20 au minimum.';
$_lang['test_mysql_version_server_nf'] = 'Impossible de détecter la version du serveur MySQL!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODx n\'a pas réussi à détecter votre version du serveur MySQL par mysql_get_server_info(). Veuillez vérifier manuellement que votre version du serveur MySQL est supérieure ou égale à 4.1.20 avant de continuer.';
$_lang['test_mysql_version_server_start'] = 'Vérification de la version du serveur MySQL:';
$_lang['test_mysql_version_success'] = 'OK! Vous utilisez: %s';
$_lang['test_php_version_fail'] = 'Vous utilisez PHP %s, et MODx Revolution requiert PHP 5.1.1 ou supérieur. Veuillez mettre à jour PHP en version 5.1.1 au minimum. MODx recommande une mise à jour vers la version 5.3.0.';
$_lang['test_php_version_516'] = 'MODx aura des problèmes avec votre version de PHP (%s), ceci en raison de multiples bugs liés aux pilotes PDO sur cette version. Veuillez mettre à jour PHP en version 5.3.0 ou supérieure, afin de corriger ces problèmes. MODx recommande une mise à jour en version 5.3.2+. Même si vous choisissez de ne pas utiliser MODx, il est recommandé de mettre à jour PHP afin d\'assurer la stabilité et la sécurité de votre propre site web.';
$_lang['test_php_version_520'] = 'MODx aura des problèmes avec votre version de PHP (%s), ceci en raison de multiples bugs liés aux pilotes PDO sur cette version. Veuillez mettre à jour PHP en version 5.3.0 ou supérieure, afin de corriger ces problèmes. MODx recommande une mise à jour en version 5.3.2+. Même si vous choisissez de ne pas utiliser MODx, il est recommandé de mettre à jour PHP afin d\'assurer la stabilité et la sécurité de votre propre site web.';
$_lang['test_php_version_start'] = 'Vérification de la version de PHP:';
$_lang['test_php_version_success'] = 'OK! Vous utilisez: %s';
$_lang['test_sessions_start'] = 'Vérification si les sessions sont correctement configurées:';
$_lang['test_simplexml'] = 'Vérification de SimpleXML:';
$_lang['test_simplexml_nf'] = 'Impossible de trouver SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODx n\'a pas réussi à détecter SimpleXML dans votre environnement PHP. Le gestionnaire de paquets et d\'autres fonctions ne seront donc pas utilisables. Vous pouvez continuer l\'installation, mais MODx recommande l\'activation de SimpleXML afin d\'utiliser les fonctions avancées.';
$_lang['test_table_prefix'] = 'Vérification du préfixe de table `%s`: ';
$_lang['test_table_prefix_inuse'] = 'Le préfixe est déjà utilisé dans cette base de données!';
$_lang['test_table_prefix_inuse_desc'] = 'MODx n\'a pas pu être installé dans la base de données sélectionnée, car celle-ci contient déjà des tables avec le préfixe spécifié. Veuillez choisir un nouveau table_prefix, et relancer l\'installation.';
$_lang['test_table_prefix_nf'] = 'Préfixe de table inexistant dans la base de données!';
$_lang['test_table_prefix_nf_desc'] = 'MODx n\'a pas pu être installé dans la base de données sélectionnée, car celle-ci ne contient aucune table à mettre à jour avec le préfixe spécifié. Veuillez choisir un table_prefix existant, et relancer l\'installation.';
$_lang['test_zip_memory_limit'] = 'Vérification si la limite de mémoire est fixée au minimum à 24M pour les extensions zip: ';
$_lang['test_zip_memory_limit_fail'] = 'MODx a détecté une configuration du memory_limit en dessous des 24M recommandés. MODx a tenté sans succès de paramétrer memory_limit à 24M. Avant de continuer, veuillez paramétrer la valeur de memory_limit au minimum à 24M dans le fichier php.ini, ceci afin d\'assurer un fonctionnement optimal des extensions zip.';

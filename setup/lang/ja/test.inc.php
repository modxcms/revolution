<?php
/**
 * Test-related Japanese Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 * @author KUROI Enogu http://twitter.com/enogu
 * @author yamamoto http://kyms.jp
 */
$_lang['test_config_file'] = '<span class="mono">%s</span>が書き込み可能になっているか確認します:';
$_lang['test_config_file_nw'] = 'Linux/Unix環境に新しくインストールする場合、<span class="mono">core/config/</span>ディレクトリーに<span class="mono">%s.inc.php</span>という名前の空ファイルを作成し、PHPからの書き込みを許可してください。';
$_lang['test_db_check'] = 'データベース設定の作成: ';
$_lang['test_db_check_conn'] = '設定を確認して再試行してください。';
$_lang['test_db_failed'] = 'データベースに接続できませんでした。';
$_lang['test_db_setup_create'] = 'データベースを作成しています。';
$_lang['test_dependencies'] = 'zlibの確認: ';
$_lang['test_dependencies_fail_zlib'] = 'お使いのPHP実行環境ではzlibが有効になっていません。MODxはzlibを必要とします。zlibを有効にしてから再試行してください。';
$_lang['test_directory_exists'] = '<span class="mono">%s</span>ディレクトリーが存在するか確認します: ';
$_lang['test_directory_writable'] = '<span class="mono">%s</span>ディレクトリーが書き込み可能か確認します: ';
$_lang['test_memory_limit'] = 'メモリーサイズ(24M以上必要)の確認: ';
$_lang['test_memory_limit_fail'] = 'PHPのmemory_limitが推奨値(24M以上)を下回っています。MODxはmemory_limitの自動設定に失敗しました。インストールを続ける前にphp.iniを編集してください。';
$_lang['test_memory_limit_success'] = 'OK! Set to %s';
$_lang['test_mysql_version_5051'] = 'MODx will have issues on your MySQL version (%s), because of the many bugs related to the PDO drivers on this version. Please upgrade MySQL to patch these problems. Even if you choose not to use MODx, it is recommended you upgrade to this version for the security and stability of your own website.';
$_lang['test_mysql_version_client_nf'] = 'Could not detect MySQL client version!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODx could not detect your MySQL client version via mysql_get_client_info(). Please manually make sure that your MySQL client version is at least 4.1.20 before proceeding.';
$_lang['test_mysql_version_client_old'] = 'MODx may have issues because you are using a very old MySQL client version (%s)';
$_lang['test_mysql_version_client_old_msg'] = 'MODx will allow installation using this MySQL client version, but we cannot guarantee all functionality will be available or work properly when using older versions of the MySQL client libraries.';
$_lang['test_mysql_version_client_start'] = 'Checking MySQL client version:';
$_lang['test_mysql_version_fail'] = 'You are running on MySQL %s, and MODx Revolution requires MySQL 4.1.20 or later. Please upgrade MySQL to at least 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Could not detect MySQL server version!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODx could not detect your MySQL server version via mysql_get_server_info(). Please manually make sure that your MySQL server version is at least 4.1.20 before proceeding.';
$_lang['test_mysql_version_server_start'] = 'Checking MySQL server version:';
$_lang['test_mysql_version_success'] = 'OK! Running: %s';
$_lang['test_php_version_fail'] = 'お使いのPHPのバージョンは%sです。MODx Revolutionの動作にはPHP 5.1.1以降のバージョンが必要です。';
$_lang['test_php_version_516'] = 'MODx will have issues on your PHP version (%s), because of the many bugs related to the PDO drivers on this version. Please upgrade PHP to version 5.3.0 or higher, which patches these problems. MODx recommends upgrading to 5.3.2+. Even if you choose not to use MODx, it is recommended you upgrade to this version for the security and stability of your own website.';
$_lang['test_php_version_520'] = 'MODx will have issues on your PHP version (%s), because of the many bugs related to the PDO drivers on this version. Please upgrade PHP to version 5.3.0 or higher, which patches these problems. MODx recommends upgrading to 5.3.2+. Even if you choose not to use MODx, it is recommended you upgrade to this version for the security and stability of your own website.';
$_lang['test_php_version_start'] = 'PHPバージョンの確認: ';
$_lang['test_php_version_success'] = 'OK! Running: %s';
$_lang['test_sessions_start'] = 'セッション設定の確認: ';
$_lang['test_simplexml'] = 'Checking for SimpleXML:';
$_lang['test_simplexml_nf'] = 'Could not find SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODx could not find SimpleXML on your PHP environment. Package Management and other functionality will not work without this installed. You may continue with installation, but MODx recommends enabling SimpleXML for advanced features and functionality.';
$_lang['test_table_prefix'] = '`%s`のテーブルプレフィックスの確認: ';
$_lang['test_table_prefix_inuse'] = 'このテーブルプレフィックスはすでに使用されています。';
$_lang['test_table_prefix_inuse_desc'] = '指定されたテーブルプレフィックスが既に使用されているため、インストールを続行できませんでした。別のプレフィックスを指定し、テストを再試行してください。';
$_lang['test_table_prefix_nf'] = '指定されたプレフィックスのテーブルは存在しません。';
$_lang['test_table_prefix_nf_desc'] = 'アップグレード対象のテーブルを発見できなかったため、インストールを続行できませんでした。既存のテーブルプレフィックスを指定できているか確認し、テストを再試行してください。';
$_lang['test_zip_memory_limit'] = 'zip拡張モジュールのメモリーサイズ(24MB以上必要)の確認: ';
$_lang['test_zip_memory_limit_fail'] = 'PHPのmemory_limitが推奨値(24M以上)を下回っています。MODxはmemory_limitの自動設定に失敗しました。インストールを続ける前にphp.iniを編集してください。';

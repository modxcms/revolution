<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = '<span class="mono">[[+file]]</span>が書き込み可能になっているか確認します。:';
$_lang['test_config_file_nw'] = 'Linux/Unix環境に新しくインストールする場合、<span class="mono">core/config/</span>ディレクトリーに<span class="mono">[[+key]].inc.php</span>という名前の空ファイルを作成し、PHPからの書き込みを許可してください。';
$_lang['test_db_check'] = 'データベース設定の作成: ';
$_lang['test_db_check_conn'] = '設定を確認して再試行してください。';
$_lang['test_db_failed'] = 'データベースに接続できませんでした。';
$_lang['test_db_setup_create'] = 'データベースを作成しています。';
$_lang['test_dependencies'] = 'zlibの確認: ';
$_lang['test_dependencies_fail_zlib'] = 'お使いのPHP実行環境ではzlibが有効になっていません。MODXはzlibを必要とします。zlibを有効にしてから再試行してください。';
$_lang['test_directory_exists'] = '<span class="mono">[[+dir]]</span>ディレクトリーが存在するか確認します: ';
$_lang['test_directory_writable'] = '<span class="mono">[[+dir]]</span>ディレクトリーが書き込み可能か確認します: ';
$_lang['test_memory_limit'] = 'メモリーサイズ(24M以上必要)の確認: ';
$_lang['test_memory_limit_fail'] = 'このサーバのmemory_limit設定の値は [[+memory]] になっています。MODXのインストーラを実行するためには24MB必要であるため一時的な設定の変更を試みましたが、変更できませんでした。サーバのphp.ini設定を確認し、memory_limitの値を24MB以上(できれば32-64MB以上)に設定してください。';
$_lang['test_memory_limit_success'] = '問題なし - [[+memory]]に設定されています。';
$_lang['test_mysql_version_5051'] = 'お使いのMySQL環境([[+version]])のPDOドライバーには複数の不具合があるため、MODXが正常に動作しない恐れがあります。MySQLをアップグレードし、適切なパッチを当てることをおすすめします。';
$_lang['test_mysql_version_client_nf'] = 'MySQLクライアントのバージョンが確認できません。';
$_lang['test_mysql_version_client_nf_msg'] = 'mysql_get_client_info()関数を使ったMySQL clientのバージョン取得に失敗しました。セットアップを続行する前に、お使いのMySQL clientのバージョンが4.1.20以上である事を手動で確認してください。';
$_lang['test_mysql_version_client_old'] = 'MODXは現在お使いのMySQLライブラリ([[+version]])では正常に動作しない恐れがあります。';
$_lang['test_mysql_version_client_old_msg'] = 'この環境へのMODXのインストールは不可能ではありませんが、開発チームが想定しない動作をする可能性があります。';
$_lang['test_mysql_version_client_start'] = 'MySQL clientのバージョンをチェック中:';
$_lang['test_mysql_version_fail'] = 'MODXは、お使いのMySQLのバージョン([[+version]])をサポートしていません。MySQLをバージョン4.1.20以上にアップグレードして下さい。';
$_lang['test_mysql_version_server_nf'] = 'MySQLサーバーのバージョンが確認できません。';
$_lang['test_mysql_version_server_nf_msg'] = 'mysql_get_server_info()関数を使ったMySQL serverのバージョン取得に失敗しました。セットアップを続行する前に、お使いのMySQL clientのバージョンが4.1.20以上である事を手動で確認してください。';
$_lang['test_mysql_version_server_start'] = 'MySQL Serverのバージョンをチェック中:';
$_lang['test_mysql_version_success'] = 'OK! MySQLの動作を確認: バージョン[[+version]]';
$_lang['test_nocompress'] = 'Checking if we should disable CSS/JS compression: ';
$_lang['test_nocompress_disabled'] = 'OK! Disabled.';
$_lang['test_nocompress_skip'] = 'Not selected, skipping test.';
$_lang['test_php_version_fail'] = 'お使いのPHPのバージョンは[[+version]]です。MODX Revolutionの動作にはPHP 5.1.1以降のバージョンが必要です。';
$_lang['test_php_version_start'] = 'PHPバージョンの確認: ';
$_lang['test_php_version_success'] = 'OK! MySQLの動作を確認: バージョン[[+version]]';
$_lang['test_safe_mode_start'] = 'セーフモード（safe_mode）の無効を確認:';
$_lang['test_safe_mode_fail'] = 'お使いのPHPではセーフモードが有効です。処理を続行するには、セーフモードを無効にする必要があります。';
$_lang['test_sessions_start'] = 'セッション設定の確認: ';
$_lang['test_simplexml'] = 'SimpleXMLの確認:';
$_lang['test_simplexml_nf'] = 'SimpleXMLが見つかりません';
$_lang['test_simplexml_nf_msg'] = 'お使いのPHP実行環境ではSimpleXMLが有効になっていません。パッケージマネジメントシステム、ならびに一部の機能が動作しなくなります。このままインストールを続行することも可能ですが、MODXの先進的な機能を十分に利用するためにはSimpleXMLを有効にすることを強くおすすめします。';
$_lang['test_suhosin'] = 'Suhosinによる問題をチェックしています:';
$_lang['test_suhosin_max_length'] = 'Suhosinによって制限されたGETの最大値が低すぎます。';
$_lang['test_suhosin_max_length_err'] = 'お使いのPHP実行環境では、PHP用セキュリティパッチ『Suhosin』が適用されています。そのSuhoshinの設定suhosin.get.max_value_lengthが、MODXが管理画面用JavaScriptを圧縮するには低すぎる値です。MODXを利用する場合、値を4096に引き上げることをおすすめします。<br />それまでの間このエラーを避けるために、MODXは自動的にJavaScriptの圧縮設定（compress_js）を0に設定します。';
$_lang['test_table_prefix'] = 'テーブルプレフィックス `[[+prefix]]` を確認: ';
$_lang['test_table_prefix_inuse'] = 'このテーブルプレフィックスはすでに使われています。';
$_lang['test_table_prefix_inuse_desc'] = '指定されたテーブルプレフィックスがすでに使用されているため、インストールを続行できませんでした。別のプレフィックスを指定し、テストを再試行してください。';
$_lang['test_table_prefix_nf'] = '指定されたプレフィックスのテーブルは存在しません。';
$_lang['test_table_prefix_nf_desc'] = 'アップデート対象のテーブルを発見できなかったため、インストールを続行できませんでした。既存のテーブルプレフィックスを指定できているか確認し、テストを再試行してください。';
$_lang['test_zip_memory_limit'] = 'zip拡張モジュールのメモリーサイズ(24MB以上必要)の確認: ';
$_lang['test_zip_memory_limit_fail'] = 'PHPのmemory_limitが下限値(24M以上)を下回っています。MODXはmemory_limitの自動設定に失敗しました。インストールを続ける前にphp.iniを編集してください。';
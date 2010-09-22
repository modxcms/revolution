<?php
/**
 * Test-related Japanese Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 * @author KUROI Enogu http://twitter.com/enogu
 * @author yamamoto http://kyms.jp
 */
$_lang['test_config_file'] = '<span class="mono">[[+file]]</span>が書き込み可能になっているか確認します:';
$_lang['test_config_file_nw'] = 'Linux/Unix環境に新しくインストールする場合、<span class="mono">core/config/</span>ディレクトリーに<span class="mono">[[+key]].inc.php</span>という名前の空ファイルを作成し、PHPからの書き込みを許可してください。';
$_lang['test_db_check'] = 'データベース設定の作成: ';
$_lang['test_db_check_conn'] = '設定を確認して再試行してください。';
$_lang['test_db_failed'] = 'データベースに接続できませんでした。';
$_lang['test_db_setup_create'] = 'データベースを作成しています。';
$_lang['test_dependencies'] = 'zlibの確認: ';
$_lang['test_dependencies_fail_zlib'] = 'お使いのPHP実行環境ではzlibが有効になっていません。MODxはzlibを必要とします。zlibを有効にしてから再試行してください。';
$_lang['test_directory_exists'] = '<span class="mono">[[+dir]]</span>ディレクトリーの存在: ';
$_lang['test_directory_writable'] = '<span class="mono">[[+dir]]</span>ディレクトリーの書き込み属性: ';
$_lang['test_memory_limit'] = 'メモリーサイズ(24M以上必要)の確認: ';
$_lang['test_memory_limit_fail'] = 'PHPのmemory_limitが推奨値(24M以上)を下回っています。MODxはmemory_limitの自動設定に失敗しました。インストールを続ける前にphp.iniを編集してください。';
$_lang['test_memory_limit_success'] = '問題なし - [[+memory]]に設定されています。';
$_lang['test_mysql_version_5051'] = 'お使いのMySQL環境([[+version]])のPDOドライバーには複数の不具合があるため、MODxが正常に動作しない恐れがあります。MySQLをアップグレードし、適切なパッチを当てることをおすすめします。';
$_lang['test_mysql_version_client_nf'] = 'MySQLクライアントのバージョンが確認できません。';
$_lang['test_mysql_version_client_nf_msg'] = 'MODxはお使いのMySQLクライアントのバージョンを確認できませんでした。MySQLクライアントのバージョンが4.1.20以上であることをご確認下さい。';
$_lang['test_mysql_version_client_old'] = 'MODxは現在お使いのMySQLライブラリ([[+version]])では正常に動作しない恐れがあります。';
$_lang['test_mysql_version_client_old_msg'] = 'この環境へのMODxのインストールは不可能ではありませんが、開発チームが想定しない動作をする可能性があります。';
$_lang['test_mysql_version_client_start'] = 'MySQLクライアントのバージョンの確認:';
$_lang['test_mysql_version_fail'] = 'お使いのMySQLのバージョンは [[+version]] です。MODx Revolutionを動作させるにはMySQL 4.1.20以上である必要があります。MySQLをアップグレードして再試行して下さい。';
$_lang['test_mysql_version_server_nf'] = 'MySQLサーバーのバージョンが確認できません。';
$_lang['test_mysql_version_server_nf_msg'] = 'MODxはお使いのMySQLサーバーのバージョンを確認できませんでした。MODxはMySQL 4.1.20以上で動作します。MySQLサーバーのバージョンがあっているかご確認下さい。';
$_lang['test_mysql_version_server_start'] = 'MySQLサーバーのバージョンの確認:';
$_lang['test_mysql_version_success'] = '問題なし 実行中のバージョン : [[+version]]';
$_lang['test_php_version_fail'] = 'お使いのPHPのバージョンは[[+version]]です。MODx Revolutionの動作にはPHP 5.1.1以降のバージョンが必要です。';
$_lang['test_php_version_516'] = 'お使いのPHP実行環境([[+version]])のPDOドライバーには複数の不具合があるため、MODxが正常に動作しない恐れがあります。PHPを5.3.0以上にバージョンアップし、必要なパッチを当てることをおすすめします。MODxはPHP 5.3.2以上で最適に動作します。';
$_lang['test_php_version_520'] = 'お使いのPHP実行環境([[+version]])のPDOドライバーには複数の不具合があるため、MODxが正常に動作しない恐れがあります。PHPを5.3.0以上にバージョンアップし、必要なパッチを当てることをおすすめします。MODxはPHP 5.3.2以上で最適に動作します。';
$_lang['test_php_version_start'] = 'PHPバージョンの確認: ';
$_lang['test_php_version_success'] = '問題なし - 実行中のバージョン : [[+version]]';
$_lang['test_sessions_start'] = 'セッション設定の確認: ';
$_lang['test_simplexml'] = 'SimpleXMLの確認:';
$_lang['test_simplexml_nf'] = 'SimpleXMLが見つかりません';
$_lang['test_simplexml_nf_msg'] = 'お使いのPHP実行環境ではSimpleXMLが有効になっていません。パッケージマネジメントシステム、ならびに一部の機能が動作しなくなります。このままインストールを続行することも可能ですが、MODxの先進的な機能を十分に利用するためにはSimpleXMLを有効にすることを強くおすすめします。';
$_lang['test_table_prefix'] = 'テーブルプレフィックス`[[+prefix]]`を確認: ';
$_lang['test_table_prefix_inuse'] = 'このテーブルプレフィックスはすでに使用されています。';
$_lang['test_table_prefix_inuse_desc'] = '指定されたテーブルプレフィックスが既に使用されているため、インストールを続行できませんでした。別のプレフィックスを指定し、テストを再試行してください。';
$_lang['test_table_prefix_nf'] = '指定されたプレフィックスのテーブルは存在しません。';
$_lang['test_table_prefix_nf_desc'] = 'アップデート対象のテーブルを発見できなかったため、インストールを続行できませんでした。既存のテーブルプレフィックスを指定できているか確認し、テストを再試行してください。';
$_lang['test_zip_memory_limit'] = 'zip拡張モジュールのメモリーサイズ(24MB以上必要)の確認: ';
$_lang['test_zip_memory_limit_fail'] = 'PHPのmemory_limitが下限値(24M以上)を下回っています。MODxはmemory_limitの自動設定に失敗しました。インストールを続ける前にphp.iniを編集してください。';
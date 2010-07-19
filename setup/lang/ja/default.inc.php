<?php
/**
 * Japanese language files for Revolution 2.0.0 setup
 *
 * @package setup
 * @author KUROI Enogu http://twitter.com/enogu
 * @author yamamoto http://kyms.jp
 */
$_lang['additional_css'] = 'CSS';
$_lang['addons'] = 'アドオン';
$_lang['advanced_options'] = 'Advanced Options';
$_lang['all'] = '全て';
$_lang['app_description'] = 'CMS、そしてPHPアプリケーションフレームワーク';
$_lang['app_motto'] = 'MODx Create and Do More with Less';
$_lang['back'] = '戻る';
$_lang['base_template'] = 'BaseTemplate';
$_lang['cache_manager_err'] = 'MODxのキャッシュマネージャがロードできませんでした。';
$_lang['choose_language'] = '言語を選択してください';
$_lang['cleanup_errors_title'] = 'Important Note:';
$_lang['close'] = '閉じる';
$_lang['config_file_err_w'] = '設定ファイルの書き込みに失敗しました。';
$_lang['config_file_perms_notset'] = '設定ファイルのパーミッションが修正されていません。設定ファイルを保護するために、適切なパーミッションに変更することを強くお勧めします。';
$_lang['config_file_perms_set'] = '設定ファイルのパーミッションを修正しました。';
$_lang['config_file_written'] = '設定ファイルの作成が完了しました。';
$_lang['config_key'] = 'MODx Configuration Key';
$_lang['config_key_change'] = 'If you would like to change the MODx configuration key, <a id="cck-href" href="javascript:void(0);">please click here.</a>';
$_lang['config_key_override'] = 'If you wish to run setup on a configuration key other than the one currently specified in your setup/includes/config.core.php, please specify it below.';
$_lang['config_not_writable_err'] = 'You have attempted to change a setting in setup/includes/config.core.php but the file is not writable. Make the file writable or edit the file manually before continuing.';
$_lang['connection_character_set'] = 'データベース接続の文字セット:';
$_lang['connection_collation'] = '文字セットの照合順序:';
$_lang['connection_connection_and_login_information'] = 'データベース接続とデータベースユーザーの指定';
$_lang['connection_connection_note'] = 'MODxをインストールするために作成したデータベースの名前を入力してください。まだデータベースを作成していない場合、インストーラーはデータベースの自動作成を試みます。自動作成機能はMySQLの設定、ならびにデータベースユーザーのアクセス権限に依存していることを銘記してください。';
$_lang['connection_database_host'] = 'データベースホスト:';
$_lang['connection_database_info'] = 'データベースにログインするための情報を入力してください。';
$_lang['connection_database_login'] = 'データベースユーザー名:';
$_lang['connection_database_name'] = 'データベース名:';
$_lang['connection_database_pass'] = 'データベースパスワード:';
$_lang['connection_default_admin_email'] = '管理者のメールアドレス:';
$_lang['connection_default_admin_login'] = '管理者のユーザー名:';
$_lang['connection_default_admin_note'] = '最初の管理者ユーザーを作成します。セットアップが完了したあと最低でも一度はログインする必要があります。パスワードは後で変更できますので、まずは覚えやすいパスワードを入力することをお勧めします。';
$_lang['connection_default_admin_password'] = '管理者のパスワード:';
$_lang['connection_default_admin_password_confirm'] = 'もう一度入力してください:';
$_lang['connection_default_admin_user'] = 'デフォルト管理ユーザー';
$_lang['connection_table_prefix'] = 'テーブル名のプレフィックス:';
$_lang['connection_test_connection'] = '接続テスト';
$_lang['connection_title'] = '接続情報';
$_lang['context_connector_options'] = '<strong>コネクターコンテキスト</strong> (AJAXコネクターサービス)';
$_lang['context_connector_path'] = 'コネクターコンテキストのファイルパス';
$_lang['context_connector_url'] = 'コネクターコンテキストのURL';
$_lang['context_installation'] = 'コンテキストのインストール';
$_lang['context_manager_options'] = '<strong>マネージャーコンテキスト</strong> (管理ページ)';
$_lang['context_manager_path'] = 'マネージャコンテキストのファイルパス';
$_lang['context_manager_url'] = 'マネージャコンテキストのURL';
$_lang['context_override'] = 'Leave these disabled to allow the system to auto-determine this information as shown.  By enabling a specific value, regardless if you manually set the value, you are indicating that you want the path to be set explicitly to that value in the configuration.';
$_lang['context_web_options'] = '<strong>ウェブコンテキスト</strong> (あなたのウェブサイト)';
$_lang['context_web_path'] = 'ウェブコンテキストのファイルパス';
$_lang['context_web_url'] = 'ウェブコンテキストのURL';
$_lang['continue'] = '続ける';
$_lang['dau_err_save'] = 'デフォルト管理ユーザーを登録できませんでした。';
$_lang['dau_saved'] = 'デフォルト管理ユーザーを登録しました。';
$_lang['db_check_db'] = 'Checking database:';
$_lang['db_connecting'] = 'Connecting to mysql server:';
$_lang['db_connected'] = 'データベース接続を確認しました。';
$_lang['db_created'] = 'データベースは正しく作成されました。';
$_lang['db_err_connect'] = 'データベースに接続できませんでした。';
$_lang['db_err_connect_upgrade'] = 'アップグレードを試みましたが、データベースに接続できません。設定をもう一度確認してください。';
$_lang['db_err_connect_server'] = 'Could not connect to the database server.  Check the connection properties and try again.';
$_lang['db_err_create'] = 'データベースの作成中にエラーが発生しました。';
$_lang['db_err_create_database'] = 'MODx could not create your database. Please manually create your database and then try again.';
$_lang['db_err_show_charsets'] = 'MODx could not get the available character sets from your MySQL server.';
$_lang['db_err_show_collations'] = 'MODx could not get the available collations from your MySQL server.';
$_lang['db_success'] = 'Success!';
$_lang['db_test_coll_msg'] = 'Create or test selection of your database.';
$_lang['db_test_conn_msg'] = 'Test database server connection and view collations.';
$_lang['default_admin_user'] = 'デフォルト管理ユーザー';
$_lang['delete_setup_dir'] = 'セットアップディレクトリを削除する';
$_lang['dir'] = 'ltr:左から右';
$_lang['email_err_ns'] = 'メールアドレスが正しくありません';
$_lang['err_occ'] = 'エラーが発生しました!';
$_lang['err_update_table'] = 'Error updating table for class %s';
$_lang['errors_occurred'] = 'コアのインストール中にエラーが発生しました。インストール状態を確認し、問題を解消してから再開してください。';
$_lang['failed'] = '問題有り';
$_lang['fatal_error'] = '致命的なエラー: MODxのセットアップが続行できません。';
$_lang['home'] = 'Home';
$_lang['img_banner'] = 'assets/images/img_banner.gif';
$_lang['img_box'] = 'assets/images/img_box.png';
$_lang['img_splash'] = 'assets/images/img_splash.gif';
$_lang['install'] = 'インストール';
$_lang['install_packages'] = 'パッケージのインストール';
$_lang['install_packages_desc'] = 'アドオンパッケージを追加インストールすることができます。必要なパッケージをインストールした後、完了ボタンを押してください。';
$_lang['install_packages_options'] = 'パッケージのインストールオプション';
$_lang['install_success'] = 'MODxコアは正常にインストールされました。続行ボタンを押して、インストール作業を完了させてください。';
$_lang['install_summary'] = 'インストールの概要';
$_lang['install_update'] = 'インストール/アップデート';
$_lang['license'] = '<p class="title">You must agree to the License before continuing installation.</p>
	<p>Usage of this software is subject to the GPL license. To help you understand
	what the GPL licence is and how it affects your ability to use the software, we
	have provided the following summary:</p>
	<h4>The GNU General Public License is a Free Software license.</h4>
	<p>Like any Free Software license, it grants to you the four following freedoms:</p>
	<ul>
        <li>The freedom to run the program for any purpose. </li>
        <li>The freedom to study how the program works and adapt it to your needs. </li>
        <li>The freedom to redistribute copies so you can help your neighbor. </li>
        <li>The freedom to improve the program and release your improvements to the
        public, so that the whole community benefits. </li>
	</ul>
	<p>You may exercise the freedoms specified here provided that you comply with
	the express conditions of this license. The principal conditions are:</p>
	<ul>
        <li>You must conspicuously and appropriately publish on each copy distributed an
        appropriate copyright notice and disclaimer of warranty and keep intact all the
        notices that refer to this License and to the absence of any warranty; and give
        any other recipients of the Program a copy of the GNU General Public License
        along with the Program. Any translation of the GNU General Public License must
        be accompanied by the GNU General Public License.</li>

        <li>If you modify your copy or copies of the program or any portion of it, or
        develop a program based upon it, you may distribute the resulting work provided
        you do so under the GNU General Public License. Any translation of the GNU
        General Public License must be accompanied by the GNU General Public License. </li>

        <li>If you copy or distribute the program, you must accompany it with the
        complete corresponding machine-readable source code or with a written offer,
        valid for at least three years, to furnish the complete corresponding
        machine-readable source code.</li>

        <li>Any of these conditions can be waived if you get permission from the
        copyright holder.</li>

        <li>Your fair use and other rights are in no way affected by the above.</li>
    </ul>
	<p>The above is a summary of the GNU General Public License. By proceeding, you
	are agreeing to the GNU General Public Licence, not the above. The above is
	simply a summary of the GNU General Public Licence, and its accuracy is not
	guaranteed. It is strongly recommended you read the <a href="http://www.gnu.org/copyleft/gpl.html" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">GNU General Public
	License</a> in full before proceeding, which can also be found in the license
	file distributed with this package.</p>
';
$_lang['license_agree'] = 'はい。私はこのライセンス条文に合意しました。';
$_lang['license_agreement'] = 'ライセンスについて';
$_lang['license_agreement_error'] = 'あなたはインストールを続ける前にライセンス文書を読み、その内容に合意する必要があります。';
$_lang['login'] = 'ログイン';
$_lang['modx_class_err_nf'] = 'MODxクラスの読み込みに失敗しました。';
$_lang['modx_configuration_file'] = 'MODx設定ファイル';
$_lang['modx_err_instantiate'] = 'MODxオブジェクトを生成できません。';
$_lang['modx_err_instantiate_mgr'] = 'MODxマネージャコンテキストの初期化に失敗しました。';
$_lang['modx_footer1'] = '&copy; 2005-' . date('Y') . ' the <a href="http://www.modxcms.com/" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;"  style="color: green; text-decoration:underline">MODx</a> Content Management Framework (CMF) project. All rights reserved. MODx is licensed under the GNU GPL.';
$_lang['modx_footer2'] = 'MODx is free software.  We encourage you to be creative and make use of MODx in any way you see fit. Just make sure that if you do make changes and decide to redistribute your modified MODx, that you keep the source code free!';
$_lang['modx_install'] = 'MODxのインストール';
$_lang['modx_install_complete'] = 'MODxのインストールが完了しました';
$_lang['modx_object_err'] = 'MODxオブジェクトがロードできませんでした。';
$_lang['next'] = '次へ';
$_lang['none'] = 'なし';
$_lang['ok'] = 'OK!';
$_lang['options_core_inplace'] = 'ファイルを既に配置しました<br /><small>(共用サーバーではこのオプションを有効にすることをお勧めします)</small><!--Files are already in-place<br /><small>(Recommended for installation on shared servers.)</small>-->';
$_lang['options_core_inplace_note'] = 'Subversionリポジトリ、またはMODxパッケージから抽出したファイルを事前に配置している場合、このボックスをオンにしてください。<!-- Check this if you exported MODx from SVN or extracted it from the full MODx package to the server prior to installation.-->';
$_lang['options_core_unpacked'] = 'コアパッケージを手動で解凍しました<br /><small>(共用サーバーではこのオプションを有効にすることをお勧めします)</small><!--Core Package has been manually unpacked<br /><small>(Recommended for installation on shared servers.)</small>-->';
$_lang['options_core_unpacked_note'] = '圧縮されたコアパッケージファイル(core/packages/core.transport.zip)を直接解凍して設置した場合、このボックスをオンにしてください。このオプションはタイムリミットが厳しく設定されたサーバーでファイルを展開する時間を節約するために使用できます。<!--Check this if you have manually extracted the core package from the file core/packages/core.transport.zip. This will reduce the time it takes for the installation process on systems that do not allow the PHP time_limit and Apache script execution time settings to be altered.-->';
$_lang['options_install_new_copy'] = '新しいMODx Revolutionの実行環境をインストールします。';
$_lang['options_install_new_note'] = 'このオプションをオンにするとデータが上書きされる場合が有ります。';
$_lang['options_important_upgrade'] = 'Important Upgrade Note';
$_lang['options_important_upgrade_note'] = 'アップグレードする前に、全てのマネージャー画面のユーザーがログアウトしたことを確認してください。もしアップグレード後に不具合が発生した場合、全てのマネージャー画面からログアウトし、ブラウザキャッシュをクリアしてもう一度ログインしてください。<!--Make sure all Manager users <strong>log out before upgrading</strong> to prevent problems (e.g., not being able to access resources). If you have trouble after upgrading, log out of any Manager sessions, clear your browser cache, then log in again.-->';
$_lang['options_new_file_permissions'] = 'New file permissions';
$_lang['options_new_file_permissions_note'] = 'You can override the permissions new files created via MODx will use, e.g. 0664 or 0666.';
$_lang['options_new_folder_permissions'] = 'New folder permissions';
$_lang['options_new_folder_permissions_note'] = 'You can override the permissions new folders created via MODx will use, e.g. 0775 or 0777.';
$_lang['options_new_installation'] = '新規インストール';
$_lang['options_title'] = 'インストールオプション';
$_lang['options_upgrade_advanced'] = 'アドバンスアップグレード<br /><small>(edit database config)</small>';
$_lang['options_upgrade_advanced_note'] = 'For advanced database admins or moving to servers with a different database connection character set. <strong>You will need to know your full database name, user, password and connection/collation details.</strong>';
$_lang['options_upgrade_existing'] = '既存サイトのアップデート';
$_lang['options_upgrade_existing_note'] = '現在のファイルとデータベースをアップグレードします。';
$_lang['package_err_install'] = '%s のインストールに失敗しました。';
$_lang['package_err_nf'] = '%s の取得に失敗しました。';
$_lang['package_installed'] = '%s は正しくインストールされました。';
$_lang['password_err_invchars'] = 'Your password may not contain any invalid characters, such as /, \\, &apos;, &quot;, or {}.';
$_lang['password_err_nomatch'] = 'パスワードが一致しません。';
$_lang['password_err_ns'] = 'パスワードを入力してください。';
$_lang['password_err_short'] = 'Your password must be at least 6 characters long.';
$_lang['please_select_login'] = 'ログインボタンを押すとマネージャーにアクセスできます。';
$_lang['preinstall_failure'] = 'インストール環境テストで問題が発見されました。問題を解消してから再試行してください。';
$_lang['preinstall_success'] = 'インストール環境テストをクリアしました。インストールボタンを押して続行してください。';
$_lang['refresh'] = 'リフレッシュ';
$_lang['request_handler_err_nf'] = '%s へのリクエストを処理できませんでした。必要なファイルが全てアップロードされているか確認してください。';
$_lang['restarted_msg'] = 'MODx had to restart the setup process as a security precaution because setup was idle for over 15 minutes. Please re-attempt running setup at this time.';
$_lang['retry'] = '再試行';
$_lang['security_notice'] = 'セキュリティー情報';
$_lang['select'] = '選択';
$_lang['setup_err_remove'] = 'セットアップディレクトリの削除中にエラーが発生しました。';
$_lang['setup_err_assets'] = 'Your assets/ directory was not created at: [[+path]] <br />You will need to create this directory and make it writable if you want to use Package Management or 3rd Party Components.';
$_lang['setup_err_assets_comp'] = 'Your assets/components/ directory was not created at: [[+path]] <br />You will need to create this directory and make it writable if you want to use Package Management or 3rd Party Components.';
$_lang['setup_err_core_comp'] = 'Your core/components/ directory was not created at: [[+path]] <br />You will need to create this directory and make it writable if you want to use Package Management or 3rd Party Components.';
$_lang['skip_to_bottom'] = '下までスクロール';
$_lang['success'] = '成功';
$_lang['table_created'] = '%s クラスに対応したテーブルは正しく作成されました。';
$_lang['table_err_create'] = '%s クラスに対応したテーブルの作成中にエラーが発生しました。';
$_lang['table_updated'] = 'Successfully upgraded table for class %s';
$_lang['test_class_nf'] = '%s のインストールテストクラスが発見できませんでした。<br />必要なファイルが全てアップロードされているか確認してください。';
$_lang['test_version_class_nf'] = 'Could not find the Install Test Versioner class at: %s <br />Please make sure you\'ve uploaded all the necessary files.';
$_lang['thank_installing'] = 'MODxを選んでいただきありがとうございます。';
$_lang['transport_class_err_load'] = 'Error loading transport class.';
$_lang['toggle'] = '表示/非表示の切り替え';
$_lang['toggle_success'] = '完了メッセージの表示を切り替える';
$_lang['toggle_warnings'] = '警告メッセージの表示を切り替える';
$_lang['username_err_invchars'] = 'Your username may not contain any invalid characters, such as /, \\, &apos;, &quot;, or {}.';
$_lang['username_err_ns'] = 'ユーザー名が不正です。';
$_lang['version'] = 'version';
$_lang['warning'] = 'Warning';
$_lang['welcome'] = 'MODxにようこそ';
$_lang['welcome_message'] = '<p>このプログラムはお使いのサーバーにMODxをインストールします。</p>
	<p>[次へ]を選択して作業を開始してください:</p>
';
$_lang['workspace_err_nf'] = '現在の作業ディレクトリが発見できませんでした。';
$_lang['workspace_err_path'] = '作業ディレクトリの設定中にエラーが発生しました。';
$_lang['workspace_path_update'] = '作業ディレクトリ情報は正しく更新されました。';
$_lang['versioner_err_nf'] = 'Could not find the Install Versioner at: %s <br />Please make sure you\'ve uploaded all the necessary files.';
$_lang['xpdo_err_ins'] = 'xPDOの初期化中にエラーが発生しました。';
$_lang['xpdo_err_nf'] = '%s にxPDOのファイルがありません。全てのファイルが正しくアップロードされているか確認してください。';

$_lang['preload_err_cache'] = '%scache ディレクトリーをPHPプロセスから書き込める状態にしてください。';
$_lang['preload_err_core_path'] = 'setup/includes/config.core.php を開き、MODX_CORE_PATHの値を修正してください。このパラメータはcoreディレクトリーの場所を指している必要があります。';
$_lang['preload_err_mysql'] = 'MySQL拡張モジュールが発見できませんでした。';
$_lang['preload_err_pdo'] = 'PDO拡張モジュールが発見できませんでした。';
$_lang['preload_err_pdo_mysql'] = 'PDO拡張モジュールのMySQLドライバーが発見できませんでした。';
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
$_lang['test_php_version_fail'] = 'お使いのPHPのバージョンは%sです。MODx Revolutionの動作にはPHP 5.1.1以降のバージョンが必要です。';
$_lang['test_php_version_sn'] = 'お使いのPHPのバージョンは%sです。現在MODxはこのバージョンのPHPをサポートしていますが、セキュリティー上の観点からバージョンアップを強くお勧めします。少なくともPHP5.1.1以上にアップデートし、必要なセキュリティパッチを当てるようにしてください。';
$_lang['test_php_version_start'] = 'PHPバージョンの確認: ';
$_lang['test_sessions_start'] = 'セッション設定の確認: ';
$_lang['test_table_prefix'] = '`%s`のテーブルプレフィックスの確認: ';
$_lang['test_table_prefix_inuse'] = 'このテーブルプレフィックスはすでに使用されています。';
$_lang['test_table_prefix_inuse_desc'] = '指定されたテーブルプレフィックスが既に使用されているため、インストールを続行できませんでした。別のプレフィックスを指定し、テストを再試行してください。';
$_lang['test_table_prefix_nf'] = '指定されたプレフィックスのテーブルは存在しません。';
$_lang['test_table_prefix_nf_desc'] = 'アップグレード対象のテーブルを発見できなかったため、インストールを続行できませんでした。既存のテーブルプレフィックスを指定できているか確認し、テストを再試行してください。';
$_lang['test_zip_memory_limit'] = 'zip拡張モジュールのメモリーサイズ(24MB以上必要)の確認: ';
$_lang['test_zip_memory_limit_fail'] = 'PHPのmemory_limitが推奨値(24M以上)を下回っています。MODxはmemory_limitの自動設定に失敗しました。インストールを続ける前にphp.iniを編集してください。';

<?php
/**
 * Japanese language files for Revolution 2.2 setup
 *
 * @package setup
 * @author yamamoto http://kyms.jp 2012-08-28
 * @author KUROI Enogu http://twitter.com/enogu
 * @author honda http://kogus.org
 */
$_lang['additional_css'] = '';
$_lang['addons'] = 'アドオン';
$_lang['advanced_options'] = 'その他のオプション';
$_lang['all'] = '全て';
$_lang['app_description'] = 'CMS、そしてPHPアプリケーションフレームワーク';
$_lang['app_motto'] = 'MODX Create and Do More with Less';
$_lang['back'] = '戻る';
$_lang['base_template'] = 'BaseTemplate';
$_lang['cache_manager_err'] = 'キャッシュマネージャーをロードできません。';
$_lang['choose_language'] = '言語を選択してください';
$_lang['cleanup_errors_title'] = 'Important Note:';
$_lang['cli_install_failed'] = 'インストールに失敗しました。 エラー: [[+errors]]';
$_lang['cli_no_config_file'] = 'CLIインストールの構成ファイル(例えば config.xml)が見つかりませんでした。コマンドラインからMODXをセットアップするには、構成ファイルを用意する必要があります。詳しくは公式ドキュメントを参照してください。';
$_lang['cli_tests_failed'] = 'インストールの事前テストに失敗しました。 エラー: [[+errors]]';
$_lang['close'] = '閉じる';
$_lang['config_file_err_w'] = '設定ファイルの書き込みに失敗しました。';
$_lang['config_file_perms_notset'] = 'configファイルのパーミッションが書き込み可能になっています。configファイルを保護するために、適切なパーミッションに変更することを強くお勧めします。';
$_lang['config_file_perms_set'] = '設定ファイルのパーミッションを修正しました。';
$_lang['config_file_written'] = '設定ファイルの作成が完了しました。';
$_lang['config_key'] = 'Configuration Key をここで指定';
$_lang['config_key_change'] = 'Configuration Keyを変更したい場合は <a id="cck-href" href="javascript:void(0);">ここをクリックしてください。</a><br />※通常は変更する必要はありません。';
$_lang['config_key_override'] = '/setup/includes/config.core.php内のMODX_CONFIG_KEY定数による指定を変更したい場合は、ここで新しいキー名を指定してください。';
$_lang['config_not_writable_err'] = '設定ファイル setup/includes/config.core.php が書き込み可能ではありません。パーミッションを書き込み可能に変更してください。';
$_lang['connection_character_set'] = 'データベース接続の文字セット<br />(通常はutf-8):';
$_lang['connection_collation'] = '文字セットの照合順序<br />(通常はutf8_general_ci):';
$_lang['connection_connection_and_login_information'] = 'データベース接続とデータベースユーザーの指定';
$_lang['connection_connection_note'] = 'データベース接続をテストし、設定します。まだデータベースを作成していない場合は、インストーラーはデータベースの自動作成を試みます。データベースの自動作成機能はサーバ側の権限設定に依存しており、多くの共用レンタルサーバではサポートされていないためご注意ください。';
$_lang['connection_database_host'] = 'データベースホスト:';
$_lang['connection_database_info'] = 'データベースへのログイン情報';
$_lang['connection_database_login'] = 'データベースユーザー名:';
$_lang['connection_database_name'] = 'データベース名:';
$_lang['connection_database_pass'] = 'データベースパスワード:';
$_lang['connection_database_type'] = 'データベースの種別:';
$_lang['connection_default_admin_email'] = '管理者のメールアドレス:';
$_lang['connection_default_admin_login'] = '管理者のユーザー名:';
$_lang['connection_default_admin_note'] = '最初の管理者ユーザーを作成します。<strong>セットアップが完了した後、最低でも一度はログインする必要があります。</strong>パスワードは後で変更できます。';
$_lang['connection_default_admin_password'] = '管理者のパスワード:';
$_lang['connection_default_admin_password_confirm'] = 'もう一度入力してください:';
$_lang['connection_default_admin_user'] = 'デフォルト管理ユーザー';
$_lang['connection_table_prefix'] = 'テーブル名のプレフィックス:';
$_lang['connection_test_connection'] = '接続テスト';
$_lang['connection_title'] = '接続情報';
$_lang['context_connector_options'] = '<strong>コネクターコンテキスト</strong> (AJAXコネクターサービス)';
$_lang['context_connector_path'] = 'コネクターコンテキストのファイルパス';
$_lang['context_connector_url'] = 'コネクターコンテキストのURL';
$_lang['context_installation'] = 'コンテキストのインストール<br />※通常はこのまま「次へ」をクリックしてください';
$_lang['context_manager_options'] = '<strong>管理画面コンテキスト(mgr)</strong> (管理ページ)';
$_lang['context_manager_path'] = '管理画面コンテキストのファイルパス';
$_lang['context_manager_url'] = '管理画面コンテキストのURL';
$_lang['context_override'] = 'このチェックボックスをオンにすることで、必要に応じてコンテキストのファイルパスを指定することができます。チェックをオフにしたまま続行すると、各コンテキストには以下のディレクトリが割り当てられます。';
$_lang['context_web_options'] = '<strong>ウェブコンテキスト(web)</strong> (ウェブサイト)';
$_lang['context_web_path'] = 'ウェブコンテキストのファイルパス';
$_lang['context_web_url'] = 'ウェブコンテキストのURL';
$_lang['continue'] = '続ける';
$_lang['dau_err_save'] = 'デフォルト管理ユーザーを登録できませんでした。';
$_lang['dau_saved'] = 'デフォルト管理ユーザーを登録しました。';
$_lang['db_check_db'] = 'データベースの確認 : ';
$_lang['db_connecting'] = 'データベースサーバへの接続 : ';
$_lang['db_connected'] = 'データベース接続を確認しました。';
$_lang['db_created'] = 'データベースは正しく作成されました。';
$_lang['db_err_connect'] = 'データベースに接続できませんでした。';
$_lang['db_err_connect_upgrade'] = 'アップデートを試みましたが、データベースに接続できません。設定をもう一度確認してください。';
$_lang['db_err_connect_server'] = 'データベースサーバに接続できません。設定をもう一度確認してください。';
$_lang['db_err_create'] = 'データベースの作成中にエラーが発生しました。';
$_lang['db_err_create_database'] = 'データベースを作成できませんでした。手動でデータベースを作成後、再度試してください。';
$_lang['db_err_show_charsets'] = 'MySQLサーバーから利用可能なキャラクターセットが取得できませんした。';
$_lang['db_err_show_collations'] = 'MySQLサーバから利用可能な照合順序を得ることができませんでした。';
$_lang['db_success'] = '問題ありません';
$_lang['db_test_coll_msg'] = 'ここをクリックしてデータベースの選択を確認してください。<br />指定のデータベースが存在しない場合は新規作成を試みます。';
$_lang['db_test_conn_msg'] = 'ここをクリックしてデータベース接続をテストしてください';
$_lang['default_admin_user'] = 'デフォルト管理ユーザー';
$_lang['delete_setup_dir'] = 'セットアップディレクトリを削除する';
$_lang['dir'] = 'ltr:左から右';
$_lang['email_err_ns'] = 'メールアドレスが正しくありません';
$_lang['err_occ'] = 'エラーが発生しました。';
$_lang['err_update_table'] = 'class [[+class]] のテーブル更新時にエラーが発生しました。';
$_lang['errors_occurred'] = 'コアのインストール中にエラーが発生しました。インストール状態を確認し、問題を解消してから再開してください。';
$_lang['failed'] = '要変更';
$_lang['fatal_error'] = '致命的なエラー: インストールを続行できません。';
$_lang['home'] = 'Home';
$_lang['img_banner'] = 'assets/images/img_banner.gif';
$_lang['img_box'] = 'assets/images/img_box.png';
$_lang['img_splash'] = 'assets/images/img_splash.gif';
$_lang['install'] = 'インストール';
$_lang['install_packages'] = 'パッケージのインストール';
$_lang['install_packages_desc'] = 'アドオンパッケージを追加インストールすることができます。必要なパッケージをインストールした後、完了ボタンを押してください。';
$_lang['install_packages_options'] = 'パッケージのインストールオプション';
$_lang['install_success'] = 'MODXコアは正常にインストールされました。「次へ」ボタンを押して、インストール作業を完了させてください。';
$_lang['install_summary'] = 'インストールの概要';
$_lang['install_update'] = 'インストール/アップデート';
$_lang['installation_finished'] = 'インストール完了：[[+time]]';
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
$_lang['license_agree'] = 'はい。このライセンス条文に合意しました。';
$_lang['license_agreement'] = 'ライセンスについて';
$_lang['license_agreement_error'] = 'あなたはインストールを続ける前にライセンス文書を読み、その内容に合意する必要があります。';
$_lang['login'] = 'ログイン';
$_lang['modx_class_err_nf'] = 'MODXクラスの読み込みに失敗しました。';
$_lang['modx_configuration_file'] = 'MODX設定ファイル';
$_lang['modx_err_instantiate'] = 'MODXオブジェクトを生成できません。';
$_lang['modx_err_instantiate_mgr'] = '管理画面コンテキストの初期化に失敗しました。';
$_lang['modx_footer1'] = '&copy; 2005-2012 the <a href="http://modx.com/" onclick="window.open(this.href); return false;" onkeypress="window.open(this.href); return false;">MODX</a> Content Management Framework (CMF) project. All rights reserved. MODX is licensed under the GNU GPL.';
$_lang['modx_footer2'] = 'MODX is free software.  We encourage you to be creative and make use of MODX in any way you see fit. Just make sure that if you do make changes and decide to redistribute your modified MODX, that you keep the source code free!';
$_lang['modx_install'] = 'MODX Revolutionのインストール';
$_lang['modx_install_complete'] = 'MODXのインストールが完了しました';
$_lang['modx_object_err'] = 'MODXオブジェクトをロードできませんでした。';
$_lang['next'] = '次へ';
$_lang['none'] = 'なし';
$_lang['ok'] = '問題なし';
$_lang['options_core_inplace'] = '必要なファイルは配置済みです';
$_lang['options_core_inplace_note'] = 'MODX公式サイト(本家または日本公式)で配布されている通常版(traditionalパッケージ)の場合は、必要なファイルが同梱・配置されています。そのため、このオプションはオンになっています。<br />このオプションをオフにした場合は、必要なファイルを公式サイトから動的に取得・配置します。この場合、coreディレクトリ・setupディレクトリのみが必要です。<br />※公式サイトからのファイル取得は時間がかかります。共用サーバではPHPの処理時間制限が短めに設定されていることが多く、処理が中断・失敗する可能性があります。特に理由がなければ<strong>このオプションはオンのままインストールを進めることをおすすめします。</strong>';
$_lang['options_core_unpacked'] = 'コアファイルは配置済みです';
$_lang['options_core_unpacked_note'] = 'MODX公式サイト(本家または日本公式)で配布されている通常版(traditionalパッケージ)の場合は、コアパッケージ (/core/packages/ディレクトリ内のcore.transport.zip) がすでに展開・配置されています。そのため、このオプションはオンになっています。このオプションをオフにした場合は、コアパッケージを展開しファイルを上書きします。<br />※パッケージ展開処理は時間がかかります。共用サーバではPHPの処理時間制限が短めに設定されていることが多く、処理が中断・失敗する可能性があります。特に理由がなければ<strong>このオプションはオンのままインストールを進めることをおすすめします。</strong>';
$_lang['options_install_new_copy'] = 'MODX Revolutionを新規インストールします - ';
$_lang['options_install_new_note'] = '<br />すでにMODX Revolutionがインストールされている場合、このオプションを選ぶとデータは上書きされます。';
$_lang['options_important_upgrade'] = 'Important Upgrade Note';
$_lang['options_important_upgrade_note'] = 'アップデートを実行する前に、全ての管理画面ユーザーがログアウトしていることを確認してください。もしアップデート後に不具合が発生した場合、全ての管理画面からログアウトし、ブラウザキャッシュをクリアしてもう一度ログインしてください。';
$_lang['options_new_file_permissions'] = '新規ファイルのパーミッション';
$_lang['options_new_file_permissions_note'] = 'MODXのシステムを通じてファイルを新規作成する時のパーミッションをここで指定できます。(例：0664・0666など)';
$_lang['options_new_folder_permissions'] = '新規ディレクトリのパーミッション';
$_lang['options_new_folder_permissions_note'] = 'MODXのシステムを通じてディレクトリを新規作成する時のパーミッションをここで指定できます。(例：0775・0777など)';
$_lang['options_new_installation'] = '新規インストール';
$_lang['options_title'] = 'インストールオプション';
$_lang['options_upgrade_advanced'] = 'アドバンスアップデート<br />(データベース接続設定を更新します)';
$_lang['options_upgrade_advanced_note'] = '接続設定が異なるデータベースに変更した場合は、このオプションを選択してください。 <strong>新規インストールと同様、データベース名・ユーザ名・パスワード・文字セットの照合順序の情報などが必要になります。</strong>';
$_lang['options_upgrade_existing'] = '既存サイトのアップデート';
$_lang['options_upgrade_existing_note'] = 'システムをアップデートします(ファイル・データベース構成・設定情報など)。';
$_lang['package_execute_err_retrieve'] = 'パッケージ [[+path]]packages/core.transport.zip が展開できないため、インストールに失敗しました。[[+path]]packages/core.transport.zip が存在し書き込み可能であること、また [[+path]]packages/ ディレクトリが書き込み可能であることを確認してください。';
$_lang['package_err_install'] = '[[+package]] のインストールに失敗しました。';
$_lang['package_err_nf'] = '[[+package]] の取得に失敗しました。';
$_lang['package_installed'] = '[[+package]] は正しくインストールされました。';
$_lang['password_err_invchars'] = '/, \\, &apos;, &quot;, (, ) or {} などの、パスワードに使用できない文字が含まれています。';
$_lang['password_err_nomatch'] = 'パスワードが一致しません。';
$_lang['password_err_ns'] = 'パスワードを入力してください。';
$_lang['password_err_short'] = 'パスワードは6文字以上である必要があります。';
$_lang['please_select_login'] = 'ログインボタンをクリックすると管理画面にアクセスできます。';
$_lang['preinstall_failure'] = 'インストール環境テストで問題が発見されました。問題を解消してから再試行してください。';
$_lang['preinstall_success'] = 'インストール環境テストをクリアしました。インストールボタンを押して続行してください。';
$_lang['refresh'] = 'リフレッシュ';
$_lang['request_handler_err_nf'] = '[[+path]] へのリクエストを処理できませんでした。必要なファイルが全てアップロードされているか確認してください。';
$_lang['restarted_msg'] = 'インストール処理が進まないまま15分が経過しました。トラブルを防ぐため、インストーラはセットアップのプロセスを初期化しました。申し訳ありませんが、最初からセットアップをやり直してください。';
$_lang['retry'] = '再試行';
$_lang['security_notice'] = 'セキュリティー情報';
$_lang['select'] = '選択';
$_lang['settings_handler_err_nf'] = 'modInstallSettingsクラスが [[+path]] 内に見つかりません。全てのファイルがアップロードされているか、確認して下さい。';
$_lang['setup_err_remove'] = 'セットアップディレクトリの削除中にエラーが発生しました。';
$_lang['setup_err_assets'] = '[[+path]] に assets/ ディレクトリがありません。<br />このディレクトリは拡張機能の追加と管理に必要です。';
$_lang['setup_err_assets_comp'] = '[[+path]] に assets/components/ ディレクトリがありません。<br />このディレクトリは拡張機能の追加と管理に必要です。';
$_lang['setup_err_core_comp'] = '[[+path]] に core/components/ ディレクトリがありません。<br />このディレクトリは拡張機能の追加と管理に必要です。';
$_lang['skip_to_bottom'] = '下までスクロール';
$_lang['success'] = '成功';
$_lang['table_created'] = '[[+class]] クラスに対応したテーブルは正しく作成されました。';
$_lang['table_err_create'] = '[[+class]] クラスに対応したテーブルの作成中にエラーが発生しました。';
$_lang['table_updated'] = '[[+class]] クラスに対応したテーブルのアップグレードに成功しました。';
$_lang['test_class_nf'] = '[[+path]] のインストールテストクラスが発見できませんでした。<br />必要なファイルが全てアップロードされているか確認してください。';
$_lang['test_version_class_nf'] = '[[+path]] にインストールのテストVersionerクラスが見つかりませんでした。<br />必要なファイルが全てアップロードされているか確認してください。';
$_lang['thank_installing'] = 'おつかれさまでした！<br />新しいタイプのCMS「MODX Revolution」を<br />お楽しみください！ - ';
$_lang['transport_class_err_load'] = 'transportクラスの読み込みでエラーが発生しました。';
$_lang['toggle'] = '表示/非表示の切り替え';
$_lang['toggle_success'] = '完了メッセージの表示を切り替える';
$_lang['toggle_warnings'] = '警告メッセージの表示を切り替える';
$_lang['username_err_invchars'] = 'ユーザー名に使用できない文字が含まれています。/,\\,&apos;,&quotおよび{}はユーザー名に使用できません。';
$_lang['username_err_ns'] = 'ユーザー名が不正です。';
$_lang['version'] = 'version';
$_lang['warning'] = 'Warning';
$_lang['welcome'] = 'MODX Revolutionへようこそ！';
$_lang['welcome_message'] = '<p>期待の新世代フレームワーク「MODX Revolution」を、このサーバーにインストールします。<br />パーミッション変更・オプション選択など、必要に応じてナビゲーションいたします。</p>
	<p>[次へ] をクリックしてください。</p>
';
$_lang['workspace_err_nf'] = '現在の作業ディレクトリが発見できませんでした。';
$_lang['workspace_err_path'] = '作業ディレクトリの設定中にエラーが発生しました。';
$_lang['workspace_path_updated'] = '作業ディレクトリ情報は正しく更新されました。';
$_lang['versioner_err_nf'] = '[[+path]] にインストールのVersionerクラスが見つかりませんでした。<br />必要なファイルが全てアップロードされているか確認してください。';
$_lang['xpdo_err_ins'] = 'xPDOの初期化中にエラーが発生しました。';
$_lang['xpdo_err_nf'] = '[[+path]] にxPDOのファイルがありません。全てのファイルが正しくアップロードされているか確認してください。';
$_lang['preload_err_cache'] = '[[+path]]cache ディレクトリーをPHPプロセスから書き込める状態にしてください。';
$_lang['preload_err_core_path'] = 'setup/includes/config.core.php を開き、MODX_CORE_PATHの値を修正してください。このパラメータはcoreディレクトリーの場所を指している必要があります。';
$_lang['preload_err_mysql'] = 'MySQL拡張モジュールを発見できませんでした。';
$_lang['preload_err_pdo'] = 'PDO拡張モジュールを発見できませんでした。';
$_lang['preload_err_pdo_mysql'] = 'PDO拡張モジュールのMySQLドライバーを発見できませんでした。';
$_lang['test_config_file'] = '<span class="mono">[[+file]]</span>が書き込み可能になっているか確認します。:';
$_lang['test_config_file_nw'] = 'Linux/Unix環境に新しくインストールする場合、<span class="mono">core/config/</span>ディレクトリーに<span class="mono">[[+file]].inc.php</span>という名前の空ファイルを作成し、PHPからの書き込みを許可してください。';
$_lang['test_db_check'] = 'データベース設定の作成: ';
$_lang['test_db_check_conn'] = '設定を確認して再試行してください。';
$_lang['test_db_failed'] = 'データベースに接続できませんでした。';
$_lang['test_db_setup_create'] = 'データベースを作成しています。';
$_lang['test_dependencies'] = 'zlibの確認: ';
$_lang['test_dependencies_fail_zlib'] = 'お使いのPHP実行環境ではzlibが有効になっていません。MODXはzlibを必要とします。zlibを有効にしてから再試行してください。';
$_lang['test_directory_exists'] = '<span class="mono">[[+dir]]</span>ディレクトリーが存在するか確認します: ';
$_lang['test_directory_writable'] = '<span class="mono">[[+dir]]</span>ディレクトリーが書き込み可能か確認します: ';
$_lang['test_memory_limit'] = 'メモリーサイズ(24M以上必要)の確認: ';
$_lang['test_memory_limit_fail'] = 'PHPのmemory_limitが推奨値(24M以上)を下回っています。MODXはmemory_limitの自動設定に失敗しました。インストールを続ける前にphp.iniを編集してください。';
$_lang['test_php_version_fail'] = 'お使いのPHPのバージョンは[[+version]]です。MODX Revolutionの動作にはPHP 5.1.1以降のバージョンが必要です。';
$_lang['test_php_version_sn'] = 'お使いのPHPのバージョンは[[+version]]です。現在MODXはこのバージョンのPHPをサポートしていますが、セキュリティー上の観点からバージョンアップを強くお勧めします。少なくともPHP5.1.1以上にアップデートし、必要なセキュリティパッチを当てるようにしてください。';
$_lang['test_php_version_start'] = 'PHPバージョンの確認: ';
$_lang['test_sessions_start'] = 'セッション設定の確認: ';
$_lang['test_table_prefix'] = 'テーブルプレフィックス `[[+prefix]]` を確認: ';
$_lang['test_table_prefix_inuse'] = 'このテーブルプレフィックスはすでに使われています。';
$_lang['test_table_prefix_inuse_desc'] = '指定されたテーブルプレフィックスがすでに使用されているため、インストールを続行できませんでした。別のプレフィックスを指定し、テストを再試行してください。';
$_lang['test_table_prefix_nf'] = '指定されたプレフィックスのテーブルは存在しません。';
$_lang['test_table_prefix_nf_desc'] = 'アップデート対象のテーブルを発見できなかったため、インストールを続行できませんでした。既存のテーブルプレフィックスを指定できているか確認し、テストを再試行してください。';
$_lang['test_zip_memory_limit'] = 'zip拡張モジュールのメモリーサイズ(24MB以上必要)の確認: ';
$_lang['test_zip_memory_limit_fail'] = 'PHPのmemory_limitが下限値(24M以上)を下回っています。MODXはmemory_limitの自動設定に失敗しました。インストールを続ける前にphp.iniを編集してください。';
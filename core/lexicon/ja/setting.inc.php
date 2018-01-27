<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'エリア';
$_lang['area_authentication'] = '認証とセキュリティ';
$_lang['area_caching'] = 'キャッシュ';
$_lang['area_core'] = 'Core Code';
$_lang['area_editor'] = 'リッチテキストエディタ';
$_lang['area_file'] = 'ファイルシステム';
$_lang['area_filter'] = 'エリアで絞り込み';
$_lang['area_furls'] = 'フレンドリーURL';
$_lang['area_gateway'] = 'ゲートウェイ（コントローラ）';
$_lang['area_language'] = '言語とエンコード';
$_lang['area_mail'] = 'メール';
$_lang['area_manager'] = '管理画面の設定';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'プロキシ';
$_lang['area_session'] = 'セッションとクッキー';
$_lang['area_lexicon_string'] = 'レキシコン領域';
$_lang['area_lexicon_string_msg'] = 'Enter the key of the lexicon entry for the area here. If there is no lexicon entry, it will just display the area key.<br />Core Areas: authentication, caching, file, furls, gateway, language, manager, session, site, system';
$_lang['area_site'] = 'サイト';
$_lang['area_system'] = 'システムとサーバ';
$_lang['areas'] = 'エリア';
$_lang['charset'] = '文字セット';
$_lang['country'] = '国';
$_lang['description_desc'] = '設定の簡単な説明です。レキシコンのエントリーキーを指定することもできます。';
$_lang['key_desc'] = '設定のキーです。コンテンツからは[[++key]]プレースホルダでキーを指定して、値を読み出すことができるようになります。';
$_lang['name_desc'] = '設定の名称。レキシコンのエントリーキーを指定することもできます。';
$_lang['namespace'] = 'ネームスペース';
$_lang['namespace_desc'] = 'The Namespace that this Setting is associated with. The default Lexicon Topic will be loaded for this Namespace when grabbing Settings.';
$_lang['namespace_filter'] = 'ネームスペースで絞り込み';
$_lang['search_by_key'] = 'キーで検索';
$_lang['setting_create'] = 'キーを新規作成';
$_lang['setting_err'] = 'このフィールドの存在を確認してください：';
$_lang['setting_err_ae'] = 'そのキーの設定はすでに存在します。ほかのキー名を指定してください。';
$_lang['setting_err_nf'] = '設定が見つかりませんでした。';
$_lang['setting_err_ns'] = '設定が指定されていません。';
$_lang['setting_err_remove'] = '設定の削除中にエラーが発生しました。';
$_lang['setting_err_save'] = '設定の保存中にエラーが発生しました。';
$_lang['setting_err_startint'] = '設定が整数から始まっていません。';
$_lang['setting_err_invalid_document'] = 'ID %のドキュメントが見つかりませんでした。存在するドキュメントを設定してください。';
$_lang['setting_remove'] = '設定を削除';
$_lang['setting_remove_confirm'] = '設定を削除しますか？（MODXのインストール設定が消失します）';
$_lang['setting_update'] = '設定を編集';
$_lang['settings_after_install'] = '<p><strong style="color:red;">新規インストール直後のため、各種設定を確認し保存する必要があります。設定を確認した後、データベースを更新するために「保存」ボタンをクリックしてください。</strong></p><p>※この設定はいつでも自由に変更できます。</p>';
$_lang['settings_desc'] = 'システム全体の設定（管理画面の設定も含む）を管理します。値をダブルクリックして直接編集するか、右クリックメニューから詳細設定ダイアログを開いて編集します。「＋」マークをクリックすると項目の説明を表示します。<br />複数サイトを管理する場合は「コンテキスト」でサイトごとのシステム設定を定義してください。';
$_lang['settings_furls'] = 'フレンドリーURL';
$_lang['settings_misc'] = 'ファイルマネージャ';
$_lang['settings_site'] = 'サイト';
$_lang['settings_ui'] = 'インターフェースとその他の機能';
$_lang['settings_users'] = 'ユーザー';
$_lang['system_settings'] = 'システム設定';
$_lang['usergroup'] = 'ユーザーグループ';

// user settings
$_lang['setting_access_category_enabled'] = 'カテゴリーへのアクセス権をチェックする';
$_lang['setting_access_category_enabled_desc'] = 'カテゴリーに対するアクセス権チェックの有無を指定します（コンテキストごと）。<br /><strong>注：この設定を「いいえ」にした場合、全てのカテゴリーへのアクセス権設定は無視されます。</strong>';

$_lang['setting_access_context_enabled'] = 'コンテキストアクセス権チェック';
$_lang['setting_access_context_enabled_desc'] = '全コンテキストへのアクセス権チェックの有無を指定します。<br /><strong>注：このオプションが無効の場合、全てのコンテキストアクセス権設定は無視されます。このシステム全体または"mgr"コンテキストにアクセスさせたくないとき以外はこの設定を無効にしないでください。</strong>';

$_lang['setting_access_resource_group_enabled'] = 'リソースグループのアクセス権をチェック';
$_lang['setting_access_resource_group_enabled_desc'] = '（コンテキストごとの）リソースグループのアクセス権チェックの有無を指定します。<br /><strong>注：このオプションが無効の場合、全てのリソースグループアクセス権設定は無視されます。</strong>';

$_lang['setting_allow_mgr_access'] = '管理画面へのアクセス';
$_lang['setting_allow_mgr_access_desc'] = 'ユーザーの管理画面アクセスの有無を切り替えるには、この設定を使用します。<br /><strong>注：この設定が「いいえ」のユーザーは、管理画面の認証画面、またはサイトのスタートページへリダイレクトされます。</strong>';

$_lang['setting_failed_login'] = 'ログイン失敗許可数';
$_lang['setting_failed_login_desc'] = 'ここにログインの失敗を許可する回数を入力することができます。ログイン失敗回数がこの回数を超えた過ぎたユーザーは拒否されます。';

$_lang['setting_login_allowed_days'] = '有効な日';
$_lang['setting_login_allowed_days_desc'] = 'このユーザがログインできる日を選択します。';

$_lang['setting_login_allowed_ip'] = '有効なIPアドレス';
$_lang['setting_login_allowed_ip_desc'] = 'IPアドレスを入力すると、このユーザはそのIPアドレスからのログインのみ有効になります。<strong>ノート: 複数のIPアドレスを指定するにはカンマ（ , ）で区切ります</strong>';

$_lang['setting_login_homepage'] = 'ログインのホームページ';
$_lang['setting_login_homepage_desc'] = 'ログイン後に遷移させたいドキュメントのIDを入力します。<strong>ノート: 公開されていて、このユーザ－がアクセスできるドキュメントのIDを入力してください。</strong>';

// system settings
$_lang['setting_access_policies_version'] = 'アクセスポリシースキーマバージョン';
$_lang['setting_access_policies_version_desc'] = 'アクセスポリシーシステムのバージョン。変更しないでください。';

$_lang['setting_allow_forward_across_contexts'] = 'コンテキスト間での転送を許可';
$_lang['setting_allow_forward_across_contexts_desc'] = '有効の場合、シムリンクやmodX::sendForward（） APIの呼び出しは、異なるコンテキスト内のリソースへ要求を転送可能になります。';

$_lang['setting_allow_manager_login_forgot_password'] = '管理画面ログイン時にパスワード忘れ対応機能を許可';
$_lang['setting_allow_manager_login_forgot_password_desc'] = '「いいえ」に指定すると、管理画面へのログイン時に、パスワード忘れ対応機能を無効にします。';

$_lang['setting_allow_tags_in_post'] = 'POSTアクションでタグの受け渡しを許可';
$_lang['setting_allow_tags_in_post_desc'] = '無効とすると、すべてのPOSTリクエストで、MODXタグ、数値実体参照、またはHTMLのscriptタグが取り除かれます。デフォルトでこの設定が有効な管理画面用コンテキスト`mgr`以外では、無効とすることを推奨します。';

$_lang['setting_allow_tv_eval'] = 'Disable eval in TV binding';
$_lang['setting_allow_tv_eval_desc'] = 'Select this option to enable or disable eval in TV binding. If this option is set to no, the code/value will just be handled as regular text.';

$_lang['setting_anonymous_sessions'] = 'Anonymous Sessions';
$_lang['setting_anonymous_sessions_desc'] = 'If disabled, only authenticated users will have access to a PHP session. This can reduce overhead for anonymous users and the load they impose on a MODX site if they do not need access to a unique session. If session_enabled is false, this setting has no effect as sessions would never be available.';

$_lang['setting_archive_with'] = '強制的にPCLZipを使用';
$_lang['setting_archive_with_desc'] = 'この設定が有効の場合、zipアーカイブに対して、ZipArchiveの代わりにPCLZipを使用します。<br />この設定は、ZipArchiveのextractToエラー時、またはパッケージの展開に問題がある場合に有効にしてください。';

$_lang['setting_auto_menuindex'] = 'メニューインデックスを自動インクリメント';
$_lang['setting_auto_menuindex_desc'] = '「はい」を選択すると、デフォルトでメニューのインデックスが自動的に1ずつ増加します。';

$_lang['setting_auto_check_pkg_updates'] = 'パッケージのアップデートを自動で確認する';
$_lang['setting_auto_check_pkg_updates_desc'] = '「はい」を選択すると、パッケージマネージャからパッケージのアップデートを自動的に確認します。ただし、一覧の読み込みが遅くなるかもしれません。';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'パッケージ自動アップデートチェックのキャッシュ有効時間';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'パッケージの更新チェック時に、結果をキャッシュする有効時間を指定します（分数）。';

$_lang['setting_allow_multiple_emails'] = '登録メールアドレスの重複を許可';
$_lang['setting_allow_multiple_emails_desc'] = '有効にすると、同じメールアドレスで複数のユーザを登録することができます。';

$_lang['setting_automatic_alias'] = 'エイリアスの自動付加';
$_lang['setting_automatic_alias_desc'] = '「はい」を選択すると、保存時にシステムがリソースのページタイトルからエイリアスを生成します。';

$_lang['setting_base_help_url'] = 'ヘルプの基準URL';
$_lang['setting_base_help_url_desc'] = '管理画面右上に表示される、ヘルプへのリンクの基準となるURL。';

$_lang['setting_blocked_minutes'] = 'ブロックする時間';
$_lang['setting_blocked_minutes_desc'] = 'ユーザーがログイン失敗許可数を超過した場合にブロックを行う時間を数値で指定できます。<br />この値は数値のみを入力してください（カンマやスペース等は使えません）。';

$_lang['setting_cache_action_map'] = 'アクションマップキャッシュを有効';
$_lang['setting_cache_action_map_desc'] = '「はい」を選択すると、アクション（またはコントローラーマップ）をキャッシュして、管理画面のページ読み込み時間を減らします。';

$_lang['setting_cache_alias_map'] = 'コンテキストのエイリアスマップキャッシュを有効';
$_lang['setting_cache_alias_map_desc'] = '有効にすると、すべてのリソースのURIは、コンテキストにキャッシュされます。小規模なサイトで有効にして、パフォーマンスを向上させるために大規模なサイトでは無効にします。';

$_lang['setting_use_context_resource_table'] = 'Use the context resource table';
$_lang['setting_use_context_resource_table_desc'] = 'When enabled, context refreshes use the context_resource table. This enables you to programmatically have one resource in multiple contexts. If you do not use those multiple resource contexts via the API, you can set this to false. On large sites you will get a potential performance boost in the manager then.';

$_lang['setting_cache_context_settings'] = 'コンテキスト設定キャッシュを有効';
$_lang['setting_cache_context_settings_desc'] = '「はい」を選択すると、コンテキストの設定をキャッシュして、読み込み時間を減らします。';

$_lang['setting_cache_db'] = 'データベースキャッシュを有効';
$_lang['setting_cache_db_desc'] = '「はい」を選択すると、SQLクエリの結果セットをキャッシュし、データベースの負荷を大幅に減らします。';

$_lang['setting_cache_db_expires'] = 'データベースキャッシュの有効秒数';
$_lang['setting_cache_db_expires_desc'] = 'データベース結果セットのキャッシュの有効時間を指定します（秒数）。';

$_lang['setting_cache_db_session'] = 'DBセッションキャッシュを有効にする';
$_lang['setting_cache_db_session_desc'] = 'この設定が有効且つ、「データベースキャッシュを有効」で「はい」が選択されている場合、DBセッションは結果セットキャッシュにキャッシュされます。';

$_lang['setting_cache_db_session_lifetime'] = 'DBセッションキャッシュの有効秒数';
$_lang['setting_cache_db_session_lifetime_desc'] = 'データベースセッションのキャッシュの有効時間を指定します（秒数）。';

$_lang['setting_cache_default'] = 'デフォルトでキャッシュ有効';
$_lang['setting_cache_default_desc'] = '「はい」を選択すると、すべての新規リソースのデフォルトでキャッシュを有効にします。';
$_lang['setting_cache_default_err'] = 'リソースがデフォルトでキャッシュされるか指定してください。';

$_lang['setting_cache_disabled'] = 'グローバルキャッシュを無効にする';
$_lang['setting_cache_disabled_desc'] = '「はい」を選択すると、全てのキャッシュ機能を無効にします。<br />キャッシュを無効にすることは推奨しません。';
$_lang['setting_cache_disabled_err'] = 'キャッシュを有効にするか指定してください。';

$_lang['setting_cache_expires'] = 'デフォルトキャッシュの有効秒数';
$_lang['setting_cache_expires_desc'] = 'デフォルトキャッシュの有効時間を指定します（秒数）。';

$_lang['setting_cache_format'] = 'キャッシュのフォーマット';
$_lang['setting_cache_format_desc'] = '0 = PHP、1 = JSON、2 = シリアライズ。いずれかを数値で指定します。';

$_lang['setting_cache_handler'] = 'キャッシュのハンドラクラス';
$_lang['setting_cache_handler_desc'] = 'キャッシュに使用するハンドラクラス名を指定します。';

$_lang['setting_cache_lang_js'] = 'レキシコンJavaScript文字列のキャッシュ';
$_lang['setting_cache_lang_js_desc'] = '有効の場合、管理画面インターフェース用のJavaScriptに読み込まれたレキシコン文字列を、キャッシュするサーバーヘッダを使用します。';

$_lang['setting_cache_lexicon_topics'] = 'レキシコンのキャッシュ';
$_lang['setting_cache_lexicon_topics_desc'] = '有効にすると、全てのレキシコントピックをキャッシュし、多言語対応のためのロード時間を大幅に削減します。<br />この設定に「はい」を選択することを、強くおすすめします。';

$_lang['setting_cache_noncore_lexicon_topics'] = 'コア以外のレキシコンキャッシュを有効';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = '無効にすると、コア以外のレキシコントピックはキャッシュされません。<br />独自のエクストラを開発する場合には、無効にしておくと便利です。';

$_lang['setting_cache_resource'] = '部分的なリソースキャッシュを有効';
$_lang['setting_cache_resource_desc'] = '有効の場合、リソースのキャッシングはリソースによって設定されます。<br />無効にした場合、グローバルに無効になります。';

$_lang['setting_cache_resource_expires'] = '部分的なリソースキャッシュの有効秒数';
$_lang['setting_cache_resource_expires_desc'] = '部分的なリソースキャッシュの有効時間を指定します（秒数）。';

$_lang['setting_cache_scripts'] = 'スクリプトキャッシュを有効';
$_lang['setting_cache_scripts_desc'] = '「はい」を選択すると、ロード時間短縮のために全てのスクリプト（スニペットとプラグイン）がキャッシュされます。<br />この設定は有効にしておくことをおすすめします。';

$_lang['setting_cache_system_settings'] = 'システム設定キャッシュを有効';
$_lang['setting_cache_system_settings_desc'] = 'はい」を選択すると、ロード時間短縮のためにシステム設定がキャッシュされます。<br />この設定は有効にしておくことをおすすめします。';

$_lang['setting_clear_cache_refresh_trees'] = 'サイトキャッシュクリア時にツリーをリフレッシュ';
$_lang['setting_clear_cache_refresh_trees_desc'] = '有効にすると、サイトキャッシュのクリア後に、リソースツリーを再表示します。';

$_lang['setting_compress_css'] = '圧縮したCSSを使用';
$_lang['setting_compress_css_desc'] = 'このオプションが有効の場合、管理画面用のCSSは圧縮版を使用します。これにより、管理画面での負荷の軽減と、実効速度の向上が得られます。<br />このオプションは貴方がコアに変更を加えている場合のみ、無効にします。';

$_lang['setting_compress_js'] = '圧縮したJavaScriptを使用';
$_lang['setting_compress_js_desc'] = 'このオプションが有効の場合、管理画面用のJavaScriptライブラリでその圧縮版を使用します。これにより、管理画面での負荷の軽減と、実効速度の向上が得られます。<br />このオプションは、貴方がコアに変更を加えている場合のみ、無効にします。';

$_lang['setting_compress_js_groups'] = 'JavaScript圧縮時にグループを使用';
$_lang['setting_compress_js_groups_desc'] = 'Group the core MODX manager JavaScript using minify\'s groupsConfig. suhosin その他の制限要因がある場合に、有効にします。';

$_lang['setting_compress_js_max_files'] = 'JavaScriptファイル圧縮時最大ファイル数の閾値';
$_lang['setting_compress_js_max_files_desc'] = 'compress_js設定が有効の場合、MODXはここに設定した数のJavaScriptファイルを一度に圧縮しようとします。<br />管理画面のGoogle Minifyで問題が発生した場合、小さな値を指定してください。';

$_lang['setting_concat_js'] = '連結したJavaScriptライブラリを使用';
$_lang['setting_concat_js_desc'] = '有効の場合、管理画面インターフェースでは、一般的なJavaScriptライブラリを連結して使用します。これにより、管理画面のロードと実行時間が短縮されます。<br />無効にするのは、コア要素の変更時のみにしてください。';

$_lang['setting_confirm_navigation'] = 'Confirm Navigation with unsaved changes';
$_lang['setting_confirm_navigation_desc'] = 'When this is enabled, the user will be prompted to confirm their intention if there are unsaved changes.';

$_lang['setting_container_suffix'] = 'コンテナの添え字';
$_lang['setting_container_suffix_desc'] = 'フレンドリーURLを使用する場合に、コンテナ属性のリソースに追加する添え字';

$_lang['setting_context_tree_sort'] = 'リソースツリーでコンテキストをソート';
$_lang['setting_context_tree_sort_desc'] = '有効の場合、管理画面左側に表示されるリソースツリー上で、コンテキストを英数字順でソートします。';
$_lang['setting_context_tree_sortby'] = 'リソースツリー上でのコンテキストのソートフィールド';
$_lang['setting_context_tree_sortby_desc'] = 'コンテキストのソートが有効の場合に、ソート条件となるフィールドを指定します。';
$_lang['setting_context_tree_sortdir'] = 'リソースツリー上でのコンテキストのソート方向';
$_lang['setting_context_tree_sortdir_desc'] = 'コンテキストのソートが有効の場合に、ソートする方向（ASC or DESC）を指定します。';

$_lang['setting_cultureKey'] = '言語';
$_lang['setting_cultureKey_desc'] = 'Webを含む、全ての非管理画面コンテキストの言語を指定します。';

$_lang['setting_date_timezone'] = 'デフォルトのタイムゾーン';
$_lang['setting_date_timezone_desc'] = '指定した場合、PHPのdate関数のデフォルトタイムゾーンを制御します。<br />指定がされず、PHPのiniからもタイムゾーンが取得できない場合、UTCと仮定されます。';

$_lang['setting_debug'] = 'デバッグ';
$_lang['setting_debug_desc'] = 'MODX及びPHPの error_reporting レベルを設定します。<br />\'\' = カレントの error_reporting を使用、\'0\' = false （error_reporting = 0）、\'1\' = true （error_reporting = -1）、または任意の有効な error_reporting 指定（整数）。';

$_lang['setting_default_content_type'] = 'デフォルトのコンテントタイプ';
$_lang['setting_default_content_type_desc'] = '新規リソース作成時にデフォルトで選択されるコンテントタイプを指定します。<br />ここで指定したコンテントタイプは、単にデフォルトで選択されるだけのものです。編集画面では異なるコンテントタイプを指定できます。';

$_lang['setting_default_duplicate_publish_option'] = 'リソース複製時の公開オプションのデフォルト';
$_lang['setting_default_duplicate_publish_option_desc'] = '複製したリソースの公開オプションのデフォルト状態を指定します。<br />全て非公開の場合は "unpublish"、全て公開の場合は "publish"と指定します。"preserve"とすると、複製元のリソースの公開状態を維持します。';

$_lang['setting_default_media_source'] = 'デフォルトのメディアソース';
$_lang['setting_default_media_source_desc'] = 'デフォルトでロードされるメディアソース。';

$_lang['setting_default_template'] = 'デフォルトのテンプレート';
$_lang['setting_default_template_desc'] = '新規リソース作成時にデフォルトで選択されるテンプレートを指定します。<br />この指定は単にデフォルトであり、編集時には異なるテンプレートを指定できます。';

$_lang['setting_default_per_page'] = 'デフォルトのページあたり表示数（管理画面）';
$_lang['setting_default_per_page_desc'] = '管理画面で一覧される各種要素の、ページあたり表示数のデフォルトを指定します。';

$_lang['setting_editor_css_path'] = 'リッチテキストエディタ用CSSファイルのパス';
$_lang['setting_editor_css_path_desc'] = 'リッチテキストエディタでの編集中に使用する、CSSファイルのパスを入力します。パスはサーバールートからの絶対パスでの入力をおすすめします。例：/assets/site/style.css<br />リッチテキストエディタにCSSを適用したくない場合には、この設定を空白にします。';

$_lang['setting_editor_css_selectors'] = 'CSSセレクタ（RTE用）';
$_lang['setting_editor_css_selectors_desc'] = 'リッチテキストエディタ用のCSSセレクタを、カンマ区切りで指定します。';

$_lang['setting_emailsender'] = '送信者メールアドレス';
$_lang['setting_emailsender_desc'] = ' ユーザー名とパスワード送信時の、送信元メールアドレスを指定してください。';
$_lang['setting_emailsender_err'] = '送信者メールアドレスを指定してください。';

$_lang['setting_emailsubject'] = 'メールのタイトル';
$_lang['setting_emailsubject_desc'] = 'サインアップメールのタイトルを指定してください。';
$_lang['setting_emailsubject_err'] = 'サインアップメールのタイトルを指定してください。';

$_lang['setting_enable_dragdrop'] = 'リソース・エレメントツリーでのドラッグ移動';
$_lang['setting_enable_dragdrop_desc'] = '「いいえ」を選択した場合、リソース・エレメントのツリーでは要素をドラッグして移動できなくなります。';

$_lang['setting_error_page'] = 'エラーページ';
$_lang['setting_error_page_desc'] = 'ドキュメントが存在しない場合のエラーページ（404 ページ）として表示する、ドキュメントのIDを入力してください。<br /><strong>注意: 既に存在するドキュメントで、かつ公開されている必要があります。</strong>';
$_lang['setting_error_page_err'] = 'エラーページに使用するドキュメントIDを指定してください。';

$_lang['setting_ext_debug'] = 'ExtJS debug';
$_lang['setting_ext_debug_desc'] = 'Whether or not to load ext-all-debug.js to help debug your ExtJS code.';

$_lang['setting_extension_packages'] = 'Extension Packages';
$_lang['setting_extension_packages_desc'] = 'MODXインスタンス生成時にロードするパッケージを、カンマ区切りのリストで指定。書式は "packagename:pathtomodel" となります。';

$_lang['setting_enable_gravatar'] = 'Enable Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'If enabled, Gravatar will be used as a profile image (if user do not have profile photo uploaded).';

$_lang['setting_failed_login_attempts'] = 'ログイン失敗許可数';
$_lang['setting_failed_login_attempts_desc'] = 'ユーザーが \'blocked\' 状態になるまでに、何回のログイン失敗を許容するか指定します。';

$_lang['setting_fe_editor_lang'] = 'エディタの言語';
$_lang['setting_fe_editor_lang_desc'] = 'フロントエンドで使用されるエディタの言語を選択します。';

$_lang['setting_feed_modx_news'] = 'MODXニュースフィードURL';
$_lang['setting_feed_modx_news_desc'] = '管理画面のMODXニュースパネルが表示するRSSフィードのURLを指定します。';

$_lang['setting_feed_modx_news_enabled'] = 'MODXニュースフィードを有効';
$_lang['setting_feed_modx_news_enabled_desc'] = '「いいえ」を選択すると管理画面のようこそ画面でニュースフィードが表示されません。';

$_lang['setting_feed_modx_security'] = 'MODXセキュリティーフィードのURL';
$_lang['setting_feed_modx_security_desc'] = '管理画面のセキュリティ通知パネルで表示される、MODXのセキュリティ情報のURLを指定します。';

$_lang['setting_feed_modx_security_enabled'] = 'MODXセキュリティフィードを有効';
$_lang['setting_feed_modx_security_enabled_desc'] = '「いいえ」を選択した場合、管理画面のダッシュボードでセキュリティ通知を表示しません。';

$_lang['setting_filemanager_path'] = '（非推奨）ファイルマネージャーの管理領域パス指定';
$_lang['setting_filemanager_path_desc'] = '非推奨です。代わりにメディアソースを使用してください。<br />IISでは多くの場合、document_rootプロパティが正しく設定されないため、この設定でファイルマネージャーの管理領域を補正できる可能性があります。<br />ファイルマネージャーで問題が発生している場合、この設定がMODXのインストールされているルートを指しているか確認してください。';

$_lang['setting_filemanager_path_relative'] = '（非推奨）ファイルマネージャーにIs File Manager Path Relative? （Deprecated）';
$_lang['setting_filemanager_path_relative_desc'] = '非推奨です。代わりにメディアソースを使用してください。<br />filemanager_path に相対パスを設定している場合、「はい」を指定してください。filemanager_pathがドキュメントルート外を指す場合、「いいえ」を指定してください。';

$_lang['setting_filemanager_url'] = '（非推奨）ファイルマネージャーURL';
$_lang['setting_filemanager_url_desc'] = '非推奨です。代わりにメディアソースを使用してください。<br />ファイルマネージャーにアクセスする明示的なURLを指定します（filemanager_pathをMODXのウェブルート外に指定した場合に役立ちます）。filemanager_pathの値がウェブアクセス可能なURLであることを確認してください。<br />この設定が空の場合、MODXが自動算出を試みます。';

$_lang['setting_filemanager_url_relative'] = '（非推奨）相対的なファイルマネージャーURL';
$_lang['setting_filemanager_url_relative_desc'] = '非推奨です。代わりにメディアソースを使用してください。<br />filemanager_urlに相対URLを指定している場合、「はい」を指定してください。filemanager_urlがメインのウェブルート外を指す場合、「いいえ」を指定してください。';

$_lang['setting_forgot_login_email'] = 'ログイン情報送信メール';
$_lang['setting_forgot_login_email_desc'] = 'ユーザーが自分のMODXアカウントまたはパスワードを忘れた場合に送信される、Eメールのテンプレート。';

$_lang['setting_form_customization_use_all_groups'] = 'Use All User Group Memberships for Form Customization';
$_lang['setting_form_customization_use_all_groups_desc'] = 'If set to true, FC will use *all* Sets for *all* User Groups a member is in when applying Form Customization Sets.<br />Otherwise, it will only use the Set belonging to the User\'s Primary Group. Note: setting this to Yes might cause bugs with conflicting FC Sets.';

$_lang['setting_forward_merge_excludes'] = 'sendForward Exclude Fields on Merge';
$_lang['setting_forward_merge_excludes_desc'] = 'A SymLink merges non-empty field values over the values in the target Resource; using this comma-delimited list of excludes prevents specified fields from being overridden by the SymLink.';

$_lang['setting_friendly_alias_lowercase_only'] = 'エイリアスを小文字で揃える';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'リソースのエイリアスに小文字のみを許可するかを決定します。';

$_lang['setting_friendly_alias_max_length'] = 'エイリアスの最大サイズ';
$_lang['setting_friendly_alias_max_length_desc'] = '0より大きな値を指定した場合、エイリアスの文字数をその値で制限します。0の場合は無制限となります。';

$_lang['setting_friendly_alias_realtime'] = 'FURL Alias Real-Time';
$_lang['setting_friendly_alias_realtime_desc'] = 'Determines whether a resource alias should be created on the fly when typing the pagetitle or if this should happen when the resource is saved (automatic_alias needs to be enabled for this to have an effect).';

$_lang['setting_friendly_alias_restrict_chars'] = 'エイリアスの文字の制限方法';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'エイリアスに使用する文字の制限方法を指定します。"pattern"の場合、正規表現によるパターンで制限を行います。<br />"legal"の場合、任意の有効なURL文字のみに制限されます。"alpha"の場合はアルファベットのみ、そして"alphanumeric"の場合はアルファベットと数字のみに制限されます。';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'エイリアスの文字の制限パターン';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'エイリアスの文字を制限する有効な正規表現パターンを指定します。';

$_lang['setting_friendly_alias_strip_element_tags'] = 'フレンドリーURLのエイリアスからエレメントタグを除去';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'リソースの要素名からエレメントタグを取り除くか指定します。';

$_lang['setting_friendly_alias_translit'] = 'エイリアスの翻字設定';
$_lang['setting_friendly_alias_translit_desc'] = '機種依存文字やヨーロッパ圏のウムラウト（アクセント記号付きの文字）などを「翻字」したい場合に当設定を用います。<br />The method of transliteration to use on an alias specified for a Resource. Empty or "none" is the default which skips transliteration. Other possible values are "iconv" （if available） or a named transliteration table provided by a custom transliteration service class.';

$_lang['setting_friendly_alias_translit_class'] = 'FURL Alias Transliteration Service Class';
$_lang['setting_friendly_alias_translit_class_desc'] = 'An optional service class to provide named transliteration services for FURL Alias generation/filtering.';

$_lang['setting_friendly_alias_translit_class_path'] = 'FURL Alias Transliteration Service Class Path';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'The model package location where the FURL Alias Transliteration Service Class will be loaded from.';

$_lang['setting_friendly_alias_trim_chars'] = 'エイリアスからトリムする文字';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'エイリアス文字列の末尾に付けたくない文字を指定します。<br />例えば、「-」で終わるURLを美しくないと思う場合、もしエイリアス自動生成などの結果末尾に「-」が付いた時は、ここで指定しておけば自動的に削除できます。<br />Characters to trim from the ends of a provided Resource alias.';

$_lang['setting_friendly_alias_word_delimiter'] = 'エイリアス自動生成時の区切り文字';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'フレンドリーURL用のエイリアスを自動生成する際に、単語を区切る文字を指定します。';

$_lang['setting_friendly_alias_word_delimiters'] = 'エイリアス自動生成時の区切り文字';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Characters which represent word delimiters when processing friendly URL alias slugs. These characters will be converted and consolidated to the preferred FURL alias word delimiter.';

$_lang['setting_friendly_urls'] = 'フレンドリーURLの利用';
$_lang['setting_friendly_urls_desc'] = '静的URI機能（フレンドリーURL）の設定を行います。この機能はMODXがApache上で動作している時のみ有効です。<br />この機能を動作させるために .htaccessファイルに簡単な編集を加える必要があります。詳細は同梱 .htaccessファイルの末尾あたりをご覧ください。';
$_lang['setting_friendly_urls_err'] = 'フレンドリーURLを使用するかしないか選んでください。';

$_lang['setting_friendly_urls_strict'] = '厳格なフレンドリーURLを適用';
$_lang['setting_friendly_urls_strict_desc'] = 'When friendly URLs are enabled, this option forces non-canonical requests that match a Resource to 301 redirect to the canonical URI for that Resource. WARNING: Do not enable if you use custom rewrite rules which do not match at least the beginning of the canonical URI. For example, a canonical URI of foo/ with custom rewrites for foo/bar.html would work, but attempts to rewrite bar/foo.html as foo/ would force a redirect to foo/ with this option enabled.';

$_lang['setting_global_duplicate_uri_check'] = 'すべてのコンテキスト間で重複URIをチェック';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Select \'Yes\' to make duplicate URI checks include all Contexts in the search. Otherwise, only the Context the Resource is being saved in is checked.';

$_lang['setting_hidemenu_default'] = 'リソースのデフォルト：メニューに表示しない';
$_lang['setting_hidemenu_default_desc'] = '「はい」を選択すると、新規リソースの「メニューに表示しない」設定がデフォルトで有効になります。';

$_lang['setting_inline_help'] = 'フィールドにインラインのヘルプを表示';
$_lang['setting_inline_help_desc'] = '「はい」の場合、フィールドの下に直接ヘルプが表示されます。「いいえ」の場合、全てのフィールドのヘルプはツールチップベースになります。';

$_lang['setting_link_tag_scheme'] = 'URL生成スキーム';
$_lang['setting_link_tag_scheme_desc'] = '[[~id]]タグからURLを生成する際のスキームを指定。利用可能なオプションは<a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\\modX::makeUrl（）">このページのmakeUrl（） の解説</a>を参照してください。';

$_lang['setting_locale'] = 'ロケール';
$_lang['setting_locale_desc'] = 'システムロケールを設定します。空白の場合、システムのデフォルトが使用されます。詳細については <a href="http://php.net/setlocale" target="_blank">the PHP documentation</a> を参照してください。';

$_lang['setting_lock_ttl'] = 'Lock Time-to-Live';
$_lang['setting_lock_ttl_desc'] = 'The number of seconds a lock on a Resource will remain for if the user is inactive.';

$_lang['setting_log_level'] = 'ロギングレベル';
$_lang['setting_log_level_desc'] = 'The default logging level; the lower the level, the fewer messages that are logged. Available options: 0 （FATAL）, 1 （ERROR）, 2 （WARN）, 3 （INFO）, and 4 （DEBUG）.';

$_lang['setting_log_target'] = 'ロギングターゲット';
$_lang['setting_log_target_desc'] = 'デフォルトのログ出力先を指定します。利用可能なオプションは \'FILE\', \'HTML\', \'ECHO\' で、指定しない場合、\'FILE\' がデフォルトとなります。';

$_lang['setting_mail_charset'] = 'メールの文字セット';
$_lang['setting_mail_charset_desc'] = 'メールでの（デフォルトの）文字セットを指定します。<br />例えば「UTF-8」など。';

$_lang['setting_mail_encoding'] = 'メールのエンコーディング';
$_lang['setting_mail_encoding_desc'] = 'メッセージのエンコーディングを設定します。<br />ここで指定できるのは"8bit", "7bit", "binary", "base64", と"quoted-printable"です。';

$_lang['setting_mail_use_smtp'] = 'SMTPを使用';
$_lang['setting_mail_use_smtp_desc'] = '有効に設定すると、mail関数でSMTPを使用するように試みます。';

$_lang['setting_mail_smtp_auth'] = 'SMTP認証';
$_lang['setting_mail_smtp_auth_desc'] = 'SMTP認証をセットします。mail_smtp_userとmail_smtp_passwordの設定を利用します。';

$_lang['setting_mail_smtp_helo'] = 'SMTPようこそメッセージ';
$_lang['setting_mail_smtp_helo_desc'] = 'SMTP HELO コマンドのメッセージを設定します（デフォルトはホスト名です）。';

$_lang['setting_mail_smtp_hosts'] = 'SMTPのホスト';
$_lang['setting_mail_smtp_hosts_desc'] = 'SMTPホストを指定します。複数のホストを指定する場合、セミコロンで区切ります。また、以下の書式を使ってホスト毎に異なるポートも指定できます。<br />[ホスト名：ポート番号]（例： "smtp1.example.com：25; smtp2.example.com"）<br />ホストは指定された順に接続が試みられます。';

$_lang['setting_mail_smtp_keepalive'] = 'SMTP キープアライブ';
$_lang['setting_mail_smtp_keepalive_desc'] = '※非推奨：メール送信ごとのSMTP再接続を防止します。';

$_lang['setting_mail_smtp_pass'] = 'SMTPのパスワード';
$_lang['setting_mail_smtp_pass_desc'] = 'SMTP認証で用いるパスワードを指定します。';

$_lang['setting_mail_smtp_port'] = 'SMTPのポート';
$_lang['setting_mail_smtp_port_desc'] = 'SMTPサーバーのデフォルトポート番号を指定します。';

$_lang['setting_mail_smtp_prefix'] = 'SMTP接続の接頭辞';
$_lang['setting_mail_smtp_prefix_desc'] = '接続の接頭辞を "", "ssl" または "tls" から指定します。';

$_lang['setting_mail_smtp_single_to'] = 'SMTP Single To';
$_lang['setting_mail_smtp_single_to_desc'] = 'Provides the ability to have the TO field process individual emails, instead of sending to entire TO addresses.';

$_lang['setting_mail_smtp_timeout'] = 'SMTPのタイムアウト';
$_lang['setting_mail_smtp_timeout_desc'] = 'SMTPサーバーのタイムアウトを秒で設定します（Win32サーバーでは動作しません）。';

$_lang['setting_mail_smtp_user'] = 'SMTPのユーザー';
$_lang['setting_mail_smtp_user_desc'] = 'SMTP認証時に使用するユーザーです。';

$_lang['setting_main_nav_parent'] = 'Main menu parent';
$_lang['setting_main_nav_parent_desc'] = 'The container used to pull all records for the main menu.';

$_lang['setting_manager_direction'] = 'テキストの方向';
$_lang['setting_manager_direction_desc'] = '管理画面でのテキストの流れる方向を指定します。「ltr」は、左から右。「rtl」は、右から左です。<br />主にアラビア語圏などで利用する設定です。';

$_lang['setting_manager_date_format'] = '管理画面の日付フォーマット';
$_lang['setting_manager_date_format_desc'] = '管理画面内で用いる日付の書式。PHPのdate関数のフォーマットを用いて指定します。';

$_lang['setting_manager_favicon_url'] = '管理画面のfavicon URL';
$_lang['setting_manager_favicon_url_desc'] = '設定したURLの画像が、管理画面のfaviconとして使用されます。値はmanagerディレクトリに対する相対URLか、絶対URLで指定する必要があります。';

$_lang['setting_manager_js_cache_file_locking'] = '管理画面用JS/CSS キャッシュファイルのロックを有効化';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'キャッシュファイルをロックします。ファイルシステムにNFSを使用している場合、この設定は「いいえ」を指定してください。';
$_lang['setting_manager_js_cache_max_age'] = '管理画面のJS/CSS圧縮 キャッシュ有効期間';
$_lang['setting_manager_js_cache_max_age_desc'] = '管理画面のJavaScriptとCSSを圧縮したキャッシュの、ブラウザでの保持時間を秒で指定します。指定時間を経過すると、ブラウザは異なるGETリクエストを送信します。トラフィックを抑えるため、長い時間が指定されます。';
$_lang['setting_manager_js_document_root'] = '管理画面のJS/CSS圧縮 Document Root';
$_lang['setting_manager_js_document_root_desc'] = 'サーバーがDOCUMENT_ROOTサーバー変数を提供しない場合、管理画面のJS/CSS圧縮に必要な指定を行います。<br />必ず影響をよく理解した上で変更してください。';
$_lang['setting_manager_js_zlib_output_compression'] = '管理画面のJS/CSS圧縮 zlibの使用';
$_lang['setting_manager_js_zlib_output_compression_desc'] = '管理画面でのJS/CSS圧縮に、zlibの出力を使用するかを指定します。<br />PHPの設定で zlib.output_compression を 1 に確実に設定できない限り、この設定を有効にしないでください。「いいえ」を推奨します。';

$_lang['setting_manager_lang_attribute'] = '管理画面の言語コード（HTMLとXML）';
$_lang['setting_manager_lang_attribute_desc'] = '管理画面に選択した言語設定に合う言語コードを指定してください。<br />これにより、ブラウザが最適なコンテンツを提示します。';

$_lang['setting_manager_language'] = '管理画面の言語';
$_lang['setting_manager_language_desc'] = '管理画面の使用言語を選択してください';

$_lang['setting_manager_login_url_alternate'] = '管理画面の代替URL';
$_lang['setting_manager_login_url_alternate_desc'] = '管理画面へのアクセスを要求するユーザーに提示する、ログイン用URL。<br />管理画面にアクセスできるよう、"mgr"コンテキストへユーザーをログインする必要があります。';

$_lang['setting_manager_login_start'] = 'スタートページ';
$_lang['setting_manager_login_start_desc'] = 'トップページとして使用するドキュメントのIDを入力してください。<br /><strong>注意: 既に存在するドキュメントのIDを入力してください。ドキュメントは公開されている必要があります</strong>';

$_lang['setting_manager_theme'] = '管理画面のテーマ';
$_lang['setting_manager_theme_desc'] = '管理画面で使用するデザインテーマを選択してください。';

$_lang['setting_manager_time_format'] = '管理画面の時刻フォーマット';
$_lang['setting_manager_time_format_desc'] = '管理画面内で用いる時刻の書式。PHPのdate関数のフォーマットを用いて指定します。';

$_lang['setting_manager_use_tabs'] = '管理画面内でタブを使用';
$_lang['setting_manager_use_tabs_desc'] = '有効の場合、管理画面でコンテンツの配置にタブを使用します。無効の場合はポータルを使用します。';

$_lang['setting_manager_week_start'] = '週の開始曜日';
$_lang['setting_manager_week_start_desc'] = '週の開始曜日を数字で指定します。0（または空白）は日曜日、1は月曜日となります。';

$_lang['setting_mgr_tree_icon_context'] = 'Context tree icon';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Define a CSS class here to be used to display the context icon in the tree. You can use this setting on each context to customize the icon per context.';

$_lang['setting_mgr_source_icon'] = 'Media Source icon';
$_lang['setting_mgr_source_icon_desc'] = 'Indicate a CSS class to be used to display the Media Sources icons in the files tree. Defaults to "icon-folder-open-o"';

$_lang['setting_modRequest.class'] = 'リクエストハンドラクラス';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Media Browser Tree Hide Files';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'If true the files inside folders are not displayed in the Media Browser source tree. Defaults to false.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Media Browser Tree Hide Tooltips';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'If true, no image preview tooltips are shown when hovering over a file in the Media Browser tree. Defaults to true.';

$_lang['setting_modx_browser_default_sort'] = 'ファイルブラウザーのデフォルトソートフィールド';
$_lang['setting_modx_browser_default_sort_desc'] = '管理画面でポップアップのファイルブラウザーを使用している場合の、デフォルトのソートフィールドを指定。<br />指定可能な値は name, size, lastmod （最終更新日時） です。';

$_lang['setting_modx_browser_default_viewmode'] = 'Media Browser Default View Mode';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'The default view mode when using the Media Browser in the manager. Available values are: grid, list.';

$_lang['setting_modx_charset'] = 'エンコード';
$_lang['setting_modx_charset_desc'] = '使用したいエンコードを選択してください。<br />日本語も含め、ほとんどの言語ではデフォルト設定のUTF-8が利用できます。';

$_lang['setting_new_file_permissions'] = '新ファイルのパーミッション';
$_lang['setting_new_file_permissions_desc'] = '管理画面を通じて新しいファイルをアップロードしたとき、ここで設定したパーミッションに変更します。この機能はIISなど一部の環境では動作しません。';

$_lang['setting_new_folder_permissions'] = '新フォルダのパーミッション';
$_lang['setting_new_folder_permissions_desc'] = '管理画面を通じて新しいフォルダをを作成したとき、ここで設定したパーミッションに変更します。この機能はIISなど一部の環境では動作しません。';

$_lang['setting_parser_recurse_uncacheable'] = 'Delay Uncacheable Parsing';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'If disabled, uncacheable elements may have their output cached inside cacheable element content. Disable this ONLY if you are having problems with complex nested parsing which stopped working as expected.';

$_lang['setting_password_generated_length'] = '自動生成するパスワードの長さ';
$_lang['setting_password_generated_length_desc'] = 'ユーザー用に自動生成されるパスワードの文字列長。';

$_lang['setting_password_min_length'] = 'パスワードの最小の長さ';
$_lang['setting_password_min_length_desc'] = 'ユーザー用パスワードの最小の長さ。';

$_lang['setting_preserve_menuindex'] = 'Preserve Menu Index When Duplicating Resources';
$_lang['setting_preserve_menuindex_desc'] = 'When duplicating Resources, the menu index order will also be preserved.';

$_lang['setting_principal_targets'] = 'ロードするACLターゲット';
$_lang['setting_principal_targets_desc'] = 'MODxユーザがロードするACLターゲットをカスタマイズします。';

$_lang['setting_proxy_auth_type'] = 'プロキシの認証タイプ';
$_lang['setting_proxy_auth_type_desc'] = 'BASIC認証またはNTLM認証のいずれかをサポートしています。';

$_lang['setting_proxy_host'] = 'プロキシのホスト';
$_lang['setting_proxy_host_desc'] = 'サーバーがプロキシを使用している場合、パッケージ管理などの機能を使用するには、ここでホスト名を指定する必要があります。';

$_lang['setting_proxy_password'] = 'プロキシのパスワード';
$_lang['setting_proxy_password_desc'] = 'プロキシサーバーの認証用パスワードを指定します。';

$_lang['setting_proxy_port'] = 'プロキシのポート';
$_lang['setting_proxy_port_desc'] = 'プロキシサーバーのポート番号を指定します。';

$_lang['setting_proxy_username'] = 'プロキシのユーザー名';
$_lang['setting_proxy_username_desc'] = 'プロキシサーバーの認証用ユーザー名を指定します。';

$_lang['setting_photo_profile_source'] = 'User photo Media Source';
$_lang['setting_photo_profile_source_desc'] = 'The Media Source used to store users profiles photos. Defaults to default Media Source.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb srcにドキュメントルートより上位階層を許可';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'srcのパスにドキュメントルートより上の階層を許可するか設定します。マルチコンテキストによって複数の仮想ホストを展開する場合などに役立ちます。';

$_lang['setting_phpthumb_cache_maxage'] = 'phpThumb 最大キャッシュ期間';
$_lang['setting_phpthumb_cache_maxage_desc'] = '数値で日数を指定。指定した日数を超えてアクセスされていないサムネイルは削除されます。';

$_lang['setting_phpthumb_cache_maxsize'] = 'phpThumb 最大キャッシュサイズ';
$_lang['setting_phpthumb_cache_maxsize_desc'] = '数値でMBを指定。キャッシュのサイズが指定した値を超えた場合、最近アクセスが最も少ないサムネイルを削除します。';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb 最大キャッシュ数';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = '数値でキャッシュ数を指定。キャッシュの数が指定した値を超えた場合、最近アクセスが最も少ないサムネイルを削除します。';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb ソースファイルのキャッシュ';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = '読み込まれたソースファイルをキャッシュするか設定します。"いいえ"をおすすめします。';

$_lang['setting_phpthumb_document_root'] = 'phpThumb ドキュメントルート';
$_lang['setting_phpthumb_document_root_desc'] = 'サーバー変数のDOCUMENT_ROOTを使用して問題が発生している場合や、OutputThumbnailや！IS_RESOURCEでエラーが発生している場合に設定してください。<br />ドキュメントルートを絶対パスで指定します。この値が空の場合、サーバー変数DOCUMENT_ROOTの値が使用されます。';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb エラー背景色';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'phpThumbのエラー出力背景色を、"#"を取り除いた16進数で指定します。';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb エラーフォントサイズ';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'phpThumbのエラー出力フォントサイズを、em値で指定します。';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb エラーフォント色';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'phpThumbのエラー出力フォント色を、"#"を取り除いた16進数で指定します。';

$_lang['setting_phpthumb_far'] = 'phpThumb アスペクト比の強制（far）';
$_lang['setting_phpthumb_far_desc'] = 'phpThumbのfarオプションの値を指定します。デフォルトは C で、中心に向かってアスペクト比を強制します。';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb ImageMagickのパス';
$_lang['setting_phpthumb_imagemagick_path_desc'] = '省略可能。PHPにデフォルトでImageMagickのパスが設定されていない場合に、代替となるパスを指定できます。';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb 外部からの読み込みを無効化';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'この設定が無効の場合、srcパラメーターに外部サーバーの指定を許可します（nohotlink）。';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb 外部からの画像を消去';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = '外部サーバーからの読み込みが無効の場合、外部サーバーから読み込まれて生成された画像を消去するか設定します。';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb 外部読み込み拒否時のメッセージ';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = '外部サーバーからの画像読み込み時に、代わりに表示されるメッセージを指定します。';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb 読み込みを許可するドメイン';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = '外部からのA comma-delimited list of hostnames that are valid in src URLs.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb サムネイル直リンクの無効化';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = '外部のサーバーからphpThumbを使った画像の表示を無効にします。';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Offsite Linking Erase Image';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Indicates if an image linked from a remote server should be erased when not allowed.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Offsite Linking Require Referrer';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'If enabled, any offsite linking attempts will be rejected without a valid referrer header.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Offsite Linking Not Allowed Message';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'A message that is rendered instead of the thumbnail when an offsite linking attempt is rejected.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Offsite Linking Valid Domains';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'A comma-delimited list of hostnames that are valid referrers for offsite linking.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb 外部サイトからのリンク時の透かし画像';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'オプション。A valid file system path to a file to use as a watermark source when your images are rendered offsite by phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Zoom-Crop';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'phpThumbのズームクロップ設定。デフォルトは、ズームクロップを行わないよう「0」に設定されています。';

$_lang['setting_publish_default'] = 'リソースのデフォルト：公開';
$_lang['setting_publish_default_desc'] = '「はい」を選択すると、新規リソースで「公開する」設定がデフォルトで有効になります。';
$_lang['setting_publish_default_err'] = '新しいドキュメントの公開状態を設定してください。';

$_lang['setting_rb_base_dir'] = 'リソースパス';
$_lang['setting_rb_base_dir_desc'] = 'リソースディレクトリ（通常はassetsディレクトリ）への物理パスを入力してください。通常この設定は自動的に生成されますが、IISなど一部のサーバでは正常に検出されないことがあります。<br />この場合、イメージディレクトリなどのパスをここに入力することができます（パスはWindowsのエクスプローラで見るものです）。<br />注意: ファイルブラウザが全てを扱えるようにするためには、ファイルディレクトリにイメージや文書、Flash他のメディアを格納するサブフォルダを含むべきです。';
$_lang['setting_rb_base_dir_err'] = 'リソースパスのパス名を指定してください。';
$_lang['setting_rb_base_dir_err_invalid'] = 'このリソースパスは存在しないかアクセス禁止になっています。適切なパス名を指定しなおすかディレクトリのパーミッションを変更してください。';

$_lang['setting_rb_base_url'] = 'リソースURL';
$_lang['setting_rb_base_url_desc'] = 'リソースディレクトリ（通常はassetsディレクトリ）への仮想パスを入力してください。MODX設置ディレクトリを基準とした相対パスまたは http://から始まるフルパスを記述します。<br />通常この設定は自動的に生成されますが、IISなど一部のサーバでは正常に検出されないことがあります。）。';
$_lang['setting_rb_base_url_err'] = 'リソースブラウザのベースURLを指定してください。';

$_lang['setting_request_controller'] = 'コントローラのファイル名';
$_lang['setting_request_controller_desc'] = 'リクエストコントローラのファイル名をここで指定します。通常は「index.php」です。';

$_lang['setting_request_method_strict'] = 'ストリクトなリクエストメソッド';
$_lang['setting_request_method_strict_desc'] = 'この設定が有効な場合、フレンドリーURLが有効な状態ではリクエストのIDパラメーターは無視されます。<br />また、フレンドリーURLが無効の状態では、リクエストのエイリアスパラメーターが無視されます。';

$_lang['setting_request_param_alias'] = 'エイリアスを受け取るパラメータ';
$_lang['setting_request_param_alias_desc'] = 'リソースのフレンドリーURLに転送する際、ターゲットとなるエイリアス名を受け取るGETパラメーターの名前を指定します。';

$_lang['setting_request_param_id'] = 'IDを受け取るパラメータ';
$_lang['setting_request_param_id_desc'] = 'フレンドリーURLを使用しない場合に、リソースIDを受け取るGETパラメーターの名前を指定します。';

$_lang['setting_resolve_hostnames'] = 'ホスト名の解決';
$_lang['setting_resolve_hostnames_desc'] = 'サイト表示時に、閲覧者のホストを名前解決しますか？　サーバーに少し負荷が増えますが、閲覧者が意識することはありません（訳注：アクセスログに於ける逆引きのことか？）。';

$_lang['setting_resource_tree_node_name'] = 'リソースツリーのノード用フィールド';
$_lang['setting_resource_tree_node_name_desc'] = 'リソースツリーでノードを表示する際に使用するフィールドを指定します。<br />デフォルトは pagetitle ですが、menutitle や alias、longtitle など任意のリソースフィールドも指定できます。';

$_lang['setting_resource_tree_node_name_fallback'] = 'Resource Tree Node Fallback Field';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Specify the Resource field to use as fallback when rendering the nodes in the Resource Tree. This will be used if the resource has an empty value for the configured Resource Tree Node Field.';

$_lang['setting_resource_tree_node_tooltip'] = 'リソースツリーのツールチップ用フィールド';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'リソースツリーでノードのツールチップを表示する際に使用するフィールドを指定します。<br />menutitle や alias、longtitle など任意のリソースフィールドが指定できます。指定しない場合、longtitle が使用されます。';

$_lang['setting_richtext_default'] = 'デフォルトのリッチテキストエディタ使用設定';
$_lang['setting_richtext_default_desc'] = '「はい」を選択すると、新しいリソースではデフォルトでリッチテキストエディタを使用します。';

$_lang['setting_search_default'] = 'デフォルトの検索可能設定';
$_lang['setting_search_default_desc'] = '「はい」を選択すると、新しいリソースではデフォルトで検索可能となります。';
$_lang['setting_search_default_err'] = 'リソースをデフォルトで検索可能とするか、選択してください。';

$_lang['setting_server_offset_time'] = 'サーバの時間補正';
$_lang['setting_server_offset_time_desc'] = 'サーバーの時間を補正するための、数値を指定します。';

$_lang['setting_server_protocol'] = 'サーバータイプ';
$_lang['setting_server_protocol_desc'] = 'このサイトがSSL接続を使用している場合は、httpsを選択してください。';
$_lang['setting_server_protocol_err'] = 'サーバータイプをhttp・httpsのどちらかで選んでください。';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'セッションCookieのドメイン';
$_lang['setting_session_cookie_domain_desc'] = 'セッションCookieのドメインをカスタマイズします。';

$_lang['setting_session_cookie_lifetime'] = 'セッションCookieの持続時間';
$_lang['setting_session_cookie_lifetime_desc'] = 'セッションCookieの持続時間を秒で指定します。<br />ログイン時に「ログイン情報を記憶」を選択した場合に、セッションCookieの有効期限設定に用いられます。';

$_lang['setting_session_cookie_path'] = 'セッションCookieパス';
$_lang['setting_session_cookie_path_desc'] = 'サイト固有のセッションCookieを識別するために、任意のパスを指定します。';

$_lang['setting_session_cookie_secure'] = 'セキュアなセッションCookieの使用';
$_lang['setting_session_cookie_secure_desc'] = 'httpsでの接続時のみセッションCookieが有効になります。';

$_lang['setting_session_cookie_httponly'] = 'Session Cookie HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'セッションCookieにHTTPOnlyフラグを設定するには、この設定を使用します。';

$_lang['setting_session_gc_maxlifetime'] = 'セッションのガベージコレクション実行期限';
$_lang['setting_session_gc_maxlifetime_desc'] = '\'modSessionHandler\'を使用して、PHPのINI設定"session.gc_maxlifetime"を設定します。';

$_lang['setting_session_handler_class'] = 'セッションハンドラのクラス名';
$_lang['setting_session_handler_class_desc'] = 'データベースによるセッション管理では、\'modSessionHandler\'を使用しています。空白にすると、PHP標準のセッション管理が使用されます。';

$_lang['setting_session_name'] = 'セッション名';
$_lang['setting_session_name_desc'] = 'MODXが使用するセッション名をカスタマイズします。';

$_lang['setting_settings_version'] = 'コアのバージョン';
$_lang['setting_settings_version_desc'] = '稼働しているMODXのバージョンです。';

$_lang['setting_settings_distro'] = 'ディストリビューションの設定';
$_lang['setting_settings_distro_desc'] = '現在MODXがインストールされているディストリビューション。';

$_lang['setting_set_header'] = 'HTTPヘッダーを設定';
$_lang['setting_set_header_desc'] = '有効にすると、リソースでHTTPヘッダーをセットするように試みます。';

$_lang['setting_send_poweredby_header'] = 'Send X-Powered-By Header';
$_lang['setting_send_poweredby_header_desc'] = 'When enabled, MODX will send the "X-Powered-By" header to identify this site as built on MODX. This helps tracking global MODX usage through third party trackers inspecting your site. Because this makes it easier to identify what your site is built with, it might pose a slightly increased security risk if a vulnerability is found in MODX.';

$_lang['setting_show_tv_categories_header'] = 'テンプレート変数編集時に「カテゴリー」タブを表示';
$_lang['setting_show_tv_categories_header_desc'] = '「はい」を選択すると、リソースのテンプレート変数編集時、テンプレート変数を分類する「カテゴリー」タブが表示されます。';

$_lang['setting_signupemail_message'] = 'サインアップメール';
$_lang['setting_signupemail_message_desc'] = 'ユーザ管理画面でユーザーアカウントを作成した時に、ユーザー名とパスワードを記載したメールを送信することができます。このメールの内容を設定してください。<br /><strong>参考:</strong> 下記の差し込み文字列は送信時に自動的に置き換えられます。<br /><br />[[+sname]] - サイトの名前,<br />[[+saddr]] - サイト管理者のメールアドレス,<br />[[+surl]] - サイトのURL,<br />[[+uid]] - ユーザーのログイン名,<br />[[+pwd]] - ユーザーのパスワード,<br />[[+ufn]] - ユーザーのフルネーム.<br /><br /><strong>[[+uid]] と [[+pwd]] は必ず記載してください。記載しない場合はユーザー名とパスワードはメールで送られないため、ユーザーはユーザー名とパスワードを知ることができません。</strong>';
$_lang['setting_signupemail_message_default'] = 'こんにちは[[+uid]]さん。 \n\n[[+sname]]の管理画面のログイン情報をお送りします。\n\nユーザ名: [[+uid]]\nパスワード: [[+pwd]]\n\nOnce you log into the Content Manager （[[+surl]]）, you can change your password.\n\nRegards,\nSite Administrator';

$_lang['setting_site_name'] = 'サイト名';
$_lang['setting_site_name_desc'] = 'サイト名を入力してください。';
$_lang['setting_site_name_err']  = 'サイト名が入力されていません。';

$_lang['setting_site_start'] = 'トップページとして使用するドキュメントのIDを入力してください。';
$_lang['setting_site_start_desc'] = 'トップページとして使用するドキュメントのIDを入力してください。<br /><strong>注意: 既に存在するドキュメントのIDを入力してください。ドキュメントは公開されている必要があります</strong>';
$_lang['setting_site_start_err'] = 'トップページにしたいリソースのIDを指定してください。';

$_lang['setting_site_status'] = 'サイトステータス';
$_lang['setting_site_status_desc'] = 'このサイトをウェブ上に公開するには「はい」（オンライン）を選択してください。<br />「いいえ」（メンテナンスモード）を選択した場合、訪問者には「メンテナンスモード用ページ」または「メンテナンスモード用メッセージ」が表示されます。';
$_lang['setting_site_status_err'] = 'サイトの状態を「オンライン」「メンテナンスモード」いずれかから選んでください。';

$_lang['setting_site_unavailable_message'] = 'メンテナンスモード時メッセージ';
$_lang['setting_site_unavailable_message_desc'] = 'サイトがメンテナンスモードの時、またはエラー発生時に表示するメッセージです。<br /><strong>注意: このメッセージはメンテナンスモード用ページが設定されていないときのみ表示されます。</strong>';

$_lang['setting_site_unavailable_page'] = 'メンテナンスモード用ページ';
$_lang['setting_site_unavailable_page_desc'] = 'メンテナンスモード時に表示するドキュメントのIDをここに入力してください。<br /><strong>注意: 実在するドキュメントのIDを指定してください。また公開中であるものに限ります。</strong>';
$_lang['setting_site_unavailable_page_err'] = 'メンテナンスモード時の表示に使用するドキュメントIDを指定してください。';

$_lang['setting_strip_image_paths'] = 'src属性に相対パスを渡す';
$_lang['setting_strip_image_paths_desc'] = 'この設定を「はい」にした場合、ファイルブラウザで選択したファイル（画像・Flash・その他メディアファイルなど）のsrc指定を相対パスで書き出します。<br />CMSでコンテンツを管理する場合は一般的には絶対パスが無難ですが、MODXでサイトを管理する場合はbaseタグを併用することで相対パスで効率よく運用することができます。';

$_lang['setting_symlink_merge_fields'] = 'シムリンクによるフィールドのマージ';
$_lang['setting_symlink_merge_fields_desc'] = '「はい」を指定すると、シムリンク側のフィールドに入力された値と、転送先のフィールド値が自動的にマージされます。';

$_lang['setting_syncsite_default'] = 'Empty Cache default';
$_lang['setting_syncsite_default_desc'] = 'Select \'Yes\' to empty the cache after you save a resource by default.';
$_lang['setting_syncsite_default_err'] = 'Please state whether or not you want to empty the cache after saving a resource by default.';

$_lang['setting_topmenu_show_descriptions'] = 'トップメニューの説明を表示';
$_lang['setting_topmenu_show_descriptions_desc'] = '「いいえ」とすると、管理画面上部のトップメニューで、説明が非表示になります。';

$_lang['setting_tree_default_sort'] = 'リソースツリーのデフォルトソートフィールド';
$_lang['setting_tree_default_sort_desc'] = '管理画面ロード時にリソースツリーのソートを行うフィールド。';

$_lang['setting_tree_root_id'] = 'リソースツリーのルートID';
$_lang['setting_tree_root_id_desc'] = '管理画面のリソースツリーで、ルートとなるリソースのIDを指定します。ユーザーには指定したIDの子リソースのみが表示されます。';

$_lang['setting_tvs_below_content'] = 'テンプレート変数を「コンテンツ」の下に移動';
$_lang['setting_tvs_below_content_desc'] = '「はい」を選択すると、リソース編集画面で、テンプレート変数はコンテンツの下に表示されます。';

$_lang['setting_ui_debug_mode'] = '管理画面のUIデバッグモード';
$_lang['setting_ui_debug_mode_desc'] = '管理画面で標準のテーマを使用している場合に、UIのJavaScriptがデバッグ情報を出力します。<br />※使用するブラウザが"console.log"の出力に対応している必要があります。';

$_lang['setting_udperms_allowroot'] = 'ルートディレクトリの使用許可';
$_lang['setting_udperms_allowroot_desc'] = 'サイトのルートディレクトリにユーザーがドキュメント/フォルダを作成できるようにしますか？';

$_lang['setting_unauthorized_page'] = '権限外告知のページ';
$_lang['setting_unauthorized_page_desc'] = 'ユーザーがアクセスを許可されていないドキュメントを要求したとき表示するドキュメントのIDを入力してください。<br /><strong>注意: 実在するドキュメントのIDを指定してください。また公開中でしかもPublicなアクセスが可能であるものに限ります</strong>';
$_lang['setting_unauthorized_page_err'] = '権限外告知に利用するドキュメントIDを指定してください。';

$_lang['setting_upload_files'] = 'アップロード可能なファイルタイプ';
$_lang['setting_upload_files_desc'] = 'ファイルブラウザを使用して「assets/files/」にアップロードできるメディアファイルの拡張子をカンマで区切って入力してください。';

$_lang['setting_upload_flash'] = 'アップロード可能なFlash';
$_lang['setting_upload_flash_desc'] = 'ファイルブラウザを使用して「assets/flash/」にアップロードできるFlashファイルの拡張子をカンマで区切って入力してください。';

$_lang['setting_upload_images'] = 'アップロード可能な画像タイプ';
$_lang['setting_upload_images_desc'] = 'ファイルブラウザを使用して「assets/images/」にアップロードできる画像ファイルの拡張子をカンマで区切って入力してください。';

$_lang['setting_upload_maxsize'] = 'アップロードサイズの上限';
$_lang['setting_upload_maxsize_desc'] = '管理画面を通じてアップロード可能なファイルの最大サイズを入力してください。サイズはバイト単位で入力します。<br /><strong>注意: サーバ側で設けられている上限サイズを超えることはできません。</strong>';

$_lang['setting_upload_media'] = 'アップロード可能なマルチメディアタイプ';
$_lang['setting_upload_media_desc'] = 'ファイルブラウザを使用して「assets/media/」にアップロードできるメディアファイルの拡張子をカンマで区切って入力してください。';

$_lang['setting_use_alias_path'] = 'エイリアスパスを使用';
$_lang['setting_use_alias_path_desc'] = 'このオプションを「はい」に設定すると、ドキュメントのエイリアスがパスとして表示されます。<br />例えば、「child」というエイリアスを持ったドキュメント「parent」というエイリアスのフォルダの下に配置されていると、エイリアスパスは「/parent/child.html」になります。<br /><strong>注意: このオプションを「はい」にするとき、つまりエイリアスパスを使用するときは、ドキュメントから参照するものは全て（画像やCSS、JavaScript等を）絶対パスで指定する必要があります。<br />例えば、assets/imagesに対しては/assets/imagesと指定しなければなりません。baseタグを利用すると、この問題をスマートに解決できます。</strong>';

$_lang['setting_use_browser'] = 'ファイルブラウザを使用';
$_lang['setting_use_browser_desc'] = '「はい」を選択するとファイルブラウザを使用できます。<br />これによりユーザーはサーバー上の画像やFlashなどのメディアファイルをブラウザ上で表示したり、サーバーにアップロードできるようになります。';
$_lang['setting_use_browser_err'] = 'ファイルブラウザ使用の有無を選択してください。';

$_lang['setting_use_editor'] = 'リッチテキストモード';
$_lang['setting_use_editor_desc'] = 'リッチテキストエディタを使用しますか？　HTMLを直接書いた方が良いのでしたら「いいえ」を選択し、リッチテキストエディタを使わない設定も可能です。<br />この設定は全てのリソースおよび全てのユーザーに適用されるので注意してください';
$_lang['setting_use_editor_err'] = 'リッチテキストエディタの使用を選択してください。';

$_lang['setting_use_frozen_parent_uris'] = 'Use Frozen Parent URIs';
$_lang['setting_use_frozen_parent_uris_desc'] = 'When enabled, the URI for children resources will be relative to the frozen URI of one of its parents, ignoring the aliases of resources high in the tree.';

$_lang['setting_use_multibyte'] = 'MB関数の使用（マルチバイト）';
$_lang['setting_use_multibyte_desc'] = '※日本語環境では必須です。<br />MB関数を有効にします。PHPのmbstringエクステンションがインストールされている事を確認してください。';

$_lang['setting_use_weblink_target'] = 'WebLinkターゲットの使用';
$_lang['setting_use_weblink_target_desc'] = 'Set to true if you want to have MODX link tags and makeUrl（） generate links as the target URL for WebLinks. Otherwise, the internal MODX URL will be generated by link tags and the makeUrl（） method.';

$_lang['setting_user_nav_parent'] = 'User menu parent';
$_lang['setting_user_nav_parent_desc'] = 'The container used to pull all records for the user menu.';

$_lang['setting_webpwdreminder_message'] = 'パスワード変更リクエストメール';
$_lang['setting_webpwdreminder_message_desc'] = 'ウェブユーザが、メールで新しいパスワードを要求する場合に、常に送信されるメッセージを入力します。コンテント管理はそれらの新しいパスワードと有効化情報を含むメールを送信します。<br /><strong>ノート:</strong>コンテント管理は、メッセージを送るときに以下のプレースホルダを置き換えます。<br /><br />[[+sname]] - ウェブサイト名、<br />[[+saddr]] - ウェブサイトのメールアドレス、<br />[[+surl]] - サイトのURL、<br />[[+uid]] - ユーザーのログイン名またはID、<br />[[+pwd]] - ユーザーのパスワード、<br />[[+ufn]] - ユーザーのフルネーム。<br /><br /><strong>[[+uid]] と [[+pwd]] は必ず記載してください。記載しない場合はユーザー名とパスワードはメールで送られないため、ユーザーはユーザー名とパスワードを知ることができません。</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'こんにちは [[+uid]]さん\n\nパスワードを有効化するために、下記のリンクをクリックしてください。:\n\n[[+surl]]\n\n有効化が完了後、下記のパスワードでログインできます。:\n\nパスワード:[[+pwd]]\n\nこのメール内容に心当たりがない場合は、破棄してください。\n\n\n管理者';

$_lang['setting_websignupemail_message'] = 'ウェブサインアップのメールアドレス';
$_lang['setting_websignupemail_message_desc'] = 'ユーザー自らがウェブユーザーアカウントを作成した時に、ユーザー名とパスワードを記載したメールを送信することができます。このメールの内容を設定してください。<br /><strong>注意:</strong> 下記の差し込み文字列は送信時に自動的に置き換えられます。<br /><br />[[+sname]] - サイトの名前,<br />[[+saddr]] - サイト管理者のメールアドレス,<br />[[+surl]] - サイトのURL,<br />[[+uid]] - ユーザーのログイン名,<br />[[+pwd]] - ユーザーのパスワード,<br />[[+ufn]] - ユーザーのフルネーム.<br /><br /><strong>メールの文章を編集するときは、[[+uid]]と[[+pwd]]を残すようにしてください。記述されてないとユーザー名とパスワードが分からないため、ユーザーはログインできません。</strong>';
$_lang['setting_websignupemail_message_default'] = 'こんにちは [[+uid]]さん \n\n[[+sname]] の管理画面へのログイン情報をお送りします。:\n\nユーザー名: [[+uid]]\nパスワード: [[+pwd]]\n\n[[+sname]]（ [[+surl]] ）の管理画面へログイン後、パスワードを変更できます。\n\n管理者';

$_lang['setting_welcome_screen'] = 'ようこそ画面の表示';
$_lang['setting_welcome_screen_desc'] = '「はい」を選択すると、読み込み完了後ようこそ画面を表示します。そしてそれ以降は表示しません。';

$_lang['setting_welcome_screen_url'] = 'ようこそ画面のURL';
$_lang['setting_welcome_screen_url_desc'] = '管理画面のようこそ画面として表示するURLを指定します。';

$_lang['setting_welcome_action'] = 'Welcome Action';
$_lang['setting_welcome_action_desc'] = 'The default controller to load when accessing the manager when no controller is specified in the URL.';

$_lang['setting_welcome_namespace'] = 'Welcome Namespace';
$_lang['setting_welcome_namespace_desc'] = 'The namespace the Welcome Action belongs to.';

$_lang['setting_which_editor'] = '使用するエディタ';
$_lang['setting_which_editor_desc'] = '使用したいリッチテキストエディタを選択します。パッケージマネージャから他のリッチテキストエディタをダウンロード・インストールできます。';

$_lang['setting_which_element_editor'] = 'エレメントで使用するエディタ';
$_lang['setting_which_element_editor_desc'] = 'エレメントの編集で使用したいリッチテキストエディタを選択します。リッチテキストエディタはパッケージマネージャでダウンロード・インストールできます。';

$_lang['setting_xhtml_urls'] = 'XHTML準拠のURL';
$_lang['setting_xhtml_urls_desc'] = '「はい」を選択すると、MODXによって生成されるすべてのURLは、アンパーサンド（&）を文字実態参照（&amp;）に置き換えた、XHTML準拠のものとなります。';

$_lang['setting_default_context'] = 'デフォルトのコンテキスト';
$_lang['setting_default_context_desc'] = '新規リソースに使用するデフォルトのコンテキストを選択します。';

$_lang['setting_auto_isfolder'] = 'Set container automatically';
$_lang['setting_auto_isfolder_desc'] = 'If set to yes, container property will be changed automatically.';

$_lang['setting_default_username'] = 'Default username';
$_lang['setting_default_username_desc'] = 'Default username for an unauthenticated user.';

$_lang['setting_manager_use_fullname'] = 'Show fullname in manager header ';
$_lang['setting_manager_use_fullname_desc'] = 'If set to yes, the content of the "fullname" field will be shown in manager instead of "loginname"';

$_lang['log_snippet_not_found'] = 'Log snippets not found';
$_lang['log_snippet_not_found_desc'] = 'If set to yes, snippets that are called but not found will be logged to the error log.';

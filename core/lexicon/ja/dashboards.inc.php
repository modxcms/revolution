<?php
/**
 * English language strings for Dashboards
 *
 * @package modx
 * @subpackage lexicon
 * @language en
 */
$_lang['dashboard'] = 'ダッシュボード';
$_lang['dashboard_desc_name'] = 'このダッシュボードの名前';
$_lang['dashboard_desc_description'] = 'このダッシュボードについての短い説明';
$_lang['dashboard_desc_hide_trees'] = 'このオプションを有効にすると、ウェルカムページで左側のツリーが表示されなくなります。';
$_lang['dashboard_hide_trees'] = '左側のツリーを隠す';
$_lang['dashboard_desc_customizable'] = 'Allow users to customize this dashboard for their accounts: create, delete and change position or size of widgets.';
$_lang['dashboard_customizable'] = 'Customizable';
$_lang['dashboard_remove_confirm'] = 'Are you sure you want to delete this Dashboard?';
$_lang['dashboard_remove_multiple_confirm'] = 'Are you sure you want to delete the selected Dashboards?';
$_lang['dashboard_err_ae_name'] = '"[[+name]]"という名前のダッシュボードはすでに存在しています！　別の名前を指定してください';
$_lang['dashboard_err_duplicate'] = 'ダッシュボードの複製時、エラーが発生しました。';
$_lang['dashboard_err_nf'] = 'ダッシュボードはありません。';
$_lang['dashboard_err_ns'] = 'ダッシュボードが指定されていません。';
$_lang['dashboard_err_ns_name'] = 'ダッシュボードの名前を指定してください。';
$_lang['dashboard_err_remove'] = 'An error occurred while trying to delete the Dashboard.';
$_lang['dashboard_err_remove_default'] = 'You cannot delete the default Dashboard!';
$_lang['dashboard_err_save'] = 'ダッシュボードの保存時、エラーが発生しました。';
$_lang['dashboard_usergroup_add'] = 'ユーザーグループへダッシュボードを割り当てる';
$_lang['dashboard_usergroup_remove'] = 'Delete Dashboard from User Group';
$_lang['dashboard_usergroup_remove_confirm'] = 'このユーザーグループがデフォルトのダッシュボードを使用するよう元に戻します。よろしいですか？';
$_lang['dashboard_usergroups.intro_msg'] = 'このダッシュボードを使用しているユーザーグループ';
$_lang['dashboard_widget_err_placed'] = 'このウィジェットはすでにダッシュボードへ配置されています！';
$_lang['dashboard_widgets.intro_msg'] = 'Manage widgets in this dashboard. You can also drag and drop rows in the grid to rearrange them.<br><br>Please note: if a dashboard is "customizable", this settings will be applied only for the first load for every user. From here they will be able to create, delete and change the position or size of their widgets. User access to widgets can be limited by applying permissions.';
$_lang['dashboards'] = 'ダッシュボード';
$_lang['dashboards.intro_msg'] = '管理画面用ダッシュボードを管理します。';
$_lang['rank'] = 'ランク';
$_lang['user_group_filter'] = 'By ユーザーグループ';
$_lang['widget'] = 'ウィジェット';
$_lang['widget_content'] = 'ウィジェットの内容';
$_lang['widget_err_ae_name'] = '"[[+name]]"という名前のウィジェットはすでに存在しています！　別の名前を指定してください';
$_lang['widget_err_nf'] = 'ウィジェットはありません。';
$_lang['widget_err_ns'] = 'ウィジェットが指定されていません！';
$_lang['widget_err_ns_name'] = 'ダッシュボードの名前を指定してください。';
$_lang['widget_err_remove'] = 'An error occurred while trying to delete the Widget.';
$_lang['widget_err_save'] = 'ウィジェットとの保存時に、エラーが発生しました。';
$_lang['widget_file'] = 'ファイル';
$_lang['widget_dashboards.intro_msg'] = 'このウィジェットが配置されているダッシュボード';
$_lang['widget_dashboard_remove'] = 'Delete Widget From Dashboard';
$_lang['widget_description_desc'] = 'ウィジェットの概要、または概要を示すレキシコンのエントリーキーを指定。';
$_lang['widget_html'] = 'HTML';
$_lang['widget_lexicon_desc'] = 'このウィジェットに読み込むレキシコントピックを指定します。名前と説明だけでなく、ウィジェット内のテキストを翻訳する場合に便利です。';
$_lang['widget_permission_desc'] = 'This permission will be required to add this widget to a user dashboard.';
$_lang['widget_permission'] = 'Permission';
$_lang['widget_name_desc'] = 'ウィジェット名、またはレキシコンのエントリーキーを指定。';
$_lang['widget_add'] = 'Add Widget';
$_lang['widget_add_desc'] = 'Please select a Widget to add to your Dashboard.';
$_lang['widget_add_success'] = 'The widget was successfully added to your Dashboard. It will be loaded after closing this window.';
$_lang['widget_remove_confirm'] = 'Are you sure you want to delete this Dashboard Widget? This is permanent, and will delete the Widget from all Dashboards.';
$_lang['widget_remove_multiple_confirm'] = 'Are you sure you want to delete these Dashboard Widgets? This is permanent, and will delete the Widgets from all their assigned Dashboards.';
$_lang['widget_namespace'] = 'ネームスペース';
$_lang['widget_namespace_desc'] = 'このウィジェットがロードされているネームスペース。カスタムパスの利用時に便利です。';
$_lang['widget_php'] = 'インラインPHPウィジェット';
$_lang['widget_place'] = 'ウィジェットの場所';
$_lang['widget_size'] = 'サイズ';
$_lang['widget_size_desc'] = 'The size of the widget. Can either be a from "quarter" to "double".';
$_lang['widget_size_double'] = 'Double Size';
$_lang['widget_size_full'] = 'Full Size';
$_lang['widget_size_three_quarters'] = 'Three Quarters';
$_lang['widget_size_two_third'] = 'Two Third';
$_lang['widget_size_half'] = 'ハーフ（半分の横幅）';
$_lang['widget_size_one_third'] = 'One Third';
$_lang['widget_size_quarter'] = 'Quarter';
$_lang['widget_snippet'] = 'スニペット';
$_lang['widget_type'] = 'ウィジェットのタイプ';
$_lang['widget_type_desc'] = 'ウィジェットには以下のタイプがあります。"スニペット"ウィジェットは、MODXのスニペットの実行結果を返します。"HTML"ウィジェットは、入力されたHTMLコードをそのまま出力します。 "ファイル"ウィジェットは、ファイルから直接読み込まれます。ファイルは、直接出力を返すか、modDashboardWidgetClassクラスを継承している必要があります。 "インラインPHP"ウィジェットはスニペットに似ていて、実行したいPHPコードを直接記入できます。';
$_lang['widget_unplace'] = 'Delete Widget from Dashboard';
$_lang['widgets'] = 'ウィジェット';
$_lang['widgets.intro_msg'] = 'ダッシュボード用ウィジェットの作成や削除を行います。';

$_lang['action_new_resource'] = 'New page';
$_lang['action_new_resource_desc'] = 'Create a new page for your website.';
$_lang['action_view_website'] = 'View website';
$_lang['action_view_website_desc'] = 'Open your website in a new window.';
$_lang['action_advanced_search'] = 'Advanced search';
$_lang['action_advanced_search_desc'] = 'Advanced search through your website.';
$_lang['action_manage_users'] = 'Manage users';
$_lang['action_manage_users_desc'] = 'Manage all your website and manager users.';

$_lang['w_buttons'] = 'Buttons';
$_lang['w_buttons_desc'] = 'Displays a set of buttons from array specified in properties.';
$_lang['w_updates'] = 'Updates';
$_lang['w_updates_desc'] = 'Checks for available updates for core and extras.';
$_lang['w_configcheck'] = '設定チェック';
$_lang['w_configcheck_desc'] = '現在の設定が安全かチェックし、問題点を表示します。';
$_lang['w_newsfeed'] = 'MODXニュースフィード';
$_lang['w_newsfeed_desc'] = 'MODXの公式ニュースを表示します（システム設定で指定されたフィードを使用）。';
$_lang['w_recentlyeditedresources'] = '更新されたリソース';
$_lang['w_recentlyeditedresources_desc'] = 'ユーザーによって作成または編集されたリソースを一覧します。';
$_lang['w_securityfeed'] = 'MODXセキュリティフィード';
$_lang['w_securityfeed_desc'] = 'MODXの公式セキュリティ情報を表示します（システム設定で指定されたフィードを使用）。';
$_lang['w_whosonline'] = 'オンライン中のユーザー';
$_lang['w_whosonline_desc'] = '現在オンライン中のユーザーを一覧します。';
$_lang['w_view_all'] = 'View all';
$_lang['w_no_data'] = '表示するデータがありません。';
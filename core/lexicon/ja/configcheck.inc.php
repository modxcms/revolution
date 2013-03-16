<?php
/**
 * Config Check Japanese lexicon topic
 *
 * @language ja
 * @package modx
 * @subpackage lexicon
 * @author honda http://kogus.org 2012-09-21
 * @author Nick http://smallworld.west-tokyo.com
 * @author shimojo http://www.priqia.com/
 * @author yamamoto http://kyms.jp
 */
$_lang['configcheck_admin'] = 'このメッセージをシステム管理者に報告してください';
$_lang['configcheck_cache'] = 'キャッシュディレクトリに書き込みができません。';
$_lang['configcheck_cache_msg'] = 'キャッシュディレクトリにキャッシュファイルを保存できませんでした。キャッシュが利用できないため、MODXならではの軽快なレスポンスを得られません。/core/cache/web/resources/ ディレクトリを書き込み可能にしてください。';
$_lang['configcheck_configinc'] = '設定ファイルがまだ書き込み可能になっています。';
$_lang['configcheck_configinc_msg'] = '今の状態では、悪意ある人がこのサイトを壊すことができてしまいます。設定ファイル ([[+path]])のパーミッションを404などに設定し、書込み不可にしてください。';
$_lang['configcheck_default_msg'] = '不明な警告が見つかりました。';
$_lang['configcheck_errorpage_unavailable'] = '設定した「エラーページ」は利用できません。';
$_lang['configcheck_errorpage_unavailable_msg'] = '設定した「エラーページ」が一般的なエンドユーザーからアクセスできないページ（private）かあるいは存在しないことを意味しています。この場合、システムがループ状態に陥り、多くのエラーメッセージをエラーログに記録してしまいます。「エラーページ」には存在するページでかつ、公開ページ、かつ「public」なページを指定してください。';
$_lang['configcheck_errorpage_unpublished'] = 'システム設定で設定されたエラーページは公開されていません。';
$_lang['configcheck_errorpage_unpublished_msg'] = '設定したエラーページが誰でも見られるようにはなっていないことを意味します。エラーページは全ての人(Public)に公開するようにしてください。';
$_lang['configcheck_images'] = 'イメージディレクトリに書き込みができません。';
$_lang['configcheck_images_msg'] = 'イメージディレクトリが書き込み可能でないか、またはイメージディレクトリが存在していません。エディターの画像管理機能が動作しません。';
$_lang['configcheck_installer'] = 'インストーラーが残されています。';
$_lang['configcheck_installer_msg'] = '/setup/ ディレクトリにMODXのインストーラーが残されています。悪意ある攻撃を受ける可能性がありますので、早急にこのディレクトリを削除してください。It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = '言語ファイルのエントリー数が異なります。';
$_lang['configcheck_lang_difference_msg'] = '現在選択されている言語ファイルのエントリー数がデフォルト言語ファイル(英語)のエントリー数と異なるため、一部が英語で表示されます。日本語で表示したい場合は、最新の言語ファイルを入手しアップデートする必要があります。';
$_lang['configcheck_notok'] = '<span style="font-weight:bold;color:red;">動作環境に問題があります。</span>';
$_lang['configcheck_ok'] = '確認OK - 警告はありません。';
$_lang['configcheck_register_globals'] = '"register_globals" が "ON" に設定されておりセキュリティ上問題があります。';
$_lang['configcheck_register_globals_msg'] = 'この状態は、クロスサイトスクリプティング攻撃(XSS)を受けやすい脆弱性があります。XSS攻撃はMODX本体に限らず、サードパーティによって開発されるスニペットやプラグインも攻撃対象となります。register_globals on の環境を特に必要としない場合は、.htaccessまたはphp.iniによって OFF にすることを強くおすすめします。';
$_lang['configcheck_title'] = '設定チェック';
$_lang['configcheck_unauthorizedpage_unavailable'] = '「権限外告知のページ」は公開されていないか、存在しません。';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = '設定した「権限外告知のページ」が一般的なエンドユーザーからアクセスできないページ（private）かあるいは存在しないことを意味しています。システムがループ状態に陥り、多くのエラーメッセージをエラーログに刻むでしょう。「権限外告知のページ」には存在するページでかつ、公開ページ、かつ「public」なページを指定してください。';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'システム設定で設定された権限外告知のページは公開されていません。';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = '設定した権限外告知のページが誰でも見られるようにはなっていないことを意味します。権限外告知ページは全ての人「Public」に公開するようにしてください。';
$_lang['configcheck_warning'] = '警告:';
$_lang['configcheck_what'] = 'この警告の意味';
<?php
/**
 * Import Japanese lexicon entries
 *
 * @language ja
 * @package modx
 * @subpackage lexicon
 * @author honda http://kogus.org 2012-08-16
 * @author Nick http://smallworld.west-tokyo.com
 * @author shimojo http://www.priqia.com/
 * @author yamamoto http://kyms.jp
 */
$_lang['import_allowed_extensions'] = 'インポートしたいファイルタイプ：<br /><small><em>拡張子のカンマ区切りのリストで指定します。空白を指定すると、インポートするサイトで必要なコンテンツの種類に応じて、全てのファイルをインポートします。未知のタイプは、プレーンテキストとしてインポートされます。</small></em>';
$_lang['import_base_path'] = 'インポート対象ファイルのベースパス：<br /><small><em>空白の場合、対象コンテキストの静的ファイルパスを使用します。</em></small>';
$_lang['import_duplicate_alias_found'] = 'id [[+id]]のリソースは既に [[+alias]]というエイリアスを使用しています。別な一意のエイリアスを指定してください。';
$_lang['import_element'] = 'インポートするhtml要素：';
$_lang['import_enter_root_element'] = 'インポートするhtml要素を指定してください。';
$_lang['import_files_found'] = '<strong>インポートする %s個のドキュメントを見つけました。</strong></p>';
$_lang['import_parent_document'] = '親ドキュメント:';
$_lang['import_parent_document_message'] = 'ファイルのインポート先をどの親ドキュメント配下にするか、下のドキュメントツリーから選んでください。';
$_lang['import_resource_class'] = 'インポートに使用するmodResourceクラス：<br /><small><em><dl><dt>modStaticResource</dt><dd>静的なファイルへのリンクを行う</dd><dt>modDocument</dt><dd>データベースにコンテンツをコピーする</dd></dl></em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">失敗</span>';
$_lang['import_site_html'] = 'htmlからサイトをインポート';
$_lang['import_site_importing_document'] = 'インポート中のファイル <strong>%s</strong> ';
$_lang['import_site_maxtime'] = '<span style="white-space:nowrap;">インポート時間の上限:</span>';
$_lang['import_site_maxtime_message'] = 'サイトのインポート処理に使用できる最大秒数を指定します。（PHPの指定処理秒数を上書きします）0は処理時間の制限なしを意味します。0や大きな数字を指定することはサーバーに負荷をかけ問題を起こしやすくなるため、お勧めできません。';
$_lang['import_site_message'] = 'この機能を使うと、htmlで記述されたサイトを丸ごとデータベースにインポートすることができます。まずインポートするファイルやフォルダをassets/importフォルダにコピーして置きます。<p />「インポート開始」ボタンをクリックすればスタートします。インポートしたファイルのデータはドキュメントツリーの選択した場所に格納されます。ファイル名はエイリアスになり、ページタイトルはドキュメントのタイトルになります。';
$_lang['import_site_resource'] = '静的ファイルからリソースをインポート';
$_lang['import_site_resource_message'] = '<p>このツールを使用すると、静的ファイルのセットからデータベースへ、リソースをインポートできます。 <em>core/import へファイルやフォルダをコピーする必要があります</em></p><p>下記のフォームに情報を入力し、必要に応じてインポート先となる親リソースのidをツリーで確認して入力してください。その後「リソースのインポート」ボタンを押すと、インポートが開始されます。<br />インポートされたファイルは、指定した場所にリソースとして保存されます。可能であれば、ファイル名がリソースのエイリアスとして使用され、htmlファイルの場合はページタイトルがドキュメントのタイトルとして使用されます。</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">処理をスキップしました</span>';
$_lang['import_site_start'] = 'インポート開始';
$_lang['import_site_success'] = '<span style="color:#009900">成功です</span>';
$_lang['import_site_time'] = 'インポート終了。インポートに %s 秒かかりました。';
$_lang['import_use_doc_tree'] = 'ファイルのインポート先をどの親ドキュメント配下にするか、下のドキュメントツリーから選んでください。';
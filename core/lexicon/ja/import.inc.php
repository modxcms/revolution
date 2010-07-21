<?php
/**
 * Import Japanese lexicon entries
 *
 * @language ja
 * @package modx
 * @subpackage lexicon
 * @author Nick http://smallworld.west-tokyo.com
 * @author shimojo http://www.priqia.com/
 * @author yamamoto http://kyms.jp
 */
$_lang['import_allowed_extensions'] = 'Specify a comma-delimited list of file extensions to import.<br /><small><em>Leave blank to import all files according to the content types available in your site. Unknown types will be mapped as plain text.</em></small>';
$_lang['import_base_path'] = 'Enter the base file path containing the files to import.<br /><small><em>Leave blank to use the target context\'s static file path setting.</em></small>';
$_lang['import_duplicate_alias_found'] = 'id [[+id]]のリソースは既に [[+alias]]というエイリアスを使用しています。別な一意のエイリアスを指定してください。';
$_lang['import_element'] = 'どのHTMLエレメントをインポートするかを決定してください。:';
$_lang['import_enter_root_element'] = 'どのHTMLエレメントをインポートするかを決定してください。:';
$_lang['import_files_found'] = '<strong>インポートする %s個のドキュメントを見つけました。</strong><p/>';
$_lang['import_parent_document'] = '親ドキュメント:';
$_lang['import_parent_document_message'] = 'ファイルのインポート先をどの親ドキュメント配下にするか、下のドキュメントツリーから選んでください。';
$_lang['import_resource_class'] = 'Select a modResource class for import:<br /><small><em>Use modStaticResource to link to static files, or modDocument to copy the content to the database.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">失敗です</span>';
$_lang['import_site_html'] = 'HTMLからサイトをインポート';
$_lang['import_site_importing_document'] = 'インポート中のファイル <strong>%s</strong> ';
$_lang['import_site_maxtime'] = '<span style="white-space:nowrap;">インポート時間の上限:</span>';
$_lang['import_site_maxtime_message'] = 'MODxがサイトのインポート処理に使用できる最大秒数を指定します。（PHPの指定処理秒数を上書きします）0は処理時間の制限なしを意味します。0や大きな数字を指定することはサーバーに負荷をかけ問題を起こしやすくなるため、お勧めできません。';
$_lang['import_site_message'] = 'この機能を使うと、HTMLで記述されたサイトを丸ごとデータベースにインポートすることができます。まずインポートするファイルやフォルダをassets/importフォルダにコピーして置きます。<p />「インポート開始」ボタンをクリックすればスタートします。インポートしたファイルのデータはドキュメントツリーの選択した場所に格納されます。ファイル名はエイリアスになり、ページタイトルはドキュメントのタイトルになります。';
$_lang['import_site_resource'] = '静的ファイルからリソースをインポート';
$_lang['import_site_resource_message'] = '<p>Using this tool you can import resources from a set of static files into the database. <em>Please note that you will need to copy your files and/or folders into the core/import folder.</em></p><p>Please fill out the form options below, optionally select a parent resource for the imported files from the document tree, and press \'Import Resources\' to start the import process. The files imported will be saved into the selected location, using, where possible, the files name as the document\'s alias, and, if HTML, the page title as the document\'s title.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">処理をスキップしました</span>';
$_lang['import_site_start'] = 'インポート開始';
$_lang['import_site_success'] = '<span style="color:#009900">成功です</span>';
$_lang['import_site_time'] = 'インポート終了。インポートには %s 秒かかりました。';
$_lang['import_use_doc_tree'] = 'ファイルのインポート先をどの親ドキュメント配下にするか、下のドキュメントツリーから選んでください。';
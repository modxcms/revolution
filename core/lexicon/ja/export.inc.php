<?php
/**
 * Export Japanese lexicon topic
 *
 * @language ja
 * @package modx
 * @subpackage lexicon
 * @author Nick http://smallworld.west-tokyo.com
 * @author shimojo http://www.priqia.com/
 * @author yamamoto http://kyms.jp
 */
$_lang['export_site_cacheable'] = '<span style="white-space:nowrap;">非キャッシュファイルを含む:</span>';
$_lang['export_site_exporting_document'] = 'エクスポートファイル <strong>%s</strong> of <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">失敗</span>';
$_lang['export_site_html'] = 'サイト全体を静的HTMLとしてエクスポート';
$_lang['export_site_maxtime'] = '最大エクスポート時間:';
$_lang['export_site_maxtime_message'] = 'サイトのエクスポートに使用できる秒数を指定してください(PHPの設定を上書きします)。「0」を入力すると時間無制限になります。';
$_lang['export_site_message'] = '<p>サイト全体をHTMLファイルとしてエクスポート(一括書き出し)できます。書き出したファイルをまとめてサーバ上に転送することで、通常のサイトと同様の運用が可能です。この場合、通常の静的サイトの運用と同等になるため、下記の機能が失われます。:</p><ul><li>エクスポートファイルのページ閲覧はログに記録されません。</li><li>エクスポートファイルで動的なスニペットは動作しません。</li><li>通常のドキュメントのみエクスポートされ、ウェブリンクはエクスポートされません。</li><li>ドキュメントにリダイレクトヘッダを送信するスニペットが含まれている場合、エクスポートプロセスが失敗する場合があります。</li><li>ドキュメント、スタイルシート、イメージの記述方法により、サイトのデザインが崩れる場合があります。これを直すためには、MODXのindex.phpファイルが保存されている同じディレクトリに、エクスポートファイルを保存または移動してください。</li></ul><p>フォームに必要事項を入力して「エクスポートの開始」をクリックすると処理を開始します。作成されたファイルは、ドキュメントエイリアスをファイル名として、指定した場所に保存されます。サイトをエクスポートする場合、システム設定で「フレンドリエイリアス」を「はい」にすることをお勧めします。サイトのサイズにより、エクスポートに暫く時間を要する場合もあります。</p><p><em>ファイル名が同じ場合、新しいファイルは既存のファイルに上書きされます</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>%s 個のエクスポート対象ドキュメントが見つかりました。</strong></p>';
$_lang['export_site_prefix'] = 'ファイル名の接頭辞:';
$_lang['export_site_start'] = 'エクスポートの開始';
$_lang['export_site_success'] = '<span style="color:#009900">成功</span>';
$_lang['export_site_suffix'] = 'ファイル名の接尾辞:';
$_lang['export_site_target_unwritable'] = 'ターゲットディレクトリ内に書き込みができません。ディレクトリが書き込み可能であるか確認して、再度処理を行ってください。';
$_lang['export_site_time'] = 'エクスポートが完了しました。エクスポートに要した時間は %s 秒です。';
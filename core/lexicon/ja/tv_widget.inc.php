<?php
/**
 * TV Widget Japanese lexicon topic
 *
 * @language ja
 * @package modx
 * @subpackage lexicon
 * @author honda http://kogus.org 2012-05-24
 * @author Nick http://smallworld.west-tokyo.com
 * @author shimojo http://www.priqia.com/
 * @author yamamoto http://kyms.jp
 */
$_lang['attributes'] = '属性';
$_lang['capitalize'] = '先頭を大文字';
$_lang['checkbox'] = 'チェックボックス';
$_lang['class'] = 'クラス';
$_lang['combo_allowaddnewdata'] = '新たな項目の追加を許可';
$_lang['combo_allowaddnewdata_desc'] = '「はい」を選ぶと、リストに存在しない項目の追加を許可します。デフォルトは「いいえ」です。';
$_lang['combo_forceselection'] = 'オプション値からの入力を強制';
$_lang['combo_forceselection_desc'] = '「はい」を指定すると、オプション値からの入力が強制されます。一致するものがオプション値にない場合、入力は無視され元の値に戻ります。';
$_lang['combo_listempty_text'] = 'オプション値と不一致時のメッセージ';
$_lang['combo_listempty_text_desc'] = '入力した値がオプション値に含まれていない場合、ここで指定した値がリストの代わりに表示されます。';
$_lang['combo_listwidth'] = 'リストの表示幅（px）';
$_lang['combo_listwidth_desc'] = 'ドロップダウンリストの表示幅をpxで指定します。デフォルトはコンボボックスの幅です。';
$_lang['combo_maxheight'] = '最大の高さ（px）';
$_lang['combo_maxheight_desc'] = 'スクロールバー非表示の状態の、ドロップダウンリストの最大の高さをピクセル数で指定します（デフォルトは300ピクセル）。';
$_lang['combo_stackitems'] = '選択した項目をスタックする';
$_lang['combo_stackitems_desc'] = '選択された項目をリストの上部に積み上げて表示します（この機能には問題があり、うまく表示されない場合があります）。';
$_lang['combo_title'] = 'リスト内の見出し';
$_lang['combo_title_desc'] = 'ここに設定した値が、リスト項目の先頭に見出しとして挿入されます。';
$_lang['combo_typeahead'] = '入力補完を有効にする';
$_lang['combo_typeahead_desc'] = '「はい」を指定した場合、"入力補完の遅延時間（ミリ秒）"で指定した遅延後、オプション値による入力の補完が行われます。';
$_lang['combo_typeahead_delay'] = '入力補完の遅延時間（ミリ秒）';
$_lang['combo_typeahead_delay_desc'] = '"入力補完を有効にする"が「はい」の場合の、補完を遅延させる秒数をミリ秒で指定します。(デフォルトは250ミリ秒).';
$_lang['date'] = '日付';
$_lang['date_format'] = '日付フォーマット';
$_lang['date_use_current'] = '値がない場合、現在の日時を使用';
$_lang['default'] = 'デフォルト';
$_lang['delim'] = '区切り文字';
$_lang['delimiter'] = '区切り文字';
$_lang['disabled_dates'] = '無効にする日付';
$_lang['disabled_dates_desc'] = '無効にしたい日付のパターンをコンマ区切りで指定します。指定したパターンは動的に正規表現へ変換されます。<br />
指定の例：<br />
- 厳密に特定の年月日を無効とします：2003-03-08,2003-09-16<br />
- 年に関係なく、特定の月日を無効とします: 03-08,09-16<br />
- 先頭部分が一致する日付を無効にします (2ケタの年表記を使用している場合に便利です)：^03-08<br />
- 2006年の3月を全て無効にします：03-..-2006<br />
- 全ての年で3月を無効にします：^03<br />
※注：条件には正確なフォーマットを指定する必要があります。また、正規表現による処理を行うため、日付の表記に "." 文字を使用している場合、エスケープする必要があります。';
$_lang['disabled_days'] = '無効にする曜日';
$_lang['disabled_days_desc'] = '無効にしたい曜日をカンマ区切りで指定します。曜日は、日曜日を先頭とし、0～6の数値で指定します。<br />
指定の例：<br />
- 土日を無効にします：0,6<br />
- 平日を無効にします：1,2,3,4,5';
$_lang['dropdown'] = 'ドロップダウンリストメニュー';
$_lang['earliest_date'] = '最も古い日付';
$_lang['earliest_date_desc'] = '選択可能な最も古い日付を指定します。';
$_lang['earliest_time'] = '最も早い時刻';
$_lang['earliest_time_desc'] = '選択可能な最も早い時間を指定します。';
$_lang['email'] = 'メールアドレス';
$_lang['file'] = 'ファイル';
$_lang['height'] = '高さ';
$_lang['hidden'] = 'Hidden(隠しフィールド)';
$_lang['htmlarea'] = 'HTML Area';
$_lang['htmltag'] = 'HTMLタグ';
$_lang['image'] = '画像';
$_lang['image_align'] = '位置';
$_lang['image_align_list'] = 'なし,ベースライン,上端揃え,中央揃え,下端揃え,テキストの上端,中央,テキストの下端,左寄せ,右寄せ';
$_lang['image_alt'] = '代替テキスト';
$_lang['image_border_size'] = 'ボーダーサイズ';
$_lang['image_hspace'] = '余白(左右)';
$_lang['image_vspace'] = '余白(上下)';
$_lang['latest_date'] = '最も新しい日付';
$_lang['latest_date_desc'] = '選択可能な最も新しい日付を指定します。';
$_lang['latest_time'] = '最も遅い時刻';
$_lang['latest_time_desc'] = '選択可能な最も遅い時刻を指定します。';
$_lang['listbox'] = 'リストボックス (単一選択)';
$_lang['listbox-multiple'] = 'リストボックス (複数選択)';
$_lang['lower_case'] = '小文字';
$_lang['max_length'] = '最大の長さ';
$_lang['min_length'] = '最小の長さ';
$_lang['name'] = '名前';
$_lang['number'] = '番号';
$_lang['number_allowdecimals'] = '小数を許可';
$_lang['number_allownegative'] = '負の値を許可';
$_lang['number_decimalprecision'] = '小数点以下の桁数（動作が不安定です）';
$_lang['number_decimalprecision_desc'] = '許可する小数点以下の桁数を指定します（デフォルトは 2）。';
$_lang['number_decimalseparator'] = '小数点の区切り';
$_lang['number_decimalseparator_desc'] = '小数点を区切る文字を指定します（デフォルトは "."）。';
$_lang['number_maxvalue'] = '最大値';
$_lang['number_minvalue'] = '最小値';
$_lang['option'] = 'ラジオボタン';
$_lang['parent_resources'] = '親リソース';
$_lang['radio_columns'] = '列数';
$_lang['radio_columns_desc'] = 'ラジオボタンを指定した列数に分けて表示します。';
$_lang['rawtext'] = '変換無しテキスト (非推奨)';
$_lang['rawtextarea'] = '変換無し複数行テキスト (非推奨)';
$_lang['required'] = '入力必須としない';
$_lang['required_desc'] = '「いいえ」を指定した場合、空白以外の有効な値が入力されなければリソースは保存できません。';
$_lang['resourcelist'] = 'リソースリスト';
$_lang['resourcelist_depth'] = '対象とする深さ';
$_lang['resourcelist_depth_desc'] = 'リストに表示するリソースの範囲を、親リソースからの深さで指定します。デフォルトは10です。';
$_lang['resourcelist_includeparent'] = '親を含む';
$_lang['resourcelist_includeparent_desc'] = '「はい」を指定した場合、リストには指定した親リソースも含まれます。';
$_lang['resourcelist_limitrelatedcontext'] = '関連するコンテキストのみに制限';
$_lang['resourcelist_limitrelatedcontext_desc'] = '「はい」を指定した場合、現在のリソースのコンテキストに関連するリソースのみが含まれるようになります。';
$_lang['resourcelist_limit'] = 'リストの上限数';
$_lang['resourcelist_limit_desc'] = 'リストに表示するリソースの上限数を指定します。0を指定した場合、無制限となります。';
$_lang['resourcelist_parents'] = '親リソース（id）';
$_lang['resourcelist_parents_desc'] = 'リストアップしたいリソースの、親となるリソースをidで指定します。';
$_lang['resourcelist_where'] = 'リストのフィルタリング';
$_lang['resourcelist_where_desc'] = 'JSONによる条件指定で表示するリソースをフィルタリングします。条件は {"pagetitle":"foo"} のように、項目と値をペアにしたJSONオブジェクトとして指定します。（テンプレート変数の検索には対応していません）。';
$_lang['richtext'] = 'リッチテキスト';
$_lang['sentence_case'] = '文頭の大文字化';
$_lang['shownone'] = '選択なしを許可する';
$_lang['shownone_desc'] = '値を選択しないことを許可します。';
$_lang['start_day'] = '週の開始曜日';
$_lang['start_day_desc'] = '週を開始する曜日を、日曜日（0）～土曜日（6）の値で指定します（デフォルトは日曜日）。';
$_lang['string'] = '文字列';
$_lang['string_format'] = '文字列の形式';
$_lang['style'] = 'スタイル';
$_lang['tag_id'] = 'タグID';
$_lang['tag_name'] = 'タグ名';
$_lang['target'] = 'ターゲット';
$_lang['text'] = 'テキスト';
$_lang['textarea'] = '複数行テキスト';
$_lang['textareamini'] = '複数テキスト (ミニ)';
$_lang['textbox'] = 'テキストボックス';
$_lang['time_increment'] = '時刻の間隔';
$_lang['time_increment_desc'] = '時刻リストでの各項目の間隔を、分数で指定します（デフォルトは15）。';
$_lang['title'] = 'タイトル';
$_lang['upper_case'] = '大文字';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = '表示テキスト';
$_lang['width'] = '幅';
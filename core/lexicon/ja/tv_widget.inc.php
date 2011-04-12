<?php
/**
 * TV Widget Japanese lexicon topic
 *
 * @language ja
 * @package modx
 * @subpackage lexicon
 * @author Nick http://smallworld.west-tokyo.com
 * @author shimojo http://www.priqia.com/
 * @author yamamoto http://kyms.jp
 */
$_lang['attributes'] = '属性';
$_lang['capitalize'] = 'カテゴリー';
$_lang['checkbox'] = 'チェックボックス';
$_lang['class'] = 'クラス';
$_lang['combo_allowaddnewdata'] = 'Allow Add New Items';
$_lang['combo_allowaddnewdata_desc'] = 'When Yes, allows items to be added that do not already exist in the list. Defaults to No.';
$_lang['combo_forceselection'] = 'Force Selection to List';
$_lang['combo_forceselection_desc'] = 'If using Type-Ahead, if this is set to Yes, only allow inputting of items in the list.';
$_lang['combo_listempty_text'] = 'Empty List Text';
$_lang['combo_listempty_text_desc'] = 'If Type-Ahead is on, and the user types a value not in the list, display this text.';
$_lang['combo_listwidth'] = 'List Width';
$_lang['combo_listwidth_desc'] = 'The width, in pixels, of the dropdown list itself. Defaults to the width of the combobox.';
$_lang['combo_maxheight'] = 'Max Height';
$_lang['combo_maxheight_desc'] = 'The maximum height in pixels of the dropdown list before scrollbars are shown (defaults to 300).';
$_lang['combo_stackitems'] = 'Stack Selected Items';
$_lang['combo_stackitems_desc'] = 'When set to Yes, the items will be stacked 1 per line. Defaults to No which displays the items inline.';
$_lang['combo_title'] = 'List Header';
$_lang['combo_title_desc'] = 'If supplied, a header element is created containing this text and added into the top of the dropdown list.';
$_lang['combo_typeahead'] = 'Enable Type-Ahead';
$_lang['combo_typeahead_desc'] = 'If yes, populate and autoselect the remainder of the text being typed after a configurable delay (Type Ahead Delay) if it matches a known value (defaults to off.).';
$_lang['combo_typeahead_delay'] = 'Type-Ahead Delay';
$_lang['combo_typeahead_delay_desc'] = 'The length of time in milliseconds to wait until the Type-Ahead text is displayed if Type-Ahead is enabled (defaults to 250).';
$_lang['date'] = '日付';
$_lang['date_format'] = '日付フォーマット';
$_lang['date_use_current'] = '値がない場合、現在の日時を使用';
$_lang['default'] = 'デフォルト';
$_lang['delim'] = '区切り文字';
$_lang['delimiter'] = '区切り文字';
$_lang['disabled_dates'] = 'Disabled Dates';
$_lang['disabled_dates_desc'] = 'A comma-separated list of "dates" to disable, as strings. These strings will be used to build a dynamic regular expression so they are very powerful. Some examples:<br />
- Disable these exact dates: 2003-03-08,2003-09-16<br />
- Disable these days for every year: 03-08,09-16<br />
- Only match the beginning (useful if you are using short years): ^03-08<br />
- Disable every day in March 2006: 03-..-2006<br />
- Disable every day in every March: ^03<br />
Note that the format of the dates included in the list should exactly match the format config. In order to support regular expressions, if you are using a date format that has "." in it, you will have to escape the dot when restricting dates.';
$_lang['disabled_days'] = 'Disabled Days';
$_lang['disabled_days_desc'] = 'A comma-separated list of days to disable, 0 based (defaults to null). Some examples:<br />
- Disable Sunday and Saturday: 0,6<br />
- Disable weekdays: 1,2,3,4,5';
$_lang['dropdown'] = 'ドロップダウンリストメニュー';
$_lang['earliest_date'] = 'Earliest Date';
$_lang['earliest_date_desc'] = 'The earliest allowed date that can be selected.';
$_lang['earliest_time'] = 'Earliest Time';
$_lang['earliest_time_desc'] = 'The earliest allowed time that can be selected.';
$_lang['email'] = 'メールアドレス';
$_lang['file'] = 'ファイル';
$_lang['height'] = '高さ';
$_lang['hidden'] = 'Hidden';
$_lang['htmlarea'] = 'HTML Area';
$_lang['htmltag'] = 'HTMLタグ';
$_lang['image'] = '画像';
$_lang['image_align'] = '位置';
$_lang['image_align_list'] = 'なし,ベースライン,上端揃え,中央揃え,下端揃え,テキストの上端,中央,テキストの下端,左寄せ,右寄せ';
$_lang['image_alt'] = '代替テキスト';
$_lang['image_allowedfiletypes'] = 'Allowed File Extensions';
$_lang['image_allowedfiletypes_desc'] = 'If set, will restrict the files shown to only the specified extensions. Please specify in a comma-separated list, without the .';
$_lang['image_basepath'] = 'Base Path';
$_lang['image_basepath_desc'] = 'The file path to point the Image TV to. If not set, will use the filemanager_path setting, or the base MODX path. May use [[++base_path]], [[++core_path]] and [[++assets_path]] placeholders inside this value.';
$_lang['image_basepath_relative'] = 'Base Path Relative';
$_lang['image_basepath_relative_desc'] = 'If the Base Path setting above is not relative to the MODX install path, set this to Yes.';
$_lang['image_baseurl'] = 'Base URL';
$_lang['image_baseurl_desc'] = 'The file URL to point the Image TV to. If not set, will use the filemanager_url setting, or the base MODX URL. May use [[++base_url]], [[++core_url]] and [[++assets_url]] placeholders inside this value.';
$_lang['image_baseurl_prepend_check_slash'] = 'Prepend URL If No Beginning /';
$_lang['image_baseurl_prepend_check_slash_desc'] = 'If true, MODX only will prepend the baseUrl if no forward slash (/) is found at the beginning of the URL when rendering the TV. Useful for setting a TV value outside the baseUrl.';
$_lang['image_baseurl_relative'] = 'Base Url Relative';
$_lang['image_baseurl_relative_desc'] = 'If the Base URL setting above is not relative to the MODX install URL, set this to Yes.';
$_lang['image_border_size'] = 'ボーダーサイズ';
$_lang['image_hspace'] = 'H 余白';
$_lang['image_vspace'] = 'V 余白';
$_lang['latest_date'] = 'Latest Date';
$_lang['latest_date_desc'] = 'The latest allowed date that can be selected.';
$_lang['latest_time'] = 'Latest Time';
$_lang['latest_time_desc'] = 'The latest allowed time that can be selected.';
$_lang['listbox'] = 'リストボックス (単一選択)';
$_lang['listbox-multiple'] = 'リストボックス (複数選択)';
$_lang['lower_case'] = '小文字';
$_lang['max_length'] = 'Max Length';
$_lang['min_length'] = 'Min Length';
$_lang['name'] = '名前';
$_lang['number'] = '番号';
$_lang['number_allowdecimals'] = 'Allow Decimals';
$_lang['number_allownegative'] = 'Allow Negatives';
$_lang['number_decimalprecision'] = 'Decimal Precision';
$_lang['number_decimalprecision_desc'] = 'The maximum precision to display after the decimal separator (defaults to 2).';
$_lang['number_decimalseparator'] = 'Decimal Separator';
$_lang['number_decimalseparator_desc'] = 'Character(s) to allow as the decimal separator (defaults to ".")';
$_lang['number_maxvalue'] = 'Max Value';
$_lang['number_minvalue'] = 'Min Value';
$_lang['option'] = 'ラジオボタン';
$_lang['parent_resources'] = 'Parent Resources';
$_lang['radio_columns'] = 'Columns';
$_lang['radio_columns_desc'] = 'The number of columns the radio boxes are displayed in.';
$_lang['rawtext'] = '変換無しテキスト (非推奨)';
$_lang['rawtextarea'] = '変換無し複数行テキスト (非推奨)';
$_lang['required'] = 'Allow Blank';
$_lang['required_desc'] = 'If set to No, MODX will not allow the user to save the Resource until a valid, non-blank value has been entered.';
$_lang['resourcelist'] = 'Resource List';
$_lang['resourcelist_depth'] = 'Depth';
$_lang['resourcelist_depth_desc'] = 'The levels deep that the query to grab the list of Resources will go. The default is 10 deep.';
$_lang['resourcelist_includeparent'] = 'Include Parents';
$_lang['resourcelist_includeparent_desc'] = 'If Yes, will include the Resources named in the Parents field in the list.';
$_lang['resourcelist_limit'] = 'Limit';
$_lang['resourcelist_limit_desc'] = 'The number of Resources to limit to in the list. 0 or empty means infinite.';
$_lang['resourcelist_parents'] = 'Parents';
$_lang['resourcelist_parents_desc'] = 'A list of IDs to grab children for the list.';
$_lang['resourcelist_where'] = 'Where Conditions';
$_lang['resourcelist_where_desc'] = 'A JSON object of where conditions to filter by in the query that grabs the list of Resources. (Does not support TV searching.)';
$_lang['richtext'] = 'リッチテキスト';
$_lang['sentence_case'] = '文頭を大文字';
$_lang['shownone'] = 'Allow Empty Choice';
$_lang['shownone_desc'] = 'Allow the user to select an empty choice which is a blank value.';
$_lang['start_day'] = 'Start Day';
$_lang['start_day_desc'] = 'Day index at which the week should begin, 0-based (defaults to 0, which is Sunday)';
$_lang['string'] = '文字列';
$_lang['string_format'] = '文字列の形式';
$_lang['style'] = 'スタイル';
$_lang['tag_id'] = 'タグID';
$_lang['tag_name'] = 'タグ名';
$_lang['target'] = 'ターゲット';
$_lang['text'] = 'テキスト';
$_lang['textarea'] = '複数行テキスト';
$_lang['textareamini'] = '複数テキスト (ミニ)';
$_lang['textbox'] = 'Textbox';
$_lang['time_increment'] = 'Time Increment';
$_lang['time_increment_desc'] = 'The number of minutes between each time value in the list (defaults to 15).';
$_lang['title'] = 'タイトル';
$_lang['upper_case'] = '大文字';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = '表示テキスト';
$_lang['width'] = '幅';
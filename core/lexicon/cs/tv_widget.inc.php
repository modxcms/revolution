<?php
/**
 * TV Widget Czech lexicon topic
 *
 * @language cs
 * @package modx
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2012-04-01
 */
// $_lang['attributes'] = 'Attributes';
$_lang['attributes'] = 'Atributy';

// $_lang['capitalize'] = 'Capitalize';
$_lang['capitalize'] = 'Kapitálkami';

// $_lang['checkbox'] = 'Check Box';
$_lang['checkbox'] = 'Zaškrtávací políčko';

// $_lang['class'] = 'Class';
$_lang['class'] = 'Třída';

// $_lang['combo_allowaddnewdata'] = 'Allow Add New Items';
$_lang['combo_allowaddnewdata'] = 'Povolit přidání nových položek';

// $_lang['combo_allowaddnewdata_desc'] = 'When Yes, allows items to be added that do not already exist in the list. Defaults to No.';
$_lang['combo_allowaddnewdata_desc'] = 'Pokud Ano, pak lze přidat nové položky, které dosud nejsou v seznamu. Výchozí nastavení je Ne.';

// $_lang['combo_forceselection'] = 'Force Selection to List';
$_lang['combo_forceselection'] = 'Pouze položky ze seznamu';

// $_lang['combo_forceselection_desc'] = 'If using Type-Ahead, if this is set to Yes, only allow inputting of items in the list.';
$_lang['combo_forceselection_desc'] = 'Je-li aktivní našeptávač a tato volba nastavena na Ano, pak lze vybrat pouze položku ze seznamu.';

// $_lang['combo_listempty_text'] = 'Empty List Text';
$_lang['combo_listempty_text'] = 'Text pokud není nápověda';

// $_lang['combo_listempty_text_desc'] = 'If Type-Ahead is on, and the user types a value not in the list, display this text.';
$_lang['combo_listempty_text_desc'] = 'Je-li aktivní našeptávač a uživatel zadá hodnotu, která není v seznamu zobrazí se mu tento text.';

// $_lang['combo_listwidth'] = 'List Width';
$_lang['combo_listwidth'] = 'Šířka seznamu';

// $_lang['combo_listwidth_desc'] = 'The width, in pixels, of the dropdown list itself. Defaults to the width of the combobox.';
$_lang['combo_listwidth_desc'] = 'Šířka seznamu v pixelech. Výchozí hodnoutou je šířka seznamu v závislosti na délce nejdelší položky.';

// $_lang['combo_maxheight'] = 'Max Height';
$_lang['combo_maxheight'] = 'Maximální výška seznamu';

// $_lang['combo_maxheight_desc'] = 'The maximum height in pixels of the dropdown list before scrollbars are shown (defaults to 300).';
$_lang['combo_maxheight_desc'] = 'Maximální výška seznamu v pixelech než jsou zobrazeny posuvníky. Výchozí hodnota je 300.';

// $_lang['combo_stackitems'] = 'Stack Selected Items';
$_lang['combo_stackitems'] = 'Vybrané položky pod sebou';

// $_lang['combo_stackitems_desc'] = 'When set to Yes, the items will be stacked 1 per line. Defaults to No which displays the items inline.';
$_lang['combo_stackitems_desc'] = 'Je-li tato volba nastavena na Ano, pak budou vybrané položky zobrazeny každá na jednom řádku pod sebou. Výchozí hodnota je Ne, pak jsou položky zobrazeny za sebou.';

// $_lang['combo_title'] = 'List Header';
$_lang['combo_title'] = 'Hlavička seznamu';

// $_lang['combo_title_desc'] = 'If supplied, a header element is created containing this text and added into the top of the dropdown list.';
$_lang['combo_title_desc'] = 'Je-li vyplněna, bude na začátku seznamu zobrazen tento text.';

// $_lang['combo_typeahead'] = 'Enable Type-Ahead';
$_lang['combo_typeahead'] = 'Povolit našeptávač';

// $_lang['combo_typeahead_desc'] = 'If yes, populate and autoselect the remainder of the text being typed after a configurable delay (Type Ahead Delay) if it matches a known value (defaults to off.).';
$_lang['combo_typeahead_desc'] = 'Je-li povolen, dochází automaticky pouze k zobrazení položek odpovídajících textu zadaného uživatelem v rámci nastavené prodlevy (Prodleva našeptávače). Výchozí hodnota je vypnuto.';

// $_lang['combo_typeahead_delay'] = 'Type-Ahead Delay';
$_lang['combo_typeahead_delay'] = 'Prodleva našeptávače';

// $_lang['combo_typeahead_delay_desc'] = 'The length of time in milliseconds to wait until the Type-Ahead text is displayed if Type-Ahead is enabled (defaults to 250).';
$_lang['combo_typeahead_delay_desc'] = 'Čas v milisekundách, po které je zobrazen výsledek našeptávače, pokud je našeptávač aktivní. Výchozí hodnota je 250.';

// $_lang['date'] = 'Date';
$_lang['date'] = 'Datum';

// $_lang['date_format'] = 'Date Format';
$_lang['date_format'] = 'Formát data';

// $_lang['date_use_current'] = 'If no value, use current date';
$_lang['date_use_current'] = 'Pokud není definováno použij aktuální datum';

// $_lang['default'] = 'Default';
$_lang['default'] = 'Výchozí';

// $_lang['delim'] = 'Delimiter';
$_lang['delim'] = 'Odděl.';

// $_lang['delimiter'] = 'Delimiter';
$_lang['delimiter'] = 'Oddělovač';

// $_lang['disabled_dates'] = 'Disabled Dates';
$_lang['disabled_dates'] = 'Zakázané datumy';


// $_lang['disabled_dates_desc'] = 'A comma-separated list of "dates" to disable, as strings. These strings will be // used to build a dynamic regular expression so they are very powerful. Some examples:<br />
// - Disable these exact dates: 2003-03-08,2003-09-16<br />
// - Disable these days for every year: 03-08,09-16<br />
// - Only match the beginning (useful if you are using short years): ^03-08<br />
// - Disable every day in March 2006: 03-..-2006<br />
// - Disable every day in every March: ^03<br />
// Note that the format of the dates included in the list should exactly match the format config. In order to support // regular expressions, if you are using a date format that has "." in it, you will have to escape the dot when // // // restricting dates.';
$_lang['disabled_dates_desc'] = 'Čárkou oddělený seznam datumů, které mají být zakázány. Tyto řetězce se použijí k vygenerování regulárního výrazu. Například:<br />
- Přímo zakázané dny v jednom roce: 2003-03-08,2003-09-16<br />
- Zakázané dny každý rok: 03-08,09-16<br />
- Kontrolovat pouze začátky (Užitečné pokud používáte krátký zápis roku): ^03-08<br />
- Zakázání každého dne v březnu 2006: 03-..-2006<br />
- Zakázání všech dní v březnu každý rok: ^03<br />
Pozor na to, že formát zadávaných dat musí korespondovat s formátem nastaveným v konfiguraci systému. Pokud obsahuje požadovaný formát data tečku ".", je nutné ji vždy eskejpovat "\.", jinak by došlo k problémům při vyhodnocování regulárního výrazu.';

// $_lang['disabled_days'] = 'Disabled Days';
$_lang['disabled_days'] = 'Zakázané dny';

// $_lang['disabled_days_desc'] = 'A comma-separated list of days to disable, 0 based (defaults to null). Some examples:<br />
// - Disable Sunday and Saturday: 0,6<br />
// - Disable weekdays: 1,2,3,4,5';
$_lang['disabled_days_desc'] = 'Čárkou oddělený seznam dnů, které mají být zakázané, počítáno od 0 (výchozí hodnota je prázdné pole). Například:<br />
- Zakázaná neděle a sobota: 0,6<br />
- Zakázané pracovní dny: 1,2,3,4,5';

// $_lang['dropdown'] = 'DropDown List Menu';
$_lang['dropdown'] = 'Rozbalovací seznam';

// $_lang['earliest_date'] = 'Earliest Date';
$_lang['earliest_date'] = 'Nejstarší možné datum';

// $_lang['earliest_date_desc'] = 'The earliest allowed date that can be selected.';
$_lang['earliest_date_desc'] = 'Nejstarší datum, který může být zvoleno.';

// $_lang['earliest_time'] = 'Earliest Time';
$_lang['earliest_time'] = 'Nejstarší možný čas';

// $_lang['earliest_time_desc'] = 'The earliest allowed time that can be selected.';
$_lang['earliest_time_desc'] = 'Nejstarší čas, který může být zvolen.';

// $_lang['email'] = 'Email';
$_lang['email'] = 'E-mail';

// $_lang['file'] = 'File';
$_lang['file'] = 'Soubor';

// $_lang['height'] = 'Height';
$_lang['height'] = 'Výška';

// $_lang['hidden'] = 'Hidden';
$_lang['hidden'] = 'Skryté';

// $_lang['htmlarea'] = 'HTML Area';
$_lang['htmlarea'] = 'HTML Area';

// $_lang['htmltag'] = 'HTML Tag';
$_lang['htmltag'] = 'HTML tag';

// $_lang['image'] = 'Image';
$_lang['image'] = 'Obrázek';

// $_lang['image_align'] = 'Align';
$_lang['image_align'] = 'Zarovnat';

// $_lang['image_align_list'] = 'none,baseline,top,middle,bottom,texttop,absmiddle,absbottom,left,right';
$_lang['image_align_list'] = 'none,baseline,top,middle,bottom,texttop,absmiddle,absbottom,left,right';

// $_lang['image_alt'] = 'Alternate Text';
$_lang['image_alt'] = 'Alternativní text';

// $_lang['image_border_size'] = 'Border Size';
$_lang['image_border_size'] = 'Šířka ohraničení';

// $_lang['image_hspace'] = 'H Space';
$_lang['image_hspace'] = 'H mezera';

// $_lang['image_vspace'] = 'V Space';
$_lang['image_vspace'] = 'V mezera';

// $_lang['latest_date'] = 'Latest Date';
$_lang['latest_date'] = 'Nejnovější možné datum';

// $_lang['latest_date_desc'] = 'The latest allowed date that can be selected.';
$_lang['latest_date_desc'] = 'Nejnovější datum, který může být zvoleno.';

// $_lang['latest_time'] = 'Latest Time';
$_lang['latest_time'] = 'Nejnovější možný čas';

// $_lang['latest_time_desc'] = 'The latest allowed time that can be selected.';
$_lang['latest_time_desc'] = 'Nejnovější čas, který může být zvolen..';

// $_lang['listbox'] = 'Listbox (Single-Select)';
$_lang['listbox'] = 'Seznam (jeden vybraný)';

// $_lang['listbox-multiple'] = 'Listbox (Multi-Select)';
$_lang['listbox-multiple'] = 'Seznam (více vybraných)';

// $_lang['lower_case'] = 'Lower Case';
$_lang['lower_case'] = 'Malá písmena';

// $_lang['max_length'] = 'Max Length';
$_lang['max_length'] = 'Max. délka';

// $_lang['min_length'] = 'Min Length';
$_lang['min_length'] = 'Min. délka';

// $_lang['name'] = 'Name';
$_lang['name'] = 'Název';

// $_lang['number'] = 'Number';
$_lang['number'] = 'Číslo';

// $_lang['number_allowdecimals'] = 'Allow Decimals';
$_lang['number_allowdecimals'] = 'Povolit desetinná čísla';

// $_lang['number_allownegative'] = 'Allow Negatives';
$_lang['number_allownegative'] = 'Povolit zápornou hodnotu';

// $_lang['number_decimalprecision'] = 'Decimal Precision';
$_lang['number_decimalprecision'] = 'Počet desetinných míst';

// $_lang['number_decimalprecision_desc'] = 'The maximum precision to display after the decimal separator (defaults to 2).';
$_lang['number_decimalprecision_desc'] = 'Počet desetinných míst, které chcete zobrazit (výchozí hodnota je 2).';

// $_lang['number_decimalseparator'] = 'Decimal Separator';
$_lang['number_decimalseparator'] = 'Oddělovač desetinných míst';

// $_lang['number_decimalseparator_desc'] = 'Character(s) to allow as the decimal separator (defaults to ".")';
$_lang['number_decimalseparator_desc'] = 'Znak(y), který chcete použít pro oddělení desetinné hodnoty (výchozí hodnota je ".")';

// $_lang['number_maxvalue'] = 'Max Value';
$_lang['number_maxvalue'] = 'Max. hodnota';

// $_lang['number_minvalue'] = 'Min Value';
$_lang['number_minvalue'] = 'Min. hodnota';

// $_lang['option'] = 'Radio Options';
$_lang['option'] = 'Přepínače';

// $_lang['parent_resources'] = 'Parent Resources';
$_lang['parent_resources'] = 'Nadřazený dokument';

// $_lang['radio_columns'] = 'Columns';
$_lang['radio_columns'] = 'Sloupce';

// $_lang['radio_columns_desc'] = 'The number of columns the radio boxes are displayed in.';
$_lang['radio_columns_desc'] = 'Počet sloupců, vce kterých jsou zobrazeny přepínače.';

// $_lang['rawtext'] = 'Raw Text (deprecated)';
$_lang['rawtext'] = 'Surový text (zastaralé)';

// $_lang['rawtextarea'] = 'Raw Textarea (deprecated)';
$_lang['rawtextarea'] = 'Surové textové pole (zastaralé)';

// $_lang['required'] = 'Allow Blank';
$_lang['required'] = 'Povolit prázdné';

// $_lang['required_desc'] = 'If set to No, MODX will not allow the user to save the Resource until a valid, non-blank value has been entered.';
$_lang['required_desc'] = 'Je-li nastaveno na Ne, MODX nedovolí uživateli uložit dokument dokud nezadá platnou nenulovou hodnotu pro tuto template variable.';

// $_lang['resourcelist'] = 'Resource List';
$_lang['resourcelist'] = 'Seznam dokumentů';

// $_lang['resourcelist_depth'] = 'Depth';
$_lang['resourcelist_depth'] = 'Hloubka';

// $_lang['resourcelist_depth_desc'] = 'The levels deep that the query to grab the list of Resources will go. The default is 10 deep.';
$_lang['resourcelist_depth_desc'] = 'Počet úrovní, které mají být zobrazeny v seznamu dokumentů. Výchozí hodnota je 10.';

// $_lang['resourcelist_includeparent'] = 'Include Parents';
$_lang['resourcelist_includeparent'] = 'Zobrazit rodiče';

// $_lang['resourcelist_includeparent_desc'] = 'If Yes, will include the Resources named in the Parents field in the list.';
$_lang['resourcelist_includeparent_desc'] = 'Je-li nastaveno na Ano, budou v seznamu zobrazeny také dokumenty uvedené v nastavení Rodiče.';

// $_lang['resourcelist_limitrelatedcontext'] = 'Limit to Related Context';
$_lang['resourcelist_limitrelatedcontext'] = 'Omezit na související kontexty';

// $_lang['resourcelist_limitrelatedcontext_desc'] = 'If Yes, will only include the Resources related to the context of the current Resource.';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Je-li nastaveno Ano, budou zahrnuty pouze dokumenty související s kontextem aktuálního dokumentu.';

// $_lang['resourcelist_limit'] = 'Limit';
$_lang['resourcelist_limit'] = 'Limit';

// $_lang['resourcelist_limit_desc'] = 'The number of Resources to limit to in the list. 0 or empty means infinite.';
$_lang['resourcelist_limit_desc'] = 'Počet dokumentů, který má být maximálně zobrazen. 0 nebo prázdné pole znamená neomezeně.';

// $_lang['resourcelist_parents'] = 'Parents';
$_lang['resourcelist_parents'] = 'Rodiče';

// $_lang['resourcelist_parents_desc'] = 'A list of IDs to grab children for the list.';
$_lang['resourcelist_parents_desc'] = 'Seznam ID dokumentů, ze kterých chcete v seznamu zobrazit dokumenty.';

// $_lang['resourcelist_where'] = 'Where Conditions';
$_lang['resourcelist_where'] = 'Podmínka WHERE';

// $_lang['resourcelist_where_desc'] = 'A JSON object of where conditions to filter by in the query that grabs the list of Resources. (Does not support TV searching.)';
$_lang['resourcelist_where_desc'] = 'JSON objekt podmínky WHERE pro vyfiltrování záznamů pro seznam dokumentů. (Nepodporuje prohledávání TV.)';

// $_lang['richtext'] = 'RichText';
$_lang['richtext'] = 'WYSIWYG';

// $_lang['sentence_case'] = 'Sentence Case';
$_lang['sentence_case'] = 'Věta';

// $_lang['shownone'] = 'Allow Empty Choice';
$_lang['shownone'] = 'Povolit prázdnou položku';

// $_lang['shownone_desc'] = 'Allow the user to select an empty choice which is a blank value.';
$_lang['shownone_desc'] = 'Umožní uživateli vybrat prázdnou položku jejíž hodnota je také prázdná.';

// $_lang['start_day'] = 'Start Day';
$_lang['start_day'] = 'První den týdne';

// $_lang['start_day_desc'] = 'Day index at which the week should begin, 0-based (defaults to 0, which is Sunday)';
$_lang['start_day_desc'] = 'Index dne, kterým chcete, aby začínal týden. Počítáno od nuly, 0 = neděle, 1 = pondělí.';

// $_lang['string'] = 'String';
$_lang['string'] = 'Řetězec';

// $_lang['string_format'] = 'String Format';
$_lang['string_format'] = 'Formát řetězce';

// $_lang['style'] = 'Style';
$_lang['style'] = 'Styl';

// $_lang['tag_id'] = 'Tag ID';
$_lang['tag_id'] = 'ID tagu';

// $_lang['tag_name'] = 'Tag Name';
$_lang['tag_name'] = 'Název tagu';

// $_lang['target'] = 'Target';
$_lang['target'] = 'Cíl';

// $_lang['text'] = 'Text';
$_lang['text'] = 'Text';

// $_lang['textarea'] = 'Textarea';
$_lang['textarea'] = 'Textové pole';

// $_lang['textareamini'] = 'Textarea (Mini)';
$_lang['textareamini'] = 'Textové pole (malé)';

// $_lang['textbox'] = 'Textbox';
$_lang['textbox'] = 'Textbox';

// $_lang['time_increment'] = 'Time Increment';
$_lang['time_increment'] = 'Přírustek času';

// $_lang['time_increment_desc'] = 'The number of minutes between each time value in the list (defaults to 15).';
$_lang['time_increment_desc'] = 'Počet minut mezi jednotlivými časovými údaji v seznamu (výchozí hodnota je 15).';

// $_lang['title'] = 'Title';
$_lang['title'] = 'Název';

// $_lang['upper_case'] = 'Upper Case';
$_lang['upper_case'] = 'Velká písmena';

// $_lang['url'] = 'URL';
$_lang['url'] = 'URL';

// $_lang['url_display_text'] = 'Display Text';
$_lang['url_display_text'] = 'Zobrazený text';

// $_lang['width'] = 'Width';
$_lang['width'] = 'Šířka';

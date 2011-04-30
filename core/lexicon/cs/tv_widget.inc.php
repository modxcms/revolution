<?php
/**
 * TV Widget Czech lexicon topic
 *
 * @language cs
 * @package modx
 * @subpackage lexicon
 *
 * @author modxcms.cz
 * @updated 2011-04-04
 */
$_lang['attributes'] = 'Atributy';
$_lang['capitalize'] = 'Kapitálkami';
$_lang['checkbox'] = 'Zaškrtávací políčko';
$_lang['class'] = 'Třída';
$_lang['combo_allowaddnewdata'] = 'Povolit přidání nových položek';
$_lang['combo_allowaddnewdata_desc'] = 'Pokud Ano, pak lze přidat nové položky, které dosud nejsou v seznamu. Výchozí nastavení je Ne.';
$_lang['combo_forceselection'] = 'Pouze položky ze seznamu';
$_lang['combo_forceselection_desc'] = 'Je-li aktivní našeptávač a tato volba nastavena na Ano, pak lze vybrat pouze položku ze seznamu.';
$_lang['combo_listempty_text'] = 'Text pokud není nápověda';
$_lang['combo_listempty_text_desc'] = 'Je-li aktivní našeptávač a uživatel zadá hodnotu, která není v seznamu zobrazí se mu tento text.';
$_lang['combo_listwidth'] = 'Šířka seznamu';
$_lang['combo_listwidth_desc'] = 'Šířka seznamu v pixelech. Výchozí hodnoutou je šířka seznamu v závislosti na délce nejdelší položky.';
$_lang['combo_maxheight'] = 'Maximální výška seznamu';
$_lang['combo_maxheight_desc'] = 'Maximální výška seznamu v pixelech než jsou zobrazeny posuvníky. Výchozí hodnota je 300.';
$_lang['combo_stackitems'] = 'Vybrané položky pod sebou';
$_lang['combo_stackitems_desc'] = 'Je-li tato volba nastavena na Ano, pak budou vybrané položky zobrazeny každá na jednom řádku pod sebou. Výchozí hodnota je Ne, pak jsou položky zobrazeny za sebou.';
$_lang['combo_title'] = 'Hlavička seznamu';
$_lang['combo_title_desc'] = 'Je-li vyplněna, bude na začátku seznamu zobrazen tento text.';
$_lang['combo_typeahead'] = 'Povolit našeptávač';
$_lang['combo_typeahead_desc'] = 'Je-li povolen, dochází automaticky pouze k zobrazení položek odpovídajících textu zadaného uživatelem v rámci nastavené prodlevy (Prodleva našeptávače). Výchozí hodnota je vypnuto.';
$_lang['combo_typeahead_delay'] = 'Prodleva našeptávače';
$_lang['combo_typeahead_delay_desc'] = 'Čas v milisekundách, po které je zobrazen výsledek našeptávače, pokud je našeptávač aktivní. Výchozí hodnota je 250.';
$_lang['date'] = 'Datum';
$_lang['date_format'] = 'Formát data';
$_lang['date_use_current'] = 'Pokud není definováno použij aktuální datum';
$_lang['default'] = 'Výchozí';
$_lang['delim'] = 'Odděl.';
$_lang['delimiter'] = 'Oddělovač';
$_lang['disabled_dates'] = 'Zakázané datumy';
$_lang['disabled_dates_desc'] = 'Čárkou oddělený seznam datumů, které mají být zakázány. Tyto řetězce se použijí k vygenerování regulárního výrazu. Například:<br />
- Přímo zakázané dny v jednom roce: 2003-03-08,2003-09-16<br />
- Zakázané dny každý rok: 03-08,09-16<br />
- Kontrolovat pouze začátky (Užitečné pokud používáte krátký zápis roku): ^03-08<br />
- Zakázání každého dne v březnu 2006: 03-..-2006<br />
- Zakázání všech dní v březnu každý rok: ^03<br />
Pozor na to, že formát zadávaných dat musí korespondovat s formátem nastaveným v konfiguraci systému. Pokud obsahuje požadovaný formát data tečku ".", je nutné ji vždy eskejpovat "\.", jinak by došlo k problémům při vyhodnocování regulárního výrazu.';
$_lang['disabled_days'] = 'Zakázané dny';
$_lang['disabled_days_desc'] = 'Čárkou oddělený seznam dnů, které mají být zakázané, počítáno od 0 (výchozí hodnota je prázdné pole). Například:<br />
- Zakázaná neděle a sobota: 0,6<br />
- Zakázané pracovní dny: 1,2,3,4,5';
$_lang['dropdown'] = 'Rozbalovací seznam';
$_lang['earliest_date'] = 'Nejstarší možné datum';
$_lang['earliest_date_desc'] = 'Nejstarší datum, který může být zvoleno.';
$_lang['earliest_time'] = 'Nejstarší možný čas';
$_lang['earliest_time_desc'] = 'Nejstarší čas, který může být zvolen.';
$_lang['email'] = 'E-mail';
$_lang['file'] = 'Soubor';
$_lang['height'] = 'Výška';
$_lang['hidden'] = 'Skryté';
$_lang['htmlarea'] = 'HTML Area';
$_lang['htmltag'] = 'HTML tag';
$_lang['image'] = 'Obrázek';
$_lang['image_align'] = 'Zarovnat';
$_lang['image_align_list'] = 'none,baseline,top,middle,bottom,texttop,absmiddle,absbottom,left,right';
$_lang['image_alt'] = 'Alternativní text';
$_lang['image_allowedfiletypes'] = 'Povolené přípony souborů';
$_lang['image_allowedfiletypes_desc'] = 'Jsou-li nějaké přípony uvedeny, budou zobrazované soubory omezeny na tyto přípony. Přípony zadávejte jako čárkou oddělený seznam, bez tečky. Pro soubory JPG a GIF tedy: "jpg,gif".';
$_lang['image_basepath'] = 'Cesta k souborům';
$_lang['image_basepath_desc'] = 'Cesta k souborům, kam má být template variable ukládána. Není-li nastavena, použije se nastavení "filemanager_path" z konfigurace systému nebo MODX base path. V rámci této hodnoty lze používat placeholdery [[++base_path]], [[++core_path]] a [[++assets_path]].';
$_lang['image_baseurl_prepend_check_slash'] = 'Přidat před URL / pokud jím již nezačíná';
$_lang['image_baseurl_prepend_check_slash_desc'] = 'Je-li aktivní, MODX přidá na začátek baseUrl pro vypisovanou TV "/" pokud jím již nezačíná. Užitečné pro nastavení hodnoty TV mimo baseUrl.';
$_lang['image_basepath_relative'] = 'Relativní cesta k souborům';
$_lang['image_basepath_relative_desc'] = 'Není-li cesta ve výše uvedém nastavení "Cesta k souborům" uvedeno relativně k instalaci MODX, nastavte toto na Ano.';
$_lang['image_baseurl'] = 'Základní URL';
$_lang['image_baseurl_desc'] = 'URL k souborům template variable. Není-li nastavena, použije se nastavení "filemanager_url" z konfigurace systému nebo MODX base URL. V rámci této hodnoty lze používat placeholdery [[++base_url]], [[++core_url]] a [[++assets_url]].';
$_lang['image_baseurl_relative'] = 'Relativní základní URL';
$_lang['image_baseurl_relative_desc'] = 'Není-li URL ve výše uvedém nastavení "Základní URL" uvedeno relativně k URL instalace MODX, nastavte toto na Ano.';
$_lang['image_border_size'] = 'Šířka ohraničení';
$_lang['image_hspace'] = 'H mezera';
$_lang['image_vspace'] = 'V mezera';
$_lang['latest_date'] = 'Nejnovější možné datum';
$_lang['latest_date_desc'] = 'Nejnovější datum, který může být zvoleno.';
$_lang['latest_time'] = 'Nejnovější možný čas';
$_lang['latest_time_desc'] = 'Nejnovější čas, který může být zvolen..';
$_lang['listbox'] = 'Seznam (jeden vybraný)';
$_lang['listbox-multiple'] = 'Seznam (více vybraných)';
$_lang['lower_case'] = 'Malá písmena';
$_lang['max_length'] = 'Max. délka';
$_lang['min_length'] = 'Min. délka';
$_lang['name'] = 'Název';
$_lang['number'] = 'Číslo';
$_lang['number_allowdecimals'] = 'Povolit desetinná čísla';
$_lang['number_allownegative'] = 'Povolit zápornou hodnotu';
$_lang['number_decimalprecision'] = 'Počet desetinných míst';
$_lang['number_decimalprecision_desc'] = 'Počet desetinných míst, které chcete zobrazit (výchozí hodnota je 2).';
$_lang['number_decimalseparator'] = 'Oddělovač desetinných míst';
$_lang['number_decimalseparator_desc'] = 'Znak(y), který chcete použít pro oddělení desetinné hodnoty (výchozí hodnota je ".")';
$_lang['number_maxvalue'] = 'Max. hodnota';
$_lang['number_minvalue'] = 'Min. hodnota';
$_lang['option'] = 'Přepínače';
$_lang['parent_resources'] = 'Nadřazený dokument';
$_lang['radio_columns'] = 'Sloupce';
$_lang['radio_columns_desc'] = 'Počet sloupců, vce kterých jsou zobrazeny přepínače.';
$_lang['rawtext'] = 'Surový text (zastaralé)';
$_lang['rawtextarea'] = 'Surové textové pole (zastaralé)';
$_lang['required'] = 'Povolit prázdné';
$_lang['required_desc'] = 'Je-li nastaveno na Ne, MODX nedovolí uživateli uložit dokument dokud nezadá platnou nenulovou hodnotu pro tuto template variable.';
$_lang['resourcelist'] = 'Seznam dokumentů';
$_lang['resourcelist_depth'] = 'Hloubka';
$_lang['resourcelist_depth_desc'] = 'Počet úrovní, které mají být zobrazeny v seznamu dokumentů. Výchozí hodnota je 10.';
$_lang['resourcelist_includeparent'] = 'Zobrazit rodiče';
$_lang['resourcelist_includeparent_desc'] = 'Je-li nastaveno na Ano, budou v seznamu zobrazeny také dokumenty uvedené v nastavení Rodiče.';
$_lang['resourcelist_limit'] = 'Limit';
$_lang['resourcelist_limit_desc'] = 'Počet dokumentů, který má být maximálně zobrazen. 0 nebo prázdné pole znamená neomezeně.';
$_lang['resourcelist_parents'] = 'Rodiče';
$_lang['resourcelist_parents_desc'] = 'Seznam ID dokumentů, ze kterých chcete v seznamu zobrazit dokumenty.';
$_lang['resourcelist_where'] = 'Podmínka WHERE';
$_lang['resourcelist_where_desc'] = 'JSON objekt podmínky WHERE pro vyfiltrování záznamů pro seznam dokumentů. (Nepodporuje prohledávání TV.)';
$_lang['richtext'] = 'WYSIWYG';
$_lang['sentence_case'] = 'Věta';
$_lang['shownone'] = 'Povolit prázdnou položku';
$_lang['shownone_desc'] = 'Umožní uživateli vybrat prázdnou položku jejíž hodnota je také prázdná.';
$_lang['start_day'] = 'První den týdne';
$_lang['start_day_desc'] = 'Index dne, kterým chcete, aby začínal týden. Počítáno od nuly, 0 = neděle, 1 = pondělí.';
$_lang['string'] = 'Řetězec';
$_lang['string_format'] = 'Formát řetězce';
$_lang['style'] = 'Styl';
$_lang['tag_id'] = 'ID tagu';
$_lang['tag_name'] = 'Název tagu';
$_lang['target'] = 'Cíl';
$_lang['text'] = 'Text';
$_lang['textarea'] = 'Textové pole';
$_lang['textareamini'] = 'Textové pole (malé)';
$_lang['textbox'] = 'Textbox';
$_lang['time_increment'] = 'Přírustek času';
$_lang['time_increment_desc'] = 'Počet minut mezi jednotlivými časovými údaji v seznamu (výchozí hodnota je 15).';
$_lang['title'] = 'Název';
$_lang['upper_case'] = 'Velká písmena';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Zobrazený text';
$_lang['width'] = 'Šířka';
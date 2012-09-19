<?php
/**
 * TV Widget Swedish lexicon topic
 *
 * @language sv
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Attribut';
$_lang['capitalize'] = 'Kapitalisera';
$_lang['checkbox'] = 'Kryssruta';
$_lang['class'] = 'Klass';
$_lang['combo_allowaddnewdata'] = 'Tillåt nya poster';
$_lang['combo_allowaddnewdata_desc'] = 'Om denna sätts till "Ja" kan nya poster, som inte redan finns i listan, läggas till. Standard är "Nej".';
$_lang['combo_forceselection'] = 'Tvinga urval till lista';
$_lang['combo_forceselection_desc'] = 'Om ifyllning är aktiverad och om denna sätts till "Ja" kommer bara värden som finns i listan att tillåtas.';
$_lang['combo_listempty_text'] = 'Text vid tom lista';
$_lang['combo_listempty_text_desc'] = 'Om ifyllning är aktiverad och användaren skriver in något som inte finns i listan visas denna text.';
$_lang['combo_listwidth'] = 'Listbredd';
$_lang['combo_listwidth_desc'] = 'Bredden (i pixlar) på själva rullgardinsmenyn. Standard är bredden på comboboxen.';
$_lang['combo_maxheight'] = 'Maximal höjd';
$_lang['combo_maxheight_desc'] = 'Den maximala höjden (i pixlar) på rullgardinsmenyn innan rullningslister visas. Standard är 300.';
$_lang['combo_stackitems'] = 'Stapla markerade poster';
$_lang['combo_stackitems_desc'] = 'Om denna sätts till "Ja" kommer posterna att staplas en per rad. Standard är "Nej", vilket betyder att de visas på samma rad.';
$_lang['combo_title'] = 'Listrubrik';
$_lang['combo_title_desc'] = 'Om en rubrik anges här kommer ett rubrikelement med denna rubrik att skapas och läggas till i toppen på rullgardinsmenyn.';
$_lang['combo_typeahead'] = 'Aktivera ifyllning';
$_lang['combo_typeahead_desc'] = 'Om denna sätts till "Ja" kommer den text som skrivs in att kompletteras och automatiskt markeras efter en konfigurerbar fördröjning om den matchar ett känt värde. Standard är "Nej".';
$_lang['combo_typeahead_delay'] = 'Ifyllningsfördröjning';
$_lang['combo_typeahead_delay_desc'] = 'Den tid (i millisekunder) som ska passera innan den ifyllda texten kompletteras när ifyllning är aktiverad. Standard är 250.';
$_lang['date'] = 'Datum';
$_lang['date_format'] = 'Datumformat';
$_lang['date_use_current'] = 'Använd aktuellt datum om inget värde angivits';
$_lang['default'] = 'Standard';
$_lang['delim'] = 'Avgränsare';
$_lang['delimiter'] = 'Avgränsare';
$_lang['disabled_dates'] = 'Inaktiverade datum';
$_lang['disabled_dates_desc'] = 'En kommaseparerad lista med "datum" som ska inaktiveras som strängar. Dessa strängar kommer att användas för att dynamiskt bygga ett reguljärt uttryck, så de är mycket kraftfulla. Några exempel:<br />
- Inaktivera dessa exakta datum: 2012-03-08,2012-09-16<br />
- Inaktivera dessa datum varje år: 03-08,09-16<br />
- Matcha bara början (användbart om du använder korta årtal): ^10-12<br />
- Inaktivera varje dag i mars 2012: 2012-03-..<br />
- Inaktivera varje dag i mars alla år: ^03<br />
Notera att formatet på de datum som läggs till i listan exakt måste matcha hanterarens datumformat. För att stödja reguljära uttryck måste du, om du använder ett datumformat som innehåller punkter (.), undanta punkterna med ett omvänt snedstreck när du begränsar datum.';
$_lang['disabled_days'] = 'Inaktiverade dagar';
$_lang['disabled_days_desc'] = 'En kommaseparerad lista med dagar som ska inaktiveras. 0-baserad, standard är null. Några exempel:<br />
- Inaktivera söndagar och lördagar: 0,6<br />
- Inaktivera veckodagar: 1,2,3,4,5';
$_lang['dropdown'] = 'Rullgardinsmeny';
$_lang['earliest_date'] = 'Tidigaste datum';
$_lang['earliest_date_desc'] = 'Det tidigaste datumet som kan väljas.';
$_lang['earliest_time'] = 'Tidigaste klockslag';
$_lang['earliest_time_desc'] = 'Det tidigaste klockslag som kan väljas.';
$_lang['email'] = 'E-post';
$_lang['file'] = 'Fil';
$_lang['height'] = 'Höjd';
$_lang['hidden'] = 'Dold';
$_lang['htmlarea'] = 'HTML-area';
$_lang['htmltag'] = 'HTML-tagg';
$_lang['image_align'] = 'Justera';
$_lang['image'] = 'Bild';
$_lang['image_align_list'] = 'ingen,baslinje,toppen,mitten,botten,toppen av texten,absoluta mitten,absoluta botten,vänster,höger';
$_lang['image_alt'] = 'Alternativtext';
$_lang['image_border_size'] = 'Ramstorlek';
$_lang['image_hspace'] = 'Horisontalrymd';
$_lang['image_vspace'] = 'Vertikalrymd';
$_lang['latest_date'] = 'Senaste datum';
$_lang['latest_date_desc'] = 'Det senaste datum som kan väljas.';
$_lang['latest_time'] = 'Senaste klockslag';
$_lang['latest_time_desc'] = 'Det senaste klockslag som kan väljas.';
$_lang['listbox'] = 'Listbox (enkelval)';
$_lang['listbox-multiple'] = 'Listbox (flerval)';
$_lang['lower_case'] = 'Gemener';
$_lang['max_length'] = 'Maximal längd';
$_lang['min_length'] = 'Minimal längd';
$_lang['name'] = 'Namn';
$_lang['number'] = 'Nummer';
$_lang['number_allowdecimals'] = 'Tillåt decimaler';
$_lang['number_allownegative'] = 'Tillåt negativa tal';
$_lang['number_decimalprecision'] = 'Antal decimaler';
$_lang['number_decimalprecision_desc'] = 'Det maximala antalet decimaler som visas efter decimalavgränsaren. Standard är 2.';
$_lang['number_decimalseparator'] = 'Decimalavgränsare';
$_lang['number_decimalseparator_desc'] = 'Godkända tecken för decimalavgränsare. Standard är ".".';
$_lang['number_maxvalue'] = 'Maximalt värde';
$_lang['number_minvalue'] = 'Minimalt värde';
$_lang['option'] = 'Radioval';
$_lang['parent_resources'] = 'Föräldraresurser';
$_lang['radio_columns'] = 'Kolumner';
$_lang['radio_columns_desc'] = 'Det antal kolumner som radioval visas i.';
$_lang['rawtext'] = 'Rå text (föråldrad)';
$_lang['rawtextarea'] = 'Rå textarea (föråldrad)';
$_lang['required'] = 'Tillåt tom';
$_lang['required_desc'] = 'Om denna sätts till "Nej" kommer MODX inte att tillåta användaren att spara en resurs förrän ett giltigt, icke-tomt värde har matats in.';
$_lang['resourcelist'] = 'Resurslista';
$_lang['resourcelist_depth'] = 'Djup';
$_lang['resourcelist_depth_desc'] = 'Anger hur många nivåer neråt som kommer att sökas i databasen när listan med resurser ska hämtas. Standard är 10.';
$_lang['resourcelist_includeparent'] = 'Inkludera föräldrar';
$_lang['resourcelist_includeparent_desc'] = 'Om denna sätts till "Ja" kommer resurserna som anges i föräldrafältet att inkluderas i listan.';
$_lang['resourcelist_limitrelatedcontext'] = 'Begränsa till relaterad kontext';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Om denna sätts till "Ja" kommer bara resurser som är relaterade till den nuvarande resursens kontext att inkluderas.';
$_lang['resourcelist_limit'] = 'Begränsning';
$_lang['resourcelist_limit_desc'] = 'Det antal resurser som listan kommer att begränsas till. 0 eller inget värde innebär att ingen begränsning görs.';
$_lang['resourcelist_parents'] = 'Föräldrar';
$_lang['resourcelist_parents_desc'] = 'En lista med ID:n för att lägga till barn i listan med resurser.';
$_lang['resourcelist_where'] = 'Where-uttryck';
$_lang['resourcelist_where_desc'] = 'Ett JSON-objekt med where-uttryck att filtrera efter i frågesträngen när listan med resurser ska hämtas. (Stödjer inte mallvariabelsökning)';
$_lang['richtext'] = 'Richtext';
$_lang['sentence_case'] = 'Inledande versal';
$_lang['shownone'] = 'Tillåt tomt val';
$_lang['shownone_desc'] = 'Tillåter användaren att välja ett tomt val som är ett tomt värde.';
$_lang['start_day'] = 'Startdag';
$_lang['start_day_desc'] = 'Den dag som inleder en vecka. 0-baserad, standard är 0 som är söndag.';
$_lang['string'] = 'Sträng';
$_lang['string_format'] = 'Strängformat';
$_lang['style'] = 'Stil';
$_lang['tag_id'] = 'Tagg-ID';
$_lang['tag_name'] = 'Tagg-namn';
$_lang['target'] = 'Mål';
$_lang['text'] = 'Text';
$_lang['textarea'] = 'Textarea';
$_lang['textareamini'] = 'Textarea (liten)';
$_lang['textbox'] = 'Textbox';
$_lang['time_increment'] = 'Tidsintervall';
$_lang['time_increment_desc'] = 'Antal minuter mellan varje klockslag i listan. Standard är 15.';
$_lang['title'] = 'Titel';
$_lang['upper_case'] = 'Versaler';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Visningstext';
$_lang['width'] = 'Bredd';
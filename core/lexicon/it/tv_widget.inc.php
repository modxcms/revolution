<?php
/**
 * TV Widget English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Attributi';
$_lang['capitalize'] = 'Tutte Maiuscole';
$_lang['checkbox'] = 'Check Box';
$_lang['checkbox_columns'] = 'Colonne';
$_lang['checkbox_columns_desc'] = 'Il numero di colonne in cui vengono visualizzati i checkboxes.';
$_lang['class'] = 'Classe';
$_lang['combo_allowaddnewdata'] = 'Consenti Aggiunta Nuovi Elementi';
$_lang['combo_allowaddnewdata_desc'] = 'Se abilitato, consente che siano aggiunti elementi, non ancora presenti nella lista. Defaults è No.';
$_lang['combo_forceselection'] = 'Forza Selezione dalla Lista';
$_lang['combo_forceselection_desc'] = 'Usando l\'auto-completamento, se questo parametro è impostato su Si, viene consentito soltanto l\'inserimento di elementi della lista.';
$_lang['combo_listempty_text'] = 'Testo Elemento Lista Vuoto';
$_lang['combo_listempty_text_desc'] = 'Se l\'auto-completamento è abilitato, e l\'utente digita un valore non in lista, mostra questo testo.';
$_lang['combo_listheight'] = 'Altezza Lista';
$_lang['combo_listheight_desc'] = 'L\'altezza, in pixel, dell\'elenco a discesa stesso. Impostazioni predefinite come l\'altezza del controllo combobox.';
$_lang['combo_listwidth'] = 'Larghezza Lista';
$_lang['combo_listwidth_desc'] = 'La larghezza, in pixels, della stessa lista dropdown. Di defaults è la stessa larghezza della combobox.';
$_lang['combo_maxheight'] = 'Massima Altezza';
$_lang['combo_maxheight_desc'] = 'L\'altezza massima in pixels della lista dropdown prima che vengano mostrate le scrollbars (defaults è 300).';
$_lang['combo_stackitems'] = 'Incolonna Oggetti Selezionati';
$_lang['combo_stackitems_desc'] = 'Quando impostato su "SI", gli oggetti vengono mostrati 1 per linea. Di defaults il valore è "NO" e vengono mostrati tutti gli oggetti inline.';
$_lang['combo_title'] = 'Testata Lista';
$_lang['combo_title_desc'] = 'Se fornito, viene mostrato un elemento di testata con questo testo e aggiunto in cima alla lista dropdown.';
$_lang['combo_typeahead'] = 'Abilita Auto-Completamento';
$_lang['combo_typeahead_desc'] = 'Se abilitato, popola e auto-seleziona il resto del testo che viene digitato dopo un ritardo (configurabile, con il parametro "Ritardo Auto-Completamento") se la porzione di stringa digitata corrisponde a un valore conosciuto (defaults è off.).';
$_lang['combo_typeahead_delay'] = 'Ritardo Auto-Completamento';
$_lang['combo_typeahead_delay_desc'] = 'La durata in millisecondi del tempo che MODX attende prima di mostrare il testo di Auto-Completamento; se l\'Auto-Completamento è abilitato (defaults to 250).';
$_lang['date'] = 'Data';
$_lang['date_format'] = 'Formato Data';
$_lang['date_use_current'] = 'Se non presente, usa la data corrente';
$_lang['default'] = 'Default';
$_lang['delim'] = 'Delimitatore';
$_lang['delimiter'] = 'Delimitatore';
$_lang['disabled_dates'] = 'Date disabilitate';
$_lang['disabled_dates_desc'] = 'Una lista separata da virgola di "date" da disabilitare, indicate come stringhe. Queste stringhe saranno usate per costruire dinamicamente espressioni regolari, quindi sono molto potenti. Alcuni esempi:<br />
- Disabilita queste date esatte: 2003-03-08,2003-09-16<br />
- Disabilita questi giorni di qualsiasi anno: 03-08,09-16<br />
- Confronta soltanto l\'inizio (utile se stai usando anni abbreviati): ^03-08<br />
- Disabilita ogni giorno del Marzo 2006: 03-..-2006<br />
- Disabilita qualsiasi giorno di Marzo: ^03<br />
nota che i formate delle date incluse nella lista dovrebbero esattamente combaciare con la configurazione del formato. Per supportare le espressioni regolari, se stai usando un formato di data che contiene ".", dovrai evitare (escape) il punto quando vuoi restringere le date.';
$_lang['disabled_days'] = 'Giorni Disabilitati';
$_lang['disabled_days_desc'] = 'Una lista separata da virgola, di giorni da disabilitare, partendo da 0 (defaults è null). Qualche esempio:<br />
- Disabilita Domenica e Sabato: 0,6<br />
- Disabilita giorni feriali: 1,2,3,4,5';
$_lang['dropdown'] = 'Menu a Tendina (DropDown List Menu)';
$_lang['earliest_date'] = 'Data più vecchia';
$_lang['earliest_date_desc'] = 'La data più vecchia che è consentito selezionare.';
$_lang['earliest_time'] = 'Ora più vecchia';
$_lang['earliest_time_desc'] = 'L\'orario più vecchia che può essere selezionato.';
$_lang['email'] = 'E-mail';
$_lang['file'] = 'File';
$_lang['height'] = 'Altezza';
$_lang['hidden'] = 'Nascosto';
$_lang['htmlarea'] = 'HTML Area';
$_lang['htmltag'] = 'HTML Tag';
$_lang['image'] = 'Immagine';
$_lang['image_align'] = 'Allineamento';
$_lang['image_align_list'] = '(Nessuno)none,(base)baseline,(alto)top,(metà)middle,(basso)bottom,(testoalto)texttop,absmiddle,absbottom,(sin)left,(des)right';
$_lang['image_alt'] = 'Testo Alternativo';
$_lang['image_border_size'] = 'Spessore Bordo';
$_lang['image_hspace'] = 'Spazio H';
$_lang['image_vspace'] = 'Spazio V';
$_lang['latest_date'] = 'Ultima data';
$_lang['latest_date_desc'] = 'La data entro la quale è consentito scegliere.';
$_lang['latest_time'] = 'Ultimo orario';
$_lang['latest_time_desc'] = 'L\'orario entro il quale è consentito scegliere.';
$_lang['listbox'] = 'Lista, Singola Scelta (Listbox (Single-Select))';
$_lang['listbox-multiple'] = 'Lista, Scelta Multipla (ctrl+)(Listbox (Multi-Select))';
$_lang['list-multiple-legacy'] = 'Liste multiple Legacy';
$_lang['lower_case'] = 'Minuscolo';
$_lang['max_length'] = 'Massima Lunghezza';
$_lang['min_length'] = 'Minima Lunghezza';
$_lang['regex_text'] = 'Regular Expression Error';
$_lang['regex'] = 'Regular Expression Validator';
$_lang['name'] = 'Nome';
$_lang['number'] = 'Numero';
$_lang['number_allowdecimals'] = 'Consenti Decimali';
$_lang['number_allownegative'] = 'Consenti Negativi';
$_lang['number_decimalprecision'] = 'Precisione Decimali';
$_lang['number_decimalprecision_desc'] = 'La massima precisione da mostrare dopo il separatore dei decimali (defaults è 2).';
$_lang['number_decimalseparator'] = 'Separatore Decimali';
$_lang['number_decimalseparator_desc'] = 'Carattere(i) da consentire come separatore dei decimali (defaults è ".")';
$_lang['number_maxvalue'] = 'Valore Massimo';
$_lang['number_minvalue'] = 'Valore Minimo';
$_lang['option'] = 'Bottoni Scelta (Radio Options)';
$_lang['parent_resources'] = 'Risorse Genitori';
$_lang['radio_columns'] = 'Colonne';
$_lang['radio_columns_desc'] = 'Il numero di colonne con cui mostrare i radio boxes.';
$_lang['rawtext'] = 'Puro testo (deprecato)';
$_lang['rawtextarea'] = 'Pura Area testo (deprecato)';
$_lang['required'] = 'Consenti Vuoto';
$_lang['required_desc'] = 'Se impostato su No, MODX non permetterà all\'utente di salvare la risorsa finché non verrà inserito un valore corretto, non vuoto.';
$_lang['resourcelist'] = 'Elenco Risorse';
$_lang['resourcelist_depth'] = 'Profondità';
$_lang['resourcelist_depth_desc'] = 'I livelli di profondità che attraversa la query per pescare nella lista delle Risorse. Il valore di default è 10.';
$_lang['resourcelist_includeparent'] = 'Includi Genitori';
$_lang['resourcelist_includeparent_desc'] = 'Se Si, saranno incluse le risorse nominate nel campo Genitori della lista.';
$_lang['resourcelist_limitrelatedcontext'] = 'Limita ai Contesti Correlati';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Se selezionato, saranno incluse soltanto le Risorse collegate al contesto della Risorsa corrente.';
$_lang['resourcelist_limit'] = 'Limite';
$_lang['resourcelist_limit_desc'] = 'Il numero di Risorse a cui limitare la lista. 0 o vuoto equivale a infinito.';
$_lang['resourcelist_parents'] = 'Genitori';
$_lang['resourcelist_parents_desc'] = 'Una lista di IDs da cui pescare figli per la lista.';
$_lang['resourcelist_where'] = 'Dove le condizioni';
$_lang['resourcelist_where_desc'] = 'Un oggetto JSON di condizioni "dove" per cui filtrare la query che pesca (grab) la lista di Risorse. (Non supporta la ricerca di TV.)';
$_lang['richtext'] = 'Area Testo Formattabile (RichText)';
$_lang['sentence_case'] = 'Maiuscolo a inizio frase';
$_lang['shownone'] = 'Consente scelta Vuota';
$_lang['shownone_desc'] = 'Consente all\'utente di scegliere un elemento con valore vuoto.';
$_lang['start_day'] = 'Giorno di Partenza';
$_lang['start_day_desc'] = 'Indice del giorno da cui dovrebbe cominciare la settimana, partendo da 0 (defaults è 0, ovvero Domenica)';
$_lang['string'] = 'Stringa';
$_lang['string_format'] = 'Formato Stringa';
$_lang['style'] = 'Stile';
$_lang['tag_id'] = 'Tag ID';
$_lang['tag_name'] = 'Nome Tag';
$_lang['target'] = 'Target';
$_lang['text'] = 'Testo';
$_lang['textarea'] = 'Area di testo (Textarea)';
$_lang['textareamini'] = 'Mini Area Testo(Mini TextArea)';
$_lang['textbox'] = 'Box Testo';
$_lang['time_increment'] = 'Incremento Orario';
$_lang['time_increment_desc'] = 'Il numero di minuti fra ogni valore temporale della lista (defaults è 15).';
$_lang['hide_time'] = 'Hide time option for user';
$_lang['title'] = 'Titolo';
$_lang['upper_case'] = 'Maiuscolo';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Visualizzazione Testo';
$_lang['width'] = 'Larghezza';

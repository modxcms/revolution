<?php
/**
 * TV Widget English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Attributi';
$_lang['attr_attr_desc'] = 'Uno o più attributi separati da spazi da aggiungere al tag di questo elemento (ad esempio, <span class="example-input">rel="external" type="application/pdf"</span>).';
$_lang['attr_class_desc'] = 'Uno o più nomi di classe CSS separati da spazio.';
$_lang['attr_style_desc'] = 'Definizioni CSS (ad esempio, <span class="example-input">color:#f36f99; text-decoration:none;</span>).';
$_lang['attr_target_blank'] = 'Blank';
$_lang['attr_target_parent'] = 'Parent';
$_lang['attr_target_self'] = 'Self';
$_lang['attr_target_top'] = 'Top';
$_lang['attr_target_desc'] = 'Indica in quale finestra/scheda o cornice si aprirà l\'URL collegato. Per indirizzare un frame specifico, inserisci il suo nome al posto di una delle opzioni fornite.';
$_lang['capitalize'] = 'Tutte Maiuscole';
$_lang['checkbox'] = 'Spunta Casella';
$_lang['checkbox_columns'] = 'Colonne';
$_lang['checkbox_columns_desc'] = 'Il numero di colonne in cui vengono visualizzati i checkboxes.';
$_lang['class'] = 'Classe';
$_lang['classes'] = 'Classe/i';
$_lang['combo_allowaddnewdata'] = 'Consenti Aggiunta Nuovi Elementi';
$_lang['combo_allowaddnewdata_desc'] = 'Se abilitato, consente che siano aggiunti elementi, non ancora presenti nella lista. Defaults è No.';
$_lang['combo_forceselection'] = 'Richiedi Corrispondenza';
$_lang['combo_forceselection_desc'] = 'Salva solo l\'opzione digitata quando corrisponde a quella già definita nella lista.';
$_lang['combo_forceselection_multi_desc'] = 'Se questo è impostato a Sì, solo gli elementi già nell\'elenco sono consentiti. Se No, sono inseribili anche i nuovi valori.';
$_lang['combo_listempty_text'] = 'Opzione Messaggio Non Trovato';
$_lang['combo_listempty_text_desc'] = 'Messaggio da visualizzare quando il testo digitato non corrisponde alle opzioni esistenti.';
$_lang['combo_listheight'] = 'Altezza Lista';
$_lang['combo_listheight_desc'] = 'L\'altezza, in % o px, dell\'elenco a tendina stesso. Predefiniti all\'altezza della casella della combo.';
$_lang['combo_listwidth'] = 'Larghezza Lista';
$_lang['combo_listwidth_desc'] = 'La larghezza, in % o px, dell\'elenco a tendina stesso. Predefiniti alla larghezza della casella della combo.';
$_lang['combo_maxheight'] = 'Massima Altezza';
$_lang['combo_maxheight_desc'] = 'L\'altezza massima in pixels della lista dropdown prima che vengano mostrate le scrollbars (defaults è 300).';
$_lang['combo_stackitems'] = 'Incolonna Oggetti Selezionati';
$_lang['combo_stackitems_desc'] = 'Quando impostato su "SI", gli oggetti vengono mostrati 1 per linea. Di defaults il valore è "NO" e vengono mostrati tutti gli oggetti inline.';
$_lang['combo_title'] = 'Testata Lista';
$_lang['combo_title_desc'] = 'Se fornito, viene mostrato un elemento di testata con questo testo e aggiunto in cima alla lista dropdown.';
$_lang['combo_typeahead'] = 'Abilita Auto-Completamento';
$_lang['combo_typeahead_desc'] = 'Popola e seleziona automaticamente le opzioni che corrispondono a quando digiti dopo un ritardo configurabile. (Predefinito: No)';
$_lang['combo_typeahead_delay'] = 'Ritardo';
$_lang['combo_typeahead_delay_desc'] = 'Millisecondi prima che sia visualizzata un\'opzione corrispondente. (Predefinito: 250)';
$_lang['date'] = 'Data';
$_lang['date_format'] = 'Formato Data';
$_lang['date_format_desc'] = 'Inserisci un formato usando la sintassi strftime di <a href="https://www.php.net/strftime" target="_blank">php</a>.
    <div class="example-list">Esempi comuni includono:
        <ul>
            <li><span class="example-input">[[+example_1a]]</span> ([[+example_1b]]) (formato predefinito)</li>
            <li><span class="example-input">[[+example_2a]]</span> ([[+example_2b]])</li>
            <li><span class="example-input">[[+example_3a]]</span> ([[+example_3b]])</li>
            <li><span class="example-input">[[+example_4a]]</span> ([[+example_4b]])</li>
            <li><span class="example-input">[[+example_5a]]</span> ([[+example_5b]])</li>
            <li><span class="example-input">[[+example_6a]]</span> ([[+example_6b]])</li>
            <li><span class="example-input">[[+example_7a]]</span> ([[+example_7b]])</li>
        </ul>
    </div>
';
$_lang['date_use_current'] = 'Usa la data corrente come Fallback';
$_lang['date_use_current_desc'] = 'Quando non è richiesto un valore per questa TV (Consenti vuoto = “Sì”) e non è specificata una data predefinita, impostando questa opzione a “Sì” verrà visualizzata la data corrente.';
$_lang['default'] = 'Predefinito';
$_lang['default_date_now'] = 'Oggi con l\'ora corrente';
$_lang['default_date_today'] = 'Oggi (mezzanotte)';
$_lang['default_date_yesterday'] = 'Ieri (mezzanotte)';
$_lang['default_date_tomorrow'] = 'Domani (mezzanotte)';
$_lang['default_date_custom'] = 'Personalizzato (vedi descrizione qui sotto)';
$_lang['delim'] = 'Delimitatore';
$_lang['delimiter'] = 'Delimitatore';
$_lang['delimiter_desc'] = 'Uno o più caratteri utilizzati per separare i valori (applicabile alle TV che supportano più opzioni selezionabili).';
$_lang['disabled_dates'] = 'Date disabilitate';
$_lang['disabled_dates_desc'] = 'Un elenco separato, javascript <abbr title="regular expression">regex</abbr>-compatibile (meno delimitatori) delle date nel formato della data del gestore (attualmente «[[+format_current]]»).
    <p>Gli esempi che utilizzano il formato predefinito (“[[+format_default]]”) includono:</p>
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (seleziona date individuali)</li>
            <li><span class="example-input">[[+example_2a]]</span> (seleziona [[+example_2b]] e [[+example_2c]] di ogni anno)</li>
            <li><span class="example-input">[[+example_3a]]</span> (“^” per abbinare l\'inizio della stringa; questo seleziona tutti i [[+example_3b]])</li>
            <li><span class="example-input">[[+example_4a]]</span> (seleziona ogni giorno in [[+example_4b]])</li>
            <li><span class="example-input">[[+example_5]]</span> (“$” per abbinare la fine della stringa; questo seleziona ogni giorno nel mese di marzo di ogni anno)</li>
        </ul>
        Nota: se il formato della data utilizza separatori di punti dovranno avere sequenze di escape (e. ., «[[+example_6a]]» dovrebbe essere inserito sopra come «[[+example_6b]]»).
    </div>
';
$_lang['disabled_days'] = 'Giorni Disabilitati';
$_lang['disabled_days_desc'] = '';
$_lang['dropdown'] = 'Menu a Tendina (DropDown List Menu)';
$_lang['earliest_date'] = 'Data più vecchia';
$_lang['earliest_date_desc'] = 'La data più vecchia che è consentito selezionare.';
$_lang['earliest_time'] = 'Ora più vecchia';
$_lang['earliest_time_desc'] = 'L\'orario più vecchia che può essere selezionato.';
$_lang['email'] = 'E-mail';
$_lang['file'] = 'File';
$_lang['height'] = 'Altezza';
$_lang['hidden'] = 'Nascosto';
$_lang['hide_time'] = 'Nascondi Opzione Ora';
$_lang['hide_time_desc'] = 'Rimuove la possibilità di scegliere un\'ora dal selettore di data di questa TV.';
$_lang['htmlarea'] = 'Area HTML';
$_lang['htmltag'] = 'Tag HTML';
$_lang['image'] = 'Immagine';
$_lang['image_alt'] = 'Testo Alternativo';
$_lang['latest_date'] = 'Ultima data';
$_lang['latest_date_desc'] = 'La data entro la quale è consentito scegliere.';
$_lang['latest_time'] = 'Ultimo orario';
$_lang['latest_time_desc'] = 'L\'orario entro il quale è consentito scegliere.';
$_lang['listbox'] = 'Lista, Singola Scelta (Listbox (Single-Select))';
$_lang['listbox-multiple'] = 'Lista, Scelta Multipla (ctrl+)(Listbox (Multi-Select))';
$_lang['lower_case'] = 'Minuscolo';
$_lang['max_length'] = 'Massima Lunghezza';
$_lang['min_length'] = 'Minima Lunghezza';
$_lang['regex_text'] = 'Errore dell\'Espressione Regolare';
$_lang['regex_text_desc'] = 'Il messaggio da mostrare se l\'utente inserisce testo che non è valido in base al validatore <abbr title="regular expression">regex</abbr>.';
$_lang['regex'] = 'Convalidatore dell\'Espressione Regolare';
$_lang['regex_desc'] = 'Una stringa compatibile con javascript <abbr title="regular expression">regex</abbr> (meno i delimitatori) per limitare il contenuto di questa TV. Qualche esempio:
     <div class="example-list">
         <ul>
             <li><span class="example-input">[[+example_1]]</span> (schema per C.A.P. statunitensi)</li>
             <li><span class="example-input">[[+example_2]]</span> (consenti solo lettere)</li>
             <li><span class="example-input">[[+example_3]]</span> (consenti tutti i caratteri tranne i numeri)</li>
             <li><span class="example-input">[[+example_4]]</span> (deve terminare con la stringa “-XP”)</li>
         </ul>
     </div> 
';
$_lang['name'] = 'Nome';
$_lang['number'] = 'Numero';
$_lang['number_allowdecimals'] = 'Consenti Decimali';
$_lang['number_allownegative'] = 'Consenti Negativo';
$_lang['number_decimalprecision'] = 'Precisione';
$_lang['number_decimalprecision_desc'] = 'Il numero massimo di cifre consentito dopo il separatore decimale. (Predefinito: 2)';
$_lang['number_decimalprecision_strict'] = 'Precisione Decimale rigorosa';
$_lang['number_decimalprecision_strict_desc'] = 'Quando impostato a “Sì,” conserva gli zeri finali in numeri decimali (valori predefiniti a “No”).';
/* See note in number inputproperties config re separators */
$_lang['number_decimalseparator'] = 'Separatore';
$_lang['number_decimalseparator_desc'] = 'Il carattere usato come separatore decimale. (Predefinito: “.”)';
$_lang['number_maxvalue'] = 'Valore Massimo';
$_lang['number_minvalue'] = 'Valore Minimo';
$_lang['option'] = 'Bottoni Scelta (Radio Options)';
$_lang['parent_resources'] = 'Risorse Genitori';
$_lang['radio_columns'] = 'Colonne';
$_lang['radio_columns_desc'] = 'The number of columns the radio buttons are displayed in.';
$_lang['rawtext'] = 'Puro testo (deprecato)';
$_lang['rawtextarea'] = 'Pura Area testo (deprecato)';
$_lang['required'] = 'Consenti Vuoto';
$_lang['required_desc'] = 'Select “No” to make this TV a required field in the Resources it’s assigned to. (Default: “Yes”)';
$_lang['resourcelist'] = 'Elenco Risorse';
$_lang['resourcelist_depth'] = 'Profondità';
$_lang['resourcelist_depth_desc'] = 'The number of subfolders to drill down into for this lising’s search query. (Default: 10)';
$_lang['resourcelist_forceselection_desc'] = 'Disabled; only list matches are valid.';
$_lang['resourcelist_includeparent'] = 'Includi Genitori';
$_lang['resourcelist_includeparent_desc'] = 'Select “Yes” to include the Resources specified in the Parents field in the list.';
$_lang['resourcelist_limitrelatedcontext'] = 'Limita ai Contesti Correlati';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Select “Yes” to only include the Resources related to the context of the current Resource.';
$_lang['resourcelist_limit'] = 'Limite';
$_lang['resourcelist_limit_desc'] = 'The maximum number of Resources shown in this TV’s listing. (Default: 0, meaning unlimited)';
$_lang['resourcelist_listempty_text_desc'] = 'Disabled; selections will always match the list.';
$_lang['resourcelist_parents'] = 'Genitori';
$_lang['resourcelist_parents_desc'] = 'If specified, this TV’s listing will include only the child resources from this comma-separated set of resource IDs (containers).';
$_lang['resourcelist_where'] = 'Dove le condizioni';
$_lang['resourcelist_where_desc'] = '
    <p>A JSON object of one or more Resource fields to filter this TV’s listing of Resources.</p>
    <div class="example-list">Some examples:
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (only include Resources with template 4 applied)</li>
            <li><span class="example-input">[[+example_2]]</span> (include all Resources, except for those named “Home”)</li>
            <li><span class="example-input">[[+example_3]]</span> (include only Resources whose Resource Type is Weblink or Symlink)</li>
            <li><span class="example-input">[[+example_4]]</span> (include only Resources that are published and are not containers)</li>
        </ul>
    </div>
    <p>Note: Filtering by TV values is not supported.</p>
';
$_lang['richtext'] = 'Area Testo Formattabile (RichText)';
$_lang['sentence_case'] = 'Maiuscolo a inizio frase';
$_lang['start_day'] = 'Giorno di Partenza';
$_lang['start_day_desc'] = 'Day displayed as the beginning of the week in this TV’s date picker. (Default: “Sunday”)';
$_lang['string'] = 'Stringa';
$_lang['string_format'] = 'Formato Stringa';
$_lang['style'] = 'Stile';
$_lang['tag_name'] = 'Nome Tag';
$_lang['target'] = 'Obiettivo';
$_lang['text'] = 'Testo';
$_lang['textarea'] = 'Area di testo (Textarea)';
$_lang['textareamini'] = 'Mini Area Testo(Mini TextArea)';
$_lang['textbox'] = 'Box Testo';
$_lang['time_increment'] = 'Incremento Orario';
$_lang['time_increment_desc'] = 'The number of minutes between each time value in the list. (Default: 15)';
$_lang['title'] = 'Titolo';
$_lang['tv_default'] = 'Default Value';
$_lang['tv_default_desc'] = 'The content this TV will show if user-entered content is not provided.';
$_lang['tv_default_checkbox_desc'] = 'A double-pipe-separated set of option(s) selected for this TV if the user does not check one or more. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One, or “1||3” for Option One and Option Three)';
$_lang['tv_default_date'] = 'Default Date and Time';
$_lang['tv_default_date_desc'] = 'The date to show if the user does not provide one. Choose a relative date from the list above or enter a different date using one of the following patterns:
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (number respresents hours ago)</li>
            <li><span class="example-input">[[+example_2]]</span> (number represents hours in the future)</li>
            <li><span class="example-input">[[+example_3]]</span> (a specific date [and time if desired] using the format shown)</li>
        </ul>
        Note: The use of the “+” and “-” shown above is counter-intuitive, but correct (“+” represents backward in time).
    </div>';
$_lang['tv_default_email'] = 'Default Email Address';
$_lang['tv_default_email_desc'] = 'The email address this TV will show if the user does not provide one.';
$_lang['tv_default_file'] = 'Default File';
$_lang['tv_default_file_desc'] = 'The file path this TV will show if the user does not provide one.';
$_lang['tv_default_image'] = 'Default Image';
$_lang['tv_default_image_desc'] = 'The image path this TV will show if the user does not provide one.';
$_lang['tv_default_option'] = 'Default Option';
$_lang['tv_default_option_desc'] = 'The option selected for this TV if the user does not choose one. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One)';
$_lang['tv_default_options'] = 'Default Option(s)';
$_lang['tv_default_options_desc'] = 'A double-pipe-separated set of option(s) selected for this TV if the user does not choose one or more. If your options include labels (e.g., Option One==1||Option Two==2||Option Three==3), be sure to enter the value (i.e., “1” for Option One, or “1||3” for Option One and Option Three)';
$_lang['tv_default_radio_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox-multiple_desc'] = $_lang['tv_default_options_desc'];
$_lang['tv_default_number'] = 'Default Number';
$_lang['tv_default_number_desc'] = 'The number this TV will show if the user does not provide one.';
$_lang['tv_default_resource'] = 'Default Resource (ID)';
$_lang['tv_default_resourcelist_desc'] = 'The resource this TV will show if the user does not choose one.';
$_lang['tv_default_tag'] = 'Default Tag(s)';
$_lang['tv_default_tag_desc'] = 'A comma-separated set of option(s) selected for this TV if the user does not choose one or more. If your options include labels (e.g., Tag One==1||Tag Two==2||Tag Three==3), be sure to enter the value (i.e., “1” for Tag One, or “1,3” for Tag One and Tag Three)';
$_lang['tv_default_text'] = 'Default Text';
$_lang['tv_default_text_desc'] = 'The text content this TV will show if the user does not provide it.';
$_lang['tv_default_url'] = 'Default URL';
$_lang['tv_default_url_desc'] = 'The URL this TV will show if the user does not provide one.';
$_lang['tv_elements'] = 'Input Option Values';
$_lang['tv_elements_checkbox'] = 'Checkbox Options';
$_lang['tv_elements_listbox'] = 'Dropdown List Options';
$_lang['tv_elements_radio'] = 'Radio Button Options';
$_lang['tv_elements_tag'] = 'Tag Options';
$_lang['tv_elements_desc'] = 'Defines the selectable options for this TV, which may be manually entered or built with a one-line <a href="https://docs.modx.com/current/en/building-sites/elements/template-variables/bindings/select-binding" target="_blank">database query</a>. Some examples:
    <div class="example-list">
        <ul>
            <li><span class="example-input">Bird||Cat||Dog</span> (shorthand for Bird==Bird||Cat==Cat||Dog==Dog)</li>
            <li><span class="example-input">White==#ffffff||Black==#000000</span> (where label==value)</li>
            <li><span class="example-input">[[+example_1]]</span> (builds a list of published Resources whose assigned template id is 1)</li>
            <li><span class="example-input">[[+example_2]]</span> (builds the same list as the previous example, including a blank option)</li>
        </ul>
    </div>
    ';
$_lang['tv_elements_checkbox_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_listbox_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_listbox-multiple_desc'] = $_lang['tv_elements_listbox_desc'];
$_lang['tv_elements_radio_desc'] = $_lang['tv_elements_option_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_tag_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_daterange_elements_desc'] = 'Test options desc for daterange with example ph: [[+ex1]]';
$_lang['tv_daterange_default_text_desc'] = 'Test default text desc for daterange with example ph: [[+ex1]]';
$_lang['tv_type'] = 'Input Type';
$_lang['upper_case'] = 'Maiuscolo';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Visualizzazione Testo';
$_lang['width'] = 'Larghezza';

// Temporarily match old keys to new ones to ensure compatibility
$_lang['tv_default_datetime'] = $_lang['tv_default_date'];

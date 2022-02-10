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
$_lang['radio_columns_desc'] = 'Il numero di colonne in cui vengono visualizzati i pulsanti di selezione.';
$_lang['rawtext'] = 'Puro testo (deprecato)';
$_lang['rawtextarea'] = 'Pura Area testo (deprecato)';
$_lang['required'] = 'Consenti Vuoto';
$_lang['required_desc'] = 'Selezionare “No” per rendere questo TV un campo obbligatorio nelle Risorse a cui è assegnato. (Predefinito: “Sì”)';
$_lang['resourcelist'] = 'Elenco Risorse';
$_lang['resourcelist_depth'] = 'Profondità';
$_lang['resourcelist_depth_desc'] = 'Il numero di sottocartelle in cui scendere per la ricerca di questa lista. (Predefinito: 10)';
$_lang['resourcelist_forceselection_desc'] = 'Disabilitato; solo le corrispondenze della lista sono valide.';
$_lang['resourcelist_includeparent'] = 'Includi Genitori';
$_lang['resourcelist_includeparent_desc'] = 'Selezionare “Sì” per includere le risorse specificate nel campo Genitori nella lista.';
$_lang['resourcelist_limitrelatedcontext'] = 'Limita ai Contesti Correlati';
$_lang['resourcelist_limitrelatedcontext_desc'] = 'Selezionare “Sì” per includere solo le risorse relative al contesto della risorsa attuale.';
$_lang['resourcelist_limit'] = 'Limite';
$_lang['resourcelist_limit_desc'] = 'Il numero massimo di Risorse mostrato nella lista di questa TV. (Predefinito: 0, significa illimitato)';
$_lang['resourcelist_listempty_text_desc'] = 'Disabilitato; le selezioni corrisponderanno sempre all\'elenco.';
$_lang['resourcelist_parents'] = 'Genitori';
$_lang['resourcelist_parents_desc'] = 'Se specificato, l\'elenco di questa TV includerà solo le risorse figlio di questo insieme separato da virgole di ID risorsa (contenitori).';
$_lang['resourcelist_where'] = 'Dove le condizioni';
$_lang['resourcelist_where_desc'] = '
    <p>Un oggetto JSON di uno o più campi di risorse per filtrare l\'elenco delle risorse di questa TV.</p>
    <div class="example-list">Alcuni esempi:
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (include solo le Risorse con il Template 4 applicato)</li>
            <li><span class="example-input">[[+example_2]]</span> (include tutte le Risorse, ad eccezione di quelli denominati “Home”)</li>
            <li><span class="example-input">[[+example_3]]</span> (include solo le Risorse il cui tipo di risorsa è Weblink o Symlink)</li>
            <li><span class="example-input">[[+example_4]]</span> (include solo le Risorse che sono pubblicate e che non sono contenitori)</li>
        </ul>
    </div>
    <p>Nota: il filtraggio per i valori TV non è supportato.</p>
';
$_lang['richtext'] = 'Area Testo Formattabile (RichText)';
$_lang['sentence_case'] = 'Maiuscolo a inizio frase';
$_lang['start_day'] = 'Giorno di Partenza';
$_lang['start_day_desc'] = 'Giorno visualizzato come l\'inizio della settimana nel selettore di data di questa TV (predefinito: “Domenica”)';
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
$_lang['time_increment_desc'] = 'Il numero di minuti tra ogni valore temporale nella lista. (Predefinito: 15)';
$_lang['title'] = 'Titolo';
$_lang['tv_default_checkbox_desc'] = 'Un insieme di opzioni selezionate per questa TV separate da due pipe se l\' utente non controlla una o più. Se le opzioni includono etichette (ad es. Opzione One==1<unk> <unk> Opzione Two==2<unk> <unk> Opzione Three==3), essere sicuri di inserire il valore (cioè, “1” per l\'opzione uno, o “1<unk> <unk> 3” per l\'opzione Uno e l\'opzione tre)';
$_lang['tv_default_date'] = 'Data e ora predefinite';
$_lang['tv_default_date_desc'] = 'La data da mostrare se l\'utente non ne fornisce una. Scegli una data relativa dalla lista qui sopra o inserisci una data diversa utilizzando uno dei seguenti modelli:
    <div class="example-list">
        <ul>
            <li><span class="example-input">[[+example_1]]</span> (numero rappresenta ore fa)</li>
            <li><span class="example-input">[[+example_2]]</span> (numero rappresenta ore in futuro)</li>
            <li><span class="example-input">[[+example_3]]</span> (data specifica [e ora se lo desideri] utilizzando il formato mostrato)</li>
        </ul>
        Nota: l\'uso dei “+” e “-” mostrati sopra è contro-intuitivo, ma corretto (“+” rappresenta indietro nel tempo).
    </div>';
$_lang['tv_default_email'] = 'Indirizzo Email Predefinito';
$_lang['tv_default_email_desc'] = 'L\'indirizzo email che questa TV mostrerà se l\'utente non ne fornisce uno.';
$_lang['tv_default_file'] = 'File Predefinito';
$_lang['tv_default_file_desc'] = 'Il percorso del file che questa TV mostrerà se l\'utente non ne fornisce uno.';
$_lang['tv_default_image'] = 'Immagine Predefinita';
$_lang['tv_default_image_desc'] = 'Il percorso dell\'immagine che questa TV mostrerà se l\'utente non ne fornisce uno.';
$_lang['tv_default_option'] = 'Opzione Predefinita';
$_lang['tv_default_option_desc'] = 'L\'opzione selezionata per questo televisore se l\'utente non sceglie una. Se le opzioni includono etichette (ad es. Opzione One==1<unk> <unk> Opzione Two==2<unk> <unk> Opzione Three==3), assicurarsi di inserire il valore (cioè, “1” per l\'opzione 1)';
$_lang['tv_default_options'] = 'Opzioni Predefinite';
$_lang['tv_default_options_desc'] = 'Un insieme di opzioni, separate da due pipe, selezionate per questa TV se l\'utente non sceglie una o più opzioni. Se le opzioni includono etichette (ad es. Opzione One==1<unk> <unk> Opzione Two==2<unk> <unk> Opzione Three==3), essere sicuri di inserire il valore (cioè, “1” per l\'opzione uno, o “1<unk> <unk> 3” per l\'opzione Uno e l\'opzione tre)';
$_lang['tv_default_radio_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox_desc'] = $_lang['tv_default_option_desc'];
$_lang['tv_default_listbox-multiple_desc'] = $_lang['tv_default_options_desc'];
$_lang['tv_default_number'] = 'Numero Predefinito';
$_lang['tv_default_number_desc'] = 'Il numero che la TV mostrerà se l\'utente non ne fornisce uno.';
$_lang['tv_default_resource'] = 'Risorsa Predefinita (ID)';
$_lang['tv_default_resourcelist_desc'] = 'La risorsa mostrata da questa TV se l\'utente non ne sceglie una.';
$_lang['tv_default_tag'] = 'Tag(s) predefinito/i';
$_lang['tv_default_tag_desc'] = 'Un insieme separato da virgole di opzioni selezionate per questa TV se l\'utente non sceglie una o più opzioni. Se le opzioni includono etichette (e. ., Tag One==1<unk> <unk> Tag Two==2<unk> <unk> Tag Three==3), assicuratevi di inserire il valore (cioè, “1” per Tag One, o “1,3” per Tag One e Tag Three)';
$_lang['tv_default_text'] = 'Testo Predefinito';
$_lang['tv_default_text_desc'] = 'Il contenuto del testo che la TV mostrerà se l\'utente non lo fornisce.';
$_lang['tv_default_url'] = 'Url Predefinito';
$_lang['tv_default_url_desc'] = 'L\'URL che questa TV mostrerà se l\'utente non ne fornisce uno.';
$_lang['tv_elements_checkbox'] = 'Opzioni Checkbox';
$_lang['tv_elements_listbox'] = 'Opzioni Elenco a tendina';
$_lang['tv_elements_radio'] = 'Opzioni Pulsante Radio';
$_lang['tv_elements_tag'] = 'Opzioni Tag';
$_lang['tv_elements_desc'] = 'Definisce le opzioni selezionabili per questa TV , che possono essere inserite manualmente o costruite con una <a href="https://docs.modx.com/current/en/building-sites/elements/template-variables/bindings/select-binding" target="_blank">query di database</a>. Alcuni esempi:
    <div class="example-list">
        <ul>
            <li><span class="example-input">Bird<unk> <unk> Cat<unk> <unk> Dog</span> (shorthand for Bird==Bird<unk> <unk> Cat==Cat<unk> <unk> Dog==Dog)</li>
            <li><span class="example-input">Bianco==#ffff<unk> <unk> Black==#000000</span> (dove label==value)</li>
            <li><span class="example-input">[[+example_1]]</span> (compila una lista di risorse pubblicate il cui ID modello assegnato è 1)</li>
            <li><span class="example-input">[[+example_2]]</span> (costruisce la stessa lista dell\'esempio precedente, inclusa un\'opzione vuota)</li>
        </ul>
    </div>
    ';
$_lang['tv_elements_checkbox_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_listbox_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_listbox-multiple_desc'] = $_lang['tv_elements_listbox_desc'];
$_lang['tv_elements_radio_desc'] = $_lang['tv_elements_option_desc'] = $_lang['tv_elements_desc'];
$_lang['tv_elements_tag_desc'] = $_lang['tv_elements_desc'];
$_lang['upper_case'] = 'Maiuscolo';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Visualizzazione Testo';
$_lang['width'] = 'Larghezza';

// Temporarily match old keys to new ones to ensure compatibility
$_lang['tv_default_datetime'] = $_lang['tv_default_date'];

/*
    Refer to default.inc.php for the keys below.
    (Placement in this default file necessary to allow
    quick create/edit panels access to them when opened
    outside the context of their respective element types)

    tv_type
    tv_default
    tv_default_desc
    tv_elements

*/

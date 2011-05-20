<?php
/**
 * Import Italian lexicon entries
 *
 * @language it
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Specifica una lista di estensioni da importare separate da virgola.<br /><small><em>Lascia il campo vuoto per importare tutti i file compatibili con i tipi di contenuto che hai impostato per il sito. Tipi sconosciuti saranno importati come testo semplice.</em></small>';
$_lang['import_base_path'] = 'Inserisci il percorso base che contiene i files da importare.<br /><small><em>Lascia il campo vuoto per usare l\'impostazione del percorso statico files del Contesto bersaglio.</em></small>';
$_lang['import_duplicate_alias_found'] = 'La risorsa [[+id]] utilizza già l\'alias [[+alias]]. Inserire un alias univoco.';
$_lang['import_element'] = 'Inserisci l\'elemento HTML root da importare:';
$_lang['import_enter_root_element'] = 'Inserisci l\'elemento root da importare:';
$_lang['import_files_found'] = '<strong>Trovati %s documenti da importare...</strong><p/>';
$_lang['import_parent_document'] = 'Documento genitore:';
$_lang['import_parent_document_message'] = 'Usa l\'albero di documenti proposto sotto per selezionare la directory  in cui importare i file.';
$_lang['import_resource_class'] = 'Selezione una classe modResource per l\'importazione:<br /><small><em>Usa modStaticResource per linkare a file statici, o usa modDocument per copiare il contenuto nel database.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">FALLITO!</span>';
$_lang['import_site_html'] = 'Importa sito da HTML';
$_lang['import_site_importing_document'] = 'Sto importando  il file <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Tempo massimo di importazione:';
$_lang['import_site_maxtime_message'] = 'Qui puoi specificare il numero di secondi che il Content Manager può impiegare per importare il sito (sovrascrivendo la configurazione php). Scrivi 0 per un tempo illimitato. ATTENZIONE! settando 0 o un numero troppo alto puoi causare problemi al server e  quindi non è consigliabile.';
$_lang['import_site_message'] = '<p>Con questo strumento puoi importare  nel database il contenuto da un set di file HTML. <em>Nota che avrai bisogno di copiare i file e/o le cartelle nella directory core/import.</em></p><p>Compila le opzioni del modulo sottostante, e (facoltativo) seleziona dall\'albero dei documenti una risorsa genitore dove inserire i file importati, quindi clicca \'Importa HTML\' per iniziare il processo di importazione. I file importati saranno salvati nel posto selezionato usando, ove possibile, il nome dei file come alias del documento e il titolo della pagina come titolo del documento.</p>';
$_lang['import_site_resource'] = 'Importa risorse da file statici';
$_lang['import_site_resource_message'] = '<p>Usando questo strumento puoi importare nel database risorse da un set di file statici. <em>Nota che avrai bisogno di copiare i file e/o le cartelle nella directory core/import.</em></p><p>Compila le opzioni del formulario sottostante, e (facoltativo) seleziona dall\'albero dei documenti una risorsa genitore dove inserire  i file importati, quindi clicca \'Importa Risorse\' per iniziare il processo di importazione. I file importati saranno salvati nel posto selezionato usando, ove possibile, il nome dei file come alias del documento, e, se HTML, il titolo della pagina come titolo del documento.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">SALTATO!</span>';
$_lang['import_site_start'] = 'Inizia importazione';
$_lang['import_site_success'] = '<span style="color:#009900">SUCCESSO!</span>';
$_lang['import_site_time'] = 'Importazione conclusa. L\'importazione è durata %s secondi.';
$_lang['import_use_doc_tree'] = 'Usa l\'albero di documenti proposto sotto per selezionare la directory  in cui importare i file.';
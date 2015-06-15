<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Includi files non inseribili in cache (non-cacheable):';
$_lang['export_site_exporting_document'] = 'Sto esportando  file <strong>%s</strong> di <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Fallito!</span>';
$_lang['export_site_html'] = 'Esporta sito in HTML';
$_lang['export_site_maxtime'] = 'Tempo massimo di esportazione:';
$_lang['export_site_maxtime_message'] = '';
$_lang['export_site_message'] = '<p>Usando questa funzione potete esportare l\'intero sito in HTML. Considerate che cosi\' facendo perderete molte funzionalita\' di MODX, tipo:</p><ul><li>Gli accessi ai file esportati non saranno registrati.</li><li>Gli snippet interattivi NON funzionano nei file esportati.</li><li>Solo le normali Risorse saranno esportate, i Link Web non lo saranno.</li><li>Il processo di esportazione potrebbe fallire se le Risorse contengono snippet che inviano headers HTTP di redirezione.</li><li>A seconda di come avete scritto i documenti, i fogli di stile e le immagini, il design del sito potrebbe essere compromesso. Per sistemarlo, potete salvare/spostare i file esportati nella stessa cartella dove risiede il file index.php di MODX.</li></ul></p><p>Compilate il modulo e premete \'Avvia esportazione\' per avviare il processo. I file creati saranno salvati nel punto specificato usando, ove possibile, gli alias dei documenti come nomi dei file. Nell\'esportare il sito, sarebbe meglio aver attivato gli \'URL semplici\' nella configurazione di MODX. In base alla dimensione del sito, l\'esportazione potrebbe impiegare un po\' di tempo.</p><p><b>Ogni file esistente sar&agrave; sovrascritto dai nuovi file se i loro nomi sono identici!</b></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Trovati %s documenti da esportare...</strong></p>';
$_lang['export_site_prefix'] = 'Prefisso file:';
$_lang['export_site_start'] = 'Avvia esportazione';
$_lang['export_site_success'] = '<span style="color:#009900">Successo!</span>';
$_lang['export_site_suffix'] = 'Suffisso file:';
$_lang['export_site_target_unwritable'] = 'La cartella scelta non e\' scrivibile. Assicurarsi che la cartella sia scrivibile e di riprovare.';
$_lang['export_site_time'] = 'Esportazione terminata. L\'operazione e\' stata completata in %s secondi.';
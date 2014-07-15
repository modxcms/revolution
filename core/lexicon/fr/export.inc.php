<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Inclure les fichiers non mis en cache:';
$_lang['export_site_exporting_document'] = 'Exporter les fichiers <strong>%s</strong> de <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Echec!</span>';
$_lang['export_site_html'] = 'Exporter le site en HTML';
$_lang['export_site_maxtime'] = 'Temps maximum d\'export:';
$_lang['export_site_maxtime_message'] = 'Vous pouvez indiquer ici le nombre de secondes maximum dont MODX dispose pour exporter le site (en écrasant les réglages PHP). 0 pour une durée illimitée. Notez cependant que spécifier la valeur à 0, ou une valeur très élevée peut entrainer des comportements bizarres du serveur et n\'est donc pas recommandé.';
$_lang['export_site_message'] = '<p>En utilisant cette fonction, vous pouvez exporter la totalité du site en fichiers HTML. Notez cependant que vous perdrez une grande majorité des fonctionnalités de MODX en procédant ainsi:</p><ul><li>Les lectures de page des fichiers exportés ne seront pas enregistrées.</li><li>L\'interaction des snippets de fonctionnera PAS dans les fichiers exportés</li><li>Seuls les documents normaux seront exportés, les liens ne le seront pas</li><li>Le processus d\'exportation peut échouer si vos documents contiennent des snippets qui utilisent des entêtes de redirections.</li><li>Selon la façon dont vous avez rédigé vos documents, vos feuilles de style et images, le visuel de votre site peut être déterioré. Pour prévenir cela, vous pouvez sauvegarder/déplacer vos fichiers exportés dans le même répertoire où se trouve le fichier principal index.php de MODX.</li></ul><p>Veuillez remplir le formulaire et cliquer sur \'Exporter\' pour commencer le processus d\'exportation. Les fichiers créés seront sauvegardés dans le répertoire choisi, en utilisant, si possible, l\'alias du document en tant que nom de fichier. Lors de l\'export de votre site, il est préférable d\'avoir le paramètre \'URL simples\' activé dans les options de configuration de MODX. Selon la taille de votre site, l\'export peut être long.</p><p><em>Tout ancien fichier existant sera remplacé pour les nouveaux si leurs noms sont identiques!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>%s documents trouvés pour l\'export...</strong></p>';
$_lang['export_site_prefix'] = 'Préfixe de fichier:';
$_lang['export_site_start'] = 'Commencer l\'export';
$_lang['export_site_success'] = '<span style="color:#009900">Opération effectuée!</span>';
$_lang['export_site_suffix'] = 'Suffixe de fichier:';
$_lang['export_site_target_unwritable'] = 'Le répertoire choisi n\'est pas accessible en écriture. Veuillez vous assurer que le répertoire cible soit accessible en écriture et essayez de nouveau.';
$_lang['export_site_time'] = 'Export terminé en %s secondes.';
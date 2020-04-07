<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Incluir arquivos que não são inclusos no cache:';
$_lang['export_site_exporting_document'] = 'Exportando arquivo <strong>%s</strong> de <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Falhou!</span>';
$_lang['export_site_html'] = 'Exportar site para HTML';
$_lang['export_site_maxtime'] = 'Tempo máximo de exportação:';
$_lang['export_site_maxtime_message'] = 'Aqui você pode especificar o número de segundos MODx pode tomar para exportar o site (substituindo as configurações do PHP). Digite 0 para tempo ilimitado. Por favor observe que definindo 0 ou um número muito alto pode fazer coisas estranhas a seu servidor e não é recomendado.';
$_lang['export_site_message'] = '<p>Usando esta função, você pode exportar o site inteiro para arquivos HTML. Por favor observe que no entanto, que você vai perder um monte de funcionalidades MODx se você fizer isso:</p><ul><li>Leituras de página sobre os arquivos exportados não serão gravadas.</li><li>Snippets interativos NÃO funcionarão em arquivos exportados</li><li>Apenas os documentos normais serão exportados, links da Web não serão exportados.</li><li>O processo de exportação pode falhar se seus documentos contêm Snippets que enviam cabeçalhos de redirecionamento.</li><li>Dependendo de como você escreveu seus documentos, folhas de estilo e imagens, o design do seu site pode ser quebrado. Para corrigir isso, você pode salvar / mover os arquivos exportados para o mesmo diretório onde o arquivo principal index.php do MODx está localizado.</li></ul><p>Por favor, preencha o formulário e em Exportar para iniciar o processo de exportação. Os arquivos criados serão salvos no local especificado, utilizando, sempre que possível, aliases do documento como nomes de arquivos. Ao exportar o seu site, é melhor ter o item de configuração MODx aliases amigáveis definida como Sim. Dependendo do tamanho do seu site, a exportação pode demorar um pouco.</p><p><em>Todos os arquivos existentes serão substituídos pelos novos arquivos, se os seus nomes são idênticos!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Encontrado %s documentos para exportar...</strong></p>';
$_lang['export_site_prefix'] = 'Prefixo de arquivo:';
$_lang['export_site_start'] = 'Iniciar Exportação';
$_lang['export_site_success'] = '<span style="color:#009900">Sucesso!</span>';
$_lang['export_site_suffix'] = 'Sufixo de arquivo:';
$_lang['export_site_target_unwritable'] = 'O diretório Alvo não tem permissão de escrita. Por favor tenha certeza que o diretório tem permissão de escrita e tente novamente.';
$_lang['export_site_time'] = 'Exportação finalizou. Levou %s segundos para completar.';
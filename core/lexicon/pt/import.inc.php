<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Especificar uma lista delimitada por vírgulas de extensões de arquivo para importação.<br /><small><em>Deixe em branco para importar todos os arquivos de acordo com os tipos de conteúdo disponível em seu site. Tipos Desconhecido serão mapeados como texto simples.</em></small>';
$_lang['import_base_path'] = 'Digite o caminho do arquivo base que contém os arquivos para importação.<br /><small><em>Deixe em branco para usar o caminho do arquivo de configuração estática do contexto alvo.</em></small>';
$_lang['import_duplicate_alias_found'] = 'Recurso [[+id]] já está usando o alias de [[+alias.]] Por favor insira um alias exclusivo.';
$_lang['import_element'] = 'Digite o elemento raiz HTML para importar:';
$_lang['import_element_help'] = 'Fornecer o JSON com associações "campo": "valor". Se valor começa com $ é seletor estilo jQuery. O campo pode ser um campo de Recurso ou o nome de VT.';
$_lang['import_enter_root_element'] = 'Digite o elemento raiz de importação:';
$_lang['import_files_found'] = '<strong>Foram encontrados %s documentos para importação...</strong><p/>';
$_lang['import_parent_document'] = 'Documento pai:';
$_lang['import_parent_document_message'] = 'Use a árvore de documentos apresentada a seguir para selecionar a localização dos pais para importar seus arquivos em.';
$_lang['import_resource_class'] = 'Selecione uma classe modResource de importação:<br /><small><em>Use modStaticResource link para arquivos estáticos, ou modDocument para copiar o conteúdo para o banco de dados.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Falhou!</span>';
$_lang['import_site_html'] = 'Importar do site HTML';
$_lang['import_site_importing_document'] = 'Importação de arquivo <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Tempo máximo de importação:';
$_lang['import_site_maxtime_message'] = 'Aqui você pode especificar o número de segundos que o Gerenciador de Conteúdo pode ter de importar o site (substituir configurações PHP). Digite 0 para ilimitado de tempo. Por favor note, a configuração 0 ou um número muito alto pode fazer coisas estranhas ao seu servidor e não é recomendado.';
$_lang['import_site_message'] = '<p>Usando esta ferramenta você poderá importar o conteúdo de um conjunto de arquivos HTML para o banco de dados.<em>Por favor note que você precisará copiar os arquivos e/ou pastas para a pasta de \'core/import\'.</em></p><p>Preencha o formulário abaixo opções, opcionalmente, selecione um recurso principal para os arquivos importados da árvore do documento e pressione "Importar HTML"para iniciar o processo de importação. Os arquivos importados serão salvos no local selecionado, utilizando, sempre que possível, o nome de arquivos como alias do documento, o título da página o título do documento.</p>';
$_lang['import_site_resource'] = 'Importar recursos de arquivos estáticos';
$_lang['import_site_resource_message'] = '<p>Usando esta ferramenta você pode importar recursos de um conjunto de arquivos estáticos no banco de dados. <em> Por favor note que você precisará copiar os arquivos e / ou pastas para a pasta de \'core/import\'. </em></p><p>Preencha o formulário abaixo opções, opcionalmente, selecione um recurso principal para os arquivos importados da árvore do documento e pressione importar recursos para iniciar o processo de importação. Os arquivos importados serão salvos no local selecionado, utilizando, sempre que possível, o nome de arquivos como alias do documento e, se for em HTML, o título da página o título do documento.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Pulado!</span>';
$_lang['import_site_start'] = 'Iniciar importação';
$_lang['import_site_success'] = '<span style="color:#009900">Sucesso!</span>';
$_lang['import_site_time'] = 'Importação terminada. Importação tomou %s segundos para completar.';
$_lang['import_use_doc_tree'] = 'Use a árvore de documentos apresentada a seguir para selecionar a localização dos pais para importar seus arquivos em.';
<?php
/**
 * Config Check English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Por favor entre em contato com o administrador do sistema e avise sobre esta mensagem!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'Configuração de Contexto allow_tags_in_post está ativa fora do `mgr`';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'A Configuração de Contexto allow_tags_in_post está ativa em sua instalação fora do Contexto mgr. O MODX recomenda que esta configuração esteja desativada a menos que você precisa permitir explicitamente que os usuários enviem tags de MODX, entidades numéricos, ou tags de script HTML através do método POST de um formulário em seu site. Este geralmente deve ser desativado, exceto no Contexto mgr.';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'A Configuração do sistema allow_tags_in_post está Ativa';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'A Configuração do sistema allow_tags_in_post System Setting está ativa em sua instalação. O MODX recomenda que esta configuração esteja desativada a menos que você precisa permitir explicitamente que os usuários enviem tags MODX, entidades numéricas, ou tags de script HTML através do método POST de um formulário em seu site. É melhor permitir que este via Configurações de Contexto para Contextos específicos.';
$_lang['configcheck_cache'] = 'diretório de cache não tem permissão de escrita';
$_lang['configcheck_cache_msg'] = 'MODX cannot write to the cache directory. MODX will still function as expected, but no caching will take place. To solve this, make the /cache/ directory writable.';
$_lang['configcheck_configinc'] = 'Arquivo de configuração tem permissões de acesso!';
$_lang['configcheck_configinc_msg'] = 'Seu site está vulnerável a hackers que poderiam fazer um grande estrago para o site. Por favor faça com que seu arquivo de configuração seja apenas de leitura! Se você não é o administrador do site por favor, entre em contato com o administrador de sistemas e avise-o sobre esta mensagem! O arquivo está localizado em  core/config/config.inc.php';
$_lang['configcheck_default_msg'] = 'Um aviso não especificado não foi encontrado. O que é estranho.';
$_lang['configcheck_errorpage_unavailable'] = 'A pagina de erro do seu site não está disponível.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Isto significa que sua página de Erro não está acessível para navegantes normais ou não existe. Isto pode levar para um looping recursivo e vários erros nos relatórios do seu site. Tenha certeza que não há grupos de usuários associados a esta página.';
$_lang['configcheck_errorpage_unpublished'] = 'A página de erro do seu website não está publicada ou não existe.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Isto siginifica que a sua página de Erro não está acessível ao público geral. Publique a sua página ou tenha certeza que está associado a um documento existente em sua árvore do site no menu Sistema &gt; Opções de Sistema.';
$_lang['configcheck_htaccess'] = 'Pasta \'core\' é acessível pela web';
$_lang['configcheck_htaccess_msg'] = 'MODX detected that your core folder is (partially) accessible to the public.
<strong>This is not recommended and a security risk.</strong>
If your MODX installation is running on a Apache webserver
you should at least set up the .htaccess file inside the core folder <em>[[+fileLocation]]</em>.
This can be easily done by renaming the existing ht.access example file there to .htaccess.
<p>There are other methods and webservers you may use, please read the <a href="https://docs.modx.com/3.x/en/getting-started/maintenance/securing-modx">Hardening MODX Guide</a>
for further information about securing your site.</p>
If you setup everything correctly, browsing e.g. to the <a href="[[+checkUrl]]" target="_blank">Changelog</a>
should give you a 403 (permission denied) or better a 404 (not found). If you can see the changelog
there in the browser, something is still wrong and you need to reconfigure or call an expert to solve this.';
$_lang['configcheck_images'] = 'Diretório de imagens não tem permissão de escrita';
$_lang['configcheck_images_msg'] = 'O diretório de imagens não tem permissões para escrita, ou não existe. Isto siginifica que as funções do Gerenciador de Imagens não funcionaram no editor!';
$_lang['configcheck_installer'] = 'Instalador ainda presente.';
$_lang['configcheck_installer_msg'] = 'The setup/ directory contains the installer for MODX. Just imagine what might happen if an evil person finds this folder and runs the installer! They probably won\'t get too far, because they\'ll need to enter some user information for the database, but it\'s still best to delete this folder from your server. It is located at: [[+path]]';
$_lang['configcheck_lang_difference'] = 'Número de entradas incorreto no arquivo de linguagem';
$_lang['configcheck_lang_difference_msg'] = 'A língua selecionada tem um número diferente de entradas que a língua padrão. Isto não é necessáriamente um problem, mas pode significar que o arquivo de linguagem deve ser atualizado.';
$_lang['configcheck_notok'] = 'Um ou mais detalhes de configuração não estão de acordo:';
$_lang['configcheck_phpversion'] = 'Versão do PHP está desatualizada';
$_lang['configcheck_phpversion_msg'] = 'Sua versão PHP [[+phpversion]] já não é mantida pelos desenvolvedores PHP, o que significa que não há atualizações de segurança estão disponíveis. Também é provável que MODX ou um pacote extra, agora ou num futuro próximo já não suporta esta versão. Por favor, atualize seu ambiente pelo menos para PHP [[+phprequired]] assim que possível para proteger seu site.';
$_lang['configcheck_register_globals'] = 'register_globals está ligado no seu arquivo de configuração php.ini';
$_lang['configcheck_register_globals_msg'] = 'Esta configuração faz seus site mais sucetível a ataques de Cross Site Scripting (XSS). Você deveria falar com seu provedor de hospedagem para saber se é possível desabilitar esta configuração.';
$_lang['configcheck_title'] = 'Checkagem de configuração';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'A página de Não Autorizado do seu site não está publicada ou não existe.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Isto significa que sua página de Não Autorizado não está acessível para navegantes normais ou não existe. Isto pode levar para um looping recursivo e vários erros nos relatórios do seu site. Tenha certeza que não há grupos de usuários associados a esta página.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'A página de Não Autorizado do seu website não está publicada ou não existe.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Isto siginifica que a sua página de Não Autorizado não está acessível ao público geral. Publique a sua página ou tenha certeza que está associado a um documento existente em sua árvore do site no menu Sistema &gt; Opções de Sistema.';
$_lang['configcheck_warning'] = 'Avisos de Configuração:';
$_lang['configcheck_what'] = 'O que isto siginifica?';

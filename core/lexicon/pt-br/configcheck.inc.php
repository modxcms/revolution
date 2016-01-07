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
$_lang['configcheck_cache_msg'] = 'MODx não pode escrever no diretório de cache. MODx vai continuar funcionando, mas não será feito cache. Para resolver isto faça com que o diretório /_cache/ ter permissão para escrita.';
$_lang['configcheck_configinc'] = 'Arquivo de configuração tem permissões de acesso!';
$_lang['configcheck_configinc_msg'] = 'Seu site está vulnerável a hackers que poderiam fazer um grande estrago para o site. Por favor faça com que seu arquivo de configuração seja apenas de leitura! Se você não é o administrador do site por favor, entre em contato com o administrador de sistemas e avise-o sobre esta mensagem! O arquivo está localizado em  core/config/config.inc.php';
$_lang['configcheck_default_msg'] = 'Um aviso não especificado não foi encontrado. O que é estranho.';
$_lang['configcheck_errorpage_unavailable'] = 'A pagina de erro do seu site não está disponível.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Isto significa que sua página de Erro não está acessível para navegantes normais ou não existe. Isto pode levar para um looping recursivo e vários erros nos relatórios do seu site. Tenha certeza que não há grupos de usuários associados a esta página.';
$_lang['configcheck_errorpage_unpublished'] = 'A página de erro do seu website não está publicada ou não existe.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Isto siginifica que a sua página de Erro não está acessível ao público geral. Publique a sua página ou tenha certeza que está associado a um documento existente em sua árvore do site no menu Sistema &gt; Opções de Sistema.';
$_lang['configcheck_htaccess'] = 'Pasta \'core\' é acessível pela web';
$_lang['configcheck_htaccess_msg'] = 'MODX detectou que sua pasta do núcleo (core) é (parcialmente) acessível ao público. <strong>Isso não é recomendado e é um risco de segurança.</strong> Se sua instalação de MODX está sendo executado em um servidor Web Apache você deve configurar pelo menos o arquivo .htaccess dentro o pasta núcleo <em>[[+fileLocation]]</em>. Isto pode ser feito facilmente, renomeando o arquivo de exemplo ht.access para .htaccess.<p>Existem outros métodos e servidores Web, caso utilize, por favor leia o <a href="https://rtfm.modx.com/revolution/2.x/administering-your-site/security/hardening-modx-revolution">Guia MODX de solidez</a> para mais informações sobre como proteger seu site.</p>Se você configurar tudo corretamente, por exemplo, navegando para o <a href="[[+checkUrl]]" target="_blank">Changelog</a> deve dar-lhe um 403 (permissão negada) ou melhor um 404 (não encontrado). Se você pode ver o changelog lá no navegador, algo está errado e você precisa reconfigurar ou chamar um especialista para resolver isso.';
$_lang['configcheck_images'] = 'Diretório de imagens não tem permissão de escrita';
$_lang['configcheck_images_msg'] = 'O diretório de imagens não tem permissões para escrita, ou não existe. Isto siginifica que as funções do Gerenciador de Imagens não funcionaram no editor!';
$_lang['configcheck_installer'] = 'Instalador ainda presente.';
$_lang['configcheck_installer_msg'] = 'O diretório setup/ ainda tem o instalador do MODx. Imagine o que pode acontecer se alguma pessoa má encontra esta pasta e roda o indalador! Ele provavelmente não vai conseguir ir muito longe porque ele terá que informar algumas informações de usuários presentes na base de dados, mas de qualquer maneira é melhor remover esta pasta do seu servidor.';
$_lang['configcheck_lang_difference'] = 'Número de entradas incorreto no arquivo de linguagem';
$_lang['configcheck_lang_difference_msg'] = 'A língua selecionada tem um número diferente de entradas que a língua padrão. Isto não é necessáriamente um problem, mas pode significar que o arquivo de linguagem deve ser atualizado.';
$_lang['configcheck_notok'] = 'Um ou mais detalhes de configuração não estão de acordo:';
$_lang['configcheck_ok'] = 'A configuração está OK - sem avisos para reportar.';
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

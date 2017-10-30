<?php
/**
 * Setting English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['area'] = 'Área';
$_lang['area_authentication'] = 'Autenticação e Segurança ';
$_lang['area_caching'] = 'Armazenamento em cache';
$_lang['area_core'] = 'Código do Núcleo';
$_lang['area_editor'] = 'Editor de Rich-Text';
$_lang['area_file'] = 'Sistema de arquivos';
$_lang['area_filter'] = 'Filtro por área...';
$_lang['area_furls'] = 'URL Amigável';
$_lang['area_gateway'] = 'Gateway';
$_lang['area_language'] = 'Léxico e Linguagem ';
$_lang['area_mail'] = 'E-mail';
$_lang['area_manager'] = 'Gestor de Back-end';
$_lang['area_phpthumb'] = 'phpThumb';
$_lang['area_proxy'] = 'Proxy';
$_lang['area_session'] = 'Sessão e Cookie';
$_lang['area_lexicon_string'] = 'Área da entrada de Léxico';
$_lang['area_lexicon_string_msg'] = 'Digite a chave da entrada do léxico para a área aqui. Se não houver nenhuma entrada léxico, ela só vai exibir a chave área <br /> Áreas Principais:. Autenticação, cache, arquivos, furls, gateway, língua, gerente de sessão, site, sistema';
$_lang['area_site'] = 'Site';
$_lang['area_system'] = 'Sistema e Servidor';
$_lang['areas'] = 'Áreas';
$_lang['charset'] = 'charset';
$_lang['country'] = 'País';
$_lang['description_desc'] = 'Uma breve descrição da configuração. Esta pode ser uma Entrada Léxico baseada na chave, de acordo com o formato "setting_" + tecla + "_desc" .';
$_lang['key_desc'] = 'A chave para o ajuste. Ele estará disponível em seu conteúdo através do marcador [[++key]].';
$_lang['name_desc'] = 'Uma nome para a configuração. Esta pode ser uma Entrada Léxico com base na chave, seguindo o formato "setting_" + chave.';
$_lang['namespace'] = 'namespace';
$_lang['namespace_desc'] = 'Espaço Nominal que esta configuração está associado. O tópico léxico padrão será carregado para este espaço nominal ao pegar Configurações.';
$_lang['namespace_filter'] = 'Filtrar por espaço nominal...';
$_lang['search_by_key'] = 'Pesquisa por chave...';
$_lang['setting_create'] = 'Criar Nova Configuração';
$_lang['setting_err'] = 'Por favor verifique os dados para os seguintes campos:';
$_lang['setting_err_ae'] = 'Configuração com essa chave já existe. Por favor, especifique outro nome de chave.';
$_lang['setting_err_nf'] = 'Configuração não encontrada.';
$_lang['setting_err_ns'] = 'A configuração não especificada';
$_lang['setting_err_remove'] = 'Ocorreu um erro ao tentar remover a configuração. ';
$_lang['setting_err_save'] = 'Ocorreu um erro ao tentar salvar a configuração.';
$_lang['setting_err_startint'] = 'Configurações não podem começar com um número inteiro.';
$_lang['setting_err_invalid_document'] = 'Não há nenhum documento com ID %d. Por favor, especifique um documento existente.';
$_lang['setting_remove'] = 'Excluir Configuração';
$_lang['setting_remove_confirm'] = 'Tem certeza de que deseja excluir essa configuração? Isso pode quebrar sua instalação MODX .';
$_lang['setting_update'] = 'Atualizar Configuração';
$_lang['settings_after_install'] = 'Como se trata de uma nova instalação, você é obrigado a controlar essas configurações, e mudar tudo o que possa desejar. Depois de ter controlado as configurações, pressione "Salvar" para atualizar o banco de dados de configurações. <br /> <br />';
$_lang['settings_desc'] = 'Aqui você pode definir preferências gerais e definições de configuração para a interface do gerenciador MODX, bem como a forma como o seu site MODX é executado. Clique duas vezes na coluna de valor para a configuração que deseja editar para editar de forma dinâmica através da grade, ou o botão direito do mouse em uma configuração para mais opções. Você também pode clicar no sinal de "+" para obter uma descrição da configuração.';
$_lang['settings_furls'] = 'URLs amigáveis';
$_lang['settings_misc'] = 'Diversos';
$_lang['settings_site'] = 'Site';
$_lang['settings_ui'] = 'Interface & Funcionalidades';
$_lang['settings_users'] = 'Usuário';
$_lang['system_settings'] = 'Configurações de Sistema';
$_lang['usergroup'] = 'Grupo de Usuário';

// user settings
$_lang['setting_access_category_enabled'] = 'Verificar Acesso à Categoria';
$_lang['setting_access_category_enabled_desc'] = 'Use para ativar ou desativar verificações de ACL da categoria (por Contexto). <strong> NOTA: Se esta opção estiver definida como não, então todas as permissões de acesso Categoria serão ignorados </ strong> ';

$_lang['setting_access_context_enabled'] = 'Verificar acesso ao contexto';
$_lang['setting_access_context_enabled_desc'] = 'Use para ativar ou desativar Contexto verificações de ACL. <strong> NOTA: Se esta opção estiver definida como não, então TODAS as permissões de Acesso à Contexto serão ignoradas. NÃO desative esse de todo o sistema ou para o contexto mgr ou você vai desabilitar o acesso à interface do gerenciador </strong>.';

$_lang['setting_access_resource_group_enabled'] = 'Verificar Acesso ao Grupo de Recursos ';
$_lang['setting_access_resource_group_enabled_desc'] = 'Use para ativar ou desativar de Recursos do Grupo verificações de ACL (por Contexto). <strong> NOTA: Se esta opção estiver definida como não, então TODAS as permissões Acesso à do grupo de recursos será ignorado </strong> ';

$_lang['setting_allow_mgr_access'] = 'Acesso à interface do gerenciador';
$_lang['setting_allow_mgr_access_desc'] = 'Selecione essa opção para ativar ou desativar o acesso à interface do gerenciador. <strong> NOTA:. Se esta opção estiver definida como não, então o usuário será redirecionado a sessão de inicialização do Gestor ou a página inicial do site </strong> ';

$_lang['setting_failed_login'] = 'Tentativas Frustradas de Login';
$_lang['setting_failed_login_desc'] = 'Aqui você pode digitar o número de tentativas fracassadas de login que são permitidas antes que um usuário seja bloqueado.';

$_lang['setting_login_allowed_days'] = 'Dias Autorizados ';
$_lang['setting_login_allowed_days_desc'] = 'Selecione os dias em que este usuário tem permissão para entrar. ';

$_lang['setting_login_allowed_ip'] = 'Endereços de IP Permitidos. ';
$_lang['setting_login_allowed_ip_desc'] = 'Digite os endereços IP que este usuário tem permissão para iniciar sessão . <strong> NOTA: Separe vários endereços IP com uma vírgula (,) </strong> ';

$_lang['setting_login_homepage'] = 'Página de Entrada';
$_lang['setting_login_homepage_desc'] = 'Digite o ID do documento que pretende enviar ao usuário depois que ele fez o acesso. <strong> NOTA: verifique se o ID que você inserir pertence a um documento existente, e que foi publicado e é acessível por este usuário! </strong> ';

// system settings
$_lang['setting_access_policies_version'] = 'Versão do Esquema de Política de Acesso';
$_lang['setting_access_policies_version_desc'] = 'A versão do sistema de Política de Acesso. NÃO MUDAR.';

$_lang['setting_allow_forward_across_contexts'] = 'Permitir o Encaminhamento Através de Contextos';
$_lang['setting_allow_forward_across_contexts_desc'] = 'Quando true,  Symlinks e chamadas de API modX::sendForward() pode encaminhar solicitações a Recursos em outros contextos.';

$_lang['setting_allow_manager_login_forgot_password'] = 'Permitir que o usuário recupere a senha na tela de login do Gestor';
$_lang['setting_allow_manager_login_forgot_password_desc'] = 'Configurando para "Não" irá desativar a capacidade "equeceu sua senha?" na tela de login do gestor.';

$_lang['setting_allow_tags_in_post'] = 'Permitir Tags em POST';
$_lang['setting_allow_tags_in_post_desc'] = 'Se for falso, todas as variáveis POST serão despojadas de tags HTML, entidades numéricas e tags MODX. MODX recomenda deixar este conjunto para false para contextos que não são o mgr, onde é definido como true por padrão.';

$_lang['setting_allow_tv_eval'] = 'Disable eval in TV binding';
$_lang['setting_allow_tv_eval_desc'] = 'Select this option to enable or disable eval in TV binding. If this option is set to no, the code/value will just be handled as regular text.';

$_lang['setting_anonymous_sessions'] = 'Sessões Anônimas';
$_lang['setting_anonymous_sessions_desc'] = 'Se desativado, somente usuários autenticados terão acesso a uma sessão PHP. Isto pode reduzir a sobrecarga para usuários anônimos e a carga que eles impõem um site MODX, se eles não precisam de acesso a uma sessão exclusiva. Se session_enabled for false, esta configuração não tem efeito como sessões nunca estaria disponíveis.';

$_lang['setting_archive_with'] = 'Força Arquivos PCLZip';
$_lang['setting_archive_with_desc'] = 'Se for verdade, vai usar PCLZip em vez de ZipArchive como a extensão zip. Ligue isso em se você está recebendo erros extractTo ou está tendo problemas com a descompactação em Gestão de pacotes.';

$_lang['setting_auto_menuindex'] = 'Indexação padrão de menu';
$_lang['setting_auto_menuindex_desc'] = 'Selecione "Sim" para ligar índice de menus automático incrementado por padrão.';

$_lang['setting_auto_check_pkg_updates'] = 'Verificação Automática de Atualizações de pacotes';
$_lang['setting_auto_check_pkg_updates_desc'] = 'Se "Sim", MODX irá automaticamente verificar se há atualizações para os pacotes no Gerenciamento de pacotes. Isso pode diminuir a carga da rede.';

$_lang['setting_auto_check_pkg_updates_cache_expire'] = 'Tempo de Expiração de Cache para verificação automática de atualizações de pacotes';
$_lang['setting_auto_check_pkg_updates_cache_expire_desc'] = 'O número de minutos que a Gerenciamento de Pacotes armazenará em cache os resultados para verificar se há atualizações de pacotes.';

$_lang['setting_allow_multiple_emails'] = 'Permitir emails duplicados para usuários';
$_lang['setting_allow_multiple_emails_desc'] = 'Se habilitada, os usuários podem compartilhar o mesmo endereço de e-mail.';

$_lang['setting_automatic_alias'] = 'Gerar automaticamente o alias';
$_lang['setting_automatic_alias_desc'] = 'Selecione "Sim" para que o sistema gere automaticamente um alias com base no título da página do Recurso ao salvar.';

$_lang['setting_base_help_url'] = 'URL Base da Ajuda ';
$_lang['setting_base_help_url_desc'] = 'A URL base através da qual serão construidos os links da Ajuda no canto superior direito das páginas do gestor.';

$_lang['setting_blocked_minutes'] = 'Minutos Bloqueados';
$_lang['setting_blocked_minutes_desc'] = 'Aqui você pode digitar o número de minutos que o usuário será bloqueado se atingir o número máximo permitido de tentativas de login. Por favor, insira este valor como apenas números (sem vírgulas, espaços e etc.) ';

$_lang['setting_cache_action_map'] = 'Ativar Cache para Mapa da Ação';
$_lang['setting_cache_action_map_desc'] = 'Quando habilitada, as ações (ou mapas do controlador) serão armazenados em cache para reduzir o tempo de carregamento da página gestor.';

$_lang['setting_cache_alias_map'] = 'Ativar o Cache de Mapa de Alias de Contexto';
$_lang['setting_cache_alias_map_desc'] = 'Quando ativado, todos os URIs de recursos são armazenados em cache no contexto. Habilitar em sites menores, e desativar em sites maiores para uma melhor performance.';

$_lang['setting_use_context_resource_table'] = 'Use the context resource table';
$_lang['setting_use_context_resource_table_desc'] = 'When enabled, context refreshes use the context_resource table. This enables you to programmatically have one resource in multiple contexts. If you do not use those multiple resource contexts via the API, you can set this to false. On large sites you will get a potential performance boost in the manager then.';

$_lang['setting_cache_context_settings'] = 'Ativar Cache para Configurações de Contexto.';
$_lang['setting_cache_context_settings_desc'] = 'Quando ativo, as configurações de contexto será armazenado em cache para reduzir o tempo de carga.';

$_lang['setting_cache_db'] = 'Ativar o Cache de Banco de Dados';
$_lang['setting_cache_db_desc'] = 'Quando habilitada, os objetos e conjuntos de resultados brutos de consultas SQL são armazenados em cache para reduzir significativamente as cargas de banco de dados.';

$_lang['setting_cache_db_expires'] = 'Tempo de Expiração para Cache de DB';
$_lang['setting_cache_db_expires_desc'] = 'Este valor (em segundos) define a quantidade de tempo que os arquivos de cache durar "conjunto de resultados" da DB em cache.';

$_lang['setting_cache_db_session'] = 'Ativar o Cache de Sessão de Banco de Dados';
$_lang['setting_cache_db_session_desc'] = 'Quando habilitado, e cache_db está ativado, as sessões de banco de dados serão armazenados em cache no cache do conjunto de resultados da DB.';

$_lang['setting_cache_db_session_lifetime'] = 'Tempo de expiração de cache de sessão do BD';
$_lang['setting_cache_db_session_lifetime_desc'] = 'Este valor (em segundos) define a quantidade de arquivos de cache tempo duram as entradas de sessão em cache do conjunto de resultados da BD.';

$_lang['setting_cache_default'] = 'Amazenável em cache por padrão';
$_lang['setting_cache_default_desc'] = 'Selecione \'Sim\' para todos os novos recursos serem amazenáveis em cache por padrão.';
$_lang['setting_cache_default_err'] = 'Por favor defina se quer ou não que documentos sejam armazenados em cache por padrão.';

$_lang['setting_cache_disabled'] = 'Desativar Opções de Cache Global';
$_lang['setting_cache_disabled_desc'] = 'Selecione \'Sim\' para desabilitar todos os recursos de cache do MODX. MODX não recomenda desabilitar o cache.';
$_lang['setting_cache_disabled_err'] = 'Por favor defina se quer ou não que o cache seja habilitado.';

$_lang['setting_cache_expires'] = 'Tempo padrão de expiração de Cache';
$_lang['setting_cache_expires_desc'] = 'Este valor (em segundos) define o tempo que os arquivos de cache duram para armazenamento padrão em cache.';

$_lang['setting_cache_format'] = 'Formato de Cache para Usar';
$_lang['setting_cache_format_desc'] = '0 = PHP, 1 = JSON, 2 = serializar. Um dos formatos';

$_lang['setting_cache_handler'] = 'Classe de Manipulador de Cache';
$_lang['setting_cache_handler_desc'] = 'O nome da classe do manipulador de tipo para usar para realizar o cache.';

$_lang['setting_cache_lang_js'] = 'Cache JS de Srings de Léxico';
$_lang['setting_cache_lang_js_desc'] = 'Se definido como verdadeiro, isso irá usar cabeçalhos de servidor para armazenar em cache as strings do léxico carregado em JavaScript para a interface do Gerenciador.';

$_lang['setting_cache_lexicon_topics'] = 'Cache de Tópicos de Léxico';
$_lang['setting_cache_lexicon_topics_desc'] = 'Quando habilitado, todos os tópicos do léxico serão armazenados em cache para reduzir significativamente o tempo de carregamento para a funcionalidade de internacionalização. MODX recomenda fortemente deixar esta definida como \'Sim\'.';

$_lang['setting_cache_noncore_lexicon_topics'] = 'Armazenar em cache tópicos léxico que não são do core';
$_lang['setting_cache_noncore_lexicon_topics_desc'] = 'Quando desativado, tópicos léxico que não são do core serão ser armazenados em cache. Isso é útil para desativar quando estiver desenvolvendo seus próprios Extras.';

$_lang['setting_cache_resource'] = 'Ativar Cache Parcial de Recurso';
$_lang['setting_cache_resource_desc'] = 'Cache parcial do recurso é configurável por recurso quando esta funcionalidade está habilitada. Desativar esta funcionalidade irá desativar de forma global.';

$_lang['setting_cache_resource_expires'] = 'Tempo de Expiração para Cache Parcial de Recurso';
$_lang['setting_cache_resource_expires_desc'] = 'Este valor (em segundos) define o tanto de tempo que os arquivos de cache irão durar para o cache parcial de Recurso.';

$_lang['setting_cache_scripts'] = 'Habilitar Cache de Script';
$_lang['setting_cache_scripts_desc'] = 'Quando habilitado, MODX irá realizar cache de todos os Scripts (Snippets e Plugins) para arquivos para reduzir os tempos de carregamento. MODX recomenda deixar este definido como \'Sim\'.';

$_lang['setting_cache_system_settings'] = 'Habilitar Cache de Configuração do Sistema';
$_lang['setting_cache_system_settings_desc'] = 'Quando habilitado, as configurações de sistema serão armazenadas em cache para reduzir os tempos de carregamento. MODX recomenda deixar habilitado.';

$_lang['setting_clear_cache_refresh_trees'] = 'Atualizar Árvores na Limpeza de Cache do Site';
$_lang['setting_clear_cache_refresh_trees_desc'] = 'Quando habilitado, as árvores serão atualizadas depois de limpar o cache do site.';

$_lang['setting_compress_css'] = 'Usar CSS Comprimido';
$_lang['setting_compress_css_desc'] = 'Quando essa opção estiver habilitada, MODX usará uma versão compactada de suas folhas de estilo css na interface do Manager. Isto reduz a carga e o tempo de execução dentro do painel. Desative apenas se você estiver modificando elementos do núcleo.';

$_lang['setting_compress_js'] = 'Usar bibliotecas JavaScript comprimidas';
$_lang['setting_compress_js_desc'] = 'Quando essa opção estiver habilitada, MODX usará uma versão compactada de suas folhas de estilo css na interface do Manager. Isto reduz a carga e o tempo de execução dentro do painel. Desative apenas se você estiver modificando elementos do núcleo.';

$_lang['setting_compress_js_groups'] = 'Agrupar quando estiver comprimindo JavaScript';
$_lang['setting_compress_js_groups_desc'] = 'Agrupar JavaScript do núcleo do Gerenciador MODX usando o groupsConfig do minify. Defina Sim se estiver usando suhosin ou outros fatores de limitação.';

$_lang['setting_compress_js_max_files'] = 'Limiar máximo de compressão de arquivos JavaScript';
$_lang['setting_compress_js_max_files_desc'] = 'O número máximo de arquivos JavaScript que MODX tentará comprimir quando compress_js está ligado. Defina como um número menor, se você estiver enfrentando problemas com Google Minify no Gerenciador MODX.';

$_lang['setting_concat_js'] = 'Usar Bibliotecas de Javascript Concatenadas';
$_lang['setting_concat_js_desc'] = 'Quando essa opção estiver habilitada, o MODX usará uma versão compactada de suas folhas de estilo css na interface do Manager. Isto reduz a carga e o tempo de execução dentro do painel. Desative apenas se você estiver modificando elementos do núcleo.';

$_lang['setting_confirm_navigation'] = 'Confirmar a navegação com alterações não salvas';
$_lang['setting_confirm_navigation_desc'] = 'Quando essa opção estiver habilitada, o usuário será solicitado a confirmar a sua intenção, caso existam alterações não salvas.';

$_lang['setting_container_suffix'] = 'Sufixo do Container';
$_lang['setting_container_suffix_desc'] = 'O sufixo para acrescentar aos Recursos definidos como recipientes quando usando FURLs.';

$_lang['setting_context_tree_sort'] = 'Habilitar a Ordenação de Contextos na Árvore de Recursos';
$_lang['setting_context_tree_sort_desc'] = 'Se definida como Sim, contextos serão alfanumericamente classificados na árvore de Recursos da esquerda.';
$_lang['setting_context_tree_sortby'] = 'Ordenar Campos de Contextos na Árvore de Documentos';
$_lang['setting_context_tree_sortby_desc'] = 'O campo para ordenar Contextos na Árvore de Recursos, se a ordenação está habilitada.';
$_lang['setting_context_tree_sortdir'] = 'Direção da Ordenação de Contextos na Árvore de Recursos';
$_lang['setting_context_tree_sortdir_desc'] = 'A direção a ordenar Contextos na Árvore de Recursos, se a ordenação está habilitada.';

$_lang['setting_cultureKey'] = 'Língua';
$_lang['setting_cultureKey_desc'] = 'Selecione o idioma para todos os contextos que não são o manager, incluindo web.';

$_lang['setting_date_timezone'] = 'Fuso Horário Padrão';
$_lang['setting_date_timezone_desc'] = 'Controla o fuso horário padrão para funções de data PHP, se não está vazio. Se não estiver definido e a configuração date.timezone no PHP.ini em seu ambiente, será assumido UTC.';

$_lang['setting_debug'] = 'Depurar';
$_lang['setting_debug_desc'] = 'Controls turning debugging on/off in MODX and/or sets the PHP error_reporting level. \'\' = use current error_reporting, \'0\' = false (error_reporting = 0), \'1\' = true (error_reporting = -1), or any valid error_reporting value (as an integer).';

$_lang['setting_default_content_type'] = 'Tipo de Conteúdo Padrão';
$_lang['setting_default_content_type_desc'] = 'Selecione o padrão de tipo de conteúdo você deseja usar para novos recursos. Você ainda pode selecionar um tipo diferente de conteúdo no editor de recurso; Essa configuração só pré-seleciona um de seus tipos de conteúdo para você.';

$_lang['setting_default_duplicate_publish_option'] = 'Opção de Publicação Padrão para Duplicata de Recurso';
$_lang['setting_default_duplicate_publish_option_desc'] = 'A opção padrão selecionada quando um Recurso é duplicado. Pode ser "despublicar" para cancelar a publicação de todas as duplicatas ou "publicar" para publicar todas as duplicatas ou "preservar" para preservar o estado de publicação, com base no recurso de duplicado.';

$_lang['setting_default_media_source'] = 'Fonte de Mídia Padrão';
$_lang['setting_default_media_source_desc'] = 'A Fonte de Mídia padrão para carregar.';

$_lang['setting_default_template'] = 'Template Padrão';
$_lang['setting_default_template_desc'] = 'Select the default Template you wish to use for new Resources. You can still select a different template in the Resource editor, this setting just pre-selects one of your Templates for you.';

$_lang['setting_default_per_page'] = 'Padrão de Por Página';
$_lang['setting_default_per_page_desc'] = 'O número padrão de resultados para mostrar em grades em todo o Gerenciador.';

$_lang['setting_editor_css_path'] = 'Caminho para o arquivo CSS';
$_lang['setting_editor_css_path_desc'] = 'Enter the path to your CSS file that you wish to use within a richtext editor. The best way to enter the path is to enter the path from the root of your server, for example: /assets/site/style.css. If you do not wish to load a style sheet into a richtext editor, leave this field blank.';

$_lang['setting_editor_css_selectors'] = 'Seletores CSS para o Editor';
$_lang['setting_editor_css_selectors_desc'] = 'Uma lista separada por vírgulas de seletores CSS para um editor texto formatado.';

$_lang['setting_emailsender'] = 'Remetente do Email de Saudação de Registro';
$_lang['setting_emailsender_desc'] = 'Aqui você pode especificar o e-mail endereço usado quando os Usuários recebem seus nomes de usuário e senhas.';
$_lang['setting_emailsender_err'] = 'Por favor, indique o endereço de e-mail da administração.';

$_lang['setting_emailsubject'] = 'Assunto do E-mail de Registo';
$_lang['setting_emailsubject_desc'] = 'O assunto para o e-mail de inscrição padrão quando um usuário é cadastrado.';
$_lang['setting_emailsubject_err'] = 'Por favor, indique a linha de assunto para o e-mail de inscrição.';

$_lang['setting_enable_dragdrop'] = 'Habilitar Arrastar e Soltar em árvores de Elemento/Recurso';
$_lang['setting_enable_dragdrop_desc'] = 'Se desabilitado, impedirá arrastar e soltar em árvores de Recurso e Elemento.';

$_lang['setting_error_page'] = 'Página de Erro';
$_lang['setting_error_page_desc'] = 'Enter the ID of the document you want to send users to if they request a document which doesn\'t actually exist. <strong>NOTE: make sure this ID you enter belongs to an existing document, and that it has been published!</strong>';
$_lang['setting_error_page_err'] = 'Por favor, especifique uma ID do documento para a página de erro.';

$_lang['setting_ext_debug'] = 'Depurar ExtJS';
$_lang['setting_ext_debug_desc'] = 'Whether or not to load ext-all-debug.js to help debug your ExtJS code.';

$_lang['setting_extension_packages'] = 'Pacotes de Extensão';
$_lang['setting_extension_packages_desc'] = 'A JSON array of packages to load on MODX instantiation. In the format [{"packagename":{"path":"path/to/package"}},{"anotherpackagename":{"path":"path/to/otherpackage"}}]';

$_lang['setting_enable_gravatar'] = 'Ativar o Gravatar';
$_lang['setting_enable_gravatar_desc'] = 'Se ativo, Gravatar será usado como uma imagem de perfil (se o usuário não tem a foto de perfil carregada).';

$_lang['setting_failed_login_attempts'] = 'Tentativas Frustradas de Login';
$_lang['setting_failed_login_attempts_desc'] = 'The number of failed login attempts a User is allowed before becoming \'blocked\'.';

$_lang['setting_fe_editor_lang'] = 'Idioma do Editor Front-end';
$_lang['setting_fe_editor_lang_desc'] = 'Escolha um idioma para o editor para usar quando usado como um editor no front-end.';

$_lang['setting_feed_modx_news'] = 'URL do Feed de Notícias do MODX';
$_lang['setting_feed_modx_news_desc'] = 'Defina o URL do feed RSS para o painel de notícias do MODX no Gerenciador.';

$_lang['setting_feed_modx_news_enabled'] = 'Feed de Notícias do MODX habilitado';
$_lang['setting_feed_modx_news_enabled_desc'] = 'Se \'Não\', o MODX irá esconder o Feed de Notícias na seção de boas-vindas do gerenciador.';

$_lang['setting_feed_modx_security'] = 'URL do Feed de Avisos de Segurança do MODX';
$_lang['setting_feed_modx_security_desc'] = 'Set the URL for the RSS feed for the MODX Security Notices panel in the manager.';

$_lang['setting_feed_modx_security_enabled'] = 'Feed de Segurança MODX habilitado';
$_lang['setting_feed_modx_security_enabled_desc'] = 'Se \'Não\', o MODX irá esconder o Feed de Segurança na seção de boas-vindas do gerenciador.';

$_lang['setting_filemanager_path'] = 'Caminho do Gerenciador de Arquivos (obsoleto)';
$_lang['setting_filemanager_path_desc'] = 'Deprecated - use Media Sources instead. IIS often does not populate the document_root setting properly, which is used by the file manager to determine what you can look at. If you\'re having problems using the file manager, make sure this path points to the root of your MODX installation.';

$_lang['setting_filemanager_path_relative'] = 'O Gerenciador de Arquivos é de Caminho Relativo? (Obsoleto)';
$_lang['setting_filemanager_path_relative_desc'] = 'Deprecated - use Media Sources instead. If your filemanager_path setting is relative to the MODX base_path, then please set this setting to Yes. If your filemanager_path is outside the docroot, set this to No.';

$_lang['setting_filemanager_url'] = 'Url para o Gerenciador de arquivos (obsoleto)';
$_lang['setting_filemanager_url_desc'] = 'Deprecated - use Media Sources instead. Optional. Set this if you want to set an explicit URL to access the files in the MODX file manager from (useful if you have changed filemanager_path to a path outside the MODX webroot). Make sure this is the web-accessible URL of the filemanager_path setting value. If you leave this empty, MODX will try to automatically calculate it.';

$_lang['setting_filemanager_url_relative'] = 'O Gerenciador de Arquivos é de URL Relativa? (Obsoleto)';
$_lang['setting_filemanager_url_relative_desc'] = 'Deprecated - use Media Sources instead. If your filemanager_url setting is relative to the MODX base_url, then please set this setting to Yes. If your filemanager_url is outside the main webroot, set this to No.';

$_lang['setting_forgot_login_email'] = 'Email para Login Esquecido';
$_lang['setting_forgot_login_email_desc'] = 'The template for the email that is sent when a user has forgotten their MODX username and/or password.';

$_lang['setting_form_customization_use_all_groups'] = 'Use All User Group Memberships for Form Customization';
$_lang['setting_form_customization_use_all_groups_desc'] = 'If set to true, FC will use *all* Sets for *all* User Groups a member is in when applying Form Customization Sets. Otherwise, it will only use the Set belonging to the User\'s Primary Group. Note: setting this to Yes might cause bugs with conflicting FC Sets.';

$_lang['setting_forward_merge_excludes'] = 'sendForward Exclude Fields on Merge';
$_lang['setting_forward_merge_excludes_desc'] = 'A Symlink merges non-empty field values over the values in the target Resource; using this comma-delimited list of excludes prevents specified fields from being overridden by the Symlink.';

$_lang['setting_friendly_alias_lowercase_only'] = 'FURL Aliases em minúsculo';
$_lang['setting_friendly_alias_lowercase_only_desc'] = 'Determines whether to allow only lowercase characters in a Resource alias.';

$_lang['setting_friendly_alias_max_length'] = 'Comprimento Máximo de Alias FURL';
$_lang['setting_friendly_alias_max_length_desc'] = 'Se maior que zero, o número máximo de caracteres para permitir em um alias de um Recurso. Zero é igual a ilimitada.';

$_lang['setting_friendly_alias_realtime'] = 'FURL Alias em tempo real';
$_lang['setting_friendly_alias_realtime_desc'] = 'Determina se um alias de recursos deve ser criado na hora ao digitar o título da página, ou se isso deve acontecer quando o recurso é salvo (automatic_alias precisa ser habilitado para que isto tenha efeito).';

$_lang['setting_friendly_alias_restrict_chars'] = 'FURL Alias Character Restriction Method';
$_lang['setting_friendly_alias_restrict_chars_desc'] = 'The method used to restrict characters used in a Resource alias. "pattern" allows a RegEx pattern to be provided, "legal" allows any legal URL characters, "alpha" allows only letters of the alphabet, and "alphanumeric" allows only letters and numbers.';

$_lang['setting_friendly_alias_restrict_chars_pattern'] = 'FURL Alias Character Restriction Pattern';
$_lang['setting_friendly_alias_restrict_chars_pattern_desc'] = 'A valid RegEx pattern for restricting characters used in a Resource alias.';

$_lang['setting_friendly_alias_strip_element_tags'] = 'FURL Alias Strip Element Tags';
$_lang['setting_friendly_alias_strip_element_tags_desc'] = 'Determines if Element tags should be stripped from a Resource alias.';

$_lang['setting_friendly_alias_translit'] = 'FURL Alias Transcrição';
$_lang['setting_friendly_alias_translit_desc'] = 'O método de transliteração para usar em um alias especificado para um recurso. Vazio ou "none" é o padrão que ignora a transliteração. Outros valores possíveis são "iconv" (se disponível) ou uma tabela de transliteração nomeado fornecida por uma classe de serviço personalizado de transliteração.';

$_lang['setting_friendly_alias_translit_class'] = 'Classe de Serviço da Transliteração de Alias FURL';
$_lang['setting_friendly_alias_translit_class_desc'] = 'Uma classe de serviço opcional de prestação de serviços de transliteração nomeado para geração/filtragem de Alias FURL.';

$_lang['setting_friendly_alias_translit_class_path'] = 'FURL Alias Transliteration Service Class Path';
$_lang['setting_friendly_alias_translit_class_path_desc'] = 'The model package location where the FURL Alias Transliteration Service Class will be loaded from.';

$_lang['setting_friendly_alias_trim_chars'] = 'FURL Alias Caracteres Corte';
$_lang['setting_friendly_alias_trim_chars_desc'] = 'Caracteres para cortar as extremidades de um alias de recurso fornecido.';

$_lang['setting_friendly_alias_word_delimiter'] = 'FURL Alias Delimitador de Palavras';
$_lang['setting_friendly_alias_word_delimiter_desc'] = 'The preferred word delimiter for friendly URL alias slugs.';

$_lang['setting_friendly_alias_word_delimiters'] = 'FURL Alias Delimitadores de Palavras';
$_lang['setting_friendly_alias_word_delimiters_desc'] = 'Characters which represent word delimiters when processing friendly URL alias slugs. These characters will be converted and consolidated to the preferred FURL alias word delimiter.';

$_lang['setting_friendly_urls'] = 'Usar URLs amigáveis';
$_lang['setting_friendly_urls_desc'] = 'This allows you to use search engine friendly URLs with MODX. Please note, this only works for MODX installations running on Apache, and you\'ll need to write an .htaccess file for this to work. See the .htaccess file included in the distribution for more info.';
$_lang['setting_friendly_urls_err'] = 'Please state whether or not you want to use friendly URLs.';

$_lang['setting_friendly_urls_strict'] = 'Usar URLs amigáveis estritas';
$_lang['setting_friendly_urls_strict_desc'] = 'When friendly URLs are enabled, this option forces non-canonical requests that match a Resource to 301 redirect to the canonical URI for that Resource. WARNING: Do not enable if you use custom rewrite rules which do not match at least the beginning of the canonical URI. For example, a canonical URI of foo/ with custom rewrites for foo/bar.html would work, but attempts to rewrite bar/foo.html as foo/ would force a redirect to foo/ with this option enabled.';

$_lang['setting_global_duplicate_uri_check'] = 'Verificar por Duplicatas de URIs em Todos os Contextos';
$_lang['setting_global_duplicate_uri_check_desc'] = 'Select \'Yes\' to make duplicate URI checks include all Contexts in the search. Otherwise, only the Context the Resource is being saved in is checked.';

$_lang['setting_hidemenu_default'] = 'Esconder Menus por Padrão';
$_lang['setting_hidemenu_default_desc'] = 'Select \'Yes\' to make all new resources hidden from menus by default.';

$_lang['setting_inline_help'] = 'Mostrar Texto de Ajuda in-line para Campos';
$_lang['setting_inline_help_desc'] = 'If \'Yes\', then fields will display their help text directly below the field. If \'No\', all fields will have tooltip-based help.';

$_lang['setting_link_tag_scheme'] = 'Esquema de geração de URL';
$_lang['setting_link_tag_scheme_desc'] = 'Esquema de geração de URL para a tag [[~id]]. Opções disponíveis <a href="http://api.modx.com/revolution/2.2/db_core_model_modx_modx.class.html#\\modX::makeUrl()" target="_blank">aqui</a>.';

$_lang['setting_locale'] = 'Localidade';
$_lang['setting_locale_desc'] = 'Defina a localidade do sistema. Deixe em branco para usar o padrão. Consulte <a href="http://php.net/setlocale" target="_blank"> a documentação do PHP</a> para mais informações.';

$_lang['setting_lock_ttl'] = 'Tempo de Vida da Trava';
$_lang['setting_lock_ttl_desc'] = 'The number of seconds a lock on a Resource will remain for if the user is inactive.';

$_lang['setting_log_level'] = 'Nível de Relatório';
$_lang['setting_log_level_desc'] = 'The default logging level; the lower the level, the fewer messages that are logged. Available options: 0 (FATAL), 1 (ERROR), 2 (WARN), 3 (INFO), and 4 (DEBUG).';

$_lang['setting_log_target'] = 'Destino do log';
$_lang['setting_log_target_desc'] = 'The default logging target where log messages are written. Available options: \'FILE\', \'HTML\', or \'ECHO\'. Default is \'FILE\' if not specified.';

$_lang['setting_mail_charset'] = 'Charset do E-mail';
$_lang['setting_mail_charset_desc'] = 'O charset padrão para e-mails, por exemplo, \'iso-8859-1\' ou \'utf-8\'';

$_lang['setting_mail_encoding'] = 'Codificação de Email';
$_lang['setting_mail_encoding_desc'] = 'Define a codificação da mensagem. Opções são "8 bit", "7 bits", "binary", "base64" e "quoted-printable".';

$_lang['setting_mail_use_smtp'] = 'Usar SMTP';
$_lang['setting_mail_use_smtp_desc'] = 'Se habilitado, MODX tentará usar SMTP em funções de e-mail.';

$_lang['setting_mail_smtp_auth'] = 'Autenticação SMTP';
$_lang['setting_mail_smtp_auth_desc'] = 'Sets SMTP authentication. Utilizes the mail_smtp_user and mail_smtp_pass settings.';

$_lang['setting_mail_smtp_helo'] = 'Mensagem de Boas Vindas SMTP';
$_lang['setting_mail_smtp_helo_desc'] = 'Sets the SMTP HELO of the message (Defaults to the hostname).';

$_lang['setting_mail_smtp_hosts'] = 'Hosts SMTP';
$_lang['setting_mail_smtp_hosts_desc'] = 'Sets the SMTP hosts.  All hosts must be separated by a semicolon.  You can also specify a different port for each host by using this format: [hostname:port] (e.g., "smtp1.example.com:25;smtp2.example.com"). Hosts will be tried in order.';

$_lang['setting_mail_smtp_keepalive'] = 'Keep-Alive de SMTP';
$_lang['setting_mail_smtp_keepalive_desc'] = 'Prevents the SMTP connection from being closed after each mail sending. Not recommended.';

$_lang['setting_mail_smtp_pass'] = 'Senha do SMTP';
$_lang['setting_mail_smtp_pass_desc'] = 'A senha para autenticar no SMTP.';

$_lang['setting_mail_smtp_port'] = 'Porta SMTP';
$_lang['setting_mail_smtp_port_desc'] = 'Define a porta padrão do servidor SMTP.';

$_lang['setting_mail_smtp_prefix'] = 'Prefixo da Conexão SMTP';
$_lang['setting_mail_smtp_prefix_desc'] = 'Define o prefixo de conexão. As opções são "", "ssl" ou "tls"';

$_lang['setting_mail_smtp_single_to'] = 'Destinatário Individual SMTP';
$_lang['setting_mail_smtp_single_to_desc'] = 'Provides the ability to have the TO field process individual emails, instead of sending to entire TO addresses.';

$_lang['setting_mail_smtp_timeout'] = 'Tempo limite para SMTP';
$_lang['setting_mail_smtp_timeout_desc'] = 'Sets the SMTP server timeout in seconds. This function will not work in win32 servers.';

$_lang['setting_mail_smtp_user'] = 'Usuário SMTP';
$_lang['setting_mail_smtp_user_desc'] = 'O usuário para autenticar no servidor SMTP.';

$_lang['setting_main_nav_parent'] = 'Pai do menu principal';
$_lang['setting_main_nav_parent_desc'] = 'O recipiente usado para puxar todos os registros para o menu principal.';

$_lang['setting_manager_direction'] = 'Direção do texto do Gerenciador';
$_lang['setting_manager_direction_desc'] = 'Escolha a direção que o texto será processado no Gerenciador, à esquerda para a direita ou da direita para a esquerda.';

$_lang['setting_manager_date_format'] = 'Formato de Data no Gerenciador';
$_lang['setting_manager_date_format_desc'] = 'A formato seqüência de caracteres, no formato de date() do PHP, nas datas representadas no Gerenciador.';

$_lang['setting_manager_favicon_url'] = 'URL do Favicon do Gerenciador';
$_lang['setting_manager_favicon_url_desc'] = 'Se definido, carregará essa URL como um favicon para o Gerenciador MODX. Deve ser um URL relativo para o diretório manager/, ou uma URL absoluta.';

$_lang['setting_manager_js_cache_file_locking'] = 'Enable File Locking for Manager JS/CSS Cache';
$_lang['setting_manager_js_cache_file_locking_desc'] = 'Arquivo de cache bloqueado. Definida para Não, se o sistema de arquivos for NFS.';
$_lang['setting_manager_js_cache_max_age'] = 'Manager JS/CSS Compression Cache Age';
$_lang['setting_manager_js_cache_max_age_desc'] = 'Maximum age of browser cache of manager CSS/JS compression in seconds. After this period, the browser will send another conditional GET. Use a longer period for lower traffic.';
$_lang['setting_manager_js_document_root'] = 'Manager JS/CSS Compression Document Root';
$_lang['setting_manager_js_document_root_desc'] = 'If your server does not handle the DOCUMENT_ROOT server variable, set it explicitly here to enable the manager CSS/JS compression. Do not change this unless you know what you are doing.';
$_lang['setting_manager_js_zlib_output_compression'] = 'Permitir a saída de compressão de zlib para o JS/CSS do Gerenciador';
$_lang['setting_manager_js_zlib_output_compression_desc'] = 'Whether or not to enable zlib output compression for compressed CSS/JS in the manager. Do not turn this on unless you are sure the PHP config variable zlib.output_compression can be set to 1. MODX recommends leaving it off.';

$_lang['setting_manager_lang_attribute'] = 'Atributo de idioma HTML e XML do Gerenciador';
$_lang['setting_manager_lang_attribute_desc'] = 'Enter the language code that best fits with your chosen manager language, this will ensure that the browser can present content in the best format for you.';

$_lang['setting_manager_language'] = 'Idioma do Gerenciador';
$_lang['setting_manager_language_desc'] = 'Selecione o idioma para o Gerenciador de Conteúdo de MODX.';

$_lang['setting_manager_login_url_alternate'] = 'URL alternativa de Login no Gerenciador';
$_lang['setting_manager_login_url_alternate_desc'] = 'An alternate URL to send an unauthenticated user to when they need to login to the manager. The login form there must login the user to the "mgr" context to work.';

$_lang['setting_manager_login_start'] = 'Página inicial do Gerenciador';
$_lang['setting_manager_login_start_desc'] = 'Enter the ID of the document you want to send the user to after he/she has logged into the manager. <strong>NOTE: make sure the ID you\'ve entered belongs to an existing document, and that it has been published and is accessible by this user!</strong>';

$_lang['setting_manager_theme'] = 'Tema do Gerenciador';
$_lang['setting_manager_theme_desc'] = 'Select the Theme for the Content Manager.';

$_lang['setting_manager_time_format'] = 'Manager Time Format';
$_lang['setting_manager_time_format_desc'] = 'The format string, in PHP date() format, for the time settings represented in the manager.';

$_lang['setting_manager_use_tabs'] = 'Usar guias no Layout do Gerenciador';
$_lang['setting_manager_use_tabs_desc'] = 'Se habilitado, o Gerenciador vai usar guias para renderizar os painéis de conteúdo. Caso contrário, ele irá usar portais.';

$_lang['setting_manager_week_start'] = 'Início da semana';
$_lang['setting_manager_week_start_desc'] = 'Defina o dia que começa a semana. Use 0 (ou deixe em branco) para domingo, 1 na segunda e assim por diante...';

$_lang['setting_mgr_tree_icon_context'] = 'Ícone de árvore de contexto';
$_lang['setting_mgr_tree_icon_context_desc'] = 'Defina uma classe CSS aqui para ser usado para exibir o ícone de contexto na árvore. Você pode usar essa configuração em cada contexto para personalizar o ícone por contexto.';

$_lang['setting_mgr_source_icon'] = 'Ícone de Fonte de Mídia';
$_lang['setting_mgr_source_icon_desc'] = 'Indica uma classe CSS a ser usado para exibir os ícones de Fontes de Mídia na árvore de arquivos. O padrão é "icon-folder-open-o"';

$_lang['setting_modRequest.class'] = 'Request Handler Class';
$_lang['setting_modRequest.class_desc'] = '';

$_lang['setting_modx_browser_tree_hide_files'] = 'Esconder Arquivos do Navegador de Mídia';
$_lang['setting_modx_browser_tree_hide_files_desc'] = 'Se verdadeiro os arquivos dentro de pastas não são exibidos na árvore do navegador de fonte de mídia. O padrão é falso.';

$_lang['setting_modx_browser_tree_hide_tooltips'] = 'Ocultar Dicas de Ferramentas da Árvore do Navegador de Mídia';
$_lang['setting_modx_browser_tree_hide_tooltips_desc'] = 'Se verdadeiro, não serão exibidas visualização de imagem e dicas de ferramentas quando passando o mouse sobre um arquivo na árvore do navegador de mídia. O padrão é verdadeiro.';

$_lang['setting_modx_browser_default_sort'] = 'Classificação padrão do Navegador de Mídia';
$_lang['setting_modx_browser_default_sort_desc'] = 'O método de classificação padrão ao usar o Navegador de Mídia no Gerenciador. Os valores disponíveis são: name (nome), size (tamanho), lastmod (ultima modificação).';

$_lang['setting_modx_browser_default_viewmode'] = 'Modo de Exibição padrão do Navegador de Mídia';
$_lang['setting_modx_browser_default_viewmode_desc'] = 'O modo de exibição padrão ao usar o Navegador de Mídia no Gerenciador. Os valores disponíveis são: grid (grade), list (lista).';

$_lang['setting_modx_charset'] = 'Codificação de Carateres';
$_lang['setting_modx_charset_desc'] = 'Por favor selecione qual codificação de caracteres que você deseja usar. Por favor, note que MODX foi testado com um número destas codificações, mas não todas elas. Para a maioria dos idiomas, a configuração padrão de UTF-8 é recomendada.';

$_lang['setting_new_file_permissions'] = '\'Permissões de Novos Arquivos';
$_lang['setting_new_file_permissions_desc'] = 'When uploading a new file in the File Manager, the File Manager will attempt to change the file permissions to those entered in this setting. This may not work on some setups, such as IIS, in which case you will need to manually change the permissions.';

$_lang['setting_new_folder_permissions'] = 'New Folder Permissions';
$_lang['setting_new_folder_permissions_desc'] = 'When creating a new folder in the File Manager, the File Manager will attempt to change the folder permissions to those entered in this setting. This may not work on some setups, such as IIS, in which case you will need to manually change the permissions.';

$_lang['setting_parser_recurse_uncacheable'] = 'Atrasar Análise de Recursos Sem Cache';
$_lang['setting_parser_recurse_uncacheable_desc'] = 'Se desativado, elementos que não tem cache podem ter sua saída em cache dentro do conteúdo do elemento armazenáveis em cache. Desabilite esta SOMENTE se você está tendo problemas com a complexa análise aninhada que pararam de funcionar como esperado.';

$_lang['setting_password_generated_length'] = 'Password Auto-Generated Length';
$_lang['setting_password_generated_length_desc'] = 'The length of the auto-generated password for a User.';

$_lang['setting_password_min_length'] = 'Minimum Password Length';
$_lang['setting_password_min_length_desc'] = 'The minimum length for a password for a User.';

$_lang['setting_preserve_menuindex'] = 'Preservar o Índice de Menu Quando Duplicar Recursos';
$_lang['setting_preserve_menuindex_desc'] = 'Quando duplicar Recursos, a ordem do menu também será preservada.';

$_lang['setting_principal_targets'] = 'ACL Targets to Load';
$_lang['setting_principal_targets_desc'] = 'Customize the ACL targets to load for MODX Users.';

$_lang['setting_proxy_auth_type'] = 'Tipo de Autenticação do Proxy';
$_lang['setting_proxy_auth_type_desc'] = 'Suporta BASIC ou NTLM.';

$_lang['setting_proxy_host'] = 'Servidor Proxy';
$_lang['setting_proxy_host_desc'] = 'Se seu servidor estiver usando um proxy, defina o nome de host para habilitar os recursos MODX que talvez precise usar o proxy, como o gerenciamento de pacotes.';

$_lang['setting_proxy_password'] = 'Senha do Proxy';
$_lang['setting_proxy_password_desc'] = 'A senha necessária para autenticar para o servidor proxy.';

$_lang['setting_proxy_port'] = 'Porta do Proxy';
$_lang['setting_proxy_port_desc'] = 'A porta para o servidor proxy.';

$_lang['setting_proxy_username'] = 'Nome de usuário Proxy';
$_lang['setting_proxy_username_desc'] = 'O nome do usuário para autenticar contra com seu servidor de proxy.';

$_lang['setting_photo_profile_source'] = 'Fonte de Mídia da Foto de Usuário';
$_lang['setting_photo_profile_source_desc'] = 'A Fonte de Mídia usada para armazenar fotos de perfis de usuários. O padrão é a fonte de mídia padrão.';

$_lang['setting_phpthumb_allow_src_above_docroot'] = 'phpThumb Permitir src Acima do Documento Raiz';
$_lang['setting_phpthumb_allow_src_above_docroot_desc'] = 'Indica se o caminho de src é permitido fora a raiz do documento. Isso é útil para implantações de multi-contexto com vários hosts virtuais.';

$_lang['setting_phpthumb_cache_maxage'] = 'Idade Máxima do Cache phpThumb';
$_lang['setting_phpthumb_cache_maxage_desc'] = 'Delete cached thumbnails that have not been accessed in more than X days.';

$_lang['setting_phpthumb_cache_maxsize'] = 'Tamanho Máximo do Cache phpThumb';
$_lang['setting_phpthumb_cache_maxsize_desc'] = 'Delete least-recently-accessed thumbnails when cache grows bigger than X megabytes in size.';

$_lang['setting_phpthumb_cache_maxfiles'] = 'phpThumb Max Cache Files';
$_lang['setting_phpthumb_cache_maxfiles_desc'] = 'Delete least-recently-accessed thumbnails when cache has more than X files.';

$_lang['setting_phpthumb_cache_source_enabled'] = 'phpThumb Cache Source Files';
$_lang['setting_phpthumb_cache_source_enabled_desc'] = 'Whether or not to cache source files as they are loaded. Recommended to off.';

$_lang['setting_phpthumb_document_root'] = 'PHPThumb Document Root';
$_lang['setting_phpthumb_document_root_desc'] = 'Set this if you are experiencing issues with the server variable DOCUMENT_ROOT, or getting errors with OutputThumbnail or !is_resource. Set it to the absolute document root path you would like to use. If this is empty, MODX will use the DOCUMENT_ROOT server variable.';

$_lang['setting_phpthumb_error_bgcolor'] = 'phpThumb Error Background Color';
$_lang['setting_phpthumb_error_bgcolor_desc'] = 'A hex value, without the #, indicating a background color for phpThumb error output.';

$_lang['setting_phpthumb_error_fontsize'] = 'phpThumb Error Font Size';
$_lang['setting_phpthumb_error_fontsize_desc'] = 'An em value indicating a font size to use for text appearing in phpThumb error output.';

$_lang['setting_phpthumb_error_textcolor'] = 'phpThumb Error Font Color';
$_lang['setting_phpthumb_error_textcolor_desc'] = 'A hex value, without the #, indicating a font color for text appearing in phpThumb error output.';

$_lang['setting_phpthumb_far'] = 'phpThumb Force Aspect Ratio';
$_lang['setting_phpthumb_far_desc'] = 'The default far setting for phpThumb when used in MODX. Defaults to C to force aspect ratio toward the center.';

$_lang['setting_phpthumb_imagemagick_path'] = 'phpThumb ImageMagick Path';
$_lang['setting_phpthumb_imagemagick_path_desc'] = 'Optional. Set an alternative ImageMagick path here for generating thumbnails with phpThumb, if it is not in the PHP default.';

$_lang['setting_phpthumb_nohotlink_enabled'] = 'phpThumb Hotlinking Disabled';
$_lang['setting_phpthumb_nohotlink_enabled_desc'] = 'Remote servers are allowed in the src parameter unless you disable hotlinking in phpThumb.';

$_lang['setting_phpthumb_nohotlink_erase_image'] = 'phpThumb Hotlinking Erase Image';
$_lang['setting_phpthumb_nohotlink_erase_image_desc'] = 'Indicates if an image generated from a remote server should be erased when not allowed.';

$_lang['setting_phpthumb_nohotlink_text_message'] = 'phpThumb Hotlinking Not Allowed Message';
$_lang['setting_phpthumb_nohotlink_text_message_desc'] = 'A message that is rendered instead of the thumbnail when a hotlinking attempt is rejected.';

$_lang['setting_phpthumb_nohotlink_valid_domains'] = 'phpThumb Hotlinking Valid Domains';
$_lang['setting_phpthumb_nohotlink_valid_domains_desc'] = 'A comma-delimited list of hostnames that are valid in src URLs.';

$_lang['setting_phpthumb_nooffsitelink_enabled'] = 'phpThumb Offsite Linking Disabled';
$_lang['setting_phpthumb_nooffsitelink_enabled_desc'] = 'Disables the ability for others to use phpThumb to render images on their own sites.';

$_lang['setting_phpthumb_nooffsitelink_erase_image'] = 'phpThumb Offsite Linking Erase Image';
$_lang['setting_phpthumb_nooffsitelink_erase_image_desc'] = 'Indicates if an image linked from a remote server should be erased when not allowed.';

$_lang['setting_phpthumb_nooffsitelink_require_refer'] = 'phpThumb Offsite Linking Require Referrer';
$_lang['setting_phpthumb_nooffsitelink_require_refer_desc'] = 'If enabled, any offsite linking attempts will be rejected without a valid referrer header.';

$_lang['setting_phpthumb_nooffsitelink_text_message'] = 'phpThumb Offsite Linking Not Allowed Message';
$_lang['setting_phpthumb_nooffsitelink_text_message_desc'] = 'A message that is rendered instead of the thumbnail when an offsite linking attempt is rejected.';

$_lang['setting_phpthumb_nooffsitelink_valid_domains'] = 'phpThumb Offsite Linking Valid Domains';
$_lang['setting_phpthumb_nooffsitelink_valid_domains_desc'] = 'A comma-delimited list of hostnames that are valid referrers for offsite linking.';

$_lang['setting_phpthumb_nooffsitelink_watermark_src'] = 'phpThumb Offsite Linking Watermark Source';
$_lang['setting_phpthumb_nooffsitelink_watermark_src_desc'] = 'Optional. A valid file system path to a file to use as a watermark source when your images are rendered offsite by phpThumb.';

$_lang['setting_phpthumb_zoomcrop'] = 'phpThumb Zoom-Crop';
$_lang['setting_phpthumb_zoomcrop_desc'] = 'The default zc setting for phpThumb when used in MODX. Defaults to 0 to prevent zoom cropping.';

$_lang['setting_publish_default'] = 'Padrão publicado';
$_lang['setting_publish_default_desc'] = 'Select \'Yes\' to make all new resources published by default.';
$_lang['setting_publish_default_err'] = 'Please state whether or not you want documents to be published by default.';

$_lang['setting_rb_base_dir'] = 'Resource path';
$_lang['setting_rb_base_dir_desc'] = 'Enter the physical path to the resource directory. This setting is usually automatically generated. If you\'re using IIS, however, MODX may not be able to work the path out on its own, causing the Resource Browser to show an error. In that case, you can enter the path to the images directory here (the path as you\'d see it in Windows Explorer). <strong>NOTE:</strong> The resource directory must contain the subfolders images, files, flash and media in order for the resource browser to function correctly.';
$_lang['setting_rb_base_dir_err'] = 'Please state the resource browser base directory.';
$_lang['setting_rb_base_dir_err_invalid'] = 'This resource directory either does not exist or cannot be accessed. Please state a valid directory or adjust the permissions of this directory.';

$_lang['setting_rb_base_url'] = 'Resource URL';
$_lang['setting_rb_base_url_desc'] = 'Enter the virtual path to resource directory. This setting is usually automatically generated. If you\'re using IIS, however, MODX may not be able to work the URL out on its own, causing the Resource Browser to show an error. In that case, you can enter the URL to the images directory here (the URL as you\'d enter it on Internet Explorer).';
$_lang['setting_rb_base_url_err'] = 'Please state the resource browser base URL.';

$_lang['setting_request_controller'] = 'Request Controller Filename';
$_lang['setting_request_controller_desc'] = 'The filename of the main request controller from which MODX is loaded. Most users can leave this as index.php.';

$_lang['setting_request_method_strict'] = 'Strict Request Method';
$_lang['setting_request_method_strict_desc'] = 'If enabled, requests via the Request ID Parameter will be ignored with FURLs enabled, and those via Request Alias Parameter will be ignored without FURLs enabled.';

$_lang['setting_request_param_alias'] = 'Request Alias Parameter';
$_lang['setting_request_param_alias_desc'] = 'The name of the GET parameter to identify Resource aliases when redirecting with FURLs.';

$_lang['setting_request_param_id'] = 'Request ID Parameter';
$_lang['setting_request_param_id_desc'] = 'The name of the GET parameter to identify Resource IDs when not using FURLs.';

$_lang['setting_resolve_hostnames'] = 'Resolve hostnames';
$_lang['setting_resolve_hostnames_desc'] = 'Do you want MODX to try to resolve your visitors\' hostnames when they visit your site? Resolving hostnames may create some extra server load, although your visitors won\'t notice this in any way.';

$_lang['setting_resource_tree_node_name'] = 'Resource Tree Node Field';
$_lang['setting_resource_tree_node_name_desc'] = 'Specify the Resource field to use when rendering the nodes in the Resource Tree. Defaults to pagetitle, although any Resource field can be used, such as menutitle, alias, longtitle, etc.';

$_lang['setting_resource_tree_node_name_fallback'] = 'Resource Tree Node Fallback Field';
$_lang['setting_resource_tree_node_name_fallback_desc'] = 'Specify the Resource field to use as fallback when rendering the nodes in the Resource Tree. This will be used if the resource has an empty value for the configured Resource Tree Node Field.';

$_lang['setting_resource_tree_node_tooltip'] = 'Resource Tree Tooltip Field';
$_lang['setting_resource_tree_node_tooltip_desc'] = 'Specify the Resource field to use when rendering the nodes in the Resource Tree. Any Resource field can be used, such as menutitle, alias, longtitle, etc. If blank, will be the longtitle with a description underneath.';

$_lang['setting_richtext_default'] = 'Richtext Default';
$_lang['setting_richtext_default_desc'] = 'Select \'Yes\' to make all new Resources use the Richtext Editor by default.';

$_lang['setting_search_default'] = 'Searchable Default';
$_lang['setting_search_default_desc'] = 'Select \'Yes\' to make all new resources searchable by default.';
$_lang['setting_search_default_err'] = 'Please specify whether or not you want documents to be searchable by default.';

$_lang['setting_server_offset_time'] = 'Server offset time';
$_lang['setting_server_offset_time_desc'] = 'Select the number of hours time difference between where you are and where the server is.';

$_lang['setting_server_protocol'] = 'Tipo de servidor';
$_lang['setting_server_protocol_desc'] = 'If your site is on a https connection, please specify so here.';
$_lang['setting_server_protocol_err'] = 'Please specify whether or not your site is a secure site.';
$_lang['setting_server_protocol_http'] = 'http';
$_lang['setting_server_protocol_https'] = 'https';

$_lang['setting_session_cookie_domain'] = 'Session Cookie Domain';
$_lang['setting_session_cookie_domain_desc'] = 'Use this setting to customize the session cookie domain. Leave blank to use the current domain.';

$_lang['setting_session_cookie_lifetime'] = 'Session Cookie Lifetime';
$_lang['setting_session_cookie_lifetime_desc'] = 'Use this setting to customize the session cookie lifetime in seconds.  This is used to set the lifetime of a client session cookie when they choose the \'remember me\' option on login.';

$_lang['setting_session_cookie_path'] = 'Caminho do Cookie de sessão';
$_lang['setting_session_cookie_path_desc'] = 'Use this setting to customize the cookie path for identifying site specific session cookies. Leave blank to use MODX_BASE_URL.';

$_lang['setting_session_cookie_secure'] = 'Cookie de sessão segura';
$_lang['setting_session_cookie_secure_desc'] = 'Enable this setting to use secure session cookies.';

$_lang['setting_session_cookie_httponly'] = 'Cookie de sessão HttpOnly';
$_lang['setting_session_cookie_httponly_desc'] = 'Use this setting to set the HttpOnly flag on session cookies.';

$_lang['setting_session_gc_maxlifetime'] = 'Session Garbage Collector Max Lifetime';
$_lang['setting_session_gc_maxlifetime_desc'] = 'Allows customization of the session.gc_maxlifetime PHP ini setting when using \'modSessionHandler\'.';

$_lang['setting_session_handler_class'] = 'Session Handler Class Name';
$_lang['setting_session_handler_class_desc'] = 'For database managed sessions, use \'modSessionHandler\'.  Leave this blank to use standard PHP session management.';

$_lang['setting_session_name'] = 'Nome da Sessão';
$_lang['setting_session_name_desc'] = 'Use this setting to customize the session name used for the sessions in MODX. Leave blank to use the default PHP session name.';

$_lang['setting_settings_version'] = 'Versão das configurações';
$_lang['setting_settings_version_desc'] = 'The current installed version of MODX.';

$_lang['setting_settings_distro'] = 'Distribuição de configurações';
$_lang['setting_settings_distro_desc'] = 'The current installed distribution of MODX.';

$_lang['setting_set_header'] = 'Definir cabeçalhos HTTP';
$_lang['setting_set_header_desc'] = 'When enabled, MODX will attempt to set the HTTP headers for Resources.';

$_lang['setting_send_poweredby_header'] = 'Enviar cabeçalho X-Powered-By';
$_lang['setting_send_poweredby_header_desc'] = 'Quando habilitado, MODX irá enviar o cabeçalho "X-Powered-By" para identificar este site como construído sobre MODX. Isso ajuda a realizar um rastreamento global de uso do MODX através de rastreadores de terceiros inspecionando seu site. Porque isso torna mais fácil identificar com o que seu site é construído, isso pode representar um risco ligeiramente maior de segurança se uma vulnerabilidade for encontrada no MODX.';

$_lang['setting_show_tv_categories_header'] = 'Show "Categories" Tabs Header with TVs';
$_lang['setting_show_tv_categories_header_desc'] = 'If "Yes", MODX will show the "Categories" header above the first category tab when editing TVs in a Resource.';

$_lang['setting_signupemail_message'] = 'Email de Inscrição';
$_lang['setting_signupemail_message_desc'] = 'Here you can set the message sent to your users when you create an account for them and let MODX send them an email containing their username and password. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site URL, <br />[[+uid]] - User\'s login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the email, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_signupemail_message_default'] = 'Hello [[+uid]] \n\nHere are your login details for [[+sname]] Content Manager:\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nOnce you log into the Content Manager ([[+surl]]), you can change your password.\n\nRegards,\nSite Administrator';

$_lang['setting_site_name'] = 'Site name';
$_lang['setting_site_name_desc'] = 'Enter the name of your site here.';
$_lang['setting_site_name_err']  = 'Por favor, insira um nome de site.';

$_lang['setting_site_start'] = 'Início do site';
$_lang['setting_site_start_desc'] = 'Enter the ID of the Resource you want to use as homepage here. <strong>NOTE: make sure this ID you enter belongs to an existing Resource, and that it has been published!</strong>';
$_lang['setting_site_start_err'] = 'Please specify a Resource ID that is the site start.';

$_lang['setting_site_status'] = 'Status do Site';
$_lang['setting_site_status_desc'] = 'Select \'Yes\' to publish your site on the web. If you select \'No\', your visitors will see the \'Site unavailable message\', and won\'t be able to browse the site.';
$_lang['setting_site_status_err'] = 'Please select whether or not the site is online (Yes) or offline (No).';

$_lang['setting_site_unavailable_message'] = 'Mensagem do site indisponível';
$_lang['setting_site_unavailable_message_desc'] = 'Message to show when the site is offline or if an error occurs. <strong>Note: This message will only be displayed if the Site unavailable page option is not set.</strong>';

$_lang['setting_site_unavailable_page'] = 'Página do site indisponível';
$_lang['setting_site_unavailable_page_desc'] = 'Enter the ID of the Resource you want to use as an offline page here. <strong>NOTE: make sure this ID you enter belongs to an existing Resource, and that it has been published!</strong>';
$_lang['setting_site_unavailable_page_err'] = 'Please specify the document ID for the site unavailable page.';

$_lang['setting_strip_image_paths'] = 'Rewrite browser paths?';
$_lang['setting_strip_image_paths_desc'] = 'If this is set to \'No\', MODX will write file browser resource src\'s (images, files, flash, etc.) as absolute URLs. Relative URLs are helpful should you wish to move your MODX install, e.g., from a staging site to a production site. If you have no idea what this means, it\'s best just to leave it set to \'Yes\'.';

$_lang['setting_symlink_merge_fields'] = 'Merge Resource Fields in Symlinks';
$_lang['setting_symlink_merge_fields_desc'] = 'If set to Yes, will automatically merge non-empty fields with target resource when forwarding using Symlinks.';

$_lang['setting_syncsite_default'] = 'Limpar Cache Padrão';
$_lang['setting_syncsite_default_desc'] = 'Selecione \'Sim\' para esvaziar o cache depois de salvar um recurso por padrão.';
$_lang['setting_syncsite_default_err'] = 'Por favor defina ou não se deseja esvaziar o cache depois de salvar um recurso por padrão.';

$_lang['setting_topmenu_show_descriptions'] = 'Show Descriptions in Top Menu';
$_lang['setting_topmenu_show_descriptions_desc'] = 'If set to \'No\', MODX will hide the descriptions from top menu items in the manager.';

$_lang['setting_tree_default_sort'] = 'Resource Tree Default Sort Field';
$_lang['setting_tree_default_sort_desc'] = 'The default sort field for the Resource tree when loading the manager.';

$_lang['setting_tree_root_id'] = 'Tree Root ID';
$_lang['setting_tree_root_id_desc'] = 'Set this to a valid ID of a Resource to start the left Resource tree at below that node as the root. The user will only be able to see Resources that are children of the specified Resource.';

$_lang['setting_tvs_below_content'] = 'Move TVs Below Content';
$_lang['setting_tvs_below_content_desc'] = 'Set this to Yes to move Template Variables below the Content when editing Resources.';

$_lang['setting_ui_debug_mode'] = 'UI Debug Mode';
$_lang['setting_ui_debug_mode_desc'] = 'Set this to Yes to output debug messages when using the UI for the default manager theme. You must use a browser that supports console.log.';

$_lang['setting_udperms_allowroot'] = 'Allow root';
$_lang['setting_udperms_allowroot_desc'] = 'Do you want to allow your users to create new Resources in the root of the site?';

$_lang['setting_unauthorized_page'] = 'Página não autorizada';
$_lang['setting_unauthorized_page_desc'] = 'Enter the ID of the Resource you want to send users to if they have requested a secured or unauthorized Resource. <strong>NOTE: Make sure the ID you enter belongs to an existing Resource, and that it has been published and is publicly accessible!</strong>';
$_lang['setting_unauthorized_page_err'] = 'Please specify a Resource ID for the unauthorized page.';

$_lang['setting_upload_files'] = 'Uploadable File Types';
$_lang['setting_upload_files_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/files/\' using the Resource Manager. Please enter the extensions for the filetypes, seperated by commas.';

$_lang['setting_upload_flash'] = 'Uploadable Flash Types';
$_lang['setting_upload_flash_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/flash/\' using the Resource Manager. Please enter the extensions for the flash types, separated by commas.';

$_lang['setting_upload_images'] = 'Uploadable Image Types';
$_lang['setting_upload_images_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/images/\' using the Resource Manager. Please enter the extensions for the image types, separated by commas.';

$_lang['setting_upload_maxsize'] = 'Tamanho máximo de upload';
$_lang['setting_upload_maxsize_desc'] = 'Enter the maximum file size that can be uploaded via the file manager. Upload file size must be entered in bytes. <strong>NOTE: Large files can take a very long time to upload!</strong>';

$_lang['setting_upload_media'] = 'Uploadable Media Types';
$_lang['setting_upload_media_desc'] = 'Here you can enter a list of files that can be uploaded into \'assets/media/\' using the Resource Manager. Please enter the extensions for the media types, separated by commas.';

$_lang['setting_use_alias_path'] = 'Use Friendly Alias Path';
$_lang['setting_use_alias_path_desc'] = 'Setting this option to \'yes\' will display the full path to the Resource if the Resource has an alias. For example, if a Resource with an alias called \'child\' is located inside a container Resource with an alias called \'parent\', then the full alias path to the Resource will be displayed as \'/parent/child.html\'.<br /><strong>NOTE: When setting this option to \'Yes\' (turning on alias paths), reference items (such as images, CSS, JavaScripts, etc.) use the absolute path, e.g., \'/assets/images\' as opposed to \'assets/images\'. By doing so you will prevent the browser (or web server) from appending the relative path to the alias path.</strong>';

$_lang['setting_use_browser'] = 'Enable Resource Browser';
$_lang['setting_use_browser_desc'] = 'Select yes to enable the resource browser. This will allow your users to browse and upload resources such as images, flash and media files on the server.';
$_lang['setting_use_browser_err'] = 'Please state whether or not you want to use the resource browser.';

$_lang['setting_use_editor'] = 'Habilitar o Editor de Rich Text';
$_lang['setting_use_editor_desc'] = 'Do you want to enable the rich text editor? If you\'re more comfortable writing HTML, then you can turn the editor off using this setting. Note that this setting applies to all documents and all users!';
$_lang['setting_use_editor_err'] = 'Please state whether or not you want to use an RTE editor.';

$_lang['setting_use_frozen_parent_uris'] = 'Usar URIs Pai Congeladas';
$_lang['setting_use_frozen_parent_uris_desc'] = 'Quando habilitado, o URI para recursos filhos será relativo URI congelado de um dos seus pais, ignorando aliases de recursos altos na árvore.';

$_lang['setting_use_multibyte'] = 'Use Multibyte Extension';
$_lang['setting_use_multibyte_desc'] = 'Set to true if you want to use the mbstring extension for multibyte characters in your MODX installation. Only set to true if you have the mbstring PHP extension installed.';

$_lang['setting_use_weblink_target'] = 'Use WebLink Target';
$_lang['setting_use_weblink_target_desc'] = 'Set to true if you want to have MODX link tags and makeUrl() generate links as the target URL for WebLinks. Otherwise, the internal MODX URL will be generated by link tags and the makeUrl() method.';

$_lang['setting_user_nav_parent'] = 'Pai do menu de usuário';
$_lang['setting_user_nav_parent_desc'] = 'O recipiente usado para puxar todos os registros para o menu de usuário.';

$_lang['setting_webpwdreminder_message'] = 'E-mail de lembrete da Web';
$_lang['setting_webpwdreminder_message_desc'] = 'Enter a message to be sent to your web users whenever they request a new password via email. The Content Manager will send an email containing their new password and activation information. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site URL, <br />[[+uid]] - User\'s login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the email, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_webpwdreminder_message_default'] = 'Hello [[+uid]]\n\nTo activate your new password, click the following link:\n\n[[+surl]]\n\nIf successful, you can use the following password to log in:\n\nPassword:[[+pwd]]\n\nIf you did not request this email, then please ignore it.\n\nRegrads,\nSite Administrator';

$_lang['setting_websignupemail_message'] = 'E-mail de inscrição Web';
$_lang['setting_websignupemail_message_desc'] = 'Here you can set the message sent to your web users when you create a web account for them and let the Content Manager send them an email containing their username and password. <br /><strong>Note:</strong> The following placeholders are replaced by the Content Manager when the message is sent: <br /><br />[[+sname]] - Name of your web site, <br />[[+saddr]] - Your web site email address, <br />[[+surl]] - Your site URL, <br />[[+uid]] - User\'s login name or id, <br />[[+pwd]] - User\'s password, <br />[[+ufn]] - User\'s full name. <br /><br /><strong>Leave the [[+uid]] and [[+pwd]] in the email, or else the username and password won\'t be sent in the mail and your users won\'t know their username or password!</strong>';
$_lang['setting_websignupemail_message_default'] = 'Hello [[+uid]] \n\nHere are your login details for [[+sname]]:\n\nUsername: [[+uid]]\nPassword: [[+pwd]]\n\nOnce you log into [[+sname]] ([[+surl]]), you can change your password.\n\nRegards,\nSite Administrator';

$_lang['setting_welcome_screen'] = 'Mostrar tela de Boas Vindas';
$_lang['setting_welcome_screen_desc'] = 'Se definido como true, a tela de boas-vindas será exibida no próximo carregamento sucesso da página de boas-vindas, e depois não vai aparecer depois disso.';

$_lang['setting_welcome_screen_url'] = 'URL da Tela de Boas Vindas';
$_lang['setting_welcome_screen_url_desc'] = 'A URL para a tela de boas-vindas que carrega no primeiro carregamento do MODX Revolution. ';

$_lang['setting_welcome_action'] = 'Ação de boas-vinda';
$_lang['setting_welcome_action_desc'] = 'The default controller to load when accessing the manager when no controller is specified in the URL.';

$_lang['setting_welcome_namespace'] = 'Welcome Namespace';
$_lang['setting_welcome_namespace_desc'] = 'The namespace the Welcome Action belongs to.';

$_lang['setting_which_editor'] = 'Editor para usar';
$_lang['setting_which_editor_desc'] = 'Aqui você pode selecionar quais Editor de Rich Text você deseja usar. Você pode baixar e instalar editores de Rich Text adicionais a partir do Gerenciador de Pacotes.';

$_lang['setting_which_element_editor'] = 'Editor de usar para elementos "';
$_lang['setting_which_element_editor_desc'] = 'Aqui você pode selecionar quais Editor de Rich Text você deseja usar durante a edição Elementos. Você pode baixar e instalar editores de Rich Text adicionais a partir do Gerenciador de Pacotes.';

$_lang['setting_xhtml_urls'] = 'URLs XHTML';
$_lang['setting_xhtml_urls_desc'] = 'Se definido como true, todas as URLs geradas por MODX serão compatíveis com XHTML, incluindo a codificação do caractere e comercial.';

$_lang['setting_default_context'] = 'Contexto Padrão';
$_lang['setting_default_context_desc'] = 'Selecione o Contexto padrão que você deseja usar para novos Recursos.';

$_lang['setting_auto_isfolder'] = 'Definir o contêiner automaticamente';
$_lang['setting_auto_isfolder_desc'] = 'Se definido para Sim, a propriedade do contêiner será alterada automaticamente.';

$_lang['setting_default_username'] = 'Nome de usuário padrão';
$_lang['setting_default_username_desc'] = 'Nome de usuário padrão para um usuário não autenticado.';

$_lang['setting_manager_use_fullname'] = 'Mostrar fullname no cabeçalho do Gerenciador ';
$_lang['setting_manager_use_fullname_desc'] = 'Se definida como Sim, o conteúdo do campo "fullname" será mostrado no Gerenciador ao invés de "loginname"';

$_lang['log_snippet_not_found'] = 'Log snippets not found';
$_lang['log_snippet_not_found_desc'] = 'If set to yes, snippets that are called but not found will be logged to the error log.';

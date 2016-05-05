<?php
/**
 * Test-related English Lexicon Topic for Revolution setup.
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['test_config_file'] = 'Verificando se <span class="mono">[[+file]]</span> existe e é gravável: ';
$_lang['test_config_file_nw'] = 'Para novas instalações de Linux/Unix, por favor crie um arquivo branco chamado <span class="mono">[[+key]].inc.php</span> em seu núcleo MODX <span class="mono">config/</span> diretório com permissão de escrita pelo PHP.';
$_lang['test_db_check'] = 'Criando conexão com o banco de dados: ';
$_lang['test_db_check_conn'] = 'Confira os detalhes da conexão e tente novamente.';
$_lang['test_db_failed'] = 'Conexão com o banco falhou!';
$_lang['test_db_setup_create'] = 'A instalação tentará criar o banco de dados.';
$_lang['test_dependencies'] = 'Verificando PHP para dependência zlib: ';
$_lang['test_dependencies_fail_zlib'] = 'Sua instalação do PHP não tem a extensão "zlib" instalada. Essa extensão é necessária para MODX ser executado. Ative-o para continuar.';
$_lang['test_directory_exists'] = 'Verificando se o diretório <span class="mono">[[+dir]]</span> existe: ';
$_lang['test_directory_writable'] = 'Verificando se o diretório <span class="mono">[[+dir]]</span> é gravável: ';
$_lang['test_memory_limit'] = 'Verificando se o limite de memória é definida como pelo menos 24M: ';
$_lang['test_memory_limit_fail'] = 'MODX encontrou sua configuração memory_limit estando em [[+memory]], abaixo da configuração recomendada de 24M. MODX tentou definir o memory_limit para 24M, mas não teve sucesso. Por favor, defina a configuração memory_limit no seu arquivo php.ini para pelo menos 24M ou superior antes de prosseguir. Se você ainda está tendo problemas (tais como a obtenção de uma tela em branco na instalação), definida para 32M, 64M ou superior.';
$_lang['test_memory_limit_success'] = 'OK! Definido para [[+memory]]';
$_lang['test_mysql_version_5051'] = 'MODX terá problemas em sua versão do MySQL ([[+version]]), por causa dos muitos erros relacionados aos drivers PDO nesta versão. Por favor, atualize o MySQL para corrigir esses problemas. Mesmo se você optar por não usar MODX, é recomendado que você atualize para esta versão para a segurança e estabilidade do seu próprio site.';
$_lang['test_mysql_version_client_nf'] = 'Não foi possível detectar a versão do cliente MySQL!';
$_lang['test_mysql_version_client_nf_msg'] = 'MODX não conseguiu detectar a sua versão do cliente MySQL via mysql_get_client_info(). Por favor, faça manualmente e certifique-se de que sua versão do cliente MySQL é pelo menos 4.1.20 antes de prosseguir.';
$_lang['test_mysql_version_client_old'] = 'MODX pode ter problemas porque você está usando uma versão muito antiga do cliente MySQL ([[+version]])';
$_lang['test_mysql_version_client_old_msg'] = 'MODX vai permitir a instalação utilizando esta versão do cliente MySQL, mas não podemos garantir que todas as funcionalidades estarão disponíveis ou funcionão corretamente ao usar versões mais antigas das bibliotecas de cliente do MySQL .';
$_lang['test_mysql_version_client_start'] = 'Verificando a versão do cliente MySQL:';
$_lang['test_mysql_version_fail'] = 'Você está rodando em MySQL [[+version]], e MODX Revolution requer MySQL 4.1.20 ou posterior. Por favor, atualize o MySQL pelo menos 4.1.20.';
$_lang['test_mysql_version_server_nf'] = 'Não foi possível detectar a versão do servidor MySQL!';
$_lang['test_mysql_version_server_nf_msg'] = 'MODX não conseguiu detectar a sua versão do servidor MySQL via mysql_get_server_info(). Por favor, faça manualmente certeza de que sua versão do servidor MySQL é pelo menos 4.1.20 antes de prosseguir.';
$_lang['test_mysql_version_server_start'] = 'Verificando a versão do servidor MySQL:';
$_lang['test_mysql_version_success'] = 'OK! Executando: [[+version]]';
$_lang['test_nocompress'] = 'Verificando se devemos desativar a compressão de CSS/JS: ';
$_lang['test_nocompress_disabled'] = 'OK! Desabilitado.';
$_lang['test_nocompress_skip'] = 'Não selecionado, passando o teste.';
$_lang['test_php_version_fail'] = 'Você está rodando em PHP [[+version]], e MODX Revolution requer PHP 5.1.1 ou posterior. Por favor, atualize o PHP para pelo menos 5.1.1. MODX recomenda a atualização para pelo menos 5.3.2 +.';
$_lang['test_php_version_start'] = 'Verificando versão do PHP:';
$_lang['test_php_version_success'] = 'OK! Executando: [[+version]]';
$_lang['test_safe_mode_start'] = 'Verificando se o safe_mode está desligado:';
$_lang['test_safe_mode_fail'] = 'MODX encontrou safe_mode estar ligado. Você deve desativar o safe_mode em sua configuração do PHP para prosseguir.';
$_lang['test_sessions_start'] = 'Verificando se as sessões estão configurados corretamente:';
$_lang['test_simplexml'] = 'Verificando SimpleXML:';
$_lang['test_simplexml_nf'] = 'Não foi possível encontrar SimpleXML!';
$_lang['test_simplexml_nf_msg'] = 'MODX não poderia encontrar SimpleXML em seu ambiente PHP. Gerenciamento de pacotes e outras funcionalidades não funcionarão sem esse software instalado. Você pode continuar com a instalação, mas MODX recomenda ativar SimpleXML para características e funcionalidades avançadas.';
$_lang['test_suhosin'] = 'Verificação de questões suhosin:';
$_lang['test_suhosin_max_length'] = 'Suhosin valor GET máximo muito baixo!';
$_lang['test_suhosin_max_length_err'] = 'Atualmente, você está usando a extensão suhosin PHP, e seu suhosin.get.max_value_length está muito baixo para MODX para compactar corretamente arquivos JS no gerenciador. MODX recomenda aumentando esse valor para 4096; até então, MODX irá definir automaticamente o seu JS compressão (configuração compress_js) para 0 para evitar erros.';
$_lang['test_table_prefix'] = 'Verificando prefixo da tabela `[[+prefix]]`: ';
$_lang['test_table_prefix_inuse'] = 'Prefixo da tabela já está em uso neste banco de dados!';
$_lang['test_table_prefix_inuse_desc'] = 'A instalação não pode instalar no banco de dados selecionado, uma vez que já contém tabelas com o prefixo especificado. Escolha uma nova table_prefix e execute a instalação novamente.';
$_lang['test_table_prefix_nf'] = 'Prefixo tabela não existe neste banco de dados!';
$_lang['test_table_prefix_nf_desc'] = 'A instalação não pode instalar no banco de dados selecionado, uma vez que não contém tabelas existentes com o prefixo especificado para ser atualizado. Por favor escolha uma table_prefix existente e execute a instalação novamente.';
$_lang['test_zip_memory_limit'] = 'Verificando se o limite de memória está definida como pelo menos 24M para extensões zip: ';
$_lang['test_zip_memory_limit_fail'] = 'MODX encontrou sua configuração memory_limit estando abaixo da configuração recomendada de 24M. MODX tentou definir o memory_limit para 24M, mas não teve sucesso. Por favor, defina a configuração memory_limit no seu arquivo php.ini para 24M ou superior antes de prosseguir para que as extensões zip funcionem corretamente.';
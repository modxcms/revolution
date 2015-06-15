<?php
/**
 * English Drivers Lexicon Topic for Revolution setup
 *
 * @package setup
 * @subpackage lexicon
 */
$_lang['mysql_err_ext'] = 'MODX requer a extensão mysql para o PHP e não parece ter sido carregado.';
$_lang['mysql_err_pdo'] = 'MODX requer o driver pdo_mysql quando PDO nativo está sendo usado e não parece ter sido carregado.';
$_lang['mysql_version_5051'] = 'MODX terá problemas em sua versão do MySQL ([[+version]]), por causa dos muitos erros relacionados aos drivers PDO nesta versão. Por favor, atualize o MySQL para corrigir esses problemas. Mesmo se você optar por não usar MODX, é recomendado que você atualize para esta versão para a segurança e estabilidade do seu próprio site.';
$_lang['mysql_version_client_nf'] = 'MODX não conseguiu detectar a sua versão do cliente MySQL via mysql_get_client_info(). Por favor, faça manualmente e certifique-se de que sua versão do cliente MySQL é pelo menos 4.1.20 antes de prosseguir.';
$_lang['mysql_version_client_start'] = 'Verificando a versão do cliente MySQL:';
$_lang['mysql_version_client_old'] = 'MODX pode ter problemas porque você está usando uma versão muito antiga do cliente MySQL ([[+version]]). MODX vai permitir a instalação utilizando esta versão do cliente MySQL, mas não podemos garantir a todas as funcionalidades estarão disponíveis ou funcionar corretamente ao usar versões mais antigas do MySQL as bibliotecas de cliente.';
$_lang['mysql_version_fail'] = 'Você está rodando em MySQL [[+version]], e MODX Revolution requer MySQL 4.1.20 ou posterior. Por favor, atualize o MySQL pelo menos 4.1.20.';
$_lang['mysql_version_server_nf'] = 'MODX não conseguiu detectar a sua versão do servidor MySQL via mysql_get_server_info(). Por favor, faça manualmente certeza de que sua versão do servidor MySQL é pelo menos 4.1.20 antes de prosseguir.';
$_lang['mysql_version_server_start'] = 'Verificando a versão do servidor MySQL:';
$_lang['mysql_version_success'] = 'OK! Executando: [[+version]]';

$_lang['sqlsrv_version_success'] = 'OK!';
$_lang['sqlsrv_version_client_success'] = 'OK!';
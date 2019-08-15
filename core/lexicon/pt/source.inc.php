<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Permissões de Acesso';
$_lang['base_path'] = 'Caminho Base';
$_lang['base_path_relative'] = 'Base de dados relativa caminho?';
$_lang['base_url'] = 'URL Base';
$_lang['base_url_relative'] = 'URL relativa a Base?';
$_lang['minimum_role'] = 'Função mínima';
$_lang['path_options'] = 'Opções caminho';
$_lang['policy'] = 'Política';
$_lang['source'] = 'Fonte de mídia';
$_lang['source_access_add'] = 'Adicionar User Group';
$_lang['source_access_remove'] = 'Remover o Acesso';
$_lang['source_access_remove_confirm'] = 'Tem certeza que deseja remover acesso a esta fonte para esse grupo de usuários?';
$_lang['source_access_update'] = 'Atualização de Acesso ';
$_lang['source_create'] = 'Criar Nova Fonte de Mídia';
$_lang['source_description_desc'] = 'Uma breve descrição da Fonte Mídia.';
$_lang['source_duplicate'] = 'Duplicata da Fonte de mídia';
$_lang['source_err_ae_name'] = 'uma fonte de mídia com esse nome já existe! Por favor, especifique um novo nome.';
$_lang['source_err_nf'] = 'Fonte mídia não encontrado!';
$_lang['source_err_nfs'] = 'Não Fonte de mídia pode ser encontrado com o id:. [[+id]]';
$_lang['source_err_ns'] = 'Por favor, especificar a fonte de mídia.';
$_lang['source_err_ns_name'] = 'Por favor, indique um nome para a Fonte de de mídia.';
$_lang['source_name_desc'] = 'O nome da Fonte de Mídia.';
$_lang['source_properties.intro_msg'] = 'Gerenciar as propriedades para esta fonte abaixo.';
$_lang['source_remove'] = 'Excluir Fonte de Mídia';
$_lang['source_remove_confirm'] = 'Tem certeza que deseja remover esta Fonte de de mídia? Isso pode quebrar qualquer TVs que você atribuiu a esta fonte.';
$_lang['source_remove_multiple'] = 'Excluir múltiplas fontes de mídia';
$_lang['source_remove_multiple_confirm'] = 'Tem certeza que deseja excluir essas fontes de mídia? Isso pode quebrar qualquer TVs que você atribuiu a estas fontes.';
$_lang['source_update'] = 'Atualizar a Fonte de Mídia';
$_lang['source_type'] = 'Tipo de origem';
$_lang['source_type_desc'] = 'O tipo, ou motorista, da Fonte de Mídia. A fonte vai usar esse driver para conectar-se na coleta de seus dados. Por exemplo: File System vai pegar os arquivos do sistema de arquivos. S3 terá arquivos de um bucket S3 .';
$_lang['source_type.file'] = 'Sistema de arquivos';
$_lang['source_type.file_desc'] = 'A fonte baseado em sistema de arquivos que navega \ do seu servidor de arquivos.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Navega um bucket do Amazon S3.';
$_lang['source_types'] = 'Tipos de fonte';
$_lang['source_types.intro_msg'] = 'Esta é uma lista de todos os tipos de fontes de mídia instalado você tem nesta instância MODX.';
$_lang['source.access.intro_msg'] = 'Aqui você pode restringir uma fonte de mídia para grupos específicos de usuários e aplicar políticas para os grupos de usuários. Uma fonte de mídia sem Grupos de usuários conectados a ele está disponível para todos os usuários gerente.';
$_lang['sources'] = 'Fontes de Mídia';
$_lang['sources.intro_msg'] = 'Gerenciar todas as suas Fontes de Mídia aqui.';
$_lang['user_group'] = 'Grupo de Usuário';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'Se definido, vai restringir os arquivos mostrados apenas para as extensões especificadas. Por favor, especifique em uma lista separada por vírgulas';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'O caminho do arquivo para apontar a Fonte de Mídia.';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Se a base de dados de configuração de caminho acima não é relativo ao caminho de instalação MODX, defina-o para Não.';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'O URL que esta fonte pode ser acessado a partir de.';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Se for verdade, MODX só irá preceder o baseUrl se nenhuma barra (/) é encontrado no início da URL ao processamento de TV. Útil para definir um valor fora da TV baseUrl .';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'Se a definição de URL base acima não é em relação à instalação URL MODX, defina-o para Não.';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'A lista separada por vírgulas de extensões de arquivos para usar como imagens. MODX tentará fazer miniaturas de arquivos com estas extensões.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'Uma lista separada por vírgulas. MODX vai pular e se esconder arquivos e pastas que correspondem a qualquer destes .';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'A qualidade das miniaturas prestados, em uma escala de 0-100.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'O tipo de imagem para tornar thumbnails como.';

/* s3 source type */
$_lang['bucket'] = 'Bucket';
$_lang['prop_s3.bucket_desc'] = 'O S3 Bucket para carregar seus dados.';
$_lang['prop_s3.key_desc'] = 'A chave Amazon para autenticação no bucket. ';
$_lang['prop_s3.imageExtensions_desc'] = 'A lista separada por vírgulas de extensões de arquivos para usar como imagens. MODX tentará fazer miniaturas de arquivos com estas extensões.';
$_lang['prop_s3.secret_key_desc'] = 'A chave secreta Amazon para autenticação no bucket. ';
$_lang['prop_s3.skipFiles_desc'] = 'Uma lista separada por vírgulas. MODX vai pular e se esconder arquivos e pastas que correspondem a qualquer destes .';
$_lang['prop_s3.thumbnailQuality_desc'] = 'A qualidade das miniaturas prestados, em uma escala de 0-100.';
$_lang['prop_s3.thumbnailType_desc'] = 'O tipo de imagem para tornar thumbnails como.';
$_lang['prop_s3.url_desc'] = 'O URL da instância do Amazon S3.';
$_lang['s3_no_move_folder'] = 'O driver S3 não suporta movimentação de pastas neste momento.';
$_lang['prop_s3.region_desc'] = 'Região do bucket. Exemplo: us-west-1';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

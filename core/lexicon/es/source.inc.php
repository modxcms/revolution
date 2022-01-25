<?php
/**
 * Sources English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['access'] = 'Permisos de Acceso';
$_lang['base_path'] = 'Directorio Base';
$_lang['base_path_relative'] = '¿Es el Directorio Base Relativo?';
$_lang['base_url'] = 'URL Base';
$_lang['base_url_relative'] = '¿Es la URL Base Relativa?';
$_lang['minimum_role'] = 'Rol Mínimo';
$_lang['path_options'] = 'Opciones de la Ruta';
$_lang['policy'] = 'Política';
$_lang['source'] = 'Origen Multimedia';
$_lang['source_access_add'] = 'Añadir Grupo de Usuarios';
$_lang['source_access_remove'] = 'Delete Access';
$_lang['source_access_remove_confirm'] = 'Are you sure you want to delete Access to this Source for this User Group?';
$_lang['source_access_update'] = 'Edit Access';
$_lang['source_description_desc'] = 'Breve descripción del Origen Multimedia.';
$_lang['source_err_ae_name'] = '¡Ya existe un Origen Multimedia con ese nombre! Por favor, especifica un nuevo nombre.';
$_lang['source_err_nf'] = '¡Origen Multimedia no encontrado!';
$_lang['source_err_init'] = 'Could not initialize "[[+source]]" Media Source!';
$_lang['source_err_nfs'] = 'No se encontró un Origen Multimedia con el id: [[+id]].';
$_lang['source_err_ns'] = 'Por favor, especifica el Origen Multimedia.';
$_lang['source_err_ns_name'] = 'Por favor, especifica el nombre para el Origen Multimedia.';
$_lang['source_name_desc'] = 'El nombre del Origen Multimedia.';
$_lang['source_properties.intro_msg'] = 'Gestiona debajo las propiedades de este Origen Multimedia.';
$_lang['source_remove_confirm'] = 'Are you sure you want to delete this Media Source? This might break any TVs you have assigned to this source.';
$_lang['source_remove_multiple_confirm'] = '¿Estás seguro de que quieres eliminar estos Orígenes Multimedia? Esto podría inutilizar cualquier Variable de Plantilla asignada a las mismas.';
$_lang['source_type'] = 'Tipo de Recurso';
$_lang['source_type_desc'] = 'El tipo, o manejador, de los Orígenes Multimedia. El Recurso se conectará a este manejador cuando vaya a obtener datos. Por ejemplo: "Sistema de Archivos" tomará archivos directamente del sistema de archivos. "Amazon S3" tomará archivos desde un depósito de archivos de Amazon S3.';
$_lang['source_type.file'] = 'Sistema de Archivos';
$_lang['source_type.file_desc'] = 'Un Origen Multimedia basado en sistema de archivos para navegar por los archivos del servidor.';
$_lang['source_type.s3'] = 'Amazon S3';
$_lang['source_type.s3_desc'] = 'Navegar a través de un "bucket" de Amazon S3.';
$_lang['source_type.ftp'] = 'File Transfer Protocol';
$_lang['source_type.ftp_desc'] = 'Navigates an FTP remote server.';
$_lang['source_types'] = 'Tipos de Orígenes Multimedia';
$_lang['source_types.intro_msg'] = 'Esta es una lista de todos los Tipos de Origen Multimedia disponibles en esta instalación de MODX.';
$_lang['source.access.intro_msg'] = 'Aquí se puede restringir un Origen Multimedia a un Grupo de Usuarios y aplicar políticas para dichos Grupos de Usuarios. Un Origen Multimedia sin Grupo de Usuarios será accesible a todos los usuarios del panel de administración.';
$_lang['sources'] = 'Orígenes Multimedia';
$_lang['sources.intro_msg'] = 'Gestiona aquí todos los Orígenes Multimedia.';
$_lang['user_group'] = 'Grupos de Usuario';

/* file source type */
$_lang['allowedFileTypes'] = 'allowedFileTypes';
$_lang['prop_file.allowedFileTypes_desc'] = 'Si se activa, solo se mostrarán los ficheros con las extensiones especificadas. El contenido deberá ser una lista separada por comas de los formatos sin el "." al principio.';
$_lang['basePath'] = 'basePath';
$_lang['prop_file.basePath_desc'] = 'The file path to point the Source to, for example: assets/images/<br>The path may depend on the "basePathRelative" parameter';
$_lang['basePathRelative'] = 'basePathRelative';
$_lang['prop_file.basePathRelative_desc'] = 'Si la configuración del Directorio Base de arriba no es relativa a la instalación de MODX, configurar este elemento como "No".';
$_lang['baseUrl'] = 'baseUrl';
$_lang['prop_file.baseUrl_desc'] = 'The URL that this source can be accessed from, for example: assets/images/<br>The path may depend on the "baseUrlRelative" parameter';
$_lang['baseUrlPrependCheckSlash'] = 'baseUrlPrependCheckSlash';
$_lang['prop_file.baseUrlPrependCheckSlash_desc'] = 'Si se activa, MODX solo agregará al inicio la URL base (baseUrl) si no se encuentra una barra inversa (/) al principio de la URL al renderizar la Variable de Plantilla. Útil cuando se configura la variable de plantilla con una URL externa a baseUrl.';
$_lang['baseUrlRelative'] = 'baseUrlRelative';
$_lang['prop_file.baseUrlRelative_desc'] = 'Si la configuración de URL Base de arriba no es relativa a la URL de instalación de MODX, configurar como "No".';
$_lang['imageExtensions'] = 'imageExtensions';
$_lang['prop_file.imageExtensions_desc'] = 'Una lista separada por comas de extensiones de archivo a utilizar como imágenes. MODX tratará de crear miniaturas de los archivos con dichas extensiones.';
$_lang['skipFiles'] = 'skipFiles';
$_lang['prop_file.skipFiles_desc'] = 'Una lista separada por comas de archivos a omitir. MODX se saltará los archivos que coincidan con alguno de ellos.';
$_lang['thumbnailQuality'] = 'thumbnailQuality';
$_lang['prop_file.thumbnailQuality_desc'] = 'La calidad de las miniaturas a generar, en una escala del 0 al 100.';
$_lang['thumbnailType'] = 'thumbnailType';
$_lang['prop_file.thumbnailType_desc'] = 'El tipo de archivo de las miniaturas generadas.';
$_lang['prop_file.visibility_desc'] = 'Default visibility for new files and folders.';
$_lang['no_move_folder'] = 'The Media Source driver does not support moving of folders at this time.';

/* s3 source type */
$_lang['bucket'] = 'Depósito';
$_lang['prop_s3.bucket_desc'] = 'El depósito de S3 desde el cual cargar los datos.';
$_lang['prop_s3.key_desc'] = 'La clave de Amazon para autenticarse en el depósito.';
$_lang['prop_s3.imageExtensions_desc'] = 'Una lista separada por comas de extensiones de archivo a utilizar como imágenes. MODX tratará de crear miniaturas de los archivos con dichas extensiones.';
$_lang['prop_s3.secret_key_desc'] = 'La clave secreta de Amazon para autenticarse en el depósito.';
$_lang['prop_s3.skipFiles_desc'] = 'Una lista separada por comas de archivos a omitir. MODX se saltará los archivos que coincidan con alguno de ellos.';
$_lang['prop_s3.thumbnailQuality_desc'] = 'La calidad de las miniaturas a generar, en una escala del 0 al 100.';
$_lang['prop_s3.thumbnailType_desc'] = 'El tipo de archivo de las miniaturas generadas.';
$_lang['prop_s3.url_desc'] = 'La URL de la instancia de Amazon S3.';
$_lang['prop_s3.endpoint_desc'] = 'Alternative S3-compatible endpoint URL, e.g., "https://s3.<region>.example.com". Review your S3-compatible provider’s documentation for the endpoint location. Leave empty for Amazon S3';
$_lang['prop_s3.region_desc'] = 'Region of the bucket. Example: us-west-1';
$_lang['prop_s3.prefix_desc'] = 'Optional path/folder prefix';
$_lang['s3_no_move_folder'] = 'El manejador de S3 no permitemover carpetas en este momento.';

/* ftp source type */
$_lang['prop_ftp.host_desc'] = 'Server hostname or IP address';
$_lang['prop_ftp.username_desc'] = 'Username for authentication. Can be "anonymous".';
$_lang['prop_ftp.password_desc'] = 'Password of user. Leave empty for anonymous user.';
$_lang['prop_ftp.url_desc'] = 'If this FTP is has a public URL, you can enter its public http-address here. This will also enable image previews in the media browser.';
$_lang['prop_ftp.port_desc'] = 'Port of the server, default is 21.';
$_lang['prop_ftp.root_desc'] = 'The root folder, it will be opened after connection';
$_lang['prop_ftp.passive_desc'] = 'Enable or disable passive ftp mode';
$_lang['prop_ftp.ssl_desc'] = 'Enable or disable ssl connection';
$_lang['prop_ftp.timeout_desc'] = 'Timeout for connection in seconds.';

/* file type */
$_lang['PNG'] = 'PNG';
$_lang['JPG'] = 'JPG';
$_lang['GIF'] = 'GIF';

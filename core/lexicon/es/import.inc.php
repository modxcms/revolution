<?php
/**
 * Import Spanish lexicon entries
 *
 * @language es_MX
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Especifica una lista separada por comas de extensiones de archivo para importar.<br /><small><em>Déjalo en blanco para importar todos los archivos de acuerdo a los tipos de contenido disponibles en tu sitio.  Tipos desconocidos serán mapeados como texto normal</em></small>';
$_lang['import_base_path'] = 'Ingresa la ruta de archivos base que contiene los archivos a importar.<br /><small><em>Déjalo en blanco para usar la configuración de la ruta de archivos estática del contexto objetivo.</em></small>';
$_lang['import_duplicate_alias_found'] = 'El recurso [[+id]] ya está usando el alias [[+alias]].  Por favor ingresa un alias original.';
$_lang['import_element'] = 'Ingresa el el elemento raíz de HTML a importar:';
$_lang['import_enter_root_element'] = 'Ingresa el elemento raíz a importar:';
$_lang['import_files_found'] = '<strong>Se encontraron %s documentos a importar...</strong><p/>';
$_lang['import_parent_document'] = 'Documento Padre:';
$_lang['import_parent_document_message'] = 'Usa el árbol de documentos presentado abajo para seleccionar el lugar del padre donde se importarán tus archivos.';
$_lang['import_resource_class'] = 'Selecciona una clase modResource a importar:<br /><small><em>Usa modStaticResource para enlazar a archivos estáticos, o modDocument para copiar el contenido a la base de datos.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">Falló!</span>';
$_lang['import_site_html'] = 'Importar sitio de HTML';
$_lang['import_site_importing_document'] = 'Importando archivo <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Tiempo máximo de importación:';
$_lang['import_site_maxtime_message'] = 'Aquí puedes especificar el número de segundos que el Admin de Contenido se puede tomar en importar el sitio (anulando la configuración de PHP).  Ingresa 0 para tiempo ilimitado.  Por favor toma nota, configurándolo a 0 o a un número realment alto puede hacer cosas raras a tu servidor y no es recomendable.';
$_lang['import_site_message'] = '<p>Usando esta herramient puedes importar el contenido de un conjunto de archivos HTML a la base de datos. <em>Por favor nota que necesitarás copiar tus archivos y/o carpetas en la carpeta core/import.</em></p><p>Por favor llena la las opciones de la forma de abajo, selecciona opcionalmente un recurso padre para los archivos importados del árbol de documentos y presiona \'Importar HTML\' para comenzar el proceso de importación.  Los archivos importados serán guardados en el lugar seleccionado, usando, donde sea posible, los nombres de archivo como los alias del documento, el título de la página como el título del documento.</p>';
$_lang['import_site_resource'] = 'Importar recursos de archivos estáticos';
$_lang['import_site_resource_message'] = '<p>Usando esta herramienta puedes importar recursos de un conjunto de archivos estáticos a la base de datos. <em>Por favor nota que necesitarás copuar tus archivos y/o carpetas en la carpeta core/import.</em></p><p>Por favor llena las opciones de la forma de abajo, selecciona opcionalmente un recurso padre para los archivos importados en el árbol de documentos y presiona \'Importar Recursos\' para comenzar el proceso de importación.  Los archivos importados serán guardados en el lugar seleccionado, usando, donde sea posible, los nombres del archivo como el alias del documento y, si son HTML, el título de la página como el título del documento.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">Saltado!</span>';
$_lang['import_site_start'] = 'Comenzar Importación';
$_lang['import_site_success'] = '<span style="color:#009900">Éxito!</span>';
$_lang['import_site_time'] = 'Importación terminada.  La importación tomó %s segundos para completar.';
$_lang['import_use_doc_tree'] = 'Usa el árbol de documentos presentado abajo para seleccionar el lugar a donde importar los archivos.';
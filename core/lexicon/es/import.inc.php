<?php
/**
 * Import English lexicon entries
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['import_allowed_extensions'] = 'Lista separada por comas de extensiones de archivo para importar.<br /><small><em>Dejar en blanco para importar todos los archivos de acuerdo a los tipos de contenido disponibles en tu sitio. Los tipos de archivo desconocidos serán tratados como archivos de texto</em></small>';
$_lang['import_base_path'] = 'Ruta principal que contiene los archivos a importar.<br /><small><em>Dejar en blanco para usar la configuración de la ruta de archivos estática del contexto destino.</em></small>';
$_lang['import_duplicate_alias_found'] = 'El recurso [[+id]] ya tiene asignado el alias [[+alias]]. Por favor, introduce un alias único.';
$_lang['import_element'] = 'Introduce el elemento raíz de HTML a importar:';
$_lang['import_element_help'] = 'Proporciona un JSON con asociaciones "field":"value". Si el valor comienza con $ es un  selector tipo jQuery. El campo puede ser un Campo de Recursos o un nombre de TV (variable de plantilla).';
$_lang['import_enter_root_element'] = 'Introduce el elemento raíz a importar:';
$_lang['import_files_found'] = '<strong>Se encontraron %s documentos a importar...</strong><p/>';
$_lang['import_parent_document'] = 'Documento Padre:';
$_lang['import_parent_document_message'] = 'Usa el árbol de documentos abajo mostrado para seleccionar el lugar del padre donde se importarán tus archivos.';
$_lang['import_resource_class'] = 'Selecciona una clase "modResource" a importar:<br /><small><em>Usa "modStaticResource" para enlazar a archivos estáticos, o "modDocument" para copiar el contenido a la base de datos.</em></small>';
$_lang['import_site_failed'] = '<span style="color:#990000">¡Falló!</span>';
$_lang['import_site_html'] = 'Importar sitio desde HTML';
$_lang['import_site_importing_document'] = 'Importando archivo <strong>%s</strong> ';
$_lang['import_site_maxtime'] = 'Tiempo máximo de importación:';
$_lang['import_site_maxtime_message'] = 'Aquí puede especificarse el número de segundos que el Administrador de Contenido puede tardar en importar el sitio (anulando la configuración de PHP). Introduce 0 para no establecer un límite. Ten en cuenta que anular este valor poniéndolo a 0 puede causar un comportamiento anómalo en el servidor.';
$_lang['import_site_message'] = '<p>Usando esta herramienta puedes importar el contenido de un conjunto de archivos HTML a la base de datos. <em>Toma en cuenta que necesitarás copiar tus archivos y/o carpetas en la carpeta core/import.</em></p><p>Por favor completa las opciones del formulario de abajo, selecciona opcionalmente un recurso padre para los archivos importados del árbol de documentos y haz click en "Importar HTML" para comenzar el proceso de importación. Los archivos importados serán guardados en el lugar seleccionado, usando, donde sea posible, los nombres de archivo como los alias del documento, y el título de la página como el título del documento.</p>';
$_lang['import_site_resource'] = 'Importar recursos de archivos estáticos';
$_lang['import_site_resource_message'] = '<p>Usando esta herramienta puedes importar el contenido de un conjunto de archivos estáticos a la base de datos. <em>Toma en cuenta que necesitarás copiar tus archivos y/o carpetas en la carpeta core/import.</em></p><p>Por favor completa las opciones del formulario de abajo, selecciona opcionalmente un recurso padre para los archivos importados del árbol de documentos y haz click en "Importar Recursos" para comenzar el proceso de importación. Los archivos importados serán guardados en el lugar seleccionado, usando, donde sea posible, los nombres de archivo como los alias del documento, y si son HTML, el título de la página como el título del documento.</p>';
$_lang['import_site_skip'] = '<span style="color:#990000">¡Omitido!</span>';
$_lang['import_site_start'] = 'Comenzar Importación';
$_lang['import_site_success'] = '<span style="color:#009900">¡Éxito!</span>';
$_lang['import_site_time'] = 'Importación terminada. La importación tardó %s segundos para completar.';
$_lang['import_use_doc_tree'] = 'Usa el árbol de documentos abajo mostrado para seleccionar el lugar del padre donde se importarán tus archivos.';
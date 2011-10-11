<?php
/**
 * Export Spanish lexicon topic
 *
 * @language es_MX
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Incluir archivos no cacheables:';
$_lang['export_site_exporting_document'] = 'Exportando archivo <strong>%s</strong> de <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">Falló!</span>';
$_lang['export_site_html'] = 'Exportar sitio a HTML';
$_lang['export_site_maxtime'] = 'Tiempo máximo de exportación:';
$_lang['export_site_maxtime_message'] = 'Aquí puedes especificar el número de segundos que MODX se puede llevar en exportar el sitio (anulando la configuración de PHP). Ingresa 0 para tiempo ilimitado.  Por favor toma nota, que configurando 0 o un número realmente alto puede hacer cosas raras a tu servidor y no es recomendado.';
$_lang['export_site_message'] = '<p>Usando esta función puedes exportar el sitio entero a archivos de HTML.  Por favor toma nota, sin embargo, de que perderás mucha funcionalidad de MODX si lo haces:</p><ul><li>Las veces que una página exportada a sido leída no serán registradas.</li><li>Los snippets interactivos NO funcionarán en archivos exportadoss</li><li>Sólo documentos regulares serán exportados, Weblinks no serán exportados.</li><li>El proceso de exportación puede fallar si tus documentos contienen snippets que manden headers de redirección.</li><li>Dependiendo de como hayas escrito tus documentos, hojas de estilo e imágenes, el diseño de tu sitio podría estar roto.  Para arreglarlo, puedes guardar/mover tus archivos exportados al mismo directorio donde está el archivo principal de MODX, index.php.</li></ul><p>Por favor llena la forma y presiona \'Exportar\' para comenzar el proceso de exportación.  Los archivos creados serán guardados en el lugar que tu especifiques, usando, donde sea posible, los alias de documentos como nombres de archivos.  Mientra se exporta tu sitio, es mejor tener el artículo de la configuración de MODX \'Alias amigables\' configurado a \'si\'.  Dependiendo en el tamaño de tu sitio, la exportación puede tardarse.</p><p><em>Cualesquiera archivos existentes serán sobre-escritos por los archivos nuevos si sus nombres son idénticos!</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Se encontraron %s documentos para exportar...</strong></p>';
$_lang['export_site_prefix'] = 'Prefijo de archivo:';
$_lang['export_site_start'] = 'Comenzar exportación';
$_lang['export_site_success'] = '<span style="color:#009900">Éxito!</span>';
$_lang['export_site_suffix'] = 'Sufijo de archivo:';
$_lang['export_site_target_unwritable'] = 'El directorio objetivo no es escribible.  Por favor asegúrate de que el directorio es escribible y trata nuevamente.';
$_lang['export_site_time'] = 'Exportación terminada. La exportación tomó %s segundos para completarse.';
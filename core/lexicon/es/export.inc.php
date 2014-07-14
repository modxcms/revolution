<?php
/**
 * Export English lexicon topic
 *
 * @language en
 * @package modx
 * @subpackage lexicon
 */
$_lang['export_site_cacheable'] = 'Incluir archivos no almacenables en caché:';
$_lang['export_site_exporting_document'] = 'Exportando archivo <strong>%s</strong> de <strong>%s</strong><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small><i>%s</i>, id %s</small><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
$_lang['export_site_failed'] = '<span style="color:#990000">¡Falló!</span>';
$_lang['export_site_html'] = 'Exportar sitio a HTML';
$_lang['export_site_maxtime'] = 'Tiempo límite de exportación:';
$_lang['export_site_maxtime_message'] = 'Aquí puede especificarse el número de segundos que MODX puede tardar en exportar el sitio (anulando la configuración de PHP). Introduce 0 para no establecer un límite. Ten en cuenta que anular este valor poniéndolo a 0 puede causar un comportamiento anómalo en el servidor.';
$_lang['export_site_message'] = '<p>Usando esta función se puede exportar el sitio entero a archivos HTML. Sin embargo, se perderán muchas de las funcionalidades de MODX:</p><ul><li>Las visitas de una página exportada no serán registradas.</li><li>Los snippets interactivos no funcionarán en archivos exportados</li><li>Sólo serán exportados los documentos regulares, los Weblinks no serán exportados.</li><li>El proceso de exportación puede fallar si tus documentos contienen snippets que manden cabeceras de redirección.</li><li>Dependiendo de cómo hayas escrito tus documentos, hojas de estilo e imágenes, el diseño de tu sitio podría perderse. Para restaurarlo, puedes guardar/mover tus archivos exportados al mismo directorio donde está el archivo principal de MODX, index.php.</li></ul><p>Completa el formulario y pulsa "Exportar" para comenzar el proceso de exportación. Los archivos serán guardados en la carpeta escogida, utilizando, donde sea posible, los alias como nombres de archivo. Para la exportación es recomendable tener activada la función "URLs amigables" de MODX. Dependiendo del tamaño del sitio, la exportación podría tardar un rato. Durante la exportación.</p><p><em>se sobreescribiran los archivos de destino si los nombres coinciden.</em></p>';
$_lang['export_site_numberdocs'] = '<p><strong>Se encontraron %s documentos para exportar...</strong></p>';
$_lang['export_site_prefix'] = 'Prefijo de archivo:';
$_lang['export_site_start'] = 'Comenzar exportación';
$_lang['export_site_success'] = '<span style="color:#009900">¡Éxito!</span>';
$_lang['export_site_suffix'] = 'Sufijo de archivo:';
$_lang['export_site_target_unwritable'] = 'No se puede escribir en la carpeta de destino. Por favor, asegúrate de que MODX tiene permisos de escritura sobre el directorio e inténtalo de nuevo.';
$_lang['export_site_time'] = 'Exportación completada. La exportación ha tardado %s segundos en realizarse.';
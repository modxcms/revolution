<?php
/**
 * TV Widget Spanish lexicon topic
 *
 * @language es_MX
 * @package modx
 * @subpackage lexicon
 */
$_lang['attributes'] = 'Atributos';
$_lang['capitalize'] = 'Capitalizar';
$_lang['checkbox'] = 'Opciones Múltiples';
$_lang['class'] = 'Clase';
$_lang['combo_allowaddnewdata'] = 'Permitir Añadir Nuevos Artículos';
$_lang['combo_allowaddnewdata_desc'] = 'Cuando sea Si, permite añadir artículos que no existan en la lista.  El valor prefijado es No.';
$_lang['combo_forceselection'] = 'Forzar Selección a Lista';
$_lang['combo_forceselection_desc'] = 'Si se usa Type-Ahead, si esto está configurado a Si, sólo permite el ingreso de artículos en la lista.';
$_lang['combo_listempty_text'] = 'Vaciar Texto de Lista';
$_lang['combo_listempty_text_desc'] = 'Si Type-Ahead está activado, y el usario ingresa un valor que no esté en la lista, mostrar este texto.';
$_lang['combo_listwidth'] = 'Ancho de Lista';
$_lang['combo_listwidth_desc'] = 'El ancho, en pixeles, de la lista de menu desplegable.  El valor prefijado es el ancho del combobox.';
$_lang['combo_maxheight'] = 'Altura Máxima';
$_lang['combo_maxheight_desc'] = 'La altura máxima en pixeles de la lista desplegable antes de que las barras de navegación sean mostradas (valor prefijado es 300).';
$_lang['combo_stackitems'] = 'Encimar Artículos Seleccionados';
$_lang['combo_stackitems_desc'] = 'Cuando está configurado a Si, los artículos estarán encimados uno por línea.  El valor prefijado es No, el cual musetra los artículos en una línea.';
$_lang['combo_title'] = 'Encabezado de la Lista';
$_lang['combo_title_desc'] = 'Si se provee, un elemento de encabezado es creado conteniendo este texto y añadido a la parte de arriba de la lista desplegable.';
$_lang['combo_typeahead'] = 'Habilitar Type-Ahead';
$_lang['combo_typeahead_desc'] = 'Si está configuradoa Si, llenar y auto-seleccionar lo que falta del texto que se está ingresando después de un retrazo configurable (Retraso de Type-Ahead) si coincide con un valor conocido (el valor prefijado es No).';
$_lang['combo_typeahead_delay'] = 'Retrazo de Type-Ahead';
$_lang['combo_typeahead_delay_desc'] = 'El tiempo en milisegundos que se espera hasta que el texto Type-Ahead es mostrado si Type-Ahead está habilitado (el valor prefijado es 250).';
$_lang['date'] = 'Fecha';
$_lang['date_format'] = 'Formato de Fecha';
$_lang['date_use_current'] = 'Si no hay valor, usa fecha actual';
$_lang['default'] = 'Prefijado';
$_lang['delim'] = 'Separador';
$_lang['delimiter'] = 'Separador';
$_lang['disabled_dates'] = 'Disabled Dates';
$_lang['disabled_dates_desc'] = 'Una lista separada por comas de "fechas" a deshabilitar, como palabras.  Estas palabras serán usadas para construir una expresión regular dinámica así que son muy poderosas.  Algunos ejemplos:<br />
- Deshabilitar estas fechas exactas: 2003-03-08,2003-09-16<br />
- Deshabilitar estos días para cada año: 03-08,09-16<br />
- Sólo coincide el principio (útil si estás usando años cortos): ^03-08<br />
- Deshabilitar cada día en Marzo 2006: 03-..-2006<br />
- Deshabilitar cada día en cada Marzo: ^03<br />
Nota que el formato de las fechas incluido en la lista deberá coincidir exáctamente el formato configurado.  De manera que se soporten expresiones regulares, si estás usando un formato de fecha que tienen un ".", tendrás que escapar el punto cuando restrinjas fechas.';
$_lang['disabled_days'] = 'Días Deshabilitados';
$_lang['disabled_days_desc'] = 'Una lista separada por comas de los días a deshabilitar, basado en 0 (el valor prefijado es null).  Algunos ejemplos:<br />
- Deshabilitar Domingo y Sábado: 0,6<br />
- Deshabilitar días de la semana: 1,2,3,4,5';
$_lang['dropdown'] = 'Lista Desplegable';
$_lang['earliest_date'] = 'Fecha Más Temprana';
$_lang['earliest_date_desc'] = 'La fecha más temprana permitida que puede ser seleccionada.';
$_lang['earliest_time'] = 'Hora Más Temprana';
$_lang['earliest_time_desc'] = 'La hora más temprana permitida que puede ser seleccionada.';
$_lang['email'] = 'Email';
$_lang['file'] = 'Archivo';
$_lang['height'] = 'Altura';
$_lang['hidden'] = 'Oculto';
$_lang['htmlarea'] = 'Area de HTML';
$_lang['htmltag'] = 'Etiqueta HTML';
$_lang['image'] = 'Imagen';
$_lang['image_align'] = 'Alinear';
$_lang['image_align_list'] = 'ninguna,base,arriba,centro,abajo,arribatexto,abscentro,absabajo,izquierda,derecha';
$_lang['image_alt'] = 'Texto Alterno';
$_lang['image_allowedfiletypes'] = 'Extensiones de Archivo Permitidas';
$_lang['image_allowedfiletypes_desc'] = 'Si está configurado, restringirá los archivos mostrados a sólo las extensiones especificádas.  Por favor especifíca en una lista separada por comas, sin el .';
$_lang['image_basepath'] = 'Ruta Base';
$_lang['image_basepath_desc'] = 'La ruta del archivo a la que apunta la VdP de Imagen.  Si no está configurada, usará la configuración en filemanager_path, o la ruta base de MODX.  Puedes usar [[++base_path]], [[++core_path]] y [[++assets_path]] en este valor.';
$_lang['image_basepath_relative'] = 'Ruta Base Relativa';
$_lang['image_basepath_relative_desc'] = 'Si la configuración de la Ruta Base de arriba no es relativa a la ruta de instalación de MODX, configura esto a Si.';
$_lang['image_baseurl'] = 'URL Base';
$_lang['image_baseurl_desc'] = 'El URL de archivo al que apunta la VdP de Imagen.  Si no testá configurada, usará la configuración en filemanager_url setting, o el URL base de MODX.   Puedes usar [[++base_url]], [[++core_url]] y [[++assets_url]] en este valor.';
$_lang['image_baseurl_relative'] = 'Url Base Relativo';
$_lang['image_baseurl_relative_desc'] = 'Si la configuración del URL Base de arriba no es relativo al URL de instalación de MODX, configura esto a Si.';
$_lang['image_border_size'] = 'Tamaño de Borde';
$_lang['image_hspace'] = 'Espacio H';
$_lang['image_vspace'] = 'Espacio V';
$_lang['latest_date'] = 'Última Fecha';
$_lang['latest_date_desc'] = 'La última fecha permitida que puede ser seleccionada.';
$_lang['latest_time'] = 'Última Hora';
$_lang['latest_time_desc'] = 'La última hora permitida que puede ser seleccionada.';
$_lang['listbox'] = 'Lista (Sel. Sencilla)';
$_lang['listbox-multiple'] = 'Lista  (Sel. Múltiple)';
$_lang['lower_case'] = 'Minúsculas';
$_lang['max_length'] = 'Longitud Máxima';
$_lang['min_length'] = 'Longitud Mínima';
$_lang['name'] = 'Nombre';
$_lang['number'] = 'Número';
$_lang['number_allowdecimals'] = 'Permitir Decimales';
$_lang['number_allownegative'] = 'Permitar Negativos';
$_lang['number_decimalprecision'] = 'Precisión Decimal';
$_lang['number_decimalprecision_desc'] = 'La precisión máxima después del separador decimal (el valor prefijado es 2).';
$_lang['number_decimalseparator'] = 'Separador Decimal';
$_lang['number_decimalseparator_desc'] = 'Caracter(es) permitidos como el separador decimal (el valor prefijado es ".")';
$_lang['number_maxvalue'] = 'Valor Máximo';
$_lang['number_minvalue'] = 'Valor Mínimo';
$_lang['option'] = 'Lista (Radio)';
$_lang['parent_resources'] = 'Recursos Padre';
$_lang['radio_columns'] = 'Columnas';
$_lang['radio_columns_desc'] = 'El número de columnas en las que las opciones de radio son mostradas.';
$_lang['rawtext'] = 'Texto (obsoleto)';
$_lang['rawtextarea'] = 'Area de Texto (obsoleto)';
$_lang['required'] = 'Permitir Vacío';
$_lang['required_desc'] = 'Si se configura a No, MODX no permitirá al usuario guardar el Recurso hasta que un valor válido, no vacío ha sido ingresado.';
$_lang['resourcelist'] = 'Lista de Recursos';
$_lang['resourcelist_depth'] = 'Profundidad';
$_lang['resourcelist_depth_desc'] = 'Los niveles de profundidad que serán interrogados por la consulta para obtener la lista de Recursos. El valor prefijado es 10.';
$_lang['resourcelist_includeparent'] = 'Incluir Padres';
$_lang['resourcelist_includeparent_desc'] = 'Si está configurado a Si, incluir+a los Recursos nombrados en el campo Padres en la lista.';
$_lang['resourcelist_limit'] = 'Límite';
$_lang['resourcelist_limit_desc'] = 'El número de Recursos en la lista.  0 o vacío significa infinito.';
$_lang['resourcelist_parents'] = 'Padres';
$_lang['resourcelist_parents_desc'] = 'Una lista de IDs de los hijos para la lista.';
$_lang['resourcelist_where'] = 'Condiciones WHERE';
$_lang['resourcelist_where_desc'] = 'Un objeto JSON con las condiciones WHERE para filtrar la consulta que obtiene los Recursos de la lista. (No soporta búsqueda en VdP.)';
$_lang['richtext'] = 'Texto Formateado';
$_lang['sentence_case'] = 'Oraciones Capitalizadas';
$_lang['shownone'] = 'Permitir Selección Vacía';
$_lang['shownone_desc'] = 'Permitir al usuario seleccionar una selección vacía la cual es un valor vacío.';
$_lang['start_day'] = 'Día de Comienzo';
$_lang['start_day_desc'] = 'Índice del día en el cual la semana deberá comenzar, basado en 0 (el valor prefijado es 0, que es el Domingo)';
$_lang['string'] = 'Cadena de Caracteres';
$_lang['string_format'] = 'Formato de Cadena de Caracteres';
$_lang['style'] = 'Estilo';
$_lang['tag_id'] = 'ID de Etiqueta';
$_lang['tag_name'] = 'Nombre de Etiqueta';
$_lang['target'] = 'Objetivo';
$_lang['text'] = 'Texto';
$_lang['textarea'] = 'Area de Texto';
$_lang['textareamini'] = 'Area de Texto (Mini)';
$_lang['textbox'] = 'Caja de Texto';
$_lang['time_increment'] = 'Incremento en Tiempo';
$_lang['time_increment_desc'] = 'El número de minutos entre cada valor de tiempo en la lista (el valor prefijado es 15).';
$_lang['title'] = 'Título';
$_lang['upper_case'] = 'Mayúsculas';
$_lang['url'] = 'URL';
$_lang['url_display_text'] = 'Mostrar Texto';
$_lang['width'] = 'Ancho';
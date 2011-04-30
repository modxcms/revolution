<?php
/**
 * Config Check Spanish lexicon topic
 *
 * @language es_MX
 * @package modx
 * @subpackage lexicon
 */
$_lang['configcheck_admin'] = 'Por favor contacta a un administrador del sistema y adviértele acerca de este mensaje!';
$_lang['configcheck_cache'] = 'el directorio del cache no es escribible';
$_lang['configcheck_cache_msg'] = 'MODX no puede escribir al directorio del cache.  MODX todavía funcionará como es esperado, pero no se llevará a cabo ninguna operación de cache.  Para resolver esto has el directorio /_cache/ escribible.';
$_lang['configcheck_configinc'] = 'El archivo Config todavía es escribible!';
$_lang['configcheck_configinc_msg'] = 'Tu sitio es vulnerable a los hackers quienes pudieran hacer mucho daño al sitio.  Por favor has tu archivo config sólo para lectura!  Si no eres el administrador del sitio, por favor contacta a un administrador del sistema y adviértele acerca de este mensaje!  El archivo está localizado en core/config/config.inc.php';
$_lang['configcheck_default_msg'] = 'Una advertencia no especificada fue encontrada.  Lo cual es extraño.';
$_lang['configcheck_errorpage_unavailable'] = 'La página de Error de tu sitio no está disponible.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Esto significa que tu página de Error no es accesible para navegadores de internet o no existe.  Esto puede llevar a una condición de recursividad y a muchos errores en tus bitácoras de errores.  Asegúrate que no haya grupos de usuarios asignados a la página.';
$_lang['configcheck_errorpage_unpublished'] = 'La página de Erro de tu sitio no está publicada o no existe.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Esto significa que tu página de Error no es accesible para el público en general.  Publica la página o asegúrate de que esté asignada a un documento existenteen tu árbol del sitio en el menu Sistema &gt; Configuración del Sistema.';
$_lang['configcheck_images'] = 'El directorio de imágenes no es escribible';
$_lang['configcheck_images_msg'] = 'El directorio de imágenes no es escribible o no existe.  Esto significa que las funciones del Admin de Imágenes en el editor no funcionarán!';
$_lang['configcheck_installer'] = 'El instalador está presente todavía';
$_lang['configcheck_installer_msg'] = 'El directorio setup/ contiene el instalador de MODX.  Sólo imagina que podría pasar si una persona malvada encuentra esta carpeta y activa el instalador!  Probablemente no llegue muy lejos, porque necesitará ingresar información del usuario para la base de datos, pero pero de todas maneras es mejor remover esta carpeta de tu servidor.';
$_lang['configcheck_lang_difference'] = 'Número incorrecto de entradas en el archivo de idioma';
$_lang['configcheck_lang_difference_msg'] = 'El idioma seleccionado actualmente tiene un número diferente de entradas que el lenguaje prefijado.  Mientras esto no es necesariamente un problema, esto puede significar que el archivo de idioma necesita ser actualizado.';
$_lang['configcheck_notok'] = 'Uno o más de los detalles de configuración no estuvieron OK: ';
$_lang['configcheck_ok'] = 'La prueba pasó OK - no hay advertencias que reportar.';
$_lang['configcheck_register_globals'] = 'register_globals está configurada a ON en tu archivo de configuración php.ini';
$_lang['configcheck_register_globals_msg'] = 'Esta configuración hace tu sitio mucho más suceptible a ataques de Cross Site Scripting (XSS). Deberías hablar con tu compañía de hospedaje acerca de lo que puedes hacer para deshabilitar esta configuración.';
$_lang['configcheck_title'] = 'Prueba de Configuración';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'La página de No Autorizado de tu sitio no está publicada o no existe.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Esto significa que tu página de No Autorizado no es accesible para navegadores de Internet o no existe.  Esto puede llevar a una condición recursiva y a muchos errores en tus bitácoras de errores.  Asegúrate de que no haya grupos de usuarios asignados a la página.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'La página de No Autorizado definida en la configuración del sitio no está publicada.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Esto significa que tu página de No Autorizado no es accesible para el público en general.  Publica la página o asegúrate de que esté asignada a un documento existente en tu árbol del sitio en el menu Sistema &gt; Configuración del Sistema.';
$_lang['configcheck_warning'] = 'Advertencia de Configuración:';
$_lang['configcheck_what'] = 'Que significa esto?';
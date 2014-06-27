﻿<?php
/**
 * Config Check Spanish lexicon topic
 *
 * @language es_ES
 * @package MODX
 * @subpackage lexicon
 *
 * @author libnova, S.L. <http://www.libnova.com/>
 * @updated 2014-03-10
 */
$_lang['configcheck_admin'] = 'Por favor, ¡Contacta con un administrador del sitio e infórmale de este mensaje!';
$_lang['configcheck_allowtagsinpost_context_enabled'] = 'Configuración de Contexto "allow_tags_in_post" Activada fuera del Contexto "mgr"';
$_lang['configcheck_allowtagsinpost_context_enabled_msg'] = 'La Configuración de Contexto "allow_tags_in_post" está habilitada para Contextos diferentes a "mgr". MODX recomienda desactivar esta configuración a no ser que se necesite permitir explícitamente a los usuarios insertar etiquetas de MODX, entidades numéricas, o scripts HTML a través de métodos POST en los formularios del sitio. Esto debería estar desactivado por defecto excepto en el contexto "mgr".';
$_lang['configcheck_allowtagsinpost_system_enabled'] = 'Configuración de Sistema "allow_tags_in_post"';
$_lang['configcheck_allowtagsinpost_system_enabled_msg'] = 'La Configuración de Sistema "allow_tags_in_post" está habilitada. MODX recomienda desactivar esta configuración a no ser que se necesite permitir explícitamente a los usuarios insertar etiquetas de MODX, entidades numéricas, o scripts HTML a través de métodos POST en los formularios del sitio. Es mejor habilitarlo a través de la Configuración del Contexto para Contextos específicos.';
$_lang['configcheck_cache'] = 'sin derechos de escritura sobre el directorio de memoria caché';
$_lang['configcheck_cache_msg'] = 'MODX no tiene derechos de escritura sobre el directorio caché. MODX funcionará normalmente, pero las funciones de caché no funcionarán. Para resolverlo, otorgar derechos de escritura sobre el directorio "/_cache/".';
$_lang['configcheck_configinc'] = '¡El archivo de configuración no es de solo lectura!';
$_lang['configcheck_configinc_msg'] = 'El sitio es vulnerable a hackers, que podrían vulnerar el sitio. Por favor, ¡Cambia el archivo de configuración a sólo lectura! Si no eres un administrador del sitio, por favor, contacta con uno e infórmale de este mensaje! El archivo está localizado en core/config/config.inc.php';
$_lang['configcheck_default_msg'] = 'Se encontró una alerta desconocida. Esto es muy raro.';
$_lang['configcheck_errorpage_unavailable'] = 'Página de Error del sitio no disponible.';
$_lang['configcheck_errorpage_unavailable_msg'] = 'Esto significa que la página de Error no es accesible a través del navegador o no existe. Esto puede llevar a un bucle y colapsar el registro de errores. Asegúrate que no haya grupos de usuarios asignados a la página de error.';
$_lang['configcheck_errorpage_unpublished'] = 'La página de Error del sitio no está publicada o no existe.';
$_lang['configcheck_errorpage_unpublished_msg'] = 'Esto significa que la página de Error no es accesible públicamente. Publica la página o asegúrate de que esté asignada a un documento existente en tu árbol de recursos del sitio en el menu Sistema &gt; Configuración del Sistema.';
$_lang['configcheck_images'] = 'Sin derechos de escritura sobre el directorio de imágenes';
$_lang['configcheck_images_msg'] = 'MODX no tiene derechos de escritura sobre el directorio de imágenes o no existe. ¡Las funciones del Administrador de Imágenes en el editor no funcionarán!';
$_lang['configcheck_installer'] = 'El script de instalación aún está presente';
$_lang['configcheck_installer_msg'] = 'El directorio "setup/" contiene el instalador de MODX. ¡Sólo imagina lo que podría pasar si alguien activara el instalador! Probablemente no llegue muy lejos, porque necesitará introducir los datos de acceso a la base de datos, pero es mejor prevenir y eliminar la carpeta de tu servidor.';
$_lang['configcheck_lang_difference'] = 'Número incorrecto de entradas en el archivo de idioma';
$_lang['configcheck_lang_difference_msg'] = 'El archivo de idioma configurado actualmente tiene un número diferente de entradas que el archivo de idioma por defecto. A pesar de que esto no supone necesariamente un problema, puede significar que el archivo de idioma necesita ser actualizado.';
$_lang['configcheck_notok'] = 'Uno o más detalles de configuración no fueron correctos: ';
$_lang['configcheck_ok'] = 'La comprobación fue correcta - sin advertencias que mostrar.';
$_lang['configcheck_register_globals'] = '"register_globals" está activado en el archivo de configuración php.ini';
$_lang['configcheck_register_globals_msg'] = 'Esta configuración hace el sitio más vulnerable a ataques del tipo Cross Site Scripting (XSS). Si el servidor no es propio, el proveedor del servicio de alojamiento del sitio web deberá deshabilitar esta configuración.';
$_lang['configcheck_title'] = 'Prueba de Configuración';
$_lang['configcheck_unauthorizedpage_unavailable'] = 'La página de "Acceso No Autorizado" del sitio no está publicada o no existe.';
$_lang['configcheck_unauthorizedpage_unavailable_msg'] = 'Esto significa que la página de "Acceso No Autorizado" no es accesible a través del navegador o no existe. Esto puede llevar a un bucle y colapsar el registro de errores. Asegúrate de que no haya grupos de usuarios asignados a la página.';
$_lang['configcheck_unauthorizedpage_unpublished'] = 'La página de "Acceso No Autorizado" definida en la configuración del sitio no está publicada.';
$_lang['configcheck_unauthorizedpage_unpublished_msg'] = 'Esto significa que la página de "Acceso No Autorizado" no es accesible públicamente. Publica la página o asegúrate de que esté asignada a un documento existente en tu árbol de recursos del sitio en el menu Sistema &gt; Configuración del Sistema.';
$_lang['configcheck_warning'] = 'Advertencia de Configuración:';
$_lang['configcheck_what'] = '¿Qué significa esto?';

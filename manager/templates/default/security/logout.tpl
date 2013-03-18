<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$_config.manager_direction}" lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
    <title>MODx :: {$_lang.permission_denied}</title>

    <link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/xtheme-gray-extend.css" />
    <link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/index.css" />
    <link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/login.css" />


    <script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/ext3/ext-all.js" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/modext/core/modx.js" type="text/javascript"></script>
    <script src="{$_config.connectors_url}lang.js.php?topic=login" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/modext/core/modx.form.handler.js" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/modext/core/modx.component.js" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/modext/util/utilities.js" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/modext/util/spotlight.js" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/modext/widgets/core/modx.panel.js" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/modext/widgets/core/modx.msg.js" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/modext/widgets/core/modx.window.js" type="text/javascript"></script>
    <script src="{$_config.manager_url}assets/modext/sections/login.js" type="text/javascript"></script>
    
    <meta name="robots" content="noindex, nofollow" />
    {literal}<style>body, html { background: #fafafa !important; }</style>{/literal}
	<script type="text/javascript">
	var SITE_NAME = '{$_config.site_name|escape}';
	var CONNECTORS_URL = '{$_config.connectors_url}';
	</script>
</head>
<body id="login" style="background-color: #fffffa;">

<div id="mx_loginbox">
	<form action="?" method="post">
		<h2>{$_lang.permission_denied}</h2>
		
		<p>{$_lang.permission_denied_msg}</p>
		
		<br />
		
		<input type="submit" name="logout" value="{$_lang.logout}" />
	</form>
</div>
<p class="loginLicense">
{$_lang.login_copyright}
</p>


</body>
</html>
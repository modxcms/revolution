<!doctype html>
<html {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.cultureKey}" xml:lang="{$_config.cultureKey}">
<head>
    <title>MODx :: {$_lang.permission_denied}</title>
    <meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />
    <link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/xtheme-gray-extend.css" />
    <link rel="stylesheet" type="text/css" href="{$indexCss}" />
    <link rel="stylesheet" type="text/css" href="{$loginCss}" />


    {if isset($_config.ext_debug) && $_config.ext_debug}
    <script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base-debug.js"></script>
    <script src="{$_config.manager_url}assets/ext3/ext-all-debug.js"></script>
    {else}
    <script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js"></script>
    <script src="{$_config.manager_url}assets/ext3/ext-all.js"></script>
    {/if}
    <script src="{$_config.manager_url}assets/modext/core/modx.js"></script>
    <script src="{$_config.connectors_url}lang.js.php?topic=login"></script>
    <script src="{$_config.manager_url}assets/modext/core/modx.form.handler.js"></script>
    <script src="{$_config.manager_url}assets/modext/core/modx.component.js"></script>
    <script src="{$_config.manager_url}assets/modext/util/utilities.js"></script>
    <script src="{$_config.manager_url}assets/modext/util/spotlight.js"></script>
    <script src="{$_config.manager_url}assets/modext/widgets/core/modx.panel.js"></script>
    <script src="{$_config.manager_url}assets/modext/widgets/core/modx.msg.js"></script>
    <script src="{$_config.manager_url}assets/modext/widgets/core/modx.window.js"></script>
    <script src="{$_config.manager_url}assets/modext/sections/login.js"></script>

    <meta name="robots" content="noindex, nofollow" />
    {literal}<style>body, html { background: #fafafa !important; }</style>{/literal}
	<script>
	var SITE_NAME = '{$_config.site_name|strip_tags|escape}';
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

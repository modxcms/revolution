<!doctype html>
<html {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>MODX :: {$_lang.modx_resource_browser}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />


<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/default/css/index{if $_config.compress_css}-min{/if}.css" />

{if isset($_config.ext_debug) && $_config.ext_debug}
<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base-debug.js"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all-debug.js"></script>
{else}
<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all.js"></script>
{/if}
<script src="{$_config.manager_url}assets/modext/core/modx.js"></script>
<script src="{$_config.connectors_url}lang.js.php?ctx=mgr&topic=category,file,resource&action={$smarty.get.a|strip_tags|default:''}"></script>
<script src="{$_config.connectors_url}modx.config.js.php?action={$smarty.get.a|strip_tags|default:''}{if $_ctx}&wctx={$_ctx}{/if}&HTTP_MODAUTH={$site_id|default|htmlspecialchars}"></script>

{$maincssjs}

{foreach from=$cssjs item=scr}
{$scr}
{/foreach}

{$rteincludes}
</head>
<body>

{literal}
<script>
Ext.onReady(function() {
    Ext.QuickTips.init();
    Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext3/resources/images/default/s.gif';{/literal}
    {if $rtecallback}{literal}
    MODx.onBrowserReturn = {/literal}{$rtecallback}{literal};{/literal}{/if}{literal}
    MODx.ctx = "{/literal}{if $_ctx}{$_ctx}{else}web{/if}{literal}";
    MODx.load({
       xtype: 'modx-browser-rte'
       ,auth: '{/literal}{$site_id}{literal}'
    });
});
</script>
{/literal}
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>MODX :: {$_lang.modx_resource_browser}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />


{if $_config.compress_css}
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}min/index.php?f={$_config.manager_url}templates/default/css/xtheme-modx.css,{$_config.manager_url}templates/default/css/index.css" />
{else}
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/default/css/xtheme-modx.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/default/css/index.css" />
{/if}

<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?ctx=mgr&topic=category,file,resource&action={$smarty.get.a|strip_tags}" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php?action={$smarty.get.a|strip_tags}{if $_ctx}&wctx={$_ctx}{/if}" type="text/javascript"></script>

{if $_config.compress_js_groups}
<script src="{$_config.manager_url}min/index.php?g=coreJs1" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs2" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs3" type="text/javascript"></script>
{/if}

{$maincssjs}
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.rte.browser.js" type="text/javascript"></script>

{foreach from=$cssjs item=scr}
{$scr}
{/foreach}

{$rteincludes}
<!--[if IE]>
<style type="text/css">body { behavior: url("{$_config.manager_url}templates/{$_config.manager_theme}/css/csshover3.htc"); }</style>
<link rel="stylesheet" type="text/css" href="templates/{$_config.manager_theme}/css/ie.css" />
<![endif]-->
</head>
<body>

{literal}
<script type="text/javascript">
Ext.onReady(function() {
    Ext.QuickTips.init();
    Ext.BLANK_IMAGE_URL = MODx.config.manager_url+'assets/ext3/resources/images/default/s.gif';
    MODx.onBrowserReturn = {/literal}{$rtecallback}{literal};
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

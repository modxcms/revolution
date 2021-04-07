<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$_config.manager_direction}" lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>{if $_pagetitle}{$_pagetitle|escape} | {/if}{$_config.site_name|strip_tags|escape}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

    {if $_config.manager_favicon_url}<link rel="shortcut icon" href="{$_config.manager_favicon_url}" />{/if}

<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
<link rel="stylesheet" type="text/css" href="{$indexCss}?v={$versionToken}" />

{if isset($_config.ext_debug) && $_config.ext_debug}
<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base-debug.js"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all-debug.js"></script>
{else}
<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all.js"></script>
{/if}
<script src="{$_config.manager_url}assets/modext/core/modx.js?v={$versionToken}"></script>
<script src="{$_config.connectors_url}lang.js.php?ctx=mgr&topic=topmenu,file,resource,trash,{$_lang_topics}&action={$smarty.get.a|default|htmlspecialchars}"></script>
<script src="{$_config.connectors_url}modx.config.js.php?action={$smarty.get.a|default|htmlspecialchars}{if $_ctx}&wctx={$_ctx}{/if}&HTTP_MODAUTH={$_authToken|default|htmlspecialchars}"></script>

{$maincssjs}
{foreach from=$cssjs item=scr}
{$scr}
{/foreach}

<script>
    Ext.onReady(function() {
        // Enable site name tooltip (on overflow only)
        if( Ext.get('site_name').dom.scrollWidth > Ext.get('site_name').dom.clientWidth ){
          new Ext.ToolTip({
              title: Ext.get('site_name').dom.title
              ,target: Ext.get('site_name')
          });
        }
        {if $_search}
        new MODx.SearchBar;
        {/if}
    });
</script>

</head>
<body id="modx-body-tag">

<div id="modx-browser"></div>
<div id="modx-container">
    <div id="modx-header">
        <div id="modx-navbar">
            <ul id="modx-user-menu">
                {* eval is used here to support nested variables *}
                {eval var=$userNav}
            </ul>

            <ul id="modx-topnav">
                <li id="modx-home-dashboard">
                    <a href="?" title="MODX {$_config.settings_version} ({$_config.settings_distro})
{$_lang.dashboard}">{$_lang.dashboard}</a>
                </li>
                <li id="modx-site-info">
                    <div id="site_name" class="info-item site_name" title="{$_config.site_name|strip_tags|escape}">{$_config.site_name|strip_tags|escape}</div>
                    {* TODO: Pull full_appname from docs/version.inc.php ? *}
                    <div class="info-item full_appname">MODX Revolution {$_config.settings_version}</div>
                </li>
                {if $_search}
                <li id="modx-manager-search-icon">
                    <a href="javascript:;" onclick="Ext.getCmp('modx-uberbar').toggle()" title="{$_lang.search}">
                        <span class="icon-stack icon-lg">
                          <i class="icon icon-square icon-stack-2x"></i>
                          <i class="icon icon-search icon-stack-1x"></i>
                        </span>
                    </a>
                </li>
                {/if}
                {eval var=$navb}
            </ul>
            {if $_search}
            <div id="modx-manager-search" role="search"></div>
            {/if}
        </div>
    </div>
        <div id="modAB"></div>
        <div id="modx-leftbar"></div>
        <div id="modx-action-buttons-container"></div>
        <div id="modx-content">
            <div id="modx-panel-holder"></div>

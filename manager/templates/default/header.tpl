<!doctype html>
<html dir="{$_config.manager_direction}" lang="{$_config.cultureKey}" xml:lang="{$_config.cultureKey}">
<head>
<title>{if $_pagetitle}{$_pagetitle|escape} | {/if}{$_config.site_name|strip_tags|escape}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="google" content="notranslate" />

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
<script src="{$_config.manager_url}assets/modext/core/modx.js?mv={$versionToken}"></script>
<script src="{$_config.manager_url}assets/lib/popper.min.js"></script>
<script src="{$_config.connectors_url}lang.js.php?ctx=mgr&topic=topmenu,file,resource,{$_lang_topics}&action={$smarty.get.a|default|htmlspecialchars}"></script>
<script src="{$_config.connectors_url}modx.config.js.php?action={$smarty.get.a|default|htmlspecialchars}{if $_ctx}&wctx={$_ctx}{/if}&HTTP_MODAUTH={$_authToken|default|htmlspecialchars}"></script>

<script>
    const tvPanelOverrides = [];
</script>

{$maincssjs}
{foreach from=$cssjs item=scr}
{$scr}
{/foreach}

<script>
    MODx.config.search_enabled = {$_search};
    if (!Ext.isEmpty(tvPanelOverrides)) {
        let fn = {},
            vd = {},
            ld = {}
            ;
        tvPanelOverrides.forEach(obj => {
            if (obj.hasOwnProperty('fn')) {
                fn = Object.assign(fn, obj.fn);
            }
            if (obj.hasOwnProperty('validatorDefs')) {
                vd = Object.assign(vd, obj.validatorDefs);
            }
            if (obj.hasOwnProperty('listenerDefs')) {
                ld = Object.assign(ld, obj.listenerDefs);
            }
        });
        Ext.override(MODx.panel.TV, {
            initComponent: function() {
                tvPanelOverrides.forEach(obj => {
                    if (obj.hasOwnProperty('initComponent')) {
                        if (obj.initComponent.hasOwnProperty('sharedComponentOverrides')) {
                            Ext.applyIf(this.sharedComponentOverrides, obj.initComponent.sharedComponentOverrides);
                        }
                        if (obj.initComponent.hasOwnProperty('validatorRefMap')) {
                            Ext.applyIf(this.validatorRefMap, obj.initComponent.validatorRefMap);
                        }
                        if (obj.initComponent.hasOwnProperty('addNewLoaderType')) {
                            this.addNewLoaderType(...obj.initComponent.addNewLoaderType);
                        }
                    }
                });
                this.validatorCustomDefs = vd;
                this.listenerCustomDefs = ld;

                MODx.panel.TV.superclass.initComponent.call(this);
            },
            fn
        });
    }
</script>
</head>
<body id="modx-body-tag">

<div id="modx-browser"></div>
<div id="modx-container">
    <div id="modx-header">
        <div id="modx-navbar">
            <ul id="modx-headnav">
                <li id="modx-home-dashboard">
                    <a href="?" title="{$_config.site_name|strip_tags|escape}">
                        <img src="{$_config.manager_url}templates/{$_config.manager_theme}/images/modx-icon-color.svg" title="{$_config.site_name|strip_tags|escape}">
                    </a>
                </li>
                <li id="modx-site-info">
                    <div class="info-item full_appname">{$_version.full_version|strip_tags|escape}</div>
                </li>
                <li id="modx-leftbar-trigger">
                    <a href="javascript:;">
                        <i class="icon"></i>
                    </a>
                </li>
                {if $_search}
                    <li id="modx-manager-search-icon" class="top">
                        <a href="javascript:;" title="{$_lang.search}" onclick="setTimeout(function(){ Ext.getCmp('modx-uberbar').selectText() },50)">
                            <i class="icon icon-search"></i>
                        </a>
                    </li>
                {/if}
            </ul>
            <ul id="modx-topnav">
                {eval var=$navb}
            </ul>
            <ul id="modx-user-menu">
                {* eval is used here to support nested variables *}
                {eval var=$userNav}
            </ul>
        </div>
    </div>
    {*<div id="modAB"></div>*}
    <div id="modx-leftbar"></div>
    <div id="modx-action-buttons-container"></div>
    <div id="modx-content">
        <div id="modx-panel-holder"></div>

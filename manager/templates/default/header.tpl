<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$_config.manager_direction}" lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}"{if $_config.manager_html5_cache EQ 1} manifest="{$_config.manager_url}cache.manifest.php"{/if}>
<head>
<title>{if $_pagetitle}{$_pagetitle} | {/if}{$_config.site_name}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />

{if $_config.manager_favicon_url}<link rel="shortcut icon" href="{$_config.manager_favicon_url}" />{/if}

<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/default/css/index.css" />

{if $_config.ext_debug}
<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base-debug.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all-debug.js" type="text/javascript"></script>
{else}
<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all.js" type="text/javascript"></script>
{/if}
<script src="{$_config.manager_url}assets/modext/core/modx.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.searchbar.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?ctx=mgr&topic=topmenu,file,resource,{$_lang_topics}&action={$smarty.get.a|strip_tags}" type="text/javascript"></script>
<script src="{$_config.connectors_url}modx.config.js.php?action={$smarty.get.a|strip_tags}{if $_ctx}&wctx={$_ctx}{/if}" type="text/javascript"></script>

{if $_config.compress_js && $_config.compress_js_groups}
<script src="{$_config.manager_url}min/index.php?g=coreJs1" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs2" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs3" type="text/javascript"></script>
{/if}

<script type="text/javascript">
    Ext.onReady(function() {
        new MODx.SearchBar;
    });
</script>


{$maincssjs}
{foreach from=$cssjs item=scr}
{$scr}
{/foreach}
</head>
<body id="modx-body-tag">

<div id="modx-browser"></div>
<div id="modx-container">
    <div id="modx-header">
        <div id="modx-navbar">
            <ul id="modx-user-menu">
                <li id="limenu-user" class="top">
                    <a href="?a=security/profile" title="{$_lang.profile_desc}"><span id="user-avatar">{$userImage}</span> <span id="user-username">{$username}</span></a>
                    <ul class="modx-subnav">
                        {$navbUser}
                    </ul>
                </li>
                <li id="limenu-admin" class="top">
                    <a href="?a=system/settings" title="{$_lang.system_settings_desc}"><i class="icon-gear icon-large"></i></a>
                    <ul class="modx-subnav">
                        {$navbAdmin}
                    </ul>
                </li>
                <li id="limenu-about" class="top">
                    <a href="?a=help" title="{$_lang.about_desc}"><i class="icon-question-sign icon-large"></i></a>
                </li>
            </ul>
            <ul id="modx-topnav">
                <li id="modx-home-dashboard">
                    <a href="?a=welcome" title="{$_lang.dashboard}">{$_lang.dashboard}</a>
                </li>
                <li id="modx-manager-search"></li>
                {$navb}
            </ul>
        </div>

        <div id="modAB"></div>
        <div id="modx-leftbar"></div>
        <div id="modx-content">
            <div id="modx-panel-holder"></div>

{if $_config.manager_html5_cache EQ 1}<!DOCTYPE HTML>{else}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">{/if}

<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}"{if $_config.manager_html5_cache EQ 1} manifest="{$_config.manager_url}cache.manifest.php"{/if}>
<head>
<title>{if $_pagetitle}{$_pagetitle} | {/if}{$_config.site_name}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />

{if $_config.manager_favicon_url}<link rel="shortcut icon" type="image/x-icon" href="{$_config.manager_favicon_url}" />{/if}

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
<script src="{$_config.connectors_url}lang.js.php?ctx=mgr&topic=topmenu,file,resource,{$_lang_topics}&action={$smarty.get.a|strip_tags}" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php?action={$smarty.get.a|strip_tags}{if $_ctx}&wctx={$_ctx}{/if}" type="text/javascript"></script>

{if $_config.compress_js && $_config.compress_js_groups}
<script src="{$_config.manager_url}min/index.php?g=coreJs1" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs2" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs3" type="text/javascript"></script>
{/if}

{$maincssjs}
{foreach from=$cssjs item=scr}
{$scr}
{/foreach}
</head>
<body id="modx-body-tag">

<div id="modx-browser"></div>
<div id="modx-container">
    <div id="modx-mainpanel">
        <div id="modx-header">
            <div id="modx-topbar">
                <div id="modx-logo"><a href="http://modx.com" onclick="window.open(this.href); return false;"><img src="templates/default/images/style/modx-logo-header.png" alt="" /></a></div>


                <div class="rightlogin">
                    {if $canChangeProfile}<a class="modx-user-profile" href="?a={$profileAction}">{$username}</a>{else}<span class="modx-user-profile">{$username}</span>{/if}
                    {if $canLogout}<a class="modx-logout" href="javascript:;" onclick="MODx.logout();">{$_lang.logout}</a>{/if}
                </div>
                <div id="modx-site-name">
                    {$_config.site_name}
                    <span class="modx-version">MODX Revolution {$_config.settings_version} ({$_config.settings_distro})</span>
                </div>
            </div>
            <div id="modx-navbar">
                <div id="modx-topnav-div">
                    <ul id="modx-topnav">
                        {$navb}
                        <li class="cls"></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div id="modAB"></div>
        <div id="modx-leftbar"></div>
        <div id="modx-content">
            <div id="modx-panel-holder"></div>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>MODx :: {$_config.site_name}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />

<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
{if $_config.compress_css}
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/modx-min.css" />
{else}
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/xtheme-modx.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/index.css" />
{/if}

<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/{if $_config.compress_js}build/core/modx-min{else}core/modx{/if}.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?ctx=mgr&topic=topmenu,file,resource,{$_lang_topics}&action={$smarty.get.a}" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php?action={$smarty.get.a}" type="text/javascript"></script>

{foreach from=$cssjs item=scr}
{$scr}
{/foreach}

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/ie.css" />
<![endif]-->
</head>
<body id="modx-body-tag">

<div id="modx-browser"></div>
<div id="modx-container">
    <div id="modx-topbar">
        <div id="modx-logo"><a href="http://modxcms.com" onclick="window.open(this.href); return false;"><img src="templates/{$_config.manager_theme}/images/style/modx_logo_header.png" alt="" /></a></div>
        <div id="modx-site-name">
            {$_config.site_name}
            <span class="modx-version">MODx Revolution {$_config.settings_version} rev{$revision}</span>
        </div>
    </div>
    <div id="modx-navbar">
        <div id="rightlogin">
        <span>
            <a class="modx-logout" href="javascript:;" onclick="MODx.logout();">{$_lang.logout}</a>
            <a id="modx-login-user" href="?a={$profileAction}">{$username}</a>
        </span>
        </div>
        {include file="navbar.tpl"}
    </div>

    <div id="modx-mainpanel">
        <div id="modAB"></div>
        <div id="modx-leftbar"></div>
        <div id="modx-content">
            <div id="modx-panel-holder"></div>
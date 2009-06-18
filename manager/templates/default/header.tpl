<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {if $_config.manager_direction EQ 'rtl'}dir="rtl"{/if} lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}">
<head>
<title>MODx :: {$_config.site_name}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />

<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext2/resources/css/ext-all.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext2/resources/css/xtheme-gray.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/index.css" />

<script src="{$_config.manager_url}assets/ext2/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext2/ext-all.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.js" type="text/javascript"></script>
<script src="assets/modext/util/eventfix.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?topic=topmenu,file,resource,{$_lang_topics}&action={$smarty.get.a}" type="text/javascript"></script>
<script src="{$_config.connectors_url}layout/modx.config.js.php?action={$smarty.get.a}" type="text/javascript"></script>

<script src="{$_config.manager_url}assets/modext/core/modx.localization.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/spotlight.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/utilities.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/switchbutton.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.form.handler.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.component.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/core/modx.actionbuttons.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.msg.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.panel.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.tabs.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.window.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.tree.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.combo.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.grid.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.grid.local.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.console.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/core/modx.portal.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/windows.js" type="text/javascript"></script>

<script src="{$_config.manager_url}assets/modext/widgets/resource/modx.tree.resource.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/element/modx.tree.element.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/widgets/system/modx.tree.directory.js" type="text/javascript"></script>

<script src="{$_config.manager_url}assets/modext/util/filetree/js/Ext.ux.form.BrowseButton.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/filetree/js/Ext.ux.FileUploader.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/modext/util/filetree/js/Ext.ux.UploadPanel.js" type="text/javascript"></script>
<link href="{$_config.manager_url}assets/modext/util/filetree/css/icons.css" rel="stylesheet" type="text/css" />
<link href="{$_config.manager_url}assets/modext/util/filetree/css/filetype.css" rel="stylesheet" type="text/css" />
<link href="{$_config.manager_url}assets/modext/util/filetree/css/filetree.css" rel="stylesheet" type="text/css" />

<script src="{$_config.manager_url}assets/modext/core/modx.layout.js" type="text/javascript"></script>

{foreach from=$cssjs item=scr}
{$scr}
{/foreach}

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="templates/{$_config.manager_theme}/css/ie.css" />
<![endif]-->
</head>
<body>

<div id="modx-browser"></div>
<div id="modx-container">
    <div id="modx-topbar">
        <div id="modx-logo"><a href="http://modxcms.com"><img src="templates/{$_config.manager_theme}/images/style/modx_logo_header.png" target="_blank" alt="" /></a></div>
        <div id="modx-site-name">
            {$_config.site_name}
            <span class="modx-version">MODx Revolution {$_config.settings_version}</span>
        </div>
    </div>
    <div id="modx-navbar">
        <div id="rightlogin">
        <span>
            <a class="modx-logout" href="javascript:;" onclick="MODx.logout();">{$_lang.logout}</a>
            {$logged_in_as}
        </span>
        </div>
        {include file="navbar.tpl"}
    </div>
    
    <div id="modx-mainpanel">
        <div id="modx-accordion" class="modx-accordion">
            <div class="tl"></div><div class="tr"></div>
            <div id="modx-accordion-content">                    
                <div id="modx_rt_div"><div id="modx_resource_tree"></div></div>
                <div id="modx_et_div"><div id="modx_element_tree"></div></div>
                <div id="modx_ft_div"><div id="modx_file_tree"></div></div>                
            </div>            
            <div class="bl"></div><div class="br"></div> 
        </div>
        
        <div id="modAB"></div>
        <div id="modx-content">
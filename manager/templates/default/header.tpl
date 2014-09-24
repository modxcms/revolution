<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$_config.manager_direction}" lang="{$_config.manager_lang_attribute}" xml:lang="{$_config.manager_lang_attribute}"{if $_config.manager_html5_cache EQ 1} manifest="{$_config.manager_url}cache.manifest.php"{/if}>
<head>
<title>{if $_pagetitle}{$_pagetitle} | {/if}{$_config.site_name}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$_config.modx_charset}" />

{if $_config.manager_favicon_url}<link rel="shortcut icon" href="{$_config.manager_favicon_url}" />{/if}

<link rel="stylesheet" type="text/css" href="{$_config.manager_url}assets/ext3/resources/css/ext-all-notheme-min.css" />
<link rel="stylesheet" type="text/css" href="{$_config.manager_url}templates/{$_config.manager_theme}/css/index.css" />

{if $_config.ext_debug}
<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base-debug.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all-debug.js" type="text/javascript"></script>
{else}
<script src="{$_config.manager_url}assets/ext3/adapter/ext/ext-base.js" type="text/javascript"></script>
<script src="{$_config.manager_url}assets/ext3/ext-all.js" type="text/javascript"></script>
{/if}
<script src="{$_config.manager_url}assets/modext/core/modx.js" type="text/javascript"></script>
<script src="{$_config.connectors_url}lang.js.php?ctx=mgr&topic=topmenu,file,resource,{$_lang_topics}&action={$smarty.get.a|htmlspecialchars}" type="text/javascript"></script>
<script src="{$_config.connectors_url}modx.config.js.php?action={$smarty.get.a|htmlspecialchars}{if $_ctx}&wctx={$_ctx}{/if}" type="text/javascript"></script>

{if $_config.compress_js && $_config.compress_js_groups}
<script src="{$_config.manager_url}min/index.php?g=coreJs1" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs2" type="text/javascript"></script>
<script src="{$_config.manager_url}min/index.php?g=coreJs3" type="text/javascript"></script>
{/if}

{$maincssjs}
{foreach from=$cssjs item=scr}
{$scr}
{/foreach}

{if $_search}
<script type="text/javascript">
    Ext.onReady(function() {
        new MODx.SearchBar;
    });
</script>
{/if}
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
                    <a href="?" title="{$_lang.dashboard}">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0" y="0" width="36" height="37.388" viewBox="1.682 50.718 36 37.388">
                            <g>
                                <linearGradient id="SVGID_1_" gradientUnits="userSpaceOnUse" x1="877.3604" y1="620.4822" x2="859.7464" y2="609.0126" gradientTransform="matrix(0.7643 0 0 0.7643 -635.8752 -413.4337)">
                                    <stop  offset="0" style="stop-color:#80C3E6"/>
                                    <stop  offset="1" style="stop-color:#3380C2"/>
                                </linearGradient>
                                <polygon fill="url(#SVGID_1_)" points="30.205,66.181 37.682,54.026 19.652,54.026 17.119,58.083"/>
                                <polygon opacity="0.15" points="17.119,58.316 18.446,56.265 30.205,66.336"/>
                                
                                <linearGradient id="SVGID_2_" gradientUnits="userSpaceOnUse" x1="880.0195" y1="647.1326" x2="867.0278" y2="638.5532" gradientTransform="matrix(0.7643 0 0 0.7643 -635.8752 -413.4337)">
                                    <stop  offset="0" style="stop-color:#F38649"/>
                                    <stop  offset="0.1849" style="stop-color:#F28147"/>
                                    <stop  offset="0.4091" style="stop-color:#EF7242"/>
                                    <stop  offset="0.6537" style="stop-color:#EA5A3A"/>
                                    <stop  offset="0.911" style="stop-color:#E4382E"/>
                                    <stop  offset="1" style="stop-color:#E12A29"/>
                                </linearGradient>
                                <polygon fill="url(#SVGID_2_)" points="33.853,88.105 33.853,70.075 30.13,67.602 21.861,80.567"/>
                                <polygon opacity="0.15" points="22.064,80.567 24.114,81.834 30.265,67.602"/>
                                
                                <linearGradient id="SVGID_3_" gradientUnits="userSpaceOnUse" x1="837.6904" y1="628.6257" x2="862.6507" y2="613.0289" gradientTransform="matrix(0.7643 0 0 0.7643 -635.8752 -413.4337)">
                                    <stop  offset="0" style="stop-color:#42AB4A"/>
                                    <stop  offset="1" style="stop-color:#ADD155"/>
                                </linearGradient>
                                <polygon fill="url(#SVGID_3_)" points="5.448,50.718 5.448,68.748 9.475,71.22 30.497,66.336"/>
                                
                                <linearGradient id="SVGID_4_" gradientUnits="userSpaceOnUse" x1="842.7178" y1="656.1873" x2="863.0244" y2="623.6898" gradientTransform="matrix(0.7643 0 0 0.7643 -635.8752 -413.4337)">
                                    <stop  offset="0" style="stop-color:#42AB4A"/>
                                    <stop  offset="1" style="stop-color:#ADD155"/>
                                </linearGradient>
                                <polygon fill="url(#SVGID_4_)" points="9.159,72.429 1.682,84.009 19.712,84.009 30.265,67.333"/>
                            </g>
                        </svg>
                    <span>{$_lang.dashboard}</span></a>
                </li>
                {if $_search}
                <li id="modx-manager-search"></li>
                {/if}
                {$navb}
            </ul>
        </div>
    </div>
        <div id="modAB"></div>
        <div id="modx-leftbar"></div>
        <div id="modx-content">
            <div id="modx-panel-holder"></div>

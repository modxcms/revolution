<!doctype html>
<html lang="en">

<head>
    <title>{$app_name} {$app_version} &raquo; {$_lang.install}</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" />
    <link href="assets/css/installer.css" type="text/css" rel="stylesheet" />

    {if $_lang.additional_css NEQ ''}
    <style type="text/css">
        {
            $_lang.additional_css
        }

    </style>
    {/if}

    <script type="text/javascript" src="assets/js/ext-core.js"></script>
    <script type="text/javascript" src="assets/js/modx.setup.js"></script>

</head>

<body>
    <!-- start header -->
    <header>
        <div class="wrapper">
            <div class="wrapper_logo">
                <a href="http://www.modx.com" title="Modx" class="logo" target="_blank">MODX</a>
            </div>
            <div class="wrapper_version">
                <strong>{$app_name}</strong>&nbsp;<em>{$_lang.version} {$app_version}</em>
            </div>
        </div>
    </header>
    <!-- end header -->

    <div id="content">
        <div class="content-inside">
            <div class="wrapper">
                <div class="content_header">
                    <div class="content_header_title">
                        MODX Installer
                    </div>
                </div>

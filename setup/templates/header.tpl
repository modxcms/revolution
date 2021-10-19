<!doctype html>
<html lang="en">

<head>
    <title>{$app_name} {$app_version} &raquo; {$_lang.install}</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <base href="{$base_url}">

    <link rel="shortcut icon" href="favicon.ico" />
    <link href="assets/css/installer.css" type="text/css" rel="stylesheet" />

    {if $_lang.additional_css NEQ ''}
        <style type="text/css"> {$_lang.additional_css} </style>
    {/if}

    <script src="assets/js/ext-core.js"></script>
    <script src="assets/js/modx.setup.js"></script>
    <script>
        window.onload = function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var action = url.searchParams.get('action');

            switch (action) {
                case 'welcome':
                    setCurrent(0);
                    break;
                case 'options':
                    setCurrent(1);
                    break;
                case 'database':
                    setCurrent(2);
                    break;
                case 'contexts':
                    setCurrent(3);
                    break;
                case 'summary':
                    setCurrent(4);
                    break;
                case 'install':
                    setCurrent(5);
                    break;
                case 'complete':
                    setCurrent(6);
                    break;
                default:
                    setCurrent(0);
            }

            function setCurrent(index) {
                for (let i = 0; i < index; i++) {
                    document.querySelectorAll('.modx-installer-steps li')[i].classList.add('active');
                }
                document.querySelectorAll('.modx-installer-steps li')[index].classList.add('current');
            }
        }
    </script>

</head>

<body>
    <!-- start header -->
    <header>
        <div class="wrapper">
            <div class="wrapper_logo">
                <a href="https://modx.com/" title="MODX" class="logo" target="_blank">MODX</a>
            </div>
            <div class="wrapper_version">
                {$app_name} {$app_version}
            </div>
            <div class="steps-outer">
                <ul class="modx-installer-steps">
                    <li><span class="icon"></span> <span class="title">{$_lang.step_welcome}</span></li>
                    <li><span class="icon"></span> <span class="title">{$_lang.step_options}</span></li>
                    <li><span class="icon"></span> <span class="title">{$_lang.step_connect}</span></li>
                    <li><span class="icon"></span> <span class="title">{$_lang.step_contexts}</span></li>
                    <li><span class="icon"></span> <span class="title">{$_lang.step_test}</span></li>
                    <li><span class="icon"></span> <span class="title">{$_lang.step_install}</span></li>
                    <li><span class="icon"></span> <span class="title">{$_lang.step_complete}</span></li>
                </ul>
            </div>
        </div>
    </header>
    <!-- end header -->

    <div id="content">
        <div class="content-inside">
            <div class="wrapper">
                <div class="content_header">
                    <div class="content_header_title">
                        {$_lang.modx_installer}
                    </div>
                </div>

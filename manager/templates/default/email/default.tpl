<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset={$_config.mail_charset}"/>
    <title>{$_config.site_name}</title>
    <style type="text/css">
        body { background: #f7f7f7;margin: 0;padding: 0;width: 100%;height: 100%;font-family: Arial, serif;font-size: 14px;color: #293034; }
        h1 { font-size: 26px;}
        h2 { font-size: 23px;}
        h3 { font-size: 20px;}
        h4 { font-size: 18px;}
        table { border-spacing: 0; width: 100%; }
        table td { margin: 0; }
        body > table { width: 600px; margin: auto; }
        a { color: #14c8a0; outline: none; text-decoration: none; }
        p { font-size: 16px; line-height: 22px; }
        .main-logo { padding: 35px 0; }
        .main-logo img { width: 217px; border: 0;}
        .content { height: 100px; vertical-align: top; background: #ffffff; border: 1px solid #e1ddcb; border-radius: 5px; box-shadow: #d7d7d7 0 2px 5px; padding: 30px; }
        .content .btn { color: #ffffff; text-decoration: none; border-radius: 3px; background-color: #14c8a0; border-top: 12px solid #14c8a0; border-bottom: 12px solid #14c8a0; border-right: 18px solid #14c8a0; border-left: 18px solid #14c8a0; display: inline-block; }
        .left { text-align: left; }
        .center { text-align: center; }
        .right { text-align: right; }
        .small { font-size: 12px; color: #999;}
        .footer td { padding: 35px 0; text-align: center; text-transform: uppercase; }
        .footer td a { color: #999999; }
    </style>
</head>
<body>
<table>
    <thead>
    <tr>
        <td class="main-logo">
            <a href="{$_config.site_url}" target="_blank">
                {if $_config.login_logo}
                    <img src="{$_config.login_logo}" alt="{$_config.site_name}"/>
                {else}
                    <img src="{$_config.url_scheme}{$_config.http_host}{$_config.manager_url}templates/default/images/modx-logo-color.svg" alt="{$_config.site_name}"/>
                {/if}
            </a>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td class="content">
            {block "content"}
                {$content}
            {/block}
        </td>
    </tr>
    <tr>
        <td>
            <table class="footer">
                <tr>
                    <td>
                        <a href="{$_config.site_url}" target="_blank">{$_config.site_name}</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>

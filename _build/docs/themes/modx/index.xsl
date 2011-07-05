<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output indent="yes" method="html"/>

    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
            <head>
                <title>MODX API Documentation</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                <link rel="stylesheet" href="{$root}css/black-tie/jquery-ui-1.8.2.custom.css" type="text/css"/>
                <link rel="stylesheet" href="{$root}css/theme.css" type="text/css"/>
                <script type="text/javascript" src="{$root}js/jquery-1.4.2.min.js"></script>
                <script type="text/javascript" src="{$root}js/jquery-ui-1.8.2.custom.min.js"></script>
                <script type="text/javascript" src="{$root}js/jquery.cookie.js"></script>
                <script type="text/javascript" src="{$root}js/jquery.treeview.js"></script>
                <script type="text/javascript" src="{$root}js/theme.js"></script>
            </head>
            <body class="chrome">
                <table id="page">
                    <tr>
                        <td colspan="2" id="db-header">
                            <h1>
                                <div class="docblox">
                                    <img src="{$root}images/apidocs-logo.png" alt="MODX"/>
                                </div>
                            </h1>
                            <div id="menubar">
                                <ul xmlns="" id="menu">
                                    <li>
                                        <a href="{$root}graph.html" target="content">Class diagram</a>
                                    </li>
                                    <li>
                                        <a href="{$root}markers.html" target="content">TODO / FIXME</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td id="sidebar">
                            <img xmlns="" src="{$root}images/search.gif" id="search_box_icon" border="0" alt="" />
                            <label>
                                <input xmlns="" id="search_box"/>
                                <img xmlns=""
                                     src="{$root}images/clear_left.png"
                                     id="search_box_clear"
                                     border="0"
                                     alt="Clear" />
                            </label>
                            <iframe name="nav" id="nav" src="{$root}nav.html" frameBorder="0"></iframe>
                        </td>
                        <td id="contents">
                            <iframe name="content" id="content" src="{$root}content.html" frameBorder="0"></iframe>
                        </td>
                    </tr>
                </table>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
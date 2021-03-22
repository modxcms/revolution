<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output indent="yes" method="html" />

  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
      <head>
        <title>
            <xsl:choose>
                <xsl:when test="$title != ''"><xsl:value-of select="$title" /></xsl:when>
                <xsl:otherwise>API Documentation</xsl:otherwise>
            </xsl:choose>
        </title>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <link rel="stylesheet" href="{$root}css/black-tie/jquery-ui-1.8.2.custom.css" type="text/css" />
        <link rel="stylesheet" href="{$root}css/theme.css" type="text/css" />
        <script src="{$root}js/jquery-1.4.2.min.js"></script>
        <script src="{$root}js/jquery-ui-1.8.2.custom.min.js"></script>
      </head>
      <body class="chrome">
        <table id="page">
          <tr>
            <td colspan="2" id="db-header">
              <h1>
                <xsl:if test="/project/@title != ''">
                  <div class="docblox"><img src="{$root}images/apidocs-logo.png" /></div>
                </xsl:if>
                <xsl:if test="/project/@title = ''">
                  <div class="docblox"><img src="{$root}images/apidocs-logo.png" /></div>
                </xsl:if>
              </h1>

              <div id="menubar">
                <xsl:call-template name="menubar" />
              </div>
            </td>
          </tr>
          <tr>
            <td id="sidebar">
              <xsl:call-template name="search" />
                <script>
                    $(function() {
                        $("#sidebar-content").resizable({
                            helper: "ui-resizable-helper",
                            handles: 'e'
                        });
                    });
                </script>
                <div id="sidebar-content">
                <iframe name="nav" id="nav" src="{$root}nav.html" frameBorder="0"></iframe>
              </div>
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

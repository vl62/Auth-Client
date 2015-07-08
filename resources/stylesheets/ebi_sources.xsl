<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="html" indent="yes"/>
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>
        <link rel="stylesheet"  href="http://www.ebi.ac.uk/inc/css/contents.css"     type="text/css" />
        <link rel="stylesheet"  href="http://www.ebi.ac.uk/inc/css/userstyles.css"   type="text/css" />
        <!-- script src="http://www.ebi.ac.uk/inc/js/contents.js" type="text/javascript" / -->
        <link rel="SHORTCUT ICON" href="http://www.ebi.ac.uk/bookmark.ico" />
        <style type="text/css">
          @media print { 
            body, .contents, .header, .contentsarea, .head { 
              position: relative;
            }
          }
        </style>
        <style type="text/css">tr{vertical-align:top}</style>
        <title>DAS Server: Sources List</title>
      </head>
      <body>
        <div class="headerdiv" id="headerdiv" style="position:absolute; z-index: 1;">
          <iframe src="http://www.ebi.ac.uk/inc/head.html" name="head" id="head" frameborder="0" marginwidth="0px" marginheight="0px" scrolling="no"  width="100%" style="position:absolute; z-index: 1; height: 57px;">Your browser does not support inline frames or is currently configured not to display inline frames. Content can be viewed at actual source page: http://www.ebi.ac.uk/inc/head.html</iframe>
        </div>
        <div class="contents" id="contents">
          <table class="contentspane" id="contentspane" summary="The main content pane of the page"   style="width: 100%">
            <tr>
              <td class="leftmargin"><img src="http://www.ebi.ac.uk/inc/images/spacer.gif" class="spacer" alt="spacer"  /></td>
              <td class="leftmenucell" id="leftmenucell">
                <div class="leftmenu" id="leftmenu"><img src="http://www.ebi.ac.uk/inc/images/spacer.gif" class="spacer" alt="spacer"  /></div>
              </td>
              <td class="contentsarea" id="contentsarea">
                <!-- start contents here -->
                <div class="breadcrumbs">
                  <a href="http://www.ebi.ac.uk/" class="firstbreadcrumb">EBI</a>
                  <a href="">DAS server</a>
                </div>
        <div>
          <h1>DAS Server: Source Information</h1>
        </div>
        <div id="mainbody">
          <p>Format:
            <input type="radio" name="format" onclick="document.getElementById('data').style.display='block';document.getElementById('xml').style.display='none';" value="Table" checked="checked"/>Table
            <input type="radio" name="format" onclick="document.getElementById('xml').style.display='block';document.getElementById('data').style.display='none';" value="XML"/>XML
          </p>
          <table class="contenttable_lmenu" id="data">
            <thead>
              <tr><th>URI</th><th>Title</th><th>Description</th><th>Contact</th><th>Coordinates</th><th>Capabilities</th><th>Created</th></tr>
            </thead>
            <tbody>
              <xsl:apply-templates select="SOURCES/SOURCE">
                <xsl:sort select="@title"/>
              </xsl:apply-templates>
            </tbody>
          </table>
          <div id="xml" style="font-family:courier;display:none;">
            <xsl:apply-templates select="*" mode="xml-main"/>
          </div>
        </div>
                <!-- end contents here -->
                <img src="http://www.ebi.ac.uk/inc/images/spacer.gif" class="spacer"  alt="spacer" />
              </td>
              <td class="rightmenucell" id="rightmenucell">
                <div class="rightmenu" id="rightmenu"><img src="http://www.ebi.ac.uk/inc/images/spacer.gif" class="spacer" alt="spacer" /></div>
              </td>
            </tr>
          </table>
          <table class="footerpane" id="footerpane" summary="The main footer pane of the page">
            <tr>
              <td colspan ="4" class="footerrow">
                <div class="footerdiv" id="footerdiv"  style="z-index:2;">
                  <iframe src="http://www.ebi.ac.uk/inc/foot.html" name="foot" frameborder="0" marginwidth="0px" marginheight="0px" scrolling="no"  height="22px" width="100%"  style="z-index:2;">Your browser does not support inline frames or is currently configured not to display inline frames. Content can be viewed at actual source page: http://www.ebi.ac.uk/inc/foot.html</iframe>
                </div>
              </td>
            </tr>
          </table>
          <script  src="http://www.ebi.ac.uk/inc/js/footer.js" type="text/javascript"></script>
        </div>
      </body>
    </html>
  </xsl:template>
  <xsl:template match="SOURCE">
    <xsl:for-each select="VERSION">
      <xsl:sort select="@uri"/>
      <tr>
        <td><xsl:value-of select="@uri"/></td>
        <td style="white-space:nowrap;"><xsl:value-of select="../@title"/></td>
        <td><xsl:value-of select="../@description"/> [<a><xsl:attribute name="href"><xsl:value-of select="../@doc_href"/></xsl:attribute>More info</a>]</td>
        <td><xsl:value-of select="../MAINTAINER/@email"/></td>
        <td style="white-space:nowrap;"><xsl:apply-templates select="COORDINATES"/></td>
        <td><xsl:apply-templates select="CAPABILITY"/></td>
        <td><xsl:value-of select="@created"/></td>
      </tr>
    </xsl:for-each>
  </xsl:template>
    <xsl:template match="COORDINATES">
    <xsl:value-of select="."/>
    <xsl:if test="position()!=last()"><br/></xsl:if>
  </xsl:template>
  <xsl:template match="CAPABILITY">
    <xsl:variable name="command" select="substring-after( @type,':')"/>
    <xsl:choose>
      <xsl:when test="not(@query_uri)">
        [<xsl:value-of select="@type"/>]
      </xsl:when>
      <xsl:when test="($command='dsn' or $command='stylesheet' or $command='sources')">
        [<a><xsl:attribute name="href"><xsl:value-of select="@query_uri"/></xsl:attribute><xsl:value-of select="@type"/></a>]
      </xsl:when>
      <xsl:when test="($command='entry_points')">
        [<a><xsl:attribute name="href"><xsl:value-of select="@query_uri"/>?rows=1-10</xsl:attribute><xsl:value-of select="@type"/></a>]
      </xsl:when>
      <xsl:when test="($command='alignment')">
        [<a><xsl:attribute name="href"><xsl:value-of select="@query_uri"/>?query=<xsl:value-of select="../COORDINATES[1]/@test_range"/>&amp;subjectcoordsys=<xsl:value-of select="../COORDINATES[2]"/></xsl:attribute><xsl:value-of select="@type"/></a>]
      </xsl:when>
      <xsl:when test="($command='volmap')">
        [<a><xsl:attribute name="href"><xsl:value-of select="@query_uri"/>?query=<xsl:value-of select="../COORDINATES/@test_range"/></xsl:attribute><xsl:value-of select="@type"/></a>]
      </xsl:when>
      <xsl:when test="($command='interaction')">
        [<a><xsl:attribute name="href"><xsl:value-of select="@query_uri"/>?interactor=<xsl:value-of select="../COORDINATES/@test_range"/></xsl:attribute><xsl:value-of select="@type"/></a>]
      </xsl:when>
      <xsl:otherwise>
        [<a><xsl:attribute name="href"><xsl:value-of select="@query_uri"/>?segment=<xsl:value-of select="../COORDINATES/@test_range"/></xsl:attribute><xsl:value-of select="@type"/></a>]
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  
  <xsl:template match="@*" mode="xml-att">
    <span style="color:purple"><xsl:text>&#160;</xsl:text><xsl:value-of select="name()"/>=&quot;</span><span style="color:red"><xsl:value-of select="."/></span><span style="color:purple">&quot;</span>
  </xsl:template>
  
  <xsl:template match="*" mode="xml-main">
    <xsl:choose>
      <xsl:when test="*">
        <span style="color:blue">&lt;<xsl:value-of select="name()"/></span><xsl:apply-templates select="@*" mode="xml-att"/><span style="color:blue">&gt;</span>
        <div style="margin-left: 1em"><xsl:apply-templates select="*" mode="xml-main"/></div>
        <span style="color:blue">&lt;/<xsl:value-of select="name()"/>&gt;</span><br/>
      </xsl:when>
      <xsl:when test="text()">
        <span style="color:blue">&lt;<xsl:value-of select="name()"/></span><xsl:apply-templates select="@*" mode="xml-att"/><span style="color:blue">&gt;</span><xsl:apply-templates select="text()" mode="xml-text"/><span style="color:blue">&lt;/<xsl:value-of select="name()"/>&gt;</span><br/>
      </xsl:when>
      <xsl:otherwise>
        <span style="color:blue">&lt;<xsl:value-of select="name()"/></span><xsl:apply-templates select="@*" mode="xml-att"/><span style="color:blue"> /&gt;</span><br/>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>
  
  <xsl:template match="text()" mode="xml-text">
    <div style="margin-left: 1em; color:black"><xsl:value-of select="."/></div>
  </xsl:template>
  
</xsl:stylesheet>

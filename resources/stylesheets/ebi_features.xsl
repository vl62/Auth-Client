<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
  <xsl:output method="html" indent="yes"/>
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml">
      <head>

        <title>DAS Server: Features for <xsl:value-of select="/DASGFF/GFF/@href"/></title>

	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

	<meta name="viewport" content="width=device-width" />
	<meta name="keywords" content="mutation, diagnostics, genetics" />
	<meta name="author" content="Owen Lancaster" />
	<meta name="description" content="Cafe Variome" />

	<link rel="stylesheet" type="text/css" href="http://127.0.0.1/cafevariome/index.php/css" />

	<link rel="stylesheet" href="http://127.0.0.1/cafevariome/resources/css/global.css" />
	<link rel="stylesheet" href="http://127.0.0.1/cafevariome/resources/css/prettify.css"/>
	<link rel="stylesheet" href="http://127.0.0.1/cafevariome/resources/css/jquery.ibutton.css"/>
	<link rel="stylesheet" href="http://127.0.0.1/cafevariome/resources/css/fileUploader.css"/>
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css"/>
	<link rel="stylesheet" href="http://127.0.0.1/cafevariome/resources/css/DT_bootstrap.css"/>

			</head>
			<body>
				<header id="header" class="navbar navbar-fixed-top">
					<div class="navbar-inner">
						<div class="container">
							<div class="navbar navbar-inverse navbar-fixed-top">
								<div class="navbar-inner">
									<div class="container">
										<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
											<span class="icon-bar"></span>
										</a>
										<a class="brand" href="#">
											<img src="http://127.0.0.1/cafevariome/resources/images/logos/cafevariome-logo-full.png" width="170" height="170" />
										</a>
										<p class="navbar-text pull-right">
											<a href="http://127.0.0.1/cafevariome/auth/login" class="navbar-link">Login</a> | 
											<a href="http://127.0.0.1/cafevariome/auth/signup" class="navbar-link">Register</a>
										</p>

										<ul class="nav">  
											<li >
												<a href="http://127.0.0.1/cafevariome/home">Home</a>
											</li>
											<li >
												<a href="http://127.0.0.1/cafevariome/share">Share</a>
											</li>
											<li >
												<a href="http://127.0.0.1/cafevariome/discover">Discover</a>
											</li>
											<li class="dropdown">  
												<a href="#" class="dropdown-toggle" data-toggle="dropdown">About
													<b class="caret"></b>
												</a>  
												<ul class="dropdown-menu">  
													<li>
														<a href="http://127.0.0.1/cafevariome/about/api">API</a>
													</li>
													<li>
														<a href="http://127.0.0.1/cafevariome/about/cafevariome">Cafe Variome</a>
													</li>
													<li>
														<a href="http://127.0.0.1/cafevariome/about/faq">FAQ</a>
													</li> 
													<li>
														<a href="http://127.0.0.1/cafevariome/about/gensearch">Gensearch</a>
													</li>
													<li>
														<a href="http://127.0.0.1/cafevariome/about/inabox">In-a-box</a>
													</li>
													<li>
														<a href="http://127.0.0.1/cafevariome/about/varioml">VarioML</a>
													</li>
													<li class="divider"></li>
													<li>
														<a href="http://127.0.0.1/cafevariome/about/contact">Contact</a>
													</li>
												</ul>  
											</li>  
										</ul>
									</div>
								</div>
							</div>    
						</div>
					</div>
				</header>
				<div class="headerdiv" id="headerdiv" style="position:absolute; z-index: 1;">
					<iframe src="http://www.ebi.ac.uk/inc/head.html" name="head" id="head" frameborder="0" marginwidth="0px" marginheight="0px" scrolling="no"  width="100%" style="position:absolute; z-index: 1; height: 57px;">Your browser does not support inline frames or is currently configured not to display inline frames. Content can be viewed at actual source page: http://www.ebi.ac.uk/inc/head.html</iframe>
				</div>
				<div class="contents" id="contents">
					<table class="contentspane" id="contentspane" summary="The main content pane of the page"   style="width: 100%">
						<tr>
							<td class="leftmargin">
								<img src="http://www.ebi.ac.uk/inc/images/spacer.gif" class="spacer" alt="spacer"  />
							</td>
							<td class="leftmenucell" id="leftmenucell">
								<div class="leftmenu" id="leftmenu">
									<img src="http://www.ebi.ac.uk/inc/images/spacer.gif" class="spacer" alt="spacer"  />
								</div>
							</td>
							<td class="contentsarea" id="contentsarea">
                <!-- start contents here -->
								<div class="breadcrumbs">
									<a href="http://www.ebi.ac.uk/" class="firstbreadcrumb">EBI</a>
									<a href="">DAS server</a>
									<a href="/sources">Sources</a>
									<a href="">human-microrna</a>
								</div>
								<div>
									<h1>DAS Server: Features for 
										<xsl:value-of select="/DASGFF/GFF/@href"/>
									</h1>
								</div>
								<div id="mainbody">
									<p>Format:
										<input type="radio" name="format" onclick="document.getElementById('table').style.display='block';document.getElementById('xml').style.display='none';" value="Table" checked="checked"/>Table
										<input type="radio" name="format" onclick="document.getElementById('xml').style.display='block';document.getElementById('table').style.display='none';" value="XML"/>XML
									</p>
									<div id="table" style="display:block;">
										<xsl:apply-templates select="/DASGFF/GFF/SEGMENT" mode="table"/>
									</div>
									<div id="xml" style="font-family:courier;display:none;">
										<xsl:apply-templates select="*" mode="xml-main"/>
									</div>
								</div>
                <!-- end contents here -->
								<img src="http://www.ebi.ac.uk/inc/images/spacer.gif" class="spacer"  alt="spacer" />
							</td>
							<td class="rightmenucell" id="rightmenucell">
								<div class="rightmenu" id="rightmenu">
									<img src="http://www.ebi.ac.uk/inc/images/spacer.gif" class="spacer" alt="spacer" />
								</div>
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
  <xsl:template match="SEGMENT" mode="table">
      <h3>
        Features for segment 
        <xsl:choose>
          <xsl:when test="@start and @end">
            <xsl:value-of select="@id"/>:<xsl:value-of select="@start"/>,<xsl:value-of select="@end"/>
          </xsl:when>
          <xsl:otherwise>
            <xsl:value-of select="@id"/>
          </xsl:otherwise>
        </xsl:choose>
      </h3>
    <table class="table table-striped table-bordered" id="example">
      <xsl:attribute name="id">data_<xsl:value-of select="@id"/></xsl:attribute>
      <thead>
        <tr>
          <th>Label</th>
          <th>Start</th>
          <th>End</th>
          <th>Orientation</th>
          <th>Type</th>
          <th>Method</th>
          <th>Notes</th>
          <th>Links</th>
          <th>Parts</th>
        </tr>
      </thead>
      <tbody>
    <xsl:for-each select="FEATURE">
      <xsl:sort select="@id"/>
      <tr>
        <td><xsl:choose><xsl:when test="@label != ''"><xsl:value-of select="@label"/></xsl:when><xsl:otherwise><xsl:value-of select="@id"/></xsl:otherwise></xsl:choose></td>
        <td><xsl:value-of select="START"/></td>
        <td><xsl:value-of select="END"/></td>
        <td><xsl:value-of select="ORIENTATION"/></td>
        <td><xsl:choose><xsl:when test="TYPE != ''"><xsl:value-of select="TYPE"/></xsl:when><xsl:otherwise><xsl:value-of select="TYPE/@id"/></xsl:otherwise></xsl:choose></td>
        <td><xsl:choose><xsl:when test="METHOD != ''"><xsl:value-of select="METHOD"/></xsl:when><xsl:otherwise><xsl:value-of select="METHOD/@id"/></xsl:otherwise></xsl:choose></td>
        <td><xsl:apply-templates select="NOTE"/></td>
        <td><xsl:if test="LINK"><xsl:apply-templates select="LINK"/></xsl:if></td>
        <td><xsl:apply-templates select="PART"/></td>
      </tr>
    </xsl:for-each>
    </tbody>
    </table>
  </xsl:template>
  <xsl:template match="PART">
    <xsl:variable name="part_id" select="@id" />
    <xsl:variable name="part_el" select="../../FEATURE[@id=$part_id]" />
    <xsl:choose><xsl:when test="$part_el/@label != ''"><xsl:value-of select="$part_el/@label"/></xsl:when><xsl:otherwise><xsl:value-of select="$part_el/@id"/></xsl:otherwise></xsl:choose>
    <xsl:if test="position()!=last()"><br/></xsl:if>
  </xsl:template>
  <xsl:template match="NOTE">
    <xsl:value-of select="."/>
    <xsl:if test="position()!=last()"><br/></xsl:if>
  </xsl:template>
  <xsl:template match="LINK">
    [<a><xsl:attribute name="href"><xsl:value-of select="@href"/></xsl:attribute>
      <xsl:choose>
        <xsl:when test="text()"><xsl:value-of select="text()" /></xsl:when>
        <xsl:otherwise><xsl:value-of select="@href" /></xsl:otherwise>
      </xsl:choose>
    </a>]
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

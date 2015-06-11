<div class="container">
	<div class="row-fluid">
		<div class="span3">
			<div style="position: fixed">
				<div class="well">
					<ul class="nav nav-list bs-docs-sidenav">
						<li><a href="#introduction"><i class="icon-chevron-right"></i>Introduction</a></li>
						<li><a href="#purpose"><i class="icon-chevron-right"></i>Purpose</a></li>
						<li><a href="#audience"><i class="icon-chevron-right"></i>Audience</a></li>
						<li><a href="#useful"><i class="icon-chevron-right"></i>Useful tools</a></li>
						<li><a href="#retrieving"><i class="icon-chevron-right"></i>Retrieving</a></li>
						<li><a href="#submitting"><i class="icon-chevron-right"></i>Submitting</a></li>
						<li><a href="#varioml"><i class="icon-chevron-right"></i>VarioML</a></li>
						<li><a href="#updatecontent"><i class="icon-chevron-right"></i>Updating Content</a></li>
						<li><a href="#categories"><i class="icon-chevron-right"></i>Categories</a></li>
						<li><a href="#modifycategories"><i class="icon-chevron-right"></i>Modify Categories</a></li>
					</ul>
				</div>
			</div>
		</div>
        <div class="span9 pagination-centered">
			<h2>Cafe Variome API</h2>
			<hr>
			<section id="introduction">
				<h3>Introduction</h3>
				<p>Cafe Variome is built upon standard Web protocols and components. At the heart of Cafe Variome lies an Atom Store database (using <a href="http://atomserver.codehaus.org/">AtomServer</a>), which is used to store sequence variant entries as they are submitted to the Cafe.</p>
				<p>AtomServer is a generic, off-the-shelf open-source data store modelled after GData. Cafe Variome is based on AtomServer. See <a href="http://atomserver.codehaus.org/" title="http://atomserver.codehaus.org/">http://atomserver.codehaus.org/</a> for the AtomServer project page and also this article <a href="https://www.infoq.com/articles/atomserver" title="https://www.infoq.com/articles/atomserver">https://www.infoq.com/articles/atomserver</a> for a very good introduction to the AtomServer concept. The full API documentation for AtomServer can be found at<a href="http://atomserver.codehaus.org/docs/protocol_reference.html"> http://atomserver.codehaus.org/docs/protocol_reference.html</a>. See here for an introduction to feeds and g2p databases<a href="https://www.gen2phen.org/post/atom-web-feeds-atompub-protocol-and-g2p-databases">: https://www.gen2phen.org/post/atom-web-feeds-atompub-protocol-and-g2p-databases.</a></p>
				<hr>
			</section>
			<section id="purpose">
				<h3>Purpose</h3>
				<p>The purpose of this document is to explain how to use Cafe Variome for retrieving, submitting and updating variants via the RESTful AtomServer API.</p>
				<hr>
			</section>
			<section id="audience">
				<h3>Audience</h3>
				<p>This document and the API is meant for use by a technical audience. You will need a working knowledge of programming, the http protocol and the basics of the Atom specification. You will require a developers account with us in order to submit data to Cafe Variome via the API, please contact us at admin@cafevariome.org to setup your account.</p>
				<hr>
			</section>
			<section id="useful">
				<h3>Useful Tools for Testing the API</h3>
				<p><a href="http://ditchnet.org/httpclient/">http://ditchnet.org/httpclient/</a> - http Client (Mac)<br /> <a href="https://addons.mozilla.org/en-US/firefox/addon/poster/">https://addons.mozilla.org/en-US/firefox/addon/poster/</a> - Poster addon for Firefox</p>
				<hr>
			</section>
			<section id="retrieving">
				<h3>Retrieving variants</h3>
				<p>Requesting a variant feed:</p>
				<pre><code class="mceVisualAid mceVisualGuides">GET http://www.cafevariome.org/atomserver/v1/cafevariome/variants/-/(sharing_policy)openAccess</code></pre>
				<p>The server responds with a 200 status and the content of the feed:</p>
				<pre><samp class="mceVisualAid mceVisualGuides">http/1.1 200 OK<br /> Date: Wed, 23 Feb 2011 15:25:11 GMT<br /> Server: Jetty(6.1.9)<br /> Content-Type: application/atom+xml; charset=UTF-8<br /> Etag: https://www.cafevariome.org<br /> &lt;feed xmlns="http://www.w3.org/2005/Atom" xmlns:as="http://atomserver.org/namespaces/1.0/"&gt;<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lt;entry&gt;<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lt;content&gt;<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ...<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lt;/content&gt;<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lt;/entry&gt;<br />&lt;/feed&gt;<br /> </samp></pre>
				<p>It is also possible to perform more advanced queries via the API (e.g. all variants in BRCA1), see the <a href="#Categories">categories section</a> for more information.
				<hr>
			</section>
			<section id="submitting">
				<h3>Submitting Variants</h3>
				Submitting Variants</h3>
				<p>In order to submit variants to Cafe Variome via the API, basic http authentication is required. SSL encryption is used to ensure data is sent securely. Please contact admin@cafevariome.org to obtain a username and password.</p>
				<p>To submit variants (either one at a time or in batch), you should PUT your content (using <a href="#VarioML" > VarioML format</a>) to the following URI:</p>
				<pre><code class="mceVisualAid mceVisualGuides">PUT http://www.cafevariome.org/atomserver/v1/cafevariome/variants/$batch</code></pre>
				<p>Example content body:</p>
				<pre><samp class="mceVisualAid mceVisualGuides">&lt;feed&nbsp;xmlns=&quot;http://www.w3.org/2005/Atom&quot;&nbsp;xmlns:asbatch=&quot;http://atomserver.org/namespaces/1.0/batch&quot;&gt;<br/>	&lt;entry&nbsp;xmlns=&quot;http://www.w3.org/2005/Atom&quot;&gt;<br/>		&lt;content&nbsp;type=&quot;application/atom+xml&quot;&gt;<br/>			&lt;cafe_variome&nbsp;xmlns=&quot;http://www.varioml.org/xml/1.0&quot;&nbsp;type=&quot;testing&quot;&gt;<br/>				&lt;source&nbsp;id=&quot;example_lab&quot;&gt;<br/>					&lt;name&gt;Example Diagnostic Laboratory&lt;/name&gt;<br/>					&lt;contact&gt;<br/>						&lt;name&gt;Fred Bloggs&lt;/name&gt;
						&lt;email&gt;fred@gmail.com&lt;/email&gt;<br/>					&lt;/contact&gt;<br/>				&lt;/source&gt;<br/>				&lt;variant type=&quot;DNA&quot;&nbsp;&gt;<br/>					&lt;gene&nbsp;source=&quot;HGNC&quot;&nbsp;accession=&quot;FLNC&quot;/&gt;<br/>					&lt;ref_seq source=&quot;refseq&quot; accession=&quot;NM_001458.4&quot;/&gt;<br />					&lt;name&nbsp;scheme=&quot;HGVS&quot;&gt;c.4404C&gt;G&lt;/name&gt;<br/>					&lt;panel id=&quot;p9999&quot;&gt;<br/>						&lt;individual&gt;<br/>							&lt;gender&nbsp;code=&quot;2&quot;/&gt;<br/>						&lt;/individual&gt;<br/>						&lt;phenotype&nbsp;term=&quot;myopathy myofibrillar filamin C-related&quot; source=&quot;OMIM&quot; accession=&quot;609524&quot;/&gt;<br/>					&lt;/panel&gt;<br/>					&lt;seq_changes&gt;<br/>						&lt;variant type=&quot;AA&quot;&gt;<br/>							&lt;ref_seq source=&quot;refseq&quot; accession=&quot;NP_001449.3&quot;/&gt;<br/>							&lt;name scheme=&quot;HGVS&quot;&gt;p.(Asp1468Glu)&lt;/name&gt;<br/>						&lt;/variant&gt;<br/>					&lt;/seq_changes&gt;<br/>					&lt;location&gt;<br/>						&lt;ref_seq&nbsp;source=&quot;refseq&quot;&nbsp;accession=&quot;NC_000018.9&quot;/&gt;<br/>						&lt;chr&gt;chr7&lt;/chr&gt;<br/>						&lt;start&gt;128275077&lt;/start&gt;<br/>						&lt;end&gt;128275127&lt;/end&gt;<br/>					&lt;/location&gt;<br/>					&lt;sharing_policy&nbsp;type=&quot;openAccess&quot;&nbsp;/&gt;<br/>				&lt;/variant&gt;<br/>				&lt;comment&gt;<br/>					&lt;text&gt;Variant&nbsp;submitted&nbsp;from&nbsp;Gensearch&nbsp;tool&nbsp;during&nbsp;Cafe&nbsp;Variome&nbsp;pilot&lt;/text&gt;<br/>				&lt;/comment&gt;<br/>			&lt;/cafe_variome&gt;<br/>		&lt;/content&gt;<br/>		&lt;asbatch:operation&nbsp;type=&quot;insert&quot;/&gt;<br/>		&lt;link&nbsp;href=&quot;/atomserver/v1/cafevariome/variants/&quot;&nbsp;rel=&quot;edit&quot;/&gt;<br/>	&lt;/entry&gt;<br/>&lt;/feed&gt;<br/></samp></pre>

				<p>The server responds with a 200 status if the submit was successful</p>
				<p>If you wish to submit multiple variants in batch then the above body should include multiple <code>&lt;entry&gt;</code> blocks - i.e. one entry block for each variant to be submitted. The batching capability of AtomServer can also be applied to updating entries as well as submitting. See the following for more information on this: <a href="http://atomserver.codehaus.org/docs/batching.html" target="_blank">http://atomserver.codehaus.org/docs/batching.html</a>.</p>
				<p><span style="font-weight: bold;">Variants should be submitted in VarioML format</span> and embedded between the <code>&lt;content&gt;</code> elements in your submission body (as seen in the example above). The following section gives a description of VarioML and how it should be used in the context of Cafe Variome.</p>
				<hr>
			</section>
			<section id="varioml">
				<h3>VarioML Exchange Format</h3>
				<p>Cafe Variome has implemented the VarioML exchange format for representing variants. This exchange format has been developed within the GEN2PHEN consortium to address the need for a common, standardized format for the exchange of variants between diverse sources and databases. For information regarding the VarioML specification see <a href="http://www.varioml.org" target="_blank">http://www.varioml.org</a>
				<hr>
			</section>
			<section id="updatecontent">
				<h3>Updating Variant Content</h3>
				<p>To update the content of an existing entry, use PUT, with the entry's revision/edit URI (as provided by the server in the <code>&lt;link rel="edit"&gt;</code> element of the entry you wish to update).</p>
				<p>The revision identifier can be found in the following element for the entry you wish to modify the content:</p>
				<pre><code class="mceVisualAid mceVisualGuides" id="line1">&lt;link href="/atomserver/v1/cafevariome/variants25.xml/1" rel="edit" /&gt;</code></pre>
				<p>N.B. revisions exist to ensure that multiple clients don't inadvertently overwrite one another's changes. In AtomServer, this is achieved by the version ID that is present for each entry's <span style="font-family: monospace;">edit</span> URI (see example above). Note that the <span style="font-family: monospace;">edit</span> URI <span style="font-style: italic;"> always points to the version you should PUT to</span> (i.e. to one version greater than the current version), while the <span style="font-family: monospace;">self </span> link <span style="font-style: italic;"> always points to an "unversioned" URI</span>. Each update to the entry changes the entry's revision number, and its corresponding<span style="font-family: monospace;">edit</span> URI, thus guaranteeing that <span style="font-style: italic;">subsequent updates based on the original version fail</span>. If the server detects a version conflict on PUT or DELETE, the server responds with <span style="font-family: monospace;">409 Conflict.</span> The body of the response contains the correct <span style="font-family: monospace;">edit</span> URI of the entry. The client is advised to resolve the conflict and resubmit the request, using the <span style="font-family: monospace;">edit </span>URI from the 409 response.</p>
				<p>Note that there is effectively no difference between an Update and an Insert, <span style="font-style: italic;">except</span> in Updates, you <span style="font-weight: bold;">must</span> provide the revision identifier. Although, for situations where there is a "single writer" and optimistic concurrency is not required, you can use the wildcard revision identifier ( <span style="font-family: monospace;">/*</span> ) to override optimistic concurrency.<br /></p><p>For example to update an entry do a PUT:
				<pre><samp class="mceVisualAid mceVisualGuides">PUT http://www.cafevariome.org/atomserver/v1/cafevariome/variants25.xml/1</pre></samp>
				<p>With the following example body:</p>
				<pre><samp class="mceVisualAid mceVisualGuides">
&lt;entry&nbsp;xmlns=&quot;http://www.w3.org/2005/Atom&quot;&gt;<br/>	&lt;content&nbsp;type=&quot;application/atom+xml&quot;&gt;<br/>		&lt;cafe_variome&nbsp;xmlns=&quot;http://www.varioml.org/xml/1.0&quot;&nbsp;type=&quot;testing&quot;&gt;<br/>			&lt;source&nbsp;id=&quot;anonymous&quot;&gt;<br/>				&lt;name&gt;anonymous&lt;/name&gt;<br/>				&lt;contact&gt;<br/>					&lt;email&gt;examplelab@example.com&lt;/email&gt;<br/>				&lt;/contact&gt;<br/>			&lt;/source&gt;<br/>			&lt;variant&nbsp;type=&quot;DNA&quot;&gt;<br/>				&lt;gene&nbsp;source=&quot;HGNC&quot;&nbsp;accession=&quot;FLNC&quot;/&gt;<br/>				&lt;ref_seq source=&quot;refseq&quot; accession=&quot;NM_001458.4&quot;/&gt;<br />				&lt;name&nbsp;scheme=&quot;HGVS&quot;&gt;c.4404T&gt;C&lt;/name&gt;<br/>				&lt;panel&gt;<br/>&nbsp;					&lt;phenotype&nbsp;term=&quot;myopathy myofibrillar filamin C-related&quot;/&gt;<br/>				&lt;/panel&gt;<br/>				&lt;location&gt;<br/>					&lt;ref_seq&nbsp;source=&quot;refseq&quot;&nbsp;accession=&quot;NC_000018.9&quot;/&gt;<br/>					&lt;chr&gt;chr7&lt;/chr&gt;<br/>					&lt;start&gt;128275077&lt;/start&gt;<br/>					&lt;end&gt;128275127&lt;/end&gt;<br/>				&lt;/location&gt;	<br/>				&lt;sharing_policy&nbsp;policy=&quot;openAccess&quot;&nbsp;/&gt;<br/>			&lt;/variant&gt;<br/>			&lt;comment&gt;<br/>				&lt;text&gt;Variant&nbsp;submitted&nbsp;from&nbsp;Gensearch&nbsp;tool&nbsp;during&nbsp;Cafe&nbsp;Variome&nbsp;pilot&lt;/text&gt;<br/>			&lt;/comment&gt;<br/>		&lt;/cafe_variome&gt;<br/>	&lt;/content&gt;<br/>&lt;/entry&gt;

</samp></pre>
				<p>A successful update will return a 200 code.</p>
				<hr>
			</section>
			<section id="categories">
				<h3>Categories</h3>
				<p>Most data requires categorization to make it more manageable, especially as the data grows in size. Categorization is the characterization of data into collections based on some common attribute. The Atom specification provides a built-in mechanism for categorization. Cafe Variome makes use of the category functionality of the Atom specification and the following section explains exactly how categories can be useful in the Cafe Variome context:</p>
				<p id="rfc. .7.p.1">Atom provides the concept of category documents, which contain lists of categories described using the     "atom:category" element from the Atom Syndication Format <a href="#RFC4287" title="The Atom Syndication Format">[RFC4287]</a>.</p>

				<p>The<code> &lt;category&gt;</code> has two attributes <code>term</code> and <code>scheme</code></p>
				<p><code style="font-weight: bold;">scheme</code> identifies the categorization scheme via a <abbr title="Universal Resource Identifier">URI</abbr> for example gene, or phenotype</p>
				<p><code style="font-weight: bold;">term</code><span style="font-weight: bold;"> </span>identifies the category that is associated with the scheme (e.g. for scheme=gene -&gt; term=BRCA1 or for scheme=phenotype -&gt; term=cancer)</p>
				<p>For example if you wish to obtain a feed that consists of all public entries that are in the BRCA1 gene the following category query can be performed:</p>
				<pre><code class="mceVisualAid mceVisualGuides">GET http://www.cafevariome.org/atomserver/v1/cafevariome/variants/-/AND/(sharing_policy)openAccess/(gene_symbol)BRCA1</code></pre>
				<p>Further details of how category queries in AtomServer work can be found here <a href="http://atomserver.codehaus.org/docs/categories.html">http://atomserver.codehaus.org/docs/categories.html</a></p>
				<p>AtomServer additionally provides the ability to automatically assign Atom categories to entries as they are submitted to Cafe Variome. This is called "<span style="font-style: italic;">Auto tagging</span>". Since Cafe Variome uses a standarized format for representing variants (<a href="http://www.varioml.org" target="_blank">http://www.varioml.org</a>) we have configured AtomServer so that categories are created for key element that are useful to categorize (details to follow). See here <a href="http://atomserver.codehaus.org/docs/autotagger.html">http://atomserver.codehaus.org/docs/autotagger.html</a> for further information.</p>
				<hr>
			</section>
			<section id="modifycategories">
				<h3>Creating or Modifying Categories for a Specific Entry</h3>
				<p>To create or modify the categories which apply to a given entry, send a <span style="font-family: monospace;">PUT</span> request, and supply a standard Atom Categories document within the <span style="font-family: monospace;">&lt;content&gt;</span> element. When performing an update you <span style="font-weight: bold;">must</span> provide the revision identifier in the update URI (see above on how to obtain this URI).</p>
				<p>Once you have the edit URI then an example of how you would modify the categories for the entry is as follows:</p>
				<pre><span style="font-family: monospace;">PUT http://www.cafevariome.org/atomserver/v1/cafevariome/variants25.xml/1</span></pre>
				<p>Example of the body of this PUT:</p>
				<pre><samp class="mceVisualAid mceVisualGuides">&lt;entry&nbsp;xmlns=&quot;http://www.w3.org/2005/Atom&quot;&gt;<br/>	&lt;id&gt;cr25&lt;/id&gt;<br/>	&lt;content&nbsp;type=&quot;application/xml&quot;&nbsp;&gt;<br/>		&lt;categories&nbsp;xmlns=&quot;http://www.w3.org/2007/app&quot;&nbsp;xmlns:atom=&quot;http://www.w3.org/2005/Atom&quot;&gt;<br/>			&lt;category&nbsp;xmlns=&quot;http://www.w3.org/2005/Atom&quot;&nbsp;scheme=&quot;sharing_policy&quot;&nbsp;term=&quot;openAccess&quot;&nbsp;&gt;<br/>			&lt;category&nbsp;xmlns=&quot;http://www.w3.org/2005/Atom&quot;&nbsp;scheme=&quot;DOI&quot;&nbsp;term=&quot;10.551725&quot;&nbsp;/&gt;<br/>		&lt;/categories&gt;<br/>	&lt;/content&gt;<br/>&lt;/entry&gt;<br/></samp></pre>
				<br />
				<p>N.B. You need to supply all the previous categories as well as the new/updated categories in the body above otherwise the old categories will be overwritten. In other words: AtomServer receives a list of categories to apply to a given entry, it will first <span style="font-style: italic;">delete all existing categories</span>, before inserting the current list of categories. Thus, the user <span style="font-weight: bold;">must</span> make certain that the list of categories they are submitting is correct and preserve whatever necessary categories they received from a previous GET or PUT.</p>
				<hr>
			</section>
        </div><!--/span-->
	</div><!--/row-->

	<hr>

</div><!--/.fluid-container-->
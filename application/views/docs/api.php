<div class="container">
	<div class="row-fluid">
		<div class="span8 offset2">
			<!--<div style="position: fixed">-->
				<div class="well">
					<div class="pagination-centered"><h3>Cafe Variome API Documentation</h3><hr></div>
					<ul class="nav nav-list bs-docs-sidenav">
<!--						<li><a href="#introduction"><i class="icon-chevron-right"></i>Introduction</a></li>
						<li><a href="#audience"><i class="icon-chevron-right"></i>Audience</a></li>
						<li><a href="#useful"><i class="icon-chevron-right"></i>Useful tools</a></li>-->
						<li><h4>Data Discovery & Querying API Calls:</h4></li>
						<ul class="nav nav-list bs-docs-sidenav">
							<li><a href="#variant_count_section"><i class="icon-chevron-right"></i>Discover Variants</a></li>
							<li><a href="#variants_section"><i class="icon-chevron-right"></i>Retrieve Variants</a></li>
							<li><a href="#variant_section"><i class="icon-chevron-right"></i>Retrieve Individual Variant</a></li>
						</ul>
						<li>&nbsp;</li>
						<li><h4>Data Submit, Update and Delete API Calls:</h4></li>
						<ul class="nav nav-list bs-docs-sidenav">
							<li><a href="#variants_submit"><i class="icon-chevron-right"></i>Submit/Update Variants</a></li>
							<li><a href="#variants_delete"><i class="icon-chevron-right"></i>Delete Variants</a></li>
						</ul>
						<li>&nbsp;</li>
						<li><h4>Information API Calls:</h4></li>
						<ul class="nav nav-list bs-docs-sidenav">
							<li><a href="#sources_section"><i class="icon-chevron-right"></i>Current Sources</a></li>
							<li><a href="#search_fields_section"><i class="icon-chevron-right"></i>Current Searchable Fields</a></li>
							<li><a href="#search_results_fields_section"><i class="icon-chevron-right"></i>Current Result Display Fields</a></li>
							<li><a href="#individual_record_fields_section"><i class="icon-chevron-right"></i>Current Individual Record Display Fields</a></li>
						</ul>
					</ul>
				</div>
			<!--</div>-->
		</div>
	</div>
<!--	<div class="row">
        <div class="span12 pagination-centered">
			<h2>Cafe Variome API</h2>
			<hr>
			<section id="introduction">
				<h3>Introduction</h3>
				<p>Cafe Variome is built upon standard Web protocols and components. The purpose of this document is to explain how to use Cafe Variome for retrieving, submitting and updating variants via the RESTful API.</p>
				<hr>
			</section>
			<section id="audience">
				<h3>Audience</h3>
				<p>This document and the API is meant for use by a technical audience. You will need a working knowledge of programming and the HHTP protocol.</p>
				<hr>
			</section>
			<section id="useful">
				<h3>Useful Tools for Testing the API</h3>
				<p><a href="http://www.getpostman.com/">http://www.getpostman.com/</a> - POSTMAN for Chrome<br /><a href="http://ditchnet.org/httpclient/">http://ditchnet.org/httpclient/</a> - http Client (Mac)<br /> <a href="https://addons.mozilla.org/en-US/firefox/addon/poster/">https://addons.mozilla.org/en-US/firefox/addon/poster/</a> - Poster addon for Firefox</p>
				<hr>
			</section>
		</div>
	</div>-->
	<br />
	<hr>
	<h2>Data Discovery & Querying API Calls</h2>
	<section id="variant_count_section">
		<div class="row">
			<div class="span12 collapse-group">
				<h3>Discover Variants</h3>
				<p>This is the initial data discover step. The full content of the data is searched to determine whether or not a record (or records) of interest exist in the specified source. The searchable fields are controlled by the data owner from within the Cafe Variome administrator interface, see <a href="#search_fields_section">here</a> for the API call to retrieve current searchable fields.</p>
				<p>A count of hits in each sources is returned, grouped by the sharing policy (openAccess, restrictedAccess, linkedAccess), see <a href="<?php echo base_url('about/cafevariome'); ?>">here</a> for a description of sharing policies.</p>
				<div class="span10 offset1"><pre><button class="btn btn-success btn-small" data-toggle="collapse" data-target="#variantcount">GET</button><code> <?php echo base_url('discover/variantcount/{search_term}/{source_name}/{format}'); ?></code></pre></div>
				<div class="span12 collapse" id="variantcount">
					<div class="well">
						<p>HTTP GET request, URL should be constructed as above with the parameters described below:</p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Parameter</th>
									<th>Value</th>
									<th>Type</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>search_term</td>
									<td>
										<input name="search_term" id="search_term" value="BRCA1" />
									</td>
									<td>Required</td>
									<td>Search query term (uses <a href="http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/query-dsl-query-string-query.html" target="_blank">ElasticSearch query dsl syntax</a>), the current searchable fields can be retrieved with the <a href="#search_fields_section">following call</a>.</td>
								</tr>
								<tr>
									<td>source_name</td>
									<td>
										<select id="source_name">
											<option value="all">All</option>
										<?php foreach ( $sources as $source_name => $source_description ): ?>
											<option value="<?php echo $source_name; ?>"><?php echo $source_description; ?></option>
										<?php endforeach; ?>
										</select>
										<!--<input name="source_name" id="source_name" value="" placeholder="required"/>-->
									</td>
									<td>Required (use "all" to search all available sources)</td>
									<td>Name of source (for obtaining a list of sources use the <a href="#sources_section" >current sources</a> API call</a>)</td>
								</tr>
								<tr>
									<td>format</td>
									<td>
										<select name="format" id="format">
											<option value="json">json</option>
											<option value="tab">tab</option>
											<option value="html">html</option>
										</select>
									</td>
									<td>Optional (defaults to html if not specified)</td>
									<td>Output format of counts, either: json, tab or html</td>
								</tr>
							</tbody>
						</table>
						<br />
						<div class="pagination-centered"><button type="submit" id="variant_count_button" class="btn btn-primary">Try it!</button></div>
						<div id="variant_count_call"></div>
						<div id="variant_count_result"></div>
						<br /><br />
					</div>
<!--					<p>Language samples:</p>
					<select>
						<option>PHP</option>
						<option>C++</option>
						<option>Python</option>
					</select>
					<button class="btn btn-primary btn-small">Get Code Sample</button>
					<hr>-->
				</div>


			</div>
		</div>
	</section>

	<section id="variants_section">
		<div class="row">
			<div class="span12 collapse-group">
				<h3>Retrieve Variants</h3>
				<p>The hit data (or direct queries) can be subsequently accessed in line with one of the three sharing policies (see above). A list of variants is returned in the specified format.</p>
				<div class="span10 offset1"><pre><button class="btn btn-success btn-small" data-toggle="collapse" data-target="#variants">GET</button><code> <?php echo base_url('discover/variants/{search_term}/{source_name}/{sharing_policy}/{format}'); ?></code></pre></div>
				<div class="span12 collapse" id="variants">
					<div class="well">
						<p>HTTP GET request to the base URL of installation. The fields returned for each hit are specified by the data owner from within the Cafe Variome administrator interface (and can be set independently for each sharing policy). Use <a href="#search_results_fields_section">the following API call</a> if you wish to get current display fields for the search result. The URL should be constructed as above with the parameters outlined below:</p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Parameter</th>
									<th>Value</th>
									<th>Type</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>search_term</td>
									<td>
										<input name="variants_search_term" id="variants_search_term" value="BRCA1" />
									</td>
									<td>Required</td>
									<td>Search query term (uses <a href="http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/query-dsl-query-string-query.html" target="_blank">ElasticSearch query dsl syntax</a>), the current searchable fields can be retrieved with the <a href="#search_fields_section">following call</a>.</td>
								</tr>
								<tr>
									<td>source_name</td>
									<td>
										<select id="variants_source_name">
											<!--<option value="all">All</option>-->
										<?php foreach ( $sources as $source_name => $source_description ): ?>
											<option value="<?php echo $source_name; ?>"><?php echo $source_description; ?></option>
										<?php endforeach; ?>
										</select>
										<!--<input name="source_name" id="source_name" value="" placeholder="required"/>-->
									</td>
									<td>Required</td>
									<td>Name of source (for obtaining a list of sources use the <a href="#sources_section" >current sources</a> API call</a>)</td>
								</tr>
								<tr>
									<td>sharing_policy</td>
									<td>
										<select name="variants_sharing_policy" id="variants_sharing_policy">
											<option value="openAccess">openAccess</option>
											<option value="restrictedAccess">restrictedAccess</option>
											<option value="linkedAccess">linkedAccess</option>
										</select>
									</td>
									<td>Required</td>
									<td>openAccess, restrictedAccess or linkedAccess</td>
								</tr>
								<tr>
									<td>format</td>
									<td>
										<select name="variants_format" id="variants_format">
											<option value="json">json</option>
											<option value="tab">tab</option>
											<option value="html">html</option>
											<option value="rss">rss</option>
											<option value="gff">gff</option>
											<option value="bed">bed</option>
											<option value="lovd">lovd</option>
											<option value="varioml">varioml</option>
										</select>
									</td>
									<td>Optional (defaults to html if not specified)</td>
									<td>Output format: json, tab, html, rss, gff, bed, lovd, varioml</td>
								</tr>
								<tr>
									<td>username</td>
									<td>
										<input name="variants_username" id="variants_username" />
									</td>
									<td>Required if restrictedAccess</td>
									<td>Username (as email) for basic HTTP authentication (uses credentials from main site user account)</td>
								</tr>
								<tr>
									<td>password</td>
									<td>
										<input type="password" name="variants_password" id="variants_password" />
									</td>
									<td>Required if restrictedAccess</td>
									<td>Password for basic HTTP authentication (uses credentials from main site user account)</td>
								</tr>

							</tbody>
						</table>
						<br />
						<div class="pagination-centered"><button type="submit" id="variants_button" class="btn btn-primary">Try it!</button></div>
						<div id="variants_call"></div>
						<div id="variants_result"></div>
						<br /><br /><br />
					</div>
<!--					<p>Language samples:</p>
					<select>
						<option>PHP</option>
						<option>C++</option>
						<option>Python</option>
					</select>
					<button class="btn btn-primary btn-small">Get Code Sample</button>
					<hr>-->
				</div>
			</div>
		</div>
	</section>
	
	<section id="variant_section">
		<div class="row">
			<div class="span12 collapse-group">
				<h3>Retrieve Individual Variant</h3>
				<p>The individual variant record is returned in the specified format.</p>
				<div class="span10 offset1"><pre><button class="btn btn-success btn-small" data-toggle="collapse" data-target="#variant">GET</button><code> <?php echo base_url('discover/variant/{id}/{format}'); ?></code></pre></div>
				<div class="span12 collapse" id="variant">
					<div class="well">
						<p>HTTP GET request to the base URL of installation. The fields returned for each variant are specified by the data owner from within the Cafe Variome administrator interface. Use <a href="#individual_record_fields_section">the following API call</a> if you wish to get current display fields for an individual record. URL should be constructed as above with the parameters outlined below:</p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Parameter</th>
									<th>Value</th>
									<th>Type</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>id</td>
									<td>
										<input name="variant_id" id="variant_id" value="1" />
									</td>
									<td>Required</td>
									<td>Existing ID of the variant (do not include prefix)</td>
								</tr>
								<tr>
									<td>format</td>
									<td>
										<select name="variant_format" id="variant_format">
											<option value="json">json</option>
											<!--<option value="tab">tab</option>-->
											<option value="html">html</option>
											<!--<option value="rss">rss</option>-->
											<!--<option value="gff">gff</option>-->
											<!--<option value="bed">bed</option>-->
											<!--<option value="lovd">lovd</option>-->
											<!--<option value="varioml">varioml</option>-->
										</select>
									</td>
									<td>Optional (defaults to html if not specified)</td>
									<td>Output format: json, html</td>
								</tr>
								<tr>
									<td>username</td>
									<td>
										<input name="variant_username" id="variant_username" />
									</td>
									<td>Required if variant is restrictedAccess</td>
									<td>Username (as email) for basic HTTP authentication (uses credentials from main site user account)</td>
								</tr>
								<tr>
									<td>password</td>
									<td>
										<input type="password" name="variant_password" id="variant_password" />
									</td>
									<td>Required if variant is restrictedAccess</td>
									<td>Password for basic HTTP authentication (uses credentials from main site user account)</td>
								</tr>

							</tbody>
						</table>
						<br />
						<div class="pagination-centered"><button type="submit" id="variant_button" class="btn btn-primary">Try it!</button></div>
						<div id="variant_call"></div>
						<div id="variant_result"></div>
						<br /><br /><br />
					</div>
<!--					<p>Language samples:</p>
					<select>
						<option>PHP</option>
						<option>C++</option>
						<option>Python</option>
					</select>
					<button class="btn btn-primary btn-small">Get Code Sample</button>
					<hr>-->
				</div>
			</div>
		</div>
	</section>
	<hr>
	<h2>Data Submit, Update and Delete API Calls</h2>
	<section id="variants_submit">
		<div class="row">
			<div class="span12 collapse-group">
				<h3>Submit/Update Variants</h3>
				<p>Submits or updates variants in a source.</p>
				<div class="span10 offset1"><pre><button class="btn btn-success btn-small" data-toggle="collapse" data-target="#submit">POST</button> Base URL followed by key/value pairs to specify parameters:<br /><code><br /><strong>Base URL to submit API: </strong><?php echo base_url('api/variants/submit/'); ?> <br /><strong>Key/value URL parameters:</strong><br />/source/{source_name}<br />/sharing_policy/{openAccess|restrictedAccess|linkedAccess}<br />/overwrite/{on|off}<br />/mutalyzer/{on|off}<br /><br />E.g. <?php echo base_url('api/variants/submit/'); ?>/source/test/sharing_policy/restrictedAccess/overwrite/off/mutalyzer/off</code></pre></div>
				<div class="span12 collapse" id="submit">
					<div class="well">
						<p>HTTP POST request. URL should be constructed as above with the parameters outlined below with the variants to insert/update as the body of the request (N.B. format will be auto-detected, support formats are Alamut XML and <a href="http://www.varioml.org" target="_blank">VarioML</a>). N.B. you may need to specify the content type in your request e.g. "application/xml". A valid username (as email) and password with administrator privileges from the Cafe Variome instance must be supplied using basic HTTP authentication.</p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Parameter</th>
									<th>Value</th>
									<th>Type</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>source</td>
									<td>
										<!--<input name="submit_source" id="submit_source" value="temp" /><br />-->
										<select name="submit_source_name" id="submit_source_name" onchange="if(this.value=='!') custom_select_val(this, 'Enter name of source (source will be created if it does not already exist)')">
										<?php foreach ( $sources as $source_name => $source_description ): ?>
											<option value="<?php echo $source_name; ?>"><?php echo $source_description; ?></option>
										<?php endforeach; ?>
											<option value="!">[specify...]</option>
										</select>
									</td>
									<td>Required (defaults to temp if not specified)</td>
									<td>Source name, if the source doesn't exist it will be automatically created (for obtaining a list of sources use the <a href="#sources_section" >current sources</a> API call</a>)</td>
								</tr>
								<tr>
									<td>sharing_policy</td>
									<td>
										<select name="submit_sharing_policy" id="submit_sharing_policy">
											<option value="openAccess">openAccess</option>
											<option value="restrictedAccess">restrictedAccess</option>
											<option value="linkedAccess">linkedAccess</option>
										</select>
									</td>
									<td>Required (defaults to openAccess if not specified)</td>
									<td>openAccess, restrictedAccess or linkedAccess</td>
								</tr>
								<tr>
									<td>overwrite</td>
									<td>
										<select name="submit_overwrite" id="submit_overwrite">
											<option value="off">off</option>
											<option value="on">on</option>
										</select>
									</td>
									<td>Required (defaults to off if not specified)</td>
									<td>Existing variants can be updated instead of inserting a new entry. This is done by matching the supplied local variant ID with any existing entries, if a match is found the details for this record will be updated. I.e. if set to off then a completely new entry will be inserted.</td>
								</tr>
								<tr>
									<td>mutalyzer</td>
									<td>
										<select name="submit_mutalyzer" id="submit_mutalyzer">
											<option value="off">off</option>
											<option value="on">on</option>
										</select>
									</td>
									<td>Required (defaults to off if not specified)</td>
									<td>Variant nomenclature can be checked with the Mutalyzer webservice (N.B. may be slow for large numbers of variants).</td>
								</tr>
								<tr>
									<td>username</td>
									<td>
										<input name="submit_username" id="submit_username" />
									</td>
									<td>Required</td>
									<td>Username (as email) for basic HTTP authentication (uses credentials from main site user account). The account must be a member of the administrator group.</td>
								</tr>
								<tr>
									<td>password</td>
									<td>
										<input type="password" name="submit_password" id="submit_password" />
									</td>
									<td>Required</td>
									<td>Password for basic HTTP authentication (uses credentials from main site user account)</td>
								</tr>
							</tbody>
						</table>
						<br />
						<div class="pagination-centered">
							<h4>Request Body:</h4>
							<p>The format type of the body will be auto-detected, currently supported formats are Alamut XML and <a href="http://www.varioml.org" target="_blank">VarioML</a></p>
						<textarea id="submit_body" name="submit_body" rows="10" cols="50" style="width:100%; font-family: monospace;">
<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<Mutations>
    <Mutation id="{92e51122-a27c-43f5-ad2c-bca6e5c7ec83}" version="1.0" organism="Hsapiens" refAssembly="LRG_1" chr="17" geneSym="COL1A1">
        <Variant type="Deletion" from="14303" to="14775">
            <gNomen val="g.14303_14775del"/>
            <Nomenclature refSeq="NM_000088.3">
                <cNomen val="c.404C&gt;G"/>
                <rNomen val=""/>
                <pNomen val="p.?"/>
            </Nomenclature>
        </Variant>
        <Classification val="Simple" index="2"/>
        <Pathogenic val="unknown"/>
        <Note val=""/>
        <Occurrences/>
    </Mutation>
    <Mutation id="{82462641-ae27-4267-b177-69e039e7ffb8}" version="1.0" organism="Hsapiens" refAssembly="LRG_1" chr="17" geneSym="COL1A1">
        <Variant type="Substitution" pos="11833" baseFrom="C" baseTo="T">
            <gNomen val="g.11833C&gt;T"/>
            <Nomenclature refSeq="NM_000088.3">
                <cNomen val="c.1375C&gt;T"/>
                <rNomen val=""/>
                <pNomen val="p.Pro459Ser"/>
            </Nomenclature>
        </Variant>
        <Classification val="CMGS_VGKL_5" index="3"/>
        <Pathogenic val="unknown"/>
        <Note val=""/>
        <Occurrences/>
    </Mutation>
</Mutations>
						</textarea>
						</div>
						<br />
						<div class="pagination-centered"><button type="submit" id="submit_button" class="btn btn-primary">Try it!</button></div>
						<div id="submit_call"></div>
						<div id="submit_result"></div>
						<br /><br /><br />
					</div>
<!--					<p>Language samples:</p>
					<select>
						<option>PHP</option>
						<option>C++</option>
						<option>Python</option>
					</select>
					<button class="btn btn-primary btn-small">Get Code Sample</button>
					<hr>-->
				</div>
			</div>
		</div>
	</section>
	<section id="variants_delete">
		<div class="row">
			<div class="span12 collapse-group">
				<h3>Delete Variants</h3>
				<p>Deletes a list of variants.</p>
				<div class="span10 offset1"><pre><button class="btn btn-success btn-small" data-toggle="collapse" data-target="#delete">POST</button><code> <?php echo base_url('api/variants/delete'); ?></code></pre></div>
				<div class="span12 collapse" id="delete">
					<div class="well">
						<p>HTTP POST request (POST is used instead of DELETE since some servers do not support DELETE requests). A JSON list of variant ids should be supplied as the body of the request (see below for an example), n.b. "application/json" should be specified as the content type. A valid username (as email) and password with administrator privileges from the Cafe Variome instance must be supplied using basic HTTP authentication. It is also possible to specify a single ID in the URL parameter with using id/{id}, however, the body of the request must be empty.</p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Parameter</th>
									<th>Value</th>
									<th>Type</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
<!--								<tr>
									<td>variant_id</td>
									<td>
										<input name="delete_variant_id" id="delete_variant_id" value="{92e51122-a27c-43f5-ad2c-bca6e5c7ec83}"/>
									</td>
									<td>Required</td>
									<td>ID of variant.</td>
								</tr>-->
								<tr>
									<td>username</td>
									<td>
										<input name="delete_username" id="delete_username" />
									</td>
									<td>Required</td>
									<td>Username (as email) for basic HTTP authentication (uses credentials from main site user account). The account must be a member of the administrator group.</td>
								</tr>
								<tr>
									<td>password</td>
									<td>
										<input type="password" name="delete_password" id="delete_password" />
									</td>
									<td>Required</td>
									<td>Password for basic HTTP authentication (uses credentials from main site user account)</td>
								</tr>
							</tbody>
						</table>
						<br />
						<div class="pagination-centered">
							<h4>Request Body:</h4>
							<p>JSON list of variant IDs</p>
							<textarea id="delete_body" name="delete_body" rows="6" cols="50" style="width:80%; font-family: monospace; text-align:left;">
[
	"{92e51122-a27c-43f5-ad2c-bca6e5c7ec83}",
	"{82462641-ae27-4267-b177-69e039e7ffb8}"
]
						</textarea>
						</div>
						<br />
						<div class="pagination-centered"><button type="submit" id="delete_button" class="btn btn-primary">Try it!</button></div>
						<div id="delete_call"></div>
						<div id="delete_result"></div>
						<br /><br /><br />
					</div>
<!--					<p>Language samples:</p>
					<select>
						<option>PHP</option>
						<option>C++</option>
						<option>Python</option>
					</select>
					<button class="btn btn-primary btn-small">Get Code Sample</button>
					<hr>-->
				</div>
			</div>
		</div>
	</section>
	<hr>
	<h2>Information API Calls</h2>
	<section id="sources_section">
		<div class="row">
			<div class="span12 collapse-group">
				<h3>Current Sources</h3>
				<p>Retrieve a list of the current sources in the installation:</p>
				<div class="span10 offset1"><pre><button class="btn btn-success btn-small" data-toggle="collapse" data-target="#sources">GET</button><code> <?php echo base_url('discover/sources/{format}'); ?></code></pre></div>
				<div class="span12 collapse" id="sources">
					<div class="well">
						<p>HTTP GET request, returns JSON of the current sources (for each source the short name and the description are given).</p>

						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Parameter</th>
									<th>Value</th>
									<th>Type</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>format</td>
									<td>
										<select name="sources_format" id="sources_format">
											<option value="json" selected>json</option>
											<option value="tab">tab</option>
											<option value="html">html</option>
										</select>
									</td>
									<td>Optional (defaults to html if not specified)</td>
									<td>Output format of sources, either: json, tab or html</td>
								</tr>
							</tbody>
						</table>
						<br />
						<div class="pagination-centered"><button type="submit" id="sources_button" class="btn btn-primary">Try it!</button></div>
						<div id="sources_result"></div>
						<br />
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="search_fields_section">
		<div class="row">
			<div class="span12 collapse-group">
				<h3>Searchable Fields</h3>
				<p>Retrieve a list of the current fields that will be used in searches:</p>
				<div class="span10 offset1"><pre><button class="btn btn-success btn-small" data-toggle="collapse" data-target="#search_fields">GET</button><code> <?php echo base_url('discover/search_fields'); ?></code></pre></div>
				<div class="span12 collapse" id="search_fields">
					<div class="well">
						<p>HTTP GET request, returns JSON of the current searchable fields. If all fields are searchable then "all" will be returned.</p>
						<br />
						<div class="pagination-centered"><button type="submit" id="search_fields_button" class="btn btn-primary">Try it!</button></div>
						<div id="search_fields_result"></div>
						<br />
					</div>
				</div>
			</div>
		</div>
	</section>

	<section id="search_results_fields_section">
		<div class="row">
			<div class="span12 collapse-group">
				<h3>Results Fields</h3>
				<p>Retrieve a list of the current fields that are displayed when returning a list of hits for a query. These fields can be modified from within the Cafe Variome administrator interface.</p>
				<div class="span10 offset1"><pre><button class="btn btn-success btn-small" data-toggle="collapse" data-target="#search_results_fields">GET</button><code> <?php echo base_url('discover/variants/{search_term}/{source_name}/{sharing_policy}/{format}'); ?></code></pre></div>
				<div class="span12 collapse" id="search_results_fields">
					<div class="well">
						<p>HTTP GET request to the base URL of installation. The fields returned for each hit are specified by the data owner from within the Cafe Variome administrator interface (and can be set independently for each sharing policy). The URL should be constructed as above with the parameters outlined below:</p>
						<table class="table table-striped table-bordered">
							<thead>
								<tr>
									<th>Parameter</th>
									<th>Value</th>
									<th>Type</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>sharing_policy</td>
									<td>
										<select name="search_results_fields_sharing_policy" id="search_results_fields_sharing_policy">
											<option value="openAccess">openAccess</option>
											<option value="restrictedAccess">restrictedAccess</option>
											<option value="linkedAccess">linkedAccess</option>
										</select>
									</td>
									<td>Required</td>
									<td>openAccess, restrictedAccess or linkedAccess</td>
								</tr>


							</tbody>
						</table>
						<br />
						<div class="pagination-centered"><button type="submit" id="search_results_fields_button" class="btn btn-primary">Try it!</button></div>
						<div id="search_results_fields_call"></div>
						<div id="search_results_fields_result"></div>
						<br /><br /><br />
					</div>
<!--					<p>Language samples:</p>
					<select>
						<option>PHP</option>
						<option>C++</option>
						<option>Python</option>
					</select>
					<button class="btn btn-primary btn-small">Get Code Sample</button>
					<hr>-->
				</div>
			</div>
		</div>
	</section>
	
	<section id="individual_record_fields_section">
		<div class="row">
			<div class="span12 collapse-group">
				<h3>Individual Record Display Fields</h3>
				<p>Retrieve a list of the current fields that are displayed when viewing or retrieving an individual variant record. These fields can be modified from within the Cafe Variome administrator interface.</p>
				<div class="span10 offset1"><pre><button class="btn btn-success btn-small" data-toggle="collapse" data-target="#individual_record_fields">GET</button><code> <?php echo base_url('discover/individual_record_fields'); ?></code></pre></div>
				<div class="span12 collapse" id="individual_record_fields">
					<div class="well">
						<p>HTTP GET request, returns JSON of the current fields that will be displayed per individual record. JSON array consists of the database field name as the key (no whitespaces) and a human readable name as the value.</p>
						<br />
						<div class="pagination-centered"><button type="submit" id="individual_record_fields_button" class="btn btn-primary">Try it!</button></div>
						<div id="individual_record_fields_result"></div>
						<br />
					</div>
				</div>
			</div>
		</div>
	</section>

<!--			<section id="retrieving">
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
        </div>/span
	</div>/row-->

	<hr>

</div><!--/.fluid-container-->
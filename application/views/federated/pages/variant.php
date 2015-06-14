<div class="container">
	<div class="row-fluid">
		<div class="span12 pagination-centered" id="table_container">
			<div class="well">
				<h3>Record <?php echo $this->config->item('cvid_prefix') . $variant['cafevariome_id']; ?></h3>
				
				<table class="table table-striped table-bordered table-hover" >
					<?php $location_flag = 0; ?>
					<?php foreach ( $individual_record_display_fields as $individual_record ): ?>
					<?php if ( $individual_record['name'] == "cafevariome_id" ): ?>
					<tr>
						<th style="width: 22em;"><?php echo $individual_record['visible_name']; ?></th>
						<td colspan="2"><?php echo $this->config->item('cvid_prefix') . $variant['cafevariome_id']; ?></td>
					</tr>
					<?php elseif ( $individual_record['name'] == "source_url" ): ?>
					<tr>
						<th align="center" class="title"><?php echo $individual_record['visible_name']; ?></th>
						<td colspan="2"><?php if ( $variant['source_url'] ) { ?> <a href="<?php echo $variant['source_url']; ?>" target="_blank"><?php echo $variant['source_url']; ?></a><?php } else { echo "-"; }?></td>
					</tr>
					
					<?php elseif ( $individual_record['name'] == "gene" ): ?>
					<tr>
						<th align="center" class="title"><?php echo $individual_record['visible_name']; ?></th>
						<td colspan="2"><a href="http://www.genecards.org/cgi-bin/carddisp.pl?gene=<?php echo $variant['gene'];?>" title="External link to GeneCards" target="_blank" ><?php echo $variant['gene'];?></a></td>
					</tr>
					<?php elseif ( $individual_record['name'] == "LRG" ): ?>
					<tr>
						<th align="center" class="title"><?php echo $individual_record['visible_name']; ?></th>
						<td colspan="2"><?php if ( isset($variant['LRG']) && $variant['LRG'] ) { ?><a href="ftp://ftp.ebi.ac.uk/pub/databases/lrgex/<?php echo $variant['LRG'];?>.xml" title="External link to LRG" target="_blank" ><?php echo $variant['LRG'];?></a><?php } ?></td>
					</tr>
					<?php elseif ( $individual_record['name'] == "dbsnp_id" ): ?>
					<tr>
						<th align="center" class="title"><?php echo $individual_record['visible_name']; ?></th>
						<td colspan="2"><a href="http://www.ncbi.nlm.nih.gov/snp/?term=<?php echo $variant['dbsnp_id'];?>" title="External link to dbSNP" target="_blank" ><?php echo $variant['dbsnp_id'];?></a></td>
					</tr>
					
					<?php elseif ( $individual_record['name'] == "ref" ): ?>
					<tr>
						<th align="center" class="title"><?php echo $individual_record['visible_name']; ?></th>
						<td colspan="2">
						<?php if ( $variant['hgvs'] ): ?>
							<?php if ( $variant['source'] == "uniprot" ): ?>
								<a href="http://www.uniprot.org/uniprot/<?php echo $variant['ref']; ?>" title="External link to reference sequence" target="_blank"><?php echo $variant['ref']; ?></a>
							<?php else: ?>
								<a href="http://www.ncbi.nlm.nih.gov/entrez/query.fcgi?db=Nucleotide&cmd=Search&doptcmdl=GenBank&term=<?php echo $variant['ref']; ?>" title="External link to reference sequence" target="_blank"><?php echo $variant['ref']; ?></a>
							<?php endif; ?>
						<?php else: ?>
							-
						<?php endif; ?>
						</td>
					</tr>	
					<?php elseif ( $individual_record['name'] == "hgvs" ): ?>
					<tr>
						<th align="center" class="title"><?php echo $individual_record['visible_name']; ?></th>
						<td colspan="2">
						<?php if ( $variant['hgvs'] ): ?>
							<?php echo $variant['hgvs']; ?>
							<?php echo nbs(2); ?>
							<a onclick='httpGetFocusOn("<?php echo $variant['ref'] . ":" . $variant['hgvs']; ?>");' rel="popover" data-content="Click to view this variant in Alamut (Alamut must be installed locally and be running in order to use this functionality)." data-original-title="View in Alamut"> <?php echo img(base_url('resources/images/cafevariome/alamut_logo.png'));?>
						<?php else: ?>
							<?php echo "-"; ?>
						<?php endif; ?>
						</td>
					</tr>
					<?php elseif ( $individual_record['name'] == "phenotype" ): ?>
					<tr>
						<th align="center" class="title"><?php echo $individual_record['visible_name']; ?></th>
						
						<?php 
							if (sizeof($phenotypes) > 0){
                                                            $cell1 = array();
                                                            $cell2 = array();
                                                            foreach ($phenotypes as $value){
								list($firstval, $secondval) = explode("|", $value);
                                                                array_push($cell1, $firstval);
                                                                array_push($cell2, $secondval);
                                                            }
                                                            echo "<td style=\"text-align: right;\">";
                                                            foreach ($cell1 as $key => $value){
                                                                echo $value."<br/>";
                                                            }
                                                            echo "</td><td style=\"text-align: left;\">";
                                                            foreach ($cell2 as $key => $value){
                                                                echo $value."<br/>";
                                                            }
                                                            echo "</td>";
                                                       }
							
                                                       else {
								echo "-"; 
							}
						?>
											</tr>
					<?php elseif ( $individual_record['name'] == "mutalyzer_check" ): ?>
					<tr>
						<th align="center" class="title"><?php echo $individual_record['visible_name']; ?></th>
						<td colspan="2">
						<?php if ( $variant['mutalyzer_check'] == "1" ): ?>
							Mutalyzer check passed, HGVS description is valid
						<?php else: ?>
							Mutalyzer check failed, HGVS description is not valid
						<?php endif; ?>
						<?php echo nbs(1); ?>
						<?php $escaped_hgvs = urlencode($variant['ref'] . ":" . $variant['hgvs']); ?>
                                                <a href="https://mutalyzer.nl/check?name=<?php echo $escaped_hgvs; ?>" target="_blank" rel="popover" data-content="Click validate this variant HGVS description at the Mutalyzer website" data-original-title="Mutalyzer Check" ><i class="icon-share"></i></a>
                                                </td>
					</tr>
					<?php elseif ( $individual_record['name'] == "location_ref" || $individual_record['name'] == "start" || $individual_record['name'] == "end"): ?>
					<?php if ( ! $location_flag ): ?>
					<?php $location_flag = 1; ?>
					<tr>
						<th align="center" class="title">Genomic location</th>
						<td colspan="2">
						<?php
						if ( $variant['location_ref'] ) {
							echo $variant['location_ref'] . ":" . $variant['start'] . "-" . $variant['end'];
							$ucsc_link = "http://genome.ucsc.edu/cgi-bin/hgTracks?org=human&db=" . $variant['build'] . "&position=" . $variant['location_ref'] . "%3A" . $variant['start'] . "-" . $variant['end'];
							?>
							<br><a href="<?php echo $ucsc_link; ?>" title="External link to UCSC genome browser for variant region" target="_blank">View in UCSC Genome Browser</a>
							<?php
						}
						else {
							echo "-";
						}
						?>
						</td>
					</tr>
					<?php endif; ?>
					<?php else: ?>
					<tr>
						<th align="center" class="title"><?php echo $individual_record['visible_name']; ?></th>
						<td colspan="2"><?php if ( $variant[$individual_record['name']] ) { echo $variant[$individual_record['name']]; } else { echo "-"; } ?></td>
					</tr>					
					<?php endif; ?>
					<?php endforeach; ?>
				</table>
				<br />
				<a class="btn btn-small btn-primary" href="mailto:<?php echo $source_email; ?>?subject=Record <?php echo $this->config->item('cvid_prefix') . $variant['cafevariome_id']; ?>"><i class="icon-envelope icon-white"></i> Email source owner for further information about this record</a>
			</div>
		</div>
	</div>
</div><!--/.container-->

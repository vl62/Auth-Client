<?php echo $xml_init; ?>
<?php echo $stylesheet_init; ?>
<SOURCES>
	<SOURCE uri="<?php echo $source;?>" title="<?php echo $source; ?>" doc_href="<?php echo base_url() . "discover/source/" . $source; ?>" description="<?php echo $source; ?>">
	    <MAINTAINER email="admin@cafevariome.org" />
	    <VERSION uri="<?php echo base_url() . "das/" . $source;?>" created="<?php echo date('m/d/Y h:i:s a', time()); ?>">
			<COORDINATES uri="<?php echo base_url() . "das/" . $source;?>" taxid="9606" source="Chromosome" authority="GRCh" test_range="1:69269,169269" version="37">GRCh_37,Chromosome,Homo sapiens</COORDINATES>
			<CAPABILITY type="das1:features" query_uri="<?php echo base_url() . "das/" . $source . "/features";?>"/>
			<CAPABILITY type="das1:stylesheet" query_uri="<?php echo base_url() . "resources/stylesheets/stylesheet.xsl"; ?>"/>
		</VERSION>
	</SOURCE>
</SOURCES>
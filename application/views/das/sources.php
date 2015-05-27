<?php echo $xml_init; ?>
<?php echo $stylesheet_init; ?>
<SOURCES>
<?php foreach ($sources->result() as $source): ?>
	<SOURCE uri="<?php echo $source->name;?>" title="<?php echo $source->name; ?>" doc_href="<?php echo base_url() . "discover/source/" . $source->name; ?>" description="<?php echo $source->description; ?>">
	    <MAINTAINER email="admin@cafevariome.org" />
	    <VERSION uri="<?php echo $source->name;?>" created="<?php echo date('m/d/Y h:i:s a', time()); ?>">
			<COORDINATES uri="http://www.dasregistry.org/dasregistry/coordsys/CS_DS311" taxid="9606" source="Chromosome" authority="GRCh" test_range="1:69269,169269" version="37">GRCh_37,Chromosome,Homo sapiens</COORDINATES>
<!--			<CAPABILITY type="das1:sources" query_uri="<?php echo base_url() . "das/" . $source->name; ?>"/>
			<CAPABILITY type="das1:types" query_uri="<?php echo base_url() . "das/sources/" . $source->name . "/types";?>"/>-->
			<CAPABILITY type="das1:features" query_uri="<?php echo base_url() . "das/" . $source->name . "/features";?>"/>
			<CAPABILITY type="das1:stylesheet" query_uri="<?php echo base_url() . "resources/stylesheets/stylesheet.xsl"; ?>"/>
		</VERSION>
	</SOURCE>
<?php endforeach; ?>
</SOURCES>
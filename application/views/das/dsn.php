<?php echo $xml_init; ?>
<?php echo $stylesheet_init; ?>
<!DOCTYPE DASDSN SYSTEM 'http://www.biodas.org/dtd/dasdsn.dtd' >
<DASDSN>
<?php foreach ($sources->result() as $source): ?>
	<DSN>
		<SOURCE id="<?php echo $source->name;?>" version="1.0"><?php echo $source->name; ?></SOURCE>
		<DESCRIPTION><?php echo $source->description; ?></DESCRIPTION>
		<MAPMASTER/>
	</DSN>
<?php endforeach; ?>
</DASDSN>
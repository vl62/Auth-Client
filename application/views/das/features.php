<?php echo $xml_init; ?>
<?php echo $stylesheet_init; ?>
<!DOCTYPE DASGFF SYSTEM "http://www.biodas.org/dtd/dasgff.dtd">
<DASGFF>
	<GFF version="1.01" href="<?php echo base_url() . "das/" . $source . "/features"; ?>">
	<SEGMENT id="<?php echo $locations['chr']; ?>" version="1.0" start="<?php echo $locations['start']; ?>" stop="<?php echo $locations['end']; ?>">
		<?php ksort($variants); foreach ($variants as $variant): ?>
		<FEATURE id="<?php echo $this->config->item('cvid_prefix') . $variant['cafevariome_id']; ?>" label="<?php echo $this->config->item('cvid_prefix') . $variant['cafevariome_id']; ?>">
			<TYPE id="Strain">Strain</TYPE>
			<START><?php echo $variant['start'] ?></START>
			<END><?php echo $variant['start'] ?></END>
			<METHOD id=""></METHOD>
			<NOTE><?php echo " HGVS=" . $variant['ref'] . ":" . $variant['hgvs'] . " | Comment=" . $variant['comment']; ?></NOTE>
			<LINK href="<?php echo base_url() . "discover/variant/" . $variant['cafevariome_id']; ?>"><?php echo $this->config->item('cvid_prefix') . $variant['cafevariome_id']; ?></LINK>
		</FEATURE>
		<?php endforeach; ?>
	</SEGMENT>
	</GFF>
</DASGFF>
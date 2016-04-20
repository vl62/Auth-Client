<div style="width:800px; margin:0 auto;">
	
	
	<table>
		<tr>
			<td><em>Executed by</em></td>
			<td><em style="padding-right: 10px;">:</em></td>
			<td><em><strong><?php echo $this->session->userdata('email') . " &#64; " . date("Y-m-d H:i:s") . " GMT"; ?></strong></em></td>
		</tr>
		<tr>
			<td><em>Query source</em></td>
			<td><em style="padding-right: 10px;">:</em></td>
			<td><em><strong><?php echo $source; ?></strong></em></td>
		</tr>
	<?php if(isset($precan_log_id)): ?>
		<tr>
			<td><em>Query type</em></td>
			<td><em style="padding-right: 10px;">:</em></td>
			<td><em><strong><?php echo $api['case_control'] ?></strong></em></td>
		</tr>
		<tr>
			<td><em>Query notes</em></td>
			<td><em style="padding-right: 10px;">:</em></td>
			<td style="width: 550px;"><em><strong><?php echo $api['notes'] ? $api['notes'] : "n/a"; ?></strong></em></td>
		</tr>
		<tr>
			<td><em>Query syntax</em></td>
			<td><em style="padding-right: 10px;">:</em></td>
			<td style="width: 550px;"><em><strong><?php echo $api['queryString']; ?></strong></em></td>
		</tr>
	<?php else: ?>
		<tr>
			<td><em>Query syntax</em></td>
			<td><em style="padding-right: 10px;">:</em></td>
			<td style="width: 550px;"><em><strong><?php echo $term; ?></strong></em></td>
		</tr>
	<?php endif; ?>
		
		<tr>
			<td><em>Total results</em></td>
			<td><em style="padding-right: 10px;">:</em></td>
			<td><em><strong><?php echo count($variants); ?></strong></em></td>
		</tr>
	</table>
	

<?php
	echo "<br/>";
	echo $this->input->post('email');

	echo '<em><strong><u>List of DerIDs</u>: </strong></em><br/>';
	foreach ($variants as $variant) {
	    echo $variant;
	    echo '<br/>';
	}

echo '</div>';
?>
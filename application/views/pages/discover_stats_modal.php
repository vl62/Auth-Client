<script>
$(function() {
    $('.termselect').click(function(e) {
		e.preventDefault();
		var href = $(this).text();
		var current_term = $('#term').val();
//		if ( current_term ) {
//			$('#term').val(current_term + ' \"AND\" ' + href);
//		}
//		else {
			$('#term').val(href);
//		}
		$('#<?php echo $type; ?>Modal').modal('hide');
    });
});

</script>

<script>
$(document).ready(function() {
	$('.general').dataTable( {
		"sDom": "<'row'<'span5 offset1'l><'span6'f>r>t<'row'<'span5 offset1'i><'span6'p>>",
		"sPaginationType": "bootstrap",
//		"bStateSave": true,
		"bRetrieve": true,
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
		}, 
		"aLengthMenu": [[5, 10, 25, 50, 100, 200, -1], [5, 10, 25, 50, 100, 200, "All"]],
		"aaSorting": [[ 1, "desc" ]]
	} );
});
</script>
<div class="row-fluid">
	<div class="span12 pagination-centered">
		<div class="well">
			<table class="table table-bordered table-striped table-hover general">
				<thead>
					<tr>
<!--						<th>Add to query</th>-->
						<th><?php echo ucfirst($type); ?></th>
						<th>Variant Count</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($counts as $key => $count): ?>
					<tr>
<!--						<td><a href="#" class="termselect">AND <?php // echo $key; ?></a> <a href="#" class="termselect">OR <?php // echo $key; ?></a></td>-->
						<td><a href="#" class="termselect"><?php echo $key; ?></a></td>
						<td>
							<?php echo $count; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

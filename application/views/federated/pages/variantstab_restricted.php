<?php


echo '<div style="width:800px; margin:0 auto;">';

foreach ($source_owner as $row)
{
   echo '<em><strong>Source owner name: ';
   echo $row['owner_name'];
   echo "</BR>";
   echo 'Source owner email: ';
   echo $row['email'];
   echo '</em></strong>';
   echo "</BR>";
}

echo "</BR>";

echo $this->input->post('email');

echo 'List of DerIDs: </BR>';
foreach ($variants as $variant) {
    echo $variant;
    echo '</BR>';
}
        

echo '</div>';
?>
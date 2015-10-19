<?php
echo "### LOVD-version 2000-330 ### Variants/Patients ### Do not remove this line, unless importing in versions before 2.0-04 ###\n<br />";
echo '"{{ Variant/Reference }}"	"{{ Variant/Exon }}"	"{{ Variant/DNA }}"	"{{ Variant/RNA }}"	"{{ Variant/Protein }}"	"{{ Variant/Restriction_site }}"	"{{ Variant/Frequency }}"	"{{ Variant/DBID }}"	"{{ Variant/Detection/Template }}"	"{{ Variant/Detection/Technique }}"	"{{ Patient/Patient_ID }}"	"{{ Patient/Phenotype/Disease }}"	"{{ Patient/Reference }}"	"{{ Patient/Remarks }}"	"{{ Patient/Remarks_Non_Public }}"	"{{ Patient/Times_Reported }}"	"{{ ID_variantid_ }}"	"{{ ID_patientid_ }}"	"{{ ID_allele_ }}"	"{{ ID_pathogenic_ }}"	"{{ ID_status_ }}"	"{{ ID_sort_ }}"	"{{ ID_submitterid_ }}"	"{{ ID_variant_created_by_ }}"	"{{ variant_created_date_ }}"	"{{ ID_variant_edited_by_ }}"	"{{ variant_edited_date_ }}"	"{{ ID_patient_created_by_ }}"	"{{ patient_created_date_ }}"	"{{ ID_patient_edited_by_ }}"	"{{ patient_edited_date_ }}"' . "\n<br />";
ksort($variants);
foreach ($variants as $variant) {
	// Declare all variables that need to be printed in order to avoid Undeclared Index errors
	$ref = isset($variant['ref']) ? $variant['ref'] : '';
	$hgvs = isset($variant['hgvs']) ? $variant['hgvs'] : '';
	$phenotype = isset($variant['phenotype']) ? $variant['phenotype'] : '';
	echo '"' . $ref . '"' . "\t" . '"-"' . "\t" . '""' . "\t" . '"'. $hgvs . '"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"' . $phenotype . '"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"'. "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\t" . '"-"' . "\n<br />";
}

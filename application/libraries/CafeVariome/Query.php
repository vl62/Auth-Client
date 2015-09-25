<?php

require_once "CafeVariome.php";

class Query extends CafeVariome {

    function __construct($parameters) {
//		parent::__construct();
        $this->CI = & get_instance();
        if (array_key_exists('syntax', $parameters)) {
            $this->syntax = $parameters['syntax'];
        } else {
            $this->syntax = 'elasticsearch';
        }
    }

    function parse($query) {
        $query_data = $query['query'];

        $query_array = array();
        foreach ($query_data as $k => $v) {
            foreach ($v as $element) {
                if (!$this->syntax == "elasticsearch")
                    continue;

                if (strtolower($element['operator']) == "is") {
                    if ($k == "sequence")
                        $query_array[$element['querySegmentID']] = ($element['molecule'] == "DNA" ? "dna_sequence:" : "protein_sequence:") . $element['sequence'];
                    else if ($k == "geneSymbol")
                        $query_array[$element['querySegmentID']] = "gene_symbol:" . $element['geneSymbol']['symbol'];
                    else if ($k == "hgvsName")
                        $query_array[$element['querySegmentID']] = "(hgvs_reference:" . $element['reference']['id'] . " AND hgvs_name:" . $element['hgvsName'] . ")";
                } else if (strtolower($element['operator']) == "is like") {
                    if ($k == "geneSymbol")
                        $query_array[$element['querySegmentID']] = "gene_symbol:*" . $element['geneSymbol']['symbol'] . "*";
                }
            }
        }

        error_log("query array -> " . print_r($query_array, 1));

        $query_statement = $query['queryStatement'];
//        error_log("QUERY STATEMENT -> $query_statement");
//        Add hashes to make sure that numbers on their own don't get replace (e.g. BRCA2 would get replaced if there's a statement ID of 2 after first initial)
        $query_statement = preg_replace('/\b(\d+)\b/', "##$1##", $query_statement);
//		error_log("queryStatement: $query_statement");
        foreach ($query_array as $statement_id => $query_element) {
            $statement_id = "##" . $statement_id . "##";
            $query_element = "##" . $query_element . "##";
//            error_log("BEFORE query_element -> $statement_id -> $query_element -> $query_statement");
            $query_statement = preg_replace("/$statement_id/", "$query_element", $query_statement);
//            error_log("AFTER query_element -> $statement_id -> $query_element -> $query_statement");
        }
        $query_statement = str_replace('##', '', $query_statement);
        error_log("query_statement -> $query_statement");

        $query_statement_for_display = $query_statement;
        $query_statement_for_display = str_replace('_d', '', $query_statement_for_display); // Remove the appended numeric index name so that it isn't displayed to the user
        $query_statement_for_display = str_replace('_raw', '', $query_statement_for_display);
        $query_statement_for_display = str_replace('_missing_', 'missing', $query_statement_for_display);
        $query_statement_for_display = str_replace('_exists_', 'exists', $query_statement_for_display);
        $query_statement_for_display = str_replace('\[', '[', $query_statement_for_display);
        $query_statement_for_display = str_replace('\]', ']', $query_statement_for_display);
//        $query_statement_for_display = str_replace('_', ' ', $query_statement_for_display);
        print "<h4>$query_statement_for_display</h4>";
        return $query_statement;
    }

}

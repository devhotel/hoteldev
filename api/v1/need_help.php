<?php

/**
 * Script called on CMS API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');
if (isset($_POST['faq_keyword'])) {
	$faq_keyword = htmlentities($_POST['faq_keyword'], ENT_COMPAT, 'UTF-8');
} else {
	$faq_keyword = '';
}
if (empty($faq_keyword)) {

	$result_faq = $db->query('SELECT id as faq_id, question, answer FROM pm_faq WHERE checked = 1');

	if ($result_faq !== false && $db->last_row_count() > 0) {

		$rowFAQ = $result_faq->fetchAll();

		$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('faq_list' => $rowFAQ));
		displayOutput($response);
	} else {
		$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('faq_list' => "No FAQ Found!"));
		displayOutput($response);
	}
} else {

	$result_faq = $db->query('SELECT id as faq_id, question, answer FROM pm_faq WHERE (question LIKE "%' . $faq_keyword . '%" OR answer LIKE 
"%' . $faq_keyword . '%") AND checked = 1');

	if ($result_faq !== false && $db->last_row_count() > 0) {

		$rowFAQ = $result_faq->fetchAll();

		$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('faq_list' => $rowFAQ));
		displayOutput($response);
	} else {
		$response = array('status' => array('error_code' => 0, 'message' => 'Success'), 'response' => array('faq_list' => "No FAQ Found!"));
		displayOutput($response);
	}
}

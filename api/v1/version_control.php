<?php

/**
 * Script called on Version Control
 */
require_once('../../common/lib.php');
require_once('../../common/define.php');
$data = json_decode(file_get_contents('php://input'), true);
$message = '';
$errorCode = 0;
$result = array(
	'updateRequired' => 'no',
	'severity' => 'nonCritical'
);
if (empty($data['version'])) {
	$message = 'Version Code is required';
	$errorCode = 1;
} else {
	$check = $db->query('SELECT * FROM pm_version_control WHERE version_code = "' . $data['version'] . '"')->fetch(PDO::FETCH_ASSOC);
	$checkAbove = $db->query('SELECT * FROM pm_version_control WHERE version_code > "' . $data['version'] . '"');
	if (empty($check)) {
		$result['updateRequired'] = 'no';
		$result['severity'] = 'nonCritical';
	} else {
		if ($checkAbove !== false && $db->last_row_count() > 0) {
			foreach ($checkAbove as $val) {
				if ($val['severity'] == 'critical') {
					$message = 'You must update Gupta Hotels App to latest version from play store to continue using it';
					$result['updateRequired'] = 'yes';
					$result['severity'] = 'critical';
					break;
				} else {
					$message = 'You must update Gupta Hotels App to latest version from play store to continue using it';
					$result['updateRequired'] = 'yes';
					$result['severity'] = 'nonCritical';
				}
			}
		}
	}
}
$response = array('status' => array('error_code' => $errorCode, 'message' => $message));
$response['response']['updateResponse']  = $result;
displayOutput($response);

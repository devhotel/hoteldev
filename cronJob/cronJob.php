<?php
date_default_timezone_set("Asia/Kolkata");
$dbhost = "localhost";
$dbuser = "guptahotels_gupta";
$dbpass = "xIh~j(3kld;o";
$db = "guptahotels_gupta";
$mysqli = new mysqli($dbhost, $dbuser, $dbpass,$db);
if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}
$prevDate = date("Y-m-d", strtotime("-1 day"));
$start = strtotime(date($prevDate. ' 00:00:00')) + 19800;
$end = strtotime(date($prevDate .' 23:59:59')) + 19800;
$query = "SELECT pb.id bookingId, from_unixtime(from_date, '%d-%m-%Y') fromDate, from_unixtime(to_date, '%d-%m-%Y') toDate, ph.title hotelName, CONCAT(pb.firstname, ' ', pb.lastname) AS customerName, pb.mobile,  round(pb.total) total,
CASE 
WHEN pb.payment_option = 'arrival' THEN 'Pay At Hotel'
WHEN pb.payment_option = '2checkout' THEN 'Pay At Hotel'
WHEN pb.payment_option = '' THEN 'Pay At Hotel'
WHEN pb.payment_option = 'paypal' THEN 'Pre Paid' 
WHEN pb.payment_option = 'pre-paid' THEN 'Pre Paid' END AS payments, 
CASE 
WHEN round(pb.status) = '1' THEN 'Pending'
WHEN round(pb.status) = '2' THEN 'Cancelled'
WHEN round(pb.status) = '4' THEN 'Paid'
END AS paymentStatus,
CASE 
WHEN pb.checked_out = 'out' THEN 'Checked Out'
WHEN pb.checked_in = 'in' THEN 'Checked In'
END AS checkedInOut 
FROM pm_booking pb 
LEFT JOIN pm_hotel ph ON ph.hotelid = pb.id_hotel 
WHERE pb.add_date >= '".$start."' AND pb.add_date <= '".$end."' ORDER BY pb.from_date ASC";
/*WHERE pb.from_date >= '".$start."' AND pb.to_date <= '".$end."' ORDER BY pb.from_date ASC";*/
$res = $mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
$paid = $cancelled = $pending = 0;
$html = '<h1>Booking Summary</h1>';
if(!empty($res)){    
    foreach($res as $v){
        if($v['paymentStatus'] == 'Pending'){
            $pending++;
        }else if($v['paymentStatus'] == 'Cancelled'){
            $cancelled++;
        }else if($v['paymentStatus'] == 'Paid'){
            $paid++;
        }
    }
}
$html .= "<table style='width:100%; border:0px; background-color:#e3f6fa; padding-bottom:20px;' cellpadding='0' cellspacing='0' border = 1>
            <tr style='height: 50px; text-align:center;'>
                <td><b>Date</b></td>
                <td><b>Total Bookings</b></td>
                <td><b>Paid</b></td>
                <td><b>Cancelled</b></td>
                <td><b>Pending</b></td>
            </tr>
            <tr style='height: 50px; text-align:center;'>
                <td> ".date_format(date_create(date($prevDate)), 'j M, y')." </td>
                <td> ".count($res)." </td>
                <td> ".$paid." </td>
                <td> ".$cancelled." </td>
                <td> ".$pending." </td>
            </tr>";

$html .= '</table>';
$html .= '<br/><br/><br/><h1>Booking Details</h1>';
$html .= "<table style='width:100%; border:0px; background-color:#e3f6fa; padding-bottom:20px;' cellpadding='0' cellspacing='0' border = 1>
            <tr style='height: 50px; text-align:center;'>
                <td><b>Booking Id</b></td>
                <td><b>From Date</b></td>
                <td><b>To Date</b></td>
                <td><b>Hotel</b></td>
                <td><b>Customer</b></td>
                <td><b>Mobile</b></td>
                <td><b>Total</b></td>
                <td><b>Payment</b></td>
                <td><b>Payment Status</b></td>
            </tr>";
if(!empty($res)){
    foreach($res as $val){
        $html .= "<tr style='height: 50px; text-align:center;'>
                    <td> ".$val['bookingId']." </td>
                    <td> ".$val['fromDate']." </td>
                    <td> ".$val['toDate']." </td>
                    <td> ".$val['hotelName']." </td>
                    <td> ".$val['customerName']." </td>
                    <td> ".$val['mobile']." </td>
                    <td> Rs. ".$val['total']." </td>
                    <td> ".$val['payments']." </td>
                    <td> ".$val['paymentStatus']." </td>
                </tr>";
    }
}else{
    $html .= "<tr style='height: 50px; text-align:center;'>
                <td colspan='9'>No Booking Found !!!</td>
            </tr>";
}
$html .= '</table>';
$subject    = 'Gupta Hotels : Booking Details of ' . date_format(date_create(date($prevDate)), 'j M, y');
$from_email = 'reservation@guptahotels.com';
$from_name  = 'Gupta Hotels';
$to         = 'asr.computers@yahoo.com';
$headers    = 'MIME-Version: 1.0' . "\r\n";
$headers    .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers    .= 'From: ' . $from . "\r\n" .
    'Reply-To: ' . $from . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($to, $subject, $html, $headers);
?>
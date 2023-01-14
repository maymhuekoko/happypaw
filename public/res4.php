<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
<?php
session_start();
$_SESSION['token'] = bin2hex(random_bytes(32));
use App\User;

$response = file_get_contents('php://input');
			echo "Response:<br/><textarea style='width:100%;height:80px'>".$response."</textarea>"; 
			//each response params:
			$version = $_REQUEST["version"];
			$request_timestamp = $_REQUEST["request_timestamp"];
			$merchant_id = $_REQUEST["merchant_id"];
			$currency = $_REQUEST["currency"];
			$order_id = $_REQUEST["order_id"];
			$order_pieces= explode("-",$order_id);
			$booking_id= $order_pieces[0];
			$order_invoice= $order_pieces[1];
			$amount = $_REQUEST["amount"];
			$invoice_no = $_REQUEST["invoice_no"];
			$transaction_ref = $_REQUEST["transaction_ref"];
			$approval_code = $_REQUEST["approval_code"];
			$eci = $_REQUEST["eci"];
			$transaction_datetime = $_REQUEST["transaction_datetime"];
			$payment_channel = $_REQUEST["payment_channel"];
			$payment_status = $_REQUEST["payment_status"];
			$channel_response_code = $_REQUEST["channel_response_code"];
			$channel_response_desc = $_REQUEST["channel_response_desc"];
			$masked_pan = $_REQUEST["masked_pan"];
			$stored_card_unique_id = $_REQUEST["stored_card_unique_id"];
			$backend_invoice = $_REQUEST["backend_invoice"];
			$paid_channel = $_REQUEST["paid_channel"];
			$recurring_unique_id = $_REQUEST["recurring_unique_id"];
			$paid_agent = $_REQUEST["paid_agent"];
			$payment_scheme = $_REQUEST["payment_scheme"];
			$user_defined_1 = $_REQUEST["user_defined_1"];
			$user_defined_2 = $_REQUEST["user_defined_2"];
			$user_defined_3 = $_REQUEST["user_defined_3"];
			$user_defined_4 = $_REQUEST["user_defined_4"];
			$user_defined_5 = $_REQUEST["user_defined_5"];
			$browser_info = $_REQUEST["browser_info"];
			$ippPeriod = $_REQUEST["ippPeriod"];
			$ippInterestType = $_REQUEST["ippInterestType"];
			$ippInterestRate = $_REQUEST["ippInterestRate"];
			$ippMerchantAbsorbRate = $_REQUEST["ippMerchantAbsorbRate"];
			$payment_scheme = $_REQUEST["payment_scheme"];
			$process_by = $_REQUEST["process_by"];
			$sub_merchant_list = $_REQUEST["sub_merchant_list"];
			$hash_value = $_REQUEST["hash_value"];   
			
			$checkHashStr = $version . $request_timestamp . $merchant_id . $order_id . 
			$invoice_no . $currency . $amount . $transaction_ref . $approval_code . 
			$eci . $transaction_datetime . $payment_channel . $payment_status . 
			$channel_response_code . $channel_response_desc . $masked_pan . 
			$stored_card_unique_id . $backend_invoice . $paid_channel . $paid_agent . 
			$recurring_unique_id . $user_defined_1 . $user_defined_2 . $user_defined_3 . 
			$user_defined_4 . $user_defined_5 . $browser_info . $ippPeriod . 
			$ippInterestType . $ippInterestRate . $ippMerchantAbsorbRate . $payment_scheme .
			$process_by . $sub_merchant_list;
			  
			$SECRETKEY = "7jYcp4FxFdf0";
			$checkHash = hash_hmac('sha256',$checkHashStr, $SECRETKEY,false); 
			// echo "checkHash: ".$checkHash."<br/><br/>";
		
			if(strcmp(strtolower($hash_value), strtolower($checkHash))==0){
			echo $order_id."<br/>";
				// echo "Hash check = success. it is safe to use this response data.";
				echo "
					<p id='bookingId'>$booking_id</p>
					<p id='invoice_no'>$order_invoice</p>
				<p id='amount'> Success your payment $amount $</p>
				";

			}
			else{
				echo "<p > failed  <a href=".'/GetToken'."> Back </a></p>";
				// echo "Hash check = failed. do not use this response data.";
			}
?>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

			<script>
				$(document).ready(function(){
					var booking_id= $('#bookingId').text();
					var amount= $('#amount').text();
					var invoice_no= $('#invoice_no').text();
					var token= $('#token').val();
					if(booking_id){
						window.location.href = "storepayment/web/"+booking_id+"/"+invoice_no;
					}
				});

			</script>
</body>
</html>

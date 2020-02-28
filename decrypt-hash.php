<?php


$decryptData = '';

if(isset($_POST['tx_url']) && $_POST['tx_url'] != ""){
	
	$tx_hash = str_replace('https://explorer.enix.ai/tx/', '', $_POST['tx_url']);
	$tx_json_data = file_get_contents('https://explorer.enix.ai/api/?module=transaction&action=gettxinfo&txhash=' . $tx_hash);
	$tx_json_data = json_decode($tx_json_data);
	
	if($tx_json_data->status == 0){
		
		$decryptData = $tx_json_data->message . '! Try again later.';
		
	} else {
	
		$utf8_converted = str_replace('0x', '', utf8_encode($tx_json_data->result->input));
		
		if(isset($_POST['tx_password']) && trim($_POST['tx_password']) != ""){
			$decryptData = deCipherConversion(hex2bin($utf8_converted), $_POST['tx_password']);
		} else {
			$decryptData = hex2bin($utf8_converted);
		}
		
	}
	
}

function deCipherConversion($crypted_token, $password){
	
	list($crypted_token, $enc_iv) = explode("::", $crypted_token);;
  $cipher_method = 'aes-128-ctr';
  $enc_key = openssl_digest($password, 'SHA256', TRUE);
  $token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
  unset($crypted_token, $cipher_method, $enc_key, $enc_iv);
  
  return $token;
	
}
 
?>

<!DOCTYPE html>
<html class="no-touchevents">
	
	<head>
		<title>Enix Foundation | Decentralised Blockchain</title>
	</head>
	
	<body style="text-align: center;">
		
		<form name="decrypt_txt_data" method="post" action="">
			
			<h3>Decrypt TX HASH</h3>
			
			<label>Input TX URL: <input type="text" name="tx_url" value="" placeholder="For example: https://explorer.enix.ai/tx/0x0e2fe582d2d798a6b44dfed2fde8f144b98a3f380c6eba3d85d427e3712ec465" style="padding: 5px 10px; width: 60%;"/></label>
			
			<br /><br />
			
			<label>Password: <input type="password" name="tx_password" value="" style="padding: 5px 10px; width: 60%;"/></label>
			
			<br /><br /><br />
			
			<input type="submit" name="decrypt_button" value="Decrypt" style="border: none; background-color: #0f2e4c; color: #FFF; font-size: 16px; padding: 10px 20px;" />
			
		</form>
		
		<br /><hr /><br />
			
		<div style="overflow: auto; height: 200px; width: 40%; word-break: break-all; margin: 0 auto; border: 1px solid #000; padding: 10px;"><?php echo $decryptData; ?></div>
		
	</body>
	
</html>
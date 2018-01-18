<?php 

require 'vendor/autoload.php';

$soapCheck = lspeak\identitycheck\identitycheck::soapIdentityCheck("***********","TOLGA KARABULUT" ,"19**");
$algorithmCheck = lspeak\identitycheck\identitycheck::algorithmCheck("***********");

if( $soapCheck ){
	#Valid identity number
}
if( $algorithmCheck ) {
	#Valid identity number algorithm
}

 ?>
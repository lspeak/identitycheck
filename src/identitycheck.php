<?php 
namespace lspeak\identitycheck;

/**
*  @author TOLGA KARABULUT
*/
class identitycheck {
    
	/**
	 * [Identity Number Algorithm]
	 * @param  [Int] $number 
	 * @return [bool]
	 */
    static function algorithm( $number ){
    	if ( empty($number) ) return false;
  		if ( strlen($number) != 11 ) return false;
  		if ( !is_numeric( $number) ) return false;
		if ( !preg_match('/(?<!\S)\d++(?!\S)/', $number )) return false;
		
		$digit = preg_split('//', $number, -1, PREG_SPLIT_NO_EMPTY);
		if ($digit[0] == 0)return false;
			$odd = $digit[0] + $digit[2] + $digit[4] + $digit[6] + $digit[8];
			$even = $digit[1] + $digit[3] + $digit[5] + $digit[7];
			$digit10 = ($odd * 7 - $even) % 10;
			$total = ($odd + $even + $digit[9]) % 10;
		if ($digit10 != $digit[9] or $total != $digit[10])return false;
		return true;
    }


	/**
	* [E-Devlet API ]
	* @param  [String] $number 
	* @param  [String] $fullname 
	* @param  [Date] $birtday  
	* @return [Bool]           
	*/
    static function soapIdentityCheck( $number  , $fullname , $birtday )
    {
    	if ( empty($fullname)) return false ;
    	if ( !is_numeric($number) ) return false ;
    	if ( strlen($birtday) != 4 ) return false ;
    	
		$fullname  =  str_replace(['i', 'ı', 'ü', 'ğ', 'ş', 'ö', 'ç'],['İ', 'I', 'Ü', 'Ğ', 'Ş', 'Ö', 'Ç'], $fullname);
		$fullname  =  mb_strtoupper($fullname);
		$e = explode(" " , $fullname);
		$surname = end($e);
		$name = trim(str_replace($surname , "" ,$fullname));
		$xml_data = '<?xml version="1.0" encoding="utf-8"?>
						<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
							<soap:Body>
								<TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
									<TCKimlikNo>'.$identity.'</TCKimlikNo>
									<Ad>'.$name.'</Ad>
									<Soyad>'.$surname.'</Soyad>
									<DogumYili>'.$birtday.'</DogumYili>
								</TCKimlikNoDogrula>
							</soap:Body>
						</soap:Envelope>';
		$ch = curl_init();
		$options = array(	CURLOPT_URL				=> 'https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx',
							CURLOPT_POST			=> true,
							CURLOPT_POSTFIELDS		=> $xml_data,
							CURLOPT_RETURNTRANSFER	=> true,
							CURLOPT_SSL_VERIFYPEER	=> false,
							CURLOPT_HEADER			=> false,
							CURLOPT_HTTPHEADER		=> array(
									'POST /Service/KPSPublic.asmx HTTP/1.1',
									'Host: tckimlik.nvi.gov.tr',
									'Content-Type: text/xml; charset=utf-8',
									'SOAPAction: "http://tckimlik.nvi.gov.tr/WS/TCKimlikNoDogrula"',
									'Content-Length: '.strlen($xml_data)
							),
						);
		curl_setopt_array($ch, $options);
		$response = curl_exec($ch);
		curl_close($ch);
		return ( strip_tags($response) === 'true' ) ? true : false ;
    }
} 
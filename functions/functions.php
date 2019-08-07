<?php
function cleanStr($str) {
        return preg_replace("/[^0-9]/", "", $str);
    }
function mask($val, $mask){
    $maskared = '';
    $k = 0;
    for($i = 0; $i<=strlen($mask)-1; $i++){
        if($mask[$i] == '#'){
            if(isset($val[$k])){
                $maskared .= $val[$k++];
            }
        }else{
            if(isset($mask[$i])){
                $maskared .= $mask[$i];
            }
        }
    }
    return $maskared;
}
function hashPassword($password){
    $timeTarget = 0.05;
    $cost = 8;
    do {
        $cost++;
        $start = microtime(true);
        password_hash($password, PASSWORD_BCRYPT, ["cost" => $cost]);
        $end = microtime(true);
    } while (($end - $start) < $timeTarget);
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => $cost]);
    return $hashedPassword;
}
function validaCPF($cpf = null) {

	// Verifica se um número foi informado
	if(empty($cpf)) {
		return false;
	}

	// Elimina possivel mascara
	$cpf = preg_replace("/[^0-9]/", "", $cpf);
	$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
	
	// Verifica se o numero de digitos informados é igual a 11 
	if (strlen($cpf) != 11) {
		return false;
	}
	// Verifica se nenhuma das sequências invalidas abaixo 
	// foi digitada. Caso afirmativo, retorna falso
	else if ($cpf == '00000000000' || 
		$cpf == '11111111111' || 
		$cpf == '22222222222' || 
		$cpf == '33333333333' || 
		$cpf == '44444444444' || 
		$cpf == '55555555555' || 
		$cpf == '66666666666' || 
		$cpf == '77777777777' || 
		$cpf == '88888888888' || 
		$cpf == '99999999999') {
		return false;
	 // Calcula os digitos verificadores para verificar se o
	 // CPF é válido
	 } else {   
		
		for ($t = 9; $t < 11; $t++) {
			
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $cpf{$c} * (($t + 1) - $c);
			}
			$d = ((10 * $d) % 11) % 10;
			if ($cpf{$c} != $d) {
				return false;
			}
		}

		return true;
	}
}
/**
     * Calculate Age.
     * @param string $date Date string to calculate the age.
     * @param string $format Format string to br used for get date data for the calculation.
     * @return string Return the string cotaining the age.
     */
function ageYear($date, $format){
   
    //Find delimiter
    $delimiter = str_split($format);
    $delimiter = $delimiter[1];
    //Explode the format string
    $format = explode($delimiter, $format);
    //Explode the date string
    $date = explode($delimiter, $date);
    //Find the date data
    $day = $date[array_search("d", $format)];    
    $month = $date[array_search("m", $format)];
    $year = $date[array_search("Y", $format)];
    //Get today and birthday date variable;
    $today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    $birthday = mktime( 0, 0, 0, $month, $day, $year);
    //Calculate the age
    $age = floor((((($today - $birthday) / 60) / 60) / 24) / 365.25);
    return $age;
}

function idadeStr($data){
    $date = new DateTime($data); 
    $interval = $date->diff(new DateTime(date('y-m-d'))); 
    return $interval->format('%y ano(s), %m mês(es) e %d dia(s)');
}
/**
* Function to generate shorten names
*
*
* @param integer $name name to be shortened
* @param boolean $agnome If the name has a agnome
*
* @return string The shortened name
*/
function shortenName($name, $agnome = false){
    $name = strip_tags($name);

    $words    = explode(' ', $name);
    $firstname       = $words[0];

    $words    = explode(' ', $name);
    $lastname  = trim($words[count($words) - 1]);            
    if($agnome){
        $lastname  = trim($words[count($words) - 2]);                 
    }

    $lastposition = count($words) - 1;
    if($agnome){
        $lastposition = count($words) - 2;                
    }

    $midname = '';
    for($a = 1; $a < $lastposition; $a++){
        $firstname .= ' '.strtoupper(substr($words[$a], 0,1)).'.';
    }
    if($agnome){
        $lastname .= ' '.strtoupper(substr($words[count($words) - 1], 0,1));
        if(ucwords($words[count($words) - 1]) == 'Filho'){
            $lastname .= strtoupper(substr($words[count($words) - 1], 3,1)).'.';
        }else if(ucwords($words[count($words) - 1]) == 'Junior'){
            $lastname .= strtoupper(substr($words[count($words) - 1], 2,1)).'.';            
        }else{
            $lastname .= '.';
        }
    }
return trim($firstname.$midname.' '.$lastname);
}
/**
* Function to generate random passwords
*
* @author Julio Cavallari <julio_cesar__@live.com>
*
* @param integer $length Tamanho da senha a ser gerada
* @param boolean $capital If the function will use capital letters
* @param boolean $number If the function will use numbers
* @param boolean $simbolos If the function will use simbols
*
* @return string The generated password
*/
function randomPasswordGen($length = 15, $uppercase = true, $number = true, $simbol = true){
    $lowercaseLetter = 'abcdefghijklmnopqrstuvwxyz';
    $uppercaseLetter = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '1234567890';
    $simbols = '!@#$%*-';
    $return = '';
    $useCharacters = $lowercaseLetter;
    if ($uppercase){
        $useCharacters .= $uppercaseLetter;
    }
    if ($number){
        $useCharacters .= $numbers;
    }
    if ($simbol){
        $useCharacters .= $simbols;
    }

    $characteresLength = strlen($useCharacters);

    for ($i = 1; $i <= $length; $i++){
        $randomCharacter = mt_rand(1, $characteresLength);
        $return .= $useCharacters[$randomCharacter-1];
    }
    return $return;
}
function getAmount($money){
    $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
    $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

    $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

    $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
    $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

    return (float) str_replace(',', '.', $removedThousendSeparator);
}
function porcentagem_nx($parcial, $total){
    return ($parcial * 100) / $total;
}
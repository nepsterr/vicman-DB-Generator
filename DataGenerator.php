<?php
class DataGenerator {



	public static function generate($field) {
		$type = $field->getColumn('data_type');
		$decimals = array('decimal','float','double','real');
		if($type === '');
		elseif(stripos($type,"int") !== false)
			return self::generateInt($type,$field->getColumn('unsigned'));
		elseif(in_array($type,$decimals))
			return self::generateDecimalNumeric($field->getColumn('precision'),$field->getColumn('scale'),$field->getColumn('unsigned'));
		elseif($type === 'boolean')
			return self::generateBoolean();
		elseif($type === 'bit')
			return self::generateBits($field->getColumn('precision'));
		elseif($type === 'date')
			return self::generateDate(false,false);
		elseif(($type === 'datetime')||($type === 'timestamp'))
			return self::generateDate(true,false);
		elseif($type === 'time')
			return self::generateDate(false,true);
		elseif($type === 'year')
			return self::generateYear();
		elseif((stripos($type,"char") !== false)||(stripos($type,"text") !== false))
			return self::generateRandomString($field->getColumn('length'));
		else
			return 'random';
	}

	public static function generateInt($type, $unsigned = false) {
		/* Issues with INT type. Random generate always 0 */
		$maxLength = 1000;
		if($type === "tinyint")
			$maxLength = tinyint;
		elseif($type === "smallint")
			$maxLength = smallint;
		elseif($type === "mediumint")
			$maxLength = mediumint;
		elseif($type === "justint")
			$maxLength = justint;
		if($unsigned)
			$formula = rand(0,($maxLength*2+1));
		else
			$formula = rand(0,($maxLength*2+1))-($maxLength+1);
		return $formula;
	}
	
	public static function generateDecimalNumeric($precision /* 5 */, $scale /* 2 */, $unsigned = false) {
		$decimalResult = '0';
		// Need exception here
		if($precision < $scale)
			return;
		// Calculating count of left decimal places
		$decimal = $precision - $scale;
		// Calculating maximum number what can be stored
		$leftMax = 0;
		for($i = 0; $i < $decimal; $i++)
		{
			// Decimal (5,2) can store maximum 999,99. Left side is 999. Its (1 + 10 + 100) * 9; Each number in sum is 10^i. 
			// For exampl Decimal (7,1) will store (7-1 = 6 left side) (1 + 10 + 100 + 1000 + 10000 + 100000) * 9 = 999999;
			$leftMax += 10^$i * 9;
		}
		// Double number will be generating by random generate both sides (decimal and float). Then just concatenate;
		$leftNumber = rand(0,$leftMax);
		$rightNumber = rand(0,$scale);
		$decimalResult = $leftNumber.'.'.$rightNumber;
		// Unsigned its just "-" before number. 
		if(!$unsigned)
			$decimalResult = '-'.$decimalResult;
		return $decimalResult;
	}
	
	public static function generateBoolean() {
		return .01 * rand(0, 100) >= .5;
	}
	
	public static function generateBits($precision /* 5 */) {
		$bitResult = '';
		for($i = 0; $i < $precision; $i++)
			$bitResult .= rand(0,1);
		return $bitResult;
	}
	
	public static function generateDate($withTime = false, $onlyTime = false) {
		$dateStr = "";
		// Generate date from 1970 to now (probably to 2013);
		$dateInt = mt_rand(0,strtotime("now"));
		// Converting to MySQL format
		if($withTime)
			$format = "Y-m-d H:i:s";
		elseif($onlyTime)
			$format = "H:i:s";
		else
			$format = "Y-m-d";
		$dateStr = date($format,$dateInt);
		return $dateStr;
	}
	public static function generateYear() {
		$year = "";
		// Generate year from 1970 to now (probably to 2013);
		$yearInt = mt_rand(0,strtotime("now"));
		// Converting to MySQL format
		$year = date("Y",$yearInt);
		return $year;
	}
	/* String generating between 1 and maximum length */
	public static function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ';
		$len = strlen($characters);
		if($length > 50000)
			$length = 50000;
		$maxLength = rand(1,$length);
		$randomString = '';
		for ($i = 0; $i < $maxLength; $i++) {
			$randomString .= $characters[rand(0, $len - 1)];
		}
		return $randomString;
	}
}
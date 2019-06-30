<?php
	
	/**
	 * 	
	 * @param  [type] $allLoc    [description]
	 * @param  [type] $arrTax    [description]
	 * @param  string $index_key [description]
	 * @param  string $by_key    [description]
	 * @return [type]            [description]
	 */
	function groupingArr($allLoc,$arrTax,$index_key = 'location_id', $by_key = 'tax')
	{
		$tax_by_location = [];
        foreach ($allLoc as $allLoc_key => $allLoc_value) {
            $tax_search = [$by_key => array_values(array_where($arrTax, function ($arrTax_value, $arrTax_key) use ($allLoc_value,$index_key) {
                return ($arrTax_value[$index_key] == $allLoc_value['id']);
            }))];          
            $tax_by_location[] = $allLoc_value + $tax_search;
        }
        return $tax_by_location;
	}

	/**
	 * [queryFormatDate description]
	 * @param  [type] $column   [description]
	 * @param  [type] $asColumn [description]
	 * @param  string $format   [description]
	 * @return [type]           [description]
	 */
	function queryFormatDate($column, $asColumn, $format = '"%d-%b-%Y"')
	{
		return \DB::raw('DATE_FORMAT('.$column.', '.$format.') as '.$asColumn);
	}

	/**
	 * [getDay description]
	 * @param  [type] $date [description]
	 * @return [type]       [description]
	 */
	function getDay($date){
		return Carbon\Carbon::createFromFormat('Y-m-d', $date)->dayOfWeek;
	}

	/**
	 * [getDateParse description]
	 * @param  [type] $date  [description]
	 * @param  [type] $parse [description]
	 * @return [type]        [description]
	 */
	function getDateParse($date,$parse){
		return Carbon\Carbon::createFromFormat('Y-m-d', $date)->modify($parse)->format('Y-m-d');;
	}

	/**
	 * [getDateDiff description]
	 * @param  [type] $date_1 [description]
	 * @param  [type] $date_2 [description]
	 * @return [type]         [description]
	 */
	function getDateDiff($date_1,$date_2){
		$date_1 = Carbon\Carbon::createFromFormat('Y-m-d', $date_1);
	    $date_2 = Carbon\Carbon::createFromFormat('Y-m-d', $date_2);

	    $diff = $date_1->diffInDays($date_2);
	    return $diff;
	}

	/**
	 * [formatMoney description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	function queryFormatMoney($column, $asColumn = null, $currency_id = 'empty', $dec_digit = '2', $dec_point = '","', $thousands_sep = '"."', $default_value = 0)
	{

			$space = " ";
			if($currency_id=="empty")
			{
				$space = "";
				$currency_id = "''";
			}

			if(empty($asColumn))
					return \DB::raw(' IFNULL(concat('.$currency_id.',"'.$space.'",REPLACE(REPLACE(REPLACE(CAST(FORMAT('.$column.', '.$dec_digit.') AS CHAR), ".", "@"), '.$dec_point.', '.$thousands_sep.'), "@", '.$dec_point.')),'.$default_value.') ');
				else
					return \DB::raw(' IFNULL(concat('.$currency_id.',"'.$space.'",REPLACE(REPLACE(REPLACE(CAST(FORMAT('.$column.', '.$dec_digit.') AS CHAR), ".", "@"), '.$dec_point.', '.$thousands_sep.'), "@", '.$dec_point.')),'.$default_value.') as '.$asColumn.' ');
	}

	/**
	 * [user description]
	 * @return [type] [description]
	 */
	function user($id = 0)
	{
		if(!empty($id))
		{
			$user = App\Http\Models\Staff::getAll()
				->find($id);
			\Config::set('sitesetting.user',$user);			
		}
		
		return \Config::get('sitesetting.user');
	}

	/**
	 * [generateRandomString description]
	 * @param  integer $length [description]
	 * @return [type]          [description]
	 */
	function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	/**
	 * 
	 */
	function queryFormatPhotoWithAlias($column, $type = 'avatarpath', $arr_type = ['md'=>'photo_md','xs'=>'photo_xs'])
	{	
		$real_path = '';

		$i = 0;
		$jumlahdata = count($arr_type);
		foreach ($arr_type as $arr_type_key => $arr_type_value) {
			$i++;
			$space = '';
			if(!empty($arr_type_key))
			{
				$space = '_';
			}

			if(!empty($arr_type_value))
			{
				$arr_type_value = ' as '.$arr_type_value;
			}

			$real_path .= 'REPLACE(REPLACE(IFNULL(concat("'.config('sitesetting')[$type].$arr_type_key.$space.'",'.$column.'), "'.url('/img').'/no_avatar.png"), CHAR(13), ""), CHAR(10), "")'.$arr_type_value;

			if($jumlahdata!=$i)
			{
				$real_path .= ',';
			}
		}
		
		return \DB::raw($real_path);
	}

	/**
	 * check_diff_array function
	 *
	 * @param [type] $array1
	 * @param [type] $array2
	 * @return void
	 */
	function check_diff_array($array1, $array2){
		$diff = array();
		foreach($array1 as $key => $val) {
			$same = 0;
			foreach ($array2 as $array2_key => $array2_value) {
				if($val === $array2_value)
				{
					$same = 1;
				}
			}
			if( empty($same) )
			{
				$diff[] = $val;
			}
		}

		return $diff;
	}

	/**
	 * camelToTitle function
	 *
	 * @param [type] $camelStr
	 * @return void
	 */
	function camelToTitle($camelStr)
	{
		$intermediate = preg_replace('/(?!^)([[:upper:]][[:lower:]]+)/',
							' $0',
							$camelStr);
		$titleStr = preg_replace('/(?!^)([[:lower:]])([[:upper:]])/',
							'$1 $2',
							$intermediate);
		return $titleStr;
	}

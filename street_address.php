<?php
class StreetAddressHelper extends Helper {
	/*
		Returns the array of each element of a street address, into the categories:
		property_address  (or house number)
		direction   (abbreviated; n s e w)
		unit (apt number)
		suffix	(also abbreviated, st, blvd, etc.)

		Note: this does not account for City, State, and ZIP!!!  
		Why? Cuz I didn't need it for my project.  
		Add the functionality if you need it--pay it forward, man!
	*/
	//reference arrays
	var $directions = array(
			'N1'=>'North',
			'S1'=>'South',
			'E1'=>'East',
			'W1'=>'West',
			'N2'=>'N.',
			'S2'=>'S.',
			'E2'=>'E.',
			'W2'=>'W.',
			'N'=>'N',
			'S'=>'S',
			'E'=>'E',
			'W'=>'W'
		);
	
	var $suffixes = array(
			'Ave' => 'Avenue',
			'St' => 'Street',
			'Ct' => 'Court',
			'Cir' => 'Circle',
			'Blvd' => 'Boulevard',
			'Dr' => 'Drive',
			'Pl' => 'Place',
			'Cr' => 'Creek',
			'Land' => 'Land',
			'Rd' => 'Road',
			'Ln' => 'Llane',			
			'Tr' => 'Trail',
			'Pkwy'=>'Parkway'	    
		);

	var $suffixes_abbr = array(
			'Circle' => 'Cir',
			'Place' => 'Pl',	    
			'Court' => 'Ct',
			'Street' => 'St',
			'Avenue' => 'Ave',
			'Road' => 'Rd',	  
			'Way' => 'Way',  
			'Boulevard' => 'Blvd',
			'Drive' => 'Dr',
			'Lane'=>'Ln',
			'Trail'=>'Trl',
			'Parkway'=>'Pkwy',
			'Terrace'=>'Ter'
		 );

	/**
	 * returns an array of street address components
	 * 
	 * @param unknown_type $address
	 * @param unknown_type $type
	 */
	function parse($address=null, $type=1){	    	
		switch($type){
	
			case 1: // 1 =  address type (i.e. house number  direction  street unit)

				//break down into array
			    	$addrArray=(explode(' ',$this->sentence_case($address)));

				//first element virtually always house number
			    	$record['property_address']=array_shift($addrArray);
			
				//sanitize unit number
			    	if(in_array('Unit',$addrArray)){
			    		$record['unit']=str_replace('#','',trim(array_pop($addrArray)));
			    		array_pop($addrArray);
			    	}elseif(in_array('Apt',$addrArray)){
			    		$record['unit']=str_replace('#','',trim(array_pop($addrArray)));
			    		array_pop($addrArray);
			    	}elseif(in_array('#',$addrArray)){
			    		$record['unit']=trim(array_pop($addrArray));
			    		array_pop($addrArray);
			    	}

			    	$unit = trim(array_pop($addrArray));

			    	if(is_numeric($unit)){
			    		$record['unit']=$unit;
			    	}elseif(strpos($unit,'#') !== false){
			    		$record['unit']=str_replace('#','',$unit);
			    	}else{
			    		$addrArray[] = $unit;
			    	}

				//sanitize and apply direction		    		
			    	$direction = array_intersect($this->directions,$addrArray);
			    	if(!empty($direction)){
			    		unset($addrArray[key(array_intersect($addrArray,$directions))]);
			    		$record['direction']=strtoupper(substr(key($direction),0,1));
			    	}

				//... street suffix
			    	$suffix = array_intersect($this->suffixes,$addrArray);
			    	if(!empty($suffix)){
			    		unset($addrArray[key(array_intersect($addrArray,$this->suffixes))]);
			    		$record['suffix']=key($suffix);
			    	}
			    	$suffix = array_intersect($this->suffixes_abbr,$addrArray);
			    	if(!empty($suffix)){
			    		unset($addrArray[key(array_intersect($addrArray,$this->suffixes_abbr))]);
			    		$record['suffix']=array_shift($suffix);
			    	}

			    	$record['street']=implode(' ',$addrArray);
			    	
				return $record;
	   		break;
		}
	}
}
?>

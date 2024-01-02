<?php
/* mathfunctions.php */
 

function factorial ($n) {
	$result = 1;	
	$factor = $n;		
	while ($factor > 1) {	
	  $result = $result * $factor;
	  $factor--;		
	}				
	return $result;
}

function isPositiveInteger ($n){
	$result = false;
		if (is_numeric($n))
			if ($n == floor($n))
				if ($n > 0)
					$result = true;
	return $result;
}
?>

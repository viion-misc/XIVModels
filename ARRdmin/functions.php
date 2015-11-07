<?

	function radiansToDegrees($rad) {
	  return $rad / (pi() / 180);
	}

	function transToGoogle($x,$y, $scale)
	{
			 $TILE_SIZE = 2048/$scale;
			 //print_r($TILE_SIZE."<br>");
			 $point = array("x" => $TILE_SIZE / 2, "y" =>  $TILE_SIZE / 2);
			 
			 $pixelsPerLonDegree_ = $TILE_SIZE / 360;
			 $pixelsPerLonRadian_ = $TILE_SIZE / (2 * pi());
			 
			 $lng = $x / $pixelsPerLonDegree_;
			 $latRadians = $y / - $pixelsPerLonRadian_;
			 $lat = radiansToDegrees(2 * atan(exp($latRadians)) - pi() / 2);
			 
			 return array("X" => $lng , "Y" => $lat);
	}
	
	function transToInGame($x,$y, $scale)
	{
		$TILESIZE = 50;
		
		$map = 2048/$scale;
		
		$TileCount = $map/$TILESIZE;
		
		$xCell = ceil(round(($x/$TILESIZE) + ($TileCount/2),1));
		$yCell = ceil(round(($y/$TILESIZE) + ($TileCount/2),1));
		return "(".$xCell."-".$yCell.")";
	
	}
		# Microtime
	function microtime_float() 
	{ 
		list ($msec, $sec) = explode(' ', microtime()); 
		$microtime = (float)$msec + (float)$sec; 
		return $microtime; 
	}
	
	function Show($Variable)
{
	echo '<pre>';
	print_r($Variable);
	echo '</pre>';
}

	function cartesian($input) {
		$result = array();

		while (list($key, $values) = each($input)) {
			// If a sub-array is empty, it doesn't affect the cartesian product
			if (empty($values)) {
				continue;
			}

			// Seeding the product array with the values from the first sub-array
			if (empty($result)) {
				foreach($values as $value) {
					$result[] = array($key => $value);
				}
			}
			else {
				// Second and subsequent input sub-arrays work like this:
				//   1. In each existing array inside $product, add an item with
				//      key == $key and value == first item in input sub-array
				//   2. Then, for each remaining item in current input sub-array,
				//      add a copy of each existing array inside $product with
				//      key == $key and value == first item of input sub-array

				// Store all items to be added to $product here; adding them
				// inside the foreach will result in an infinite loop
				$append = array();

				foreach($result as &$product) {
					// Do step 1 above. array_shift is not the most efficient, but
					// it allows us to iterate over the rest of the items with a
					// simple foreach, making the code short and easy to read.
					$product[$key] = array_shift($values);

					// $product is by reference (that's why the key we added above
					// will appear in the end result), so make a copy of it here
					$copy = $product;

					// Do step 2 above.
					foreach($values as $item) {
						$copy[$key] = $item;
						$append[] = $copy;
					}

					// Undo the side effecst of array_shift
					array_unshift($values, $product[$key]);
				}

				// Out of the foreach, we can add to $results now
				$result = array_merge($result, $append);
			}
		}

		return $result;
	}


?>
<?



// DB: DB (VPS.Net Chicago)
$array = array(
	'db' => '',
	'table' => '',
	'user' => '',
	'pass' => ''
);



// keys
define("KEY", 'base 64/compressed of $array');

//echo base64_encode(gzcompress(serialize($array)));
//print_r(unserialize(gzuncompress(base64_decode(KEY_LOCAL))));

?>
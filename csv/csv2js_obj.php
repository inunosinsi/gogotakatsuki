<?php
$filename = (isset($argv[1])) ? htmlspecialchars($argv[1], ENT_QUOTES, "UTF-8") : null;
if(isset($filename) && file_exists(dirname(__FILE__) . "/" . $filename . ".csv")){
	$lines = explode("\n", file_get_contents("./" . $filename . ".csv"));
	$js = array();
	$js[] = "var " . $filename . " = [";
	foreach($lines as $line){
		$values = explode(",", $line);
		if(!strlen($values[0])) continue;
		$js[] = "	{";
		$js[] = "		\"name\":\"" . trim(trim($values[0], "\"")) . "\",";
		//緯度軽度
		$js[] = "		\"lat\":" . trim(trim($values[2], "\"")) . ",";
		$js[] = "		\"lng\":" . trim(trim($values[3], "\"")) . ",";
		$js[] = "		\"url\":\"" . trim(trim($values[4], "\"")) . "\",";
		$js[] = "	},";
	}
	$js[] = "];";
	$js[] = "places = places.concat(" . $filename . ");";

	file_put_contents(dirname(dirname(__FILE__)) . "/js/" . $filename . ".js", implode("\n", $js));
}

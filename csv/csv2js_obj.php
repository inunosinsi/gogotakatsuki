<?php
$filename = (isset($argv[1])) ? trim(htmlspecialchars(str_replace(".csv", "", $argv[1]), ENT_QUOTES, "UTF-8")) : null;
if(isset($filename) && file_exists(dirname(__FILE__) . "/" . $filename . ".csv")){
	$lines = explode("\n", file_get_contents("./" . $filename . ".csv"));
	$js = array();
	$js[] = "if(params.k == null || (params.k != null &&params.k == \"" . $filename . "\")){";
	$js[] = "	var " . $filename . " = [";
	foreach($lines as $line){
		$values = _trimAllValue(explode(",", $line));

		//緯度経度の情報がない場合はスルー
		if(!strlen($values[0]) || !strlen($values[2]) || !strlen($values[3]) || !is_numeric($values[3])) continue;
		$js[] = "		{";
		$js[] = "			\"type\":\"Feature\",";
		$js[] = "			\"properties\":{";
		$js[] = "				\"name\":\"" . $values[0] . "\",";
		$js[] = "				\"hash\":\"" . _getHash($values[4]) . "\",";
		$js[] = "				\"category\":" . _getCategory($values[1]) . ",";
		$js[] = "			},";
		$js[] = "			\"geometry\":{";
		$js[] = "				\"type\":\"Point\",";
		$js[] = "				\"coordinates\":[" . $values[3] . "," . $values[2] . "]";
		$js[] = "			}";
		$js[] = "		},";
	}
	$js[] = "	];";
	$js[] = "	features = features.concat(" . $filename . ");";
	$js[] = "}";

	$jsDir = dirname(dirname(__FILE__)) . "/js/";
	if(!file_exists($jsDir)) {
		mkdir($jsDir);
		file_put_contents($jsDir . ".gitignore", "*.js");
	}

	file_put_contents($jsDir . $filename . ".js", implode("\n", $js));
}

function _getHash($url){
	$url = substr($url, 0, strrpos($url, "/"));
	return trim(trim(substr($url, strrpos($url, "/")), "/"));
}

function _getCategory($cat){
	if(!strlen($cat)) return 0;

	//毎回読み込む事にする
	$lines = explode("\n", file_get_contents("./category.txt"));
	foreach($lines as $idx => $line){
		if(strpos($line, "*")) continue;
		$categories = explode(",", $line);
		foreach($categories as $category){
			if($cat == trim($category)) return $idx - 2;	//category.txtの冒頭のコメント分を除いたindexを返す
		}
	}

	//カテゴリ一覧から番号を付与
	return 0;
}

function _trimAllValue($values){
	for($i = 0; $i < count($values); $i++){
		$values[$i] = trim(trim($values[$i], "\""));
	}
	return $values;
}

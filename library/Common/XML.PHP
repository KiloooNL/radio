<?php

//XML handling routines
function GetChildren($vals, &$i) {
	while ($i < count($vals)) {

		switch ($vals[$i]['type']) {
			//case 'cdata':
			case 'complete' :
				if (isset($vals[$i]['attributes'])) {
					$value = array("attributes" => $vals[$i]['attributes'],
						"value" => $vals[$i]['value']);
				} else {
					$value = array("value" => '');
					if (isset($vals[$i]['value'])) {
						$value = array("value" => $vals[$i]['value']);
					}
				}
				$name = $vals[$i]['tag'];
				$children["$name"][] = $value;

				break;

			case 'open' :
				$siblings = GetChildren($vals, ++$i);
				if (isset($vals[$i]['attributes'])) {
					$siblings['attributes'] = $vals[$i]['attributes'];
				}

				$name = $vals[$i]['tag'];
				$children["$name"][] = $siblings;

				/*
				  array_push($children,
				  array("attributes" => $vals[$i]['attributes'],
				  "children" => GetChildren($vals, ++$i))
				  );
				 */
				break;

			case 'close' :
				return $children;
				break;

			default :
				echo "XML error, Unsupported tag type: " . $vals[$i]['type'] . "<br>";
		}

		$i++;
	}

	return $children;
}

function GetXMLTree($data, $uppercase = 1) {
	$p = xml_parser_create();
	xml_parser_set_option($p, XML_OPTION_SKIP_WHITE, 1);
	xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, $uppercase);

	xml_parse_into_struct($p, $data, $vals, $index);
	xml_parser_free($p);

	$tree = array();
	$i = 0;

	$tree = GetChildren($vals, $i);

	return $tree;
}

//######################################################
//######################################################

function CompackTree($data) {

	reset($data);
	while (list($key, $value) = each($data)) {

		if (is_array($value)) {

			//SPECIAL HANDLING
			//Make Attributes the Values if value empty
			if (isset($value['attributes']))
				if (is_array($value['attributes']))
					if (!isset($value['value'])) {
						$leaf["$key"] = $value['attributes'];
						continue;
					}

			$c = count($value);

			if ($c > 1) {
				//REMOVE empty Attributes if Value is valid
				if (!((isset($value['attributes'])) && (is_array($value['attributes']))) && (isset($value['value']))) {
					$leaf["$key"] = $value['value'];
				} else {
					$leaf["$key"] = CompackTree($value);
				}
			} else {
				$temp = $value[0];
				//REMOVE empty Attributes if Value is valid
				if (isset($temp['attributes'])) {
					if (!is_array($temp['attributes']) && (isset($temp['value']))) {
						$leaf["$key"] = $temp['value'];
					} else {
						$leaf["$key"] = CompackTree($temp);
					}
				}
				else if (isset($temp['value'])) {
					$leaf["$key"] = $temp['value'];
				} else {
					$leaf["$key"] = CompackTree($temp);
				}
			}
		}
		else {
			$leaf["$key"] = $value;
		}
	}// while loop

	return $leaf;
}

function XMLToArray($data, $uppercase = 1) {
	$tree = GetXMLTree($data, $uppercase);
	return CompackTree($tree);
}

function XML2Array($data, $uppercase = 1) {
	return XMLToArray($data, $uppercase);
}

function XML2Arr($data, $uppercase = 1) {
	return XMLToArray($data, $uppercase);
}

//######################################################
//######################################################
function ChildKeyIsNum($data) {
	if (!is_array($data)) {
		return false;
	}

	reset($data);
	list($key, $value) = each($data);
	if (is_numeric($key)) {
		return true;
	} else {
		return false;
	}
}

function MakeAttr($data) {
	if (!is_array($data)) {
		return "";
	}

	$attr = " ";
	reset($data);
	while (list($key, $value) = each($data)) {
		$attr .= " $key=\"$value\"";
	}// while loop

	return $attr;
}

function ArrayToXML($data, $parent = "") {
	if (empty($parent)) {
		$xml = '<?xml version="1.0"?>';
	} else {
		$xml = "";
	}

	reset($data);
	while (list($key, $value) = each($data)) {
		$attr = "";

		if (is_numeric($key)) {
			$key = $parent;

			if (!empty($value['attributes'])) {
				$attr = MakeAttr($value['attributes']);
				unset($value['attributes']);
			}
		};

		if (ChildKeyIsNum($value)) {
			$xml .= ArrayToXML($value, $key);
		} else if (is_array($value)) {
			$xml .= "<$key$attr>";
			$xml .= ArrayToXML($value, $key);
			$xml .= "</$key$attr>";
		} else {
			$xml .= "<$key$attr>$value</$key>";
		}
	}//while

	return $xml;
}

function Array2XML($data, $parent = "") {
	return ArrayToXML($data, $parent);
}

function Arr2XML($data, $parent = "") {
	return ArrayToXML($data, $parent);
}

//######################################################
//######################################################


function Keys2Lower($a) {
	if (!is_array($a)) {
		return $a;
	}

	unset($res);

	reset($a);
	while (list($key, $value) = each($a)) {

		if (is_array($value)) {
			$res[strtolower($key)] = Keys2Lower($value);
		} else {
			$res[strtolower($key)] = $value;
		}
	} // while loop

	return $res;
}

function XMLheader() {
	header("Content-type: text/xml");
}

function EmptyNodeCleanUp(&$data) {
	while (list($key, $val) = each($data)) {
		if (is_array($val)) {
			$data[$key] = "";
		}
	}// while loop
}

function echoXML($xml) {
	XMLheader();

	if (is_array($xml)) {
		echo Array2XML($xml);
	} else {
		echo $xml;
	}
}

?>

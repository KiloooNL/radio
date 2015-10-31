<?php

function Walk($a, $pre = '-->') {
	if (!is_array($a)) {
		echo "$pre value=$a<br>";
		return;
	}

	reset($a);
	while (list($key, $value) = each($a)) {
		echo "$pre $key=$value<br>";

		if (is_array($value)) {
			Walk($value, $pre . '-->');
		}
	}// while loop
}

function StripNumericKeys($in) {
	reset($in);
	$res = Array();
	while (list($key, $val) = each($in)) {
		if (!is_numeric($key)) {
			$res[$key] = $val;
		}
	}// while loop
	return $res;
}

function DateMove(&$d, &$m, &$y, $ad, $am, $ay) {
	$stamp = mktime(0, 0, 0, $am, $ad, $ay);
	$d = date("d", $stamp);
	$m = date("m", $stamp);
	$y = date("Y", $stamp);
	return $stamp;
}

function DaysInMonth($m, $y = '') {
	if (empty($y)) {
		$y = date('Y');
	}

	$m = (int) $m;
	$days[01] = 31;
	$days[02] = 28;
	$days[03] = 31;
	$days[04] = 30;
	$days[05] = 31;
	$days[06] = 30;
	$days[07] = 31;
	$days[08] = 31;
	$days[09] = 30;
	$days[10] = 31;
	$days[11] = 30;
	$days[12] = 31;

	return $days[$m];
}

function Def($var, $def = '') {
	if (isset($_REQUEST[$var])) {
		$val = $_REQUEST[$var];
	} else {
		$val = $def;
	}

	return $val;
}

function FillData($data, $source) {
	reset($data);
	while (list($key, $val) = each($data)) {
		if ($key[0] != '_')
			ReplaceField($key, $val, $source);
	}

	ReplaceField("time", date('Y-m-d'), $source);
	ReplaceField("date", date('H:i:s'), $source);
	return $source;
}

function ReplaceField($field, $value, &$message) {
	if ($value instanceof DateTime) {
		$value = $value->format('Y-m-d H:i:s');
	}

	$message = preg_replace("/\{" . $field . "\}/i", "$value", $message);
	$message = preg_replace("/\[" . $field . "\]/i", Text2Html($value), $message);
	$message = preg_replace("/#" . $field . "#/i", urlencode($value), $message);
}

function File2Str($fname) {
	$temp = "";
	$fd = fopen($fname, "r");
	while ($buffer = fgets($fd, 4096)) {
		$temp .= $buffer;
	}
	fclose($fd);
	return $temp;
}

function Str2File($fname, $data) {
	$temp = "";
	$fd = fopen($fname, "w");
	if (!$fd) {
		echo "Error writing to $fname";
	}

	if (!fputs($fd, $data)) {
		echo "Error writing to $fname";
	}

	fclose($fd);
}

function Text2Html($txt) {
	return str_replace("\n", "\n<br>", $txt);
}

/*##########################################################################

In here is various functions to handle the data received from HTML forms
It allows for standards to accept data from any form and put it into a Database

//========================================================================== */
function DataConvert(&$data, $from, $to) {
	$from = explode(',', $from);
	$to = explode(',', $to);

	for ($k = 0; $k < count($from); $k++) {
		if ($data == $from[$k]) {
			$data = $to[$k];
			return;
		}
	}
}

//==========================================================================*/
function CheckRequiredFields(&$err, $req) {
	global $HTTP_POST_VARS;

	$err = Array();
	reset($req);
	while (list($key, $value) = each($req)) {

		//Are we testing composite required fields?
		if (gettype($value) == "array") {
			$keys = explode(",", $value["fields"]);

			unset($dummy);
			$dummy = "";
			$k = 0;
			while (($k < count($keys)) and ($dummy == "")) {
				if (empty($HTTP_POST_VARS[$keys[$k]])) {
					$dummy = $value["message"];
				}
				$k++;
			} //end while

			if (!empty($dummy)) {
				$err[$key] = $dummy;
			}
		} else { //Testing for a normal required field
			if (empty($HTTP_POST_VARS[$key])) {
				$err[$key] = $value;
			}
		}//end if
	}//end while

	if (count($err) > 0) {
		return 0;
	} else {
		return 1;
	}
}

//==========================================================================

function CreateSQLQuery($form, &$fields, &$values, $mode) {
	//Set Database insert/update mode
	$fields = "";
	$values = "";

	reset($form);
	while (list($key, $val) = each($form)) {
		if ($key[0] != '_') {
			AddKey($key, $val, $fields, $values, $mode, "auto");
		}
	}
}

//================== ##

/*##########################################################################

Various Form output functions

//========================================================================== */

function FormatDefData($name, &$data, $def) {
	if (gettype($data) == "array") {
		$data = $data["$name"];
	}

	if (empty($data)) {
		$data = $def;
	}
}

//==========================================================================
function InputHidden($name, $data, $def = "") {
	FormatDefData($name, $data, $def);
	echo '<input type="hidden" name="' . $name . '" value="' . htmlentities($data) . '">';
}

//==========================================================================
function InputCheckBox($name, $data, $def="", $val = "") {
	if (empty($val)) {
		$val = $name;
	}
	FormatDefData($name, $data, $def);
	echo '<input type="checkbox" name="' . $name . '" value="' . $val . '" ' . ($val == $data) ? 'checked="checked"' : '' . '>';
}

//==========================================================================
function InputRadio($name, $data, $def = "", $val = "") {
	if (empty($val)) {
		$val = $name;
	}
	FormatDefData($name, $data, $def);
	echo '<input type="radio" name="' . $name . '" value="' . $val . '" ' . ($val == $data) ? 'checked="checked"' : '' . '>';
}

//==========================================================================
function InputTextArea($name, $data, $def = "", $rows = 3, $cols = 20) {
	FormatDefData($name, $data, $def);
	echo '<textarea rows="' . $rows . '" name="' . $name . '" cols="' . $cols . '">' . htmlentities($data) . '</textarea>';
}

//==========================================================================
function InputText($name, $data, $def = "", $size = 20) {
	FormatDefData($name, $data, $def);
	echo '<input type="text" name="' . $name . '" size="' . $size . '" value="' . htmlentities($data) . '">';
}

//==========================================================================
function InputPassword($name, $data, $def = "", $size = 20) {
	FormatDefData($name, $data, $def);
	echo '<input type="password" name="' . $name . '" size="' . $size . '" value="' . htmlentities($data) . '">';
}

//==========================================================================
function InputFile($name, $data, $def = "", $size = 20, $accept = "") {
	FormatDefData($name, $data, $def);
	if (!empty($accept)) {
		$accept = 'ACCEPT="' . $accept . '"';
	}
	echo '<input type="file" name="' . $name . '" size="' . $size . '" value="' . htmlentities($data) . '"' . $accept . '>';
}

//==========================================================================
function InputCombo($name, $data, $def, $labels, $values = "", $onchange = "") {
	FormatDefData($name, $data, $def);

	if (empty($values)) {
		$values = $labels;
	}

	$labels = explode(',', $labels);
	$values = explode(',', $values);

	if (!empty($onchange)) {
		$onchange = 'onchange="' . $onchange . '"';
	}

	echo '<select name="' . $name . '" size="1" ' . $onchange . '>';

	for ($k = 0; $k < count($labels); $k++) {
		echo '<option value="' . $values[$k] . '" ' . (($values[$k] == $data) ? 'selected="selected"' : '') . '>';
		echo $labels[$k];
		echo '</option>';
	}
	echo '</select>';
}

//==========================================================================
function InputComboDB($name, $data, $def, $res) {
	FormatDefData($name, $data, $def);
	echo '<select name="' . $name . '" size="1">';

	while ($row = mysql_fetch_array($res)) {
		echo '<option value="' . $row["value"] . '" ' . ($row["value"] == $data) ? 'selected="selected"' : '' . '>';
		echo $row["label"];
		echo '</option>';
	}
	echo '</select>';
}

function InputButton($name = 'Go', $data = '', $type = 'submit') {
	FormatDefData($name, $data, $name);
	echo "<input type='$type' value='$data' name='$name'>";
}

//==========================================================================*/
function InputDay($d, $name) {
	FormatDefData($name, $d, date('d'));
	$d = (int) $d;

	echo "<select NAME='$name' size='1'>\n";
	for ($k = 1; $k <= 31; $k++) {

		if ($d == $k) {
			$selected = "selected";
		} else {
			$selected = "";
		}

		echo "<option value='$k' $selected>$k</option> \n";
	}
	echo "</select>";
}

function InputMonth($m, $name) {
	FormatDefData($name, $m, date('m'));
	$m = (int) $m;

	echo "<select NAME='$name' size='1'>\n";
	for ($k = 1; $k <= 12; $k++) {

		$stamp = mktime(0, 0, 0, $k, 1, date("Y"));
		$mm = date("M", $stamp);
		if ($m == $k) {
			$selected = "selected";
		} else {
			$selected = "";
		}

		echo "<option value='$k' $selected>$mm</option> \n";
	}
	echo "</select>";
}

function InputYear($y, $name) {
	FormatDefData($name, $y, date('Y'));
	$y = (int) $y;

	echo "<select NAME='$name' size='1'>\n";
	for ($k = 2001; $k <= date("Y"); $k++) {

		if ($y == $k) {
			$selected = "selected";
		} else {
			$selected = "";
		}

		echo "<option value='$k' $selected>$k</option> \n";
	}
	echo "</select>";
}

function InputDate($f, $fieldname) {

	$months[1] = "Jan";
	$months[2] = "Feb";
	$months[3] = "Mar";
	$months[4] = "Apr";
	$months[5] = "May";
	$months[6] = "Jun";
	$months[7] = "Jul";
	$months[8] = "Aug";
	$months[9] = "Sep";
	$months[10] = "Oct";
	$months[11] = "Nov";
	$months[12] = "Dec";

	if (empty($f['d'])) {
		$f['d'] = date("d");
		$f['m'] = date("m");
		$f['y'] = date("Y");
	};


	echo '<select NAME="' . $fieldname . '[m]" size="1">';
//======= FROM month ======
	for ($k = 1; $k <= 12; $k++) {
		echo '<option value="' . $k . '"' . ($f[m] == $k) ? 'selected="selected"' : '' . '>' . $months[$k] . '</option>';
	};
//=====================
	echo '</select>';

	echo '<select NAME="' . $fieldname . '[d]" size="1">';
//======= FROM Day ======
	for ($k = 1; $k <= 31; $k++) {
		echo '<option value="' . $k . '"' . ($f[d] == $k) ? 'selected="selected"' : '' . '>' . $k . '</option>';
	};
//=====================

	echo '</select>';
	echo '<select NAME="' . $fieldname . '[y]" size="1">';

//======= FROM year ======
	for ($k = 2001; $k <= date("Y"); $k++) {
		echo '<option value="' . $k . '"' . ($f[y] == $k) ? 'selected="selected"' : '' . '>' . $k . '</option>';
	};
//=====================
	echo '</select>';
}

//==========================================================================*/
function AddKey($field, $value, &$fields, &$values, $DBmode, $type = "string") {
	if ($type == "auto") {
		if (strtoupper($value) == "NULL") {
			$type = "NULL";
		} else {
			$type = gettype($value);
		}
	}

	switch ($type) {
		case "NULL"		:
		case "null"		:
		case "double"	:
		case "integer"	:
		case "integer"	:
			$doStr = "";
			break;
		case "string" :
		default		  :
			$value = addslashes($value);
			$doStr = "'";
	}

	$DBmode = strtoupper($DBmode);

	if ($DBmode == "UPDATE") {
		if (!empty($values)) {
			$values .= ",";
		}
		$values .= $field . " = $doStr" . $value . "$doStr ";
	}
	else {

		if (!empty($fields)) {
			$fields .= ",";
		}

		if (!empty($values)) {
			$values .= ",";
		}

		$fields .= $field;
		$values .= "$doStr" . $value . "$doStr ";
	}
}

//========================= --[ END ]-- ====================================
?>
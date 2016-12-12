<?

class validator {

    var $validatorId;
    var $valid = false;
    var $duplicate = false;
    var $errors = array();
    var $request = array();

    function Validator($requestArray) {
        //get a unique 3 number id
        $id = uniqid("");
        $id = ereg_replace("[[:alpha:]]", "", $id);
        $id = strrev($id);
        $id = substr($id, 0, 3);
        if($id{0} == 0){
            $id = strrev($id);
        }
        $this->validatorId = $id;
        $this->request = $requestArray;
    }

    //check if a field or fields are filled in
    //ERROR: 100
    function filledIn($field = null) {
        if(is_array($field)) {
            foreach ($field as $key => $value){
                if(array_key_exists($value, $this->request) && $this->request[$value] != "") {
                } elseif($this->request[$value] === 0) {
                } else {
                    $this->setError($value, 100);
                }
            }
            foreach ($this->errors as $key => $value){
                if($value == 100) {
                    $this->valid = false;
                }
            }
            if($this->valid) {
                $this->resetValid();
                return true;
            } else {
                $this->resetValid();
                return false;
            }
        } elseif ($field == null){
            foreach ($this->request as $key => $value) {
                if(array_key_exists($value, $this->request) && $this->request[$value] != "") {
                } elseif($this->request[$value] === 0) {
                } else {
                    $this->setError($value, 100);
                }
            }
            foreach ($this->errors as $key => $value){
                if($value == 100) {
                    $this->valid = false;
                }
            }
            if($this->valid) {
                $this->resetValid();
                return true;
            } else {
                $this->resetValid();
                return false;
            }
        }  else {
            if(array_key_exists($field, $this->request) && $this->request[$field] != "") {
                return true;
            } elseif ($this->request[$field] === 0) {
                return true;
            } else {
                $this->setError($field, 100);
                return false;
            }
        }
    }

    //length functions on a field takes <, >, =, <=, and >= as operators
    //ERROR: 101
    function length($field, $operator, $length) {
        switch($operator) {
            case "<":
                if(strlen(trim($this->request[$field])) < $length) {
                    return true;
                } else {
                    $this->setError($field, 101);
                    return false;
                }
                break;
            case ">":
                if(strlen(trim($this->request[$field])) > $length) {
                    return true;
                } else {
                    $this->setError($field, 101);
                    return false;
                }
                break;
            case "=":
                if(strlen(trim($this->request[$field])) == $length) {
                    return true;
                } else {
                    $this->setError($field, 101);
                    return false;
                }
                break;
            case "<=":
                if(strlen(trim($this->request[$field])) <= $length) {
                    return true;
                } else {
                    $this->setError($field, 101);
                    return false;
                }
                break;
            case ">=":
                if(strlen(trim($this->request[$field])) >= $length) {
                    return true;
                } else {
                    $this->setError($field, 101);
                    return false;
                }
                break;
            default:
                if(strlen(trim($this->request[$field])) < $length) {
                    return true;
                } else {
                    $this->setError($field, 101);
                    return false;
                }
        }
    }

    //check to see if valid email address
    //ERROR: 102
    function email($field) {
        $address = trim($this->request[$field]);
        if (ereg('^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$', $address)){
            return true;
        }	else {
            $this->setError($field, 102);
            return false;
        }
    }

    //check to see if two fields are equal
    //ERROR: 103
    function compare($field1, $field2, $caseInsensitive = false) {
        if($caseInsensitive) {
            if (strcmp(strtolower($this->request[$field1]), strtolower($this->request[$field2])) == 0) {
                return true;
            } else {
                $this->setError($field1."|".$field2, 103);
                return false;
            }
        } else {
            if (strcmp($this->request[$field1], $this->request[$field2]) == 0) {
                return true;
            } else {
                $this->setError($field1."|".$field2, 103);
                return false;
            }
        }
    }

    //check to see if the length of a field is between two numbers
    //ERROR: 104
    function lengthBetween($field, $max, $min, $inclusive = false){
        if(!$inclusive){
            if(strlen(trim($this->request[$field])) < $max && strlen(trim($this->request[$field])) > $min) {
                return true;
            } else {
                $this->setError($field, 104);
                return false;
            }
        } else {
            if(strlen(trim($this->request[$field])) <= $max && strlen(trim($this->request[$field])) >= $min) {
                return true;
            } else {
                $this->setError($field, 104);
                return false;
            }
        }
    }

    //check to see if there is punctuation
    //ERROR: 105
    function punctuation($field = null) {
        if(is_array($field)) {
            foreach ($field as $key => $value){
                if(ereg("[[:punct:]]", $this->request[$value])) {
                    $this->setError($value, 105);
                }
            }
            foreach ($this->errors as $key => $value){
                if($value == 105) {
                    $this->valid = false;
                }
            }
            if($this->valid) {
                $this->resetValid();
                return true;
            } else {
                $this->resetValid();
                return false;
            }
        } elseif ($field == null){
            foreach ($this->request as $key => $value) {
                if(ereg("[[:punct:]]", $value)) {
                    $this->setError($key, 105);
                }
            }
            foreach ($this->errors as $key => $value){
                if($value == 105) {
                    $this->valid = false;
                }
            }
            if($this->valid) {
                $this->resetValid();
                return true;
            } else {
                $this->resetValid();
                return false;
            }
        } else {
            if(ereg("[[:punct:]]", $this->request[$field])) {
                $this->setError($field, 105);
                return false;
            } else {
                return true;
            }
        }
    }

    //number value functions takes <, >, =, <=, and >= as operators
    //ERROR: 106
    function value($field, $operator, $length) {
        switch($operator) {
            case "<":
                if($this->request[$field] < $length) {
                    return true;
                } else {
                    $this->setError($field, 106);
                    return false;
                }
                break;
            case ">":
                if($this->request[$field] > $length) {
                    return true;
                } else {
                    $this->setError($field, 106);
                    return false;
                }
                break;
            case "=":
                if($this->request[$field] == $length) {
                    return true;
                } else {
                    $this->setError($field, 106);
                    return false;
                }
                break;
            case "<=":
                if($this->request[$field] <= $length) {
                    return true;
                } else {
                    $this->setError($field, 106);
                    return false;
                }
                break;
            case ">=":
                if($this->request[$field] >= $length) {
                    return true;
                } else {
                    $this->setError($field, 106);
                    return false;
                }
                break;
            default:
                if($this->request[$field] < $length) {
                    return true;
                } else {
                    $this->setError($field, 106);
                    return false;
                }
        }
    }

    //check if a number value is between $max and $min
    //ERROR: 107
    function valueBetween($field, $max, $min, $inclusive = false){
        if(!$inclusive){
            if($this->request[$field] < $max && $this->request[$field] > $min) {
                return true;
            } else {
                $this->setError($field, 107);
                return false;
            }
        } else {
            if($this->request[$field] <= $max && $this->request[$field] >= $min) {
                return true;
            } else {
                $this->setError($field, 107);
                return false;
            }
        }
    }

    //check if a field contains only alphabetic characters
    //ERROR: 108
    function alpha($field = null) {
        if(is_array($field)) {
            foreach ($field as $key => $value){
                $strlen = strlen($this->request[$value]);
                if($strlen > 0) {
                    if(!ereg("[[:alpha:]]\{$strlen}", $this->request[$value])) {
                        $this->setError($value, 108);
                    }
                }
            }
            foreach ($this->errors as $key => $value){
                if($value == 108) {
                    $this->valid = false;
                }
            }
            if($this->valid) {
                $this->resetValid();
                return true;
            } else {
                $this->resetValid();
                return false;
            }
        } elseif ($field == null) {
            foreach ($this->request as $key => $value) {
                $strlen = strlen($value);
                if($strlen > 0) {
                    if(!ereg("[[:alpha:]]\{$strlen}", $value)) {
                        $this->setError($key, 108);
                    }
                }
            }
            foreach ($this->errors as $key => $value){
                if($value == 108) {
                    $this->valid = false;
                }
            }
            if($this->valid) {
                $this->resetValid();
                return true;
            } else {
                $this->resetValid();
                return false;
            }
        } else {
            $strlen = strlen($this->request[$field]);
            if($strlen > 0) {
                if(ereg("[[:alpha:]]\{$strlen}", $this->request[$field])) {
                    return true;
                } else {
                    $this->setError($field, 108);
                    return false;
                }
            }
        }
    }

    //check if a field contains only alphanumeric characters
    //ERROR: 109
    function alphaNumeric($field = null) {
        if(is_array($field)) {
            foreach ($field as $key => $value){
                $strlen = strlen($this->request[$value]);
                if($strlen > 0) {
                    if(!ereg("[[:alnum:]]\{$strlen}", $this->request[$value])) {
                        $this->setError($value, 109);
                    }
                }
            }
            foreach ($this->errors as $key => $value){
                if($value == 109) {
                    $this->valid = false;
                }
            }
            if($this->valid) {
                $this->resetValid();
                return true;
            } else {
                $this->resetValid();
                return false;
            }
        } elseif ($field == null) {
            foreach ($this->request as $key => $value) {
                $strlen = strlen($value);
                if($strlen > 0) {
                    if(!ereg("[[:alnum:]]\{$strlen}", $value)) {
                        $this->setError($key, 109);
                    }
                }
            }
            foreach ($this->errors as $key => $value){
                if($value == 109) {
                    $this->valid = false;
                }
            }
            if($this->valid) {
                $this->resetValid();
                return true;
            } else {
                $this->resetValid();
                return false;
            }
        } else {
            $strlen = strlen($this->request[$field]);
            if($strlen > 0) {
                if(ereg("[[:alnum:]]\{$strlen}", $this->request[$field])) {
                    return true;
                } else {
                    $this->setError($field, 109);
                    return false;
                }
            }
        }
    }

    //check if field is a date by specified format
    //acceptable separators are "/" "." "-"
    //acceptable formats use "m" for month, "d" for day, "y" for year
    //eg: date("date", "mm.dd.yyyy") will match a field called "date" containing 01-12.01-31.nnnn where n is any real number
    //ERROR: 110
    function date($field, $format) {
        $month = false;
        $day = false;
        $year = false;
        $monthPos = null;
        $dayPos = null;
        $yearPos = null;
        $monthNum = null;
        $dayNum = null;
        $yearNum = null;
        $separator = null;
        $separatorCount = null;
        $fieldSeparatorCount = null;
        $formatArray = array();
        $dateArray = array();

        //determine the separator
        if(strstr($format, "-")) {
            $separator = "-";
            $this->valid = true;
        } elseif (strstr($format, ".")) {
            $separator = ".";
            $this->valid = true;
        } elseif (strstr($format, "/")) {
            $separator = "/";
            $this->valid = true;
        }	else {
            $this->valid = false;
        }

        if($this->valid){
            //determine the number of separators in $format and $field
            $separatorCount = substr_count($format, $separator);
            $fieldSeparatorCount = substr_count($this->request[$field], $separator);

            //if number of separators in $format and $field don't match return false
            if(!strstr($this->request[$field], $separator) || $fieldSeparatorCount != $separatorCount) {
                $this->valid = false;
            } else {
                $this->valid = true;
            }
        }

        if($this->valid) {
            //explode $format into $formatArray and get the index of the day, month, and year
            //then get the number of occurances of either m, d, or y
            $formatArray = explode($separator, $format);
            for($i = 0; $i < sizeof($formatArray); $i++) {
                if(strstr($formatArray[$i], "m")) {
                    $monthPos = $i;
                    $monthNum = substr_count($formatArray[$i], "m");
                } elseif (strstr($formatArray[$i], "d")) {
                    $dayPos = $i;
                    $dayNum = substr_count($formatArray[$i], "d");
                } elseif (strstr($formatArray[$i], "y")) {
                    $yearPos = $i;
                    $yearNum = substr_count($formatArray[$i], "y");
                } else {
                    $this->valid = false;
                }
            }

            //set whether $format uses day, month, year
            if($monthNum) {
                $month = true;
            } else {
                $month = false;
            }
            if($dayNum) {
                $day = true;
            } else {
                $day = false;
            }
            if($yearNum) {
                $year = true;
            } else {
                $year = false;
            }

            //explode date field into $dateArray
            //check if the monthNum, dayNum, and yearNum match appropriately to the $dateArray
            $dateArray = explode($separator, $this->request[$field]);
            if($month) {
                if(!ereg("^[0-9]\{$monthNum}$", $dateArray[$monthPos]) || $dateArray[$monthPos] > 12) {
                    $this->valid = false;
                }
            }
            if($day) {
                if(!ereg("^[0-9]\{$dayNum}$", $dateArray[$dayPos]) || $dateArray[$dayPos] > 31) {
                    $this->valid = false;
                }
            }
            if($year) {
                if (!ereg("^[0-9]\{$yearNum}$", $dateArray[$yearPos])) {
                    $this->valid = false;
                }
            }
        }

        if ($this->valid) {
            $this->resetValid();
            return true;
        } else {
            $this->resetValid();
            $this->setError($field, 110);
            return false;
        }
    }

    //set errors here
    function setError($field, $error) {
        if(!key_exists($field, $this->errors) || $this->errors[$field] !== $error && !is_array($this->errors[$field])) {
            $tmpArray = array($field => $error);
            $this->errors = array_merge_recursive($this->errors, $tmpArray);
            return true;
        } elseif(is_array($this->errors[$field])) {
            foreach ($this->errors[$field] as $value) {
                if($value == $error) {
                    $this->duplicate = true;
                } else {
                    $this->duplicate = false;
                }
            }
            if(!$this->duplicate){
                $tmpArray = array($field => $error);
                $this->errors = array_merge_recursive($this->errors, $tmpArray);
            }
        } else {
            $this->duplicate = false;
        }
    }

    //get validation errors
    function getErrors() {
        return $this->errors;
    }

    //get the validator id
    function getId() {
        return $this->validatorId;
    }

    //resets $valid to false
    function resetValid() {
        $this->valid = false;
    }
}
?>
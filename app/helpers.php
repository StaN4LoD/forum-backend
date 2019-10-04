<?php
// composer dump-autoload сделать чтобы работало
if (! function_exists('value_validation')) {
    /*
     * @array
     */
    function value_validation ($array)  {
        foreach($array as $key => $value) {
            $value = trim($value);
            $value = stripslashes($value);
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            $array[$key] = $value;
        }
        return $array;
    }
}
?>

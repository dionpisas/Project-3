<?php
function hashgenerator(){
    $string = "abcdefghijklmnopqrstuvwxyz0123456789";
    $hash = "";
    for($i = 1; $i <= 25; $i++){
        $hash .= substr($string, rand(1, strlen($string)), 1);
    }
    return $hash;
}
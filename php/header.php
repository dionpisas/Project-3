<?php
// Hier laad ik de header.html in
$header = new TemplatePower("template/files/header.tpl");
$header->prepare();


if(isset($_SESSION['accountid'])){
    $header->newBlock("LOGGEDIN");
    $header->assign("USERNAME", $_SESSION['username']);
}else{
    $header->newBlock("LOGINTOP");
}



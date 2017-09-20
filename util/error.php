<?php
function send_error($res, $message, $error_code){
    if(!isset($error_code)){
        $error_code = 400;
    }
    return $res->withJSON(array("error"=>$message))->withStatus($error_code);
}
<?php

class Helper {

    public static function checkRequiredParameters($requiredParams, $providedBody) {
        foreach ($requiredParams as $requiredParam) {
            if(!isset($providedBody[$requiredParam]))
                throw new Exception("Missing required parameter: ".$requiredParam, 400);
        }
    }

}
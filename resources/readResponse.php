<?php

namespace resources;

class readResponse
{
    public function cardpayment($response)
    {
        if ($response != null) {
            $operationsArray = $response['response']->{'operations'};
            if (count($operationsArray) != 0) {
                if (array_search('redirect-authorization', array_column($operationsArray, 'rel')) == true) {
                    return "customer not yet paid";
                } elseif (array_search('redirect-authorization', array_column($operationsArray, 'rel')) == true) {
                    return "";
                }
            }
        } else {
            return "Empty";
        }
    }
}

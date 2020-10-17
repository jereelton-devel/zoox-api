<?php

namespace ZooxTest;

class ZooxTestRequest
{
    private static function requestDecoder($data)
    {
        $decoderResult = true;
        $tmpJsonCheck = json_decode($data, true);

        if (json_last_error() == 0) {

            $decoderResult = true;

        } else {

            switch (json_last_error()) {

                case JSON_ERROR_DEPTH:
                    $decoderResult = 'JSON/Parse/Error: Maximum depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $decoderResult = 'JSON/Parse/Error: State mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $decoderResult = 'JSON/Parse/Error: Control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $decoderResult = 'JSON/Parse/Error: String JSON mal-formada!';
                    break;
                case JSON_ERROR_UTF8:
                    $decoderResult = 'JSON/Parse/Error: Encoding error UTF-8';
                    break;
                default:
                    $decoderResult = 'JSON/Parse/Error: Unknown error';
                    break;
            }
        }

        return $decoderResult;
    }

    private static function requestReturn($data)
    {
        $tmpJsonCheck = json_decode($data, true);

        return $tmpJsonCheck;
    }

    public static function getJsonRequest($data)
    {
        $decoder = ZooxTestRequest::requestDecoder($data);

        if($decoder == true) {

            $decoder = ZooxTestRequest::requestReturn($data);

            return $decoder;

        } else {

            return ['msgError' => $decoder];

        }
    }

}

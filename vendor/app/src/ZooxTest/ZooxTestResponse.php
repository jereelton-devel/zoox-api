<?php

namespace ZooxTest;

class ZooxTestResponse
{
    private static function setExpiredPage()
    {
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() - 3600) . ' GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Pragma: no-cache');
    }

    private static function setContentType($content_type)
    {
        header("Content-Type: {$content_type}");
    }

    public static function setResponse($data, $content_type)
    {
        ZooxTestResponse::setExpiredPage();
        ZooxTestResponse::setContentType($content_type);

        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}

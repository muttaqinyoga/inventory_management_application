<?php
    require_once '../vendor/ramsey_uuid/vendor/autoload.php';
    function response($data, $status_code = 200)
    {
        $statuses = [
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            206 => 'Partial Content',

            301 => 'Moved Permanently',
            302 => 'Found',

            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            413 => 'Payload Too Large',
            415 => 'Unsupported Media Type',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
        ];
        header("{$_SERVER['SERVER_PROTOCOL']} {$status_code} {$statuses[$status_code]}");
        header('Content-Type: application/json');
        echo $data === null ? null : json_encode($data);
    }
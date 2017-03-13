<?php
return [
    'http-client' => function() {
        return new GuzzleHttp\Client();
    }
];
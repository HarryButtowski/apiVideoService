<?php
require_once '../vendor/autoload.php';

use HarryButtowski\VideoService\Source\IframeSource;
use HarryButtowski\VideoService\Source\UrlSource;
use HarryButtowski\VideoService\VideoService;

//TODO delete block
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);


$data       = [];
$attributes = ['title', 'description', 'url_preview', 'iframe'];

$url    = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
$iframe = filter_input(INPUT_GET, 'iframe');

try {
    switch (true) {
        case $url:
            $source = new UrlSource($url);

            break;
        case $iframe:
            $source = new IframeSource($iframe);

            break;
        default:
            throw new Exception('Source is not defined', 404);

            break;
    }

    $videoService = new VideoService($source, $attributes);

    sendResponse([
        'status' => 200,
        'data'   => $videoService->getDataVideo(),
    ]);
} catch (Exception $e) {
    sendResponse([
        'status' => $e->getCode(),
        'error'  => $e->getMessage(),
    ]);
}

/**
 * @param array $data
 */
function sendResponse(array $data)
{
    die(json_encode($data));
}
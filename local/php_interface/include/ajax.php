<?php use JetBrains\PhpStorm\NoReturn;

#[NoReturn] function outputError(string $message = ''): void
{
    http_response_code(400);
    header('Content-Type: application/json; charset=utf-8');
    die( json_encode(array('status' => 'error', 'message' => $message)) );
}

#[NoReturn] function outputSuccess(): void
{
    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    die( json_encode(array('status' => 'success')) );
}
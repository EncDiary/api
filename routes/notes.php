<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/notes', function (Request $request, Response $response, $args) {
  $response->getBody()->write("Notes");
  return $response;
});

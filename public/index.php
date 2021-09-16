<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/db.php';


$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
  $response->getBody()->write("Hello world!");
  return $response;
});

$app->get('/book/{id}', function (Request $request, Response $response, $args) {
  $response->getBody()->write("Book number: " . $args['id']);
  return $response;
});

require __DIR__ . '/../routes/books.php';
require __DIR__ . '/../routes/notes.php';


$app->run();

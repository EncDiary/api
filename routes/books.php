<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


$app->get('/books', function (Request $request, Response $response) {
  $sql = "SELECT * FROM books";
  try {
    $db = new DB();
    $conn = $db->connect();

    $stmt = $conn->query($sql);
    $books = $stmt->fetchAll(PDO::FETCH_OBJ);

    $db = null;
    $response->getBody()->write(json_encode($books));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(200);
  } catch (PDOException $e) {
    $error = array(
      'message' => $e->getMessage()
    );

    $response->getBody()->write(json_encode($error));
    return $response
      ->withHeader('content-type', 'application/json')
      ->withStatus(500);
  }
});

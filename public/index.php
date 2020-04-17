<?php
include __DIR__ . '/../src/route.php';
include __DIR__ . '/../src/whoami.php';
include __DIR__ . '/../src/s.php';

use sailboats\route; // https://github.com/steampixel/simplePHPRouter
use sailboats\whoami;
use sailboats\sharedBoard;

route::add('/', function() {
  echo 'index but who am i?';
});

route::add('/whoami', function() {
  $obj = new whoami();
  $obj->get();
});

route::add('/s', function() {
  $obj = new sharedBoard();
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['content'])) {
      header("HTTP/1.1 204 No Content", TRUE, 204);
      exit;
    }
    else {
      $obj->post($_POST['replyTo'], $_POST['content']);
    }
  }
  else {
    $obj->get();
  }
}, ['post','get']);

route::pathNotFound(function($path) {
  header('HTTP/1.1 404 Not Found', TRUE, 404);
  echo 'The requested path "'.$path.'" was not found!';
});

route::methodNotAllowed(function($path, $method) {
  header('HTTP/1.1 405 Method Not Allowed', TRUE, 405);
  echo 'The requested path "'.$path.'" exists. But the request method "'.$method.'" is not allowed on this path!';
});

route::run('/');

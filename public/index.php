<?php
include __DIR__ . '/../src/route.php';
include __DIR__ . '/../src/whoami.php';

use sailboats\route;
use sailboats\whoami;

route::add('/', function() {
  echo 'index but who am i?';
});

route::add('/whoami', function() {
  $who_obj = new whoami();
  $who_obj->get();
});

route::pathNotFound(function($path) {
  header('HTTP/1.1 404 Not Found', TRUE, 404);
  echo 'The requested path "'.$path.'" was not found!';
});

route::methodNotAllowed(function($path, $method) {
  header('HTTP/1.1 405 Method Not Allowed', TRUE, 405);
  echo 'The requested path "'.$path.'" exists. But the request method "'.$method.'" is not allowed on this path!';
});

route::run('/');

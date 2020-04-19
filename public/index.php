<?php
include __DIR__ . '/../src/route.php';
include __DIR__ . '/../src/whoami.php';
include __DIR__ . '/../src/s.php';
include __DIR__ . '/../src/boards.php';
include __DIR__ . '/../src/frontend_index.php';
include __DIR__ . '/../src/login.php';
include __DIR__ . '/../src/resource.php';

use sailboats\route; // https://github.com/steampixel/simplePHPRouter
use sailboats\whoami;
use sailboats\sharedBoard; // https://github.com/sailboat-anon/sailboatland
use sailboats\boards; // https://github.com/cyberland-digital/cyberland-protocol/blob/master/protocol.md
use sailboats\frontend;
use sailboats\login_obj; // https://github.com/firebase/php-jwt
use sailboats\secretResource;

route::add('/', function() {
  $obj = new frontend();
  $obj->get();
});

route::add('/resource', function () {
  $obj = new secretResource();
  $token = $obj->getToken();
  $valid_token = $obj->validateToken($token);
});

route::add('/login', function() {
  $obj = new login_obj();
  $obj->get();
});

route::add('/boards', function() {
  $obj = new boards();
  $obj->get();
});

route::add('/whoami', function() {
  $obj = new whoami();
  $obj->get();
});

route::add('/s', function() {
  $sb = new sharedBoard();
  $sb->get($_GET['replyTo'], $_GET['num']);
});

route::add('/s', function() {
  $obj = new sharedBoard();
  $obj->post($_POST['replyTo'], $_POST['content']);
}, 'post');

route::pathNotFound(function() {
  header('HTTP/1.1 404 Not Found', TRUE, 404);
});

route::methodNotAllowed(function() {
  header('HTTP/1.1 405 Method Not Allowed', TRUE, 405);
});

route::run('/');

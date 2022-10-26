<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;
use UMA\DIC\Container;


require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/../config/settings.php';
$container = new Container($settings);

AppFactory::setContainer($container);
$app = AppFactory::create();

$container->set('test','testcont');
$container = $app->getContainer();
#echo $container->get('test');

$container->set('logger', function ($c) {
        $logger = new \Monolog\Logger('my_logger');
        $file_handle = new Monolog\Handler\StreamHandler('../logs/app.log');
        $logger->pushHandler($file_handle);
        return $logger;
    }
);

#var_dump($container->get('logger'));

$container->set('view', new \Slim\Views\PhpRenderer('../templates/'));

$container->set('db', function (Container $c) {
    $db = $c->get('settings')['db'];
    $dsn = "pgsql:host={$db['host']};port=5432;dbname={$db['name']};";

    // make a database connection
    $pdo = new PDO(
        $dsn,
        $db['user'],
        $db['password']
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}
);

var_dump($container->get('db'));

$app->get('/ticket/{id}', function(Request $request, Response $response, $args) {
    $ticket_id = (int)$args['id'];
    $mapper = new TicketMapper($this->db);
    $ticket = $mapper->getTicketById($ticket_id);

    $response->getBody()->write(var_export($ticket, true));
    return $response;
})->setName('ticket-detail');

/*$app->post('/ticket/new', function (Request $request, Response $response) {
   $data = $request->getParsedBody();
   $ticket_data = [];
   $ticket_data['title'] = filter_var($data['title'], FILTER_SANITIZE_STRING);
   $ticket_data['description'] = filter_var($data['description'], FILTER_SANITIZE_STRING);
});*/



$app->get('/tickets', function (Request $request, Response $response){
    $this->logger->addInfo('Ticket list');
    $mapper = new  TicketMapper($this->db);
    $tickets = $mapper->getTickets();

    $response = $this->view->render($response, 'tickets.phtml', ['tickets' => $tickets, 'router' => $this->router]);
    return $response;
});

//$app->get('/hello/{name}', function (Request $request, Response $response, array $args){
//    $name = $args['name'];
//    $response->getBody()->write("Hello, $name");
//    return $response;
//});
//
//$app->run();

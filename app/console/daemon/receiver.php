<?php

require __DIR__ . '/../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

$connection = new AMQPStreamConnection(
    'rabbitmq',
    5672,
    'rabbitmq',
    'rabbitmq'
);
$channel = $connection->channel();

$channel->queue_declare(
    'email',
    false,
    false,
    false,
    false
);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg){
    echo ' [x] Received ', $msg->body, "\n";
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = "smtp.gmail.com";                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = "lubsan14091998@gmail.com";             //SMTP username
        $mail->Password   = "vvecpkpgcktxifsh";                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        //Recipients
        $mail->setFrom('lubsan14091998@gmail.com', 'Lubsan');
        $mail->addAddress($msg->body, 'Rabbit');     //Add a recipient

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Test send RabbitMQ';
        $mail->Body = 'Hello from Tomsk';

        $mail->send();
        echo 'Message has been sent';

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
};

$channel->basic_consume(
    'email',
    '',
    false,
    true,
    false,
    false,
    $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
<?php

if($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = strip_tags(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    $recipient = $_POST['recipient'];
    $subject = $_POST['subject'];

    echo "Mensagem: ";

    if(empty($name) || empty($email)) {
        http_response_code(400);
        echo "Preencha todos os campos.";
        exit;
    }

    $message = "Nome: $name\n";
    $message .="Email: $email\n";
    
    $headers ="From: $name <$email>\n";

    if(mail($recipient, $subject, $message, $headers)) {
        http_response_code(200);
        echo "Está registado nas newsletter";
    } else {
        http_response_code(500);
        echo "Ocorreu um erro";
    }
} else {
    http_response_code(403);
    echo "Ocorreu um erro";
}
<?php
//identificação para a chamada da classe

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once "PHPMailer/src/PHPMailer.php";
require_once "PHPMailer/src/SMTP.php";

function enviaEmail($email, $mensagem, $assunto, $email_resposta=null){

    // instanciando a classe
    $mail = new PHPMailer();

    // habilitando SMTP	
    $mail->isSMTP();

    // habilitando tranferêcia segura 
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    // Pode ser: 0 = não exibe erros, 1 = exibe erros e mensagens, 2 = apenas mensagens	

    $mail->SMTPDebug = 0; // Debug

    // habilitando autenticação	
    $mail->SMTPAuth = true;

    // Configurações para utilização do SMTP do Gmail 

    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 587; // porta gmail
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ]
    ];

    $mail->Username = 'dawexemplo2014@gmail.com'; ////Usuário para autenticação 
    $mail->Password = 'tcycdlrjmtbngpie'; //senha autenticação

    // Remetente da mensagem - sempre usar o mesmo usuário da autenticação  
    $mail->setFrom('dawexemplo2014@gmail.com','Adm Site');

    // Endereço de destino do email
    $mail->addAddress($email);

    $mail->CharSet = "utf-8";

    // Assunto e Corpo do email
    $mail->Subject = $assunto;

    $mail->Body = $mensagem;

    $mail->isHTML(true);

    // Enviando o email
    if (!$mail->send()) {
        $retorno=false;
    } else {
        $retorno = $mensagem;
    }   
    return $retorno;
}


?>
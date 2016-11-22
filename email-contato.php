<?php
$Nome		= $_POST["nome"];	// Pega o valor do campo Nome
$Email		= $_POST["email"];	// Pega o valor do campo Email
$DDDTelefone		= $_POST["ddd-telefone"];	// Pega o valor do campo Telefone
$Telefone		= $_POST["telefone"];	// Pega o valor do campo Telefone
$DDDCelular		= $_POST["ddd-celular"];	// Pega o valor do campo Celular
$Celular		= $_POST["celular"];	// Pega o valor do campo Celular
$Assunto		= $_POST["assunto"];	// Pega o valor do campo Email
$Mensagem	= $_POST["mensagem"];// Pega os valores do campo Mensagem

// Variável que junta os valores acima e monta o corpo do email

$Vai 		= "Nome: $Nome\n\nEmail: $Email\n\nTelefone: $DDDTelefone $Telefone\n\nCelular: $DDDCelular $Celular\n\nAssunto: $Assunto\n\nMensagem: $Mensagem\n";

require_once("phpmailer/class.phpmailer.php");

define('GUSER', 'telecomriogroup.contato@telecomrio.com.br');	// <-- Insira aqui o seu GMail
define('GPWD', '123456ca');		// <-- Insira aqui a senha do seu GMail

function smtpmailer($para, $de, $de_nome, $assunto, $corpo) { 
	global $error;
	$mail = new PHPMailer();
	$mail->IsSMTP();		// Ativar SMTP
	$mail->SMTPDebug = 0;		// Debugar: 1 = erros e mensagens, 2 = mensagens apenas
	$mail->SMTPAuth = true;		// Autenticação ativada
	$mail->SMTPSecure = 'ssl';	// SSL REQUERIDO pelo GMail
	$mail->Host = 'smtp.gmail.com';	// SMTP utilizado
	$mail->Port = 587;  		// A porta 587 deverá estar aberta em seu servidor
	$mail->SMTPSecure = 'tls'; // SSL REQUERIDO pelo GMail
	$mail->Username = GUSER;
	$mail->Password = GPWD;
	$mail->SetFrom($de, $de_nome);
	$mail->Subject = $assunto;
	$mail->Body = $corpo;
	$mail->AddAddress($para);
	if(!$mail->Send()) {
		$error = 'Erro no Envio: '.$mail->ErrorInfo; 
		return false;
	} else {
		$error = 'Mensagem Enviada!';
		return true;
	}
}

// Insira abaixo o email que irá receber a mensagem, o email que irá enviar (o mesmo da variável GUSER), o nome do email que envia a mensagem, o Assunto da mensagem e por último a variável com o corpo do email.

 if (smtpmailer('contato@telecomrio.com.br', 'telecomriogroup.contato@telecomrio.com.br', 'Telecom Rio', 'Contato Via Site', $Vai)) {

	Header("location:http://www.telecomrio.com.br/2015/contato-enviado.php"); // Redireciona para uma página de obrigado.

}
if (!empty($error)) echo $error;
?>
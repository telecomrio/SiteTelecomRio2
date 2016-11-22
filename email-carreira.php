<?php
$Nome		= $_POST["nome"];	// Pega o valor do campo Nome
$Email		= $_POST["email"];	// Pega o valor do campo Email
$DDDTelefone		= $_POST["ddd-telefone"];	// Pega o valor do campo Telefone
$Telefone		= $_POST["telefone"];	// Pega o valor do campo Telefone
$DDDCelular		= $_POST["ddd-celular"];	// Pega o valor do campo Celular
$Celular		= $_POST["celular"];	// Pega o valor do campo Celular
$UF		= $_POST["uf"];	// Pega o valor do campo Estado UF
$Cep		= $_POST["cep"];	// Pega o valor do campo Cep
$Cidade		= $_POST["cidade"];	// Pega o valor do campo Cidade
$Endereco		= $_POST["endereco"];	// Pega o valor do campo Endereço
$Complemento		= $_POST["complemento"];	// Pega o valor do campo Complemento
$DataNasc		= $_POST["datanasc"];	// Pega o valor do campo Data de Nascimento
$Sexo		= $_POST["sexo"];	// Pega o valor do campo Sexo
$EstadoCivil		= $_POST["estadocivil"];	// Pega o valor do campo Estado Civil
$Filhos		= $_POST["filhos"];	// Pega o valor do campo Filhos
$Nacionalidade		= $_POST["nacionalidade"];	// Pega o valor do campo Nacionalidade
$Objetivo		= $_POST["objetivo"];	// Pega o valor do campo Objetivo Profissional
$PretensaoSalarial		= $_POST["pretensaosalarial"];	// Pega o valor do campo Pretensão salarial
$AceitaViajar		= $_POST["aceitaviajar"];	// Pega o valor do campo Aceita Viajar
$InfoComplementares		= $_POST["infocomplementares"];	// Pega o valor do campo Informações Complementares

// Variável que junta os valores acima e monta o corpo do email

$Vai 		= "Nome: $Nome\n\nEmail: $Email\n\nTelefone: $DDDTelefone $Telefone\n\nCelular: $DDDCelular $Celular\n\nEstado: $uF\n\nCep: $Cep\n\nCidade: $Cidade\n\nEndereço: $Endereco\n\nComplemento: $Complemento\n\nData de Nascimento: $DataNasc\n\nSexo: $Sexo\n\nEstado Civil: $EstadoCivil\n\nQuantidade de Filhos: $Filhos\n\nNacionalidade: $Nacionalidade\n\nObjetivo Profissional: $Objetivo\n\nPretensão Salarial: $PretensaoSalarial\n\nAceita Viajar pela Empresa: $AceitaViajar\n\nInformações Complementares: $InfoComplementares\n";

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

 if (smtpmailer('leonardo.fonseca@telecomrio.com.br', 'telecomriogroup.contato@telecomrio.com.br', 'Telecom Rio', 'Curriculum Via Site', $Vai)) {

	Header("location:http://www.telecomrio.com.br/2015/curriculum-enviado.php"); // Redireciona para uma página de obrigado.

}
if (!empty($error)) echo $error;
?>
<?php

namespace App\Util;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use DB, URL;

class notificar
{
	//Funcion para enviar el correo
    public function correo($correo = [], $asunto = '', $msg = '', $adjuntos = [], $correoDependencia = '', $enviarCopia = '', $enviarPiePagina = ''){
	
		$mail = new PHPMailer(true);	
		$usuario    = 'notificacioncootranshacaritama@gmail.com';
		$password   = 'Notific@2023.';
		$password   = 'grgsmqtlmijxaapj';
		$host       = 'smtp.gmail.com';	
	    $puerto     = 587;
		$SMTPSecure = 'SSL';

		try {
			$mail->SMTPDebug  = 0;
			$mail->isSMTP();
			$mail->charSet 	  = "UTF-8";
			$mail->Host       = $host;
			$mail->SMTPAuth   = true; 
			$mail->Username   = $usuario;
			$mail->Password   = $password; 
			$mail->SMTPSecure = $SMTPSecure;
			$mail->Port       = $puerto; //587

			$mail->setFrom($usuario,utf8_decode('Notificaciones CRM HACARITAMA'));
	
			foreach ($correo as $email) {
				$mail->addAddress($email);
			}

			if($enviarCopia == 1){//copia de la oculta
				$mail->addBCC($correoDependencia);
			}

			if(count($adjuntos) > 0){
				foreach ($adjuntos as $i => $adjunto) {
					$mail->addAttachment($adjunto[$i]);
				}
			}

			$piePagina = ($enviarPiePagina == 1) ? $this->consultarPiePagina() : '';
		
			$mail->isHTML(true);
			$mail->Subject = utf8_decode($asunto);
			$mail->Body    = utf8_decode($this->htmlCorreo($msg, $piePagina));
			$mail->AltBody = $msg;
			$mail->send();
			$mail->ClearAttachments();
			$mail->ClearAllRecipients();

			return implode(",", $correo);

		}catch (Exception $e) {
			return "No se puedo enviar el correo. Error: ".$mail->ErrorInfo;
		}
	}

	//Funcion para generar el html del correo
	function htmlCorreo($body,$piePagina) {
		$msj = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head><body>';
		$msj .='<div style="border:1px solid #e4e4e4;border-radius:5px; width: 98%;">';	
		$msj .='<div style="background-color:#44ac34; text-align:center; font-weight:bold; color:#fdfdfd; font-size: 15px; margin-top: -4px;"> Notificación del CRM COOTRANSHACARITAMA </div>';
		$msj .='<div style="margin-top:5px; padding:8px; text-align:justify;">'; 
		$msj .= $body;
		$msj .='</div>
				<div style="margin-top:5px; font-size:11px; padding:8px; color:#8c8c8c; text-align:justify; ">'.$piePagina.'
				</div>
				</div>
				</html>';
		return $msj;
	}

	//Consular la iformacion del pie de pagina
	function consultarPiePagina(){
		$informacioncorreo = DB::table('informacionnotificacioncorreo')
								->select('innococontenido')
								->where('innocoid', 1)->first();

		return $informacioncorreo->innococontenido;
	}
}
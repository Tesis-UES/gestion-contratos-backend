<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ValidationDocsNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    //El envio de correos de este notificador sera tanto para validaciones y Cambios criticos en datos de alto riesgo del sistema
    public function __construct($mensaje, $type)
    {
        $this->type = $type;
        $this->mensaje = $mensaje;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->type) {
            case 'validations':
                return $this->subject('Notificación de Validación de Documentos - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;
            case 'escalafones':
                return $this->subject('Notificación de Cambio de Información en Catalogo de Escalafónes - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;
            case 'validationHR':
                return $this->subject('Notificación de Envio de Solicitud de Contrato para Validación - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;

            case 'validationHRDirector':
                return $this->subject('Notificación de Validación de Solicitud de Contrato por Parte de Recursos Humanos - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;
            case 'SendSecretarySolicitude':
                return $this->subject('Notificación de Solicitud de Contrato Enviada a Secretaria - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;
            case 'recepcionSecretary':
                return $this->subject('Notificación de Solicitud de Contrato Enviada a Secretaria (Actualización)- FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;
            case 'AgreementUpdate':
                return $this->subject('Notificación de Solicitud de Contrato (Actualización) Acuerdo de Junta Directiva - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;
            case 'notificacionSolicitud':
                return $this->subject('Notificación de Ingreso a Solicitud de Contrato - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;

            case 'notificacionExpDoc':
                return $this->subject('Notificación de Vencimiento de Documento Personal - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;

            case 'notificacionAuthorities':
                return $this->subject('Notificación de Vencimiento de Periodo de Autoridades - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;
            case 'HrUpdateDoc':
                return $this->subject('Notificación de Actualización de Documentación por parte de candidato de contratación - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;
            case 'hhrrUpdateHr':
                return $this->subject('Notificación de Actualización de Solicitud de Contrato Observada - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;
            case 'notificacionCiclos':
                return $this->subject('Notificación de vencimiento de periodo de ciclo actual activo - FIA-UES')
                    ->markdown('emails.NotificationDocs', [
                        'mensaje'      => $this->mensaje,
                    ]);
                break;

            default:
                # code...
                break;
        }
    }
}

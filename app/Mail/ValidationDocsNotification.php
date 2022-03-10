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
    public function __construct($mensaje,$type)
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
                ->markdown('emails.NotificationDocs',[
                    'mensaje'      =>$this-> mensaje,
                ]);
                break;
            case 'escalafones':
                return $this->subject('Notificación de Cambio de Información en Catalogo de Escalafónes - FIA-UES')
                ->markdown('emails.NotificationDocs',[
                    'mensaje'      =>$this-> mensaje,
                ]);
                break;
            case 'validationHR':
                return $this->subject('Notificación de Envio de Solicitud de Contrato para Validación - FIA-UES')
                ->markdown('emails.NotificationDocs',[
                    'mensaje'      =>$this-> mensaje,
                ]);
                break;
            
            default:
                # code...
                break;
        }
        
    }
}

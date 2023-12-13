<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Clases\EnviarEmail;
use Carbon\Carbon;
use DB;

class NotificarSolicitud
{	
    //Funcion para verificar que si se envia los correos
    public function verificarSalidaCorreo()
    {
        $emailnotificacion = DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
        $email = $emailnotificacion->emprcorreo;
        $fechaHoraActual = Carbon::now();
        $enviarEmail = new EnviarEmail();
        $asunto = 'Notificación de prueba enviada hoy '.$fechaHoraActual;
        $msg = 'Este es el contenido de la notificaciones de prueba para ver si esta funcionando';
        $enviarEmail->enviar([$email], $asunto, $msg, '', '',0, 1);

        echo"Notifiacion de prueba enviada hoy ".$fechaHoraActual.", al email ".$email."\r\n";
    }

    //Funcion para notificar las solicitudes en estado inicial
    public function notificarSolicitudesEstadoInicial()
    {   
        $fechaHoraActual = Carbon::now();
        echo"Iniciando proceso de noticiar solicitudes en estado inicial hoy ".$fechaHoraActual." \r\n";
        $informacionemail  =  DB::table('informacioncorreonotificacion')->where('inconoid', 29)->first(); 
        $emailnotificacion =  DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
        $emailEmpresa = $emailnotificacion->emprcorreo;
        $enviarEmail  = new EnviarEmail();
       
       //Notifico las solicitudes en incial
        $solicitudes = DB::table('solicitud as s')
                                ->select('s.soliconsecutivo as consecutivo','s.solifechahora as fecha',
                                        'ts.tipsolnombre as tipo_solicitud', 'tm.tipmednombre as medio',
                                        's.solifecharespuesta','s.solianonimo',
                                        DB::raw("CONCAT(p.persprimernombre,' ',if(p.perssegundonombre is null ,'', p.perssegundonombre),' ',p.persprimerapellido,' ',if(p.perssegundoapellido is null ,'', p.perssegundoapellido)) as nombres")
                                        ) 
                                ->join('persona as p', 'p.persid', '=', 's.persid')
                                ->join('tipomedio as tm', 'tm.tipmedid', '=', 's.tipmedid')
                                ->join('tiposolicitud as ts', 'ts.tipsolid', '=', 's.tipsolid')
                                ->whereIn('s.tiessoid', [1,2])
                                ->orderBy('s.soliconsecutivo')->get();
                                
        if(count($solicitudes) > 0){
            $table = "<table>
                    <thead>
                        <tr>
                            <td><b>Número Solicitud</b></td>
                            <td><b>Peticionario</b></td>
                            <td><b>Fecha Hora</b></td>
                            <td><b>Tipo Solicitud</b></td>
                            <td><b>Medio</b></td>
                            <td><b>Fecha Máxima</b></td>
                        </tr>
                    </thead>
                    <tbody> ";
            foreach($solicitudes as $solicitud){
                $nombrePeticionario = ($solicitud->solianonimo == 1)?'ANONIMO':$solicitud->nombres;
                $table .= "             
                        <tr>
                            <td>".$solicitud->consecutivo."</td>
                            <td>".$nombrePeticionario."</td>
                            <td>".$solicitud->fecha."</td>
                            <td>".$solicitud->tipo_solicitud."</td>
                            <td>".$solicitud->medio."</td>
                            <td>".$solicitud->solifecharespuesta."</td>
                        </tr>";

            }
            $table .= "</tbody></table>";
            
            $buscar   = Array("fecha_actual");
            $remplazo = Array($fechaHoraActual);

            $asunto          = str_replace($buscar,$remplazo,$informacionemail->inconotitulo);
            $msg             = str_replace($buscar,$remplazo,$informacionemail->inconocontenido); 
            $enviarcopia     = $informacionemail->inconoenviarcopia;
            $enviarpiepagina = $informacionemail->inconoenviarpiepagina;
            
            $enviarEmail->enviar([$emailEmpresa], $asunto, $msg.' '.$table, '', $emailEmpresa, $enviarcopia, $enviarpiepagina);
                  
            echo "Notificando al ".$emailEmpresa." las solicitudes en estado inicial\r\n";
        }else{
            echo"No hay solicitudes en estado incial para notificar\r\n";
        }      

    }

    //Funcion para notificar las solicitudes pediente por responer
    public function notificarSolicitudesPendientePorResponder()
    {
        $fechaHoraActual = Carbon::now();
        echo "Iniciando proceso de notificacion antes de vencer las solicitudes hoy ".$fechaHoraActual."\r\n";
        $dias = DB::table('dianotificacion')->select('dianotdias')->orderBy('dianotdias')->get();
    
        $fechas = []; 
        foreach($dias as $dia){
            $fecha_notificar = $fechaHoraActual->addDays($dia->dianotdias);
            array_push($fechas, $fecha_notificar->format('Y-m-d'));
            $fechaHoraActual = $fechaHoraActual->subDays($dia->dianotdias);  
        }

        $informacionemail  =  DB::table('informacioncorreonotificacion')->where('inconoid', 30)->first(); 
        $emailnotificacion =  DB::table('empresa')->select('emprcorreo')->where('emprid', 1)->first();
        $emailEmpresa = $emailnotificacion->emprcorreo;
        $enviarEmail  = new EnviarEmail();

        $cordinador_pqrs = 'No existe ningún coordinador en la base de datos';
        $usuario = DB::table('users as u')
                        ->select(DB::raw("CONCAT(u.name,' ', u.apellidos) as nombre"))
                        ->join('persona as p', 'p.persid', '=', 'u.persid')
                        ->where('p.tipresid', 2)->where('activo',true)->first();
        if($usuario){
            $cordinador_pqrs = $usuario->nombre; 
        }

        echo "Notificando al coordinador ".$cordinador_pqrs."\r\n";
               
        //Consulto las  solicitudes en este periodo
        $solicitudes = DB::table('solicitud as s')  
                            ->select('s.soliid','s.soliconsecutivo', 's.solifecharespuesta','ts.tipsolnombre as tipo_solicitud')
                            ->join('tiposolicitud as ts', 'ts.tipsolid', '=', 's.tipsolid')
                            ->where('s.tiessoid', 3)
                            ->whereIn('s.solifecharespuesta', $fechas)
                            ->whereNotIn('s.soliid', function($query) use($fechas){ 
                                            $query->select('s1.soliid')
                                                ->from('solicitud as s1')
                                                ->Join('solicitudrespuesta as sr', 'sr.soliid', '=', 's1.soliid')
                                                ->whereIn('sr.solresrespuesta', $fechas)
                                                ->where('s1.tiessoid', 3);
                                        })->get();

        if(count($solicitudes) > 0){
            foreach ($solicitudes as $solicitud) {
                $soliid      = $solicitud->soliid;
                $consecutivo = $solicitud->soliconsecutivo;
                $fecha_max_respuesta = $solicitud->solifecharespuesta;
                $tipo_solicitud      = $solicitud->tipo_solicitud;

                $emailNotificaciones = DB::table('solicituddependencia as sd') ->select('d.depecorreo')
                                        ->join('dependencia as d', 'd.depeid', '=', 'sd.depeid')
                                        ->where('sd.soliid', $soliid)->get();
                
                foreach($emailNotificaciones as $emailNotificacion)
                {
                    $email[] = $emailNotificacion->depecorreo;
                }                     

                $buscar   = Array("consecutivo","fecha_max_respuesta","tipo_solicitud","cordinador_pqrs");
                $remplazo = Array($consecutivo,$fecha_max_respuesta,$tipo_solicitud,$cordinador_pqrs);

                $asunto          = str_replace($buscar,$remplazo,$informacionemail->inconotitulo);
                $msg             = str_replace($buscar,$remplazo,$informacionemail->inconocontenido); 
                $enviarcopia     = $informacionemail->inconoenviarcopia;
                $enviarpiepagina = $informacionemail->inconoenviarpiepagina;
                
                $enviarEmail->enviar($email, $asunto, $msg, '', $emailEmpresa, $enviarcopia, $enviarpiepagina);

                echo"Notifiacion enviada hoy ".$fechaHoraActual.", al email ".$email."\r\n";                
            }
        }else{
            echo"No existen solicitudes para notificar \r\n";
       }         
    }
}
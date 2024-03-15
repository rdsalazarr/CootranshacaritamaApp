import React, {useState} from 'react';
import Show from '../../atencionUsuario/solicitud/show';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import { Box} from '@mui/material';

export default function SolicitudVehiculo({data}){
 
    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});
    const modales = [<Show id={modal.data.soliid} />];
    const tituloModal = ['Visualizar la información de la solicitud'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'mediumFlot'});
    }

    return (
        <Box>
            <Box className='frmDivision' style={{marginBottom: '1em'}}>
                Solicitudes realizada al vehículo
            </Box>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Consecutivo','Fecha registro','Tipo solicitud','Asunto', 'Peticionario','Ver']}
                    ver={["consecutivo","solifechahoraregistro","tipoSolicitud","asunto", "nombrePersonaRadica"]}
                    accion={[
                        {tipo: 'B', icono : 'visibility',   color: 'green',  funcion : (data)=>{edit(data,0)} }
                        
                    ]}
                    funciones={{orderBy: false,search: false, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:5, data:{}, titulo:'', tamano: ''})}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
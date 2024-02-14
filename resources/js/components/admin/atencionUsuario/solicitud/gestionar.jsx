import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto  } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import { Box} from '@mui/material';
import NewEdit from './new';
import Show from './show';

export default function Gestionar(){
    
    const [modal, setModal] = useState({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});
    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);

    const modales = [
                        <NewEdit tipo={'I'} />,
                        <NewEdit data={modal.data} tipo={'U'} /> ,
                        <Show id={(tipo !== 0) ? modal.data.solicitudId : null}  />
                    ];

    const tituloModal = ['Registrar nueva solicitud',
                        'Editar solicitud',
                        'Visualizar información del registro de la solicitud'
                    ];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/antencion/usuario/listar/solicitud', {tipo:'GESTIONAR'}).then(res=>{
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={["Radicado", "Fecha registro", "Peticionario", "Asunto","Tipo solicitud", "Dependencia", "Estado", "Editar","Ver"]}
                    ver={["consecutivo", "fechaRadicado", "nombrePersonaRadica", "asunto", "tipoSolicitud", "dependencia", "estado"]}
                    accion={[
                        {tipo: 'T', icono : 'add',        color: 'green',  funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'edit',       color: 'orange', funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'visibility', color: 'green',  funcion : (data)=>{edit(data,2)} },
                    ]}
                    funciones={{orderBy: true, search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:5, data:{}, titulo:'', tamano: ''}), (modal.vista === 0 || modal.vista === 1 ) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
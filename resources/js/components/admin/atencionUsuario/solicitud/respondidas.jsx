import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto  } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import { Box} from '@mui/material';
import Show from './show';

export default function Respondidas(){

    const [modal, setModal] = useState({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});
    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);

    const modales     = [<Show id={modal.data.solicitudId}  />];
    const tituloModal = ['Visualizar información del registro de la solicitud'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/antencion/usuario/listar/solicitud', {tipo:'HISTORICO'}).then(res=>{
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
                    titulo={["Radicado", "Fecha registro", "Peticionario", "Asunto","Tipo solicitud", "Dependencia", "Estado", "Ver"]}
                    ver={["consecutivo", "fechaRadicado", "nombrePersonaRadica", "asunto", "tipoSolicitud", "dependencia", "estado"]}
                    accion={[{tipo: 'B', icono : 'visibility', color: 'green', funcion : (data)=>{edit(data, 0)} }]}
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
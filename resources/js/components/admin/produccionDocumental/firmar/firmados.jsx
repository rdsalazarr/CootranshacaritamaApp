import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto  } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Trazabilidad from '../trazabilidad';
import { Box} from '@mui/material';

export default function Firmados(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const modales = [ <Trazabilidad idProceso={modal.data.codoprid} idDocumento={modal.data.id} ruta={'acta'} /> ];

    const tituloModal = [  'Visualizar trazabilidad del documento'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano:  'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/firmar/documento/list', {tipo:'FIRMADOS'}).then(res=>{
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
                    titulo={['Consecutivo', 'Fecha','Tipo documento','Nombre dirigido','Estado','Trazabilidad']}
                    ver={["codigoDocumental", "fecha", "tipoDocumento","nombreDirigido", "nombreEstado"]}
                    accion={[
                        {tipo: 'B', icono : 'picture_as_pdf',      color: 'orange', funcion : (data)=>{edit(data, 0)} },
                    ]}
                    funciones={{orderBy: true, search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''})}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
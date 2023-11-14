import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto  } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';
import { Box} from '@mui/material';
import Frm from './new';

export default function Planilla(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const modales     = [<Frm tipo={'I'} />,
                        <Frm data={modal.data} tipo={'U'} />,
                        <VisualizarPdf id={(tipo === 2) ? modal.data.coseesid : null} /> ];

    const tituloModal = ['Registrar nueva planilla de servicio especial', 
                        'Editar planilla de servicio especial', 
                        'Visualizar PDF de la planilla del servicio especial'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2) ? 'mediumFlot' :'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/despacho/servicio/especial/list', {tipo:'ACTIVOS'}).then(res=>{
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
                    titulo={["Responsable", "Número de contrato", "Fecha inicial", "Fecha final", "Origen", "Destino","Editar", "PDF"]}
                    ver={["nombreResponsable", "numeroContrato", "coseesfechaincial", "coseesfechafinal", "coseesorigen", "coseesdestino"]}
                    accion={[
                        {tipo: 'T', icono : 'add',                   color: 'green',  funcion : (data)=>{edit(data, 0)} },
                        {tipo: 'B', icono : 'content_paste_go_icon', color: 'red',    funcion : (data)=>{edit(data, 1)} },
                        {tipo: 'B', icono : 'picture_as_pdf',        color: 'orange', funcion : (data)=>{edit(data, 2)} },
                    ]}
                    funciones={{orderBy: true, search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''}), (modal.vista !== 2 ) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
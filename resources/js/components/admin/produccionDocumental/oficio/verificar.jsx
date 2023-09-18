import React, {useState, useEffect} from 'react';
import {AnularSolicitarFirma, SellarDocumento} from '../../../layout/modalFijas';
import { ModalDefaultAuto  } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import VisualizarPdf from '../visualizarPdf';
import { Box} from '@mui/material';

export default function Verificar(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <AnularSolicitarFirma id={modal.data.id } ruta={'oficio'} cerrarModal={cerrarModal} />,
                        <SellarDocumento id={modal.data.id } ruta={'oficio'} cerrarModal={cerrarModal} />,
                        <VisualizarPdf id={modal.data.id } tipo={'OFICIO'} />
                    ];

    const tituloModal = ['Anular solicitud firma del tipo documental',
                        'Sellar el tipo documental',
                        'Visualizar el tipo documental en formato PDF'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 0 || tipo === 1) ? 'smallFlot' : 'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/producion/documental/oficio/list', {tipo:'VERIFICAR'}).then(res=>{
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
                    titulo={['Consecutivo', 'Dependencia','Fecha','Asunto','Dirigido','Estado','Anular firma','Sellar','PDF']}
                    ver={["consecutivo", "dependencia","fecha", "asunto","nombredirigido", "estado"]}
                    accion={[
                        {tipo: 'B', icono : 'clear_icon',     color: 'red',    funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'lock_icon',      color: 'green',  funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'picture_as_pdf', color: 'orange', funcion : (data)=>{edit(data,2)} },
                    ]}
                    funciones={{orderBy: false, search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''}), (modal.vista === 0 || modal.vista === 1) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
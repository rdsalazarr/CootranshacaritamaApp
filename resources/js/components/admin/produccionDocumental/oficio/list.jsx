import React, {useState, useEffect} from 'react';
import { Box, Typography, Card} from '@mui/material';
import TablaGeneral from '../../../layout/tablaGeneral';
import { ModalDefaultAuto  } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import NewEdit from './new';
import Prueba from './prueba';
import VisualizarPdf from '../visualizarPdf';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:4, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:4, data:{}, titulo:'', tamano:'bigFlot'});
    }

    //modal.data.id
    //<NewEdit tipo={'I'}  />, <Prueba />
    const modales = [
                        <NewEdit tipo={'I'} />,
                        <NewEdit id={(tipo === 1) ? modal.data.id : null} tipo={'U'} /> ,                      
                        <Prueba />,
                        <VisualizarPdf id={(tipo === 3) ? modal.data.id : null} tipo={'OFICIO'} />                    
                    ];

    const tituloModal = ['Registrar nuevo tipo documental oficio','Editar tipo documental oficio','Solicitar firma del tipo documental','Visualizar el tipo documental en formato PDF'];

    function edit(data, tipo){
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 ) ? 'smallFlot' :  'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/producion/documental/oficio/list').then(res=>{
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
            <Card className={'cardContainer'} >
                <Box><Typography  component={'h2'} className={'titleGeneral'}>Produccion de oficio</Typography>
                </Box>
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    <TablaGeneral
                        datos={data}
                        titulo={['Consecutivo', 'Dependencia','Fecha','Asunto','Dirigido','Estado','Editar','Solicitar','PDF']}
                        ver={["consecutivo", "dependencia","fecha", "asunto","nombredirigido", "estado"]}
                        accion={[
                            {tipo: 'T', icono : 'add',                 color: 'green',  funcion : (data)=>{edit(data,0)} },
                            {tipo: 'B', icono : 'edit',                color: 'orange', funcion : (data)=>{edit(data,1)} },
                            {tipo: 'B', icono : 'signal_cellular_alt', color: 'red',    funcion : (data)=>{edit(data,2)} },
                            {tipo: 'B', icono : 'picture_as_pdf',      color: 'orange', funcion : (data)=>{edit(data,3)} },
                        ]}
                        funciones={{orderBy: true,search: true, pagination:true}}
                    />
                </Box>

                <ModalDefaultAuto
                    title={modal.titulo}
                    content={modales[modal.vista]}
                    close={() =>{setModal({open : false, vista:4, data:{}, titulo:'', tamano: ''}), (modal.vista !== 3) ? inicio() : null;}}
                    tam = {modal.tamano}
                    abrir ={modal.open}
                />
            </Card>
        </Box>
    )
}
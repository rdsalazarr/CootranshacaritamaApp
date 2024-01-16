import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import Eliminar from '../../../layout/modalFijas';
import instance from '../../../layout/instance';
import { Box, Typography} from '@mui/material';
import VisualizarPdf from './visualizarPdf';
import NewEdit from './new';
import Show from './show';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:4, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <NewEdit tipo={'I'}  />,
                        <NewEdit data={modal.data} tipo={'U'} /> ,
                        <Eliminar id={(tipo === 2) ? modal.data.ingpdfid : null} ruta={'/admin/informacionGeneralPdf/destroy'} cerrarModal={cerrarModal} />,
                        <Show data={modal.data}  />,
                        <VisualizarPdf data={modal.data}  />                        
                    ];

    const tituloModal = ['Nueva información general de PDF','Editar información general de PDF','','Visualizar la información general de PDF'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2) ? 'smallFlot' : ((tipo === 4) ? 'mediumFlot' :'bigFlot')});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/informacionGeneralPdf/list').then(res=>{
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
            <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar información general del contenido de los PDF</Typography>
            </Box>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Nombre', 'Título','Actualizar','Eliminar','Ver', 'PDF']}
                    ver={["ingpdfnombre","ingpdftitulo"]}
                    accion={[
                        {tipo: 'T', icono : 'add',            color: 'green',  funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'edit',           color: 'orange', funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'delete',         color: 'red',    funcion : (data)=>{edit(data,2)} },
                        {tipo: 'B', icono : 'visibility',     color: 'green',  funcion : (data)=>{edit(data,3)} },
                        {tipo: 'B', icono : 'picture_as_pdf', color: 'orange', funcion : (data)=>{edit(data,4)} },
                    ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title   ={modal.titulo}
                content ={modales[modal.vista]}
                close   ={() =>{setModal({open : false, vista:5, data:{}, titulo:'', tamano: ''}), (modal.vista < 3) ? inicio() : null;}}
                tam     ={modal.tamano}
                abrir   ={modal.open}
            />
        </Box>
    )
}
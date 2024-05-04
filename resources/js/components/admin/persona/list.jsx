import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../layout/modal';
import TablaGeneral from '../../layout/tablaGeneral';
import instanceFile from '../../layout/instanceFile';
import {LoaderModal} from "../../layout/loader";
import { Box, Typography} from '@mui/material';
import Eliminar from '../../layout/modalFijas';
import instance from '../../layout/instance';
import Procesar from './procesar';
import NewEdit from './new';
import Show from './show';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <NewEdit tipo={'I'} frm={'PERSONA'} url={'/admin/persona/salve'} tpRelacion={'E'} />,
                        <NewEdit data={modal.data} tipo={'U'} frm={'PERSONA'} url={'/admin/persona/salve'} tpRelacion={'E'} /> ,
                        <Eliminar id={(tipo === 2) ? modal.data.persid : null} ruta={'/admin/persona/destroy'} cerrarModal={cerrarModal} />,
                        <Show id={(tipo === 3) ? modal.data.persid : null} frm={'PERSONA'}/>,
                        <Procesar data={modal.data}/>
                    ];

    const tituloModal = ['Nueva persona','Editar persona','','Visualizar la información de la persona', 'Procesar persona como asociado o como conductor'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 ) ? 'smallFlot' : 'bigFlot'});
    }

    const descargarFile = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/persona').then(res=>{
            setLoader(false);
        })
    } 

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/persona/list').then(res=>{
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box >
            <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar personas</Typography>
            </Box>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Tipo documento','Documento','Nombre','Dirección', 'Correo','Tipo de persona', 'Activo','Actualizar','Eliminar','Ver','Procesar']}
                    ver={["tipoIdentificacion","persdocumento","nombrePersona","persdireccion", "perscorreoelectronico", "tipoPersona","estado"]}
                    accion={[
                        {tipo: 'D', icono : 'file_download_icon', color: 'orange', funcion : (data)=>{descargarFile()} },
                        {tipo: 'T', icono : 'add',                color: 'green',  funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'edit',               color: 'orange', funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'delete',             color: 'red',    funcion : (data)=>{edit(data,2)} },
                        {tipo: 'B', icono : 'visibility',         color: 'green',  funcion : (data)=>{edit(data,3)} },
                        {tipo: 'B', icono : 'add_task_Icon',      color: 'red',    funcion : (data)=>{edit(data,4)} },
                        
                    ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:5, data:{}, titulo:'', tamano: ''}), (modal.vista < 3) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
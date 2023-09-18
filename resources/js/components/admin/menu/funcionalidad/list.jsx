import React, {useState, useEffect} from 'react';
import { Box, Card} from '@mui/material';
import TablaGeneral from '../../../layout/tablaGeneral';
import { ModalDefaultAuto  } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Eliminar from '../../../layout/eliminar';
import NewEdit from './new';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <NewEdit tipo={'I'}  />,
                        <NewEdit data={modal.data} tipo={'U'} /> ,
                        <Eliminar id={(tipo === 2) ? modal.data.funcid : null} ruta={'/admin/funcionalidad/destroy'} cerrarModal={cerrarModal} />
                    ];

    const tituloModal = ['Nueva funcionalidad','Editar funcionalidad',''];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 ) ? 'smallFlot' :  'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/funcionalidad/list').then(res=>{
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);
    
    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'containerMedium'} >
            <Box className={'cardContainer'} >
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    <TablaGeneral
                        datos={data}
                        titulo={['Módulo','Nombre', 'Título','Ruta','Ícono','Orden','Activo','Actualizar','Eliminar']}
                        ver={["modunombre","funcnombre","functitulo","funcruta","funcicono","funcorden","estado"]}
                        accion={[
                            {tipo: 'T', icono : 'add',   color: 'green',   funcion : (data)=>{edit(data,0)} },
                            {tipo: 'B', icono : 'edit',   color: 'orange', funcion : (data)=>{edit(data,1)} },
                            {tipo: 'B', icono : 'delete', color: 'red',    funcion : (data)=>{edit(data,2)} },
                        ]}
                        funciones={{orderBy: true,search: true, pagination:true}}
                    />
                </Box>

                <ModalDefaultAuto
                    title={modal.titulo}
                    content={modales[modal.vista]}
                    close={() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''}), inicio();}}
                    tam = {modal.tamano}
                    abrir ={modal.open}
                />
            </Box>
        </Box>
    )
}
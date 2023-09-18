import React, {useState, useEffect} from 'react';
import { Box, Typography, Card} from '@mui/material';
import TablaGeneral from '../../../layout/tablaGeneral';
import { ModalDefaultAuto  } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Eliminar from '../../../layout/eliminar';
import NewEdit from './new';
import Show from './show';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:4, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:4, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <NewEdit tipo={'I'}  />,
                        <NewEdit data={modal.data} tipo={'U'} /> ,
                        <Eliminar id={(tipo === 2) ? modal.data.innocoid : null} ruta={'/admin/informacionCorreo/destroy'} cerrarModal={cerrarModal} />,
                        <Show data={modal.data}  />
                    ];

    const tituloModal = ['Nueva información de notificación de correo','Editar información de notificación de correo','','Visualizar la información de notificación por correo'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 ) ? 'smallFlot' :  'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/informacionCorreo/list').then(res=>{
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
            <Card className={'cardContainer'} >
                <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar información de la notificación del correo</Typography>
                </Box>
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    <TablaGeneral
                        datos={data}
                        titulo={['Nombre', 'Asunto','Píe pagina','Copia','Actualizar','Eliminar','Ver']}
                        ver={["innoconombre","innocoasunto","enviarPiePagina","enviarCopia"]}
                        accion={[
                            {tipo: 'T', icono : 'add',        color: 'green',  funcion : (data)=>{edit(data,0)} },
                            {tipo: 'B', icono : 'edit',       color: 'orange', funcion : (data)=>{edit(data,1)} },
                            {tipo: 'B', icono : 'delete',     color: 'red',    funcion : (data)=>{edit(data,2)} },
                            {tipo: 'B', icono : 'visibility', color: 'green',  funcion : (data)=>{edit(data,3)} },
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
import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../../layout/modal';
import TablaGeneral from '../../../../layout/tablaGeneral';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import { Box, Card} from '@mui/material';
import NewEdit from './new';

export default function List(){

    const [loader, setLoader] = useState(false);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});

    const modales     = [<NewEdit data={modal.data} />];
    const tituloModal = ['Editar configuración de notificación de correo'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/configuracionCorreo/list').then(res=>{
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
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    <TablaGeneral
                        datos={data}
                        titulo={['Host', 'Usuario','Clave','Clave api','Puerto','Actualizar']}
                        ver={["incocohost","incocousuario","incococlave","incococlaveapi","incocopuerto"]}
                        accion={[{tipo: 'B', icono : 'edit',  color: 'orange', funcion : (data)=>{edit(data, 0)}}]}
                        funciones={{orderBy: false, search: false, pagination:false}}
                    />
                </Box>

                <ModalDefaultAuto
                    title={modal.titulo}
                    content={modales[modal.vista]}
                    close={() =>{setModal({open : false, vista:2, data:{}, titulo:'', tamano: ''}), inicio();}}
                    tam = {modal.tamano}
                    abrir ={modal.open}
                />
            </Card>
        </Box>
    )
}
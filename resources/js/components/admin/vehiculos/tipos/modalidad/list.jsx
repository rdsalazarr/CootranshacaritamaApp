import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../../layout/modal';
import TablaGeneral from '../../../../layout/tablaGeneral';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import { Box} from '@mui/material';
import NewEdit from './new';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const modales = [ <NewEdit data={modal.data} tipo={'U'} /> ];

    const tituloModal = ['Editar modalidad del vehículo',''];

    const edit = (data, tipo) =>{  
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/direccion/transporte/modalidad/list').then(res=>{
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
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Nombre','Cuota sostenimiento','Descuento pago anticipado', 'Recargo por mora','Posee despacho', 'Actualizar']}
                    ver={['timovenombre','cuotaSostenimiento','descuentoPagoAnticipado','moraRecargo','tieneDespacho']}
                    accion={[
                        {tipo: 'B', icono : 'edit',   color: 'orange', funcion : (data)=>{edit(data,0)} },
                    ]}
                    funciones={{orderBy: true, search: false, pagination:false}}
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
    )
}
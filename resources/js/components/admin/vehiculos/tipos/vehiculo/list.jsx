import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../../layout/modal';
import TablaGeneral from '../../../../layout/tablaGeneral';
import {LoaderModal} from "../../../../layout/loader";
import Eliminar from '../../../../layout/modalFijas';
import instance from '../../../../layout/instance';
import {Box} from '@mui/material';
import NewEdit from './new';

export default function List(){

    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);

    const cerrarModal = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <NewEdit tipo={'I'}  />,
                        <NewEdit data={modal.data} tipo={'U'} /> ,
                        <Eliminar id={(tipo === 2) ? modal.data.tipvehid : null} ruta={'/admin/direccion/transporte/tipo/destroy'} cerrarModal={cerrarModal} />
                    ];

    const tituloModal = ['Nuevo tipo de vehículo','Editar tipo de vehículo',''];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 ) ? 'smallFlot' :  'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/direccion/transporte/tipo/list').then(res=>{
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);
    
    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'container'}>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Nombre','Referencia','Capacidad pasajeros', 'Número de filas', 'Número de columna','Activo','Actualizar','Eliminar']}
                    ver={["tipvehnombre","tipvehreferencia","tipvehcapacidad","tipvehnumerofilas","tipvehnumerocolumnas","estado"]}
                    accion={[
                        {tipo: 'T', icono : 'add',    color: 'green',  funcion : (data)=>{edit(data, 0)} },
                        {tipo: 'B', icono : 'edit',   color: 'orange', funcion : (data)=>{edit(data, 1)} },
                        {tipo: 'B', icono : 'delete', color: 'red',    funcion : (data)=>{edit(data, 2)} },
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
    )
}
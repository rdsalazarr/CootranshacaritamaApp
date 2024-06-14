import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import instanceFile from '../../../layout/instanceFile';
import {LoaderModal} from "../../../layout/loader";
import Eliminar from '../../../layout/modalFijas';
import instance from '../../../layout/instance';
import { Box, Typography} from '@mui/material';
import NewEdit from './new';
import Show from './show';

export default function List(){

    const [modal, setModal] = useState({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});
    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);

    const cerrarModal = () =>{
        setModal({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <NewEdit tipo={'I'}  />,
                        <NewEdit data={modal.data} tipo={'U'} /> ,
                        <Eliminar id={(tipo === 2) ? modal.data.vehiid : null} ruta={'/admin/direccion/transporte/vehiculo/destroy'} cerrarModal={cerrarModal} />,
                        <Show id={(tipo === 3) ? modal.data.vehiid : null} />
                    ];

    const tituloModal = ['Nuevo vehículo','Editar vehículo','','Visualizar información general del vehículo'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 ) ? 'smallFlot' :  'bigFlot'});
    }

    const descargarFile = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/vehiculos').then(res=>{
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/direccion/transporte/vehiculo/list').then(res=>{
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
            <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar vehículos</Typography>
            </Box>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Tipo vehículo','Fecha ingreso','Número interno','Placa', 'Modelo', 'Cilindraje', 'Número de ejes', 'Estado', 'Actualizar','Eliminar', 'Visualizar']}
                    ver={["tipvehnombre","vehifechaingreso","vehinumerointerno","vehiplaca","vehimodelo","vehicilindraje","vehinumeroejes", "estado"]}
                    accion={[
                        {tipo: 'T', icono : 'add',                color: 'green',  funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'edit',               color: 'orange', funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'delete',             color: 'red',    funcion : (data)=>{edit(data,2)} },
                        {tipo: 'B', icono : 'visibility',         color: 'green',  funcion : (data)=>{edit(data,3)} },
                        {tipo: 'D', icono : 'file_download_icon', color: 'orange', funcion : (data)=>{descargarFile()} },
                    ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:5, data:{}, titulo:'', tamano: ''}), (modal.vista !== 3) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
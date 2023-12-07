import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import { Box, Card} from '@mui/material';
import Despachar from './despachar';
import NewEdit from './new';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const modales = [
                        <NewEdit tipo={'I'} />,
                        <NewEdit data={modal.data} tipo={'U'} />,
                        <Despachar data={modal.data} />
                    ];

    const tituloModal = ['Nueva planilla','Editar planilla','Despachar vehículo'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/despacho/planillas/list', {estado:false}).then(res=>{
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
                    titulo={['Fecha registo','Fecha de salida','Origen', 'Destino','Número','Vehículo','Conductor','Registrado por','Actualizar', 'Despachar']}
                    ver={["fechaHoraRegistro","fechaHoraSalida","municipioOrigen","municipioDestino","numeroPlanilla","nombreVehiculo", "nombreConductor", "usuarioRegistra"]}
                    accion={[
                        {tipo: 'T', icono : 'add',             color: 'green',  funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'edit',            color: 'orange', funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'car_repair_icon', color: 'red',    funcion : (data)=>{edit(data,2)} }
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
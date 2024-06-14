import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import {Box} from '@mui/material';
import NewEdit from './new';
import Show from './show';

export default function Registrados(){

    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);

    const modales     = [ <Show data={modal.data} /> ];
    const tituloModal = ['Visualizar la información de la suspención del vehículo'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/direccion/transporte/suspender/vehiculo/list', {tipo : 'PROCESADOS'}).then(res=>{
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
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Vehículo', 'Asociado', 'Fecha inicial', 'Fecha final', 'Motivo', 'Visualizar']}
                    ver={["nombreVehiculo","nombreAsociado","vehsusfechainicialsuspencion","vehsusfechafinalsuspencion","vehsusmotivo"]}
                    accion={[
                        {tipo: 'B', icono : 'visibility', color: 'green',  funcion : (data)=>{edit(data, 0)} },
                    ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title   = {modal.titulo}
                content = {modales[modal.vista]}
                close   = {() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''})}}
                tam     = {modal.tamano}
                abrir   = {modal.open}
            />
        </Box>
    )
}
import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';
import { Box} from '@mui/material';

export default function List(){

    const [data, setData] = useState([]);
    const [loader, setLoader] = useState(true);
     const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});
    const modales = [<VisualizarPdf id={modal.data.plarutid} /> ];
    const tituloModal = ['Visualizar PDF del formato de la planilla'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'smallFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/despacho/planillas/list', {estado:1}).then(res=>{
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
                    titulo={['Fecha registo','Fecha de salida','Origen', 'Destino','Número','Vehículo','Conductor','Registrado por','Recibida por', 'Imprimir']}
                    ver={["fechaHoraRegistro","fechaHoraSalida","municipioOrigen","municipioDestino","numeroPlanilla","nombreVehiculo", "nombreConductor", "usuarioRegistra", "usuarioDespacha"]}
                    accion={[ {tipo: 'B', icono : 'picture_as_pdf', color: 'orange', funcion : (data)=>{edit(data,0)} }]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:2, data:{}, titulo:'', tamano: ''})}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
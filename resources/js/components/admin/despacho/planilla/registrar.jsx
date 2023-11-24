import React, {useState, useEffect} from 'react';
import { Box, Typography, Card} from '@mui/material';
import TablaGeneral from '../../../layout/tablaGeneral';
import { ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import Eliminar from '../../../layout/modalFijas';
import instance from '../../../layout/instance';
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
                        <NewEdit data={modal.data} tipo={'U'} />
                    ];

    const tituloModal = ['Nueva planilla','Editar planilla','Despachar vehículo',''];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 3 ) ? 'smallFlot' : 'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/despacho/planillas/list', {estado:'R'}).then(res=>{
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
            <Card className={'cardContainer'} >              
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    <TablaGeneral
                        datos={data}
                        titulo={['Fecha registo','Fecha de salida','Origen', 'Destino','Número','Vehículo','Conductor','Registrado por','Recibida por','Actualizar']}
                        ver={["fechaHoraRegistro","fechaHoraSalida","municipioOrigen","municipioDestino","numeroPlanilla","nombreVehiculo", "nombreConductor", "usuarioRegistra", "usuarioRecibe"]}
                        accion={[
                            {tipo: 'T', icono : 'add',                    color: 'green',  funcion : (data)=>{edit(data,0)} },
                            {tipo: 'B', icono : 'edit',                   color: 'orange', funcion : (data)=>{edit(data,1)} },
                        ]}
                        funciones={{orderBy: true,search: true, pagination:true}}
                    />
                </Box>
            </Card>

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
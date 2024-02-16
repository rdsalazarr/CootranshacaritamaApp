import React, {useState, useEffect} from 'react';
import {ActivarConductor} from '../../../layout/modalFijas';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Firmar from './firmar';
import { Box} from '@mui/material';

export default function Pendientes(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <Firmar data={modal.data}  /> ,
                        
                    ];

    const tituloModal = ['Editar conductor', 'Activar conductor','Visualizar la información del conductor'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 1) ? 'smallFlot' :  'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/direccion/transporte/list/contrato/vehiculos', {tipo: 'PENDIENTE'}).then(res=>{
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
                    titulo={['Fecha contrato','Número contrato','Nombre asociado','Vehículo', 'Firmado por asociado','Firmar']}
                    ver={["fechaContrato","numeroContrato","nombreAsociado","nombreVehiculo", "firmadoAsociado"]}
                    accion={[
                        {tipo: 'B', icono : 'edit',          color: 'orange', funcion : (data)=>{edit(data, 0)} },
                    ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:2, data:{}, titulo:'', tamano: ''}), (modal.vista !== 1) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
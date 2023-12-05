import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import { Box} from '@mui/material';
import Show from './show';

export default function Historico(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});
    const modales = [<Show data={modal.data} /> ];
    const tituloModal = ['Visualizar información general del tiquete'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 1) ? 'smallFlot' : 'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/despacho/tiquete/list', {estado:'R', tipo:'HISTORICO'}).then(res=>{
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
                    titulo={['Fecha registo','Fecha salida','Número tiquete','Origen', 'Destino','Vehículo','Cliente','Visualizar']}
                    ver={["fechaHoraRegistro","fechaSalida","numeroTiquete", "municipioOrigen","municipioDestino","nombreVehiculo","nombreCliente"]}
                    accion={[
                        {tipo: 'B', icono : 'visibility',     color: 'green',  funcion : (data)=>{edit(data, 0)} }
                    ]}
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
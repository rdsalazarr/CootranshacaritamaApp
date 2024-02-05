import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import { Box, Typography} from '@mui/material';
import Show from '../show/show';

export default function List(){

    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});
    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);

    const modales     = [ <Show id={modal.data.solcreid } /> ];
    const tituloModal = ['Visualizar información de la solicitud de crédito'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/cartera/historial/solicitud/credito').then(res=>{
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
            <Box><Typography  component={'h2'} className={'titleGeneral'}>Verificar historial solicitud de créditos</Typography>
            </Box>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Fecha','Línea crédito', 'Asociado','Destino', 'Valor solicitado','Plazo', 'Visualizar']}
                    ver={["solcrefechasolicitud","lineaCredito","nombreAsociado","solcredescripcion","valorSolicitado", "solcrenumerocuota"]}
                    accion={[{tipo: 'B', icono : 'visibility',    color: 'green', funcion : (data)=>{edit(data,0)} }]}
                    funciones={{orderBy: true, search: true, pagination:true}}
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
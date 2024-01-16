import React, {useState, useEffect} from 'react';
import {TomarDecisionSolicitudCredito} from '../../../../layout/modalFijas';
import { ModalDefaultAuto } from '../../../../layout/modal';
import TablaGeneral from '../../../../layout/tablaGeneral';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import { Box, Typography} from '@mui/material';
import Show from '../../show/show';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <TomarDecisionSolicitudCredito id={modal.data.solcreid } cerrarModal={cerrarModal}  />,
                        <Show id={modal.data.solcreid } />
                    ];

    const tituloModal = ['Tomar decisión sobre la solicitud de crédito','Visualizar información de la solicitud de crédito',''];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 0 ) ? 'smallFlot' :  'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/cartera/aprobar/solicitud/credito').then(res=>{
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
            <Box><Typography  component={'h2'} className={'titleGeneral'}>Aprobar solicitud de créditos</Typography>
            </Box>
            <Box style={{ paddingTop: "1em"}}  sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Fecha','Línea crédito', 'Asociado','Destino', 'Valor solicitado','Plazo', 'Decidir','Visualizar']}
                    ver={["solcrefechasolicitud","lineaCredito","nombreAsociado","solcredescripcion","valorSolicitado", "solcrenumerocuota"]}
                    accion={[
                        {tipo: 'B', icono : 'done_all_icon', color: 'red',   funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'visibility',    color: 'green', funcion : (data)=>{edit(data,1)} },
                    ]}
                    funciones={{orderBy: true, search: false, pagination:true}}
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
import React, {useState, useEffect} from 'react';
import {Grid, Button, Box, Typography} from '@mui/material';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import instanceFile from '../../../layout/instanceFile';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Seguimiento from './seguimiento';
import Show from './show';

export default function List(){

    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'smallFlot'});
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [loader, setLoader] = useState(false);
    const [data, setData] = useState([]);

    const tituloModal = ['Hacer seguimiento','Visualizar el crédito'];
    const modales     = [
                        <Seguimiento id={modal.data.solcreid} />,
                        <Show id={modal.data.solcreid} />
                       ];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 0 ) ? 'mediumFlot' : 'bigFlot'});
    }

    const descargarFile = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/cartera/vencida').then(res=>{
            setLoader(false);
        })
    } 

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/cartera/gestionar/cobro/cartera').then(res=>{
            let datos = res.data;
            setData(datos);
            setDatosEncontrados((datos.length > 0) ? true : false);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <Box>
                <Typography component={'h2'} className={'titleGeneral'}>Gestión de cobro de cartera</Typography>
            </Box>

            <Grid container spacing={2}>

                {(datosEncontrados) ? 
                    <Grid item md={12} xl={12} sm={12} style={{textAlign: 'center', paddingTop: '2em'}}>
                        <Button class="download-button" type="button" onClick={() => {descargarFile()}}>
                            <Box class="docs">
                                <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2" stroke="currentColor" height="20" width="20" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line y2="13" x2="8" y1="13" x1="16"></line>
                                <line y2="17" x2="8" y1="17" x1="16"></line>
                                <polyline points="10 9 9 9 8 9"></polyline></svg> Descargar excel
                            </Box>
                            <Box class="download">
                                <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2" stroke="currentColor" height="24" width="24" viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line y2="3" x2="12" y1="15" x1="12"></line>
                                </svg>
                            </Box>
                        </Button>
                    </Grid>
                : null}

                <Grid item md={12} xl={12} sm={12} style={{marginTop: '-1em'}}>
                    <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                        <TablaGeneral
                            datos={data}
                            titulo={['Documento','Nombre asociado','Número de crédito','Fecha desembolso','Valor', 'Vehículo', 'Placa', 'Número interno', 'Días en mora', 'Hacer seguimiento', 'Visualizar']}
                            ver={["persdocumento","nombreAsociado","numeroColocacion","colofechacolocacion","colovalordesembolsado", "referenciaVehiculo", "vehiplaca", "vehinumerointerno", "diasMora"]}
                            accion={[
                                {tipo: 'B', icono : 'assignment_turned_in', color: 'red',   funcion : (data)=>{edit(data,0)}},
                                {tipo: 'B', icono : 'visibility',           color: 'green', funcion : (data)=>{edit(data,1)}}
                            ]}
                            funciones={{orderBy: true, search: true, pagination:true}}
                        />
                    </Box>
                </Grid>
            </Grid>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:2, data:{}, titulo:'', tamano: ''});}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />

        </Box>
    )
}
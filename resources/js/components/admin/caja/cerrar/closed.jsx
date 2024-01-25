import React, {useState, useEffect} from 'react';
import { Box, Grid, Card, Typography, Button, ButtonGroup} from '@mui/material';
import FileDownloadIcon from '@mui/icons-material/FileDownload';
import AttachMoneyIcon from '@mui/icons-material/AttachMoney';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import instanceFile from '../../../layout/instanceFile';
import {LoaderModal} from "../../../layout/loader";
import CloseIcon from '@mui/icons-material/Close';
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';

export default function Closed(){

    const [movimientoCaja, setMovimientoCaja] = useState({saldoInicial:'',valorDebito:'',valorCredito:'', saldoCerrar:''});
    const [data, setData]     = useState([]);
    const [loader, setLoader] = useState(true);
    const [modal, setModal]   = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});
    const modales     = [<VisualizarPdf id={modal.data.plarutid} /> ];
    const tituloModal = ['Visualizar PDF del formato del comprobante contable'];
    
    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'smallFlot'});
    }

    const descargarFile = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/movimiento/diarios').then(res=>{
            setLoader(false);
        })
    }

    const buttons = [
            <Button key="1" startIcon={<FileDownloadIcon />} onClick={() => {descargarFile()}}>Descargar excel</Button>,
            <Button key="2" startIcon={<CloseIcon />} style={{marginTop: '1em'}}>Cerrar caja</Button>
        ];

    const inicio = () =>{
        setLoader(true);
        let newMovimientoCaja = {...movimientoCaja}
        instance.get('/admin/caja/cerrar/movimiento').then(res=>{
            let movimientocaja             = res.movimientoCaja;
            newMovimientoCaja.saldoInicial = movimientocaja.saldoInicial;
            newMovimientoCaja.valorDebito  = formatearNumero(movimientocaja.valorDebito);
            newMovimientoCaja.valorCredito = formatearNumero(movimientocaja.valorCredito);
            newMovimientoCaja.saldoCerrar  = formatearNumero(parseInt(movimientocaja.movcajsaldoinicial) + parseInt(movimientocaja.valorDebito));
            setMovimientoCaja(newMovimientoCaja);
            setData(res.data);
            setLoader(false);
        }) 
    }
    
    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
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
                    titulo={['Fecha movimeinto','Código contable','Descripción', 'Débito','Crédito']}
                    ver={["fechaMovimiento","cueconcodigo","cueconnombre","valorDebito","valorCredito"]}
                    accion={[]}
                    funciones={{orderBy: true, search: true, pagination:true}}
                />
            </Box>

            <Grid container spacing={2} style={{marginTop:'1em'}}>

                <Grid item xl={10} md={10} sm={9} xs={12}> 
                    <Grid container spacing={2}>
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Card className='cardNotificacion'>
                                <Typography component={'h5'} >Saldo inicial</Typography>
                                <Box className='cardBox'>
                                    <AttachMoneyIcon className='cardIcono'></AttachMoneyIcon>
                                    <Typography component={'h4'} >{movimientoCaja.saldoInicial}</Typography>
                                </Box>
                            </Card>                    
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Card className='cardNotificacion'>
                                <Typography component={'h5'} >Valor débito</Typography>
                                <Box className='cardBox'>
                                    <AttachMoneyIcon className='cardIcono'></AttachMoneyIcon>
                                    <Typography component={'h4'} >{movimientoCaja.valorDebito}</Typography>
                                </Box>
                            </Card> 
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Card className='cardNotificacion'>
                                <Typography component={'h5'} >Valor crédito</Typography>
                                <Box className='cardBox'>
                                    <AttachMoneyIcon className='cardIcono'></AttachMoneyIcon>
                                    <Typography component={'h4'} >{movimientoCaja.valorCredito}</Typography>
                                </Box>
                            </Card> 
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Card className='cardNotificacion'>
                                <Typography component={'h5'} >Saldo a cerrar</Typography>
                                <Box className='cardBox'>
                                    <AttachMoneyIcon className='cardIcono'></AttachMoneyIcon>
                                    <Typography component={'h4'} >{movimientoCaja.saldoCerrar}</Typography>
                                </Box>
                            </Card> 
                        </Grid>
                    </Grid>
                </Grid>

                <Grid item xl={2} md={2} sm={3} xs={12} style={{textAlign: 'center'}}>
                    <ButtonGroup
                        orientation="vertical"
                    >
                        {buttons}
                    </ButtonGroup>
                </Grid>

            </Grid>

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
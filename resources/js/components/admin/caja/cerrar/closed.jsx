import React, {useState, useEffect, Fragment} from 'react';
import { Box, Grid, Card, Typography, Button, ButtonGroup} from '@mui/material';
import {CerrarCaja, ContabilizarTiquete} from '../../../layout/modalFijas';
import FileDownloadIcon from '@mui/icons-material/FileDownload';
import AttachMoneyIcon from '@mui/icons-material/AttachMoney';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import instanceFile from '../../../layout/instanceFile';
import VisualizarPdf from '../movimiento/visualizarPdf';
import {FormatearNumero} from "../../../layout/general";
import LocalAtmIcon from '@mui/icons-material/LocalAtm';
import {LoaderModal} from "../../../layout/loader";
import CloseIcon from '@mui/icons-material/Close';
import instance from '../../../layout/instance';
import CajaNoAbierta from "./cajaNoAbierta";

export default function Closed(){

    const [movimientoCaja, setMovimientoCaja] = useState({saldoInicial:'',valorDebito:'',valorCredito:'', saldoCerrar:'', saldoCerrarFormateado:'', saldoTiquete:''});
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    const [nombreUsuario, setNombreUsuario] = useState('');
    const [cajaAbierta, setCajaAbierta] = useState(true);
    const [loader, setLoader] = useState(true);
    const [data, setData]     = useState([]);

    const cerrarModal = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const mostrarComprobante = (dataFactura) =>{
        edit(dataFactura, 1);
    }

    const ejecutarInicio = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
        inicio();
    }

    const modales     = [<CerrarCaja saldoCerrar={modal.data} cerrarModal={cerrarModal} mostrarComprobante={mostrarComprobante} />, 
                        <VisualizarPdf dataFactura={modal.data} />,
                        <ContabilizarTiquete cerrarModal={cerrarModal} ejecutarInicio={ejecutarInicio} /> ];

    const tituloModal = ['Cerrar caja para el día de hoy',
                        'Visualizar comprobante contable en PDF'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 0 || tipo === 2 ) ? 'smallFlot' : 'mediumFlot'});
    }

    const descargarFile = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/movimiento/diarios').then(res=>{
            setLoader(false);
        })
    }

    const buttons = [
            <Button key="1" startIcon={<FileDownloadIcon />} onClick={() => {descargarFile()}}>Descargar excel</Button>,
            <Button key="2" startIcon={<CloseIcon />} style={{marginTop: '1em'}} onClick={() => {edit(movimientoCaja.saldoCerrar, 0)}} >Cerrar caja</Button>
        ];

    const inicio = () =>{
        setLoader(true);
        let newMovimientoCaja = {...movimientoCaja}
        instance.get('/admin/caja/cerrar/movimiento').then(res=>{
            if(res.success){
                let movimientocaja                      = res.movimientoCaja;
                newMovimientoCaja.saldoInicial          = movimientocaja.saldoInicial;
                newMovimientoCaja.valorDebito           = FormatearNumero({numero: movimientocaja.valorDebito});
                newMovimientoCaja.valorCredito          = FormatearNumero({numero: movimientocaja.valorCredito});
                newMovimientoCaja.saldoCerrarFormateado = FormatearNumero({numero: parseInt(movimientocaja.movcajsaldoinicial) + parseInt((movimientocaja.valorDebito === null) ? 0 : movimientocaja.valorDebito )});
                newMovimientoCaja.saldoCerrar           = parseInt(movimientocaja.movcajsaldoinicial) + parseInt(movimientocaja.valorDebito);
                newMovimientoCaja.saldoTiquete          = FormatearNumero({numero: movimientocaja.saldoTiquete});
                setMovimientoCaja(newMovimientoCaja);
                setData(res.data);
            }else{
                showSimpleSnackbar(res.message, 'error');
            }
            setNombreUsuario(res.nombreUsuario);
            setCajaAbierta(res.success);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            {(cajaAbierta) ?
                <Fragment>
                    <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                        <TablaGeneral
                            datos={data}
                            titulo={['Fecha movimeinto','Código contable','Descripción', 'Débito','Crédito']}
                            ver={["fechaMovimiento","cueconcodigo","cuecondescripcion","valorDebito","valorCredito"]}
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
                                            <Typography component={'h4'} >{movimientoCaja.saldoCerrarFormateado}</Typography>
                                        </Box>
                                    </Card>
                                </Grid>

                                {(movimientoCaja.saldoTiquete > 0) ?
                                    <Fragment>
                                        <Grid item xl={3} md={3} sm={6} xs={12}> 
                                            <Card className='cardNotificacion'>
                                                <Typography component={'h5'} >Tiquetes vendidos</Typography>
                                                <Box className='cardBox'>
                                                    <AttachMoneyIcon className='cardIcono'></AttachMoneyIcon>
                                                    <Typography component={'h4'} >{movimientoCaja.saldoTiquete}</Typography>
                                                </Box>
                                            </Card>
                                        </Grid>

                                        <Grid item xl={3} md={3} sm={6} xs={12}>
                                            <Button key="1" startIcon={<LocalAtmIcon />} onClick={() => {edit('', 2)}} >Contabilizar tiquete</Button>,
                                        </Grid>
                                    </Fragment>
                                : null }

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
                        title   = {modal.titulo}
                        content = {modales[modal.vista]}
                        close   = {() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''}), inicio();}}
                        tam     = {modal.tamano}
                        abrir   = {modal.open}
                    />

              </Fragment>
            : 
                <CajaNoAbierta usuario={nombreUsuario} />
            }

        </Box>
    )
}
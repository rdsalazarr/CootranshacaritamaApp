import React, {useState, useEffect, Fragment} from 'react';

import { Radio, RadioGroup, FormControlLabel, FormControl, FormLabel} from '@mui/material';
import { ValidatorForm} from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../../layout/modal';
import {Button, Grid, Stack, Box} from '@mui/material';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import ClearIcon from '@mui/icons-material/Clear';
import SaveIcon from '@mui/icons-material/Save';
import VisualizarPdf from '../visualizarPdf';

export default function PagarCuota({data, cerrarModal}){

    const [formData, setFormData] = useState({colocacionId: data.coloid, liquidacionId: data.colliqid, fechaCuota:'', valorCuota:'', interesCorriente:'', interesMora:'',
                                            descuentoAnticipado:'', totalAPagar:'', valorCuotaMostrar:'',interesCorrienteMostrar:'', interesMoraMostrar:'',
                                            descuentoAnticipadoMostrar:'', totalAPagarMostrar:'', interesCorrienteTotal: '', interesCorrienteTotalMostrar: '' });
    const [pagoMensualidad, setPagoMensualidad] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);
    const [pagoGeneral, setPagoGeneral] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [dataFactura, setDataFactura] = useState('');  
    const [pagoTotal, setPagoTotal] = useState('N');
    const [loader, setLoader] = useState(false);

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }

    const handleChangeRadio = (event) => {
        setPagoTotal(event.target.value); 
        let newFormData = {...formData};

        if(event.target.value === 'S'){
            newFormData.interesCorriente           = pagoGeneral.valorIntereses;
            newFormData.interesMora                = pagoGeneral.valorInteresMora;
            newFormData.descuentoAnticipado        = pagoGeneral.valorDescuento;
            newFormData.totalAPagar                = pagoGeneral.totalAPagar;
            newFormData.interesCorrienteTotal      = pagoGeneral.interesMensualTotal;
            newFormData.interesCorrienteMostrar    = formatearNumero(pagoGeneral.valorIntereses);
            newFormData.interesMoraMostrar         = formatearNumero(pagoGeneral.valorInteresMora);
            newFormData.descuentoAnticipadoMostrar = formatearNumero(pagoGeneral.valorDescuento);
            newFormData.totalAPagarMostrar         = formatearNumero(pagoGeneral.totalAPagar);
            newFormData.interesCorrienteTotalMostrar = formatearNumero(pagoGeneral.interesMensualTotal);
        }else{
            newFormData.interesCorriente             = pagoMensualidad.valorIntereses;
            newFormData.interesMora                  = pagoMensualidad.valorInteresMora;
            newFormData.descuentoAnticipado          = pagoMensualidad.valorDescuento;
            newFormData.totalAPagar                  = pagoMensualidad.totalAPagar;
            newFormData.interesCorrienteTotal        = pagoMensualidad.interesMensualTotal;
            newFormData.interesCorrienteMostrar      = formatearNumero(pagoMensualidad.valorIntereses);
            newFormData.interesMoraMostrar           = formatearNumero(pagoMensualidad.valorInteresMora);
            newFormData.descuentoAnticipadoMostrar   = formatearNumero(pagoMensualidad.valorDescuento);
            newFormData.totalAPagarMostrar           = formatearNumero(pagoMensualidad.totalAPagar);
            newFormData.interesCorrienteTotalMostrar = formatearNumero(pagoMensualidad.interesMensualTotal);
        }
        setFormData(newFormData);
    }

    const handleSubmit = () =>{
        setLoader(true);
        let newFormData       = {...formData}
        newFormData.pagoTotal = pagoTotal;
        instance.post('/admin/caja/registrar/pago/cuota', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? (setHabilitado(false), setDataFactura(res.dataFactura), setAbrirModal(true)) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/caja/calcular/valor/cuota', {colocacionId: data.coloid, liquidacionId: data.colliqid}).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{
                let pagoMensualidad                      = res.pagoMensualidad[0];
                newFormData.fechaCuota                   = pagoMensualidad.fechaCuota;
                newFormData.valorCuota                   = pagoMensualidad.valorCuota;
                newFormData.interesCorriente             = pagoMensualidad.valorIntereses;
                newFormData.interesMora                  = pagoMensualidad.valorInteresMora;
                newFormData.descuentoAnticipado          = pagoMensualidad.valorDescuento;
                newFormData.totalAPagar                  = pagoMensualidad.totalAPagar;
                newFormData.interesCorrienteTotal        = pagoMensualidad.interesMensualTotal;
                newFormData.valorCuotaMostrar            = formatearNumero(pagoMensualidad.valorCuota);
                newFormData.interesCorrienteMostrar      = formatearNumero(pagoMensualidad.valorIntereses);
                newFormData.interesMoraMostrar           = formatearNumero(pagoMensualidad.valorInteresMora);
                newFormData.descuentoAnticipadoMostrar   = formatearNumero(pagoMensualidad.valorDescuento);
                newFormData.totalAPagarMostrar           = formatearNumero(pagoMensualidad.totalAPagar);
                newFormData.interesCorrienteTotalMostrar = formatearNumero(pagoMensualidad.interesMensualTotal);
                setPagoMensualidad(res.pagoMensualidad[0]);
                setPagoGeneral(res.pagoTotal[0]);
                setFormData(newFormData);
                setPagoTotal('N');
            }
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={handleSubmit}>

                <Grid container spacing={2}>
                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='frmDivision'>
                            Información de la persona y la colocación
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Número colocacion</label>
                            <span>{data.numeroColocacion}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={6} md={6} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Nombre persona</label>
                            <span>{data.nombrePersona}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Valor desembolso</label>
                            <span>{data.valorDesembolsado}</span>
                        </Box>
                    </Grid>

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='frmDivision'>
                            Información del pago
                        </Box>
                    </Grid>

                    <Grid item md={3} xl={3} sm={6} xs={12} >
                        <FormControl>
                            <FormLabel className='labelRadio'>Pago total</FormLabel>
                                <RadioGroup
                                    row
                                    name="pagoTotal"
                                    value={pagoTotal}
                                    onChange={handleChangeRadio}
                                >
                                <FormControlLabel value="N" control={<Radio color="success"/>} label="No" />
                                <FormControlLabel value="S" control={<Radio color="success"/>} label="Sí" />
                            </RadioGroup>
                        </FormControl>
                    </Grid>

                    <Grid item md={3} xl={3} sm={6} xs={12} >
                        <Box className='frmTexto'>
                            <label>Fecha cuota: </label>
                            <span >{'\u00A0'+ formData.fechaCuota}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}> 
                        <Box className='frmTexto'>
                            <label>Valor cuota</label>
                            <span className='textoRojo' ><span className='textoGris'>$</span> {'\u00A0'+ formData.valorCuotaMostrar}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Interés cuota</label>
                            <span className='textoRojo'><span className='textoGris'>$</span> {'\u00A0'+formData.interesCorrienteMostrar}</span>
                        </Box>
                    </Grid>

                    {(formData.interesMoraMostrar !== '0') ?
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Interés mora </label>
                                <span className='textoRojo'><span className='textoGris'>$</span> {'\u00A0'+formData.interesMoraMostrar}</span>
                            </Box>
                        </Grid>
                    : null }

                    {(formData.descuentoAnticipadoMostrar !== '0') ?
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Descuento anticipado</label>
                                <span className='textoRojo'> <span className='textoGris'>$</span> {'\u00A0'+formData.descuentoAnticipadoMostrar}</span>
                            </Box>
                        </Grid>
                    : null }

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Interés a pagar</label>
                            <span className='textoRojo'><span className='textoGris'>$</span> {'\u00A0'+formData.interesCorrienteTotalMostrar}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto columnaPagar'>
                            <label className='labelDeroration'>Total a pagar</label>
                            <span className='textoRojo'> <span className='textoGris'>$</span> {'\u00A0'+formData.totalAPagarMostrar}</span>
                        </Box>
                    </Grid>

                </Grid>

                <Grid container spacing={2}>
                    <Grid item xl={6} md={6} sm={6} xs={6}>
                        <Button onClick={cerrarModal} className='modalBtnRojo'
                            startIcon={<ClearIcon />}> Cancelar
                        </Button>
                    </Grid>

                    <Grid item xl={6} md={6} sm={6} xs={6}>
                        <Stack direction="row" spacing={2} justifyContent="right">
                            <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                                startIcon={<SaveIcon />}> Guardar
                            </Button>
                        </Stack>
                    </Grid>
                </Grid>

            </ValidatorForm>

            <ModalDefaultAuto
                title   = {'Visualizar factura en PDF del pago de crédito'}
                content = {<VisualizarPdf dataFactura={dataFactura} />}
                close   = {() =>{setAbrirModal(false);}}
                tam     = 'smallFlot'
                abrir   = {abrirModal}
            />

        </Fragment>
    )
}
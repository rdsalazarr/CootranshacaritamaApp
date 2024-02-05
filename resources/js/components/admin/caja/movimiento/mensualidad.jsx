import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import { Button, Grid, Stack, Icon, Autocomplete, createFilterOptions, Box, Card} from '@mui/material';
import { Radio, RadioGroup, FormControlLabel, FormControl, FormLabel} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import SearchIcon from '@mui/icons-material/Search';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import VisualizarPdf from './visualizarPdf';

export default function Mensualidad(){

    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [formData, setFormData] = useState({vehiculoId:'', idResponsabilidad:'', fechaCompromiso:'', valorAPagar:'', interesMoraMostrar:'', 
                                            descuentoAnticipado:'', valorDesAnticipado:'', interesMora:'', totalAPagar:''});
    const [pagoMensualidad, setPagoMensualidad] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);
    const [pagoGeneral, setPagoGeneral] = useState([]);
    const [dataFactura, setDataFactura] = useState('');
    const [pagoTotal, setPagoTotal] = useState('N');
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);    

    const handleChangeRadio = (event) => {
        setPagoTotal(event.target.value); 
        let newFormData = {...formData};

        if(event.target.value === 'S'){
            newFormData.idResponsabilidad   = pagoGeneral.idResponsabilidad;
            newFormData.fechaCompromiso     = pagoGeneral.fechaCompromiso;
            newFormData.valorAPagar         = pagoGeneral.valorAPagar;
            newFormData.interesMoraMostrar  = pagoGeneral.interesMoraMostrar;
            newFormData.descuentoAnticipado = pagoGeneral.descuentoAnticipado;
            newFormData.valorDesAnticipado  = pagoGeneral.valorDesAnticipado;
            newFormData.totalAPagarMostrar  = pagoGeneral.totalAPagarMostrar;
            newFormData.interesMora         = pagoGeneral.interesMora;
        }else{
            newFormData.idResponsabilidad   = pagoMensualidad.idResponsabilidad;
            newFormData.fechaCompromiso     = pagoMensualidad.fechaCompromiso;
            newFormData.valorAPagar         = pagoMensualidad.valorAPagar;
            newFormData.interesMoraMostrar  = pagoMensualidad.interesMoraMostrar;
            newFormData.descuentoAnticipado = pagoMensualidad.descuentoAnticipado;
            newFormData.totalAPagarMostrar  = pagoMensualidad.totalAPagarMostrar;
            newFormData.valorDesAnticipado  = pagoMensualidad.valorDesAnticipado;
            newFormData.totalAPagar         = pagoMensualidad.totalAPagar;
            newFormData.interesMora         = pagoMensualidad.interesMora;
        }
        setFormData(newFormData);
    }

    const consultarVehiculo = () =>{
        setDatosEncontrados(false);
        if(formData.vehiculoId === ''){
            showSimpleSnackbar("Debe seleccionar un vehículo", 'error');
            return;
        }

        setLoader(true); 
        let newFormData = {...formData};
        instance.post('/admin/caja/consultar/vehiculo', {vehiculoId: formData.vehiculoId}).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{
                setPagoGeneral(res.pagoTotal[0]);
                setPagoMensualidad(res.pagoMensualidad[0]);
                setDatosEncontrados(true);
                let pagoMensualidad             = res.pagoMensualidad[0];
                newFormData.idResponsabilidad   = pagoMensualidad.idResponsabilidad;
                newFormData.fechaCompromiso     = pagoMensualidad.fechaCompromiso;
                newFormData.valorAPagar         = pagoMensualidad.valorAPagar;
                newFormData.interesMoraMostrar  = pagoMensualidad.interesMoraMostrar;
                newFormData.descuentoAnticipado = pagoMensualidad.descuentoAnticipado;
                newFormData.totalAPagarMostrar  = pagoMensualidad.totalAPagarMostrar;
                newFormData.valorDesAnticipado  = pagoMensualidad.valorDesAnticipado;
                newFormData.interesMora         = pagoMensualidad.interesMora;
                newFormData.totalAPagar         = pagoMensualidad.totalAPagar;
                setFormData(newFormData);
                setPagoTotal('N');
            }
            setLoader(false);
        })
    }

    const registrarPago = () =>{
        setLoader(true);
        let newFormData       = {...formData}
        newFormData.pagoTotal = pagoTotal;
        instance.post('/admin/caja/registrar/mensualidad', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setDatosEncontrados(false) : null;
            (res.success) ? setFormData({vehiculoId:'', idResponsabilidad:'', fechaCompromiso:'', valorAPagar:'', interesMoraMostrar:'', 
                                        descuentoAnticipado:'', valorDesAnticipado:'', interesMora:'', totalAPagar:''}) : null;
            (res.success) ? setDataFactura(res.dataFactura) : null;
            (res.success) ? setAbrirModal(true) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/caja/listar/vehiculos').then(res=>{
            setVehiculos(res.vehiculos); 
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={consultarVehiculo}>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>
                            <Grid item xl={9} md={9} sm={8} xs={8}>
                                <Autocomplete
                                    id="vehiculo"
                                    style={{height: "26px", width: "100%"}}
                                    options={vehiculos}
                                    getOptionLabel={(option) => option.nombreVehiculo} 
                                    value={vehiculos.find(v => v.vehiid === formData.vehiculoId) || null}
                                    filterOptions={createFilterOptions({ limit:10 })}
                                    onChange={(event, newInputValue) => {
                                        if(newInputValue){
                                            setFormData({...formData, vehiculoId: newInputValue.vehiid})
                                        }
                                    }}
                                    renderInput={(params) =>
                                        <TextValidator {...params}
                                            label="Consultar vehículo"
                                            className="inputGeneral"
                                            variant="standard"
                                            validators={["required"]}
                                            errorMessages="Campo obligatorio"
                                            value={formData.vehiculoId}
                                            placeholder="Consulte el vehículo aquí..." />}
                                />
                            </Grid> 

                            <Grid item xl={3} md={3} sm={4} xs={4}>
                                <Stack direction="row" spacing={2} >
                                    <Button type={"submit"} className={'modalBtnBuscar'}
                                        startIcon={<SearchIcon className='icono' />}> Consultar
                                    </Button>
                                </Stack>
                            </Grid>

                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            {(datosEncontrados) ?
                <Box style={{marginTop: '2em'}}>
                    <Card style={{margin: 'auto', width:'70%', padding: '5px'}}>
                        <Grid container spacing={2} >

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
                                    <label>Fecha compromiso: </label>
                                    <span >{'\u00A0'+ formData.fechaCompromiso}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Valor a pagar</label>
                                    <span className='textoRojo' ><span className='textoGris'>$</span> {'\u00A0'+ formData.valorAPagar}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Interés mora </label>
                                    <span className='textoRojo'><span className='textoGris'>$</span> {'\u00A0'+formData.interesMoraMostrar}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Descuento anticipado</label>
                                    <span className='textoRojo'> <span className='textoGris'>$</span> {'\u00A0'+formData.descuentoAnticipado}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Total a pagar</label>
                                     <span className='textoRojo'> <span className='textoGris'>$</span> {'\u00A0'+formData.totalAPagarMostrar}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}></Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Stack direction="row" spacing={2}>
                                    <Button type={"button"} className={'modalBtn'}  onClick={registrarPago}
                                        startIcon={<SaveIcon />}> Guardar
                                    </Button>
                                </Stack>
                            </Grid>

                        </Grid>
                    </Card>
                </Box>
            : null }

            <ModalDefaultAuto
                title   = {'Visualizar factura en PDF de la mensualidad'} 
                content = {<VisualizarPdf dataFactura={dataFactura} />} 
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'smallFlot'
                abrir   = {abrirModal}
            />

        </Fragment>
    )
}
import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import { Button, Grid, Stack, Autocomplete, createFilterOptions, Box, Card} from '@mui/material';
import { Radio, RadioGroup, FormControlLabel, FormControl, FormLabel} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
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
                                            descuentoAnticipado:'', valorDesAnticipado:'', interesMora:'', totalAPagar:'', totalAbono:''});
    const [pagoMensualidad, setPagoMensualidad] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);
    const [pagoGeneral, setPagoGeneral] = useState([]);
    const [dataFactura, setDataFactura] = useState('');
    const [formaPago, setFormaPago] = useState('M');
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeRadio = (event) => {
        setFormaPago(event.target.value);
        let newFormData = {...formData};

        if(event.target.value === 'T'){
            newFormData.idResponsabilidad   = pagoGeneral.idResponsabilidad;
            newFormData.fechaCompromiso     = pagoGeneral.fechaCompromiso;
            newFormData.valorAPagar         = pagoGeneral.valorAPagar;
            newFormData.valorAPagarMostrar  = pagoGeneral.valorAPagarMostrar;
            newFormData.interesMoraMostrar  = pagoGeneral.interesMoraMostrar;
            newFormData.descuentoAnticipado = pagoGeneral.descuentoAnticipado;
            newFormData.valorDesAnticipado  = pagoGeneral.valorDesAnticipado;
            newFormData.totalAPagarMostrar  = pagoGeneral.totalAPagarMostrar;
            newFormData.totalAPagar         = pagoGeneral.totalAPagar;
            newFormData.interesMora         = pagoGeneral.interesMora;
            newFormData.totalAbono          = pagoGeneral.totalAbono; 
        }

        if(event.target.value === 'M'){
            newFormData.idResponsabilidad   = pagoMensualidad.idResponsabilidad;
            newFormData.fechaCompromiso     = pagoMensualidad.fechaCompromiso;
            newFormData.valorAPagar         = pagoMensualidad.valorAPagar;
            newFormData.valorAPagarMostrar  = pagoMensualidad.valorAPagarMostrar;
            newFormData.interesMoraMostrar  = pagoMensualidad.interesMoraMostrar;
            newFormData.descuentoAnticipado = pagoMensualidad.descuentoAnticipado;
            newFormData.valorDesAnticipado  = pagoMensualidad.valorDesAnticipado;
            newFormData.totalAPagarMostrar  = pagoMensualidad.totalAPagarMostrar;
            newFormData.totalAPagar         = pagoMensualidad.totalAPagar;
            newFormData.interesMora         = pagoMensualidad.interesMora;
            newFormData.totalAbono          = pagoMensualidad.totalAbono;            
        }

        if(event.target.value === 'P'){
            newFormData.totalAPagar         = pagoMensualidad.totalAPagar.toString();
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
                newFormData.valorAPagarMostrar  = pagoMensualidad.valorAPagarMostrar;
                newFormData.interesMoraMostrar  = pagoMensualidad.interesMoraMostrar;
                newFormData.descuentoAnticipado = pagoMensualidad.descuentoAnticipado;
                newFormData.totalAPagarMostrar  = pagoMensualidad.totalAPagarMostrar;
                newFormData.valorDesAnticipado  = pagoMensualidad.valorDesAnticipado;
                newFormData.interesMora         = pagoMensualidad.interesMora;
                newFormData.totalAPagar         = pagoMensualidad.totalAPagar;
                newFormData.totalAbono          = pagoMensualidad.totalAbono; 
                setFormData(newFormData);
                setFormaPago('M');
            }
            setLoader(false);
        })
    }

    const registrarPago = () =>{
        setLoader(true);
        let newFormData       = {...formData}
        newFormData.formaPago = formaPago;
        instance.post('/admin/caja/registrar/mensualidad', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setDatosEncontrados(false) : null;
            (res.success) ? setFormData({vehiculoId:'', idResponsabilidad:'', fechaCompromiso:'', valorAPagar:'', interesMoraMostrar:'', 
                                        descuentoAnticipado:'', valorDesAnticipado:'', interesMora:'', totalAPagar:'', totalAbono:''}) : null;
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
                    <Card className={'cardContainer'} >
                        <Grid container spacing={2}>
                            <Grid item xl={9} md={9} sm={8} xs={8} style={{padding: '8px'}}>
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
                                    <Button type={"submit"} className={'modalBtnIcono'}
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
                    <ValidatorForm onSubmit={registrarPago}>
                        <Card style={{margin: 'auto', width:'70%', padding: '5px'}}>
                            <Grid container spacing={2} >

                            <Grid item md={5} xl={5} sm={6} xs={12} >
                                    <FormControl>
                                        <FormLabel className='labelRadio'>Forma de pago</FormLabel>
                                            <RadioGroup
                                                row
                                                name="formaPago"
                                                value={formaPago}
                                                onChange={handleChangeRadio}
                                            >
                                            <FormControlLabel value="M" control={<Radio color="success"/>} label="Mensual" />
                                            <FormControlLabel value="P" control={<Radio color="success"/>} label="Parcial" />
                                            <FormControlLabel value="T" control={<Radio color="success"/>} label="Total" />
                                        </RadioGroup>
                                    </FormControl>
                                </Grid>

                                <Grid item md={3} xl={3} sm={6} xs={12} >
                                    <Box className='frmTexto'>
                                        <label>Fecha compromiso: </label>
                                        <span >{'\u00A0'+ formData.fechaCompromiso}</span>
                                    </Box>
                                </Grid>

                                {(formaPago === 'P') ?
                                    <Fragment>
                                        <Grid item xl={4} md={4} sm={6} xs={12}>
                                            <NumberValidator fullWidth
                                                id={"totalAPagar"}
                                                name={"totalAPagar"}
                                                label={"Total a pagar"}
                                                value={formData.totalAPagar}
                                                type={'numeric'}
                                                require={['required', 'maxStringLength:9']}
                                                error={['Campo obligatorio','Número máximo permitido es el 999999999']}
                                                onChange={handleChange}
                                            />
                                        </Grid>

                                        <Grid item xl={4} md={4} sm={6} xs={12}>
                                            <Box className='frmTexto'>
                                                <label>Total en abono</label>
                                                <span className='textoRojo' ><span className='textoGris'>$</span> {'\u00A0'+ formData.totalAbono}</span>
                                            </Box>
                                        </Grid>

                                    </Fragment>
                                :
                                    <Fragment>
                                        <Grid item xl={4} md={4} sm={6} xs={12}>
                                            <Box className='frmTexto'>
                                                <label>Valor a pagar</label>
                                                <span className='textoRojo' ><span className='textoGris'>$</span> {'\u00A0'+ formData.valorAPagarMostrar}</span>
                                            </Box>
                                        </Grid>

                                        {(formData.interesMoraMostrar > 0) ? 
                                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                                <Box className='frmTexto'>
                                                    <label>Interés mora </label>
                                                    <span className='textoRojo'><span className='textoGris'>$</span> {'\u00A0'+formData.interesMoraMostrar}</span>
                                                </Box>
                                            </Grid>
                                        : null}

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
                                    </Fragment>
                                 }

                            </Grid>

                            <Grid container direction="row"  justifyContent="right">
                                <Stack direction="row" spacing={2}>
                                    <Button type={"submit"} className={'modalBtn'} 
                                        startIcon={<SaveIcon />}> Guardar
                                    </Button>
                                </Stack>
                            </Grid>

                        </Card>
                    </ValidatorForm>
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
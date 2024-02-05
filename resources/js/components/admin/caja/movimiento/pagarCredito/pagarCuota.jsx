import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Radio, RadioGroup, FormControlLabel, FormControl, FormLabel} from '@mui/material';
import {Button, Grid, Stack, Box, MenuItem, Card} from '@mui/material';
import showSimpleSnackbar from '../../../../layout/snackBar';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function PagarCuota({data}){
    const [formData, setFormData] = useState({idResponsabilidad:'', fechaCuota:'', valorAPagar:'', interesMoraMostrar:'', 
                                            descuentoAnticipado:'', valorDesAnticipado:'', interesMora:'', totalAPagar:'', totalAPagarMostrar:''});

    const [entidadFinancieras, setEntidadFinancieras] = useState([]);
    const [loader, setLoader]         = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [pagoTotal, setPagoTotal] = useState('N');

    const handleChangeRadio = (event) => {
        setPagoTotal(event.target.value); 
        let newFormData = {...formData};

    }  

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/caja/registrar/pago/cuota', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null; 
            //(res.success) ? setFormData({entidadFinaciera:'', monto: '', descripcion: ''}) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/caja/calcular/valor/cuota', {colocacionId: data.coloid}).then(res=>{

            //setEntidadFinancieras(res.entidadFinancieras);
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
                            <label>Valor solicitado</label>
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

                </Grid>

                <Grid container direction="row" justifyContent="right">
                    <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                            startIcon={<SaveIcon />}> Guardar
                        </Button>
                    </Stack>
                </Grid>
            </ValidatorForm>
        </Fragment>
    )
}
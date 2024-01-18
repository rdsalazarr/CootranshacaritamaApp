import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import { Button, Grid, Stack, Icon, Autocomplete, createFilterOptions, Box, Card} from '@mui/material';
import { Radio, RadioGroup, FormControlLabel, FormControl, FormLabel} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function Mensualidad(){

    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [formData, setFormData] = useState({vehiculoId:'', fechaCompromiso:'', valorAPagar:'', interesMora:'', descuentoAnticipado:'', totalAPagar:''});
    const [pagoTotal, setPagoTotal] = useState('N');
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);    

    const handleChangeRadio = (event) => {
        setPagoTotal(event.target.value); 
    }

    const consultarVehiculo = () =>{
        setDatosEncontrados(false);
        if(formData.vehiculoId === ''){
            showSimpleSnackbar("Debe seleccionar un vehículo", 'error');
            return;
        }

        setLoader(true); 
        instance.post('/admin/caja/consultar/vehiculo', {vehiculoId: formData.vehiculoId}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            ( res.success) ? setDatosEncontrados(true) : null; 
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
                            <Grid item xl={11} md={11} sm={10} xs={9}>
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
                                <br />
                            </Grid>

                            <Grid item xl={1} md={1} sm={2} xs={3} sx={{position: 'relative'}}>
                                <Icon className={'iconLupa'} onClick={consultarVehiculo}>search</Icon>
                                <br />
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
                                    <span className='textoRojo' >{formData.valorAPagar}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Interés mora</label>
                                    <span >{formData.interesMora}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Descuento anticipado</label>
                                    <span >{formData.descuentoAnticipado}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Total a pagar</label>
                                    <span className='textoRojo'>{formData.totalAPagar}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Stack direction="row" spacing={2}>
                                    <Button type={"submit"} className={'modalBtn'} 
                                        startIcon={<SaveIcon />}> Guardar
                                    </Button>
                                </Stack>
                            </Grid>

                        </Grid> 
                    </Card>
                </Box>
            : null }
        </Fragment>
    )
}
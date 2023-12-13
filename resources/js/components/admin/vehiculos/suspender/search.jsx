import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import { Button, Card, Grid, Typography, Stack, Box, Autocomplete, createFilterOptions} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function Search(){

    const [formData, setFormData] = useState({vehiculoId:'', fechaInicialSuspencion:'', fechaFinalSuspencion: '', motivo: ''}); 
    const [loader, setLoader] = useState(false);
    const [vehiculos, setVehiculos] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/direccion/transporte/suspender/vehiculo/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setFormData({vehiculoId:'', fechaInicialSuspencion:'', fechaFinalSuspencion: '', motivo: ''}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.get('/admin/direccion/transporte/suspender/vehiculo/datos').then(res=>{
            newFormData.fechaInicialSuspencion = res.fechaActual;
            newFormData.fechaFinalSuspencion = res.fechaActual;
            setVehiculos(res.data); 
            setFormData(newFormData);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Card className={'cardContainer'} >
                <Box>
                    <Typography component={'h2'} className={'titleGeneral'} style={{marginBottom: '1em'}}>Gestionar suspención de vehículo</Typography>
                </Box>
                <Grid container spacing={2}>

                    <Grid item xl={6} md={6} sm={6} xs={12}>
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
   
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'fechaInicialSuspencion'}
                            value={formData.fechaInicialSuspencion}
                            label={'Fecha inicial de la suspención'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                            type={"date"}
                            InputLabelProps={{
                                shrink: true,
                            }}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'fechaFinalSuspencion'}
                            value={formData.fechaFinalSuspencion}
                            label={'Fecha final de suspención'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                            type={"date"}
                            InputLabelProps={{
                                shrink: true,
                            }}
                        />
                    </Grid>

                    <Grid item xl={12} md={12} sm={6} xs={12}>
                        <TextValidator
                            multiline
                            maxRows={3}
                            name={'motivo'}
                            value={formData.motivo}
                            label={'Motivo'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 500}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChangeUpperCase}
                        />
                    </Grid>
                </Grid>

                <Grid container direction="row" justifyContent="right" style={{marginTop:'1em'}}>
                    <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'}
                            startIcon={<SaveIcon />}> Guardar
                        </Button>
                    </Stack>
                </Grid>

            </Card>
        </ValidatorForm>
    )
}
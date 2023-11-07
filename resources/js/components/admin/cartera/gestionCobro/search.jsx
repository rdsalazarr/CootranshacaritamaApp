import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import {Grid, Icon, Box, Typography, Card, Autocomplete, createFilterOptions, Tab, Tabs} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';

export default function Search(){

    const [formData, setFormData] = useState({vehiculoId:'', fechaInicial:'', fechaFinal:'', placa:'', numeroInterno:''})
    const [loader, setLoader] = useState(false);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [vehiculos, setVehiculos] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}));
    }

    const inicio = () =>{
        /*setLoader(true);
        instance.get('/admin/direccion/transporte/listar/vehiculos').then(res=>{
            setVehiculos(res.data); 
            setLoader(false);
        })*/
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={consultarVehiculo}>
                <Box><Typography component={'h2'} className={'titleGeneral'}>Gestión de cobro de cartera</Typography>
                </Box>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>

                            <Grid item xl={3} md={3} sm={6} xs={12}> 
                                <TextValidator
                                    name={'fechaInicial'}
                                    value={formData.fechaInicial}
                                    label={'Fecha inicial'}
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
                                    name={'fechaFinal'}
                                    value={formData.fechaFinal}
                                    label={'Fecha inicial'}
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
                                    name={'placa'}
                                    value={formData.placa}
                                    label={'Placa'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 8}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChangeUpperCase}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'numeroInterno'}
                                    value={formData.numeroInterno}
                                    label={'Número interno'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:9999"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 9999"]}
                                    onChange={handleChange}
                                    type={"number"}
                                />
                            </Grid>

                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

        </Fragment>
    )
}
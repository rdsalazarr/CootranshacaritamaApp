import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import {Grid, Button, Icon, Box, Typography, Card, Autocomplete, createFilterOptions, Tab, Tabs} from '@mui/material';
import showSimpleSnackbar from '../../../../layout/snackBar';
import TablaGeneral from '../../../../layout/tablaGeneral';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';

export default function Search(){

    const [formData, setFormData] = useState({vehiculoId:'', fechaInicial:'', fechaFinal:'', placa:'', numeroInterno:''})
    const [loader, setLoader] = useState(false);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [vehiculos, setVehiculos] = useState([]);
    const [data, setData] = useState([]);    

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}));
    }

    const edit = (data, tipo) =>{
     
        //setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 ) ? 'smallFlot' :  'mediumFlot'});
    }

    const descargarFile = () =>{
        /*setLoader(true);
        instanceFile.post('/api/Informes/excel/postDescargarInformeEncuesta', dataForm(formData)).then(res=>{
            setLoader(false);
        })*/
      } 

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/cartera/gestionar/cobro/cartera').then(res=>{
            setData(res.data); 
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <Box >
                <ValidatorForm onSubmit={inicio}> 
                    <Box><Typography component={'h2'} className={'titleGeneral'}>Gestión de cobro de cartera</Typography>
                    </Box>

                    <Card style={{padding: '5px', width: '80%', margin: 'auto', marginTop: '1em' }}>
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

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'anioColocacion'}
                                    value={formData.anioColocacion}
                                    label={'Año de colocación'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:9999"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 9999"]}
                                    onChange={handleChange}
                                    type={"number"}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'numeroColocacion'}
                                    value={formData.numeroColocacion}
                                    label={'Número de colocación'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:9999"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 9999"]}
                                    onChange={handleChange}
                                    type={"number"}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'documento'}
                                    value={formData.documento}
                                    label={'Documento'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 15}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChange}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12} style={{textAlign: 'center'}}>
                                <Button type={"submit"} >Consultar</Button>
                            </Grid>

                        </Grid>

                    </Card>
                </ValidatorForm>

                <Grid container spacing={2} >
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

                    <Grid item md={12} xl={12} sm={12} style={{marginTop: '-1em'}}>
                        <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                            <TablaGeneral
                                datos={data}
                                titulo={['Documento','Nombre asociado','Número de colocacion','Fecha desembolso','Valor', 'Vehículo', 'Placa', 'Número interno', 'Hacer seguimiento']}
                                ver={["persdocumento","nombreAsociado","numeroColocacion","colofechadesembolso","colovalordesembolsado", "referenciaVehiculo", "vehiplaca", "vehinumerointerno"]}
                                accion={[
                                    {tipo: 'B', icono : 'edit',   color: 'orange', funcion : (data)=>{edit(data,0)} }
                                ]}
                                funciones={{orderBy: true, search: true, pagination:true}}
                            />
                        </Box>
                    </Grid>
                </Grid>

            </Box> 

        </Fragment>
    )
}
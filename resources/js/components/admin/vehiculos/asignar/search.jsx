import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import {Grid, Icon, Box, Typography, Card, Autocomplete, createFilterOptions, Tab, Tabs} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import { TabPanel } from '../../../layout/general';
import {LoaderModal} from "../../../layout/loader";
import TarjetaOperacion from './tarjetaOperacion';
import instance from '../../../layout/instance';
import Conductores from './conductores';
import Contratos from './contratos';
import Vehiculo from './vehiculo';
import Polizas from './polizas';
import Soat from './soat';
import Crt from './crt';

export default function Search(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [formData, setFormData] = useState({vehiculoId:''})
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false); 
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    }

    const consultarVehiculo = () =>{
        setDatosEncontrados(false);
        if(formData.vehiculoId === ''){
            showSimpleSnackbar("Debe seleccionar un vehículo", 'error');
            return;
        }
        setLoader(true);
        setInterval(() => {recargarPagina(); }, 400)
        setDatosEncontrados(true);
    }

    const recargarPagina = () =>{
        setDatosEncontrados(true);
        setLoader(false);
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/direccion/transporte/listar/vehiculos').then(res=>{
            setVehiculos(res.data); 
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
                <Box><Typography component={'h2'} className={'titleGeneral'}>Asignar vehículos</Typography>
                </Box>
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
               <Grid container spacing={2}  style={{marginTop: '2em'}}>
                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Tabs value={value} onChange={handleChangeTab} 
                            sx={{background: '#e2e2e2'}}
                            indicatorColor="secondary"
                            textColor="secondary"
                            variant={variantTab} >
                            <Tab label="Informacion" />
                            <Tab label="Contratos" />
                            <Tab label="Conductores" />
                            <Tab label="Soat" />
                            <Tab label="CRT" />
                            <Tab label="Polizas" />
                            <Tab label="Tarjeta de operación" />
                        </Tabs>

                        <TabPanel value={value} index={0}>
                            <Vehiculo id={formData.vehiculoId}/>
                        </TabPanel>
    
                        <TabPanel value={value} index={1}>
                            <Contratos id={formData.vehiculoId}/>
                        </TabPanel>
    
                        <TabPanel value={value} index={2}>
                            <Conductores id={formData.vehiculoId}/>
                        </TabPanel>
    
                        <TabPanel value={value} index={3}>
                            <Soat id={formData.vehiculoId}/>
                        </TabPanel>
    
                        <TabPanel value={value} index={4}>
                            <Crt id={formData.vehiculoId} />
                        </TabPanel>
    
                        <TabPanel value={value} index={5}>
                            <Polizas id={formData.vehiculoId} />
                        </TabPanel>

                        <TabPanel value={value} index={6}>
                            <TarjetaOperacion id={formData.vehiculoId} />
                        </TabPanel>
    
                    </Grid>
                </Grid>
            : null }

        </Fragment>
    )
}
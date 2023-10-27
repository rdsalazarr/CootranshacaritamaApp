import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import {Grid, Icon, Box, Typography, Card, Autocomplete, createFilterOptions, Tab, Tabs} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import { TabPanel } from '../../../layout/general';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Conductores from './conductores';
import Asociados from './asociados';
import Polizas from './polizas';
import Soat from './soat';
import Crt from './crt';

export default function Search(){

    const [formData, setFormData] = useState({vehiculo:''})
    const [loader, setLoader] = useState(false);
    const [datosEncontrados, setDatosEncontrados] = useState(false);    
    const [vehiculos, setVehiculos] = useState([]);
    
    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    const consultarVehiculo = () =>{
        setDatosEncontrados(false);
        instance.post('/admin/direccion/transporte/consultar/asignacion/vehiculo', formData).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{
                setDatosEncontrados(true);
            }
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/direccion/transporte/listar/vehiculos').then(res=>{
            setVehiculos(res.tipoIdentificaciones);           
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
                            <Grid item xl={12} md={12} sm={12} xs={12} sx={{position: 'relative'}}>                                            
                                <Autocomplete
                                    id="vehiculo"
                                    style={{height: "26px"}}
                                    options={vehiculos}
                                    freeSolo
                                    getOptionLabel={(option) => option.tipidenombre} 
                                    value={vehiculos.find(v => v.tipideid === formData.vehiculo) || null}
                                    filterOptions={createFilterOptions({ limit:10 })}
                                    onChange={(event, newInputValue) => {
                                        if(newInputValue){
                                            setFormData({...formData, lugar: newInputValue.tipideid})
                                        }
                                    }}
                                    renderInput={(params) =>
                                        <TextValidator {...params}
                                            label="Consultar vehículo"
                                            className="inputGeneral"
                                            variant="standard"
                                            validators={["required"]}
                                            errorMessages="Campo obligatorio"
                                            value={formData.vehiculo}
                                            placeholder="Consulte el vehículo aquí..." />}
                                />                            
                                <Icon className={'iconLupa'} onClick={consultarVehiculo}>search</Icon>
                                <br />
                            </Grid>
                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            <Grid container spacing={2}  style={{marginTop: '2em'}}>
                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Tabs value={value} onChange={handleChangeTab} 
                        sx={{background: '#e2e2e2'}}
                        indicatorColor="secondary"
                        textColor="secondary"
                        variant={variantTab} >
                        <Tab label="Asociados" />
                        <Tab label="Conductores" />
                        <Tab label="Soat" />
                        <Tab label="CRT" />
                        <Tab label="Polizas" />
                    </Tabs>

                    <TabPanel value={value} index={0}>
                        <Asociados id={1} />
                    </TabPanel>

                    <TabPanel value={value} index={1}>
                        <Conductores id={1} />
                    </TabPanel>

                    <TabPanel value={value} index={2}>
                        <Soat id={1} />
                    </TabPanel>

                    <TabPanel value={value} index={3}>
                        <Crt id={1} />
                    </TabPanel>

                    <TabPanel value={value} index={4}>
                        <Polizas id={1} />
                    </TabPanel>

                </Grid>
            </Grid>         

            {(datosEncontrados) ? 
                <div></div>
            : null }

        </Fragment>
    )
}
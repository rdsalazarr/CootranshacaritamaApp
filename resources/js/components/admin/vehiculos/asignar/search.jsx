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

    const [formData, setFormData] = useState({vehiculoId:''})
    const [formDataConsulta, setFormDataConsulta] = useState({vehiculoId:''})
    const [loader, setLoader] = useState(false);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [vehiculos, setVehiculos] = useState([]);
    const [asociadoVehiculos, setAsociadoVehiculos] = useState([]);
    const [conductoresVehiculo, setConductoresVehiculo] = useState([]);    
    const [soatVehiculo, setSoatVehiculos] = useState([]);
    const [crtVehiculo, setCrtVehiculo] = useState([]);
    const [polizasVehiculo, setPolizasVehiculos] = useState([]);
    
    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    }

    const consultarVehiculo = () =>{
        if(formData.vehiculoId === ''){
            showSimpleSnackbar("Debe seleccionar un vehículo", 'error');
            return;
        }
        let newFormDataConsulta = {...formDataConsulta}
        setDatosEncontrados(false);
        instance.post('/admin/direccion/transporte/consultar/asignacion/vehiculo', formData).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{
                setDatosEncontrados(true);
                newFormDataConsulta.vehiculoId = formData.vehiculoId
                setAsociadoVehiculos(res.asociadoVehiculos);
                setConductoresVehiculo(res.conductoresVehiculo);
                setSoatVehiculos(res.soatVehiculo);
                setCrtVehiculo(res.crtVehiculo);
                setPolizasVehiculos(res.polizasVehiculo);
                setFormDataConsulta(newFormDataConsulta);
            }
            setLoader(false);
        })
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
                            <Tab label="Asociados" />
                            <Tab label="Conductores" />
                            <Tab label="Soat" />
                            <Tab label="CRT" />
                            <Tab label="Polizas" />
                        </Tabs>
    
                        <TabPanel value={value} index={0}>
                            <Asociados id={formDataConsulta.vehiculoId} data={asociadoVehiculos} />
                        </TabPanel>
    
                        <TabPanel value={value} index={1}>
                            <Conductores id={formDataConsulta.vehiculoId} data={conductoresVehiculo} />
                        </TabPanel>
    
                        <TabPanel value={value} index={2}>
                            <Soat id={formDataConsulta.vehiculoId} data={soatVehiculo} />
                        </TabPanel>
    
                        <TabPanel value={value} index={3}>
                            <Crt id={formDataConsulta.vehiculoId} data={crtVehiculo} />
                        </TabPanel>
    
                        <TabPanel value={value} index={4}>
                            <Polizas id={formDataConsulta.vehiculoId} data={polizasVehiculo} />
                        </TabPanel>
    
                    </Grid>
                </Grid>
            : null }

        </Fragment>
    )
}
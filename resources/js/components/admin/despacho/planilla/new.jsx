import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator';
import { Button, Grid, Stack, Autocomplete, createFilterOptions} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';

import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DateTimePicker } from '@mui/x-date-pickers';
import TextField from '@mui/material/TextField';
import "/resources/scss/fechaDatePicker.scss";
import esLocale from 'dayjs/locale/es';
import dayjs from 'dayjs';
import 'dayjs/locale/es';

export default function New({data, tipo}){

    const [formData, setFormData]     = useState(
                        (tipo !== 'I') ? {codigo:data.plarutid, ruta:data.rutaid, vehiculo: data.vehiid, conductor: data.condid, 
                            fechaHoraSalida:data.plarutfechahorasalida,  tipo:tipo 
                        } : {codigo:'000', ruta: '', vehiculo:'', conductor:'', fechaHoraSalida:'', tipo:tipo
                    });
     
    const [conductoresVehiculos, setConductoresVehiculos] = useState([]);
    const [fechaActual, setFechaActual] = useState(new Date());
    const [fechaMinima, setFechaMinima] = useState(dayjs());
    const [habilitado, setHabilitado] = useState(true);
    const [conductores, setConductores] = useState([]);
    const [vehiculos, setVehiculos] = useState([]);  
    const [loader, setLoader] = useState(false);
    const [rutas, setRutas] = useState([]);

    const handleChangeDate = (e) => {
        setFormData((prevData) => ({...prevData, fechaHoraSalida: formatearFechaHora(e)}));
    };

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const filtrarConductoresVehiculo = (vehiculoId) =>{
        const conductoresFiltrados = conductores.filter(conductor => conductor.vehiid === vehiculoId);
        setConductoresVehiculos(conductoresFiltrados);
        let newFormData       = {...formData}
        newFormData.vehiculo  = vehiculoId;
        newFormData.conductor = '';
        setFormData(newFormData);
    }

    const formatearFechaHora = (date) =>{
        let fecha    = new Date(date);
        let anio     = fecha.getFullYear();
        let mes      = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Agregar 1 porque los meses comienzan desde 0
        let dia      = fecha.getDate().toString().padStart(2, '0');
        let horas    = fecha.getHours().toString().padStart(2, '0');
        let minutos  = fecha.getMinutes().toString().padStart(2, '0');
        let segundos = fecha.getSeconds().toString().padStart(2, '0');

        return  `${anio}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/despacho/planilla/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', ruta: '', vehiculo:'', conductor:'', fechaHoraSalida:fechaActual, tipo:tipo}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/despacho/planilla/listar/datos', {codigo:formData.codigo, tipo:tipo}).then(res=>{
            (tipo === 'I') ? setFechaActual(dayjs(res.fechaActual, 'YYYY-MM-DD HH')): null;
            (tipo === 'I') ? setFechaMinima(dayjs(res.fechaActual, 'YYYY-MM-DD')): null;
            newFormData.fechaHoraSalida = res.fechaActual;
            setConductores(res.conductores);

            if(tipo === 'U'){
                const conductoresFiltrados = res.conductores.filter(conductor => conductor.vehiid === formData.vehiculo);
                setConductoresVehiculos(conductoresFiltrados);
            }

            setVehiculos(res.vehiculos);
            setFormData(newFormData);
            setRutas(res.rutas);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Grid container spacing={2}>
                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <Autocomplete
                        id="ruta"
                        style={{height: "26px", width: "100%"}}
                        options={rutas}
                        getOptionLabel={(option) => option.nombreRuta}
                        value={rutas.find(v => v.rutaid === formData.ruta) || null}
                        filterOptions={createFilterOptions({ limit:10 })}
                        onChange={(event, newInputValue) => {
                            if(newInputValue){
                                setFormData({...formData, ruta: newInputValue.rutaid})
                            }
                        }}
                        renderInput={(params) =>
                            <TextValidator {...params}
                                label="Consultar ruta"
                                className="inputGeneral"
                                variant="standard"
                                value={formData.ruta}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                placeholder="Consulte la ruta aquí..." />}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12} >
                    <Autocomplete
                        id="vehiculo"
                        style={{height: "26px", width: "100%"}}
                        options={vehiculos}
                        getOptionLabel={(option) => option.nombreVehiculo}
                        value={vehiculos.find(v => v.vehiid === formData.vehiculo) || null}
                        filterOptions={createFilterOptions({ limit:10 })}
                        onChange={(event, newInputValue) => {
                            if(newInputValue){
                                filtrarConductoresVehiculo(newInputValue.vehiid)
                            }
                        }}
                        renderInput={(params) =>
                            <TextValidator {...params}
                                label="Consultar vehículo"
                                className="inputGeneral"
                                variant="standard"
                                value={formData.vehiculo}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                placeholder="Consulte el vehículo aquí..." />}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12} style={{marginTop:'1.8em'}}>
                    <Autocomplete
                        id="conductor"
                        style={{height: "26px", width: "100%"}}
                        options={conductoresVehiculos}
                        getOptionLabel={(option) => option.nombreConductor}
                        value={conductoresVehiculos.find(v => v.condid === formData.conductor) || null}
                        filterOptions={createFilterOptions({ limit:10 })}
                        onChange={(event, newInputValue) => {
                            if(newInputValue){
                                setFormData({...formData, conductor: newInputValue.condid})
                            }
                        }}
                        renderInput={(params) =>
                            <TextValidator {...params}
                                label="Consultar conductor"
                                className="inputGeneral"
                                variant="standard"
                                value={formData.conductor}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                placeholder="Consulte el conductor aquí..." />}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12} style={{marginTop:'1.4em'}}>
                    <LocalizationProvider dateAdapter={AdapterDateFns} locale={esLocale}>
                        <DateTimePicker
                            label="Fecha y hora de salida"
                            value={new Date(fechaActual)}
                            minDate={new Date(fechaMinima)}
                            renderInput={(props) => <TextField {...props} className={'inputGeneral'} />}
                            onChange={handleChangeDate}
                        />
                    </LocalizationProvider>
                </Grid>

            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    )
}
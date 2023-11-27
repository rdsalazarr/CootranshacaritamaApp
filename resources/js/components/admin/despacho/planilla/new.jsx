import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Autocomplete, createFilterOptions} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';

import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker, DateTimePicker } from '@mui/x-date-pickers';
import "/resources/scss/fechaDatePicker.scss";
import esLocale from 'dayjs/locale/es';
import dayjs from 'dayjs';
import 'dayjs/locale/es';

import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import TextField from '@mui/material/TextField';


export default function New({data, tipo}){

    const [formData, setFormData]     = useState(    
                        (tipo !== 'I') ? {codigo:data.plarutid, ruta:data.rutaid, vehiculo: data.vehiid, conductor: data.condid, 
                            fechaHoraSalida:data.plarutfechahorasalida,  tipo:tipo 
                        } : {codigo:'000', ruta: '', vehiculo:'', conductor:'', fechaHoraSalida:new Date(), tipo:tipo
                    });

    const [selectedDate, setSelectedDate] = useState(new Date());
    const [fechaMinima, setFechaMinima] = useState(dayjs());
    const [habilitado, setHabilitado] = useState(true);
    const [conductores, setConductores] = useState([]);
    const [fechaActual, setFechaActual] = useState('');
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);
    const [rutas, setRutas] = useState([]);
  
    const handleChangeDate = (date) => {
      setSelectedDate(date);
    };

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{ 
        setLoader(true);
        instance.post('/admin/despacho/planillas/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        instance.post('/admin/despacho/planillas/listar/datos', {codigo:formData.codigo, tipo:tipo}).then(res=>{
            (tipo === 'I') ? setFechaActual(res.fechaActual): null;
            (tipo === 'I') ? setFechaMinima(dayjs(res.fechaActual, 'YYYY-MM-DD')): null;
            setConductores(res.conductores);
            setVehiculos(res.vehiculos);
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
                                setFormData({...formData, vehiculo: newInputValue.vehiid})
                            }
                        }}
                        renderInput={(params) =>
                            <TextValidator {...params}
                                label="Consultar vehículo"
                                className="inputGeneral"
                                variant="standard"
                                value={formData.vehiculo}
                                placeholder="Consulte el vehículo aquí..." />}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12} style={{marginTop:'1em'}}>
                    <Autocomplete
                        id="conductor"
                        style={{height: "26px", width: "100%"}}
                        options={conductores}
                        getOptionLabel={(option) => option.nombreConductor}
                        value={conductores.find(v => v.condid === formData.conductor) || null}
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
                                placeholder="Consulte el conductor aquí..." />}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12} style={{marginTop:'1em'}}>
                    <LocalizationProvider dateAdapter={AdapterDateFns} locale={esLocale}>
                        <DateTimePicker
                            label="Fecha y hora del documento"
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
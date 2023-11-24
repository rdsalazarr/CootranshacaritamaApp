import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Icon, Table, TableHead, TableBody, TableRow, TableCell, Autocomplete, createFilterOptions} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import AddIcon from '@mui/icons-material/Add';


import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers';
import "/resources/scss/fechaDatePicker.scss";
import esLocale from 'dayjs/locale/es';
import dayjs from 'dayjs';
import 'dayjs/locale/es';


export default function New({data, tipo}){
    const [formData, setFormData]     = useState(    
                        (tipo !== 'I') ? {codigo:data.rutaid,  ruta:data.depaidorigen, vehiculo: data.muniidorigen, conductor: data.depaiddestino, 
                            fechaHoraSalida:data.muniiddestino,  tipo:tipo 
                        } : {codigo:'000', ruta: '', vehiculo:'', conductor:'', fechaHoraSalida:'', tipo:tipo
                    });

    const [planillaRuta, setPlanillaRuta] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [conductores, setConductores] = useState([]);
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);    
    const [rutas, setRutas] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeDate = (date) => {
        setFormData((prevData) => ({...prevData, fecha: date.format('YYYY-MM-DD')}));
    }

    const handleSubmit = () =>{ 
        setLoader(true);
        instance.post('/admin/despacho/ruta/salvar/datos/tiquete', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        instance.post('/admin/despacho/planillas/listar/datos', {codigo:formData.codigo, tipo:tipo}).then(res=>{
            setPlanillaRuta(res.planillaRuta);
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
                <Grid item xl={4} md={4} sm={4} xs={12}>
                    <SelectValidator
                        name={'vehiculo'}
                        value={formData.vehiculo}
                        label={'Ruta'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {vehiculos.map(res=>{
                            return <MenuItem value={res.vehiid} key={res.vehiid} >{res.nombreVehiculo}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={4} xs={12}>
                    <SelectValidator
                        name={'vehiculo'}
                        value={formData.vehiculo}
                        label={'Vehículo'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {vehiculos.map(res=>{
                            return <MenuItem value={res.vehiid} key={res.vehiid} >{res.nombreVehiculo}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={4} xs={12}>
                    <SelectValidator
                        name={'vehiculo'}
                        value={formData.vehiculo}
                        label={'Conductor'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {vehiculos.map(res=>{
                            return <MenuItem value={res.vehiid} key={res.vehiid} >{res.nombreVehiculo}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale={esLocale} >
                        <DatePicker 
                            label="Fecha del documento"
                            defaultValue={dayjs(fechaActual)}
                            views={['year', 'month', 'day']} 
                            minDate={fechaMinima}
                            className={'inputGeneral'} 
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
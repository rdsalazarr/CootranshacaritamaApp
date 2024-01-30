import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import Colocacion from '../show/colocacion';

export default function Seguimiento({id}){

    const [formData, setFormData] = useState({solicitudId:id, colocacionId:'', tipoEstado:'', tipoEstadoOld:'', observacionCambio:''})
    const [formDataColocacion, setFormDataColocacion] = useState({solicitudId:id, nombreUsuario:'', fechaDesembolso:'', estadoActual:'',
                                                                numeroPagare:'', valorDesembolsado:'', tasaNominal:'', numeroCuota:''});
    const [tipoEstadosColocacion, setTipoEstadosColocacion] = useState([]);    
    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }
    
    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/cartera/hacer/seguimiento/colocacion', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null; 
            (res.success) ? setFormData({solicitudId:id, colocacionId:'', tipoEstado:'', tipoEstadoOld:'', observacionCambio:''}) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData           = {...formData};
        let newFormDataColocacion = {...formDataColocacion};
        instance.post('/admin/cartera/show/colocacion', {codigo: id, tipo:'S'}).then(res=>{
            let colocacion                          = res.colocacion;
            newFormDataColocacion.nombreUsuario     = colocacion.nombreUsuario;
            newFormDataColocacion.fechaDesembolso   = colocacion.colofechahoradesembolso;
            newFormDataColocacion.estadoActual      = colocacion.tiesclnombre;
            newFormDataColocacion.numeroPagare      = colocacion.numeroColocacion;
            newFormDataColocacion.valorDesembolsado = colocacion.valorDesembolsado;
            newFormDataColocacion.tasaNominal       = colocacion.colotasa;
            newFormDataColocacion.numeroCuota       = colocacion.colonumerocuota;

            newFormData.colocacionId                = colocacion.coloid;
            newFormData.tipoEstado                  = colocacion.tiesclid;
            newFormData.tipoEstadoOld               = colocacion.tiesclid;

            setFormDataColocacion(newFormDataColocacion);
            setTipoEstadosColocacion(res.tipoEstadosColocacion);
            setFormData(newFormData);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Colocacion data={formDataColocacion} />
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Información del cambio realizado al crédito 
                    </Box>
                </Grid>

                <Grid item xl={9} md={9} sm={8} xs={12}>
                    <TextValidator 
                        name={'observacionCambio'}
                        value={formData.observacionCambio}
                        label={'Observación del cambio'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 500}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tipoEstado'}
                        value={formData.tipoEstado}
                        label={'Estado actual'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoEstadosColocacion.map(res=>{
                            return <MenuItem value={res.tiesclid} key={res.tiesclid}>{res.tiesclnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> Guardar
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    )
}
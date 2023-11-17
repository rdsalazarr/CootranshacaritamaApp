import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box} from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';

export default function Procesar({data}){

    const [formData, setFormData] = useState({codigo:data.persid,  fechaIngresoAsociado:'', fechaIngresoConductor:'', tipoConductor:'',
                             agencia:'', tipoCategoria:'', numeroLicencia:'', fechaExpedicionLicencia:'', fechaVencimiento:'', tipo:''}); 

    const [tipoCategoriaLicencias, setTipoCategoriaLicencias] = useState([]);
    const [mostrarMensaje, setMostrarMensaje] = useState(false);
    const [tipoConductores, setTipoConductores] = useState([]);
    const [mostrarSelect, setMostrarSelect] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [conductor, setConductor] = useState(false);
    const [asociado, setAsociado] = useState(false);
    const [agencias, setAgencias] = useState([]);
    const [loader, setLoader] = useState(false);     

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/persona/procesar', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null; 
            (res.success) ? setFormData({codigo:data.persid,  fechaIngresoAsociado:'', fechaIngresoConductor:'', tipoConductor:'',
                                        agencia:'', tipoCategoria:'', numeroLicencia:'', fechaExpedicionLicencia:'', fechaVencimiento:'', tipo:''}) : null;
            setLoader(false);
        })
    }

    const seleccionarFormulario = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}));
        setConductor((e.target.value === 'CONDUCTOR') ? true : false);
        setAsociado((e.target.value === 'ASOCIADO') ? true : false);
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/persona/consultar/asignacion', {codigo:data.persid}).then(res=>{
            let persona          = res.persona;
            let mostrarSelect    = (persona.totalConductor === 0 && persona.totalAsociado === 0) ? true : false;
            let mostrarConductor = (!mostrarSelect && persona.totalConductor === 0) ? true : false;
            let mostrarAsociado  = (!mostrarSelect && persona.totalAsociado === 0) ? true : false;
            let mostrarMensaje   = (!mostrarSelect && !mostrarConductor && !mostrarAsociado) ? true : false;
            setTipoCategoriaLicencias(res.tpCateLicencias);
            setTipoConductores(res.tipoConductores);
            setMostrarMensaje(mostrarMensaje);
            setMostrarSelect(mostrarSelect);
            setConductor(mostrarConductor);
            setAsociado(mostrarAsociado);
            setAgencias(res.agencias);
            newFormData.tipo = (!mostrarSelect && mostrarAsociado) ? 'ASOCIADO' : ((!mostrarSelect && mostrarConductor) ? 'CONDUCTOR' : '');
            setFormData(newFormData);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>

            <Grid container spacing={2}>

                {(mostrarSelect) ?
                    <Fragment>
                        <Grid item xl={4} md={4} sm={6} xs={12}>
                        </Grid>
                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipo'}
                                value={formData.tipo}
                                label={'Seleccione el proceso que desea realizar'}
                                className={'inputGeneral'} 
                                variant={"standard"}
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={seleccionarFormulario} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                <MenuItem value={"ASOCIADO"}>ASOCIADO</MenuItem>
                                <MenuItem value={"CONDUCTOR"}>CONDUCTOR</MenuItem>
                            </SelectValidator>
                        </Grid>
                    </Fragment>
                : null}

                {(asociado) ?
                    <Fragment>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Información de asociado
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'fechaIngresoAsociado'}
                                value={formData.fechaIngresoAsociado}
                                label={'Fecha ingreso como asociado'}
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
                    </Fragment>
                : null}

                {(conductor) ?
                    <Fragment>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Información del conductor
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'fechaIngresoConductor'}
                                value={formData.fechaIngresoConductor }
                                label={'Fecha ingreso como condutor'}
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
                            <SelectValidator
                                name={'tipoConductor'}
                                value={formData.tipoConductor}
                                label={'Tipo de conductor'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange}
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoConductores.map(res=>{
                                    return <MenuItem value={res.tipconid} key={res.tipconid}>{res.tipconnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'agencia'}
                                value={formData.agencia}
                                label={'Agencia'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {agencias.map(res=>{
                                    return <MenuItem value={res.agenid} key={res.agenid}>{res.agennombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Información de la licencia del conducción
                            </Box>
                        </Grid>
                        
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoCategoria'}
                                value={formData.tipoCategoria}
                                label={'Tipo categoría'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoCategoriaLicencias.map(res=>{
                                    return <MenuItem value={res.ticaliid} key={res.ticaliid}>{res.ticalinombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'numeroLicencia'}
                                value={formData.numeroLicencia}
                                label={'Número de licencia'}
                                className={'inputGeneral'}
                                variant={"standard"}
                                inputProps={{autoComplete: 'off', maxLength: 30}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'fechaExpedicionLicencia'}
                                value={formData.fechaExpedicionLicencia }
                                label={'Fecha expedición'}
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
                                name={'fechaVencimiento'}
                                value={formData.fechaVencimiento }
                                label={'Fecha vencimiento'}
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
                    </Fragment>
                : null}

            </Grid>

            {(mostrarMensaje) ? 
                <Grid container spacing={2}>
                    <Grid item xl={12} md={12} sm={12} xs={12} style={{marginTop:'1em'}}>
                        <Box className='mensajeProcesarPersona'>
                           <p>La persona seleccionada no puede ser procesada, ya que existe un registro tanto como asociado como conductor. 
                            Se recomienda acceder a su sección correspondiente y realizar el trámite correspondiente.</p> 
                        </Box>
                    </Grid>
                </Grid>
            : 
                <Grid container direction="row"  justifyContent="right">
                    <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                            startIcon={<SaveIcon />}> {"Guardar" }
                        </Button>
                    </Stack>
                </Grid>
            }

        </ValidatorForm>
    )
}
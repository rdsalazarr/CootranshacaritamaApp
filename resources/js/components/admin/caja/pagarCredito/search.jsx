import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Card, Typography} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {FormatearNumero} from "../../../layout/general";
import person from "../../../../../images/person.png";
import SearchIcon from '@mui/icons-material/Search';
import {LoaderModal} from "../../../layout/loader";
import Persona from '../../cartera/show/persona';
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function Search(){
    const [formDataConsulta, setFormDataConsulta] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'', segundoApellido:'', fechaNacimiento:'',
                                                            direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:null, numeroColocacion:'', valorDesembolsado:''})
    const [formData, setFormData] = useState({tipoIdentificacion:'1', documento:'', valorDesembolsado:'', colocacionId:''});
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const consultarCredito = () =>{
        setLoader(true);
        let newFormDataConsulta = {...formDataConsulta};
        let newFormData         = {...formData};
        let datosEncontrados    = false;
        instance.post('/admin/caja/pagar/credito/consultar/persona', formData).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{
                let colocacion                         = res.colocacion;
                newFormDataConsulta.tipoIdentificacion = colocacion.nombreTipoIdentificacion;
                newFormDataConsulta.documento          = colocacion.persdocumento;
                newFormDataConsulta.primerNombre       = colocacion.persprimernombre;
                newFormDataConsulta.segundoNombre      = colocacion.perssegundonombre;
                newFormDataConsulta.primerApellido     = colocacion.persprimerapellido;
                newFormDataConsulta.segundoApellido    = colocacion.perssegundoapellido;
                newFormDataConsulta.fechaNacimiento    = colocacion.persfechanacimiento;
                newFormDataConsulta.direccion          = colocacion.persdireccion;
                newFormDataConsulta.correo             = colocacion.perscorreoelectronico;
                newFormDataConsulta.telefonoFijo       = colocacion.persnumerotelefonofijo;
                newFormDataConsulta.numeroCelular      = colocacion.persnumerocelular;    
                newFormDataConsulta.showFotografia     = (colocacion.fotografia !== null) ? colocacion.fotografia : person;

                newFormDataConsulta.numeroColocacion   = colocacion.numeroColocacion;
                newFormDataConsulta.valorDesembolsado  = FormatearNumero({numero: colocacion.valorDesembolsado});
                newFormData.colocacionId               = colocacion.coloid;
                newFormData.valorDesembolsado          = colocacion.valorDesembolsado;

                setFormDataConsulta(newFormDataConsulta);
                datosEncontrados = res.datosEncontrado;
                setFormData(newFormData);
            }
            setDatosEncontrados(datosEncontrados);
            setLoader(false);
        })
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/caja/pagar/credito/entregar/efectivo', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setDatosEncontrados(false) : null; 
            (res.success) ? setFormData({tipoIdentificacion:'1', documento:'', valorDesembolsado:'', colocacionId:''}) : null;
            setLoader(false);
        })
    }    

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/caja/pagar/credito/tipo/documento').then(res=>{
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={consultarCredito}>
                <Box style={{marginBottom: '0.5em'}}><Typography component={'h2'} className={'titleGeneral'}>Entregar desembolso de crédito</Typography>
                </Box>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>
                            <Grid item xl={4} md={4} sm={6} xs={12}>
                                <SelectValidator
                                    name={'tipoIdentificacion'}
                                    value={formData.tipoIdentificacion}
                                    label={'Tipo identificación'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required"]}
                                    errorMessages={["Debe hacer una selección"]}
                                    onChange={handleChange} 
                                >
                                    <MenuItem value={""}>Seleccione</MenuItem>
                                    {tipoIdentificaciones.map(res=>{
                                        return <MenuItem value={res.tipideid} key={res.tipideid} >{res.tipidenombre}</MenuItem>
                                    })}
                                </SelectValidator>
                            </Grid>

                            <Grid item xl={5} md={5} sm={6} xs={12}>
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

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Stack direction="row" spacing={2} >
                                    <Button type={"submit"} className={'modalBtnIcono'}
                                        startIcon={<SearchIcon className='icono' />}> Consultar
                                    </Button>
                                </Stack>
                            </Grid>
                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            {(datosEncontrados) ?
                <ValidatorForm onSubmit={handleSubmit} style={{marginTop:'1em'}}>
                    <Card style={{margin: 'auto', width:'90%', padding: '10px'}}>
                        <Grid container spacing={2}>
                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Persona data={formDataConsulta} />
                            </Grid>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='frmDivision'>
                                    Información de la colocación
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Número colocacion</label>
                                    <span>{formDataConsulta.numeroColocacion}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Valor desembolsado</label>
                                    <span className='textoRojo' ><span className='textoGris'>$</span> {'\u00A0'+ formDataConsulta.valorDesembolsado}</span>
                                </Box>
                            </Grid>

                            <Grid item md={3} xl={3} sm={6} xs={12} >
                            </Grid>

                            <Grid item md={3} xl={3} sm={6} xs={12} >
                                <Stack direction="row" spacing={2}>
                                    <Button type={"submit"} className={'modalBtn'} 
                                        startIcon={<SaveIcon />}> Entregar efectivo
                                    </Button>
                                </Stack>
                            </Grid>
                        </Grid>
                    </Card>
                </ValidatorForm>
            : null }

        </Fragment>
    )
}
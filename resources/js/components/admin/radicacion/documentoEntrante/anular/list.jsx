import React, {useState} from 'react';
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator';
import { Box, Typography, Grid, Card, Stack, Button} from '@mui/material';
import showSimpleSnackbar from '../../../../layout/snackBar';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import Radicado from '../show/radicado';
import Persona from '../show/persona';

export default function List(){
    const currentYear = new Date().getFullYear().toString();
    const [formData, setFormData] = useState({anyo: currentYear, consecutivo: '', codigo :'', observacionCambio :'' });

    const [dataPersona, setDataPersona] = useState([]);
    const [dataRadicado, setDataRadicado] = useState([]);
    const [loader, setLoader] = useState(false);
    const [mostarDatos, setMostarDatos] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmitConsultar = () =>{
        setLoader(true);
        instance.post('/admin/radicacion/documento/entrante/consultar/radicado', formData).then(res=>{
            if (!res.success) {
                showSimpleSnackbar(res.data, 'error');
            }

            let newFormData      = {...formData}
            let newDataUsuario   = [];
            let newDataRadicado  = [];
            let radicado         = res.data;
            newFormData.codigo   = radicado.radoenid;

            //Informacion de la persona
            newDataUsuario.tipoIdentificacion   = radicado.tipoIdentificacion;
            newDataUsuario.numeroIdentificacion = radicado.peradodocumento;
            newDataUsuario.esEmpresa            = (radicado.tipideid === '5' ) ? true : false;
            newDataUsuario.primerNombre         = radicado.peradoprimernombre;
            newDataUsuario.segundoNombre        = radicado.peradosegundonombre;
            newDataUsuario.primerApellido       = radicado.peradoprimerapellido;
            newDataUsuario.segundoApellido      = radicado.peradosegundoapellido;
            newDataUsuario.direccionFisica      = radicado.peradodireccion;
            newDataUsuario.direccionElectronica = radicado.peradocorreo;
            newDataUsuario.numeroContacto       = radicado.peradotelefono;
            newDataUsuario.empresaCodigo        = radicado.peradocodigodocumental;

            //Informacion del radicado
            newDataRadicado.fechaRadicado           = radicado.radoenfechahoraradicado;
            newDataRadicado.fechaMaxRespuesta       = radicado.radoenfechamaximarespuesta;
            newDataRadicado.fechaLlegadaDocumento   = radicado.radoenfechallegada;
            newDataRadicado.fechaDocumento          = radicado.radoenfechadocumento;
            newDataRadicado.consecutivo             = radicado.consecutivo;
            newDataRadicado.departamento            = radicado.departamento;
            newDataRadicado.municipio               = radicado.municipio;
            newDataRadicado.dependencia             = radicado.dependencia;
            newDataRadicado.personaEntregaDocumento = radicado.radoenpersonaentregadocumento;
            newDataRadicado.tipoMedio               = radicado.nombreTipoMedio;
            newDataRadicado.tieneCopia              = radicado.tieneCopias;
            newDataRadicado.tieneAnexos             = radicado.tieneAnexos;
            newDataRadicado.estadoActual            = radicado.estadoActual;
            newDataRadicado.descripcionAnexos       = radicado.radoendescripcionanexo;
            newDataRadicado.observacionGeneral      = radicado.radoenobservacion;
            newDataRadicado.descripcion             = radicado.radoenasunto;
            newDataRadicado.requiereRespuesta       = radicado.requiereRespuesta;
            newFormData.totalCopias                 = radicado.totalCopias;
            
            setDataPersona(newDataUsuario);
            setDataRadicado(newDataRadicado);
            setFormData(newFormData);
            (res.success) ? setMostarDatos(true) : setMostarDatos(false);
            setLoader(false);
        })
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/radicacion/documento/entrante/anular', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setMostarDatos(false) : null;
            (res.success) ? setFormData({anyo: currentYear, consecutivo: '', codigo :'', observacionCambio :'' }) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box >
            <Box className={'container'}>
                <Box><Typography component={'h2'} className={'titleGeneral'} style={{marginBottom: '1em'}}>Anular radicado de documento entrante</Typography></Box> 
                <Card style={{padding: '5px'}}>
                    <p style={{color: '#656666', fontWeight: '600'}}><b>Nota:</b> Para poder anular un radicado de documento entrante, esta debe estar en estado diferente de anulado o respondido</p>            
                    <ValidatorForm onSubmit={handleSubmitConsultar}>
                        <Grid container justifyContent={"center"}>

                            <Grid container spacing = {2}>
                                <Grid item xl={3} md={3} sm={6} xs={12}></Grid>
                                
                                <Grid item xl={3} md={3} sm={6} xs={12}>
                                    <TextValidator 
                                        name={'anyo'}
                                        value={formData.anyo}
                                        label={'Año'}
                                        className={'inputGeneral'}
                                        variant={"standard"} 
                                        inputProps={{autoComplete: 'off'}}
                                        validators={["required","maxNumber:9999"]}
                                        errorMessages={["Campo obligatorio","Número máximo permitido es el 9999"]}
                                        type={"number"}
                                        onChange={handleChange}
                                    />
                                </Grid>

                                <Grid item xl={3} md={3} sm={6} xs={12}>
                                    <TextValidator 
                                        name={'consecutivo'}
                                        value={formData.consecutivo}
                                        label={'Consecutivo'}
                                        className={'inputGeneral'} 
                                        variant={"standard"} 
                                        inputProps={{autoComplete: 'off'}}
                                        validators={['required',"maxNumber:9999"]}
                                        errorMessages={['Campo requerido',"Número máximo permitido es el 9999"]}
                                        type={"number"}
                                        onChange={handleChange}
                                    />
                                </Grid>

                                <Grid item xl={2} md={2} sm={6} xs={12} >
                                    <Button type={"submit"} > {"Consultar"}</Button>
                                </Grid>

                            </Grid>
                        </Grid>
                    </ValidatorForm>
                </Card>
            </Box>
            
            {mostarDatos ? 
                <Card style={{padding: '5px'}}>
                    <ValidatorForm onSubmit={handleSubmit}>
                        <Grid container spacing={2}>
                            <Grid item md={12} xl={12} sm={12}>
                                <Persona data={dataPersona} />
                            </Grid>

                            <Grid item md={12} xl={12} sm={12}>
                                <Radicado data={dataRadicado} />
                            </Grid>

                        </Grid>

                        <Grid container spacing = {2}>
                            <Grid item md={12} xl={12} sm={12}>
                                <Box className='frm-division'>
                                    Observación de la anulación del registro del documento entrante
                                </Box>
                            </Grid>

                            <Grid item md={12} xl={12} sm={12}>
                                <TextValidator 
                                    multiline
                                    maxRows={2}
                                    name={'observacionCambio'}
                                    value={formData.observacionCambio}
                                    label={'Observación'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 500}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChange}
                                />
                            </Grid> 

                        </Grid>

                        <Grid container direction="row"  justifyContent="right" style={{marginTop: '0.8em', marginBottom: '0.5em'}}>
                            <Stack direction="row" spacing={2}>
                                <Button type={"submit"} className={'modalBtn'} 
                                    startIcon={<SaveIcon />}> {"Guardar"}
                                </Button>
                            </Stack>
                        </Grid>

                    </ValidatorForm>
                </Card>
            : null}
            
        </Box>
    )
}
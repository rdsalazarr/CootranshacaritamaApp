/*import React, {useState} from 'react';
import ReactDOM from "react-dom";
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator';
import { Box, Typography, Grid, Card, Button} from '@mui/material';
import SimpleSnackbar from '../../../../layout/snackBar';
import  {LoaderModal} from "../../../../layout/loader";
import Solicitud from '../verificar/show/solicitud';
import instance from '../../../../layout/instance';
import {dataForm} from '../../../../layout/util';
import Persona from '../verificar/show/persona';

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
        instance.post('/api/Radicacion/anularRadicado/getConsultarRadicadoDE', dataForm(formData)).then(res=>{
            if (!res.success) {
               ReactDOM.unmountComponentAtNode(document.getElementById("snake"));
                ReactDOM.render(<SimpleSnackbar msg={res.data}
                    icon={'error'} />,
                document.getElementById("snake"));
            }

            let newFormData      = {...formData}
            let newDataUsuario   = [];
            let newDataRadicado  = [];
            let radicado         = res.data[0];
            newFormData.codigo   = radicado.COD_RADOEX;

            //Informacion del peticionario
            newDataUsuario.aceptaPolitica       = radicado.ACEPTAPOLITICA;
            newDataUsuario.tipoIdentificacion   = radicado.TIPOIDENTIFICACION;
            newDataUsuario.numeroIdentificacion = radicado.DOCUMENTO;
            newDataUsuario.esEmpresa            = (radicado.COD_TIPDOC === 'NI' ) ? true : false;
            newDataUsuario.primerNombre         = radicado.PRIMERNOMBRE;
            newDataUsuario.segundoNombre        = radicado.SEGUNDONOMBRE;
            newDataUsuario.primerApellido       = radicado.PRIMERAPELLIDO;
            newDataUsuario.segundoApellido      = radicado.SEGUNDOAPELLIDO;
            newDataUsuario.direccionFisica      = radicado.DIRECCION;
            newDataUsuario.direccionElectronica = radicado.CORREO;
            newDataUsuario.numeroContacto       = radicado.TELEFONO;
            newDataUsuario.empresaCodigo        = radicado.CODIGOINSTITUCIONAL;
 
            //Informacion de la solicitud
            newDataRadicado.fechaRegistro           = radicado.FECHAREGISTRO;
            newDataRadicado.fechaRadicado           = radicado.FECHARADICADO;
            newDataRadicado.fechaMaxRespuesta       = radicado.FECHAMAXIMARESPUESTA;
            newDataRadicado.fechaLlegadaDocumento   = radicado.FECHALLEGADA;
            newDataRadicado.fechaDocumento          = radicado.FECHADOCUMENTO;
            newDataRadicado.consecutivo             = radicado.CONSECUTIVO;
            newDataRadicado.pais                    = radicado.PAIS;
            newDataRadicado.departamento            = radicado.DEPTO;
            newDataRadicado.municipio               = radicado.MUNICIPIO;
            newDataRadicado.dependencia             = radicado.DEPENDENCIA;
            newDataRadicado.personaEntregaDocumento = radicado.PERSONAENTREGADOCUMENTO;
            newDataRadicado.enlaceDrive             = radicado.ENLACEDRIVE;
            newDataRadicado.ruta                    = radicado.RUTA;
            newDataRadicado.tipoMedio               = radicado.TIPOMEDIO;
            newDataRadicado.tieneCopia              = radicado.TIENECOPIA;
            newDataRadicado.tieneAnexos             = radicado.TIENEANEXOS;
            newDataRadicado.estadoActual            = radicado.ESTADO;
            newDataRadicado.descripcionAnexos       = radicado.DESCRIPCIONANEXO;
            newDataRadicado.observacionGeneral      = radicado.OBSERVACION;
            newDataRadicado.descripcion             = radicado.MOTIVOSOLICITUD;
            
            setDataPersona(newDataUsuario);
            setDataRadicado(newDataRadicado);
            setFormData(newFormData);
            (res.success) ? setMostarDatos(true) : setMostarDatos(false);
            setLoader(false);
        })
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/api/Radicacion/anularRadicado/postAnularRadicadoDE', dataForm(formData)).then(res=>{
            ReactDOM.unmountComponentAtNode(document.getElementById("snake"));
            ReactDOM.render(<SimpleSnackbar msg={res.data}
                icon={(res.success) ? 'success': 'error'} />,
            document.getElementById("snake"));
            (res.success) ? setMostarDatos(false) : null;
            (res.success) ? setFormData({anyo: currentYear, consecutivo: '', codigo :'', observacionCambio :'' }) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'container'}>
            <Box><Typography component={'h2'} className={'titleGeneral'}>Anular radicado de documento externo</Typography></Box> 
            <Card style={{padding: '5px'}}>
                <p style={{color: '#656666', fontWeight: '600'}}><b>Nota:</b> Para poder anular un radicado de documento externo, esta debe estar en estado diferente de anulado o respondido</p>            
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

                {mostarDatos ? 
                    <ValidatorForm onSubmit={handleSubmit}>
                        <Grid container spacing={2}>
                            <Grid item md={12} xl={12} sm={12}>
                                <Persona data={dataPersona} />
                            </Grid>

                            <Grid item md={12} xl={12} sm={12}>
                                <Solicitud data={dataRadicado} />
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
                                    inputProps={{autoComplete: 'off', maxLength: 4000}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChange}
                                />
                            </Grid> 

                            <Grid container justifyContent={"center"}>
                                <Grid container direction="row" justifyContent="right">
                                    <Button type={"submit"} >Guardar</Button>
                                </Grid>
                            </Grid>

                        </Grid>

                    </ValidatorForm>
                : null}
            </Card>
        </Box>
    )

}*/
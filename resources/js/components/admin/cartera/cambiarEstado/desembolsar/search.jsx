import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, Icon, Box, MenuItem, Stack, Typography, Card, Fab} from '@mui/material';
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import showSimpleSnackbar from '../../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../../layout/modal';
import SolicitudCredito from '../../show/solicitudCredito';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import VisualizarPdf from './visualizarPdf';
import Asociado from '../../show/asociado';

export default function Search(){

    const [formData, setFormData] = useState({tipoIdentificacion:'1', documento:'87787878', personaId:'', asociadoId:'',  solicitudId:''})
    const [loader, setLoader] = useState(false); 
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [formDataConsulta, setFormDataConsulta] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'',                segundoApellido:'', fechaNacimiento:'',
                                                        direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:'', lineaCredito:'', destinoCredito:'', valorSolicitado:'', 
                                                        tasaNominal:'',  numerosCuota:'', observacionGeneral:''})

    const [modal, setModal] = useState({open: false, titulo:'', url: ''});

    const tituloModal = ['Generar PDF de la solicitud crédito','Generar PDF de la carta intrucciones','Generar PDF del formato', 'Generar PDF del pagaré'];
    const urlModal    = ['SOLICITUDCREDITO','CARTAINSTRUCCIONES','FORMATO', 'PAGARE'];

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const consultarAsociado = () =>{
        if(formData.tipoIdentificacion === ''){
            showSimpleSnackbar("Debe seleccionar el tipo de identificación", 'error');
            return;
        }

        if(formData.documento === ''){
            showSimpleSnackbar("Debe ingresar el número de documento", 'error');
            return;
        }

        let newFormData         = {...formData}
        let newFormDataConsulta = {...formDataConsulta}
        setDatosEncontrados(false);
        instance.post('/admin/cartera/consultar/asociado', formData).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{

                let solicitudCredito                     = res.solicitudCredito;

                newFormData.personaId                    = solicitudCredito.persid;
                newFormData.asociadoId                   = solicitudCredito.asocid;
                newFormData.solicitudId                  = solicitudCredito.solcreid;
                newFormDataConsulta.tipoIdentificacion   = solicitudCredito.nombreTipoIdentificacion;
                newFormDataConsulta.documento            = solicitudCredito.persdocumento;
                newFormDataConsulta.primerNombre         = solicitudCredito.persprimernombre;
                newFormDataConsulta.segundoNombre        = solicitudCredito.perssegundonombre;
                newFormDataConsulta.primerApellido       = solicitudCredito.persprimerapellido;
                newFormDataConsulta.segundoApellido      = solicitudCredito.perssegundoapellido;
                newFormDataConsulta.fechaNacimiento      = solicitudCredito.persfechanacimiento;
                newFormDataConsulta.direccion            = solicitudCredito.persdireccion;
                newFormDataConsulta.correo               = solicitudCredito.perscorreoelectronico;
                newFormDataConsulta.telefonoFijo         = solicitudCredito.persnumerotelefonofijo;
                newFormDataConsulta.numeroCelular        = solicitudCredito.persnumerocelular;
                newFormDataConsulta.fechaIngresoAsociado = solicitudCredito.asocfechaingreso;
                newFormDataConsulta.showFotografia       = solicitudCredito.fotografia;

                newFormDataConsulta.lineaCredito         = solicitudCredito.lineaCredito;
                newFormDataConsulta.destinoCredito       = solicitudCredito.solcredescripcion;
                newFormDataConsulta.valorSolicitado      = solicitudCredito.valorSolicitado;
                newFormDataConsulta.tasaNominal          = solicitudCredito.tasaNominal;
                newFormDataConsulta.numerosCuota         = solicitudCredito.solcrenumerocuota;
                newFormDataConsulta.observacionGeneral   = solicitudCredito.solcreobservacion;
                newFormDataConsulta.fechaSolicitud       = solicitudCredito.solcrefechasolicitud;
                newFormDataConsulta.estadoActual         = solicitudCredito.estadoActual;

                setFormData(newFormData);
                setFormDataConsulta(newFormDataConsulta);
                setDatosEncontrados(true);
            }
            setLoader(false);
        })
    }

    const desembolsarCredito = () =>{
        setLoader(true);
        instance.post('/admin/cartera/desembolsar/solicitud/credito', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setDatosEncontrados(false) : null; 
            (res.success) ? setFormData({tipoIdentificacion:'', documento:'', observacionCambio:'', asociadoId:'', tipoEstado:''}) : null; 
            setLoader(false);
        })
    }

    const abrirModal = (tipo) =>{
        setModal({open: true, titulo: tituloModal[tipo], url: urlModal[tipo]});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/cartera/desembolsar/solicitud/credito/datos').then(res=>{
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
            <ValidatorForm onSubmit={consultarAsociado}>
                <Box><Typography  component={'h2'} className={'titleGeneral'}>Desembolsar crédito</Typography>
                </Box>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>

                            <Grid item xl={6} md={6} sm={12} xs={12}>
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

                            <Grid item xl={6} md={6} sm={12} xs={12} sx={{position: 'relative'}}>
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
                                <Icon className={'iconLupa'} onClick={consultarAsociado}>search</Icon>
                            </Grid>

                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            {(datosEncontrados) ? 
                <ValidatorForm onSubmit={desembolsarCredito} style={{marginTop: '2em'}}> 
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Asociado data={formDataConsulta} />
                            </Grid>

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <SolicitudCredito data={formDataConsulta} />
                            </Grid>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='frmDivision'>
                                    Información del proceso de desembolsar el crédito
                                </Box>
                            </Grid>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
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

                            <Grid item md={4} xl={4} sm={3} xs={3}>

                            </Grid>

                            <Grid item md={7} xl={7} sm={9} xs={9}>
                                <Grid container direction="row" justifyContent="right" style={{marginTop: '0.5em'}}>
                                    <Fab variant="extended" size="medium" className={'btnRojo'} onClick={() => {abrirModal(0)}}>
                                        <PictureAsPdfIcon sx={{ mr: 1 }}  />
                                        Solicitud crédito
                                    </Fab>

                                    <Fab variant="extended" size="medium" className={'btnRojo'} onClick={() => {abrirModal(1)}}>
                                        <PictureAsPdfIcon sx={{ mr: 1 }} />
                                        Carta intrucciones
                                    </Fab>

                                    <Fab variant="extended" size="medium" className={'btnRojo'} onClick={() => {abrirModal(2)}}>
                                        <PictureAsPdfIcon sx={{ mr: 1 }} />
                                        Formato 
                                    </Fab>

                                    <Fab variant="extended" size="medium" className={'btnRojo'} onClick={() => {abrirModal(3)}}>
                                        <PictureAsPdfIcon sx={{ mr: 1 }} />
                                        Pagaré
                                    </Fab> 
                                </Grid>
                            </Grid>

                            <Grid item md={1} xl={1} sm={12} xs={12}>
                                <Grid container direction="row" justifyContent="right" style={{marginTop: '1em'}}>
                                    <Stack direction="row" spacing={2}>
                                        <Button type={"submit"} className={'modalBtn'}
                                            startIcon={<SaveIcon />}> Guardar
                                        </Button>
                                    </Stack>
                                </Grid>
                            </Grid>

                        </Grid>

                    </Card>
                </ValidatorForm>
            : null }

            <ModalDefaultAuto
                title={modal.titulo}
                content={<VisualizarPdf data={formData} url={modal.url}/>}
                close  ={() =>{setModal({open : false, titulo:'', url: ''})}}
                tam    ={'mediumFlot'}
                abrir  ={modal.open}
            />

        </Fragment>
    )
}
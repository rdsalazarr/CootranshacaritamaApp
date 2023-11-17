import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, Icon, Box, MenuItem, Stack, Typography, Card, Fab} from '@mui/material';
import NumberValidator from '../../../../layout/numberValidator';
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import showSimpleSnackbar from '../../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../../layout/modal';
import SolicitudCredito from '../../show/solicitudCredito';
import person from "../../../../../../images/person.png";
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import VisualizarPdf from './visualizarPdf';
import Asociado from '../../show/asociado';

ValidatorForm.addValidationRule('isTasaNominal', (value) => {
    // Verificar si el valor es un número válido en formato "10.50"
    const regex = /^\d+(\.\d{1,2})?$/;
    if (!regex.test(value)) {
      return false;
    }
  
    // Verificar si el número está en el rango de 0 a 100 (porcentaje válido)
    const numValue = parseFloat(value);
    return numValue >= 0 && numValue <= 100;
});

export default function Search(){

    const [formData, setFormData] = useState({tipoIdentificacion:'1', documento:'', personaId:'', asociadoId:'',  solicitudId:'', lineaCredito:'',
                                            valorSolicitado:'', valorAprobado:'',  tasaNominal:'', plazo:'', observacionGeneral:''})
    const [loader, setLoader] = useState(false);
    const [success, setSuccess] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [lineasCreditos, setLineasCreditos] = useState([]);
    const [modal, setModal] = useState({open: false, titulo:'', url: ''});
    const [formDataConsulta, setFormDataConsulta] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'',                segundoApellido:'', fechaNacimiento:'',
                                                        direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:'', lineaCredito:'', destinoCredito:'', valorSolicitado:'', 
                                                        tasaNominal:'',  numerosCuota:'', observacionGeneral:'', montoSolicitado:''})
    const [formDataLineaCredito, setFormDataLineaCredito] = useState({ tasaNominalLineaCredito: '', tasaNominal:'', valorMaximoLineaCredito:'',  valorMinimoLineaCredito:'', plazoMaximoLineaCredito:''})
   
    const tituloModal = ['Generar PDF de la solicitud crédito','Generar PDF de la carta intrucciones','Generar PDF del formato', 'Generar PDF del pagaré'];
    const urlModal    = ['SOLICITUDCREDITO','CARTAINSTRUCCIONES','FORMATO', 'PAGARE'];

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const abrirModal = (tipo) =>{
        setModal({open: true, titulo: tituloModal[tipo], url: urlModal[tipo]});
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
        setLoader(true);
        instance.post('/admin/cartera/consultar/asociado', formData).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{

                let solicitudCredito                     = res.solicitudCredito;

                newFormData.personaId                    = solicitudCredito.persid;
                newFormData.asociadoId                   = solicitudCredito.asocid;
                newFormData.solicitudId                  = solicitudCredito.solcreid;
                newFormData.lineaCredito                 = solicitudCredito.lincreid;
                newFormData.valorSolicitado              = solicitudCredito.solcrevalorsolicitado;
                newFormData.valorAprobado                = solicitudCredito.solcrevalorsolicitado;
                newFormData.tasaNominal                  = solicitudCredito.solcretasa;
                newFormData.plazo                        = solicitudCredito.solcrenumerocuota;       

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
                newFormDataConsulta.showFotografia       = (solicitudCredito.fotografia !== null) ? solicitudCredito.fotografia : person;

                newFormDataConsulta.lineaCreditoId       = solicitudCredito.lincreid;
                newFormDataConsulta.lineaCredito         = solicitudCredito.lineaCredito;
                newFormDataConsulta.destinoCredito       = solicitudCredito.solcredescripcion;
                newFormDataConsulta.valorSolicitado      = solicitudCredito.valorSolicitado;
                newFormDataConsulta.montoSolicitado      = solicitudCredito.solcrevalorsolicitado;
                newFormDataConsulta.tasaNominal          = solicitudCredito.tasaNominal;
                newFormDataConsulta.numerosCuota         = solicitudCredito.solcrenumerocuota;
                newFormDataConsulta.observacionGeneral   = solicitudCredito.solcreobservacion;
                newFormDataConsulta.fechaSolicitud       = solicitudCredito.solcrefechasolicitud;
                newFormDataConsulta.estadoActual         = solicitudCredito.estadoActual;

                setLineasCreditos(res.lineasCreditos);
                setFormDataConsulta(newFormDataConsulta);
                setFormData(newFormData);
                setDatosEncontrados(true);
            }
            setLoader(false);
        })
    }

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }

    const desembolsarCredito = () =>{
        let validarLineaCredito = (formData.lineaCredito !== formDataConsulta.lineaCreditoId) ? true : false;

        if(formData.valorSolicitado > formDataConsulta.montoSolicitado){
            showSimpleSnackbar("El monto máximo permito es "+formatearNumero(formDataConsulta.montoSolicitado), 'error');
            return;
        }

        if(!validarLineaCredito && formData.tasaNominal > formDataConsulta.tasaNominal){
            showSimpleSnackbar("La tasa máxima permita es "+formDataConsulta.tasaNominal , 'error');
            return;
        }

        if(!validarLineaCredito && formData.plazo > formDataConsulta.numerosCuota){
            showSimpleSnackbar("El plazo máximo permito es "+formDataConsulta.numerosCuota+" meses", 'error');
            return;
        }

        if(validarLineaCredito && formData.tasaNominal > formDataLineaCredito.tasaNominalLineaCredito){
            showSimpleSnackbar("La tasa máxima permita de esta línea de crédito es "+formDataLineaCredito.tasaNominalLineaCredito , 'error');
            return;
        }

        if(validarLineaCredito && formData.valorSolicitado < formDataLineaCredito.valorMinimoLineaCredito){
            showSimpleSnackbar("El monto mínimo permito es "+formatearNumero(formDataLineaCredito.valorMinimoLineaCredito), 'error');
            return;
        }

        if(validarLineaCredito && formData.valorSolicitado > formDataLineaCredito.valorMaximoLineaCredito){
            showSimpleSnackbar("El monto máximo permito es "+formatearNumero(formDataLineaCredito.valorMaximoLineaCredito), 'error');
            return;
        }

        if(validarLineaCredito && formData.plazo > formDataLineaCredito.plazoMaximoLineaCredito){
            showSimpleSnackbar("El plazo máximo permito es "+formDataLineaCredito.plazoMaximoLineaCredito+" meses", 'error');
            return;
        }

        setLoader(true);
        instance.post('/admin/cartera/desembolsar/solicitud/credito', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);          
            (res.success) ? setHabilitado(false) : null; 
            (res.success) ? setFormData({tipoIdentificacion:'', documento:'', personaId:'', asociadoId:'',  solicitudId:formData.solicitudId, lineaCredito:'',
                                        valorSolicitado:'', valorAprobado:'', tasaNominal:'', plazo:'', observacionGeneral:''}) : null; 
            
            setSuccess(res.success);
            setLoader(false);
        })
    } 

    const cargarInformacionLinea = (e) =>{
        let newFormData             = {...formData}
        let mewFormDataLineaCredito = {...formDataLineaCredito}  
        if(e.target.value !== ''){
            const resultadosFiltrados = lineasCreditos.filter((lineaCredito) => lineaCredito.lincreid === e.target.value);
            mewFormDataLineaCredito.tasaNominalLineaCredito = resultadosFiltrados[0].lincretasanominal;
            mewFormDataLineaCredito.tasaNominal             = resultadosFiltrados[0].lincretasanominal;
            mewFormDataLineaCredito.valorMaximoLineaCredito = resultadosFiltrados[0].lincremontomaximo;
            mewFormDataLineaCredito.valorMinimoLineaCredito = resultadosFiltrados[0].lincremontominimo;
            mewFormDataLineaCredito.plazoMaximoLineaCredito = resultadosFiltrados[0].lincreplazomaximo;
            setFormDataLineaCredito(mewFormDataLineaCredito);
        }else{
            newFormData.tasaNominal   = '';
        }
        newFormData.lineaCredito  = e.target.value;
        setFormData(newFormData);
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
                                <SolicitudCredito data={formDataConsulta} aprobada={true} />
                            </Grid>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='frmDivision'>
                                    Información del proceso para desembolsar el crédito
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <SelectValidator
                                    name={'lineaCredito'}
                                    value={formData.lineaCredito}
                                    label={'Línea de crédito'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    onChange={cargarInformacionLinea} 
                                >
                                    <MenuItem value={""}>Seleccione</MenuItem>
                                    {lineasCreditos.map(res=>{
                                        return <MenuItem value={res.lincreid} key={res.lincreid} >{res.lincrenombre}</MenuItem>
                                    })}
                                </SelectValidator>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <NumberValidator fullWidth
                                    id={"valorSolicitado"}
                                    name={"valorSolicitado"}
                                    label={"Valor solicitado"}
                                    value={formData.valorSolicitado}
                                    type={'numeric'}
                                    require={['required', 'maxStringLength:8']}
                                    error={['Campo obligatorio','Número máximo permitido es el 99999999']}
                                    onChange={handleChange}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'tasaNominal'}
                                    value={formData.tasaNominal}
                                    label={'Tasa nominal'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required", 'isTasaNominal']}
                                    errorMessages={["Campo obligatorio", 'Ingrese un tasa nominal válida']}
                                    onChange={handleChange}
                                />
                            </Grid> 

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator 
                                    name={'plazo'}
                                    value={formData.plazo}
                                    label={'Plazo (En meses)'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:99"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 99"]}
                                    onChange={handleChange}
                                    type={"number"}
                                />
                            </Grid>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <TextValidator
                                    name={'observacionGeneral'}
                                    value={formData.observacionGeneral}
                                    label={'Observación generales'}
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
                                {(success) ? 
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
                                : null}
                            </Grid>

                            <Grid item md={1} xl={1} sm={12} xs={12}>
                                <Grid container direction="row" justifyContent="right" style={{marginTop: '1em'}}>
                                    <Stack direction="row" spacing={2}>
                                        <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
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
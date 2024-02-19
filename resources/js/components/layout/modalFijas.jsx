
import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator} from 'react-material-ui-form-validator';
import {Box, Grid, Button, Avatar, MenuItem, Card, Typography} from "@mui/material";
import errorTotalizarFirmas from "../../../images/modal/errorTotalizarFirmas.png";
import suspenderConductor from "../../../images/modal/suspenderConductor.png";
import entregarEncomienda from "../../../images/modal/entregarEncomienda.png";
import activarConductor from "../../../images/modal/activarConductor.png";
import recibirDocumento from "../../../images/modal/recibirDocumento.png";
import sellarDocumento from "../../../images/modal/sellarDocumento.png";
import anularDocumento from "../../../images/modal/anularDocumento.png";
import firmarDocumento from "../../../images/modal/firmarDocumento.png";
import solicitudFirma from "../../../images/modal/solicitudFirma.png";
import enviarRadicado from "../../../images/modal/enviarRadicado.png";
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import AttachMoneyIcon from '@mui/icons-material/AttachMoney';
import cerrarCaja from "../../../images/modal/cerrarCaja.png";
import DeleteIcon from '@mui/icons-material/Delete';
import ClearIcon from '@mui/icons-material/Clear';
import StartIcon from '@mui/icons-material/Start';
import SaveIcon from '@mui/icons-material/Save';
import showSimpleSnackbar from './snackBar';
import RelojDigital from './relojDigital';
import {LoaderModal} from "./loader";
import instance from './instance';

export default function Eliminar({id, ruta, cerrarModal}){

    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const continuar = () =>{
        setLoader(true);
        instance.post(ruta, {codigo: id}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid container spacing={2}>
            <Grid item xl={2} md={2} sm={2} xs={2}>
                <Box className='animate__animated animate__rotateIn'>
                    <Avatar style={{marginTop: '0.8em', width:'60px', height:'60px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <DeleteForeverIcon  style={{fontSize: '2.5em', color: '#f33602'}}/> </Avatar>  
                </Box>
            </Grid>

            <Grid item xl={10} md={10} sm={10} xs={10}>
                <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'center'}}>
                    ¿Esta seguro que desea eliminar este registro?
                </p>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                    startIcon={<ClearIcon />}> Cancelar
                </Button>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={continuar} className='modalBtn' disabled={(habilitado) ? false : true}
                    startIcon={<DeleteIcon />}> Eliminar
                </Button>
            </Grid> 

        </Grid>
    )
}

export function EliminarAdjunto({data, eliminarFilasAdjunto, cerrarModal, cantidadAdjunto, ruta}){   
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const continuar = () =>{
        setLoader(true);
        instance.post(ruta, data).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? (eliminarFilasAdjunto(data.id),cerrarModal(), cantidadAdjunto(), setHabilitado(false) ) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return ( 
        <Grid container spacing={2}>

            <Grid item xl={2} md={2} sm={2} xs={2}>
                <Box className='animate__animated animate__rotateIn'>
                    <Avatar style={{marginTop: '0.8em', width:'60px', height:'60px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <DeleteForeverIcon style={{fontSize: '2.5em', color: '#f33602'}}/> </Avatar>  
                </Box>
            </Grid>

            <Grid item xl={10} md={10} sm={10} xs={10}>
                <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'center'}}>
                    ¿Esta seguro que desea eliminar este archivo adjunto?
                </p>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                    startIcon={<ClearIcon />}> Cancelar
                </Button>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={continuar} className='modalBtn' disabled={(habilitado) ? false : true}
                    startIcon={<DeleteIcon />}> Eliminar
                </Button>
            </Grid>
        </Grid>
    )
}

export function SolicitarFirma({id, ruta, cerrarModal}){

    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/producion/documental/'+ruta+'/solicitar/firma', {codigo: id, observacionCambio: '', tipo: 'S'}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }    

    return (
        <Grid container spacing={2}>
            <Box style={{width: '20%', margin: 'auto'}}>
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box className='animate__animated animate__rotateIn'>
                        <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={solicitudFirma} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                    </Box>
                </Grid>
            </Box>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                    Antes de solicitar la firma de este documento, asegúrese de haber revisado su contenido con todos los anexos y copias si se requieren.
                </p>

                <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.5em', textAlign: 'center'}}>
                    ¿Desea continuar con la solicitud de firma?
                </p>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                    startIcon={<ClearIcon />}> Cancelar
                </Button>
            </Grid> 

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={continuar} className='modalBtn' disabled={(habilitado) ? false : true}
                    startIcon={<SaveIcon />}> Continuar
                </Button>
            </Grid> 

        </Grid>
    )
}

export function AnularSolicitarFirma({id, ruta, cerrarModal}){

    const [formData, setFormData] = useState( {codigo: id, observacionCambio: '', tipo: 'E' });

    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/producion/documental/'+ruta+'/solicitar/firma', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }    

    return (
        <ValidatorForm onSubmit={continuar} >

            <Grid container spacing={2}>
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={4}
                        name={'observacionCambio'}
                        value={formData.observacionCambio}
                        label={'Observación de la analuación del la solicitud de la firma'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 500}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                        startIcon={<ClearIcon />}> Cancelar
                    </Button>
                </Grid> 

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button type={"submit"} className='modalBtn' disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> Guardar
                    </Button>
                </Grid>

            </Grid>
        </ValidatorForm> 
    )
}

export function SellarDocumento({id, ruta, cerrarModal}){

    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [puedeSellar, setPuedeSellar] = useState(true);

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/producion/documental/sellar/'+ruta, {codigo: id}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/producion/documental/verificar/sellado/'+ruta, {codigo: id}).then(res=>{
            setPuedeSellar(res.message);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }    

    return (
        <Grid container spacing={2}>

            {(puedeSellar) ?
                <Fragment>
                    <Box style={{width: '20%', margin: 'auto'}}>
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='animate__animated animate__rotateIn'>
                                <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={sellarDocumento} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 14px'}} /> </Avatar>  
                            </Box>
                        </Grid>
                    </Box>

                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                            Antes de proceder con el sellado del documento, asegúrese de que todas las firmas necesarias estén en su lugar. 
                            Una vez completado este proceso, no podrá revertirlo. 
                            Además, si el documento se envía por correo, perderá el control sobre él.
                        </p>

                        <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.5em', textAlign: 'center'}}>
                            ¿Desea continuar con el sellado de este documento?
                        </p>
                    </Grid>

                    <Grid item xl={6} md={6} sm={6} xs={6}>
                        <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                            startIcon={<ClearIcon />}> Cancelar
                        </Button>
                    </Grid> 

                    <Grid item xl={6} md={6} sm={6} xs={6}>
                        <Button onClick={continuar} className='modalBtn' disabled={(habilitado) ? false : true}
                            startIcon={<SaveIcon />}> Continuar
                        </Button>
                    </Grid> 
                </Fragment>
            :  
            <Fragment>
                <Box style={{width: '20%', margin: 'auto'}}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='animate__animated animate__rotateIn'>
                            <Avatar style={{marginTop: '0.8em', width:'110px', height:'110px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={errorTotalizarFirmas} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 1px 10px 12px'}} /> </Avatar>  
                        </Box>
                    </Grid>
                </Box>
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box style={{backgroundColor: '#f23501',  border: '1px solid rgb(131, 131, 131)', padding: '5px', color: '#fdfdfd',  borderRadius: '10px'}}>Lo sentimos, pero no podemos proceder con el sellado del documento.
                            El número de firmas realizadas en el documento no concuerda con el número de firmas solicitadas.
                    </Box>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button onClick={cerrarModal} className='modalBtnRojo'
                        startIcon={<ClearIcon />}> Cancelar
                    </Button>
                </Grid>
                
            </Fragment> 
            }  

        </Grid>
    )
}

export function AnularDocumento({id, ruta, cerrarModal}){

    const [formData, setFormData] = useState( {codigo: id, observacionCambio: ''});

    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/producion/documental/anular/'+ruta, formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }    

    return (
        <ValidatorForm onSubmit={continuar} >

            <Grid container spacing={2}>

                <Box style={{width: '20%', margin: 'auto'}}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='animate__animated animate__rotateIn'>
                            <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={anularDocumento} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                        </Box>
                    </Grid>
                </Box>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                        Antes de anular este tipo documental, verifique cuidadosamente este proceso, ya que no se puede revertir bajo ninguna circunstancia.
                    </p>
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={4}
                        name={'observacionCambio'}
                        value={formData.observacionCambio}
                        label={'Observación de la analuación del tipo documental'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 500}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                        startIcon={<ClearIcon />}> Cancelar
                    </Button>
                </Grid> 

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button type={"submit"} className='modalBtn' disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}>Continuar
                    </Button>
                </Grid>

            </Grid>
        </ValidatorForm>
    )
}

export function FirmarDocumento({id, cerrarModal, urlDatos, urlSalve}){

    const [formData, setFormData] = useState({id: id, tokenId:'',  token: ''});
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [tiempoRestante, setTiempoRestante] = useState(0);
    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false);
    const [mensaje, setMensaje] = useState('');

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const continuar = () =>{
        setLoader(true);
        instance.post(urlSalve, formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? (setHabilitado(false), setTiempoRestante(0), setFormData({id: id,tokenId:'', token: '' }) ) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post(urlDatos, {id: id}).then(res=>{
            if(res.success){
                newFormData.firma   = res.firma;
                newFormData.tokenId = res.idToken;
                setMensaje(res.mensajeMostrar);
                setTiempoRestante(res.tiempoToken);
                setDatosEncontrados(true);
                setFormData(newFormData);
            }
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={continuar} >

            <Grid container spacing={2}>

                {(datosEncontrados) ?
                    <Fragment>

                        <Box style={{width: '20%', margin: 'auto'}}>
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='animate__animated animate__rotateIn'>
                                    <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={firmarDocumento} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                                </Box>
                            </Grid>
                        </Box>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                                <span dangerouslySetInnerHTML={{__html: mensaje}} /> 
                            </p>
                        </Grid>


                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='tiepoRestante'> Tiempo restante:<Box className="tiempoevaluacion">
                                {(tiempoRestante > 0) ?
                                    <RelojDigital tiempoInicial={tiempoRestante} onTiempoFinalizado={cerrarModal} />
                                : <Box className="tiempoevaluacion">0</Box> }
                                </Box>
                            </Box>

                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={0}>

                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator
                                name={'token'}
                                value={formData.token}
                                label={'Token'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 20}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={6} md={6} sm={6} xs={6}>
                            <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                                startIcon={<ClearIcon />}> Cancelar
                            </Button>
                        </Grid>

                        <Grid item xl={6} md={6} sm={6} xs={6}>
                            <Button type={"submit"} className='modalBtn' disabled={(habilitado) ? false : true}
                                startIcon={<SaveIcon />}>Continuar
                            </Button>
                        </Grid>
                    </Fragment>
                : 
                    <Fragment>
                        <Box style={{width: '20%', margin: 'auto'}}>
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='animate__animated animate__rotateIn'>
                                    <Avatar style={{marginTop: '0.8em', width:'110px', height:'110px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={errorTotalizarFirmas} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 1px 10px 12px'}} /> </Avatar>  
                                </Box>
                            </Grid>
                        </Box>
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box style={{backgroundColor: '#f23501',  border: '1px solid rgb(131, 131, 131)', padding: '5px', color: '#fdfdfd',  borderRadius: '10px'}}>
                                Disculpe, ha ocurrido un error interno al generar el token. 
                                Por favor, intente nuevamente más tarde o póngase en contacto con nuestro equipo de soporte para recibir asistencia
                            </Box>
                        </Grid>

                        <Grid item xl={6} md={6} sm={6} xs={6}>
                            <Button onClick={cerrarModal} className='modalBtnRojo'
                                startIcon={<ClearIcon />}> Cancelar
                            </Button>
                        </Grid>

                    </Fragment>
                }

            </Grid>
        </ValidatorForm> 
    )
}

export function EnviarRadicado({id, ruta, cerrarModal}){

    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const continuar = () =>{
        setLoader(true);
        instance.post(ruta, {codigo: id,}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }    

    return (
        <Grid container spacing={2}>
            <Box style={{width: '20%', margin: 'auto'}}>
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box className='animate__animated animate__rotateIn'>
                        <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={enviarRadicado} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                    </Box>
                </Grid>
            </Box>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                    Antes de enviar este radicado, asegúrese de haber revisado su contenido con toda la información y copias si se requieren.
                </p>

                <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.5em', textAlign: 'center'}}>
                    ¿Desea continuar con el envío de este radicado a la dependencia?
                </p>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                    startIcon={<ClearIcon />}> Cancelar
                </Button>
            </Grid> 

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={continuar} className='modalBtn' disabled={(habilitado) ? false : true}
                    startIcon={<SaveIcon />}> Continuar
                </Button>
            </Grid> 

        </Grid>
    )
}

export function AceptarRadicadoDE({id, idFirma, cerrarModal}){

    const [formData, setFormData] = useState( {id: id, idFirma:idFirma, requiereRespuesta: ''});
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/radicacion/documento/entrante/recibir', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={continuar} >

            <Grid container spacing={2}>

                <Box style={{width: '20%', margin: 'auto'}}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='animate__animated animate__rotateIn'>
                            <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={recibirDocumento} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                        </Box>
                    </Grid>
                </Box>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                        Para confirmar la recepción del documento, es necesario que indique si requiere emitir una respuesta.
                    </p>

                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.5em', textAlign: 'center'}}>
                        ¿Desea proceder?
                    </p>
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                   
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={0}>

                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <SelectValidator
                        name={'requiereRespuesta'}
                        value={formData.requiereRespuesta}
                        label={'¿Requiere respuesta?'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"} >Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                        startIcon={<ClearIcon />}> Cancelar
                    </Button>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button type={"submit"} className='modalBtn' disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}>Continuar
                    </Button>
                </Grid>
            </Grid>
        </ValidatorForm> 
    )
}

export function SuspenderConductor({id, cerrarModal}){
    const [formData, setFormData] = useState({id: id, descripcionSancion:'', estado:''});
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/direccion/transporte/conductor/suspender', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null; 
            (res.success) ? setFormData({id: id, descripcionSancion:'' }) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={continuar} >

            <Grid container spacing={2}>

                <Box style={{width: '20%', margin: 'auto'}}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='animate__animated animate__rotateIn'>
                            <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={suspenderConductor} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                        </Box>
                    </Grid>
                </Box>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                        Para confirmar la suspención del conductor, se requiere que ingrese la observación y determinar el estado
                    </p>
                </Grid>

                <Grid item xl={10} md={10} sm={8} xs={12}>
                    <TextValidator
                        name={'descripcionSancion'}
                        value={formData.descripcionSancion}
                        label={'Descripción'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 500}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={2} md={2} sm={4} xs={12}>
                    <SelectValidator
                        name={'estado'}
                        value={formData.estado}
                        label={'Estado'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"S"}>Suspendido</MenuItem>
                        <MenuItem value={"D"}>Desvinculado</MenuItem>
                    </SelectValidator>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                        startIcon={<ClearIcon />}> Cancelar
                    </Button>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button type={"submit"} className='modalBtn' disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}>Continuar
                    </Button>
                </Grid>

            </Grid>
        </ValidatorForm> 
    )
}

export function ActivarConductor({id, cerrarModal}){
    const [formData, setFormData] = useState({id: id, descripcionSancion:''});
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/direccion/transporte/conductor/activar/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null; 
            (res.success) ? setFormData({id: id, descripcionSancion:'' }) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={continuar} >

            <Grid container spacing={2}>

                <Box style={{width: '20%', margin: 'auto'}}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='animate__animated animate__rotateIn'>
                            <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={activarConductor} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                        </Box>
                    </Grid>
                </Box>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                        Para confirmar la activación del conductor, se requiere que ingrese la observación.
                    </p>
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <TextValidator
                        name={'descripcionSancion'}
                        value={formData.descripcionSancion}
                        label={'Descripción'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 500}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                        startIcon={<ClearIcon />}> Cancelar
                    </Button>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button type={"submit"} className='modalBtn' disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}>Continuar
                    </Button>
                </Grid>

            </Grid>
        </ValidatorForm> 
    )
}

export function TomarDecisionSolicitudCredito({id, cerrarModal}){

    const [formData, setFormData] = useState( {codigo: id, estadoSolicitud: '', observacion:''});
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [tipoEstadosSolicitudCredito, setTipoEstadosSolicitudCredito] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/cartera/tomar/decision/solicitud/credito', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/cartera/listar/estados/solicitud/credito').then(res=>{
            setTipoEstadosSolicitudCredito(res.tipoEstadosSolicitudCredito);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={continuar} >

            <Grid container spacing={2}>

                <Box style={{width: '20%', margin: 'auto'}}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='animate__animated animate__rotateIn'>
                            <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={recibirDocumento} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                        </Box>
                    </Grid>
                </Box>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                        Va a tomar una decisión sobre la solicitud de crédito, por lo tanto, 
                        agradecemos que ingrese sus observaciones y seleccione uno de los estados disponibles en el sistema.
                    </p>
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <TextValidator
                        multiline
                        maxRows={3}
                        name={'observacion'}
                        value={formData.observacion}
                        label={'Observación'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 500}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <SelectValidator
                        name={'estadoSolicitud'}
                        value={formData.estadoSolicitud}
                        label={'Estado de la solicitud'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoEstadosSolicitudCredito.map(res=>{
                            return <MenuItem value={res.tiesscid} key={res.tiesscid} >{res.tiesscnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                        startIcon={<ClearIcon />}> Cancelar
                    </Button>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button type={"submit"} className='modalBtn' disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}>Continuar
                    </Button>
                </Grid>
            </Grid>
        </ValidatorForm> 
    )
}

export function EntregarEncomienda({data, cerrarModal}){
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/despacho/entregar/encomienda/salve', {codigo: data.encoid}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? (setHabilitado(false)) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid container spacing={2}>
            <Box style={{width: '20%', margin: 'auto'}}>
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box className='animate__animated animate__rotateIn'>
                        <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={entregarEncomienda} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                    </Box>
                </Grid>
            </Box>

            {(data.pagoContraEntrega === 'NO') ? 
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                        Antes de proceder con la entrega de la encomienda, asegúrese de tenerla en sus manos y lista para ser entregada al cliente.
                    </p>

                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.5em', textAlign: 'center'}}>
                        ¿Desea continuar con la entrega de la encomienda?
                    </p>
                </Grid>
            :  
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                        Hemos identificado que la encomienda está marcada como "Pago contra entrega". 
                        Para proceder con la entrega, le solicitamos asegurarse de tener físicamente la encomienda en sus manos, 
                        además de verificar que su caja esté abierta. También, recuerde que deberá recibir el valor correspondiente 
                        a $ {data.valorTotalEncomienda} por el costo total, por parte del cliente.
                    </p>

                    <Grid container spacing={2} style={{margin:'auto', width:'70%'}}>
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Card className='cardNotificacion'>
                                <Typography component={'h5'} >Valor encomienda</Typography>
                                <Box className='cardBox'>
                                    <AttachMoneyIcon className='cardIcono'></AttachMoneyIcon>
                                    <Typography component={'h4'} >{data.valorTotalEncomienda}</Typography>
                                </Box>
                            </Card>
                        </Grid>
                    </Grid>

                </Grid> 
            }

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                    startIcon={<ClearIcon />}> Cancelar
                </Button>
            </Grid> 

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={continuar} className='modalBtn' disabled={(habilitado) ? false : true}
                    startIcon={<SaveIcon />}> Continuar
                </Button>
            </Grid>

        </Grid>
    )   
}

export function EjecutarProcesoAutomatico({data, cerrarModal}){
    const [fechaEjecucion, setFechaEjecucion] = useState(data.fechaActual);   
    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false);

    const continuar = () =>{
        setLoader(true);
        instance.post('/admin/procesos/automaticos/ejecutar', {codigo: data.proautid}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }    

    return (
        <Grid container spacing={2}>
            <Box style={{width: '20%', margin: 'auto'}}>
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box className='animate__animated animate__rotateIn'>
                        <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={enviarRadicado} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                    </Box>
                </Grid>
            </Box>

            {(data.esFechaActual === 'NO') ? 
                <Fragment>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                            Antes de proceder a ejecutar el proceso automático para la fecha {fechaEjecucion}, asegúrese de haber revisado y corregido los errores por el cual no se ejecutó el proceso.
                        </p>

                        <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.5em', textAlign: 'center'}}>
                            ¿Desea continuar con la ejecución de este proceso?
                        </p>
                    </Grid>

                    <Grid item xl={6} md={6} sm={6} xs={6}>
                        <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                            startIcon={<ClearIcon />}> Cancelar
                        </Button>
                    </Grid> 

                    <Grid item xl={6} md={6} sm={6} xs={6}>
                        <Button onClick={continuar} className='modalBtn' disabled={(habilitado) ? false : true}
                            startIcon={<StartIcon />}> Continuar
                        </Button>
                    </Grid>
                </Fragment>
            : 
                <Fragment>
                     <Grid item xl={12} md={12} sm={12} xs={12}>
                        <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'center'}}>
                            Ya hay un registro existente para este proceso con la fecha {fechaEjecucion}.
                        </p>
                    </Grid>

                    <Grid item xl={6} md={6} sm={6} xs={6}>
                        <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                            startIcon={<ClearIcon />}> Cancelar
                        </Button>
                    </Grid> 

                </Fragment>
                }

        </Grid>
    )
}

export function FirmarContratoAsociado({contratoId, firmaId, cerrarModal, verificarContrato}){

    const [formData, setFormData] = useState({contratoId: contratoId, firmaId:firmaId, tokenId:'',  token: ''});
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [tiempoRestante, setTiempoRestante] = useState(0);
    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false);
    const [mensaje, setMensaje] = useState('');   

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const continuar = () =>{
        setLoader(true);
        instance.post('/salve/firmar/contrato/asociado', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? (setHabilitado(false), setTiempoRestante(0), verificarContrato(), setFormData({contratoId: contratoId, firmaId:firmaId, tokenId:'', token: '' })) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/solicitar/token/firmar/contrato/asociado', {contratoId: contratoId, firmaId:firmaId}).then(res=>{
            if(res.success){
                newFormData.tokenId = res.idToken;
                setMensaje(res.mensajeMostrar);
                setTiempoRestante(res.tiempoToken);
                setDatosEncontrados(true);
                setFormData(newFormData);
            }
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={continuar} >

            <Grid container spacing={2}>

                {(datosEncontrados) ?
                    <Fragment>

                        <Box style={{width: '20%', margin: 'auto'}}>
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='animate__animated animate__rotateIn'>
                                    <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={firmarDocumento} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                                </Box>
                            </Grid>
                        </Box>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                                <span dangerouslySetInnerHTML={{__html: mensaje}} /> 
                            </p>
                        </Grid>


                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='tiepoRestante'> Tiempo restante:<Box className="tiempoevaluacion">
                                {(tiempoRestante > 0) ?
                                    <RelojDigital tiempoInicial={tiempoRestante} onTiempoFinalizado={cerrarModal} />
                                : <Box className="tiempoevaluacion">0</Box> }
                                </Box>
                            </Box>

                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={0}>

                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator
                                name={'token'}
                                value={formData.token}
                                label={'Token'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 20}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={6} md={6} sm={6} xs={6}>
                            <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                                startIcon={<ClearIcon />}> Cancelar
                            </Button>
                        </Grid>

                        <Grid item xl={6} md={6} sm={6} xs={6}>
                            <Button type={"submit"} className='modalBtn' disabled={(habilitado) ? false : true}
                                startIcon={<SaveIcon />}>Continuar
                            </Button>
                        </Grid>
                    </Fragment>
                : 
                    <Fragment>
                        <Box style={{width: '20%', margin: 'auto'}}>
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='animate__animated animate__rotateIn'>
                                    <Avatar style={{marginTop: '0.8em', width:'110px', height:'110px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={errorTotalizarFirmas} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 1px 10px 12px'}} /> </Avatar>  
                                </Box>
                            </Grid>
                        </Box>
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box style={{backgroundColor: '#f23501',  border: '1px solid rgb(131, 131, 131)', padding: '5px', color: '#fdfdfd',  borderRadius: '10px'}}>
                                Disculpe, ha ocurrido un error interno al generar el token. 
                                Por favor, intente nuevamente más tarde o póngase en contacto con nuestro equipo de soporte para recibir asistencia
                            </Box>
                        </Grid>

                        <Grid item xl={6} md={6} sm={6} xs={6}>
                            <Button onClick={cerrarModal} className='modalBtnRojo'
                                startIcon={<ClearIcon />}> Cancelar
                            </Button>
                        </Grid>

                    </Fragment>
                }

            </Grid>
        </ValidatorForm> 
    )
}

export function CerrarCaja({saldoCerrar, cerrarModal, mostrarComprobante}){
    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false);

    const continuar = () =>{
        setLoader(true);   
        instance.post('/admin/caja/cerrar/movimiento/salve', {idValor: saldoCerrar}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? (mostrarComprobante(res.dataFactura), setHabilitado(false)) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return ( 
        <Grid container spacing={2}>

            <Grid item xl={2} md={2} sm={2} xs={2}>
                <Box className='animate__animated animate__rotateIn'>
                    <Avatar style={{marginTop: '0.8em', width:'60px', height:'60px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={cerrarCaja} style={{width: '80%', height: '80%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} /> </Avatar>  
                </Box>
            </Grid>

            <Grid item xl={10} md={10} sm={10} xs={10}>
                <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'center'}}>
                    ¿Esta seguro que desea cerrar la caja para el día de hoy?
                </p>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                    startIcon={<ClearIcon />}> Cancelar
                </Button>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={continuar} className='modalBtn' disabled={(habilitado) ? false : true}
                    startIcon={<DeleteIcon />}> Eliminar
                </Button>
            </Grid>
        </Grid>
    )
}


import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import errorTotalizarFirmas from "../../../images/errorTotalizarFirmas.png";
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import sellarDocumento from "../../../images/sellarDocumento.png";
import solicitudFirma from "../../../images/solicitudFirma.png";
import {Box, Grid, Button, Avatar} from "@mui/material";
import DeleteIcon from '@mui/icons-material/Delete';
import ClearIcon from '@mui/icons-material/Clear';
import SaveIcon from '@mui/icons-material/Save';
import showSimpleSnackbar from './snackBar';
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
                        <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={solicitudFirma} style={{width: '95%', height: '95%', objectFit: 'cover', padding: '5px 5px 10px 5px'}} /> </Avatar>  
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
                                <Avatar style={{marginTop: '0.8em', width:'90px', height:'90px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <img src={sellarDocumento} style={{width: '95%', height: '95%', objectFit: 'cover', padding: '5px 5px 10px 5px'}} /> </Avatar>  
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
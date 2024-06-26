import React, {useState, useEffect, Fragment, useRef} from 'react';
import { Button, Grid, MenuItem, Typography, Box, Avatar, FormGroup, FormLabel, FormControlLabel } from '@mui/material';
import { Checkbox, Icon,Table, TableHead, TableBody, TableRow, TableCell, Card } from '@mui/material';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {ButtonFileImg, ContentFile} from "../../../layout/files";
import showSimpleSnackbar from '../../../layout/snackBar';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import {ModalDefaultAuto} from '../../../layout/modal';
import WarningIcon from '@mui/icons-material/Warning';
import PostAddIcon from '@mui/icons-material/PostAdd';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import { Editor } from '@tinymce/tinymce-react';
import VisualizarPdf from '../visualizarPdf';
import Files from "react-files";
import Anexos from '../anexos';

import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers';
import "/resources/scss/fechaDatePicker.scss";
import esLocale from 'dayjs/locale/es';
import dayjs from 'dayjs';

export default function New({id, area, tipo, ruta, volver, mensaje}){ 
    const editorTexto = useRef(null);
    const fecha       = new Date();

    const [formData, setFormData] = useState( 
                                {idCD: (tipo !== 'I') ? id :'000', idCDP:'000', idCDPO:'000',
                                        dependencia: (tipo === 'I') ? area.depeid: '',   serie: '6',       subSerie: '6',         tipoMedio: '',     tipoTramite: '1', 
                                        tipoDestino: '',            fecha: '',      nombreDirigido: '',    cargoDirigido: '',      asunto: '',  
                                        correo: '',                 contenido: '',  tieneAnexo: '',        nombreAnexo: '',        tieneCopia: '', 
                                        nombreCopia: '',            saludo: '',     despedida: '',         tituloPersona: '',      ciudad: '',    
                                        direccionDestinatario: '',  empresa: '',    telefono: '',          responderRadicado: '0',
                                        tipo:tipo
                                }); 

    const [formDataRadicado, setFormDataRadicado] = useState({identificador:'', radicado:'', anioRadicado:fecha.getFullYear(), consecutivoRadicado: '', estado: 'I'});
    const [firmaPersona, setFirmaPersona] = useState([{identificador:'', persona:'',  cargo: '', estado: 'I'}]);
    const [totalAdjunto, setTotalAdjunto] = useState(import.meta.env.VITE_TOTAL_FILES_OFICIO);  
    const [formDataFile, setFormDataFile] = useState({ archivos : []});
    const [formDataDependencia, setFormDataDependencia] = useState([]);
    const [documentosRadicados, setDocumentosRadicados] = useState([]);
    const [anioActual, setAnioActual] = useState(fecha.getFullYear());
    const [radicadosRecibidos, setRadicadosRecibidos] = useState([]);
    const [dependenciaMarcada, setDependenciaMarcada] = useState([]);
    const [totalAdjuntoSubido, setTotalAdjuntoSubido] = useState(0);
    const [anexosDocumento, setAnexosDocumento] = useState([]);
    const [tipoDespedidas, setTipoDespedidas] = useState([]);
    const [cargoLaborales, setCargoLaborales] = useState([]);
    const [fechaMinima, setFechaMinima] = useState(dayjs());    
    const [idDocumento, setIdDocumento] = useState(null);
    const [tipoDestinos, setTipoDestinos] = useState([]);
    const [dependencias, setDependencias] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);
    const [fechaActual, setFechaActual] = useState('');
    const [tipoSaludos, setTipoSaludos] = useState([]);
    const [tipoMedios, setTipoMedios] = useState([]);    
    const [personas, setPersonas] = useState([]);
    const [loader, setLoader] = useState(false);

    const cantidadAdjunto = () =>{
        let totalAdjSubido = parseInt(totalAdjuntoSubido) - 1 ;
        setTotalAdjuntoSubido(totalAdjSubido);
    }

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeDate = (date) => {
        setFormData((prevData) => ({...prevData, fecha: date.format('YYYY-MM-DD')}));
    }

    const handleChangeRadicado = (e) =>{
        setFormDataRadicado(prev => ({...prev, [e.target.name]: e.target.value}))
    }     

    const handleChangeFirmaPersona = (e, index) =>{
        let newFirmaPersona= [...firmaPersona];
        newFirmaPersona[index][e.target.name] = e.target.value; 
        setFirmaPersona(newFirmaPersona);
    }
   
    const handleChangeDependencia = (e) =>{
        let newFormDataDependencia = [...formDataDependencia];
        if(e.target.checked){
            newFormDataDependencia.push({depeid: parseInt(e.target.value)});
        }else{
            //Elimino la posicion
            newFormDataDependencia = formDataDependencia.filter((item) => item.depeid !== parseInt(e.target.value));
        }
        setFormDataDependencia(newFormDataDependencia);
    }

    const onFilesChange = (files , nombre) =>  {
        setFormDataFile(prev => ({...prev, [nombre]: files}));
    }

    const removeFIle = (nombre)=>{
        setFormDataFile(prev => ({...prev, archivos: prev.archivos.filter(item => item.name !== nombre)}));
    }
    
    const onFilesError = (error, file) => {
        let msj = (error.code === 2) ? 'El archivo "'+ file.name + '" es demasiado grande y no se puede subir' : error.message  
        showSimpleSnackbar(msj, 'error');
    }

    const handleSubmit = () =>{
        if(formData.tipoMedio !== 1 && formData.correo === ''){
            showSimpleSnackbar("Debe ingresar el correo", 'error');
            return;
        }

        if(!validateCorreos(formData.correo) && formData.correo !== ''){
            showSimpleSnackbar("El campo de correo electrónico contiene uno o más correos que no tienen una estructura válida", 'error');
            return;
        }

        if(editorTexto.current.getContent() === ''){
            showSimpleSnackbar("Debe ingresar el contenido del documento", 'error');
            return;
        }

        //En el momento de enviar la peticion no muestra los cambio en el tyminice
        let formDataCopia       = {...formData};
        formDataCopia.contenido = editorTexto.current.getContent();

        let newFormData                 = {...formData};
        newFormData.contenido           = editorTexto.current.getContent()
        newFormData.firmaPersonas       = firmaPersona;
        newFormData.archivos            = formDataFile.archivos;
        newFormData.copiasDependencia   = formDataDependencia;
        newFormData.documentosRadicados = documentosRadicados;

        setLoader(true);
        setFormData(formDataCopia);
        let rutaSalve    = (ruta === 'P') ? '/admin/producion/documental/oficio/salve' : '/admin/firmar/documento/oficio/salve';
        instance.post(rutaSalve, newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo === 'I' && res.success) ? setFormData({idCD: (tipo !== 'I') ? id :'000', idCDP:'000', idCDPO:'000',
                                                                    dependencia: (tipo === 'I') ? area.depeid: '',   serie: '6',       subSerie: '6',         tipoMedio: '',     tipoTramite: '1', 
                                                                    tipoDestino: '',            fecha: '',      nombreDirigido: '',    cargoDirigido: '',      asunto: '',  
                                                                    correo: '',                 contenido: '',  tieneAnexo: '',        nombreAnexo: '',        tieneCopia: '', 
                                                                    nombreCopia: '',            saludo: '',     despedida: '',         tituloPersona: '',      ciudad: '',    
                                                                    direccionDestinatario: '',  empresa: '',    telefono: '',          responderRadicado: '0',
                                                                    tipo:tipo}) : null;
            (formData.tipo === 'I' && res.success) ? setFirmaPersona([{identificador:'', persona:'',  cargo: '', estado: 'I'}]) : null;
            if(formData.tipo === 'I' && res.success){
                let newFormDataDependencia = [];
                formDataDependencia.forEach(function(dep){
                    newFormDataDependencia.push({
                        depeid: dep.depeid
                    });
                });
                setDependenciaMarcada(newFormDataDependencia);
            }
            (res.success && ruta === 'P') ? (setIdDocumento(res.idDocumento), setAbrirModal(true) ) : null;
            setLoader(false);
        })
    }

    const adicionarFilaRadicado= () =>{

        if(formDataRadicado.anioRadicado === ''){
            showSimpleSnackbar('Debe ingresar el año del radicado', 'error');
            return
        }

        if(formDataRadicado.consecutivoRadicado === ''){
            showSimpleSnackbar('Debe ingresar el consecutivo del radicado', 'error');
            return
        }

        if(documentosRadicados.some((radicado) => radicado.radicadoId === formDataRadicado.radicado)){
            showSimpleSnackbar('Este registro ya existe', 'error');
            return
        }

        //Consulta los radicados de la base de datos
        let radicadoId   = '';
        radicadosRecibidos.map((rad) =>{
           if( parseInt(rad.radoenanio) ===  parseInt(formDataRadicado.anioRadicado) && rad.radoenconsecutivo === formDataRadicado.consecutivoRadicado ){
            radicadoId = rad.radoenid;
           }
        })

        if(radicadoId === ''){
            showSimpleSnackbar('El radicado que busca no existe o no ha sido recibido en la bandeja de radicados', 'error');
            return
        }

        let newDocumentosRadicados = [...documentosRadicados];
        newDocumentosRadicados.push({identificador: formDataRadicado.identificador, radicado: radicadoId, anioRadicado:formDataRadicado.anioRadicado, 
                                    consecutivoRadicado:formDataRadicado.consecutivoRadicado, estado: 'I'});
        setDocumentosRadicados(newDocumentosRadicados);
        setFormDataRadicado({identificador:'', radicado:'', anioRadicado:anioActual, consecutivoRadicado: '', estado: 'I'});
    }

    const eliminarFirmaRadicado = (id) =>{
        let newDatosRadicado = []; 
        documentosRadicados.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newDatosRadicado.push({ identificador:res.identificador, radicado: res.radicado, anioRadicado: res.anioRadicado, consecutivoRadicado:res.consecutivoRadicado, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newDatosRadicado.push({identificador:res.identificador, radicado: res.radicado, anioRadicado: res.anioRadicado, consecutivoRadicado:res.consecutivoRadicado, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newDatosRadicado.push({identificador:res.identificador, radicado: res.radicado, anioRadicado: res.anioRadicado, consecutivoRadicado:res.consecutivoRadicado, estado:res.estado});
            }else{
                if(i != id){
                    newDatosRadicado.push({identificador:res.identificador, radicado: res.radicado, anioRadicado: res.anioRadicado, consecutivoRadicado:res.consecutivoRadicado, estado: 'I' });
                }
            }
        })
        setDocumentosRadicados(newDatosRadicado);
    }

    const adicionarFilaFirmaPersona = () =>{
        let newFirmaPersona = [...firmaPersona];
        newFirmaPersona.push({identificador:'', persona:'',  cargo: '',  estado: 'I'});
        setFirmaPersona(newFirmaPersona);
    }

    const eliminarFirmaPersona = (id) =>{
        let newDatosFirmaPersona = [];
        firmaPersona.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newDatosFirmaPersona.push({ identificador:res.identificador, persona: res.persona, cargo:res.cargo, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newDatosFirmaPersona.push({identificador:res.identificador,  persona: res.persona, cargo:res.cargo, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newDatosFirmaPersona.push({identificador:res.identificador, persona: res.persona, cargo:res.cargo, estado:res.estado});
            }else{
                if(i != id){
                    newDatosFirmaPersona.push({identificador:res.identificador, persona: res.persona, cargo:res.cargo, estado: 'I' });
                }
            }
        })
        setFirmaPersona(newDatosFirmaPersona);
    }

    const validateCorreos = (cadena) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const correos = cadena.split(',').map(correo => correo.trim());
        for (const correo of correos) {
            if (!emailRegex.test(correo)) {
                return false;
            }
        }
        return true;
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData}
        let rutaData    = (ruta === 'P') ? '/admin/producion/documental/oficio/listar/datos' : '/admin/firmar/documento/editar/documento';
        instance.post(rutaData, {id: id, tipo: tipo, dependencia: formData.dependencia, tipoDocumental: 'O'}).then(res=>{
            (tipo === 'I') ? setFechaActual(res.fechaActual): null;
            (tipo === 'I') ? setFechaMinima(dayjs(res.fechaActual, 'YYYY-MM-DD')): null;
            setTipoDestinos(res.tipoDestinos);
            setTipoMedios(res.tipoMedios);
            setTipoSaludos(res.tipoSaludos);
            setTipoDespedidas(res.tipoDespedidas);
            setDependencias(res.dependencias);
            setPersonas(res.personas);
            setCargoLaborales(res.cargoLaborales);
            setRadicadosRecibidos(res.radicadosRecibidos);
            newFormData.fecha = res.fechaActual;

            if(tipo === 'U'){
                let tpDocumental                  = res.data;
                let firmasDocumento               = res.firmasDocumento;
                let copiaDependenciaMarcadas      = res.copiaDependencias;
                let radicadosDocumento            = res.radicadosDocumento;
                let anexosDocumento               = res.anexosDocumento;

                newFormData.idCD                  = tpDocumental.coddocid;
                newFormData.idCDP                 = tpDocumental.codoprid;
                newFormData.idCDPO                = tpDocumental.id;
                newFormData.dependencia           = tpDocumental.depeid;
                newFormData.serie                 = tpDocumental.serdocid;
                newFormData.subSerie              = tpDocumental.susedoid;
                newFormData.tipoMedio             = tpDocumental.tipmedid;
                newFormData.tipoTramite           = tpDocumental.tiptraid;
                newFormData.tipoDestino           = tpDocumental.tipdetid;
                newFormData.fecha                 = tpDocumental.codoprfecha;
                newFormData.nombreDirigido        = tpDocumental.codoprnombredirigido; 
                newFormData.cargoDirigido         = tpDocumental.codoprcargonombredirigido;
                newFormData.asunto                = tpDocumental.codoprasunto;
                newFormData.correo                = (tpDocumental.codoprcorreo !== null) ? tpDocumental.codoprcorreo : '';
                newFormData.contenido             = tpDocumental.codoprcontenido;
                newFormData.tieneAnexo            = tpDocumental.codoprtieneanexo.toString();
                newFormData.nombreAnexo           = (tpDocumental.codopranexonombre !== null) ? tpDocumental.codopranexonombre : '';
                newFormData.tieneCopia            = tpDocumental.codoprtienecopia.toString();
                newFormData.nombreCopia           = (tpDocumental.codoprcopianombre !== null) ? tpDocumental.codoprcopianombre : '';
                newFormData.responderRadicado     = tpDocumental.codoporesponderadicado;
                newFormData.saludo                = tpDocumental.tipsalid;
                newFormData.despedida             = tpDocumental.tipdesid;
                newFormData.tituloPersona         = tpDocumental.codopotitulo;
                newFormData.ciudad                = tpDocumental.codopociudad;           
                newFormData.empresa               = (tpDocumental.codopoempresa !== null) ? tpDocumental.codopoempresa : '';
                newFormData.direccionDestinatario = tpDocumental.codopodireccion;
                newFormData.telefono              = (tpDocumental.codopotelefono !== null) ? tpDocumental.codopotelefono : '';
                newFormData.totalAdjuntoSubido    = tpDocumental.totalAnexos;

                let newDocumentosRadicados = [];
                radicadosDocumento.forEach(function(rad){
                    newDocumentosRadicados.push({
                        identificador:       rad.cdprdeid,
                        radicado:            rad.radoenid,
                        anioRadicado:        rad.radoenanio,
                        consecutivoRadicado: rad.radoenconsecutivo,
                        estado: 'U'
                    });
                });

                let newFirmasDocumento = [];
                firmasDocumento.forEach(function(frm){
                    newFirmasDocumento.push({
                        identificador: frm.codopfid,
                        persona: frm.persid,
                        cargo: frm.carlabid,
                        estado: 'U'
                    });
                }); 

                let newFormDataDependencia = [];
                setDependenciaMarcada(copiaDependenciaMarcadas);
                copiaDependenciaMarcadas.forEach(function(dep){
                    newFormDataDependencia.push({
                        depeid: dep.depeid
                    });
                });

                setFormDataDependencia(newFormDataDependencia);
                setTotalAdjuntoSubido(tpDocumental.totalAnexos);
                setDocumentosRadicados(newDocumentosRadicados);
                setFechaMinima(dayjs(tpDocumental.codoprfecha, 'YYYY-MM-DD'));
                setFechaActual(tpDocumental.codoprfecha);
                setFirmaPersona(newFirmasDocumento);
                setAnexosDocumento(anexosDocumento);
            }
            setFormData(newFormData);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <ValidatorForm onSubmit={handleSubmit} >
                <Box>
                    <Typography component={'h1'} className={'titleProductorDocumento'}>{mensaje}</Typography>
                </Box>
                <Card style={{padding:'5px',marginTop: '5px'}}>                

                    <Grid container spacing={2} style={{display: 'flex', justifyContent: 'space-between'}}>

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

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoDestino'}
                                value={formData.tipoDestino}
                                label={'Tipo destino'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoDestinos.map(res=>{
                                    return <MenuItem value={res.tipdetid} key={res.tipdetid} >{res.tipdetnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid> 

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoMedio'}
                                value={formData.tipoMedio}
                                label={'Tipo de medio'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoMedios.map(res=>{
                                    return <MenuItem value={res.tipmedid} key={res.tipmedid} >{res.tipmednombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator
                                multiline
                                maxRows={3}
                                name={'correo'}
                                value={formData.correo}
                                label={'Correo (Si desea enviar varios correos sepárelos con una coma ",")'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 1000}}
                                onChange={handleChange}
                                onBlur={() => {
                                    if (formData.correo && !validateCorreos(formData.correo)) {
                                        showSimpleSnackbar("El campo de correo electrónico contiene uno o más correos que no tienen una estructura válida", 'error');
                                    }
                                }}
                                disabled={(formData.tipoMedio === 1) ? true : false}
                            />
                        </Grid>
                    </Grid>

                    <Grid container spacing={2} style={{marginTop:'1px'}}>

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator 
                                name={'tituloPersona'}
                                value={formData.tituloPersona}
                                label={'Título'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 80}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator 
                                multiline
                                maxRows={3}
                                name={'nombreDirigido'}
                                value={formData.nombreDirigido}
                                label={'Nombre de la persona que va dirigido'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 4000}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator 
                                name={'cargoDirigido'}
                                value={formData.cargoDirigido}
                                label={'Cargo'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 1000}}
                                onChange={handleChange}
                            />
                        </Grid>

                        {(formData.tipoDestino !== 1) ? 
                            <Fragment>

                                <Grid item xl={4} md={4} sm={6} xs={12}>
                                    <TextValidator 
                                        name={'empresa'}
                                        value={formData.empresa}
                                        label={'Nombre de la empresa'}
                                        className={'inputGeneral'} 
                                        variant={"standard"} 
                                        inputProps={{autoComplete: 'off', maxLength: 80}}
                                        onChange={handleChange}
                                    />
                                </Grid>

                                <Grid item xl={4} md={4} sm={6} xs={12}>
                                    <TextValidator 
                                        name={'direccionDestinatario'}
                                        value={formData.direccionDestinatario}
                                        label={'Dirección de la empresa o destinatario'}
                                        className={'inputGeneral'} 
                                        variant={"standard"} 
                                        inputProps={{autoComplete: 'off', maxLength: 80}}
                                        onChange={handleChange}
                                    />
                                </Grid>

                                <Grid item xl={4} md={4} sm={6} xs={12}>
                                    <TextValidator 
                                        name={'telefono'}
                                        value={formData.telefono}
                                        label={'Teléfono de la empresa o destinatario'}
                                        className={'inputGeneral'} 
                                        variant={"standard"} 
                                        inputProps={{autoComplete: 'off', maxLength: 20}}
                                        onChange={handleChange}
                                    />
                                </Grid>

                            </Fragment>
                        : null}                  

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator 
                                name={'ciudad'}
                                value={formData.ciudad}
                                label={'Ciudad'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 80}}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator 
                                multiline
                                maxRows={2}
                                name={'asunto'}
                                value={formData.asunto}
                                label={'Asunto'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 200}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'saludo'}
                                value={formData.saludo}
                                label={'Saludo'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoSaludos.map(res=>{
                                    return <MenuItem value={res.tipsalid} key={res.tipsalid} >{res.tipsalnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <SelectValidator
                                name={'responderRadicado'}
                                value={formData.responderRadicado}
                                label={'Responder radicado'}
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

                        {(formData.responderRadicado.toString() === '1') ?
                            <Fragment>
                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                    <Box  style={{width: '50%', margin: 'auto'}}>

                                        <Card style={{padding: '0.5em 1em 1em 1em'}}>
                                            <Grid container spacing={2}>

                                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                                    <Box className='frmDivision'>
                                                        Adicionar radicados al tipo documental
                                                    </Box>
                                                </Grid>

                                                <Grid item xl={5} md={5} sm={6} xs={12}>
                                                    <TextValidator
                                                        name={'anioRadicado'}
                                                        value={formDataRadicado.anioRadicado}
                                                        label={'Año del radicado'}
                                                        className={'inputGeneral'} 
                                                        variant={"standard"} 
                                                        inputProps={{autoComplete: 'off'}}
                                                        validators={["maxNumber:9999"]}
                                                        errorMessages={["Número máximo permitido es el 9999"]}
                                                        type={"number"}
                                                        onChange={handleChangeRadicado}
                                                    />
                                                </Grid>

                                                <Grid item xl={5} md={5} sm={6} xs={12}>
                                                    <TextValidator
                                                        name={'consecutivoRadicado'}
                                                        value={formDataRadicado.consecutivoRadicado}
                                                        label={'Número de radicado'}
                                                        className={'inputGeneral'} 
                                                        variant={"standard"} 
                                                        inputProps={{autoComplete: 'off'}}
                                                        validators={["maxNumber:9999"]}
                                                        errorMessages={["Número máximo permitido es el 9999"]}
                                                        onChange={handleChangeRadicado}
                                                    />
                                                </Grid>

                                                <Grid item xl={2} md={2} sm={4} xs={12}>
                                                    <Button type={"button"} className={'modalBtn'}  onClick={() => {adicionarFilaRadicado();}}
                                                        startIcon={<PostAddIcon />}> {"Adicionar"}
                                                    </Button>
                                                </Grid>

                                                {(documentosRadicados.length > 0) ?
                                                    <Grid item xl={12} md={12} sm={12} xs={12}>
                                                        <Table key={'tableRadicadoDocumento'}  className={'tableAdicional'} style={{marginTop: '1px'}} >
                                                            <TableHead>
                                                                <TableRow>
                                                                    <TableCell>Año del radicado</TableCell>
                                                                    <TableCell>Número del radicado</TableCell>
                                                                    <TableCell style={{width: '10%'}} className='cellCenter'>Eliminar </TableCell>
                                                                </TableRow>
                                                            </TableHead>
                                                            <TableBody>
                                                            { documentosRadicados.map((radicado, a) => {
                                                                return(
                                                                    <TableRow key={'rowA-' +a} className={(radicado.estado == 'D')? 'tachado': null}>

                                                                        <TableCell>
                                                                            {radicado['anioRadicado']}
                                                                        </TableCell>

                                                                        <TableCell>
                                                                            {radicado['consecutivoRadicado']}
                                                                        </TableCell>
                                                                    
                                                                        <TableCell className='cellCenter'>
                                                                            <Icon key={'iconDelete'+a} className={'icon top red'}
                                                                                onClick={() => {eliminarFirmaRadicado(a);}} title={'Eliminar'}
                                                                            >clear</Icon>
                                                                        </TableCell>
                                                                    </TableRow>
                                                                    );
                                                                })
                                                            }
                                                            </TableBody>
                                                        </Table>
                                                    </Grid>
                                                : null}

                                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                                    <p style={{fontSize:'0.8em', color: '#7c7777', textAlign: 'justify'}}> 
                                                        <b>Nota:</b> Solo se permiten radicados que hayan sido aceptados, 
                                                        marcados como que se requiere una respuesta y que no hayan
                                                        sido respondidos por un tipo documental.
                                                    </p>
                                                </Grid>

                                            </Grid>
                                        </Card>
                                    </Box> 
                                </Grid>

                            </Fragment>
                        : null}

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <label className={'labelEditor'}> Contenido </label>
                            <Editor 
                                onInit={(evt, editor) => editorTexto.current = editor}
                                initialValue = {formData.contenido}
                                init={{
                                    language: 'es',
                                    height: 400,
                                    object_resizing : true,
                                    browser_spellcheck: true,                                    
                                    spellchecker_language: 'es',
                                    
                                    spellchecker_wordchar_pattern: /[^\s,\.]+/g ,
                                    menubar: 'file edit view insert format tools table',
                                    plugins: 'advlist autolink lists  image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
                                    toolbar: 'undo redo | fontsize fontsizeselect | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor link',
                                    menu:{
                                        file: {title: 'File', items: 'newdocument'},
                                        edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall searchreplace'},
                                        view: {title: 'View', items: 'visualaid  | fullscreen'},
                                        insert: {title: 'Insert', items: 'link  | hr | inserttable'},
                                        format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript'},
                                        tools: {title: 'tools', items: 'wordcount'},
                                        table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'}
                                    },

                                    fontsize_formats: '8pt 9pt 10pt 11pt 12pt',
                                }}
                            />
                        </Grid>

                        <Grid item xl={6} md={6} sm={6} xs={12}>
                            <SelectValidator
                                name={'despedida'}
                                value={formData.despedida}
                                label={'Despedida'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoDespedidas.map(res=>{
                                    return <MenuItem value={res.tipdesid} key={res.tipdesid} >{res.tipdesnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'tieneAnexo'}
                                value={formData.tieneAnexo}
                                label={'Anexar anexos'}
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

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'tieneCopia'}
                                value={formData.tieneCopia}
                                label={'Anexar copia'}
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

                        { (totalAdjuntoSubido > 0) ?
                            <Grid item md={12} xl={12} sm={12} xs={12} >
                                <Anexos data={anexosDocumento} eliminar={'false'} cantidadAdjunto={cantidadAdjunto}/>
                            </Grid>
                        : null }

                        {(formData.tieneAnexo === '1') ?
                            <Fragment>

                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                    <Box className='frmDivision'>
                                        Anexar documentos al tipo documental si se prosentan 
                                    </Box>
                                </Grid>

                                <Grid item xl={12} md={12} sm={12} xs={12}>
                                    <TextValidator 
                                        multiline
                                        maxRows={2}
                                        name={'nombreAnexo'}
                                        value={formData.nombreAnexo}
                                        label={'Nombre del anexo'}
                                        className={'inputGeneral'} 
                                        variant={"standard"} 
                                        inputProps={{autoComplete: 'off', maxLength: 300}}
                                        onChange={handleChange}
                                    />
                                </Grid>

                                {(totalAdjunto > totalAdjuntoSubido) ?
                                    <Grid item md={5} xl={5} sm={12} xs={12}>
                                        <Files
                                            className='files-dropzone'
                                            onChange={(file ) =>{onFilesChange(file, 'archivos') }}
                                            onError={onFilesError}
                                            accepts={['.jpg', '.png', '.jpeg', '.doc', '.docx', '.pdf','.ppt', '.pptx', '.xls', '.xlsx', '.xlsm', '.zip', '.rar']} 
                                            multiple
                                            maxFiles={totalAdjunto - totalAdjuntoSubido}
                                            maxFileSize={1000000}
                                            clickable
                                            dropActiveClassName={"files-dropzone-active"}
                                        >
                                        <ButtonFileImg title={"Adicionar anexos"} />
                                        </Files>
                                    </Grid>
                                : null }

                                <Grid item md={6} xl={6} sm={12} xs={12}>
                                    <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                                        {formDataFile.archivos.map((file, a) =>{
                                            return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
                                        })}
                                    </Box>
                                </Grid> 

                                <Grid item xl={12} md={12} sm={12} xs={12}>
                                    <Box className={'msgAlert'}>
                                        <Avatar className={'avatar'}> <WarningIcon /></Avatar> 
                                        <p>Nota: Recuerde que puede subir como máximo ({totalAdjunto}) archivos, actualmente ha subido ({totalAdjuntoSubido}) archivos. Los formatos permitidos son .PDF, .DOCX, .DOC, .PPT, .PPTX, .XLS, XLSX, .ZIP, .RAR, .JPG y .PNG</p>
                                    </Box>
                                </Grid>
                            </Fragment>
                        : null}

                        {(formData.tieneCopia === '1') ?
                            <Fragment>

                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                    <Box className='frmDivision'>
                                        Anexar copias al tipo documental si se prosentan 
                                    </Box>
                                </Grid>

                                <Grid item xl={12} md={12} sm={12} xs={12}>
                                    <TextValidator 
                                        multiline
                                        maxRows={2}
                                        name={'nombreCopia'}
                                        value={formData.nombreCopia}
                                        label={'Nombre de la copia'}
                                        className={'inputGeneral'} 
                                        variant={"standard"} 
                                        inputProps={{autoComplete: 'off', maxLength: 300}}
                                        onChange={handleChange}
                                    />
                                </Grid>

                                <Grid item xl={12} md={12} sm={12} xs={12}>
                                    <FormLabel component="legend">Listado de dependencia para asignar copias al tipo documental</FormLabel>
                                    <FormGroup row name={"dependencias"} 
                                        value={formDataDependencia.depeid}
                                        onChange={handleChangeDependencia}
                                        >
                                        {dependencias.map(res=>{
                                            const marcado  = dependenciaMarcada.find(resul => resul.depeid === res.depeid);
                                            const checkbox = (marcado !== undefined) ? <Checkbox color="secondary" defaultChecked /> : <Checkbox color="secondary"  />;  
                                        
                                            const frmCheckbox = <Grid item xl={4} md={4} sm={6} xs={12} key={res.depenombre} >
                                                                    <FormControlLabel value={res.depeid} label={res.depenombre} control={checkbox} />
                                                                </Grid>
                                            return frmCheckbox;
                                        })}
                                    </FormGroup>
                                </Grid>
                            </Fragment>
                        : null}

                        <Grid item xl={12} md={12} sm={12} xs={12}> 
                            <Box className='frmDivision'>Adicionar personas que firma el tipo documental</Box>
                            <Table key={'tableFirmaPersona'}  className={'tableAdicional'} style={{marginTop: '5px'}} >
                                <TableHead>
                                    <TableRow>
                                        <TableCell style={{width: '50%'}}>Nombre de la persona</TableCell>
                                        <TableCell style={{width: '40%'}}>Cargo </TableCell> 
                                        <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>

                                { firmaPersona.map((frmPers, a) => { 
                                    return(
                                        <TableRow key={'rowA-' +a} className={(frmPers.estado == 'D')? 'tachado': null}>
                                            <TableCell>
                                                <SelectValidator
                                                    name={'persona'}
                                                    value={frmPers['persona']}
                                                    label={'Nombre de la persona'}
                                                    className={'inputGeneral'} 
                                                    variant={"standard"} 
                                                    inputProps={{autoComplete: 'off'}}
                                                    validators={["required"]}
                                                    errorMessages={["Campo obligatorio"]}
                                                    onChange={(e) => {handleChangeFirmaPersona(e, a)}}
                                                >
                                                <MenuItem value={""}>Seleccione</MenuItem>
                                                {personas.map(res=>{
                                                    return <MenuItem value={res.persid} key={res.persid} >{res.nombrePersona}</MenuItem>
                                                })}
                                                </SelectValidator>
                                            </TableCell>

                                            <TableCell>
                                                <SelectValidator
                                                    name={'cargo'}
                                                    value={frmPers['cargo']}
                                                    label={'Cargo laboral'}
                                                    className={'inputGeneral'} 
                                                    variant={"standard"} 
                                                    inputProps={{autoComplete: 'off'}}
                                                    validators={["required"]}
                                                    errorMessages={["Campo obligatorio"]}
                                                    onChange={(e) => {handleChangeFirmaPersona(e, a)}}
                                                >
                                                <MenuItem value={""}>Seleccione</MenuItem>
                                                {cargoLaborales.map((res, i) =>{
                                                    return <MenuItem value={res.carlabid} key={res.carlabid} >{res.carlabnombre}</MenuItem>
                                                })}
                                                </SelectValidator>
                                            </TableCell>

                                            <TableCell className='cellCenter'>
                                                {(a !== 0)?
                                                <Icon key={'iconDelete'+a} className={'icon top red'}
                                                        onClick={() => {eliminarFirmaPersona(a);}} title={'Eliminar registro'}
                                                    >clear</Icon>
                                                    : <Icon key={'iconAdd'} className={'icon top green'} title={'Adicionar firma'}
                                                        onClick={() => {adicionarFilaFirmaPersona()}}
                                                    >add</Icon>
                                                }
                                            </TableCell>
                                        </TableRow>
                                        );
                                    })
                                }
                                </TableBody>
                            </Table>
                        </Grid>

                    </Grid>

                    <Grid container spacing={2}>
                        <Grid item xl={3} md={3} sm={4} xs={12}>
                            {(ruta === 'P') ? 
                                <Button type={"button"} className={'modalBtn'} onClick={() => {volver()}}
                                    startIcon={<ArrowBackIcon />}> Volver
                                </Button>
                            : null }
                        </Grid>
                        <Grid item xl={9} md={9} sm={8} xs={12} style={{textAlign:'right'}}>
                            <Button type={"submit"} className={'modalBtn'}
                                startIcon={<SaveIcon />}> {(tipo=== 'I') ? "Guardar" : "Actualizar"}
                            </Button>
                        </Grid>
                    </Grid>
                </Card>
            </ValidatorForm>

            <ModalDefaultAuto
                title   = {'Visualizar el tipo documental en formato PDF'}
                content = {<VisualizarPdf id={idDocumento} ruta={'oficio'} />}
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'mediumFlot' 
                abrir   = {abrirModal}
            />

        </Box>
    );
}
import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Link, Table, TableHead, TableBody, TableRow, TableCell, Avatar} from '@mui/material';
import CloudDownloadIcon from '@mui/icons-material/CloudDownload';
import {ButtonFileImg, ContentFile} from "../../layout/files";
import VisibilityIcon from '@mui/icons-material/Visibility';
import CertificadoConductor from './certificadoConductor'
import { ModalDefaultAuto  } from '../../layout/modal';
import showSimpleSnackbar from '../../layout/snackBar';
import WarningIcon from '@mui/icons-material/Warning';
import ErrorIcon from '@mui/icons-material/Error';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import ShowAnexo from '../vehiculos/showAnexo';
import instance from '../../layout/instance';

import Files from "react-files";

export default function New({data, tipo, frm, url, tpRelacion}){

    let cargoLaboral = (frm === 'ASOCIADO') ? '2' : ((frm === 'CONDUCTOR') ? '3' : '')

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo:data.persid,  tipo:tipo, formulario:frm
                                    } : {codigo:'000', documento:'', cargo: cargoLaboral, tipoIdentificacion: '', tipoPersona:tpRelacion, departamentoNacimiento:'', municipioNacimiento:'',
                                        departamentoExpedicion:'', municipioExpedicion:'', primerNombre:'', segundoNombre: '', primerApellido: '', 
                                        segundoApellido:'', fechaNacimiento:'',   direccion:'', correo:'', fechaExpedicion: '', telefonoFijo: '', numeroCelular:'', genero:'',firma:'', foto:'',
                                        estado: '1', firmaDigital: '0', claveCertificado:'',  rutaCrt:'', rutaPem:'', tipo:tipo, formulario:frm, fechaIngresoAsociado:'', fechaIngresoConductor:'',
                                        tipoConductor:'', agencia:'', tipoCategoria:'', numeroLicencia:'', fechaExpedicionLicencia:'', fechaVencimiento:'', firmaElectronica:'',
                                        rutaCrtOld:'', rutaPemOld:'', rutaDescargaCertificado:'', rutaCertificadoAsociado:'', rutaCertificadoAsociadoOld:''
                                }); 

    const [formDataFile, setFormDataFile] = useState({ fotografia: [], firma: [], rutaCrt:[], rutaPem: [], rutaCertificadoAsociado: [], imagenLicencia:[], certificadoConductor:[]});
    const [totalCertificado, setTotalCertificado] = useState(import.meta.env.VITE_TOTAL_FILES_CERTIFICADO_CONDUCTOR);
    const [extencionArchivoLicencia, setExtencionArchivoLicencia] = useState(null);
    const [modal, setModal] = useState({open : false, extencion:'', ruta:''}); 
    const [tipoCategoriaLicencias, setTipoCategoriaLicencias] = useState([]); 
    const [totalCertificadoSubido, setTotalCertificadoSubido] = useState(0);
    const [conductorCertificados, setConductorCertificados] = useState([]);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [municipiosNacimiento, setMunicipiosNacimiento] = useState([]);
    const [municipiosExpedicion, setMunicipiosExpedicion] = useState([]);
    const [rutaArchivoEnfuscada, setRutaArchivoEnfuscada] = useState('');    
    const [rutaLicenciaAdjunta, setRutaLicenciaAdjunta] = useState('');
    const [historialLicencias, setHistorialLicencias] = useState([]);
    const [tipoCargoLaborales, setTipoCargoLaborales] = useState([]);    
    const [tipoConductores, setTipoConductores] = useState([]);
    const [showFotografia, setShowFotografia] = useState('');
    const [showFirmaPersona, setFirmaPersona] = useState('');
    const [departamentos, setDepartamentos] = useState([]);
    const [habilitado, setHabilitado] = useState(true); 
    const [municipios, setMunicipios] = useState([]);
    const [agencias, setAgencias] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    }

    const onFilesChange = (files , nombre) =>  {
        setFormDataFile(prev => ({...prev, [nombre]: files}));
    }

    const removeFIleFotografia = (nombre)=>{ 
        setFormDataFile(prev => ({...prev, fotografia: prev.fotografia.filter(item => item.name !== nombre)}));
    }

    const removeFIleFirma = (nombre)=>{ 
        setFormDataFile(prev => ({...prev, firma: prev.firma.filter(item => item.name !== nombre)}));
    }

    const removeFIleRutaCrt = (nombre)=>{ 
        setFormDataFile(prev => ({...prev, rutaCrt: prev.rutaCrt.filter(item => item.name !== nombre)}));
    }

    const removeFIleRutaPem = (nombre)=>{ 
        setFormDataFile(prev => ({...prev, rutaPem: prev.rutaPem.filter(item => item.name !== nombre)}));
    }   

    const removeFIleRutaCertificadoAsociado = (nombre)=>{ 
        setFormDataFile(prev => ({...prev, rutaCertificadoAsociado: prev.rutaCertificadoAsociado.filter(item => item.name !== nombre)}));
    }

    const removeFIleImagenLicencia = (nombre)=>{ 
        setFormDataFile(prev => ({...prev, imagenLicencia: prev.imagenLicencia.filter(item => item.name !== nombre)}));
    }

    const removeFIleCertificadoConductor = (nombre)=>{ 
        setFormDataFile(prev => ({...prev, certificadoConductor: prev.certificadoConductor.filter(item => item.name !== nombre)}));
    }

    const onFilesError = (error, file) => {
        let msj = (error.code === 2) ? 'El archivo "'+ file.name + '" es demasiado grande y no se puede subir' : error.message  
        showSimpleSnackbar(msj, 'error');
    }

    const cantidadAdjunto = () =>{
        let totalAdjSubido = parseInt(totalCertificadoSubido) - 1 ;
        setTotalCertificadoSubido(totalAdjSubido);
    }

    const handleSubmit = () =>{
        let rutaCrt                 = formDataFile.rutaCrt;
        let rutaPem                 = formDataFile.rutaPem;
        let fotografia              = formDataFile.fotografia;
        let firma                   = formDataFile.firma;
        let imagenLicencia          = formDataFile.imagenLicencia;
        let rutaCertificadoAsociado = formDataFile.rutaCertificadoAsociado;

        if(formData.firmaElectronica.toString() === '1' && formData.correo === ''){
            showSimpleSnackbar("El campo correo es obligatorio cuando el campo tiene firma electronica es sí", 'error');
            return;
        }

        if(tipo === '' && formData.firmaDigital.toString() === '1' && rutaCrt.length < 1){
            showSimpleSnackbar("Debe subir el certificado digital en formato crt", 'error');
            return;
        }

        if(tipo === '' && formData.firmaDigital.toString() === '1' && rutaPem.length < 1){
            showSimpleSnackbar("Debe subir el certificado digital en formato pem", 'error');
            return;
        }

        /* let certificadoConductor    = formDataFile.certificadoConductor;

        let dataFile = new FormData();
        Object.keys(formData).forEach(function(key) {
           dataFile.append(key, formData[key])
        })

        dataFile.append('firma', (firma[0] != undefined) ? firma[0] : '');
        dataFile.append('fotografia', (fotografia[0] != undefined) ? fotografia[0] : '');
        dataFile.append('rutaCrt', (rutaCrt[0] != undefined) ? rutaCrt[0] : '');
        dataFile.append('rutaPem', (rutaPem[0] != undefined) ? rutaPem[0] : '');
        dataFile.append('rutaCertificadoAsociado', (rutaCertificadoAsociado[0] != undefined) ? rutaCertificadoAsociado[0] : '');
        dataFile.append('imagenLicencia', (imagenLicencia[0] != undefined) ? imagenLicencia[0] : '');
        dataFile.append('certificadoConductor', (certificadoConductor[0] != undefined) ? certificadoConductor : '');*/

        let newFormData                     = {...formData};
        newFormData.firma                   = (firma[0] != undefined) ? firma[0] : '';
        newFormData.fotografia              = (fotografia[0] != undefined) ? fotografia[0] : '';
        newFormData.rutaCrt                 = (rutaCrt[0] != undefined) ? rutaCrt[0] : '';
        newFormData.rutaPem                 = (rutaPem[0] != undefined) ? rutaPem[0] : '';
        newFormData.imagenLicencia          = (imagenLicencia[0] != undefined) ? imagenLicencia[0] : '';
        newFormData.rutaCertificadoAsociado = (rutaCertificadoAsociado[0] != undefined) ? rutaCertificadoAsociado[0] : '';
        newFormData.certificadoConductor    = formDataFile.certificadoConductor;

        setLoader(true);
        instance.post(url, newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', documento:'', cargo: cargoLaboral, tipoIdentificacion: '', tipoPersona:tpRelacion, departamentoNacimiento:'', municipioNacimiento:'',
                                                                departamentoExpedicion:'', municipioExpedicion:'', primerNombre:'', segundoNombre: '', primerApellido: '', 
                                                                segundoApellido:'', fechaNacimiento:'',   direccion:'', correo:'', fechaExpedicion: '', telefonoFijo: '', numeroCelular:'', genero:'',firma:'', foto:'',
                                                                estado: '1', firmaDigital: '0', claveCertificado:'',  rutaCrt:'', rutaPem:'', tipo:tipo, formulario:frm,  fechaIngresoAsociado:'', fechaIngresoConductor:'', 
                                                                tipoConductor:'', agencia:'', firmaElectronica:'', rutaCrtOld:'', rutaPemOld:'', rutaDescargaCertificado:'', rutaCertificadoAsociado:'', rutaCertificadoAsociadoOld:''}) : null;
            (formData.tipo === 'I' && res.success) ? setFormDataFile({ fotografia: [], firma: [], rutaCrt:[], rutaPem: [], imagenLicencia:[], rutaCertificadoAsociado: [], certificadoConductor:[]}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/persona/listar/datos', {tipo:tipo, codigo:formData.codigo, frm:frm}).then(res=>{
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setTipoCategoriaLicencias(res.tpCateLicencias);
            setTipoCargoLaborales(res.tipoCargoLaborales);
            setTipoConductores(res.tipoConductores);
            setDepartamentos(res.departamentos);
            setMunicipios(res.municipios);
            setAgencias(res.agencias);

            if(tipo !== 'I'){
                let persona                        = res.persona;
                newFormData.documento              = persona.persdocumento;
                newFormData.cargo                  = persona.carlabid;
                newFormData.tipoIdentificacion     = persona.tipideid;
                newFormData.tipoPersona            = persona.tipperid;
                newFormData.departamentoNacimiento = persona.persdepaidnacimiento;
                newFormData.municipioNacimiento    = persona.persmuniidnacimiento;
                newFormData.departamentoExpedicion = persona.persdepaidexpedicion;
                newFormData.municipioExpedicion    = persona.persmuniidexpedicion;
                newFormData.primerNombre           = persona.persprimernombre;
                newFormData.segundoNombre          = (persona.perssegundonombre !== null)  ? persona.perssegundonombre : '';
                newFormData.primerApellido         = persona.persprimerapellido;
                newFormData.segundoApellido        = (persona.perssegundoapellido !== null) ? persona.perssegundoapellido : '';
                newFormData.fechaNacimiento        = persona.persfechanacimiento;
                newFormData.direccion              = persona.persdireccion;
                newFormData.correo                 = (persona.perscorreoelectronico !== null ) ? persona.perscorreoelectronico : '';
                newFormData.fechaExpedicion        = persona.persfechadexpedicion;
                newFormData.telefonoFijo           = (persona.persnumerotelefonofijo !== null) ? persona.persnumerotelefonofijo : '';
                newFormData.numeroCelular          = (persona.persnumerocelular !== null) ? persona.persnumerocelular : '';
                newFormData.genero                 = persona.persgenero;
                newFormData.rutaFirmaOld           = (persona.persrutafirma !== null) ? persona.persrutafirma : '';
                newFormData.rutaFotoOld            = (persona.persrutafoto !== null) ? persona.persrutafoto : '';
                newFormData.firmaElectronica       = persona.perstienefirmaelectronica;
                newFormData.firmaDigital           = persona.perstienefirmadigital;
                newFormData.rutaCrtOld             = (persona.persrutacrt !== null) ? persona.persrutacrt : '';
                newFormData.rutaPemOld             = (persona.persrutapem !== null) ? persona.persrutapem : '';
                newFormData.claveCertificado       = persona.persclavecertificado; 
                newFormData.rutaDescargaCrt        = (persona.rutaCrt !== null) ? persona.rutaCrt : '';
                newFormData.rutaDescargaPem        = (persona.rutaPem !== null) ? persona.rutaPem : '';
                newFormData.estado                 = persona.persactiva;

                if(frm == 'ASOCIADO'){
                    newFormData.fechaIngresoAsociado       = persona.asocfechaingreso;
                    newFormData.rutaDescargaCertificado    = (persona.asocrutacertificado !== null) ? persona.rutaCertificado : '';
                    newFormData.rutaCertificadoAsociadoOld = (persona.asocrutacertificado !== null) ? persona.asocrutacertificado : '';
                }

                if(frm == 'CONDUCTOR'){
                    newFormData.conductor                 = persona.condid;
                    newFormData.tipoConductor             = persona.tipconid;
                    newFormData.agencia                   = persona.agenid;
                    newFormData.fechaIngresoConductor     = persona.condfechaingreso;
                    let debeCrearRegistro                 = res.debeCrearRegistro;
                    newFormData.crearHistorial            = (debeCrearRegistro) ? 'S' : 'N';
                    newFormData.maxFechaVencimiento       = persona.maxFechaVencimiento;
                    newFormData.totalCertificadoConductor = persona.totalCertificadoConductor;

                   if(!debeCrearRegistro){
                    let conductorLicencia                   = res.conductorLicencia;
                        newFormData.licencia                = conductorLicencia.conlicid;
                        newFormData.tipoCategoria           = conductorLicencia.ticaliid;
                        newFormData.numeroLicencia          = conductorLicencia.conlicnumero;
                        newFormData.fechaExpedicionLicencia = conductorLicencia.conlicfechaexpedicion;
                        newFormData.fechaVencimiento        = conductorLicencia.conlicfechavencimiento;
                        let extencionArchivoLicencia        = (conductorLicencia.conlicextension !== undefined) ? conductorLicencia.conlicextension : null 
                        setTotalCertificadoSubido(persona.totalCertificadoConductor);
                        setExtencionArchivoLicencia(extencionArchivoLicencia);
                        setRutaArchivoEnfuscada((extencionArchivoLicencia !== null) ? conductorLicencia.conlicrutaarchivo : '');
                        setRutaLicenciaAdjunta((extencionArchivoLicencia !== null) ? conductorLicencia.rutaAdjuntoLicencia : '');
                   }
                    setHistorialLicencias(res.historialLicencias);
                }

                let munNacimiento   = [];
                let deptoNacimiento = persona.persdepaidnacimiento;
                res.municipios.forEach(function(muni){ 
                    if(muni.munidepaid === deptoNacimiento){
                        munNacimiento.push({
                            muniid: muni.muniid,
                            muninombre: muni.muninombre
                        });
                    }
                });
                setMunicipiosNacimiento(munNacimiento);

                //Municipios de expedicion
                let deptoExpedicion = persona.persdepaidexpedicion;
                let munExpedicion   = [];
                res.municipios.forEach(function(muni){ 
                    if(muni.munidepaid === deptoExpedicion){
                        munExpedicion.push({
                            muniid: muni.muniid,
                            muninombre: muni.muninombre
                        });
                    }
                });

                setMunicipiosExpedicion(munExpedicion);
                setConductorCertificados(res.conductorCertificados);
                setFormData(newFormData);
                setShowFotografia((persona.persrutafoto !== null) ? persona.fotografia : '');
                setFirmaPersona((persona.persrutafirma !== null) ? persona.firmaPersona : '');
            }
            setLoader(false);
        })
    }, []);

    const consultarMunicipioNacimiento = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
        let munNacimiento   = [];
        let deptoNacimiento = e.target.value;
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === deptoNacimiento){
                munNacimiento.push({
                    muniid: muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setMunicipiosNacimiento(munNacimiento);
    }

    const consultarMunicipioExpedicion = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
        let munExpedicion  = [];
        let deptoExpedicion = e.target.value;
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === deptoExpedicion){
                munExpedicion.push({
                    muniid: muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setMunicipiosExpedicion(munExpedicion); 
    }

    const cerrarModal = () =>{
        setModal({open : false});
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>

            <Grid container spacing={2}>

                <Grid item xl={3} md={3} sm={6} xs={12}>
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

                <Grid item xl={3} md={3} sm={6} xs={12}>
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
                    <TextValidator
                        name={'primerNombre'}
                        value={formData.primerNombre}
                        label={'Primer nombre'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 40}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'segundoNombre'}
                        value={formData.segundoNombre}
                        label={'Segundo nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 40}}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>
                
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'primerApellido'}
                        value={formData.primerApellido}
                        label={'Primer apellido'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 40}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'segundoApellido'}
                        value={formData.segundoApellido}
                        label={'Segundo apellido'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 40}}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'fechaNacimiento'}
                        value={formData.fechaNacimiento}
                        label={'Fecha nacimiento'}
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
                        name={'departamentoNacimiento'}
                        value={formData.departamentoNacimiento}
                        label={'Departamento de nacimiento'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarMunicipioNacimiento}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {departamentos.map(res=>{
                            return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'municipioNacimiento'}
                        value={formData.municipioNacimiento}
                        label={'Municipio de nacimiento'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {municipiosNacimiento.map(res=>{
                            return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'fechaExpedicion'}
                        value={formData.fechaExpedicion}
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
                    <SelectValidator
                        name={'departamentoExpedicion'}
                        value={formData.departamentoExpedicion}
                        label={'Departamento de expedición'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarMunicipioExpedicion}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {departamentos.map(res=>{
                            return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'municipioExpedicion'}
                        value={formData.municipioExpedicion}
                        label={'Municipio de expedición'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {municipiosExpedicion.map(res=>{
                            return <MenuItem value={res.muniid} key={res.muniid} >{res.muninombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'direccion'}
                        value={formData.direccion}
                        label={'Dirección'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'correo'}
                        value={formData.correo}
                        label={'Correo'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={['isEmail']}
                        errorMessages={['Correo no válido']}
                        type={"email"}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'telefonoFijo'}
                        value={formData.telefonoFijo}
                        label={'Teléfono fijo'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'numeroCelular'}
                        value={formData.numeroCelular}
                        label={'Número de celular'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'genero'}
                        value={formData.genero}
                        label={'Género'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"M"}>Másculino</MenuItem>
                        <MenuItem value={"F"}>Femenino</MenuItem>
                    </SelectValidator>
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <SelectValidator
                        name={'firmaElectronica'}
                        value={formData.firmaElectronica}
                        label={'¿Tiene firma electrónica?'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"}>Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                {(frm === 'PERSONA') ?
                    <Fragment>
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'cargo'}
                                value={formData.cargo}
                                label={'Cargo laboral'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoCargoLaborales.map(res=>{
                                    return <MenuItem value={res.carlabid} key={res.carlabid}>{res.carlabnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <SelectValidator
                                name={'firmaDigital'}
                                value={formData.firmaDigital}
                                label={'¿Tiene firma digital?'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                <MenuItem value={"1"}>Sí</MenuItem>
                                <MenuItem value={"0"}>No</MenuItem>
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <SelectValidator
                                name={'estado'}
                                value={formData.estado}
                                label={'Activo'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                <MenuItem value={"1"}>Sí</MenuItem>
                                <MenuItem value={"0"}>No</MenuItem>
                            </SelectValidator>
                        </Grid>
                    </Fragment>
                : null}

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Anexe la foto
                    </Box>
                </Grid>

                <Grid item md={5} xl={5} sm={12} xs={12}>
                    <Files
                        className='files-dropzone'
                        onChange={(file ) =>{onFilesChange(file, 'fotografia') }}
                        onError={onFilesError}
                        accepts={['.jpg', '.png', '.jpeg']} 
                        multiple
                        maxFiles={1}
                        maxFileSize={1000000}
                        clickable
                        dropActiveClassName={"files-dropzone-active"}
                    >
                    <ButtonFileImg title={"Adicionar fotografia"} />
                    </Files>
                </Grid>

                <Grid item md={4} xl={4} sm={12} xs={12}>
                    <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                        {formDataFile.fotografia.map((file, a) =>{
                            return <ContentFile file={file} name={file.name} remove={removeFIleFotografia} key={'ContentFile-' +a}/>
                        })}
                    </Box>
                </Grid>

                {(showFotografia !== '' && tipo === 'U') ?
                    <Grid item md={3} xl={3} sm={12} xs={12}>
                        <Box className='fotografia'>
                            <img src={showFotografia} ></img>
                        </Box>
                    </Grid>
                : null }

                {(frm === 'PERSONA') ?
                    <Fragment>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Anexe firma escaneada de la persona 
                            </Box>
                        </Grid>

                        <Grid item md={5} xl={5} sm={12} xs={12}>
                            <Files
                                className='files-dropzone'
                                onChange={(file ) =>{onFilesChange(file, 'firma') }}
                                onError={onFilesError}
                                accepts={['.jpg', '.png', '.jpeg']} 
                                multiple
                                maxFiles={1}
                                maxFileSize={1000000}
                                clickable
                                dropActiveClassName={"files-dropzone-active"}
                            >
                            <ButtonFileImg title={"Adicionar firma"} />
                            </Files>
                        </Grid>

                        <Grid item md={4} xl={4} sm={12} xs={12}>
                            <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                                {formDataFile.firma.map((file, a) =>{
                                    return <ContentFile file={file} name={file.name} remove={removeFIleFirma} key={'ContentFile-' +a}/>
                                })}
                            </Box>
                        </Grid>

                        {(showFirmaPersona !== '' && tipo === 'U') ?
                            <Grid item md={3} xl={3} sm={12} xs={12}>
                                <Box className='firmaPersona'>
                                    <img src={showFirmaPersona}></img>
                                </Box>
                            </Grid>
                        : null }

                    </Fragment>
                : null}           

                {(parseInt(formData.firmaDigital) === 1) ?  
                    <Fragment>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Anexe la clave de la firma y los certificado digitales de la persona
                            </Box>
                        </Grid>

                        <Grid item md={2} xl={2} sm={3} xs={12}>
                            <TextValidator
                                name={'claveCertificado'}
                                value={formData.claveCertificado}
                                label={'Contraseña'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 20}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                                type={'password'}
                            />
                        </Grid>

                        <Grid item md={3} xl={3} sm={6} xs={12}>
                            <Files
                                className='files-dropzone'
                                onChange={(file ) =>{onFilesChange(file, 'rutaCrt') }}
                                onError={onFilesError}
                                accepts={['.crt']} 
                                multiple
                                maxFiles={1}
                                maxFileSize={1000000}
                                clickable
                                dropActiveClassName={"files-dropzone-active"}
                            >
                            <ButtonFileImg title={"Adicionar certificado digital con extensión crt"} />
                            </Files>
                        </Grid>

                        <Grid item md={2} xl={2} sm={3} xs={12}>
                            <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                                {formDataFile.rutaCrt.map((file, a) =>{
                                    return <ContentFile file={file} name={file.name} remove={removeFIleRutaCrt} key={'ContentFile-' +a}/>
                                })}
                            </Box>
                        </Grid>

                        <Grid item md={3} xl={3} sm={6} xs={12}>
                            <Files
                                className='files-dropzone'
                                onChange={(file ) =>{onFilesChange(file, 'rutaPem') }}
                                onError={onFilesError}
                                accepts={['.pem']} 
                                multiple
                                maxFiles={1}
                                maxFileSize={1000000}
                                clickable
                                dropActiveClassName={"files-dropzone-active"}
                            >
                            <ButtonFileImg title={"Adicionar certificado digital con extensión pem"} />
                            </Files>
                        </Grid>

                        <Grid item md={2} xl={2} sm={6} xs={12}>
                            <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                                {formDataFile.rutaPem.map((file, a) =>{
                                    return <ContentFile file={file} name={file.name} remove={removeFIleRutaPem} key={'ContentFile-' +a}/>
                                })}
                            </Box>
                        </Grid>

                        {(tipo === 'U' && formData.claveCertificado !== null) ?
                            <Fragment>
                                <Grid item md={2} xl={2} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                        <label>Descargar certificado crt</label>
                                        <Link href={formData.rutaDescargaCrt} ><CloudDownloadIcon className={'iconoDownload'}/></Link>
                                    </Box>
                                </Grid>

                                <Grid item md={2} xl={2} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                        <label>Descargar certificado pem</label>
                                        <Link href={formData.rutaDescargaPem} ><CloudDownloadIcon className={'iconoDownload'}/></Link>
                                    </Box>
                                </Grid>
                            </Fragment>
                        : null}  

                    </Fragment>
                : null}

                {(frm === 'ASOCIADO') ?
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

                        {(tipo === 'U' && formData.rutaDescargaCertificado !== '') ?
                            <Grid item md={4} xl={4} sm={6} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Descargar certificado curso cooperativismo</label>
                                    <Link href={formData.rutaDescargaCertificado} >  <CloudDownloadIcon className={'iconoDownload'} /></Link>
                                </Box>
                            </Grid>
                        : null}

                        <Grid item md={3} xl={3} sm={6} xs={12}>
                            <Files
                                className='files-dropzone'
                                onChange={(file ) =>{onFilesChange(file, 'rutaCertificadoAsociado') }}
                                onError={onFilesError}
                                accepts={['.pdf']}
                                multiple
                                maxFiles={1}
                                maxFileSize={1000000}
                                clickable
                                dropActiveClassName={"files-dropzone-active"}
                            >
                            <ButtonFileImg title={"Adicionar certificado curso cooperativismo"} />
                            </Files>
                        </Grid>

                        <Grid item md={2} xl={2} sm={6} xs={12}>
                            <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                                {formDataFile.rutaCertificadoAsociado.map((file, a) =>{
                                    return <ContentFile file={file} name={file.name} remove={removeFIleRutaCertificadoAsociado} key={'ContentFile-' +a}/>
                                })}
                            </Box>
                        </Grid>

                    </Fragment>
                : null}

                {(frm === 'CONDUCTOR') ?
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
                                Información de la licencia de conducción
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

                        <Grid item md={5} xl={5} sm={12} xs={12}>
                            <Files
                                className='files-dropzone'
                                onChange={(file ) =>{onFilesChange(file, 'imagenLicencia') }}
                                onError={onFilesError}
                                accepts={['.jpg', '.png', '.jpeg', '.pdf', '.PDF']} 
                                multiple
                                maxFiles={1}
                                maxFileSize={1000000}
                                clickable
                                dropActiveClassName={"files-dropzone-active"}
                            >
                            <ButtonFileImg title={"Adicionar imagen de la licencia en formato jpg, png o pdf"} />
                            </Files>
                        </Grid>

                        <Grid item md={4} xl={4} sm={12} xs={12}>
                            <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                                {formDataFile.imagenLicencia.map((file, a) =>{
                                    return <ContentFile file={file} name={file.name} remove={removeFIleImagenLicencia} key={'ContentFile-' +a}/>
                                })}
                            </Box>
                        </Grid>

                        {(extencionArchivoLicencia !== null  && tipo === 'U') ?
                            <Grid item md={3} xl={3} sm={12} xs={12}>
                                <Box className='frmTexto'>
                                    <label>Visualizar adjunto de la licencia </label> 
                                </Box>
                                <Avatar style={{backgroundColor: '#43ab33', cursor: 'pointer'}}>
                                    <VisibilityIcon onClick={() => {setModal({open: true, extencion: extencionArchivoLicencia, ruta:rutaLicenciaAdjunta,  rutaEnfuscada: rutaArchivoEnfuscada })}} />
                                </Avatar>
                            </Grid>
                        : null }

                        {(formData.crearHistorial === 'S') ?
                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='mensajeAdvertencia'>
                                    <ErrorIcon />
                                    <p>La licencia de conducción registrada vencerá o vencío el {formData.maxFechaVencimiento}, por lo que el sistema le ha habilitado las casillas para que pueda ingresar una nueva licencia.</p>
                                </Box>
                            </Grid>
                        : null}

                        {(historialLicencias.length > 0 ) ?
                            <Fragment>
                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                    <Box className='divisionFormulario'>
                                        Historial de licencias de conducción
                                    </Box>
                                </Grid>
                                
                                <Grid item xl={12} md={12} sm={12} xs={12}>
                                    <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                        <Table className={'tableAdicional'} sx={{width: '90%', margin:'auto'}} sm={{width: '100%', margin:'auto'}} >
                                            <TableHead>
                                                <TableRow>
                                                    <TableCell>Tipo de categoria</TableCell>
                                                    <TableCell>Número de licencia</TableCell>
                                                    <TableCell>Fecha de expedición</TableCell>
                                                    <TableCell>Fecha de vencimiento</TableCell>
                                                    <TableCell>Adjunto</TableCell>
                                                </TableRow>
                                            </TableHead>
                                            <TableBody>
                                            { historialLicencias.map((historial, a) => {
                                                return(
                                                    <TableRow key={'rowD-' +a}>
                                                        <TableCell>
                                                            {historial['ticalinombre']}
                                                        </TableCell>

                                                        <TableCell>
                                                            {historial['conlicnumero']}
                                                        </TableCell>

                                                        <TableCell>
                                                            {historial['conlicfechaexpedicion']}
                                                        </TableCell>

                                                        <TableCell>
                                                            {historial['conlicfechavencimiento']}
                                                        </TableCell>

                                                        <TableCell>
                                                            {(historial['conlicextension'] !== null) ?
                                                                <Avatar style={{backgroundColor: '#43ab33', cursor: 'pointer'}}>
                                                                    <VisibilityIcon onClick={() => {setModal({open: true, extencion: historial['conlicextension'], ruta:historial['rutaAdjuntoLicencia'],  rutaEnfuscada:historial['conlicrutaarchivo']})}} />
                                                                </Avatar>
                                                            :null}
                                                        </TableCell>

                                                    </TableRow>
                                                    );
                                                })
                                            }
                                            </TableBody>
                                        </Table>
                                    </Box>
                                </Grid>
                            </Fragment>
                        :null}

                        {(totalCertificado > totalCertificadoSubido) ?
                            <Fragment>

                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                    <Box className='frmDivision'>
                                        Información de los certificados del conductor
                                    </Box>
                                </Grid>

                                <Grid item md={4} xl={4} sm={6} xs={12}>
                                    <Files
                                        className='files-dropzone'
                                        onChange={(file ) =>{onFilesChange(file, 'certificadoConductor') }}
                                        onError={onFilesError}
                                        accepts={['.pdf', '.PDF']} 
                                        multiple
                                        maxFiles={totalCertificado - totalCertificadoSubido}
                                        maxFileSize={1000000}
                                        clickable
                                        dropActiveClassName={"files-dropzone-active"}
                                    >
                                    <ButtonFileImg title={"Adicionar certificados en formato PDF"} />
                                    </Files>
                                </Grid>

                                <Grid item md={4} xl={4} sm={6} xs={12}>
                                    <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                                        {formDataFile.certificadoConductor.map((file, a) =>{
                                            return <ContentFile file={file} name={file.name} remove={removeFIleCertificadoConductor} key={'ContentFile-' +a}/>
                                        })}
                                    </Box>
                                </Grid>

                                <Grid item xl={4} md={4} sm={12} xs={12}>
                                    <Box className={'msgAlert'}>
                                        <Avatar className={'avatar'}> <WarningIcon /></Avatar> 
                                        <p>Nota: Recuerde que puede subir como máximo ({totalCertificado}) archivos, actualmente ha subido ({totalCertificadoSubido}) archivos.</p>
                                    </Box>
                                </Grid>

                           </Fragment>
                        : null }

                        {(formData.totalCertificadoConductor > 0) ? 
                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <CertificadoConductor data={conductorCertificados} eliminar= {true} cantidadAdjunto={cantidadAdjunto} />
                            </Grid>
                         : null }

                    </Fragment>
                : null}

            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                    </Button>
                </Stack>
            </Grid>

            <ModalDefaultAuto
                title={'Visualizar adjunto'}
                content={<ShowAnexo extencion={modal.extencion} ruta={modal.ruta} rutaEnfuscada={modal.rutaEnfuscada} cerrarModal={cerrarModal} />}
                close={() =>{setModal({open : false})}}
                tam = {'smallFlot'}
                abrir ={modal.open}
            />

        </ValidatorForm>
    );
}
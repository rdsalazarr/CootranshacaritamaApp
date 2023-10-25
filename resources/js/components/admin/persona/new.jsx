import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Link, Table, TableHead, TableBody, TableRow, TableCell } from '@mui/material';
import CloudDownloadIcon from '@mui/icons-material/CloudDownload';
import {ButtonFileImg, ContentFile} from "../../layout/files";
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import AddIcon from '@mui/icons-material/Add';
import instance from '../../layout/instance';
import Files from "react-files";

export default function New({data, tipo, frm, url, tpRelacion}){

    let cargoLaboral = (frm === 'ASOCIADO') ? '2' : ((frm === 'CONDUCTOR') ? '3' : '')

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo:data.persid,  tipo:tipo, formulario:frm
                                    } : {codigo:'000', documento:'', cargo: cargoLaboral, tipoIdentificacion: '', tipoRelacionLaboral:tpRelacion, departamentoNacimiento:'', municipioNacimiento:'',
                                        departamentoExpedicion:'', municipioExpedicion:'', primerNombre:'', segundoNombre: '', primerApellido: '', 
                                        segundoApellido:'', fechaNacimiento:'',   direccion:'', correo:'', fechaExpedicion: '', telefonoFijo: '', numeroCelular:'', genero:'',firma:'', foto:'',
                                        estado: '1', firmaDigital: '0', claveCertificado:'',  rutaCrt:'', rutaPem:'', tipo:tipo, formulario:frm, fechaIngresoAsociado:'', fechaIngresoConductor:'',
                                        tipoConductor:'', agencia:''
                                }); 

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [showFotografia, setShowFotografia] = useState('');
    const [showFirmaPersona, setFirmaPersona] = useState('');
    const [tipoCargoLaborales, setTipoCargoLaborales] = useState([]);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);  
    const [departamentos, setDepartamentos] = useState([]);
    const [municipios, setMunicipios] = useState([]);
    const [municipiosNacimiento, setMunicipiosNacimiento] = useState([]);
    const [municipiosExpedicion, setMunicipiosExpedicion] = useState([]);
    const [formDataFile, setFormDataFile] = useState({ fotografia: [], firma: [], rutaCrt:[], rutaPem: []});
    const [tipoCategoriaLicencias, settipoCategoriaLicencias] = useState([]);
    const [tipoConductores, setTipoConductores] = useState([]);
    const [agencias, setAgencias] = useState([]); 
    const [formDataAdicionar, setFormDataAdicionar] = useState({tipoCategoria:'', numeroLicencia:'', fechaExpedicion:'', fechaVencimiento:'' });
    const [licenciasConduccion, setLicenciasConduccion] = useState([]);
    
    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    }

    const handleChangeAdicionar = (e) =>{
        setFormDataAdicionar(prev => ({...prev, [e.target.name]: e.target.value}))
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
        let fotografia = formDataFile.fotografia;
        let firma      = formDataFile.firma;
        let rutaCrt    = formDataFile.rutaCrt;
        let rutaPem    = formDataFile.rutaPem;

        if(tipo === '' && formData.firmaDigital.toString() === '1' && rutaCrt.length < 1){
            showSimpleSnackbar("Debe subir el certificado digital en formato crt", 'error');
            return;
        }

        if(tipo === '' && formData.firmaDigital.toString() === '1' && rutaPem.length < 1){
            showSimpleSnackbar("Debe subir el certificado digital en formato pem", 'error');
            return;
        }

        let dataFile = new FormData();
        Object.keys(formData).forEach(function(key) {
           dataFile.append(key, formData[key])
        })

        dataFile.append('firma', (firma[0] != undefined) ? firma[0] : '');
        dataFile.append('fotografia', (fotografia[0] != undefined) ? fotografia[0] : '');
        dataFile.append('rutaCrt', (rutaCrt[0] != undefined) ? rutaCrt[0] : '');
        dataFile.append('rutaPem', (rutaPem[0] != undefined) ? rutaPem[0] : '');
        setLoader(true);
        instance.post(url, dataFile).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', documento:'', cargo: cargoLaboral, tipoIdentificacion: '', tipoRelacionLaboral:tpRelacion, departamentoNacimiento:'', municipioNacimiento:'',
                                                                departamentoExpedicion:'', municipioExpedicion:'', primerNombre:'', segundoNombre: '', primerApellido: '', 
                                                                segundoApellido:'', fechaNacimiento:'',   direccion:'', correo:'', fechaExpedicion: '', telefonoFijo: '', numeroCelular:'', genero:'',firma:'', foto:'',
                                                                estado: '1', firmaDigital: '0', claveCertificado:'',  rutaCrt:'', rutaPem:'', tipo:tipo, formulario:frm,  fechaIngresoAsociado:'', fechaIngresoConductor:'', 
                                                                tipoConductor:'', agencia:''}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/persona/listar/datos', {tipo:tipo, codigo:formData.codigo, frm:frm}).then(res=>{
            setTipoCargoLaborales(res.tipoCargoLaborales);
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setDepartamentos(res.departamentos);
            setMunicipios(res.municipios);
            setTipoConductores(res.tipoConductores);
            setAgencias(res.agencias);
            settipoCategoriaLicencias(res.tpCateLicencias);            

            if(tipo !== 'I'){     
                let persona                        = res.persona;
                newFormData.documento              = persona.persdocumento;
                newFormData.cargo                  = persona.carlabid;
                newFormData.tipoIdentificacion     = persona.tipideid;
                newFormData.tipoRelacionLaboral    = persona.tirelaid;
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
                newFormData.firmaDigital           = persona.perstienefirmadigital;
                newFormData.rutaCrtOld             = (persona.persrutacrt !== null) ? persona.persrutacrt : '';
                newFormData.rutaPemOld             = (persona.persrutapem !== null) ? persona.persrutapem : '';
                newFormData.claveCertificado       = persona.persclavecertificado; 
                newFormData.rutaDescargaCrt        = (persona.rutaCrt !== null) ? persona.rutaCrt : '';
                newFormData.rutaDescargaPem        = (persona.rutaPem !== null) ? persona.rutaPem : '';
                newFormData.estado                 = persona.persactiva;
                if(frm == 'ASOCIADO'){
                    newFormData.fechaIngresoAsociado  = (persona.fechaIngresoAsocido !== null) ? persona.fechaIngresoAsocido : '' ;
                }else{
                    newFormData.fechaIngresoConductor = (persona.fechaIngresoConductor !== null) ? persona.fechaIngresoConductor : '' ;
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

    //tipoCategoria:'', numeroLicencia:'', fechaExpedicion:'', fechaVencimiento:''
    const adicionarFilaLicencia = () =>{

        if(formDataAdicionar.tipoCategoria === ''){
            showSimpleSnackbar('Debe seleccionar un tipo de categoría', 'error');
            return
        }

        if(formDataAdicionar.numeroLicencia === ''){
            showSimpleSnackbar('Debe ingresar un número de licencia', 'error');
            return
        }

        if(formDataAdicionar.fechaExpedicion === ''){
            showSimpleSnackbar('Debe ingresar la fecha de expedición de licencia', 'error');
            return
        }

        if(formDataAdicionar.fechaExpedicion === ''){
            showSimpleSnackbar('Debe ingresar la fecha de vencimiento de licencia', 'error');
            return
        }

        if(licenciasConduccion.some(pers => pers.numeroLicencia == formDataAdicionar.numeroLicencia)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newLicenciasConduccion = [...licenciasConduccion]; 
        newLicenciasConduccion.push({identificador:'', tipoCategoria:formDataAdicionar.tipoCategoria, numeroLicencia: formDataAdicionar.numeroLicencia, 
                                        fechaExpedicion: formDataAdicionar.fechaExpedicion, fechaVencimiento: formDataAdicionar.fechaVencimiento,  estado: 'I'});
        setFormDataAdicionar({tipoCategoria:'', numeroLicencia:'', fechaExpedicion:'', fechaVencimiento:''});
        setLicenciasConduccion(newLicenciasConduccion);
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
               
                        <Grid item xl={3} md={3} sm={6} xs={12}>
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

                        <Grid item xl={3} md={3} sm={6} xs={12}>
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
                                Anexar licencia del conducción
                            </Box>
                        </Grid>
                        
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoCategoria'}
                                value={formDataAdicionar.tipoCategoria}
                                label={'Tipo categoría'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChangeAdicionar} 
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
                                value={formDataAdicionar.numeroLicencia}
                                label={'Número de licencia'}
                                className={'inputGeneral'}
                                variant={"standard"}
                                inputProps={{autoComplete: 'off', maxLength: 30}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChangeAdicionar}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'fechaExpedicion'}
                                value={formDataAdicionar.fechaExpedicion }
                                label={'Fecha expedición'}
                                className={'inputGeneral'}
                                variant={"standard"}
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChangeAdicionar}
                                type={"date"}
                                InputLabelProps={{
                                    shrink: true,
                                }}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'fechaVencimiento'}
                                value={formDataAdicionar.fechaVencimiento }
                                label={'Fecha vencimiento'}
                                className={'inputGeneral'}
                                variant={"standard"}
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChangeAdicionar}
                                type={"date"}
                                InputLabelProps={{
                                    shrink: true,
                                }}
                            />
                        </Grid>

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <Button type={"button"} className={'modalBtn'} 
                                startIcon={<AddIcon />} onClick={() => {adicionarFilaLicencia()}}> {"Agregar"}
                            </Button>
                        </Grid>


                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='divisionFormulario'>
                                Licencia del conducción adicionada
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Table className={'tableAdicional'} xl={{width: '90%', margin:'auto'}} md={{width: '90%', margin:'auto'}} sx={{width: '100%', margin:'auto'}} sm={{maxHeight: '100%', margin:'auto'}} >
                                <TableHead>
                                    <TableRow>
                                        <TableCell>Tipo de categoria</TableCell>
                                        <TableCell>Número de licencia</TableCell>
                                        <TableCell>Fecha de expedición</TableCell>
                                        <TableCell>Fecha de vencimiento</TableCell>
                                        <TableCell>Imagen</TableCell>
                                        <TableCell style={{width: '5%'}} className='cellCenter'>Acción </TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>
                                </TableBody>
                             </Table>

                             licenciasConduccion
                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={12}>                            
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
                            return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
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
                                    return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
                                })}
                            </Box>
                        </Grid>
                    </Fragment>
                : null}

                {(showFirmaPersona !== '' && tipo === 'U') ?
                    <Grid item md={3} xl={3} sm={12} xs={12}>
                        <Box className='firmaPersona'>
                            <img src={showFirmaPersona}></img>
                        </Box>
                    </Grid>
                : null }

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
                                    return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
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
                                    return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
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

            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
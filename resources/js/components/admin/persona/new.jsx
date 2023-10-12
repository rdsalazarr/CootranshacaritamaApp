import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Link } from '@mui/material';
import CloudDownloadIcon from '@mui/icons-material/CloudDownload';
import {ButtonFileImg, ContentFile} from "../../layout/files";
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';
import Files from "react-files";

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo:data.persid,  tipo:tipo 
                                    } : {codigo:'000', documento:'', cargo: '', tipoIdentificacion: '', tipoRelacionLaboral:'', departamentoNacimiento:'', municipioNacimiento:'',
                                        departamentoExpedicion:'', municipioExpedicion:'', primerNombre:'', segundoNombre: '', primerApellido: '', 
                                        segundoApellido:'', fechaNacimiento:'',   direccion:'', correo:'', fechaExpedicion: '', telefonoFijo: '', numeroCelular:'', genero:'',firma:'', foto:'',
                                        estado: '1', firmaDigital: '0', claveCertificado:'',  rutaCrt:'', rutaPem:'', tipo:tipo
                                }); 

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [showFotografia, setShowFotografia] = useState('');
    const [showFirmaPersona, setFirmaPersona] = useState('');
    const [tipoCargoLaborales, setTipoCargoLaborales] = useState([]);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [tipoRelacionLaborales, setTipoRelacionLaborales] = useState([]);
    const [departamentos, setDepartamentos] = useState([]);
    const [municipios, setMunicipios] = useState([]);
    const [municipiosNacimiento, setMunicipiosNacimiento] = useState([]);
    const [municipiosExpedicion, setMunicipiosExpedicion] = useState([]);
    const [formDataFile, setFormDataFile] = useState({ fotografia: [], firma: [], rutaCrt:[], rutaPem: []});

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleInputChange = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    };

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

        console.log(fotografia[0]);
        console.log(rutaCrt[0]);
        console.log(rutaPem[0]);

        dataFile.append('firma', (firma[0] != undefined) ? firma[0] : '');
        dataFile.append('fotografia', (fotografia[0] != undefined) ? fotografia[0] : '');
        dataFile.append('rutaCrt', (rutaCrt[0] != undefined) ? rutaCrt[0] : '');
        dataFile.append('rutaPem', (rutaPem[0] != undefined) ? rutaPem[0] : '');

        setLoader(true);
        instance.post('/admin/persona/salve', dataFile).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', documento:'', cargo: '', tipoIdentificacion: '', tipoRelacionLaboral:'', departamentoNacimiento:'', municipioNacimiento:'',
                                                                departamentoExpedicion:'', municipioExpedicion:'', primerNombre:'', segundoNombre: '', primerApellido: '', 
                                                                segundoApellido:'', fechaNacimiento:'',   direccion:'', correo:'', fechaExpedicion: '', telefonoFijo: '', numeroCelular:'', genero:'',firma:'', foto:'',
                                                                estado: '1', firmaDigital: '0', claveCertificado:'',  rutaCrt:'', rutaPem:'', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/persona/listar/datos', {tipo:tipo, codigo:formData.codigo}).then(res=>{
            setTipoCargoLaborales(res.tipoCargoLaborales);
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setTipoRelacionLaborales(res.tipoRelacionLaborales);
            setDepartamentos(res.departamentos);
            setMunicipios(res.municipios);

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

                let munNacimiento   = [];
                let deptoNacimiento = data.persdepaidnacimiento;
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
                let deptoExpedicion = data.persdepaidexpedicion;
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
                        onChange={handleInputChange}
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
                        onChange={handleInputChange}
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
                        onChange={handleInputChange}
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
                        onChange={handleInputChange}
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
                        <MenuItem value={"F"}>FEmenino</MenuItem>
                    </SelectValidator>
                </Grid>

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
                        name={'tipoRelacionLaboral'}
                        value={formData.tipoRelacionLaboral}
                        label={'Tipo relación laboral'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoRelacionLaborales.map(res=>{
                            return <MenuItem value={res.tirelaid} key={res.tirelaid} >{res.tirelanombre}</MenuItem>
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

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Anexe foto y firma escaneada de la persona 
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

                        {(tipo === 'U') ?
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
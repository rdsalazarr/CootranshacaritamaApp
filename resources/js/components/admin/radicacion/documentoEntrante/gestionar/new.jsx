import React, {useState, useEffect, Fragment} from 'react';
import {Card, Button, Grid, MenuItem, Box, Stack, FormGroup, FormControlLabel, Checkbox, Avatar } from '@mui/material';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {ButtonFileImg, ButtonFilePdf, ContentFile} from "../../../../layout/files";
import showSimpleSnackbar from '../../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../../layout/modal';
import WarningIcon from '@mui/icons-material/Warning';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import PdfStickers from '../pdfStickers';
import Files from "react-files";
import Anexos from '../anexos';

export default function New({data, tipo}){

    const [formData, setFormData] = useState({ codigo: (tipo === 'U') ? data.id : '000',
                                                tipoIdentificacion: '',    numeroIdentificacion: '',    primerNombre: '',      segundoNombre: '',     primerApellido: '', 
                                                segundoApellido: '',       direccionFisica: '',         correoElectronico: '', numeroContacto: '',    codigoDocumental: '',
                                                fechaLlegadaDocumento: '', fechaDocumento: '',          dependencia: '',       departamento: '',      municipio: '',
                                                asuntoRadicado: '',        personaEntregaDocumento: '', tieneAnexos: '',       descripcionAnexos: '',  tieneCopia: '',
                                                tipoMedio: '',             observacionGeneral: '',      personaId: '',         tipo:tipo,             archivos:[]
                                            });

    const [formDataFilePdf, setFormDataFilePdf] = useState({archivos : []}); 
    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false);
    const [esEmpresa, setEsEmpresa] = useState(false);
    const [tieneCopia, setTieneCopia] = useState(false);
    const [habilitarAnexos, setHabilitarAnexos] = useState(false);    
    const [tipoMedios, setTipoMedios] = useState([]);
    const [dependencias, setDependencias] = useState([]);

    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [departamentos, setDepartamentos] = useState([]);
    const [municipios, setMunicipios] = useState([]);
    const [deptoMunicipios, setDeptoMunicipios] = useState([]);
    const [totalAdjunto, setTotalAdjunto] = useState(import.meta.env.VITE_TOTAL_FILES_RADICADO);
    const [checkedDependencias, setCheckedDependencias] = useState([]);
    const [totalAdjuntoSubido , setTotalAdjuntoSubido] = useState(0);
    const [anexosRadicado, setAnexosRadicado] = useState([]);
    const [idRadicado , setIdRadicado] = useState(0);    
    const [abrirModal, setAbrirModal] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}));
    }

    const handleInputChange = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}));
    }

    const handleCheckboxChange = (event, dep) => {
        if (event.target.checked) {
          setCheckedDependencias([...checkedDependencias, dep]);
        } else {
          setCheckedDependencias(checkedDependencias.filter(item => item !== dep));
        }
    };

    const cantidadAdjunto = () =>{
        let totalAdjSubido = parseInt(totalAdjuntoSubido) - 1 ;
        setTotalAdjuntoSubido(totalAdjSubido);
    }

    const onFilesChangePdf = (files, nombre) =>  {
        setFormDataFilePdf(prev => ({...prev, [nombre]: files}));
    }

    const onFilesChange = (files, nombre) =>  {
        setFormData(prev => ({...prev, [nombre]: files}));
    }

    const removeFIle = (nombre)=>{
        setFormData(prev => ({...prev, archivos: prev.archivos.filter(item => item.name !== nombre)}));
    }

    const onFilesError = (error, file) => {
        let msj = (error.code === 2) ? 'El archivo "'+ file.name + '" es demasiado grande y no se puede subir' : error.message  
        ReactDOM.unmountComponentAtNode(document.getElementById("snake"));
        ReactDOM.render(<SimpleSnackbar msg={msj} icon={'error'} />,
        document.getElementById("snake"));
    }

    const handleSubmit = () =>{

        if(tipo === 'I' && formDataFilePdf.archivos.length === 0 ){
            showSimpleSnackbar("Debe adjuntar el documento PDF que se desea radicar", 'error');
            return;
        }

        if(formData.tieneCopia === '1' && checkedDependencias.length === 0){
            showSimpleSnackbar("Debe marcar cómo mínimo una dependencia par enviar la copia", 'error');
            return;
        }

        let newFormData               = {...formData};
        newFormData.pdfRadicar        = formDataFilePdf.archivos;
        newFormData.copiasDependencia = checkedDependencias;
        setLoader(true);
        instance.post('/admin/radicacion/documento/entrante/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            if(formData.tipo === 'I' && res.success){
                setFormData({codigo: (tipo === 'U') ? data.id : '000',
                            tipoIdentificacion: '',    numeroIdentificacion: '',    primerNombre: '',      segundoNombre: '',     primerApellido: '', 
                            segundoApellido: '',       direccionFisica: '',         correoElectronico: '', numeroContacto: '',    codigoDocumental: '',
                            fechaLlegadaDocumento: '', fechaDocumento: '',          dependencia: '',       departamento: '',      municipio: '',
                            asuntoRadicado: '',        personaEntregaDocumento: '', tieneAnexos: '',       descripcionAnexos: '',  tieneCopia: '',
                            tipoMedio: '',             observacionGeneral: '',      personaId: '',         tipo:tipo,             archivos:[]})

                setIdRadicado(res.idRadicado);
                setFormDataFilePdf({archivos : []});
                setAbrirModal(true);
            }

            setLoader(false);
        })
    }

    const inicio = () =>{ 
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/radicacion/documento/entrante/datos', {codigo: formData.codigo, tipo: formData.tipo}).then(res=>{
            newFormData.fechaLlegadaDocumento = res.fechaActual;

            if(tipo === 'U'){
                let radicado                        = res.data;
                newFormData.personaId               = radicado.peradoid;
                newFormData.tipoIdentificacion      = radicado.tipideid;
                newFormData.numeroIdentificacion    = radicado.peradodocumento;
                newFormData.primerNombre            = radicado.peradoprimernombre; 
                newFormData.segundoNombre           = (radicado.peradosegundonombre !== null) ? radicado.peradosegundonombre : '';
                newFormData.primerApellido          = (radicado.peradoprimerapellido !== null) ? radicado.peradoprimerapellido : '';
                newFormData.segundoApellido         = (radicado.peradosegundoapellido !== null) ? radicado.peradosegundoapellido : '';
                newFormData.direccionFisica         = (radicado.peradodireccion !== null) ? radicado.peradodireccion : '';
                newFormData.correoElectronico       = (radicado.peradocorreo !== null) ? radicado.peradocorreo : '';
                newFormData.numeroContacto          = (radicado.peradotelefono !== null) ? radicado.peradotelefono : '';
                newFormData.codigoDocumental        = (radicado.peradocodigodocumental !== null) ? radicado.peradocodigodocumental : '';
                newFormData.fechaLlegadaDocumento   = radicado.radoenfechallegada;
                newFormData.fechaDocumento          = radicado.radoenfechadocumento;
                newFormData.dependencia             = radicado.depeid;
                newFormData.departamento            = radicado.depaid;
                newFormData.municipio               = radicado.muniid;
                newFormData.asuntoRadicado          = radicado.radoenasunto;
                newFormData.personaEntregaDocumento = radicado.radoenpersonaentregadocumento;
                newFormData.tieneAnexos             = radicado.radoentieneanexo;
                newFormData.descripcionAnexos       = (radicado.radoendescripcionanexo !== null) ? radicado.radoendescripcionanexo : '';
                newFormData.tieneCopia              = radicado.radoentienecopia;
                newFormData.tipoMedio               = radicado.tipmedid;
                newFormData.observacionGeneral      = (radicado.radoenobservacion !== null) ? radicado.radoenobservacion : '';

                let newMunicipios = [];
                let deptoId       = radicado.depaid;
                res.municipios.forEach(function(muni){
                    if(muni.munidepaid === deptoId){
                        newMunicipios.push({
                            muniid: muni.muniid,
                            muninombre: muni.muninombre
                        });
                    }
                });
                
                setDeptoMunicipios(newMunicipios);
                setTotalAdjuntoSubido(radicado.totalAnexos);
                setAnexosRadicado(res.anexosRadicados);
                setHabilitarAnexos((parseInt(radicado.totalAnexos) > 0) ? true : false);
            }

            setTipoMedios(res.tipoMedios);
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setDepartamentos(res.departamentos);
            setMunicipios(res.municipios)
            setDependencias(res.dependencias);
            setFormData(newFormData);
            setLoader(false);
        })
    }

    const verificarSiTieneCopia = (e) =>{
        let newFormData        = {...formData}
        let tieneCopia         = (e.target.name === 'tieneCopia' ) ? e.target.value : formData.tieneCopia ;
        newFormData.tieneCopia = tieneCopia;
        setTieneCopia((e.target.value === '1') ? true : false);
        (e.target.value === '0') ? setCheckedDependencias([]): null;
        setFormData(newFormData);
    }
    
    const verificarSiTieneAnexos = (e) =>{
        let newFormData        = {...formData}
        let tieneAnexos        = (e.target.name === 'tieneAnexos' ) ? e.target.value : formData.tieneAnexos ;
        newFormData.tieneAnexos = tieneAnexos;
        setHabilitarAnexos((e.target.value === '1') ? true : false);
        setFormData(newFormData);
    }

    const consultarMunicipio = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
        let newMunicipios = [];
        let deptoId       = e.target.value;
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === deptoId){
                newMunicipios.push({
                    muniid: muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setDeptoMunicipios(newMunicipios);
    }

    const consultarPersona = (e) =>{
        let newFormData                  = {...formData}
        let tpIdentificacion             = (e.target.name === 'tipoIdentificacion' ) ? e.target.value : formData.tipoIdentificacion ;
        let numeroIdentificacion         = (e.target.name === 'numeroIdentificacion' ) ? e.target.value : formData.numeroIdentificacion ;
        newFormData.tipoIdentificacion   = tpIdentificacion;
        newFormData.numeroIdentificacion = numeroIdentificacion;
       if (tpIdentificacion !=='' && formData.numeroIdentificacion !==''){
            setLoader(true);
            instance.post('/admin/radicacion/documento/entrante/consultar/persona', {tipoIdentificacion:tpIdentificacion, numeroIdentificacion: formData.numeroIdentificacion}).then(res=>{
                if(res.success){
                    newFormData.personaId       = res.data.peradoid;
                    newFormData.primerNombre    = res.data.peradoprimernombre;
                    newFormData.segundoNombre   = (res.data.peradosegundonombre !== undefined) ? res.data.peradosegundonombre : '';
                    newFormData.primerApellido  = (res.data.peradoprimerapellido !== undefined) ? res.data.peradoprimerapellido : '';
                    newFormData.segundoApellido = (res.data.peradosegundoapellido !== undefined) ? res.data.peradosegundoapellido : '';
                    newFormData.direccionFisica = (res.data.peradodireccion !== undefined) ? res.data.peradodireccion : '';
                    newFormData.correoElectronico       = (res.data.peradocorreo !== undefined) ? res.data.peradocorreo : '';
                    newFormData.codigoDocumental        = (res.data.peradocodigodocumental !== undefined) ? res.data.peradocodigodocumental : '';
                    newFormData.numeroContacto          = (res.data.peradotelefono !== undefined) ? res.data.peradotelefono : '';
                    newFormData.personaEntregaDocumento = (res.data.radoenpersonaentregadocumento !== undefined) ? res.data.radoenpersonaentregadocumento : '';
                }else{
                    newFormData.personaId               = '';
                    newFormData.primerNombre            = '';
                    newFormData.segundoNombre           = '';
                    newFormData.primerApellido          = '';
                    newFormData.segundoApellido         = '';
                    newFormData.direccionFisica         = '';
                    newFormData.correoElectronico       = '';
                    newFormData.codigoDocumental        = '';
                    newFormData.numeroContacto          = '';
                    newFormData.personaEntregaDocumento = '';
                }
                setLoader(false);
            })
        }
        setEsEmpresa((e.target.value === '5') ? true : false);
        setFormData(newFormData);
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return ( 
        <Box>           
            <ValidatorForm onSubmit={handleSubmit} >
                <Card style={{padding: '6px', marginTop: '1em' }}>
                    <Grid container spacing={2} >
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='divisionFormulario'>
                                Información del remitente
                            </Box> 
                        </Grid>
           
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoIdentificacion'}
                                value={formData.tipoIdentificacion}
                                label={'Tipo de identificación'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={consultarPersona} 
                                tabIndex="1"
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoIdentificaciones.map(res=>{
                                    return <MenuItem value={res.tipideid} key={res.tipideid}>{res.tipidenombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'numeroIdentificacion'}
                                value={formData.numeroIdentificacion}
                                label={(esEmpresa)? 'NIT' : 'Número de identificación'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 15}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                                onBlur={consultarPersona}
                                tabIndex="2"
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'primerNombre'}
                                value={formData.primerNombre}
                                label={(esEmpresa)? 'Razón social' : 'Primer nombre'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 70}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleInputChange}
                                tabIndex="3"
                            />
                        </Grid>

                        {(!esEmpresa)?
                            <Fragment>
                                <Grid item xl={3} md={3} sm={6} xs={12}>
                                    <TextValidator
                                        name={'segundoNombre'}
                                        value={formData.segundoNombre}
                                        label={'Segundo nombre'}
                                        className={'inputGeneral'} 
                                        variant={"standard"} 
                                        inputProps={{ maxLength: 40}}
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
                                        inputProps={{ maxLength: 40}}
                                        onChange={handleInputChange}
                                    />
                                </Grid>
                            </Fragment>
                        : null}

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'direccionFisica'}
                                value={formData.direccionFisica}
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
                                name={'correoElectronico'}
                                value={formData.correoElectronico}
                                label={'Correo electrónico'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 80}}
                                validators={['required', 'isEmail']}
                                errorMessages={['Campo requerido', 'Correo no válido']}
                                type={"email"}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'numeroContacto'}
                                value={formData.numeroContacto}
                                label={'Número de contacto'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{ maxLength: 20}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator 
                                name={'codigoDocumental'}
                                value={formData.codigoDocumental}
                                label={'Código documental de la empresa'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{ maxLength: 20}}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}> 
                            <Box className='divisionFormulario'>
                                Información del radicado de la solicitud 
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'fechaLlegadaDocumento'}
                                value={formData.fechaLlegadaDocumento}
                                label={'Fecha de llegada'}
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
                                name={'fechaDocumento'}
                                value={formData.fechaDocumento}
                                label={'Fecha del documento'}
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
                                name={'departamento'}
                                value={formData.departamento}
                                label={'Departamento'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={consultarMunicipio} 
                            >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {departamentos.map(res=>{
                                return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
                            })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'municipio'}
                                value={formData.municipio}
                                label={'Municipio'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange} 
                            >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {deptoMunicipios.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}>{res.muninombre}</MenuItem>
                            })}
                            </SelectValidator>
                        </Grid> 

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'dependencia'}
                                value={formData.dependencia}
                                label={'Dependencia destino'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange} 
                            >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {dependencias.map(res=>{
                                return <MenuItem value={res.depeid} key={res.depeid}>{res.depenombre}</MenuItem>
                            })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator 
                                name={'personaEntregaDocumento'}
                                value={formData.personaEntregaDocumento}
                                label={'Persona que entrega el documento'}
                                className={'inputGeneral'} 
                                variant={"standard"}
                                inputProps={{autoComplete: 'off', maxLength: 100}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
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
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange}
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoMedios.map(res=>{
                                    return <MenuItem value={res.tipmedid} key={res.tipmedid}>{res.tipmednombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <SelectValidator
                                name={'tieneCopia'}
                                value={formData.tieneCopia}
                                label={'¿Tiene copia?'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={verificarSiTieneCopia}
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                <MenuItem value={"1"}>Sí</MenuItem>
                                <MenuItem value={"0"}>No</MenuItem>
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <SelectValidator
                                name={'tieneAnexos'}
                                value={formData.tieneAnexos}
                                label={'¿Tiene anexos?'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={verificarSiTieneAnexos}
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                <MenuItem value={"1"}>Sí</MenuItem>
                                <MenuItem value={"0"}>No</MenuItem>
                            </SelectValidator>
                        </Grid>

                        {parseInt(formData.tieneAnexos) === 1 ?
                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator 
                                    name={'descripcionAnexos'}
                                    value={formData.descripcionAnexos}
                                    label={'Descripción de los anexos'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 300}}
                                    onChange={handleChange}
                                />
                            </Grid>
                        : null }

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'observacionGeneral'}
                                value={formData.observacionGeneral}
                                label={'Observación general'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 300}}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <TextValidator
                                multiline
                                maxRows={10}
                                name={'asuntoRadicado'}
                                value={formData.asuntoRadicado}
                                label={'Asunto del radicado'}
                                className={'inputGeneral'} 
                                variant={"standard"}
                                inputProps={{autoComplete: 'off', maxLength: 500}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={6} md={6} sm={6} xs={12}>
                            <Files
                                className='files-dropzone'
                                onChange={(file ) =>{onFilesChangePdf(file, 'archivos') }}
                                onError={onFilesError}
                                accepts={['.pdf', '.PDF']}
                                maxFiles={1}
                                maxFileSize={2000000}
                                clickable
                                dropActiveClassName={"files-dropzone-active"}
                                >
                                <ButtonFilePdf title={"Documento que se debe radicar (Solo formato PDF)"} />
                            </Files>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            {formDataFilePdf.archivos.map((file, a) =>{
                                return <ContentFile file={file} name={file.name} remove={removeFIle} mostrarEnlace={false} key={'ContentFile-' +a}/>
                            })}
                        </Grid>

                    </Grid>

                    {tieneCopia ?
                        <Box style={{ transition: 'all .2s ease-in-out'}}>
                            <Grid container spacing = {2} >
                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                    <Box className='divisionFormulario'>
                                        Marcar las copia de las dependencia a las que se le desea enviar el documento
                                    </Box>
                                </Grid>
                            </Grid>

                            <Box style={{maxHeight: '15em', overflow:'auto'}}>
                                <Grid container spacing = {2} >
                                    { dependencias.map((dep, a) => { 
                                        return(
                                            <Grid item xl={4} md={4} sm={12} xs={12} key={a}>
                                                <FormGroup id={dep.depeid}>
                                                    <FormControlLabel control={<Checkbox color="secondary" onChange={(e) => handleCheckboxChange(e, dep)} />} label={dep.depenombre} />
                                                </FormGroup>
                                            </Grid>
                                        )
                                    })}
                                </Grid>
                            </Box>

                            {(checkedDependencias.length > 0) ?
                                <Grid container spacing = {2}  style={{marginTop:'1em'}}>
                                    <Grid item md={12} xl={12} sm={12} xs={12}>
                                        <Box className='frmDivision'>
                                            Dependencia marcadas para enviar copia del documento
                                        </Box>
                                        <Box>
                                            <ul>
                                                {checkedDependencias.map((dep) => (
                                                    <li key={dep.depesigla}>{dep.depenombre}</li>
                                                ))}
                                            </ul>
                                        </Box>
                                    </Grid>
                                </Grid>
                            : null}
                        </Box>
                    : null}

                    { (tipo === 'U') ?
                        <Grid item md={12} xl={12} sm={12} xs={12} >
                            <Anexos data={anexosRadicado} eliminar={'false'} cantidadAdjunto={cantidadAdjunto}/>
                        </Grid>
                    : null }

                    {((tipo=== 'I' && habilitarAnexos) || (tipo=== 'U' && habilitarAnexos && (totalAdjunto - totalAdjuntoSubido) > 0) )  ?
                        <Grid container spacing = {2} style={{ transition: 'all .2s ease-in-out'}}>
                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='divisionFormulario'>
                                    Anexos a la solicitud de radicado si se presentan
                                </Box>
                            </Grid>

                            <Grid item md={6} xl={6} sm={12} xs={12}>
                                <Files
                                    className='files-dropzone'
                                    onChange={(file ) =>{onFilesChange(file, 'archivos') }}
                                    onError={onFilesError}
                                    accepts={['.jpg', '.png', '.jpeg', '.doc', '.docx', '.pdf', '.xls', '.xlsx', '.ppt', '.pptx','.xlsm','.zip','.rar']} 
                                    multiple
                                    maxFiles={totalAdjunto - totalAdjuntoSubido}
                                    maxFileSize={2000000}
                                    minFileSize={0}
                                    clickable
                                    dropActiveClassName={"files-dropzone-active"}
                                >
                                <ButtonFileImg title={"Adicionar anexos"} />
                                </Files>
                            </Grid>

                            <Grid item md={6} xl={6} sm={12} xs={12}>
                                <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                                    {formData.archivos.map((file, a) =>{
                                        return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
                                    })}
                                </Box>
                            </Grid>

                            <Grid item md={6} xl={6} sm={12}>
                                <Box className={'msgAlert'}>
                                    <Avatar className={'avatar'}> <WarningIcon /></Avatar> 
                                    <p>Nota: Recuerde que pueden subir como máximos ({totalAdjunto}) archivos, actualmente ha subido ({totalAdjuntoSubido}) archivos. Solo es permitido los formatos tipo .PDF, .DOCX, .DOC, .XLS, XLSX, .PPT, .PPTX .JPG .PNG, ZIP, y .RAR</p>
                                </Box>
                            </Grid>

                        </Grid>
                    : null}

                    <Grid container direction="row"  justifyContent="right">
                        <Stack direction="row" spacing={2}>
                            <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                                startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                            </Button>
                        </Stack>
                    </Grid>

                </Card>
            
            </ValidatorForm>

            {<ModalDefaultAuto 
                    title={'Visualizar formato en PDF del stickers del radicado'} 
                    content={<PdfStickers id={idRadicado} />} 
                    close={() =>{setAbrirModal(false);}} 
                    tam= 'smallFlot' 
                    abrir= {abrirModal}
                />}
        </Box>
    )
}
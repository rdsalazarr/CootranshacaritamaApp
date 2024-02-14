import React, {useState, useEffect, Fragment} from 'react';
import {Button, Grid, MenuItem, Box, Stack, Avatar, Autocomplete, createFilterOptions } from '@mui/material';
import {TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {ButtonFileImg, ContentFile} from "../../../layout/files";
import showSimpleSnackbar from '../../../layout/snackBar';
import WarningIcon from '@mui/icons-material/Warning';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import Files from "react-files";
import Anexos from './anexos';

import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DateTimePicker } from '@mui/x-date-pickers';
import TextField from '@mui/material/TextField';
import "/resources/scss/fechaDatePicker.scss";
import esLocale from 'dayjs/locale/es';
import 'dayjs/locale/es';

export default function New({data, tipo}){

    const [formData, setFormData] = useState({ codigoSolicitud: (tipo === 'U') ? data.solicitudId : '000', codigoRadicado: (tipo === 'U') ? data.idRadicado : '000',
                                                tipoIdentificacion: '', numeroIdentificacion: '', primerNombre: '',      segundoNombre: '',            primerApellido: '', 
                                                segundoApellido: '',    direccionFisica: '',      correoElectronico: '', numeroContacto: '',           tipoSolicitud: '',
                                                tipoMedio: '',          vehiculoId: '',           conductorId: '',       observacionGeneral: '',       motivoSolicitud: '',
                                                fechaHoraIncidente:'',  personaId: '',            tipo:tipo,             tipoIdentificacionNombre: '', tipoSolicitudNombre: '',
                                                vehiculoNombre: '',     conductorNombre: '',      tipoMedioNombre:'',    archivos:[]
                                            });

    const [totalAdjunto, setTotalAdjunto] = useState(import.meta.env.VITE_TOTAL_FILES_RADICADO);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [totalAdjuntoSubido , setTotalAdjuntoSubido] = useState(0);
    const [tipoSolicitudes, setTipoSolicitudes] = useState([]);
    const [fechaActual, setFechaActual] = useState(new Date());
    const [anexosRadicado, setAnexosRadicado] = useState([]);
    const [conductores, setConductores] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [esEmpresa, setEsEmpresa] = useState(false);
    const [tipoMedios, setTipoMedios] = useState([]);
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}));
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}));
    }
    const handleChangeDate = (e) => {
        setFormData((prevData) => ({...prevData, fechaHoraIncidente: formatearFechaHora(e)}));
    }

    const formatearFechaHora = (date) =>{
        let fecha    = new Date(date);
        let anio     = fecha.getFullYear();
        let mes      = (fecha.getMonth() + 1).toString().padStart(2, '0'); // Agregar 1 porque los meses comienzan desde 0
        let dia      = fecha.getDate().toString().padStart(2, '0');
        let horas    = fecha.getHours().toString().padStart(2, '0');
        let minutos  = fecha.getMinutes().toString().padStart(2, '0');
        let segundos = fecha.getSeconds().toString().padStart(2, '0');

        return  `${anio}-${mes}-${dia} ${horas}:${minutos}:${segundos}`;
    }

    const cantidadAdjunto = () =>{
        let totalAdjSubido = parseInt(totalAdjuntoSubido) - 1 ;
        setTotalAdjuntoSubido(totalAdjSubido);
    }

    const onFilesChange = (files, nombre) =>  {
        setFormData(prev => ({...prev, [nombre]: files}));
    }

    const removeFIle = (nombre)=>{
        setFormData(prev => ({...prev, archivos: prev.archivos.filter(item => item.name !== nombre)}));
    }

    const onFilesError = (error, file) => {
        let msj = (error.code === 2) ? 'El archivo "'+ file.name + '" es demasiado grande y no se puede subir' : error.message;
        showSimpleSnackbar(msj, 'error');
    }

    const handleSubmit = () =>{
        let newFormData                      = {...formData}
        const tipoIdentificacionFiltrado     = tipoIdentificaciones.filter((tpIdentificacion) => tpIdentificacion.tipideid == formData.tipoIdentificacion);
        const tipoSolicitudFiltrado          = tipoSolicitudes.filter((tpSolicitud) => tpSolicitud.tipsolid == formData.tipoSolicitud);
        const tipoMedioSolicitudFiltrado     = tipoMedios.filter((tpMedio) => tpMedio.timesoid == formData.tipoMedio);
        const vehiculosFiltrado              = vehiculos.filter((vehic) => vehic.vehiid == formData.vehiculoId);
        const condutorFiltrado               = conductores.filter((cond) => cond.condid == formData.conductorId);
        newFormData.tipoIdentificacionNombre = tipoIdentificacionFiltrado[0].tipidenombre;
        newFormData.tipoSolicitudNombre      = tipoSolicitudFiltrado[0].tipsolnombre;
        newFormData.tipoMedioNombre          = tipoMedioSolicitudFiltrado[0].timesonombre;
        newFormData.vehiculoNombre           = (vehiculosFiltrado.length > 0) ? vehiculosFiltrado[0].nombreVehiculo : '';
        newFormData.conductorNombre          = (condutorFiltrado.length > 0) ? condutorFiltrado[0].nombreConductor : '';

       // setLoader(true);
        instance.post('/admin/antencion/usuario/salve/datos', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigoSolicitud: formData.codigoSolicitud, codigoRadicado: formData.codigoSolicitud,
                                                tipoIdentificacion: '', numeroIdentificacion: '', primerNombre: '',      segundoNombre: '',            primerApellido: '', 
                                                segundoApellido: '',    direccionFisica: '',      correoElectronico: '', numeroContacto: '',           tipoSolicitud: '',
                                                tipoMedio: '',          vehiculoId: '',           conductorId: '',       observacionGeneral: '',       motivoSolicitud: '',
                                                fechaHoraIncidente:'',  personaId: '',            tipo:tipo,             tipoIdentificacionNombre: '', tipoSolicitudNombre: '',
                                                vehiculoNombre: '',     conductorNombre: '',      tipoMedioNombre:'',    archivos:[]}) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{ 
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/antencion/usuario/listar/datos', {solicitudId: formData.codigoSolicitud, radicadoId: formData.codigoSolicitud, tipo: formData.tipo}).then(res=>{
            newFormData.fechaHoraIncidente = res.fechaHoraActual;
            const totalAnexos              = res.anexosRadicados

            if(tipo === 'U'){
                let solicitud                    = res.data;
                newFormData.personaId            = solicitud.peradoid;
                newFormData.tipoIdentificacion   = solicitud.tipideid;
                newFormData.numeroIdentificacion = solicitud.peradodocumento;
                newFormData.primerNombre         = solicitud.peradoprimernombre;
                newFormData.segundoNombre        = solicitud.peradosegundonombre;
                newFormData.primerApellido       = solicitud.peradoprimerapellido;
                newFormData.segundoApellido      = solicitud.peradosegundoapellido;
                newFormData.direccionFisica      = solicitud.peradodireccion;
                newFormData.correoElectronico    = solicitud.peradocorreo;
                newFormData.numeroContacto       = solicitud.peradotelefono;
                newFormData.tipoSolicitud        = solicitud.tipsolid;
                newFormData.tipoMedio            = solicitud.timesoid;
                newFormData.vehiculoId           = solicitud.vehiid;
                newFormData.conductorId          = solicitud.condid;
                newFormData.observacionGeneral   = solicitud.soliobservacion;
                newFormData.motivoSolicitud      = solicitud.solimotivo;
                newFormData.fechaHoraIncidente   = solicitud.solifechahoraincidente;
            }

            setTipoIdentificaciones(res.tipoIdentificaciones);
            setTotalAdjuntoSubido(totalAnexos.length);
            setTipoSolicitudes(res.tipoSolicitudes);
            setAnexosRadicado(res.anexosRadicados);
            setConductores(res.conductores);
            setTipoMedios(res.tipoMedios);
            setVehiculos(res.vehiculos);
            setFormData(newFormData);
            setLoader(false);
        })
    }

    const consultarPersona = (e) =>{
        let newFormData                  = {...formData}
        let tpIdentificacion             = (e.target.name === 'tipoIdentificacion' ) ? e.target.value : formData.tipoIdentificacion;
        let numeroIdentificacion         = (e.target.name === 'numeroIdentificacion' ) ? e.target.value : formData.numeroIdentificacion ;
        newFormData.tipoIdentificacion   = tpIdentificacion;
        newFormData.numeroIdentificacion = numeroIdentificacion;
       if (tpIdentificacion !=='' && formData.numeroIdentificacion !==''){
            setLoader(true);
            instance.post('/admin/antencion/usuario/consultar/persona', {tipoIdentificacion:tpIdentificacion, numeroIdentificacion: formData.numeroIdentificacion}).then(res=>{
                if(res.success){
                    newFormData.personaId         = res.data.peradoid;
                    newFormData.primerNombre      = res.data.peradoprimernombre;
                    newFormData.segundoNombre     = (res.data.peradosegundonombre !== undefined) ? res.data.peradosegundonombre : '';
                    newFormData.primerApellido    = (res.data.peradoprimerapellido !== undefined) ? res.data.peradoprimerapellido : '';
                    newFormData.segundoApellido   = (res.data.peradosegundoapellido !== undefined) ? res.data.peradosegundoapellido : '';
                    newFormData.direccionFisica   = (res.data.peradodireccion !== undefined) ? res.data.peradodireccion : '';
                    newFormData.correoElectronico = (res.data.peradocorreo !== undefined) ? res.data.peradocorreo : '';
                    newFormData.numeroContacto    = (res.data.peradotelefono !== undefined) ? res.data.peradotelefono : '';
                }else{
                    newFormData.personaId         = '000';
                    newFormData.primerNombre      = '';
                    newFormData.segundoNombre     = '';
                    newFormData.primerApellido    = '';
                    newFormData.segundoApellido   = '';
                    newFormData.direccionFisica   = '';
                    newFormData.correoElectronico = '';
                    newFormData.numeroContacto    = '';
                }
                setLoader(false);
            })
        }
        setEsEmpresa((e.target.value === 5) ? true : false);
        setFormData(newFormData);
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <ValidatorForm onSubmit={handleSubmit} >

                <Grid container spacing={2} >
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='divisionFormulario'>
                            Información del solicitante
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
                            onChange={handleChangeUpperCase}
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
                                    inputProps={{ maxLength: 40}}
                                    onChange={handleChangeUpperCase}
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
                            validators={['isEmail']}
                            errorMessages={['Correo no válido']}
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

                    <Grid item xl={12} md={12} sm={12} xs={12}> 
                        <Box className='divisionFormulario'>
                            Información de la solicitud 
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <LocalizationProvider dateAdapter={AdapterDateFns} locale={esLocale}>
                            <DateTimePicker
                                label="Fecha y hora de incidente"
                                value={new Date(fechaActual)}
                                renderInput={(props) => <TextField {...props} className={'inputGeneral'} />}
                                onChange={handleChangeDate}
                            />
                        </LocalizationProvider>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'tipoSolicitud'}
                            value={formData.tipoSolicitud}
                            label={'Tipo de solicitud'} 
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoSolicitudes.map(res=>{
                                return <MenuItem value={res.tipsolid} key={res.tipsolid}>{res.tipsolnombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
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
                                return <MenuItem value={res.timesoid} key={res.timesoid}>{res.timesonombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Autocomplete
                            id="vehiculoId"
                            style={{height: "26px", width: "100%"}}
                            options={vehiculos}
                            getOptionLabel={(option) => option.nombreVehiculo}
                            value={vehiculos.find(v => v.vehiid === formData.vehiculoId) || null}
                            filterOptions={createFilterOptions({ limit:10 })}
                            onChange={(event, newInputValue) => {
                                if(newInputValue){
                                    setFormData({...formData, vehiculoId: newInputValue.vehiid})
                                }
                            }}
                            renderInput={(params) =>
                                <TextValidator {...params}
                                    label="Consultar vehículo"
                                    className="inputGeneral"
                                    variant="standard"
                                    value={formData.vehiculoId}
                                    placeholder="Consulte el vehículo aquí..." />}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Autocomplete
                            id="conductorId"
                            style={{height: "26px", width: "100%"}}
                            options={conductores}
                            getOptionLabel={(option) => option.nombreConductor}
                            value={conductores.find(v => v.condid === formData.conductorId) || null}
                            filterOptions={createFilterOptions({ limit:10 })}
                            onChange={(event, newInputValue) => {
                                if(newInputValue){
                                    setFormData({...formData, conductorId: newInputValue.condid})
                                }
                            }}
                            renderInput={(params) =>
                                <TextValidator {...params}
                                    label="Consultar conductor"
                                    className="inputGeneral"
                                    variant="standard"
                                    value={formData.conductorId}
                                    placeholder="Consulte el conductor aquí..." />}
                        />
                    </Grid>

                    <Grid item xl={6} md={6} sm={6} xs={12}>
                        <TextValidator
                            name={'observacionGeneral'}
                            value={formData.observacionGeneral}
                            label={'Observación general'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 1000}}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <TextValidator
                            multiline
                            maxRows={10}
                            name={'motivoSolicitud'}
                            value={formData.motivoSolicitud}
                            label={'Motivo de las solicitud'}
                            className={'inputGeneral'} 
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 2000}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>
                </Grid>

                { (tipo === 'U' && anexosRadicado.length > 0 ) ?
                    <Grid item md={12} xl={12} sm={12} xs={12} >
                        <Anexos data={anexosRadicado} eliminar={'false'} cantidadAdjunto={cantidadAdjunto}/>
                    </Grid>
                : null }

                {((tipo=== 'I') || (tipo=== 'U' && (totalAdjunto - totalAdjuntoSubido) > 0) )  ?
                    <Grid container spacing = {2} style={{ transition: 'all .2s ease-in-out'}}>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='divisionFormulario'>
                                Anexos a la solicitud si se presentan
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

            </ValidatorForm>
        </Box>
    )
}
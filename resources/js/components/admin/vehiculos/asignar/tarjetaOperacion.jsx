import React, {useState, useEffect, Fragment} from 'react';
import { Button, Grid, Stack, MenuItem, Table, TableHead, TableBody, TableRow, TableCell, Box, Avatar} from '@mui/material';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {ButtonFileImg, ContentFile} from "../../../layout/files";
import VisibilityIcon from '@mui/icons-material/Visibility';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import ErrorIcon from '@mui/icons-material/Error';
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import ShowAnexo from '../showAnexo';
import Files from "react-files";

export default function TarjetaOperacion({id}){

    const [formData, setFormData] = useState({vehiculoId: id, codigo:'000', tipoServicio:'',  numeroTarjetaOperacion:'', fechaInicio: '', fechaVencimiento: '', 
                                                enteAdministrativo: '', radioAccion: '', extension: '' });
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [tipoServiciosVehiculos, setTipoServiciosVehiculos] = useState([]); 
    const [historialTarjetaOperacion, setHistorialTarjetaOperacion] = useState([]); 
    const [formDataFile, setFormDataFile] = useState({ imagenTarjetaOperacion:[]});
    const [modal, setModal] = useState({open : false, extencion:'', ruta:''});
    
    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}));
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

    const cerrarModal = () =>{
        setModal({open : false});
    }

    const handleSubmit = () =>{
        let dataFile = new FormData();
        Object.keys(formData).forEach(function(key) {
           dataFile.append(key, formData[key])
        })

        let imagenTarjetaOperacion = formDataFile.imagenTarjetaOperacion;
        dataFile.append('imagenTarjetaOperacion', (imagenTarjetaOperacion[0] != undefined) ? imagenTarjetaOperacion[0] : '');

        setLoader(true); 
        instance.post('/admin/direccion/transporte/tarjeta/operacion/salve', dataFile).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({vehiculoId: id, codigo:'000',  tipoServicio:'',  numeroTarjetaOperacion:'', fechaInicio: '', fechaVencimiento: '', 
                                                                enteAdministrativo: '', radioAccion: '', extension: '' }) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData  = {...formData}
        instance.post('/admin/direccion/transporte/listar/tarjeta/operacion', {vehiculoId: id}).then(res=>{ 
            let tarjetaOperacionVehiculo = res.tarjetaOperacionVehiculo;
            let debeCrearRegistro        = res.debeCrearRegistro;
            let maxFechaVencimiento      = res.maxFechaVencimiento;

            if(!debeCrearRegistro && maxFechaVencimiento !== ''){
                newFormData.codigo                      = tarjetaOperacionVehiculo.vetaopid;
                newFormData.tipoServicio                = tarjetaOperacionVehiculo.tiseveid;
                newFormData.numeroTarjetaOperacion      = tarjetaOperacionVehiculo.vetaopnumero;
                newFormData.fechaInicio                 = tarjetaOperacionVehiculo.vetaopfechainicial;
                newFormData.fechaVencimiento            = tarjetaOperacionVehiculo.vetaopfechafinal;
                newFormData.enteAdministrativo          = tarjetaOperacionVehiculo.vetaopenteadministrativo;
                newFormData.radioAccion                 = tarjetaOperacionVehiculo.vetaopradioaccion;
                newFormData.extension                   = (tarjetaOperacionVehiculo.vetaopextension !== null) ? tarjetaOperacionVehiculo.vetaopextension : '';
                newFormData.rutaAdjuntoTarjetaOperacion = tarjetaOperacionVehiculo.rutaAdjuntoTarjetaOperacion;
                newFormData.rutaArchivoTarjetaOperacion = tarjetaOperacionVehiculo.vetaoprutaarchivo;
            }
            newFormData.maxFechaVencimiento = maxFechaVencimiento;
            newFormData.crearHistorial      = debeCrearRegistro;
            setFormData(newFormData);
            setHistorialTarjetaOperacion(res.historialTarjetaOperacion);
            setTipoServiciosVehiculos(res.tipoServiciosVehiculos);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Información del póliza actual
                </Box>
            </Grid>

            <ValidatorForm onSubmit={handleSubmit} style={{marginTop:'1em'}}>
                <Grid container spacing={2}>
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'numeroTarjetaOperacion'}
                            value={formData.numeroTarjetaOperacion}
                            label={'Número de tarjeta de operación'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 30}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChangeUpperCase}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'fechaInicio'}
                            value={formData.fechaInicio}
                            label={'Fecha de inicio'}
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
                            value={formData.fechaVencimiento}
                            label={'Fecha de vencimiento'}
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
                            name={'tipoServicio'}
                            value={formData.tipoServicio}
                            label={'Tipo de servicio'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoServiciosVehiculos.map(res=>{
                                return <MenuItem value={res.tiseveid} key={res.tiseveid} >{res.tisevenombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'enteAdministrativo'}
                            value={formData.enteAdministrativo}
                            label={'Ente administrativo'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            <MenuItem value={"T"}>Tránsito</MenuItem>
                            <MenuItem value={"M"}>Ministerio</MenuItem>
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}> 
                        <SelectValidator
                            name={'radioAccion'}
                            value={formData.radioAccion}
                            label={'Radio de acción'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            <MenuItem value={"N"}>Nacional</MenuItem>
                            <MenuItem value={"M"}>Municipal</MenuItem>
                        </SelectValidator>
                    </Grid>

                    <Grid item md={4} xl={4} sm={8} xs={12}>
                        <Files
                            className='files-dropzone'
                            onChange={(file ) =>{onFilesChange(file, 'imagenTarjetaOperacion') }}
                            onError={onFilesError}
                            accepts={['.jpg', '.png', '.jpeg', '.pdf', '.PDF']} 
                            multiple
                            maxFiles={1}
                            maxFileSize={1000000}
                            clickable
                            dropActiveClassName={"files-dropzone-active"}
                        >
                        <ButtonFileImg title={"Adicionar imagen del tarjeta de operación en formato jpg, png o pdf"} />
                        </Files>
                    </Grid>

                    <Grid item md={2} xl={2} sm={4} xs={12}>
                        <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                            {formDataFile.imagenTarjetaOperacion.map((file, a) =>{
                                return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
                            })}
                        </Box>
                    </Grid>

                    {(formData.extension !== '') ?
                        <Grid item md={3} xl={3} sm={12} xs={12} style={{marginTop:'1em'}}>
                            <Box className='frmTexto'>
                                <label>Visualizar adjunto del la tarjeta de operación </label>
                            </Box>
                            <Avatar style={{backgroundColor: '#43ab33', cursor: 'pointer'}}>
                                <VisibilityIcon onClick={() => {setModal({open: true, extencion: formData.extension, ruta:formData.rutaAdjuntoTarjetaOperacion,  rutaEnfuscada: formData.rutaArchivoTarjetaOperacion })}} />
                            </Avatar>
                        </Grid>
                    : null }

                    {(formData.crearHistorial) ?
                        <Grid item md={12} xl={12} sm={12} xs={12} style={{marginTop:'1em'}}>
                            <Box className='mensajeAdvertencia'>
                                <ErrorIcon />
                                <p>La tarjeta de operación registrada vencerá o vencío el {formData.maxFechaVencimiento}, por lo que el sistema le ha habilitado las casillas para que pueda ingresar una nueva tarjeta de operación.</p>
                            </Box>
                        </Grid>
                    : null}

                </Grid>

                <Grid container direction="row"  justifyContent="right" style={{marginTop:'1em'}}>
                    <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                            startIcon={<SaveIcon />}> {"Guardar" }
                        </Button>
                    </Stack>
                </Grid>
            </ValidatorForm>

            {(historialTarjetaOperacion.length > 0) ?
                <Grid container spacing={2}>
                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='frmDivision'>
                            Historial de tarjetas de operación asignadas al vehículo
                        </Box>
                    </Grid>

                    <Grid item xl={12} md={12} sm={12} xs={12} style={{marginTop:'0.2em'}}>
                        <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                            <Table className={'tableAdicional'} sx={{width: '90%', margin:'auto'}} sm={{maxHeight: '100%', margin:'auto'}} >
                                <TableHead>
                                    <TableRow>
                                        <TableCell>Número de tarjeta de operación</TableCell>
                                        <TableCell>Fecha inicial</TableCell>
                                        <TableCell>Fecha final</TableCell>
                                        <TableCell>Tipo de servicio</TableCell>
                                        <TableCell>Ente administrativo</TableCell>
                                        <TableCell>Radio de acción</TableCell>
                                        <TableCell>Adjunto</TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>
                                { historialTarjetaOperacion.map((historial, a) => {
                                    return(
                                        <TableRow key={'rowD-' +a}>
                                            <TableCell>
                                                <p>{historial['vetaopnumero']} </p>
                                            </TableCell>

                                            <TableCell>
                                                <p>{historial['vetaopfechainicial']} </p>
                                            </TableCell>

                                            <TableCell>
                                                <p>{historial['vetaopfechafinal']} </p>
                                            </TableCell>

                                            <TableCell>
                                                <p>{historial['tisevenombre']} </p>
                                            </TableCell>

                                            <TableCell>
                                                <p>{historial['enteAdministrativo']} </p>
                                            </TableCell>

                                            <TableCell>
                                                <p>{historial['radioAccion']} </p>
                                            </TableCell>

                                            <TableCell className='cellCenter'>
                                                {(historial['vetaopextension'] !== null) ?
                                                    <Avatar style={{backgroundColor: '#43ab33', cursor: 'pointer'}}>
                                                        <VisibilityIcon onClick={() => {setModal({open: true, extencion: historial['vetaopextension'], ruta:historial['rutaAdjuntoTarjetaOperacion'],  rutaEnfuscada:historial['vetaoprutaarchivo']})}} />
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
                </Grid>
            : null}

            <ModalDefaultAuto
                title={'Visualizar adjunto'}
                content={<ShowAnexo extencion={modal.extencion} ruta={modal.ruta} rutaEnfuscada={modal.rutaEnfuscada} cerrarModal={cerrarModal} />}
                close={() =>{setModal({open : false})}}
                tam = {'smallFlot'}
                abrir ={modal.open}
            />

        </Fragment>
    )
}
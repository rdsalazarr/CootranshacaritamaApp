import React, {useState, useEffect, Fragment} from 'react';
import { Button, Grid, Stack, Table, TableHead, TableBody, TableRow, TableCell, Box, Avatar} from '@mui/material';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import {ButtonFileImg, ContentFile} from "../../../layout/files";
import VisibilityIcon from '@mui/icons-material/Visibility';
import { ModalDefaultAuto } from '../../../layout/modal';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import ErrorIcon from '@mui/icons-material/Error';
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import ShowAnexo from '../showAnexo';
import Files from "react-files";

export default function Crt({id}){

    const [formData, setFormData] = useState({vehiculoId: id, codigo:'000', numeroCrt:'', fechaInicio: '', fechaVencimiento: '', extension: '' });
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [historialCrtVehiculo, setHistorialCrtVehiculo] = useState([]); 
    const [formDataFile, setFormDataFile] = useState({ imagenCrt:[]});
    const [modal, setModal] = useState({open : false, extencion:'', ruta:''});
    
    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
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

        let imagenCrt = formDataFile.imagenCrt;
        dataFile.append('imagenCrt', (imagenCrt[0] != undefined) ? imagenCrt[0] : '');

        setLoader(true); 
        instance.post('/admin/direccion/transporte/crt/salve', dataFile).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({vehiculoId: id, codigo:'000', numeroCrt:'', fechaInicio: '', fechaVencimiento: ''}) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData  = {...formData}
        instance.post('/admin/direccion/transporte/listar/crt', {vehiculoId: id}).then(res=>{ 
            let crtVehiculo             = res.crtVehiculo;
            let debeCrearRegistro       = res.debeCrearRegistro;

            if(!debeCrearRegistro && crtVehiculo.length > 0){
                newFormData.codigo           = crtVehiculo.vehcrtid;
                newFormData.numeroCrt        = crtVehiculo.vehcrtnumero;
                newFormData.fechaInicio      = crtVehiculo.vehcrtfechainicial;
                newFormData.fechaVencimiento = crtVehiculo.vehcrtfechafinal;
                newFormData.extension        = (crtVehiculo.vehcrtextension !== null) ? crtVehiculo.vehcrtextension : '';
                newFormData.rutaAdjuntoCrt   = crtVehiculo.rutaAdjuntoCrt;
                newFormData.rutaArchivoCrt   = crtVehiculo.vehcrtrutaarchivo;
            }
            newFormData.maxFechaVencimiento = res.maxFechaVencimiento;
            newFormData.crearHistorial      = debeCrearRegistro;
            setFormData(newFormData);
            setHistorialCrtVehiculo(res.historialCrtVehiculo);
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
                    Información del CRT actual
                </Box>
            </Grid>

            <ValidatorForm onSubmit={handleSubmit} style={{marginTop:'1em'}}>
                <Grid container spacing={2}>
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'numeroCrt'}
                            value={formData.numeroCrt}
                            label={'Número del CRT'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 30}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={2} md={2} sm={6} xs={12}>
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

                    <Grid item xl={2} md={2} sm={6} xs={12}>
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

                    <Grid item md={3} xl={3} sm={12} xs={12}>
                        <Files
                            className='files-dropzone'
                            onChange={(file ) =>{onFilesChange(file, 'imagenCrt') }}
                            onError={onFilesError}
                            accepts={['.jpg', '.png', '.jpeg', '.pdf', '.PDF']} 
                            multiple
                            maxFiles={1}
                            maxFileSize={1000000}
                            clickable
                            dropActiveClassName={"files-dropzone-active"}
                        >
                        <ButtonFileImg title={"Adicionar imagen del CRT en formato jpg, png o pdf"} />
                        </Files>
                    </Grid>

                    <Grid item md={2} xl={2} sm={12} xs={12}>
                        <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                            {formDataFile.imagenCrt.map((file, a) =>{
                                return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
                            })}
                        </Box>
                    </Grid>

                    {(formData.extension !== '') ?
                        <Grid item md={3} xl={3} sm={12} xs={12}>
                            <Box className='frmTexto'>
                                <label>Visualizar adjunto del CRT </label>
                            </Box>
                            <Avatar style={{backgroundColor: '#43ab33', cursor: 'pointer'}}>
                                <VisibilityIcon onClick={() => {setModal({open: true, extencion: formData.extension, ruta:formData.rutaAdjuntoCrt,  rutaEnfuscada: formData.rutaArchivoCrt })}} />
                            </Avatar>
                        </Grid>
                    : null }

                    {(formData.crearHistorial) ?
                        <Grid item md={12} xl={12} sm={12} xs={12} style={{marginTop:'1em'}}>
                            <Box className='mensajeAdvertencia'>
                                <ErrorIcon />
                                <p>El CRT registrado vencerá o vencío el {formData.maxFechaVencimiento}, por lo que el sistema le ha habilitado las casillas para que pueda ingresar un  nuevo CRT.</p>
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

            {(historialCrtVehiculo.length > 0) ?
                <Grid container spacing={2}>
                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='frmDivision'>
                            Historial de CRT asignado al vehículo
                        </Box>
                    </Grid>

                    <Grid item xl={12} md={12} sm={12} xs={12} style={{marginTop:'0.2em'}}> 
                        <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                            <Table className={'tableAdicional'} sx={{width: '70%', margin:'auto'}} sm={{maxHeight: '100%', margin:'auto'}} >
                                <TableHead>
                                    <TableRow>
                                        <TableCell>Número de CRT</TableCell>
                                        <TableCell>Fecha inicial</TableCell>
                                        <TableCell>Fecha final</TableCell>
                                        <TableCell>Adjunto</TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>
                                { historialCrtVehiculo.map((historial, a) => {
                                    return(
                                        <TableRow key={'rowD-' +a}>
                                            <TableCell>
                                                <p>{historial['vehcrtnumero']} </p>
                                            </TableCell>

                                            <TableCell> 
                                                <p>{historial['vehcrtfechainicial']} </p>
                                            </TableCell>

                                            <TableCell> 
                                                <p>{historial['vehcrtfechafinal']} </p>
                                            </TableCell>

                                            <TableCell className='cellCenter'>
                                                {(historial['vehcrtextension'] !== null) ?
                                                    <Avatar style={{backgroundColor: '#43ab33', cursor: 'pointer'}}>
                                                        <VisibilityIcon onClick={() => {setModal({open: true, extencion: historial['vehcrtextension'], ruta:historial['rutaAdjuntoCrt'],  rutaEnfuscada:historial['vehcrtrutaarchivo']})}} />
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
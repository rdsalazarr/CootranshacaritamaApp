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

export default function Polizas({id}){

    const [formData, setFormData] = useState({vehiculoId: id, codigo:'000', numeroPolizaContractual:'',  numeroPolizaExtraContractual:'', fechaInicio: '', fechaVencimiento: '', extension: '' });
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [historialPolizasVehiculo, setHistorialPolizasVehiculo] = useState([]); 
    const [formDataFile, setFormDataFile] = useState({ imagenPoliza:[]});
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

        let imagenPoliza = formDataFile.imagenPoliza;
        dataFile.append('imagenPoliza', (imagenPoliza[0] != undefined) ? imagenPoliza[0] : '');

        setLoader(true); 
        instance.post('/admin/direccion/transporte/poliza/salve', dataFile).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({vehiculoId: id, codigo:'000', numeroPolizaContractual:'',  numeroPolizaExtraContractual:'', fechaInicio: '', fechaVencimiento: ''}) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData  = {...formData}
        instance.post('/admin/direccion/transporte/listar/poliza', {vehiculoId: id}).then(res=>{ 
            let polizaVehiculo      = res.polizaVehiculo;
            let debeCrearRegistro   = res.debeCrearRegistro;
            let maxFechaVencimiento = res.maxFechaVencimiento;

            if(!debeCrearRegistro && maxFechaVencimiento !== ''){
                newFormData.codigo                       = polizaVehiculo.vehpolid;
                newFormData.numeroPolizaContractual      = polizaVehiculo.vehpolnumeropolizacontractual;
                newFormData.numeroPolizaExtraContractual = polizaVehiculo.vehpolnumeropolizaextcontrac;
                newFormData.fechaInicio                  = polizaVehiculo.vehpolfechainicial;
                newFormData.fechaVencimiento             = polizaVehiculo.vehpolfechafinal;
                newFormData.extension                    = (polizaVehiculo.vehpolextension !== null) ? polizaVehiculo.vehpolextension : '';
                newFormData.rutaAdjuntoPoliza            = polizaVehiculo.rutaAdjuntoPoliza;
                newFormData.rutaArchivoPoliza            = polizaVehiculo.vehpolrutaarchivo;
            }
            newFormData.maxFechaVencimiento = maxFechaVencimiento;
            newFormData.crearHistorial      = debeCrearRegistro;
            setFormData(newFormData);
            setHistorialPolizasVehiculo(res.historialPolizasVehiculo);
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
                            name={'numeroPolizaContractual'}
                            value={formData.numeroPolizaContractual}
                            label={'Número del póliza contractual'}
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
                            name={'numeroPolizaExtraContractual'}
                            value={formData.numeroPolizaExtraContractual}
                            label={'Número del póliza extra contractual'}
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

                    <Grid item md={4} xl={4} sm={8} xs={12}>
                        <Files
                            className='files-dropzone'
                            onChange={(file ) =>{onFilesChange(file, 'imagenPoliza') }}
                            onError={onFilesError}
                            accepts={['.jpg', '.png', '.jpeg', '.pdf', '.PDF']} 
                            multiple
                            maxFiles={1}
                            maxFileSize={1000000}
                            clickable
                            dropActiveClassName={"files-dropzone-active"}
                        >
                        <ButtonFileImg title={"Adicionar imagen del póliza en formato jpg, png o pdf"} />
                        </Files>
                    </Grid>

                    <Grid item md={2} xl={2} sm={4} xs={12}>
                        <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                            {formDataFile.imagenPoliza.map((file, a) =>{
                                return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
                            })}
                        </Box>
                    </Grid>

                    {(formData.extension !== '') ?
                        <Grid item md={3} xl={3} sm={12} xs={12} style={{marginTop:'1em'}}>
                            <Box className='frmTexto'>
                                <label>Visualizar adjunto del la póliza </label> 
                            </Box>
                            <Avatar style={{backgroundColor: '#43ab33', cursor: 'pointer'}}>
                                <VisibilityIcon onClick={() => {setModal({open: true, extencion: formData.extension, ruta:formData.rutaAdjuntoPoliza,  rutaEnfuscada: formData.rutaArchivoPoliza })}} />
                            </Avatar>
                        </Grid>
                    : null }

                    {(formData.crearHistorial) ?
                        <Grid item md={12} xl={12} sm={12} xs={12} style={{marginTop:'1em'}}>
                            <Box className='mensajeAdvertencia'>
                                <ErrorIcon />
                                <p>La póliza registrada vencerá o vencío el {formData.maxFechaVencimiento}, por lo que el sistema le ha habilitado las casillas para que pueda ingresar una nueva póliza.</p>
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

            {(historialPolizasVehiculo.length > 0) ?
                <Grid container spacing={2}>
                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='frmDivision'>
                            Historial de pólizas asignado al vehículo
                        </Box>
                    </Grid>

                    <Grid item xl={12} md={12} sm={12} xs={12} style={{marginTop:'0.2em'}}> 
                        <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                            <Table className={'tableAdicional'} sx={{width: '80%', margin:'auto'}} sm={{maxHeight: '100%', margin:'auto'}} >
                                <TableHead>
                                    <TableRow>
                                        <TableCell>Número de póliza contractual</TableCell>
                                        <TableCell>Número de póliza extra contractual</TableCell>
                                        <TableCell>Fecha inicial</TableCell>
                                        <TableCell>Fecha final</TableCell>
                                        <TableCell>Adjunto</TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>
                                { historialPolizasVehiculo.map((historial, a) => {
                                    return(
                                        <TableRow key={'rowD-' +a}>
                                            <TableCell>
                                                {historial['vehpolnumeropolizacontractual']}
                                            </TableCell>

                                            <TableCell>
                                                {historial['vehpolnumeropolizaextcontrac']}
                                            </TableCell>

                                            <TableCell>
                                                {historial['vehpolfechainicial']}
                                            </TableCell>

                                            <TableCell>
                                                {historial['vehpolfechafinal']}
                                            </TableCell>

                                            <TableCell className='cellCenter'>
                                                {(historial['vehpolextension'] !== null) ?
                                                    <Avatar style={{backgroundColor: '#43ab33', cursor: 'pointer'}}>
                                                        <VisibilityIcon onClick={() => {setModal({open: true, extencion: historial['vehpolextension'], ruta:historial['rutaAdjuntoPoliza'],  rutaEnfuscada:historial['vehpolrutaarchivo']})}} />
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
import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Link, Table, TableHead, TableBody, TableRow, TableCell, Avatar} from '@mui/material';
import CloudDownloadIcon from '@mui/icons-material/CloudDownload';
import {ButtonFileImg, ContentFile} from "../../layout/files";
import VisibilityIcon from '@mui/icons-material/Visibility';
import { ModalDefaultAuto  } from '../../layout/modal';
import showSimpleSnackbar from '../../layout/snackBar';
import ErrorIcon from '@mui/icons-material/Error';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import ShowAnexo from '../vehiculos/showAnexo';
import instance from '../../layout/instance';
import Files from "react-files";

export default function Procesar({data}){

    const [formData, setFormData] = useState({codigo:data.persid,  fechaIngresoAsociado:'', fechaIngresoConductor:'',
                            tipoConductor:'', agencia:'', tipoCategoria:'', numeroLicencia:'', fechaExpedicionLicencia:'', fechaVencimiento:''}); 

    const [tipoConductores, setTipoConductores] = useState([]);
    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [agencias, setAgencias] = useState([]);
    const [tipoCategoriaLicencias, settipoCategoriaLicencias] = useState([]);
    const [formDataFile, setFormDataFile] = useState({imagenLicencia:[]});

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }
 
     const handleChangeUpperCase = (e) => {
         setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
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
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>

            <Grid container spacing={2}>

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
                                Información de la licencia del conducción
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
                                    return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
                                })}
                            </Box>
                        </Grid>


                    </Fragment>

            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {"Guardar" }
                    </Button>
                </Stack>
            </Grid>

        </ValidatorForm>
    )

}
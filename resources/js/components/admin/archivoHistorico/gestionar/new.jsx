import React, {useState, useEffect, Fragment} from 'react';
import {Card, Button, Grid, MenuItem, Box, Stack, Avatar } from '@mui/material';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {ButtonFilePdf, ContentFile} from "../../../layout/files";
import showSimpleSnackbar from '../../../layout/snackBar';
import WarningIcon from '@mui/icons-material/Warning';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import Files from "react-files";
import Anexos from '../anexos';

export default function New({data, tipo}){

    const [formData, setFormData] = useState({ codigo: (tipo === 'U') ? data.archisid : '000',
                                                tipoDocumental: '',    estante: '',         caja: '',          carpeta: '',         fechaDocumento: '', 
                                                numeroFolio: '',       asuntoDocumento: '', tomoDocumento: '', codigoDocumental: '', entidadRemitente: '',
                                                entidadProductora: '', resumenDocumento: '', observacion: '',  tipo:tipo,            archivos:[]
                                            });

    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false);
    const [tipoDocumentales, setTipoDocumentales ] = useState([]);
    const [tipoEstanteArchivadores, setTipoEstanteArchivadores] = useState([]);
    const [tipoCajaUbicaciones, setTipoCajaUbicaciones] = useState([]);
    const [tipoCarpetaUbicaciones, setTipoCarpetaUbicaciones] = useState([]);
    const [totalAdjunto, setTotalAdjunto] = useState(import.meta.env.VITE_TOTAL_FILES_ARCHIVO_HISTORICO);
    const [totalAdjuntoSubido , setTotalAdjuntoSubido] = useState(0);
    const [digitalizados, setDigitalizados] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}));
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
        let msj = (error.code === 2) ? 'El archivo "'+ file.name + '" es demasiado grande y no se puede subir' : error.message  
        ReactDOM.unmountComponentAtNode(document.getElementById("snake"));
        ReactDOM.render(<SimpleSnackbar msg={msj} icon={'error'} />,
        document.getElementById("snake"));
    }

    const handleSubmit = () =>{
       /* if(tipo === 'I' && formData.archivos.length === 0 ){
            showSimpleSnackbar("Debe adjuntar el archivo digitalizado", 'error');
            return;
        }*/

        //setLoader(true);
        instance.post('/admin/archivo/historico/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            if(formData.tipo === 'I' && res.success){
                setFormData({codigo: (tipo === 'U') ? data.id : '000',
                                                    tipoDocumental: '',    estante: '',         caja: '',          carpeta: '',         fechaDocumento: '', 
                                                    numeroFolio: '',       asuntoDocumento: '', tomoDocumento: '', codigoDocumental: '', entidadRemitente: '', 
                                                    entidadProductora: '', resumenDocumento: '', observacion: '',  tipo:tipo,            archivos:[]})
            }

            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/archivo/historico/obtener/datos', {codigo: formData.codigo, tipo: formData.tipo}).then(res=>{

            if(tipo === 'U'){
                let archivoHistorico          = res.data;
                newFormData.tipoDocumental    = archivoHistorico.tipdocid;
                newFormData.estante           = archivoHistorico.tiesarid;
                newFormData.caja              = archivoHistorico.ticaubid;
                newFormData.carpeta           = archivoHistorico.ticrubid;
                newFormData.fechaDocumento    = archivoHistorico.archisfechadocumento;
                newFormData.numeroFolio       = archivoHistorico.archisnumerofolio;
                newFormData.asuntoDocumento   = archivoHistorico.archisasuntodocumento;
                newFormData.tomoDocumento     = (archivoHistorico.archistomodocumento !== null) ? archivoHistorico.archistomodocumento : '';
                newFormData.codigoDocumental  = (archivoHistorico.archiscodigodocumental !== null) ? archivoHistorico.archiscodigodocumental : '';
                newFormData.entidadRemitente  = (archivoHistorico.archisentidadremitente !== null) ? archivoHistorico.archisentidadremitente : '';
                newFormData.entidadProductora = (archivoHistorico.archisentidadproductora !== null) ? archivoHistorico.archisentidadproductora : '';
                newFormData.resumenDocumento  = (archivoHistorico.archisresumendocumento !== null) ? archivoHistorico.archisresumendocumento : ''; 
                newFormData.observacion       = (archivoHistorico.archisobservacion !== null) ? archivoHistorico.archisobservacion : '';

                setTotalAdjuntoSubido(archivoHistorico.totalAnexos);
                setDigitalizados(res.digitalizados);
            }

            setTipoDocumentales(res.tipoDocumentales);
            setTipoEstanteArchivadores(res.tipoEstanteArchivadores);
            setTipoCajaUbicaciones(res.tipoCajaUbicaciones);
            setTipoCarpetaUbicaciones(res.tipoCarpetaUbicaciones);
            setFormData(newFormData);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Card style={{padding: '6px' }}>
                <Grid container spacing={2} >
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'tipoDocumental'}
                            value={formData.tipoDocumental}
                            label={'Tipo documental'} 
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                            tabIndex="1"
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoDocumentales.map(res=>{
                                return <MenuItem value={res.tipdocid} key={res.tipdocid}>{res.tipdocnombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'estante'}
                            value={formData.estante}
                            label={'Estante'} 
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                            tabIndex="2"
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoEstanteArchivadores.map(res=>{
                                return <MenuItem value={res.tiesarid} key={res.tiesarid}>{res.tiesarnombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'caja'}
                            value={formData.caja}
                            label={'Caja'} 
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                            tabIndex="3"
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoCajaUbicaciones.map(res=>{
                                return <MenuItem value={res.ticaubid} key={res.ticaubid}>{res.ticaubnombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'carpeta'}
                            value={formData.carpeta}
                            label={'Carpeta'} 
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                            tabIndex="4"
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoCarpetaUbicaciones.map(res=>{
                                return <MenuItem value={res.ticrubid} key={res.ticrubid}>{res.ticrubnombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'fechaDocumento'}
                            value={formData.fechaDocumento}
                            label={'Fecha documento'}
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
                            name={'numeroFolio'}
                            value={formData.numeroFolio}
                            label={'Número de folio'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required","maxNumber:99"]}
                            errorMessages={["Campo obligatorio","Número máximo permitido es el 99"]}
                            type={"number"}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item md={7} xl={7} sm={6} xs={12}>
                        <TextValidator
                            multiline
                            maxRows={3}
                            name={'asuntoDocumento'}
                            value={formData.asuntoDocumento}
                            label={'Asunto del documento'}
                            className={'inputGeneral'} 
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 500}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={2} md={2} sm={6} xs={12}>
                        <TextValidator
                            name={'tomoDocumento'}
                            value={formData.tomoDocumento}
                            label={'Números de tomos'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["maxNumber:99"]}
                            errorMessages={["Número máximo permitido es el 99"]}
                            type={"number"}
                            onChange={handleChange}
                        />
                    </Grid>
                    
                    <Grid item xl={2} md={2} sm={6} xs={12}>
                        <TextValidator 
                            name={'codigoDocumental'}
                            value={formData.codigoDocumental}
                            label={'Código documenal'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 20}}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <TextValidator 
                            name={'entidadRemitente'}
                            value={formData.entidadRemitente}
                            label={'Entidad remitente'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 200}}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <TextValidator 
                            name={'entidadProductora'}
                            value={formData.entidadProductora}
                            label={'Entidad productora'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 200}}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={6} md={6} sm={12} xs={12}>
                        <TextValidator 
                            name={'resumenDocumento'}
                            value={formData.resumenDocumento}
                            label={'Resumen del documento'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 500}}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={6} md={6} sm={12} xs={12}>
                        <TextValidator 
                            name={'observacion'}
                            value={formData.observacion}
                            label={'Observación'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 500}}
                            onChange={handleChange}
                        />
                    </Grid>

                </Grid>

                { (tipo === 'U' && totalAdjuntoSubido > 0) ?
                    <Grid item md={12} xl={12} sm={12} xs={12} >
                        <Anexos data={digitalizados} eliminar={'false'} cantidadAdjunto={cantidadAdjunto}/>
                    </Grid>
                : null }

                <Grid container spacing = {2} style={{ transition: 'all .2s ease-in-out'}}>
                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='divisionFormulario'>
                            Archivos digitalizados
                        </Box>
                    </Grid>

                    <Grid item md={6} xl={6} sm={12} xs={12}>
                        <Files
                            className='files-dropzone'
                            onChange={(file ) =>{onFilesChange(file, 'archivos') }}
                            onError={onFilesError}
                            accepts={['.pdf', '.PDF']} 
                            multiple
                            maxFiles={totalAdjunto - totalAdjuntoSubido}
                            maxFileSize={2000000}
                            minFileSize={0}
                            clickable
                            dropActiveClassName={"files-dropzone-active"}
                        >
                        <ButtonFilePdf title={"Adicionar archivo digitalizados"} />
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
                            <p>Nota: Recuerde que pueden subir como máximos ({totalAdjunto}) archivos, actualmente ha subido ({totalAdjuntoSubido}) archivos. Solo es permitido el formato .PDF</p>
                        </Box>
                    </Grid>

                </Grid>

                <Grid container direction="row"  justifyContent="right">
                    <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                            startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                        </Button>
                    </Stack>
                </Grid>

            </Card>
        </ValidatorForm>
    )
}
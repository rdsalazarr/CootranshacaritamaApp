import React, {useState, useEffect, Fragment, useRef} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Avatar, FormGroup, FormLabel, FormControlLabel, Checkbox, Card } from '@mui/material';
import {ButtonFileImg, ContentFile} from "../../../layout/files";
import showSimpleSnackbar from '../../../layout/snackBar';
import WarningIcon from '@mui/icons-material/Warning';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import { Editor } from '@tinymce/tinymce-react';


import { DemoContainer, DemoItem } from '@mui/x-date-pickers/internals/demo';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers';
import dayjs from 'dayjs';
import esLocale from 'date-fns/locale/es'; 
import Files from "react-files";

//npm install @mui/x-date-pickers
//npm install date-fns

export default function New({data, tipo}){
    const editorTexto = useRef(null);
  //console.log("Hola ");
   // console.log(tipo)
    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {idCodigoDocumental: data.innocoid, idCodigoDocumentalProceso: data.innoconombre,   idCodigoDocumentalProcesoOficio: data.innocoasunto,                              
                                    dependencia: data.innoconombre,            serie: data.innocoasunto,                       subSerie: data.innococontenido,    
                                    tipoMedio: data.innococontenido,           tipoTramite: data.innocoenviarpiepagina,        tipoDestino: data.innocoenviarcopia, 
                                    fecha: data.innocoenviarpiepagina,         nombreDirigido: data.innocoenviarcopia,         cargoDirigido: data.innococontenido,                
                                    asunto: data.innocoenviarpiepagina,        correo: data.innocoenviarcopia,                 contenido: data.innocoenviarcopia, 
                                    tieneAnexo: data.innocoenviarpiepagina,    nombreAnexo: data.innocoenviarcopia,            tieneCopia: data.innocoenviarcopia, 
                                    nombreCopia: data.innocoenviarpiepagina,   saludo: data.innocoenviarcopia,                 despedida: data.innocoenviarcopia, 
                                    tituloPersona: data.innocoenviarpiepagina, ciudad: data.innocoenviarcopia,                 cargoDestinatario: data.innocoenviarcopia, 
                                    empresa: data.innocoenviarpiepagina,       direccionDestinatario: data.innocoenviarcopia,  telefono: data.innocoenviarcopia, 
                                    responderRadicado: data.innocoenviarcopia,                                     
                                    tipo:tipo
                                    } : {idCodigoDocumental:'000', idCodigoDocumentalProceso:'000', idCodigoDocumentalProcesoOficio:'000',
                                        dependencia: '1',       serie: '6',     subSerie: '6',       tipoMedio: '1',     tipoTramite: '1', 
                                        tipoDestino: '1',       fecha: '',      nombreDirigido: 'ramon salazar',  cargoDirigido: 'CArgo de ramon',  asunto: 'Asunto de prueba',  
                                        correo: '',            contenido: 'abc del contenido',  tieneAnexo: '1',      nombreAnexo: '',    tieneCopia: '1', 
                                        nombreCopia: '',       saludo: '1',     despedida: 1,              tituloPersona: 'ingeniero de sistema',  ciudad: 'Ocaña',    
                                        cargoDestinatario: 'desarrollador', empresa: 'ufpso',     direccionDestinatario: 'calle principal', telefono: '5612333',       responderRadicado: '0',
                                        tipo:tipo
                                });

 

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [tipoDestinos, setTipoDestinos] = useState([]);
    const [tipoMedios, setTipoMedios] = useState([]);
    const [tipoSaludos, setTipoSaludos] = useState([]);
    const [tipoDespedidas, setTipoDespedidas] = useState([]);
    const [dependencias, setDependencias] = useState([]);
    const [fechaActual, setFechaActual] = useState('');
    const [formDataFile, setFormDataFile] = useState({ archivos : []});
    const [totalAdjunto, setTotalAdjunto] = useState(import.meta.env.VITE_TOTAL_FILES_OFICIO);
    const [formDataDependencia, setFormDataDependencia] = useState([]); 
    const [dependenciaMarcada, setDependenciaMarcada] = useState([]); 

    const minDate = dayjs();

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const onFilesChange = (files , nombre) =>  {
        setFormDataFile(prev => ({...prev, [nombre]: files}));
    }

    const handleChangeDependencia = (e) =>{
        let newFormDataDependencia = [...formDataDependencia];
        if(e.target.checked){
            newFormDataDependencia.push({depeid: parseInt(e.target.value)});
        }else{
            //Elimino la posicion
            newFormDataDependencia = formDataDependencia.filter((item) => item.depeid !== parseInt(e.target.value));
        }
        setFormDataDependencia(newFormDataDependencia);
    }

    const removeFIle = (nombre)=>{
        setFormDataFile(prev => ({...prev, archivos: prev.archivos.filter(item => item.name !== nombre)}));
    }
    
    const onFilesError = (error, file) => {  
        let msj = (error.code === 2) ? 'El archivo "'+ file.name + '" es demasiado grande y no se puede subir' : error.message  
        showSimpleSnackbar(msj, 'error');
    }

    const handleSubmit = () =>{
        console.log("enviado el formulario en handleSubmit");
        console.log(formData);
        let newFormData = {...formData};
        newFormData.contenido = editorTexto.current.getContent();
       // setLoader(true);
        
        //console.log("enviado el formulario");

    
        newFormData.dependenciaCopias = dependenciaMarcada; 
        setFormData(newFormData);

        instance.post('/admin/producion/documental/oficio/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
          //  (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
           // (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', nombre: '', asunto: '', contenido: '', piePagina: '1', copia: '0', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/producion/documental/oficio/listar/datos').then(res=>{
            setFechaActual(res.fechaActual);            
            setTipoDestinos(res.tipoDestinos);
            setTipoMedios(res.tipoMedios);
            setTipoSaludos(res.tipoSaludos);
            setTipoDespedidas(res.tipoDespedidas);
            setDependencias(res.dependencias);
            newFormData.fecha = res.fechaActual
            setFormData(newFormData);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    } 

    const handleChangeDate = (date) => {
        setFormData((prevData) => ({...prevData,  fecha: date.format('MM/DD/YYYY'),    }));
    };

    const formatDate = (date) => {
        // Personaliza el formato de la fecha
        return dayjs(date).format('YYYY-MM-DD');
      };

    return (
        <ValidatorForm onSubmit={handleSubmit} >

            <Grid container spacing={2} style={{display: 'flex',  justifyContent: 'space-between'}}>

               <Grid item xl={4} md={4} sm={6} xs={12}>
                    <label className={'labelEditor'}> Fecha del documento </label> 
                    <LocalizationProvider dateAdapter={AdapterDayjs} >
                        <DatePicker 
                            defaultValue={dayjs(fechaActual)}
                            views={['year', 'month', 'day']} 
                            minDate={minDate}
                            locale={esLocale}
                            className={'inputGeneral'} 
                            onChange={handleChangeDate}
                        />
                    </LocalizationProvider>                
                </Grid>             

                <Grid item xl={2} md={2} sm={6} xs={12} className='marginTopNofecha' >
                    <SelectValidator
                        name={'tipoDestino'}
                        value={formData.tipoDestino}
                        label={'Tipo destino'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoDestinos.map(res=>{
                            return <MenuItem value={res.tipdetid} key={res.tipdetid} >{res.tipdetnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid> 

                <Grid item xl={2} md={2} sm={6} xs={12} className='marginTopNofecha'>
                    <SelectValidator
                        name={'tipoMedio'}
                        value={formData.tipoMedio}
                        label={'Tipo de medio'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoMedios.map(res=>{
                            return <MenuItem value={res.tipmedid} key={res.tipmedid} >{res.tipmednombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12} className='marginTopNofecha'>
                    <TextValidator 
                        multiline
                        maxRows={3}
                        name={'correo'}
                        value={formData.correo}
                        label={'Correo'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        onChange={handleChange}
                    />
                </Grid>
            </Grid>

            <Grid container spacing={2} style={{marginTop:'1px'}}>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'tituloPersona'}
                        value={formData.tituloPersona}
                        label={'Título'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={3}
                        name={'nombreDirigido'}
                        value={formData.nombreDirigido}
                        label={'Nombre de la persona que va dirigido'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 2000}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'cargoDirigido'}
                        value={formData.cargoDirigido}
                        label={'Cargo'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}                    
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'saludo'}
                        value={formData.saludo}
                        label={'Saludo'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoSaludos.map(res=>{
                            return <MenuItem value={res.tipsalid} key={res.tipsalid} >{res.tipsalnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'ciudad'}
                        value={formData.ciudad}
                        label={'Ciudad'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={5} md={5} sm={6} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={2}
                        name={'asunto'}
                        value={formData.asunto}
                        label={'Asunto'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item md={12} xl={12} sm={12}>
                    <label className={'labelEditor'}> Contenido </label> 
                    <Editor 
                        onInit={(evt, editor) => editorTexto.current = editor}
                        initialValue = {formData.contenido}
                        init={{
                            language: 'es',
                            height: 400,
                            menubar: false,
                            object_resizing : true,
                            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
                            toolbar: 'undo redo | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat  | link',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
                         }}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <SelectValidator
                        name={'despedida'}
                        value={formData.despedida}
                        label={'Despedida'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoDespedidas.map(res=>{
                            return <MenuItem value={res.tipdesid} key={res.tipdesid} >{res.tipdesnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'tieneAnexo'}
                        value={formData.tieneAnexo}
                        label={'Anexar anexos'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"} >Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'tieneCopia'}
                        value={formData.tieneCopia}
                        label={'Anexar copia'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"} >Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                {(formData.tieneAnexo === '1') ?
                    <Fragment>

                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Anexar documentos al tipo documental si se prosentan 
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <TextValidator 
                                multiline
                                maxRows={2}
                                name={'nombreAnexo'}
                                value={formData.nombreAnexo}
                                label={'Nombre del anexo'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                onChange={handleChange}
                            />
                        </Grid>
                       
                        <Grid item md={5} xl={5} sm={12} xs={12}>
                            <Files
                                className='files-dropzone'
                                onChange={(file ) =>{onFilesChange(file, 'archivos') }}
                                onError={onFilesError}
                                accepts={['.jpg', '.png', '.jpeg', '.doc', '.docx', '.pdf', '.xls', '.xlsx', '.mp3', '.mp4']} 
                                multiple
                                maxFiles={totalAdjunto}
                                maxFileSize={1000000}
                                clickable
                                dropActiveClassName={"files-dropzone-active"}
                            >
                            <ButtonFileImg title={"Adicionar anexos"} />
                            </Files>
                        </Grid>                     

                        <Grid item md={6} xl={6} sm={12} xs={12}>
                            <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                                {formDataFile.archivos.map((file, a) =>{
                                    return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
                                })}
                            </Box>
                        </Grid> 
                        
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className={'msgAlert'}>
                                <Avatar className={'avatar'}> <WarningIcon /></Avatar> 
                                <p>Nota: Recuerde que puede subir como máximo ({totalAdjunto}) archivos, en los formatos tipo .PDF, .DOCX, .DOC, .XLS, XLSX, .JPG y .PNG</p>
                            </Box>
                        </Grid>
                    </Fragment>
                : null}

                {(formData.tieneCopia === '1') ?
                    <Fragment>

                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Anexar copias al tipo documental si se prosentan 
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <TextValidator 
                                multiline
                                maxRows={2}
                                name={'nombreCopia'}
                                value={formData.nombreCopia}
                                label={'Nombre de la copia'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                onChange={handleChange}
                            />
                        </Grid>



                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <FormLabel component="legend">Listado de dependencia para asignar al tipo documental</FormLabel>
                            <FormGroup row name={"dependencias"} 
                                value={formDataDependencia.depeid}
                                onChange={handleChangeDependencia}
                                >
                                {dependencias.map(res=>{
                                    const marcado  = dependenciaMarcada.find(resul => resul.depeid === res.depeid);
                                    const checkbox = (marcado !== undefined) ? <Checkbox color="secondary" defaultChecked /> : <Checkbox color="secondary"  />;  
                                
                                    const frmCheckbox = <Grid item md={4} xl={4} sm={6} key={res.depenombre} >
                                                            <FormControlLabel value={res.depeid} label={res.depenombre} control={checkbox} />
                                                        </Grid>
                                    return frmCheckbox;
                                })}
                            </FormGroup>
                        </Grid>
                    </Fragment>
                : null}

            </Grid>            

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {(tipo=== 'I') ? "Guardar" : "Actualizar"}
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
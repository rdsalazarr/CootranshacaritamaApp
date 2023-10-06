import React, {useState, useEffect, useRef} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Table, TableHead, TableBody, TableRow, TableCell } from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import { Editor } from '@tinymce/tinymce-react';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers';
import "/resources/scss/fechaDatePicker.scss";
import esLocale from 'date-fns/locale/es'; 
import dayjs from 'dayjs';

export default function New({id, area, tipo, ruta}){ 

    const editorTexto = useRef(null);
    const [formData, setFormData] = useState( 
                                {idCD: (tipo !== 'I') ? id :'000', idCDP:'000', idCDPC:'000',
                                        dependencia: (tipo === 'I') ? area.depeid: '',   serie: '2',  subSerie: '2',  tipoMedio: '',  tipoTramite: '1', 
                                        tipoDestino: '',       fecha: '',      nombreDirigido: '',    correo: '',     contenidoInicial: '',    contenido: '',  
                                        tituloDocumento: (tipo === 'I') ? 'EL SUSCRITO JEFE DE LA DEPENDENCIA DE '+area.depenombre.toUpperCase() : '',  tipoPersona: '',  tipo:tipo
                                }); 

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [fechaActual, setFechaActual] = useState('');
    const [tipoDestinos, setTipoDestinos] = useState([]);
    const [tipoMedios, setTipoMedios] = useState([]);
    const [tipoPersonaDocumentales, setTipoPersonaDocumentales] = useState([]);
    const [personas, setPersonas] = useState([]);
    const [cargoLaborales, setCargoLaborales] = useState([]);  
    const [firmaPersona, setFirmaPersona] = useState([{identificador:'', persona:'',  cargo: '', estado: 'I'}]);
    const [fechaMinima, setFechaMinima] = useState(dayjs());

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleInputChange = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}));
    }

    const handleChangeDate = (date) => {
        setFormData((prevData) => ({...prevData, fecha: date.format('YYYY-MM-DD')}));
    }

    const handleChangeFirmaPersona = (e, index) =>{
        let newFirmaPersona= [...firmaPersona];
        newFirmaPersona[index][e.target.name] = e.target.value; 
        setFirmaPersona(newFirmaPersona);
    }    

    const handleSubmit = () =>{ 

        if(formData.tipoMedio !== 1 && formData.correo === ''){
            showSimpleSnackbar("Debe ingresar el correo", 'error');
            return
        }

        //En el momento de enviar la peticion no muestra los cambio en el tyminice
        let formDataCopia                 = {...formData};
        formDataCopia.contenidoAdicional = editorTexto.current.getContent();

        let newFormData                  = {...formData};
        newFormData.contenido            = editorTexto.current.getContent()
        newFormData.firmaPersonas        = firmaPersona;

        setLoader(true);
        setFormData(formDataCopia);
        let rutaSalve    = (ruta === 'P') ? '/admin/producion/documental/certificado/salve' : '/admin/firmar/documento/certificado/salve';
        instance.post(rutaSalve, newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({idCD: (tipo !== 'I') ? id :'000', idCDP:'000', idCDPO:'000',
                                                                    dependencia: (tipo === 'I') ? area.depeid: '',   serie: '2',      subSerie: '2',      tipoMedio: '',        tipoTramite: '1', 
                                                                    tipoDestino: '',       fecha: fechaActual,      nombreDirigido: '',        correo: '',         contenidoInicial: '', contenido: '',
                                                                    tipoPersona: '',       tituloDocumento: formData.tituloDocumento, tipo:tipo}) : null;
            
            (formData.tipo === 'I' && res.success) ? setFirmaPersona([{identificador:'', persona:'',  cargo: '', estado: 'I'}]) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData}
        let rutaData    = (ruta === 'P') ? '/admin/producion/documental/certificado/listar/datos' : '/admin/firmar/documento/editar/documento';
        instance.post(rutaData, {id: id, tipo: tipo, tipoDocumental: 'B'}).then(res=>{
            (tipo === 'I') ? setFechaActual(res.fechaActual): null;
            (tipo === 'I') ? setFechaMinima(dayjs(res.fechaActual, 'YYYY-MM-DD')): null;
            setTipoDestinos(res.tipoDestinos);
            setTipoMedios(res.tipoMedios);
            setTipoPersonaDocumentales(res.tipoPersonaDocumentales);
            setPersonas(res.personas);
            setCargoLaborales(res.cargoLaborales);
            newFormData.fecha = res.fechaActual;

            if(tipo === 'U'){
                let tpDocumental             = res.data;
                let firmasDocumento          = res.firmasDocumento;  
     
                newFormData.idCD             = tpDocumental.coddocid;
                newFormData.idCDP            = tpDocumental.codoprid;
                newFormData.idCDPC           = tpDocumental.id;
                newFormData.dependencia      = tpDocumental.depeid;
                newFormData.serie            = tpDocumental.serdocid;
                newFormData.subSerie         = tpDocumental.susedoid;
                newFormData.tipoMedio        = tpDocumental.tipmedid;
                newFormData.tipoTramite      = tpDocumental.tiptraid;
                newFormData.tipoDestino      = tpDocumental.tipdetid;
                newFormData.fecha            = tpDocumental.codoprfecha;
                newFormData.nombreDirigido   = tpDocumental.codoprnombredirigido;
                newFormData.correo           = (tpDocumental.codoprcorreo !== null) ? tpDocumental.codoprcorreo : '';
                newFormData.contenido        = (tpDocumental.codoprcontenido !== null) ? tpDocumental.codoprcontenido : ''; 
                newFormData.contenidoInicial = tpDocumental.codopccontenidoinicial;
                newFormData.tituloDocumento  = tpDocumental.codopctitulo; 
                newFormData.tipoPersona      = tpDocumental.tipedoid; 

                let newFirmasDocumento = [];
                firmasDocumento.forEach(function(frm){
                    newFirmasDocumento.push({
                        identificador: frm.codopfid,
                        persona: frm.persid,
                        cargo: frm.carlabid,
                        estado: 'U'
                    });
                });

                setFirmaPersona(newFirmasDocumento);
                setFechaActual(tpDocumental.codoprfecha);
                setFechaMinima(dayjs(tpDocumental.codoprfecha, 'YYYY-MM-DD'));
            }
            setFormData(newFormData);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >

            <Grid container spacing={2} style={{display: 'flex',  justifyContent: 'space-between'}}>

               <Grid item xl={4} md={4} sm={6} xs={12}>
                    <LocalizationProvider dateAdapter={AdapterDayjs} >
                        <DatePicker
                            label="Fecha del documento"
                            defaultValue={dayjs(fechaActual)}
                            views={['year', 'month', 'day']} 
                            minDate={fechaMinima}
                            locale={esLocale}
                            className={'inputGeneral'} 
                            onChange={handleChangeDate}
                        />
                    </LocalizationProvider>
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12} >
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

                <Grid item xl={2} md={2} sm={6} xs={12}>
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

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'correo'}
                        value={formData.correo}
                        label={'Correo'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={['isEmail']}
                        errorMessages={['Correo no válido']}
                        onChange={handleChange}
                        type={"email"}
                    />
                </Grid>
            </Grid>

            <Grid container spacing={2} style={{marginTop:'1px'}}>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <TextValidator 
                        name={'tituloDocumento'}
                        value={formData.tituloDocumento}
                        label={'Título del certificado'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 200}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>
 
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'tipoPersona'}
                        value={formData.tipoPersona}
                        label={'Tipo persona documental'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoPersonaDocumentales.map(res=>{
                            return <MenuItem value={res.tipedoid} key={res.tipedoid} >{res.tipedonombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={9} md={9} sm={6} xs={12}>
                    <TextValidator
                        name={'nombreDirigido'}
                        value={formData.nombreDirigido}
                        label={'Nombre de la persona que va dirigido'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleInputChange}
                    />
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <TextValidator
                        multiline
                        maxRows={3}
                        name={'contenidoInicial'}
                        value={formData.contenidoInicial}
                        label={'Contenido'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 1000}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <label className={'labelEditor'}> Contenido adicional</label> 
                    <Editor 
                        onInit={(evt, editor) => editorTexto.current = editor}
                        initialValue = {formData.contenido}
                        init={{
                            language: 'es',
                            height: 400,
                            menubar: false,
                            object_resizing : true,
                            table_responsive_width: true,
                            browser_spellcheck: true,
                            spellchecker_language: 'es',
                            spellchecker_wordchar_pattern: /[^\s,\.]+/g ,
                            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
                            toolbar: 'undo redo | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat  | link | table',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
                         }}
                    />
                </Grid> 

                <Grid item md={12} xl={12} sm={12}>
                    <Box className='frmDivision'>Adicionar persona que firma el tipo documental</Box>
                    <Table key={'tableFirmaPersona'}  className={'tableAdicional'} style={{marginTop: '5px'}} >
                        <TableHead>
                            <TableRow>
                                <TableCell style={{width: '60%'}}>Nombre de la persona</TableCell>
                                <TableCell style={{width: '40%'}}>Cargo </TableCell> 
                            </TableRow>
                        </TableHead>
                        <TableBody>

                        { firmaPersona.map((frmPers, a) => { 

                            return(
                                <TableRow key={'rowA-' +a} className={(frmPers.estado == 'D')? 'tachado': null}>
                                    <TableCell>
                                        <SelectValidator
                                            name={'persona'}
                                            value={frmPers['persona']}
                                            label={'Nombre de la persona'}
                                            className={'inputGeneral'} 
                                            variant={"standard"} 
                                            inputProps={{autoComplete: 'off'}}
                                            validators={["required"]}
                                            errorMessages={["Campo obligatorio"]}
                                            onChange={(e) => {handleChangeFirmaPersona(e, a)}}
                                        >
                                        <MenuItem value={""}>Seleccione</MenuItem>
                                        {personas.map(res=>{
                                            return <MenuItem value={res.persid} key={res.persid} >{res.nombrePersona}</MenuItem>
                                        })}
                                        </SelectValidator>
                                    </TableCell>

                                    <TableCell>
                                        <SelectValidator
                                            name={'cargo'}
                                            value={frmPers['cargo']}
                                            label={'Cargo laboral'}
                                            className={'inputGeneral'} 
                                            variant={"standard"} 
                                            inputProps={{autoComplete: 'off'}}
                                            validators={["required"]}
                                            errorMessages={["Campo obligatorio"]}
                                            onChange={(e) => {handleChangeFirmaPersona(e, a)}}
                                        >
                                        <MenuItem value={""}>Seleccione</MenuItem>
                                        {cargoLaborales.map((res, i) =>{
                                            return <MenuItem value={res.carlabid} key={res.carlabid} >{res.carlabnombre}</MenuItem>
                                        })}
                                        </SelectValidator>
                                    </TableCell>
                                 
                                 </TableRow>
                                );
                            })
                        }

                        </TableBody>
                    </Table>
                </Grid>

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
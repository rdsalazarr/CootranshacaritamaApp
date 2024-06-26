import React, {useState, useEffect, useRef} from 'react';
import { Button, Grid, MenuItem, Box, Icon,Table, TableHead, TableBody, TableRow, TableCell, Typography, Card } from '@mui/material';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {ModalDefaultAuto} from '../../../layout/modal';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import { Editor } from '@tinymce/tinymce-react';
import VisualizarPdf from '../visualizarPdf';

import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers';
import "/resources/scss/fechaDatePicker.scss";
import esLocale from 'dayjs/locale/es';
import dayjs from 'dayjs';

export default function New({id, area, tipo, ruta, volver, mensaje}){
    const editorTexto = useRef(null);
    const [formData, setFormData] = useState(
                                {idCD: (tipo !== 'I') ? id :'000', idCDP:'000', idCDPC:'000',
                                        dependencia: (tipo === 'I') ? area.depeid: '',   serie: '4', subSerie: '4', tipoMedio: '',    tipoTramite: '1', 
                                        tipoDestino: '1',  fecha: '',              tipoCitacion: '',  correo: '',    horaInicial: '', lugar: '',
                                        contenido: '',     tipo:tipo
                                });

    const [firmaPersona, setFirmaPersona] = useState([{identificador:'', persona:'',  cargo: '', estado: 'I'}]);
    const [tipoCitaciones, setTipoCitaciones] = useState([]);
    const [cargoLaborales, setCargoLaborales] = useState([]);
    const [firmaInvitados, setFirmaInvitados] = useState([]);
    const [fechaMinima, setFechaMinima] = useState(dayjs());
    const [idDocumento, setIdDocumento] = useState(null);
    const [abrirModal, setAbrirModal] = useState(false);
    const [fechaActual, setFechaActual] = useState('');
    const [tipoMedios, setTipoMedios] = useState([]);
    const [personas, setPersonas] = useState([]);
    const [loader, setLoader] = useState(false); 

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeDate = (date) => {
        setFormData((prevData) => ({...prevData, fecha: date.format('YYYY-MM-DD')}));
    }

    const handleChangeFirmaPersona = (e, index) =>{
        let newFirmaPersona = [...firmaPersona];
        newFirmaPersona[index][e.target.name] = e.target.value; 
        setFirmaPersona(newFirmaPersona);
    }

    const handleChangeFirmaInvitado = (e, index) =>{
        let newFirmaInvitados = [...firmaInvitados];
        newFirmaInvitados[index][e.target.name] = e.target.value; 
        setFirmaInvitados(newFirmaInvitados);
    }

    const handleSubmit = () =>{
        if(formData.tipoMedio !== 1 && formData.correo === ''){
            showSimpleSnackbar("Debe ingresar el correo", 'error');
            return
        }

        if(!validateCorreos(formData.correo) && formData.correo !== ''){
            showSimpleSnackbar("El campo de correo electrónico contiene uno o más correos que no tienen una estructura válida", 'error');
            return
        }

        if(editorTexto.current.getContent() === ''){
            showSimpleSnackbar("Debe ingresar el contenido del documento", 'error');
            return
        }

        //En el momento de enviar la peticion no muestra los cambio en el tyminice
        let formDataCopia       = {...formData};
        formDataCopia.contenido = editorTexto.current.getContent();

        let newFormData            = {...formData};
        newFormData.contenido      = editorTexto.current.getContent()
        newFormData.firmaPersonas  = firmaPersona;
        newFormData.firmaInvitados = firmaInvitados;
        setLoader(true);
        setFormData(formDataCopia);
        let rutaSalve    = (ruta === 'P') ? '/admin/producion/documental/citacion/salve' : '/admin/firmar/documento/citacion/salve';
        instance.post(rutaSalve, newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo === 'I' && res.success) ? setFormData({idCD: (tipo !== 'I') ? id :'000', idCDP:'000', idCDPC:'000',
                                                                    dependencia: (tipo === 'I') ? area.depeid: '', serie: '4',      subSerie: '4', tipoMedio: '',    tipoTramite: '1', 
                                                                    tipoDestino: '1',  fecha: fechaActual,         tipocitacion: '', correo: '',    horaInicial: '', lugar: '',   contenido: '', tipo:tipo}) : null;

            (formData.tipo === 'I' && res.success) ? setFirmaPersona([{identificador:'', persona:'',  cargo: '', estado: 'I'}]) : null;
            (res.success && ruta === 'P') ? (setIdDocumento(res.idDocumento), setAbrirModal(true) ) : null;
            (formData.tipo === 'I' && res.success) ? setFirmaInvitados([]) : null;
            setLoader(false);
        })
    }

    const adicionarFilaFirmaPersona = () =>{
        let newFirmaPersona = [...firmaPersona];
        newFirmaPersona.push({identificador:'', persona:'',  cargo: '',  estado: 'I'});
        setFirmaPersona(newFirmaPersona);
    }  

    const eliminarFirmaPersona = (id) =>{
        let newDatosFirmaPersona = []; 
        firmaPersona.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newDatosFirmaPersona.push({ identificador:res.identificador, persona: res.persona, cargo:res.cargo, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newDatosFirmaPersona.push({identificador:res.identificador,  persona: res.persona, cargo:res.cargo, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newDatosFirmaPersona.push({identificador:res.identificador, persona: res.persona, cargo:res.cargo, estado:res.estado});
            }else{
                if(i != id){
                    newDatosFirmaPersona.push({identificador:res.identificador, persona: res.persona, cargo:res.cargo, estado: 'I' });
                }
            }
        })
        setFirmaPersona(newDatosFirmaPersona);
    }

    const adicionarFilaFirmaInvitado = () =>{
        let newFirmaInvitados = [...firmaInvitados];
        newFirmaInvitados.push({identificador:'', persona:'',  cargo: '',  estado: 'I'});
        setFirmaInvitados(newFirmaInvitados);
    }

    const eliminarFirmaInvitado = (id) =>{
        let newDatosFirmaInvitados = []; 
        firmaInvitados.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newDatosFirmaInvitados.push({ identificador:res.identificador, persona: res.persona, cargo:res.cargo, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newDatosFirmaInvitados.push({identificador:res.identificador,  persona: res.persona, cargo:res.cargo, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newDatosFirmaInvitados.push({identificador:res.identificador, persona: res.persona, cargo:res.cargo, estado:res.estado});
            }else{
                if(i != id){
                    newDatosFirmaInvitados.push({identificador:res.identificador, persona: res.persona, cargo:res.cargo, estado: 'I' });
                }
            }
        })
        setFirmaInvitados(newDatosFirmaInvitados);
    }

    const validateCorreos = (cadena) => {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const correos = cadena.split(',').map(correo => correo.trim());
        for (const correo of correos) {
            if (!emailRegex.test(correo)) {
                return false;
            }
        }
        return true;
    }

    const validateHora = (value) => {
        const regex = /^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/; // HH:mm format
        return regex.test(value);
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData}
        let rutaData    = (ruta === 'P') ? '/admin/producion/documental/citacion/listar/datos' : '/admin/firmar/documento/editar/documento';
        instance.post(rutaData, {id: id, tipo: tipo, tipoDocumental: 'H'}).then(res=>{
            (tipo === 'I') ? setFechaActual(res.fechaActual): null;
            (tipo === 'I') ? setFechaMinima(dayjs(res.fechaActual, 'YYYY-MM-DD')): null;
            setTipoMedios(res.tipoMedios);
            setTipoCitaciones(res.tipoCitaciones);
            setPersonas(res.personas);
            setCargoLaborales(res.cargoLaborales);
            newFormData.fecha = res.fechaActual;

            if(tipo === 'U'){
                let tpDocumental              = res.data;
                let firmasDocumento           = res.firmasDocumento;
                let firmaInvitados            = res.firmaInvitados;
  
                newFormData.idCD              = tpDocumental.coddocid;
                newFormData.idCDP             = tpDocumental.codoprid;
                newFormData.idCDPC            = tpDocumental.id;
                newFormData.dependencia       = tpDocumental.depeid;
                newFormData.serie             = tpDocumental.serdocid;
                newFormData.subSerie          = tpDocumental.susedoid;
                newFormData.tipoMedio         = tpDocumental.tipmedid;
                newFormData.tipoTramite       = tpDocumental.tiptraid;
                newFormData.tipoDestino       = tpDocumental.tipdetid;
                newFormData.fecha             = tpDocumental.codoprfecha;
                newFormData.correo            = (tpDocumental.codoprcorreo !== null) ? tpDocumental.codoprcorreo : '';
                newFormData.contenido         = tpDocumental.codoprcontenido;

                newFormData.tipoCitacion      = tpDocumental.tipactid;
                newFormData.horaInicial       = tpDocumental.codopthora;
                newFormData.lugar             = tpDocumental.codoptlugar;

                let newFirmasDocumento = [];
                firmasDocumento.forEach(function(frm){
                    newFirmasDocumento.push({
                        identificador: frm.codopfid,
                        persona: frm.persid,
                        cargo: frm.carlabid,
                        estado: 'U'
                    });
                });

                let newFirmasInvitado = [];
                firmaInvitados.forEach(function(frm){
                    newFirmasInvitado.push({
                        identificador: frm.codopfid,
                        persona: frm.persid,
                        cargo: frm.carlabid,
                        estado: 'U'
                    });
                });

                setFirmaPersona(newFirmasDocumento);
                setFirmaInvitados(newFirmasInvitado);
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
        <Box>
            <ValidatorForm onSubmit={handleSubmit} >
                <Box>
                    <Typography component={'h1'} className={'titleProductorDocumento'}>{mensaje}</Typography>
                </Box>

                <Card style={{padding:'5px',marginTop: '5px'}}>

                    <Grid container spacing={2} style={{display: 'flex',  justifyContent: 'space-between'}}>

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale={esLocale} >
                                <DatePicker
                                    label="Fecha del documento"
                                    defaultValue={dayjs(fechaActual)}
                                    views={['year', 'month', 'day']}
                                    minDate={fechaMinima}
                                    className={'inputGeneral'} 
                                    onChange={handleChangeDate}
                                />
                            </LocalizationProvider>
                        </Grid>

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoCitacion'}
                                value={formData.tipoCitacion}
                                label={'Tipo de citacion'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoCitaciones.map(res=>{
                                    return <MenuItem value={res.tipactid} key={res.tipactid} >{res.tipactnombre}</MenuItem>
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
                                multiline
                                maxRows={3}
                                name={'correo'}
                                value={formData.correo}
                                label={'Correo (Si desea enviar varios correos sepárelos con una coma ",")'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 1000}}
                                onChange={handleChange}
                                onBlur={() => {
                                    if (formData.correo && !validateCorreos(formData.correo)) {
                                        showSimpleSnackbar("El campo de correo electrónico contiene uno o más correos que no tienen una estructura válida", 'error');
                                    }
                                }}
                                disabled={(formData.tipoMedio === 1) ? true : false}
                            />
                        </Grid>
                    </Grid>

                    <Grid container spacing={2} style={{marginTop:'1px'}}>

                        <Grid item xl={2} md={2} sm={3} xs={12}>
                            <TextValidator
                                name={'horaInicial'}
                                value={formData.horaInicial}
                                label={'Hora de inicio'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 5}}
                                validators={['required']}
                                errorMessages={['Campo obligatorio']}
                                onChange={handleChange}
                                onBlur={() => {
                                    if (formData.horaInicial && !validateHora(formData.horaInicial)) {
                                        showSimpleSnackbar("El formato de la hora inicio no es permitido", 'error');
                                    }
                                }}
                            />
                        </Grid>

                        <Grid item xl={10} md={10} sm={9} xs={12}>
                            <TextValidator
                                name={'lugar'}
                                value={formData.lugar}
                                label={'Lugar'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 200}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <label className={'labelEditor'}> Contenido </label> 
                            <Editor 
                                onInit={(evt, editor) => editorTexto.current = editor}
                                initialValue = {formData.contenido}
                                init={{
                                    language: 'es',
                                    height: 400,
                                    object_resizing : true,
                                    browser_spellcheck: true,
                                    spellchecker_language: 'es', 
                                    spellchecker_wordchar_pattern: /[^\s,\.]+/g ,
                                    menubar: 'file edit view insert format tools table',
                                    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
                                    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor | link',
                                    menu:{
                                        file: {title: 'File', items: 'newdocument'},
                                        edit: {title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall searchreplace'},
                                        view: {title: 'View', items: 'visualaid  | fullscreen'},
                                        insert: {title: 'Insert', items: 'link  | hr | inserttable'},
                                        format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript'},
                                        tools: {title: 'tools', items: 'wordcount'},
                                        table: {title: 'Table', items: 'inserttable tableprops deletetable | cell row column'}
                                    },
                                }}
                            />
                        </Grid>

                        <Grid item md={12} xl={12} sm={12}>
                            <Box className='frmDivision'>Adicionar personas que firma el tipo documental</Box>
                            <Table key={'tableFirmaPersona'}  className={'tableAdicional'} style={{marginTop: '5px'}} >
                                <TableHead>
                                    <TableRow>
                                        <TableCell style={{width: '50%'}}>Nombre de la persona</TableCell>
                                        <TableCell style={{width: '40%'}}>Cargo </TableCell> 
                                        <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
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

                                            <TableCell className='cellCenter'>
                                                {(a !== 0)?
                                                <Icon key={'iconDelete'+a} className={'icon top red'}
                                                        onClick={() => {eliminarFirmaPersona(a);}} title={'Eliminar registro'}
                                                    >clear</Icon>
                                                    : <Icon key={'iconAdd'} className={'icon top green'} title={'Adicionar firma'}
                                                        onClick={() => {adicionarFilaFirmaPersona()}}
                                                    >add</Icon>
                                                }
                                            </TableCell>
                                        </TableRow>
                                        );
                                    })
                                }

                                </TableBody>
                            </Table>
                        </Grid>
                        
                        <Grid item md={12} xl={12} sm={12}>
                            <Box className='frmDivision'>Adicionar invitados que firma el tipo documental</Box>
                            <Box className={'iconAdd'}>
                                <Icon key={'iconAddIvitados'} className={'icon top green'}
                                    onClick={() => {adicionarFilaFirmaInvitado()}}
                                >add</Icon>
                            </Box>

                            <Table key={'tableFirmaInvitados'}  className={'tableAdicional'} style={{marginTop: '5px'}} >
                                <TableHead>
                                    <TableRow>
                                        <TableCell style={{width: '50%'}}>Nombre de la persona</TableCell>
                                        <TableCell style={{width: '40%'}}>Cargo </TableCell> 
                                        <TableCell style={{width: '10%'}} className='cellCenter'>Acción</TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>
                                { firmaInvitados.map((frmInv, a) => { 

                                    return(
                                        <TableRow key={'rowA-' +a} className={(frmInv.estado == 'D')? 'tachado': null}>
                                            <TableCell>
                                                <SelectValidator
                                                    name={'persona'}
                                                    value={frmInv['persona']}
                                                    label={'Nombre de la persona'}
                                                    className={'inputGeneral'} 
                                                    variant={"standard"} 
                                                    inputProps={{autoComplete: 'off'}}
                                                    validators={["required"]}
                                                    errorMessages={["Campo obligatorio"]}
                                                    onChange={(e) => {handleChangeFirmaInvitado(e, a)}}
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
                                                    value={frmInv['cargo']}
                                                    label={'Cargo laboral'}
                                                    className={'inputGeneral'} 
                                                    variant={"standard"} 
                                                    inputProps={{autoComplete: 'off'}}
                                                    validators={["required"]}
                                                    errorMessages={["Campo obligatorio"]}
                                                    onChange={(e) => {handleChangeFirmaInvitado(e, a)}}
                                                >
                                                <MenuItem value={""}>Seleccione</MenuItem>
                                                {cargoLaborales.map((res, i) =>{
                                                    return <MenuItem value={res.carlabid} key={res.carlabid} >{res.carlabnombre}</MenuItem>
                                                })}
                                                </SelectValidator>
                                            </TableCell>

                                            <TableCell className='cellCenter'>
                                                <Icon key={'iconDelete'+a} className={'icon top red'}
                                                    onClick={() => {eliminarFirmaInvitado(a);}} title={'Eliminar registro'}
                                                >clear</Icon>
                                            </TableCell>
                                        </TableRow>
                                        );
                                    })
                                }
                                </TableBody>
                            </Table>
                        </Grid>
                    </Grid>

                    <Grid container spacing={2}>
                        <Grid item xl={3} md={3} sm={4} xs={12}>
                            {(ruta === 'P') ? 
                                <Button type={"button"} className={'modalBtn'} onClick={() => {volver()}}
                                    startIcon={<ArrowBackIcon />}> Volver
                                </Button>
                            : null }
                        </Grid>
                        <Grid item xl={9} md={9} sm={8} xs={12} style={{textAlign:'right'}}>
                            <Button type={"submit"} className={'modalBtn'}
                                startIcon={<SaveIcon />}> {(tipo=== 'I') ? "Guardar" : "Actualizar"}
                            </Button>
                        </Grid>
                    </Grid>
                </Card>
            </ValidatorForm>

            <ModalDefaultAuto
                title   = {'Visualizar el tipo documental en formato PDF'}
                content = {<VisualizarPdf id={idDocumento} ruta={'citacion'} />}
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'mediumFlot' 
                abrir   = {abrirModal}
            />

        </Box>
    );
}
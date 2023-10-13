import React, {useState, useEffect, Fragment, useRef} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Icon,Table, TableHead, TableBody, TableRow, TableCell } from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import { Editor } from '@tinymce/tinymce-react';

import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers';
import "/resources/scss/fechaDatePicker.scss";
import esLocale from 'dayjs/locale/es';
import dayjs from 'dayjs';
import 'dayjs/locale/es';

export default function New({id, area, tipo, ruta}){ 
    const editorTexto = useRef(null);
    const quorum = "Se llama a lista y se comprueba que existe quorum reglamentario para deliberar y sesionar";
    const [formData, setFormData] = useState( 
                                {idCD: (tipo !== 'I') ? id :'000', idCDP:'000', idCDPA:'000',
                                        dependencia: (tipo === 'I') ? area.depeid: '',   serie: '1',     subSerie: '1',         tipoMedio: '',    tipoTramite: '1', 
                                        tipoDestino: '1',  fecha: '',              tipoActa: '',          correo: '',           horaInicial: '',  horaFinal: '',  
                                        lugar: '',         convocatoria: '0',      asistentes: '',        invitados: '',        ausentes: '',     ordenDia: '', 
                                        contenido: '',     convocatoriaLugar: '',  convocatoriaFecha: '', convocatoriaHora: '', quorum: quorum,   tipo:tipo
                                }); 

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [fechaActual, setFechaActual] = useState('');
    const [tipoMedios, setTipoMedios] = useState([]);
    const [tipoActas, setTipoActas] = useState([]);
    const [personas, setPersonas] = useState([]);
    const [cargoLaborales, setCargoLaborales] = useState([]); 
    const [firmaPersona, setFirmaPersona] = useState([{identificador:'', persona:'',  cargo: '', estado: 'I'}]);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
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

        setLoader(true);
        setFormData(formDataCopia);
        let rutaSalve    = (ruta === 'P') ? '/admin/producion/documental/acta/salve' : '/admin/firmar/documento/acta/salve';
        instance.post(rutaSalve, newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({idCD: (tipo !== 'I') ? id :'000', idCDP:'000', idCDPA:'000',
                                                                    dependencia: (tipo === 'I') ? area.depeid: '',   serie: '1',      subSerie: '1',       tipoMedio: '',    tipoTramite: '1', 
                                                                    tipoDestino: '1',  fecha: fechaActual,    tipoActa: '',          correo: '',           horaInicial: '', horaFinal: '',  
                                                                    lugar: '',         convocatoria: '0',      asistentes: '',       invitados: '',        ausentes: '',    ordenDia: '', 
                                                                    contenido: '',     convocatoriaLugar: '',  convocatoriaFecha: '', convocatoriaHora: '', quorum: quorum,  tipo:tipo}) : null;
            
            (formData.tipo === 'I' && res.success) ? setFirmaPersona([{identificador:'', persona:'',  cargo: '', estado: 'I'}]) : null;
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
        let rutaData    = (ruta === 'P') ? '/admin/producion/documental/acta/listar/datos' : '/admin/firmar/documento/editar/documento';
        instance.post(rutaData, {id: id, tipo: tipo, tipoDocumental: 'A'}).then(res=>{
            (tipo === 'I') ? setFechaActual(res.fechaActual): null;
            setTipoMedios(res.tipoMedios);
            setTipoActas(res.tipoActas);
            setPersonas(res.personas);
            setCargoLaborales(res.cargoLaborales);
            newFormData.fecha = res.fechaActual;

            if(tipo === 'U'){
                let tpDocumental              = res.data;
                let firmasDocumento           = res.firmasDocumento;
  
                newFormData.idCD              = tpDocumental.coddocid;
                newFormData.idCDP             = tpDocumental.codoprid;
                newFormData.idCDPA            = tpDocumental.id;
                newFormData.dependencia       = tpDocumental.depeid;
                newFormData.serie             = tpDocumental.serdocid;
                newFormData.subSerie          = tpDocumental.susedoid;
                newFormData.tipoMedio         = tpDocumental.tipmedid;
                newFormData.tipoTramite       = tpDocumental.tiptraid;
                newFormData.tipoDestino       = tpDocumental.tipdetid;
                newFormData.fecha             = tpDocumental.codoprfecha;
                newFormData.correo            = (tpDocumental.codoprcorreo !== null) ? tpDocumental.codoprcorreo : '';
                newFormData.contenido         = tpDocumental.codoprcontenido;

                newFormData.tipoActa          = tpDocumental.tipactid;
                newFormData.horaInicial       = tpDocumental.codopahorainicio;
                newFormData.horaFinal         = tpDocumental.codopahorafinal;
                newFormData.lugar             = tpDocumental.codopalugar;
                newFormData.convocatoria      = tpDocumental.codopaconvocatoria;
                newFormData.asistentes        = tpDocumental.codoprnombredirigido;
                newFormData.invitados         = (tpDocumental.codopainvitado !== null) ? tpDocumental.codopainvitado : '';
                newFormData.ausentes          = (tpDocumental.codopaausente !== null) ? tpDocumental.codopaausente : '';
                newFormData.ordenDia          = tpDocumental.codopaordendeldia;
                newFormData.quorum            = tpDocumental.codopaquorum;
                newFormData.convocatoriaLugar = (tpDocumental.codopaconvocatorialugar !== null) ? tpDocumental.codopaconvocatorialugar : '';
                newFormData.convocatoriaFecha = (tpDocumental.codopaconvocatoriafecha !== null) ? tpDocumental.codopaconvocatoriafecha : '';
                newFormData.convocatoriaHora  = (tpDocumental.codopaconvocatoriahora !== null) ? tpDocumental.codopaconvocatoriahora : '';

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
                    <LocalizationProvider dateAdapter={AdapterDayjs} adapterLocale={esLocale} >
                        <DatePicker
                            label="Fecha del documento"
                            defaultValue={dayjs(fechaActual)}
                            views={['year', 'month', 'day']}
                            className={'inputGeneral'} 
                            onChange={handleChangeDate}
                        />
                    </LocalizationProvider>
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <SelectValidator
                        name={'tipoActa'}
                        value={formData.tipoActa}
                        label={'Tipo de acta'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoActas.map(res=>{
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

                <Grid item xl={2} md={2} sm={6} xs={12}>
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

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <TextValidator 
                        name={'horaFinal'}
                        value={formData.horaFinal}
                        label={'Hora final'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 5}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        onBlur={() => {
                            if (formData.horaInicial && !validateHora(formData.horaInicial)) {
                                showSimpleSnackbar("El formato de la hora final no es permitido", 'error');
                            }
                        }}
                    />
                </Grid>

                <Grid item xl={5} md={5} sm={6} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={3}
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

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'convocatoria'}
                        value={formData.convocatoria}
                        label={'¿Requiere convocatoria?'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"}>Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={5}
                        name={'asistentes'}
                        value={formData.asistentes}
                        label={'Asistentes (Por cada uno utilice un enter)'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 4000}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>
                
                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={5}
                        name={'invitados'}
                        value={formData.invitados}
                        label={'Invitados (Por cada uno utilice un enter)'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 4000}}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={5}
                        name={'ausentes'}
                        value={formData.ausentes}
                        label={'Ausentes (Por cada uno utilice un enter)'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 4000}}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={5}
                        name={'ordenDia'}
                        value={formData.ordenDia}
                        label={'Orden del día'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 4000}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <TextValidator
                        name={'quorum'}
                        value={formData.quorum}
                        label={'Quorum'}
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

                {(formData.convocatoria === 1 ) ?
                    <Fragment>
                        <Grid item xl={4} md={4} sm={6} xs={12}> 
                            <TextValidator 
                                name={'convocatoriaLugar'}
                                value={formData.convocatoriaLugar}
                                label={'Lugar de la convocatoria '}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 100}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator 
                                name={'convocatoriaFecha'}
                                value={formData.convocatoriaFecha}
                                label={'Fecha de la convocatoria '}
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

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator 
                                name={'convocatoriaHora'}
                                value={formData.convocatoriaHora}
                                label={'Hora de la convocatoria '}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 5}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                                onBlur={() => {
                                    if (formData.horaInicial && !validateHora(formData.horaInicial)) {
                                        showSimpleSnackbar("El formato de la hora convocatoria no es permitido", 'error');
                                    }
                                }}
                            />
                        </Grid>
                    </Fragment>
                : null}

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
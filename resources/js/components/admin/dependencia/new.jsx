import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Icon,Table, TableHead, TableBody, TableRow, TableCell, Box  } from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import AddIcon from '@mui/icons-material/Add';
import instance from '../../layout/instance';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {id:data.depeid, codigo:data.depecodigo, sigla: data.depesigla, nombre: data.depenombre, correo: data.depecorreo, jefeDependencia: data.depejefeid, estado: data.depeactiva, tipo:tipo 
                                    } : {id:'000', codigo:'', sigla:'', nombre: '', correo: '', jefeDependencia:'', estado: '1', tipo:tipo
                                });
    const [formDataAdicionar, setFormDataAdicionar] = useState({serie:'', subSerie: '', persona: '' });
    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [seriesDocumentales, setSeriesDocumentales] = useState([]);
    const [subSeriesDocumentales, setSubSeriesDocumentales] = useState([]);
    const [listaSubSeriesDocumentales, setListaSubSeriesDocumentales] = useState([]);
    const [listaJefes, setListaJefes] = useState([]);
    const [personas, setPersonas] = useState([]);
    const [dependenciaPersonas, setDependenciaPersonas] = useState([]);
    const [dependenciaSubSerieDocumental, setDependenciaSubSerieDocumental] = useState([]);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeAdicionar = (e) =>{
        setFormDataAdicionar(prev => ({...prev, [e.target.name]: e.target.value}))
     }

    const handleInputChange = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    };

    const handleSubmit = () =>{
        if(dependenciaSubSerieDocumental.length === 0){
            showSimpleSnackbar('Debe adicionar como mínimo una sub serie documental', 'error');
            return
        }

        if(dependenciaPersonas.length === 0){
            showSimpleSnackbar('Debe adicionar como mínimo una persona encargada de la pedendencia', 'error');
            return
        }

        let newFormData       = {...formData}
        newFormData.subSeries = dependenciaSubSerieDocumental;
        newFormData.personas  = dependenciaPersonas;
        setLoader(true); 
        instance.post('/admin/dependencia/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({id:'000', codigo:'', sigla:'', nombre: '', correo: '', jefe:'', estado: '1',tipo:tipo}) : null;
            (formData.tipo === 'I' && res.success) ? setDependenciaPersonas([]) : null; 
            (formData.tipo === 'I' && res.success) ? setDependenciaSubSerieDocumental([]) : null; 
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        instance.post('/admin/dependencia/listar/datos', {codigo: formData.id, tipo:formData.tipo}).then(res=>{
            let personas            = res.personas;
            let dependenciapersonas = res.dependenciapersonas;
            let depenSSDocumental   = res.dependenciasubseriedocumentales
            let listaJefes          = [];
            personas.map(res=>{
                if(res.carlabid === 1 || res.carlabid === 2){
                    listaJefes.push({
                        persid: res.persid,
                        nombrePersona: res.nombrePersona 
                    });
                }
            })

            let newDependenciaSubSerieDocumental = [];
            depenSSDocumental.forEach(function(depSub){
                newDependenciaSubSerieDocumental.push({
                    identificador: depSub.desusdid,
                    subSerie: depSub.desusdsusedoid,
                    nombreSerie: depSub.serdocnombre,
                    nombreSubSerie: depSub.susedonombre,
                    estado: 'U'
                });
            }); 

            let newDependenciaPersonas = [];
            dependenciapersonas.forEach(function(pers){
                newDependenciaPersonas.push({
                    identificador: pers.depperid,
                    persona: pers.depperpersid,
                    nombrePersona: pers.nombrePersona,
                    estado: 'U'
                });
            });

            setListaJefes(listaJefes);
            setPersonas(personas);
            setSeriesDocumentales(res.seriesdocumentales);
            setSubSeriesDocumentales(res.subseriesdocumentales);
            setDependenciaPersonas(newDependenciaPersonas);
            setDependenciaSubSerieDocumental(newDependenciaSubSerieDocumental);
            setLoader(false);
        })
    }, []);

    const adicionarFilaSubSerie = () =>{

        if(formDataAdicionar.serie === ''){
            showSimpleSnackbar('Debe seleccionar una serie documental', 'error');
            return
        }

        if(formDataAdicionar.subSerie === ''){
            showSimpleSnackbar('Debe seleccionar una sub serie documental', 'error');
            return
        }

        if(dependenciaSubSerieDocumental.some(pers => pers.subSerie == formDataAdicionar.subSerie)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newDependenciaSubSerieDocumental = [...dependenciaSubSerieDocumental];
        const resultSeriesDocumentales       = seriesDocumentales.filter((serie) => serie.serdocid == formDataAdicionar.serie);
        const resultSubSeriesDocumentales    = subSeriesDocumentales.filter((subSerie) => subSerie.susedoid == formDataAdicionar.subSerie);
        newDependenciaSubSerieDocumental.push({identificador:'', subSerie:formDataAdicionar.subSerie, nombreSerie: resultSeriesDocumentales[0].serdocnombre, 
                                                nombreSubSerie: resultSubSeriesDocumentales[0].susedonombre,  estado: 'I'});
        setFormDataAdicionar({serie:'', subSerie: '', persona: '' });
        setDependenciaSubSerieDocumental(newDependenciaSubSerieDocumental);
    } 

    const eliminarFilaSubSerie = (id) =>{
        let newDependenciaSubSerieDocumental = []; 
        firmaPersona.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newDependenciaSubSerieDocumental.push({ identificador:res.identificador, subSerie: res.subSerie, nombreSerie:res.nombreSerie, nombreSubSerie:res.nombreSubSerie, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newDependenciaSubSerieDocumental.push({identificador:res.identificador, subSerie: res.subSerie, nombreSerie:res.nombreSerie, nombreSubSerie:res.nombreSubSerie, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newDependenciaSubSerieDocumental.push({identificador:res.identificador,subSerie: res.subSerie, nombreSerie:res.nombreSerie, nombreSubSerie:res.nombreSubSerie, estado:res.estado});
            }else{
                if(i != id){
                    newDependenciaSubSerieDocumental.push({identificador:res.identificador,subSerie: res.subSerie, nombreSerie:res.nombreSerie, nombreSubSerie:res.nombreSubSerie, estado: 'I' });
                }
            }
        })
        setDependenciaSubSerieDocumental(newDependenciaSubSerieDocumental);
    }

    const listarSubSeriesDocumentales = (e) =>{
        setFormDataAdicionar(prev => ({...prev, [e.target.name]: e.target.value}))
        let subSeries = [];
        let serie     = e.target.value;
        subSeriesDocumentales.forEach(function(ser){ 
            if(ser.serdocid === serie){
                subSeries.push({
                    susedoid: ser.susedoid,
                    serdocid: ser.serdocid,
                    susedonombre: ser.susedonombre
                });
            }
        });
        setListaSubSeriesDocumentales(subSeries);
    }

    const adicionarFilaPersona = () =>{
        if(formDataAdicionar.persona === ''){
            showSimpleSnackbar('Debe seleccionar una persona', 'error');
            return
        }

       if(dependenciaPersonas.some(pers => pers.persona == formDataAdicionar.persona)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newDependenciaPersonas = [...dependenciaPersonas]; 
        const resultPersonas = personas.filter((pers) => pers.persid == formDataAdicionar.persona); 
        newDependenciaPersonas.push({identificador:'', persona: formDataAdicionar.persona,  nombrePersona: resultPersonas[0].nombrePersona, estado: 'I'});   
        setFormDataAdicionar({serie:'', subSerie: '', persona: '' });
        setDependenciaPersonas(newDependenciaPersonas);
    } 

    const eliminarFilaPersona = (id) =>{
        let newDependenciaPersonas = []; 
        dependenciaPersonas.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newDependenciaPersonas.push({ identificador:res.identificador, persona: res.persona, nombrePersona:res.nombrePersona, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newDependenciaPersonas.push({identificador:res.identificador, persona: res.persona, nombrePersona:res.nombrePersona,estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newDependenciaPersonas.push({identificador:res.identificador, persona: res.persona, nombrePersona:res.nombrePersona,estado:res.estado});
            }else{
                if(i != id){
                    newDependenciaPersonas.push({identificador:res.identificador, persona: res.persona, nombrePersona:res.nombrePersona,estado: 'I' });
                }
            }
        })
        setDependenciaPersonas(newDependenciaPersonas);
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>
                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <SelectValidator
                        name={'jefeDependencia'}
                        value={formData.jefeDependencia}
                        label={'Jefe de la dependencia'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {listaJefes.map(res=>{
                            return <MenuItem value={res.persid} key={res.persid} >{res.nombrePersona}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'codigo'}
                        value={formData.codigo}
                        label={'Código'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 10}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator
                        name={'sigla'}
                        value={formData.sigla}
                        label={'Sigla'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 3}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleInputChange}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <TextValidator
                        name={'nombre'}
                        value={formData.nombre}
                        label={'Nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleInputChange}
                    />
                </Grid>
                
                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator
                        name={'correo'}
                        value={formData.correo}
                        label={'Correo'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={['required', 'isEmail']}
                        errorMessages={['Campo requerido', 'Correo no válido']}
                        type={"email"}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <SelectValidator
                        name={'estado'}
                        value={formData.estado}
                        label={'Activo'}
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

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Anexar series y sub series documentales
                    </Box>
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <SelectValidator
                        name={'serie'}
                        value={formDataAdicionar.serie}
                        label={'Serie documental'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}} 
                        onChange={listarSubSeriesDocumentales} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {seriesDocumentales.map(res=>{
                            return <MenuItem value={res.serdocid} key={res.serdocid} >{res.serdocnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <SelectValidator
                        name={'subSerie'}
                        value={formDataAdicionar.subSerie}
                        label={'Sub serie documental'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off'}}
                        onChange={handleChangeAdicionar}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {listaSubSeriesDocumentales.map(res=>{
                            return <MenuItem value={res.susedoid} key={res.susedoid+res.serdocid} >{res.susedonombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <Button type={"button"} className={'modalBtn'} 
                        startIcon={<AddIcon />} onClick={() => {adicionarFilaSubSerie()}}> {"Agregar"}
                    </Button>
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='divisionFormulario'>
                        Series y sub series documentales adiconada a la dependencia
                    </Box>
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Table key={'tableSubSerie'}  className={'tableAdicional'} xl={{width: '60%', margin:'auto'}} md={{width: '70%', margin:'auto'}}  sx={{width: '80%', margin:'auto'}} sm={{maxHeight: '90%', margin:'auto'}} >
                        <TableHead>
                            <TableRow>
                                <TableCell style={{width: '60%'}}>Serie documental</TableCell>
                                <TableCell style={{width: '40%'}}>Sub serie documental </TableCell> 
                                <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>

                        { dependenciaSubSerieDocumental.map((subSerie, a) => {
                            return(
                                <TableRow key={'rowD-' +a} className={(subSerie['estado'] == 'D')? 'tachado': null}>
                                    <TableCell>
                                        <p>{subSerie['nombreSerie']}</p>
                                    </TableCell> 

                                    <TableCell>
                                        <p>{subSerie['nombreSubSerie']}</p>
                                    </TableCell>
                                    
                                    <TableCell className='cellCenter'>
                                        <Icon key={'iconDelete'+a} className={'icon top red'}
                                                onClick={() => {eliminarFilaSubSerie(a);}}
                                            >clear</Icon>
                                    </TableCell>
                                </TableRow>
                                );
                            })
                        }
                        </TableBody>
                    </Table>
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Anexar persona a la dependencia
                    </Box>
                </Grid>

                <Grid item xl={2} md={2} sm={12} xs={12}>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <SelectValidator
                        name={'persona'}
                        value={formDataAdicionar.persona}
                        label={'Persona'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        onChange={handleChangeAdicionar} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {personas.map(res=>{
                            return <MenuItem value={res.persid} key={res.persid} >{res.nombrePersona}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <Button type={"button"} className={'modalBtn'} 
                        startIcon={<AddIcon />} onClick={() => {adicionarFilaPersona()}}> {"Agregar"}
                    </Button>
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='divisionFormulario'>
                        Personas adicionadas a la dependencia
                    </Box>
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Table key={'tablePersona'} className={'tableAdicional'} xl={{width: '60%', margin:'auto'}} md={{width: '70%', margin:'auto'}}  sx={{width: '80%', margin:'auto'}} sm={{maxHeight: '90%', margin:'auto'}}>
                        <TableHead>
                            <TableRow>
                                <TableCell>Persona</TableCell>
                                <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>

                        { dependenciaPersonas.map((pers, a) => {
                            return(
                                <TableRow key={'rowA-' +a} className={(pers['estado'] == 'D')? 'tachado': null}>

                                    <TableCell>
                                        <p> {pers['nombrePersona']}</p>
                                    </TableCell>
                                    
                                    <TableCell className='cellCenter'>
                                        <Icon key={'iconDelete'+a} className={'icon top red'}
                                                onClick={() => {eliminarFilaPersona(a);}}
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
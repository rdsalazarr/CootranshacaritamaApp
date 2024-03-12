import React, {useState, useEffect, Fragment} from 'react';
import { Button, Grid, MenuItem, Stack, Box, Icon, Table, TableHead, TableBody, TableRow, TableCell} from '@mui/material';
import { ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import AddIcon from '@mui/icons-material/Add';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                                (tipo !== 'I') ? {codigo:data.rutaid,  departamentoOrigen:data.rutadepaidorigen, municipioOrigen: data.rutamuniidorigen, departamentoDestino: data.rutadepaiddestino, 
                                        municipioDestino:data.rutamuniiddestino, tieneNodos:data.rutatienenodos, estado:data.rutaactiva, tipo:tipo 
                                    } : {codigo:'000', departamentoOrigen:'', municipioOrigen: '', departamentoDestino: '', municipioDestino:'', tieneNodos:'', estado:'1', tipo:tipo
                                });

    const [formDataAdicionarNodo, setFormDataAdicionarNodo] = useState({deptoNodoId:'', nombreDepto: '', municipioNodoId:'', nombreMunicipio: ''});
    const [municipiosNodoDestino, setMunicipiosNodoDestino] = useState([]);
    const [municipiosDestino, setMunicipiosDestino] = useState([]);
    const [municipiosOrigen, setMunicipiosOrigen] = useState([]);
    const [departamentos, setDepartamentos] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [municipios, setMunicipios] = useState([]);
    const [rutaNodos, setRutaNodos] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeAdicionar = (e) =>{
        setFormDataAdicionarNodo(prev => ({...prev, [e.target.name]: e.target.value}))
     }

    const handleSubmit = () =>{
        if(formData.tieneNodos.toString() === '1' && rutaNodos.length === 0){
            showSimpleSnackbar('Debe adicionar como mínimo un nodo a la ruta', 'error');
            return
        }

        let newFormData   = {...formData}
        newFormData.nodos = (rutaNodos.length > 0) ? rutaNodos : [];
        setLoader(true);
        instance.post('/admin/despacho/ruta/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo === 'I' && res.success) ? setRutaNodos([]) : null; 
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', departamentoOrigen:'', municipioOrigen: '', departamentoDestino: '', municipioDestino:'', tieneNodos:'', estado:'1',  tipo:tipo}) : null;
            setLoader(false);
        })
    }

    const consultarMunicipioOrigen = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
        let municipiosOrigen = [];
        let deptoOrigen      = e.target.value;
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === deptoOrigen){
                municipiosOrigen.push({
                    muniid:     muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setMunicipiosOrigen(municipiosOrigen);
    }

    const consultarMunicipioDestino = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
        let municipiosDestino = [];
        let deptoDestino      = e.target.value;
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === deptoDestino){
                municipiosDestino.push({
                    muniid:     muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setMunicipiosDestino(municipiosDestino);
    }

    const consultarMunicipioNodoDestino = (e) =>{
        setFormDataAdicionarNodo(prev => ({...prev, [e.target.name]: e.target.value}))
        let municipiosDestino = [];
        let deptoDestino      = e.target.value;
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === deptoDestino){
                municipiosDestino.push({
                    muniid:     muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setMunicipiosNodoDestino(municipiosDestino);
    }

    const adicionarFilaNodo = () =>{

        if(formDataAdicionarNodo.deptoNodoId === ''){
            showSimpleSnackbar('Debe seleccionar un departamento', 'error');
            return
        }

        if(formDataAdicionarNodo.municipioNodoId === ''){
            showSimpleSnackbar('Debe seleccionar un municipio', 'error');
            return
        }

        if(rutaNodos.some(nod => nod.deptoNodoId === formDataAdicionarNodo.deptoNodoId && nod.municipioNodoId == formDataAdicionarNodo.municipioNodoId)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newRutaNodos               = [...rutaNodos];
        const resultadoNombreDepto     = departamentos.filter((dep) => dep.depaid == formDataAdicionarNodo.deptoNodoId);
        const resultadoNombreMunicipio = municipios.filter((mun) => mun.muniid == formDataAdicionarNodo.municipioNodoId);
        newRutaNodos.push({identificador:'', deptoNodoId:formDataAdicionarNodo.deptoNodoId, nombreDepto: resultadoNombreDepto[0].depanombre, 
                             municipioNodoId:formDataAdicionarNodo.municipioNodoId, nombreMunicipio: resultadoNombreMunicipio[0].muninombre, estado: 'I'});
        setFormDataAdicionarNodo({deptoNodoId:'', nombreDepto: '', municipioNodoId:'', nombreMunicipio: '' });
        setRutaNodos(newRutaNodos);
    }

    const eliminarFilaNodo = (id) =>{
        let newRutaNodos = [];
        let estado       = 'I';
        rutaNodos.map((res,i) =>{
            if (i === id) {
                estado = res.estado === 'U' ? 'D' : 'U';
            } else {
                estado = (res.estado === 'D' || res.estado === 'U') ? res.estado : 'I';
            }
            newRutaNodos.push({identificador:res.identificador, deptoNodoId: res.deptoNodoId, nombreDepto:res.nombreDepto, 
                municipioNodoId: res.municipioNodoId, nombreMunicipio:res.nombreMunicipio, estado: estado });
        })
        setRutaNodos(newRutaNodos);
    }

    useEffect(()=>{
        setLoader(true);
        instance.post('/admin/despacho/ruta/listar/datos', {codigo:formData.codigo, tipo:tipo}).then(res=>{
            setDepartamentos(res.departamentos);
            setMunicipios(res.municipios);

           if(tipo !== 'I'){ 
                let municipiosOrigen = [];
                let deptoOrigen      = data.rutadepaidorigen;
                let rutasNodo        = res.rutasNodo;
                res.municipios.forEach(function(muni){ 
                    if(muni.munidepaid === deptoOrigen){
                        municipiosOrigen.push({
                            muniid:     muni.muniid,
                            muninombre: muni.muninombre
                        });
                    }
                });
                setMunicipiosOrigen(municipiosOrigen);

                let municipiosDestino = [];
                let deptoDestino      = data.rutadepaiddestino;
                res.municipios.forEach(function(muni){ 
                    if(muni.munidepaid === deptoDestino){
                        municipiosDestino.push({
                            muniid:     muni.muniid,
                            muninombre: muni.muninombre
                        });
                    }
                });
                setMunicipiosDestino(municipiosDestino);
                setRutaNodos(rutasNodo);
            }
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Grid container spacing={2}>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <SelectValidator
                        name={'departamentoOrigen'}
                        value={formData.departamentoOrigen}
                        label={'Departamento origen'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarMunicipioOrigen}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {departamentos.map(res=>{
                            return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <SelectValidator
                        name={'municipioOrigen'}
                        value={formData.municipioOrigen}
                        label={'Municipio origen'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {municipiosOrigen.map(res=>{
                            return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={3} xs={12}>
                    <SelectValidator
                        name={'departamentoDestino'}
                        value={formData.departamentoDestino}
                        label={'Departamento destino'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarMunicipioDestino}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {departamentos.map(res=>{
                            return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={5} md={5} sm={5} xs={12}>
                    <SelectValidator
                        name={'municipioDestino'}
                        value={formData.municipioDestino}
                        label={'Municipio destino'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {municipiosDestino.map(res=>{
                            return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={2} md={2} sm={2} xs={12}>
                    <SelectValidator
                        name={'tieneNodos'}
                        value={formData.tieneNodos}
                        label={'Tiene nodos'}
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

                <Grid item xl={2} md={2} sm={2} xs={12}>
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
                        <MenuItem value={"1"}>Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                {(formData.tieneNodos.toString() === '1') ?
                    <Fragment>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='divisionFormulario'>
                                Asignación de nodos a la ruta
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={3} xs={3}>
                            <SelectValidator
                                name={'deptoNodoId'}
                                value={formDataAdicionarNodo.deptoNodoId}
                                label={'Departamento nodo'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                onChange={consultarMunicipioNodoDestino}
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {departamentos.map(res=>{
                                    return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={7} md={7} sm={9} xs={9}>
                            <SelectValidator
                                name={'municipioNodoId'}
                                value={formDataAdicionarNodo.municipioNodoId}
                                label={'Municipio nodo'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                onChange={handleChangeAdicionar}
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {municipiosNodoDestino.map(res=>{
                                    return <MenuItem value={res.muniid} key={res.muniid} >{res.muninombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={2} md={2} sm={12} xs={12} style={{textAlign:'center'}}>
                            <Button type={"button"} className={'modalBtnIcono'} 
                                startIcon={<AddIcon className='icono' />} onClick={() => {adicionarFilaNodo()}}> {"Agregar"}
                            </Button>
                        </Grid>

                        {(rutaNodos.length > 0) ?
                            <Fragment>
                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                    <Box className='divisionFormulario'>
                                        Nodos adicionados a la ruta
                                    </Box>
                                </Grid>
                                
                                <Grid item md={12} xl={12} sm={12} xs={12}>
                                    <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                        <Table key={'tablePersona'} className={'tableAdicional'} xl={{width: '60%', margin:'auto'}} md={{width: '70%', margin:'auto'}}  sx={{width: '80%', margin:'auto'}} sm={{maxHeight: '90%', margin:'auto'}}>
                                            <TableHead>
                                                <TableRow>
                                                    <TableCell>Departamento</TableCell>
                                                    <TableCell>Municipio</TableCell>
                                                    <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                                                </TableRow>
                                            </TableHead>
                                            <TableBody>

                                            { rutaNodos.map((muni, a) => {
                                                return(
                                                    <TableRow key={'rowA-' +a} className={(muni['estado'] == 'D')? 'tachado': null}>

                                                        <TableCell>
                                                            {muni['nombreDepto']}
                                                        </TableCell>

                                                        <TableCell>
                                                            {muni['nombreMunicipio']}
                                                        </TableCell>

                                                        <TableCell className='cellCenter'>
                                                            <Icon key={'iconDelete'+a} className={'icon top red'}
                                                                    onClick={() => {eliminarFilaNodo(a);}}
                                                                >clear</Icon>
                                                        </TableCell>
                                                    </TableRow>
                                                    );
                                                })
                                            }
                                            </TableBody>
                                        </Table>
                                    </Box>
                                </Grid>

                            </Fragment>
                        : null}
                    </Fragment>
                : null}
 
            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
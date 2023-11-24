import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Icon, Table, TableHead, TableBody, TableRow, TableCell, Autocomplete, createFilterOptions} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import AddIcon from '@mui/icons-material/Add';

export default function New({data, tipo}){
    const [formData, setFormData] = useState(
                                (tipo !== 'I') ? {codigo:data.rutaid,  departamentoOrigen:data.depaidorigen, municipioOrigen: data.muniidorigen, departamentoDestino: data.depaiddestino, 
                                        municipioDestino:data.muniiddestino, tieneNodos:data.rutatienenodos, estado:data.rutaactiva, tipo:tipo 
                                    } : {codigo:'000', departamentoOrigen:'', municipioOrigen: '', departamentoDestino: '', municipioDestino:'', tieneNodos:'', estado:'1', tipo:tipo
                                });
   
    const [formDataAdicionarNodo, setFormDataAdicionarNodo] = useState({municipioId:'', nombreMunicipio: ''});
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
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', departamentoOrigen:'', municipioOrigen: '', departamentoDestino: '', municipioDestino:'', tieneNodos:'', estado:'1', tipo:tipo}) : null;
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

    const adicionarFilaNodo = () =>{
        if(formDataAdicionarNodo.municipioId === ''){
            showSimpleSnackbar('Debe seleccionar un municipio', 'error');
            return
        }

        if(rutaNodos.some(nod => nod.municipioId == rutaNodos.municipioId)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newRutaNodos               = [...rutaNodos];
        const resultadoNombreMunicipio = municipiosDestino.filter((mun) => mun.muniid == formDataAdicionarNodo.municipioId);
        newRutaNodos.push({identificador:'', municipioId:formDataAdicionarNodo.municipioId, nombreMunicipio: resultadoNombreMunicipio[0].muninombre, estado: 'I'});
        setFormDataAdicionarNodo({municipioId:'', nombreMunicipio: '' });
        setRutaNodos(newRutaNodos);
    }

    const eliminarFilaNodo = (id) =>{
        let newRutaNodos = [];
        rutaNodos.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newRutaNodos.push({ identificador:res.identificador, municipioId: res.municipioId, nombreMunicipio:res.nombreMunicipio, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newRutaNodos.push({identificador:res.identificador, municipioId: res.municipioId, nombreMunicipio:res.nombreMunicipio, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newRutaNodos.push({identificador:res.identificador, municipioId: res.municipioId, nombreMunicipio:res.nombreMunicipio, estado:res.estado});
            }else{
                if(i != id){
                    newRutaNodos.push({identificador:res.identificador,municipioId: res.municipioId, nombreMunicipio:res.nombreMunicipio, estado: 'I' });
                }
            }
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
                let deptoOrigen      = data.depaidorigen;
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
                let deptoDestino      = data.depaiddestino;
                res.municipios.forEach(function(muni){ 
                    if(muni.munidepaid === deptoDestino){
                        municipiosDestino.push({
                            muniid:     muni.muniid,
                            muninombre: muni.muninombre
                        });
                    }
                });
                setMunicipiosDestino(municipiosDestino);

                let newRutaNodos = [];
                rutasNodo.forEach(function(nodoRuta){
                    const nodoEncontrado = municipiosDestino.find(mun => mun.muniid === nodoRuta.muniid);
                    if(nodoEncontrado){
                        newRutaNodos.push({
                            identificador:   nodoRuta.rutnodid,
                            municipioId:     nodoRuta.muniid,
                            nombreMunicipio: nodoEncontrado.muninombre,
                            estado: 'U'
                        });
                    }
                });

                setRutaNodos(newRutaNodos);
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

                <Grid item xl={4} md={4} sm={4} xs={12}>
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

                <Grid item xl={8} md={8} sm={8} xs={12}>
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

                        <Grid item xl={2} md={2} sm={1} xs={1}>
                        </Grid>

                        <Grid item xl={8} md={8} sm={10} xs={9}>
                            <Autocomplete
                                id="vehiculo"
                                style={{height: "26px", width: "100%"}}
                                options={municipiosDestino}
                                getOptionLabel={(option) => option.muninombre} 
                                value={municipiosDestino.find(v => v.muniid === formDataAdicionarNodo.municipioId) || null}
                                filterOptions={createFilterOptions({ limit:10 })}
                                onChange={(event, newInputValue) => {
                                    if(newInputValue){
                                        setFormDataAdicionarNodo({...formDataAdicionarNodo, municipioId: newInputValue.muniid})
                                    }
                                }}
                                renderInput={(params) =>
                                    <TextValidator {...params}
                                        label="Consultar municipio"
                                        className="inputGeneral"
                                        variant="standard"
                                        value={formDataAdicionarNodo.municipioId}
                                        placeholder="Consulte el municipio aquí..." />}
                            />
                        </Grid> 

                        <Grid item xl={2} md={2} sm={12} xs={12}>
                            <Button type={"button"} className={'modalBtn'} 
                                startIcon={<AddIcon />} onClick={() => {adicionarFilaNodo()}}> {"Agregar"}
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
                                                    <TableCell>Municipio</TableCell>
                                                    <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                                                </TableRow>
                                            </TableHead>
                                            <TableBody>

                                            { rutaNodos.map((muni, a) => {
                                                return(
                                                    <TableRow key={'rowA-' +a} className={(muni['estado'] == 'D')? 'tachado': null}>

                                                        <TableCell>
                                                            <p> {muni['nombreMunicipio']}</p>
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
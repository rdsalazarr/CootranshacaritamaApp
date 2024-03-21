import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Icon,Table, TableHead, TableBody, TableRow, TableCell, Box } from '@mui/material';
import { Radio, RadioGroup, FormControlLabel, FormControl, FormLabel} from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import AddIcon from '@mui/icons-material/Add';
import instance from '../../layout/instance';

export default function New({data, tipo}){
 
    const [formData, setFormData] = useState( 
                    (tipo !== 'I') ? {codigo:data.usuaid, tipoIdentificacion: data.tipideid, persona:data.persid, documento: data.persdocumento, nombre: data.usuanombre, 
                                     alias:data.usuaalias, apellido: data.usuaapellidos, correo: data.usuaemail,usuario: data.usuanick, cambiarPassword: data.usuacambiarpassword,
                                      bloqueado: data.usuabloqueado, agencia: data.agenid, estado: data.usuaactivo, caja: data.cajaid, tipo:tipo 
                                    } : {codigo:'000', tipoIdentificacion:'',documento:'', persona:'', nombre: '', apellido: '', correo: '', usuario:'', alias:'',
                                        cambiarPassword:'1',bloqueado:'0', agencia: '', caja:'', estado: '1', tipo:tipo
                                });

    const numeroCaja = (tipo === 'I') ? '99' : ((data.cajaid === null) ? '99' : data.cajaid);
    const [formDataAdicionar, setFormDataAdicionar] = useState({rol: ''});
    const [tipoIdentificacion, setTipoIdentificacion] = useState([]);
    const [cajaAgencia, setCajaAgencia] = useState(numeroCaja);
    const [rolesUsuario, setRolesUsuario] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [agencias, setAgencias] = useState([]);
    const [loader, setLoader] = useState(false);
    const [roles, setRoles] = useState([]);
    const [cajas, setCajas] = useState([]);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}));
    }

    const handleChangeRoles = (e) =>{
        setFormDataAdicionar(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeRadio = (event) => {
        setCajaAgencia(event.target.value); 
    }

    const handleSubmit = () =>{
        if(rolesUsuario.length === 0){
            showSimpleSnackbar('Debe adicionar como mínimo un rol al usuario', 'error');
            return
        }

        let newFormData   = {...formData}
        newFormData.roles = rolesUsuario;
        newFormData.caja  = cajaAgencia;
        setLoader(true); 
        instance.post('/admin/usuario/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', tipoIdentificacion:'',documento:'',persona:'', nombre: '', apellido: '', correo: '', usuario:'', 
                                                                    alias:'', cambiarPassword:'1',bloqueado:'0',estado: '1', tipo:tipo}) : null;
            (formData.tipo === 'I' && res.success) ? setRolesUsuario([]) : null;
            setLoader(false);
        })
    }

    const consultarPersona = (e) =>{
        let newFormData                = {...formData}
        let documento                  = (e.target.name === 'documento' ) ? e.target.value : formData.documento ;
        let tpIdentificacion           = (e.target.name === 'tipoIdentificacion' ) ? e.target.value : formData.tipoIdentificacion ;
        newFormData.tipoIdentificacion = tpIdentificacion;
        if (tpIdentificacion !=='' && documento !==''){
            setLoader(true); 
            instance.post('/admin/usuario/consultar/persona', {tipoIdentificacion:formData.tipoIdentificacion, documento: formData.documento}).then(res=>{
                if(!res.success){
                    newFormData.persona  = '';
                    newFormData.nombre   = '';
                    newFormData.apellido = '';
                    newFormData.correo   = '';
                    newFormData.usuario  = '';
                    newFormData.alias    = '';
                    showSimpleSnackbar(res.message, 'error');
                    setHabilitado(false);
                }else{
                    let personas         = res.personas; 
                    let apellidoAlias    = (personas.persgenero === 'F') ? personas.persprimernombre.substring(0, 1)+'.' : personas.persprimerapellido.substring(0, 1)+'.';
                    let aliasGenerado    = (personas.persgenero === 'F') ? personas.persprimerapellido : personas.persprimernombre;
                    newFormData.persona  = personas.persid;
                    newFormData.nombre   = personas.nombres;
                    newFormData.apellido = personas.apellidos;
                    newFormData.correo   = personas.perscorreoelectronico;
                    newFormData.usuario  = eliminarCaracteresEspeciales(personas.persprimernombre)+''+personas.persprimerapellido.substring(0, 1);
                    newFormData.alias    = nombreAlias(aliasGenerado)+' '+apellidoAlias;
                    setHabilitado(true)
                }
                setLoader(false);
            })
        }
        setFormData(newFormData);
    }

    const nombreAlias = (cadena) =>{
        return cadena.charAt(0).toUpperCase() + cadena.slice(1).toLowerCase();
    }

    const eliminarCaracteresEspeciales = (cadena) =>{
        const mapaAcentos = {'á': 'a', 'é': 'e', 'í': 'i', 'ó': 'o', 'ú': 'u', 'ü': 'u', 'ñ': 'n', 'Á': 'A', 'É': 'E', 'Í': 'I', 'Ó': 'O', 'Ú': 'U', 'Ü': 'U'};
        return cadena.replace(/[áéíóúüñÁÉÍÓÚÜÑ]/g, (letra) => mapaAcentos[letra] || letra);
    }

    const adicionarFilaRol = () =>{
        if(formDataAdicionar.rol === ''){
            showSimpleSnackbar('Debe seleccionar un rol', 'error');
            return
        }

       if(rolesUsuario.some(rolUsua => rolUsua.rol == formDataAdicionar.rol)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newRolesUsuario = [...rolesUsuario]; 
        const resultRoles = roles.filter((rol) => rol.rolid == formDataAdicionar.rol);
        newRolesUsuario.push({identificador:'', rol: formDataAdicionar.rol,  nombreRol: resultRoles[0].rolnombre, estado: 'I'});   
        setFormDataAdicionar({rol: ''});
        setRolesUsuario(newRolesUsuario);
    }

    const eliminarFilaRol = (id) =>{
        let newRolesUsuario = []; 
        rolesUsuario.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newRolesUsuario.push({ identificador:res.identificador, rol: res.rol, nombreRol:res.nombreRol, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newRolesUsuario.push({identificador:res.identificador, rol: res.rol, nombreRol:res.nombreRol,estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newRolesUsuario.push({identificador:res.identificador, rol: res.rol, nombreRol:res.nombreRol,estado:res.estado});
            }else{
                if(i != id){
                    newRolesUsuario.push({identificador:res.identificador, rol: res.rol, nombreRol:res.nombreRol,estado: 'I' });
                }
            }
        })
        setRolesUsuario(newRolesUsuario);
    }

    useEffect(()=>{
        setLoader(true);
        instance.post('/admin/usuario/list/datos', {codigo:formData.codigo, tipo:tipo}).then(res=>{
            setTipoIdentificacion(res.tipoIdentificaciones);
            setAgencias(res.agencias);
            setRoles(res.roles);
            setCajas(res.cajas);

            if(tipo === 'U'){
                let newRolesUsuario = [];
                res.usuariosRoles.forEach(function(usua){
                    newRolesUsuario.push({
                        identificador: usua.usurolid,
                        rol: usua.rolid,
                        nombreRol: usua.rolnombre,
                        estado: 'U'
                    });
                });
                setRolesUsuario(newRolesUsuario);
            }
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>
                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tipoIdentificacion'}
                        value={formData.tipoIdentificacion}
                        label={'Tipo de documento'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarPersona} 
                        disabled={(tipo === 'U') ? true : false}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoIdentificacion.map(res=>{
                            return <MenuItem value={res.tipideid} key={res.tipideid}>{res.tipidenombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <TextValidator
                        name={'documento'}
                        value={formData.documento}
                        label={'Documento'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 15}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        onBlur={consultarPersona}
                        disabled={(tipo === 'U') ? true : false}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <TextValidator
                        name={'nombre'}
                        value={formData.nombre}
                        label={'Nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <TextValidator
                        name={'apellido'}
                        value={formData.apellido}
                        label={'Apellido'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
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

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <TextValidator
                        name={'usuario'}
                        value={formData.usuario}
                        label={'Usuario'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'alias'}
                        value={formData.alias}
                        label={'Alias'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
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

                { (formData.tipo === 'U') ?
                    <Fragment>
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'cambiarPassword'}
                                value={formData.cambiarPassword}
                                label={'Cambiar clave'}
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
                                name={'bloqueado'}
                                value={formData.bloqueado}
                                label={'¡Usuario bloqueado?'}
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
                    </Fragment>
                : null }

                <Grid item xl={3} md={3} sm={6} xs={12}>
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

                <Grid item xl={9} md={9} sm={12} xs={12}>
                    <FormControl>
                        <FormLabel>Número de caja en la agencia</FormLabel>
                        <RadioGroup
                            row
                            name="caja"
                            value={cajaAgencia}
                            onChange={handleChangeRadio}
                        >
                        <FormControlLabel value="99" control={<Radio color="success"/>} label="NINGUNA" />
                        {cajas.map(res=>{
                            return (
                                <FormControlLabel key={res.cajaid} value={res.cajaid} control={<Radio color="success"/>} label={res.cajanumero} /> 
                            )
                        })}
                        </RadioGroup>
                    </FormControl>
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Asignar roles al usuario
                    </Box>
                </Grid>

                <Grid item xl={4} md={4} sm={1} xs={1}>
                </Grid>

                <Grid item xl={4} md={4} sm={8} xs={11}>
                    <SelectValidator
                        name={'rol'}
                        value={formDataAdicionar.rol}
                        label={'Rol'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        onChange={handleChangeRoles}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {roles.map(res=>{
                            return <MenuItem value={res.rolid} key={res.rolid}> {res.rolnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={2} md={2} sm={2} xs={12}>
                    <Button type={"button"} className={'modalBtnIcono'} 
                        startIcon={<AddIcon className='icono' />} onClick={() => {adicionarFilaRol()}}> {"Agregar 123"}
                    </Button>
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='divisionFormulario'>
                        Roles asignados al usuario
                    </Box>
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Table key={'tableSubSerie'}  className={'tableAdicional'} xl={{width: '60%', margin:'auto'}} md={{width: '70%', margin:'auto'}}  sx={{width: '80%', margin:'auto'}} sm={{maxHeight: '90%', margin:'auto'}} >
                        <TableHead>
                            <TableRow>
                                <TableCell style={{width: '90%'}}>Nombre del rol</TableCell>
                                <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                        { rolesUsuario.map((rolUsuar, a) => {
                            return(
                                <TableRow key={'rowD-' +a} className={(rolUsuar['estado'] == 'D')? 'tachado': null}>
                                    <TableCell>
                                        {rolUsuar['nombreRol']}
                                    </TableCell> 

                                    <TableCell className='cellCenter'>
                                        <Icon key={'iconDelete'+a} className={'icon top red'}
                                                onClick={() => {eliminarFilaRol(a);}}
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
import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack } from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';

export default function New({data, tipo}){
 
    const [formData, setFormData] = useState( 
                    (tipo !== 'I') ? {codigo:data.usuaid, tipoIdentificacion: data.tipideid, persona:data.persid, documento: data.persdocumento, nombre: data.usuanombre, 
                                     alias:data.usuaalias, apellido: data.usuaapellidos, correo: data.usuaemail,usuario: data.usuanick, cambiarPassword: data.usuacambiarpassword,
                                      bloqueado: data.usuabloqueado, estado: data.usuaactivo, tipo:tipo 
                                    } : {codigo:'000', tipoIdentificacion:'',documento:'', persona:'', nombre: '', apellido: '', correo: '', usuario:'', alias:'',
                                        cambiarPassword:'1',bloqueado:'0',estado: '1', tipo:tipo
                                });
                                
    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [tipoIdentificacion, setTipoIdentificacion] = useState([]);
   
    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleInputChange = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    };

    const handleSubmit = () =>{
        setLoader(true); 
        instance.post('/admin/usuario/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', tipoIdentificacion:'',documento:'',persona:'', nombre: '', apellido: '', correo: '', usuario:'', 
                                                                    alias:'', cambiarPassword:'1',bloqueado:'0',estado: '1', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    const consultarPersona = () =>{
        //setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
        let newFormData = {...formData}
        setLoader(true); 
        instance.post('/admin/usuario/consultar/persona', {tipoIdentificacion:formData.tipoIdentificacion, documento: formData.documento}).then(res=>{
            let personas         = res.personas; 
            let apellidoAlias    = (personas.persgenero === 'F') ? personas.persprimernombre.substring(0, 1)+'.' : personas.persprimerapellido.substring(0, 1)+'.';             
            let aliasGenerado    = (personas.persgenero === 'F') ? personas.persprimerapellido : personas.persprimernombre;
            newFormData.persona  = personas.persid;
            newFormData.nombre   = personas.nombres;
            newFormData.apellido = personas.apellidos;
            newFormData.correo   = personas.perscorreoelectronico;
            newFormData.usuario  = eliminarCaracteresEspeciales(personas.persprimernombre)+''+personas.persprimerapellido.substring(0, 1);
            newFormData.alias    = nombreAlias(aliasGenerado)+' '+apellidoAlias;
            setFormData(newFormData);
            setLoader(false);
        })
    }

    const nombreAlias = (cadena) =>{
        return cadena.charAt(0).toUpperCase() + cadena.slice(1).toLowerCase();
    }

    const eliminarCaracteresEspeciales = (cadena) =>{
        const mapaAcentos = {'á': 'a', 'é': 'e', 'í': 'i', 'ó': 'o', 'ú': 'u', 'ü': 'u', 'ñ': 'n', 'Á': 'A', 'É': 'E', 'Í': 'I', 'Ó': 'O', 'Ú': 'U', 'Ü': 'U'};
        return cadena.replace(/[áéíóúüñÁÉÍÓÚÜÑ]/g, (letra) => mapaAcentos[letra] || letra);
    }

    useEffect(()=>{
        setLoader(true);
        instance.get('/admin/listar/datos/usuario').then(res=>{
            setTipoIdentificacion(res.tipoIdentificaciones);
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
                        onChange={handleChange} 
                        disabled={(tipo === 'U') ? true : false}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoIdentificacion.map(res=>{
                            return <MenuItem value={res.tipideid} key={res.tipideid} > {res.tipidenombre}</MenuItem>
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
                        onChange={handleInputChange}
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
                        onChange={handleInputChange}
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
                        onChange={handleInputChange}
                    />
                </Grid>
                
                <Grid item xl={3} md={3} sm={4} xs={12}>
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
                 
                { (formData.tipo === 'U') ?
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
                : null }  

                { (formData.tipo === 'U') ?
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
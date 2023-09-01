import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack } from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';

export default function New({data, tipo}){
 
    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo:data.usuaid, tipoIdentificacion: data.tipideid, documento: data.usuadocumento, nombre: data.usuanombre, 
                                      apellido: data.usuaapellidos, correo: data.usuaemail,usuario: data.usuanick,  cambiarPassword: data.usuacambiarpassword,
                                      bloqueado: data.usuabloqueado, estado: data.usuaactivo, tipo:tipo 
                                    } : {codigo:'000', tipoIdentificacion:'',documento:'', nombre: '', apellido: '', correo: '', usuario:'', 
                                        cambiarPassword:'',bloqueado:'',estado: '1', tipo:tipo
                                });
                                
   const [loader, setLoader] = useState(false); 
   const [habilitado, setHabilitado] = useState(true);
   const [tipoIdentificacion, setTipoIdentificacion] = useState([]);
   
   const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
   }

    const handleSubmit = () =>{
        setLoader(true); 
        instance.post('/admin/usuario/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', tipoIdentificacion:'',documento:'', nombre: '', apellido: '', correo: '', usuario:'', 
                                                                    cambiarPassword:'',bloqueado:'',estado: '1', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        instance.get('/listar/tipo/identificacion').then(res=>{
            setTipoIdentificacion(res.data);
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
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoIdentificacion.map(res=>{
                            return <MenuItem value={res.tipideid} key={res.tipideid} >{res.tipidenombre}</MenuItem>
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
                        onChange={handleChange}
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
                        onChange={handleChange}
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
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={12} xs={12}>
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
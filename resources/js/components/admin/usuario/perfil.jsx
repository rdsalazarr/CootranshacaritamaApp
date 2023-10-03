import React, {useState , useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator  } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack } from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';

export default function Perfil(){
    const [formData, setFormData] = useState({ tipoIdentificacion: '', documento: '',  nombre: '', apellido: '', correo: '', usuario: '',  alias: ''});
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [tipoIdentificacion, setTipoIdentificacion] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        let ruta = '/admin/usuario/updatePerfil';
        instance.post(ruta, formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            setLoader(false);
        })
    }

    useEffect(()=>{
       setLoader(true);
        instance.get('/admin/usuario/miPerfil').then(res=>{
            let newFormData                = {...formData}
            let usuaio                     = res.usuario;
            newFormData.tipoIdentificacion = usuaio.tipideid;
            newFormData.documento          = usuaio.persdocumento;
            newFormData.nombre             = usuaio.usuanombre;
            newFormData.apellido           = usuaio.usuaapellidos;
            newFormData.correo             = usuaio.usuaemail;
            newFormData.usuario            = usuaio.usuanick;
            newFormData.alias              = usuaio.usuaalias;
            setFormData(newFormData);
            setTipoIdentificacion(res.tipoidentificaciones);
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

            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                    startIcon={<SaveIcon />}> Guardar </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
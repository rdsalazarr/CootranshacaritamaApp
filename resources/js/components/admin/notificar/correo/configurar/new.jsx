import React, {useState} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../../layout/snackBar';
import {LoaderModal} from "../../../../layout/loader";
import { Button, Grid, Stack } from '@mui/material';
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function New({data}){ 
    const [formData, setFormData] = useState({codigo: data.incocoid, host: data.incocohost, usuario: data.incocousuario, clave: data.incococlave,
                                                claveApi: data.incococlaveapi, puerto: data.incocopuerto
                                                } );

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/configuracionCorreo/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <TextValidator 
                        name={'host'}
                        value={formData.host}
                        label={'Host'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <TextValidator 
                        name={'usuario'}
                        value={formData.usuario}
                        label={'usuario'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={['required', 'isEmail']}
                        errorMessages={['Campo requerido', 'Correo no válido']}
                        type={"email"}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'clave'}
                        value={formData.clave}
                        label={'Clave'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'claveApi'}
                        value={formData.claveApi}
                        label={'Clave de la api'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'puerto'}
                        value={formData.puerto}
                        label={'Puerto'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 4}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> Actualizar
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
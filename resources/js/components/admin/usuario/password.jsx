import React, {useState} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../layout/snackBar';
import { Button, Grid, Stack } from '@mui/material';
import {LoaderModal,} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';

export default function Perfil(){
    const [formData, setFormData] = useState({ password: '', repPassword: ''});
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);    

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        let ruta = '/admin/usuario/updatePassword';
        instance.post(ruta, formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>

                <Grid item xl={2} md={2} sm={6} xs={6} >

                </Grid>

                <Grid item md={4} xl={4} sm={12} xs={12}>
                    <TextValidator
                        name={'password'}
                        value={formData.password}
                        label={'Contraseña'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        type={'password'}
                    />
                </Grid>

                <Grid item md={4} xl={4} sm={12} xs={12}>
                    <TextValidator
                        name={'repPassword'}
                        value={formData.repPassword}
                        label={'Rep - Contraseña'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        type={'password'}
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
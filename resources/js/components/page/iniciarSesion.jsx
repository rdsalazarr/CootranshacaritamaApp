import React, {useState} from 'react';
import { Grid,Card,CardContent,Button } from '@mui/material';
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator';
import { ThemeProvider } from '@mui/material/styles';
import showSimpleSnackbar from '../layout/snackBar'
import {generalTema} from "../layout/theme";
import Loader from "../layout/loader";

export default function IniciarSesion(){
    const [formData, setFormData] = useState({usuario:'', password:'' }); 
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    };

    const handleSubmit =() =>{  
        setLoader(true);
        window.axios.post('/login', formData
            ,{ headers : { crsfToken : document.querySelector('meta[name="csrf-token"]').content } } )
        .then(response => {
            let res = response.data;
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.msg, icono);
            setLoader(false);
            if(res.success){
                location.replace(res.ruta);
            } 
        }).catch(error => {
            showSimpleSnackbar(error.response.data.message, 'error');
            setLoader(false); 
        });
    }
    
    if(loader){
        return <Loader />
    }

    return (
        <ThemeProvider theme={generalTema}>
            <Card className={'cardPrincipal'}>
                <CardContent>
                    <ValidatorForm onSubmit={handleSubmit}>
                        <h1 className='titleInicio'>Iniciar sesión</h1>
                        <Grid container spacing={2}>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
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

                            <Grid item md={12} xl={12} sm={12} xs={12}>
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

                            <Grid container direction="row"  justifyContent="right">
                                <Button type={"submit"}  style={{width: '96%', marginBottom: '1em'}} >Iniciar sesión</Button>
                            </Grid>
                        </Grid>
                    
                    </ValidatorForm>
                </CardContent>
            </Card>
        </ThemeProvider>
    )
}
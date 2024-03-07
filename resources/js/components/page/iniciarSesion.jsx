import React, {useState} from 'react';
import { Grid,Card,CardContent,Button } from '@mui/material';
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator';
import QueuePlayNextIcon from '@mui/icons-material/QueuePlayNext';
import { ThemeProvider } from '@mui/material/styles';
import showSimpleSnackbar from '../layout/snackBar'
import KeyIcon from "@mui/icons-material/VpnKey";
import Person from "@mui/icons-material/Person"
import {generalTema} from "../layout/theme";
import Loader from "../layout/loader";

export default function IniciarSesion(){
    const [formData, setFormData] = useState({usuario:'', password:'' });
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}));
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
                    <ValidatorForm onSubmit={handleSubmit} id={"form-login"}>
                        <h1 className='titleInicio'>Iniciar sesión</h1>
                        <Grid container spacing={0} alignItems="center" >

                            <Grid item md={12} xl={12} sm={12} xs={12} className={'inputIcon'}>
                                <TextValidator
                                    name={'usuario'}
                                    value={formData.usuario}
                                    label={'Usuario'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 20}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    InputLabelProps={{className: "labelInput"}}
                                    onChange={handleChangeUpperCase}
                                />
                                <Person className={'icono'}/>
                            </Grid>

                            <Grid item md={12} xl={12} sm={12} xs={12} className={'inputIcon'}>
                                <TextValidator
                                    name={'password'}
                                    value={formData.password}
                                    label={'Contraseña'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 20}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    InputLabelProps={{className: "labelInput"}}
                                    onChange={handleChange}
                                    type={'password'}
                                />
                                <KeyIcon className={'icono'}/>
                            </Grid>

                            <Grid container direction="row"  justifyContent="right">
                                <Button type={"submit"} style={{width: '96%', marginBottom: '1em'}} startIcon={<QueuePlayNextIcon />}> Ingresar</Button>
                            </Grid>
                        </Grid>

                    </ValidatorForm>
                </CardContent>
            </Card>
        </ThemeProvider>
    )
}
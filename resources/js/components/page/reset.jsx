import '../../bootstrap';
import React, {useState, useEffect} from 'react';
import {createRoot} from "react-dom/client";
import {Header, FooterAdmon } from "../layout/general";
import "../../../scss/app.scss";

import { ThemeProvider } from '@mui/material/styles';
import {generalTema} from "../layout/theme";
import { Card, CardContent, Box, Grid, Button } from '@mui/material';
import { TextValidator, ValidatorForm } from 'react-material-ui-form-validator';
import Loader from "../layout/loader";
import instance from '../layout/instance';
import showSimpleSnackbar from '../layout/snackBar';

export default function Reset(){

    const [formData, setFormData] = useState({password:'', repPassword:'' }); 
    const [loader, setLoader] = useState(false);
    const [dataUsuario, setDataUsuario] = useState([]);
    
    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit =() =>{  
        setLoader(true);
        /*window.axios.post('/updatePassword', formData
            ,{ headers : { crsfToken : document.querySelector('meta[name="csrf-token"]').content } } )
        .then(response => {
            let res = response.data;
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.msg, icono);
            setLoader(false);
            if(res.success){
                
            } 
        }).catch(error => {
            showSimpleSnackbar(error.response.data.message, 'error');
            setLoader(false); 
        });*/

        instance.post('/updatePassword', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            //(res.success) ? 'success' : 'error';
            setLoader(false);
        })

    }

    const redireccionarUrl =() =>{  
        location.replace('/dashboard');
    }

    useEffect(() => {
        instance.get('/dataUsuario').then(res=>{
            setDataUsuario(res.dataUsuario);
        })
    }, []);

    if(loader){
        return <Loader />
    }

    return(
        <Box>
            <Header />

            <ThemeProvider theme={generalTema}>

                <Box className='container' style={{ margin: '8em auto'}}>
                    <Grid container spacing={3}>
                        <Grid item md={8} xl={8} sm={7} xs={12} style={{textAlign: 'justify'}}>
                            <h1>¡Bienvenido al sistema, {dataUsuario.nombreCompleto}!</h1>
                            <p> Para comenzar a utilizar el sistema de forma segura, le solicitamos que actualice su contraseña. Asegúrese de que su nueva contraseña cumpla con los siguientes requisitos:
                            </p>
                            <ul>
                                <li>Debe tener entre 8 y 20 caracteres de longitud.</li>
                                <li>Debe incluir al menos una letra mayúscula.</li>
                                <li>Debe incluir al menos una letra minúscula.</li>
                                <li>Debe contener al menos un número.</li>
                                <li>Debe incluir al menos un carácter especial, como *, #, o !.</li>
                                <li>No debe tener números ni letras consecutivas.</li>
                            </ul>
                            <p>Gracias por su colaboración. Su seguridad es nuestra prioridad. Si decide no cambiar su contraseña en este momentos, 
                                puede cerrar sesion <a href="#" onClick={()=>{location.href = '/logout'}} title='Cerrar sesión'>aquí</a>. </p> 

                        </Grid>
                        <Grid item md={4} xl={4} sm={5} xs={12}>                        
                            <Card style={{ margin: '5em auto'}}>
                                <CardContent>
                                    <ValidatorForm onSubmit={handleSubmit}>
                                        <Grid container spacing={2}>
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

                                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                                <TextValidator
                                                    name={'repPassword'}
                                                    value={formData.repPassword}
                                                    label={'Rep contraseña'}
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
                                                <Button type={"submit"}  style={{width: '96%', marginBottom: '1em'}} >Cambiar contraseña</Button>
                                            </Grid>

                                        </Grid>
                                    </ValidatorForm>
                                </CardContent>
                            </Card>
                        </Grid>
                    </Grid>
                </Box>

            </ThemeProvider>

            <FooterAdmon />
        </Box>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<Reset />);
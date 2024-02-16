import '../../bootstrap';
import React, {useEffect, useState, Fragment } from 'react';
import {Box, Grid, Button, Avatar, List, ListItem, ListItemAvatar, ListItemText,Link } from '@mui/material';
import verificarDocumentos from "../../../images/verificarDocumentos.jpg";
import CalendarMonthIcon from '@mui/icons-material/CalendarMonth';
import FileDownloadIcon from '@mui/icons-material/FileDownload';
import DirectionsIcon from '@mui/icons-material/Directions';
import FmdGoodIcon from '@mui/icons-material/FmdGood';
import { ThemeProvider } from '@mui/material/styles';
import PersonIcon from '@mui/icons-material/Person';
import {Header, Footer} from "../layout/general";
import {generalTema} from "../layout/theme";
import {createRoot} from "react-dom/client";
import instance from '../layout/instance';
import Loader from "../layout/loader";
import "../../../scss/app.scss";

export default function ServicioEspecial(){

    const [rutaDescarga, setRutaDescarga] = useState('');
    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);

    useEffect(() => {
       instance.post('/consultar/contrato/servicio/especial', {id: window.id}).then(res=>{
            setRutaDescarga('/download/planilla/servicio/especial/'+window.id);
            setData(res.data);
            setLoader(false)
        })
    }, []);

    if(loader){
        return <Loader />
    }

    const datosDocumento = (tipo, texto, icono) =>{
        return(
            <Fragment>
                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <List>
                        <ListItem>
                            <ListItemAvatar>
                                <Avatar>
                                    {icono}
                                </Avatar>
                            </ListItemAvatar>
                            <ListItemText
                                primary={tipo} 
                            />
                        </ListItem>
                    </List>
                </Grid>
                <Grid item xl={8} md={8} sm={6} xs={12}>
                    <h2>{texto}</h2>
                </Grid>
            </Fragment>
        )
    }

    return(
        <ThemeProvider theme={generalTema}>
            <Header />
            <Box className='container' style={{ margin: '8em auto'}}>
                <Grid container spacing={3} className='verificacionDocumento'>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <h1 style={{textAlign: 'center'}}>¡Bienvenido a nuestro sistema de verificación de planilla de servicio especial!</h1>
                        <Box className='borderTitulo'></Box>
                        <p style={{textAlign: 'justify'}}>Este código QR es tu llave para confirmar la autenticidad y validez de su documento. 
                            Al escanearlo, estás asegurando que este documento ha sido creado y es respaldado por nuestra empresa.<br />
                            Recuerda, la confianza y la seguridad son nuestra prioridad. Si necesitas más detalles o tienes alguna 
                            pregunta, no dudes en contactarnos. </p>
                        <p>¡Gracias por confiar en nosotros!</p>
                    </Grid>
                    <Grid item xl={6} md={6} sm={12} xs={12} style={{textAlign:'justify'}}>
                        <h2>Información encontrada:</h2>
                        <Grid container spacing={2}>
                            {datosDocumento('Responsable: ', data.nombreContratante, <PersonIcon />)}
                            {datosDocumento('Fecha inicial: ', data.coseesfechaincial, <CalendarMonthIcon />)}
                            {datosDocumento('Fecha final: ', data.coseesfechafinal, <CalendarMonthIcon />)}
                            {datosDocumento('Origen: ', data.coseesorigen, <DirectionsIcon />)}
                            {datosDocumento('Destino: ', data.coseesdestino, <FmdGoodIcon />)}
                        </Grid>

                        <Link href={rutaDescarga} ><Button type={"submit"} style={{width: '96%', marginBottom: '1em'}} startIcon={<FileDownloadIcon />} >Descargar documento</Button> </Link>

                    </Grid>
                    <Grid item xl={6} md={6} sm={12} xs={12}>
                        <img src={verificarDocumentos} style={{width: '100%', borderRadius: '10px'}}/>
                    </Grid>
                </Grid>
            </Box>
            <Footer />
        </ThemeProvider>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<ServicioEspecial />);
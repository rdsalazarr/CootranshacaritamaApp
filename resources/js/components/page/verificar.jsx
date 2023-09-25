import '../../bootstrap';
import React, {useEffect, useState, Fragment } from 'react';
import {Box, Grid, Button, Avatar, List, ListItem, ListItemAvatar, ListItemText,Link } from '@mui/material';
import ContentPasteSearchIcon from '@mui/icons-material/ContentPasteSearch';
import verificarDocumentos from "../../../images/verificarDocumentos.jpg";
import CalendarMonthIcon from '@mui/icons-material/CalendarMonth';
import MenuBookIcon from '@mui/icons-material/MenuBook';
import { ThemeProvider } from '@mui/material/styles';
import PersonIcon from '@mui/icons-material/Person';
import {Header, Footer} from "../layout/general";
import {generalTema} from "../layout/theme";
import {createRoot} from "react-dom/client";
import instance from '../layout/instance';
import Loader from "../layout/loader";
import { } from '@mui/material';
import "../../../scss/app.scss";

export default function Verificar(){
    const [loader, setLoader] = useState(true); 
    const [data, setData] = useState([]);
    const [rutaDescarga, setRutaDescarga] = useState('');
    const [rutaDocumento, setRutaDocumento] = useState('');

    useEffect(() => {
        instance.post('/consultar/documento', {id: window.id}).then(res=>{
            let data = res.data;  
            setRutaDescarga('/download/documentos/'+data.sigla+'/'+data.anio+'/'+data.rutaDocumento);
            setRutaDocumento(data.rutaDocumento);
            setData(data);
            setLoader(false);
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
                        <h1 style={{textAlign: 'center'}}>¡Bienvenido a nuestro sistema de verificación de documentos!</h1>
                        <Box className='borderTitulo'></Box>
                        <p>Este código QR es tu llave para confirmar la autenticidad y validez de su documento. 
                            Al escanearlo, estás asegurando que este documento ha sido creado y es respaldado por nuestra empresa.<br />
                            Recuerda, la confianza y la seguridad son nuestra prioridad. Si necesitas más detalles o tienes alguna 
                            pregunta, no dudes en contactarnos. </p>
                        <p>¡Gracias por confiar en nosotros!</p>  
                    </Grid>
                    <Grid item xl={6} md={6} sm={12} xs={12} style={{textAlign:'justify'}}>
                        <h2>Información encontrada:</h2>
                        <Grid container spacing={2}>
                            {datosDocumento('Tipo documental: ', data.tipoDocumento, <MenuBookIcon />)}
                            {datosDocumento('Fecha: ', data.fechaDocumento, <CalendarMonthIcon />)}
                            {datosDocumento('Consecutivo: ', data.consecutivoDocumento, <MenuBookIcon />)}
                            {(data.asunto !== null) ? datosDocumento('Asunto: ', data.asunto, <ContentPasteSearchIcon />) : null}
                            {datosDocumento('Dirigido a: ', data.nombredirigido, <PersonIcon />)}
                        </Grid>

                        {(rutaDocumento !== null)?  <Link href={rutaDescarga} ><Button type={"submit"} style={{width: '96%', marginBottom: '1em'}} >Descargar documento</Button> </Link> : null }

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
root.render(<Verificar />);
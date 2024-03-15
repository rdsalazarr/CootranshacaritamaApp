
import React, { useState, useEffect } from 'react';
import "../../../scss/general.scss";
import { Card, CardContent, Box, Grid, Avatar, Stack } from '@mui/material';
import logo from "../../../images/logoHome.png";
import instance from "./instance";

export function Header(){
    return (
        <Box className={'banner'}> 
            <Box className={'bannerSuperior'}> 
            </Box>
            <Box className={'bannerInferior'}>            
                <Box className='container'> 
                    <Grid container spacing={2}>
                        <Grid item md={2} xl={2} sm={3} xs={2}>
                            <img src={logo} alt="Imagen" />                           
                        </Grid> 

                        <Grid item md={10} xl={10} sm={9} xs={10}>
                            <h1>Cooperativa de transportadores HACARITAMA</h1>
                        </Grid> 
                    </Grid>
                </Box>
            </Box>
        </Box>
    )
}

export function Footer(){
    var fechaActual = new Date();
    var anioActual   = fechaActual.getFullYear();
    return (
        <Box className='piePagina'>
            <Box className='container'>
                <span>COOTRANSHACARITAMA | Todos los derechos reservados | Copyright © {anioActual}</span>
                <span className='implesoft'>Diseño y desarrollo <a href="http://implesoft.com/" target="_black" style={{color: '#5ab7de'}} title="Implesoft.com">Implesoft.com</a></span>
            </Box>
        </Box>
    )
}

export function HeaderAdmon(){
    const [nameUser , setNameUser] = useState('');
    const [fotoUser , setFotoUser] = useState('');
    const [mostarDatos , setMostarDatos] = useState(false);
 
     useEffect(() => {
         instance.get('/admin/consultar/informacion/usuario').then(res=>{
             if(res.success){
                setNameUser('Bienvenido, '+res.data.nombreUsuario);
                (res.data.persrutafoto !== '') ? setFotoUser('data:application/jpg;base64,'+res.fotografia) : '';
                setMostarDatos(true);
             }
         }); 
     }, []);

    return (
        <Box className={"headerAdmon"}>
            <Grid container spacing={2}>
                <Grid item md={3} xl={3} sm={2} xs={1}>
                </Grid>
                <Grid item md={5} xl={5} sm={5} xs={6}>
                    <h2>Cooperativa de transportadores HACARITAMA</h2>
                </Grid>
                <Grid item md={4} xl={4} sm={5} xs={5}>
                    {(mostarDatos) ?
                        <Box className='informacionPersonal'> 
                            <Box className={'titleUsuario'}>{nameUser}</Box>
                            <Box>
                                <Stack direction="row" spacing={2}>
                                    <Avatar src={fotoUser} className={'avatarHome'}/>
                                </Stack>
                            </Box>
                        </Box>
                    : null }
                </Grid>
            </Grid>  
        </Box>
    )
}

export function FooterAdmon(){
    var fechaActual = new Date();
    var anioActual   = fechaActual.getFullYear();
    return (
        <Box className={'footerAdmon'}>
            <div style={{borderRight: '1px solid rgb(149 144 144)'}}>
                <p>
                    <strong>Cooperativa de transportadores HACARITAMA</strong>
                </p>
            </div>
            <div style={{marginLeft: '4px'}}>
                <span>
                    <strong>Todos los derechos Reservados | Copyright  | </strong>
                    <a href='https://implesoft.com/' target="_black" style={{color: '#5ab7de'}} title="Implesoft.com">Implesoft</a> © {anioActual}
                </span>
            </div>
        </Box>
    )
}

export function TabPanel(props) {
    const {children, value, index, ...other} = props;
    return (
        <Box
            role="tabpanel"
            hidden={value !== index}
            id={`scrollable-prevent-tabpanel-${index}`}  
            aria-labelledby={`scrollable-prevent-tab-${index}`}
            {...other}
        >
            <Card> 
                <CardContent>
                    {value === index && ( children)} 
                </CardContent>
            </Card>
        </Box>
    );
}

export function Contador({tiempoInicial, onTiempoFinalizado}){
    const [contador, setContador] = useState(tiempoInicial);
  
    useEffect(() => {
      const intervalo = setInterval(() => {
        setContador((prevContador) => prevContador - 1);
      }, 1000);
  
      return () => {
        clearInterval(intervalo);
      };
    }, []);
  
    useEffect(() => {
      if (contador === 0) {
        onTiempoFinalizado();
        clearInterval(intervalo);
      }
    }, [contador]); 
  
    return ( 
        <>{contador} </> 
    );
}

export function FormatearNumero({numero}){
    const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
    return Number(numero).toLocaleString('es-CO', opciones);
}
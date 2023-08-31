
import React, { useState, useEffect } from 'react';
import "../../../scss/general.scss";
import { Card, CardContent, Box, Grid } from '@mui/material';
import logo from "../../../images/logoHome.png";

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
    return (
        <Box className='piePagina'>
            <Box className='container'>
                <span>COOTRANSHACARITAMA | Todos los derechos reservados | Copyright © 2023</span>
                <span className='implesoft'>Diseño y desarrollo <a href="http://implesoft.com/" target="_black" style={{color: '#5ab7de'}} title="Implesoft.com">Implesoft.com</a></span>
            </Box>
        </Box>
    )
}

export function HeaderAdmon(){
        return (
        <Box className={"headerAdmon"}>
            <Box className='colAdmonLeft'>
                <h2>Cooperativa de transportadores HACARITAMA</h2>
            </Box>
            <Box className='colAdmonRight'>
                <img src={logo} alt="logo"  />
            </Box> 
        </Box>
    )
}

export function FooterAdmon(){
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
                    <a href='https://implesoft.com/' target="_black" style={{color: '#5ab7de'}} title="Implesoft.com">Implesoft</a> © 2023
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
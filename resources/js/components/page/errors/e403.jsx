import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";
import imagen from "../../../../images/errors/accesoProhibido.svg";
import {Header, Footer} from "../../layout/general";
import HomeIcon from '@mui/icons-material/Home';
import { Box, Grid } from '@mui/material';
import "../../../../scss/app.scss";
import "../../../../scss/errors.scss";

export default function E403(){

    return(
        <Box>
            <Header />

            <Box className='container paginaError'>
                <Grid container spacing={2}>
                    <Grid item xl={6} md={7} sm={12} xs={12} className='centrarContenido'>
                        <h1>¡Acceso no autorizado o prohibido!</h1>
                        <p>Lo sentimos, no cuentas con la autorización necesaria para acceder a esta página o el acceso al recurso en el servidor está denegado. 
                            Por favor, contacta al administrador del sistema para obtener ayuda.</p>
                        <a href='/'>
                            <HomeIcon className='icono' />
                        </a>
                    </Grid>
                    <Grid item xl={6} md={5} sm={12} xs={12} style={{textAlign: 'center'}}> 
                        <img src={imagen} alt="Imagen" onClick={()=>{location.href = '/'}} />
                    </Grid>
                </Grid>
            </Box>

            <Footer />
        </Box>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<E403 />);
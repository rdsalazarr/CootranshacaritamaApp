import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";
import imagen from "../../../../images/errors/errorServidor.svg";
import {Header, Footer} from "../../layout/general";
import { Box, Grid } from '@mui/material';
import "../../../../scss/app.scss";
import "../../../../scss/errors.scss";

export default function E500(){

    return(
        <Box>
            <Header /> 

            <Box className='container paginaError'>
                <Grid container spacing={2}>
                    <Grid item xl={6} md={7} sm={12} xs={12} className='centrarContenido'>
                        <h1>¡Error en el servidor!</h1>
                        <h3>Lamentablemente, hemos experimentado un problema en nuestros servidores. Nos disculpamos por las molestias que esto pueda haber causado. Nuestro equipo técnico está trabajando arduamente para resolver el inconveniente lo antes posible. Le recomendamos intentar nuevamente más tarde. Gracias por su comprensión y paciencia</h3>
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
root.render(<E500 />);
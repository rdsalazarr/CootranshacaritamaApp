import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";
import imagen from "../../../../images/errors/error405.svg";
import {Header, Footer} from "../../layout/general";
import HomeIcon from '@mui/icons-material/Home';
import { Box, Grid } from '@mui/material';
import "../../../../scss/app.scss";
import "../../../../scss/errors.scss";

export default function E405(){
    return(
        <Box>
            <Header />

            <Box className='container paginaError'>
                <Grid container spacing={2}>
                    <Grid item xl={6} md={7} sm={12} xs={12} className='centrarContenido'>
                        <h1>Error 405</h1>
                        <h3>¡Método no sorportado! </h3>
                        <p>Lamentamos informarte que se ha producido un error en tu solicitud. Hemos detectado un 
                            código de estado "405 - Método no permitido", lo que significa que el método que estás 
                            intentando utilizar no está permitido en este contexto.
                        </p>

                        <p>Este error generalmente se produce cuando intentas acceder a un recurso utilizando un método 
                            HTTP incorrecto o no válido. Es posible que hayas utilizado un método no admitido para esta 
                            acción específica, o puede haber alguna restricción en el servidor que impida el uso del método solicitado.
                        </p>

                        <p>Te recomendamos verificar cuidadosamente la solicitud que estás realizando y asegurarte de 
                            utilizar el método HTTP correcto. Si necesitas asistencia adicional, por favor, no dudes en 
                            contactar a nuestro equipo de soporte, quienes estarán encantados de ayudarte a resolver este problema.
                        </p>
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
root.render(<E405 />);

import React from 'react';
import imagen from "../../../../../images/errors/accesoNoAutorizado.svg";
import { Box, Grid } from '@mui/material';
import "../../../../../scss/errors.scss";

export default function CajaNoAbierta({usuario}){

    return (
        <Box className='container paginaError'>
            <Grid container spacing={2}>
                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <img src={imagen} alt="Imagen" />
                </Grid>

                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <h6>¡No se ha abierto una caja para su usuario en la fecha de hoy!</h6>
                    <p>Estimado usuario, <b>{usuario}</b>:</p>
                    <p>Lamentamos informarle que no es posible proceder con el cierre de la caja en este momento, 
                       ya que no se ha registrado una apertura previa. Le recomendamos abrir una caja antes de intentar el cierre.</p>
                </Grid>

            </Grid>
        </Box>
    )
}
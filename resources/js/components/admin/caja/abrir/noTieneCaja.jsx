import React from 'react';
import imagen from "../../../../../images/errors/accesoNoAutorizado.svg";
import { Box, Grid } from '@mui/material';
import "../../../../../scss/errors.scss";

export default function NoTieneCaja({usuario}){

    return (
        <Box className='container paginaError'>
            <Grid container spacing={2}>
                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <img src={imagen} alt="Imagen" />
                </Grid>

                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <h6>¡No se ha asignado una caja para su usuario!</h6>
                    <p>Estimado usuario, <b>{usuario}</b>:</p>
                    <p>A la fecha actual, no se ha asignado una caja para su usuario.
                        Le recomendamos ponerse en contacto con el administrador del sistema
                        para solicitar la asignación de una caja. De esta manera, 
                        podrá registrar los movimientos financieros y cualquier actividad de carácter institucional de manera adecuada.</p>
                </Grid>

            </Grid>
        </Box>
    )
}
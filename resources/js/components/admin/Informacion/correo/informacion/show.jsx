import React from 'react';
import {Grid, Box } from '@mui/material';

export default function Show({data}){

    return (
        <Grid container spacing={2}>

            <Grid item xl={4} md={4} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Nombre</label>
                    <span>{data.innoconombre}</span>
                </Box>
            </Grid>

            <Grid item xl={4} md={4} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Asunto</label>
                    <span>{data.innocoasunto}</span>
                </Box>
            </Grid>

            <Grid item xl={2} md={2} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Pie página</label>
                    <span>{data.enviarPiePagina}</span>
                </Box>
            </Grid>

            <Grid item xl={2} md={2} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Enviar copia</label>
                    <span>{data.enviarCopia}</span>
                </Box>
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Contenido del correo</label>
                    <span dangerouslySetInnerHTML={{__html: data.innococontenido}} />
                </Box>
            </Grid>
        </Grid>
    )
}
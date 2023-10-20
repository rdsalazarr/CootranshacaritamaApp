import React from 'react';
import {Grid, Box } from '@mui/material';

export default function Show({data}){

    return (
        <Grid container spacing={2}>

            <Grid item xl={4} md={4} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Nombre</label>
                    <span>{data.ingpdfnombre}</span>
                </Box>
            </Grid>

            <Grid item xl={4} md={4} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Título</label>
                    <span>{data.ingpdftitulo}</span>
                </Box>
            </Grid>            

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Contenido del PDF</label>
                    <span dangerouslySetInnerHTML={{__html: data.ingpdfcontenido}} /> 
                </Box>
            </Grid>
        </Grid>
    )
}
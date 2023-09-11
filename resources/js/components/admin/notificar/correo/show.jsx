import React, {useState} from 'react';
import {Grid, Box } from '@mui/material';

export default function Show({data}){

     const [formData, setFormData] = useState(
                    {
                        codigo: data.innocoid, nombre: data.innoconombre, asunto: data.innocoasunto, contenido: data.innococontenido,
                        piePagina: data.piePagina, copia: data.enviarCopia
                    } ); 

    return (
        <Grid container spacing={2}>

            <Grid item xl={4} md={4} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Nombre</label>
                    <span>{formData.nombre}</span>
                </Box>
            </Grid>

            <Grid item xl={4} md={4} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Asunto</label>
                    <span>{formData.asunto}</span>
                </Box>
            </Grid>

            <Grid item xl={2} md={2} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Pie página</label>
                    <span>{formData.enviarPiePagina}</span>
                </Box>
            </Grid>

            <Grid item xl={2} md={2} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Enviar copia</label>
                    <span>{formData.copia}</span>
                </Box>
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Contenido del correo</label>
                    <span dangerouslySetInnerHTML={{__html: formData.contenido}} /> 
                </Box>
            </Grid>
        </Grid>
    )
}
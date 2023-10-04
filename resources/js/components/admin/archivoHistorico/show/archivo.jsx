import React from 'react';
import { Box, Grid } from '@mui/material';

export default function Archivo({data}){

    return (
        <Grid container spacing={2}>
            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='divisionFormulario'>
                    Información del registro del archivo histórico
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Fecha de registro</label>
                    <span>{data.fechaRegistro}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Usuario que registró</label>
                    <span>{data.nombreUsuario}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tipo documental</label>
                    <span>{data.tipoDocumental}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Estante</label>
                    <span>{data.estante}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Caja</label>
                    <span>{data.caja}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Carpeta</label>
                    <span>{data.carpeta}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Fecha del documento</label>
                    <span>{data.fechaDocumento}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Números de folios</label>
                    <span>{data.numeroFolio}</span>
                </Box>
            </Grid>
            
            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Asunto</label>
                    <span>{data.asuntoDocumento}</span>
                </Box>
            </Grid>

            <Grid item xl={2} md={2} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Números de tomos</label>
                    <span>{data.tomoDocumento}</span>
                </Box>
            </Grid>

            <Grid item xl={2} md={2} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Código documental</label>
                    <span>{data.codigoDocumental}</span>
                </Box>
            </Grid>

            <Grid item xl={4} md={4} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Entidad remitente</label>
                    <span>{data.entidadRemitente}</span>
                </Box>
            </Grid>

            <Grid item xl={4} md={4} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Entidad productora</label>
                    <span>{data.entidadProductora}</span>
                </Box>
            </Grid>

            <Grid item xl={6} md={6} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Resumen del documento</label>
                    <span>{data.resumenDocumento}</span>
                </Box>
            </Grid>

            <Grid item xl={6} md={6} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Observación del documento</label>
                    <span>{data.observacion}</span>
                </Box>
            </Grid>

        </Grid>
    )
}
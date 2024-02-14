import React from 'react';
import { Box, Grid } from '@mui/material';

export default function Radicado({data}){

    return (

        <Grid container spacing={2}>
            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='divisionFormulario'>
                    Información del registro del documento entrante
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Requiere respuesta</label>
                    <span>{data.requiereRespuesta}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Fecha de radicado</label>
                    <span>{data.fechaRadicado}</span>
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
                    <label>Fecha de llegada</label>
                    <span>{data.fechaLlegadaDocumento}</span>
                </Box>
            </Grid>

            {(data.fechaMaxRespuesta !== null) ?
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Fecha máxima de respuesta</label>
                        <span>{data.fechaMaxRespuesta}</span>
                    </Box>
                </Grid>
            : null}

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Consecutivo</label>
                    <span>{data.consecutivo}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Usuario que radicó</label>
                    <span>{data.usuario}</span>
                </Box>
            </Grid> 

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Departamento</label>
                    <span>{data.departamento}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Municipio</label>
                    <span>{data.municipio}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Dependencia destino</label>
                    <span>{data.dependencia}</span>
                </Box>
            </Grid>
            
            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Persona que entrega el documento</label>
                    <span>{data.personaEntregaDocumento}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tipo de medio</label>
                    <span>{data.tipoMedio}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>¿Tiene copia? </label>
                    <span>{data.tieneCopia}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>¿Tiene anexos?</label>
                    <span>{data.tieneAnexos}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Estado actual</label>
                    <span>{data.estadoActual}</span>
                </Box>
            </Grid>

            {(data.descripcionAnexos !== null) ?
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Descripción de los anexos</label>
                        <span>{data.descripcionAnexos}</span>
                    </Box>
                </Grid>
            : null }

            {(data.observacionGeneral !== null)?
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Observación general</label>
                        <span>{data.observacionGeneral}</span>
                    </Box>
                </Grid>
            : null }

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Motivo de la solicitud</label>
                    <span className="longText">{data.descripcion}</span>
                </Box>
            </Grid>

        </Grid>
    )
}
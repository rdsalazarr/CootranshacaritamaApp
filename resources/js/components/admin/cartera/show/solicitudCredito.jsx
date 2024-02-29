import React from 'react';
import {Grid, Box} from '@mui/material';

export default function SolicitudCredito({data, aprobada = false}){

    return (
        <Grid container spacing={2}>

            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Información de la solicitud de crédito {(aprobada) ? 'aprobada' : null}
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Fecha de registro</label>
                    <span>{data.fechaSolicitud}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Estado actual</label>
                    <span>{data.estadoActual}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Línea de crédito</label>
                    <span>{data.lineaCredito}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Valor solicitado</label>
                    <span className='textoRojo'>{data.valorSolicitado}</span>
                </Box>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Destino del crédito</label>
                    <span>{data.destinoCredito}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tasa nominal</label>
                    <span>{data.tasaNominal}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Números de cuota </label>
                    <span>{data.numerosCuota}</span>
                </Box>
            </Grid>

            {(data.observacionGeneral !== null) ?
                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Observación general</label>
                        <span>{data.observacionGeneral}</span>
                    </Box>
                </Grid>
            : null}
        </Grid>
    )
}
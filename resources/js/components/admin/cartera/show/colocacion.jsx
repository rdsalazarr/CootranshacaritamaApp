import React from 'react';
import {Grid, Box} from '@mui/material';

export default function Colocacion({data}){

    return (
        <Grid container spacing={2}>
            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Información del crédito 
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Usuario que desembolsó</label>
                    <span>{data.nombreUsuario}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Fecha de desembolso</label>
                    <span>{data.fechaDesembolso}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Número de pagaré</label>
                    <span className='textoRojo'>{data.numeroPagare}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Valor desembolsado</label>
                    <span className='textoRojo'>{data.valorDesembolsado}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tasa nominal</label>
                    <span>{data.tasaNominal} %</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Números de cuota</label>
                    <span>{data.numeroCuota}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Estado actual</label>
                    <span>{data.estadoActual}</span>
                </Box>
            </Grid>
       </Grid>
    )
}
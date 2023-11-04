import React from 'react';
import {Grid, Box} from '@mui/material';

export default function Asociado({data}){

    return (
        <Grid container spacing={2}>

            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Información de asociado
                </Box>
            </Grid>

            <Grid item xl={10} md={10} sm={12} xs={12}>
                <Grid container spacing={2}>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Tipo identificación</label>
                            <span>{data.tipoIdentificacion}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Documento</label>
                            <span>{data.documento}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Primer nombre</label>
                            <span>{data.primerNombre}</span>
                        </Box>
                    </Grid>

                    {(data.segundoNombre !== null) ?
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Segundo nombre</label>
                                <span>{data.segundoNombre}</span>
                            </Box>
                        </Grid>
                    : null}

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Primer apellido</label>
                            <span>{data.primerApellido}</span>
                        </Box>
                    </Grid>

                    {(data.segundoApellido !== null) ?
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Segundo apellido</label>
                                <span>{data.segundoApellido}</span>
                            </Box>
                        </Grid>
                    : null}

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Fecha nacimiento</label>
                            <span>{data.fechaNacimiento}</span>
                        </Box>
                    </Grid> 
                    
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Dirección</label>
                            <span>{data.direccion}</span>
                        </Box>
                    </Grid>

                    {(data.correo !== null) ?
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Correo</label>
                                <span>{data.correo}</span>
                            </Box>
                        </Grid>
                    : null}

                    {(data.telefonoFijo !== null) ?
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Teléfono fijo</label>
                                <span>{data.telefonoFijo}</span>
                            </Box>
                        </Grid>
                    : null}

                    {(data.numeroCelular !== null) ?
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Número de celular</label>
                                <span>{data.numeroCelular}</span>
                            </Box>
                        </Grid>
                    : null}

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Fecha ingreso como asociado</label>
                            <span>{data.fechaIngresoAsociado}</span>
                        </Box>
                    </Grid>

                </Grid>
            </Grid>

            <Grid item xl={2} md={2} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Fotografia</label>
                    <Box className='fotografia' style={{marginTop: '0.6em'}}>
                        <img src={data.showFotografia} ></img>
                    </Box>
                </Box>
            </Grid>

        </Grid>
    )
}
import React from 'react';
import {Grid, Box} from '@mui/material';

export default function Show({data}){

    return (
        <Grid container spacing={2}>        
            <Grid item xl={10} md={10} sm={12} xs={12}>
                <Grid container spacing={2}>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Vehículo</label>
                            <span>{data.nombreVehiculo}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={5} md={5} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Asociado</label>
                            <span>{data.nombreAsociado}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={2} md={2} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Fecha inicial</label>
                            <span>{data.vehsusfechainicialsuspencion}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={2} md={2} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Fecha final</label>
                            <span>{data.vehsusfechafinalsuspencion}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='frmTexto'>
                            <label>Motivo</label>
                            <span>{data.vehsusmotivo}</span>
                        </Box>
                    </Grid>                    

                </Grid>
            </Grid>
        </Grid>
    )
}
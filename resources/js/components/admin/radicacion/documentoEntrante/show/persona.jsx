import React, {useState, Fragment} from 'react';

import { Box, Grid  } from '@mui/material';

export default function Persona({data}){
    const [esEmpresa, setEsEmpresa] = useState(data.esEmpresa);

    return (

        <Grid container spacing={2}>
            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='divisionFormulario'>
                    Información de la persona
                </Box>
            </Grid>   

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tipo de identificación</label>
                    <span>{data.tipoIdentificacion}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>{(esEmpresa)? 'NIT' : 'Número de identificación'}</label>
                    <span>{data.numeroIdentificacion}</span>
                </Box>
            </Grid>      

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>{(esEmpresa)? 'Razón social' : 'Primer nombre'}</label>
                    <span>{data.primerNombre}</span>
                </Box>
            </Grid>

            {(!esEmpresa)?
                <Fragment>
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Segundo nombre</label>
                            <span>{data.segundoNombre}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Primer apellido</label>
                            <span>{data.primerApellido}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Segundo apellido</label>
                            <span>{data.segundoApellido}</span>
                        </Box>
                    </Grid>

                </Fragment>
            : null} 

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Dirección</label>
                    <span>{data.direccionFisica}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Correo electrónico</label>
                    <span>{data.direccionElectronica}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Número de contacto</label>
                    <span>{data.numeroContacto}</span>
                </Box>
            </Grid>

            {(data.codigoDocumental !== undefined) ?
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Código documental de la empresa</label>
                        <span>{data.codigoDocumental}</span>
                    </Box>
                </Grid>
            : null }

        </Grid>
    )
}
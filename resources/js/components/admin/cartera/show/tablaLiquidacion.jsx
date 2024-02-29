import React from 'react';
import {Table, TableHead, TableBody, TableRow, TableCell, Grid, Box} from '@mui/material';
import CloudDownloadIcon from '@mui/icons-material/CloudDownload';

export default function TablaLiquidacion({liquidacion}){

    return (
        <Grid container spacing={2}>

            <Grid item xs={12} sm={12} md={12} xl={12} >
                <Box className='divisionFormulario'>
                    Tabla de liquidación de los pagos
                </Box>
            </Grid>

            <Grid item xs={12} sm={12} md={12} xl={12}>

                <Box sx={{maxHeight: '20em', overflow:'auto'}}>
                    <Table key={'tableCambioEstado'} className={'tableAdicional'} style={{width: '100%', margin: 'auto'}}>
                        <TableHead>
                            <TableRow>
                                <TableCell style={{width: '5%'}}>Cuota</TableCell>
                                <TableCell style={{width: '12%'}}>Valor cuota</TableCell>
                                <TableCell style={{width: '10%'}}>Fecha vencimiento</TableCell>
                                <TableCell style={{width: '10%'}}>Comprobante</TableCell>
                                <TableCell style={{width: '9%'}}>Fecha pago</TableCell>
                                <TableCell style={{width: '9%'}}>Valor pagado</TableCell> 
                                <TableCell style={{width: '9%'}}>Saldo capital</TableCell>
                                <TableCell style={{width: '9%'}}>Capital pagado</TableCell>
                                <TableCell style={{width: '9%'}}>Intereses pagado</TableCell>
                                <TableCell style={{width: '9%'}}>Intereses devuelto</TableCell>
                                <TableCell style={{width: '9%'}}>Intereses mora</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {
                                liquidacion.map((liq, a) => {
                                    return(
                                        <TableRow key={'rowLiq-' +a}>
                                            <TableCell className='cellCenter'>
                                                <span>{liq['numeroCuota']}</span>
                                            </TableCell>
                                            <TableCell>
                                                <span>{liq['valorCuota']}</span>
                                            </TableCell>
                                            <TableCell>
                                                <span>{liq['fechaVencimiento']}</span>
                                            </TableCell>
                                            <TableCell>
                                                <span>{liq['numeroComprobante']}</span>
                                            </TableCell>
                                            <TableCell>
                                                <span>{liq['fechaPago']}</span>
                                            </TableCell>
                                            <TableCell>
                                                <span>{liq['valorPagado']}</span>
                                            </TableCell> 
                                            <TableCell>
                                                <span>{liq['saldoCapital']}</span>
                                            </TableCell>
                                            <TableCell>
                                                <span>{liq['capitalPagado']}</span>
                                            </TableCell>
                                            <TableCell>
                                                <span>{liq['interesPagado']}</span>
                                            </TableCell>
                                            <TableCell>
                                                <span>{liq['interesDevuelto']}</span>
                                            </TableCell>
                                            <TableCell>
                                                <span>{liq['interesMora']}</span>
                                            </TableCell>
                                        </TableRow>
                                    );
                                })
                            }
                        </TableBody>
                    </Table>
                </Box>

                <Box style={{float: 'right', color: '#747171'}}>
                    Descargar liquidación <CloudDownloadIcon />
                </Box>
     
            </Grid>
       </Grid>
    )
}
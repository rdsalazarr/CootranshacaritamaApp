
import React from 'react';
import {Table, TableHead, TableBody, TableRow, TableCell, Grid, Box} from "@mui/material";

export default function Trazabilidad({mensaje, data}){
    
    return (
        <Grid container spacing={2}>
            <Grid item xs={12} sm={12} md={12} xl={12} >
                <Box className='divisionFormulario'>
                    {mensaje}
                </Box>
            </Grid>

            <Grid item xs={12} sm={12} md={12} xl={12}>
                <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                    <Table key={'tableCambioEstado'} className={'tableAdicional'} style={{width: '90%', margin: 'auto'}}>
                        <TableHead>
                            <TableRow>
                                <TableCell style={{width: '5%'}}>Ítem</TableCell>
                                <TableCell style={{width: '15%'}}>Fecha y hora</TableCell>
                                <TableCell style={{width: '15%'}}>Estado</TableCell>
                                <TableCell style={{width: '20%'}}>Usuario</TableCell>
                                <TableCell style={{width: '45%'}}>Observación</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {
                                data.map((cambioEstado, a) => { 
                                    let i = a + 1;
                                    return(
                                        <TableRow key={'rowCE-' +a}>
                                            <TableCell>
                                                <p>{i}</p>
                                            </TableCell>
                        
                                            <TableCell>
                                                <p>{cambioEstado['fecha']}</p>
                                            </TableCell>
                        
                                            <TableCell>
                                                <p>{cambioEstado['estado']}</p>
                                            </TableCell>
                        
                                            <TableCell>
                                                <p>{cambioEstado['nombreUsuario']}</p>
                                            </TableCell>
                        
                                            <TableCell>
                                                <p>{cambioEstado['observacion']}</p>
                                            </TableCell>
                                        
                                        </TableRow> 
                                    );
                                })
                            }
                        </TableBody>
                    </Table>
                </Box>
            </Grid>
        </Grid>
    )
}
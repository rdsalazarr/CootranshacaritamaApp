
import React from 'react';
import {Table, TableHead, TableBody, TableRow, TableCell, Grid, Box} from "@mui/material";

export default function Trazabilidad({data}){
    
    return (
        <Grid container spacing={2}>
            <Grid item xs={12} sm={12} md={12} xl={12} >
                <Box className='divisionFormulario'>
                    Cambio de estado de la radicación del documento externo
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
                                                {i}
                                            </TableCell>
                        
                                            <TableCell>
                                                {cambioEstado['fecha']}
                                            </TableCell>
                        
                                            <TableCell>
                                                {cambioEstado['estado']}
                                            </TableCell>
                        
                                            <TableCell>
                                                {cambioEstado['nombreUsuario']}
                                            </TableCell>
                        
                                            <TableCell>
                                                {cambioEstado['observacion']}
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
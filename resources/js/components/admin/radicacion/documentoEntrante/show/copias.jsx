
import React from 'react';
import {Table, TableHead, TableBody, TableRow, TableCell, Grid, Box} from "@mui/material";

export default function Copias({data}){
    
    return (
        <Grid container spacing={2}>
            <Grid item xs={12} sm={12} md={12} xl={12} >
                <Box className='divisionFormulario'>
                    Lista de dependencias a las que se les ha enviado una copia del documento
                </Box>
            </Grid>

            <Grid item xs={12} sm={12} md={12} xl={12}>
                <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                    <Table key={'tableCambioEstado'}  className={'tableAdicional'}  style={{width: '80%', margin: 'auto'}}>
                        <TableHead>
                            <TableRow>
                                <TableCell style={{width: '5%'}}>Ítem</TableCell>
                                <TableCell style={{width: '45%'}}>Dependencias</TableCell>
                                <TableCell style={{width: '25%'}}>Recibido por</TableCell>
                                <TableCell style={{width: '25%'}}>Fecha</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {
                                data.map((copia, a) => { 
                                    let i = a + 1;
                                    return(
                                        <TableRow key={'rowCop-' +a}>
                                            <TableCell>
                                                {i}
                                            </TableCell>
                        
                                            <TableCell>
                                                {copia['dependencia']}
                                            </TableCell>

                                            <TableCell>
                                                {copia['nombreUsuario']}
                                            </TableCell>

                                            <TableCell>
                                                {copia['fecha']}
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
import React, {useState} from 'react';
import VisualizarPdf from '../cambiarEstado/desembolsar/visualizarPdf';
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import { ModalDefaultAuto } from '../../../layout/modal';
import {Table, TableHead, TableBody, TableRow, TableCell, Grid, Box, Fab} from '@mui/material';

export default function Colocacion({data, liquidacion}){

    const tituloModal       = ['Generar PDF de la solicitud crédito','Generar PDF de la carta intrucciones','Generar PDF del formato', 'Generar PDF del pagaré'];
    const urlModal          = ['SOLICITUDCREDITO','CARTAINSTRUCCIONES','FORMATO', 'PAGARE'];
    const [modal, setModal] = useState({open: false, titulo:'', url: ''});

    const abrirModal = (tipo) =>{
        setModal({open: true, titulo: tituloModal[tipo], url: urlModal[tipo]});
    }

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
                    <span>{data.tasaNominal}</span>
                </Box>
            </Grid>
            
            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Número de cuota</label>
                    <span>{data.numeroCuota}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Estado actual</label>
                    <span>{data.estadoActual}</span>
                </Box>
            </Grid>

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
                                <TableCell style={{width: '12%'}}>Fecha vencimiento</TableCell>
                                <TableCell style={{width: '11%'}}>Comprobante</TableCell>
                                <TableCell style={{width: '12%'}}>fecha pago</TableCell>
                                <TableCell style={{width: '10%'}}>Valor pagado</TableCell> 
                                <TableCell style={{width: '10%'}}>Saldo capital</TableCell>
                                <TableCell style={{width: '10%'}}>Capital pagado</TableCell>
                                <TableCell style={{width: '10%'}}>Intereses pagado</TableCell>
                                <TableCell style={{width: '8%'}}>Intereses mora</TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {
                                liquidacion.map((liq, a) => {
                                    return(
                                        <TableRow key={'rowLiq-' +a}>
                                            <TableCell>
                                                <p>{liq['numeroCuota']}</p>
                                            </TableCell>
                                            <TableCell>
                                                <p>{liq['valorCuota']}</p>
                                            </TableCell>
                                            <TableCell>
                                                <p>{liq['fechaVencimiento']}</p>
                                            </TableCell>
                                            <TableCell>
                                                <p>{liq['numeroComprobante']}</p>
                                            </TableCell>
                                            <TableCell>
                                                <p>{liq['fechaPago']}</p>
                                            </TableCell>
                                            <TableCell>
                                                <p>{liq['valorPagado']}</p>
                                            </TableCell> 
                                            <TableCell>
                                                <p>{liq['saldoCapital']}</p>
                                            </TableCell>
                                            <TableCell>
                                                <p>{liq['capitalPagado']}</p>
                                            </TableCell>
                                            <TableCell>
                                                <p>{liq['interesPagado']}</p>
                                            </TableCell>
                                            <TableCell>
                                                <p>{liq['interesMora']}</p>
                                            </TableCell>
                                        </TableRow> 
                                    );
                                })
                            }
                        </TableBody>
                    </Table>
                </Box>
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Grid container direction="row" justifyContent="right" style={{marginTop: '0.5em'}}>
                    <Fab variant="extended" size="medium" className={'btnRojo'} onClick={() => {abrirModal(0)}}>
                        <PictureAsPdfIcon sx={{ mr: 1 }}  />
                        Solicitud crédito
                    </Fab>

                    <Fab variant="extended" size="medium" className={'btnRojo'} onClick={() => {abrirModal(1)}}>
                        <PictureAsPdfIcon sx={{ mr: 1 }} />
                        Carta intrucciones
                    </Fab>

                    <Fab variant="extended" size="medium" className={'btnRojo'} onClick={() => {abrirModal(2)}}>
                        <PictureAsPdfIcon sx={{ mr: 1 }} />
                        Formato 
                    </Fab>

                    <Fab variant="extended" size="medium" className={'btnRojo'} onClick={() => {abrirModal(3)}}>
                        <PictureAsPdfIcon sx={{ mr: 1 }} />
                        Pagaré
                    </Fab> 
                </Grid>
            </Grid>

            <ModalDefaultAuto
                title={modal.titulo}
                content={<VisualizarPdf data={data} url={modal.url}/>}
                close  ={() =>{setModal({open : false, titulo:'', url: ''})}}
                tam    ={'mediumFlot'}
                abrir  ={modal.open}
            />

       </Grid>
    )
}
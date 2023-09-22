import React, {useState, useEffect} from 'react';
import {Table, TableHead, TableBody, TableRow, TableCell, Grid, Box} from "@mui/material";
import instancePdf from '../../layout/instancePdf';
import {LoaderModal} from "../../layout/loader";
import instance from '../../layout/instance';

export default function Trazabilidad({idProceso, idDocumento, ruta}){

    const [loader, setLoader] = useState(false);
    const [pdf, setPdf] = useState(); 
    const [cambioEstados, setCambioEstados] = useState([]);

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/producion/documental/trazabilidad/'+ruta, {codigo: idProceso}).then(res=>{
            setCambioEstados(res.cambioEstados);
            instancePdf.post('/admin/producion/documental/'+ruta+'/visualizar/PDF', {codigo: idDocumento}).then(res=>{
                let url = 'data:application/pdf;base64,'+res.data;
                setPdf(url);
                setLoader(false);
            });
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return ( 
        <Grid container spacing={2} style={{display: 'flex',  justifyContent: 'space-between'}}>
            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Documento en formato PDF
                </Box>
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
            <iframe style={{width: '100%', height: '22em', border: 'none'}} 
            src={pdf} />
            </Grid>

            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Trazabilidd del documento
                </Box>
            </Grid>

            <Grid item xs={12} sm={12} md={12} xl={12}>
               <Box sx={{maxHeight: '20em', overflow:'auto'}}>
                    <Table className={'tableAdicional'} style={{ marginTop: '5px'}}>
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
                                cambioEstados.map((cambioEstado, a) => { 
                                    let i = a + 1;
                                    return(
                                        <TableRow key={'rowCE-' +a}>
                                            <TableCell>
                                                <p>{i}</p>
                                            </TableCell>
  
                                            <TableCell>
                                                <p>{cambioEstado['codpcefechahora']}</p>
                                            </TableCell>

                                            <TableCell>
                                                <p>{cambioEstado['tiesdonombre']}</p>
                                            </TableCell>

                                            <TableCell>
                                                <p>{cambioEstado['nombreUsuario']}</p>
                                            </TableCell>

                                            <TableCell>
                                                <p>{cambioEstado['codpceobservacion']}</p>
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
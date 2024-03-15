import React, {useState} from 'react';
import {Table, TableHead, TableBody, TableRow, TableCell, Grid, Box, Link} from "@mui/material";
import CloudDownloadIcon from '@mui/icons-material/CloudDownload';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import { EliminarAdjunto } from '../../layout/modalFijas';
import { ModalDefaultAuto  } from '../../layout/modal';

export default function CertificadoConductor({data, eliminar, cantidadAdjunto}){

    const [dataFiles, setDataFiles] = useState(data);
    const [dataModal, setDataModal] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);

    const cerrarModal = () =>{
        setAbrirModal(false);
    }

    const eliminarAdjunto = (id, codigo, rutaFile, documento) =>{
        setDataModal({id: id, codigo:codigo, rutaFile: rutaFile, documento: documento});
        setAbrirModal(true); 
    }

    const eliminarFilasAdjunto = (id) =>{
        let newDataFiles = []; 
        dataFiles.map((res,i) =>{
            if(i != id){
                newDataFiles.push({
                    id:              res.id,
                    nombreOriginal:  res.nombreOriginal,
                    nombreEditado:   res.nombreEditado,
                    rutaDescargar:   res.rutaDescargar,
                    rutaCertificado: res.rutaCertificado,
                    documento:       res.documento
                });
            }
        })
        setDataFiles(newDataFiles);
    }

    return (
        <Grid container spacing={2}>
            <Grid item xs={12} sm={12} md={12} xl={12}>
                <Box className='frmDivision'>
                    Certificados cargados al conductor
                </Box>
            </Grid>

            <Grid item xs={12} sm={12} md={12} xl={12}>
               <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                    <Table className={'tableAdicional'} style={{width: '70%', margin: 'auto'}}>
                        <TableHead>
                            <TableRow>
                                <TableCell style={{width: '5%'}}>Ítem</TableCell>
                                <TableCell>Nombre</TableCell> 
                                <TableCell className='cellCenter'>Descargar</TableCell>
                                {(eliminar) ?  <TableCell className='cellCenter'>Eliminar</TableCell> : null}
                            </TableRow>
                        </TableHead>
                        <TableBody>

                        {dataFiles.map((anexos, a) => {
                            let i = a + 1;
                            let rutaAdjunto = '/download/certificado/conductor/'+anexos['documento']+'/'+anexos['rutaCertificado'];
                            return(
                                    <TableRow key={'rowAne-' + a + anexos['id']}>
                                        <TableCell>
                                            {i}
                                        </TableCell>

                                        <TableCell>
                                            {anexos['nombreOriginal']}
                                        </TableCell>

                                        <TableCell className='cellCenter'> 
                                            <Link href={rutaAdjunto} ><CloudDownloadIcon className={'iconoDownload'}/></Link>
                                        </TableCell>

                                        {(eliminar) ?  <TableCell className='cellCenter'>
                                            <DeleteForeverIcon onClick={() => {eliminarAdjunto(a, anexos['id'], anexos['rutaCertificado'], anexos['documento']);}} className={'iconoDownload'}/>
                                            </TableCell> : null}
                                    </TableRow> 
                                );
                            })
                        }
                        </TableBody>
                    </Table>
                </Box>
            </Grid>

            <ModalDefaultAuto
                title={""}
                content={<EliminarAdjunto data={dataModal} eliminarFilasAdjunto={eliminarFilasAdjunto} cerrarModal={cerrarModal} cantidadAdjunto={cantidadAdjunto} ruta='/admin/eliminar/certificado/conductor' />}
                close={() =>{setAbrirModal(false);}} 
                tam= 'smallFlot'
                abrir= {abrirModal}
                />
        </Grid>
    )
}
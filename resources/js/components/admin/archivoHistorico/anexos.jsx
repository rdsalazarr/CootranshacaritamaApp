import React, {useState} from 'react';
import {Table, TableHead, TableBody, TableRow, TableCell, Grid, Box, Link} from "@mui/material";
import CloudDownloadIcon from '@mui/icons-material/CloudDownload';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import { EliminarAdjunto } from '../../layout/modalFijas';
import { ModalDefaultAuto } from '../../layout/modal';

export default function Anexos({data, eliminar, cantidadAdjunto}){
    const [dataFiles, setDataFiles] = useState(data);
    const [dataModal, setDataModal] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);

    const cerrarModal = () =>{
        setAbrirModal(false);
    }

    const eliminarAdjuntoSeleccionado = (id, codigo, rutaFile,  anyo) =>{
        setDataModal({id: id, codigo:codigo, rutaFile: rutaFile, anyo: anyo});
        setAbrirModal(true); 
    }

    const eliminarFilasAdjunto = (id) =>{
        let newDataFiles = []; 
        dataFiles.map((res,i) =>{
            if(i != id){
                newDataFiles.push({
                    id:               res.id,
                    nombreOriginal:   res.nombreOriginal,
                    nombreEditado:    res.nombreEditado,
                    rutaDescargar:    res.rutaDescargar,
                    rutaArchivo:      res.rutaArchivo,
                    radicarDocumento: res.radicarDocumento,
                    anio:             res.anio
                });
            }
        })
        setDataFiles(newDataFiles);
    }

    return (
        <Grid container spacing={2}>
            <Grid item xs={12} sm={12} md={12} xl={12}>
                <Box className='divisionFormulario'>
                    Documentos digitalizado en el archivo histórico
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
                            let rutaAdjunto = '/download/digitalizados/'+anexos['anio']+'/'+anexos['rutaArchivo'];
                            return(
                                    <TableRow key={'rowAne-' + a + anexos['id']}>
                                        <TableCell>
                                            <span>{i}</span>
                                        </TableCell>

                                        <TableCell>
                                            <span>{anexos['nombreOriginal']}</span> 
                                        </TableCell>
                             
                                        <TableCell className='cellCenter'> 
                                            <Link href={rutaAdjunto} ><CloudDownloadIcon className={'iconoDownload'}/></Link>
                                        </TableCell>

                                        {(eliminar) ?  <TableCell className='cellCenter'>
                                            <DeleteForeverIcon onClick={() => {eliminarAdjuntoSeleccionado(a, anexos['id'], anexos['rutaArchivo'], anexos['anio'] );}} className={'iconoDownload'}/>
                                            </TableCell> : null}
                                    </TableRow> 
                                );
                            })
                        }
                        </TableBody>
                    </Table>
                </Box>
            </Grid>

            {<ModalDefaultAuto
                title={""}
                content={<EliminarAdjunto data={dataModal} eliminarFilasAdjunto={eliminarFilasAdjunto} cerrarModal={cerrarModal} cantidadAdjunto={cantidadAdjunto} ruta='/admin/eliminar/archivo/digitalizados' />}
                close={() =>{setAbrirModal(false);}} 
                tam= 'smallFlot' 
                abrir= {abrirModal}
                />}
        </Grid>
    )
}
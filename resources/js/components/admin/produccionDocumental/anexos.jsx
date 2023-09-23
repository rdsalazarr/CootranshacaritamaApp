import React, {useState} from 'react';
import {Table, TableHead, TableBody, TableRow, TableCell, Grid, Box, Link} from "@mui/material";
import CloudDownloadIcon from '@mui/icons-material/CloudDownload';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import { ModalDefaultAuto  } from '../../layout/modal';
import ModalEliminar from './modalEliminar'

export default function Anexos({data, eliminar, cantidadAdjunto}){

    console.log(data);
   
    const [dataFiles, setDataFiles] = useState(data);
    const [dataModal, setDataModal] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);

    const cerrarModal = () =>{
        setAbrirModal(false);
    }

    const eliminarAdjunto = (id, codigo, rutaFile, sigla, anyo, idFolder) =>{
        setDataModal({id: id, codigo:codigo, rutaFile: rutaFile, sigla: sigla, anyo: anyo, idFolder:idFolder});
        setAbrirModal(true); 
    }

    const eliminarFilasAdjunto = (id) =>{
        let newDataFiles = []; 
        dataFiles.map((res,i) =>{
            if(i != id){
                newDataFiles.push({
                    id: res.id,
                    nombreOriginal: res.nombreOriginal,
                    nombreEditado:  res.nombreEditado,
                    rutaDescargar:  res.rutaDescargar,
                    rutaAnexo:      res.rutaAnexo,
                    sigla:          res.sigla,
                    anio:           res.anio,
                    idFolder:       res.idFolder
                });
            }
        })
        setDataFiles(newDataFiles);
    }

    return (
        <Grid container spacing={2}>
            <Grid item xs={12} sm={12} md={12} xl={12}>  
                <Box className='subTituloFormulario'>
                    Anexos presentandos en el tipo documental
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
                            let rutaAdjunto = '/download/adjunto/'+anexos['sigla']+'/'+anexos['anio']+'/'+anexos['rutaAnexo']+'/'+anexos['idFolder'];
                            return(
                                    <TableRow key={'rowAne-' + a + anexos['id']}>
                                        <TableCell>
                                            <p>{i}</p>
                                        </TableCell>

                                        <TableCell>
                                            <p>{anexos['nombreOriginal']}</p> 
                                        </TableCell>

                                        <TableCell className='cellCenter'> 
                                            <Link href={rutaAdjunto} ><CloudDownloadIcon className={'iconoDownload'}/></Link>
                                        </TableCell>

                                        {(eliminar) ?  <TableCell className='cellCenter'>
                                            <DeleteForeverIcon onClick={() => {eliminarAdjunto(a, anexos['id'], anexos['rutaAnexo'], anexos['sigla'], anexos['anio'], anexos['idFolder'] );}} className={'iconoDownload'}/>                                        
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
                content={<ModalEliminar data={dataModal} eliminarFilasAdjunto={eliminarFilasAdjunto} cerrarModal={cerrarModal} cantidadAdjunto={cantidadAdjunto} />}
                close={() =>{setAbrirModal(false);}} 
                tam= 'smallFlot' 
                abrir= {abrirModal}
                />}
        </Grid>
    )
}
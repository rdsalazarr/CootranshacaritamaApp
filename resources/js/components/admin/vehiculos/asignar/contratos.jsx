import React, {useState, useEffect, Fragment} from 'react';
import {Grid, Table, TableHead, TableBody, TableRow, TableCell} from '@mui/material';
import AlternateEmailIcon from '@mui/icons-material/AlternateEmail';
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import VisibilityIcon from '@mui/icons-material/Visibility';
import { ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import ShowPersona from '../../persona/show';
import VisualizarPdf from './visualizarPdf';
import EnviarCorreo from './enviarCorreo';

export default function Contratos({id}){

    const [modal, setModal] = useState({open: false, vista:2, data:[], idPersona:'', titulo: '', tamano:'mediumFlot'});
    const [listaContratos, setListaContratos] = useState([]);
    const [loader, setLoader] = useState(false); 

    const tituloModal = ['Visualizar información del asociado','Generar PDF del contrato','Reenviar correo de notificación de firma de contrato'];
    const modales     = [
                            <ShowPersona id={modal.idPersona} frm={'ASOCIADO'} />,
                            <VisualizarPdf idPersona={modal.idPersona} vehiculoId={id} idContrato={modal.idContrato}/>,
                            <EnviarCorreo data={modal.data} />
                        ];

    const edit = (tipo, idPersona, idContrato, data) =>{
       setModal({open: true, vista: tipo, data:data, idPersona:idPersona, idContrato:idContrato, titulo: tituloModal[tipo], tamano: (tipo === 0 ) ? 'bigFlot' : ( (tipo === 1) ? 'mediumFlot' : 'smallFlot')});
    }

    const inicio = () =>{
        setLoader(true); 
        instance.post('/admin/direccion/transporte/listar/contratos/vehiculo', {vehiculoId: id}).then(res=>{
            setListaContratos(res.listaContratos);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }
    
    return (
        <Fragment>

            <Grid container spacing={2} style={{marginTop:'1em'}}>
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Table className={'tableAdicional'}>
                        <TableHead>
                            <TableRow>
                                <TableCell>Número contrato</TableCell>
                                <TableCell>Fecha inicial </TableCell>
                                <TableCell>Fecha final</TableCell>
                                <TableCell>Nombre del asociado </TableCell>
                                <TableCell style={{width: '10%'}} className='cellCenter'>Visualizar </TableCell>
                                <TableCell style={{width: '10%'}} className='cellCenter'>Ver contrato </TableCell>
                                <TableCell style={{width: '10%'}} className='cellCenter'>Reenviar correo </TableCell>
                            </TableRow>
                        </TableHead>
                        <TableBody>
                        { listaContratos.map((asoc, a) => {
                            return(
                                <TableRow key={'rowD-' +a} >
                                    <TableCell>
                                       {asoc['numeroContrato']}
                                    </TableCell>

                                    <TableCell>
                                       {asoc['vehconfechainicial']}
                                    </TableCell>

                                    <TableCell>
                                       {asoc['vehconfechafinal']}
                                    </TableCell>

                                    <TableCell>
                                       {asoc['nombreAsociado']}
                                    </TableCell>

                                    <TableCell className='cellCenter'>
                                        <VisibilityIcon key={'iconDelete'+a} className={'icon top green'}
                                            onClick={() => {edit(0, asoc['persid'], asoc['vehconid'], asoc)}}
                                        ></VisibilityIcon>
                                    </TableCell>

                                    <TableCell className='cellCenter'>
                                        <PictureAsPdfIcon key={'iconDelete'+a} className={'icon top orange'}
                                            onClick={() => {edit(1, asoc['persid'], asoc['vehconid'], asoc)}}
                                        ></PictureAsPdfIcon>
                                    </TableCell>

                                    <TableCell className='cellCenter'>
                                        <AlternateEmailIcon key={'iconDelete'+a} className={'icon top red'}
                                            onClick={() => {edit(2, asoc['persid'], asoc['vehconid'], asoc)}}
                                        ></AlternateEmailIcon>
                                    </TableCell>

                                </TableRow>
                                );
                            })
                        }
                        </TableBody>
                    </Table>
                </Grid>
            </Grid>

            <ModalDefaultAuto
                title   ={modal.titulo}
                content ={modales[modal.vista]}
                close   ={() =>{setModal({open : false})}}
                tam     ={modal.tamano}
                abrir   ={modal.open}
            />

        </Fragment>
    )
}
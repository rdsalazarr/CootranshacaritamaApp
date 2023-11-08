import React, {useState} from 'react';
import VisualizarPdf from '../cambiarEstado/desembolsar/visualizarPdf';
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import { ModalDefaultAuto } from '../../../layout/modal';
import {Grid, Fab} from '@mui/material';

export default function ShowBotones({data}){

    const tituloModal       = ['Generar PDF de la solicitud crédito','Generar PDF de la carta intrucciones','Generar PDF del formato', 'Generar PDF del pagaré'];
    const urlModal          = ['SOLICITUDCREDITO','CARTAINSTRUCCIONES','FORMATO', 'PAGARE'];
    const [modal, setModal] = useState({open: false, titulo:'', url: ''});

    const abrirModal = (tipo) =>{
        setModal({open: true, titulo: tituloModal[tipo], url: urlModal[tipo]});
    }

    return (
        <Grid container spacing={2}>
         
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
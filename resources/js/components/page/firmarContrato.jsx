import '../../bootstrap';
import React, {useEffect, useState, Fragment } from 'react';
import firmaElectronica from "../../../images/firmaElectronica.jpg";
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import FileDownloadIcon from '@mui/icons-material/FileDownload';
import AutoStoriesIcon from '@mui/icons-material/AutoStories';
import {FirmarContratoAsociado} from '../layout/modalFijas';
import {Box, Grid, Button, Link } from '@mui/material';
import { ThemeProvider } from '@mui/material/styles';
import { ModalDefaultAuto  } from '../layout/modal';
import {Header, Footer} from "../layout/general";
import VisualizarPdf from './visualizarPdf';
import {generalTema} from "../layout/theme";
import {createRoot} from "react-dom/client";
import instance from '../layout/instance';
import Loader from "../layout/loader";
import "../../../scss/app.scss";

export default function FirmarContrato(){

    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    const [contratoFirmado, setContratoFirmado] = useState('NO');
    const [rutaDescarga, setRutaDescarga] = useState('');
    const [tiempoToken, setTiempoToken] = useState(0);
    const [loader, setLoader] = useState(false);
    const [data, setData] = useState([]);

    const cerrarModal = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const verificarContrato = () =>{
        setContratoFirmado('SI');
    }
 
    const modales = [
                        <VisualizarPdf data={modal.data} />,
                        <FirmarContratoAsociado contratoId={window.contratoId} firmaId={window.firmaId} cerrarModal={cerrarModal} verificarContrato={verificarContrato}/>
                    ];

    const tituloModal = ['Visualizar PDF del contrato',
                        'Firmar electrónicamente el contrato'
                    ];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano:'mediumFlot'});
    } 

    const descargarContrato = () =>{
        setLoader(true)
        instance.post('/descargar/contrato/asociado', {contratoId: window.contratoId}).then(res=>{
            (res.success) ? edit(res.data, 0) : null;
            setLoader(false)
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/consultar/informacion/contrato/asociado', {firmaId: window.firmaId}).then(res=>{
            (res.success) ? (setData(res.data), setContratoFirmado(res.data.contratoFirmado), setRutaDescarga('/download/contrato/vehiculo/'+res.placaVehiculo+'/'+res.rutaPdfContrato), setTiempoToken(res.tiempoToken)) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <Loader />
    }

    return(
        <ThemeProvider theme={generalTema}>
            <Header />
            <Box className='container' style={{ margin: '8em auto'}}>

                {(contratoFirmado === 'NO') ? 
                    <Grid container spacing={3} className='verificacionDocumento'>
                        <Grid item xl={6} md={6} sm={12} xs={12}>
                            <h1 style={{textAlign: 'center'}}>¡Bienvenido a nuestro sistema para firmar contrato electrónico!</h1>
                            <Box className='borderTitulo'></Box>
                            <p style={{textAlign: 'justify'}}>¡Estamos encantados de tenerte aquí! Para comenzar el proceso de firma del contrato electrónico número <b>{data.numeroContrato}</b>, 
                                simplemente presiona el botón <b>Firmar contrato</b>. El sistema te enviará un token de confirmación a tu correo electrónico registrado. <br /><br />
                                Por seguridad, este token tiene una duración de (<b>{tiempoToken}</b>) minutos, así que asegúrate de completar el proceso dentro de ese tiempo.</p>
                            <p>¡Gracias por confiar en nosotros!</p>

                            <Grid container spacing={3}>
                                <Grid item xl={6} md={6} sm={12} xs={12}>
                                    <Button type={"button"} style={{width: '96%', marginBottom: '1em'}} startIcon={<PictureAsPdfIcon />} onClick={() => {descargarContrato()}} >Visualizar contrato</Button>
                                </Grid>

                                <Grid item xl={6} md={6} sm={12} xs={12}>
                                <Button type={"button"} style={{width: '96%', marginBottom: '1em'}} startIcon={<AutoStoriesIcon />} onClick={() => {edit("AbrirModal", 1)}} >Firmar contrato</Button> 
                                </Grid>

                            </Grid>
                            
                        </Grid>
                        <Grid item xl={6} md={6} sm={12} xs={12}>
                            <img src={firmaElectronica} style={{width: '100%', borderRadius: '10px'}}/>
                        </Grid>
                    </Grid>
                : 
                    <Grid container spacing={3} className='verificacionDocumento'>
                        <Grid item xl={6} md={6} sm={12} xs={12}>
                            <h1 style={{textAlign: 'center'}}>¡Bienvenido a nuestro sistema para firmar contrato electrónico!</h1>
                            <Box className='borderTitulo'></Box>

                            <h2>En buenas hora el contrato ya fue firmado con exito</h2>

                            {(data.totalFirmas === data.totalFirmasRealizadas) ?
                                <Fragment>
                                    <p style={{textAlign: 'justify'}}>Hemos identificado que el contrato ya cuenta con las firmas requeridas. 
                                    Si desea obtener una copia con las firmas electrónicas de las personas involucradas, puede proceder a descargarlo en el siguiente enlace.
                                    </p>

                                    <Grid container spacing={3}>
                                        <Grid item xl={6} md={6} sm={12} xs={12}>
                                            <Link href={rutaDescarga} ><Button type={"submit"}  style={{width: '96%', marginBottom: '1em'}}
                                                startIcon={<FileDownloadIcon />} >Descargar contrato </Button>
                                            </Link>
                                        </Grid>
                                    </Grid>
                                </Fragment>
                            :
                                <p style={{textAlign: 'justify'}}>Hemos identificado que el documento no cuenta con todas las firmas requeridas. Por lo tanto, 
                                    en este momento no es posible proceder con la descarga del mismo.
                                </p>
                            }

                        </Grid>
                        <Grid item xl={6} md={6} sm={12} xs={12}>
                            <img src={firmaElectronica} style={{width: '100%', borderRadius: '10px'}}/>
                        </Grid>
                    </Grid>
                }

                <ModalDefaultAuto
                    title={modal.titulo}
                    content={modales[modal.vista]}
                    close={() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''})}}
                    tam = {modal.tamano}
                    abrir ={modal.open}
                />

            </Box>
            <Footer />
        </ThemeProvider>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<FirmarContrato />);
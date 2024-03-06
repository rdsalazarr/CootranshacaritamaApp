import React, {useState , useEffect} from 'react';
import instancePdf from '../../../layout/instancePdf';
import {LoaderModal} from "../../../layout/loader";
import ErrorIcon from '@mui/icons-material/Error';
import instance from '../../../layout/instance';
import { Grid, Box } from '@mui/material';

export default function VisualizarPdf({data}){
    const [mostrarPdf, setMostrarPdf] = useState(false);
    const [loader, setLoader] = useState(false);
    const [pdf, setPdf] = useState('');

    useEffect(()=>{
        setLoader(true);
        instance.post('/admin/direccion/transporte/verificar/total/firma/contrato', {codigo: data.vecofiid}).then(res=>{
            if(res.success){
                if(res.data.totalFirmas === res.data.totalFirmasRealizadas){
                    setLoader(true);
                    instancePdf.post('/admin/direccion/transporte/visualizar/contrato/PDF', {codigo: data.vecofiid}).then(res=>{
                        let url = 'data:application/pdf;base64,'+res.data;
                        setPdf(url);
                        setMostrarPdf(true);
                        setLoader(false);
                    });
                }else{
                    setLoader(false);
                }
            }else{
                setLoader(false);
            }
        });
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid container spacing={2}>
            {(mostrarPdf) ?
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <iframe style={{width: '100%', height: '22em', border: 'none'}} 
                    src={pdf} />
                </Grid>
            : 
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box className='mensajeAdvertencia'>
                        <ErrorIcon />
                        <p> Hemos identificado que el documento no cuenta con todas las firmas requeridas. Por lo tanto, 
                            en este momento no es posible proceder con la descarga del mismo.
                        </p>
                    </Box>
                </Grid>
            }
        </Grid>
     );
}
import React, {useState , useEffect} from 'react';
import {LoaderModal} from "../../../layout/loader";
import instancePdf from '../../../layout/instancePdf';
import { Grid } from '@mui/material';

export default function VisualizarPdf({idPersona, vehiculoId, idContrato}){
    const [loader, setLoader] = useState(false); 
    const [pdf, setPdf] = useState();

    useEffect(()=>{
        setLoader(true);
        instancePdf.post('/admin/direccion/transporte/asociados/imprimir/contrato', {idPersona: idPersona, vehiculoId:vehiculoId, idContrato:idContrato}).then(res=>{
            let url = 'data:application/pdf;base64,'+res.data;
            setPdf(url);
            setLoader(false);
        });
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid item xl={12} md={12} sm={12} xs={12}>
            <iframe style={{width: '100%', height: '28em', border: 'none'}} 
            src={pdf} />
        </Grid>
     );
}
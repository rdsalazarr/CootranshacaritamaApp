import React, {useState , useEffect} from 'react';
import {LoaderModal} from "../../../layout/loader";
import instancePdf from '../../../layout/instancePdf';
import { Grid } from '@mui/material';

export default function VisualizarPdf({data}){
    const [loader, setLoader] = useState(false); 
    const [pdf, setPdf] = useState(); 

    const tipoDocumental = {'A' : data.actaId,
                            'B' : data.certificadoId,
                            'C' : data.circularId,
                            'H' : data.citacionId,
                            'T' : data.constanciaId,
                            'O' : data.oficioId
                        }

    useEffect(()=>{
        setLoader(true);
        instancePdf.post('/admin/firmar/documento/visualizar/documento/PDF', {codigo: tipoDocumental[data.codigoDocumental], tipo:data.codigoDocumental}).then(res=>{
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
            <iframe style={{width: '100%', height: '22em', border: 'none'}} 
            src={pdf} />
        </Grid>
     );
}
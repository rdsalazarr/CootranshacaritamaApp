import React, {useState , useEffect} from 'react';
import {LoaderModal} from "../../../layout/loader";
import instancePdf from '../../../layout/instancePdf';
import { Grid } from '@mui/material';

export default function PdfStickers({id}){
    const [loader, setLoader] = useState(false); 
    const [pdf, setPdf] = useState(); 

    useEffect(()=>{
        setLoader(true);
        instancePdf.post('/admin/radicacion/documento/entrante/imprimir', {codigo: id}).then(res=>{
            let url = 'data:application/pdf;base64,'+res.data;
            setPdf(url);
            setLoader(false);
        });
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid item md={7} xl={6} xs={12}>
            <iframe style={{width: '100%', height: '22em', border: 'none'}} 
            src={pdf} />
        </Grid>
     );
}
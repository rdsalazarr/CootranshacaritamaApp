import React, {useState , useEffect} from 'react';
import {LoaderModal} from "../../layout/loader";
import instancePdf from '../../layout/instancePdf';
import { Grid } from '@mui/material';

export default function ShowAnexo({extencion, ruta, rutaEnfuscada}){
    const [loader, setLoader] = useState(false); 
    const [pdf, setPdf] = useState(); 

    useEffect(()=>{
       setLoader(true);
        instancePdf.post('/admin/show/adjunto', {ruta: ruta, rutaEnfuscada: rutaEnfuscada}).then(res=>{
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
            {(extencion === 'PDF') ?
            <iframe style={{width: '100%', height: '22em', border: 'none'}} 
            src={pdf} />
            :
            
            null}
        </Grid>
     );
}
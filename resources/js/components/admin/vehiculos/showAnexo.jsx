import React, {useState , useEffect} from 'react';
import showSimpleSnackbar from '../../layout/snackBar';
import instancePdf from '../../layout/instancePdf';
import {LoaderModal} from "../../layout/loader";
import { Grid } from '@mui/material';

export default function ShowAnexo({extencion, ruta, rutaEnfuscada, cerrarModal}){
    const [loader, setLoader] = useState(false); 
    const [rutaFile, setRutaFile] = useState(); 

    useEffect(()=>{
       setLoader(true);
        instancePdf.post('/admin/show/adjunto', {ruta: ruta, rutaEnfuscada: rutaEnfuscada}).then(res=>{
            if(res.successError){
                showSimpleSnackbar(res.message, 'error');
                cerrarModal();
            }else{
                let url = '';
                if(extencion === 'PDF'){
                    url = 'data:application/pdf;base64,'+res.data;
                }else{
                    url = 'data:application/jpg;base64,'+res.data;
                }
                setRutaFile(url);
            }
            setLoader(false);
        });
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid item xl={12} md={12} sm={12} xs={12}>
            {(extencion === 'PDF') ?
              <iframe style={{width: '100%', height: '22em', border: 'none'}} src={rutaFile} />
            : <img src={rutaFile} style={{ width: '100%', objectFit: 'cover'}}></img>}
        </Grid>
     );
}
import React, {useState} from 'react';
import { Grid } from '@mui/material';

export default function VisualizarPdf({data}){  
    const [pdf, setPdf] = useState('data:application/pdf;base64,'+data); 
    return (
        <Grid item xl={12} md={12} sm={12} xs={12}>
            <iframe style={{width: '100%', height: '22em', border: 'none'}} 
            src={pdf} />
        </Grid>
     );
}
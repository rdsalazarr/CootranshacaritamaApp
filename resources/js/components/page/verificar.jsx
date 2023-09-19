import '../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";
import {Header, Footer} from "../layout/general";
import { Box} from '@mui/material';
import "../../../scss/app.scss";

export default function Verificar(){
    
    const dataDocumento = window.dataDocumento;
    console.log(dataDocumento);

    return(
        <Box>
            <Header />
            <Box className='container'>
                <br></br> <br></br> <br></br> <br></br> <br></br> <br></br> <br></br> <br></br>
                 hola Verificar
                 <br></br> <br></br> <br></br> <br></br> <br></br> <br></br> <br></br> <br></br>
            </Box>
            <Footer />
        </Box>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<Verificar />);
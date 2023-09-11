import '../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";
import {Header, Footer} from "../layout/general";
import IniciarSesion from './iniciarSesion'
import { Box} from '@mui/material';
import "../../../scss/app.scss";

export default function App(){

    return(
        <Box>
            <Header />
            <Box className='container'>
                <IniciarSesion />
            </Box>
            <Footer />
        </Box>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<App />);
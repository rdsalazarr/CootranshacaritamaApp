import '../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";
import {HeaderAdmon, FooterAdmon } from "../layout/general";
import Contenedor from '../layout/contenedor';
import { Box} from '@mui/material';
import "../../../scss/app.scss";

export default function Dashboard(){
    return(
        <Box>
            <HeaderAdmon />
            <Contenedor />
            <FooterAdmon />
        </Box>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<Dashboard />);
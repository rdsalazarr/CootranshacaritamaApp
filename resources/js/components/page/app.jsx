import '../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";
import {Header, Footer} from "../layout/general";
import { Box, Grid } from '@mui/material';
import "../../../scss/app.scss";

export default function App(){

    return(
        <Box>
            <Header />
            <div>
                <br></br><br></br><br></br><br></br><br></br><br></br><br></br>

                <h1>hola mundo </h1>

                <br></br><br></br><br></br><br></br><br></br><br></br><br></br>
            </div>

            <Footer />
        </Box>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<App />);

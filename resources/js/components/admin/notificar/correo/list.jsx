import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import { TabPanel } from '../../../layout/general';
import Informacion from "./informacion/list";
import Configurar from "./configurar/list";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return (
        <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Gestionar información de la notificaciones por correo</Typography>
            </Box>

            <Tabs value={value} onChange={handleChangeTab} 
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Información" />
                <Tab label="Configurar" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Informacion />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Configurar />
            </TabPanel>

        </Box>
    )
}
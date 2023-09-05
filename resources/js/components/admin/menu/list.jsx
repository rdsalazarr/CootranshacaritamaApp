import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import Funcionalidad from "./funcionalidad/list.jsx";
import { TabPanel } from '../../layout/general';
import Modulo from "./modulo/list.jsx";
import Rol from "./rol/list.jsx";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return ( 
          <Box> 
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Gestión de menú</Typography>
            </Box> 
            <Tabs value={value} onChange={handleChangeTab}
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Rol" />
                <Tab label="Funcionalidad" />
                <Tab label="Módulo" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Rol />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Funcionalidad />
            </TabPanel>

            <TabPanel value={value} index={2}>
                <Modulo />
            </TabPanel>

          </Box>
    )
}
import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import { TabPanel } from '../../../layout/general';
import Carroceria from "./carroceria/list";
import Referencia from "./referencia/list";
import Modalidad from "./modalidad/list";
import Vehiculo from "./vehiculo/list";
import Marca from "./marca/list";
import Color from "./color/list";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return (
        <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Gestion de tipos correspondiente a los vehículos</Typography>
            </Box>

            <Tabs value={value} onChange={handleChangeTab} 
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Tipo vehículo" />
                <Tab label="Referencia" />
                <Tab label="Marca" />
                <Tab label="Color" />
                <Tab label="Carroceria" />
                <Tab label="Modalidad" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Vehiculo />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Referencia />
            </TabPanel>

            <TabPanel value={value} index={2}>
                <Marca />
            </TabPanel>

            <TabPanel value={value} index={3}>
                <Color />
            </TabPanel>

            <TabPanel value={value} index={4}>
                <Carroceria />
            </TabPanel>

            <TabPanel value={value} index={5}>
                <Modalidad />
            </TabPanel>
        </Box>
    )
}
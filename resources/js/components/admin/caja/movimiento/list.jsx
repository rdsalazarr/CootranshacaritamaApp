import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import { TabPanel } from '../../../layout/general';
import Mensualidad from "./mensualidad";
import PagoCredito from "./pagoCredito";
import Sancion from "./sancion";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return (
        <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Registrar movimiento de caja</Typography>
            </Box>

            <Tabs value={value} onChange={handleChangeTab} 
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Mensualidad" />
                <Tab label="Pago crédito" />
                <Tab label="Sanción" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Mensualidad />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <PagoCredito />
            </TabPanel>

            <TabPanel value={value} index={2}>
                <Sancion />
            </TabPanel>

        </Box>
    )
}
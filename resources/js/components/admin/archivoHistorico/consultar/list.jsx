import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import { TabPanel } from '../../../layout/general';
import Consultar from "./consultar";
import Expediente from "./expediente";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return (
        <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Gestionar consulta del arhivo histórico</Typography>
            </Box>

            <Tabs value={value} onChange={handleChangeTab} 
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Consultar" />
                <Tab label="Generar expediente" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Consultar />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Expediente />
            </TabPanel>

        </Box>
    )
}
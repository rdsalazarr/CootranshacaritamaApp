import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import { TabPanel } from '../../layout/general';
import Noche from "./noche";
import Dia from "./dia";

export default function RegistrarMovimientos(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    }

    return (
        <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Verificar proceso automáticos</Typography>
            </Box>

            <Tabs value={value} onChange={handleChangeTab} 
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Dia" />
                <Tab label="Noche" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Dia />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Noche />
            </TabPanel>
        </Box>
    )
}
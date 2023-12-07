import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import { TabPanel } from '../../../layout/general';
import Encomienda from "./encomienda";
import Planilla from "./planilla";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    }

    return (
        <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Recibir planilla y/o entregar encomienda</Typography>
            </Box>

            <Tabs value={value} onChange={handleChangeTab} 
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Recibir Planilla" />
                <Tab label="Entregar Encomienda" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Planilla />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Encomienda />
            </TabPanel>

        </Box>
    )
}
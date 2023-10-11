import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import { TabPanel } from '../../../../layout/general';
import Verificar from "./verificar";
import Historico from "./historico";
import Gestionar from "./gestionar";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return (
        <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Gestionar radicación de documento entrante</Typography>
            </Box>

            <Tabs value={value} onChange={handleChangeTab} 
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Gestionar" />
                <Tab label="Verificar" />
                <Tab label="Histórico" /> 
            </Tabs>

            <TabPanel value={value} index={0}>
                <Gestionar />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Verificar />
            </TabPanel>        

            <TabPanel value={value} index={2}>
                <Historico />
            </TabPanel>
        </Box>
    )
}
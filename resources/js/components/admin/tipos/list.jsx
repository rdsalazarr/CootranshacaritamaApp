import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import PersonaDocumental from "./personaDocumental/list";
import { TabPanel } from '../../layout/general';
import CargoLaboral from "./cargoLaboral/list";
import Despedida from "./despedida/list";
import Saludo from "./saludo/list";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return (
          <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Gestión de tipos</Typography>
            </Box> 
            <Tabs value={value} onChange={handleChangeTab}
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Saludo" />
                <Tab label="Despedida" />
                <Tab label="Cargo laboral" />
                <Tab label="Persona Documental" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Saludo />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Despedida />
            </TabPanel>

            <TabPanel value={value} index={2}>
                <CargoLaboral />
            </TabPanel>

            <TabPanel value={value} index={3}>
                <PersonaDocumental />
            </TabPanel>

          </Box>
    )
}
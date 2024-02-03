import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import { TabPanel } from '../../../layout/general';
import Vehiculo from "./vehiculo.jsx";
import Empleado from "./empleado.jsx";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return ( 
          <Box> 
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Gestión de solicitud de crédito</Typography>
            </Box> 
            <Tabs value={value} onChange={handleChangeTab}
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Vehículo" />
                <Tab label="Empleado" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Vehiculo />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Empleado />
            </TabPanel>

          </Box>
    )
}
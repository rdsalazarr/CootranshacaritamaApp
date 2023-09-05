import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import Departamento from "./departamento/list.jsx";
import { TabPanel } from '../../layout/general';
import Municipio from "./municipio/list.jsx";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return ( 
          <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Gestiónar datos geográficos de la empresa</Typography>
            </Box>            
            <Tabs value={value} onChange={handleChangeTab} 
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Municipio" />
                <Tab label="Departamento" />              
            </Tabs>

            <TabPanel value={value} index={0}>
                <Municipio />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Departamento />
            </TabPanel>


          </Box>
    )
}
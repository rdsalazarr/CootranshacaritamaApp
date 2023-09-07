import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import { TabPanel } from '../../layout/general';
import Serie from "./serie/list.jsx";
import SubSerie from "./subSerie/list.jsx";

export default function List(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };

    return ( 
          <Box> 
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Gestión de series documentales</Typography>
            </Box> 
            <Tabs value={value} onChange={handleChangeTab}
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Serie" />
                <Tab label="Subserie" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Serie />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <SubSerie />
            </TabPanel>

          </Box>
    )
}
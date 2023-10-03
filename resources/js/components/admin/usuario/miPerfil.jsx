import React, {useState} from 'react';
import { TabPanel } from '../../layout/general';
import {Box, Tab, Tabs} from '@mui/material';
import Password from './password';
import Perfil from './perfil';

export default function MiPerfil(){  
    const [value, setValue] = useState(0);
    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    };      

    return (
        <Box>
            <Tabs value={value} onChange={handleChangeTab}
                sx={{background: '#e2e2e2'}}
                indicatorColor="secondary"
                textColor="secondary"
                variant="fullWidth">
                <Tab label="Perfil del usuario" />
                <Tab label="Actualizar mi contraseña" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <Perfil />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Password />
            </TabPanel>
        </Box>
    );
}
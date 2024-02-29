import React, {useState} from 'react';
import { Box, Typography, Tab, Tabs} from '@mui/material';
import DocumentoVencidos from "./documentoVencidos";
import { TabPanel } from '../../../layout/general';
import TablaLiquidacion from "./tablaLiquidacion";
import MovimientoCaja from "./movimientoCaja";
import Licencias from "./licencias";
import Tiquete from "./tiquete";

export default function RegistrarMovimientos(){

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    }

    return (
        <Box>
            <Box><Typography component={'h2'} className={'titleGeneral'} style={{ paddingBottom: "0.5em"}}>Generar informes en formato descargable</Typography>
            </Box>

            <Tabs value={value} onChange={handleChangeTab}
                sx={{ background: '#e2e2e2' }}
                indicatorColor="secondary"
                textColor="secondary"
                variant={variantTab} >
                <Tab label="Movimiento caja" />
                <Tab label="Tiquete" />
                <Tab label="Documento vencidos" />
                <Tab label="Licencias vencidas" />
                <Tab label="Tabla de liquidación" />
            </Tabs>

            <TabPanel value={value} index={0}>
                <MovimientoCaja />
            </TabPanel>

            <TabPanel value={value} index={1}>
                <Tiquete />
            </TabPanel>

            <TabPanel value={value} index={2}>
                <DocumentoVencidos />
            </TabPanel>

            <TabPanel value={value} index={3}>
                <Licencias />
            </TabPanel>

            <TabPanel value={value} index={4}>
                <TablaLiquidacion />
            </TabPanel>

        </Box>
    )
}
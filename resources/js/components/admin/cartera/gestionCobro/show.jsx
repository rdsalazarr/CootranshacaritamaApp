import React, {useState, useEffect} from 'react';
import Trazabilidad from '../../../layout/trazabilidad';
import TablaLiquidacion from '../show/tablaLiquidacion';
import {LoaderModal} from "../../../layout/loader";
import { TabPanel } from '../../../layout/general';
import instance from '../../../layout/instance';
import {Grid, Tab, Tabs} from '@mui/material';
import Colocacion from '../show/colocacion';

export default function Show({id}){

    const [formDataColocacion, setFormDataColocacion] = useState({solicitudId:id, nombreUsuario:'', fechaDesembolso:'', estadoActual:'',
                                                                    numeroPagare:'', valorDesembolsado:'', tasaNominal:'', numeroCuota:''});
    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [cambiosEstadoColocacion, setCambiosEstadoColocacion] = useState([]);
    const [colocacionLiquidacion, setColocacionLiquidacion] = useState([]);    
    const [loader, setLoader] = useState(false);
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    }

    const inicio = () =>{
        setLoader(true);
        let newFormDataColocacion = {...formDataColocacion};
        instance.post('/admin/cartera/show/colocacion', {codigo: id, tipo:'H'}).then(res=>{
            let colocacion                          = res.colocacion;
            newFormDataColocacion.nombreUsuario     = colocacion.nombreUsuario;
            newFormDataColocacion.fechaDesembolso   = colocacion.colofechahoraregistro;
            newFormDataColocacion.estadoActual      = colocacion.tiesclnombre;
            newFormDataColocacion.numeroPagare      = colocacion.numeroColocacion;
            newFormDataColocacion.valorDesembolsado = colocacion.valorDesembolsado;
            newFormDataColocacion.tasaNominal       = colocacion.colotasa;
            newFormDataColocacion.numeroCuota       = colocacion.colonumerocuota;
            setCambiosEstadoColocacion(res.cambiosEstadoColocacion);
            setColocacionLiquidacion(res.colocacionLiquidacion);
            setFormDataColocacion(newFormDataColocacion);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid container spacing={2}>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Colocacion data={formDataColocacion} />
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Tabs value={value} onChange={handleChangeTab} 
                    sx={{background: '#e2e2e2'}}
                    indicatorColor="secondary"
                    textColor="secondary"
                    variant={variantTab} >
                    <Tab label="Tabla de liquidacion" />
                    <Tab label="Anotaciones al crédito" />
                </Tabs>

                <TabPanel value={value} index={0}>
                    <TablaLiquidacion liquidacion={colocacionLiquidacion} />
                </TabPanel>

                <TabPanel value={value} index={1}>
                    <Trazabilidad mensaje='' data={cambiosEstadoColocacion} />
                </TabPanel>

            </Grid>

        </Grid>
    )
}
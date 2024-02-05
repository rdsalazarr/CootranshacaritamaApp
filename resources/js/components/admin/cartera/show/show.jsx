import React, {useState, useEffect, Fragment} from 'react';
import Trazabilidad from '../../../layout/trazabilidad';
import person from "../../../../../images/person.png";
import { TabPanel } from '../../../layout/general';
import {LoaderModal} from "../../../layout/loader";
import TablaLiquidacion from './tablaLiquidacion';
import SolicitudCredito from './solicitudCredito';
import instance from '../../../layout/instance';
import {Grid, Tab, Tabs} from '@mui/material';
import ShowBotones from './showBotones';
import Colocacion from './colocacion';
import Persona from './persona';

export default function Show({id}){

    const [formData, setFormData] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'', segundoApellido:'', fechaNacimiento:'',
                                         direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:'', lineaCredito:'', destinoCredito:'', valorSolicitado:'',
                                         tasaNominal:'',  numerosCuota:'', observacionGeneral:''})
    const [formDataColocacion, setFormDataColocacion] = useState({solicitudId:id, nombreUsuario:'', fechaDesembolso:'', estadoActual:'',
                                                                    numeroPagare:'', valorDesembolsado:'', tasaNominal:'', numeroCuota:''});

    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [cambiosEstadoSolicitudCredito, setCambiosEstadoSolicitudCredito] = useState([]);
    const [cambiosEstadoColocacion, setCambiosEstadoColocacion] = useState([]);
    const [colocacionLiquidacion, setColocacionLiquidacion] = useState([]);
    const [loader, setLoader] = useState(false);
    const [value, setValue] = useState(0); 

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData           = {...formData};
        let newFormDataColocacion = {...formDataColocacion};
        instance.post('/admin/cartera/show/solicitud/credito', {codigo: id}).then(res=>{
            let solicitudCredito             = res.solicitudCredito;
            let colocacion                   = res.colocacion;
            newFormData.tipoIdentificacion   = solicitudCredito.nombreTipoIdentificacion;
            newFormData.documento            = solicitudCredito.persdocumento;
            newFormData.primerNombre         = solicitudCredito.persprimernombre;
            newFormData.segundoNombre        = solicitudCredito.perssegundonombre;
            newFormData.primerApellido       = solicitudCredito.persprimerapellido;
            newFormData.segundoApellido      = solicitudCredito.perssegundoapellido;
            newFormData.fechaNacimiento      = solicitudCredito.persfechanacimiento;
            newFormData.direccion            = solicitudCredito.persdireccion;
            newFormData.correo               = solicitudCredito.perscorreoelectronico;
            newFormData.telefonoFijo         = solicitudCredito.persnumerotelefonofijo;
            newFormData.numeroCelular        = solicitudCredito.persnumerocelular;
            newFormData.fechaIngresoAsociado = solicitudCredito.asocfechaingreso;
            newFormData.showFotografia       = (solicitudCredito.fotografia !== null) ? solicitudCredito.fotografia : person;
            newFormData.totalColocacion      = solicitudCredito.totalColocacion;

            newFormData.lineaCredito         = solicitudCredito.lineaCredito;
            newFormData.destinoCredito       = solicitudCredito.solcredescripcion;
            newFormData.valorSolicitado      = solicitudCredito.valorSolicitado;
            newFormData.tasaNominal          = solicitudCredito.tasaNominal;
            newFormData.numerosCuota         = solicitudCredito.solcrenumerocuota;
            newFormData.observacionGeneral   = solicitudCredito.solcreobservacion;
            newFormData.fechaSolicitud       = solicitudCredito.solcrefechasolicitud;
            newFormData.estadoActual         = solicitudCredito.estadoActual;

            if(solicitudCredito.totalColocacion > 0){
                newFormDataColocacion.nombreUsuario     = colocacion.nombreUsuario;
                newFormDataColocacion.fechaDesembolso   = colocacion.colofechahoraregistro;
                newFormDataColocacion.estadoActual      = colocacion.tiesclnombre;
                newFormDataColocacion.numeroPagare      = colocacion.numeroColocacion;
                newFormDataColocacion.valorDesembolsado = colocacion.valorDesembolsado;
                newFormDataColocacion.tasaNominal       = colocacion.colotasa;
                newFormDataColocacion.numeroCuota       = colocacion.colonumerocuota;
            }

            setFormData(newFormData);
            setCambiosEstadoSolicitudCredito(res.cambiosEstadoSolicitudCredito);
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
                <Persona data={formData} />
            </Grid>

            {(formData.totalColocacion > 0)?
                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Tabs value={value} onChange={handleChangeTab} 
                        sx={{background: '#e2e2e2'}}
                        indicatorColor="secondary"
                        textColor="secondary"
                        variant={variantTab} >
                        <Tab label="Solicitud" />
                        <Tab label="Estado de la solicitud" />
                        <Tab label="Crédito" />
                        <Tab label="Anotaciones al crédito" />
                    </Tabs>

                    <TabPanel value={value} index={0}>
                        <SolicitudCredito data={formData} />
                    </TabPanel>

                    <TabPanel value={value} index={1}>
                        <Trazabilidad mensaje='' data={cambiosEstadoSolicitudCredito} />
                    </TabPanel>

                    <TabPanel value={value} index={2}>
                        <Colocacion data={formDataColocacion} liquidacion={colocacionLiquidacion} />
                        <TablaLiquidacion liquidacion={colocacionLiquidacion} />
                        <ShowBotones data={formDataColocacion} />
                    </TabPanel>

                    <TabPanel value={value} index={3}>
                        <Trazabilidad mensaje='' data={cambiosEstadoColocacion} />
                    </TabPanel>
                </Grid>
            :
                <Fragment>

                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <SolicitudCredito data={formData} />
                    </Grid>

                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Trazabilidad mensaje='Cambio de estado de la solicitud de crédito' data={cambiosEstadoSolicitudCredito} />
                    </Grid>

                </Fragment>
            }

        </Grid>
    )
}
import React, {useState, useEffect} from 'react';
import Trazabilidad from '../../../layout/trazabilidad';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import ShowAsociado from './showAsociado';
import {Grid, Box} from '@mui/material';

export default function Show({id}){

    const [loader, setLoader] = useState(false);

    const [formData, setFormData] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'', segundoApellido:'', fechaNacimiento:'',
                                         direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:'',lineaCredito:'', destinoCredito:'', valorSolicitado:'', 
                                         tasaNominal:'',  numerosCuota:'', observacionGeneral:''})
    
    const [cambiosEstadoSolicitudCredito, setCambiosEstadoSolicitudCredito] = useState([]);

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData};
        instance.post('/admin/cartera/show/solicitud/credito', {codigo: id}).then(res=>{
            let solicitudcredito             = res.solicitudcredito;
            newFormData.tipoIdentificacion   = solicitudcredito.nombreTipoIdentificacion;
            newFormData.documento            = solicitudcredito.persdocumento;
            newFormData.primerNombre         = solicitudcredito.persprimernombre;
            newFormData.segundoNombre        = solicitudcredito.perssegundonombre;
            newFormData.primerApellido       = solicitudcredito.persprimerapellido;
            newFormData.segundoApellido      = solicitudcredito.perssegundoapellido;
            newFormData.fechaNacimiento      = solicitudcredito.persfechanacimiento;
            newFormData.direccion            = solicitudcredito.persdireccion;
            newFormData.correo               = solicitudcredito.perscorreoelectronico;
            newFormData.telefonoFijo         = solicitudcredito.persnumerotelefonofijo;
            newFormData.numeroCelular        = solicitudcredito.persnumerocelular;
            newFormData.fechaIngresoAsociado = solicitudcredito.asocfechaingreso;
            newFormData.showFotografia       = solicitudcredito.fotografia;

            newFormData.lineaCredito         = solicitudcredito.lineaCredito;
            newFormData.destinoCredito       = solicitudcredito.solcredescripcion;
            newFormData.valorSolicitado      = solicitudcredito.valorSolicitado;
            newFormData.tasaNominal          = solicitudcredito.tasaNominal;
            newFormData.numerosCuota         = solicitudcredito.solcrenumerocuota;
            newFormData.observacionGeneral   = solicitudcredito.solcreobservacion;
            newFormData.fechaSolicitud       = solicitudcredito.solcrefechasolicitud;
            newFormData.estadoActual         = solicitudcredito.estadoActual;

            setFormData(newFormData);
            setCambiosEstadoSolicitudCredito(res.cambiosEstadoSolicitudCredito);
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
                <ShowAsociado data={formData} />
            </Grid>

            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Información de la solicitud de crédito
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Fecha de registro</label>
                    <span>{formData.fechaSolicitud}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Estado actual</label>
                    <span>{formData.estadoActual}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Línea de crédito</label>
                    <span>{formData.lineaCredito}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Valor solicitado</label>
                    <span>{formData.valorSolicitado}</span>
                </Box>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Destino del crédito</label>
                    <span>{formData.destinoCredito}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tasa nominal</label>
                    <span>{formData.tasaNominal}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Número de cuota </label>
                    <span>{formData.numerosCuota}</span>
                </Box>
            </Grid>

            {(formData.observacionGeneral !== null) ?
                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Observación general</label>
                        <span>{formData.observacionGeneral}</span>
                    </Box>
                </Grid>
            : null}

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Trazabilidad mensaje='Cambio de estado de la solicitud de crédito' data={cambiosEstadoSolicitudCredito}/>
            </Grid>

        </Grid>
    )
}
import React, {useState, useEffect} from 'react';
import Trazabilidad from '../../../layout/trazabilidad';
import {LoaderModal} from "../../../layout/loader";
import SolicitudCredito from './solicitudCredito';
import instance from '../../../layout/instance';
import {Grid} from '@mui/material';
import Asociado from './asociado';

export default function Show({id}){

    const [loader, setLoader] = useState(false);

    const [formData, setFormData] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'', segundoApellido:'', fechaNacimiento:'',
                                         direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:'', lineaCredito:'', destinoCredito:'', valorSolicitado:'', 
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
                <Asociado data={formData} />
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <SolicitudCredito data={formData} />
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Trazabilidad mensaje='Cambio de estado de la solicitud de crédito' data={cambiosEstadoSolicitudCredito}/>
            </Grid>

        </Grid>
    )
}
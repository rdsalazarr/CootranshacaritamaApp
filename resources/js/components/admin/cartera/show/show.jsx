import React, {useState, useEffect} from 'react';
import Trazabilidad from '../../../layout/trazabilidad';
import person from "../../../../../images/person.png";
import {LoaderModal} from "../../../layout/loader";
import SolicitudCredito from './solicitudCredito';
import instance from '../../../layout/instance';
import {Grid} from '@mui/material';
import Asociado from './asociado';

export default function Show({id}){    

    const [formData, setFormData] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'', segundoApellido:'', fechaNacimiento:'',
                                         direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:'', lineaCredito:'', destinoCredito:'', valorSolicitado:'', 
                                         tasaNominal:'',  numerosCuota:'', observacionGeneral:''})

    const [cambiosEstadoSolicitudCredito, setCambiosEstadoSolicitudCredito] = useState([]);
    const [loader, setLoader] = useState(false);

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData};
        instance.post('/admin/cartera/show/solicitud/credito', {codigo: id}).then(res=>{
            let solicitudCredito             = res.solicitudCredito;
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
            newFormData.showFotografia       = (solicitudCredito.fotografia !== null) ? solicitudCredito.fotografia  : person;

            newFormData.lineaCredito         = solicitudCredito.lineaCredito;
            newFormData.destinoCredito       = solicitudCredito.solcredescripcion;
            newFormData.valorSolicitado      = solicitudCredito.valorSolicitado;
            newFormData.tasaNominal          = solicitudCredito.tasaNominal;
            newFormData.numerosCuota         = solicitudCredito.solcrenumerocuota;
            newFormData.observacionGeneral   = solicitudCredito.solcreobservacion;
            newFormData.fechaSolicitud       = solicitudCredito.solcrefechasolicitud;
            newFormData.estadoActual         = solicitudCredito.estadoActual;

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
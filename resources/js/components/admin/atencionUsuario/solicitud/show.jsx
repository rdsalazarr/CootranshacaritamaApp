import React, {useState, useEffect} from 'react';
import Persona from '../../radicacion/documentoEntrante/show/persona';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import {Grid, Box} from '@mui/material';
import Anexos from './anexos';

export default function Show({id}){

    const [dataSolicitud, setDataSolicitud] = useState([]);
    const [dataPersona, setDataPersona] = useState([]);
    const [dataAnexos, setDataAnexos] = useState([]);
    const [loader, setLoader] = useState(false);

    const inicio = () =>{
        setLoader(true);
        showSimpleSnackbar("Estamos recopilando toda la información de la solicitud, se paciente por favor",'warning');
        instance.post('/admin/antencion/usuario/show/solicitud', {codigo: id}).then(res=>{
            let newDataUsuario   = [];
            let newDataSolicitud = [];
            let solicitud        = res.solicitud;
            let anexosRadicado   = res.anexos;

            //Informacion de la persona
            newDataUsuario.tipoIdentificacion   = solicitud.tipoIdentificacion;
            newDataUsuario.numeroIdentificacion = solicitud.peradodocumento;
            newDataUsuario.esEmpresa            = (solicitud.tipideid === '5' ) ? true : false;
            newDataUsuario.primerNombre         = solicitud.peradoprimernombre;
            newDataUsuario.segundoNombre        = solicitud.peradosegundonombre;
            newDataUsuario.primerApellido       = solicitud.peradoprimerapellido;
            newDataUsuario.segundoApellido      = solicitud.peradosegundoapellido;
            newDataUsuario.direccionFisica      = solicitud.peradodireccion;
            newDataUsuario.direccionElectronica = solicitud.peradocorreo;
            newDataUsuario.numeroContacto       = solicitud.peradotelefono;

            //Informacion de la solicitud
            newDataSolicitud.fechaRegistro      = solicitud.solifechahoraregistro;
            newDataSolicitud.fechaIncidente     = solicitud.solifechahoraincidente;
            newDataSolicitud.tipoMedio          = solicitud.tipoMedio;
            newDataSolicitud.tipoSolicitud      = solicitud.tipoSolicitud;
            newDataSolicitud.vehiculo           = solicitud.nombreVehiculo;
            newDataSolicitud.conductor          = solicitud.nombreConductor;
            newDataSolicitud.observacionGeneral = solicitud.soliobservacion;
            newDataSolicitud.descripcion        = solicitud.solimotivo;

            setDataSolicitud(newDataSolicitud);
            setDataPersona(newDataUsuario);
            setDataAnexos(anexosRadicado);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (

        <Grid container spacing={2}>
            <Grid item md={12} xl={12} sm={12}>
                <Persona data={dataPersona} />
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='divisionFormulario'>
                    Información de la solicitud
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Fecha de registro</label>
                    <span>{dataSolicitud.fechaRegistro}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Fecha de incidente</label>
                    <span>{dataSolicitud.fechaIncidente}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tipo de solicitud</label>
                    <span>{dataSolicitud.tipoSolicitud}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tipo de medio</label>
                    <span>{dataSolicitud.tipoMedio}</span>
                </Box>
            </Grid>

            {(dataSolicitud.vehiculo !== null) ?
                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <Box className='frmTexto'>
                        <label>Vehículo</label>
                        <span>{dataSolicitud.vehiculo}</span>
                    </Box>
                </Grid>
            : null }

            {(dataSolicitud.conductor !== null) ?
                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <Box className='frmTexto'>
                        <label>Conductor</label>
                        <span>{dataSolicitud.conductor}</span>
                    </Box>
                </Grid>
            : null }

            {(dataSolicitud.observacionGeneral !== null) ?
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box className='frmTexto'>
                        <label>Observación general</label>
                        <span>{dataSolicitud.observacionGeneral}</span>
                    </Box>
                </Grid>
            : null }

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Motivo</label>
                    <span className="longText">{dataSolicitud.descripcion}</span>
                </Box>
            </Grid>

            <Grid item md={12} xl={12} sm={12}>
                <Anexos data={dataAnexos} eliminar={false} />
            </Grid>

        </Grid>
    )
}
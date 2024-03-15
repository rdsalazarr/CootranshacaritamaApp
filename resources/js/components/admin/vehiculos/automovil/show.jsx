import React, {useState, useEffect} from 'react';
import imagenVehiculo from "../../../../../images/vehiculo.png";
import Trazabilidad from '../../../layout/trazabilidad';
import SolicitudVehiculo from './solicitudVehiculo';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import {Grid, Box} from '@mui/material';

export default function Show({id}){

    const [formData, setFormData] = useState({  tipoVehiculo: '',   tipoReferencia: '', tipoMarca: '',      tipoCombustible: '', 
                                                tipoModalidad: '',   tipoCarroceria: '', tipoColor: '',      agencia: '',          fechaIngreso: '', 
                                                numeroInterno: '',   placa: '',          modelo: '',         cilindraje: '',       numeroMotor: '', 
                                                numeroChasis: '',    numeroSerie: '',    numeroEjes: '1',    motorRegrabado: '0', chasisRegrabado: '0', 
                                                serieRegrabado: '0', observacion: '',    fotografia: ''
                                        });
    
    const [cambiosEstadoVehiculo, setCambiosEstadoVehiculo] = useState([]);
    const [solicitudVehiculos, setSolicitudVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);
 
    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData};
        instance.post('/admin/direccion/transporte/vehiculo/show', {vehiculoId: id}).then(res=>{
            let vehiculo                          = res.vehiculo;           
            newFormData.tipoVehiculo              = vehiculo.tipoVehiculo;
            newFormData.tipoReferencia            = vehiculo.tipoReferencia;
            newFormData.tipoMarca                 = vehiculo.tipoMarca;
            newFormData.tipoCombustible           = vehiculo.tipoCombustible;
            newFormData.tipoModalidad             = vehiculo.tipoModalidad;
            newFormData.tipoCarroceria            = vehiculo.tipoCarroceria;
            newFormData.tipoColor                 = vehiculo.tipoColor;
            newFormData.agencia                   = vehiculo.agencia;
            newFormData.fechaIngreso              = vehiculo.vehifechaingreso;
            newFormData.numeroInterno             = vehiculo.vehinumerointerno;
            newFormData.placa                     = vehiculo.vehiplaca;
            newFormData.modelo                    = vehiculo.vehimodelo;
            newFormData.cilindraje                = vehiculo.vehicilindraje;
            newFormData.numeroMotor               = (vehiculo.vehinumeromotor !== null) ? vehiculo.vehinumeromotor : 'NO REPORTADO';
            newFormData.numeroChasis              = (vehiculo.vehinumerochasis !== null) ? vehiculo.vehinumerochasis : 'NO REPORTADO';
            newFormData.numeroSerie               = (vehiculo.vehinumeroserie !== null) ? vehiculo.vehinumeroserie : 'NO REPORTADO';
            newFormData.numeroEjes                = (vehiculo.vehinumeroejes !== null) ? vehiculo.vehinumeroejes : 'NO REPORTADO'; 
            newFormData.motorRegrabado            = vehiculo.motorRegrabado;
            newFormData.chasisRegrabado           = vehiculo.chasisRegrabado;
            newFormData.serieRegrabado            = vehiculo.serieRegrabado;
            newFormData.observacion               = vehiculo.vehiobservacion;
            newFormData.showFotografia            = (vehiculo.vehirutafoto !== null) ? vehiculo.rutaFotografia : imagenVehiculo;
            newFormData.nombreAsociado            = vehiculo.nombreAsociado;
            newFormData.estadoActual              = vehiculo.estadoActual;
            newFormData.totalCambioEstadoVehiculo = vehiculo.totalCambioEstadoVehiculo;
            newFormData.totalSolicitudVehiculo    = vehiculo.totalSolicitudVehiculo;     
           
            setSolicitudVehiculos(res.solicitudVehiculos);
            setCambiosEstadoVehiculo(res.cambiosEstadoVehiculo);            
            setFormData(newFormData);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid container spacing={2}>
        
            <Grid item xl={10} md={10} sm={12} xs={12}>
                <Grid container spacing={2}>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Tipo de vehículo</label>
                            <span>{formData.tipoVehiculo}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Tipo de referencia</label>
                            <span>{formData.tipoReferencia}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Tipo de marca</label>
                            <span>{formData.tipoMarca}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Tipo de carrocería</label>
                            <span>{formData.tipoCarroceria}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Tipo de color</label>
                            <span>{formData.tipoColor}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Tipo de combstible</label>
                            <span>{formData.tipoCombustible}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Tipo de modalidad</label>
                            <span>{formData.tipoModalidad}</span>
                        </Box>
                    </Grid> 
                    
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Agencia</label>
                            <span>{formData.agencia}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Fecha de ingeso</label>
                            <span>{formData.fechaIngreso}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Número interno</label>
                            <span>{formData.numeroInterno}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Placa</label>
                            <span>{formData.placa}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Modelo</label>
                            <span>{formData.modelo}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Cilindraje</label>
                            <span>{formData.cilindraje}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Número de motor</label>
                            <span>{formData.numeroMotor}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Número de chasis</label>
                            <span>{formData.numeroChasis}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Número de serie</label>
                            <span>{formData.numeroSerie}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Número de ejes</label>
                            <span>{formData.numeroEjes}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Motor regrabado</label>
                            <span>{formData.motorRegrabado}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Chasis regrabado</label>
                            <span>{formData.chasisRegrabado}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Serie regrabado</label>
                            <span>{formData.serieRegrabado}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={6} md={6} sm={12} xs={12}>
                        <Box className='frmTexto'>
                            <label>Asociado</label>
                            <span>{formData.nombreAsociado}</span>
                        </Box>
                    </Grid>

                    {(formData.observacion !== null) ?
                        <Grid item xl={6} md={6} sm={12} xs={12}>
                            <Box className='frmTexto'>
                                <label>Observación general</label>
                                <span>{formData.observacion}</span>
                            </Box>
                        </Grid>
                    : null }

                </Grid>
            </Grid>

            <Grid item xl={2} md={2} sm={12} xs={12}>
                <Grid container spacing={2}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='frmTexto'>
                            <label>Fotografia</label>
                            <Box className='fotografiaVehiculo' style={{marginTop: '0.6em'}}>
                                <img src={formData.showFotografia} ></img>
                            </Box>
                        </Box>
                    </Grid>

                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='frmTexto'>
                            <label>Estado actual</label>
                            <span>{formData.estadoActual}</span>
                        </Box>
                    </Grid>
                </Grid>
            </Grid>

            {(formData.totalSolicitudVehiculo > 0) ? 
                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <SolicitudVehiculo data={solicitudVehiculos} />
                </Grid>
            : null }

            {(formData.totalCambioEstadoVehiculo > 0) ? 
                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Trazabilidad mensaje='Cambio de estado del vehículo' data={cambiosEstadoVehiculo}/>
                </Grid>
            : null }

        </Grid>
    )   
}
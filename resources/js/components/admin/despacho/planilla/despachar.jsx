import React, {useState, useEffect, Fragment} from 'react';
import { ValidatorForm } from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import { Button, Grid, Stack, Box} from '@mui/material';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';

export default function Despachar({data}){

    const [formData, setFormData] = useState({codigo:data.plarutid, numeroPlanilla:'', fechaRegistro:'', fechaSalida:'', ruta:'', vehiculo:'', conductor: ''});
    const [abrirModal, setAbrirModal] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false);

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/despacho/planilla/registrar/salida', {codigo:data.plarutid, conductor: data.condid, vehiculo: data.vehiid}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            (res.success) ? setAbrirModal(true) : setAbrirModal(false);
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/despacho/planilla/consultar/datos', {codigo:formData.codigo}).then(res=>{
            let planillaRuta             = res.planillaRuta;
            newFormData.numeroPlanilla   = planillaRuta.numeroPlanilla;
            newFormData.fechaRegistro    = planillaRuta.plarutfechahoraregistro;
            newFormData.fechaSalida      = planillaRuta.plarutfechahorasalida;
            newFormData.ruta             = planillaRuta.nombreRuta;
            newFormData.vehiculo         = planillaRuta.nombreVehiculo;
            newFormData.conductor        = planillaRuta.nombreConductor;
            newFormData.totalEncomiendas = planillaRuta.totalEncomiendas;            
            setFormData(newFormData);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <ValidatorForm onSubmit={handleSubmit}>

                <Grid container spacing={2}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='divisionFormulario'>
                            Información de la ruta
                        </Box>
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Número de planilla</label>
                            <span>{formData.numeroPlanilla}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Fecha de registro</label>
                            <span>{formData.fechaRegistro}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Fecha de salida</label>
                            <span>{formData.fechaSalida}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Ruta</label>
                            <span>{formData.ruta}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Vehículo</label>
                            <span>{formData.vehiculo}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Conductor</label>
                            <span>{formData.conductor}</span>
                        </Box>
                    </Grid>

                    {(formData.totalEncomiendas > 0) ?
                        <Fragment>
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='divisionFormulario'>
                                    Información de encomienda
                                </Box>
                            </Grid>

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='divisionFormulario'>
                                    Listado de pasajeros
                                </Box>
                            </Grid>
                        </Fragment>
                    : null}

                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <Box className='frmTexto'>

                        </Box>
                    </Grid>

                </Grid>

                <Grid container direction="row"  justifyContent="right">
                    <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                            startIcon={<SaveIcon />}> Despachar
                        </Button>
                    </Stack>
                </Grid>
            </ValidatorForm>

            <ModalDefaultAuto
                title   = {'Visualizar PDF del formato de la planilla'} 
                content = {<VisualizarPdf id={data.plarutid} />} 
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'smallFlot' 
                abrir   = {abrirModal}
            />

       </Box>
    )
}
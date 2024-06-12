import React, {useState, useEffect, Fragment} from 'react';
import { ValidatorForm } from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import { Button, Grid, Stack, Box, Table, TableHead, TableBody, TableRow, TableCell} from '@mui/material';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';

export default function Despachar({data}){

    const [formData, setFormData] = useState({codigo:data.plarutid, numeroPlanilla:'', fechaRegistro:'', fechaSalida:'', ruta:'', vehiculo:'', conductor: ''});
    const [resumenPlanilla, setResumenPlanilla] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [encomiendas, setEncomiendas] = useState([]);    
    const [tiquetes, setTiquetes] = useState([]);
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
            newFormData.totalTiquete     = planillaRuta.totalTiquete;

            setResumenPlanilla(res.resumenPlanilla);
            setEncomiendas(res.encomiendas);
            setTiquetes(res.tiquetes);
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
                                <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                    <Table key={'tablePersona'} className={'tableAdicional'} sx={{width: '98%', margin:'auto'}} sm={{maxHeight: '99%', margin:'auto'}}>
                                        <TableHead>
                                            <TableRow>
                                                <TableCell>Tipo encomienda</TableCell>
                                                <TableCell>Nombre del cliente </TableCell>
                                                <TableCell>Destino</TableCell>
                                                <TableCell>Valor envío</TableCell>
                                                <TableCell>Valor declarado</TableCell>
                                                <TableCell>Comisión seguro</TableCell>
                                                <TableCell>Comisión empresa</TableCell>
                                                <TableCell>Comisión agencia</TableCell>
                                                <TableCell>Comisión vehículo</TableCell>
                                                <TableCell>Valor total </TableCell>
                                            </TableRow>
                                        </TableHead>
                                        <TableBody>

                                        { encomiendas.map((enc, a) => {
                                            return(
                                                <TableRow key={'rowT-' +a}>
                                                    <TableCell>
                                                        {enc['tipoEncomienda']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {enc['nombrePersonaRemitente']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {enc['destinoEncomienda']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {enc['valorEnvio']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {enc['valorDeclarado']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {enc['comisionSeguro']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {enc['comisionEmpresa']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {enc['comisionAgencia']}
                                                    </TableCell> 

                                                    <TableCell>
                                                        {enc['comisionVehiculo']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {enc['valorTotal']}
                                                    </TableCell>

                                                </TableRow>
                                                );
                                            })
                                        }
                                        </TableBody>
                                    </Table>
                                </Box>
                            </Grid>
                        </Fragment>
                    : null}

                    {(formData.totalTiquete > 0) ?
                        <Fragment>
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='divisionFormulario'>
                                    Listado de pasajeros
                                </Box>
                            </Grid>
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                    <Table key={'tablePersona'} className={'tableAdicional'} sx={{width: '98%', margin:'auto'}} sm={{maxHeight: '99%', margin:'auto'}}>
                                        <TableHead>
                                            <TableRow>
                                                <TableCell>Agencia</TableCell>
                                                <TableCell>Nombre del cliente </TableCell>
                                                <TableCell>Número tiquete</TableCell>
                                                <TableCell>Destino</TableCell>
                                                <TableCell>Valor tiquete</TableCell>
                                                <TableCell>Valor seguro</TableCell>
                                                <TableCell>Valor descuento</TableCell>
                                                <TableCell>Valor fondo reposición</TableCell>
                                                <TableCell>Valor total tiquete</TableCell>
                                            </TableRow>
                                        </TableHead>
                                        <TableBody>  

                                        { tiquetes.map((tiq, a) => {                                            
                                            return(
                                                <TableRow key={'rowT-' +a}>
                                                    <TableCell>
                                                        {tiq['nombreAgencia']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {tiq['nombreCliente']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {tiq['numeroTiquete']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {tiq['municipioDestino']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {tiq['valorTiquete']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {tiq['valorSeguro']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {tiq['valorDescuento']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {tiq['valorValorfondoReposicion']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {tiq['valorTotalTiquete']}
                                                    </TableCell>

                                                </TableRow>
                                                );
                                            })
                                        }
                                        </TableBody>
                                    </Table>
                                </Box>
                            </Grid>
                        </Fragment>
                    : null}

                    <Grid item xl={5} md={5} sm={4} xs={2}></Grid>
                    <Grid item xl={7} md={7} sm={8} xs={10}>
                        <Box className='divisionFormulario'>
                            Resumen de la información para ser entregados al conductor
                        </Box>
                        <Table key={'tablePersona'} className={'tableAdicional'} sx={{width: '98%', margin:'auto'}} sm={{maxHeight: '99%', margin:'auto'}}>                                                       
                            <TableBody> 
                                <TableCell>
                                    Valor pasajes
                                </TableCell>
                                <TableCell>
                                    {resumenPlanilla.valorTiquete}
                                </TableCell>
                            </TableBody>
                            {(resumenPlanilla.valorEncomiendaVehiculo > 0) ?
                                <TableBody> 
                                    <TableCell>
                                        Comisión encomienda
                                    </TableCell>
                                    <TableCell>
                                        {resumenPlanilla.valorEncomiendaVehiculo}
                                    </TableCell>
                                </TableBody>
                            : null}
                            <TableBody> 
                                <TableCell>
                                    Descuento fondo recaudo
                                </TableCell>
                                <TableCell>
                                    {resumenPlanilla.valorFondoRecaudo}
                                </TableCell>
                            </TableBody>

                            <TableBody> 
                                <TableCell>
                                    Total a entregar
                                </TableCell>
                                <TableCell>
                                    {resumenPlanilla.valorEntregar}
                                </TableCell>
                            </TableBody>
                        </Table> 
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
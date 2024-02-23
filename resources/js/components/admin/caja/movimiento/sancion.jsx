import React, {useState, useEffect, Fragment} from 'react';
import { Button, Grid, Stack, Icon, Autocomplete, createFilterOptions, Box, Card, Table, TableHead, TableBody, TableRow, TableCell, FormGroup, FormControlLabel, Checkbox,} from '@mui/material';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import SearchIcon from '@mui/icons-material/Search';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import VisualizarPdf from './visualizarPdf';

export default function Sancion(){

    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [formData, setFormData] = useState({vehiculoId:'',  totalAPagar:'', totalSancion:''});
    const [sancionesAsociado, setSancionesAsociado] = useState([]);
    const [formDataSancion, setFormDataSancion] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);
    const [dataFactura, setDataFactura] = useState('');
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChangeSancion = (e) =>{
        let newFormData        = {...formData}
        let newFormDataSancion = [...formDataSancion];
        let totalSancion       = 0;
        (e.target.checked) ? newFormDataSancion.push({asosanid: parseInt(e.target.value)}) :
                            newFormDataSancion = formDataSancion.filter((item) => item.asosanid !== parseInt(e.target.value));

        sancionesAsociado.map((sancion) => {
            newFormDataSancion.map((frmSancion) => {
                if(sancion['asosanid'] === frmSancion.asosanid){
                    totalSancion += Number(sancion['asosanvalorsancion']);
                }
            })
        })

        newFormData.totalAPagar  = totalSancion;
        newFormData.totalSancion = formatearNumero(totalSancion);
        setFormDataSancion(newFormDataSancion);
        setFormData(newFormData);
    }

    const handleSubmit = () =>{
        let newFormData               = {...formData}
        newFormData.sancionesAsociado = formDataSancion;

        if(formDataSancion.length === 0){
            showSimpleSnackbar('Por favor, seleccione al menos una sanción', 'error');
            return;
        }

        setLoader(true);
        instance.post('/admin/caja/registrar/sancion', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            if(res.success){
                setFormData({vehiculoId:'', totalAPagar:'', totalSancion:''});
                setDataFactura(res.dataFactura);
                setFormDataSancion([]);
                setDatosEncontrados(false);
                setAbrirModal(true);
            }
            setLoader(false);
        })
    }

    const consultarSancion = () =>{
        setDatosEncontrados(false);
        if(formData.vehiculoId === ''){
            showSimpleSnackbar("Debe seleccionar un vehículo", 'error');
            return;
        }

        setLoader(true);
        let newFormData         = {...formData}
        let newFormDataSancion = [...formDataSancion];
        instance.post('/admin/caja/consultar/sancion/asociado', {vehiculoId: formData.vehiculoId}).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{
                let sancionesAsociado = res.sancionesAsociado;
                let totalSancion      = 0;
                sancionesAsociado.map((sancion) => {
                    newFormDataSancion.push({asosanid: parseInt(sancion['asosanid'])});
                    totalSancion +=  Number(sancion['asosanvalorsancion']);
                })

                newFormData.totalAPagar  = totalSancion;
                newFormData.totalSancion = formatearNumero(totalSancion);
                setSancionesAsociado(sancionesAsociado);
                setFormDataSancion(newFormDataSancion);
                setFormData(newFormData);
                setDatosEncontrados(true);
            }
            setLoader(false);
        })
    }

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/caja/listar/vehiculos').then(res=>{
            setVehiculos(res.vehiculos); 
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={consultarSancion}>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>
                            <Grid item xl={9} md={9} sm={8} xs={8}>
                                <Autocomplete
                                    id="vehiculo"
                                    style={{height: "26px", width: "100%"}}
                                    options={vehiculos}
                                    getOptionLabel={(option) => option.nombreVehiculo} 
                                    value={vehiculos.find(v => v.vehiid === formData.vehiculoId) || null}
                                    filterOptions={createFilterOptions({ limit:10 })}
                                    onChange={(event, newInputValue) => {
                                        if(newInputValue){
                                            setFormData({...formData, vehiculoId: newInputValue.vehiid})
                                        }
                                    }}
                                    renderInput={(params) =>
                                        <TextValidator {...params}
                                            label="Consultar vehículo"
                                            className="inputGeneral"
                                            variant="standard"
                                            validators={["required"]}
                                            errorMessages="Campo obligatorio"
                                            value={formData.vehiculoId}
                                            placeholder="Consulte el vehículo aquí..." />}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={4} xs={4}>
                                <Stack direction="row" spacing={2} >
                                    <Button type={"submit"} className={'modalBtnBuscar'}
                                        startIcon={<SearchIcon className='icono' />}> Consultar
                                    </Button>
                                </Stack>
                            </Grid>
                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            {(datosEncontrados) ?
                <Box style={{marginTop: '2em'}}>
                    <Card style={{margin: 'auto', width:'70%', padding: '5px'}}>
                        <Grid container spacing={2} >

                            <Grid item md={12} xl={12} sm={12} xs={12} style={{marginBottom: '1em'}}>
                                <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                    <Table key={'tableSubSerie'} className={'tableAdicional'} style={{margin:'auto'}} >
                                        <TableHead>
                                            <TableRow>
                                            <TableCell className='cellCenter'>Seleccionar </TableCell>
                                                <TableCell>Tipo sanción</TableCell>
                                                <TableCell>Fecha sanción </TableCell>
                                                <TableCell>Fecha máxima pago </TableCell>
                                                <TableCell>Motivo </TableCell>
                                                <TableCell>Valor sanción </TableCell>
                                            </TableRow>
                                        </TableHead>
                                        <TableBody>

                                        { sancionesAsociado.map((sancion, a) => {
                                            const sancionChequeada = formDataSancion.find(resul => resul.asosanid === parseInt(sancion['asosanid']));
                                            const marcarCheckbox   = (sancionChequeada !== undefined) ? true : false;
                                            return(
                                                <TableRow key={'rowD-' +a} >
                                                    <TableCell>
                                                        <FormGroup row name={"sancion"} value={formDataSancion.asosanid} onChange={handleChangeSancion}>
                                                            <FormControlLabel value={sancion['asosanid']} control={<Checkbox color="secondary" checked={marcarCheckbox} />}  />
                                                        </FormGroup>
                                                    </TableCell>

                                                    <TableCell>
                                                        {sancion['tipsannombre']}
                                                    </TableCell>

                                                    <TableCell>
                                                        {sancion['fechaSancion']}
                                                    </TableCell>

                                                    <TableCell className='cellCenter'>
                                                        {sancion['asosanfechamaximapago']}
                                                    </TableCell>

                                                    <TableCell>
                                                    {sancion['asosanmotivo']}
                                                    </TableCell>

                                                    <TableCell className='cellCenter'>
                                                        {sancion['valorSancion']}
                                                    </TableCell>
                                                </TableRow>
                                                );
                                            })
                                        }
                                        </TableBody>
                                    </Table>
                                </Box>

                            </Grid>

                            <Grid item md={4} xl={4} sm={6} xs={12} >

                            </Grid>

                           <Grid item md={4} xl={4} sm={6} xs={12} >
                                <Box className='frmTextoColor'>
                                    <label>Valor a pagar: $ </label>
                                    <span  className='textoRojo'>{'\u00A0'+ formData.totalSancion}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={4} md={4} sm={6} xs={12}>
                                <Stack direction="row" spacing={2} style={{float:'right'}}>
                                    <Button type={"button"} className={'modalBtn'} onClick={handleSubmit}
                                        startIcon={<SaveIcon />}> Guardar
                                    </Button>
                                </Stack>
                            </Grid>

                        </Grid> 
                    </Card>
                </Box>
            : null }

            <ModalDefaultAuto
                title   = {'Visualizar factura en PDF de la multa'} 
                content = {<VisualizarPdf dataFactura={dataFactura} />} 
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'smallFlot'
                abrir   = {abrirModal}
            />

        </Fragment>
    )
}
import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Icon, Autocomplete, createFilterOptions, Box, Typography, Card} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import ContentPasteSearchIcon from '@mui/icons-material/ContentPasteSearch';
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';

export default function pagoCredito(){

    const [formData, setFormData] = useState({tipoIdentificacion:'1', documento:''});
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false); 

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/caja/registrar/pago/cuota', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', nombre:'', naturaleza: '', codigoContable: '', estado:'1', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    const consultarCredito = () =>{
        
    }


    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/caja/listar/tipo/documento').then(res=>{
            setTipoIdentificaciones(res.tipoIdentificaciones); 
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);


    if(loader){
        return <LoaderModal />
    }


    return (
        <Fragment>
            <ValidatorForm onSubmit={consultarCredito}>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>
                            <Grid item xl={4} md={4} sm={6} xs={12}>
                                <SelectValidator
                                    name={'tipoIdentificacion'}
                                    value={formData.tipoIdentificacion}
                                    label={'Tipo identificación'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required"]}
                                    errorMessages={["Debe hacer una selección"]}
                                    onChange={handleChange} 
                                >
                                    <MenuItem value={""}>Seleccione</MenuItem>
                                    {tipoIdentificaciones.map(res=>{
                                        return <MenuItem value={res.tipideid} key={res.tipideid} >{res.tipidenombre}</MenuItem>
                                    })}
                                </SelectValidator>
                            </Grid>

                            <Grid item xl={5} md={5} sm={6} xs={12}>
                                <TextValidator 
                                    name={'documento'}
                                    value={formData.documento}
                                    label={'Documento'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 15}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChange}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <Stack direction="row" spacing={2} >
                                    <Button type={"submit"} className={'modalBtn'}
                                        startIcon={<ContentPasteSearchIcon />}> Consultar
                                    </Button>
                                </Stack>

                            </Grid>
                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>
        </Fragment>
    )
}
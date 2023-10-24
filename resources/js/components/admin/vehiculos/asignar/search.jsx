import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, Icon, Box, MenuItem, Stack, Typography, Card, Autocomplete, createFilterOptions} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import Show from '../../persona/show';

export default function Search(){

    const [formData, setFormData] = useState({tipoIdentificacion:'', documento:'', observacionCambio:'', asociadoId:'', tipoEstado:''})
    const [loader, setLoader] = useState(false);
    const [data, setData] = useState([]);
    const [datosEncontrados, setDatosEncontrados] = useState(false);    
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [tipoEstadosAsociados, setTipoEstadosAsociados] = useState([]);
    const [newTipoEstadosAsociados, setNewTipoEstadosAsociados] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
     }

    const consultarAsociado = () =>{
        if(formData.tipoIdentificacion === ''){
            showSimpleSnackbar("Debe seleccionar el tipo de identificación", 'error');
            return;
        }

        if(formData.documento === ''){
            showSimpleSnackbar("Debe ingresar el número de documento", 'error');
            return;
        }

        let newFormData = {...formData}
        setDatosEncontrados(false);
        instance.post('/admin/asociado/consultar', formData).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{
                let tipoEstadoAsociado = [];
                let idEstadoActual     = res.data.tiesasid;
                newFormData.asociadoId = res.data.asocid;

                tipoEstadosAsociados.forEach(function(tpEstado){ 
                    if(tpEstado.tiesasid !== idEstadoActual){
                        tipoEstadoAsociado.push({
                            tiesasid: tpEstado.tiesasid,
                            tiesasnombre: tpEstado.tiesasnombre
                        });
                    }
                });
                setNewTipoEstadosAsociados(tipoEstadoAsociado);
                setDatosEncontrados(true);
                setFormData(newFormData);
                setData(res.data);
            }
            setLoader(false);
        })
    }

    const desvincularAsociado = () =>{
        setLoader(true);
        instance.post('/admin/asociado/desvincular/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setDatosEncontrados(false) : null; 
            (res.success) ? setFormData({tipoIdentificacion:'', documento:'', observacionCambio:'', asociadoId:'', tipoEstado:''}) : null; 
            setLoader(false);
        })
     }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/asociado/desvincular').then(res=>{
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setTipoEstadosAsociados(res.tipoEstadosAsociados);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={consultarAsociado}>
                <Box><Typography  component={'h2'} className={'titleGeneral'}>Desvincular asociados</Typography>
                </Box>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>

                            <Grid item xl={6} md={6} sm={12} xs={12}>
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

                            <Grid item xl={6} md={6} sm={12} xs={12} sx={{position: 'relative'}}>
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
                                <Icon className={'iconLupa'} onClick={consultarAsociado}>search</Icon>
                            </Grid>

                            <Grid item md={4} xl={4} sm={12}>                   
                                <Autocomplete
                                    id="lugar"
                                    style={{height: "26px"}}
                                    options={lugares}
                                    freeSolo
                                    getOptionLabel={(option) => option.tipidenombre} 
                                    value={tipoIdentificaciones.find(v => v.tipideid === formData.tipoIdentificacion) || null}
                                    filterOptions={createFilterOptions({ limit:10 })}
                                    onChange={(event, newInputValue) => {
                                        if(newInputValue){
                                            setFormData({...formData, lugar: newInputValue.tipideid})
                                        }
                                    }}
                                    renderInput={(params) =>
                                        <TextValidator {...params}
                                            label="Lugar"
                                            className="inputGeneral"
                                            variant="standard"
                                            validators={["required"]}
                                            errorMessages="Campo obligatorio"
                                            value={formData.tipoIdentificacion}
                                            placeholder="Consulte el lugar aquí..." />}
                                />
                            </Grid>

                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            {(datosEncontrados) ? 
                <ValidatorForm onSubmit={desvincularAsociado} style={{marginTop: '2em'}}> 
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='frmDivision'>
                                    Información del asociado
                                </Box>
                            </Grid>

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Show id={data.persid} />
                            </Grid>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='frmDivision'>
                                    Información del proceso a realizar
                                </Box>
                            </Grid>

                            <Grid item md={9} xl={9} sm={12} xs={12}>
                                <TextValidator
                                    name={'observacionCambio'}
                                    value={formData.observacionCambio}
                                    label={'Observación del cambio'}
                                    className={'inputGeneral'}
                                    variant={"standard"}
                                    inputProps={{autoComplete: 'off', maxLength: 500}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChange}
                                />
                            </Grid>

                            <Grid item md={3} xl={3} sm={12} xs={12}>
                                <SelectValidator
                                    name={'tipoEstado'}
                                    value={formData.tipoEstado}
                                    label={'Estado'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required"]}
                                    errorMessages={["Debe hacer una selección"]}
                                    onChange={handleChange} 
                                >
                                    <MenuItem value={""}>Seleccione</MenuItem>
                                    {newTipoEstadosAsociados.map(res=>{
                                        return <MenuItem value={res.tiesasid} key={res.tiesasid} >{res.tiesasnombre}</MenuItem>
                                    })}
                                </SelectValidator>
                            </Grid>

                        </Grid>

                        <Grid container direction="row"  justifyContent="right" style={{marginTop: '1em'}}>
                            <Stack direction="row" spacing={2}>
                                <Button type={"submit"} className={'modalBtn'}
                                    startIcon={<SaveIcon />}> Guardar
                                </Button>
                            </Stack>
                        </Grid>
                    </Card>
                </ValidatorForm>
            : null }

        </Fragment>
    )
}
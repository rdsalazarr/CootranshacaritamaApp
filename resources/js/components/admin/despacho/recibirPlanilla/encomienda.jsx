import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, Box, MenuItem, Card} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function Encomienda(){

    const [formData, setFormData] = useState({tipoIdentificacion:'', tipoPersona:'', documento:''});
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/despacho/entregar/encomienda/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setFormData({agencia:'', anyo:'', consecutivo:''}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        instance.get('/admin/despacho/recibir/planilla/list').then(res=>{
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Box className={'containerMedium'}>
                <Card className={'cardContainer'}>
                    <Grid container spacing={2}>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoPersona'}
                                value={formData.tipoPersona}
                                label={'Tipo persona'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                <MenuItem value={"R"}>Remitente</MenuItem>
                                <MenuItem value={"D"}>Destinatario</MenuItem>
                                </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
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

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'consecutivo'}
                                value={formData.consecutivo}
                                label={'Consecutivo'}
                                className={'inputGeneral'}
                                variant={"standard"}
                                inputProps={{autoComplete: 'off', maxLength: 15}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Button type={"submit"} className={'modalBtn'}
                                startIcon={<SaveIcon />}>Consultar
                            </Button>
                        </Grid>

                    </Grid>
                </Card>
            </Box>
        </ValidatorForm>
    )
}
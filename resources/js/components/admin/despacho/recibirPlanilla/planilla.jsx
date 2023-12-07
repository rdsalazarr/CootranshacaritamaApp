import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, Box, MenuItem, Card} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function Search(){

    const [formData, setFormData] = useState({agencia:'', anyo:'', consecutivo:''});
    const [agencias, setAgencias] = useState([]);
    const [loader, setLoader] = useState(false);
    const [anyos, setAnyos] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/despacho/recibir/planilla/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setFormData({agencia:'', anyo:'', consecutivo:''}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        instance.get('/admin/despacho/recibir/planilla/list').then(res=>{
            setAgencias(res.agencias);
            setAnyos((res.anyos.length > 0) ? res.anyos : []);
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

                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <SelectValidator
                                name={'agencia'}
                                value={formData.agencia}
                                label={'Agencia'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {agencias.map(res=>{
                                    return <MenuItem value={res.agenid} key={res.agenid} >{res.agennombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'anyo'}
                                value={formData.anyo}
                                label={'Año'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {anyos.map(res=>{
                                    return <MenuItem value={res.plarutanio} key={res.plarutanio} >{res.plarutanio}</MenuItem>
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
                                inputProps={{autoComplete: 'off'}}
                                validators={["required","maxNumber:9999"]}
                                errorMessages={["campo obligatorio","Número máximo permitido es el 9999"]}
                                onChange={handleChange}
                                type={"number"}
                            />
                        </Grid>

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <Button type={"submit"} className={'modalBtn'}
                                startIcon={<SaveIcon />}>Guardar
                            </Button>
                        </Grid>

                    </Grid>
                </Card>
            </Box>
        </ValidatorForm>
    )
}
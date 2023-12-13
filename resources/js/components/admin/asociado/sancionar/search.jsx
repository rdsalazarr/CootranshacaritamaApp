import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Card, Grid, MenuItem, Typography, Stack, Box} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function Search(){

    const [formData, setFormData] = useState({tipoSancion:'', fechaMaximaPago:'', valorSancion: '', motivo: '', numeroInternoInicial:'', numeroInternoFinal:''}); 
    const [loader, setLoader] = useState(false);
    const [tipoSanciones, setTipoSanciones] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/asociado/sancionar/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setFormData({tipoSancion:'', fechaMaximaPago:'', valorSancion: '', motivo: '', numeroInternoInicial:'', numeroInternoFinal:''}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.get('/admin/asociado/sancionar/datos').then(res=>{
            newFormData.fechaMaximaPago = res.fechaActual;
            setTipoSanciones(res.tipoSanciones);
            setFormData(newFormData);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Card className={'cardContainer'} >
                <Box>
                    <Typography component={'h2'} className={'titleGeneral'} style={{marginBottom: '1em'}}>Gestionar sanción a asociados</Typography>
                </Box>
                <Grid container spacing={2}>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <SelectValidator
                            name={'tipoSancion'}
                            value={formData.tipoSancion}
                            label={'Tipo sanción'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoSanciones.map(res=>{
                                return <MenuItem value={res.tipsanid} key={res.tipsanid} >{res.tipsannombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <NumberValidator fullWidth
                            id={"valorSancion"}
                            name={"valorSancion"}
                            label={"Valor sanción"}
                            value={formData.valorSancion}
                            type={'numeric'}
                            require={['required', 'maxStringLength:7']}
                            error={['Campo obligatorio','Número máximo permitido es el 9999999']}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <TextValidator
                            name={'fechaMaximaPago'}
                            value={formData.fechaMaximaPago}
                            label={'Fecha máxima de pago'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                            type={"date"}
                            InputLabelProps={{
                                shrink: true,
                            }}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator 
                            name={'numeroInternoInicial'}
                            value={formData.numeroInternoInicial}
                            label={'Número interno inicial'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required","maxNumber:999"]}
                            errorMessages={["campo obligatorio","Número máximo permitido es el 999"]}
                            onChange={handleChange}
                            type={"number"}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator 
                            name={'numeroInternoFinal'}
                            value={formData.numeroInternoFinal}
                            label={'Número interno final'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required","maxNumber:999"]}
                            errorMessages={["campo obligatorio","Número máximo permitido es el 999"]}
                            onChange={handleChange}
                            type={"number"}
                        />
                    </Grid>

                    <Grid item xl={6} md={6} sm={6} xs={12}>
                        <TextValidator
                            multiline
                            maxRows={3}
                            name={'motivo'}
                            value={formData.motivo}
                            label={'Motivo'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 500}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChangeUpperCase}
                        />
                    </Grid>
                </Grid>

                <Grid container direction="row" justifyContent="right" style={{marginTop:'1em'}}>
                    <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'}
                            startIcon={<SaveIcon />}> Guardar
                        </Button>
                    </Stack>
                </Grid>

            </Card>
        </ValidatorForm>
    )
}
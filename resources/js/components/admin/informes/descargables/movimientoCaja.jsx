import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import DownloadingIcon from '@mui/icons-material/Downloading';
import { Button, Grid, Box, Stack, Card} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';

export default function MovimientoCaja(){

    const [formData, setFormData] = useState({fechaInicial:'', fechaFinal:''});
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/informes/descargable/moviemiento/caja', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            //(res.success) ? (setDataPdf(res.dataComprobante), setAbrirModal(true)) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Box className={'containerSmall'}>
                <Card className={'cardContainer'}>
                    <Grid container spacing={2}>
                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator
                                name={'fechaInicial'}
                                value={formData.fechaInicial}
                                label={'Fecha inicial'}
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
                        <Grid item xl={4} md={4} sm={6} xs={12}>
                            <TextValidator
                                name={'fechaFinal'}
                                value={formData.fechaFinal}
                                label={'Fecha final'}
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
                        <Grid item xl={4} md={4} sm={6} xs={12} >
                            <Stack direction="row" spacing={2} style={{ float:'right'}}>
                                <Button type={"submit"} className={'modalBtn'}
                                    startIcon={<DownloadingIcon />}> Descargar
                                </Button>
                            </Stack>
                        </Grid>
                    </Grid>
                </Card>
            </Box>

        </ValidatorForm>
    )
}
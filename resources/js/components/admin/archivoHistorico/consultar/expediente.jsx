import React, {useState, useEffect} from 'react';
import { ValidatorForm, SelectValidator} from 'react-material-ui-form-validator';
import ScreenSearchDesktopIcon from '@mui/icons-material/ScreenSearchDesktop';
import { Button, Grid, Card, Box, MenuItem, Stack } from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import instancePdf from '../../../layout/instancePdf';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';

export default function Expediente(){

    const [formData, setFormData] = useState({tipoDocumental:'000', estante: '', caja:'', carpeta: ''});
    const [mostarDatos, setMostarDatos] = useState(false);
    const [loader, setLoader] = useState(false);
    const [tipoDocumentales, setTipoDocumentales ] = useState([]);
    const [tipoEstanteArchivadores, setTipoEstanteArchivadores] = useState([]);
    const [tipoCajaUbicaciones, setTipoCajaUbicaciones] = useState([]);
    const [tipoCarpetaUbicaciones, setTipoCarpetaUbicaciones] = useState([]);
    const [pdf, setPdf] = useState(); 

    const handleChange = (e) =>{
      setFormData(prev => ({...prev, [e.target.name]: e.target.value}));
    }

    const handleSubmit = () =>{
       // setLoader(true);
        setMostarDatos(false);
        let consultarPdf = false;
        instance.post('/admin/archivo/historico/consultar/expediente', formData).then(res=>{
            (!res.success) ? showSimpleSnackbar(res.message, 'error') : null;
            if(res.succes){
                consultarPdf = true;

                console.log("consulta con exito");

                instancePdf.post('/admin/archivo/historico/consultar/expediente/pdf', formData).then(resPdf=>{
                    console.log("Generando pdf");
                    let url = 'data:application/pdf;base64,'+resPdf.data;
                    setPdf(url);
                    setMostarDatos(true);
                    setLoader(false);
                });

            }
            setLoader(false);
        })

        console.log(consultarPdf);

        /*if(consultarPdf){
            setLoader(true);
            instancePdf.post('/admin/archivo/historico/consultar/expediente/pdf', formData).then(resPdf=>{
                let url = 'data:application/pdf;base64,'+resPdf.data;
                setPdf(url);
                setMostarDatos(true);
                setLoader(false);
            });
        }*/
    }

    const inicio = () =>{
        setLoader(true);    
        instance.get('/admin/archivo/historico/obtener/datos/consulta').then(res=>{
            setTipoDocumentales(res.tipoDocumentales);
            setTipoEstanteArchivadores(res.tipoEstanteArchivadores);
            setTipoCajaUbicaciones(res.tipoCajaUbicaciones);
            setTipoCarpetaUbicaciones(res.tipoCarpetaUbicaciones);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'container'} >
            <ValidatorForm onSubmit={handleSubmit} >
                <Card style={{padding: '5px', width: '90%', margin: 'auto', marginTop: '1em' }}>
                    <Grid container spacing={2}>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoDocumental'}
                                value={formData.tipoDocumental}
                                label={'Tipo documental'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange}
                            >
                                <MenuItem value={"000"}>Todos...</MenuItem>
                                {tipoDocumentales.map(res=>{
                                    return <MenuItem value={res.tipdocid} key={res.tipdocid}>{res.tipdocnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'estante'}
                                value={formData.estante}
                                label={'Estante'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange}
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoEstanteArchivadores.map(res=>{
                                    return <MenuItem value={res.tiesarid} key={res.tiesarid}>{res.tiesarnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'caja'}
                                value={formData.caja}
                                label={'Caja'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange}
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoCajaUbicaciones.map(res=>{
                                    return <MenuItem value={res.ticaubid} key={res.ticaubid}>{res.ticaubnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'carpeta'}
                                value={formData.carpeta}
                                label={'Carpeta'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange}
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoCarpetaUbicaciones.map(res=>{
                                    return <MenuItem value={res.ticrubid} key={res.ticrubid}>{res.ticrubnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                    </Grid>

                    <Grid item md={12} xl={12} sm={12} style={{float: 'right', marginTop: '1em'}}>
                        <Stack direction="row" spacing={2}>
                            <Button type={"submit"} className={'modalBtn'}
                                startIcon={<ScreenSearchDesktopIcon />}> Consultar
                            </Button>
                        </Stack>
                    </Grid>

                </Card>
            </ValidatorForm>

            {mostarDatos ? 
                <Card style={{marginTop:'1em'}}>
                    <iframe style={{width: '100%', height: '40em', border: 'none'}} 
                    src={pdf} />
                </Card>
            : null }
            </Box>
    )
}
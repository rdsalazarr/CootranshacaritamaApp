import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator} from 'react-material-ui-form-validator';
import ScreenSearchDesktopIcon from '@mui/icons-material/ScreenSearchDesktop';
import { Button, Grid, Card, Box, MenuItem, Stack } from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import instanceFile from '../../../layout/instanceFile';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Show from '../show';

export default function Consultar(){

    const [formData, setFormData] = useState({fechaInicial:'', fechaFinal: '', tipoDocumental:'000', estante: '000', caja:'000', carpeta: '000', asuntoDocumento:''});
    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});
    const [tipoEstanteArchivadores, setTipoEstanteArchivadores] = useState([]);
    const [tipoCarpetaUbicaciones, setTipoCarpetaUbicaciones] = useState([]);
    const [tipoCajaUbicaciones, setTipoCajaUbicaciones] = useState([]);
    const [tipoDocumentales, setTipoDocumentales ] = useState([]);
    const [mostarDatos, setMostarDatos] = useState(false);
    const [loader, setLoader] = useState(false);
    const [data, setData] = useState([]);

    const handleChange = (e) =>{
      setFormData(prev => ({...prev, [e.target.name]: e.target.value}));
    }

    const edit = (data) =>{      
        setModal({open: true, vista: 0, data:data, titulo: 'Visualizar información del archivo histórico', tamano: 'bigFlot'});
    }

    const handleSubmit = () =>{
      setLoader(true);
      instance.post('/admin/archivo/historico/consultar/datos', formData).then(res=>{
        (!res.success) ? showSimpleSnackbar(res.message, 'error') : null;
        (res.success) ? setMostarDatos(true) : setMostarDatos(false); 
        (res.success) ? setData(res.data) : null;
        setLoader(false);
      })
    }

    const descargarFile = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/consulta/archivo/historico', formData).then(res=>{
            setLoader(false);
        })
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
        <Box>
            <ValidatorForm onSubmit={handleSubmit} >
                <Card style={{padding: '5px', width: '80%', margin: 'auto', marginTop: '1em' }}>
                    <Grid container spacing={2}>
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoDocumental'}
                                value={formData.tipoDocumental}
                                label={'Tipo documental'} 
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
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
                                onChange={handleChange}
                            >
                                <MenuItem value={"000"}>Todos...</MenuItem>
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
                                onChange={handleChange}
                            >
                                <MenuItem value={"000"}>Todas...</MenuItem>
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
                                onChange={handleChange} 
                            >
                                <MenuItem value={"000"}>Todas...</MenuItem>
                                {tipoCarpetaUbicaciones.map(res=>{
                                    return <MenuItem value={res.ticrubid} key={res.ticrubid}>{res.ticrubnombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator 
                                name={'fechaInicial'}
                                value={formData.fechaInicial}
                                label={'Fecha inicial'}
                                className={'inputGeneral'} 
                                variant={"standard"}
                                inputProps={{autoComplete: 'off'}}
                                onChange={handleChange}
                                type={"date"}
                                InputLabelProps={{
                                    shrink: true,
                            }}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator 
                                name={'fechaFinal'}
                                value={formData.fechaFinal}
                                label={'Fecha final'}
                                className={'inputGeneral'} 
                                variant={"standard"}
                                inputProps={{autoComplete: 'off'}}
                                onChange={handleChange}
                                type={"date"}
                                InputLabelProps={{
                                    shrink: true,
                            }}
                            />
                        </Grid>

                        <Grid item md={6} xl={6} sm={12} xs={12}>
                            <TextValidator
                                name={'asuntoDocumento'}
                                value={formData.asuntoDocumento}
                                label={'Asunto del documento'}
                                className={'inputGeneral'} 
                                variant={"standard"}
                                inputProps={{autoComplete: 'off', maxLength: 100}}
                                onChange={handleChange}
                            />
                        </Grid>

                    </Grid>

                    <Grid item md={12} xl={12} sm={12} xs={12} style={{float: 'right', marginTop: '1em'}}>
                        <Stack direction="row" spacing={2}>
                            <Button type={"submit"} className={'modalBtn'} 
                                startIcon={<ScreenSearchDesktopIcon />}> Consultar
                            </Button>
                        </Stack>
                    </Grid>
                </Card>
            </ValidatorForm>

            {mostarDatos ? 
                <Card style={{marginTop:'2em', padding: '5px'}}>
                    <Grid container spacing={2} >
                        <Grid item md={12} xl={12} sm={12} xs={12} style={{textAlign: 'center', paddingTop: '2em'}}>
                            <Button class="download-button" type="button" onClick={() => {descargarFile()}}>
                                <Box class="docs">
                                    <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2" stroke="currentColor" height="20" width="20" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line y2="13" x2="8" y1="13" x1="16"></line>
                                    <line y2="17" x2="8" y1="17" x1="16"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline></svg> Descargar excel
                                </Box>
                                <Box class="download">
                                    <svg class="css-i6dzq1" stroke-linejoin="round" stroke-linecap="round" fill="none" stroke-width="2" stroke="currentColor" height="24" width="24" viewBox="0 0 24 24">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line y2="3" x2="12" y1="15" x1="12"></line>
                                    </svg>
                                </Box>
                            </Button>
                        </Grid>

                        <Grid item md={12} xl={12} sm={12} xs={12} style={{marginTop: '-1em'}}>
                            <TablaGeneral
                                datos={data}
                                titulo={["Tipo documento", "Estante", "Caja", "Carpeta", "Asunto", "Número de folios", "Ver"]}
                                ver={["tipoDocumental", "estante", "caja", "carpeta", "asunto", "numeroFolio"]} 
                                accion={[{tipo: 'B', icono : 'visibility', color: 'green',  funcion : (data)=>{edit(data)} }]}
                                funciones={{orderBy: true,search: true, pagination:true}}
                            />
                        </Grid>

                        <ModalDefaultAuto
                            title={modal.titulo}
                            content={<Show id={modal.data.archisid} />}
                            close  ={() =>{setModal({open : false, vista:2, data:{}, titulo:'', tamano: ''})}}
                            tam    ={modal.tamano}
                            abrir  ={modal.open}
                        />

                    </Grid>
                </Card>
            : null }
        </Box>
    )
}
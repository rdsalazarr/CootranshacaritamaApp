import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import { Button, Grid, MenuItem, Stack} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";

import instance from '../../../layout/instance';
import VisualizarPdf from "./visualizarPdf";

export default function ComprobanteContable(){

    const [formData, setFormData] = useState({agencia:'', usuario:'', caja: '', fecha: ''});
    const [usuarioAgencias, setUsuarioAgencias] = useState([]);
    const [cajasUsuario, setCajasUsuario] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);
    const [agencias, setAgencias] = useState([]);
    const [usuarios, setUsuarios] = useState([]);
    const [loader, setLoader] = useState(false);
    const [dataPdf, setDataPdf] = useState(''); 

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/informes/pdf/generar/comprobante/contable', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? (setDataPdf(res.dataComprobante), setAbrirModal(true)) : null;
            setLoader(false);
        })
    }

    const consultarUsuario = (e) => {
        let newFormData         = {...formData}
        newFormData.agencia     = e.target.value;
        const usuariosFiltrados = usuarios.filter(age => age.agenid === e.target.value);

        const usuarioAgencias   = usuariosFiltrados.map(usuario => {
                                    return {
                                        cajaid:        usuario.cajaid,
                                        cajanumero:    usuario.cajanumero,
                                        usuaid:        usuario.usuaid,
                                        nombreUsuario: usuario.nombreUsuario
                                    };
                                });

        setUsuarioAgencias(usuarioAgencias);
        setFormData(newFormData);
    }

    const consultarCaja = (e) => {
        let newFormData             = {...formData}
        newFormData.usuario         = e.target.value;
        const usuarioCajasFiltrados = usuarioAgencias.filter(usua => usua.usuaid === e.target.value);

        const cajasUsuario          = usuarioCajasFiltrados.map(usuario => {
                                            return {
                                                cajaid:     usuario.cajaid,
                                                cajanumero: usuario.cajanumero,
                                            };
                                        });

        setCajasUsuario(cajasUsuario);
        setFormData(newFormData);
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/informes/pdf/comprobante/contable').then(res=>{
            setAgencias(res.agencias);
            setUsuarios(res.usuarios);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Grid container spacing={2}>
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'agencia'}
                        value={formData.agencia}
                        label={'Agencia'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={consultarUsuario} 
                    >
                        <MenuItem value={""}>Seleccione </MenuItem>
                        {agencias.map(res=>{
                           return <MenuItem value={res.agenid} key={res.agenid} >{res.agennombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'usuario'}
                        value={formData.usuario}
                        label={'Usuario'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={consultarCaja} 
                    >
                        <MenuItem value={""}>Seleccione </MenuItem>
                        {usuarioAgencias.map(res=>{
                           return <MenuItem value={res.usuaid} key={res.usuaid} >{res.nombreUsuario}</MenuItem>
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
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione </MenuItem>
                        {cajasUsuario.map(res=>{
                           return <MenuItem value={res.cajaid} key={res.cajaid} >{res.cajanumero}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'fecha'}
                        value={formData.fecha}
                        label={'Fecha'}
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

            </Grid>

            <Grid container direction="row"  justifyContent="right" style={{marginTop:'1em'}}>
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'}
                        startIcon={<PictureAsPdfIcon />}> Generar
                    </Button>
                </Stack>
            </Grid>

            <ModalDefaultAuto
                title={'Visualizar reporte en formato PDF'}
                content={<VisualizarPdf data={dataPdf} />}
                close={() =>{setAbrirModal(false);}}
                tam = {'mediumFlot'}
                abrir ={abrirModal}
            />
        </ValidatorForm>
    )
}
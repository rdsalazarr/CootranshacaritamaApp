import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Card} from '@mui/material';
import showSimpleSnackbar from '../../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../../layout/modal';
import TablaGeneral from '../../../../layout/tablaGeneral';
import {LoaderModal} from "../../../../layout/loader";
import SearchIcon from '@mui/icons-material/Search';
import instance from '../../../../layout/instance';
import PagarCuota from "./pagarCuota";

export default function Search(){
  
    const [formData, setFormData] = useState({tipoIdentificacion:'1', documento:''});
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [creditoAsociados, setCreditoAsociados] = useState([]);    
    const [abrirModal, setAbrirModal] = useState(false);
    const [modal, setModal] = useState({data:{}});
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const cerrarModal = () =>{
        setAbrirModal(false);
        setFormData({tipoIdentificacion:'1', documento:''});
        setDatosEncontrados(false);
    }

    const openModal = (data) =>{
        setModal({data:data});
        setAbrirModal(true);
    }

    const consultarCredito = () =>{
        setLoader(true);
        setDatosEncontrados(false);
        instance.post('/admin/caja/consultar/credito/asociado', formData).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{
                setCreditoAsociados(res.creditoAsociados); 
                setDatosEncontrados(res.datosEncontrado)
            }
            setLoader(false);
        })
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
                                    <Button type={"submit"} className={'modalBtnIcono'}
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
                    <Grid container spacing={2} style={{margin: 'auto', width:'90%'}}>
                        <Grid item md={12} xl={12} sm={12} xs={12} >
                            <TablaGeneral 
                                datos={creditoAsociados}
                                titulo={['Persona','Número de colocacion','Fecha desembolso','Línea de crédito','Valor desembolsado', 'Fecha cuota','Procesar']}
                                ver={["nombrePersona", "numeroColocacion", "colofechacolocacion","lincrenombre","valorDesembolsado", "colliqfechavencimiento"]}
                                accion={[{tipo: 'B', icono : 'monetization_on_icon', color: 'green', funcion : (data)=>{openModal(data)} }]}
                                funciones={{orderBy: false, search: false, pagination:false}}
                            />
                        </Grid>
                    </Grid>

                    <ModalDefaultAuto
                        title   = {'Pagar cuota de crédito'}
                        content = {<PagarCuota data={modal.data} cerrarModal={cerrarModal}  />}
                        close   = {() =>{(setAbrirModal(false), inicio())}} 
                        tam     = 'mediumFlot'
                        abrir   = {abrirModal}
                    />
                    
                </Box>
            : null }

        </Fragment>
    )
}
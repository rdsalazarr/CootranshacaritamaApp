import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, Box, MenuItem, Card} from '@mui/material';
import { EntregarEncomienda } from '../../../layout/modalFijas';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import SearchIcon from '@mui/icons-material/Search';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';

export default function Encomienda(){

    const [formData, setFormData] = useState({tipoPersona:'D', tipoIdentificacion:'', documento:''});
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [modal, setModal]   = useState({open : false, data:{}});
    const [listaPersonas, setListaPersonas] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const cerrarModal = () =>{
        setModal({open : false, data:{}});
    }

    const edit = (data) =>{
        setModal({open: true, data:data});
    }

    const consultarPersona = () =>{
        setLoader(true);
        setListaPersonas([]);
        instance.post('/admin/despacho/consultar/persona/entregar/encomienda', formData).then(res=>{
            (!res.success) ? showSimpleSnackbar(res.message, 'error') : setListaPersonas(res.data);
            setLoader(false);
        })
    }

    const limpiar = () =>{
       setFormData({tipoPersona:'D', tipoIdentificacion:'', documento:''});
       setListaPersonas([]);
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
        <Fragment>
            <ValidatorForm onSubmit={consultarPersona}>
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
                                <Button type={"submit"} className={'modalBtn'}
                                    startIcon={<SearchIcon />}>Consultar
                                </Button>
                            </Grid>

                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            {(listaPersonas.length > 0) ?
                <Grid container spacing={2} style={{marginTop:'1em'}}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                        <TablaGeneral
                            datos={listaPersonas}
                            titulo={['Fecha registo','Tipo encomienda','Ruta','Destino', 'Remitente','Destinatario', 'Pago contraentrega', 'Entregar']}
                            ver={["fechaHoraRegistro","tipoEncomienda","nombreRuta", "destinoEncomienda","nombrePersonaRemitente","nombrePersonaDestino","pagoContraEntrega"]}
                            accion={[ {tipo: 'B', icono : 'local_shipping_icon', color: 'red', funcion : (data)=>{edit(data, 0)} }]}
                            funciones={{orderBy: false, search: false, pagination:false }}
                        />
                    </Grid>
                </Grid>               
            : null }

            <ModalDefaultAuto
                title   = {'Entregar encomienda'}
                content = {<EntregarEncomienda id={modal.data.encoid} cerrarModal={cerrarModal}/>}
                close   = {() =>{setModal({open : false, data:{}}); limpiar();}}
                tam     = {'smallFlot'}
                abrir   = {modal.open}
            />

        </Fragment>
    )
}
import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Icon, Autocomplete, createFilterOptions, Box, Card} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import PagarMensualidad from "./pagarMensualidad";
import instance from '../../../layout/instance';

export default function Mensualidad(){

    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    const [vehiculoResponsabilidadesFiltrados, setVehiculosResponsabilidadesFiltrados] = useState([]);
    const [vehiculoResponsabilidades, setVehiculosResponsabilidades] = useState([]);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [formData, setFormData] = useState({vehiculoId:''});
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);    

    const modales     = [<PagarMensualidad data={vehiculoResponsabilidadesFiltrados}  />];
    const tituloModal = ['Pagar mensualidad'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'mediumFlot'});
    }

    const consultarVehiculo = () =>{
        setDatosEncontrados(false);
        if(formData.vehiculoId === ''){
            showSimpleSnackbar("Debe seleccionar un vehículo", 'error');
            return;
        }
        const vehiculoResponsabilidadesFiltrados  = vehiculoResponsabilidades.filter(vehiculo => vehiculo.vehiid === formData.vehiculoId);
        (vehiculoResponsabilidadesFiltrados.length > 0) ? setDatosEncontrados(true) : showSimpleSnackbar('No se encuentra registro para este vehículo', 'error');
        setVehiculosResponsabilidadesFiltrados(vehiculoResponsabilidadesFiltrados);
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/caja/listar/vehiculos').then(res=>{
            setVehiculosResponsabilidades(res.vehiculoResponsabilidades);
            setVehiculos(res.data); 
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={consultarVehiculo}>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>
                            <Grid item xl={11} md={11} sm={10} xs={9}>
                                <Autocomplete
                                    id="vehiculo"
                                    style={{height: "26px", width: "100%"}}
                                    options={vehiculos}
                                    getOptionLabel={(option) => option.nombreVehiculo} 
                                    value={vehiculos.find(v => v.vehiid === formData.vehiculoId) || null}
                                    filterOptions={createFilterOptions({ limit:10 })}
                                    onChange={(event, newInputValue) => {
                                        if(newInputValue){
                                            setFormData({...formData, vehiculoId: newInputValue.vehiid})
                                        }
                                    }}
                                    renderInput={(params) =>
                                        <TextValidator {...params}
                                            label="Consultar vehículo"
                                            className="inputGeneral"
                                            variant="standard"
                                            validators={["required"]}
                                            errorMessages="Campo obligatorio"
                                            value={formData.vehiculoId}
                                            placeholder="Consulte el vehículo aquí..." />}
                                />
                                <br />
                            </Grid>

                            <Grid item xl={1} md={1} sm={2} xs={3} sx={{position: 'relative'}}>
                                <Icon className={'iconLupa'} onClick={consultarVehiculo}>search</Icon>
                                <br />
                            </Grid>
                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            {(datosEncontrados) ?
                <Box style={{marginTop: '2em'}}>
                    <Grid container spacing={2} style={{margin: 'auto', width:'70%'}}>
                        <Grid item md={12} xl={12} sm={12} xs={12} >
                            <TablaGeneral 
                                datos={vehiculoResponsabilidadesFiltrados}
                                titulo={['Fecha compromiso','Valor','Pagar']}
                                ver={[ "vehresfechacompromiso", "valorResponsabilidad"]}
                                accion={[{tipo: 'B', icono : 'monetization_on_icon', color: 'red', funcion : (data)=>{edit(data, 0)} }]}
                                funciones={{orderBy: false, search: false, pagination:false}}
                            />
                        </Grid>
                    </Grid>

                    <ModalDefaultAuto
                        title={modal.titulo}
                        content={modales[modal.vista]}
                        close={() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''});}}
                        tam = {modal.tamano}
                        abrir ={modal.open}
                    />
                    
                </Box>
            : null }
        </Fragment>
    )
}
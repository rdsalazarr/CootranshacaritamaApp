import React, {useState, useEffect, Fragment} from 'react';
import {TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, MenuItem, Stack, Box, Link, Table, TableHead, TableBody, TableRow, TableCell, Avatar, Autocomplete, createFilterOptions} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import AddIcon from '@mui/icons-material/Add';
import VisualizarPdf from './visualizarPdf';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
        (tipo !== 'I') ? {codigo:data.coseesid,  tipo:tipo,
                        } : {codigo:'000',      documento:'', tipoIdentificacion: '',  primerNombre:'',         segundoNombre: '', primerApellido: '', segundoApellido:'',
                            fechaNacimiento:'', direccion:'', correo:'',              telefonoCelular: '',      tipoConvenio:'',   fechaInicial:'',    fechaFinal:'',      
                            origen:'',          destino:'',   tipoContrato:'',        descripcionRecorrido: '', observaciones: '', tipo:tipo
                    }); 

    const [modal, setModal] = useState({open : false, extencion:'', ruta:''}); 
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [tipoContratosServicioEspecial, setTipoContratosServicioEspecial] = useState([]);
    const [tipoConveniosServicioEspecial, setTipoConveniosServicioEspecial] = useState([]);
    const [vehiculosServicioEspecial, setVehiculosServicioEspecial] = useState([]);
    const [idServicioEspecial , setIdServicioEspecial] = useState(0);
    const [abrirModal, setAbrirModal] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [conductores, setConductores] = useState([]);
    const [esEmpresa, setEsEmpresa] = useState(false);
    const [vehiculos, setVehiculos] = useState([]);
    const [loader, setLoader] = useState(false);   
    

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }
 
    const handleChangeUpperCase = (e) => {
         setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    }

    const handleSubmit = () =>{
        instance.post('/admin/despacho/servicio/especial/list', {tipo:'ACTIVOS'}).then(res=>{
            setData(res.data);
            setLoader(false);
        })
    }

    const adicionarFilaVehiculos = () =>{

    }

    const verificarTipoIdentificacion = (e) =>{
        let newFormData                  = {...formData}
        let tpIdentificacion             = (e.target.name === 'tipoIdentificacion' ) ? e.target.value : formData.tipoIdentificacion;
        newFormData.tipoIdentificacion   = tpIdentificacion;
        setEsEmpresa((e.target.value === 5) ? true : false);
        setFormData(newFormData);
    }

    useEffect(()=>{
       // setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/despacho/servicio/especial/listar/datos', {tipo:tipo, codigo:formData.codigo}).then(res=>{
            setTipoContratosServicioEspecial(res.tipoContratosServicioEspecial);
            setTipoConveniosServicioEspecial(res.tipoConveniosServicioEspecial);
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setVehiculos(res.vehiculos);
            setConductores(res.conductores);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <ValidatorForm onSubmit={handleSubmit} >
              
                <Grid container spacing={2}>

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='divisionFormulario'>
                            Información del contratante
                        </Box>
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
                            onChange={verificarTipoIdentificacion} 
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
                            label={(esEmpresa)? 'NIT' : 'Documento'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 15}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'primerNombre'}
                            value={formData.primerNombre}
                            label={(esEmpresa)? 'Razón social' : 'Primer nombre'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: (esEmpresa) ? 140 : 40}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChangeUpperCase}
                        />
                    </Grid>

                    {(!esEmpresa)?
                        <Fragment>
                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'segundoNombre'}
                                    value={formData.segundoNombre}
                                    label={'Segundo nombre'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 40}}
                                    onChange={handleChangeUpperCase}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'primerApellido'}
                                    value={formData.primerApellido}
                                    label={'Primer apellido'}
                                    className={'inputGeneral'}
                                    variant={"standard"}
                                    inputProps={{autoComplete: 'off', maxLength: 40}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChangeUpperCase}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'segundoApellido'}
                                    value={formData.segundoApellido}
                                    label={'Segundo apellido'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 40}}
                                    onChange={handleChangeUpperCase}
                                />
                            </Grid>
                        </Fragment>
                    : null }

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'direccion'}
                            value={formData.direccion}
                            label={'Dirección'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 100}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'correo'}
                            value={formData.correo}
                            label={'Correo'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 80}}
                            validators={['isEmail']}
                            errorMessages={['Correo no válido']}
                            type={"email"}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'telefonoCelular'}
                            value={formData.telefonoCelular}
                            label={'Teléfono fijo'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 20}}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='divisionFormulario'>
                            Información del sericio
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'tipoConvenio'}
                            value={formData.tipoConvenio}
                            label={'Tipo convenio'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoConveniosServicioEspecial.map(res=>{
                                return <MenuItem value={res.ticossid} key={res.ticossid} >{res.ticossnombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'tipoContrato'}
                            value={formData.tipoContrato}
                            label={'Tipo contrato'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoContratosServicioEspecial.map(res=>{
                                return <MenuItem value={res.ticoseid} key={res.ticoseid} >{res.ticosenombre}</MenuItem>
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

                    <Grid item xl={6} md={6} sm={6} xs={12}>
                        <TextValidator
                            name={'origen'}
                            value={formData.origen}
                            label={'Origen'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 100}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={6} md={6} sm={6} xs={12}>
                        <TextValidator
                            name={'destino'}
                            value={formData.destino}
                            label={'Destino'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 100}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={8} md={8} sm={6} xs={12}>
                        <TextValidator
                            multiline
                            maxRows={3}
                            name={'descripcionRecorrido'}
                            value={formData.descripcionRecorrido}
                            label={'Descripción del recorrido'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 1000}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <TextValidator
                            multiline
                            maxRows={3}
                            name={'observaciones'}
                            value={formData.observaciones}
                            label={'Obseraciones'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 1000}}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='divisionFormulario'>
                            Asignación de vehículos contratados
                        </Box>
                    </Grid>

                    <Grid item xl={2} md={2} sm={1} xs={1}>
                    </Grid>

                    <Grid item xl={8} md={8} sm={10} xs={9}>
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
                                    value={formData.vehiculoId}
                                    placeholder="Consulte el vehículo aquí..." />}
                        />
                    </Grid>

                    <Grid item xl={2} md={2} sm={12} xs={12}>
                        <Button type={"button"} className={'modalBtn'} 
                            startIcon={<AddIcon />} onClick={() => {adicionarFilaVehiculos()}}> {"Agregar"}
                        </Button>
                    </Grid>

                    {(vehiculosServicioEspecial.length > 0) ?
                        <Fragment>
                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='divisionFormulario'>
                                    Vehículos adicionados al servicios especial
                                </Box>
                            </Grid>
                            
                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                    <Table key={'tablePersona'} className={'tableAdicional'} xl={{width: '60%', margin:'auto'}} md={{width: '70%', margin:'auto'}}  sx={{width: '80%', margin:'auto'}} sm={{maxHeight: '90%', margin:'auto'}}>
                                        <TableHead>
                                            <TableRow>
                                                <TableCell>Persona</TableCell>
                                                <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                                            </TableRow>
                                        </TableHead>
                                        <TableBody>

                                        { vehiculosServicioEspecial.map((pers, a) => {
                                            return(
                                                <TableRow key={'rowA-' +a} className={(pers['estado'] == 'D')? 'tachado': null}>

                                                    <TableCell>
                                                        <p> {pers['nombrePersona']}</p>
                                                    </TableCell>
                                                    
                                                    <TableCell className='cellCenter'>
                                                        <Icon key={'iconDelete'+a} className={'icon top red'}
                                                                onClick={() => {eliminarFilaVehiculo(a);}}
                                                            >clear</Icon>
                                                    </TableCell>
                                                </TableRow>
                                                );
                                            })
                                        }
                                        </TableBody>
                                    </Table>
                                </Box>
                            </Grid>

                        </Fragment>
                    : null}


                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='divisionFormulario'>
                            Asignación de conductores
                        </Box>
                    </Grid>

                    <Grid item xl={2} md={2} sm={1} xs={1}>
                    </Grid>

                    <Grid item xl={8} md={8} sm={10} xs={9}>
                        <Autocomplete
                            id="vehiculo"
                            style={{height: "26px", width: "100%"}}
                            options={conductores}
                            getOptionLabel={(option) => option.nombreConductor} 
                            value={conductores.find(v => v.vehiid === formData.conductorId) || null}
                            filterOptions={createFilterOptions({ limit:10 })}
                            onChange={(event, newInputValue) => {
                                if(newInputValue){
                                    setFormData({...formData, conductorId: newInputValue.vehiid})
                                }
                            }}
                            renderInput={(params) =>
                                <TextValidator {...params}
                                    label="Consultar conductor"
                                    className="inputGeneral"
                                    variant="standard"
                                    value={formData.conductorId}
                                    placeholder="Consulte el conductor aquí..." />}
                        />
                    </Grid>

                    <Grid item xl={2} md={2} sm={12} xs={12}>
                        <Button type={"button"} className={'modalBtn'} 
                            startIcon={<AddIcon />} onClick={() => {adicionarFilaVehiculos()}}> {"Agregar"}
                        </Button>
                    </Grid>

                </Grid>

                <Grid container direction="row"  justifyContent="right">
                    <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                            startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                        </Button>
                    </Stack>
                </Grid>

            </ValidatorForm>

            <ModalDefaultAuto 
                title   = {'Visualizar formato en PDF del servicio especial'} 
                content = {<VisualizarPdf id={idServicioEspecial} />} 
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'smallFlot' 
                abrir   = {abrirModal}
            />
        </Box>
    )
}
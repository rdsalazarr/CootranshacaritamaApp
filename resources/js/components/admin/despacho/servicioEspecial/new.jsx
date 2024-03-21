import React, {useState, useEffect, Fragment} from 'react';
import {TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, MenuItem, Stack, Box, Icon, Table, TableHead, TableBody, TableRow, TableCell, Avatar, Autocomplete, createFilterOptions} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
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
                        } : {codigo:'000', documento:'',    tipoIdentificacion: '',   primerNombre:'',   segundoNombre: '', primerApellido: '', segundoApellido:'',
                            direccion:'',  correo:'',       telefonoCelular: '',      tipoConvenio:'',   fechaInicial: '',  fechaFinal:'',      origen:'',
                            destino:'',    tipoContrato:'', descripcionRecorrido: '', observaciones: '', personaId: '000',  valorContrato:'',   nombreUnionTemporal: '', tipo:tipo
                    });
    
    const [formDataAdicionarConductor, setFormDataAdicionarConductor] = useState({conductorId:'', nombreConductor: ''});
    const [formDataAdicionarVehiculo, setFormDataAdicionarVehiculo] = useState({vehiculoId:'', nombreVehiculo: ''});
    const [tipoContratosServicioEspecial, setTipoContratosServicioEspecial] = useState([]);
    const [tipoConveniosServicioEspecial, setTipoConveniosServicioEspecial] = useState([]);
    const [conductoresServicioEspecial, setConductoresServicioEspecial] = useState([]);
    const [vehiculosServicioEspecial, setVehiculosServicioEspecial] = useState([]);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
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
        if(vehiculosServicioEspecial.length === 0){
            showSimpleSnackbar('Debe adicionar como mínimo un vehículo', 'error');
            return
        }

        if(conductoresServicioEspecial.length === 0){
            showSimpleSnackbar('Debe adicionar como mínimo un conductor', 'error');
            return
        }

        let newFormData         = {...formData}
        newFormData.vehiculos   = vehiculosServicioEspecial;
        newFormData.conductores = conductoresServicioEspecial;
        setLoader(true); 
        instance.post('/admin/despacho/servicio/especial/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            if(formData.tipo === 'I' && res.success){
                setFormData({codigo:'000', documento:'',    tipoIdentificacion: '',   primerNombre:'',   segundoNombre: '', primerApellido: '', segundoApellido:'',
                            direccion:'',  correo:'',       telefonoCelular: '',      tipoConvenio:'',   fechaInicial: '',  fechaFinal:'',      origen:'',
                            destino:'',    tipoContrato:'', descripcionRecorrido: '', observaciones: '', personaId: '000',  valorContrato:'',   nombreUnionTemporal: '', tipo:tipo });
                setVehiculosServicioEspecial([]);
                setConductoresServicioEspecial([]);
                setIdServicioEspecial(res.planillaId);
                setAbrirModal(true)
            }
            setLoader(false);
        })
    }

    const consultarPersona = (e) =>{
        let newFormData                = {...formData}
        let tpIdentificacion           = (e.target.name === 'tipoIdentificacion' ) ? e.target.value : formData.tipoIdentificacion;
        let documento                  = (e.target.name === 'documento' ) ? e.target.value : formData.documento ;
        newFormData.tipoIdentificacion = tpIdentificacion;
        newFormData.documento          = documento;
       if (tpIdentificacion !=='' && formData.documento !==''){
            setLoader(true);
            instance.post('/admin/despacho/servicio/especial/consultar/persona', {tipoIdentificacion:tpIdentificacion, documento: formData.documento}).then(res=>{
                if(res.success){
                    let personaContrato         = res.data;
                    newFormData.personaId       = personaContrato.pecoseid;
                    newFormData.primerNombre    = personaContrato.pecoseprimernombre;
                    newFormData.segundoNombre   = (personaContrato.pecosesegundonombre !== undefined) ? personaContrato.pecosesegundonombre : '';
                    newFormData.primerApellido  = (personaContrato.pecoseprimerapellido !== undefined) ? personaContrato.pecoseprimerapellido : '';
                    newFormData.segundoApellido = (personaContrato.pecosesegundoapellido !== undefined) ? personaContrato.pecosesegundoapellido : '';
                    newFormData.direccion       = (personaContrato.pecosedireccion !== undefined) ? personaContrato.pecosedireccion : '';
                    newFormData.correo          = (personaContrato.pecosecorreoelectronico !== undefined) ? personaContrato.pecosecorreoelectronico : '';
                    newFormData.telefonoCelular = (personaContrato.pecosenumerocelular !== undefined) ? personaContrato.pecosenumerocelular : '';
                }else{
                    newFormData.personaId       = '000';
                    newFormData.primerNombre    = '';
                    newFormData.segundoNombre   = '';
                    newFormData.primerApellido  = '';
                    newFormData.segundoApellido = '';
                    newFormData.direccion       = '';
                    newFormData.correo          = '';
                    newFormData.telefonoCelular = '';      
                }
                setLoader(false);
            })
        }
        setEsEmpresa((e.target.value === 5) ? true : false);
        setFormData(newFormData);
    }

    const adicionarFilaVehiculo = () =>{
        if(formDataAdicionarVehiculo.vehiculoId === ''){
            showSimpleSnackbar('Debe seleccionar un vehículo', 'error');
            return
        }

        if(vehiculosServicioEspecial.some(vehi => vehi.vehiculoId == formDataAdicionarVehiculo.vehiculoId)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newVehiculosServicioEspecial = [...vehiculosServicioEspecial];
        const resultadoNombreVehiculo    = vehiculos.filter((car) => car.vehiid == formDataAdicionarVehiculo.vehiculoId);
        newVehiculosServicioEspecial.push({identificador:'', vehiculoId:formDataAdicionarVehiculo.vehiculoId, nombreVehiculo: resultadoNombreVehiculo[0].nombreVehiculo, estado: 'I'});
        setFormDataAdicionarVehiculo({vehiculoId:'', nombreVehiculo: '' });
        setVehiculosServicioEspecial(newVehiculosServicioEspecial);
    }

    const eliminarFilaVehiculo = (id) =>{
        let newVehiculosServicioEspecial = []; 
        vehiculosServicioEspecial.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newVehiculosServicioEspecial.push({ identificador:res.identificador, vehiculoId: res.vehiculoId, nombreVehiculo:res.nombreVehiculo, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newVehiculosServicioEspecial.push({identificador:res.identificador, vehiculoId: res.vehiculoId, nombreVehiculo:res.nombreVehiculo, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newVehiculosServicioEspecial.push({identificador:res.identificador, vehiculoId: res.vehiculoId, nombreVehiculo:res.nombreVehiculo, estado:res.estado});
            }else{
                if(i != id){
                    newVehiculosServicioEspecial.push({identificador:res.identificador,vehiculoId: res.vehiculoId, nombreVehiculo:res.nombreVehiculo, estado: 'I' });
                }
            }
        })
        setVehiculosServicioEspecial(newVehiculosServicioEspecial);
    }

    const adicionarFilaConductor = () =>{
        if(formDataAdicionarConductor.conductorId === ''){
            showSimpleSnackbar('Debe seleccionar un conductor', 'error');
            return
        }

        if(conductoresServicioEspecial.some(cond => cond.conductorId == formDataAdicionarConductor.conductorId)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newConductoresServicioEspecial = [...conductoresServicioEspecial];
        const resultadoNombreConductor     = conductores.filter((cond) => cond.condid == formDataAdicionarConductor.conductorId);
        newConductoresServicioEspecial.push({identificador:'', conductorId:formDataAdicionarConductor.conductorId, nombreConductor: resultadoNombreConductor[0].nombreConductor, estado: 'I'});
        setFormDataAdicionarConductor({conductorId:'', nombreConductor: '' });
        setConductoresServicioEspecial(newConductoresServicioEspecial);
    }

    const eliminarFilaConductor = (id) =>{
        let newConductoresServicioEspecial = [];
        conductoresServicioEspecial.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newConductoresServicioEspecial.push({ identificador:res.identificador, conductorId: res.conductorId, nombreConductor:res.nombreConductor, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newConductoresServicioEspecial.push({identificador:res.identificador, conductorId: res.conductorId, nombreConductor:res.nombreConductor, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newConductoresServicioEspecial.push({identificador:res.identificador, conductorId: res.conductorId, nombreConductor:res.nombreConductor, estado:res.estado});
            }else{
                if(i != id){
                    newConductoresServicioEspecial.push({identificador:res.identificador,conductorId: res.conductorId, nombreConductor:res.nombreConductor, estado: 'I' });
                }
            }
        })
        setConductoresServicioEspecial(newConductoresServicioEspecial);
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/despacho/servicio/especial/listar/datos', {tipo:tipo, codigo:formData.codigo}).then(res=>{
            setTipoContratosServicioEspecial(res.tipoContratosServicioEspecial);
            setTipoConveniosServicioEspecial(res.tipoConveniosServicioEspecial);
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setVehiculos(res.vehiculos);
            setConductores(res.conductores);

            if(tipo === 'U'){
                let contratoServicioEspecial     = res.contratoServicioEspecial;
                let contratoVehiculos            = res.contratoVehiculos;
                let contratoConductores          = res.contratoConductores;

                newFormData.personaId            = contratoServicioEspecial.pecoseid;
                newFormData.documento            = contratoServicioEspecial.pecosedocumento;
                newFormData.tipoIdentificacion   = contratoServicioEspecial.tipideid;
                newFormData.primerNombre         = contratoServicioEspecial.pecoseprimernombre;
                newFormData.segundoNombre        = contratoServicioEspecial.pecosesegundonombre;
                newFormData.primerApellido       = contratoServicioEspecial.pecoseprimerapellido;
                newFormData.segundoApellido      = contratoServicioEspecial.pecosesegundoapellido;
                newFormData.direccion            = contratoServicioEspecial.pecosedireccion;
                newFormData.correo               = contratoServicioEspecial.pecosecorreoelectronico;
                newFormData.telefonoCelular      = contratoServicioEspecial.pecosenumerocelular;
                newFormData.tipoConvenio         = contratoServicioEspecial.ticossid;
                newFormData.fechaInicial         = contratoServicioEspecial.coseesfechaincial;
                newFormData.fechaFinal           = contratoServicioEspecial.coseesfechafinal;
                newFormData.valorContrato        = contratoServicioEspecial.coseesvalorcontrato;
                newFormData.origen               = contratoServicioEspecial.coseesorigen;
                newFormData.destino              = contratoServicioEspecial.coseesdestino;
                newFormData.tipoContrato         = contratoServicioEspecial.ticoseid;
                newFormData.descripcionRecorrido = contratoServicioEspecial.coseesdescripcionrecorrido;
                newFormData.nombreUnionTemporal  = contratoServicioEspecial.coseesnombreuniontemporal;
                newFormData.observaciones        = contratoServicioEspecial.coseesobservacion;

                let newVehiculosServicioEspecial = [];
                contratoVehiculos.forEach(function(contVehi){
                    const vehiculoEncontrado = res.vehiculos.find(vehi => vehi.vehiid === contVehi.vehiid);
                    if(vehiculoEncontrado){
                        newVehiculosServicioEspecial.push({
                            identificador:  contVehi.coseevid,
                            vehiculoId:     contVehi.vehiid,
                            nombreVehiculo: vehiculoEncontrado.nombreVehiculo,
                            estado: 'U'
                        });
                    }
                });

                let newConductoresServicioEspecial = [];
                contratoConductores.forEach(function(contCond){
                    const conductorEncontrado = res.conductores.find(cond => cond.condid === contCond.condid);
                    if(conductorEncontrado){
                        newConductoresServicioEspecial.push({
                            identificador:   contCond.coseecod,
                            conductorId:     contCond.condid,
                            nombreConductor: conductorEncontrado.nombreConductor,
                            estado: 'U'
                        });
                    }
                });

                setVehiculosServicioEspecial(newVehiculosServicioEspecial);
                setConductoresServicioEspecial(newConductoresServicioEspecial);
                setFormData(newFormData);
            }
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
                            onChange={consultarPersona} 
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
                            onBlur={consultarPersona}
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
                            label={'Teléfono celular'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 20}}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='divisionFormulario'>
                            Información del servicio
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

                    {(formData.tipoConvenio === 'UT') ?
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'nombreUnionTemporal'}
                                value={formData.nombreUnionTemporal}
                                label={'Nombre union temporal'}
                                className={'inputGeneral'}
                                variant={"standard"}
                                inputProps={{autoComplete: 'off', maxLength: 100}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>
                    : null }

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

                </Grid>

                <Grid container spacing={2}>
                    <Grid item xl={2} md={2} sm={6} xs={12}>
                        <NumberValidator fullWidth
                            id={"valorContrato"}
                            name={"valorContrato"}
                            label={"Valor contrato"}
                            value={formData.valorContrato}
                            type={'numeric'}
                            require={['required', 'maxStringLength:9']}
                            error={['Campo obligatorio','Número máximo permitido es el 999999999']}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={5} md={5} sm={6} xs={12}>
                        <TextValidator
                            name={'origen'}
                            value={formData.origen}
                            label={'Origen'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 100}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChangeUpperCase}
                        />
                    </Grid>

                    <Grid item xl={5} md={5} sm={6} xs={12}>
                        <TextValidator
                            name={'destino'}
                            value={formData.destino}
                            label={'Destino'}
                            className={'inputGeneral'}
                            variant={"standard"}
                            inputProps={{autoComplete: 'off', maxLength: 100}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChangeUpperCase}
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
                            onChange={handleChangeUpperCase}
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
                            onChange={handleChangeUpperCase}
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
                            value={vehiculos.find(v => v.vehiid === formDataAdicionarVehiculo.vehiculoId) || null}
                            filterOptions={createFilterOptions({ limit:10 })}
                            onChange={(event, newInputValue) => {
                                if(newInputValue){
                                    setFormDataAdicionarVehiculo({...formDataAdicionarVehiculo, vehiculoId: newInputValue.vehiid})
                                }
                            }}
                            renderInput={(params) =>
                                <TextValidator {...params}
                                    label="Consultar vehículo"
                                    className="inputGeneral"
                                    variant="standard"
                                    value={formDataAdicionarVehiculo.vehiculoId}
                                    placeholder="Consulte el vehículo aquí..." />}
                        />
                    </Grid>

                    <Grid item xl={2} md={2} sm={12} xs={12}>
                        <Button type={"button"} className={'modalBtn'} 
                            startIcon={<AddIcon />} onClick={() => {adicionarFilaVehiculo()}}> {"Agregar"}
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
                                                <TableCell>Vehículo</TableCell>
                                                <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                                            </TableRow>
                                        </TableHead>
                                        <TableBody>

                                        { vehiculosServicioEspecial.map((vehi, a) => {
                                            return(
                                                <TableRow key={'rowA-' +a} className={(vehi['estado'] == 'D')? 'tachado': null}>

                                                    <TableCell>
                                                        {vehi['nombreVehiculo']}
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
                            id="conductor"
                            style={{height: "26px", width: "100%"}}
                            options={conductores}
                            getOptionLabel={(option) => option.nombreConductor} 
                            value={conductores.find(v => v.condid === formDataAdicionarConductor.conductorId) || null}
                            filterOptions={createFilterOptions({ limit:10 })}
                            onChange={(event, newInputValue) => {
                                if(newInputValue){
                                    setFormDataAdicionarConductor({...formDataAdicionarConductor, conductorId: newInputValue.condid})
                                }
                            }}
                            renderInput={(params) =>
                                <TextValidator {...params}
                                    label="Consultar conductor"
                                    className="inputGeneral"
                                    variant="standard"
                                    value={formDataAdicionarConductor.conductorId}
                                    placeholder="Consulte el conductor aquí..." />}
                        />
                    </Grid>

                    <Grid item xl={2} md={2} sm={12} xs={12}>
                        <Button type={"button"} className={'modalBtn'} 
                            startIcon={<AddIcon />} onClick={() => {adicionarFilaConductor()}}> {"Agregar"}
                        </Button>
                    </Grid>

                    {(conductoresServicioEspecial.length > 0) ?
                        <Fragment>
                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='divisionFormulario'>
                                    Conductores adicionados al servicios especial
                                </Box>
                            </Grid>
                            
                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                    <Table key={'tablePersona'} className={'tableAdicional'} xl={{width: '60%', margin:'auto'}} md={{width: '70%', margin:'auto'}}  sx={{width: '80%', margin:'auto'}} sm={{maxHeight: '90%', margin:'auto'}}>
                                        <TableHead>
                                            <TableRow>
                                                <TableCell>Conductor</TableCell>
                                                <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                                            </TableRow>
                                        </TableHead>
                                        <TableBody>

                                        { conductoresServicioEspecial.map((cond, a) => {
                                            return(
                                                <TableRow key={'rowA-' +a} className={(cond['estado'] == 'D')? 'tachado': null}>

                                                    <TableCell>
                                                        <p> {cond['nombreConductor']}</p>
                                                    </TableCell>
                                                    
                                                    <TableCell className='cellCenter'>
                                                        <Icon key={'iconDelete'+a} className={'icon top red'}
                                                                onClick={() => {eliminarFilaConductor(a);}}
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
                tam     = 'mediumFlot' 
                abrir   = {abrirModal}
            />
        </Box>
    )
}
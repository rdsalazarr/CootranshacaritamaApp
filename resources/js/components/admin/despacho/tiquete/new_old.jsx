import React, {useState, useEffect, Fragment} from 'react';
import {TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, MenuItem, Stack, Box, FormControlLabel, Switch, FormGroup, Checkbox} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';


import puestoVehiculoVendido from "../../../../../images/puestoVehiculoVendido.png";
import puestoVehiculo from "../../../../../images/puestoVehiculo.png";



import FavoriteBorder from '@mui/icons-material/FavoriteBorder';
import Favorite from '@mui/icons-material/Favorite';
import BookmarkBorderIcon from '@mui/icons-material/BookmarkBorder';
import BookmarkIcon from '@mui/icons-material/Bookmark';




export default function New({data, tipo}){
    let tiquid        = (tipo === 'U') ? data.tiquid : '000';
    const [formData, setFormData] = useState({codigo:tiquid,           tipoIdentificacion:'',          documento:'',          primerNombre:'',
                                              segundoNombre:'',        primerApellido:'',              segundoApellido:'',    direccion:'',
                                              correo:'',               telefonoCelular:'',             departamentoOrigen:'', municipioOrigen:'',
                                              departamentoDestino:'',  municipioDestino:'',            valorTiquete :'',      planilla:'',
                                              valorDescuento:'',       valorFondoReposicion:'',        valorTotal:'',         personaId:'000',
                                              valorTiqueteMostrar :'', valorFondoReposicionMostrar:'', valorTotalTiquete:'',  cantidadPuesto: '', tipo:tipo});
    
    const [distribucionVehiculos, setDistribucionVehiculos] = useState([]);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [municipiosDestino, setMunicipiosDestino] = useState([]);
    const [enviarTiquete, setEnviarTiquete] = useState(false);
    const [tarifaTiquetes, setTarifaTiquetes] = useState([]);
    const [planillaRutas, setPlanillaRutas] = useState([]);  
    const [abrirModal, setAbrirModal] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [dataPuestos, setDataPuestos] = useState([]);
    const [esEmpresa, setEsEmpresa] = useState(false);
    const [municipios, setMunicipios] = useState([]);    
    const [idTiquete , setIdTiquete] = useState(0);
    const [loader, setLoader] = useState(false); 
 
    const [selectedPuestos, setSelectedPuestos] = useState([]);

    const label = { inputProps: { 'aria-label': 'Checkbox demo' } };


    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    }

    const handleChangeEnviarTiquete = (e) => {
        setEnviarTiquete(e.target.checked);
    }

    const handleSubmit = () =>{
        let newFormData             = {...formData}
        newFormData.enviarTiquete   = enviarTiquete;
        newFormData.puestosVendidos = formDataPuesto; 
        setLoader(true);
        instance.post('/admin/despacho/tiquete/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            if(formData.tipo === 'I' && res.success){
                setFormData({codigo:tiquid,           tipoIdentificacion:'',          documento:'',          primerNombre:'',
                            segundoNombre:'',        primerApellido:'',              segundoApellido:'',    direccion:'',
                            correo:'',               telefonoCelular:'',             departamentoOrigen:'', municipioOrigen:'',
                            departamentoDestino:'',  municipioDestino:'',            valorTiquete :'',      planilla:'',
                            valorDescuento:'',       valorFondoReposicion:'',        valorTotal:'',         personaId:'000',
                            valorTiqueteMostrar :'', valorFondoReposicionMostrar:'', valorTotalTiquete:'',  tipo:tipo});
                setIdTiquete(res.tiqueteId);
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
       if (tpIdentificacion !=='' && documento !== ''){
            setLoader(true);
            instance.post('/admin/despacho/tiquete/consultar/datos/persona', {tipoIdentificacion:tpIdentificacion, documento: documento}).then(res=>{
                if(res.success){
                    let personaservicio         = res.data;
                    newFormData.personaId       = personaservicio.perserid;
                    newFormData.primerNombre    = personaservicio.perserprimernombre;
                    newFormData.segundoNombre   = (personaservicio.persersegundonombre !== undefined) ? personaservicio.persersegundonombre : '';
                    newFormData.primerApellido  = (personaservicio.perserprimerapellido !== undefined) ? personaservicio.perserprimerapellido : '';
                    newFormData.segundoApellido = (personaservicio.persersegundoapellido !== undefined) ? personaservicio.persersegundoapellido : '';
                    newFormData.direccion       = (personaservicio.perserdireccion !== undefined) ? personaservicio.perserdireccion : '';
                    newFormData.correo          = (personaservicio.persercorreoelectronico !== undefined) ? personaservicio.persercorreoelectronico : '';
                    newFormData.telefonoCelular = (personaservicio.persernumerocelular !== undefined) ? personaservicio.persernumerocelular : '';
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
        setEsEmpresa((tpIdentificacion === 5) ? true : false);
        setFormData(newFormData);
    }

    const consultarMunicipioOrigen = (e) =>{
        let newFormData               = {...formData}
        let valorTiquete              = 0;
        let fondoTeposicion           = 0;
        const planillaRutasFiltradas  = planillaRutas.filter(planilla => planilla.plarutid === e.target.value);
        let rutaId                    = planillaRutasFiltradas[0].rutaid;
        let vehiculoId                = planillaRutasFiltradas[0].vehiid;
        let depaIdOrigen              = planillaRutasFiltradas[0].depaidorigen;
        let muniIdOrigen              = planillaRutasFiltradas[0].muniidorigen;
        let municipioOrigen           = planillaRutasFiltradas[0].municipioOrigen;
        let depaIdDestino             = planillaRutasFiltradas[0].depaiddestino;
        let muniIdDestino             = planillaRutasFiltradas[0].muniiddestino;
        let municipioDestino          = planillaRutasFiltradas[0].municipioDestino;
        const tarifaTiquetesFiltradas = tarifaTiquetes.filter(tt => tt.rutaid === rutaId && tt.depaiddestino === depaIdDestino && tt.muniiddestino === muniIdDestino);

        if(tarifaTiquetesFiltradas.length === 0){
            showSimpleSnackbar("No existe valor del tiquete gestionado para la ruta "+municipioOrigen+' - '+municipioDestino, 'error');
            newFormData.valorTiquete         = 0;
            newFormData.valorFondoReposicion = 0;
            setFormData(newFormData);
            return;
        }

        const distribucionVehiculosFiltrados  = distribucionVehiculos.filter(vehiculo => vehiculo.vehiid === vehiculoId);
        distribucionVehiculo(distribucionVehiculosFiltrados);

        newFormData.planilla                    = e.target.value;
        newFormData.municipioOrigen             = muniIdOrigen;
        newFormData.departamentoOrigen          = depaIdOrigen; 
        newFormData.departamentoDestino         = depaIdDestino;
        newFormData.municipioDestino            = muniIdDestino; 
        valorTiquete                            = tarifaTiquetesFiltradas[0].tartiqvalor;
        fondoTeposicion                         = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
        let valorFondoReposicion                = (valorTiquete * fondoTeposicion) / 100;
        newFormData.valorTiquete                = valorTiquete;
        newFormData.valorFondoReposicion        = valorFondoReposicion;
        newFormData.valorTotal                  = valorTiquete;
        newFormData.valorTiqueteMostrar         = formatearNumero(valorTiquete);
        newFormData.valorFondoReposicionMostrar = formatearNumero(valorFondoReposicion);
        newFormData.valorTotalTiquete           = formatearNumero(valorTiquete);

        let municipiosDestino = [];
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === depaIdDestino){
                municipiosDestino.push({
                    muniid:     muni.muniid,
                    munidepaid: muni.munidepaid,
                    muninombre: muni.muninombre
                });
            }
        });

        municipiosDestino.push({
            muniid:     muniIdDestino,
            munidepaid: depaIdDestino,
            muninombre: municipioDestino
        }); 

        setFormData(newFormData);
        setMunicipiosDestino(municipiosDestino);
    }

    const distribucionVehiculo= (distribucionVehiculo) => {
        let totalFilas = distribucionVehiculo[0].totalFilas;
        let dataFilas  = [];
        let idColumna  = 0;
        for (let i = 0; i < totalFilas; i++) {
            let dataColumnas = [];
            distribucionVehiculo.map((res, j)=>{
                if(parseInt(res.tivedifila) === i){
                    let contenido    = res.tivedipuesto;
                    let clase        = (contenido === 'C') ? 'conductor' : ((contenido === 'P') ? 'pasillo' : 'asiento');
                    const esCondutor = clase === 'conductor';
                    dataColumnas.push({idDistribucion:res.tivedipuesto,  puestoColumna: idColumna.toString(), contenido, clase, esCondutor });
                    idColumna ++;
                }
            });
            dataFilas.push(dataColumnas);
        }
       setDataPuestos(dataFilas);
    }

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }
 
    const calcularValorTotal = (e) =>{
        let newFormData               = {...formData}
        let valorDescuento            = (e.target.name === 'valorDescuento' ) ? e.target.value : formData.valorDescuento;
        let valorTiquete              = newFormData.valorTiquete
        newFormData.valorDescuento    = valorDescuento; 
        newFormData.valorTotalTiquete = formatearNumero(Number(valorTiquete) - Number(valorDescuento));
        setFormData(newFormData);
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/despacho/tiquete/listar/datos', {tipo:tipo, codigo:formData.codigo}).then(res=>{
            setDistribucionVehiculos(res.distribucionVehiculos);
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setTarifaTiquetes(res.tarifaTiquetes);
            setPlanillaRutas(res.planillaRutas);
            setMunicipios(res.municipios);

            if(tipo === 'U'){
                let tiquete                             = res.tiquete;
                newFormData.personaId                   = tiquete.perserid;
                newFormData.tipoIdentificacion          = tiquete.tipideid;
                newFormData.documento                   = tiquete.perserdocumento;
                newFormData.primerNombre                = tiquete.perserprimernombre;
                newFormData.segundoNombre               = (tiquete.persersegundonombre !== null) ? tiquete.persersegundonombre : '';
                newFormData.primerApellido              = (tiquete.perserprimerapellido !== null) ? tiquete.perserprimerapellido : '';
                newFormData.segundoApellido             = (tiquete.persersegundoapellido !== null) ? tiquete.persersegundoapellido : '';
                newFormData.direccion                   = tiquete.perserdireccion;
                newFormData.correo                      = (tiquete.persercorreoelectronico !== null) ? tiquete.persercorreoelectronico : '';
                newFormData.telefonoCelular             = tiquete.persernumerocelular;
                newFormData.departamentoOrigen          = tiquete.depaidorigen;
                newFormData.municipioOrigen             = tiquete.muniidorigen;
                newFormData.departamentoDestino         = tiquete.depaiddestino;
                newFormData.municipioDestino            = tiquete.muniiddestino;
                newFormData.planilla                    = tiquete.plarutid;
                newFormData.cantidadPuesto              = tiquete.tiqucantidad;///
                newFormData.valorTiquete                = tiquete.tiquvalortiquete;
                newFormData.valorDescuento              = tiquete.tiquvalordescuento;
                newFormData.valorFondoReposicion        = tiquete.tiquvalorfondoreposicion;
                newFormData.valorTotal                  = tiquete.tiquvalortotal;
                newFormData.valorTiqueteMostrar         = formatearNumero(tiquete.tiquvalortiquete);
                newFormData.valorFondoReposicionMostrar = formatearNumero(tiquete.tiquvalorfondoreposicion);
                newFormData.valorTotalTiquete           = formatearNumero(tiquete.tiquvalortotal);

                let municipiosDestino = [];
                let deptoDestino      = tiquete.depaiddestino;
                res.municipios.forEach(function(muni){ 
                    if(muni.munidepaid === deptoDestino){
                        municipiosDestino.push({
                            muniid:     muni.muniid,
                            munidepaid: muni.munidepaid,
                            muninombre: muni.muninombre
                        });
                    }
                });

                const planillaRutasFiltradas  = res.planillaRutas.filter(planilla => planilla.plarutid === tiquete.plarutid);
                let depaIdDestino             = planillaRutasFiltradas[0].depaiddestino;
                let muniIdDestino             = planillaRutasFiltradas[0].muniiddestino;
                let municipioDestino          = planillaRutasFiltradas[0].municipioDestino;
        
                municipiosDestino.push({
                    muniid:     muniIdDestino,
                    munidepaid: depaIdDestino,
                    muninombre: municipioDestino
                }); 

                setMunicipiosDestino(municipiosDestino);
                setEsEmpresa((tiquete.tipideid === 5) ? true : false);
            }

            setFormData(newFormData);
            setLoader(false);
        })
    }, []);


    const handleDragEnd = (result) => {
        // ... (tu lógica existente)
        
        // Obtén el id del puesto seleccionado
        const selectedPuestoId = result.draggableId;
        
        // Verifica si ya está seleccionado y agrega o quita del estado
        setSelectedPuestos((prevSelected) => {
            if (prevSelected.includes(selectedPuestoId)) {
                // Quitar del estado si ya está seleccionado
                return prevSelected.filter((id) => id !== selectedPuestoId);
            } else {
                // Agregar al estado si no está seleccionado
                return [...prevSelected, selectedPuestoId];
            }
        });
    };


    /*const getPuestoClass = (isSelected) => {
        return isSelected ? 'selectedPuesto' : 'regularPuesto';
    };*/


    const claseDistribucionPuesto = 'distribucionPuestoTaxi'; // Reemplaza con tu clase real

    const handleCheckboxChange = (puestoId) => {
      setSelectedPuestos((prevSelected) =>
        prevSelected.includes(puestoId)
          ? prevSelected.filter((id) => id !== puestoId)
          : [...prevSelected, puestoId]
      );

      console.log(selectedPuestos);
    };
  
    const getPuestoClass = (isSelected) => (isSelected ? 'selectedPuesto' : 'regularPuesto');


    const [formDataPuesto, setFormDataPuesto] = useState([]); 

    const handleChangePuesto = (e) =>{
        let newFormDataPuesto = [...formDataPuesto];
        if(e.target.checked){
            newFormDataPuesto.push({tivedipuesto: parseInt(e.target.value)});
        }else{
            //Elimino la posicion
            newFormDataPuesto = formDataPuesto.filter((item) => item.tivedipuesto !== parseInt(e.target.value));
        }
        setFormDataPuesto(newFormDataPuesto);
        calcularValorTiquete(newFormDataPuesto.length);
    }


    const calcularValorTiquete = (cantidadPuesto) =>{
        let newFormData                         = {...formData}
        const planillaRutasFiltradas            = planillaRutas.filter(planilla => planilla.plarutid === newFormData.planilla);
        let rutaId                              = planillaRutasFiltradas[0].rutaid;
        let depaIdDestino                       = planillaRutasFiltradas[0].depaiddestino;
        let muniIdDestino                       = planillaRutasFiltradas[0].muniiddestino;
        const tarifaTiquetesFiltradas           = tarifaTiquetes.filter(tt => tt.rutaid === rutaId && tt.depaiddestino === depaIdDestino && tt.muniiddestino === muniIdDestino);
        let  valorTiquete                       = tarifaTiquetesFiltradas[0].tartiqvalor;
        valorTiquete                            = valorTiquete * cantidadPuesto;
        let fondoReposicion                     = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
        let valorFondoReposicion                = (valorTiquete * fondoReposicion) / 100;
        newFormData.valorTiquete                = valorTiquete;
        newFormData.valorFondoReposicion        = valorFondoReposicion;
        newFormData.valorTotal                  = valorTiquete;
        newFormData.valorTiqueteMostrar         = formatearNumero(valorTiquete);
        newFormData.valorFondoReposicionMostrar = formatearNumero(valorFondoReposicion);
        newFormData.valorTotalTiquete           = formatearNumero(valorTiquete);
        newFormData.cantidadPuesto              = cantidadPuesto;
        setFormData(newFormData);
    }


    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <ValidatorForm onSubmit={handleSubmit}>
                <Grid container spacing={2}>

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='frmDivision'>
                            Información del tiquete
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'planilla'}
                            value={formData.planilla}
                            label={'Planilla'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={consultarMunicipioOrigen}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {planillaRutas.map(res=>{
                                return <MenuItem value={res.plarutid} key={res.plarutid}> {res.plarutid} {res.nombreRuta}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'municipioDestino'}
                            value={formData.municipioDestino}
                            label={'Municipio nodo destino'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipiosDestino.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muniid} {res.muninombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>

                    </Grid>

                </Grid>

                <Grid container spacing={2}>
                    <Grid item xl={9} md={9} sm={12} xs={12}>
                        <Grid container spacing={2}>
                            <Grid item xl={12} md={12} sm={12} xs={12} style={{marginTop:'1em'}}>
                                {(dataPuestos.length > 0)?
                                    <Box className={claseDistribucionPuesto}>
                                        <Box style={{ display: 'flex', justifyContent: 'space-between' }}>
                                            {Object.keys(dataPuestos).map((listId) => (
                                                <Box key={listId} >
                                                    {dataPuestos[listId].map((item, index) => (
                                                        <Box key={item.puestoColumna} >
                                                            {(item.clase === 'asiento') ?
                                                                <Box >

                                                                    {/*<FormGroup row name={"menus"} 
                                                                        value={item.puestoColumna}
                                                                        checked={selectedPuestos.includes(item.puestoColumna)}
                                                                        onChange={() => handleCheckboxChange(item.puestoColumna)}                                                                       
                                                                    >
                                                                         <FormControlLabel value={item.puestoColumna} 
                                                                         label={<p>{item.contenido}</p>}
                                                                         control={<Checkbox  className="checkbox-asiento"   />} />

                                                    </FormGroup>*/}


                                                                <FormGroup row name={"menus"} 
                                                                    value={formDataPuesto.tivedipuesto}
                                                                    onChange={handleChangePuesto}>
                                                                    <FormControlLabel value={item.idDistribucion} label={item.contenido} 
                                                                        control={ <Checkbox
                                                                                    icon={<img src={puestoVehiculo} width={'100%'} />}
                                                                                    checkedIcon={<img src={puestoVehiculoVendido }  width={'100%'}/>}
                                                                                />
                                                                    } />
                                                                </FormGroup>
                                                                </Box>
                                                            :  
                                                                <Box
                                                                className={item.clase}
                                                                >
                                                                <p>{item.contenido}</p>
                                                                </Box>
                                                            }
                                                        </Box>
                                                    ))}
                                                </Box>
                                            ))}
                                        </Box>

                                    </Box>
                                : null }
                            </Grid>
                        </Grid>
                    </Grid>

                    <Grid item xl={3} md={3} sm={12} xs={12} style={{marginTop:'1em'}}>
                        <Grid container spacing={2}>

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='frmTextoColor'>
                                    <label>Cantidad de puestos: </label>
                                    <span className='textoRojo'>{'\u00A0'+ formData.cantidadPuesto}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='frmTextoColor'>
                                    <label>Valor tiquete:  $ </label>
                                    <span className='textoRojo'>{'\u00A0'+ formData.valorTiqueteMostrar}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <NumberValidator fullWidth
                                    id={"valorDescuento"}
                                    name={"valorDescuento"}
                                    label={"Valor descuento"}
                                    value={formData.valorDescuento}
                                    type={'numeric'}
                                    require={['maxStringLength:8']}
                                    error={['Número máximo permitido es el 99999999']}
                                    onChange={calcularValorTotal}
                                />
                            </Grid> 

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='frmTextoColor'>
                                    <label>Fondo de reposición: $ </label>
                                    <span className='textoRojo'>{'\u00A0'+ formData.valorFondoReposicionMostrar}</span>
                                </Box>
                            </Grid>
                            
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='frmTextoColor'>
                                    <label>Total: $ </label>
                                    <span className='textoRojo'> {'\u00A0'+ formData.valorTotalTiquete}</span>
                                </Box>
                            </Grid> 

                        </Grid>
                    </Grid>

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='frmDivision'>
                            Información de la persona
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'tipoIdentificacion'}
                            value={formData.tipoIdentificacion}
                            label={'Tipo de identificación'} 
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={consultarPersona} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoIdentificaciones.map(res=>{
                                return <MenuItem value={res.tipideid} key={res.tipideid}>{res.tipidenombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'documento'}
                            value={formData.documento}
                            label={(esEmpresa)? 'NIT' : 'Número de identificación'}
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
                            inputProps={{autoComplete: 'off', maxLength: 120}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChangeUpperCase}
                            tabIndex="3"
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
                                    inputProps={{ maxLength: 40}}
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
                                    inputProps={{ maxLength: 40}}
                                    onChange={handleChangeUpperCase}
                                />
                            </Grid>
                        </Fragment>
                    : null}

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
                            label={'Correo electrónico'}
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
                            label={'Teléfono'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{ maxLength: 20}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item md={3} xl={3} sm={6} xs={12}>
                        <FormControlLabel
                            control={<Switch name={'notificar'} 
                            value={enviarTiquete} onChange={handleChangeEnviarTiquete} 
                            color="secondary"/>} 
                            label="Enviar copia del tiquete al correo"
                        />
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
                title   = {'Visualizar factura en PDF del tiquete'} 
                content = {<VisualizarPdf id={idTiquete} />} 
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'smallFlot'
                abrir   = {abrirModal}
            />
        </Box>
    )
}
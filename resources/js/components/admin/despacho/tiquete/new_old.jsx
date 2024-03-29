import React, {useState, useEffect, Fragment} from 'react';
import {TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, MenuItem, Stack, Box, FormControlLabel, Switch, FormGroup, Checkbox} from '@mui/material';
import puestoVehiculoSeleccionado from "../../../../../images/iconoPuestoVehiculoSeleccionado.png";
import puestoVehiculo from "../../../../../images/iconoPuestoVehiculo.png";
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import ErrorIcon from '@mui/icons-material/Error';
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';

export default function New({data, tipo}){
    let tiquid        = (tipo === 'U') ? data.tiquid : '000';
    let plarutid      = (tipo === 'U') ? data.plarutid : '000';
    const [formData, setFormData] = useState({codigo:tiquid,           tipoIdentificacion:'',          documento:'',          primerNombre:'',
                                              segundoNombre:'',        primerApellido:'',              segundoApellido:'',    direccion:'',
                                              correo:'',               telefonoCelular:'',             departamentoOrigen:'', municipioOrigen:'',
                                              departamentoDestino:'',  municipioDestino:'',            valorTiquete :'',      planillaId:'',
                                              valorDescuento:'',       valorFondoReposicion:'',        valorTotal:'',         personaId:'000',
                                              valorTiqueteMostrar :'', valorFondoReposicionMostrar:'', valorTotalTiquete:'',  cantidadPuesto: '',
                                              valorSeguro:'',          valorSeguroMostrar:'',          rutaId: '',            valorEstampilla:'',   tipo:tipo});
    
    const [claseDistribucionPuesto, setClaseDistribucionPuesto] = useState('distribucionPuestoGeneral' + 'Venta');
    const [tiqueteContabilizado, setTiqueteContabilizado] = useState(false);
    const [distribucionVehiculos, setDistribucionVehiculos] = useState([]);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [municipiosDestino, setMunicipiosDestino] = useState([]);
    const [enviarTiquete, setEnviarTiquete] = useState(false);
    const [tarifaTiquetes, setTarifaTiquetes] = useState([]);
    const [formDataPuesto, setFormDataPuesto] = useState([]);
    const [planillaRutas, setPlanillaRutas] = useState([]);
    const [puestoMarcado, setPuestoMarcado] = useState([]);
    const [tomarSeguro, setTomarSeguro] = useState(false);
    const [cajaAbierta, setCajaAbierta] = useState(false);
    const [abrirModal, setAbrirModal] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [dataPuestos, setDataPuestos] = useState([]);
    const [esEmpresa, setEsEmpresa] = useState(false);
    const [municipios, setMunicipios] = useState([]);
    const [idTiquete , setIdTiquete] = useState(0);
    const [loader, setLoader] = useState(false);  
    
    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    }

    const handleChangeEnviarTiquete = (e) => {
        setEnviarTiquete(e.target.checked);
    }

    const handleChangeTomarSeguro= (e) => {
        setTomarSeguro(e.target.checked);
        let valorTiquete              = Number(formData.valorTiquete);
        let valorSeguro               = Number(formData.valorSeguro);
        let valorDescuento            = Number(formData.valorDescuento)
        let cantidadPuesto            = formData.cantidadPuesto;
        let valorSeguroPuesto         = valorSeguro * cantidadPuesto;
        let valorTiquetePuesto        = (valorTiquete * cantidadPuesto) - valorDescuento;
        let valorTotalTiquete         = (e.target.checked) ? valorTiquetePuesto + valorSeguroPuesto : valorTiquetePuesto;
        let newFormData               = {...formData}
        newFormData.valorTotal        = valorTotalTiquete;
        newFormData.valorTotalTiquete = formatearNumero(valorTotalTiquete);
        setFormData(newFormData);
    }

    const handleChangePuesto = (e) =>{
        let newFormDataPuesto = [...formDataPuesto];
        (e.target.checked) ? newFormDataPuesto.push({tivedipuesto: parseInt(e.target.value)}) :
                            newFormDataPuesto = formDataPuesto.filter((item) => item.tivedipuesto !== parseInt(e.target.value));
        setFormDataPuesto(newFormDataPuesto);
        calcularValorTiquete(newFormDataPuesto.length);
    }

    const calcularValorTiquete = (cantidadPuesto) =>{
        let newFormData                         = {...formData}
        let rutaId                              = formData.rutaId;
        let depaIdDestino                       = formData.departamentoDestino;
        let muniIdDestino                       = formData.municipioDestino;

        const tarifaTiquetesFiltradas           = tarifaTiquetes.filter(tt => tt.rutaid === rutaId && tt.depaiddestino === depaIdDestino && tt.muniiddestino === muniIdDestino);
        if(tarifaTiquetesFiltradas.length === 0){
            showSimpleSnackbar('Se produjo un error interno al procesar el valor del tiquete, por favor contacte el administrador del sistema', 'error');
            return;
        }

        let valorTiquete                        = tarifaTiquetesFiltradas[0].tartiqvalor;
        let fondoReposicion                     = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
        let valorTiquetePuesto                  = valorTiquete * cantidadPuesto;
        let valorFondoReposicion                = (valorTiquetePuesto * fondoReposicion) / 100;
        let valorSeguroPuesto                   = (tomarSeguro) ? Number(formData.valorSeguro * cantidadPuesto) : 0;
        let valorTotalTiquete                   = valorTiquetePuesto + valorSeguroPuesto - Number(formData.valorDescuento);
        newFormData.valorFondoReposicion        = valorFondoReposicion;
        newFormData.valorTiquete                = valorTiquete;
        newFormData.valorTotal                  = valorTotalTiquete;
        newFormData.valorTiqueteMostrar         = formatearNumero(valorTiquete);
        newFormData.valorFondoReposicionMostrar = formatearNumero(valorFondoReposicion);
        newFormData.valorTotalTiquete           = formatearNumero(valorTotalTiquete);
        newFormData.cantidadPuesto              = cantidadPuesto;
        setFormData(newFormData);
    }

    const handleSubmit = () =>{
        let newFormData             = {...formData}
        newFormData.enviarTiquete   = (enviarTiquete) ? 'SI' : 'NO'; ;
        newFormData.puestosVendidos = formDataPuesto;

        if(formDataPuesto.length === 0){
            showSimpleSnackbar('Por favor, seleccione al menos un puesto del vehículo', 'error');
            return;
        }

        if(enviarTiquete && formData.correo === ''){
            showSimpleSnackbar("El campo correo electrónico es obligatorio cuando el campo enviar copia del tiquete al correo es sí", 'error');
            return;
        }

        (!cajaAbierta) ? showSimpleSnackbar('Advertencia: No se ha encontrado ninguna caja abierta para el día de hoy. Sin embargo, aún es posible vender tiquetes', 'warning') : null;

        setLoader(true);
        instance.post('/admin/despacho/tiquete/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            if(formData.tipo === 'I' && res.success){
                setFormData({codigo:tiquid,          tipoIdentificacion:'',          documento:'',          primerNombre:'',
                            segundoNombre:'',        primerApellido:'',              segundoApellido:'',    direccion:'',
                            correo:'',               telefonoCelular:'',             departamentoOrigen:'', municipioOrigen:'',
                            departamentoDestino:'',  municipioDestino:'',            valorTiquete :'',      planillaId:'',
                            valorDescuento:'',       valorFondoReposicion:'',        valorTotal:'',         personaId:'000',
                            valorTiqueteMostrar :'', valorFondoReposicionMostrar:'', valorTotalTiquete:'',  cantidadPuesto: 0,
                            valorSeguro:'',          valorSeguroMostrar:'',          rutaId: '',            valorEstampilla:'',  tipo:tipo });                
                setEnviarTiquete(false);
                setTomarSeguro(false);
                setDataPuestos([]);
            }

            (res.success) ? setIdTiquete(res.tiqueteId) : null;
            (res.success) ? setAbrirModal(true) : null;
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
                    setEnviarTiquete((personaservicio.perserpermitenotificacion) ? true : false);
                }else{
                    newFormData.personaId       = '000';
                    newFormData.primerNombre    = '';
                    newFormData.segundoNombre   = '';
                    newFormData.primerApellido  = '';
                    newFormData.segundoApellido = '';
                    newFormData.direccion       = '';
                    newFormData.correo          = '';
                    newFormData.telefonoCelular = '';
                    setEnviarTiquete(false);
                }
                setLoader(false); 
            })
        }
        setEsEmpresa((tpIdentificacion === 5) ? true : false);
        setFormData(newFormData);
    }

    const consultarNodoDestino = (e) =>{
        let newFormData               = {...formData}
        let valorTiquete              = 0;
        let fondoTeposicion           = 0;
        let valorSeguro               = 0;
        const planillaRutasFiltradas  = planillaRutas.filter(planilla => planilla.plarutid === e.target.value);
        let rutaId                    = planillaRutasFiltradas[0].rutaid;
        let vehiculoId                = planillaRutasFiltradas[0].vehiid;
        let depaIdOrigen              = planillaRutasFiltradas[0].depaidorigen;
        let muniIdOrigen              = planillaRutasFiltradas[0].muniidorigen;
        let municipioOrigen           = planillaRutasFiltradas[0].municipioOrigen;
        let depaIdDestino             = planillaRutasFiltradas[0].depaiddestino;
        let muniIdDestino             = planillaRutasFiltradas[0].muniiddestino;
        let municipioDestino          = planillaRutasFiltradas[0].municipioDestino;
        let valorEstampilla           = planillaRutasFiltradas[0].rutavalorestampilla;
        const tarifaTiquetesFiltradas = tarifaTiquetes.filter(tt => tt.rutaid === rutaId && tt.depaiddestino === depaIdDestino && tt.muniiddestino === muniIdDestino);

        if(tarifaTiquetesFiltradas.length === 0){
            showSimpleSnackbar("No existe valor del tiquete gestionado para la ruta "+municipioOrigen+' - '+municipioDestino, 'error');
            newFormData.valorTiquete         = 0;
            newFormData.valorFondoReposicion = 0;
            setFormData(newFormData);
            return;
        }

        setLoader(true);
        instance.post('/admin/despacho/tiquete/consultar/ventas/realizadas', {palnillaId: e.target.value}).then(res=>{
            const distribucionVehiculosFiltrados  = distribucionVehiculos.filter(vehiculo => vehiculo.vehiid === vehiculoId);
            distribucionVehiculo(distribucionVehiculosFiltrados, res.data);

            newFormData.planillaId                  = e.target.value;
            newFormData.municipioOrigen             = muniIdOrigen;
            newFormData.departamentoOrigen          = depaIdOrigen;
            newFormData.departamentoDestino         = depaIdDestino;
            newFormData.municipioDestino            = muniIdDestino;
            newFormData.rutaId                      = rutaId;
            valorTiquete                            = tarifaTiquetesFiltradas[0].tartiqvalor;
            valorSeguro                             = tarifaTiquetesFiltradas[0].tartiqvalorseguro;
            fondoTeposicion                         = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
            let valorFondoReposicion                = (valorTiquete * fondoTeposicion) / 100;
            newFormData.valorSeguro                 = valorSeguro;
            newFormData.valorTiquete                = valorTiquete;
            newFormData.valorEstampilla             = valorEstampilla;
            newFormData.valorSeguroMostrar          = formatearNumero(valorSeguro);
            newFormData.valorTiqueteMostrar         = formatearNumero(valorTiquete);
            newFormData.valorFondoReposicionMostrar = formatearNumero(valorFondoReposicion);

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
            setLoader(false);
        })
    }

    const consultarValorTiqueteDestino = (e) =>{
        let newFormData     = {...formData}
        let valorTiquete    = 0;
        let fondoTeposicion = 0;
        let valorSeguro     = 0;
        let rutaId          = formData.rutaId
        let depaIdDestino   = formData.departamentoDestino
        let cantidadPuesto  = formData.cantidadPuesto;

        const tarifaTiquetesFiltradas = tarifaTiquetes.filter(tt => tt.rutaid === rutaId && tt.depaiddestino === depaIdDestino && tt.muniiddestino === e.target.value);
        if(tarifaTiquetesFiltradas.length > 0){
            valorTiquete                        = tarifaTiquetesFiltradas[0].tartiqvalor;
            valorSeguro                         = tarifaTiquetesFiltradas[0].tartiqvalorseguro;
            fondoTeposicion                     = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
        }else{
            showSimpleSnackbar("No se han gestionado valores para el nodo de la ruta seleccionada", 'error');
        }

        let valorTiquetePuesto                  = (cantidadPuesto > 0) ? valorTiquete * cantidadPuesto : valorTiquete
        let valorFondoReposicion                = (valorTiquetePuesto * fondoTeposicion) / 100;
        newFormData.valorSeguro                 = valorSeguro;
        newFormData.valorTiquete                = valorTiquete;
        newFormData.valorFondoReposicion        = valorFondoReposicion; 
        newFormData.valorSeguroMostrar          = formatearNumero(valorSeguro);
        newFormData.valorTiqueteMostrar         = formatearNumero(valorTiquete);
        newFormData.valorFondoReposicionMostrar = formatearNumero(valorFondoReposicion);
        let valorSeguroPuesto                   = (tomarSeguro) ? Number(valorSeguro) * cantidadPuesto : 0
        let valorTotalTiquete                   = valorTiquetePuesto + valorSeguroPuesto - Number(formData.valorDescuento);
        newFormData.valorTotalTiquete           = (valorTotalTiquete > 0) ? formatearNumero(valorTotalTiquete) : 0;
        newFormData.valorTotal                  = (valorTotalTiquete > 0) ? valorTotalTiquete: 0;
        newFormData.municipioDestino            = e.target.value;
        setFormData(newFormData);
    }

    const distribucionVehiculo = (distribucionVehiculo, puestosVendidos, puestosVendidosGeneral = []) => {
        let totalFilas = distribucionVehiculo[0].totalFilas;
        let dataFilas  = [];
        let idColumna  = 0;
        for (let i = 0; i < totalFilas; i++) {
            let dataColumnas = [];
            distribucionVehiculo.map((res, j)=>{
                if(parseInt(res.tivedifila) === i){
                    const contenido        = res.tivedipuesto;
                    const puestoGenerales  = puestosVendidosGeneral.some(puesto => puesto.tiqpuenumeropuesto === contenido);
                    const puestoDespachado = puestosVendidos.some(puesto => puesto.tiqpuenumeropuesto === contenido);
                    const puestoVendido    = (puestoDespachado && tipo === 'I') ? true : false;
                    const clase            = (contenido === 'C') ? 'conductor' : ((contenido === 'P') ? 'pasillo' : ((puestoVendido || puestoGenerales) ? 'asientoVendido' : 'asiento'));
                    const esCondutor       = clase === 'conductor';
                    dataColumnas.push({puestoVendido:puestoVendido,  puestoColumna: idColumna.toString(), contenido, clase, esCondutor });
                    idColumna ++;
                }
            });
            dataFilas.push(dataColumnas);
        }
       setDataPuestos(dataFilas);
       setClaseDistribucionPuesto(distribucionVehiculo[0].tipvehclasecss + 'Venta');
    }

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }
 
    const calcularValorTotalDescuento = (e) =>{
        let newFormData               = {...formData}
        let valorDescuento            = (e.target.name === 'valorDescuento' ) ? e.target.value : formData.valorDescuento;
        let valorTiquete              = Number(formData.valorTiquete);
        let cantidadPuesto            = formData.cantidadPuesto;
        let valorSeguro               = (tomarSeguro) ? Number(formData.valorSeguro) * cantidadPuesto : 0;
        let valorTiquetePuesto        = valorTiquete * cantidadPuesto;
        let valorTotalTiquete         = valorTiquetePuesto + valorSeguro - Number(valorDescuento);
        newFormData.valorDescuento    = valorDescuento;
        newFormData.valorTotal        = valorTotalTiquete;
        newFormData.valorTotalTiquete = formatearNumero(valorTotalTiquete);
        setFormData(newFormData);
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData       = {...formData}
        let newFormDataPuesto = [...formDataPuesto];
        instance.post('/admin/despacho/tiquete/listar/datos', {tipo:tipo, codigo:formData.codigo, planillaId: plarutid}).then(res=>{
            setDistribucionVehiculos(res.distribucionVehiculos);
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setTarifaTiquetes(res.tarifaTiquetes);
            setPuestoMarcado(res.tiquetePuestos);
            setPlanillaRutas(res.planillaRutas);
            setCajaAbierta(res.cajaAbierta);
            setMunicipios(res.municipios);            
           
            if(!res.cajaAbierta){             
                showSimpleSnackbar('Advertencia: No se ha encontrado ninguna caja abierta para el día de hoy. Sin embargo, aún es posible vender tiquetes', 'warning');
            }      

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
                newFormData.rutaId                      = tiquete.rutaid;
                newFormData.planillaId                  = tiquete.plarutid;
                newFormData.cantidadPuesto              = tiquete.tiqucantidad;
                newFormData.valorTiquete                = tiquete.tiquvalortiquete;
                newFormData.valorDescuento              = tiquete.tiquvalordescuento;
                newFormData.valorFondoReposicion        = tiquete.tiquvalorfondoreposicion;
                newFormData.valorTotal                  = tiquete.tiquvalortotal;
                newFormData.valorEstampilla             = tiquete.tiquvalorestampilla;
                newFormData.valorTiqueteMostrar         = formatearNumero(tiquete.tiquvalortiquete);
                newFormData.valorFondoReposicionMostrar = formatearNumero(tiquete.tiquvalorfondoreposicion);
                newFormData.valorTotalTiquete           = formatearNumero(tiquete.tiquvalortotal);
                const tarifaTiquetesFiltradas           = res.tarifaTiquetes.filter(tt => tt.rutaid === tiquete.rutaid && tt.depaiddestino === tiquete.depaiddestino && tt.muniiddestino === tiquete.muniiddestino);
                let valorSeguroPuesto                   = tarifaTiquetesFiltradas[0].tartiqvalorseguro;
                newFormData.valorSeguro                 = Number(valorSeguroPuesto);
                newFormData.valorSeguroMostrar          = formatearNumero(valorSeguroPuesto);

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

                res.tiquetePuestos.forEach(function(tiq){
                    newFormDataPuesto.push({
                        tivedipuesto: parseInt(tiq.tiqpuenumeropuesto)
                    });
                });

                setFormDataPuesto(newFormDataPuesto);
                setMunicipiosDestino(municipiosDestino);
                setEsEmpresa((tiquete.tipideid === 5) ? true : false);
                setTomarSeguro((tiquete.tiquvalorseguro > 0) ? true : false);
                setHabilitado((tiquete.contabilizado === 'SI') ? false : true );
                setEnviarTiquete((tiquete.perserpermitenotificacion) ? true : false);
                setTiqueteContabilizado((tiquete.contabilizado === 'SI')? true : false)

                //Dibujamos el vehiculo
                const distribucionVehiculos          = res.distribucionVehiculos;
                const distribucionVehiculosFiltrados = distribucionVehiculos.filter(vehiculo => vehiculo.vehiid === tiquete.vehiid);
                distribucionVehiculo(distribucionVehiculosFiltrados, res.tiquetePuestos, res.tiquetePuestosPlanilla);
            }

            setFormData(newFormData);
            setLoader(false);
        })
    }, []);

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
                            name={'planillaId'}
                            value={formData.planillaId}
                            label={'Planilla'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={consultarNodoDestino}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {planillaRutas.map(res=>{
                                return <MenuItem value={res.plarutid} key={res.plarutid}> {res.nombreRuta}</MenuItem>
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
                            onChange={consultarValorTiqueteDestino}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipiosDestino.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muniid} {res.muninombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item md={3} xl={3} sm={6} xs={12} style={{textAlign:'center'}}>
                        <FormControlLabel
                            control={<Switch name={'tomarSeguro'} 
                            value={tomarSeguro} onChange={handleChangeTomarSeguro} 
                            color="secondary" checked={(tomarSeguro) ? true : false}/>} 
                            label="Tomar seguro"
                        />
                    </Grid>
                    
                    {(tomarSeguro) ?
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Valor seguro:  $ </label>
                                <span className='textoRojo'>{'\u00A0'+ formData.valorSeguroMostrar}</span>
                            </Box>
                        </Grid>
                    : null}

                </Grid>

                <Grid container spacing={2}>
                    <Grid item xl={9} md={9} sm={12} xs={12}>
                        <Grid container spacing={2}>
                            <Grid item xl={12} md={12} sm={12} xs={12} style={{marginTop:'1em'}}>
                                {(dataPuestos.length > 0)?
                                    <Box className={claseDistribucionPuesto} style={{padding: '2px'}}>
                                        <Box style={{ display: 'flex', justifyContent: 'space-between', padding: '2px' }}>
                                            {Object.keys(dataPuestos).map((listId) => (
                                                <Box key={listId}>
                                                    {dataPuestos[listId].map((item) => {
                                                        const puestoChequeado = formDataPuesto.find(resul => resul.tivedipuesto === parseInt(item.contenido));
                                                        const marcarCheckbox  = (puestoChequeado !== undefined) ? true : false;
                                                        const marcado         = puestoMarcado.find(resul => resul.tiqpuenumeropuesto === item.contenido);
                                                        const checkbox        = (marcado !== undefined) ?  <Checkbox defaultChecked
                                                                                                                icon={<img src={puestoVehiculo} />}
                                                                                                                checkedIcon={<img src={puestoVehiculoSeleccionado} />} /> :
                                                                                                            <Checkbox
                                                                                                                checked={marcarCheckbox}
                                                                                                                icon={<img src={puestoVehiculo} />}
                                                                                                                checkedIcon={<img src={puestoVehiculoSeleccionado} />}
                                                                                                            />;
                                                        return (
                                                            <Box key={item.puestoColumna}>
                                                                {(item.clase === 'asiento' && !item.puestoVendido) ?
                                                                    <FormGroup row name={"puestos"} value={formDataPuesto.tivedipuesto}
                                                                        onChange={handleChangePuesto}>
                                                                        <FormControlLabel value={item.contenido} label={item.contenido} 
                                                                                          control={checkbox} />
                                                                    </FormGroup>
                                                                :  
                                                                    <Box className={item.clase}>
                                                                        <p>{item.contenido}</p>
                                                                    </Box>
                                                                }
                                                            </Box>
                                                        );
                                                    })}
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
                                    onChange={calcularValorTotalDescuento}
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
                            color="secondary" checked={(enviarTiquete) ? true : false}/>} 
                            label="Enviar copia del tiquete al correo"
                        />
                    </Grid>
                    
                    {(tiqueteContabilizado) ?
                        <Grid item md={12} xl={12} sm={12} xs={12} style={{marginTop:'1em'}}>
                            <Box className='mensajeAdvertencia'>
                                <ErrorIcon />
                                <p>No es posible modificar un tiquete que ya ha sido contabilizado. Por favor, póngase en contacto con el administrador para que realice el proceso necesario que permita su edición.</p>
                            </Box>
                        </Grid>
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
                title   = {'Visualizar factura en PDF del tiquete'} 
                content = {<VisualizarPdf id={idTiquete} />} 
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'smallFlot'
                abrir   = {abrirModal}
            />
        </Box>
    )
}
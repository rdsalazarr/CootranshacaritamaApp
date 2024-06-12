import React, {useState, useEffect, Fragment} from 'react';
import {TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, MenuItem, Stack, Box, FormControlLabel, Switch, FormGroup, Checkbox} from '@mui/material';
import puestoVehiculoSeleccionado from "../../../../../images/iconoPuestoVehiculoSeleccionado.png";
import puestoVehiculo from "../../../../../images/iconoPuestoVehiculo.png";
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {ModalDefaultAuto } from '../../../layout/modal';
import {FormatearNumero} from "../../../layout/general";
import {LoaderModal} from "../../../layout/loader";
import ErrorIcon from '@mui/icons-material/Error';
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';

export default function New({data, tipo}){
    let tiquid        = (tipo === 'U') ? data.tiquid : '000';
    let plarutid      = (tipo === 'U') ? data.plarutid : '000';
    const [formData, setFormData] = useState({codigo:tiquid,            tipoIdentificacion:'',          documento:'',             primerNombre:'',
                                              segundoNombre:'',         primerApellido:'',              segundoApellido:'',       direccion:'',
                                              correo:'',                telefonoCelular:'',             departamentoOrigen:'',    municipioOrigen:'',
                                              departamentoDestino:'',   municipioDestino:'',            valorTiquete :'',         planillaId:'',
                                              valorDescuento:'',        valorFondoReposicion:'',        valorTotal:'',            personaId:'000',
                                              valorTiqueteMostrar :'',  valorFondoReposicionMostrar:'', valorTotalTiquete:'',     cantidadPuesto: '',
                                              valorSeguro:'',           valorSeguroMostrar:'',          rutaId: '',               valorEstampilla:'',  
                                              valorTotalEstampilla: '', fondoReposicion:'',             valorFondoRecaudo:'',     valorFondoRecaudoTotal:'',
                                              valorTotalSeguro:'',      valorTiqueteEditar:'',          valorSeguroEditar:'',     fondoReposicionEditar:'',
                                              valorEstampillaEditar:'', valorFondoRecaudoEditar:'',     totalPuntosAcomulados:'', enviarTiquete:'', 
                                              redimirPuntos:'',         valorPuntosAcomulados:'',        tipo:tipo});

    const [claseDistribucionPuesto, setClaseDistribucionPuesto] = useState('distribucionPuestoGeneral' + 'Venta');
    const [mostrarRedencionPuntos, setMostrarRedencionPuntos] = useState(false);
    const [tiqueteContabilizado, setTiqueteContabilizado] = useState(false);
    const [distribucionVehiculos, setDistribucionVehiculos] = useState([]);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [municipiosDestino, setMunicipiosDestino] = useState([]);
    const [municipiosOrigen, setMunicipiosOrigen] = useState([]);
    const [redimirPuntos, setRedimirPuntos] = useState(false);  
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

    const handleChangeTomarSeguro = (e) => {
        setTomarSeguro(e.target.checked);
        let valorTiquete = (tipo === 'U') ? formData.valorTiqueteEditar : formData.valorTiquete;
        let valorSeguro  = (tipo === 'U') ? formData.valorSeguroEditar : formData.valorSeguro;
        let valorDescuento             = Number(formData.valorDescuento)
        let cantidadPuesto             = formData.cantidadPuesto;
        let valorSeguroPuesto          = valorSeguro * cantidadPuesto;
        let valorTiquetePuesto         = (valorTiquete * cantidadPuesto) - valorDescuento;
        let valorTotalTiquete          = (e.target.checked) ? valorTiquetePuesto + valorSeguroPuesto : valorTiquetePuesto;
        let newFormData                = {...formData}
        newFormData.valorTotal         = valorTotalTiquete;
        newFormData.valorTotalSeguro   = valorSeguroPuesto;
        newFormData.valorSeguroMostrar = FormatearNumero({numero: valorSeguroPuesto}); 
        newFormData.valorTotalTiquete  = FormatearNumero({numero: valorTotalTiquete});
        setFormData(newFormData);
    }

    const handleChangeRedimirPuntos = (e) => {
        setRedimirPuntos(e.target.checked);
        let newFormData               = {...formData}
        let totalPuntosAcomulados     = Number(formData.totalPuntosAcomulados);
        let valorTotal                = Number(formData.valorTotal);
        let valorTotalTiquete         = (e.target.checked) ? (valorTotal - totalPuntosAcomulados) : valorTotal;
        newFormData.valorTotalTiquete = FormatearNumero({numero: valorTotalTiquete});
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
        let newFormData       = {...formData}
        let valorEstampilla   = 0;
        let valorTiquete      = 0;
        let fondoReposicion   = 0;
        let valorFondoRecaudo = 0;
        let valorSeguro       = 0;

        if(tipo === 'U'){
            valorEstampilla   = formData.valorEstampillaEditar;
            valorTiquete      = formData.valorTiqueteEditar;
            fondoReposicion   = formData.fondoReposicionEditar;
            valorFondoRecaudo = formData.valorFondoRecaudoEditar;
            valorSeguro       = formData.valorSeguroEditar;
        }else{
            valorEstampilla   = formData.valorEstampilla;
            valorTiquete      = formData.valorTiquete;
            fondoReposicion   = formData.fondoReposicion;
            valorFondoRecaudo = formData.valorFondoRecaudo;
            valorSeguro       = formData.valorSeguro;
        }

        let valorTiquetePuesto                  = valorTiquete * cantidadPuesto;
        let valorFondoReposicion                = (valorTiquetePuesto * fondoReposicion) / 100;
        let valorSeguroPuesto                   = (tomarSeguro) ? Number(valorSeguro * cantidadPuesto) : 0;
        let valorTotalTiquete                   = valorTiquetePuesto + valorSeguroPuesto - Number(formData.valorDescuento);
        newFormData.valorFondoReposicion        = valorFondoReposicion;
        newFormData.valorTiquete                = valorTiquete;
        newFormData.valorTotal                  = valorTotalTiquete;
        newFormData.valorTotalSeguro            = valorSeguroPuesto;
        newFormData.valorTiqueteMostrar         = FormatearNumero({numero: valorTiquete});
        newFormData.valorFondoReposicionMostrar = FormatearNumero({numero: valorFondoReposicion});
        newFormData.valorTotalTiquete           = FormatearNumero({numero: valorTotalTiquete});
        newFormData.valorSeguroMostrar          = FormatearNumero({numero: valorSeguroPuesto});
        newFormData.cantidadPuesto              = cantidadPuesto;
        newFormData.valorTotalEstampilla        = valorEstampilla * cantidadPuesto;
        newFormData.valorFondoRecaudoTotal      = cantidadPuesto * valorFondoRecaudo;
        setFormData(newFormData);
    }

    const handleSubmit = () =>{
        let newFormData             = {...formData}
        newFormData.enviarTiquete   = (enviarTiquete) ? 'SI' : 'NO'; 
        newFormData.redimirPuntos   = (redimirPuntos) ? 'SI' : 'NO'; 
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
                setFormData({codigo:tiquid,          tipoIdentificacion:'',          documento:'',             primerNombre:'',
                            segundoNombre:'',        primerApellido:'',              segundoApellido:'',       direccion:'',
                            correo:'',               telefonoCelular:'',             departamentoOrigen:'',    municipioOrigen:'',
                            departamentoDestino:'',  municipioDestino:'',            valorTiquete :'',         planillaId:'',
                            valorDescuento:'',       valorFondoReposicion:'',        valorTotal:'',            personaId:'000',
                            valorTiqueteMostrar :'', valorFondoReposicionMostrar:'', valorTotalTiquete:'',     cantidadPuesto: '',
                            valorSeguro:'',          valorSeguroMostrar:'',          rutaId: '',               valorEstampilla:'', 
                            valorTotalEstampilla: '', fondoReposicion:'',            valorFondoRecaudo:'',     valorFondoRecaudoTotal:'',
                            valorTotalSeguro:'',      valorTiqueteEditar:'',         valorSeguroEditar:'',     fondoReposicionEditar:'',
                            valorEstampillaEditar:'', valorFondoRecaudoEditar:'',    totalPuntosAcomulados:'', enviarTiquete:'', 
                            redimirPuntos:'',         valorPuntosAcomulados:'',       tipo:tipo });
                setMostrarRedencionPuntos(false);
                setEnviarTiquete(false);
                setRedimirPuntos(false);
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
                    let personaservicio               = res.data;
                    let totalPuntosAcomulados         = personaservicio.totalPuntosAcomulados;
                    newFormData.personaId             = personaservicio.perserid;
                    newFormData.primerNombre          = personaservicio.perserprimernombre;
                    newFormData.segundoNombre         = (personaservicio.persersegundonombre !== undefined) ? personaservicio.persersegundonombre : '';
                    newFormData.primerApellido        = (personaservicio.perserprimerapellido !== undefined) ? personaservicio.perserprimerapellido : '';
                    newFormData.segundoApellido       = (personaservicio.persersegundoapellido !== undefined) ? personaservicio.persersegundoapellido : '';
                    newFormData.direccion             = (personaservicio.perserdireccion !== undefined) ? personaservicio.perserdireccion : '';
                    newFormData.correo                = (personaservicio.persercorreoelectronico !== undefined) ? personaservicio.persercorreoelectronico : '';
                    newFormData.telefonoCelular       = (personaservicio.persernumerocelular !== undefined) ? personaservicio.persernumerocelular : '';
                    newFormData.totalPuntosAcomulados = (totalPuntosAcomulados !== undefined) ? totalPuntosAcomulados : '';
                    newFormData.valorPuntosAcomulados = (totalPuntosAcomulados !== undefined) ? FormatearNumero({numero: totalPuntosAcomulados}) : '';
                    setMostrarRedencionPuntos((totalPuntosAcomulados > 0) ? true : false);
                    setEnviarTiquete((personaservicio.perserpermitenotificacion) ? true : false);
                }else{
                    newFormData.personaId             = '000';
                    newFormData.primerNombre          = '';
                    newFormData.segundoNombre         = '';
                    newFormData.primerApellido        = '';
                    newFormData.segundoApellido       = '';
                    newFormData.direccion             = '';
                    newFormData.correo                = '';
                    newFormData.telefonoCelular       = '';
                    newFormData.totalPuntosAcomulados = '';
                    setEnviarTiquete(false);
                }
                setLoader(false); 
            })
        }
        setEsEmpresa((tpIdentificacion === 5) ? true : false);
        setFormData(newFormData);
    }

    const consultarMuncipioOrigen = (e) =>{
        if(e.target.value === '' || e.target.value === null){
            return;
        }

        let newFormData               = {...formData}
        let valorTiquete              = 0;
        let fondoReposicion           = 0;
        let valorSeguro               = 0;
        let valorEstampilla           = 0;
        let valorFondoRecaudo         = 0;
        const planillaRutasFiltradas  = planillaRutas.filter(planilla => planilla.plarutid === e.target.value);
        let rutaId                    = planillaRutasFiltradas[0].rutaid;
        let vehiculoId                = planillaRutasFiltradas[0].vehiid;
        let depaIdOrigen              = planillaRutasFiltradas[0].rutadepaidorigen;
        let muniIdOrigen              = planillaRutasFiltradas[0].rutamuniidorigen;
        let depaIdDestino             = planillaRutasFiltradas[0].rutadepaiddestino;
        let muniIdDestino             = planillaRutasFiltradas[0].rutamuniiddestino;
        let municipioOrigen           = planillaRutasFiltradas[0].municipioOrigen;
        let municipioDestino          = planillaRutasFiltradas[0].municipioDestino;

        const tarifaTiquetesFiltradas = tarifaTiquetes.filter(tt => tt.rutaid === rutaId && tt.tartiqdepaidorigen === depaIdOrigen && tt.tartiqmuniidorigen === muniIdOrigen 
                                                                    && tt.tartiqdepaiddestino === depaIdDestino && tt.tartiqmuniiddestino === muniIdDestino);

        if(tarifaTiquetesFiltradas.length === 0){
            showSimpleSnackbar("No existe valor del tiquete gestionado para la ruta "+municipioOrigen+' - '+municipioDestino, 'error');
            newFormData.valorTiquete         = 0;
            newFormData.valorFondoReposicion = 0;
            setFormData(newFormData);
            return;
        }

        setLoader(true);
        instance.post('/admin/despacho/tiquete/consultar/ventas/realizadas', {planillaId: e.target.value}).then(res=>{
            const distribucionVehiculosFiltrados  = distribucionVehiculos.filter(vehiculo => vehiculo.vehiid === vehiculoId);

            if(distribucionVehiculosFiltrados.length === 0){
                showSimpleSnackbar("No hay un vehículo disponible asignado a esta ruta o aún no se ha establecido una distribución", 'error');
                setLoader(false);
                return;
            }

            asignacionVehiculo(distribucionVehiculosFiltrados, res.data);

            newFormData.planillaId                  = e.target.value;
            newFormData.departamentoOrigen          = depaIdOrigen;
            newFormData.municipioOrigen             = muniIdOrigen;
            newFormData.departamentoDestino         = depaIdDestino;
            newFormData.municipioDestino            = muniIdDestino;
            newFormData.rutaId                      = rutaId;

            valorTiquete                            = tarifaTiquetesFiltradas[0].tartiqvalor;
            valorSeguro                             = tarifaTiquetesFiltradas[0].tartiqvalorseguro;
            fondoReposicion                         = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
            valorEstampilla                         = tarifaTiquetesFiltradas[0].tartiqvalorestampilla;
            valorFondoRecaudo                       = tarifaTiquetesFiltradas[0].tartiqvalorfondorecaudo;

            let valorFondoReposicion                = (valorTiquete * fondoReposicion) / 100;
            newFormData.valorSeguro                 = valorSeguro;
            newFormData.valorTiquete                = valorTiquete;
            newFormData.valorEstampilla             = valorEstampilla;
            newFormData.fondoReposicion             = fondoReposicion;
            newFormData.valorFondoReposicion        = valorFondoReposicion;
            newFormData.valorFondoRecaudo           = valorFondoRecaudo;
            newFormData.valorSeguroMostrar          = FormatearNumero({numero: valorSeguro});
            newFormData.valorTiqueteMostrar         = FormatearNumero({numero: valorTiquete});
            newFormData.valorFondoReposicionMostrar = FormatearNumero({numero: valorFondoReposicion});
            newFormData.valorTotalTiquete           = 0;
            newFormData.cantidadPuesto              = 0;

            let municipiosOrigen  = [];
            let municipiosDestino = [];
            municipios.forEach(function(muni){
                if(muni.rutaid === rutaId && muni.tipo === 'ORIGEN'){
                    municipiosOrigen.push({
                        muniid:     muni.muniid,
                        munidepaid: muni.munidepaid,
                        muninombre: muni.muninombre
                    });
                }

                if(muni.rutaid === rutaId && muni.tipo === 'DESTINO'){
                    municipiosDestino.push({
                        muniid:     muni.muniid,
                        munidepaid: muni.munidepaid,
                        muninombre: muni.muninombre
                    });
                }
            });

            setFormData(newFormData);
            setMunicipiosOrigen(municipiosOrigen);
            setMunicipiosDestino(municipiosDestino);
            setFormDataPuesto([]);
            setLoader(false);
        })
    }

    const consultarValorTiqueteOrigen = (e) =>{
        if(e.target.value === '' || e.target.value === null){
            return;
        }
        let newFormData         = {...formData}
        let valorTiquete        = 0;
        let fondoReposicion     = 0;
        let valorSeguro         = 0;
        let valorEstampilla     = 0;
        let valorFondoRecaudo   = 0;

        let municipiosFiltrados = municipios.filter(mun => mun.muniid ===  e.target.value);

        let depaIdOrigen        = municipiosFiltrados[0].munidepaid;
        let muniIdOrigen        = e.target.value;
        let depaIdDestino       = formData.departamentoDestino;
        let muniIdDestino       = formData.municipioDestino;
        let cantidadPuesto      = formData.cantidadPuesto;
        let rutaId              = formData.rutaId;

        const tarifaTiquetesFiltradas = tarifaTiquetes.filter(tt => tt.rutaid === rutaId && tt.tartiqdepaidorigen === depaIdOrigen && tt.tartiqmuniidorigen === muniIdOrigen 
                                                                 && tt.tartiqdepaiddestino === depaIdDestino && tt.tartiqmuniiddestino === muniIdDestino);

        if(tarifaTiquetesFiltradas.length > 0){
            valorTiquete      = tarifaTiquetesFiltradas[0].tartiqvalor;
            valorSeguro       = tarifaTiquetesFiltradas[0].tartiqvalorseguro;
            fondoReposicion   = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
            valorEstampilla   = tarifaTiquetesFiltradas[0].tartiqvalorestampilla;
            valorFondoRecaudo = tarifaTiquetesFiltradas[0].tartiqvalorfondorecaudo;
        }else{
            showSimpleSnackbar("No se han gestionado valores para el municipio de la ruta seleccionada", 'error');
        }

        let valorTiquetePuesto                  = (cantidadPuesto > 0) ? valorTiquete * cantidadPuesto : valorTiquete
        let valorFondoReposicion                = (valorTiquetePuesto * fondoReposicion) / 100;
        newFormData.valorSeguro                 = valorSeguro;
        newFormData.valorTiquete                = valorTiquete;
        newFormData.valorFondoReposicion        = valorFondoReposicion;
        newFormData.valorEstampilla             = valorEstampilla;
        newFormData.valorFondoRecaudo           = valorFondoRecaudo;
        newFormData.valorSeguroMostrar          = FormatearNumero({numero: valorSeguro});
        newFormData.valorTiqueteMostrar         = FormatearNumero({numero: valorTiquete});
        newFormData.valorFondoReposicionMostrar = FormatearNumero({numero: valorFondoReposicion});
        let valorSeguroPuesto                   = (tomarSeguro) ? Number(valorSeguro) * cantidadPuesto : 0
        let valorTotalTiquete                   = valorTiquetePuesto + valorSeguroPuesto - Number(formData.valorDescuento);
        newFormData.valorTotalTiquete           = (valorTotalTiquete > 0) ? FormatearNumero({numero: valorTotalTiquete}): 0;
        newFormData.valorTotal                  = (valorTotalTiquete > 0) ? valorTotalTiquete: 0;
        newFormData.departamentoOrigen          = depaIdOrigen;
        newFormData.municipioOrigen             = muniIdOrigen;
        setFormData(newFormData);
    }

    const consultarValorTiqueteDestino = (e) =>{
        if(e.target.value === '' || e.target.value === null){
            return;
        }
        let newFormData         = {...formData}
        let valorTiquete        = 0;
        let fondoReposicion     = 0;
        let valorSeguro         = 0;
        let valorEstampilla     = 0;
        let valorFondoRecaudo   = 0;

        let municipiosFiltrados = municipios.filter(mun => mun.muniid ===  e.target.value);
        let cantidadPuesto      = formData.cantidadPuesto;
        let depaIdOrigen        = formData.departamentoOrigen;
        let muniIdOrigen        = formData.municipioOrigen;
        let depaIdDestino       = municipiosFiltrados[0].munidepaid;
        let muniIdDestino       = e.target.value;
        let rutaId              = formData.rutaId;

        const tarifaTiquetesFiltradas = tarifaTiquetes.filter(tt => tt.rutaid === rutaId && tt.tartiqdepaidorigen === depaIdOrigen && tt.tartiqmuniidorigen === muniIdOrigen 
                                                                 && tt.tartiqdepaiddestino === depaIdDestino && tt.tartiqmuniiddestino === muniIdDestino);

        if(tarifaTiquetesFiltradas.length > 0){
            valorTiquete      = tarifaTiquetesFiltradas[0].tartiqvalor;
            valorSeguro       = tarifaTiquetesFiltradas[0].tartiqvalorseguro;
            fondoReposicion   = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
            valorEstampilla   = tarifaTiquetesFiltradas[0].tartiqvalorestampilla;
            valorFondoRecaudo = tarifaTiquetesFiltradas[0].tartiqvalorfondorecaudo;
        }else{
            showSimpleSnackbar("No se han gestionado valores para el municipio de la ruta seleccionada", 'error');
        }

        let valorTiquetePuesto                  = (cantidadPuesto > 0) ? valorTiquete * cantidadPuesto : valorTiquete
        let valorFondoReposicion                = (valorTiquetePuesto * fondoReposicion) / 100;
        newFormData.valorSeguro                 = valorSeguro;
        newFormData.valorTiquete                = valorTiquete;
        newFormData.valorFondoReposicion        = valorFondoReposicion;
        newFormData.valorEstampilla             = valorEstampilla;
        newFormData.valorFondoRecaudo           = valorFondoRecaudo;
        newFormData.valorSeguroMostrar          = FormatearNumero({numero: valorSeguro});
        newFormData.valorTiqueteMostrar         = FormatearNumero({numero: valorTiquete});
        newFormData.valorFondoReposicionMostrar = FormatearNumero({numero: valorFondoReposicion});
        let valorSeguroPuesto                   = (tomarSeguro) ? Number(valorSeguro) * cantidadPuesto : 0
        let valorTotalTiquete                   = valorTiquetePuesto + valorSeguroPuesto - Number(formData.valorDescuento);
        newFormData.valorTotalTiquete           = (valorTotalTiquete > 0) ? FormatearNumero({numero: valorTotalTiquete}) : 0;
        newFormData.valorTotal                  = (valorTotalTiquete > 0) ? valorTotalTiquete: 0;
        newFormData.departamentoDestino         = depaIdDestino;
        newFormData.municipioDestino            = muniIdDestino;
        setFormData(newFormData);
    }

    const asignacionVehiculo = (distribucionVehiculo, puestosVendidos, puestosVendidosGeneral = []) => {
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
        newFormData.valorTotalTiquete = FormatearNumero({numero: valorTotalTiquete});
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
                newFormData.departamentoOrigen          = tiquete.tiqudepaidorigen;
                newFormData.municipioOrigen             = tiquete.tiqumuniidorigen;
                newFormData.departamentoDestino         = tiquete.tiqudepaiddestino;
                newFormData.municipioDestino            = tiquete.tiqumuniiddestino;
                newFormData.rutaId                      = tiquete.rutaid;
                newFormData.planillaId                  = tiquete.plarutid;
                newFormData.cantidadPuesto              = tiquete.tiqucantidad;
                newFormData.valorTiquete                = tiquete.tiquvalortiquete;
                newFormData.valorDescuento              = (tiquete.tiquvalordescuento !== null) ? tiquete.tiquvalordescuento : '';
                newFormData.valorFondoReposicion        = Number(tiquete.tiquvalorfondoreposicion);
                newFormData.valorTotal                  = Number(tiquete.tiquvalortotal);
                newFormData.valorEstampilla             = Number(tiquete.tiquvalorestampilla);
                newFormData.valorTotalEstampilla        = Number(tiquete.tiquvalorestampilla) * tiquete.tiqucantidad;
                newFormData.valorTiqueteMostrar         = FormatearNumero({numero: tiquete.tiquvalortiquete});
                newFormData.valorFondoReposicionMostrar = FormatearNumero({numero: tiquete.tiquvalorfondoreposicion});
                newFormData.valorTotalTiquete           = FormatearNumero({numero: tiquete.tiquvalortotal});
                newFormData.valorFondoRecaudoTotal      = Number(tiquete.tiquvalorfondorecaudo);
                newFormData.valorSeguroMostrar          = Number(tiquete.tiquvalorseguro);

                const tarifaTiquetesFiltradas           = res.tarifaTiquetes.filter(tt => tt.rutaid === tiquete.rutaid  && tt.tartiqdepaidorigen === tiquete.tiqudepaidorigen && tt.tartiqmuniidorigen === tiquete.tiqumuniidorigen  &&
                                                                                    tt.tartiqdepaiddestino === tiquete.tiqudepaiddestino && tt.tartiqmuniiddestino === tiquete.tiqumuniiddestino);

                let valorTiquete                        = tarifaTiquetesFiltradas[0].tartiqvalor;
                let valorSeguro                         = tarifaTiquetesFiltradas[0].tartiqvalorseguro;
                let fondoReposicion                     = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
                let valorEstampilla                     = tarifaTiquetesFiltradas[0].tartiqvalorestampilla;
                let valorFondoRecaudo                   = tarifaTiquetesFiltradas[0].tartiqvalorfondorecaudo;
                newFormData.valorTiqueteEditar          = valorTiquete;
                newFormData.valorSeguroEditar           = valorSeguro;
                newFormData.fondoReposicionEditar       = fondoReposicion;
                newFormData.valorEstampillaEditar       = valorEstampilla;
                newFormData.valorFondoRecaudoEditar     = valorFondoRecaudo;

                let municipiosOrigen  = [];
                let municipiosDestino = [];
                res.municipios.forEach(function(muni){
                    if(muni.rutaid === tiquete.rutaid && muni.tipo === 'ORIGEN'){
                        municipiosOrigen.push({
                            muniid:     muni.muniid,
                            munidepaid: muni.munidepaid,
                            muninombre: muni.muninombre
                        });
                    }
    
                    if(muni.rutaid === tiquete.rutaid && muni.tipo === 'DESTINO'){
                        municipiosDestino.push({
                            muniid:     muni.muniid,
                            munidepaid: muni.munidepaid,
                            muninombre: muni.muninombre
                        });
                    }
                });

                res.tiquetePuestos.forEach(function(tiq){
                    newFormDataPuesto.push({
                        tivedipuesto: parseInt(tiq.tiqpuenumeropuesto)
                    });
                });

                setFormDataPuesto(newFormDataPuesto);
                setMunicipiosOrigen(municipiosOrigen);
                setMunicipiosDestino(municipiosDestino);
                setEsEmpresa((tiquete.tipideid === 5) ? true : false);
                setTomarSeguro((tiquete.tiquvalorseguro > 0) ? true : false);
                setHabilitado((tiquete.contabilizado === 'SI') ? false : true );
                setEnviarTiquete((tiquete.perserpermitenotificacion) ? true : false);
                setTiqueteContabilizado((tiquete.contabilizado === 'SI')? true : false)

                //Dibujamos el vehiculo
                const distribucionVehiculos          = res.distribucionVehiculos;
                const distribucionVehiculosFiltrados = distribucionVehiculos.filter(vehiculo => vehiculo.vehiid === tiquete.vehiid);
                asignacionVehiculo(distribucionVehiculosFiltrados, res.tiquetePuestos, res.tiquetePuestosPlanilla);
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
                            onChange={consultarMuncipioOrigen}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {planillaRutas.map(res=>{
                                return <MenuItem value={res.plarutid} key={res.plarutid}> {res.nombreRuta}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'municipioOrigen'}
                            value={formData.municipioOrigen}
                            label={'Municipio origen'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={consultarValorTiqueteOrigen}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipiosOrigen.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'municipioDestino'}
                            value={formData.municipioDestino}
                            label={'Municipio destino'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={consultarValorTiqueteDestino}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipiosDestino.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
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

                            {(tomarSeguro) ?
                                <Grid item xl={12} md={12} sm={12} xs={12}>
                                    <Box className='frmTextoColor'>
                                        <label>Valor seguro:  $ </label>
                                        <span className='textoRojo'>{'\u00A0'+ formData.valorSeguroMostrar}</span>
                                    </Box>
                                </Grid>
                            : null}

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

                    {(mostrarRedencionPuntos) ? 
                        <Fragment>
                            <Grid item md={3} xl={3} sm={6} xs={12}>
                                <FormControlLabel
                                    control={<Switch name={'redimirPuntos'} 
                                    value={redimirPuntos} onChange={handleChangeRedimirPuntos}
                                    color="secondary" checked={(redimirPuntos) ? true : false}/>} 
                                    label="Redimir puntos"
                                />
                            </Grid> 

                            <Grid item xl={2} md={2} sm={6} xs={12}>
                                <Box className='frmTextoColor'>
                                    <label>Valor a redimir:  $ </label>
                                    <span className='textoRojo'>{'\u00A0'+ formData.valorPuntosAcomulados}</span>
                                </Box>
                            </Grid>
                        </Fragment>
                    : null}
                    
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
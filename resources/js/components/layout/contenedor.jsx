import React, {useState, useEffect} from 'react';
import {BrowserRouter as Router, Route, Routes, NavLink } from "react-router-dom";
import {Drawer, List, ListItem, IconButton, Divider, Icon, Box } from '@mui/material';
import { ListItemButton, ListItemText, Collapse } from '@mui/material';
import { ThemeProvider } from '@mui/material/styles';
import ExpandLess from '@mui/icons-material/ExpandLess';
import ExpandMore from '@mui/icons-material/ExpandMore';
import IconoMenu from "@mui/icons-material/Menu";
import ClearIcon from '@mui/icons-material/Clear';
import "../../../scss/contenedor.scss";
import {generalTema} from "./theme";
import instance from './instance';
import Loader from "./loader";

import Welcome from "../admin/welcome";
import EnConstruccion from "../admin/enConstruccion";

import Menu from "../admin/menu/list";
import Usuario from "../admin/usuario/list";
import MiPerfil from "../admin/usuario/miPerfil";
import InformacionCorreo from "../admin/informacion/correo/list";
import InformacionGeneralPdf from "../admin/informacion/generalPdf/list";
import DatosGeograficos from "../admin/datosGeograficos/list";
import Empresa from "../admin/empresa/list";

import GestionTipos from "../admin/tipos/list";
import SeriesDocumentales from "../admin/seriesDocumental/list";
import Dependencia from "../admin/dependencia/list";
import Persona from "../admin/persona/list";
import Festivos from "../admin/festivo/list";
import Agencia from "../admin/agencia/list";
import Asociados from "../admin/asociado/gestionar/list";
import DesvincularAsociado from "../admin/asociado/desvincular/search";
import AsociadosInactivos from "../admin/asociado/inactivos/list";

import Acta from "../admin/produccionDocumental/acta/list";
import Citacion from "../admin/produccionDocumental/citacion/list";
import Constancia from "../admin/produccionDocumental/constancia/list";
import Certificado from "../admin/produccionDocumental/certificado/list";
import Circular from "../admin/produccionDocumental/circular/list";
import Oficio from "../admin/produccionDocumental/oficio/list";
import FirmarDocumento from "../admin/produccionDocumental/firmar/list";

import RadicadoEntrante from "../admin/radicacion/documentoEntrante/gestionar/list";
import AnularRadicadoEntrante from "../admin/radicacion/documentoEntrante/anular/list";
import BandejaRadicadoEntrante from "../admin/radicacion/documentoEntrante/bandeja/list";

import ArchivoHistorico from "../admin/archivoHistorico/gestionar/list";
import ConsultarArchivoHistorico from "../admin/archivoHistorico/consultar/list";

import TiposVehiculos from "../admin/vehiculos/tipos/list";
import Vehiculo from "../admin/vehiculos/automovil/list";
import Conductor from "../admin/vehiculos/conductor/list";
import AsignarVehiculo from "../admin/vehiculos/asignar/search";

import LineaCredito from "../admin/cartera/lineaCredito/list";
import SolicitudCredito from "../admin/cartera/solicitudCredito/search";
import AprobacionCredito from "../admin/cartera/cambiarEstado/aprobar/list";
import DesembolsarCredito from "../admin/cartera/cambiarEstado/desembolsar/search";
import CobroCartera from "../admin/cartera/gestionCobro/list";
import HistorialSolicitudCredito from "../admin/cartera/solicitudCredito/historial";

import Rutas from "../admin/despacho/ruta/list";
import ServicoEspecial from "../admin/despacho/servicioEspecial/list";
import Planillas from "../admin/despacho/planilla/list";
import Encomiendas from "../admin/despacho/encomienda/list";

import Rutas1 from "../admin/vehiculos/tipos/vehiculo/old/list1";
import Rutas2 from "../admin/vehiculos/tipos/vehiculo/old/list2";
import Rutas3 from "../admin/vehiculos/tipos/vehiculo/old/ruta3";

//clean_hands_icon
const HeaderMenu = ({open, setOpen}) =>{
    return (
        <div className={"toolbarIcon"} onClick={() => setOpen(!open)}>
            <List className={"accionMenu"}>
                <ListItem className={"iconoMenu"}>
                    <label>{open ? "Cerrar menú" : ""}</label>
                  <IconButton>{open ? <div> <ClearIcon /></div> : <IconoMenu style={{marginLeft: '-10px' }}/>} </IconButton>
                </ListItem>
                <ListItem style={{padding: 0}}>
                    <div className={"titleMenu"}>
                        <span className={open ? '': 'hidden'}>COOPERATIVA</span>
                        <h3>{ open ?  "HACARITAMA" : "CTH"}</h3>
                    </div> 
                </ListItem>
            </List>
        </div>
    )
}

const ListMenu = ({res, control, setControll, j}) =>{
    const [open, setOpen] = useState(false);
    const handleClick = () => {
        setOpen((control) ?  !open: true);
        setControll(true);
    };
   
    return (<Box><List sx={{paddingTop: 0, paddingBottom: 0}} >
                <ListItemButton onClick={handleClick} key={'listeButton'+j} >
                    <Icon>{res.icono}</Icon>
                    <ListItemText primary={res.nombre} sx={{paddingLeft: '0.5em'}} />
                    {open ? <ExpandLess /> : <ExpandMore />}
                </ListItemButton>
                <Collapse in={(control === false) ? control:  open}  unmountOnExit timeout="auto" key={'collapse'+j}>

                    {res.itemMenu.map((item, i ) =>{
                        return (
                            <NavLink className={"itemMenu"} exact = {`true`} to={`/${item.ruta}`}  key={item.ruta + 'nav'} >
                                <List component="div" disablePadding key={i + 'datosGeneral'}>
                                    <ListItemButton sx={{ pl: 4 }}>
                                        <Icon>{item.icono}</Icon>
                                        <ListItemText primary={item.menu}  sx={{paddingLeft: '0.5em'}} />
                                    </ListItemButton>
                                </List>
                             </NavLink>
                        );
                    })}

                </Collapse>
            </List>
            <Divider />
        </Box>);
}

const ItemMenu = ({route, text, icon}) => {
    return (
        <List disablePadding key={route + "_li"}>
            {(route === 'logout' || route === 'dashboard') ?
                <a  href={'/'+ route}  key={route + 'nav'} className={"itemMenu"} >
                    <ListItem button key={route + 'item'} className={"nested"}>
                        <Icon className={'pr10'}>{icon}</Icon>
                        <ListItemText key={route + '_text'}  primary={text}/>
                    </ListItem>
                </a>:
                <NavLink exact = {`true`} to={`/${route}`}  key={route + 'nav'}
                                className={"itemMenu"} >
                    <ListItem button key={route + 'item'} className={"nested"}>
                        <Icon className={'pr10'}>{icon}</Icon>
                        <ListItemText key={route + '_text'}  primary={text}/>
                    </ListItem>
                </NavLink >
            }
        </List>
    );
};

const componenteMenu = [    
    {   nombre: 'Otros',
        icono : 'send_time_extension_icon',
        itemMenu: [
           /* {ruta : 'admin/despacho/rutas',             menu: 'Rutas',            icono : 'directions_icon',     componente : <Rutas /> },
            {ruta : 'admin/despacho/servicioEspecial',  menu: 'Servico especial', icono : 'taxi_alert_icon',     componente : <Rutas1 /> },
            {ruta : 'admin/despacho/planillas',         menu: 'Planillas',        icono : 'no_crash_icon',       componente : <Rutas1 />}, 
            {ruta : 'admin/despacho/encomiendas',       menu: 'Encomiendas',      icono : 'local_shipping_icon', componente : <Rutas2 />},*/
            {ruta : 'admin/despacho/otros',             menu: 'Otros',            icono : 'traffic_icon',        componente : <Rutas />},
            {ruta : 'admin/despacho/otros1',            menu: 'Otros',            icono : 'traffic_icon',        componente : <Rutas1 />},
            {ruta : 'admin/despacho/otros2',            menu: 'Otros',            icono : 'traffic_icon',        componente : <Rutas2 />},
            {ruta : 'admin/despacho/otros3',            menu: 'Otros',            icono : 'traffic_icon',        componente : <Rutas3 />},
        ]
    },
];

const menuComponente = [
    {id:1,componente : <Menu />},
    {id:2,componente : <InformacionCorreo />},
    {id:3,componente : <InformacionGeneralPdf />},
    {id:4,componente : <DatosGeograficos />},
    {id:5,componente : <Empresa />},
    {id:6,componente : <GestionTipos />},
    {id:7,componente : <SeriesDocumentales />},
    {id:8,componente : <Dependencia />},
    {id:9,componente : <Persona />},
    {id:10,componente : <Agencia />},
    {id:11,componente : <Usuario />},
    {id:12,componente : <Festivos />},

    {id:13,componente : <Festivos />},

    {id:14,componente : <Acta />},
    {id:15,componente : <Certificado />},
    {id:16,componente : <Circular />},
    {id:17,componente : <Citacion />},
    {id:18,componente : <Constancia />},
    {id:19,componente : <Oficio />},
    {id:20,componente : <FirmarDocumento />},
    {id:21,componente : <RadicadoEntrante />},
    {id:22,componente : <AnularRadicadoEntrante />},
    {id:23,componente : <BandejaRadicadoEntrante />},
    {id:24,componente : <ArchivoHistorico />},
    {id:25,componente : <ConsultarArchivoHistorico />},
    {id:26,componente : <Asociados />},
    {id:27,componente : <DesvincularAsociado />},
    {id:28,componente : <AsociadosInactivos />},
    {id:29,componente : <TiposVehiculos />},
    {id:30,componente : <Vehiculo />},
    {id:31,componente : <Conductor />},
    {id:32,componente : <AsignarVehiculo />},
    {id:33,componente : <LineaCredito />},
    {id:34,componente : <SolicitudCredito />},
    {id:35,componente : <AprobacionCredito />},
    {id:36,componente : <DesembolsarCredito />},
    {id:37,componente : <HistorialSolicitudCredito />},
    {id:38,componente : <CobroCartera />},
    {id:39,componente : <Rutas />},
    {id:40,componente : <ServicoEspecial />},
    {id:41,componente : <Planillas />},
    {id:42,componente : <Encomiendas />},
    
    {id:43,componente : <Rutas1 />},
    {id:44,componente : <Rutas2 />},
    {id:45,componente : <Rutas3 />},
    {id:46,componente : <EnConstruccion />},
    {id:47,componente : <EnConstruccion />},
    {id:48,componente : <EnConstruccion />},
    {id:49,componente : <EnConstruccion />},
    {id:50,componente : <EnConstruccion />},
];

export default function  Contenedor () {
    const [loader, setLoader] = useState(true);
    const [open, setOpen] = useState(true); 
    const [componente, setComponente] = useState([]); 

    useEffect(() => {
       instance.get('/admin/generarMenu').then(res=>{      
        setComponente(res.data);
        setLoader(false);   
        })
    }, []);

    if(loader){
        return <Loader />
    }

    return (
        <ThemeProvider theme={generalTema}>
            <Router>
                <Box className={open ? 'component' : 'component componentClose'}>
                </Box>
                <Drawer variant="permanent" className={open ? "nav" : "nav navClose"} open={open}>
                    <HeaderMenu open={open} setOpen={setOpen} />
                    <Divider/>
                </Drawer>

                <Box className={open ? 'component' : 'component componentClose'}>
                    <Box className='containerAdmin'>
                        <Routes >
                            <Route exact = {`true`} path="/dashboard" element={<Welcome />}/>
                            <Route exact = {`true`} path="/admin/miPerfil" element={<MiPerfil />}/>
                            {componente.map(item=>{
                                return item.itemMenu.map((res, i ) =>{
                                    const resultado = menuComponente.find( resul => resul.id === parseInt(res.id));
                                    return (<Route key={'R-'+res.ruta} exact = {`true`} path={'/'+res.ruta} element={resultado.componente}></Route>)
                                }
                            )})}

                            {componenteMenu.map(item=>{
                                return item.itemMenu.map((res, i ) =>{
                                   return (<Route key={'R-'+res.ruta} exact = {`true`} path={'/'+res.ruta} element={res.componente}></Route>)
                                }
                            )})}
                        </Routes>
                    </Box>
                </Box>

                <Drawer variant="permanent" className={open ? "nav" : "nav navClose"} open={open}>
                    <HeaderMenu open={open} setOpen={setOpen} />
                    <Divider/>
                    <ItemMenu route={'dashboard'} text={'Inicio'} icon={'home'} />
                    {componente.map((res, i )=>{
                        return <ListMenu res={res} control={open} setControll={setOpen} j={i} key ={'list'+ i}/>
                    }) }

                    {componenteMenu.map((res, i )=>{
                        return <ListMenu res={res} control={open} setControll={setOpen} j={i} key ={'list'+ i}/>
                    }) }

                    <ItemMenu route={'admin/miPerfil'} text={'Mi perfil'} icon={'person'} />
                    <ItemMenu route={'logout'} text={'Salir'} icon={'exit_to_app'} />
                </Drawer>

            </Router>
        </ThemeProvider>
    );
}
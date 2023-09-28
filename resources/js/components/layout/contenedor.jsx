import React, {useState, useEffect} from 'react';
import {BrowserRouter as Router, Route, Routes, NavLink } from "react-router-dom";
import {Drawer, List, ListItem,  IconButton, Divider, Icon, Box  } from '@mui/material';
import { ListItemButton , ListItemText ,  Collapse  } from '@mui/material';
import { ThemeProvider } from '@mui/material/styles';
import ExpandLess from '@mui/icons-material/ExpandLess';
import ExpandMore from '@mui/icons-material/ExpandMore';
import IconoMenu from "@mui/icons-material/Menu";
import ClearIcon from '@mui/icons-material/Clear';
import "../../../scss/contenedor.scss";
import {generalTema} from "../layout/theme";
import instance from '../layout/instance';

import Welcome from "../admin/welcome";
import Usuario from "../admin/usuario/list";
import NotificarCorreo from "../admin/notificar/correo/list";

import Menu from "../admin/menu/list";
import DatosGeograficos from "../admin/datosGeograficos/list";
import Empresa from "../admin/empresa/list";

import GestionTipos from "../admin/tipos/list";
import SeriesDocumentales from "../admin/seriesDocumental/list";
import Dependencia from "../admin/dependencia/list";
import Persona from "../admin/persona/list";

import Acta from "../admin/produccionDocumental/acta/list";
import Citacion from "../admin/produccionDocumental/citacion/list";
import Constancia from "../admin/produccionDocumental/constancia/list";
import Certificado from "../admin/produccionDocumental/certificado/list";
import Circular from "../admin/produccionDocumental/circular/list";
import Oficio from "../admin/produccionDocumental/oficio/list";

import FirmarDocumento from "../admin/produccionDocumental/firmar/list";

import RegistrarRadicadoEntrante from "../admin/radicacion/documentoEntrante/registrar/new";
import VerificarRadicadoEntrante from "../admin/radicacion/documentoEntrante/verificar/list";
import AnularRadicadoEntrante from "../admin/radicacion/documentoEntrante/anular/list";


const HeaderMenu = ({open , setOpen}) =>{
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
    {   nombre: 'Configuración',
        icono : 'settings_applications',
        itemMenu: [  
            {ruta : 'admin/menu', menu: 'Menu', icono : 'add_chart ', componente : <Menu /> },
            {ruta : 'admin/informacionNotificarCorreo', menu: 'Info notificar correo', icono : 'mail_outline_icon', componente : <NotificarCorreo />}, 
            {ruta : 'admin/datosTerritorial', menu: 'Datos territorial', icono : 'language_icon ', componente : <DatosGeograficos /> },
            {ruta : 'admin/empresa', menu: 'Empresa', icono : 'store', componente : <Empresa /> },            
        ]
    },
    {   nombre: 'Gestionar',
        icono : 'ac_unit_icon',
        itemMenu: [            
            {ruta : 'admin/gestionarTipos', menu: 'Tipos', icono : 'star_rate_icon', componente : <GestionTipos /> },
            {ruta : 'admin/gestionarSeriesDocumentales', menu: 'Series', icono : 'insert_chart_icon', componente : <SeriesDocumentales /> },
            {ruta : 'admin/gestionarDependencia', menu: 'Dependencia', icono : 'maps_home_work_icon', componente : <Dependencia /> },
            {ruta : 'admin/gestionarPersona', menu: 'Persona', icono : 'person_icon', componente : <Persona /> },
            {ruta : 'admin/usuario', menu: 'Usuario', icono : 'person', componente : <Usuario /> }, 
        ]
    } ,
    {   nombre: 'Produccion documental',
        icono : 'menu_book_icon',
        itemMenu: [
            {ruta : 'admin/produccion/documental/acta', menu: 'Acta', icono : 'local_library_icon', componente : <Acta /> }, 
            {ruta : 'admin/produccion/documental/certificado', menu: 'Certificado', icono : 'note_icon', componente : <Certificado /> },
            {ruta : 'admin/produccion/documental/circular', menu: 'Circular', icono : 'menu_book_icon', componente : <Circular /> },
            {ruta : 'admin/produccion/documental/citacion', menu: 'Citación', icono : 'collections_bookmark_icon', componente : <Citacion /> },           
            {ruta : 'admin/produccion/documental/constancia', menu: 'Constancia', icono : 'import_contacts_icon', componente : <Constancia /> }, 
            {ruta : 'admin/produccion/documental/oficio', menu: 'Oficio', icono : 'library_books_icon', componente : <Oficio /> }, 
        ]
    },
    {   nombre: 'Firmar',
        icono : 'folder_special_icon',
        itemMenu: [       
            {ruta : 'admin/produccion/documental/firmar', menu: 'Documentos ', icono : 'import_contacts_icon', componente : <FirmarDocumento /> },
        ]
    } ,
    {   nombre: 'Radicación',
        icono : 'insert_page_break_icon',
        itemMenu: [       
            {ruta : 'admin/radicacion/documento/entrante', menu: 'Registrar Doc. Ent', icono : 'post_add_icon', componente : <RegistrarRadicadoEntrante /> },
            {ruta : 'admin/radicacion/documento/verificar', menu: 'Verificar  Doc. Ent', icono : 'bookmark_added_icon', componente : <VerificarRadicadoEntrante /> },
            {ruta : 'admin/radicacion/documento/anular', menu: 'Anular radicado', icono : 'layers_clear_icon', componente : <AnularRadicadoEntrante /> },
        ]
    } ,
    {   nombre: 'Archivo Histórico',
        icono : 'forward_to_inbox_icon',
        itemMenu: [       
            {ruta : 'admin/archivo/historico/gestionar', menu: 'Gestionar', icono : 'ac_unit_icon', componente : <RegistrarRadicadoEntrante /> },
            {ruta : 'admin/archivo/historico/consultar', menu: 'Consultar', icono : 'find_in_page_icon ', componente : <RegistrarRadicadoEntrante /> },
        ]
    } 
];


const menuComponente = [
    {id:11,componente : <Usuario />},
    {id:12,componente : <Usuario />}     
];

export default function  Contenedor ({componente, users}) {
    const [esInvitado, setEsInvitado] = useState(true); 
    useEffect(() => {
        /*instance.get('/nameUser').then(res=>{
            setEsInvitado((res.esInvitado === 1) ? true : false);
        })*/
    }, []);
    const [open, setOpen] = useState(true); 
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
                            <Route exact = {`true`} path="/dashboard" element={<Welcome susuario={users}/>}/>
                            {componenteMenu.map(item=>{
                                return item.itemMenu.map((res, i ) =>{
                                        /*const resultado = menuComponente.find( resul => resul.id === parseInt(res.id));
                                        return (<Route key={'R-'+res.ruta} exact = {`true`} path={'/'+res.ruta} element={resultado.componente}></Route>)*/  
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
                    {componenteMenu.map((res, i )=>{
                            return <ListMenu res={res} control={open} setControll={setOpen} j={i} key ={'list'+ i}/>
                        }) }
                    <ItemMenu route={'logout'} text={'Salir'} icon={'exit_to_app'} />
                </Drawer>

            </Router>
        </ThemeProvider>
    );
}
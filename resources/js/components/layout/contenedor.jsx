import React, {useState, useEffect} from 'react';
import ReactDOM from 'react-dom';
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
import Welcome from "../admin/curso/welcome";
import Usuario from "../admin/usuario/list";
import Evaluacion from "../admin/evaluacion/list";

import Modulo from "../admin/curso/institucion/list";
import Funcionalidad from "../admin/curso/institucion/list";
import Institucion from "../admin/curso/institucion/list";
import Asociados from "../admin/curso/asociados/list";
import Beneficios from "../admin/curso/beneficios/list";
import Certificacion from "../admin/curso/certificacion/list";
import Organizacion from "../admin/curso/organizacion/list";
import Productos from "../admin/curso/productos/list";
import Servicios from "../admin/curso/servicios/list";
import MiPerfil from "../admin/usuario/miPerfil";

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
                        <span className={open ? '': 'hidden'}>CURSO DE</span>
                        <h3>{ open ?  "COOPERATIVISMO" : ""}</h3>
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
            {ruta : 'admin/usuario', menu: 'Usuario', icono : 'person', componente : <Usuario /> },
            {ruta : 'admin/evaluacion', menu: 'Evaluación', icono : 'add_chart ', componente : <Evaluacion /> }
        ]
    }    
];

const menuComponente = [
    {id:11,componente : <Modulo />},
    {id:12,componente : <Funcionalidad />}     
];

export default function  Contenedor ({componente, users}) {
    const [esInvitado, setEsInvitado] = useState(true); 
    useEffect(() => {
        instance.get('/nameUser').then(res=>{
            setEsInvitado((res.esInvitado === 1) ? true : false);
        })
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

                            <Route exact = {`true`} path="/curso/institucion" element={<Institucion />}/>
                            <Route exact = {`true`} path="/curso/asociados" element={<Asociados />}/>
                            <Route exact = {`true`} path="/curso/organizacion" element={<Organizacion />}/>
                            <Route exact = {`true`} path="/curso/productos" element={<Productos />}/>
                            <Route exact = {`true`} path="/curso/servicios" element={<Servicios />}/>
                            <Route exact = {`true`} path="/curso/beneficios" element={<Beneficios />}/>
                            <Route exact = {`true`} path="/curso/certificacion" element={<Certificacion />}/>
                            <Route exact = {`true`} path="/admin/miPerfil" element={<MiPerfil />}/>

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

                    {(!esInvitado)? componenteMenu.map((res, i )=>{
                                return <ListMenu res={res} control={open} setControll={setOpen} j={i} key ={'list'+ i}/>
                                })
                    : null}

                    <ItemMenu route={'curso/institucion'} text={'Institución'} icon={'account_balance'} />
                    <ItemMenu route={'curso/asociados'} text={'Asociados'} icon={'people'} />
                    <ItemMenu route={'curso/organizacion'} text={'Organización'} icon={'business'} />
                    <ItemMenu route={'curso/productos'} text={'Productos'} icon={'category'} />
                    <ItemMenu route={'curso/servicios'} text={'Servicios'} icon={'settings_suggest'} />
                    <ItemMenu route={'curso/beneficios'} text={'Beneficios'} icon={'loyalty'} />
                    <ItemMenu route={'curso/certificacion'} text={'Certificación'} icon={'school'} />
                    <ItemMenu route={'admin/miPerfil'} text={'Mi perfil'} icon={'person'} />

                    <ItemMenu route={'logout'} text={'Salir'} icon={'exit_to_app'} />
                  
                </Drawer>

            </Router>
        </ThemeProvider>
    );
}
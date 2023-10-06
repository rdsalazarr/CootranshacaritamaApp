import React, {useState, useEffect} from 'react';
import reportesEstadisticos from "../../../images/home/reportesEstadisticos.svg";
import documentosOrganizados from "../../../images/home/documentosOrganizados.svg";
import software from "../../../images/home/software.svg";
import { Card, Grid, Box } from '@mui/material';
import {LoaderModal} from "../layout/loader";
import instance from '../layout/instance';

export default function Welcome(){

    const [loader, setLoader] = useState(false);
    const [data, setData] = useState([]);

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/welcome').then(res=>{
            setData(res.data);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box style={{width: '80%', margin: 'auto'}}>
            <Grid container spacing={4}>
                <Grid item xl={12} md={12} sm={12} xs={12} style={{textAlign: 'center'}}>
                    <h1>Hola, {data.nombreUsuario}</h1>
                    <p>Bienvenidos al CRM de <b>{data.siglaEmpresa}</b>, Vamos a entrar en materia.</p>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>                   
                    <img src={software} style={{width: '80%', height: '70%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} />
                    <p style={{textAlign: 'justify'}}>Ponemos a su disposición un conjunto de prácticas, estrategias comerciales y tecnologías centradas en mejorar la relación con sus clientes.</p>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <img src={documentosOrganizados} style={{width: '80%', height: '70%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} />
                    <p style={{textAlign: 'justify'}}>Organice todos los documentos producidos por su organización de manera ordenada, eficiente y puntual.</p>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                   <img src={reportesEstadisticos} style={{width: '80%', height: '70%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} />
                    <p style={{textAlign: 'justify'}}>Elabore sus informes de forma ágil y segura para tomar decisiones informadas y acertadas.</p>
                </Grid>

            </Grid>
        </Box>
    )
}
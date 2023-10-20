import React, {useState, useEffect} from 'react';
import software from "../../../images/home/software.svg";
import {LoaderModal} from "../layout/loader";
import { Grid, Box } from '@mui/material';
import instance from '../layout/instance';

export default function EnConstruccion(){

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
                    <p>Bienvenidos al CRM de <b>{data.siglaEmpresa}</b>, estamos trabajando para construir esta funcionalidad.</p>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}> 

                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>                   
                    <img src={software} style={{width: '80%', height: '70%', objectFit: 'cover', padding: '5px 5px 10px 10px'}} />
                    <p style={{textAlign: 'justify'}}>.</p>
                </Grid>

            </Grid>
        </Box>
    )
}
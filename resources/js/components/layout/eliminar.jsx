
import React, {useState} from 'react';
import DeleteForeverIcon from '@mui/icons-material/DeleteForever';
import {Box, Grid, Button, Avatar} from "@mui/material";
import DeleteIcon from '@mui/icons-material/Delete';
import ClearIcon from '@mui/icons-material/Clear';
import showSimpleSnackbar from './snackBar';
import {LoaderModal} from "./loader";
import instance from './instance';

export default function Eliminar({id, ruta, cerrarModal}){
    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);

    const continuar = () =>{
        setLoader(true);
        instance.post(ruta, {codigo: id}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid container spacing={2}>
            <Grid item xl={2} md={2} sm={2} xs={2}>
                <Box className='animate__animated animate__rotateIn'>
                    <Avatar style={{marginTop: '0.8em', width:'60px', height:'60px', backgroundColor: '#fdfdfd', border: 'solid 3px #d3cccc'}}> <DeleteForeverIcon  style={{fontSize: '2.5em', color: '#f33602'}}/> </Avatar>  
                </Box>
            </Grid>

            <Grid item xl={10} md={10} sm={10} xs={10}>
                <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'center'}}>
                    ¿Esta seguro que desea eliminar este registro?
                </p>
            </Grid>

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={cerrarModal} className='modalBtnRojo floatBtnRojo' disabled={(habilitado) ? false : true}
                    startIcon={<ClearIcon />}> Cancelar
                </Button>
            </Grid> 

            <Grid item xl={6} md={6} sm={6} xs={6}>
                <Button onClick={continuar} className='modalBtn' disabled={(habilitado) ? false : true}
                    startIcon={<DeleteIcon />}> Eliminar
                </Button>
            </Grid> 

        </Grid>
     )
}
import React, {useState, useEffect} from 'react';
import TablaGeneral from '../../layout/tablaGeneral';
import { ModalDefaultAuto } from '../../layout/modal';
import {LoaderModal} from "../../layout/loader";
import Eliminar from '../../layout/modalFijas';
import instance from '../../layout/instance';
import { Box} from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';

export default function Black(){

    
    const inicio = () =>{
       
        instance.post('/admin/procesos/automaticos/ejecutar', {'codigo' : 1}).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
         
        })
    }

    useEffect(()=>{inicio();}, []);

    return (
        <div>home Black</div>
    )
}
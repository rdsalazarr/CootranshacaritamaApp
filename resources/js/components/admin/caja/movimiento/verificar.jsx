import React, {useState, useEffect} from 'react';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import NoTieneCaja from "../abrir/noTieneCaja";
import AbrirCaja from "../abrir/abrirCaja";
import RegistrarMovimientos from "./list_old";
import { Box} from '@mui/material';

export default function Verificar(){

    const [data, setData] = useState([]);
    const [cajaId, setCajaId] = useState('');
    const [loader, setLoader] = useState(true);
    const [cajaNumero, setCajaNumero] = useState(''); 
    const [nombreUsuario, setNombreUsuario] = useState('');
    const [saldoAnterior, setSaldoAnterior] = useState('');

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/caja/procesar/movimiento').then(res=>{
            setNombreUsuario(res.nombreUsuario);
            setSaldoAnterior(res.saldoAnterior);
            setCajaNumero(res.cajaNumero);
            setCajaId(res.cajaId);
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            {(cajaId === null)? <NoTieneCaja usuario={nombreUsuario} /> : ((data === null) ? <AbrirCaja saldoAnterior={saldoAnterior} usuario={nombreUsuario} caja={cajaNumero} /> : <RegistrarMovimientos />)}     
        </Box>
    )
}
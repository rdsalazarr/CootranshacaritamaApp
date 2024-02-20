import React, {useState, useEffect} from 'react';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import {Grid, Box} from '@mui/material';

export default function EnviarCorreo({data}){
    const [contratoFirmado, setContratoFirmado] = useState((data.totalFirmas === data.totalFirmasRealizadas) ? true : false); 
    const [loader, setLoader] = useState(false);
    const [mensaje, setMensaje] = useState('');

    const inicio = () =>{
        setLoader(true); 
        instance.post('/admin/direccion/transporte/reenviar/correo/contrato/vehiculo', {contradoId: data.vehconid, personaId: data.persid}).then(res=>{
            (res.success) ? setMensaje(res.message) : null;
            setLoader(false);
        })
    }

    (!contratoFirmado) ? useEffect(()=>{inicio();}, []) : null;

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid container spacing={2}>
            {(contratoFirmado) ? 
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box style={{backgroundColor: '#E3AB16',  border: '1px solid rgb(131, 131, 131)', padding: '5px', color: '#fdfdfd',  borderRadius: '10px'}}>
                        Lamentablemente, no es posible enviar el correo al usuario, ya que el documento ha sido firmado por todas las personas involucradas
                    </Box>
                </Grid>
            :
                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <p style={{color: 'rgb(149 149 149)',  fontWeight: 'bold', fontSize: '1.2em', textAlign: 'justify'}}>
                        <span dangerouslySetInnerHTML={{__html: mensaje}} /> 
                    </p>
                </Grid>
            }
        </Grid>
    )
}
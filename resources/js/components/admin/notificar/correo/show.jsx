import React, {useState} from 'react';
import {Grid } from '@mui/material';

export default function Show({data, tipo}){

     const [formData, setFormData] = useState(
                    {
                        codigo: data.innocoid, nombre: data.innoconombre, titulo: data.innocotitulo, contenido: data.innococontenido,
                        piePagina: data.piePagina, copia: data.enviarCopia, tipo:tipo
                    } ); 

    return ( 
        <div>
            <Grid container spacing={2}>

                <Grid item xl={3} md={3} sm={12} xs={12}>
                    <div className='frmTexto'>
                        <label>Nombre</label>
                        <span>{formData.nombre}</span>
                    </div>
                </Grid>

                <Grid item xl={3} md={3} sm={12} xs={12}>
                    <div className='frmTexto'>
                        <label>Encabezado</label>
                        <span>{formData.titulo}</span>
                    </div>
                </Grid>

                <Grid item xl={2} md={2} sm={12} xs={12}>
                    <div className='frmTexto'>
                        <label>Pie página</label>
                        <span>{formData.piePagina}</span>
                    </div>
                </Grid>

                <Grid item xl={2} md={2} sm={12} xs={12}>
                    <div className='frmTexto'>
                        <label>Enviar copia</label>
                        <span>{formData.copia}</span>
                    </div>
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <div className='frmTexto'>
                        <label>Contenido</label>
                        <span dangerouslySetInnerHTML={{__html: formData.contenido}} /> 
                    </div>
                </Grid>
            </Grid>
        </div>
    )
}
import React, {useState, useEffect, Fragment} from 'react';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import {Grid, Box } from '@mui/material';

export default function Show({data}){
    const [formData, setFormData] = useState({codigo:data.tiquid,     tipoIdentificacion:'',          documento:'',          primerNombre:'',
                                             segundoNombre:'',        primerApellido:'',              segundoApellido:'',    direccion:'',
                                             correo:'',               telefonoCelular:'',             departamentoOrigen:'', municipioOrigen:'',
                                             departamentoDestino:'',  municipioDestino:'',            valorTiquete :'',      planilla:'',
                                             valorDescuento:'',       valorFondoReposicion:'',        valorTotal:'',         cantidadPuesto: '',
                                             valorTiqueteMostrar :'', valorFondoReposicionMostrar:'', valorTotalTiquete:''  });

  
    const [esEmpresa, setEsEmpresa] = useState(false);
    const [loader, setLoader] = useState(false);

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/despacho/tiquete/show/general', {codigo:formData.codigo}).then(res=>{
            let tiquete                             = res.tiquete;
            newFormData.personaId                   = tiquete.perserid;
            newFormData.tipoIdentificacion          = tiquete.tipoIdentificacion;
            newFormData.documento                   = tiquete.perserdocumento;
            newFormData.primerNombre                = tiquete.perserprimernombre;
            newFormData.segundoNombre               = (tiquete.persersegundonombre !== null) ? tiquete.persersegundonombre : '';
            newFormData.primerApellido              = (tiquete.perserprimerapellido !== null) ? tiquete.perserprimerapellido : '';
            newFormData.segundoApellido             = (tiquete.persersegundoapellido !== null) ? tiquete.persersegundoapellido : '';
            newFormData.direccion                   = tiquete.perserdireccion;
            newFormData.correo                      = (tiquete.persercorreoelectronico !== null) ? tiquete.persercorreoelectronico : '';
            newFormData.telefonoCelular             = tiquete.persernumerocelular;
            newFormData.departamentoDestino         = tiquete.deptoDestino;
            newFormData.municipioDestino            = tiquete.municipioDestino;
            newFormData.planilla                    = tiquete.nombreRuta;
            newFormData.valorTiquete                = tiquete.tiquvalortiquete;
            newFormData.valorDescuento              = formatearNumero(tiquete.tiquvalordescuento);
            newFormData.cantidadPuesto              = tiquete.tiqucantidad;
            newFormData.valorTiqueteMostrar         = formatearNumero(tiquete.tiquvalortiquete);
            newFormData.valorFondoReposicionMostrar = formatearNumero(tiquete.tiquvalorfondoreposicion);
            newFormData.valorTotalTiquete           = formatearNumero(tiquete.tiquvalortotal);
            setEsEmpresa((tiquete.tipideid === 5) ? true : false);
            setFormData(newFormData);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <Grid container spacing={2}>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Información del tiquete
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Planilla</label>
                        <span>{formData.planilla}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Municipio nodo destino</label>
                        <span>{formData.municipioDestino}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Cantidad de puestos</label>
                        <span>{formData.cantidadPuesto}</span>
                    </Box>
                </Grid>
            </Grid>

            <Grid container spacing={2}>
                <Grid item xl={9} md={9} sm={12} xs={12}>
                    <Grid container spacing={2}>

                    </Grid>
                </Grid>

                <Grid item xl={3} md={3} sm={12} xs={12} style={{marginTop:'1em'}}>
                    <Grid container spacing={2}>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Valor tiquete $</label>
                                <span className='textoRojo'>{'\u00A0'+ formData.valorTiqueteMostrar}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Valor descuento $</label>
                                <span className='textoRojo'>{'\u00A0'+formData.valorDescuento}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Fondo de reposición $ </label>
                                <span className='textoRojo'>{'\u00A0'+ formData.valorFondoReposicionMostrar}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Total $ </label>
                                <span className='textoRojo'> {'\u00A0'+ formData.valorTotalTiquete}</span>
                            </Box>
                        </Grid>
                    </Grid>
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Información de la persona
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Tipo de identificación</label>
                        <span>{formData.tipoIdentificacion}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>{(esEmpresa)? 'NIT' : 'Número de identificación'} </label>
                        <span>{formData.documento}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>{(esEmpresa)? 'Razón social' : 'Primer nombre'}</label>
                        <span>{formData.primerNombre}</span>
                    </Box>
                </Grid>

                {(!esEmpresa)?
                    <Fragment>
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Segundo nombre</label>
                                <span>{formData.segundoNombre}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                                <label>Primer apellido</label>
                                <span>{formData.primerApellido}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Segundo apellido</label>
                                <span>{formData.segundoApellido}</span>
                            </Box>
                        </Grid>
                    </Fragment>
                : null}

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Dirección</label>
                        <span>{formData.direccion}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Correo electrónico</label>
                        <span>{formData.correo}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Teléfono</label>
                        <span>{formData.telefonoCelular}</span>
                    </Box>
                </Grid>
                 

            </Grid>
        </Box>
    )
}
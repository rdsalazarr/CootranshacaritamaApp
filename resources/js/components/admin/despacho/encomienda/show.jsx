import React, {useState, useEffect, Fragment} from 'react';
import Trazabilidad from '../../../layout/trazabilidad';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import {Grid, Box } from '@mui/material';

export default function Show({data}){
    const [formData, setFormData] = useState({codigo:data.encoid,        tipoIdentificacionRemitente:'', documentoRemitente:'',        primerNombreRemitente:'',
                                              segundoNombreRemitente:'', primerApellidoRemitente:'',     segundoApellidoRemitente:'',  direccionRemitente:'',
                                              correoRemitente:'',        telefonoCelularRemitente:'',    tipoIdentificacionDestino:'', documentoDestino:'',
                                              primerNombreDestino:'',    segundoNombreDestino :'',       primerApellidoDestino:'',     segundoApellidoDestino:'',
                                              direccionDestino:'',       correoDestino:'',               telefonoCelularDestino:'',    departamentoOrigen:'',
                                              municipioOrigen:'',        departamentoDestino:'',         municipioDestino:'',          tipoEncomienda:'',
                                              cantidad:'',               valorDeclarado :'',             valorEnvio:'',                valorDomicilio:'',
                                              contenido:'',              observaciones: '',              ruta:'',                      valorSeguro:'',                 
                                              valorTotal:''});

    const [cambiosEstadoEncomienda, setCambiosEstadoEncomienda] = useState([]);
    const [esEmpresaRemitente, setEsEmpresaRemitente] = useState(false);
    const [esEmpresaDestino, setEsEmpresaDestino] = useState(false);
    const [loader, setLoader] = useState(false);    

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/despacho/encomienda/show/general', {codigo:formData.codigo}).then(res=>{
            setCambiosEstadoEncomienda(res.cambiosEstadoEncomienda);
            let encomienda                          = res.encomienda;
            newFormData.tipoIdentificacionRemitente = encomienda.tipoIdentificacionRemitente;
            newFormData.documentoRemitente          = encomienda.perserdocumento;
            newFormData.primerNombreRemitente       = encomienda.perserprimernombre;
            newFormData.segundoNombreRemitente      = encomienda.persersegundonombre;
            newFormData.primerApellidoRemitente     = encomienda.perserprimerapellido;
            newFormData.segundoApellidoRemitente    = encomienda.persersegundoapellido;
            newFormData.direccionRemitente          = encomienda.perserdireccion;
            newFormData.correoRemitente             = encomienda.persercorreoelectronico;
            newFormData.telefonoCelularRemitente    = encomienda.persernumerocelular;
            newFormData.tipoIdentificacionDestino   = encomienda.tipoIdentificacionDestino;
            newFormData.documentoDestino            = encomienda.perserdocumentoDestino;
            newFormData.primerNombreDestino         = encomienda.perserprimernombreDestino;
            newFormData.segundoNombreDestino        = encomienda.persersegundonombreDestino;
            newFormData.primerApellidoDestino       = encomienda.perserprimerapellidoDestino;
            newFormData.segundoApellidoDestino      = encomienda.persersegundoapellidoDestino;
            newFormData.direccionDestino            = encomienda.perserdireccionDestino;
            newFormData.correoDestino               = encomienda.persercorreoelectronicoDestino;
            newFormData.telefonoCelularDestino      = encomienda.persernumerocelularDestino;
            newFormData.departamentoOrigen          = encomienda.deptoOrigen;
            newFormData.municipioOrigen             = encomienda.municipioOrigen;
            newFormData.departamentoDestino         = encomienda.deptoDestino;
            newFormData.municipioDestino            = encomienda.municipioDestino;
            newFormData.tipoEncomienda              = encomienda.tipencnombre;
            newFormData.cantidad                    = encomienda.encocantidad;
            newFormData.valorDeclarado              = formatearNumero(encomienda.encovalordeclarado);
            newFormData.valorEnvio                  = formatearNumero(encomienda.encovalorenvio);
            newFormData.valorDomicilio              = formatearNumero(encomienda.encovalordomicilio);
            newFormData.contenido                   = encomienda.encocontenido;
            newFormData.observaciones               = encomienda.encoobservacion;
            newFormData.ruta                        = encomienda.nombreRuta;
            newFormData.valorSeguro                 = formatearNumero(encomienda.encovalorcomisionseguro);
            newFormData.valorTotal                  = formatearNumero(encomienda.encovalortotal); 
            setFormData(newFormData);        
            setEsEmpresaRemitente((encomienda.tipideid === 5) ? true : false);
            setEsEmpresaDestino((encomienda.tipideidDestino === 5) ? true : false);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Grid container spacing={2}>

            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Información de la encomienda
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Departamento origen</label>
                    <span>{formData.departamentoOrigen}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Municipio origen</label>
                    <span>{formData.municipioOrigen}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Departamento destino</label>
                    <span>{formData.departamentoDestino}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Municipio destino</label>
                    <span>{formData.municipioDestino}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Ruta</label>
                    <span>{formData.ruta}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tipo de encomienda</label>
                    <span>{formData.tipoEncomienda}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Cantidad</label>
                    <span>{formData.cantidad}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Valor declarado</label>
                    <span>$ {formData.valorDeclarado}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Valor envío</label>
                    <span>$ {formData.valorEnvio}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Valor domicilio</label>
                    <span>$ {formData.valorDomicilio}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Seguro</label>
                    <span>$ {formData.valorSeguro}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Total</label>
                    <span>$ {formData.valorTotal}</span>
                </Box>
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Contenido</label>
                    <span>{formData.contenido}</span>
                </Box>
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box className='frmTexto'>
                    <label>Observaciones</label>
                    <span>{formData.observaciones}</span>
                </Box>
            </Grid>

            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Información del remitente
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tipo de identificación</label>
                    <span>{formData.tipoIdentificacionRemitente}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>{(esEmpresaRemitente)? 'NIT' : 'Número de identificación'} </label>
                    <span>{formData.documentoRemitente}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>{(esEmpresaRemitente)? 'Razón social' : 'Primer nombre'}</label>
                    <span>{formData.primerNombreRemitente}</span>
                </Box>
            </Grid>

            {(!esEmpresaRemitente)?
                <Fragment>
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Segundo nombre</label>
                            <span>{formData.segundoNombreRemitente}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                       <Box className='frmTexto'>
                            <label>Primer apellido</label>
                            <span>{formData.primerApellidoRemitente}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Segundo apellido</label>
                            <span>{formData.segundoApellidoRemitente}</span>
                        </Box>
                    </Grid>
                </Fragment>
            : null}

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Dirección</label>
                    <span>{formData.direccionRemitente}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Correo electrónico</label>
                    <span>{formData.correoRemitente}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Teléfono</label>
                    <span>{formData.telefonoCelularRemitente}</span>
                </Box>
            </Grid>

            <Grid item md={12} xl={12} sm={12} xs={12}>
                <Box className='frmDivision'>
                    Información del destino
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Tipo de identificación</label>
                    <span>{formData.tipoIdentificacionDestino}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>{(esEmpresaDestino)? 'NIT' : 'Número de identificación'} </label>
                    <span>{formData.documentoDestino}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>{(esEmpresaDestino)? 'Razón social' : 'Primer nombre'}</label>
                    <span>{formData.primerNombreDestino}</span>
                </Box>
            </Grid>

            {(!esEmpresaDestino)?
                <Fragment>
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Segundo nombre</label>
                            <span>{formData.segundoNombreDestino}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                       <Box className='frmTexto'>
                            <label>Primer apellido</label>
                            <span>{formData.primerApellidoDestino}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                            <label>Segundo apellido</label>
                            <span>{formData.segundoApellidoDestino}</span>
                        </Box>
                    </Grid>
                </Fragment>
            : null}

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Dirección</label>
                    <span>{formData.direccionDestino}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Correo electrónico</label>
                    <span>{formData.correoDestino}</span>
                </Box>
            </Grid>

            <Grid item xl={3} md={3} sm={6} xs={12}>
                <Box className='frmTexto'>
                    <label>Teléfono</label>
                    <span>{formData.telefonoCelularDestino}</span>
                </Box>
            </Grid>

            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Trazabilidad mensaje='Cambio de estado de la encomienda' data={cambiosEstadoEncomienda} />
            </Grid>

        </Grid>
    )
}
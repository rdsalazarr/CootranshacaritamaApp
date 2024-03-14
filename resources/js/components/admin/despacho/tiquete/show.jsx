import React, {useState, useEffect, Fragment} from 'react';
import {FormatearNumero} from "../../../layout/general";
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

    const [claseDistribucionPuesto, setClaseDistribucionPuesto] = useState('distribucionPuestoGeneral' + 'Venta');
    const [dataPuestos, setDataPuestos] = useState([]);
    const [esEmpresa, setEsEmpresa] = useState(false);
    const [loader, setLoader] = useState(false);    

    const distribucionVehiculo = (distribucionVehiculo, puestosVendidos) => {
        let totalFilas = distribucionVehiculo[0].totalFilas;
        let dataFilas  = [];
        let idColumna  = 0;
        for (let i = 0; i < totalFilas; i++) {
            let dataColumnas = [];
            distribucionVehiculo.map((res, j)=>{
                if(parseInt(res.tivedifila) === i){
                    const contenido        = res.tivedipuesto;
                    const puestoDespachado = puestosVendidos.some(puesto => puesto.tiqpuenumeropuesto === contenido);
                    const puestoVendido    = (puestoDespachado) ? true : false;
                    const clase            = (contenido === 'C') ? 'conductor' : ((contenido === 'P') ? 'pasillo' : ((puestoVendido) ? 'asientoVendido' : 'asiento'));
                    const esCondutor       = clase === 'conductor';
                    dataColumnas.push({puestoVendido:puestoVendido,  puestoColumna: idColumna.toString(), contenido, clase, esCondutor });
                    idColumna ++;
                }
            });
            dataFilas.push(dataColumnas);
        }
       setDataPuestos(dataFilas);
       setClaseDistribucionPuesto(distribucionVehiculo[0].tipvehclasecss + 'Venta');
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
            newFormData.valorDescuento              = FormatearNumero({numero: tiquete.tiquvalordescuento});
            newFormData.cantidadPuesto              = tiquete.tiqucantidad;
            newFormData.valorTiqueteMostrar         = FormatearNumero({numero: tiquete.tiquvalortiquete});
            newFormData.valorFondoReposicionMostrar = FormatearNumero({numero: tiquete.tiquvalorfondoreposicion});
            newFormData.valorTotalTiquete           = FormatearNumero({numero: tiquete.tiquvalortotal});
            newFormData.valorSeguro                 = FormatearNumero({numero: tiquete.tartiqvalorseguro});

            setEsEmpresa((tiquete.tipideid === 5) ? true : false);
            setFormData(newFormData);
            distribucionVehiculo(res.distribucionVehiculo, res.tiquetePuestos);
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
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTextoColor'>
                        <label>Seguro: $</label>
                        <span className='textoRojo'>{'\u00A0'+ formData.valorSeguro}</span>
                    </Box>
                </Grid>
            </Grid>

            <Grid container spacing={2}>
                <Grid item xl={9} md={9} sm={12} xs={12}>
                    <Grid container spacing={2}>
                        <Grid item xl={12} md={12} sm={12} xs={12} style={{marginTop:'1em'}}>
                            {(dataPuestos.length > 0)?
                                <Box className={claseDistribucionPuesto} style={{padding: '2px'}}>
                                    <Box style={{ display: 'flex', justifyContent: 'space-between', padding: '2px' }}>
                                        {Object.keys(dataPuestos).map((listId) => (
                                            <Box key={listId}>
                                                {dataPuestos[listId].map((item) => {
                                                    return (
                                                        <Box key={item.puestoColumna}>
                                                            <Box className={item.clase}>
                                                                <p>{item.contenido}</p>
                                                            </Box>
                                                        </Box>
                                                    );
                                                })}
                                            </Box>
                                        ))}
                                    </Box>
                                </Box>
                            : null }
                        </Grid>

                    </Grid>
                </Grid>

                <Grid item xl={3} md={3} sm={12} xs={12} style={{marginTop:'1em'}}>
                    <Grid container spacing={2}>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Cantidad de puestos: </label>
                                <span className='textoRojo'>{'\u00A0'+ formData.cantidadPuesto}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Valor tiquete: $</label>
                                <span className='textoRojo'>{'\u00A0'+ formData.valorTiqueteMostrar}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Valor descuento: $</label>
                                <span className='textoRojo'>{'\u00A0'+formData.valorDescuento}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Fondo de reposición: $ </label>
                                <span className='textoRojo'>{'\u00A0'+ formData.valorFondoReposicionMostrar}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className='frmTextoColor'>
                                <label>Total: $ </label>
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
import React, {useState, useEffect, Fragment} from 'react';
import {Grid, Box, Link, Table, TableHead, TableBody, TableRow, TableCell, Avatar} from '@mui/material';
import CloudDownloadIcon from '@mui/icons-material/CloudDownload';
import VisibilityIcon from '@mui/icons-material/Visibility';
import { ModalDefaultAuto  } from '../../layout/modal';
import Trazabilidad from '../../layout/trazabilidad';
import {LoaderModal} from "../../layout/loader";
import ShowAnexo from '../vehiculos/showAnexo';
import instance from '../../layout/instance';

export default function Show({id, frm}){

    const [loader, setLoader] = useState(false);
    const [formData, setFormData] = useState(
                                    {documento:'', cargo: '', tipoIdentificacion: '', nombreTipoPersona:'', departamentoNacimiento:'', municipioNacimiento:'',
                                    departamentoExpedicion:'', municipioExpedicion:'', primerNombre:'', segundoNombre: '', primerApellido: '', 
                                    segundoApellido:'', fechaNacimiento:'',   direccion:'', correo:'', fechaExpedicion: '', telefonoFijo: '', numeroCelular:'', 
                                    genero:'',firma:'', foto:'', showFotografia:'', showFirmaPersona:'', estado: ''
                                    } );

    const [cambiosEstadoAsociado, setCambiosEstadoAsociado] = useState([]);
    const [cambiosEstadoConductor, setCambiosEstadoConductor] = useState([]);
    const [licenciasConducion, setLicenciasConducion] = useState([]);
    const [modal, setModal] = useState({open : false, extencion:'', ruta:''});

    const cerrarModal = () =>{
        setModal({open : false});
    }

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData};
        instance.post('/admin/show/persona', {codigo: id, frm: frm}).then(res=>{
            let persona                           = res.persona;
            newFormData.documento                 = persona.persdocumento;
            newFormData.cargo                     = persona.nombreCargo;
            newFormData.tipoIdentificacion        = persona.nombreTipoIdentificacion;
            newFormData.nombreTipoPersona         = persona.nombreTipoPersona;
            newFormData.departamentoNacimiento    = persona.nombreDeptoNacimiento;
            newFormData.municipioNacimiento       = persona.nombreMunicipioNacimiento;
            newFormData.departamentoExpedicion    = persona.nombreDeptoExpedicion;
            newFormData.municipioExpedicion       = persona.nombreMunicipioExpedicion;
            newFormData.primerNombre              = persona.persprimernombre;
            newFormData.segundoNombre             = persona.perssegundonombre;
            newFormData.primerApellido            = persona.persprimerapellido;
            newFormData.segundoApellido           = persona.perssegundoapellido;
            newFormData.fechaNacimiento           = persona.persfechanacimiento;
            newFormData.direccion                 = persona.persdireccion;
            newFormData.correo                    = persona.perscorreoelectronico;
            newFormData.fechaExpedicion           = persona.persfechadexpedicion;
            newFormData.telefonoFijo              = persona.persnumerotelefonofijo;
            newFormData.numeroCelular             = persona.persnumerocelular;
            newFormData.genero                    = persona.genero;
            newFormData.estado                    = persona.estado;
            newFormData.firma                     = persona.persrutafirma;
            newFormData.foto                      = persona.persrutafoto;
            newFormData.showFotografia            = persona.fotografia;
            newFormData.showFirmaPersona          = persona.firmaPersona;
            newFormData.tieneFirmaDigital         = persona.tieneFirmaDigital;
            newFormData.firmaDigital              = persona.firmaDigital;
            newFormData.rutaDescargaCrt           = persona.rutaCrt;
            newFormData.rutaDescargaPem           = persona.rutaPem;
            newFormData.totalCambioEstadoAsociado  = persona.totalCambioEstadoAsociado
            newFormData.totalCambioEstadoConductor = persona.totalCambioEstadoConductor

            if(frm == 'ASOCIADO'){
                newFormData.fechaIngresoAsociado  = persona.asocfechaingreso;
            }

            if(frm == 'CONDUCTOR'){
                newFormData.tipoConductor           = persona.tipconnombre;
                newFormData.agencia                 = persona.agennombre;
                newFormData.fechaIngresoConductor   = persona.condfechaingreso;
                setLicenciasConducion(res.licenciasConducion);
            }

            setFormData(newFormData);
            setCambiosEstadoAsociado(res.cambiosEstadoAsociado);
            setCambiosEstadoConductor(res.cambiosEstadoConductor);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return ( 
        <Box>
            <Grid container spacing={2}>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Tipo identificación</label>
                        <span>{formData.tipoIdentificacion}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Documento</label>
                        <span>{formData.documento}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Primer nombre</label>
                        <span>{formData.primerNombre}</span>
                    </Box>
                </Grid>

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

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Fecha nacimiento</label>
                        <span>{formData.fechaNacimiento}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Departamento de nacimiento</label>
                        <span>{formData.departamentoNacimiento}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                   <Box className='frmTexto'>
                        <label>Municipio de nacimiento</label>
                        <span>{formData.municipioNacimiento}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Fecha expedición</label>
                        <span>{formData.fechaExpedicion}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                   <Box className='frmTexto'>
                        <label>Departamento de expedición</label>
                        <span>{formData.departamentoExpedicion}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                   <Box className='frmTexto'>
                        <label>Municipio de expedición</label>
                        <span>{formData.municipioExpedicion}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Dirección</label>
                        <span>{formData.direccion}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                   <Box className='frmTexto'>
                        <label>Correo</label>
                        <span>{formData.correo}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Teléfono fijo</label>
                        <span>{formData.telefonoFijo}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                   <Box className='frmTexto'>
                        <label>Número de celular</label>
                        <span>{formData.numeroCelular}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Género</label>
                        <span>{formData.genero}</span>
                    </Box>
                </Grid>

                {(frm === 'PERSONA') ?
                    <Fragment>
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTexto'>
                                <label>Cargo laboral</label>
                                <span>{formData.cargo}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Tipo relación laboral</label>
                                <span>{formData.nombreTipoPersona}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Activo</label>
                                <span>{formData.estado}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>¿Tiene firma digital?</label>
                                <span>{formData.tieneFirmaDigital}</span>
                            </Box>
                        </Grid>
                    </Fragment>
                    : null}

                {(frm === 'ASOCIADO') ?
                    <Fragment>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Información de asociado
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Fecha ingreso como asociado</label>
                                <span>{formData.fechaIngresoAsociado}</span>
                            </Box>
                        </Grid>
                    </Fragment>
                : null}

                {(frm === 'CONDUCTOR') ?
                    <Fragment>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Información del conductor
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Fecha ingreso como condutor</label>
                                <span>{formData.fechaIngresoConductor}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Tipo de conductor</label>
                                <span>{formData.tipoConductor}</span>
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <Box className='frmTexto'>
                                <label>Agencia</label>
                                <span>{formData.agencia}</span>
                            </Box>
                        </Grid>

                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Información de la licencia del conducción
                            </Box>
                        </Grid>
                                
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                <Table className={'tableAdicional'}  sx={{width: '70%', margin:'auto'}}  >
                                    <TableHead>
                                        <TableRow>
                                            <TableCell>Tipo de categoria</TableCell>
                                            <TableCell>Número de licencia</TableCell>
                                            <TableCell>Fecha de expedición</TableCell>
                                            <TableCell>Fecha de vencimiento</TableCell>
                                            <TableCell>Adjunto</TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                    { licenciasConducion.map((historial, a) => {
                                        return(
                                            <TableRow key={'rowD-' +a}>
                                                <TableCell>
                                                    <p>{historial['ticalinombre']}</p>
                                                </TableCell>

                                                <TableCell>
                                                    <p>{historial['conlicnumero']}</p>
                                                </TableCell>

                                                <TableCell>
                                                    <p>{historial['conlicfechaexpedicion']}</p>
                                                </TableCell>

                                                <TableCell>
                                                    <p>{historial['conlicfechavencimiento']} {historial['conlicextension']}</p>
                                                </TableCell>

                                                <TableCell>
                                                    {(historial['conlicextension'] !== null) ?
                                                        <Avatar style={{backgroundColor: '#43ab33', cursor: 'pointer'}}>
                                                            <VisibilityIcon onClick={() => {setModal({open: true, extencion: historial['conlicextension'], ruta:historial['rutaAdjuntoLicencia'],  rutaEnfuscada:historial['conlicrutaarchivo']})}} />
                                                        </Avatar>
                                                    : null}
                                                </TableCell>

                                            </TableRow>
                                            );
                                        })
                                    }
                                    </TableBody>
                                </Table>
                            </Box>
                        </Grid>

                    </Fragment>
                : null}

                {(formData.foto !== null) ?
                    <Grid item md={3} xl={3} sm={12} xs={12}>
                        <Box className='frmTexto'>
                            <label>Fotografia</label>
                            <Box className='fotografia' style={{marginTop: '0.6em'}}>
                                <img src={formData.showFotografia} ></img>
                            </Box>
                        </Box>
                    </Grid>
                : null }

                {(formData.firma !== null) ?
                    <Grid item md={3} xl={3} sm={12} xs={12}>
                        <Box className='frmTexto'>
                            <label>Firma</label>
                            <Box className='firmaPersona' style={{marginTop: '0.6em'}}>
                                <img src={formData.showFirmaPersona} ></img>
                            </Box>
                        </Box>
                    </Grid>
                : null }

                {(formData.firmaDigital === 1) ?
                    <Fragment>
                        <Grid item md={2} xl={2} sm={6} xs={12}>
                           <Box className='frmTexto'>
                                <label>Descargar certificado crt</label>
                                <Link href={formData.rutaDescargaCrt} ><CloudDownloadIcon className={'iconoDownload'}/></Link>
                            </Box>
                        </Grid>

                        <Grid item md={2} xl={2} sm={6} xs={12}>
                           <Box className='frmTexto'>
                                <label>Descargar certificado pem</label>
                                <Link href={formData.rutaDescargaPem} ><CloudDownloadIcon className={'iconoDownload'}/></Link>
                            </Box>
                        </Grid>
                </Fragment>
                : null}

                {(formData.totalCambioEstadoAsociado > 0) ? 
                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Trazabilidad mensaje='Cambio de estado del asociado' data={cambiosEstadoAsociado}/>
                    </Grid>
                : null }

                {(formData.totalCambioEstadoConductor > 0) ? 
                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Trazabilidad mensaje='Cambio de estado del conductor' data={cambiosEstadoConductor}/>
                    </Grid>
                : null }

            </Grid>

            <ModalDefaultAuto
                title={'Visualizar adjunto'}
                content={<ShowAnexo extencion={modal.extencion} ruta={modal.ruta} rutaEnfuscada={modal.rutaEnfuscada} cerrarModal={cerrarModal} />}
                close={() =>{setModal({open : false})}}
                tam = {'smallFlot'}
                abrir ={modal.open}
            />
        </Box>
    )
}
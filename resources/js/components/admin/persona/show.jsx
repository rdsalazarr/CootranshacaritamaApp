import React, {useState, useEffect} from 'react';
import {LoaderModal} from "../../layout/loader";
import instance from '../../layout/instance';
import {Grid, Box } from '@mui/material';

export default function Show({id}){  
    const [loader, setLoader] = useState(false);
    const [formData, setFormData] = useState(
            {documento:'', cargo: '', tipoIdentificacion: '', tipoRelacionLaboral:'', departamentoNacimiento:'', municipioNacimiento:'',
            departamentoExpedicion:'', municipioExpedicion:'', primerNombre:'', segundoNombre: '', primerApellido: '', 
            segundoApellido:'', fechaNacimiento:'',   direccion:'', correo:'', fechaExpedicion: '', telefonoFijo: '', numeroCelular:'', 
            genero:'',firma:'', foto:'', showFotografia:'', showFirmaPersona:'', estado: ''
            } );

    const inicio = () =>{
        setLoader(true);
        let newFormData = {...formData};
        instance.post('/admin/show/persona', {codigo: id}).then(res=>{
            let persona                         = res.data;
            newFormData.documento               = persona.persdocumento;
            newFormData.cargo                   = persona.nombreCargo;
            newFormData.tipoIdentificacion      = persona.nombreTipoIdentificacion;
            newFormData.tipoRelacionLaboral     = persona.nombreTipoRelacionLaboral;   
            newFormData.departamentoNacimiento  = persona.nombreDeptoNacimiento;
            newFormData.municipioNacimiento     = persona.nombreMunicipioNacimiento;
            newFormData.departamentoExpedicion  = persona.nombreDeptoExpedicion;
            newFormData.municipioExpedicion     = persona.nombreMunicipioExpedicion;
            newFormData.primerNombre            = persona.persprimernombre;
            newFormData.segundoNombre           = persona.perssegundonombre;
            newFormData.primerApellido          = persona.persprimerapellido;
            newFormData.segundoApellido         = persona.perssegundoapellido;
            newFormData.fechaNacimiento         = persona.persfechanacimiento;  
            newFormData.direccion               = persona.persdireccion;
            newFormData.correo                  = persona.perscorreoelectronico;
            newFormData.fechaExpedicion         = persona.persfechadexpedicion;
            newFormData.telefonoFijo            = persona.persnumerotelefonofijo;
            newFormData.numeroCelular           = persona.persnumerocelular;
            newFormData.genero                  = persona.genero;   
            newFormData.estado                  = persona.estado;
            newFormData.firma                   = persona.persrutafirma;
            newFormData.foto                    = persona.persrutafoto;
            newFormData.showFotografia          = persona.fotografia;
            newFormData.showFirmaPersona        = persona.firmaPersona;          
            setFormData(newFormData);    
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

                <Grid item xl={3} md={3} sm={6} xs={12}>
                   <Box className='frmTexto'>
                        <label>Cargo laboral</label>
                        <span>{formData.cargo}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Tipo relación laboral</label>
                        <span>{formData.tipoRelacionLaboral}</span>
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <Box className='frmTexto'>
                        <label>Activo</label>
                        <span>{formData.estado}</span>
                    </Box>
                </Grid>

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

            </Grid>
        </Box>
    )
}
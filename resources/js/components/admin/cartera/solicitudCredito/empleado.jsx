import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, Stack, Box, MenuItem, Card} from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import person from "../../../../../images/person.png";
import {LoaderModal} from "../../../layout/loader";
import SearchIcon from '@mui/icons-material/Search';
import instance from '../../../layout/instance';
import Procesar from './procesar';

export default function Empleado(){
    const [formDataConsulta, setFormDataConsulta] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'', segundoApellido:'', fechaNacimiento:'',showFotografia:'',
                                                                direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado: null, personaId :'', vehiculoId:'', nombrePersona:''});    
    const [formData, setFormData] = useState({tipoIdentificacion:'1', documento:''});
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [lineasCreditos, setLineasCreditos] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const ocultarDatos = () =>{
        setDatosEncontrados(false);
    }

    const consultarPersona = () =>{
        setLoader(true);
        let newFormDataConsulta  = {...formDataConsulta};
        instance.post('/admin/cartera/consultar/datos/persona', formData).then(res=>{
            if(res.success) {
                let persona                            = res.persona;
                newFormDataConsulta.tipoIdentificacion = persona.nombreTipoIdentificacion;
                newFormDataConsulta.documento          = persona.persdocumento;
                newFormDataConsulta.primerNombre       = persona.persprimernombre;
                newFormDataConsulta.segundoNombre      = persona.perssegundonombre;
                newFormDataConsulta.primerApellido     = persona.persprimerapellido;
                newFormDataConsulta.segundoApellido    = persona.perssegundoapellido;
                newFormDataConsulta.fechaNacimiento    = persona.persfechanacimiento;
                newFormDataConsulta.direccion          = persona.persdireccion;
                newFormDataConsulta.correo             = (persona.perscorreoelectronico !== null) ? persona.perscorreoelectronico : 'No reportó como persona';
                newFormDataConsulta.telefonoFijo       = persona.persnumerotelefonofijo;
                newFormDataConsulta.numeroCelular      = persona.persnumerocelular;
                newFormDataConsulta.showFotografia     = (persona.persrutafoto !== null) ? persona.fotografia : person;
                newFormDataConsulta.nombrePersona      = persona.nombrePersona;
                setLineasCreditos(res.lineasCreditos);
                setFormDataConsulta(newFormDataConsulta);
                setDatosEncontrados(true);
            }else{
                showSimpleSnackbar(res.message, 'error');
            }
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/cartera/solicitud/credito/tipo/documento').then(res=>{
            setTipoIdentificaciones(res.tipoIdentificaciones); 
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }
    
    return (
        <ValidatorForm onSubmit={consultarPersona}>
            <Box className={'containerMedium'}>
                <Card className={'cardContainer'}>
                    <Grid container spacing={2}>
                        <Grid item xl={5} md={5} sm={6} xs={12}>
                            <SelectValidator
                                name={'tipoIdentificacion'}
                                value={formData.tipoIdentificacion}
                                label={'Tipo identificación'}
                                className={'inputGeneral'}
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Debe hacer una selección"]}
                                onChange={handleChange} 
                            >
                                <MenuItem value={""}>Seleccione</MenuItem>
                                {tipoIdentificaciones.map(res=>{
                                    return <MenuItem value={res.tipideid} key={res.tipideid} >{res.tipidenombre}</MenuItem>
                                })}
                            </SelectValidator>
                        </Grid>

                        <Grid item xl={5} md={5} sm={6} xs={12}>
                            <TextValidator 
                                name={'documento'}
                                value={formData.documento}
                                label={'Documento'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{autoComplete: 'off', maxLength: 15}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                            />
                        </Grid>

                        <Grid item xl={2} md={2} sm={6} xs={12}>
                            <Stack direction="row" spacing={2}>
                                <Button type={"submit"} className={'modalBtnBuscar'} 
                                    startIcon={<SearchIcon className='icono' />}> consultar
                                </Button>
                            </Stack>
                        </Grid>

                    </Grid>
                </Card>
            </Box>

            {(datosEncontrados) ?
                <Procesar data={formDataConsulta} lineasCreditos={lineasCreditos} ocultarDatos={ocultarDatos} />
            :null }

        </ValidatorForm>
    )
}
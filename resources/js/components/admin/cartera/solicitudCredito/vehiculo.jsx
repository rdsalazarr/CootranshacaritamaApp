import React, {useState, useEffect, Fragment} from 'react';
import { Grid, Icon, Box, Card, Autocomplete, createFilterOptions} from '@mui/material';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../layout/snackBar';
import person from "../../../../../images/person.png";
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Procesar from './procesar';

export default function Vehiculo(){

    const [formDataConsulta, setFormDataConsulta] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'', segundoApellido:'', fechaNacimiento:'',
                                                                direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:'', personaId :'', vehiculoId:'', nombrePersona:''});    
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [formData, setFormData] = useState({identificador:''});
    const [listaAsociados, setListaAsociados] = useState([]);
    const [lineasCreditos, setLineasCreditos] = useState([]);
    const [loader, setLoader] = useState(false);

    const ocultarDatos = () =>{
        setDatosEncontrados(false);
        setFormData([]);
    }

    const consultarVehiculo = () =>{
        let newFormDataConsulta        = {...formDataConsulta};
        let identificador              = formData.identificador;
        let array                      = identificador.split("-");
        newFormDataConsulta.personaId  = Number(array[0]);
        newFormDataConsulta.vehiculoId = Number(array[1]);

        setDatosEncontrados(false);
        if(formData.identificador === ''){
            showSimpleSnackbar("Debe seleccionar un asociado", 'error');
            return;
        }

        setLoader(true);
        instance.post('/admin/cartera/consultar/datos/asociado', {personaId: Number(array[0])}).then(res=>{
            if(res.success) {
                console.log(res.asociado);
                let asociado                             = res.asociado;
                newFormDataConsulta.tipoIdentificacion   = asociado.nombreTipoIdentificacion;
                newFormDataConsulta.documento            = asociado.persdocumento;
                newFormDataConsulta.primerNombre         = asociado.persprimernombre;
                newFormDataConsulta.segundoNombre        = asociado.perssegundonombre;
                newFormDataConsulta.primerApellido       = asociado.persprimerapellido;
                newFormDataConsulta.segundoApellido      = asociado.perssegundoapellido;
                newFormDataConsulta.fechaNacimiento      = asociado.persfechanacimiento;
                newFormDataConsulta.direccion            = asociado.persdireccion;
                newFormDataConsulta.correo               = (asociado.perscorreoelectronico !== null) ? asociado.perscorreoelectronico : 'No reportó como asociado';
                newFormDataConsulta.telefonoFijo         = asociado.persnumerotelefonofijo;
                newFormDataConsulta.numeroCelular        = asociado.persnumerocelular;
                newFormDataConsulta.fechaIngresoAsociado = asociado.asocfechaingreso;
                newFormDataConsulta.showFotografia       = (asociado.persrutafoto !== null) ? asociado.fotografia : person;
                newFormDataConsulta.nombrePersona        = asociado.nombrePersona;

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
        instance.get('/admin/cartera/solicitud/credito/datos').then(res=>{
            setListaAsociados(res.listaAsociados); 
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={consultarVehiculo}>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>
                            <Grid item xl={11} md={11} sm={10} xs={9}>
                                <Autocomplete
                                    id="vehiculo"
                                    style={{height: "26px", width: "100%"}}
                                    options={listaAsociados}
                                    getOptionLabel={(option) => option.nombrePersona} 
                                    value={listaAsociados.find(v => v.identificador === formData.identificador) || null}
                                    filterOptions={createFilterOptions({ limit:10 })}
                                    onChange={(event, newInputValue) => {
                                        if(newInputValue){
                                            setFormData({...formData, identificador: newInputValue.identificador})
                                        }
                                    }}
                                    renderInput={(params) =>
                                        <TextValidator {...params}
                                            label="Consultar asociado con el número interno del vehículo"
                                            className="inputGeneral"
                                            variant="standard"
                                            validators={["required"]}
                                            errorMessages="Campo obligatorio"
                                            value={formData.identificador}
                                            placeholder="Consulte el asociado con el número interno del vehículo aquí..." />}
                                />
                                <br />
                            </Grid>

                            <Grid item xl={1} md={1} sm={2} xs={3} sx={{position: 'relative'}}>
                                <Icon className={'iconLupa'} onClick={consultarVehiculo}>search</Icon>
                                <br />
                            </Grid>
                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            {(datosEncontrados) ?
                <Procesar data={formDataConsulta} lineasCreditos={lineasCreditos} ocultarDatos={ocultarDatos} />
            :null }

        </Fragment>
    )
}
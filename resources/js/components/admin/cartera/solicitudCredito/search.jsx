import React, {useState, useEffect} from 'react';
import {Button, Grid, Icon, Box, MenuItem, Stack, Typography, Card, Autocomplete, createFilterOptions} from '@mui/material';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import person from "../../../../../images/person.png";
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';
import Asociado from '../show/asociado';

ValidatorForm.addValidationRule('isTasaNominal', (value) => {
    // Verificar si el valor es un número válido en formato "10.50"
    const regex = /^\d+(\.\d{1,2})?$/;
    if (!regex.test(value)) {
      return false;
    }
  
    // Verificar si el número está en el rango de 0 a 100 (porcentaje válido)
    const numValue = parseFloat(value);
    return numValue >= 0 && numValue <= 100;
});

export default function Search(){

    const [formData, setFormData] = useState({identificador:'', asociadoId:'', vehiculoId:'', lineaCredito:'', destinoCredito:'', valorSolicitado:'',  tasaNominal:'',  plazo:'', observacionGeneral:'',
                                            tasaNominalLineaCredito: '', valorMinimoLineaCredito:'', valorMaximoLineaCredito:'', plazoMaximoLineaCredito:'', correo:'', nombreAsociado:'' })
    const [formDataConsulta, setFormDataConsulta] = useState({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'', segundoApellido:'', fechaNacimiento:'',
                                                                direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:''})
    const [loader, setLoader] = useState(false);
    const [datosEncontrados, setDatosEncontrados] = useState(false);
    const [listaAsociados, setListaAsociados] = useState([]);
    const [lineasCreditos, setLineasCreditos] = useState([]);   
    const [deshabilitado, setDeshabilitado] = useState(true); 
    const [modal, setModal] = useState({open: false});
    
    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const consultarVehiculo = () =>{
        let newFormData        = {...formData};
        let identificador      = formData.identificador;
        let array              = identificador.split("-");
        newFormData.asociadoId = Number(array[0]);
        newFormData.vehiculoId = Number(array[1]);       

        setDatosEncontrados(false);
        if(formData.identificador === ''){
            showSimpleSnackbar("Debe seleccionar un asociado", 'error');
            return;
        }

        setLoader(true);
        let newFormDataConsulta = {...formDataConsulta};
        instance.post('/admin/cartera/consultar/datos/asociado', {asociadoId: Number(array[0])}).then(res=>{
            if(res.success) {
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
                newFormDataConsulta.showFotografia       = (asociado.fotografia !== null) ? asociado.fotografia : person;

                newFormData.correo                       = asociado.perscorreoelectronico;
                newFormData.nombreAsociado               = asociado.nombrePersona;

                setLineasCreditos(res.lineasCreditos);
                setFormDataConsulta(newFormDataConsulta);
                setDatosEncontrados(true);
            }else{
                showSimpleSnackbar(res.message, 'error');
            }
            setLoader(false);
        })

        setFormData(newFormData);
    }

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }

    const registrarSolicitudCredito = () =>{
        if(formData.tasaNominal > formData.tasaNominalLineaCredito){
            showSimpleSnackbar("La tasa máxima permita es "+formData.tasaNominalLineaCredito , 'error');
            return;
        }
        if(Number(formData.valorSolicitado) < Number(formData.valorMinimoLineaCredito)){
            showSimpleSnackbar("El monto mínimo permito es "+formatearNumero(formData.valorMinimoLineaCredito), 'error');
            return;
        }
        if(Number(formData.valorSolicitado) > Number(formData.valorMaximoLineaCredito)){
            showSimpleSnackbar("El monto máximo permito es "+formatearNumero(formData.valorMaximoLineaCredito), 'error');
            return;
        }
        if(Number(formData.plazo) > Number(formData.plazoMaximoLineaCredito)){
            showSimpleSnackbar("El plazo máximo permito es "+formData.plazoMaximoLineaCredito+" meses", 'error');
            return;
        }
        setLoader(true);
        instance.post('/admin/cartera/registrar/solicitud/credito', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            if(res.success){
                setDatosEncontrados(false);
                setFormData({asociadoId:'', lineaCredito:'', destinoCredito:'', valorSolicitado:'',  tasaNominal:'',  plazo:'', observacionGeneral:'',
                            tasaNominalLineaCredito: '', valorMinimoLineaCredito:'', valorMaximoLineaCredito:'', plazoMaximoLineaCredito:''});
                setFormDataConsulta({tipoIdentificacion:'', documento:'', primerNombre:'', segundoNombre:'', primerApellido:'', segundoApellido:'', fechaNacimiento:'',
                                                                direccion:'', correo:'', telefonoFijo:'', numeroCelular:'', fechaIngresoAsociado:''});
            }
            setLoader(false);
        })
    }

    const cargarInformacionLinea = (e) =>{
        let newFormData           = {...formData}
        setDeshabilitado((e.target.value === '') ? true : false);
        if(e.target.value !== ''){
            const resultadosFiltrados = lineasCreditos.filter((lineaCredito) => lineaCredito.lincreid === e.target.value);
            newFormData.tasaNominalLineaCredito = resultadosFiltrados[0].lincretasanominal;
            newFormData.tasaNominal             = resultadosFiltrados[0].lincretasanominal;
            newFormData.valorMaximoLineaCredito = resultadosFiltrados[0].lincremontomaximo;
            newFormData.valorMinimoLineaCredito = resultadosFiltrados[0].lincremontominimo;
            newFormData.plazoMaximoLineaCredito = resultadosFiltrados[0].lincreplazomaximo;
        }else{
            newFormData.tasaNominal   = '';
        }
        newFormData.lineaCredito  = e.target.value;
        setFormData(newFormData);
    }

    const generarSimulacionCredito = () =>{
        if(formData.destinoCredito === ''){
            showSimpleSnackbar("Debe ingresar el destino del crédito", 'error');
            return;
        }
        if(formData.valorSolicitado === ''){
            showSimpleSnackbar("Debe ingresar el valor solicitado del crédito", 'error');
            return;
        }
        if(formData.tasaNominal === ''){
            showSimpleSnackbar("Debe ingresar la tasa nominal del crédito", 'error');
            return;
        }
        if(formData.plazo === ''){
            showSimpleSnackbar("Debe ingresar el plazo del crédito", 'error');
            return;
        }
        if(formData.tasaNominal > formData.tasaNominalLineaCredito){
            showSimpleSnackbar("La tasa máxima permita es "+formData.tasaNominalLineaCredito , 'error');
            return;
        }
        if(Number(formData.valorSolicitado) < Number(formData.valorMinimoLineaCredito)) {
            showSimpleSnackbar("El monto mínimo permito es "+formatearNumero(formData.valorMinimoLineaCredito), 'error');
            return;
        }
        if(Number(formData.valorSolicitado) > Number(formData.valorMaximoLineaCredito)) {
            showSimpleSnackbar("El monto máximo permito es "+formatearNumero(formData.valorMaximoLineaCredito), 'error');
            return;
        }
        if(Number(formData.plazo) > Number(formData.plazoMaximoLineaCredito)) {
            showSimpleSnackbar("El plazo máximo permito es "+formData.plazoMaximoLineaCredito+" meses", 'error');
            return;
        }

        setModal({open: true});
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
        <ValidatorForm onSubmit={registrarSolicitudCredito}>
            <Box><Typography component={'h2'} className={'titleGeneral'}>Solicitud de crédito</Typography>
            </Box>
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

            {(datosEncontrados) ?
                <Box style={{marginTop: '0.8em'}}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Asociado data={formDataConsulta} />
                            </Grid>

                            <Grid item md={12} xl={12} sm={12} xs={12}>
                                <Box className='frmDivision'>
                                    Información de la solicitud de crédito
                                </Box>
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <SelectValidator
                                    name={'lineaCredito'}
                                    value={formData.lineaCredito}
                                    label={'Línea de crédito'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    onChange={cargarInformacionLinea} 
                                >
                                    <MenuItem value={""}>Seleccione</MenuItem>
                                    {lineasCreditos.map(res=>{
                                        return <MenuItem value={res.lincreid} key={res.lincreid} >{res.lincrenombre}</MenuItem>
                                    })}
                                </SelectValidator>
                            </Grid>

                            <Grid item xl={6} md={6} sm={6} xs={12}>
                                <TextValidator 
                                    name={'destinoCredito'}
                                    value={formData.destinoCredito}
                                    label={'Destino del crédito'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 1000}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChange}
                                    disabled={deshabilitado}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator 
                                    name={'valorSolicitado'}
                                    value={formData.valorSolicitado}
                                    label={'Valor solicitado'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:99999999"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 99999999"]}
                                    onChange={handleChange}
                                    type={"number"}
                                    disabled={deshabilitado}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'tasaNominal'}
                                    value={formData.tasaNominal}
                                    label={'Tasa nominal'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required", 'isTasaNominal']}
                                    errorMessages={["Campo obligatorio", 'Ingrese un tasa nominal válida']}
                                    onChange={handleChange}
                                    disabled={deshabilitado}
                                />
                            </Grid> 

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator 
                                    name={'plazo'}
                                    value={formData.plazo}
                                    label={'Plazo (En meses)'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:99"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 99"]}
                                    onChange={handleChange}
                                    type={"number"}
                                    disabled={deshabilitado}
                                />
                            </Grid>

                            <Grid item xl={6} md={6} sm={6} xs={12}>
                                <TextValidator 
                                    name={'observacionGeneral'}
                                    value={formData.observacionGeneral}
                                    label={'Observación general'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 1000}}                        
                                    onChange={handleChange}
                                />
                            </Grid>

                        </Grid>

                        <Grid container direction="row"  justifyContent="right" style={{marginTop: '0.8em'}}>
                            <Stack direction="row" spacing={2} style={{marginRight: '3em'}}>
                                <Button type={"button"} className={'btnAdvertencia'} disabled={deshabilitado}
                                    startIcon={<PictureAsPdfIcon />} onClick={generarSimulacionCredito}> Simulación
                                </Button>
                            </Stack>

                            <Stack direction="row" spacing={2}>
                                <Button type={"submit"} className={'modalBtn'}
                                    startIcon={<SaveIcon />}> Registrar
                                </Button>
                            </Stack>
                        </Grid>

                    </Card>
                </Box>
            : null }

            <ModalDefaultAuto
                title={'Muestra el PDF de la simulación del crédito'}
                content={<VisualizarPdf data={formData} />}
                close  ={() =>{setModal({open : false})}}
                tam    ={'mediumFlot'}
                abrir  ={modal.open}
            />

        </ValidatorForm>
    )
}
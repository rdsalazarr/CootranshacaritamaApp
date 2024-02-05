import React, {useState} from 'react';
import {Button, Grid, Box, MenuItem, Stack} from '@mui/material';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';
import Persona from '../show/persona';

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

export default function Procesar({data, lineasCreditos, ocultarDatos}){

    const [formData, setFormData] = useState({personaId:data.personaId, vehiculoId:data.vehiculoId, lineaCredito:'', destinoCredito:'', valorSolicitado:'',  tasaNominal:'',  plazo:'', observacionGeneral:'',
                                            tasaNominalLineaCredito: '', valorMinimoLineaCredito:'', valorMaximoLineaCredito:'', plazoMaximoLineaCredito:'', correo:data.correo, nombrePersona:data.nombrePersona });
    const [formDataConsulta, setFormDataConsulta] = useState({tipoIdentificacion:data.tipoIdentificacion, documento:data.documento,             primerNombre:data.primerNombre,       segundoNombre:data.segundoNombre, 
                                                              primerApellido:data.primerApellido,         segundoApellido:data.segundoApellido, fechaNacimiento:data.fechaNacimiento, direccion:data.direccion, 
                                                              correo:data.correo,                         telefonoFijo:data.telefonoFijo,       numeroCelular:data.numeroCelular,     fechaIngresoAsociado:data.fechaIngresoAsociado,
                                                              showFotografia:data.showFotografia});
    const [deshabilitado, setDeshabilitado] = useState(true); 
    const [modal, setModal] = useState({open: false});
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
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
                ocultarDatos();
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

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={registrarSolicitudCredito}>

            <Grid container spacing={2}>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Persona data={formDataConsulta} />
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
                        multiline
                        maxRows={3}
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
                    <NumberValidator fullWidth
                        id={"valorSolicitado"}
                        name={"valorSolicitado"}
                        label={"Valor solicitado"}
                        value={formData.valorSolicitado}
                        type={'numeric'}
                        require={['required', 'maxStringLength:8']}
                        error={['Campo obligatorio','Número máximo permitido es el 99999999']}
                        onChange={handleChange}
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
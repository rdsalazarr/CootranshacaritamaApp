import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Tab, Tabs } from '@mui/material';
import NumberValidator from '../../layout/numberValidator';
import showSimpleSnackbar from '../../layout/snackBar';
import { TabPanel } from '../../layout/general';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';

export default function New({data}){
 
    const [formData, setFormData] = useState(
                    {
                        codigo: data.emprid, jefe: data.persidrepresentantelegal, departamento: data.emprdepaid, municipio: data.emprmuniid, 
                        nit: data.emprnit, digitoVerificacion: data.emprdigitoverificacion,  nombre: data.emprnombre,  sigla: data.emprsigla,
                        lema: (data.emprlema !== null ) ? data.emprlema : '',  direccion: data.emprdireccion, correo: (data.emprcorreo !== null ) ? data.emprcorreo : '',
                        barrio: (data.emprbarrio !== null ) ? data.emprbarrio : '',telefono: (data.emprtelefonofijo !== null ) ? data.emprtelefonofijo : '',
                        celular: (data.emprtelefonocelular !== null ) ? data.emprtelefonocelular : '', personeriaJuridica : (data.emprpersoneriajuridica !== null ) ? data.emprpersoneriajuridica : '',
                        horarioAtencion: (data.emprhorarioatencion !== null ) ? data.emprhorarioatencion : '',
                        url: data.emprurl, codigoPostal: data.emprcodigopostal,  
                        logo_old: data.emprlogo , imagen: (data.emprlogo !== null ) ? data.imagen : '' , logo: '',
                        valorMinimoEnvio: '', valorMinimoDeclarado:'', porcentajeSeguro:'', porcentajeComisionEmpresa:'', porcentajeComisionAgencia:'', 
                        porcentajeComisionVehiculo:''
                    });  

    const logo = formData.imagen; 
    const [value, setValue] = useState(0); 
    const [jefes, setJefes] = useState([]);
    const [logoEmpresa, setLogo] = useState();
    const [loader, setLoader] = useState(true); 
    const [habilitado, setHabilitado] = useState(true);
    const [municipios, setMunicipios] = useState([]);
    const [newMunicipios, setNewMunicipios] = useState([]);
    const [departamentos, setDepartamentos] = useState([]);  
    const [variantTab, setVariantTab] = useState((window.innerWidth <= 768) ? 'scrollable' : 'fullWidth');
    const [formDataMensajeimpresion, setFormDataMensajeimpresion] = useState([{codigo: '', nombre: '', valor: ''}]);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeMensajeimpresion = (e, index) =>{
        let newFormDataMensajeimpresion = [...formDataMensajeimpresion];
        newFormDataMensajeimpresion[index][e.target.name] = e.target.value; 
        setFormDataMensajeimpresion(newFormDataMensajeimpresion);
    }

    const handleChangeTab = (event, newValue) => {
        setValue(newValue);
    }

    const handleSubmit = () =>{
        let dataFile = new FormData();
        Object.keys(formData).forEach(function(key) {
           dataFile.append(key, formData[key])
        })
        dataFile.append('logo', (logoEmpresa !== undefined) ? logoEmpresa : '');

        let totalCampoMensaje = 0;
        Object.keys(formDataMensajeimpresion).forEach(function(key) {
            dataFile.append('mensajeImpresionCodigo'+key, formDataMensajeimpresion[key].codigo);
            dataFile.append('mensajeImpresionNombre'+key, formDataMensajeimpresion[key].nombre);
            dataFile.append('mensajeImpresionValor'+key, formDataMensajeimpresion[key].valor);
            totalCampoMensaje ++;
        })
        dataFile.append('totalCampoMensaje', totalCampoMensaje);

        setLoader(true);
        instance.post('/admin/empresa/salve', dataFile).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }    
    const consultarMunicipio = () =>{
        let depto_id =  formData.departamento;
        let mun      = [];
        setLoader(true);
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === depto_id){
                mun.push({
                    muniid: muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setNewMunicipios(mun);
        setLoader(false);
    }

    useEffect(()=>{
        let newFormData = {...formData}
        instance.get('/admin/empresa/list/datos').then(res=>{
            let configuracionEncomienda             = res.configuracionEncomienda;
            newFormData.valorMinimoEnvio            = configuracionEncomienda.conencvalorminimoenvio;
            newFormData.valorMinimoDeclarado        = configuracionEncomienda.conencvalorminimodeclarado;            
            newFormData.porcentajeSeguro            = configuracionEncomienda.conencporcentajeseguro;
            newFormData.porcentajeComisionEmpresa   = configuracionEncomienda.conencporcencomisionempresa;
            newFormData.porcentajeComisionAgencia   = configuracionEncomienda.conencporcencomisionagencia;
            newFormData.porcentajeComisionVehiculo  = configuracionEncomienda.conencporcencomisionvehiculo;
 
            let dataMsj = [];
            res.mensajeImpresiones.forEach(function(msj){
                dataMsj.push({
                    codigo: msj.menimpid,
                    nombre: msj.menimpnombre,
                    valor:  msj.menimpvalor
                });
            })
 
            let depto_id =  formData.departamento;
            let mun      = [];
            res.municipios.forEach(function(muni){
                if(muni.munidepaid === depto_id){
                    mun.push({
                        muniid: muni.muniid,
                        muninombre: muni.muninombre
                    });
                }
            });

            setJefes(res.jefes);
            setNewMunicipios(mun);
            setFormData(newFormData);
            setDepartamentos(res.deptos);
            setMunicipios(res.municipios);
            setFormDataMensajeimpresion(dataMsj);
            setLoader(false);
        })
    }, []); 

    if(loader){
        return <LoaderModal />
    }
 
    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'nit'}
                        value={formData.nit}
                        label={'NIT'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 12}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <TextValidator 
                        name={'digitoVerificacion'}
                        value={formData.digitoVerificacion}
                        label={'Dígito de verificación'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required","maxNumber:9"]}
                        errorMessages={["Campo obligatorio","Número máximo permitido es el 9"]}
                        onChange={handleChange}
                        type={"number"}
                    />
                </Grid>

                <Grid item xl={7} md={7} sm={12} xs={12}>
                    <TextValidator 
                        name={'nombre'}
                        value={formData.nombre}
                        label={'Nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <TextValidator 
                        name={'sigla'}
                        value={formData.sigla}
                        label={'Sigla'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <TextValidator 
                        name={'codigoPostal'}
                        value={formData.codigoPostal}
                        label={'Código postal'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        onChange={handleChange}
                        type={"number"}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'lema'}
                        value={formData.lema}
                        label={'Lema'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'direccion'}
                        value={formData.direccion}
                        label={'Dirección'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <TextValidator 
                        name={'barrio'}
                        value={formData.barrio}
                        label={'Barrio'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <TextValidator 
                        name={'telefono'}
                        value={formData.telefono}
                        label={'Teléfono'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <TextValidator 
                        name={'celular'}
                        value={formData.celular}
                        label={'Celular'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'correo'}
                        value={formData.correo}
                        label={'Correo'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={['required', 'isEmail']}
                        errorMessages={['Campo requerido', 'Correo no válido']}
                        onChange={handleChange}
                        type={"email"}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator
                        name={'personeriaJuridica'}
                        value={formData.personeriaJuridica}
                        label={'Personería jurídica'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        validators={['required']}
                        errorMessages={['Campo requerido']}
                        onChange={handleChange}
                    />
                </Grid>                

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'url'}
                        value={formData.url}
                        label={'URL'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={['required', 'matchRegexp:^(https?:\/\/)?(www\.)?[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}(\/\S*)?$']}
                        errorMessages={["Campo obligatorio", "La URL que ingresó no contiene una estructura válida"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'horarioAtencion'}
                        value={formData.horarioAtencion}
                        label={'Horario atención'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>
                
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'departamento'}
                        value={formData.departamento}
                        label={'Departamento'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        onBlur={consultarMunicipio} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {departamentos.map(res=>{
                           return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'municipio'}
                        value={formData.municipio}
                        label={'Municipio'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione </MenuItem>
                        {newMunicipios.map(res=>{
                           return <MenuItem value={res.muniid} key={res.muniid} >{res.muninombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'jefe'}
                        value={formData.jefe}
                        label={'Representante legal'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione </MenuItem>
                        {jefes.map(res=>{
                           return <MenuItem value={res.persid} key={res.persid} >{res.nombres} {res.apellidos}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        fullWidth
                        name={'logo'}
                        label={'Logo de la empresa'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', accept: "image/png"}}
                        onChange={(e)=>{ setLogo(e.target.files[0])}}
                        type={"file"}
                        InputLabelProps={{shrink :true}}
                    />
                </Grid>

                {(formData.emprlogo !== null) ?
                    <Grid item xl={2} md={2} sm={6} xs={12}>
                        <Box className='fotografia'>
                            <img src={logo} style={{width: '100%'}} ></img>
                        </Box>
                    </Grid>
                : null }

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Tabs value={value} onChange={handleChangeTab} 
                        sx={{background: '#e2e2e2'}}
                        indicatorColor="secondary"
                        textColor="secondary"
                        variant={variantTab} >
                        <Tab label="Mensajes de impresión" />
                        <Tab label="Configuracion  de encomiendas" />
                    </Tabs>

                    <TabPanel value={value} index={0}>
                        <Grid container spacing={2}>
                            { formDataMensajeimpresion.map((msj, i) => { 
                                return(
                                    <Fragment key={'rowI-' +i}>
                                        <Grid item xl={3} md={3} sm={4} xs={12}>
                                            <Box className='frmTexto'>
                                                <label>Nombre</label>
                                                <span>{msj['nombre']}</span>
                                            </Box>
                                        </Grid>

                                        <Grid item xl={9} md={9} sm={8} xs={12}>
                                            <TextValidator 
                                                name={'valor'}
                                                value={msj['valor']}
                                                label={'Valor'}
                                                className={'inputGeneral'} 
                                                variant={"standard"} 
                                                inputProps={{autoComplete: 'off', maxLength: 500}}
                                                onChange={(e) => {handleChangeMensajeimpresion(e, i)}} 
                                            />
                                        </Grid>
                                        
                                    </Fragment>
                                    )
                                })
                            }
                        </Grid>
                    </TabPanel>

                    <TabPanel value={value} index={1}>
                        <Grid container spacing={2}>
                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <NumberValidator fullWidth
                                    id={"valorMinimoEnvio"}
                                    name={"valorMinimoEnvio"}
                                    label={"Valor mínimo envío"}
                                    value={formData.valorMinimoEnvio}
                                    type={'numeric'}
                                    require={['required', 'maxStringLength:8']}
                                    error={['Campo obligatorio','Número máximo permitido es el 99999999']}
                                    onChange={handleChange}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <NumberValidator fullWidth
                                    id={"valorMinimoDeclarado"}
                                    name={"valorMinimoDeclarado"}
                                    label={"Valor mínimo declarado"}
                                    value={formData.valorMinimoDeclarado}
                                    type={'numeric'}
                                    require={['required', 'maxStringLength:8']}
                                    error={['Campo obligatorio','Número máximo permitido es el 99999999']}
                                    onChange={handleChange}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator 
                                    name={'porcentajeSeguro'}
                                    value={formData.porcentajeSeguro}
                                    label={'Porcentaje seguro'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:99"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 99"]}
                                    onChange={handleChange}
                                    type={"number"}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator 
                                    name={'porcentajeComisionEmpresa'}
                                    value={formData.porcentajeComisionEmpresa}
                                    label={'Porcentaje comisión empresa'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:99"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 99"]}
                                    onChange={handleChange}
                                    type={"number"}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator 
                                    name={'porcentajeComisionAgencia'}
                                    value={formData.porcentajeComisionAgencia}
                                    label={'Porcentaje comisión agencia'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:99"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 99"]}
                                    onChange={handleChange}
                                    type={"number"}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator 
                                    name={'porcentajeComisionVehiculo'}
                                    value={formData.porcentajeComisionVehiculo}
                                    label={'Porcentaje comisión vehículo'}
                                    className={'inputGeneral'}
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required","maxNumber:99"]}
                                    errorMessages={["campo obligatorio","Número máximo permitido es el 99"]}
                                    onChange={handleChange}
                                    type={"number"}
                                />
                            </Grid>

                        </Grid>
                    </TabPanel>
                </Grid>

            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {'Actualizar'}
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
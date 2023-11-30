import React, {useState, useEffect, Fragment} from 'react';
import {TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, MenuItem, Stack, Box} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';

export default function New({data, tipo}){
    let tiquid        = (tipo === 'U') ? data.tiquid : '000';
    const [formData, setFormData] = useState({codigo:tiquid,             tipoIdentificacion:'', documento:'',        primerNombre:'',
                                              segundoNombre:'', primerApellido:'',     segundoApellido:'',  direccion:'',
                                              correo:'',        telefonoCelular:'',    tipoIdentificacionDestino:'', documentoDestino:'',
                                              departamentoOrigen:'',
                                              municipioOrigen:'',        departamentoDestino:'',         municipioDestino:'',          
                                              cantidad:'',               valorDeclarado :'',             valorEnvio:'',                valorDomicilio:'',
                                               personaId:'000',    
                                              ruta:'',                   valorSeguro:'',                 valorTotal:'',                tipo:tipo});

    const [configuracionEncomienda, setConfiguracionEncomienda] = useState([]);
    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [esEmpresa, setEsEmpresa] = useState(false);
    const [municipiosDestino, setMunicipiosDestino] = useState([]);
    const [tiposEncomiendas, setTiposEncomiendas] = useState([]);
    const [municipiosOrigen, setMunicipiosOrigen] = useState([]);
    const [planillaRutas, setPlanillaRutas] = useState([]);
    const [idEncomienda , setIdEncomienda] = useState(0); 
    const [abrirModal, setAbrirModal] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [municipios, setMunicipios] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    }

    const handleSubmit = () =>{
        console.log("listo para enviar");
        setLoader(true);
        instance.post('/admin/despacho/tiquete/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            if(formData.tipo === 'I' && res.success){
                setFormData({codigo:encoid,             tipoIdentificacion:'', documento:'',        primerNombre:'',
                            segundoNombre:'', primerApellido:'',     segundoApellido:'',  direccion:'',
                            correo:'',        telefonoCelular:'',    tipoIdentificacionDestino:'', documentoDestino:'',
                            primerNombreDestino:'',    segundoNombreDestino :'',       primerApellidoDestino:'',     segundoApellidoDestino:'',
                            direccionDestino:'',       correoDestino:'',               telefonoCelularDestino:'',    departamentoOrigen:'',
                            municipioOrigen:'',        departamentoDestino:'',         municipioDestino:'',          tipoEncomienda:'',
                            cantidad:'',               valorDeclarado :'',             valorEnvio:'',                valorDomicilio:'',
                            contenido:'',              observaciones: observaciones,   personaId:'000',     personaIdDestino:'000',
                            ruta:'',                   valorSeguro:'',                 valorTotal:'',                tipo:tipo });

                setIdEncomienda(res.tiqueteId);
                setAbrirModal(true)
            }
            setLoader(false);
        })
    }

    const consultarPersona = (e) =>{
        let newFormData                         = {...formData}
        let tpIdentificacion                    = (e.target.name === 'tipoIdentificacion' ) ? e.target.value : formData.tipoIdentificacion;
        let documento                           = (e.target.name === 'documento' ) ? e.target.value : formData.documento ;
        newFormData.tipoIdentificacion = tpIdentificacion;
        newFormData.documento          = documento;
       if (tpIdentificacion !=='' && documento !== ''){
            setLoader(true);
            instance.post('/admin/despacho/tiquete/consultar/datos/persona', {tipoIdentificacion:tpIdentificacion, documento: documento}).then(res=>{
                if(res.success){
                    let personaservicio                  = res.data;
                    newFormData.personaId       = personaservicio.perserid;
                    newFormData.primerNombre    = personaservicio.perserprimernombre;
                    newFormData.segundoNombre   = (personaservicio.persersegundonombre !== undefined) ? personaservicio.persersegundonombre : '';
                    newFormData.primerApellido  = (personaservicio.perserprimerapellido !== undefined) ? personaservicio.perserprimerapellido : '';
                    newFormData.segundoApellido = (personaservicio.persersegundoapellido !== undefined) ? personaservicio.persersegundoapellido : '';
                    newFormData.direccion       = (personaservicio.perserdireccion !== undefined) ? personaservicio.perserdireccion : '';
                    newFormData.correo          = (personaservicio.persercorreoelectronico !== undefined) ? personaservicio.persercorreoelectronico : '';
                    newFormData.telefonoCelular = (personaservicio.persernumerocelular !== undefined) ? personaservicio.persernumerocelular : '';
                }else{
                    newFormData.personaId       = '000';
                    newFormData.primerNombre    = '';
                    newFormData.segundoNombre   = '';
                    newFormData.primerApellido  = '';
                    newFormData.segundoApellido = '';
                    newFormData.direccion       = '';
                    newFormData.correo          = '';
                    newFormData.telefonoCelular = '';
                }
                setLoader(false); 
            })
        }
        setEsEmpresa((tpIdentificacion === 5) ? true : false);
        setFormData(newFormData);
    }

    const consultarMunicipios = (e) =>{
        let newFormData              = {...formData}
        const planillaRutasFiltradas = planillaRutas.filter(planilla => planilla.plarutid === e.target.value);
        let depaIdOrigen             = planillaRutasFiltradas[0].depaidorigen;
        let depaIdDestino            = planillaRutasFiltradas[0].depaiddestino;
        let muniIdOrigen             = planillaRutasFiltradas[0].muniidorigen;
        let muniIdDestino            = planillaRutasFiltradas[0].muniiddestino;

        let municipiosOrigen = []; 
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === depaIdOrigen){
                municipiosOrigen.push({
                    muniid:     muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });

        let municipiosDestino = [];
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === depaIdDestino){
                municipiosDestino.push({
                    muniid:     muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });

        newFormData.ruta                = e.target.value;
        newFormData.municipioOrigen     = muniIdOrigen;
        newFormData.municipioDestino    = muniIdDestino;
        newFormData.departamentoOrigen  = depaIdOrigen;
        newFormData.departamentoDestino = depaIdDestino;
        setFormData(newFormData);
        setMunicipiosOrigen(municipiosOrigen);
        setMunicipiosDestino(municipiosDestino);
    }

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }

    const calcularValorEncomienda = (e) =>{
        let newFormData            = {...formData}
        let valorDeclarado         = (e.target.name === 'valorDeclarado' ) ? e.target.value : formData.valorDeclarado;
        let valorEnvio             = (e.target.name === 'valorEnvio' ) ? e.target.value : formData.valorEnvio ;
        let valorDomicilio         = (e.target.name === 'valorDomicilio' ) ? e.target.value : formData.valorDomicilio;
        let valorSeguro            = (valorDeclarado * configuracionEncomienda.conencporcentajeseguro) / 100;
        let valorTotal             = Number(valorEnvio) + Number(valorDomicilio) + Number(valorSeguro);
        newFormData.valorDeclarado = valorDeclarado;
        newFormData.valorEnvio     = valorEnvio;
        newFormData.valorDomicilio = valorDomicilio;
        newFormData.valorSeguro    = formatearNumero(valorSeguro);
        newFormData.valorTotal     = formatearNumero(valorTotal);
        setFormData(newFormData);
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/despacho/tiquete/listar/datos', {tipo:tipo, codigo:formData.codigo}).then(res=>{
            let valorEnvio             =  res.configuracionEncomienda.conencvalorminimoenvio
            let valorDeclarado         = res.configuracionEncomienda.conencvalorminimodeclarado;
            let valorSeguro            = (valorDeclarado * res.configuracionEncomienda.conencporcentajeseguro) / 100;
            let valorTotal             = Number(valorEnvio) + Number(valorSeguro);
            newFormData.valorDeclarado = valorDeclarado;
            newFormData.valorEnvio     = valorEnvio;
            newFormData.valorSeguro    = formatearNumero(valorSeguro);
            newFormData.valorTotal     = formatearNumero(valorTotal);

            setConfiguracionEncomienda(res.configuracionEncomienda);
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setTiposEncomiendas(res.tiposEncomiendas);
            setPlanillaRutas(res.planillaRutas);
            setMunicipios(res.municipios);

            if(tipo === 'U'){
                let tiquete                     = res.tiquete;
                newFormData.personaId           = tiquete.perserid;            
                newFormData.tipoIdentificacion  = tiquete.tipideid;
                newFormData.documento           = tiquete.perserdocumento;
                newFormData.primerNombre        = tiquete.perserprimernombre;
                newFormData.segundoNombre       = (tiquete.persersegundonombre !== null) ? tiquete.persersegundonombre : '';
                newFormData.primerApellido      = (tiquete.perserprimerapellido !== null) ? tiquete.perserprimerapellido : '';
                newFormData.segundoApellido     = (tiquete.persersegundoapellido !== null) ? tiquete.persersegundoapellido : '';
                newFormData.direccion           = tiquete.perserdireccion;
                newFormData.correo              = (tiquete.persercorreoelectronico !== null) ? tiquete.persercorreoelectronico : '';
                newFormData.telefonoCelular     = tiquete.persernumerocelular;       
                newFormData.departamentoOrigen  = tiquete.depaidorigen;
                newFormData.municipioOrigen     = tiquete.muniidorigen;
                newFormData.departamentoDestino = tiquete.depaiddestino;
                newFormData.municipioDestino    = tiquete.muniiddestino;  
                newFormData.ruta                = tiquete.plarutid;
                newFormData.valorTiquete        = tiquete.tiquvalortiquete;  
                newFormData.valorDescuento      = tiquete.tiquvalordescuento;                 
                newFormData.valorSeguro         = formatearNumero(tiquete.encovalorcomisionseguro);
                newFormData.valorTotal                  = formatearNumero(tiquete.encovalortotal);

                let municipiosOrigen = [];
                let deptoOrigen      = tiquete.depaidorigen;
                res.municipios.forEach(function(muni){ 
                    if(muni.munidepaid === deptoOrigen){
                        municipiosOrigen.push({
                            muniid:     muni.muniid,
                            muninombre: muni.muninombre
                        });
                    }
                });
                setMunicipiosOrigen(municipiosOrigen);

                let municipiosDestino = [];
                let deptoDestino      = tiquete.depaiddestino;
                res.municipios.forEach(function(muni){ 
                    if(muni.munidepaid === deptoDestino){
                        municipiosDestino.push({
                            muniid:     muni.muniid,
                            muninombre: muni.muninombre
                        });
                    }
                });
                setMunicipiosDestino(municipiosDestino);
                setEsEmpresa((tiquete.tipideid === 5) ? true : false);
                setEsEmpresaDestino((tiquete.tipideidDestino === 5) ? true : false);
            }

            setFormData(newFormData);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <ValidatorForm onSubmit={handleSubmit}>
                <Grid container spacing={2}>

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='frmDivision'>
                            Información del tiquete
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'ruta'}
                            value={formData.ruta}
                            label={'Ruta'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={consultarMunicipios}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {planillaRutas.map(res=>{
                                return <MenuItem value={res.plarutid} key={res.plarutid} >{res.nombreRuta}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>


                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'municipioOrigen'}
                            value={formData.municipioOrigen}
                            label={'Municipio origen'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipiosOrigen.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'municipioDestino'}
                            value={formData.municipioDestino}
                            label={'Municipio destino'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipiosDestino.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator 
                            name={'cantidad'}
                            value={formData.cantidad}
                            label={'Cantidad'}
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
                        <NumberValidator fullWidth
                            id={"valorDeclarado"}
                            name={"valorDeclarado"}
                            label={"Valor declarado"}
                            value={formData.valorDeclarado}
                            type={'numeric'}
                            require={['required', 'maxStringLength:8']}
                            error={['Campo obligatorio','Número máximo permitido es el 99999999']}
                            onChange={calcularValorEncomienda}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <NumberValidator fullWidth
                            id={"valorEnvio"}
                            name={"valorEnvio"}
                            label={"Valor envío"}
                            value={formData.valorEnvio}
                            type={'numeric'}
                            require={['required', 'maxStringLength:8']}
                            error={['Campo obligatorio','Número máximo permitido es el 99999999']}
                            onChange={calcularValorEncomienda}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <NumberValidator fullWidth
                            id={"valorDomicilio"}
                            name={"valorDomicilio"}
                            label={"Valor domicilio"}
                            value={formData.valorDomicilio}
                            type={'numeric'}
                            require={['maxStringLength:8']}
                            error={['Número máximo permitido es el 99999999']}
                            onChange={calcularValorEncomienda}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTextoColor'>
                            <label>Seguro $ </label>
                            <span className='textoRojo'>{'\u00A0'+ formData.valorSeguro}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='frmTextoColor'>
                            <label>Total $ </label>
                            <span className='textoRojo'> {'\u00A0'+ formData.valorTotal}</span>
                        </Box>
                    </Grid>       

                    <Grid item md={12} xl={12} sm={12} xs={12}>
                        <Box className='frmDivision'>
                            Información de la persona
                        </Box>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'tipoIdentificacion'}
                            value={formData.tipoIdentificacion}
                            label={'Tipo de identificación'} 
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={consultarPersona} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {tipoIdentificaciones.map(res=>{
                                return <MenuItem value={res.tipideid} key={res.tipideid}>{res.tipidenombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'documento'}
                            value={formData.documento}
                            label={(esEmpresa)? 'NIT' : 'Número de identificación'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 15}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                            onBlur={consultarPersona}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'primerNombre'}
                            value={formData.primerNombre}
                            label={(esEmpresa)? 'Razón social' : 'Primer nombre'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 120}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChangeUpperCase}
                            tabIndex="3"
                        />
                    </Grid>

                    {(!esEmpresa)?
                        <Fragment>
                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'segundoNombre'}
                                    value={formData.segundoNombre}
                                    label={'Segundo nombre'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{ maxLength: 40}}
                                    onChange={handleChangeUpperCase}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator 
                                    name={'primerApellido'}
                                    value={formData.primerApellido}
                                    label={'Primer apellido'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off', maxLength: 40}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChangeUpperCase}
                                />
                            </Grid>

                            <Grid item xl={3} md={3} sm={6} xs={12}>
                                <TextValidator
                                    name={'segundoApellido'}
                                    value={formData.segundoApellido}
                                    label={'Segundo apellido'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{ maxLength: 40}}
                                    onChange={handleChangeUpperCase}
                                />
                            </Grid>
                        </Fragment>
                    : null}

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

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'correo'}
                            value={formData.correo}
                            label={'Correo electrónico'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off', maxLength: 80}}
                            validators={['isEmail']}
                            errorMessages={['Correo no válido']}
                            type={"email"}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'telefonoCelular'}
                            value={formData.telefonoCelular}
                            label={'Teléfono'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{ maxLength: 20}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange}
                        />
                    </Grid>                    

                </Grid>

                <Grid container direction="row"  justifyContent="right">
                    <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                            startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                        </Button>
                    </Stack>
                </Grid>
            </ValidatorForm>

            <ModalDefaultAuto
                title   = {'Visualizar factura en PDF del tiquete'} 
                content = {<VisualizarPdf id={idEncomienda} />} 
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'smallFlot'
                abrir   = {abrirModal}
            />
        </Box>
    )
}
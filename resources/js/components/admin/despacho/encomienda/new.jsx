import React, {useState, useEffect} from 'react';
import {TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, MenuItem, Stack, Box} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';

export default function New({data, tipo}){

    const [formData, setFormData] = useState({codigo:data.encoid,        tipoIdentificacionRemitente:'', documentoRemitente:'',        primerNombreRemitente:'',
                                              segundoNombreRemitente:'', primerApellidoRemitente:'',     segundoApellidoRemitente:'',  direccionRemitente:'',
                                              correoRemitente:'',        telefonoCelularRemitente:'',    tipoIdentificacionDestino:'', documentoDestino:'',
                                              primerNombreDestino:'',    segundoNombreDestino :'',       primerApellidoDestino:'',     segundoApellidoDestino:'',
                                              direccionDestino:'',       correoDestino:'',               telefonoCelularDestino:'',    departamentoOrigen:'',
                                              municipioOrigen:'',        departamentoDestino:'',         municipioDestino:'',          tipoEncomienda:'',
                                              cantidad:'',               valorDeclarado :'',             valorEnvio:'',                valorDomicilio:'',
                                              contenido :'',             observaciones:'',               personaIdRemitente:'000',     personaIdDestino:'000',    tipo:tipo });

    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]);
    const [esEmpresaRemitente, setEsEmpresaRemitente] = useState(false);
    const [esEmpresaDestino, setEsEmpresaDestino] = useState(false);
    const [municipiosDestino, setMunicipiosDestino] = useState([]);
    const [tiposEncomiendas, setTiposEncomiendas] = useState([]);
    const [municipiosOrigen, setMunicipiosOrigen] = useState([]);
    const [departamentos, setDepartamentos] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [municipios, setMunicipios] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    };

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/despacho/encomienda/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:data.encoid,        tipoIdentificacionRemitente:'', documentoRemitente:'',        primerNombreRemitente:'',
                                                                  segundoNombreRemitente:'', primerApellidoRemitente:'',     segundoApellidoRemitente:'',  direccionRemitente:'',
                                                                  correoRemitente:'',        telefonoCelularRemitente:'',    tipoIdentificacionDestino:'', documentoDestino:'',
                                                                  primerNombreDestino:'',    segundoNombreDestino :'',       primerApellidoDestino:'',     segundoApellidoDestino:'',
                                                                  direccionDestino:'',       correoDestino:'',               telefonoCelularDestino:'',    departamentoOrigen:'',
                                                                  municipioOrigen:'',        departamentoDestino:'',         municipioDestino:'',          tipoEncomienda:'',
                                                                  cantidad:'',               valorDeclarado :'',             valorEnvio:'',                valorDomicilio:'',
                                                                  contenido :'',             observaciones:'',               personaIdRemitente:'000',     personaIdDestino:'000',    tipo:tipo }) : null;
            setLoader(false);
        })
    }

    const consultarPersonaRemitente = (e) =>{
        let newFormData                         = {...formData}
        let tpIdentificacion                    = (e.target.name === 'tipoIdentificacionRemitente' ) ? e.target.value : formData.tipoIdentificacionRemitente;
        let documento                           = (e.target.name === 'documentoRemitente' ) ? e.target.value : formData.documentoRemitente ;
        newFormData.tipoIdentificacionRemitente = tpIdentificacion;
        newFormData.documentoRemitente          = documento;
       if (tpIdentificacion !=='' && documento !==''){
            setLoader(true);
            instance.post('/admin/despacho/encomienda/consultar/datos/persona', {tipoIdentificacion:tpIdentificacion, documento: documento}).then(res=>{
                if(res.success){
                    let personaservicio                  = res.data;
                    newFormData.personaIdRemitente       = personaservicio.perserid;
                    newFormData.primerNombreRemitente    = personaservicio.perserprimernombre;
                    newFormData.segundoNombreRemitente   = (personaservicio.persersegundonombre !== undefined) ? personaservicio.persersegundonombre : '';
                    newFormData.primerApellidoRemitente  = (personaservicio.perserprimerapellido !== undefined) ? personaservicio.perserprimerapellido : '';
                    newFormData.segundoApellidoRemitente = (personaservicio.persersegundoapellido !== undefined) ? personaservicio.persersegundoapellido : '';
                    newFormData.direccionRemitente       = (personaservicio.perserdireccion !== undefined) ? personaservicio.perserdireccion : '';
                    newFormData.correoRemitente          = (personaservicio.persercorreoelectronico !== undefined) ? personaservicio.persercorreoelectronico : '';
                    newFormData.telefonoCelularRemitente = (personaservicio.persernumerocelular !== undefined) ? personaservicio.persernumerocelular : '';
                }else{
                    newFormData.personaIdRemitente       = '000';
                    newFormData.primerNombreRemitente    = '';
                    newFormData.segundoNombreRemitente   = '';
                    newFormData.primerApellidoRemitente  = '';
                    newFormData.segundoApellidoRemitente = '';
                    newFormData.direccionRemitente       = '';
                    newFormData.correoRemitente          = '';
                    newFormData.telefonoCelularRemitente = '';
                }
                setLoader(false); 
            })
        }
        setEsEmpresaRemitente((e.target.value === 5) ? true : false);
        setFormData(newFormData);
    }

    const consultarPersonaDestino = (e) =>{
        let newFormData                       = {...formData}
        let tpIdentificacion                  = (e.target.name === 'tipoIdentificacionDestino' ) ? e.target.value : formData.tipoIdentificacionDestino;
        let documento                         = (e.target.name === 'documentoDestino' ) ? e.target.value : formData.documentoDestino ;
        newFormData.tipoIdentificacionDestino = tpIdentificacion;
        newFormData.documentoDestino          = documento;
       if (tpIdentificacion !=='' && documento !==''){
            setLoader(true);
            instance.post('/admin/despacho/encomienda/consultar/datos/persona', {tipoIdentificacion:tpIdentificacion, documento: documento}).then(res=>{
                if(res.success){
                    let personaservicio                = res.data;
                    newFormData.personaIdDestino       = personaservicio.perserid;
                    newFormData.primerNombreDestino    = personaservicio.perserprimernombre;
                    newFormData.segundoNombreDestino   = (personaservicio.persersegundonombre !== undefined) ? personaservicio.persersegundonombre : '';
                    newFormData.primerApellidoDestino  = (personaservicio.perserprimerapellido !== undefined) ? personaservicio.perserprimerapellido : '';
                    newFormData.segundoApellidoDestino = (personaservicio.persersegundoapellido !== undefined) ? personaservicio.persersegundoapellido : '';
                    newFormData.direccionDestino       = (personaservicio.perserdireccion !== undefined) ? personaservicio.perserdireccion : '';
                    newFormData.correoDestino          = (personaservicio.persercorreoelectronico !== undefined) ? personaservicio.persercorreoelectronico : '';
                    newFormData.telefonoCelularDestino = (personaservicio.persernumerocelular !== undefined) ? personaservicio.persernumerocelular : '';
                }else{
                    newFormData.personaIdDestino       = '000';
                    newFormData.primerNombreDestino    = '';
                    newFormData.segundoNombreDestino   = '';
                    newFormData.primerApellidoDestino  = '';
                    newFormData.segundoApellidoDestino = '';
                    newFormData.direccionDestino       = '';
                    newFormData.correoDestino          = '';
                    newFormData.telefonoCelularDestino = '';
                }
                setLoader(false);
            })
        }
        setEsEmpresaDestino((e.target.value === 5) ? true : false);
        setFormData(newFormData);
    }

    const consultarMunicipioOrigen = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
        let municipiosOrigen = [];
        let deptoOrigen      = e.target.value;
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === deptoOrigen){
                municipiosOrigen.push({
                    muniid:     muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setMunicipiosOrigen(municipiosOrigen);
    }

    const consultarMunicipioDestino = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
        let municipiosDestino = [];
        let deptoDestino      = e.target.value;
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === deptoDestino){
                municipiosDestino.push({
                    muniid:     muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setMunicipiosDestino(municipiosDestino);
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/encomienda/listar/datos', {tipo:tipo, codigo:formData.codigo}).then(res=>{   
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setTiposEncomiendas(res.tiposEncomiendas);
            setDepartamentos(res.departamentos);
            setMunicipios(res.municipios);
            if(tipo === 'U'){
                let encomienda                          = res.encomienda;
                newFormData.personaIdRemitente          = encomienda.perseridremitente;
                newFormData.personaIdDestino            = encomienda.perseriddestino;
                newFormData.tipoIdentificacionRemitente = encomienda.tipideid;
                newFormData.documentoRemitente          = encomienda.perserdocumento;
                newFormData.primerNombreRemitente       = encomienda.perserprimernombre;
                newFormData.segundoNombreRemitente      = encomienda.persersegundonombre;
                newFormData.primerApellidoRemitente     = encomienda.perserprimerapellido;
                newFormData.segundoApellidoRemitente    = encomienda.persersegundoapellido;
                newFormData.direccionRemitente          = encomienda.perserdireccion;
                newFormData.correoRemitente             = encomienda.persercorreoelectronico;
                newFormData.telefonoCelularRemitente    = encomienda.persernumerocelular;
                newFormData.tipoIdentificacionDestino   = encomienda.tipideidDestino;
                newFormData.documentoDestino            = encomienda.perserdocumentoDestino;
                newFormData.primerNombreDestino         = encomienda.perserprimernombreDestino;
                newFormData.segundoNombreDestino        = encomienda.persersegundonombreDestino;
                newFormData.primerApellidoDestino       = encomienda.perserprimerapellidoDestino;
                newFormData.segundoApellidoDestino      = encomienda.persersegundoapellidoDestino;
                newFormData.direccionDestino            = encomienda.perserdireccionDestino;
                newFormData.correoDestino               = encomienda.persercorreoelectronicoDestino;
                newFormData.telefonoCelularDestino      = encomienda.persernumerocelularDestino;
                newFormData.departamentoOrigen          = encomienda.depaidorigen;
                newFormData.municipioOrigen             = encomienda.muniidorigen;
                newFormData.departamentoDestino         = encomienda.depaiddestino;
                newFormData.municipioDestino            = encomienda.muniiddestino;
                newFormData.tipoEncomienda              = encomienda.tipencid;
                newFormData.cantidad                    = encomienda.encocantidad;
                newFormData.valorDeclarado              = encomienda.encovalordeclarado;
                newFormData.valorEnvio                  = encomienda.encovalorenvio;
                newFormData.valorDomicilio              = encomienda.encovalordomicilio;
                newFormData.contenido                   = encomienda.encocontenido;
                newFormData.observaciones               = encomienda.encoobservacion
                setFormData(newFormData);
            }
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Grid container spacing={2}>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Información de la encomienda
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'departamentoOrigen'}
                        value={formData.departamentoOrigen}
                        label={'Departamento origen'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarMunicipioOrigen}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {departamentos.map(res=>{
                            return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
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
                        name={'departamentoDestino'}
                        value={formData.departamentoDestino}
                        label={'Departamento destino'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarMunicipioDestino}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {departamentos.map(res=>{
                            return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
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
                    <SelectValidator
                        name={'tipoEncomienda'}
                        value={formData.tipoEncomienda}
                        label={'Tipo de encomienda'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tiposEncomiendas.map(res=>{
                            return <MenuItem value={res.tipencid} key={res.tipencid} >{res.tipencnombre}</MenuItem>
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
                        onChange={handleChange}
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
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <NumberValidator fullWidth
                        id={"valorDomicilio"}
                        name={"valorDomicilio"}
                        label={"Valor domicilio"}
                        value={formData.valorDomicilio}
                        type={'numeric'}
                        require={['required', 'maxStringLength:8']}
                        error={['Campo obligatorio','Número máximo permitido es el 99999999']}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <TextValidator 
                        name={'contenido'}
                        value={formData.contenido}
                        label={'Contenido'}
                        className={'inputGeneral'} 
                        inputProps={{autoComplete: 'off', maxLength: 1000}}
                        variant={"standard"} 
                        require={['required']}
                        error={['Campo obligatorio']}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <TextValidator 
                        name={'observaciones'}
                        value={formData.observaciones}
                        label={'Observaciones'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 500}}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Información del remitente
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'tipoIdentificacionRemitente'}
                        value={formData.tipoIdentificacionRemitente}
                        label={'Tipo de identificación'} 
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarPersonaRemitente} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoIdentificaciones.map(res=>{
                            return <MenuItem value={res.tipideid} key={res.tipideid}>{res.tipidenombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'documentoRemitente'}
                        value={formData.documentoRemitente}
                        label={(esEmpresaRemitente)? 'NIT' : 'Número de identificación'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 15}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        onBlur={consultarPersonaRemitente}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'primerNombreRemitente'}
                        value={formData.primerNombreRemitente}
                        label={(esEmpresaRemitente)? 'Razón social' : 'Primer nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 120}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                        tabIndex="3"
                    />
                </Grid>

                {(!esEmpresaRemitente)?
                    <Fragment>
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'segundoNombreRemitente'}
                                value={formData.segundoNombreRemitente}
                                label={'Segundo nombre'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{ maxLength: 40}}
                                onChange={handleChangeUpperCase}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator 
                                name={'primerApellidoRemitente'}
                                value={formData.primerApellidoRemitente}
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
                                name={'segundoApellidoRemitente'}
                                value={formData.segundoApellidoRemitente}
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
                        name={'direccionRemitente'}
                        value={formData.direccionRemitente}
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
                        name={'correoRemitente'}
                        value={formData.correoRemitente}
                        label={'Correo electrónico'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={['required', 'isEmail']}
                        errorMessages={['Campo requerido', 'Correo no válido']}
                        type={"email"}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'telefonoCelularRemitente'}
                        value={formData.telefonoCelularRemitente}
                        label={'Teléfono'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{ maxLength: 20}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>


                <Grid item md={12} xl={12} sm={12} xs={12}>
                    <Box className='frmDivision'>
                        Información del destino
                    </Box>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'tipoIdentificacionDestino'}
                        value={formData.tipoIdentificacionDestino}
                        label={'Tipo de identificación'} 
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarPersonaDestino} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoIdentificaciones.map(res=>{
                            return <MenuItem value={res.tipideid} key={res.tipideid}>{res.tipidenombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'documentoDestino'}
                        value={formData.documentoDestino}
                        label={(esEmpresaDestino)? 'NIT' : 'Número de identificación'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 15}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        onBlur={consultarPersonaDestino}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'primerNombreDestino'}
                        value={formData.primerNombreDestino}
                        label={(esEmpresaDestino)? 'Razón social' : 'Primer nombre'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 120}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                        tabIndex="3"
                    />
                </Grid>

                {(!esEmpresaDestino)?
                    <Fragment>
                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'segundoNombreDestino'}
                                value={formData.segundoNombreDestino}
                                label={'Segundo nombre'}
                                className={'inputGeneral'} 
                                variant={"standard"} 
                                inputProps={{ maxLength: 40}}
                                onChange={handleChangeUpperCase}
                            />
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator 
                                name={'primerApellidoDestino'}
                                value={formData.primerApellidoDestino}
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
                                name={'segundoApellidoDestino'}
                                value={formData.segundoApellidoDestino}
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
                        name={'direccionDestino'}
                        value={formData.direccionDestino}
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
                        name={'correoDestino'}
                        value={formData.correoDestino}
                        label={'Correo electrónico'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={['required', 'isEmail']}
                        errorMessages={['Campo requerido', 'Correo no válido']}
                        type={"email"}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'telefonoCelularDestino'}
                        value={formData.telefonoCelularDestino}
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
    )
}
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
    const [formData, setFormData] = useState({codigo:tiquid,          tipoIdentificacion:'',   documento:'',          primerNombre:'',
                                              segundoNombre:'',       primerApellido:'',       segundoApellido:'',    direccion:'',
                                              correo:'',              telefonoCelular:'',      departamentoOrigen:'', municipioOrigen:'',
                                              departamentoDestino:'', municipioDestino:'',     valorTiquete :'',      ruta:'',     
                                              valorDescuento:'',      valorFondoReposicion:'', valorTotal:'',         personaId:'000',   tipo:tipo});

    const [tipoIdentificaciones, setTipoIdentificaciones] = useState([]); 
    const [municipiosDestino, setMunicipiosDestino] = useState([]);
    const [municipiosOrigen, setMunicipiosOrigen] = useState([]);
    const [tarifaTiquetes, setTarifaTiquetes] = useState([]);
    const [planillaRutas, setPlanillaRutas] = useState([]);
    const [abrirModal, setAbrirModal] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [esEmpresa, setEsEmpresa] = useState(false);
    const [municipios, setMunicipios] = useState([]);
    const [idTiquete , setIdTiquete] = useState(0);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/despacho/tiquete/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            if(formData.tipo === 'I' && res.success){
                setFormData({codigo:tiquid,         tipoIdentificacion:'',   documento:'',          primerNombre:'',
                            segundoNombre:'',       primerApellido:'',       segundoApellido:'',    direccion:'',
                            correo:'',              telefonoCelular:'',      departamentoOrigen:'', municipioOrigen:'',
                            departamentoDestino:'', municipioDestino:'',      ruta:'',              valorTiquete :'',
                            valorDescuento:'',      valorFondoReposicion:'', valorTotal:'',         personaId:'000',   tipo:tipo });

                setIdTiquete(res.tiqueteId);
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
                    let personaservicio         = res.data;
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

    const consultarMunicipioOrigen = (e) =>{
        let newFormData               = {...formData}
        let valorTiquete              = 0;
        let fondoTeposicion           = 0;
        const planillaRutasFiltradas  = planillaRutas.filter(planilla => planilla.plarutid === e.target.value);
        let rutaId                    = planillaRutasFiltradas[0].rutaid;
        let depaIdOrigen              = planillaRutasFiltradas[0].depaidorigen;
        let muniIdOrigen              = planillaRutasFiltradas[0].muniidorigen;
        let municipioOrigen           = planillaRutasFiltradas[0].municipioOrigen;
        let depaIdDestino             = planillaRutasFiltradas[0].depaiddestino;
        let muniIdDestino             = planillaRutasFiltradas[0].muniiddestino;
        let municipioDestino          = planillaRutasFiltradas[0].municipioDestino;
        const tarifaTiquetesFiltradas = tarifaTiquetes.filter(tt => tt.rutaid === rutaId && tt.depaiddestino === depaIdDestino && tt.muniiddestino === muniIdDestino);

        if(tarifaTiquetesFiltradas.length === 0){
            showSimpleSnackbar("No existe valor del tiquete gestionado para la ruta "+municipioOrigen+' - '+municipioDestino, 'error');
            newFormData.valorTiquete         = 0;
            newFormData.valorFondoReposicion = 0;
            setFormData(newFormData);
            return;
        }

        newFormData.ruta                 = e.target.value;
        newFormData.municipioOrigen      = muniIdOrigen;
        newFormData.departamentoOrigen   = depaIdOrigen; 
        newFormData.departamentoDestino  = depaIdDestino;
        newFormData.municipioDestino     = muniIdDestino; 
        valorTiquete                     = tarifaTiquetesFiltradas[0].tartiqvalor;
        fondoTeposicion                  = tarifaTiquetesFiltradas[0].tartiqfondoreposicion;
        newFormData.valorTiquete         = formatearNumero(valorTiquete);
        newFormData.valorFondoReposicion = formatearNumero((valorTiquete * fondoTeposicion)/ 100);

        let municipiosDestino = [];
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === depaIdDestino){
                municipiosDestino.push({
                    muniid:     muni.muniid,
                    munidepaid: muni.munidepaid,
                    muninombre: muni.muninombre
                });
            }
        });

        municipiosDestino.push({
            muniid:     muniIdDestino,
            munidepaid: depaIdDestino,
            muninombre: municipioDestino
        }); 

        setFormData(newFormData);      
        setMunicipiosDestino(municipiosDestino);
    }

    const consultarMunicipioDestino = (e) =>{
        let newFormData                 = {...formData}
        const municipiosOrigenFiltrados = municipiosOrigen.filter(mun => mun.muniid === e.target.value);
        let depaIdOrigen                = municipiosOrigenFiltrados[0].munidepaid;   
        let municipioOrigen             = municipiosOrigenFiltrados[0].muninombre;
        newFormData.departamentoOrigen  = depaIdOrigen;
        newFormData.municipioOrigen     = e.target.value;

        const planillaRutasFiltradas    = planillaRutas.filter(planilla => planilla.plarutid === formData.ruta);
        let muniIdDestino               = planillaRutasFiltradas[0].muniiddestino;
        let municipioDestino            = planillaRutasFiltradas[0].municipioDestino;

        const tarifaTiquetesFiltradas  = tarifaTiquetes.filter(tt => tt.rutaid === formData.ruta && tt.depaiddestino === formData.departamentoDestino && tt.muniiddestino === e.target.value);
        if(tarifaTiquetesFiltradas.length === 0){
            showSimpleSnackbar("No existe valor del tiquete gestionado para la ruta "+municipioOrigen+' - '+municipioDestino, 'error');
            return;
        }

        let municipiosDestino = [];
        municipios.forEach(function(muni){ 
            if(muni.muniid !== e.target.value){
                municipiosDestino.push({
                    muniid:     muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });

        municipiosDestino.push({
            muniid:     muniIdDestino,
            muninombre: municipioDestino
        });

        setFormData(newFormData);
        setMunicipiosDestino(municipiosDestino);
    }

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }

    const calcularValorTiquete = (e) =>{
        let newFormData              = {...formData} 

        const tarifaTiquetesFiltradas = tarifaTiquetes.filter(tt => tt.rutaid === formData.ruta 
                                                                && tt.depaiddestino === formData.municipioOrigen
                                                                && tt.muniiddestino === formData.municipioDestino);



        console.log(tarifaTiquetesFiltradas);

    }

    const calcularValorTotal = (e) =>{

        /*let newFormData            = {...formData}
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
        setFormData(newFormData);*/
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.post('/admin/despacho/tiquete/listar/datos', {tipo:tipo, codigo:formData.codigo}).then(res=>{
           /* let valorEnvio             =  res.configuracionEncomienda.conencvalorminimoenvio
            let valorDeclarado         = res.configuracionEncomienda.conencvalorminimodeclarado;
            let valorSeguro            = (valorDeclarado * res.configuracionEncomienda.conencporcentajeseguro) / 100;
            let valorTotal             = Number(valorEnvio) + Number(valorSeguro);
            newFormData.valorDeclarado = valorDeclarado;
            newFormData.valorEnvio     = valorEnvio;
            newFormData.valorSeguro    = formatearNumero(valorSeguro);
            newFormData.valorTotal     = formatearNumero(valorTotal);*/
            
            setTipoIdentificaciones(res.tipoIdentificaciones);
            setTarifaTiquetes(res.tarifaTiquetes);
            setPlanillaRutas(res.planillaRutas);
            setMunicipios(res.municipios);

            if(tipo === 'U'){
                let tiquete                      = res.tiquete;
                newFormData.personaId            = tiquete.perserid;
                newFormData.tipoIdentificacion   = tiquete.tipideid;
                newFormData.documento            = tiquete.perserdocumento;
                newFormData.primerNombre         = tiquete.perserprimernombre;
                newFormData.segundoNombre        = (tiquete.persersegundonombre !== null) ? tiquete.persersegundonombre : '';
                newFormData.primerApellido       = (tiquete.perserprimerapellido !== null) ? tiquete.perserprimerapellido : '';
                newFormData.segundoApellido      = (tiquete.persersegundoapellido !== null) ? tiquete.persersegundoapellido : '';
                newFormData.direccion            = tiquete.perserdireccion;
                newFormData.correo               = (tiquete.persercorreoelectronico !== null) ? tiquete.persercorreoelectronico : '';
                newFormData.telefonoCelular      = tiquete.persernumerocelular;
                newFormData.departamentoOrigen   = tiquete.depaidorigen;
                newFormData.municipioOrigen      = tiquete.muniidorigen;
                newFormData.departamentoDestino  = tiquete.depaiddestino;
                newFormData.municipioDestino     = tiquete.muniiddestino;
                newFormData.ruta                 = tiquete.plarutid;
                newFormData.valorTiquete         = formatearNumero(tiquete.tiquvalortiquete);
                newFormData.valorDescuento       = formatearNumero(tiquete.tiquvalordescuento);
                newFormData.valorFondoReposicion = formatearNumero(tiquete.tiquvalorfondoreposicion);
                newFormData.valorTotal           = formatearNumero(tiquete.tiquvalortotal);

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
                            onChange={consultarMunicipioOrigen}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {planillaRutas.map(res=>{
                                return <MenuItem value={res.plarutid} key={res.plarutid}> {res.plarutid} {res.nombreRuta}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'municipioDestino'}
                            value={formData.municipioDestino}
                            label={'Municipio nodo destino'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipiosDestino.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muniid} {res.muninombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'cantidadPuesto'}
                            value={formData.cantidadPuesto}
                            label={'cantidad de Puesto'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={calcularValorTiquete} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            <MenuItem value={"1"}>1</MenuItem>
                            <MenuItem value={"2"}>2</MenuItem>
                            <MenuItem value={"3"}>3</MenuItem>
                            <MenuItem value={"4"}>4</MenuItem>
                            <MenuItem value={"5"}>5</MenuItem>
                           
                        </SelectValidator>
                    </Grid>

                </Grid>

                <Grid container spacing={2}>
                    <Grid item xl={9} md={9} sm={12} xs={12}>
                        <Grid container spacing={2}>

                        </Grid>
                    </Grid>

                    <Grid item xl={3} md={3} sm={12} xs={12} style={{marginTop:'1em'}}>
                        <Grid container spacing={2}>                            
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='frmTextoColor'>
                                    <label>Valor tiquete $ </label>
                                    <span className='textoRojo'>{'\u00A0'+ formData.valorTiquete}</span>
                                </Box>
                            </Grid>

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <NumberValidator fullWidth
                                    id={"valorDescuento"}
                                    name={"valorDescuento"}
                                    label={"Valor descuento"}
                                    value={formData.valorDescuento}
                                    type={'numeric'}
                                    require={['maxStringLength:8']}
                                    error={['Número máximo permitido es el 99999999']}
                                    onChange={calcularValorTotal}
                                />
                            </Grid> 

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='frmTextoColor'>
                                    <label>Fondo de reposición $ </label>
                                    <span className='textoRojo'>{'\u00A0'+ formData.valorFondoReposicion}</span>
                                </Box>
                            </Grid>
                            
                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Box className='frmTextoColor'>
                                    <label>Total $ </label>
                                    <span className='textoRojo'> {'\u00A0'+ formData.valorTotal}</span>
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
                content = {<VisualizarPdf id={idTiquete} />} 
                close   = {() =>{setAbrirModal(false);}} 
                tam     = 'smallFlot'
                abrir   = {abrirModal}
            />
        </Box>
    )
}
import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Autocomplete, createFilterOptions}  from '@mui/material';
import {ButtonFileImg, ContentFile} from "../../../layout/files";
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import Files from "react-files";

export default function New({data, tipo}){

    const [formData, setFormData] = useState({codigo: '000',      tipoVehiculo: '',   tipoReferencia: '', tipoMarca: '',        tipoCombustible: '', 
                                             tipoModalidad: '',   tipoCarroceria: '', tipoColor: '',      agencia: '',          fechaIngreso: '', 
                                             numeroInterno: '',   placa: '',          modelo: '',         cilindraje: '',       numeroMotor: '', 
                                             numeroChasis: '',    numeroSerie: '',    numeroEjes: '1',    motorRegrabado: '0', chasisRegrabado: '0', 
                                             serieRegrabado: '0', observacion: '',    fotografia: '',     tipo:tipo,           fechaInicialContrato: '',
                                             asociado:''
                                            });

    
    const [loader, setLoader] = useState(false);
    const [agencias, setAgencias] = useState([]);
    const [asociados, setAsociados] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [fotoVehiculo, setFotoVehiculo] = useState('');
    const [tipoVehiculos, setTipoVehiculos] = useState([]);    
    const [tipoColorVehiculos, setTipoColorVehiculos] = useState([]);
    const [tipoMarcaVehiculos, setTipoMarcaVehiculos] = useState([]);
    const [formDataFile, setFormDataFile] = useState({ archivos : []});
    const [tipoModalidadVehiculos, setTipoModalidadVehiculos] = useState([]);
    const [tipoReferenciaVehiculos, setTipoReferenciaVehiculos] = useState([]);
    const [tipoCarroceriaVehiculos, setTipoCarroceriaVehiculos] = useState([]);
    const [tipoCombustibleVehiculos, setTipoCombustibleVehiculos] = useState([]); 
    
    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}));
    }

    const onFilesChange = (files , nombre) =>  {
        setFormDataFile(prev => ({...prev, [nombre]: files}));
    }

    const removeFIle = (nombre)=>{
        setFormDataFile(prev => ({...prev, archivos: prev.archivos.filter(item => item.name !== nombre)}));
    }
    
    const onFilesError = (error, file) => {
        let msj = (error.code === 2) ? 'El archivo "'+ file.name + '" es demasiado grande y no se puede subir' : error.message  
        showSimpleSnackbar(msj, 'error');
    }

    const handleSubmit = () =>{
        let newFormData        = {...formData};
        newFormData.fotografia = (formDataFile.archivos.length > 0) ? formDataFile.archivos[0] : '';
        setLoader(true);
        instance.post('/admin/direccion/transporte/vehiculo/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo: '000',     tipoVehiculo: '',   tipoReferencia: '', tipoMarca: '',       tipoCombustible: '', 
                                                                tipoModalidad: '',   tipoCarroceria: '', tipoColor: '',      agencia: '',         fechaIngreso: '', 
                                                                numeroInterno: '',   placa: '',          modelo: '',         cilindraje: '',      numeroMotor: '', 
                                                                numeroChasis: '',    numeroSerie: '',    numeroEjes: '1',    motorRegrabado: '0', chasisRegrabado: '0', 
                                                                serieRegrabado: '0', observacion: '',    fotografia: '',     tipo:tipo,           fechaInicialContrato: '', asociado:'' }) : null;

            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        instance.post('/admin/direccion/transporte/vehiculo/list/datos', {codigo: (tipo === 'I') ? '000' : data.vehiid, tipo:tipo}).then(res=>{
            setAsociados(res.asociados);
            setTipoVehiculos(res.tipovehiculos);
            setTipoReferenciaVehiculos(res.tiporeferenciavehiculos);
            setTipoMarcaVehiculos(res.tipomarcavehiculos);
            setTipoCarroceriaVehiculos(res.tipocarroceriavehiculos);
            setTipoColorVehiculos(res.tipocolorvehiculos);
            setTipoCombustibleVehiculos(res.tipocombustiblevehiculos);
            setTipoModalidadVehiculos(res.tipomodalidadvehiculos);
            setAgencias(res.agencias);

            if(tipo === 'U'){
                let newFormData             = {...formData}
                let vehiculo                = res.vehiculo;          
                newFormData.asociado        = vehiculo.asocid;
                newFormData.codigo          = vehiculo.vehiid;
                newFormData.tipoVehiculo    = vehiculo.tipvehid;
                newFormData.tipoReferencia  = vehiculo.tireveid;
                newFormData.tipoMarca       = vehiculo.timaveid;
                newFormData.tipoCombustible = vehiculo.ticovhid;
                newFormData.tipoModalidad   = vehiculo.timoveid;
                newFormData.tipoCarroceria  = vehiculo.ticaveid;
                newFormData.tipoColor       = vehiculo.ticoveid;
                newFormData.agencia         = vehiculo.agenid;
                newFormData.fechaIngreso    = vehiculo.vehifechaingreso;
                newFormData.numeroInterno   = vehiculo.vehinumerointerno;
                newFormData.placa           = vehiculo.vehiplaca;
                newFormData.modelo          = vehiculo.vehimodelo;
                newFormData.cilindraje      = vehiculo.vehicilindraje;
                newFormData.numeroMotor     = (vehiculo.vehinumeromotor !== null) ? vehiculo.vehinumeromotor : '';
                newFormData.numeroChasis    = (vehiculo.vehinumerochasis !== null) ? vehiculo.vehinumerochasis : '';
                newFormData.numeroSerie     = (vehiculo.vehinumeroserie !== null) ? vehiculo.vehinumeroserie : '';
                newFormData.numeroEjes      = (vehiculo.vehinumeroejes !== null) ? vehiculo.vehinumeroejes : ''; 
                newFormData.motorRegrabado  = vehiculo.vehiesmotorregrabado;
                newFormData.chasisRegrabado = vehiculo.vehieschasisregrabado;
                newFormData.serieRegrabado  = vehiculo.vehiesserieregrabado;
                newFormData.observacion     = (vehiculo.vehiobservacion !== null) ? vehiculo.vehiobservacion : '';
                newFormData.fotografia      = (vehiculo.vehirutafoto !== null) ? vehiculo.vehirutafoto : '';
                newFormData.rutaFotoOld     = (vehiculo.vehirutafoto !== null) ? vehiculo.vehirutafoto : '';
                setFotoVehiculo((vehiculo.vehirutafoto !== null) ? vehiculo.rutaFotografia : '');
                setFormData(newFormData)
            }
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >

            <Grid container spacing={2}>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tipoVehiculo'}
                        value={formData.tipoVehiculo}
                        label={'Tipo de vehículo'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoVehiculos.map(res=>{
                            return <MenuItem value={res.tipvehid} key={res.tipvehid}>{res.tipvehnombre} {res.tipvehreferencia}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tipoReferencia'}
                        value={formData.tipoReferencia}
                        label={'Tipo de referencia'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoReferenciaVehiculos.map(res=>{
                            return <MenuItem value={res.tireveid} key={res.tireveid}>{res.tirevenombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tipoMarca'}
                        value={formData.tipoMarca}
                        label={'Tipo de marca'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoMarcaVehiculos.map(res=>{
                            return <MenuItem value={res.timaveid} key={res.timaveid}>{res.timavenombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tipoCarroceria'}
                        value={formData.tipoCarroceria}
                        label={'Tipo de carrocería'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoCarroceriaVehiculos.map(res=>{
                            return <MenuItem value={res.ticaveid} key={res.ticaveid}>{res.ticavenombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tipoColor'}
                        value={formData.tipoColor}
                        label={'Tipo de color'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoColorVehiculos.map(res=>{
                            return <MenuItem value={res.ticoveid} key={res.ticoveid}>{res.ticovenombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tipoCombustible'}
                        value={formData.tipoCombustible}
                        label={'Tipo de combstible'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoCombustibleVehiculos.map(res=>{
                            return <MenuItem value={res.ticovhid} key={res.ticovhid}>{res.ticovhnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tipoModalidad'}
                        value={formData.tipoModalidad}
                        label={'Tipo de modalidad'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoModalidadVehiculos.map(res=>{
                            return <MenuItem value={res.timoveid} key={res.timoveid}>{res.timovenombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'agencia'}
                        value={formData.agencia}
                        label={'Agencia'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {agencias.map(res=>{
                            return <MenuItem value={res.agenid} key={res.agenid}>{res.agennombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'fechaIngreso'}
                        value={formData.fechaIngreso}
                        label={'Fecha de ingeso'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        type={"date"}
                        InputLabelProps={{
                            shrink: true,
                        }}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'numeroInterno'}
                        value={formData.numeroInterno}
                        label={'Número interno'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required","maxNumber:9999"]}
                        errorMessages={["campo obligatorio","Número máximo permitido es el 9999"]}
                        onChange={handleChange}
                        type={"number"}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'placa'}
                        value={formData.placa}
                        label={'Placa'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 8}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'modelo'}
                        value={formData.modelo}
                        label={'Modelo'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required","maxNumber:9999"]}
                        errorMessages={["campo obligatorio","Número máximo permitido es el 9999"]}
                        onChange={handleChange}
                        type={"number"}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'cilindraje'}
                        value={formData.cilindraje}
                        label={'Cilindraje'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 6}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                    />
                </Grid> 

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'numeroMotor'}
                        value={formData.numeroMotor}
                        label={'Número de motor'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 30}}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>  

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'numeroChasis'}
                        value={formData.numeroChasis}
                        label={'Número de chasis'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 30}}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'numeroSerie'}
                        value={formData.numeroSerie}
                        label={'Número de serie'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 30}}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'numeroEjes'}
                        value={formData.numeroEjes}
                        label={'Número de ejes'}
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
                    <SelectValidator
                        name={'motorRegrabado'}
                        value={formData.motorRegrabado}
                        label={'Motor regrabado'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"} >Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'chasisRegrabado'}
                        value={formData.chasisRegrabado}
                        label={'Chasis regrabado'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"} >Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'serieRegrabado'}
                        value={formData.serieRegrabado}
                        label={'Serie regrabado'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"} >Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                <Grid item xl={5} md={5} sm={12} xs={12}>
                    <Autocomplete
                        id="asociado"
                        style={{height: "26px", width: "100%"}}
                        options={asociados}
                        getOptionLabel={(option) => option.nombrePersona} 
                        value={asociados.find(v => v.asocid === formData.asociado) || null}
                        filterOptions={createFilterOptions({ limit:10 })}
                        onChange={(event, newInputValue) => {
                            if(newInputValue){
                                setFormData({...formData, asociado: newInputValue.asocid})
                            }
                        }}
                        renderInput={(params) =>
                            <TextValidator {...params}
                                label="Consultar asociado"
                                className="inputGeneral"
                                variant="standard"
                                validators={["required"]}
                                errorMessages="Campo obligatorio"
                                value={formData.asociado}
                                placeholder="Consulte el asociado aquí..." />}
                    />
                </Grid>

                <Grid item xl={7} md={7} sm={12} xs={12}>
                    <TextValidator 
                        name={'observacion'}
                        value={formData.observacion}
                        label={'Observación'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 500}}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item md={6} xl={6} sm={12} xs={12}>
                    <Files
                        className='files-dropzone'
                        onChange={(file ) =>{onFilesChange(file, 'archivos') }}
                        onError={onFilesError}
                        accepts={['.jpg', '.png', '.jpeg']} 
                        multiple
                        maxFiles={1}
                        maxFileSize={1000000}
                        clickable
                        dropActiveClassName={"files-dropzone-active"}
                    >
                    <ButtonFileImg title={"Subir foto del vehículo"} />
                    </Files>
                </Grid>

                <Grid item md={3} xl={3} sm={6} xs={12}>
                    <Box style={{display: 'flex', flexWrap: 'wrap'}}>
                        {formDataFile.archivos.map((file, a) =>{
                            return <ContentFile file={file} name={file.name} remove={removeFIle} key={'ContentFile-' +a}/>
                        })}
                    </Box>
                </Grid>

                {(tipo === 'U' && formData.fotografia !== '') ?
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <Box className='fotografiaVehiculo'>
                            <img src={fotoVehiculo} style={{width: '100%'}} ></img>
                        </Box>
                    </Grid>
                : null }

                {(tipo === 'I')? 
                    <Fragment>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='frmDivision'>
                                Información de del contrato para el vehículo
                            </Box>
                        </Grid>

                        <Grid item xl={3} md={3} sm={6} xs={12}>
                            <TextValidator
                                name={'fechaInicialContrato'}
                                value={formData.fechaInicialContrato}
                                label={'Fecha inicial del contrato'}
                                className={'inputGeneral'}
                                variant={"standard"}
                                inputProps={{autoComplete: 'off'}}
                                validators={["required"]}
                                errorMessages={["Campo obligatorio"]}
                                onChange={handleChange}
                                type={"date"}
                                InputLabelProps={{
                                    shrink: true,
                                }}
                            />
                        </Grid>

                    </Fragment>
                : null}

            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {(tipo=== 'I') ? "Guardar" : "Actualizar"}
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
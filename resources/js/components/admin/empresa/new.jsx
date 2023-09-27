import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box } from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';

export default function New({data}){
 
    const [formData, setFormData] = useState(
                    {
                        codigo: data.emprid, jefe: data.persidrepresentantelegal, departamento: data.emprdepaid, municipio: data.emprmuniid, 
                        nit: data.emprnit, digitoVerificacion: data.emprdigitoverificacion,  nombre: data.emprnombre,  sigla: data.emprsigla,
                        lema: (data.emprlema !== null ) ? data.emprlema : '',  direccion: data.emprdireccion, correo: (data.emprcorreo !== null ) ? data.emprcorreo : '',
                        telefono: (data.emprtelefonofijo !== null ) ? data.emprtelefonofijo : '', celular: (data.emprtelefonocelular !== null ) ? data.emprtelefonocelular : '', 
                        horarioAtencion: (data.emprhorarioatencion !== null ) ? data.emprhorarioatencion : '',
                        url: data.emprurl, codigoPostal: data.emprcodigopostal,  
                        logo_old: data.emprlogo , imagen: (data.emprlogo !== null ) ? data.imagen : '' , logo: ''
                    });

    const logo = formData.imagen; 
    const [logoEmpresa, setLogo] = useState();
    const [loader, setLoader] = useState(true); 
    const [habilitado, setHabilitado] = useState(true);
    const [firmaRL, setFirmaRL] = useState();
    const [municipios, setMunicipios] = useState([]);
    const [newMunicipios, setNewMunicipios] = useState([]);
    const [departamentos, setDepartamentos] = useState([]);
    const [jefes, setJefes] = useState([]);
   
    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        let dataFile = new FormData();
        Object.keys(formData).forEach(function(key) {
           dataFile.append(key, formData[key])
        })
        dataFile.append('logo', (logoEmpresa !== undefined) ? logoEmpresa : '');
        setLoader(true);
        instance.post('/admin/empresa/salve', dataFile).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        instance.get('/admin/empresa/list/datos').then(res=>{ 
            setJefes(res.jefes);
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
            setDepartamentos(res.deptos);
            setMunicipios(res.municipios);
            setNewMunicipios(mun);
            setLoader(false);
        })
    }, []);
 
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
                        inputProps={{autoComplete: 'off'}}
                        validators={["required","maxNumber:999999999"]}
                        errorMessages={["Campo obligatorio","Número máximo permitido es el 999999999"]}
                        onChange={handleChange}
                        type={"number"}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
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

                <Grid item xl={6} md={6} sm={12} xs={12}>
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

                <Grid item xl={5} md={5} sm={6} xs={12}>
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

                <Grid item xl={3} md={3} sm={6} xs={12}>
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

                <Grid item xl={5} md={5} sm={6} xs={12}>
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

                <Grid item xl={6} md={6} sm={6} xs={12}>
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
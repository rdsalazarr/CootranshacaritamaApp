import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack} from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo:data.agenid,  responsable:data.persidresponsable, departamento: data.agendepaid, municipio: data.agenmuniid, 
                                        nombre:data.agennombre, direccion:data.agendireccion, correo: data.agencorreo, telefonoCelular: data.agentelefonocelular,
                                        telefonoFijo:data.agentelefonofijo, estado:data.agenactiva, tipo:tipo 
                                    } : {codigo:'000', responsable:'', departamento: '', municipio: '', nombre:'', direccion:'', correo:'',
                                    telefonoCelular:'', telefonoFijo:'', estado:'1', tipo:tipo
                                }); 

    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [responsables, setResponsables] = useState([]);
    const [departamentos, setDepartamentos] = useState([]);
    const [municipios, setMunicipios] = useState([]);
    const [municipiosDeptos, setMunicipiosDeptos] = useState([]);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    };

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/agencia/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', responsable:'', departamento: '', municipio: '', nombre:'', direccion:'', correo:'',
                                                                    telefonoCelular:'', telefonoFijo:'', estado:'1', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        let newFormData = {...formData}
        instance.get('/admin/agencia/listar/datos').then(res=>{
            setResponsables(res.responsables);
            setDepartamentos(res.deptos);
            setMunicipios(res.municipios);

           if(tipo !== 'I'){ 
                let municipiosDepto = [];
                let deptoId         = data.agendepaid;
                res.municipios.forEach(function(muni){ 
                    if(muni.munidepaid === deptoId){
                        municipiosDepto.push({
                            muniid: muni.muniid,
                            muninombre: muni.muninombre
                        });
                    }
                });
                setMunicipiosDeptos(municipiosDepto);
                setFormData(newFormData);
            }
            setLoader(false);
        })
    }, []);

    const consultarMunicipio = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
        let municipiosDepto = [];
        let deptoId         = e.target.value;
        municipios.forEach(function(muni){ 
            if(muni.munidepaid === deptoId){
                municipiosDepto.push({
                    muniid:     muni.muniid,
                    muninombre: muni.muninombre
                });
            }
        });
        setMunicipiosDeptos(municipiosDepto);
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Grid container spacing={2}>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'nombre'}
                        value={formData.nombre}
                        label={'Nombre'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
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

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator
                        name={'correo'}
                        value={formData.correo}
                        label={'Correo'}
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
                        label={'Teléfono celular'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <TextValidator
                        name={'telefonoFijo'}
                        value={formData.telefonoFijo}
                        label={'Teléfono fijo'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 20}}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'responsable'}
                        value={formData.responsable}
                        label={'Responsables'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione </MenuItem>
                        {responsables.map(res=>{
                           return <MenuItem value={res.persid} key={res.persid} >{res.nombres} {res.apellidos}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <SelectValidator
                        name={'departamento'}
                        value={formData.departamento}
                        label={'Departamento'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarMunicipio}
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
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {municipiosDeptos.map(res=>{
                            return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <SelectValidator
                        name={'estado'}
                        value={formData.estado}
                        label={'Activo'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"}>Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
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
    );
}
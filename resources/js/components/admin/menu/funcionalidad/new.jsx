import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack } from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo: data.funcid, modulo: data.moduid, nombre: data.funcnombre, orden: data.funcorden,
                                    titulo: data.functitulo,icono: data.funcicono, ruta: data.funcruta,  estado: data.funcactiva, tipo:tipo 
                                    } : {codigo:'000', modulo: '', nombre: '', orden: '', icono: '', titulo: '', ruta: '', estado: '1', tipo:tipo
                                });

   const [loader, setLoader] = useState(false); 
   const [habilitado, setHabilitado] = useState(true);
   const [modulos, setModulos] = useState([]);

   const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
   }

    const handleSubmit = () =>{
        setLoader(true); 
        instance.post('/admin/funcionalidad/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', modulo: '', nombre: '', orden: '', icono: '', titulo: '', ruta: '', estado: '1', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/funcionalidad/listar/modulos').then(res=>{
            setModulos(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio(); }, []);


    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>           

                <Grid item xl={3} md={3} sm={12} xs={12}>
                    <SelectValidator
                        name={'modulo'}
                        value={formData.modulo}
                        label={'Módulo'} 
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {modulos.map(res=>{
                            return <MenuItem value={res.moduid} key={res.moduid} >{res.modunombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={5} md={5} sm={12} xs={12}>
                    <TextValidator 
                        name={'nombre'}
                        value={formData.nombre}
                        label={'Nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid> 

                <Grid item xl={4} md={4} sm={12} xs={12}>
                    <TextValidator 
                        name={'titulo'}
                        value={formData.titulo}
                        label={'Título'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={12} xs={12}>
                    <TextValidator 
                        name={'ruta'}
                        value={formData.ruta}
                        label={'Ruta'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 60}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={12} xs={12}>
                    <TextValidator 
                        name={'icono'}
                        value={formData.icono}
                        label={'Ícono'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 60}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={12} xs={12}>
                    <TextValidator 
                        name={'orden'}
                        value={formData.orden}
                        label={'Orden'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required","maxNumber:99"]}
                        errorMessages={["Campo obligatorio","Número máximo permitido es el 99"]}
                        onChange={handleChange}
                        type={"number"}
                    />
                </Grid> 

                <Grid item xl={2} md={2} sm={12} xs={12}>
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
                        <MenuItem value={"1"} >Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>
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
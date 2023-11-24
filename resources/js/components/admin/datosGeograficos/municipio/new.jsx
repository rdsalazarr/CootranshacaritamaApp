import React, {useState,useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack } from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                                            (tipo !== 'I') ? {id: data.muniid, codigo: data.municodigo, nombre: data.muninombre, 
                                            hacePresencia: data.munihacepresencia, depto: data.munidepaid, tipo:tipo
                                            } : {id:'000', codigo:'000', nombre:'', hacePresencia:'1', depto:'', tipo:tipo
                                            });

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [deptos, setDeptos] = useState([]);
   
    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true); 
        instance.post('/admin/municipio/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
            (formData.tipo === 'I' && res.success) ? setFormData({id:'000', codigo:'000', nombre:'', hacePresencia:'1', depto:'', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        instance.get('/admin/municipio/list/deptos').then(res=>{
            setDeptos(res.data);
            setLoader(false);
        }) 
    }, []); 

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>

                <Grid item xl={2} md={2} sm={12} xs={12}>
                    <TextValidator
                        name={'codigo'}
                        value={formData.codigo}
                        label={'Código'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={12} xs={12}>
                    <SelectValidator
                        name={'depto'}
                        value={formData.depto}
                        label={'Departamento'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {deptos.map(res=>{
                            return <MenuItem value={res.depaid} key={res.depaid} >{res.depanombre}</MenuItem>
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
                        inputProps={{autoComplete: 'off', maxLength: 80}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid> 

                <Grid item xl={2} md={2} sm={12} xs={12}>
                    <SelectValidator
                        name={'hacePresencia'}
                        value={formData.hacePresencia}
                        label={'Hace presencia'}
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
                        startIcon={<SaveIcon />}> {"Actualizar"}
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
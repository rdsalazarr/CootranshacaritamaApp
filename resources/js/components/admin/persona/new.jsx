import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack } from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../layout/instance';

export default function New({data, tipo}){ 

    const [formData, setFormData] = useState( 
                    (tipo !== 'I') ? {id:data.depeid, codigo:data.depecodigo, sigla: data.depesigla, nombre: data.depenombre, correo: data.depecorreo, 
                                    jefe: data.depejefeid, estado: data.depeactiva, tipo:tipo 
                                    } : {id:'000', codigo:'', sigla:'', nombre: '', correo: '', jefe:'', estado: '1', tipo:tipo
                                });
                                
    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [listaJefes, setListaJefes] = useState([]);
   
    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleInputChange = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    };

    const handleSubmit = () =>{
        setLoader(true); 
        instance.post('/admin/persona/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({id:'000', codigo:'', sigla:'', nombre: '', correo: '', jefe:'', estado: '1', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    useEffect(()=>{
        setLoader(true);
        instance.get('/admin/persona/listar/datos').then(res=>{
            setListaJefes(res.tipoidentificaciones);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>
                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <SelectValidator
                        name={'jefe'}
                        value={formData.jefe}
                        label={'Jefe de la dependencia'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {listaJefes.map(res=>{
                            return <MenuItem value={res.persid} key={res.persid} >{res.nombreJefe}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'codigo'}
                        value={formData.codigo}
                        label={'Código'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 10}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator
                        name={'sigla'}
                        value={formData.sigla}
                        label={'Sigla'}
                        className={'inputGeneral'}
                        variant={"standard"}
                        inputProps={{autoComplete: 'off', maxLength: 3}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleInputChange}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <TextValidator
                        name={'nombre'}
                        value={formData.nombre}
                        label={'Nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleInputChange}
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
                        type={"email"}
                        onChange={handleChange}
                    />
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
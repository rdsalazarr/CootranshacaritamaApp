import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack}  from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {id: data.susedoid,codigo: data.susedocodigo, nombre: data.susedonombre, serie: data.serdocid.toString(), 
                                     tipoDocumento: data.tipdocid.toString(),  permiteEliminar: data.susedopermiteeliminar, estado: data.susedoactiva, tipo:tipo 
                                    } : {id:'000', codigo:'', nombre:'', serie:'', tipoDocumento:'', permiteEliminar:'',  estado:'1', tipo:tipo
                                });

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [seriedocumentales, setSeriedocumentales] = useState([]);
    const [tipoDocumentales, setTipoDocumentales] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/subSerieDocumental/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({id:'000', codigo:'', nombre:'', serie:'', tipoDocumento:'', permiteEliminar:'',  estado:'1', tipo:tipo}) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/subSerieDocumental/listar/datos').then(res=>{
            setSeriedocumentales(res.seriedocumentales);
            setTipoDocumentales(res.tipoDocumentales);
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

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <TextValidator 
                        name={'codigo'}
                        value={formData.codigo}
                        label={'Código'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required","maxNumber:99"]}
                        errorMessages={["Campo obligatorio","Número máximo permitido es el 99"]}
                        type={"number"}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={10} md={10} sm={6} xs={12}>
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

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <SelectValidator
                        name={'serie'}
                        value={formData.serie}
                        label={'Serie documental'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>                 
                        {seriedocumentales.map(res=>{                         
                           return <MenuItem key={res.serdocid} value={res.serdocid} >{res.serdocnombre} </MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <SelectValidator
                        name={'tipoDocumento'}
                        value={formData.tipoDocumento}
                        label={'Tipo documental'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoDocumentales.map(res=>{
                           return <MenuItem key={res.tipdocid} value={res.tipdocid}>{res.tipdocnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <SelectValidator
                        name={'permiteEliminar'}
                        value={formData.permiteEliminar}
                        label={'¿Permite eliminar?'}
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
import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import NumberValidator from '../../../../layout/numberValidator';
import { Button, Grid, MenuItem, Stack}  from '@mui/material';
import showSimpleSnackbar from '../../../../layout/snackBar';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function New(){

    const [formData, setFormData]     = useState({entidadFinaciera:'', monto: '', descripcion: ''});
    const [entidadFinancieras, setEntidadFinancieras] = useState([]);
    const [loader, setLoader]         = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
      
    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/caja/registrar/consignacion/bancaria', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null; 
            (res.success) ? setFormData({entidadFinaciera:'', monto: '', descripcion: ''}) : null;
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/caja/consultar/datos/consignacion/bancaria').then(res=>{
            setEntidadFinancieras(res.entidadFinancieras);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            
            <Grid container spacing={2}>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <SelectValidator
                        name={'entidadFinaciera'}
                        value={formData.entidadFinaciera}
                        label={'Entidad finaciera'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {entidadFinancieras.map(res=>{
                            return <MenuItem value={res.entfinid} key={res.entfinid} >{res.entfinnombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <NumberValidator fullWidth
                        id={"monto"}
                        name={"monto"}
                        label={"Monto"}
                        value={formData.monto}
                        type={'numeric'}
                        require={['required', 'maxStringLength:9']}
                        error={['Campo obligatorio','Número máximo permitido es el 999999999']}
                        onChange={handleChange}
                    />
                </Grid> 

                <Grid item xl={6} md={6} sm={12} xs={12}>
                    <TextValidator 
                        name={'descripcion'}
                        value={formData.descripcion}
                        label={'Descripción'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 200}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>                
                
            </Grid>

            <Grid container direction="row" justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> Guardar
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
import React, {useState} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack}  from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

ValidatorForm.addValidationRule('isTasaNominal', (value) => {
    // Verificar si el valor es un número válido en formato "10.50"
    const regex = /^\d+(\.\d{1,2})?$/;
    if (!regex.test(value)) {
      return false;
    }
  
    // Verificar si el número está en el rango de 0 a 100 (porcentaje válido)
    const numValue = parseFloat(value);
    return numValue >= 0 && numValue <= 100;
});

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo: data.lincreid, nombre: data.lincrenombre, tasaNominal: data.lincretasanominal, montoMinimo: data.lincremontominimo,
                                       montoMaximo: data.lincremontomaximo, plazoMaximo: data.lincreplazomaximo, estado: data.lincreactiva, tipo:tipo 
                                    } : {codigo:'000', nombre: '', tasaNominal: '',  montoMinimo: '', montoMaximo: '', plazoMaximo:'', estado: '1', tipo:tipo
                                });

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeUpperCase = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}));
    }

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/cartera/linea/credito/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', nombre: '', tasaNominal: '',  montoMinimo: '', montoMaximo: '', plazoMaximo:'', estado: '1', tipo:tipo}) : null;

            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            
            <Grid container spacing={2}>
                <Grid item xl={9} md={9} sm={6} xs={12}>
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
                        name={'tasaNominal'}
                        value={formData.tasaNominal}
                        label={'Tasa nominal'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required", 'isTasaNominal']}
                        errorMessages={["Campo obligatorio", 'Ingrese un tasa nominal válida']}
                        onChange={handleChange}
                    />
                </Grid> 

               <Grid item xl={3} md={3} sm={6} xs={12}>
                    <NumberValidator fullWidth
                        id={"montoMinimo"}
                        name={"montoMinimo"}
                        label={"Monto mínimo"}
                        value={formData.montoMinimo}
                        type={'numeric'}
                        require={['required', 'maxStringLength:5']}
                        error={['Campo obligatorio','Número máximo permitido es el 99999']}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <NumberValidator fullWidth
                        id={"montoMaximo"}
                        name={"montoMaximo"}
                        label={"Monto máximo"}
                        value={formData.montoMaximo}
                        type={'numeric'}
                        require={['required', 'maxStringLength:8']}
                        error={['Campo obligatorio','Número máximo permitido es el 99999999']}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'plazoMaximo'}
                        value={formData.plazoMaximo}
                        label={'Plazo máximo (En meses)'}
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
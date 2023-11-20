import React, {useState} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import NumberValidator from '../../../../layout/numberValidator';
import { Button, Grid, MenuItem, Stack}  from '@mui/material';
import showSimpleSnackbar from '../../../../layout/snackBar';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo: data.timoveid, nombre: data.timovenombre, cuotaSostenimiento: data.timovecuotasostenimiento, 
                                    descuentoPagoAnticipado: data.timovedescuentopagoanticipado, recargoMora: data.timoverecargomora,
                                    tieneDespacho: data.timovetienedespacho, tipo:tipo 
                                    } : {codigo:'000', nombre: '',  cuotaSostenimiento: '', descuentoPagoAnticipado: '', recargoMora: '', tieneDespacho: '1', tipo:tipo
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
        instance.post('/admin/direccion/transporte/modalidad/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', nombre: '',  cuotaSostenimiento: '', descuentoPagoAnticipado: '', recargoMora: '', tieneDespacho: '1', tipo:tipo}) : null;

            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            
            <Grid container spacing={2}>
                <Grid item xl={9} md={9} sm={12} xs={12}>
                    <TextValidator 
                        name={'nombre'}
                        value={formData.nombre}
                        label={'Nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 30}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <NumberValidator fullWidth
                        id={"cuotaSostenimiento"}
                        name={"cuotaSostenimiento"}
                        label={"Cuota de sostenimiento"}
                        value={formData.cuotaSostenimiento}
                        type={'numeric'}
                        require={['required', 'maxStringLength:8']}
                        error={['Campo obligatorio','Número máximo permitido es el 99999999']}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={5} md={5} sm={6} xs={12}>
                    <TextValidator 
                        name={'descuentoPagoAnticipado'}
                        value={formData.descuentoPagoAnticipado}
                        label={'Descuento por pago annticipado (%)'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        type={'numeric'}
                    />
                </Grid>

                <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator
                        name={'recargoMora'}
                        value={formData.recargoMora}
                        label={'Recargo por mora (%)'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                        type={'numeric'}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={4} xs={12}>
                    <SelectValidator
                        name={'tieneDespacho'}
                        value={formData.tieneDespacho}
                        label={'¿Tiene despacho?'}
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
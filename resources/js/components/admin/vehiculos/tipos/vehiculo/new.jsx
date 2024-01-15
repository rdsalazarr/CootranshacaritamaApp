import React, {useState} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack}  from '@mui/material';
import showSimpleSnackbar from '../../../../layout/snackBar';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo: data.tipvehid, nombre: data.tipvehnombre, referencia: data.tipvehreferencia, capacidadPasajero: data.tipvehcapacidad,
                                        numeroFilas: data.tipvehnumerofilas, numeroColumnas: data.tipvehnumerocolumnas,  estado: data.tipvehactivo, tipo:tipo 
                                    } : {codigo:'000', nombre: '', referencia:'', capacidadPasajero:'', numeroFilas:'', numeroColumnas:'', estado: '1', tipo:tipo
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
        instance.post('/admin/direccion/transporte/tipo/salve', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', nombre: '', referencia:'', capacidadPasajero:'', numeroFilas:'', numeroColumnas:'', estado: '1', tipo:tipo}) : null;

            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            
            <Grid container spacing={2}>
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
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={12}>
                    <TextValidator 
                        name={'referencia'}
                        value={formData.referencia}
                        label={'Referencia'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 30}}
                        onChange={handleChangeUpperCase}
                    />
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    <TextValidator 
                        name={'capacidadPasajero'}
                        value={formData.capacidadPasajero}
                        label={'Capacidad de pasajero'}
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
                    <TextValidator 
                        name={'numeroFilas'}
                        value={formData.numeroFilas}
                        label={'Número de filas'}
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
                    <TextValidator 
                        name={'numeroColumnas'}
                        value={formData.numeroColumnas}
                        label={'Número de filas'}
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
import React, {useState} from 'react';
import ArrowForwardIosIcon from '@mui/icons-material/ArrowForwardIos';
import RegistrarMovimientos from "../movimiento/registrarMovimientos";
import { ValidatorForm } from 'react-material-ui-form-validator';
import { Button, Box, Grid, Stack, Card } from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {FormatearNumero} from "../../../layout/general";
import LockOpenIcon from '@mui/icons-material/LockOpen';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';

export default function AbrirCaja({saldoAnterior, usuario, caja}){
    const [formData, setFormData] = useState({saldoInicial: (saldoAnterior === 0 || saldoAnterior === null) ? '' : saldoAnterior.toString()});
    const [valorSaldoAnterior, setValorSaldoAnterior] = useState(FormatearNumero({numero: saldoAnterior}));
    const [cajaAbierta, setCajaAbierta] = useState(false);  
    const [continuar, setContinuar] = useState(false);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    } 

    const handleSubmit = () =>{
        setLoader(true);
        instance.post('/admin/caja/abrir/dia', formData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setCajaAbierta(true) : null;
            setLoader(false);
        })
    }

    const continuarApertura = () =>{
        setContinuar(true);
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            {(cajaAbierta) ? <RegistrarMovimientos />
                :
                <Box className={'containerMedium'}>
                    <Card className={'cardContainer'}>
                        {(!continuar) ?
                            <Grid container spacing={2}>
                                <Grid item xl={12} md={12} sm={12} xs={12}>
                                    <p>Hemos notado que, para la fecha de hoy, usted, <b>{usuario}</b>, no ha abierto una caja. ¿Desea continuar con el proceso y abrir una caja ahora?</p>
                                    <Stack direction="row" spacing={2} style={{float: 'right'}}>
                                        <Button type={"button"} className={'modalBtn'} onClick={() => {continuarApertura();}}
                                            startIcon={<ArrowForwardIosIcon />}> Continuar
                                        </Button>
                                    </Stack>
                                </Grid>
                            </Grid>
                        : 
                            <ValidatorForm onSubmit={handleSubmit}>
                                <Grid container spacing={2}>
                                    <Grid item xl={3} md={3} sm={6} xs={12}>
                                        <Box className='frmTextoColor'>
                                            <label>Saldo anterior: $  </label>
                                            <span className='textoRojo'>{'\u00A0'+ (saldoAnterior !== null) ? valorSaldoAnterior : 0}</span>
                                        </Box>
                                    </Grid>

                                    <Grid item xl={3} md={3} sm={6} xs={12}>
                                        <Box className='frmTextoColor'>
                                            <label>Caja número: </label>
                                            <span className='textoRojo'>{'\u00A0'+ caja}</span>
                                        </Box>
                                    </Grid>

                                    <Grid item xl={3} md={3} sm={6} xs={12}>
                                        <NumberValidator fullWidth
                                            id={"saldoInicial"}
                                            name={"saldoInicial"}
                                            label={"Saldo inicial"}
                                            value={formData.saldoInicial}
                                            type={'numeric'}
                                            require={['required', 'maxStringLength:9']}
                                            error={['Campo obligatorio','Número máximo permitido es el 999999999']}
                                            onChange={handleChange}
                                        />
                                    </Grid>

                                    <Grid item xl={3} md={3} sm={6} xs={12}>
                                        <Stack direction="row" spacing={2}>
                                            <Button type={"submit"} className={'modalBtn'} 
                                                startIcon={<LockOpenIcon />}> Abrir caja
                                            </Button>
                                        </Stack>
                                    </Grid>

                                </Grid>
                            </ValidatorForm>
                        }
                    </Card>
                </Box>
            }
        </Box>
    )
}
import React, {useState, useEffect, Fragment, useRef} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Icon,Table, TableHead, TableBody, TableRow, TableCell } from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';


export default function List(){

    const [formData, setFormData] = useState( 
        {codigo: '',     tipoMedio: '',    tipoTramite: '1', 
                tipoDestino: '1',  fecha: '',              tipoActa: '',          correo: '',           horaInicial: '',  horaFinal: '',  
                lugar: '',         convocatoria: '0',      asistentes: '',        invitados: '',        ausentes: '',     ordenDia: '', 
                contenido: '',     convocatoriaLugar: '',  convocatoriaFecha: '', convocatoriaHora: '', quorum: ''
        }); 

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleInputChange = (e) => {
        setFormData(prev => ({...prev, [e.target.name]: e.target.value.toUpperCase()}))
    };

    const handleSubmit = () =>{

    }

    return ( 
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2} style={{display: 'flex',  justifyContent: 'space-between'}}>
                <Grid item xs={12} sm={12} md={12} xl={12}>  
                    <Box className='subTituloFormulario'>
                        Información del remitente
                    </Box>
                </Grid>

                <Grid item xl={5} md={5} sm={6} xs={12}>
                    <TextValidator 
                        multiline
                        maxRows={3}
                        name={'lugar'}
                        value={formData.lugar}
                        label={'Lugar'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 200}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>
            </Grid>
        </ValidatorForm>
    )
}
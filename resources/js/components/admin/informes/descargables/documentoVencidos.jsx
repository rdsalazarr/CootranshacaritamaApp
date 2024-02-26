import React, {useState} from 'react';
import { SelectValidator, ValidatorForm} from 'react-material-ui-form-validator';
import { Button, Grid, Box, Stack, Card, MenuItem} from '@mui/material';
import DownloadingIcon from '@mui/icons-material/Downloading';
import instanceFile from '../../../layout/instanceFile';
import {LoaderModal} from "../../../layout/loader";

export default function DocumentoVehiculo(){

    const [formData, setFormData] = useState({fechaInicial:'', fechaFinal:''});
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/documento/vencidos/vehiculos', formData).then(res=>{
            setLoader(false);
        })
    } 

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Box className={'containerSmall'}>
                <Card className={'cardContainer'}>
                    <Grid container spacing={2}>
                        <Grid item xl={8} md={8} sm={12} xs={12}>
                            <SelectValidator
                            name={'tipoReporte'}
                            value={formData.tipoReporte}
                            label={'Tipo de reporte'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Campo obligatorio"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione </MenuItem>
                            <MenuItem value={"SOAT"}>SOAT </MenuItem>
                            <MenuItem value={"CRT"}>CRT </MenuItem>
                            <MenuItem value={"TARJETAOPEARCION"}>Tarjeta de operación </MenuItem>                      
                        </SelectValidator>

                        </Grid>                  
                        <Grid item xl={4} md={4} sm={6} xs={12} >
                            <Stack direction="row" spacing={2} style={{ float:'right'}}>
                                <Button type={"submit"} className={'modalBtnIcono'}
                                    startIcon={<DownloadingIcon className='icono'/>}> Descargar
                                </Button>
                            </Stack>
                        </Grid>
                    </Grid>
                </Card>
            </Box>

        </ValidatorForm>
    )
}
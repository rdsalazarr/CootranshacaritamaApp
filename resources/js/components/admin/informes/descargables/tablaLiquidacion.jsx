import React, {useState, useEffect} from 'react';
import { Button, Grid, Box, Stack, Card,Autocomplete, createFilterOptions,} from '@mui/material';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import DownloadingIcon from '@mui/icons-material/Downloading';
import instanceFile from '../../../layout/instanceFile';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';

export default function MovimientoCaja(){

    const [formData, setFormData] = useState({colocacionId:''});
    const [colocaciones, setColocaciones] = useState([]);
    const [loader, setLoader] = useState(false); 

    const handleSubmit = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/tabla/liquidacion/credito', formData).then(res=>{
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/informes/descargable/list/tabla/liquidacion').then(res=>{
            setColocaciones(res.colocaciones);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);


    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit}>
            <Box className={'containerSmall'}>
                <Card className={'cardContainer'}>
                    <Grid container spacing={2}>
                        <Grid item xl={9} md={9} sm={12} xs={12}>
                            <Autocomplete
                                id="colocacionId"
                                style={{height: "26px", width: "100%"}}
                                options={colocaciones}
                                getOptionLabel={(option) => option.nombrePersona} 
                                value={colocaciones.find(v => v.coloid === formData.colocacionId) || null}
                                filterOptions={createFilterOptions({ limit:10 })}
                                onChange={(event, newInputValue) => {
                                    if(newInputValue){
                                        setFormData({...formData, colocacionId: newInputValue.coloid})
                                    }
                                }}
                                renderInput={(params) =>
                                    <TextValidator {...params}
                                        label="Consultar colocación"
                                        className="inputGeneral"
                                        variant="standard"
                                        validators={["required"]}
                                        errorMessages="Campo obligatorio"
                                        value={formData.colocacionId}
                                        placeholder="Consulte la colocación aquí..." />}
                            />
                        </Grid>
                        <Grid item xl={3} md={3} sm={12} xs={12} >
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
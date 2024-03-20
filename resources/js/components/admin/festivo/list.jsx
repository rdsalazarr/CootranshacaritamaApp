import React, {useState, useEffect } from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import { Button, Grid, Card, Typography, Stack, Box,Table, TableHead, TableBody, TableRow, TableCell, Icon } from '@mui/material';
import showSimpleSnackbar from '../../layout/snackBar';
import PostAddIcon from '@mui/icons-material/PostAdd';
import SaveIcon from '@mui/icons-material/Save';
import {LoaderModal} from "../../layout/loader";
import instance from '../../layout/instance';

export default function List(){

    const [formData, setFormData] = useState({codigo:'000', fecha: '', estado: 'I'});
    const [festivos, setFestivos] = useState([]);
    const [formDataSalve, setFormDataSalve] = useState([]);
    const [loader, setLoader] = useState(false);
    
    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }
 
    const handleSubmit = () =>{
        if(formDataSalve.length === 0){
            showSimpleSnackbar('Debe por lo menos registra una fecha', 'error');
            return
        }

        let newFormData = {...formData}
        newFormData.fechas = formDataSalve;

        setLoader(true);
        instance.post('/admin/festivo/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? inicio() : null; 
            (formData.tipo === 'I' && res.success) ? setFormDataSalve([]) : null;
            setLoader(false);
        })
    }

    const adicionarFila = () =>{

        if(festivos.some((festivo) => festivo.festfecha === formData.fecha)){
            showSimpleSnackbar('Este registro ya existe', 'error');
            return
        }

        let newFormDataSalve = [...formDataSalve];
        newFormDataSalve.push({codigo: formData.codigo, fecha:formData.fecha,  estado: 'I'});
        setFormDataSalve(newFormDataSalve);
        setFormData({codigo:'000', fecha: '', estado: 'I'})
    }

    const eliminarFirma = (id) =>{
        let newFormDataSalve = []; 
        formDataSalve.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newFormDataSalve.push({ codigo:res.codigo, fecha: res.fecha, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newFormDataSalve.push({codigo:res.codigo,  fecha: res.fecha, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newFormDataSalve.push({codigo:res.codigo, fecha: res.fecha, estado:res.estado});
            }else{
                if(i != id){
                    newFormDataSalve.push({codigo:res.codigo, fecha: res.fecha, estado: 'I' });
                }
            }
        })
        setFormDataSalve(newFormDataSalve);
    }

    const inicio = () =>{
        let newFormDataSalve = []; 
        setLoader(true);
        instance.get('/admin/festivo/list').then(res=>{
            let festivos = res.data;
            festivos.forEach(function(frm){
                newFormDataSalve.push({codigo: frm.festid, fecha:frm.festfecha, estado: 'U'});
            });
            setFormDataSalve(newFormDataSalve);
            setFestivos(festivos);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []); 
    
    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'containerSmall'} >
            <Card className={'cardContainer'} >
                <Box><Typography component={'h2'} className={'titleGeneral'} style={{marginBottom: '1em'}}>Gestionar festivos</Typography>
                </Box>

                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    
                    <ValidatorForm onSubmit={adicionarFila} >
                        <Grid container spacing={2}>
                            <Grid item xl={9} md={9} sm={8} xs={12}>
                                <TextValidator 
                                    name={'fecha'}
                                    value={formData.fecha}
                                    label={'Fecha del festivo'}
                                    className={'inputGeneral'} 
                                    variant={"standard"} 
                                    inputProps={{autoComplete: 'off'}}
                                    validators={["required"]}
                                    errorMessages={["Campo obligatorio"]}
                                    onChange={handleChange}
                                    type={"date"}
                                    InputLabelProps={{
                                        shrink: true,
                                    }}
                                />
                            </Grid>

                            <Grid item xl={2} md={2} sm={4} xs={12}>
                                <Button type={"submit"} className={'modalBtnIcono'}
                                    startIcon={<PostAddIcon className='icono' />}> {"Adicionar"}
                                </Button>
                            </Grid>
                        </Grid>
                    </ValidatorForm>
                
                {(formDataSalve.length > 0) ?
                    <Box>
                        <Grid container spacing={2}>
                            <Grid item xs={12} sm={12} md={12} xl={12} >
                                <Box className='divisionFormulario'>
                                    Festivos registrados
                                </Box>
                            </Grid>

                            <Grid item xl={12} md={12} sm={12} xs={12}>
                                <Table key={'tableFecha'}  className={'tableAdicional'} style={{marginTop: '1px'}} >
                                    <TableHead>
                                        <TableRow>
                                            <TableCell>Fecha</TableCell>
                                            <TableCell style={{width: '10%'}} className='cellCenter'>Eliminar </TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                    { formDataSalve.map((frmSalve, a) => {
                                        return(
                                            <TableRow key={'rowA-' +a} className={(frmSalve.estado == 'D')? 'tachado': null}>
                                                <TableCell>
                                                    <p>{frmSalve['fecha']}</p> 
                                                </TableCell>
                                              
                                                <TableCell className='cellCenter'>
                                                    <Icon key={'iconDelete'+a} className={'icon top red'}
                                                        onClick={() => {eliminarFirma(a);}} title={'Eliminar'}
                                                    >clear</Icon>
                                                </TableCell>
                                            </TableRow>
                                            );
                                        })
                                    }
                                    </TableBody>
                                </Table>
                            </Grid>
                        </Grid>

                        <Grid container direction="row" justifyContent="right" style={{marginTop: '1em'}} >
                            <Stack direction="row" spacing={2}>
                                <Button type={"button"} className={'modalBtn'} onClick={() => {handleSubmit();}}
                                    startIcon={<SaveIcon />}> {"Guardar" }
                                </Button>
                            </Stack>
                        </Grid>
                    </Box>
                : null }
                </Box>
            </Card>
        </Box>
    )
}
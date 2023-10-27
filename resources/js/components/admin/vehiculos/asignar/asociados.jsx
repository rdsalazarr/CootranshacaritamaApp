import React, {useState, useEffect, Fragment} from 'react';
import {Button, Grid, Icon, Box, MenuItem, Stack, Table, TableHead, TableBody, TableRow, TableCell, Card, Autocomplete, createFilterOptions} from '@mui/material';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import AddIcon from '@mui/icons-material/Add';

export default function Asociados({id}){

    const [formData, setFormData] = useState({asociado:[], vehiculo:id})
    const [loader, setLoader] = useState(false);
    const [listaAsocidados, setListaAsocidados] = useState([]);
    const [formDataAdicionar, setFormDataAdicionar] = useState({asociado:''});
    const [asocidos, setAsociados] = useState([]);

    const handleChange = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeAdicionar = (e) =>{
        setFormDataAdicionar(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const adicionarFilaAsociado = () =>{

        if(formDataAdicionar.asociado === ''){
            showSimpleSnackbar('Debe seleccionar un asocido', 'error');
            return
        }

        if(asocidos.some(pers => pers.asocid == formDataAdicionar.asociado)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newAsocidos = [...asocidos]; 
        newAsocidos.push({identificador:'', asociado:formDataAdicionar.asociado, estado: 'I'});
        setFormDataAdicionar({asociado:''});
        setAsociados(newAsocidos);
    } 

    const consultarAsociado = () =>{     

        let newFormData = {...formData}
        setDatosEncontrados(false);
        instance.post('/admin/direccion/transporte/asociados/salve', formData).then(res=>{
            if(!res.success){
                showSimpleSnackbar(res.message, 'error');
            }else{
                let tipoEstadoAsociado = [];
                let idEstadoActual     = res.data.tiesasid;
                newFormData.asociadoId = res.data.asocid;

                tipoEstadosAsociados.forEach(function(tpEstado){ 
                    if(tpEstado.tiesasid !== idEstadoActual){
                        tipoEstadoAsociado.push({
                            tiesasid: tpEstado.tiesasid,
                            tiesasnombre: tpEstado.tiesasnombre
                        });
                    }
                });
                setNewTipoEstadosAsociados(tipoEstadoAsociado);
                setDatosEncontrados(true);
                setFormData(newFormData);
                setData(res.data);
            }
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/direccion/transporte/listar/asociados').then(res=>{
            setListaAsocidados(res.asociados);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={consultarAsociado}>               
            <Box className={'containerSmall'}>
                <Card className={'cardContainer'}>
                    <Grid container spacing={2}>

                        <Grid item xl={10} md={10} sm={8} xs={12}>
                            <Autocomplete
                                id="asociado"
                                style={{height: "26px"}}
                                options={listaAsocidados}
                                freeSolo
                                getOptionLabel={(option) => option.nombrePersona} 
                                value={listaAsocidados.find(v => v.asocid === formDataAdicionar.asociado) || null}
                                filterOptions={createFilterOptions({ limit:10 })}
                                onChange={(event, newInputValue) => {
                                    if(newInputValue){
                                        setFormDataAdicionar({...formDataAdicionar, asociado: newInputValue.asocid})
                                    }
                                }}
                                renderInput={(params) =>
                                    <TextValidator {...params}
                                        label="Consultar asociado"
                                        className="inputGeneral"
                                        variant="standard"
                                        validators={["required"]}
                                        errorMessages="Campo obligatorio"
                                        value={formDataAdicionar.asociado}
                                        placeholder="Consulte el asociado aquí..." />}
                            />
                        </Grid>

                        <Grid item xl={2} md={2} sm={4} xs={12}>
                            <Button type={"button"} className={'modalBtn'} 
                                startIcon={<AddIcon />} onClick={() => {adicionarFilaAsociado()}}> {"Agregar"}
                            </Button>
                        </Grid>

                    </Grid>
                </Card>
            </Box>
        </ValidatorForm>
    )
}

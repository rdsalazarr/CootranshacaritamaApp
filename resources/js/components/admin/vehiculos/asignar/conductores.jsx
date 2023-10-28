import React, {useState, useEffect, Fragment} from 'react';
import {Button, Grid, Icon, Box, Stack, Table, TableHead, TableBody, TableRow, TableCell, Card, Autocomplete, createFilterOptions} from '@mui/material';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import VisibilityIcon from '@mui/icons-material/Visibility';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import AddIcon from '@mui/icons-material/Add';
import ShowPersona from '../../persona/show';

export default function Conductors({id}){

    const [formData, setFormData] = useState({conductorId:'', vehiculo:id})
    const [loader, setLoader] = useState(false);
    const [listaConductores, setListaConductores] = useState([]);
    const [conductores, setConductores] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [modal, setModal] = useState({open: false, idPersona:''});

    const adicionarFilaConductor = () =>{
        if(formData.conductorId === ''){
            showSimpleSnackbar('Debe seleccionar un conductor', 'error');
            return
        }
        if(conductores.some(pers => pers.condid == formData.conductorId)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }
        let newConductores        = [...conductores]; 
        const resultLisConductor = listaConductores.filter((asoc) => asoc.condid == formData.conductorId);
        newConductores.push({identificador:'', conductorId:formData.conductorId, nombreConductor:resultLisConductor[0].nombrePersona, personaId:resultLisConductor[0].persid, estado: 'I'});
        setFormData({conductorId:'', vehiculo:id});
        setConductores(newConductores);
    }

    const eliminarFilaConductor = (id) =>{
        let newConductors = []; 
        conductores.map((res,i) =>{
           if(res.estado === 'U' && i === id){
                newConductors.push({ identificador:res.identificador, conductorId: res.conductorId, nombreConductor:res.nombreConductor, personaId:res.personaId, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newConductors.push({identificador:res.identificador, conductorId: res.conductorId, nombreConductor:res.nombreConductor, personaId:res.personaId, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newConductors.push({identificador:res.identificador, conductorId: res.conductorId, nombreConductor:res.nombreConductor, personaId:res.personaId, estado:res.estado});
            }else{
                if(i != id){
                    newConductors.push({identificador:res.identificador, conductorId: res.conductorId, nombreConductor:res.nombreConductor, personaId:res.personaId, estado: 'I' });
                }
            }
        })
        setConductores(newConductors);
    }

    const registrarConductores = () =>{
        let newFormData         = {...formData}
        newFormData.conductores = conductores;
        setLoader(true);
        instance.post('/admin/direccion/transporte/conductores/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newConductores = []; 
        instance.post('/admin/direccion/transporte/listar/conductores', {vehiculoId: id}).then(res=>{
            res.conductoresVehiculo.map((res) =>{
                newConductores.push({identificador:res.asovehid, conductorId: res.condid, nombreConductor:res.nombrePersona, personaId: res.persid, estado: 'U'});
            })
            setConductores(newConductores);
            setListaConductores(res.conductores);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={registrarConductores} style={{marginTop:'1em'}}>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>
                            <Grid item xl={11} md={11} sm={10} xs={9}>
                                <Autocomplete
                                    id="conductorId"
                                    style={{height: "26px", width: "100%"}}
                                    options={listaConductores}
                                    getOptionLabel={(option) => option.nombrePersona} 
                                    value={listaConductores.find(v => v.condid === formData.conductorId) || null}
                                    filterOptions={createFilterOptions({ limit:10 })}
                                    onChange={(event, newInputValue) => {
                                        if(newInputValue){
                                            setFormData({...formData, conductorId: newInputValue.condid})
                                        }
                                    }}
                                    renderInput={(params) =>
                                        <TextValidator {...params}
                                            label="Consultar conductor"
                                            className="inputGeneral"
                                            variant="standard"
                                            validators={["required"]}
                                            errorMessages="Campo obligatorio"
                                            value={formData.conductorId}
                                            placeholder="Consulte el conductor aquí..." />}
                                />
                                <br />
                            </Grid>

                            <Grid item xl={1} md={1} sm={2} xs={3} sx={{position: 'relative'}}>
                                <AddIcon className={'iconLupa'} onClick={() => {adicionarFilaConductor()}} ></AddIcon>
                                <br />
                            </Grid>

                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm> 

            {(conductores.length > 0) ?
                <Box className={'containerSmall'}>
                    <Grid container spacing={2} style={{marginTop:'1em'}}>
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Table className={'tableAdicional'} >
                                <TableHead>
                                    <TableRow>
                                        <TableCell>Nombre completo del conductor asignado al vehículo</TableCell>
                                        <TableCell style={{width: '5%'}} className='cellCenter'>Visualizar </TableCell>
                                        <TableCell style={{width: '5%'}} className='cellCenter'>Eliminar </TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>
                                { conductores.map((asoc, a) => {
                                    return(
                                        <TableRow key={'rowD-' +a} className={(asoc['estado'] == 'D')? 'tachado': null}>
                                            <TableCell>
                                                <p>{asoc['nombreConductor']} </p>
                                            </TableCell>

                                            <TableCell className='cellCenter'> 
                                                <VisibilityIcon key={'iconDelete'+a} className={'icon top green'}
                                                        onClick={() => {setModal({open: true, idPersona: asoc['personaId']});}}
                                                ></VisibilityIcon>
                                            </TableCell>

                                            <TableCell className='cellCenter'>
                                                <Icon key={'iconDelete'+a} className={'icon top red'}
                                                        onClick={() => {eliminarFilaConductor(a);}}
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

                    <Grid container direction="row"  justifyContent="right" style={{marginTop:'1em'}}>
                        <Stack direction="row" spacing={2}>
                            <Button type={"button"} onClick={() => {registrarConductores();}}  className={'modalBtn'} disabled={(habilitado) ? false : true}
                                startIcon={<SaveIcon />}> {"Guardar"}
                            </Button>
                        </Stack>
                    </Grid>
                </Box>
            : null}

            <ModalDefaultAuto
                title={'Visualizar información del conductor'}
                content={<ShowPersona id={modal.idPersona} frm={'CONDUCTOR'} />}
                close={() =>{setModal({open : false})}}
                tam = {'bigFlot'}
                abrir ={modal.open}
            />

        </Fragment>
    )
}
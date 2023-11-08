import React, {useState, useEffect, Fragment} from 'react';
import {Button, Grid, Icon, Box, Stack, Table, TableHead, TableBody, TableRow, TableCell, Card, Autocomplete, createFilterOptions} from '@mui/material';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import PictureAsPdfIcon from '@mui/icons-material/PictureAsPdf';
import VisibilityIcon from '@mui/icons-material/Visibility';
import showSimpleSnackbar from '../../../layout/snackBar';
import { ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import AddIcon from '@mui/icons-material/Add';
import ShowPersona from '../../persona/show';
import VisualizarPdf from './visualizarPdf';

export default function Asociados({id}){

    const [formData, setFormData] = useState({asociadoId:'', vehiculo:id})
    const [loader, setLoader] = useState(false);
    const [listaAsocidados, setListaAsocidados] = useState([]); 
    const [asociados, setAsociados] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [modal, setModal] = useState({open: false, vista:2, idPersona:'', titulo: '', tamano:'mediumFlot'});

    const tituloModal = ['Visualizar información del asociado','Generar PDF del contrato'];
    const modales     = [
                            <ShowPersona id={modal.idPersona} frm={'ASOCIADO'} />,
                            <VisualizarPdf idPersona={modal.idPersona} vehiculoId={id} />
                        ];

    const edit = (tipo, id) =>{
       setModal({open: true, vista: tipo, idPersona:id, titulo: tituloModal[tipo], tamano: (tipo === 0 ) ? 'bigFlot' :  'mediumFlot'});
    }

    const adicionarFilaAsociado = () =>{
        if(formData.asociadoId === ''){
            showSimpleSnackbar('Debe seleccionar un asociado', 'error');
            return
        }
        if(asociados.some(pers => pers.asocid == formData.asociadoId)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }
        let newAsociados        = [...asociados]; 
        const resultLisAsociado = listaAsocidados.filter((asoc) => asoc.asocid == formData.asociadoId);
        newAsociados.push({identificador:'', asociadoId:formData.asociadoId, nombreAsociado:resultLisAsociado[0].nombrePersona, personaId:resultLisAsociado[0].persid, estado: 'I'});
        setFormData({asociadoId:'', vehiculo:id});
        setAsociados(newAsociados);
    }

    const eliminarFilaAsociado = (id) =>{
        let newAsociados = []; 
        asociados.map((res,i) =>{
           if(res.estado === 'U' && i === id){
                newAsociados.push({ identificador:res.identificador, asociadoId: res.asociadoId, nombreAsociado:res.nombreAsociado, personaId:res.personaId, estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newAsociados.push({identificador:res.identificador, asociadoId: res.asociadoId, nombreAsociado:res.nombreAsociado, personaId:res.personaId, estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newAsociados.push({identificador:res.identificador, asociadoId: res.asociadoId, nombreAsociado:res.nombreAsociado, personaId:res.personaId, estado:res.estado});
            }else{
                if(i != id){
                    newAsociados.push({identificador:res.identificador, asociadoId: res.asociadoId, nombreAsociado:res.nombreAsociado, personaId:res.personaId, estado: 'I' });
                }
            }
        })
        setAsociados(newAsociados);
    }

    const registrarAsociados = () =>{
        let newFormData       = {...formData}
        newFormData.asociados = asociados;
        setLoader(true);
        instance.post('/admin/direccion/transporte/asociados/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newAsociados = []; 
        instance.post('/admin/direccion/transporte/listar/asociados', {vehiculoId: id}).then(res=>{
            res.asociadoVehiculos.map((res) =>{
                newAsociados.push({identificador:res.asovehid, asociadoId: res.asocid, nombreAsociado:res.nombrePersona, personaId: res.persid, estado: 'U'});
            })
            setAsociados(newAsociados);
            setListaAsocidados(res.asociados);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={registrarAsociados} style={{marginTop:'1em'}}>
                <Box className={'containerSmall'}>
                    <Card className={'cardContainer'}>
                        <Grid container spacing={2}>
                            <Grid item xl={11} md={11} sm={10} xs={9}>
                                <Autocomplete
                                    id="asociadoId"
                                    style={{height: "26px", width: "100%"}}
                                    options={listaAsocidados}
                                    getOptionLabel={(option) => option.nombrePersona} 
                                    value={listaAsocidados.find(v => v.asocid === formData.asociadoId) || null}
                                    filterOptions={createFilterOptions({ limit:10 })}
                                    onChange={(event, newInputValue) => {
                                        if(newInputValue){
                                            setFormData({...formData, asociadoId: newInputValue.asocid})
                                        }
                                    }}
                                    renderInput={(params) =>
                                        <TextValidator {...params}
                                            label="Consultar asociado"
                                            className="inputGeneral"
                                            variant="standard"
                                            validators={["required"]}
                                            errorMessages="Campo obligatorio"
                                            value={formData.asociadoId}
                                            placeholder="Consulte el asociado aquí..." />}
                                />
                                <br />
                            </Grid>

                            <Grid item xl={1} md={1} sm={2} xs={3} sx={{position: 'relative'}}>
                                <AddIcon className={'iconLupa'} onClick={() => {adicionarFilaAsociado()}} ></AddIcon>
                                <br />
                            </Grid>

                        </Grid>
                    </Card>
                </Box>
            </ValidatorForm>

            {(asociados.length > 0) ?
                <Box className={'containerSmall'}>
                    <Grid container spacing={2} style={{marginTop:'1em'}}>
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Table className={'tableAdicional'} >
                                <TableHead>
                                    <TableRow>
                                        <TableCell>Nombre completo del asociado asignado al vehículo</TableCell>
                                        <TableCell style={{width: '5%'}} className='cellCenter'>Visualizar </TableCell>
                                        <TableCell style={{width: '20%'}} className='cellCenter'>Ver contrato </TableCell>
                                        <TableCell style={{width: '5%'}} className='cellCenter'>Eliminar </TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>
                                { asociados.map((asoc, a) => {
                                    return(
                                        <TableRow key={'rowD-' +a} className={(asoc['estado'] == 'D')? 'tachado': null}>
                                            <TableCell>
                                                <p>{asoc['nombreAsociado']} </p>
                                            </TableCell>

                                            <TableCell className='cellCenter'>
                                                <VisibilityIcon key={'iconDelete'+a} className={'icon top green'}
                                                        onClick={() => {edit(0, asoc['personaId'])}}
                                                ></VisibilityIcon>
                                            </TableCell>

                                            <TableCell className='cellCenter'>
                                                <PictureAsPdfIcon key={'iconDelete'+a} className={'icon top green'}
                                                         onClick={() => {edit(1, asoc['personaId'])}}
                                                ></PictureAsPdfIcon>
                                            </TableCell>

                                            <TableCell className='cellCenter'>
                                                <Icon key={'iconDelete'+a} className={'icon top red'}
                                                        onClick={() => {eliminarFilaAsociado(a);}}
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
                            <Button type={"button"} onClick={() => {registrarAsociados();}}  className={'modalBtn'} disabled={(habilitado) ? false : true}
                                startIcon={<SaveIcon />}> {"Guardar"}
                            </Button>
                        </Stack>
                    </Grid>
                </Box>
            : null}

            <ModalDefaultAuto
                title   ={modal.titulo}
                content ={modales[modal.vista]}
                close   ={() =>{setModal({open : false})}}
                tam     ={modal.tamano}
                abrir   ={modal.open}
            />

        </Fragment>
    )
}
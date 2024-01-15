import React, {useState, useEffect} from 'react';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import {ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, Stack, Box, MenuItem, Typography, Card } from '@mui/material';
import showSimpleSnackbar from '../../../../layout/snackBar';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

const reorder = (list, startIndex, endIndex) => {
    const result = Array.from(list);
    const [removed] = result.splice(startIndex, 1);
    result.splice(endIndex, 0, removed);
    return result;
};

export default function Distribucion(){

    const [loader, setLoader] = useState(false);
    const [habilitado, setHabilitado] = useState(true);
    const [dataPuestos, setDataPuestos] = useState([]);
    const [tipoVehiculos, setTipoVehiculos] = useState([]);
    const [mostrarDatos, setMostrarDatos] = useState(false);
    const [existenDatos, setExistenDatos] = useState(false);
    const [formData, setFormData] = useState({tipoVehiculo: '', existenDatos: 'N'});
    const [claseDistribucionPuesto, setClaseDistribucionPuesto] = useState('distribucionPuestoGeneral');

    const handleDragEnd = (result) => {
        if (!result.destination) return;

        const { source, destination } = result;

        if (source.droppableId === destination.droppableId) {
            const items = reorder(
                dataPuestos[source.droppableId],
                source.index,
                destination.index
            );
            setDataPuestos({ ...dataPuestos, [source.droppableId]: items });
        } else {
            const sourceItems = [...dataPuestos[source.droppableId]];
            const destItems = [...dataPuestos[destination.droppableId]];

            const [movedItem] = sourceItems.splice(source.index, 1);
            destItems.splice(destination.index, 0, movedItem);

            setDataPuestos({
                ...dataPuestos,
                [source.droppableId]: sourceItems,
                [destination.droppableId]: destItems,
            });
        }
    }

    const handleSubmit = () =>{
        let puestosVehiculo = [];
        Object.keys(dataPuestos).forEach((clave, j) => {
            const filas = dataPuestos[clave];
            filas.forEach((elemento) => {
                puestosVehiculo.push({
                    fila:     j,
                    columna:  elemento.puestoColumna,
                    puesto:   elemento.contenido
                });
            });
        });

        setLoader(true);
        let newFormData             = {...formData};
        newFormData.puestosVehiculo = puestosVehiculo;
        instance.post('/admin/direccion/transporte/salve/distribucion/vehiculo', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    const consultarDistribucion = (e) => {
        setLoader(true);
        setHabilitado(true);
        setMostrarDatos(false);
        let newFormData  = {...formData};
        let tipoVehiculo = e.target.value;
        instance.post('/admin/direccion/transporte/list/distribucion/vehiculo', {codigo:tipoVehiculo}).then(res => {
            const tipoVehiculoDistribuciones = res.tipoVehiculoDistribuciones;
            const resultTipoVehiculo         = tipoVehiculos.find((tpVehiculo) => tpVehiculo.tipvehid == tipoVehiculo);
            const numeroColumnas             = resultTipoVehiculo.tipvehnumerocolumnas;
            const numeroFilas                = resultTipoVehiculo.tipvehnumerofilas;
            const numeroTotalPuestos         = resultTipoVehiculo.tipvehcapacidad;
            setClaseDistribucionPuesto(resultTipoVehiculo.tipvehclasecss);
            setExistenDatos((tipoVehiculoDistribuciones.length > 0) ? true : false);
            (tipoVehiculoDistribuciones.length > 0) ? distribucionUpdate(tipoVehiculoDistribuciones) : distribucionInicial(numeroFilas, numeroColumnas, numeroTotalPuestos);
            newFormData.existenDatos = (tipoVehiculoDistribuciones.length > 0) ? 'S' : 'N';
            newFormData.tipoVehiculo = tipoVehiculo;
            setFormData(newFormData);
            setMostrarDatos(true);
            setLoader(false);
        })
    }

    const distribucionInicial = (numeroFilas, numeroColumnas, numeroTotalPuestos) => {
        let dataFilas    = [];
        let numeroPuesto = 0;
        for (let i = 0; i < numeroFilas; i++) {
            let dataColumnas = [];
            for (let j = 0; j < numeroColumnas; j++) {
                numeroPuesto ++;
                let contenido    = '';
                let clase        = '';
                let id           = i * numeroColumnas + j;
                if (numeroPuesto === 1) {
                    contenido = 'C';
                    clase     = 'conductor';
                } else if (numeroPuesto <= numeroTotalPuestos + 1) {
                    contenido = numeroPuesto - 1;
                    clase     = 'asiento';
                } else {
                    contenido = 'P';
                    clase     = 'pasillo';
                }
                const esCondutor = clase === 'conductor';
                dataColumnas.push({ puestoColumna: id.toString(), contenido, clase, esCondutor });
            }
            dataFilas.push(dataColumnas);
        }

        setDataPuestos(dataFilas);
    }

    const distribucionUpdate = (distribucionVehiculo) => { 
        let totalFilas = distribucionVehiculo[0].totalFilas;
        let dataFilas  = [];
        let idColumna  = 0;
        for (let i = 0; i < totalFilas; i++) {
            let dataColumnas = [];
            distribucionVehiculo.map((res, j)=>{
                if(parseInt(res.tivedifila) === i){
                    let contenido    = res.tivedipuesto;
                    let clase        = (contenido === 'C') ? 'conductor' : ((contenido === 'P') ? 'pasillo' : 'asiento');
                    const esCondutor = clase === 'conductor';
                    dataColumnas.push({  puestoColumna: idColumna.toString(), contenido, clase, esCondutor });
                    idColumna ++;
                }
            });
            dataFilas.push(dataColumnas);
        }
       setDataPuestos(dataFilas);
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/direccion/transporte/list/tipos/vehiculos').then(res=>{
            setTipoVehiculos(res.tipoVehiculos);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Box>
                <Typography component={'h2'} className={'titleGeneral'}>Asignar distribución de los tipo de vehículos</Typography>
            </Box>

            <Box className={'containerSmall'} style={{marginTop: '1em'}}>
                <Card className={'cardContainer'}>
                <Grid container spacing={2}>
                    <Grid item xl={12} md={12} sm={12} xs={12}>
                    <SelectValidator
                        name={'tipoVehiculo'}
                        value={formData.tipoVehiculo}
                        label={'Tipo de vehículo'}
                        className={'inputGeneral'}
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Debe hacer una selección"]}
                        onChange={consultarDistribucion}
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {tipoVehiculos.map(res=>{
                            return <MenuItem value={res.tipvehid} key={res.tipvehid}> {res.nombreVehiculo} {res.filasColumnaPuesto} </MenuItem>
                        })}
                    </SelectValidator>
                    </Grid>
                </Grid>
                </Card>
            </Box>

            {(mostrarDatos) ? 
                <Card style={{marginTop: '1em', padding: '1em'}}>
                    <Grid container spacing={2}>  
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <p style={{textAlign: 'justify'}}>Para configurar la distribución de los asientos del tipo de vehículo, por favor, ordénelos según su número correspondiente y luego proceda a guardar la disposición.</p>
                        </Grid>
                        <Grid item xl={12} md={12} sm={12} xs={12}>
                            <Box className={claseDistribucionPuesto} >
                                <DragDropContext onDragEnd={handleDragEnd}>
                                    <Box style={{ display: 'flex', justifyContent: 'space-between' }}>
                                        {Object.keys(dataPuestos).map((listId) => (
                                            <Droppable key={listId} droppableId={listId}>
                                                {(provided) => (
                                                    <Box
                                                        ref={provided.innerRef}
                                                        {...provided.droppableProps}
                                                    >
                                                        {dataPuestos[listId].map((item, index) => (
                                                        <Draggable key={item.puestoColumna} draggableId={item.puestoColumna} index={index} isDragDisabled={item.esCondutor}>
                                                            { (provided) => (
                                                            <Box
                                                                ref={provided.innerRef}
                                                                {...provided.draggableProps}
                                                                {...provided.dragHandleProps}
                                                                className={item.clase}>
                                                                <p>{item.contenido}</p>
                                                            </Box>
                                                            )}
                                                        </Draggable>
                                                        ))}
                                                        {provided.placeholder}
                                                    </Box>
                                                )}
                                            </Droppable>
                                        ))}
                                    </Box>
                                </DragDropContext>
                            </Box>
                        </Grid>
                    </Grid>

                    <Grid container direction="row" justifyContent="right">
                        <Stack direction="row" spacing={2}>
                        <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                            startIcon={<SaveIcon />}> {(!existenDatos) ? "Guardar" : "Actualizar"}
                        </Button>
                        </Stack>
                    </Grid>
                </Card>
            : null}

        </ValidatorForm>
    )
}
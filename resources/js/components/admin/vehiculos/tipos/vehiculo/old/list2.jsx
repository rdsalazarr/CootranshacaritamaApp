import React, {useState, useEffect, Fragment } from 'react';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import {ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import {Button, Grid, Stack, Box, MenuItem, Typography, Card } from '@mui/material';
import showSimpleSnackbar from '../../../../../layout/snackBar';
import {LoaderModal} from "../../../../../layout/loader";
import instance from '../../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

const reorder = (list, startIndex, endIndex) => {
  const result = Array.from(list);
  const [removed] = result.splice(startIndex, 1);
  result.splice(endIndex, 0, removed);
  return result;
};

const initialData = {
  list1: [{ id: '0', contenido: '' , clase:'conductor', esCondutor: true},
          { id: '1', contenido: '' , clase:'pasillo', esCondutor: false},
          { id: '2', contenido: '1' , clase:'asiento', esCondutor: false},],
  list2: [
          { id: '3', contenido: '2', clase:'asiento', esCondutor: false },
          { id: '4', contenido: '3', clase:'asiento', esCondutor: false },
          { id: '5', contenido: '4', clase:'asiento', esCondutor: false }],
};

export default function Distribucion(){
  
  const [loader, setLoader] = useState(false);
  const [habilitado, setHabilitado] = useState(true);
  const [dataPuestos, setDataPuestos] = useState([]);
  const [tipoVehiculos, setTipoVehiculos] = useState([]);
  const [mostrarDatos, setMostrarDatos] = useState(false);
  const [existenDatos, setExistenDatos] = useState(false);
  const [adicionarClase, setAdicionarClase] = useState(false);
  const [formData, setFormData] = useState({tipoVehiculo: ''});
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

    console.log(dataPuestos);

    let puestosVehiculo = [];
    Object.keys(dataPuestos).forEach((clave) => {
      const filas = dataPuestos[clave];
      filas.forEach((elemento) => {
        puestosVehiculo.push({
          puestoId:  elemento.id,
          idPuesto:  elemento.idPuesto,
          contenido: elemento.contenido
        });
      });
    });

    // setLoader(true);
     let newFormData = {...formData};
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
  setHabilitado(true)
  setMostrarDatos(false); 
  let newFormData = { ...formData };
  let tipoVehiculo = e.target.name === 'tipoVehiculo' ? e.target.value : formData.tipoVehiculo;
  instance.post('/admin/direccion/transporte/list/distribucion/vehiculo', {codigo:e.target.value }).then(res => {
    const distribucionVehiculo       = res.tipoVehiculoDistribuciones;
    const resultTipoVehiculo         = tipoVehiculos.find((tpVehiculo) => tpVehiculo.tipvehid == tipoVehiculo);
    const numeroColumnas             = resultTipoVehiculo.tipvenumerocolumnas;
    const numeroFilas                = resultTipoVehiculo.tipvenumerofilas;
    const numeroTotalPuestos         = resultTipoVehiculo.tipvecapacidad;
    setClaseDistribucionPuesto(resultTipoVehiculo.tipveclasecss);
    setExistenDatos((distribucionVehiculo.length > 0) ? true : false);
    setMostrarDatos(true);
    
    let dataFilas = [];
    let numeroPuesto = 0;
    for (let i = 0; i < numeroFilas; i++) {
      let dataColumnas = [];

      for (let j = 0; j < numeroColumnas; j++) {
        numeroPuesto++;
        let contenido = '';
        let clase     = '';
        let idPuesto = '0';
        const puestoInfo = distribucionVehiculo.find(info => parseInt(info.tivediid) === numeroPuesto);        
        let id = i * numeroColumnas + j;
        if (puestoInfo) {
          contenido = puestoInfo.tivedicontenido;
         // id        = puestoInfo.tivediid;
          idPuesto  = puestoInfo.tivediid;
          clase     = (contenido === 'C') ? 'conductor' : ((contenido === 'P') ? 'pasillo' : 'asiento' );
        } else {
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
        }
        const esCondutor = clase === 'conductor';
        dataColumnas.push({ id: id.toString(), idPuesto:idPuesto, contenido, clase, esCondutor });
      }
      dataFilas.push(dataColumnas);
      console.log(dataFilas);
    }
    
    setDataPuestos(dataFilas);
    newFormData.tipoVehiculo = tipoVehiculo;
    setFormData(newFormData);
    setLoader(false);
  })
  //
}


/*
 const consultarDistribucion = (e) => {
    let newFormData = { ...formData };
    let tipoVehiculo = e.target.name === 'tipoVehiculo' ? e.target.value : formData.tipoVehiculo;
    const resultTipoVehiculo = tipoVehiculos.find((tpVehiculo) => tpVehiculo.tipvehid == tipoVehiculo);
    const numeroColumnas = resultTipoVehiculo.tipvenumerocolumnas;
    const numeroFilas = resultTipoVehiculo.tipvenumerofilas;
    const numeroTotalPuestos = resultTipoVehiculo.tipvecapacidad;
    let dataFilas = [];
    let numeroPuesto = 0;
  
    for (let i = 0; i < numeroFilas; i++) {
      let dataColumnas = [];
      for (let j = 0; j < numeroColumnas; j++) {
        numeroPuesto++;
  
        let contenido = '';
        let clase = '';

        if (numeroPuesto === 1) {
          contenido = '';
          clase = 'conductor';
        } else if (numeroPuesto <= numeroTotalPuestos + 1) {
          contenido = numeroPuesto - 1;
          clase = 'asiento';
        } else {
          contenido = '';
          clase = 'pasillo';
        }
  
        const id = i * numeroColumnas + j;
        const esCondutor = clase === 'conductor';
        dataColumnas.push({ id: id.toString(), contenido, clase, esCondutor });

      }
      dataFilas.push(dataColumnas);
    }
  
    setDataPuestos(dataFilas);
    newFormData.tipoVehiculo = tipoVehiculo;
    setFormData(newFormData);
  }*/

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
                    return <MenuItem value={res.tipvehid} key={res.tipvehid}>{res.tipvehnombre} {res.tipvehreferencia} Filas ({res.tipvenumerofilas})  Columnas ({res.tipvenumerocolumnas}) Puestos ({res.tipvecapacidad}) </MenuItem>
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
              <p>Para poder definir la distribución de los puestos del tipo de vehículo por favor organícelos según el numero de puesto y luego proceda a guardar el registro.</p>
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
                              <Draggable key={item.id} draggableId={item.id} index={index} isDragDisabled={item.esCondutor}>
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
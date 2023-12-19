import React, {useState, useEffect, Fragment } from 'react';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import {ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../../../layout/snackBar';
import {Button, Grid, Stack, Box, MenuItem, Typography, Card } from '@mui/material';
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

  const [tipo, setTipo] = useState(false);
  const [loader, setLoader] = useState(false);
  const [habilitado, setHabilitado] = useState(true);
  const [tipoVehiculos, setTipoVehiculos] = useState([]);
  const [dataPuesto, setDataPuesto] = useState(initialData);
  const [formData, setFormData] = useState({tipoVehiculo: ''});

  const handleChange = (e) =>{
    setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
}

  const handleDragEnd = (result) => {
    if (!result.destination) return;

    const { source, destination } = result;

    if (source.droppableId === destination.droppableId) {
      const items = reorder(
        dataPuesto[source.droppableId],
        source.index,
        destination.index
      );

      setDataPuesto({ ...dataPuesto, [source.droppableId]: items });
    } else {
      const sourceItems = [...dataPuesto[source.droppableId]];
      const destItems = [...dataPuesto[destination.droppableId]];

      const [movedItem] = sourceItems.splice(source.index, 1);
      destItems.splice(destination.index, 0, movedItem);

      setDataPuesto({
        ...dataPuesto,
        [source.droppableId]: sourceItems,
        [destination.droppableId]: destItems,
      });
    }
  }

  const handleSubmit = () =>{
    /*// setLoader(true);
     let formData = {...asientos};
     formData.tpVehiculo = data.tipvehid;
     instance.post('/admin/direccion/transporte/tipo/distribucion/salve', formData).then(res=>{
         let icono = (res.success) ? 'success' : 'error';
         showSimpleSnackbar(res.message, icono);
         (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;
         setLoader(false);
    })*/
 }

  /*const consultarDistribucion = (e) =>{
    let newFormData          = {...formData}
    let tipoVehiculo         = (e.target.name === 'tipoVehiculo' ) ? e.target.value : formData.tipoVehiculo;
    const resultTipoVehiculo = tipoVehiculos.filter((tpVehiculo) => tpVehiculo.tipvehid == tipoVehiculo);
    const numeroColumnas     = resultTipoVehiculo[0].tipvenumerocolumnas;
    const numeroFilas        = resultTipoVehiculo[0].tipvenumerofilas;
    let dataFilas            = [];
    let dataPuestos          = [];

    console.log(resultTipoVehiculo);

    for(let i = 1; i <= numeroFilas; i++){
      let identificador = 'list'+i;
      dataFilas.push({ identificador:[{id: i, contenido: i, clase:'asiento', esCondutor: false}] }); 
    }

    console.log(dataFilas);




    newFormData.tipoVehiculo = tipoVehiculo;
    setFormData(newFormData);
  }*/

  const consultarDistribucion = (e) => {
    let newFormData = { ...formData };
    let tipoVehiculo = e.target.name === 'tipoVehiculo' ? e.target.value : formData.tipoVehiculo;
    const resultTipoVehiculo = tipoVehiculos.find((tpVehiculo) => tpVehiculo.tipvehid == tipoVehiculo);
    const numeroColumnas = resultTipoVehiculo.tipvenumerocolumnas;
    const numeroFilas    = resultTipoVehiculo.tipvenumerofilas;
    const numeroTotalPuestos  = resultTipoVehiculo.tipvecapacidad;
    let dataFilas = [];
    let numeroPuesto = 0;

    let dataColumnas = [];
    for (let k = 90; k < 90 + numeroColumnas; k++) {
      let id = k;
      let contenido = ''; 
      let clase = (k === 90) ? 'conductor': 'pasillo'; 
      let esCondutor = false;
      dataColumnas.push({ id: id.toString(), contenido, clase, esCondutor });
    }
    dataFilas.push(dataColumnas);

    for (let i = 0; i < numeroFilas - 1; i++) {
      let dataColumnas = [];
      let puestoAsignado = 0;
      let contenido = ''; 
      let clase = '';
      for (let j = 0; j < numeroColumnas; j++) {
        puestoAsignado ++;

        if(puestoAsignado === 3 && numeroColumnas !== 3) {
          puestoAsignado = 0 ;
          contenido = '';
          clase = 'pasillo';
        }else{
          numeroPuesto ++;
          contenido = numeroPuesto; 
          clase = 'asiento';
        }        
        
        const id = i * numeroColumnas + j;
        const esCondutor = false;
        dataColumnas.push({ id: id.toString(), contenido, clase, esCondutor });

        if(numeroPuesto === numeroTotalPuestos){
          console.log("entrando");
          break;
        }
      }
      dataFilas.push(dataColumnas);
    }
    setDataPuesto(dataFilas);  
    newFormData.tipoVehiculo = tipoVehiculo;
    setFormData(newFormData);
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
                    return <MenuItem value={res.tipvehid} key={res.tipvehid}>{res.tipvehnombre} {res.tipvehreferencia} Filas ({res.tipvenumerofilas})  Columnas ({res.tipvenumerocolumnas}) Puesto ({res.tipvecapacidad}) </MenuItem>
                })}
              </SelectValidator>
            </Grid>
          </Grid>
        </Card>
      </Box>

      <Fragment>

      <Card style={{marginTop: '1em'}}>
        <Grid container spacing={2}>  
          <Grid item xl={12} md={12} sm={12} xs={12}>
            <p>Para poder definir la distribución de los puestos del tipo de vehículo por favor organícelos según el numero de puesto y luego proceda a guardar el registro.</p>
          </Grid>
          <Grid item xl={12} md={12} sm={12} xs={12}>
            <Box className='distribucionPuesto'>  
              <DragDropContext onDragEnd={handleDragEnd}>
                <Box style={{ display: 'flex', justifyContent: 'space-between' }}>
                  {Object.keys(dataPuesto).map((listId) => (
                    <Droppable key={listId} droppableId={listId}>
                      {(provided) => (
                        <Box
                          ref={provided.innerRef}
                          {...provided.droppableProps}
                        >
                          {dataPuesto[listId].map((item, index) => (
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
                startIcon={<SaveIcon />}> {(tipo) ? "Guardar" : "Actualizar"}
            </Button>
          </Stack>
        </Grid>  
      </Card>   

      </Fragment>

    </ValidatorForm>
  )
}

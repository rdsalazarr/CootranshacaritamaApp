import React, { useState } from 'react';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import { ValidatorForm } from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../../layout/snackBar';
import {Button, Grid, Stack, Box } from '@mui/material';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
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


export default function Distribucion({data, tipo}){

    let totalPuesto        = data.tipvenumerofilas * data.tipvenumerocolumnas - 1;
    let capacidadPasajero  = data.tipvecapacidad;
    //const [claseModeloVehiculo, setClaseModeloVehiculo] = useState((capacidadPasajero <= 4) ? 'modeloTaxi' : 'modeloGeneral');
    const [claseModeloVehiculo, setClaseModeloVehiculo] = useState('modeloGeneral');
    const [tamanoAciento, setTamanoAciento] = useState(Math.ceil(capacidadPasajero / 8));
    const [habilitado, setHabilitado] = useState(true);
    const [loader, setLoader] = useState(false);
    
    /*const [asientos, setAsientos] = useState([{ id: '0', contenido: '', clase:'conductor', esCondutor: true}]);   

    let numeroPorFila      = 0;
    let numero             = 0;
    let contador           = 0;
    let i                  = 0;
    let j                  = 0;

    if(capacidadPasajero <= 5){
        asientos.push({ id: i.toString(), contenido: '', clase:'pasillo', esCondutor: false});
        for (j = 2; j <= totalPuesto + 1; j++) { 
            numero ++;      
            asientos.push({ id: j.toString(), contenido: numero.toString(), clase:'asiento', esCondutor: false}); 
            if (numero === capacidadPasajero){
                break;
            }
        }

    }else{

        for (i = 1; i <= 4; i++) {
            asientos.push({ id: i.toString(), contenido: '', clase:'pasillo', esCondutor: false});
        }

        for (j = 5; j <= totalPuesto + 5; j++) {
            numeroPorFila ++;
            if(contador == 2){
                asientos.push({ id: j.toString(), contenido: '', clase:'pasillo', esCondutor: true});
                contador = 0;
            }else{
                numero ++;
                contador ++;
                asientos.push({ id: j.toString(), contenido: numero.toString(), clase:'asiento', esCondutor: false});            
            }
    
            if(numeroPorFila === 5){
                numeroPorFila = 0;
                contador = 0;
            }
    
            if (numero === capacidadPasajero){
                break;
            }
        }

    }  */   

    /* <Box class="modeloGeneral">
                        <Box class="conductor">1</Box>
                        <Box class="asiento">2</Box>
                        <Box class="pasillo"></Box>
                        <Box class="asiento">3</Box>
                        <Box class="asiento">4</Box>
                    </Box>*/


   const [asientos1, setAsientos1] = useState([
            { claseGeneral : 'modeloGeneral',
                puestos:[{ id: '0', contenido: '' , clase:'conductor', esCondutor: true},
                { id: '2', contenido: '' , clase:'pasillo', esCondutor: false},
                { id: '3', contenido: '' , clase:'pasillo', esCondutor: false},
                { id: '4', contenido: '' , clase:'pasillo', esCondutor: false},
                { id: '5', contenido: '' , clase:'pasillo', esCondutor: false},]
            },

            { claseGeneral : 'modeloGeneral',
                puestos:[{ id: '6', contenido: '1', clase:'asiento', esCondutor: false },
                { id: '7', contenido: '2', clase:'asiento', esCondutor: false },
                { id: '8', contenido: '' , clase:'pasillo', esCondutor: false},
                { id: '9', contenido: '3', clase:'asiento', esCondutor: false },
                { id: '10', contenido: '4', clase:'asiento', esCondutor: false }]
            },
            { claseGeneral : 'modeloGeneral',
                puestos:[{ id: '16', contenido: '5', clase:'asiento', esCondutor: false },
                { id: '17', contenido: '6', clase:'asiento', esCondutor: false },
                { id: '18', contenido: '' , clase:'pasillo', esCondutor: false},
                { id: '19', contenido: '7', clase:'asiento', esCondutor: false },
                { id: '20', contenido: '8', clase:'asiento', esCondutor: false }]
        },
   ])

    const [asientos, setAsientos] = useState([

       /* { id: '0', contenido: 'Conductor' , clase:'conductor', esCondutor: true},
        { id: '2', contenido: 'pasillo' , clase:'pasillo', esCondutor: false},
        { id: '3', contenido: 'pasillo' , clase:'pasillo', esCondutor: false},
        { id: '4', contenido: 'pasillo' , clase:'pasillo', esCondutor: false},
        { id: '5', contenido: 'pasillo' , clase:'pasillo', esCondutor: false},*/


        { id: '6', contenido: '1' , clase:'asiento', esCondutor: false, claseGeneal : 'modeloGeneral'},
        { id: '7', contenido: '2' , clase:'asiento', esCondutor: false, claseGeneal : ''},
        { id: '8', contenido: 'pasillo' , clase:'pasillo', esCondutor: false, claseGeneal : ''},
        { id: '9', contenido: '3' , clase:'asiento', esCondutor: false, claseGeneal : ''},
        { id: '10', contenido: '4' , clase:'asiento', esCondutor: false, claseGeneal : ''},  

        { id: '11', contenido: '5', clase:'asiento', esCondutor: false , claseGeneal : 'modeloGeneral'},
        { id: '12', contenido: '6', clase:'asiento', esCondutor: false , claseGeneal : ''},
        { id: '13', contenido: 'pasillo' , clase:'pasillo', esCondutor: false, claseGeneal : ''},
        { id: '14', contenido: '7', clase:'asiento', esCondutor: false , claseGeneal : ''},
        { id: '15', contenido: '8', clase:'asiento', esCondutor: false , claseGeneal : ''},

       /* { id: '16', contenido: '9', clase:'asiento', esCondutor: false },
        { id: '17', contenido: '10', clase:'asiento', esCondutor: false },
        { id: '18', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '19', contenido: '11', clase:'asiento', esCondutor: false },
        { id: '20', contenido: '12', clase:'asiento', esCondutor: false },

        { id: '21', contenido: '13', clase:'asiento', esCondutor: false },
        { id: '22', contenido: '14', clase:'asiento', esCondutor: false },
        { id: '23', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '24', contenido: '15', clase:'asiento', esCondutor: false },
        { id: '25', contenido: '16', clase:'asiento', esCondutor: false },

        { id: '26', contenido: '17', clase:'asiento', esCondutor: false },
        { id: '27', contenido: '18', clase:'asiento', esCondutor: false },
        { id: '28', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '29', contenido: '19', clase:'asiento', esCondutor: false },
        { id: '30', contenido: '20', clase:'asiento', esCondutor: false },

        { id: '31', contenido: '21', clase:'asiento', esCondutor: false },
        { id: '32', contenido: '22', clase:'asiento', esCondutor: false },
        { id: '33', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '34', contenido: '23', clase:'asiento', esCondutor: false },
        { id: '35', contenido: '24', clase:'asiento', esCondutor: false },*/

    ]);


    const [dataPuesto, setDataPuesto] = useState(initialData);

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
    };
    
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

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
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

               {
                /*


            <Grid item xl={12} md={12} sm={12} xs={12}>
                <Box class="distribucionPuesto">
                    

                    <Box class="modeloGeneral">
                        <Box class="conductor"></Box>
                        <Box class="pasillo">2</Box>
                        <Box class="pasillo"></Box>
                        <Box class="pasillo"></Box>
                        <Box class="pasillo"></Box>
                    </Box>

                    <Box class="modeloGeneral">
                        <Box class="asiento">1</Box>
                        <Box class="asiento">2</Box>
                        <Box class="pasillo"></Box>
                        <Box class="asiento">3</Box>
                        <Box class="asiento">4</Box>
                    </Box>

                    <Box class="modeloGeneral">
                        <Box class="asiento"><p>5</p></Box>
                        <Box class="asiento"><p>6</p></Box>
                        <Box class="pasillo"></Box>
                        <Box class="asiento"><p>7</p></Box>
                        <Box class="asiento"><p>8</p></Box>
                    </Box>

                    <Box class="modeloGeneral">
                        <Box class="asiento"><p>7</p></Box>
                        <Box class="asiento"><p>8</p></Box>
                        <Box class="pasillo"></Box>
                        <Box class="asiento"><p>9</p></Box>
                        <Box class="asiento"><p>10</p></Box>
                    </Box>
                </Box>
            </Grid> */
               }


            </Grid>
            <Grid container direction="row" justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
};
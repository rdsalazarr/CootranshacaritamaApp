import React, { useState } from 'react';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';
import {Button, Grid, Stack, Box } from '@mui/material';
import instance from '../../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';

export default function Distribucion({data, tipo}){

    const [tamanoAciento, setTamanoAciento] = useState(4);
    const [habilitado, setHabilitado] = useState(true);

    const [asientos, setAsientos] = useState([

        { id: '0', contenido: '' , clase:'conductor', esCondutor: true},
        { id: '2', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '3', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '4', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '5', contenido: '' , clase:'pasillo', esCondutor: false},


        { id: '6', contenido: '1' , clase:'asiento', esCondutor: false},
        { id: '7', contenido: '2' , clase:'asiento', esCondutor: false},
        { id: '8', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '9', contenido: '3' , clase:'asiento', esCondutor: false},
        { id: '10', contenido: '4' , clase:'asiento', esCondutor: false},   

        { id: '11', contenido: '5', clase:'asiento', esCondutor: false },
        { id: '12', contenido: '6', clase:'asiento', esCondutor: false },
        { id: '13', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '14', contenido: '7', clase:'asiento', esCondutor: false },
        { id: '15', contenido: '8', clase:'asiento', esCondutor: false },

        { id: '16', contenido: '9', clase:'asiento', esCondutor: false },
        { id: '17', contenido: '10', clase:'asiento', esCondutor: false },
        { id: '18', contenido: '' , clase:'pasillo', esCondutor: false},
        { id: '18', contenido: '11', clase:'asiento', esCondutor: false },
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
        { id: '35', contenido: '24', clase:'asiento', esCondutor: false },

    ]);

    const handleDragEnd = (result) => {
        if (!result.destination) return;

        const nuevosAsientos = [...asientos];
        const [movedItem] = nuevosAsientos.splice(result.source.index, 1);
        nuevosAsientos.splice(result.destination.index, 0, movedItem);

        setAsientos(nuevosAsientos.map((asiento, index) => ({ ...asiento, id: String(index + 1) })));
        
        // Aquí puedes guardar nuevosAsientos en la base de datos
    };

    //isDragDisabled={index === 0}
    return (
        <Box>
            <Grid container spacing={2}>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <p>Para poder definir la distribución de los puestos del tipo de vehículo por favor organícelos según el numero de puesto y luego proceda a guardar el registro.</p>
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <Box className='distribucionPuesto'>
                        <DragDropContext onDragEnd={handleDragEnd}>
                            <Droppable droppableId="asientos">
                            {(provided) => (
                                <Box className='modeloAutomovil' {...provided.droppableProps} ref={provided.innerRef} fontStyle={{gridTemplateRows: 'repeat('+tamanoAciento+', 50px)'}}>
                                    {asientos.map((asiento, index) => (
                                        <Draggable key={asiento.id} draggableId={asiento.id} index={index} isDragDisabled={asiento.esCondutor} >
                                            {(provided) => (
                                                <Box className={asiento.clase}
                                                    ref={provided.innerRef}
                                                    {...provided.draggableProps}
                                                    {...provided.dragHandleProps}
                                                    >
                                                    <p>{asiento.contenido}</p>
                                                </Box>
                                            )}
                                        </Draggable>
                                    ))}
                                </Box>
                            )}
                            </Droppable>
                        </DragDropContext>
                    </Box>
                </Grid>
            </Grid>
            <Grid container direction="row" justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {(tipo === 'I') ? "Guardar" : "Actualizar"}
                    </Button>
                </Stack>
            </Grid>
        </Box>
    );
};
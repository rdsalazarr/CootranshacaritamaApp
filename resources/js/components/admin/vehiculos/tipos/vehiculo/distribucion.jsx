import React, { useState } from 'react';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';
import { Box} from '@mui/material';

export default function Distribucion(){

    const [vehicles, setVehicles] = useState([
        { id: '1', name: '1' },
        { id: '2', name: '2' },    
        { id: '3', name: '3' },
        { id: '4', name: '4' },    
        { id: '5', name: '5' },
        { id: '6', name: '6' }
      
      ]);


    const handleDragEnd = (result) => {
        if (!result.destination) return; // El vehículo no se soltó en un destino válido
    
        const newVehicles = Array.from(vehicles);
        const [movedVehicle] = newVehicles.splice(result.source.index, 1);
        newVehicles.splice(result.destination.index, 0, movedVehicle);
    
        setVehicles(newVehicles);
        // Lógica adicional para enviar la nueva disposición al servidor
      };


      /* <Box className='distribucionPuesto'>
                <Box className='modeloAutomovil'>
                    <Box className='asiento'><p>1</p></Box>
                    <Box className='asiento'><p>2</p></Box>
                    <Box className='pasillo'></Box>
                    <Box className='asiento'><p>3</p></Box>
                    <Box className='asiento'><p>4</p></Box>

                    <Box className='asiento'><p>5</p></Box>
                    <Box className='asiento'><p>6</p></Box>
                    <Box className='pasillo'></Box>
                    <Box className='asiento'><p>7</p></Box>
                    <Box className='asiento'><p>8</p></Box>

                    <Box className='asiento'><p>9</p></Box>
                    <Box className='asiento'><p>10</p></Box>
                    <Box className='pasillo'></Box>
                    <Box className='asiento'><p>11</p></Box>
                    <Box className='asiento'><p>12</p></Box>

                    <Box className='asiento'><p>13</p></Box>
                    <Box className='asiento'><p>14</p></Box>
                    <Box className='pasillo'></Box>
                    <Box className='asiento'><p>15</p></Box>
                    <Box className='asiento'><p>16</p></Box>

                    <Box className='asiento'><p>17</p></Box>
                    <Box className='asiento'><p>18</p></Box>
                    <Box className='pasillo'></Box>
                    <Box className='asiento'><p>19</p></Box>
                    <Box className='asiento'><p>20</p></Box>
                </Box>
            </Box>*/

    return (
        <Box className='distribucionPuesto'>
            <DragDropContext onDragEnd={handleDragEnd}>
                <Droppable droppableId="vehicles">
                        {(provided) => (
                       <Box className='modeloAutomovil' {...provided.droppableProps} ref={provided.innerRef}>
                            {vehicles.map((vehicle, index) => (
                            <Draggable key={vehicle.id} draggableId={vehicle.id} index={index}>
                                {(provided) => (
                                <Box className='asiento'
                                    ref={provided.innerRef}
                                    {...provided.draggableProps}
                                    {...provided.dragHandleProps}
                                >
                                    <p>{vehicle.name}</p>
                                </Box>
                                )}
                            </Draggable>
                            ))}
                            {provided.placeholder}
                        </Box>
                        )}
                    </Droppable> 
            </DragDropContext>
       </Box>       
    )
}
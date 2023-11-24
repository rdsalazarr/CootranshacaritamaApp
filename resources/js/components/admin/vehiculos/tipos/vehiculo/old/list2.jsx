import React, { useState } from 'react';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';

const VehicleDistribution = () => {
  const [vehicles, setVehicles] = useState([
    { id: 'vehicle1', name: 'Vehicle 1' },
    { id: 'vehicle2', name: 'Vehicle 2' },

    { id: 'vehicle3', name: 'Vehicle 3' },
    { id: 'vehicle4', name: 'Vehicle 4' },

    { id: 'vehicle5', name: 'Vehicle 5' },
    { id: 'vehicle6', name: 'Vehicle 6' },
    // Agrega más vehículos según sea necesario
  ]);

  const handleDragEnd = (result) => {
    if (!result.destination) return; // El vehículo no se soltó en un destino válido

    const newVehicles = Array.from(vehicles);
    const [movedVehicle] = newVehicles.splice(result.source.index, 1);
    newVehicles.splice(result.destination.index, 0, movedVehicle);

    setVehicles(newVehicles);
    // Lógica adicional para enviar la nueva disposición al servidor
  };

  return (
    <DragDropContext onDragEnd={handleDragEnd}>
      <Droppable droppableId="vehicles">
        {(provided) => (
          <ul {...provided.droppableProps} ref={provided.innerRef}>
            {vehicles.map((vehicle, index) => (
              <Draggable key={vehicle.id} draggableId={vehicle.id} index={index}>
                {(provided) => (
                  <li
                    ref={provided.innerRef}
                    {...provided.draggableProps}
                    {...provided.dragHandleProps}
                  >
                    {vehicle.name}
                  </li>
                )}
              </Draggable>
            ))}
            {provided.placeholder}
          </ul>
        )}
      </Droppable>
    </DragDropContext>
  );
};

export default VehicleDistribution;

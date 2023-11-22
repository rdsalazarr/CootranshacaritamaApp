import React, { useState } from 'react';
import { DragDropContext, Droppable, Draggable } from 'react-beautiful-dnd';

const VehiculoDistribucion = ({ numPuestos, numColumnas }) => {
    const numFilas = Math.ceil(numPuestos / numColumnas);
  
    const [puestos, setPuestos] = useState(
      Array.from({ length: numPuestos }, (_, index) => ({
        id: String(index + 1),
        contenido: `${index + 1}`,
      }))
    );
  
    const handleDragEnd = (result) => {
        if (!result.destination) return;
    
        const nuevosPuestos = [...puestos];
        const [puestoMovido] = nuevosPuestos.splice(result.source.index, 1);
        nuevosPuestos.splice(result.destination.index, 0, puestoMovido);
    
        setPuestos(nuevosPuestos.map((puesto, index) => ({ ...puesto, id: String(index + 1) })));
      };
    
    return (
      <DragDropContext onDragEnd={handleDragEnd}>
        {Array.from({ length: numFilas }).map((_, rowIndex) => (
          <Droppable key={rowIndex} droppableId={`fila-${rowIndex}`} direction="horizontal">
            {(provided) => (
              <div
                {...provided.droppableProps}
                ref={provided.innerRef}
                style={{ display: 'flex', justifyContent: 'space-between' }}
              >
                {puestos
                  .slice(rowIndex * numColumnas, (rowIndex + 1) * numColumnas)
                  .map((puesto, index) => (
                    <Draggable key={puesto.id} draggableId={puesto.id} index={index}>
                      {(provided) => (
                        <div
                          {...provided.draggableProps}
                          {...provided.dragHandleProps}
                          ref={provided.innerRef}
                          style={{
                            border: '1px solid #ccc',
                            borderRadius: '5px',
                            margin: '5px',
                            padding: '10px',
                            flex: 1,
                            boxSizing: 'border-box',
                            textAlign: 'center',
                          }}
                        >
                          {puesto.contenido}
                        </div>
                      )}
                    </Draggable>
                  ))}
                {provided.placeholder}
              </div>
            )}
          </Droppable>
        ))}
      </DragDropContext>
    );
  };

// Uso del componente
const App = () => {
  return (
    <div>
      <h1>Vehículo Distribución</h1>
      <VehiculoDistribucion numPuestos={16} numColumnas={4} />
    </div>
  );
};

export default App;

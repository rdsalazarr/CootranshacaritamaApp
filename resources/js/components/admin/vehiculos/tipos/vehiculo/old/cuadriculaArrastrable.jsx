import React, { useState } from 'react';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';

const CuadriculaArrastrable = () => {
  const [puestos, setPuestos] = useState(Array.from({ length: 30 }, (_, index) => `Puesto ${index + 1}`));

  const handleDragEnd = (result) => {
    if (!result.destination) return;

    const nuevosPuestos = Array.from(puestos);
    const [removido] = nuevosPuestos.splice(result.source.index, 1);
    nuevosPuestos.splice(result.destination.index, 0, removido);

    setPuestos(nuevosPuestos);
  };

  return (
    <DragDropContext onDragEnd={handleDragEnd}>
      <Droppable droppableId="puestos" direction="horizontal">
        {(provided) => (
          <div
            {...provided.droppableProps}
            ref={provided.innerRef}
            style={{ display: 'flex', flexWrap: 'wrap', width: '400px' }}
          >
            {puestos.map((puesto, index) => (
              <Draggable key={puesto} draggableId={puesto} index={index}>
                {(provided) => (
                  <div
                    ref={provided.innerRef}
                    {...provided.draggableProps}
                    {...provided.dragHandleProps}
                    style={{
                      width: '80px',
                      height: '80px',
                      border: '1px solid #ccc',
                      margin: '8px',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                    }}
                  >
                    {puesto}
                  </div>
                )}
              </Draggable>
            ))}
            {provided.placeholder}
          </div>
        )}
      </Droppable>
    </DragDropContext>
  );
};

export default CuadriculaArrastrable;

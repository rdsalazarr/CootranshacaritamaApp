import React, { useState } from 'react';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';

const BusLayout = () => {
  const [seats, setSeats] = useState(Array.from({ length: 40 }, (_, index) => index + 1));

  const handleDragEnd = (result) => {
    if (!result.destination) return;

    const newSeats = Array.from(seats);
    const [movedSeat] = newSeats.splice(result.source.index, 1);
    newSeats.splice(result.destination.index, 0, movedSeat);

    setSeats(newSeats);
  };

  return (
    <DragDropContext onDragEnd={handleDragEnd}>
      <Droppable droppableId="bus" direction="horizontal">
        {(provided) => (
          <div {...provided.droppableProps} ref={provided.innerRef} style={{ display: 'flex' }}>
            {seats.map((seat, index) => (
              <Draggable key={seat} draggableId={`seat-${seat}`} index={index}>
                {(provided) => (
                  <div
                    ref={provided.innerRef}
                    {...provided.draggableProps}
                    {...provided.dragHandleProps}
                    style={{
                      width: '40px',
                      height: '40px',
                      margin: '4px',
                      background: 'lightgray',
                      textAlign: 'center',
                      lineHeight: '40px',
                    }}
                  >
                    {seat}
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

export default BusLayout;

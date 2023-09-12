import React, { useState } from 'react';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers';
import dayjs from 'dayjs';
import esLocale from 'dayjs/locale/es';

function MyForm() {
  const [formData, setFormData] = useState({
    fecha: dayjs(),
  });

  const handleChange = (date) => {
    setFormData((prevData) => ({
      ...prevData,
      fecha: date,
    }));
  };

  const formatDate = (date) => {
    // Personaliza el formato de la fecha
    return dayjs(date).format('YYYY-MM-DD');
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    // Ahora formData.fecha contendrá la fecha en el formato "YYYY-MM-DD"
    console.log('Fecha formateada:', formData.fecha.format('YYYY-MM-DD'));
    // Envía otros datos del formulario al servidor si es necesario
  };

  return (
    <form onSubmit={handleSubmit}>
      <LocalizationProvider dateAdapter={AdapterDayjs} locale={esLocale}>
        <h1> hola esta es la fecha</h1>
        <DatePicker
          value={formData.fecha}
          onChange={(date) => handleChange(date)}
          renderInput={(params) => (
            <input
              {...params.inputProps}
              value={formatDate(params.inputProps.value)} // Formatea la fecha para mostrarla en el campo
              readOnly // Evita que el usuario modifique manualmente la fecha
            />
          )}
          className={'inputGeneral'}
        />
      </LocalizationProvider>

      <button type="submit">Enviar</button>
    </form>
  );
}

export default MyForm;
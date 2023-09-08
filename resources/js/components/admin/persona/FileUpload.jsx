import React, { useState } from 'react';
import axios from 'axios';

function FileUpload() {
  const [selectedFile, setSelectedFile] = useState(null);

  const handleFileChange = (e) => {
    setSelectedFile(e.target.files[0]);
  };

  const handleFileUpload = () => {
    if (selectedFile) {
      const formData = new FormData();
      formData.append('pdf', selectedFile);

      axios.post('/admin/persona/salve', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      })
        .then((response) => {
          console.log('Archivo subido con éxito:', response.data);
        })
        .catch((error) => {
          console.error('Error al subir el archivo:', error);
        });
    } else {
      console.error('Selecciona un archivo PDF antes de subirlo.');
    }
  };

  return (
    <div>
      <h2>Subir Archivo PDF</h2>
      <input type="file" accept="application/pdf" onChange={handleFileChange} />
      <button onClick={handleFileUpload}>Subir PDF</button>
    </div>
  );
}

export default FileUpload;

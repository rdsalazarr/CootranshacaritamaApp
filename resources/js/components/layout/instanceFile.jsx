import React from "react";
import axios from "axios";
import showSimpleSnackbar from './snackBar';

let config = {
    Authorization: "Bearer " + document.querySelector('meta[name="csrf-token"]').content,
    "Content-Type": "application/json"
};

const instanceFile = axios.create({headers: config, withCredentials: true, responseType: 'blob'});

instanceFile.interceptors.response.use(
    response => {
        if (response.status === 201) {
            showSimpleSnackbar(response.data.data, "success")
        }
        try{
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            let filename =  '';
            let filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
            let matches = filenameRegex.exec(response.headers["content-disposition"]);
            if (matches != null && matches[1]) { 
            filename = matches[1].replace(/['"]/g, '');
            }
            link.setAttribute('download',filename);
            document.body.appendChild(link);
            link.click();
        return {
            msg : 'El archivo se esta descargando',
            success: true
        }
    } catch (error) {
        return {
            msg : 'No se pudo descargar el archivo',
            success: false
        }
    }
    },
    error => {
        if (error.response.status === 401 || error.response.status === 403) {
            window.location.href = import.meta.env.VITE_APP_URL+"/logout";
        }
        let msg = ( typeof error.response.data.data === 'undefined') ?  error.response.data.message : error.response.data.data;
        showSimpleSnackbar(msg, "error");
        return error.response.data;
    }
);

export default instanceFile;
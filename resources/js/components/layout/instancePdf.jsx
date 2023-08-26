import React from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import showSimpleSnackbar from './snackBar';

let config = {
    Authorization: "Bearer " + document.querySelector('meta[name="csrf-token"]').content,
    "Content-Type": "application/json"
};

const instancePdf = axios.create({headers: config, withCredentials: true});
    instancePdf.interceptors.response.use(
        response => {
            if (response.status === 201) {
                showSimpleSnackbar(response.data.data, "success")
            }

            let datos = {};
            Object.keys(response.data).map(res => {
                if (res !== 'success') {
                    datos[res] = response.data[res];
                }
            });

            return datos;
        },
        error => {
            if (error.response.status === 401 || error.response.status === 403) {
                window.location.href = import.meta.env.VITE_APP_URL+"logout";
            }
            let msg = (typeof error.response.data.data === 'undefined') ? error.response.data.message : error.response.data.data;
            showSimpleSnackbar(msg, "error");
            return error.response.data;
        }
);

export default instancePdf;
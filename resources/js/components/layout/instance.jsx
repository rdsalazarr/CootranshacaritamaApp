import React from "react";
import axios from "axios";
import showSimpleSnackbar from './snackBar';

let config = {
    Authorization: "Bearer " + document.querySelector('meta[name="csrf-token"]').content,
    "Content-Type": "multipart/form-data"
};
//"Content-Type": "application/json" 

const instance = axios.create({headers: config, withCredentials: true});

instance.interceptors.response.use(
    response => {
        if (response.status === 201) {
            showSimpleSnackbar(response.data.data, "success")
        }
        return response.data;
    },
    error => {
        if (error.response.status === 401 || error.response.status === 403) {
            window.location.href = import.meta.env.VITE_APP_URL+"/logout";
        }
        let msg = (typeof error.response.data.data === 'undefined') ? error.response.data.message : error.response.data.data;
        showSimpleSnackbar(msg, "error");
        return error.response.data;
    }
);

export default instance;
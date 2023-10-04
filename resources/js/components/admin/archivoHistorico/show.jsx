import React, {useState, useEffect} from 'react';
import showSimpleSnackbar from '../../layout/snackBar';
import {LoaderModal} from "../../layout/loader";
import instance from '../../layout/instance';
import {Grid  } from '@mui/material';
import Archivo from './show/archivo';
import Anexos from './anexos';

export default function Show({id}){

    const [loader, setLoader] = useState(false);
    const [dataArchivo, setDataArchivo] = useState([]);
    const [digitalizados, setDigitalizados] = useState([]);

    const inicio = () =>{
        setLoader(true);
        showSimpleSnackbar("Estamos recopilando toda la información del archivo hitórico, se paciente por favor",'warning')    
        instance.post('/admin/archivo/historico/show', {codigo: id}).then(res=>{
            let newDataArchivo  = [];
            let archivoHistorico = res.data;
            let digitalizados    = res.digitalizados;

            newDataArchivo.tipoDocumental    = archivoHistorico.tipdocnombre;
            newDataArchivo.estante           = archivoHistorico.tiesarnombre;
            newDataArchivo.caja              = archivoHistorico.ticaubnombre;
            newDataArchivo.carpeta           = archivoHistorico.ticaubnombre;
            newDataArchivo.fechaRegistro     = archivoHistorico.archisfechahora;
            newDataArchivo.nombreUsuario     = archivoHistorico.nombreUsuario;
            newDataArchivo.fechaDocumento    = archivoHistorico.archisfechadocumento;
            newDataArchivo.numeroFolio       = archivoHistorico.archisnumerofolio;
            newDataArchivo.asuntoDocumento   = archivoHistorico.archisasuntodocumento;
            newDataArchivo.tomoDocumento     = (archivoHistorico.archistomodocumento !== null) ? archivoHistorico.archistomodocumento : 'No reportado';
            newDataArchivo.codigoDocumental  = (archivoHistorico.archiscodigodocumental !== null) ? archivoHistorico.archiscodigodocumental : 'No reportado';
            newDataArchivo.entidadRemitente  = (archivoHistorico.archisentidadremitente !== null) ? archivoHistorico.archisentidadremitente : 'No reportado';
            newDataArchivo.entidadProductora = (archivoHistorico.archisentidadproductora !== null) ? archivoHistorico.archisentidadproductora : 'No reportado';
            newDataArchivo.resumenDocumento  = (archivoHistorico.archisresumendocumento !== null) ? archivoHistorico.archisresumendocumento : 'No reportado'; 
            newDataArchivo.observacion       = (archivoHistorico.archisobservacion !== null) ? archivoHistorico.archisobservacion : 'No reportado';

            setDataArchivo(newDataArchivo); 
            setDigitalizados(digitalizados);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (

        <Grid container spacing={2}>

            <Grid item md={12} xl={12} sm={12}>
                <Archivo data={dataArchivo} />
            </Grid>

            { (digitalizados.length > 0) ?
                <Grid item md={12} xl={12} sm={12}>
                    <Anexos data={digitalizados} eliminar={false} />
                </Grid>
           : null }

        </Grid>
    )
}
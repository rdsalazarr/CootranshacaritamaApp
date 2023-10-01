import React, {useState, useEffect} from 'react';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Trazabilidad from './show/trazabilidad';
import Radicado from './show/radicado';
import {Grid  } from '@mui/material';
import Persona from './show/persona';
import Copias from './show/copias';
import Anexos from './anexos';

export default function Show({id}){

    const [formData, setFormData] = useState({codigo: id, totalAnexos:'', totalCopias:''});   
    const [loader, setLoader] = useState(false);
    const [dataPersona, setDataPersona] = useState([]);
    const [dataRadicado, setDataRadicado] = useState([]);
    const [dataAnexos, setDataAnexos] = useState([]);
    const [dataCopias, setDataCopias] = useState([]);
    const [dataEstados, setDatosEstados] = useState([]);    

    const inicio = () =>{
        setLoader(true);
        showSimpleSnackbar("Estamos recopilando toda la información del radicado del documento externo, se paciente por favor",'warning')    
        instance.post('/admin/radicacion/documento/entrante/show', {codigo: id}).then(res=>{
            let newFormData      = {...formData};
            let newDataUsuario   = [];
            let newDataRadicado  = [];
            let radicado         = res.radicado;          
            let estadosRadicado  = res.estados;
            let anexosRadicado   = res.anexos;
            let copiasRadicado   = res.copias;

            //Informacion de la persona
            newDataUsuario.tipoIdentificacion   = radicado.tipoIdentificacion;
            newDataUsuario.numeroIdentificacion = radicado.peradodocumento;
            newDataUsuario.esEmpresa            = (radicado.tipideid === '5' ) ? true : false;
            newDataUsuario.primerNombre         = radicado.peradoprimernombre;
            newDataUsuario.segundoNombre        = radicado.peradosegundonombre;
            newDataUsuario.primerApellido       = radicado.peradoprimerapellido;
            newDataUsuario.segundoApellido      = radicado.peradosegundoapellido;
            newDataUsuario.direccionFisica      = radicado.peradodireccion;
            newDataUsuario.direccionElectronica = radicado.peradocorreo;
            newDataUsuario.numeroContacto       = radicado.peradotelefono;
            newDataUsuario.empresaCodigo        = radicado.peradocodigodocumental;

            //Informacion del radicado
            newDataRadicado.fechaRadicado           = radicado.radoenfechahoraradicado;
            newDataRadicado.fechaMaxRespuesta       = radicado.radoenfechamaximarespuesta;
            newDataRadicado.fechaLlegadaDocumento   = radicado.radoenfechallegada;
            newDataRadicado.fechaDocumento          = radicado.radoenfechadocumento;
            newDataRadicado.consecutivo             = radicado.consecutivo;
            newDataRadicado.departamento            = radicado.departamento;
            newDataRadicado.municipio               = radicado.municipio;
            newDataRadicado.dependencia             = radicado.dependencia;
            newDataRadicado.personaEntregaDocumento = radicado.radoenpersonaentregadocumento;
            newDataRadicado.tipoMedio               = radicado.nombreTipoMedio;
            newDataRadicado.tieneCopia              = radicado.tieneCopias;
            newDataRadicado.tieneAnexos             = radicado.tieneAnexos;
            newDataRadicado.estadoActual            = radicado.estadoActual;
            newDataRadicado.descripcionAnexos       = radicado.radoendescripcionanexo;
            newDataRadicado.observacionGeneral      = radicado.radoenobservacion;
            newDataRadicado.descripcion             = radicado.radoenasunto;
            newDataRadicado.requiereRespuesta       = radicado.requiereRespuesta;
            newFormData.totalCopias                 = radicado.totalCopias;

           setDataPersona(newDataUsuario);
           setDataRadicado(newDataRadicado);
           setFormData(newFormData);
           setDataAnexos(anexosRadicado);
           setDataCopias(copiasRadicado);
           setDatosEstados(estadosRadicado);
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
                <Persona data={dataPersona} />
            </Grid>

            <Grid item md={12} xl={12} sm={12}>
                <Radicado data={dataRadicado} />
            </Grid>
            
            <Grid item md={12} xl={12} sm={12}>
                <Anexos data={dataAnexos} eliminar={false} />
            </Grid>

            { (formData.totalCopias > 0) ?
                <Grid item md={12} xl={12} sm={12}>
                    <Copias data={dataCopias} />
                </Grid>
            : null}

            <Grid item md={12} xl={12} sm={12}>
                <Trazabilidad data={dataEstados} />
            </Grid>

        </Grid>
    )
}
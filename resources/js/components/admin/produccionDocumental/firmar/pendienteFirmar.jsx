import React, {useState, useEffect} from 'react';
import {FirmarDocumento} from '../../../layout/modalFijas';
import { ModalDefaultAuto  } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Verificar from './verificar';
import VisualizarPdf from './visualizarPdf';
import { Box} from '@mui/material';

import Acta from '../acta/new';
import Certificado from '../certificado/new';
import Circular from '../circular/new';
import Citacion from '../citacion/new';
import Constancia from '../constancia/new';
import Oficio from '../oficio/new';

export default function PendienteFirmar(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:8, data:{}, titulo:'', tamano:'bigFlot'});
    const tipoDocumental = ['A','B','C','H','T','O'];

    const cerrarModal = () =>{
        setModal({open : false, vista:8, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                    <Acta tipo={'U'} id={modal.data.actaId} ruta='JEFE' />,
                    <Certificado tipo={'U'} id={modal.data.certificadoId} ruta='JEFE' />,
                    <Circular tipo={'U'} id={modal.data.circularId} ruta='JEFE' />,
                    <Citacion tipo={'U'} id={modal.data.citacionId} ruta='JEFE' />,
                    <Constancia tipo={'U'} id={modal.data.constanciaId} ruta='JEFE' />,
                    <Oficio tipo={'U'} id={modal.data.oficioId} ruta='JEFE' />,
                    <FirmarDocumento id={modal.data.codoprid} cerrarModal={cerrarModal} />,
                    <VisualizarPdf data={modal.data} /> ];

    const tituloModal = ['Editar el tipo documental',
                        'Firma el tipo documental',
                        'Visualizar el tipo documental en formato PDF'];

    const edit = (data, tipo, dato) =>{
        let  vista = (dato === 1) ? tipoDocumental.indexOf(data.codigoDocumental) : ((dato === 2) ? 6 : 7)
        setModal({open: true, vista: vista, data:data, titulo: tituloModal[tipo], tamano: (tipo === 0 ) ? 'bigFlot' : ((tipo === 1) ? 'smallFlot' :'mediumFlot')});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/firmar/documento/list', {tipo:'PENDIENTE'}).then(res=>{
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);
    
    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Fecha','Tipo documento','Nombre dirigido','Estado','Editar','Firmar','PDF']}
                    ver={[ "fecha", "tipoDocumento","nombreDirigido", "nombreEstado"]}
                    accion={[
                        {tipo: 'B', icono : 'edit',                color: 'orange', funcion : (data)=>{edit(data, 0, 1)} },
                        {tipo: 'B', icono : 'signal_cellular_alt', color: 'red',    funcion : (data)=>{edit(data, 1, 2)} },
                        {tipo: 'B', icono : 'picture_as_pdf',      color: 'orange', funcion : (data)=>{edit(data, 2, 3)} },
                    ]}
                    funciones={{orderBy: true, search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:8, data:{}, titulo:'', tamano: ''}), (modal.vista !== 7) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
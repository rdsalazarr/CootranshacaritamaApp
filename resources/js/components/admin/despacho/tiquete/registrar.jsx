import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';
import { Box} from '@mui/material';
import NewEdit from './new';
import Show from './show';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:4, data:{}, titulo:'', tamano:'bigFlot'});

    const modales = [
                        <NewEdit tipo={'I'} />,
                        <NewEdit data={modal.data} tipo={'U'} />,
                        <Show data={modal.data} />,
                        <VisualizarPdf id={(tipo === 3) ? modal.data.encoid : null} />
                    ];

    const tituloModal = ['Nuevo tiquete','Editar tiquete',
                        'Visualizar información general del tiquete',
                        'Visualizar factura en PDF de tiquete'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 3) ? 'smallFlot' : 'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/despacho/tiquete/list', {estado:'R', tipo:'REGISTRADO'}).then(res=>{
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
                    titulo={['Fecha registo','Tipo encomienda','Ruta','Destino', 'Remitente','Destinatario','Estado','Actualizar','Visualizar', 'PDF']}
                    ver={["fechaHoraRegistro","tipoEncomienda","nombreRuta", "destinoEncomienda","nombrePersonaRemitente","nombrePersonaDestino","estado"]}
                    accion={[
                        {tipo: 'T', icono : 'add',            color: 'green',  funcion : (data)=>{edit(data, 0)} },
                        {tipo: 'B', icono : 'edit',           color: 'orange', funcion : (data)=>{edit(data, 1)} },
                        {tipo: 'B', icono : 'visibility',     color: 'green',  funcion : (data)=>{edit(data, 2)} },
                        {tipo: 'B', icono : 'picture_as_pdf', color: 'red',    funcion : (data)=>{edit(data, 3)} }
                    ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:4, data:{}, titulo:'', tamano: ''}), inicio();}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
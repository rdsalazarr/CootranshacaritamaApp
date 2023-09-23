import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto  } from '../../../layout/modal';
import {SolicitarFirma} from '../../../layout/modalFijas';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import VisualizarPdf from '../visualizarPdf';
import Verificar from '../verificar';
import { Box} from '@mui/material';
import NewEdit from './new';

export default function Producir(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [areaSeleccionada, setAreaSeleccionada] = useState([]);
    const [modal, setModal] = useState({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const verificarArea = (area) =>{
        setAreaSeleccionada(area);
        setModal({open: true, vista: 1, data:data, titulo: 'Registrar nuevo tipo documental oficio del área '+area.depenombre.toLowerCase(), tamano: 'bigFlot'});
    }

    const modales = [
                        <Verificar cerrarModal={cerrarModal} verificarArea={verificarArea} ruta={'oficio'} />,
                        <NewEdit tipo={'I'} area={areaSeleccionada} />,
                        <NewEdit tipo={'U'} id={(tipo !== 0) ? modal.data.id : null} /> ,
                        <SolicitarFirma id={(tipo !== 0) ? modal.data.id : null} ruta={'oficio'} cerrarModal={cerrarModal} />,
                        <VisualizarPdf id={(tipo !== 0) ? modal.data.id : null} ruta={'oficio'} />
                    ];

    const tituloModal = ['Selecionar área de producción documental', 
                        'Registrar nuevo tipo documental ',
                        'Editar tipo documental oficio',
                        'Solicitar firma del tipo documental',
                        'Visualizar el tipo documental en formato PDF'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 0 || tipo === 3) ? 'smallFlot' : ((tipo === 4) ? 'mediumFlot' :'bigFlot')});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/producion/documental/oficio/list', {tipo:'PRODUCIR'}).then(res=>{
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
                    titulo={['Consecutivo', 'Dependencia','Fecha','Asunto','Dirigido','Estado','Editar','Solicitar','PDF']}
                    ver={["consecutivo", "dependencia","fecha", "asunto","nombredirigido", "estado"]}
                    accion={[
                        {tipo: 'T', icono : 'add',                 color: 'green',  funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'edit',                color: 'orange', funcion : (data)=>{edit(data,2)} },
                        {tipo: 'B', icono : 'signal_cellular_alt', color: 'red',    funcion : (data)=>{edit(data,3)} },
                        {tipo: 'B', icono : 'picture_as_pdf',      color: 'orange', funcion : (data)=>{edit(data,4)} },
                    ]}
                    funciones={{orderBy: false, search: false, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:5, data:{}, titulo:'', tamano: ''}), (modal.vista === 1 || modal.vista === 2 || modal.vista === 3) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
import React, {useState, useEffect} from 'react';
import {EnviarRadicado} from '../../../../layout/modalFijas';
import { ModalDefaultAuto  } from '../../../../layout/modal';
import TablaGeneral from '../../../../layout/tablaGeneral';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import PdfStickers from '../pdfStickers';
import { Box} from '@mui/material';
import NewEdit from './new';
import Show from '../show';

export default function Producir(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <NewEdit data={modal.data} tipo={'I'} />,
                        <PdfStickers id={(tipo !== 0) ? modal.data.id : null}  />,
                        <NewEdit data={modal.data} tipo={'U'} /> ,
                        <EnviarRadicado id={(tipo !== 0) ? modal.data.id : null} ruta='/admin/radicacion/documento/entrante/enviar' cerrarModal={cerrarModal} />,
                        <Show id={(tipo !== 0) ? modal.data.id : null}  />
                    ];

    const tituloModal = ['Registrar documento entrante',
                        'Visualizar PDF del radicado del documento',
                        'Editar documento entrante',
                        'Enviar radicado',
                        'Visualizar información del registro del radicado'
                    ];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 0 || tipo === 2 || tipo === 4) ? 'bigFlot' : ((tipo === 1 || tipo === 3) ? 'smallFlot' :'mediumFlot')});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/radicacion/documento/entrante', {tipo:'PRODUCIR'}).then(res=>{
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
                    titulo={["Radicado", "Fecha radicado", "Usuario", "Asunto", "Dependencia", "Estado", "PDF", "Editar","Enviar", "Ver"]}
                    ver={["consecutivo", "fechaRadicado", "nombrePersonaRadica", "asunto", "dependencia", "estado"]}
                    accion={[
                        {tipo: 'T', icono : 'add',                  color: 'green',  funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'picture_as_pdf',       color: 'orange', funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'edit',                 color: 'blue',   funcion : (data)=>{edit(data,2)} },
                        {tipo: 'B', icono : 'assignment_turned_in', color: 'red',    funcion : (data)=>{edit(data,3)} },
                        {tipo: 'B', icono : 'visibility',           color: 'green',  funcion : (data)=>{edit(data,4)} },
                    ]}
                    funciones={{orderBy: false, search: false, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:5, data:{}, titulo:'', tamano: ''}), (modal.vista === 0 || modal.vista === 2 || modal.vista === 3) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
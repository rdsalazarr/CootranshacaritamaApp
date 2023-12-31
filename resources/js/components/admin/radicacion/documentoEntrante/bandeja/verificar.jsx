import React, {useState, useEffect} from 'react';
import {AceptarRadicadoDE} from '../../../../layout/modalFijas';
import { ModalDefaultAuto  } from '../../../../layout/modal';
import TablaGeneral from '../../../../layout/tablaGeneral';
import {LoaderModal} from "../../../../layout/loader";
import instance from '../../../../layout/instance';
import { Box} from '@mui/material';
import Show from '../show';

export default function Producir(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [<AceptarRadicadoDE id={modal.data.id} idFirma={modal.data.idFirma} cerrarModal={cerrarModal} />, <Show id={modal.data.id} /> ];

    const tituloModal = ['Recibir documento entrante', 'Visualizar información del registro del radicado'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 0) ? 'smallFlot' :'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/radicacion/documento/entrante/bandeja', {tipo:'VERIFICADOS'}).then(res=>{
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
                    titulo={["Radicado", "Fecha radicado", "Usuario", "Asunto", "Dependencia", "Estado","Recibir", "Ver"]}
                    ver={["consecutivo", "fechaRadicado", "nombrePersonaRadica", "asunto", "dependencia", "estado"]}
                    accion={[
                        {tipo: 'B', icono : 'content_paste_go_icon', color: 'red',   funcion : (data)=>{edit(data, 0)} },
                        {tipo: 'B', icono : 'visibility',            color: 'green', funcion : (data)=>{edit(data, 1)} },
                    ]}
                    funciones={{orderBy: true, search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:2, data:{}, titulo:'', tamano: ''}), (modal.vista === 0 ) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
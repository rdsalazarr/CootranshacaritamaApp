import React, {useState, useEffect} from 'react';
import {EjecutarProcesoAutomatico} from '../../layout/modalFijas';
import TablaGeneral from '../../layout/tablaGeneral';
import { ModalDefaultAuto } from '../../layout/modal';
import {LoaderModal} from "../../layout/loader";
import instance from '../../layout/instance';
import { Box} from '@mui/material';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales     = [ <EjecutarProcesoAutomatico data={modal.data} cerrarModal={cerrarModal} proceso='DIA' /> ];
    const tituloModal = ['Ejecutar proceso día'];


    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'smallFlot' });
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/procesos/automaticos', {tipo:'D'}).then(res=>{
            setData(res.data);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'containerSmall'}> 
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Nombre proceso','Fecha de ejecución','Tipo de proceso', 'Ejecutar']}
                    ver={["proautnombre","proautfechaejecucion","tipoProceso",]}
                    accion={[
                        {tipo: 'B', icono : 'done_all_icon',    color: 'red',  funcion : (data)=>{edit(data,0)} },
                    ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''}), inicio();}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
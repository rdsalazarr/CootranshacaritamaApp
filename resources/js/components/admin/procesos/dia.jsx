import React, {useState, useEffect} from 'react';
import TablaGeneral from '../../layout/tablaGeneral';
import { ModalDefaultAuto } from '../../layout/modal';
import {LoaderModal} from "../../layout/loader";
import Eliminar from '../../layout/modalFijas';
import instance from '../../layout/instance';
import { Box} from '@mui/material';
import Noche from "./noche";

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const modales = [
                        <Noche tipo={'I'}  />,
                    ];

    const tituloModal = ['Nueva agencia'];

    const edit = (data, tipo) =>{      
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'smallFlot' });
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/procesos/automaticos/dia').then(res=>{
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
                        {tipo: 'B', icono : 'add',    color: 'red',  funcion : (data)=>{edit(data,0)} },
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
import React, {useState, useEffect} from 'react';
import { Box, Card} from '@mui/material';
import TablaGeneral from '../../../layout/tablaGeneral';
import { ModalDefaultAuto  } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Eliminar from '../../../layout/eliminar';
import NewEdit from './new';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:1, data:{}, titulo:'', tamano:'bigFlot'});

    const modales     = [ <NewEdit data={modal.data} /> ];
    const tituloModal = ['Editar depatamento'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/departamento/list').then(res=>{
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);
    
    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'containerSmall'} >
            <Box className={'cardContainer'} >
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    <TablaGeneral
                        datos={data}
                        titulo={['Código','Nombre', 'Hace presencia','Actualizar']}
                        ver={["depacodigo","depanombre","hacePresencia"]}
                        accion={[                          
                            {tipo: 'B', icono : 'edit',   color: 'orange', funcion : (data)=>{edit(data,0)} },
                        ]}
                        funciones={{orderBy: true,search: true, pagination:true}}
                    />
                </Box>

                <ModalDefaultAuto
                    title={modal.titulo}
                    content={modales[modal.vista]}
                    close={() =>{setModal({open : false, vista:1, data:{}, titulo:'', tamano: ''}), inicio();}}
                    tam = {modal.tamano}
                    abrir ={modal.open}
                />
            </Box>
        </Box>
    )
}
import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import { Box, Typography} from '@mui/material';
import NewEdit from './new';
import Show from '../show';

export default function Producir(){

    const [loader, setLoader] = useState(false);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const modales = [
                        <NewEdit data={modal.data} tipo={'I'} />,
                        <NewEdit data={modal.data} tipo={'U'} /> ,
                        <Show id={(tipo !== 0) ? modal.data.archisid : null}  />
                    ];

    const tituloModal = ['Registrar archivo histórico',
                        'Editar archivo histórico',
                        'Visualizar información del archivo histórico'
                    ];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);    
        instance.get('/admin/archivo/historico/gestionar/list').then(res=>{
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
            <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar archivo histórico</Typography>
            </Box>

            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={["Tipo documento", "Estante", "Caja", "Carpeta", "Asunto", "Número de folios", "Editar", "Ver"]}
                    ver={["tipoDocumental", "estante", "caja", "carpeta", "asunto", "numeroFolio"]}
                    accion={[
                        {tipo: 'T', icono : 'add',        color: 'green',  funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'edit',       color: 'orange', funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'visibility', color: 'green',  funcion : (data)=>{edit(data,2)} },
                    ]}
                    funciones={{orderBy: false, search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''}), (modal.vista !== 2 ) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
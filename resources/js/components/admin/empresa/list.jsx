import React, {useState, useEffect} from 'react';
import TablaGeneral from '../../layout/tablaGeneral';
import { ModalDefaultAuto} from '../../layout/modal';
import {LoaderModal} from "../../layout/loader";
import { Box, Typography} from '@mui/material';
import instance from '../../layout/instance';
import NewEdit from './new';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});

    const modales     = [<NewEdit data={modal.data} />];
    const tituloModal = ['Editar datos de la empresa',''];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/empresa/list').then(res=>{
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
            <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar información de la empresa</Typography>
            </Box>
            <Box style={{ paddingTop: "1em"}} sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Nombre','Sigla','Lema','Dirección','Teléfono','Correo', 'Actualizar']}
                    ver={["emprnombre","emprsigla","emprlema","telefonos","emprdireccion","emprcorreo"]}
                    accion={[
                        {tipo: 'B', icono : 'edit',   color: 'orange', funcion : (data)=>{edit(data,0)} },
                    ]}
                    funciones={{orderBy: false,search: false, pagination:false}}
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
import React, {useState, useEffect} from 'react';
import TablaGeneral from '../../../layout/tablaGeneral';
import { ModalDefaultAuto } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import Eliminar from '../../../layout/modalFijas';
import instance from '../../../layout/instance';
import { Box, Typography} from '@mui/material';
import Show from '../../persona/show';
import Frm from '../../persona/new';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <Frm tipo={'I'} frm={'ASOCIADO'} url={'/admin/asociado/salve'} tpRelacion={'A'} />,
                        <Frm data={modal.data} tipo={'U'} frm={'ASOCIADO'} url={'/admin/asociado/salve'} tpRelacion={'A'} /> ,
                        <Eliminar id={(tipo === 2) ? modal.data.asocid : null} ruta={'/admin/asociado/destroy'} cerrarModal={cerrarModal} />,
                        <Show id={(tipo === 3) ? modal.data.persid : null} frm={'ASOCIADO'}/>
                    ];

    const tituloModal = ['Nuevo asociado','Editar asociado','','Visualizar la información del asociado'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 ) ? 'smallFlot' :  'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/asociado/list').then(res=>{
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
            <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar asociados</Typography>
            </Box>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Tipo documento','Documento','Nombre','Dirección', 'Correo','Estado','Actualizar','Eliminar','Ver']}
                    ver={["tipoIdentificacion","persdocumento","nombrePersona","persdireccion", "perscorreoelectronico","estado"]}
                    accion={[
                        {tipo: 'T', icono : 'add',        color: 'green',   funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'edit',       color: 'orange', funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'delete',     color: 'red',    funcion : (data)=>{edit(data,2)} },
                        {tipo: 'B', icono : 'visibility', color: 'green',  funcion : (data)=>{edit(data,3)} },
                    ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>
 
            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:3, data:{}, titulo:'', tamano: ''}), (modal.vista !== 3) ? inicio() : null;}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
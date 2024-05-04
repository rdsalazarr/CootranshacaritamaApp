import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import instanceFile from '../../../layout/instanceFile';
import {LoaderModal} from "../../../layout/loader";
import Eliminar from '../../../layout/modalFijas';
import instance from '../../../layout/instance';
import { Box, Typography} from '@mui/material';
import Tiquete from './tiquete';
import NewEdit from './new';

export default function List(){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0);
    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});

    const cerrarModal = () =>{
        setModal({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const modales = [
                        <NewEdit tipo={'I'}  />,
                        <NewEdit data={modal.data} tipo={'U'} /> ,
                        <Tiquete data={modal.data} /> ,
                        <Eliminar id={(tipo === 3) ? modal.data.rutaid : null} ruta={'/admin/despacho/ruta/destroy'} cerrarModal={cerrarModal} />
                    ];

    const tituloModal = ['Nueva ruta','Editar ruta','Asignar valores al tiquete para la ruta',''];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 3 ) ? 'smallFlot' : 'mediumFlot'});
    }

    const descargarFile = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/rutas').then(res=>{
            setLoader(false);
        })
    } 

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/despacho/ruta/list').then(res=>{
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
            <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar rutas</Typography>
            </Box>
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Departamento origen','Municipio origen','Departamento destino', 'Municipio destino','Tiene nodos','Activa','Actualizar','Tiquete','Eliminar']}
                    ver={["nombreDeptoOrigen","nombreMunicipioOrigen","nombreDeptoDestino","nombreMunicipioDestino","tieneNodos", "estado"]}
                    accion={[
                        {tipo: 'T', icono : 'add',                    color: 'green',  funcion : (data)=>{edit(data,0)} },
                        {tipo: 'B', icono : 'edit',                   color: 'orange', funcion : (data)=>{edit(data,1)} },
                        {tipo: 'B', icono : 'currency_exchange_icon', color: 'green',  funcion : (data)=>{edit(data,2)} },
                        {tipo: 'B', icono : 'delete',                 color: 'red',    funcion : (data)=>{edit(data,3)} },
                        {tipo: 'D', icono : 'file_download_icon',     color: 'orange', funcion : (data)=>{descargarFile()} },
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
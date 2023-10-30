import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import { Box, Card, Typography} from '@mui/material';
import {LoaderModal} from "../../../layout/loader";
import Eliminar from '../../../layout/modalFijas';
import instance from '../../../layout/instance';
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
                        <Eliminar id={(tipo === 2) ? modal.data.lincreid : null} ruta={'/admin/cartera/linea/credito/destroy'} cerrarModal={cerrarModal} />
                    ];

    const tituloModal = ['Nueva línea de crédito','Editar línea de crédito',''];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 ) ? 'smallFlot' :  'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/cartera/linea/credito/list').then(res=>{
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);
    
    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'containerMedium'}>
            <Card className={'cardContainer'}>
                <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar líneas de créditos</Typography>
                </Box>
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    <TablaGeneral
                        datos={data}
                        titulo={['Nombre','Tasa nominal','Monto mínimo','Monto máximo','Plazo máximo', 'Activo','Actualizar','Eliminar']}
                        ver={["lincrenombre","tasaNominal","montoMinimo","montoMaximo","plazoMaximo", "estado"]}
                        accion={[
                            {tipo: 'T', icono : 'add',   color: 'green',   funcion : (data)=>{edit(data,0)} },
                            {tipo: 'B', icono : 'edit',   color: 'orange', funcion : (data)=>{edit(data,1)} },
                            {tipo: 'B', icono : 'delete', color: 'red',    funcion : (data)=>{edit(data,2)} },
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
            </Card>
        </Box>
    )
}
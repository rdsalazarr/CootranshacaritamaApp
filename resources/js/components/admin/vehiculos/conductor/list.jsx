import React, {useState, useEffect} from 'react';
import Eliminar, {SuspenderConductor} from '../../../layout/modalFijas';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import { Box, Typography, Card} from '@mui/material';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
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
                        <Frm tipo={'I'} frm={'CONDUCTOR'} url={'/admin/direccion/transporte/conductor/salve'} tpRelacion={'C'} />,
                        <Frm data={modal.data} tipo={'U'} frm={'CONDUCTOR'} url={'/admin/direccion/transporte/conductor/salve'} tpRelacion={'C'} /> ,
                        <Eliminar id={(tipo === 2) ? modal.data.condid : null} ruta={'/admin/direccion/transporte/conductor/destroy'} cerrarModal={cerrarModal} />,
                        <Show id={(tipo === 3) ? modal.data.persid : null} frm={'CONDUCTOR'} />,
                        <SuspenderConductor id={(tipo === 4) ? modal.data.condid : null} cerrarModal={cerrarModal}/>,
                    ];

    const tituloModal = ['Nuevo conductor','Editar conductor','','Visualizar la información del conductor'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 2 || tipo === 4 ) ? 'smallFlot' :  'bigFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/direccion/transporte/conductor/list').then(res=>{
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    //suspender {tipo: 'B', icono : 'do_not_touch_icon', color: 'red',    funcion : (data)=>{edit(data,4)}},
    return (
        <Box>
            <Card className={'cardContainer'} >
                <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar conductores</Typography>
                </Box>
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    <TablaGeneral
                        datos={data}
                        titulo={['Tipo documento','Documento','Nombre','Dirección', 'Correo','Activo','Actualizar','Eliminar','Ver']}
                        ver={["tipoIdentificacion","persdocumento","nombrePersona","persdireccion", "perscorreoelectronico","estado"]}
                        accion={[
                            {tipo: 'T', icono : 'add',               color: 'green',  funcion : (data)=>{edit(data,0)} },
                            {tipo: 'B', icono : 'edit',              color: 'orange', funcion : (data)=>{edit(data,1)} },
                            {tipo: 'B', icono : 'delete',            color: 'red',    funcion : (data)=>{edit(data,2)} },
                            {tipo: 'B', icono : 'visibility',        color: 'green',  funcion : (data)=>{edit(data,3)} },                            
                        ]}
                        funciones={{orderBy: true,search: true, pagination:true}}
                    />
                </Box>
            </Card>

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
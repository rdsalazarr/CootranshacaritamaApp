import React, {useState , useEffect} from 'react';
import { Box, Typography, Card } from '@mui/material';
import TablaGeneral from '../../../layout/tablaGeneral';
import { ModalDefault } from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import NewEdit from './new';

export default function Rol(){
    const [loader, setLoader] = useState(false);
    const [data, setData] = useState([]);
    const [accion , setAccion] = useState('');
    const [dataModal, setDataModal] = useState([]);

    const modales ={
        editar  : {titulo: 'Editar rol', componente: <NewEdit data={dataModal} tipo={'U'} /> },
        insertar: {titulo: 'Nuevo rol', componente: <NewEdit tipo={'I'} /> },
        eliminar: {titulo: 'Eliminar Registro' , componente:  <NewEdit data={dataModal} tipo={'D'} />}      
    };

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/rol/list').then(res=>{
            setData(res.data);
            setLoader(false);
        })
    }

    useEffect(()=>{
        inicio();
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'containerSmall'} >
            <Card className={'cardContainer'} >
                <Box><Typography  component={'h2'} className={'titleGeneral'}>Gestionar rol</Typography></Box>   
                <Box>
                    <TablaGeneral 
                        datos={data}
                        titulo={['Nombre','Activo','Actualizar','Eliminar']}
                        ver={["rolnombre","estado"]}
                        accion={[
                            {tipo: 'B', icono : 'edit' ,  color: 'orange', funcion : (datos)=>{setAccion('editar'); setDataModal(datos);}},
                            {tipo: 'B', icono : 'delete', color: 'red',    funcion : (datos)=>{setAccion('eliminar'); setDataModal(datos);}},
                            {tipo: 'T', icono : 'add' ,   color: 'green',  funcion : (datos)=>{setAccion('insertar'); setDataModal(datos);}}
                        ]}
                        funciones={{orderBy: false,search: false, pagination:false}}
                    />
                </Box> 

                {accion !== ''? 
                    <ModalDefault 
                        title={modales[accion]["titulo"]} 
                        content={modales[accion]["componente"]} 
                        close={() =>{setAccion(''); setDataModal([]);inicio();}} 
                        tam = 'mediumFlot' 
                    /> 
                : null }
            </Card>
        </Box>
    )
}
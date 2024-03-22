import React, {useState, useEffect} from 'react';
import TablaGeneral from '../../../layout/tablaGeneral';
import instanceFile from '../../../layout/instanceFile';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import { Box} from '@mui/material';

export default function Show({datos}){

    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);

    const descargarFile = () =>{
        setLoader(true);
        instanceFile.post('/admin/exportar/datos/auditoria/usuario', {codigo: datos.usuaid}).then(res=>{
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/auditoria/usuario/show', {codigo: datos.usuaid}).then(res=>{
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
            <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                <TablaGeneral
                    datos={data}
                    titulo={['Documento','Nombre completo', 'Usuario', 'IP acceso','Fecha ingreso', 'Fecha salida']}
                    ver={["tipoDocumento","nombreUsuario","usuanick", "ingsisipacceso","ingsisfechahoraingreso","ingsisfechahorasalida"]}
                    accion={[{tipo: 'D', icono : 'file_download_icon', color: 'orange', funcion : (data)=>{descargarFile()} } ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>
        </Box>
    )
}
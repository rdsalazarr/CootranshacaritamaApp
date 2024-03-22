import React, {useState, useEffect} from 'react';
import TablaGeneral from '../../../layout/tablaGeneral';
import {ModalDefaultAuto} from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import { Box} from '@mui/material';
import Show from './show';

export default function List(){

    const [modal, setModal] = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});
    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);

    const modales     = [<Show datos={modal.data} /> ];
    const tituloModal = ['Visualizar ingreso de usuario'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/auditoria/usuario/list').then(res=>{
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
                    titulo={['Documento','Nombre','Apellidos','Usuario', 'Correo','Bloqueado','¿Cambiar contraseña?','N° caja','Activo','Ingresos']}
                    ver={["tipoDocumento","usuanombre","usuaapellidos","usuanick", "usuaemail","bloqueado","cambiarpassword","cajanumero", "estado"]}
                    accion={[{tipo: 'B', icono : 'visibility', color: 'green', funcion : (data)=>{edit(data, 0)} } ]}
                    funciones={{orderBy: true,search: true, pagination:true}}
                />
            </Box>

            <ModalDefaultAuto
                title={modal.titulo}
                content={modales[modal.vista]}
                close={() =>{setModal({open : false, vista:2, data:{}, titulo:'', tamano: ''})}}
                tam = {modal.tamano}
                abrir ={modal.open}
            />
        </Box>
    )
}
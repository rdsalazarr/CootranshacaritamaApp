import React, {useState, useEffect} from 'react';
import { ModalDefaultAuto } from '../../../layout/modal';
import TablaGeneral from '../../../layout/tablaGeneral';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import VisualizarPdf from './visualizarPdf';
import { Box} from '@mui/material';

export default function Closed(){

    const [data, setData]     = useState([]);
    const [loader, setLoader] = useState(true);
    const [modal, setModal]   = useState({open : false, vista:2, data:{}, titulo:'', tamano:'bigFlot'});
    const modales     = [<VisualizarPdf id={modal.data.plarutid} /> ];
    const tituloModal = ['Visualizar PDF del formato del comprobante contable'];

    const edit = (data, tipo) =>{
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: 'smallFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/caja/cerrar/movimiento').then(res=>{
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
                    titulo={['Fecha movimeinto','Código contable','Descripción', 'Débito','Crédito']}
                    ver={["cocodefechahora","codigoContable","descripcionContable","debito","Crédito"]}
                    accion={[]}
                    funciones={{orderBy: true, search: true, pagination:true}}
                />
            </Box>

            <Grid container spacing={2}>
                <Grid item xl={3} md={3} sm={6} xs={12}>
                    Saldo inicial
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    Valor débito
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    Valor crédito
                </Grid>

                <Grid item xl={3} md={3} sm={6} xs={12}>
                    Cerrar caja
                </Grid>
            </Grid>

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
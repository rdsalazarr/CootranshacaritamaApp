import React, {useState, useEffect} from 'react';
import {SolicitarFirma} from '../../../layout/modalFijas';
import TablaGeneral from '../../../layout/tablaGeneral';
import {ModalDefaultAuto} from '../../../layout/modal';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import VisualizarPdf from '../visualizarPdf';
import VerificarArea from '../verificarArea';
import {Box} from '@mui/material';
import NewEdit from './new';

export default function Producir(){

    const [modal, setModal] = useState({open : false, vista:3, data:{}, titulo:'', tamano:'bigFlot'});
    const [mensajeTipoDocumental, setMensajeTipoDocumental] = useState('');
    const [areaSeleccionada, setAreaSeleccionada] = useState([]);
    const [idDocumento, setIdDocumento] = useState(null);
    const [loader, setLoader] = useState(true);
    const [accion, setAccion] = useState('L');
    const [data, setData] = useState([]);
    const [tipo, setTipo] = useState(0); 

    const cerrarModal = () =>{
        setModal({open : false, vista:5, data:{}, titulo:'', tamano:'bigFlot'});
    }

    const verificarAccion = (data) =>{
        setIdDocumento(data.id);
        setAccion('U');
    }

    const verificarArea = (area) =>{
        setAreaSeleccionada(area);
        setMensajeTipoDocumental('Registrar nuevo tipo documental acta del área '+area.depenombre.toLowerCase());
        setAccion('N');
    }

    const modales = [
                        <VerificarArea cerrarModal={cerrarModal} verificarArea={verificarArea} ruta={'acta'} />,
                        <SolicitarFirma id={(tipo !== 0) ? modal.data.id : null} ruta={'acta'} cerrarModal={cerrarModal} />,
                        <VisualizarPdf id={(tipo !== 0) ? modal.data.id : null} ruta={'acta'} />
                    ];

    const tituloModal = ['Selecionar área de producción documental',
                        'Solicitar firma del tipo documental',
                        'Visualizar el tipo documental en formato PDF'];

    const edit = (data, tipo) =>{
        setTipo(tipo);
        setModal({open: true, vista: tipo, data:data, titulo: tituloModal[tipo], tamano: (tipo === 0 || tipo === 1) ? 'smallFlot' : 'mediumFlot'});
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/producion/documental/acta/list', {tipo:'PRODUCIR'}).then(res=>{
            setData(res.data);
            setLoader(false);
        })
        setAccion('L');
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Box>
            {(accion === 'L') ?
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                    <TablaGeneral
                        datos={data}
                        titulo={['Consecutivo', 'Dependencia','Fecha','Hora acta','Dirigido','Estado','Editar','Solicitar','PDF']}
                        ver={["consecutivo", "dependencia","fecha", "horaActa","nombredirigido", "estado"]}
                        accion={[
                            {tipo: 'T', icono : 'add',                 color: 'green',  funcion : (data)=>{edit(data, 0)} },
                            {tipo: 'B', icono : 'edit',                color: 'orange', funcion : (data)=>{verificarAccion(data)} },
                            {tipo: 'B', icono : 'signal_cellular_alt', color: 'red',    funcion : (data)=>{edit(data, 1)} },
                            {tipo: 'B', icono : 'picture_as_pdf',      color: 'orange', funcion : (data)=>{edit(data, 2)} },
                        ]}
                        funciones={{orderBy: false, search: false, pagination:true}}
                    />
                </Box>
            : ((accion === 'N') ?
                <Box>
                    <NewEdit tipo={'I'} area={areaSeleccionada} ruta='P' volver={inicio} mensaje={mensajeTipoDocumental} />
                </Box>
                :  
                <Box>
                    <NewEdit tipo={'U'} id={idDocumento} ruta='P' volver={inicio}  mensaje={'Editar tipo documental acta'} />
                </Box>
                )
            }

            <ModalDefaultAuto
                title   = {modal.titulo}
                content = {modales[modal.vista]}
                close   = {() =>{setModal({open : false, vista:5, data:{}, titulo:'', tamano: ''}), (modal.vista === 1 || modal.vista === 2 || modal.vista === 3) ? inicio() : null;}}
                tam     = {modal.tamano}
                abrir   = {modal.open}
            />
        </Box>
    )
}
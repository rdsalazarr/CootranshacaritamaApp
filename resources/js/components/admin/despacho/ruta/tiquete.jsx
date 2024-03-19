import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Icon, Table, TableHead, TableBody, TableRow, TableCell} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {FormatearNumero} from "../../../layout/general";
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import EditIcon from '@mui/icons-material/Edit';
import AddIcon from '@mui/icons-material/Add';

ValidatorForm.addValidationRule('isTasaNominal', (value) => {
    // Verificar si el valor es un número válido en formato "10.50"
    const regex = /^\d+(\.\d{1,2})?$/;
    if (!regex.test(value)) {
      return false;
    }
  
    // Verificar si el número está en el rango de 0 a 100 (porcentaje válido)
    const numValue = parseFloat(value);
    return numValue >= 0 && numValue <= 100;
});

export default function Tiquete({data}){

    const [formDataTiquete, setFormDataTiquete] = useState({deptoIdOrigen:'',       municipioIdOrigen:'',      nombreMunicipioOrigen:'',   deptoIdDestino:'',
                                                            municipioIdDestino:'',  nombreMunicipioDestino:'', valorTiquete: '',           valorTiqueteMostrar: '',
                                                            valorSeguro:'',         valorSeguroMostrar:'',     valorEstampilla:'0',        valorEstampillaMostrar:'',
                                                            fondoReposicion:'1.00', valorFondoRecaudo:'200',   valorFondoRecaudoMostrar:'' });
    const [formData, setFormData]               = useState({codigo:data.rutaid});
    const [municipiosDestino, setMunicipiosDestino] = useState([]);
    const [tarifaTiquetes, setTarifaTiquetes] = useState([]);    
    const [tipoProceso, setTipoProceso] = useState('I');
    const [habilitado, setHabilitado] = useState(true);
    const [municipios, setMunicipios] = useState([]);
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormDataTiquete(prev => ({...prev, [e.target.name]: e.target.value}))
    }
 
    const handleSubmit = () =>{
        if(tarifaTiquetes.length === 0){
            showSimpleSnackbar('Debe adicionar como mínimo una tarifa de tiquete para la ruta', 'error');
            return
        }
 
        let newFormData            = {...formData}
        newFormData.tarifaTiquetes = tarifaTiquetes;
        setLoader(true);
        instance.post('/admin/despacho/ruta/salvar/datos/tiquete', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (res.success) ? setHabilitado(false) : null;
            setLoader(false);
        })
    }

    const consultarMunicipiosDestino = (e) =>{
        const newFormDataTiquete  = {...formDataTiquete}
        const municipiosOrigen    = municipios.filter((mun) => mun.muniid == e.target.value);
        const municipioFiltrados  = municipios.filter(mun => mun.muniid !== e.target.value);
        newFormDataTiquete.deptoIdOrigen         = municipiosOrigen[0].depaid;
        newFormDataTiquete.nombreMunicipioOrigen = municipiosOrigen[0].muninombre;
        newFormDataTiquete.municipioIdOrigen     = e.target.value;
        setMunicipiosDestino(municipioFiltrados);
        setFormDataTiquete(newFormDataTiquete);
    }

    const obtenerMunicipioDestino = (e) =>{
        const newFormDataTiquete  = {...formDataTiquete}
        const municipiosDestino   = municipios.filter((mun) => mun.muniid == e.target.value);
        newFormDataTiquete.deptoIdDestino         = municipiosDestino[0].depaid;
        newFormDataTiquete.nombreMunicipioDestino = municipiosDestino[0].muninombre;
        newFormDataTiquete.municipioIdDestino     = e.target.value;
        setFormDataTiquete(newFormDataTiquete);
    }

    const adicionarFilaTarifa = () =>{
 
        if(tipoProceso === 'I' && tarifaTiquetes.some(nod => nod.municipioIdOrigen == formDataTiquete.municipioIdOrigen && nod.municipioIdDestino == formDataTiquete.municipioIdDestino)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newTarifaTiquetes                 = [...tarifaTiquetes];
        const resultadoNombreMunicipioOrigen  = municipios.filter((mun) => mun.muniid == formDataTiquete.municipioIdOrigen);
        const resultadoNombreMunicipioDestino = municipios.filter((mun) => mun.muniid == formDataTiquete.municipioIdDestino);
        if(tipoProceso === 'I'){
            newTarifaTiquetes.push({identificador:'', deptoIdOrigen:formDataTiquete.deptoIdOrigen,  municipioIdOrigen:formDataTiquete.municipioIdOrigen,   nombreMunicipioOrigen: resultadoNombreMunicipioOrigen[0].muninombre, 
                                                     deptoIdDestino:formDataTiquete.deptoIdDestino, municipioIdDestino:formDataTiquete.municipioIdDestino, nombreMunicipioDestino: resultadoNombreMunicipioDestino[0].muninombre,
                                    valorTiquete: formDataTiquete.valorTiquete,  valorTiqueteMostrar: FormatearNumero({numero: formDataTiquete.valorTiquete}), valorSeguro: formDataTiquete.valorSeguro, 
                                    valorSeguroMostrar: FormatearNumero({numero: formDataTiquete.valorSeguro}), valorEstampilla: formDataTiquete.valorEstampilla, valorEstampillaMostrar: FormatearNumero({numero: formDataTiquete.valorEstampilla}),
                                    fondoReposicion: formDataTiquete.fondoReposicion, valorFondoRecaudo: formDataTiquete.valorFondoRecaudo, valorFondoRecaudoMostrar: FormatearNumero({numero: formDataTiquete.valorFondoRecaudo}), estado: 'I'}); 
           setTarifaTiquetes(newTarifaTiquetes);
        }else{
            let arrayTarifaTiquetes = [];
            tarifaTiquetes.map((res) =>{
                if(res.identificador === formDataTiquete.identificador){
                    arrayTarifaTiquetes.push({ identificador:res.identificador, deptoIdOrigen: formDataTiquete.deptoIdOrigen, municipioIdOrigen:formDataTiquete.municipioIdOrigen, nombreMunicipioOrigen:resultadoNombreMunicipioOrigen[0].muninombre,
                        deptoIdDestino:formDataTiquete.deptoIdDestino, municipioIdDestino:formDataTiquete.municipioIdDestino, nombreMunicipioDestino: resultadoNombreMunicipioDestino[0].muninombre,
                        valorTiquete: formDataTiquete.valorTiquete, valorTiqueteMostrar: FormatearNumero({numero: formDataTiquete.valorTiquete}),
                        valorSeguro: formDataTiquete.valorSeguro, valorSeguroMostrar: FormatearNumero({numero: formDataTiquete.valorSeguro}),
                        valorEstampilla: formDataTiquete.valorEstampilla, valorEstampillaMostrar: FormatearNumero({numero: formDataTiquete.valorEstampilla}),
                        fondoReposicion: formDataTiquete.fondoReposicion,  valorFondoRecaudo: formDataTiquete.valorFondoRecaudo, 
                        valorFondoRecaudoMostrar: FormatearNumero({numero: formDataTiquete.valorFondoRecaudo}), estado: 'U' });
                }else{
                    arrayTarifaTiquetes.push({identificador:res.identificador, deptoIdOrigen: res.deptoIdOrigen, municipioIdOrigen: res.municipioIdOrigen, nombreMunicipioOrigen:res.nombreMunicipioOrigen,
                        deptoIdDestino:res.deptoIdDestino, municipioIdDestino:res.municipioIdDestino, nombreMunicipioDestino: res.nombreMunicipioDestino,
                        valorTiquete: res.valorTiquete, valorTiqueteMostrar: res.valorTiqueteMostrar,valorSeguro:res.valorSeguro, valorSeguroMostrar:res.valorSeguroMostrar, 
                        valorEstampilla: res.valorEstampilla, valorEstampillaMostrar: res.valorEstampillaMostrar,
                        fondoReposicion: formDataTiquete.fondoReposicion,  valorFondoRecaudo: formDataTiquete.valorFondoRecaudo, 
                        valorFondoRecaudoMostrar: FormatearNumero({numero: formDataTiquete.valorFondoRecaudo}), estado: res.estado});
                }
            })
            setTarifaTiquetes(arrayTarifaTiquetes);
        }

        setFormDataTiquete({deptoIdOrigen:'',       municipioIdOrigen:'',      nombreMunicipioOrigen:'',   deptoIdDestino:'',
                            municipioIdDestino:'',  nombreMunicipioDestino:'', valorTiquete: '',           valorTiqueteMostrar: '',
                            valorSeguro:'',         valorSeguroMostrar:'',     valorEstampilla:'0',        valorEstampillaMostrar:'',
                            fondoReposicion:'1.00', valorFondoRecaudo:'200',   valorFondoRecaudoMostrar:'' });
        setTipoProceso('I');
    }

    const eliminarFilaTarifa = (id) =>{
        let newTarifaTiquetes = [];
        let estado            = 'I';
        tarifaTiquetes.map((res,i) =>{
            if (i === id) {
                estado = res.estado === 'U' ? 'D' : 'U';
            } else {
                estado = (res.estado === 'D' || res.estado === 'U') ? res.estado : 'I';
            }

            newTarifaTiquetes.push({ identificador:res.identificador, deptoIdOrigen: res.deptoIdOrigen, municipioIdOrigen: res.municipioIdOrigen, nombreMunicipioOrigen:res.nombreMunicipioOrigen, 
                                    deptoIdDestino: res.deptoIdDestino, municipioIdDestino: res.municipioIdDestino, nombreMunicipioDestino:res.nombreMunicipioDestino,  valorTiquete: res.valorTiquete, 
                                    valorTiqueteMostrar: res.valorTiqueteMostrar,valorSeguro: res.valorSeguro,valorSeguroMostrar: res.valorSeguroMostrar, valorEstampilla: res.valorEstampilla, 
                                    valorEstampillaMostrar: res.valorEstampillaMostrar, fondoReposicion: res.fondoReposicion, valorFondoRecaudo: res.valorFondoRecaudo,
                                    valorFondoRecaudoMostrar: FormatearNumero({numero: formDataTiquete.valorFondoRecaudoMostrar}), estado: estado }); 
        })
        setTarifaTiquetes(newTarifaTiquetes);
    }

    const editarFilaTarifa = (id) =>{
        const resultadoTarifaTiquetes         = tarifaTiquetes.filter((tarifa) => tarifa.identificador == id);
        let newFormDataTiquete                = {...formDataTiquete}
        newFormDataTiquete.identificador      = resultadoTarifaTiquetes[0].identificador;
        newFormDataTiquete.municipioIdOrigen  = resultadoTarifaTiquetes[0].municipioIdOrigen;
        newFormDataTiquete.municipioIdDestino = resultadoTarifaTiquetes[0].municipioIdDestino;
        newFormDataTiquete.valorTiquete       = resultadoTarifaTiquetes[0].valorTiquete;
        newFormDataTiquete.valorSeguro        = resultadoTarifaTiquetes[0].valorSeguro;
        newFormDataTiquete.valorEstampilla    = resultadoTarifaTiquetes[0].valorEstampilla;
        newFormDataTiquete.fondoReposicion    = resultadoTarifaTiquetes[0].fondoReposicion;
        newFormDataTiquete.valorFondoRecaudo  = resultadoTarifaTiquetes[0].valorFondoRecaudo;

        const municipioFiltrados  = municipios.filter(mun => mun.muniid !== resultadoTarifaTiquetes[0].municipioIdOrigen);
        setMunicipiosDestino(municipioFiltrados);
        setFormDataTiquete(newFormDataTiquete);
        setTipoProceso('U');
    }

    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/despacho/ruta/listar/datos/tiquete', {codigo:formData.codigo}).then(res=>{
            let tarifaTiquetes    = res.tarifaTiquetes;
            let municipioRutas    = res.municipioRutas;
            let newMunicipioRutas = municipioRutas.sort((a, b) => a.muninombre.localeCompare(b.muninombre));
            setMunicipios(newMunicipioRutas);

            let newValorTiquetes = [];
            tarifaTiquetes.forEach(function(tiq){
                const municipioOrigenEncontrado = municipioRutas.find(mun => mun.muniid === tiq.tartiqmuniidorigen);
                const municipioDestinoEncontrado = municipioRutas.find(mun => mun.muniid === tiq.tartiqmuniiddestino);
                if(municipioOrigenEncontrado){
                    newValorTiquetes.push({
                        identificador:            tiq.tartiqid,
                        deptoIdOrigen:            tiq.tartiqdepaidorigen,
                        municipioIdOrigen:        tiq.tartiqmuniidorigen,
                        deptoIdDestino:           tiq.tartiqdepaiddestino,
                        municipioIdDestino:       tiq.tartiqmuniiddestino,
                        nombreMunicipioOrigen:    municipioOrigenEncontrado.muninombre,
                        nombreMunicipioDestino:   municipioDestinoEncontrado.muninombre,
                        valorTiquete:             tiq.tartiqvalor,
                        valorTiqueteMostrar:      tiq.valorTiquete,
                        valorSeguro:              tiq.tartiqvalorseguro,
                        valorSeguroMostrar:       tiq.valorSeguro,
                        valorEstampilla:          tiq.tartiqvalorestampilla,
                        valorEstampillaMostrar:   tiq.valorEstampilla,
                        fondoReposicion:          tiq.tartiqfondoreposicion,
                        valorFondoRecaudo:        tiq.tartiqvalorfondorecaudo,
                        valorFondoRecaudoMostrar: tiq.valorFondoRecaudo,
                        estado: 'U'
                    });
                }
            });

            setTarifaTiquetes(newValorTiquetes);
            setLoader(false);
        })
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={adicionarFilaTarifa} >
                <Grid container spacing={2}>
            
                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'municipioIdOrigen'}
                            value={formDataTiquete.municipioIdOrigen}
                            label={'Municipio origen'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={consultarMunicipiosDestino}
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipios.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <SelectValidator
                            name={'municipioIdDestino'}
                            value={formDataTiquete.municipioIdDestino}
                            label={'Municipio destino'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={obtenerMunicipioDestino} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipiosDestino.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <NumberValidator fullWidth
                            id={"valorTiquete"}
                            name={"valorTiquete"}
                            label={"Valor tiquete"}
                            value={formDataTiquete.valorTiquete}
                            type={'numeric'}
                            require={['required', 'maxStringLength:9']}
                            error={['Campo obligatorio','Número máximo permitido es el 999999999']}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <NumberValidator fullWidth
                            id={"valorSeguro"}
                            name={"valorSeguro"}
                            label={"Valor seguro"}
                            value={formDataTiquete.valorSeguro}
                            type={'numeric'}
                            require={['required', 'maxStringLength:9']}
                            error={['Campo obligatorio','Número máximo permitido es el 999999']}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <NumberValidator fullWidth
                            id={"valorEstampilla"}
                            name={"valorEstampilla"}
                            label={"Valor estampilla"}
                            value={formDataTiquete.valorEstampilla}
                            type={'numeric'}
                            require={['maxStringLength:9']}
                            error={['Número máximo permitido es el 999999']}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <TextValidator
                            name={'fondoReposicion'}
                            value={formDataTiquete.fondoReposicion}
                            label={'Fondo de reposición'}
                            className={'inputGeneral'} 
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required", 'isTasaNominal']}
                            errorMessages={["Campo obligatorio", 'Ingrese un porcentaje válido']}
                            onChange={handleChange}
                        />
                    </Grid>

                    <Grid item xl={3} md={3} sm={6} xs={12}>
                        <NumberValidator fullWidth
                            id={"valorFondoRecaudo"}
                            name={"valorFondoRecaudo"}
                            label={"Valor fondo recaudo"}
                            value={formDataTiquete.valorFondoRecaudo}
                            type={'numeric'}
                            require={['required', 'maxStringLength:4']}
                            error={['Campo obligatorio','Número máximo permitido es el 9999']}
                            onChange={handleChange}
                        />
                    </Grid>
          
                    <Grid item xl={3} md={3} sm={12} xs={12} style={{textAlign:'center'}}>
                        <Button type={"submit"} className={'modalBtnIcono'} 
                            startIcon={(tipoProceso === 'I') ? <AddIcon className='icono' /> : <EditIcon className='icono' /> }> {(tipoProceso === 'I') ? "Agregar" : "Actualizar"}
                        </Button>
                    </Grid>
                </Grid>

            </ValidatorForm>

            {(tarifaTiquetes.length > 0) ?
                <Fragment>
                        <Grid container spacing={2}>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='divisionFormulario'>
                                Tarifa de tiquete adicionados a la ruta
                            </Box>
                        </Grid>

                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                <Table key={'tablePersona'} className={'tableAdicional'} sx={{width: '90%', margin:'auto'}} sm={{width: '96%', margin:'auto'}}>
                                    <TableHead>
                                        <TableRow>
                                            <TableCell>Municipio origen</TableCell>
                                            <TableCell>Municipio destino</TableCell>
                                            <TableCell>Valor tiquete</TableCell>
                                            <TableCell>Valor seguro</TableCell>
                                            <TableCell>Valor estampilla</TableCell>
                                            <TableCell>Fondo de reposición</TableCell>
                                            <TableCell>Fondo de recaudo</TableCell>
                                            <TableCell style={{width: '10%'}} className='cellCenter'>Editar </TableCell>
                                            <TableCell style={{width: '10%'}} className='cellCenter'>Eliminar </TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>

                                    { tarifaTiquetes.map((tari, a) => {
                                        return(
                                            <TableRow key={'rowA-' +a} className={(tari['estado'] == 'D')? 'tachado': null}>

                                                <TableCell>
                                                    {tari['nombreMunicipioOrigen']}
                                                </TableCell>

                                                <TableCell>
                                                    {tari['nombreMunicipioDestino']}
                                                </TableCell>

                                                <TableCell>
                                                    $ {tari['valorTiqueteMostrar']}
                                                </TableCell>

                                                <TableCell>
                                                    $ {tari['valorSeguroMostrar']}
                                                </TableCell>

                                                <TableCell>
                                                    $ {tari['valorEstampillaMostrar']}
                                                </TableCell>

                                                <TableCell>
                                                    {tari['fondoReposicion']} %
                                                </TableCell>

                                                <TableCell>
                                                    $ {tari['valorFondoRecaudoMostrar']}
                                                </TableCell>

                                                <TableCell className='cellCenter'>
                                                    {(tari['estado'] == 'U')?
                                                        <Icon key={'iconDelete'+a} className={'icon top orange'}
                                                            onClick={() => {editarFilaTarifa(tari['identificador']);}}
                                                        >edit</Icon>
                                                    : null}
                                                </TableCell>

                                                <TableCell className='cellCenter'>
                                                    <Icon key={'iconDelete'+a} className={'icon top red'}
                                                            onClick={() => {eliminarFilaTarifa(a);}}
                                                        >clear</Icon>
                                                </TableCell>
                                            </TableRow>
                                            );
                                        })
                                    }
                                    </TableBody>
                                </Table>
                            </Box>
                        </Grid>
                    </Grid>

                    <Grid container direction="row"  justifyContent="right">
                        <Stack direction="row" spacing={2}>
                            <Button type={"button"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                                startIcon={<SaveIcon />} onClick={() => {handleSubmit()}}>Registrar
                            </Button>
                        </Stack>
                    </Grid>

                </Fragment>
            : null}

        </Fragment>
    )
}
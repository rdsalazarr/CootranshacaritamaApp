import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Icon, Table, TableHead, TableBody, TableRow, TableCell} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
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

    const [formDataTiquete, setFormDataTiquete] = useState({municipioId:'', nombreMunicipio:'', valorTiquete: '', valorTiqueteMostrar: '', valorSeguro:'', valorSeguroMostrar:'', valorEstampilla:'0', valorEstampillaMostrar:'', fondoReposicion:'1.00'});
    const [formData, setFormData]               = useState({codigo:data.rutaid, departamento: data.depaiddestino});
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

    const formatearNumero = (numero) =>{
        const opciones = { style: 'decimal', minimumFractionDigits: 0, maximumFractionDigits: 2 };
        return Number(numero).toLocaleString('es-CO', opciones);
    }

    const adicionarFilaTarifa = () =>{
 
        if(tipoProceso === 'I' && tarifaTiquetes.some(nod => nod.municipioId == formDataTiquete.municipioId)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newTarifaTiquetes          = [...tarifaTiquetes];
        const resultadoNombreMunicipio = municipios.filter((mun) => mun.muniid == formDataTiquete.municipioId);
        if(tipoProceso === 'I'){
            newTarifaTiquetes.push({identificador:'', municipioId:formDataTiquete.municipioId, nombreMunicipio: resultadoNombreMunicipio[0].muninombre, valorTiquete: formDataTiquete.valorTiquete, 
                                valorTiqueteMostrar: formatearNumero(formDataTiquete.valorTiquete), valorSeguro: formDataTiquete.valorSeguro, valorSeguroMostrar: formatearNumero(formDataTiquete.valorSeguro),
                                valorEstampilla: formDataTiquete.valorEstampilla, valorEstampillaMostrar: formatearNumero(formDataTiquete.valorEstampilla),
                                fondoReposicion: formDataTiquete.fondoReposicion, estado: 'I'}); 
            setTarifaTiquetes(newTarifaTiquetes);
        }else{
            let arrayTarifaTiquetes = [];
            tarifaTiquetes.map((res,i) =>{
                if(res.identificador === formDataTiquete.identificador){
                    arrayTarifaTiquetes.push({ identificador:res.identificador, municipioId: formDataTiquete.municipioId, nombreMunicipio:resultadoNombreMunicipio[0].muninombre,
                        valorTiquete: formDataTiquete.valorTiquete, valorTiqueteMostrar: formatearNumero(formDataTiquete.valorTiquete),
                        valorSeguro: formDataTiquete.valorSeguro, valorSeguroMostrar: formatearNumero(formDataTiquete.valorSeguro),
                        valorEstampilla: formDataTiquete.valorEstampilla, valorEstampillaMostrar: formatearNumero(formDataTiquete.valorEstampilla),
                        fondoReposicion: formDataTiquete.fondoReposicion, estado: 'U' });
                }else{
                    arrayTarifaTiquetes.push({identificador:res.identificador, municipioId: res.municipioId,nombreMunicipio:res.nombreMunicipio,
                        valorTiquete: res.valorTiquete, valorTiqueteMostrar: res.valorTiqueteMostrar,valorSeguro:res.valorSeguro, valorSeguroMostrar:res.valorSeguroMostrar, 
                        valorEstampilla: res.valorEstampilla, valorEstampillaMostrar: res.valorEstampillaMostrar,
                        fondoReposicion: res.fondoReposicion, estado: res.estado});
                }
            })
            setTarifaTiquetes(arrayTarifaTiquetes);
        }

        setFormDataTiquete({municipioId:'', nombreMunicipio:'', valorTiquete: '', valorSeguro:'',valorEstampilla:'0', fondoReposicion:'1.00' });
        setTipoProceso('I');
    }

    const eliminarFilaTarifa = (id) =>{
        let newTarifaTiquetes = [];
        tarifaTiquetes.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newTarifaTiquetes.push({ identificador:res.identificador, municipioId: res.municipioId,nombreMunicipio:res.nombreMunicipio, valorTiquete: res.valorTiquete, 
                                        valorTiqueteMostrar: res.valorTiqueteMostrar, valorSeguro: res.valorSeguro, valorSeguroMostrar: res.valorSeguroMostrar, 
                                        valorEstampilla: res.valorEstampilla, valorEstampillaMostrar: res.valorEstampillaMostrar, fondoReposicion: res.fondoReposicion,estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newTarifaTiquetes.push({identificador:res.identificador, municipioId: res.municipioId,nombreMunicipio:res.nombreMunicipio, valorTiquete: res.valorTiquete,
                                        valorTiqueteMostrar: res.valorTiqueteMostrar, valorSeguro: res.valorSeguro, valorSeguroMostrar: res.valorSeguroMostrar, 
                                        valorEstampilla: res.valorEstampilla, valorEstampillaMostrar: res.valorEstampillaMostrar, fondoReposicion: res.fondoReposicion,estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newTarifaTiquetes.push({identificador:res.identificador, municipioId: res.municipioId,nombreMunicipio:res.nombreMunicipio, valorTiquete: res.valorTiquete,
                                        valorTiqueteMostrar: res.valorTiqueteMostrar, valorSeguro: res.valorSeguro, valorSeguroMostrar: res.valorSeguroMostrar, 
                                        valorEstampilla: res.valorEstampilla, valorEstampillaMostrar: res.valorEstampillaMostrar, fondoReposicion: res.fondoReposicion,estado:res.estado});
            }else{
                if(i != id){
                    newTarifaTiquetes.push({identificador:res.identificador,municipioId: res.municipioId,nombreMunicipio:res.nombreMunicipio, valorTiquete: res.valorTiquete,
                                            valorTiqueteMostrar: res.valorTiqueteMostrar, valorSeguro: res.valorSeguro, valorSeguroMostrar: res.valorSeguroMostrar,
                                            valorEstampilla: res.valorEstampilla, valorEstampillaMostrar: res.valorEstampillaMostrar, fondoReposicion: res.fondoReposicion,estado: 'I' });
                }
            }
        })
        setTarifaTiquetes(newTarifaTiquetes);
    }

    const editarFilaTarifa = (id) =>{
        const resultadoTarifaTiquetes      = tarifaTiquetes.filter((tarifa) => tarifa.identificador == id);   
        let newFormDataTiquete             = {...formDataTiquete}
        newFormDataTiquete.identificador   = resultadoTarifaTiquetes[0].identificador;
        newFormDataTiquete.municipioId     = resultadoTarifaTiquetes[0].municipioId;
        newFormDataTiquete.valorTiquete    = resultadoTarifaTiquetes[0].valorTiquete;
        newFormDataTiquete.valorSeguro     = resultadoTarifaTiquetes[0].valorSeguro;
        newFormDataTiquete.valorEstampilla = resultadoTarifaTiquetes[0].valorEstampilla;
        newFormDataTiquete.fondoReposicion = resultadoTarifaTiquetes[0].fondoReposicion;
        setFormDataTiquete(newFormDataTiquete);
        setTipoProceso('U');
    }

    useEffect(()=>{
        setLoader(true);
        instance.post('/admin/despacho/ruta/listar/datos/tiquete', {codigo:formData.codigo}).then(res=>{
            let tarifaTiquetes    = res.tarifaTiquetes;
            let municipioRutas    = res.municipioRutas;
            let newMunicipioRutas = municipioRutas.sort((a, b) => a.muninombre.localeCompare(b.muninombre));
            setMunicipios(newMunicipioRutas);

            let newValorTiquetes = [];
            tarifaTiquetes.forEach(function(tiq){
                const municipioEncontrado = municipioRutas.find(mun => mun.muniid === tiq.muniiddestino);
                if(municipioEncontrado){
                    newValorTiquetes.push({
                        identificador:          tiq.tartiqid,
                        municipioId:            tiq.muniiddestino,
                        nombreMunicipio:        municipioEncontrado.muninombre,
                        valorTiquete:           tiq.tartiqvalor,
                        valorTiqueteMostrar:    tiq.valorTiquete,
                        valorSeguro:            tiq.tartiqvalorseguro,
                        valorSeguroMostrar:     tiq.valorSeguro,
                        valorEstampilla:        tiq.tartiqvalorestampilla,
                        valorEstampillaMostrar: tiq.valorEstampilla,
                        fondoReposicion:        tiq.tartiqfondoreposicion,
                        estado: 'U'
                    });
                }
            });

            setTarifaTiquetes(newValorTiquetes);
            setLoader(false);
        })
    }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <Fragment>
            <ValidatorForm onSubmit={adicionarFilaTarifa} >
                <Grid container spacing={2}>
            
                    <Grid item xl={6} md={6} sm={12} xs={12}>
                        <Box className='frmTexto'>
                            <label>Departamento origen</label>
                            <span>{data.nombreDeptoOrigen}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={6} md={6} sm={12} xs={12}>
                        <Box className='frmTexto'>
                            <label>Municipio origen</label>
                            <span>{data.nombreMunicipioOrigen}</span>
                        </Box>
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
                        <SelectValidator
                            name={'municipioId'}
                            value={formDataTiquete.municipioId}
                            label={'Nodo del municipio destino'}
                            className={'inputGeneral'}
                            variant={"standard"} 
                            inputProps={{autoComplete: 'off'}}
                            validators={["required"]}
                            errorMessages={["Debe hacer una selección"]}
                            onChange={handleChange} 
                        >
                            <MenuItem value={""}>Seleccione</MenuItem>
                            {municipios.map(res=>{
                                return <MenuItem value={res.muniid} key={res.muniid}> {res.muninombre}</MenuItem>
                            })}
                        </SelectValidator>
                    </Grid>

                    <Grid item xl={4} md={4} sm={6} xs={12}>
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

                    <Grid item xl={4} md={4} sm={6} xs={12}>
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

                    <Grid item xl={4} md={4} sm={6} xs={12}>
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

                    <Grid item xl={2} md={2} sm={6} xs={12}>

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
                                            <TableCell>Municipio destino</TableCell>
                                            <TableCell>Valor tiquete</TableCell>
                                            <TableCell>Valor seguro</TableCell>
                                            <TableCell>Valor estampilla</TableCell>
                                            <TableCell>Fondo de reposición</TableCell>
                                            <TableCell style={{width: '10%'}} className='cellCenter'>Editar </TableCell>
                                            <TableCell style={{width: '10%'}} className='cellCenter'>Eliminar </TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>

                                    { tarifaTiquetes.map((tari, a) => {
                                        return(
                                            <TableRow key={'rowA-' +a} className={(tari['estado'] == 'D')? 'tachado': null}>

                                                <TableCell>
                                                    {tari['nombreMunicipio']}
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
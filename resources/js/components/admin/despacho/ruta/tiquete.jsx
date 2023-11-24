import React, {useState, useEffect, Fragment} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, Box, Icon, Table, TableHead, TableBody, TableRow, TableCell, Autocomplete, createFilterOptions} from '@mui/material';
import NumberValidator from '../../../layout/numberValidator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
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

    const [formData, setFormData]               = useState({codigo:data.rutaid, departamento: data.depaiddestino});
    const [formDataTiquete, setFormDataTiquete] = useState({municipioId:'', nombreMunicipio:'', valorTiquete: '', valorTiqueteMostrar: '', fondoReposicion:'1.00'});
    const [valorTiquetes, setValorTiquetes] = useState([]);
    const [habilitado, setHabilitado] = useState(true);
    const [municipios, setMunicipios] = useState([]);   
    const [loader, setLoader] = useState(false);

    const handleChange = (e) =>{
        setFormDataTiquete(prev => ({...prev, [e.target.name]: e.target.value}))
    }
 
    const handleSubmit = () =>{
        if(valorTiquetes.length === 0){
            showSimpleSnackbar('Debe adicionar como mínimo una tarifa de tiquete para la ruta', 'error');
            return
        }
 
        let newFormData            = {...formData}
        newFormData.tarifaTiquetes = valorTiquetes;
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
        if(formDataTiquete.municipioId === ''){
            showSimpleSnackbar('Debe seleccionar un municipio', 'error');
            return
        }
        if(formDataTiquete.valorTiquete === ''){
            showSimpleSnackbar('Debe ingresar un valor del tiquete', 'error');
            return
        }
        if(formDataTiquete.fondoReposicion === ''){
            showSimpleSnackbar('Debe ingresar un valor del fondo de reposición', 'error');
            return
        }
        if(valorTiquetes.some(nod => nod.municipioId == formDataTiquete.municipioId)){
            showSimpleSnackbar('Este registro ya fue adicionado', 'error');
            return
        }

        let newValorTiquetes           = [...valorTiquetes];
        const resultadoNombreMunicipio = municipios.filter((mun) => mun.muniid == formDataTiquete.municipioId);
        newValorTiquetes.push({identificador:'', municipioId:formDataTiquete.municipioId, nombreMunicipio: resultadoNombreMunicipio[0].muninombre, valorTiquete: formDataTiquete.valorTiquete, valorTiqueteMostrar: formatearNumero(formDataTiquete.valorTiquete), fondoReposicion: formDataTiquete.fondoReposicion, estado: 'I'});
        setFormDataTiquete({municipioId:'', nombreMunicipio:'', valorTiquete: '', fondoReposicion:'1.00' });
        setValorTiquetes(newValorTiquetes);
    }

    const eliminarFilaTarifa = (id) =>{
        let newValorTiquetes = [];
        valorTiquetes.map((res,i) =>{
            if(res.estado === 'U' && i === id){
                newValorTiquetes.push({ identificador:res.identificador, municipioId: res.municipioId,nombreMunicipio:res.nombreMunicipio, valorTiquete: res.valorTiquete, valorTiqueteMostrar: res.valorTiqueteMostrar, fondoReposicion: res.fondoReposicion,estado: 'D' }); 
            }else if(res.estado === 'D' && i === id){
                newValorTiquetes.push({identificador:res.identificador, municipioId: res.municipioId,nombreMunicipio:res.nombreMunicipio, valorTiquete: res.valorTiquete, valorTiqueteMostrar: res.valorTiqueteMostrar, fondoReposicion: res.fondoReposicion,estado: 'U'});
            }else if((res.estado === 'D' || res.estado === 'U') && i !== id){
                newValorTiquetes.push({identificador:res.identificador, municipioId: res.municipioId,nombreMunicipio:res.nombreMunicipio, valorTiquete: res.valorTiquete, valorTiqueteMostrar: res.valorTiqueteMostrar, fondoReposicion: res.fondoReposicion,estado:res.estado});
            }else{
                if(i != id){
                    newValorTiquetes.push({identificador:res.identificador,municipioId: res.municipioId,nombreMunicipio:res.nombreMunicipio, valorTiquete: res.valorTiquete, valorTiqueteMostrar: res.valorTiqueteMostrar, fondoReposicion: res.fondoReposicion,estado: 'I' });
                }
            }
        })
        setValorTiquetes(newValorTiquetes);
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
                        identificador:       tiq.tartiqid,
                        municipioId:         tiq.muniiddestino,
                        nombreMunicipio:     municipioEncontrado.muninombre,
                        valorTiquete:        tiq.tartiqvalor,
                        valorTiqueteMostrar: tiq. valorTiquete,
                        fondoReposicion:     tiq.tartiqfondoreposicion,
                        estado: 'U'
                    });
                }
            });

            setValorTiquetes(newValorTiquetes);
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

                    <Grid item xl={5} md={5} sm={12} xs={12}>
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

                    <Grid item xl={2} md={2} sm={6} xs={12}>
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

                    <Grid item xl={2} md={2} sm={12} xs={12}>
                        <Button type={"submit"} className={'modalBtn'} 
                            startIcon={<AddIcon />}> {"Agregar"}
                        </Button>
                    </Grid>
                </Grid>

            </ValidatorForm>

            {(valorTiquetes.length > 0) ?
                <Fragment>
                        <Grid container spacing={2}>
                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box className='divisionFormulario'>
                                Tarifa de tiquete adicionados a la ruta
                            </Box>
                        </Grid>

                        <Grid item md={12} xl={12} sm={12} xs={12}>
                            <Box sx={{maxHeight: '35em', overflow:'auto'}}>
                                <Table key={'tablePersona'} className={'tableAdicional'} xl={{width: '60%', margin:'auto'}} md={{width: '70%', margin:'auto'}}  sx={{width: '80%', margin:'auto'}} sm={{maxHeight: '90%', margin:'auto'}}>
                                    <TableHead>
                                        <TableRow>
                                            <TableCell>Municipio destino</TableCell>
                                            <TableCell>Valor tiquete</TableCell>
                                            <TableCell>Fondo de reposición</TableCell>
                                            <TableCell style={{width: '10%'}} className='cellCenter'>Acción </TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>

                                    { valorTiquetes.map((tari, a) => {
                                        return(
                                            <TableRow key={'rowA-' +a} className={(tari['estado'] == 'D')? 'tachado': null}>

                                                <TableCell>
                                                    <p> {tari['nombreMunicipio']}</p>
                                                </TableCell>

                                                <TableCell>
                                                    <p> {tari['valorTiqueteMostrar']}</p>
                                                </TableCell>

                                                <TableCell>
                                                    <p> {tari['fondoReposicion']}</p>
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
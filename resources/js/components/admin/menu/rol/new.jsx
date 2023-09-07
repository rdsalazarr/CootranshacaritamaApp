import React, {useState, useEffect} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack, FormGroup, FormLabel, FormControlLabel, Checkbox}  from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo: data.rolid, nombre: data.rolnombre, funcionalidades: '', estado: data.rolactivo, tipo:tipo 
                                    } : {codigo:'000', nombre: '', funcionalidades: '',  estado: '1', tipo:tipo
                                });

    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);
    const [menus, setMenus] = useState([]); 
    const [formDataMenu, setFormDataMenu] = useState([]); 
    const [menuMarcado, setMenuMarcado] = useState([]); 

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleChangeMenu = (e) =>{
        let newFormDataMenu = [...formDataMenu];
        if(e.target.checked){
            newFormDataMenu.push({funcid: parseInt(e.target.value)});
        }else{
            //Elimino la posicion
            newFormDataMenu = formDataMenu.filter((item) => item.funcid !== parseInt(e.target.value));
        }
        setFormDataMenu(newFormDataMenu);
    }

    const handleSubmit = () =>{
        setLoader(true);
        let newFormData = {...formData}
        newFormData.funcionalidades = formDataMenu; 
        instance.post('/admin/rol/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', nombre: '', funcionalidades: '',  estado: '1', tipo:tipo}) : null;

            let newFormDataMenu = [];
            formDataMenu.forEach(function(men){
                newFormDataMenu.push({
                    funcid: men.funcid
                });
            });
            setMenuMarcado(newFormDataMenu);
            setLoader(false);
        })
    }

    const inicio = () =>{
        setLoader(true);
        let newFormDataMenu = [...formDataMenu];
        instance.post('/admin/rol/listar/funcionalidad', {codigo: formData.codigo}).then(res=>{
            setMenus(res.data);
            setMenuMarcado(res.marcados);
            res.marcados.forEach(function(men){
                newFormDataMenu.push({
                    funcid: men.funcid
                });
            });
            setFormDataMenu(newFormDataMenu);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio(); }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            
            <Grid container spacing={2}>
                <Grid item xl={9} md={9} sm={12} xs={12}>
                    <TextValidator 
                        name={'nombre'}
                        value={formData.nombre}
                        label={'Nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 50}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>
                
                <Grid item xl={3} md={3} sm={12} xs={12}>
                    <SelectValidator
                        name={'estado'}
                        value={formData.estado}
                        label={'Activo'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        <MenuItem value={"1"} >Sí</MenuItem>
                        <MenuItem value={"0"}>No</MenuItem>
                    </SelectValidator>
                </Grid>

                <Grid item xl={12} md={12} sm={12} xs={12}>
                    <FormLabel component="legend">Listado de menús para asignar</FormLabel>
                    <FormGroup row name={"menus"} 
                        value={formDataMenu.funcid}
                        onChange={handleChangeMenu}
                        >
                        {menus.map(res=>{
                            const marcado  = menuMarcado.find(resul => resul.funcid === res.funcid);
                            const checkbox = (marcado !== undefined) ? <Checkbox color="secondary" defaultChecked /> : <Checkbox color="secondary"  />;  
                          
                            const frmCheckbox = <Grid item md={4} xl={4} sm={6} key={res.titulo} >
                                                    <FormControlLabel value={res.funcid} label={res.titulo}  control={checkbox} />
                                                </Grid>
                            return frmCheckbox;
                        })}
                    </FormGroup>  
                </Grid>
                
            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}
                        startIcon={<SaveIcon />}> {(tipo=== 'I') ? "Guardar" : "Actualizar"}
                    </Button>
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
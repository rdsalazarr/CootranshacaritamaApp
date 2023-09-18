import React, {useState, useEffect} from 'react';
import { ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import ArrowForwardIosIcon from '@mui/icons-material/ArrowForwardIos';
import { Button, Grid, MenuItem} from '@mui/material';
import ClearIcon from '@mui/icons-material/Clear';
import {LoaderModal} from "../../layout/loader";
import instance from '../../layout/instance';

export default function Verificar({cerrarModal, verificarArea, ruta}){

    const [formData, setFormData] = useState({dependencia: '' });
    const [loader, setLoader] = useState(false); 
    const [areas, setAreas] = useState([]); 

    const seleccionarArea = (e) =>{
        setFormData(prev => ({...prev, [e.target.name]: e.target.value}));
        if(e.target.value !== ''){
            cerrarModal();
            const resultAreasSelecionada = areas.filter((res) => res.depeid == e.target.value);
            verificarArea({depeid:resultAreasSelecionada[0].depeid, depenombre: resultAreasSelecionada[0].depenombre, depesigla: resultAreasSelecionada[0].depesigla });
        } 
    }

    const inicio = () =>{
        setLoader(true);
        instance.get('/admin/producion/documental/'+ruta+'/consultar/area').then(res=>{
            let areas = res.areas;
            if(areas.length === 1){
                setLoader(false);
                cerrarModal();
                verificarArea({depeid:areas[0].depeid, depenombre: areas[0].depenombre, depesigla: areas[0].depesigla });
            }
            setAreas(areas);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);

    if(loader){
        return <LoaderModal />
    }

    return (
       <ValidatorForm onSubmit={seleccionarArea} >
            <Grid container spacing={2} style={{display: 'flex',  justifyContent: 'space-between'}}>
               <Grid item xl={12} md={12} sm={12} xs={12}>
                    <SelectValidator
                        name={'dependencia'}
                        value={formData.dependencia}
                        label={'Área de producción documental'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off'}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={seleccionarArea} 
                    >
                        <MenuItem value={""}>Seleccione</MenuItem>
                        {areas.map(res=>{
                            return <MenuItem value={res.depeid} key={res.depeid} >{res.depesigla} - {res.depenombre}</MenuItem>
                        })}
                    </SelectValidator>
                </Grid>

                <Grid item xl={6} md={6} sm={6} xs={6}>
                    <Button onClick={cerrarModal} className='modalBtnRojo' 
                        startIcon={<ClearIcon />}> Cancelar
                    </Button>
                </Grid> 

                <Grid item xl={6} md={6} sm={6} xs={6} style={{textAlign: 'right'}}>
                    <Button type={"submit"} className='modalBtn' 
                        startIcon={<ArrowForwardIosIcon />}> Continuar
                    </Button>
                </Grid>

            </Grid>
        </ValidatorForm>
    )
}
import React, {useState , useEffect, useRef} from 'react';
import ReactDOM from "react-dom";
import { TextValidator, ValidatorForm, SelectValidator  } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack } from '@mui/material';
import Loader, {LoaderModal} from "../../../layout/loader";
import SimpleSnackbar from '../../../layout/snackBar';
import DeleteIcon from '@mui/icons-material/Delete';
import { Editor } from '@tinymce/tinymce-react';
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';

export default function New({data, tipo}){
    const editorTexto = useRef(null);
    const [formData, setFormData] = useState(
               (tipo !== 'I') ?
                   {
                     codigo: data.inconoid, titulo: data.inconotitulo, contenido: data.inconocontenido,
                     piePagina: data.inconoenviarpiepagina, copia: data.inconoenviarcopia, tipo:tipo
                   } : {
                    codigo:'999', titulo: '', contenido: '', piePagina: '1', copia: '0', tipo:tipo
               });

   const [loader, setLoader] = useState(true); 
   const [habilitado, setHabilitado] = useState(true);
   
   const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
   }

    const handleSubmit = () =>{
        let newFormData = {...formData};
        newFormData.contenido = editorTexto.current.getContent();
        setLoader(true);
        let ruta = (formData.tipo === 'D') ? '/Admin/InformacionCorreo/Destroy' : '/Admin/InformacionCorreo/Salve';
        instance.post(ruta, newFormData).then(res=>{
            ReactDOM.unmountComponentAtNode(document.getElementById("snake"));
            ReactDOM.render(<SimpleSnackbar msg={(res.message === undefined) ? res.data : res.message} 
                icon={(res.success) ? 'success': 'error'} />,
            document.getElementById("snake")); 
            setLoader(false);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null;  
            (formData.tipo === 'I' && res.success) ?
                setFormData({
                        codigo:'999', titulo: '', contenido: '', piePagina: '1', copia: '0', tipo: newFormData.tipo
                }): null;
        })
    }

    useEffect(()=>{
        setLoader(false);
     }, []);

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>
                <Grid item md={8} xl={8} sm={12}>
                    <TextValidator 
                        name={'titulo'}
                        value={formData.titulo}
                        label={'Título'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item md={2} xl={2} sm={12}>
                    <SelectValidator
                        name={'piePagina'}
                        value={formData.piePagina}
                        label={'Pie Página'}
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

                <Grid item md={2} xl={2} sm={12}>
                    <SelectValidator
                        name={'copia'}
                        value={formData.copia}
                        label={'Enviar Copia'}
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

                <Grid item md={12} xl={12} sm={12}>
                    <label className={'labelEditor'}> Contenido </label>
                    <Editor tinymceScriptSrc="https://cdn.tiny.cloud/1/no-origin/tinymce/5.10.4-130/tinymce.min.js"
                        onInit={(evt, editor) => editorTexto.current = editor}
                        initialValue = {formData.contenido}
                        init={{
                            height: 400,
                            menubar: false,
                            object_resizing : true,
                            plugins: [
                                'advlist autolink lists link image charmap print preview anchor',
                                'searchreplace visualblocks code fullscreen',
                                'insertdatetime media table paste wordcount'
                            ],
                            toolbar: 'undo redo | formatselect | ' +
                                'bold italic backcolor | alignleft aligncenter ' +
                                'alignright alignjustify | bullist numlist outdent indent | ' +
                                'removeformat',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
                            }}
                    />
                </Grid>              
                
            </Grid>

            <Grid container direction="row"  justifyContent="right">
                <Stack direction="row" spacing={2}>
                    <Button type={"submit"} className={'modalBtn'} disabled={(habilitado) ? false : true}                     
                     startIcon={(tipo=== 'D') ?  <DeleteIcon /> :  <SaveIcon />}> {(tipo=== 'I') ? "Guardar" : (tipo === 'U' ? "Actualizar" : "Eliminar"  ) }</Button>                        
                </Stack>
            </Grid>
        </ValidatorForm>
    );
}
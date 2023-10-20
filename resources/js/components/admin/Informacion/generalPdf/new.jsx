import React, {useState, useRef} from 'react';
import { TextValidator, ValidatorForm} from 'react-material-ui-form-validator';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import { Button, Grid, Stack } from '@mui/material';
import instance from '../../../layout/instance';
import SaveIcon from '@mui/icons-material/Save';
import { Editor } from '@tinymce/tinymce-react';

export default function New({data, tipo}){
 
    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo: data.ingpdfid, nombre: data.ingpdfnombre, titulo: data.ingpdftitulo, contenido: data.ingpdfcontenido, tipo:tipo
                                    } : {codigo:'000', nombre: '', titulo: '', contenido: '',  tipo:tipo
                                });

    const editorTexto = useRef(null);
    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        if(editorTexto.current.getContent() === ''){
            showSimpleSnackbar("Debe ingresar el contenido del documento", 'error');
            return
        }

        let newFormData = {...formData};
        newFormData.contenido = editorTexto.current.getContent();
        setLoader(true);
        setFormData(newFormData);
        instance.post('/admin/informacionGeneralPdf/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', nombre: '', titulo: '', contenido: '',  tipo:tipo}) : null;
            setLoader(false);
        })
    }

    if(loader){
        return <LoaderModal />
    }

    return (
        <ValidatorForm onSubmit={handleSubmit} >
            <Grid container spacing={2}>

               <Grid item xl={4} md={4} sm={6} xs={12}>
                    <TextValidator 
                        name={'nombre'}
                        value={formData.nombre}
                        label={'Nombre'}
                        className={'inputGeneral'} 
                        variant={"standard"} 
                        inputProps={{autoComplete: 'off', maxLength: 100}}
                        validators={["required"]}
                        errorMessages={["Campo obligatorio"]}
                        onChange={handleChange}
                    />
                </Grid>

                <Grid item xl={8} md={8} sm={6} xs={12}>
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

                <Grid item md={12} xl={12} sm={12}>
                    <label className={'labelEditor'}> Contenido PDF</label>
                    <Editor
                        onInit={(evt, editor) => editorTexto.current = editor}
                        initialValue = {formData.contenido}
                        init={{
                            language: 'es',
                            height: 400,
                            menubar: false,
                            object_resizing : true,
                            browser_spellcheck: true,
                            spellchecker_language: 'es',
                            spellchecker_wordchar_pattern: /[^\s,\.]+/g ,
                            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
                            toolbar: 'undo redo | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat  | link | table',
                            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
                        }}
                    />
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
import React, {useState, useRef} from 'react';
import { TextValidator, ValidatorForm, SelectValidator } from 'react-material-ui-form-validator';
import { Button, Grid, MenuItem, Stack } from '@mui/material';
import showSimpleSnackbar from '../../../layout/snackBar';
import {LoaderModal} from "../../../layout/loader";
import SaveIcon from '@mui/icons-material/Save';
import instance from '../../../layout/instance';
import { Editor } from '@tinymce/tinymce-react';

export default function New({data, tipo}){

    const [formData, setFormData] = useState(
                    (tipo !== 'I') ? {codigo: data.innocoid, nombre: data.innocotitulo, titulo: data.innocotitulo, contenido: data.innococontenido,
                                    piePagina: data.innocoenviarpiepagina, copia: data.innocoenviarcopia, tipo:tipo
                                    } : {codigo:'000', nombre: '', titulo: '', contenido: '',  piePagina: '1', copia: '0', tipo:tipo
                                });

    const editorTexto = useRef(null);
    const [loader, setLoader] = useState(false); 
    const [habilitado, setHabilitado] = useState(true);

    const handleChange = (e) =>{
       setFormData(prev => ({...prev, [e.target.name]: e.target.value}))
    }

    const handleSubmit = () =>{
        let newFormData = {...formData};
        newFormData.contenido = editorTexto.current.getContent();
        setLoader(true);
        instance.post('/admin/informacionCorreo/salve', newFormData).then(res=>{
            let icono = (res.success) ? 'success' : 'error';
            showSimpleSnackbar(res.message, icono);
            (formData.tipo !== 'I' && res.success) ? setHabilitado(false) : null; 
            (formData.tipo === 'I' && res.success) ? setFormData({codigo:'000', nombre: '', titulo: '', contenido: '', piePagina: '1', copia: '0', tipo:tipo}) : null;
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

                <Grid item xl={4} md={4} sm={6} xs={12}>
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

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <SelectValidator
                        name={'piePagina'}
                        value={formData.piePagina}
                        label={'Pie página'}
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

                <Grid item xl={2} md={2} sm={6} xs={12}>
                    <SelectValidator
                        name={'copia'}
                        value={formData.copia}
                        label={'Enviar copia'}
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

                    <Editor apiKey={import.meta.env.VITE_KEY_TINYMCE}
                        onInit={(evt, editor) => editorTexto.current = editor}
                        initialValue = {formData.contenido}
                        init={{
                            height: 500,
                            menubar: false,   
                                                    
                            plugins: 'preview powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed codesample table charmap pagebreak nonbreaking anchor tableofcontents insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker editimage help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons advtable export footnotes mergetags autocorrect typography advtemplate',
                            tinydrive_token_provider: 'URL_TO_YOUR_TOKEN_PROVIDER',
                            tinydrive_dropbox_app_key: 'YOUR_DROPBOX_APP_KEY',
                            tinydrive_google_drive_key: 'YOUR_GOOGLE_DRIVE_KEY',
                            tinydrive_google_drive_client_id: 'YOUR_GOOGLE_DRIVE_CLIENT_ID',
                            mobile: {
                              plugins: 'preview powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed codesample table charmap pagebreak nonbreaking anchor tableofcontents insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker help formatpainter pageembed charmap mentions quickbars linkchecker emoticons advtable footnotes mergetags autocorrect typography advtemplate',
                            },
                            menu: {
                              tc: {
                                title: 'Comments',
                                items: 'addcomment showcomments deleteallconversations'
                              }
                            },
                            menubar: 'file edit view insert format tools table tc help',
                            toolbar: 'undo redo | bold italic underline strikethrough | typography | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | inserttemplate addtemplate | outdent indent | numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed link anchor codesample | a11ycheck ltr rtl | showcomments addcomment | footnotes | mergetags',
                            toolbar_sticky: true,
                            toolbar_sticky_offset:  108, //isSmallScreen ? 102 :
                            autosave_ask_before_unload: true,
                            autosave_interval: '30s',
                            autosave_prefix: '{path}{query}-{id}-',
                            autosave_restore_when_empty: false,
                            autosave_retention: '2m',
                            image_advtab: true,
                              typography_rules: [
                                  'common/punctuation/quote',
                                  'en-US/dash/main',
                                  'common/nbsp/afterParagraphMark',
                                  'common/nbsp/afterSectionMark',
                                  'common/nbsp/afterShortWord',
                                  'common/nbsp/beforeShortLastNumber',
                                  'common/nbsp/beforeShortLastWord',
                                  'common/nbsp/dpi',
                                  'common/punctuation/apostrophe',
                                  'common/space/delBeforePunctuation',
                                  'common/space/afterComma',
                                  'common/space/afterColon',
                                  'common/space/afterExclamationMark',
                                  'common/space/afterQuestionMark',
                                  'common/space/afterSemicolon',
                                  'common/space/beforeBracket',
                                  'common/space/bracket',
                                  'common/space/delBeforeDot',
                                  'common/space/squareBracket',
                                  'common/number/mathSigns',
                                  'common/number/times',
                                  'common/number/fraction',
                                  'common/symbols/arrow',
                                  'common/symbols/cf',
                                  'common/symbols/copy',
                                  'common/punctuation/delDoublePunctuation',
                                  'common/punctuation/hellip'
                              ],
                              typography_ignore: [ 'code' ],
                              advtemplate_list: () => {
                              return Promise.resolve([
                                {
                                  id: '1',
                                  title: 'Resolving tickets',
                                  content: '<p>As we have not heard back from you in over a week, we have gone ahead and resolved your ticket.</p>'
                                },
                                {
                                  id: '2',
                                  title: 'Quick replies',
                                  items: [
                                    {
                                      id: '3',
                                      title: 'Message received',
                                      content: '<p>Just a quick note to say we have received your message, and will get back to you within 48 hours.</p>'
                                    },
                                    {
                                      id: '4',
                                      title: 'Progress update',
                                      content: '</p>Just a quick note to let you know we are still working on your case</p>'
                                    }
                                  ]
                                }
                              ]);
                            },
                            link_list: [
                              { title: 'My page 1', value: 'https://www.tiny.cloud' },
                              { title: 'My page 2', value: 'http://www.moxiecode.com' }
                            ],
                            image_list: [
                              { title: 'My page 1', value: 'https://www.tiny.cloud' },
                              { title: 'My page 2', value: 'http://www.moxiecode.com' }
                            ],
                            image_class_list: [
                              { title: 'None', value: '' },
                              { title: 'Some class', value: 'class-name' }
                            ],
                            importcss_append: true,
                            height: 600,
                            image_caption: true,
                            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
                            noneditable_class: 'mceNonEditable',
                            toolbar_mode: 'sliding',
                            spellchecker_ignore_list: ['Ephox', 'Moxiecode', 'tinymce', 'TinyMCE', 'tm', 'TM'], // ‘tm’ and ‘TM’ are temporary spellchecker bug workarounds
                            tinycomments_mode: 'embedded',
                            content_style: '.mymention{ color: gray; }',
                            contextmenu: 'link image editimage table configurepermanentpen',
                            a11y_advanced_options: true,
                            //skin: useDarkMode ? 'oxide-dark' : 'oxide',
                            //content_css: useDarkMode ? 'dark' : 'default',
                            /*
                            The following settings require more configuration than shown here.
                            For information on configuring the mentions plugin, see:
                            https://www.tiny.cloud/docs/tinymce/6/mentions/.
                            */
                            mentions_selector: '.mymention',
                           // mentions_fetch: mentions_fetch,
                            //mentions_menu_hover: mentions_menu_hover,
                            //mentions_menu_complete: mentions_menu_complete,
                            //mentions_select: mentions_select,
                           // mentions_item_type: 'profile',
                            autocorrect_capitalize: true,
                            mergetags_list: [
                              {
                                title: 'Client',
                                menu: [
                                  {
                                    value: 'Client.LastCallDate',
                                    title: 'Call date'
                                  },
                                  {
                                    value: 'Client.Name',
                                    title: 'Client name'
                                  }
                                ]
                              },
                              {
                                title: 'Proposal',
                                menu: [
                                  {
                                    value: 'Proposal.SubmissionDate',
                                    title: 'Submission date'
                                  }
                                ]
                              },
                              {
                                value: 'Consultant',
                                title: 'Consultant'
                              },
                              {
                                value: 'Salutation',
                                title: 'Salutation'
                              }
                            ]
                           
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
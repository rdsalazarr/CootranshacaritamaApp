import React from 'react';
import "../../../scss/general.scss";
import logoNoVistaPrevia from "../../../images/files/noVistaPrevia.png";
import logoFile from "../../../images/files/file.png";
import logoPdf from "../../../images/files/pdf.png";
import { Box, Typography } from '@mui/material';
import '../../../scss/files.scss';

export function ButtonFile({title, icon = 'backup'}){
    return (
        <Box className={'fileContent'}>
            <Icon>{icon}</Icon>
            <Typography component={"label"}>{title}</Typography>
        </Box>
    )
}

export function ButtonFilePdf({title}){
    return (
        <Box>
            <img className={'iconoSubirPdf'} src={logoPdf}/>
            <Typography className={'descripcionSubida'} component={"label"}>{title}</Typography>
        </Box>
    )
}

export function ButtonFileImg({title}){
    return (
        <Box>
            <img className={'iconoSubirAdjunto'} src={logoFile}/>
            <Typography className={'descripcionSubida'} component={"label"}>{title}</Typography>
        </Box>
    )
}

export  function ContentFile({file, name, remove, mostrarEnlace = true}){
    return (
        <Box className={"fileInd"}>
            <embed className={"fileImg"} src={canPreviewFile(file, file.extension )} key={file.id} />
            {(mostrarEnlace) ? <Box onClick={() => {remove(name)}} className={'eliminar'}>Eliminar </Box> : null}
        </Box>
    )
}

export function canPreviewFile(file, extension) {
    const previewableExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
    return (previewableExtensions.includes(extension.toLowerCase())) ? URL.createObjectURL(file) : logoNoVistaPrevia ;
}
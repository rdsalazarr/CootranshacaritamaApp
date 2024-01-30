
import React, {useState , useEffect} from 'react';
import { Box, Modal, Paper, Typography } from '@mui/material';
import '../../../scss/modal.scss';

export function ModalDefault({title, content, close, tam = 'bigFlot'}){
    const [open , setOpen] = useState(true);
    return (
       
        <Modal open={open} onClose={() => { setOpen(false); close()}} className={"modalCenter"} >           
            <Paper className= {tam} >
                <Box className={"modalHeader"} >
                    <Box className={"iconLateral"}><Box /></Box>
                    <Typography component={'h3'} >{title}</Typography>
                </Box>
                <Box className={"modalContent"}>
                    {content}
                </Box>
            </Paper>         
        </Modal>
    );
}

export function ModalDefaultAuto({title, content, close, tam = 'bigFlot', abrir= false}){
    const [open , setOpen] = useState(abrir);
    useEffect(()=>{
        setOpen(abrir);
    }, [abrir]);
    return (
        <Modal open={open} onClose={() => {close()}} className={"modalCenter"} >
            <Paper className= {tam + " animate__animated animate__zoomIn" }>
                <Box className={"modalHeader"} >
                    <Box className={"iconLateral"}><Box />
                </Box>

                <Typography component={'h3'} >{title}</Typography>
                </Box>
                
                <Box className={"modalContent"} >
                    {content}
                </Box>
            </Paper>
        </Modal>
    );
}
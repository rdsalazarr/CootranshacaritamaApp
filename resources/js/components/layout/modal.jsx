
import React, {useState , useEffect} from 'react';
import Card from '@mui/material/Card';
import { Box, CardContent, Icon, Modal, Paper, Typography } from '@mui/material';
import '../../../scss/modal.scss';

export function TabPanel(props) {
    const {children, value, index, ...other} = props;

    return (
        <Box
            role="tabpanel"
            hidden={value !== index}
            id={`scrollable-prevent-tabpanel-${index}`}
            aria-labelledby={`scrollable-prevent-tab-${index}`}
            {...other}
        >
            <Card> 
                <CardContent>
                    {value === index && ( children)}     
                </CardContent>
            </Card>
        </Box>
    );
}

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
            <Paper className= {tam} >
                <Box className={"modalHeader"} >
                <Box className={"iconLateral"}><Box /></Box>
                    <Typography component={'h3'} >{title}</Typography>
                </Box>
                <Box className={"modalContent"} >
                    {content}
                </Box>
            </Paper>
        </Modal>
    );
}
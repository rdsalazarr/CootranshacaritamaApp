import React, {useState, useEffect } from 'react';
import {LoaderModal} from "../../layout/loader";
import instance from '../../layout/instance';
import { Box} from '@mui/material';

export default function List(){

    const [loader, setLoader] = useState(false);
      
 
    const inicio = () =>{
        /*setLoader(true);
        instance.get('/admin/festivos/list').then(res=>{
           // setData(res.data);
            setLoader(false);
        }) */
    }

    useEffect(()=>{inicio();}, []); 
    
    if(loader){
        return <LoaderModal />
    }

    return (
        <Box className={'containerSmall'} >
            <Box className={'cardContainer'} >
                <Box sx={{maxHeight: '35em', overflow:'auto'}} sm={{maxHeight: '35em', overflow:'auto'}}>
                 hola festivos
                </Box>
            </Box>
        </Box>
    )
}
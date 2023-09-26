import React, {useState, useEffect} from 'react';
import {LoaderModal} from "../../../layout/loader";
import instance from '../../../layout/instance';
import Oficio from '../oficio/new';

export default function Verificar({data}){

    console.log(data);

    <Oficio tipo={'U'} id={data.oficioId } />


/*
    const [loader, setLoader] = useState(true);
    const [data, setData] = useState([]);


    const inicio = () =>{
        setLoader(true);
        instance.post('/admin/firmar/documento/verificar', {id:id, tipo:tipo}).then(res=>{
            setData(res.data);
            setLoader(false);
        }) 
    }

    useEffect(()=>{inicio();}, []);
    
    if(loader){
        return <LoaderModal />
    }
*/

    return (
        <div>hola verificar</div>
    )

}
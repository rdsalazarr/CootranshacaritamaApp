import React from "react";
import ReactDOM from "react-dom";
import { Box } from "@mui/material";
import "../../../scss/loader.scss";
import logo from "../../../images/logoCootranshacaritama.png";

export default function Loader(){
    return (<Box className={'loader'}>
                <Box className={'img'}><img src={logo} /></Box>
                <Box className={'lds-ellipsis'}>
                   <div></div><div></div><div></div><div></div>
                </Box>
            </Box>)
}

export function LoaderModal(){
    return (<Box className={'loaderModal'}>
            <Box className={'img'}><img src={logo} /></Box>
                <Box className={'lds-ellipsis'}>
                <div></div><div></div><div></div><div></div>
                </Box>
            </Box>)
}

export function LoaderTransparent(){
    return (<Box className={'loader Transparent'}>
                <Box className={'img'}><img src={logo} /></Box>
                <Box className={'lds-ellipsis'}>
                    <div></div><div></div><div></div><div></div>
                </Box>
            </Box>)
}

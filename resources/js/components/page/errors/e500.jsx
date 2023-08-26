import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";

export default function E500(){
    return(
        <div>hola mundo E500</div>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<E500 />);

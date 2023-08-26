import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";

export default function E405(){
    return(
        <div>hola mundo E405</div>
    )
}


const root = createRoot(document.getElementById('app'));
root.render(<E405 />);

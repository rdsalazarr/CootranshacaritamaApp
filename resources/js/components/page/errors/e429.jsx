import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";

export default function E429(){
    return(
        <div>hola mundo E429</div>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<E429 />);

import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";

export default function E503(){
    return(
        <div>hola mundo E503</div>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<E503 />);

import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";

export default function E419(){
    return(
        <div>hola mundo E419</div>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<E419 />);

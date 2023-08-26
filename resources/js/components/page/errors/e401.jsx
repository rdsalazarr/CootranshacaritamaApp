import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";

export default function E401(){

    return(
        <div>hola mundo E401</div>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<E401 />);

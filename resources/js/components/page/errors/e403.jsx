import '../../../bootstrap';
import React from 'react';
import {createRoot} from "react-dom/client";

export default function E403(){

    return(
        <div>hola mundo E403</div>
    )
}

const root = createRoot(document.getElementById('app'));
root.render(<E403 />);

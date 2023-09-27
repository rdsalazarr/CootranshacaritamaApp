import React, { useState, useEffect } from 'react';

export default function RelojDigital({ tiempoInicial, onTiempoFinalizado }) { 
    const [tiempoRestante, setTiempoRestante] = useState(tiempoInicial);
    const [tiempoCumplido, setTiempoCumplido] = useState(false);

    useEffect(() => {
    if (tiempoRestante <= 0 && !tiempoCumplido) {
        onTiempoFinalizado();
        setTiempoCumplido(true);
    }

    const intervalo = setInterval(() => {
        setTiempoRestante((prevTiempo) => prevTiempo - 1);
    }, 900);

    return () => clearInterval(intervalo);
    }, [tiempoRestante, tiempoCumplido, onTiempoFinalizado]);

    const minutos = Math.floor(tiempoRestante / 60);
    const segundos = tiempoRestante % 60;
 
    return  (tiempoCumplido) ? ( <div className="tiempoevaluacion">{'00:00'}</div> ) : 
                              (<div className="tiempoevaluacion">{`${minutos < 10 ? '0' + minutos : minutos}:${
                                            segundos < 10 ? '0' + segundos : segundos
                                        }`}</div>);
}
import React, {useState, useEffect, Fragment, useRef} from 'react';
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator';

import { isPercentage, isCorreoValido } from './customValidations';


ValidatorForm.addValidationRule('isPercentage', (value) => {
    // Verificar si el valor es un número válido en formato "10.50"
    const regex = /^\d+(\.\d{1,2})?$/;
    if (!regex.test(value)) {
      return false;
    }
  
    // Verificar si el número está en el rango de 0 a 100 (porcentaje válido)
    const numValue = parseFloat(value);
    return numValue >= 0 && numValue <= 100;
  });



  ValidatorForm.addValidationRule('isCorreoValido', (cadena) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+/;
    const correos = cadena.split(',').map(correo => correo.trim());
    for (const correo of correos) {
      if (!emailRegex.test(correo)) {
        return false;
      }
    }
    return true;
  });

class MyForm extends React.Component {

  


    state = {
      creditPercentage: '', correo: '',
    };
  
    handleInputChange = (e) => {
      this.setState({ creditPercentage: e.target.value });
    };

    handleInputChange1 = (e) => {
        this.setState({ correo: e.target.value });
      };
  
    
    render() {
      return (
        <ValidatorForm>
          <TextValidator
            label="Porcentaje de Crédito"
            onChange={this.handleInputChange}
            name="creditPercentage"
            value={this.state.creditPercentage}
            validators={['isPercentage']}
            errorMessages={['Ingrese un porcentaje válido']}
          />

            <TextValidator
            label="Correo(s)"
            onChange={this.handleInputChange1}
            name="correo"
            value={this.state.correo}
            validators={['isCorreoValido']}
            errorMessages={['Ingrese un correo válido']}
            />
                


          <button type="submit">Enviar</button>
        </ValidatorForm>
      );
    }
  }
  
  export default MyForm;
  
/*import React from 'react';
import { ValidatorForm, TextValidator } from 'react-material-ui-form-validator';

class MyForm extends React.Component {
  state = {
    creditPercentage: '',
    correo: '',
  };

  handleCreditInputChange = (e) => {
    this.setState({ creditPercentage: e.target.value });
  };

  handleCorreoInputChange = (e) => {
    this.setState({ correo: e.target.value });
  };

  render() {
    return (
      <ValidatorForm>
        <TextValidator
          label="Porcentaje de Crédito"
          onChange={this.handleCreditInputChange}
          name="creditPercentage"
          value={this.state.creditPercentage}
          validators={["required", 'isPercentage']}
          errorMessages={["Campo obligatorio", 'Ingrese un porcentaje válido']}
        />

        <TextValidator
          label="Correo (s)"
          onChange={this.handleCorreoInputChange}
          name="correo"
          value={this.state.correo}
          validators={["required",'isCorreoValido']}
          errorMessages={["Campo obligatorio",'Ingrese un correo válido']}
        />

        <button type="submit">Enviar</button>
      </ValidatorForm>
    );
  }
}

export default MyForm;
*/
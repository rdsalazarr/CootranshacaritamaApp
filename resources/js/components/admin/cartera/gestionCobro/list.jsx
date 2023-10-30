import React from 'react';
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

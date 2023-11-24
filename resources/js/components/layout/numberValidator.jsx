import * as React from 'react';
import PropTypes from 'prop-types';
import {NumericFormat} from 'react-number-format';
import {TextValidator} from "react-material-ui-form-validator";

const NumberFormatCustom = React.forwardRef(function NumberFormatCustom(props, ref) {
    const {onChange, ...other} = props;
    return (
        <NumericFormat
            {...other}
            getInputRef={ref}
            onValueChange={(values) => {
                onChange({
                    target: {
                        name: props.name,
                        value: values.value,
                    },
                });
            }}
            thousandSeparator            
            allowNegative={false}
            prefix="$"
        />
    );
});

NumberFormatCustom.propTypes = {
    name: PropTypes.string.isRequired,
    onChange: PropTypes.func.isRequired,
};

export default function NumberValidator(props) {

    const {
        id,
        name,
        label,
        value,
        onChange,
        onKeyPress,
        full,
        disabled,
        tipo,
        require,
        error,
        multiple,
        colorText,
        colorValor,
        colorBorder,
        variante,
        negrilla
    } = props;

    return (
        <TextValidator
            id={id}
            name={name}
            label={label}
            validators={require === undefined ? [] : require}
            errorMessages={error === undefined ? [] : error}
            value={value}
            type={tipo === undefined ? 'text' : tipo}
            disabled={disabled}
            onChange={onChange}
            onKeyPress={onKeyPress ? onKeyPress : undefined}
            variant={variante === undefined ? "standard" : variante}
            fullWidth={!full}
            multiline={multiple !== undefined}
            rows={multiple ? 2 : 1}
            InputProps={{
                inputComponent: NumberFormatCustom,
            }}
            sx={{
                "& .MuiInputBase-input.Mui-disabled": {WebkitTextFillColor: colorValor == undefined ? null : colorValor},
                "& .MuiInputLabel-root.Mui-disabled": {
                    color: colorText == undefined ? null : colorText,
                    fontWeight: negrilla == undefined ? null : negrilla,
                },
                "& .MuiOutlinedInput-notchedOutline ": {
                    borderColor: colorBorder == undefined ? null : colorBorder,
                    borderStyle: colorBorder == undefined ? null : 'dashed',
                }
            }}
        />
    );
}
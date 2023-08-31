import React, {useState } from "react";
import PropTypes from 'prop-types';
import { Table, TableBody, TableContainer, TableHead, TableRow, Icon,Box,TableCell,TablePagination,IconButton, TextField, TableSortLabel, Grid } from "@mui/material";
import FirstPageIcon from '@mui/icons-material/FirstPage';
import KeyboardArrowLeft from '@mui/icons-material/KeyboardArrowLeft';
import KeyboardArrowRight from '@mui/icons-material/KeyboardArrowRight';
import LastPageIcon from '@mui/icons-material/LastPage';
import { visuallyHidden } from '@mui/utils';
import '../../../scss/tabla.scss';

function TablePaginationActions(props) {
    const {count, page, rowsPerPage, onPageChange} = props;

    const handleFirstPageButtonClick = (event) => {
        onPageChange(event, 0);
    };

    const handleBackButtonClick = (event) => {
        onPageChange(event, page - 1);
    };

    const handleNextButtonClick = (event) => {
        onPageChange(event, page + 1);
    };

    const handleLastPageButtonClick = (event) => {
        onPageChange(event, Math.max(0, Math.ceil(count / rowsPerPage) - 1));
    };

    return (
        <div style={{display: "flex", flexDirection: 'row'}}>
            <IconButton
                onClick={handleFirstPageButtonClick}
                disabled={page === 0}
                aria-label="first page"
            >
                <FirstPageIcon/>
            </IconButton>
            <IconButton onClick={handleBackButtonClick} disabled={page === 0} aria-label="previous page">
                <KeyboardArrowLeft/>
            </IconButton>
            <IconButton
                onClick={handleNextButtonClick}
                disabled={page >= Math.ceil(count / rowsPerPage) - 1}
                aria-label="next page"
            >
                <KeyboardArrowRight/>
            </IconButton>
            <IconButton
                onClick={handleLastPageButtonClick}
                disabled={page >= Math.ceil(count / rowsPerPage) - 1}
                aria-label="last page"
            >
                <LastPageIcon/>
            </IconButton>
        </div>
    );
}

TablePaginationActions.propTypes = {
    count: PropTypes.number.isRequired,
    onPageChange: PropTypes.func.isRequired,
    page: PropTypes.number.isRequired,
    rowsPerPage: PropTypes.number.isRequired,
  };
  
const mergeJSON  =  (uno, dos)  =>{
    Object.keys(dos).map(res=>{
        uno[res] = dos[res];
    });
    return uno;
}

export default function TablaGeneral({datos, titulo, ver, accion= [],
    style={
        width :'100%'
    }, 
    funciones={
        orderBy: false,
        search: false,
        pagination: false
    }
    }){        

    const [page, setPage] =  useState(0);
    const [rowsPerPage , setRowsPerPage] =  useState((funciones.pagination) ? 5 : 10);
    const [row, setRow] = useState(datos);
    const [search , setSearch] = useState();
    const [ordenar , setOrdenar] = useState({valor : true, tipo :''});
    const handleChangePage = (event, newPage) => {
        setPage(newPage);
    };

    const handleChangeRowsPerPage = (event) => {
        setRowsPerPage(parseInt(event.target.value, 10));
        setPage(0);
      };

    const orderData = (item) => {
        ordenarAsc(row, item, ordenar.valor);
        setOrdenar({valor: !ordenar.valor , tipo :item});
    };
    const ordenarAsc = (p_array_json, prop, asc) => {
        return p_array_json.sort(function (a, b) {
            if (asc) {
                return (a[prop] > b[prop]) ? 1 : ((a[prop] < b[prop]) ? -1 : 0);
            } else {
                return (b[prop] > a[prop]) ? 1 : ((b[prop] < a[prop]) ? -1 : 0);
            }
        });
    };
    const searchFunction = (valor) => {
        let arrBusqueda = valor.split(",");
        let ndata = datos.filter(item => {
            let a = arrBusqueda.map(value => {
                let c = [] ;
                Object.keys(item).map(res=>{
                   c.push(item[res]);
                });
                return JSON.stringify(c)
                    .toUpperCase()
                    .indexOf(value.toUpperCase()) > -1 ? 1 : 0;

            }).reduce((x, y) => x + y);
            return a > 0
        });
        return ndata;        
    };

    return (
            <TableContainer  className={'tableGeneral'}   style={mergeJSON({margin: '0 auto'}, style)}>
                <Grid container spacing={0}>
                    <Grid item className={'tablaIcono'} xl={8} md={8} sm={2} xs={2} style={{textAlign:'left', padding: 0}}>
                        {accion.map(ev=>{
                            if(ev.tipo === 'T'){
                                return (<Icon key={'icon'+ ev.icono} className={'icon top ' + ev.color }
                                            onClick={() => {const a = ev.funcion;
                                                a();}}>{ev.icono}</Icon>);
                            }
                        })}
                    </Grid>
                    <Grid item xl={4} md={4} sm={10} xs={10}  style={{ padding: 0}}>
                        {(funciones.search) ?  
                            <TextField 
                                className={'inputGeneral searchTable'}
                                label={"Búsqueda"}
                                name={"search"}
                                variant={"standard"}
                                autoComplete={'off'}
                                value={search}
                                onChange={(e)=>{setSearch(e.target.value); setRow((e.target.value === '') ? datos: searchFunction(e.target.value))}}
                            />
                        : null}
                    </Grid>
                </Grid>
                <Box>
                    <Table>
                        <TableHead>
                            <TableRow>
                                {titulo.map((res , i )=>{
                                    if(funciones.orderBy){
                                    return (
                                    <TableCell key={res} onClick={()=>{orderData(ver[i])}}>
                                        <TableSortLabel
                                            active={ordenar.tipo === ver[i]}
                                            direction={ordenar.valor ? 'desc' : 'asc'}
                                        >
                                                {res}
                                                {ordenar.tipo === ver[i] ? (<Box  component="span" sx={visuallyHidden}>
                                                {ordenar.valor ? 'sorted descending' : 'sorted ascending'}
                                                </Box>): null}
                                        </TableSortLabel>
                                        </TableCell>)
                                    }
                                    return <TableCell key={res}>{res}</TableCell>
                                })}
                            </TableRow>
                        </TableHead>
                        <TableBody>
                            {(datos.length === 0) ?
                                <TableRow>
                                    <TableCell 
                                        component={"th"} 
                                        colSpan={ver.length + accion.length} 
                                        style={{textAlign: 'center', fontSize: '1.5em', color: '#8b8b8b', fontWeight: '600'}}>
                                            <b>No existen registros para mostrar</b>                                            
                                    </TableCell>
                                </TableRow> : null}
                                {(rowsPerPage > 0
                                        ? row.slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
                                        : row
                                ).map((res, i) => {
                                    return (
                                        <TableRow key={'row' + i}>
                                            {ver.map(see => {
                                                return (
                                                    <TableCell className={see} key={see + i}>{res[see]}</TableCell>
                                                )
                                            })}
                                            {accion.map(ev => {   
                                                if(ev.tipo === 'B'){
                                                    return (<TableCell className={'tablaIcono'} style={{textAlign: "center"}} component={"td"} key={'evento' + i +ev.icono}>
                                                        <Icon className={'icon ' + ev.color }
                                                        onClick={() => {
                                                            const a = ev.funcion;
                                                            a(res);
                                                        }}>{ev.icono}</Icon>
                                                    </TableCell>)
                                                }
                                            })}

                                        </TableRow>
                                    )
                                })}
                        </TableBody>
                    </Table>

                    {(funciones.pagination) ?                 
                        <TablePagination
                            style={{border:'1px solid #e0e0e0' , borderTop: '0px'}}
                            component="div"
                            rowsPerPageOptions={[5, 10, 25, {label: 'Todos', value: -1}] }
                          /* colSpan={header.length}*/
                            count={row.length}
                            labelRowsPerPage="Filas por pág."
                            labelDisplayedRows={({from, to, count}) => to + " de " + count}
                            rowsPerPage={rowsPerPage}
                            page={page}
                            onPageChange={handleChangePage}
                            onRowsPerPageChange={handleChangeRowsPerPage}
                            ActionsComponent={TablePaginationActions}
                        />
                    : null}
                </Box>
            </TableContainer>
    );
}
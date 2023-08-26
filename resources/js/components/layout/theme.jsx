import { createTheme, ThemeProvider } from '@mui/material/styles';
const rojo       = '#ec1c21';
const rojoClaro  = '#c53f45';
const verde      = '#44ac34';
const verdeClaro = '#80C900';
export const generalTema = createTheme({  
    palette: {
        primary: {
            main: '#6D6D6D',
            contrastText: "#ffffff",
        },
        secondary: {
            main: verde,
            contrastText: "#ffffff",
        },
        neutral:{
            main: '#64748B',
            contrastText: '#fff',
        }
    },
    typography: {
        // Use the system font instead of the default Roboto font.
        fontFamily: [
            '"Myriad Pro"',
            '"Myriad Pro Bold"',
            '"Myriad Pro Cond"',
            '"Myriad Pro Bold Cond"',
        ].join(',')
    },
    components:{    
        MuiButton: {
            styleOverrides:{
                root: {
                    marginTop: '1em',
                    background: verde,
                    textAlign: 'center',
                    transition: 'all .5s ease-in-out',
                    color: 'white',
                    '&:hover': {
                        backgroundColor: verdeClaro,
                        color: '#fdfdfd',
                        transition: 'all .5s ease-in-out',
                        boxShadow: '0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12)'
                    }
                },
            }
        },
        MuiTabs: {
            root: {
                letterSpacing :'1.5px',
                background: '#e6e6e6',
            },
            indicator: {
                transition: 'all .5s ease-in-out',
                backgroundColor: verdeClaro,
                top: 0
            }
        },
        MuiTab:{
            border : '1px solid #282c2a1a',
            root: {
                letterSpacing :'1.5px',
                borderBottom :'1px solid #d2d1d1',
                fontFamily: [
                    '"Myriad Pro"',
                    '"Myriad Pro Bold"',
                    '"Myriad Pro Cond"',
                    '"Myriad Pro Bold Cond"',
                ].join(','),
                '&$selected': {
                    backgroundColor: '#f5f5f5',
                    color: verdeClaro,
                    fontWeight: 'bold',
                },

            }

        }
    }
});
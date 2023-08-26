import React from "react";
import {createRoot} from "react-dom/client";
import Snackbar from "@mui/material/Snackbar";
import SnackbarContent from "@mui/material/SnackbarContent";
import IconButton from "@mui/material/IconButton";
import clsx from "clsx";
import CheckCircleIcon from "@mui/icons-material/CheckCircleOutline";
import ErrorIcon from "@mui/icons-material/ErrorOutline";
import InfoIcon from "@mui/icons-material/Info";
import CloseIcon from "@mui/icons-material/Close";
import WarningIcon from "@mui/icons-material/ReportProblem";
import {makeStyles} from "@mui/styles";

const variantIcon = {
    success: CheckCircleIcon,
    warning: WarningIcon,
    error: ErrorIcon,
    info: InfoIcon
};

const useStyles1 = makeStyles(theme => ({
    success: {
        backgroundColor: "rgb(31, 203, 74)"
    },
    error: {
        backgroundColor: "rgb(245, 61, 61)"
    },
    info: {
        backgroundColor: "#6969d6"
    },
    warning: {
        backgroundColor: "rgb(255, 235, 63)"
    },
    icon: {
        fontSize: 22,
        color: "rgb(78, 78, 78)"
    },
    iconClose: {
        fontSize: 18
    },
    iconVariant: {
        opacity: 0.9,
        marginRight: "0.8em"
    },
    message: {
        display: "flex",
        alignItems: "center",
        marginLeft: "3em",
        color: 'white'
    },
    contentIcon: {
        position: "absolute",
        top: 0,
        bottom: 0,
        left: 0,
        display: "flex",
        alignItems: "center",
        paddingLeft: "1em"
    },
    contentMsg: {position: "relative", background: "rgba(45, 45, 45, 0.88) !important", color: "white"}
}));

export function SimpleSnackbar({icon, msg}) {
    const classes = useStyles1();
    const [openSnack, setOpen] = React.useState(true);
    const Icon = variantIcon[icon];

    function handleCloseSnack(event, reason) {
        if (reason === "clickaway") {
            return;
        }

        setOpen(false);
    }

    return (
        <Snackbar
            style={{zIndex: 99999999999999}}
            anchorOrigin={{
                vertical: "top",
                horizontal: "right"
            }}
            open={openSnack}
            autoHideDuration={4000}
            onClose={handleCloseSnack}
        >
            <SnackbarContent
                className={clsx(classes.contentMsg)}
                aria-describedby="client-snackbar"
                message={
                    <div>
                        <div className={clsx(classes.contentIcon, classes[icon])}>
                            <Icon className={clsx(classes.icon, classes.iconVariant)}/>
                        </div>
                        <span id="client-snackbar" className={classes.message}>
              {msg}
            </span>
                    </div>
                }
                action={[
                    <IconButton
                        key="close"
                        aria-label="Close"
                        color="inherit"
                        className={classes.iconClose}
                        onClick={handleCloseSnack}
                    >
                        <CloseIcon/>
                    </IconButton>
                ]}
            />
        </Snackbar>
    );
}

export default function showSimpleSnackbar(msj, icono){
    const notificacion = createRoot(document.getElementById("snake"));
    notificacion.render( <SimpleSnackbar msg={msj} icon={icono} />);  
}
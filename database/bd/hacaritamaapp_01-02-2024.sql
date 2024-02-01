-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-02-2024 a las 22:27:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hacaritamaapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agencia`
--

CREATE TABLE `agencia` (
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla agencia',
  `persidresponsable` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `agendepaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento',
  `agenmuniid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio',
  `agennombre` varchar(100) NOT NULL COMMENT 'Nombre del la agencia',
  `agendireccion` varchar(100) NOT NULL COMMENT 'Dirección de la agencia',
  `agencorreo` varchar(80) DEFAULT NULL COMMENT 'Correo de la agencia',
  `agentelefonocelular` varchar(20) DEFAULT NULL COMMENT 'Teléfono celular de la agencia',
  `agentelefonofijo` varchar(20) DEFAULT NULL COMMENT 'Teléfono fijo de la agencia',
  `agenactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la agencia se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `agencia`
--

INSERT INTO `agencia` (`agenid`, `persidresponsable`, `agendepaid`, `agenmuniid`, `agennombre`, `agendireccion`, `agencorreo`, `agentelefonocelular`, `agentelefonofijo`, `agenactiva`, `created_at`, `updated_at`) VALUES
(101, 2, 18, 804, 'PRINCIPAL', 'Calle 7 a 56 211 la ondina vía a rio de oro', 'cootranshacaritama@hotmail.com', '3146034311', '5611012', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivohistorico`
--

CREATE TABLE `archivohistorico` (
  `archisid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla archivo histórico',
  `tipdocid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tipo documento',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el registro del documento',
  `tiesarid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo estante archivador',
  `ticaubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de caja ubicacion',
  `ticrubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de carpeta ubicacion',
  `archisfechahora` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se crea el registro del documento',
  `archisfechadocumento` date DEFAULT NULL COMMENT 'Fecha que contiene el documento',
  `archisnumerofolio` varchar(2) NOT NULL COMMENT 'Número de folio que posee el documento del archivo histórico',
  `archisasuntodocumento` varchar(200) NOT NULL COMMENT 'Asunto que posee el documento del archivo histórico',
  `archistomodocumento` varchar(2) DEFAULT NULL COMMENT 'Tomo que posee el documento del archivo histórico',
  `archiscodigodocumental` varchar(20) DEFAULT NULL COMMENT 'Código que posee el documento del archivo histórico',
  `archisentidadremitente` varchar(200) DEFAULT NULL COMMENT 'Entidad remitente que posee el documento del archivo histórico',
  `archisentidadproductora` varchar(200) DEFAULT NULL COMMENT 'Entidad productora que posee el documento del archivo histórico',
  `archisresumendocumento` varchar(500) DEFAULT NULL COMMENT 'REsumen que posee el documento del archivo histórico',
  `archisobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación general del registro del archivo histórico',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `archivohistoricodigitalizado`
--

CREATE TABLE `archivohistoricodigitalizado` (
  `arhidiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante dependencia',
  `archisid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador del archivo histórico',
  `arhidinombrearchivooriginal` varchar(200) NOT NULL COMMENT 'Nombre con el cual se ha subido el archivo digitalizado',
  `arhidinombrearchivoeditado` varchar(200) NOT NULL COMMENT 'Nombre con el cual se ha subido el archivo digitalizado pero editado',
  `arhidirutaarchivo` varchar(500) NOT NULL COMMENT 'Ruta enfuscada del archivo digitalizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asociado`
--

CREATE TABLE `asociado` (
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla asociado',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `tiesasid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de estado del asociado',
  `asocfechaingreso` date NOT NULL COMMENT 'Fecha de ingreso del asocado a la cooperativa',
  `asocfecharetiro` date DEFAULT NULL COMMENT 'Fecha de retiro del asocado a la cooperativa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asociadocambioestado`
--

CREATE TABLE `asociadocambioestado` (
  `ascaesid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla asociado cambio estado',
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del asociado',
  `tiesasid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de estado asociado',
  `ascaesusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del asociado',
  `ascaesfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del asociado',
  `ascaesobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación del cambio estado del asociado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asociadosancion`
--

CREATE TABLE `asociadosancion` (
  `asosanid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla asociado sanción',
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del asociado',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que sanciona el asociado',
  `tipsanid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de sanción',
  `asosanfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el registro de la sanción del asociado',
  `asosanfechamaximapago` date NOT NULL COMMENT 'Fecha máxima de pago de la sanción del asociado',
  `asosanmotivo` varchar(500) NOT NULL COMMENT 'Motivo de la sanción del asociado',
  `asosanvalorsancion` decimal(8,0) DEFAULT NULL COMMENT 'Valor de la sanción apliacada al asociado',
  `asosanprocesada` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la sanción del asociado ha sido procesada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `cajaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla caja',
  `cajanumero` varchar(30) NOT NULL COMMENT 'Nombre o número de la caja'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`cajaid`, `cajanumero`) VALUES
(1, 'UNO'),
(2, 'DOS'),
(3, 'TRES'),
(4, 'CUATRO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargolaboral`
--

CREATE TABLE `cargolaboral` (
  `carlabid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla cargo laboral',
  `carlabnombre` varchar(100) NOT NULL COMMENT 'Nombre del cargo laboral',
  `carlabactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el cargo laboral',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargolaboral`
--

INSERT INTO `cargolaboral` (`carlabid`, `carlabnombre`, `carlabactivo`, `created_at`, `updated_at`) VALUES
(1, 'Desarrollador', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'Asociado', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'Conductor', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'Gerente', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 'Jefe de área', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 'Secretaria', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesoacta`
--

CREATE TABLE `coddocumprocesoacta` (
  `codopaid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso acta',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `tipactid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de acta',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `codopaconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo del acta',
  `codopasigla` varchar(3) NOT NULL COMMENT 'Sigla de la dependencia productora del acta',
  `codopaanio` year(4) NOT NULL COMMENT 'Año en el cual se crea el acta',
  `codopahorainicio` varchar(6) NOT NULL COMMENT 'Hora de inicio del acta',
  `codopahorafinal` varchar(6) NOT NULL COMMENT 'Hora de final del acta',
  `codopalugar` varchar(200) NOT NULL COMMENT 'Lugar donde se realiza el acta',
  `codopaquorum` varchar(200) DEFAULT NULL COMMENT 'Quorum reglamentario para el acta',
  `codopaordendeldia` varchar(4000) NOT NULL COMMENT 'Orden del dñia del acta',
  `codopainvitado` varchar(4000) DEFAULT NULL COMMENT 'Personas invitados para el acta',
  `codopaausente` varchar(4000) DEFAULT NULL COMMENT 'Persona usente en la generación para el acta',
  `codopaconvocatoria` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el acta tiene conovocatoria',
  `codopaconvocatorialugar` varchar(100) DEFAULT NULL COMMENT 'Lugar conovocatoria para el acta',
  `codopaconvocatoriafecha` date DEFAULT NULL COMMENT 'Fecha para la conovocatoria del acta',
  `codopaconvocatoriahora` varchar(6) DEFAULT NULL COMMENT 'Hora de la conovocatoria del acta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesoanexo`
--

CREATE TABLE `coddocumprocesoanexo` (
  `codopxid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso anexo',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `codopxnombreanexooriginal` varchar(200) NOT NULL COMMENT 'Nombre con el cual se ha subido el documento',
  `codopxnombreanexoeditado` varchar(200) NOT NULL COMMENT 'Nombre con el cual se ha subido el documento pero editado',
  `codopxrutaanexo` varchar(500) NOT NULL COMMENT 'Ruta enfuscada del anexo para el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocambioestado`
--

CREATE TABLE `coddocumprocesocambioestado` (
  `codpceid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso cambio estado',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `tiesdoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estado documento',
  `codpceusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del documento',
  `codpcefechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del documento',
  `codpceobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación del cambio estado documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocertificado`
--

CREATE TABLE `coddocumprocesocertificado` (
  `codopcid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso certificado',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `tipedoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de persona documental',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `codopcconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo de la certificado',
  `codopcsigla` varchar(3) NOT NULL COMMENT 'Sigla de la dependencia productora de la certificado',
  `codopcanio` year(4) NOT NULL COMMENT 'Año en el cual se crea la certificado',
  `codopctitulo` varchar(200) NOT NULL COMMENT 'Título con el que se crea la certificado',
  `codopccontenidoinicial` varchar(1000) NOT NULL COMMENT 'contenido incial de la certificado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocircular`
--

CREATE TABLE `coddocumprocesocircular` (
  `codoplid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso circular',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `tipdesid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo despedida',
  `codoplconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo de la circular',
  `codoplsigla` varchar(3) NOT NULL COMMENT 'Sigla de la dependencia productora de la circular',
  `codoplanio` year(4) NOT NULL COMMENT 'Año en el cual se crea la circular',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocitacion`
--

CREATE TABLE `coddocumprocesocitacion` (
  `codoptid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso citación',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `tipactid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de acta',
  `codoptconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo del citación',
  `codoptsigla` varchar(3) NOT NULL COMMENT 'Sigla de la dependencia productora del citación',
  `codoptanio` year(4) NOT NULL COMMENT 'Año en el cual se crea el citación',
  `codopthora` varchar(8) NOT NULL COMMENT 'Hora de la citación',
  `codoptlugar` varchar(80) NOT NULL COMMENT 'Lugar donde se realiza el citación',
  `codoptfecharealizacion` date NOT NULL COMMENT 'Fecha para la conovocatoria del citación',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocompartido`
--

CREATE TABLE `coddocumprocesocompartido` (
  `codopdid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso compartido',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario al que se le comparte el documento',
  `codopdfechacompartido` datetime NOT NULL COMMENT 'Fecha y hora en la cual se comparte el documento',
  `codopdfechaleido` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se lee el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesoconstancia`
--

CREATE TABLE `coddocumprocesoconstancia` (
  `codopnid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso constancia',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `tipedoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de persona documental',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `codopnconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo de la constancia',
  `codopnsigla` varchar(3) NOT NULL COMMENT 'Sigla de la dependencia productora de la constancia',
  `codopnanio` year(4) NOT NULL COMMENT 'Año en el cual se crea la constancia',
  `codopntitulo` varchar(200) NOT NULL COMMENT 'Título con el que se crea la constancia',
  `codopncontenidoinicial` varchar(1000) NOT NULL COMMENT 'contenido incial de la constancia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesocopia`
--

CREATE TABLE `coddocumprocesocopia` (
  `codoppid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso copia',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `depeid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador de la dependencia',
  `codoppescopiadocumento` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si es una copia en el documento',
  `codoppfechacompartido` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se comparte el documento',
  `codoppfechaleido` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se lee el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesofirma`
--

CREATE TABLE `coddocumprocesofirma` (
  `codopfid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso firma',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona',
  `carlabid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador de la tabla cargo laboral',
  `codopftoken` varchar(20) DEFAULT NULL COMMENT 'Token con el cual es firmado el documento',
  `codopffechahorafirmado` datetime DEFAULT NULL COMMENT 'Fecha y hora de la cual se firma el documento',
  `codopffechahoranotificacion` datetime DEFAULT NULL COMMENT 'Fecha y hora de la cual se envio la notifiación del token',
  `codopffechahoramaxvalidez` datetime DEFAULT NULL COMMENT 'Fecha y hora maxima de validez del token',
  `codopfmensajecorreo` varchar(500) DEFAULT NULL COMMENT 'Contendio de la información enviada al correo',
  `codopfmensajecelular` varchar(200) DEFAULT NULL COMMENT 'Contendio de la información enviada al celular',
  `codopffirmado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento esta firmado',
  `codopfesinvitado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el que firma es invitado en el acta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesooficio`
--

CREATE TABLE `coddocumprocesooficio` (
  `codopoid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso oficio',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `tipsalid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo saludo',
  `tipdesid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo despedida',
  `codopoconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo de la oficio',
  `codoposigla` varchar(3) NOT NULL COMMENT 'Sigla de la dependencia productora de la oficio',
  `codopoanio` year(4) NOT NULL COMMENT 'Año en el cual se crea la oficio',
  `codopotitulo` varchar(80) DEFAULT NULL COMMENT 'Título de la persona a la que va dirigido el ofico',
  `codopociudad` varchar(80) NOT NULL COMMENT 'Ciudad a la que va dirigido el oficio',
  `codopocargodestinatario` varchar(80) DEFAULT NULL COMMENT 'Nombre del cargo de la persona ala que va dirigido el oficio',
  `codopoempresa` varchar(80) DEFAULT NULL COMMENT 'Nombre de la persona o empresa a la que va dirigido el oficio',
  `codopodireccion` varchar(80) DEFAULT NULL COMMENT 'direción de la persona o empresa a la que va dirigido el oficio',
  `codopotelefono` varchar(20) DEFAULT NULL COMMENT 'Telefono de la persona o empresa a la que va dirigido el oficio',
  `codoporesponderadicado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si se esta respondiendo radicados en el oficio',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesoraddocentrante`
--

CREATE TABLE `coddocumprocesoraddocentrante` (
  `cdprdeid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso radicado documento entrante',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigodocumental`
--

CREATE TABLE `codigodocumental` (
  `coddocid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental',
  `depeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la dependencia',
  `serdocid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la serie documental',
  `susedoid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la sub serie',
  `tipdocid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tipo documento',
  `tipmedid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de medio',
  `tiptraid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de trámite',
  `tipdetid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de destino',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `coddocfechahora` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se crea el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigodocumentalproceso`
--

CREATE TABLE `codigodocumentalproceso` (
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `coddocid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental',
  `tiesdoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estado documento',
  `codoprfecha` date NOT NULL COMMENT 'Fecha en la cual se crea el documento',
  `codoprnombredirigido` varchar(4000) DEFAULT NULL COMMENT 'Nombre o nombres de la persona a quien va dirigido el documento',
  `codoprcargonombredirigido` varchar(1000) DEFAULT NULL COMMENT 'Cargo de la persona a quien va dirigido el documento',
  `codoprasunto` varchar(200) DEFAULT NULL COMMENT 'Asunto por el cual se crea el documento o título de la resolución',
  `codoprcorreo` varchar(1000) DEFAULT NULL COMMENT 'Correo de la persona o personas a quien van dirigir el documento',
  `codoprcontenido` longtext DEFAULT NULL COMMENT 'Contenido del documento',
  `codoprtieneanexo` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento tiene anexo',
  `codopranexonombre` varchar(300) DEFAULT NULL COMMENT 'Nombre del adjunto que se relaciona en el documento',
  `codoprtienecopia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento tiene copia',
  `codoprcopianombre` varchar(300) DEFAULT NULL COMMENT 'Nombre de la persona a quien va dirigido el documento como copia',
  `codoprrutadocumento` varchar(500) DEFAULT NULL COMMENT 'Nombre de la ruta al sellar el documento',
  `codoprsolicitafirma` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento se le ha solicitado la firma',
  `codoprfirmado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento ha sido firmado',
  `codoprsellado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento esta sellado',
  `codoprradicado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento fue radicado en ventanilla única',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colocacion`
--

CREATE TABLE `colocacion` (
  `coloid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla solicitud de credito desembolso',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea la colocación',
  `solcreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la solicitud de crédito',
  `tiesclid` varchar(2) NOT NULL COMMENT 'Identificador del tipo estado colocación',
  `colofechahoraregistro` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se registra la colocacion',
  `colofechadesembolso` date NOT NULL COMMENT 'Fecha de desembolso del crédito',
  `coloanio` year(4) NOT NULL COMMENT 'Año en el cual se desembolsa el crédito',
  `colonumerodesembolso` varchar(4) NOT NULL COMMENT 'Número de desembolso asignado por cada año',
  `colovalordesembolsado` decimal(12,0) NOT NULL COMMENT 'Monto o valor desembolsado',
  `colotasa` decimal(6,2) NOT NULL COMMENT 'Tasa de interés aplicado en el desembolso',
  `colonumerocuota` decimal(5,0) NOT NULL COMMENT 'Número de cuota aprobado en el desembolso',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colocacioncambioestado`
--

CREATE TABLE `colocacioncambioestado` (
  `cocaesid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla colocación cambio estado',
  `coloid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la colocación',
  `tiesclid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de estado colocación',
  `cocaesusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado de la colocación',
  `cocaesfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado de la colocación',
  `cocaesobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación del cambio estado de la colocación',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colocacionliquidacion`
--

CREATE TABLE `colocacionliquidacion` (
  `colliqid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla colocación liquidación',
  `coloid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la solicitud de crédito',
  `colliqnumerocuota` varchar(3) NOT NULL COMMENT 'Número de cuota de la colocación',
  `colliqvalorcuota` varchar(10) NOT NULL COMMENT 'Monto o valor de la cuota de la colocación',
  `colliqfechavencimiento` date NOT NULL COMMENT 'Fecha de vencimiento de la cuota de la colocación',
  `colliqfechapago` date DEFAULT NULL COMMENT 'Fecha de pago de la cuota de la colocación',
  `colliqnumerocomprobante` varchar(10) DEFAULT NULL COMMENT 'Número de comprobante de pago de la cuota de la colocación',
  `colliqvalorpagado` decimal(12,0) DEFAULT NULL COMMENT 'Valor pagado en la cuota de la colocación',
  `colliqsaldocapital` decimal(10,0) DEFAULT NULL COMMENT 'Saldo a capital de la colocación',
  `colliqvalorcapitalpagado` decimal(10,0) DEFAULT NULL COMMENT 'Valor capital pagado la colocación',
  `colliqvalorinterespagado` decimal(10,0) DEFAULT NULL COMMENT 'Valor interés pagado la colocación',
  `colliqvalorinteresmora` decimal(10,0) DEFAULT NULL COMMENT 'Valor interés de mora pagado la colocación',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobantecontable`
--

CREATE TABLE `comprobantecontable` (
  `comconid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla comprobante contable',
  `movcajid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador del movimiento caja',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia',
  `cajaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la caja',
  `comconanio` year(4) NOT NULL COMMENT 'Año en el cual se registra el comprobante contable',
  `comconconsecutivo` varchar(5) NOT NULL COMMENT 'Consecutivo del comprobante contable asignado por cada año',
  `comconfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el comprobante contable',
  `comcondescripcion` varchar(1000) NOT NULL COMMENT 'Descripción del comprobante contable',
  `comconfechahoracierre` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se cierra el comprobante contable',
  `comconestado` varchar(1) NOT NULL DEFAULT 'A' COMMENT 'Estado del comprobante contable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comprobantecontabledetalle`
--

CREATE TABLE `comprobantecontabledetalle` (
  `cocodeid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla movimiento caja detallado',
  `comconid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador del comprobante contable',
  `cueconid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la cuenta contable',
  `cocodefechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se realiza el registro',
  `cocodemonto` double(12,2) NOT NULL COMMENT 'Monto del movimiento de caja detallado',
  `cocodecontabilizado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el movimiento fue contabilizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductor`
--

CREATE TABLE `conductor` (
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla conductor',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `tiescoid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de estado del conductor',
  `tipconid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de conductor',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia a la que esta asignado el vehículo',
  `condfechaingreso` date NOT NULL COMMENT 'Fecha de ingreso del conductor a la cooperativa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductorcambioestado`
--

CREATE TABLE `conductorcambioestado` (
  `cocaesid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla conductor cambio estado',
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del conductor',
  `tiescoid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de estado conductor',
  `cocaesusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del conductor',
  `cocaesfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del conductor',
  `cocaesobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación del cambio estado del conductor',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductorlicencia`
--

CREATE TABLE `conductorlicencia` (
  `conlicid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla conductor licencia',
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del conductor',
  `ticaliid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de categoría de la licencia',
  `conlicnumero` varchar(30) NOT NULL COMMENT 'Número del licencia',
  `conlicfechaexpedicion` date NOT NULL COMMENT 'Fecha de expedición de la licencia',
  `conlicfechavencimiento` date NOT NULL COMMENT 'Fecha de vencimiento de la licencia',
  `conlicextension` varchar(5) DEFAULT NULL COMMENT 'Extensión del archivo que se anexa a la licencia',
  `conlicnombrearchivooriginal` varchar(200) DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa a la licencia',
  `conlicnombrearchivoeditado` varchar(200) DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa a la licencia',
  `conlicrutaarchivo` varchar(500) DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa a la licencia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `conductorvehiculo`
--

CREATE TABLE `conductorvehiculo` (
  `convehid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla conductor vehículo',
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del conductor',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracionencomienda`
--

CREATE TABLE `configuracionencomienda` (
  `conencid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla configuración encomienda',
  `conencvalorminimoenvio` decimal(10,0) NOT NULL COMMENT 'Valor mínimo del envío de la encomienda',
  `conencvalorminimodeclarado` decimal(10,0) NOT NULL COMMENT 'Valor mínimo declarado del envío de la encomienda',
  `conencporcentajeseguro` decimal(3,0) NOT NULL COMMENT 'Porcentaje del seguro del envío de la encomienda',
  `conencporcencomisionempresa` decimal(3,0) NOT NULL COMMENT 'Porcentaje de comisión de la empresa del envío de la encomienda',
  `conencporcencomisionagencia` decimal(3,0) NOT NULL COMMENT 'Porcentaje de comisión de la agencia del envío de la encomienda',
  `conencporcencomisionvehiculo` decimal(3,0) NOT NULL COMMENT 'Porcentaje de comisión del vehículo del envío de la encomienda',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracionencomienda`
--

INSERT INTO `configuracionencomienda` (`conencid`, `conencvalorminimoenvio`, `conencvalorminimodeclarado`, `conencporcentajeseguro`, `conencporcencomisionempresa`, `conencporcencomisionagencia`, `conencporcencomisionvehiculo`, `created_at`, `updated_at`) VALUES
(1, 3900, 10000, 1, 10, 20, 70, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consignacionbancaria`
--

CREATE TABLE `consignacionbancaria` (
  `conbanid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla consignacion bancaria',
  `entfinid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la entidad finaciera',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia',
  `conbanfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se registra la consignacion bancaria',
  `conbanmonto` decimal(10,2) NOT NULL COMMENT 'Cantidad de dinero consignada',
  `conbandescripcion` varchar(200) DEFAULT NULL COMMENT 'Descripción de la consignación realizada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratoservicioespecial`
--

CREATE TABLE `contratoservicioespecial` (
  `coseesid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla contrato servicio especial',
  `pecoseid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona que contrata el servicio especial',
  `persidgerente` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `ticoseid` varchar(2) NOT NULL COMMENT 'Identificador del tipo contrato servicio especial',
  `ticossid` varchar(2) NOT NULL COMMENT 'Identificador del tipo contrato servicio especial',
  `coseesfechahora` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se registra el contrato de servicio especial',
  `coseesanio` year(4) NOT NULL COMMENT 'Año en el cual se realiza el contrato de servicio especial',
  `coseesconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo del contrato de servicio especial dado por cada año',
  `coseesfechaincial` date NOT NULL COMMENT 'Fecha de inicio del contrato de servicio especial',
  `coseesfechafinal` date NOT NULL COMMENT 'Fecha final del contrato de servicio especial',
  `coseesvalorcontrato` varchar(20) NOT NULL COMMENT 'Valor del contrato de servicio especial',
  `coseesorigen` varchar(100) NOT NULL COMMENT 'Origen del contrato de servicio especial',
  `coseesdestino` varchar(100) NOT NULL COMMENT 'Destino del contrato de servicio especial',
  `coseesdescripcionrecorrido` varchar(1000) NOT NULL COMMENT 'Descripción del recorrido para el contrato de servicio especial',
  `coseesnombreuniontemporal` varchar(100) DEFAULT NULL COMMENT 'Nombre de la unión temporal para el contrato de servicio especial',
  `coseesobservacion` varchar(1000) DEFAULT NULL COMMENT 'Observación del contrato de servicio especial',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratoservicioespecialcond`
--

CREATE TABLE `contratoservicioespecialcond` (
  `coseecod` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla contrato servicio especial vehículo',
  `coseesid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del contrato servicio especial',
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del conductor',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contratoservicioespecialvehi`
--

CREATE TABLE `contratoservicioespecialvehi` (
  `coseevid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla contrato servicio especial vehículo',
  `coseesid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del contrato servicio especial',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `coseevextractoanio` year(4) NOT NULL COMMENT 'Año en el cual se realiza el extracto contrato de servicio especial para el vehículo',
  `coseevextractoconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo en el cual se realiza el extracto contrato de servicio especial para el vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentacontable`
--

CREATE TABLE `cuentacontable` (
  `cueconid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla cuenta contable',
  `cueconcodigo` varchar(20) NOT NULL COMMENT 'Codigo contable de la cuenta contable',
  `cueconnombre` varchar(200) NOT NULL COMMENT 'Nombre de la cuenta contable',
  `cueconnaturaleza` varchar(1) NOT NULL DEFAULT 'D' COMMENT 'Naturaleza de la cuenta contable',
  `cueconactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la cuenta contable se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cuentacontable`
--

INSERT INTO `cuentacontable` (`cueconid`, `cueconcodigo`, `cueconnombre`, `cueconnaturaleza`, `cueconactiva`, `created_at`, `updated_at`) VALUES
(5, '120005', 'CXP PAGO CUOTA CRÉDITO', 'C', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, '120002', 'CXP PAGO CUOTA CRÉDITO TOTAL', 'C', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, '120005', 'CXP PAGO SANCIÓN', 'C', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(8, '120006', 'CXP PAGO ENCOMIENDA', 'C', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(9, '120007', 'CXP PAGO ENCOMIENDA CONTRAENTREGA', 'C', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `depaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla departamento',
  `depacodigo` varchar(4) NOT NULL COMMENT 'Codigo del departamento',
  `depanombre` varchar(80) NOT NULL COMMENT 'Nombre del departamento',
  `depahacepresencia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la entidad hace presencia en este departamento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`depaid`, `depacodigo`, `depanombre`, `depahacepresencia`, `created_at`, `updated_at`) VALUES
(1, '05', 'Antioquia', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(2, '08', 'Atlántico', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(3, '11', 'Bogotá', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(4, '13', 'Bolivar', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(5, '15', 'Boyaca', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(6, '17', 'Caldas', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(7, '18', 'Caquetá', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(8, '19', 'Cauca', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(9, '20', 'Cesar', 1, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(10, '23', 'Cordoba', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(11, '25', 'Cundinamarca', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(12, '27', 'Chocó', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(13, '41', 'Huila', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(14, '44', 'La Guajira', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(15, '47', 'Magdalena', 1, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(16, '50', 'Meta', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(17, '52', 'Nariño', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(18, '54', 'Norte de Santander', 1, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(19, '63', 'Quindio', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(20, '66', 'Risaralda', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(21, '68', 'Santander', 1, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(22, '70', 'Sucre', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(23, '73', 'Tolima', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(24, '76', 'Valle del Cauca', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(25, '81', 'Arauca', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(26, '85', 'Casanare', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(27, '86', 'Putumayo', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(28, '88', 'San Andrés', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(29, '91', 'Amazonas', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(30, '94', 'Guainia', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(31, '95', 'Guaviare', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(32, '97', 'Vaupes', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(33, '99', 'Vichada', 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencia`
--

CREATE TABLE `dependencia` (
  `depeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia',
  `depejefeid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del jefe de la dependencia',
  `depecodigo` varchar(10) NOT NULL COMMENT 'Código de la dependencia',
  `depesigla` varchar(3) NOT NULL COMMENT 'Sigla de la dependencia',
  `depenombre` varchar(80) NOT NULL COMMENT 'Nombre de la dependencia',
  `depecorreo` varchar(80) NOT NULL COMMENT 'Correo de la dependencia',
  `depeactiva` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la dependencia se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dependencia`
--

INSERT INTO `dependencia` (`depeid`, `depejefeid`, `depecodigo`, `depesigla`, `depenombre`, `depecorreo`, `depeactiva`, `created_at`, `updated_at`) VALUES
(1, 1, '100', 'GER', 'GERENCIA', 'rdsalazarr@ufpso.edu.co', 1, '2024-02-01 21:00:42', '2024-02-01 21:00:42'),
(2, 1, '200', 'CON', 'CONTABILIDAD', 'radasa10@hotmail.com', 1, '2024-02-01 21:00:42', '2024-02-01 21:00:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependenciapersona`
--

CREATE TABLE `dependenciapersona` (
  `depperid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia persona',
  `depperdepeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la dependencia',
  `depperpersid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del persona asignado a la dependencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dependenciapersona`
--

INSERT INTO `dependenciapersona` (`depperid`, `depperdepeid`, `depperpersid`) VALUES
(1, 1, 1),
(2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependenciasubseriedocumental`
--

CREATE TABLE `dependenciasubseriedocumental` (
  `desusdid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia sub serie documental',
  `desusdsusedoid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla sub serie',
  `desusddepeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `dependenciasubseriedocumental`
--

INSERT INTO `dependenciasubseriedocumental` (`desusdid`, `desusdsusedoid`, `desusddepeid`) VALUES
(1, 1, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `emprid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla empresa',
  `persidrepresentantelegal` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `emprdepaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento',
  `emprmuniid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio',
  `emprnit` varchar(15) NOT NULL COMMENT 'Nit de la empresa',
  `emprdigitoverificacion` varchar(1) NOT NULL COMMENT 'Dígito de verificación de la empresa',
  `emprnombre` varchar(100) NOT NULL COMMENT 'Nombre de la empresa',
  `emprsigla` varchar(20) DEFAULT NULL COMMENT 'Sigla de la empresa',
  `emprlema` varchar(100) DEFAULT NULL COMMENT 'Lema de la empresa',
  `emprdireccion` varchar(80) NOT NULL COMMENT 'Dirección de la empresa',
  `emprbarrio` varchar(80) DEFAULT NULL COMMENT 'Barrio de la empresa',
  `emprpersoneriajuridica` varchar(50) DEFAULT NULL COMMENT 'Personería jurídica de la empresa',
  `emprcorreo` varchar(80) DEFAULT NULL COMMENT 'Correo de la empresa',
  `emprtelefonofijo` varchar(20) DEFAULT NULL COMMENT 'Teléfono fijo de contacto con la empresa',
  `emprtelefonocelular` varchar(20) DEFAULT NULL COMMENT 'Teléfono celular de contacto con la empresa',
  `emprhorarioatencion` varchar(200) DEFAULT NULL COMMENT 'Horario de atención',
  `emprurl` varchar(100) DEFAULT NULL COMMENT 'Url de la página web institucional',
  `emprcodigopostal` varchar(10) DEFAULT NULL COMMENT 'Código postal',
  `emprlogo` varchar(80) DEFAULT NULL COMMENT 'Logo de la empresa en en formato png',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`emprid`, `persidrepresentantelegal`, `emprdepaid`, `emprmuniid`, `emprnit`, `emprdigitoverificacion`, `emprnombre`, `emprsigla`, `emprlema`, `emprdireccion`, `emprbarrio`, `emprpersoneriajuridica`, `emprcorreo`, `emprtelefonofijo`, `emprtelefonocelular`, `emprhorarioatencion`, `emprurl`, `emprcodigopostal`, `emprlogo`, `created_at`, `updated_at`) VALUES
(1, 3, 18, 804, '890.505.424', '7', 'COOPERATIVA DE TRANSPORTADORES HACARITAMA', 'COOTRANSHACARITAMA', 'La empresa que integra la region', 'Calle 7 a 56 211 la ondina vía a rio de oro', 'Santa Clara', 'Personería Jurídica No. 73 enero 28 de 1976', 'cootranshacaritama@hotmail.com', '5611012', '3146034311', 'Lunes a Viernes De 8:00 a.m a 12:00  y de 2:00 p.m a 6:00 p.m', 'www.cootranshacaritama.com', '546552', '890505424_logoHacaritama.png', '2024-02-01 21:00:24', '2024-02-01 21:00:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encomienda`
--

CREATE TABLE `encomienda` (
  `encoid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla encomienda',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia que esta registrando la encomienda',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el registro de la encomienda',
  `plarutid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la planilla ruta',
  `perseridremitente` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona que envia la encomienda',
  `perseriddestino` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona que recibe la encomienda',
  `depaidorigen` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de origen de la encomienda',
  `muniidorigen` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de origen de la encomienda',
  `depaiddestino` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de destino de la encomienda',
  `muniiddestino` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de destino de la encomienda',
  `tipencid` varchar(2) NOT NULL COMMENT 'Identificador del tipo encomienda',
  `tiesenid` varchar(2) NOT NULL COMMENT 'Identificador del tipo estado encomienda',
  `encoanio` year(4) NOT NULL COMMENT 'Año en el cual se registra la encomienda',
  `encoconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo de la encomienda asignado por cada año',
  `encofechahoraregistro` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se registra la encomienda',
  `encocontenido` varchar(1000) NOT NULL COMMENT 'Descripción del contenido de la encomienda',
  `encocantidad` decimal(4,0) NOT NULL COMMENT 'Cantidad de elemento en la encomienda',
  `encovalordeclarado` decimal(10,0) NOT NULL COMMENT 'Valor declarado en la encomienda',
  `encovalorenvio` decimal(10,0) NOT NULL COMMENT 'Valor del envío de la encomienda',
  `encovalordomicilio` decimal(10,0) DEFAULT NULL COMMENT 'Valor del domicilio de la encomienda',
  `encovalorcomisionseguro` decimal(10,0) NOT NULL COMMENT 'Valor de comisión del seguro de la encomienda',
  `encovalorcomisionempresa` decimal(10,0) NOT NULL COMMENT 'Valor de comisión para la empresa sobre la encomienda',
  `encovalorcomisionagencia` decimal(10,0) NOT NULL COMMENT 'Valor de comisión para la agencia que envía la encomienda',
  `encovalorcomisionvehiculo` decimal(10,0) NOT NULL COMMENT 'Valor de comisión para el vehículo que transporta la encomienda',
  `encovalortotal` decimal(10,0) NOT NULL COMMENT 'Valor total de la encomienda',
  `encoobservacion` varchar(500) DEFAULT NULL COMMENT 'Observacion de la encomienda',
  `encofecharecibido` date DEFAULT NULL COMMENT 'Fecha de recibido de la encomienda',
  `encopagocontraentrega` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la encomienda debe ser pagada contra entrega',
  `encocontabilizada` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la encomienda fue contabilizada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encomiendacambioestado`
--

CREATE TABLE `encomiendacambioestado` (
  `encaesid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla encomienda cambio estado',
  `encoid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la encomienda',
  `tiesenid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de estado encomienda',
  `encaesusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado de la encomienda',
  `encaesfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado de la encomienda',
  `encaesobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación del cambio estado de la encomienda',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidadfinanciera`
--

CREATE TABLE `entidadfinanciera` (
  `entfinid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla entidad finaciera',
  `entfinnombre` varchar(100) NOT NULL COMMENT 'Nombre de la entidad finaciera',
  `entfinnumerocuenta` varchar(20) DEFAULT NULL COMMENT 'Número de cuenta de la entidad finaciera',
  `entfinactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la entidad finaciera se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `festivo`
--

CREATE TABLE `festivo` (
  `festid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla festivo',
  `festfecha` date NOT NULL COMMENT 'Fecha que corresponde a un festivo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funcionalidad`
--

CREATE TABLE `funcionalidad` (
  `funcid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla funcionalidad',
  `moduid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del módulo',
  `funcnombre` varchar(80) NOT NULL COMMENT 'Nombre de la funcionalidad',
  `functitulo` varchar(80) DEFAULT NULL COMMENT 'Título de la funcionalidad',
  `funcruta` varchar(60) DEFAULT NULL COMMENT 'Ruta de la funcionalidad',
  `funcicono` varchar(30) DEFAULT NULL COMMENT 'Clase de css para montar en el link del menú',
  `funcorden` smallint(6) NOT NULL COMMENT 'Orden del en el árbol del menú',
  `funcactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la funcionalidad encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `funcionalidad`
--

INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(1, 1, 'Menú', 'Gestionar menú', 'admin/configurar/menu', 'add_chart', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(2, 1, 'Notificación correo', 'Gestionar información de notificar correo', 'admin/configurar/notificarCorreo', 'mail_outline_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(3, 1, 'Información PDF', 'Gestionar información PDF', 'admin/configurar/GeneralPdf', 'picture_as_pdf', 3, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(4, 1, 'Datos territorial', 'Gestionar datos territorial', 'admin/configurar/datosTerritorial', 'language_icon', 4, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(5, 1, 'Empresa', 'Gestionar empresa', 'admin/configurar/empresa', 'store', 5, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(6, 2, 'Tipos', 'Gestionar tipos', 'admin/gestionar/tipos', 'star_rate_icon', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(7, 2, 'Series', 'Gestionar series documentales', 'admin/gestionar/seriesDocumentales', 'insert_chart_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(8, 2, 'Dependencia', 'Gestionar dependencia', 'admin/gestionar/dependencia', 'maps_home_work_icon', 3, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(9, 2, 'Persona', 'Gestionar persona', 'admin/gestionar/persona', 'person_icon', 4, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(10, 2, 'Agencia', 'Gestionar agencia', 'admin/gestionar/agencia', 'holiday_village_con', 5, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(11, 2, 'Usuario', 'Gestionar usuario', 'admin/gestionar/usuario', 'person', 6, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(12, 2, 'Festivos', 'Gestionar festivos', 'admin/gestionar/festivos', 'calendar_month_icon', 7, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(13, 2, 'Cuenta contable', 'Gestionar cuentas contables', 'admin/gestionar/cuentaContable', 'repeat_one_icon', 8, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(14, 3, 'Acta', 'Gestionar actas', 'admin/produccion/documental/acta', 'local_library_icon', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(15, 3, 'Certificado', 'Gestionar certificados', 'admin/produccion/documental/certificado', 'note_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(16, 3, 'Circular', 'Gestionar circulares', 'admin/produccion/documental/circular', 'menu_book_icon', 3, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(17, 3, 'Citación', 'Gestionar citaciones', 'admin/produccion/documental/citacion', 'collections_bookmark_icon', 4, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(18, 3, 'Constancia', 'Gestionar constancias', 'admin/produccion/documental/constancia', 'auto_stories_icon', 5, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(19, 3, 'Oficio', 'Gestionar oficios', 'admin/produccion/documental/oficio', 'library_books_icon', 6, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(20, 3, 'Firmar', 'Firmar documentos', 'admin/produccion/documental/firmar', 'import_contacts_icon', 7, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(21, 4, 'Documento entrante', 'Gestionar documento entrante', 'admin/radicacion/documento/entrante', 'post_add_icon', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(22, 4, 'Anular radicado', 'Gestionar anulado de radicado', 'admin/radicacion/documento/anular', 'layers_clear_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(23, 4, 'Bandeja de radicado', 'Gestionar bandeja de radicado', 'admin/radicacion/documento/bandeja', 'content_paste_go_icon', 3, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(24, 5, 'Gestionar', 'Gestionar archivo histórico', 'admin/archivo/historico/gestionar', 'ac_unit_icon', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(25, 5, 'Consultar', 'Gestionar consulta del archivo histórico', 'admin/archivo/historico/consultar', 'find_in_page_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(26, 6, 'Procesar', 'Procesar asociados', 'admin/gestionar/asociados', 'person_add_alt1_icon', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(27, 6, 'Desvincular', 'Desvincular asociado', 'admin/gestionar/desvincularAsociado', 'person_remove_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(28, 6, 'Sancionar', 'Getionar sanciones asociado', 'admin/gestionar/sancionarAsociado', 'person_add_disabled_icon', 3, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(29, 6, 'Inactivos', 'Gestionar asociados inactivos', 'admin/gestionar/asociadosInactivos', 'person_off_icon', 4, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(30, 7, 'Tipos de vehiculos', 'Gestionar tipos de vehículos', 'admin/direccion/transporte/tipos', 'car_crash_icon', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(31, 7, 'Vehículos', 'Gestionar vehículos', 'admin/direccion/transporte/vehiculos', 'electric_car_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(32, 7, 'Conductores', 'Gestionar conductores', 'admin/direccion/transporte/conductores', 'attach_money_icon', 3, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(33, 7, 'Asignación vehículos', 'Gestionar asignación de vehículos', 'admin/direccion/transporte/asignarVehiculo', 'credit_score_icon', 4, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(34, 7, 'Distribución', 'Getionar Distribución de vehículo', 'admin/direccion/transporte/distribucionVehiculos', 'local_car_wash_icon', 5, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(35, 7, 'Suspender', 'Getionar suspención de vehículo', 'admin/direccion/transporte/suspenderVehiculo', 'no_transfer_icon', 6, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(36, 8, 'Línea de crédito', 'Gestionar línea de crédito', 'admin/cartera/lineaCredito', 'add_chart', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(37, 8, 'Solicitud', 'Gestionar solicitud de crédito', 'admin/cartera/solicitud', 'add_card_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(38, 8, 'Aprobación', 'Aprobar solicitud de crédito', 'admin/cartera/aprobacion', 'credit_score_icon', 3, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(39, 8, 'Desembolso', 'Getionar desembolso', 'admin/cartera/desembolso', 'attach_money_icon', 4, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(40, 8, 'Historial S.C.', 'Getionar historial de solicitud de crédito', 'admin/cartera/historial', 'auto_stories_icon ', 5, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(41, 8, 'Cobranza', 'Getionar cobranza', 'admin/cartera/cobranza', 'table_chart_icon', 6, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(42, 9, 'Rutas', 'Getionar de rutas', 'admin/despacho/rutas', 'directions_icon', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(43, 9, 'Planillas', 'Getionar planillas', 'admin/despacho/planillas', 'no_crash_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(44, 9, 'Encomiendas', 'Getionar encomiendas', 'admin/despacho/encomiendas', 'local_shipping_icon', 3, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(45, 9, 'Tiquetes', 'Getionar tiquetes', 'admin/despacho/tiquetes', 'card_travel_icon', 4, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(46, 9, 'Recibir', 'Getionar proceso de recibir planilla o encomienda', 'admin/despacho/recibirPlanilla', 'checklist_rtl_icon', 5, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(47, 9, 'Servico especial', 'Getionar planilla de servico especial', 'admin/despacho/servicioEspecial', 'taxi_alert_icon', 6, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(48, 10, 'Procesar', 'Procesar movimientos de caja', 'admin/caja/procesar', 'currency_exchange_icon', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(49, 10, 'Cerrar', 'Cerrar moviemiento de caja', 'admin/caja/cerrar', 'close_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historialcontrasena`
--

CREATE TABLE `historialcontrasena` (
  `hisconid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla historial de contrasena',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `hisconpassword` varchar(255) NOT NULL COMMENT 'Password del usuario utilizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacionconfiguracioncorreo`
--

CREATE TABLE `informacionconfiguracioncorreo` (
  `incocoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla información configuración del correo',
  `incocohost` varchar(50) NOT NULL COMMENT 'Host para el cual se permite enviar el correo',
  `incocousuario` varchar(80) NOT NULL COMMENT 'Usuario o correo con el cual se va autenticar para enviar los correos en el sistema',
  `incococlave` varchar(20) NOT NULL COMMENT 'Clave del correo para acceder a la plataforma',
  `incococlaveapi` varchar(20) NOT NULL COMMENT 'Clave de la api para autenticar y poder enviar el corro',
  `incocopuerto` varchar(4) NOT NULL COMMENT 'Puerto por el cual se envia el correo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informacionconfiguracioncorreo`
--

INSERT INTO `informacionconfiguracioncorreo` (`incocoid`, `incocohost`, `incocousuario`, `incococlave`, `incococlaveapi`, `incocopuerto`, `created_at`, `updated_at`) VALUES
(1, 'smtp.gmail.com', 'notificacioncootranshacaritama@gmail.com', 'Notific@2023.', 'grgsmqtlmijxaapj', '587', '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informaciongeneralpdf`
--

CREATE TABLE `informaciongeneralpdf` (
  `ingpdfid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla información general PDF',
  `ingpdfnombre` varchar(50) NOT NULL COMMENT 'Nombre general para utilizar la consulta de la información en PDF',
  `ingpdftitulo` varchar(100) NOT NULL COMMENT 'Título de la información general del PDF',
  `ingpdfcontenido` longtext NOT NULL COMMENT 'Contenido de la información que lleva PDF',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informaciongeneralpdf`
--

INSERT INTO `informaciongeneralpdf` (`ingpdfid`, `ingpdfnombre`, `ingpdftitulo`, `ingpdfcontenido`, `created_at`, `updated_at`) VALUES
(1, 'pagareColocacion', 'PAGARÉ NÚMERO  numeroPagare', '<table style=\"border-collapse: collapse; width: 100.008%;\" border=\"0\"><colgroup><col style=\"width: 27.3991%;\"><col style=\"width: 25.5309%;\"><col style=\"width: 25.5303%;\"><col style=\"width: 21.5878%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td>N&uacute;mero de pagar&eacute;:</td>\r\n<td><strong>numeroPagare</strong></td>\r\n<td>Valor del cr&eacute;dito:</td>\r\n<td>$ <strong>valorCredito</strong></td>\r\n</tr>\r\n<tr>\r\n<td>Fecha de la solicitud:</td>\r\n<td>fechaSolicitud</td>\r\n<td>Fecha del desembolso:</td>\r\n<td><strong>fechaDesembolso</strong></td>\r\n</tr>\r\n<tr>\r\n<td>Fecha de la primera cuota:</td>\r\n<td>fechaPrimeraCuota</td>\r\n<td>Fecha de la &uacute;ltima cuota:</td>\r\n<td>fechaUltimaCuota</td>\r\n</tr>\r\n<tr>\r\n<td>Inter&eacute;s mensual:</td>\r\n<td>interesMensual %</td>\r\n<td>N&uacute;mero de cuotas:</td>\r\n<td>numeroCuota</td>\r\n</tr>\r\n<tr>\r\n<td>Destinaci&oacute;n del cr&eacute;dito:</td>\r\n<td colspan=\"3\">destinacionCredito</td>\r\n</tr>\r\n<tr>\r\n<td>Referencia:</td>\r\n<td>referenciaCredito</td>\r\n<td>Garant&iacute;a:</td>\r\n<td>garantiaCredito</td>\r\n</tr>\r\n<tr>\r\n<td>N&uacute;mero interno:</td>\r\n<td>numeroInternoVehiculo</td>\r\n<td>Placa:</td>\r\n<td>placaVehiculo</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify;\">El Suscrito, <strong>nombreAsociado </strong>identificado con tpDocumentoAsociado <strong>documentoAsociado</strong>, deudor(a) principal me obligo a pagar solidaria e incondicionalmente en dinero en efectivo a COOTRANSHACARITAMA LTDA, en su oficina de Oca&ntilde;a N.S, a su orden o a quien represente sus derechos, la suma de ($ <strong>valorCredito</strong>), (<strong>valorEnLetras</strong>) moneda legal recibida en calidad de mutuo o pr&eacute;stamo a inter&eacute;s. INTERESES: Que sobre la suma debida reconocer&eacute; intereses equivalentes al <strong>interesMensual</strong>% mensual, sobre el saldo de capital insoluto, los cuales se liquidar&aacute;n y pagar&aacute;n mes vencido, junto con la cuota mensual correspondiente al mes de causaci&oacute;n. En caso de mora, reconocer&eacute; intereses moratorios del <strong>interesMoratorio</strong>% mensual. PARAGRAFO: En caso que la tasa de inter&eacute;s corriente y/o moratorio pactado, sobrepase los topes m&aacute;ximos permitidos por las disposiciones comerciales, dichas tasas se ajustar&aacute;n mensualmente a los m&aacute;ximos legales. PLAZO: Que pagar&eacute; la suma indicada en la cl&aacute;usula anterior mediante instalamentos mensuales sucesivos y en <strong>numeroCuota </strong>cuota(s), correspondientes cada una a la cantidad de $ <strong>valorCuota</strong>,&nbsp; m&aacute;s los intereses corrientes sobre el saldo, a partir del d&iacute;a fechaLargaPrestamo. VENCIMIENTO DEL PLAZO: Autorizo a COOTRANSHACARITAMA LTDA para declarar vencido totalmente el plazo de esta obligaci&oacute;n y exigir el pago inmediato del saldo, intereses, gastos judiciales y de los que se causen por el cobro de la obligaci&oacute;n, en cualquiera de los siguientes casos: a) Por mora de una o m&aacute;s cuotas de capital o de los intereses de esta o cualquier obligaci&oacute;n que, conjunta o separadamente, tenga contra&iacute;da a favor de COOTRANSHACARITAMA LTDA ; b) Si fuere demandado judicialmente o si los bienes de cualquiera de los otorgantes son embargados o perseguidos por la v&iacute;a judicial; c) Por muerte, concordato, quiebra, concurso de acreedores, disoluci&oacute;n, liquidaci&oacute;n o inhabilidad de uno de los otorgantes; d) Si mis activos se disminuyen, los bienes dados en garant&iacute;a se gravan o enajenan en todo o en parte o dejan de ser respaldo suficiente de la(s) obligaci&oacute;n(es) adquirida(s) o si incumpliera la obligaci&oacute;n de mantener actualizada la garant&iacute;a; e) Si la inversi&oacute;n del cr&eacute;dito fuese diferente de la convenida o de la mencionada en la solicitud del pr&eacute;stamo; f) si no actualizo(amos) oportunamente la informaci&oacute;n legal y financiera en los plazos que determine COOTRANSHACARITAMA LTDA; g) Las dem&aacute;s que las reglamentaciones internas de COOTRANSHACARITAMA LTDA contemplen. GASTOS E IMPUESTOS: Todos los gastos e impuestos que cause este pagar&eacute; sean de mi cargo, as&iacute; como los honorarios de abogado, costos judiciales y dem&aacute;s gastos que se generen. Me oblig&oacute; a cancelar las primas de seguros en las condiciones establecidas en las p&oacute;lizas respectivas. Autorizo a COOTRANSHACARITAMA LTDA para debitar de la(s) cuenta(s) de dep&oacute;sito(s) en todas las modalidades que tenga cualquiera de los otorgantes, el importe de este t&iacute;tulo valor, la cuota o cuotas respectivas, los intereses, primas de seguros y dem&aacute;s gastos o impuestos causados por esta obligaci&oacute;n. DESCUENTOS LABORALES: De acuerdo con lo previsto en el art&iacute;culo 142 de la ley 79 de 1988, autorizo (amos) irrevocablemente a la persona natural o jur&iacute;dica, p&uacute;blica o privada, a quien corresponda realizarme el pago de cualquier cantidad de dinero por concepto laboral o prestaciones, para que deduzca o retenga de estos valores, sin perjuicio de las acciones judiciales que quiera iniciar directamente sin hacer valer la autorizaci&oacute;n. Se suscribe en la ciudad de Oca&ntilde;a a los fechaLargaDesembolso.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', '2024-02-01 21:00:53', '2024-02-01 21:00:53'),
(2, 'cartaInstrucciones', 'REFERENCIA: CARTA DE INSTRUCCIONES', '<p style=\"text-align: justify;\">Yo, <strong>nombreAsociado &nbsp;</strong>mayor de edad, identificado como aparece al pie de mi firma, actuando en nombre propio, por medio del presente escrito manifiesto que le faculto a usted, de manera permanente e irrevocable para que, en caso de incumplimiento en el pago oportuno de alguna de las obligaciones que hemos adquirido con usted, derivadas de los negocios comerciales y contractuales bien sean verbales o escritos; sin previo aviso, proceda a llenar los espacios en blanco La letra del pagar&eacute; No. <strong>numeroPagare </strong>que he suscrito en la fecha a su favor y que se anexa, con el fin de convertir el pagar&eacute;, en un documento que presta m&eacute;rito ejecutivo y que est&aacute; sujeto a los par&aacute;metros legales del Art&iacute;culo 622 del C&oacute;digo de Comercio.</p>\r\n<p style=\"text-align: justify;\">1. El espacio correspondiente a &ldquo;la suma cierta de&rdquo; se llenar&aacute; por una suma igual a la que resulte pendiente de pago de todas la obligaciones contra&iacute;das con el acreedor, por concepto de capital, intereses, seguros, cobranza extrajudicial, seg&uacute;n la contabilidad del acreedor a la fecha en que sea llenado el pagar&eacute;.</p>\r\n<p style=\"text-align: justify;\">2. El espacio correspondiente a la fecha en que se debe hacer el pago, se llenar&aacute; con la fecha correspondiente al d&iacute;a en que sea llenado el pagar&eacute;, fecha que se entiende que es la de su vencimiento.</p>\r\n<p style=\"text-align: justify;\">En constancia de lo anterior firmamos la presente autorizaci&oacute;n, el d&iacute;a fechaLargaPrestamo.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">EL DEUDOR,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', '2024-02-01 21:00:53', '2024-02-01 21:00:53'),
(3, 'fichaTecnica', 'FICHA TÉCNICA', '<p class=\"MsoNormal\"><strong>FICHA T&Eacute;CNICA</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">FORMATO UNICO DE EXTRACTO DEL CONTRATO \"FUEC\" REVERSO</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">INSTRUCTIVO PARA LA DETERMINACI&Oacute;N DEL N&Uacute;MERO CONSECUTIVO DEL FUEC</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">El formato &Uacute;nico de Extracto del Contrato \"FUEC\" estar&aacute; constituida por los siguientes n&uacute;meros:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">a) Los tres primeros d&iacute;gitos de izquierda a derecha corresponder&aacute;n al c&oacute;digo de la Direcci&oacute;n Territorial que otorg&oacute; la habilitaci&oacute;n de la empresa de transporte de Servicio Especial.</p>\r\n<table style=\"border-collapse: collapse; width: 90%;\" border=\"1\"><colgroup><col style=\"width: 35%;\"><col style=\"width: 15%;\"><col style=\"width: 35%;\"><col style=\"width: 15%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td style=\"width: 40%;\"><strong>Antioquia - Choc&oacute;</strong></td>\r\n<td style=\"width: 10%; text-align: center;\"><strong>305</strong></td>\r\n<td style=\"width: 40%;\"><strong>Huila - Caquet&aacute;</strong></td>\r\n<td style=\"width: 10%; text-align: center;\"><strong>441</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Atl&aacute;ntico</strong></td>\r\n<td style=\"text-align: center;\"><strong>208</strong></td>\r\n<td><strong>Magdalena</strong></td>\r\n<td style=\"text-align: center;\"><strong>247</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Bolivar - San Andr&eacute;s y Providencia</strong></td>\r\n<td style=\"text-align: center;\"><strong>213</strong></td>\r\n<td><strong>Meta - Vaup&eacute;s - Vichada</strong></td>\r\n<td style=\"text-align: center;\"><strong>550</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Boyac&aacute; - Casanare</strong></td>\r\n<td style=\"text-align: center;\"><strong>415</strong></td>\r\n<td><strong>Nari&ntilde;o - Putumayo</strong></td>\r\n<td style=\"text-align: center;\"><strong>352</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Caldas</strong></td>\r\n<td style=\"text-align: center;\"><strong>317</strong></td>\r\n<td><strong>N/Santander - Arauca</strong></td>\r\n<td style=\"text-align: center;\"><strong>454</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Cauca</strong></td>\r\n<td style=\"text-align: center;\"><strong>319</strong></td>\r\n<td><strong>Quind&iacute;o</strong></td>\r\n<td style=\"text-align: center;\"><strong>363</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Cesar</strong></td>\r\n<td style=\"text-align: center;\"><strong>220</strong></td>\r\n<td><strong>Risaralda</strong></td>\r\n<td style=\"text-align: center;\"><strong>366</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>C&oacute;rdoba - Sucre</strong></td>\r\n<td style=\"text-align: center;\"><strong>223</strong></td>\r\n<td><strong>Santander</strong></td>\r\n<td style=\"text-align: center;\"><strong>468</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Cundinamarca</strong></td>\r\n<td style=\"text-align: center;\"><strong>425</strong></td>\r\n<td><strong>Tolima</strong></td>\r\n<td style=\"text-align: center;\"><strong>473</strong></td>\r\n</tr>\r\n<tr>\r\n<td><strong>Guajira</strong></td>\r\n<td style=\"text-align: center;\"><strong>241</strong></td>\r\n<td><strong>Valle del Cauca</strong></td>\r\n<td style=\"text-align: center;\"><strong>376</strong></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">b) Los cuatro d&iacute;gitos siguientes se&ntilde;alar&aacute;n el n&uacute;mero de resoluci&oacute;n mediante el cual se otorg&oacute; la habilitaci&oacute;n de la Empresa. En caso que la resoluci&oacute;n no tenga estos d&iacute;gitos, los faltantes ser&aacute;n completados con ceros a la izquierda.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">c) Los dos siguientes d&iacute;gitos corresponder&aacute;n a los dos &uacute;ltimos del a&ntilde;o en que la empresa fue habilitada.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">d) A continuaci&oacute;n, cuatro d&iacute;gitos que corresponder&aacute;n al a&ntilde;o en que se expide el extracto del contrato.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">e) Posteriormente, cuatro d&iacute;gitos que identifican el n&uacute;mero del contrato. La numeraci&oacute;n debe ser consecutiva, establecida por cada empresa y continuar&aacute; con la numeraci&oacute;n dada a los contratos de prestaci&oacute;n de servicio celebrados para el transporte de estudiantes, empleados, turistas, usuarios del servicio de salud y grupos espec&iacute;ficos de usuarios, en vigencia de la resoluci&oacute;n 3068 de 2014.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">f) Finalmente, los cuatro &uacute;ltimos d&iacute;gitos corresponder&aacute;n al n&uacute;mero consecutivo o identificaci&oacute;n interna del extracto de contrato que se expida, para la ejecuci&oacute;n de cada contrato. Se debe expedir un nuevo extracto por vencimiento del plazo inicial del mismo o por cada cambio de veh&iacute;culo.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Ejemplo:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Empresa habilitada por la Direcci&oacute;n Territorial Norte de Santander en el a&ntilde;o 2002, con resoluci&oacute;n de habilitaci&oacute;n N. 0083 que expide el primer extracto del contrato en el a&ntilde;o 2023, del contrato 0693. El n&uacute;mero del Formato &Uacute;nico de Extracto del Contrato \"FUE\" ser&aacute;: numeroContratoServicioEspecial.</p>', '2024-02-01 21:00:53', '2024-02-01 21:00:53'),
(4, 'contratoTransporteEspecial', 'CONTRATO DE TRANSPORTE ESPECIAL numeroContratoServicioEspecial', '<p style=\"text-align: justify;\">Entre los suscritos nombreGerente, identificado con C.C. documentoGerente de Oca&ntilde;a, quien obra en representaci&oacute;n legal de COOPERATIVA DE TRANSPORTADORES HACARITAMA con NIT: 890.505.424-7, domiciliado en Oca&ntilde;a y quien para efectos del presente contrato se llamar&aacute; EL CONTRATISTA y por otra parte nombreContratante identificado(a) con NIT/C.C documentoContratante y quien para el presente contrato se denominar&aacute; EL CONTRATANTE, hemos celebrado el presente contrato que consta de las siguientes cl&aacute;usulas: <strong>PRIMERA</strong>: EL CONTRATISTA se compromete a poner a disposici&oacute;n de EL CONTRATANTE descripcionServicoContratado. <strong>SEGUNDA</strong>: OBJETO: objetoContrato. EL CONTRATISTA se compromete a transportar el personal que EL CONTRATANTE le conf&iacute;a en la ruta especificada en la cl&aacute;usula siguiente.&nbsp;<strong>TERCERA</strong>: RUTA: EL CONTRATANTE estipula como ruta la siguiente: Origen: origenContrato, Destino: destinoContrato. <strong>CUARTA</strong>: DIAS CONTRATADOS: El contrato iniciar&aacute; en el origenContrato el d&iacute;a fechaInicialContrato, con destino a destinoContrato hasta el d&iacute;a fechaFinalContrato. <strong>QUINTA</strong>: VALOR: El presente contrato tiene un valor de $valorContrato. Forma de pago: CONTADO.&nbsp;<strong>SEXTA</strong>: El veh&iacute;culo est&aacute; en &oacute;ptimas condiciones de seguridad y mec&aacute;nicas para el transporte del personal en la ruta acordada y porta las p&oacute;lizas contractuales y extracontractuales. <strong>SEPTIMA</strong>: EL CONTRATISTA se compromete a cumplir con los decretos y disposiciones emanados del Ministerio de Transporte, en lo concerniente al transporte de personal contemplados en el Decreto 0348 del 2015, as&iacute; como las dem&aacute;s normas que emita el gobierno para mejorar el transporte.</p>\r\n<p style=\"text-align: justify;\"><br>En constancia firmamos el presente contrato el d&iacute;a fechaInicialContrato.</p>', '2024-02-01 21:00:53', '2024-02-01 21:00:53');
INSERT INTO `informaciongeneralpdf` (`ingpdfid`, `ingpdfnombre`, `ingpdftitulo`, `ingpdfcontenido`, `created_at`, `updated_at`) VALUES
(5, 'contratoModalidadIntermunicipal', 'CONTRATO DE VINCULACIÓN POR AFILIACIÓN', '<p style=\"text-align: justify;\">Entre los suscritos a saber nombreGerente, mayor de edad y con cedula de ciudadan&iacute;a No. documentoGerente expedida en ciudadExpDocumentoGerente, quien act&uacute;a como representante legal de la Cooperativa de Transportadores Hacaritama, <strong>COOTRANSHACARITAMA </strong>identificada con NIT 890.505.424 - 7,&nbsp; quien en adelante se llamara LA EMPRESA y el mencionado en la parte inicial del contrato quien obra como propietario del automotor descrito inscrito en matricula, y quien en adelante se llamar&aacute; EL CONTRATISTA, por la otra parte; hacemos constar que hemos celebrado el contrato de administraci&oacute;n por afiliaci&oacute;n por la modalidad de afiliaci&oacute;n, el cual se rige por las siguientes cl&aacute;usulas:</p>\r\n<p style=\"text-align: justify;\"><strong>PRIMERA. OBJETO DEL CONTRATO.</strong> El contratista vincula el veh&iacute;culo cuyas caracter&iacute;sticas se mencionan anteriormente, con el objeto de prestar el servicio p&uacute;blico de transporte terrestre automotor individual de conformidad con la habilitaci&oacute;n otorgada mediante la Resoluci&oacute;n &hellip;&hellip;&hellip; del &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;.Expedida &nbsp;por LA SECRETARIA DE TRANSITO DE &hellip;&hellip;&hellip;&hellip;.., radio de acci&oacute;n MUNICIPAL, y la Empresa &nbsp;por la suma convenida que se pagara de forma mensual y el cumplimiento de las dem&aacute;s obligaciones, &nbsp;le permitir&aacute; usufructuar y usar la raz&oacute;n social y los dem&aacute;s beneficios &nbsp;que se estipulen &nbsp;en este Contrato o que se desprenden de la naturaleza seg&uacute;n las normas vigentes sobre la materia,</p>\r\n<p style=\"text-align: justify;\"><strong>PARAGRAFO SEGUNDO:</strong>-El uso y usufructo de la raz&oacute;n social de la empresa constituye una contraprestaci&oacute;n contractual que recibe el propietario del automotor, pues aquella es y seguir&aacute; siendo de propiedad &nbsp;exclusiva &nbsp;de &nbsp;la Empresa obtenida a trav&eacute;s de su constituci&oacute;n y de la Licencia de Funcionamiento &nbsp;y/o &nbsp;Habilitaci&oacute;n &nbsp;para operar &nbsp;en &nbsp;la actividad &nbsp;del &nbsp;servicio p&uacute;blico de transporte terrestre automotor especial &nbsp;y no puede ser objeto de transferencia a ning&uacute;n t&iacute;tulo de conformidad con lo establecido en el art&iacute;culo 13 de la ley 336 de 1996.</p>\r\n<p style=\"text-align: justify;\"><strong>SEGUNDA.- VINCULACI&Oacute;N DEL VEHICULO</strong>.- &nbsp;Para el cumplimiento de la finalidad mencionada en la cl&aacute;usula anterior, el Contratista &nbsp;vincula a la Empresa el veh&iacute;culo de su propiedad, manifestando que este se encuentra libre &nbsp;de &nbsp;acciones &nbsp;reales, &nbsp;pleitos pendientes, embargos y &oacute;rdenes de retenci&oacute;n oficiales, condiciones resolutorias, y que el veh&iacute;culo no se encuentra vinculado a ninguna otra empresa de transporte, en caso que siga vinculado a la otra empresa, deber&aacute; tramitar la desvinculaci&oacute;n y/o la cancelaci&oacute;n del certificado de disponibilidad de capacidad transportadora para vincularse formalmente en vista de que hasta que no se expida la tarjeta de operaci&oacute;n no se oficializa el contrato, y por lo tanto no comenzara a generar efectos jur&iacute;dicos. Esta vinculaci&oacute;n en ning&uacute;n caso genera aceptaci&oacute;n del propietario de vehicul&oacute; como asociado, y en caso de ser aceptado como tal, deber&aacute; cumplir las obligaciones propias establecidas en los estatutos.</p>\r\n<p style=\"text-align: justify;\"><strong>TERCERA: DURACI&Oacute;N Y PR&Oacute;RROGAS DEL CONTRATO</strong>: El t&eacute;rmino de duraci&oacute;n de este contrato ser&aacute; de 2 a&ntilde;os, pero podr&aacute; ser inferior en el caso de disoluci&oacute;n y liquidaci&oacute;n de la empresa, efectuada de conformidad a los Estatutos o la Ley, caso en el cual no habr&aacute; lugar a indemnizaci&oacute;n. Adem&aacute;s, podr&aacute; darse por terminado en cualquier momento por cualquiera de las partes dando aviso a la otra con sesenta (60) d&iacute;as antes de la fecha en que se desea terminar, sin acatar el plazo previsto en este contrato, este contrato se perfecciona con su suscripci&oacute;n y la expedici&oacute;n de la tarjeta de operaci&oacute;n por parte del Ministerio de Transporte y hasta tanto no se expida la tarjeta no surtir&aacute; efectos legales.</p>\r\n<p style=\"text-align: justify;\"><strong>CUARTA</strong>. Acuerdan las partes que, de acuerdo a la legislaci&oacute;n sobre la materia, el propietario del veh&iacute;culo, debe mantener el veh&iacute;culo en &oacute;ptimas condiciones t&eacute;cnicas, mec&aacute;nicas, de aseo, presentaci&oacute;n y seguridad, so pena de que la empresa de transporte se abstenga leg&iacute;timamente de incluirlo en su plan de rodamiento, y pueda proceder a negar el despacho , para lo cual solamente se necesitara una manifestaci&oacute;n escrita a la direcci&oacute;n registrada en este contrato o entregada directamente al contratista, o anterior en vista de que la seguridad de los usuarios es primordial en la prestaci&oacute;n del servicio.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>QUINTA: DESIGNACI&Oacute;N Y CONTRATACI&Oacute;N DEL CONDUCTOR</strong>. - El conductor ser&aacute; designado por el propietario del veh&iacute;culo, salvo que este delegue esta responsabilidad en la empresa. Este deber&aacute; contar con los requisitos exigidos por la empresa y por las normas legales vigentes sobre la materia especialmente lo relacionado con los requisitos de la legislaci&oacute;n de tr&aacute;nsito, deber&aacute; contar con seguridad social como trabajador independiente, cumpliendo con los requisitos exigidos por la ley tanto de tr&aacute;nsito, como ley de transporte y ley de seguridad social para el desarrollo de esa labor y siempre y cuando &nbsp;las &nbsp;normas lo permitan, caso en el cual las obligaciones y responsabilidades de ese oficio, ser&aacute;n diferentes a las que se desprenden de este contrato, siendo su obligaci&oacute;n afiliarse &nbsp;al sistema de seguridad social, y allegar a la empresa cada mes, el soporte del pago respectivo a cada entidad a la cual se encuentra afiliado como trabajador independiente, o cancelar mensualmente a la empresa dicho valor. <strong>PARAGRAFO 2</strong>: El conductor deber&aacute; dar cumplimiento a los programas de capacitaci&oacute;n que establezca la empresa, as&iacute; como cumplir con los ex&aacute;menes m&eacute;dicos para ingresos los peri&oacute;dicos y de retiro, y la empresa queda en libertad de prescindir de sus servicios cuando se genera alguna causal de incumplimiento.</p>\r\n<p style=\"text-align: justify;\"><strong>SEXTA: SALARIO, PRESTACIONES Y DEMAS OBLIGACIONES CON EL CONDUCTOR.</strong>- EL CONTRATISTA como propietario del veh&iacute;culo asume el pago de los salarios, primas, cesant&iacute;as, bonificaciones, aportes a la Seguridad Social y dem&aacute;s prestaciones sociales y econ&oacute;micas del conductor, en la forma y t&eacute;rminos se&ntilde;alados en el Contrato de Trabajo <strong>PARAGRAFO 1:</strong> Queda acordado y aceptado por el propietario que, si el conductor no cumple con sus obligaciones, la EMPRESA queda facultada para suspender la prestaci&oacute;n del servicio y no expedir la tarjeta de operaci&oacute;n ni despacho y aplicar las sanciones a que haya lugar, incluida la desvinculaci&oacute;n unilateral del veh&iacute;culo dando por terminada esta relaci&oacute;n contractual. <strong>PARAGRAFO 2:</strong> La empresa no asume ninguna responsabilidad con relaci&oacute;n al pago de salarios y prestaciones sociales del conductor, pues es una responsabilidad exclusiva del propietario.</p>\r\n<p style=\"text-align: justify;\"><strong>SEPTIMA. VIGILANCIA Y SUPERVIGILANCIA</strong>. - LA EMPRESA vigilar&aacute; el cumplimiento de todos los requisitos legales, reglamentarios y los que se determinen en el r&eacute;gimen interno de la EMPRESA, relacionadas con la operaci&oacute;n del veh&iacute;culo y la prestaci&oacute;n del servicio, para lo cual tomar&aacute; las medidas que cada situaci&oacute;n requiera, bien sea respecto del veh&iacute;culo o en relaci&oacute;n con el conductor.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>OCTAVA. MANTENIMIENTO PREVENTIVO</strong>: El ministerio de Transporte expidi&oacute; la Resoluci&oacute;n 315 del 6 de febrero del 2013, por la cual se adoptan unas medidas para garantizar la seguridad en el transporte p&uacute;blico terrestre automotor y se dictan otras disposiciones. En dicha resoluci&oacute;n se establecen nuevas obligaciones para propietarios y empresas de transporte con relaci&oacute;n al mantenimiento de los veh&iacute;culos vinculados y espec&iacute;ficamente el art&iacute;culo 10 de la misma, ordena ajustar los contratos de administraci&oacute;n por afiliaci&oacute;n a esta nueva norma. As&iacute; las cosas, la empresa y el propietario acuerdan lo siguiente: <strong>1.</strong> Acatando lo dispuesto en esta resoluci&oacute;n, El propietario se compromete a mantener actualizada una ficha t&eacute;cnica Revisi&oacute;n y mantenimiento de cada veh&iacute;culo entregando a la empresa mensualmente las facturas de mantenimiento y reparaciones realizadas; dichas intervenciones deber&aacute;n ser realizadas en un centro especializado seg&uacute;n lo que defina la empresa de transporte. <strong>2.</strong> Para la validaci&oacute;n satisfactoria de las reparaciones correctivas y mantenimientos preventivas y de que trata el art&iacute;culo 2 de la resoluci&oacute;n 315, el propietario deber&aacute; remitir el veh&iacute;culo para unas inspecciones peri&oacute;dicas que se realizaran donde la empresa establezca que realizaran cada dos meses, en caso de que el veh&iacute;culo no apruebe estas revisiones no se le dar&aacute; despacho. <strong>3.</strong> Para efectos del establecer el periodo en que deber&aacute; realizarse el mantenimiento preventivo de los veh&iacute;culos, la empresa ha establecido que estos no podr&aacute;n realizarse con m&aacute;s de dos meses, pero se realizar&aacute;n cuando el veh&iacute;culo lo requiera anexando la factura correspondiente a la ficha t&eacute;cnica del automotor. <strong>4.</strong> El conductor en compa&ntilde;&iacute;a del propietario o de la auxiliar de ruta, este &uacute;ltimo actuando en representaci&oacute;n de la empresa, se encargara de realizar el protocolo de alistamiento de que trata el art&iacute;culo 3 de la resoluci&oacute;n 315 del 2013, verificando como m&iacute;nimo los siguientes aspectos: Fugas del motor, tensi&oacute;n correas, tapas, niveles de aceite del motor, transmisi&oacute;n, direcci&oacute;n, frenos, nivel de agua limpia brisas, aditivos de radiador, filtros h&uacute;medos y secos; bater&iacute;as: niveles de electrolito, ajuste de bordes y sulfataci&oacute;n; llantas: desgaste, presi&oacute;n de aire; equipo de carretera; botiqu&iacute;n. De dicho alistamiento se dejar&aacute; constancia en la ficha pre operacional, la cual ser&aacute; entregada por la empresa. <strong>5.</strong> El propietario y el conductor no podr&aacute; realizar reparaciones del veh&iacute;culo en las v&iacute;as, &nbsp;solo se except&uacute;a las reparaciones de emergencia o bajo absoluta imposibilidad f&iacute;sica de mover el veh&iacute;culo, con el fin de permitir el desplazamiento del automotor al centro especializado para las labores de reparaci&oacute;n; cuando el veh&iacute;culo haya sido intervenido en la v&iacute;a no podr&aacute; continuar con la prestaci&oacute;n del servicio de transporte debiendo a la empresa proveer oportunamente un veh&iacute;culo de reemplazo, salvo cuando el veh&iacute;culo se haya pinchado. <strong>6.</strong> Para viajes de m&aacute;s de 8 horas de recorrido entre el lugar de origen y el lugar de destino, el propietario deber&aacute; contar con un segundo conductor. <strong>7.</strong> Los propietarios se comprometen a asistir y a convocar a los conductores a las reuniones y capacitaciones que establezca la empresa para el cumplimiento de la resoluci&oacute;n 315 y dem&aacute;s disposiciones establecidas en la ley y las disposiciones administrativas de la empresa. <strong>8.</strong> Adem&aacute;s el propietario se compromete a llevar el veh&iacute;culo para la realizaci&oacute;n del mantenimiento preventivo en los talleres establecidos por la empresa. PARRAGRAFO: INSPECCI&Oacute;N DEL VEH&Iacute;CULO Y CONSECUENCIAS: LA EMPRESA podr&aacute; en cualquier tiempo inspeccionar el estado mec&aacute;nico y general del veh&iacute;culo, como garant&iacute;a de seguridad en el servicio y en caso de comprobarse fallas, tanto EL CONTRATISTA como el conductor del veh&iacute;culo atender&aacute;n de inmediato las ordenes de LA EMPRESA para corregirlas so pena de no despachar el veh&iacute;culo.</p>\r\n<p style=\"text-align: justify;\"><strong>NOVENA: COLORES, EMBLEMAS E IDENTIFICACION</strong>. - El contratista se compromete una vez firmado este contrato a marcar el veh&iacute;culo con los logos respectivos de la EMPRESA, e instalar en la forma que se le indique, el radio y los emblemas que identifican a la empresa y a retirarlos al momento de la desvinculaci&oacute;n del automotor.</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA: - RESPONSABILIDADES POR INFRACCIONES Y DA&Ntilde;OS</strong>.- Conforme lo estipulan las normas legales vigentes, ser&aacute;n a cargo del propietario el pago de las multas por infracciones a las normas de tr&aacute;nsito y transporte que se generen por su conducta o la conducta del conductor, por lo tanto en caso de que la empresa resulte involucrada podr&aacute; repetir en contra del contratista o propietario lo pagado, as&iacute; mismo EL CONTRATISTA se har&aacute; cargo de cualquier reclamaci&oacute;n de terceros que pueda presentarse y realizar los descaros correspondientes, colaborando a la empresa en la defensa de los intereses de los involucrados.</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA PRIMERA. - OBLIGACIONES DEL CONTRATISTA.</strong> - Son obligaciones especiales de EL CONTRATISTA las &nbsp;que a continuaci&oacute;n se convienen: -<strong>1.</strong>Utilizar la raz&oacute;n social y los distintivos de la Empresa, &uacute;nicamente para cumplimiento del objeto y fines del presente contrato lo cual incluye prestar el servicio p&uacute;blico de pasajeros en la modalidad de intermunicipal, con autorizaci&oacute;n de la empresa que se entender&aacute; otorgada con la orden de despacho, de no cumplir con esta obligaci&oacute;n la EMPRESA no responder&aacute; civilmente, ni ante las autoridades en forma solidaria como lo establece la ley &nbsp;por las indemnizaciones y perjuicios que causare. &nbsp;<strong>2.</strong>- Pagar oportunamente todos los impuestos y dem&aacute;s erogaciones oficiales, as&iacute; como combustible, repuestos, accesorios, mano de obra y dem&aacute;s gastos de operaci&oacute;n y mantenimiento del veh&iacute;culo y las relacionadas con las obligaciones laborales a que se refieren las cl&aacute;usulas cuarta y quinta de este contrato. <strong>3.</strong>- Contratar y mantener vigente durante el tiempo de administraci&oacute;n por afiliaci&oacute;n las p&oacute;lizas de seguros, de las cuales la Empresa ser&aacute; la depositaria; as&iacute; como afiliarse a los dem&aacute;s fondos que la ley exige, a fin de efectuar los pagos e indemnizaciones a que hubiere lugar y que correspondieren al Contratista. -Dicha p&oacute;liza deber&aacute; cubrir los riesgos y los montos que las leyes y Decretos vigentes establezcan y los que dispusiere la Empresa. <strong>4.</strong>- Obtener de las autoridades de Tr&aacute;nsito y/o de Transporte respectivas y de la Empresa, autorizaci&oacute;n previa antes de prestaci&oacute;n de servicios. <strong>5.</strong>- No enajenar, ni dar en prenda el veh&iacute;culo durante el tiempo de vigencia de este contrato, sin la previa autorizaci&oacute;n de la Empresa. - <strong>6.</strong>- &nbsp;Acatar y cumplir las disposiciones emanadas de la Asamblea General, as&iacute; como las del Gerente. &nbsp;<strong>7.</strong>- Abstenerse de despachar o disponer que el veh&iacute;culo preste servicio p&uacute;blico de transporte, en las siguientes circunstancias: A) Sin tarjeta de operaci&oacute;n, o revisi&oacute;n t&eacute;cnico mec&aacute;nica &nbsp; vigente; B) Sin ser despachado por la empresa, y sin tenerlo debidamente diligenciado. C) Sin los seguros obligatorio o de responsabilidad civil vigentes y/o aportes al Fondo de Responsabilidad Civil; D) Sin licencia de conducci&oacute;n del conductor y vigente. E) Con el veh&iacute;culo en mal estado. &nbsp;<strong>8.</strong>- Instruir permanentemente al conductor para que cumpla las normas de Tr&aacute;nsito y de Transporte, especialmente las relacionadas con la obligaci&oacute;n de llevar acompa&ntilde;ante cuando transporte ni&ntilde;os, no llevar pasajeros o personas de pie. -<strong>9.</strong> Cumplir y hacer que se cumplan por el conductor, las disposiciones contenidas en la ley 105 de 1993, ley 336 de 1996, decreto 1079 del 2015 y dem&aacute;s normas reglamentarias y concordantes que con posterioridad se dicten, o aquellas que las modifiquen o adicionen. <strong>10.</strong>- Presentar oportunamente y dentro de los t&eacute;rminos se&ntilde;alados por la EMPRESA los documentos exigidos para tramitar la renovaci&oacute;n de la tarjeta de operaci&oacute;n y pagar oportunamente el valor correspondiente exigido por la autoridad competente para dicho tr&aacute;mite. <strong>11.</strong>- Entregar o responder a la Gerencia los informes que le sean solicitados dentro de los t&eacute;rminos que se le se&ntilde;alen. <strong>12.</strong>- Informar a la Gerencia por escrito cualquier cambio de direcci&oacute;n de su residencia, dentro de los ocho d&iacute;as siguientes de haberse cumplido el traslado. <strong>13.</strong> Cumplir y hacer cumplir al conductor, los servicios oficialmente autorizados, modificados o que se autoricen, as&iacute; como los horarios, turnos y el Reglamento interno. <strong>14.</strong> pagar oportunamente los deducibles y dem&aacute;s rublos relacionados con los da&ntilde;os ocasionados por el veh&iacute;culo a terceros o a los usuarios del servicio, siempre y cuando exista responsabilidad civil presunta. <strong>15. </strong>Cumplir y hacer cumplir Oportunamente al conductor el programa de revisi&oacute;n y mantenimiento preventivo dise&ntilde;ado por la empresa y diligenciar lo correspondiente en la plataforma virtual establecida para tal fin. <strong>16.</strong> Verificar el estado mec&aacute;nico del veh&iacute;culo antes de prestar el servicio. <strong>17.</strong> Renovar oportunamente los seguros de responsabilidad civil contractual y extracontractual, cancelando con ocho d&iacute;as de anticipaci&oacute;n a su vencimiento el valor total de la prima en las oficinas de la empresa o de manera mensual en caso de que se puedan financiar. <strong>18.</strong> Habilitar el canal de comunicaci&oacute;n utilizando los medios electr&oacute;nicos (informando a la empresa la direcci&oacute;n del correo electr&oacute;nico vigente). <strong>19.</strong> Asistir a todas las reuniones dentro de los programas de capacitaci&oacute;n permanente establecido por la EMPRESA ya sea de manera personal o virtual. <strong>20.</strong> Comprar los dispositivos requeridos para el control de flota que disponga la empresa y pagar el servicio mensual del rastreo satelital donde disponga la empresa de transporte, con el fin de vigilar y constatar el cumplimiento de sus obligaciones. 21. Diligenciar la plataforma tecnol&oacute;gica que la empresa contarte para el control de los requisitos legales tanto del conductor como del veh&iacute;culo. &nbsp;<strong>22.</strong> El propietario pagar&aacute; en calidad de cuota de sostenimiento de manera mensual dentro de los 5 primeros d&iacute;as de cada mes la suma de $158.000 (ciento cincuenta y ocho mil pesos) El incremento de este valor ser&aacute; el IPC as 2 puntos porcentuales por a&ntilde;o.&nbsp;<strong>PARAGRAFO 1:</strong> Todas las obligaciones econ&oacute;micas contra&iacute;das por el propietario podr&aacute;n ser descontadas por la empresa de los servicios prestados. <strong>PARAGRAFO 2:</strong> En caso de venta del veh&iacute;culo, la empresa se reserva el derecho de aceptar al nuevo propietario, en todo caso el nuevo propietario pagara a t&iacute;tulo de cesio de contrato la suma de un salario m&iacute;nimo mensual legal vigente.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA SEGUNDA: OBLIGACIONES DE LA EMPRESA</strong>.- Son las siguientes: 1.- Velar porque el veh&iacute;culo una vez formalizada la administraci&oacute;n por afiliaci&oacute;n coloque en la carrocer&iacute;a los distintivos, n&uacute;mero de orden, raz&oacute;n social y pagina web de la empresa.- 2.- Vigilar que el conductor y el propietario que conduce su propio veh&iacute;culo se encuentre afiliado al Sistema de Seguridad Social.- 3.- Desarrollar el programa de medicina preventiva para el conductor.- 4.- Suministrar oportunamente la tarjeta de operaci&oacute;n siempre y cuando el contratista entregue los documentos oportunamente a la empresa y pague el valor correspondiente seg&uacute;n las autoridades. - 5.- Desarrollar programas de capacitaci&oacute;n para los operadores del equipo y a los Cooperados- 6.- Vigilar que el veh&iacute;culo cuente con las condiciones de seguridad y comodidad reglamentados por el Ministerio de Transporte. - 7.- Desarrollar el programa de revisi&oacute;n y mantenimiento preventivo del equipo. - 8.- Vigilar que el veh&iacute;culo preste el servicio con la tarjeta de operaci&oacute;n vigente. 9.- Vigilar y constatar que el conductor del veh&iacute;culo cuente con licencia de conducci&oacute;n vigente y apropiada. 10.- Llevar y mantener en el archivo una ficha t&eacute;cnica del veh&iacute;culo. - 11.- Entregar al propietario del equipo la ficha t&eacute;cnica una vez efectuada la vinculaci&oacute;n por la autoridad competente. - 12.- Expedir el paz y salvo sin costo alguno, siempre y cuando no tengan deudas originadas con ocasi&oacute;n de las obligaciones contra&iacute;das en el presente contrato. -</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA TERCERA: CAUSALES DE TERMINACION DE CONTRATO Y DESVINCULACION DEL VEHICULO</strong>.- Sin perjuicio de las disposiciones contenidas en este contrato o las de orden legal, LA EMPRESA proceder&aacute; &nbsp;a dar por terminado el contrato en cualquier momento dando aviso al propietario con 60 d&iacute;as de anticipaci&oacute;n a la fecha en que se terminara el contrato sin que este supeditado a la terminaci&oacute;n del mismo y remitir&aacute; carta al Ministerio de Transporte en la forma prevista en las disposiciones de transporte, adem&aacute;s terminara el contrato de manera inmediata cuando acontezca una de las siguientes causales: 1) Cuando el propietario por intermedio del conductor o personalmente preste servicio de transporte sin tarjeta de &nbsp;operaci&oacute;n, o sin ser despachado por la empresa o &nbsp;con el veh&iacute;culo en mal estado. 2) Cuando el propietario o su conductor se abstenga de forma reiterada y sin ninguna justificaci&oacute;n, de cumplir las determinaciones emanadas de la Asamblea General, o de las &oacute;rdenes que imparta la Gerencia. - 3) Por decisi&oacute;n judicial u orden administrativa de las autoridades de Tr&aacute;nsito y Transporte. - 4) Por incumplimiento total o parcial o cumplimiento tard&iacute;o o defectuoso de las obligaciones expresadas en este contrato. 5) Cuando el CONTRATISTA dirija, de &oacute;rdenes o instrucciones, o patrocine al conductor para cometer cualquier tipo de il&iacute;citos o el incumplimiento de las normas de Tr&aacute;nsito y Transporte. 6)- No cancelar oportunamente los valores acordados en este contrato. 7). cuando en el t&eacute;rmino de (8) Ocho d&iacute;as no sean corregidos los da&ntilde;os mec&aacute;nicos que sean detectados al inspeccionar el veh&iacute;culo. &nbsp;<strong>PARAGRAFO 1:</strong>- Tambi&eacute;n podr&aacute; cancelarse este contrato por acuerdo mutuo entre las partes o por muerte del propietario si los herederos o causahabientes no desean continuarlo, caso este en el cual no tendr&aacute;n derecho a reclamar perjuicios o indemnizaci&oacute;n &nbsp;a la Empresa, cuando esta cancele el contrato por incumplimiento de las obligaciones adquiridas por el Contratista o por cualquiera de las causales de terminaci&oacute;n aqu&iacute; previstas.- Una vez adjudicado dentro de la sucesi&oacute;n el veh&iacute;culo, el heredero favorecido deber&aacute; tramitar el traspaso correspondiente y manifestar por escrito ante la Gerencia si desea continuar la afiliaci&oacute;n del veh&iacute;culo, caso en el cual deber&aacute; firmarse nuevo contrato entre las partes o prorrogarse el existente mediante cl&aacute;usula adicional. <strong>PARAGRAFO 2:</strong>.- El CONTRATISTA se compromete en caso de terminaci&oacute;n del contrato a presentarse a la empresa en el momento que se le cite, para firmar la comunicaci&oacute;n que debe enviarse al Ministerio de Transporte &nbsp;para el retiro del veh&iacute;culo de la capacidad transportadora y entregar el original de la tarjeta de operaci&oacute;n, y si no lo hiciere responder&aacute; en los t&eacute;rminos de este contrato y judicialmente si a ello hubiere lugar por los perjuicios causados a LA EMPRESA. y a terceros.- <strong>PARAGRAFO 3</strong>: .- Mientras el Ministerio de Transporte expide la autorizaci&oacute;n para la desvinculaci&oacute;n, la EMPRESA permitir&aacute; que el veh&iacute;culo contin&uacute;e prestando el servicio si el CONTRATISTA as&iacute; lo solicita por escrito, comprometi&eacute;ndose a seguir cumpliendo las obligaciones contenidas en este contrato y a observar los reglamentos de la EMPRESA. PARAGARFO 4: En caso de &eacute;l, propietario de manera unilateral decida el cambio de empresa y desvinculaci&oacute;n, el propietario deber&aacute; estar a paz y salvo y pagar la suma de ----------------- y el valor de las p&oacute;lizas hasta tanto se genere efectivamente la desvinculaci&oacute;n del veh&iacute;culo.</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA CUARTA: PROHIBICIONES AL CONTRATISTA</strong>.- Le queda prohibido al CONTRATISTA lo siguiente: 1.- &nbsp;Ordenar &nbsp;prestar &nbsp;el &nbsp;servicio &nbsp;cuando &nbsp;el &nbsp;veh&iacute;culo no cumpla con las condiciones de seguridad y comodidad exigidas por el C&oacute;digo de Tr&aacute;nsito, &nbsp;la ley y los reglamentos de transporte.- 2.- Permitir que se preste el servicio de transporte sin autorizaci&oacute;n de la empresa, sin tener tarjeta de operaci&oacute;n vigente, sin &nbsp;seguro &nbsp;obligatorio y &nbsp;de &nbsp;responsabilidad &nbsp;civil contractual y extracontractual o estando vencidos- 3.- Entregar el veh&iacute;culo para su conducci&oacute;n y para la prestaci&oacute;n del servicio p&uacute;blico de transporte a un conductor no autorizado por la Empresa.- 4.- Autorizar o permitir que el conductor transporte en el veh&iacute;culo sustancias inflamables o estupefacientes, o de contrabando o de procedencia il&iacute;cita.-5.- Ordenar o Permitir que el conductor cobre tarifas &nbsp;distintas a &nbsp;las contratadas.- 5.- <strong>DERECHOS DE AUTOR:</strong> Se proh&iacute;be expresamente la reproducci&oacute;n de obras musicales y audiovisuales con destino a los pasajeros dentro de los veh&iacute;culos de transporte terrestre automotor intermunicipal, ello con el fin de evitar que se presuma de hecho, por parte de la &nbsp;Organizaci&oacute;n Sayco Acinpro (OSA), que al interior de los veh&iacute;culos se est&aacute; realizando el fen&oacute;meno de la comunicaci&oacute;n p&uacute;blica, plasmada en el art&iacute;culo 8 literal &Ntilde;. de la ley 23 de 1982&rdquo;.</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA QUINTA: PROHIBICIONES A LA EMPRESA</strong>. - 1.- Exigir suma alguna por desvinculaci&oacute;n o por el tr&aacute;mite de expedici&oacute;n de paz y salvo. - 2.- Hacer cobros diferentes a los previstos en este contrato. - 3.- Retener la tarjeta de operaci&oacute;n por obligaciones contractuales. - 4.- Exigir al propietario del veh&iacute;culo comprar acciones de la empresa.</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA SEXTA: RESPONSABILIDADES Y OBLIGACIONES SUBSIDIARIAS</strong>. - EL CONTRATISTA ser&aacute; responsable ante las autoridades y ante terceros, de las sanciones, perjuicios e indemnizaciones que se causaren por el incumplimiento total o parcial o ejecuci&oacute;n defectuosa o tard&iacute;a de cualquiera de las obligaciones o prohibiciones aqu&iacute; estipuladas, cuando por acci&oacute;n u omisi&oacute;n propia o del conductor del veh&iacute;culo, sea el causante de los mismos. En el evento que LA EMPRESA en forma solidaria y subsidiaria tenga que responder por las situaciones previstas en esta cl&aacute;usula y otras similares no previstas en este Contrato, aquella podr&aacute; repetir lo pagado contra el Contratista por v&iacute;a judicial, en caso de no darse la conciliaci&oacute;n o arreglo extrajudicial. &nbsp;La empresa tambi&eacute;n podr&aacute; repetir en contra del propietario lo pagado ante la Superintendencia de Puertos y Transporte por sanciones administrativas generadas cuando el propietario del veh&iacute;culo transite sin los documentos de transporte o sin acompa&ntilde;ante o genere con su conducta la violaci&oacute;n a las normas de transporte. El propietario autoriza expresamente a la empresa a repetir lo pagado por sanciones administrativas anteriores y desde que el veh&iacute;culo se vincul&oacute; a la empresa.</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA SEPTIMA: CONSECUENCIAS POR ACTUACIONES UNILATERALES</strong>. - Si en forma unilateral EL CONTRATISTA retira el veh&iacute;culo del servicio o no presta el servicio sin justa causa o sin previo aviso y como consecuencia de tal actuaci&oacute;n la Empresa es investigada y sancionada, el contratista deber&aacute; cancelar los honorarios del abogado para ejercer la defensa, pagar la multa que se impusiere y los perjuicios que con tal acci&oacute;n se causen. -&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA OCTAVA: OBLIGACIONES DEL CONTRATISTA DESPUES DE LA DESVINCULACION DEL VEHICULO</strong>.- Una vez se produzca la desvinculaci&oacute;n definitiva del &nbsp;veh&iacute;culo &nbsp;por &nbsp;la &nbsp;autorizaci&oacute;n &nbsp;otorgada por la autoridad competente de Transporte, EL CONTRATISTA se obliga para con LA EMPRESA a firmar el Acta de Terminaci&oacute;n del contrato y a borrar o cambiar la raz&oacute;n &nbsp;social, as&iacute; como los distintivos o emblemas de la Empresa en el t&eacute;rmino no mayor de cinco ( 5 ) d&iacute;as, haciendo llegar la comprobaci&oacute;n respectiva a la Gerencia de la Empresa, so pena de incurrir en responsabilidad y &nbsp;consiguiente &nbsp;pago de los perjuicios e indemnizaciones que con tal actitud se causen. Adem&aacute;s, deber&aacute; cancelar los valores pendientes por p&oacute;lizas de seguros hasta el momento de la cancelaci&oacute;n de la tarjeta de operaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA NOVENA: DERECHOS DEL CONTRATISTA</strong>.- Son los siguientes: 1.- A que se tramite y entregue oportunamente la tarjeta de operaci&oacute;n, si ha suministrado con la oportunidad debida los requisitos exigidos.2 &nbsp;Expedir un extracto que contenga en forma discriminada los rubros y montos, cobrados y pagados, por cada concepto.-&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA: &nbsp;DERECHOS &nbsp;DE &nbsp;LA &nbsp;EMPRESA</strong>.- &nbsp;Son &nbsp; los siguientes: 1.- A qu&eacute; se le entregue oportunamente la informaci&oacute;n &nbsp;solicitada &nbsp;al &nbsp;propietario &nbsp;del &nbsp;veh&iacute;culo- 2.- A hacer uso de la aplicaci&oacute;n de las sanciones previstas por el incumplimiento de las obligaciones y convenios determinados en este contrato.- 3.- A qu&eacute; se le entreguen oportuna y previamente los documentos y requisitos exigidos en la ley, as&iacute; como los pagos respectivos, para el tr&aacute;mite de solicitud o renovaci&oacute;n de la tarjeta de operaci&oacute;n y de las p&oacute;lizas de los seguros.- 4.- A que se &nbsp;cumplan por el &nbsp;CONTRATISTA los pagos dentro de los t&eacute;rminos y de las fechas acordados en las cl&aacute;usulas de este contrato.</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA PRIMERA: NORMAS QUE FORMAN PARTE DEL CONTRATO</strong>.- Forman parte del presente contrato para todos los efectos no previstos en estas cl&aacute;usulas, las normas expedidas o que se expidan por el Congreso Nacional, El Gobierno Nacional, El Concejo Municipal, La Autoridad competente del &Aacute;rea Metropolitana, el Ministerio de Transporte, tanto en el campo &nbsp;administrativo, &nbsp;como &nbsp;en &nbsp; materia &nbsp;penal, &nbsp; civil, &nbsp; laboral &nbsp;y &nbsp; comercial, como de Tr&aacute;nsito y Transporte.- PARAGRAFO: Las decisiones adoptadas por la Asamblea General y/o El Consejo de Administraci&oacute;n seg&uacute;n el caso, constituyen parte integrante de este contrato y por lo tanto EL CONTRATISTA se obliga a cumplirlas, cuando las mismas impliquen revocaci&oacute;n, reforma, adici&oacute;n o aclaraci&oacute;n de cualquiera de las cl&aacute;usulas contenidas en el presente contrato.</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA SEGUNDA: INTERESES MORATORIOS Y RENUNCIA A REQUERIMIENTOS</strong>. - Cualquiera de las obligaciones aqu&iacute; contra&iacute;das, causar&aacute;n intereses moratorios conforme a la certificaci&oacute;n expedida por la Superintendencia Bancaria o el Banco de la Rep&uacute;blica. Todas y cada una de las obligaciones contenidas en el presente contrato o las que se llegaren a establecer y &nbsp;las &nbsp;que se &nbsp;impongan &nbsp;conforme a las cl&aacute;usulas y estipulaciones contenidas en este Contrato, se cumplir&aacute;n sin necesidad de requerimientos personales, extrajudiciales, judiciales o legales a los cuales renunciamos expresamente.- PARAGRAFO: No obstante lo anterior, si la EMPRESA decide mediante comunicaciones de prensa o radio, comunicar, notificar o hacer conocer sus decisiones a EL CONTRATISTA, estas constituir&aacute;n medio v&aacute;lido de informaci&oacute;n para todos los efectos legales.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA TERCERA: PAGAR&Eacute; A CARGO DEL CONTRATISTA</strong>.- &nbsp;EL CONTRATISTA expresamente manifiesta, que este contrato tiene la caracter&iacute;stica del T&Iacute;TULO VALOR PAGAR&Eacute;, en la &nbsp;forma como lo contempla el C&oacute;digo de Comercio, y por lo tanto en forma incondicional pagar&aacute; a la orden de la &hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;., dentro de los treinta d&iacute;as siguientes al requerimiento escrito de LA EMPRESA o comunicaci&oacute;n de prensa o radio, las sumas que resulte a deber durante la vigencia del presente contrato por concepto de: cuotas atrasadas por los pagos u obligaciones aqu&iacute; contra&iacute;das, valores adeudados por concepto de provisiones y suministros de la empresa, bien sea combustible, llantas, repuestos, insumos para el automotor, GPS, multas y comparendos antes la superintendencia de puertos y transportes; pr&eacute;stamos; valores cubiertos por &nbsp;la empresa y relacionados con la &nbsp;responsabilidad civil contractual o extracontractual derivados de la prestaci&oacute;n del servicio p&uacute;blico de transporte; valores cubiertos por la &nbsp;empresa por &nbsp;raz&oacute;n de infracciones al C&oacute;digo Nacional de Tr&aacute;nsito o al Estatuto de Transporte en que haya incurrido el Contratista o el conductor de su veh&iacute;culo con ocasi&oacute;n de la prestaci&oacute;n de servicio p&uacute;blico de transporte; valores causados por cuotas ordinarias o extraordinarias con cargo a los propietarios de veh&iacute;culos ordenados por la Asamblea General de la Empresa; PARAGRAFO : La EMPRESA se abstendr&aacute; de expedir el paz y salvo cuando existan obligaciones no canceladas por el CONTRATISTA, hasta tanto este t&iacute;tulo valor se haya hecho efectivo.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA CUARTA: DE LA RESPONSABILIDAD CIVIL CON OCASI&Oacute;N DE HECHOS RESULTANTES DE LA PRESTACI&Oacute;N DEL SERVICIO.</strong>- Las partes libremente acuerdan, que en caso de condenas por la responsabilidad civil contractual o extracontractual derivadas de la prestaci&oacute;n del servicio p&uacute;blico de transporte con &nbsp;el &nbsp;automotor &nbsp;identificado &nbsp;en &nbsp;la &nbsp;cl&aacute;usula &nbsp;primera, LA EMPRESA &nbsp;solo se &nbsp;obliga a responder hasta el monto de las coberturas de las p&oacute;lizas de Responsabilidad Civil contractual, extracontractual RCC RCE en exceso .</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA QUINTA:</strong> EL CONTRATISTA manifiesta que en ejercicio de mi Derecho a la libertad y &nbsp;autodeterminaci&oacute;n inform&aacute;tica autorizo a &nbsp;la empresa &nbsp;o a la entidad que mi acreedor delegue para representarlo o a su cesonario , endosatario o quien ostente en el futuro la calidad de acreedor , previo a la relaci&oacute;n contractual y de manera irrevocable , escrita, expresa, concreta, suficiente, voluntaria e informada, con la finalidad de que la informaci&oacute;n comercial, crediticia, financiera y de servicio de la cual soy titular, referido al nacimiento , ejecuci&oacute;n extinci&oacute;n de obligaciones &nbsp;dinerarias(independiente de la naturaleza del contrato que les de origen)a mi comportamiento e historial crediticio, incluida informaci&oacute;n positiva y negativa de mis h&aacute;bitos de pago y aquella que se refiere a la informaci&oacute;n personal necesaria para el estudio, an&aacute;lisis y eventual otorgamiento de cr&eacute;dito o celebraci&oacute;n de un contrato, sea en general administrada y en especial ,capturada, tratada, procesada, operada verificada y transmitida, transferida, usada opuesta en circulaci&oacute;n y consultada por terceras personas autorizadas expresamente para que la informaci&oacute;n sea concedida &nbsp;y reportada en la base de datos de DATACREDITO operada por DATACREDITO o cualquier otro. De la misma manera autorizo a DATACREDITO, como operador de la base de datos de PROCREDITO que tiene la finalidad estrictamente comercial, financiera, crediticia y de servicios, para que procese, opere y administre la informaci&oacute;n de la cual soy titular y para que la misma sea transferida y transmitida a usuarios, lo mismo que a otros operadores nacionales o extranjeros que tengan la misma finalidad que comprenda la que tiene DATACREDITO. Certifico que los datos personales suministrados por m&iacute;, son veraces, completos, exactos, actualizados, reales y comprobados. Por lo tanto, cualquier error en la informaci&oacute;n suministrada ser&aacute; de mi &uacute;nica y exclusiva responsabilidad. Lo que exonera a DATACREDITO de su responsabilidad ante las autoridades judiciales y /o administrativas. Declaro que he le&iacute;do y comprendido a cabalidad el contenido de la presente autorizaci&oacute;n y acepto la finalidad en ella descrita y las consecuencias que se derivan de ella.</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMO SEXTA: VALOR Y PAGO DE PERJUICIOS</strong>. - El incumplimiento de las obligaciones contenidas en el presente contrato por parte del CONTRATISTA, dar&aacute; derecho a la EMPRESA para exigir sin ning&uacute;n requerimiento, el pago de perjuicios que se tasan en el equivalente a cuatro (4) salarios m&iacute;nimos mensuales vigentes, este mismo valor se cobrara en el caso de terminaci&oacute;n del contrato de vinculaci&oacute;n de forma anticipada, en calidad de clausula penal.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA SEPTIMA: INTERPRETACI&Oacute;N DE SITUACIONES</strong>. - Si se admitieren, toleraren o presentaren hechos o situaciones que difieran de lo convenido en el presente contrato, no por eso se entender&aacute; revocaci&oacute;n, modificaci&oacute;n o novaci&oacute;n alguna del mismo, salvo acuerdo expreso por escrito de las partes.</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA OCTAVA: MEDIDAS DISCIPLINARIAS INTERNAS</strong>. - Con el prop&oacute;sito de prestar un mejor servicio en el transporte p&uacute;blico individual y sin perjuicio de lo dispuesto en el texto del presente contrato, LA EMPRESA podr&aacute; adoptar medidas disciplinarias de acuerdo a lo previsto en los Reglamentos de la Empresa y las normas sobre la materia.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA NOVENA: SOLUCI&Oacute;N DE CONFLICTOS</strong>. &ndash; En caso de que exista alg&uacute;n conflicto o diferencia con relaci&oacute;n a la relaci&oacute;n contractual, se procurara que primero se cite una reuni&oacute;n entre el propietario y la Gerencia para tratar de solucionar el conflicto; en caso de que no sea posible legar a un acuerdo, las partes tienen la libertad de acudir a la justicia ordinaria.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>TRIGESIMA: SUSTITUCI&Oacute;N Y MANIFESTACIONES</strong>. - El presente contrato sustituye a cualquier otro (s) anterior (es), rigi&eacute;ndose las obligaciones bilaterales en adelante por este contrato. El contratista manifiesta que conoce y entiende, por lo cual adem&aacute;s acepta el contenido de cada una de las cl&aacute;usulas redactadas en el presente contrato, que lo ley&oacute; y le da su aprobaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">En constancia se firma en sendos originales como aparece, en el municipio de Oca&ntilde;a que ser&aacute; el domicilio para todos los efectos legales, a los fechaContrato.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', '2024-02-01 21:00:53', '2024-02-01 21:00:53');
INSERT INTO `informaciongeneralpdf` (`ingpdfid`, `ingpdfnombre`, `ingpdftitulo`, `ingpdfcontenido`, `created_at`, `updated_at`) VALUES
(6, 'contratoModalidadEspecial', 'Servicio Público de Pasajeros en la Modalidad de Transporte Especial', '<p style=\"text-align: justify;\">Entre. La EMPRESA la Cooperativa de Transportadores Hacaritama, COOTRANSHACARITAMA Sociedad Legalmente constituida, e inscrita en el Registro Mercantil, quien para los efectos del presente contrato se denomina LA EMPRESA; debidamente representada por el se&ntilde;or nombreGerente, identificado con la c&eacute;dula de ciudadan&iacute;a No. documentoGerente expedida en ciudadExpDocumentoGerente, en adelante se llama LA EMPRESA y el mencionado en la parte inicial del contrato quien obra como propietario del automotor descrito inscrito en matricula, y quien en adelante se llamar&aacute; EL CONTRATISTA, por la otra parte; hacemos constar que hemos celebrado el contrato de administraci&oacute;n por afiliaci&oacute;n por la modalidad de afiliaci&oacute;n, el cual se rige por las siguientes cl&aacute;usulas:&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>PRIMERA: OBJETO</strong>. El presente contrato tiene por objeto la vinculaci&oacute;n del veh&iacute;culo identificado en esta cl&aacute;usula primera, al parque automotor de <strong>LA EMPRESA</strong>, en los t&eacute;rminos del Decreto1079 de 2015, art&iacute;culo 983 del C&oacute;digo de Comercio y dem&aacute;s normas que lo regulan, de forma tal que el propietario vincula el veh&iacute;culo para la prestaci&oacute;n del servicio p&uacute;blico de transporte especial en los t&eacute;rminos establecidos en las normas legales vigentes y lo establecido en este contrato. <strong>Par&aacute;grafo 1: El PROPIETARIO</strong> manifiesta que cuenta con todos los documentos en regla de acuerdo a la Ley para con &eacute;ste poder dar cumplimiento al objeto del presente contrato, pero adem&aacute;s se obliga a cumplir con todas las exigencias que le requiera <strong>LA EMPRESA</strong> en cumplimiento de la normatividad aplicable al tipo de contrato, la prestaci&oacute;n del servicio p&uacute;blico de transporte especial y la normatividad laboral aplicable especialmente la referida en el SG-SST y PESV. <strong>Par&aacute;grafo 2:</strong> El propietario se podr&aacute; reservar la administraci&oacute;n del veh&iacute;culo, con la facultad de utilizarlo en los t&eacute;rminos del presente contrato y designar al personal que lo opera, directamente y sin alguna responsabilidad de <strong>LA EMPRESA</strong>, por consiguiente, asumir&aacute; la responsabilidad que se derive de la administraci&oacute;n del veh&iacute;culo. <strong>Par&aacute;grafo 3:</strong> Sera causal de cancelaci&oacute;n del presente contrato por parte de <strong>LA EMPRESA</strong>, la caducidad de cualquiera de los documentos exigidos para el libre tr&aacute;nsito y operaci&oacute;n del Veh&iacute;culo y los exigidos por la autoridad competente. <strong>Par&aacute;grafo 4:</strong> El veh&iacute;culo objeto de la vinculaci&oacute;n, tiene las siguientes caracter&iacute;sticas: Uso: Servicio p&uacute;blico de transporte especial. <strong>Par&aacute;grafo 5:</strong> El propietario bajo la gravedad del juramento, dentro del presente acuerdo, que posee este veh&iacute;culo por la v&iacute;a legal, es decir que su posesi&oacute;n es l&iacute;cita, quieta y pac&iacute;fica y contra esta no procede ninguna acci&oacute;n legal que la restrinja, de no ser as&iacute; y de no manifestar lo contrario, presumiendo la buena fe, saldr&aacute; en defensa de los intereses de la Empresa. <strong>Par&aacute;grafo 6:</strong> Acuerdan las partes que, de acuerdo a la legislaci&oacute;n sobre la materia, el propietario del veh&iacute;culo, debe mantener el veh&iacute;culo en &oacute;ptimas condiciones t&eacute;cnicas, mec&aacute;nicas, de aseo, presentaci&oacute;n y seguridad, so pena de que la empresa de transporte se abstenga leg&iacute;timamente de incluirlo en su plan de rodamiento, y pueda proceder a negar los extractos de contrato y la suscrici&oacute;n de convenios de colaboraci&oacute;n empresarial, para lo cual solamente se necesitara una manifestaci&oacute;n escrita a la direcci&oacute;n registrada en este contrato o entregada directamente al contratista, o anterior en vista de que la seguridad de los usuarios es primordial en la prestaci&oacute;n del servicio.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>SEGUNDA: ALCANCE.</strong> LA EMPRESA, emplear&aacute; el automotor descrito para prestar el servicio p&uacute;blico de transporte especial, en cumplimiento de los contratos de servicio de transporte que celebre con terceros en las condiciones contractuales que se establezca y que acepta el&nbsp;<strong>PROPIETARIO</strong>.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>TERCERA: DURACI&Oacute;N Y PR&Oacute;RROGAS DEL CONTRATO:</strong> El t&eacute;rmino de duraci&oacute;n de este contrato ser&aacute; de 2 a&ntilde;os, pero podr&aacute; ser inferior en el caso de disoluci&oacute;n y liquidaci&oacute;n de la empresa, efectuada de conformidad a los Estatutos o la Ley, caso en el cual no habr&aacute; lugar a indemnizaci&oacute;n. Adem&aacute;s, podr&aacute; darse por terminado en cualquier momento por cualquiera de las partes dando aviso a la otra con sesenta (60) d&iacute;as antes de la fecha en que se desea terminar, sin acatar el plazo previsto en este contrato, este contrato se perfecciona con su suscripci&oacute;n y la expedici&oacute;n de la tarjeta de operaci&oacute;n por parte del Ministerio de Transporte y hasta tanto no se expida la tarjeta no surtir&aacute; efectos legales. Nota: este contrato no genera en ning&uacute;n caso prorrogas autom&aacute;ticas.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>CUARTA: OBLIGACIONES PECUNIARIAS:</strong> EL CONTRATISTA se obliga a pagar mensualmente a LA EMPRESA, las sumas que se detallan a continuaci&oacute;n:</p>\r\n<table style=\"border-collapse: collapse; width: 99.9611%; height: 156.75px;\" border=\"1\"><colgroup><col style=\"width: 33.2988%;\"><col style=\"width: 33.2988%;\"><col style=\"width: 33.2988%;\"></colgroup>\r\n<tbody>\r\n<tr>\r\n<td><strong>CONCEPTO</strong></td>\r\n<td><strong>VALOR</strong></td>\r\n<td><strong>PERIODO</strong></td>\r\n</tr>\r\n<tr>\r\n<td>Cuota de Sostenimiento de Administraci&oacute;n</td>\r\n<td>$cuotaSostenimientoAdmon</td>\r\n<td>Mensual, anticipado cinco (5) primeros d&iacute;as de cada mes.</td>\r\n</tr>\r\n<tr>\r\n<td>Descuento Por Pago anual anticipado</td>\r\n<td>descuentoPagoAnualAnticipado% Valor del a&ntilde;o a cancelar de Administraci&oacute;n.</td>\r\n<td>Anual descuento 5%</td>\r\n</tr>\r\n<tr>\r\n<td>Recargo de Mora Cuota de Sostenimiento de Administraci&oacute;n.</td>\r\n<td>descuentoPagoAnualAnticipado% de la Cuota de Sostenimiento de Administraci&oacute;n mensual.</td>\r\n<td>Mensual pasado el d&iacute;a (5) de cada mes.&nbsp;</td>\r\n</tr>\r\n<tr>\r\n<td>Control de Transporte-Seguimiento de Infracciones-GPS.</td>\r\n<td>&nbsp;</td>\r\n<td>Mensual, anticipado cinco (5) primeros d&iacute;as de cada mes.</td>\r\n</tr>\r\n<tr>\r\n<td>Mensajer&iacute;a por tramite tarjeta de Operaci&oacute;n &nbsp;</td>\r\n<td>&nbsp;</td>\r\n<td>Seg&uacute;n Requerimiento.</td>\r\n</tr>\r\n<tr>\r\n<td>Certificaciones</td>\r\n<td>&nbsp;</td>\r\n<td>A solicitud del Interesado.</td>\r\n</tr>\r\n<tr>\r\n<td>Envi&oacute; Extractos de Contrato F&iacute;sico por Mensajer&iacute;a.</td>\r\n<td>&nbsp;</td>\r\n<td>Seg&uacute;n Requerimiento.</td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n<p style=\"text-align: justify;\"><strong>QUINTA: COBROS Y PAGOS A LOS QUE SE COMPROMETEN LAS PARTES:&nbsp;</strong></p>\r\n<p style=\"text-align: justify;\">El propietario se compromete a realzar los pagos antes descrito, en la periodicidad all&iacute; establecida, y la empresa a pagar el propietario las sumas pactadas por servicios prestados menos los descuentos necesarios para el cumplimiento de las obligaciones relacionadas con el conductor y el veh&iacute;culo.</p>\r\n<p style=\"text-align: justify;\">El valor mensual que LA EMPRESA pagar&aacute; a &nbsp;EL PROPIETARIO por el servicio que aqu&iacute; se contrata, depender&aacute; de lo establecido en cada uno de los contratos &nbsp;de prestaci&oacute;n de servicios que har&aacute;n parte integral del presente contrato, en todo caso, del valor pactado se deducir&aacute;n los descuentos de &nbsp;ley, como la retenci&oacute;n en la fuente, u otros valores que sean necesarios seg&uacute;n las normas tributarias, y los descuentos que se deban realizar por obligaciones econ&oacute;micas del propietario derivadas de la vinculaci&oacute;n del veh&iacute;culo.</p>\r\n<p style=\"text-align: justify;\"><strong>SEXTA: OBLIGACIONES DEL CONTRATISTA</strong>. - Son obligaciones especiales de EL CONTRATISTA las &nbsp;que a continuaci&oacute;n se convienen: -<strong>1</strong>.Utilizar la raz&oacute;n social y los distintivos de la Empresa, &uacute;nicamente para cumplimiento del objeto y fines del presente contrato lo cual incluye prestar el servicio p&uacute;blico especial con autorizaci&oacute;n de la empresa que se entender&aacute; otorgada por la entrega del extracto del contrato debidamente diligenciado, de no cumplir con esta obligaci&oacute;n la EMPRESA no responder&aacute; civilmente, ni ante las autoridades en forma solidaria como lo establece la ley &nbsp;por las indemnizaciones y perjuicios que causare. &nbsp;<strong>2</strong>.- Pagar oportunamente todos los impuestos y dem&aacute;s erogaciones oficiales, as&iacute; como combustible, repuestos, accesorios, mano de obra y dem&aacute;s gastos de operaci&oacute;n y mantenimiento del veh&iacute;culo y las relacionadas con las obligaciones laborales a que se refieren las cl&aacute;usulas cuarta y quinta de este contrato. <strong>3</strong>.- Contratar y mantener vigente durante el tiempo de administraci&oacute;n por afiliaci&oacute;n las p&oacute;lizas de seguros, de las cuales la Empresa ser&aacute; la depositaria; as&iacute; como afiliarse a los dem&aacute;s fondos que la ley exige, a fin de efectuar los pagos e indemnizaciones a que hubiere lugar y que correspondieren al Contratista.-Dicha p&oacute;liza deber&aacute; cubrir los riesgos y los montos que las leyes y Decretos vigentes establezcan y los que dispusiere la Empresa. <strong>4</strong>.- Obtener de las autoridades de Tr&aacute;nsito y/o de Transporte respectivas y de la Empresa, autorizaci&oacute;n previa antes de la ejecuci&oacute;n de servicios especiales o expresos. <strong>5</strong>.- No enajenar, ni dar en prenda el veh&iacute;culo durante el tiempo de vigencia de este contrato, sin la previa autorizaci&oacute;n de la Empresa. - <strong>6</strong>.- &nbsp;Acatar y cumplir las disposiciones emanadas de la Asamblea General, as&iacute; como las del Gerente. &nbsp;<strong>7</strong>.- Abstenerse de despachar o disponer que el veh&iacute;culo preste servicio p&uacute;blico de transporte, en las siguientes circunstancias: A) &nbsp;Sin tarjeta de operaci&oacute;n, o revisi&oacute;n t&eacute;cnico mec&aacute;nica &nbsp; vigente; B) Sin extracto del contrato, y sin tenerlo debidamente diligenciado. C) Sin los seguros obligatorio o de responsabilidad civil vigentes &nbsp;y/o aportes al Fondo de Responsabilidad Civil; D) Sin &nbsp;licencia de conducci&oacute;n del conductor y vigente. E) Con el veh&iacute;culo en mal estado. &nbsp;<strong>8</strong>.- Instruir permanentemente al conductor para que cumpla las normas de Tr&aacute;nsito y de Transporte, especialmente las relacionadas con la obligaci&oacute;n de llevar acompa&ntilde;ante cuando transporte ni&ntilde;os, no llevar pasajeros o personas de pie.-<strong>9</strong>. Cumplir y hacer que se cumplan por el conductor, las disposiciones contenidas en la ley 105 de 1993, ley 336 de 1996, decreto 1079 del 2015 y dem&aacute;s normas reglamentarias y concordantes que con posterioridad se dicten, o aquellas que las modifiquen o adicionen. <strong>10</strong>.- Presentar oportunamente y dentro de los t&eacute;rminos se&ntilde;alados por la EMPRESA los documentos exigidos para tramitar la renovaci&oacute;n de la tarjeta de operaci&oacute;n y pagar oportunamente el valor correspondiente exigido por la autoridad competente para dicho tr&aacute;mite. <strong>11</strong>- Entregar o responder a la Gerencia los informes que le sean solicitados dentro de los t&eacute;rminos que se le se&ntilde;alen. <strong>12</strong>.- Informar a la Gerencia por escrito cualquier cambio de direcci&oacute;n de su residencia, dentro de los ocho d&iacute;as siguientes de haberse cumplido el traslado. <strong>13</strong>. Cumplir y hacer cumplir al conductor, los servicios oficialmente autorizados, modificados o que se autoricen, as&iacute; como los horarios, turnos y el Reglamento interno. <strong>14</strong>. pagar oportunamente los deducibles y dem&aacute;s rublos relacionados con los da&ntilde;os ocasionados por el veh&iacute;culo a terceros o a los usuarios del servicio, siempre y cuando exista responsabilidad civil presunta. <strong>15</strong>. Cumplir y hacer cumplir Oportunamente al conductor el programa de revisi&oacute;n y mantenimiento preventivo dise&ntilde;ado por la empresa y diligenciar lo correspondiente en la plataforma virtual establecida para tal fin. <strong>16</strong>. Verificar el estado mec&aacute;nico del veh&iacute;culo antes de prestar el servicio. <strong>17</strong>. Renovar oportunamente los seguros de responsabilidad civil contractual y extracontractual, cancelando con ocho d&iacute;as de anticipaci&oacute;n a su vencimiento el valor total de la prima en las oficinas de la empresa o de manera mensual en caso de que se puedan financiar. <strong>18</strong>. Habilitar el canal de comunicaci&oacute;n utilizando los medios electr&oacute;nicos (informando a la empresa la direcci&oacute;n del correo electr&oacute;nico vigente). <strong>19</strong>. Asistir a todas las reuniones dentro de los programas de capacitaci&oacute;n permanente establecido por la EMPRESA ya sea de manera personal o virtual. <strong>20</strong>. Comprar los dispositivos requeridos para el control de flota que disponga la empresa y pagar el servicio mensual del rastreo satelital donde disponga la empresa de transporte, con el fin de vigilar y constatar el cumplimiento de sus obligaciones. <strong>21</strong>. Diligenciar la plataforma tecnol&oacute;gica que la empresa contarte para el control de los requisitos legales tanto del conductor como del veh&iacute;culo. &nbsp;PARAGRAFO 1: Todas las obligaciones econ&oacute;micas contra&iacute;das por el propietario podr&aacute;n ser descontadas por la empresa de los servicios prestados. PARAGRAFO 2: En caso de venta del veh&iacute;culo, la empresa se reserva el derecho de aceptar al nuevo propietario, en todo caso el nuevo propietario pagara a t&iacute;tulo de cesio de contrato la suma de un salario m&iacute;nimo mensual legal vigente.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>SEPTIMA: OBLIGACIONES DE LA EMPRESA</strong>.- Son las siguientes: 1.- Velar porque el veh&iacute;culo una vez formalizada la administraci&oacute;n por afiliaci&oacute;n coloque en la carrocer&iacute;a los distintivos, n&uacute;mero de orden, raz&oacute;n social y pagina web de la empresa.- 2.- Vigilar que el conductor y el propietario que conduce su propio veh&iacute;culo se encuentre afiliado al Sistema de Seguridad Social.- 3.- Desarrollar el programa de medicina preventiva para el conductor.- 4.- Suministrar oportunamente la tarjeta de operaci&oacute;n siempre y cuando el contratista entregue los documentos oportunamente a la empresa y pague el valor correspondiente seg&uacute;n las autoridades. - 5.- Desarrollar programas de capacitaci&oacute;n para los operadores del equipo y a los Cooperados- 6.- Vigilar que el veh&iacute;culo cuente con las condiciones de seguridad y comodidad reglamentados por &nbsp;el &nbsp;Ministerio de Transporte.- 7.- Desarrollar el programa de revisi&oacute;n y mantenimiento preventivo del equipo.- 8.- Vigilar que el veh&iacute;culo preste el servicio con la tarjeta de operaci&oacute;n vigente. 9.- Vigilar y constatar que el conductor del veh&iacute;culo cuente con licencia de conducci&oacute;n vigente y apropiada. 10.- Llevar y mantener en el archivo una ficha t&eacute;cnica del veh&iacute;culo.- 11.- Entregar al propietario del equipo la ficha t&eacute;cnica una vez efectuada la vinculaci&oacute;n por la autoridad competente.- 12.- Expedir el paz y salvo sin costo alguno, siempre y cuando no tengan deudas originadas con ocasi&oacute;n de las obligaciones contra&iacute;das en el presente contrato.- 13.- Expedir un extracto en el que se discrimine los rubros y montos de los pagos efectuados.- 14.- entregar al propietario el extracto del contrato de acuerdo a la normatividad vigente y debidamente firmado por representante legal de la misma, despu&eacute;s de verificar la existencia del contrato.</p>\r\n<p style=\"text-align: justify;\"><strong>OCTAVA: DERECHOS DE LA EMPRESA:</strong> Los derechos de empresa se limitan a la exigencia al propietario del cumplimiento de sus obligaciones, en especial las relacionadas con el conductor y el veh&iacute;culo.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>NOVENA: DERECHOS DEL PROPIETARIO:</strong> Los derechos del propietario se limitan a la exigencia a la empresa del cumplimiento de sus obligaciones, en especial las relacionadas con el conductor y el veh&iacute;culo y el pago de los servicios prestados en los t&eacute;rminos de este contrato.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA: PROHIBICIONES AL CONTRATISTA</strong>.- Le queda prohibido al CONTRATISTA lo siguiente: 1.- &nbsp;Ordenar &nbsp;prestar &nbsp;el &nbsp;servicio &nbsp;cuando &nbsp;el &nbsp;veh&iacute;culo no cumpla con las condiciones de seguridad y comodidad exigidas por el C&oacute;digo de Tr&aacute;nsito, &nbsp;la ley y los reglamentos de transporte.- 2.- Permitir que se preste el servicio de transporte sin autorizaci&oacute;n de la empresa, sin tener tarjeta de operaci&oacute;n vigente, sin &nbsp;seguro &nbsp;obligatorio y &nbsp;de &nbsp;responsabilidad &nbsp;civil contractual y extracontractual o estando vencidos, y sin extracto de contrato- 3.- Entregar el veh&iacute;culo para su conducci&oacute;n y para la prestaci&oacute;n del servicio p&uacute;blico de transporte a un conductor no autorizado por la Empresa.- 4.- Autorizar o permitir que el conductor transporte en el veh&iacute;culo sustancias inflamables o estupefacientes, o de contrabando o de procedencia il&iacute;cita.-5.- Ordenar o Permitir que el conductor cobre tarifas &nbsp;distintas a &nbsp;las contratadas.- 5.- <strong>DERECHOS DE AUTOR</strong>: Se proh&iacute;be expresamente la reproducci&oacute;n de obras musicales y audiovisuales con destino a los pasajeros dentro de los veh&iacute;culos de transporte terrestre automotor especial, ello con el fin de evitar que se presuma de hecho, por parte de la &nbsp;Organizaci&oacute;n Sayco Acinpro (OSA), que al interior de los veh&iacute;culos se est&aacute; realizando el fen&oacute;meno de la comunicaci&oacute;n p&uacute;blica, plasmada en el art&iacute;culo 8 literal &Ntilde;. de la ley 23 de 1982&rdquo;.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA PRIMERA: PROHIBICIONES A LA EMPRESA</strong>. - 1.- Exigir suma alguna por desvinculaci&oacute;n o por el tr&aacute;mite de expedici&oacute;n de paz y salvo. - 2.- Hacer cobros diferentes a los previstos en este contrato. - 3.- Retener la tarjeta de operaci&oacute;n por obligaciones contractuales. - 4.- Exigir al propietario del veh&iacute;culo comprar acciones de la empresa.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA SEGUNDA: CAUSALES DE TERMINACION DE CONTRATO Y DESVINCULACION DEL VEHICULO</strong>.- Sin perjuicio de las disposiciones contenidas en este contrato o las de orden legal, LA EMPRESA proceder&aacute; &nbsp;a dar por terminado el contrato en cualquier momento dando aviso al propietario con 60 d&iacute;as de anticipaci&oacute;n a la fecha en que se terminara el contrato sin que este supeditado a la terminaci&oacute;n del mismo y remitir&aacute; carta al Ministerio de Transporte en la forma prevista en las disposiciones de transporte, adem&aacute;s terminara el contrato de manera inmediata cuando acontezca una de las siguientes causales: 1) Cuando el propietario por intermedio del conductor o personalmente preste servicio de transporte sin tarjeta de operaci&oacute;n, sin el extracto del contrato &nbsp;o con el veh&iacute;culo en mal estado. 2) Cuando el propietario o su conductor se abstenga de forma reiterada y sin ninguna justificaci&oacute;n, de cumplir las determinaciones emanadas de la Asamblea General, o de las &oacute;rdenes que imparta la Gerencia. - 3) &nbsp;Por decisi&oacute;n judicial u orden administrativa de las autoridades de Tr&aacute;nsito y Transporte.- 4) Por incumplimiento total o parcial o cumplimiento tard&iacute;o o defectuoso de las obligaciones expresadas en este contrato. 5) Cuando el CONTRATISTA dirija, de &oacute;rdenes o instrucciones, o patrocine al conductor para cometer cualquier tipo de il&iacute;citos o el incumplimiento de las normas de Tr&aacute;nsito y Transporte. 6)- No cancelar oportunamente los valores acordados en este contrato. 7). cuando en el t&eacute;rmino de (8) Ocho d&iacute;as no sean corregidos los da&ntilde;os mec&aacute;nicos que sean detectados al inspeccionar el veh&iacute;culo. &nbsp;<strong>PARAGRAFO 1:</strong>- Tambi&eacute;n podr&aacute; cancelarse este contrato por acuerdo mutuo entre las partes o por muerte del propietario si los herederos o causahabientes no desean continuarlo, caso este en el cual no tendr&aacute;n derecho a reclamar perjuicios o indemnizaci&oacute;n &nbsp;a la Empresa, cuando esta cancele el contrato por incumplimiento de las obligaciones adquiridas por el Contratista o por cualquiera de las causales de terminaci&oacute;n aqu&iacute; previstas.- Una vez adjudicado dentro de la sucesi&oacute;n el veh&iacute;culo, el heredero favorecido deber&aacute; tramitar el traspaso correspondiente y manifestar por escrito ante la Gerencia si desea continuar la afiliaci&oacute;n del veh&iacute;culo, caso en el cual deber&aacute; firmarse nuevo contrato entre las partes o prorrogarse el existente mediante cl&aacute;usula adicional. <strong>PARAGRAFO 2</strong>:.- El CONTRATISTA se compromete en caso de terminaci&oacute;n del contrato a presentarse a la empresa en el momento que se le cite, para firmar la comunicaci&oacute;n que debe enviarse al Ministerio de Transporte &nbsp;para el retiro del veh&iacute;culo de la capacidad transportadora y entregar el original de la tarjeta de operaci&oacute;n, y si no lo hiciere responder&aacute; en los t&eacute;rminos de este contrato y judicialmente si a ello hubiere lugar por los perjuicios causados a LA EMPRESA. y a terceros. - <strong>PARAGRAFO 3</strong>: .- Mientras el Ministerio de Transporte expide la autorizaci&oacute;n para la desvinculaci&oacute;n, la EMPRESA permitir&aacute; que el veh&iacute;culo contin&uacute;e prestando el servicio si el CONTRATISTA as&iacute; lo solicita por escrito, comprometi&eacute;ndose a seguir cumpliendo las obligaciones contenidas en este contrato y a observar los reglamentos de la EMPRESA. <strong>PARAGARFO 4</strong>: En caso de &eacute;l, propietario de manera unilateral decida el cambio de empresa y desvinculaci&oacute;n, el propietario deber&aacute; estas a paz y salvo y pagar un mes de administraci&oacute;n y las p&oacute;lizas gasta tanto se genera efectivamente la desvinculaci&oacute;n del veh&iacute;culo.&nbsp;&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA TERCERA: DESIGNACI&Oacute;N Y CONTRATACI&Oacute;N DEL CONDUCTOR</strong>. - El conductor ser&aacute; designado por el propietario del veh&iacute;culo, salvo que este delegue esta responsabilidad en la empresa. Este deber&aacute; contar con los requisitos exigidos por la empresa y por las normas legales vigentes sobre la materia especialmente lo relacionado con los requisitos de la legislaci&oacute;n de tr&aacute;nsito, deber&aacute; ser contratado directamente por la empresa como dependiente de la misma de conformidad a lo establecido en el art&iacute;culo 2.2.1.6.12.7. del Decreto 1079 del 2015.&nbsp;<strong>PARAGRAFO 1:</strong> Se conviene que tambi&eacute;n podr&aacute; LA EMPRESA &nbsp;aceptar como conductor al mismo Contratista, si este cumple los requisitos exigidos por la ley tanto de tr&aacute;nsito, como ley de transporte y ley de seguridad social para el desarrollo de esa labor y siempre y cuando &nbsp;las &nbsp;normas lo permitan, caso en el cual las obligaciones y responsabilidades de ese oficio, ser&aacute;n diferentes a las que se desprenden de este contrato, siendo su obligaci&oacute;n afiliarse &nbsp;al sistema de seguridad social, y allegar a la empresa cada mes, el soporte del pago respectivo a cada entidad a la cual se encuentra afiliado como trabajador independiente, o cancelar mensualmente a la empresa dicho valor. <strong>PARAGRAFO 2:</strong> El conductor deber&aacute; dar cumplimiento a los programas de capacitaci&oacute;n que establezca la empresa, as&iacute; como cumplir con los ex&aacute;menes m&eacute;dicos para ingresos los peri&oacute;dicos y de retiro, y la empresa queda en libertad de prescindir de sus servicios cuando se genera alguna causal de incumplimiento.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA CUARTA: SALARIO, PRESTACIONES Y DEMAS OBLIGACIONES CON EL CONDUCTOR.- EL CONTRATISTA</strong> como propietario del veh&iacute;culo asume el pago de los salarios, primas, cesant&iacute;as, bonificaciones, aportes a la Seguridad Social y dem&aacute;s prestaciones sociales y econ&oacute;micas del conductor, en la forma y t&eacute;rminos se&ntilde;alados en el Contrato de Trabajo, pagos que el propietario autoriza descontar del producido del veh&iacute;culo y que de no cubrirlo, deber&aacute; consignar mensualmente a la empresa para atender los pagos a las empresas promotoras de salud ( EPS ), a la de riesgos profesionales (ARL), al Fondo de Pensiones y pagos parafiscales a cajas de compensaci&oacute;n y dem&aacute;s. &nbsp;En caso de que el propietario decida no contar m&aacute;s con los servicios del conductor debe oficializar el despido con justa causa con el acompa&ntilde;amiento de la empresa, entregando la constancia del pago realizado de las prestaciones sociales o dineros adeudados al conductor a la fecha del despido, &nbsp;o si se trata de una renuncia voluntaria debe anexar la carta de renuncia debidamente diligenciada, y adem&aacute;s el paz y salvo del conductor, pues en caso de no ser as&iacute; no se permitir&aacute; el ingreso de un nuevo conductor, lo anterior para proteger los derechos del conductor y los intereses de la empresa ante una eventual demanda laboral. <strong>PARAGRAFO</strong>: Queda acordado y aceptado por el propietario que, si el conductor no cumple con sus obligaciones, la EMPRESA queda facultada para suspender la prestaci&oacute;n del servicio y no expedir la tarjeta de operaci&oacute;n y extracto del contrato y aplicar las sanciones a que haya lugar, incluida la desvinculaci&oacute;n unilateral del veh&iacute;culo dando por terminada esta relaci&oacute;n contractual.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA QUINTA: VIGILANCIA Y SUPERVIGILANCIA</strong>. - LA EMPRESA vigilar&aacute; el cumplimiento de todos los requisitos legales, reglamentarios y los que se determinen en el r&eacute;gimen interno de la EMPRESA, relacionadas con la operaci&oacute;n del veh&iacute;culo y la prestaci&oacute;n del servicio, para lo cual tomar&aacute; las medidas que cada situaci&oacute;n requiera, bien sea respecto del veh&iacute;culo o en relaci&oacute;n con el conductor.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA SEXTA. MANTENIMIENTO PREVENTIVO</strong>: El ministerio de Transporte expidi&oacute; la Resoluci&oacute;n 315 del 6 de febrero del 2013, por la cual se adoptan unas medidas para garantizar la seguridad en el transporte p&uacute;blico terrestre automotor y se dictan otras disposiciones. En dicha resoluci&oacute;n se establecen nuevas obligaciones para propietarios y empresas de transporte con relaci&oacute;n al mantenimiento de los veh&iacute;culos vinculados y espec&iacute;ficamente el art&iacute;culo 10 de la misma, ordena ajustar los contratos de administraci&oacute;n por afiliaci&oacute;n a esta nueva norma. As&iacute; las cosas, la empresa y el propietario acuerdan lo siguiente: <strong>1</strong>. Acatando lo dispuesto en esta resoluci&oacute;n, El propietario se compromete a mantener actualizada una ficha t&eacute;cnica Revisi&oacute;n y mantenimiento de cada veh&iacute;culo entregando a la empresa mensualmente las facturas de mantenimiento y reparaciones realizadas; dichas intervenciones deber&aacute;n ser realizadas en un centro especializado seg&uacute;n lo que defina el Ministerio de Transporte. <strong>2</strong>. Para la validaci&oacute;n satisfactoria de las reparaciones correctivas y mantenimientos preventivas y de que trata el art&iacute;culo 2 de la resoluci&oacute;n 315, el propietario deber&aacute; remitir el veh&iacute;culo para unas inspecciones peri&oacute;dicas que se realizaran donde la empresa establezca que realizaran cada dos meses, en caso de que el veh&iacute;culo no apruebe estas revisiones no se le entregara el extracto de contrato. <strong>3</strong>. Para efectos del establecer el periodo en que deber&aacute; realizarse el mantenimiento preventivo de los veh&iacute;culos, la empresa ha establecido que estos no podr&aacute;n realizarse con m&aacute;s de dos meses, pero se realizar&aacute;n cuando el veh&iacute;culo lo requiera anexando la factura correspondiente a la ficha t&eacute;cnica del automotor. <strong>4</strong>. El conductor en compa&ntilde;&iacute;a del propietario o de la auxiliar de ruta, este &uacute;ltimo actuando en representaci&oacute;n de la empresa, se encargara de realizar el protocolo de alistamiento de que trata el art&iacute;culo 3 de la resoluci&oacute;n 315 del 2013, verificando como m&iacute;nimo los siguientes aspectos: Fugas del motor, tensi&oacute;n correas, tapas, niveles de aceite del motor, transmisi&oacute;n, direcci&oacute;n, frenos, nivel de agua limpia brisas, aditivos de radiador, filtros h&uacute;medos y secos; bater&iacute;as: niveles de electrolito, ajuste de bordes y sulfataci&oacute;n; llantas: desgaste, presi&oacute;n de aire; equipo de carretera; botiqu&iacute;n. De dicho alistamiento se dejar&aacute; constancia en la ficha pre operacional, la cual ser&aacute; entregada por la empresa. <strong>5</strong>. El propietario y el conductor no podr&aacute; realizar reparaciones del veh&iacute;culo en las v&iacute;as, &nbsp;solo se except&uacute;a las reparaciones de emergencia o bajo absoluta imposibilidad f&iacute;sica de mover el veh&iacute;culo, con el fin de permitir el desplazamiento del automotor al centro especializado para las labores de reparaci&oacute;n; cuando el veh&iacute;culo haya sido intervenido en la v&iacute;a no podr&aacute; continuar con la prestaci&oacute;n del servicio de transporte debiendo a la empresa proveer oportunamente un veh&iacute;culo de reemplazo, salvo cuando el veh&iacute;culo se haya pinchado. <strong>6</strong>. Para viajes de m&aacute;s de 8 horas de recorrido entre el lugar de origen y el lugar de destino, el propietario deber&aacute; contar con un segundo conductor. <strong>7</strong>. Los propietarios se comprometen a asistir y a convocar a los conductores a las reuniones y capacitaciones que establezca la empresa para el cumplimiento de la resoluci&oacute;n 315 y dem&aacute;s disposiciones establecidas en la ley y las disposiciones administrativas de la empresa. <strong>8</strong>. Adem&aacute;s el propietario se compromete a llevar el veh&iacute;culo para la realizaci&oacute;n del mantenimiento preventivo en los talleres establecidos por la empresa. PARRAGRAFO: INSPECCI&Oacute;N DEL VEH&Iacute;CULO Y CONSECUENCIAS: LA EMPRESA podr&aacute; en cualquier tiempo inspeccionar el estado mec&aacute;nico y general del veh&iacute;culo, como garant&iacute;a de seguridad en el servicio y en caso de comprobarse fallas, tanto EL CONTRATISTA como el conductor del veh&iacute;culo atender&aacute;n de inmediato las ordenes de LA EMPRESA para corregirlas so pena de no expedir extractos de contrato y no incluirlo en el plan de rodamiento.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA SEPTIMA: COLORES, EMBLEMAS E IDENTIFICACION</strong>. - El contratista se compromete una vez firmado este contrato a marcar el veh&iacute;culo con los colores respectivos de la EMPRESA, e instalar en la forma que se le indique, el radio y los emblemas que identifican a la empresa, adem&aacute;s deber&aacute; acatar lo establecido en el 2.2.1.6.2.4 del Decreto 1079 del 2015 modificado por el Decreto 431 de 2017, que establece que los veh&iacute;culos que ingresen al Servicio P&uacute;blico de Transporte Terrestre Automotor Especial deber&aacute;n ser de color blanco. &nbsp;Adem&aacute;s, en sus costados laterales y en la parte trasera del veh&iacute;culo, con caracteres destacados y legibles, llevar&aacute;n la raz&oacute;n social o sigla comercial de la empresa a la cual est&aacute;n vinculados, acompa&ntilde;ada de la expresi&oacute;n &ldquo;Servicio Especial&rdquo; en caracteres de color verde y de no menos de 15 cent&iacute;metros de alto, as&iacute; como el n&uacute;mero del veh&iacute;culo asignado por la empresa, con caracteres num&eacute;ricos de 10 cent&iacute;metros de alto. Los logos, su distribuci&oacute;n y tama&ntilde;o ser&aacute;n potestativos de cada empresa, para lo cual se les indicara lo que establece su manual de imagen corporativo. En caso de que el contratante del servicio exija la fijaci&oacute;n de su logotipo en el veh&iacute;culo, este no podr&aacute; impedir la visibilidad de la placa que deber&aacute; llevar en los costados, conforme a la exigencia del art&iacute;culo 28 de la Ley 769 de 2002. El tama&ntilde;o de dicho logotipo no podr&aacute; ser mayor al 50% del escogido para la raz&oacute;n social o sigla comercial de la empresa a la cual est&aacute; vinculado el veh&iacute;culo. Cuando se trate de veh&iacute;culos acondicionados para el transporte de personas con requerimientos en servicio de salud o en situaci&oacute;n de discapacidad, adicionalmente deber&aacute; aplicarse lo establecido por las normas que regulan el particular; adem&aacute;s si el veh&iacute;culo va a prestar transporta escolar deber&aacute; acatar lo dispuesto en el art&iacute;culo 2.2.1.6.10.1 del decreto 1079 del 2015 y deber&aacute; contar con las siguientes caracter&iacute;sticas, sin que por ello dejen de dedicarse a los dem&aacute;s servicios que les permiten las normas legales vigentes:</p>\r\n<p style=\"text-align: justify;\">Para transporta escolar, deber&aacute; tener deber&aacute;n tener pintadas en la parte posterior de la carrocer&iacute;a, franjas alternas de diez (10) cent&iacute;metros de ancho en colores amarillo pantone 109 y negro, con inclinaci&oacute;n de 45 grados y una altura m&iacute;nima de 60 cent&iacute;metros. &nbsp;Igualmente, en la parte superior trasera y delantera de la carrocer&iacute;a en caracteres destacados, de altura m&iacute;nima de 10 cent&iacute;metros, deber&aacute;n llevar la leyenda &ldquo;Escolar&rdquo;.&rdquo;&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA OCTAVA: PONSABILIDADES POR INFRACCIONES Y DA&Ntilde;OS</strong>.- Conforme lo estipulan las normas legales vigentes, ser&aacute;n a cargo del propietario el pago de las multas por infracciones a las normas de tr&aacute;nsito y transporte que se generen por su conducta o la conducta del conductor, por lo tanto en caso de que la empresa resulte involucrada podr&aacute; repetir en contra del contratista o propietario lo pagado, as&iacute; mismo EL CONTRATISTA se har&aacute; cargo de cualquier reclamaci&oacute;n de terceros que pueda presentarse y realizar los descaros correspondientes, colaborando a la empresa en la defensa de los intereses de los involucrados.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>DECIMA NOVENA: CONVENIOS DE COLABORACI&Oacute;N EMPRESARIAL:</strong> La empresa firmara los convenios de colaboraci&oacute;n empresarial que sean solicitados por otras empresas o por el propietario, siempre y cuando el propietario se encuentre al d&iacute;a con las obligaciones econ&oacute;micas y con los requisitos legales; y siempre que el convenio cumpla con lo establecido en las normas legales vigentes; la empresa podr&aacute; exigir a la empresa que solicita el convenio que se pague directamente a la cuenta de la empresa el dinero fruto de la prestaci&oacute;n del servicio y descontar de all&iacute; las obligaciones que mes a mes surjan, especialmente la cuota de sostenimiento y las obligaciones con las aseguradoras y con el conductor; la empresa tambi&eacute;n podr&aacute; abstenerse de firmar el convenio cuando la empresa requiera el veh&iacute;culo &nbsp;para cumplir los servicios contratados y as&iacute; cumplir con el plan de rodamiento, adem&aacute;s podr&aacute; exigir a la otra empresa la resoluci&oacute;n de habilitaci&oacute;n vigente y la copia del contrato con el usuario o una certificaci&oacute;n de la existencia del mismo.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA: RESPONSABILIDADES Y OBLIGACIONES SUBSIDIARIAS.</strong> - EL CONTRATISTA ser&aacute; responsable ante las autoridades y ante terceros, de las sanciones, perjuicios e indemnizaciones que se causaren por el incumplimiento total o parcial o ejecuci&oacute;n defectuosa o tard&iacute;a de cualquiera de las obligaciones o prohibiciones aqu&iacute; estipuladas, cuando por acci&oacute;n u omisi&oacute;n propia o del conductor del veh&iacute;culo, sea el causante de los mismos. En el evento que LA EMPRESA en forma solidaria y subsidiaria tenga que responder por las situaciones previstas en esta cl&aacute;usula y otras similares no previstas en este Contrato, aquella podr&aacute; repetir lo pagado contra el Contratista por v&iacute;a judicial, en caso de no darse la conciliaci&oacute;n o arreglo extrajudicial. &nbsp;La empresa tambi&eacute;n podr&aacute; repetir en contra del propietario lo pagado ante la Superintendencia de Puertos y Transporte por sanciones administrativas generadas cuando el propietario del veh&iacute;culo transite sin los documentos de transporte o sin acompa&ntilde;ante o genere con su conducta la violaci&oacute;n a las normas de transporte. El propietario autoriza expresamente a la empresa a repetir lo pagado por sanciones administrativas anteriores y desde que el veh&iacute;culo se vincul&oacute; a la empresa.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA PRIMERA: CONSECUENCIAS POR ACTUACIONES UNILATERALES</strong>. - Si en forma unilateral EL CONTRATISTA retira el veh&iacute;culo del servicio o no presta el servicio sin justa causa o sin previo aviso y como consecuencia de tal actuaci&oacute;n la Empresa es investigada y sancionada, el contratista deber&aacute; cancelar los honorarios del abogado para ejercer la defensa, pagar la multa que se impusiere y los perjuicios que con tal acci&oacute;n se causen. &nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA SEGUNDA: OBLIGACIONES DEL CONTRATISTA DESPUES DE LA DESVINCULACION DEL VEHICULO</strong>.- Una vez se produzca la desvinculaci&oacute;n definitiva del &nbsp;veh&iacute;culo &nbsp;por &nbsp;la &nbsp;autorizaci&oacute;n &nbsp;otorgada por la autoridad competente de Transporte, EL CONTRATISTA se obliga para con LA EMPRESA a firmar el Acta de Terminaci&oacute;n del contrato y a borrar o cambiar la raz&oacute;n &nbsp;social, as&iacute; como los distintivos o emblemas de la Empresa en el t&eacute;rmino no mayor de cinco ( 5 ) d&iacute;as, haciendo llegar la comprobaci&oacute;n respectiva a la Gerencia de la Empresa, so pena de incurrir en responsabilidad y &nbsp;consiguiente &nbsp;pago de los perjuicios e indemnizaciones que con tal actitud se causen. Adem&aacute;s, deber&aacute; cancelar los valores pendientes por p&oacute;lizas de seguros hasta el momento de la cancelaci&oacute;n de la tarjeta de operaci&oacute;n.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA TERCERA: DERECHOS DEL CONTRATISTA.</strong> - Son los siguientes: 1.- A que se tramite y entregue oportunamente la tarjeta de operaci&oacute;n, si ha suministrado con la oportunidad debida los requisitos exigidos.2 &nbsp;Expedir un extracto que contenga en forma discriminada los rubros y montos, cobrados y pagados, por cada concepto.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA CUARTA: &nbsp;DERECHOS &nbsp;DE &nbsp;LA &nbsp;EMPRESA</strong>.- &nbsp;Son &nbsp; los siguientes: 1.- A qu&eacute; se le entregue oportunamente la informaci&oacute;n &nbsp;solicitada &nbsp;al &nbsp;propietario &nbsp;del &nbsp;veh&iacute;culo- 2.- A hacer uso de la aplicaci&oacute;n de las sanciones previstas por el incumplimiento de las obligaciones y convenios determinados en este contrato.- 3.- A qu&eacute; se le entreguen oportuna y previamente los documentos y requisitos exigidos en la ley, as&iacute; como los pagos respectivos, para el tr&aacute;mite de solicitud o renovaci&oacute;n de la tarjeta de operaci&oacute;n y de las p&oacute;lizas de los seguros.- 4.- A que se &nbsp;cumplan por el &nbsp;CONTRATISTA los pagos dentro de los t&eacute;rminos y de las fechas acordados en las cl&aacute;usulas de este contrato.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA QUINTA: NORMAS QUE FORMAN PARTE DEL CONTRATO</strong>.- Forman parte del presente contrato para todos los efectos no previstos en estas cl&aacute;usulas, las normas expedidas o que se expidan por el Congreso Nacional, El Gobierno Nacional, El Concejo Municipal, La Autoridad competente del &Aacute;rea Metropolitana, el Ministerio de Transporte, tanto en el campo &nbsp;administrativo, &nbsp;como &nbsp;en &nbsp; materia &nbsp;penal, &nbsp; civil, &nbsp; laboral &nbsp;y &nbsp; comercial, como de Tr&aacute;nsito y Transporte.- PARAGRAFO: Las decisiones adoptadas por la Asamblea General y/o El Consejo de Administraci&oacute;n seg&uacute;n el caso, constituyen parte integrante de este contrato y por lo tanto EL CONTRATISTA se obliga a cumplirlas, cuando las mismas impliquen revocaci&oacute;n, reforma, adici&oacute;n o aclaraci&oacute;n de cualquiera de las cl&aacute;usulas contenidas en el presente contrato.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA SEXTA: INTERESES MORATORIOS Y RENUNCIA A REQUERIMIENTOS</strong>. - Cualquiera de las obligaciones aqu&iacute; contra&iacute;das, causar&aacute;n intereses moratorios conforme a la certificaci&oacute;n expedida por la Superintendencia Bancaria o el Banco de la Rep&uacute;blica. Todas y cada una de las obligaciones contenidas en el presente contrato o las que se llegaren a establecer y &nbsp;las &nbsp;que se &nbsp;impongan &nbsp;conforme a las cl&aacute;usulas y estipulaciones contenidas en este Contrato, se cumplir&aacute;n sin necesidad de requerimientos personales, extrajudiciales, judiciales o legales a los cuales renunciamos expresamente.- PARAGRAFO: No obstante lo anterior, si la EMPRESA decide mediante comunicaciones de prensa o radio, comunicar, notificar o hacer conocer sus decisiones a EL CONTRATISTA, estas constituir&aacute;n medio v&aacute;lido de informaci&oacute;n para todos los efectos legales.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA SEPTIMA: PAGAR&Eacute; A CARGO DEL CONTRATISTA.- &nbsp;EL CONTRATISTA</strong> expresamente manifiesta, que este contrato tiene la caracter&iacute;stica del T&Iacute;TULO VALOR PAGAR&Eacute;, en la &nbsp;forma como lo contempla el C&oacute;digo de Comercio, y por lo tanto en forma incondicional pagar&aacute; a la orden de la Cooperativa de Transportadores Hacaritama, <strong>COOTRANSHACARITAMA</strong>, dentro de los treinta d&iacute;as siguientes al requerimiento escrito de LA EMPRESA o comunicaci&oacute;n de prensa o radio, las sumas que resulte a deber durante la vigencia del presente contrato por concepto de: cuotas atrasadas por los pagos u obligaciones aqu&iacute; contra&iacute;das, valores adeudados por concepto de provisiones y suministros de la empresa, bien sea combustible, llantas, repuestos, insumos para el automotor, GPS, multas y comparendos antes la superintendencia de puertos y transportes; pr&eacute;stamos; valores cubiertos por &nbsp;la empresa y relacionados con la &nbsp;responsabilidad civil contractual o extracontractual derivados de la prestaci&oacute;n del servicio p&uacute;blico de transporte; valores cubiertos por la &nbsp;empresa por &nbsp;raz&oacute;n de infracciones al C&oacute;digo Nacional de Tr&aacute;nsito o al Estatuto de Transporte en que haya incurrido el Contratista o el conductor de su veh&iacute;culo con ocasi&oacute;n de la prestaci&oacute;n de servicio p&uacute;blico de transporte; valores causados por cuotas ordinarias o extraordinarias con cargo a los propietarios de veh&iacute;culos ordenados por la Asamblea General de la Empresa; PARAGRAFO : La EMPRESA se abstendr&aacute; de expedir el paz y salvo cuando existan obligaciones no canceladas por el CONTRATISTA, hasta tanto este t&iacute;tulo valor se haya hecho efectivo. &nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA OCTAVA</strong>: EL CONTRATISTA manifiesta que en ejercicio de mi Derecho a la libertad y &nbsp;autodeterminaci&oacute;n inform&aacute;tica autorizo a &nbsp;la empresa &nbsp;o a la entidad que mi acreedor delegue para representarlo o a su cesonario , endosatario o quien ostente en el futuro la calidad de acreedor , previo a la relaci&oacute;n contractual y de manera irrevocable , escrita, expresa, concreta, suficiente, voluntaria e informada, con la finalidad de que la informaci&oacute;n comercial, crediticia, financiera y de servicio de la cual soy titular, referido al nacimiento , ejecuci&oacute;n extinci&oacute;n de obligaciones &nbsp;dinerarias(independiente de la naturaleza del contrato que les de origen)a mi comportamiento e historial crediticio, incluida informaci&oacute;n positiva y negativa de mis h&aacute;bitos de pago y aquella que se refiere a la informaci&oacute;n personal necesaria para el estudio, an&aacute;lisis y eventual otorgamiento de cr&eacute;dito o celebraci&oacute;n de un contrato, sea en general administrada y en especial ,capturada, tratada, procesada, operada verificada y transmitida, transferida, usada opuesta en circulaci&oacute;n y consultada por terceras personas autorizadas expresamente para que la informaci&oacute;n sea concedida &nbsp;y reportada en la base de datos de DATACREDITO operada por DATACREDITO o cualquier otro. De la misma manera autorizo a DATACREDITO, como operador de la base de datos de PROCREDITO que tiene la finalidad estrictamente comercial, financiera, crediticia y de servicios, para que procese, opere y administre la informaci&oacute;n de la cual soy titular y para que la misma sea transferida y transmitida a usuarios, lo mismo que a otros operadores nacionales o extranjeros que tengan la misma finalidad que comprenda la que tiene DATACREDITO. Certifico que los datos personales suministrados por m&iacute;, son veraces, completos, exactos, actualizados, reales y comprobados. Por lo tanto, cualquier error en la informaci&oacute;n suministrada ser&aacute; de mi &uacute;nica y exclusiva responsabilidad. Lo que exonera a DATACREDITO de su responsabilidad ante las autoridades judiciales y /o administrativas. Declaro que he le&iacute;do y comprendido a cabalidad el contenido de la presente autorizaci&oacute;n y acepto la finalidad en ella descrita y las consecuencias que se derivan de ella.</p>\r\n<p style=\"text-align: justify;\"><strong>VIGESIMA NOVENA: VALOR Y PAGO DE PERJUICIOS.</strong> - El incumplimiento de las obligaciones contenidas en el presente contrato por parte del CONTRATISTA, dar&aacute; derecho a la EMPRESA para exigir sin ning&uacute;n requerimiento, el pago de perjuicios que se tasan en el equivalente a ocho (8) salarios m&iacute;nimos mensuales vigentes, este mismo valor se cobrara en el caso de terminaci&oacute;n del contrato de vinculaci&oacute;n de forma anticipada, en calidad de clausula penal.</p>\r\n<p style=\"text-align: justify;\"><strong>TRIGESIMA: CL&Aacute;USULA D&Eacute;CIMA. CONFIDENCIALIDAD</strong>: El Contratista se obliga a la m&aacute;s estricta confidencialidad y reserva sobre los t&eacute;rminos del presente contrato, as&iacute; como sobre cualquier otra informaci&oacute;n a la que tenga acceso como consecuencia de la ejecuci&oacute;n de este. Toda la informaci&oacute;n que suministre el Contratante o que por cualquier medio llegue a ser conocida por el Contratista con ocasi&oacute;n de la ejecuci&oacute;n del presente contrato, incluyendo informaci&oacute;n sobre cualquiera de las producciones de El Contratante, tendr&aacute; el car&aacute;cter de &ldquo;Confidencial&rdquo; o &ldquo;Privilegiado&rdquo;, a menos de que obtenga previamente la expresa autorizaci&oacute;n de El Contratante sobre el levantamiento de la reserva. El Contratista se obliga a adoptar las medidas necesarias para vigilar la informaci&oacute;n que reciba u obtenga, as&iacute; como para evitar su divulgaci&oacute;n, indebida o utilizaci&oacute;n o explotaci&oacute;n. Esta obligaci&oacute;n de reserva no ser&aacute; exigible respecto de las informaciones que sean de conocimiento p&uacute;blico en el momento de su divulgaci&oacute;n: por ejemplo, sean conocidas por El Contratista con anterioridad, conforme conste en sus registros escritos o hayan sido obtenidos o desarrollados en forma independiente por El Contratista, sin basarse en la informaci&oacute;n obtenida de El Contratante, o cuya divulgaci&oacute;n sea requerida por disposici&oacute;n legal u orden judicial. La obligaci&oacute;n contenida en esta cl&aacute;usula continuara en vigencia de manera indefinida, aunque el presente contrato, por cualquier causa, termine.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>TRIGESIMA PRIMERA: INTERPRETACI&Oacute;N DE SITUACIONES</strong>. - Si se admitieren, toleraren o presentaren hechos o situaciones que difieran de lo convenido en el presente contrato, no por eso se entender&aacute; revocaci&oacute;n, modificaci&oacute;n o novaci&oacute;n alguna del mismo, salvo acuerdo expreso por escrito de las partes.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>TRIGESIMA SEGUNDA: MEDIDAS DISCIPLINARIAS INTERNAS.</strong> - Con el prop&oacute;sito de prestar un mejor servicio en el transporte p&uacute;blico especial y sin perjuicio de lo dispuesto en el texto del presente contrato, LA EMPRESA podr&aacute; adoptar medidas disciplinarias de acuerdo a lo previsto en los Reglamentos de la Empresa y las normas sobre la materia.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>TRIGESIMA TERCERA: SOLUCI&Oacute;N DE CONFLICTOS</strong>. &ndash; En caso de que exista alg&uacute;n conflicto o diferencia con relaci&oacute;n a la relaci&oacute;n contractual, se procurara que primero se cite una reuni&oacute;n entre el propietario y la Gerencia para tratar de solucionar el conflicto; en caso de que no sea posible legar a un acuerdo, las partes tienen la libertad de acudir a la justicia ordinaria.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>TRIGESIMA CUARTA: SUSTITUCI&Oacute;N Y MANIFESTACIONES.</strong> - El presente contrato sustituye a cualquier otro (s) anterior (es), rigi&eacute;ndose las obligaciones bilaterales en adelante por este contrato. El contratista manifiesta que conoce y entiende, por lo cual adem&aacute;s acepta el contenido de cada una de las cl&aacute;usulas redactadas en el presente contrato, que lo ley&oacute; y le da su aprobaci&oacute;n.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>TRIG&Eacute;SIMA QUINTA: TITULO EJECUTIVO</strong>. Las obligaciones establecidas en este contrato a cargo de cada una de las Partes son claras, expresas y ser&aacute;n exigibles por la v&iacute;a ejecutiva por la parte acreedora, sin necesidad de requerimiento alguno, judicial o extrajudicialmente o constituci&oacute;n en mora frente a la otra, derechos &eacute;stos a los cuales renuncian las partes en su rec&iacute;proco beneficio y para ello bastar&aacute; la presentaci&oacute;n de este contrato, el cual prestar&aacute; el m&eacute;rito de t&iacute;tulo ejecutivo y la manifestaci&oacute;n de la parte acreedora del incumplimiento de la obligaci&oacute;n.&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong>TRIG&Eacute;SIMA SEXTA: PERFECCIONAMIENTO Y LEGALIZACION.</strong> El presente contrato se perfecciona con el cumplimiento de todos los requisitos estipulados, por lo que deja sin efecto cualquier otro contrato suscrito entre las partes con anterioridad que verse sobre el mismo objeto, en constancia de lo anterior y en se&ntilde;al de asentimiento se firma el presente documento en dos (2) ejemplares del mismo tenor, en la ciudad de Oca&ntilde;a el fechaFirmaContrato.<br><br></p>', '2024-02-01 21:00:54', '2024-02-01 21:00:54');
INSERT INTO `informaciongeneralpdf` (`ingpdfid`, `ingpdfnombre`, `ingpdftitulo`, `ingpdfcontenido`, `created_at`, `updated_at`) VALUES
(7, 'contratoModalidadColectivo', 'Servicio Público de Pasajeros en la Modalidad de Transporte Colectivo', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Entre. La EMPRESA la Cooperativa de Transportadores Hacaritama, COOTRANSHACARITAMA Sociedad Legalmente&nbsp;constituida, e inscrita en el Registro Mercantil, quien para los efectos del presente contrato se denomina LA EMPRESA;&nbsp;debidamente representada por el se&ntilde;or <strong>nombreGerente</strong>, identificado con la c&eacute;dula de ciudadan&iacute;a No. documentoGerente expedida en ciudadExpDocumentoGerente, en adelante se llama LA EMPRESA y el mencionado en la parte inicial del contrato quien obra como propietario del automotor descrito inscrito en matricula, y quien en adelante se llamar&aacute; EL CONTRATISTA, porla otra parte; hacemos constar que hemos celebrado el contrato de administraci&oacute;n por afiliaci&oacute;n por la modalidad de afiliaci&oacute;n, el cual se rige por las siguientes cl&aacute;usulas: <strong>PRIMERA: OBJETO</strong>.El ASOCIADO presenta el siguiente que se referencia al inicio, el cual se declara libre de acci&oacute;n legal, pleitos pendientes, embargos judiciales, condiciones resolutorias y en general, cualquier otro gravamen. <strong>SEGUNDA</strong>: La duraci&oacute;n del presente contrato es por el t&eacute;rmino de un (1) a&ntilde;o a partir de su perfeccionamiento, dicho contrato no se prorroga de forma autom&aacute;tica, por lo que no es necesario dar preaviso a las partes con anterioridad a su vencimiento. <strong>TERCERA</strong>: El valor a pagar por la suscripci&oacute;n del presente contrato ser&aacute; lo acordado por los estatutos, acuerdos y reglamentos vigentes. <strong>CUARTA</strong>: La COOPERATIVA se obliga a colocar y mantener el plan de rodamiento que para este tipo de veh&iacute;culo le ha se&ntilde;alado el Ministerio de Transporte y/o autoridad competente. <strong>QUINTA</strong>: Los impuestos del veh&iacute;culo, multas, da&ntilde;os a terceros en caso de accidentes, servicios m&eacute;dicos, farmac&eacute;uticos, quir&uacute;rgicos, hospitalarios y dem&aacute;s que se ocasionen por el veh&iacute;culo, gastos de combustibles, dineros entregados, prestaciones sociales, salarios e indemnizaciones, seguros del conductor, entre otros, ser&aacute;n de cuenta exclusiva del asociado propietario del veh&iacute;culo. <strong>SEXTA</strong>: El ASOCIADO se compromete a cancelar en la planilla (cuota administrativa) el valor por concepto de servicios administrativos m&aacute;s el aporte social de conformidad con los estatutos vigentes de la cooperativa. <strong>S&Eacute;PTIMA</strong>: El ASOCIADO se responsabiliza de todas y cada una de las prestaciones sociales de sus trabajadores, manteniendo indemne a la COOPERATIVA de cualquier demanda, denuncia, queja o reclamo, teniendo en cuenta la relaci&oacute;n laboral es &uacute;nica y exclusiva entre el ASOCIADO y el CONDUCTOR del veh&iacute;culo vinculado. <strong>OCTAVA</strong>: El ASOCIADO ser&aacute; el &uacute;nico responsable, indemnizar&aacute; y mantendr&aacute; a la COOPERATIVA indemne y libre de todo tipo de P&eacute;rdidas causadas a la COOPERATIVA, a los ASOCIADOS, al Personal, a Otros ASOCIADOS y/o a terceras.<span style=\"mso-spacerun: yes;\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Personas, que sean imputables a los actos u omisiones del ASOCIADO, sus trabajadores y/o el Personal, o que se produzcan como consecuencia del incumplimiento del Contrato, de un Servicio, de los Permisos y/o de las Leyes Aplicables, pudiendo la COOPERATIVA cobrar, compensar o deducir cualquier P&eacute;rdida contra las sumas adeudadas o que lleguen a adeudarse al ASOCIADO bajo cualquier pago pendiente. <strong>NOVENA</strong>:&nbsp; En particular, y sin que ello implique limitaci&oacute;n alguna de lo previsto en la cl&aacute;usula anterior, el ASOCIADO ser&aacute; responsable y mantendr&aacute; al COOPERATIVA indemne frente a todo tipo de P&eacute;rdidas por: (i) cualquier incumplimiento de las Leyes Aplicables, los Permisos y/o de las obligaciones derivadas de actos administrativos expedidos por las Autoridades Competentes, y/o por cualquier afectaci&oacute;n o da&ntilde;o; (ii) P&eacute;rdidas relacionadas con Impuestos que den lugar a un proceso de fiscalizaci&oacute;n o reclamaci&oacute;n de cualquier tipo por parte de las Autoridades Competentes tributarias nacionales o locales, relacionadas con la ejecuci&oacute;n de este Contrato; (iii) cualquier sanci&oacute;n o condena impuesta por las Autoridades Competentes administrativas o judiciales en relaci&oacute;n con el incumplimiento de las obligaciones laborales y de seguridad social del ASOCIADO y sus trabajadores, as&iacute; como por cualquier reclamaci&oacute;n judicial o administrativa iniciada por el trabajador a cargo del ASOCIADO asignado a la ejecuci&oacute;n del Servicio, o por los causahabientes de dicho Personal. <strong>DECIMA</strong>: Las obligaciones de indemnidad del ASOCIADO frente a la COOPERATIVA estar&aacute;n sujetas a los mismos t&eacute;rminos que aquellos aplicables a la prescripci&oacute;n de las acciones correspondientes seg&uacute;n el tipo de reclamaci&oacute;n de que se trate. No obstante, en el evento en que, con posterioridad al vencimiento del correspondiente t&eacute;rmino de prescripci&oacute;n, la COOPERATIVA sea notificada acerca de reclamaciones por P&eacute;rdidas que hayan sido presentadas con anterioridad a dicho vencimiento por terceras Personas (incluyendo el Personal y las Autoridades Competentes), as&iacute; como por reclamaciones laborales a cargo del ASOCIADO, la COOPERATIVA tendr&aacute; un (1) a&ntilde;o m&aacute;s a partir de la fecha de vencimiento del respectivo t&eacute;rmino de prescripci&oacute;n para presentar al ASOCIADO una reclamaci&oacute;n bajo esta cl&aacute;usula con base en dichas reclamaciones. <strong>DECIMA PRIMERA</strong>: El ASOCIADO se compromete a estar h&aacute;bil para asistir a todas las asambleas y eventos cooperativos para los cuales sea citado, so pena de ser sancionado seg&uacute;n los reglamentos vigentes. <strong>DECIMA SEGUNDA</strong>: Ser&aacute; motivo de exclusi&oacute;n y posterior desvinculaci&oacute;n administrativa el asociado que cometa alguna causal de las contempladas en el art&iacute;culo 60 de los estatutos vigentes aprobados el 10 de marzo de 2019, el procedimiento para la desvinculaci&oacute;n se realizar&aacute; bajos los par&aacute;metros del art&iacute;culo 20 de los Estatutos en cuyo caso la Cooperativa dispondr&aacute; de la capacidad transportadora del veh&iacute;culo desvinculado. <strong>DECIMA TERCERA</strong>: La mora en el pago de las cuotas mensuales o de cualquier otra obligaci&oacute;n, causar&aacute; intereses moratorios equivalentes a la tasa m&aacute;xima legal autorizada. <strong>DECIMA CUARTA</strong>: El ASOCIADO se compromete a estar h&aacute;bil para asistir a todas las asambleas y eventos cooperativos para los cuales sea citado, so pena de ser sancionado seg&uacute;n los reglamentos vigentes. <strong>DECIMA QUINTA</strong>: ser&aacute; motivo de exclusi&oacute;n y posterior desvinculaci&oacute;n administrativa el asociado que cometa alguna causal de las contempladas en el art&iacute;culo 60 de los estatutos vigentes, el procedimiento para la desvinculaci&oacute;n se realizar&aacute; bajos los par&aacute;metros del art&iacute;culo 20 de los Estatutos, en cuyo caso la Cooperativa dispondr&aacute; de la capacidad transportadora del veh&iacute;culo desvinculado. <strong>DECIMA SEXTA</strong>: La mora en el pago de las cuotas mensuales o de cualquier otra obligaci&oacute;n, causar&aacute; intereses moratorios equivalentes a la tasa m&aacute;xima legal autorizada. <strong>DECIMA S&Eacute;PTIMA</strong>: El ASOCIADO se compromete con la COOPERATIVA a dar aviso inmediato de los cambios de direcci&oacute;n e informaci&oacute;n personal. <strong>DECIMA OCTAVA</strong>: Se aclara que la venta del veh&iacute;culo a terceros, no implica para el asociado vendedor la cesi&oacute;n de sus aportes sociales, ni dem&aacute;s compromisos econ&oacute;micos que pueda llegar a tener. Tampoco implica la venta la a capacidad transportadora, pues solamente el asociado tiene pleno dominio sobre el veh&iacute;culo. <strong>DECIMA NOVENA</strong>: Quien se constituya en nuevo propietario, debe asociarse a la cooperativa de inmediato para tener derecho a la utilizaci&oacute;n de la capacidad transportadora y se someter&aacute; a los requisitos y tr&aacute;mites de ingresos a la COOPERATIVA como nuevo asociado, reserv&aacute;ndose la COOPERATIVA los derechos de admisi&oacute;n. La capacidad transportadora ser&aacute; siempre de la COOPERATIVA quien tiene la postad de poderla asignar de forma temporal a quienes cumplan con los requisitos para ser asociado. <strong>VIG&Eacute;SIMA</strong>: El presente contrato se perfecciona con el cumplimiento de todos los requisitos estipulados, por lo que deja sin efecto cualquier otro contrato suscrito entre las partes con anterioridad que verse sobre el mismo objeto, en constancia de lo anterior y en se&ntilde;al de asentimiento se firma el presente documento en dos (2) ejemplares del mismo tenor, en la ciudad de Oca&ntilde;a el fechaFirmaContrato.</p>', '2024-02-01 21:00:54', '2024-02-01 21:00:54'),
(8, 'contratoModalidadMixto', 'Servicio Público de Pasajeros en la Modalidad de Transporte Mixto', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Entre. La EMPRESA la Cooperativa de Transportadores Hacaritama, COOTRANSHACARITAMA Sociedad Legalmente&nbsp;constituida, e inscrita en el Registro Mercantil, quien para los efectos del presente contrato se denomina LA EMPRESA;&nbsp;debidamente representada por el se&ntilde;or <strong>nombreGerente</strong>, identificado con la c&eacute;dula de ciudadan&iacute;a No. documentoGerente expedida en ciudadExpDocumentoGerente, en adelante se llama LA EMPRESA y el mencionado en la parte inicial del contrato quien obra como propietario del automotor descrito inscrito en matricula, y quien en adelante se llamar&aacute; EL CONTRATISTA, porla otra parte; hacemos constar que hemos celebrado el contrato de administraci&oacute;n por afiliaci&oacute;n por la modalidad de afiliaci&oacute;n, el cual se rige por las siguientes cl&aacute;usulas: <strong>PRIMERA: OBJETO</strong>.El ASOCIADO presenta el siguiente que se referencia al inicio, el cual se declara libre de acci&oacute;n legal, pleitos pendientes, embargos judiciales, condiciones resolutorias y en general, cualquier otro gravamen. <strong>SEGUNDA</strong>: La duraci&oacute;n del presente contrato es por el t&eacute;rmino de un (1) a&ntilde;o a partir de su perfeccionamiento, dicho contrato no se prorroga de forma autom&aacute;tica, por lo que no es necesario dar preaviso a las partes con anterioridad a su vencimiento. <strong>TERCERA</strong>: El valor a pagar por la suscripci&oacute;n del presente contrato ser&aacute; lo acordado por los estatutos, acuerdos y reglamentos vigentes. <strong>CUARTA</strong>: La COOPERATIVA se obliga a colocar y mantener el plan de rodamiento que para este tipo de veh&iacute;culo le ha se&ntilde;alado el Ministerio de Transporte y/o autoridad competente. <strong>QUINTA</strong>: Los impuestos del veh&iacute;culo, multas, da&ntilde;os a terceros en caso de accidentes, servicios m&eacute;dicos, farmac&eacute;uticos, quir&uacute;rgicos, hospitalarios y dem&aacute;s que se ocasionen por el veh&iacute;culo, gastos de combustibles, dineros entregados, prestaciones sociales, salarios e indemnizaciones, seguros del conductor, entre otros, ser&aacute;n de cuenta exclusiva del asociado propietario del veh&iacute;culo. <strong>SEXTA</strong>: El ASOCIADO se compromete a cancelar en la planilla (cuota administrativa) el valor por concepto de servicios administrativos m&aacute;s el aporte social de conformidad con los estatutos vigentes de la cooperativa. <strong>S&Eacute;PTIMA</strong>: El ASOCIADO se responsabiliza de todas y cada una de las prestaciones sociales de sus trabajadores, manteniendo indemne a la COOPERATIVA de cualquier demanda, denuncia, queja o reclamo, teniendo en cuenta la relaci&oacute;n laboral es &uacute;nica y exclusiva entre el ASOCIADO y el CONDUCTOR del veh&iacute;culo vinculado. <strong>OCTAVA</strong>: El ASOCIADO ser&aacute; el &uacute;nico responsable, indemnizar&aacute; y mantendr&aacute; a la COOPERATIVA indemne y libre de todo tipo de P&eacute;rdidas causadas a la COOPERATIVA, a los ASOCIADOS, al Personal, a Otros ASOCIADOS y/o a terceras.<span style=\"mso-spacerun: yes;\">&nbsp;</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Personas, que sean imputables a los actos u omisiones del ASOCIADO, sus trabajadores y/o el Personal, o que se produzcan como consecuencia del incumplimiento del Contrato, de un Servicio, de los Permisos y/o de las Leyes Aplicables, pudiendo la COOPERATIVA cobrar, compensar o deducir cualquier P&eacute;rdida contra las sumas adeudadas o que lleguen a adeudarse al ASOCIADO bajo cualquier pago pendiente. <strong>NOVENA</strong>:&nbsp; En particular, y sin que ello implique limitaci&oacute;n alguna de lo previsto en la cl&aacute;usula anterior, el ASOCIADO ser&aacute; responsable y mantendr&aacute; al COOPERATIVA indemne frente a todo tipo de P&eacute;rdidas por: (i) cualquier incumplimiento de las Leyes Aplicables, los Permisos y/o de las obligaciones derivadas de actos administrativos expedidos por las Autoridades Competentes, y/o por cualquier afectaci&oacute;n o da&ntilde;o; (ii) P&eacute;rdidas relacionadas con Impuestos que den lugar a un proceso de fiscalizaci&oacute;n o reclamaci&oacute;n de cualquier tipo por parte de las Autoridades Competentes tributarias nacionales o locales, relacionadas con la ejecuci&oacute;n de este Contrato; (iii) cualquier sanci&oacute;n o condena impuesta por las Autoridades Competentes administrativas o judiciales en relaci&oacute;n con el incumplimiento de las obligaciones laborales y de seguridad social del ASOCIADO y sus trabajadores, as&iacute; como por cualquier reclamaci&oacute;n judicial o administrativa iniciada por el trabajador a cargo del ASOCIADO asignado a la ejecuci&oacute;n del Servicio, o por los causahabientes de dicho Personal. <strong>DECIMA</strong>: Las obligaciones de indemnidad del ASOCIADO frente a la COOPERATIVA estar&aacute;n sujetas a los mismos t&eacute;rminos que aquellos aplicables a la prescripci&oacute;n de las acciones correspondientes seg&uacute;n el tipo de reclamaci&oacute;n de que se trate. No obstante, en el evento en que, con posterioridad al vencimiento del correspondiente t&eacute;rmino de prescripci&oacute;n, la COOPERATIVA sea notificada acerca de reclamaciones por P&eacute;rdidas que hayan sido presentadas con anterioridad a dicho vencimiento por terceras Personas (incluyendo el Personal y las Autoridades Competentes), as&iacute; como por reclamaciones laborales a cargo del ASOCIADO, la COOPERATIVA tendr&aacute; un (1) a&ntilde;o m&aacute;s a partir de la fecha de vencimiento del respectivo t&eacute;rmino de prescripci&oacute;n para presentar al ASOCIADO una reclamaci&oacute;n bajo esta cl&aacute;usula con base en dichas reclamaciones. <strong>DECIMA PRIMERA</strong>: El ASOCIADO se compromete a estar h&aacute;bil para asistir a todas las asambleas y eventos cooperativos para los cuales sea citado, so pena de ser sancionado seg&uacute;n los reglamentos vigentes. <strong>DECIMA SEGUNDA</strong>: Ser&aacute; motivo de exclusi&oacute;n y posterior desvinculaci&oacute;n administrativa el asociado que cometa alguna causal de las contempladas en el art&iacute;culo 60 de los estatutos vigentes aprobados el 10 de marzo de 2019, el procedimiento para la desvinculaci&oacute;n se realizar&aacute; bajos los par&aacute;metros del art&iacute;culo 20 de los Estatutos en cuyo caso la Cooperativa dispondr&aacute; de la capacidad transportadora del veh&iacute;culo desvinculado. <strong>DECIMA TERCERA</strong>: La mora en el pago de las cuotas mensuales o de cualquier otra obligaci&oacute;n, causar&aacute; intereses moratorios equivalentes a la tasa m&aacute;xima legal autorizada. <strong>DECIMA CUARTA</strong>: El ASOCIADO se compromete a estar h&aacute;bil para asistir a todas las asambleas y eventos cooperativos para los cuales sea citado, so pena de ser sancionado seg&uacute;n los reglamentos vigentes. <strong>DECIMA QUINTA</strong>: ser&aacute; motivo de exclusi&oacute;n y posterior desvinculaci&oacute;n administrativa el asociado que cometa alguna causal de las contempladas en el art&iacute;culo 60 de los estatutos vigentes, el procedimiento para la desvinculaci&oacute;n se realizar&aacute; bajos los par&aacute;metros del art&iacute;culo 20 de los Estatutos, en cuyo caso la Cooperativa dispondr&aacute; de la capacidad transportadora del veh&iacute;culo desvinculado. <strong>DECIMA SEXTA</strong>: La mora en el pago de las cuotas mensuales o de cualquier otra obligaci&oacute;n, causar&aacute; intereses moratorios equivalentes a la tasa m&aacute;xima legal autorizada. <strong>DECIMA S&Eacute;PTIMA</strong>: El ASOCIADO se compromete con la COOPERATIVA a dar aviso inmediato de los cambios de direcci&oacute;n e informaci&oacute;n personal. <strong>DECIMA OCTAVA</strong>: Se aclara que la venta del veh&iacute;culo a terceros, no implica para el asociado vendedor la cesi&oacute;n de sus aportes sociales, ni dem&aacute;s compromisos econ&oacute;micos que pueda llegar a tener. Tampoco implica la venta la a capacidad transportadora, pues solamente el asociado tiene pleno dominio sobre el veh&iacute;culo. <strong>DECIMA NOVENA</strong>: Quien se constituya en nuevo propietario, debe asociarse a la cooperativa de inmediato para tener derecho a la utilizaci&oacute;n de la capacidad transportadora y se someter&aacute; a los requisitos y tr&aacute;mites de ingresos a la COOPERATIVA como nuevo asociado, reserv&aacute;ndose la COOPERATIVA los derechos de admisi&oacute;n. La capacidad transportadora ser&aacute; siempre de la COOPERATIVA quien tiene la postad de poderla asignar de forma temporal a quienes cumplan con los requisitos para ser asociado. <strong>VIG&Eacute;SIMA</strong>: El presente contrato se perfecciona con el cumplimiento de todos los requisitos estipulados, por lo que deja sin efecto cualquier otro contrato suscrito entre las partes con anterioridad que verse sobre el mismo objeto, en constancia de lo anterior y en se&ntilde;al de asentimiento se firma el presente documento en dos (2) ejemplares del mismo tenor, en la ciudad de Oca&ntilde;a el fechaFirmaContrato.</p>', '2024-02-01 21:00:54', '2024-02-01 21:00:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacionnotificacioncorreo`
--

CREATE TABLE `informacionnotificacioncorreo` (
  `innocoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla informacion notificación correo',
  `innoconombre` varchar(50) NOT NULL COMMENT 'Nombre con el cual se consulta desde el sistema',
  `innocoasunto` varchar(120) NOT NULL COMMENT 'Asunto de la información que lleva notificación del correo',
  `innococontenido` longtext NOT NULL COMMENT 'Contenido de la información que lleva notificación del correo',
  `innocoenviarpiepagina` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si se va incluir el contenido de pie de pagina',
  `innocoenviarcopia` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina se se desea enviar copia al administrador',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informacionnotificacioncorreo`
--

INSERT INTO `informacionnotificacioncorreo` (`innocoid`, `innoconombre`, `innocoasunto`, `innococontenido`, `innocoenviarpiepagina`, `innocoenviarcopia`, `created_at`, `updated_at`) VALUES
(1, 'piePaginaCorreo', 'Pie página correo', '<p style=\"text-align: justify;\"><strong>Para su inter&eacute;s</strong>:&nbsp;<br /><br /><span style=\"font-size: 10pt;\">1. Este correo fue generado autom&aacute;ticamente, por favor no responda a &eacute;l.</span><br /><span style=\"font-size: 10pt;\">2. La informaci&oacute;n contenida en esta comunicaci&oacute;n es confidencial y s&oacute;lo puede ser utilizada por la persona natural o jur&iacute;dica a la cual est&aacute; dirigida.</span><br /><span style=\"font-size: 10pt;\">3. Si no es el destinatario autorizado, cualquier retenci&oacute;n, difusi&oacute;n, distribuci&oacute;n o copia de este mensaje, se encuentra prohibida y sancionada por la ley.</span><br /><span style=\"font-size: 10pt;\">4. Si por error recibe este mensaje, favor reenviar y borrar el mensaje recibido inmediatamente\". (Resoluci&oacute;n No. 089 de 2003 - Reglamento para el uso de Internet y Correo Electr&oacute;nico en el AGN. Art&iacute;culo 3&deg; numeral 5.</span></p>', 0, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(2, 'registroUsuario', '¡Bienvenido al CRM de siglaCooperativa!', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreUsuario</strong>, Es un placer informarle que a partir de este momento, la <strong>nombreEmpresa </strong>ha implementado un nuevo y avanzado Sistema de Gesti&oacute;n de Relaciones con el Cliente (CRM). Este sistema ha sido dise&ntilde;ado para mejorar significativamente nuestros procesos internos y proporcionarle a usted, como usuario, una experiencia m&aacute;s eficiente y personalizada.<br><br></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">A continuaci&oacute;n, encontrar&aacute; los detalles clave de esta actualizaci&oacute;n:<br><br></p>\r\n<p class=\"MsoNormal\">URL del Sistema: <strong><a href=\"urlSistema\" target=\"_blank\">urlSistema</a> </strong><br>Usuario del CRM: <strong>usuarioSistema</strong><br>Credenciales de acceso: <strong>contrasenaSistema</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br>Este nuevo CRM le permitir&aacute;:</p>\r\n<ul>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Acceder a su informaci&oacute;n y estado de cuenta de manera r&aacute;pida y sencilla.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Realizar seguimiento de sus transacciones y solicitudes.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Comunicarse de manera efectiva con nuestro equipo de trabajo.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Acceder a servicios y recursos exclusivos para miembros de la Cooperativa.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Le recomendamos que cambie su contrase&ntilde;a inicial despu&eacute;s de su primer inicio de sesi&oacute;n para garantizar la seguridad de su cuenta.</li>\r\n</ul>\r\n<p>Estamos comprometidos en brindarle un servicio de la m&aacute;s alta calidad, y creemos que esta actualizaci&oacute;n nos permitir&aacute; servirle de manera m&aacute;s efectiva. Si tiene alguna pregunta o necesita asistencia para familiarizarse con el nuevo sistema, no dude en ponerse en contacto con nuestro equipo de tecn&oacute;loga, quienes estar&aacute;n encantados de ayudarle.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Agradecemos su confianza en la nombreEmpresa y esperamos que este nuevo CRM mejore su experiencia con nosotros. Estamos seguros de que encontrar&aacute; el sistema m&aacute;s intuitivo y &uacute;til.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&iexcl;Bienvenido al futuro de la nombreEmpresa!</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\"><strong>nombreGerente</strong><br><strong>Gerente general </strong><br><strong>nombreEmpresa</strong></p>\r\n<p>&nbsp;</p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(3, 'solicitaFirmaDocumento', 'Solicitud de firma para del documento con referencia numeroDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreFeje</strong>, por medio de la presente me permito informar que se ha generado un documento importante que requiere su aprobaci&oacute;n y firma.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Detalles del documento:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Tipo de documento: <strong>tipoDocumental</strong><br>N&uacute;mero de documento: <strong>numeroDocumental</strong><br>Fecha de generaci&oacute;n: <strong>fechaDocumento</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Por favor, revise el documento y proceda a firmarlo utilizando la plataforma de CRM de nuestra cooperativa. Si tiene alguna pregunta o inquietud sobre el contenido del documento o el proceso de firma, no dude en contactarme.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Su cooperaci&oacute;n en este asunto es altamente apreciada y fundamental para avanzar en este proceso de manera oportuna.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Quedo a su disposici&oacute;n para cualquier aclaraci&oacute;n adicional que pueda necesitar.<br><br></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,<br><br></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><em><strong>nombreUsuario</strong></em><br><em><strong>cargoUsuario</strong></em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(4, 'anularSolicitudFirmaDocumento', 'Revisión y ajustes necesarios para documento con referencia numeroDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreFeje</strong>, le informo que el documento <strong>tipoDocumental </strong>con el n&uacute;mero <strong>numeroDocumental</strong>, que est&aacute; programado para su firma, requiere algunos ajustes y revisiones antes de que podamos proceder con el proceso de firma.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Detalles del documento:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Tipo de documento: <strong>tipoDocumental</strong><br>N&uacute;mero de documento: <strong>numeroDocumental</strong><br>Fecha de generaci&oacute;n: <strong>fechaDocumento</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Los ajustes necesarios est&aacute;n relacionados con &ldquo;<em>observacionAnulacionFirma</em>&rdquo;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Tan pronto como realice los ajustes, estaremos listos para proceder con el proceso de firma.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Quedo a su disposici&oacute;n para cualquier aclaraci&oacute;n adicional que pueda necesitar.<br><br></p>\r\n<p class=\"MsoNormal\" style=\"mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; text-align: justify; line-height: normal;\"><span style=\"font-size: 12.0pt; font-family: \'Times New Roman\',serif; mso-fareast-font-family: \'Times New Roman\'; mso-font-kerning: 0pt; mso-ligatures: none; mso-fareast-language: ES-CO;\">Atentamente,<br><br></span></p>\r\n<p class=\"MsoNormal\" style=\"mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; text-align: justify; line-height: normal;\"><strong><em>nombreUsuario</em></strong><br><strong><em>cargoUsuario</em></strong></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(5, 'notificarEnvioDocumento', 'Envío de documento digital de la Cooperativa siglaCooperativa con referencia numeroDocumental', '<p style=\"text-align: justify;\">Estimado/a <strong>nombreUsuario</strong>,</p>\r\n<p style=\"text-align: justify;\">Por medio de la presente nos permitimos informar que la dependencia de <strong>nombreDependencia </strong>de la <strong>nombreEmpresa </strong>ha enviado un documento en formato digital. Este archivo adjunto contiene la informaci&oacute;n requerida y puede ser revisado en su dispositivo electr&oacute;nico.</p>\r\n<p style=\"text-align: justify;\"><br>Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nuestro equipo de soporte.</p>\r\n<p style=\"text-align: justify;\"><br>Agradecemos su colaboraci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p>Atentamente,</p>\r\n<p>&nbsp;</p>\r\n<p><em><strong>jefeDependencia</strong></em><br><em><strong>nombreEmpresa</strong></em><br><em><strong>nombreDependencia</strong></em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(6, 'notificarFirmadoDocumento', 'Solicitud de token de verificación para el firmado del documento numeroDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreJefe</strong>, para avanzar con el proceso de firma electr&oacute;nica del documento <strong>numeroDocumental</strong>, es necesario que ingrese el siguiente c&oacute;digo de verificaci&oacute;n:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><strong>C&oacute;digo de Verificaci&oacute;n:&nbsp;<em>tokenAcceso</em></strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br>Tenga en cuenta que este token de verificaci&oacute;n ser&aacute; v&aacute;lido durante los pr&oacute;ximos&nbsp;<strong>tiempoToken </strong>minutos. Si transcurre este tiempo sin completar el proceso, deber&aacute; solicitar un nuevo token.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Por favor, acceda a nuestra plataforma y proporcione el token que le hemos proporcionado. Luego, haga clic en el bot&oacute;n de firma para completar el proceso.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Gracias por su colaboraci&oacute;n y compromiso con la seguridad de nuestros servicios.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Administrador del CRM</p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(7, 'notificarRegistroRadicado', 'Nuevo documento radicado en la Ventanilla Única Virtual con consecutivo numeroRadicado', '<p style=\"text-align: justify;\">Estimado <strong>nombreUsuario</strong>, agradecemos el uso del servicio prestado por la Ventanilla &Uacute;nica de la nombreEmpresa. Queremos informarle que su documento ha sido radicado con los siguientes detalles:</p>\r\n<p style=\"text-align: justify;\"><em>N&uacute;mero de radicado:&nbsp; &nbsp;<strong>numeroRadicado</strong></em><br><em>Fecha de radicado:&nbsp; &nbsp; &nbsp; &nbsp;<strong>fechaRadicado</strong></em><br><em>Destino:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>nombreDependencia</strong></em><br><em>Radicado por:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>nombreFuncionario</strong></em></p>\r\n<p style=\"text-align: justify;\">Le recordamos que la funci&oacute;n de la Ventanilla &Uacute;nica es recibir, radicar y redireccionar su solicitud, cumpliendo con los criterios b&aacute;sicos, ante la instancia correspondiente. La respuesta ser&aacute; proporcionada por la oficina indicada en su comunicaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">Quedamos a su disposici&oacute;n para cualquier consulta adicional.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Ventanilla &Uacute;nica<br>nombreEmpresa</p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(8, 'notificarRadicadoDocumento', 'Nuevo documento radicado con consecutivo numeroRadicado', '<p style=\"text-align: justify;\">Estimado usuario de <strong>nombreDependencia</strong>, les informamos que ha llegado un nuevo documento a trav&eacute;s de la Ventanilla &Uacute;nica, el cual ha sido debidamente radicado con los siguientes detalles:</p>\r\n<p style=\"text-align: justify;\">Radicado:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>numeroRadicado</strong><br>Fecha de recepci&oacute;n:&nbsp; <strong>fechaRadicado</strong><br>Destino:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>nombreDependencia</strong><br>Radicado por:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>nombreFuncionario</strong></p>\r\n<p style=\"text-align: justify;\">Queremos recordarles que, de acuerdo con los procedimientos, se tiene un plazo de 15 d&iacute;as h&aacute;biles para proporcionar una respuesta a esta solicitud. Este plazo iniciar&aacute; a partir del d&iacute;a siguiente a la radicaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">Por favor, tomen en cuenta que para acceder al contenido completo, les recomendamos ingresar al nuestro CRM institucional.</p>\r\n<p style=\"text-align: justify;\">Agradecemos su compromiso y dedicaci&oacute;n en pro del mejoramiento continuo de nuestros procesos.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Ventanilla &Uacute;nica<br>nombreEmpresa</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(9, 'notificarFirmaTipoDocumental', 'Proceso de firmado exitoso del tipo documental tipoDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Estimado usuario de la dependencia <strong>nombreDependencia</strong>, por medio de la presente me permito informar que el tipo documental \"<strong>tipoDocumental</strong>\"&nbsp;<strong>&nbsp;</strong>ha sido correctamente firmado y est&aacute; listo para avanzar al siguiente paso en el proceso.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Por favor, procede con el sellamiento y cualquier otra acci&oacute;n necesaria para completar este proceso de manera satisfactoria.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en contactarme. Agradezco tu atenci&oacute;n y dedicaci&oacute;n en este asunto.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Atentamente,<br><br></span></p>\r\n<p class=\"MsoNormal\"><em><strong><span lang=\"ES\" style=\"mso-ansi-language: ES;\">nombreJefe<br></span><span lang=\"ES\" style=\"mso-ansi-language: ES;\">cargoJefe</span></strong></em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(10, 'notificarRegistroServicioEspecial', 'Envío de planilla del servicio especial número numeroPlanilla', '<p style=\"text-align: justify;\">Estimado usuario nombreResponsable,</p>\r\n<p style=\"text-align: justify;\">Es un placer para nosotros enviarte la planilla correspondiente al servicio especial solicitado. La planilla est&aacute; adjunta a este correo electr&oacute;nico.</p>\r\n<p style=\"text-align: justify;\">Dentro de la planilla encontrar&aacute;s un c&oacute;digo QR que puede ser escaneado para descargar una copia digital del documento cuando lo necesites.</p>\r\n<p style=\"text-align: justify;\">Te recordamos que desde la administraci&oacute;n de la cooperativa estamos comprometidos con brindarte un servicio de calidad. &iexcl;Te deseamos un feliz viaje y esperamos tenerte de vuelta pronto!</p>\r\n<p style=\"text-align: justify;\">Quedamos a tu disposici&oacute;n para cualquier consulta o asistencia adicional.<br><br></p>\r\n<p style=\"text-align: justify;\">Cordialmente,<br><br></p>\r\n<p style=\"text-align: justify;\">Administraci&oacute;n del ERP HACARITAMA.</p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(11, 'notificarRegistroSolicitudCredito', 'Confirmación de registro de solicitud de crédito por $valorCredito', '<p dir=\"ltr\" style=\"text-align: justify;\">Estimado <strong>nombreSolicitante</strong>, es un gusto saludarte. En representaci&oacute;n de la <strong>COOPERATIVA DE TRANSPORTADORES HACARITAMA</strong>, queremos informarte que hemos registrado con &eacute;xito tu solicitud de cr&eacute;dito por un monto de $ <strong>valorCredito </strong>en nuestro sistema. Dicha solicitud ha sido enviada al &aacute;rea correspondiente para su verificaci&oacute;n y aprobaci&oacute;n.</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Te mantendremos informado sobre cualquier avance o decisi&oacute;n que se tome respecto a tu solicitud.</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Agradecemos sinceramente tu confianza en nuestra cooperativa y quedamos a tu disposici&oacute;n para cualquier consulta o asistencia adicional que puedas necesitar.</p>\r\n<p style=\"text-align: justify;\"><strong>&nbsp;</strong></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\"><strong id=\"docs-internal-guid-c6cbccdb-7fff-a8b1-b26b-f27c210a6db7\"><br><br>nombrePersonaCartera<br>COOPERATIVA DE TRANSPORTADORES HACARITAMA</strong></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(12, 'notificarDecisionSolicitudCredito', 'Verificación de solicitud de crédito para aprobación por un valor de $ valorCredito', '<p dir=\"ltr\" style=\"text-align: justify;\">Estimado <strong id=\"docs-internal-guid-ffd64544-7fff-6b2f-0a74-6a960fd91ccb\">nombreGerente</strong>, por medio de la presente me permito informarle que hemos recibido la solicitud de cr&eacute;dito por un monto de $ <strong>valorCredito </strong>presentada por <strong id=\"docs-internal-guid-ffd64544-7fff-6b2f-0a74-6a960fd91ccb\">nombreSolicitante</strong>. La solicitud ha sido registrada y se encuentra en proceso de verificaci&oacute;n.</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><br>Detalles de la solicitud:</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Solicitante: <strong>nombreSolicitante<br></strong>Monto solicitado: $ <strong>valorCredito<br></strong>L&iacute;nea de cr&eacute;dito: <strong>lineaCredito</strong></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Te solicitamos realizar la revisi&oacute;n correspondiente y tomar la decisi&oacute;n adecuada en cuanto a la aprobaci&oacute;n o negaci&oacute;n de este cr&eacute;dito. Estamos comprometidos en proporcionar un servicio eficiente y velar por la satisfacci&oacute;n de nuestros asociados.</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Quedamos atentos a tu pronta respuesta y agradecemos tu dedicaci&oacute;n en este proceso.<br><br></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Cordialmente,<br><br></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><strong>nombrePersonaCartera</strong><br><strong>COOPERATIVA DE TRANSPORTADORES HACARITAMA</strong></p>\r\n<p>&nbsp;</p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(13, 'notificarAprobacionSolicitudCredito', 'Decisión tomada sobre la solicitud de crédito por un valor de $ valorCredito', '<p dir=\"ltr\" style=\"text-align: justify;\">Estimado <strong>nombreSolicitante</strong>,</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Es un placer saludarte. Queremos informarte que hemos completado la revisi&oacute;n de tu solicitud de cr&eacute;dito por un monto de $ <strong>valorCredito</strong>. Nos complace comunicarte que tu solicitud ha sido <strong>aprobada</strong>.&nbsp;</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Detalles de la decisi&oacute;n:</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Monto aprobado: $ <strong>montoAprobado<br></strong>Tasa de Inter&eacute;s: <strong>tasaInteres %<br></strong>Plazo: <strong>plazoCredito</strong></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">A continuaci&oacute;n te recomendamos tener en cuenta las siguientes observaciones: <strong>observacionesGenerales</strong>.</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><br>Agradecemos tu confianza en la <strong>COOPERATIVA DE TRANSPORTADORES HACARITAMA.</strong></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Cordialmente,</p>\r\n<p style=\"text-align: justify;\"><strong>&nbsp;</strong></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><strong>nombreGerente</strong><br><strong>COOPERATIVA DE TRANSPORTADORES HACARITAMA</strong></p>\r\n<p>&nbsp;</p>', 1, 1, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(14, 'notificarNegacionSolicitudCredito', 'Decisión tomada sobre la solicitud de crédito por un valor de $ valorCredito', '<p dir=\"ltr\" style=\"text-align: justify;\">Estimado <strong>nombreSolicitante</strong>,</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Es un placer saludarte. Queremos informarte que hemos completado la revisi&oacute;n de tu solicitud de cr&eacute;dito por un monto de $ <strong>valorCredito</strong>. Nos complace comunicarte que tu solicitud ha sido <strong>negada</strong>.&nbsp;</p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Lamentamos informarte que <strong>observacionesGenerales</strong>. Entendemos que esto puede ser decepcionante, pero estamos aqu&iacute; para ayudarte en cualquier otra consulta o proceso futuro.<strong><br></strong></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\">Agradecemos tu confianza en la <strong>COOPERATIVA DE TRANSPORTADORES HACARITAMA</strong>.</p>\r\n<p style=\"text-align: justify;\"><strong>&nbsp;<br></strong>Cordialmente,</p>\r\n<p style=\"text-align: justify;\"><strong>&nbsp;</strong></p>\r\n<p dir=\"ltr\" style=\"text-align: justify;\"><strong>nombreGerente</strong><br><strong>COOPERATIVA DE TRANSPORTADORES HACARITAMA</strong></p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', 1, 1, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(15, 'notificacionConfirmacionTiquete', 'Confirmación de compra - Detalles de su Tiquete Adjuntos', '<p class=\"MsoNormal\" style=\"text-align: justify;\"><em>Estimado/a<strong> nombreCliente,</strong></em></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Es un placer saludarte. Esperamos que te encuentre bien.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Nos complace informarte que tu compra de tiquete ha sido procesada con &eacute;xito. Adjunto a este correo encontrar&aacute;s los detalles completos de tu tiquete, incluyendo la informaci&oacute;n del viaje, fechas y cualquier otra especificaci&oacute;n relevante.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Agradecemos tu preferencia y confianza en nuestros servicios. Si tienes alguna pregunta, comentario o necesitas asistencia adicional, no dudes en ponerte en contacto con nosotros. Estamos aqu&iacute; para ayudarte.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Te deseamos un viaje seguro y placentero. &iexcl;Gracias por elegirnos!</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Cordial saludo,</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><em>Oficina de despacho</em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(16, 'notificacionConfirmacionEncomienda', 'Confirmación de Recepción de Encomienda', '<p>Estimado/a <em><strong>nombreCliente</strong></em>,</p>\r\n<p style=\"text-align: justify;\">Es un placer saludarte. Esperamos que te encuentres bien.</p>\r\n<p style=\"text-align: justify;\">Nos complace informarte que tu encomienda ha sido recibida con &eacute;xito. Adjunto a este correo encontrar&aacute;s los detalles completos del env&iacute;o, que incluyen informaci&oacute;n del remitente y destinatario, fechas y cualquier otra especificaci&oacute;n relevante.</p>\r\n<p style=\"text-align: justify;\">Agradecemos tu preferencia y confianza en nuestros servicios. Si tienes alguna pregunta, comentario o necesitas asistencia adicional, no dudes en ponerte en contacto con nosotros. Estamos aqu&iacute; para ayudarte en todo lo que necesites.</p>\r\n<p style=\"text-align: justify;\">&iexcl;Gracias por elegirnos!<br><br></p>\r\n<p style=\"text-align: justify;\">Cordialmente,<br><br></p>\r\n<p style=\"text-align: justify;\"><em>Oficina de Despacho</em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(17, 'notificarVencimientoLicencia', 'Próxima fecha de vencimiento de su licencia con número numeroLicencia', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreConductor</strong>, esperamos que se encuentre bien.&nbsp;<span style=\"mso-spacerun: yes;\">&nbsp;</span>Queremos informarle que la licencia con el n&uacute;mero <strong>numeroLicencia&nbsp;</strong>est&aacute; pr&oacute;xima a vencer.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><strong>Detalles de la licencia:</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><em>N&uacute;mero de licencia: <strong>numeroLicencia</strong></em><br><em>Fecha de expedici&oacute;n: <strong>fechaExpedicion</strong></em><br><em>Fecha de vencimiento: <strong>fechaVencimiento</strong></em></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Por favor, tome las medidas necesarias para renovar su licencia antes de la fecha de vencimiento para evitar cualquier inconveniente.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Si ya ha renovado su licencia, le agradecemos y le recordamos que actualice la informaci&oacute;n en nuestros registros.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nuestro equipo de soporte.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Gracias por su cooperaci&oacute;n.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><em><strong>nombreGerente</strong></em><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(18, 'notificarVencimientoSoat', 'Próxima fecha de vencimiento de su SOAT con número numeroPoliza', '<p style=\"text-align: justify;\">Estimado <strong>nombreAsociado</strong>, esperamos que se encuentre bien. &nbsp;Queremos informarle que su SOAT n&uacute;mero <strong>numeroPoliza</strong> est&aacute; pr&oacute;xima a vencer.</p>\r\n<p style=\"text-align: justify;\"><strong>Detalles del SOAT:</strong></p>\r\n<p style=\"text-align: justify;\"><em>N&uacute;mero de SOAT: <strong>numeroPoliza</strong></em><br><em>Fecha de inicial: <strong>fechaInicial</strong></em><br><em>Fecha de vencimiento: <strong>fechaVencimiento</strong></em></p>\r\n<p style=\"text-align: justify;\">Por favor, tome las medidas necesarias para renovar su SOAT antes de la fecha de vencimiento para evitar cualquier inconveniente.</p>\r\n<p style=\"text-align: justify;\">Si ya ha renovado su SOAT, le agradecemos y le recordamos que actualice la informaci&oacute;n en nuestros registros.</p>\r\n<p style=\"text-align: justify;\">Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nuestro equipo de soporte.</p>\r\n<p style=\"text-align: justify;\">Gracias por su cooperaci&oacute;n.</p>\r\n<div>&nbsp;</div>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><em><strong>nombreGerente</strong></em><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(19, 'notificarVencimientoCRT', 'Próxima fecha de vencimiento de su CRT con número  numeroCrt', '<p style=\"text-align: justify;\">Estimado <strong>nombreAsociado</strong>, esperamos que se encuentre bien. Queremos informarle que &eacute;l CRT con n&uacute;mero <strong>numeroCrt </strong>est&aacute; pr&oacute;xima a vencer.</p>\r\n<p style=\"text-align: justify;\"><strong>Detalles del CRT:</strong></p>\r\n<p style=\"text-align: justify;\"><em>N&uacute;mero de CRT: <strong>numeroCrt</strong></em><br><em>Fecha de inicial: <strong>fechaInicial</strong></em><br><em>Fecha de vencimiento: <strong>fechaVencimiento</strong></em></p>\r\n<p style=\"text-align: justify;\">Por favor, tome las medidas necesarias para renovar su CRT antes de la fecha de vencimiento para evitar cualquier inconveniente.</p>\r\n<p style=\"text-align: justify;\">Si ya ha renovado su CRT, le agradecemos y le recordamos que actualice la informaci&oacute;n en nuestros registros.</p>\r\n<p style=\"text-align: justify;\">Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nuestro equipo de soporte.</p>\r\n<p style=\"text-align: justify;\">Gracias por su cooperaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><em><strong>nombreGerente</strong></em><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(20, 'notificarVencimientoPolizas', 'Próxima fecha de vencimiento de su póliza con número numeroPolizaContractual', '<p style=\"text-align: justify;\">Estimado <strong>nombreAsociado</strong>, esperamos que se encuentre bien. Queremos informarle que la p&oacute;liza con n&uacute;mero <strong>numeroPolizaContractual </strong>est&aacute; pr&oacute;xima a vencer.</p>\r\n<p style=\"text-align: justify;\">Detalles del la p&oacute;liza:</p>\r\n<p style=\"text-align: justify;\"><em>N&uacute;mero de p&oacute;liza contractual: <strong>numeroPolizaContractual</strong></em><br><em>N&uacute;mero de p&oacute;liza extra contractual: <strong>numeroPolizaExtContractual</strong></em><br><em>Fecha de inicial: <strong>fechaInicial</strong></em><br><em>Fecha de vencimiento: <strong>fechaVencimiento</strong></em></p>\r\n<p style=\"text-align: justify;\">Por favor, tome las medidas necesarias para renovar su p&oacute;liza antes de la fecha de vencimiento para evitar cualquier inconveniente.</p>\r\n<p style=\"text-align: justify;\">Si ya ha renovado su p&oacute;liza, le agradecemos y le recordamos que actualice la informaci&oacute;n en nuestros registros.</p>\r\n<p style=\"text-align: justify;\">Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nuestro equipo de soporte.</p>\r\n<p style=\"text-align: justify;\">Gracias por su cooperaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><em><strong>nombreGerente</strong></em><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(21, 'notificarVencimientoTarjetaOperacion', 'Próxima fecha de vencimiento de su tarjeta de operación con número numeroTarjetaOperacion', '<p style=\"text-align: justify;\">Estimado <strong>nombreAsociado</strong>, esperamos que se encuentre bien. Queremos informarle que la tarjeta de operaci&oacute;n con n&uacute;mero <strong>numeroTarjetaOperacion &nbsp;</strong>est&aacute; pr&oacute;xima a vencer.</p>\r\n<p style=\"text-align: justify;\"><strong>Detalles de la tarjeta de operaci&oacute;n:</strong></p>\r\n<p style=\"text-align: justify;\"><em>N&uacute;mero de tarjeta de operaci&oacute;n: <strong>numeroTarjetaOperacion</strong></em><br><em>Fecha de inicial: <strong>fechaInicial</strong></em><br><em>Fecha de vencimiento: <strong>fechaVencimiento</strong></em></p>\r\n<p style=\"text-align: justify;\">Por favor, tome las medidas necesarias para renovar su tarjeta de operaci&oacute;n antes de la fecha de vencimiento para evitar cualquier inconveniente.</p>\r\n<p style=\"text-align: justify;\">Si ya ha renovado su tarjeta de operaci&oacute;n, le agradecemos y le recordamos que actualice la informaci&oacute;n en nuestros registros.</p>\r\n<p style=\"text-align: justify;\">Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nuestro equipo de soporte.</p>\r\n<p style=\"text-align: justify;\">Gracias por su cooperaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\"><strong><em>nombreGerente</em></strong><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(22, 'notificarVencimientoCuotaCredito', 'Recordatorio de mora del crédito número numeroCredito otorgado por la cooperativa', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreAsociado</strong>, por medio de la presente nos dirigimos a usted para informarle sobre el estado actual de su cr&eacute;dito n&uacute;mero <strong>numeroCredito</strong>, el cual fue realizado en la fecha <strong>fechaPrestamo</strong>.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Observamos que la cuota n&uacute;mero <strong>numeroCuota</strong>, con vencimiento el <strong>fechaVencimiento </strong>presenta un retraso actual de&nbsp;<strong>diasMora </strong>d&iacute;as. Entendemos que pueden surgir imprevistos, y queremos recordarle la importancia de realizar el pago correspondiente lo m&aacute;s pronto posible.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Para su conveniencia, adjuntamos los detalles de su estado de cuenta actualizado. Le instamos a revisar la informaci&oacute;n y proceder con el pago para evitar cargos adicionales por concepto de mora.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Agradecemos su pronta atenci&oacute;n a este asunto y quedamos a disposici&oacute;n para cualquier consulta o asistencia que pueda necesitar.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\">Atentamente,</p>\r\n<p class=\"MsoNormal\">&nbsp;</p>\r\n<p class=\"MsoNormal\"><em><strong>nombreGerente</strong></em><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(23, 'notificarSuspencionConductor', 'Suspensión como conductor por vencimiento de licencia número numeroLicencia', '<div style=\"text-align: justify;\">Estimado <strong>nombreConductor</strong>, por medio de la presente nos dirigimos a usted para informarle sobre una situaci&oacute;n importante relacionada con su licencia de conducir.<br><br></div>\r\n<div style=\"text-align: justify;\">Lamentablemente, hemos detectado que su licencia con n&uacute;mero <strong>numeroLicencia </strong>ha vencido en la fecha <strong>fechaVencimiento</strong>. Como medida preventiva, hemos suspendido su capacidad para cubrir rutas en la cooperativa hasta que regularice su situaci&oacute;n.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">La suspensi&oacute;n como conductor es una medida necesaria para cumplir con las normativas de seguridad y garantizar el bienestar de todos los miembros de nuestra cooperativa. Para levantar la suspensi&oacute;n y poder continuar con sus actividades como conductor de nuestra empresa, le solicitamos amablemente que renueve su licencia a la mayor brevedad posible.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">Entendemos que la renovaci&oacute;n de la licencia puede llevar tiempo, por lo que le instamos a iniciar el proceso inmediatamente. Si ya ha renovado su licencia, por favor, ignore este mensaje y proporci&oacute;nenos la documentaci&oacute;n actualizada.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">Si tiene alguna pregunta o necesita orientaci&oacute;n sobre el proceso de renovaci&oacute;n, no dude en comunicarse con nuestro departamento de recursos humanos.<br><br></div>\r\n<div style=\"text-align: justify;\">Agradecemos su comprensi&oacute;n y colaboraci&oacute;n en este asunto. La seguridad de nuestros conductores y pasajeros es nuestra prioridad, y confiamos en que tomar&aacute; las medidas necesarias para regularizar su situaci&oacute;n a la mayor brevedad posible.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">Quedamos a su disposici&oacute;n para cualquier consulta adicional que pueda tener.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">Cordial saludos,</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\"><em><strong>nombreGerente</strong></em><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></div>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(24, 'notificarSuspencionVehiculo', 'Suspensión de servicios por falta de documentación obligatoria del vehículo con número  numeroInterno', '<div style=\"text-align: justify;\">Estimado <strong>nombreAsociado</strong>, por medio de la presente nos dirigimos a usted para informarle sobre una situaci&oacute;n importante relacionada con su veh&iacute;culo registrado en nuestra cooperativa.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">De acuerdo con nuestros registros, hemos identificado que el veh&iacute;culo con placa <strong>placaVehiculo </strong>y n&uacute;mero interno <strong>numeroInterno </strong>actualmente se encuentra suspendido debido a la falta de documentaci&oacute;n obligatoria. Esta suspensi&oacute;n afecta la posibilidad de realizar cualquier tr&aacute;mite con la cooperativa hasta que se regularice la situaci&oacute;n.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">La documentaci&oacute;n faltante corresponde&nbsp;<strong>tipoDocumentacion </strong>la cual vence en la fecha <strong>fechaVencimiento</strong>. Para levantar la suspensi&oacute;n y poder continuar con sus actividades, le solicitamos amablemente que nos proporcione la documentaci&oacute;n requerida a la brevedad posible.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">Por favor, tenga en cuenta que este requisito es fundamental para cumplir con las normativas legales y garantizar la seguridad y el bienestar de todos nuestros asociados. Estamos comprometidos en brindar un servicio de calidad y, por ello, es indispensable contar con la documentaci&oacute;n actualizada.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">Si tiene alguna pregunta o requiere asistencia para completar este proceso, no dude en comunicarse con nuestro equipo de atenci&oacute;n al cliente. Estaremos encantados de ayudarle en lo que necesite.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">Agradecemos su pronta atenci&oacute;n a este asunto y su colaboraci&oacute;n para mantener al d&iacute;a la documentaci&oacute;n de su veh&iacute;culo. Valoramos su compromiso con la seguridad y el cumplimiento de las normativas.</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div style=\"text-align: justify;\">Cordial saludos,</div>\r\n<div style=\"text-align: justify;\">&nbsp;</div>\r\n<div>&nbsp;</div>\r\n<div><strong><em>nombreGerente</em></strong><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></div>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05'),
(25, 'notificacionLevantamientoSuspension', 'Levantamiento de suspensión del vehículo con número interno numeroInterno', '<p style=\"text-align: justify;\">Estimado <strong>nombreAsociado</strong>, por medio de la presente nos complace informarle que la suspensi&oacute;n de su veh&iacute;culo con la placa <strong>placaVehiculo </strong>y n&uacute;mero interno <strong>numeroInterno </strong>ha sido oficialmente levantada.</p>\r\n<p style=\"text-align: justify;\">La suspensi&oacute;n estuvo en vigor desde la fecha inicial <strong>fechaInicialSupencion </strong>hasta la fecha final <strong>fechaFinalSupencion</strong>, debido a <strong><em>motivoSuspencion</em></strong>. Tras revisar su situaci&oacute;n y cumplirse el plazo establecido, hemos procedido con el levantamiento de la suspensi&oacute;n.</p>\r\n<p style=\"text-align: justify;\">Agradecemos su comprensi&oacute;n y colaboraci&oacute;n durante este per&iacute;odo. Si tiene alguna pregunta o necesita informaci&oacute;n adicional, no dude en ponerse en contacto con nosotros.</p>\r\n<p style=\"text-align: justify;\">Le agradecemos por su atenci&oacute;n y colaboraci&oacute;n continua.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\"><br><em><strong>nombreGerente</strong></em><br><em>COOPERATIVA DE TRANSPORTADORES HACARITAMA</em></p>', 1, 0, '2024-02-01 20:59:05', '2024-02-01 20:59:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresosistema`
--

CREATE TABLE `ingresosistema` (
  `ingsisid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla ingreso sistema',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `ingsisipacceso` varchar(20) NOT NULL COMMENT 'Ip de la cual accede el usuario al sistema',
  `ingsisfechahoraingreso` datetime NOT NULL COMMENT 'Fecha y hora de ingreso al sistema',
  `ingsisfechahorasalida` datetime DEFAULT NULL COMMENT 'Fecha y hora de ingreso al sistema',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ingresosistema`
--

INSERT INTO `ingresosistema` (`ingsisid`, `usuaid`, `ingsisipacceso`, `ingsisfechahoraingreso`, `ingsisfechahorasalida`, `created_at`, `updated_at`) VALUES
(1, 2, '127.0.0.1', '2024-02-01 16:01:24', NULL, '2024-02-01 21:01:24', '2024-02-01 21:01:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentosfallidos`
--

CREATE TABLE `intentosfallidos` (
  `intfalid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla intentos fallidos',
  `intfalusurio` varchar(20) NOT NULL COMMENT 'Usuario que accede al sistema',
  `intfalipacceso` varchar(20) NOT NULL COMMENT 'Ip de la cual accede el usuario al sistema',
  `intfalfecha` datetime NOT NULL COMMENT 'Fecha y hora de ingreso al sistema',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lineacredito`
--

CREATE TABLE `lineacredito` (
  `lincreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla línea de crédito',
  `lincrenombre` varchar(100) NOT NULL COMMENT 'Nombre de la línea de crédito',
  `lincretasanominal` decimal(6,2) DEFAULT NULL COMMENT 'Tasa nominal para línea de crédito',
  `lincremontominimo` varchar(10) NOT NULL COMMENT 'Monto mínimo de la línea de crédito',
  `lincremontomaximo` varchar(10) NOT NULL COMMENT 'Monto máximo de la línea de crédito',
  `lincreplazomaximo` varchar(3) NOT NULL DEFAULT '1' COMMENT 'Plazo máximo en meses de la línea de crédito',
  `lincreactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la línea de crédito se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajeimpresion`
--

CREATE TABLE `mensajeimpresion` (
  `menimpid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla mensaje impresión',
  `menimpnombre` varchar(50) NOT NULL COMMENT 'Nombre del mensaje de impresión',
  `menimpvalor` varchar(500) DEFAULT NULL COMMENT 'Valor del mensaje de impresión',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `mensajeimpresion`
--

INSERT INTO `mensajeimpresion` (`menimpid`, `menimpnombre`, `menimpvalor`, `created_at`, `updated_at`) VALUES
(1, 'TIQUETES', '*** FELIZ NAVIDAD Y PROSPERO AÑO 2024 ***', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'PLANILLA', '*** FELIZ VIAJE Y PRONTO REGRESO ***', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'ENCOMIENDAS', 'Su encomienda será tratada con la máxima gentileza posible', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'RECAUDO', 'Agradecemos su contribución y compromiso con lo pactado. ¡Gracias!', '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(2, '2023_08_28_084042_create_tipo_identificacion', 1),
(3, '2023_08_28_084043_create_tipo_documental', 1),
(4, '2023_08_28_084044_create_tipo_despedida', 1),
(5, '2023_08_28_084045_create_tipo_saludo', 1),
(6, '2023_08_28_084046_create_tipo_estado_documento', 1),
(7, '2023_08_28_084047_create_tipo_destino', 1),
(8, '2023_08_28_084048_create_tipo_persona_documental', 1),
(9, '2023_08_28_084049_create_tipo_tramite', 1),
(10, '2023_08_28_084050_create_tipo_medio', 1),
(11, '2023_08_28_084051_create_tipo_acta', 1),
(12, '2023_08_28_084052_create_tipo_persona', 1),
(13, '2023_08_28_084053_create_tipo_estado_radicacion_documento_entrante', 1),
(14, '2023_08_28_084054_create_tipo_estante_archivador', 1),
(15, '2023_08_28_084055_create_tipo_caja_ubicacion', 1),
(16, '2023_08_28_084056_create_tipo_carpeta_ubicacion', 1),
(17, '2023_08_28_085009_create_informacion_notificacion_correo', 1),
(18, '2023_08_28_085010_create_informacion_configuracion_correo', 1),
(19, '2023_08_28_085011_create_cargo_laboral', 1),
(20, '2023_08_28_085012_create_departamento', 1),
(21, '2023_08_28_085013_create_municipio', 1),
(22, '2023_08_28_085014_create_persona', 1),
(23, '2023_08_28_085016_create_agencia', 1),
(24, '2023_08_28_085017_create_caja', 1),
(25, '2023_08_28_085110_create_usuario', 1),
(26, '2023_08_28_085111_create_ingreso_sistema', 1),
(27, '2023_08_28_085112_create_intentos_fallidos', 1),
(28, '2023_08_28_085113_create_historial_contrasena', 1),
(29, '2023_08_28_086105_create_serie_documental', 1),
(30, '2023_08_28_086106_create_sub_serie_documental', 1),
(31, '2023_08_28_086110_create_dependencia', 1),
(32, '2023_08_28_086111_create_dependencia_persona', 1),
(33, '2023_08_28_086112_create_dependencia_sub_serie_documental', 1),
(34, '2023_08_28_086114_create_token_firma_persona', 1),
(35, '2023_08_31_043412_create_modulo', 1),
(36, '2023_08_31_043621_create_rol', 1),
(37, '2023_08_31_043639_create_funcionalidad', 1),
(38, '2023_08_31_043658_create_rol_funcionalidad', 1),
(39, '2023_08_31_043659_create_usuario_rol', 1),
(40, '2023_09_02_140139_create_empresa', 1),
(41, '2023_09_02_140140_create_festivo', 1),
(42, '2023_09_02_140141_create_mensaje_impresion', 1),
(43, '2023_09_02_140142_create_configuracion_encomienda', 1),
(44, '2023_09_02_140143_create_cuenta_contable', 1),
(45, '2023_09_07_050956_create_codigo_documental', 1),
(46, '2023_09_07_052612_create_codigo_documental_proceso', 1),
(47, '2023_09_07_052618_create_codigo_documental_proceso_acta', 1),
(48, '2023_09_07_055753_create_codigo_documental_proceso_certificado', 1),
(49, '2023_09_07_055759_create_codigo_documental_proceso_circular', 1),
(50, '2023_09_07_055806_create_codigo_documental_proceso_citacion', 1),
(51, '2023_09_07_055812_create_codigo_documental_proceso_constancia', 1),
(52, '2023_09_07_055817_create_codigo_documental_proceso_oficio', 1),
(53, '2023_09_09_102437_create_codigo_documental_proceso_firma', 1),
(54, '2023_09_09_102822_create_codigo_documental_proceso_anexo', 1),
(55, '2023_09_09_103332_create_codigo_documental_proceso_copia', 1),
(56, '2023_09_09_103333_create_codigo_documental_proceso_cambio_estado', 1),
(57, '2023_09_09_103333_create_codigo_documental_proceso_compartido', 1),
(58, '2023_09_09_103335_create_persona_radica_documento', 1),
(59, '2023_09_09_103336_create_radicacion_documento_entrante', 1),
(60, '2023_09_09_103337_create_radicacion_documento_entrante_anexo', 1),
(61, '2023_09_09_103338_create_radicacion_documento_entrante_dependencia', 1),
(62, '2023_09_09_103339_create_radicacion_documento_entrante_cambio_estado', 1),
(63, '2023_09_09_103340_create_codigo_documental_proceso_radicacion_documento_entrante', 1),
(64, '2023_09_09_103345_create_archivo_historico', 1),
(65, '2023_09_09_103346_create_archivo_historico_digitalizado', 1),
(66, '2023_10_18_043200_create_informacion_general_pdf', 1),
(67, '2023_10_18_043301_create_tipo_estado_asociado', 1),
(68, '2023_10_18_043302_create_tipo_estado_solicitud_credito', 1),
(69, '2023_10_18_043303_create_tipo_estado_conductor', 1),
(70, '2023_10_18_043304_create_tipo_estado_colocacion', 1),
(71, '2023_10_18_043312_create_tipo_vehiculo', 1),
(72, '2023_10_18_043313_create_tipo_referencia_vehiculo', 1),
(73, '2023_10_18_043314_create_tipo_marca_vehiculo', 1),
(74, '2023_10_18_043315_create_tipo_conductor', 1),
(75, '2023_10_18_043316_create_tipo_color_vehiculo', 1),
(76, '2023_10_18_043317_create_tipo_carroceria_vehiculo', 1),
(77, '2023_10_18_043318_create_tipo_combustible_vehiculo', 1),
(78, '2023_10_18_043319_create_tipo_estado_vehiculo', 1),
(79, '2023_10_18_043320_create_tipo_modalidad_vehiculo', 1),
(80, '2023_10_18_043321_create_tipo_categoria_licencia', 1),
(81, '2023_10_18_043322_create_tipo_servicio_vehiculo', 1),
(82, '2023_10_18_043323_create_tipo_vehiculo_distribucion', 1),
(83, '2023_10_18_043324_create_tipo_sancion', 1),
(84, '2023_10_18_043325_create_entidad_financiera', 1),
(85, '2023_10_18_043326_create_proceso_automatico', 1),
(86, '2023_10_18_043401_create_asociado', 1),
(87, '2023_10_18_043402_create_asociado_cambio_estado', 1),
(88, '2023_10_18_043403_create_asociado_sancion', 1),
(89, '2023_10_18_043410_create_conductor', 1),
(90, '2023_10_18_043411_create_conductor_licencia', 1),
(91, '2023_10_18_043412_create_conductor_cambio_estado', 1),
(92, '2023_10_18_043415_create_vehiculo', 1),
(93, '2023_10_18_043416_create_vehiculo_crt', 1),
(94, '2023_10_18_043417_create_vehiculo_poliza', 1),
(95, '2023_10_18_043418_create_vehiculo_soat', 1),
(96, '2023_10_18_043419_create_vehiculo_tarjeta_operacion', 1),
(97, '2023_10_18_043420_create_conductor_vehiculo', 1),
(98, '2023_10_18_043421_create_vehiculo_cambio_estado', 1),
(99, '2023_10_18_043422_create_vehiculo_responsabilidad', 1),
(100, '2023_10_18_043423_create_vehiculo_suspendido', 1),
(101, '2023_10_18_043515_create_vehiculo_contrato', 1),
(102, '2023_10_18_053310_create_linea_credito', 1),
(103, '2023_10_18_053311_create_solicitud_credito', 1),
(104, '2023_10_18_053315_create_solicitud_credito_cambio_estado', 1),
(105, '2023_10_18_053320_create_colocacion', 1),
(106, '2023_10_18_053321_create_colocacion_liquidacion', 1),
(107, '2023_10_18_053322_create_colocacion_cambio_estado', 1),
(108, '2023_11_11_082405_create_tipo_contrato_servicio_especial', 1),
(109, '2023_11_11_082406_create_tipo_convenio_servicio_especial', 1),
(110, '2023_11_11_082407_create_persona_contrato_servicio_esp', 1),
(111, '2023_11_11_082408_create_contrato_servicio_especial', 1),
(112, '2023_11_11_082409_create_contrato_servicio_especial_vehi', 1),
(113, '2023_11_11_082410_create_contrato_servicio_especial_cond', 1),
(114, '2023_11_20_170901_create_tipo_encomienda', 1),
(115, '2023_11_20_170902_create_tipo_estado_encomienda', 1),
(116, '2023_11_20_170929_create_ruta', 1),
(117, '2023_11_20_170931_create_ruta_nodo', 1),
(118, '2023_11_20_170935_create_tarifa_tiquete', 1),
(119, '2023_11_20_170940_create_persona_servicio', 1),
(120, '2023_11_20_170941_create_planilla_ruta', 1),
(121, '2023_11_20_170945_create_encomienda', 1),
(122, '2023_11_20_170946_create_encomienda_cambio_estado', 1),
(123, '2023_11_20_170951_create_tiquete', 1),
(124, '2023_11_20_170952_create_tiquete_puesto', 1),
(125, '2024_01_17_160920_create_movimiento_caja', 1),
(126, '2024_01_17_160922_create_comprobante_contable', 1),
(127, '2024_01_19_145721_create_comprobante_contable_detalle', 1),
(128, '2024_01_19_145722_create_consignacion_bancaria', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `moduid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla módulo',
  `modunombre` varchar(30) NOT NULL COMMENT 'Nombre del módulo',
  `moduicono` varchar(30) DEFAULT NULL COMMENT 'Clase de css para montar en el link del módulo',
  `moduorden` smallint(6) NOT NULL COMMENT 'Orden del en el árbol del menú que se muesra el módulo',
  `moduactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el módulo encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`moduid`, `modunombre`, `moduicono`, `moduorden`, `moduactivo`, `created_at`, `updated_at`) VALUES
(1, 'Configuración', 'settings_applications', 1, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(2, 'Gestionar', 'ac_unit_icon', 2, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(3, 'Producción documental', 'menu_book_icon', 3, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(4, 'Radicación', 'folder_special_icon', 4, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(5, 'Archivo histórico', 'insert_page_break_icon', 5, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(6, 'Asociados', 'person_search_icon', 6, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(7, 'Dirección transporte', 'drive_eta_icon', 7, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(8, 'Cartera', 'work_icon', 8, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(9, 'Despachos', 'send_time_extension_icon', 9, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(10, 'Caja', 'attach_money_icon', 10, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24'),
(11, 'Informes', 'assessment_icon', 11, 1, '2024-02-01 21:00:24', '2024-02-01 21:00:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientocaja`
--

CREATE TABLE `movimientocaja` (
  `movcajid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla movimiento caja',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `cajaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la caja',
  `movcajfechahoraapertura` datetime NOT NULL COMMENT 'Fecha y hora en la cual se abre la caja',
  `movcajsaldoinicial` decimal(10,2) NOT NULL COMMENT 'Saldo incial para abrir la caja',
  `movcajfechahoracierre` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se cierra la caja',
  `movcajsaldofinal` decimal(10,2) DEFAULT NULL COMMENT 'Saldo final con el que cierra la caja',
  `movcajcerradoautomaticamente` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la el movimeinto de la caja fue cerrada automaticamente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `muniid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla municipio',
  `munidepaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento',
  `municodigo` varchar(8) NOT NULL COMMENT 'Código del municipio',
  `muninombre` varchar(80) NOT NULL COMMENT 'Nombre del municipio',
  `munihacepresencia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la entidad hace presencia en este municipio',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`muniid`, `munidepaid`, `municodigo`, `muninombre`, `munihacepresencia`, `created_at`, `updated_at`) VALUES
(1, 1, '05001', 'Medellin', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(2, 1, '05002', 'Abejorral', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(3, 1, '05004', 'Abriaqui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(4, 1, '05021', 'Alejandria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(5, 1, '05030', 'Amaga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(6, 1, '05031', 'Amalfi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(7, 1, '05034', 'Andes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(8, 1, '05036', 'Angelopolis', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(9, 1, '05038', 'Angostura', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(10, 1, '05040', 'Anori', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(11, 1, '05042', 'Santafe de Antioquia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(12, 1, '05044', 'Anza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(13, 1, '05045', 'Apartado', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(14, 1, '05051', 'Arboletes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(15, 1, '05055', 'Argelia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(16, 1, '05059', 'Armenia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(17, 1, '05079', 'Barbosa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(18, 1, '05086', 'Belmira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(19, 1, '05088', 'Bello', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(20, 1, '05091', 'Betania', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(21, 1, '05093', 'Betulia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(22, 1, '05101', 'Ciudad Bolivar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(23, 1, '05107', 'Briceño', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(24, 1, '05113', 'Buritica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(25, 1, '05120', 'Caceres', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(26, 1, '05125', 'Caicedo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(27, 1, '05129', 'Caldas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(28, 1, '05134', 'Campamento', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(29, 1, '05138', 'Cañasgordas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(30, 1, '05142', 'Caracoli', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(31, 1, '05145', 'Caramanta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(32, 1, '05147', 'Carepa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(33, 1, '05148', 'El Carmen de Viboral', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(34, 1, '05150', 'Carolina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(35, 1, '05154', 'Caucasia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(36, 1, '05172', 'Chigorodo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(37, 1, '05190', 'Cisneros', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(38, 1, '05197', 'Cocorna', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(39, 1, '05206', 'Concepcion', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(40, 1, '05209', 'Concordia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(41, 1, '05212', 'Copacabana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(42, 1, '05234', 'Dabeiba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(43, 1, '05237', 'Don Matias', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(44, 1, '05240', 'Ebejico', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(45, 1, '05250', 'El Bagre', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(46, 1, '05264', 'Entrerrios', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(47, 1, '05266', 'Envigado', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(48, 1, '05282', 'Fredonia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(49, 1, '05284', 'Frontino', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(50, 1, '05306', 'Giraldo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(51, 1, '05308', 'Girardota', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(52, 1, '05310', 'Gomez Plata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(53, 1, '05313', 'Granada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(54, 1, '05315', 'Guadalupe', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(55, 1, '05318', 'Guarne', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(56, 1, '05321', 'Guatape', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(57, 1, '05347', 'Heliconia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(58, 1, '05353', 'Hispania', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(59, 1, '05360', 'Itagui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(60, 1, '05361', 'Ituango', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(61, 1, '05364', 'Jardin', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(62, 1, '05368', 'Jerico', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(63, 1, '05376', 'La Ceja', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(64, 1, '05380', 'La Estrella', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(65, 1, '05390', 'La Pintada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(66, 1, '05400', 'La Union', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(67, 1, '05411', 'Liborina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(68, 1, '05425', 'Maceo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(69, 1, '05440', 'Marinilla', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(70, 1, '05467', 'Montebello', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(71, 1, '05475', 'Murindo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(72, 1, '05480', 'Mutata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(73, 1, '05483', 'Nariño', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(74, 1, '05490', 'Necocli', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(75, 1, '05495', 'Nechi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(76, 1, '05501', 'Olaya', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(77, 1, '05541', 'Peðol', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(78, 1, '05543', 'Peque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(79, 1, '05576', 'Pueblorrico', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(80, 1, '05579', 'Puerto Berrio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(81, 1, '05585', 'Puerto Nare', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(82, 1, '05591', 'Puerto Triunfo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(83, 1, '05604', 'Remedios', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(84, 1, '05607', 'Retiro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(85, 1, '05615', 'Rionegro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(86, 1, '05628', 'Sabanalarga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(87, 1, '05631', 'Sabaneta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(88, 1, '05642', 'Salgar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(89, 1, '05647', 'San Andres De Cuerquia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(90, 1, '05649', 'San Carlos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(91, 1, '05652', 'San Francisco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(92, 1, '05656', 'San Jeronimo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(93, 1, '05658', 'San Jose De La Montaña', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(94, 1, '05659', 'San Juan De Uraba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(95, 1, '05660', 'San Luis', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(96, 1, '05664', 'San Pedro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(97, 1, '05665', 'San Pedro De Uraba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(98, 1, '05667', 'San Rafael', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(99, 1, '05670', 'San Roque', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(100, 1, '05674', 'San Vicente', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(101, 1, '05679', 'Santa Barbara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(102, 1, '05686', 'Santa Rosa De Osos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(103, 1, '05690', 'Santo Domingo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(104, 1, '05697', 'El Santuario', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(105, 1, '05736', 'Segovia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(106, 1, '05756', 'Sonson', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(107, 1, '05761', 'Sopetran', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(108, 1, '05789', 'Tamesis', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(109, 1, '05790', 'Taraza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(110, 1, '05792', 'Tarso', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(111, 1, '05809', 'Titiribi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(112, 1, '05819', 'Toledo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(113, 1, '05837', 'Turbo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(114, 1, '05842', 'Uramita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(115, 1, '05847', 'Urrao', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(116, 1, '05854', 'Valdivia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(117, 1, '05856', 'Valparaiso', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(118, 1, '05858', 'Vegachi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(119, 1, '05861', 'Venecia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(120, 1, '05873', 'Vigia Del Fuerte', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(121, 1, '05885', 'Yali', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(122, 1, '05887', 'Yarumal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(123, 1, '05890', 'Yolombo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(124, 1, '05893', 'Yondo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(125, 1, '05895', 'Zaragoza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(126, 2, '08001', 'Barranquilla', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(127, 2, '08078', 'Baranoa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(128, 2, '08137', 'Campo De La Cruz', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(129, 2, '08141', 'Candelaria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(130, 2, '08296', 'Galapa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(131, 2, '08372', 'Juan De Acosta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(132, 2, '08421', 'Luruaco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(133, 2, '08433', 'Malambo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(134, 2, '08436', 'Manati', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(135, 2, '08520', 'Palmar De Varela', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(136, 2, '08549', 'Piojo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(137, 2, '08558', 'Polonuevo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(138, 2, '08560', 'Ponedera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(139, 2, '08573', 'Puerto Colombia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(140, 2, '08606', 'Repelon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(141, 2, '08634', 'Sabanagrande', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(142, 2, '08638', 'Sabanalarga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(143, 2, '08675', 'Santa Lucia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(144, 2, '08685', 'Santo Tomas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(145, 2, '08758', 'Soledad', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(146, 2, '08770', 'Suan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(147, 2, '08832', 'Tubara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(148, 2, '08849', 'Usiacuri', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(149, 3, '11001', 'Bogotá, D.C.', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(150, 4, '13001', 'Cartagena', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(151, 4, '13006', 'Achi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(152, 4, '13030', 'Altos Del Rosario', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(153, 4, '13042', 'Arenal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(154, 4, '13052', 'Arjona', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(155, 4, '13062', 'Arroyohondo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(156, 4, '13074', 'Barranco De Loba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(157, 4, '13140', 'Calamar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(158, 4, '13160', 'Cantagallo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(159, 4, '13188', 'Cicuco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(160, 4, '13212', 'Cordoba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(161, 4, '13222', 'Clemencia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(162, 4, '13244', 'El Carmen De Bolivar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(163, 4, '13248', 'El Guamo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(164, 4, '13268', 'El Peñon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(165, 4, '13300', 'Hatillo De Loba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(166, 4, '13430', 'Magangue', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(167, 4, '13433', 'Mahates', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(168, 4, '13440', 'Margarita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(169, 4, '13442', 'Maria La Baja', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(170, 4, '13458', 'Montecristo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(171, 4, '13468', 'Mompos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(172, 4, '13490', 'Norosi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(173, 4, '13473', 'Morales', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(174, 4, '13549', 'Pinillos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(175, 4, '13580', 'Regidor', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(176, 4, '13600', 'Rio Viejo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(177, 4, '13620', 'San Cristobal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(178, 4, '13647', 'San Estanislao', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(179, 4, '13650', 'San Fernando', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(180, 4, '13654', 'San Jacinto', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(181, 4, '13655', 'San Jacinto del Cauca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(182, 4, '13657', 'San Juan Nepomuceno', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(183, 4, '13667', 'San Martin de Loba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(184, 4, '13670', 'San Pablo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(185, 4, '13673', 'Santa Catalina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(186, 4, '13683', 'Santa Rosa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(187, 4, '13688', 'Santa Rosa del Sur', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(188, 4, '13744', 'Simiti', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(189, 4, '13760', 'Soplaviento', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(190, 4, '13780', 'Talaigua Nuevo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(191, 4, '13810', 'Tiquisio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(192, 4, '13836', 'Turbaco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(193, 4, '13838', 'Turbana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(194, 4, '13873', 'Villanueva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(195, 4, '13894', 'Zambrano', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(196, 5, '15001', 'Tunja', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(197, 5, '15022', 'Almeida', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(198, 5, '15047', 'Aquitania', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(199, 5, '15051', 'Arcabuco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(200, 5, '15087', 'Belen', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(201, 5, '15090', 'Berbeo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(202, 5, '15092', 'Beteitiva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(203, 5, '15097', 'Boavita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(204, 5, '15104', 'Boyaca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(205, 5, '15106', 'Briceño', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(206, 5, '15109', 'Buenavista', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(207, 5, '15114', 'Busbanza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(208, 5, '15131', 'Caldas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(209, 5, '15135', 'Campohermoso', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(210, 5, '15162', 'Cerinza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(211, 5, '15172', 'Chinavita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(212, 5, '15176', 'Chiquinquira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(213, 5, '15180', 'Chiscas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(214, 5, '15183', 'Chita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(215, 5, '15185', 'Chitaraque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(216, 5, '15187', 'Chivata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(217, 5, '15189', 'Cienega', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(218, 5, '15204', 'Combita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(219, 5, '15212', 'Coper', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(220, 5, '15215', 'Corrales', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(221, 5, '15218', 'Covarachia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(222, 5, '15223', 'Cubara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(223, 5, '15224', 'Cucaita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(224, 5, '15226', 'Cuitiva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(225, 5, '15232', 'Chiquiza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(226, 5, '15236', 'Chivor', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(227, 5, '15238', 'Duitama', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(228, 5, '15244', 'El Cocuy', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(229, 5, '15248', 'El Espino', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(230, 5, '15272', 'Firavitoba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(231, 5, '15276', 'Floresta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(232, 5, '15293', 'Gachantiva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(233, 5, '15296', 'Gameza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(234, 5, '15299', 'Garagoa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(235, 5, '15317', 'Guacamayas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(236, 5, '15322', 'Guateque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(237, 5, '15325', 'Guayata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(238, 5, '15332', 'Gsican', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(239, 5, '15362', 'Iza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(240, 5, '15367', 'Jenesano', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(241, 5, '15368', 'Jerico', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(242, 5, '15377', 'Labranzagrande', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(243, 5, '15380', 'La Capilla', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(244, 5, '15401', 'La Victoria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(245, 5, '15403', 'La Uvita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(246, 5, '15407', 'Villa de Leyva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(247, 5, '15425', 'Macanal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(248, 5, '15442', 'Maripi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(249, 5, '15455', 'Miraflores', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(250, 5, '15464', 'Mongua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(251, 5, '15466', 'Mongui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(252, 5, '15469', 'Moniquira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(253, 5, '15476', 'Motavita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(254, 5, '15480', 'Muzo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(255, 5, '15491', 'Nobsa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(256, 5, '15494', 'Nuevo Colon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(257, 5, '15500', 'Oicata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(258, 5, '15507', 'Otanche', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(259, 5, '15511', 'Pachavita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(260, 5, '15514', 'Paez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(261, 5, '15516', 'Paipa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(262, 5, '15518', 'Pajarito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(263, 5, '15522', 'Panqueba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(264, 5, '15531', 'Pauna', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(265, 5, '15533', 'Paya', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(266, 5, '15537', 'Paz De Rio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(267, 5, '15542', 'Pesca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(268, 5, '15550', 'Pisba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(269, 5, '15572', 'Puerto Boyaca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(270, 5, '15580', 'Quipama', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(271, 5, '15599', 'Ramiriqui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(272, 5, '15600', 'Raquira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(273, 5, '15621', 'Rondon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(274, 5, '15632', 'Saboya', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(275, 5, '15638', 'Sachica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(276, 5, '15646', 'Samaca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(277, 5, '15660', 'San Eduardo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(278, 5, '15664', 'San Jose se Pare', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(279, 5, '15667', 'San Luis se Gaceno', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(280, 5, '15673', 'San Mateo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(281, 5, '15676', 'San Miguel se Sema', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(282, 5, '15681', 'San Pablo se Borbur', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(283, 5, '15686', 'Santana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(284, 5, '15690', 'Santa Maria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(285, 5, '15693', 'Santa Rosa se Viterbo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(286, 5, '15696', 'Santa Sofia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(287, 5, '15720', 'Sativanorte', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(288, 5, '15723', 'Sativasur', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(289, 5, '15740', 'Siachoque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(290, 5, '15753', 'Soata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(291, 5, '15755', 'Socota', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(292, 5, '15757', 'Socha', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(293, 5, '15759', 'Sogamoso', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(294, 5, '15761', 'Somondoco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(295, 5, '15762', 'Sora', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(296, 5, '15763', 'Sotaquira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(297, 5, '15764', 'Soraca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(298, 5, '15774', 'Susacon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(299, 5, '15776', 'Sutamarchan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(300, 5, '15778', 'Sutatenza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(301, 5, '15790', 'Tasco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(302, 5, '15798', 'Tenza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(303, 5, '15804', 'Tibana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(304, 5, '15806', 'Tibasosa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(305, 5, '15808', 'Tinjaca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(306, 5, '15810', 'Tipacoque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(307, 5, '15814', 'Toca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(308, 5, '15816', 'Togsi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(309, 5, '15820', 'Topaga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(310, 5, '15822', 'Tota', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(311, 5, '15832', 'Tunungua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(312, 5, '15835', 'Turmeque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(313, 5, '15837', 'Tuta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(314, 5, '15839', 'Tutaza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(315, 5, '15842', 'Umbita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(316, 5, '15861', 'Ventaquemada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(317, 5, '15879', 'Viracacha', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(318, 5, '15897', 'Zetaquira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(319, 6, '17001', 'Manizales', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(320, 6, '17013', 'Aguadas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(321, 6, '17042', 'Anserma', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(322, 6, '17050', 'Aranzazu', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(323, 6, '17088', 'Belalcazar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(324, 6, '17174', 'Chinchina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(325, 6, '17272', 'Filadelfia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(326, 6, '17380', 'La Dorada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(327, 6, '17388', 'La Merced', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(328, 6, '17433', 'Manzanares', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(329, 6, '17442', 'Marmato', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(330, 6, '17444', 'Marquetalia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(331, 6, '17446', 'Marulanda', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(332, 6, '17486', 'Neira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(333, 6, '17495', 'Norcasia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(334, 6, '17513', 'Pacora', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(335, 6, '17524', 'Palestina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(336, 6, '17541', 'Pensilvania', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(337, 6, '17614', 'Riosucio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(338, 6, '17616', 'Risaralda', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(339, 6, '17653', 'Salamina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(340, 6, '17662', 'Samana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(341, 6, '17665', 'San Jose', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(342, 6, '17777', 'Supia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(343, 6, '17867', 'Victoria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(344, 6, '17873', 'Villamaria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(345, 6, '17877', 'Viterbo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(346, 7, '18001', 'Florencia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(347, 7, '18029', 'Albania', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(348, 7, '18094', 'Belen se los Andaquies', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(349, 7, '18150', 'Cartagena del Chaira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(350, 7, '18205', 'Curillo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(351, 7, '18247', 'El Doncello', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(352, 7, '18256', 'El Paujil', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(353, 7, '18410', 'La Montañita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(354, 7, '18460', 'Milan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(355, 7, '18479', 'Morelia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(356, 7, '18592', 'Puerto Rico', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(357, 7, '18610', 'San Jose del Fragua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(358, 7, '18753', 'San Vicente del Caguan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(359, 7, '18756', 'Solano', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(360, 7, '18785', 'Solita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(361, 7, '18860', 'Valparaiso', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(362, 8, '19001', 'Popayan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(363, 8, '19022', 'Almaguer', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(364, 8, '19050', 'Argelia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(365, 8, '19075', 'Balboa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(366, 8, '19100', 'Bolivar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(367, 8, '19110', 'Buenos Aires', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(368, 8, '19130', 'Cajibio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(369, 8, '19137', 'Caldono', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(370, 8, '19142', 'Caloto', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(371, 8, '19212', 'Corinto', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(372, 8, '19256', 'El Tambo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(373, 8, '19290', 'Florencia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(374, 8, '19300', 'Guachene', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(375, 8, '19318', 'Guapi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(376, 8, '19355', 'Inza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(377, 8, '19364', 'Jambalo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(378, 8, '19392', 'La Sierra', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(379, 8, '19397', 'La Vega', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(380, 8, '19418', 'Lopez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(381, 8, '19450', 'Mercaderes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(382, 8, '19455', 'Miranda', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(383, 8, '19473', 'Morales', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(384, 8, '19513', 'Padilla', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(385, 8, '19517', 'Paez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(386, 8, '19532', 'Patia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(387, 8, '19533', 'Piamonte', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(388, 8, '19548', 'Piendamo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(389, 8, '19573', 'Puerto Tejada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(390, 8, '19585', 'Purace', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(391, 8, '19622', 'Rosas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(392, 8, '19693', 'San Sebastian', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(393, 8, '19698', 'Santander de Quilichao', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(394, 8, '19701', 'Santa Rosa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(395, 8, '19743', 'Silvia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(396, 8, '19760', 'Sotara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(397, 8, '19780', 'Suarez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(398, 8, '19785', 'Sucre', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(399, 8, '19807', 'Timbio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(400, 8, '19809', 'Timbiqui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(401, 8, '19821', 'Toribio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(402, 8, '19824', 'Totoro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(403, 8, '19845', 'Villa Rica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(404, 9, '20001', 'Valledupar', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(405, 9, '20011', 'Aguachica', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(406, 9, '20013', 'Agustin Codazzi', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(407, 9, '20032', 'Astrea', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(408, 9, '20045', 'Becerril', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(409, 9, '20060', 'Bosconia', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(410, 9, '20175', 'Chimichagua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(411, 9, '20178', 'Chiriguana', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(412, 9, '20228', 'Curumani', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(413, 9, '20238', 'El Copey', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(414, 9, '20250', 'El Paso', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(415, 9, '20295', 'Gamarra', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(416, 9, '20310', 'Gonzalez', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(417, 9, '20383', 'La Gloria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(418, 9, '20400', 'La Jagua De Ibirico', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(419, 9, '20443', 'Manaure', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(420, 9, '20517', 'Pailitas', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(421, 9, '20550', 'Pelaya', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(422, 9, '20570', 'Pueblo Bello', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(423, 9, '20614', 'Rio De Oro', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(424, 9, '20621', 'La Paz', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(425, 9, '20710', 'San Alberto', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(426, 9, '20750', 'San Diego', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(427, 9, '20770', 'San Martin', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(428, 9, '20787', 'Tamalameque', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(429, 10, '23001', 'Monteria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(430, 10, '23068', 'Ayapel', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(431, 10, '23079', 'Buenavista', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(432, 10, '23090', 'Canalete', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(433, 10, '23162', 'Cerete', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(434, 10, '23168', 'Chima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(435, 10, '23182', 'Chinu', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(436, 10, '23189', 'Cienaga De Oro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(437, 10, '23300', 'Cotorra', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(438, 10, '23350', 'La Apartada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(439, 10, '23417', 'Lorica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(440, 10, '23419', 'Los Cordobas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(441, 10, '23464', 'Momil', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(442, 10, '23466', 'Montelibano', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(443, 10, '23500', 'Moñitos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(444, 10, '23555', 'Planeta Rica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(445, 10, '23570', 'Pueblo Nuevo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(446, 10, '23574', 'Puerto Escondido', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(447, 10, '23580', 'Puerto Libertador', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(448, 10, '23586', 'Purisima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(449, 10, '23660', 'Sahagun', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(450, 10, '23670', 'San Andres Sotavento', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(451, 10, '23672', 'San Antero', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(452, 10, '23675', 'San Bernardo Del Viento', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(453, 10, '23678', 'San Carlos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(454, 10, '23682', 'San José De Uré', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(455, 10, '23686', 'San Pelayo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(456, 10, '23807', 'Tierralta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(457, 10, '23815', 'Tuchín', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(458, 10, '23855', 'Valencia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(459, 11, '25001', 'Agua De Dios', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(460, 11, '25019', 'Alban', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(461, 11, '25035', 'Anapoima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(462, 11, '25040', 'Anolaima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(463, 11, '25053', 'Arbelaez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(464, 11, '25086', 'Beltran', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(465, 11, '25095', 'Bituima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(466, 11, '25099', 'Bojaca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(467, 11, '25120', 'Cabrera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(468, 11, '25123', 'Cachipay', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(469, 11, '25126', 'Cajica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(470, 11, '25148', 'Caparrapi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(471, 11, '25151', 'Caqueza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(472, 11, '25154', 'Carmen De Carupa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(473, 11, '25168', 'Chaguani', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(474, 11, '25175', 'Chia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(475, 11, '25178', 'Chipaque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(476, 11, '25181', 'Choachi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(477, 11, '25183', 'Choconta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(478, 11, '25200', 'Cogua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(479, 11, '25214', 'Cota', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(480, 11, '25224', 'Cucunuba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(481, 11, '25245', 'El Colegio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(482, 11, '25258', 'El Peñon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(483, 11, '25260', 'El Rosal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(484, 11, '25269', 'Facatativa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(485, 11, '25279', 'Fomeque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(486, 11, '25281', 'Fosca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(487, 11, '25286', 'Funza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(488, 11, '25288', 'Fuquene', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(489, 11, '25290', 'Fusagasuga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(490, 11, '25293', 'Gachala', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(491, 11, '25295', 'Gachancipa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(492, 11, '25297', 'Gacheta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(493, 11, '25299', 'Gama', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(494, 11, '25307', 'Girardot', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(495, 11, '25312', 'Granada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(496, 11, '25317', 'Guacheta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(497, 11, '25320', 'Guaduas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(498, 11, '25322', 'Guasca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(499, 11, '25324', 'Guataqui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(500, 11, '25326', 'Guatavita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(501, 11, '25328', 'Guayabal De Siquima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(502, 11, '25335', 'Guayabetal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(503, 11, '25339', 'Gutierrez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(504, 11, '25368', 'Jerusalen', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(505, 11, '25372', 'Junin', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(506, 11, '25377', 'La Calera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(507, 11, '25386', 'La Mesa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(508, 11, '25394', 'La Palma', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(509, 11, '25398', 'La Peña', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(510, 11, '25402', 'La Vega', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(511, 11, '25407', 'Lenguazaque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(512, 11, '25426', 'Macheta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(513, 11, '25430', 'Madrid', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(514, 11, '25436', 'Manta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(515, 11, '25438', 'Medina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(516, 11, '25473', 'Mosquera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(517, 11, '25483', 'Nariño', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(518, 11, '25486', 'Nemocon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(519, 11, '25488', 'Nilo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(520, 11, '25489', 'Nimaima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(521, 11, '25491', 'Nocaima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(522, 11, '25506', 'Venecia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(523, 11, '25513', 'Pacho', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(524, 11, '25518', 'Paime', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(525, 11, '25524', 'Pandi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(526, 11, '25530', 'Paratebueno', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(527, 11, '25535', 'Pasca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(528, 11, '25572', 'Puerto Salgar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(529, 11, '25580', 'Puli', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(530, 11, '25592', 'Quebradanegra', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(531, 11, '25594', 'Quetame', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(532, 11, '25596', 'Quipile', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(533, 11, '25599', 'Apulo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(534, 11, '25612', 'Ricaurte', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(535, 11, '25645', 'San Antonio Del Tequendama', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(536, 11, '25649', 'San Bernardo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(537, 11, '25653', 'San Cayetano', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(538, 11, '25658', 'San Francisco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(539, 11, '25662', 'San Juan De Rio Seco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(540, 11, '25718', 'Sasaima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(541, 11, '25736', 'Sesquile', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(542, 11, '25740', 'Sibate', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(543, 11, '25743', 'Silvania', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(544, 11, '25745', 'Simijaca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(545, 11, '25754', 'Soacha', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(546, 11, '25758', 'Sopo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(547, 11, '25769', 'Subachoque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(548, 11, '25772', 'Suesca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(549, 11, '25777', 'Supata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(550, 11, '25779', 'Susa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(551, 11, '25781', 'Sutatausa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(552, 11, '25785', 'Tabio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(553, 11, '25793', 'Tausa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(554, 11, '25797', 'Tena', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(555, 11, '25799', 'Tenjo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(556, 11, '25805', 'Tibacuy', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(557, 11, '25807', 'Tibirita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(558, 11, '25815', 'Tocaima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(559, 11, '25817', 'Tocancipa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(560, 11, '25823', 'Topaipi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(561, 11, '25839', 'Ubala', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(562, 11, '25841', 'Ubaque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(563, 11, '25843', 'Villa De San Diego De Ubate', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(564, 11, '25845', 'Une', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(565, 11, '25851', 'Utica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(566, 11, '25862', 'Vergara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(567, 11, '25867', 'Viani', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(568, 11, '25871', 'Villagomez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(569, 11, '25873', 'Villapinzon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(570, 11, '25875', 'Villeta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(571, 11, '25878', 'Viota', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(572, 11, '25885', 'Yacopi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(573, 11, '25898', 'Zipacon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(574, 11, '25899', 'Zipaquira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(575, 12, '27001', 'Quibdo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(576, 12, '27006', 'Acandi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(577, 12, '27025', 'Alto Baudo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(578, 12, '27050', 'Atrato', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(579, 12, '27073', 'Bagado', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(580, 12, '27075', 'Bahia Solano', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(581, 12, '27077', 'Bajo Baudo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(582, 12, '27099', 'Bojaya', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(583, 12, '27135', 'El Canton Del San Pablo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(584, 12, '27150', 'Carmen Del Darien', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(585, 12, '27160', 'Certegui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(586, 12, '27205', 'Condoto', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(587, 12, '27245', 'El Carmen De Atrato', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(588, 12, '27250', 'El Litoral Del San Juan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(589, 12, '27361', 'Istmina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(590, 12, '27372', 'Jurado', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(591, 12, '27413', 'Lloro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(592, 12, '27425', 'Medio Atrato', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(593, 12, '27430', 'Medio Baudo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(594, 12, '27450', 'Medio San Juan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(595, 12, '27491', 'Novita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(596, 12, '27495', 'Nuqui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(597, 12, '27580', 'Rio Iro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(598, 12, '27600', 'Rio Quito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(599, 12, '27615', 'Riosucio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(600, 12, '27660', 'San Jose Del Palmar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(601, 12, '27745', 'Sipi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(602, 12, '27787', 'Tado', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(603, 12, '27800', 'Unguia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(604, 12, '27810', 'Union Panamericana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(605, 13, '41001', 'Neiva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(606, 13, '41006', 'Acevedo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(607, 13, '41013', 'Agrado', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(608, 13, '41016', 'Aipe', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(609, 13, '41020', 'Algeciras', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(610, 13, '41026', 'Altamira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(611, 13, '41078', 'Baraya', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(612, 13, '41132', 'Campoalegre', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(613, 13, '41206', 'Colombia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(614, 13, '41244', 'Elias', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(615, 13, '41298', 'Garzon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(616, 13, '41306', 'Gigante', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(617, 13, '41319', 'Guadalupe', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(618, 13, '41349', 'Hobo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(619, 13, '41357', 'Iquira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(620, 13, '41359', 'Isnos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(621, 13, '41378', 'La Argentina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(622, 13, '41396', 'La Plata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(623, 13, '41483', 'Nataga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(624, 13, '41503', 'Oporapa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(625, 13, '41518', 'Paicol', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(626, 13, '41524', 'Palermo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(627, 13, '41530', 'Palestina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(628, 13, '41548', 'Pital', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(629, 13, '41551', 'Pitalito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(630, 13, '41615', 'Rivera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(631, 13, '41660', 'Saladoblanco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(632, 13, '41668', 'San Agustin', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06');
INSERT INTO `municipio` (`muniid`, `munidepaid`, `municodigo`, `muninombre`, `munihacepresencia`, `created_at`, `updated_at`) VALUES
(633, 13, '41676', 'Santa Maria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(634, 13, '41770', 'Suaza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(635, 13, '41791', 'Tarqui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(636, 13, '41797', 'Tesalia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(637, 13, '41799', 'Tello', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(638, 13, '41801', 'Teruel', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(639, 13, '41807', 'Timana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(640, 13, '41872', 'Villavieja', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(641, 13, '41885', 'Yaguara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(642, 14, '44001', 'Riohacha', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(643, 14, '44035', 'Albania', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(644, 14, '44078', 'Barrancas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(645, 14, '44090', 'Dibulla', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(646, 14, '44098', 'Distraccion', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(647, 14, '44110', 'El Molino', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(648, 14, '44279', 'Fonseca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(649, 14, '44378', 'Hatonuevo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(650, 14, '44420', 'La Jagua Del Pilar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(651, 14, '44430', 'Maicao', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(652, 14, '44560', 'Manaure', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(653, 14, '44650', 'San Juan Del Cesar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(654, 14, '44847', 'Uribia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(655, 14, '44855', 'Urumita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(656, 14, '44874', 'Villanueva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(657, 15, '47001', 'Santa Marta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(658, 15, '47030', 'Algarrobo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(659, 15, '47053', 'Aracataca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(660, 15, '47058', 'Ariguani', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(661, 15, '47161', 'Cerro San Antonio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(662, 15, '47170', 'Chibolo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(663, 15, '47189', 'Cienaga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(664, 15, '47205', 'Concordia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(665, 15, '47245', 'El Banco', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(666, 15, '47258', 'El Piñon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(667, 15, '47268', 'El Reten', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(668, 15, '47288', 'Fundacion', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(669, 15, '47318', 'Guamal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(670, 15, '47460', 'Nueva Granada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(671, 15, '47541', 'Pedraza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(672, 15, '47545', 'Pijiño Del Carmen', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(673, 15, '47551', 'Pivijay', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(674, 15, '47555', 'Plato', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(675, 15, '47570', 'Puebloviejo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(676, 15, '47605', 'Remolino', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(677, 15, '47660', 'Sabanas De San Angel', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(678, 15, '47675', 'Salamina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(679, 15, '47692', 'San Sebastian De Buenavista', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(680, 15, '47703', 'San Zenon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(681, 15, '47707', 'Santa Ana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(682, 15, '47720', 'Santa Barbara De Pinto', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(683, 15, '47745', 'Sitionuevo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(684, 15, '47798', 'Tenerife', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(685, 15, '47960', 'Zapayan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(686, 15, '47980', 'Zona Bananera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(687, 16, '50001', 'Villavicencio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(688, 16, '50006', 'Acacias', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(689, 16, '50110', 'Barranca De Upia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(690, 16, '50124', 'Cabuyaro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(691, 16, '50150', 'Castilla La Nueva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(692, 16, '50223', 'Cubarral', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(693, 16, '50226', 'Cumaral', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(694, 16, '50245', 'El Calvario', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(695, 16, '50251', 'El Castillo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(696, 16, '50270', 'El Dorado', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(697, 16, '50287', 'Fuente De Oro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(698, 16, '50313', 'Granada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(699, 16, '50318', 'Guamal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(700, 16, '50325', 'Mapiripan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(701, 16, '50330', 'Mesetas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(702, 16, '50350', 'La Macarena', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(703, 16, '50370', 'Uribe', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(704, 16, '50400', 'Lejanias', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(705, 16, '50450', 'Puerto Concordia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(706, 16, '50568', 'Puerto Gaitan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(707, 16, '50573', 'Puerto Lopez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(708, 16, '50577', 'Puerto Lleras', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(709, 16, '50590', 'Puerto Rico', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(710, 16, '50606', 'Restrepo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(711, 16, '50680', 'San Carlos De Guaroa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(712, 16, '50683', 'San Juan De Arama', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(713, 16, '50686', 'San Juanito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(714, 16, '50689', 'San Martin', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(715, 16, '50711', 'Vistahermosa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(716, 17, '52001', 'Pasto', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(717, 17, '52019', 'Alban', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(718, 17, '52022', 'Aldana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(719, 17, '52036', 'Ancuya', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(720, 17, '52051', 'Arboleda', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(721, 17, '52079', 'Barbacoas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(722, 17, '52083', 'Belen', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(723, 17, '52110', 'Buesaco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(724, 17, '52203', 'Colon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(725, 17, '52207', 'Consaca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(726, 17, '52210', 'Contadero', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(727, 17, '52215', 'Cordoba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(728, 17, '52224', 'Cuaspud', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(729, 17, '52227', 'Cumbal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(730, 17, '52233', 'Cumbitara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(731, 17, '52240', 'Chachagsi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(732, 17, '52250', 'El Charco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(733, 17, '52254', 'El Peñol', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(734, 17, '52256', 'El Rosario', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(735, 17, '52258', 'El Tablon De Gomez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(736, 17, '52260', 'El Tambo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(737, 17, '52287', 'Funes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(738, 17, '52317', 'Guachucal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(739, 17, '52320', 'Guaitarilla', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(740, 17, '52323', 'Gualmatan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(741, 17, '52352', 'Iles', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(742, 17, '52354', 'Imues', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(743, 17, '52356', 'Ipiales', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(744, 17, '52378', 'La Cruz', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(745, 17, '52381', 'La Florida', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(746, 17, '52385', 'La Llanada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(747, 17, '52390', 'La Tola', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(748, 17, '52399', 'La Union', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(749, 17, '52405', 'Leiva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(750, 17, '52411', 'Linares', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(751, 17, '52418', 'Los Andes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(752, 17, '52427', 'Magsi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(753, 17, '52435', 'Mallama', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(754, 17, '52473', 'Mosquera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(755, 17, '52480', 'Nariño', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(756, 17, '52490', 'Olaya Herrera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(757, 17, '52506', 'Ospina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(758, 17, '52520', 'Francisco Pizarro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(759, 17, '52540', 'Policarpa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(760, 17, '52560', 'Potosi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(761, 17, '52565', 'Providencia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(762, 17, '52573', 'Puerres', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(763, 17, '52585', 'Pupiales', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(764, 17, '52612', 'Ricaurte', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(765, 17, '52621', 'Roberto Payan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(766, 17, '52678', 'Samaniego', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(767, 17, '52683', 'Sandona', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(768, 17, '52685', 'San Bernardo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(769, 17, '52687', 'San Lorenzo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(770, 17, '52693', 'San Pablo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(771, 17, '52694', 'San Pedro De Cartago', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(772, 17, '52696', 'Santa Barbara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(773, 17, '52699', 'Santacruz', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(774, 17, '52720', 'Sapuyes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(775, 17, '52786', 'Taminango', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(776, 17, '52788', 'Tangua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(777, 17, '52835', 'San Andres De Tumaco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(778, 17, '52838', 'Tuquerres', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(779, 17, '52885', 'Yacuanquer', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(780, 18, '54001', 'Cucuta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(781, 18, '54003', 'Abrego', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(782, 18, '54051', 'Arboledas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(783, 18, '54099', 'Bochalema', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(784, 18, '54109', 'Bucarasica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(785, 18, '54125', 'Cacota', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(786, 18, '54128', 'Cachira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(787, 18, '54172', 'Chinacota', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(788, 18, '54174', 'Chitaga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(789, 18, '54206', 'Convención', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(790, 18, '54223', 'Cucutilla', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(791, 18, '54239', 'Durania', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(792, 18, '54245', 'El Carmen', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(793, 18, '54250', 'El Tarra', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(794, 18, '54261', 'El Zulia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(795, 18, '54313', 'Gramalote', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(796, 18, '54344', 'Hacarí', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(797, 18, '54347', 'Herran', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(798, 18, '54377', 'Labateca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(799, 18, '54385', 'La Esperanza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(800, 18, '54398', 'La Playa', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(801, 18, '54405', 'Los Patios', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(802, 18, '54418', 'Lourdes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(803, 18, '54480', 'Mutiscua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(804, 18, '54498', 'Ocaña', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(805, 18, '54518', 'Pamplona', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(806, 18, '54520', 'Pamplonita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(807, 18, '54553', 'Puerto Santander', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(808, 18, '54599', 'Ragonvalia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(809, 18, '54660', 'Salazar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(810, 18, '54670', 'San Calixto', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(811, 18, '54673', 'San Cayetano', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(812, 18, '54680', 'Santiago', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(813, 18, '54720', 'Sardinata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(814, 18, '54743', 'Silos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(815, 18, '54800', 'Teorama', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(816, 18, '54810', 'Tibu', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(817, 18, '54820', 'Toledo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(818, 18, '54871', 'Villa Caro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(819, 18, '54874', 'Villa Del Rosario', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(820, 19, '63001', 'Armenia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(821, 19, '63111', 'Buenavista', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(822, 19, '63130', 'Calarca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(823, 19, '63190', 'Circasia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(824, 19, '63212', 'Cordoba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(825, 19, '63272', 'Filandia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(826, 19, '63302', 'Genova', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(827, 19, '63401', 'La Tebaida', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(828, 19, '63470', 'Montenegro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(829, 19, '63548', 'Pijao', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(830, 19, '63594', 'Quimbaya', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(831, 19, '63690', 'Salento', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(832, 20, '66001', 'Pereira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(833, 20, '66045', 'Apia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(834, 20, '66075', 'Balboa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(835, 20, '66088', 'Belen De Umbria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(836, 20, '66170', 'Dosquebradas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(837, 20, '66318', 'Guatica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(838, 20, '66383', 'La Celia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(839, 20, '66400', 'La Virginia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(840, 20, '66440', 'Marsella', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(841, 20, '66456', 'Mistrato', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(842, 20, '66572', 'Pueblo Rico', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(843, 20, '66594', 'Quinchia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(844, 20, '66682', 'Santa Rosa De Cabal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(845, 20, '66687', 'Santuario', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(846, 21, '68001', 'Bucaramanga', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(847, 21, '68013', 'Aguada', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(848, 21, '68020', 'Albania', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(849, 21, '68051', 'Aratoca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(850, 21, '68077', 'Barbosa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(851, 21, '68079', 'Barichara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(852, 21, '68081', 'Barrancabermeja', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(853, 21, '68092', 'Betulia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(854, 21, '68101', 'Bolivar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(855, 21, '68121', 'Cabrera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(856, 21, '68132', 'California', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(857, 21, '68147', 'Capitanejo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(858, 21, '68152', 'Carcasi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(859, 21, '68160', 'Cepita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(860, 21, '68162', 'Cerrito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(861, 21, '68167', 'Charala', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(862, 21, '68169', 'Charta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(863, 21, '68176', 'Chima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(864, 21, '68179', 'Chipata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(865, 21, '68190', 'Cimitarra', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(866, 21, '68207', 'Concepcion', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(867, 21, '68209', 'Confines', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(868, 21, '68211', 'Contratacion', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(869, 21, '68217', 'Coromoro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(870, 21, '68229', 'Curiti', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(871, 21, '68235', 'El Carmen De Chucuri', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(872, 21, '68245', 'El Guacamayo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(873, 21, '68250', 'El Peñon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(874, 21, '68255', 'El Playon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(875, 21, '68264', 'Encino', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(876, 21, '68266', 'Enciso', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(877, 21, '68271', 'Florian', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(878, 21, '68276', 'Floridablanca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(879, 21, '68296', 'Galan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(880, 21, '68298', 'Gambita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(881, 21, '68307', 'Giron', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(882, 21, '68318', 'Guaca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(883, 21, '68320', 'Guadalupe', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(884, 21, '68322', 'Guapota', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(885, 21, '68324', 'Guavata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(886, 21, '68327', 'Gsepsa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(887, 21, '68344', 'Hato', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(888, 21, '68368', 'Jesus Maria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(889, 21, '68370', 'Jordan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(890, 21, '68377', 'La Belleza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(891, 21, '68385', 'Landazuri', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(892, 21, '68397', 'La Paz', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(893, 21, '68406', 'Lebrija', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(894, 21, '68418', 'Los Santos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(895, 21, '68425', 'Macaravita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(896, 21, '68432', 'Malaga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(897, 21, '68444', 'Matanza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(898, 21, '68464', 'Mogotes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(899, 21, '68468', 'Molagavita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(900, 21, '68498', 'Ocamonte', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(901, 21, '68500', 'Oiba', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(902, 21, '68502', 'Onzaga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(903, 21, '68522', 'Palmar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(904, 21, '68524', 'Palmas Del Socorro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(905, 21, '68533', 'Paramo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(906, 21, '68547', 'Piedecuesta', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(907, 21, '68549', 'Pinchote', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(908, 21, '68572', 'Puente Nacional', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(909, 21, '68573', 'Puerto Parra', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(910, 21, '68575', 'Puerto Wilches', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(911, 21, '68615', 'Rionegro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(912, 21, '68655', 'Sabana De Torres', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(913, 21, '68669', 'San Andres', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(914, 21, '68673', 'San Benito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(915, 21, '68679', 'San Gil', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(916, 21, '68682', 'San Joaquin', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(917, 21, '68684', 'San Jose De Miranda', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(918, 21, '68686', 'San Miguel', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(919, 21, '68689', 'San Vicente De Chucuri', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(920, 21, '68705', 'Santa Barbara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(921, 21, '68720', 'Santa Helena Del Opon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(922, 21, '68745', 'Simacota', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(923, 21, '68755', 'Socorro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(924, 21, '68770', 'Suaita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(925, 21, '68773', 'Sucre', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(926, 21, '68780', 'Surata', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(927, 21, '68820', 'Tona', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(928, 21, '68855', 'Valle De San Jose', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(929, 21, '68861', 'Velez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(930, 21, '68867', 'Vetas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(931, 21, '68872', 'Villanueva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(932, 21, '68895', 'Zapatoca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(933, 22, '70001', 'Sincelejo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(934, 22, '70110', 'Buenavista', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(935, 22, '70124', 'Caimito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(936, 22, '70204', 'Coloso', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(937, 22, '70215', 'Corozal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(938, 22, '70221', 'Coveñas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(939, 22, '70230', 'Chalan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(940, 22, '70233', 'El Roble', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(941, 22, '70235', 'Galeras', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(942, 22, '70265', 'Guaranda', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(943, 22, '70400', 'La Union', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(944, 22, '70418', 'Los Palmitos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(945, 22, '70429', 'Majagual', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(946, 22, '70473', 'Morroa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(947, 22, '70508', 'Ovejas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(948, 22, '70523', 'Palmito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(949, 22, '70670', 'Sampues', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(950, 22, '70678', 'San Benito Abad', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(951, 22, '70702', 'San Juan De Betulia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(952, 22, '70708', 'San Marcos', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(953, 22, '70713', 'San Onofre', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(954, 22, '70717', 'San Pedro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(955, 22, '70742', 'San Luis De Since', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(956, 22, '70771', 'Sucre', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(957, 22, '70820', 'Santiago De Tolu', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(958, 22, '70823', 'Tolu Viejo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(959, 23, '73001', 'Ibague', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(960, 23, '73024', 'Alpujarra', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(961, 23, '73026', 'Alvarado', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(962, 23, '73030', 'Ambalema', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(963, 23, '73043', 'Anzoategui', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(964, 23, '73055', 'Armero', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(965, 23, '73067', 'Ataco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(966, 23, '73124', 'Cajamarca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(967, 23, '73148', 'Carmen De Apicala', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(968, 23, '73152', 'Casabianca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(969, 23, '73168', 'Chaparral', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(970, 23, '73200', 'Coello', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(971, 23, '73217', 'Coyaima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(972, 23, '73226', 'Cunday', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(973, 23, '73236', 'Dolores', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(974, 23, '73268', 'Espinal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(975, 23, '73270', 'Falan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(976, 23, '73275', 'Flandes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(977, 23, '73283', 'Fresno', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(978, 23, '73319', 'Guamo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(979, 23, '73347', 'Herveo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(980, 23, '73349', 'Honda', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(981, 23, '73352', 'Icononzo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(982, 23, '73408', 'Lerida', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(983, 23, '73411', 'Libano', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(984, 23, '73443', 'Mariquita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(985, 23, '73449', 'Melgar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(986, 23, '73461', 'Murillo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(987, 23, '73483', 'Natagaima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(988, 23, '73504', 'Ortega', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(989, 23, '73520', 'Palocabildo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(990, 23, '73547', 'Piedras', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(991, 23, '73555', 'Planadas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(992, 23, '73563', 'Prado', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(993, 23, '73585', 'Purificacion', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(994, 23, '73616', 'Rioblanco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(995, 23, '73622', 'Roncesvalles', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(996, 23, '73624', 'Rovira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(997, 23, '73671', 'Saldaña', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(998, 23, '73675', 'San Antonio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(999, 23, '73678', 'San Luis', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1000, 23, '73686', 'Santa Isabel', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1001, 23, '73770', 'Suarez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1002, 23, '73854', 'Valle De San Juan', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1003, 23, '73861', 'Venadillo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1004, 23, '73870', 'Villahermosa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1005, 23, '73873', 'Villarrica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1006, 24, '76001', 'Cali', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1007, 24, '76020', 'Alcala', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1008, 24, '76036', 'Andalucia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1009, 24, '76041', 'Ansermanuevo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1010, 24, '76054', 'Argelia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1011, 24, '76100', 'Bolivar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1012, 24, '76109', 'Buenaventura', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1013, 24, '76111', 'Guadalajara De Buga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1014, 24, '76113', 'Bugalagrande', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1015, 24, '76122', 'Caicedonia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1016, 24, '76126', 'Calima', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1017, 24, '76130', 'Candelaria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1018, 24, '76147', 'Cartago', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1019, 24, '76233', 'Dagua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1020, 24, '76243', 'El Aguila', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1021, 24, '76246', 'El Cairo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1022, 24, '76248', 'El Cerrito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1023, 24, '76250', 'El Dovio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1024, 24, '76275', 'Florida', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1025, 24, '76306', 'Ginebra', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1026, 24, '76318', 'Guacari', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1027, 24, '76364', 'Jamundi', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1028, 24, '76377', 'La Cumbre', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1029, 24, '76400', 'La Union', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1030, 24, '76403', 'La Victoria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1031, 24, '76497', 'Obando', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1032, 24, '76520', 'Palmira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1033, 24, '76563', 'Pradera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1034, 24, '76606', 'Restrepo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1035, 24, '76616', 'Riofrio', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1036, 24, '76622', 'Roldanillo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1037, 24, '76670', 'San Pedro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1038, 24, '76736', 'Sevilla', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1039, 24, '76823', 'Toro', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1040, 24, '76828', 'Trujillo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1041, 24, '76834', 'Tulua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1042, 24, '76845', 'Ulloa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1043, 24, '76863', 'Versalles', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1044, 24, '76869', 'Vijes', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1045, 24, '76890', 'Yotoco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1046, 24, '76892', 'Yumbo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1047, 24, '76895', 'Zarzal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1048, 25, '81001', 'Arauca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1049, 25, '81065', 'Arauquita', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1050, 25, '81220', 'Cravo Norte', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1051, 25, '81300', 'Fortul', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1052, 25, '81591', 'Puerto Rondon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1053, 25, '81736', 'Saravena', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1054, 25, '81794', 'Tame', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1055, 26, '85001', 'Yopal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1056, 26, '85010', 'Aguazul', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1057, 26, '85015', 'Chameza', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1058, 26, '85125', 'Hato Corozal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1059, 26, '85136', 'La Salina', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1060, 26, '85139', 'Mani', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1061, 26, '85162', 'Monterrey', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1062, 26, '85225', 'Nunchia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1063, 26, '85230', 'Orocue', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1064, 26, '85250', 'Paz De Ariporo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1065, 26, '85263', 'Pore', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1066, 26, '85279', 'Recetor', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1067, 26, '85300', 'Sabanalarga', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1068, 26, '85315', 'Sacama', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1069, 26, '85325', 'San Luis De Palenque', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1070, 26, '85400', 'Tamara', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1071, 26, '85410', 'Tauramena', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1072, 26, '85430', 'Trinidad', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1073, 26, '85440', 'Villanueva', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1074, 27, '86001', 'Mocoa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1075, 27, '86219', 'Colon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1076, 27, '86320', 'Orito', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1077, 27, '86568', 'Puerto Asis', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1078, 27, '86569', 'Puerto Caicedo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1079, 27, '86571', 'Puerto Guzman', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1080, 27, '86573', 'Leguizamo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1081, 27, '86749', 'Sibundoy', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1082, 27, '86755', 'San Francisco', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1083, 27, '86757', 'San Miguel', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1084, 27, '86760', 'Santiago', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1085, 27, '86865', 'Valle Del Guamuez', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1086, 27, '86885', 'Villagarzon', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1087, 28, '88001', 'San Andres', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1088, 28, '88564', 'Providencia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1089, 29, '91001', 'Leticia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1090, 29, '91263', 'El Encanto', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1091, 29, '91405', 'La Chorrera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1092, 29, '91407', 'La Pedrera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1093, 29, '91430', 'La Victoria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1094, 29, '91460', 'Miriti - Parana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1095, 29, '91530', 'Puerto Alegria', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1096, 29, '91536', 'Puerto Arica', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1097, 29, '91540', 'Puerto Nariño', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1098, 29, '91669', 'Puerto Santander', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1099, 29, '91798', 'Tarapaca', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1100, 30, '94001', 'Inirida', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1101, 30, '94343', 'Barranco Minas', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1102, 30, '94663', 'Mapiripana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1103, 30, '94883', 'San Felipe', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1104, 30, '94884', 'Puerto Colombia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1105, 30, '94885', 'La Guadalupe', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1106, 30, '94886', 'Cacahual', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1107, 30, '94887', 'Pana Pana', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1108, 30, '94888', 'Morichal', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1109, 31, '95001', 'San Jose Del Guaviare', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1110, 31, '95015', 'Calamar', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1111, 31, '95025', 'El Retorno', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1112, 31, '95200', 'Miraflores', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1113, 32, '97001', 'Mitu', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1114, 32, '97161', 'Caruru', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1115, 32, '97511', 'Pacoa', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1116, 32, '97666', 'Taraira', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1117, 32, '97777', 'Papunaua', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1118, 32, '97889', 'Yavarate', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1119, 33, '99001', 'Puerto Carreño', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1120, 33, '99524', 'La Primavera', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1121, 33, '99624', 'Santa Rosalia', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1122, 33, '99773', 'Cumaribo', 0, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1123, 18, '54399', 'Aspacica', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1124, 18, '54400', 'La Vega de San Antonio', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1125, 9, '20615', 'Otaré', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1126, 9, '20012', 'Besote', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1127, 9, '20014', 'Casacará', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1128, 18, '54246', 'Guamalito', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1129, 9, '20015', 'Cuatrovientos', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1130, 9, '20016', 'El burro', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1131, 9, '20017', 'La loma', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1132, 9, '20018', 'La mata', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06'),
(1133, 9, '20019', 'Rincon Hondo', 1, '2024-02-01 20:59:06', '2024-02-01 20:59:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona',
  `carlabid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del cargo laboral',
  `tipideid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de identificación',
  `tipperid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de persona',
  `persdepaidnacimiento` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento de nacimiento del documento',
  `persmuniidnacimiento` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio de nacimiento del documento',
  `persdepaidexpedicion` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento de expedición del documento',
  `persmuniidexpedicion` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio de expedición del documento',
  `persdocumento` varchar(15) NOT NULL COMMENT 'Número de documento de la persona',
  `persprimernombre` varchar(100) NOT NULL COMMENT 'Primer nombre de la persona',
  `perssegundonombre` varchar(40) DEFAULT NULL COMMENT 'Segundo nombre de la persona',
  `persprimerapellido` varchar(40) DEFAULT NULL COMMENT 'Primer apellido de la persona',
  `perssegundoapellido` varchar(40) DEFAULT NULL COMMENT 'Segundo apellido de la persona',
  `persfechanacimiento` date DEFAULT NULL COMMENT 'Fecha de nacimiento de la persona',
  `persdireccion` varchar(100) NOT NULL COMMENT 'Determina el genero de la persona',
  `perscorreoelectronico` varchar(80) DEFAULT NULL COMMENT 'Correo electrónico de la persona',
  `persfechadexpedicion` date DEFAULT NULL COMMENT 'Fecha de nacimiento de la persona',
  `persnumerotelefonofijo` varchar(20) DEFAULT NULL COMMENT 'Número de teléfono fijo de la persona',
  `persnumerocelular` varchar(20) DEFAULT NULL COMMENT 'Número de teléfono celular de la persona',
  `persgenero` varchar(1) NOT NULL COMMENT 'Determina el genero de la persona',
  `persrutafoto` varchar(100) DEFAULT NULL COMMENT 'Ruta de la foto de la persona',
  `persrutafirma` varchar(100) DEFAULT NULL COMMENT 'Ruta de la firma digital de la persona para la gestión documental',
  `perstienefirmadigital` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la persona tiene firma digital',
  `persclavecertificado` varchar(20) DEFAULT NULL COMMENT 'Clave del certificado digital',
  `persrutacrt` varchar(500) DEFAULT NULL COMMENT 'Ruta de certificado digital con extensión crt',
  `persrutapem` varchar(500) DEFAULT NULL COMMENT 'Ruta de certificado digital con extensión pem',
  `persactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la persona se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`persid`, `carlabid`, `tipideid`, `tipperid`, `persdepaidnacimiento`, `persmuniidnacimiento`, `persdepaidexpedicion`, `persmuniidexpedicion`, `persdocumento`, `persprimernombre`, `perssegundonombre`, `persprimerapellido`, `perssegundoapellido`, `persfechanacimiento`, `persdireccion`, `perscorreoelectronico`, `persfechadexpedicion`, `persnumerotelefonofijo`, `persnumerocelular`, `persgenero`, `persrutafoto`, `persrutafirma`, `perstienefirmadigital`, `persclavecertificado`, `persrutacrt`, `persrutapem`, `persactiva`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'E', NULL, NULL, NULL, NULL, '1', 'SISTEMA', NULL, NULL, NULL, NULL, 'SISTEMA', NULL, NULL, NULL, NULL, 'M', NULL, NULL, 0, NULL, NULL, NULL, 0, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 1, 1, 'E', 18, 789, 18, 804, '1978917', 'RAMÓN', 'DAVID', 'SALAZAR', 'RINCÓN', '1979-08-29', 'Calle 4 36 49', 'radasa10@hotmail.com', '1998-04-16', '3204018506', '3204018506', 'M', NULL, 'Firma_1978917.png', 1, '123456', NULL, NULL, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 4, 1, 'E', 9, 416, 9, 416, '5036123', 'LUIS', 'MANUEL', 'ASCANIO', 'CLARO', '1979-08-29', 'Calle 4 36 49', 'luisangel330@hotmail.com', '1998-04-16', '3163374329', '3163374329', 'M', NULL, 'Firma_5036123.png', 0, NULL, NULL, NULL, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personacontratoservicioesp`
--

CREATE TABLE `personacontratoservicioesp` (
  `pecoseid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona contrato servicio especial',
  `tipideid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de identificación',
  `pecosedocumento` varchar(15) NOT NULL COMMENT 'Número de documento de la persona que contrata el servicio especial',
  `pecoseprimernombre` varchar(140) NOT NULL COMMENT 'Primer nombre de la persona que contrata el servicio especial',
  `pecosesegundonombre` varchar(40) DEFAULT NULL COMMENT 'Segundo nombre de la persona que contrata el servicio especial',
  `pecoseprimerapellido` varchar(40) DEFAULT NULL COMMENT 'Primer apellido de la persona que contrata el servicio especial',
  `pecosesegundoapellido` varchar(40) DEFAULT NULL COMMENT 'Segundo apellido de la persona que contrata el servicio especial',
  `pecosedireccion` varchar(100) NOT NULL COMMENT 'Dirección de la persona que contrata el servicio especial',
  `pecosecorreoelectronico` varchar(80) DEFAULT NULL COMMENT 'Correo electrónico de la persona que contrata el servicio especial',
  `pecosenumerocelular` varchar(20) DEFAULT NULL COMMENT 'Número de teléfono fijo de la persona que contrata el servicio especial',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personaradicadocumento`
--

CREATE TABLE `personaradicadocumento` (
  `peradoid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona radica documento',
  `tipideid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de identificación',
  `peradodocumento` varchar(15) NOT NULL COMMENT 'Número de documento de la persona',
  `peradoprimernombre` varchar(70) NOT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradosegundonombre` varchar(40) DEFAULT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradoprimerapellido` varchar(40) DEFAULT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradosegundoapellido` varchar(40) DEFAULT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradodireccion` varchar(100) NOT NULL COMMENT 'Dirección de la persona que radica el documento',
  `peradotelefono` varchar(20) DEFAULT NULL COMMENT 'Telefóno de la persona que radica el documento',
  `peradocorreo` varchar(80) DEFAULT NULL COMMENT 'correo de la persona que radica el documento',
  `peradocodigodocumental` varchar(20) DEFAULT NULL COMMENT 'Código documental proveniente de la emprea que emite el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personaservicio`
--

CREATE TABLE `personaservicio` (
  `perserid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona servicio',
  `tipideid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de identificación',
  `perserdocumento` varchar(15) NOT NULL COMMENT 'Número de documento de la persona que utiliza un servicio de la cooperativa (pasaje o encomienda)',
  `perserprimernombre` varchar(140) NOT NULL COMMENT 'Primer nombre de la persona que un servicio de la cooperativa (pasaje o encomienda)',
  `persersegundonombre` varchar(40) DEFAULT NULL COMMENT 'Segundo nombre de la persona que un servicio de la cooperativa (pasaje o encomienda)',
  `perserprimerapellido` varchar(40) DEFAULT NULL COMMENT 'Primer apellido de la persona que un servicio de la cooperativa (pasaje o encomienda)',
  `persersegundoapellido` varchar(40) DEFAULT NULL COMMENT 'Segundo apellido de la persona que un servicio de la cooperativa (pasaje o encomienda)',
  `perserdireccion` varchar(100) NOT NULL COMMENT 'Dirección de la persona que un servicio de la cooperativa (pasaje o encomienda)',
  `persercorreoelectronico` varchar(80) DEFAULT NULL COMMENT 'Correo electrónico de la persona que un servicio de la cooperativa (pasaje o encomienda)',
  `persernumerocelular` varchar(20) DEFAULT NULL COMMENT 'Número de teléfono fijo de la persona que un servicio de la cooperativa (pasaje o encomienda)',
  `perserpermitenotificacion` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la persona requiere notificar al correo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planillaruta`
--

CREATE TABLE `planillaruta` (
  `plarutid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla planilla ruta',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia que esta generando la planilla',
  `rutaid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la ruta',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `condid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del conductor',
  `usuaidregistra` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que rgistra la planilla',
  `usuaiddespacha` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del usuario que despacha la planilla',
  `plarutfechahoraregistro` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se registra la planilla',
  `plarutanio` year(4) NOT NULL COMMENT 'Año en el cual se genera de la planilla de la ruta',
  `plarutconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo de la planilla de la ruta',
  `plarutfechahorasalida` datetime DEFAULT NULL COMMENT 'Fecha y hora actual se entrega la planilla para la ruta',
  `plarutfechallegadaaldestino` datetime DEFAULT NULL COMMENT 'Fecha y hora en el cual se recibe la planilla en su destino final',
  `plarutdespachada` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la ruta fue despachada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `procesoautomatico`
--

CREATE TABLE `procesoautomatico` (
  `proautid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla proceso automático',
  `proautnombre` varchar(50) NOT NULL COMMENT 'Nombre del proceso automático',
  `proautfechaejecucion` date NOT NULL COMMENT 'Fecha de ejecución del proceso automático',
  `proauttipo` varchar(1) NOT NULL DEFAULT 'D' COMMENT 'Tipo de proceso dia o noche',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `procesoautomatico`
--

INSERT INTO `procesoautomatico` (`proautid`, `proautnombre`, `proautfechaejecucion`, `proauttipo`, `created_at`, `updated_at`) VALUES
(1, 'VencimientoLicencias', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'VencimientoSoat', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'VencimientoCRT', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'VencimientoPolizas', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 'VencimientoTarjetaOperacion', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 'VencimientoCuotasCreditos', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, 'SuspenderConductor', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(8, 'SuspenderVehiculosSoat', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(9, 'SuspenderVehiculosCRT', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(10, 'SuspenderVehiculosPolizas', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(11, 'SuspenderVehiculosTarjetaOperacion', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(12, 'LevantarSancionVehiculo', '2024-02-01', 'D', '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocentanexo`
--

CREATE TABLE `radicaciondocentanexo` (
  `radoeaid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante dependencia',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `radoeanombreanexooriginal` varchar(200) NOT NULL COMMENT 'Nombre con el cual se ha subido el documento',
  `radoeanombreanexoeditado` varchar(200) NOT NULL COMMENT 'Nombre con el cual se ha subido el documento pero editado',
  `radoearutaanexo` varchar(500) NOT NULL COMMENT 'Ruta enfuscada del anexo del radicado',
  `radoearequiereradicado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el adjunto requiere radicado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocentcambioestado`
--

CREATE TABLE `radicaciondocentcambioestado` (
  `radeceid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante cambio estado',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `tierdeid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de estado radicación documento entrante',
  `radeceusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del radicado',
  `radecefechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del radicado',
  `radeceobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación del cambio estado radicado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocentdependencia`
--

CREATE TABLE `radicaciondocentdependencia` (
  `radoedid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante dependencia',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `depeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la dependencia',
  `radoedsuaid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del usuario que recibe el documento radicado',
  `radoedfechahorarecibido` datetime DEFAULT NULL COMMENT 'Fecha y hora en la cual se recibe el documento',
  `radoedescopia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado es una copia para una dependencia',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocumentoentrante`
--

CREATE TABLE `radicaciondocumentoentrante` (
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante',
  `peradoid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona que radica el documento',
  `tipmedid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de medio',
  `tierdeid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de estado radicación documento entrante',
  `depaid` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento del cual proviene el documento',
  `muniid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio del cual proviene el documento',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del documento',
  `radoenconsecutivo` varchar(4) NOT NULL COMMENT 'Consecutivo del radicado',
  `radoenanio` year(4) NOT NULL COMMENT 'Año en el cual se crea el radicado',
  `radoenfechahoraradicado` datetime NOT NULL COMMENT 'Fecha y hora en la cual se radica el documento',
  `radoenfechamaximarespuesta` date NOT NULL COMMENT 'Fecha máxima para emitir la respuesta del radicado del documento',
  `radoenfechadocumento` date NOT NULL COMMENT 'Fecha que contiene el documento',
  `radoenfechallegada` date NOT NULL COMMENT 'Fecha de llegada del documento',
  `radoenpersonaentregadocumento` varchar(100) NOT NULL COMMENT 'Nombre de la persona que radica el documento',
  `radoenasunto` varchar(500) NOT NULL COMMENT 'Asunto que contiene el documento para radicar',
  `radoentieneanexo` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado tiene anexo',
  `radoendescripcionanexo` varchar(300) DEFAULT NULL COMMENT 'Descripción del anexo',
  `radoentienecopia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado tiene copia',
  `radoenobservacion` varchar(300) DEFAULT NULL COMMENT 'Observación general del radicado del documento',
  `radoenrequiererespuesta` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado requiere una respuesta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `rolid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla rol',
  `rolnombre` varchar(80) NOT NULL COMMENT 'Nombre del rol',
  `rolactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el rol se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rolid`, `rolnombre`, `rolactivo`, `created_at`, `updated_at`) VALUES
(1, 'Super administrador', 1, '2024-02-01 21:00:42', '2024-02-01 21:00:42'),
(2, 'Administrador', 1, '2024-02-01 21:00:42', '2024-02-01 21:00:42'),
(3, 'Secretaria', 1, '2024-02-01 21:00:42', '2024-02-01 21:00:42'),
(4, 'Jefe', 1, '2024-02-01 21:00:42', '2024-02-01 21:00:42'),
(5, 'Radicador', 1, '2024-02-01 21:00:42', '2024-02-01 21:00:42'),
(6, 'Coordinador del archivo histórico', 1, '2024-02-01 21:00:42', '2024-02-01 21:00:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rolfuncionalidad`
--

CREATE TABLE `rolfuncionalidad` (
  `rolfunid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla rol funcionalidad',
  `rolfunrolid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del rol',
  `rolfunfuncid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la funcionalidad'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rolfuncionalidad`
--

INSERT INTO `rolfuncionalidad` (`rolfunid`, `rolfunrolid`, `rolfunfuncid`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6),
(7, 1, 7),
(8, 1, 8),
(9, 1, 9),
(10, 1, 10),
(11, 1, 11),
(12, 1, 12),
(13, 1, 13),
(14, 1, 14),
(15, 1, 15),
(16, 1, 16),
(17, 1, 17),
(18, 1, 18),
(19, 1, 19),
(20, 1, 20),
(21, 1, 21),
(22, 1, 22),
(23, 1, 23),
(24, 1, 24),
(25, 1, 25),
(26, 1, 26),
(27, 1, 27),
(28, 1, 28),
(29, 1, 29),
(30, 1, 30),
(31, 1, 31),
(32, 1, 32),
(33, 1, 33),
(34, 1, 34),
(35, 1, 35),
(36, 1, 36),
(37, 1, 37),
(38, 1, 38),
(39, 1, 39),
(40, 1, 40),
(41, 1, 41),
(42, 1, 42),
(43, 1, 43),
(44, 1, 44),
(45, 1, 45),
(46, 1, 46),
(47, 1, 47),
(48, 1, 48),
(49, 1, 49);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ruta`
--

CREATE TABLE `ruta` (
  `rutaid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla ruta',
  `depaidorigen` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento de origen de la ruta',
  `muniidorigen` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio de origen de la ruta',
  `depaiddestino` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento de destino de la ruta',
  `muniiddestino` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio de destino de la ruta',
  `rutatienenodos` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la ruta tiene nodos',
  `rutaactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la ruta se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutanodo`
--

CREATE TABLE `rutanodo` (
  `rutnodid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla ruta nodo',
  `rutaid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la ruta',
  `muniid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio del nodo de la ruta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seriedocumental`
--

CREATE TABLE `seriedocumental` (
  `serdocid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla serie documental',
  `serdoccodigo` varchar(3) NOT NULL COMMENT 'Código de la serie',
  `serdocnombre` varchar(80) NOT NULL COMMENT 'Nombre de la serie',
  `serdoctiempoarchivogestion` smallint(6) NOT NULL COMMENT 'Tiempo en el archivo de gestión',
  `serdoctiempoarchivocentral` smallint(6) NOT NULL COMMENT 'Tiempo en el archivo central',
  `serdoctiempoarchivohistorico` smallint(6) NOT NULL COMMENT 'Tiempo en el archivo histórico',
  `serdocpermiteeliminar` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la serie se puede eliminar',
  `serdocactiva` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la serie esta activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `seriedocumental`
--

INSERT INTO `seriedocumental` (`serdocid`, `serdoccodigo`, `serdocnombre`, `serdoctiempoarchivogestion`, `serdoctiempoarchivocentral`, `serdoctiempoarchivohistorico`, `serdocpermiteeliminar`, `serdocactiva`, `created_at`, `updated_at`) VALUES
(1, '001', 'Acta', 360, 720, 1440, 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, '002', 'Certificado', 360, 720, 1440, 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, '003', 'Circular', 360, 720, 1440, 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, '004', 'Citación', 360, 720, 1440, 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, '005', 'Constancia', 360, 720, 1440, 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, '006', 'Oficio', 360, 720, 1440, 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, '007', 'Resolucion', 360, 720, 1440, 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudcredito`
--

CREATE TABLE `solicitudcredito` (
  `solcreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla solicitud crédito',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea la solicitud de crédito',
  `lincreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la línea de crédito',
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del asociado',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `tiesscid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de estado de la solicitud de crédito',
  `solcrefechasolicitud` date NOT NULL COMMENT 'Fecha de registro de la solicitud de crédito',
  `solcredescripcion` varchar(1000) NOT NULL COMMENT 'Descripción de la solicitud de crédito',
  `solcrevalorsolicitado` decimal(12,0) NOT NULL COMMENT 'Monto o valor de la solicitud de crédito',
  `solcretasa` decimal(6,2) NOT NULL COMMENT 'Tasa de interés para solicitud de crédito',
  `solcrenumerocuota` decimal(5,0) NOT NULL COMMENT 'Número de cuota de la solicitud de crédito',
  `solcreobservacion` varchar(1000) DEFAULT NULL COMMENT 'Observación general de la  solicitud de crédito',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudcreditocambioestado`
--

CREATE TABLE `solicitudcreditocambioestado` (
  `socrceid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla solicitud de credito cambio estado',
  `solcreid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la solicitud de crédito',
  `tiesscid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de estado solicitud de crédito',
  `socrceusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado de la solicitud de crédito',
  `socrcefechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado de la solicitud de crédito',
  `socrceobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación del cambio estado de la solicitud de crédito',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subseriedocumental`
--

CREATE TABLE `subseriedocumental` (
  `susedoid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla sub serie documental',
  `serdocid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla serie documental',
  `tipdocid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo documento',
  `susedocodigo` varchar(2) NOT NULL COMMENT 'Código de la sub serie documental',
  `susedonombre` varchar(80) NOT NULL COMMENT 'Nombre de la sub serie documental',
  `susedopermiteeliminar` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la sub serie documental se puede eliminar',
  `susedoactiva` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la sub serie documental esta activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `subseriedocumental`
--

INSERT INTO `subseriedocumental` (`susedoid`, `serdocid`, `tipdocid`, `susedocodigo`, `susedonombre`, `susedopermiteeliminar`, `susedoactiva`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '01', 'Acta universal', 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 2, 2, '01', 'Certificado universal', 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 3, 3, '01', 'Circular universal', 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 4, 4, '01', 'Citación universal', 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 5, 5, '01', 'Constancia universal', 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 6, 6, '01', 'Oficio universal', 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, 7, 7, '01', 'Resolución universal', 0, 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifatiquete`
--

CREATE TABLE `tarifatiquete` (
  `tartiqid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tarifa tiquete',
  `rutaid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la ruta',
  `depaiddestino` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de destino del tiquete',
  `muniiddestino` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de destino del tiquete',
  `tartiqvalor` decimal(10,0) NOT NULL COMMENT 'Valor del tiquete',
  `tartiqfondoreposicion` decimal(6,2) NOT NULL COMMENT 'Porcentaje para el fondo de reposición del tiquete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoacta`
--

CREATE TABLE `tipoacta` (
  `tipactid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de acta',
  `tipactnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de acta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoacta`
--

INSERT INTO `tipoacta` (`tipactid`, `tipactnombre`) VALUES
(1, 'Ordinaria'),
(2, 'Extra Ordinaria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocajaubicacion`
--

CREATE TABLE `tipocajaubicacion` (
  `ticaubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo caja ubicación',
  `ticaubnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de caja ubicación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocajaubicacion`
--

INSERT INTO `tipocajaubicacion` (`ticaubid`, `ticaubnombre`) VALUES
(1, 'Caja uno'),
(2, 'Caja dos'),
(3, 'Caja tres'),
(4, 'Caja cuatro'),
(5, 'Caja cinco'),
(6, 'Caja seis'),
(7, 'Caja siete'),
(8, 'Caja ocho'),
(9, 'Caja nueve'),
(10, 'Caja diez'),
(11, 'Caja once'),
(12, 'Caja doce');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocarpetaubicacion`
--

CREATE TABLE `tipocarpetaubicacion` (
  `ticrubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo carpeta ubicación',
  `ticrubnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de carpeta ubicación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocarpetaubicacion`
--

INSERT INTO `tipocarpetaubicacion` (`ticrubid`, `ticrubnombre`) VALUES
(1, 'Carpeta uno'),
(2, 'Carpeta dos'),
(3, 'Carpeta tres'),
(4, 'Carpeta cuatro'),
(5, 'Carpeta cinco'),
(6, 'Carpeta seis'),
(7, 'Carpeta siete'),
(8, 'Carpeta ocho'),
(9, 'Carpeta nueve'),
(10, 'Carpeta diez'),
(11, 'Carpeta once'),
(12, 'Carpeta doce');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocarroceriavehiculo`
--

CREATE TABLE `tipocarroceriavehiculo` (
  `ticaveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo carroceria vehículo',
  `ticavenombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo de carroceria del vehículo',
  `ticaveactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo del carroceria del vehículo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocarroceriavehiculo`
--

INSERT INTO `tipocarroceriavehiculo` (`ticaveid`, `ticavenombre`, `ticaveactivo`, `created_at`, `updated_at`) VALUES
(1, 'CERRADO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'SEDÁN', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'HATCH-BACK', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'MIXTA', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 'CABINADO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 'VAN', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, 'STAT-WAGON', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(8, 'VANS', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(9, 'CARPADO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(10, 'ESTACAS', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocategorialicencia`
--

CREATE TABLE `tipocategorialicencia` (
  `ticaliid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de categoría de licencia',
  `ticalinombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de categoría de la licencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocategorialicencia`
--

INSERT INTO `tipocategorialicencia` (`ticaliid`, `ticalinombre`) VALUES
('A1', 'A1'),
('A2', 'A2'),
('B1', 'B1'),
('B2', 'B2'),
('C1', 'C1'),
('C2', 'C2'),
('C3', 'C3');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocolorvehiculo`
--

CREATE TABLE `tipocolorvehiculo` (
  `ticoveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo color vehículo',
  `ticovenombre` varchar(50) NOT NULL COMMENT 'Nombre del color del tipo vehículo',
  `ticoveactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo del color del vehículo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocolorvehiculo`
--

INSERT INTO `tipocolorvehiculo` (`ticoveid`, `ticovenombre`, `ticoveactivo`, `created_at`, `updated_at`) VALUES
(1, 'BLANCO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'BLANCO VERDE AMARILLO ROJO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'BLANCO VERDE AMARILLO AZUL', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'VERDE BLANCO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 'BLANCO NIEVE', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 'BLANCO VERDE', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, 'AMARILLO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(8, 'AMARILLO URBANO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(9, 'AMARILLO LIMA', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(10, 'BLANCO NIEBLA', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(11, 'BLANCO GALAXIA', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(12, 'BLANCO VERDE AMARILLO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(13, 'AMARILLO BLANCO VERDE', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(14, 'BLANCO GLACIAL', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(15, 'BLANCO AZUL ROJO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(16, 'BLANCO ÁRTICO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(17, 'AZUL', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(18, 'BLANCO POLAR', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(19, 'VERDE', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(20, 'AZUL AMARILLO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(21, 'VERDE AMARILLO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(22, 'NARANJA-CREMA', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(23, 'VERDE AMARILLO ROJO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(24, 'ROJO LADRILLO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(25, 'ROJO VERDE BLANCO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocombustiblevehiculo`
--

CREATE TABLE `tipocombustiblevehiculo` (
  `ticovhid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo combustible vehículo',
  `ticovhnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo combustible vehículo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocombustiblevehiculo`
--

INSERT INTO `tipocombustiblevehiculo` (`ticovhid`, `ticovhnombre`) VALUES
(1, 'ACPM'),
(2, 'GASOLINA'),
(3, 'GAS'),
(4, 'HIBRIDO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoconductor`
--

CREATE TABLE `tipoconductor` (
  `tipconid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo conductor',
  `tipconnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de conductor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoconductor`
--

INSERT INTO `tipoconductor` (`tipconid`, `tipconnombre`) VALUES
('P', 'PRINCIPAL'),
('R', 'RELEVADOR'),
('S', 'SUPLENTE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocontratoservicioespecial`
--

CREATE TABLE `tipocontratoservicioespecial` (
  `ticoseid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo contrato servicio especial',
  `ticosenombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo contrato servicio especial'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipocontratoservicioespecial`
--

INSERT INTO `tipocontratoservicioespecial` (`ticoseid`, `ticosenombre`) VALUES
('EM', 'Empresarial'),
('ES', 'Escolar'),
('GU', 'Grupo de usuarios'),
('SA', 'Salud'),
('TU', 'Turismo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoconvenioservicioespecial`
--

CREATE TABLE `tipoconvenioservicioespecial` (
  `ticossid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo contrato servicio especial',
  `ticossnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo contrato servicio especial'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoconvenioservicioespecial`
--

INSERT INTO `tipoconvenioservicioespecial` (`ticossid`, `ticossnombre`) VALUES
('CS', 'Consorcio'),
('CV', 'Convenio'),
('NA', 'No aplica'),
('UT', 'Union temporal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodespedida`
--

CREATE TABLE `tipodespedida` (
  `tipdesid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo despedida',
  `tipdesnombre` varchar(100) NOT NULL COMMENT 'Nombre del tipo despedida',
  `tipdesactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de despedida se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipodespedida`
--

INSERT INTO `tipodespedida` (`tipdesid`, `tipdesnombre`, `tipdesactivo`, `created_at`, `updated_at`) VALUES
(1, 'Atentamente,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'Atentamente le saluda,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'Atentamente se despide,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'Agradecidos por su amabilidad,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 'Agradecidos por su atención,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 'Cordialmente se despide,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, 'Sin otro particular por el momento,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(8, 'Reiteramos nuestros mas cordiales saludos,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(9, 'Nuestra consideracion mas distinguida,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(10, 'En espera de sus noticias le saludamos,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(11, 'Un atento saludo,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(12, 'Agradeciendo su valiosa colaboración,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(13, 'En espera de una respuesta,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(14, 'Quedamos a su disposicion por cuanto puedan necesitar', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(15, 'Les quedamos muy agradecidos por su colaboración', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(16, 'Hasta otra oportunidad,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodestino`
--

CREATE TABLE `tipodestino` (
  `tipdetid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de destino',
  `tipdetnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de destino'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipodestino`
--

INSERT INTO `tipodestino` (`tipdetid`, `tipdetnombre`) VALUES
(1, 'Interno'),
(2, 'Externo'),
(3, 'Interno / Externo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumental`
--

CREATE TABLE `tipodocumental` (
  `tipdocid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo documental',
  `tipdoccodigo` varchar(2) NOT NULL COMMENT 'Código del tipo documental',
  `tipdocnombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo documental',
  `tipdocproducedocumento` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el tipo documental produce documento',
  `tipdocactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de documento se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipodocumental`
--

INSERT INTO `tipodocumental` (`tipdocid`, `tipdoccodigo`, `tipdocnombre`, `tipdocproducedocumento`, `tipdocactivo`, `created_at`, `updated_at`) VALUES
(1, 'A', 'Acta', 1, 1, NULL, NULL),
(2, 'B', 'Certificado', 1, 1, NULL, NULL),
(3, 'C', 'Circular', 1, 1, NULL, NULL),
(4, 'H', 'Citación', 1, 1, NULL, NULL),
(5, 'T', 'Constancia', 1, 1, NULL, NULL),
(6, 'O', 'Oficio', 1, 1, NULL, NULL),
(7, 'R', 'Resolución', 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoencomienda`
--

CREATE TABLE `tipoencomienda` (
  `tipencid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo encomienda',
  `tipencnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo encomienda'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoencomienda`
--

INSERT INTO `tipoencomienda` (`tipencid`, `tipencnombre`) VALUES
('B', 'Bolsa'),
('C', 'Caja'),
('E', 'Equipaje'),
('L', 'Bulto'),
('P', 'Paquete'),
('S', 'Sobre'),
('V', 'Cava');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadoasociado`
--

CREATE TABLE `tipoestadoasociado` (
  `tiesasid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo de estado asociado',
  `tiesasnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de estado del asociado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadoasociado`
--

INSERT INTO `tipoestadoasociado` (`tiesasid`, `tiesasnombre`) VALUES
('A', 'Activo'),
('E', 'Excluido'),
('I', 'Inactivo'),
('R', 'Retirado'),
('S', 'Sancionado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadocolocacion`
--

CREATE TABLE `tipoestadocolocacion` (
  `tiesclid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo estado solicitud colocación',
  `tiesclnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de estado de la solicitud de colocación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadocolocacion`
--

INSERT INTO `tipoestadocolocacion` (`tiesclid`, `tiesclnombre`) VALUES
('C', 'Cancelado anticipadamente'),
('J', 'Júridica'),
('R', 'Recuperacón'),
('S', 'Saldada'),
('V', 'Vigente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadoconductor`
--

CREATE TABLE `tipoestadoconductor` (
  `tiescoid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo estado conductor',
  `tiesconombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de estado del conductor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadoconductor`
--

INSERT INTO `tipoestadoconductor` (`tiescoid`, `tiesconombre`) VALUES
('A', 'Activo'),
('D', 'Desvinculado'),
('S', 'Suspendido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadodocumento`
--

CREATE TABLE `tipoestadodocumento` (
  `tiesdoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estado documento',
  `tiesdonombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo estado documento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadodocumento`
--

INSERT INTO `tipoestadodocumento` (`tiesdoid`, `tiesdonombre`) VALUES
(1, 'Inicial'),
(2, 'Solicitar firma'),
(3, 'Anular la solicitud de firma'),
(4, 'Documento firmado'),
(5, 'Documento sellado'),
(6, 'Documento Radicado'),
(7, 'Documento compartido'),
(8, 'Documento recibido'),
(9, 'Solicitar anulación del documento'),
(10, 'Documento anulado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadoencomienda`
--

CREATE TABLE `tipoestadoencomienda` (
  `tiesenid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo estado encomienda',
  `tiesennombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de estado de la encomienda'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadoencomienda`
--

INSERT INTO `tipoestadoencomienda` (`tiesenid`, `tiesennombre`) VALUES
('D', 'Terminal destino'),
('E', 'Entregado'),
('R', 'Recibido'),
('T', 'En transporte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadoraddocentrante`
--

CREATE TABLE `tipoestadoraddocentrante` (
  `tierdeid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estado documento entrante',
  `tierdenombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo estado documento entrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadoraddocentrante`
--

INSERT INTO `tipoestadoraddocentrante` (`tierdeid`, `tierdenombre`) VALUES
(1, 'Inicial'),
(2, 'Tramitado'),
(3, 'Recibido'),
(4, 'Respondido'),
(5, 'Anulado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadosolicitudcredito`
--

CREATE TABLE `tipoestadosolicitudcredito` (
  `tiesscid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo estado solicitud crédito',
  `tiesscnombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de estado de la solicitud de crédito'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadosolicitudcredito`
--

INSERT INTO `tipoestadosolicitudcredito` (`tiesscid`, `tiesscnombre`) VALUES
('A', 'Aprobado'),
('D', 'Desembolsado'),
('N', 'Negado'),
('R', 'Registrado'),
('S', 'Asesoria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestadovehiculo`
--

CREATE TABLE `tipoestadovehiculo` (
  `tiesveid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo estado vehículo',
  `tiesvenombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo estado vehículo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestadovehiculo`
--

INSERT INTO `tipoestadovehiculo` (`tiesveid`, `tiesvenombre`) VALUES
('A', 'Activo'),
('D', 'Desvinculado'),
('S', 'Suspendido');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoestantearchivador`
--

CREATE TABLE `tipoestantearchivador` (
  `tiesarid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estante archivador',
  `tiesarnombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo estante archivador',
  `tiesaractivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el estante archivador se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoestantearchivador`
--

INSERT INTO `tipoestantearchivador` (`tiesarid`, `tiesarnombre`, `tiesaractivo`, `created_at`, `updated_at`) VALUES
(1, 'Estante uno', 1, NULL, NULL),
(2, 'Estante dos', 1, NULL, NULL),
(3, 'Estante tres', 1, NULL, NULL),
(4, 'Estante cuatro', 1, NULL, NULL),
(5, 'Estante cinco', 1, NULL, NULL),
(6, 'Estante seis', 1, NULL, NULL),
(7, 'Estante siete', 1, NULL, NULL),
(8, 'Estante ocho', 1, NULL, NULL),
(9, 'Estante nueve', 1, NULL, NULL),
(10, 'Estante diez', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoidentificacion`
--

CREATE TABLE `tipoidentificacion` (
  `tipideid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo identificación',
  `tipidesigla` varchar(4) NOT NULL COMMENT 'Sigla del tipo de identificación',
  `tipidenombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo de identificación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipoidentificacion`
--

INSERT INTO `tipoidentificacion` (`tipideid`, `tipidesigla`, `tipidenombre`) VALUES
(1, 'CC', 'Cédula de ciudadanía'),
(2, 'TI', 'Tarjeta de identidad'),
(3, 'RC', 'Registro civil'),
(4, 'CE', 'Cédula de extranjería'),
(5, 'NIT', 'Número de identificación tributaria');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomarcavehiculo`
--

CREATE TABLE `tipomarcavehiculo` (
  `timaveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo marca vehículo',
  `timavenombre` varchar(50) NOT NULL COMMENT 'Nombre de la marca del tipo vehículo',
  `timaveactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de marcha del vehículo se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipomarcavehiculo`
--

INSERT INTO `tipomarcavehiculo` (`timaveid`, `timavenombre`, `timaveactiva`, `created_at`, `updated_at`) VALUES
(1, 'NISSAN', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'CHEVROLET', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'DAIHATSU', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'RENAULT', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 'HYUNDAI', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 'DAEWOO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, 'FORD', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(8, 'KIA', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(9, 'SUZUKI', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(10, 'MITSUBISHI', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(11, 'DFSK', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(12, 'MERCEDES BENZ', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(13, 'JAC', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(14, 'WILLYS', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(15, 'AGRALE', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(16, 'DODGE', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(17, 'INTERNATIONAL', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(18, 'HINO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(19, 'VOLKSWAGEN', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(20, 'MAZDA', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(21, 'SUSUKI', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(22, 'JEEP', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(23, 'FOTON', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomedio`
--

CREATE TABLE `tipomedio` (
  `tipmedid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de medio',
  `tipmednombre` varchar(20) NOT NULL COMMENT 'Nombre del tipo de medio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipomedio`
--

INSERT INTO `tipomedio` (`tipmedid`, `tipmednombre`) VALUES
(1, 'Impreso'),
(2, 'Correo'),
(3, 'Impreso / Correo'),
(4, 'Fax'),
(5, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipomodalidadvehiculo`
--

CREATE TABLE `tipomodalidadvehiculo` (
  `timoveid` varchar(2) NOT NULL COMMENT 'Identificador del la tabla tipo modalidad vehículo',
  `timovenombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de modalidad del vehículo',
  `timovecuotasostenimiento` varchar(9) NOT NULL COMMENT 'Cuota de sostenimiento del tipo de modalidad del vehículo',
  `timovedescuentopagoanticipado` varchar(4) NOT NULL COMMENT 'Descuento por pago anual anticipado de la cuota de sostenimiento de administración del tipo de modalidad del vehículo',
  `timoverecargomora` varchar(4) NOT NULL COMMENT 'Recargo de mora de la cuota de sostenimiento de administración del tipo de modalidad del vehículo',
  `timovetienedespacho` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo modalidad del vehículo tiene despacho',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipomodalidadvehiculo`
--

INSERT INTO `tipomodalidadvehiculo` (`timoveid`, `timovenombre`, `timovecuotasostenimiento`, `timovedescuentopagoanticipado`, `timoverecargomora`, `timovetienedespacho`, `created_at`, `updated_at`) VALUES
('C', 'COLECTIVO', '105000', '5', '5', 1, NULL, NULL),
('E', 'ESPECIAL', '105000', '5', '5', 1, NULL, NULL),
('I', 'INTERMUNICIPAL', '105000', '5', '5', 1, NULL, NULL),
('M', 'MIXTO', '105000', '5', '5', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipopersona`
--

CREATE TABLE `tipopersona` (
  `tipperid` varchar(2) NOT NULL COMMENT 'Identificador de la tabla tipo de persona',
  `tippernombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de persona'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipopersona`
--

INSERT INTO `tipopersona` (`tipperid`, `tippernombre`) VALUES
('A', 'Asociado'),
('C', 'Conductor'),
('E', 'Empleado'),
('X', 'Externo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipopersonadocumental`
--

CREATE TABLE `tipopersonadocumental` (
  `tipedoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de persona documental',
  `tipedonombre` varchar(150) NOT NULL COMMENT 'Nombre del tipo de persona documental',
  `tipedoactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de persona documental se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipopersonadocumental`
--

INSERT INTO `tipopersonadocumental` (`tipedoid`, `tipedonombre`, `tipedoactivo`, `created_at`, `updated_at`) VALUES
(1, 'El señor', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'El doctor', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'La doctora', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiporeferenciavehiculo`
--

CREATE TABLE `tiporeferenciavehiculo` (
  `tireveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo referencia vehículo',
  `tirevenombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo vehículo',
  `tireveactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de referencia del vehículo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tiporeferenciavehiculo`
--

INSERT INTO `tiporeferenciavehiculo` (`tireveid`, `tirevenombre`, `tireveactivo`, `created_at`, `updated_at`) VALUES
(1, 'URVAN', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'NKR-55', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'NKR', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'DELTA', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 'NKR-4', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 'TRAFIC', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, 'ATOS PRIME GL', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(8, 'CIELO', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(9, 'ATOS PRIME', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(10, 'TAXI 7:24', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(11, 'SYMBOL CITIUS', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(12, 'R-9', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(13, 'TAXI DIESEL', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(14, 'SUPER TAXI', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(15, 'CLIO EXPRESS', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(16, 'ATOS', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(17, 'SPARK', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(18, 'R-9 INYECCIÓN', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(19, 'MATIZ', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(20, 'CIELO BX', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(21, 'SYMBOL', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(22, 'LANOS', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(23, 'TAXI LANOS S', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(24, 'CBX 1047', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(25, 'LOGAN DYNAMIQUE', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposaludo`
--

CREATE TABLE `tiposaludo` (
  `tipsalid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de saludo',
  `tipsalnombre` varchar(100) NOT NULL COMMENT 'Nombre del tipo de saludo',
  `tipsalactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de saludo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tiposaludo`
--

INSERT INTO `tiposaludo` (`tipsalid`, `tipsalnombre`, `tipsalactivo`, `created_at`, `updated_at`) VALUES
(1, 'Apreciado señor,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'Apreciada señora,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'Apreciado proveedor,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'Cordial saludo,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 'Estimado señor,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 'Estimada señora,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, 'Estimado cliente,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(8, 'Estimado consultante,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(9, 'Distinguido señor,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(10, 'Distinguida señora,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(11, 'Distinguidos señores,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(12, 'Notable señora,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(13, 'Notables señores,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(14, 'Respetable señor,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(15, 'Respetable señora,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(16, 'Respetables señores,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(17, 'Amable señor,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(18, 'Amable señora,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(19, 'Notable señor,', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposancion`
--

CREATE TABLE `tiposancion` (
  `tipsanid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo sanción',
  `tipsannombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo vehículo',
  `tipsanactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de sanción se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposerviciovehiculo`
--

CREATE TABLE `tiposerviciovehiculo` (
  `tiseveid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de servicio del vehículo',
  `tisevenombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de servicio del vehículo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tiposerviciovehiculo`
--

INSERT INTO `tiposerviciovehiculo` (`tiseveid`, `tisevenombre`) VALUES
('B', 'Básico'),
('C', 'Común'),
('CR', 'Corriente'),
('E', 'Especial'),
('SS', 'Sin servicio');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipotramite`
--

CREATE TABLE `tipotramite` (
  `tiptraid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo de trámite',
  `tiptranombre` varchar(30) NOT NULL COMMENT 'Nombre del tipo de trámite'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipotramite`
--

INSERT INTO `tipotramite` (`tiptraid`, `tiptranombre`) VALUES
(1, 'Archivar'),
(2, 'Socializar'),
(3, 'Enviar a otra dependencia'),
(4, 'Dar respuesta'),
(5, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipovehiculo`
--

CREATE TABLE `tipovehiculo` (
  `tipvehid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo vehículo',
  `tipvehnombre` varchar(50) NOT NULL COMMENT 'Nombre del tipo vehículo',
  `tipvehreferencia` varchar(30) DEFAULT NULL COMMENT 'Referencia del tipo vehículo',
  `tipvehcapacidad` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Capacidad del tipo de vehículo',
  `tipvehnumerofilas` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Número de filas del tipo de vehículo',
  `tipvehnumerocolumnas` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Número de columnas del tipo de vehículo',
  `tipvehclasecss` varchar(50) NOT NULL DEFAULT 'distribucionPuestoGeneral' COMMENT 'Clase en CSS para poder visualizar el vehículo con su puesto',
  `tipvehactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo vehículo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipovehiculo`
--

INSERT INTO `tipovehiculo` (`tipvehid`, `tipvehnombre`, `tipvehreferencia`, `tipvehcapacidad`, `tipvehnumerofilas`, `tipvehnumerocolumnas`, `tipvehclasecss`, `tipvehactivo`, `created_at`, `updated_at`) VALUES
(1, 'AUTOMÓVIL', NULL, 4, 2, 3, 'distribucionPuestoTaxi', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(2, 'BUS', '24P', 24, 7, 5, 'distribucionPuestoBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(3, 'BUS', '25P', 25, 7, 5, 'distribucionPuestoBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(4, 'BUS', '26P', 26, 8, 5, 'distribucionPuestoBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(5, 'BUS', '28P', 28, 8, 5, 'distribucionPuestoBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(6, 'BUS', '30P', 30, 8, 5, 'distribucionPuestoBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(7, 'BUS', '32P', 32, 10, 5, 'distribucionPuestoGeneral', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(8, 'BUS', '33P', 33, 10, 5, 'distribucionPuestoGeneral', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(9, 'BUS', '34P', 34, 9, 5, 'distribucionPuestoGeneral', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(10, 'BUS', '36P', 36, 11, 5, 'distribucionPuestoGeneral', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(11, 'BUS', '37P', 37, 11, 5, 'distribucionPuestoGeneral', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(12, 'BUS', '38P', 38, 11, 5, 'distribucionPuestoGeneral', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(13, 'BUSETA', NULL, 22, 6, 5, 'distribucionPuestoMicroBusDos', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(14, 'CAMION', NULL, 8, 2, 5, 'distribucionPuestoTaxi', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(15, 'CAMIONETA', NULL, 7, 3, 3, 'distribucionPuestoMicroBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(16, 'JEEP', NULL, 5, 3, 3, 'distribucionPuestoMicroBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(17, 'MICROBUS', '06P', 6, 3, 3, 'distribucionPuestoMicroBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(18, 'MICROBUS', '08P', 8, 4, 3, 'distribucionPuestoMicroBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(19, 'MICROBUS', '09P', 9, 4, 4, 'distribucionPuestoMicroBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(20, 'MICROBUS', '11P', 11, 4, 3, 'distribucionPuestoMicroBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(21, 'MICROBUS', '12P', 12, 4, 4, 'distribucionPuestoMicroBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(22, 'MICROBUS', '14P', 14, 6, 4, 'distribucionPuestoMicroBusDos', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(23, 'MICROBUS', '15P', 15, 5, 4, 'distribucionPuestoMicroBusDos', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(24, 'MICROBUS', '16P', 16, 6, 4, 'distribucionPuestoMicroBusDos', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(25, 'MICROBUS', '17P', 17, 6, 4, 'distribucionPuestoMicroBusDos', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(26, 'MICROBUS', '18P', 18, 6, 5, 'distribucionPuestoMicroBusDos', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(27, 'MICROBUS', '19P', 19, 6, 5, 'distribucionPuestoMicroBusDos', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(28, 'MICROBUS', '20P', 20, 6, 5, 'distribucionPuestoMicroBusDos', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(29, 'MICROBUS', 'CARNIVAL', 7, 3, 3, 'distribucionPuestoMicroBus', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(30, 'MICROBUS', 'SPRINTER', 15, 5, 4, 'distribucionPuestoMicroBusDos', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(31, 'MICROBUS', 'URVAN', 9, 4, 3, 'distribucionPuestoMicroBus', 0, '2024-02-01 20:59:39', '2024-02-01 20:59:39'),
(32, 'MOTO', NULL, 1, 1, 1, 'distribucionPuestoTaxi', 1, '2024-02-01 20:59:39', '2024-02-01 20:59:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipovehiculodistribucion`
--

CREATE TABLE `tipovehiculodistribucion` (
  `tivediid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo vehículo distribución',
  `tipvehid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de vehículo',
  `tivedicolumna` varchar(3) NOT NULL COMMENT 'Columna de distribución de tipo de vehículo',
  `tivedifila` varchar(3) NOT NULL COMMENT 'Fila de distribución de tipo de vehículo',
  `tivedipuesto` varchar(3) NOT NULL COMMENT 'Contenido del número de ubicación del tipo de vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiquete`
--

CREATE TABLE `tiquete` (
  `tiquid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tiquete',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia que esta generando el tiquete',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el registro del tiquete',
  `plarutid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la planilla ruta',
  `perserid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona que utiliza el servicio del tiquete',
  `depaidorigen` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de origen del tiquete',
  `muniidorigen` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de origen del tiquete',
  `depaiddestino` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento de destino del tiquete',
  `muniiddestino` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del municipio de destino del tiquete',
  `tiquanio` year(4) NOT NULL COMMENT 'Año en el cual se registra el tiquete',
  `tiquconsecutivo` varchar(5) NOT NULL COMMENT 'Consecutivo del tiquete asignado por cada año',
  `tiqufechahoraregistro` datetime NOT NULL COMMENT 'Fecha y hora actual en el que se registra el tiquete',
  `tiqucantidad` decimal(4,0) NOT NULL COMMENT 'Cantidad de puesto en el tiquete',
  `tiquvalortiquete` decimal(10,0) NOT NULL COMMENT 'Valor del tiquete',
  `tiquvalordescuento` decimal(10,0) DEFAULT NULL COMMENT 'Valor de descuento del tiquete',
  `tiquvalorfondoreposicion` decimal(10,0) NOT NULL COMMENT 'Valor del fondo de reposición del tiquete',
  `tiquvalortotal` decimal(10,0) NOT NULL COMMENT 'Valor total del tiquete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiquetepuesto`
--

CREATE TABLE `tiquetepuesto` (
  `tiqpueid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tiquete puesto',
  `tiquid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del tiquete',
  `tiqpuenumeropuesto` varchar(3) NOT NULL COMMENT 'Número de puesto en el tiquete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokenfirmapersona`
--

CREATE TABLE `tokenfirmapersona` (
  `tofipeid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla token firma',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `tofipetoken` varchar(20) NOT NULL COMMENT 'Token creado aleatoriamente para validar la firma',
  `tofipefechahoranotificacion` datetime NOT NULL COMMENT 'Fecha y hora de la cual se envio la notifiación',
  `tofipefechahoramaxvalidez` datetime NOT NULL COMMENT 'Fecha y hora maxima de validez del token',
  `tofipemensajecorreo` varchar(500) DEFAULT NULL COMMENT 'Contendio de la información enviada al correo',
  `tofipemensajecelular` varchar(200) DEFAULT NULL COMMENT 'Contendio de la información enviada al celular',
  `tofipeutilizado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el token fue utilizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla usuario',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia a la que esta asignado el usuario',
  `cajaid` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador de la caja',
  `usuanombre` varchar(50) NOT NULL COMMENT 'Nombre del usuario',
  `usuaapellidos` varchar(50) NOT NULL COMMENT 'Apellidos del usuario',
  `usuaemail` varchar(80) NOT NULL COMMENT 'Correo del usuario',
  `usuanick` varchar(20) NOT NULL COMMENT 'Nick del usuario',
  `usuaalias` varchar(50) DEFAULT NULL COMMENT 'Alias para colocar como transcriptor del documento en la gestion documental',
  `password` varchar(255) NOT NULL COMMENT 'Password del usuario',
  `usuacambiarpassword` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el usuario debe cambar la contraseña para poder inciar sesión',
  `usuabloqueado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el usuario esta bloqueado',
  `usuaactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el usuario esta activo',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuaid`, `persid`, `agenid`, `cajaid`, `usuanombre`, `usuaapellidos`, `usuaemail`, `usuanick`, `usuaalias`, `password`, `usuacambiarpassword`, `usuabloqueado`, `usuaactivo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 101, NULL, 'SISTEMA', 'SISTEMA', 'notificacioncootranshacaritama@gmail.com', 'SISTEMA', 'SISTEMA', '$2y$10$fdJcE5k.zPxd3sHfY1UIZufgyQ3ISrNXLp2/k1qLs5vDjnTTDILV6', 0, 0, 0, NULL, NULL, NULL),
(2, 2, 101, NULL, 'RAMÓN DAVID', 'SALAZAR RINCÓN', 'radasa10@hotmail.com', 'RSALAZAR', 'Salazar R.', '$2y$10$G6awQ38z6YEiUZc1U7FdWOnCwO41eDBbN6bhJ8dlyt4BCQ65ObT7.', 0, 0, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuariorol`
--

CREATE TABLE `usuariorol` (
  `usurolid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla usuario rol',
  `usurolusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `usurolrolid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuariorol`
--

INSERT INTO `usuariorol` (`usurolid`, `usurolusuaid`, `usurolrolid`) VALUES
(1, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculo`
--

CREATE TABLE `vehiculo` (
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo',
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del asociado',
  `tipvehid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de vehículo',
  `tireveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de referencia del vehículo',
  `timaveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo marca vehículo',
  `ticoveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo color vehículo',
  `timoveid` varchar(2) NOT NULL COMMENT 'Identificador del tipo modalidad vehículo',
  `ticaveid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo carroceria vehículo',
  `ticovhid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo combustible vehículo',
  `agenid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la agencia a la que esta asignado el vehículo',
  `tiesveid` varchar(2) NOT NULL COMMENT 'Identificador del tipo estado vehículo',
  `vehifechaingreso` date NOT NULL COMMENT 'Fecha de ingreso del vehículo a la cooperativa',
  `vehinumerointerno` varchar(4) NOT NULL COMMENT 'Número interno del vehículo',
  `vehiplaca` varchar(8) NOT NULL COMMENT 'Placa del vehículo',
  `vehimodelo` varchar(4) NOT NULL COMMENT 'Modelo del vehículo',
  `vehicilindraje` varchar(6) DEFAULT NULL COMMENT 'Cilindraje del vehículo',
  `vehinumeromotor` varchar(30) DEFAULT NULL COMMENT 'Número del motor del vehículo',
  `vehinumerochasis` varchar(30) DEFAULT NULL COMMENT 'Número del chasis del vehículo',
  `vehinumeroserie` varchar(30) DEFAULT NULL COMMENT 'Número del serie del vehículo',
  `vehinumeroejes` varchar(4) DEFAULT NULL COMMENT 'Número del chasis del vehículo',
  `vehiesmotorregrabado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el vehículo tiene motor regrabado',
  `vehieschasisregrabado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el vehículo tiene chasis regrabado',
  `vehiesserieregrabado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el vehículo tiene serie regrabado',
  `vehiobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación general del vehículo',
  `vehirutafoto` varchar(100) DEFAULT NULL COMMENT 'Ruta de la foto del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculocambioestado`
--

CREATE TABLE `vehiculocambioestado` (
  `vecaesid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo cambio estado',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `tiesveid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de estado vehículo',
  `vecaesusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del vehículo',
  `vecaesfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del vehículo',
  `vecaesobservacion` varchar(500) DEFAULT NULL COMMENT 'Observación del cambio estado del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculocontrato`
--

CREATE TABLE `vehiculocontrato` (
  `vehconid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo contrato',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `asocid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del asociado',
  `persidgerente` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona que es gerente de la empresa',
  `vehconanio` year(4) NOT NULL COMMENT 'Año en el cual se realiza el contrato del vehículo',
  `vehconnumero` varchar(4) NOT NULL COMMENT 'Número de contrato del vehículo por cada año',
  `vehconfechainicial` date NOT NULL COMMENT 'Fecha inicial del contrato del vehículo',
  `vehconfechafinal` date NOT NULL COMMENT 'Fecha final del contrato del vehículo',
  `vehconobservacion` varchar(500) DEFAULT NULL COMMENT 'Observaciones realizada al contrato del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculocrt`
--

CREATE TABLE `vehiculocrt` (
  `vehcrtid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo CRT',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `vehcrtnumero` varchar(30) NOT NULL COMMENT 'Número del CRT del vehículo',
  `vehcrtfechainicial` date NOT NULL COMMENT 'Fecha inicial del CRT del vehículo',
  `vehcrtfechafinal` date NOT NULL COMMENT 'Fecha final del CRT del vehículo',
  `vehcrtextension` varchar(5) DEFAULT NULL COMMENT 'Extensión del archivo que se anexa del CRT del vehículo',
  `vehcrtnombrearchivooriginal` varchar(200) DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa del CRT del vehículo',
  `vehcrtnombrearchivoeditado` varchar(200) DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa del CRT del vehículo',
  `vehcrtrutaarchivo` varchar(500) DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa del CRT del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculopoliza`
--

CREATE TABLE `vehiculopoliza` (
  `vehpolid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo póliza',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `vehpolnumeropolizacontractual` varchar(30) NOT NULL COMMENT 'Número de póliza contractual del vehículo',
  `vehpolnumeropolizaextcontrac` varchar(30) NOT NULL COMMENT 'Número de póliza extra contractual del vehículo',
  `vehpolfechainicial` date NOT NULL COMMENT 'Fecha inicial de la póliza del vehículo',
  `vehpolfechafinal` date NOT NULL COMMENT 'Fecha final de la póliza  del vehículo',
  `vehpolextension` varchar(5) DEFAULT NULL COMMENT 'Extensión del archivo que se anexa de la póliza del vehículo',
  `vehpolnombrearchivooriginal` varchar(200) DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa de la póliza del vehículo',
  `vehpolnombrearchivoeditado` varchar(200) DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa de la póliza del vehículo',
  `vehpolrutaarchivo` varchar(500) DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa de la póliza del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculoresponsabilidad`
--

CREATE TABLE `vehiculoresponsabilidad` (
  `vehresid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo responsabilidad',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `vehresfechacompromiso` date NOT NULL COMMENT 'Fecha máxima en la cual se debe realizar el pago de la responsabilidad',
  `vehresvalorresponsabilidad` decimal(8,0) NOT NULL COMMENT 'Valor de la responsabilidad o cuota del pago mensual',
  `vehresfechapagado` date DEFAULT NULL COMMENT 'Fecha en la cual se realiza el pago de la responsabilidad',
  `vehresdescuento` decimal(8,0) DEFAULT NULL COMMENT 'Valor de escuento por pago anticipado en la responsabilidad pagado',
  `vehresinteresmora` decimal(8,0) DEFAULT NULL COMMENT 'Valor de interés de mora en la responsabilidad pagado',
  `vehresvalorpagado` decimal(8,0) DEFAULT NULL COMMENT 'Valor de la responsabilidad pagado',
  `agenid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador de la agencia a la que recibe el pago',
  `usuaid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del usuario que recibe el pago',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculosoat`
--

CREATE TABLE `vehiculosoat` (
  `vehsoaid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo SOAT',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `vehsoanumero` varchar(30) NOT NULL COMMENT 'Número del SOAT del vehículo',
  `vehsoafechainicial` date NOT NULL COMMENT 'Fecha inicial del SOAT del vehículo',
  `vehsoafechafinal` date NOT NULL COMMENT 'Fecha final del SOAT del vehículo',
  `vehsoaextension` varchar(5) DEFAULT NULL COMMENT 'Extensión del archivo que se anexa del SOAT del vehículo',
  `vehsoanombrearchivooriginal` varchar(200) DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa del SOAT del vehículo',
  `vehsoanombrearchivoeditado` varchar(200) DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa del SOAT del vehículo',
  `vehsoarutaarchivo` varchar(500) DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa del SOAT del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculosuspendido`
--

CREATE TABLE `vehiculosuspendido` (
  `vehsusid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo responsabilidad',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que suspende el vehículo',
  `vehsusfechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el registro de la suspención del vehículo',
  `vehsusfechainicialsuspencion` date NOT NULL COMMENT 'Fecha inicial de la suspención del vehículo',
  `vehsusfechafinalsuspencion` date NOT NULL COMMENT 'Fecha inicial de la suspención del vehículo',
  `vehsusmotivo` varchar(500) NOT NULL COMMENT 'Motivo de la suspención del vehículo',
  `vehsusprocesada` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la supención del vehículo ha sido procesada',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculotarjetaoperacion`
--

CREATE TABLE `vehiculotarjetaoperacion` (
  `vetaopaid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla vehículo tarjeta operación',
  `vehiid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del vehículo',
  `tiseveid` varchar(2) NOT NULL COMMENT 'Identificador del tipo de servicio del vehículo',
  `vetaopnumero` varchar(30) NOT NULL COMMENT 'Número de la tarjeta de operación del vehículo',
  `vetaopfechainicial` date NOT NULL COMMENT 'Fecha inicial de la tarjeta de operación del vehículo',
  `vetaopfechafinal` date NOT NULL COMMENT 'Fecha final de la tarjeta de operación del vehículo',
  `vetaopenteadministrativo` varchar(2) NOT NULL COMMENT 'Ente administrativo que emite la tarjeta de operación del vehículo',
  `vetaopradioaccion` varchar(2) NOT NULL COMMENT 'Radio de acción de la tarjeta de operación del vehículo',
  `vetaopextension` varchar(5) DEFAULT NULL COMMENT 'Extensión del archivo que se anexa a la tarjeta de operación del vehículo',
  `vetaopnombrearchivooriginal` varchar(200) DEFAULT NULL COMMENT 'Nombre con el cual se ha subido el archivo que se anexa a la tarjeta de operación del vehículo',
  `vetaopnombrearchivoeditado` varchar(200) DEFAULT NULL COMMENT 'Nombre editado con el cual se ha subido el archivo que se anexa a la tarjeta de operación del vehículo',
  `vetaoprutaarchivo` varchar(500) DEFAULT NULL COMMENT 'Ruta enfuscada del archivo que se anexa a la tarjeta de operación del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agencia`
--
ALTER TABLE `agencia`
  ADD PRIMARY KEY (`agenid`),
  ADD KEY `fk_depaagen` (`agendepaid`),
  ADD KEY `fk_muniagen` (`agenmuniid`),
  ADD KEY `fk_persagen` (`persidresponsable`);

--
-- Indices de la tabla `archivohistorico`
--
ALTER TABLE `archivohistorico`
  ADD PRIMARY KEY (`archisid`),
  ADD KEY `fk_tipdocarchis` (`tipdocid`),
  ADD KEY `fk_usuaarchis` (`usuaid`),
  ADD KEY `fk_tiesararchis` (`tiesarid`),
  ADD KEY `fk_ticauarchis` (`ticaubid`),
  ADD KEY `fk_ticrubarchis` (`ticrubid`);

--
-- Indices de la tabla `archivohistoricodigitalizado`
--
ALTER TABLE `archivohistoricodigitalizado`
  ADD PRIMARY KEY (`arhidiid`),
  ADD KEY `fk_archisarhidi` (`archisid`);

--
-- Indices de la tabla `asociado`
--
ALTER TABLE `asociado`
  ADD PRIMARY KEY (`asocid`),
  ADD KEY `fk_persasoc` (`persid`),
  ADD KEY `fk_tiesasasoc` (`tiesasid`);

--
-- Indices de la tabla `asociadocambioestado`
--
ALTER TABLE `asociadocambioestado`
  ADD PRIMARY KEY (`ascaesid`),
  ADD KEY `fk_asocascaes` (`asocid`),
  ADD KEY `fk_tiesasascaes` (`tiesasid`),
  ADD KEY `fk_usuaascaes` (`ascaesusuaid`);

--
-- Indices de la tabla `asociadosancion`
--
ALTER TABLE `asociadosancion`
  ADD PRIMARY KEY (`asosanid`),
  ADD KEY `fk_asocasosan` (`asocid`),
  ADD KEY `fk_usuaasosan` (`usuaid`),
  ADD KEY `fk_tipsanasosan` (`tipsanid`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`cajaid`);

--
-- Indices de la tabla `cargolaboral`
--
ALTER TABLE `cargolaboral`
  ADD PRIMARY KEY (`carlabid`);

--
-- Indices de la tabla `coddocumprocesoacta`
--
ALTER TABLE `coddocumprocesoacta`
  ADD PRIMARY KEY (`codopaid`),
  ADD UNIQUE KEY `uk_coddocumprocesoacta` (`codopaconsecutivo`,`codopasigla`,`codopaanio`),
  ADD KEY `fk_codoprcodopa` (`codoprid`),
  ADD KEY `fk_tipactcodopa` (`tipactid`),
  ADD KEY `fk_usuacodopa` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesoanexo`
--
ALTER TABLE `coddocumprocesoanexo`
  ADD PRIMARY KEY (`codopxid`),
  ADD KEY `fk_codoprcodopx` (`codoprid`);

--
-- Indices de la tabla `coddocumprocesocambioestado`
--
ALTER TABLE `coddocumprocesocambioestado`
  ADD PRIMARY KEY (`codpceid`),
  ADD KEY `fk_codoprcodpce` (`codoprid`),
  ADD KEY `fk_tiesdocodpce` (`tiesdoid`),
  ADD KEY `fk_usuacodpce` (`codpceusuaid`);

--
-- Indices de la tabla `coddocumprocesocertificado`
--
ALTER TABLE `coddocumprocesocertificado`
  ADD PRIMARY KEY (`codopcid`),
  ADD UNIQUE KEY `uk_coddocumprocesocertificado` (`codopcconsecutivo`,`codopcsigla`,`codopcanio`),
  ADD KEY `fk_codoprcodopc` (`codoprid`),
  ADD KEY `fk_tippedocodopc` (`tipedoid`),
  ADD KEY `fk_usuacodopc` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesocircular`
--
ALTER TABLE `coddocumprocesocircular`
  ADD PRIMARY KEY (`codoplid`),
  ADD UNIQUE KEY `uk_coddocumprocesocircular` (`codoplconsecutivo`,`codoplsigla`,`codoplanio`),
  ADD KEY `fk_codoprcodopl` (`codoprid`),
  ADD KEY `fk_usuacodopl` (`usuaid`),
  ADD KEY `fk_tipdescodopl` (`tipdesid`);

--
-- Indices de la tabla `coddocumprocesocitacion`
--
ALTER TABLE `coddocumprocesocitacion`
  ADD PRIMARY KEY (`codoptid`),
  ADD UNIQUE KEY `uk_coddocumprocesocitacion` (`codoptconsecutivo`,`codoptsigla`,`codoptanio`),
  ADD KEY `fk_codoprcodopt` (`codoprid`),
  ADD KEY `fk_tipactcodopt` (`tipactid`),
  ADD KEY `fk_usuacodopt` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesocompartido`
--
ALTER TABLE `coddocumprocesocompartido`
  ADD PRIMARY KEY (`codopdid`),
  ADD KEY `fk_codoprcodopd` (`codoprid`),
  ADD KEY `fk_usuacodopd` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesoconstancia`
--
ALTER TABLE `coddocumprocesoconstancia`
  ADD PRIMARY KEY (`codopnid`),
  ADD UNIQUE KEY `uk_coddocumprocesocontancia` (`codopnconsecutivo`,`codopnsigla`,`codopnanio`),
  ADD KEY `fk_codoprcodopn` (`codoprid`),
  ADD KEY `fk_tippedocodopn` (`tipedoid`),
  ADD KEY `fk_usuacodopn` (`usuaid`);

--
-- Indices de la tabla `coddocumprocesocopia`
--
ALTER TABLE `coddocumprocesocopia`
  ADD PRIMARY KEY (`codoppid`),
  ADD KEY `fk_codoprcodopp` (`codoprid`),
  ADD KEY `fk_depecodopp` (`depeid`);

--
-- Indices de la tabla `coddocumprocesofirma`
--
ALTER TABLE `coddocumprocesofirma`
  ADD PRIMARY KEY (`codopfid`),
  ADD KEY `fk_codoprcodopf` (`codoprid`),
  ADD KEY `fk_perscodopf` (`persid`),
  ADD KEY `fk_carlabcodopf` (`carlabid`);

--
-- Indices de la tabla `coddocumprocesooficio`
--
ALTER TABLE `coddocumprocesooficio`
  ADD PRIMARY KEY (`codopoid`),
  ADD UNIQUE KEY `uk_coddocumprocesooficio` (`codopoconsecutivo`,`codoposigla`,`codopoanio`),
  ADD KEY `fk_codoprcodopo` (`codoprid`),
  ADD KEY `fk_usuacodopo` (`usuaid`),
  ADD KEY `fk_tipsalcodopo` (`tipsalid`),
  ADD KEY `fk_tipdescodopo` (`tipdesid`);

--
-- Indices de la tabla `coddocumprocesoraddocentrante`
--
ALTER TABLE `coddocumprocesoraddocentrante`
  ADD PRIMARY KEY (`cdprdeid`),
  ADD KEY `fk_codoprcdprde` (`codoprid`),
  ADD KEY `fk_radoencdprde` (`radoenid`);

--
-- Indices de la tabla `codigodocumental`
--
ALTER TABLE `codigodocumental`
  ADD PRIMARY KEY (`coddocid`),
  ADD KEY `fk_depecoddoc` (`depeid`),
  ADD KEY `fk_serdoccoddoc` (`serdocid`),
  ADD KEY `fk_susedocoddoc` (`susedoid`),
  ADD KEY `fk_tipdoccoddoc` (`tipdocid`),
  ADD KEY `fk_tipmedcoddoc` (`tipmedid`),
  ADD KEY `fk_tiptracoddoc` (`tiptraid`),
  ADD KEY `fk_tipdepcoddoc` (`tipdetid`),
  ADD KEY `fk_coddocuser` (`usuaid`);

--
-- Indices de la tabla `codigodocumentalproceso`
--
ALTER TABLE `codigodocumentalproceso`
  ADD PRIMARY KEY (`codoprid`),
  ADD KEY `fk_coddoccodopr` (`coddocid`),
  ADD KEY `fk_tiesdocodopr` (`tiesdoid`);

--
-- Indices de la tabla `colocacion`
--
ALTER TABLE `colocacion`
  ADD PRIMARY KEY (`coloid`),
  ADD UNIQUE KEY `uk_colocacion` (`coloanio`,`colonumerodesembolso`),
  ADD KEY `fk_usuacolo` (`usuaid`),
  ADD KEY `fk_solcrecolo` (`solcreid`),
  ADD KEY `fk_tiesclcolo` (`tiesclid`);

--
-- Indices de la tabla `colocacioncambioestado`
--
ALTER TABLE `colocacioncambioestado`
  ADD PRIMARY KEY (`cocaesid`),
  ADD KEY `fk_colococaes` (`coloid`),
  ADD KEY `fk_tiesclcocaes` (`tiesclid`),
  ADD KEY `fk_usuaclcaes` (`cocaesusuaid`);

--
-- Indices de la tabla `colocacionliquidacion`
--
ALTER TABLE `colocacionliquidacion`
  ADD PRIMARY KEY (`colliqid`),
  ADD KEY `fk_colocolliq` (`coloid`);

--
-- Indices de la tabla `comprobantecontable`
--
ALTER TABLE `comprobantecontable`
  ADD PRIMARY KEY (`comconid`),
  ADD UNIQUE KEY `uk_comprobantecontable` (`agenid`,`comconanio`,`comconconsecutivo`),
  ADD KEY `fk_movcajcomcon` (`movcajid`),
  ADD KEY `fk_usuacomcon` (`usuaid`),
  ADD KEY `fk_cajacomcon` (`cajaid`);

--
-- Indices de la tabla `comprobantecontabledetalle`
--
ALTER TABLE `comprobantecontabledetalle`
  ADD PRIMARY KEY (`cocodeid`),
  ADD KEY `fk_comconcocode` (`comconid`),
  ADD KEY `fk_cueconcocode` (`cueconid`);

--
-- Indices de la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD PRIMARY KEY (`condid`),
  ADD KEY `fk_perscond` (`persid`),
  ADD KEY `fk_tiescocond` (`tiescoid`),
  ADD KEY `fk_tipconcond` (`tipconid`),
  ADD KEY `fk_agencond` (`agenid`);

--
-- Indices de la tabla `conductorcambioestado`
--
ALTER TABLE `conductorcambioestado`
  ADD PRIMARY KEY (`cocaesid`),
  ADD KEY `fk_condcocaes` (`condid`),
  ADD KEY `fk_tiesascocaes` (`tiescoid`),
  ADD KEY `fk_usuacocaes` (`cocaesusuaid`);

--
-- Indices de la tabla `conductorlicencia`
--
ALTER TABLE `conductorlicencia`
  ADD PRIMARY KEY (`conlicid`),
  ADD KEY `fk_condconlic` (`condid`),
  ADD KEY `fk_ticaliconlic` (`ticaliid`);

--
-- Indices de la tabla `conductorvehiculo`
--
ALTER TABLE `conductorvehiculo`
  ADD PRIMARY KEY (`convehid`),
  ADD KEY `fk_condconveh` (`condid`),
  ADD KEY `fk_vehiconveh` (`vehiid`);

--
-- Indices de la tabla `configuracionencomienda`
--
ALTER TABLE `configuracionencomienda`
  ADD PRIMARY KEY (`conencid`);

--
-- Indices de la tabla `consignacionbancaria`
--
ALTER TABLE `consignacionbancaria`
  ADD PRIMARY KEY (`conbanid`),
  ADD KEY `fk_entfinconban` (`entfinid`),
  ADD KEY `fk_usuaconban` (`usuaid`),
  ADD KEY `fk_agenconban` (`agenid`);

--
-- Indices de la tabla `contratoservicioespecial`
--
ALTER TABLE `contratoservicioespecial`
  ADD PRIMARY KEY (`coseesid`),
  ADD UNIQUE KEY `uk_contratoservicioespecial` (`coseesanio`,`coseesconsecutivo`),
  ADD KEY `fk_pecosecosees` (`pecoseid`),
  ADD KEY `fk_perscosees` (`persidgerente`),
  ADD KEY `fk_ticosecosees` (`ticoseid`),
  ADD KEY `fk_ticosscosees` (`ticossid`);

--
-- Indices de la tabla `contratoservicioespecialcond`
--
ALTER TABLE `contratoservicioespecialcond`
  ADD PRIMARY KEY (`coseecod`),
  ADD KEY `fk_coseescoseeco` (`coseesid`),
  ADD KEY `fk_condcoseeco` (`condid`);

--
-- Indices de la tabla `contratoservicioespecialvehi`
--
ALTER TABLE `contratoservicioespecialvehi`
  ADD PRIMARY KEY (`coseevid`),
  ADD KEY `fk_coseescoseev` (`coseesid`),
  ADD KEY `fk_vehicoseev` (`vehiid`);

--
-- Indices de la tabla `cuentacontable`
--
ALTER TABLE `cuentacontable`
  ADD PRIMARY KEY (`cueconid`);

--
-- Indices de la tabla `departamento`
--
ALTER TABLE `departamento`
  ADD PRIMARY KEY (`depaid`),
  ADD UNIQUE KEY `uk_departamento` (`depacodigo`);

--
-- Indices de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  ADD PRIMARY KEY (`depeid`),
  ADD UNIQUE KEY `uk_dependencia1` (`depecodigo`),
  ADD UNIQUE KEY `uk_dependencia2` (`depesigla`),
  ADD KEY `fk_persdepe` (`depejefeid`);

--
-- Indices de la tabla `dependenciapersona`
--
ALTER TABLE `dependenciapersona`
  ADD PRIMARY KEY (`depperid`),
  ADD UNIQUE KEY `uk_dependenciapersona` (`depperdepeid`,`depperpersid`),
  ADD KEY `fk_persdepper` (`depperpersid`);

--
-- Indices de la tabla `dependenciasubseriedocumental`
--
ALTER TABLE `dependenciasubseriedocumental`
  ADD PRIMARY KEY (`desusdid`),
  ADD KEY `fk_susedodesusd` (`desusdsusedoid`),
  ADD KEY `fk_depedesusd` (`desusddepeid`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`emprid`),
  ADD KEY `fk_depaempr` (`emprdepaid`),
  ADD KEY `fk_muniempr` (`emprmuniid`),
  ADD KEY `fk_persempr` (`persidrepresentantelegal`);

--
-- Indices de la tabla `encomienda`
--
ALTER TABLE `encomienda`
  ADD PRIMARY KEY (`encoid`),
  ADD UNIQUE KEY `uk_encomienda` (`agenid`,`encoanio`,`encoconsecutivo`),
  ADD KEY `fk_usuaenco` (`usuaid`),
  ADD KEY `fk_plarutenco` (`plarutid`),
  ADD KEY `fk_perserencoremitente` (`perseridremitente`),
  ADD KEY `fk_perserencodestino` (`perseriddestino`),
  ADD KEY `fk_depaencoorigen` (`depaidorigen`),
  ADD KEY `fk_muniencoorigen` (`muniidorigen`),
  ADD KEY `fk_depaencodestino` (`depaiddestino`),
  ADD KEY `fk_muniencodestino` (`muniiddestino`),
  ADD KEY `fk_tipencenco` (`tipencid`),
  ADD KEY `fk_tiesenenco` (`tiesenid`);

--
-- Indices de la tabla `encomiendacambioestado`
--
ALTER TABLE `encomiendacambioestado`
  ADD PRIMARY KEY (`encaesid`),
  ADD KEY `fk_encoencaes` (`encoid`),
  ADD KEY `fk_tiesenencaes` (`tiesenid`),
  ADD KEY `fk_usuaencaes` (`encaesusuaid`);

--
-- Indices de la tabla `entidadfinanciera`
--
ALTER TABLE `entidadfinanciera`
  ADD PRIMARY KEY (`entfinid`);

--
-- Indices de la tabla `festivo`
--
ALTER TABLE `festivo`
  ADD PRIMARY KEY (`festid`);

--
-- Indices de la tabla `funcionalidad`
--
ALTER TABLE `funcionalidad`
  ADD PRIMARY KEY (`funcid`),
  ADD KEY `fk_modufunc` (`moduid`);

--
-- Indices de la tabla `historialcontrasena`
--
ALTER TABLE `historialcontrasena`
  ADD PRIMARY KEY (`hisconid`),
  ADD KEY `fk_usuahiscon` (`usuaid`);

--
-- Indices de la tabla `informacionconfiguracioncorreo`
--
ALTER TABLE `informacionconfiguracioncorreo`
  ADD PRIMARY KEY (`incocoid`);

--
-- Indices de la tabla `informaciongeneralpdf`
--
ALTER TABLE `informaciongeneralpdf`
  ADD PRIMARY KEY (`ingpdfid`),
  ADD UNIQUE KEY `uk_informaciongeneralpdf` (`ingpdfnombre`);

--
-- Indices de la tabla `informacionnotificacioncorreo`
--
ALTER TABLE `informacionnotificacioncorreo`
  ADD PRIMARY KEY (`innocoid`),
  ADD UNIQUE KEY `uk_infornotificacioncorreo` (`innoconombre`);

--
-- Indices de la tabla `ingresosistema`
--
ALTER TABLE `ingresosistema`
  ADD PRIMARY KEY (`ingsisid`),
  ADD KEY `fk_usuaingsis` (`usuaid`);

--
-- Indices de la tabla `intentosfallidos`
--
ALTER TABLE `intentosfallidos`
  ADD PRIMARY KEY (`intfalid`);

--
-- Indices de la tabla `lineacredito`
--
ALTER TABLE `lineacredito`
  ADD PRIMARY KEY (`lincreid`);

--
-- Indices de la tabla `mensajeimpresion`
--
ALTER TABLE `mensajeimpresion`
  ADD PRIMARY KEY (`menimpid`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `modulo`
--
ALTER TABLE `modulo`
  ADD PRIMARY KEY (`moduid`);

--
-- Indices de la tabla `movimientocaja`
--
ALTER TABLE `movimientocaja`
  ADD PRIMARY KEY (`movcajid`),
  ADD KEY `fk_usuamovcaj` (`usuaid`),
  ADD KEY `fk_cajamovcaj` (`cajaid`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`muniid`),
  ADD UNIQUE KEY `uk_municipio` (`municodigo`),
  ADD KEY `fk_depamuni` (`munidepaid`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`persid`),
  ADD UNIQUE KEY `uk_personatipoidentificacion` (`tipideid`,`persdocumento`),
  ADD KEY `fk_carglabpers` (`carlabid`),
  ADD KEY `fk_tipperpers` (`tipperid`),
  ADD KEY `fk_depapersnac` (`persdepaidnacimiento`),
  ADD KEY `fk_munipersnac` (`persmuniidnacimiento`),
  ADD KEY `fk_depapersexp` (`persdepaidexpedicion`),
  ADD KEY `fk_munipersexp` (`persmuniidexpedicion`);

--
-- Indices de la tabla `personacontratoservicioesp`
--
ALTER TABLE `personacontratoservicioesp`
  ADD PRIMARY KEY (`pecoseid`),
  ADD UNIQUE KEY `uk_personacontratoservicioesp` (`tipideid`,`pecosedocumento`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `personaradicadocumento`
--
ALTER TABLE `personaradicadocumento`
  ADD PRIMARY KEY (`peradoid`),
  ADD UNIQUE KEY `uk_personaradicadocumento` (`tipideid`,`peradodocumento`);

--
-- Indices de la tabla `personaservicio`
--
ALTER TABLE `personaservicio`
  ADD PRIMARY KEY (`perserid`),
  ADD UNIQUE KEY `uk_personaservicio` (`tipideid`,`perserdocumento`);

--
-- Indices de la tabla `planillaruta`
--
ALTER TABLE `planillaruta`
  ADD PRIMARY KEY (`plarutid`),
  ADD UNIQUE KEY `uk_planillaruta` (`agenid`,`plarutanio`,`plarutconsecutivo`),
  ADD KEY `fk_rutaplarut` (`rutaid`),
  ADD KEY `fk_vehiplarut` (`vehiid`),
  ADD KEY `fk_condplarut` (`condid`),
  ADD KEY `fk_usuaplarutregistra` (`usuaidregistra`),
  ADD KEY `fk_usuaplarutdespacha` (`usuaiddespacha`);

--
-- Indices de la tabla `procesoautomatico`
--
ALTER TABLE `procesoautomatico`
  ADD PRIMARY KEY (`proautid`),
  ADD UNIQUE KEY `uk_procesoautomatico` (`proautnombre`);

--
-- Indices de la tabla `radicaciondocentanexo`
--
ALTER TABLE `radicaciondocentanexo`
  ADD PRIMARY KEY (`radoeaid`),
  ADD KEY `fk_radoenradoea` (`radoenid`);

--
-- Indices de la tabla `radicaciondocentcambioestado`
--
ALTER TABLE `radicaciondocentcambioestado`
  ADD PRIMARY KEY (`radeceid`),
  ADD KEY `fk_radoenradece` (`radoenid`),
  ADD KEY `fk_tierderadece` (`tierdeid`),
  ADD KEY `fk_usuaradece` (`radeceusuaid`);

--
-- Indices de la tabla `radicaciondocentdependencia`
--
ALTER TABLE `radicaciondocentdependencia`
  ADD PRIMARY KEY (`radoedid`),
  ADD KEY `fk_radoenradoed` (`radoenid`),
  ADD KEY `fk_deperadoed` (`depeid`),
  ADD KEY `fk_usuaradoed` (`radoedsuaid`);

--
-- Indices de la tabla `radicaciondocumentoentrante`
--
ALTER TABLE `radicaciondocumentoentrante`
  ADD PRIMARY KEY (`radoenid`),
  ADD UNIQUE KEY `uk_radicaciondocumentoentrante` (`radoenconsecutivo`,`radoenanio`),
  ADD KEY `fk_peradoradoen` (`peradoid`),
  ADD KEY `fk_tipmedradoen` (`tipmedid`),
  ADD KEY `fk_tierderadoen` (`tierdeid`),
  ADD KEY `fk_deparadoen` (`depaid`),
  ADD KEY `fk_muniradoen` (`muniid`),
  ADD KEY `fk_radoen` (`usuaid`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`rolid`);

--
-- Indices de la tabla `rolfuncionalidad`
--
ALTER TABLE `rolfuncionalidad`
  ADD PRIMARY KEY (`rolfunid`),
  ADD KEY `fk_rolrolfun` (`rolfunrolid`),
  ADD KEY `fk_funcrolfun` (`rolfunfuncid`);

--
-- Indices de la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD PRIMARY KEY (`rutaid`),
  ADD UNIQUE KEY `uk_ruta` (`depaidorigen`,`muniidorigen`,`depaiddestino`,`muniiddestino`),
  ADD KEY `fk_munirutaorigen` (`muniidorigen`),
  ADD KEY `fk_deparutadestino` (`depaiddestino`),
  ADD KEY `fk_munirutadestino` (`muniiddestino`);

--
-- Indices de la tabla `rutanodo`
--
ALTER TABLE `rutanodo`
  ADD PRIMARY KEY (`rutnodid`),
  ADD KEY `fk_rutarutnod` (`rutaid`),
  ADD KEY `fk_munirutnod` (`muniid`);

--
-- Indices de la tabla `seriedocumental`
--
ALTER TABLE `seriedocumental`
  ADD PRIMARY KEY (`serdocid`),
  ADD UNIQUE KEY `uk_serie` (`serdoccodigo`);

--
-- Indices de la tabla `solicitudcredito`
--
ALTER TABLE `solicitudcredito`
  ADD PRIMARY KEY (`solcreid`),
  ADD KEY `fk_usuasolcre` (`usuaid`),
  ADD KEY `fk_lincresolcre` (`lincreid`),
  ADD KEY `fk_asocsolcre` (`asocid`),
  ADD KEY `fk_vehisolcre` (`vehiid`),
  ADD KEY `fk_tiesscsolcre` (`tiesscid`);

--
-- Indices de la tabla `solicitudcreditocambioestado`
--
ALTER TABLE `solicitudcreditocambioestado`
  ADD PRIMARY KEY (`socrceid`),
  ADD KEY `fk_solcresocrce` (`solcreid`),
  ADD KEY `fk_tiesscsocrce` (`tiesscid`),
  ADD KEY `fk_usuasocrce` (`socrceusuaid`);

--
-- Indices de la tabla `subseriedocumental`
--
ALTER TABLE `subseriedocumental`
  ADD PRIMARY KEY (`susedoid`),
  ADD UNIQUE KEY `uk_serdocsusedo` (`serdocid`,`susedocodigo`),
  ADD KEY `fk_tipdocsusedo` (`tipdocid`);

--
-- Indices de la tabla `tarifatiquete`
--
ALTER TABLE `tarifatiquete`
  ADD PRIMARY KEY (`tartiqid`),
  ADD KEY `fk_rutatartiq` (`rutaid`),
  ADD KEY `fk_depatartiqdestino` (`depaiddestino`),
  ADD KEY `fk_munitartiqdestino` (`muniiddestino`);

--
-- Indices de la tabla `tipoacta`
--
ALTER TABLE `tipoacta`
  ADD PRIMARY KEY (`tipactid`);

--
-- Indices de la tabla `tipocajaubicacion`
--
ALTER TABLE `tipocajaubicacion`
  ADD PRIMARY KEY (`ticaubid`);

--
-- Indices de la tabla `tipocarpetaubicacion`
--
ALTER TABLE `tipocarpetaubicacion`
  ADD PRIMARY KEY (`ticrubid`);

--
-- Indices de la tabla `tipocarroceriavehiculo`
--
ALTER TABLE `tipocarroceriavehiculo`
  ADD PRIMARY KEY (`ticaveid`);

--
-- Indices de la tabla `tipocategorialicencia`
--
ALTER TABLE `tipocategorialicencia`
  ADD PRIMARY KEY (`ticaliid`);

--
-- Indices de la tabla `tipocolorvehiculo`
--
ALTER TABLE `tipocolorvehiculo`
  ADD PRIMARY KEY (`ticoveid`);

--
-- Indices de la tabla `tipocombustiblevehiculo`
--
ALTER TABLE `tipocombustiblevehiculo`
  ADD PRIMARY KEY (`ticovhid`);

--
-- Indices de la tabla `tipoconductor`
--
ALTER TABLE `tipoconductor`
  ADD PRIMARY KEY (`tipconid`);

--
-- Indices de la tabla `tipocontratoservicioespecial`
--
ALTER TABLE `tipocontratoservicioespecial`
  ADD PRIMARY KEY (`ticoseid`);

--
-- Indices de la tabla `tipoconvenioservicioespecial`
--
ALTER TABLE `tipoconvenioservicioespecial`
  ADD PRIMARY KEY (`ticossid`);

--
-- Indices de la tabla `tipodespedida`
--
ALTER TABLE `tipodespedida`
  ADD PRIMARY KEY (`tipdesid`);

--
-- Indices de la tabla `tipodestino`
--
ALTER TABLE `tipodestino`
  ADD PRIMARY KEY (`tipdetid`);

--
-- Indices de la tabla `tipodocumental`
--
ALTER TABLE `tipodocumental`
  ADD PRIMARY KEY (`tipdocid`),
  ADD UNIQUE KEY `uk_tipodocumental` (`tipdoccodigo`);

--
-- Indices de la tabla `tipoencomienda`
--
ALTER TABLE `tipoencomienda`
  ADD PRIMARY KEY (`tipencid`);

--
-- Indices de la tabla `tipoestadoasociado`
--
ALTER TABLE `tipoestadoasociado`
  ADD PRIMARY KEY (`tiesasid`);

--
-- Indices de la tabla `tipoestadocolocacion`
--
ALTER TABLE `tipoestadocolocacion`
  ADD PRIMARY KEY (`tiesclid`);

--
-- Indices de la tabla `tipoestadoconductor`
--
ALTER TABLE `tipoestadoconductor`
  ADD PRIMARY KEY (`tiescoid`);

--
-- Indices de la tabla `tipoestadodocumento`
--
ALTER TABLE `tipoestadodocumento`
  ADD PRIMARY KEY (`tiesdoid`);

--
-- Indices de la tabla `tipoestadoencomienda`
--
ALTER TABLE `tipoestadoencomienda`
  ADD PRIMARY KEY (`tiesenid`);

--
-- Indices de la tabla `tipoestadoraddocentrante`
--
ALTER TABLE `tipoestadoraddocentrante`
  ADD PRIMARY KEY (`tierdeid`);

--
-- Indices de la tabla `tipoestadosolicitudcredito`
--
ALTER TABLE `tipoestadosolicitudcredito`
  ADD PRIMARY KEY (`tiesscid`);

--
-- Indices de la tabla `tipoestadovehiculo`
--
ALTER TABLE `tipoestadovehiculo`
  ADD PRIMARY KEY (`tiesveid`);

--
-- Indices de la tabla `tipoestantearchivador`
--
ALTER TABLE `tipoestantearchivador`
  ADD PRIMARY KEY (`tiesarid`);

--
-- Indices de la tabla `tipoidentificacion`
--
ALTER TABLE `tipoidentificacion`
  ADD PRIMARY KEY (`tipideid`),
  ADD UNIQUE KEY `uk_tipoidentificacion` (`tipidesigla`);

--
-- Indices de la tabla `tipomarcavehiculo`
--
ALTER TABLE `tipomarcavehiculo`
  ADD PRIMARY KEY (`timaveid`);

--
-- Indices de la tabla `tipomedio`
--
ALTER TABLE `tipomedio`
  ADD PRIMARY KEY (`tipmedid`);

--
-- Indices de la tabla `tipomodalidadvehiculo`
--
ALTER TABLE `tipomodalidadvehiculo`
  ADD PRIMARY KEY (`timoveid`);

--
-- Indices de la tabla `tipopersona`
--
ALTER TABLE `tipopersona`
  ADD PRIMARY KEY (`tipperid`);

--
-- Indices de la tabla `tipopersonadocumental`
--
ALTER TABLE `tipopersonadocumental`
  ADD PRIMARY KEY (`tipedoid`),
  ADD KEY `pk_tipedo` (`tipedoid`);

--
-- Indices de la tabla `tiporeferenciavehiculo`
--
ALTER TABLE `tiporeferenciavehiculo`
  ADD PRIMARY KEY (`tireveid`);

--
-- Indices de la tabla `tiposaludo`
--
ALTER TABLE `tiposaludo`
  ADD PRIMARY KEY (`tipsalid`);

--
-- Indices de la tabla `tiposancion`
--
ALTER TABLE `tiposancion`
  ADD PRIMARY KEY (`tipsanid`);

--
-- Indices de la tabla `tiposerviciovehiculo`
--
ALTER TABLE `tiposerviciovehiculo`
  ADD PRIMARY KEY (`tiseveid`);

--
-- Indices de la tabla `tipotramite`
--
ALTER TABLE `tipotramite`
  ADD PRIMARY KEY (`tiptraid`);

--
-- Indices de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  ADD PRIMARY KEY (`tipvehid`);

--
-- Indices de la tabla `tipovehiculodistribucion`
--
ALTER TABLE `tipovehiculodistribucion`
  ADD PRIMARY KEY (`tivediid`),
  ADD KEY `fk_tipvehtivedi` (`tipvehid`);

--
-- Indices de la tabla `tiquete`
--
ALTER TABLE `tiquete`
  ADD PRIMARY KEY (`tiquid`),
  ADD UNIQUE KEY `uk_tiquete` (`agenid`,`tiquanio`,`tiquconsecutivo`),
  ADD KEY `fk_usuatiqu` (`usuaid`),
  ADD KEY `fk_plaruttiqu` (`plarutid`),
  ADD KEY `fk_persertiqu` (`perserid`),
  ADD KEY `fk_depatiquorigen` (`depaidorigen`),
  ADD KEY `fk_munitiquorigen` (`muniidorigen`),
  ADD KEY `fk_depatiqudestino` (`depaiddestino`),
  ADD KEY `fk_munitiqudestino` (`muniiddestino`);

--
-- Indices de la tabla `tiquetepuesto`
--
ALTER TABLE `tiquetepuesto`
  ADD PRIMARY KEY (`tiqpueid`),
  ADD UNIQUE KEY `uk_tiquetepuesto` (`tiquid`,`tiqpuenumeropuesto`);

--
-- Indices de la tabla `tokenfirmapersona`
--
ALTER TABLE `tokenfirmapersona`
  ADD PRIMARY KEY (`tofipeid`),
  ADD UNIQUE KEY `uk_tokenfirma` (`tofipetoken`),
  ADD KEY `fk_perstofipe` (`persid`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuaid`),
  ADD UNIQUE KEY `uk_usuario` (`usuaemail`),
  ADD UNIQUE KEY `uk_usuario1` (`usuanick`),
  ADD KEY `fk_persusua` (`persid`),
  ADD KEY `fk_agenusua` (`agenid`),
  ADD KEY `fk_cajausua` (`cajaid`);

--
-- Indices de la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD PRIMARY KEY (`usurolid`),
  ADD KEY `fk_usuausurol` (`usurolusuaid`),
  ADD KEY `fk_rolusurol` (`usurolrolid`),
  ADD KEY `pk_usurol` (`usurolid`);

--
-- Indices de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD PRIMARY KEY (`vehiid`),
  ADD UNIQUE KEY `uk_vehiculoplaca` (`vehiplaca`),
  ADD KEY `fk_asocvehi` (`asocid`),
  ADD KEY `fk_tipvehvehi` (`tipvehid`),
  ADD KEY `fk_tirevevehi` (`tireveid`),
  ADD KEY `fk_timavevehi` (`timaveid`),
  ADD KEY `fk_ticovevehi` (`ticoveid`),
  ADD KEY `fk_timovevehi` (`timoveid`),
  ADD KEY `fk_ticavevehi` (`ticaveid`),
  ADD KEY `fk_ticovhvehi` (`ticovhid`),
  ADD KEY `fk_agenvehi` (`agenid`),
  ADD KEY `fk_tiesvevehi` (`tiesveid`);

--
-- Indices de la tabla `vehiculocambioestado`
--
ALTER TABLE `vehiculocambioestado`
  ADD PRIMARY KEY (`vecaesid`),
  ADD KEY `fk_vehivecaes` (`vehiid`),
  ADD KEY `fk_tiesvevecaes` (`tiesveid`),
  ADD KEY `fk_usuavecaes` (`vecaesusuaid`);

--
-- Indices de la tabla `vehiculocontrato`
--
ALTER TABLE `vehiculocontrato`
  ADD PRIMARY KEY (`vehconid`),
  ADD UNIQUE KEY `uk_vehiculocontrato` (`vehconanio`,`vehconnumero`),
  ADD KEY `fk_vehivehcon` (`vehiid`),
  ADD KEY `fk_asocvehcon` (`asocid`),
  ADD KEY `fk_persvehcon` (`persidgerente`);

--
-- Indices de la tabla `vehiculocrt`
--
ALTER TABLE `vehiculocrt`
  ADD PRIMARY KEY (`vehcrtid`),
  ADD KEY `fk_vehivehcrt` (`vehiid`);

--
-- Indices de la tabla `vehiculopoliza`
--
ALTER TABLE `vehiculopoliza`
  ADD PRIMARY KEY (`vehpolid`),
  ADD KEY `fk_vehivehpol` (`vehiid`);

--
-- Indices de la tabla `vehiculoresponsabilidad`
--
ALTER TABLE `vehiculoresponsabilidad`
  ADD PRIMARY KEY (`vehresid`),
  ADD KEY `fk_vehivehres` (`vehiid`),
  ADD KEY `fk_agenvehres` (`agenid`),
  ADD KEY `fk_usuavehres` (`usuaid`);

--
-- Indices de la tabla `vehiculosoat`
--
ALTER TABLE `vehiculosoat`
  ADD PRIMARY KEY (`vehsoaid`),
  ADD KEY `fk_vehivehsoa` (`vehiid`);

--
-- Indices de la tabla `vehiculosuspendido`
--
ALTER TABLE `vehiculosuspendido`
  ADD PRIMARY KEY (`vehsusid`),
  ADD KEY `fk_vehivehsus` (`vehiid`),
  ADD KEY `fk_usuavehsus` (`usuaid`);

--
-- Indices de la tabla `vehiculotarjetaoperacion`
--
ALTER TABLE `vehiculotarjetaoperacion`
  ADD PRIMARY KEY (`vetaopaid`),
  ADD KEY `fk_vehivetaop` (`vehiid`),
  ADD KEY `fk_tisevevetaop` (`tiseveid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agencia`
--
ALTER TABLE `agencia`
  MODIFY `agenid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla agencia', AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT de la tabla `archivohistorico`
--
ALTER TABLE `archivohistorico`
  MODIFY `archisid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla archivo histórico';

--
-- AUTO_INCREMENT de la tabla `archivohistoricodigitalizado`
--
ALTER TABLE `archivohistoricodigitalizado`
  MODIFY `arhidiid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante dependencia';

--
-- AUTO_INCREMENT de la tabla `asociado`
--
ALTER TABLE `asociado`
  MODIFY `asocid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla asociado';

--
-- AUTO_INCREMENT de la tabla `asociadocambioestado`
--
ALTER TABLE `asociadocambioestado`
  MODIFY `ascaesid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla asociado cambio estado';

--
-- AUTO_INCREMENT de la tabla `asociadosancion`
--
ALTER TABLE `asociadosancion`
  MODIFY `asosanid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla asociado sanción';

--
-- AUTO_INCREMENT de la tabla `cargolaboral`
--
ALTER TABLE `cargolaboral`
  MODIFY `carlabid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla cargo laboral', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `coddocumprocesoacta`
--
ALTER TABLE `coddocumprocesoacta`
  MODIFY `codopaid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso acta';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesoanexo`
--
ALTER TABLE `coddocumprocesoanexo`
  MODIFY `codopxid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso anexo';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocambioestado`
--
ALTER TABLE `coddocumprocesocambioestado`
  MODIFY `codpceid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso cambio estado';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocertificado`
--
ALTER TABLE `coddocumprocesocertificado`
  MODIFY `codopcid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso certificado';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocircular`
--
ALTER TABLE `coddocumprocesocircular`
  MODIFY `codoplid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso circular';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocitacion`
--
ALTER TABLE `coddocumprocesocitacion`
  MODIFY `codoptid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso citación';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocompartido`
--
ALTER TABLE `coddocumprocesocompartido`
  MODIFY `codopdid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso compartido';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesoconstancia`
--
ALTER TABLE `coddocumprocesoconstancia`
  MODIFY `codopnid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso constancia';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesocopia`
--
ALTER TABLE `coddocumprocesocopia`
  MODIFY `codoppid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso copia';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesofirma`
--
ALTER TABLE `coddocumprocesofirma`
  MODIFY `codopfid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso firma';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesooficio`
--
ALTER TABLE `coddocumprocesooficio`
  MODIFY `codopoid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso oficio';

--
-- AUTO_INCREMENT de la tabla `coddocumprocesoraddocentrante`
--
ALTER TABLE `coddocumprocesoraddocentrante`
  MODIFY `cdprdeid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso radicado documento entrante';

--
-- AUTO_INCREMENT de la tabla `codigodocumental`
--
ALTER TABLE `codigodocumental`
  MODIFY `coddocid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental';

--
-- AUTO_INCREMENT de la tabla `codigodocumentalproceso`
--
ALTER TABLE `codigodocumentalproceso`
  MODIFY `codoprid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla codigo documental proceso';

--
-- AUTO_INCREMENT de la tabla `colocacion`
--
ALTER TABLE `colocacion`
  MODIFY `coloid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla solicitud de credito desembolso';

--
-- AUTO_INCREMENT de la tabla `colocacioncambioestado`
--
ALTER TABLE `colocacioncambioestado`
  MODIFY `cocaesid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla colocación cambio estado';

--
-- AUTO_INCREMENT de la tabla `colocacionliquidacion`
--
ALTER TABLE `colocacionliquidacion`
  MODIFY `colliqid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla colocación liquidación';

--
-- AUTO_INCREMENT de la tabla `comprobantecontable`
--
ALTER TABLE `comprobantecontable`
  MODIFY `comconid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla comprobante contable';

--
-- AUTO_INCREMENT de la tabla `comprobantecontabledetalle`
--
ALTER TABLE `comprobantecontabledetalle`
  MODIFY `cocodeid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla movimiento caja detallado';

--
-- AUTO_INCREMENT de la tabla `conductor`
--
ALTER TABLE `conductor`
  MODIFY `condid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla conductor';

--
-- AUTO_INCREMENT de la tabla `conductorcambioestado`
--
ALTER TABLE `conductorcambioestado`
  MODIFY `cocaesid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla conductor cambio estado';

--
-- AUTO_INCREMENT de la tabla `conductorlicencia`
--
ALTER TABLE `conductorlicencia`
  MODIFY `conlicid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla conductor licencia';

--
-- AUTO_INCREMENT de la tabla `conductorvehiculo`
--
ALTER TABLE `conductorvehiculo`
  MODIFY `convehid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla conductor vehículo';

--
-- AUTO_INCREMENT de la tabla `configuracionencomienda`
--
ALTER TABLE `configuracionencomienda`
  MODIFY `conencid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla configuración encomienda', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `consignacionbancaria`
--
ALTER TABLE `consignacionbancaria`
  MODIFY `conbanid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla consignacion bancaria';

--
-- AUTO_INCREMENT de la tabla `contratoservicioespecial`
--
ALTER TABLE `contratoservicioespecial`
  MODIFY `coseesid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla contrato servicio especial';

--
-- AUTO_INCREMENT de la tabla `contratoservicioespecialcond`
--
ALTER TABLE `contratoservicioespecialcond`
  MODIFY `coseecod` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla contrato servicio especial vehículo';

--
-- AUTO_INCREMENT de la tabla `contratoservicioespecialvehi`
--
ALTER TABLE `contratoservicioespecialvehi`
  MODIFY `coseevid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla contrato servicio especial vehículo';

--
-- AUTO_INCREMENT de la tabla `cuentacontable`
--
ALTER TABLE `cuentacontable`
  MODIFY `cueconid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla cuenta contable', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  MODIFY `depeid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla dependencia', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `dependenciapersona`
--
ALTER TABLE `dependenciapersona`
  MODIFY `depperid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla dependencia persona', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `dependenciasubseriedocumental`
--
ALTER TABLE `dependenciasubseriedocumental`
  MODIFY `desusdid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla dependencia sub serie documental', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `emprid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla empresa', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `encomienda`
--
ALTER TABLE `encomienda`
  MODIFY `encoid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla encomienda';

--
-- AUTO_INCREMENT de la tabla `encomiendacambioestado`
--
ALTER TABLE `encomiendacambioestado`
  MODIFY `encaesid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla encomienda cambio estado';

--
-- AUTO_INCREMENT de la tabla `entidadfinanciera`
--
ALTER TABLE `entidadfinanciera`
  MODIFY `entfinid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla entidad finaciera';

--
-- AUTO_INCREMENT de la tabla `festivo`
--
ALTER TABLE `festivo`
  MODIFY `festid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla festivo';

--
-- AUTO_INCREMENT de la tabla `funcionalidad`
--
ALTER TABLE `funcionalidad`
  MODIFY `funcid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla funcionalidad', AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `historialcontrasena`
--
ALTER TABLE `historialcontrasena`
  MODIFY `hisconid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla historial de contrasena';

--
-- AUTO_INCREMENT de la tabla `informaciongeneralpdf`
--
ALTER TABLE `informaciongeneralpdf`
  MODIFY `ingpdfid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla información general PDF', AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `informacionnotificacioncorreo`
--
ALTER TABLE `informacionnotificacioncorreo`
  MODIFY `innocoid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla informacion notificación correo', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `ingresosistema`
--
ALTER TABLE `ingresosistema`
  MODIFY `ingsisid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla ingreso sistema', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `intentosfallidos`
--
ALTER TABLE `intentosfallidos`
  MODIFY `intfalid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla intentos fallidos';

--
-- AUTO_INCREMENT de la tabla `lineacredito`
--
ALTER TABLE `lineacredito`
  MODIFY `lincreid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla línea de crédito';

--
-- AUTO_INCREMENT de la tabla `mensajeimpresion`
--
ALTER TABLE `mensajeimpresion`
  MODIFY `menimpid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla mensaje impresión', AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `moduid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla módulo', AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `movimientocaja`
--
ALTER TABLE `movimientocaja`
  MODIFY `movcajid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla movimiento caja';

--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `muniid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla municipio', AUTO_INCREMENT=1134;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `persid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla persona', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `personacontratoservicioesp`
--
ALTER TABLE `personacontratoservicioesp`
  MODIFY `pecoseid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla persona contrato servicio especial';

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personaradicadocumento`
--
ALTER TABLE `personaradicadocumento`
  MODIFY `peradoid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla persona radica documento';

--
-- AUTO_INCREMENT de la tabla `personaservicio`
--
ALTER TABLE `personaservicio`
  MODIFY `perserid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla persona servicio';

--
-- AUTO_INCREMENT de la tabla `planillaruta`
--
ALTER TABLE `planillaruta`
  MODIFY `plarutid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla planilla ruta';

--
-- AUTO_INCREMENT de la tabla `procesoautomatico`
--
ALTER TABLE `procesoautomatico`
  MODIFY `proautid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla proceso automático', AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `radicaciondocentanexo`
--
ALTER TABLE `radicaciondocentanexo`
  MODIFY `radoeaid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante dependencia';

--
-- AUTO_INCREMENT de la tabla `radicaciondocentcambioestado`
--
ALTER TABLE `radicaciondocentcambioestado`
  MODIFY `radeceid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante cambio estado';

--
-- AUTO_INCREMENT de la tabla `radicaciondocentdependencia`
--
ALTER TABLE `radicaciondocentdependencia`
  MODIFY `radoedid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante dependencia';

--
-- AUTO_INCREMENT de la tabla `radicaciondocumentoentrante`
--
ALTER TABLE `radicaciondocumentoentrante`
  MODIFY `radoenid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante';

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `rolid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla rol', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `rolfuncionalidad`
--
ALTER TABLE `rolfuncionalidad`
  MODIFY `rolfunid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla rol funcionalidad', AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT de la tabla `ruta`
--
ALTER TABLE `ruta`
  MODIFY `rutaid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla ruta';

--
-- AUTO_INCREMENT de la tabla `rutanodo`
--
ALTER TABLE `rutanodo`
  MODIFY `rutnodid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla ruta nodo';

--
-- AUTO_INCREMENT de la tabla `seriedocumental`
--
ALTER TABLE `seriedocumental`
  MODIFY `serdocid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla serie documental', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `solicitudcredito`
--
ALTER TABLE `solicitudcredito`
  MODIFY `solcreid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla solicitud crédito';

--
-- AUTO_INCREMENT de la tabla `solicitudcreditocambioestado`
--
ALTER TABLE `solicitudcreditocambioestado`
  MODIFY `socrceid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla solicitud de credito cambio estado';

--
-- AUTO_INCREMENT de la tabla `subseriedocumental`
--
ALTER TABLE `subseriedocumental`
  MODIFY `susedoid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla sub serie documental', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tarifatiquete`
--
ALTER TABLE `tarifatiquete`
  MODIFY `tartiqid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tarifa tiquete';

--
-- AUTO_INCREMENT de la tabla `tipocarroceriavehiculo`
--
ALTER TABLE `tipocarroceriavehiculo`
  MODIFY `ticaveid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo carroceria vehículo', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipocolorvehiculo`
--
ALTER TABLE `tipocolorvehiculo`
  MODIFY `ticoveid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo color vehículo', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `tipodespedida`
--
ALTER TABLE `tipodespedida`
  MODIFY `tipdesid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo despedida', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `tipodocumental`
--
ALTER TABLE `tipodocumental`
  MODIFY `tipdocid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo documental', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipoestantearchivador`
--
ALTER TABLE `tipoestantearchivador`
  MODIFY `tiesarid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo estante archivador', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipomarcavehiculo`
--
ALTER TABLE `tipomarcavehiculo`
  MODIFY `timaveid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo marca vehículo', AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `tipopersonadocumental`
--
ALTER TABLE `tipopersonadocumental`
  MODIFY `tipedoid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo de persona documental', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tiporeferenciavehiculo`
--
ALTER TABLE `tiporeferenciavehiculo`
  MODIFY `tireveid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo referencia vehículo', AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `tiposaludo`
--
ALTER TABLE `tiposaludo`
  MODIFY `tipsalid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo de saludo', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `tiposancion`
--
ALTER TABLE `tiposancion`
  MODIFY `tipsanid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo sanción';

--
-- AUTO_INCREMENT de la tabla `tipovehiculo`
--
ALTER TABLE `tipovehiculo`
  MODIFY `tipvehid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo vehículo', AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `tipovehiculodistribucion`
--
ALTER TABLE `tipovehiculodistribucion`
  MODIFY `tivediid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tipo vehículo distribución';

--
-- AUTO_INCREMENT de la tabla `tiquete`
--
ALTER TABLE `tiquete`
  MODIFY `tiquid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tiquete';

--
-- AUTO_INCREMENT de la tabla `tiquetepuesto`
--
ALTER TABLE `tiquetepuesto`
  MODIFY `tiqpueid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla tiquete puesto';

--
-- AUTO_INCREMENT de la tabla `tokenfirmapersona`
--
ALTER TABLE `tokenfirmapersona`
  MODIFY `tofipeid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla token firma';

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuaid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla usuario', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  MODIFY `usurolid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla usuario rol', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  MODIFY `vehiid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo';

--
-- AUTO_INCREMENT de la tabla `vehiculocambioestado`
--
ALTER TABLE `vehiculocambioestado`
  MODIFY `vecaesid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo cambio estado';

--
-- AUTO_INCREMENT de la tabla `vehiculocontrato`
--
ALTER TABLE `vehiculocontrato`
  MODIFY `vehconid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo contrato';

--
-- AUTO_INCREMENT de la tabla `vehiculocrt`
--
ALTER TABLE `vehiculocrt`
  MODIFY `vehcrtid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo CRT';

--
-- AUTO_INCREMENT de la tabla `vehiculopoliza`
--
ALTER TABLE `vehiculopoliza`
  MODIFY `vehpolid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo póliza';

--
-- AUTO_INCREMENT de la tabla `vehiculoresponsabilidad`
--
ALTER TABLE `vehiculoresponsabilidad`
  MODIFY `vehresid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo responsabilidad';

--
-- AUTO_INCREMENT de la tabla `vehiculosoat`
--
ALTER TABLE `vehiculosoat`
  MODIFY `vehsoaid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo SOAT';

--
-- AUTO_INCREMENT de la tabla `vehiculosuspendido`
--
ALTER TABLE `vehiculosuspendido`
  MODIFY `vehsusid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo responsabilidad';

--
-- AUTO_INCREMENT de la tabla `vehiculotarjetaoperacion`
--
ALTER TABLE `vehiculotarjetaoperacion`
  MODIFY `vetaopaid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla vehículo tarjeta operación';

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agencia`
--
ALTER TABLE `agencia`
  ADD CONSTRAINT `fk_depaagen` FOREIGN KEY (`agendepaid`) REFERENCES `departamento` (`depaid`),
  ADD CONSTRAINT `fk_muniagen` FOREIGN KEY (`agenmuniid`) REFERENCES `municipio` (`muniid`),
  ADD CONSTRAINT `fk_persagen` FOREIGN KEY (`persidresponsable`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `archivohistorico`
--
ALTER TABLE `archivohistorico`
  ADD CONSTRAINT `fk_ticauarchis` FOREIGN KEY (`ticaubid`) REFERENCES `tipocajaubicacion` (`ticaubid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticrubarchis` FOREIGN KEY (`ticrubid`) REFERENCES `tipocarpetaubicacion` (`ticrubid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesararchis` FOREIGN KEY (`tiesarid`) REFERENCES `tipoestantearchivador` (`tiesarid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdocarchis` FOREIGN KEY (`tipdocid`) REFERENCES `tipodocumental` (`tipdocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaarchis` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `archivohistoricodigitalizado`
--
ALTER TABLE `archivohistoricodigitalizado`
  ADD CONSTRAINT `fk_archisarhidi` FOREIGN KEY (`archisid`) REFERENCES `archivohistorico` (`archisid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `asociado`
--
ALTER TABLE `asociado`
  ADD CONSTRAINT `fk_persasoc` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesasasoc` FOREIGN KEY (`tiesasid`) REFERENCES `tipoestadoasociado` (`tiesasid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `asociadocambioestado`
--
ALTER TABLE `asociadocambioestado`
  ADD CONSTRAINT `fk_asocascaes` FOREIGN KEY (`asocid`) REFERENCES `asociado` (`asocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesasascaes` FOREIGN KEY (`tiesasid`) REFERENCES `tipoestadoasociado` (`tiesasid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaascaes` FOREIGN KEY (`ascaesusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `asociadosancion`
--
ALTER TABLE `asociadosancion`
  ADD CONSTRAINT `fk_asocasosan` FOREIGN KEY (`asocid`) REFERENCES `asociado` (`asocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipsanasosan` FOREIGN KEY (`tipsanid`) REFERENCES `tiposancion` (`tipsanid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaasosan` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesoacta`
--
ALTER TABLE `coddocumprocesoacta`
  ADD CONSTRAINT `fk_codoprcodopa` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipactcodopa` FOREIGN KEY (`tipactid`) REFERENCES `tipoacta` (`tipactid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopa` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesoanexo`
--
ALTER TABLE `coddocumprocesoanexo`
  ADD CONSTRAINT `fk_codoprcodopx` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocambioestado`
--
ALTER TABLE `coddocumprocesocambioestado`
  ADD CONSTRAINT `fk_codoprcodpce` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesdocodpce` FOREIGN KEY (`tiesdoid`) REFERENCES `tipoestadodocumento` (`tiesdoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodpce` FOREIGN KEY (`codpceusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocertificado`
--
ALTER TABLE `coddocumprocesocertificado`
  ADD CONSTRAINT `fk_codoprcodopc` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tippedocodopc` FOREIGN KEY (`tipedoid`) REFERENCES `tipopersonadocumental` (`tipedoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopc` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocircular`
--
ALTER TABLE `coddocumprocesocircular`
  ADD CONSTRAINT `fk_codoprcodopl` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdescodopl` FOREIGN KEY (`tipdesid`) REFERENCES `tipodespedida` (`tipdesid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopl` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocitacion`
--
ALTER TABLE `coddocumprocesocitacion`
  ADD CONSTRAINT `fk_codoprcodopt` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipactcodopt` FOREIGN KEY (`tipactid`) REFERENCES `tipoacta` (`tipactid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopt` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocompartido`
--
ALTER TABLE `coddocumprocesocompartido`
  ADD CONSTRAINT `fk_codoprcodopd` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopd` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesoconstancia`
--
ALTER TABLE `coddocumprocesoconstancia`
  ADD CONSTRAINT `fk_codoprcodopn` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tippedocodopn` FOREIGN KEY (`tipedoid`) REFERENCES `tipopersonadocumental` (`tipedoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopn` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesocopia`
--
ALTER TABLE `coddocumprocesocopia`
  ADD CONSTRAINT `fk_codoprcodopp` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depecodopp` FOREIGN KEY (`depeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesofirma`
--
ALTER TABLE `coddocumprocesofirma`
  ADD CONSTRAINT `fk_carlabcodopf` FOREIGN KEY (`carlabid`) REFERENCES `cargolaboral` (`carlabid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_codoprcodopf` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_perscodopf` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesooficio`
--
ALTER TABLE `coddocumprocesooficio`
  ADD CONSTRAINT `fk_codoprcodopo` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdescodopo` FOREIGN KEY (`tipdesid`) REFERENCES `tipodespedida` (`tipdesid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipsalcodopo` FOREIGN KEY (`tipsalid`) REFERENCES `tiposaludo` (`tipsalid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacodopo` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `coddocumprocesoraddocentrante`
--
ALTER TABLE `coddocumprocesoraddocentrante`
  ADD CONSTRAINT `fk_codoprcdprde` FOREIGN KEY (`codoprid`) REFERENCES `codigodocumentalproceso` (`codoprid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_radoencdprde` FOREIGN KEY (`radoenid`) REFERENCES `radicaciondocumentoentrante` (`radoenid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `codigodocumental`
--
ALTER TABLE `codigodocumental`
  ADD CONSTRAINT `fk_coddocuser` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depecoddoc` FOREIGN KEY (`depeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_serdoccoddoc` FOREIGN KEY (`serdocid`) REFERENCES `seriedocumental` (`serdocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_susedocoddoc` FOREIGN KEY (`susedoid`) REFERENCES `subseriedocumental` (`susedoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdepcoddoc` FOREIGN KEY (`tipdetid`) REFERENCES `tipodestino` (`tipdetid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdoccoddoc` FOREIGN KEY (`tipdocid`) REFERENCES `tipodocumental` (`tipdocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipmedcoddoc` FOREIGN KEY (`tipmedid`) REFERENCES `tipomedio` (`tipmedid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiptracoddoc` FOREIGN KEY (`tiptraid`) REFERENCES `tipotramite` (`tiptraid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `codigodocumentalproceso`
--
ALTER TABLE `codigodocumentalproceso`
  ADD CONSTRAINT `fk_coddoccodopr` FOREIGN KEY (`coddocid`) REFERENCES `codigodocumental` (`coddocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesdocodopr` FOREIGN KEY (`tiesdoid`) REFERENCES `tipoestadodocumento` (`tiesdoid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `colocacion`
--
ALTER TABLE `colocacion`
  ADD CONSTRAINT `fk_solcrecolo` FOREIGN KEY (`solcreid`) REFERENCES `solicitudcredito` (`solcreid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesclcolo` FOREIGN KEY (`tiesclid`) REFERENCES `tipoestadocolocacion` (`tiesclid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacolo` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `colocacioncambioestado`
--
ALTER TABLE `colocacioncambioestado`
  ADD CONSTRAINT `fk_colococaes` FOREIGN KEY (`coloid`) REFERENCES `colocacion` (`coloid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesclcocaes` FOREIGN KEY (`tiesclid`) REFERENCES `tipoestadocolocacion` (`tiesclid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaclcaes` FOREIGN KEY (`cocaesusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `colocacionliquidacion`
--
ALTER TABLE `colocacionliquidacion`
  ADD CONSTRAINT `fk_colocolliq` FOREIGN KEY (`coloid`) REFERENCES `colocacion` (`coloid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `comprobantecontable`
--
ALTER TABLE `comprobantecontable`
  ADD CONSTRAINT `fk_agencomcon` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cajacomcon` FOREIGN KEY (`cajaid`) REFERENCES `caja` (`cajaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_movcajcomcon` FOREIGN KEY (`movcajid`) REFERENCES `movimientocaja` (`movcajid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacomcon` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `comprobantecontabledetalle`
--
ALTER TABLE `comprobantecontabledetalle`
  ADD CONSTRAINT `fk_comconcocode` FOREIGN KEY (`comconid`) REFERENCES `comprobantecontable` (`comconid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cueconcocode` FOREIGN KEY (`cueconid`) REFERENCES `cuentacontable` (`cueconid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conductor`
--
ALTER TABLE `conductor`
  ADD CONSTRAINT `fk_agencond` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_perscond` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiescocond` FOREIGN KEY (`tiescoid`) REFERENCES `tipoestadoconductor` (`tiescoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipconcond` FOREIGN KEY (`tipconid`) REFERENCES `tipoconductor` (`tipconid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conductorcambioestado`
--
ALTER TABLE `conductorcambioestado`
  ADD CONSTRAINT `fk_condcocaes` FOREIGN KEY (`condid`) REFERENCES `conductor` (`condid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesascocaes` FOREIGN KEY (`tiescoid`) REFERENCES `tipoestadoconductor` (`tiescoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuacocaes` FOREIGN KEY (`cocaesusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conductorlicencia`
--
ALTER TABLE `conductorlicencia`
  ADD CONSTRAINT `fk_condconlic` FOREIGN KEY (`condid`) REFERENCES `conductor` (`condid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticaliconlic` FOREIGN KEY (`ticaliid`) REFERENCES `tipocategorialicencia` (`ticaliid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `conductorvehiculo`
--
ALTER TABLE `conductorvehiculo`
  ADD CONSTRAINT `fk_condconveh` FOREIGN KEY (`condid`) REFERENCES `conductor` (`condid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehiconveh` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `consignacionbancaria`
--
ALTER TABLE `consignacionbancaria`
  ADD CONSTRAINT `fk_agenconban` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_entfinconban` FOREIGN KEY (`entfinid`) REFERENCES `entidadfinanciera` (`entfinid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaconban` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contratoservicioespecial`
--
ALTER TABLE `contratoservicioespecial`
  ADD CONSTRAINT `fk_pecosecosees` FOREIGN KEY (`pecoseid`) REFERENCES `personacontratoservicioesp` (`pecoseid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_perscosees` FOREIGN KEY (`persidgerente`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticosecosees` FOREIGN KEY (`ticoseid`) REFERENCES `tipocontratoservicioespecial` (`ticoseid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticosscosees` FOREIGN KEY (`ticossid`) REFERENCES `tipoconvenioservicioespecial` (`ticossid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contratoservicioespecialcond`
--
ALTER TABLE `contratoservicioespecialcond`
  ADD CONSTRAINT `fk_condcoseeco` FOREIGN KEY (`condid`) REFERENCES `conductor` (`condid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_coseescoseeco` FOREIGN KEY (`coseesid`) REFERENCES `contratoservicioespecial` (`coseesid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contratoservicioespecialvehi`
--
ALTER TABLE `contratoservicioespecialvehi`
  ADD CONSTRAINT `fk_coseescoseev` FOREIGN KEY (`coseesid`) REFERENCES `contratoservicioespecial` (`coseesid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehicoseev` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `dependencia`
--
ALTER TABLE `dependencia`
  ADD CONSTRAINT `fk_persdepe` FOREIGN KEY (`depejefeid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `dependenciapersona`
--
ALTER TABLE `dependenciapersona`
  ADD CONSTRAINT `fk_depedepper` FOREIGN KEY (`depperdepeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_persdepper` FOREIGN KEY (`depperpersid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `dependenciasubseriedocumental`
--
ALTER TABLE `dependenciasubseriedocumental`
  ADD CONSTRAINT `fk_depedesusd` FOREIGN KEY (`desusddepeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_susedodesusd` FOREIGN KEY (`desusdsusedoid`) REFERENCES `subseriedocumental` (`susedoid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `fk_depaempr` FOREIGN KEY (`emprdepaid`) REFERENCES `departamento` (`depaid`),
  ADD CONSTRAINT `fk_muniempr` FOREIGN KEY (`emprmuniid`) REFERENCES `municipio` (`muniid`),
  ADD CONSTRAINT `fk_persempr` FOREIGN KEY (`persidrepresentantelegal`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `encomienda`
--
ALTER TABLE `encomienda`
  ADD CONSTRAINT `fk_agenenco` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depaencodestino` FOREIGN KEY (`depaiddestino`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depaencoorigen` FOREIGN KEY (`depaidorigen`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_muniencodestino` FOREIGN KEY (`muniiddestino`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_muniencoorigen` FOREIGN KEY (`muniidorigen`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_perserencodestino` FOREIGN KEY (`perseriddestino`) REFERENCES `personaservicio` (`perserid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_perserencoremitente` FOREIGN KEY (`perseridremitente`) REFERENCES `personaservicio` (`perserid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_plarutenco` FOREIGN KEY (`plarutid`) REFERENCES `planillaruta` (`plarutid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesenenco` FOREIGN KEY (`tiesenid`) REFERENCES `tipoestadoencomienda` (`tiesenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipencenco` FOREIGN KEY (`tipencid`) REFERENCES `tipoencomienda` (`tipencid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaenco` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `encomiendacambioestado`
--
ALTER TABLE `encomiendacambioestado`
  ADD CONSTRAINT `fk_encoencaes` FOREIGN KEY (`encoid`) REFERENCES `encomienda` (`encoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesenencaes` FOREIGN KEY (`tiesenid`) REFERENCES `tipoestadoencomienda` (`tiesenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaencaes` FOREIGN KEY (`encaesusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `funcionalidad`
--
ALTER TABLE `funcionalidad`
  ADD CONSTRAINT `fk_modufunc` FOREIGN KEY (`moduid`) REFERENCES `modulo` (`moduid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `historialcontrasena`
--
ALTER TABLE `historialcontrasena`
  ADD CONSTRAINT `fk_usuahiscon` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ingresosistema`
--
ALTER TABLE `ingresosistema`
  ADD CONSTRAINT `fk_usuaingsis` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientocaja`
--
ALTER TABLE `movimientocaja`
  ADD CONSTRAINT `fk_cajamovcaj` FOREIGN KEY (`cajaid`) REFERENCES `caja` (`cajaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuamovcaj` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD CONSTRAINT `fk_depamuni` FOREIGN KEY (`munidepaid`) REFERENCES `departamento` (`depaid`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `fk_carglabpers` FOREIGN KEY (`carlabid`) REFERENCES `cargolaboral` (`carlabid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depapersexp` FOREIGN KEY (`persdepaidexpedicion`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depapersnac` FOREIGN KEY (`persdepaidnacimiento`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_munipersexp` FOREIGN KEY (`persmuniidexpedicion`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_munipersnac` FOREIGN KEY (`persmuniidnacimiento`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipidepers` FOREIGN KEY (`tipideid`) REFERENCES `tipoidentificacion` (`tipideid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipperpers` FOREIGN KEY (`tipperid`) REFERENCES `tipopersona` (`tipperid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `personacontratoservicioesp`
--
ALTER TABLE `personacontratoservicioesp`
  ADD CONSTRAINT `fk_tipidepecose` FOREIGN KEY (`tipideid`) REFERENCES `tipoidentificacion` (`tipideid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `personaradicadocumento`
--
ALTER TABLE `personaradicadocumento`
  ADD CONSTRAINT `fk_tipideperado` FOREIGN KEY (`tipideid`) REFERENCES `tipoidentificacion` (`tipideid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `personaservicio`
--
ALTER TABLE `personaservicio`
  ADD CONSTRAINT `fk_tipideperser` FOREIGN KEY (`tipideid`) REFERENCES `tipoidentificacion` (`tipideid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `planillaruta`
--
ALTER TABLE `planillaruta`
  ADD CONSTRAINT `fk_agenplarut` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_condplarut` FOREIGN KEY (`condid`) REFERENCES `conductor` (`condid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rutaplarut` FOREIGN KEY (`rutaid`) REFERENCES `ruta` (`rutaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaplarutdespacha` FOREIGN KEY (`usuaiddespacha`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaplarutregistra` FOREIGN KEY (`usuaidregistra`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehiplarut` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `radicaciondocentanexo`
--
ALTER TABLE `radicaciondocentanexo`
  ADD CONSTRAINT `fk_radoenradoea` FOREIGN KEY (`radoenid`) REFERENCES `radicaciondocumentoentrante` (`radoenid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `radicaciondocentcambioestado`
--
ALTER TABLE `radicaciondocentcambioestado`
  ADD CONSTRAINT `fk_radoenradece` FOREIGN KEY (`radoenid`) REFERENCES `radicaciondocumentoentrante` (`radoenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tierderadece` FOREIGN KEY (`tierdeid`) REFERENCES `tipoestadoraddocentrante` (`tierdeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaradece` FOREIGN KEY (`radeceusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `radicaciondocentdependencia`
--
ALTER TABLE `radicaciondocentdependencia`
  ADD CONSTRAINT `fk_deperadoed` FOREIGN KEY (`depeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_radoenradoed` FOREIGN KEY (`radoenid`) REFERENCES `radicaciondocumentoentrante` (`radoenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuaradoed` FOREIGN KEY (`radoedsuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `radicaciondocumentoentrante`
--
ALTER TABLE `radicaciondocumentoentrante`
  ADD CONSTRAINT `fk_deparadoen` FOREIGN KEY (`depaid`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_muniradoen` FOREIGN KEY (`muniid`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_peradoradoen` FOREIGN KEY (`peradoid`) REFERENCES `personaradicadocumento` (`peradoid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_radoen` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tierderadoen` FOREIGN KEY (`tierdeid`) REFERENCES `tipoestadoraddocentrante` (`tierdeid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipmedradoen` FOREIGN KEY (`tipmedid`) REFERENCES `tipomedio` (`tipmedid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `rolfuncionalidad`
--
ALTER TABLE `rolfuncionalidad`
  ADD CONSTRAINT `fk_funcrolfun` FOREIGN KEY (`rolfunfuncid`) REFERENCES `funcionalidad` (`funcid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rolrolfun` FOREIGN KEY (`rolfunrolid`) REFERENCES `rol` (`rolid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ruta`
--
ALTER TABLE `ruta`
  ADD CONSTRAINT `fk_deparutadestino` FOREIGN KEY (`depaiddestino`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deparutaorigen` FOREIGN KEY (`depaidorigen`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_munirutadestino` FOREIGN KEY (`muniiddestino`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_munirutaorigen` FOREIGN KEY (`muniidorigen`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutanodo`
--
ALTER TABLE `rutanodo`
  ADD CONSTRAINT `fk_munirutnod` FOREIGN KEY (`muniid`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rutarutnod` FOREIGN KEY (`rutaid`) REFERENCES `ruta` (`rutaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitudcredito`
--
ALTER TABLE `solicitudcredito`
  ADD CONSTRAINT `fk_asocsolcre` FOREIGN KEY (`asocid`) REFERENCES `asociado` (`asocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_lincresolcre` FOREIGN KEY (`lincreid`) REFERENCES `lineacredito` (`lincreid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesscsolcre` FOREIGN KEY (`tiesscid`) REFERENCES `tipoestadosolicitudcredito` (`tiesscid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuasolcre` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehisolcre` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `solicitudcreditocambioestado`
--
ALTER TABLE `solicitudcreditocambioestado`
  ADD CONSTRAINT `fk_solcresocrce` FOREIGN KEY (`solcreid`) REFERENCES `solicitudcredito` (`solcreid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesscsocrce` FOREIGN KEY (`tiesscid`) REFERENCES `tipoestadosolicitudcredito` (`tiesscid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuasocrce` FOREIGN KEY (`socrceusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `subseriedocumental`
--
ALTER TABLE `subseriedocumental`
  ADD CONSTRAINT `fk_serdocsusedo` FOREIGN KEY (`serdocid`) REFERENCES `seriedocumental` (`serdocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdocsusedo` FOREIGN KEY (`tipdocid`) REFERENCES `tipodocumental` (`tipdocid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tarifatiquete`
--
ALTER TABLE `tarifatiquete`
  ADD CONSTRAINT `fk_depatartiqdestino` FOREIGN KEY (`depaiddestino`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_munitartiqdestino` FOREIGN KEY (`muniiddestino`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rutatartiq` FOREIGN KEY (`rutaid`) REFERENCES `ruta` (`rutaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tipovehiculodistribucion`
--
ALTER TABLE `tipovehiculodistribucion`
  ADD CONSTRAINT `fk_tipvehtivedi` FOREIGN KEY (`tipvehid`) REFERENCES `tipovehiculo` (`tipvehid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tiquete`
--
ALTER TABLE `tiquete`
  ADD CONSTRAINT `fk_agentiqu` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depatiqudestino` FOREIGN KEY (`depaiddestino`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_depatiquorigen` FOREIGN KEY (`depaidorigen`) REFERENCES `departamento` (`depaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_munitiqudestino` FOREIGN KEY (`muniiddestino`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_munitiquorigen` FOREIGN KEY (`muniidorigen`) REFERENCES `municipio` (`muniid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_persertiqu` FOREIGN KEY (`perserid`) REFERENCES `personaservicio` (`perserid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_plaruttiqu` FOREIGN KEY (`plarutid`) REFERENCES `planillaruta` (`plarutid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuatiqu` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tiquetepuesto`
--
ALTER TABLE `tiquetepuesto`
  ADD CONSTRAINT `fk_tiqutiqpue` FOREIGN KEY (`tiquid`) REFERENCES `tiquete` (`tiquid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tokenfirmapersona`
--
ALTER TABLE `tokenfirmapersona`
  ADD CONSTRAINT `fk_perstofipe` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_agenusua` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cajausua` FOREIGN KEY (`cajaid`) REFERENCES `caja` (`cajaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_persusua` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD CONSTRAINT `fk_rolusurol` FOREIGN KEY (`usurolrolid`) REFERENCES `rol` (`rolid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuausurol` FOREIGN KEY (`usurolusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculo`
--
ALTER TABLE `vehiculo`
  ADD CONSTRAINT `fk_agenvehi` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asocvehi` FOREIGN KEY (`asocid`) REFERENCES `asociado` (`asocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticavevehi` FOREIGN KEY (`ticaveid`) REFERENCES `tipocarroceriavehiculo` (`ticaveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticovevehi` FOREIGN KEY (`ticoveid`) REFERENCES `tipocolorvehiculo` (`ticoveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ticovhvehi` FOREIGN KEY (`ticovhid`) REFERENCES `tipocombustiblevehiculo` (`ticovhid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tiesvevehi` FOREIGN KEY (`tiesveid`) REFERENCES `tipoestadovehiculo` (`tiesveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_timavevehi` FOREIGN KEY (`timaveid`) REFERENCES `tipomarcavehiculo` (`timaveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_timovevehi` FOREIGN KEY (`timoveid`) REFERENCES `tipomodalidadvehiculo` (`timoveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipvehvehi` FOREIGN KEY (`tipvehid`) REFERENCES `tipovehiculo` (`tipvehid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tirevevehi` FOREIGN KEY (`tireveid`) REFERENCES `tiporeferenciavehiculo` (`tireveid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculocambioestado`
--
ALTER TABLE `vehiculocambioestado`
  ADD CONSTRAINT `fk_tiesvevecaes` FOREIGN KEY (`tiesveid`) REFERENCES `tipoestadovehiculo` (`tiesveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuavecaes` FOREIGN KEY (`vecaesusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehivecaes` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculocontrato`
--
ALTER TABLE `vehiculocontrato`
  ADD CONSTRAINT `fk_asocvehcon` FOREIGN KEY (`asocid`) REFERENCES `asociado` (`asocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_persvehcon` FOREIGN KEY (`persidgerente`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehivehcon` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculocrt`
--
ALTER TABLE `vehiculocrt`
  ADD CONSTRAINT `fk_vehivehcrt` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculopoliza`
--
ALTER TABLE `vehiculopoliza`
  ADD CONSTRAINT `fk_vehivehpol` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculoresponsabilidad`
--
ALTER TABLE `vehiculoresponsabilidad`
  ADD CONSTRAINT `fk_agenvehres` FOREIGN KEY (`agenid`) REFERENCES `agencia` (`agenid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuavehres` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehivehres` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculosoat`
--
ALTER TABLE `vehiculosoat`
  ADD CONSTRAINT `fk_vehivehsoa` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculosuspendido`
--
ALTER TABLE `vehiculosuspendido`
  ADD CONSTRAINT `fk_usuavehsus` FOREIGN KEY (`usuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehivehsus` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculotarjetaoperacion`
--
ALTER TABLE `vehiculotarjetaoperacion`
  ADD CONSTRAINT `fk_tisevevetaop` FOREIGN KEY (`tiseveid`) REFERENCES `tiposerviciovehiculo` (`tiseveid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehivetaop` FOREIGN KEY (`vehiid`) REFERENCES `vehiculo` (`vehiid`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2023 a las 04:02:41
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

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
  `archisnumerofolio` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de folio que posee el documento del archivo histórico',
  `archisasuntodocumento` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Asunto que posee el documento del archivo histórico',
  `archistomodocumento` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tomo que posee el documento del archivo histórico',
  `archiscodigodocumental` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código que posee el documento del archivo histórico',
  `archisentidadremitente` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Entidad remitente que posee el documento del archivo histórico',
  `archisentidadproductora` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Entidad productora que posee el documento del archivo histórico',
  `archisresumendocumento` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'REsumen que posee el documento del archivo histórico',
  `archisobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación general del registro del archivo histórico',
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
  `arhidinombrearchivooriginal` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el archivo digitalizado',
  `arhidinombrearchivoeditado` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el archivo digitalizado pero editado',
  `arhidirutaarchivo` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta enfuscada del archivo digitalizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargolaboral`
--

CREATE TABLE `cargolaboral` (
  `carlabid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla cargo laboral',
  `carlabnombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del cargo laboral',
  `carlabactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el cargo laboral',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargolaboral`
--

INSERT INTO `cargolaboral` (`carlabid`, `carlabnombre`, `carlabactivo`, `created_at`, `updated_at`) VALUES
(1, 'Jefe', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(2, 'Jefe encargado', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(3, 'Secretaria', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coddocumprocesoacta`
--

CREATE TABLE `coddocumprocesoacta` (
  `codopaid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso acta',
  `codoprid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla codigo documental proceso',
  `tipactid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de acta',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el documento',
  `codopaconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo del acta',
  `codopasigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora del acta',
  `codopaanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea el acta',
  `codopahorainicio` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hora de inicio del acta',
  `codopahorafinal` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hora de final del acta',
  `codopalugar` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lugar donde se realiza el acta',
  `codopaquorum` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Quorum reglamentario para el acta',
  `codopaordendeldia` varchar(4000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Orden del dñia del acta',
  `codopainvitado` varchar(4000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Personas invitados para el acta',
  `codopaausente` varchar(4000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Persona usente en la generación para el acta',
  `codopaconvocatoria` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el acta tiene conovocatoria',
  `codopaconvocatorialugar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lugar conovocatoria para el acta',
  `codopaconvocatoriafecha` date DEFAULT NULL COMMENT 'Fecha para la conovocatoria del acta',
  `codopaconvocatoriahora` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Hora de la conovocatoria del acta',
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
  `codopxnombreanexooriginal` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el documento',
  `codopxnombreanexoeditado` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el documento pero editado',
  `codopxrutaanexo` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta enfuscada del anexo para el documento',
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
  `codpceobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación del cambio estado documento',
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
  `codopcconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo de la certificado',
  `codopcsigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora de la certificado',
  `codopcanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea la certificado',
  `codopctitulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título con el que se crea la certificado',
  `codopccontenidoinicial` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'contenido incial de la certificado',
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
  `codoplconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo de la circular',
  `codoplsigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora de la circular',
  `codoplanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea la circular',
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
  `codoptconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo del citación',
  `codoptsigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora del citación',
  `codoptanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea el citación',
  `codopthora` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hora de la citación',
  `codoptlugar` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lugar donde se realiza el citación',
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
  `codopnconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo de la constancia',
  `codopnsigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora de la constancia',
  `codopnanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea la constancia',
  `codopntitulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Título con el que se crea la constancia',
  `codopncontenidoinicial` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'contenido incial de la constancia',
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
  `codopftoken` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Token con el cual es firmado el documento',
  `codopffechahorafirmado` datetime DEFAULT NULL COMMENT 'Fecha y hora de la cual se firma el documento',
  `codopffechahoranotificacion` datetime DEFAULT NULL COMMENT 'Fecha y hora de la cual se envio la notifiación del token',
  `codopffechahoramaxvalidez` datetime DEFAULT NULL COMMENT 'Fecha y hora maxima de validez del token',
  `codopfmensajecorreo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contendio de la información enviada al correo',
  `codopfmensajecelular` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contendio de la información enviada al celular',
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
  `codopoconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo de la oficio',
  `codoposigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia productora de la oficio',
  `codopoanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea la oficio',
  `codopotitulo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Título de la persona a la que va dirigido el ofico',
  `codopociudad` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ciudad a la que va dirigido el oficio',
  `codopocargodestinatario` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del cargo de la persona ala que va dirigido el oficio',
  `codopoempresa` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona o empresa a la que va dirigido el oficio',
  `codopodireccion` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'direción de la persona o empresa a la que va dirigido el oficio',
  `codopotelefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Telefono de la persona o empresa a la que va dirigido el oficio',
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
  `codoprnombredirigido` varchar(4000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre o nombres de la persona a quien va dirigido el documento',
  `codoprcargonombredirigido` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Cargo de la persona a quien va dirigido el documento',
  `codoprasunto` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Asunto por el cual se crea el documento o título de la resolución',
  `codoprcorreo` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Correo de la persona o personas a quien van dirigir el documento',
  `codoprcontenido` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contenido del documento',
  `codoprtieneanexo` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento tiene anexo',
  `codopranexonombre` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre del adjunto que se relaciona en el documento',
  `codoprtienecopia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento tiene copia',
  `codoprcopianombre` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona a quien va dirigido el documento como copia',
  `codoprrutadocumento` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la ruta al sellar el documento',
  `codoprsolicitafirma` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento se le ha solicitado la firma',
  `codoprfirmado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento ha sido firmado',
  `codoprsellado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento esta sellado',
  `codoprradicado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el documento fue radicado en ventanilla única',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamento`
--

CREATE TABLE `departamento` (
  `depaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla departamento',
  `depacodigo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Codigo del departamento',
  `depanombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del departamento',
  `depahacepresencia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la entidad hace presencia en este departamento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `departamento`
--

INSERT INTO `departamento` (`depaid`, `depacodigo`, `depanombre`, `depahacepresencia`, `created_at`, `updated_at`) VALUES
(1, '05', 'Antioquia', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(2, '08', 'Atlántico', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(3, '11', 'Bogotá', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(4, '13', 'Bolivar', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(5, '15', 'Boyaca', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(6, '17', 'Caldas', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(7, '18', 'Caquetá', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(8, '19', 'Cauca', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(9, '20', 'Cesar', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(10, '23', 'Cordoba', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(11, '25', 'Cundinamarca', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(12, '27', 'Chocó', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(13, '41', 'Huila', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(14, '44', 'La Guajira', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(15, '47', 'Magdalena', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(16, '50', 'Meta', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(17, '52', 'Nariño', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(18, '54', 'Norte de Santander', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(19, '63', 'Quindio', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(20, '66', 'Risaralda', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(21, '68', 'Santander', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(22, '70', 'Sucre', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(23, '73', 'Tolima', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(24, '76', 'Valle del Cauca', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(25, '81', 'Arauca', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(26, '85', 'Casanare', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(27, '86', 'Putumayo', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(28, '88', 'San Andrés', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(29, '91', 'Amazonas', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(30, '94', 'Guainia', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(31, '95', 'Guaviare', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(32, '97', 'Vaupes', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(33, '99', 'Vichada', 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependencia`
--

CREATE TABLE `dependencia` (
  `depeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla cargo dependencia',
  `depejefeid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del jefe de la dependencia',
  `depecodigo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código de la dependencia',
  `depesigla` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla de la dependencia',
  `depenombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'nombre de la dependencia',
  `depecorreo` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Correo de la dependencia',
  `depeactiva` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la dependencia se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependenciapersona`
--

CREATE TABLE `dependenciapersona` (
  `depperid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia persona',
  `depperdepeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la dependencia',
  `depperpersid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del persona asignado a la dependencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dependenciasubseriedocumental`
--

CREATE TABLE `dependenciasubseriedocumental` (
  `desusdid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia sub serie documental',
  `desusdsusedoid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla sub serie',
  `desusddepeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla dependencia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `emprid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla empresa',
  `persidrepresentantelegal` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona',
  `emprdepaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento',
  `emprmuniid` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio',
  `emprnit` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nit de la empresa',
  `emprdigitoverificacion` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Dígito de verificación de la empresa',
  `emprnombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la empresa',
  `emprsigla` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Sigla de la empresa',
  `emprlema` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Lema de la empresa',
  `emprdireccion` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Dirección de la empresa',
  `emprcorreo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Correo de la empresa',
  `emprtelefonofijo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono fijo de contacto con la empresa',
  `emprtelefonocelular` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Teléfono celular de contacto con la empresa',
  `emprhorarioatencion` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Horario de atención',
  `emprurl` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Url de la página web institucional',
  `emprcodigopostal` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código postal',
  `emprlogo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Logo de la empresa en en formato png',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`emprid`, `persidrepresentantelegal`, `emprdepaid`, `emprmuniid`, `emprnit`, `emprdigitoverificacion`, `emprnombre`, `emprsigla`, `emprlema`, `emprdireccion`, `emprcorreo`, `emprtelefonofijo`, `emprtelefonocelular`, `emprhorarioatencion`, `emprurl`, `emprcodigopostal`, `emprlogo`, `created_at`, `updated_at`) VALUES
(1, 2, 18, 804, '890505424', '7', 'COOPERATIVA DE TRANSPORTADORES HACARITAMA', 'COOTRANSHACARITAMA', 'La empresa que integra la region', 'Calle 7 a 56 211 la ondina vía a rio de oro', 'cootranshacaritama@hotmail.com', '3146034311', '3146034311', 'Lunes a Viernes De 8:00 a.m a 12:00  y de 2:00 p.m a 6:00 p.m', 'www.cootranshacaritama.com', '546552', '890505424_logoHacaritama.png', '2023-10-10 01:25:35', '2023-10-10 01:25:35');

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
  `funcnombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la funcionalidad',
  `functitulo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Título de la funcionalidad',
  `funcruta` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la funcionalidad',
  `funcicono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Clase de css para montar en el link del menú',
  `funcorden` smallint(6) NOT NULL COMMENT 'Orden del en el árbol del menú',
  `funcactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la funcionalidad encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `funcionalidad`
--

INSERT INTO `funcionalidad` (`funcid`, `moduid`, `funcnombre`, `functitulo`, `funcruta`, `funcicono`, `funcorden`, `funcactiva`, `created_at`, `updated_at`) VALUES
(1, 1, 'Menú', 'Gestionar menú', 'admin/menu', 'add_chart', 1, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(2, 1, 'Info notificar correo', 'Gestionar información de notificar correo', 'admin/informacionNotificarCorreo', 'mail_outline_icon', 2, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(3, 1, 'Datos territorial', 'Gestionar datos territorial', 'admin/datosTerritorial', 'language_icon', 3, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(4, 1, 'Empresa', 'Gestionar empresa', 'admin/empresa', 'store', 4, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(5, 2, 'Tipos', 'Gestionar tipos', 'admin/gestionarTipos', 'star_rate_icon', 1, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(6, 2, 'Series', 'Gestionar series documentales', 'admin/seriesDocumentales', 'insert_chart_icon', 2, 1, '2023-10-10 01:25:35', '2023-10-10 01:57:04'),
(7, 2, 'Dependencia', 'Gestionar dependencia', 'admin/dependencia', 'maps_home_work_icon', 3, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(8, 2, 'Persona', 'Gestionar persona', 'admin/persona', 'person_icon', 4, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(9, 2, 'Usuario', 'Gestionar usuario', 'admin/usuario', 'person', 5, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(10, 2, 'Festivos', 'Gestionar festivos', 'admin/festivos', 'calendar_month_icon', 6, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(11, 3, 'Acta', 'Gestionar actas', 'admin/produccion/documental/acta', 'local_library_icon', 1, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(12, 3, 'Certificado', 'Gestionar certificados', 'admin/produccion/documental/certificado', 'note_icon', 2, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(13, 3, 'Circular', 'Gestionar circulares', 'admin/produccion/documental/circular', 'menu_book_icon', 3, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(14, 3, 'Citación', 'Gestionar citaciones', 'admin/produccion/documental/citacion', 'collections_bookmark_icon', 4, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(15, 3, 'Constancia', 'Gestionar constancias', 'admin/produccion/documental/constancia', 'import_contacts_icon', 5, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(16, 3, 'Oficio', 'Gestionar oficios', 'admin/produccion/documental/oficio', 'library_books_icon', 6, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(17, 4, 'Firmar', 'Firmar documentos', 'admin/produccion/documental/firmar', 'import_contacts_icon', 1, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(18, 5, 'Documento entrante', 'Gestionar documento entrante', 'admin/radicacion/documento/entrante', 'post_add_icon', 1, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(19, 5, 'Anular radicado', 'Gestionar anulado de radicado', 'admin/radicacion/documento/anular', 'layers_clear_icon', 2, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(20, 5, 'Bandeja de radicado', 'Gestionar bandeja de radicado', 'admin/radicacion/documento/bandeja', 'content_paste_go_icon', 3, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(21, 6, 'Gestionar', 'Gestionar archivo histórico', 'admin/archivo/historico/gestionar', 'ac_unit_icon', 1, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(22, 6, 'Consultar', 'Gestionar consulta del archivo histórico', 'admin/archivo/historico/consultar', 'find_in_page_icon', 2, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historialcontrasena`
--

CREATE TABLE `historialcontrasena` (
  `hisconid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla historial de contrasena',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `hisconpassword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Password del usuario utilizado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informacionnotificacioncorreo`
--

CREATE TABLE `informacionnotificacioncorreo` (
  `innocoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla informacion notificación correo ',
  `innoconombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se consulta desde el sistema',
  `innocoasunto` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Asunto de la información que lleva notificación del correo',
  `innococontenido` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Contenido de la información que lleva notificación del correo',
  `innocoenviarpiepagina` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si se va incluir el contenido de pie de pagina',
  `innocoenviarcopia` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina se se desea enviar copia al administrador',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `informacionnotificacioncorreo`
--

INSERT INTO `informacionnotificacioncorreo` (`innocoid`, `innoconombre`, `innocoasunto`, `innococontenido`, `innocoenviarpiepagina`, `innocoenviarcopia`, `created_at`, `updated_at`) VALUES
(1, 'piePaginaCorreo', 'Pie página correo', '<p style=\"text-align: justify;\"><strong>Para su inter&eacute;s</strong>:&nbsp;<br /><br /><span style=\"font-size: 10pt;\">1. Este correo fue generado autom&aacute;ticamente, por favor no responda a &eacute;l.</span><br /><span style=\"font-size: 10pt;\">2. La informaci&oacute;n contenida en esta comunicaci&oacute;n es confidencial y s&oacute;lo puede ser utilizada por la persona natural o jur&iacute;dica a la cual est&aacute; dirigida.</span><br /><span style=\"font-size: 10pt;\">3. Si no es el destinatario autorizado, cualquier retenci&oacute;n, difusi&oacute;n, distribuci&oacute;n o copia de este mensaje, se encuentra prohibida y sancionada por la ley.</span><br /><span style=\"font-size: 10pt;\">4. Si por error recibe este mensaje, favor reenviar y borrar el mensaje recibido inmediatamente\". (Resoluci&oacute;n No. 089 de 2003 - Reglamento para el uso de Internet y Correo Electr&oacute;nico en el AGN. Art&iacute;culo 3&deg; numeral 5.</span></p>', 0, 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(2, 'registroUsuario', '¡Bienvenido al CRM de siglaCooperativa!', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreUsuario</strong>, Es un placer informarle que a partir de este momento, la <strong>nombreEmpresa </strong>ha implementado un nuevo y avanzado Sistema de Gesti&oacute;n de Relaciones con el Cliente (CRM). Este sistema ha sido dise&ntilde;ado para mejorar significativamente nuestros procesos internos y proporcionarle a usted, como usuario, una experiencia m&aacute;s eficiente y personalizada.<br><br></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">A continuaci&oacute;n, encontrar&aacute; los detalles clave de esta actualizaci&oacute;n:<br><br></p>\r\n<p class=\"MsoNormal\">URL del Sistema: <strong><a href=\"urlSistema\" target=\"_blank\">urlSistema</a> </strong><br>Usuario del CRM: <strong>usuarioSistema</strong><br>Credenciales de acceso: <strong>contrasenaSistema</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br>Este nuevo CRM le permitir&aacute;:</p>\r\n<ul>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Acceder a su informaci&oacute;n y estado de cuenta de manera r&aacute;pida y sencilla.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Realizar seguimiento de sus transacciones y solicitudes.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Comunicarse de manera efectiva con nuestro equipo de trabajo.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Acceder a servicios y recursos exclusivos para miembros de la Cooperativa.</li>\r\n<li class=\"MsoNormal\" style=\"text-align: justify;\">Le recomendamos que cambie su contrase&ntilde;a inicial despu&eacute;s de su primer inicio de sesi&oacute;n para garantizar la seguridad de su cuenta.</li>\r\n</ul>\r\n<p>Estamos comprometidos en brindarle un servicio de la m&aacute;s alta calidad, y creemos que esta actualizaci&oacute;n nos permitir&aacute; servirle de manera m&aacute;s efectiva. Si tiene alguna pregunta o necesita asistencia para familiarizarse con el nuevo sistema, no dude en ponerse en contacto con nuestro equipo de tecn&oacute;loga, quienes estar&aacute;n encantados de ayudarle.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Agradecemos su confianza en la nombreEmpresa y esperamos que este nuevo CRM mejore su experiencia con nosotros. Estamos seguros de que encontrar&aacute; el sistema m&aacute;s intuitivo y &uacute;til.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&iexcl;Bienvenido al futuro de la nombreEmpresa!</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\"><strong>nombreGerente</strong><br><strong>Gerente general </strong><br><strong>nombreEmpresa</strong></p>\r\n<p>&nbsp;</p>', 1, 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(3, 'solicitaFirmaDocumento', 'Solicitud de firma para del documento con referencia numeroDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreFeje</strong>, por medio de la presente me permito informar que se ha generado un documento importante que requiere su aprobaci&oacute;n y firma.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Detalles del documento:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Tipo de documento: <strong>tipoDocumental</strong><br>N&uacute;mero de documento: <strong>numeroDocumental</strong><br>Fecha de generaci&oacute;n: <strong>fechaDocumento</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Por favor, revise el documento y proceda a firmarlo utilizando la plataforma de CRM de nuestra cooperativa. Si tiene alguna pregunta o inquietud sobre el contenido del documento o el proceso de firma, no dude en contactarme.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Su cooperaci&oacute;n en este asunto es altamente apreciada y fundamental para avanzar en este proceso de manera oportuna.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Quedo a su disposici&oacute;n para cualquier aclaraci&oacute;n adicional que pueda necesitar.<br><br></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,<br><br></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><em><strong>nombreUsuario</strong></em><br><em><strong>cargoUsuario</strong></em></p>', 1, 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(4, 'anularSolicitudFirmaDocumento', 'Revisión y ajustes necesarios para documento con referencia numeroDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreFeje</strong>, le informo que el documento <strong>tipoDocumental </strong>con el n&uacute;mero <strong>numeroDocumental</strong>, que est&aacute; programado para su firma, requiere algunos ajustes y revisiones antes de que podamos proceder con el proceso de firma.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Detalles del documento:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Tipo de documento: <strong>tipoDocumental</strong><br>N&uacute;mero de documento: <strong>numeroDocumental</strong><br>Fecha de generaci&oacute;n: <strong>fechaDocumento</strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Los ajustes necesarios est&aacute;n relacionados con &ldquo;<em>observacionAnulacionFirma</em>&rdquo;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Tan pronto como realice los ajustes, estaremos listos para proceder con el proceso de firma.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Quedo a su disposici&oacute;n para cualquier aclaraci&oacute;n adicional que pueda necesitar.<br><br></p>\r\n<p class=\"MsoNormal\" style=\"mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; text-align: justify; line-height: normal;\"><span style=\"font-size: 12.0pt; font-family: \'Times New Roman\',serif; mso-fareast-font-family: \'Times New Roman\'; mso-font-kerning: 0pt; mso-ligatures: none; mso-fareast-language: ES-CO;\">Atentamente,<br><br></span></p>\r\n<p class=\"MsoNormal\" style=\"mso-margin-top-alt: auto; mso-margin-bottom-alt: auto; text-align: justify; line-height: normal;\"><strong><em>nombreUsuario</em></strong><br><strong><em>cargoUsuario</em></strong></p>', 1, 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(5, 'notificarEnvioDocumento', 'Envío de documento digital de la Cooperativa siglaCooperativa con referencia numeroDocumental', '<p style=\"text-align: justify;\">Estimado/a <strong>nombreUsuario</strong>,</p>\r\n<p style=\"text-align: justify;\">Por medio de la presente nos permitimos informar que la dependencia de <strong>nombreDependencia </strong>de la <strong>nombreEmpresa </strong>ha enviado un documento en formato digital. Este archivo adjunto contiene la informaci&oacute;n requerida y puede ser revisado en su dispositivo electr&oacute;nico.</p>\r\n<p style=\"text-align: justify;\"><br>Si tiene alguna pregunta o necesita asistencia adicional, no dude en ponerse en contacto con nuestro equipo de soporte.</p>\r\n<p style=\"text-align: justify;\"><br>Agradecemos su colaboraci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p>Atentamente,</p>\r\n<p>&nbsp;</p>\r\n<p><em><strong>jefeDependencia</strong></em><br><em><strong>nombreEmpresa</strong></em><br><em><strong>nombreDependencia</strong></em></p>', 1, 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(6, 'notificarFirmadoDocumento', 'Solicitud de token de verificación para el firmado del documento numeroDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\">Estimado <strong>nombreJefe</strong>, para avanzar con el proceso de firma electr&oacute;nica del documento <strong>numeroDocumental</strong>, es necesario que ingrese el siguiente c&oacute;digo de verificaci&oacute;n:</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><strong>C&oacute;digo de Verificaci&oacute;n:&nbsp;<em>tokenAcceso</em></strong></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><br>Tenga en cuenta que este token de verificaci&oacute;n ser&aacute; v&aacute;lido durante los pr&oacute;ximos&nbsp;<strong>tiempoToken </strong>minutos. Si transcurre este tiempo sin completar el proceso, deber&aacute; solicitar un nuevo token.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Por favor, acceda a nuestra plataforma y proporcione el token que le hemos proporcionado. Luego, haga clic en el bot&oacute;n de firma para completar el proceso.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Gracias por su colaboraci&oacute;n y compromiso con la seguridad de nuestros servicios.</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Atentamente,</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">Administrador del CRM</p>', 1, 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(7, 'notificarRegistroRadicado', 'Nuevo documento radicado en la Ventanilla Única Virtual con consecutivo numeroRadicado', '<p style=\"text-align: justify;\">Estimado <strong>nombreUsuario</strong>, agradecemos el uso del servicio prestado por la Ventanilla &Uacute;nica de la nombreEmpresa. Queremos informarle que su documento ha sido radicado con los siguientes detalles:</p>\r\n<p style=\"text-align: justify;\"><em>N&uacute;mero de radicado:&nbsp; &nbsp;<strong>numeroRadicado</strong></em><br><em>Fecha de radicado:&nbsp; &nbsp; &nbsp; &nbsp;<strong>fechaRadicado</strong></em><br><em>Destino:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>nombreDependencia</strong></em><br><em>Radicado por:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>nombreFuncionario</strong></em></p>\r\n<p style=\"text-align: justify;\">Le recordamos que la funci&oacute;n de la Ventanilla &Uacute;nica es recibir, radicar y redireccionar su solicitud, cumpliendo con los criterios b&aacute;sicos, ante la instancia correspondiente. La respuesta ser&aacute; proporcionada por la oficina indicada en su comunicaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">Quedamos a su disposici&oacute;n para cualquier consulta adicional.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Ventanilla &Uacute;nica<br>nombreEmpresa</p>', 1, 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(8, 'notificarRadicadoDocumento', 'Nuevo documento radicado con consecutivo numeroRadicado', '<p style=\"text-align: justify;\">Estimado usuario de <strong>nombreDependencia</strong>, les informamos que ha llegado un nuevo documento a trav&eacute;s de la Ventanilla &Uacute;nica, el cual ha sido debidamente radicado con los siguientes detalles:</p>\r\n<p style=\"text-align: justify;\">Radicado:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>numeroRadicado</strong><br>Fecha de Recepci&oacute;n:&nbsp; <strong>fechaRadicado</strong><br>Destino:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<strong>nombreDependencia</strong><br>Radicado por:&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <strong>nombreFuncionario</strong></p>\r\n<p style=\"text-align: justify;\">Queremos recordarles que, de acuerdo con los procedimientos, se tiene un plazo de 15 d&iacute;as h&aacute;biles para proporcionar una respuesta a esta solicitud. Este plazo iniciar&aacute; a partir del d&iacute;a siguiente a la radicaci&oacute;n.</p>\r\n<p style=\"text-align: justify;\">Por favor, tomen en cuenta que para acceder al contenido completo, les recomendamos ingresar al nuestro CRM institucional.</p>\r\n<p style=\"text-align: justify;\">Agradecemos su compromiso y dedicaci&oacute;n en pro del mejoramiento continuo de nuestros procesos.</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Atentamente,</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>\r\n<p style=\"text-align: justify;\">Ventanilla &Uacute;nica<br>nombreEmpresa</p>\r\n<p style=\"text-align: justify;\">&nbsp;</p>', 1, 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04'),
(9, 'notificarFirmaTipoDocumental', 'Proceso de firmado exitoso del tipo documental tipoDocumental', '<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Estimado usuario de la dependencia <strong>nombreDependencia</strong>, por medio de la presente me permito informar que el tipo documental \"<strong>tipoDocumental</strong>\"&nbsp;<strong>&nbsp;</strong>ha sido correctamente firmado y est&aacute; listo para avanzar al siguiente paso en el proceso.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Por favor, procede con el sellamiento y cualquier otra acci&oacute;n necesaria para completar este proceso de manera satisfactoria.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en contactarme. Agradezco tu atenci&oacute;n y dedicaci&oacute;n en este asunto.</span></p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\">&nbsp;</p>\r\n<p class=\"MsoNormal\" style=\"text-align: justify;\"><span lang=\"ES\" style=\"mso-ansi-language: ES;\">Atentamente,<br><br></span></p>\r\n<p class=\"MsoNormal\"><em><strong><span lang=\"ES\" style=\"mso-ansi-language: ES;\">nombreJefe<br></span><span lang=\"ES\" style=\"mso-ansi-language: ES;\">cargoJefe</span></strong></em></p>', 1, 0, '2023-10-10 01:24:04', '2023-10-10 01:24:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresosistema`
--

CREATE TABLE `ingresosistema` (
  `ingsisid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla ingreso sistema',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario',
  `ingsisipacceso` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ip de la cual accede el usuario al sistema',
  `ingsisfechahoraingreso` datetime NOT NULL COMMENT 'Fecha y hora de ingreso al sistema',
  `ingsisfechahorasalida` datetime DEFAULT NULL COMMENT 'Fecha y hora de ingreso al sistema',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ingresosistema`
--

INSERT INTO `ingresosistema` (`ingsisid`, `usuaid`, `ingsisipacceso`, `ingsisfechahoraingreso`, `ingsisfechahorasalida`, `created_at`, `updated_at`) VALUES
(1, 1, '127.0.0.1', '2023-10-09 20:45:06', '2023-10-09 20:59:36', '2023-10-10 01:45:06', '2023-10-10 01:59:36'),
(2, 1, '127.0.0.1', '2023-10-09 20:59:50', NULL, '2023-10-10 01:59:50', '2023-10-10 01:59:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentosfallidos`
--

CREATE TABLE `intentosfallidos` (
  `intfalid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla intentos fallidos',
  `intfalusurio` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Usuario que accede al sistema',
  `intfalipacceso` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ip de la cual accede el usuario al sistema',
  `intfalfecha` datetime NOT NULL COMMENT 'Fecha y hora de ingreso al sistema',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(12, '2023_08_28_084052_create_tipo_relacion_laboral', 1),
(13, '2023_08_28_084053_create_tipo_estado_radicacion_documento_entrante', 1),
(14, '2023_08_28_084054_create_tipo_estante_archivador', 1),
(15, '2023_08_28_084055_create_tipo_caja_ubicacion', 1),
(16, '2023_08_28_084056_create_tipo_carpeta_ubicacion', 1),
(17, '2023_08_28_085009_create_informacion_notificacion_correo', 1),
(18, '2023_08_28_085010_create_cargo_laboral', 1),
(19, '2023_08_28_085012_create_departamento', 1),
(20, '2023_08_28_085013_create_municipio', 1),
(21, '2023_08_28_085014_create_persona', 1),
(22, '2023_08_28_085110_create_usuario', 1),
(23, '2023_08_28_085111_create_ingreso_sistema', 1),
(24, '2023_08_28_085112_create_intentos_fallidos', 1),
(25, '2023_08_28_085113_create_historial_contrasena', 1),
(26, '2023_08_28_086105_create_serie_documental', 1),
(27, '2023_08_28_086106_create_sub_serie_documental', 1),
(28, '2023_08_28_086110_create_dependencia', 1),
(29, '2023_08_28_086111_create_dependencia_persona', 1),
(30, '2023_08_28_086112_create_dependencia_sub_serie_documental', 1),
(31, '2023_08_28_086114_create_token_firma_persona', 1),
(32, '2023_08_31_043412_create_modulo', 1),
(33, '2023_08_31_043621_create_rol', 1),
(34, '2023_08_31_043639_create_funcionalidad', 1),
(35, '2023_08_31_043658_create_rol_funcionalidad', 1),
(36, '2023_08_31_043659_create_usuario_rol', 1),
(37, '2023_09_02_140139_create_empresa', 1),
(38, '2023_09_02_140140_create_festivo', 1),
(39, '2023_09_07_050956_create_codigo_documental', 1),
(40, '2023_09_07_052612_create_codigo_documental_proceso', 1),
(41, '2023_09_07_052618_create_codigo_documental_proceso_acta', 1),
(42, '2023_09_07_055753_create_codigo_documental_proceso_certificado', 1),
(43, '2023_09_07_055759_create_codigo_documental_proceso_circular', 1),
(44, '2023_09_07_055806_create_codigo_documental_proceso_citacion', 1),
(45, '2023_09_07_055812_create_codigo_documental_proceso_constancia', 1),
(46, '2023_09_07_055817_create_codigo_documental_proceso_oficio', 1),
(47, '2023_09_09_102437_create_codigo_documental_proceso_firma', 1),
(48, '2023_09_09_102822_create_codigo_documental_proceso_anexo', 1),
(49, '2023_09_09_103332_create_codigo_documental_proceso_copia', 1),
(50, '2023_09_09_103333_create_codigo_documental_proceso_cambio_estado', 1),
(51, '2023_09_09_103333_create_codigo_documental_proceso_compartido', 1),
(52, '2023_09_09_103335_create_persona_radica_documento', 1),
(53, '2023_09_09_103336_create_radicacion_documento_entrante', 1),
(54, '2023_09_09_103337_create_radicacion_documento_entrante_anexo', 1),
(55, '2023_09_09_103338_create_radicacion_documento_entrante_dependencia', 1),
(56, '2023_09_09_103339_create_radicacion_documento_entrante_cambio_estado', 1),
(57, '2023_09_09_103340_create_codigo_documental_proceso_radicacion_documento_entrante', 1),
(58, '2023_09_09_103345_create_archivo_historico', 1),
(59, '2023_09_09_103346_create_archivo_historico_digitalizado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modulo`
--

CREATE TABLE `modulo` (
  `moduid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla módulo',
  `modunombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del módulo',
  `moduicono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Clase de css para montar en el link del módulo',
  `moduorden` smallint(6) NOT NULL COMMENT 'Orden del en el árbol del menú que se muesra el módulo',
  `moduactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el módulo encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `modulo`
--

INSERT INTO `modulo` (`moduid`, `modunombre`, `moduicono`, `moduorden`, `moduactivo`, `created_at`, `updated_at`) VALUES
(1, 'Configuración', 'settings_applications', 1, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(2, 'Gestionar', 'ac_unit_icon', 2, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(3, 'Producción documental', 'menu_book_icon', 3, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(4, 'Firmar', 'folder_special_icon', 4, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(5, 'Radicación', 'insert_page_break_icon', 5, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(6, 'Archivo histórico', 'forward_to_inbox_icon', 6, 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `muniid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla municipio',
  `munidepaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del departamento',
  `municodigo` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código del municipio',
  `muninombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del municipio',
  `munihacepresencia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la entidad hace presencia en este municipio',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`muniid`, `munidepaid`, `municodigo`, `muninombre`, `munihacepresencia`, `created_at`, `updated_at`) VALUES
(1, 1, '05001', 'Medellin', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(2, 1, '05002', 'Abejorral', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(3, 1, '05004', 'Abriaqui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(4, 1, '05021', 'Alejandria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(5, 1, '05030', 'Amaga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(6, 1, '05031', 'Amalfi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(7, 1, '05034', 'Andes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(8, 1, '05036', 'Angelopolis', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(9, 1, '05038', 'Angostura', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(10, 1, '05040', 'Anori', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(11, 1, '05042', 'Santafe de Antioquia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(12, 1, '05044', 'Anza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(13, 1, '05045', 'Apartado', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(14, 1, '05051', 'Arboletes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(15, 1, '05055', 'Argelia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(16, 1, '05059', 'Armenia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(17, 1, '05079', 'Barbosa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(18, 1, '05086', 'Belmira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(19, 1, '05088', 'Bello', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(20, 1, '05091', 'Betania', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(21, 1, '05093', 'Betulia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(22, 1, '05101', 'Ciudad Bolivar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(23, 1, '05107', 'Briceño', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(24, 1, '05113', 'Buritica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(25, 1, '05120', 'Caceres', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(26, 1, '05125', 'Caicedo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(27, 1, '05129', 'Caldas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(28, 1, '05134', 'Campamento', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(29, 1, '05138', 'Cañasgordas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(30, 1, '05142', 'Caracoli', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(31, 1, '05145', 'Caramanta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(32, 1, '05147', 'Carepa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(33, 1, '05148', 'El Carmen de Viboral', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(34, 1, '05150', 'Carolina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(35, 1, '05154', 'Caucasia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(36, 1, '05172', 'Chigorodo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(37, 1, '05190', 'Cisneros', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(38, 1, '05197', 'Cocorna', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(39, 1, '05206', 'Concepcion', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(40, 1, '05209', 'Concordia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(41, 1, '05212', 'Copacabana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(42, 1, '05234', 'Dabeiba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(43, 1, '05237', 'Don Matias', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(44, 1, '05240', 'Ebejico', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(45, 1, '05250', 'El Bagre', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(46, 1, '05264', 'Entrerrios', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(47, 1, '05266', 'Envigado', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(48, 1, '05282', 'Fredonia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(49, 1, '05284', 'Frontino', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(50, 1, '05306', 'Giraldo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(51, 1, '05308', 'Girardota', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(52, 1, '05310', 'Gomez Plata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(53, 1, '05313', 'Granada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(54, 1, '05315', 'Guadalupe', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(55, 1, '05318', 'Guarne', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(56, 1, '05321', 'Guatape', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(57, 1, '05347', 'Heliconia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(58, 1, '05353', 'Hispania', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(59, 1, '05360', 'Itagui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(60, 1, '05361', 'Ituango', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(61, 1, '05364', 'Jardin', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(62, 1, '05368', 'Jerico', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(63, 1, '05376', 'La Ceja', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(64, 1, '05380', 'La Estrella', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(65, 1, '05390', 'La Pintada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(66, 1, '05400', 'La Union', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(67, 1, '05411', 'Liborina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(68, 1, '05425', 'Maceo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(69, 1, '05440', 'Marinilla', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(70, 1, '05467', 'Montebello', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(71, 1, '05475', 'Murindo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(72, 1, '05480', 'Mutata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(73, 1, '05483', 'Nariño', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(74, 1, '05490', 'Necocli', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(75, 1, '05495', 'Nechi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(76, 1, '05501', 'Olaya', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(77, 1, '05541', 'Peðol', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(78, 1, '05543', 'Peque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(79, 1, '05576', 'Pueblorrico', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(80, 1, '05579', 'Puerto Berrio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(81, 1, '05585', 'Puerto Nare', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(82, 1, '05591', 'Puerto Triunfo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(83, 1, '05604', 'Remedios', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(84, 1, '05607', 'Retiro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(85, 1, '05615', 'Rionegro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(86, 1, '05628', 'Sabanalarga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(87, 1, '05631', 'Sabaneta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(88, 1, '05642', 'Salgar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(89, 1, '05647', 'San Andres De Cuerquia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(90, 1, '05649', 'San Carlos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(91, 1, '05652', 'San Francisco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(92, 1, '05656', 'San Jeronimo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(93, 1, '05658', 'San Jose De La Montaña', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(94, 1, '05659', 'San Juan De Uraba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(95, 1, '05660', 'San Luis', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(96, 1, '05664', 'San Pedro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(97, 1, '05665', 'San Pedro De Uraba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(98, 1, '05667', 'San Rafael', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(99, 1, '05670', 'San Roque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(100, 1, '05674', 'San Vicente', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(101, 1, '05679', 'Santa Barbara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(102, 1, '05686', 'Santa Rosa De Osos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(103, 1, '05690', 'Santo Domingo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(104, 1, '05697', 'El Santuario', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(105, 1, '05736', 'Segovia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(106, 1, '05756', 'Sonson', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(107, 1, '05761', 'Sopetran', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(108, 1, '05789', 'Tamesis', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(109, 1, '05790', 'Taraza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(110, 1, '05792', 'Tarso', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(111, 1, '05809', 'Titiribi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(112, 1, '05819', 'Toledo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(113, 1, '05837', 'Turbo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(114, 1, '05842', 'Uramita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(115, 1, '05847', 'Urrao', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(116, 1, '05854', 'Valdivia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(117, 1, '05856', 'Valparaiso', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(118, 1, '05858', 'Vegachi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(119, 1, '05861', 'Venecia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(120, 1, '05873', 'Vigia Del Fuerte', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(121, 1, '05885', 'Yali', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(122, 1, '05887', 'Yarumal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(123, 1, '05890', 'Yolombo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(124, 1, '05893', 'Yondo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(125, 1, '05895', 'Zaragoza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(126, 2, '08001', 'Barranquilla', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(127, 2, '08078', 'Baranoa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(128, 2, '08137', 'Campo De La Cruz', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(129, 2, '08141', 'Candelaria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(130, 2, '08296', 'Galapa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(131, 2, '08372', 'Juan De Acosta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(132, 2, '08421', 'Luruaco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(133, 2, '08433', 'Malambo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(134, 2, '08436', 'Manati', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(135, 2, '08520', 'Palmar De Varela', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(136, 2, '08549', 'Piojo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(137, 2, '08558', 'Polonuevo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(138, 2, '08560', 'Ponedera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(139, 2, '08573', 'Puerto Colombia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(140, 2, '08606', 'Repelon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(141, 2, '08634', 'Sabanagrande', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(142, 2, '08638', 'Sabanalarga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(143, 2, '08675', 'Santa Lucia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(144, 2, '08685', 'Santo Tomas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(145, 2, '08758', 'Soledad', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(146, 2, '08770', 'Suan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(147, 2, '08832', 'Tubara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(148, 2, '08849', 'Usiacuri', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(149, 3, '11001', 'Bogotá, D.C.', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(150, 4, '13001', 'Cartagena', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(151, 4, '13006', 'Achi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(152, 4, '13030', 'Altos Del Rosario', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(153, 4, '13042', 'Arenal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(154, 4, '13052', 'Arjona', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(155, 4, '13062', 'Arroyohondo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(156, 4, '13074', 'Barranco De Loba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(157, 4, '13140', 'Calamar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(158, 4, '13160', 'Cantagallo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(159, 4, '13188', 'Cicuco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(160, 4, '13212', 'Cordoba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(161, 4, '13222', 'Clemencia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(162, 4, '13244', 'El Carmen De Bolivar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(163, 4, '13248', 'El Guamo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(164, 4, '13268', 'El Peñon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(165, 4, '13300', 'Hatillo De Loba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(166, 4, '13430', 'Magangue', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(167, 4, '13433', 'Mahates', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(168, 4, '13440', 'Margarita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(169, 4, '13442', 'Maria La Baja', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(170, 4, '13458', 'Montecristo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(171, 4, '13468', 'Mompos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(172, 4, '13490', 'Norosi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(173, 4, '13473', 'Morales', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(174, 4, '13549', 'Pinillos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(175, 4, '13580', 'Regidor', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(176, 4, '13600', 'Rio Viejo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(177, 4, '13620', 'San Cristobal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(178, 4, '13647', 'San Estanislao', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(179, 4, '13650', 'San Fernando', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(180, 4, '13654', 'San Jacinto', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(181, 4, '13655', 'San Jacinto del Cauca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(182, 4, '13657', 'San Juan Nepomuceno', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(183, 4, '13667', 'San Martin de Loba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(184, 4, '13670', 'San Pablo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(185, 4, '13673', 'Santa Catalina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(186, 4, '13683', 'Santa Rosa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(187, 4, '13688', 'Santa Rosa del Sur', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(188, 4, '13744', 'Simiti', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(189, 4, '13760', 'Soplaviento', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(190, 4, '13780', 'Talaigua Nuevo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(191, 4, '13810', 'Tiquisio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(192, 4, '13836', 'Turbaco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(193, 4, '13838', 'Turbana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(194, 4, '13873', 'Villanueva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(195, 4, '13894', 'Zambrano', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(196, 5, '15001', 'Tunja', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(197, 5, '15022', 'Almeida', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(198, 5, '15047', 'Aquitania', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(199, 5, '15051', 'Arcabuco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(200, 5, '15087', 'Belen', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(201, 5, '15090', 'Berbeo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(202, 5, '15092', 'Beteitiva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(203, 5, '15097', 'Boavita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(204, 5, '15104', 'Boyaca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(205, 5, '15106', 'Briceño', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(206, 5, '15109', 'Buenavista', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(207, 5, '15114', 'Busbanza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(208, 5, '15131', 'Caldas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(209, 5, '15135', 'Campohermoso', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(210, 5, '15162', 'Cerinza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(211, 5, '15172', 'Chinavita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(212, 5, '15176', 'Chiquinquira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(213, 5, '15180', 'Chiscas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(214, 5, '15183', 'Chita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(215, 5, '15185', 'Chitaraque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(216, 5, '15187', 'Chivata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(217, 5, '15189', 'Cienega', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(218, 5, '15204', 'Combita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(219, 5, '15212', 'Coper', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(220, 5, '15215', 'Corrales', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(221, 5, '15218', 'Covarachia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(222, 5, '15223', 'Cubara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(223, 5, '15224', 'Cucaita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(224, 5, '15226', 'Cuitiva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(225, 5, '15232', 'Chiquiza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(226, 5, '15236', 'Chivor', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(227, 5, '15238', 'Duitama', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(228, 5, '15244', 'El Cocuy', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(229, 5, '15248', 'El Espino', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(230, 5, '15272', 'Firavitoba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(231, 5, '15276', 'Floresta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(232, 5, '15293', 'Gachantiva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(233, 5, '15296', 'Gameza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(234, 5, '15299', 'Garagoa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(235, 5, '15317', 'Guacamayas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(236, 5, '15322', 'Guateque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(237, 5, '15325', 'Guayata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(238, 5, '15332', 'Gsican', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(239, 5, '15362', 'Iza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(240, 5, '15367', 'Jenesano', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(241, 5, '15368', 'Jerico', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(242, 5, '15377', 'Labranzagrande', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(243, 5, '15380', 'La Capilla', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(244, 5, '15401', 'La Victoria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(245, 5, '15403', 'La Uvita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(246, 5, '15407', 'Villa de Leyva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(247, 5, '15425', 'Macanal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(248, 5, '15442', 'Maripi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(249, 5, '15455', 'Miraflores', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(250, 5, '15464', 'Mongua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(251, 5, '15466', 'Mongui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(252, 5, '15469', 'Moniquira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(253, 5, '15476', 'Motavita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(254, 5, '15480', 'Muzo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(255, 5, '15491', 'Nobsa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(256, 5, '15494', 'Nuevo Colon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(257, 5, '15500', 'Oicata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(258, 5, '15507', 'Otanche', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(259, 5, '15511', 'Pachavita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(260, 5, '15514', 'Paez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(261, 5, '15516', 'Paipa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(262, 5, '15518', 'Pajarito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(263, 5, '15522', 'Panqueba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(264, 5, '15531', 'Pauna', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(265, 5, '15533', 'Paya', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(266, 5, '15537', 'Paz De Rio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(267, 5, '15542', 'Pesca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(268, 5, '15550', 'Pisba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(269, 5, '15572', 'Puerto Boyaca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(270, 5, '15580', 'Quipama', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(271, 5, '15599', 'Ramiriqui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(272, 5, '15600', 'Raquira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(273, 5, '15621', 'Rondon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(274, 5, '15632', 'Saboya', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(275, 5, '15638', 'Sachica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(276, 5, '15646', 'Samaca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(277, 5, '15660', 'San Eduardo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(278, 5, '15664', 'San Jose se Pare', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(279, 5, '15667', 'San Luis se Gaceno', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(280, 5, '15673', 'San Mateo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(281, 5, '15676', 'San Miguel se Sema', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(282, 5, '15681', 'San Pablo se Borbur', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(283, 5, '15686', 'Santana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(284, 5, '15690', 'Santa Maria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(285, 5, '15693', 'Santa Rosa se Viterbo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(286, 5, '15696', 'Santa Sofia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(287, 5, '15720', 'Sativanorte', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(288, 5, '15723', 'Sativasur', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(289, 5, '15740', 'Siachoque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(290, 5, '15753', 'Soata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(291, 5, '15755', 'Socota', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(292, 5, '15757', 'Socha', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(293, 5, '15759', 'Sogamoso', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(294, 5, '15761', 'Somondoco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(295, 5, '15762', 'Sora', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(296, 5, '15763', 'Sotaquira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(297, 5, '15764', 'Soraca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(298, 5, '15774', 'Susacon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(299, 5, '15776', 'Sutamarchan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(300, 5, '15778', 'Sutatenza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(301, 5, '15790', 'Tasco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(302, 5, '15798', 'Tenza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(303, 5, '15804', 'Tibana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(304, 5, '15806', 'Tibasosa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(305, 5, '15808', 'Tinjaca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(306, 5, '15810', 'Tipacoque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(307, 5, '15814', 'Toca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(308, 5, '15816', 'Togsi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(309, 5, '15820', 'Topaga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(310, 5, '15822', 'Tota', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(311, 5, '15832', 'Tunungua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(312, 5, '15835', 'Turmeque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(313, 5, '15837', 'Tuta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(314, 5, '15839', 'Tutaza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(315, 5, '15842', 'Umbita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(316, 5, '15861', 'Ventaquemada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(317, 5, '15879', 'Viracacha', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(318, 5, '15897', 'Zetaquira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(319, 6, '17001', 'Manizales', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(320, 6, '17013', 'Aguadas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(321, 6, '17042', 'Anserma', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(322, 6, '17050', 'Aranzazu', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(323, 6, '17088', 'Belalcazar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(324, 6, '17174', 'Chinchina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(325, 6, '17272', 'Filadelfia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(326, 6, '17380', 'La Dorada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(327, 6, '17388', 'La Merced', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(328, 6, '17433', 'Manzanares', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(329, 6, '17442', 'Marmato', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(330, 6, '17444', 'Marquetalia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(331, 6, '17446', 'Marulanda', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(332, 6, '17486', 'Neira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(333, 6, '17495', 'Norcasia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(334, 6, '17513', 'Pacora', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(335, 6, '17524', 'Palestina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(336, 6, '17541', 'Pensilvania', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(337, 6, '17614', 'Riosucio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(338, 6, '17616', 'Risaralda', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(339, 6, '17653', 'Salamina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(340, 6, '17662', 'Samana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(341, 6, '17665', 'San Jose', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(342, 6, '17777', 'Supia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(343, 6, '17867', 'Victoria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(344, 6, '17873', 'Villamaria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(345, 6, '17877', 'Viterbo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(346, 7, '18001', 'Florencia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(347, 7, '18029', 'Albania', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(348, 7, '18094', 'Belen se los Andaquies', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(349, 7, '18150', 'Cartagena del Chaira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(350, 7, '18205', 'Curillo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(351, 7, '18247', 'El Doncello', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(352, 7, '18256', 'El Paujil', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(353, 7, '18410', 'La Montañita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(354, 7, '18460', 'Milan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(355, 7, '18479', 'Morelia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(356, 7, '18592', 'Puerto Rico', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(357, 7, '18610', 'San Jose del Fragua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(358, 7, '18753', 'San Vicente del Caguan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(359, 7, '18756', 'Solano', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(360, 7, '18785', 'Solita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(361, 7, '18860', 'Valparaiso', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(362, 8, '19001', 'Popayan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(363, 8, '19022', 'Almaguer', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(364, 8, '19050', 'Argelia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(365, 8, '19075', 'Balboa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(366, 8, '19100', 'Bolivar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(367, 8, '19110', 'Buenos Aires', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(368, 8, '19130', 'Cajibio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(369, 8, '19137', 'Caldono', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(370, 8, '19142', 'Caloto', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(371, 8, '19212', 'Corinto', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(372, 8, '19256', 'El Tambo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(373, 8, '19290', 'Florencia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(374, 8, '19300', 'Guachene', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(375, 8, '19318', 'Guapi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(376, 8, '19355', 'Inza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(377, 8, '19364', 'Jambalo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(378, 8, '19392', 'La Sierra', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(379, 8, '19397', 'La Vega', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(380, 8, '19418', 'Lopez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(381, 8, '19450', 'Mercaderes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(382, 8, '19455', 'Miranda', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(383, 8, '19473', 'Morales', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(384, 8, '19513', 'Padilla', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(385, 8, '19517', 'Paez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(386, 8, '19532', 'Patia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(387, 8, '19533', 'Piamonte', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(388, 8, '19548', 'Piendamo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(389, 8, '19573', 'Puerto Tejada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(390, 8, '19585', 'Purace', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(391, 8, '19622', 'Rosas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(392, 8, '19693', 'San Sebastian', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(393, 8, '19698', 'Santander de Quilichao', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(394, 8, '19701', 'Santa Rosa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(395, 8, '19743', 'Silvia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(396, 8, '19760', 'Sotara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(397, 8, '19780', 'Suarez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(398, 8, '19785', 'Sucre', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(399, 8, '19807', 'Timbio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(400, 8, '19809', 'Timbiqui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(401, 8, '19821', 'Toribio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(402, 8, '19824', 'Totoro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(403, 8, '19845', 'Villa Rica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(404, 9, '20001', 'Valledupar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(405, 9, '20011', 'Aguachica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(406, 9, '20013', 'Agustin Codazzi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(407, 9, '20032', 'Astrea', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(408, 9, '20045', 'Becerril', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(409, 9, '20060', 'Bosconia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(410, 9, '20175', 'Chimichagua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(411, 9, '20178', 'Chiriguana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(412, 9, '20228', 'Curumani', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(413, 9, '20238', 'El Copey', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(414, 9, '20250', 'El Paso', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(415, 9, '20295', 'Gamarra', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(416, 9, '20310', 'Gonzalez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(417, 9, '20383', 'La Gloria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(418, 9, '20400', 'La Jagua De Ibirico', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(419, 9, '20443', 'Manaure', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(420, 9, '20517', 'Pailitas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(421, 9, '20550', 'Pelaya', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(422, 9, '20570', 'Pueblo Bello', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(423, 9, '20614', 'Rio De Oro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(424, 9, '20621', 'La Paz', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(425, 9, '20710', 'San Alberto', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(426, 9, '20750', 'San Diego', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(427, 9, '20770', 'San Martin', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(428, 9, '20787', 'Tamalameque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(429, 10, '23001', 'Monteria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(430, 10, '23068', 'Ayapel', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(431, 10, '23079', 'Buenavista', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(432, 10, '23090', 'Canalete', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(433, 10, '23162', 'Cerete', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(434, 10, '23168', 'Chima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(435, 10, '23182', 'Chinu', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(436, 10, '23189', 'Cienaga De Oro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(437, 10, '23300', 'Cotorra', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(438, 10, '23350', 'La Apartada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(439, 10, '23417', 'Lorica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(440, 10, '23419', 'Los Cordobas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(441, 10, '23464', 'Momil', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(442, 10, '23466', 'Montelibano', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(443, 10, '23500', 'Moñitos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(444, 10, '23555', 'Planeta Rica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(445, 10, '23570', 'Pueblo Nuevo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(446, 10, '23574', 'Puerto Escondido', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(447, 10, '23580', 'Puerto Libertador', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(448, 10, '23586', 'Purisima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(449, 10, '23660', 'Sahagun', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(450, 10, '23670', 'San Andres Sotavento', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(451, 10, '23672', 'San Antero', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(452, 10, '23675', 'San Bernardo Del Viento', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(453, 10, '23678', 'San Carlos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(454, 10, '23682', 'San José De Uré', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(455, 10, '23686', 'San Pelayo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(456, 10, '23807', 'Tierralta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(457, 10, '23815', 'Tuchín', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(458, 10, '23855', 'Valencia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(459, 11, '25001', 'Agua De Dios', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(460, 11, '25019', 'Alban', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(461, 11, '25035', 'Anapoima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(462, 11, '25040', 'Anolaima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(463, 11, '25053', 'Arbelaez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(464, 11, '25086', 'Beltran', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(465, 11, '25095', 'Bituima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(466, 11, '25099', 'Bojaca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(467, 11, '25120', 'Cabrera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(468, 11, '25123', 'Cachipay', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(469, 11, '25126', 'Cajica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(470, 11, '25148', 'Caparrapi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(471, 11, '25151', 'Caqueza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(472, 11, '25154', 'Carmen De Carupa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(473, 11, '25168', 'Chaguani', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(474, 11, '25175', 'Chia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(475, 11, '25178', 'Chipaque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(476, 11, '25181', 'Choachi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(477, 11, '25183', 'Choconta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(478, 11, '25200', 'Cogua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(479, 11, '25214', 'Cota', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(480, 11, '25224', 'Cucunuba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(481, 11, '25245', 'El Colegio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(482, 11, '25258', 'El Peñon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(483, 11, '25260', 'El Rosal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(484, 11, '25269', 'Facatativa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(485, 11, '25279', 'Fomeque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(486, 11, '25281', 'Fosca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(487, 11, '25286', 'Funza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(488, 11, '25288', 'Fuquene', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(489, 11, '25290', 'Fusagasuga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(490, 11, '25293', 'Gachala', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(491, 11, '25295', 'Gachancipa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(492, 11, '25297', 'Gacheta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(493, 11, '25299', 'Gama', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(494, 11, '25307', 'Girardot', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(495, 11, '25312', 'Granada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(496, 11, '25317', 'Guacheta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(497, 11, '25320', 'Guaduas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(498, 11, '25322', 'Guasca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(499, 11, '25324', 'Guataqui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(500, 11, '25326', 'Guatavita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(501, 11, '25328', 'Guayabal De Siquima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(502, 11, '25335', 'Guayabetal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(503, 11, '25339', 'Gutierrez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(504, 11, '25368', 'Jerusalen', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(505, 11, '25372', 'Junin', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(506, 11, '25377', 'La Calera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(507, 11, '25386', 'La Mesa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(508, 11, '25394', 'La Palma', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(509, 11, '25398', 'La Peña', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(510, 11, '25402', 'La Vega', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(511, 11, '25407', 'Lenguazaque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(512, 11, '25426', 'Macheta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(513, 11, '25430', 'Madrid', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(514, 11, '25436', 'Manta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(515, 11, '25438', 'Medina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(516, 11, '25473', 'Mosquera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(517, 11, '25483', 'Nariño', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(518, 11, '25486', 'Nemocon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(519, 11, '25488', 'Nilo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(520, 11, '25489', 'Nimaima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(521, 11, '25491', 'Nocaima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(522, 11, '25506', 'Venecia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(523, 11, '25513', 'Pacho', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(524, 11, '25518', 'Paime', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(525, 11, '25524', 'Pandi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(526, 11, '25530', 'Paratebueno', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(527, 11, '25535', 'Pasca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(528, 11, '25572', 'Puerto Salgar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(529, 11, '25580', 'Puli', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(530, 11, '25592', 'Quebradanegra', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(531, 11, '25594', 'Quetame', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(532, 11, '25596', 'Quipile', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(533, 11, '25599', 'Apulo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(534, 11, '25612', 'Ricaurte', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(535, 11, '25645', 'San Antonio Del Tequendama', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(536, 11, '25649', 'San Bernardo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(537, 11, '25653', 'San Cayetano', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(538, 11, '25658', 'San Francisco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(539, 11, '25662', 'San Juan De Rio Seco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(540, 11, '25718', 'Sasaima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(541, 11, '25736', 'Sesquile', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(542, 11, '25740', 'Sibate', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(543, 11, '25743', 'Silvania', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(544, 11, '25745', 'Simijaca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(545, 11, '25754', 'Soacha', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(546, 11, '25758', 'Sopo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(547, 11, '25769', 'Subachoque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(548, 11, '25772', 'Suesca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(549, 11, '25777', 'Supata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(550, 11, '25779', 'Susa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(551, 11, '25781', 'Sutatausa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(552, 11, '25785', 'Tabio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(553, 11, '25793', 'Tausa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(554, 11, '25797', 'Tena', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(555, 11, '25799', 'Tenjo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(556, 11, '25805', 'Tibacuy', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(557, 11, '25807', 'Tibirita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(558, 11, '25815', 'Tocaima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(559, 11, '25817', 'Tocancipa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(560, 11, '25823', 'Topaipi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(561, 11, '25839', 'Ubala', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(562, 11, '25841', 'Ubaque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(563, 11, '25843', 'Villa De San Diego De Ubate', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(564, 11, '25845', 'Une', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(565, 11, '25851', 'Utica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(566, 11, '25862', 'Vergara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(567, 11, '25867', 'Viani', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(568, 11, '25871', 'Villagomez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(569, 11, '25873', 'Villapinzon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(570, 11, '25875', 'Villeta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(571, 11, '25878', 'Viota', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(572, 11, '25885', 'Yacopi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(573, 11, '25898', 'Zipacon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(574, 11, '25899', 'Zipaquira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(575, 12, '27001', 'Quibdo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(576, 12, '27006', 'Acandi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(577, 12, '27025', 'Alto Baudo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(578, 12, '27050', 'Atrato', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(579, 12, '27073', 'Bagado', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(580, 12, '27075', 'Bahia Solano', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(581, 12, '27077', 'Bajo Baudo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(582, 12, '27099', 'Bojaya', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(583, 12, '27135', 'El Canton Del San Pablo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(584, 12, '27150', 'Carmen Del Darien', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(585, 12, '27160', 'Certegui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(586, 12, '27205', 'Condoto', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(587, 12, '27245', 'El Carmen De Atrato', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(588, 12, '27250', 'El Litoral Del San Juan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(589, 12, '27361', 'Istmina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(590, 12, '27372', 'Jurado', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(591, 12, '27413', 'Lloro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(592, 12, '27425', 'Medio Atrato', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(593, 12, '27430', 'Medio Baudo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(594, 12, '27450', 'Medio San Juan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(595, 12, '27491', 'Novita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(596, 12, '27495', 'Nuqui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(597, 12, '27580', 'Rio Iro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(598, 12, '27600', 'Rio Quito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(599, 12, '27615', 'Riosucio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(600, 12, '27660', 'San Jose Del Palmar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(601, 12, '27745', 'Sipi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(602, 12, '27787', 'Tado', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(603, 12, '27800', 'Unguia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(604, 12, '27810', 'Union Panamericana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(605, 13, '41001', 'Neiva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(606, 13, '41006', 'Acevedo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(607, 13, '41013', 'Agrado', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(608, 13, '41016', 'Aipe', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(609, 13, '41020', 'Algeciras', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(610, 13, '41026', 'Altamira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(611, 13, '41078', 'Baraya', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(612, 13, '41132', 'Campoalegre', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(613, 13, '41206', 'Colombia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(614, 13, '41244', 'Elias', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(615, 13, '41298', 'Garzon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(616, 13, '41306', 'Gigante', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(617, 13, '41319', 'Guadalupe', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(618, 13, '41349', 'Hobo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(619, 13, '41357', 'Iquira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(620, 13, '41359', 'Isnos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(621, 13, '41378', 'La Argentina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(622, 13, '41396', 'La Plata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(623, 13, '41483', 'Nataga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(624, 13, '41503', 'Oporapa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(625, 13, '41518', 'Paicol', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(626, 13, '41524', 'Palermo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(627, 13, '41530', 'Palestina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(628, 13, '41548', 'Pital', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(629, 13, '41551', 'Pitalito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(630, 13, '41615', 'Rivera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(631, 13, '41660', 'Saladoblanco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(632, 13, '41668', 'San Agustin', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05');
INSERT INTO `municipio` (`muniid`, `munidepaid`, `municodigo`, `muninombre`, `munihacepresencia`, `created_at`, `updated_at`) VALUES
(633, 13, '41676', 'Santa Maria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(634, 13, '41770', 'Suaza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(635, 13, '41791', 'Tarqui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(636, 13, '41797', 'Tesalia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(637, 13, '41799', 'Tello', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(638, 13, '41801', 'Teruel', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(639, 13, '41807', 'Timana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(640, 13, '41872', 'Villavieja', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(641, 13, '41885', 'Yaguara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(642, 14, '44001', 'Riohacha', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(643, 14, '44035', 'Albania', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(644, 14, '44078', 'Barrancas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(645, 14, '44090', 'Dibulla', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(646, 14, '44098', 'Distraccion', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(647, 14, '44110', 'El Molino', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(648, 14, '44279', 'Fonseca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(649, 14, '44378', 'Hatonuevo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(650, 14, '44420', 'La Jagua Del Pilar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(651, 14, '44430', 'Maicao', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(652, 14, '44560', 'Manaure', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(653, 14, '44650', 'San Juan Del Cesar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(654, 14, '44847', 'Uribia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(655, 14, '44855', 'Urumita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(656, 14, '44874', 'Villanueva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(657, 15, '47001', 'Santa Marta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(658, 15, '47030', 'Algarrobo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(659, 15, '47053', 'Aracataca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(660, 15, '47058', 'Ariguani', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(661, 15, '47161', 'Cerro San Antonio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(662, 15, '47170', 'Chibolo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(663, 15, '47189', 'Cienaga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(664, 15, '47205', 'Concordia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(665, 15, '47245', 'El Banco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(666, 15, '47258', 'El Piñon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(667, 15, '47268', 'El Reten', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(668, 15, '47288', 'Fundacion', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(669, 15, '47318', 'Guamal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(670, 15, '47460', 'Nueva Granada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(671, 15, '47541', 'Pedraza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(672, 15, '47545', 'Pijiño Del Carmen', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(673, 15, '47551', 'Pivijay', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(674, 15, '47555', 'Plato', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(675, 15, '47570', 'Puebloviejo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(676, 15, '47605', 'Remolino', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(677, 15, '47660', 'Sabanas De San Angel', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(678, 15, '47675', 'Salamina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(679, 15, '47692', 'San Sebastian De Buenavista', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(680, 15, '47703', 'San Zenon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(681, 15, '47707', 'Santa Ana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(682, 15, '47720', 'Santa Barbara De Pinto', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(683, 15, '47745', 'Sitionuevo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(684, 15, '47798', 'Tenerife', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(685, 15, '47960', 'Zapayan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(686, 15, '47980', 'Zona Bananera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(687, 16, '50001', 'Villavicencio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(688, 16, '50006', 'Acacias', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(689, 16, '50110', 'Barranca De Upia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(690, 16, '50124', 'Cabuyaro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(691, 16, '50150', 'Castilla La Nueva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(692, 16, '50223', 'Cubarral', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(693, 16, '50226', 'Cumaral', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(694, 16, '50245', 'El Calvario', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(695, 16, '50251', 'El Castillo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(696, 16, '50270', 'El Dorado', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(697, 16, '50287', 'Fuente De Oro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(698, 16, '50313', 'Granada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(699, 16, '50318', 'Guamal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(700, 16, '50325', 'Mapiripan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(701, 16, '50330', 'Mesetas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(702, 16, '50350', 'La Macarena', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(703, 16, '50370', 'Uribe', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(704, 16, '50400', 'Lejanias', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(705, 16, '50450', 'Puerto Concordia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(706, 16, '50568', 'Puerto Gaitan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(707, 16, '50573', 'Puerto Lopez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(708, 16, '50577', 'Puerto Lleras', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(709, 16, '50590', 'Puerto Rico', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(710, 16, '50606', 'Restrepo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(711, 16, '50680', 'San Carlos De Guaroa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(712, 16, '50683', 'San Juan De Arama', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(713, 16, '50686', 'San Juanito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(714, 16, '50689', 'San Martin', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(715, 16, '50711', 'Vistahermosa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(716, 17, '52001', 'Pasto', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(717, 17, '52019', 'Alban', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(718, 17, '52022', 'Aldana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(719, 17, '52036', 'Ancuya', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(720, 17, '52051', 'Arboleda', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(721, 17, '52079', 'Barbacoas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(722, 17, '52083', 'Belen', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(723, 17, '52110', 'Buesaco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(724, 17, '52203', 'Colon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(725, 17, '52207', 'Consaca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(726, 17, '52210', 'Contadero', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(727, 17, '52215', 'Cordoba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(728, 17, '52224', 'Cuaspud', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(729, 17, '52227', 'Cumbal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(730, 17, '52233', 'Cumbitara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(731, 17, '52240', 'Chachagsi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(732, 17, '52250', 'El Charco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(733, 17, '52254', 'El Peñol', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(734, 17, '52256', 'El Rosario', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(735, 17, '52258', 'El Tablon De Gomez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(736, 17, '52260', 'El Tambo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(737, 17, '52287', 'Funes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(738, 17, '52317', 'Guachucal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(739, 17, '52320', 'Guaitarilla', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(740, 17, '52323', 'Gualmatan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(741, 17, '52352', 'Iles', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(742, 17, '52354', 'Imues', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(743, 17, '52356', 'Ipiales', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(744, 17, '52378', 'La Cruz', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(745, 17, '52381', 'La Florida', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(746, 17, '52385', 'La Llanada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(747, 17, '52390', 'La Tola', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(748, 17, '52399', 'La Union', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(749, 17, '52405', 'Leiva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(750, 17, '52411', 'Linares', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(751, 17, '52418', 'Los Andes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(752, 17, '52427', 'Magsi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(753, 17, '52435', 'Mallama', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(754, 17, '52473', 'Mosquera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(755, 17, '52480', 'Nariño', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(756, 17, '52490', 'Olaya Herrera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(757, 17, '52506', 'Ospina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(758, 17, '52520', 'Francisco Pizarro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(759, 17, '52540', 'Policarpa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(760, 17, '52560', 'Potosi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(761, 17, '52565', 'Providencia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(762, 17, '52573', 'Puerres', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(763, 17, '52585', 'Pupiales', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(764, 17, '52612', 'Ricaurte', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(765, 17, '52621', 'Roberto Payan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(766, 17, '52678', 'Samaniego', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(767, 17, '52683', 'Sandona', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(768, 17, '52685', 'San Bernardo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(769, 17, '52687', 'San Lorenzo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(770, 17, '52693', 'San Pablo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(771, 17, '52694', 'San Pedro De Cartago', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(772, 17, '52696', 'Santa Barbara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(773, 17, '52699', 'Santacruz', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(774, 17, '52720', 'Sapuyes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(775, 17, '52786', 'Taminango', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(776, 17, '52788', 'Tangua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(777, 17, '52835', 'San Andres De Tumaco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(778, 17, '52838', 'Tuquerres', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(779, 17, '52885', 'Yacuanquer', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(780, 18, '54001', 'Cucuta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(781, 18, '54003', 'Abrego', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(782, 18, '54051', 'Arboledas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(783, 18, '54099', 'Bochalema', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(784, 18, '54109', 'Bucarasica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(785, 18, '54125', 'Cacota', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(786, 18, '54128', 'Cachira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(787, 18, '54172', 'Chinacota', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(788, 18, '54174', 'Chitaga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(789, 18, '54206', 'Convencion', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(790, 18, '54223', 'Cucutilla', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(791, 18, '54239', 'Durania', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(792, 18, '54245', 'El Carmen', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(793, 18, '54250', 'El Tarra', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(794, 18, '54261', 'El Zulia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(795, 18, '54313', 'Gramalote', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(796, 18, '54344', 'Hacari', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(797, 18, '54347', 'Herran', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(798, 18, '54377', 'Labateca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(799, 18, '54385', 'La Esperanza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(800, 18, '54398', 'La Playa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(801, 18, '54405', 'Los Patios', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(802, 18, '54418', 'Lourdes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(803, 18, '54480', 'Mutiscua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(804, 18, '54498', 'Ocaña', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(805, 18, '54518', 'Pamplona', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(806, 18, '54520', 'Pamplonita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(807, 18, '54553', 'Puerto Santander', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(808, 18, '54599', 'Ragonvalia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(809, 18, '54660', 'Salazar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(810, 18, '54670', 'San Calixto', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(811, 18, '54673', 'San Cayetano', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(812, 18, '54680', 'Santiago', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(813, 18, '54720', 'Sardinata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(814, 18, '54743', 'Silos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(815, 18, '54800', 'Teorama', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(816, 18, '54810', 'Tibu', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(817, 18, '54820', 'Toledo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(818, 18, '54871', 'Villa Caro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(819, 18, '54874', 'Villa Del Rosario', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(820, 19, '63001', 'Armenia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(821, 19, '63111', 'Buenavista', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(822, 19, '63130', 'Calarca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(823, 19, '63190', 'Circasia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(824, 19, '63212', 'Cordoba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(825, 19, '63272', 'Filandia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(826, 19, '63302', 'Genova', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(827, 19, '63401', 'La Tebaida', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(828, 19, '63470', 'Montenegro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(829, 19, '63548', 'Pijao', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(830, 19, '63594', 'Quimbaya', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(831, 19, '63690', 'Salento', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(832, 20, '66001', 'Pereira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(833, 20, '66045', 'Apia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(834, 20, '66075', 'Balboa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(835, 20, '66088', 'Belen De Umbria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(836, 20, '66170', 'Dosquebradas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(837, 20, '66318', 'Guatica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(838, 20, '66383', 'La Celia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(839, 20, '66400', 'La Virginia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(840, 20, '66440', 'Marsella', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(841, 20, '66456', 'Mistrato', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(842, 20, '66572', 'Pueblo Rico', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(843, 20, '66594', 'Quinchia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(844, 20, '66682', 'Santa Rosa De Cabal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(845, 20, '66687', 'Santuario', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(846, 21, '68001', 'Bucaramanga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(847, 21, '68013', 'Aguada', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(848, 21, '68020', 'Albania', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(849, 21, '68051', 'Aratoca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(850, 21, '68077', 'Barbosa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(851, 21, '68079', 'Barichara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(852, 21, '68081', 'Barrancabermeja', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(853, 21, '68092', 'Betulia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(854, 21, '68101', 'Bolivar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(855, 21, '68121', 'Cabrera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(856, 21, '68132', 'California', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(857, 21, '68147', 'Capitanejo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(858, 21, '68152', 'Carcasi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(859, 21, '68160', 'Cepita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(860, 21, '68162', 'Cerrito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(861, 21, '68167', 'Charala', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(862, 21, '68169', 'Charta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(863, 21, '68176', 'Chima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(864, 21, '68179', 'Chipata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(865, 21, '68190', 'Cimitarra', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(866, 21, '68207', 'Concepcion', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(867, 21, '68209', 'Confines', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(868, 21, '68211', 'Contratacion', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(869, 21, '68217', 'Coromoro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(870, 21, '68229', 'Curiti', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(871, 21, '68235', 'El Carmen De Chucuri', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(872, 21, '68245', 'El Guacamayo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(873, 21, '68250', 'El Peñon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(874, 21, '68255', 'El Playon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(875, 21, '68264', 'Encino', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(876, 21, '68266', 'Enciso', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(877, 21, '68271', 'Florian', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(878, 21, '68276', 'Floridablanca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(879, 21, '68296', 'Galan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(880, 21, '68298', 'Gambita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(881, 21, '68307', 'Giron', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(882, 21, '68318', 'Guaca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(883, 21, '68320', 'Guadalupe', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(884, 21, '68322', 'Guapota', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(885, 21, '68324', 'Guavata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(886, 21, '68327', 'Gsepsa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(887, 21, '68344', 'Hato', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(888, 21, '68368', 'Jesus Maria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(889, 21, '68370', 'Jordan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(890, 21, '68377', 'La Belleza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(891, 21, '68385', 'Landazuri', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(892, 21, '68397', 'La Paz', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(893, 21, '68406', 'Lebrija', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(894, 21, '68418', 'Los Santos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(895, 21, '68425', 'Macaravita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(896, 21, '68432', 'Malaga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(897, 21, '68444', 'Matanza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(898, 21, '68464', 'Mogotes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(899, 21, '68468', 'Molagavita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(900, 21, '68498', 'Ocamonte', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(901, 21, '68500', 'Oiba', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(902, 21, '68502', 'Onzaga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(903, 21, '68522', 'Palmar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(904, 21, '68524', 'Palmas Del Socorro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(905, 21, '68533', 'Paramo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(906, 21, '68547', 'Piedecuesta', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(907, 21, '68549', 'Pinchote', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(908, 21, '68572', 'Puente Nacional', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(909, 21, '68573', 'Puerto Parra', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(910, 21, '68575', 'Puerto Wilches', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(911, 21, '68615', 'Rionegro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(912, 21, '68655', 'Sabana De Torres', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(913, 21, '68669', 'San Andres', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(914, 21, '68673', 'San Benito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(915, 21, '68679', 'San Gil', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(916, 21, '68682', 'San Joaquin', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(917, 21, '68684', 'San Jose De Miranda', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(918, 21, '68686', 'San Miguel', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(919, 21, '68689', 'San Vicente De Chucuri', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(920, 21, '68705', 'Santa Barbara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(921, 21, '68720', 'Santa Helena Del Opon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(922, 21, '68745', 'Simacota', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(923, 21, '68755', 'Socorro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(924, 21, '68770', 'Suaita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(925, 21, '68773', 'Sucre', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(926, 21, '68780', 'Surata', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(927, 21, '68820', 'Tona', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(928, 21, '68855', 'Valle De San Jose', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(929, 21, '68861', 'Velez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(930, 21, '68867', 'Vetas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(931, 21, '68872', 'Villanueva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(932, 21, '68895', 'Zapatoca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(933, 22, '70001', 'Sincelejo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(934, 22, '70110', 'Buenavista', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(935, 22, '70124', 'Caimito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(936, 22, '70204', 'Coloso', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(937, 22, '70215', 'Corozal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(938, 22, '70221', 'Coveñas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(939, 22, '70230', 'Chalan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(940, 22, '70233', 'El Roble', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(941, 22, '70235', 'Galeras', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(942, 22, '70265', 'Guaranda', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(943, 22, '70400', 'La Union', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(944, 22, '70418', 'Los Palmitos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(945, 22, '70429', 'Majagual', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(946, 22, '70473', 'Morroa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(947, 22, '70508', 'Ovejas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(948, 22, '70523', 'Palmito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(949, 22, '70670', 'Sampues', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(950, 22, '70678', 'San Benito Abad', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(951, 22, '70702', 'San Juan De Betulia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(952, 22, '70708', 'San Marcos', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(953, 22, '70713', 'San Onofre', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(954, 22, '70717', 'San Pedro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(955, 22, '70742', 'San Luis De Since', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(956, 22, '70771', 'Sucre', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(957, 22, '70820', 'Santiago De Tolu', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(958, 22, '70823', 'Tolu Viejo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(959, 23, '73001', 'Ibague', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(960, 23, '73024', 'Alpujarra', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(961, 23, '73026', 'Alvarado', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(962, 23, '73030', 'Ambalema', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(963, 23, '73043', 'Anzoategui', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(964, 23, '73055', 'Armero', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(965, 23, '73067', 'Ataco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(966, 23, '73124', 'Cajamarca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(967, 23, '73148', 'Carmen De Apicala', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(968, 23, '73152', 'Casabianca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(969, 23, '73168', 'Chaparral', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(970, 23, '73200', 'Coello', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(971, 23, '73217', 'Coyaima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(972, 23, '73226', 'Cunday', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(973, 23, '73236', 'Dolores', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(974, 23, '73268', 'Espinal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(975, 23, '73270', 'Falan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(976, 23, '73275', 'Flandes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(977, 23, '73283', 'Fresno', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(978, 23, '73319', 'Guamo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(979, 23, '73347', 'Herveo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(980, 23, '73349', 'Honda', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(981, 23, '73352', 'Icononzo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(982, 23, '73408', 'Lerida', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(983, 23, '73411', 'Libano', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(984, 23, '73443', 'Mariquita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(985, 23, '73449', 'Melgar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(986, 23, '73461', 'Murillo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(987, 23, '73483', 'Natagaima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(988, 23, '73504', 'Ortega', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(989, 23, '73520', 'Palocabildo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(990, 23, '73547', 'Piedras', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(991, 23, '73555', 'Planadas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(992, 23, '73563', 'Prado', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(993, 23, '73585', 'Purificacion', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(994, 23, '73616', 'Rioblanco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(995, 23, '73622', 'Roncesvalles', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(996, 23, '73624', 'Rovira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(997, 23, '73671', 'Saldaña', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(998, 23, '73675', 'San Antonio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(999, 23, '73678', 'San Luis', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1000, 23, '73686', 'Santa Isabel', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1001, 23, '73770', 'Suarez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1002, 23, '73854', 'Valle De San Juan', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1003, 23, '73861', 'Venadillo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1004, 23, '73870', 'Villahermosa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1005, 23, '73873', 'Villarrica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1006, 24, '76001', 'Cali', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1007, 24, '76020', 'Alcala', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1008, 24, '76036', 'Andalucia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1009, 24, '76041', 'Ansermanuevo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1010, 24, '76054', 'Argelia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1011, 24, '76100', 'Bolivar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1012, 24, '76109', 'Buenaventura', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1013, 24, '76111', 'Guadalajara De Buga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1014, 24, '76113', 'Bugalagrande', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1015, 24, '76122', 'Caicedonia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1016, 24, '76126', 'Calima', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1017, 24, '76130', 'Candelaria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1018, 24, '76147', 'Cartago', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1019, 24, '76233', 'Dagua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1020, 24, '76243', 'El Aguila', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1021, 24, '76246', 'El Cairo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1022, 24, '76248', 'El Cerrito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1023, 24, '76250', 'El Dovio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1024, 24, '76275', 'Florida', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1025, 24, '76306', 'Ginebra', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1026, 24, '76318', 'Guacari', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1027, 24, '76364', 'Jamundi', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1028, 24, '76377', 'La Cumbre', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1029, 24, '76400', 'La Union', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1030, 24, '76403', 'La Victoria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1031, 24, '76497', 'Obando', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1032, 24, '76520', 'Palmira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1033, 24, '76563', 'Pradera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1034, 24, '76606', 'Restrepo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1035, 24, '76616', 'Riofrio', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1036, 24, '76622', 'Roldanillo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1037, 24, '76670', 'San Pedro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1038, 24, '76736', 'Sevilla', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1039, 24, '76823', 'Toro', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1040, 24, '76828', 'Trujillo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1041, 24, '76834', 'Tulua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1042, 24, '76845', 'Ulloa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1043, 24, '76863', 'Versalles', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1044, 24, '76869', 'Vijes', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1045, 24, '76890', 'Yotoco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1046, 24, '76892', 'Yumbo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1047, 24, '76895', 'Zarzal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1048, 25, '81001', 'Arauca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1049, 25, '81065', 'Arauquita', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1050, 25, '81220', 'Cravo Norte', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1051, 25, '81300', 'Fortul', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1052, 25, '81591', 'Puerto Rondon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1053, 25, '81736', 'Saravena', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1054, 25, '81794', 'Tame', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1055, 26, '85001', 'Yopal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1056, 26, '85010', 'Aguazul', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1057, 26, '85015', 'Chameza', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1058, 26, '85125', 'Hato Corozal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1059, 26, '85136', 'La Salina', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1060, 26, '85139', 'Mani', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1061, 26, '85162', 'Monterrey', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1062, 26, '85225', 'Nunchia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1063, 26, '85230', 'Orocue', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1064, 26, '85250', 'Paz De Ariporo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1065, 26, '85263', 'Pore', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1066, 26, '85279', 'Recetor', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1067, 26, '85300', 'Sabanalarga', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1068, 26, '85315', 'Sacama', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1069, 26, '85325', 'San Luis De Palenque', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1070, 26, '85400', 'Tamara', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1071, 26, '85410', 'Tauramena', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1072, 26, '85430', 'Trinidad', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1073, 26, '85440', 'Villanueva', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1074, 27, '86001', 'Mocoa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1075, 27, '86219', 'Colon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1076, 27, '86320', 'Orito', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1077, 27, '86568', 'Puerto Asis', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1078, 27, '86569', 'Puerto Caicedo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1079, 27, '86571', 'Puerto Guzman', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1080, 27, '86573', 'Leguizamo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1081, 27, '86749', 'Sibundoy', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1082, 27, '86755', 'San Francisco', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1083, 27, '86757', 'San Miguel', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1084, 27, '86760', 'Santiago', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1085, 27, '86865', 'Valle Del Guamuez', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1086, 27, '86885', 'Villagarzon', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1087, 28, '88001', 'San Andres', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1088, 28, '88564', 'Providencia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1089, 29, '91001', 'Leticia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1090, 29, '91263', 'El Encanto', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1091, 29, '91405', 'La Chorrera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1092, 29, '91407', 'La Pedrera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1093, 29, '91430', 'La Victoria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1094, 29, '91460', 'Miriti - Parana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1095, 29, '91530', 'Puerto Alegria', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1096, 29, '91536', 'Puerto Arica', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1097, 29, '91540', 'Puerto Nariño', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1098, 29, '91669', 'Puerto Santander', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1099, 29, '91798', 'Tarapaca', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1100, 30, '94001', 'Inirida', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1101, 30, '94343', 'Barranco Minas', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1102, 30, '94663', 'Mapiripana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1103, 30, '94883', 'San Felipe', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1104, 30, '94884', 'Puerto Colombia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1105, 30, '94885', 'La Guadalupe', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1106, 30, '94886', 'Cacahual', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1107, 30, '94887', 'Pana Pana', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1108, 30, '94888', 'Morichal', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1109, 31, '95001', 'San Jose Del Guaviare', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1110, 31, '95015', 'Calamar', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1111, 31, '95025', 'El Retorno', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1112, 31, '95200', 'Miraflores', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1113, 32, '97001', 'Mitu', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1114, 32, '97161', 'Caruru', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1115, 32, '97511', 'Pacoa', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1116, 32, '97666', 'Taraira', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1117, 32, '97777', 'Papunaua', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1118, 32, '97889', 'Yavarate', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1119, 33, '99001', 'Puerto Carreño', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1120, 33, '99524', 'La Primavera', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1121, 33, '99624', 'Santa Rosalia', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05'),
(1122, 33, '99773', 'Cumaribo', 0, '2023-10-10 01:24:05', '2023-10-10 01:24:05');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla persona',
  `carlabid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del cargo laboral',
  `tipideid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de identificación',
  `tirelaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de realación laboral',
  `persdepaidnacimiento` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento de nacimiento del documento',
  `persmuniidnacimiento` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio de nacimiento del documento',
  `persdepaidexpedicion` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'Identificador del departamento de expedición del documento',
  `persmuniidexpedicion` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Identificador del municipio de expedición del documento',
  `persdocumento` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de documento de la persona',
  `persprimernombre` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Primer nombre de la persona',
  `perssegundonombre` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Segundo nombre de la persona',
  `persprimerapellido` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Primer apellido de la persona',
  `perssegundoapellido` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Segundo apellido de la persona',
  `persfechanacimiento` date DEFAULT NULL COMMENT 'Fecha de nacimiento de la persona',
  `persdireccion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Determina el genero de la persona',
  `perscorreoelectronico` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Correo electrónico de la persona',
  `persfechadexpedicion` date DEFAULT NULL COMMENT 'Fecha de nacimiento de la persona',
  `persnumerotelefonofijo` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de teléfono fijo de la persona',
  `persnumerocelular` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Número de teléfono fijo de la persona',
  `persgenero` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Determina el genero de la persona',
  `persrutafoto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la foto de la persona',
  `persrutafirma` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ruta de la firma digital de la persona para la gestión documental',
  `persactiva` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la persona se encuentra activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`persid`, `carlabid`, `tipideid`, `tirelaid`, `persdepaidnacimiento`, `persmuniidnacimiento`, `persdepaidexpedicion`, `persmuniidexpedicion`, `persdocumento`, `persprimernombre`, `perssegundonombre`, `persprimerapellido`, `perssegundoapellido`, `persfechanacimiento`, `persdireccion`, `perscorreoelectronico`, `persfechadexpedicion`, `persnumerotelefonofijo`, `persnumerocelular`, `persgenero`, `persrutafoto`, `persrutafirma`, `persactiva`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 18, 789, 18, 804, '1978917', 'RAMÓN', 'DAVID', 'SALAZAR', 'RINCÓN', '1979-08-29', 'Calle 4 36 49', 'radasa10@hotmail.com', '1998-04-16', '3204018506', '3204018506', 'M', NULL, 'Firma_1978917.png', 1, '2023-10-10 01:24:21', '2023-10-10 01:24:21'),
(2, 1, 1, 1, 9, 416, 9, 416, '5036123', 'LUIS', 'MANUEL', 'ASCANIO', 'CLARO', '1979-08-29', 'Calle 4 36 49', 'luisangel330@hotmail.com', '1998-04-16', '3163374329', '3163374329', 'M', NULL, NULL, 1, '2023-10-10 01:24:21', '2023-10-10 01:24:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `peradodocumento` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Número de documento de la persona',
  `peradoprimernombre` varchar(70) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradosegundonombre` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradoprimerapellido` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradosegundoapellido` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Nombre de la persona que radica el documento',
  `peradodireccion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Dirección de la persona que radica el documento',
  `peradotelefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Telefóno de la persona que radica el documento',
  `peradocorreo` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'correo de la persona que radica el documento',
  `peradocodigodocumental` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Código documental proveniente de la emprea que emite el documento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocentanexo`
--

CREATE TABLE `radicaciondocentanexo` (
  `radoeaid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla radicacion documento entrante dependencia',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `radoeanombreanexooriginal` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el documento',
  `radoeanombreanexoeditado` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre con el cual se ha subido el documento pero editado',
  `radoearutaanexo` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Ruta enfuscada del anexo del radicado',
  `radoearequiereradicado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el adjunto requiere radicado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `radicaciondocentcambioestado`
--

CREATE TABLE `radicaciondocentcambioestado` (
  `radeceid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla cambio estado radicacion documento entrante',
  `radoenid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador del radicado del documento entrante',
  `tierdeid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de estado radicación documento entrante',
  `radeceusuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del radicado',
  `radecefechahora` datetime NOT NULL COMMENT 'Fecha y hora en la cual se crea el cambio estado del radicado',
  `radeceobservacion` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación del cambio estado radicado',
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
  `depeid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la dependencia',
  `usuaid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del usuario que crea el estado del documento',
  `radoenconsecutivo` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Consecutivo del radicado',
  `radoenanio` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Año en el cual se crea el radicado',
  `radoenfechahoraradicado` datetime NOT NULL COMMENT 'Fecha y hora en la cual se radica el documento',
  `radoenfechamaximarespuesta` date NOT NULL COMMENT 'Fecha máxima para emitir la respuesta del radicado del documento',
  `radoenfechadocumento` date NOT NULL COMMENT 'Fecha que contiene el documento',
  `radoenfechallegada` date NOT NULL COMMENT 'Fecha de llegada del documento',
  `radoenpersonaentregadocumento` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la persona que radica el documento',
  `radoenasunto` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Asunto que contiene el documento para radicar',
  `radoentieneanexo` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado tiene anexo',
  `radoendescripcionanexo` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Descripción del anexo',
  `radoentienecopia` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el radicado tiene copia',
  `radoenobservacion` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Observación general del radicado del documento',
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
  `rolnombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del rol',
  `rolactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el rol se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`rolid`, `rolnombre`, `rolactivo`, `created_at`, `updated_at`) VALUES
(1, 'Super administrador', 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(2, 'Administrador', 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(3, 'Secretaria', 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(4, 'Jefe', 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(5, 'Radicador', 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35'),
(6, 'Coordinador del archivo histórico', 1, '2023-10-10 01:25:35', '2023-10-10 01:25:35');

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
(1, 1, 22),
(2, 1, 4),
(3, 1, 17),
(4, 1, 8),
(5, 1, 11),
(6, 1, 14),
(7, 1, 19),
(8, 1, 20),
(9, 1, 15),
(10, 1, 12),
(11, 1, 5),
(12, 1, 7),
(13, 1, 2),
(14, 1, 21),
(15, 1, 3),
(16, 1, 1),
(17, 1, 10),
(18, 1, 9),
(19, 1, 13),
(20, 1, 16),
(21, 1, 18),
(22, 4, 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seriedocumental`
--

CREATE TABLE `seriedocumental` (
  `serdocid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla serie documental',
  `serdoccodigo` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código de la serie',
  `serdocnombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la serie',
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
(1, '001', 'Acta', 360, 720, 1440, 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(2, '002', 'Certificado', 360, 720, 1440, 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(3, '003', 'Circular', 360, 720, 1440, 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(4, '004', 'Citación', 360, 720, 1440, 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(5, '005', 'Constancia', 360, 720, 1440, 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(6, '006', 'Oficio', 360, 720, 1440, 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(7, '007', 'Resolucion', 360, 720, 1440, 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subseriedocumental`
--

CREATE TABLE `subseriedocumental` (
  `susedoid` mediumint(8) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla sub serie documental',
  `serdocid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla serie documental',
  `tipdocid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo documento',
  `susedocodigo` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código de la sub serie documental',
  `susedonombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la sub serie documental',
  `susedopermiteeliminar` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si la sub serie documental se puede eliminar',
  `susedoactiva` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si la sub serie documental esta activa',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `subseriedocumental`
--

INSERT INTO `subseriedocumental` (`susedoid`, `serdocid`, `tipdocid`, `susedocodigo`, `susedonombre`, `susedopermiteeliminar`, `susedoactiva`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '01', 'Acta universal', 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(2, 2, 2, '01', 'Certificado universal', 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(3, 3, 3, '01', 'Circular universal', 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(4, 4, 4, '01', 'Citación universal', 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(5, 5, 5, '01', 'Constancia universal', 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(6, 6, 6, '01', 'Oficio universal', 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19'),
(7, 7, 7, '01', 'Resolución universal', 0, 1, '2023-10-10 01:24:19', '2023-10-10 01:24:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoacta`
--

CREATE TABLE `tipoacta` (
  `tipactid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de acta',
  `tipactnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de acta'
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
  `ticaubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de caja ubicación',
  `ticaubnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de caja ubicación'
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
  `ticrubid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de carpeta ubicación',
  `ticrubnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de carpeta ubicación'
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
-- Estructura de tabla para la tabla `tipodespedida`
--

CREATE TABLE `tipodespedida` (
  `tipdesid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo despedida',
  `tipdesnombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo despedida',
  `tipdesactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de despedida se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipodespedida`
--

INSERT INTO `tipodespedida` (`tipdesid`, `tipdesnombre`, `tipdesactivo`, `created_at`, `updated_at`) VALUES
(1, 'Atentamente,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(2, 'Atentamente le saluda,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(3, 'Atentamente se despide,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(4, 'Agradecidos por su amabilidad,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(5, 'Agradecidos por su atención,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(6, 'Cordialmente se despide,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(7, 'Sin otro particular por el momento,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(8, 'Reiteramos nuestros mas cordiales saludos,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(9, 'Nuestra consideracion mas distinguida,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(10, 'En espera de sus noticias le saludamos,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(11, 'Un atento saludo,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(12, 'Agradeciendo su valiosa colaboración,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(13, 'En espera de una respuesta,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(14, 'Quedamos a su disposicion por cuanto puedan necesitar', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(15, 'Les quedamos muy agradecidos por su colaboración', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17'),
(16, 'Hasta otra oportunidad,', 1, '2023-10-10 01:24:17', '2023-10-10 01:24:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodestino`
--

CREATE TABLE `tipodestino` (
  `tipdetid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de destino',
  `tipdetnombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de destino'
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
  `tipdoccodigo` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código del tipo documental',
  `tipdocnombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo documental',
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
-- Estructura de tabla para la tabla `tipoestadodocumento`
--

CREATE TABLE `tipoestadodocumento` (
  `tiesdoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla tipo estado documento',
  `tiesdonombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo estado documento'
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
-- Estructura de tabla para la tabla `tipoestadoraddocentrante`
--

CREATE TABLE `tipoestadoraddocentrante` (
  `tierdeid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de estado documento entrante',
  `tierdenombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de estado documento entrante'
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
-- Estructura de tabla para la tabla `tipoestantearchivador`
--

CREATE TABLE `tipoestantearchivador` (
  `tiesarid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo estante archivador',
  `tiesarnombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo  estante archivador',
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
  `tipidesigla` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sigla del tipo de identificación',
  `tipidenombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de identificación'
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
-- Estructura de tabla para la tabla `tipomedio`
--

CREATE TABLE `tipomedio` (
  `tipmedid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de medio',
  `tipmednombre` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de medio'
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
-- Estructura de tabla para la tabla `tipopersonadocumental`
--

CREATE TABLE `tipopersonadocumental` (
  `tipedoid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de persona documental',
  `tipedonombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de persona documental',
  `tipedoactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de persona documental se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tipopersonadocumental`
--

INSERT INTO `tipopersonadocumental` (`tipedoid`, `tipedonombre`, `tipedoactivo`, `created_at`, `updated_at`) VALUES
(1, 'El señor', 1, '2023-10-10 01:24:20', '2023-10-10 01:24:20'),
(2, 'El doctor', 1, '2023-10-10 01:24:20', '2023-10-10 01:24:20'),
(3, 'La doctora', 1, '2023-10-10 01:24:20', '2023-10-10 01:24:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiporelacionlaboral`
--

CREATE TABLE `tiporelacionlaboral` (
  `tirelaid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de realación laboral',
  `tirelanombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de relación laboral'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tiporelacionlaboral`
--

INSERT INTO `tiporelacionlaboral` (`tirelaid`, `tirelanombre`) VALUES
(1, 'Jefe'),
(2, 'Secretaria'),
(3, 'Usuario Invitado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposaludo`
--

CREATE TABLE `tiposaludo` (
  `tipsalid` smallint(5) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de saludo',
  `tipsalnombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de saludo',
  `tipsalactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el tipo de saludo se encuentra activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `tiposaludo`
--

INSERT INTO `tiposaludo` (`tipsalid`, `tipsalnombre`, `tipsalactivo`, `created_at`, `updated_at`) VALUES
(1, 'Apreciado señor,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(2, 'Apreciada señora,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(3, 'Apreciado proveedor,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(4, 'Cordial saludo,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(5, 'Estimado señor,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(6, 'Estimada señora,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(7, 'Estimado cliente,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(8, 'Estimado consultante,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(9, 'Distinguido señor,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(10, 'Distinguida señora,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(11, 'Distinguidos señores,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(12, 'Notable señora,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(13, 'Notables señores,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(14, 'Respetable señor,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(15, 'Respetable señora,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(16, 'Respetables señores,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(17, 'Amable señor,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(18, 'Amable señora,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18'),
(19, 'Notable señor,', 1, '2023-10-10 01:24:18', '2023-10-10 01:24:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipotramite`
--

CREATE TABLE `tipotramite` (
  `tiptraid` tinyint(3) UNSIGNED NOT NULL COMMENT 'Identificador del tipo de trámite',
  `tiptranombre` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del tipo de trámite'
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
-- Estructura de tabla para la tabla `tokenfirmapersona`
--

CREATE TABLE `tokenfirmapersona` (
  `tofipeid` bigint(20) UNSIGNED NOT NULL COMMENT 'Identificador de la tabla token firma',
  `persid` int(10) UNSIGNED NOT NULL COMMENT 'Identificador de la persona',
  `tofipetoken` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Token creado aleatoriamente para validar la firma',
  `tofipefechahoranotificacion` datetime NOT NULL COMMENT 'Fecha y hora de la cual se envio la notifiación',
  `tofipefechahoramaxvalidez` datetime NOT NULL COMMENT 'Fecha y hora maxima de validez del token',
  `tofipemensajecorreo` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contendio de la información enviada al correo',
  `tofipemensajecelular` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Contendio de la información enviada al celular',
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
  `usuanombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre del usuario',
  `usuaapellidos` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Apellidos del usuario',
  `usuaemail` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Correo del usuario',
  `usuanick` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nick del usuario',
  `usuaalias` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Alias para colocar como transcriptor del documento en la gestion documental',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Password del usuario',
  `usuacambiarpassword` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el usuario debe cambar la contraseña para poder inciar sesión',
  `usuabloqueado` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Determina si el usuario esta bloqueado',
  `usuaactivo` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Determina si el usuario esta activo',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuaid`, `persid`, `usuanombre`, `usuaapellidos`, `usuaemail`, `usuanick`, `usuaalias`, `password`, `usuacambiarpassword`, `usuabloqueado`, `usuaactivo`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'RAMÓN DAVID', 'SALAZAR RINCÓN', 'radasa10@hotmail.com', 'RSALAZAR', 'Salazar R.', '$2y$10$XNIMMl9TnWyBdmBT30ArYOzM///pVkLJ2bxfTQJNgn98TwwXMg71m', 0, 0, 1, NULL, NULL, NULL);

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
(1, 1, 1);

--
-- Índices para tablas volcadas
--

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
-- Indices de la tabla `cargolaboral`
--
ALTER TABLE `cargolaboral`
  ADD PRIMARY KEY (`carlabid`),
  ADD KEY `pk_carlab` (`carlabid`);

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
  ADD KEY `fk_tirelapers` (`tirelaid`),
  ADD KEY `fk_depapersnac` (`persdepaidnacimiento`),
  ADD KEY `fk_munipersnac` (`persmuniidnacimiento`),
  ADD KEY `fk_depapersexp` (`persdepaidexpedicion`),
  ADD KEY `fk_munipersexp` (`persmuniidexpedicion`);

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
  ADD KEY `fk_deperadoen` (`depeid`),
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
-- Indices de la tabla `seriedocumental`
--
ALTER TABLE `seriedocumental`
  ADD PRIMARY KEY (`serdocid`),
  ADD UNIQUE KEY `uk_serie` (`serdoccodigo`);

--
-- Indices de la tabla `subseriedocumental`
--
ALTER TABLE `subseriedocumental`
  ADD PRIMARY KEY (`susedoid`),
  ADD UNIQUE KEY `uk_serdocsusedo` (`serdocid`,`susedocodigo`),
  ADD KEY `fk_tipdocsusedo` (`tipdocid`);

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
-- Indices de la tabla `tipoestadodocumento`
--
ALTER TABLE `tipoestadodocumento`
  ADD PRIMARY KEY (`tiesdoid`);

--
-- Indices de la tabla `tipoestadoraddocentrante`
--
ALTER TABLE `tipoestadoraddocentrante`
  ADD PRIMARY KEY (`tierdeid`);

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
-- Indices de la tabla `tipomedio`
--
ALTER TABLE `tipomedio`
  ADD PRIMARY KEY (`tipmedid`);

--
-- Indices de la tabla `tipopersonadocumental`
--
ALTER TABLE `tipopersonadocumental`
  ADD PRIMARY KEY (`tipedoid`),
  ADD KEY `pk_tipedo` (`tipedoid`);

--
-- Indices de la tabla `tiporelacionlaboral`
--
ALTER TABLE `tiporelacionlaboral`
  ADD PRIMARY KEY (`tirelaid`);

--
-- Indices de la tabla `tiposaludo`
--
ALTER TABLE `tiposaludo`
  ADD PRIMARY KEY (`tipsalid`);

--
-- Indices de la tabla `tipotramite`
--
ALTER TABLE `tipotramite`
  ADD PRIMARY KEY (`tiptraid`);

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
  ADD KEY `fk_persusua` (`persid`);

--
-- Indices de la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD PRIMARY KEY (`usurolid`),
  ADD KEY `fk_usuausurol` (`usurolusuaid`),
  ADD KEY `fk_rolusurol` (`usurolrolid`),
  ADD KEY `pk_usurol` (`usurolid`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

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
-- AUTO_INCREMENT de la tabla `cargolaboral`
--
ALTER TABLE `cargolaboral`
  MODIFY `carlabid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla cargo laboral', AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT de la tabla `dependencia`
--
ALTER TABLE `dependencia`
  MODIFY `depeid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla cargo dependencia';

--
-- AUTO_INCREMENT de la tabla `dependenciapersona`
--
ALTER TABLE `dependenciapersona`
  MODIFY `depperid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla dependencia persona';

--
-- AUTO_INCREMENT de la tabla `dependenciasubseriedocumental`
--
ALTER TABLE `dependenciasubseriedocumental`
  MODIFY `desusdid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla dependencia sub serie documental';

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `emprid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla empresa', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `festivo`
--
ALTER TABLE `festivo`
  MODIFY `festid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla festivo';

--
-- AUTO_INCREMENT de la tabla `funcionalidad`
--
ALTER TABLE `funcionalidad`
  MODIFY `funcid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla funcionalidad', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `historialcontrasena`
--
ALTER TABLE `historialcontrasena`
  MODIFY `hisconid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla historial de contrasena';

--
-- AUTO_INCREMENT de la tabla `informacionnotificacioncorreo`
--
ALTER TABLE `informacionnotificacioncorreo`
  MODIFY `innocoid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla informacion notificación correo ', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ingresosistema`
--
ALTER TABLE `ingresosistema`
  MODIFY `ingsisid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla ingreso sistema', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `intentosfallidos`
--
ALTER TABLE `intentosfallidos`
  MODIFY `intfalid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla intentos fallidos';

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `modulo`
--
ALTER TABLE `modulo`
  MODIFY `moduid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla módulo', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `muniid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla municipio', AUTO_INCREMENT=1123;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `persid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla persona', AUTO_INCREMENT=3;

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
-- AUTO_INCREMENT de la tabla `radicaciondocentanexo`
--
ALTER TABLE `radicaciondocentanexo`
  MODIFY `radoeaid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla radicacion documento entrante dependencia';

--
-- AUTO_INCREMENT de la tabla `radicaciondocentcambioestado`
--
ALTER TABLE `radicaciondocentcambioestado`
  MODIFY `radeceid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla cambio estado radicacion documento entrante';

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
  MODIFY `rolfunid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla rol funcionalidad', AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `seriedocumental`
--
ALTER TABLE `seriedocumental`
  MODIFY `serdocid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla serie documental', AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `subseriedocumental`
--
ALTER TABLE `subseriedocumental`
  MODIFY `susedoid` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla sub serie documental', AUTO_INCREMENT=8;

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
  MODIFY `tiesarid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador del tipo estante archivador', AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipopersonadocumental`
--
ALTER TABLE `tipopersonadocumental`
  MODIFY `tipedoid` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador del tipo de persona documental', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tiposaludo`
--
ALTER TABLE `tiposaludo`
  MODIFY `tipsalid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador del tipo de saludo', AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `tokenfirmapersona`
--
ALTER TABLE `tokenfirmapersona`
  MODIFY `tofipeid` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla token firma';

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuaid` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla usuario', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  MODIFY `usurolid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Identificador de la tabla usuario rol', AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

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
  ADD CONSTRAINT `fk_tirelapers` FOREIGN KEY (`tirelaid`) REFERENCES `tiporelacionlaboral` (`tirelaid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `personaradicadocumento`
--
ALTER TABLE `personaradicadocumento`
  ADD CONSTRAINT `fk_tipideperado` FOREIGN KEY (`tipideid`) REFERENCES `tipoidentificacion` (`tipideid`) ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_deperadoen` FOREIGN KEY (`depeid`) REFERENCES `dependencia` (`depeid`) ON UPDATE CASCADE,
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
-- Filtros para la tabla `subseriedocumental`
--
ALTER TABLE `subseriedocumental`
  ADD CONSTRAINT `fk_serdocsusedo` FOREIGN KEY (`serdocid`) REFERENCES `seriedocumental` (`serdocid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tipdocsusedo` FOREIGN KEY (`tipdocid`) REFERENCES `tipodocumental` (`tipdocid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `tokenfirmapersona`
--
ALTER TABLE `tokenfirmapersona`
  ADD CONSTRAINT `fk_perstofipe` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_persusua` FOREIGN KEY (`persid`) REFERENCES `persona` (`persid`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuariorol`
--
ALTER TABLE `usuariorol`
  ADD CONSTRAINT `fk_rolusurol` FOREIGN KEY (`usurolrolid`) REFERENCES `rol` (`rolid`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuausurol` FOREIGN KEY (`usurolusuaid`) REFERENCES `usuario` (`usuaid`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
